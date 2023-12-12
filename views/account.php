<?php
use Services\SessionManager;   
include 'header.php'; 
?>
 
        <!-- Main content section -->
        <main class="container mx-auto p-4">
            <?= '<h1 class="text-2xl font-bold mb-4">Welcome to your acount area ' . SessionManager::getSessionVariable('user')['first_name'] . '</h1>' ?>
        </main>

        <!-- Footer section -->
        <?php
            include 'footer.php';
        ?>
    </body>
    </html>