<?php
namespace Controllers;

use Models\ArticleModel;
use Services\SessionManager;

class ArticleController 
{
    private $articleModel;
    private $sessionManager;

    public function __construct() {
        // Create an instance of the ArticleModel class
        $this->articleModel = new ArticleModel();
        $this->sessionManager = new SessionManager();
        $this->sessionManager->startSession();


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

    public function showAllArticles()
    {
        try {
            $articleModel = new ArticleModel(); // Create an instance of ArticleModel
            $articles = $articleModel->getAllArticles(); // Call the method on the instance

            // Load the view to display all articles
            include 'views/admin/articles.php';
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

    public function showArticleForm()
    {
        try {
            if (!SessionManager::isAdmin()) {
                 // User is not logged in as an admin, redirect to the home page
                 SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
                 header('Location:'. BASE_URL.   '/');
                 exit;
            } 
            // Load the view to display the form for adding a new article
            include 'views/admin/add-article.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function addArticle()
    {
        try {
            $title = $_POST['articleTitle'];
            $content = $_POST['articleContent'];
            $publish_date = date('Y-m-d H:i:s');
            $update_date = date('Y-m-d H:i:s');
            $user_id = $_POST['userId'];

            // Call the method to add a new article
            $result = $this->articleModel->addArticle($title, $content, $publish_date, $update_date, $user_id);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Article added successfully']);
                exit();

            }
             else{
                    echo json_encode(['status' => 'error', 'message' => 'Article could not be added']);
                    exit();
                }
            
           
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
