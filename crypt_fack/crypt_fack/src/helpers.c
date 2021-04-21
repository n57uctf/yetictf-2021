#include "helpers.h"

void attr_init (struct mq_attr * attr)                                          
{                                                                               
  attr->mq_flags = 0;                                                           
  attr->mq_maxmsg = MAX_MSG;                                                    
  attr->mq_msgsize = sizeof(int);                                               
  attr->mq_curmsgs = 0;                                                         
}

void sigquit_handler (int sig)
{
  pid_t self = getpid();

  if (parent_pid != self)
  {
    close(client_sockfd);
    exit(0);
  }
  close(server_sockfd);
  pthread_cancel(server_tid);
}

int recv_timeout(int sockfd, char * buff, int timeout)
{
  char * ptr = buff;
  int recv_size = 0;
  int total_size = 0;
  struct timeval begin = {0};
  struct timeval now = {0};
  double timediff = 0;

  gettimeofday(&begin , NULL);

  while(1)
  {
    gettimeofday(&now , NULL);

    timediff = (now.tv_sec - begin.tv_sec) + 1e-6 *
      (now.tv_usec - begin.tv_usec);

    if ((total_size > 0) && (timediff > timeout))
    {
      break;
    }

    else if (timediff > timeout * 2)
    {
      break;
    }

    if (((recv_size = recv(sockfd, ptr, CHUNK_SIZE, MSG_DONTWAIT)) < 0))
    {
      usleep(100000);
    }
    else
    {
      ptr += recv_size;
      total_size += recv_size;
      gettimeofday(&begin , NULL);
    }
  }

  return total_size;
}
