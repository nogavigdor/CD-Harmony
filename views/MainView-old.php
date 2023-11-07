
<!DOCTYPE html>
<html>
<?php

require("header.php"); 
?>
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

          (new models\ProductModel)->readProducts('pop');

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

  <?php require("footer.php"); ?>A

</body>
</html>
