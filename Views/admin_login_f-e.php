<form action="index.php?page=login" method="POST">
    
    <!-- FIELDSET : Regroupe sémantiquement un ensemble de champs logiques -->
    <fieldset>
        <!-- LEGEND : Donne un titre sémantique à ce groupe de champs -->
        <legend>Vos identifiants de connexion</legend>
        
        <!-- P : Définit un paragraphe de contenu (très utilisé par le W3C pour les formulaires) -->
        <p>
            <label for="email">Adresse Email :</label>
            <input type="email" id="email" name="email" required>
        </p>

        <p>
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" required>
        </p>
    </fieldset>

    <button type="submit">Se connecter</button>
</form>