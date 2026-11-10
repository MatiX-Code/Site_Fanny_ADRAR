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
     * DELETE : Supprime un événement
     */
    public function deleteEvent(int $id_event): bool {
        $sql = "DELETE FROM event WHERE id_event = :id_event";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_event', $id_event, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}