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

        public function deleteSpecialOffer($productVariantId)
        {
            try {
                $sql = '
                    DELETE FROM special_offers
                    WHERE product_variant_id = :product_variant_id
                ';
                $query = $this->db->prepare($sql);
                $query->bindParam(':product_variant_id', $productVariantId);
                $query->execute();
                return true;
            } catch (\PDOException $e) {
                
                $e->getMessage();
            }
        }

        
public function getSpecialOfferBySpecialOfferId($specialOfferId){
    try {
        $sql = '
            SELECT *
            FROM special_offers
            WHERE special_offer_id = :special_offer_id
        ';

        $query = $this->db->prepare($sql);
        $query->bindParam(':special_offer_id', $specialOfferId);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        echo $e->getMessage();
    }

}

 //get special offer by product variant id which is active
 //  (current date is between start date and end date)
function getSpecialOffer($productVariantId)
{
    try {
        $currentDate = date('Y-m-d'); // Get current date

        $sql = '
            SELECT *
            FROM special_offers
            WHERE product_variant_id = :product_variant_id 
            AND start_date <= :current_date 
            AND end_date >= :current_date
            LIMIT 1
        ';

        $query = $this->db->prepare($sql);
        $query->bindParam(':product_variant_id', $productVariantId);
        $query->bindParam(':current_date', $currentDate);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (\PDOException $e) {
        // Handle exception
        echo $e->getMessage();
        return false;
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

        //show special offer on the homepage
        public function showSpecialOffer(){
            try {
                $sql = '
                SELECT so.*, pvd.* 
                FROM special_offers AS so
                INNER JOIN product_variants_details AS pvd ON so.product_variant_id = pvd.product_variant_id
                WHERE so.is_homepage = 1
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

        public function addSpecialOffer($variant_id, $title, $description, $isHomepage,  $discountSum, $startDate, $endDate)
        {
            try {
                $sql = '
                    INSERT INTO special_offers (product_variant_id, title, special_offer_description, is_homepage, discount_sum, special_offer_start_date, special_offer_end_date)
                    VALUES (:product_variant_id, :title, :special_offer_description, :is_homepage, :discount_sum, :special_offer_start_date, :special_offer_end_date)
                ';

                $query = $this->db->prepare($sql);
                $query->bindParam(':product_variant_id', $variant_id);
                $query->bindParam(':title', $title);
                $query->bindParam(':special_offer_description', $description);
                $query->bindParam(':is_homepage', $isHomepage);
                $query->bindParam(':discount_sum', $discountSum);
                $query->bindParam(':special_offer_start_date', $startDate);
                $query->bindParam(':special_offer_end_date', $endDate);
                $query->execute();
                return $this->db->lastInsertId();
            } catch (\PDOException $e) {
                
                throw new \PDOException($e->getMessage());
            }
        }

        //update the a special deal on a specific product to be on the homepage
        //while removing the homepage status from other special deals
        public function updateHomepage($productVariantId)
        {
            try {

                $sql = "UPDATE special_offers SET is_homepage = 0 WHERE is_homepage = 1";
               $query = $this->db->prepare($sql);
                $query->execute();
        
                $sql = "UPDATE special_offers SET is_homepage = 1 WHERE product_variant_id = :product_variant_id";
              $query = $this->db->prepare($sql);
              $query->bindParam(':product_variant_id', $productVariantId); // Redefine parameter name
                $query->execute();
                return true;
            } catch (\PDOException $e) {

                error_log('PDOException: ' . $e->getMessage());
                return $e->getMessage();
            }
        }


      public function getSpecialOffersByVariantId($variantId){
        try {
            $sql = '
                SELECT *
                FROM special_offers
                WHERE product_variant_id = :product_variant_id
            ';

            $query = $this->db->prepare($sql);
            $query->bindParam(':product_variant_id', $variantId);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
      }


      public function updateSpecialOffer($specialOfferId, $title, $description, $discount, $startDate, $endDate)
      {
          try {
              $sql = '
                  UPDATE special_offers
                  SET title = :title, special_offer_description = :special_offer_description, discount_sum = :discount_sum, special_offer_start_date = :special_offer_start_date, special_offer_end_date = :special_offer_end_date
                  WHERE special_offer_id = :special_offer_id
              ';
              $query = $this->db->prepare($sql);
              $query->bindParam(':special_offer_id', $specialOfferId);
              $query->bindParam(':title', $title);
              $query->bindParam(':special_offer_description', $description);
              $query->bindParam(':discount_sum', $discount);
              $query->bindParam(':special_offer_start_date', $startDate);
              $query->bindParam(':special_offer_end_date', $endDate);
              $result = $query->execute();
              return $result;
 
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
      } 

      public function isHomepage($specialOfferId){
        try {
            $sql = '
                SELECT is_homepage
                FROM special_offers
                WHERE special_offer_id = :special_offer_id
            ';

            $query = $this->db->prepare($sql);
            $query->bindParam(':special_offer_id', $specialOfferId);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
      }
      
 }

