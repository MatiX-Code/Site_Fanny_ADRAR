<?php

class UserModel {
    private ?PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Recherche un utilisateur par son email (Pour la connexion)
     */
    public function getUserByEmail(string $email) {
        // BONNE PRATIQUE : On liste explicitement les colonnes dont on a besoin
        $sql = "SELECT id_user, nom_user, email_user, mdp_user, id_droit 
                FROM `user` 
                WHERE email_user = :email";
        
        $data = $this->db->prepare($sql);
        
        $data->execute([
            ':email' => $email
        ]);
        
        return $data->fetch();
    }

    /**
     * Crée un nouvel administrateur (Pour l'inscription)
     */
    public function createUser(string $nom, string $email, string $hashedPassword, int $id_droit = 1) {
        $sql = "INSERT INTO `user` (nom_user, email_user, mdp_user, id_droit) 
                VALUES (:nom, :email, :mdp, :id_droit)";
        
        $request = $this->db->prepare($sql);
        
        return $request->execute([
            ':nom'      => $nom,
            ':email'    => $email,
            ':mdp'      => $hashedPassword, 
            ':id_droit' => $id_droit
        ]);
    }
}