import os
import secrets

from PIL import Image
from flask import url_for, current_app
from flask_mail import Message

from app import mail


def save_picture(form_picture):
    random_hex = secrets.token_hex(8) # random filename
    _, f_ext = os.path.splitext(form_picture.filename) # split filename to name and ext
    picture_fn = random_hex + f_ext # new filename
    picture_path = os.path.join(current_app.root_path, 'static', 'profile_pics', picture_fn)
    # resize picture
    output_size = (125, 125)
    i = Image.open(form_picture)
    i.thumbnail(output_size)
    # save resized picture
    i.save(picture_path)

    return picture_fn


def send_reset_email(user):
    token = user.get_reset_token()
    msg = Message('Password Reset Request', 
                  sender='noreply@demo.com', 
                  recipients=[user.email])
    msg.body = f'''To reset your password, visit the following link:
{url_for('reset_token', token=token, _external=True)}

If you did not make this request then simply ignore this email and no changes will be made.
    '''
    print(msg.body)
    try:
        mail.send(msg)
    except:
        pass # 忽略所有错误，显示发送成功