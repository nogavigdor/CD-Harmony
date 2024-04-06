<?php
namespace Controllers;
use Services\SessionManager;
use Services\Validator;
use Models\SpecialOfferModel;
use DataAccess\DBConnector;


class SpecialOfferController 
{
    private $db;
    private $specialOfferModel;
    private $validator;
    public function __construct() {
        $session = new SessionManager();
        $session->startSession();
        $this->specialOfferModel = new SpecialOfferModel();
        $this->validator = new Validator();
        $this->db = DBConnector::getInstance()->connectToDB();
    }
    public function createSpecialOffer()
    {
        try {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $discountPrecentage = $_POST['discountPrecentage'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $this->specialOfferModel->createSpecialOffer($productVariantId, $title, $description, $discountPrecentage, $startDate, $endDate); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }   

    public function updateSpecialOffer()
    {
        try {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $discountPrecentage = $_POST['discountPrecentage'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $productVariantId = $_POST['productVariantID'];
            // update the special deal details
            $success = $this->specialOfferModel->updateSpecialOffer($productVariantID, $title, $description, $discountPrecentage, $startDate, $endDate); // Call the method on the instance
            SessionManager::setSessionVariable('success_massage', 'Special Offer updated successfully');
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function deleteSpecialOffer()
    {
        try {
            $productVariantId = $_POST['productVariantId'];
            // Get the special deal details
            $success=$specialOfferModel->deleteSpecialOffer($productID); // Call the method on the instance
            SessionManager::setSessionVariable('success_massage', 'Special Offer deleted successfully');
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function updateHomepage($productVariantId)
    {
        try {
            // Update the special offer product to be on the homepage
            $success = $this->specialOfferModel->updateHomepage($productVariantId);

            // Send JSON response indicating success or failure
            if ($success) {
                http_response_code(200); // Success status code
                echo json_encode(array('status' => 'success'));
            } else {
                http_response_code(500); // Internal Server Error status code
                echo json_encode(array('status' => 'error'));
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
            http_response_code(500); // Internal Server Error status code
            echo json_encode(array('status' => 'error'));
        }
    }

    

	public function showSpecialOffer()
    {
        try {
            // Get the special deal details
            return $this->specialOfferModel->showSpecialOffer(); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

   

    public function getSpecialOffer($productVariantId)
    {
        try {
            // Get the special deal details
            return $this->specialOfferModel->getSpecialOffer($productVariantId); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
    
  public function getAllSpecialOffers(){
        try {
            // Get the special deal details
            return  $this->specialOfferModel->getAllSpecialOffers(); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
    //Presents the special offers page in the admin panel
    public function showSpecialOffers()
    {
        try {
            // Get all the special offers
            $specialOffers = self::getAllSpecialOffers();
           include 'views/admin/special-offers.php';

        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function deleteSpecialOfferDetails($id)
    {
        try {
            // Create an instance of the model
            $specialDealModel = new SpecialDealModel(); // Create an instance of ProductModel
            // Get the special deal details
            return $specialDealModel->deleteSpecialDealDetail(); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showSpecialOfferForm($variant_id)
    {
        try {
            $specialOffer = $this->specialOfferModel->getSpecialOfferDetails($variant_id); 
           include 'views/admin/add-special-offer.php';

        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function addSpecialOffer() {
        try {
            $this->db->beginTransaction();
    
            // Verify if the user is logged in as an admin
            if (!SessionManager::isAdmin()) {
                throw new \Exception('Unauthorized access');
            }
    
            // Validate the CSRF token on form submission
            if (!SessionManager::validateCSRFToken($_POST['csrf_token'])) {
                echo json_encode(["error" => "CSRF token validation failed"]);
                return;
            }
    
            // Validate request method
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Invalid request method');
            }
    
            // Initialize an array to store errors
            $errType = [];

            //The defualt value for isHomepage is 0 so the special offer will not be displayed on the homepage
            $isHomepage = 0;
            // Get the product variant ID
            $variantId = htmlspecialchars(trim($_POST['variantId']));
    
            // Validate special offer title
            $specialOfferTitle = htmlspecialchars(trim($_POST['specialOfferTitle']));
            if (empty($specialOfferTitle)) {
                $errType['specialOfferTitle'] = 'Special Offer Title is required';
            
            $msg=$this->validator->validateText($specialOfferTitle);
            if ($msg) {
                $errType['specialOfferTitle'] = $msg;
            }
            }
    
            // Validate special offer description
            $specialOfferDescription = htmlspecialchars(trim($_POST['specialOfferDescription']));
            if (empty($specialOfferDescription)) {
                $errType['specialOfferDescription'] = 'Special Offer description is required';
            } 
            
            $msg=$this->validator->validateText($specialOfferDescription);
            if ($msg) {
                $errType['specialOfferDescription'] = 'The description should be max 5000 characters';
            }
            
            //current product price
            $price = htmlspecialchars(trim($_POST['price']));
    
            // Validate discount
            $discount = htmlspecialchars(trim($_POST['discount']));
            if (empty($discount)) {
                $errType['discount'] = 'Discount is required';
            } else {
                if ($discount>$price) {
                    $errType['discount'] = 'Discount should not be greater than the current price';
                }
                $discountValidation = $this->validator->validatePositiveNumber($discount);
                if ($discountValidation !== null) {
                    $errType['discount'] = $discountValidation;
                }
            }
    
            // Validate start and end dates
            $startDate = isset($_POST['startDate']) ? htmlspecialchars(trim($_POST['startDate'])) : null;
            $endDate = isset($_POST['endDate']) ? htmlspecialchars(trim($_POST['endDate'])) : null;

            if (empty($startDate)) {
                $errType['startDate'] = 'Start date is required';
            } 

            if (empty($endDate)) {
                $errType['endDate'] = 'End date is required';
            } 

            // Validate date range
            if ($startDate && $endDate) {
                $dateRangeValidation = $this->validator->validateDateRange($startDate, $endDate);
                if ($dateRangeValidation !== null) {
                    $errType['dateRange'] = $dateRangeValidation;
                }
            }

            //get all special offers for the product variant
            $specialOffers = $this->specialOfferModel->getSpecialOffersByVariantId($variantId);

            foreach ($specialOffers as $specialOffer) {
                //the start date of the existing special offer
                $start1 = $specialOffer['special_offer_start_date'];
                //the end date of the existing special offer
                $end1 = $specialOffer['special_offer_end_date'];
                //the start date of the new special offer
                $startDate;
                //the end date of the new special offer
                $endDate;
                $dateOverlapValidation = $this->validator->dateRangesOverlap($start1, $end1, $startDate, $endDate);
                if ($dateOverlapValidation !== null) {
                    $errType['dateOverlap'] = $dateOverlapValidation;
                }
            }

    
            // Check for errors
            if (!empty($errType)) {
                http_response_code(400);
                echo json_encode($errType);
                return;
            }
         
    
            // Add special offer to the database
            $specialOffer = $this->specialOfferModel->addSpecialOffer($variantId, $specialOfferTitle, $specialOfferDescription, $isHomepage, $discount, $startDate, $endDate);
          
            if (!$specialOffer) {
                throw new \Exception('There was a problem adding the special offer to the database');
            }
    
            // Commit transaction
            $this->db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Special Offer added successfully']);
    
        } catch (\PDOException $ex) {
            $this->db->rollBack();
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
        } catch (\Exception $ex) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
    

    public function updateSpecialOfferDetails($id)
    {
        try {
            // Create an instance of the model
            $specialOfferModel = new SpecialOfferModel(); // Create an instance of ProductModel
            // Get the special deal details
            return $specialOfferModel->deleteSpecialOffer(); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}


