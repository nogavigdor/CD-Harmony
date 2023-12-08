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
           <?= 'implement number of products, number of users number of orders'; ?>
        </div>
    </div>
</main>
<?php include 'admin-footer.php' ?>
