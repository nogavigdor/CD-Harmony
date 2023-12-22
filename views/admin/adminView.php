<?php include 'admin-header.php' ?>

<?php
use Services\SessionManager;
?>


<main class="flex  bg-gray-100">

        <!-- Sidebar -->
        <?php include './partials/admin-sidebar.php' ?>

        <!-- Content Area -->
        <div class="flex-1 p-8">
          <h1> <?= 'Welcome to mini admin of CD Harmony.'; ?></h1>
        </div>
   
</main>
<?php include 'admin-footer.php' ?>
