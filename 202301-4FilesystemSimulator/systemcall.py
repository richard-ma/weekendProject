from filesystem import Filesystem
from file import File


def open(filename: str):
    fp = File(fs)
    fp.set_type(File.TYPE_FILE)
    fp.set_name(filename)
    # TODO set parent block

    return fp

def flush(fp: "File"):
    fp.write() # TODO

def close(fp: "File"):
    flush(fp)

def write(fp: "File", data: str):
    fp.set_buffer(data)

def read(fp: "File"):
    return fp.read()

def save_to_disk():
    fs.quit()



# systemcall initial
fs = Filesystem()
# load from data file
fs.initial()

# setting block 0: root Directory /
root_dir = File(fs)
root_dir.set_type(File.TYPE_DIRECTORY)
root_dir.set_name("/")