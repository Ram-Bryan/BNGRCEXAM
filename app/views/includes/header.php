<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Gestion des Dons et Besoins</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .navbar {
            background: linear-gradient(135deg, #1a5276, #2980b9);
            padding: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .navbar-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 15px 20px;
            text-decoration: none;
        }

        .navbar-brand span {
            color: #f39c12;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            padding: 18px 20px;
            display: block;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
        }

        .main-content {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
    </style>
</head>

<body>
    <?php $baseurl = Flight::get('flight.base_url'); ?>
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
            </ul>
        </div>
    </nav>
    <div class="main-content">