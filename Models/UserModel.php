<?php

/**
 * Classe UserModel : Représente le 'Modèle' dans l'architecture MVC.
 * Son rôle exclusif est de communiquer avec la base de données pour la table 'user'.
 */
class UserModel {
    // Propriété privée contenant l'instance de connexion à la base de données (PDO)
    private ?PDO $db;

    /**
     * Constructeur : Injection de dépendance.
     * On passe la connexion PDO existante au modèle au moment de son instanciation.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Recherche un utilisateur par son email (Utilisé pour l'authentification/connexion).
     */
    public function getUserByEmail(string $email) {
        // OPTIMISATION : On liste explicitement les colonnes au lieu de faire un SELECT *
        $sql = "SELECT id_user, nom_user, email_user, mdp_user, id_droit 
                FROM `user` 
                WHERE email_user = :email"; // Utilisation d'un marqueur nominatif (:email)
        
        // SÉCURITÉ : Préparation de la requête pour bloquer les failles d'injection SQL
        $data = $this->db->prepare($sql);
        
        // Exécution en liant la variable PHP $email au marqueur SQL :email
        $data->execute([
            ':email' => $email
        ]);
        
        // fetch() retourne une seule ligne (un seul utilisateur attendu)
        return $data->fetch();
    }

    /**
     * Crée un nouvel administrateur (Utilisé pour l'inscription/création de compte).
     * SÉCURITÉ : Le mot de passe ($hashedPassword) DOIT être haché avant d'être envoyé à cette méthode.
     */
    public function createUser(string $nom, string $email, string $hashedPassword, int $id_droit = 1) {
        // Requête d'insertion avec des marqueurs pour protéger toutes les données entrantes
        $sql = "INSERT INTO `user` (nom_user, email_user, mdp_user, id_droit) 
                VALUES (:nom, :email, :mdp, :id_droit)";
        
        // SÉCURITÉ : On prépare la requête (PDO)
        $request = $this->db->prepare($sql);
        
        // Exécution de la requête en associant chaque marqueur à sa valeur
        // Retourne true si l'insertion a réussi, false sinon
        return $request->execute([
            ':nom'      => $nom,
            ':email'    => $email,
            ':mdp'      => $hashedPassword, // On enregistre l'empreinte cryptée, jamais le mot de passe en clair
            ':id_droit' => $id_droit
        ]);
    }
}