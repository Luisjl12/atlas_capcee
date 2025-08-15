document.addEventListener('DOMContentLoaded', function () {
    $('#buscar').on('input', function () {
        const buscar = $(this).val();

        $.ajax({
            url: URL_BUSCAR_USUARIOS,
            method: 'GET',
            data: { buscar },
            success: function (response) {
                $('#tabla-usuarios').html(response.html);
            }
        });
    });
});
