import argparse


parser = argparse.ArgumentParser(description="pass a nunber")
parser.add_argument('integers', type=str, help="input a number")

args = parser.parse_args()

print(args)