<div class="section">
    <h2 class="section-title">Featured Pop CDs</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo '<div class="hover:shadow-md hover:bg-gray-100 transition duration-300 ease-in-out transform hover:-translate-y-1">';
            echo '<div class="bg-white rounded-lg overflow-hidden shadow-lg">';
            echo '<a href="product_details.php?product_id=' . $row['product_id'] . '">';
            echo '<img src="' . $row['image_path'] . '/' . $row['image_name'] . '" alt="' . $row['title'] . '" class="w-full h-64 object-cover">';
            echo '</a>';
            echo '<div class="p-4">';
            echo '<h3 class="text-xl font-semibold">' . $row['title'] . '</h3>';
            echo '<p class="text-gray-600">' . $row['artist_name'] . '</p>';
            echo '<a href="#" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-full inline-block">Add to Cart</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
        <button class="btn">Button</button>
        </body>
        </html>