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

echo var_dump($result); 
echo print_r($result);  

// Close the database connection
$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./dist/output.css" rel="stylesheet">
  <link href="./src/css/custom.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <img src="./src/assets/logo_square_original_no_background.png" alt="logo" class="logo">
    <nav>
      <ul class="flex justify-center py-4">
        <li class="mx-4">
          <a href="#" class="text-menu-text">Home</a>
        </li>
        <li class="mx-4 dropdown">
          <a href="#" class="text-menu-text">Genres</a>
          <div class="submenu">
            <a href="#">Rock</a>
            <a href="#">Jazz</a>
            <a href="#">Classical</a>
          </div>
        </li>
        <li class="mx-4">
          <a href="#" class="text-menu-text">Blog</a>
        </li>
        <li class="mx-4">
          <a href="#" class="text-menu-text">Deals</a>
        </li>
        <li class="mx-4 dropdown">
          <a href="#" class="text-menu-text">More</a>
          <div class="submenu">
            <a href="#">Rares</a>
            <a href="#">My Cart</a>
            <a href="#">Recommendations</a>
          </div>
        </li>
      </ul>
    </nav>
  </header>

  <div class="hero">
    <h1 class="text-4xl font-bold mb-4">Welcome to CD Harmony</h1>
    <p class="text-lg mb-4">Discover a world of music.</p>
    <a href="#" class="cta-button">Get Started</a>
  </div>

  <main class="content">


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
           echo '<p class="text-gray-600">Left in stock: ' . $row['units_in_stock'] . '</p>';
            echo '<a href="#" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-full inline-block">Add to Cart</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>


    <section class="section">
      <h2 class="section-title">Featured Genres</h2>
      <div class="genre-items">
     
        <div class="product-item">
          <img src="genre1.jpg" alt="Genre 1">
          <h3>Rock Classics</h3>
          <p>Discover legendary rock albums.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
        <div class="product-item">
          <img src="genre2.jpg" alt="Genre 2">
          <h3>Jazz Essentials</h3>
          <p>Explore the world of jazz.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
        <div class="product-item">
          <img src="genre3.jpg" alt="Genre 3">
          <h3>Classical Melodies</h3>
          <p>Timeless classical compositions.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
      </div>
    </section>

    <section class="section">
      <h2 class="section-title">Latest Blog Posts</h2>
      <div class="blog-posts">
 
        <div class="blog-post">
          <h3>Top 10 Albums of All Time</h3>
          <p>Discover the greatest albums in music history.</p>
        </div>
        <div class="blog-post">
          <h3>Exploring Jazz: A Musical Journey</h3>
          <p>Learn about the origins of jazz music.</p>
        </div>
        <div class="blog-post">
          <h3>The Beauty of Classical Compositions</h3>
          <p>Explore the world of classical music.</p>
        </div>
      </div>
    </section>

    <section class="section">
      <h2 class="section-title">Hot Deals</h2>
      <div class="deal-items">
  
        <div class="product-item">
          <img src="deal1.jpg" alt="Deal 1">
          <h3>Classic Rock Collection</h3>
          <p>Get these rock classics at a special price.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
        <div class="product-item">
          <img src="deal2.jpg" alt="Deal 2">
          <h3>Jazz Greats Compilation</h3>
          <p>Get a mix of jazz masterpieces in one package.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
        <div class="product-item">
          <img src="deal3.jpg" alt="Deal 3">
          <h3>Classical Gems Selection</h3>
          <p>Enjoy the beauty of classical music with this set.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
      </div>
    </section>

    <section class="section">
      <h2 class="section-title">Rare Finds</h2>
      <div class="rare-items">

        <div class="product-item">
          <img src="rare1.jpg" alt="Rare 1">
          <h3>The Beatles - 'Abbey Road'</h3>
          <p>A limited edition vinyl of this iconic album.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
        <div class="product-item">
          <img src="rare2.jpg" alt="Rare 2">
          <h3>Louis Armstrong - 'What a Wonderful World'</h3>
          <p>An autographed vinyl by the jazz legend.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
        <div class="product-item">
          <img src="rare3.jpg" alt="Rare 3">
          <h3>Beethoven - Symphony No. 9</h3>
          <p>Original sheet music from Beethoven's era.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
      </div>
    </section>

    <section class="section">
      <h2 class="section-title">Personalized Recommendations</h2>
      <div class="recommendation-items">
     
        <div class="product-item">
          <img src="recommend1.jpg" alt="Recommendation 1">
          <h3>Discover the Classics</h3>
          <p>Our classic music recommendations for you.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
        <div class="product-item">
          <img src="recommend2.jpg" alt="Recommendation 2">
          <h3>Exploring Jazz</h3>
          <p>Jazz tunes that you'll love.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
        <div class="product-item">
          <img src="recommend3.jpg" alt="Recommendation 3">
          <h3>Classical Masterpieces</h3>
          <p>Timeless classical compositions tailored for you.</p>
          <a href="#" class="add-to-basket-button">Add to Basket</a>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="footer-content">
      <div class="footer-logo">
        <h1 class="text-2xl font-bold text-menu-text">CD Harmony</h1>
        <p class="text-menu-text">Finding Your Perfect Pitch</p>
      </div>
      <div class="footer-contact">
        <h2 class="text-lg font-bold text-menu-text">Contact Us</h2>
        <p class="text-menu-text">123 Music Ave, Retro City, Denmark</p>
        <p class="text-menu-text">Email: info@cdharmony.com</p>
      </div>
      <div class="footer-social">
        <h2 class="text-lg font-bold text-menu-text">Follow Us</h2>
        <div class="social-icons">
          <!-- Add social media icons and links here -->
          <a href="#"><img src="facebook.png" alt="Facebook"></a>
          <a href="#"><img src="twitter.png" alt="Twitter"></a>
          <a href="#"><img src="instagram.png" alt="Instagram"></a>
        </div>
      </div>
    </div>
    <p class="text-menu-text text-center mt-4">© 2023 CD Harmony</p>
  </footer>
</body>
</html>
