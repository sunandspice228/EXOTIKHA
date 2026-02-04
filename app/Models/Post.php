<?php
class Post {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // LISTE
    public function getPosts(){
        $this->db->query("SELECT * FROM posts ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // UNITAIRE
    public function getPostById($id){
        $this->db->query("SELECT * FROM posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // AJOUT
    public function addPost($data){
        $this->db->query("INSERT INTO posts (title, title_fr, slug, content, content_fr, image, status, created_at) 
                          VALUES (:title, :title_fr, :slug, :content, :content_fr, :image, :status, NOW())");
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':title_fr', $data['title_fr']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':content_fr', $data['content_fr']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    // MODIFICATION
    public function updatePost($data){
        $this->db->query("UPDATE posts SET 
                          title = :title, 
                          title_fr = :title_fr,
                          slug = :slug, 
                          content = :content, 
                          content_fr = :content_fr,
                          image = :image,
                          status = :status
                          WHERE id = :id");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':title_fr', $data['title_fr']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':content_fr', $data['content_fr']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    // SUPPRESSION
    public function deletePost($id){
        $this->db->query("DELETE FROM posts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}