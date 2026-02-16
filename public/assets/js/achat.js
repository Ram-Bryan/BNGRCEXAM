// JS for achat page
function confirmAchat(form, prixUnitaire, fraisPercent, argentDispo) {
    const quantite = parseInt(form.querySelector('input[name="quantite"]').value);
    const montantHT = quantite * prixUnitaire;
    const frais = montantHT * (fraisPercent / 100);
    const total = montantHT + frais;

    if (total > argentDispo) {
        alert('❌ Argent insuffisant !\n\nMontant requis: ' + total.toLocaleString('fr-FR') + ' Ar\nDisponible: ' + argentDispo.toLocaleString('fr-FR') + ' Ar');
        return false;
    }

    return confirm('Confirmer l\'achat ?\n\nQuantité: ' + quantite + '\nMontant HT: ' + montantHT.toLocaleString('fr-FR') + ' Ar\nFrais (' + fraisPercent + '%): ' + frais.toLocaleString('fr-FR') + ' Ar\nTotal TTC: ' + total.toLocaleString('fr-FR') + ' Ar');
}
