<?php 

require_once "bootstrap.php";

use Controllers\MainViewController;
use Controllers\ProductController;
use Controllers\ArticleController;
use Controllers\SpecialOfferController;
use Controllers\ContactController;
use Controllers\LoginController;
use Controllers\UserController;
use Controllers\AdminController;
use Controllers\CompanyController;
use Controllers\TestController;


//define("BASE_URL", "http://localhost/cdharmony");
require_once "./config/constants.php";

require_once "router.php";

route('/cdharmony/', 'GET', function () {
    $controller = new MainViewController();
    $controller->showMainView();
});

route('/cdharmony/test', 'GET', function () {
    $controller = new TestController();
    $controller->showTest();
});

route('/cdharmony/product/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    $controller->showProductDetails($id);
});

route('/cdharmony/admin/product/show/(\d+)', 'GET', function ($id) {
    $controller = new ProductController();
    //when accessing the product details from the admin panel, the role is set to admin
    $controller->showProductDetails($id,'admin');
});

route('/cdharmony/admin/product/(\d+)', 'POST', function ($id) {
    $controller = new ProductController();
    $controller->showProductDetails($id);
});

route('/cdharmony/admin/product/delete/(\d+)', 'DELETE', function ($id) {
    $controller = new ProductController();
    $controller->deleteProduct($id);
});

route('/cdharmony/admin/product/update/(\d+)', 'PUT', function ($id) {
    $controller = new ProductController();
    $controller->updateProduct($id);
});

route('/cdharmony/admin/product/create/', 'POST', function () {
    $controller = new ProductController();
    $controller->createProduct();
});
route('/cdharmony/admin/product/add', 'GET', function () {
    $controller = new ProductController();
    $controller->showProductForm();
});

route('/cdharmony/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdharmony/admin/article/(\d+)', 'GET', function ($id) {
    $controller = new ArticleController();
    $controller->showArticleDetails($id);
});

route('/cdharmony/admin/article/delete/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->deleteArticle($id);
});

route('/cdharmony/admin/article/update/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->updateArticle($id);
});

route('/cdharmony/admin/article/create/(\d+)', 'POST', function ($id) {
    $controller = new ArticleController();
    $controller->createArticle($id);
});

route('/cdharmony/special-offer/(\d+)', 'GET', function ($id) {
    $controller = new SpecialOfferController();
    $controller->showSpecialOfferDetails($id);
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
    $controller->logoutView();
});

route('/cdharmony/signup/', 'GET', function() {
    $controller = new UserController();
    $controller->signupView();
});

route('/cdharmony/signup/', 'POST', function() {
    $controller = new UserController();
    $controller->createAccount();
});

//not implemented yet
route('/cdharmony/search/', 'GET', function () {
    $controller = new SearchController();
    $controller->searchView();
});

//not implemented yet
route('/cdharmony/search/', 'POST', function () {
    $controller = new SearchController();
    $controller->performSearch();
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

route('/cdharmony/admin/articles/', 'GET', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});

route('/cdharmony/admin/products/', 'GET', function () {
    $controller = new CompanyController();
    $controller->updateCompanyDetails();
});

route('/cdharmony/admin/products/', 'GET', function () {
    $controller = new AdminController();
    $controller->showProducts();
});

route('/cdharmony/admin/products/', 'POST', function () {
    $controller = new AdminController();
    $controller->handleProduct();
});




// Dispatch the router
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$method = $_SERVER['REQUEST_METHOD'];
// If the method is POST, check for method override
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']); // Use the method override
}
dispatch($path, $method);




