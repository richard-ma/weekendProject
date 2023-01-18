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


class File(AbstrackFile):
    def __init__(self):
        super().__init__()


class Directory(AbstrackFile):
    def __init__(self):
        super().__init__()
        self._children_block = list()