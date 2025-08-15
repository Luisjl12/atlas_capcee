document.addEventListener("DOMContentLoaded", function() {
        const municipioSelect = document.getElementById("id_municipio");
        const inputNuevoMunicipio = document.getElementById("input_nuevo_municipio");
        const localidadSelect = document.getElementById("select_localidad");

        function actualizarEstadoLocalidad() {
            const seleccion = municipioSelect.value;

            // Si seleccionó un municipio válido o seleccionó "nuevo" y escribió algo
            const municipioValido = (
                seleccion !== "" && seleccion !== "nuevo"
            ) || (
                seleccion === "nuevo" && inputNuevoMunicipio.value.trim() !== ""
            );

            // Habilita o deshabilita el select de localidad
            localidadSelect.disabled = !municipioValido;
        }

        // Escucha cambios en el select de municipio
        municipioSelect.addEventListener("change", actualizarEstadoLocalidad);

        // Escucha cambios en el input de nuevo municipio
        inputNuevoMunicipio.addEventListener("input", actualizarEstadoLocalidad);

        // Ejecuta una vez al cargar
        actualizarEstadoLocalidad();
});
