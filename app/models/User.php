<?php
namespace App\Models;
use Core\Model;

class User extends Model {

    // 1. Inscription (Register)
    public function register($data) {
        // Hachage sécurisé du mot de passe
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (full_name, email, password, role, created_at) VALUES (:name, :email, :password, 'user', NOW())";
        
        return $this->db->query($sql, [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword
        ]);
    }

    // 2. Connexion (Login)
    public function login($email, $password) {
        $user = $this->findByEmail($email);
        
        // Si user existe ET que le mot de passe correspond au hash
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // 3. Trouver par Email (Utile pour vérifier doublons)
    public function findByEmail($email) {
        return $this->db->query("SELECT * FROM users WHERE email = :email", ['email' => $email])->fetch();
    }

    // 4. Trouver par ID (Pour le profil)
    public function findById($id) {
        return $this->db->query("SELECT * FROM users WHERE id = :id", ['id' => $id])->fetch();
    }

    // 5. Mettre à jour le profil
    public function updateProfile($id, $data) {
        $sql = "UPDATE users SET full_name = :name, phone = :phone, address = :address, city = :city WHERE id = :id";
        $this->db->query($sql, [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'id' => $id
        ]);
    }

    // 6. Récupérer l'historique des commandes d'un client
    public function getOrdersByUserId($userId) {
        return $this->db->query("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC", ['uid' => $userId])->fetchAll();
    }
}