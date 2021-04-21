#!/usr/bin/env python3.7

import api
import sys

OK, CORRUPT, MUMBLE, DOWN, CHECKER_ERROR = 101, 102, 103, 104, 110

def verdict(code, public="", private=""):
    if public:
        print(public)
    if private:
        print(private, file=sys.stderr)
    print('Exit with code {}'.format(code), file=sys.stderr)
    exit(code)

def connect(host):
    try:
        r = api.connect(host)
        return r
    except Exception as e:
        verdict(DOWN, "Connection error")

def error_arg(*args):
    verdict(CHECKER_ERROR, private="Wrong command {}".format(sys.argv[1]))

def check(*args):
    host = args[0]
    r = connect(host)
    try:
        name = api.get_rand_str(8)
        pas = api.get_rand_str(16)
        # Authorisation
        api.auth(r, name, pas)
        # Logout and exit
        api.logout(r)
        verdict(OK)
    except Exception as e:
        r.close()
        verdict(MUMBLE, "Check error", str(e))

def put(*args):
    host, flag_id, flag = args[:3]
    r = connect(host)
    encflag = api.encrypt_flag(flag)
    data = api.gen_file(encflag)
    try:
        name = api.get_rand_str(12)
        password = api.get_rand_str(16)
        # Authorisation
        api.auth(r, name, password)
        # Load public data 
        api.load_public(r, data)
        # Load private data
        api.load_private(r, flag)
        # Logout and exit
        api.logout(r)
        flag_id = f"{name}:{password}:{data.decode()}"
        verdict(OK, private=flag_id)
    except Exception as e:
        r.close()
        verdict(MUMBLE, "Put error", str(e))

def get(*args):
    host, flag_id, flag = args[:3]
    name, password, data = flag_id.strip().split(":")
    r = connect(host)
    encflag = api.encrypt_flag(flag)
    try:
        # Authorisation
        api.auth(r, name, password)
        # Check public data
        api.check_public(r, name, data.encode())
        # Check private data
        api.check_private(r, flag)
        # Logout and exit
        api.logout(r)
        r.close()
        verdict(OK)
    except Exception as e:
        r.close()
        verdict(MUMBLE, "Get error", str(e))

def init(*args):
    verdict(OK)

def info(*args):
    verdict(OK, "vulns: 1")

COMMANDS = {
    'put': put,
    'check': check,
    'get': get,
    'info': info,
    'init': init
}

if __name__ == "__main__":
    try:
        COMMANDS.get(sys.argv[1])(*sys.argv[2:])
    except Exception as ex:
        verdict(CHECKER_ERROR, private="INTERNAL ERROR: {}".format(ex))
