<?php
namespace Controllers;

use Models\ArticleModel;
use Models\UserModel;
use Services\SessionManager;
use Services\Validator;
use Controllers\LoginController;

class ArticleController 
{
    private $articleModel;
    private $userModel;
    private $sessionManager;
    private $validator;
    private $loginController;

    public function __construct() {
        // Create an instance of the ArticleModel class
        $this->articleModel = new ArticleModel();
        $this->userModel = new UserModel();
        $this->validator = new Validator();
        $this->sessionManager = new SessionManager();
        $this->sessionManager->startSession();
        $this->loginController = new LoginController();


    }
    
/*This feature is not fully implemented yet. It is supposed to show articles by tag.
    public function showArticlesByTag($tag)
    {
        try {
            $articleModel = new ArticleModel(); 
            $articles = $articleModel->getArticlesByTag($tag); 
            include 'views/articles_section.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
*/

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
            if (!LoginController::isAdmin()) {
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

            $csrf_token = $_POST['csrf_token'];
            if (!SessionManager::validateCSRFToken($csrf_token)) {
                echo json_encode(['status' => 'error', 'message' => 'CSRF token validation failed']);
                exit();
            }

            if (!LoginController::isAdmin()) {
                // User is not logged in as an admin, redirect to the home page
                SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
                header('Location:'. BASE_URL.   '/');
                exit;
            }

            if (!($_SERVER['REQUEST_METHOD'] === 'POST')) 
            throw new \Exception('Invalid request method');

            $errType =[];

            $title = htmlspecialchars(trim($_POST['articleTitle']));

            if (empty($title)) {
                $errType['title'] = 'Title is required';
            }
            $validation=$this->validator->validateTitle($title);
            if ($validation !== null){
                $errType['title']=$validation;
            }
            
            $content = htmlspecialchars(trim($_POST['articleContent']));
            if (empty($content)) {
                $errType['content'] = 'Content is required';
            }
            $validation=$this->validator->validateText($content);
            if ($validation !== null){
                $errType['title']=$validation;
            }

            if (!empty($errType)) {
                http_response_code(400); // Set HTTP status code to indicate a client error
                echo json_encode($errType);
                exit(); // Terminate script execution
            }
            

            //since the article is being added, the publish date and update date are the same
            $publish_date = date('Y-m-d H:i:s');
            $update_date = date('Y-m-d H:i:s');
            $user_id = htmlspecialchars(trim($_POST['userId']));

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

    public function showEditArticleForm($id){
        try {
            
         
            if (!LoginController::isAdmin()) {
                // User is not logged in as an admin, redirect to the home page
                SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
                header('Location:'. BASE_URL.   '/');
                exit;
            } 
            // Load the view to display the form for editing an article
            $article = $this->articleModel->getArticleDetails($id);
            $usersAdminAndEditor = $this->userModel->getAdminANDEditorUsers();
            $currentUser=$this->loginController->getLoggedInUserId(); 
            include 'views/admin/edit-article.php';
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function updateArticle(){
        try {
            $csrf_token = $_POST['csrfToken'];
            $article_id = $_POST['articleId'];
            $title = $_POST['articleTitle'];
            $content = $_POST['articleContent'];
            $update_date = date('Y-m-d H:i:s');
            $user_id = $_POST['userId'];

            if (!SessionManager::validateCSRFToken($csrf_token)) {
                echo json_encode(['status' => 'error', 'message' => 'CSRF token validation failed']);
                exit();
            }

            if (!LoginController::isAdmin()) {
                // User is not logged in as an admin, redirect to the home page
                SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
                header('Location:'. BASE_URL.   '/');
                exit;
            }

            if (!($_SERVER['REQUEST_METHOD'] === 'POST'))
                throw new \Exception('Invalid request method');

            $errType = [];

            if (empty($title)) {
                $errType['title'] = 'Title is required';
            }
            else{
                $validation=$this->validator->validateTitle($title);
                if ($validation !== null){
                    $errType['title']=$validation;
                }
            }

            if (empty($content)) {
                $errType['content'] = 'Content is required';
            } 
            else {
                $validation=$this->validator->validateText($content);
                if ($validation !== null){
                    $errType['title']=$validation;
                }
            }
            
            if (empty($user_id)) {
                $errType['user_id'] = 'Author is required';
            }

            if (!empty($errType)) {
                http_response_code(400); // Set HTTP status code to indicate a client error
                echo json_encode($errType);
                exit(); // Terminate script execution
            }

            // Call the method to update the article
            $result = $this->articleModel->updateArticle($article_id, $title, $content, $update_date, $user_id);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Article updated successfully']);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Article could not be updated']);
                exit();
            }
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }

    }

    public function deleteArticle($id){
        try {

            if (!LoginController::isAdmin()) {
                // User is not logged in as an admin, redirect to the home page
                SessionManager::setSessionVariable('error_message', 'You are not authorized to view this page.');
                header('Location:'. BASE_URL.   '/');
                exit;
            }

            // Call the method to delete the article
            $result = $this->articleModel->deleteArticle($id);
            if (!$result) 
                throw new \Exception('Article could not be deleted');

            // Redirect to the page that displays all articles
            SessionManager::setSessionVariable('success_message', 'Article deleted successfully');
            header('Location:'. BASE_URL.   '/admin/articles');

       
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }



}
