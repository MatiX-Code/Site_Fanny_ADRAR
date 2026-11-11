<?php

class ContactController {
    
    public function sendEmail(array $postData): string {
        // 1. SÉCURITÉ ABSOLUE : Nettoyage contre les failles XSS (Injections HTML/JS)
        $nom = htmlspecialchars(trim($postData['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $prenom = htmlspecialchars(trim($postData['firstname'] ?? ''), ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars(trim($postData['telephone'] ?? ''), ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars(trim($postData['message'] ?? ''), ENT_QUOTES, 'UTF-8');
        
        // Nettoyage spécifique pour l'email
        $email = filter_var(trim($postData['email'] ?? ''), FILTER_SANITIZE_EMAIL);

        // 2. VALIDATION : Vérification des champs obligatoires
        if (empty($nom) || empty($prenom) || empty($email) || empty($message)) {
            return "Erreur : Tous les champs obligatoires doivent être remplis.";
        }

        // Vérification approfondie du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Erreur : L'adresse email fournie n'est pas valide.";
        }

        // 3. PRÉPARATION DE L'EMAIL
        $destinataire = "adressebellemaman@gmail.com"; // REMPLACEZ PAR LA VRAIE ADRESSE
        $sujet = "Nouveau message de $prenom $nom depuis le site web";
        
        // Construction du corps du message
        $contenu = "Vous avez reçu un nouveau message depuis le formulaire de contact.\n\n";
        $contenu .= "Nom : $nom\n";
        $contenu .= "Prénom : $prenom\n";
        $contenu .= "Email : $email\n";
        $contenu .= "Téléphone : " . ($telephone ? $telephone : "Non renseigné") . "\n\n";
        $contenu .= "Message :\n$message\n";

        // 4. CONFIGURATION DES EN-TÊTES (Headers)
        // Indispensable pour éviter que votre mail ne finisse dans les SPAMs
        $headers = "From: contact@votresite.com\r\n"; // À changer par votre vrai nom de domaine plus tard
        $headers .= "Reply-To: $email\r\n"; // Permet de faire "Répondre" directement au client
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n"; // Gère correctement les accents

        // 5. ENVOI DU MAIL (Fonction native PHP)
        if (mail($destinataire, $sujet, $contenu, $headers)) {
            return "Succès : Votre message a bien été envoyé. Nous vous répondrons très vite !";
        } else {
            return "Erreur : Un problème technique a empêché l'envoi. Veuillez réessayer plus tard.";
        }
    }
}