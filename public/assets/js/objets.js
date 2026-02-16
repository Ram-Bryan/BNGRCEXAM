/**
 * objets.js — Recherche AJAX temps réel + preview photos + formulaires objets
 */
$(document).ready(function () {
    var baseUrl = $('meta[name="base-url"]').attr('content') || '';

    // ============================
    // Recherche AJAX temps réel
    // ============================
    var searchTimer = null;

    function rechercherObjets() {
        var keyword = $('#searchKeyword').val();
        var categorieId = $('#searchCategorie').val();

        $.ajax({
            url: baseUrl + '/objets/search',
            type: 'GET',
            data: { keyword: keyword, categorie_id: categorieId },
            dataType: 'json',
            success: function (data) {
                afficherResultats(data);
            },
            error: function () {
                $('#objetsContainer').html('<div class="col-12 text-center"><p>Erreur lors de la recherche.</p></div>');
            }
        });
    }

    function afficherResultats(objets) {
        var container = $('#objetsContainer');
        container.empty();

        if (objets.length === 0) {
            container.html('<div class="col-12 text-center"><p>Aucun objet trouvé.</p></div>');
            return;
        }

        objets.forEach(function (obj) {
            var prix = obj.prix_estime ? parseFloat(obj.prix_estime).toFixed(2) + ' Ar' : 'À négocier';
            var card = '<div class="col-lg-4 col-md-6 mb-4">' +
                '<div class="item">' +
                '<div class="thumb">' +
                '<img src="' + baseUrl + '/assets/images/products/' + obj.photo + '" alt="" style="width:100%;height:250px;object-fit:cover;">' +
                '<div class="hover-content">' +
                '<ul>' +
                '<li><a href="' + baseUrl + '/objets/' + obj.id + '"><i class="fa fa-eye"></i></a></li>' +
                '</ul>' +
                '</div>' +
                '</div>' +
                '<div class="down-content">' +
                '<h4>' + escapeHtml(obj.titre) + '</h4>' +
                '<span>' + prix + '</span>' +
                '<p><small>Par ' + escapeHtml(obj.proprietaire) + ' | ' + escapeHtml(obj.categorie) + '</small></p>' +
                '</div>' +
                '</div>' +
                '</div>';
            container.append(card);
        });
    }

    // Recherche sur frappe clavier (debounce 400ms)
    $('#searchKeyword').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(rechercherObjets, 400);
    });

    // Recherche sur changement catégorie
    $('#searchCategorie').on('change', function () {
        rechercherObjets();
    });

    // Bouton recherche
    $('#btnSearch').on('click', function (e) {
        e.preventDefault();
        rechercherObjets();
    });

    // ============================
    // Preview photos avec radio principal
    // ============================
    $('#photosInput').on('change', function () {
        var files = this.files;
        var preview = $('#photosPreview');
        preview.empty();

        for (var i = 0; i < files.length; i++) {
            (function (index) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var checked = index === 0 ? 'checked' : '';
                    var html = '<div class="col-3 mb-2 text-center">' +
                        '<img src="' + e.target.result + '" class="img-thumbnail" style="height:100px;width:100%;object-fit:cover;">' +
                        '<div class="mt-1">' +
                        '<label><input type="radio" name="photo_principale" value="' + index + '" ' + checked + '> Principale</label>' +
                        '</div>' +
                        '</div>';
                    preview.append(html);
                };
                reader.readAsDataURL(files[index]);
            })(i);
        }
    });

    // ============================
    // Formulaire ajout objet
    // ============================
    $('#formAjoutObjet').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: baseUrl + '/objets/create',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    alert(data.message || 'Objet créé avec succès');
                    window.location.href = baseUrl + '/mes-objets';
                } else {
                    alert(data.message || 'Erreur lors de la création');
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Erreur serveur');
            }
        });
    });

    // ============================
    // Formulaire modification objet
    // ============================
    $('#formEditObjet').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var objetId = $(this).data('id');

        $.ajax({
            url: baseUrl + '/objets/' + objetId + '/update',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    alert(data.message || 'Objet modifié avec succès');
                    window.location.href = baseUrl + '/mes-objets';
                } else {
                    alert(data.message || 'Erreur lors de la modification');
                }
            },
            error: function (xhr) {
                var resp = xhr.responseJSON;
                alert(resp && resp.message ? resp.message : 'Erreur serveur');
            }
        });
    });

    // ============================
    // Supprimer objet
    // ============================
    $(document).on('click', '.btn-delete-objet', function (e) {
        e.preventDefault();
        if (!confirm('Supprimer cet objet ?')) return;

        var objetId = $(this).data('id');
        $.ajax({
            url: baseUrl + '/objets/' + objetId + '/delete',
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    alert(data.message || 'Objet supprimé');
                    window.location.reload();
                } else {
                    alert(data.message || 'Erreur');
                }
            },
            error: function () { alert('Erreur serveur'); }
        });
    });

    // ============================
    // Utilitaire : escape HTML
    // ============================
    function escapeHtml(text) {
        if (!text) return '';
        var map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, function (m) { return map[m]; });
    }
});
