#include "helpers.h"
#include "client.h"
#include "db_api.h"

#define SHUT "/shut\n"
#define HELP "/help\n"

void * connection_control(void * data)
{
  int mq_data;
  int rc = 0;

  while(1)
  {
    if (mq_receive(mq, (char *)&mq_data, sizeof(int), NULL) == -1)
    {
      ERR_LOG("Can't recv from queue message");
      kill(-parent_pid, SIGQUIT);
      exit(-1);
    }

    int sockfd = mq_data;

    rc = fork();
    if (0 == rc)
    {
      close(server_sockfd);
      client(sockfd);
      exit(0);
    }
    else if (-1 == rc)
    {
      ERR_LOG("fork error");
      kill(-parent_pid, SIGQUIT);
    }
 
    signal(SIGCHLD, SIG_IGN);
    close(sockfd);
  }
  return 0;
}

void * start_server(void * data)
{
  struct mq_attr attr;
  struct sockaddr_in servaddr, cliaddr;
  int flag = 1;

  server_sockfd = socket(AF_INET, SOCK_STREAM, 0);
  setsockopt(server_sockfd, SOL_SOCKET, SO_REUSEADDR, &flag, sizeof(int));
  attr_init(&attr);

  if((mq = mq_open(MQUEUE, O_RDWR | O_CREAT, 0777, &attr)) == -1)
  {                                                                             
    ERR_LOG("Can't open queue message");                        
    exit(-1);                                                                  
  }

  if(-1 == server_sockfd)
  {
    ERR_LOG("Socket error");
    exit(-1);
  }
  
  servaddr.sin_family = AF_INET;
  servaddr.sin_addr.s_addr = INADDR_ANY;
  servaddr.sin_port = htons(PORT);

  if(-1 == bind(server_sockfd, (const struct sockaddr*)&servaddr,
        sizeof(servaddr)))
  {
    ERR_LOG("Can't bind socket");
    exit(-1);
  }

  listen(server_sockfd, 5);

  printf("Server starting successfully\n");
  if (0 != pthread_create(&connection_tid, NULL, connection_control, NULL))
  {
    ERR_LOG("Can't create server_control thread");
    exit(-1);
  }

  while(1)
  {
    socklen_t len = sizeof(struct sockaddr_in);
    int new_sockfd = accept(server_sockfd, (struct sockaddr *)&cliaddr, &len);

    if(mq_send(mq, (char *)&new_sockfd, sizeof(int), 1) == -1 )
    {
      ERR_LOG("Can't send to queue message");
      exit(-1);
    }
  }
  return 0;
}

int main(int argc, char** argv)
{
  printf("Starting server...\n");

  signal(SIGQUIT, sigquit_handler);
  parent_pid = getpid();

  mq_unlink(MQUEUE);

  if (-1 == db_api_create_tables())
  {
    ERR_LOG("Create tables error");
    return -1;
  }

  if (0 != pthread_create(&server_tid, NULL, start_server, NULL))
  {
    ERR_LOG("Can't create start_server thread");
    return -1;
  }

  pthread_join(server_tid, NULL);
  printf("Server shutdown\n");

  return 0;
}
