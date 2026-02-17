<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/stats.css">

<div class="page-container">
    <a href="<?php echo $baseurl; ?>/stats" class="btn" style="margin-bottom: 20px;">← Retour aux statistiques</a>

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
