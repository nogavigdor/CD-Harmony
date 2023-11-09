<?php

namespace models;

use PDO; 

class CompanyModel extends BaseModel
{
	function __construct() {}

    public function getCompanyDetails() {
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
        }
    }


    public function updateCompanyDetails($companyId, $companyName, $street)
    {
    try {
        $db = parent::connectToDB();
        $query = $db->prepare('
            UPDATE company_details
            SET company_name = :company_name, street = :street
            WHERE company_id = :company_id
        ');

        $query->bindParam(':company_id', $companyId);
        $query->bindParam(':company_name', $companyName);
        $query->bindParam(':street', $street);

        $query->execute();
    } catch (\PDOException $ex) {
        // Handle errors (log or rethrow the exception)
        throw $ex;
    }
}




}
