<?php

// Your database connection code here

// Read the JSON file
$jsonData = file_get_contents('albums_ready.json');
$data = json_decode($jsonData, true);

// Iterate over each album in the JSON data
foreach ($data as $album) {
    // Extract album data
    $title = $album['title'];
    $condition = $album['condition'];
    $artist = $album['artist'];

    // Ensure the artist exists in the artists table (no duplicates)
    $artistExistsQuery = "SELECT artist_id FROM artists WHERE title = '$artist'";
    $artistExistsResult = mysqli_query($conn, $artistExistsQuery);

    if (mysqli_num_rows($artistExistsResult) == 0) {
        // If the artist doesnt exist, insert it into the artists table
        $insertArtistQuery = "INSERT INTO artists (title) VALUES ('$artist')";
        mysqli_query($conn, $insertArtistQuery);
    }

    // Retrieve the artist_id for the inserted or existing artist
    $artistIdQuery = "SELECT artist_id FROM artists WHERE title = '$artist'";
    $artistIdResult = mysqli_query($conn, $artistIdQuery);
    $row = mysqli_fetch_assoc($artistIdResult);
    $artistId = $row['artist_id'];

    // Randomly generate a quantity between 0 and 5 for each condition
    $quantity = rand(0, 5);

    // Check if the album already exists in the products table
    $checkProductQuery = "SELECT product_id, units_in_stock FROM products WHERE title = '$title'";
    $checkProductResult = mysqli_query($conn, $checkProductQuery);

    if (mysqli_num_rows($checkProductResult) > 0) {
        // If the product exists, update the quantity_in_stock for the corresponding product
        $updateProductRow = mysqli_fetch_assoc($checkProductResult);
        $existingQuantity = $updateProductRow['units_in_stock'];
        $newQuantity = $existingQuantity + $quantity;

        $updateProductQuery = "UPDATE products SET units_in_stock = $newQuantity WHERE title = '$title'";
        mysqli_query($conn, $updateProductQuery);

        $productId = $updateProductRow['product_id'];
    } else {
        // If the product doesn't exist, insert it into the products table
        $insertProductQuery = "INSERT INTO products (title, created, price, units_in_stock) VALUES ('$title', NOW(), 0, $quantity)";
        mysqli_query($conn, $insertProductQuery);

        // Retrieve the product_id for the inserted product
        $productIdQuery = "SELECT product_id FROM products WHERE title = '$title'";
        $productIdResult = mysqli_query($conn, $productIdQuery);
        $row = mysqli_fetch_assoc($productIdResult);
        $productId = $row['product_id'];
    }

    // Insert the album into the cds table with artist_id, product_id, condition, and quantity
    $insertCDQuery = "INSERT INTO cds (title, `condition`, release_date, cover_image, `type`, artist_id, product_id) VALUES ('$title', '$condition', NOW(), '', '', $artistId, $productId)";
    mysqli_query($conn, $insertCDQuery);
}

// Close the database connection
mysqli_close($conn);

echo "Data insertion complete.";

?>
