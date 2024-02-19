<?php
namespace Controllers;
use Models\ProductModel;
use Models\RecommendationModel;
use Models\UserModel;


class RecommendationController {

    private $productModel;
    private $recommendationModel;
    private $userModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->recommendationModel = new RecommendationModel();
        $this->userModel = new UserModel();
    }

    public function getRecommendations($userId) {
      
        if ($this->userModel->hasMadePurchase($userId)) {
            // Use user behavior-based recommendation
            return $this->recommendationModel->getRecommendationsBasedOnUserBehavior($userId);
        } else {
            // Use product-based recommendation
            return $this->recommendationtModel->getRecommendationsBasedOnProduct();
        }
    }

    public function getRecommendentProductsByTag($tag) {
        return $this->recommendationModel->getProductsByTags($tag);
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

}