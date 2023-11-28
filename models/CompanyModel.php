<?php

namespace Models;   

use \DataAccess\DBConnector;
use PDO; 

class CompanyModel 
{
    private $dbConnector; 

    public function __construct()
    {
        $this->dbConnector = DBConnector::getInstance(); 
    }

    public function getCompanyDetails() {
        try {
            $dbInstance = $this->dbConnector;
            $db=$dbInstance->connectToDB();
            $query = $db->prepare('
            
            SELECT
            c.*, p.city
            FROM company_details c
            INNER JOIN postal_codes p ON c.postal_code_id=p.postal_code_id
            ');
            $query->execute();

            return $query->fetch(PDO::FETCH_OBJ);

      
        } catch (\PDOException $ex) {
            print($ex->getMessage());
        } 
           
        
    }


    public function updateCompanyDetails($companyId, $companyName, $street, $postalCodeId, $openingHours, $phoneNumber, $email)
    {
    try {
        $dbInstance = $this->dbConnector;
        $db=$dbInstance->connectToDB();
        $query = $db->prepare('
            UPDATE company_details
            SET company_name = :company_name, street = :street, postal_code_id = :postal_code_id, email = :email, opening_hours = :opening_hours, phone_number = :phone_number
            WHERE company_details_id = :company_details_id
        ');

        $query->bindParam(':company_details_id', $companyId);
        $query->bindParam(':company_name', $companyName);
        $query->bindParam(':street', $street);
        $query->bindParam(':postal_code_id', $postalCodeId);
        $query->bindParam(':phone_number', $phoneNumber);
        $query->bindParam(':email', $email);
        $query->bindParam(':opening_hours', $openingHours);


        $success = $query->execute();
        return $success;
    } catch (\PDOException $ex) {
        // Handle errors (log or rethrow the exception)
        throw $ex;
    } 
    
    
}




}
