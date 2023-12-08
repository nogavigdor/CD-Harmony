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
           <form action="<?php echo BASE_URL.'/admin/product/create' ?>" method="POST" enctype="multipart/form-data">
           

           <input type="file" name="fileToUpload" id="fileToUpload" name="fileToUpload">
              <input type="submit" value="Upload Image" name="submit">
           </form>
        </div>
    </div>
</main>

<?php include 'admin-footer.php' ?>
