<?php
class Newsletter {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function addEmail($email){
        // 1. Vérifier si l'email existe déjà
        $this->db->query('SELECT id FROM newsletter WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();

        if($this->db->rowCount() > 0){
            return false; // Email déjà inscrit
        }

        // 2. Insérer le nouvel email
        $this->db->query('INSERT INTO newsletter (email) VALUES (:email)');
        $this->db->bind(':email', $email);
        
        return $this->db->execute();
    }
}