<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/achats.css">

<div class="page-container">
    
    <div class="header">
        <div>
            <h1>🛒 Liste des Achats</h1>
            <p style="color: #666;">Achats effectués avec les dons en argent</p>
        </div>
        <a href="<?php echo $baseurl; ?>/achats/besoins" class="btn btn-success">➕ Nouvel achat</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="success">✅
            <?php
            switch ($_GET['success']) {
                case 'created':
                    echo 'Achat créé avec succès (en attente de validation)';
                    break;
                case 'validated':
                    echo 'Tous les achats ont été validés !';
                    break;
                case 'cancelled':
                    echo 'Simulation annulée';
                    break;
                case 'deleted':
                    echo 'Achat supprimé';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="info-card">
        <div class="value"><?php echo number_format($argentDisponible, 2, ',', ' '); ?> Ar</div>
        <div class="label">💰 Argent disponible pour les achats</div>
    </div>

    <div class="filter-bar">
        <label>Filtrer par ville :</label>
        <select onchange="window.location.href='<?php echo $baseurl; ?>/achats?ville_id=' + this.value">
            <option value="">-- Toutes les villes --</option>
            <?php foreach ($villes as $ville): ?>
                <option value="<?php echo $ville['id']; ?>" <?php echo ($ville_id == $ville['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($ville['ville_nom']); ?> (<?php echo $ville['region_nom']; ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php
    $achatsEnAttente = array_filter($achats, fn($a) => !$a['valide']);
    if (count($achatsEnAttente) > 0):
    ?>
        <div class="actions-bar">
            <form method="POST" action="<?php echo $baseurl; ?>/achats/valider" style="display:inline;">
                <button type="submit" class="btn btn-success" onclick="return confirm('Valider tous les achats en attente ?');">
                    ✅ Valider tous les achats (<?php echo count($achatsEnAttente); ?>)
                </button>
            </form>
            <form method="POST" action="<?php echo $baseurl; ?>/achats/annuler" style="display:inline;">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Annuler tous les achats en attente ?');">
                    ❌ Annuler la simulation
                </button>
            </form>
        </div>
    <?php endif; ?>

    <?php if (empty($achats)): ?>
        <div class="empty-state">
            <h2>📭 Aucun achat enregistré</h2>
            <p>Utilisez les dons en argent pour acheter des besoins en nature ou matériaux</p><br>
            <a href="<?php echo $baseurl; ?>/achats/besoins" class="btn btn-success">➕ Faire un achat</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ville</th>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Montant HT</th>
                    <th>Frais (<?php echo \models\Configuration::getValue(Flight::db(), 'FRAIS_ACHAT_PERCENT', 10, 'int'); ?>%)</th>
                    <th>Total TTC</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($achats as $achat): ?>
                    <tr>
                        <td><strong>#<?php echo $achat['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($achat['ville_nom']); ?></td>
                        <td><?php echo htmlspecialchars($achat['article_nom']); ?></td>
                        <td><?php echo number_format($achat['quantite']); ?> <?php echo htmlspecialchars($achat['unite']); ?></td>
                        <td><?php echo number_format($achat['montant_ht'], 2, ',', ' '); ?> Ar</td>
                        <td><?php echo number_format($achat['montant_frais'], 2, ',', ' '); ?> Ar</td>
                        <td><strong><?php echo number_format($achat['montant_total'], 2, ',', ' '); ?> Ar</strong></td>
                        <td><?php echo date('d/m/Y', strtotime($achat['date_achat'])); ?></td>
                        <td>
                            <?php if ($achat['valide']): ?>
                                <span class="badge badge-success">✅ Validé</span>
                            <?php else: ?>
                                <span class="badge badge-warning">⏳ En attente</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$achat['valide']): ?>
                                <form method="POST" action="<?php echo $baseurl; ?>/achats/<?php echo $achat['id']; ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer cet achat ?');">
                                    <button type="submit" class="btn-small btn-danger">🗑️</button>
                                </form>
                            <?php else: ?>
                                <span style="color: #999;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>