import cv2 as cv

if __name__ == "__main__":
    capture = cv.VideoCapture(0)
    while capture.isOpened():
        retval, image = capture.read()
        image = cv.cvtColor(image, cv.COLOR_BGR2GRAY)
        cv.imshow("Video", image)
        key = cv.waitKey(1)
        if key == 32:
            break
    capture.release()
    # cv.destoryAllWindows()