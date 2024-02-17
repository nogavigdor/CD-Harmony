 
 
  DROP DATABASE IF EXISTS cdhrmnyDB;

  CREATE DATABASE cdhrmnyDB;

  USE cdhrmnyDB;



  CREATE TABLE users
  (
    user_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    user_password varchar(100) NOT NULL,  
    user_role ENUM('admin', 'customer', 'author')

  ) ENGINE=InnoDB; 


  CREATE TABLE postal_codes (
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

    CREATE TABLE addresses
  (
    address_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    first_name varchar(100) NOT NULL,
    last_name varchar(100) NOT NULL,
    phone varchar(20) NOT NULL,
    street varchar(100) NOT NULL,  
    comment varchar(300) NOT NULL,
    address_default binary NOT NULL,
    user_id int NOT NULL,
    postal_code_id varchar(4) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (postal_code_id) REFERENCES postal_codes (postal_code_id)

  ) ENGINE=InnoDB; 



  CREATE TABLE products
  (
    product_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL,
    created timestamp NOT NULL,
    price decimal(10,2) NOT NULL,
    units_in_stock int NOT NULL

  ) ENGINE=InnoDB;

    CREATE TABLE artists
  (
    artist_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL

  ) ENGINE=InnoDB;

  
      CREATE TABLE cds
  (
    cd_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL,
    cd_condition ENUM('new', 'good as new', 'good','fair') NOT NULL,
    release_date date NOT NULL,
    cover_image varchar(255) NOT NULL,
  --  cd_type ENUM('collection', 'rare', 'special edition') NOT NULL,
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
 

  CREATE TABLE tags_cds
  (
    cd_id int,
    tag_id int,
    CONSTRAINT PK_tags_cds PRIMARY KEY (tag_id, cd_id),
    FOREIGN KEY (cd_id) REFERENCES cds (cd_id),
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
  discount_precentage int NOT NULL,
  special_offer_start_date timestamp NOT NULL,
  special_offer_end_date timestamp NOT NULL,
  product_id int,
  FOREIGN KEY (product_id) REFERENCES products (product_id)
 ) ENGINE=InnoDB;

 CREATE TABLE images_for_products 
 (
  title varchar(100) NOT NULL,
  image_url varchar(255) NOT NULL,
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
  `value` int NOT NULL,
   created timestamp NOT NULL,
  product_id int,
  user_id int,
  FOREIGN KEY (product_id) REFERENCES products(product_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
 ) ENGINE=InnoDB;



 CREATE TABLE orders 
 (
  order_id int AUTO_INCREMENT NOT NULL PRIMARY KEY,
  created timestamp NOT NULL,
   `status` ENUM('pending', 'in process', 'completed') NOT NULL,
   `payment` ENUM('unpaid', 'paid', 'refunded') NOT NULL,
  user_id int,
  FOREIGN KEY (user_id) REFERENCES users(user_id)
 ) ENGINE=InnoDB;


  CREATE TABLE orders_lines
  (
    quantaty int NOT NULL,
    price decimal(10,2),
    order_id int,
    product_id int,
    CONSTRAINT PK_order_line PRIMARY KEY (order_Id, product_id),
    FOREIGN KEY (order_id) REFERENCES orders (order_id),
    FOREIGN KEY (product_id) REFERENCES products (product_id) 
  ) ENGINE=InnoDB;



