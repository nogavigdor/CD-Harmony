<?php
namespace Controllers;

use Models\ProductModel;

use Controllers\AdminController;

class ProductController 
{
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }
    

    public function showProductsByTag($tag)
    {
        try {
            $products= $this->productModel->getProductsByTag($tag);
      
    
          // var_dump($products);
        //    print_r($products);
            // Load the view to display the products
            include 'views/products_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }


    public function showRecentReleases()
    {
        try {
 
            $products=$this->productModel->getRecentReleases(); // Call the method on the instance

            // Load the view to display the "New Releases" section
            include 'views/products_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

	public function showProductDetails($id)
    {
        try {
          
            $product=$this->productModel->getProductDetails($id); // Call the method on the instance
            //the request came from an admin route
            if ($role == 'admin') {
                if (AdminController::authorizeAdmin()) {
                    // Load the view to display the product details if the user is an admin
                    include 'views/admin/product_details.php';
                } else {
                    // Redirect to the login page since the user is not an admin
                    header('Location:'. BASE_URL. '/admin-login');
                    exit();
                }
            //the request came from a customer route
            } else {
                // Load the view to display the product details
                include 'views/product_details.php';
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function getProductsList()
    {
        try {
            if(AdminController::authorizeAdmin()) {
                return $this->productModel->getProductsList(); // Call the method on the instance

           
            
                
            }
            else{
                // Redirect to the login page in case the user is not logged as admin
                header('Location:'. BASE_URL. '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showProductForm()
    {
        try {
            if(AdminController::authorizeAdmin()) {
                // Load the view to display the product form
                include 'views/admin/product_form.php';
            }
            else{
                // Redirect to the login page in case the user is not logged as admin
                header('Location:'. BASE_URL. '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
