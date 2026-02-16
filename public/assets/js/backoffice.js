/**
 * backoffice.js — CRUD catégories AJAX pour le backoffice
 */
$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content') || '';

    // ============================
    // Ouvrir modal ajout catégorie
    // ============================
    $('#btnAddCategorie').on('click', function () {
        $('#catId').val('');
        $('#catLibelle').val('');
        $('#catSymbole').val('');
        $('#catDescription').val('');
        $('#modalCategorieTitle').html('<i class="fa fa-plus"></i> Ajouter une catégorie');
    });

    // ============================
    // Ouvrir modal édition catégorie
    // ============================
    $(document).on('click', '.btn-edit-cat', function () {
        var id = $(this).data('id');
        var libelle = $(this).data('libelle');
        var symbole = $(this).data('symbole');
        var description = $(this).data('description');

        $('#catId').val(id);
        $('#catLibelle').val(libelle);
        $('#catSymbole').val(symbole);
        $('#catDescription').val(description);
        $('#modalCategorieTitle').html('<i class="fa fa-pencil"></i> Modifier la catégorie');
        $('#modalCategorie').modal('show');
    });

    // ============================
    // Enregistrer catégorie (create ou update)
    // ============================
    $('#btnSaveCategorie').on('click', function () {
        var id = $('#catId').val();
        var libelle = $('#catLibelle').val().trim();
        var symbole = $('#catSymbole').val().trim();
        var description = $('#catDescription').val().trim();

        if (!libelle) {
            alert('Le libellé est requis.');
            return;
        }

        var url = id ? baseUrl + '/backoffice/categories/' + id + '/update' : baseUrl + '/backoffice/categories/create';

        $.ajax({
            url: url,
            type: 'POST',
            data: { libelle: libelle, symbole: symbole, description: description },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    $('#modalCategorie').modal('hide');
                    showAlert('success', data.message);
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
    // Supprimer catégorie
    // ============================
    $(document).on('click', '.btn-delete-cat', function () {
        if (!confirm('Supprimer cette catégorie ?')) return;

        var id = $(this).data('id');
        $.ajax({
            url: baseUrl + '/backoffice/categories/' + id + '/delete',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    showAlert('success', data.message);
                    $('#cat-row-' + id).fadeOut(300, function () { $(this).remove(); });
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
    // Helper: afficher alerte
    // ============================
    function showAlert(type, message) {
        var alert = $('#categorieAlert');
        alert.removeClass('d-none alert-success alert-danger alert-warning');
        alert.addClass('alert-' + type).text(message).show();
        setTimeout(function () { alert.fadeOut(); }, 3000);
    }
});
