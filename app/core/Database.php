<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    private $pdo;

    public function __construct() {
        try {
            $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4";
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("<h1>Erreur de connexion BDD</h1><p>" . $e->getMessage() . "</p>");
        }
    }

    // Exécuter une requête sécurisée
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("<h1>Erreur SQL</h1><p>" . $e->getMessage() . "</p><pre>$sql</pre>");
        }
    }

    // Récupérer le dernier ID inséré
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}