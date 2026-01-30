<?php
// public/create_admin.php

// Configuration Base de données (Adaptez si besoin)
$host = 'localhost';
$db   = 'exotikha';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$pdo = new PDO($dsn, $user, $pass);

// Données de l'admin
$name = "Super Admin";
$email = "admin@exotikha.com";
$password = "admin123"; // <--- VOTRE MOT DE PASSE ADMIN

// Hachage
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insertion
$sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

if($stmt->execute([$name, $email, $hashed_password, 'admin'])) {
    echo "<h1>✅ Admin créé avec succès !</h1>";
    echo "<p>Email: <strong>$email</strong></p>";
    echo "<p>Mot de passe: <strong>$password</strong></p>";
    echo "<p>Supprimez ce fichier maintenant par sécurité.</p>";
} else {
    echo "❌ Erreur lors de la création.";
}
?>