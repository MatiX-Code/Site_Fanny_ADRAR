<?php

class AuthController {
    //Attributs
    private UserModel $userModel;

    // Le contrôleur a besoin du modèle pour fonctionner
    public function __construct(UserModel $userModel) {
        $this->userModel = $userModel;
    }

    /** Méthodes
     * Traitement du formulaire d'inscription d'un administrateur
     */
    public function register(array $postData) : string {
        // 1. NETTOYAGE : Suppression des espaces invisibles en début/fin de chaîne
        $nom = trim($postData['nom'] ?? '');
        $email = trim($postData['email'] ?? '');
        $mdp = $postData['mdp'] ?? ''; // On ne trim pas un mot de passe (les espaces peuvent en faire partie)

        // 2. SÉCURITÉ : Contre les failles XSS (Cross-Site Scripting)
        // Convertit les caractères spéciaux (<, >) en entités HTML inoffensives
        $nom = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');

        // 3. VALIDATION : Vérification du format
        if (empty($nom) || empty($email) || empty($mdp)) {
            return "Erreur : Tous les champs sont obligatoires.";
        }

        // Vérification stricte du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Erreur : L'adresse email n'est pas valide.";
        }
        //  Vérification de la longueur du mot de passe (Recommandation CNIL/ANSSI)
        // Grâce à mb_strlen, on compte le nombre de caractères et non d'octets (utile pour les caractères spéciaux)
        if (mb_strlen($mdp, 'UTF-8') < 15) {
            return "Erreur : Pour des raisons de sécurité, le mot de passe doit faire au minimum 15 caractères.";
        }
        // Vérification que l'email n'existe pas déjà en base
        if ($this->userModel->getUserByEmail($email)) {
            return "Erreur : Cet email est déjà utilisé.";
        }

        // 4. HASHAGE DU MOT DE PASSE (Sécurité vitale)
        $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

        // 5. ENREGISTREMENT via le Modèle
        // Les données sont maintenant propres et le mot de passe est crypté
        $isCreated = $this->userModel->createUser($nom, $email, $hashedPassword);

        if ($isCreated) {
            return "Succès : Administrateur créé avec succès !";
        } else {
            return "Erreur : Impossible de créer l'administrateur.";
        }
    }
    /**
     * Traitement du formulaire de connexion
     */
    public function login(array $postData): string {
        // 1. Nettoyage de base
        $email = trim($postData['email'] ?? '');
        $mdp = $postData['mdp'] ?? '';

        if (empty($email) || empty($mdp)) {
            return "Erreur : Veuillez remplir tous les champs.";
        }

        // 2. Recherche via le Modèle
        $user = $this->userModel->getUserByEmail($email);

        // SÉCURITÉ : Message d'erreur volontairement flou
        if (!$user) {
            return "Erreur : Identifiants incorrects.";
        }

        // 3. Vérification du mot de passe crypté
        if (password_verify($mdp, $user['mdp_user'])) {
            
            // 4. SÉCURITÉ : Empêche le vol de session (Session Fixation)
            session_regenerate_id(true);

            // 5. Initialisation du trousseau de clés (Variables de session)
            $_SESSION['id_user']  = $user['id_user'];
            $_SESSION['nom_user'] = $user['nom_user'];
            $_SESSION['id_droit'] = $user['id_droit'];
            
            // Ce drapeau sera très pratique pour bloquer l'accès aux pages privées
            $_SESSION['is_admin'] = true; 
            
            return "Succès : Vous êtes connecté.";
            
        } else {
            return "Erreur : Identifiants incorrects.";
        }
    }
}

