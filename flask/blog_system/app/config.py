import os


class Config:
    # .bash_profile
    # export KEY='val'
    SECRET_KEY = '9886f27f2b6c200b6c4b54a8966b9dab' # 将这里读取环境变量设置
    SQLALCHEMY_DATABASE_URI = 'sqlite:///db.sqlite3' # 将这里读取环境变量设置
    # mail settings
    MAIL_SERVER = 'smtp.googlemail.com' # 将这里读取环境变量设置
    MAIL_PORT = '587' # 将这里读取环境变量设置
    MAIL_USE_TLS = True # 将这里读取环境变量设置
    MAIL_USERNAME = os.environ.get('EMAIL_USER') # 从环境变量读取邮箱
    MAIL_PASSWORD = os.environ.get('EMAIL_PASS') # 从环境变量读取邮箱密码
