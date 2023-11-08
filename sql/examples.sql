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
