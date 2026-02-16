<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/assets/css/forms.css">

<div class="page-container">
    <h1>🎁 Enregistrer un Don</h1>
    <p style="color: #666; margin-bottom: 30px;">Saisir un nouveau don (sera distribué plus tard via la simulation)</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-box">✅ <?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="info-box">
        ℹ️ <strong>Note :</strong> Les dons sont saisis librement sans être liés directement à un besoin.
        La distribution des dons vers les besoins se fait via la page <a href="/simulation">Simulation</a>.
    </div>

    <form action="/dons/create" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="donateur">Nom du donateur</label>
                <input type="text" name="donateur" id="donateur" placeholder="Ex: Croix Rouge, Anonyme, M. Rakoto...">
            </div>

            <div class="form-group">
                <label for="date_don">Date du don *</label>
                <input type="date" name="date_don" id="date_don" required value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="type_article_id">Type d'article *</label>
            <select name="type_article_id" id="type_article_id" required>
                <option value="">-- Sélectionnez le type d'article --</option>
                <?php foreach ($typeArticles as $article): ?>
                    <option value="<?php echo $article['id']; ?>" data-categorie="<?php echo $article['categorie']; ?>" data-unite="<?php echo htmlspecialchars($article['unite']); ?>">
                        <?php echo htmlspecialchars($article['nom']); ?>
                        [<?php echo ucfirst($article['categorie']); ?>]
                        — <?php echo htmlspecialchars($article['unite']); ?>
                        <?php if ($article['categorie'] === 'argent'): ?>
                            💰
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="quantite">Quantité *</label>
                <input type="number" name="quantite" id="quantite" required min="1" placeholder="Entrez la quantité">
                <small id="unite-info" style="color: #666;"></small>
            </div>
        </div>

        <div class="buttons">
            <button type="submit" class="btn">✔️ Enregistrer le don</button>
            <a href="/dons" class="btn btn-secondary">❌ Annuler</a>
        </div>
    </form>
</div>

<script src="<?php echo Flight::get('flight.base_url'); ?>/assets/js/don-form.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
