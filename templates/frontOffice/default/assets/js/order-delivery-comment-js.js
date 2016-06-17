$('#modaleComm').on('hidden.bs.modal', function (e) {
    $('#cart').append('<div class="alert alert-success" role="alert">{intl l="Merci nous avons bien pris en compte votre commentaire "}</div>');
})