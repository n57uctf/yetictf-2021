from pwnlib.tubes.remote import remote
from ctypes import CDLL
from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED
from checker import verdict, OK, CORRUPT, MUMBLE, DOWN, CHECKER_ERROR

import string
import random
import base64

PORT = 6666 

BASE_DIR = Path(__file__).absolute().resolve().parent
LIB_PATH = BASE_DIR / "lib/libcf.so"
ZIP_PATH = BASE_DIR / "files/file.zip"
FILE_PATH = BASE_DIR / "files/file"

def gen_file(data):
    lib = CDLL(LIB_PATH)
    full_file_path = str(FILE_PATH.as_posix())
    lib.gen_file(full_file_path.encode(), data.encode())
    zip_obj = ZipFile(ZIP_PATH, "w", compression = ZIP_DEFLATED)
    zip_obj.write(FILE_PATH, arcname = "file", compresslevel = 9)
    zip_obj.close()

    with open(ZIP_PATH, "rb") as fd:
        return base64.b64encode(fd.read())

def encrypt_flag(flag):
    enc_flag = ""
    for c in flag:
        if ord(c) | 254 == 254:
            enc_flag += chr(ord(c) + 1)
        else:
            enc_flag += chr(ord(c) - 1)
    return enc_flag

def connect(hostname):
    return remote(hostname, PORT)

def get_rand_str(n):
    rand = ''.join(random.choices(string.ascii_uppercase + string.digits, k = n))
    return str(rand)

def str_b64(s):
    return base64.b64encode(s)

def auth(r, name, pas):
    r.recvuntil("> ")
    # Registration of user
    r.sendline("2")
    r.recvuntil(": ")
    r.sendline(name)
    r.recvuntil(": ")
    r.sendline(pas)
    r.recvuntil("> ")
    # Authorisation of user
    r.sendline("1")
    r.recvuntil(": ")
    r.sendline(name)
    r.recvuntil(": ")
    r.sendline(pas)
    r.recvuntil("> ")

def check_public(r, name, data):
    # All
    r.sendline("3")
    r.recvuntil("> ")
    r.sendline("1")
    check_str = r.recvuntil("> ")
    if name not in check_str.decode():
        verdict(CORRUPT, "Can't find self name")
    r.sendline("3")
    r.recvuntil("> ")
    # Target
    r.sendline("2")
    r.recvuntil(": ")
    r.sendline(name)
    target_data = r.recvuntil("> ")
    r.sendline("3")
    r.recvuntil("> ")
    # Self
        # Decrypt
    r.sendline("3")
    r.recvuntil("> ")
    r.sendline("2")
    check_str = r.recvuntil("> ")
    if data not in check_str:
        verdict(CORRUPT, "Can't get self data")
    r.sendline("3")
    r.recvuntil("> ")
        # Encrypt
    r.sendline("3")
    r.recvuntil("> ")
    r.sendline("1")
    check_str = r.recvuntil("> ")
    if target_data.decode().split('\x00')[2] not in check_str.decode():
        verdict(CORRUPT, "Incorrect target data")

def check_private(r, data):
    r.sendline("4")
    check_str = r.recvuntil("> ")
    if data not in check_str.decode():
        verdict(CORRUPT, "Can't get private data")

def load_public(r, data):
    r.sendline("1")
    r.recvline()
    r.sendline(data)
    r.recvuntil("> ")

def load_private(r, data):
    r.sendline("2")
    r.recvline()
    r.sendline(data)
    r.recvuntil("> ")

def logout(r):
    r.sendline("5")
    r.recvuntil("> ")
    r.sendline("3")
    r.close()
