<?php use Controllers\ProductController;
use Services\SessionManager;
?>
<?php include 'admin-header.php' ?>

<main class='flex  bg-gray-100'>

        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
        <div class="flex-1 p-8">
        <a href="<?php echo BASE_URL.'/admin/product/add/' ?>" class="btn">Add Product</a>
        <h2 class="text-2xl font-semibold mb-4">Product List</h2>
            <?php
            $products = new ProductController();
            $productsList = $products->getAllVariants(); 
            if (!empty($productsList)) {?>
            <table class="table-auto">
            <thead>
              <tr>
                <th>Product ID</th>
              
                <th>Title</th>
               
                <th colspan="2">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($productsList as $product) { ?>
              <tr>
                <td><?= $product->product_id ?></td>
               
                <td><?= $product->product_title ?></td>
                
                <td>  <a href="<?= BASE_URL . '/admin/product/edit/' . $product->product_id ?>" class="btn btn-primary">Edit</a>
                      <a href="<?= BASE_URL . '/admin/product/delete/' . $product->product_id ?>" class="btn btn-danger">Delete</a>
                    </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
            <?php } else { ?>
                <p>No products found.</p>
            <?php } ?>
        </div>
  
</main>

<?php include 'admin-footer.php' ?>
