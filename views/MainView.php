<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "CDHarmonydb";

// Create a database connection
$connection = new mysqli($host, $user, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fetch CDs with the "pop" tag
$sql = "SELECT p.product_id, p.title, p.product_description, i.image_path, i.image_name, a.title AS artist_name, c.release_date, p.units_in_stock
        FROM products p
        JOIN cds c ON p.product_id = c.product_id
        JOIN images_for_products i ON p.product_id = i.product_id
        JOIN products_tags pt ON p.product_id = pt.product_id
        JOIN tags t ON pt.tag_id = t.tag_id
        JOIN artists a ON a.artist_id = c.artist_id
        WHERE t.title = 'pop' AND p.units_in_stock > 0";

$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html>
<?php require("header.php"); ?>
<body class="bg-background-color text-text-color font-poppins">
  <header class="bg-menu-background p-4">
    <div class="container mx-auto flex justify-between items-center">
      <img src="./src/assets/logo_square_original_no_background.png" alt="logo" class="w-16 h-16">
      <nav>
        <ul class="flex space-x-6">
          <li class="mx-4">
            <a href="#" class="text-menu-text">Home</a>
          </li>
          <li class="mx-4 dropdown relative">
            <a href="#" class="text-menu-text">Genres</a>
            <div class="submenu absolute hidden bg-menu-background top-10 left-0 w-32 text-menu-text">
              <a href="#">Rock</a>
              <a href="#">Jazz</a>
              <a href="#">Classical</a>
            </div>
          </li>
          <!-- ... other navigation items ... -->
        </ul>
      </nav>
    </div>
  </header>

  <div class="hero bg-gradient-to-b from-background-color to-secondary-background text-text-color py-20 text-center">
    <h1 class="text-4xl font-oswald font-bold mb-4">Welcome to CD Harmony</h1>
    <p class="text-lg mb-4">Discover a world of music.</p>
    <a href="#" class="cta-button bg-gradient-to-b from-accent-color to-text-color py-2 px-4 rounded-full font-bold hover:bg-hover-states transition duration-300 ease-in-out">Get Started</a>
  </div>

  <main class="content">
    <div class="section">
      <h2 class="section-title text-text-color">Featured Pop CDs</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php
        while ($row = $result->fetch_assoc()) {
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
        ?>
      </div>
    </div>

    <section class="section">
      <h2 class="section-title text-text-color">Featured Genres</h2>
      <div class="genre-items">
        <!-- Add genre items here -->
      </div>
    </section>

    <!-- Other sections go here -->

  </main>

  <?php require("footer.php"); ?>

</body>
</html>
