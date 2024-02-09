<?php

namespace Models;

use PDO; 

class AdminModel
{
    private $db;
	function __construct() {
        $this->db = DBConnector::getInstance()->connectToDB();
    }

    public function updateCompanyDetails($companyId, $companyName, $street)
    {
    try {
        $query = $this->db->prepare('
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
