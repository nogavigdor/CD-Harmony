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
           <?php
    $url_components = explode('/', $_SERVER['REQUEST_URI']);
    $params = [];
    $sort_position = array_search('sort', $url_components);
    if ($sort_position !== false && count($url_components) > $sort_position + 1) {
        $params['sort_by'] = $url_components[$sort_position + 1];
    }
    if ($sort_position !== false && count($url_components) > $sort_position + 2) {
        $params['order_by'] = $url_components[$sort_position + 2];
    }
?>



<div class="grid grid-cols-3 gap-4">
    <div>
        <form id="sortForm" action="<?= BASE_URL . '/admin/products/sort/' ?>" method="GET" class="flex flex-col space-y-4">
        <div class="flex items-center space-x-2">
        <label for="sort_by" class="text-sm font-medium text-gray-700">Sort by:</label>
        <select id="sort_by" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="variant_creation_date" <?= isset($params['sort_by']) && $params['sort_by'] == 'variant_creation_date' ? 'selected' : '' ?>>Date</option>
            <option value="product_title" <?= isset($params['sort_by']) && $params['sort_by'] == 'product_title' ? 'selected' : '' ?>>Title</option>
            <option value="artist_title" <?= isset($params['sort_by']) && $params['sort_by'] == 'artist_title' ? 'selected' : '' ?>>Artist</option>
            <option value="product_id" <?= isset($params['sort_by']) && $params['sort_by'] == 'product_id' ? 'selected' : '' ?>>Product ID</option>
            <option value="product_variant_id" <?= isset($params['sort_by']) && $params['sort_by'] == 'product_variant_id' ? 'selected' : '' ?>>Variant ID</option>
        </select>
    </div>

    <div class="flex items-center space-x-2">
        <label for="order_by" class="text-sm font-medium text-gray-700">Order by:</label>
        <select id="order_by" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <option value="asc" <?= isset($params['order_by']) && $params['order_by'] == 'asc' ? 'selected' : '' ?>>Ascending</option>
            <option value="desc" <?= isset($params['order_by']) && $params['order_by'] == 'desc' ? 'selected' : '' ?>>Descending</option>
        </select>
    </div>

    <button type="button" id="sortButton" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Sort
    </button>
        </form>
    </div>
    <div>
        <form id="searchForm" action="<?= BASE_URL . '/admin/products/search/' ?>" method="GET" class="flex flex-col space-y-4">
            <div class="flex items-center space-x-2">
                <label for="search_by" class="text-sm font-medium text-gray-700">Search by:</label>
                <input id="search_by" type="text" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button type="button" id="searchButton" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-secondary hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Search
            </button>
        </form>
    </div>
    <div>
        <!-- The remaining third of the screen -->
    </div>
</div>
        <h2 class="text-2xl font-semibold mb-4">Product List</h2>
            <?php
        
            if (!empty($productsList)) {?>
            <table class="table-auto">
            <thead>
              <tr>
                <th>ID</th>
                <th>Variant ID</th>
                <th>Condition</th>
                <th>Album Title</th>
                <th>Artist</th>
                <th>Date Added</th>
               
                <th colspan="2">Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($productsList as $product) { ?>
              <tr>
                <td><?= $product->product_id ?></td>
                <td><?= $product->product_variant_id ?></td>
                <td><?= $product->condition_title ?></td>
                <td><?= $product->product_title ?></td>
                <td><?= $product->artist_title ?></td>
                <td><?= $product->variant_creation_date ?></td>
                
                <td>  <a href="<?= BASE_URL . '/admin/product/edit/' . $product->product_variant_id ?>" class="btn btn-primary">Edit</a>
                      <a href="<?= BASE_URL . '/admin/product/delete/' . $product->product_variant_id ?>" class="btn btn-danger">Delete</a>
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

document.getElementById('searchButton').addEventListener('click', function() {
    const searchTerm = document.getElementById('search_by').value;
    const url = `<?= BASE_URL . '/admin/products/search/' ?>${searchTerm}`;
    window.location.href = url;
});
</script>


<?php include 'admin-footer.php' ?>
