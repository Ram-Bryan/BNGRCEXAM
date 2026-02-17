<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/home.css">

<div class="welcome">
    <h1>🏛️ Bienvenue sur BNGRC</h1>
    <p>Bureau National de Gestion des Risques et des Catastrophes<br>
        Système de gestion des besoins et distribution des dons</p>
</div>

<div class="dashboard">
    <a href="<?php echo $baseurl; ?>/besoins" class="card">
        <div class="card-icon">📋</div>
        <h2>Gestion des Besoins</h2>
        <p>Enregistrer et gérer les besoins des villes sinistrées (nature, matériaux, argent)</p>
    </a>
    <a href="<?php echo $baseurl; ?>/dons" class="card">
        <div class="card-icon">🎁</div>
        <h2>Saisie des Dons</h2>
        <p>Enregistrer les dons reçus (saisis librement, sans lien direct aux besoins)</p>
    </a>
    <a href="<?php echo $baseurl; ?>/simulation" class="card">
        <div class="card-icon">⚙️</div>
        <h2>Distribution</h2>
        <p>Simuler puis distribuer les dons aux besoins (prévisualisation avant validation)</p>
    </a>
    <a href="<?php echo $baseurl; ?>/achats" class="card">
        <div class="card-icon">🛒</div>
        <h2>Achats</h2>
        <p>Utiliser les dons en argent pour acheter des besoins nature/matériaux (avec <?php echo isset($fraisPercent) ? intval($fraisPercent) : 10; ?>% de frais)</p>
    </a>
    <a href="<?php echo $baseurl; ?>/recap" class="card">
        <div class="card-icon">📊</div>
        <h2>Récapitulation</h2>
        <p>Vue d'ensemble : besoins totaux, satisfaits et restants (actualisation AJAX)</p>
    </a>
    <a href="<?php echo $baseurl; ?>/stats" class="card">
        <div class="card-icon">📈</div>
        <h2>Statistiques</h2>
        <p>Tableau de bord par ville avec ratio de satisfaction</p>
    </a>
</div>

<?php include 'includes/footer.php'; ?>