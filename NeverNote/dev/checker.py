#!/usr/bin/env python3 

from __future__ import print_function
from requests.sessions import Session
from mimesis import Text
from bs4 import BeautifulSoup
from random import randint, choice
from string import ascii_uppercase, digits
import sys

def rand():
    return ''.join(choice(ascii_uppercase + digits) for _ in range(randint(4,30)))

def eprint(*args, **kwargs):
    print(*args, file=sys.stderr, **kwargs)

try:
    mode = sys.argv[1]
    url = sys.argv[2]
    url = 'http://'+url+':8050'

except Exception as e:
    exit(110)

 
if mode == 'check':

    s = Session()
    t = Text('en')
    username = rand()
    password = rand()
    
    try:
        a = True if s.get(url).status_code == 200 else False
        if not a:
            print('Service down')
            exit(104)
        
        status = s.post(f"{url}/register.php", data={"username":username, "password":password})
        if status.status_code != 200:
            print('Register failed')
            exit(103)
        status = s.post(f"{url}/login.php", data={"username":username, "password":password})
        if status.status_code != 200:
            print('Login failed')
            exit(103)
        status = s.get(f"{url}/index.php")
        if status.status_code != 200:
            print('Index.php not found')
            exit(103)
        
        content = BeautifulSoup(status.text, 'html.parser')
        listRaw, usersRaw = content.findAll('div', {"class":"list"})
        users = [i.text for i in usersRaw.findAll('li')]
        if username not in users:
            print("User not found") 
            exit(103)

        note = t.text(quantity=randint(1,10))
        status = s.post(f"{url}/index.php", data={"note":note, "Add":"Add"})
        content = BeautifulSoup(status.text, 'html.parser')
        content = content.findAll('div', {"class":"area"})[0]
        content = [i.text for i in content.findAll('p')][0]
        if "Error" in content:
            print("Can't store note")
            exit(103)
        noteID = content.partition("You note: ")[2]
        
        status = s.get(f"{url}/index.php").text
        content = BeautifulSoup(status, 'html.parser')
        listRaw = content.findAll('div', {"class":"list"})[0]
        list = [i.text for i in listRaw.findAll('li')]
        if noteID not in list:
            print("Note not found") 
            exit(103)
        
        status = s.post(f"{url}/index.php", data={"file":str(noteID), "Read":"Read"}).text
        content = BeautifulSoup(status, 'html.parser')
        content = content.findAll('div', {"class":"area"})[1]
        content = [i.text for i in content.findAll('p')][0]
        if "Error" in content:
            print("Can't read note")
            exit(103)
        note = content.partition("You note: ")[2]
        if note != note:
            print('Note was changed')
            exit(103)
        
        status = s.get(f"{url}/images/favicon.ico")
        if status.status_code != 200:
            print('favicon.ico not found')
            exit(103)
        
        print("Ok")
        exit(101)
    except Exception as e:
        print('Service down')
        exit(104)

elif mode == 'put':
    flag_id = sys.argv[3]
    flag = sys.argv[4]
    vuln_number = sys.argv[5]
    
    s = Session()
    t = Text('en')
    username = rand()
    password = rand()    

    try:
        a = True if s.get(url).status_code == 200 else False
        if not a:
            print('Service down')
            exit(104)
        
        status = s.post(f"{url}/register.php", data={"username":username, "password":password})
        if status.status_code != 200:
            print('Register failed')
            exit(103)
        status = s.post(f"{url}/login.php", data={"username":username, "password":password})
        if status.status_code != 200:
            print('Login failed')
            exit(103)
        status = s.get(f"{url}/index.php")
        if status.status_code != 200:
            print('index.php not found')
            exit(103)
        
        note = flag
        status = s.post(f"{url}/index.php", data={"note":note, "Add":"Add"})
        content = BeautifulSoup(status.text, 'html.parser')
        content = content.findAll('div', {"class":"area"})[0]
        content = [i.text for i in content.findAll('p')][0]
        if "Error" in content:
            print("Can't store note")
            exit(103)
        noteID = content.partition("You note: ")[2]
        print("Flag added")
        stderr = flag_id+':'+username+':'+password+':'+noteID
        eprint(stderr)
        exit(101)
    except Exception as e:
        print('Service down')
        exit(104)

elif mode == 'get':
    flag_id = sys.argv[3]
    flag = sys.argv[4]
    vuln_number = sys.argv[5]
    s = Session()
    
    flag_id = flag_id.split(':')
    noteID = flag_id[3]  
    password = flag_id[2]  
    username = flag_id[1]  
    flag_id = flag_id[0]  

    print(f"{username}")

    try:    
        a = True if s.get(url).status_code == 200 else False
        if not a:
            print('Service Down')
            exit(104)
        
        status = s.post(f"{url}/login.php", data={"username":username, "password":password})
        if status.status_code != 200:
            print('Login failed')
            exit(103)
        status = s.get(f"{url}/index.php")
        if status.status_code != 200:
            print('index.php not found')
            exit(103)
        
        status = s.post(f"{url}/index.php", data={"file":str(noteID), "Read":"Read"}).text
        content = BeautifulSoup(status, 'html.parser')
        content = content.findAll('div', {"class":"area"})[1]
        content = [i.text for i in content.findAll('p')][0]
        if "Error" in content:
            print("Can't read note") 
            exit(102)
        note = content.partition("You note: ")[2]
        if note != flag:
            print('Note was changed')
            exit(102)
        print('Success')
        exit(101)
    except Exception as e:
        print('Service down')
        exit(104)