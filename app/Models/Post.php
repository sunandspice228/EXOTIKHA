<?php
class Post {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // LECTURE
    // =========================================================

    // Récupérer tous les articles (Admin & Blog Index)
    public function getPosts(){
        $this->db->query('SELECT * FROM posts ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // Récupérer un article par ID (Pour l'Admin Edit)
    public function getPostById($id){
        $this->db->query('SELECT * FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Récupérer un article par SLUG (Pour l'affichage Front-End SEO)
    public function getPostBySlug($slug){
        $this->db->query('SELECT * FROM posts WHERE slug = :slug');
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    // Récupérer les derniers articles (Pour la Home Page)
    public function getLatestPosts($limit = 3){
        $this->db->query('SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // =========================================================
    // ÉCRITURE (ADMIN)
    // =========================================================

    // Ajouter un article
    public function addPost($data){
        $this->db->query('INSERT INTO posts (title, slug, category, image, content) VALUES (:title, :slug, :category, :image, :content)');
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':image', $data['image']);
        // Correction : on utilise 'content' partout pour être cohérent avec la BDD
        $this->db->bind(':content', $data['content']); 

        return $this->db->execute();
    }

    // Mettre à jour un article
    public function updatePost($data){
        $this->db->query('UPDATE posts SET title = :title, slug = :slug, category = :category, image = :image, content = :content WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':content', $data['content']);

        return $this->db->execute();
    }

    // Supprimer un article
    public function deletePost($id){
        $this->db->query('DELETE FROM posts WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}