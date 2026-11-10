<?php require_once 'Views/header.php'; ?>
    <main>
        <section class="planning">
            <h1>Planning</h1>
            
            <!-- Conteneur du calendrier -->
            <div id="calendrier"></div>

            <!-- Section d'inscription à la newsletter -->
            <aside class="newsletter-section" aria-labelledby="newsletter-title">
                <p class="newletter" id="newsletter-title">Pour ne rien louper, n'hésitez pas à vous abonner à la <strong>newsletter !</strong></p>
                <form class="newletter" action="register.php" method="post">
                    <label for="mail" class="visually-hidden"></label>
                    <input class="newletter" type="email" name="mail" id="mail" placeholder="Votre Email" required>
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