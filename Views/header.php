<?php $page = $page ?? 'accueil'; ?>
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
    <link rel="stylesheet" href="public/css/fanny.css">
    <link rel="stylesheet" href="public/css/fanny_media_queries.css">
    <link rel="stylesheet" href="public/css/fanny_calendar.css">
    <link rel="stylesheet" href="public/css/fanny_modale.css">

    <!-- Script JS personnel -->
    <script src="public/js/fanny.js" defer></script>
</head>

<body>
    <!-- Boutons Navigations -->
    <header id="navbar">
        <nav id="nav-links" aria-label="Navigation principale">
            <a href="index.php?page=accueil" <?= ($page === 'accueil' || $page === '') ? 'class="active"' : '' ?>>
                ACCUEIL
            </a>
            <a href="index.php?page=mon_spectacle" <?= ($page === 'mon_spectacle') ? 'class="active"' : '' ?>>
                MON SPECTACLE
            </a>
            <a href="index.php?page=portfolio" <?= ($page === 'portfolio') ? 'class="active"' : '' ?>>
                PORTFOLIO
            </a>
            <a href="index.php?page=planning" <?= ($page === 'planning') ? 'class="active"' : '' ?>>
                PLANNING
            </a>
            <a href="index.php?page=contact" <?= ($page === 'contact') ? 'class="active"' : '' ?>>
                CONTACT
            </a>
        </nav>
        
        <!-- Menu Burger Responsive -->
        <button id="burger" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </header>