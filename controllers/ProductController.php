<?php
namespace controllers;

use models\ProductModel;

class ProductController 
{
    public function __construct() {
    }

    public function showProductsByTag($tag) {
        try {
            $productModel = new ProductModel(); // Create an instance of ProductModel
            $products = $productModel->getProductsByTag($tag); // Call the method on the instance

            require 'views/products_section.php'; // Display the view
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showRecentReleases()
    {
        try {
			$tag='recent';
            $productModel = new ProductModel(); // Create an instance of ProductModel
            $products = $productModel->getRecentReleases(); // Call the method on the instance

            // Load the view to display the "New Releases" section
            include_once 'views/products_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
