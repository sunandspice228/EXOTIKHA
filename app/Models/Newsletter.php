<?php
class Newsletter {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Ajouter un nouvel email (Front-Office)
    public function addEmail($email){
        // 1. Vérifier si l'email existe déjà
        $this->db->query('SELECT id FROM newsletter WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();

        if($this->db->rowCount() > 0){
            return false; // Email déjà inscrit
        }

        // 2. Insérer le nouvel email
        $this->db->query('INSERT INTO newsletter (email, created_at) VALUES (:email, NOW())');
        $this->db->bind(':email', $email);
        
        return $this->db->execute();
    }

    // Récupérer tous les inscrits (Pour l'Admin)
    public function getSubscribers(){
        $this->db->query("SELECT * FROM newsletter ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // Supprimer un inscrit (Pour l'Admin)
    public function deleteSubscriber($id){
        $this->db->query("DELETE FROM newsletter WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}