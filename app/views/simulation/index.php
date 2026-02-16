<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/simulation.css">

<div class="page-container">
    <div class="header">
        <div>
            <h1>⚙️ Simulation & Distribution</h1>
            <p style="color: #666;">Simuler puis distribuer les dons aux besoins</p>
        </div>
        <a href="<?php echo $baseurl; ?>/" class="btn">🏠 Accueil</a>
    </div>

    <!-- Zone de simulation -->
    <div class="simulation-box">
        <h2>🎯 Distribution des Dons(Plus ancien)</h2>
        <p style="opacity: 0.9; margin-bottom: 20px;">
            1. Cliquez sur <strong>SIMULER</strong> pour prévisualiser la distribution<br>
            2. Vérifiez les attributions proposées<br>
            3. Cliquez sur <strong>DISTRIBUER</strong> pour valider définitivement
        </p>
        <div class="buttons-row">
            <button type="button" class="btn btn-warning btn-lg" id="btn-simuler" onclick="simuler()">
                SIMULER
            </button>
            <button type="button" class="btn btn-success btn-lg" id="btn-distribuer" onclick="valider()" <?php echo empty($simulations) ? 'disabled' : ''; ?>>
                DISTRIBUER
            </button>
            <?php if (!empty($simulations)): ?>
                <button type="button" class="btn btn-danger btn-lg" onclick="annuler()">
                    ❌ Annuler simulation
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div id="loading" class="loading" style="display: none;">
        ⏳ Chargement en cours...
    </div>

    <!-- Résumé -->
    <div class="info-cards">
        <div class="info-card blue">
            <div class="value"><?php echo count($dons ?? []); ?></div>
            <div class="label">Dons disponibles</div>
        </div>
        <div class="info-card purple">
            <div class="value"><?php echo count($besoins ?? []); ?></div>
            <div class="label">Besoins non satisfaits</div>
        </div>
        <div class="info-card orange">
            <div class="value" id="nb-simulations"><?php echo count($simulations ?? []); ?></div>
            <div class="label">Distributions en simulation</div>
        </div>
        <div class="info-card green">
            <div class="value"><?php echo count($distribuees ?? []); ?></div>
            <div class="label">Distributions validées</div>
        </div>
    </div>

    <!-- Simulation en cours -->
    <?php if (!empty($simulations)): ?>
        <div class="simulation-preview" id="simulation-preview">
            <h4>⚠️ Simulation en cours - Non validée</h4>
            <p>Les distributions suivantes sont en attente de validation :</p>
            <table>
                <thead>
                    <tr>
                        <th>Don #</th>
                        <th>Donateur</th>
                        <th>→</th>
                        <th>Besoin #</th>
                        <th>Ville</th>
                        <th>Article</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($simulations as $sim): ?>
                        <tr>
                            <td><strong>#<?php echo $sim['don_id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($sim['donateur'] ?? 'Anonyme'); ?></td>
                            <td style="text-align: center;">→</td>
                            <td><strong>#<?php echo $sim['besoin_id']; ?></strong></td>
                            <td><?php echo htmlspecialchars($sim['ville_nom'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($sim['article_nom'] ?? '-'); ?></td>
                            <td><?php echo number_format($sim['quantite']); ?></td>
                            <td><span class="badge badge-simulation">En simulation</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Dons disponibles -->
    <h3 class="section-title">📦 Dons disponibles (non distribués)</h3>
    <?php if (empty($dons)): ?>
        <div class="empty-state">
            <p>Aucun don disponible à distribuer.</p>
            <a href="<?php echo $baseurl; ?>/dons/new" class="btn btn-success">➕ Ajouter un don</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Don #</th>
                    <th>Date</th>
                    <th>Donateur</th>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Quantité totale</th>
                    <th>Disponible</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><strong>#<?php echo $don['id']; ?></strong></td>
                        <td><?php echo date('d/m/Y', strtotime($don['date_don'])); ?></td>
                        <td><?php echo htmlspecialchars($don['donateur'] ?? 'Anonyme'); ?></td>
                        <td><?php echo htmlspecialchars($don['article_nom'] ?? $don['type_article_id']); ?></td>
                        <td>
                            <?php
                            $cat = $don['categorie'] ?? 'nature';
                            ?>
                            <span class="badge badge-<?php echo $cat; ?>">
                                <?php echo ucfirst($cat); ?>
                            </span>
                        </td>
                        <td><?php echo number_format($don['quantite']); ?></td>
                        <td><strong><?php echo number_format($don['quantite_disponible'] ?? $don['quantite']); ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Besoins non satisfaits -->
    <h3 class="section-title">📋 Besoins non satisfaits</h3>
    <?php if (empty($besoins)): ?>
        <div class="empty-state" style="background: #d4edda; color: #155724;">
            <h2>✅ Tous les besoins sont satisfaits !</h2>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Besoin #</th>
                    <th>Date</th>
                    <th>Ville</th>
                    <th>Article</th>
                    <th>Catégorie</th>
                    <th>Demandé</th>
                    <th>Reçu</th>
                    <th>Restant</th>
                    <th>Satisfaction</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($besoins as $besoin): ?>
                    <tr>
                        <td><strong>#<?php echo $besoin['id']; ?></strong></td>
                        <td><?php echo date('d/m/Y', strtotime($besoin['date_besoin'] ?? $besoin['date_saisie'] ?? 'now')); ?></td>
                        <td><?php echo htmlspecialchars($besoin['ville_nom']); ?></td>
                        <td><?php echo htmlspecialchars($besoin['article_nom']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $besoin['categorie']; ?>">
                                <?php echo ucfirst($besoin['categorie']); ?>
                            </span>
                        </td>
                        <td><?php echo number_format($besoin['quantite_demandee']); ?></td>
                        <td><?php echo number_format($besoin['quantite_recue']); ?></td>
                        <td><strong><?php echo number_format($besoin['quantite_restante']); ?></strong></td>
                        <td style="width: 150px;">
                            <div class="progress-bar-container">
                                <div class="progress-bar <?php echo $besoin['progress_class']; ?>" style="width: <?php echo $besoin['progress_width']; ?>%">
                                    <?php echo number_format($besoin['ratio_display'], 1); ?>%<?php if ($besoin['is_projected']): ?> 📊<?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Distributions validées récentes -->
    <?php if (!empty($distribuees)): ?>
        <h3 class="section-title">✅ Distributions validées (récentes)</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Don #</th>
                    <th>Donateur</th>
                    <th>→</th>
                    <th>Besoin #</th>
                    <th>Ville</th>
                    <th>Article</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($distribuees, 0, 10) as $dist): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($dist['date_distribution'])); ?></td>
                        <td><strong>#<?php echo $dist['don_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($dist['donateur'] ?? 'Anonyme'); ?></td>
                        <td style="text-align: center;">→</td>
                        <td><strong>#<?php echo $dist['besoin_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($dist['ville_nom'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($dist['article_nom'] ?? '-'); ?></td>
                        <td><?php echo number_format($dist['quantite']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="<?php echo $baseurl; ?>/assets/js/simulation.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
