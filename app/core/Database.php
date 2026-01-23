<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // Database Handler
    private $stmt; // Statement
    private $error;

    public function __construct() {
        // DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            die("Erreur de connexion BDD : " . $this->error);
        }
    }

    // Préparer la requête
    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Lier les valeurs
    public function bind($param, $value, $type = null) {
        if(is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Exécuter la requête
    public function execute() {
        return $this->stmt->execute();
    }

    // Récupérer un ensemble de résultats
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Récupérer une seule ligne
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Obtenir le nombre de lignes
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    // Dernier ID inséré
    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }
}