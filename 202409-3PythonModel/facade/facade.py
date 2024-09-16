#!/usr/bin/env python3


class EventManager: # 作为统一接口，供You调用的门面
    def __init__(self):
        print("Event Manager:: Let me talk to the folks")

        def arrage(self):
            self.hotelier = Hotelier()
            self.hotelier.bookHotel()

            self.florist = Florist()
            self.florist.setFlowerRequriements()

            self.caterer = Caterer()
            self.caterer.setCuisine()

            self.musician = Musician()
            self.musician.setMusicType()


# 负责预定酒店的负责人
class Hotelier:
    def __init__(self):
        print("Arranging the Hotel for Marriage? --")

    def __isAvailable(self):
        print("Is the Hotel free for the event on given day?")
        return True

    def bookHotel(self):
        if self.__isAvailable():
            print("Registered the Booking")


# 负责花卉装饰的人员
class Florist:
    def __init__(self):
        print("Flower Decorations for the Event? --")

    def setFlowerRequirements(self):
        print("Carnations, Rose and Lilies would be used for Decorations")


# 厨师
class Caterer:
    def __init__(self):
        print("Food Arrangements for the Event")

    def setCuisine(self):
        print("Chinese & Continental Cuisine to be served")


# 乐手
class Musician:
    def __init__(self):
        print("Musical Arrangements for the Marriage")

    def setMusicType(self):
        print("Jazz and Classical will be played")


# 调用EventManager的你
class You(object):
    def __init__(self):
        print("You:: Whoa! Marriage Arrangements? ? ! ! ! ")

    def askEventManager(self):
        print("You:: Let's Contact the Event Manager\n\n")
        em = EventManager()
        em.arrange()

    def __del__(self):
        print("You:: Thanks to Event Manager, all preparations done!  Phew! ")

if __name__ == "__main__":
    you = You()
    you.askEventManager() # 所有细节都由EventManger来管理安排
