import sqlite3

DATABASE = 'static/db/data.db'


def init_db():
    try:
        conn = sqlite3.connect(DATABASE)
        cur = conn.cursor()
        cur.execute("""
            CREATE TABLE IF NOT EXISTS notes(
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            token TEXT,
            title TEXT,
            note TEXT);
        """)
        conn.commit()
    except Exception as e:
        return e
    return True


def create_note(token, note, title):
    try:
        data = (token, note, title)
        conn = sqlite3.connect(DATABASE)
        cur = conn.cursor()
        cur.execute("""
            INSERT INTO notes(token, note, title) VALUES(?, ?, ?);
        """, data)
        conn.commit()
        conn.close()
    except Exception as e:
        print(e)
        return e
    print('COMMIT')
    return True


def get_note(token):
    try:
        conn = sqlite3.connect(DATABASE)
        cur = conn.cursor()
        cur.execute("""
                    SELECT note, title FROM notes WHERE token='""" + token + """';
                """)
        data = cur.fetchall()
        print(data)
        conn.close()
    except Exception as e:
        return 'SQL:' + str(e)
    return data
