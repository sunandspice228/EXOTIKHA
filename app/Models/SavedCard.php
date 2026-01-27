<?php
class SavedCard {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Ajouter une carte (Token Paystack)
    public function addCard($data){
        // 1. Vérifier si cette carte existe déjà pour cet utilisateur
        // (On évite les doublons basés sur le code d'autorisation unique)
        $this->db->query('SELECT id FROM saved_cards WHERE authorization_code = :code');
        $this->db->bind(':code', $data['authorization_code']);
        $this->db->single();

        if($this->db->rowCount() > 0){
            return true; // La carte existe déjà, on ne fait rien mais on retourne succès
        }

        // 2. Insérer la nouvelle carte
        $this->db->query('INSERT INTO saved_cards (user_id, authorization_code, card_type, last4, bank, email, created_at) 
                          VALUES (:uid, :code, :type, :last4, :bank, :email, NOW())');
        
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':code', $data['authorization_code']);
        $this->db->bind(':type', $data['card_type']); // ex: visa, mastercard
        $this->db->bind(':last4', $data['last4']);
        $this->db->bind(':bank', $data['bank']);
        $this->db->bind(':email', $data['email']);
        
        return $this->db->execute();
    }

    // Récupérer les cartes d'un utilisateur
    public function getUserCards($userId){
        $this->db->query('SELECT * FROM saved_cards WHERE user_id = :uid ORDER BY created_at DESC');
        $this->db->bind(':uid', $userId);
        return $this->db->resultSet();
    }

    // Récupérer une carte spécifique (pour vérifier avant paiement)
    public function getCardById($id){
        $this->db->query('SELECT * FROM saved_cards WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Supprimer une carte (Sécurisé : on vérifie que c'est bien la carte du user)
    public function deleteCard($id, $userId){
        $this->db->query('DELETE FROM saved_cards WHERE id = :id AND user_id = :uid');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $userId);
        return $this->db->execute();
    }
}