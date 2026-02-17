// JS for simulation page
function simuler() {
    // Vérifier qu'un mode est sélectionné
    const modeRadios = document.getElementsByName('distribution_mode');
    let modeSelectionne = null;
    
    for (const radio of modeRadios) {
        if (radio.checked) {
            modeSelectionne = radio.value;
            break;
        }
    }
    
    if (!modeSelectionne) {
        alert('⚠️ Veuillez d\'abord choisir un mode de distribution');
        return;
    }
    
    const btn = document.getElementById('btn-simuler');
    btn.disabled = true;
    btn.innerHTML = '⏳ Simulation...';

    document.getElementById('loading').style.display = 'block';

    // Envoyer le mode sélectionné au backend
    const formData = new FormData();
    formData.append('distribution_logic', modeSelectionne);

    fetch(window.BASE_URL + 'simulation/simuler', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            btn.disabled = false;
            btn.innerHTML = '👁️ SIMULER';

            if (data.success) {
                alert('✅ ' + data.message + '\n\nVérifiez les attributions et cliquez sur DISTRIBUER pour valider.');
                location.reload();
            } else {
                alert('⚠️ ' + data.message);
            }
        })
        .catch(error => {
            document.getElementById('loading').style.display = 'none';
            btn.disabled = false;
            btn.innerHTML = '👁️ SIMULER';
            alert('❌ Erreur: ' + error.message);
        });
}

function valider() {
    if (!confirm('⚠️ Êtes-vous sûr de vouloir DISTRIBUER ?\n\nCette action validera définitivement toutes les distributions en simulation.')) {
        return;
    }

    const btn = document.getElementById('btn-distribuer');
    btn.disabled = true;
    btn.innerHTML = '⏳ Distribution...';

    fetch(window.BASE_URL + 'simulation/valider', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '✅ DISTRIBUER';

            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('❌ Erreur: ' + data.message);
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = '✅ DISTRIBUER';
            alert('❌ Erreur: ' + error.message);
        });
}

function annuler() {
    if (!confirm('Voulez-vous annuler la simulation en cours ?')) {
        return;
    }

    fetch(window.BASE_URL + 'simulation/annuler', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Simulation annulée');
                location.reload();
            } else {
                alert('❌ Erreur: ' + data.message);
            }
        })
        .catch(error => {
            alert('❌ Erreur: ' + error.message);
        });
}

function resetData() {
    if (!confirm('⚠️ ATTENTION : Réinitialisation complète !\n\n' +
        'Cette action va :\n' +
        '• Supprimer TOUTES les distributions (validées et simulées)\n' +
        '• Supprimer TOUS les achats\n' +
        '• Supprimer TOUT l\'historique\n' +
        '• Restaurer les besoins et dons initiaux\n\n' +
        '⚠️ Cette action est IRRÉVERSIBLE !\n\n' +
        'Voulez-vous vraiment continuer ?')) {
        return;
    }

    // Double confirmation pour éviter les erreurs
    if (!confirm('Dernière confirmation :\n\nÊtes-vous ABSOLUMENT SÛR de vouloir réinitialiser toutes les données ?')) {
        return;
    }

    const loadingDiv = document.getElementById('loading');
    if (loadingDiv) {
        loadingDiv.style.display = 'block';
        loadingDiv.innerHTML = '🔄 Réinitialisation en cours...';
    }

    fetch(window.BASE_URL + 'reset', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (loadingDiv) {
                loadingDiv.style.display = 'none';
            }

            if (data.success) {
                alert('✅ ' + data.message + '\n\n' +
                    'Statistiques :\n' +
                    '• Besoins restaurés : ' + (data.stats?.after?.besoins || 0) + '\n' +
                    '• Dons restaurés : ' + (data.stats?.after?.dons || 0) + '\n' +
                    '• Distributions supprimées : ' + (data.stats?.before?.distributions || 0) + '\n' +
                    '• Achats supprimés : ' + (data.stats?.before?.achats || 0));
                location.reload();
            } else {
                alert('❌ Erreur lors de la réinitialisation :\n\n' + data.message);
            }
        })
        .catch(error => {
            if (loadingDiv) {
                loadingDiv.style.display = 'none';
            }
            alert('❌ Erreur réseau : ' + error.message);
        });
}

