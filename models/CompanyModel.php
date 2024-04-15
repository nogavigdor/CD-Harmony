<?php
namespace Models;   
use \DataAccess\DBConnector;
use PDO; 

class CompanyModel 
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


    public function updateCompanyDetails($companyId, $companyName, $street, $postalCodeId, $openingHours, $email, $phoneNumber)
    {
    try {
        $query = $this->db->prepare('
            UPDATE company_details
            SET company_name = :company_name, street = :street, email = :email, phone_number = :phone_number,postal_code_id = :postal_code_id,
            opening_hours = :opening_hours
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
    } catch (\PDOException $e) {
        // Handle errors (log or rethrow the exception)
        throw $e;
    } 
    
    
}

//checks if the postal code exists in the database
public function isPostalCode($postalCodeId)
    {
        try {
            $query = $this->db->prepare('
                SELECT * FROM postal_codes WHERE postal_code_id = :postal_code_id
            ');

            $query->bindParam(':postal_code_id', $postalCodeId);
            $query->execute();

            return $query->rowCount() > 0;
        } catch (\PDOException $e) {
            // Handle errors (log or rethrow the exception)
            throw $e;
        }
    }
}
