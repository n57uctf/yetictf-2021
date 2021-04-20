import random
from checklib import *


# no 'Aa' chars
ascii_letters = 'qwertyuiopsdfghjklzxcvbnmQWERTYUIOPSDFGHJKLZXCVBNM'
ascii_lowercase = 'qwertyuiopsdfghjklzxcvbnm'
ascii_uppercase = 'QWERTYUIOPSDFGHJKLZXCVBNM'
ascii_digits = '1234567890'

PORT = 3030

specialAlp = '@#^&*_-'
usernameAlp = ascii_letters + ascii_digits
passwordAlp = specialAlp + ascii_letters + ascii_digits + specialAlp
domainAlp = ascii_lowercase

itemQ = ['New', 'Brand New', 'Fresh', 'old', 'second-handed', 'Just', 'Freaking']
itemM = ['leather', 'wooden', 'diamond', 'imaginary', 'human-bloody', 'steel', 'plastic', 'hairy']
itemN = ['socks', '@roamiiing\'s mom', 'cola', 'keyboard', 'book', 'cup', 'energizer', 'sneakers', 'lamp', 'car', 'door']

class CheckMachine:
    @property
    def url(self):
        return f'http://{self.host}:{self.port}'

    def __init__(self, host):
        self.host = host
        self.port = PORT
        self.token = None
        self.headers = {}

    def select_token(self, token):
        self.token = token
        self.headers['Authorization'] = 'Bearer ' + token

    def register_user(self):
        templates = ['sun.microsystems.id_', 'CN_TID_', 'crumples', 'hacking_you_', 'oracle.tech.id_', '']
        username = templates[min(random.randint(0, 17), 5)] + rnd_string(random.randint(8, 18), usernameAlp) + '@'
        for _ in range(random.randint(1, 3)):
          username += rnd_string(random.randint(2, 6), domainAlp) + '.'
        username += 'com'
        password = rnd_string(random.randint(4, 15), passwordAlp)
        password += rnd_string(random.randint(1, 2), specialAlp)
        password += rnd_string(random.randint(1, 3), ascii_lowercase)
        password += rnd_string(random.randint(1, 3), ascii_uppercase)
        password += rnd_string(random.randint(2, 15), passwordAlp)
        password += rnd_string(random.randint(1, 3), ascii_digits)

        phone = rnd_string(random.randint(7, 20), ascii_digits)

        name = rnd_string(random.randint(2, 13), ascii_letters) + ' ' + rnd_string(random.randint(2, 15), ascii_letters)

        #print(f'registering... \t %s : %s' % (username,password,))
        #print(f'\t#REGISTER\n%s\n%s\n%s\n%s\n' % (username, password, phone, name,))

        sess = get_initialized_session()

        r = sess.post(f'{self.url}/users', data={'email': username, 'password': password, 'phoneNumber': phone, 'name': name})
        #print(r.text)
        assert_eq(r.status_code, 201, 'Could not register')

        return username, password

    def login_user(self, username, password):
        sess = get_initialized_session()
        r = sess.post(f'{self.url}/authentication', data={'email': username, 'password': password, 'strategy': 'local'})
        #print(r.text)

        #print(f'logging as... \t%s : %s' % (username,password,))
        assert_eq(r.status_code, 201, 'Could not login')
        data = get_json(r, 'Could not get login info')
        assert_in('accessToken', data, 'Could not login (no-token)')

        return data["accessToken"]

    def patch_settings(self):
        sess = get_initialized_session()

        # patching
        r = sess.patch(f'{self.url}/settings/my',
                      data={'allowCollectData': 'false', 'allowSMSNotify': 'true'}, headers=self.headers)
        #print(r.text)

        #print(f'patching settings...')
        assert_eq(r.status_code, 200, 'Could not patch settings')

        #getting
        r = sess.get(f'{self.url}/settings/my', headers=self.headers)
        #print(r.text)

        #print(f'checking correctness settings...')
        assert_eq(r.status_code, 200, 'Could not patch settings')

        data = get_json(r, 'Could not patch settings')
        assert_in('allowCollectData', data, 'Could not patch settings')
        assert_eq(data['allowCollectData'], False, 'Could not patch settings')

        return

    def change_number(self, number = None):
        sess = get_initialized_session()

        number = number or rnd_string(random.randint(7, 20), ascii_digits)

        # patching
        r = sess.patch(f'{self.url}/users/my',
                      data={'phoneNumber': number}, headers=self.headers)
        #print(r.text)

        #print(f'patching phone...')
        assert_eq(r.status_code, 200, 'Could not update user post')

        #getting
        r = sess.get(f'{self.url}/users/my', headers=self.headers)
        #print(r.text)

        #print(f'checking correctness of phone...')
        assert_eq(r.status_code, 200, 'Could not update user')

        data = get_json(r, 'Could not update user')
        assert_in('phoneNumber', data, 'Could not update user')
        assert_eq(data['phoneNumber'], number, 'Could not update user')
        #print(f'Got: %s and %s' % (number, data['phoneNumber'],))

        return

    def create_shop(self):
        sess = get_initialized_session()

        shop_title = rnd_string(random.randint(8, 28), usernameAlp) + f'\'s Shop'
        r = sess.post(f'{self.url}/shops',
                      data={'title': shop_title}, headers=self.headers)


        #print(f'creating shop... %s' % (shop_title,))
        assert_eq(r.status_code, 201, 'Could not create shop')
        data = get_json(r, 'Could not create shop')
        assert_eq(data['title'], shop_title, 'Could not create shop')
        assert_in('_id', data, 'Could not create shop')

        return data['_id']

    def create_item(self, title = None):
        sess = get_initialized_session()

        name = title or itemQ[random.randint(0, len(itemQ) - 1)] + ' ' + itemM[random.randint(0, len(itemM) - 1)] + ' ' + itemN[random.randint(0, len(itemN) - 1)]

        r = sess.get(f'{self.url}/shops/my', headers=self.headers)

        r = sess.post(f'{self.url}/items',
                      data={'title': name, 'price': random.randint(12, 100000) / 100, 'bonus': 0}, headers=self.headers)

        #print(f'creating item... %s' % (name,))
        assert_eq(r.status_code, 201, 'Could not create item')
        data = get_json(r, 'Could not create item')
        assert_in('title', data, 'Could not create item')
        assert_in(name, data['title'], 'Could not create item')

        return data['_id']

    def findFlagInPhone(self):
        sess = get_initialized_session()

        r = sess.get(f'{self.url}/users/my', headers=self.headers)
        #print(r.text)

        #print(f'searching flag in user data...')
        assert_eq(r.status_code, 200, 'Could not found uflag')

        data = get_json(r, 'Could not found uflag')
        assert_in('phoneNumber', data, 'Could not found uflag')

        return data['phoneNumber']


    def findFlagInItems(self, item_id):
        sess = get_initialized_session()
        r = sess.get(f'{self.url}/items?_id=' + item_id, headers=self.headers)
        r = sess.get(f'{self.url}/items/' + item_id, headers=self.headers)
        #print(r.text)

        #print(f'searching flag in items...')
        assert_eq(r.status_code, 200, 'Could not found iflag')

        data = get_json(r, 'Could not found iflag')
        assert_in('title', data, 'Could not found iflag')

        return data['title']

