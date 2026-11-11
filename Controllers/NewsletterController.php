<?php

class NewsletterController {
    private NewsletterModel $newsletterModel;

    public function __construct(NewsletterModel $newsletterModel) {
        $this->newsletterModel = $newsletterModel;
    }

    public function subscribe(array $postData): string {
        // 1. Nettoyage de l'email
        $email = filter_var(trim($postData['mail'] ?? ''), FILTER_SANITIZE_EMAIL);

        // 2. Validation stricte du format
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Erreur : L'adresse email n'est pas valide.";
        }

        // 3. Vérification des doublons (règle métier)
        if ($this->newsletterModel->emailExists($email)) {
            return "Erreur : Cette adresse est déjà inscrite à la newsletter.";
        }

        // 4. Enregistrement
        if ($this->newsletterModel->addEmail($email)) {
            return "Succès : Merci pour votre inscription à la newsletter !";
        } else {
            return "Erreur : Un problème est survenu lors de l'inscription.";
        }
    }
}