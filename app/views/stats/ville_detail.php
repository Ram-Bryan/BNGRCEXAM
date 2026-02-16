<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .btn { display: inline-block; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; }
    .btn:hover { background-color: #545b62; }
    .ville-header { background: linear-gradient(135deg, #1a5276, #2980b9); color: white; padding: 30px; border-radius: 10px; margin-bottom: 30px; }
    .ville-header h1 { margin-bottom: 5px; }
    .ville-stats { display: flex; gap: 30px; margin-top: 15px; }
    .ville-stat { text-align: center; }
    .ville-stat .value { font-size: 28px; font-weight: bold; }
    .ville-stat .label { font-size: 12px; opacity: 0.8; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th { background-color: #2c3e50; color: white; padding: 12px; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid #ddd; }
    tr:hover { background-color: #f5f5f5; }
    .badge { display: inline-block; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
    .badge-nature { background-color: #28a745; color: white; }
    .badge-argent { background-color: #ffc107; color: #333; }
    .badge-material { background-color: #6c757d; color: white; }
    .progress-bar-container { width: 120px; background-color: #e9ecef; border-radius: 4px; overflow: hidden; height: 22px; position: relative; display: inline-block; }
    .progress-bar { height: 100%; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: white; font-size: 11px; font-weight: bold; min-width: 30px; }
    .progress-low { background-color: #dc3545; }
    .progress-partial { background-color: #ffc107; color: #333; }
    .progress-complete { background-color: #28a745; }
    .ratio-complete { color: #28a745; font-weight: bold; }
    .ratio-partial { color: #ffc107; font-weight: bold; }
    .ratio-low { color: #dc3545; font-weight: bold; }
    .ratio-none { color: #999; }
    .empty-state { text-align: center; padding: 60px 20px; color: #666; }
</style>

<div class="page-container">
    <a href="/stats" class="btn" style="margin-bottom: 20px;">← Retour aux statistiques</a>

    <div class="ville-header">
        <h1>🏙️ <?php echo htmlspecialchars($ville['ville_nom']); ?></h1>
        <p>Région : <?php echo htmlspecialchars($ville['region_nom']); ?></p>
        <div class="ville-stats">
            <div class="ville-stat">
                <div class="value"><?php echo number_format($ville['nbsinistres']); ?></div>
                <div class="label">Sinistrés</div>
            </div>
            <div class="ville-stat">
                <div class="value"><?php echo $ville['nombre_besoins']; ?></div>
                <div class="label">Besoins</div>
            </div>
            <div class="ville-stat">
                <div class="value"><?php echo number_format($ville['total_quantite_demandee']); ?></div>
                <div class="label">Qté Demandée</div>
            </div>
            <div class="ville-stat">
                <div class="value"><?php echo number_format($ville['total_quantite_recue']); ?></div>
                <div class="label">Qté Reçue</div>
            </div>
            <div class="ville-stat">
                <div class="value"><?php echo number_format($ville['ratio_satisfaction_global'], 1); ?>%</div>
                <div class="label">Satisfaction</div>
            </div>
        </div>
    </div>

    <h2 style="margin-bottom: 10px;">📋 Besoins de la ville</h2>
    <p style="color: #666; margin-bottom: 20px;">Détail de chaque besoin avec son ratio de satisfaction</p>

    <?php if (empty($besoins)): ?>
        <div class="empty-state">
            <h2>📭 Aucun besoin enregistré pour cette ville</h2>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Qté Demandée</th>
                    <th>Qté Reçue</th>
                    <th>Qté Restante</th>
                    <th>Date Demande</th>
                    <th>Satisfaction</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($besoins as $besoin): ?>
                    <tr>
                        <td><strong>#<?php echo $besoin->id; ?></strong></td>
                        <td><?php echo htmlspecialchars($besoin->article_nom); ?></td>
                        <td>
                            <span class="badge <?php echo $besoin->getCategorieClass(); ?>">
                                <?php echo ucfirst($besoin->categorie); ?>
                            </span>
                        </td>
                        <td><?php echo $besoin->getQuantiteFormatee(); ?> <?php echo htmlspecialchars($besoin->unite); ?></td>
                        <td><?php echo number_format($besoin->quantite_recue ?? 0); ?> <?php echo htmlspecialchars($besoin->unite); ?></td>
                        <td>
                            <span class="<?php echo ($besoin->quantite_restante ?? 0) > 0 ? 'ratio-low' : 'ratio-complete'; ?>">
                                <?php echo number_format($besoin->quantite_restante ?? 0); ?> <?php echo htmlspecialchars($besoin->unite); ?>
                            </span>
                        </td>
                        <td><?php echo $besoin->getDateFormatee(); ?></td>
                        <td>
                            <?php 
                            $ratio = $besoin->ratio_satisfaction ?? 0;
                            $class = $ratio >= 100 ? 'progress-complete' : ($ratio >= 50 ? 'progress-partial' : 'progress-low');
                            $width = min($ratio, 100);
                            ?>
                            <div class="progress-bar-container">
                                <div class="progress-bar <?php echo $class; ?>" style="width: <?php echo $width; ?>%">
                                    <?php echo number_format($ratio, 1); ?>%
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
