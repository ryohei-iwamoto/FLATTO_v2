document.addEventListener('DOMContentLoaded', function() {
    var addForm = document.getElementById('addFavoriteForm');
    var removeForm = document.getElementById('removeFavoriteForm');

    if (addForm) {
        addForm.addEventListener('submit', function(event) {
            handleFormSubmit(event, 'add');
        });
    }

    if (removeForm) {
        removeForm.addEventListener('submit', function(event) {
            handleFormSubmit(event, 'remove');
        });
    }
});

function handleFormSubmit(event, actionType) {
    event.preventDefault();

    var form = event.target;
    var formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        updateButtonsDisplay(data.favorite);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateButtonsDisplay(favorite) {
    var addForm = document.getElementById('addFavoriteForm');
    var removeForm = document.getElementById('removeFavoriteForm');

    if (favorite === 1) {
        if (addForm) addForm.style.display = 'none';
        if (removeForm) removeForm.style.display = 'flex';
    } else {
        if (addForm) addForm.style.display = 'flex';
        if (removeForm) removeForm.style.display = 'none';
    }
}
