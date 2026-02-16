<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 5px; color: #333; font-weight: bold; }
    select, input[type="number"], input[type="date"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
    select:focus, input:focus { outline: none; border-color: #007bff; }
    .btn { display: inline-block; padding: 12px 30px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-right: 10px; text-decoration: none; }
    .btn:hover { background-color: #0056b3; }
    .btn-secondary { background-color: #6c757d; }
    .btn-secondary:hover { background-color: #545b62; }
    .error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    .info-box { background-color: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    .buttons { margin-top: 30px; }
</style>

<div class="page-container">
    <h1>📋 Formulaire de Demande de Besoin</h1>
    <p style="color: #666; margin-bottom: 30px;">Remplissez le formulaire pour enregistrer une nouvelle demande</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
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
                <?php 
                $current_categorie = '';
                foreach ($typeArticles as $article): 
                    if ($current_categorie != $article['categorie']):
                        if ($current_categorie != '') echo '</optgroup>';
                        echo '<optgroup label="' . htmlspecialchars(ucfirst($article['categorie'])) . '">';
                        $current_categorie = $article['categorie'];
                    endif;
                ?>
                    <option value="<?php echo $article['id']; ?>">
                        <?php echo htmlspecialchars($article['nom']); ?> 
                        (<?php echo number_format($article['prix_unitaire'], 2); ?> Ar/<?php echo htmlspecialchars($article['unite']); ?>)
                    </option>
                <?php 
                endforeach;
                if ($current_categorie != '') echo '</optgroup>';
                ?>
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
