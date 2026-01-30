<?php
// public/reset.php
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/'); // Détruit le cookie du navigateur

echo "<h1>🛑 SESSION DÉTRUITE DE FORCE 🛑</h1>";
echo "<p>Le cycle de redirection est cassé.</p>";
echo "<a href='index.php'>Retourner à l'accueil</a>";
?>