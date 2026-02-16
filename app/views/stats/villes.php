<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th { background-color: #2c3e50; color: white; padding: 12px; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid #ddd; }
    tr:hover { background-color: #f5f5f5; cursor: pointer; }
    .btn { display: inline-block; padding: 8px 16px; background-color: #3498db; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; }
    .btn:hover { background-color: #2980b9; }
    .progress-bar-container { width: 100%; background-color: #e9ecef; border-radius: 4px; overflow: hidden; height: 24px; position: relative; }
    .progress-bar { height: 100%; border-radius: 4px; transition: width 0.3s; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: bold; min-width: 30px; }
    .progress-low { background-color: #dc3545; }
    .progress-partial { background-color: #ffc107; color: #333; }
    .progress-complete { background-color: #28a745; }
    .stat-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px; }
    .stat-card { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 10px; text-align: center; }
    .stat-card.green { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .stat-card.orange { background: linear-gradient(135deg, #f2994a, #f2c94c); }
    .stat-card .stat-value { font-size: 32px; font-weight: bold; }
    .stat-card .stat-label { font-size: 13px; opacity: 0.9; }
</style>

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
                    <tr onclick="window.location='/stats/ville/<?php echo $ville['ville_id']; ?>'">
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
                        <td><a href="/stats/ville/<?php echo $ville['ville_id']; ?>" class="btn">📋 Détail</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
