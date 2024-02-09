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

//define("BASE_URL", "http://localhost/cdhrmny");
require_once "./config/constants.php";
require_once "./utilities/functions.php";
require_once "router.php";

//Home page route
route('/cdhrmny/', 'GET', function () {
    $controller = new MainViewController();
    $controller->showMainView();
});

//insert data into the database
route('/cdhrmny/test', 'GET', function () {
    $controller = new TestController();
    $controller->insertData();
});



route('/cdhrmny/cart/id/(\d+)', 'GET', function ($product_variant_id) {
    
    $controller = new CartController();
    $controller->addToCart($product_variant_id);
});
// update cart
route('/cdhrmny/cart/update-cart/qty/(\d+)/id/(\d+)', 'GET', function ($qty,$product_variant_id) {
    
    $controller = new CartController();
    $controller->updateCart($qty, $product_variant_id);
});

// Remove item from cart
route('/cdhrmny/cart/delete-from-cart/id/(\d+)', 'GET', function ($id) {
    $controller = new CartController();
    $controller->deleteFromCart($id);
});

// Checkout view
route('/cdhrmny/cart/checkout', 'GET', function () {
    $controller = new CheckoutController();
    $controller->checkoutView();
});

// Checkout cart
route('/cdhrmny/cart/checkout', 'POST', function () {
    $controller = new CheckoutController();
    $controller->checkout();
});

// View cart
route('/cdhrmny/cart', 'GET', function () {
    $controller = new CartController();
    $controller->viewCart();
});

// View order confirmation
route('/cdhrmny/order-confirmation', 'GET', function () {
    $controller = new OrderController();
    $controller->viewOrderConfirmation();
});

//Shows product details
route('/cdhrmny/product/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    //when accessing the product details from the main page, the role is set to none
    $controller->showProductDetails($id, 'none');
});


route('/cdhrmny/admin/product/(\d+)', 'POST', function ($id) {
    $controller = new ProductController();
    $controller->showProductDetails($id,'admin');
});

route('/cdhrmny/admin/product/delete/(\d+)', 'DELETE', function ($id) {
    $controller = new ProductController();
    $controller->deleteProduct($id);
});

route('/cdhrmny/admin/product/edit/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    $controller->showEditProductForm($id);
});

route('/cdhrmny/admin/product/update/', 'PUT', function () {
    $controller = new ProductController();
    $controller->updateProduct();
});

route('/cdhrmny/admin/product/add/', 'POST', function () {
    $controller = new ProductController();
    $controller->addProduct();
});
route('/cdhrmny/admin/product/add', 'GET', function () {
    $controller = new ProductController();
    $controller->showAddProductForm();
});

route('/cdhrmny/admin/invoice/(\d+)', 'GET', function ($id) {
    $controller = new OrderController();
    $controller->showInvoice($id);
});

route('/cdhrmny/admin/send-invoice', 'POST', function () {
    $controller = new OrderController();
    $controller->sendInvoice();
});

route('/cdhrmny/admin/orders', 'GET', function () {
    $controller = new OrderController();
    $controller->showOrders();
});


route('/cdhrmny/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdhrmny/admin/articles/', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showAllArticles($id);
});

route('/cdhrmny/admin/article/delete/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->deleteArticle($id);
});

route('/cdhrmny/admin/article/update/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->updateArticle($id);
});

route('/cdhrmny/admin/article/create/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->createArticle($id);
});

route('/cdhrmny/admin/special-offers/', 'GET', function () {
    $controller = new SpecialOfferController();
    $controller->showSpecialOffers();
});


route('/cdhrmny/admin/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdhrmny/contact/', 'GET', function() {
    $controller = new ContactController();
    $controller->contactView();
});


route('/cdhrmny/contact/', 'POST', function() {
    $controller = new ContactController();
    $controller->contactInput();
});

route('/cdhrmny/login/', 'GET', function() {
    $controller = new LoginController();
    $controller->loginView();
});
route('/cdhrmny/login/', 'POST', function() {
    $controller = new UserController();
    $controller->authenticateUser();
});

route('/cdhrmny/logout/', 'GET', function() {
    $controller = new Controllers\LoginController();
    $controller->logout();
});

route('/cdhrmny/signup/', 'GET', function() {
    $controller = new UserController();
    $controller->signupView();
});

route('/cdhrmny/signup/', 'POST', function() {
    $controller = new UserController();
    $controller->createAccount();
});





route('/cdhrmny/admin/', 'GET', function () {
    $controller = new AdminController();
    $controller->adminView();
});

route('/cdhrmny/admin/product/', 'GET', function () {
    $controller = new ProductController();
    $controller->showProductList();
});

route('/cdhrmny/admin-login', 'GET', function() {
    $controller = new AdminController();
    $controller->adminLoginView();

});

route('/cdhrmny/admin-login', 'POST', function() {
  $controller = new AdminController();
  $controller->adminLogin();
 
});


route('/cdhrmny/admin/company/', 'GET', function () {
    $controller = new CompanyController();  
    $controller->showCompanyDetails();
});

route('/cdhrmny/admin/company/', 'POST', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});

route('/cdhrmny/admin/articles/', 'GET', function () {
    $controller = new ArticleController();
    $controller->getArticles();
});

route('/cdhrmny/admin/products/', 'GET', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});



// Sort products on admin page
route('/cdhrmny/admin/products/sort/(\w+)/(\w+)', 'GET', function ($sortBy, $orderBy) {
    $controller = new ProductController();
    $controller->showAdminProducts($sortBy, $orderBy);
});

// Route for when there's a search term
route('/cdhrmny/admin/products/search/([ \w]+)', 'GET', function ($searchTerm) {
    $controller = new ProductController();
    $controller->search($searchTerm);
});

// Route for when there isn't a search term
route('/cdhrmny/admin/products/search/', 'GET', function () {
    $controller = new ProductController();
    $controller->search('');
}); 

route('/cdhrmny/admin/products/', 'GET', function () {
    $controller = new ProductController();
    $controller->showAdminProducts();
});

//CHECK THIS LATER
route('/cdhrmny/admin/products/', 'POST', function () {
    $controller = new AdminController();
    $controller->handleProduct();
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




