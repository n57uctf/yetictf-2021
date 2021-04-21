#!/usr/bin/env python3

import requests
import re
import math
from checklib import *
from bs4 import BeautifulSoup

class CheckMachine:
    @property

    def url(self):
        return f'http://{self.host}:{self.port}'

    def __init__(self, host):
        self.host = host
        self.port = 8000

    def register_user(self, login, passwd):
        sess = get_initialized_session()
        req = sess.post(f'{self.url}/account/register', data={"login": login,"passwd": passwd,"passwd2": passwd})
        check_response(req, 'Could not register')

        return sess

    def login_user(self, login, passwd):
        sess = get_initialized_session()

        req = sess.post(f'{self.url}/account/login', data={'login': login, 'passwd': passwd})
        check_response(req, 'Could not login')

        return sess

    def get_amount(self,sess,ctype):
        req = sess.post(f'{self.url}/management/membership_prep', data={'type': ctype})
        check_response(req, 'Could not get amount')

        return req.text

    def get_problem(self, sess):
        headers = {'Accept-Encoding': 'identity'}
        req = sess.get(f'{self.url}/management/calculations', headers=headers)
        check_response(req, 'Could not get calculation page')

        return req

    def currency_type(self,sess):
        coins = float(self.get_amount(sess, 'coins'))
        links = float(self.get_amount(sess, 'links'))
        rocks = float(self.get_amount(sess, 'rocks'))

        if coins < links:
            if coins < rocks:
                return 'coins'
            elif rocks < links:
                return 'rocks'
        elif links < rocks:
            return 'links'
        else:
            return 'rocks'


    def solve_problem(self, sess, resp, ctype):
        soup = BeautifulSoup(resp.text, 'html.parser')
        task = soup.findAll("div",class_="task")

        if not task:
            cquit (Status.MUMBLE, "Couldn't get task")
        else: 
            calc = re.findall(r'\d+', str(task))

            calc1 = int(calc[0])
            calc2 = int(calc[1])

            calc11 = math.sqrt(calc1+calc2)
            calc22 = math.sqrt(calc1*calc2)
            calcres = round(math.sqrt(calc22/calc11),2)

            req = sess.post(f'{self.url}/management/calculations', data={"type": ctype,"calcres": calcres})
            check_response(req, 'Could not send result')

            return req

    def mine(self, sess, amount, curr_amount, ctype):
        while float(amount) + 1 > float(curr_amount):
            amount = self.get_amount(sess,ctype)

            resp = self.get_problem(sess)
            result = self.solve_problem(sess,resp,ctype)

            soup = BeautifulSoup(result.text, 'html.parser')
            ca = soup.findAll("dd", {"id": str(ctype)})

            if not ca:

                cquit(Status.MUMBLE, "Couldn't get current amount")

            else:
                
                current = re.findall("\d+\.\d+", str(ca))
                if not current:
                    current = re.findall(r'\d+', str(ca))
                    curr_amount = current[1]
                else:
                    curr_amount = current[0]

        return self.buy_membership(sess, ctype, curr_amount)

    def buy_membership(self, sess, ctype, curr_amount):

        amount = self.get_amount(sess,ctype)
        
        if amount <= curr_amount:

            req = sess.post(f'{self.url}/management/membership', data = {"type": ctype,"amount": amount})
            check_response(req, 'Could not buy membership')

            return 1

        else: 

            return 0

    def mine_one(self,sess,ctype):

        for i in range(5):
            resp = self.get_problem(sess)
            result = self.solve_problem(sess,resp,ctype)
        return 1