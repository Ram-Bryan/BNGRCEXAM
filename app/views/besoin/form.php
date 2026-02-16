<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/assets/css/forms.css">

<div class="page-container">
    <h1>📋 Formulaire de Demande de Besoin</h1>
    <p style="color: #666; margin-bottom: 30px;">Remplissez le formulaire pour enregistrer une nouvelle demande</p>

    <?php if (isset($_GET['message'])): ?>
        <div class="message">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <div class="info-box">
        ℹ️ Cette demande sera enregistrée avec un historique pour suivre toutes les modifications futures.
    </div>

    <form action="/besoins/create" method="POST">
        <div class="form-group">
            <label for="ville_id">Ville concernée *</label>
            <select name="ville_id" id="ville_id" required>
                <option value="">-- Sélectionnez une ville --</option>
                <?php foreach ($villes as $ville): ?>
                    <option value="<?php echo $ville['id']; ?>">
                        <?php echo htmlspecialchars($ville['ville_nom']); ?> 
                        (<?php echo htmlspecialchars($ville['region_nom']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="type_article_id">Type d'article *</label>
            <select name="type_article_id" id="type_article_id" required>
                <option value="">-- Sélectionnez un article --</option>
                <?php foreach ($typeArticles as $article): ?>
                    <option value="<?php echo $article['id']; ?>">
                        <?php echo htmlspecialchars($article['nom']); ?>
                        (<?php echo htmlspecialchars($article['categorie'] ?? ''); ?>)
                        — <?php echo number_format($article['prix_unitaire'] ?? 0, 2); ?> Ar/<?php echo htmlspecialchars($article['unite'] ?? ''); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="quantite">Quantité *</label>
            <input type="number" name="quantite" id="quantite" required min="1" placeholder="Entrez la quantité nécessaire">
        </div>

        <div class="form-group">
            <label for="date_demande">Date de la demande *</label>
            <input type="date" name="date_demande" id="date_demande" required value="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="buttons">
            <button type="submit" class="btn">✔️ Enregistrer la demande</button>
            <a href="/besoins" class="btn btn-secondary">❌ Annuler</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
