#!/usr/bin/env python3
import sys
import requests
from checklib import *
from checkutils import *


def check(host):
    cm = CheckMachine(host)

    username, password = cm.register_user()
    token = cm.login_user(username, password)
    cm.select_token(token)
    cm.patch_settings()
    cm.create_shop()
    cm.change_number()
    cm.create_item()

    cquit(Status.OK)


def put(host, flag_id, flag, vuln):
    cm = CheckMachine(host)

    username, password = cm.register_user()
    token = cm.login_user(username, password)
    cm.select_token(token)
    cm.patch_settings()
    shop_id = cm.create_shop()
    if int(vuln) % 2 == 1:
      cm.change_number(flag)
      item_id = cm.create_item()
      answer = f'{username}:{password}:0'
    else:
      cm.change_number()
      item_id = cm.create_item(flag)
      answer = f'{username}:{password}:{item_id}'

    cquit(Status.OK, answer)


def get(host, flag_id, flag, vuln):
    cm = CheckMachine(host)

    username, password, item_id = flag_id.split(":")
    token = cm.login_user(username, password)
    cm.select_token(token)
    expflag = ''

    if item_id == '0':
      cm.create_item()
      expflag = cm.findFlagInPhone()
    else:
      cm.change_number()
      expflag = cm.findFlagInItems(item_id)

    if not expflag == flag:
        cquit(Status.CORRUPT, "Flag not found")

    #print('done!')
    cquit(Status.OK)


if __name__ == '__main__':
    action, *args = sys.argv[1:]

    try:
        if action == "check":
            host, = args
            check(host)
        elif action == "put":
            host, flag_id, flag, vuln = args
            put(host, flag_id, flag, vuln)
        elif action == "get":
            host, flag_id, flag, vuln = args
            get(host, flag_id, flag, vuln)
        else:
            cquit(Status.ERROR, 'System error', 'Unknown action: ' + action)

        cquit(Status.ERROR)
    except requests.exceptions.ConnectionError:
        cquit(Status.DOWN, 'Connection error')
    except SystemError as e:
        raise
    except Exception as e:
        print(f'Got checksystem exception {e} {type(e)} {repr(e)}')
        cquit(Status.ERROR, 'System error', str(e))
