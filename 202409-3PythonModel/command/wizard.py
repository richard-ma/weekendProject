#!/usr/bin/env python3


class Wizard():
    def __init__(self, src, rootdir):
        self.choices = []
        self.rootdir = rootdir
        self.src = src

    def preferences(self, command):
        self.choices.append(command)

    def execute(self):
        for choice in self.choices:
            if list(choice.values())[0]: # value为True时输出Copy xxx
                print("Copying binaries --", self.src, " to ", self.rootdir)
            else:
                print("No Operation") # value为False时输出No Operation


if __name__ == '__main__':
    ## Client code
    wizard = Wizard('python3.5.gzip', '/usr/bin/')
    ## Users chooses to install Python only
    wizard.preferences({'python':True})
    wizard.preferences({'java':False})
    wizard.execute()
