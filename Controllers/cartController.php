1. First item to the cart:

    keep the id of the product in the cart or session (price, qty)

2. same product being added to the cart
    look inside the table or session if this product already exists
        if it exists then just update the qty

        if do not exists then go to step 1

3. increase only the qty from the cart page

4. delete an item from the cart page

cart_master:
    id
    session_id => session_id();
    user_id 
    discount_total
    sub_total
    grand_total
    date_added
    ip_address


cart_details
    id
    cart_id
    product_id
    qty
    price
    discount

    press checkout
    Summary of the cart
    grab shipping address
    place order

    create order_master
    create lines_products
    (connect to stripe)
after stripe respone update order master status_id to paid or whatever...
transsactions table - transcation id, order_id, amount, date, status_id

pressing    

order_master:
    id    
    user_id 
    discount_total
    sub_total
    grand_total
    date_ordered
    status_id


order_details
    id
    order_id
    product_id
    qty
    price
    discount

