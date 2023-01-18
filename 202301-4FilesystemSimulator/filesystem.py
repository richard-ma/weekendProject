from block import Block


class Filesystem:
    def __init__(self, block_size=1024, block_count=1024):
        self._block_size = block_size
        self._block_count = block_count
        self._bitmap = [0] * self._block_count
        self._disk = {k: Block(self._block_size) for k in range(self._block_count)}

    def can_use(self, idx: int):
        if idx < self._block_count and self._bitmap[idx] == 0:
            return True
        else:
            return False

    def read_block(self, idx: int):
        if idx < self._block_count:
            return self._disk[idx].read()
        else:
            return None

    def write_block(self, idx: int, data: str):
        if idx < self._block_count and self.can_use():
            self._disk[idx].write(data)

    def _debug_print_bitmap(self):
        idx = 1
        for bit in self._bitmap:
            print(bit, end=' ')
            if idx % 100 == 0:
                print()
            idx += 1