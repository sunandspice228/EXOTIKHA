<?php
namespace App\Models;

use Core\Model;

class User extends Model
{
    // Trouver par Email (Login)
    public function findByEmail($email)
    {
        return $this->db->query("SELECT * FROM users WHERE email = :email", ['email' => $email])->fetch();
    }

    // Trouver par ID (Session)
    public function findById($id)
    {
        return $this->db->query("SELECT * FROM users WHERE id = :id", ['id' => $id])->fetch();
    }

    // Inscription
    public function create($data)
    {
        $sql = "INSERT INTO users (name, email, password, role, created_at) 
                VALUES (:name, :email, :pass, 'user', NOW())";
        
        $this->db->query($sql, [
            'name' => $data['name'],
            'email' => $data['email'],
            'pass' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
        
        return $this->db->lastInsertId();
    }

    // Mise à jour Profil (Dynamique)
    public function updateProfile($id, $data)
    {
        $sql = "UPDATE users SET 
                name = :name, 
                phone = :phone, 
                address = :address, 
                city = :city, 
                region = :region 
                WHERE id = :id";
        
        $this->db->query($sql, [
            'id' => $id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'region' => $data['region']
        ]);
    }

    // Mise à jour Mot de passe
    public function updatePassword($id, $newHash)
    {
        $this->db->query("UPDATE users SET password = :pass WHERE id = :id", [
            'id' => $id, 
            'pass' => $newHash
        ]);
    }
}