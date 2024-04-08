<?php

namespace Models;

use \DataAccess\DBConnector;

use PDO; 

class ArticleModel 
{
    private $db; 

    public function __construct()
    {
        $this->db = DBConnector::getInstance()->connectToDB();
    }



    public function getRecentArticles() {
        // Implement the logic to fetch recent releases here
        // For example:
        try {
            $sql='
            SELECT
            a.article_id,
            a.title,
            a.content,
            a.publish_date,
            a.update_date,
            u.first_name,
            u.last_name
            FROM
                articles a
            INNER JOIN users u ON u.user_id = a.user_id
            ORDER BY a.publish_date DESC
            LIMIT 3
            ';
            $query = $this->db->prepare($sql);
     
            $query->execute();
         //s   var_dump($query->queryString);
        //var_dump($tag);
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $ex) {
            die("Connection failed: " . $e->getMessage());
        } 
    }

	function getArticleDetails($id)
{
    try {
        $sql = '
        SELECT
        a.article_id,
        a.title,
        a.content,
        a.publish_date,
        a.update_date,
        u.first_name,
        u.last_name
        FROM
            articles a
        INNER JOIN users u ON u.user_id = a.user_id
        WHERE a.article_id = :id
        ';
        $query = $this->db->prepare($sql);
        $query->bindParam(':id', $id, \PDO::PARAM_INT);
        $query->execute();

        $result = $query->fetch(\PDO::FETCH_OBJ);

        return $result;
    } catch (\PDOException $ex) {
        die("Connection failed: " . $e->getMessage());
    } 

    
}

//for admin panel
public function getAllArticles() {
    try {
        $sql='
        SELECT
        a.article_id,
        a.title,
        a.content,
        a.publish_date,
        a.update_date,
        u.first_name,
        u.last_name
        FROM
            articles a
        INNER JOIN users u ON u.user_id = a.user_id
        ORDER BY a.publish_date DESC
        ';
        $query = $this->db->prepare($sql);
 
        $query->execute();
     //s   var_dump($query->queryString);
    //var_dump($tag);
        return $query->fetchAll(PDO::FETCH_OBJ);
    } catch (\PDOException $ex) {
        die("Connection failed: " . $e->getMessage());
    }
}
/*
 public function getInvoiceDetails($orderId){
        try {
            $sql = '
            SELECT * FROM invoice_details
            WHERE order_id = :order_id
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':order_id', $orderId, \PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $ex) {
            die("Connection failed: " . $e->getMessage());
        }
 }
*/

public function addArticle($title, $content, $publish_date, $update_date, $user_id)
{
    try {
        $sql = '
        INSERT INTO articles (title, content, publish_date, update_date, user_id)
        VALUES (:title, :content, :publish_date, :update_date, :user_id)
        ';
        $query = $this->db->prepare($sql);
        $query->bindParam(':title', $title, \PDO::PARAM_STR);
        $query->bindParam(':content', $content, \PDO::PARAM_STR);
        $query->bindParam(':publish_date', $publish_date, \PDO::PARAM_STR);
        $query->bindParam(':update_date', $update_date, \PDO::PARAM_STR);
        $query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
        $query->execute();
        return $this->db->lastInsertId();
    } catch (\PDOException $ex) {
        die("Connection failed: " . $e->getMessage());
    }

    }

    public function updateArticle($article_id, $title, $content, $update_date, $user_id)
    {
        try {
            $sql = '
            UPDATE articles
            SET title = :title, content = :content, update_date = :update_date, user_id = :user_id
            WHERE article_id = :article_id
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':article_id', $article_id, \PDO::PARAM_INT);
            $query->bindParam(':title', $title, \PDO::PARAM_STR);
            $query->bindParam(':content', $content, \PDO::PARAM_STR);
            $query->bindParam(':update_date', $update_date, \PDO::PARAM_STR);
            $query->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function deleteArticle($article_id)
    {
        try {
            $sql = '
            DELETE FROM articles
            WHERE article_id = :article_id
            ';
            $query = $this->db->prepare($sql);
            $query->bindParam(':article_id', $article_id, \PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (\PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

}

