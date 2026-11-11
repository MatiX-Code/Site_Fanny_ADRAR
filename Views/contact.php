<?php require_once 'Views/header.php'; ?>
    <main id="contact" >
        <h1>Contact</h1>     

        <section class="contact">
        <article class="contact-description">
            <h3>Vous souhaitez plus d'informations ?</h3>
            <p> Vous pouvez remplir directement le formulaire ci-dessous ou me contacter à l'adresse suivante : <a href="mailto:      adressebellemaman@gmail.com">adressebellemaman@gmail.com</a>.</p>
        <p> Je vous répondrai dans les plus brefs délais.</p>
        </article>
            <!-- Formulaire de contact -->
            <form class="contact-form" action="mailto:adressemailàrenseigner" method="post" enctype="text/plain">
                <label for="name"></label>
                <input type="text" id="name" name="name" placeholder="Votre nom" required>

                <label for="firstname"></label>
                <input type="text" id="firstname" name="firstname" placeholder="Votre prénom" required>

                <label for="email"></label>
                <input type="email" id="email" name="email" placeholder="Votre email" required>

                <label for="telephone"></label>
                <input type="tel" id="telephone" name="telephone" placeholder="Votre numéro de téléphone (facultatif)">

                <label for="message"></label>
                <textarea id="message" name="message" placeholder="Votre message" required></textarea>

                <button class="contact-submit" type="submit">Envoyer</button>
            </form>
        </section>
    </main>
<?php require_once 'Views/footer.php'; ?>