/**
 * echanges.js — Proposer / Accepter / Refuser / Annuler échanges via AJAX
 */
$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content') || '';

    // ============================
    // Proposer un échange
    // ============================
    $('#formProposerEchange').on('submit', function (e) {
        e.preventDefault();

        var objetOffertId = $('#objetOffertId').val();
        var objetDemandeId = $('#objetDemandeId').val();

        if (!objetOffertId) {
            alert('Veuillez sélectionner un de vos objets à proposer.');
            return;
        }

        $.ajax({
            url: baseUrl + '/echanges/proposer',
            type: 'POST',
            data: {
                objet_offert_id: objetOffertId,
                objet_demande_id: objetDemandeId
            },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    alert(data.message || 'Échange proposé avec succès');
                    $('#modalEchange').modal('hide');
                    window.location.reload();
                } else {
                    alert(data.message || 'Erreur');
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Erreur serveur');
            }
        });
    });

    // ============================
    // Accepter un échange
    // ============================
    $(document).on('click', '.btn-accepter-echange', function (e) {
        e.preventDefault();
        if (!confirm('Accepter cet échange ?')) return;

        var echangeId = $(this).data('id');
        $.ajax({
            url: baseUrl + '/echanges/' + echangeId + '/accepter',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Erreur');
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Erreur serveur');
            }
        });
    });

    // ============================
    // Refuser un échange
    // ============================
    $(document).on('click', '.btn-refuser-echange', function (e) {
        e.preventDefault();
        if (!confirm('Refuser cet échange ?')) return;

        var echangeId = $(this).data('id');
        $.ajax({
            url: baseUrl + '/echanges/' + echangeId + '/refuser',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Erreur');
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Erreur serveur');
            }
        });
    });

    // ============================
    // Annuler un échange
    // ============================
    $(document).on('click', '.btn-annuler-echange', function (e) {
        e.preventDefault();
        if (!confirm('Annuler cet échange ?')) return;

        var echangeId = $(this).data('id');
        $.ajax({
            url: baseUrl + '/echanges/' + echangeId + '/annuler',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Erreur');
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Erreur serveur');
            }
        });
    });

    // ============================
    // Détail échange (modal)
    // ============================
    $(document).on('click', '.btn-detail-echange', function (e) {
        e.preventDefault();

        var echangeId = $(this).data('id');
        var modalBody = $('#detailEchangeBody');
        modalBody.html('<p class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</p>');
        $('#modalDetailEchange').modal('show');

        $.ajax({
            url: baseUrl + '/echanges/' + echangeId + '/detail',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (!data.success) {
                    modalBody.html('<p class="text-danger">' + (data.message || 'Erreur') + '</p>');
                    return;
                }

                var e = data.echange;
                var html = '<table class="table table-bordered">';
                html += '<tr><th>Demandeur</th><td>' + escapeHtml(e.demandeur) + '</td></tr>';
                html += '<tr><th>Receveur</th><td>' + escapeHtml(e.receveur) + '</td></tr>';
                html += '<tr><th>Statut</th><td>' + escapeHtml(e.statut) + '</td></tr>';
                html += '<tr><th>Date demande</th><td>' + (e.date_demande || '-') + '</td></tr>';
                html += '<tr><th>Date réponse</th><td>' + (e.date_reponse || '-') + '</td></tr>';
                html += '</table>';

                if (data.objets && data.objets.length > 0) {
                    html += '<h6>Objets concernés :</h6>';
                    html += '<table class="table table-sm table-bordered">';
                    html += '<thead><tr><th>Objet</th><th>Direction</th></tr></thead><tbody>';
                    data.objets.forEach(function (o) {
                        html += '<tr><td>' + escapeHtml(o.objet_titre || 'Objet #' + o.objet_id) + '</td>';
                        html += '<td><span class="badge ' + (o.direction === 'OFFERT' ? 'badge-info' : 'badge-primary') + '">' + o.direction + '</span></td></tr>';
                    });
                    html += '</tbody></table>';
                }

                modalBody.html(html);
            },
            error: function () {
                modalBody.html('<p class="text-danger">Erreur lors du chargement.</p>');
            }
        });
    });

    // ============================
    // Utilitaire
    // ============================
    function escapeHtml(text) {
        if (!text) return '';
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function (m) { return map[m]; });
    }
});
