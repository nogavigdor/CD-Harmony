

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

