<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="page-container">
    
    <div class="header">
        <div>
            <h1>⚙️ Configuration</h1>
            <p style="color: #666;">Gestion des paramètres du système</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="success">✅
            <?php
            switch ($_GET['success']) {
                case 'created':
                    echo 'Configuration créée avec succès';
                    break;
                case 'updated':
                    echo 'Configuration mise à jour avec succès';
                    break;
                case 'deleted':
                    echo 'Configuration supprimée avec succès';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <!-- Formulaire d'ajout -->
    <div class="card">
        <h3>➕ Ajouter une nouvelle configuration</h3>
        <form method="POST" action="<?php echo $baseurl; ?>/configurations/create" style="display: grid; gap: 15px; max-width: 600px;">
            <div>
                <label><strong>Nom :</strong></label>
                <input type="text" name="nom" required placeholder="Ex: FRAIS_ACHAT_PERCENT" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label><strong>Valeur :</strong></label>
                <input type="text" name="valeur" required placeholder="Ex: 10" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <button type="submit" class="btn btn-success">✅ Ajouter</button>
        </form>
    </div>

    <!-- Liste des configurations -->
    <div class="card" style="margin-top: 20px;">
        <h3>📋 Liste des configurations</h3>
        
        <?php if (empty($configurations)): ?>
            <div class="empty-state">
                <h2>📭 Aucune configuration</h2>
                <p>Ajoutez une configuration pour commencer</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Valeur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($configurations as $config): ?>
                        <tr>
                            <td><strong>#<?php echo $config['id']; ?></strong></td>
                            <td>
                                <form method="POST" action="<?php echo $baseurl; ?>/configurations/update" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $config['id']; ?>">
                                    <input type="text" name="nom" value="<?php echo htmlspecialchars($config['nom']); ?>" 
                                           style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 3px;">
                            </td>
                            <td>
                                    <input type="text" name="valeur" value="<?php echo htmlspecialchars($config['valeur'] ?? ''); ?>" 
                                           style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 3px;">
                            </td>
                            <td>
                                    <button type="submit" class="btn btn-primary btn-sm" style="margin-right: 5px;">💾 Sauvegarder</button>
                                </form>
                                <form method="POST" action="<?php echo $baseurl; ?>/configurations/delete/<?php echo $config['id']; ?>" 
                                      style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette configuration ?');">
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
