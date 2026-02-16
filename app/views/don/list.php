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
    th { background-color: #28a745; color: white; padding: 12px; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid #ddd; }
    tr:hover { background-color: #f5f5f5; }
    .btn-small { padding: 5px 10px; font-size: 12px; text-decoration: none; border-radius: 3px; color: white; border: none; cursor: pointer; }
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
            <h1>🎁 Liste des Dons</h1>
            <p style="color: #666;">Suivi des dons distribués aux besoins</p>
        </div>
        <a href="/dons/ajout" class="btn btn-success">➕ Nouveau don</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="success">✅ 
            <?php 
            switch($_GET['success']) {
                case 'created': echo 'Don enregistré avec succès !'; break;
                case 'deleted': echo 'Don supprimé avec succès !'; break;
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

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
                    <th>Besoin #</th>
                    <th>Ville</th>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Qté Don</th>
                    <th>Qté Besoin</th>
                    <th>Date Livraison</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><strong>#<?php echo $don->id; ?></strong></td>
                        <td>#<?php echo $don->besoin_id; ?></td>
                        <td><?php echo htmlspecialchars($don->ville_nom); ?></td>
                        <td><?php echo htmlspecialchars($don->article_nom); ?></td>
                        <td><span class="badge <?php echo $don->getCategorieClass(); ?>"><?php echo ucfirst($don->categorie); ?></span></td>
                        <td><strong><?php echo number_format($don->quantite_don); ?></strong> <?php echo htmlspecialchars($don->unite); ?></td>
                        <td><?php echo number_format($don->quantite_besoin); ?> <?php echo htmlspecialchars($don->unite); ?></td>
                        <td><?php echo $don->getDateLivraisonFormatee(); ?></td>
                        <td>
                            <form method="POST" action="/dons/<?php echo $don->id; ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer ce don ?');">
                                <button type="submit" class="btn-small btn-danger">🗑️ Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
