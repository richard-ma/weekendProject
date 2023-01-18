import os
from block import Block
from file import File, Directory


class Filesystem:
    DATA_FILENAME = 'data.dat'

    def __init__(self, block_size=1024, block_count=1024):
        self._block_size = block_size
        self._block_count = block_count
        self._bitmap = [0] * self._block_count
        self._disk = {k: Block(self._block_size) for k in range(self._block_count)}

        # setting block 0: root Directory /
        root_dir = Directory()
        root_dir.set_name("/")
        # parent is None represents the directory is root directory
        self.write_block(0, str(root_dir))

    def quit(self):
        # save to data file
        self._save()

    def load(self):
        # load from data file
        self._load()

    def _save(self):
        with open(Filesystem.DATA_FILENAME, 'w+') as f:
            f.writelines([b.read()+'\n' for k, b in self._disk.items()])

    def _load(self):
        if os.path.exists(Filesystem.DATA_FILENAME) and os.path.isfile(Filesystem.DATA_FILENAME):
            with open(Filesystem.DATA_FILENAME, 'r+') as f:
                idx = 0
                for line in f.readlines():
                    self._disk[idx].write(line.strip('\n'))
                    idx += 1

    def _block_can_use(self, idx: int):
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
        if idx < self._block_count and self._block_can_use(idx):
            self._disk[idx].write(data)
            self._bitmap[idx] = 1
            return True
        else:
            raise Exception("Block: {:d} is used. Cann't be written".format(idx))

    def _debug_print_bitmap(self):
        idx = 1
        for bit in self._bitmap:
            print(bit, end=' ')
            if idx % 100 == 0:
                print()
            idx += 1


if __name__ == "__main__":
    fs = Filesystem()
    block_id = 33
    data = "hello"
    assert fs.write_block(block_id, data) is True
    assert fs.read_block(block_id) == data
    #fs.write_block(block_id, data)
    fs.quit()