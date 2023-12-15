<?php
namespace Models;   
use \DataAccess\DBConnector;
use PDO; 

class AdminModel 
{
    private $db; 

    public function __construct()
    {
        $this->db = DBConnector::getInstance()->connectToDB();
    }

    public function getCompanyDetails() {
        try {
         
            $query = $this->db->prepare('
            
            SELECT
            c.*, p.city
            FROM company_details c
            INNER JOIN postal_codes p ON c.postal_code_id=p.postal_code_id
            ');
            $query->execute();

            return $query->fetch(PDO::FETCH_OBJ);

      
        } catch (\PDOException $e) {
            print($e->getMessage());
        } 
           
        
    }


    public function updateCompanyDetails($companyId, $companyName, $street, $postalCodeId, $openingHours, $email, $phoneNumber, $logo)
    {
    try {
        $query = $this->db->prepare('
            UPDATE company_details
            SET company_name = :company_name, street = :street, email = :email, phone_number = :phone_number,postal_code_id = :postal_code_id,
            opening_hours = :opening_hours, logo = :logo
            WHERE company_details_id = :company_details_id
        ');

        $query->bindParam(':company_details_id', $companyId);
        $query->bindParam(':company_name', $companyName);
        $query->bindParam(':street', $street);
        $query->bindParam(':postal_code_id', $postalCodeId);
        $query->bindParam(':phone_number', $phoneNumber);
        $query->bindParam(':email', $email);
        $query->bindParam(':opening_hours', $openingHours);
        $query->bindParam(':logo', $logo);


        $success = $query->execute();
        return $success;
    } catch (\PDOException $e) {
        // Handle errors (log or rethrow the exception)
        throw $e;
    } 
    
    
}




}
