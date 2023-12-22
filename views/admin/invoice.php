<?php
use Services\SessionManager;
use controllers\OrderController;;
?>
<?php
$orderController = new OrderController();
$order = $orderController->getOrderDetails($_GET['id']);
?>
<?php include 'admin-header.php' ?>

<?php include 'admin-header.php' ?>

<main class='flex bg-gray-100'>
    <!-- Sidebar -->
    <?php include './partials/admin-sidebar.php' ?>

    <div class="w-full p-6">
        <h2 class="text-2xl font-bold mb-2">Invoice</h2>

        <div class="overflow-x-auto mt-6">
            <?php if (isset($order)): ?>
                <div class="mb-4">
                    <h3 class="font-bold">Order ID: <?= $order['order_id'] ?></h3>
                    <h3 class="font-bold">Customer Name: <?= $order['customer_name'] ?></h3>
                    <h3 class="font-bold">Customer Email: <?= $order['customer_email'] ?></h3>
                    <h3 class="font-bold">Order Date: <?= $order['order_date'] ?></h3>
                    <h3 class="font-bold">Total Amount: <?= $order['total_amount'] ?></h3>
                </div>

                <table class="w-full text-md bg-white shadow-md rounded mb-4">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-left p-3 px-5">Product</th>
                            <th class="text-left p-3 px-5">Quantity</th>
                            <th class="text-left p-3 px-5">Price</th>
                        </tr>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr class="border-b">
                                <td class="p-3 px-5"><?= $item['product_name'] ?></td>
                                <td class="p-3 px-5"><?= $item['quantity'] ?></td>
                                <td class="p-3 px-5"><?= $item['price'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No order details available.</p>
            <?php endif; ?>
        </div>
    </div>
</main>