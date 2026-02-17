<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/achats.css">

<div class="page-container">

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .btn-success {
        background-color: #28a745;
    }

    .btn-small {
        padding: 6px 12px;
        font-size: 13px;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th {
        background-color: #28a745;
        color: white;
        padding: 12px;
        text-align: left;
    }

    td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: bold;
    }

    .badge-nature {
        background-color: #28a745;
        color: white;
    }

    .badge-material {
        background-color: #6c757d;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .filter-bar {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-bar select {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .info-card {
        padding: 20px;
        border-radius: 10px;
        color: white;
        text-align: center;
    }

    .info-card.green {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .info-card.orange {
        background: linear-gradient(135deg, #f2994a, #f2c94c);
    }

    .info-card .value {
        font-size: 24px;
        font-weight: bold;
    }

    .info-card .label {
        font-size: 13px;
        opacity: 0.9;
    }

    .achat-form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .achat-form input {
        width: 80px;
        padding: 6px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-align: center;
    }

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
