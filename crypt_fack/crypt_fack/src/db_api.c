#define _GNU_SOURCE
#include <mysql.h>

#include "helpers.h"

static char * db_query_create_table_users =
"CREATE TABLE IF NOT EXISTS users(\
    user_id INT PRIMARY KEY AUTO_INCREMENT,\
    name VARCHAR(31), password VARCHAR(31))";

static char * db_query_create_table_data =
"CREATE TABLE IF NOT EXISTS data(\
    data_id INT PRIMARY KEY AUTO_INCREMENT,\
    data TEXT, user_id INT,\
    FOREIGN KEY(user_id) REFERENCES users(user_id))";

static char * db_query_check_exists =
"SELECT 1 FROM users WHERE name='%s'";

static char * db_query_insert_user =
"INSERT INTO users (name, password) VALUES ('%s', '%s')";

static char * db_query_get_password =
"SELECT password FROM users WHERE name='%s'";

static char * db_query_create_user_table =
"CREATE TABLE %s_data(\
    id INT PRIMARY KEY AUTO_INCREMENT,\
    private_data TINYTEXT)";

static char * db_query_get_user_id =
"SELECT user_id FROM users where name='%s'";

static char * db_query_load_private_data =
"INSERT INTO %s (private_data) VALUES ('%s')";

static char * db_query_load_public_data =
"INSERT INTO data (data, user_id) VALUES ('%s', '%s')";

static char * db_query_get_private_data =
"SELECT private_data FROM %s";

static char * db_query_get_public_all =
"SELECT DISTINCT users.name, data.data\
    FROM data\
    INNER JOIN users ON users.user_id=data.user_id";

static char * db_query_get_public_data =
"SELECT DISTINCT data FROM data INNER JOIN users ON data.user_id='%s'";

int db_api_connect(MYSQL ** connect)
{
  *connect = mysql_init(NULL);

  if (NULL == *connect)
  {
    ERR_LOG(mysql_error(*connect));
    return -1;
  }

  while (NULL == mysql_real_connect(*connect, "db", "user", "password",
        "crypt_fack_db", 0, NULL, 0))
  {
    ERR_LOG("Trying connect to db");
    sleep(3);
  }

  return 0;
}

int db_api_create_tables()
{
  MYSQL * connect = NULL;

  if (-1 == db_api_connect(&connect)) return -1;

  if (mysql_query(connect, db_query_create_table_users))
  {
    ERR_LOG(mysql_error(connect));
    return -1;
  }
  if (0 != mysql_query(connect, db_query_create_table_data))
  {
    ERR_LOG(mysql_error(connect));
    return -1;
  }

  mysql_close(connect);
  return 0;
}

static int db_api_user_is_exists(MYSQL * connect, const char * nick)
{
  char * query = NULL;
  char buff[BUFSIZE] = "";
  MYSQL_RES * result = NULL;
  int rc = -1;

  mysql_real_escape_string(connect, buff, nick, strlen(nick));

  if (-1 == asprintf(&query, db_query_check_exists, buff))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed");
    return rc;
  }

  if (0 != mysql_query(connect, query))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return rc;
  }

  if (NULL == (result = mysql_store_result(connect)))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return rc;
  }

  rc = 0;

  if (0 != mysql_num_rows(result)) rc = 1;

  free(query);
  mysql_free_result(result);
  return rc;
}

static char * db_api_get_user_id(MYSQL * connect, const char * nick)
{
  char buff[BUFSIZE] = "";
  char * id = NULL;
  char * query = NULL;
  MYSQL_ROW row;
  MYSQL_RES * result = NULL;

  mysql_real_escape_string(connect, buff, nick, strlen(nick));

  if (-1 == asprintf(&query, db_query_get_user_id, buff))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed");
    return NULL;
  }

  if (0 != mysql_query(connect, query))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return NULL;
  }
  free(query);

  if (NULL == (result = mysql_store_result(connect)))
  {
    ERR_LOG(mysql_error(connect));
    return NULL;
  }

  row = mysql_fetch_row(result);
  id = row[0];
  mysql_free_result(result);
  return id;
}

