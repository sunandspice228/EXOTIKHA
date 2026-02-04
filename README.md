# EXOTIKHA - Plateforme E-Commerce Premium 👙✨

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-purple.svg)
![Status](https://img.shields.io/badge/status-Production%20Ready-green.svg)

**Exotikha** est une boutique en ligne moderne et élégante dédiée à la lingerie, l'intimité et aux accessoires. Conçue pour le marché ouest-africain (Togo & Ghana), la plateforme propose une expérience utilisateur fluide, sécurisée et bilingue (Français/Anglais).

Le projet est construit sur une architecture **MVC (Modèle-Vue-Contrôleur)** personnalisée, légère et performante, sans framework lourd.

---

## 🚀 Fonctionnalités Clés

### 🛍️ Front-Office (Clients)
* **Catalogue Interactif :** Filtrage par catégories (Femme, Homme, Coffrets Cadeaux).
* **Multi-Langue :** Bascule instantanée entre Français 🇹🇬 et Anglais 🇬🇭.
* **Panier Dynamique :** Gestion des ajouts/suppressions sans rechargement excessif.
* **Wishlist :** Sauvegarde des produits favoris.
* **Blog Lifestyle :** Section journal pour le contenu éditorial.
* **Responsive Design :** Interface 100% mobile-friendly (Tailwind CSS).

### 🛡️ Back-Office (Administration)
* **Dashboard Analytique :** Vue d'ensemble des ventes, commandes récentes et stocks critiques.
* **Gestion des Produits :** CRUD complet, gestion des stocks, galeries d'images multiples.
* **Gestion des Commandes :** Suivi des statuts (En attente, Livré, Annulé).
* **Gestion des Utilisateurs :** Administration des clients et des membres du staff.
* **Sécurité Renforcée :** Gatekeeper Admin, protection des routes, et nettoyage des uploads.

---

## 🛠️ Stack Technique

* **Langage :** PHP 8+ (Orienté Objet Strict).
* **Base de Données :** MySQL / MariaDB.
* **Frontend :** HTML5, Tailwind CSS (CDN), Alpine.js (pour l'interactivité).
* **Architecture :** Custom MVC (Core, Controller, Model).
* **Serveur :** Apache (avec réécriture d'URL `.htaccess`).

---

## ⚙️ Installation & Configuration

### 1. Prérequis
* Serveur local (Wamp, Xampp, Laragon) ou Hébergement Web.
* PHP 8.0 ou supérieur.
* Module Apache `mod_rewrite` activé.

### 2. Installation des Fichiers
Clonez le projet ou extrayez l'archive dans votre dossier serveur.

**Structure recommandée pour la sécurité :**

/racine_serveur/
├── app/                 <-- Dossier protégé (Logique métier)
├── public/              <-- Dossier accessible (Index, Images, CSS)
│   ├── index.php
│   ├── .htaccess
│   └── uploads/
└── .htaccess            <-- Redirection vers public/
3. Base de Données
Créez une base de données nommée exotikha_db (ou autre).

Importez le fichier database.sql (fourni à la racine) via phpMyAdmin.

Compte Admin par défaut :

Créez un utilisateur via la page d'inscription du site (/users/register).

Allez dans la table users et changez la colonne role de customer à admin.

4. Configuration
Renommez ou éditez le fichier app/Config/config.php :


// Paramètres Base de Données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'exotikha_db');

// URL Racine (Sans slash à la fin)
// En local avec VirtualHost :
define('URLROOT', '[http://exotikha.local](http://exotikha.local)');
// Ou en production :
define('URLROOT', '[https://www.exotikha.com](https://www.exotikha.com)');

// Nom du Site
define('SITENAME', 'Exotikha');
🔒 Sécurité
Ce projet intègre plusieurs couches de sécurité natives :

Isolation du Cœur : Le dossier app/ est protégé contre l'accès direct via .htaccess et vérification de la constante APPROOT.

Protection XSS & SQL Injection : Utilisation systématique de bind() (PDO) et nettoyage des entrées (filter_input).

CSRF : Vérification des jetons sur les formulaires sensibles (POST).

Uploads Sécurisés : Renommage automatique des fichiers images et vérification des extensions MIME.

📂 Structure des Dossiers
exotikha/
├── app/
│   ├── Config/       # Configuration BDD et Constantes
│   ├── Controllers/  # Logique des pages (Admin, Shop, Users...)
│   ├── Core/         # Cœur du framework (Router, Database...)
│   ├── Helpers/      # Fonctions (Session, Upload, Langue...)
│   ├── Languages/    # Fichiers de traduction (fr.php, en.php)
│   ├── Models/       # Interaction BDD
│   └── Views/        # Fichiers HTML/PHP
├── public/
│   ├── css/
│   ├── js/
│   ├── uploads/      # Images produits (permissions 755 ou 777 requises)
│   └── index.php     # Point d'entrée unique
└── .htaccess         # Routage
🌍 Internationalisation (i18n)
Le système gère les langues via des fichiers de traduction situés dans app/Languages/. Pour ajouter une langue :

Créez un fichier (ex: es.php).

Ajoutez l'option dans le sélecteur du header.php.

Le LangHelper gérera automatiquement la session.

📝 Auteur
Lilmoussis - Lead Developer

Design & Développement Fullstack.

Intégration Paiement & Logistique.

📄 Licence
Ce projet est propriétaire. Toute reproduction ou distribution non autorisée est interdite. Copyright © 2024-2025 EXOTIKHA. Tous droits réservés.