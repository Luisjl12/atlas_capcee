
function toggleInput(selectId, inputId) {
        const select = document.getElementById(selectId);
        const input = document.getElementById(inputId);

        select.addEventListener("change", function() {
            if (this.value === "nuevo") {
                input.classList.remove("d-none");
                input.required = true;
            } else {
                input.classList.add("d-none");
                input.required = false;
                input.value = '';
            }


            // Si se seleccionó un municipio, hacer solicitud AJAX para obtener localidades
            if (selectId === "id_municipio" && this.value !== "" && this.value !== "nuevo") {
                fetch(`/municipios/${this.value}/localidades`)
                    .then(response => response.json())
                    .then(data => {
                        const localidadSelect = document.getElementById("select_localidad");
                        localidadSelect.innerHTML = '<option value="">Seleccione una localidad</option>';
                        localidadSelect.disabled = false;

                        data.forEach(localidad => {
                            const option = document.createElement("option");
                            option.value = localidad.id;
                            option.textContent = localidad.nombre_localidad;
                            localidadSelect.appendChild(option);
                        });
                        const otroOption = document.createElement("option");
                        otroOption.value = "nuevo";
                        otroOption.textContent = "Otro...";
                        localidadSelect.appendChild(otroOption);

                    })
                    .catch(error => console.error("Error al cargar localidades:", error));
            }
        });
    }

    // Aplica la función a cada combo
toggleInput("id_municipio", "input_nuevo_municipio");
toggleInput("select_localidad", "input_nuevo_localidad");
toggleInput("select_corde", "input_nuevo_corde");
