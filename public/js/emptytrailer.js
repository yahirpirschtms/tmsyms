$(document).ready(function() {
 
    //Formatos fecha y hora
    flatpickr(".datetms", {
      dateFormat: "m/d/Y",  // Establece el formato como mes/día/año
      //defaultDate: "today",     // Establece la fecha y hora actuales como predeterminados
    });
    
    flatpickr(".datetimepicker", {
    enableTime: true,         // Habilita la selección de hora
    dateFormat: "m/d/y H:i:S",  // Establece el formato para incluir año, mes, día, hora, minuto y segundo
    time_24hr: true,          // Si quieres el formato de 24 horas
    enableSeconds: true,      // Habilita la selección de segundos
    //defaultDate: new Date(),
    });
});
    //Script para buscar el availability indicator en la pantalla de empty trailer
    $(document).ready(function () {
      //Funcion para buscar los carriers en la pantalla de empty trailer update
    function loadCarriersupdate() {
        var carrierRoute = $('#updateinputcarrier').data('url');
        $.ajax({
            url: carrierRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#updateinputcarrier');
                let selectedValue = select.val();
                //let selectedValue = "{{ old('inputcarrier') }}"; // Recupera el valor previo
                select.empty();
                select.append('<option selected disabled hidden></option>');

                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    data.forEach(item => {
                        select.append(`<option value="${item.id_company}">${item.CoName}</option>`);
                    });
                }

                if (selectedValue) {
                    select.val(selectedValue); // Restaura el valor anterior
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data carriers:', error);
            }
        });
    }

    //Ejecutar la funcion al picarle al select update
    $('#updateinputcarrier').on('focus', loadCarriersupdate);
    loadCarriersupdate();

    //Funcion para buscar los carriers en la pantalla de empty trailer
    function loadCarriers() {
        var carrierRoute = $('#inputcarrier').data('url');
        $.ajax({
            url: carrierRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputcarrier');
                let selectedValue = select.val();
                //let selectedValue = "{{ old('inputcarrier') }}"; // Recupera el valor previo
                select.empty();
                select.append('<option selected disabled hidden></option>');

                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    data.forEach(item => {
                        select.append(`<option value="${item.id_company}">${item.CoName}</option>`);
                    });
                }

                if (selectedValue) {
                    select.val(selectedValue); // Restaura el valor anterior
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data carriers:', error);
            }
        });
    }

    //Ejecutar la funcion al picarle al select
    $('#inputcarrier').on('focus', loadCarriers);
    loadCarriers();




    //Funcion para buscar las locations en la pantalla de empty trailer update
    function loadLocationsupdate() {
        var locationsRoute = $('#updateinputlocation').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#updateinputlocation');
                let selectedValue = select.val();
                //let selectedValue = "{{ old('inputlocation') }}"; // Recupera el valor previo
                select.empty();
                select.append('<option selected disabled hidden></option>');

                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    data.forEach(item => {
                        select.append(`<option value="${item.id_company}">${item.CoName}</option>`);
                    });
                }

                if (selectedValue) {
                    select.val(selectedValue); // Restaura el valor anterior
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data locations:', error);
            }
        });
    }

    //Ejecurtar la funcion al picarle al boton update 
    $('#updateinputlocation').on('focus', loadLocationsupdate);
    loadLocationsupdate();

    //Funcion para buscar las locations en la pantalla de empty trailer
    function loadLocations() {
        var locationsRoute = $('#inputlocation').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputlocation');
                let selectedValue = select.val();
                //let selectedValue = "{{ old('inputlocation') }}"; // Recupera el valor previo
                select.empty();
                select.append('<option selected disabled hidden></option>');

                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    data.forEach(item => {
                        select.append(`<option value="${item.id_company}">${item.CoName}</option>`);
                    });
                }

                if (selectedValue) {
                    select.val(selectedValue); // Restaura el valor anterior
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data locations:', error);
            }
        });
    }

    //Ejecurtar la funcion al picarle al boton 
    $('#inputlocation').on('focus', loadLocations);
    loadLocations();




    //Funcion para buscar el availability indicator en la pantalla de empty trailer update
    function loadAvailabilityIndicatorupdate() {
        var availabilityRoute = $('#updateinputavailabilityindicator').data('url');
          $.ajax({
              url: availabilityRoute,
              method: 'GET',
              success: function (data) {
                  let select = $('#updateinputavailabilityindicator');
                  let selectedValue = select.val();
                  //let selectedValue = "{{ old('inputavailabilityindicator') }}"; // Recupera el valor previo
                  select.empty(); // Limpia el select eliminando todas las opciones
                  select.append('<option selected disabled hidden></option>'); // Opción inicial

                  if (data.length === 0) {
                      select.append('<option disabled>No options available</option>');
                  } else {
                      data.forEach(item => {
                          select.append(`<option value="${item.gnct_id}">${item.gntc_description}</option>`);
                      });
                  }

                  if (selectedValue) {
                      select.val(selectedValue); // Restaura el valor anterior
                  }
              },
              error: function (xhr, status, error) {
                  console.error('Error fetching data Availability Indicators:', error);
              }
          });
    }

    // Cargar datos al enfocarse y al cargar la página update 
    $('#updateinputavailabilityindicator').on('focus', loadAvailabilityIndicatorupdate);
    loadAvailabilityIndicatorupdate();

    //Funcion para buscar el availability indicator en la pantalla de empty trailer update
    function loadAvailabilityIndicator() {
        var availabilityRoute = $('#inputavailabilityindicator').data('url');
          $.ajax({
              url: availabilityRoute,
              method: 'GET',
              success: function (data) {
                  let select = $('#inputavailabilityindicator');
                  let selectedValue = select.val();
                  //let selectedValue = "{{ old('inputavailabilityindicator') }}"; // Recupera el valor previo
                  select.empty(); // Limpia el select eliminando todas las opciones
                  select.append('<option selected disabled hidden></option>'); // Opción inicial

                  if (data.length === 0) {
                      select.append('<option disabled>No options available</option>');
                  } else {
                      data.forEach(item => {
                          select.append(`<option value="${item.gnct_id}">${item.gntc_description}</option>`);
                      });
                  }

                  if (selectedValue) {
                      select.val(selectedValue); // Restaura el valor anterior
                  }
              },
              error: function (xhr, status, error) {
                  console.error('Error fetching data Availability Indicators:', error);
              }
          });
    }

    // Cargar datos al enfocarse y al cargar la página update 
    $('#inputavailabilityindicator').on('focus', loadAvailabilityIndicator);
    loadAvailabilityIndicator();
  });

    //Crear nuevo trailer 
    $(document).ready(function() {
        // Validación en vivo cuando el usuario interactúa con los campos
        $('input, select').on('input change', function() {
            const field = $(this);
            const fieldName = field.attr('name');
            const errorContainer = field.next('.invalid-feedback');
    
            // Elimina las clases de error al interactuar con el campo
            field.removeClass('is-invalid');
            errorContainer.text(''); // Borra el mensaje de error
            
            // Validaciones en vivo para cada campo
            if (fieldName === 'inputidtrailer' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('El ID Trailer es obligatorio');
            }
    
            if (fieldName === 'inputdateofstatus' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('La Fecha de Status es obligatoria.');
            }
    
            if (fieldName === 'inputpalletsontrailer' && field.val().trim().length > 50) {
                field.addClass('is-invalid');
                errorContainer.text('El campo Pallets On Trailer no debe exceder los 50 caracteres.');
            }
    
            if (fieldName === 'inputpalletsonfloor' && field.val().trim().length > 50) {
                field.addClass('is-invalid');
                errorContainer.text('El campo Pallets On Floor no debe exceder los 50 caracteres.');
            }
    
            if (fieldName === 'inputcarrier' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('El Carrier es obligatorio.');
            }
    
            if (fieldName === 'inputavailabilityindicator' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('El Availability Indicator es obligatorio.');
            }
    
            if (fieldName === 'inputlocation' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('La Location es obligatoria.');
            }
    
            // Validación simple para las fechas (solo obligatorio)
            if (fieldName === 'inputdatein' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('La Fecha In es obligatoria.');
            }
    
            if (fieldName === 'inputdateout' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('La Fecha Out es obligatoria.');
            }
    
            if (fieldName === 'inputtransactiondate' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('La Transaction Date es obligatoria.');
            }
    
            if (fieldName === 'inputusername' && field.val().trim().length > 50) {
                field.addClass('is-invalid');
                errorContainer.text('El Username es obligatorio y no debe exceder los 50 caracteres');
            }
        });
    
        // Cuando el formulario se envía (al hacer clic en Save)
        $('#emptytrailerformm').submit(function(e) {
            e.preventDefault(); // Evita el envío del formulario
    
            const saveButton = $('#saveButton');
            const url = saveButton.data('url'); // URL para la petición AJAX
            let formData = new FormData(this);
    
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Si la respuesta es exitosa, mostrar el mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'Trailer agregado correctamente.',
                        confirmButtonText: 'Aceptar'
                    });
                    $('#closenewtrailerregister').click();
                    $('#refreshemptytrailertable').click();
                },
                error: function(xhr, status, error) {
                    // Limpia los errores anteriores
                    $('input, select').removeClass('is-invalid');
                    $('.invalid-feedback').text('');
                    
                    // Manejo de errores con SweetAlert2
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        const inputField = $('#' + field);
                        const errorContainer = inputField.next('.invalid-feedback');
                        
                        inputField.addClass('is-invalid');  // Marca el campo con error
                        errorContainer.text(errors[field][0]); // Muestra el error correspondiente
                    }
    
                    // Si hubo un error en el servidor, muestra una alerta
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: 'Hubo un problema al agregar el trailer. Por favor, inténtelo de nuevo.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        });
    });
    
    //Funcion para actualizar los datos de la tabla empty trailer al picarle al boton refresh
    // Función para actualizar la tabla y trailersData
    function updateTrailerTable() {
        const url = document.getElementById('refreshemptytrailertable').getAttribute('data-url'); // Obtén la URL desde el atributo data-url
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Aquí va el código para actualizar trailersData
                trailersData = data.reduce((acc, trailer) => {
                    acc[trailer.pk_trailer] = trailer;
                    return acc;
                }, {});

                // Actualiza la tabla
                const tbody = document.getElementById('emptyTrailerTableBody');
                tbody.innerHTML = ''; // Limpiar tabla antes de agregar nuevas filas
                data.forEach(trailer => {
                    const row = `
                        <tr id="trailer-${trailer.pk_trailer}" class="clickable-row" 
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#emptytrailer" 
                            aria-controls="emptytrailer" 
                            data-id="${trailer.pk_trailer}">                              
                            <td>${trailer.trailer_num}</td>
                            <td>${trailer.status}</td>
                            <td>${trailer.pallets_on_trailer}</td>
                            <td>${trailer.pallets_on_floor}</td>
                            <td>${trailer.carrier}</td>
                            <td>${trailer.availability_indicator?.gntc_description ?? 'N/A'}</td>
                            <td>${trailer.locations?.CoName ?? 'N/A'}</td>
                            <td>${trailer.date_in}</td>
                            <td>${trailer.date_out}</td>
                            <td>${trailer.transaction_date}</td>
                            <td>${trailer.username}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
                
                // Vuelve a agregar los listeners de clic después de actualizar la tabla
                const rows = document.querySelectorAll(".clickable-row");
                rows.forEach(row => {
                    row.addEventListener("click", function () {
                        const id = this.getAttribute("data-id");
                        const trailer = trailersData[id]; // Busca los datos del tráiler
                        if (trailer) {
                            // Asigna los datos al offcanvas
                            document.getElementById("pk_trailer").textContent = trailer.pk_trailer;
                            document.getElementById("offcanvas-id").textContent = trailer.trailer_num;
                            document.getElementById("offcanvas-status").textContent = trailer.status;
                            document.getElementById("offcanvas-pallets-on-trailer").textContent = trailer.pallets_on_trailer;
                            document.getElementById("offcanvas-pallets-on-floor").textContent = trailer.pallets_on_floor;
                            document.getElementById("offcanvas-carrier").textContent = trailer.carrier;
                            document.getElementById("offcanvas-availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : 'N/A';
                            document.getElementById("offcanvas-location").textContent = trailer.locations && trailer.locations.CoName ? trailer.locations.CoName : 'N/A';
                            document.getElementById("offcanvas-date-in").textContent = trailer.date_in;
                            document.getElementById("offcanvas-date-out").textContent = trailer.date_out;
                            document.getElementById("offcanvas-transaction-date").textContent = trailer.transaction_date;
                            document.getElementById("offcanvas-username").textContent = trailer.username;
                        } else {
                            console.error(`No data found for trailer ID ${id}`);
                        }
                    });
                });

            })
            .catch(error => console.error('Error:', error));
    }

    // Llama a la función cuando se hace clic en el botón de "Refresh"
    document.getElementById('refreshemptytrailertable').addEventListener('click', updateTrailerTable);

    // Configura la actualización automática cada 5 minutos (300,000 ms)
    setInterval(updateTrailerTable, 300000); // 300,000 ms = 5 minutos


  //Resetear Off Canvas al cerrarlo
  document.addEventListener("DOMContentLoaded", function () {
        const offcanvas = document.getElementById("newtrailerempty");
        const form = document.getElementById("emptytrailerformm");
        const inputs = document.querySelectorAll("#emptytrailerformm input, #emptytrailerformm select");


        // Escuchar el evento de cierre del offcanvas
        offcanvas.addEventListener("hidden.bs.offcanvas", function () {
            // Reiniciar el formulario
            form.reset();

            inputs.forEach((input) => {
            // Eliminar clases de validación
            input.classList.remove("is-invalid", "is-valid");

            // Eliminar mensajes de error si existen
            const errorDiv = input.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains("invalid-feedback")) {
                errorDiv.remove();
            }

            // Eliminar atributos añadidos (como required o disabled)
            input.removeAttribute('required');
            input.removeAttribute('disabled');
            });

            // Opcional: Si necesitas reiniciar select dinámicos o resetear errores manualmente
            const selects = form.querySelectorAll("select");
            selects.forEach(select => {
                select.selectedIndex = 0; // Restablecer al primer valor (placeholder)
            });

            

        });
  });
  
  //Mostrar datos del trailer en el canvas
  document.addEventListener("DOMContentLoaded", function () {
          const rows = document.querySelectorAll(".clickable-row");

          rows.forEach(row => {
              row.addEventListener("click", function () {
                  const id = this.getAttribute("data-id");
                  const trailer = trailersData[id]; // Busca los datos del tráiler
                  //console.log(trailer.pk_trailer);

                  if (trailer) {

                    //console.log(trailer.availabilityIndicator); // Verifica que availabilityIndicator esté cargado
                      // Asigna los datos al offcanvas
                      document.getElementById("pk_trailer").textContent = trailer.pk_trailer;
                      document.getElementById("offcanvas-id").textContent = trailer.trailer_num;
                      document.getElementById("offcanvas-status").textContent = trailer.status;
                      document.getElementById("offcanvas-pallets-on-trailer").textContent = trailer.pallets_on_trailer;
                      document.getElementById("offcanvas-pallets-on-floor").textContent = trailer.pallets_on_floor;
                      document.getElementById("offcanvas-carrier").textContent = trailer.carrier;
                      document.getElementById("offcanvas-availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : 'N/A';
                      document.getElementById("offcanvas-location").textContent = trailer.locations && trailer.locations.CoName ? trailer.locations.CoName : 'N/A';
                      document.getElementById("offcanvas-date-in").textContent = trailer.date_in;
                      document.getElementById("offcanvas-date-out").textContent = trailer.date_out;
                      document.getElementById("offcanvas-transaction-date").textContent = trailer.transaction_date;
                      document.getElementById("offcanvas-username").textContent = trailer.username;
                  } else {
                      console.error(`No data found for trailer ID ${id}`);
                  }
              });
          });
  });

  //Eliminacion de EmptyTrailers
  document.addEventListener('DOMContentLoaded', function () {
      const deleteButton = document.getElementById('deleteemptytrailercanvas'); // Botón de eliminar
      const closeButton = document.getElementById('closeoffcanvastrailersdetails'); // Botón de cerrar
      const offcanvasElement = document.getElementById('emptytrailer'); // El offcanvas

      deleteButton.addEventListener('click', function () {
        const trailerId = document.getElementById('pk_trailer').innerText.trim(); // ID del tráiler
        const baseUrl = deleteButton.getAttribute('data-url'); // Base URL

          /*const deleteUrl = deleteButton.getAttribute('data-url'); // URL de eliminación
        console.log(deleteUrl)*/
          if (trailerId) {
            // Construir la URL completa
            const deleteUrl = `${baseUrl}/${trailerId}`;
            //console.log(deleteUrl);
              // Usar SweetAlert para confirmar
              Swal.fire({
                  title: '¿Estás seguro?',
                  text: 'No podrás revertir esta acción',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Sí, eliminar',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {
                  if (result.isConfirmed) {
                      // Realizar la solicitud para eliminar el tráiler
                      fetch(deleteUrl, {
                          method: 'DELETE',
                          headers: {
                              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                              'Content-Type': 'application/json',
                          },
                      })
                      .then((response) => {
                          if (!response.ok) {
                              throw new Error('Error al eliminar el tráiler.');
                          }
                          return response.json();
                      })
                      .then((data) => {
                          // Eliminar el tráiler de trailersData
                          delete trailersData[trailerId]; // Elimina el tráiler de trailersData
                          //console.log(trailersData)
                          // Eliminar el tráiler de la tabla solo si la eliminación fue exitosa
                          const row = document.querySelector(`#trailer-${trailerId}`);
                          if (row) row.remove();

                          // Mostrar alerta de éxito
                          Swal.fire({
                              title: 'Eliminado',
                              text: data.message,
                              icon: 'success',
                              confirmButtonText: 'OK',
                          }).then(() => {
                              // Simular el clic en el botón de cerrar el offcanvas
                              closeButton.click();
                          });
                      })
                      .catch((error) => {
                          // Mostrar alerta de error
                          Swal.fire({
                              title: 'Error',
                              text: 'Hubo un problema al intentar eliminar el tráiler.',
                              icon: 'error',
                              confirmButtonText: 'OK',
                          });
                          console.error(error);
                      });
                  }
              });
          } else {
              // Mostrar alerta de error si no hay ID
              Swal.fire({
                  title: 'Error',
                  text: 'No se pudo identificar el tráiler.',
                  icon: 'error',
                  confirmButtonText: 'OK',
              });
          }
      });
  });

  //Abrir update offcanvas
  document.getElementById('updateemptytrailer').addEventListener('click', function () {
      // Obtener el ID del trailer actualmente seleccionado
      const selectedId = document.getElementById("pk_trailer").textContent.trim();
      const trailer = trailersData[selectedId];

      if (trailer) {
          // Cambiar el título del canvas concatenando el número del tráiler
          const titleElement = document.getElementById('updateoffcanvasWithBothOptionsLabel');
          const originalTitle = titleElement.dataset.originalTitle || titleElement.textContent; // Guardar el título original
          titleElement.dataset.originalTitle = originalTitle; // Almacenar en un atributo de datos personalizados
          titleElement.textContent = `${originalTitle} - Trailer ${trailer.trailer_num}`;

          // Llenar los campos del canvas de actualización
          document.getElementById('updateinputpktrailer').value = trailer.pk_trailer;
          document.getElementById('updateinputidtrailer').value = trailer.trailer_num;
          document.getElementById('updateinputdateofstatus').value = trailer.status || ''; // Ajusta según tu modelo
          document.getElementById('updateinputpalletsontrailer').value = trailer.pallets_on_trailer || '';
          document.getElementById('updateinputpalletsonfloor').value = trailer.pallets_on_floor || '';
          document.getElementById('updateinputcarrier').value = trailer.carrier || '';
          document.getElementById('updateinputavailabilityindicator').value = trailer.gnct_id_avaibility_indicator || '';
          document.getElementById('updateinputlocation').value = trailer.location || '';
          document.getElementById('updateinputdatein').value = trailer.date_in || '';
          document.getElementById('updateinputdateout').value = trailer.date_out || '';
          document.getElementById('updateinputtransactiondate').value = trailer.transaction_date || '';
          document.getElementById('updateinputusername').value = trailer.username || '';

          // Mostrar el canvas de actualización
          const updateCanvas = new bootstrap.Offcanvas(document.getElementById('updatenewtrailerempty'));
          updateCanvas.show();

          // Restaurar el título al cerrar el canvas
          document.getElementById('updatenewtrailerempty').addEventListener('hidden.bs.offcanvas', function () {
              titleElement.textContent = originalTitle; // Restaurar el título original
          });
      } else {
          // Mostrar SweetAlert si no se encuentra el tráiler
          Swal.fire({
              title: 'Error',
              text: 'No se encontró información para el tráiler seleccionado.',
              icon: 'error',
              confirmButtonText: 'Aceptar'
          });
      }
  });

 // Guardar los cambios al hacer clic en el botón de guardar
    /*document.getElementById("updatesaveButton").addEventListener("click", function () {
        const closeButtonUpdate = document.getElementById('closeupdatemptytrailerbutton'); // Botón de cerrar
        const refreshButtonUpdate = document.getElementById('refreshemptytrailertable'); // Botón de cerrar
        const closeButtonDetailsUpdate = document.getElementById('closeoffcanvastrailersdetails'); // Botón de cerrar


        const updatesaveButton = $('#updatesaveButton');
        const urlupdatetrailer = updatesaveButton.data('url'); // URL para la petición AJAX

        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Quieres guardar los cambios realizados?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, guardar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                // Crear datos para enviar
                const data = {
                    pk_trailer: document.getElementById("updateinputpktrailer").value,
                    trailer_num: document.getElementById("updateinputidtrailer").value,
                    status: document.getElementById("updateinputdateofstatus").value,
                    pallets_on_trailer: document.getElementById("updateinputpalletsontrailer").value,
                    pallets_on_floor: document.getElementById("updateinputpalletsonfloor").value,
                    carrier: document.getElementById("updateinputcarrier").value,
                    gnct_id_avaibility_indicator: document.getElementById("updateinputavailabilityindicator").value,
                    location: document.getElementById("updateinputlocation").value,
                    date_in: document.getElementById("updateinputdatein").value,
                    date_out: document.getElementById("updateinputdateout").value,
                    transaction_date: document.getElementById("updateinputtransactiondate").value,
                    username: document.getElementById("updateinputusername").value,
                    //_token: "{{ csrf_token() }}" // Agregar el token CSRF
                };
                console.log(data);

                // Enviar la solicitud PUT (cambiar de POST a PUT)
                fetch(urlupdatetrailer, {
                    method: "PUT", // Cambié de POST a PUT
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // CSRF token correctamente
                    },
                    body: JSON.stringify(data),
                })
                    .then((response) => {
                        if (response.ok) {
                            Swal.fire("¡Guardado!", "Los cambios se guardaron correctamente.", "success");
                            closeButtonUpdate.click();
                            closeButtonDetailsUpdate.click();
                            refreshButtonUpdate.click();
                        } else {
                            return response.json().then((data) => {
                                throw new Error(data.message || "Error al guardar los cambios.");
                            });
                        }
                    })
                    .catch((error) => {
                        Swal.fire("Error", error.message, "error");
                    });
            }
        });
    });*/

    // Función de validación en tiempo real
    const formFields = [
        'updateinputidtrailer',
        'updateinputdateofstatus',
        'updateinputpalletsontrailer',
        'updateinputpalletsonfloor',
        'updateinputcarrier',
        'updateinputavailabilityindicator',
        'updateinputlocation',
        'updateinputdatein',
        'updateinputdateout',
        'updateinputtransactiondate',
        'updateinputusername'
    ];

    // Validación de cada campo
    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`error-${fieldId}`);

        // Validación en tiempo real: keyup y blur
        field.addEventListener('keyup', function () {
            validateField(field, errorElement);
        });

        field.addEventListener('blur', function () {
            validateField(field, errorElement);
        });
    });

    // Función común para validar cada campo
    function validateField(field, errorElement) {
        // Validar si el campo está vacío
        if (field.value.trim() === '') {
            field.classList.add('is-invalid');
            errorElement.textContent = 'Este campo es obligatorio'; // Mensaje de error
        }
        // Validar si el campo excede los 50 caracteres
        else if ((field.id === 'updateinputusername' || field.id === 'updateinputpalletsonfloor' || field.id === 'updateinputpalletsontrailer') && field.value.length > 50) {
            field.classList.add('is-invalid');
            errorElement.textContent = 'Este campo no puede exceder los 50 caracteres'; // Mensaje de error
        } else {
            field.classList.remove('is-invalid');
            errorElement.textContent = ''; // Limpiar el mensaje de error
        }
    }

    // Ejecutar la validación al hacer clic en el botón de "guardar"
    document.getElementById("updatesaveButton").addEventListener("click", function () {
        let valid = true;

        // Validar cada campo antes de enviar
        formFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(`error-${fieldId}`);

            // Validar el campo
            if (field.value.trim() === '') {
                valid = false;
                field.classList.add('is-invalid');
                errorElement.textContent = 'Este campo es obligatorio';
            }
            // Validar si el campo excede los 50 caracteres (solo para los campos específicos)
            else if ((fieldId === 'updateinputusername' || fieldId === 'updateinputpalletsonfloor' || fieldId === 'updateinputpalletsontrailer') && field.value.length > 50) {
                valid = false;
                field.classList.add('is-invalid');
                errorElement.textContent = 'Este campo no puede exceder los 50 caracteres';
            } else {
                field.classList.remove('is-invalid');
                errorElement.textContent = '';
            }
        });

        // Si hay errores, no enviar el formulario
        if (!valid) {
            // Enfocar el primer campo con error (si existe)
            const firstInvalidField = document.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.focus(); // Seleccionar el primer campo con error
            }
            return; // Detener el envío si hay errores
        }

        // Si todos los campos son válidos, proceder con la solicitud de actualización
        const closeButtonUpdate = document.getElementById('closeupdatemptytrailerbutton');
        const refreshButtonUpdate = document.getElementById('refreshemptytrailertable');
        const closeButtonDetailsUpdate = document.getElementById('closeoffcanvastrailersdetails');
        const updatesaveButton = $('#updatesaveButton');
        const urlupdatetrailer = updatesaveButton.data('url');

        Swal.fire({
            title: "¿Estás seguro?",
            text: "¿Quieres guardar los cambios realizados?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, guardar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                const data = {
                    pk_trailer: document.getElementById("updateinputpktrailer").value,
                    trailer_num: document.getElementById("updateinputidtrailer").value,
                    status: document.getElementById("updateinputdateofstatus").value,
                    pallets_on_trailer: document.getElementById("updateinputpalletsontrailer").value,
                    pallets_on_floor: document.getElementById("updateinputpalletsonfloor").value,
                    carrier: document.getElementById("updateinputcarrier").value,
                    gnct_id_avaibility_indicator: document.getElementById("updateinputavailabilityindicator").value,
                    location: document.getElementById("updateinputlocation").value,
                    date_in: document.getElementById("updateinputdatein").value,
                    date_out: document.getElementById("updateinputdateout").value,
                    transaction_date: document.getElementById("updateinputtransactiondate").value,
                    username: document.getElementById("updateinputusername").value,
                };

                fetch(urlupdatetrailer, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(data),
                })
                    .then((response) => {
                        if (response.ok) {
                            Swal.fire("¡Guardado!", "Los cambios se guardaron correctamente.", "success");
                            closeButtonUpdate.click();
                            closeButtonDetailsUpdate.click();
                            refreshButtonUpdate.click();
                        } else {
                            return response.json().then((data) => {
                                throw new Error(data.message || "Error al guardar los cambios.");
                            });
                        }
                    })
                    .catch((error) => {
                        Swal.fire("Error", error.message, "error");
                    });
            }
        });
    });

    // Limpiar los mensajes de error al cerrar el modal (tanto al hacer clic en el botón de cerrar como al hacer clic fuera del modal)
    $(document).ready(function() {
        const updatecanvas = document.getElementById("updatenewtrailerempty");
        // Verifica si el evento 'hidden.bs.modal' se está registrando correctamente
        updatecanvas.addEventListener("hidden.bs.offcanvas", function () {
            //console.log("Modal se ha cerrado");  // Asegúrate de que este log se imprime
    
            // Limpiar los mensajes de error y las clases de error
            const fields = [
                'updateinputidtrailer',
                'updateinputdateofstatus',
                'updateinputpalletsontrailer',
                'updateinputpalletsonfloor',
                'updateinputcarrier',
                'updateinputavailabilityindicator',
                'updateinputlocation',
                'updateinputdatein',
                'updateinputdateout',
                'updateinputtransactiondate',
                'updateinputusername'
            ];
    
            fields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                const errorElement = document.getElementById(`error-${fieldId}`);
    
                if (field) {
                    // Limpiar mensaje de error y quitar la clase 'is-invalid'
                    field.classList.remove('is-invalid');  // Quitar clase 'is-invalid'
    
                    if (errorElement) {
                        errorElement.textContent = '';  // Limpiar mensaje de error
                    }
                } else {
                    console.log(`No se encontró el campo: ${fieldId}`);  // Verifica si no encuentras los campos
                }
            });
        });
    });
    
    /*document.getElementById('createshipmentwithemptytrailer').addEventListener('click', function() {
        // Obtener los valores de los campos en el offcanvas
        const origin = document.getElementById("offcanvas-location").textContent;
        const trailerId = document.getElementById("offcanvas-id").textContent;
    
        // Almacenar los datos en sessionStorage
        sessionStorage.setItem('shipment_origin', origin);
        sessionStorage.setItem('trailer_id', trailerId);
    
        // Obtener la URL de la ruta desde el atributo data-url
        const url = this.getAttribute('data-url');
    
        // Redirigir a la ruta de creación de workflow start
        window.location.href = url;
    });

    */


    //Enviar datos al formulario TrafficWorkflowStart con el boton shipment
    document.getElementById('createshipmentwithemptytrailer').addEventListener('click', function() {
        // Obtener la URL desde el atributo data-url
        const url = this.getAttribute('data-url');
    
        // Obtener los valores de los campos en el offcanvas
        const origin = document.getElementById("offcanvas-location").textContent;
        const trailerId = document.getElementById("offcanvas-id").textContent;
    
        // Construir la URL con los parámetros necesarios
        const redirectUrl = `${url}?origin=${encodeURIComponent(origin)}&trailerId=${encodeURIComponent(trailerId)}`;
    
        // Redirigir a la URL con los parámetros
        window.location.href = redirectUrl;
    });
    