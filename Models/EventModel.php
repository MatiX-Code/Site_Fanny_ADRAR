<?php

class EventModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * READ : Récupère tous les événements
     */
    public function getAllEvents(): array {
        $sql = "SELECT id_event, titre_event, date_event, affiche_event, resume_event, lien_event, id_user 
                FROM event";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * CREATE : Ajoute un nouvel événement en base de données avec l'affiche
     */
    public function createEvent(string $titre, string $datetime, string $resume, string $lien, int $id_user, ?string $affiche): bool {
        $sql = "INSERT INTO event (titre_event, date_event, resume_event, lien_event, id_user, affiche_event) 
                VALUES (:titre, :date_event, :resume, :lien, :id_user, :affiche)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindValue(':date_event', $datetime, PDO::PARAM_STR);
        $stmt->bindValue(':resume', $resume, PDO::PARAM_STR);
        $stmt->bindValue(':lien', $lien, PDO::PARAM_STR);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindValue(':affiche', $affiche, $affiche !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
        
        return $stmt->execute();
    }
    /**
     * UPDATE : Modifie un événement existant
     * 
     */
    // Récupère les données d'un seul spectacle pour le pré-remplir
    public function getEventById(int $id): ?array {
        
        $sql = "SELECT id_event, titre_event, date_event, affiche_event, resume_event, lien_event FROM event WHERE id_event = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null; // Retourne le tableau de données, ou null si introuvable

    }
    // Met à jour un spectacle existant
    public function updateEvent(int $id, string $titre, string $date_event, string $resume, string $lien, string $affiche): bool {
        
        // ⚠️ Adaptez les noms des colonnes selon votre base de données !
        $sql = "UPDATE event SET 
                titre_event = :titre, 
                date_event = :date_event, 
                resume_event = :resume, 
                lien_event = :lien, 
                affiche_event = :affiche 
                WHERE id_event = :id";
                
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':titre' => $titre,
            ':date_event' => $date_event,
            ':resume' => $resume,
            ':lien' => $lien,
            ':affiche' => $affiche,
            ':id' => $id
        ]);
    }

    /**
     * DELETE : Supprime un événement
     */
    public function deleteEvent(int $id_event): bool {
        $sql = "DELETE FROM event WHERE id_event = :id_event";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_event', $id_event, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}