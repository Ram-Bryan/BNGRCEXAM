<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Gestion des Dons et Besoins</title>
    <?php $baseurl = rtrim(Flight::get('flight.base_url'), '/'); ?>
    <link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/common.css">
    <script>
        window.BASE_URL = '<?php echo $baseurl; ?>/';
    </script>
</head>

<body>
    <?php $baseurl = rtrim(Flight::get('flight.base_url'), '/'); ?>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="<?php echo $baseurl; ?>/" class="navbar-brand">🏛️ <span>BNGRC</span> - Gestion des Dons</a>
            <ul class="nav-links">
                <li><a href="<?php echo $baseurl; ?>/" <?php echo ($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/index') ? 'class="active"' : ''; ?>>🏠 Accueil</a></li>
                <li><a href="<?php echo $baseurl; ?>/besoins" <?php echo strpos($_SERVER['REQUEST_URI'], '/besoins') === 0 ? 'class="active"' : ''; ?>>📋 Besoins</a></li>
                <li><a href="<?php echo $baseurl; ?>/dons" <?php echo strpos($_SERVER['REQUEST_URI'], '/dons') === 0 ? 'class="active"' : ''; ?>>🎁 Dons</a></li>
                <li><a href="<?php echo $baseurl; ?>/achats" <?php echo strpos($_SERVER['REQUEST_URI'], '/achats') === 0 ? 'class="active"' : ''; ?>>🛒 Achats</a></li>
                <li><a href="<?php echo $baseurl; ?>/simulation" <?php echo strpos($_SERVER['REQUEST_URI'], '/simulation') === 0 ? 'class="active"' : ''; ?>>⚙️ Simulation</a></li>
                <li><a href="<?php echo $baseurl; ?>/recap" <?php echo strpos($_SERVER['REQUEST_URI'], '/recap') === 0 ? 'class="active"' : ''; ?>>📊 Récap</a></li>
                <li><a href="<?php echo $baseurl; ?>/stats" <?php echo strpos($_SERVER['REQUEST_URI'], '/stats') === 0 ? 'class="active"' : ''; ?>>📈 Stats</a></li>
                <li><a href="<?php echo $baseurl; ?>/configurations" <?php echo strpos($_SERVER['REQUEST_URI'], '/configurations') === 0 ? 'class="active"' : ''; ?>>⚙️ Config</a></li>
            </ul>
        </div>
    </nav>
    <div class="main-content">