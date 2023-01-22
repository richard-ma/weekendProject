class Block:
    def __init__(self, size: int, next_length: int):
        self._size = size
        self._data = ""

        self._next = None
        self._next_length = next_length


    @property
    def _data_length(self):
        return self._size - self._next_length

    def write(self, data: str, next_block_id=0):
        if len(data) > self._data_length:
            raise Exception("Too long for [size: {:d}]: {:s}".format(self._data_length, data))
        
        self._data = data
        self._next = next_block_id

    def read(self):
        return self._data

    def has_next_block(self):
        return not self._next == 0

    def get_next_block(self):
        return self._next

    def save(self): # save to simulator file
        return self._data + str(self._next)

    def load(self, s: str):
        if len(s) > self._size:
            raise Exception("Too long for [size: {:d}]: {:s}".format(self._size, s))

        self._data = s[:-self._next_length]
        self._next = int(s[-self._next_length:])


if __name__ == "__main__":
    block_data = {
        "size": 10,
        "data": "23"*2,
        "next_block_id": 33,
        "next_length": 4
    }
    block_data["next_length"] = len(str(block_data["size"]))


    b = Block(block_data["size"], block_data["next_length"])
    b.write(block_data["data"])

    assert b.read() == block_data["data"]
    assert b._next_length == block_data["next_length"]
    assert b._data_length == block_data["size"] - b._next_length
    assert b.has_next_block() is False

    b.write(block_data["data"], next_block_id=block_data["next_block_id"])
    assert b.get_next_block() == block_data["next_block_id"]
    assert b.has_next_block() is True

    # test save and load
    s = b.save()
    b.load(s)

    assert b.read() == block_data["data"]
    assert b._next_length == block_data["next_length"]
    assert b._data_length == block_data["size"] - b._next_length
    assert b.get_next_block() == block_data["next_block_id"]
    assert b.has_next_block() is True