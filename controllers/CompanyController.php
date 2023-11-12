<?php
namespace controllers;

use models\CompanyModel;

class CompanyController
{
    public function getCompanyDetails()
    {
        try {
            $companyModel = new CompanyModel();
            return $companyModel->getCompanyDetails();
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
            // Handle error (maybe return default company details or log the error)
            return null;
        }
    }


    public function showCompanyDetails()
    {
        // Use the getCompanyDetails function to get company details
       // $companyModel = new CompanyModel();
        //$company = $companyModel->getCompanyDetails();
        // Render the view
      
        include_once 'views/admin/company.php';
    }

    public function updateCompanyDetails()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Retrieve values from the form
                $companyId = $_POST['company_details_id'];
                $companyName = $_POST['company_name'];
                $street = $_POST['street'];
                $postalCodeId = $_POST['postal_code_id'];
                $openingHours = $_POST['opening_hours'];
                $phoneNumber = $_POST['phone_number'];
                $email = $_POST['email'];


                $companyDetails=new CompanyModel();
                $success=$companyDetails->updateCompanyDetails($companyId, $companyName, $street, $postalCodeId, $openingHours, $email, $phoneNumber  );

                
                
                
                if ($success) {
                   
                   echo 'The company details have been updated successfully.';
                } else {
                    // Return an error message
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update company details']);
                }

                // Terminate script execution to prevent additional output
                exit();
                
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

   
}
