<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; border: none; cursor: pointer; }
    .btn:hover { background-color: #0056b3; }
    .btn-success { background-color: #28a745; }
    .btn-success:hover { background-color: #218838; }
    .success { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    .error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th { background-color: #007bff; color: white; padding: 12px; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid #ddd; }
    tr:hover { background-color: #f5f5f5; }
    .actions { display: flex; gap: 8px; }
    .btn-small { padding: 5px 10px; font-size: 12px; text-decoration: none; border-radius: 3px; color: white; border: none; cursor: pointer; }
    .btn-info { background-color: #17a2b8; }
    .btn-warning { background-color: #ffc107; color: #333; }
    .btn-danger { background-color: #dc3545; }
    .badge { display: inline-block; padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
    .badge-nature { background-color: #28a745; color: white; }
    .badge-argent { background-color: #ffc107; color: #333; }
    .badge-material { background-color: #6c757d; color: white; }
    .empty-state { text-align: center; padding: 60px 20px; color: #666; }
</style>

<div class="page-container">
    <div class="header">
        <div>
            <h1>📋 Liste des Besoins</h1>
            <p style="color: #666;">Gestion des demandes de besoins</p>
        </div>
        <a href="/besoins/ajout" class="btn btn-success">➕ Nouvelle demande</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="success">✅ 
            <?php 
            switch($_GET['success']) {
                case 'created': echo 'Besoin créé avec succès !'; break;
                case 'updated': echo 'Besoin mis à jour avec succès !'; break;
                case 'deleted': echo 'Besoin supprimé avec succès !'; break;
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (empty($besoins)): ?>
        <div class="empty-state">
            <h2>📭 Aucun besoin enregistré</h2>
            <p>Commencez par créer une nouvelle demande de besoin</p><br>
            <a href="/besoins/ajout" class="btn btn-success">➕ Créer une demande</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Région</th>
                    <th>Ville</th>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Date Demande</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($besoins as $besoin): ?>
                    <tr>
                        <td><strong>#<?php echo $besoin->id; ?></strong></td>
                        <td><?php echo htmlspecialchars($besoin->region_nom); ?></td>
                        <td><?php echo htmlspecialchars($besoin->ville_nom); ?></td>
                        <td><?php echo htmlspecialchars($besoin->article_nom); ?></td>
                        <td><span class="badge <?php echo $besoin->getCategorieClass(); ?>"><?php echo ucfirst($besoin->categorie); ?></span></td>
                        <td><?php echo $besoin->getQuantiteFormatee(); ?> <?php echo htmlspecialchars($besoin->unite); ?></td>
                        <td><?php echo $besoin->getPrixUnitaireFormate(); ?> Ar</td>
                        <td><?php echo $besoin->getDateFormatee(); ?></td>
                        <td>
                            <div class="actions">
                                <a href="/besoins/<?php echo $besoin->id; ?>/historique" class="btn-small btn-info">📜</a>
                                <a href="/besoins/<?php echo $besoin->id; ?>/edit" class="btn-small btn-warning">✏️</a>
                                <form method="POST" action="/besoins/<?php echo $besoin->id; ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer ce besoin ?');">
                                    <button type="submit" class="btn-small btn-danger">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
