<?php
class Post {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Récupérer tous les articles (du plus récent au plus ancien)
    public function getPosts(){
        $this->db->query("SELECT * FROM posts ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // Récupérer un seul article par ID (pour la page de lecture)
    public function getPostById($id){
        $this->db->query("SELECT * FROM posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Ajouter un article
    public function addPost($data){
        $this->db->query("INSERT INTO posts (title, category, content, image) VALUES (:title, :category, :content, :image)");
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':image', $data['image']);
        return $this->db->execute();
    }

    // Supprimer un article
    public function deletePost($id){
        $this->db->query("DELETE FROM posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}