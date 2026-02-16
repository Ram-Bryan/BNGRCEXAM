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
        padding: 12px 25px;
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

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-lg {
        padding: 15px 30px;
        font-size: 16px;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th {
        background-color: #6c757d;
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

    .badge-argent {
        background-color: #ffc107;
        color: #333;
    }

    .badge-material {
        background-color: #6c757d;
        color: white;
    }

    .badge-simulation {
        background-color: #17a2b8;
        color: white;
    }

    .badge-validee {
        background-color: #28a745;
        color: white;
    }

    .progress-bar-container {
        width: 100%;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        height: 24px;
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .progress-low {
        background-color: #dc3545;
    }

    .progress-partial {
        background-color: #ffc107;
        color: #333;
    }

    .progress-complete {
        background-color: #28a745;
    }

    .simulation-box {
        background: linear-gradient(135deg, #667eea, #764ba2);
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 30px;
        color: white;
    }

    .simulation-box h2 {
        margin-bottom: 15px;
    }

    .buttons-row {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
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

    .info-card.purple {
        background: linear-gradient(135deg, #667eea, #764ba2);
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

    .info-card.red {
        background: linear-gradient(135deg, #eb3349, #f45c43);
    }

    .info-card .value {
        font-size: 28px;
        font-weight: bold;
    }

    .info-card .label {
        font-size: 13px;
        opacity: 0.9;
        margin-top: 5px;
    }

    .loading {
        text-align: center;
        padding: 30px;
        color: #666;
    }

    .section-title {
        margin-top: 40px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #666;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .simulation-preview {
        background: #fff3cd;
        border: 2px solid #ffc107;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .simulation-preview h4 {
        color: #856404;
        margin-bottom: 10px;
    }
</style>

<div class="page-container">
    <div class="header">
        <div>
            <h1>⚙️ Simulation & Distribution</h1>
            <p style="color: #666;">Simuler puis distribuer les dons aux besoins</p>
        </div>
        <a href="/" class="btn">🏠 Accueil</a>
    </div>

    <!-- Zone de simulation -->
    <div class="simulation-box">
        <h2>🎯 Distribution des Dons</h2>
        <p style="opacity: 0.9; margin-bottom: 20px;">
            1. Cliquez sur <strong>SIMULER</strong> pour prévisualiser la distribution<br>
            2. Vérifiez les attributions proposées<br>
            3. Cliquez sur <strong>DISTRIBUER</strong> pour valider définitivement
        </p>
        <div class="buttons-row">
            <button type="button" class="btn btn-warning btn-lg" id="btn-simuler" onclick="simuler()">
                👁️ SIMULER
            </button>
            <button type="button" class="btn btn-success btn-lg" id="btn-distribuer" onclick="valider()" <?php echo empty($simulations) ? 'disabled' : ''; ?>>
                ✅ DISTRIBUER
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
            <a href="/dons/new" class="btn btn-success">➕ Ajouter un don</a>
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
                            <?php
                            // Si simulation en cours, afficher le ratio projeté, sinon le ratio actuel
                            if (isset($hasSimulation) && $hasSimulation && isset($besoin['ratio_satisfaction_avec_simulation'])) {
                                $ratio = $besoin['ratio_satisfaction_avec_simulation'];
                                $isProjected = true;
                            } else {
                                $ratio = $besoin['ratio_satisfaction'];
                                $isProjected = false;
                            }
                            $class = $ratio >= 100 ? 'progress-complete' : ($ratio >= 50 ? 'progress-partial' : 'progress-low');
                            $width = min($ratio, 100);
                            ?>
                            <div class="progress-bar-container">
                                <div class="progress-bar <?php echo $class; ?>" style="width: <?php echo $width; ?>%">
                                    <?php echo number_format($ratio, 1); ?>%<?php if ($isProjected): ?> 📊<?php endif; ?>
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

<script>
    function simuler() {
        const btn = document.getElementById('btn-simuler');
        btn.disabled = true;
        btn.innerHTML = '⏳ Simulation...';

        document.getElementById('loading').style.display = 'block';

        fetch('/simulation/simuler', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                btn.disabled = false;
                btn.innerHTML = '👁️ SIMULER';

                if (data.success) {
                    alert('✅ ' + data.message + '\n\nVérifiez les attributions et cliquez sur DISTRIBUER pour valider.');
                    // Recharger pour afficher la satisfaction actualisée
                    location.reload();
                } else {
                    alert('⚠️ ' + data.message);
                }
            })
            .catch(error => {
                document.getElementById('loading').style.display = 'none';
                btn.disabled = false;
                btn.innerHTML = '👁️ SIMULER';
                alert('❌ Erreur: ' + error.message);
            });
    }

    function valider() {
        if (!confirm('⚠️ Êtes-vous sûr de vouloir DISTRIBUER ?\n\nCette action validera définitivement toutes les distributions en simulation.')) {
            return;
        }

        const btn = document.getElementById('btn-distribuer');
        btn.disabled = true;
        btn.innerHTML = '⏳ Distribution...';

        fetch('/simulation/valider', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '✅ DISTRIBUER';

                if (data.success) {
                    alert('✅ ' + data.message);
                    // Recharger pour afficher la satisfaction mise à jour
                    location.reload();
                } else {
                    alert('❌ Erreur: ' + data.message);
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = '✅ DISTRIBUER';
                alert('❌ Erreur: ' + error.message);
            });
    }

    function annuler() {
        if (!confirm('Voulez-vous annuler la simulation en cours ?')) {
            return;
        }

        fetch('/simulation/annuler', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Simulation annulée');
                    // Recharger pour afficher la satisfaction actualisée
                    location.reload();
                } else {
                    alert('❌ Erreur: ' + data.message);
                }
            })
            .catch(error => {
                alert('❌ Erreur: ' + error.message);
            });
    }
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>