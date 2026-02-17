<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/forms.css">

<div class="page-container">
    <h1>✏️ Modifier le Besoin #<?php echo $besoin->getId(); ?></h1>
    <p style="color: #666; margin-bottom: 30px;">Modifier le besoin (un historique sera enregistré)</p>

    <?php if (!empty($message)): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="besoin-info">
        <h2>📋 Informations actuelles</h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">RÉGION</span>
                <span class="info-value"><?php echo htmlspecialchars($besoin->getRegionNom()); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">VILLE</span>
                <span class="info-value"><?php echo htmlspecialchars($besoin->getVilleNom()); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">ARTICLE</span>
                <span class="info-value"><?php echo htmlspecialchars($besoin->getArticleNom()); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">CATÉGORIE</span>
                <span class="info-value"><span class="badge <?php echo $besoin->getCategorieClass(); ?>"><?php echo htmlspecialchars($besoin->getCategorie()); ?></span></span>
            </div>
            <div class="info-item">
                <span class="info-label">QUANTITÉ ACTUELLE</span>
                <span class="info-value"><?php echo $besoin->getQuantiteFormatee(); ?> <?php echo htmlspecialchars($besoin->getUnite()); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">PRIX UNITAIRE</span>
                <span class="info-value"><?php echo $besoin->getPrixUnitaireFormate(); ?> Ar</span>
            </div>
        </div>
    </div>

    <div class="warning-box">
        ℹ️ <strong>Note :</strong> Vous pouvez modifier la ville et/ou la quantité. La modification sera enregistrée dans l'historique.
    </div>

    <form action="<?php echo $baseurl; ?>/besoins/<?php echo $besoin->getId(); ?>/update" method="POST">
        <div class="form-group">
            <label for="ville_id">Ville concernée *</label>
            <select name="ville_id" id="ville_id" required>
                <?php foreach ($villes as $ville): ?>
                    <option value="<?php echo $ville['id']; ?>" <?php echo ($ville['id'] == $besoin->getVilleId()) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($ville['ville_nom']); ?> 
                        (<?php echo htmlspecialchars($ville['region_nom']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="quantite">Nouvelle quantité *</label>
            <input type="number" name="quantite" id="quantite" required min="1" 
                   value="<?php echo $besoin->getQuantite(); ?>"
                   placeholder="Entrez la nouvelle quantité">
            <small style="color: #666; display: block; margin-top: 5px;">
                Unité : <?php echo htmlspecialchars($besoin->getUnite()); ?>
            </small>
        </div>

        <div class="buttons">
            <button type="submit" class="btn">✔️ Enregistrer la modification</button>
            <a href="<?php echo $baseurl; ?>/besoins" class="btn btn-secondary">❌ Annuler</a>
            <a href="<?php echo $baseurl; ?>/besoins/<?php echo $besoin->getId(); ?>/historique" class="btn btn-secondary">📜 Voir l'historique</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
