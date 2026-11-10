<?php

class EventController {
    private EventModel $eventModel;

    public function __construct(EventModel $eventModel) {
        $this->eventModel = $eventModel;
    }

    /**
     * READ : Récupère les événements pour le tableau HTML de l'administration
     */
    public function listEvents(): array {
        return $this->eventModel->getAllEvents();
    }

    /**
     * API : Exporte les événements au format JSON pour la modale JavaScript
     */
    public function exportEventsToJson(): void {
        $spectacles = $this->eventModel->getAllEvents();
        $eventsForCalendar = [];
        
        foreach ($spectacles as $spectacle) {
            $dateObj = new DateTime($spectacle['date_event']);

            $eventsForCalendar[] = [
                'id' => $spectacle['id_event'],
                'title' => $spectacle['titre_event'],
                'start' => $spectacle['date_event'], 
                'color' => '#64DFDF',
                'textColor' => '#111111',
                
                // --- Données étendues pour la modale (extendedProps) ---
                'heure' => $dateObj->format('H\hi'), 
                'description' => $spectacle['resume_event'],
                'lien_billetterie' => $spectacle['lien_event'],
                'affiche' => $spectacle['affiche_event'] // Chemin de l'image pour le JS
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($eventsForCalendar);
        exit;
    }

    /**
     * CREATE : Traitement ultra-sécurisé du formulaire d'ajout
     */
    public function addEvent(array $postData): string {
        $id_user = $_SESSION['id_user'] ?? 0;
        
        if ($id_user === 0) {
            return "Erreur : Vous devez être connecté pour ajouter un événement.";
        }

        // 1. Nettoyage
        $titre = trim($postData['titre_event'] ?? '');
        $date_saisie = trim($postData['date_event'] ?? ''); 
        $heure_saisie = trim($postData['heure_event'] ?? ''); 
        $resume = trim($postData['resume_event'] ?? '');
        $lien = trim($postData['lien_event'] ?? '');
        
        $titre = htmlspecialchars($titre, ENT_QUOTES, 'UTF-8');
        $resume = htmlspecialchars($resume, ENT_QUOTES, 'UTF-8');
        $lien = filter_var($lien, FILTER_SANITIZE_URL);

        if (empty($titre) || empty($date_saisie) || empty($heure_saisie) || empty($resume)) {
            return "Erreur : Tous les champs obligatoires doivent être remplis.";
        }

        // 2. Formatage DATETIME
        $datetime_mysql = $date_saisie . ' ' . $heure_saisie . ':00';
        $dateObj = DateTime::createFromFormat('Y-m-d H:i:s', $datetime_mysql);
        
        if (!$dateObj || $dateObj->format('Y-m-d H:i:s') !== $datetime_mysql) {
            return "Erreur : Le format de la date ou de l'heure est invalide.";
        }

        // 3. Gestion sécurisée de l'image
        $chemin_affiche = null;

        if (isset($_FILES['affiche_event']) && $_FILES['affiche_event']['error'] === UPLOAD_ERR_OK) {
            // ATTENTION : Créez bien le dossier "assets/uploads/" à la racine de votre projet !
            $dossier_cible = 'public/assets/uploads/'; 
            $nom_fichier = $_FILES['affiche_event']['name'];
            $chemin_temporaire = $_FILES['affiche_event']['tmp_name'];
            $taille_fichier = $_FILES['affiche_event']['size'];

            if ($taille_fichier > 2000000) {
                return "Erreur : L'image est trop lourde (2 Mo max).";
            }

            $extensions_autorisees = ['jpg', 'jpeg', 'png', 'webp'];
            $extension_fichier = strtolower(pathinfo($nom_fichier, PATHINFO_EXTENSION));

            if (!in_array($extension_fichier, $extensions_autorisees)) {
                return "Erreur : Seuls les formats JPG, PNG et WEBP sont autorisés.";
            }

            // Cryptage du nom du fichier
            $nouveau_nom = uniqid('affiche_') . '.' . $extension_fichier;
            $chemin_final = $dossier_cible . $nouveau_nom;

            if (move_uploaded_file($chemin_temporaire, $chemin_final)) {
                $chemin_affiche = $chemin_final;
            } else {
                return "Erreur : Impossible d'enregistrer l'image.";
            }
        }

        // 4. Envoi au Modèle
        $isCreated = $this->eventModel->createEvent($titre, $datetime_mysql, $resume, $lien, $id_user, $chemin_affiche);

        if ($isCreated) {
            return "Succès : La date de spectacle a bien été ajoutée.";
        } else {
            return "Erreur : Impossible d'ajouter le spectacle.";
        }
    }

    /**
     * DELETE : Suppression
     */
    public function deleteEvent(int $id_event): string {
        if ($id_event <= 0) return "Erreur : Identifiant invalide.";
        return $this->eventModel->deleteEvent($id_event) ? "Succès : Spectacle supprimé." : "Erreur de suppression.";
    }
}