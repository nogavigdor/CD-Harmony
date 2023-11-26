<?php
use Services\SessionManager;

require_once "bootstrap.php";

//define("BASE_URL", "http://localhost/cdharmony");

require_once "router.php";

include "./config/constants.php";

// will Start the session for all pages
SessionManager::startSession();

route(BASE_URL . '/', 'GET', function () {
    require "views/MainView.php";
});

route(BASE_URL . '/product/(\d+)', 'GET', function ($id) {
    $controller = new controllers\ProductController();
    $controller->showProductDetails($id);
});

route(BASE_URL . '/contact/', 'GET', function() {
    $controller = new controllers\ContactController();
    $controller->contactView();
});

route(BASE_URL . '/contact/', 'POST', function() {
    $controller = new controllers\ContactController();
    $controller->contactInput();
});

route(BASE_URL . '/login/', 'GET', function() {
    $controller = new controllers\LoginController();
    $controller->loginView();
});
route(BASE_URL . '/login/', 'POST', function() {
    $controller = new controllers\UserController();
    $controller->authenticateUser();
});

route(BASE_URL . '/signup/', 'GET', function() {
    $controller = new controllers\UserController();
    $controller->signupView();
});

route(BASE_URL . '/signup/', 'POST', function() {
    $controller = new controllers\UserController();
    $controller->createAccount();
});

//not implemented yet
route(BASE_URL . '/search/', 'GET', function () {
    $controller = new controllers\SearchController();
    $controller->searchView();
});

//not implemented yet
route(BASE_URL . '/search/', 'POST', function () {
    $controller = new controllers\SearchController();
    $controller->performSearch();
});

route(BASE_URL . '/admin/', 'GET', function () {
    $controller = new controllers\AdminController();
    $controller->adminView();
});

route(BASE_URL . '/admin/company/', 'GET', function () {
    $controller = new controllers\CompanyController();
    $controller->showCompanyDetails();
});

route(BASE_URL . '/admin/company/', 'POST', function () {
    $controller = new controllers\CompanyController();
    $controller->updateCompanyDetails();
});

route(BASE_URL . '/admin/', 'GET', function() {
    $controller = new controllers\AdminController();
    $controller->adminView();
});

route(BASE_URL . '/admin/login', 'GET', function() {
    $controller = new controllers\UserController();
    $controller->authenticateUser();
});

route(BASE_URL . '/admin/login', 'POST', function() {
    require 'views/admin/login.php';
});

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); // Extract the path
$method = $_SERVER['REQUEST_METHOD'];
dispatch($path, $method);
