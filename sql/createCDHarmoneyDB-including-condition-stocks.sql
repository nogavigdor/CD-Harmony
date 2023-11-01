-- Without conditions for the cds entity

DROP DATABASE IF EXISTS CDHarmonyDB;

CREATE DATABASE CDHarmonyDB;

USE CDHarmonyDB;

CREATE TABLE users
(
    user_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    user_password varchar(100) NOT NULL,  
    user_role ENUM('admin', 'customer', 'author')
) ENGINE=InnoDB;

CREATE TABLE roles
(
    role_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    role_name varchar(100) NOT NULL,
    CHECK (role_name IN ('customer', 'editor', 'admin'))
)

CREATE TABLE addresses
(
    address_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    phone varchar(20) NOT NULL,
    street varchar(100) NOT NULL,  
    comment varchar(300) NOT NULL,
    address_default binary NOT NULL,
    postal_code_id varchar(4) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (postal_code_id) REFERENCES postal_codes (postal_code_id)
) ENGINE=InnoDB;

CREATE TABLE users_addresses 
(
    user_id int,
    address_id int,
    CONSTRAINT PK_user_address PRIMARY KEY (user_id, address_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (address_id) REFERENCES addresses (address_id)
)

CREATE TABLE postal_codes
(
    postal_code_id varchar(4) NOT NULL PRIMARY KEY,
    city varchar(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE company_details
(
    company_details_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    company_name varchar(100) NOT NULL,
    street varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    phone varchar(20) NOT NULL,
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
    main_image varchar(255) NOT NULL,
    created timestamp NOT NULL,
    price decimal(10,2) NOT NULL,
    units_in_stock int NOT NULL
) ENGINE=InnoDB;

CREATE TABLE artists
(
    artist_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE conditions 
(
    condition_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title varchar(100) NOT NULL,
    CHECK (role_name IN ('customer', 'editor', 'admin'))

)

CREATE TABLE cds
(
    cd_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    release_date date NOT NULL,
    cd_condition  varchar(100) NOT NULL,
    CHECK (role_name IN ('new', 'used')),
    units_in_stock int NOT NULL,
    artist_id int,
    product_id int,
    FOREIGN KEY (artist_id) REFERENCES artists (artist_id),
    FOREIGN KEY (product_id) REFERENCES products (product_id)
) ENGINE=InnoDB;

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
    discount_percentage int NOT NULL,
    special_offer_start_date timestamp NOT NULL,
    special_offer_end_date timestamp NOT NULL,
    product_id int,
    FOREIGN KEY (product_id) REFERENCES products (product_id)
) ENGINE=InnoDB;

CREATE TABLE images_for_products
(
    image_for_product_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title varchar(100) NOT NULL,
    image_path varchar(255) NOT NULL,
    image_name varchar(255) NOT NULL,
    product_id int,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
) ENGINE=InnoDB;

CREATE TABLE reviews
(
    review_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    content TEXT NOT NULL,
    created TIMESTAMP NOT NULL,
    product_id INT,
    user_id INT,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE ratings
(
    rating_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    rating_value int NOT NULL,
    created timestamp NOT NULL,
    product_id int,
    user_id int,
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE orders_status 
(
    order_status_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    status_title varchar(100) NOT NULL,
    CHECK (status_title IN ('pending', 'in process', 'completed')),
)

CREATE TABLE orders_payment 
(
    order_payment_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    status_title varchar(100) NOT NULL,
    CHECK (status_title IN ('unpaid', 'paid', 'refunded')),
)

CREATE TABLE orders
(
    order_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    created timestamp NOT NULL,
    user_id int,
    PK_user_address in,
    FOREIGN KEY (order_status_id) REFERENCES orders_status(order_status_id),
    FOREIGN KEY (order_payment_id) REFERENCES orders_payment(order_payment_id),
    FOREIGN KEY (PK_user_address) REFERENCES users_address(PK_user_address),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE orders_lines
(
    quantity int NOT NULL,
    price decimal(10,2),
    order_id int,
    product_id int,
    CONSTRAINT PK_order_line PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders (order_id),
    FOREIGN KEY (product_id) REFERENCES products (product_id)
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
    image_path varchar(255) NOT NULL,
    image_name varchar(255) NOT NULL,
    product_id int,
    FOREIGN KEY (product_id) REFERENCES articles(article_id)
) ENGINE=InnoDB;
