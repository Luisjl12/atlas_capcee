document.addEventListener("DOMContentLoaded", function() {
            const tipoSelect = document.getElementById("tipo_documento");
            const otroTipoContainer = document.getElementById("otro_tipo_container");

            tipoSelect.addEventListener("change", function() {
                if (this.value === "Otro") {
                    otroTipoContainer.classList.remove("d-none");
                    document.getElementById("otro_tipo").setAttribute("required", true);
                } else {
                    otroTipoContainer.classList.add("d-none");
                    document.getElementById("otro_tipo").removeAttribute("required");
            }
        });
});