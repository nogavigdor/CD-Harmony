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
    private $session;
    public function __construct() {
        $this->session = new SessionManager();
        $this->session->startSession();
        $this->specialOfferModel = new SpecialOfferModel();
        $this->validator = new Validator();
        $this->db = DBConnector::getInstance()->connectToDB();
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

   

    //update secial offer on homepage
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
    
    public function showEditSpecialOfferForm($id)
    {
        try {
            // Get the special offer details
            $offer = $this->specialOfferModel->getSpecialOfferBySpecialOfferId($id);
            $offerDetails = $this->specialOfferModel->getSpecialOfferDetails($offer['product_variant_id']);
            include 'views/admin/edit-special-offer.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function updateSpecialOffer() {
        try {
          $this->db->beginTransaction();
            
            // Retrieve form data and sanitize input
            $csrfToken = htmlspecialchars($_POST['csrfToken'] ?? '');
            $specialOfferTitle = htmlspecialchars(trim($_POST['specialOfferTitle'] ?? ''));
            $specialOfferDescription = htmlspecialchars(trim($_POST['specialOfferDescription'] ?? ''));
            $discount = htmlspecialchars(trim($_POST['discount'] ?? ''));
            $startDate = htmlspecialchars(trim($_POST['startDate'] ?? ''));
            $endDate = htmlspecialchars(trim($_POST['endDate'] ?? ''));
            $specialOfferId = htmlspecialchars(trim($_POST['specialOfferId'] ?? ''));
            
            // Verify if the user is logged in as an admin
            if (!SessionManager::isAdmin()) {
                throw new \Exception('Unauthorized access');
            }
            
            // Validate the CSRF token on form submission
            if (!SessionManager::validateCSRFToken($csrfToken)) {
                throw new \Exception('CSRF token validation failed');
            }
            
            // Validate request method
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Invalid request method');
            }
            
            // Initialize an array to store errors
            $errors = [];
            
            // Validate special offer title
            if (empty($specialOfferTitle)) {
                $errors['specialOfferTitle'] = 'Special Offer Title is required';
            }
            
            // Validate special offer description
            if (empty($specialOfferDescription)) {
                $errors['specialOfferDescription'] = 'Special Offer description is required';
            }
            
            // Validate discount
            if (empty($discount)) {
                $errors['discount'] = 'Discount is required';
            } else {
                $discountValidation = $this->validator->validatePositiveNumber($discount);
                if ($discountValidation !== null) {
                    $errors['discount'] = $discountValidation;
                }
            }

            $discount = floatval($discount);
            
            // Validate start and end dates
            if (empty($startDate)) {
                $errors['startDate'] = 'Start date is required';
            }
            
            if (empty($endDate)) {
                $errors['endDate'] = 'End date is required';
            }
            
            // Validate date range so that the start date is before or equals to the end date
            if ($startDate && $endDate) {
                $dateRangeValidation = $this->validator->validateDateRange($startDate, $endDate);
                if ($dateRangeValidation !== null) {
                    $errors['dateRange'] = $dateRangeValidation;
                }
            }
    
            // Handle errors
              // Check for errors
              if (!empty($errors)) {
                http_response_code(400);
                echo json_encode($errors);
                return;
            }
            echo $specialOfferId;
            exit();
            // update special offer in the database
            $success = $this->specialOfferModel->updateSpecialOffer($specialOfferId, $specialOfferTitle, $specialOfferDescription, $discount, $startDate, $endDate);
            if (!$success) {
                throw new \Exception('There was a problem updating the special offer in the database');
            }

             
            $this->db->commit();
            echo json_encode(['status' => 'success', 'message' => 'Special Offer updated successfully']);
            
          
            
        } catch (\PDOException $ex) {
           $this->db->rollBack();
            error_log('PDO Exception: ' . $ex->getMessage());
            echo 'An error occurred while processing your request';
        } catch (\Exception $ex) {
            error_log('Exception: ' . $ex->getMessage());
            echo 'An error occurred while processing your request';
        }
    }

    public function deleteSpecialOffer() {
        try {
            // Verify if the user is logged in as an admin
            if (!SessionManager::isAdmin()) {
                throw new \Exception('Unauthorized access');
            }

            $specialOfferId = htmlspecialchars(trim($_POST['special_offer_id']));

            $isHomepage = $this->specialOfferModel->isHomepage($specialOfferId);

            //if special offer is set to display on the homepage, it can't be deleted
            if ($isHomepage['is_homepage']) {
              SessionManager::setSessionVariable('error_message', 'Special Offer is on the homepage and cannot be deleted');
                header('Location: ' . BASE_URL . '/admin/special-offers');
            }
            
            // Delete the special offer
            $success = $this->specialOfferModel->deleteSpecialOffer($specialOfferId);
   
            if (!$success)
            {
                throw new \Exception('There was a problem deleting the special offer');
            }

            SessionManager::setSessionVariable('success_message', 'Special Offer was deleted successfully');
            header('Location: ' . BASE_URL . '/admin/special-offers');
            exit();
    
        } catch (\Exception $ex) {
            error_log('Exception: ' . $ex->getMessage());
            http_response_code(500); // Internal Server Error status code
            echo json_encode(['status' => 'error', 'message' => 'An error occurred while deleting the special offer']);
        }
    }
    
}
