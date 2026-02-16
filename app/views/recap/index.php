<?php include __DIR__ . '/../includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/assets/css/recap.css">

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

    .recap-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .recap-card {
        padding: 25px;
        border-radius: 12px;
        color: white;
    }

    .recap-card.blue {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .recap-card.green {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .recap-card.orange {
        background: linear-gradient(135deg, #f2994a, #f2c94c);
    }

    .recap-card.red {
        background: linear-gradient(135deg, #eb3349, #f45c43);
    }

    .recap-card.teal {
        background: linear-gradient(135deg, #00b4db, #0083b0);
    }

    .recap-card .value {
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .recap-card .label {
        font-size: 14px;
        opacity: 0.9;
    }

    .recap-card .icon {
        font-size: 40px;
        float: right;
        opacity: 0.7;
    }

    .ratio-bar {
        margin-top: 30px;
    }

    .ratio-bar-container {
        width: 100%;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        height: 40px;
        position: relative;
    }

    .ratio-bar-fill {
        height: 100%;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        font-weight: bold;
        transition: width 0.5s ease;
    }

    .ratio-low {
        background: linear-gradient(90deg, #eb3349, #f45c43);
    }

    .ratio-partial {
        background: linear-gradient(90deg, #f2994a, #f2c94c);
    }

    .ratio-complete {
        background: linear-gradient(90deg, #11998e, #38ef7d);
    }

    .section-title {
        font-size: 20px;
        margin: 30px 0 20px;
        color: #333;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
    }

    .detail-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .detail-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid;
    }

    .detail-card.nature {
        border-color: #28a745;
    }

    .detail-card.material {
        border-color: #6c757d;
    }

    .detail-card.argent {
        border-color: #ffc107;
    }

    .detail-card .title {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .detail-card .amount {
        font-size: 20px;
        font-weight: bold;
        color: #333;
    }

    .refresh-info {
        text-align: center;
        color: #999;
        font-size: 13px;
        margin-top: 20px;
    }

    #last-update {
        font-weight: bold;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .loading-spinner {
        font-size: 24px;
        color: #007bff;
    }
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

<script src="<?php echo Flight::get('flight.base_url'); ?>/assets/js/recap.js"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
