from pymemcache.client.base import Client

client = Client(('localhost', 9999))
client.set("user:1", "John Doe")
print(client.get("user:1"))