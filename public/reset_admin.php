<?php
// public/reset_admin.php

$host = 'localhost';
$db   = 'exotikha';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    
    // Le nouveau mot de passe que vous voulez
    $new_password = "admin123"; 
    $email_target = "admin@exotikha.com";

    // Hachage
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // MISE À JOUR (UPDATE) au lieu de CRÉATION (INSERT)
    $sql = "UPDATE users SET password = :pwd WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([':pwd' => $hashed_password, ':email' => $email_target]);

    echo "<h1>✅ Mot de passe réinitialisé !</h1>";
    echo "<p>Vous pouvez vous connecter avec : <strong>admin123</strong></p>";
    echo "<a href='../admin/login'>Aller au login</a>";

} catch (\PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>