// JS for simulation page

// Descriptions et thèmes des méthodes
const methodDescriptions = {
    'ancien': 'Les dons vont aux demandes les plus anciennes en premier',
    'petit': 'Les dons vont aux demandes avec les quantités les plus petites en premier'
};

const methodThemes = {
    'ancien': {
        headerBg: 'linear-gradient(135deg, #2196F3 0%, #1976D2 100%)',
        headerText: 'white',
        descriptionBg: '#e7f3ff',
        descriptionBorder: '#2196F3',
        boxBg: '#f0f7ff'
    },
    'petit': {
        headerBg: 'linear-gradient(135deg, #FF9800 0%, #F57C00 100%)',
        headerText: 'white',
        descriptionBg: '#fff3e0',
        descriptionBorder: '#FF9800',
        boxBg: '#fff8f0'
    }
};

// Mettre à jour la description quand on change de méthode
function updateMethodDescription() {
    const methode = document.getElementById('methode-distribution').value;
    const description = document.getElementById('method-description');
    description.innerHTML = `<strong>Méthode sélectionnée :</strong> ${methodDescriptions[methode]}<br>
        1. Cliquez sur <strong>SIMULER</strong> pour prévisualiser la distribution<br>
        2. Vérifiez les attributions proposées<br>
        3. Cliquez sur <strong>DISTRIBUER</strong> pour valider définitivement`;
}

// Mettre à jour le thème de la page selon la méthode sélectionnée
function updatePageTheme() {
    const methode = document.getElementById('methode-distribution').value;
    const theme = methodThemes[methode];
    
    // Mettre à jour le header
    const header = document.getElementById('header-simulation');
    header.style.background = theme.headerBg;
    header.style.color = theme.headerText;
    
    // Mettre à jour la description
    const description = document.getElementById('method-description');
    description.style.background = theme.descriptionBg;
    description.style.borderLeftColor = theme.descriptionBorder;
    
    // Mettre à jour la boîte de simulation
    const simulationBox = document.getElementById('simulation-box-main');
    simulationBox.style.borderTopColor = theme.descriptionBorder;
    simulationBox.style.borderTop = `5px solid ${theme.descriptionBorder}`;
}

// Initialiser le thème au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    updatePageTheme();
});

// Fonction simuler qui récupère la méthode sélectionnée
function simuler() {
    const methode = document.getElementById('methode-distribution').value;
    const btn = document.getElementById('btn-simuler');
    btn.disabled = true;
    btn.innerHTML = '⏳ Simulation...';

    document.getElementById('loading').style.display = 'block';

    const endpoint = methode === 'petit' ? 'simulation/simuler-petit' : 'simulation/simuler';

    fetch(window.BASE_URL + endpoint, {
            method: 'POST'
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
