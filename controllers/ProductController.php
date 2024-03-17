<?php
namespace Controllers;

use Models\ProductModel;

use Controllers\AdminController;

use Services\ImageHandler;

use Services\SessionManager;

use DataAccess\DBConnector;

use PDO;

class ProductController
{
    private $db;
    private $productModel;
    private $imageHandler;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->imageHandler = new ImageHandler();
        $session = new SessionManager();
        $session->startSession();

        $this->db = DBConnector::getInstance()->connectToDB();
    }

    public function showProductsByTag($tag)
    {
        try {
            $products = $this->productModel->getProductsByTag($tag);

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
            $products = $this->productModel->getRecentReleases(); // Call the method on the instance

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
            //getting an array of variants (new and old) and the product details
            $product = $this->productModel->getProductDetails($id);
            //route for admin
            if ($role == 'admin') {
                if (AdminController::authorizeAdmin()) {
                    // Load the view to display the product details if the user is an admin
                    include 'views/admin/product_details.php';
                } else {
                    // Redirect to the login page in case the user is not logged as admin
                    header('Location:' . BASE_URL . '/admin-login');
                    exit();
                }
                //route for customer
            } else {
                // Load the view to display the product details
                include 'views/product_details.php';
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //Getting the product variantd details (in this case, the new or used variant of a cd product)
    public function getProductVariantDetails($id)
    {
        try {
            $productVariantsDetails = $this->productModel->getProductVariantsDetails($id);
            return $productVariantsDetails;
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //presentation for the admin products page which can be sorted
    public function getAllVariants($sortBy = null, $orderBy = null)
    {
        try {
            if (SessionManager::isAdmin()) {
                return $this->productModel->getAllVariants($sortBy = 'variant_creation_date', $orderBy); // Call the method on the instance
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //THE PARAMETER IS THE PRODUCT VARIANT ID
    //Getting the product variants details
    public function getProductVariantsDetails($id)
    {
        try {
            $productVariantsDetails = $this->productModel->getProductVariantsDetails($id);
            return $productVariantsDetails;
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showAdminProducts($sortBy = null, $orderedBy = null)
    {
        try {
            if (!SessionManager::isAdmin()) {
                // Redirect to the homepage in case the user is not logged as admin
                header('Location:' . BASE_URL);
                exit();
            }
            //in case of loading the admin products the first time, both parameters will be null
            $productsList = $this->productModel->getAllVariants($sortBy, $orderedBy);

            include 'views/admin/products.php'; // Include the view file
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showProductForm()
    {
        try {
            if (AdminController::authorizeAdmin()) {
                // Load the view to display the product form
                include 'views/admin/product_form.php';
            } else {
                // Redirect to the login page in case the user is not logged as admin
                header('Location:' . BASE_URL . '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //adds a tag to a product
    public function addTagToProduct($newProductId, $tag)
    {
        //Checks if the tag already exists
        $tagId = $this->productModel->getTagIdByTitle($tag);

        echo "if its a new tag then the Tagid: $tagId<br> ";

        if (!$tagId) {
            // If the tag doesn't exist, insert it and get the new tag id
            $tagId = $this->productModel->insertTag($tag);
        }
        echo "after the insertion of the new tag, the tagid is: $tagId<br>";
        echo "the new product id is: $newProductId<br>";
        //Associate the tag id with the new product id
        //returns a boolean
        $success = $this->productModel->addProductTag($newProductId, $tagId);
       return $success;
    }
/*
    //adds a tag to a product
    private function addProductTag($productId, $tagId)
    {
        // Insert into the junction table (e.g., product_tags)
        $sql = 'INSERT INTO product_tags (product_id, tag_id) VALUES (:productId, :tagId)';
        $query = $this->db->prepare($sql);
        $query->bindParam(':productId', $productId);
        $query->bindParam(':tagId', $tagId);
        $query->execute();
    }
*/
    //Adds a new product (cd) to the database
    public function addProduct()
    {
        
       try {
          //  $this->db->beginTransaction();
            //verifying if the user is logged as admin
            if (!SessionManager::isAdmin()) {
                // Redirect to the homepage in case the user is not logged as admin {
                header('Location:' . BASE_URL . '/admin-login');
                exit();
            }

            // Validate the CSRF token on form submission - to ensure that only by authorized admin users
            if (SessionManager::validateCSRFToken($_POST['csrf_token'])) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    //initializing an array to store the errors
                    $errType = [];
    
                    /*****Retrieve values from the form *****/

                    $productTitle = htmlspecialchars(trim($_POST['productTitle']));
                    $artisTitle = htmlspecialchars(trim($_POST['artistTitle']));
                    $description = htmlspecialchars(trim($_POST['productDescription']));
                    $condition = htmlspecialchars(trim($_POST['productCondition']));
                    //converting the number to decimal
                    $price = floatval(htmlspecialchars(trim($_POST['price'])));
                    $quantityInStock = htmlspecialchars(trim($_POST['quantityInStock']));
                    $creationDate = date('Y-m-d h:i:s');
                    $tags = htmlspecialchars(trim($_POST['tags']));
                    $releaseDate = trim($_POST['releaseDate']);
                    $file = $_FILES['image'];

                    /********  Validating form values **********/

                    // Validate quantityInStock
                    if (!is_numeric($quantityInStock) || $quantityInStock < 0) {
                        $quantityInStock = 'Quantity in Stock must be a non-negative integer.';
                        $errType['quantityInStock'] = $quantityInStock;
                    }

                
                    //if there are errors, redirect to the add product form and show error message
                    SessionManager::setSessionVariable('errors_output', $errType);
                    if (count($errType) > 0) {
                        header('Location:' . BASE_URL . '/admin/product/add');
                        exit();
                    }

                    $productModel = new ProductModel();

                    //creating the product prototype and getting the new product id
                    $newProductId = $productModel->addProduct($productTitle, $description, $creationDate);

                    //if a new product prototype was created successfully, continue
                    if ($newProductId) {
                        //creating a tags array from the tags string
                        $arr_tags = [];
                        //creating an array of tags
                        $arr_tags = explode(',', $tags);

                        //creates the products id tags
                        //checks if the tags (as strings) exists in the tags table
                        //and inserts those who don't exist
                        //returns an array of tag ids that will be associated with the product

                        $id_tags = [];
                        foreach ($arr_tags as $tag) {
                            //gets the tag id
                            $tagId = $this->productModel->getTagIdByTitle($tag);
                            //if the tag doesn't exist, insert it
                            if (!$tagId) {
                                //inserts the tag and returns the tag id
                                $tagId = $this->productModel->insertTag($tag);
                            }
                            //pushes the tag id to the array
                            array_push($id_tags, $tagId);
                        }

                        //updating the the tags for tags and products table (many to many)
                        foreach ($id_tags as $tagId) {
                            echo $newProductId . '---' . $tagId;
                            var_dump($id_tags);
                            var_dump($arr_tags);
                         //   exit();
                            //calling a class method to add a tag to a product
                            //returns a boolean
                            $success = $this->addTagToProduct($newProductId, $tagId);
                        }

                        //product and tag were added successfully
                        if ($success) {
                            //checks if the artist already exists in the database
                            echo "the artist title is: $artistTitle<br>";
                            $artistId = $productModel->checkArtist($newProductId, $artistTitle);
                            //if the artist doesn't exist, insert it
                            if (!$artistId) {
                                //inserts the artist and returns the artist id
                                $artistId = $productModel->insertArtist($artistTitle);
                            }

                            //inserts the cd product - no need for the cd is so just getting a success message
                            /* parameter returned: bolean */
                            $cdInserted = $productModel->insertCD($newProductId, $releaseDate, $artistId);

                            //if the cd was inserted successfully, continue
                            if ($cdInserted) {
                                //inserts the product variant and returns the product variant id
                                /* parameter returned: int */
                                $new_products_var_added = $this->productModel->insertProductVariant($newProductId, $condition, $price, $quantityInStock);
                            }

                            //if the product variant was inserted successfully, continue
                            if ($new_products_var_added) {
                                //image upload code
                                $image = $this->imageHandler->handleImageUpload($file, './src/assets/images/albums/');

                                $msg = [];
                                $msg = $this->imageHandler->getMessages();
                                //print_r($msg);

                                //exit;
                                if (count($msg) > 0) {
                                    $errType['image'] = $msg;
                                    SessionManager::setSessionVariable('errors_output', $errType);
                                    header('Location:' . BASE_URL . '/admin/product/edit/' . $new_products_var_added);
                                    exit();
                                } else {
                                    $image_name = $image;
                                    $main_image = 1;
                                    $image_added = $productModel->insertImage($newProductId, $image_name, $main_image);
                                }

                                //sets the success message in the session variable
                                SessionManager::setSessionVariable('success_message', 'Product added successfully');
                            }
                        }
                    }
                }
            }
          //  $this->db->commit();
        } catch (\PDOException $ex) {
         //   $this->db->rollBack();
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //shows the product form with the product details for editing
    public function showEditProductForm($id)
    {
        try {
            if (SessionManager::isAdmin()) {
                // Get the product details from the database
                $productDetails = $this->productModel->getProductDetails($id);
                // Load the view to display the product form
                include 'views/admin/edit-product-form.php';
            } else {
                // Redirect to the login page in case the user is not logged as admin
                header('Location:' . BASE_URL . '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //updates the product (product variant) details
    public function updateProduct()
    {
        try {
            if (SessionManager::isAdmin()) {
                // Validate the CSRF token on form submission - to ensure that only by authorized admin users
                if (SessionManager::validateCSRFToken($_POST['csrf_token'])) {
                    // CSRF token is valid
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Retrieve values from the form
                        $productId = htmlspecialchars(trim($_POST['product_id']));
                        $productVariantId = htmlspecialchars(trim($_POST['productVariantId']));
                        $productTitle = htmlspecialchars(trim($_POST['productTitle']));
                        $description = htmlspecialchars(trim($_POST['productDescription']));
                        $price = htmlspecialchars(trim($_POST['price']));
                        $stock = htmlspecialchars(trim($_POST['QuantatyInStock']));
                        $image = htmlspecialchars(trim($_POST['image']));
                        $tags = htmlspecialchars(trim($_POST['tags']));
                        $releaseDate = htmlspecialchars(trim($_POST['release_date']));
                        $artistTitle = htmlspecialchars(trim($_POST['artistTitle']));

                        $productModel = new ProductModel();
                        $success = $productModel->updateProduct($productVariantId, $productTitle, $artistTitle, $description, $price, $stock, $image, $tags, $releaseDate);
                        if ($success) {
                            //sets the success message in the session variable
                            SessionManager::setSessionVariable('success_message', 'Product updated successfully');
                        }
                    }
                }
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    //deletes the product (product variant)
    public function deleteProduct()
    {
        try {
            if (SessionManager::isAdmin()) {
                // Validate the CSRF token on form submission - to ensure that only by authorized admin users
                if (SessionManager::validateCSRFToken($_POST['csrf_token'])) {
                    // CSRF token is valid
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Retrieve values from the form
                        $productId = htmlspecialchars(trim($_POST['product_id']));
                        $productVariantId = htmlspecialchars(trim($_POST['productVariantId']));

                        $productModel = new ProductModel();
                        $success = $productModel->deleteProduct($productVariantId);
                        if ($success) {
                            //sets the success message in the session variable
                            SessionManager::setSessionVariable('success_message', 'Product deleted successfully');
                        }
                    }
                }
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showAddProductForm()
    {
        try {
            if (SessionManager::isAdmin()) {
                // Load the view to display the product form
                include 'views/admin/add-product.php';
            } else {
                // Redirect to the login page in case the user is not logged as admin
                header('Location:' . BASE_URL . '/admin-login');
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function search($searchTerm)
    {
        try {
            $productsList = $this->productModel->search($searchTerm);
            // Check if the products list is empty
            if (empty($productsList)) {
                // Load a view that displays a 'no search results' message
                $productsList = $this->productModel->getAllVariants();
                SessionManager::setSessionVariable('error_message', 'No results found');
            }
            // Load the view to display the products
            include 'views/admin/products.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
