<?php 
use Services\SessionManager;

require_once "bootstrap.php";

define("BASE_URL", "http://localhost/cdhrmny");

require_once "router.php";

//include "./config/constants.php";

// will Start the session for all pages
SessionManager::startSession();

route('/cdhrmny/', 'GET', function () {
    require "views/MainView.php";
});

route('/cdhrmny/product/(\d+)', 'GET', function ($id) {
    $controller = new controllers\ProductController();
    $controller->showProductDetails($id);
});

route('/cdhrmny/contact/', 'GET', function() {
    $controller = new controllers\ContactController();
    $controller->contactView();
});

route('/cdhrmny/contact/', 'POST', function() {
    $controller = new controllers\ContactController();
    $controller->contactInput();
});

route('/cdhrmny/login/', 'GET', function() {
    $controller = new controllers\LoginController();
    $controller->loginView();
});
route('/cdhrmny/login/', 'POST', function() {
    $controller = new controllers\UserController();
    $controller->authenticateUser();
});

route('/cdhrmny/signup/', 'GET', function() {
    $controller = new controllers\UserController();
    $controller->signupView();
});

route('/cdhrmny/signup/', 'POST', function() {
    $controller = new controllers\UserController();
    $controller->createAccount();
});

//not implemented yet
route('/cdhrmny/search/', 'GET', function () {
    $controller = new controllers\SearchController();
    $controller->searchView();
});

//not implemented yet
route('/cdhrmny/search/', 'POST', function () {
    $controller = new controllers\SearchController();
    $controller->performSearch();
});

route('/cdhrmny/admin/', 'GET', function () {
    $controller = new controllers\AdminController();
    $controller->adminView();
});

route('/cdhrmny/admin/company/', 'GET', function () {
    $controller = new controllers\CompanyController();
    $controller->showCompanyDetails();
});

route('/cdhrmny/admin/company/', 'POST', function () {
    $controller = new controllers\CompanyController();
    $controller->updateCompanyDetails();
});





route('/cdhrmny/admin/', 'GET', function() {
    $controller = new controllers\AdminController();
    $controller->adminView();
});

route('/cdhrmny/admin/login', 'GET', function() {
      $controller = new controllers\UserController();
      $controller->authenticateUser();
  
});

route('/cdhrmny/admin/login', 'POST', function() {
    // $controller = new controllers\AdminController();
    // $controller->adminView();
    require 'views/admin/login.php';
 });

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$method = $_SERVER['REQUEST_METHOD'];
dispatch($path, $method);
