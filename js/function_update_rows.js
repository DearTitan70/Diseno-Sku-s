async function fetchDependentOptionsAndUpdateRow(rowElement) {
        if (!rowElement) {
             console.error('Row element is null.');
             return;
        }

        // Recoletar la informacion actual de toda la fila, incluyendo campos estaticos
        const formValues = {};
        const formFieldsInRow = rowElement.querySelectorAll('.campo-formulario[data-campo-nombre]');
        formFieldsInRow.forEach(field => {
             const fieldName = field.dataset.campoNombre;
             if (fieldName) {
                  if (field.tagName === 'SELECT' || field.tagName === 'INPUT' || field.tagName === 'TEXTAREA') {
                      formValues[fieldName] = field.value;
                  } else if (field.tagName === 'TD') {
                       formValues[fieldName] = field.textContent.trim();
                  }
             }
        })
        console.log('Form values collected from row:', formValues)
        ;

        // Recolectar los nombres de los campos dependientes en esta fila que necesitan ser actualizados
        const camposDestino = [];
        // Selecciona elementos con clase "campo-formulario" y atributos "data-campo-nombre" y "data-campo-type" igual a "dependent"
        const dependentSelectsInRow = rowElement.querySelectorAll('select.campo-formulario[data-campo-nombre][data-campo-type="dependent"]');

        dependentSelectsInRow.forEach(selectElement => {
            if (selectElement.dataset.campoNombre) {
                camposDestino.push(selectElement.dataset.campoNombre);
            }
        });

        // Si no hay selects dependientes en esta fila, no es necesario hacer la llamada a la API
        if (camposDestino.length === 0) {
            console.log('No dependent selects found in this row to update via API.');
            return;
        }

        // Preparar los datos para enviar a la API
        const postData = {
            campos_destino: camposDestino, // Solo enviar los campos dependientes
            form_values: formValues       // Enviar todos los valores de la fila
        };
         // Aplicar estado de carga a los selects dependientes
         dependentSelectsInRow.forEach(select => {
              select.style.opacity = '0.5';
         });

        try {
            const response = await fetch('../api/get_opciones.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(postData)
            });

             // Remover estado de carga de los selects dependientes
             dependentSelectsInRow.forEach(select => {
                  select.style.opacity = '1';
             });

            if (!response.ok) {
                const errorText = await response.text();
                console.error(`HTTP error fetching batched options for dependent fields: ${response.status} ${response.statusText}`, errorText);
                 // Manejo de error visual
                 dependentSelectsInRow.forEach(select => {
                 });
                 return;
            }

            const batchedResults = await response.json();

            if (typeof batchedResults !== 'object' || batchedResults === null) {
                 console.error('Batched response for dependent fields is not a valid object:', batchedResults);
                 // Manejo de respuesta no válida
                 dependentSelectsInRow.forEach(select => {
                 });
                 return;
            }

            // Iteraar sobre los selects dependientes en la fila
            // Iterar sobre los nombres de los campos dependientes para asegurar que sean los mismos que los de la respuesta
            camposDestino.forEach(targetFieldName => {
                // El php podria devolver un array vacio si no hay opciones para ese campo
                const opciones = batchedResults[targetFieldName];

                const targetSelectElement = rowElement.querySelector(`select.campo-formulario[data-campo-nombre="${targetFieldName}"][data-campo-type="dependent"]`);

                if (!targetSelectElement) {
                     // Podria no pasar si el select fue eliminado o no existe
                     return;
                }

                 const originalValue = targetSelectElement.value;

                // Limpiar opciones existetes (Permite el 'Seleccione' por defecto)
                const firstOption = targetSelectElement.querySelector('option:first-child');
                targetSelectElement.innerHTML = '';
                let defaultOptionAdded = false;
                if (firstOption && (firstOption.value === '' || firstOption.value === null)) {
                     targetSelectElement.appendChild(firstOption.cloneNode(true));
                     defaultOptionAdded = true;
                }

                // llENAR CON OPCIONES NUEVAS y manejar el valor original si existe
                if (Array.isArray(opciones)) { // Validar si las opciones son un array
                    opciones.forEach(opcion => {
                        const optionElement = document.createElement('option');
                        optionElement.value = opcion;
                        optionElement.textContent = opcion;
                        targetSelectElement.appendChild(optionElement);
                    });

                    // --- Manejar el valor previo ---
                    if ([...targetSelectElement.options].some(option => option.value === originalValue)) {
                        targetSelectElement.value = originalValue;
                    } else {
                         // Si el valor previo ya no es valido, mantener el valor por defecto
                         targetSelectElement.value = defaultOptionAdded ? (firstOption.value || '') : '';
                    }

                } else if (opciones !== undefined && opciones !== null) { 
                     console.error(`Response for dependent field ${targetFieldName} was not an array:`, opciones);
                }

            });


        } catch (error) {
            console.error('Error fetching dependent batched options:', error);
             // Remover estado de carga de los selects dependientes y aplicar un error visual
             dependentSelectsInRow.forEach(select => {
                  select.style.opacity = '1';
             });
        }
    }