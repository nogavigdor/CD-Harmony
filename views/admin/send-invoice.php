<?php
use Services\SessionManager;

?>

<?php include 'admin-header.php' ?>

<main class='flex bg-gray-100'>
    <!-- Sidebar -->
    <?php include './partials/admin-sidebar.php' ?>

    <div class="w-full p-6">
        <h2 class="text-2xl font-bold mb-2">Invoice</h2>

        <?php
        if (isset($_POST['invoiceHtml'])) {
            $message = $_POST['invoiceHtml'];
            $subject = 'Invoice';
            $customerName = $_POST['customerName'];
            $from = ADMIN_EMAIL;
            $to = $_POST['to'];
            $headers = "From: " . ADMIN_EMAIL . "\r\n";
            $headers .= "Reply-To: " . ADMIN_EMAIL . "\r\n";
            $headers .= "CC: " . ADMIN_EMAIL . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
            sendMail($to, $customerName, $from, $subject, $message, $headers);
    }
    
    ?>
    </div>
</main>