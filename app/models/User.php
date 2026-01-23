<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // ENREGISTRER UN UTILISATEUR
    public function register($data){
        $this->db->query('INSERT INTO users (full_name, email, password, role) VALUES(:name, :email, :password, "customer")');
        // On force le rôle "customer" pour le front-office
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        return $this->db->execute();
    }

    // CONNEXION (LOGIN)
    public function login($email, $password){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Vérification du mot de passe hashé
        if($row){
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)){
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

    // COMPTER LES CLIENTS (Pour l'admin)
    public function countCustomers(){
        $this->db->query('SELECT COUNT(*) as count FROM users WHERE role = "customer"');
        $row = $this->db->single();
        return $row->count;
    }
    
    // LISTER LES CLIENTS (Pour l'admin)
    public function getCustomers(){
        $this->db->query('SELECT * FROM users WHERE role = "customer" ORDER BY created_at DESC');
        return $this->db->resultSet();
    }
    
    // SUPPRIMER CLIENT (Pour l'admin)
    public function deleteCustomer($id){
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- GESTION MOT DE PASSE OUBLIÉ ---

    // 1. Enregistrer le token de réinitialisation
    public function setResetToken($email, $token){
        // Le token expire dans 1 heure
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->db->query('UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email');
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        $this->db->bind(':email', $email);

        return $this->db->execute();
    }

    // 2. Vérifier si le token est valide (et pas expiré)
    public function getUserByToken($token){
        $this->db->query('SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()');
        $this->db->bind(':token', $token);
        
        return $this->db->single();
    }

    // 3. Mettre à jour le nouveau mot de passe
    public function resetPassword($email, $newPasswordHash){
        $this->db->query('UPDATE users SET password = :pass, reset_token = NULL, reset_expires = NULL WHERE email = :email');
        $this->db->bind(':pass', $newPasswordHash);
        $this->db->bind(':email', $email);

        return $this->db->execute();
    }

    // METTRE À JOUR LES DÉTAILS DU COMPTE (Nom, Email, Password)
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

    // METTRE À JOUR LES ADRESSES
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
}