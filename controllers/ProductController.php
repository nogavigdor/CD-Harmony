<?php
namespace Controllers;

use Models\ProductModel;

use Controllers\AdminController;

use Services\SessionManager;

class ProductController 
{
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $session = new SessionManager();
        $session->startSession();
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

    //includes the produt details page for admin or customer - depending on the role
	public function showProductDetails($id, $role = 'none')
    {
        try {
            //route for admin
            if ($role == 'admin') {
                if (AdminController::authorizeAdmin()) {
                    $product = $this->productModel->getProductDetails($id);
                    // Load the view to display the product details if the user is an admin
                    include 'views/admin/product_details.php';
                } else {
                    // Redirect to the login page in case the user is not logged as admin
                    header('Location:'. BASE_URL. '/admin-login');
                    exit();
                }
            //route for customer
            } else {
                //getting an array of variants (new and old) and the product details
                $product = $this->productModel->getProductDetails($id);
                // Load the view to display the product details
                include 'views/product_details.php';
            }
          
          
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //Getting the product variantd details (in this case, the new and old variant of a cd product)
    public function getProductVariantDetails($id)
    {
        try {
            $productVariantsDetails = $this->productModel->getProductVariantsDetails($id);
            return $productVariantsDetails;
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function getProductsList()
    {
        try {
            if(AdminController::authorizeAdmin()) {
                return $this->productModel->getAllProducts(); // Call the method on the instance

           
            
                
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

    public function editProduct($id){
        try {
          
            if(SessionManager::isAdmin()) {
                // Get the product details from the database
                $productDetails = $this->productModel->getProductDetails($id);
                // Load the view to display the product form
                include 'views/admin/edit-product-form.php';
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
