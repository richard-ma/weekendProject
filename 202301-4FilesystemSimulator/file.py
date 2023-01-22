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
        self._children_block.append(block_id)

    def get_all_children_block(self):
        return self._children_block


if __name__ == "__main__":
    file_data = {
        "type": File.TYPE_FILE,
        "name": "file.file",
        "current_block": 33,
        "parent_block": 0,
        "children_blocks": [],
        "data_buffer": "hello this is my first file." # TODO: add buffer for File
    }

    dire_data = {
        "type": File.TYPE_DIRECTORY,
        "name": "dire",
        "current_block": 25,
        "parent_block": 0,
        "children_blocks": [33, 24],
        "data_buffer": ""
    }

    f = File()
    f.set_type(file_data["type"])
    f.set_name(file_data["name"])
    f.set_current_block(file_data["current_block"])
    f.set_parent_block(file_data["parent_block"])
    for block_id in file_data['children_blocks']:
        f.add_child(block_id)

    d = File()
    d.set_type(dire_data["type"])
    d.set_name(dire_data["name"])
    d.set_current_block(dire_data["current_block"])
    d.set_parent_block(dire_data["parent_block"])
    for block_id in dire_data['children_blocks']:
        d.add_child(block_id)

    assert f.is_type(File.TYPE_FILE) is True
    assert f.is_type(File.TYPE_DIRECTORY) is False
    assert f.get_name() == file_data["name"]
    assert f.get_current_block() == file_data["current_block"]
    assert f.get_parent_block() == file_data["parent_block"]
    assert f.get_all_children_block() == file_data["children_blocks"]

    assert d.is_type(File.TYPE_FILE) is False
    assert d.is_type(File.TYPE_DIRECTORY) is True
    assert d.get_name() == dire_data["name"]
    assert d.get_current_block() == dire_data["current_block"]
    assert d.get_parent_block() == dire_data["parent_block"]
    assert d.get_all_children_block() == dire_data["children_blocks"]