<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}
class SavedCard {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. ÉCRITURE
    // =========================================================

    // Ajouter une carte (Token Paystack)
    public function addCard($data){
        // 1. Vérifier si ce token existe déjà (évite les doublons)
        $this->db->query('SELECT id FROM saved_cards WHERE authorization_code = :code');
        $this->db->bind(':code', $data['authorization_code']);
        $this->db->single();

        if($this->db->rowCount() > 0){
            return true; // La carte existe déjà, c'est un succès
        }

        // 2. Insérer la nouvelle carte liée au CUSTOMER
        $this->db->query('INSERT INTO saved_cards (customer_id, authorization_code, card_type, last4, bank, email, created_at) 
                          VALUES (:uid, :code, :type, :last4, :bank, :email, NOW())');
        
        $this->db->bind(':uid', $data['customer_id']); // Changé de user_id à customer_id
        $this->db->bind(':code', $data['authorization_code']);
        $this->db->bind(':type', $data['card_type']); // ex: visa, mastercard
        $this->db->bind(':last4', $data['last4']);
        $this->db->bind(':bank', $data['bank']);
        $this->db->bind(':email', $data['email']);
        
        return $this->db->execute();
    }

    // =========================================================
    // 2. LECTURE
    // =========================================================

    // Récupérer les cartes d'un client
    public function getCustomerCards($customerId){
        $this->db->query('SELECT * FROM saved_cards WHERE customer_id = :uid ORDER BY created_at DESC');
        $this->db->bind(':uid', $customerId);
        return $this->db->resultSet();
    }

    // Récupérer une carte spécifique par son ID (pour le traitement du paiement)
    public function getCardById($id){
        $this->db->query('SELECT * FROM saved_cards WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // =========================================================
    // 3. SUPPRESSION
    // =========================================================

    // Supprimer une carte (Sécurisé : on vérifie que c'est bien la carte du client)
    public function deleteCard($id, $customerId){
        $this->db->query('DELETE FROM saved_cards WHERE id = :id AND customer_id = :uid');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $customerId);
        return $this->db->execute();
    }
}