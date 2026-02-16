<?php include __DIR__ . '/../includes/header.php'; ?>

<style>
    .page-container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 5px; color: #333; font-weight: bold; }
    select, input[type="number"], input[type="date"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
    select:focus, input:focus { outline: none; border-color: #28a745; }
    .btn { display: inline-block; padding: 12px 30px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-right: 10px; text-decoration: none; }
    .btn:hover { background-color: #218838; }
    .btn-secondary { background-color: #6c757d; }
    .btn-secondary:hover { background-color: #545b62; }
    .error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    .info-box { background-color: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    .priority-box { background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    .buttons { margin-top: 30px; }
    .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 11px; font-weight: bold; }
    .badge-nature { background-color: #28a745; color: white; }
    .badge-argent { background-color: #ffc107; color: #333; }
    .badge-material { background-color: #6c757d; color: white; }
</style>

<div class="page-container">
    <h1>🎁 Enregistrer un Don</h1>
    <p style="color: #666; margin-bottom: 30px;">Attribuer un don à un besoin existant</p>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">⚠️ Erreur : <?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <div class="priority-box">
        ⚡ <strong>Priorité :</strong> Les besoins sont triés par ancienneté. Les plus anciens apparaissent en premier et doivent être subventionnés en priorité.
    </div>

    <div class="info-box">
        ℹ️ Seuls les besoins non encore entièrement satisfaits sont affichés.
    </div>

    <?php if (empty($besoins)): ?>
        <div style="text-align: center; padding: 40px; color: #666;">
            <h2>✅ Tous les besoins sont satisfaits !</h2>
            <p>Il n'y a plus de besoin en attente de don.</p><br>
            <a href="/dons" class="btn btn-secondary">← Retour aux dons</a>
        </div>
    <?php else: ?>
        <form action="/dons/create" method="POST">
            <div class="form-group">
                <label for="idbesoins">Besoin à subventionner * (triés par ancienneté)</label>
                <select name="idbesoins" id="idbesoins" required>
                    <option value="">-- Sélectionnez un besoin --</option>
                    <?php foreach ($besoins as $besoin): ?>
                        <option value="<?php echo $besoin->id; ?>">
                            🏙️ <?php echo htmlspecialchars($besoin->ville_nom); ?> 
                            — <?php echo htmlspecialchars($besoin->article_nom); ?> 
                            [<?php echo ucfirst($besoin->categorie); ?>]
                            — Demandé: <?php echo $besoin->getQuantiteFormatee(); ?> <?php echo htmlspecialchars($besoin->unite); ?>
                            — Restant: <?php echo number_format($besoin->quantite_restante); ?> <?php echo htmlspecialchars($besoin->unite); ?>
                            — 📅 <?php echo $besoin->getDateFormatee(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantite">Quantité du don *</label>
                <input type="number" name="quantite" id="quantite" required min="1" placeholder="Entrez la quantité à donner">
            </div>

            <div class="form-group">
                <label for="date_livraison">Date de livraison *</label>
                <input type="date" name="date_livraison" id="date_livraison" required value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="buttons">
                <button type="submit" class="btn">✔️ Enregistrer le don</button>
                <a href="/dons" class="btn btn-secondary">❌ Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
