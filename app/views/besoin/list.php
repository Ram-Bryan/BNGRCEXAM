<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/assets/css/besoins.css">

<div class="page-container">
    <div class="header">
        <div>
            <h1>📋 Liste des Besoins</h1>
            <p style="color: #666;">Gestion des demandes de besoins</p>
        </div>
        <a href="/besoins/ajout" class="btn btn-success">➕ Nouvelle demande</a>
    </div>

    <?php if (!empty($message)): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
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
                        <td><strong>#<?php echo $besoin->getId(); ?></strong></td>
                        <td><?php echo htmlspecialchars($besoin->getRegionNom()); ?></td>
                        <td><?php echo htmlspecialchars($besoin->getVilleNom()); ?></td>
                        <td><?php echo htmlspecialchars($besoin->getArticleNom()); ?></td>
                        <td><span class="badge <?php echo $besoin->getCategorieClass(); ?>"><?php echo htmlspecialchars($besoin->getCategorie()); ?></span></td>
                        <td><?php echo $besoin->getQuantiteFormatee(); ?> <?php echo htmlspecialchars($besoin->getUnite()); ?></td>
                        <td><?php echo $besoin->getPrixUnitaireFormate(); ?> Ar</td>
                        <td><?php echo $besoin->getDateFormatee(); ?></td>
                        <td>
                            <div class="actions">
                                <a href="/besoins/<?php echo $besoin->getId(); ?>/historique" class="btn-small btn-info">📜</a>
                                <a href="/besoins/<?php echo $besoin->getId(); ?>/edit" class="btn-small btn-warning">✏️</a>
                                <form method="POST" action="/besoins/<?php echo $besoin->getId(); ?>/delete" style="display:inline;" onsubmit="return confirm('Supprimer ce besoin ?');">
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
