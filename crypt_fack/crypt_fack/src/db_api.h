#ifndef _DB_API_H_
#define _DB_API_H_

#include <mysql.h>
#include "client_helpers.h"

int db_api_connect(
    MYSQL ** connect);

int db_api_create_tables();

int db_api_sign_up(
    MYSQL      * connect,
    const char * nick,
    const char * pass);

int db_api_login_try(
    MYSQL      * connect,
    user_t     * user,
    const char * nick,
    const char * pass);

int db_api_create_user_table(
    MYSQL      * connect,
    const char * nick);

int db_api_load_private_data(
    MYSQL      * connect,
    user_t     * user,
    const char * data);

MYSQL_RES * db_api_get_private_data(
    MYSQL  * connect, 
    user_t * user);

MYSQL_RES * db_api_get_public_data(
    MYSQL      * connect,
    user_t     * user,
    const char * filter,
    char       * err_buff);

int db_api_load_public_data(
    MYSQL * connect,
    user_t * user,
    char * data);

#endif // _DB_API_H
