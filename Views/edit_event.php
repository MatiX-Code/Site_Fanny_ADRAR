
<main style="padding: 20px;">
    <h1>Modifier le spectacle</h1>
    
    <!-- Le formulaire renvoie vers la même page en gardant l'ID dans l'URL -->

<!-- Ajout de l'enctype obligatoire pour les images -->
    <form action="index.php?page=edit_event&id=<?= htmlspecialchars((string)$eventToEdit['id_event'], ENT_QUOTES, 'UTF-8') ?>" method="POST" enctype="multipart/form-data" style="max-width: 500px; display: flex; flex-direction: column; gap: 15px;">
        
        <div>
            <label for="titre">Titre du spectacle :</label><br>
            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($eventToEdit['titre_event'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required style="width: 100%;">
        </div>
        
        <div>
            <label for="date_event">Date et Heure :</label><br>
            <!-- Adaptez si vous aviez séparé date et heure en deux champs -->
            <input type="datetime-local" name="date_event" id="date_event" value="<?= htmlspecialchars($eventToEdit['date_event'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required style="width: 100%;">
        </div>

        <div>
            <label for="resume">Résumé du spectacle :</label><br>
            <!-- Le PHP se met ENTRE les balises textarea -->
            <textarea name="resume" id="resume" rows="5" style="width: 100%;"><?= htmlspecialchars($eventToEdit['resume_event'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div>
            <label for="lien">Lien de la billetterie :</label><br>
            <input type="url" name="lien" id="lien" value="<?= htmlspecialchars($eventToEdit['lien_event'] ?? '', ENT_QUOTES, 'UTF-8') ?>" style="width: 100%;">
        </div>

        <div>
            <label for="affiche">Affiche (laisser vide pour conserver l'actuelle) :</label><br>
            <input type="file" name="affiche_event" id="affiche" accept="image/*" style="width: 100%;">
            
            <!-- ASTUCE : On garde discrètement le nom de l'ancienne image en mémoire -->
            <input type="hidden" name="ancienne_affiche" value="<?= htmlspecialchars($eventToEdit['affiche_event'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" style="padding: 10px 20px; background-color: #64DFDF; border: none; cursor: pointer; font-weight: bold;">Mettre à jour</button>
            <a href="index.php?page=admin_events" style="margin-left: 15px; color: grey;">Annuler</a>
        </div>
    </form>
</main>
