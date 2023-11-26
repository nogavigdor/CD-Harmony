<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "CDHarmonyDB";

// Connect to the database
$db = new mysqli($host, $user, $password, $database);

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Load the JSON data with error checking
$jsonData = file_get_contents('artists_and_albums-29-10-23.json');

if ($jsonData === false) {
    die("Error reading JSON file.");
}

$data = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error parsing JSON data: " . json_last_error_msg());
}

// Create arrays to track artists and tags that have already been inserted
$artistsInserted = [];
$tagsInserted = [];

// Iterate through the JSON data and insert records
foreach ($data as $artistName => $albums) {
    $artistName = $db->real_escape_string($artistName);

    // Insert the artist into the artists table
    $db->query("INSERT INTO artists (title) VALUES ('$artistName')");
    $artistId = $db->insert_id;

    if ($db->error) {
        die("Error inserting artist into artists table: " . $db->error);
    }

    foreach ($albums as $album) {
        $albumName = $db->real_escape_string($album['album_name']);
        $description = $db->real_escape_string($album['description']);
        $releaseDate = date('Y-m-d', strtotime($album['release_date']));
        $imagePath = pathinfo($album['image_url'], PATHINFO_DIRNAME);
        $imageName = $db->real_escape_string(pathinfo($album['image_url'], PATHINFO_BASENAME));

        // Insert album data into the products table
        $db->query("INSERT INTO products (title, product_description, created) VALUES ('$albumName', '$description', NOW())");
        $productId = $db->insert_id;

        if ($db->error) {
            die("Error inserting album into products table: " . $db->error);
        }

        // Insert album images into the images_for_products table
        $db->query("INSERT INTO images_for_products (title, image_path, image_name, main_image, product_id) VALUES ('$albumName', '$imagePath', '$imageName', 1, $productId)");

        if ($db->error) {
            die("Error inserting album images into imagesForProducts table: " . $db->error);
        }

        // Insert tags into the Tags Table and Products_Tags Table
        if (is_array($album) && isset($album['tags']) && is_array($album['tags'])) {
            $tags = $album['tags'];
            foreach ($tags as $tag) {
                if (isset($tag['name'])) {
                    $tagName = $db->real_escape_string($tag['name']);

                    // Check if the tag has already been inserted
                    $result = $db->query("SELECT tag_id FROM tags WHERE title = '$tagName'");
                    if ($result === false) {
                        die("Error checking tag in tags table: " . $db->error);
                    }

                    if ($result->num_rows === 0) {
                        $db->query("INSERT INTO tags (title) VALUES ('$tagName')");
                        $tagId = $db->insert_id;
                    } else {
                        $row = $result->fetch_assoc();
                        $tagId = $row['tag_id'];
                    }

                    if ($db->error) {
                        die("Error inserting tag into tags table: " . $db->error);
                    }

                    // Insert entry into Products_Tags table
                    $db->query("INSERT INTO products_tags (product_id, tag_id) VALUES ($productId, $tagId)");

                    if ($db->error) {
                        die("Error inserting product-tag relationship into products_tags table: " . $db->error);
                    }
                }
            }
        }

        // Insert album data into the products_conditions table for "new" condition
        $priceNew = rand(1, 5) * 10 + 89.95;
        $quantityInStockNew = rand(0, 10);
        $conditionIdNew = $db->query("SELECT condition_id FROM conditions WHERE title = 'new'")->fetch_assoc()['condition_id'];
        if ($conditionIdNew === null) {
            die("Error fetching condition_id for 'new' condition.");
        }
        $db->query("INSERT INTO products_conditions (price, quantity_in_stock, product_id, condition_id) VALUES ($priceNew, $quantityInStockNew, $productId, $conditionIdNew)");

        if ($db->error) {
            die("Error inserting new album data into products_conditions table: " . $db->error);
        }

        // Insert album data into the products_conditions table for "used" condition
        $priceUsed = $priceNew - 40;
        $quantityInStockUsed = rand(0, 10);
        $conditionIdUsed = $db->query("SELECT condition_id FROM conditions WHERE title = 'used'")->fetch_assoc()['condition_id'];
        if ($conditionIdUsed === null) {
            die("Error fetching condition_id for 'used' condition.");
        }
        $db->query("INSERT INTO products_conditions (price, quantity_in_stock, product_id, condition_id) VALUES ($priceUsed, $quantityInStockUsed, $productId, $conditionIdUsed)");

        if ($db->error) {
            die("Error inserting used album data into products_conditions table: " . $db->error);
        }

        // Insert album data into the cds table
        $db->query("INSERT INTO cds (release_date, artist_id, product_id) VALUES ('$releaseDate', $artistId, $productId)");

        if ($db->error) {
            die("Error inserting album into cds table: " . $db->error);
        }
    }
}

// Close the database connection
$db->close();
