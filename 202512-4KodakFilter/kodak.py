# 导入 Pillow 库的 Image 模块（用于图像处理）
from PIL import Image
# 导入 os 模块（用于处理文件路径和文件名）
import os
# 导入 sys 模块（用于获取命令行参数）
import sys

import numpy as np

light_rgbs = {
    'kodak': (255, 180, 100),
    "white": (250, 245, 210), # 中度黄调乳白（米黄乳白，质感浓郁）
    'red': (255, 0, 0),
    "blue": (138, 131, 249), # 淡紫蓝（略偏紫，清新雅致）
    "yellow": (240, 230, 140), # 浅卡其黄（轻微低饱和，带淡棕调，柔和自然）
}

dark_rgbs = {
    'black': (0, 0, 0),
    'kodak-black': (20, 60, 70),
}

def binarize_and_replace_color(img, dark_rgb, light_rgb):
    """
    将图片按像素亮度中位数二值化，并分别替换暗部、亮部为指定RGB颜色
    :param img: Pillow 读取的 Image 对象（输入图片）
    :param dark_rgb: 暗部替换颜色，RGB元组，如 (0, 0, 0)（黑色）
    :param light_rgb: 亮部替换颜色，RGB元组，如 (255, 255, 255)（白色）
    :return: 处理后的 Pillow Image 对象
    """
    # 1. 验证输入参数合法性
    # 验证RGB元组格式（3个整数，取值0-255）
    def is_valid_rgb(rgb_tuple):
        if not (isinstance(rgb_tuple, tuple) and len(rgb_tuple) == 3):
            return False
        for val in rgb_tuple:
            if not (isinstance(val, int) and 0 <= val <= 255):
                return False
        return True
    
    if not is_valid_rgb(dark_rgb):
        raise ValueError("暗部颜色必须是包含3个0-255整数的RGB元组，例如 (0,0,0)")
    if not is_valid_rgb(light_rgb):
        raise ValueError("亮部颜色必须是包含3个0-255整数的RGB元组，例如 (255,255,255)")
    # 验证输入是否为Pillow Image对象
    if not isinstance(img, Image.Image):
        raise TypeError("输入图片必须是Pillow库读取的Image对象")

    # 2. 转换图片为灰度模式（便于计算亮度中位数），同时保留原始尺寸
    gray_img = img.convert('L')  # 'L' 表示8位灰度图（0=黑，255=白）
    width, height = gray_img.size

    # 3. 获取灰度像素数据，并计算像素亮度中位数
    # 将灰度图片转换为numpy数组，便于数值计算（形状：height × width）
    gray_array = np.array(gray_img)
    # 扁平化数组（转为一维），计算中位数
    pixel_flat = gray_array.flatten()
    median_threshold = np.median(pixel_flat)  # 像素亮度中位数作为二值化阈值

    # 4. 创建空白画布（RGB模式），用于绘制替换后的图片
    result_img = Image.new('RGB', (width, height))
    result_pixels = result_img.load()  # 获取像素操作对象，便于逐像素赋值

    # 5. 逐像素判断，二值化并替换颜色
    for y in range(height):
        for x in range(width):
            # 获取当前像素的灰度亮度值
            pixel_brightness = gray_array[y, x]
            # 以中位数为阈值：低于等于中位数 → 暗部，替换为dark_rgb；高于中位数 → 亮部，替换为light_rgb
            if pixel_brightness <= median_threshold:
                result_pixels[x, y] = dark_rgb
            else:
                result_pixels[x, y] = light_rgb

    # 6. 返回处理后的Pillow Image对象
    return result_img

def process_image(image_path):
    """
    读取 JPG 图片，并将其保存为 原主文件名_filter.jpg 格式
    :param image_path: 原始 JPG 图片的路径（相对路径或绝对路径）
    """
    try:
        # 1. 使用 Pillow 打开（读取）JPG 图片
        img = Image.open(image_path)
        
        # 2. 处理文件名：分离主文件名和扩展名，拼接新文件名
        file_name_without_ext, file_ext = os.path.splitext(image_path)
        
        # 校验原始文件是否为 JPG 格式（避免处理非 JPG 文件）
        if file_ext.lower() not in ['.jpg', '.jpeg']:
            print(f"错误：文件 {image_path} 不是 JPG/JPEG 格式，请传入正确格式图片！")
            return

        for light_color_name, light_rgb in light_rgbs.items():
            for dark_color_name, dark_rgb in dark_rgbs.items():
                # 3. 调用二值化并替换颜色函数，处理图片
                img_processed = binarize_and_replace_color(img, dark_rgb=dark_rgb, light_rgb=light_rgb)
                
                # 4. 保存图片（格式为 JPG），处理透明通道避免异常
                # 拼接新文件名：原主文件名 + _darkColor_lightColor + .jpg
                new_file_name = f"{file_name_without_ext}_{light_color_name}_on_{dark_color_name}.jpg"
                if img_processed.mode in ('RGBA', 'P'):
                    img_processed = img_processed.convert('RGB')
                img_processed.save(new_file_name, format='JPEG')
                
                print(f"图片处理成功！")
                print(f"原始文件：{image_path}")
                print(f"保存文件：{new_file_name}")
        
    except FileNotFoundError:
        print(f"错误：未找到文件 {image_path}，请检查文件路径是否正确！")
    except Exception as e:
        print(f"未知错误：{e}")

# ------------------- 程序入口（从命令行获取参数） -------------------
if __name__ == "__main__":
    # 校验命令行参数数量是否合法
    # sys.argv[0] 是程序本身文件名，sys.argv[1] 是第一个传入的命令行参数（图片路径）
    if len(sys.argv) != 2:
        print("使用方法错误！请在命令行传入唯一的 JPG 图片路径作为参数。")
        print("命令格式：")
        print("  Windows/Mac/Linux: python 脚本名.py 图片路径")
        print("  示例：python image_process.py test.jpg  （图片与脚本同目录）")
        print("  示例：python image_process.py C:/Users/XXX/Desktop/test.jpg  （绝对路径）")
        # 退出程序，返回非0状态码表示异常退出
        sys.exit(1)
    
    # 获取命令行传入的图片路径（sys.argv[1] 是第一个用户传入参数）
    target_image_path = sys.argv[1]
    
    # 调用函数处理图片
    process_image(target_image_path)