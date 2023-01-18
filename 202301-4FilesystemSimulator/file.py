class AbstrackFile:
    def __init__(self):
        self._start_block = None
        self._name = ""
        self._parent_block = None

    def set_name(self, name: str):
        self._name = name
    
    def get_name(self):
        return self._name

    def is_file(self):
        return isinstance(self, File)

    def is_dir(self):
        return isinstance(self, Directory)

    def parent_block(self):
        return self._parent_block

    def set_parent_block(self, block_id: int):
        self._parent_block = block_id

    def __str__(self):
        return ';'.join([str(k)+":"+str(v) for k,v in self.__dict__.items()])

    def load_from_block(self, s: str):
        for item in s.split(";"):
            k, v = item.split(":")
            self.__dict__[k] = v


class File(AbstrackFile):
    def __init__(self):
        super().__init__()


class Directory(AbstrackFile):
    def __init__(self):
        super().__init__()
        self._children_block = list()

    def add_child(self, block_id: int):
        self._children_block.append(item)

    def get_all_children_block_id(self):
        return self._children_block


if __name__ == "__main__":
    file_name = "file.file"
    dire_name = "dire"

    f = File()
    f.set_name(file_name)
    assert f.get_name() == file_name
    assert f.is_dir() is False
    assert f.is_file() is True
    new_f = File()
    new_f.load_from_block(str(f))
    assert str(new_f) == str(f)

    d = Directory()
    d.set_name(dire_name)
    assert d.get_name() == dire_name
    assert d.is_dir() is True
    assert d.is_file() is False
    new_d = Directory()
    new_d.load_from_block(str(d))
    assert str(new_d) == str(d)