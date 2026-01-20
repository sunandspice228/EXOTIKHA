<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    
    // ⚠️ ICI C'EST LE CHANGEMENT IMPORTANT : 'public' au lieu de 'private'
    public $pdo; 

    public function __construct() {
        // On récupère les infos depuis config.php
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Affiche les erreurs SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Résultats en tableau associatif
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // En cas d'erreur de connexion, on arrête tout
            die("Erreur de connexion Base de Données : " . $e->getMessage());
        }
    }

    // Méthode raccourcie pour faire des requêtes
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    // Méthode pour récupérer le dernier ID inséré (Utile pour CheckoutController)
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}