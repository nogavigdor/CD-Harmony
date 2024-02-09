<?php
use Services\SessionManager;
use controllers\OrderController;;
?>

<?php include 'admin-header.php' ?>


<main class='flex bg-gray-100'>
    <!-- Sidebar -->
    <?php include './partials/admin-sidebar.php' ?>
    <?php
    ob_start(); // Start output buffering
    ?>
    <div class="w-full p-6">
        <h2 class="text-2xl font-bold mb-2">Invoice</h2>

        <div class="overflow-x-auto mt-6">
            <?php if (isset($total)): ?>
                <div class="mb-4">
                    <h3 class="font-bold">Order ID: <?= $total['order_id'] ?></h3>
                    <h3 class="font-bold">Customer Name: <?= $total['customer_name'] ?></h3>
                    <h3 class="font-bold">Customer Email: <?= $total['customer_email'] ?></h3>
                    <h3 class="font-bold">Order Date: <?= $total['order_date'] ?></h3>
                    <h3 class="font-bold">Sub Total: <?= $total['order_subtotal'] ?></h3>
                    <h3 class="font-bold">Discount Total: <?= $total['order_discount'] ?></h3>
                    <h3 class="font-bold">Grand Total: <?= $total['order_grand_total'] ?></h3>
                </div>

                <table class="w-full text-md bg-white shadow-md rounded mb-4">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-left p-3 px-5">Item Name</th>
                            <th class="text-left p-3 px-5">Condition</th>
                            <th class="text-left p-3 px-5">Quantity</th>
                            <th class="text-left p-3 px-5">Price</th>
                            <th class="text-left p-3 px-5">Item Subtotal</th>
                            <th class="text-left p-3 px-5">Item Discount</th>
                            <th class="text-left p-3 px-5">Item Grand Total</th>
                        </tr>
                        <?php foreach ($items as $item): ?>
                            <tr class="border-b">
                                <td class="p-3 px-5"><?= $item['product_name'] ?></td>
                                <td class="p-3 px-5"><?= $item['condition_title'] ?></td>
                                <td class="p-3 px-5"><?= $item['quantity_per_variant'] ?></td>
                                <td class="p-3 px-5"><?= $item['unit_price'] ?></td>
                                <td class="p-3 px-5"><?= $item['order_subtotal'] ?></td>
                                <td class="p-3 px-5"><?= $item['order_discount'] ?></td>
                                <td class="p-3 px-5"><?= $item['order_grand_total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No order details available.</p>
            <?php endif; ?>
        </div>
       <?php

            
        $invoiceHtml = ob_get_clean(); // Get output into a variable and clean (erase) the output buffer

        ob_flush(); // Flush (send) the output buffer and turn off output buffering
    
        echo $invoiceHtml; // Display the invoice

       //$to = $total['customer_email'];
       $to = 'admin@cdharmony.dk';
       $orderId = $total['order_id']; 
       $customerName = $total['customer_name'];
       $customerEmail = $total['customer_email'];
       $subject = 'Invoice No. '.$total['order_id'];
       $headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
       $from = 'contact@cdharmony.dk';
       ?>

       <!-- Email to admin button -->
        <div class="mt-4">
        <form action="<?= BASE_URL.'/admin/send-invoice'; ?>" method="post">
        <input type="hidden" name="invoiceHtml" value="<?= htmlspecialchars($invoiceHtml) ?>">
        <input type="hidden" name="orderId" value="<?=htmlspecialchars($orderId) ?>">
        <input type="hidden" name="to" value="<?= htmlspecialchars($to) ?>">
        <input type="hidden" name="customerName" value="<?= htmlspecialchars($customerName) ?>">
        <input type="hidden" name="customerEmail" value="<?= htmlspecialchars($customerEmail) ?>">
        <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
        <input type="hidden" name="headers" value="<?= htmlspecialchars($headers) ?>">
        <input type="hidden" name="from" value="<?= htmlspecialchars($from) ?>">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Email to Admin
        </button>
    </form>
</div>
    </div>
</main>