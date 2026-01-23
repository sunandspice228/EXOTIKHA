<?php
class SavedCard {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Ajouter une carte (Token Paystack)
    public function addCard($data){
        $this->db->query('INSERT INTO saved_cards (user_id, authorization_code, card_type, last4, bank, email) VALUES (:uid, :code, :type, :last4, :bank, :email)');
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':code', $data['authorization_code']);
        $this->db->bind(':type', $data['card_type']);
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

    public function getCardById($id){
        $this->db->query('SELECT * FROM saved_cards WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function deleteCard($id, $userId){
        $this->db->query('DELETE FROM saved_cards WHERE id = :id AND user_id = :uid');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $userId);
        return $this->db->execute();
    }
}