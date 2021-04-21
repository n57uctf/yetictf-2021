from pwnlib.tubes.remote import remote
from zipfile import ZipFile, ZIP_DEFLATED

import string
import random
import base64

PORT = 6666 

def decrypt_flag(enc_flag):
    flag = ""
    for c in enc_flag:
        try:
            if ord(c) | 254 == 254:
                flag += chr(ord(c) + 1)
            else:
                flag += chr(ord(c) - 1)
        except Exception as e:
            return

    return flag

def connect(hostname):
    return remote(hostname, PORT)

def get_rand_str(n):
    rand = ''.join(random.choices(string.ascii_uppercase + string.digits, k = n))
    return str(rand)

def b64_encode(s):
    return base64.b64encode(s)

def b64_decode(s):
    return base64.b64decode(s)

def auth(r, name, pas):
    r.sendline("1")
    r.recvuntil(": ")
    r.sendline(name)
    r.recvuntil(": ")
    r.sendline(pas)
    return r.recvuntil("> ")

def reg_auth(r, name, pas):
    r.recvuntil("> ")
    # Registration of user
    r.sendline("2")
    r.recvuntil(": ")
    r.sendline(name)
    r.recvuntil(": ")
    r.sendline(pas)
    r.recvuntil("> ")
    # Authoriprint(sation of user
    r.sendline("1")
    r.recvuntil(": ")
    r.sendline(name)
    r.recvuntil(": ")
    r.sendline(pas)
    r.recvuntil("> ")

def get_public_data(r):
    # All
    r.sendline("3")
    r.recvuntil("> ")
    r.sendline("1")
    return r.recvuntil("> ")
    
def get_private_data(r):
    r.sendline("4")
    data = r.recvline()
    r.recvline("> ")
    return data.decode()

def logout_exit(r):
    r.sendline("5")
    r.recvuntil("> ")
    r.sendline("3")
    r.close()

def logout(r):
    r.sendline("5")
    r.recvuntil("> ")
