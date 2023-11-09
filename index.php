<?php 
define("BASE_URL", "http://localhost/cdharmony");

require_once "router.php";
require_once "bootstrap.php";

route('/cdharmony/', 'GET', function () {
    require "views/MainView.php";
});

route('/cdharmony/product/(\d+)', 'GET', function ($id) {
    $controller = new controllers\ProductController();
    $controller->showProductDetails($id);
});

route('/cdharmony/contact/', 'GET', function() {
    $controller = new controllers\ContactController();
    $controller->contactView();
});

route('/cdharmony/contact/', 'POST', function() {
    $controller = new controllers\ContactController();
    $controller->contactInput();
});

route('/cdharmony/login/', 'GET', function() {
    $controller = new controllers\LoginController();
    $controller->loginView();
});
route('/cdharmony/login/', 'POST', function() {
    $controller = new controllers\LoginController();
    $controller->loginCheck();
});

route('/cdharmony/signup/', 'GET', function() {
    $controller = new controllers\SignupController();
    $controller->signupView();
});

route('/cdharmony/signup/', 'POST', function() {
    $controller = new controllers\SignupController();
    $controller->signupCreation();
});

route('/cdharmony/search/', 'GET', function () {
    $controller = new controllers\SearchController();
    $controller->searchView();
});

route('/cdharmony/search/', 'POST', function () {
    $controller = new controllers\SearchController();
    $controller->performSearch();
});

route('/cdharmony/admin/company/', 'GET', function () {
    $controller = new controllers\CompanyController();
    $controller->showCompanyDetails();
});

route('/cdharmony/admin/company/', 'POST', function () {
    $controller = new controllers\CompanyController();
    $controller->updateCompanyDetails();
});
route('/cdharmony/test/', 'GET', function() {

    require "views/test.php";
});

$action = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$method = $_SERVER['REQUEST_METHOD'];
dispatch($action, $method);
