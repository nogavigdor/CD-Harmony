-- Shows all the cd sales from the highest to the lowest - including products that didn't have any sales if exists. --
--It gives a total over view of the current cd sales and enables me to show 'Best Sellers' on the website--
--in case the purpose is just to find the best sellers without a whole overview of the sales, an inner join would be sufficient
SELECT c.product_name, COUNT(op.product_id) AS sales_count
FROM cds c
LEFT JOIN orders_lines op ON c.product_id = op.product_id
GROUP BY c.product_name
ORDER BY sales_count DESC; 

--selects all the users who are customers and purchased 2 used cds of elton john (can be any album) 

SELECT u.*
FROM users u
JOIN orders o ON u.user_id = o.user_id
JOIN orders_lines ol ON o.order_id = ol.order_id
JOIN products p ON ol.product_id = p.product_id
JOIN cds c ON p.product_id = c.product_id
JOIN artists a ON c.artist_id = a.artist_id
WHERE u.role = 'customer'
  AND c.`condition` = 'old'
  AND a.title = 'Elton John'
GROUP BY u.user_id
HAVING SUM(ol.quantaty) = 2;


-- A view for best sellers
CREATE VIEW Top10BestSellers AS
SELECT p.product_id, p.title, p.price, SUM(ol.quantity) AS total_units_sold
FROM products p
JOIN orders_lines ol ON p.product_id = ol.product_id
GROUP BY p.product_id
ORDER BY total_units_sold DESC
LIMIT 10;




-- A view for all pop cds (cds who are associated with the pop tag)
CREATE VIEW CDsWithPopTag AS
SELECT p.*
FROM products p
JOIN cds c ON p.product_id = c.product_id
JOIN products_tags tp ON p.product_id = tp.product_id
JOIN tags t ON tp.tag_id = t.tag_id
WHERE t.title = 'pop';


--The last five articles that were published - based on their creation date
CREATE VIEW Last5Articles AS
SELECT article_id, title, content, publish_date
FROM articles
ORDER BY publish_date DESC
LIMIT 5;


-- Triggers
/*
This trigger first check if the remaining quantaty is greater or equal to the ordered quantaty
and only then update the quantity of a product variant in the product_variants table

*/
CREATE TRIGGER update_stock_after_order
AFTER INSERT ON orders_lines
FOR EACH ROW
BEGIN
    DECLARE remaining_quantity INT;

    SELECT quantity_in_stock INTO remaining_quantity
    FROM product_variants
    WHERE product_variant_id = NEW.product_variant_id;

    IF remaining_quantity >= NEW.quantity THEN
        UPDATE product_variants
        SET quantity_in_stock = quantity_in_stock - NEW.quantity
        WHERE product_variant_id = NEW.product_variant_id;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Not enough stock for this product variant.';
    END IF;
END;

<?php
try {
    // Code to insert a new order line
    // ...
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Not enough stock for this product variant') !== false) {
        // Display a user-friendly error message
        echo "We're sorry, but there's not enough stock for one of the products in your order.";
    } else {
        // If the error is something else, rethrow the exception
        throw $e;
    }
}

/* This trigger will increase the total_price in the orders table when a new order line 
is inserted.
*/
DELIMITER $$
CREATE TRIGGER update_total_price_on_insert
AFTER INSERT ON orders_lines
FOR EACH ROW
BEGIN
    UPDATE orders
    SET total_price = total_price + NEW.price
    WHERE order_id = NEW.order_id;
END$$
DELIMITER ;




-- A trigger that updates the grand_total in the cart_master table when a new cart_item is added
DELIMITER $$
CREATE TRIGGER update_grand_total
AFTER INSERT ON cart_items
FOR EACH ROW
BEGIN
    UPDATE cart_master
    SET grand_total = grand_total + NEW.price
    WHERE cart_master_id = NEW.cart_master_id;
END$$
DELIMITER ;

--A trigger that updates the sub_total in the cart_master table when a new cart_item is added
DELIMITER $$
CREATE TRIGGER update_sub_total
AFTER INSERT ON cart_items
FOR EACH ROW
BEGIN
    UPDATE cart_master
    SET sub_total = sub_total + NEW.price
    WHERE cart_master_id = NEW.cart_master_id;
END$$ 
DELIMITER ;

--A trigger that update the discount in the cart_master table when a new cart_item is added
DELIMITER $$
CREATE TRIGGER update_discount
AFTER INSERT ON cart_items
FOR EACH ROW
BEGIN
    UPDATE cart_master
    SET discount = discount + NEW.price
    WHERE cart_master_id = NEW.cart_master_id;
END$$
DELIMITER ;

--A trigger that checks that there is enough stock for the order to be placed
DELIMITER $$
CREATE TRIGGER check_stock
BEFORE INSERT ON orders_lines
FOR EACH ROW
BEGIN
    IF NEW.quantity > (SELECT quantity_in_stock FROM product_variants WHERE product_variant_id = NEW.product_variant_id) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough in stock';
    END IF;
END$$
DELIMITER ;

--how to implement in php session variable error_message when the trigger is activated
/*
try {
    // Database operations that might throw a PDOException
} catch (\PDOException $e) {
    $_SESSION['error_message'] = $e->getMessage();
    // Redirect to an error page or display the error in the current page
    header("Location: error_page.php");
    exit();
}
*/



























--A trigger that updates the quantity_in_stock in the product_variants table when a new order is placed
DELIMITER $$
CREATE TRIGGER update_quantity_in_stock
AFTER INSERT ON orders_lines
FOR EACH ROW
BEGIN
    UPDATE product_variants
    SET quantity_in_stock = quantity_in_stock - NEW.quantity
    WHERE product_variant_id = NEW.product_variant_id;
END$$
DELIMITER ;

--A trigger that updates the grand_total in the cart_master table when a cart_item is deleted
DELIMITER $$
CREATE TRIGGER update_grand_total
AFTER DELETE ON cart_items
FOR EACH ROW
BEGIN
    UPDATE cart_master
    SET grand_total = grand_total - OLD.price
    WHERE cart_master_id = OLD.cart_master_id;
END$$
DELIMITER ;

--A trigger that updates the sub_total in the cart_master table when a cart_item is deleted
DELIMITER $$
CREATE TRIGGER update_sub_total
AFTER DELETE ON cart_items
FOR EACH ROW
BEGIN
    UPDATE cart_master
    SET sub_total = sub_total - OLD.price
    WHERE cart_master_id = OLD.cart_master_id;
END$$ 
DELIMITER ;

--A trigger that update the discount in the cart_master table when a cart_item is deleted
DELIMITER $$
CREATE TRIGGER update_discount
AFTER DELETE ON cart_items
FOR EACH ROW
BEGIN
    UPDATE cart_master
    SET discount = discount - OLD.price
    WHERE cart_master_id = OLD.cart_master_id;
END$$
DELIMITER ;

--A trigger that updates the quantity_in_stock in the product_variants table when a order is deleted
DELIMITER $$
CREATE TRIGGER update_quantity_in_stock
AFTER DELETE ON orders_lines
FOR EACH ROW
BEGIN
    UPDATE product_variants
    SET quantity_in_stock = quantity_in_stock + OLD.quantity
    WHERE product_variant_id = OLD.product_variant_id;
END$$
DELIMITER ;




