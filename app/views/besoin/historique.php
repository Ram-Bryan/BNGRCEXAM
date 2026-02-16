<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .btn { display: inline-block; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; margin-top: 10px; }
    .btn:hover { background-color: #545b62; }
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
    .timeline { position: relative; padding-left: 30px; }
    .timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 2px; background: #007bff; }
    .timeline-item { position: relative; padding: 20px; margin-bottom: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #007bff; }
    .timeline-item::before { content: ''; position: absolute; left: -24px; top: 25px; width: 12px; height: 12px; border-radius: 50%; background: #007bff; border: 3px solid white; }
    .timeline-date { color: #666; font-size: 14px; margin-bottom: 10px; }
    .timeline-quantite { font-size: 24px; color: #007bff; font-weight: bold; }
    .empty-state { text-align: center; padding: 60px 20px; color: #666; }
</style>

<div class="page-container">
    <div style="margin-bottom: 30px;">
        <h1>📜 Historique du Besoin #<?php echo $besoin->id; ?></h1>
        <a href="/besoins" class="btn">← Retour à la liste</a>
    </div>

    <div class="besoin-info">
        <h2>📋 Informations du Besoin</h2>
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

    <h3 style="color: #333; margin-bottom: 20px; font-size: 20px;">🕒 Historique des modifications</h3>

    <?php if (empty($historique)): ?>
        <div class="empty-state">
            <h2>📭 Aucun historique disponible</h2>
            <p>Aucune modification n'a encore été enregistrée pour ce besoin</p>
        </div>
    <?php else: ?>
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
