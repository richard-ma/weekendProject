from sender import Sender


class CscartAPI:
    def __init__(self, username: str, api_key: str) -> None:
        self.username = username
        self.api_key = api_key
        self.sender = Sender()