import click


@click.command()
@click.option("-i", "--id", required=True, help="input an id")
@click.option("-n", "--num", type=int, help="input a num", show_default=True)
def main(id, num):
    click.echo(f"your {id=} {num=}")


if __name__ == "__main__":
    main()