#!/usr/bin/env python3


class EventManager:
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


class Hotelier:
    def __init__(self):
        print("Arranging the Hotel for Marriage? --")

    def __isAvailable(self):
        print("Is the Hotel free for the event on given day?")
        return True

    def bookHotel(self):
        if self.__isAvailable():
            print("Registered the Booking")


class Florist:
    def __init__(self):
        print("Flower Decorations for the Event? --")

    def setFlowerRequirements(self):
        print("Carnations, Rose and Lilies would be used for Decorations")

class Caterer:
    def __init__(self):
        print("Food Arrangements for the Event")

    def setCuisine(self):
        print("Chinese & Continental Cuisine to be served")

class Musician:
    def __init__(self):
        print("Musical Arrangements for the Marriage")

    def setMusicType(self):
        print("Jazz and Classical will be played")


class You:
    def __init__(self):
        print("You:: Whoa! Marriage Arrangements??")
    
