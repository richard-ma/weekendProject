import os
from app import App


class CombineCsv(App):
    DEFAULT_INPUT_DIR = "test-data"
    DEFAULT_EXT = '.csv'
    DEFAULT_OUTPUT_FILENAME = 'all.csv'

    def process(self):
        # input filename
        input_dir = input("请将要合并的CSV文件所在目录拖动到窗口范围内。")
        if len(input_dir) < 1:
            input_filename = CombineCsv.DEFAULT_INPUT_DIR
    
        ret = list()
        for root, dirs, files in os.walk(input_dir):
            for f in files:
                input_filename = os.path.join(root, f)
                name, ext = os.path.splitext(input_filename)
                if ext == CombineCsv.DEFAULT_EXT:
                    input_data = self.readCsvToDict(input_filename)
                    ret.extend(input_data)

            self.writeCsvFromDict(os.path.join(root, CombineCsv.DEFAULT_OUTPUT_FILENAME), ret)


if __name__ == "__main__":
    app = CombineCsv()
    app.run()