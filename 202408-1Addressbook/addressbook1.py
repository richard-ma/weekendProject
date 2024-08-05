class TelNumber:
    def __init__(self, tel):
        self._tel = tel
    
    def get(self):
        return self._tel

    def update(self, tel):
        old_tel = self._tel
        self._tel = tel
        return old_tel


class Address:
    def __init__(self, address):
        self._address = address

    def get(self):
        return self._address

    def update(self, address):
        old_address = self._address
        self._address = address
        return old_address


class Email:
    def __init__(self, email):
        self._email = email

    def get(self):
        return self._email

    def update(self, email):
        old_email = self._email
        self._email = email
        return old_email


class Party:
    def __init__(self, name):
        self._name = name
        self._telNumbers = list()
        self._addresses = list()
        self._emails = list()

    def add(self, item):
        if isinstance(item, TelNumber):
            self._telNumbers.append(item)
        elif isinstance(item, Address):
            self._addresses.append(item)
        elif isinstance(item, Email):
            self._emails.append(item)
        else:
            raise ValueError("item is not valid value type")

    def __str__(self):
        ret = self._name + '\n'
        ret += 'Tel:' + '\t\t'
        for item in self._telNumbers:
            ret += item.get() + '\t'
        ret += '\n'
        ret += 'Address:' + '\t'
        for item in self._addresses:
            ret += item.get() + '\t'
        ret += '\n'
        ret += 'Email:' + '\t\t'
        for item in self._emails:
            ret += item.get() + '\t'
        ret += '\n'

        return ret


class Person(Party):
    pass


class Organization(Party):
    def __init__(self, name):
        super().__init__(name)
        self._children = list()
    
    def add_child(self, child):
        if isinstance(child, Party):
            self._children.append(child)
        else:
            raise ValueError("Not valid value type")

    def __str__(self):
        ret = super().__str__()

        temp = ''
        if self._children:
            temp += 'Items: \n'
            for item in self._children:
                temp += item.__str__() + '\n'
        
            temp = temp.replace('\n', '\n\t')
        return ret + temp


if __name__ == "__main__":
    p = Person('person')
    o = Organization('Organization')

    p.add(TelNumber('25122131257'))
    p.add(TelNumber('35122131257'))
    p.add(Email('hello@hello.com'))
    p.add(Email('bye@bye.com'))

    o.add(TelNumber('25122131257'))
    o.add(TelNumber('35122131257'))
    o.add(Address('hello street'))
    o.add(Address('bye street'))
    o.add(Email('hello@hello.com'))
    o.add(Email('bye@bye.com'))

    parent_o = Organization("Parent")
    parent_o.add(TelNumber('25122131257'))
    parent_o.add(TelNumber('35122131257'))
    parent_o.add(Address('hello street'))
    parent_o.add(Address('bye street'))
    parent_o.add(Email('hello@hello.com'))
    parent_o.add(Email('bye@bye.com'))

    parent_o.add_child(p)
    parent_o.add_child(o)

    print(p)
    print("-"*80)
    print(o)
    print("-"*80)
    print(parent_o)