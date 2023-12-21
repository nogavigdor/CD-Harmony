

            DROP DATABASE IF EXISTS CDHarmonyDB;

            CREATE DATABASE CDHarmonyDB CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

            USE CDHarmonyDB;

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
    CREATE VIEW product_details AS
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
    /* Second view : Creates a view that includes all the variations of the products */
    /* In my specific webshop scneario, it will create a view that includes all the new/used variations */
    /* It includes price, discount, quantaty */
    /* and any other detail which is joint from other tables that gives a better overview of the product */
    /* such as product title, product description, the condition title, artist title, release date, image name, image path, and tag titles */
    /* It's important to note that I've concatenated the tag titles to one string for better access in the front end */
CREATE VIEW product_variants_details AS
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
    pv.product_variant_id, p.product_id, pv.creation_date, pv.price, pv.quantity_in_stock, con.title, a.title, c.release_date, ip.image_name;

/*Third View - customer details overveiew - will be used for the admin panel*/
/*includes the user id, first name, last name, email, registration date, total orders, total items purchased, and total amount spent*/
CREATE VIEW customer_details AS
SELECT
    u.user_id,
    u.first_name,
    u.last_name,
    u.email,
    u.creation_date AS registration_date,
    COUNT(DISTINCT o.order_id) AS total_orders,
    COUNT(ol.quantity) AS total_items_purchased,
    IFNULL(SUM(pv.price * ol.quantity), 0) AS total_amount_spent
FROM
    users u
JOIN roles r ON u.role_id = r.role_id
LEFT JOIN orders o ON u.user_id = o.user_id
LEFT JOIN orders_lines ol ON o.order_id = ol.order_id
LEFT JOIN product_variants pv ON ol.product_variant_id = pv.product_variant_id
WHERE
    r.role_name = 'customer'
GROUP BY
    u.user_id, u.first_name, u.last_name, u.email, u.creation_date;

/* Forth view -  Order details  - summery of customer's orders*/
CREATE VIEW order_details AS
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
    op.status_title AS order_payment
FROM
    orders o
JOIN users u ON o.user_id = u.user_id
JOIN orders_lines ol ON o.order_id = ol.order_id
JOIN product_variants pv ON ol.product_variant_id = pv.product_variant_id
JOIN products p ON pv.product_id = p.product_id
LEFT JOIN special_offers so ON pv.product_variant_id = so.product_variant_id
JOIN orders_status os ON o.order_status_id = os.order_status_id
JOIN orders_payment op ON o.order_payment_id = op.order_payment_id;


/* Triggers */
/* Here are my triggers for updating the total amounts in my cart master table */
/* after each insert of a new cart item */

/*
The next three triggers automatically update the product_variants_details view whenever there is an insertion, 
update, or deletion in the product_variants table. 
*/
DELIMITER //
CREATE TRIGGER after_product_variant_insert
AFTER INSERT ON product_variants
FOR EACH ROW
BEGIN
    REPLACE INTO product_variants_details
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
    WHERE pv.product_variant_id = NEW.product_variant_id
    GROUP BY pv.product_variant_id, p.product_id, pv.creation_date, pv.price, pv.quantity_in_stock, con.title, a.title, c.release_date, ip.image_name;
END;
//
DELIMITER ;

-- Trigger for UPDATE operation on product_variants
DELIMITER //
CREATE TRIGGER after_product_variant_update
AFTER UPDATE ON product_variants
FOR EACH ROW
BEGIN
    REPLACE INTO product_variants_details
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
    WHERE pv.product_variant_id = NEW.product_variant_id
    GROUP BY pv.product_variant_id, p.product_id, pv.creation_date,

/*
This trigger updates the product_details view after an INSERT, UPDATE, or DELETE operation
 on the products table. The REPLACE INTO statement first
deletes the row if it exists and then inserts a new row. 
*/
DELIMITER //
CREATE TRIGGER after_product_details_change
AFTER INSERT, UPDATE, DELETE ON products
FOR EACH ROW
BEGIN
    -- Update the corresponding rows in product_details
    REPLACE INTO product_details
    (product_id, product_title, product_description, release_date, artist_title, image_name, main_image, tag_titles)
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
    WHERE
        p.product_id = NEW.product_id;
END;
//
DELIMITER ;



CREATE TRIGGER after_cart_item_insert
AFTER INSERT ON cart_items
FOR EACH ROW
BEGIN
   UPDATE cart_master
   SET grand_total = grand_total + NEW.total_price,
       sub_total = sub_total + NEW.price,
       discount_total = discount_total + NEW.discount
   WHERE id = NEW.cart_master_id;
   
   UPDATE cart_items
   SET total_price = NEW.quantity * NEW.price_per_item
   WHERE cart_master_id = NEW.cart_master_id AND product_variant_id = NEW.product_variant_id;
END;

/* After each update of a cart item */
CREATE TRIGGER after_cart_item_update
AFTER UPDATE ON cart_items
FOR EACH ROW
BEGIN
   UPDATE cart_master
   SET grand_total = grand_total - OLD.total_price + NEW.total_price,
       sub_total = sub_total - OLD.price + NEW.price,
       discount_total = discount_total - OLD.discount + NEW.discount
   WHERE id = NEW.cart_master_id;
   
   UPDATE cart_items
   SET total_price = NEW.quantity * NEW.price_per_item
   WHERE cart_master_id = NEW.cart_master_id AND product_variant_id = NEW.product_variant_id;
END;

/* After each delete of a cart item */
/* In this case there is no need to handle the total price of the cart item because it will be deleted */
CREATE TRIGGER after_cart_item_delete
AFTER DELETE ON cart_items
FOR EACH ROW
BEGIN
   UPDATE cart_master
   SET grand_total = grand_total - OLD.total_price,
       sub_total = sub_total - OLD.price,
       discount_total = discount_total - OLD.discount
   WHERE id = OLD.cart_master_id;
END;

/*
 This trigger updates the order_details view after an order is inserted into the orders table.
The REPLACE INTO statement first deletes the row if it exists and then inserts a new row,
just like the previous 2 triggers.

*/
DELIMITER //
CREATE TRIGGER after_order_insert
AFTER INSERT ON orders
FOR EACH ROW
BEGIN

    REPLACE INTO order_details
    (user_id, first_name, last_name, email, order_id, product_variant_id, product_title, item_price, quantity, total_price, discount, order_status, order_payment)
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
        op.status_title AS order_payment
    FROM
        orders o
    JOIN users u ON o.user_id = u.user_id
    JOIN orders_lines ol ON o.order_id = ol.order_id
    JOIN product_variants pv ON ol.product_variant_id = pv.product_variant_id
    JOIN products p ON pv.product_id = p.product_id
    LEFT JOIN special_offers so ON pv.product_variant_id = so.product_variant_id
    JOIN orders_status os ON o.order_status_id = os.order_status_id
    JOIN orders_payment op ON o.order_payment_id = op.order_payment_id
    WHERE
        o.order_id = NEW.order_id;
END;
//
DELIMITER ;
