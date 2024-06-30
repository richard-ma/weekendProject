class Book:
    def __init__(self, _id: str):
        self._id = _id
        self._name = None
        self._author = None
        self._isbn = None

    def set_name(self, name: str):
        self._name = name

    def get_name(self):
        if self._name is None:
            raise ValueError("Name is None")
        
        return self._name

    def set_author(self, author: str):
        self._author = author

    def get_name(self):
        if self._author is None:
            raise ValueError("Author is None")
        
        return self._author

    def set_isbn(self, isbn: str):
        self._isbn = isbn

    def get_isbn(self):
        if self._isbn is None:
            raise ValueError("ISBN is None")
        
        return self._isbn