import os
from block import Block
from file import File


class Filesystem:
    DATA_FILENAME = 'data.dat'

    BLOCK_UNUSED = 0
    BLOCK_USED = 1

    def __init__(self, block_size=1024, block_count=1024):
        self._block_size = block_size
        self._block_count = block_count
        self._bitmap = [0] * self._block_count
        self._next_size = len(str(self._block_count))
        self._data_size = self._block_size - self._next_size
        self._disk = {k: Block(self._block_size, self._next_size) for k in range(self._block_count)}
        '''
        # setting block 0: root Directory /
        root_dir = Directory()
        root_dir.set_name("/")
        root_dir.set_current_block(0)
        # parent is None represents the directory is root directory
        self.write_block(0, str(root_dir))

        # set pwd
        self._pwd = root_dir
        '''

    def _is_block_used(self, idx: int):
        if idx < self._block_count and self._bitmap[idx] == Filesystem.BLOCK_USED:
            return True
        else:
            return False

    def _get_block(self, idx: int):
        if idx < self._block_count:
            return self._disk[idx]
        else:
            raise IndexError("Block index out of [size: {:d}] {:d}".format(self._block_count, idx))

    def _write_block(self, idx: int, data: str, next_block_id=0):
        if not self._is_block_used(idx):
            self._disk[idx].write(data, next_block_id)
            self._bitmap[idx] = Filesystem.BLOCK_USED 
        else:
            raise Exception("Block {:d} is used.".format(idx))

    def _malloc_one_block(self, start=0):
        for idx in range(start+1, self._block_count):
            if not self._is_block_used(idx): # success
                return idx

        # failed
        raise Exception("Not enough disk space.")


    def write(self, data: str):
        size = len(data)

        block_data = data[:self._data_size]
        data = data[self._data_size:]

        file_block_id = block_id = self._malloc_one_block()

        while len(data) > 0:
            new_block_id = self._malloc_one_block(block_id)

            self._write_block(block_id, block_data, new_block_id)

            block_data = data[:self._data_size]
            data = data[self._data_size:]
            block_id = new_block_id

        self._write_block(block_id, block_data)

        return file_block_id

    def read(self, idx: int):
        data = ""

        block = self._get_block(idx) # first block
        data += block.read()

        while block.has_next_block():
            block = self._get_block(block.get_next_block()) # update block to next block
            data += block.read()

        return data

    '''
    def malloc(self, size: int):
        ret = []
        counter = size // self._block_size
        # ceil
        if counter != size / self._block_size:
            counter += 1

        for i in range(self._block_count):
            if self._bitmap[i] == 0:
                ret.append(i)
            
            # success
            if len(ret) == counter:
                for i in ret:
                    self._bitmap[i] = 1
                return ret

        # failed
        raise Exception("Not enough disk space.")

    def free(self, block_ids: list):
        for block_id in block_ids:
            if block_id == 0:
                raise Exception("Cann't free root directory!")
            self._bitmap[block_id] = 0

    def get_pwd(self):
        return self._pwd

    # TODO: fix this function
    def change_dir(self, dire_name: str):
        children_blocks = self._pwd.get_all_children_block_id()
        for block_id in children_blocks:
            d = AbstrackFile()
            d.load_from_block(self.read_block(block_id))
            if d.is_dir() and d.get_name().lower() == dire_name.lower():
                self._pwd = d
                break
        return self._pwd

    def quit(self):
        # save to data file
        self._save(Filesystem.DATA_FILENAME)

    def load(self):
        # load from data file
        if os.path.exists(Filesystem.DATA_FILENAME) and os.path.isfile(Filesystem.DATA_FILENAME):
            self._load(Filesystem.DATA_FILENAME)

            # load root directory information
            root_dir = Directory()
            root_dir.load_from_block(self.read_block(0))
            self._pwd = root_dir

    def _save(self, filename: str):
        with open(filename, 'w+') as f:
            f.writelines([b.read()+'\n' for k, b in self._disk.items()])

    def _load(self, filename: str):
        with open(filename, 'r+') as f:
            idx = 0
            for line in f.readlines():
                self._disk[str(idx)].write(line.strip('\n'))
                idx += 1
    '''

    def _debug_print_bitmap(self):
        idx = 1
        for bit in self._bitmap:
            print(bit, end=' ')
            if idx % 100 == 0:
                print()
            idx += 1


if __name__ == "__main__":
    fs = Filesystem()
    '''
    fs.load()
    block_id = 33
    data = "hello"
    assert fs.write_block(block_id, data) is True
    assert fs.read_block(block_id) == data
    assert fs.get_pwd().get_name() == '/' # root directory name is /
    assert fs.get_pwd().get_current_block() == 0 # root directory block id is 0
    fs.quit()
    '''
    # test one block data
    data = "zkzk"*4
    block_id = fs.write(data)
    assert data == fs.read(block_id)

    # test multi blocks data
    data = "zkzk"*1000
    block_id = fs.write(data)
    assert data == fs.read(block_id)