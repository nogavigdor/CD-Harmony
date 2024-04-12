
        
        <?php include 'header.php'; ?>


<!-- Main content section -->
<main class="container h-min  mx-auto p-4 flex">
    <!-- Left Sidebar -->
    <aside class="sidebar w-1/4 bg-white p-4 border-r border-gray-300">
        <h2 class="text-lg font-bold mb-4">User Options</h2>
        <ul>
            <li class="mb-2">
                <a href="<?php BASE_URL.'/views/acount'?>" class="flex items-center text-gray-700 hover:text-gray-900">
                    <i class="fas fa-history mr-2"></i> <!-- Icon for Orders History -->
                    <span class="font-bold">View Order History</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center text-gray-700 hover:text-gray-900">
                    <i class="fas fa-address-card mr-2"></i> <!-- Icon for Update Address -->
                    <span class="font-bold">Information</span>
                </a>
            </li>
            <!-- Add more options here if needed -->
        </ul>
    </aside>

    <!-- Content section -->
    <section class="content w-3/4 bg-white p-4">
        <h1 class="text-2xl font-bold mb-4">Orders History</h1>
        <!-- Display Order Details -->
        <div class="overflow-x-auto">
            <?php foreach ($ordersSummary as $orderSummary): ?>
                <!-- Display Order Headline -->
                <div class="border rounded-lg mb-4 overflow-hidden">
                    <div class="p-4 bg-gray-100">
                        <h2 class="text-lg  text-secondary font-bold inline-block">
                            Order #: <?php echo $orderSummary['order_id']; ?>
                        </h2>
                        <p class="text-sm inline-block ml-4">
                            Order Date: <?php echo $orderSummary['order_date']; ?>
                        </p>
                        <p class="text-sm inline-block ml-4">
                            Total Price: $<?php echo $orderSummary['order_grand_total']; ?>
                        </p>
                    </div>
                    <!-- Display Order Items --> 
                    <div class="p-4">
                        <dl class="grid grid-cols-5 gap-4">
                        <div class="font-bold">CD Cover</div>
                        <div class="font-bold">Condition</div>
                            <div class="font-bold">Product Title</div>
                            <div class="font-bold">Price</div>
                            <div class="font-bold">Quantity</div>
                            <?php foreach ($ordersDetails as $orderDetail): ?>
                                
                                  <div>  <img src="<?php echo BASE_URL . IMAGE_PATH . $orderDetail['image_name']; ?>" alt="<?php echo $orderDetail['product_title']; ?>" class="w-16 h-16 object-cover"></div>
                                <div><?php echo $orderDetail['condition_title']; ?></div>
                                  <div><?php echo $orderDetail['product_title']; ?></div>
                                <div><?php echo $orderDetail['item_price']; ?>DKK</div>
                                <div><?php echo $orderDetail['quantity']; ?></div>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>



</main>
    <?php include 'footer.php'; ?>
