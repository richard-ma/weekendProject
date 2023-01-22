class File:
    TYPE_DIRECTORY = 0
    TYPE_FILE = 1


    def __init__(self):
        self._type = None
        self._name = ""
        self._current_block = None
        self._parent_block = None
        self._children_block = list()

    def set_type(self, t: int):
        if t not in [File.TYPE_DIRECTORY, File.TYPE_FILE]:
            raise TypeError("Such file type not found.")

        self._type = t

    def set_name(self, name: str):
        self._name = name
    
    def get_name(self):
        return self._name

    def is_type(self, t: int):
        if t not in [File.TYPE_DIRECTORY, File.TYPE_FILE]:
            raise TypeError("Such file type not found.")

        return self._type == t

    def set_current_block(self, block_id: int):
        self._current_block = block_id

    def get_current_block(self):
        return self._current_block

    def get_parent_block(self):
        return self._parent_block

    def set_parent_block(self, block_id: int):
        self._parent_block = block_id

    def add_child(self, block_id: int):
        self._children_block.append(item)

    def get_all_children_block(self):
        return self._children_block


if __name__ == "__main__":
    file_name = "file.file"
    dire_name = "dire"
