<?php

class NewsletterModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Vérifie si l'email est déjà dans la base (OPTIMISÉ)
    public function emailExists(string $email): bool {
        // Bonne pratique : On ne sélectionne que le champ nécessaire (l'email)
        // et on ajoute "LIMIT 1" pour que la base s'arrête de chercher dès le premier trouvé.
        $sql = "SELECT email_abonne FROM abonne WHERE email_abonne = :email LIMIT 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        
        // fetch() renvoie la ligne si elle existe, ou "false" s'il n'y a rien.
        // Le (bool) transforme ce résultat en un vrai true ou false pour PHP.
        return (bool) $stmt->fetch();
    }

    // Ajoute l'email dans la base
    public function addEmail(string $email): bool {
        // Pas besoin de spécifier l'ID s'il est en Auto-Incrément dans votre BDD
        $sql = "INSERT INTO abonne (email_abonne, date_abonne) VALUES (:email, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['email' => $email]);
    }
}