<?php

namespace models;

use PDO; 

class ProductModel extends BaseModel
{
	function __construct() {}

    public function getProductsByTag($tag) {
        try {
            $db = parent::connectToDB();
            $query = $db->prepare('
            SELECT DISTINCT
            p.title AS product_title,       -- Product title
            a.title AS artist_title,        -- Artist title
            p.main_image,                   -- Main image
            pc.quantity_in_stock,           -- Quantity in stock
            pc.price,                       -- Product price
            con.title AS product_condition  -- Product condition
        FROM
            products p
        INNER JOIN
            cds c ON p.product_id = c.product_id
        INNER JOIN
            products_conditions pc ON p.product_id = pc.product_id
        INNER JOIN
            conditions con ON pc.condition_id = con.condition_id
        INNER JOIN
            products_tags pt ON p.product_id = pt.product_id
        INNER JOIN
            tags t ON pt.tag_id = t.tag_id
        INNER JOIN
            artists a ON c.artist_id = a.artist_id
        WHERE
            t.title = :tag;

            ');
            $query->bindParam(':tag', $tag, PDO::PARAM_STR);
            $query->execute();
            var_dump($query->queryString);
        var_dump($tag);
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $ex) {
            print($ex->getMessage());
        }
    }



    public function getRecentReleases() {
        // Implement the logic to fetch recent releases here
        // For example:
        try {
            $db = parent::connectToDB();

            $query = $db->prepare('
                SELECT p.*, c.*
                FROM products p
                INNER JOIN cds c ON p.product_id=c.product_id
                WHERE p.release_date >= NOW() - INTERVAL 360 DAY
            ');

            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
            return []; // Return an empty array or handle the error appropriately
        }
    }

	function getProductDetails($id)
{
    try {
        $db = parent::connectToDB(); // Add this line to get the database connection

        $query = $db->prepare('
            SELECT p.*, c.*
            FROM products p 
            INNER JOIN cds c ON p.product_id = c.product_id
            WHERE p.product_id = :id
        ');
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(\PDO::FETCH_OBJ);

        return $result;
    } catch (\PDOException $ex) {
        print($ex->getMessage());
    }
}



	function readProducts($tagName)
	{
		try {
			$cxn = parent::connectToDB();
          

            if ($tagName=='all') {
                $sql = $cxn->prepare('SELECT * FROM Review ORDER BY ReviewID DESC');
            }
            else {
                $sql = $cxn->prepare(

                    'SELECT p.product_id, p.title, p.product_description, i.image_path, i.image_name, a.title AS artist_name, c.release_date, p.units_in_stock
                    FROM products p
                    JOIN cds c ON p.product_id = c.product_id
                    JOIN images_for_products i ON p.product_id = i.product_id
                    JOIN products_tags pt ON p.product_id = pt.product_id
                    JOIN tags t ON pt.tag_id = t.tag_id
                    JOIN artists a ON a.artist_id = c.artist_id
                    WHERE t.title = :tagName AND p.units_in_stock > 0'
                );
            }
                
                    // Prepare and execute the query with the named parameter
                    // Prepare and execute the query with the named parameter
                    $sql->bindParam(':tagName', $tagName, PDO::PARAM_STR);
                    $sql->execute();

                    // Fetch the results
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          
                    foreach ($result as $row) {
                        print($this->productTemplate($row));
                    }


			}
		 catch (\PDOException $ex) {
			print($ex->getMessage());
		}
	}


	// Utility function to provide some basic styling for a product
	function productTemplate($row)
	{
        echo '<div class="hover:shadow-md hover:bg-gray-100 transition duration-300 ease-in-out transform hover:-translate-y-1">';
        echo '<div class="bg-white rounded-lg overflow-hidden shadow-lg">';
        echo '<a href="product_details?product_id=' . $row['product_id'] . '">';
        echo '<img src="' . $row['image_path'] . '/' . $row['image_name'] . '" alt="' . $row['title'] . '" class="w-full h-64 object-cover">';
        echo '</a>';
        echo '<div class="p-4">';
        echo '<h3 class="text-xl font-semibold">' . $row['title'] . '</h3>';
        echo '<p class="text-gray-600">' . $row['artist_name'] . '</p>';
        echo '<p class="text-gray-600">Left in stock: ' . $row['units_in_stock'] . '</p>';
        echo '<a href="#" class="mt-4 bg-button-color hover:bg-hover-states text-secondary-background py-2 px-4 rounded-full inline-block font-bold">Add to Cart</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
	}
}
