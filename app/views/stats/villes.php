<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/stats.css">

<div class="page-container">
    <h1 style="margin-bottom: 10px;">📊 Statistiques par Ville</h1>
    <p style="color: #666; margin-bottom: 30px;">Cliquez sur une ville pour voir le détail de ses besoins et le ratio de satisfaction</p>

    <?php if (isset($_GET['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
            ⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <?php 
    $totalBesoins = array_sum(array_column($villes, 'nombre_besoins'));
    $totalDemande = array_sum(array_column($villes, 'total_quantite_demandee'));
    $totalRecue = array_sum(array_column($villes, 'total_quantite_recue'));
    $ratioGlobal = $totalDemande > 0 ? round($totalRecue * 100 / $totalDemande, 1) : 0;
    ?>

    <div class="stat-cards">
        <div class="stat-card">
            <div class="stat-value"><?php echo count($villes); ?></div>
            <div class="stat-label">Villes</div>
        </div>
        <div class="stat-card green">
            <div class="stat-value"><?php echo $totalBesoins; ?></div>
            <div class="stat-label">Total Besoins</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-value"><?php echo $ratioGlobal; ?>%</div>
            <div class="stat-label">Satisfaction Globale</div>
        </div>
    </div>

    <?php if (empty($villes)): ?>
        <div style="text-align: center; padding: 60px 20px; color: #666;">
            <h2>📭 Aucune ville enregistrée</h2>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Ville</th>
                    <th>Région</th>
                    <th>Nb Sinistrés</th>
                    <th>Nb Besoins</th>
                    <th>Qté Demandée</th>
                    <th>Qté Reçue</th>
                    <th>Satisfaction</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($villes as $ville): ?>
                    <tr onclick="window.location='<?php echo $baseurl; ?>/stats/ville/<?php echo $ville['ville_id']; ?>'">
                        <td><strong><?php echo htmlspecialchars($ville['ville_nom']); ?></strong></td>
                        <td><?php echo htmlspecialchars($ville['region_nom']); ?></td>
                        <td><?php echo number_format($ville['nbsinistres']); ?></td>
                        <td><?php echo $ville['nombre_besoins']; ?></td>
                        <td><?php echo number_format($ville['total_quantite_demandee']); ?></td>
                        <td><?php echo number_format($ville['total_quantite_recue']); ?></td>
                        <td>
                            <div class="progress-bar-container">
                                <?php 
                                $ratio = $ville['ratio_satisfaction_global'];
                                $class = $ratio >= 100 ? 'progress-complete' : ($ratio >= 50 ? 'progress-partial' : 'progress-low');
                                $width = min($ratio, 100);
                                ?>
                                <div class="progress-bar <?php echo $class; ?>" style="width: <?php echo $width; ?>%">
                                    <?php echo number_format($ratio, 1); ?>%
                                </div>
                            </div>
                        </td>
                        <td><a href="<?php echo $baseurl; ?>/stats/ville/<?php echo $ville['ville_id']; ?>" class="btn">📋 Détail</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
