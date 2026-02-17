<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/dons.css">

<div class="page-container">
    <div class="header">
        <div>
            <h1>🎁 Liste des Dons</h1>
            <p style="color: #666;">Dons saisis (distribution via Simulation)</p>
        </div>
        <div>
            <a href="<?php echo $baseurl; ?>/dons/ajout" class="btn btn-success">➕ Nouveau don</a>
            <a href="<?php echo $baseurl; ?>/simulation" class="btn btn-warning">⚙️ Distribution</a>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="success">✅ <?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Statistiques rapides -->
    <div class="info-cards">
        <div class="info-card blue">
            <div class="value"><?php echo $totalDons; ?></div>
            <div class="label">Total dons</div>
        </div>
        <div class="info-card green">
            <div class="value"><?php echo number_format($totalDisponible); ?></div>
            <div class="label">Quantité disponible</div>
        </div>
        <div class="info-card orange">
            <div class="value"><?php echo number_format($totalDistribue); ?></div>
            <div class="label">Quantité distribuée</div>
        </div>
    </div>

    <?php if (empty($dons)): ?>
        <div class="empty-state">
            <h2>📭 Aucun don enregistré</h2>
            <p>Commencez par créer un nouveau don</p><br>
            <a href="<?php echo $baseurl; ?>/dons/ajout" class="btn btn-success">➕ Ajouter un don</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Donateur</th>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Quantité</th>
                    <th>Disponible</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><strong>#<?php echo $don->getId(); ?></strong></td>
                        <td><?php echo $don->getDateFormatee(); ?></td>
                        <td><?php echo htmlspecialchars($don->getDonateur()); ?></td>
                        <td><?php echo htmlspecialchars($don->getArticleNom()); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $don->getCategorie(); ?>">
                                <?php echo $don->getCategorie(); ?>
                            </span>
                        </td>
                        <td><strong><?php echo $don->getQuantiteFormatee(); ?></strong> <?php echo htmlspecialchars($don->getUnite()); ?></td>
                        <td>
                            <?php if ($don->isDisponible()): ?>
                                <strong style="color: #17a2b8;"><?php echo $don->getQuantiteDisponibleFormatee(); ?></strong>
                            <?php else: ?>
                                <span style="color: #6c757d;">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($don->isDisponible()): ?>
                                <span class="badge badge-disponible">Disponible</span>
                            <?php else: ?>
                                <span class="badge badge-distribue">Distribué</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($don->isPeutSupprimer()): ?>
                                <form method="POST" action="<?php echo $baseurl; ?>/dons/<?php echo $don->getId(); ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer ce don ?');">
                                    <button type="submit" class="btn-small btn-danger">🗑️</button>
                                </form>
                            <?php else: ?>
                                <span style="color: #999; font-size: 12px;">Déjà distribué</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>