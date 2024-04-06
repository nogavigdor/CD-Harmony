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

//define("BASE_URL", "http://localhost/cdharmony");
require_once "./config/constants.php";
require_once "./utilities/functions.php";
require_once "router.php";

//Home page route
route('/cdharmony/', 'GET', function () {
    $controller = new MainViewController();
    $controller->showMainView();
});

//insert data into the database
route('/cdharmony/test', 'GET', function () {
    $controller = new TestController();
    $controller->insertData();
});

route('/cdharmony/products/section', 'POST', function(){
    // Retrieve the tag, offset, and limit parameters from the request
    $tag = $_POST['tag'];
    $offset = $_POST['offset'];
    $limit = $_POST['limit'];
    $controller = new ProductController();
    $controller->showProductsByTag($tag, $offset, $limit);
});

route('/cdharmony/cart/id/(\d+)', 'GET', function ($product_variant_id) {
    
    $controller = new CartController();
    $controller->addToCart($product_variant_id);
});
// update cart
route('/cdharmony/cart/update-cart/qty/(\d+)/id/(\d+)', 'GET', function ($qty,$product_variant_id) {
    
    $controller = new CartController();
    $controller->updateCart($qty, $product_variant_id);
});

// Remove item from cart
route('/cdharmony/cart/delete-from-cart/id/(\d+)', 'GET', function ($id) {
    $controller = new CartController();
    $controller->deleteFromCart($id);
});

// Checkout view
route('/cdharmony/cart/checkout', 'GET', function () {
    $controller = new CheckoutController();
    $controller->checkoutView();
});

// Checkout cart
route('/cdharmony/cart/checkout', 'POST', function () {
    $controller = new CheckoutController();
    $controller->checkout();
});

// View cart
route('/cdharmony/cart', 'GET', function () {
    $controller = new CartController();
    $controller->viewCart();
});

// View order confirmation
route('/cdharmony/order-confirmation', 'GET', function () {
    $controller = new OrderController();
    $controller->viewOrderConfirmation();
});

//Shows product details
route('/cdharmony/product/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    //when accessing the product details from the main page, the role is set to none
    $controller->showProductDetails($id, 'none');
});


route('/cdharmony/admin/product/(\d+)', 'POST', function ($id) {
    $controller = new ProductController();
    $controller->showProductDetails($id,'admin');
});

route('/cdharmony/admin/product/delete/(\d+)', 'DELETE', function ($variantId) {
    $controller = new ProductController();
    $controller->deleteProductVariant($variantId);
});

route('/cdharmony/admin/product/edit/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    $controller->showEditProductForm($id);
});

route('/cdharmony/admin/product/update/', 'POST', function () {
    $controller = new ProductController();
    $controller->updateProduct();
});

route('/cdharmony/admin/product/add/', 'POST', function () {
    $controller = new ProductController();
    $controller->addProduct();
});
route('/cdharmony/admin/product/add', 'GET', function () {
    $controller = new ProductController();
    $controller->showAddProductForm();
});

route('/cdharmony/admin/invoice/(\d+)', 'GET', function ($id) {
    $controller = new OrderController();
    $controller->showInvoice($id);
});

route('/cdharmony/admin/send-invoice', 'POST', function () {
    $controller = new OrderController();
    $controller->sendInvoice();
});

route('/cdharmony/admin/orders', 'GET', function () {
    $controller = new OrderController();
    $controller->showOrders();
});


route('/cdharmony/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdharmony/admin/articles/', 'GET', function () {
    $controller = new ArticleController();
    $controller->showAllArticles();
});


route('/cdharmony/admin/article/add/', 'GET', function () {
    $controller = new ArticleController();
    $controller->showArticleForm();
});

route('/cdharmony/admin/article/add/', 'POST', function () {
    $controller = new ArticleController();
    $controller->addArticle();
});


route('/cdharmony/admin/article/delete/(\d+)', 'DELETE', function ($id) {
    $controller = new ArticleController();
    $controller->deleteArticle($id);
});

route('/cdharmony/admin/article/edit/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showEditArticleForm($id);
});

route('/cdharmony/admin/article/update', 'PUT', function () {
    $controller = new ArticleController();
    $controller->updateArticle();
});


route('/cdharmony/admin/special-offers/', 'GET', function () {
    $controller = new SpecialOfferController();
    $controller->showSpecialOffers();
});

route('/cdharmony/admin/special-offers/update-homepage', 'POST', function () {
     // Retrieve the product_variant_id from the POST data
    $productVariantId = $_POST['product_variant_id'];
    $controller = new SpecialOfferController();
    $controller->updateHomepage($productVariantId);
});


route('/cdharmony/admin/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdharmony/contact/', 'GET', function() {
    $controller = new ContactController();
    $controller->contactView();
});


route('/cdharmony/contact/', 'POST', function() {
    $controller = new ContactController();
    $controller->contactInput();
});

route('/cdharmony/login/', 'GET', function() {
    $controller = new LoginController();
    $controller->loginView();
});
route('/cdharmony/login/', 'POST', function() {
    $controller = new UserController();
    $controller->authenticateUser();
});

route('/cdharmony/logout/', 'GET', function() {
    $controller = new Controllers\LoginController();
    $controller->logout();
});

route('/cdharmony/signup/', 'GET', function() {
    $controller = new UserController();
    $controller->signupView();
});

route('/cdharmony/signup/', 'POST', function() {
    $controller = new UserController();
    $controller->createAccount();
});





route('/cdharmony/admin/', 'GET', function () {
    $controller = new AdminController();
    $controller->adminView();
});

route('/cdharmony/admin/product/', 'GET', function () {
    $controller = new ProductController();
    $controller->showProductList();
});

route('/cdharmony/admin-login', 'GET', function() {
    $controller = new AdminController();
    $controller->adminLoginView();

});

route('/cdharmony/admin-login', 'POST', function() {
  $controller = new AdminController();
  $controller->adminLogin();
 
});


route('/cdharmony/admin/company/', 'GET', function () {
    $controller = new CompanyController();  
    $controller->showCompanyDetails();
});

route('/cdharmony/admin/company/', 'POST', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});


route('/cdharmony/admin/products/', 'GET', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});



// Sort products on admin page
route('/cdharmony/admin/products/sort/(\w+)/(\w+)', 'GET', function ($sortBy, $orderBy) {
    $controller = new ProductController();
    $controller->showAdminProducts($sortBy, $orderBy);
});

// Route for when there's a search term
route('/cdharmony/admin/products/search/([ \w]+)', 'GET', function ($searchTerm) {
    $controller = new ProductController();
    $controller->search($searchTerm);
});

// Route for when there isn't a search term
route('/cdharmony/admin/products/search/', 'GET', function () {
    $controller = new ProductController();
    $controller->search('');
}); 

route('/cdharmony/admin/products/', 'GET', function () {
    $controller = new ProductController();
    $controller->showAdminProducts();
});

//CHECK THIS LATER
route('/cdharmony/admin/products/', 'POST', function () {
    $controller = new AdminController();
    $controller->handleProduct();
});

route('/cdharmony/admin/product/add/special-offer/(\d+)', 'GET', function ($variant_id) {
    $controller = new SpecialOfferController();
    $controller->showSpecialOfferForm($variant_id);
});

route('/cdharmony/admin/product/add-special-offer/', 'POST', function() {
    $controller = new SpecialOfferController();
    $controller->addSpecialOffer();
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




