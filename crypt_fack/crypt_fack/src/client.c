#include "helpers.h"
#include "client_helpers.h"
#include "db_api.h"
#include "b64/base64.h"
#include "crypt.h"

#include <ctype.h>

void client_parse_str(char * str)
{
  int last_byte = strlen(str) - 1;

  if ((last_byte > -1) && (0 == isalpha(str[last_byte])))
  {
      str[last_byte] = '\0';
  }
}

int client_send_auth_form(char * nick, char * pass)
{
  SEND(AUTH_FORM_NICK);
  RECV(nick, CLIENT_NICK_SIZE);
  SEND(AUTH_FORM_PASS);
  RECV(pass, CLIENT_PASS_SIZE);

  client_parse_str(nick);
  client_parse_str(pass);
  return 0;
}

int client_load_data(MYSQL * db_connect, user_t * user, int data_type)
{
  char data[DATA_SIZE] = "";
  switch(data_type)
  {
    case(PRIVATE):
    {
      SEND("Load your private data:\n");
      RECV(data, BUFSIZE);
      client_parse_str(data);
      if (-1 == db_api_load_private_data(db_connect, user, data))
      {
        ERR_MSG("Load private data failed");
        return -1;
      }
      break;
    }

    case(PUBLICK):
    {
      SEND("Load public data (base64 format)\n");
      RECV_DATA(data);
      client_parse_str(data);

      if (-1 == crypt_encrypt_data(data))
      {
        ERR_MSG("Load data failed\n");
        return -1;
      }

      if (-1 == db_api_load_public_data(db_connect, user, data))
      {
        return -1;
      }
      break;
    }

    default: return -1;
  }
    return 0;
}

static int client_send_data(MYSQL * db_connect, user_t * user,
    const char * filter, char * err_buff, int data_type)
{
  MYSQL_ROW row;
  MYSQL_RES * result;

  switch(data_type)
  {
    case(PRIVATE):
    {
      if (NULL == (result = db_api_get_private_data(db_connect, user)))
      {
        ERR_LOG("Get private data failed");
        return -1;
      }
      break;
    }
    case(PUBLICK):
    {
      if (NULL == (result = db_api_get_public_data(db_connect, user, filter,
              err_buff)))
      {
        if ('\0' != err_buff[0])
        {
          SEND_S(err_buff, strlen(err_buff));
          return 0;
        }
        return -1;
      }
      break;
    }
    default: return -1;
  }

  int num_fields = mysql_num_fields(result);
  while ((row = mysql_fetch_row(result)))
  {
    for (int i = 0; i < num_fields; ++i)
    {
      SEND_DATA(row[i]);
      SEND_C("\n");
    }
    SEND_C("\n");
  }

  mysql_free_result(result);
  return 0;
}

int client_show_data(MYSQL * db_connect, user_t * user, int data_type)
{
  char filter[BUFSIZE] = "";
  char command[COMMAND_SIZE] = "";
  char err_buff[BUFSIZE] = "";
  char data[DATA_SIZE] = "";

  switch(data_type)
  {
    case(PRIVATE):
    {
      if (-1 == client_send_data(db_connect, user, NULL, err_buff, PRIVATE))
      {
        ERR_MSG("Show private data failed\n");
        return -1;
      }
      break;
    }

    case(PUBLICK):
    {
      SEND(PUB_DATA_MENU);
      RECV(command, COMMAND_SIZE);
      switch(command[0])
      {
        case(CLIENT_SHOW_ALL_DATA):
        {
          if (-1 == client_send_data(db_connect, user, "all", err_buff,
                PUBLICK))
          {
            ERR_MSG("Show all data failed\n");
            return -1;
          }
          break;
        }

        case(CLIENT_SHOW_USER_DATA):
        {
          SEND("Enter users's nickname: ");
          RECV(filter, CLIENT_NICK_SIZE);
          SEND("\n");
          client_parse_str(filter);

          if (-1 == client_send_data(db_connect, user, filter, err_buff,
                PUBLICK))
          {
            ERR_MSG("Show user's data failed\n");
            return -1;
          }
          break;
        }
        case(CLIENT_SHOW_SELF_DATA):
        {

          SEND(FORMAT_CHOISE);
          RECV(command, COMMAND_SIZE);

          switch(command[0])
          {
            case(ENCRYPT_FORMAT):
            {
              if (-1 == client_send_data(db_connect, user, NULL, err_buff,
                    PUBLICK))
              {
                ERR_MSG("Show your data failed\n");
                return -1;
              }
              break;
            }

            case(DECRYPT_FORMAT):
            {
              MYSQL_RES * result;
              MYSQL_ROW row;

              if (NULL == (result = db_api_get_public_data(db_connect, user,
                      NULL, err_buff)))
              {
                if (err_buff[0] != '\0')
                {
                  SEND_S(err_buff, strlen(err_buff));
                  return 0;
                }
              }
 
              while ((row = mysql_fetch_row(result)))
              {
                memcpy(data, row[0], strlen(row[0]));
                crypt_decrypt_data(data);
                SEND_DATA(data);
                SEND_C("\n\n");
                memset(data, 0, DATA_SIZE);
              }

              mysql_free_result(result);
              break;
            }
            default: break;
          }
          break;
        }

        default: break;
      }
    }

    default: break;
  }

  return 0;
}

