<?php
namespace App\Models;

use Core\Model;

class Order extends Model
{
    // Liste complète (Admin) avec nom du client
    public function getAll()
    {
        $sql = "SELECT o.*, u.name as user_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Liste par Client (Compte)
    public function getByUserId($userId)
    {
        return $this->db->query("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC", ['uid' => $userId])->fetchAll();
    }
    
    // Détails d'une commande (Items)
    public function getOrderItems($orderId)
    {
        $sql = "SELECT oi.*, p.name, p.image 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = :oid";
        return $this->db->query($sql, ['oid' => $orderId])->fetchAll();
    }

    // Créer commande (Header)
    public function createOrderWithLocation($data)
    {
        // Générer une référence unique (ORD + Timestamp + Random)
        $ref = 'ORD-' . date('ymd') . '-' . rand(1000, 9999);

        $sql = "INSERT INTO orders (user_id, reference, name, email, phone, address, region, city, total, status, created_at) 
                VALUES (:uid, :ref, :name, :email, :phone, :addr, :reg, :city, :total, 'En attente', NOW())";
        
        $this->db->query($sql, [
            'uid' => $data['user_id'],
            'ref' => $ref,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'addr' => $data['address'],
            'reg' => $data['region'],
            'city' => $data['city'],
            'total' => $data['total']
        ]);
        
        return $this->db->lastInsertId();
    }

    // Ajouter items
    public function addItem($orderId, $prodId, $qty, $price)
    {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [$orderId, $prodId, $qty, $price]);
    }

    // Mise à jour statut (Admin)
    public function updateStatus($id, $status)
    {
        $this->db->query("UPDATE orders SET status = :status WHERE id = :id", ['status' => $status, 'id' => $id]);
    }

    // Mise à jour Ref Paystack
    public function updateReference($id, $paystackRef)
    {
        $this->db->query("UPDATE orders SET paystack_reference = :ref WHERE id = :id", ['ref' => $paystackRef, 'id' => $id]);
    }
}