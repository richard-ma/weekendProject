class User:
    def __init__(self, _id: str):
        self._id = _id
        self._name = None

    def set_name(self, name: str):
        self._name = name

    def get_name(self):
        if self._name is None:
            raise ValueError("Name is None")

        return self._name