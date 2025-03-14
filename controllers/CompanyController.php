<?php
namespace Controllers;
use \Models\CompanyModel;
use \Services\SessionManager;
use \Services\Validator;

//SessionManager::startSession();


class CompanyController
{
    private $companyModel;
    private $validator;
    public function __construct()
    {
        $this->companyModel = new CompanyModel();
        $this->validator = new Validator();
        $session = new SessionManager();
        $session->startSession();
    }   
    public function getCompanyDetails()
    {
        try {
            $companyModel = new CompanyModel();
            return $companyModel->getCompanyDetails();
        } catch (\PDOException $e) {
            error_log('PDO Exception: ' . $e->getMessage());
            // Handle error (maybe return default company details or log the error)
            return null;
        }
    }

    public function showCompanyDetails()
    {
        if(AdminController::authorizeAdmin()) {
       
        $company = $this->getCompanyDetails(); 
        include_once 'views/admin/company.php';
        } else {
        // Redirect to the login page
        header('Location:'. BASE_URL. '/admin-login');
        exit();
        }
    }

    public function updateCompanyDetails()
    {
        if (!(AdminController::authorizeAdmin())) {
            return json_encode(['success' => false, 'message' => 'Authorization failed']);
        }

        
        if (!isset($_POST['csrf_token']) || !(SessionManager::validateCSRFToken($_POST['csrf_token']))) {
            return json_encode(['success' => false, 'message' => 'CSRF token validation failed']);
        }

        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $companyId = htmlspecialchars(trim($_POST['company_details_id']));
                $companyName = htmlspecialchars(trim($_POST['company_name']));
                $street = htmlspecialchars(trim($_POST['street']));
                $postalCodeId = htmlspecialchars(trim($_POST['postal_code_id']));
                $openingHours = htmlspecialchars(trim($_POST['opening_hours']));
                $phoneNumber = htmlspecialchars(trim($_POST['phone_number']));
                $email = htmlspecialchars(trim($_POST['email']));

                $errType = [];

                // Validate company name
                if (empty($companyName)) {
                    $errType['company_name'] = 'Company name is required';
                } elseif ($this->validator->validateName($companyName)) {
                    $errType['company_name'] = 'Company name should be up to 50 characters';
                }

                // Validate street
                if (empty($street)) {
                    $errType['street'] = 'Street is required';
                } elseif ($this->validator->validateName($street)) {
                    $errType['street'] = 'Street should be up to 50 characters';
                }

                // Validate postal code
                if (empty($postalCodeId)) {
                    $errType['postal_code_id'] = 'Postal code is required';
                } elseif (!$this->companyModel->isPostalCode($postalCodeId)) {
                    $errType['postal_code_id'] = 'Please enter an existing postal code in Denmark';
                }

                if (empty($openingHours)){
                    $errType['opening_hours'] = 'Opening hours is required';
                } elseif ($this->validator->validateTitle($openingHours)) {
                    $errType['opening_hours'] = 'Opening hours should be up to 100 characters';
                }

                if(empty($phoneNumber)){
                    $errType['phone_number'] = 'Phone number is required';
                } elseif ($this->validator->validatePhoneNumber($phoneNumber)) {
                    $errType['phone_number'] = 'Please enter a valid phone number';
                }

                // Validate email
                if (empty($email)) {
                    $errType['email'] = 'Email is required';
                } elseif ($this->validator->validateEmail($email)) {
                    $errType['email'] = 'Please enter a valid email address';
                }

                //if there are any validation errors, return the errors
                if (!empty($errType)) {
                    echo json_encode(['success' => false, 'errors' => $errType]);
                    return;
                }
                 
                
                // Update the company details
                $success = $this->companyModel->updateCompanyDetails($companyId, $companyName, $street, $postalCodeId, $openingHours, $email, $phoneNumber);
               
                if ($success) {
                    echo json_encode(['success' => true, 'message' => 'Company details were updated successfully']);
                    return;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Company details could not be updated']);
                    return;
                }
            }
        } catch (\PDOException $e) {
            error_log('PDO Exception: ' . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'An error occurred while updating company details']);
        }
    }

}