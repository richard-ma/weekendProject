from filesystem import Filesystem


class File:
    TYPE_DIRECTORY = 0
    TYPE_FILE = 1

    def __init__(self, filesystem: "Filesystem"):
        self._type = None
        self._name = ""
        self._current_block = None
        self._parent_block = None
        self._children_block = list()
        self._data_buffer = ""

        self._filesystem = filesystem

    def set_type(self, t: int):
        if t not in [File.TYPE_DIRECTORY, File.TYPE_FILE]:
            raise TypeError("Such file type not found.")

        self._type = t

    def is_type(self, t: int):
        if t not in [File.TYPE_DIRECTORY, File.TYPE_FILE]:
            raise TypeError("Such file type not found.")

        return self._type == t

    def set_buffer(self, data: str):
        self._data_buffer = data

    def get_buffer(self):
        return self._data_buffer

    def set_name(self, name: str):
        self._name = name
    
    def get_name(self):
        return self._name

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

    def save(self):
        children_list = list(map(str, self._children_block))
        children_list = ','.join(children_list)
        head = [
            self._type,
            self._name,
            self._current_block,
            self._parent_block,
            children_list,
        ]
        head = ":".join(list(map(str, head)))
        body = self._data_buffer

        return ";;".join([head, body])

    def load(self, s: str):
        head, body = s.split(";;")
        self._data_buffer = body

        head = head.split(":")
        self._type = int(head[0])
        self._name = head[1]
        self._current_block = int(head[2])
        self._parent_block = int(head[3])

        # deal with empty head[4]
        if "," in head[4]:
            self._children_block = list(head[4].split(","))
            for idx in range(len(self._children_block)):
                self._children_block[idx] = int(self._children_block[idx])
        elif len(head[4]) == 0: # empty head[4]
            self._children_block = list()
        else: # only one item in children_block
            self._children_block = [int(head[4])]


if __name__ == "__main__":
    file_data = {
        "type": File.TYPE_FILE,
        "name": "file.file",
        "current_block": 33,
        "parent_block": 0,
        "children_blocks": list(),
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

    fs = Filesystem()

    f = File(fs)
    f.set_type(file_data["type"])
    f.set_name(file_data["name"])
    f.set_buffer(file_data["data_buffer"])
    f.set_current_block(file_data["current_block"])
    f.set_parent_block(file_data["parent_block"])
    for block_id in file_data['children_blocks']:
        f.add_child(block_id)

    d = File(fs)
    d.set_type(dire_data["type"])
    d.set_name(dire_data["name"])
    d.set_current_block(dire_data["current_block"])
    d.set_parent_block(dire_data["parent_block"])
    for block_id in dire_data['children_blocks']:
        d.add_child(block_id)

    def file_assertions(f: "File", file_data):
        assert f.is_type(File.TYPE_FILE) is True
        assert f.is_type(File.TYPE_DIRECTORY) is False
        assert f.get_name() == file_data["name"]
        assert f.get_buffer() == file_data["data_buffer"]
        assert f.get_current_block() == file_data["current_block"]
        assert f.get_parent_block() == file_data["parent_block"]
        assert f.get_all_children_block() == file_data["children_blocks"]

    def dire_assertions(d: "File", dire_data):
        assert d.is_type(File.TYPE_FILE) is False
        assert d.is_type(File.TYPE_DIRECTORY) is True
        assert d.get_name() == dire_data["name"]
        assert d.get_current_block() == dire_data["current_block"]
        assert d.get_parent_block() == dire_data["parent_block"]
        assert d.get_all_children_block() == dire_data["children_blocks"]

    file_assertions(f, file_data)
    dire_assertions(d, dire_data)

    s = f.save()
    f.load(s)
    file_assertions(f, file_data)

    s = d.save()
    d.load(s)
    dire_assertions(d, dire_data)