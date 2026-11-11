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

    // ==========================================
    // ✉️ ROUTE : PAGE DE CONTACT
    // ==========================================
    case 'contact':
        require_once 'Controllers/ContactController.php';
        $contactController = new ContactController();
        
        // Si le formulaire vient d'être cliqué
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // On envoie toutes les données au contrôleur qui retourne un message (Succès ou Erreur)
            $message_contact = $contactController->sendEmail($_POST);
        }

        // On affiche la page
        require_once 'Views/contact.php'; // Mettez le nom exact de votre fichier de vue
        break;

    case 'mentions_legales':
        require_once 'Views/mentions_legales.php';
        break;

    // ----------------------------------
    // PARTIE ADMINISTRATION (CMS)
    // ----------------------------------
    case 'admin':
        case 'login':
            // 1. Si le formulaire a été soumis (Méthode POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                // On charge nos classes (les "acteurs" de notre MVC)
                require_once 'Models/Database.php';
                require_once 'Models/UserModel.php';
                require_once 'Controllers/AuthController.php';

                // On fait nos Injections de Dépendances (les poupées russes)
                $db = new Database();
                $pdo = $db->getConnection();
                
                $userModel = new UserModel($pdo);
                $authController = new AuthController($userModel);

                // On demande au contrôleur de traiter les données du formulaire
                $message = $authController->login($_POST);

                // On vérifie le résultat retourné par le contrôleur
                if (strpos($message, 'Succès') === 0) {
                    // Si ça commence par "Succès", on redirige vers la zone privée
                    header('Location: index.php?page=admin_events');
                    exit;
                } else {
                    // Sinon, on stocke le message d'erreur pour l'afficher dans la vue
                    $erreur_login = $message;
                }
            }

            // 2. Affichage de la vue (Que ce soit le premier chargement ou après une erreur)
            require_once 'Views/admin_login_f-e.php';
            break;

    case 'admin_events':
            // ==========================================
            // 🔒 PÉAGE DE SÉCURITÉ (Votre excellent code !)
            // ==========================================
            if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
                header('Location: index.php?page=login');
                exit;
            }

            // ==========================================
            // ⚙️ APPEL DU CONTRÔLEUR ET GESTION DES DONNÉES
            // ==========================================
            // 1. On charge les outils nécessaires
            require_once 'Models/Database.php';
            require_once 'Models/EventModel.php';
            require_once 'Controllers/EventController.php';

            $db = new Database();
            $pdo = $db->getConnection();
            $eventModel = new EventModel($pdo);
            $eventController = new EventController($eventModel);

            // 2. Écoute du formulaire : Si l'admin a cliqué sur "Ajouter"
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // On envoie toutes les données saisies au Contrôleur
                $message_event = $eventController->addEvent($_POST);
            }

            // 3. On charge la page HTML (qui affichera le $message_event)
            require_once 'Views/admin_events_f-e.php';
            break;

    // ==========================================
    // 📡 ROUTE API POUR LE CALENDRIER JS
    // ==========================================
    case 'api_events':
        // 1. On appelle les fichiers nécessaires
        require_once 'Models/Database.php';
        require_once 'Models/EventModel.php';
        require_once 'Controllers/EventController.php';

        // 2. On instancie les classes
        $db = new Database();
        $pdo = $db->getConnection();
        $eventModel = new EventModel($pdo);
        $eventController = new EventController($eventModel);

        // 3. On déclenche la méthode qui génère le JSON
        // (Rappelez-vous, cette méthode contient un "exit;" à la fin 
        // pour ne pas afficher le reste du HTML de la page)
        $eventController->exportEventsToJson();
        break;
    
    // ==========================================
    // ⚠️ ROUTE TEMPORAIRE (À SUPPRIMER ENSUITE)
    // ==========================================
    case 'create_first_admin':
        require_once 'Models/Database.php';
        require_once 'Models/UserModel.php';
        require_once 'Controllers/AuthController.php';

        $db = new Database();
        $pdo = $db->getConnection();
        $userModel = new UserModel($pdo);
        $authController = new AuthController($userModel);

        // On simule les données d'un formulaire (15 caractères minimum !)
        $fakePostData = [
            'nom' => 'Fanny',
            'email' => 'fanny@votresite.com', 
            'mdp' => 'FannyAdminSuperSecurise2024!' 
        ];

        // On déclenche l'inscription
        $message = $authController->register($fakePostData);
        
        // On affiche le résultat et on stoppe la page
        echo "<h1>Résultat de la création :</h1>";
        echo "<p>" . $message . "</p>";
        exit;
    // ==========================================
    // ROUTE TEMPORAIRE (À SUPPRIMER ENSUITE)
    // ==========================================


    // ----------------------------------
    // GESTION DES ERREURS
    // ----------------------------------
    default:
        // Si l'utilisateur tape une URL qui n'est pas dans le switch (ex: monsite.com/nimportequoi)
        http_response_code(404);
        echo "<h1>Erreur 404</h1><p>Oups, la page demandée n'existe pas !</p>";
        break;
}