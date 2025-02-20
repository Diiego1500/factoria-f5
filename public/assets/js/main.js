$(document).ready(function(){
    $('.delete-image').on('click', function(e) {
        if (!confirm('¿Desea Eliminar esta imágen?')) {
            e.preventDefault();
        }
    });
});
