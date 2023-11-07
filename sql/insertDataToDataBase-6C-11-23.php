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

        // Insert album data into the cds table
        $db->query("INSERT INTO cds (release_date, artist_id, product_id) VALUES ('$releaseDate', $artistId, $productId)");

        if ($db->error) {
            die("Error inserting album into cds table: " . $db->error);
        }

        // ... (rest of the code)
    }
}

// Close the database connection
$db->close();
