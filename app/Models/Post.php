<?php
class Post {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. LECTURE (READ)
    // =========================================================

    // Récupérer tous les articles
    public function getPosts(){
        // CORRECTION ICI : users.full_name au lieu de users.name
        $this->db->query("SELECT posts.*, 
                                 users.full_name as author_name,
                                 posts.id as postId,
                                 users.id as userId
                          FROM posts 
                          LEFT JOIN users ON posts.user_id = users.id 
                          ORDER BY posts.created_at DESC");
        return $this->db->resultSet();
    }

    // Récupérer les derniers articles (Home Page)
    public function getLatestPosts($limit = 3){
        $this->db->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit); 
        return $this->db->resultSet();
    }

    // Récupérer un article par ID
    public function getPostById($id){
        // CORRECTION ICI : users.full_name
        $this->db->query("SELECT posts.*, users.full_name as author_name 
                          FROM posts 
                          LEFT JOIN users ON posts.user_id = users.id 
                          WHERE posts.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // =========================================================
    // 2. ÉCRITURE (CREATE / UPDATE / DELETE)
    // =========================================================

    public function addPost($data){
        // CORRECTION ICI : content au lieu de body
        $this->db->query("INSERT INTO posts (user_id, title, category, image, content, created_at) 
                          VALUES (:uid, :title, :category, :image, :content, NOW())");
        
        $this->db->bind(':uid', $_SESSION['user_id'] ?? 1); 
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':category', isset($data['category']) ? $data['category'] : 'General');
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':content', $data['body']); // Attention : ton contrôleur envoie 'body', on le map vers 'content'
        
        return $this->db->execute();
    }

    public function updatePost($data){
        // CORRECTION ICI : content au lieu de body
        $this->db->query("UPDATE posts SET title = :title, content = :content, image = :image WHERE id = :id");
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['body']); // Map body -> content
        $this->db->bind(':image', $data['image']);
        
        return $this->db->execute();
    }

    public function deletePost($id){
        // 1. Récupérer l'image
        $this->db->query("SELECT image FROM posts WHERE id = :id");
        $this->db->bind(':id', $id);
        $post = $this->db->single();

        if($post && !empty($post->image)){
            // Correction du chemin d'image
            $imagePath = '../public/img/' . $post->image;
            if(file_exists($imagePath)){
                unlink($imagePath);
            }
        }

        // 2. Supprimer en BDD
        $this->db->query("DELETE FROM posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}