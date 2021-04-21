#!/usr/bin/env python3

import sys
import requests
import pymysql

host, user, passwd, db = sys.argv[1:]

conn = pymysql.connect(host = host,user = user,password = passwd,database = db)

with conn:
        cur = conn.cursor()
        cur.execute("SELECT * FROM applications")

        rows = cur.fetchall()

        for row in rows:
            print(row[2])
