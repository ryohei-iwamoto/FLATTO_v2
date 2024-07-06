function clearFormElements() {
    var form = document.querySelector('.via_system');
    var inputs = form.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type == 'text') {
            inputs[i].value = '';
        }
    }

    var selectMeans = form.querySelector('select[name="means"]');
    selectMeans.value = 'driving';

    var checkboxes = document.querySelectorAll('.check_btn');
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = false;
    }
}