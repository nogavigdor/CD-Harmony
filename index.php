<?php 

require_once "bootstrap.php";

//define("BASE_URL", "http://localhost/cdharmony");
require_once "./config/constants.php";

require_once "router.php";

route('/cdharmony/', 'GET', function () {
    $controller = new Controllers\MainViewController();
    $controller->showMainView();
});

route('/cdharmony/test', 'GET', function () {
    $controller = new Controllers\TestController();
    $controller->showTest();
});

route('/cdharmony/product/(\d+)', 'GET', function ($id) {
    $controller = new Controllers\ProductController();
    $controller->showProductDetails($id);
});

route('/cdharmony/article/(\d+)', 'GET', function ($id) {
    $controller = new Controllers\ArticleController();
    $controller->showArticleDetails($id);
});


route('/cdharmony/contact/', 'GET', function() {
    $controller = new Controllers\ContactController();
    $controller->contactView();
});

route('/cdharmony/contact/', 'POST', function() {
    $controller = new Controllers\ContactController();
    $controller->contactInput();
});

route('/cdharmony/login/', 'GET', function() {
    $controller = new Controllers\LoginController();
    $controller->loginView();
});
route('/cdharmony/login/', 'POST', function() {
    $controller = new Controllers\UserController();
    $controller->authenticateUser();
});

route('/cdharmony/logout/', 'GET', function() {
    $controller = new Controllers\LoginController();
    $controller->logoutView();
});

route('/cdharmony/signup/', 'GET', function() {
    $controller = new Controllers\UserController();
    $controller->signupView();
});

route('/cdharmony/signup/', 'POST', function() {
    $controller = new Controllers\UserController();
    $controller->createAccount();
});

//not implemented yet
route('/cdharmony/search/', 'GET', function () {
    $controller = new Controllers\SearchController();
    $controller->searchView();
});

//not implemented yet
route('/cdharmony/search/', 'POST', function () {
    $controller = new Controllers\SearchController();
    $controller->performSearch();
});

route('/cdharmony/admin/', 'GET', function () {
    $controller = new Controllers\AdminController();
    $controller->adminView();
});

route('/cdharmony/admin/product/', 'GET', function () {
    $controller = new Controllers\ProductController();
    $controller->showProductList();
});

route('/cdharmony/admin-login', 'GET', function() {
    $controller = new Controllers\AdminController();
    $controller->adminLoginView();

});

route('/cdharmony/admin-login', 'POST', function() {
  $controller = new controllers\AdminController();
  $controller->adminLogin();
 
});


route('/cdharmony/admin/company/', 'GET', function () {
    $controller = new Controllers\CompanyController();
    $controller->showCompanyDetails();
});

route('/cdharmony/admin/company/', 'POST', function () {
    $controller = new Controllers\CompanyController();
    $controller->updateCompanyDetails();
});

route('/cdharmony/admin/articles/', 'GET', function () {
    $controller = new Controllers\CompanyController();
    $controller->updateCompanyDetails();
});

route('/cdharmony/admin/articles/', 'POST', function () {
    $controller = new Controllers\CompanyController();
    $controller->updateCompanyDetails();
});


$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$method = $_SERVER['REQUEST_METHOD'];
dispatch($path, $method);




