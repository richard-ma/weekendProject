class Book:
    def __init__(self, _id: str):
        self._id = _id
        self._name = None
        self._author = None
        self._isbn = None
        self._borrowed_by = None

    def set_name(self, name: str):
        self._name = name

    def get_name(self):
        if self._name is None:
            raise ValueError("Name is None")
        
        return self._name

    def set_author(self, author: str):
        self._author = author

    def get_author(self):
        if self._author is None:
            raise ValueError("Author is None")
        
        return self._author

    def set_isbn(self, isbn: str):
        self._isbn = isbn

    def get_isbn(self):
        if self._isbn is None:
            raise ValueError("ISBN is None")
        
        return self._isbn

    def borrowed_by(self, user_id: str):
        if self._borrowed_by is None:
            self._borrowed_by = user_id
        else:
            raise ValueError("Book has been borrowed.")

    def returned_by(self, user_id: str):
        if self._borrowed_by == user_id:
            self._borrowed_by = None
        else:
            raise ValueError("Book has not been borrowed by current user.")

    def __str__(self):
        return self._id + '\t' + self._name + '\t' + self._author + '\t' + self._isbn + '\t' + (self._borrowed_by if self._borrowed_by else '未借出')