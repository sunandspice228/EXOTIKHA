<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}

class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. ADMIN : GESTION DES CLIENTS (Table 'customers')
    // =========================================================

    // Récupérer tous les clients
    public function getCustomers(){
        // On récupère les infos de la table 'customers'
        // On concatène Prénom + Nom pour créer un champ 'name' virtuel
        // On joint avec les commandes pour avoir les stats
        $this->db->query("SELECT c.*, 
                                 CONCAT(c.first_name, ' ', c.last_name) as name,
                                 COUNT(o.id) as order_count, 
                                 COALESCE(SUM(o.total_amount), 0) as total_spent 
                          FROM customers c 
                          LEFT JOIN orders o ON c.id = o.user_id 
                          GROUP BY c.id 
                          ORDER BY c.created_at DESC");
        return $this->db->resultSet();
    }

    // Récupérer un client par ID
    public function getUserById($id){
        $this->db->query("SELECT *, CONCAT(first_name, ' ', last_name) as name FROM customers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Récupérer l'historique des commandes d'un client
    public function getCustomerOrders($user_id){
        // Attention : Vérifiez si dans votre table 'orders', la colonne est 'user_id' ou 'customer_id'
        $this->db->query("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC");
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }

    // Supprimer un client
    public function deleteUser($id){
        $this->db->query("DELETE FROM customers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Compter les clients (Pour le Dashboard)
    public function countUsers() {
        $this->db->query("SELECT COUNT(*) as count FROM customers");
        $row = $this->db->single();
        return $row ? $row->count : 0;
    }

    // Alias pour éviter les erreurs si le contrôleur appelle countCustomers
    public function countCustomers() {
        return $this->countUsers();
    }

    // =========================================================
    // 2. AUTHENTIFICATION CLIENT (LOGIN / REGISTER)
    // =========================================================

    public function loginCustomer($email, $password){
        $this->db->query('SELECT *, CONCAT(first_name, " ", last_name) as name FROM customers WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if($row){
            if(password_verify($password, $row->password)){
                return $row;
            }
        }
        return false;
    }

    public function registerCustomer($data){
        // Séparation Prénom/Nom si le formulaire envoie juste "name"
        $parts = explode(' ', $data['name'], 2);
        $firstName = $parts[0];
        $lastName = isset($parts[1]) ? $parts[1] : '';

        $this->db->query('INSERT INTO customers (first_name, last_name, email, password, created_at) VALUES (:fname, :lname, :email, :password, NOW())');
        $this->db->bind(':fname', $firstName);
        $this->db->bind(':lname', $lastName);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); 

        return $this->db->execute();
    }

    public function findCustomerByEmail($email){
        $this->db->query('SELECT id FROM customers WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();
        return ($this->db->rowCount() > 0);
    }
}