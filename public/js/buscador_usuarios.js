document.addEventListener('DOMContentLoaded', function () {
    const $input = $('#buscar');

    //  Activar despliegue al cargar la tabla inicial
    inicializarDespliegueUsuarios();

    $input.on('input', function () {
        const buscar = $(this).val();

        $.ajax({
            url: URL_BUSCAR_USUARIOS,
            method: 'GET',
            data: { buscar },
            success: function (response) {
                $('#tabla-usuarios').html(response.html);

                //  Reactivar despliegue después de actualizar la tabla
                inicializarDespliegueUsuarios();
            }
        });
    });
});
