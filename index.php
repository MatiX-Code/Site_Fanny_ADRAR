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
        // ==========================================
        // 1. CHARGEMENT DES OUTILS POUR LA NEWSLETTER
        // ==========================================
        require_once 'Models/Database.php';
        require_once 'Models/NewsletterModel.php';
        require_once 'Controllers/NewsletterController.php';

        $db = new Database();
        $pdo = $db->getConnection();
        $newsletterModel = new NewsletterModel($pdo);
        $newsletterController = new NewsletterController($newsletterModel);

        // ==========================================
        // 2. ÉCOUTE DU FORMULAIRE DE NEWSLETTER
        // ==========================================
        // Si le visiteur a cliqué sur "S'inscrire" (Envoi en POST avec le champ "mail")
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mail'])) {
            // On traite l'inscription et on récupère le message de retour
            $message_newsletter = $newsletterController->subscribe($_POST);
        }

        // ==========================================
        // 3. AFFICHAGE DE LA PAGE
        // ==========================================
        // (Les spectacles se chargeront tout seuls via le JS et l'API)
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
            // 🔒 PÉAGE DE SÉCURITÉ
            // ==========================================
            if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
                header('Location: index.php?page=login');
                exit;
            }

            // ==========================================
            // ⚙️ APPEL DU CONTRÔLEUR ET GESTION DES DONNÉES
            // ==========================================
            require_once 'Models/Database.php';
            require_once 'Models/EventModel.php';
            require_once 'Controllers/EventController.php';

            $db = new Database();
            $pdo = $db->getConnection();
            $eventModel = new EventModel($pdo);
            $eventController = new EventController($eventModel);

            // 1. Écoute du formulaire : Si l'admin a cliqué sur "Ajouter"
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $message_event = $eventController->addEvent($_POST);
            }

            // 2. On récupère TOUS les événements de la BDD
            $allEvents = $eventModel->getAllEvents();

            // 3. On charge la page HTML (qui va utiliser $allEvents pour construire le tableau)
            require_once 'Views/admin_events_f-e.php';
            break;
    // ==========================================
    // ✏️ ROUTE : MODIFIER UN SPECTACLE
    // ==========================================
    case 'edit_event':
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            header('Location: index.php?page=login');
            exit;
        }

        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: index.php?page=admin_events');
            exit;
        }
        $id = (int) $_GET['id'];

        require_once 'Models/Database.php';
        require_once 'Models/EventModel.php';
        require_once 'Controllers/EventController.php'; // N'oubliez pas d'inclure le contrôleur !
        
        $db = new Database();
        $pdo = $db->getConnection();
        $eventModel = new EventModel($pdo);
        $eventController = new EventController($eventModel);

        // ⭐  Si on a cliqué sur "Mettre à jour" (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // On envoie les données, les fichiers (image) et l'ID au Contrôleur
            $message_edit = $eventController->modifyEvent($id, $_POST, $_FILES);
        }

        // On récupère les infos du spectacle (qui seront fraîches si on vient de faire un POST)
        $eventToEdit = $eventModel->getEventById($id);

        if (!$eventToEdit) {
            header('Location: index.php?page=admin_events');
            exit;
        }

        require_once 'Views/edit_event.php';
        break;

    // ==========================================
    // 🗑️ ROUTE : SUPPRIMER UN SPECTACLE
    // ==========================================
    case 'delete_event':
        // 1. PÉAGE DE SÉCURITÉ (Seul l'admin a le droit de supprimer)
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            header('Location: index.php?page=login');
            exit;
        }

        // 2. Vérification : On s'assure que l'ordre vient bien du formulaire POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['event_id'])) {
            require_once 'Models/Database.php';
            require_once 'Models/EventModel.php';

            $db = new Database();
            $pdo = $db->getConnection();
            $eventModel = new EventModel($pdo);

            // 3. On demande au modèle de supprimer la ligne avec cet ID
            // (Le (int) est une sécurité supplémentaire pour forcer un format numérique)
            $eventModel->deleteEvent((int) $_POST['event_id']);
        }

        // 4. Une fois supprimé (ou si on a tenté de tricher en y allant sans POST), 
        // on renvoie l'utilisateur vers le tableau de bord
        header('Location: index.php?page=admin_events');
        exit;
    // ==========================================
    // 🚪 ROUTE : DÉCONNEXION DE L'ADMIN
    // ==========================================
    case 'logout':
        // 1. On vide toutes les variables de session
        $_SESSION = [];

        // 2. Si un cookie de session existe, on le supprime
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // 3. On détruit la session
        session_destroy();

        // 4. On redirige vers la page de connexion
        header('Location: index.php?page=login');
        exit;

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