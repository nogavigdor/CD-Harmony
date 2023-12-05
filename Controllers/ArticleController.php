<?php
namespace Controllers;

use Models\ArticleModel;

class ArticleController 
{
    public function __construct() {
    }
    

    public function showArticlesByTag($tag)
    {
        try {
            $articleModel = new ArticleModel(); 
            $articles = $articlesModel->getArticlesByTag($tag); 
            include 'views/articles_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }


    public function showRecentArticles()
    {
        try {
            $articleModel = new ArticleModel(); // Create an instance of ArticleModel
            $articles = $articleModel->getRecentArticles(); // Call the method on the instance

            // Load the view to display the "New Releases" section
            include 'views/articles_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

	public function showArticleDetails($id)
    {
        try {
            $articleModel = new ArticleModel(); // Create an instance of ProductModel
            $article = $articleModel->getArticleDetails($id); // Call the method on the instance

            // Load the view to display the article details

            include 'views/article_details.php';
            
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
