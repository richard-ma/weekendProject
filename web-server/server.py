import http.server


class RequestHandler(http.server.BaseHTTPRequestHandler):
    Page = '''\
<html>
<head>
    <title>TITLE</title>
</head>
<body>
    <h1>Hello web!</h1>
</body>
</html>
'''

    def do_GET(self):
        self.send_response(200)
        self.send_header("Content-Type", "text/html")
        self.send_header("Content-Length", str(len(self.Page)))
        self.end_headers()
        self.wfile.write(bytes(self.Page, 'utf-8'))


if __name__ == "__main__":
    serverAddress = ('', 8080)
    server = http.server.HTTPServer(serverAddress, RequestHandler)
    server.serve_forever()