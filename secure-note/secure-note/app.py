import db
import tokens
import secure

from flask import Flask, render_template, request, redirect, url_for, send_from_directory, g, send_file

app = Flask(__name__)


status = db.init_db()
if (status != True):
    print(status)


@app.route('/', methods=['GET', 'POST'])
def index():
    if request.method == 'GET':
        return render_template("index.html")
    if request.method == 'POST':
        token = secure.gen_token()
        key = tokens.get_key_by_token(token)
        return send_from_directory('static/keys/', key, as_attachment=True)


@app.route('/create', methods=['GET', 'POST'])
def create():
    if request.method == 'GET':
        return render_template("create.html")
    if request.method == 'POST':
        title = request.form['title']
        note = request.form['note']
        key = request.files['profile']
        if not title or not note or not key:
            return render_template("create.html", status='Error')
        token = tokens.get_token_by_key(key)
        db.create_note(token, note, title)
        return render_template("create.html", status='Your note saved successfully!')


@app.route('/check', methods=['GET', 'POST'])
def check():
    if request.method == 'POST':
        key = request.files['profile']
        if not key:
            return render_template("check.html")
        try:
            token = tokens.get_token_by_key(key)
        except:
            return render_template("check.html")
        note = db.get_note(token)
        return render_template("check.html", note=note)

    if request.method == 'GET':
        return render_template("check.html")


@app.route('/static/<path:path>')
def send_token(path):
    return send_file(path)


if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)

