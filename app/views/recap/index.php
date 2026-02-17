<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/recap.css">

<div class="loading-overlay" id="loading">
    <div class="loading-spinner">⏳ Actualisation...</div>
</div>

<div class="page-container">
    <div class="header">
        <div>
            <h1>📊 Récapitulation des Besoins</h1>
            <p style="color: #666;">Vue d'ensemble des besoins et de leur satisfaction</p>
        </div>
        <button type="button" class="btn btn-success" onclick="actualiser()">
            🔄 Actualiser
        </button>
    </div>

    <!-- Cartes principales -->
    <div class="recap-cards">
        <div class="recap-card blue">
            <div class="icon">📋</div>
            <div class="value" id="montant-total"><?php echo number_format($recap['montant_total_besoins'], 0, ',', ' '); ?> Ar</div>
            <div class="label">Montant total des besoins</div>
        </div>
        <div class="recap-card green">
            <div class="icon">✅</div>
            <div class="value" id="montant-satisfait"><?php echo number_format($recap['montant_satisfait'], 0, ',', ' '); ?> Ar</div>
            <div class="label">Montant satisfait</div>
        </div>
        <div class="recap-card orange">
            <div class="icon">⏳</div>
            <div class="value" id="montant-restant"><?php echo number_format($recap['montant_restant'], 0, ',', ' '); ?> Ar</div>
            <div class="label">Montant restant</div>
        </div>
        <div class="recap-card teal">
            <div class="icon">💰</div>
            <div class="value" id="argent-dispo"><?php echo number_format($recap['argent_disponible'], 0, ',', ' '); ?> Ar</div>
            <div class="label">Argent disponible pour achats</div>
        </div>
    </div>

    <!-- Barre de ratio global -->
    <div class="ratio-bar">
        <h3>Ratio de satisfaction global</h3>
        <?php
        $ratio = $recap['ratio_global'];
        $ratioClass = $ratio >= 100 ? 'ratio-complete' : ($ratio >= 50 ? 'ratio-partial' : 'ratio-low');
        ?>
        <div class="ratio-bar-container">
            <div class="ratio-bar-fill <?php echo $ratioClass; ?>" id="ratio-bar" style="width: <?php echo min($ratio, 100); ?>%">
                <span id="ratio-value"><?php echo number_format($ratio, 1); ?>%</span>
            </div>
        </div>
    </div>

    <!-- Détails des dons et achats -->
    <h3 class="section-title">💰 Détails des contributions</h3>
    <div class="detail-cards">
        <div class="detail-card nature">
            <div class="title">🌾 Dons en nature/matériaux</div>
            <div class="amount" id="dons-nature"><?php echo number_format($recap['total_dons_nature'], 0, ',', ' '); ?> Ar</div>
        </div>
        <div class="detail-card argent">
            <div class="title">💵 Dons en argent</div>
            <div class="amount" id="dons-argent"><?php echo number_format($recap['total_dons_argent'], 0, ',', ' '); ?> Ar</div>
        </div>
        <div class="detail-card material">
            <div class="title">🛒 Achats validés</div>
            <div class="amount" id="achats-valides"><?php echo number_format($recap['total_achats_valides'], 0, ',', ' '); ?> Ar</div>
        </div>
        <div class="detail-card" style="border-color: #17a2b8;">
            <div class="title">⏳ Achats en attente</div>
            <div class="amount" id="achats-attente"><?php echo number_format($recap['total_achats_attente'], 0, ',', ' '); ?> Ar</div>
        </div>
    </div>

    <!-- Statistiques -->
    <h3 class="section-title">📈 Statistiques</h3>
    <div class="detail-cards">
        <div class="detail-card" style="border-color: #007bff;">
            <div class="title">📋 Nombre de besoins</div>
            <div class="amount" id="nb-besoins"><?php echo $recap['nombre_besoins']; ?></div>
        </div>
        <?php foreach ($recap['stats_par_categorie'] as $stat): ?>
            <div class="detail-card <?php echo $stat['categorie']; ?>">
                <div class="title"><?php echo ucfirst($stat['categorie']); ?></div>
                <div class="amount"><?php echo number_format($stat['montant_total'], 0, ',', ' '); ?> Ar</div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="refresh-info">
        Dernière mise à jour : <span id="last-update"><?php echo date('d/m/Y H:i:s'); ?></span>
    </div>
</div>

<script src="<?php echo $baseurl; ?>/assets/js/recap.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
