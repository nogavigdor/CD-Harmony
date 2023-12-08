<?php include 'admin-header.php' ?>

<main class='content bg-primary'>
<?php
use Services\SessionManager;
?>


<div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
        <div class="flex-1 p-8">
           <?= 'implement a product form to update product details'; ?>
        </div>
    </div>
</main>
<?php include 'admin-footer.php' ?>