int db_api_login_try(MYSQL * connect, user_t * user, const char * nick,
    const char * pass)
{
  char * id = NULL;
  char * get_pass = NULL;
  char * query = NULL;
  char buff[BUFSIZE] = "";
  MYSQL_RES * result = NULL;
  MYSQL_ROW row;
  int rc = -1;

  mysql_real_escape_string(connect, buff, nick, strlen(nick));

  rc = db_api_user_is_exists(connect, buff);
  if (1 != rc) return rc;

  if (-1 == asprintf(&query, db_query_get_password, buff))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed");
    return rc;
  }

  if (0 != mysql_query(connect, query))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return rc;
  }

  if (NULL == (result = mysql_store_result(connect)))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return rc;
  }

  row = mysql_fetch_row(result);
  get_pass = row[0];

  if (NULL == strstr(get_pass, pass)) rc = 0;

  if (NULL == (id = db_api_get_user_id(connect, nick)))
    return -1;

  memcpy(user->id, id, sizeof(user->id));
  memcpy(user->nick, nick, sizeof(user->nick));
  memcpy(user->pass, pass, sizeof(user->pass));
  sprintf(user->table, "%s_data", user->id);

  free(query);
  mysql_free_result(result);
  return rc;
}

int db_api_sign_up(MYSQL * connect, const char * nick, const char * pass)
{
  char nick_buff[BUFSIZE] = "";
  char pass_buff[BUFSIZE] = "";
  char * query = NULL;
  int rc = 0;

  rc = db_api_user_is_exists(connect, nick);
  if (0 != rc) return rc;

  mysql_real_escape_string(connect, nick_buff, nick, strlen(nick));
  mysql_real_escape_string(connect, pass_buff, pass, strlen(pass));

  if (-1 == asprintf(&query, db_query_insert_user, nick_buff, pass_buff))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed");
    return -1;
  }

  if (0 != mysql_query(connect, query))
  {
    free(query);
    ERR_LOG(mysql_error(connect));
    return -1;
  }

  free(query);
  return rc;
}

int db_api_create_user_table(MYSQL * connect, const char * nick)
{
  char * id = NULL;
  char * query = NULL;

  if(NULL == (id = db_api_get_user_id(connect, nick)))
    return -1;

  if (-1 == asprintf(&query, db_query_create_user_table, id))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed");
    return -1;
  }

  if (0 != mysql_query(connect, query))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return -1;
  }

  free(query);
  return 0;
}

int db_api_load_private_data(MYSQL * connect, user_t * user, const char * data)
{
  char * query = NULL;
  char data_buff[BUFSIZE * 2] = "";

  mysql_real_escape_string(connect, data_buff, data, strlen(data));
  
  if (-1 == asprintf(&query, db_query_load_private_data, user->table,
        data_buff))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed");
    return -1;
  }

  if (0 != mysql_query(connect, query))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return -1;
  }

  free(query);
  return 0;
}

int db_api_load_public_data(MYSQL * connect, user_t * user, char * data)
{
  char * query = NULL;

  if (-1 == asprintf(&query, db_query_load_public_data, data, user->id))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed");
    return -1;
  }

  if (0 != mysql_query(connect, query))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return -1;
  }

  free(query);
  return 0;
}

MYSQL_RES * db_api_get_private_data(MYSQL * connect, user_t * user)
{
  char * query = NULL;
  MYSQL_RES * result = NULL;                                                    

  if (-1 == asprintf(&query, db_query_get_private_data, user->table))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed");
    return NULL;
  }

  if (0 != mysql_query(connect, query))
  {
    ERR_LOG(mysql_error(connect));
    free(query);
    return NULL;
  }
  free(query);

  if (NULL == (result = mysql_store_result(connect)))
  {
    ERR_LOG(mysql_error(connect));
    return NULL;
  }

  return result;
}

MYSQL_RES * db_api_get_public_data(MYSQL * connect, user_t * user,
  const char * filter, char * err_buff)
{
  char * query = NULL;
  MYSQL_RES * result = NULL;                                                    
  char * id = "";

  if (NULL == filter)
  {
    filter = user->nick;
  }
  else if (0 == strcmp(filter, "all"))
  {
    if (0 != mysql_query(connect, db_query_get_public_all))
    {
      ERR_LOG(mysql_error(connect));
      free(query);
      return NULL;
    }

    if (NULL == (result = mysql_store_result(connect)))
    {
      ERR_LOG(mysql_error(connect));
      free(query);
      return NULL;
    }

    free(query);
    return result;
  }
  else if (0 == db_api_user_is_exists(connect, filter))
  {
    sprintf(err_buff, "Such user does not exists\n");
    return NULL;
  }

  id = db_api_get_user_id(connect, filter);

  if (-1 == asprintf(&query, db_query_get_public_data, id))
  {
    if (NULL != query) free(query);
    ERR_LOG("Asprintf failed\n");
    return NULL;
  }

  if (0 != mysql_query(connect, query))
  {
    ERR_LOG(mysql_error(connect));
    return NULL;
  }

  if (NULL == (result = mysql_store_result(connect)))
  {
    ERR_LOG(mysql_error(connect));
    return NULL;
  }

  free(query);
  return result;
}
