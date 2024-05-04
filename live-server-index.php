<?php 

require_once "bootstrap.php";

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;
use Controllers\MainViewController;
use Controllers\ProductController;
use Controllers\ArticleController;
use Controllers\SpecialOfferController;
use Controllers\ContactController;
use Controllers\LoginController;
use Controllers\UserController;
use Controllers\AdminController;
use Controllers\CompanyController;
use Controllers\CartController;
use Controllers\CheckoutController;
use Controllers\OrderController;
use Controllers\TestController;


//require './PHPMailer-master/src/Exception.php';
//require './PHPMailer-master/src/PHPMailer.php';
//require './PHPMailer-master/src/SMTP.php';

//define("BASE_URL", "http://localhost/cdharmony2");
require_once "./config/constants.php";
require_once "./utilities/functions.php";
require_once "router.php";

//Home page route
route('/', 'GET', function () {
    $controller = new MainViewController();
    $controller->showMainView();
});

//insert data into the database
route('/test', 'GET', function () {
    $controller = new TestController();
    $controller->insertData();
});

route('/products/section', 'POST', function(){
    // Retrieve the tag, offset, and limit parameters from the request
    $tag = $_POST['tag'];
    $offset = $_POST['offset'];
    $limit = $_POST['limit'];
    $controller = new ProductController();
    $controller->showProductsByTag($tag, $offset, $limit);
});

route('/cart/id/(\d+)', 'GET', function ($product_variant_id) {
    
    $controller = new CartController();
    $controller->addToCart($product_variant_id);
});
// update cart
route('/cart/update-cart/qty/(\d+)/id/(\d+)', 'GET', function ($qty,$product_variant_id) {
    
    $controller = new CartController();
    $controller->updateCart($qty, $product_variant_id);
});

// Remove item from cart
route('/cart/delete-from-cart/id/(\d+)', 'GET', function ($id) {
    $controller = new CartController();
    $controller->deleteFromCart($id);
});

// Checkout view
route('/cart/checkout', 'GET', function () {
    $controller = new CheckoutController();
    $controller->checkoutView();
});

//stripe checkout 
route('/cart/stripe-checkout', 'GET', function () {
    $controller = new CheckoutController();
    $controller->stripeCheckout();
});

//stripe webhook - for handling stripe events - for future implementation
/*
route('/cart/stripe-webhook', 'GET', function () {
    $controller = new CheckoutController();
    $controller->stripeWebhook();
});
*/

// Checkout cart - was replace by stripeCheckout()
/*
route('/cart/checkout', 'POST', function () {
    $controller = new CheckoutController();
    $controller->checkout();
});
*/
// View cart
route('/cart', 'GET', function () {
    $controller = new CartController();
    $controller->viewCart();
});

// View order confirmation
route('/order-confirmation', 'GET', function () {
    $controller = new OrderController();
    $controller->viewOrderConfirmation();
});

//Shows product details
route('/product/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    //when accessing the product details from the main page, the role is set to none
    $controller->showProductDetails($id, 'none');
});


route('/admin/product/(\d+)', 'POST', function ($id) {
    $controller = new ProductController();
    $controller->showProductDetails($id,'admin');
});

route('/admin/products/', 'DELETE', function () {
    $controller = new ProductController();
    $controller->deleteProductVariant();
});

route('/admin/product/edit/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    $controller->showEditProductForm($id);
});

route('/admin/product/update/', 'PUT', function () {
    $controller = new ProductController();
    $controller->updateProduct();
});

route('/admin/product/add/', 'POST', function () {
    $controller = new ProductController();
    $controller->addProduct();
});
route('/admin/product/add', 'GET', function () {
    $controller = new ProductController();
    $controller->showAddProductForm();
});

route('/admin/invoice/(\d+)', 'GET', function ($id) {
    $controller = new OrderController();
    $controller->showInvoice($id);
});

route('/admin/send-invoice', 'POST', function () {
    $controller = new OrderController();
    $controller->sendInvoice();
});

route('/admin/orders', 'GET', function () {
    $controller = new OrderController();
    $controller->showOrders();
});


route('/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/admin/articles/', 'GET', function () {
    $controller = new ArticleController();
    $controller->showAllArticles();
});


