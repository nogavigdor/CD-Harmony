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
           <!-- Dynamic form for sorting -->
           <form id="sortForm" action="<?= BASE_URL . '/admin/products/sort/' ?>" method="GET">
    <label for="sort_by">Sort by:</label>
    <select  id="sort_by">
        <option value="variant_creation_date">Date</option>
        <option value="product_title">Title</option>
        <option value="artist_title">Artist</option>
    </select>

    <label for="order_by">Order by:</label>
    <select  id="order_by">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
    </select>

    <button type="button" id="sortButton">Sort</button>
</form>
        <h2 class="text-2xl font-semibold mb-4">Product List</h2>
            <?php
        
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
                <td><?= htmlspecialchars($product->product_id) ?></td>
                <td><?= htmlspecialchars($product->product_title) ?></td>
                <td><?= htmlspecialchars($product->artist_title) ?></td>
                <td><?= htmlspecialchars($product->variant_creation_date) ?></td>
                
                <td>
                    <a href="<?= htmlspecialchars(BASE_URL . '/admin/product/edit/' . $product->product_id) ?>" class="btn btn-primary">Edit</a>
                    <a href="<?= htmlspecialchars(BASE_URL . '/admin/product/delete/' . $product->product_id) ?>" class="btn btn-danger">Delete</a>
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
<script>
document.getElementById('sortButton').addEventListener('click', function() {
    const sortBy = document.getElementById('sort_by').value;
    const orderBy = document.getElementById('order_by').value;
    const url = `<?= BASE_URL . '/admin/products/sort/' ?>${sortBy}/${orderBy}`;
    window.location.href = url;
});
</script>
</script

<?php include 'admin-footer.php' ?>
