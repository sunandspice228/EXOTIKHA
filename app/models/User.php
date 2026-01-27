<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. AUTHENTIFICATION
    // =========================================================

    // ENREGISTRER UN UTILISATEUR
    public function register($data){
        $this->db->query('INSERT INTO users (full_name, email, password, role) VALUES(:name, :email, :password, :role)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        
        // Si le rôle n'est pas spécifié, on met "customer" par défaut
        $role = isset($data['role']) ? $data['role'] : 'customer';
        $this->db->bind(':role', $role);

        return $this->db->execute();
    }

    // CONNEXION (LOGIN)
    public function login($email, $password){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Vérification du mot de passe hashé
        if($row){
            if(password_verify($password, $row->password)){
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // VÉRIFIER SI L'EMAIL EXISTE DÉJÀ
    public function findUserByEmail($email){
        $this->db->query('SELECT id FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $this->db->single();

        return ($this->db->rowCount() > 0);
    }

    // RÉCUPÉRER UTILISATEUR PAR ID
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // VÉRIFIER LE MOT DE PASSE ACTUEL (Sécurité avant changement)
    public function checkPassword($id, $password){
        $this->db->query('SELECT password FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        
        if($row){
            return password_verify($password, $row->password);
        }
        return false;
    }

    // =========================================================
    // 2. GESTION ADMIN (Listes et Stats)
    // =========================================================

    // COMPTER LES CLIENTS UNIQUEMENT
    public function countCustomers(){
        $this->db->query('SELECT COUNT(*) as count FROM users WHERE role = "customer"');
        $row = $this->db->single();
        return $row->count;
    }

    // COMPTER TOUS LES UTILISATEURS (Admin + Clients)
    public function countUsers(){
        $this->db->query("SELECT COUNT(*) as count FROM users");
        $row = $this->db->single();
        return $row->count;
    }
    
    // LISTER LES CLIENTS
    public function getCustomers(){
        $this->db->query('SELECT * FROM users WHERE role = "customer" ORDER BY created_at DESC');
        return $this->db->resultSet();
    }
    
    // SUPPRIMER CLIENT
    public function deleteCustomer($id){
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // =========================================================
    // 3. MOT DE PASSE OUBLIÉ
    // =========================================================

    public function setResetToken($email, $token){
        // Le token expire dans 1 heure
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->db->query('UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email');
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        $this->db->bind(':email', $email);

        return $this->db->execute();
    }

    public function getUserByToken($token){
        $this->db->query('SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()');
        $this->db->bind(':token', $token);
        return $this->db->single();
    }

    public function resetPassword($email, $newPasswordHash){
        $this->db->query('UPDATE users SET password = :pass, reset_token = NULL, reset_expires = NULL WHERE email = :email');
        $this->db->bind(':pass', $newPasswordHash);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    // =========================================================
    // 4. PROFIL & ADRESSES
    // =========================================================

    public function updateDetails($id, $data){
        // Si le mot de passe est fourni, on met à jour tout, sinon juste nom/email
        if(!empty($data['password'])){
            $this->db->query('UPDATE users SET full_name = :name, email = :email, password = :pass WHERE id = :id');
            $this->db->bind(':pass', $data['password']);
        } else {
            $this->db->query('UPDATE users SET full_name = :name, email = :email WHERE id = :id');
        }
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function updateAddress($id, $data, $type = 'billing'){
        if($type == 'billing'){
            $sql = 'UPDATE users SET billing_phone = :phone, billing_address = :address, billing_city = :city, billing_region = :region WHERE id = :id';
        } else {
            $sql = 'UPDATE users SET shipping_phone = :phone, shipping_address = :address, shipping_city = :city, shipping_region = :region WHERE id = :id';
        }

        $this->db->query($sql);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':region', $data['region']);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // =========================================================
    // 5. GESTION DES CARTES (Compatible avec SavedCard.php)
    // =========================================================

    // NOTE : Idéalement, passe par le modèle SavedCard, mais je laisse ceci pour compatibilité
    // J'ai renommé la table en 'saved_cards' pour matcher ton autre modèle.

    public function addCard($data){
        // Vérifier doublon
        $this->db->query("SELECT id FROM saved_cards WHERE user_id = :uid AND last4 = :last4 AND card_type = :ctype");
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':last4', $data['last4']);
        $this->db->bind(':ctype', $data['card_type']);
        
        if($this->db->single()) return true;

        $this->db->query("INSERT INTO saved_cards (user_id, authorization_code, email, last4, card_type, bank, created_at) 
                          VALUES (:uid, :auth, :email, :last4, :ctype, :bank, NOW())");
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':auth', $data['authorization_code']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':last4', $data['last4']);
        $this->db->bind(':ctype', $data['card_type']);
        $this->db->bind(':bank', $data['bank']);
        
        return $this->db->execute();
    }

    public function getCards($user_id){
        $this->db->query("SELECT * FROM saved_cards WHERE user_id = :uid ORDER BY created_at DESC");
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }

    public function deleteCard($card_id, $user_id){
        $this->db->query("DELETE FROM saved_cards WHERE id = :id AND user_id = :uid");
        $this->db->bind(':id', $card_id);
        $this->db->bind(':uid', $user_id);
        return $this->db->execute();
    }
    // ... (Code existant du User.php)

    // MISE À JOUR DE L'AVATAR
    public function updateAvatar($id, $imagePath){
        $this->db->query('UPDATE users SET image = :image WHERE id = :id');
        $this->db->bind(':image', $imagePath);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
