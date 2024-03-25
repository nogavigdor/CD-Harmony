<?php
namespace Controllers;
use Services\SessionManager;
use Models\SpecialOfferModel;

class SpecialOfferController 
{
    private $specialOfferModel;
    public function __construct() {
        $session = new SessionManager();
        $session->startSession();
        $this->specialOfferModel = new SpecialOfferModel();
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

    public function updateSpecialOfferDetails($id)
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
}


