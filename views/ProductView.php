<!DOCTYPE html>
<html>
<?php require("header.php"); ?>
<body class="bg-background-color text-text-color font-poppins">
<main class="content">
<div class="bg-white rounded-lg overflow-hidden shadow-lg">
  <a href="product_details.php?product_id=PRODUCT_ID">
    <img src="IMAGE_URL" alt="Product Name" class="w-full h-64 object-cover">
  </a>
  <div class="p-4">
    <h3 class="text-xl font-semibold">Product Title</h3>
    <p class="text-gray-600">Artist Name</p>
    <p class="text-gray-600">Left in stock: PRODUCT_STOCK</p>
    <a href="#" class="mt-4 bg-button-color hover:bg-hover-states text-secondary-background py-2 px-4 rounded-full inline-block font-bold">Add to Cart</a>
  </div>
</div>
</main>
<?php require("footer.php"); ?>
</body>
</html?