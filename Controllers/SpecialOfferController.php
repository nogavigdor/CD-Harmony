<?php
namespace Controllers;

use Models\SpecialOfferModel;

class SpecialOfferController 
{
    private $specialOfferModel;
    public function __construct() {
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
            // Get the special deal details
            $this->specialspecialOfferModel->createSpecialOffer($title, $description, $discountPrecentage, $startDate, $endDate); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }   

    public function updateSpecialOffer($id)
    {
        try {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $discountPrecentage = $_POST['discountPrecentage'];
            $startDate = $_POST['startDate'];
            $endDate = $_POST['endDate'];
            $productID = $_POST['productID'];
            // update the special deal details
            $this->specialOfferModel->updateSpecialOffer($title, $description, $discountPrecentage, $startDate, $endDate, $productID); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function deleteSpecialOffer()
    {
        try {
            $productID = $_POST['productID'];
            // Get the special deal details
            $specialOfferModel->deleteSpecialOffer($productID); // Call the method on the instance
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
    

	public function getSpecialOfferDetails($id)
    {
        try {
            // Get the special deal details
            return $this->specialDealModel->getSpecialOffierDetails(); // Call the method on the instance
            
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


