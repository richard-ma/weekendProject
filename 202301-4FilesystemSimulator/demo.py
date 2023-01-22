import systemcall


if __name__ == "__main__":
    test_data = "this is some test data."*200

    fp = systemcall.open("test.py")
    systemcall.write(fp, test_data)
    systemcall.close(fp)
    assert systemcall.read(fp) == test_data
    print(systemcall.read(fp))

    systemcall.save_to_disk()