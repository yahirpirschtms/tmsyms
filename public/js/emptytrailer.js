$(document).ready(function() {
 
    //Formatos fecha y hora
    flatpickr(".datetms", {
      dateFormat: "m/d/Y",  // Establece el formato como mes/día/año
      //defaultDate: "today",     // Establece la fecha y hora actuales como predeterminados
    });
    
    flatpickr(".datetimepicker", {
    enableTime: true,         // Habilita la selección de hora
    dateFormat: "m/d/Y H:i:S",  // Establece el formato para incluir año, mes, día, hora, minuto y segundo
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
                errorContainer.text('ID Trailer is required.');
            }
    
            if (fieldName === 'inputdateofstatus' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Date of Status is required.');
            }
    
            /*if (fieldName === 'inputpalletsontrailer' && field.val().trim().length > 50) {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets On Trailer field must not exceed 50 characters.');
            }*/
            if (fieldName === 'inputpalletsontrailer' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets On Trailer is required.');
            }
    
            /*if (fieldName === 'inputpalletsonfloor' && field.val().trim().length > 50) {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets On Floor field must not exceed 50 characters.');
            }*/
                if (fieldName === 'inputpalletsonfloor' && field.val().trim().length === 0) {
                    field.addClass('is-invalid');
                    errorContainer.text('The Pallets On Floor is required.');
                }
    
            if (fieldName === 'inputcarrier' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Carrier is required.');
            }
    
            if (fieldName === 'inputavailabilityindicator' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Availability Indicator is required.');
            }
    
            if (fieldName === 'inputlocation' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Location is required.');
            }
    
            // Validación simple para las fechas (solo obligatorio)
            if (fieldName === 'inputdatein' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Date In is required.');
            }
    
            if (fieldName === 'inputdateout' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Date out is required.');
            }
    
            if (fieldName === 'inputtransactiondate' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Transaction Date is required.');
            }
    
            /*if (fieldName === 'inputusername' && field.val().trim().length > 50) {
                field.addClass('is-invalid');
                errorContainer.text('The Username is mandatory and must not exceed 50 characters.');
            }*/
            if (fieldName === 'inputusername' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('The Username is required.');
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
                        title: '¡Succes!',
                        text: 'Trailer successfully added.',
                        confirmButtonText: 'Ok'
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
                        text: 'There was a problem adding the trailer. Please try again.',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });
    });
    
    //Funcion para actualizar los datos de la tabla empty trailer al picarle al boton refresh
    // Función para actualizar la tabla y trailersData
    /*function updateTrailerTable() {
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
                        //console.log(trailer);
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
                            document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : 'N/A';
                            document.getElementById("pk_location").textContent = trailer.location;
                            document.getElementById("pk_carrier").textContent = trailer.carrier;
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
    setInterval(updateTrailerTable, 300000); // 300,000 ms = 5 minutos*/

    function updateTrailerTable() {
        // Obtener los valores de los filtros
        const search = document.getElementById('searchemptytrailergeneral').value;
        const trailerNum = document.getElementById('emptytrailerfilterinputidtrailer').value;
        const statusStart = document.getElementById('emptytrailerfilterinputdateofstartstatus').value;
        const statusEnd = document.getElementById('emptytrailerfilterinputdateofendstatus').value;
        const palletsOnTrailer = document.getElementById('emptytrailerfilterinputpalletsontrailer').value;
        const palletsOnFloor = document.getElementById('emptytrailerfilterinputpalletsonfloor').value;
        const carrier = document.getElementById('emptytrailerfilterinputcarrierpk').value;
        const availabilityIndicator = document.getElementById('emptytrailerfilterinputavailabilityindicatorpk').value;
        const location = document.getElementById('emptytrailerfilterinputlocationpk').value;
        const username = document.getElementById('emptytrailerfilterinputusername').value;
        const dateInStart = document.getElementById('emptytrailerfilterinputstartdatein').value;
        const dateInEnd = document.getElementById('emptytrailerfilterinputenddatein').value;
        const dateOutStart = document.getElementById('emptytrailerfilterinputstartdateout').value;
        const dateOutEnd = document.getElementById('emptytrailerfilterinputenddateout').value;
        const transactionDateStart = document.getElementById('emptytrailerfilterinputstarttransactiondate').value;
        const transactionDateEnd = document.getElementById('emptytrailerfilterinputendtransactiondate').value;
        
        // Construir la URL con los parámetros de filtro
        const url = new URL(document.getElementById('refreshemptytrailertable').getAttribute('data-url'));
        const params = new URLSearchParams(url.search);
    
        // Agregar los filtros a los parámetros de la URL
        params.set('search', search);
        params.set('trailer_num', trailerNum);
        params.set('status_start', statusStart);
        params.set('status_end', statusEnd);
        params.set('pallets_on_trailer', palletsOnTrailer);
        params.set('pallets_on_floor', palletsOnFloor);
        params.set('carrier', carrier);
        params.set('gnct_id_availability_indicator', availabilityIndicator);
        params.set('location', location);
        params.set('username', username);
        params.set('date_in_start', dateInStart);
        params.set('date_in_end', dateInEnd);
        params.set('date_out_start', dateOutStart);
        params.set('date_out_end', dateOutEnd);
        params.set('transaction_date_start', transactionDateStart);
        params.set('transaction_date_end', transactionDateEnd);
    
        url.search = params.toString();
        //console.log(url);
        fetch(url)
            .then(response => response.json())
            .then(data => {

                // Aquí va el código para actualizar trailersData
                trailersData = data.reduce((acc, trailer) => {
                    acc[trailer.pk_trailer] = trailer;
                    return acc;
                }, {});
                // Actualizar la tabla con los datos filtrados
                const tbody = document.getElementById('emptyTrailerTableBody');
                tbody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevas filas
    
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
                        //console.log(trailer);
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
                            document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : 'N/A';
                            document.getElementById("pk_location").textContent = trailer.location;
                            document.getElementById("pk_carrier").textContent = trailer.carrier;
                        } else {
                            console.error(`No data found for trailer ID ${id}`);
                        }
                    });
                });

            })
            .catch(error => console.error('Error:', error));
    }
    
    // Llamar la función cuando se hace clic en el botón de "Refresh" o cuando cambian los filtros
    document.getElementById('refreshemptytrailertable').addEventListener('click', updateTrailerTable);

    //Refresh de la tabla al aplicar un filtro
    //document.querySelectorAll('.filterapply').addEventListener('click', updateTrailerTable);

    //document.getElementById('applytraileridfilter').addEventListener('click', updateTrailerTable);
    
    // Configurar los eventos de cambio de los inputs
    const filterInputs = document.querySelectorAll('#filtersapplied input');
    filterInputs.forEach(input => {
        input.addEventListener('input', updateTrailerTable);
    });

    const filterGeneralInputs = document.querySelectorAll('#searchemptytrailergeneral');
    filterGeneralInputs.forEach(input => {
        input.addEventListener('input', updateTrailerTable);
    });
    
    // Actualización automática cada 5 minutos (300,000 ms)
    setInterval(updateTrailerTable, 5000000);
    

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
                  //console.log(trailer);

                  if (trailer) {

                    //console.log(trailer.availabilityIndicator); // Verifica que availabilityIndicator esté cargado
                      // Asigna los datos al offcanvas
                      document.getElementById("pk_trailer").textContent = trailer.pk_trailer;
                      document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : 'N/A' ;
                      document.getElementById("pk_location").textContent = trailer.location;
                      document.getElementById("pk_carrier").textContent = trailer.carrier;
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
                  title: 'Are you sure?',
                  text: 'You will not be able to reverse this action',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete',
                  cancelButtonText: 'Cancel',
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
                              throw new Error('Error deleting trailer.');
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
                              title: 'Deleted',
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
                              text: 'There was a problem trying to delete the trailer.',
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
                  text: 'The trailer could not be identified.',
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
              text: 'No information was found for the selected trailer.',
              icon: 'error',
              confirmButtonText: 'Ok'
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
            errorElement.textContent = 'This field is required'; // Mensaje de error
        }
        // Validar si el campo excede los 50 caracteres
        else if ((field.id === 'updateinputusername' || field.id === 'updateinputpalletsonfloor' || field.id === 'updateinputpalletsontrailer') && field.value.length > 50) {
            field.classList.add('is-invalid');
            errorElement.textContent = 'This field cannot exceed 50 characters'; // Mensaje de error
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
                errorElement.textContent = 'This field is required';
            }
            // Validar si el campo excede los 50 caracteres (solo para los campos específicos)
            else if ((fieldId === 'updateinputusername' || fieldId === 'updateinputpalletsonfloor' || fieldId === 'updateinputpalletsontrailer') && field.value.length > 50) {
                valid = false;
                field.classList.add('is-invalid');
                errorElement.textContent = 'This field cannot exceed 50 characters';
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
            title: "Are you sure?",
            text: "Do you want to save the changes made?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, save",
            cancelButtonText: "Cancel",
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
                            Swal.fire("Saved!", "The changes were saved successfully.", "success");
                            closeButtonUpdate.click();
                            closeButtonDetailsUpdate.click();
                            refreshButtonUpdate.click();
                        } else {
                            return response.json().then((data) => {
                                throw new Error(data.message || "Error saving changes.");
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
        const trailerId = document.getElementById("offcanvas-id").textContent;
        const status = document.getElementById("offcanvas-status").textContent;
        const palletsontrailer = document.getElementById("offcanvas-pallets-on-trailer").textContent;
        const palletsonfloor = document.getElementById("offcanvas-pallets-on-floor").textContent;
        const carrier = document.getElementById("pk_carrier").textContent;
        const availability = document.getElementById("pk_availability").textContent;
        const location = document.getElementById("pk_location").textContent;
        const datein = document.getElementById("offcanvas-date-in").textContent;
        const dateout = document.getElementById("offcanvas-date-out").textContent;
        const transaction = document.getElementById("offcanvas-transaction-date").textContent;
        const username = document.getElementById("offcanvas-username").textContent;
    
        // Construir la URL con los parámetros necesarios
        //const redirectUrl = `${url}?location=${encodeURIComponent(location)}&trailerId=${encodeURIComponent(trailerId)}&status=${encodeURIComponent(status)}`;
        const redirectUrl = `${url}?trailerId=${encodeURIComponent(trailerId)}
        &status=${encodeURIComponent(status)}
        &palletsontrailer=${encodeURIComponent(palletsontrailer)}
        &palletsonfloor=${encodeURIComponent(palletsonfloor)}
        &carrier=${encodeURIComponent(carrier)}
        &availability=${encodeURIComponent(availability)}
        &location=${encodeURIComponent(location)}
        &datein=${encodeURIComponent(datein)}
        &dateout=${encodeURIComponent(dateout)}
        &transaction=${encodeURIComponent(transaction)}
        &username=${encodeURIComponent(username)}`;

        console.log(redirectUrl);

        // Redirigir a la URL con los parámetros
        window.location.href = redirectUrl;
    });


    /*$(document).ready(function() {
        // Función para habilitar o deshabilitar el botón dependiendo si hay texto en el input
        function toggleApplyButton() {
            if ($('#inputapplytraileridfilter').val()) {
                $('#closeapplytraileridfilter').prop('disabled', true); // Deshabilita el botón
            } else {
                $('#closeapplytraileridfilter').prop('disabled', false); // Habilita el botón
            }
        }
    
        // Llamar a la función para verificar el estado del botón al cargar la página
        toggleApplyButton();
    
        // Acción al escribir en el input para habilitar/deshabilitar el botón
        $('#inputapplytraileridfilter').on('input', function() {
            toggleApplyButton(); // Actualiza el estado del botón al cambiar el valor del input
        });
    
        // Acción al hacer clic en el botón de aplicar el filtro
        $('#applytraileridfilter').on('click', function() {
            var trailerId = $('#inputapplytraileridfilter').val(); // Obtiene el valor del input
    
            if (trailerId) {
                // Si el div del filtro ya está visible, actualiza el valor
                if ($('#emptytrailerfilterdividtrailer').is(':visible')) {
                    $('#emptytrailerfilterinputidtrailer').val(trailerId);
                } else {
                    // Si el div no está visible, muestra el div y coloca el valor
                    $('#emptytrailerfilterinputidtrailer').val(trailerId);
                    $('#emptytrailerfilterdividtrailer').show();
                }
            } else {
                // Si el campo está vacío, vacía el input del filtro y oculta el div
                $('#emptytrailerfilterinputidtrailer').val('');
                $('#emptytrailerfilterdividtrailer').hide();
                $('#closeapplytraileridfilter').click(); // Simula un clic en Collapse
            }
        });
    
        // Acción al hacer clic en el botón X para eliminar el filtro
        $('#emptytrailerfilterbuttonidtrailer').on('click', function() {
            // Borra el valor y oculta el filtro
            $('#emptytrailerfilterinputidtrailer').val('');
            $('#emptytrailerfilterdividtrailer').hide();
    
            // Habilita el botón closeapplytraileridfilter
            $('#closeapplytraileridfilter').prop('disabled', false);
    
            // Vacía el input de filtro y simula clic en Apply
            $('#inputapplytraileridfilter').val('');
            $('#applytraileridfilter').click(); // Simula un clic en Apply
        });
    
        // Acción al hacer clic sobre el botón de cerrar filtro
        $('#closeapplytraileridfilter').on('click', function() {
            // Borra el valor y oculta el filtro
            $('#emptytrailerfilterinputidtrailer').val('');
            $('#emptytrailerfilterdividtrailer').hide();
        });
    
        // Acción al hacer clic en el botón o el input del filtro
        $('#emptytrailerfilterbtnidtrailer, #emptytrailerfilterinputidtrailer').on('click', function() {
            // Abre el OffCanvas
            var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasaddmorefilters'));
            offcanvas.show();
    
            // Enfocar el input
            $('#inputapplytraileridfilter').focus();
        });
    });*/
    
    //Manejo de filtros de inputs simples
    $(document).ready(function () {

        const updatetab = document.getElementById("refreshemptytrailertable");
        // Función genérica para habilitar o deshabilitar botones
        function toggleApplyButton(inputSelector, buttonSelector) {
            if ($(inputSelector).val()) {
                $(buttonSelector).prop('disabled', true); // Deshabilita el botón
            } else {
                $(buttonSelector).prop('disabled', false); // Habilita el botón
            }
        }
    
        // Función genérica para manejar clics en botones Apply
        function handleApplyButton(inputSelector, divSelector, inputFilterSelector, closeButtonSelector) {
            var inputValue = $(inputSelector).val(); // Obtiene el valor del input
    
            if (inputValue) {
                // Si el div del filtro ya está visible, actualiza el valor
                if ($(divSelector).is(':visible')) {
                    $(inputFilterSelector).val(inputValue);
                    updatetab.click();
                } else {
                    // Si el div no está visible, muestra el div y coloca el valor
                    $(inputFilterSelector).val(inputValue);
                    $(divSelector).show();
                    updatetab.click();
                }
            } else {
                // Si el campo está vacío, vacía el input del filtro y oculta el div
                $(inputFilterSelector).val('');
                $(divSelector).hide();
                $(closeButtonSelector).click(); // Simula un clic en Collapse
                updatetab.click();
            }
        }
    
        // Función genérica para manejar clics en botones X
        function handleClearButton(divSelector, inputSelector, applyButtonSelector, closeButtonSelector) {
            $(inputSelector).val('');
            $(divSelector).hide();
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón
            $(applyButtonSelector).click(); // Simula clic en Apply
            updatetab.click();
        }
    
        // Función genérica para abrir el offcanvas y enfocar el input
        function handleFilterButtonClick(offCanvasSelector, inputSelector) {
            var offcanvas = new bootstrap.Offcanvas(document.getElementById(offCanvasSelector));
            offcanvas.show();
            $(inputSelector).focus();
        }

        // Función genérica para manejar clics en botones de cerrar Collapse
        function handleCloseCollapseButton(inputSelector, divSelector, inputFilterSelector) {
            if (!$(inputSelector).val()) {
                $(inputFilterSelector).val(''); // Limpia el input asociado al filtro
                $(divSelector).hide(); // Oculta el div del filtro
                updatetab.click();
            }
        }
    
        // ID Trailer
        $('#inputapplytraileridfilter').on('input', function () {
            toggleApplyButton('#inputapplytraileridfilter', '#closeapplytraileridfilter');
        });
    
        $('#applytraileridfilter').on('click', function () {
            handleApplyButton('#inputapplytraileridfilter', '#emptytrailerfilterdividtrailer', '#emptytrailerfilterinputidtrailer', '#closeapplytraileridfilter');
        });
    
        $('#emptytrailerfilterbuttonidtrailer').on('click', function () {
            handleClearButton('#emptytrailerfilterdividtrailer', '#inputapplytraileridfilter', '#applytraileridfilter', '#closeapplytraileridfilter');
        });
    
        $('#emptytrailerfilterbtnidtrailer, #emptytrailerfilterinputidtrailer').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputapplytraileridfilter');
        });

        $('#closeapplytraileridfilter').on('click', function () {
            handleCloseCollapseButton('#inputapplytraileridfilter', '#emptytrailerfilterdividtrailer', '#emptytrailerfilterinputidtrailer');
        });
    
        // Pallets On Trailer
        $('#inputapplypotfilter').on('input', function () {
            toggleApplyButton('#inputapplypotfilter', '#closeapplypotfilter');
        });
    
        $('#applypotfilter').on('click', function () {
            handleApplyButton('#inputapplypotfilter', '#emptytrailerfilterdivpalletsontrailer', '#emptytrailerfilterinputpalletsontrailer', '#closeapplypotfilter');
        });
    
        $('#emptytrailerfilterbuttonpalletsontrailer').on('click', function () {
            handleClearButton('#emptytrailerfilterdivpalletsontrailer', '#inputapplypotfilter', '#applypotfilter', '#closeapplypotfilter');
        });
    
        $('#emptytrailerfilterbtnpalletsontrailer, #emptytrailerfilterinputpalletsontrailer').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputapplypotfilter');
        });

        $('#closeapplypotfilter').on('click', function () {
            handleCloseCollapseButton('#inputapplypotfilter', '#emptytrailerfilterdivpalletsontrailer', '#emptytrailerfilterinputpalletsontrailer');
        });
    
        // Pallets On Floor
        $('#inputapplypoffilter').on('input', function () {
            toggleApplyButton('#inputapplypoffilter', '#closeapplypoffilter');
        });
    
        $('#applypoffilter').on('click', function () {
            handleApplyButton('#inputapplypoffilter', '#emptytrailerfilterdivpalletsonfloor', '#emptytrailerfilterinputpalletsonfloor', '#closeapplypoffilter');
        });
    
        $('#emptytrailerfilterbuttonpalletsonfloor').on('click', function () {
            handleClearButton('#emptytrailerfilterdivpalletsonfloor', '#inputapplypoffilter', '#applypoffilter', '#closeapplypoffilter');
        });
    
        $('#emptytrailerfilterbtnpalletsonfloor, #emptytrailerfilterinputpalletsonfloor').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputapplypoffilter');
        });

        $('#closeapplypoffilter').on('click', function () {
            handleCloseCollapseButton('#inputapplypoffilter', '#emptytrailerfilterdivpalletsonfloor', '#emptytrailerfilterinputpalletsonfloor');
        });
    
        // Username
        $('#inputusernamefilter').on('input', function () {
            toggleApplyButton('#inputusernamefilter', '#closeapplyusernamefilter');
        });
    
        $('#applyusernamefilter').on('click', function () {
            handleApplyButton('#inputusernamefilter', '#emptytrailerfilterdivusername', '#emptytrailerfilterinputusername', '#closeapplyusernamefilter');
        });
    
        $('#emptytrailerfilterbuttonusername').on('click', function () {
            handleClearButton('#emptytrailerfilterdivusername', '#inputusernamefilter', '#applyusernamefilter', '#closeapplyusernamefilter');
        });
    
        $('#emptytrailerfilterbtnusername, #emptytrailerfilterinputusername').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputusernamefilter');
        });
        
        $('#closeapplyusernamefilter').on('click', function () {
            handleCloseCollapseButton('#inputusernamefilter', '#emptytrailerfilterdivusername', '#emptytrailerfilterinputusername');
        });
    });

    //Manejo de inputs de Date Of Status
    /*$(document).ready(function () {
        // Función para manejar el estado de los botones (habilitar/deshabilitar)
        function toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector) {
            if ($(startInputSelector).val() || $(endInputSelector).val()) {
                $(closeButtonSelector).prop('disabled', true); // Deshabilita el botón Collapse
            } else {
                $(closeButtonSelector).prop('disabled', false); // Habilita el botón Collapse
            }
        }
    
        // Función para manejar el filtro de rango de fechas (Start Date y End Date)
        function handleDateRangeFilter(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector, closeButtonSelector) {
            var startDate = $(startInputSelector).val(); // Obtiene el valor del Start Date
            var endDate = $(endInputSelector).val(); // Obtiene el valor del End Date
    
            if (startDate && endDate) {
                if ($(divSelector).is(':visible')) {
                    $(startFilterInputSelector).val(startDate); // Actualiza el input del filtro con el Start Date
                    $(endFilterInputSelector).val(endDate); // Actualiza el input del filtro con el End Date
                } else {
                    $(startFilterInputSelector).val(startDate);
                    $(endFilterInputSelector).val(endDate);
                    $(divSelector).show(); // Muestra el div del filtro
                }
            } else {
                $(startFilterInputSelector).val(''); // Limpia el input del Start Date asociado al filtro
                $(endFilterInputSelector).val(''); // Limpia el input del End Date asociado al filtro
                $(divSelector).hide(); // Oculta el div del filtro
                $(closeButtonSelector).click(); // Simula un clic en Collapse
            }
            toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector);
        }
    
        // Función para limpiar el filtro (botón X)
        function clearDateRangeFilter(divSelector, startInputSelector, endInputSelector, applyButtonSelector, closeButtonSelector) {
            $(startInputSelector).val(''); // Limpia el input del Start Date
            $(endInputSelector).val(''); // Limpia el input del End Date
            $(divSelector).hide(); // Oculta el div del filtro
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón
            $(applyButtonSelector).click(); // Simula clic en Apply
        }
    
        // Función para manejar clics en botones de cerrar Collapse
        function handleCloseDateRangeCollapse(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector) {
            if (!$(startInputSelector).val() && !$(endInputSelector).val()) {
                $(startFilterInputSelector).val(''); // Limpia el Start Date del filtro
                $(endFilterInputSelector).val(''); // Limpia el End Date del filtro
                $(divSelector).hide(); // Oculta el div del filtro
            }
        }
    
        // Función para abrir el canvas y enfocar el input correspondiente
        function openCanvasAndFocus(inputSelector) {
            var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasaddmorefilters'));
            offcanvas.show();
            $(inputSelector).focus(); // Enfocar el input específico
        }
    
        // ------------------ Date Of Status ------------------
        $('#applystatusfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplystatusstfilter', // Start Date input en el offcanvas
                '#inputapplystatusedfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdateofstatus', // Div del filtro
                '#emptytrailerfilterinputdateofstartstatus', // Start Date input en el filtro
                '#emptytrailerfilterinputdateofendstatus', // End Date input en el filtro
                '#closeapplystatusfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbuttondateofstatus').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivdateofstatus', // Div del filtro
                '#inputapplystatusstfilter', // Start Date input en el offcanvas
                '#inputapplystatusedfilter', // End Date input en el offcanvas
                '#applystatusfilter', // Botón Apply
                '#closeapplystatusfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtndateofstatus, #emptytrailerfilterinputdateofstartstatus').on('click', function () {
            openCanvasAndFocus('#inputapplystatusstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputdateofendstatus').on('click', function () {
            openCanvasAndFocus('#inputapplystatusedfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplystatusfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplystatusstfilter', // Start Date input en el offcanvas
                '#inputapplystatusedfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdateofstatus', // Div del filtro
                '#emptytrailerfilterinputdateofstartstatus', // Start Date input en el filtro
                '#emptytrailerfilterinputdateofendstatus' // End Date input en el filtro
            );
        });
    
        // Llamada inicial para verificar los botones
        toggleDateRangeButtons('#inputapplystatusstfilter', '#inputapplystatusedfilter', '#closeapplystatusfilter');

        // ------------------ Verificación de los inputs ------------------
    // Detecta cambios en los inputs del Offcanvas para habilitar o deshabilitar el botón de Collapse
    $('#inputapplystatusstfilter, #inputapplystatusedfilter').on('input', function () {
        toggleDateRangeButtons('#inputapplystatusstfilter', '#inputapplystatusedfilter', '#closeapplystatusfilter');
    });
    });*/
    
    
    //Manejo mejorado de Date of Status
    $(document).ready(function () {
        const updatetab = document.getElementById("refreshemptytrailertable");
        // Función para manejar el estado de los botones (habilitar/deshabilitar)
        function toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector) {
            if ($(startInputSelector).val() || $(endInputSelector).val()) {
                $(closeButtonSelector).prop('disabled', true); // Habilita el botón Collapse
            } else {
                $(closeButtonSelector).prop('disabled', false); // Deshabilita el botón Collapse
            }
    
            // Deshabilita el botón Apply hasta que ambos inputs estén llenos
            if ($(startInputSelector).val() && $(endInputSelector).val()) {
                $(applyButtonSelector).prop('disabled', false); // Habilita el botón Apply
            } else {
                $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
            }
        }
    
        // Función para manejar el filtro de rango de fechas (Start Date y End Date)
        function handleDateRangeFilter(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector, closeButtonSelector, applyButtonSelector) {
            var startDate = $(startInputSelector).val(); // Obtiene el valor del Start Date
            var endDate = $(endInputSelector).val(); // Obtiene el valor del End Date
    
            if (startDate && endDate) {
                if ($(divSelector).is(':visible')) {
                    $(startFilterInputSelector).val(startDate); // Actualiza el input del filtro con el Start Date
                    $(endFilterInputSelector).val(endDate); // Actualiza el input del filtro con el End Date
                    updatetab.click();
                } else {
                    $(startFilterInputSelector).val(startDate);
                    $(endFilterInputSelector).val(endDate);
                    $(divSelector).show(); // Muestra el div del filtro
                    updatetab.click();
                }
            } else {
                $(startFilterInputSelector).val(''); // Limpia el input del Start Date asociado al filtro
                $(endFilterInputSelector).val(''); // Limpia el input del End Date asociado al filtro
                $(divSelector).hide(); // Oculta el div del filtro
                $(closeButtonSelector).click(); // Simula un clic en Collapse
                updatetab.click();
            }
            toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector);
        }
    
        // Función para limpiar el filtro (botón X)
        function clearDateRangeFilter(divSelector, startInputSelector, endInputSelector, applyButtonSelector, closeButtonSelector) {
            $(startInputSelector).val(''); // Limpia el input del Start Date
            $(endInputSelector).val(''); // Limpia el input del End Date
            $(divSelector).hide(); // Oculta el div del filtro
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón
            $(closeButtonSelector).click();
            $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
            updatetab.click();
        }
    
        // Función para manejar clics en botones de cerrar Collapse
        function handleCloseDateRangeCollapse(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector) {
            if (!$(startInputSelector).val() && !$(endInputSelector).val()) {
                $(startFilterInputSelector).val(''); // Limpia el Start Date del filtro
                $(endFilterInputSelector).val(''); // Limpia el End Date del filtro
                $(divSelector).hide(); // Oculta el div del filtro
                updatetab.click();
            }
        }
    
        // Función para abrir el canvas y enfocar el input correspondiente
        function openCanvasAndFocus(inputSelector) {
            var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasaddmorefilters'));
            offcanvas.show();
            $(inputSelector).focus(); // Enfocar el input específico
        }
    
        // ------------------ Date Of Status ------------------
        $('#applystatusfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplystatusstfilter', // Start Date input en el offcanvas
                '#inputapplystatusedfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdateofstatus', // Div del filtro
                '#emptytrailerfilterinputdateofstartstatus', // Start Date input en el filtro
                '#emptytrailerfilterinputdateofendstatus', // End Date input en el filtro
                '#closeapplystatusfilter', // Botón Collapse
                '#applystatusfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttondateofstatus').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivdateofstatus', // Div del filtro
                '#inputapplystatusstfilter', // Start Date input en el offcanvas
                '#inputapplystatusedfilter', // End Date input en el offcanvas
                '#applystatusfilter', // Botón Apply
                '#closeapplystatusfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtndateofstatus, #emptytrailerfilterinputdateofstartstatus').on('click', function () {
            openCanvasAndFocus('#inputapplystatusstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputdateofendstatus').on('click', function () {
            openCanvasAndFocus('#inputapplystatusedfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplystatusfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplystatusstfilter', // Start Date input en el offcanvas
                '#inputapplystatusedfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdateofstatus', // Div del filtro
                '#emptytrailerfilterinputdateofstartstatus', // Start Date input en el filtro
                '#emptytrailerfilterinputdateofendstatus' // End Date input en el filtro
            );
        });
    
    
        // Llamada inicial para verificar los botones
        toggleDateRangeButtons('#inputapplystatusstfilter', '#inputapplystatusedfilter', '#closeapplystatusfilter', '#applystatusfilter');

        // ------------------ Verificación de los inputs ------------------
        // Detecta cambios en los inputs del Offcanvas para habilitar o deshabilitar el botón de Collapse
        $('#inputapplystatusstfilter, #inputapplystatusedfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplystatusstfilter', '#inputapplystatusedfilter', '#closeapplystatusfilter', '#applystatusfilter');
        });
    });

    //Manejo Filtro de fechas datetime 
    $(document).ready(function () {
        const updatetab = document.getElementById("refreshemptytrailertable");
        // Función para manejar el estado de los botones (habilitar/deshabilitar)
        function toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector) {
            if ($(startInputSelector).val() || $(endInputSelector).val()) {
                $(closeButtonSelector).prop('disabled', true); // Habilita el botón Collapse
            } else {
                $(closeButtonSelector).prop('disabled', false); // Deshabilita el botón Collapse
            }
    
            // Deshabilita el botón Apply hasta que ambos inputs estén llenos
            if ($(startInputSelector).val() && $(endInputSelector).val()) {
                $(applyButtonSelector).prop('disabled', false); // Habilita el botón Apply
            } else {
                $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
            }
        }
    
        // Función para manejar el filtro de rango de fechas
        function handleDateRangeFilter(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector, closeButtonSelector, applyButtonSelector) {
            var startDate = $(startInputSelector).val(); // Obtiene el valor del Start Date
            var endDate = $(endInputSelector).val(); // Obtiene el valor del End Date
    
            if (startDate && endDate) {
                if ($(divSelector).is(':visible')) {
                    // Actualiza los inputs del filtro con los valores de fecha seleccionados
                    $(startFilterInputSelector).val(startDate); // Actualiza el Start Date en el div de filtros
                    $(endFilterInputSelector).val(endDate); // Actualiza el End Date en el div de filtros
                    updatetab.click();
                } else {
                    $(startFilterInputSelector).val(startDate); // Actualiza el Start Date en el div de filtros
                    $(endFilterInputSelector).val(endDate); // Actualiza el End Date en el div de filtros
                    $(divSelector).show(); // Muestra el div del filtro
                    updatetab.click();
                }
            } else {
                $(startFilterInputSelector).val(''); // Limpia el input del Start Date asociado al filtro
                $(endFilterInputSelector).val(''); // Limpia el input del End Date asociado al filtro
                $(divSelector).hide(); // Oculta el div del filtro
                $(closeButtonSelector).click(); // Simula un clic en Collapse
                updatetab.click();
            }
            toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector);
        }
    
        // Función para limpiar el filtro (botón X)
        function clearDateRangeFilter(divSelector, startInputSelector, endInputSelector, applyButtonSelector, closeButtonSelector) {
            $(startInputSelector).val(''); // Limpia el input del Start Date
            $(endInputSelector).val(''); // Limpia el input del End Date
            $(divSelector).hide(); // Oculta el div del filtro
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón Collapse
            $(closeButtonSelector).click(); // Simula un clic en Collapse
            $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
            updatetab.click();
        }
    
        // Función para manejar clics en botones de cerrar Collapse
        function handleCloseDateRangeCollapse(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector) {
            if (!$(startInputSelector).val() && !$(endInputSelector).val()) {
                $(startFilterInputSelector).val(''); // Limpia el Start Date del filtro
                $(endFilterInputSelector).val(''); // Limpia el End Date del filtro
                $(divSelector).hide(); // Oculta el div del filtro
                updatetab.click();
            }
        }
    
        // Función para abrir el canvas y enfocar el input correspondiente
        function openCanvasAndFocus(inputSelector) {
            var offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvasaddmorefilters'));
            offcanvas.show();
            $(inputSelector).focus(); // Enfocar el input específico
        }
    
        // ------------------ Date In ------------------
        $('#applydifilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplydistfilter', // Start Date input en el offcanvas
                '#inputapplydienfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdatein', // Div del filtro
                '#emptytrailerfilterinputstartdatein', // Start Date input en el filtro
                '#emptytrailerfilterinputenddatein', // End Date input en el filtro
                '#closeapplydifilter', // Botón Collapse
                '#applydifilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttondatein').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivdatein', // Div del filtro
                '#inputapplydistfilter', // Start Date input en el offcanvas
                '#inputapplydienfilter', // End Date input en el offcanvas
                '#applydifilter', // Botón Apply
                '#closeapplydifilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtndatein, #emptytrailerfilterinputstartdatein').on('click', function () {
            openCanvasAndFocus('#inputapplydistfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputenddatein').on('click', function () {
            openCanvasAndFocus('#inputapplydienfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplydifilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplydistfilter', // Start Date input en el offcanvas
                '#inputapplydienfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdatein', // Div del filtro
                '#emptytrailerfilterinputstartdatein', // Start Date input en el filtro
                '#emptytrailerfilterinputenddatein' // End Date input en el filtro
            );
        });
    
        // ------------------ Date Out ------------------
        $('#applydofilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplydostfilter', // Start Date input en el offcanvas
                '#inputapplydoedfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdateout', // Div del filtro
                '#emptytrailerfilterinputstartdateout', // Start Date input en el filtro
                '#emptytrailerfilterinputenddateout', // End Date input en el filtro
                '#closeapplydofilter', // Botón Collapse
                '#applydofilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttondateout').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivdateout', // Div del filtro
                '#inputapplydostfilter', // Start Date input en el offcanvas
                '#inputapplydoedfilter', // End Date input en el offcanvas
                '#applydofilter', // Botón Apply
                '#closeapplydofilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtndateout, #emptytrailerfilterinputstartdateout').on('click', function () {
            openCanvasAndFocus('#inputapplydostfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputenddateout').on('click', function () {
            openCanvasAndFocus('#inputapplydoedfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplydofilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplydostfilter', // Start Date input en el offcanvas
                '#inputapplydoedfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdateout', // Div del filtro
                '#emptytrailerfilterinputstartdateout', // Start Date input en el filtro
                '#emptytrailerfilterinputenddateout' // End Date input en el filtro
            );
        });
    
        // ------------------ Transaction Date ------------------
        $('#applytdfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplytdstfilter', // Start Date input en el offcanvas
                '#inputapplytdedfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivtransactiondate', // Div del filtro
                '#emptytrailerfilterinputstarttransactiondate', // Start Date input en el filtro
                '#emptytrailerfilterinputendtransactiondate', // End Date input en el filtro
                '#closeapplytdfilter', // Botón Collapse
                '#applytdfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttontransactiondate').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivtransactiondate', // Div del filtro
                '#inputapplytdstfilter', // Start Date input en el offcanvas
                '#inputapplytdedfilter', // End Date input en el offcanvas
                '#applytdfilter', // Botón Apply
                '#closeapplytdfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtntransactiondate, #emptytrailerfilterinputstarttransactiondate').on('click', function () {
            openCanvasAndFocus('#inputapplytdstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputendtransactiondate').on('click', function () {
            openCanvasAndFocus('#inputapplytdedfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplytdfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplytdstfilter', // Start Date input en el offcanvas
                '#inputapplytdedfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivtransactiondate', // Div del filtro
                '#emptytrailerfilterinputstarttransactiondate', // Start Date input en el filtro
                '#emptytrailerfilterinputendtransactiondate' // End Date input en el filtro
            );
        });
    
        // Detectar cambios en los inputs de las fechas para habilitar o deshabilitar botones
        $('#inputapplydistfilter, #inputapplydienfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydistfilter', '#inputapplydienfilter', '#closeapplydifilter', '#applydifilter');
        });
    
        $('#inputapplydostfilter, #inputapplydoedfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydostfilter', '#inputapplydoedfilter', '#closeapplydofilter', '#applydofilter');
        });
    
        $('#inputapplytdstfilter, #inputapplytdedfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplytdstfilter', '#inputapplytdedfilter', '#closeapplytdfilter', '#applytdfilter');
        });
    
        // Llamada inicial para verificar los botones
        toggleDateRangeButtons('#inputapplydistfilter', '#inputapplydienfilter', '#closeapplydifilter', '#applydifilter');
        toggleDateRangeButtons('#inputapplydostfilter', '#inputapplydoedfilter', '#closeapplydofilter', '#applydofilter');
        toggleDateRangeButtons('#inputapplytdstfilter', '#inputapplytdedfilter', '#closeapplytdfilter', '#applytdfilter');
    });
    

    //Funcion para buscar los carriers en la pantalla de empty trailer en los filtros
    function loadCarriersFilter() {
        var carrierRoute = $('#inputapplycarrierfilter').data('url');
        $.ajax({
            url: carrierRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputapplycarrierfilter');
                let selectedValue = select.val();
                //let selectedValue = "{{ old('inputcarrier') }}"; // Recupera el valor previo
                select.empty();
                //select.append('<option selected disabled hidden></option>');

                if (data.length === 0) {
                    select.append('<option disabled >No options available</option>');
                } else {
                    select.append('<option value="">Choose a filter</option>');
                    //select.append('<option value="">Remove filter</option>');
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

    //Ejecutar la funcion al picarle al select en los filtros
    $('#inputapplycarrierfilter').on('focus', loadCarriersFilter);
    loadCarriersFilter();

    //Funcion para buscar las locations en la pantalla de empty trailer en los filtros
    function loadLocationsFilter() {
        var locationsRoute = $('#inputapplylocationfilter').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputapplylocationfilter');
                let selectedValue = select.val();
                //let selectedValue = "{{ old('inputlocation') }}"; // Recupera el valor previo
                select.empty();
                //select.append('<option selected disabled hidden></option>');

                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    select.append('<option value="">Choose a filter</option>');
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

    //Ejecurtar la funcion al picarle al boton en los filtros
    $('#inputapplylocationfilter').on('focus', loadLocationsFilter);
    loadLocationsFilter();

    //Funcion para buscar el availability indicator en la pantalla de empty trailer update en el filtro
    function loadAvailabilityIndicatorFilter() {
        var availabilityRoute = $('#inputapplyaifilter').data('url');
          $.ajax({
              url: availabilityRoute,
              method: 'GET',
              success: function (data) {
                  let select = $('#inputapplyaifilter');
                  let selectedValue = select.val();
                  //let selectedValue = "{{ old('inputavailabilityindicator') }}"; // Recupera el valor previo
                  select.empty(); // Limpia el select eliminando todas las opciones
                  //select.append('<option selected disabled hidden></option>'); // Opción inicial

                  if (data.length === 0) {
                      select.append('<option disabled>No options available</option>');
                  } else {
                    select.append('<option value="">Choose a filter</option>');
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

    // Cargar datos al enfocarse y al cargar la página filters
    $('#inputapplyaifilter').on('focus', loadAvailabilityIndicatorFilter);
    loadAvailabilityIndicatorFilter();

    //Filtros de selects
    //jhgbwvefqvrjrhegwvfeqcwdfevwgrbehtrnjymrnthegvwfeqcvwgrbet
    $(document).ready(function () {
        // Obtenemos los elementos
        const updatetab = document.getElementById("refreshemptytrailertable");
        const $selectElement = $("#inputapplylocationfilter");
        const $filterDiv = $("#emptytrailerfilterdivlocation");
        const $inputPk = $("#emptytrailerfilterinputlocationpk");
        const $inputLocation = $("#emptytrailerfilterinputlocation");
        const $applyButton = $("#applylocationfilter");
        const $closeButton = $("#closeapplylocationfilter");
        const $offcanvas = $("#offcanvasaddmorefilters");
        const $clearButton = $("#emptytrailerfilterbuttonlocation");
    
        // Variable que guarda los elementos que abrirán el offcanvas
        const openOffcanvasElements = $("#emptytrailerfilterinputlocation, #emptytrailerfilterbtnlocation");
    
        // Escuchamos el cambio del select
        $selectElement.on("change", function () {
            const selectedValue = $selectElement.val(); // Valor del select
    
            if (selectedValue !== "") {
                // Inhabilitar el botón Close cuando el valor del select no sea vacío
                $closeButton.prop("disabled", true);
            } else {
                // Habilitar el botón Close si el valor del select es vacío
                $closeButton.prop("disabled", false);
            }
        });
    
        // Escuchamos el click del botón Apply
        $applyButton.on("click", function () {
            const selectedValue = $selectElement.val(); // Valor del select
            const selectedText = $selectElement.find("option:selected").text(); // Texto del select
    
            if (selectedValue !== "") {
                // Mostrar el div
                $filterDiv.show();
                // Rellenar los inputs
                $inputPk.val(selectedValue);
                $inputLocation.val(selectedText);
                updatetab.click();
            } else {
                // Limpiar los inputs y ocultar el div si el select está vacío
                $inputPk.val("");
                $inputLocation.val("");
                $filterDiv.hide();
                // Simular clic en el botón Close
                $closeButton.click();
                updatetab.click();
            }
        });
    
        // Escuchamos el clic en el botón Close
        $closeButton.on("click", function () {
            const selectedValue = $selectElement.val(); // Valor del select
    
            if (selectedValue === "" && ($inputLocation.val() !== "" || $inputPk.val() !== "")) {
                // Limpiar los inputs si el select está vacío pero los inputs tienen datos
                $inputLocation.val("");
                $inputPk.val("");
                updatetab.click();
            }
    
            // Ocultar el div si está visible
            if ($filterDiv.is(":visible")) {
                $filterDiv.hide();
                updatetab.click();
            }
        });
    
        // Escuchar clic en los elementos que abrirán el offcanvas
        openOffcanvasElements.on("click", function () {
            // Verificar si el div está visible
            if ($filterDiv.is(":visible")) {
                // Abrir el offcanvas y enfocar el input
                $offcanvas.offcanvas("show"); // Abrir el offcanvas
                $selectElement.focus(); // Enfocar el input de selección
            }
        });
    
        // Escuchar clic en el botón de limpiar (vaciar inputs y ocultar el div)
        $clearButton.on("click", function () {
            // Verificar si el div está visible y los inputs tienen datos
            if ($filterDiv.is(":visible") && ($inputLocation.val() !== "" || $inputPk.val() !== "")) {
                // Limpiar los inputs
                $inputLocation.val("");
                $inputPk.val("");
                // Ocultar el div
                $filterDiv.hide();
                // Vaciar el select
                $selectElement.val("");
                // Habilitar el botón Close si está deshabilitado
                if ($closeButton.prop("disabled")) {
                    $closeButton.prop("disabled", false);
                }
                // Simular clic en el botón Close
                $closeButton.click();
                updatetab.click();
            }
        });
    });

    $(document).ready(function () {
        const updatetab = document.getElementById("refreshemptytrailertable");
        // Obtenemos los elementos para Carrier
        const $selectCarrier = $("#inputapplycarrierfilter");
        const $filterDivCarrier = $("#emptytrailerfilterdivcarrier");
        const $inputCarrier = $("#emptytrailerfilterinputcarrier");
        const $inputCarrierPk = $("#emptytrailerfilterinputcarrierpk");
        const $applyCarrierButton = $("#applycarrierfilter");
        const $closeCarrierButton = $("#closeapplycarrierfilter");
        const $clearCarrierButton = $("#emptytrailerfilterbuttoncarrier");
    
        // Obtenemos los elementos para Availability Indicator
        const $selectAvailability = $("#inputapplyaifilter");
        const $filterDivAvailability = $("#emptytrailerfilterdivavailabilityindicator");
        const $inputAvailability = $("#emptytrailerfilterinputavailabilityindicator");
        const $inputAvailabilityPk = $("#emptytrailerfilterinputavailabilityindicatorpk");
        const $applyAvailabilityButton = $("#applyaifilter");
        const $closeAvailabilityButton = $("#closeapplyaifilter");
        const $clearAvailabilityButton = $("#emptytrailerfilterbuttonavailabilityindicator");
    
        // Variables que guardan los elementos que abrirán el offcanvas
        const openOffcanvasElementsCarrier = $("#emptytrailerfilterinputcarrier, #emptytrailerfilterbtncarrier");
        const openOffcanvasElementsAvailability = $("#emptytrailerfilterinputavailabilityindicator, #emptytrailerfilterbtnavailabilityindicator");
    
        // Función para los filtros Carrier
        $selectCarrier.on("change", function () {
            const selectedValue = $selectCarrier.val();
    
            if (selectedValue !== "") {
                $closeCarrierButton.prop("disabled", true);
            } else {
                $closeCarrierButton.prop("disabled", false);
            }
        });
    
        $applyCarrierButton.on("click", function () {
            const selectedValue = $selectCarrier.val();
            const selectedText = $selectCarrier.find("option:selected").text();
    
            if (selectedValue !== "") {
                $filterDivCarrier.show();
                $inputCarrierPk.val(selectedValue);
                $inputCarrier.val(selectedText);
                updatetab.click();
            } else {
                $inputCarrierPk.val("");
                $inputCarrier.val("");
                $filterDivCarrier.hide();
                $closeCarrierButton.click();
                updatetab.click();
            }
        });
    
        $closeCarrierButton.on("click", function () {
            const selectedValue = $selectCarrier.val();
    
            if (selectedValue === "" && ($inputCarrier.val() !== "" || $inputCarrierPk.val() !== "")) {
                $inputCarrier.val("");
                $inputCarrierPk.val("");
                updatetab.click();
            }
    
            if ($filterDivCarrier.is(":visible")) {
                $filterDivCarrier.hide();
                updatetab.click();
            }
        });
    
        $clearCarrierButton.on("click", function () {
            if ($filterDivCarrier.is(":visible") && ($inputCarrier.val() !== "" || $inputCarrierPk.val() !== "")) {
                $inputCarrier.val("");
                $inputCarrierPk.val("");
                $filterDivCarrier.hide();
                $selectCarrier.val("");
                if ($closeCarrierButton.prop("disabled")) {
                    $closeCarrierButton.prop("disabled", false);
                }
                $closeCarrierButton.click();
                updatetab.click();
            }
        });
    
        openOffcanvasElementsCarrier.on("click", function () {
            if ($filterDivCarrier.is(":visible")) {
                $("#offcanvasaddmorefilters").offcanvas("show");
                $selectCarrier.focus();
            }
        });
    
        // Función para los filtros Availability Indicator
        $selectAvailability.on("change", function () {
            const selectedValue = $selectAvailability.val();
    
            if (selectedValue !== "") {
                $closeAvailabilityButton.prop("disabled", true);
            } else {
                $closeAvailabilityButton.prop("disabled", false);
            }
        });
    
        $applyAvailabilityButton.on("click", function () {
            const selectedValue = $selectAvailability.val();
            const selectedText = $selectAvailability.find("option:selected").text();
    
            if (selectedValue !== "") {
                $filterDivAvailability.show();
                $inputAvailabilityPk.val(selectedValue);
                $inputAvailability.val(selectedText);
                updatetab.click();
            } else {
                $inputAvailabilityPk.val("");
                $inputAvailability.val("");
                $filterDivAvailability.hide();
                $closeAvailabilityButton.click();
                updatetab.click();
            }
        });
    
        $closeAvailabilityButton.on("click", function () {
            const selectedValue = $selectAvailability.val();
    
            if (selectedValue === "" && ($inputAvailability.val() !== "" || $inputAvailabilityPk.val() !== "")) {
                $inputAvailability.val("");
                $inputAvailabilityPk.val("");
                updatetab.click();
            }
    
            if ($filterDivAvailability.is(":visible")) {
                $filterDivAvailability.hide();
                updatetab.click();
            }
        });
    
        $clearAvailabilityButton.on("click", function () {
            if ($filterDivAvailability.is(":visible") && ($inputAvailability.val() !== "" || $inputAvailabilityPk.val() !== "")) {
                $inputAvailability.val("");
                $inputAvailabilityPk.val("");
                $filterDivAvailability.hide();
                $selectAvailability.val("");
                if ($closeAvailabilityButton.prop("disabled")) {
                    $closeAvailabilityButton.prop("disabled", false);
                }
                $closeAvailabilityButton.click();
                updatetab.click();
            }
        });
    
        openOffcanvasElementsAvailability.on("click", function () {
            if ($filterDivAvailability.is(":visible")) {
                $("#offcanvasaddmorefilters").offcanvas("show");
                $selectAvailability.focus();
            }
        });
    });
    
    
    //Guardar los valores de los filtros para recargas de la pagina
    // Lista de IDs de los inputs
    const inputIds = [
        'searchemptytrailergeneral',
        'emptytrailerfilterinputidtrailer',
        'emptytrailerfilterinputdateofstartstatus',
        'emptytrailerfilterinputdateofendstatus',
        'emptytrailerfilterinputpalletsontrailer',
        'emptytrailerfilterinputpalletsonfloor',
        'emptytrailerfilterinputcarrierpk',
        'emptytrailerfilterinputcarrier',
        'emptytrailerfilterinputavailabilityindicator',
        'emptytrailerfilterinputavailabilityindicatorpk',
        'emptytrailerfilterinputlocationpk',
        'emptytrailerfilterinputlocation',
        'emptytrailerfilterinputstartdatein',
        'emptytrailerfilterinputenddatein',
        'emptytrailerfilterinputstartdateout',
        'emptytrailerfilterinputenddateout',
        'emptytrailerfilterinputstarttransactiondate',
        'emptytrailerfilterinputendtransactiondate',
        'emptytrailerfilterinputusername'
    ];

    // Al cargar la página, recuperar los valores desde sessionStorage y asignarlos a los inputs
    window.onload = function() {
        inputIds.forEach(id => {
            const storedValue = sessionStorage.getItem(id);
            if (storedValue) {
                const input = document.getElementById(id);
                if (input) {
                    input.value = storedValue;
                }
            }
        });
    };

    // Antes de recargar la página, guardar los valores de los inputs en sessionStorage
    window.addEventListener('beforeunload', function() {
        inputIds.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                sessionStorage.setItem(id, input.value);
            }
        });
    });

    // Limpiar los valores de sessionStorage al cambiar de ruta
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                inputIds.forEach(id => {
                    sessionStorage.removeItem(id);
                });
            });
        });
    });

    // Llamar a la función updateTrailerTable al cargar la página
    window.addEventListener('load', () => {
        updateTrailerTable();
        applyTrailerIdFilter();
        applypotFilter();
        applypofFilter();
        applyuserFilter();
        applystatusFilter();
        applydiFilter();
        applydoFilter();
        applytdFilter();
        //applylocationFilter();
        //applyavailabilityindicartorFilter();
        loadCarriersFilternono(() => {
            applycarrierFilter();
        });

        loadlocationsFilternono(() => {
            applylocationsFilter();
        });

        loadAvailabilityFilternono(() => {
            applyAvailabilityFilter();
        });
    });
    
    function applyTrailerIdFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputidtrailer').value;
        const filterDiv = document.getElementById('emptytrailerfilterdividtrailer');
        const inputApply = document.getElementById('inputapplytraileridfilter');
        const applyButton = document.getElementById('applytraileridfilter');
        const closeButton = document.getElementById('closeapplytraileridfilter');

        // Verificar si el input tiene valor y el div está oculto
        if (inputFilterValue && filterDiv.style.display === 'none') {
            // Rellenar el input en el offcanvas
            inputApply.value = inputFilterValue;

            // Simular clic en el botón "Apply"
            applyButton.click();

            // Simular clic en el botón "Close"
            closeButton.click();

            // Deshabilitar el botón "Close"
            closeButton.disabled = true;
        }
    }

    function applypotFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputpalletsontrailer').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivpalletsontrailer');
        const inputApply = document.getElementById('inputapplypotfilter');
        const applyButton = document.getElementById('applypotfilter');
        const closeButton = document.getElementById('closeapplypotfilter');

        // Verificar si el input tiene valor y el div está oculto
        if (inputFilterValue && filterDiv.style.display === 'none') {
            // Rellenar el input en el offcanvas
            inputApply.value = inputFilterValue;

            // Simular clic en el botón "Apply"
            applyButton.click();

            // Simular clic en el botón "Close"
            closeButton.click();

            // Deshabilitar el botón "Close"
            closeButton.disabled = true;
        }
    }

    function applyuserFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputusername').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivusername');
        const inputApply = document.getElementById('inputusernamefilter');
        const applyButton = document.getElementById('applyusernamefilter');
        const closeButton = document.getElementById('closeapplyusernamefilter');

        // Verificar si el input tiene valor y el div está oculto
        if (inputFilterValue && filterDiv.style.display === 'none') {
            // Rellenar el input en el offcanvas
            inputApply.value = inputFilterValue;

            // Simular clic en el botón "Apply"
            applyButton.click();

            // Simular clic en el botón "Close"
            closeButton.click();

            // Deshabilitar el botón "Close"
            closeButton.disabled = true;
        }
    }

    function applypofFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputpalletsonfloor').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivpalletsonfloor');
        const inputApply = document.getElementById('inputapplypoffilter');
        const applyButton = document.getElementById('applypoffilter');
        const closeButton = document.getElementById('closeapplypoffilter');

        // Verificar si el input tiene valor y el div está oculto
        if (inputFilterValue && filterDiv.style.display === 'none') {
            // Rellenar el input en el offcanvas
            inputApply.value = inputFilterValue;

            // Simular clic en el botón "Apply"
            applyButton.click();

            // Simular clic en el botón "Close"
            closeButton.click();

            // Deshabilitar el botón "Close"
            closeButton.disabled = true;
        }
    }

    function applystatusFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputdateofstartstatus').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputdateofendstatus').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivdateofstatus');
        const inputApply1 = document.getElementById('inputapplystatusstfilter');
        const inputApply2 = document.getElementById('inputapplystatusedfilter');
        const applyButton = document.getElementById('applystatusfilter');
        const closeButton = document.getElementById('closeapplystatusfilter');

        if(inputFilterValue1 && inputFilterValue2 && filterDiv.style.display === 'none'){
            inputApply1.value = inputFilterValue1;
            inputApply2.value = inputFilterValue2;

            // Simular clic en el botón "Close"
            closeButton.click();

            applyButton.disabled = false;
            // Simular clic en el botón "Apply"
            applyButton.click();

            // Deshabilitar el botón "Close"
            closeButton.disabled = true;
        }
    }

    function applydiFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartdatein').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputenddatein').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivdatein');
        const inputApply1 = document.getElementById('inputapplydistfilter');
        const inputApply2 = document.getElementById('inputapplydienfilter');
        const applyButton = document.getElementById('applydifilter');
        const closeButton = document.getElementById('closeapplydifilter');

        if(inputFilterValue1 && inputFilterValue2 && filterDiv.style.display === 'none'){
            inputApply1.value = inputFilterValue1;
            inputApply2.value = inputFilterValue2;

            // Simular clic en el botón "Close"
            closeButton.click();

            applyButton.disabled = false;
            // Simular clic en el botón "Apply"
            applyButton.click();

            // Deshabilitar el botón "Close"
            closeButton.disabled = true;
        }
    }

    function applydoFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartdateout').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputenddateout').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivdateout');
        const inputApply1 = document.getElementById('inputapplydostfilter');
        const inputApply2 = document.getElementById('inputapplydoedfilter');
        const applyButton = document.getElementById('applydofilter');
        const closeButton = document.getElementById('closeapplydofilter');

        if(inputFilterValue1 && inputFilterValue2 && filterDiv.style.display === 'none'){
            inputApply1.value = inputFilterValue1;
            inputApply2.value = inputFilterValue2;

            // Simular clic en el botón "Close"
            closeButton.click();

            applyButton.disabled = false;
            // Simular clic en el botón "Apply"
            applyButton.click();

            // Deshabilitar el botón "Close"
            closeButton.disabled = true;
        }
    }

    function applytdFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstarttransactiondate').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputendtransactiondate').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivtransactiondate');
        const inputApply1 = document.getElementById('inputapplytdstfilter');
        const inputApply2 = document.getElementById('inputapplytdedfilter');
        const applyButton = document.getElementById('applytdfilter');
        const closeButton = document.getElementById('closeapplytdfilter');

        if(inputFilterValue1 && inputFilterValue2 && filterDiv.style.display === 'none'){
            inputApply1.value = inputFilterValue1;
            inputApply2.value = inputFilterValue2;

            // Simular clic en el botón "Close"
            closeButton.click();

            applyButton.disabled = false;
            // Simular clic en el botón "Apply"
            applyButton.click();

            // Deshabilitar el botón "Close"
            closeButton.disabled = true;
        }
    }

    // Función para buscar y cargar los carriers en el select
    function loadCarriersFilternono(callback) {
        var carrierRoute = $('#inputapplycarrierfilter').data('url');
        $.ajax({
            url: carrierRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputapplycarrierfilter');
                let selectedValue = select.val();
                select.empty();

                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    select.append('<option value="">Choose a filter</option>');
                    data.forEach(item => {
                        select.append(`<option value="${item.id_company}">${item.CoName}</option>`);
                    });
                }

                if (selectedValue) {
                    select.val(selectedValue); // Restaura el valor anterior
                }

                // Llama al callback si está definido
                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data carriers:', error);
            }
        });
    }
        
    function applycarrierFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputcarrier').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputcarrierpk').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivcarrier');
        const applyButton = document.getElementById('applycarrierfilter');
        const closeButton = document.getElementById('closeapplycarrierfilter');
        const inputApply2 = document.getElementById('inputapplycarrierfilter');

        if (!inputApply2) {
            console.error("El elemento select con el ID 'inputapplycarrierfilter' no existe.");
            return;
        }

        // Esperar a que las opciones estén cargadas y luego seleccionar el valor
        const options = Array.from(inputApply2.options);
        
        if(inputFilterValue1 && inputFilterValue2 && filterDiv.style.display === 'none'){

        const optionToSelect = options.find(option => option.value === inputFilterValue2);
        if (optionToSelect) {
            inputApply2.value = inputFilterValue2; // Selecciona el valor si existe
        } else {
            console.warn(`El valor ${inputFilterValue2} no existe en el select inputapplycarrierfilter.`);
        }

        // Simular clic en el botón "Close"
        closeButton.click();

        // Simular clic en el botón "Apply"
        applyButton.click();

        // Deshabilitar el botón "Close"
        closeButton.disabled = true;
        }
    }

    // Función para buscar y cargar los Locations en el select
    function loadlocationsFilternono(callback) {
        var carrierRoute = $('#inputapplylocationfilter').data('url');
        $.ajax({
            url: carrierRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputapplylocationfilter');
                let selectedValue = select.val();
                select.empty();

                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    select.append('<option value="">Choose a filter</option>');
                    data.forEach(item => {
                        select.append(`<option value="${item.id_company}">${item.CoName}</option>`);
                    });
                }

                if (selectedValue) {
                    select.val(selectedValue); // Restaura el valor anterior
                }

                // Llama al callback si está definido
                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data carriers:', error);
            }
        });
    }
        
    function applylocationsFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputlocation').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputlocationpk').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivlocation');
        const applyButton = document.getElementById('applylocationfilter');
        const closeButton = document.getElementById('closeapplylocationfilter');
        const inputApply2 = document.getElementById('inputapplylocationfilter');

        if (!inputApply2) {
            console.error("El elemento select con el ID 'inputapplycarrierfilter' no existe.");
            return;
        }

        // Esperar a que las opciones estén cargadas y luego seleccionar el valor
        const options = Array.from(inputApply2.options);
        
        if(inputFilterValue1 && inputFilterValue2 && filterDiv.style.display === 'none'){
        const optionToSelect = options.find(option => option.value === inputFilterValue2);
        if (optionToSelect) {
            inputApply2.value = inputFilterValue2; // Selecciona el valor si existe
        } else {
            console.warn(`El valor ${inputFilterValue2} no existe en el select inputapplyfilter.`);
        }

        // Simular clic en el botón "Close"
        closeButton.click();

        // Simular clic en el botón "Apply"
        applyButton.click();

        // Deshabilitar el botón "Close"
        closeButton.disabled = true;
        }
    }


    // Función para buscar y cargar los Locations en el select
    function loadAvailabilityFilternono(callback) {
        var carrierRoute = $('#inputapplyaifilter').data('url');
        $.ajax({
            url: carrierRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputapplyaifilter');
                let selectedValue = select.val();
                select.empty();

                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    select.append('<option value="">Choose a filter</option>');
                    data.forEach(item => {
                        select.append(`<option value="${item.gnct_id}">${item.gntc_description}</option>`);
                    });
                }

                if (selectedValue) {
                    select.val(selectedValue); // Restaura el valor anterior
                }

                // Llama al callback si está definido
                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data carriers:', error);
            }
        });
    }
        
    function applyAvailabilityFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputavailabilityindicator').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputavailabilityindicatorpk').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivavailabilityindicator');
        const applyButton = document.getElementById('applyaifilter');
        const closeButton = document.getElementById('closeapplyaifilter');
        const inputApply2 = document.getElementById('inputapplyaifilter');

        if (!inputApply2) {
            console.error("El elemento select con el ID 'Availability' no existe.");
            return;
        }

        // Esperar a que las opciones estén cargadas y luego seleccionar el valor
        const options = Array.from(inputApply2.options);
        
        if(inputFilterValue1 && inputFilterValue2 && filterDiv.style.display === 'none'){
        const optionToSelect = options.find(option => option.value === inputFilterValue2);
        if (optionToSelect) {
            inputApply2.value = inputFilterValue2; // Selecciona el valor si existe
        } else {
            console.warn(`El valor ${inputFilterValue2} no existe en el select Availability.`);
        }

        // Simular clic en el botón "Close"
        closeButton.click();

        // Simular clic en el botón "Apply"
        applyButton.click();

        // Deshabilitar el botón "Close"
        closeButton.disabled = true;
        }
    }