<?php
namespace Models;

    use \DataAccess\DBConnector;

    use PDO; 

    class SpecialOfferModel
    {
        private $db; 

        public function __construct()
        {
            $this->db = DBConnector::getInstance()->connectToDB();
        }

        public function createSpecialOffer($productVariantId, $title, $description, $discountPrecentage, $startDate, $endDate)
        {
            try {
                $sql = '
                    INSERT INTO special_offers (product_variant_id, title, special_offer_description, discount_precentage, special_offer_start_date, special_offer_end_date)
                    VALUES (:product_variant_id, :title, :special_offer_description, :discount_precentage, :special_offer_start_date, :special_offer_end_date)
                ';

                $query->bindParam(':product_variant_id', $productVariantId);
                $query->bindParam(':title', $title);
                $query->bindParam(':special_offer_description', $description);
                $query->bindParam(':discount_precentage', $discountPrecentage);
                $query->bindParam(':special_offer_start_date', $startDate);
                $query->bindParam(':special_offer_end_date', $endDate);
                $query = $this->db->prepare($sql);
                $query->execute();
            } catch (\PDOException $e) {
                
                $e->getMessage();
            }
        }

        public function updateSpecialOffer($productVariantId, $title, $description, $discountPrecentage, $startDate, $endDate)
        {
            try {
                $sql = '
                    UPDATE special_offers
                    SET title = :title, special_offer_description = :special_offer_description, discount_precentage = :discount_precentage, special_offer_start_date = :special_offer_start_date, special_offer_end_date = :special_offer_end_date
                    WHERE product_variant_id = :product_variant_id
                ';

                $query->bindParam(':product_variant_id', $productVariantId);
                $query->bindParam(':title', $title);
                $query->bindParam(':special_offer_description', $description);
                $query->bindParam(':discount_precentage', $discountPrecentage);
                $query->bindParam(':special_offer_start_date', $startDate);
                $query->bindParam(':special_offer_end_date', $endDate);
                $query = $this->db->prepare($sql);
                $query->execute();
            } catch (\PDOException $e) {
         
                $e->getMessage();
            } 
        }

        public function deleteSpecialOffer($productVariantId)
        {
            try {
                $sql = '
                    DELETE FROM special_offers
                    WHERE product_variant_id = :product_variant_id
                ';

                $query->bindParam(':product_variant_id', $productVariantId);
                $query = $this->db->prepare($sql);
                $query->execute();
            } catch (\PDOException $e) {
                
                $e->getMessage();
            }
        }

        function getSpecialOffer($productVariantId)
        {
            try {
                $sql = '
                    SELECT *
                    FROM special_offers
                    WHERE product_variant_id = :product_variant_id AND
                ';

                $query->bindParam(':product_variant_id', $productVariantId);
                $query = $this->db->prepare($sql);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch (\PDOException $e) {
                
                $e->getMessage();
            }
        }
        //get the special offer details by product variant id
        //from the product_variants_details view
        public function getSpecialOfferDetails($productVariantId)
        {
            try {
                $sql = '
                    SELECT *
                    FROM product_variants_details
                    WHERE product_variant_id = :product_variant_id
                ';
        
                // Prepare the query
                $query = $this->db->prepare($sql);
        
                // Bind the parameters
                $query->bindParam(':product_variant_id', $productVariantId);
        
                // Execute the query
                $query->execute();
        
                // Fetch the result
                $result = $query->fetch(PDO::FETCH_ASSOC);
        
                return $result;
            } catch (\PDOException $e) {
                // Handle the exception (e.g., log or throw a custom exception)
                echo $e->getMessage();
            }
        }
        
      
        function getAllSpecialOffers()
        { // get all special offers
         //product_variants_details is a view that contains the product variant details
          //junctioned with other relevant tables
         try {
                $sql = '
                SELECT *
                FROM special_offers

                    ';

                $query = $this->db->prepare($sql);
                $query->execute();
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } catch (\PDOException $e) {
                
                $e->getMessage();
            }
        }

        public function showSpecialOffer(){
            try {
                $sql = '
                    SELECT *
                    FROM special_offers AS sp
                    INNER JOIN product_variants_details AS pvd on so.product_variant_id = pvd.product_variants_details.product_variant_id
                    LIMIT 1
                ';

                $query = $this->db->prepare($sql);
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch (\PDOException $e) {
                
                $e->getMessage();
            }
        }


    }


