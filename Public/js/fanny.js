// ============================================================================
// 🍔 MENU BURGER (Navigation Mobile)
// ============================================================================

// Récupération des éléments du DOM
let burger = document.querySelector("#burger");
let nav = document.querySelector("#nav-links");

// Écouteur d'événement sur le clic du bouton burger
burger.addEventListener("click", () => {
    // La méthode toggle() ajoute la classe "open" si elle n'y est pas, 
    // ou la retire si elle y est déjà. Très pratique pour un menu !
    nav.classList.toggle("open");
});


// ============================================================================
// 📅 AGENDA (FullCalendar & Fenêtre Modale)
// ============================================================================

// On attend que tout le HTML soit chargé avant de lancer le script du calendrier
document.addEventListener('DOMContentLoaded', function() {
    
    // -------------------------------------------------------------
    // 1. Récupération des éléments de la modale (<dialog>)
    // -------------------------------------------------------------
    const modal = document.getElementById('eventModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalDate = document.getElementById('modalDate');
    const modalHour = document.getElementById('modalHour');
    const modalDescription = document.getElementById('modalDescription');
    const modalImg = document.getElementById('modalImg'); // L'affiche du spectacle
    const modalLien = document.getElementById('modalLien'); // Le lien billetterie
    const closeBtn = document.querySelector('.close-btn');
    
    // -------------------------------------------------------------
    // 2. Initialisation de FullCalendar
    // -------------------------------------------------------------
    const calendarEl = document.getElementById('calendrier');
    
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Vue par mois par défaut
        locale: 'fr', // Traduction en français
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        buttons: { 
            today: { text: "Aujourd'hui" } 
        },
        // Injection d'une classe personnalisée pour styliser le jour actuel en CSS
        dayCellClass: function(state) {
            return state.isToday ? 'mon-jour-actuel' : '';
        },
        
        // 📡 API BACKEND : Demande à FullCalendar d'aller chercher le JSON généré par PHP
        events: 'index.php?page=api_events',

        // -------------------------------------------------------------
        // 3. Détection du clic sur une date de spectacle
        // -------------------------------------------------------------
        eventClick: function(info) {
            // Empêche le comportement par défaut (éviter que la page ne saute)
            info.jsEvent.preventDefault();

            // --- Remplissage des textes basiques ---
            modalTitle.textContent = info.event.title;
            
            if (info.event.start) {
                // toLocaleDateString permet un affichage propre au format français (JJ/MM/AAAA)
                modalDate.textContent = 'Date : ' + info.event.start.toLocaleDateString('fr-FR');
            }
            
            // Les propriétés personnalisées (heure, description) sont rangées dans 'extendedProps'
            modalHour.textContent = info.event.extendedProps.heure ? "A partir de " + info.event.extendedProps.heure : "";
            modalDescription.textContent = info.event.extendedProps.description || "Description non disponible.";

            // --- Gestion dynamique de l'affiche ---
            if (modalImg) {
                if (info.event.extendedProps.affiche) {
                    // On ajoute explicitement le chemin du dossier devant le nom de l'image
                    modalImg.src = 'public/assets/uploads/' + info.event.extendedProps.affiche;
                    modalImg.style.display = 'block'; // On rend l'image visible
                } else {
                    modalImg.src = '';
                    modalImg.style.display = 'none'; // On cache la balise pour éviter le carré vide
                }
            }

            // --- Gestion dynamique du lien de billetterie ---
            if (modalLien) {
                if (info.event.extendedProps.lien_billetterie) {
                    modalLien.href = info.event.extendedProps.lien_billetterie;
                    modalLien.style.display = 'inline-block'; // On affiche le bouton
                } else {
                    modalLien.style.display = 'none'; // On cache le bouton si c'est gratuit/complet
                }
            }

            // --- Ouverture de la modale ---
            // Utilisation de l'API HTML5 native des balises <dialog>
            modal.showModal();
        }
    });
    
    // -------------------------------------------------------------
    // 4. Gestion de la fermeture de la modale
    // -------------------------------------------------------------
    
    // On écoute les clics sur l'ensemble du document
    document.addEventListener('click', function(event) {
        
        // Cas n°1 : Clic sur la croix (bouton avec la classe .close-btn)
        if (event.target.classList.contains('close-btn')) {
            modal.close(); // Fermeture native du <dialog>
        }
        
        // Cas n°2 : Clic en dehors de la boîte (sur le fond sombre / backdrop)
        // Comme l'élément <dialog> prend tout l'écran, cliquer sur lui = cliquer sur le fond
        if (event.target === modal) {
            modal.close(); 
        }
    });
    
    // -------------------------------------------------------------
    // 5. Affichage final du calendrier
    // -------------------------------------------------------------
    calendar.render();
});
