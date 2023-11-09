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

    // Other methods...

    public function showCompanyDetails()
    {
        // Use the getCompanyDetails function to get company details
        $companyModel = new CompanyModel();
        $company = $companyModel->getCompanyDetails();
        // Render the view
        var_dump($company);
        include 'views/admin/company.php';
    }

    public function updateCompanyDetails()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Retrieve values from the form
                $companyId = $_POST['company_id'];
                $companyName = $_POST['company_name'];
                $street = $_POST['street'];

                // Add similar lines for other attributes

                // Call the update method
                $companyDetails=new CompanyModel();
                $companyDetails->updateCompanyDetails($companyId, $companyName, $street);

                
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

   
}
