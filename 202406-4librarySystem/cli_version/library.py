from user import User
from book import Book

class Library:
    def __init__(self):
        self._users = list()
        self._books = list()

        self._current_user = None

    def load_user(self):
        usernames = ['John', 'Lily', 'Richard']
        id_start = 1
        for username in usernames:
            u = User(str(id_start))
            u.set_name(username)
            self._users.append(u)
            id_start += 1

    def load_book(self):
        books = [
            ['Gone with wind', 'Bob', '0001'],
            ['丰乳肥臀', '莫言', '0002'],
            ['废都贾平凹', '贾平凹', '0003'],
        ]
        id_start = 1
        for book in books:
            b = Book(str(id_start))
            b.set_name(book[0])
            b.set_author(book[1])
            b.set_isbn(book[2])
            self._books.append(b)
            id_start += 1

    def load(self):
        self.load_user()
        self.load_book()

    def list_books(self):
        for book in self._books:
            print(book)

    def find_book(self, book_id: str):
        for book in self._books:
            if book._id == book_id:
                return book
        return None

    def user_login(self):
        if self._current_user is None:
            input_username = input("请输入要登录的用户名：")
            for user in self._users:
                if input_username == user.get_name():
                    self._current_user = user

    def user_logout(self):
        self._current_user = None

    def print_main_menu(self):
        print("请选择数字进行操作：")
        print("1. 列出所有书籍")
        if self._current_user is None:
            print("2. 用户登录")
        else:
            print("2. 用户" + self._current_user.get_name() + "已登录，选择登出")
        print("3. 用户借书")
        print("4. 用户还书")
        print("5. 退出")

    def main(self):
        self.load()

        system_running = True
        while(system_running):
            self.print_main_menu()
            try:
                cmd = int(input("请输入操作选项："))
            except Exception as e:
                pass
            
            if cmd == 5:
                system_running = False
            elif cmd == 1:
                self.list_books()
            elif cmd == 2:
                if self._current_user is None:
                    self.user_login()
                else:
                    self.user_logout()
            elif cmd == 3:
                if self._current_user is None:
                    print("请先登录用户!")
                    continue
                book_id = input("请输入要借出书的ID：")
                book = self.find_book(book_id)
                if book:
                    book.borrowed_by(self._current_user._id)
            elif cmd == 4:
                if self._current_user is None:
                    print("请先登录用户!")
                    continue
                book_id = input("请输入要归还书的ID：")
                book = self.find_book(book_id)
                if book:
                    book.returned_by(self._current_user._id)
            

if __name__ == "__main__":
    library = Library()
    library.main()