int client_sign_in(MYSQL * db_connect)
{
  char client_nick[BUFSIZE] = "";
  char client_pass[BUFSIZE] = "";
  char command[COMMAND_SIZE] = ""; 
  int rc = 0;

  user_t user = {0};

  if (0 != client_send_auth_form(client_nick, client_pass))
  {
    ERR_LOG("Show auth form failed");
    return -1;
  }
  
  rc = db_api_login_try(db_connect, &user, client_nick, client_pass);
  if (0 == rc)
  {
    SEND("Invalid login or password");
    return 0;
  }
  else if (1 == rc)
  {
    while(1)
    {
      SEND(USER_MAIN_MENU);
      RECV(command, COMMAND_SIZE);

      switch(command[0])
      {
        case(CLIENT_LOAD_PUB_DATA):
          if (-1 == client_load_data(db_connect, &user, PUBLICK))
            return -1;
          break;

        case(CLIENT_LOAD_PRV_DATA):
          if (-1 == client_load_data(db_connect, &user, PRIVATE))
            return -1;
          break;

        case(CLIENT_SHOW_PUB_DATA):
          if (-1 == client_show_data(db_connect, &user, PUBLICK))
            return -1;
          break;

        case(CLIENT_SHOW_PRV_DATA):
          if (-1 == client_show_data(db_connect, &user, PRIVATE))
            return -1;
          break;

        case(CLIENT_MAIN_EXIT):
          return 0;

        default:
          break;
      }
    }
  }

  return -1;
}

int client_sign_up(MYSQL * db_connect)
{
  char client_nick[BUFSIZE] = "";
  char client_pass[BUFSIZE] = "";
  int rc = 0;

  client_send_auth_form(client_nick, client_pass);

  rc = db_api_sign_up(db_connect, client_nick, client_pass);
  if (0 == rc)
  {
    if (-1 == db_api_create_user_table(db_connect, client_nick))
      return -1;

    SEND("User successfully created\n");
    return 0;
  }
  else if (1 == rc)
  {
    SEND("Such user already exists\n");
    return 0;
  }

  return -1;
}

int client(int sockfd)
{
  char command[COMMAND_SIZE] = "";
  MYSQL * db_connect = NULL;  
  client_sockfd = sockfd;
  client_pid = getpid();

  int close_all(int rc)
  {
    if (NULL != db_connect)
    {
      mysql_close(db_connect);
    }
    close(client_sockfd);
    return rc;
  }

  if (-1 == db_api_connect(&db_connect))
  {
    return close_all(-1);
  }

  SEND_M(BANNER);

  while(1)
  {
    SEND_M(WELCOME_FORM);
    RECV_M(command, COMMAND_SIZE);

    switch(command[0])
    {
      case(CLIENT_SIGN_IN):
        if (-1 == client_sign_in(db_connect))
        {
          ERR_LOG("Sign in failed");
          return close_all(-1);
        }
        break;

      case(CLIENT_SIGN_UP):
        if (-1 == client_sign_up(db_connect))
        {
          ERR_MSG("Sign up failed\n");
          return close_all(-1);
        }
        break;

      case(CLIENT_SIGN_EXIT): return close_all(-1);

      default:
      {
        SEND_M("Wrong command\n");
        return close_all(-1);
      }
    }
  }
  return 0;
}
