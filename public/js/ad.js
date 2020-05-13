$('#add-image').click(function(){
    // je recupere les numero des futurs champs que je vais crée
    const index = +$('#widgets-counter').val();
    // je recupere le prototype
    const tmpl = $('#annonce_images').data('prototype').replace(/__name__/g, index);

    // j'incete ce code au sein de la div
    $('#annonce_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    // gerais le bouton supprimer
    handleDeleteButtons();
    });

function handleDeleteButtons() {
$('button[data-action="delete"]').click(function(){
    const target = this.dataset.target;
    $(target).remove();
});
}
function updateCounter() {
    const count = +$('#ad_images div.form-group').length;

    $('#widgets-counter').val(count);
}
updateCounter();
handleDeleteButtons();