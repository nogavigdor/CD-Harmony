<?php
namespace Controllers;

use \Models\CompanyModel;
use \Services\SessionManager;
SessionManager::startSession();


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
        if(SessionManager::getSessionVariable('user') && SessionManager::getSessionVariable('user')['role'] == 1) {
      
        include_once 'views/admin/company.php';
        } else {
        // Redirect to the login page
        header('Location:'. BASE_URL. '/admin-login');
        exit();
        }
    }

    public function updateCompanyDetails()
    {
        // Validate the CSRF token on form submission - to ensure that only by authorized admin users
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // CSRF token is invalid, handle accordingly (e.g., log the incident, show an error)
            exit('CSRF token validation failed');
        }
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Retrieve values from the form
                $companyId=htmlspecialchars(trim($_POST['company_details_id']));
                $companyName = htmlspecialchars(trim($_POST['company_name']));
                $street = htmlspecialchars(trim($_POST['street']));
                $postalCodeId = htmlspecialchars(trim($_POST['postal_code_id']));
                $openingHours = htmlspecialchars(trim($_POST['opening_hours']));
                $phoneNumber = htmlspecialchars(trim($_POST['phone_number']));
                $email = htmlspecialchars(trim($_POST['email']));

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
