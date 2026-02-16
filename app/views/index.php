<?php include 'includes/header.php'; ?>

<style>
    .dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: transform 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .card-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    .card h2 {
        font-size: 18px;
        color: #333;
        margin-bottom: 8px;
    }

    .card p {
        color: #666;
        font-size: 14px;
    }

    .welcome {
        background: linear-gradient(135deg, #1a5276, #2980b9);
        color: white;
        padding: 40px;
        border-radius: 10px;
        margin-bottom: 30px;
        text-align: center;
    }

    .welcome h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .welcome p {
        font-size: 16px;
        opacity: 0.9;
    }
</style>

<div class="welcome">
    <h1>🏛️ Bienvenue sur BNGRC</h1>
    <p>Bureau National de Gestion des Risques et des Catastrophes<br>
        Système de gestion des besoins et distribution des dons</p>
</div>

<div class="dashboard">
    <a href="/besoins" class="card">
        <div class="card-icon">📋</div>
        <h2>Gestion des Besoins</h2>
        <p>Enregistrer et gérer les besoins des villes sinistrées (nature, matériaux, argent)</p>
    </a>
    <a href="/dons" class="card">
        <div class="card-icon">🎁</div>
        <h2>Saisie des Dons</h2>
        <p>Enregistrer les dons reçus (saisis librement, sans lien direct aux besoins)</p>
    </a>
    <a href="/simulation" class="card">
        <div class="card-icon">⚙️</div>
        <h2>Distribution</h2>
        <p>Simuler puis distribuer les dons aux besoins (prévisualisation avant validation)</p>
    </a>
    <a href="/achats" class="card">
        <div class="card-icon">🛒</div>
        <h2>Achats</h2>
        <p>Utiliser les dons en argent pour acheter des besoins nature/matériaux (avec <?php echo FRAIS_ACHAT_PERCENT; ?>% de frais)</p>
    </a>
    <a href="/recap" class="card">
        <div class="card-icon">📊</div>
        <h2>Récapitulation</h2>
        <p>Vue d'ensemble : besoins totaux, satisfaits et restants (actualisation AJAX)</p>
    </a>
    <a href="/stats" class="card">
        <div class="card-icon">📈</div>
        <h2>Statistiques</h2>
        <p>Tableau de bord par ville avec ratio de satisfaction</p>
    </a>
</div>

<?php include 'includes/footer.php'; ?>