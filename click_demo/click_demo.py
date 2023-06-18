import click


@click.command()
@click.option("-n", "--num", help="input a num")
def main(num):
    click.echo(f"{num =}")


if __name__ == "__main__":
    main()