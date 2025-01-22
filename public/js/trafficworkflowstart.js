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

    //Funcion para buscar las Carriers en la pantalla de shipments
    function loadCarriers() {
        var locationsRoute = $('#inputshipmentcarrier').data('url');
        $.ajax({
                url: locationsRoute,
                type: 'GET',
                success: function (data) {
                    let select = $('#inputshipmentcarrier');
                    let currentValue = select.val(); // Valor actual seleccionado por el usuario
                    let initialValue = select.attr('value'); // Valor inicial definido en el HTML
        
                    // Si no hay valor actual (por ejemplo, al cargar por primera vez), usa el inicial
                    let selectedValue = currentValue || initialValue;
        
                    select.empty(); // Limpia el contenido del select
        
                    // Agrega la opción deshabilitada y oculta solo si no hay valor seleccionado
                    /*if (!selectedValue) {
                        select.append('<option selected disabled hidden></option>');
                    }*/
        
                    if (data.length === 0) {
                        select.append('<option disabled>No options available</option>');
                    } else {
                        select.append('<option value="">Choose an option</option>');
                        data.forEach(item => {
                            select.append(`<option value="${item.pk_company}">${item.CoName}</option>`);
                        });
                    }
        
                    // Restaura el valor seleccionado si existe
                    if (selectedValue) {
                        select.val(selectedValue);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data locations:', error);
                }
        });
    }
        
    // Ejecutar la función al enfocar el select y al cargar la página
    $('#inputshipmentcarrier').on('focus', loadCarriers);
    loadCarriers();

    //Funcion para buscar las Origins en la pantalla de shipments
    function loadOrigins() {
        var locationsRoute = $('#inputorigin').data('url');
        $.ajax({
                url: locationsRoute,
                type: 'GET',
                success: function (data) {
                    let select = $('#inputorigin');
                    let currentValue = select.val(); // Valor actual seleccionado por el usuario
                    let initialValue = select.attr('value'); // Valor inicial definido en el HTML
        
                    // Si no hay valor actual (por ejemplo, al cargar por primera vez), usa el inicial
                    let selectedValue = currentValue || initialValue;
        
                    select.empty(); // Limpia el contenido del select
        
                    // Agrega la opción deshabilitada y oculta solo si no hay valor seleccionado
                    if (!selectedValue) {
                        select.append('<option selected disabled hidden></option>');
                    }
        
                    if (data.length === 0) {
                        select.append('<option disabled>No options available</option>');
                    } else {
                        data.forEach(item => {
                            select.append(`<option value="${item.pk_company}">${item.CoName}</option>`);
                        });
                    }
        
                    // Restaura el valor seleccionado si existe
                    if (selectedValue) {
                        select.val(selectedValue);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching data locations:', error);
                }
        });
    }
        
    // Ejecutar la función al enfocar el select y al cargar la página
    $('#inputorigin').on('focus', loadOrigins);
    loadOrigins();

    //Funcion para buscar el shipmenttype en la pantalla de empty trailer update
    function loadShipmentType() {
        var availabilityRoute = $('#inputshipmentshipmenttype').data('url');
          $.ajax({
              url: availabilityRoute,
              method: 'GET',
              success: function (data) {
                  let select = $('#inputshipmentshipmenttype');
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
                  console.error('Error fetching data Shipment Types:', error);
              }
          });
    }

    // Cargar datos al enfocarse y al cargar la página update 
    $('#inputshipmentshipmenttype').on('focus', loadShipmentType);
    loadShipmentType();
});

    //Crear nuevo Shipment
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
            if (fieldName === 'inputshipmentstmid' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('STM ID is required');
            }
    
            if (fieldName === 'inputshipmentshipmenttype' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Shipment Type is required.');
            }

            /*if (fieldName === 'inputshipmentreference' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Reference is required.');
            }*/
    
            /*if (fieldName === 'inputpalletsontrailer' && field.val().trim().length > 50) {
                field.addClass('is-invalid');
                errorContainer.text('El campo Pallets On Trailer no debe exceder los 50 caracteres.');
            }
    
            if (fieldName === 'inputpalletsonfloor' && field.val().trim().length > 50) {
                field.addClass('is-invalid');
                errorContainer.text('El campo Pallets On Floor no debe exceder los 50 caracteres.');
            }*/
    
            if (fieldName === 'inputorigin' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Origin is required.');
            }
    
            if (fieldName === 'inputshipmentdestination' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Destination is required.');
            }
    
            if (fieldName === 'inputshipmentprealertdatetime' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Pre-Alert datetime is required.');
            }
    
            // Validación simple para las fechas (solo obligatorio)
            if (fieldName === 'inputidtrailer' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('ID Trailer is required.');
            }
    
            /*if (fieldName === 'inputshipmentcarrier' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Carrier is required.');
            }*/
    
            /*if (fieldName === 'inputshipmentdriver' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Driver is required');
            }*/
    
            /*if (fieldName === 'inputshipmenttrailer' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Trailer is required.');
            }*/
            /*if (fieldName === 'inputshipmenttruck' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Truck is required.');
            }*/
            if (fieldName === 'inputshipmentetd' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Estimated date is required.');
            }
            /*if (fieldName === 'inputshipmentsunits' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Units are required.');
            }*/
            /*if (fieldName === 'inputpallets' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Pallets are required.');
            }*/
            /*if (fieldName === 'inputshipmentsecurityseals' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Security seals are required.');
            }*/
            /*if (fieldName === 'inputshipmentdevicenumber' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Device number is required.');
            }*/
            /*if (fieldName === 'inputshipmentoverhaulid' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Overhaul ID is required.');
            }*/
        });
    
        // Cuando el formulario se envía (al hacer clic en Save)
        $('#createnewshipmentform').submit(function(e) {
            e.preventDefault(); // Evita el envío del formulario
    
            const saveButton = $('#saveButtonShipment');
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
                        text: 'Shipment created successfully.',
                        confirmButtonText: 'Ok'
                    });
                    //$('#closenewtrailerregister').click();
                    //$('#refreshemptytrailertable').click();
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
                        text: 'There was a problem adding the shipment. Please try again.',
                        confirmButtonText: 'Ok'
                    });
                }
            });
        });
    });

