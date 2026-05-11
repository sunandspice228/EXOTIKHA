<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}
class Newsletter {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // FRONT-OFFICE (CLIENT)
    // =========================================================

    // Ajouter un nouvel email
    public function addEmail($email){
        // 1. Vérifier si l'email existe déjà
        $this->db->query('SELECT id FROM newsletter WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();

        // Si l'email est déjà là, on renvoie false (ou on pourrait renvoyer true pour ne pas alerter le spammeur)
        if($this->db->rowCount() > 0){
            return false; 
        }

        // 2. Insérer le nouvel email
        $this->db->query('INSERT INTO newsletter (email, created_at) VALUES (:email, NOW())');
        $this->db->bind(':email', $email);
        
        return $this->db->execute();
    }

    // =========================================================
    // BACK-OFFICE (ADMIN)
    // =========================================================

    // Récupérer tous les inscrits
    public function getSubscribers(){
        $this->db->query("SELECT * FROM newsletter ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // Compter le nombre d'inscrits (Utile pour les stats du Dashboard)
    public function countSubscribers(){
        $this->db->query('SELECT COUNT(*) as total FROM newsletter');
        $row = $this->db->single();
        return $row->total;
    }

    // Supprimer un inscrit
    public function deleteSubscriber($id){
        $this->db->query("DELETE FROM newsletter WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}