route('/admin/article/add/', 'GET', function () {
    $controller = new ArticleController();
    $controller->showArticleForm();
});

route('/admin/article/add/', 'POST', function () {
    $controller = new ArticleController();
    $controller->addArticle();
});


route('/admin/article/delete/(\d+)', 'DELETE', function ($id) {
    $controller = new ArticleController();
    $controller->deleteArticle($id);
});

route('/admin/article/edit/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showEditArticleForm($id);
});


route('/admin/article/update', 'PUT', function () {
    $controller = new ArticleController();
    $controller->updateArticle();
});


route('/admin/special-offers/', 'GET', function () {
    $controller = new SpecialOfferController();
    $controller->showSpecialOffers();
});


route('/admin/special-offers/edit/(\d+)', 'GET', function ($id) {
    $controller = new SpecialOfferController();
    $controller->showEditSpecialOfferForm($id);
});

route('/admin/special-offer/update/', 'PUT', function () {
    $controller = new SpecialOfferController();
    $controller->updateSpecialOffer();
});

route('/admin/special-offers/delete/', 'DELETE', function () {
    $controller = new SpecialOfferController();
    $controller->deleteSpecialOffer();
});

route('/admin/special-offers/update-homepage', 'POST', function () {
     // Retrieve the product_variant_id from the POST data
    $productVariantId = $_POST['product_variant_id'];
    $controller = new SpecialOfferController();
    $controller->updateHomepage($productVariantId);
});


route('/admin/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/contact/', 'GET', function() {
    $controller = new ContactController();
    $controller->contactView();
});


route('/contact/', 'POST', function() {
    $controller = new ContactController();
    $controller->contactInput();
});

route('/login/', 'GET', function() {
    $controller = new LoginController();
    $controller->loginView();
});
route('/login/', 'POST', function() {
    $controller = new UserController();
    $controller->authenticateUser();
});

route('/logout/', 'GET', function() {
    $controller = new Controllers\LoginController();
    $controller->logout();
});

route('/signup/', 'GET', function() {
    $controller = new UserController();
    $controller->signupView();
});

route('/signup/', 'POST', function() {
    $controller = new UserController();
    $controller->createAccount();
});





route('/admin/', 'GET', function () {
    $controller = new AdminController();
    $controller->adminView();
});
/*
route('/admin/product/', 'GET', function () {
    $controller = new ProductController();
    $controller->showProductList();
});
*/
route('/admin-login', 'GET', function() {
    $controller = new AdminController();
    $controller->adminLoginView();

});

route('/admin-login', 'POST', function() {
  $controller = new AdminController();
  $controller->adminLogin();
 
});


route('/admin/company/', 'GET', function () {
    $controller = new CompanyController();  
    $controller->showCompanyDetails();
});

route('/admin/company/', 'PUT', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});



// Sort products on admin page
route('/admin/products/sort/(\w+)/(\w+)', 'GET', function ($sortBy, $orderBy) {
    $controller = new ProductController();
    $controller->showAdminProducts($sortBy, $orderBy);
});

// Route for when there's a search term
route('/admin/products/search/([ \w]+)', 'GET', function ($searchTerm) {
    $controller = new ProductController();
    $controller->search($searchTerm);
});

// Route for when there isn't a search term
route('/admin/products/search/', 'GET', function () {
    $controller = new ProductController();
    $controller->search('');
}); 

route('/admin/products/', 'GET', function () {
    $controller = new ProductController();
    $controller->showAdminProducts();
});



route('/admin/product/add/special-offer/(\d+)', 'GET', function ($variant_id) {
    $controller = new SpecialOfferController();
    $controller->showSpecialOfferForm($variant_id);
});

route('/admin/product/add-special-offer/', 'POST', function() {
    $controller = new SpecialOfferController();
    $controller->addSpecialOffer();
});

route('/acount/', 'GET', function() {
    $controller = new UserController();
    $controller->acountView();
});






// Dispatch the router
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$path = urldecode($path); // Decode the URL
$method = $_SERVER['REQUEST_METHOD'];
// If the method is POST, check for method override
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']); // Use the method override
}
dispatch($path, $method);




