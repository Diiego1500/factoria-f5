$(document).on('click', '.image', function () {
    let imageId = $(this).data('id');
    fetch(Routing.generate('getSingleImage', {id: imageId}))
        .then(response => response.json())
        .then(data => {
            $('#dynamic-pic').attr('src', awsBucketLink + data.filename);
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

$(document).ready(function(){
    $('.delete-image').on('click', function(e) {
        if (!confirm('¿Desea Eliminar esta imágen?')) {
            e.preventDefault();
        }
    });
});
