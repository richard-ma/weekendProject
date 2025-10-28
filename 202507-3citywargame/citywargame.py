

class City:
    def __init__(self, id, group=0, max_level=1):
        self.id = id
        self.level = 1
        self.max_level = max_level
        self.group = group 
        self.hp = self.max_hp()

        self.soldiers_inside = 0
        self.soliders_outside = 0

    @property
    def capacity(self):
        return self.level * 10

    @property
    def new_solider_rate(self):
        return 1 + (self.level-1) * 0.2

    def max_hp(self):
        return self.level * 100

    def level_up(self):
        if self.level <= self.max_level:
            self.level += 1
            self.hp = self.max_hp()
        else:
            raise ValueError("City is already at maximum level.")

    def soldier_in(self, num):
        self.soldiers_inside += num
        
        if self.soldiers_inside > 10:
            self.soliders_outside += self.soldiers_inside - 10
            self.level_up()
            self.soldiers_inside = 0

    def turn_end(self):
        self.soliders_outside += self.new_solider_rate

    def __str__(self):
        return f"[{self.group}]{self.id}[{self.level}]: {self.hp}/{self.max_hp()}"

if __name__ == "__main__":
    cities = []
    for i in range(10):
        city = City(id=i)
        cities.append(city)

    for c in cities:
        print(c)
        c.level_up()
        print(c)