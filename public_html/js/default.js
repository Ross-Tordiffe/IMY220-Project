// ======== Toast =========

const showError = (msg) => {
    $('#warning-text').html(msg.replace('-', ' '));
    $('.toast').toast('show');
}