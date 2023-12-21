<?php use Services\SessionManager;
use Controllers\CartController;
use Controllers\ProductController;

?>
<?php include 'header.php'; ?>

<!-- Main content section -->
<main class="container mx-auto p-4 bg-white mt-8 min-h-screen">
    <h1 class="text-2xl headline text-secondary font-bold mb-4">Order Confirmation</h1>

    <p> Thank you for your order. Your order number is <?php $orderId ?> . We will send you an email confirmation shortly.</p>
    <div class="flex flex-start mb-4">
    <a href="<?= BASE_URL; ?>" class="btn flex-1 text-center py-2 m-2">Back to Shop</a>  
       
    </div>
   
    </main>
  


<?php include 'footer.php'; ?>