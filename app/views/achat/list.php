<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

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

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-warning {
        background-color: #ffc107;
        color: #333;
    }

    .btn-danger {
        background-color: #dc3545;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
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
        background-color: #17a2b8;
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

    .btn-small {
        padding: 5px 10px;
        font-size: 12px;
        text-decoration: none;
        border-radius: 3px;
        color: white;
        border: none;
        cursor: pointer;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: bold;
    }

    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #333;
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

    .info-card {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .info-card .value {
        font-size: 28px;
        font-weight: bold;
    }

    .info-card .label {
        font-size: 14px;
        opacity: 0.9;
    }

    .actions-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
</style>

<div class="page-container">
    <div class="header">
        <div>
            <h1>🛒 Liste des Achats</h1>
            <p style="color: #666;">Achats effectués avec les dons en argent</p>
        </div>
        <a href="/achats/besoins" class="btn btn-success">➕ Nouvel achat</a>
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
        <select onchange="window.location.href='/achats?ville_id=' + this.value">
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
            <form method="POST" action="/achats/valider" style="display:inline;">
                <button type="submit" class="btn btn-success" onclick="return confirm('Valider tous les achats en attente ?');">
                    ✅ Valider tous les achats (<?php echo count($achatsEnAttente); ?>)
                </button>
            </form>
            <form method="POST" action="/achats/annuler" style="display:inline;">
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
            <a href="/achats/besoins" class="btn btn-success">➕ Faire un achat</a>
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
                    <th>Frais (<?php echo FRAIS_ACHAT_PERCENT; ?>%)</th>
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
                                <form method="POST" action="/achats/<?php echo $achat['id']; ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer cet achat ?');">
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