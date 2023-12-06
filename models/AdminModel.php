<?php

namespace Models;

use PDO; 

class AdminModel
{
	function __construct() {}

    public function adminSomething() {
        try {
            $db = parent::connectToDB();
            $query = $db->prepare('
            
            SELECT
            *
            FROM company_details 
            ');
            $query->execute();

            return $query->fetch(PDO::FETCH_OBJ);

      
        } catch (\PDOException $ex) {
            print($ex->getMessage());
        } finally {
            parent::closeConnection();
        }
    }


    public function updateCompanyDetails($companyId, $companyName, $street)
    {
    try {
        $db = parent::connectToDB();
        $query = $db->prepare('
            UPDATE company_details
            SET company_name = :company_name, street = :street
            WHERE company_details_id = :company_details_id
        ');

        $query->bindParam(':company_details_id', $companyId);
        $query->bindParam(':company_name', $companyName);
        $query->bindParam(':street', $street);

        $query->execute();
    } catch (\PDOException $ex) {
        // Handle errors (log or rethrow the exception)
        throw $ex;
    } finally {
        parent::closeConnection();
    }
}




}
