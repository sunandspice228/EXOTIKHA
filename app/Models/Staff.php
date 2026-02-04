<?php
class Staff {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // LECTURE (Filtrer par Rôle != 'customer')
    // =========================================================

    public function getAdmins(){
        // On exclut les clients classiques
        $this->db->query("SELECT *, CONCAT(first_name, ' ', last_name) as name 
                          FROM customers 
                          WHERE role != 'customer' 
                          ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    public function getAdminById($id){
        $this->db->query("SELECT *, CONCAT(first_name, ' ', last_name) as name 
                          FROM customers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function findAdminByEmail($email){
        $this->db->query("SELECT id FROM customers WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->single();
        return ($this->db->rowCount() > 0);
    }

    // =========================================================
    // ÉCRITURE (Dans la table customers)
    // =========================================================

    public function addAdmin($data){
        // Découpage du nom complet (ex: "Jean Dupont" -> "Jean" / "Dupont")
        $parts = explode(' ', $data['name'], 2);
        $firstName = $parts[0];
        $lastName = isset($parts[1]) ? $parts[1] : '';

        $this->db->query("INSERT INTO customers (first_name, last_name, email, password, role, image, created_at) 
                          VALUES (:fname, :lname, :email, :password, :role, :image, NOW())");
        
        $this->db->bind(':fname', $firstName);
        $this->db->bind(':lname', $lastName);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':image', $data['image']);

        return $this->db->execute();
    }

    public function updateAdmin($data){
        $query = "UPDATE customers SET first_name = :fname, last_name = :lname, email = :email, role = :role, image = :image";
        
        if(!empty($data['password'])){
            $query .= ", password = :password";
        }
        
        $query .= " WHERE id = :id";

        $this->db->query($query);
        
        // Découpage du nom
        $parts = explode(' ', $data['name'], 2);
        $firstName = $parts[0];
        $lastName = isset($parts[1]) ? $parts[1] : '';

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':fname', $firstName);
        $this->db->bind(':lname', $lastName);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':image', $data['image']);
        
        if(!empty($data['password'])){
            $this->db->bind(':password', $data['password']);
        }

        return $this->db->execute();
    }

    public function deleteAdmin($id){
        $this->db->query("DELETE FROM customers WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}