import hashlib
import random


def gen_token():
    bb = random.randrange(2**3)
    h = hashlib.md5(bb.to_bytes(16, 'big'))
    return h.hexdigest()


def get_secure_filename():
    bb = random.randrange(2**10)
    h = hashlib.md5(bb.to_bytes(16, 'big'))
    return h.hexdigest()
