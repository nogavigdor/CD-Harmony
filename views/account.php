<?php
use Services\SessionManager;   
include 'header.php'; 
?>
 
        <!-- Main content section -->
        <main class="container min-h-screen mx-auto p-4 ">
            <?= '<h1 class="text-2xl text-white font-bold mb-4">Welcome to your acount area ' . SessionManager::getSessionVariable('user')['first_name'] . '</h1>' ?>
        </main>

        <!-- Footer section -->
        <?php
            include 'footer.php';
        ?>
