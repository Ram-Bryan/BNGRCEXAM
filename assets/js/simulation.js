// JS for simulation page
function simuler() {
  const btn = document.getElementById("btn-simuler");
  btn.disabled = true;
  btn.innerHTML = "⏳ Simulation...";

  document.getElementById("loading").style.display = "block";

  // Récupérer la logique sélectionnée
  const logic =
    document.querySelector('input[name="distribution_logic"]:checked')?.value ||
    "ancien";
  fetch(window.BASE_URL + "simulation/simuler", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "distribution_logic=" + encodeURIComponent(logic),
  })
    .then((response) => response.json())
    .then((data) => {
      document.getElementById("loading").style.display = "none";
      btn.disabled = false;
      btn.innerHTML = "👁️ SIMULER";

      if (data.success) {
        alert(
          "✅ " +
            data.message +
            "\n\nVérifiez les attributions et cliquez sur DISTRIBUER pour valider.",
        );
        location.reload();
      } else {
        alert("⚠️ " + data.message);
      }
    })
    .catch((error) => {
      document.getElementById("loading").style.display = "none";
      btn.disabled = false;
      btn.innerHTML = "👁️ SIMULER";
      alert("❌ Erreur: " + error.message);
    });
}

function valider() {
  if (
    !confirm(
      "⚠️ Êtes-vous sûr de vouloir DISTRIBUER ?\n\nCette action validera définitivement toutes les distributions en simulation.",
    )
  ) {
    return;
  }

  const btn = document.getElementById("btn-distribuer");
  btn.disabled = true;
  btn.innerHTML = "⏳ Distribution...";

  fetch(window.BASE_URL + "simulation/valider", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      btn.disabled = false;
      btn.innerHTML = "✅ DISTRIBUER";

      if (data.success) {
        alert("✅ " + data.message);
        location.reload();
      } else {
        alert("❌ Erreur: " + data.message);
      }
    })
    .catch((error) => {
      btn.disabled = false;
      btn.innerHTML = "✅ DISTRIBUER";
      alert("❌ Erreur: " + error.message);
    });
}

function annuler() {
  if (!confirm("Voulez-vous annuler la simulation en cours ?")) {
    return;
  }

  fetch(window.BASE_URL + "simulation/annuler", {
    method: "POST",
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("✅ Simulation annulée");
        location.reload();
      } else {
        alert("❌ Erreur: " + data.message);
      }
    })
    .catch((error) => {
      alert("❌ Erreur: " + error.message);
    });
}
