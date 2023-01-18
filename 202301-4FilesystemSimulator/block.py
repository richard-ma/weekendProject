class Block:
    def __init__(self, size: int):
        self._size = size
        self._data = ""

    def write(self, data: str):
        if len(data) > self._size:
            raise Exception("Too long: ", data)
        
        self._data = data

    def read(self):
        return self._data

    def __str__(self):
        return "Block: " + self._data


if __name__ == "__main__":
    b = Block(10)
    b.write("23"*2)
    assert b.read() == "23"*2

    b.write("23"*10)