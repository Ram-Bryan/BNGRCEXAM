// JS for don form page
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type_article_id');
    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
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
    }
});
