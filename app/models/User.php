<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. SECTION ADMINISTRATEURS (Table 'users')
    // =========================================================

    // Login Admin
    public function login($email, $password){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if($row){
            if(password_verify($password, $row->password)){
                return $row;
            }
        }
        return false;
    }

    // Enregistrer un nouvel Admin
    public function register($data){
        $this->db->query('INSERT INTO users (name, email, password, role, created_at) VALUES (:name, :email, :password, :role, NOW())');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); // Assurez-vous que le password est haché dans le Controller
        $this->db->bind(':role', 'admin');

        return $this->db->execute();
    }

    // Trouver Admin par Email
    public function findUserByEmail($email){
        $this->db->query('SELECT id FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();
        return ($this->db->rowCount() > 0);
    }

    // Récupérer Admin par ID
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Liste des Admins
    public function getAdmins(){
        $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // Supprimer un Admin
    public function deleteUser($id){
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // =========================================================
    // 2. SECTION CLIENTS (Table 'customers')
    // =========================================================

    // Login Client
    public function loginCustomer($email, $password){
        $this->db->query('SELECT * FROM customers WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if($row){
            if(password_verify($password, $row->password)){
                return $row;
            }
        }
        return false;
    }

    // Enregistrer un Client
    public function registerCustomer($data){
        // Séparation Prénom/Nom si un seul champ 'name' est envoyé
        $parts = explode(' ', $data['name'], 2);
        $firstName = $parts[0];
        $lastName = isset($parts[1]) ? $parts[1] : '';

        $this->db->query('INSERT INTO customers (first_name, last_name, email, password, created_at) VALUES (:fname, :lname, :email, :password, NOW())');
        $this->db->bind(':fname', $firstName);
        $this->db->bind(':lname', $lastName);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); // Haché dans le controller

        return $this->db->execute();
    }

    // Trouver Client par Email
    public function findCustomerByEmail($email){
        $this->db->query('SELECT id FROM customers WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();
        return ($this->db->rowCount() > 0);
    }

    // Récupérer Client par ID
    public function getCustomerById($id){
        $this->db->query('SELECT * FROM customers WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // --- MISE À JOUR PROFIL (NOM, EMAIL, PASSWORD) ---
    public function updateCustomerProfile($id, $data){
        // Gestion du nom complet
        $parts = explode(' ', $data['name'], 2);
        $firstName = $parts[0];
        $lastName = isset($parts[1]) ? $parts[1] : '';

        // Si un nouveau mot de passe est fourni
        if(!empty($data['password'])){
            $this->db->query('UPDATE customers SET first_name = :fname, last_name = :lname, email = :email, password = :pass WHERE id = :id');
            $this->db->bind(':pass', $data['password']);
        } else {
            $this->db->query('UPDATE customers SET first_name = :fname, last_name = :lname, email = :email WHERE id = :id');
        }

        $this->db->bind(':fname', $firstName);
        $this->db->bind(':lname', $lastName);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // --- MISE À JOUR ADRESSE (LIVRAISON OU FACTURATION) ---
    // Dans app/Models/User.php

// Dans app/Models/User.php

// AJOUTEZ AUSSI CETTE MÉTHODE pour récupérer les infos complètes (User + Customer)
// Cette méthode fusionne les infos de 'users' et 'customers'
// Dans app/Models/User.php

// 1. Récupérer les infos du client connecté
public function getUserInfo($id){
    // Plus de JOIN, on prend tout directement dans customers
    $this->db->query("SELECT * FROM customers WHERE id = :id");
    $this->db->bind(':id', $id);
    
    $row = $this->db->single();
    
    // Petite astuce : on map les champs pour que la vue (account.php) s'y retrouve
    // La vue attend souvent 'shipping_phone' mais la table a 'phone'
    if($row){
        $row->shipping_phone = $row->phone;
        $row->shipping_address = $row->address;
        $row->shipping_city = $row->city;
        $row->shipping_region = $row->region;
    }
    
    return $row;
}

// 2. Mettre à jour l'adresse (Update direct)
// Dans app/Models/User.php

public function updateCustomerAddress($customerId, $data){
    // Requête SQL sur la table customers
    $this->db->query("UPDATE customers 
                      SET phone = :phone, 
                          address = :address, 
                          city = :city, 
                          region = :region 
                      WHERE id = :id");

    $this->db->bind(':id', $customerId);

    // MAPPING SÉCURISÉ : On lie les champs du formulaire aux colonnes de la BDD
    // Le formulaire envoie 'shipping_phone', la BDD veut ':phone'
    // L'opérateur '??' évite l'erreur si la clé n'existe pas
    $this->db->bind(':phone', $data['shipping_phone'] ?? null);
    $this->db->bind(':address', $data['shipping_address'] ?? null);
    
    // Pour la ville et la région, on met des valeurs par défaut si vide
    $this->db->bind(':city', !empty($data['shipping_city']) ? $data['shipping_city'] : 'Accra');
    $this->db->bind(':region', !empty($data['shipping_region']) ? $data['shipping_region'] : 'Greater Accra');

    try {
        return $this->db->execute();
    } catch(PDOException $e) {
        // En cas d'erreur, on l'affiche pour déboguer
        die('Erreur Update : ' . $e->getMessage());
    }
}

// 3. Mettre à jour les infos perso (Nom, Email, etc.)
public function updateProfile($customerId, $data){
    // Mise à jour de base
    $query = "UPDATE customers SET first_name = :name, email = :email";
    
    // Si un nouveau mot de passe est fourni
    if(!empty($data['password'])){
        $query .= ", password = :password";
    }
    
    $query .= " WHERE id = :id";
    
    $this->db->query($query);
    
    $this->db->bind(':id', $customerId);
    // On sépare le nom complet en Prénom/Nom si besoin, ou on met tout dans first_name pour simplifier
    $this->db->bind(':name', $data['name']); 
    $this->db->bind(':email', $data['email']);
    
    if(!empty($data['password'])){
        $this->db->bind(':password', $data['password']);
    }

    return $this->db->execute();
}

    // Liste des clients (Admin)
    public function getCustomers(){
        $this->db->query('SELECT *, CONCAT(first_name, " ", last_name) as full_name FROM customers ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    // Supprimer un client
    public function deleteCustomer($id){
        $this->db->query('DELETE FROM customers WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // =========================================================
    // 3. STATISTIQUES (DASHBOARD)
    // =========================================================

    public function countCustomers(){
        $this->db->query('SELECT COUNT(*) as count FROM customers');
        $row = $this->db->single();
        return $row->count;
    }

    public function countAdmins(){
        $this->db->query('SELECT COUNT(*) as count FROM users');
        $row = $this->db->single();
        return $row->count;
    }
}