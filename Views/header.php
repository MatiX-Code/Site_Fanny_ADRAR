<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanny Egéa</title>
    
    <!-- Polices d'écriture -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Young+Serif&display=swap" rel="stylesheet">
    
    <!-- Calendar JS & CSS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@7.1.0/all/global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@7.1.0/themes/monarch/global.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@7.1.0/skeleton.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@7.1.0/themes/monarch/theme.css' rel='stylesheet' />

    <!-- Fichiers CSS personnels -->
    <link rel="stylesheet" href="Public/css/fanny.css">
    <link rel="stylesheet" href="Public/css/fanny_media_queries.css">
    <link rel="stylesheet" href="Public/css/fanny_calendar.css">
    <link rel="stylesheet" href="Public/css/fanny_modale.css">

    <!-- Script JS personnel -->
    <script src="Public/js/fanny.js" defer></script>
</head>

<body>
    <!-- Boutons Navigations -->
    <header id="navbar">
        <nav id="nav-links" aria-label="Navigation principale">
            <!-- Pensez à gérer la classe "active" en PHP plus tard si vous le souhaitez pour la couleur du bouton -->
            <a href="index.php?page=accueil"><button>ACCUEIL</button></a>
            <a href="index.php?page=mon_spectacle"><button>MON SPECTACLE</button></a>
            <a href="index.php?page=portfolio"><button>PORTFOLIO</button></a>
            <a href="index.php?page=planning"><button>PLANNING</button></a>
            <a href="index.php?page=contact"><button>CONTACT</button></a>
        </nav>
        
        <!-- Menu Burger Responsive -->
        <button id="burger" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>