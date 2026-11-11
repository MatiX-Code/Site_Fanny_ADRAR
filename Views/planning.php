<?php require_once 'Views/header.php'; ?>
    <main>
        <section class="planning">
            <h1>Planning</h1>
            
            <!-- Conteneur du calendrier -->
            <div id="calendrier"></div>

           <!-- Section d'inscription à la newsletter -->
        <aside class="newsletter-section" aria-labelledby="newsletter-title">
            <p class="newsletter" id="newsletter-title">Pour ne rien louper, n'hésitez pas à vous abonner à la <strong>newsletter !</strong></p>
            
            <!-- Affichage du message de retour PHP -->
            <?php if (isset($message_newsletter)): ?>
                <div style="font-weight: bold; margin-bottom: 15px; text-align: center; color: <?= strpos($message_newsletter, 'Succès') === 0 ? 'green' : 'red' ?>;">
                    <?= htmlspecialchars($message_newsletter, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <!-- Le formulaire pointe vers la page planning du routeur -->
            <form class="newsletter" action="index.php?page=planning" method="post">
                <!-- Accessibilité : Le label doit contenir du texte, même caché visuellement -->
                <label for="mail" class="visually-hidden">Votre adresse email</label>
                <!-- Sécurité front : maxlength limite la taille saisie -->
                <input class="newletter" type="email" name="mail" id="mail" placeholder="Votre Email" maxlength="100" required>
                
                <input class="envoyer" type="submit" value="S'inscrire">
            </form>
        </aside>
        </section>

        <!-- Fenêtre Modale pour les événements -->
        <dialog id="eventModal" class="modal">
            <article class="modal-content">
                <header id="modalHeader">
                    <button type="button" class="close-btn" aria-label="Fermer la fenêtre">&times;</button>
                    <h2 id="modalTitle"></h2>
                </header>

                <!-- L'ID est modalImg, on laisse le src vide au départ -->
                <img id="modalImg" src="" alt="Affiche du spectacle">

                <p><time id="modalDate"></time></p>
                <p><time id="modalHour"></time></p>
                <p id="modalDescription"></p>

                <footer id="modalFooter">
                    <!-- L'ID est modalLien, on laisse le href vide au départ -->
                    <a id="modalLien" href="" target="_blank" rel="noopener">Réserver ma place</a>
                </footer>
            </article>
        </dialog>  
    </main>
<?php require_once 'Views/footer.php'; ?>