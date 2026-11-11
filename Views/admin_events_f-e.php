<!-- Affichage des messages de succès ou d'erreur depuis le routeur -->
<?php if (isset($message_event)): ?>
    <div style="font-weight: bold; margin-bottom: 20px; color: <?= strpos($message_event, 'Succès') === 0 ? 'green' : 'red' ?>;">
        <?= htmlspecialchars($message_event, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<!-- Formulaire avec l'attribut obligatoire enctype pour les images -->
<form action="index.php?page=admin_events" method="POST" enctype="multipart/form-data">
    <fieldset>
        <legend>Ajouter une nouvelle date de spectacle</legend>

        <p>
            <label for="titre_event">Titre du spectacle :</label>
            <input type="text" id="titre_event" name="titre_event" required>
        </p>

        <p>
            <label for="date_event">Date :</label>
            <input type="date" id="date_event" name="date_event" required>
        </p>

        <p>
            <label for="heure_event">Heure :</label>
            <!-- L'attribut step="60" limite la saisie aux minutes entières -->
            <input type="time" id="heure_event" name="heure_event" required step="60">
        </p>

        <p>
            <label for="resume_event">Résumé du spectacle :</label>
            <textarea id="resume_event" name="resume_event" rows="5" required></textarea>
        </p>

        <p>
            <label for="lien_event">Lien de la billetterie (optionnel) :</label>
            <!-- Le type URL valide nativement la structure du lien côté client -->
            <input type="url" id="lien_event" name="lien_event" placeholder="https://www.billetweb.fr/...">
        </p>

        <p>
            <label for="affiche_event">Affiche (optionnelle, max 2 Mo) :</label>
            <input type="file" id="affiche_event" name="affiche_event" accept="image/png, image/jpeg, image/webp">
        </p>

    </fieldset>

    <button type="submit">Enregistrer la date</button>
</form>
<hr style="margin: 40px 0;">

<h2>Liste des spectacles existants</h2>

<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #f4f4f4; text-align: left;">
            <th style="padding: 10px; border: 1px solid #ddd;">Titre</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Date et Heure</th>
            <th style="padding: 10px; border: 1px solid #ddd;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($allEvents)): ?>
            <?php foreach ($allEvents as $event): ?>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <!-- REMPLACEZ 'titre' SI VOTRE COLONNE S'APPELLE AUTREMENT -->
                        <?= htmlspecialchars($event['titre_event'] ?? 'Titre inconnu', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <!-- REMPLACEZ 'date_event' SI VOTRE COLONNE S'APPELLE AUTREMENT -->
                        <?= htmlspecialchars($event['date_event'] ?? 'Date inconnue', ENT_QUOTES, 'UTF-8') ?>
                    </td>
                    
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <!-- Bouton Modifier -->
                        <a href="index.php?page=edit_event&id=<?= htmlspecialchars((string)$event['id_event'], ENT_QUOTES, 'UTF-8') ?>" style="color: blue; text-decoration: none; margin-right: 15px;">Modifier</a>
                        
                        <!-- Bouton Supprimer -->
                        <form action="index.php?page=delete_event" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce spectacle ?');">
                            <input type="hidden" name="event_id" value="<?= htmlspecialchars((string)$event['id_event'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; text-decoration: underline;">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="padding: 10px; text-align: center; border: 1px solid #ddd;">Aucun spectacle programmé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>