<?php
// public/install_admin.php

// On charge les outils
require_once '../app/config/config.php';
require_once '../app/core/Database.php';

use Core\Database;

try {
    $db = new Database();

    // 1. Paramètres du Super Admin
    $email = 'admin@exotikha.com';
    $password = 'admin123';
    $role = 'admin';
    $name = 'Super Admin';

    // 2. Hashage du mot de passe (Cryptage)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 3. On supprime l'ancien s'il existe (pour éviter les doublons)
    $db->query("DELETE FROM users WHERE email = :email", ['email' => $email]);

    // 4. On insère le nouveau
    $sql = "INSERT INTO users (name, email, password, role, created_at) 
            VALUES (:name, :email, :pass, :role, NOW())";
    
    $db->query($sql, [
        'name' => $name,
        'email' => $email,
        'pass' => $hashed_password,
        'role' => $role
    ]);

    echo "<div style='font-family:sans-serif; text-align:center; padding:50px; color:green;'>";
    echo "<h1>✅ Admin Créé avec succès !</h1>";
    echo "<p>Email : <b>$email</b></p>";
    echo "<p>Mot de passe : <b>$password</b></p>";
    echo "<br><a href='admin/login' style='background:#333; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Aller à la connexion</a>";
    echo "</div>";

} catch (Exception $e) {
    echo "<h1>Erreur :</h1> " . $e->getMessage();
}