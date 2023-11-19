from datetime import datetime


print('''\
    <html>
    <head>
        <title>CGI Page</title>
    </head>
    <body>
        <h1>CGI Page</h1>
        <p>Generated {0}</p>
    </body>
    </html>
'''.format(datetime.now()))