<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "bootstrap.php";

define("BASE_URL", "http://localhost/cdharmony");

require_once "router.php";


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
    $controller = new controllers\UserController();
    $controller->authenticateUser();
});

route('/cdharmony/signup/', 'GET', function() {
    $controller = new controllers\UserController();
    $controller->signupView();
});

route('/cdharmony/signup/', 'POST', function() {
    $controller = new controllers\UserController();
    $controller->createAccount();
});

//not implemented yet
route('/cdharmony/search/', 'GET', function () {
    $controller = new controllers\SearchController();
    $controller->searchView();
});

//not implemented yet
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





route('/cdharmony/admin/', 'GET', function() {
    $controller = new controllers\AdminController();
    $controller->showAdmin();
});

route('/cdharmony/admin/login', 'GET', function() {
   // $controller = new controllers\AdminController();
   // $controller->showAdmin();
   require 'views/admin/login.php';
});

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$method = $_SERVER['REQUEST_METHOD'];
dispatch($path, $method);
