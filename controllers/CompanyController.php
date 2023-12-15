<?php
namespace Controllers;
use \Models\CompanyModel;
use \Services\SessionManager;
//SessionManager::startSession();


class CompanyController
{
    public function __construct()
    {
        $session = new SessionManager();
        $session->startSession();
    }   
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
        if(AdminController::authorizeAdmin()) {
      
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
        if (SessionManager::validateCSRFToken($_POST['csrf_token'])) {
            // CSRF token is valid
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
                    $logo = htmlspecialchars(trim($_POST['logo']));
    
                    $companyDetails=new CompanyModel();
                    $success=$companyDetails->updateCompanyDetails($companyId, $companyName, $street, $postalCodeId, $openingHours, $email, $phoneNumber, $logo  );
    
                    
                    
                    
                    if ($success) {
                       //Show a success message
                       SessionManager::setSessionVariable('success_message', 'Company details updated successfully');
                        // Redirect to the company details page
                        header('Location: ' . BASE_URL . '/admin/company');
                        exit();
                    } else {
                        // Show an error message
                        SessionManager::setSessionVariable('error_message', 'Company details could not be updated');
                        // Redirect to the company details page
                        header('Location: ' . BASE_URL . '/admin/company');
                        exit();
                    }
    
                    // Terminate script execution to prevent additional output
                    exit();
                    
                }
            } catch (\PDOException $ex) {
                error_log('PDO Exception: ' . $ex->getMessage());
            }
           
        }
        // CSRF token validation failed, show an error
        else {
            exit('CSRF token validation failed');
        }
      
    }

   
}
