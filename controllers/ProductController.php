<?php
namespace Controllers;

use Models\ProductModel;

use Models\SpecialOfferModel;

use Controllers\AdminController;

use Services\ImageHandler;

use Services\SessionManager;

use DataAccess\DBConnector;

use Services\Validator;
use Controllers\LoginController;

use PDO;
use Stripe\BillingPortal\Session;

class ProductController
{
    private $db;
    private $productModel;
    private $specialOfferModel;
    private $imageHandler;
    private $sessionManager;
    private $validator;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->specialOfferModel = new SpecialOfferModel();
        $this->imageHandler = new ImageHandler();
        $this->validator = new Validator();
        $this->sessionManager = new SessionManager();
        $this->sessionManager->startSession();

        $this->db = DBConnector::getInstance()->connectToDB();
    }

    public function showProductsByTag($tag, $offset = 0, $limit = 4)
    {
        try {
            $products = $this->productModel->getProductsByTag($tag, $offset, $limit); // Call the method on the instance
           
            // var_dump($products);
            //    print_r($products);
            if (isset($products) && !empty($products)) {
                // Load the view to display the products
                include 'views/products_section.php';
            }
            else {
                echo 'no products';
            }
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

    //includes the product details page for admin or customer - depending on the role
    public function showProductDetails($id, $role = 'none')
    {
        try {
            //getting an array of variants (new and old) and the product details
            $product = $this->productModel->getProductDetails($id);
            //route for admin
            if ($role == 'admin') {
                if (AdminController::authorizeAdmin()) {

                    $controller = new ProductController();
                    $productVars = $controller->getProductVariantsDetails($product->product_id);
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

    //Getting the product variant details (in this case, the new or used variant of a cd product)
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
    public function getAllVariants($sortBy = null, $orderBy = 'DESC')
    {
        try {
            if (LoginController::isAdmin()) {
                return $this->productModel->getAllVariants($sortBy = 'variant_creation_date', $orderBy); // Call the method on the instance
            }else {
                // Redirect to the login page in case the user is not logged as admin
                SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
                header('Location:' . BASE_URL . '/admin-login');
                exit();
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
public function showAdminProducts($sortBy = 'product_variant_id', $orderedBy = 'DESC')
    {
        try {
            if (!LoginController::isAdmin()) {
                // Redirect to the homepage in case the user is not logged as admin
                header('Location:' . BASE_URL);
                exit();
            }
            //Loading all the product variants which are not marked as deleted 
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


public function showAddProductForm()
{
    try {
        if (LoginController::isAdmin()) {
            // Load the view to display the product form
            include 'views/admin/add-product.php';
        } else {
             // User is not logged in as an admin, redirect to the home page
            SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
            header('Location:' . BASE_URL . '/admin-login');
            exit();
        }
    } catch (\PDOException $ex) {
        error_log('PDO Exception: ' . $ex->getMessage());
    }
}

    //Adds a new product (cd) to the database
    public function addProduct()
    {   
       try {
            $this->db->beginTransaction();
            //verifying if the user is logged as admin
            if (!LoginController::isAdmin()) {
                throw new \Exception('Unauthorized access');
            }

            // Validate the CSRF token on form submission - to ensure that only by authorized admin users
            if (!(SessionManager::validateCSRFToken($_POST['csrf_token']))) 
            echo json_encode("An internal server error occurred. Please try again later.");
            
            if (!($_SERVER['REQUEST_METHOD'] === 'POST')) 
                throw new \Exception('Invalid request method');
                    //initializing an array to store the errors
                    $errType = [];
    
                    /*****Retrieve values from the form *****/
                    if (empty($_POST['productTitle'])) {
                        $errType['productTitle'] = 'Product Title is required';
                    }
                    $productTitle = htmlspecialchars(trim($_POST['productTitle']));
                    if (empty($_POST['artistTitle'])) {
                        $errType['artistTitle'] = 'Artist Title is required';
                    }
                    $artistTitle = htmlspecialchars(trim($_POST['artistTitle']));
                
                    $description = htmlspecialchars(trim($_POST['productDescription']));
                   
                    if (empty($_POST['productCondition'])) {
                        $errType['productCondition'] = 'Product Condition is required';
                    }
                    $condition = htmlspecialchars(trim($_POST['productCondition']));

                    $price = htmlspecialchars(trim($_POST['price']));
                    if (!is_numeric($price)) {
                        $errType['price'] = 'Price must be a number';
                    }
                    //converting the number to decimal
                    $price = floatval(htmlspecialchars(trim($_POST['price'])));
                    $quantityInStock = htmlspecialchars(trim($_POST['quantityInStock']));
                    if (!is_numeric($quantityInStock) || $quantityInStock < 0) {
                        $quantityInStock = 'Quantity in Stock must be a non-negative integer.';
                        $errType['quantityInStock'] = $quantityInStock;
                    }
                    $creationDate = date('Y-m-d h:i:s');
                    
                    if (empty($_POST['releaseDate'])) {
                        $errType['releaseDate'] = 'Release Date is required';
                    }
                    $releaseDate = trim($_POST['releaseDate']);
                   
                    $file = isset($_FILES['image']) ? $_FILES['image'] : null;

                    if ($file && $file['name']) {
                        // Handle image upload
                        $image = $this->imageHandler->handleImageUpload($file, './src/assets/images/albums/');

                        // Handle potential errors from image upload
                        if ($image === false) {
                            $imageErrorMsg = $this->imageHandler->getErrorMessages();
                            $imageErrorMsgString = implode(' and ', $imageErrorMsg);
                            $errType['image'] = $imageErrorMsgString;
                        }
                    } else {
                        // Handle case when no image is uploaded
                         $errType['image'] = 'No image uploaded';
                    }

                    //if there are errors, return them as JSON
                    if (!empty($errType)) {
                        http_response_code(400); // Set HTTP status code to indicate a client error
                        echo json_encode($errType);
                        exit(); // Terminate script execution
                    }
                    $productModel = new ProductModel();

                    //creating the product prototype and getting the new product id
                    $newProductId = $productModel->addProduct($productTitle, $description, $creationDate);

                    if (!($newProductId)) 
                        throw new \Exception('There was a problem updating the product table, please try again');

                    //if a new product prototype was created successfully, continue
                    $tags = htmlspecialchars(trim($_POST['tags']));
                    //creating a tags array from the tags string
                    $arr_tags = [];
                    
                    //creating an array of tags without commas and spaces and without duplicates
                    $arr_tags = tagsToArray($tags);
                    
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
                    if(!(empty($id_tags))){
                             //updating the the tags for tags and products table (many to many)
                        foreach ($id_tags as $tagId) {
                        // echo $newProductId . '---' . $tagId;
                        //   var_dump($id_tags);
                        //   var_dump($arr_tags);
                        //   exit();
                        //add a tag to a product
                        //returns a boolean
                            $tagsSuccess = $this->productModel->addProductTag($newProductId, $tagId);
                            }
    
                            //product and tag were added successfully
                            if (!($tagsSuccess))
                             throw new \Exception('There was a problem updating the product_tags table, please try again');
    
                    }
               
                            //checks if the artist already exists in the database
                           // echo "the artist title is: $artistTitle<br>";
                            $artistId = $productModel->checkArtist($artistTitle);
                            //if the artist doesn't exist, insert it
                            if (!$artistId) {
                                //inserts the artist and returns the artist id
                                $artistId = $productModel->insertArtist($artistTitle);
                            }
                            else
                            {
                                //get the artist id
                                $artistId = $productModel->getArtistId($artistTitle);
                            }

                            //inserts the cd product - no need for the cd is so just getting a success message
                            /* parameter returned: boolean */
                           // echo "$newProductId, $releaseDate, $artistId<br>";

                            $cdInserted = $productModel->insertCd($releaseDate, $artistId, $newProductId);
                            if (!$cdInserted) {
                                throw new \Exception('There was a problem updating the cd table, please try again');
                            }

                            //if the cd was inserted successfully, continue adding the product image

                               
                                
                                   // Handle image upload
                                $image = $this->imageHandler->handleImageUpload($file, './src/assets/images/albums/');
                                $image_name = $image;
                                $main_image = 1;
                                $image_added = $productModel->insertImage($newProductId, $image_name, $main_image);
                                //   echo('the new image id is: ' . $image_added . '<br>');
                                //throws an error if the image was not updated
                                if (!$image_added)
                                throw new \Exception('There was a problem updating the image table, please try again');
                                

                        
                                //inserts the product variant and returns the product variant id
                                /* parameter returned: int */
                                $new_products_var_added1 = $this->productModel->insertProductVariant($newProductId, $condition, $price, $quantityInStock);

                                if(!$new_products_var_added1) {
                                    throw new \Exception('There was a problem updating the product_variant table, please try again');
                                }
                           //     echo('the new product variant id is: ' . $new_products_var_added . '<br>');
                               //the next step will be to add a default product variant with the opposite condition
                            $condition_id = 2;
                              if ($condition==2) {
                                    $condition_id = 1;
                             
                            }
                            //if the product variant was inserted successfully, an automatic new/used variant will be added
                            //with the same product id and with the opposite condition - with a default value of 0 for price and quantity in stock
                            $new_products_var_added2 = $this->productModel->insertProductVariant($newProductId, $condition_id, 0, 0);
                            if(!$new_products_var_added2) {
                                throw new \Exception('There was a problem updating the product_variant table, please try again');
                            }
                            //if the product variant was inserted successfully, continue to confirm the product was added successfully
                    
                            
                                //sets the success message in the session variable
                                SessionManager::setSessionVariable('success_message', 'Product added successfully');
                                $response = ['status' => 'success', 'message' => 'Product added successfully'];
                               
                                $this->db->commit();
                                echo json_encode($response);


        } catch (\PDOException $ex) {
          $this->db->rollBack();
             // Handle exceptions
        $error_message = $ex->getMessage();
        $response = ['status' => 'error', 'message' => $error_message];
        echo json_encode($response);
       
        }
}

    //shows the product form with the product details for editing
    public function showEditProductForm($id)
    {
        try {
            if (LoginController::isAdmin()) {
                // Get the product details from the database
              
                $variantDetails = $this->productModel->getVariantDetails($id);

                // Load the view to display the product form
                include 'views/admin/edit-product.php';
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
            $this->db->beginTransaction();
            //verifying if the user is logged as admin
            if (!LoginController::isAdmin()) {
                throw new \Exception('Unauthorized access');
            }

            // Validate the CSRF token on form submission - to ensure that only by authorized admin users
            if (!(SessionManager::validateCSRFToken($_POST['csrf_token']))) 
            echo json_encode("An internal server error occurred. Please try again later.");
            
            if (!($_SERVER['REQUEST_METHOD'] === 'POST')) 
                throw new \Exception('Invalid request method');
                    //initializing an array to store the errors
                    $errType = [];
    
                    /*****Retrieve values from the form *****/
                    $productId = htmlspecialchars(trim($_POST['productId']));
                    $variantId = htmlspecialchars(trim($_POST['variantId']  ));
                    $productTitle = htmlspecialchars(trim($_POST['productTitle']));
                    $artistTitle = htmlspecialchars(trim($_POST['artistTitle']));
                    $description = htmlspecialchars(trim($_POST['productDescription']));
                    $price = htmlspecialchars(trim($_POST['price']));
                    if (!is_numeric($price)) {
                        $errType['price'] = 'Price must be a number';
                    }
                    //converting the number to decimal
                    $price = floatval(htmlspecialchars(trim($_POST['price'])));
                    $quantityInStock = htmlspecialchars(trim($_POST['quantityInStock']));
                    if (!is_numeric($quantityInStock) || $quantityInStock < 0) {
                        $quantityInStock = 'Quantity in Stock must be a non-negative integer.';
                        $errType['quantityInStock'] = $quantityInStock;
                    }
                    $tags = htmlspecialchars(trim($_POST['tags']));
                    $releaseDate = trim($_POST['releaseDate']);

                    $file = isset($_FILES['image']) ? $_FILES['image'] : null;

                    $image_to_upload_exist = true;
                    if ($file && $file['name']) {
                        // Handle image upload
                        $image = $this->imageHandler->handleImageUpload($file, './src/assets/images/albums/');
                    
                        // Handle potential errors from image upload
                        if ($image === false) {
                            $imageErrorMsg = $this->imageHandler->getErrorMessages();
                            $imageErrorMsgString = implode(' and ', $imageErrorMsg);
                            $errType['image'] = $imageErrorMsgString;
                        }
                        //no image was uploaded
                    } else {
                         $image_to_upload_exist = false;
                    }

                    
                    //if there are errors, return them as JSON
                    if (!empty($errType)) {
                        http_response_code(400); // Set HTTP status code to indicate a client error
                        echo json_encode($errType);
                        exit(); // Terminate script execution
                    }
                    $productModel = new ProductModel();

                    //updating the products table and getting a boolean if successful
                    $productUpdate = $productModel->updateProduct($productId, $productTitle, $description);
                  

                    if (!($productUpdate)) 
                        throw new \Exception('There was a problem updating the product table, please try again');

                    //if a new product prototype was created successfully, continue
                
                    //creating a tags array from the tags string
                    $form_tags = [];
                    
                    //creating an array of tags without commas and spaces and without duplicates
                    $form_tags = tagsToArray($tags);

                     

                   //get current tags for products from the products_tags table
                    $currentTags = $this->productModel->getProductTags($productId);

                    // Initialize an empty array to store the titles
                    $currentTagTitles = [];

                    // Iterate over each associative array in $currentTags
                    foreach ($currentTags as $tag) {
                        // Extract the value associated with the 'title' key from each associative array
                        $title = $tag['title'];
                        // Add the extracted title to the $currentTagTitles array
                        $currentTagTitles[] = $title;
                    }

                    // Tags to delete (tags in $currentTags but not in $id_tags)
                    $tags_to_delete = array_diff($currentTagTitles, $form_tags);

                    // Tags to update (tags in $id_tags but not in $currentTags)
                    $tags_to_update = array_diff($form_tags, $currentTagTitles);
  
                      //checks if the tags (as strings) exists in the tags table
                      //and inserts those who don't exist
                      //returns an array of tag ids that will be associated with the product
  
                        $tags_id_to_update = [];
                      foreach ($tags_to_update as $tag) {
                          //gets the tag id
                          $tagId = $this->productModel->getTagIdByTitle($tag);
                          //if the tag doesn't exist, insert it
                          if (!$tagId) {
                              //inserts the tag and returns the tag id
                              $tagId = $this->productModel->insertTag($tag);
                          }
                          //pushes the tag id to the array
                          array_push($tags_id_to_update, $tagId);
                          }

                 
  
                      //updating the the tags for tags and products table (many to many)
                      foreach ($tags_id_to_update as $tagId) {

                        //update the tags for an existing product
                          $tagsSuccess = $this->productModel->addProductTag($productId, $tagId);

                        //product and tag were added successfully
                        if (!($tagsSuccess))
                        throw new \Exception('There was a problem updating the product_tags table, please try again');

                          }

                      

                        foreach ($tags_to_delete as $tag) {
                            //gets the tag id
                            $tagId = $this->productModel->getTagIdByTitle($tag);
                             
                            //deletes the tag from the product
                            $tagsSuccess = $this->productModel->deleteProductTag($productId, $tagId);

                            
                          
                          //product and tag were added successfully
                        if (!($tagsSuccess))
                        throw new \Exception('There was a problem updating the product_tags table, please try again');

                            }


                            //checks if the artist already exists in the database
                           // echo "the artist title is: $artistTitle<br>";
                            $artistId = $productModel->checkArtist($artistTitle);
                            //if the artist doesn't exist, insert it
                            if (!$artistId) {
                                //inserts the artist and returns the artist id
                                $artistId = $productModel->updateArtist($artistTitle);
                            }
                            else
                            {
                                //get the artist id
                                $artistId = $productModel->getArtistId($artistTitle);
                            }

                            //inserts the cd product - no need for the cd is so just getting a success message
                            /* parameter returned: boolean */
                           // echo "$newProductId, $releaseDate, $artistId<br>";

                            $cdInserted = $productModel->updateCd($releaseDate, $artistId, $productId);
                            if (!$cdInserted) {
                                throw new \Exception('There was a problem updating the cd table, please try again');
                            }

                            //if the cd was updated successfully, continue adding the product image

                              
                              if ($image_to_upload_exist) {
                                // Handle image upload
                               $image_name = $image;
                                $main_image = 1;
                                $image_added = $productModel->updateImage($productId, $image_name, $main_image);
                              }
                                  //throws an error if the image was not updated
                         
                                //inserts the product variant and returns the product variant id
                                /* parameter returned: int */
                                $new_products_var_added1 = $this->productModel->updateProductVariant($variantId, $price, $quantityInStock);

                                if(!$new_products_var_added1) {
                                    throw new \Exception('There was a problem updating the product_variant table, please try again');
                                }
                           //if the product variant was inserted successfully, continue to confirm the product was added successfully
                    
                            
                                //sets the success message in the session variable
                                SessionManager::setSessionVariable('success_message', 'Product was updated successfully');
                                $response = ['status' => 'success', 'message' => 'Product was updated successfully'];
                               
                               
                               $this->db->commit();
                                echo json_encode($response);

        } catch (\PDOException $ex) {
          $this->db->rollBack();
             // Handle exceptions
        $error_message = $ex->getMessage();
        $response = ['status' => 'error', 'message' => $error_message];
        echo json_encode($response);
       
        }
    }

    //soft delete
    public function deleteProductVariant(){
        try {

            $variantId = $_POST['product_variant_id'];
            $productId = $_POST['product_id'];
            $csrf_token = $_POST['csrf_token'];

            // Validate the CSRF token on form submission - to ensure that only by authorized admin users
            if (!(SessionManager::validateCSRFToken($csrf_token))) {
                // Send message to user about invalid CSRF token
                SessionManager::setSessionVariable('error_message', 'Invalid CSRF token. Please try again.');
                header('Location:' . BASE_URL . '/admin/products');
                exit();
            }

            //verifying if the user is logged as admin
            if (!LoginController::isAdmin()) {
                // Send message to user about unauthorized access
                SessionManager::setSessionVariable('error_message', 'Unauthorized access.');
                header('Location:' . BASE_URL . '/admin/products');
                exit();
            }
   
       
           //check if product variant has a special offer - if yes - send a message that
           //a special offer must be deleted first
              $specialOffer = $this->specialOfferModel->isSpecialOffer($variantId);
                if ($specialOffer) {
                    SessionManager::setSessionVariable('alert_message', 'The product variant has a special offer. Please delete the special offer first.');;
                    header('Location:' . BASE_URL . '/admin/products');
                    exit();
                }
            //no special offer - delete the product variant (soft delete - mark as deleted in the database)
            //returns product id of the product variant
            $productVariantDeleted = $this->productModel->deleteProductVariant($variantId);
            if (!$productVariantDeleted) {
                SessionManager::setSessionVariable('error_message', 'There was a problem deletingt the product. Please try again.');
                header('Location:' . BASE_URL . '/admin/products');
                exit();
            }

      
            
      //      $this->db->commit();
           //The product was succssefully deleted from all its relevant tables
            SessionManager::setSessionVariable('success_message', 'Product Variant was deleted successfully');
            header('Location:' . BASE_URL . '/admin/products');


        } catch (\PDOException $ex) {
         //   $this->db->rollBack();
            // Handle exceptions
            $error_message = $ex->getMessage();
            SessionManager::setSessionVariable('error_message', $error_message);
            header('Location:' . BASE_URL . '/admin/products');
            exit();
        }
    }

    public function search($searchTerm) {
        try {

            if (empty($searchTerm)) {
                // Redirect backs to admin page in case the search term is empty
                SessionManager::setSessionVariable('alert_message', 'Please enter a search term.');
                header('Location:' . BASE_URL.'/admin/products');
                exit();
            }
            $productsList = $this->productModel->search($searchTerm); // Call the method on the instance

            // Load the view to display the products
            include 'views/admin/products.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
