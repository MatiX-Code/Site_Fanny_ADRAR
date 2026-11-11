<?php require_once 'Views/header.php'; ?>
    <main id="contact" >
        <h1>Contact</h1>     

        <section class="contact">
        <article class="contact-description">
            <h2>Vous souhaitez plus d'informations ?</h2>
            <p> Vous pouvez remplir directement le formulaire ci-dessous ou me contacter à l'adresse suivante : <a href="mailto:      adressebellemaman@gmail.com">adressebellemaman@gmail.com</a>.</p>
        <p> Je vous répondrai dans les plus brefs délais.</p>
        </article>
        
        <!-- Affichage du message de retour PHP (Sémantique et Accessible) -->
        <?php if (isset($message_contact)): ?>
            <p role="alert" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 20px; text-align: center; color: <?= strpos($message_contact, 'Succès') === 0 ? 'green' : 'red' ?>;">
                <?= htmlspecialchars($message_contact, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

            <!-- Formulaire de contact MVC avec Sécurité Front-End -->
        <form class="contact-form" action="index.php?page=contact" method="POST">
            
            <label for="name">Votre nom</label>
            <!-- pattern : uniquement des lettres, espaces, tirets. maxlength : 50 caractères -->
            <input type="text" id="name" name="name" placeholder="Ex : Dupont" 
                   pattern="^[a-zA-ZÀ-ÿ\s\-']+$" title="Uniquement des lettres" maxlength="50" required>

            <label for="firstname">Votre prénom</label>
            <input type="text" id="firstname" name="firstname" placeholder="Ex : Jean" 
                   pattern="^[a-zA-ZÀ-ÿ\s\-']+$" title="Uniquement des lettres" maxlength="50" required>

            <label for="email">Votre email</label>
            <!-- Le type="email" vérifie déjà la présence du @ -->
            <input type="email" id="email" name="email" placeholder="Ex : jean.dupont@email.com" maxlength="100" required>

            <label for="telephone">Votre numéro de téléphone (facultatif)</label>
            <!-- pattern : 10 chiffres attachés -->
            <input type="tel" id="telephone" name="telephone" placeholder="Ex : 0612345678" 
                   pattern="[0-9]{10}" title="Format : 10 chiffres sans espace" maxlength="10">

            <label for="message">Votre message</label>
            <!-- maxlenght : bloque le message à 1000 caractères -->
            <textarea id="message" name="message" placeholder="Écrivez votre message ici..." maxlength="1000" required></textarea>

            <button class="contact-submit" type="submit">Envoyer</button>
        </form>
        </section>
    </main>
<?php require_once 'Views/footer.php'; ?>