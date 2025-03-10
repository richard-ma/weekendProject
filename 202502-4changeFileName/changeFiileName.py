import os
from app import App


class ChangeFileName(App):
    STUDENTS_DATA_FILE = "./students.csv"
    PHOTOS_DATA_FILE = "./photodata.csv"
    PHOTOS_DIR = "./photos"

    def process(self):
        # load student data
        student_data = self.readCsvToDict(ChangeFileName.STUDENTS_DATA_FILE)

        # load photos data
        photos_data = self.readCsvToDict(ChangeFileName.PHOTOS_DATA_FILE)

        # find new filename in student data
        for photo in photos_data:
            old_filename, ext_name = photo['file'].split('.')

            name = photo['name']
            for item in student_data:
                if item['name'] == name:
                    new_filename = '.'.join([item['id'], ext_name])
            
            new_filename = os.path.join(ChangeFileName.PHOTOS_DIR, new_filename)
            old_filename = '.'.join([old_filename, ext_name])
            old_filename = os.path.join('.', old_filename)
            print(new_filename, old_filename)
            # rename file
            os.rename(old_filename, new_filename)


if __name__ == "__main__":
    app = ChangeFileName()
    app.run()