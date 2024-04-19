

/***************************************************************************************
These are six views which I'm use in my database - and are implemented in my models 
*******************************************************************************************/
        /* First view - Product details (without new/use variations - for simplicity purposes) */
        /* Will be used mainly for massivly displaying products in the front end */
        /* where not all the details are needed, but only the main ones */
        /* Artist name, release date, product description, */
        /* product title, image name, image path, main image, and tag titles */
        /* It's important to note that I've concatenated the tag titles to one string for better access in the front end */
        /* It can be very useful for future implementation of a search bar, for example */
        /* Even though I have just one image for each product, I've used the main image column as a condition, to insure I get one image */
        /* and to make sure that for this scenario, I will not retrieve more than one image for each product */
        /* if in the future I will want to add more images for each product, I will concatenate them to one string as well */
        DROP VIEW IF EXISTS product_details;
        CREATE  OR REPLACE VIEW product_details AS
        SELECT
            p.product_id,
            p.title AS product_title,
            p.product_description,
            c.release_date,
            a.title AS artist_title,
            ip.image_name,
            ip.main_image,
            GROUP_CONCAT(DISTINCT t.title) AS tag_titles
        FROM
            products p
        LEFT JOIN cds c ON c.product_id = p.product_id
        LEFT JOIN artists a ON a.artist_id = c.artist_id
        LEFT JOIN images_for_products ip ON ip.product_id = p.product_id AND ip.main_image = 1
        LEFT JOIN products_tags pt ON pt.product_id = p.product_id
        LEFT JOIN tags t ON t.tag_id = pt.tag_id
        GROUP BY
            p.product_id;
    

            /* This view is used in for example in my product details where there, as well as the admin area */
        /* Second view : Creates a view that includes all the variations of the products */
        /* In my specific webshop scneario, it will create a view that includes all the new/used variations */
        /* It includes price, discount, quantaty */
        /* and any other detail which is joint from other tables that gives a better overview of the product */
        /* such as product title, product description, the condition title, artist title, release date, image name, image path, and tag titles */
        /* It's important to note that I've concatenated the tag titles to one string for better access in the front end */

    DROP VIEW IF EXISTS product_variants_details;
    CREATE OR REPLACE VIEW product_variants_details AS
    SELECT
        pv.product_variant_id,
        p.product_id,
        p.title AS product_title,
        p.product_description,
        pv.creation_date AS variant_creation_date,
        pv.price,
        pv.is_deleted,
        CASE
            WHEN s.product_variant_id IS NOT NULL AND CURDATE() BETWEEN s.special_offer_start_date AND s.special_offer_end_date THEN s.discount_sum
            ELSE 0
        END AS discount,
        pv.quantity_in_stock,
        con.title AS condition_title,
        a.title AS artist_title,
        c.release_date,
        ip.image_name,
        GROUP_CONCAT(DISTINCT t.title) AS tag_titles
    FROM
        product_variants pv
    LEFT JOIN products p ON pv.product_id = p.product_id
    LEFT JOIN special_offers s ON pv.product_variant_id = s.product_variant_id
    LEFT JOIN conditions con ON pv.condition_id = con.condition_id
    LEFT JOIN cds c ON c.product_id = p.product_id
    LEFT JOIN artists a ON a.artist_id = c.artist_id
    LEFT JOIN images_for_products ip ON ip.product_id = p.product_id AND ip.main_image = 1
    LEFT JOIN products_tags pt ON pt.product_id = p.product_id
    LEFT JOIN tags t ON t.tag_id = pt.tag_id
    GROUP BY
        pv.product_variant_id, p.product_id, pv.creation_date, pv.price, pv.quantity_in_stock, con.title, a.title, c.release_date, ip.image_name;


    -- ...

    /* Forth view - Orders details - summary of customer's orders */
    DROP VIEW IF EXISTS order_details;
    CREATE OR REPLACE VIEW order_details AS
    SELECT
        u.user_id,
        u.first_name,
        u.last_name,
        u.email,
        o.order_id,
        pv.product_variant_id,
        p.title AS product_title,
        pv.price AS item_price,
        ol.quantity,
        (pv.price * ol.quantity) AS total_price,
        IFNULL(so.discount_sum, 0) AS discount,
        os.status_title AS order_status,
        op.status_title AS order_payment,
        a.title AS artist_name,
        con.title AS condition_title, 
        pv.quantity_in_stock AS quantity_in_stock_after_order,
        ip.image_name,
        c.release_date,
        GROUP_CONCAT(DISTINCT t.title) AS tag_titles
    FROM
        orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN orders_lines ol ON o.order_id = ol.order_id
    JOIN product_variants pv ON ol.product_variant_id = pv.product_variant_id
    JOIN products p ON pv.product_id = p.product_id
    LEFT JOIN special_offers so ON pv.product_variant_id = so.product_variant_id
    JOIN orders_status os ON o.order_status_id = os.order_status_id
    JOIN orders_payment op ON o.order_payment_id = op.order_payment_id
    JOIN cds c ON c.product_id = p.product_id
    JOIN artists a ON a.artist_id = c.artist_id
    LEFT JOIN conditions con ON pv.condition_id = con.condition_id
    LEFT JOIN images_for_products ip ON ip.product_id = p.product_id AND ip.main_image = 1
    LEFT JOIN products_tags pt ON pt.product_id = p.product_id
    LEFT JOIN tags t ON t.tag_id = pt.tag_id

    GROUP BY
        u.user_id, u.first_name, u.last_name, u.email, o.order_id, pv.product_variant_id,
        p.title, pv.price, ol.quantity, total_price, discount, order_status, order_payment,
        a.title, condition_title, pv.quantity_in_stock, ip.image_name, c.release_date;


            -- ...
    -- ...

    -- Creates a view for order summary
    -- sums up the totals of the order and is being used in the admin area
    DROP VIEW IF EXISTS order_summary;
    CREATE OR REPLACE VIEW order_summary AS
    SELECT
        o.order_id,
        u.user_id,
        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
        u.email AS customer_email,
        u.creation_date AS registration_date,
        o.creation_date AS order_date,
        os.status_title AS order_status,
        op.status_title AS order_payment,
        COUNT(DISTINCT ol.product_variant_id) AS total_items, -- Count of distinct product variants
        SUM(ol.quantity) AS total_quantity,
        SUM(pv.price * ol.quantity) AS order_subtotal,
        IFNULL(SUM(so.discount_sum), 0) AS order_discount,
        SUM((pv.price * ol.quantity) - IFNULL(so.discount_sum, 0)) AS order_grand_total
    FROM
        orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN orders_lines ol ON o.order_id = ol.order_id
    JOIN product_variants pv ON ol.product_variant_id = pv.product_variant_id
    LEFT JOIN special_offers so ON pv.product_variant_id = so.product_variant_id
    JOIN orders_status os ON o.order_status_id = os.order_status_id
    JOIN orders_payment op ON o.order_payment_id = op.order_payment_id
    GROUP BY
        o.order_id, u.user_id, customer_name, customer_email, registration_date,
        order_date, order_status, order_payment;


    /*  customer details overview - will be used for the admin panel */
    /* includes the user id, first name, last name, email, registration date, total orders, total items purchased, and total amount spent */
    DROP VIEW IF EXISTS customer_details;
    CREATE OR REPLACE VIEW customer_details AS
    SELECT
        u.user_id,
        u.first_name,
        u.last_name,
        u.email,
        COUNT(DISTINCT o.order_id) AS total_orders_done_so_far,
        SUM(ol.quantity) AS total_items_purchased,
        SUM(pv.price * ol.quantity) AS total_amount_spent,
        SUM(IFNULL(so.discount_sum, 0)) AS total_discount,
        SUM(pv.price * ol.quantity - IFNULL(so.discount_sum, 0)) AS total_grand_total
    FROM
        users u
    JOIN roles r ON u.role_id = r.role_id
    LEFT JOIN orders o ON u.user_id = o.user_id
    LEFT JOIN orders_lines ol ON o.order_id = ol.order_id
    LEFT JOIN product_variants pv ON ol.product_variant_id = pv.product_variant_id
    LEFT JOIN special_offers so ON pv.product_variant_id = so.product_variant_id
    WHERE
        r.role_name = 'customer'
    GROUP BY
        u.user_id, u.first_name, u.last_name, u.email;



    -- Details invoice which summmerize order details according to variants
    DROP VIEW IF EXISTS invoice_details;
    CREATE OR REPLACE VIEW invoice_details AS
    SELECT
        o.order_id,
        u.user_id,
        CONCAT(u.first_name, ' ', u.last_name) AS customer_name,
        u.email AS customer_email,
        u.creation_date AS registration_date,
        o.creation_date AS order_date,
        os.status_title AS order_status,
        op.status_title AS order_payment,
        a.title AS artist_name,
        c.release_date,
        p.title AS product_name,
        ol.quantity AS quantity_per_variant, -- Use the quantity from orders_lines
        pv.price AS unit_price, -- Include the price of the product variant
        con.title AS condition_title,
        ip.image_name,
        -- Calculate Subtotal for the entire order
        SUM(pv.price * ol.quantity) AS order_subtotal,
        -- Calculate Total Discount for the entire order
        IFNULL(SUM(so.discount_sum), 0) AS order_discount,
        -- Calculate Grand Total for the entire order after discount
        SUM((pv.price * ol.quantity) - IFNULL(so.discount_sum, 0)) AS order_grand_total
    FROM
        orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN orders_lines ol ON o.order_id = ol.order_id
    JOIN product_variants pv ON ol.product_variant_id = pv.product_variant_id
    JOIN products p ON pv.product_id = p.product_id
    LEFT JOIN special_offers so ON pv.product_variant_id = so.product_variant_id
    JOIN orders_status os ON o.order_status_id = os.order_status_id
    JOIN orders_payment op ON o.order_payment_id = op.order_payment_id
    JOIN cds c ON c.product_id = p.product_id
    JOIN artists a ON a.artist_id = c.artist_id
    LEFT JOIN conditions con ON pv.condition_id = con.condition_id
    LEFT JOIN images_for_products ip ON ip.product_id = p.product_id AND ip.main_image = 1
    GROUP BY
        o.order_id, u.user_id, customer_name, customer_email, registration_date,
        order_date, order_status, order_payment, artist_name, release_date,
        product_name, quantity_per_variant, unit_price, condition_title, image_name;





-- These are the two trigger I use in my database


    -- ...

    /* Triggers */
    /* Here are my triggers for updating the total amounts in my cart master table */
    /* after each insert of a new cart item */


    /*a trigger that updates the stock after each order */

    DELIMITER //
    CREATE TRIGGER after_order_insert
    AFTER INSERT ON orders_lines
    FOR EACH ROW
    BEGIN
        -- Update product_variant quantity_in_stock
        UPDATE product_variants
        SET quantity_in_stock = quantity_in_stock - NEW.quantity
        WHERE product_variant_id = NEW.product_variant_id;
    END;
    //
    DELIMITER ;
  
    
    DELIMITER //

    /* A trigger the prevents stock to have negative values */
    DELIMITER //
    CREATE TRIGGER before_order_insert
    BEFORE INSERT ON orders_lines
    FOR EACH ROW
    BEGIN
        DECLARE available_stock INT;
        SELECT quantity_in_stock INTO available_stock
        FROM product_variants
        WHERE product_variant_id = NEW.product_variant_id;

        IF NEW.quantity > available_stock THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Insufficient stock for the order';
        END IF;
    END;
    //
    DELIMITER ;


