#ifndef __HELPERS_H__
#define __HELPERS_H__

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <arpa/inet.h>
#include <netinet/in.h>
#include <pthread.h>
#include <signal.h>
#include <mqueue.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <sys/time.h>

#define DATA_SIZE    65535
#define CHUNK_SIZE   512
#define PORT         6666
#define BUFSIZE      255
#define MAX_MSG      10
#define COMMAND_SIZE 4

#define CLIETN_TABLE_NAME_SIZE   20
#define CLIENT_NICK_SIZE         31
#define CLIENT_PASS_SIZE         31
#define CLIENT_ID_SIZE           10 

#define PRIVATE 0
#define PUBLICK 1

#define MQUEUE         "/client_queue"
#define BANNER         "**************\n  CRYPT_FACK  \n**************\n"
#define WELCOME_FORM   "\n1 - Sign in\n2 - Sign up\n3 - Exit\n> "
#define AUTH_FORM_NICK "Enter nickname: "
#define AUTH_FORM_PASS "Enter password: "
#define USER_MAIN_MENU "\n1 - Load public data\n2 - Load private data\n3 -\
 Show public data\n4 - Show private data\n5 - Logout\n> "
#define PUB_DATA_MENU  "\n1 - All\n2 - Choose user\n3 - Self\n4 - Exit\n> "
#define FORMAT_CHOISE  "\n1 - Encrypt format\n2 - Decrypt format\n> "

pthread_t server_tid;
pthread_t connection_tid;
pthread_t client_tid;

mqd_t mq;

int server_sockfd;
int client_sockfd;

pid_t parent_pid;
pid_t client_pid;

#define ERR_LOG(str) printf("<%s (%d)> %s\n", __func__, client_pid, str)

#define SEND(data) \
  do \
  { \
    if (-1 == send(client_sockfd, data, sizeof(data), 0)) \
    { \
      return -1; \
    } \
  } while(0) \

#define SEND_S(data, size) \
  do \
  { \
    if (-1 == send(client_sockfd, data, size, 0)) \
    { \
      return -1; \
    } \
  } while(0) \


#define SEND_DATA(data) \
  do \
  { \
    char * ptr = data; \
    int send_size = 0; \
    int data_size = strlen(data); \
    while (data_size > 0) \
    { \
      send_size = send(client_sockfd, ptr, data_size, 0); \
      ptr += send_size; \
      data_size -= send_size; \
    } \
  } while(0) \

#define SEND_C(data) \
  do \
  { \
    if (-1 == send(client_sockfd, data, sizeof(data), 0)) \
    { \
      mysql_free_result(result); \
      return -1; \
    } \
  } while(0) \

#define SEND_M(data) \
  do \
  { \
    if (-1 == send(client_sockfd, data, sizeof(data), 0)) \
    { \
      return close_all(-1); \
    } \
  } while(0) \

#define RECV_DATA(data) \
  do \
  { \
    if (-1 == recv_timeout(client_sockfd, data, 2)) \
    { \
      return -1; \
    } \
  } while (0)


#define RECV(data, size) \
  do \
  { \
    if (recv(client_sockfd, data, size, 0) < 1) \
    { \
       return -1; \
    } \
  } while (0)

#define RECV_C(data, size) \
  do \
  { \
    if (recv(client_sockfd, data, size, 0) < 1) \
    { \
       mysql_free_result(result); \
       return -1; \
    } \
  } while (0)

#define RECV_M(data, size) \
  do \
  { \
    if (recv(client_sockfd, data, size, 0) < 1) \
    { \
       return close_all(-1); \
    } \
  } while (0)


#define ERR_MSG(data) SEND(data)

void attr_init (struct mq_attr * attr);
void sigquit_handler (int sig);
int recv_timeout(int socket, char * data, int timeout);

typedef struct
{
  char nick[CLIENT_NICK_SIZE];
  char pass[CLIENT_PASS_SIZE];
  char table[CLIETN_TABLE_NAME_SIZE];
  char id[CLIENT_ID_SIZE];
} user_t;

#endif // __HELPERS_H__
