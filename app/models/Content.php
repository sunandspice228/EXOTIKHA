<?php
namespace App\Models;
use Core\Model;

class Content extends Model {

    // Récupérer les articles de blog
    public function getLatestBlogPosts($limit = 3) {
        return $this->db->query("SELECT *, DATE_FORMAT(created_at, '%d') as day, DATE_FORMAT(created_at, '%b') as month FROM blog_posts ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    }

    // Récupérer les témoignages
    public function getTestimonials() {
        return $this->db->query("SELECT * FROM testimonials ORDER BY created_at DESC LIMIT 5")->fetchAll();
    }

    // Enregistrer une inscription Vente Privée
    public function savePrivateSaleLead($name, $phone) {
        $sql = "INSERT INTO private_sales (full_name, whatsapp_number) VALUES (?, ?)";
        $this->db->query($sql, [$name, $phone]);
    }
    
    // Récupérer les inscrits (Pour l'Admin)
    public function getPrivateSaleLeads() {
        return $this->db->query("SELECT * FROM private_sales ORDER BY created_at DESC")->fetchAll();
    }
}