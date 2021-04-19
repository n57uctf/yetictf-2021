import os
import secure


def get_key_by_token(token):
    filename = secure.get_secure_filename()
    file = open(f'static/keys/{filename}.key', 'w')
    file.write(token)
    file.close()
    return f'{filename}.key'


def get_token_by_key(key):
    filename = secure.get_secure_filename()
    key.save(os.path.join('static/upload_keys', filename))
    f = open(f'static/upload_keys/{filename}', 'r')
    token = f.read()
    return token

