// JS for recap page
function actualiser() {
    const loading = document.getElementById('loading');
    loading.style.display = 'flex';

    fetch(window.BASE_URL + 'recap/ajax')
        .then(response => response.json())
        .then(result => {
            loading.style.display = 'none';

            if (result.success) {
                const data = result.data;

                // Mettre à jour les valeurs
                document.getElementById('montant-total').textContent = formatNumber(data.montant_total_besoins) + ' Ar';
                document.getElementById('montant-satisfait').textContent = formatNumber(data.montant_satisfait) + ' Ar';
                document.getElementById('montant-restant').textContent = formatNumber(data.montant_restant) + ' Ar';
                document.getElementById('argent-dispo').textContent = formatNumber(data.argent_disponible) + ' Ar';

                // Ratio
                const ratio = data.ratio_global;
                const ratioBar = document.getElementById('ratio-bar');
                ratioBar.style.width = Math.min(ratio, 100) + '%';
                document.getElementById('ratio-value').textContent = ratio.toFixed(1) + '%';

                // Changer la classe du ratio
                ratioBar.className = 'ratio-bar-fill ' + (ratio >= 100 ? 'ratio-complete' : (ratio >= 50 ? 'ratio-partial' : 'ratio-low'));

                // Détails
                document.getElementById('dons-nature').textContent = formatNumber(data.total_dons_nature) + ' Ar';
                document.getElementById('dons-argent').textContent = formatNumber(data.total_dons_argent) + ' Ar';
                document.getElementById('achats-valides').textContent = formatNumber(data.total_achats_valides) + ' Ar';
                document.getElementById('achats-attente').textContent = formatNumber(data.total_achats_attente) + ' Ar';
                document.getElementById('nb-besoins').textContent = data.nombre_besoins;

                // Timestamp
                document.getElementById('last-update').textContent = new Date().toLocaleString('fr-FR');
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            alert('Erreur lors de l\'actualisation: ' + error.message);
        });
}

function formatNumber(num) {
    return Math.round(num).toLocaleString('fr-FR');
}
