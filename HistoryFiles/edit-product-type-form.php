<?php use Controllers\ProductController;
use Services\SessionManager;
?>
<?php include 'admin-header.php' ?>

<main class='content bg-primary'>


    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
        <div class="flex-1 p-8">
        <?php
        ?>
        <form action="<?php echo BASE_URL.'/admin/product/update' ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto p-4 bg-white shadow-md rounded-md">
        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">  
        
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
            <input type="text" name="title" id="title" value="<?php echo $productDetails['title'] ?? ''; ?>"
                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea name="description" id="description"
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"><?php echo $productDetails['description'] ?? ''; ?></textarea>
        </div>
        <!-- Product Type Selection -->
        <div class="mb-4">
            <label for="productType" class="block text-sm font-medium text-gray-700">Choose Product Type:</label>
            <select name="productType" id="productType" required
                    class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="cd" <?php echo ($productDetails['product_type'] == 'cd') ? 'selected' : ''; ?>>CD</option>
                <option value="electronic_device" <?php echo ($productDetails['product_type'] == 'electronic_device') ? 'selected' : ''; ?>>Electronic Device</option>
            </select>
        </div>

        <!-- CD Fields -->
        <div id="cdFields" style="display: <?php echo ($productDetails['product_type'] == 'cd') ? 'block' : 'none'; ?>;">
            <!-- ... (existing CD fields) ... -->
        </div>

        <!-- Electronic Device Fields -->
        <div id="electronicDeviceFields" style="display: <?php echo ($productDetails['product_type'] == 'electronic_device') ? 'block' : 'none'; ?>;">
            <!-- ... (existing electronic device fields) ... -->
        </div>

        <!-- Image Upload -->
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Upload Image:</label>
            <input type="file" name="image" id="image"
                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>

        <!-- Other Common Fields -->
    

        <!-- Add any other common fields here -->

        <!-- Submit Button -->
        <div class="mb-4">
            <input type="submit" value="Submit" name="submit"
                class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>

         </form>


           <form action="<?php echo BASE_URL.'/admin/product/update' ?>" method="POST" enctype="multipart/form-data">
           

           <input type="file" name="fileToUpload" id="fileToUpload" name="fileToUpload">
              <input type="submit" value="Upload Image" name="submit">
           </form>
        </div>
    </div>
</main>

<?php include 'admin-footer.php' ?>
