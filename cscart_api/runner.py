import os

from cscartapi import CscartAPI


if __name__ == "__main__":
    api = CscartAPI(
        os.environ['CSCART_BASE_URL'],
        os.environ['CSCART_USERNAME'],
        os.environ['CSCART_API_KEY'],
        )

    # api.get('order') # get all orders
    # api.get('order').sort_by(key) # geet all order by desc
    # api.get('order').sort_by(key, asc) # get all order by asc
    # api.get('order').page(2) # get page 2
    # api.get('order').page(2, 50) # set item of page is 50, and get the 2nd page
    # api.get('order', 3) # get certain order which id is 3

    # api.create('order', data) # create an order by data

    # api.delete('order', 3) # delete the order which id is 3

    # api.update('order', 3, data) # update order which id is 3 with data

    # api.commit() # execute operation and send request to server