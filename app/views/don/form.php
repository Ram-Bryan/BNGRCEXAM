<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: bold;
    }

    select,
    input[type="number"],
    input[type="date"],
    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    select:focus,
    input:focus {
        outline: none;
        border-color: #28a745;
    }

    .btn {
        display: inline-block;
        padding: 12px 30px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-right: 10px;
        text-decoration: none;
    }

    .btn:hover {
        background-color: #218838;
    }

    .btn-secondary {
        background-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #545b62;
    }

    .error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .info-box {
        background-color: #d1ecf1;
        color: #0c5460;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .success-box {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    .buttons {
        margin-top: 30px;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-container">
    <h1>🎁 Enregistrer un Don</h1>
    <p style="color: #666; margin-bottom: 30px;">Saisir un nouveau don (sera distribué plus tard via la simulation)</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-box">✅ <?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="info-box">
        ℹ️ <strong>Note :</strong> Les dons sont saisis librement sans être liés directement à un besoin.
        La distribution des dons vers les besoins se fait via la page <a href="/simulation">Simulation</a>.
    </div>

    <form action="/dons/create" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="donateur">Nom du donateur</label>
                <input type="text" name="donateur" id="donateur" placeholder="Ex: Croix Rouge, Anonyme, M. Rakoto...">
            </div>

            <div class="form-group">
                <label for="date_don">Date du don *</label>
                <input type="date" name="date_don" id="date_don" required value="<?php echo date('Y-m-d'); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="type_article_id">Type d'article *</label>
            <select name="type_article_id" id="type_article_id" required>
                <option value="">-- Sélectionnez le type d'article --</option>
                <?php foreach ($typeArticles as $article): ?>
                    <option value="<?php echo $article['id']; ?>" data-categorie="<?php echo $article['categorie']; ?>" data-unite="<?php echo htmlspecialchars($article['unite']); ?>">
                        <?php echo htmlspecialchars($article['nom']); ?>
                        [<?php echo ucfirst($article['categorie']); ?>]
                        — <?php echo htmlspecialchars($article['unite']); ?>
                        <?php if ($article['categorie'] === 'argent'): ?>
                            💰
                        <?php endif; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="quantite">Quantité *</label>
                <input type="number" name="quantite" id="quantite" required min="1" placeholder="Entrez la quantité">
                <small id="unite-info" style="color: #666;"></small>
            </div>
        </div>

        <div class="buttons">
            <button type="submit" class="btn">✔️ Enregistrer le don</button>
            <a href="/dons" class="btn btn-secondary">❌ Annuler</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('type_article_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const unite = selected.getAttribute('data-unite');
        const categorie = selected.getAttribute('data-categorie');

        if (unite) {
            document.getElementById('unite-info').textContent = 'Unité: ' + unite;
        } else {
            document.getElementById('unite-info').textContent = '';
        }

        // Afficher un message spécial pour l'argent
        if (categorie === 'argent') {
            document.getElementById('unite-info').textContent = '💰 Don en argent (Ariary)';
        }
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>