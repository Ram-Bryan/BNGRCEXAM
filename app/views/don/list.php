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

    .btn-small {
        padding: 5px 10px;
        font-size: 12px;
        text-decoration: none;
        border-radius: 3px;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-danger {
        background-color: #dc3545;
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

    .badge-argent {
        background-color: #ffc107;
        color: #333;
    }

    .badge-material {
        background-color: #6c757d;
        color: white;
    }

    .badge-disponible {
        background-color: #17a2b8;
        color: white;
    }

    .badge-distribue {
        background-color: #6c757d;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .info-card {
        padding: 15px;
        border-radius: 8px;
        color: white;
        text-align: center;
    }

    .info-card.blue {
        background: linear-gradient(135deg, #0093E9, #80D0C7);
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
        font-size: 12px;
        opacity: 0.9;
    }
</style>

<div class="page-container">
    <div class="header">
        <div>
            <h1>🎁 Liste des Dons</h1>
            <p style="color: #666;">Dons saisis (distribution via Simulation)</p>
        </div>
        <div>
            <a href="/dons/ajout" class="btn btn-success">➕ Nouveau don</a>
            <a href="/simulation" class="btn btn-warning">⚙️ Distribution</a>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="success">✅
            <?php
            switch ($_GET['success']) {
                case 'created':
                    echo 'Don enregistré avec succès !';
                    break;
                case 'deleted':
                    echo 'Don supprimé avec succès !';
                    break;
                default:
                    echo htmlspecialchars($_GET['success']);
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <!-- Statistiques rapides -->
    <?php
    $totalDons = count($dons ?? []);
    $totalDisponible = 0;
    $totalDistribue = 0;
    foreach ($dons as $don) {
        $disponible = $don['quantite_disponible'] ?? ($don['quantite'] - ($don['quantite_distribuee'] ?? 0));
        $distribue = $don['quantite_distribuee'] ?? 0;
        $totalDisponible += $disponible;
        $totalDistribue += $distribue;
    }
    ?>
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
            <a href="/dons/ajout" class="btn btn-success">➕ Ajouter un don</a>
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
                    <?php
                    $disponible = $don['quantite_disponible'] ?? ($don['quantite'] - ($don['quantite_distribuee'] ?? 0));
                    $distribue = $don['quantite_distribuee'] ?? 0;
                    ?>
                    <tr>
                        <td><strong>#<?php echo $don['id']; ?></strong></td>
                        <td><?php echo isset($don['date_don']) ? date('d/m/Y', strtotime($don['date_don'])) : '-'; ?></td>
                        <td><?php echo htmlspecialchars($don['donateur'] ?? 'Anonyme'); ?></td>
                        <td><?php echo htmlspecialchars($don['article_nom'] ?? '-'); ?></td>
                        <td>
                            <?php $cat = $don['categorie'] ?? 'nature'; ?>
                            <span class="badge badge-<?php echo $cat; ?>">
                                <?php echo ucfirst($cat); ?>
                            </span>
                        </td>
                        <td><strong><?php echo number_format($don['quantite']); ?></strong> <?php echo htmlspecialchars($don['unite'] ?? ''); ?></td>
                        <td>
                            <?php if ($disponible > 0): ?>
                                <strong style="color: #17a2b8;"><?php echo number_format($disponible); ?></strong>
                            <?php else: ?>
                                <span style="color: #6c757d;">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($disponible > 0): ?>
                                <span class="badge badge-disponible">Disponible</span>
                            <?php else: ?>
                                <span class="badge badge-distribue">Distribué</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($disponible == $don['quantite']): ?>
                                <form method="POST" action="/dons/<?php echo $don['id']; ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer ce don ?');">
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