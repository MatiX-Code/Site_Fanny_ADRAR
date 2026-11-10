<?php 
// 1. Démarrage de la session (Indispensable pour gérer la connexion de l'administrateur plus tard)
session_start();

// Optionnel : Inclusion de la configuration et de la base de données
require_once 'Models/Database.php';

// 2. Récupération de l'URL demandée
// Grâce au .htaccess, si on tape "monsite.com/planning", $page vaudra "planning"
// Le "??" signifie : si 'page' n'existe pas dans l'URL, on met 'accueil' par défaut.
$page = $_GET['page'] ?? 'accueil';

// 3. Le Routeur (Front Controller)
switch ($page) {

    // ----------------------------------
    // PAGES PUBLIQUES (Vues directes)
    // ----------------------------------
    case 'accueil':
    case '': // Racine du site
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
        require_once 'Views/admin_login_f-e.php';
        break;

    case 'admin_events':
        // ==========================================
        // 🔒 PÉAGE DE SÉCURITÉ 
        // ==========================================
        // On vérifie si la variable de session existe ET si elle vaut true
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            // L'utilisateur n'est pas connecté : on le renvoie vers la page de login
            header('Location: index.php?page=login');
            // SÉCURITÉ CRITIQUE : On bloque l'exécution du reste du code
            exit; 
        }

        // Si la vérification passe, on charge la page privée
        require_once 'Views/admin_events_f-e.php';
        break;

    case 'logout':
        // 🚪 SYSTÈME DE DÉCONNEXION
        session_destroy(); // Détruit toutes les données de session
        header('Location: index.php?page=accueil'); // Redirection vers l'accueil
        exit;

    // ----------------------------------
    // GESTION DES ERREURS
    // ----------------------------------
    default:
        // Si l'utilisateur tape une URL qui n'est pas dans le switch (ex: monsite.com/nimportequoi)
        http_response_code(404);
        echo "<h1>Erreur 404</h1><p>Oups, la page demandée n'existe pas !</p>";
        break;
}