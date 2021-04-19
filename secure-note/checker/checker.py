#!/usr/bin/env python3

import sys
import requests
import enum
import typing
import random


def get_random_note():
    return f'Note number {random.randint(500, 9000)}'

class Status(enum.Enum):
    OK = 101
    CORRUPT = 102
    MUMBLE = 103
    DOWN = 104
    ERROR = 110

    def __bool__(self):
        return self.value == Status.OK


def cquit(status: Status, public: str='', private: typing.Optional[str] = None):
    if private is None:
        private = public

    print(public, file=sys.stdout)
    print(private, file=sys.stderr)
    assert (type(status) == Status)
    sys.exit(status.value)


def check(host):
    r = requests.get(f'http://{host}:5000/')
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} on url {r.url}')

    r = requests.post(f'http://{host}:5000/')
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} when getting key (POST on {r.url})')

    token = r.text

    r = requests.get(f'http://{host}:5000/create')
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} on url {r.url}')

    note = get_random_note()
    data = {'title': 'My secret note',
            'note': note}
    file = {'profile': ('checker.key', token)}

    r = requests.post(f'http://{host}:5000/create', files=file, data=data)
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} when create note (POST on {r.url})')

    r = requests.get(f'http://{host}:5000/check')
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} on url {r.url}')

    r = requests.post(f'http://{host}:5000/check', files=file)
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} when check note (POST on {r.url})')

    res = r.text.find(note)
    if res == -1:
        cquit(Status.MUMBLE, f"Can't take note")

    cquit(Status.OK, f'OK')

def put(host, flag):
    r = requests.post(f'http://{host}:5000/')
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} when getting key (POST on {r.url})')

    token = r.text

    note = flag
    data = {'title': 'My secret note',
            'note': note}
    file = {'profile': ('checker.key', token)}

    r = requests.post(f'http://{host}:5000/create', files=file, data=data)
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} when create note (POST on {r.url})')

    cquit(Status.OK, "OK", f'{token}')

def get(host, flag, flag_id):
    file = {'profile': ('checker.key', flag_id)}

    r = requests.post(f'http://{host}:5000/check', files=file)
    if r.status_code != 200:
        cquit(Status.MUMBLE, f'Code {r.status_code} when check note (POST on {r.url})')
    else:
        stat = r.text.find(flag)
        if stat == -1:
            cquit(Status.CORRUPT, f'Couldn\'t find flag in note')
        else:
            cquit(Status.OK, f'OK')


if __name__ == '__main__':
    action, *args = sys.argv[1:]

    try:
        if action == 'check':
            host, = args
            check(host)

        elif action =='put':
            host, flag_id, flag, vuln_number = args
            put(host, flag)

        elif action == 'get':
            host, flag_id, flag, vuln_number = args
            get(host, flag, flag_id)
        else:
            cquit(Status.ERROR, 'System error', 'Unknown action: ' + action)

        cquit(Status.ERROR)
    except (requests.exceptions.ConnectionError, requests.exceptions.ConnectTimeout):
        cquit(Status.DOWN, 'Connection error')
    except SystemError as e:
        raise
    except Exception as e:
        cquit(Status.ERROR, 'System error', str(e))
