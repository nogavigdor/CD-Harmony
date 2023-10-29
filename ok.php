<?php
// Replace these placeholders with your database credentials
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
$jsonData = file_get_contents('artists_and_albums-ready.json');

if ($jsonData === false) {
    die("Error reading JSON file.");
}

$data = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error parsing JSON data: " . json_last_error_msg());
}

// Insert Artists into the Artists Table
foreach ($data as $artistName => $albums) {
    $artistName = $db->real_escape_string($artistName);
    $db->query("INSERT IGNORE INTO artists (title) VALUES ('$artistName')");
    $artistId = $db->insert_id;

    if ($db->error) {
        die("Error inserting artist into artists table: " . $db->error);
    }

    foreach ($albums as $album) {
        $albumName = $db->real_escape_string($album['album_name']);
        $description = $db->real_escape_string($album['description']);
        $price = rand(1, 5) * 10 + 89.95; // Random price between 89.95 and 129.95
        $unitsInStock = rand(0, 20); // Random units in stock
        $releaseDate = date('Y-m-d', strtotime($album['release_date']));
        $creationTime = date('Y-m-d H:i:s'); // Current time
        $imageName = $db->real_escape_string(pathinfo($album['image_url'], PATHINFO_BASENAME));

        // Insert album data into the products table
        $db->query("INSERT INTO products (title, product_description, price, units_in_stock, created, main_image) VALUES ('$albumName', '$description', $price, $unitsInStock, '$creationTime', '$imageName')");
        $productId = $db->insert_id;

        if ($db->error) {
            die("Error inserting album into products table: " . $db->error);
        }

        // Insert album images into the imagesForProducts table
        $imagePath = pathinfo($album['image_url'], PATHINFO_DIRNAME);
        $db->query("INSERT INTO images_for_products (title, image_path, image_name, product_id) VALUES ('$albumName', '$imagePath', '$imageName',  $productId)");

        if ($db->error) {
            die("Error inserting album images into imagesForProducts table: " . $db->error);
        }

        // Insert album data into the cds table
        $db->query("INSERT INTO cds (release_date, artist_id, product_id) VALUES ('$releaseDate', $artistId, $productId)");

        if ($db->error) {
            die("Error inserting album data into cds table: " . $db->error);
        }

        // Insert tags into the Tags Table and Products_Tags Table
        if (is_array($album) && isset($album['tags']) && is_array($album['tags'])) {
            $tags = $album['tags'];
            foreach ($tags as $tag) {
                if (isset($tag['name'])) {
                    $tagName = $db->real_escape_string($tag['name']);
                    $db->query("INSERT IGNORE INTO tags (title) VALUES ('$tagName')");
                    $tagId = $db->insert_id;

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
    }
}

// Close the database connection
$db->close();
?>
