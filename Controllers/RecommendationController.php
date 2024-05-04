<?php
namespace Controllers;
use Models\ProductModel;
use Models\RecommendationModel;
use Models\UserModel;
use Services\SessionManager;
use Stripe\BillingPortal\Session;

class RecommendationController {

    private $productModel;
    private $recommendationModel;
    private $userModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->recommendationModel = new RecommendationModel();
        $this->userModel = new UserModel();
    }

       /**
     * Get recommended products for the product details page
     * @param $productId
     * @return array
     */
    public function getRecommendationsOnProductPage($productId) {
        $recommendedProductsByTags = [];
        $recommendedProductsByTags = $this->recommendationModel->getRecommendationBasedOnSharedTags($productId);
        return $recommendedProductsByTags;
    }

    
    public function getRecommendentProductsByTag($tag) {
        return $this->recommendationModel->getProductsByTags($tag);
    }


    /**
     * Get recommended products for the product details page
     * @param $productId
     * @return array
     */
    public function getRecommendationsOnCartPage() {
    // Get the cart from the session
    if (SessionManager::isVar('cart')){
        $cart = SessionManager::getSessionVariable('cart');
    }
    else {
        exit('Cart not found');
    }

// Extract product variant IDs from the cart (only the product variant Ids, without additional keys)
foreach ($cart as $product_variant_id => $cartItem) {
    // Check if the current item is one of the product items
    if (is_numeric($product_variant_id)) {
        // Add the product variant ID to the array
        $productVariantIds[] = $product_variant_id;
    }
}
    // Build a comma-separated list of product IDs for the SQL query
    $productIdsStr = implode(',', $productVariantIds);


        $recommendedProductsByTags = $this->recommendationModel->getRecommendationBasedOnSharedTagsInCart($productIdsStr);
        return $recommendedProductsByTags;
    }



    /**
     * For future implementation
     * Recommend products based on user behavior
     * Get recommended products for the home page
     * @return array
     */
    public function getRecommendations($userId) {
      
        if ($this->userModel->hasMadePurchase($userId)) {
            // Use user behavior-based recommendation
            return $this->recommendationModel->getRecommendationsBasedOnUserBehavior($userId);
        } else {
            // Use product-based recommendation
            return $this->recommendationtModel->getRecommendationsBasedOnProduct();
        }
    }



}