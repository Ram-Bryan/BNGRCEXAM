<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/achats.css">

<div class="page-container">
    <div class="header">
        <div>
            <h1>🛒 Besoins Restants - Faire un Achat</h1>
            <p style="color: #666;">Utilisez les dons en argent pour acheter des besoins en nature ou matériaux</p>
        </div>
        <a href="<?php echo $baseurl; ?>/achats" class="btn">📋 Voir les achats</a>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="info-cards">
        <div class="info-card green">
            <div class="value"><?php echo number_format($argentDisponible, 2, ',', ' '); ?> Ar</div>
            <div class="label">💰 Argent disponible</div>
        </div>
        <div class="info-card orange">
            <div class="value"><?php echo $fraisPercent; ?>%</div>
            <div class="label">📊 Frais d'achat</div>
        </div>
    </div>

    <div class="filter-bar">
        <label>Filtrer par ville :</label>
        <select onchange="window.location.href='<?php echo $baseurl; ?>/achats/besoins?ville_id=' + this.value">
            <option value="">-- Toutes les villes --</option>
            <?php foreach ($villes as $ville): ?>
                <option value="<?php echo $ville['id']; ?>" <?php echo ($ville_id == $ville['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($ville['ville_nom']); ?> (<?php echo $ville['region_nom']; ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if (empty($besoins)): ?>
        <div class="empty-state">
            <h2>✅ Tous les besoins sont satisfaits !</h2>
            <p>Aucun besoin en nature ou matériaux restant à satisfaire</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Besoin #</th>
                    <th>Ville</th>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Qté Restante</th>
                    <th>Prix Unit.</th>
                    <th>Montant Restant</th>
                    <th>Acheter</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($besoins as $besoin):
                    $montantRestant = $besoin['quantite_restante'] * $besoin['prix_unitaire'];
                ?>
                    <tr>
                        <td><strong>#<?php echo $besoin['id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($besoin['ville_nom']); ?></td>
                        <td><?php echo htmlspecialchars($besoin['article_nom']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $besoin['categorie']; ?>">
                                <?php echo ucfirst($besoin['categorie']); ?>
                            </span>
                        </td>
                        <td><?php echo number_format($besoin['quantite_restante']); ?> <?php echo htmlspecialchars($besoin['unite']); ?></td>
                        <td><?php echo number_format($besoin['prix_unitaire'], 2, ',', ' '); ?> Ar</td>
                        <td><strong><?php echo number_format($montantRestant, 2, ',', ' '); ?> Ar</strong></td>
                        <td>
                            <form method="POST" action="<?php echo $baseurl; ?>/achats/create" class="achat-form" onsubmit="return confirmAchat(this, <?php echo $besoin['prix_unitaire']; ?>, <?php echo $fraisPercent; ?>, <?php echo $argentDisponible; ?>);">
                                <input type="hidden" name="besoin_id" value="<?php echo $besoin['id']; ?>">
                                <input type="number" name="quantite" min="1" max="<?php echo $besoin['quantite_restante']; ?>" value="1" required>
                                <button type="submit" class="btn btn-success btn-small">🛒 Acheter</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="<?php echo $baseurl; ?>/assets/js/achat.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
