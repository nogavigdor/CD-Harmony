<?php
use Services\SessionManager;
use controllers\OrderController;
?>

<?php include 'admin-header.php' ?>

<main class='flex bg-gray-100'>
    <!-- Sidebar -->
    <?php include './partials/admin-sidebar.php' ?>

    <!-- Content Area -->
    <div class="flex-1 p-8">
        <h1 class="flex flex-start text-2xl font-semibold mb-8">Orders List</h1>
        <?php
        $ordersController = new OrderController();
        $orders = $ordersController->getAllOrders();
       

        if (!empty($orders)) {
        ?>
            <table class="min-w-full divide-y divide-gray-200">
                <thead  class="bg-gray-50" >
                    <tr>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" >Order ID</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">status</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Email</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal </th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grand Total</th>

                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap" ><?= $order['order_id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $order['order_date'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $order['order_status'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $order['order_payment'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $order['customer_name'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $order['customer_email'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $order['order_subtotal'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $order['order_discount'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= $order['order_grand_total'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><a href="<?= BASE_URL . '/admin/invoice/' . $order['order_id'] ?>" class="ml-4 text-green-600 hover:text-green-900">Generate Invoice</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No orders found.</p>
        <?php } ?>
    </div>
</main>

<?php include 'admin-footer.php' ?>
