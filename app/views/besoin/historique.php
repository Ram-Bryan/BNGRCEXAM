<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/besoins.css">

<div class="page-container">
    <div style="margin-bottom: 30px;">
        <h1>📜 Historique du Besoin #<?php echo $besoin->getId(); ?></h1>
        <a href="<?php echo $baseurl; ?>/besoins" class="btn">← Retour à la liste</a>
    </div>

    <div class="besoin-info">
        <h2>📋 Informations du Besoin</h2>
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

    <h3 style="color: #333; margin-bottom: 20px; font-size: 20px;">🕒 Historique des modifications</h3>

    <?php if (empty($historique)): ?>
        <div class="empty-state">
            <h2>📭 Aucun historique disponible</h2>
            <p>Aucune modification n'a encore été enregistrée pour ce besoin</p>
        </div>
    <?php else: ?>
        <?php if (!empty($message)): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <div class="timeline">
            <?php foreach ($historique as $entry): ?>
                <div class="timeline-item">
                    <div class="timeline-date">
                        📅 <?php echo date('d/m/Y à H:i:s', strtotime($entry['date_enregistrement'])); ?>
                    </div>
                    <div class="timeline-quantite">
                        Quantité : <?php echo number_format($entry['quantite']); ?> <?php echo htmlspecialchars($entry['unite']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
