#!/usr/bin/env python3

import sys
import requests
import re
import math
from bs4 import BeautifulSoup
from merclib import *

def check(host):

    chk = CheckMachine(host)

    login = rnd_username()
    passwd = rnd_password()

    chk.register_user(login,passwd)
    
    sess = chk.login_user(login,passwd)
    
    ctype = chk.currency_type(sess)

    chk.mine_one(sess, ctype)

    cquit(Status.OK, "OK",f'{login}:{passwd}')
        

def put_flag1(host, flag):

    chk = CheckMachine(host)

    login = rnd_username()
    passwd = rnd_password()

    chk.register_user(login,passwd)
    
    sess = chk.login_user(login,passwd)

    ctype = chk.currency_type(sess)

    chk.mine_one(sess,ctype)

    req = sess.post(f'{chk.url}/management/transactions', data = {"type": ctype,"amount": "0.01","recv_login": "tellers2006","message": flag})
    check_response(req, 'Could not send currency')

    soup = BeautifulSoup(req.text, 'html.parser')
    table = soup.findAll('td', text = re.compile(flag))
    if not table:
        cquit(Status.MUMBLE, 'Couldn\'t send message')
    else:
        cquit(Status.OK, f"{login}",f'{login}:{passwd}')

def get_flag1(host, flag, flag_id):

    chk = CheckMachine(host)

    login, passwd = flag_id.strip().split(":")

    sess = chk.login_user(login,passwd)

    req = sess.get(f'{chk.url}/management/transactions')
    check_response(req, 'Could not get transaction messages')

    soup = BeautifulSoup(req.text, 'html.parser')
    table = soup.findAll('td', text = re.compile(flag))
    if not table:
        cquit(Status.CORRUPT, 'Couldn\'t find flag in transaction message')
    else: 
        cquit(Status.OK, 'OK')

def put_flag2(host, flag):

    chk = CheckMachine(host)

    login = rnd_username()
    passwd = rnd_password()
    
    amount = 1
    curr_amount = 0
    result = 0

    chk.register_user(login,passwd)

    sess = chk.login_user(login,passwd)
    
    #ctype = chk.currency_type(sess)
    ctype = 'coins'

    while result!=1:
        result = chk.mine(sess,amount,curr_amount,ctype)

    req = sess.post(f'{chk.url}/casinoe/VIP_page', data = {"email": login, "message": flag})
    check_response(req, 'Could not send application')

    soup = BeautifulSoup(req.text, 'html.parser')
    table = soup.findAll('h4', text = re.compile(flag))
    if not table:
        cquit(Status.MUMBLE, 'Couldn\'t send message')
    else:
        cquit(Status.OK, f"{login}", f'{login}:{passwd}')

def get_flag2(host, flag, flag_id):

    chk = CheckMachine(host)

    login, passwd = flag_id.strip().split(":")

    sess = chk.login_user(login,passwd)

    req = sess.get(f'{chk.url}/casinoe/VIP_page')
    check_response(req, 'Could not get application text')

    soup = BeautifulSoup(req.text, 'html.parser')
    table = soup.findAll('h4', text = re.compile(flag))
    if not table:
        cquit(Status.CORRUPT, 'Couldn\'t find flag in application message')
    else: 
        cquit(Status.OK, 'OK')


if __name__ == '__main__':

    action, *args = sys.argv[1:]

    try:
        if action == 'check':

            host, = args
            check(host)

        elif action == 'put':
    
            host, flag_id, flag, vuln_number = args

            if vuln_number == '1':
                put_flag1(host, flag)
            else:
                put_flag2(host, flag)

        elif action == 'get':
            
            host, flag_id, flag, vuln_number = args

            if vuln_number == '1':
                get_flag1(host, flag, flag_id)
            else:
                get_flag2(host, flag, flag_id)

        else:
            cquit(Status.ERROR, 'System error', 'Unknown action: ' + action)

        cquit(Status.ERROR)
    except (requests.exceptions.ConnectionError, requests.exceptions.ConnectTimeout):
        cquit(Status.DOWN, 'Connection error')
    except SystemError as e:
        raise
    except Exception as e:
        cquit(Status.ERROR, 'System error', str(e))
