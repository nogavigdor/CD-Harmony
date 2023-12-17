<?php 

function getCurrentView() {
    $url = $_SERVER['REQUEST_URI'];
    $parts = explode('/', trim($url, '/'));
    $currentView = end($parts);
    return $currentView;
}