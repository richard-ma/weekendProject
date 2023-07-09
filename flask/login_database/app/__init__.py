from flask import Flask
from flask_bcrypt import Bcrypt

from app.models import db

app = Flask(__name__)

app.config['SECRET_KEY'] = '9886f27f2b6c200b6c4b54a8966b9dab'
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///db.sqlite3'

db.init_app(app)
bcrypt = Bcrypt(app)

from app import routes