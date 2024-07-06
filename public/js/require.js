function validateFormAndRedirect() {
    var form = document.querySelector('.via_system form');

    if (form.checkValidity() === false) {
        form.reportValidity();
        return;
    }

    location.href = './via.html';
}
