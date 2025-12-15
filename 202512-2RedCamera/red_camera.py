from PIL import Image
import numpy as np
import os

def get_test_jpg_iterator(target_dir="."):
    """
    生成器函数：返回目录中以test_开头的JPG文件路径迭代器
    :param target_dir: 目标目录（默认当前目录）
    :return: 迭代器，逐个返回符合条件的文件绝对路径
    """
    # 遍历目录，惰性生成符合条件的文件路径
    for file in os.listdir(target_dir):
        # 严格筛选：test_开头 + .jpg后缀（兼容大小写） + 是文件
        if (file.startswith("test_") and 
            file.lower().endswith(".jpg") and 
            os.path.isfile(os.path.join(target_dir, file))):
            # 转为绝对路径，避免目录歧义
            abs_path = os.path.abspath(os.path.join(target_dir, file))
            yield abs_path

def median_binarization(image_path):
    """
    基于像素中位数对JPG图片进行二值化处理
    :param image_path: 输入JPG图片路径
    """
    # 打开图片并转为灰度图（便于计算像素中位数）
    try:
        img = Image.open(image_path)
        gray_img = img.convert('L')  # 转为8位灰度图（0-255）
    except Exception as e:
        print(f"打开图片失败：{e}")
        return
    
    # 将灰度图转为numpy数组，方便计算中位数
    img_array = np.array(gray_img)
    # 计算所有像素的中位数
    median_value = np.median(img_array)
    print(f"图片像素中位数：{median_value}")
    
    # 创建新的RGB图片（用于保存红黑二值化结果）
    height, width = img_array.shape
    new_img = Image.new("RGB", (width, height))
    new_pixels = new_img.load()
    
    # 遍历每个像素，进行二值化并设置颜色
    for y in range(height):
        for x in range(width):
            pixel_value = img_array[y, x]
            if pixel_value > median_value:
                # 高于中位数：设为红色 (255, 0, 0)
                new_pixels[x, y] = (255, 0, 0)
            else:
                # 低于/等于中位数：设为黑色 (0, 0, 0)
                new_pixels[x, y] = (0, 0, 0)
    
    # 保存处理后的图片（同一目录，原文件名加"_binary"后缀）
    save_path = image_path.replace(".jpg", "_binary.jpg")
    new_img.save(save_path)
    print(f"处理完成，图片已保存至：{save_path}")

# 示例调用
if __name__ == "__main__":
    # 替换为你的JPG图片路径（如："test.jpg"）
    for input_image in get_test_jpg_iterator():
    	median_binarization(input_image)

