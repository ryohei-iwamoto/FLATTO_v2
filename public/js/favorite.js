function handleFormSubmit(event) {
    event.preventDefault();

    var form = event.target;
    var formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.favorite === 1) {
            // お気に入りに登録された場合の処理
            document.getElementById('addFavoriteForm').style.display = 'none';
            document.getElementById('removeFavoriteForm').style.display = 'flex';
        } else {
            // お気に入りから解除された場合の処理
            document.getElementById('addFavoriteForm').style.display = 'flex';
            document.getElementById('removeFavoriteForm').style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
