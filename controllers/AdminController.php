<?php
namespace controllers;

use models\AdminModel;

class AdminController
{
  
    public function showAdmin()
    {
        // Use the getCompanyDetails function to get company details
       // $companyModel = new CompanyModel();
        //$company = $companyModel->getCompanyDetails();
        // Render the view
      
        include_once 'views/admin/admin.php';
    }

  

   
}
