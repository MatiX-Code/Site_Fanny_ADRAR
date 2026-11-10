<?php

class Database {
    // Propriétés privées (Encapsulation) typées
    private string $host;
    private string $dbname;
    private string $user;
    private string $pass;
    private ?PDO $pdo = null; // Le "?" signifie que la variable peut contenir un objet PDO ou être "null"

    // Le constructeur s'exécute automatiquement quand on crée un objet "Database"
    public function __construct() {
        // 1. Définir le chemin vers le fichier .env
        // __DIR__ correspond au dossier 'Models', on remonte d'un cran ('/../') pour trouver '.env'
        $envFile = __DIR__ . '/../.env';

        // 2. Sécurité : Vérifier que le fichier existe avant de le lire
        if (file_exists($envFile)) {
            // Lecture du fichier .env
            $env = parse_ini_file($envFile);
            
            // Assignation des variables
            $this->host   = $env['DB_HOST'];
            $this->dbname = $env['DB_NAME'];
            $this->user   = $env['DB_USER'];
            $this->pass   = $env['DB_PASS'];
        } else {
            die("Erreur critique : Le fichier de configuration est introuvable.");
        }
    }

    // Méthode pour obtenir la connexion à la base de données
    public function getConnection() {
        // On ne crée la connexion que si elle n'existe pas déjà (optimisation)
        if ($this->pdo === null) {
            try {
                // Création du DSN (Data Source Name)
                $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4";
                
                // Instanciation de l'objet PDO
                $this->pdo = new PDO($dsn, $this->user, $this->pass);
                
                // Sécurité et gestion des erreurs :
                // - ERRMODE_EXCEPTION : Déclenche une exception en cas d'erreur SQL
                // - FETCH_ASSOC : Retourne les résultats sous forme de tableau associatif
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                // SÉCURITÉ : Ne jamais afficher $e->getMessage() en production !
                // Cela donnerait des indices aux pirates sur la structure de votre BDD.
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
            }
        }
        
        return $this->pdo;
    }
}