
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