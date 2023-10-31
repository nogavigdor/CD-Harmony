-- Shows all the cdsales from the highest to the lowest - including products that didn't have any sales if exists. --
--It gives a total over view of the current cd sales and enables me to show 'Best Sellers' on the website--
--in case the purpose is just to find the best sellers without a whole overview of the sales, an inner join would be sufficient
SELECT c.product_name, COUNT(op.product_id) AS sales_count
FROM cds c
LEFT JOIN orderProducts op ON c.product_id = op.product_id
GROUP BY c.product_name
ORDER BY sales_count DESC; 