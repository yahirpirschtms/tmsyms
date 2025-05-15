var selectedShipmentTypes = []; // Array para almacenar los nombres de 'shipment types'
var selectedCurrentStatus = []; // Array para almacenar los nombres de 'current status'
var selectedSecurityCompanies = []; // Array para almacenar los nombres de 'current status'
var selectedDrivers = []; // Arreglo para almacenar todos los drivers seleccionados
var selectedCarriers = []; // Arreglo para almacenar todos los carriers seleccionados
var selectedTrailerOwners = []; // Arreglo para almacenar todos los Trailer Owners seleccionados
var shipmentTypeData = null;
var currenStatusData = null;
var securityCompaniesData = null;
var driversData = null;
var carriersData = null;
var trailerOwnersData = null;

$(document).ready(function () {
    var selectedCarrierId = $('#inputshipmentcarrier').data('carrier');
    var selectedTrailerId = $('#inputshipmenttrailer').data('trailerowner');
    var isGeneralCatalogLoaded = false;
    //funcion nutrir los diferentes selects 
    function loadGeneralcatalogs(){
        if (isGeneralCatalogLoaded) return; // Evita cargar dos veces

        $.ajax({
            url: 'getLoadInfo',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                //Cargar los shipment type
                shipmentTypeData = data.shipment_types.map(item => ({
                    id: item.gnct_id,
                    text: item.gntc_value
                }));
                // Asegurarse de que no haya duplicados en 'shipment_type'
                data.shipment_types.forEach(function (type) {
                    if (!selectedShipmentTypes.includes(type.gntc_value)) {
                        selectedShipmentTypes.push(type.gntc_value); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Shipment Type cargados:", selectedShipmentTypes);

                let selectShipmentType = $('#inputshipmentshipmenttype');
                let selectedValueShipmenttype = selectShipmentType.val();

                // Inicializar Select2 para 'inputshipmentshipmenttype'
                $('#inputshipmentshipmenttype').select2({
                    placeholder: 'Select a shipment type',
                    allowClear: false,
                    tags: false,
                    data: shipmentTypeData, // Los datos cargados desde el backend
                    //dropdownParent: $('#neweditcfsproject'),
                    minimumInputLength: 0
                });

                if (selectedValueShipmenttype) {
                    selectShipmentType.val(selectedValueShipmenttype); // Restaura el valor anterior
                }
                //Cargar los current status
                currenStatusData = data.current_status.map(item => ({
                    id: item.gnct_id,
                    text: item.gntc_value
                }));
                // Asegurarse de que no haya duplicados en 'current_status'
                data.current_status.forEach(function (status) {
                    if (!selectedCurrentStatus.includes(status.gntc_value)) {
                        selectedCurrentStatus.push(status.gntc_value); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Current Status cargados:", selectedCurrentStatus);

                let selectCurrentStatus = $('#inputshipmentcurrentstatus');
                let currentValueCurrentStatus = selectCurrentStatus.val(); // Valor actual seleccionado por el usuario
                let initialValueCurrentStatus = selectCurrentStatus.attr('value'); // Valor inicial definido en el HTML
                let selectedValueCurrentStatus = currentValueCurrentStatus || initialValueCurrentStatus;

                if (!selectedValueCurrentStatus) {
                    selectCurrentStatus.append('<option selected disabled hidden></option>');
                }
        
                if (data.current_status.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    // Inicializar Select2 para 'inputshipmentcurrentstatus'
                    $('#inputshipmentcurrentstatus').select2({
                        placeholder: 'Select a Status',
                        allowClear: false,
                        tags: false,
                        data: currenStatusData, // Los datos cargados desde el backend
                        //dropdownParent: $('#neweditcfsproject'),
                        minimumInputLength: 0
                    });

                    setDefaultCurrentStatus();
                }
                //Cargar las security companies
                securityCompaniesData = data.security_companies.map(item => ({
                    id: item.gnct_id,
                    text: item.gntc_value
                }));
                // Asegurarse de que no haya duplicados en 'security companies'
                data.security_companies.forEach(function (companie) {
                    if (!selectedSecurityCompanies.includes(companie.gntc_value)) {
                        selectedSecurityCompanies.push(companie.gntc_value); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Security Companies cargados:", selectedSecurityCompanies);

                // Inicializar Select2 sin AJAX
                $('#inputshipmentsecuritycompany').select2({
                    placeholder: 'Select a Security Company',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: securityCompaniesData, // Pasar los datos directamente
                    minimumInputLength: 0
                });
                //Cargar las drivers
                driversData = data.drivers.map(item => ({
                    id: item.pk_driver,
                    text: item.drivername
                }));
                // Asegurarse de que no haya duplicados en 'drivers'
                data.drivers.forEach(function (driver) {
                    if (!selectedDrivers.includes(driver.drivername)) {
                        selectedDrivers.push(driver.drivername); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Drivers cargados:", selectedDrivers);

                $('#inputshipmentdriver').select2({
                    placeholder: 'Select or enter a New Driver',
                    allowClear: true,
                    tags: true, // Permite agregar nuevas opciones
                    data: driversData, // Pasar los datos directamente
                    minimumInputLength: 0
                });

                //Cargar los carriers
                carriersData = data.carriers.map(item => ({
                    id: item.pk_company,
                    text: item.CoName
                }));
                // Asegurarse de que no haya duplicados en Carriers'
                data.carriers.forEach(function (carrier) {
                    if (!selectedCarriers.includes(carrier.CoName)) {
                        selectedCarriers.push(carrier.CoName); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Carriers cargados:", selectedCarriers);
                
                $('#inputshipmentcarrier').select2({
                    placeholder: 'Select a Carrier',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: carriersData, // Pasar los datos directamente
                    minimumInputLength: 0
                });

                let carrier = carriersData.find(item => item.id == selectedCarrierId);
                if (carrier) {
                    let exists = $('#inputshipmentcarrier option[value="' + carrier.id + '"]').length > 0;
                    if (!exists) {
                        let newOption = new Option(carrier.text, carrier.id, true, true);
                        $('#inputshipmentcarrier').append(newOption).trigger('change');
                    } else {
                        $('#inputshipmentcarrier').val(carrier.id).trigger('change');
                    }
                }

                //Cargar los Trailer Owners
                trailerOwnersData = data.trailer_owners.map(item => ({
                    id: item.pk_company,
                    text: item.CoName
                }));
                // Asegurarse de que no haya duplicados en Carriers'
                data.trailer_owners.forEach(function (trailer) {
                    if (!selectedTrailerOwners.includes(trailer.CoName)) {
                        selectedTrailerOwners.push(trailer.CoName); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Trailer Owners cargados:", selectedTrailerOwners);

                // Inicializar Select2 sin AJAX
                $('#inputshipmenttrailer').select2({
                    placeholder: 'Select a Trailer Owner',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: trailerOwnersData, // Pasar los datos directamente
                    minimumInputLength: 0
                });

                let trailer = trailerOwnersData.find(item => item.id == selectedTrailerId);
                if (trailer) {
                    let exists = $('#inputshipmenttrailer option[value="' + trailer.id + '"]').length > 0;
                    if (!exists) {
                        let newOption = new Option(trailer.text, trailer.id, true, true);
                        $('#inputshipmenttrailer').append(newOption).trigger('change');
                    } else {
                        $('#inputshipmenttrailer').val(trailer.id).trigger('change');
                    }
                }

                isGeneralCatalogLoaded = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los catálogos:', error);
            }
        });
    }
    loadGeneralcatalogs();

    function setDefaultCurrentStatus() {
        let prealerted = false;
        let driverAssigned = false;

        // Buscar los valores
        currenStatusData.forEach(item => {
            if (item.text === 'Prealerted' && !prealerted) {
                prealerted = true;
            }
            if (item.text === 'Driver Assigned' && !driverAssigned) {
                driverAssigned = true;
            }
        });

        let driverValue = $('#inputshipmentdriver').val();
        const select = $('#inputshipmentcurrentstatus');

        if (driverValue && driverAssigned) {
            const driverAssignedItem = currenStatusData.find(item => item.text === 'Driver Assigned');
            if (driverAssignedItem) {
                select.val(driverAssignedItem.id).trigger('change');
            }
        } else if (!driverValue && prealerted) {
            const prealertedItem = currenStatusData.find(item => item.text === 'Prealerted');
            if (prealertedItem) {
                select.val(prealertedItem.id).trigger('change');
            }
        }
    }

    //Funcion para buscar el shipmenttype en la pantalla de empty trailer update
    function loadShipmentType() {
        var shipmentTypeRoute = $('#inputshipmentshipmenttype').data('url');
          $.ajax({
              url: shipmentTypeRoute,
              method: 'GET',
              success: function (data) {
                  let select = $('#inputshipmentshipmenttype');
                  let selectedValue = select.val();
                  select.empty(); // Limpia el select eliminando todas las opciones
                  //select.append('<option selected disabled hidden></option>'); // Opción inicial

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

    //$('#inputshipmentshipmenttype').on('focus', loadShipmentType);
    //loadShipmentType();

    //carga de los current status
    function LoadCurrentStatus() {
        var locationsRoute = $('#inputshipmentcurrentstatus').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputshipmentcurrentstatus');
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
                    let prealertedFound = false;
                    let driverAssignedFound = false;
                    
                    // Añadir las opciones al select
                    data.forEach(item => {
                        select.append(`<option value="${item.gnct_id}">${item.gntc_description}</option>`);
                        
                        // Verificar si la opción "Prealerted" está entre las opciones
                        if (item.gntc_description === 'Prealerted' && !prealertedFound) {
                            prealertedFound = true;
                        }
                        
                        // Verificar si la opción "Driver Assigned" está entre las opciones
                        if (item.gntc_description === 'Driver Assigned' && !driverAssignedFound) {
                            driverAssignedFound = true;
                        }
                    });
        
                    // Si "Driver Assigned" está disponible y el segundo select tiene un valor
                    let driverValue = $('#inputshipmentdriver').val();
                    if (driverValue) {
                        if (driverAssignedFound) {
                            select.val(data.find(item => item.gntc_description === 'Driver Assigned').gnct_id);
                        }
                    } else if (prealertedFound) {
                        // Si no hay conductor asignado, seleccionamos "Prealerted" por defecto
                        select.val(data.find(item => item.gntc_description === 'Prealerted').gnct_id);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data locations:', error);
            }
        });
    }
    
    // Ejecutar la función al enfocar el select y al cargar la página
    $('#inputshipmentcurrentstatus').on('focus', setDefaultCurrentStatus);
    $('#inputshipmentdriver').on('change', setDefaultCurrentStatus);
    //LoadCurrentStatus();

    //Busqueda de Driver en un nuevo registro
    var selectedsecuritycompanies = []; // Arreglo para almacenar todos los drivers seleccionados

    var isSecurityCompaniesLoaded = false; // Bandera para controlar la carga

    //loadSecurityCompaniesOnce();
    
    function loadSecurityCompaniesOnce() {
        if (isSecurityCompaniesLoaded) return; // Evita cargar dos veces
    
        $.ajax({
            url:'/securitycompany-shipment', 
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var securityCompaniesData = data.map(item => ({
                    id: item.gnct_id,
                    text: item.gntc_value
                }));
                data.forEach(function (securitycompanies) {
                    if (!selectedsecuritycompanies.includes(securitycompanies.gntc_value)) {
                        selectedsecuritycompanies.push(securitycompanies.gntc_value); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Security Companies cargados desde la base de datos:", selectedsecuritycompanies);
    
                // Inicializar Select2 sin AJAX
                $('#inputshipmentsecuritycompany').select2({
                    placeholder: 'Select a Security Company',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: securityCompaniesData, // Pasar los datos directamente
                    minimumInputLength: 0
                });
    
                isSecurityCompaniesLoaded = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar las Security Companies:', error);
            }
        });
    }

    //Busqueda de Driver en un nuevo registro
    var newlyCreatedDriverId = null; // Variable para almacenar el ID del carrier recién creado
    var isDriversLoaded = false; // Bandera para controlar la carga

    //loadDriversOnce();
    
    function loadDriversOnce() {
        if (isDriversLoaded) return; // Evita cargar dos veces
    
        $.ajax({
            url:'/drivers-shipment', 
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var driversData = data.map(driver => ({
                    id: driver.pk_driver,
                    text: driver.drivername
                }));
                data.forEach(function (driver) {
                    if (!selectedDrivers.includes(driver.drivername)) {
                        selectedDrivers.push(driver.drivername); // Agregar al arreglo si no está ya
                    }
                }); 
                console.log("Drivers cargados desde la base de datos:", selectedDrivers);
    
                // Inicializar Select2 sin AJAX
                $('#inputshipmentdriver').select2({
                    placeholder: 'Select or enter a New Driver',
                    allowClear: true,
                    tags: true, // Permite agregar nuevas opciones
                    data: driversData, // Pasar los datos directamente
                    minimumInputLength: 0
                });
    
                isDriversLoaded = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los drivers:', error);
            }
        });
    }
    /*loadDriversAjax();

    function loadDriversAjax() {
        if (isDriversLoaded) return; // Si ya se ejecutó, salir de la función
        
        if (!$('#inputshipmentdriver').hasClass("select2-hidden-accessible")) {
            console.log("lo vilvio a hacer")
            $('#inputshipmentdriver').select2({
                placeholder: 'Select or enter a New Driver',
                allowClear: true,
                tags: true, // Permite agregar nuevas opciones
                //dropdownParent: $('#newtrailerempty'),
                ajax: {
                    url: carrierRoute,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term || '' // Si no hay texto, envía un string vacío
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.pk_driver,
                                text: item.drivername
                            }))
                        };
                    },
                    cache: true
                },
                minimumInputLength:0
            });    
        }
        isDriversLoaded = true; // Marcar como cargado
    }*/

    // Cargar los drivers existentes de la base de datos
    /*$.ajax({
        url: '/drivers-shipment',  // Ruta que manejará la carga de los drivers existentes
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (driver) {
                if (!selectedDrivers.includes(driver.drivername)) {
                    selectedDrivers.push(driver.drivername); // Agregar al arreglo si no está ya
                }
            });
            console.log("Drivers cargados desde la base de datos:", selectedDrivers);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar los drivers existentes:', error);
        }
    });*/

    // Actualizar la lista cuando se haga clic en el select
    /*$('#inputshipmentdriver').on('click', function () {
        loadDriversAjax();
    });*/

    // Cuando el usuario seleccione o ingrese un nuevo valor
    $('#inputshipmentdriver').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada

        // Si no es el nuevo carrier, lo procesamos
        if (selectedText  !== newlyCreatedDriverId &&  selectedText.trim() !== '') {
            console.log(selectedText);
            //saveNewDriver(selectedText);
            if (!selectedDrivers.includes(selectedText)) {
                selectedDrivers.push(selectedText);  // Agregar al arreglo solo si no existe
                console.log(selectedDrivers);  // Mostrar el arreglo con todos los drivers seleccionados
                saveNewDriver(selectedText);
            }
        }
    });

    // Guardar un nuevo carrier en la base de datos
    function saveNewDriver(driversName) {
        $.ajax({
            url: '/save-new-driver',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                driversName: driversName,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);

                // Crear una nueva opción para el select2 con el nuevo carrier
                var newOption = new Option(response.newDriver.drivername, response.newDriver.pk_driver, true, true);

                // Agregar la nueva opción al select2
                $('#inputshipmentdriver').append(newOption).trigger('change');

                // Seleccionar el nuevo carrier automáticamente
                $('#inputshipmentdriver').val(response.newDriver.pk_driver).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedDriverId = response.newDriver.drivername;

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#inputshipmentdriver').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedDriverId) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedDriverId = null;  // Restablecer el ID del carrier creado
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el Driver', error);
            }
        });
    }

    $("#inputshipmentstmid").on("input", function () {
        let idService = $(this).val().trim(); // Obtiene el valor del input y quita espacios

        if (idService.length > 6) { // Solo ejecuta si tiene más de 6 caracteres
            $.ajax({
                url: "/get-service", // Ruta a tu controlador en Laravel
                method: "GET",
                data: { id_service: idService },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        if(response.from !== null && response.from_id !== null){
                            $("#inputorigin").val(response.from); // Asigna el valor de "from" al input
                            $("#inputoriginn").val(response.from_id);
                            $('#inputorigin, #inputshipmentstmid').removeClass('is-invalid');
                            $('#inputorigin, #inputshipmentstmid').next('.invalid-feedback').text('');
                            loadLanes();
                        }else{
                            $("#inputorigin").val(""); // Borra el valor si no se encontró
                            $("#inputoriginn").val(""); // Borra el valor si no se encontró
                            $('#inputorigin').addClass('is-invalid');
                            $('#inputorigin').next('.invalid-feedback').text('Origin is required.');
                            $("#inputshipmentstmid").addClass('is-invalid');
                            $("#inputshipmentstmid").next('.invalid-feedback').text('ID STM has no origin.');
                            $("#ln_code").val(""); // Borra el valor si no se encontró
                        }
                        if(response.to !== null && response.to_id !== null){
                            $("#inputshipmentdestination").val(response.to);
                            $("#inputshipmentdestinationn").val(response.to_id);
                            $('#inputshipmentdestination, #inputshipmentstmid').removeClass('is-invalid');
                            $('#inputshipmentdestination, #inputshipmentstmid').next('.invalid-feedback').text('');
                        }else{
                            $("#inputshipmentdestination").val(""); // Borra el valor si no se encontró
                            $("#inputshipmentdestinationn").val(""); // Borra el valor si no se encontró
                            $('#inputshipmentdestination').addClass('is-invalid');
                            $('#inputshipmentdestination').next('.invalid-feedback').text('Destination is required.');
                            $("#inputshipmentstmid").addClass('is-invalid');
                            $("#inputshipmentstmid").next('.invalid-feedback').text('ID STM has no destination.');
                        }
                        if(response.from === null && response.from_id === null && response.to === null && response.to_id === null){
                            $("#inputorigin").val(""); // Borra el valor si no se encontró
                            $("#inputoriginn").val(""); // Borra el valor si no se encontró
                            $('#inputorigin').addClass('is-invalid');
                            $('#inputorigin').next('.invalid-feedback').text('Origin is required.');
                            $("#ln_code").val(""); // Borra el valor si no se encontró
                            $("#inputshipmentdestination").val(""); // Borra el valor si no se encontró
                            $("#inputshipmentdestinationn").val(""); // Borra el valor si no se encontró
                            $('#inputshipmentdestination').addClass('is-invalid');
                            $('#inputshipmentdestination').next('.invalid-feedback').text('Destination is required.');
                            $("#inputshipmentstmid").addClass('is-invalid');
                            $("#inputshipmentstmid").next('.invalid-feedback').text('ID STM has no origin or destination.');
                            
                        }
                    } else {
                        let inputorigin = $("#inputorigin").val().trim();
                        let inputshipmentdestination = $("#inputshipmentdestination").val().trim();
                        let inputoriginn = $("#inputoriginn").val().trim();
                        let inputshipmentdestinationn = $("#inputshipmentdestinationn").val().trim();
                            $("#inputorigin").val(""); // Borra el valor si no se encontró
                            $("#inputshipmentdestination").val(""); // Borra el valor si no se encontró
                            $("#inputoriginn").val(""); // Borra el valor si no se encontró
                            $("#inputshipmentdestinationn").val(""); // Borra el valor si no se encontró
                            $("#ln_code").val(""); // Borra el valor si no se encontró

                            if($('#inputshipmentstmid').val().trim().length === 0){
                                $("#inputshipmentstmid").addClass('is-invalid');
                                $("#inputshipmentstmid").next('.invalid-feedback').text('ID STM is required.');
                            }else{
                                $("#inputshipmentstmid").addClass('is-invalid');
                                $("#inputshipmentstmid").next('.invalid-feedback').text('ID STM doesnt exists.');
                            }
                            
                            if ($('#inputshipmentdestination').val().trim().length === 0) {
                                $('#inputshipmentdestination').addClass('is-invalid');
                                $('#inputshipmentdestination').next('.invalid-feedback').text('Destination is required.');
                            }
                            if ($('#inputorigin').val().trim().length === 0) {
                                $('#inputorigin').addClass('is-invalid');
                                $('#inputorigin').next('.invalid-feedback').text('Origin is required.');
                            }
                        
                    }
                },
                error: function () {
                    console.log("Error en la petición AJAX");
                }
            });
        }else{
            $("#inputorigin").val(""); // Borra el valor si no se encontró
            $("#inputoriginn").val(""); // Borra el valor si no se encontró
            $("#inputshipmentdestination").val(""); // Borra el valor si no se encontró
            $("#inputshipmentdestinationn").val(""); // Borra el valor si no se encontró
            $("#ln_code").val(""); // Borra el valor si no se encontró
        }
    });

    function loadLanes(){
        let idorigin = $("#inputorigin").val(); // Obtiene el valor del input y quita espacios
        let idoriginn = $("#inputoriginn").val().trim();
            $.ajax({
                url: "/get-lanestrafficworkflowstart", // Ruta a tu controlador en Laravel
                method: "GET",
                data: { id_companie: idorigin },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $("#ln_code").val(response.ln_code); // Asigna el valor de "from" al input
                    } else {
                        $("#ln_code").val(""); // Asigna el valor de "from" al input
                    }
                },
                error: function () {
                    console.log("Error en la petición AJAX");
                }
            });
        
    }

    //Manejo para nuevos carries 
    var newlyCreatedCarrierId = null;

    // Cuando el usuario seleccione o ingrese un nuevo valor
    $('#inputshipmentcarrier').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada
        
        // Si no es el nuevo carrier, lo procesamos
        if (selectedText !== newlyCreatedCarrierId &&  selectedText.trim() !== '') {
            console.log(selectedText);
            if (!selectedCarriers.includes(selectedText)) {
                selectedCarriers.push(selectedText);  // Agregar al arreglo solo si no existe
                console.log(selectedCarriers);  // Mostrar el arreglo con todos los carrier seleccionados
                saveNewCarrierShipment(selectedText);
            }
            if (!selectedTrailerOwners.includes(selectedText)) {
                selectedTrailerOwners.push(selectedText);  
                console.log(selectedTrailerOwners); 
            }
        }
    });

    // Guardar un nuevo carrier en la base de datos
    function saveNewCarrierShipment(carrierName) {
        $.ajax({
            url: '/save-new-carrier',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                carrierName: carrierName,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);

                // Crear una nueva opción para el select2 con el nuevo carrier
                var newOption = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                var newOption1 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);

                // Agregar la nueva opción al select2
                $('#inputshipmentcarrier').append(newOption).trigger('change');
                // Agregar la nueva opción al select2
                $('#inputshipmenttrailer').append(newOption1);

                // Seleccionar el nuevo carrier automáticamente
                $('#inputshipmentcarrier').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedCarrierId = response.newCarrier.CoName;
                //loadCarriersShipment();

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#inputshipmentcarrier').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedCarrierId) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedCarrierId = null;  // Restablecer el ID del carrier creado
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el carrier', error);
            }
        });
    }

    //Manejo para nuevos trailers 
    var newlyCreatedTrailerId = null;

    $('#inputshipmenttrailer').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada

        if (selectedText !== newlyCreatedTrailerId &&  selectedText.trim() !== '') {
            console.log(selectedText);
            if (!selectedTrailerOwners.includes(selectedText)) {
                selectedTrailerOwners.push(selectedText);  
                console.log(selectedTrailerOwners); 
                saveNewTrailerOwnerShipment(selectedText);
            }
            if (!selectedCarriers.includes(selectedText)) {
                selectedCarriers.push(selectedText);  // Agregar al arreglo solo si no existe
                console.log(selectedCarriers);  // Mostrar el arreglo con todos los carrier seleccionados
            }
        }
    });

    function saveNewTrailerOwnerShipment(carrierName) {
        $.ajax({
            url: '/save-new-trailerowner',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                carrierName: carrierName,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);

                // Crear una nueva opción para el select2 con el nuevo carrier
                var newOption = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                var newOption1 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);

                // Agregar la nueva opción al select2
                $('#inputshipmenttrailer').append(newOption).trigger('change');
                // Agregar la nueva opción al select2
                $('#inputshipmentcarrier').append(newOption1);

                // Seleccionar el nuevo carrier automáticamente
                $('#inputshipmenttrailer').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedTrailerId = response.newCarrier.CoName;
                //loadTrailerOwnersShipment();

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#inputshipmenttrailer').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedTrailerId) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedTrailerId = null;  // Restablecer el ID del carrier creado
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el Trailer Owner', error);
            }
        });
    }
});

//Cargar Carries en la pantalla de registro de shipments
$(document).ready(function () {
    var newlyCreatedCarrierId = null; // Asegúrate de que esta variable esté definida al inicio
    var carrierRoute = $('#inputshipmentcarrier').data('url');
    var selectedCarrierId = $('#inputshipmentcarrier').data('carrier'); // Recuperar el valor predeterminado
    console.log(selectedCarrierId)
    /*if (selectedCarrierId) {
        console.log(selectedCarrierId)
        $.ajax({
            url: 'carrier-emptytrailerAjax', // Llamamos a la misma API de carriers
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                let carrier = data.find(item => item.pk_company == selectedCarrierId);
                if (carrier) {
                    let newOption = new Option(carrier.CoName, carrier.pk_company, true, true);
                    $('#inputshipmentcarrier').append(newOption).trigger('change');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los carriers:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'There was an error loading carrier information.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        });
    }*/

    var isCarriersLoaded = false; // Bandera para controlar la carga
    
    //loadCarriersShipmentOnce();

    function loadCarriersShipmentOnce() {
        if (isCarriersLoaded) return; // Evita cargar dos veces
    
        $.ajax({
            url:'carrier-emptytrailerAjax',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var carriersData = data.map(item => ({
                    id: item.pk_company,
                    text: item.CoName
                }));
                data.forEach(function (carrier) {
                    if (!selectedCarriers.includes(carrier.CoName)) {
                        selectedCarriers.push(carrier.CoName); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Carriers cargados desde la base de datos:", selectedCarriers);
    
                // Inicializar Select2 sin AJAX
                $('#inputshipmentcarrier').select2({
                    placeholder: 'Select a Carrier',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: carriersData, // Pasar los datos directamente
                    minimumInputLength: 0
                });
    
                isCarriersLoaded = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los carriers:', error);
            }
        });
    }
    /*loadCarriersShipment();

    function loadCarriersShipment() {
        $('#inputshipmentcarrier').select2({
            placeholder: 'Select or enter a New Carrier',
            allowClear: true,
            tags: true, // Permite agregar nuevas opciones
            ajax: {
                url: carrierRoute,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term || '' // Si no hay texto, envía un string vacío
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(item => ({
                            id: item.pk_company,
                            text: item.CoName
                        }))
                    };
                },
                cache: true
            },
            minimumInputLength: 0
        });

        // Asegúrate de que el valor se establezca después de la inicialización
        
        setTimeout(function() {
            if (selectedCarrierId) {
                console.log("Valor predeterminado a establecer: ", selectedCarrierId);
                $('#inputshipmentcarrier').val(selectedCarrierId).trigger('change');
            }
        }, 500); // Ajusta el tiempo según lo necesario para asegurar que los datos estén listos
    }*/

    // Actualizar la lista cuando se haga clic en el select
    /*$('#inputshipmentcarrier').on('click', function () {
        loadCarriersShipment();
    });*/

    // Cuando el usuario seleccione o ingrese un nuevo valor
    /*$('#inputshipmentcarrier').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada
        
        // Si no es el nuevo carrier, lo procesamos
        if (selectedText !== newlyCreatedCarrierId &&  selectedText.trim() !== '') {
            console.log(selectedText);
            //saveNewCarrierShipment(selectedText);
            if (!selectedCarriers.includes(selectedText)) {
                selectedCarriers.push(selectedText);  // Agregar al arreglo solo si no existe
                console.log(selectedCarriers);  // Mostrar el arreglo con todos los drivers seleccionados
                saveNewCarrierShipment(selectedText);
            }
        }
    });*/

    // Guardar un nuevo carrier en la base de datos
    /*function saveNewCarrierShipment(carrierName) {
        $.ajax({
            url: '/save-new-carrier',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                carrierName: carrierName,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);

                // Crear una nueva opción para el select2 con el nuevo carrier
                var newOption = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);

                // Agregar la nueva opción al select2
                $('#inputshipmentcarrier').append(newOption).trigger('change');

                // Seleccionar el nuevo carrier automáticamente
                $('#inputshipmentcarrier').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedCarrierId = response.newCarrier.CoName;
                //loadCarriersShipment();

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#inputshipmentcarrier').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedCarrierId) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedCarrierId = null;  // Restablecer el ID del carrier creado
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el carrier', error);
            }
        });
    }*/
});

//Cargar Origins en la pantalla de registro de shipments
$(document).ready(function () {
    /*var selectedOrigins = []; // Arreglo para almacenar todos los origins seleccionados
    $.ajax({
        url: 'locations-emptytrailerAjax',  // Ruta que manejará la carga de los drivers existentes
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (carrier) {
                if (!selectedOrigins.includes(carrier.CoName)) {
                    selectedOrigins.push(carrier.CoName); // Agregar al arreglo si no está ya
                }
            });
            console.log("Origins cargados desde la base de datos:", selectedOrigins);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar los Origins existentes:', error);
        }
    });


    var newlyCreatedCarrierId = null; // Asegúrate de que esta variable esté definida al inicio
    var carrierRoute = $('#inputorigin').data('url');
    var selectedCarrierId = $('#inputorigin').data('location'); // Recuperar el valor predeterminado
    console.log(selectedCarrierId);

    if (selectedCarrierId) {
        console.log(selectedCarrierId)
        $.ajax({
            url: 'locations-emptytrailerAjax', // Llamamos a la misma API de carriers
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                let carrier = data.find(item => item.pk_company == selectedCarrierId);
                if (carrier) {
                    let newOption = new Option(carrier.CoName, carrier.pk_company, true, true);
                    $('#inputorigin').append(newOption).trigger('change');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los carriers:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'There was an error loading carrier information.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        });
    }
    
    loadCarriersShipment();

    function loadCarriersShipment() {
        $('#inputorigin').select2({
            placeholder: 'Select or enter a New Origin',
            //allowClear: true,
            tags: true, // Permite agregar nuevas opciones
            ajax: {
                url: carrierRoute,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term || '' // Si no hay texto, envía un string vacío
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(item => ({
                            id: item.pk_company,
                            text: item.CoName
                        }))
                    };
                },
                cache: true
            },
            minimumInputLength: 0
        });

        // Asegúrate de que el valor se establezca después de la inicialización
        
        setTimeout(function() {
            if (selectedCarrierId) {
                console.log("Valor predeterminado a establecer: ", selectedCarrierId);
                $('#inputorigin').val(selectedCarrierId).trigger('change');
            }
        }, 500); // Ajusta el tiempo según lo necesario para asegurar que los datos estén listos
    }

    // Actualizar la lista cuando se haga clic en el select
    $('#inputorigin').on('click', function () {
        loadCarriersShipment();
    });

    // Cuando el usuario seleccione o ingrese un nuevo valor
    $('#inputorigin').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada
        
        // Si no es el nuevo carrier, lo procesamos
        if (selectedText !== newlyCreatedCarrierId &&  selectedText.trim() !== '') {
            console.log(selectedText);
            //saveNewLocationShipment(selectedText);
            if (!selectedOrigins.includes(selectedText)) {
                selectedOrigins.push(selectedText);  // Agregar al arreglo solo si no existe
                console.log(selectedOrigins);  // Mostrar el arreglo con todos los drivers seleccionados
                saveNewLocationShipment(selectedText);
            }
        }
    });

    // Guardar un nuevo carrier en la base de datos
    function saveNewLocationShipment(carrierName) {
        $.ajax({
            url: '/save-new-location',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                carrierName: carrierName,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);

                // Crear una nueva opción para el select2 con el nuevo carrier
                var newOption = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);

                // Agregar la nueva opción al select2
                $('#inputorigin').append(newOption).trigger('change');

                // Seleccionar el nuevo carrier automáticamente
                $('#inputorigin').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedCarrierId = response.newCarrier.CoName;

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#inputorigin').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedCarrierId) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedCarrierId = null;  // Restablecer el ID del carrier creado
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el carrier', error);
            }
        });
    }*/
});

//Cargar TrailerOwners en la pantalla de registro de shipments
$(document).ready(function () {
    /*$.ajax({
        url: 'trailerowner-emptytrailerAjax',  // Ruta que manejará la carga de los drivers existentes
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (carrier) {
                if (!selectedTrailerOwners.includes(carrier.CoName)) {
                    selectedTrailerOwners.push(carrier.CoName); // Agregar al arreglo si no está ya
                }
            });
            console.log("Trailer Owners cargados desde la base de datos:", selectedTrailerOwners);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar los Trailer Owners existentes:', error);
        }
    });*/
    
    var newlyCreatedCarrierId = null; // Asegúrate de que esta variable esté definida al inicio
    var carrierRoute = $('#inputshipmenttrailer').data('url');
    var selectedCarrierId = $('#inputshipmenttrailer').data('trailerowner'); // Recuperar el valor predeterminado
    console.log(selectedCarrierId);

    /*if (selectedCarrierId) {
        console.log(selectedCarrierId)
        $.ajax({
            url: 'trailerowner-emptytrailerAjax', // Llamamos a la misma API de carriers
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                let carrier = data.find(item => item.pk_company == selectedCarrierId);
                if (carrier) {
                    let newOption = new Option(carrier.CoName, carrier.pk_company, true, true);
                    $('#inputshipmenttrailer').append(newOption).trigger('change');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los carriers:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'There was an error loading carrier information.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        });
    }*/

    var isTrailerOwnersLoaded = false; // Bandera para controlar la carga
    
    //loadTrailerOwnersShipmentOnce();

    function loadTrailerOwnersShipmentOnce() {
        if (isTrailerOwnersLoaded) return; // Evita cargar dos veces
    
        $.ajax({
            url:'trailerowner-emptytrailerAjax',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var trailerOwnersData = data.map(item => ({
                    id: item.pk_company,
                    text: item.CoName
                }));
                data.forEach(function (carrier) {
                    if (!selectedTrailerOwners.includes(carrier.CoName)) {
                        selectedTrailerOwners.push(carrier.CoName); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Trailer Owners cargados desde la base de datos:", selectedTrailerOwners);
    
                // Inicializar Select2 sin AJAX
                $('#inputshipmenttrailer').select2({
                    placeholder: 'Select a Trailer Owner',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: trailerOwnersData, // Pasar los datos directamente
                    minimumInputLength: 0
                });
    
                isTrailerOwnersLoaded = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los TrailerOwners:', error);
            }
        });
    }
    
    /*loadTrailerOwnersShipment();

    function loadTrailerOwnersShipment() {
        $('#inputshipmenttrailer').select2({
            placeholder: 'Select or enter a New Trailer Owner',
            allowClear: true,
            tags: false, // Permite agregar nuevas opciones
            ajax: {
                url: carrierRoute,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term || '' // Si no hay texto, envía un string vacío
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.map(item => ({
                            id: item.pk_company,
                            text: item.CoName
                        }))
                    };
                },
                cache: true
            },
            minimumInputLength: 0
        });

        // Asegúrate de que el valor se establezca después de la inicialización
        
        setTimeout(function() {
            if (selectedCarrierId) {
                console.log("Valor predeterminado a establecer: ", selectedCarrierId);
                $('#inputshipmenttrailer').val(selectedCarrierId).trigger('change');
            }
        }, 500); // Ajusta el tiempo según lo necesario para asegurar que los datos estén listos
    }*/

    // Actualizar la lista cuando se haga clic en el select
    /*$('#inputshipmenttrailer').on('click', function () {
        loadTrailerOwnersShipment();
    });*/

    // Cuando el usuario seleccione o ingrese un nuevo valor
    $('#inputshipmenttrailer').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada
        
        // Si no es el nuevo carrier, lo procesamos
        if (selectedText !== newlyCreatedCarrierId &&  selectedText.trim() !== '') {
            console.log(selectedText);
            //saveNewLocationShipment(selectedText);
            if (!selectedTrailerOwners.includes(selectedText)) {
                selectedTrailerOwners.push(selectedText);  // Agregar al arreglo solo si no existe
                console.log(selectedTrailerOwners);  // Mostrar el arreglo con todos los drivers seleccionados
                saveNewLocationShipment(selectedText);
            }
        }
    });

    // Guardar un nuevo carrier en la base de datos
    function saveNewLocationShipment(carrierName) {
        $.ajax({
            url: '/save-new-trailerowner',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                carrierName: carrierName,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);

                // Crear una nueva opción para el select2 con el nuevo carrier
                var newOption = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);

                // Agregar la nueva opción al select2
                $('#inputshipmenttrailer').append(newOption).trigger('change');

                // Seleccionar el nuevo carrier automáticamente
                $('#inputshipmenttrailer').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedCarrierId = response.newCarrier.CoName;
                //loadTrailerOwnersShipment();

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#inputshipmenttrailer').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedCarrierId) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedCarrierId = null;  // Restablecer el ID del carrier creado
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el Trailer Owner', error);
            }
        });
    }
});

$(document).ready(function() {
 
    //Formatos fecha y hora
    flatpickr(".datetms", {
      dateFormat: "m/d/Y",  // Establece el formato como mes/día/año
      //defaultDate: "today",     // Establece la fecha y hora actuales como predeterminados
        onOpen: function (selectedDates, dateStr, instance) {
        // Si el campo está vacío, se coloca la fecha y hora actual
            if (dateStr === "") {
                instance.setDate(new Date(), true); // Establece la fecha actual
            }
        },
    });
    
    flatpickr(".datetimepicker", {
    enableTime: true,         // Habilita la selección de hora
    dateFormat: "m/d/y H:i:S",  // Establece el formato para incluir año, mes, día, hora, minuto y segundo
    time_24hr: true,          // Si quieres el formato de 24 horas
    enableSeconds: true,      // Habilita la selección de segundos
    //defaultDate: new Date(),
    onOpen: function (selectedDates, dateStr, instance) {
        // Si el campo está vacío, se coloca la fecha y hora actual
        if (dateStr === "") {
            instance.setDate(new Date(), true); // Establece la fecha actual
        }
    },
    });

    //Funcion para buscar las Carriers en la pantalla de shipments
    /*function loadCarriers() {
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
                    //if (!selectedValue) {
                    //    select.append('<option selected disabled hidden></option>');
                    //}
        
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
    /*$('#inputshipmentcarrier').on('focus', loadCarriers);
    loadCarriers();*/

    //Funcion para buscar las Trailers Owners en la pantalla de shipments
    /*function LoadTrailersOwners() {
        var locationsRoute = $('#inputshipmenttrailer').data('url');
        $.ajax({
                url: locationsRoute,
                type: 'GET',
                success: function (data) {
                    let select = $('#inputshipmenttrailer');
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
    }*/
        
    // Ejecutar la función al enfocar el select y al cargar la página
    /*$('#inputshipmenttrailer').on('focus', LoadTrailersOwners);
    LoadTrailersOwners();*/

    //Funcion para buscar las Origins en la pantalla de shipments
    /*function loadOrigins() {
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
    }*/
        
    // Ejecutar la función al enfocar el select y al cargar la página
    /*$('#inputorigin').on('focus', loadOrigins);
    loadOrigins();*/

// Función para cargar las destinos desde la base de datos
/*function loadDestinations(query = '') {
    var locationsRoute = $('#inputshipmentdestination').data('url') + '?query=' + query;
    $.ajax({
        url: locationsRoute,
        type: 'GET',
        success: function (data) {
            let select = $('#inputshipmentdestination');
            let selectedValue = select.val(); // Obtener el valor seleccionado antes de actualizar

            // Agregar la opción de "Seleccione una opción"
            // Si las opciones no se encuentran, no limpiar el select
            if (data.length === 0) {
                if (select.find('option').length === 0) {
                    select.append('<option disabled>No options available</option>');
                }
            } else {
                // Si las opciones ya están, solo agregar las nuevas
                if (select.find('option').length === 0) {
                    select.append('<option value="" selected disabled hidden>Seleccione una opción</option>');
                }
                data.forEach(item => {
                    // Verificar si la opción ya existe
                    if (!select.find(`option[value="${item.fac_id}"]`).length) {
                        select.append(`<option value="${item.fac_id}">${item.fac_name}</option>`);
                    }
                });
            }

            // Restaura la selección anterior si existe
            if (selectedValue) {
                select.val(selectedValue);
            }

            // Forzar a Select2 que se actualice con las nuevas opciones
            select.trigger('change.select2');
        },
        error: function (xhr, status, error) {
            console.error('Error fetching data locations:', error);
        }
    });
}

// Ejecutar la función al cargar la página y al escribir en el select
$(document).ready(function() {
    // Inicializa Select2 una sola vez
    if (!$('#inputshipmentdestination').data('select2')) {
        $('#inputshipmentdestination').select2({
            placeholder: "Escribe para buscar o selecciona una opción",
            allowClear: true, // Permitir borrar la selección
        });
    }

    // Cargar las opciones al iniciar
    loadDestinations();

    // Cargar las opciones al escribir en el select
    $('#inputshipmentdestination').on('input', function() {
        var query = $(this).val();
        loadDestinations(query); // Llamar a la función con el texto ingresado
    });

    // Asegurarse de que el valor seleccionado no se pierda al cambiar el focus
    $('#inputshipmentdestination').on('select2:select', function (e) {
        let selectedValue = e.params.data.id;
        $(this).val(selectedValue);  // Asegurarse de que el valor quede seleccionado
    });
});*/


//Funcion Buena
    //Funcion para buscar las Destinations en la pantalla de shipments
    /*function loadDestinations() {
        var locationsRoute = $('#inputshipmentdestination').data('url');
        $.ajax({
                url: locationsRoute,
                type: 'GET',
                success: function (data) {
                    let select = $('#inputshipmentdestination');
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
                            select.append(`<option value="${item.fac_id}">${item.fac_name}</option>`);
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
    }*/
        
    // Ejecutar la función al enfocar el select y al cargar la página
    /*$('#inputshipmentdestination').on('focus', loadDestinations);
    loadDestinations();*/


    /*function loadDestinations() {
        var locationsRoute = $('#inputshipmentdestination').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let datalist = $('#destinations-list');
                let input = $('#inputshipmentdestination');
                let currentValue = input.attr('data-selected-id'); // Guardar el ID actual
    
                datalist.empty(); // Limpia las opciones
    
                if (data.length === 0) {
                    datalist.append('<option disabled>No options available</option>');
                } else {
                    data.forEach(item => {
                        datalist.append(`<option value="${item.fac_name}" data-id="${item.fac_id}"></option>`);
                    });
                }
    
                // Restaurar el valor si existía
                if (currentValue) {
                    input.val($(`option[data-id="${currentValue}"]`).attr("value"));
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data locations:', error);
            }
        });
    }
    
    // Evento para guardar el ID real en un atributo oculto
    $('#inputshipmentdestination').on('input', function () {
        let selectedValue = $(this).val();
        let selectedOption = $(`#destinations-list option[value="${selectedValue}"]`);
        if (selectedOption.length > 0) {
            $(this).attr('data-selected-id', selectedOption.attr('data-id'));
        } else {
            $(this).attr('data-selected-id', ''); // Si el usuario ingresa manualmente algo que no está en la lista
        }
    });
    
    // Cargar datos al enfocar el input y al cargar la página
    $('#inputshipmentdestination').on('focus', loadDestinations);
    loadDestinations();*/
    

    //Funcion para buscar los Drivers
    /*function LoadDrivers() {
        let selectedCarrierId = $('#inputshipmentcarrier').val(); // Obtener el ID seleccionado
    
        if (!selectedCarrierId) {
            // Si no hay un carrier seleccionado, limpiar el select de drivers
            $('#inputshipmentdriver').empty().append('<option value="">Choose an option</option>');
            return;
        }
    
        // Obtener la URL base desde el atributo data-url
        let driversRoute = $('#inputshipmentdriver').data('url'); 
        let fullUrl = `${driversRoute}/${selectedCarrierId}`; // Construir la URL completa
    
        $.ajax({
            url: fullUrl, // Usar la URL completa
            method: 'GET',
            success: function (data) {
                let select = $('#inputshipmentdriver');
                let selectedValue = select.val();
                select.empty();
    
                if (data.length === 0) {
                    select.append('<option disabled>No options available</option>');
                } else {
                    select.append('<option value="">Choose an option</option>');
                    data.forEach(item => {
                        select.append(`<option value="${item.pk_driver}">${item.drivername}</option>`);
                    });
                }
    
                if (selectedValue) {
                    select.val(selectedValue);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching drivers:', error);
            }
        });
    }*/
    
    // Cargar drivers cuando cambia el carrier seleccionado
    //$('#inputshipmentcarrier').on('change', LoadDrivers);
    
    // Cargar datos al enfocarse y al cargar la página update 
    /*$('#inputshipmentdriver').on('focus', LoadDrivers);
    LoadDrivers();*/

    //Funcion para buscar las Destinations en la pantalla de shipments
    /*function LoadCurrentStatus() {
        var locationsRoute = $('#inputshipmentcurrentstatus').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputshipmentcurrentstatus');
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
                    let prealertedFound = false;
                    data.forEach(item => {
                        select.append(`<option value="${item.gnct_id}">${item.gntc_description}</option>`);
                        // Si la descripción es "Prealerted", seleccionamos esa opción automáticamente
                        //if (item.gntc_description === 'Prealerted' && !prealertedFound) {
                        //  select.val(item.gnct_id); // Establece el valor de la opción como seleccionada
                        //   prealertedFound = true; // Marca que se encontró "Prealerted"
                        //}
                    });
                }
    
                // Restaura el valor seleccionado si existe
                if (selectedValue) {
                //if (selectedValue && !prealertedFound) {
                    select.val(selectedValue); // Si no se encontró "Prealerted", restaura el valor inicial
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data locations:', error);
            }
        });
    }*/
    

    //Funcion para buscar las Carriers en la pantalla de shipments
    /*function LoadSTMID() {
        var locationsRoute = $('#inputshipmentstmid').data('url');
        $.ajax({
                url: locationsRoute,
                type: 'GET',
                success: function (data) {
                    let select = $('#inputshipmentstmid');
                    let currentValue = select.val(); // Valor actual seleccionado por el usuario
                    let initialValue = select.attr('value'); // Valor inicial definido en el HTML
        
                    // Si no hay valor actual (por ejemplo, al cargar por primera vez), usa el inicial
                    let selectedValue = currentValue || initialValue;
        
                    select.empty(); // Limpia el contenido del select
        
                    // Agrega la opción deshabilitada y oculta solo si no hay valor seleccionado
                    //if (!selectedValue) {
                    //    select.append('<option selected disabled hidden></option>');
                    //}
        
                    if (data.length === 0) {
                        select.append('<option disabled>No options available</option>');
                    } else {
                        select.append('<option value="">Choose an option</option>');
                        data.forEach(item => {
                            select.append(`<option value="${item.id_service}">${item.id_service}</option>`);
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
    }*/
        
    // Ejecutar la función al enfocar el select y al cargar la página
    /*$('#inputshipmentstmid').on('focus', LoadSTMID);
    LoadSTMID();*/
});

    //Crear nuevo Shipment
    $(document).ready(function() {

        // Evento cuando se borra la selección con la "X"
        $('#inputshipmentsecuritycompany').on('select2:clear', function() {
            const field = $(this);
            const errorContainer = field.parent().find('.invalid-feedback');

            field.addClass('is-invalid'); // Agregar borde rojo
            field.next('.select2-container').find('.select2-selection').addClass('is-invalid');
            errorContainer.text('Security Company is required.'); // Mostrar mensaje de error
        });

        // Si selecciona un valor válido, eliminar error
        $('#inputshipmentsecuritycompany').on('select2:select', function() {
            const field = $(this);
            const errorContainer = field.parent().find('.invalid-feedback');

            field.removeClass('is-invalid'); // Quitar borde rojo
            field.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            errorContainer.text(''); // Borrar mensaje de error
        });

        let trackerCount = 0; // Contador de trackers visibles

        // Template HTML para los inputs con IDs diferenciados
        const trackerTemplate = (trackerNumber) => {
            return `
                <div class="mb-3 tracker-container" id="tracker-container-${trackerNumber}">
                    <label for="tracker${trackerNumber}" class="form-label">Shipment Tracker ${trackerNumber}</label>
                    <input type="text" class="form-control" id="tracker${trackerNumber}" name="tracker${trackerNumber}">
                    <div class="invalid-feedback"></div>
                </div>
            `;
        };

        // Añadir un tracker
        $('#addtrackers').on('click', function() {
            if (trackerCount < 3) {
                trackerCount++; // Incrementar el contador
                $('.trackers-container').append(trackerTemplate(trackerCount));

                // Si ya se han mostrado 3, deshabilitar el botón "Add Tracker"
                if (trackerCount === 3) {
                    $('#addtrackers').prop('disabled', true);
                }

                // Habilitar el botón "Remove Tracker" cuando haya al menos un tracker visible
                if (trackerCount > 0) {
                    $('#removetrackers').prop('disabled', false);
                }
            }
        });

        // Eliminar un tracker y limpiar su contenido
        $('#removetrackers').on('click', function() {
            if (trackerCount > 0) {
                // Obtener el input del último tracker antes de eliminarlo
                const inputField = $('#tracker' + trackerCount);
                
                // Limpiar el valor del input
                inputField.val('');

                // Remover clases de error si las tenía
                inputField.removeClass('is-invalid');
                inputField.next('.invalid-feedback').text('');

                // Eliminar el contenedor del tracker
                $('#tracker-container-' + trackerCount).remove();

                trackerCount--; // Decrementar el contador
            }

            // Habilitar el botón "Add Tracker" si se ocultaron todos los inputs
            if (trackerCount < 3) {
                $('#addtrackers').prop('disabled', false);
            }

            // Deshabilitar el botón "Remove Tracker" si no hay trackers visibles
            if (trackerCount === 0) {
                $('#removetrackers').prop('disabled', true);
            }
        });

        // Validación en vivo cuando el usuario interactúa con los campos
        $('#createnewshipmentform').on('input change', 'input, select', function() {
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
    
            /*if (fieldName === 'inputshipmentshipmenttype' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Shipment Type is required.');
            }*/

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
            }

            if (fieldName === 'inputshipmentsunits' && field.val().trim() === '0' || field.value <= 0) {
                field.addClass('is-invalid');
                errorContainer.text('Units must have a valid value.');
            }*/
                if (fieldName === 'inputshipmentsunits') {
                    const value = field.val().trim(); // Obtener el valor del campo
                
                    // Verificar si el campo está vacío
                    if (value.length === 0) {
                        field.addClass('is-invalid');
                        errorContainer.text('Units are required.');
                    }
                    // Verificar si el valor es 0 o menor que 0
                    else if (parseFloat(value) === 0 || parseFloat(value) <= 0) {
                        field.addClass('is-invalid');
                        errorContainer.text('Units must have a valid value.');
                    }
                    // Verificar si el valor es una letra (no un número)
                    else if (isNaN(value)) {
                        field.addClass('is-invalid');
                        errorContainer.text('The value must be an integer.');
                    }
                    else {
                        field.removeClass('is-invalid');
                        errorContainer.text('');
                    }
                }
                
                if (fieldName === 'inputpallets') {
                    const value = field.val().trim(); // Obtener el valor del campo
                
                    // Verificar si el campo está vacío
                    if (value.length === 0) {
                        field.addClass('is-invalid');
                        errorContainer.text('Pallets are required.');
                    }
                    // Verificar si el valor es 0 o menor que 0
                    else if (parseFloat(value) === 0 || parseFloat(value) <= 0) {
                        field.addClass('is-invalid');
                        errorContainer.text('Pallets must have a valid value.');
                    }
                    // Verificar si el valor es una letra (no un número)
                    else if (isNaN(value)) {
                        field.addClass('is-invalid');
                        errorContainer.text('The value must be an integer.');
                    }
                    else {
                        field.removeClass('is-invalid');
                        errorContainer.text('');
                    }
                }
                
            /*if (fieldName === 'inputpallets' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Pallets are required.');
            }

            if (fieldName === 'inputpallets' && field.val().trim() === '0' || field.value <= 0) {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets must have a valid value.');
            }
            if (fieldName === 'inputpallets' && field.value <= 0) {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets must have a valid value.');
            }*/
            // Validar que inputpallets no sea mayor que inputshipmentsunits
            if (fieldName === 'inputpallets') {
                let units = parseInt($('#inputshipmentsunits').val().trim(), 10); // Obtener el valor de inputshipmentsunits
                let pallets = parseInt(field.val().trim(), 10); // Obtener el valor de inputpallets

                if (!isNaN(units) && !isNaN(pallets) && pallets > units) {
                    field.addClass('is-invalid');
                    errorContainer.text('The number of pallets cannot be greater than the number of shipment units.');
                }
            }
            if (fieldName === 'tracker1' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Tracker one is required.');
            }
            if (fieldName === 'tracker2' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Tracker two is required.');
            }
            if (fieldName === 'tracker3' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Tracker three is required.');
            }
        });
    
        // Cuando el formulario se envía (al hacer clic en Save)
        $('#createnewshipmentform').submit(function(e) {
            e.preventDefault(); // Evita el envío del formulario

            let hasTracker = false;
            $('.trackers-container input').each(function() {
                if ($(this).val().trim().length > 0) {
                    hasTracker = true; // Si al menos uno tiene valor, marcar como verdadero
                }
            });

             // Si no hay trackers
            if (!hasTracker) {
                // Mostrar alerta de SweetAlert para confirmar si realmente no tiene tracker
                Swal.fire({
                    title: 'Shipment Trackers',
                    text: "The shipment does not have a tracker?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si responde que sí, se ejecuta el guardado del shipment
                        sendShipmentForm();  // Función que realiza el envío del formulario
                    } else {
                        // Si responde que no, se regresa al formulario sin guardar
                        // No hacer nada, el formulario no se enviará
                    }
                });
            } else {
                // Si hay trackers, continuar con el envío
                sendShipmentForm(); // Función que realiza el envío del formulario
            }
            function sendShipmentForm() {

                const saveButton = $('#saveButtonShipment');
                const url = saveButton.data('url'); // URL para la petición AJAX
                //let formData = new FormData(this);
                let formData = new FormData($('#createnewshipmentform')[0]);

                // Obtener todos los inputs de trackers dinámicos y agregarlos individualmente a formData
                $('.trackers-container input').each(function(index, input) {
                    formData.append(`tracker${index + 1}`, $(input).val()); // Se envía como tracker1, tracker2, etc.
                });
        
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
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirige directamente a la URL de tu vista
                                //window.location.href = '/whapptapproval';

                                // Restablece el formulario
                                $('#createnewshipmentform')[0].reset();

                                $('#inputshipmentcarrier, #inputshipmentdriver, #inputshipmenttrailer, #inputpallets, #inputidtrailer, #inputshipmentsecuritycompany').val(null).trigger('change');

                                // Elimina los inputs de trackers añadidos dinámicamente
                                $('.trackers-container').empty();  // Elimina todo el contenido dentro de .trackers-container
                                // Resetear el contador de trackers
                                trackerCount = 0;
                                // Habilitar nuevamente el botón "Add Tracker" si no hay trackers
                                $('#addtrackers').prop('disabled', false);

                                // También puedes eliminar cualquier clase de validación de error si es necesario
                                $('input, select').removeClass('is-invalid');
                                $('.invalid-feedback').text('');
                                $('.select2-selection').removeClass('is-invalid'); // También eliminar la clase del contenedor de select2
                            }
                        });
                        //$('#closenewtrailerregister').click();
                        //$('#refreshemptytrailertable').click();
                    },
                    error: function(xhr, status, error) {
                        // Limpia los errores anteriores
                        $('input, select').removeClass('is-invalid');
                        $('.invalid-feedback').text('');
                        $('.select2-selection').removeClass('is-invalid'); // También eliminar la clase del contenedor de select2
                        
                        // Manejo de errores con SweetAlert2
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            const fieldId = field;
                            const inputFieldselect = $('#' + field); // Convierte el ID en un objeto jQuery
                            const errorContainerselectsecuritycompany = inputFieldselect.parent().find('.invalid-feedback'); 
                            const inputField = $('#' + field);
                            const errorContainer = inputField.next('.invalid-feedback');
                            const fieldElement = document.getElementById(fieldId);
                            const isSelect2 = $(fieldElement).hasClass("searchsecuritycompany"); 
                            
                            inputField.addClass('is-invalid');  // Marca el campo con error
                            // Si es un select2, aplica la clase a la interfaz de select2
                            if (isSelect2) {
                                $(fieldElement).next('.select2-container').find('.select2-selection').addClass("is-invalid");
                                errorContainerselectsecuritycompany.text('Security Company is required.'); // Mostrar mensaje de error
                            }
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
            }
        });
    });

