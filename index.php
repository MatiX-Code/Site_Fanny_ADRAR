<?php
// 1. Démarrage de la session (Indispensable pour gérer la connexion de l'administrateur plus tard)
session_start();

// Optionnel : Inclusion de la configuration et de la base de données
require_once 'Models/Database.php';

// 2. Récupération de l'URL demandée
// Grâce au .htaccess, si on tape "monsite.com/planning", $page vaudra "planning"
// Le "??" signifie : si 'page' n'existe pas dans l'URL, on met 'accueil' par défaut.
$page = $_GET['page'] ?? 'accueil';

// 3. Le Routeur (Le "Maître d'hôtel")
switch ($page) {

    // ----------------------------------
    // PAGES PUBLIQUES (Vues directes)
    // ----------------------------------
    case 'accueil':
    case '': // Si l'URL est juste la racine du site
        require_once 'Views/accueil.php';
        break;

    case 'mon_spectacle':
        require_once 'Views/mon_spectacle.php';
        break;

    case 'portfolio':
        require_once 'Views/portfolio.php';
        break;

    case 'chanteuse':
        require_once 'Views/chanteuse.php';
        break;

    case 'comedienne':
        require_once 'Views/comedienne.php';
        break;

    case 'sirene':
        require_once 'Views/sirene.php';
        break;

    case 'planning':
        // Pour l'instant on charge juste la vue. 
        // Plus tard, on appellera le EventController ici pour chercher les événements en BDD
        require_once 'Views/planning.php';
        break;

    case 'contact':
        require_once 'Views/contact.php';
        break;

    case 'mentions_legales':
        require_once 'Views/mentions_legales.php';
        break;

    // ----------------------------------
    // PARTIE ADMINISTRATION (CMS)
    // ----------------------------------
    case 'admin':
    case 'login':
        // Affichage de la page de connexion
        require_once 'Views/admin_login_f-e.php';
        break;

    case 'admin_events':
        // ATTENTION POUR L'ORAL : 
        // Pour l'instant on affiche directement la vue.
        // Mais plus tard, on appellera le EventController ici pour VÉRIFIER que la personne 
        // est bien connectée en tant qu'admin AVANT d'afficher cette page (Sécurité !).
        require_once 'Views/admin_events_f-e.php';
        break;

    // ----------------------------------
    // GESTION DES ERREURS
    // ----------------------------------
    default:
        // Si l'utilisateur tape une URL qui n'est pas dans le switch (ex: monsite.com/nimportequoi)
        http_response_code(404);
        echo "<h1>Erreur 404</h1><p>Oups, la page demandée n'existe pas !</p>";
        break;
}