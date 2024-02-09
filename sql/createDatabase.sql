

            DROP DATABASE IF EXISTS cdhrmnyDB;

            CREATE DATABASE cdhrmnyDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

            USE cdhrmnyDB;

            CREATE TABLE roles
            (
                role_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                role_name varchar(100) NOT NULL,
                UNIQUE(role_name)
            ) ENGINE=InnoDB;

            INSERT INTO roles (role_name) VALUES
            ('admin'),
            ('editor'),
            ('customer');

            CREATE TABLE users
            (
                user_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                first_name varchar(100) NOT NULL,
                last_name varchar(100) NOT NULL,
                email varchar(100) UNIQUE NOT NULL,
                user_password varchar(100) NOT NULL,  
                creation_date timestamp NOT NULL,
                role_id int,
                FOREIGN KEY (role_id) REFERENCES roles (role_id)
            ) ENGINE=InnoDB;

            CREATE TABLE postal_codes
            (
                postal_code_id varchar(4) NOT NULL PRIMARY KEY,
                city varchar(100) NOT NULL
            ) ENGINE=InnoDB;

            CREATE TABLE addresses
            (
                address_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                phone_number varchar(20) NOT NULL,
                street varchar(100) NOT NULL,  
                comments varchar(300) NOT NULL,
                user_id int,
                postal_code_id varchar(4) NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users (user_id),
                FOREIGN KEY (postal_code_id) REFERENCES postal_codes (postal_code_id)
            ) ENGINE=InnoDB;

            CREATE TABLE users_addresses 
            (
                default_address tinyint(1) NOT NULL,
                user_id int,
                address_id int,
                CONSTRAINT PK_user_address PRIMARY KEY (user_id, address_id),
                FOREIGN KEY (user_id) REFERENCES users (user_id),
                FOREIGN KEY (address_id) REFERENCES addresses (address_id)
            ) ENGINE=InnoDB;

            CREATE TABLE company_details
            (
                company_details_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                company_name varchar(100) NOT NULL,
                street varchar(100) NOT NULL,
                email varchar(100) NOT NULL,
                phone_number varchar(20) NOT NULL,
                logo varchar(200) NOT NULL,
                opening_hours text NOT NULL,
                postal_code_id varchar(4) NOT NULL,
                FOREIGN KEY (postal_code_id) REFERENCES postal_codes (postal_code_id)
            ) ENGINE=InnoDB;

            CREATE TABLE products
            (
                product_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title varchar(255) NOT NULL,
                product_description text NOT NULL,
                creation_date timestamp NOT NULL
            ) ENGINE=InnoDB;

            CREATE TABLE conditions 
            (
                condition_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title varchar(100) NOT NULL,
                UNIQUE(title)
            ) ENGINE=InnoDB;

            INSERT INTO conditions (title) VALUES
            ('new'),
            ('used');

            CREATE TABLE product_variants
            (
                product_variant_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                creation_date timestamp NOT NULL,
                price decimal(10,2) NOT NULL,
                quantity_in_stock int NOT NULL,
                product_id int,
                condition_id int,
                FOREIGN KEY (product_id) REFERENCES products (product_id),
                FOREIGN KEY (condition_id) REFERENCES conditions (condition_id)
            ) ENGINE=InnoDB;

            CREATE TABLE artists
            (
                artist_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title varchar(255) NOT NULL
            ) ENGINE=InnoDB;

            CREATE TABLE cds
            (
                cd_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                release_date date NOT NULL,
                artist_id int,
                product_id int,
                FOREIGN KEY (artist_id) REFERENCES artists (artist_id),
                FOREIGN KEY (product_id) REFERENCES products (product_id)
            ) ENGINE=InnoDB;
        /*
            CREATE TABLE electronic_devices
            (
                electronic_device_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                brand varchar(100) NOT NULL,
                model varchar(100) NOT NULL,
                product_id int,
                FOREIGN KEY (product_id) REFERENCES products (product_id)
            ) ENGINE=InnoDB; 
        */
            CREATE TABLE tags
            (
                tag_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title varchar(100) NOT NULL
            ) ENGINE=InnoDB;

            CREATE TABLE products_tags
            (
                product_id int,
                tag_id int,
                CONSTRAINT PK_product_tag PRIMARY KEY (product_id, tag_id),
                FOREIGN KEY (product_id) REFERENCES products (product_id),
                FOREIGN KEY (tag_id) REFERENCES tags (tag_id)
            ) ENGINE=InnoDB;

            CREATE TABLE tracks
            (
                track_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title varchar(255) NOT NULL,
                duration int NOT NULL,
                cd_id int,
                FOREIGN KEY (cd_id) REFERENCES cds (cd_id)
            ) ENGINE=InnoDB;

            CREATE TABLE special_offers
            (
                special_offer_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title varchar(100) NOT NULL,
                special_offer_description varchar(300) NOT NULL,
                is_homepage tinyint(1) NOT NULL,
                discount_sum decimal(10,2) NOT NULL,
                special_offer_start_date timestamp NOT NULL,
                special_offer_end_date timestamp NOT NULL,
                product_variant_id int,
                FOREIGN KEY (product_variant_id) REFERENCES product_variants (product_variant_id)
            ) ENGINE=InnoDB;

            CREATE TABLE images_for_products
            (
                image_for_product_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                image_name varchar(255) NOT NULL,
                main_image tinyint(1) NOT NULL,
                product_id int,
                FOREIGN KEY (product_id) REFERENCES products (product_id)
            ) ENGINE=InnoDB;


            CREATE TABLE orders_status 
            (
                order_status_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                status_title varchar(100) NOT NULL,
                UNIQUE(status_title)
            ) ENGINE=InnoDB;

            INSERT INTO orders_status (status_title) VALUES
            ('pending'),
            ('in process'),
            ('completed');

            CREATE TABLE orders_payment 
            (
                order_payment_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                status_title varchar(100) NOT NULL,
                UNIQUE(status_title)
            ) ENGINE=InnoDB;

            INSERT INTO orders_payment (status_title) VALUES
            ('unpaid'),
            ('paid'),
            ('refund');

            CREATE TABLE cart_master
            (
                cart_master_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                creation_date timestamp NOT NULL,
                session_id varchar(100) NOT NULL,
                discount_total decimal(10,2) NOT NULL,
                sub_total decimal(10,2) NOT NULL,
                grand_total decimal(10,2) NOT NULL,
                user_id int,
                FOREIGN KEY (user_id) REFERENCES users (user_id)
            ) ENGINE=InnoDB;
            

            CREATE TABLE cart_items
            (
                quantity int NOT NULL,
                price decimal(10,2),
                discount decimal (10,2),
                total_price decimal(10,2),
                cart_master_id int,
                product_variant_id int,
                CONSTRAINT PK_cart_line PRIMARY KEY (cart_master_id, product_variant_id),
                FOREIGN KEY (cart_master_id) REFERENCES cart_master (cart_master_id),
                FOREIGN KEY (product_variant_id) REFERENCES product_variants (product_variant_id)
            ) ENGINE=InnoDB;


            CREATE TABLE orders
            (
                order_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                creation_date timestamp NOT NULL,
                order_status_id int,
                order_payment_id int,
                cart_master_id int,
                user_id int,
                FOREIGN KEY (user_id) REFERENCES users(user_id),
                FOREIGN KEY (order_status_id) REFERENCES orders_status(order_status_id),
                FOREIGN KEY (order_payment_id) REFERENCES orders_payment(order_payment_id),
                FOREIGN KEY (cart_master_id) REFERENCES cart_master(cart_master_id)
            ) ENGINE=InnoDB;

            CREATE TABLE orders_lines
            (
                quantity int NOT NULL,
                price decimal(10,2),
                order_id int,
                product_variant_id int,
                CONSTRAINT PK_order_line PRIMARY KEY (order_id, product_variant_id),
                FOREIGN KEY (order_id) REFERENCES orders (order_id),
                FOREIGN KEY (product_variant_id) REFERENCES product_variants (product_variant_id)
            ) ENGINE=InnoDB;

            CREATE TABLE articles
            (
                article_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title varchar(100) NOT NULL,
                content text NOT NULL,
                publish_date timestamp NOT NULL,
                update_date timestamp NOT NULL,
                user_id int,
                FOREIGN KEY (user_id) REFERENCES users (user_id)
            ) ENGINE=InnoDB;

            CREATE TABLE articles_tags
            (
                article_id int,
                tag_id int,
                CONSTRAINT PK_article_tag PRIMARY KEY (article_id, tag_id),
                FOREIGN KEY (article_id) REFERENCES articles (article_id),
                FOREIGN KEY (tag_id) REFERENCES tags (tag_id)
            ) ENGINE=InnoDB;

            CREATE TABLE images_for_articles
            (
                image_for_article_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title varchar(100) NOT NULL,
                image_name varchar(255) NOT NULL,
                main_image tinyint(1) NOT NULL,
                article_id int,
                FOREIGN KEY (article_id) REFERENCES articles(article_id)
            ) ENGINE=InnoDB;

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
        p.product_id
 

        /* This view is used in for example in my product details where there, as well as the admin area */
    /* Second view : Creates a view that includes all the variations of the products */
    /* In my specific webshop scneario, it will create a view that includes all the new/used variations */
    /* It includes price, discount, quantaty */
    /* and any other detail which is joint from other tables that gives a better overview of the product */
    /* such as product title, product description, the condition title, artist title, release date, image name, image path, and tag titles */
    /* It's important to note that I've concatenated the tag titles to one string for better access in the front end */

CREATE  OR REPLACE VIEW product_variants_details AS
SELECT
    pv.product_variant_id,
    p.product_id,
    p.title AS product_title,
    p.product_description,
    pv.creation_date AS variant_creation_date,
    pv.price,
    COALESCE(s.discount_sum, 0) AS discount,
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
    pv.product_variant_id, p.product_id, pv.creation_date, pv.price, pv.quantity_in_stock, con.title, a.title, c.release_date, ip.image_name


-- ...

        /* Forth view - Orders details - summary of customer's orders */
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
--summs up the totals of the order and is being used in the admin area
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
    order_date, order_status, order_payment


/*  customer details overview - will be used for the admin panel */
/* includes the user id, first name, last name, email, registration date, total orders, total items purchased, and total amount spent */
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
    u.user_id, u.first_name, u.last_name, u.email



-- Details invoice which summmerize order details according to variants
-- Details invoice which summmerize order details according to variants
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
The next three triggers automatically update the product_variants_details view whenever there is an insertion, 
update, or deletion in the product_variants table. 
*/
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




