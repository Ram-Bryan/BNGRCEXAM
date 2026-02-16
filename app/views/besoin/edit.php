<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .besoin-info { background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid #007bff; }
    .besoin-info h2 { color: #007bff; margin-bottom: 15px; font-size: 18px; }
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; }
    .info-item { display: flex; flex-direction: column; }
    .info-label { font-weight: bold; color: #666; font-size: 12px; margin-bottom: 5px; }
    .info-value { color: #333; font-size: 16px; }
    .badge { display: inline-block; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
    .badge-nature { background-color: #28a745; color: white; }
    .badge-argent { background-color: #ffc107; color: #333; }
    .badge-material { background-color: #6c757d; color: white; }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 5px; color: #333; font-weight: bold; }
    input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
    input:focus { outline: none; border-color: #007bff; }
    .btn { display: inline-block; padding: 12px 30px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-right: 10px; text-decoration: none; }
    .btn:hover { background-color: #0056b3; }
    .btn-secondary { background-color: #6c757d; }
    .btn-secondary:hover { background-color: #545b62; }
    .error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    .warning-box { background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    .buttons { margin-top: 30px; }
</style>

<div class="page-container">
    <h1>✏️ Modifier le Besoin #<?php echo $besoin->id; ?></h1>
    <p style="color: #666; margin-bottom: 30px;">Modifier la quantité du besoin (un historique sera enregistré)</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="besoin-info">
        <h2>📋 Informations actuelles</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">RÉGION</span>
                <span class="info-value"><?php echo htmlspecialchars($besoin->region_nom); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">VILLE</span>
                <span class="info-value"><?php echo htmlspecialchars($besoin->ville_nom); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">ARTICLE</span>
                <span class="info-value"><?php echo htmlspecialchars($besoin->article_nom); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">CATÉGORIE</span>
                <span class="info-value"><span class="badge <?php echo $besoin->getCategorieClass(); ?>"><?php echo ucfirst($besoin->categorie); ?></span></span>
            </div>
            <div class="info-item">
                <span class="info-label">QUANTITÉ ACTUELLE</span>
                <span class="info-value"><?php echo $besoin->getQuantiteFormatee(); ?> <?php echo htmlspecialchars($besoin->unite); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">PRIX UNITAIRE</span>
                <span class="info-value"><?php echo $besoin->getPrixUnitaireFormate(); ?> Ar</span>
            </div>
        </div>
    </div>

    <div class="warning-box">
        ℹ️ <strong>Note :</strong> Seule la quantité peut être modifiée. La modification sera enregistrée dans l'historique.
    </div>

    <form action="/besoins/<?php echo $besoin->id; ?>/update" method="POST">
        <div class="form-group">
            <label for="quantite">Nouvelle quantité *</label>
            <input type="number" name="quantite" id="quantite" required min="1" 
                   value="<?php echo $besoin->quantite; ?>"
                   placeholder="Entrez la nouvelle quantité">
            <small style="color: #666; display: block; margin-top: 5px;">
                Unité : <?php echo htmlspecialchars($besoin->unite); ?>
            </small>
        </div>

        <div class="buttons">
            <button type="submit" class="btn">✔️ Enregistrer la modification</button>
            <a href="/besoins" class="btn btn-secondary">❌ Annuler</a>
            <a href="/besoins/<?php echo $besoin->id; ?>/historique" class="btn btn-secondary">📜 Voir l'historique</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
