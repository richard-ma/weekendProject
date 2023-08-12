import os

from cscartapi import CscartAPI


if __name__ == "__main__":
    api = CscartAPI(
        os.environ['CSCART_BASE_URL'], # cscart url: http://doamin.com
        os.environ['CSCART_USERNAME'], # admin username
        os.environ['CSCART_API_KEY'],  # api key
        )

    # api.get('orders') # get all order
    # api.get('orders').sort_by(key) # geet all order by desc
    # api.get('orders').sort_by(key, asc) # get all order by asc
    # api.get('orders').page(2) # get page 2
    # api.get('orders').page(2, 50) # set item of page is 50, and get the 2nd page
    # api.get('orders', 3) # get certain order which id is 3

    # api.create('orders', data) # create an order by data

    # api.delete('orders', 3) # delete the order which id is 3

    # api.update('orders', 3, data) # update order which id is 3 with data

    # api.commit() # execute operation and send request to server

    api.get('orders').order_by('order_id', 'desc').page(1, 50).filter('status', 'P')
    response = api.commit()
    print(response)

    api.get('users').page(1, 50)
    response = api.commit()
    print(response)