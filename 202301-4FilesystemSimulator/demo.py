from filesystem import Filesystem


if __name__ == "__main__":
    fs = Filesystem()

    for id, b in fs._disk.items():
        print(id, b)

    fs.print_bitmap()