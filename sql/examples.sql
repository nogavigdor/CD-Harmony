-- Shows all the cd sales from the highest to the lowest - including products that didn't have any sales if exists. --
--It gives a total over view of the current cd sales and enables me to show 'Best Sellers' on the website--
--in case the purpose is just to find the best sellers without a whole overview of the sales, an inner join would be sufficient
SELECT c.product_name, COUNT(op.product_id) AS sales_count
FROM cds c
LEFT JOIN orders_lines op ON c.product_id = op.product_id
GROUP BY c.product_name
ORDER BY sales_count DESC; 

--selects all the users who are customers and purchased 2 old cds of elton john (can be any album) 

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


