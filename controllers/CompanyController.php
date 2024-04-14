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
        if(!(AdminController::authorizeAdmin())) 
        {
            // Redirect to the login page
            header('Location:'. BASE_URL. '/admin-login');
            exit();
        }

        if (!(SessionManager::validateCSRFToken($_POST['csrf_token']))) 
        {
            SessionManager::setSessionVariable('error_message', 'CSRF token validation failed.');
            header('Location:'. BASE_URL. '/admin/company');
        }


            try {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Retrieve values from the form
                    $companyId=htmlspecialchars(trim($_POST['company_details_id']));
                    $companyName = htmlspecialchars(trim($_POST['company_name']));
                    $street = htmlspecialchars(trim($_POST['street']));
                    $city = htmlspecialchars(trim($_POST['city']));
                    $postalCodeId = htmlspecialchars(trim($_POST['postal_code_id']));
                    $openingHours = htmlspecialchars(trim($_POST['opening_hours']));
                    $phoneNumber = htmlspecialchars(trim($_POST['phone_number']));
                    $email = htmlspecialchars(trim($_POST['email']));

                    
                    $errType = [];

               

                    //validate company name
                    if (empty($companyName)) {
                        $errType['company_name'] = 'Company name is required';
                    } else {
                      if ($this->validator->validateName($companyName))  
                        {
                            $errType['company_name'] = 'Company name should be up to 50 characters';
                        }
                    }   

                    //validate street
                    if (empty($street)) {
                        $errType['street'] = 'Street is required';
                    } else {
                      if ($this->validator->validateName($street))  
                        {
                            $errType['street'] = 'Street should be up to 50 characters';
                        }
                    }

                    //validate city
                    if (empty($city)) {
                        $errType['city'] = 'City is required';
                    } else {
                      if ($this->validator->validateName($city))  
                        {
                            $errType['city'] = 'City should be up to 50 characters';
                        }
                    }

                    //validate postal code
                    if (empty($postalCodeId)) {
                        $errType['postal_code_id'] = 'Postal code is required';
                    } else {
                      if ($this->companyModel->validatePostalCode($postalCodeId) != $city)
                        {
                            $errType['postal_code_id'] = 'Please enter a city and postal code that match';
                        }
                    }

                    if(empty($openingHours)) {
                        $errType['opening_hours'] = 'Opening hours is required';
                    } else {
                        if ($this->validator->validateTitle($openingHours))  
                        {
                            $errType['opening_hours'] = 'Opening hours should be up to 100 characters';
                        }
                    }

                    if(empty($email)) {
                        $errType['email'] = 'Email is required';
                    } else {
                        if ($this->validator->validateEmail($email))  
                        {
                            $errType['email'] = 'Please enter a valid email';
                        }
                    }

                    if(empty($phoneNumber)) {
                        $errType['phone_number'] = 'Phone number is required';
                    } else {
                        if ($this->validator->validateName($phoneNumber))  
                        {
                            $errType['phone_number'] = 'Please enter a valid phone number';
                        }
                    }
                   
                
                    if (!empty($errType)) {
                        // Show an error message
                        SessionManager::setSessionVariable('error_message', 'Please correct the errors in the form');
                        // Redirect to the company details page
                        header('Location: ' . BASE_URL . '/admin/company');
                        exit();
                    }

                  

    
                    $success=$this->companyModel->updateCompanyDetails($companyId, $companyName, $street, $city, $postalCodeId, $openingHours, $email, $phoneNumber);
                    if ($success) {
                       //Show a success message
                       SessionManager::setSessionVariable('success_message', 'Company details were updated successfully');
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
            } catch (\PDOException $e) {
                error_log('PDO Exception: ' . $e->getMessage());
            }
      
    }

   
}
