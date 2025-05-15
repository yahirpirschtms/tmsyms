var table;  // Declara la variable de la tabla fuera de cualquier document.ready
var carriersData = null;
var locationsData = null;
var selectedCarriersUpdate = [];
var selectedLocationsUpdate = [];
var selectedCarriers = [];
var selectedLocations = []; // Arreglo para almacenar todos los drivers seleccionados
$(document).ready(function () {
    // Verifica si la tabla ya ha sido inicializada antes de inicializarla
    if (!$.fn.dataTable.isDataTable('#table_empty_trailers')) {
        table = $('#table_empty_trailers').DataTable({
            paging: false,  // Desactiva la paginación
            searching: true, // Mantiene la búsqueda activada
            info: false,     // Oculta la información
            lengthChange: false // Desactiva el cambio de cantidad de registros
        });
    } else {
        // Si la tabla ya está inicializada, se puede actualizar la configuración
        table.page.len(-1).draw();  // Muestra todos los registros sin paginación
    }

    $('#searchemptytrailergeneral').val(''); // Vaciar el input
    updateTrailerTable(); // Llamar a la función para actualizar la tabla
    
    function applycarriers(){
        var filterValues = $('#emptytrailerfilterinputcarriercheckbox').val()
        .split(',')
        .map(value => value.trim()) // Elimina espacios extra
        .filter(value => value !== '') // Elimina valores vacíos
        .join('|'); // Convierte la lista en una regex separada por "|"

        if (filterValues) {
            table.column(4).search(filterValues, true, false).draw(); // Busca con regex
        } else {
            table.column(4).search('').draw(); // Limpia el filtro si está vacío
        }
    }

    function applyindicators(){
        var filterValues = $('#emptytrailerfilterinputavailabilityindicatorcheckbox').val()
        .split(',')
        .map(value => value.trim()) // Elimina espacios extra
        .filter(value => value !== '') // Elimina valores vacíos
        .map(value => `^${value}$`) // Agrega los delimitadores ^ y $ para coincidencia exacta
        .join('|'); // Convierte la lista en una regex separada por "|"

        if (filterValues) {
            table.column(5).search(filterValues, true, false).draw(); // Busca con regex
        } else {
            table.column(5).search('').draw(); // Limpia el filtro si está vacío
        }
    }

    function applylocations(){
        var filterValues = $('#emptytrailerfilterinputlocationcheckbox').val()
        .split(',')
        .map(value => value.trim()) // Elimina espacios extra
        .filter(value => value !== '') // Elimina valores vacíos
        .map(value => `^${value}$`) // Agrega los delimitadores ^ y $ para coincidencia exacta
        .join('|'); // Convierte la lista en una regex separada por "|"

        if (filterValues) {
            table.column(6).search(filterValues, true, false).draw(); // Busca con regex
        } else {
            table.column(6).search('').draw(); // Limpia el filtro si está vacío
        }
    }

    //Esto es para los checkbotons de los filtros
    function setupCheckboxFilter(
        containerId, applyBtnId, closeBtnId, inputPkId, inputTextId,
        filterDivId, clearBtnId, updateBtnId, offcanvasElements
    ) {
        const updatetab = document.getElementById(updateBtnId);
        const $checkboxContainer = $(containerId);
        const $filterDiv = $(filterDivId);
        const $inputPk = $(inputPkId);
        const $inputText = $(inputTextId);
        const $applyButton = $(applyBtnId);
        const $closeButton = $(closeBtnId);
        const $offcanvas = $("#offcanvasaddmorefilters");
        const $clearButton = $(clearBtnId);
        const $offcanvasElements = $(offcanvasElements);

        // Detecta cambios en los checkboxes
        $checkboxContainer.on("change", "input[type='checkbox']", function () {
            const anyChecked = $checkboxContainer.find("input[type='checkbox']:checked").length > 0;
            $closeButton.prop("disabled", anyChecked);
        });

        // Botón Apply: recoge los valores seleccionados
        $applyButton.on("click", function () {
            let selectedValues = [];
            let selectedIDs = [];

            $checkboxContainer.find("input[type='checkbox']:checked").each(function () {
                selectedIDs.push($(this).val());  // Guarda el ID
                selectedValues.push($(this).next("label").text().trim()); // Guarda el nombre
            });

            if (selectedValues.length > 0) {
                $filterDiv.show();
                $inputPk.val(selectedIDs.join(",")); // IDs separados por coma
                $inputText.val(selectedValues.join(", ")); // Nombres separados por coma
                //debe de ir descomentado
                //updatetab.click();
                applycarriers();
                applyindicators();
                applylocations();
            } else {
                $filterDiv.hide();
                $inputPk.val("");
                $inputText.val("");
                $closeButton.click();
                //debe de ir descomentado
                //updatetab.click();
                applycarriers();
                applyindicators();
                applylocations();
            }
        });

        // Botón Close
        $closeButton.on("click", function () {
            if (!$filterDiv.is(":visible")) return;

            if ($inputText.val() !== "" || $inputPk.val() !== "") {
                $inputText.val("");
                $inputPk.val("");
                //updatetab.click();
                applycarriers();
                applyindicators();
                applylocations();
            }
            applycarriers();
            applyindicators();
            applylocations();
            $filterDiv.hide();
            //debe de ir descomentado
            //updatetab.click();
        });

        // Abrir el offcanvas al hacer clic en el input
        $offcanvasElements.on("click", function () {
            if ($filterDiv.is(":visible")) {
                $offcanvas.offcanvas("show");
            }
        });

        // Botón de limpiar
        $clearButton.on("click", function () {
            if ($filterDiv.is(":visible") && ($inputText.val() !== "" || $inputPk.val() !== "")) {
                $inputText.val("");
                $inputPk.val("");
                $checkboxContainer.find("input[type='checkbox']").prop("checked", false);
                $filterDiv.hide();
                $closeButton.prop("disabled", false);
                $closeButton.click();
                applycarriers();
                applyindicators();
                applylocations();
                //debe de ir descomentado
                //updatetab.click();
            }
        });
    }

    // Inicializamos la función para cada set de checkboxes
    setupCheckboxFilter(
        "#AvailabilityIndicatorCheckboxContainer",
        "#applyaifiltercheckbox",
        "#closeapplyaifiltercheckbox",
        "#emptytrailerfilterinputavailabilityindicatorcheckboxpk",
        "#emptytrailerfilterinputavailabilityindicatorcheckbox",
        "#emptytrailerfilterdivavailabilityindicatorcheckbox",
        "#emptytrailerfilterbuttonavailabilityindicatorcheckbox",
        "refreshemptytrailertable",
        "#emptytrailerfilterinputavailabilityindicatorcheckbox, #emptytrailerfilterbtnavailabilityindicatorcheckbox"
    );

    setupCheckboxFilter(
        "#CarrierCheckboxContainer",
        "#applycarrierfiltercheckbox",
        "#closeapplycarrierfiltercheckbox",
        "#emptytrailerfilterinputcarriercheckboxpk",
        "#emptytrailerfilterinputcarriercheckbox",
        "#emptytrailerfilterdivcarriercheckbox",
        "#emptytrailerfilterbuttoncarriercheckbox",
        "refreshemptytrailertable",
        "#emptytrailerfilterinputcarriercheckbox, #emptytrailerfilterbtncarriercheckbox"
    );

    setupCheckboxFilter(
        "#locationCheckboxContainer",
        "#applylocationfiltercheckbox",
        "#closeapplylocationfiltercheckbox",
        "#emptytrailerfilterinputlocationcheckboxpk",
        "#emptytrailerfilterinputlocationcheckbox",
        "#emptytrailerfilterdivlocationcheckbox",
        "#emptytrailerfilterbuttonlocationcheckbox",
        "refreshemptytrailertable",
        "#emptytrailerfilterinputlocationcheckbox, #emptytrailerfilterbtnlocationcheckbox"
    );
});

//Esto es para los checkbotons de los filtros
$(document).ready(function () {
    
    // Obtenemos los elementos
    const updatetab = document.getElementById("refreshemptytrailertable");
    const $checkboxContainer = $("#locationCheckboxContainer");
    const $filterDiv = $("#emptytrailerfilterdivlocationcheckbox");
    const $inputPk = $("#emptytrailerfilterinputlocationcheckboxpk");
    const $inputLocation = $("#emptytrailerfilterinputlocationcheckbox");
    const $applyButton = $("#applylocationfiltercheckbox");
    const $closeButton = $("#closeapplylocationfiltercheckbox");
    const $offcanvas = $("#offcanvasaddmorefilters");
    const $clearButton = $("#emptytrailerfilterbuttonlocationcheckbox");

    // Variable que guarda los elementos que abrirán el offcanvas
    const openOffcanvasElements = $("#emptytrailerfilterinputlocationcheckbox, #emptytrailerfilterbtnlocationcheckbox");

    // Escuchamos el cambio de checkboxes
    $checkboxContainer.on("change", "input[type='checkbox']", function () {
        const anyChecked = $checkboxContainer.find("input[type='checkbox']:checked").length > 0;
        $closeButton.prop("disabled", anyChecked);
    });

    // Escuchamos el click del botón Apply
    $applyButton.on("click", function () {
        let selectedLocations = [];
        let selectedIDs = [];

        // Recorrer todos los checkboxes seleccionados
        $checkboxContainer.find("input[type='checkbox']:checked").each(function () {
            selectedIDs.push($(this).val());  // Guardar el ID (pk_company)
            selectedLocations.push($(this).next("label").text().trim()); // Guardar el nombre (CoName)
        });

        if (selectedLocations.length > 0) {
            $filterDiv.show(); // Mostrar el div
            $inputPk.val(selectedIDs.join(",")); // Guardar IDs
            $inputLocation.val(selectedLocations.join(", ")); // Guardar nombres
            updatetab.click();
        } else {
            $filterDiv.hide();
            $inputPk.val("");
            $inputLocation.val("");
            $closeButton.click();
            updatetab.click();
        }
    });

    // Escuchamos el clic en el botón Close
    $closeButton.on("click", function () {
        if (!$filterDiv.is(":visible")) return;

        // Limpiar los inputs si hay datos seleccionados
        if ($inputLocation.val() !== "" || $inputPk.val() !== "") {
            $inputLocation.val("");
            $inputPk.val("");
            updatetab.click();
        }

        // Ocultar el div si está visible
        $filterDiv.hide();
        updatetab.click();
    });

    // Escuchar clic en los elementos que abrirán el offcanvas
    openOffcanvasElements.on("click", function () {
        if ($filterDiv.is(":visible")) {
            $offcanvas.offcanvas("show"); // Abrir el offcanvas
        }
    });

    // Escuchar clic en el botón de limpiar (vaciar inputs y ocultar el div)
    $clearButton.on("click", function () {
        if ($filterDiv.is(":visible") && ($inputLocation.val() !== "" || $inputPk.val() !== "")) {
            // Limpiar los inputs y desmarcar los checkboxes
            $inputLocation.val("");
            $inputPk.val("");
            $checkboxContainer.find("input[type='checkbox']").prop("checked", false);
            $filterDiv.hide();
            $closeButton.prop("disabled", false);
            $closeButton.click();
            updatetab.click();
        }
    });

});

$(document).ready(function () {

    function loadCheckboxFilters(){
        $.ajax({
            url: 'loadCheckBoxfiltersEmptyTrailer',
            type: 'GET',
            success: function (data) {
                let containerCarrier = $('#CarrierCheckboxContainer');
                containerCarrier.empty();  // Limpiar cualquier contenido previo
    
                if (data.carriers.length === 0) {
                    containerCarrier.append('<p>No options available</p>');
                } else {
                    data.carriers.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        containerCarrier.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.pk_company}" id="carriers${item.pk_company}">
                                <label class="form-check-label" for="carriers${item.pk_company}">
                                    ${item.CoName}
                                </label>
                            </div>
                        `);
                    });
                }


                let containerLocation = $('#locationCheckboxContainer');
                containerLocation.empty();  // Limpiar cualquier contenido previo
    
                if (data.locations.length === 0) {
                    containerLocation.append('<p>No options available</p>');
                } else {
                    data.locations.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        containerLocation.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.pk_company}" id="location${item.pk_company}">
                                <label class="form-check-label" for="location${item.pk_company}">
                                    ${item.CoName}
                                </label>
                            </div>
                        `);
                    });
                }

                let containerAvailability = $('#AvailabilityIndicatorCheckboxContainer');
                containerAvailability.empty();  // Limpiar cualquier contenido previo
    
                if (data.availability_indicator.length === 0) {
                    containerAvailability.append('<p>No options available</p>');
                } else {
                    data.availability_indicator.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        containerAvailability.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.gnct_id}" id="availabilityIndicators${item.gnct_id}">
                                <label class="form-check-label" for="availabilityIndicators${item.gnct_id}">
                                    ${item.gntc_value}
                                </label>
                            </div>
                        `);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data checkboxfilters:', error);
            }
        });
    }
    //Cargar los Carriers en los filtros de los checkbox
    function loadAvailabilityIndicatorsFilterCheckbox() { 
        //console.log("sikeeeee")
        var locationsRoute = $('#multiCollapseapplyaifiltercheckbox').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let container = $('#AvailabilityIndicatorCheckboxContainer');
                container.empty();  // Limpiar cualquier contenido previo
    
                if (data.length === 0) {
                    container.append('<p>No options available</p>');
                } else {
                    data.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        container.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.gnct_id}" id="availabilityIndicators${item.gnct_id}">
                                <label class="form-check-label" for="availabilityIndicators${item.gnct_id}">
                                    ${item.gntc_description}
                                </label>
                            </div>
                        `);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data Availability Indicators:', error);
            }
        });
    }
    
    // Ejecutar la función cuando el panel de filtros se haya expandido
    /*$('#addmorefiltersemptytrailer').one('click', function () {
        loadAvailabilityIndicatorsFilterCheckbox();
        loadCarriersFilterCheckbox();
        loadLocationsFilterCheckbox();
    });*/
    //loadAvailabilityIndicatorsFilterCheckbox();

    //Cargar los Carriers en los filtros de los checkbox
    function loadCarriersFilterCheckbox() { 
        //console.log("sikeeeee")
        var locationsRoute = $('#multiCollapseapplycarrierfiltercheckbox').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let container = $('#CarrierCheckboxContainer');
                container.empty();  // Limpiar cualquier contenido previo
    
                if (data.length === 0) {
                    container.append('<p>No options available</p>');
                } else {
                    data.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        container.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.pk_company}" id="carriers${item.pk_company}">
                                <label class="form-check-label" for="carriers${item.pk_company}">
                                    ${item.CoName}
                                </label>
                            </div>
                        `);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data Carriers:', error);
            }
        });
    }
    
    function loadLocationsFilterCheckbox() { 
        var locationsRoute = $('#multiCollapseapplylocationfiltercheckbox').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let container = $('#locationCheckboxContainer');
                container.empty();  // Limpiar cualquier contenido previo
    
                if (data.length === 0) {
                    container.append('<p>No options available</p>');
                } else {
                    data.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        container.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.pk_company}" id="location${item.pk_company}">
                                <label class="form-check-label" for="location${item.pk_company}">
                                    ${item.CoName}
                                </label>
                            </div>
                        `);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data locations:', error);
            }
        });
    }
    
    // Ejecutar la función cuando el panel de filtros se haya expandido
    /*$('#closeapplylocationfiltercheckbox').on('click', function () {
        loadLocationsFilterCheckbox();
    });*/
    //loadLocationsFilterCheckbox();


    //nueva funcion para cargar los nuevos catalogos
    var isCatalogsLoaded = false; // Bandera para controlar la carga
    loadGeneralCatalogs();
    function loadGeneralCatalogs(){
        if (isCatalogsLoaded) return; // Evita cargar dos veces

        $.ajax({
            url:'generalCatalogs',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                carriersData = data.carriers.map(item => ({
                    id: item.pk_company,
                    text: item.CoName
                }));
                data.carriers.forEach(function (carrier) {
                    if (!selectedCarriers.includes(carrier.CoName)) {
                        selectedCarriers.push(carrier.CoName); // Agregar al arreglo si no está ya
                    }
                });
                selectedCarriersUpdate = [...selectedCarriers]; 
                console.log("Carriers cargados desde la base de datos:", selectedCarriers);
                console.log("Carriers copiados a selectedCarriersUpdate:", selectedCarriersUpdate);
    
                // Inicializar Select2 sin AJAX
                $('#inputcarrier').select2({
                    placeholder: 'Select a Carrier',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: carriersData, // Pasar los datos directamente
                    dropdownParent: $('#newtrailerempty'),
                    minimumInputLength: 0
                });

                $('#updateinputcarrier').select2({
                    placeholder: 'Select a Carrier',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: carriersData, // Pasar los datos directamente
                    dropdownParent: $('#updatenewtrailerempty'),
                    minimumInputLength: 0
                });
                //Cargar los locations
                locationsData = data.locations.map(item => ({
                        id: item.pk_company,
                        text: item.CoName
                }));
                data.locations.forEach(function (location) {
                    if (!selectedLocations.includes(location.CoName)) {
                        selectedLocations.push(location.CoName); // Agregar al arreglo si no está ya
                    }
                });
                selectedLocationsUpdate = [...selectedLocations]; 
                console.log("Locations cargados desde la base de datos:", selectedLocations);
                console.log("Locations copiados a selectedLocationsUpdate:", selectedLocationsUpdate);

                // Inicializar Select2 sin AJAX
                $('#inputlocation').select2({
                    placeholder: 'Select a Location',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: locationsData, // Pasar los datos directamente
                    dropdownParent: $('#newtrailerempty'),
                    minimumInputLength: 0
                });
                    
                $('#updateinputlocation').select2({
                    placeholder: 'Select a Location',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: locationsData, // Pasar los datos directamente
                    dropdownParent: $('#updatenewtrailerempty'),
                    minimumInputLength: 0
                });

                //Cargar filtros 
                let containerCarrier = $('#CarrierCheckboxContainer');
                containerCarrier.empty();  // Limpiar cualquier contenido previo
    
                if (data.carriers.length === 0) {
                    containerCarrier.append('<p>No options available</p>');
                } else {
                    data.carriers.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        containerCarrier.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.pk_company}" id="carriers${item.pk_company}">
                                <label class="form-check-label" for="carriers${item.pk_company}">
                                    ${item.CoName}
                                </label>
                            </div>
                        `);
                    });
                }


                let containerLocation = $('#locationCheckboxContainer');
                containerLocation.empty();  // Limpiar cualquier contenido previo
    
                if (data.locations.length === 0) {
                    containerLocation.append('<p>No options available</p>');
                } else {
                    data.locations.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        containerLocation.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.pk_company}" id="location${item.pk_company}">
                                <label class="form-check-label" for="location${item.pk_company}">
                                    ${item.CoName}
                                </label>
                            </div>
                        `);
                    });
                }

                let containerAvailability = $('#AvailabilityIndicatorCheckboxContainer');
                containerAvailability.empty();  // Limpiar cualquier contenido previo
    
                if (data.availability_indicator.length === 0) {
                    containerAvailability.append('<p>No options available</p>');
                } else {
                    data.availability_indicator.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        containerAvailability.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.gnct_id}" id="availabilityIndicators${item.gnct_id}">
                                <label class="form-check-label" for="availabilityIndicators${item.gnct_id}">
                                    ${item.gntc_value}
                                </label>
                            </div>
                        `);
                    });
                }

                let selectAvailability = $('#inputavailabilityindicator');
                let selectedValueAvailability = selectAvailability.val();
                selectAvailability.empty(); // Limpia el select eliminando todas las opciones
                if (data.availability_indicator.length === 0) {
                    selectAvailability.append('<option disabled>No options available</option>');
                } else {
                    selectAvailability.append('<option value="">Choose an option</option>');
                    data.availability_indicator.forEach(item => {
                      selectAvailability.append(`<option value="${item.gnct_id}">${item.gntc_value}</option>`);
                    });
                }
    
                if (selectedValueAvailability) {
                    selectAvailability.val(selectedValueAvailability); // Restaura el valor anterior
                }

                let selectAvailabilityUpdate = $('#updateinputavailabilityindicator');
                let selectedValueAvailabilityUpdate = selectAvailabilityUpdate.val();
                selectAvailabilityUpdate.empty(); // Limpia el select eliminando todas las opciones
                if (data.availability_indicator.length === 0) {
                    selectAvailabilityUpdate.append('<option disabled>No options available</option>');
                } else {
                      selectAvailabilityUpdate.append('<option value="">Choose an option</option>');
                      data.availability_indicator.forEach(item => {
                          selectAvailabilityUpdate.append(`<option value="${item.gnct_id}">${item.gntc_value}</option>`);
                      });
                }
    
                if (selectedValueAvailabilityUpdate) {
                    selectAvailabilityUpdate.val(selectedValueAvailabilityUpdate); // Restaura el valor anterior
                }

                isCatalogsLoaded = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los carriers:', error);
            }
        });
    }

    //Busqueda de carries en un nuevo registro
    var carrierRoute = $('#inputcarrier').data('url');
    var newlyCreatedCarrierId = null; // Variable para almacenar el ID del carrier recién creado
    var isCarriersLoaded = false; // Bandera para controlar la carga
    var newlyCreatedLocationId = null; // Variable para almacenar el ID del carrier recién creado

    //loadCarriersOnce();

    function loadCarriersOnce() {
        if (isCarriersLoaded) return; // Evita cargar dos veces
    
        $.ajax({
            url:'/carrier-emptytrailerAjax',
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
                selectedCarriersUpdate = [...selectedCarriers]; 
                console.log("Carriers cargados desde la base de datos:", selectedCarriers);
                console.log("Carriers copiados a selectedCarriersUpdate:", selectedCarriersUpdate);
    
                // Inicializar Select2 sin AJAX
                $('#inputcarrier').select2({
                    placeholder: 'Select a Carrier',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: carriersData, // Pasar los datos directamente
                    dropdownParent: $('#newtrailerempty'),
                    minimumInputLength: 0
                });

                $('#updateinputcarrier').select2({
                    placeholder: 'Select a Carrier',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: carriersData, // Pasar los datos directamente
                    dropdownParent: $('#updatenewtrailerempty'),
                    minimumInputLength: 0
                });
                isCarriersLoaded = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los catalogos:', error);
            }
        });
    }

    /*function loadCarriers() {
        $('#inputcarrier').select2({
            placeholder: 'Select or enter a New Carrier',
            //allowClear: true,
            tags: true, // Permite agregar nuevas opciones
            dropdownParent: $('#newtrailerempty'),
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
    }*/

    /*loadCarriers();
    $.ajax({
        url: '/carrier-emptytrailerAjax',  // Ruta que manejará la carga de los drivers existentes
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (carrier) {
                if (!selectedCarriers.includes(carrier.CoName)) {
                    selectedCarriers.push(carrier.CoName); // Agregar al arreglo si no está ya
                }
            });
            console.log("Carriers Registro cargados desde la base de datos:", selectedCarriers);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar los Carriers Registro existentes:', error);
        }
    });*/

    // Actualizar la lista cuando se haga clic en el select
    /*$('#inputcarrier').one('click', function () {
        loadCarriers();
    });*/

    // Cuando el usuario seleccione o ingrese un nuevo valor
    $('#inputcarrier').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada

        // Si no es el nuevo carrier, lo procesamos
        if (selectedText  !== newlyCreatedCarrierId &&  selectedText.trim() !== '') {
            console.log(selectedText);
            //saveNewCarrier(selectedText);
            if (!selectedCarriers.includes(selectedText) || !selectedCarriersUpdate.includes(selectedText) || !selectedLocations.includes(selectedText) || !selectedLocationsUpdate.includes(selectedText)) {
                if(!selectedCarriers.includes(selectedText)){
                    selectedCarriers.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedCarriers);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                if(!selectedCarriersUpdate.includes(selectedText)){
                    selectedCarriersUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedCarriersUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                if(!selectedLocations.includes(selectedText)){
                        selectedLocations.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedLocations);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                if(!selectedLocationsUpdate.includes(selectedText)){
                    selectedLocationsUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedLocationsUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                saveNewCarrier(selectedText);
            }
        }
    });

    // Guardar un nuevo carrier en la base de datos
    function saveNewCarrier(carrierName) {
        $.ajax({
            url: '/save-new-carrier',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                carrierName: carrierName,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);

                // Crear una nueva opción para cada select2
                var newOption1 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                var newOption2 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                var newOption3 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);
                var newOption4 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);

                // Agregar la opción a ambos select2 sin eliminarla del otro
                $('#updateinputcarrier').append(newOption1).trigger('change');
                $('#inputcarrier').append(newOption2).trigger('change');
                $('#inputlocation').append(newOption3);
                $('#updateinputlocation').append(newOption4);
                
                // Seleccionar automáticamente el nuevo carrier
                $('#inputcarrier').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedCarrierId = response.newCarrier.CoName;

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#inputcarrier').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedCarrierId) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedCarrierId = null;  // Restablecer el ID del carrier creado
                    }
                });
                loadCheckboxFilters();
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el carrier', error);
            }
        });
    }



    var carrierRouteUpdate = $('#updateinputcarrier').data('url');
    var newlyCreatedCarrierIdUpdate = null; // Variable para almacenar el ID del carrier recién creado
    //var selectedCarriersUpdate = []; // Arreglo para almacenar todos los drivers seleccionados

    /*function loadCarriersUpdate() {
        $('#updateinputcarrier').select2({
            placeholder: 'Select or enter a New Carrier',
            //allowClear: true,
            tags: true, // Permite agregar nuevas opciones
            dropdownParent: $('#updatenewtrailerempty'),
            ajax: {
                url: carrierRouteUpdate,
                dataType: 'json',
                delay: 450,
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
    }*/

    //loadCarriersUpdate();

    /*$.ajax({
        url: '/carrier-emptytrailerAjax',  // Ruta que manejará la carga de los drivers existentes
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (carrier) {
                if (!selectedCarriersUpdate.includes(carrier.CoName)) {
                    selectedCarriersUpdate.push(carrier.CoName); // Agregar al arreglo si no está ya
                }
            });
            console.log("Carriers Update cargados desde la base de datos:", selectedCarriersUpdate);
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar los Carriers Update existentes:', error);
        }
    });*/

    // Actualizar la lista cuando se haga clic en el select
    /*$('#updateinputcarrier').on('click', function () {
        loadCarriersUpdate();
    });*/
    //$('#updateemptytrailer').one('click', loadCarriersUpdate);

    // Cuando el usuario seleccione o ingrese un nuevo valor
    $('#updateinputcarrier').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada

        // Si no es el nuevo carrier, lo procesamos
        if (selectedText  !== newlyCreatedCarrierIdUpdate &&  selectedText.trim() !== '') {
            console.log(selectedText);
            //saveNewCarrier(selectedText);
            /*if (!selectedCarriersUpdate.includes(selectedText)) {
                selectedCarriersUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                console.log(selectedCarriersUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                saveNewCarrierUpdate(selectedText);
            }*/
            if (!selectedCarriers.includes(selectedText) || !selectedCarriersUpdate.includes(selectedText) || !selectedLocations.includes(selectedText) || !selectedLocationsUpdate.includes(selectedText)) {
                if(!selectedCarriers.includes(selectedText)){
                    selectedCarriers.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedCarriers);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                if(!selectedCarriersUpdate.includes(selectedText)){
                    selectedCarriersUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedCarriersUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                if(!selectedLocations.includes(selectedText)){
                        selectedLocations.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedLocations);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                if(!selectedLocationsUpdate.includes(selectedText)){
                    selectedLocationsUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedLocationsUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                saveNewCarrierUpdate(selectedText);
            }
        }
    });

    // Guardar un nuevo carrier en la base de datos
    function saveNewCarrierUpdate(carrierName) {
        $.ajax({
            url: '/save-new-carrier',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                carrierName: carrierName,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);
                // Crear una nueva opción para cada select2 (evita que se mueva de un select a otro)
                var newOption1 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                var newOption2 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                var newOption3 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);
                var newOption4 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);

                // Agregar la opción a ambos select2
                $('#updateinputcarrier').append(newOption1).trigger('change');
                $('#inputcarrier').append(newOption2).trigger('change');
                $('#inputlocation').append(newOption3);
                $('#updateinputlocation').append(newOption4);

                // Seleccionar automáticamente el nuevo carrier en ambos select2
                $('#updateinputcarrier').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedCarrierIdUpdate = response.newCarrier.CoName;

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#updateinputcarrier').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedCarrierIdUpdate) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedCarrierIdUpdate = null;  // Restablecer el ID del carrier creado
                    }
                });
                loadCheckboxFilters();
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el carrier', error);
            }
        });
    }


        
    
        // Cargar datos al enfocarse y al cargar la página update 
        /*$(document).one('click', '.clickable-row', function () {
            loadAvailabilityIndicatorupdate();
            //loadCarriersUpdate();
        });*/

        //loadAvailabilityIndicatorupdate();
        // Cargar datos al enfocarse y al cargar la página update 
        //$('#refreshemptytrailertable').on('click', loadAvailabilityIndicatorupdate);


    
        // Cargar datos al enfocarse y al cargar la página update 
        //$('#addnewemptytrailer').one('click', loadAvailabilityIndicator);
        
        $('#addnewemptytrailer').on('click', function () {
            $('#inputcarrier').val(null).trigger('change'); // Restablecer el select2

            // Quitar clases de error
            $('#inputcarrier').removeClass('is-invalid'); 
            $('#inputcarrier').next('.select2-container').find('.select2-selection').removeClass('is-invalid');

            // Borrar mensaje de error
            $('#inputcarrier').parent().find('.invalid-feedback').text('');

            $('#inputlocation').val(null).trigger('change'); // Restablecer el select2

            // Quitar clases de error
            $('#inputlocation').removeClass('is-invalid'); 
            $('#inputlocation').next('.select2-container').find('.select2-selection').removeClass('is-invalid');

            // Borrar mensaje de error
            $('#inputlocation').parent().find('.invalid-feedback').text('');
        });

        $('#inputlocation').on('change', function () {
            var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
            var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada

            // Si no es el nuevo carrier, lo procesamos
            if (selectedText  !== newlyCreatedLocationId &&  selectedText.trim() !== '') {
                console.log(selectedText);
                //saveNewCarrier(selectedText);
                if (!selectedLocations.includes(selectedText) || !selectedLocationsUpdate.includes(selectedText) || !selectedCarriers.includes(selectedText) || !selectedCarriersUpdate.includes(selectedText)) {
                    if(!selectedLocations.includes(selectedText)){
                        selectedLocations.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedLocations);  // Mostrar el arreglo con todos los drivers seleccionados
                    }
                    if(!selectedLocationsUpdate.includes(selectedText)){
                        selectedLocationsUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedLocationsUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                    }
                    if(!selectedCarriers.includes(selectedText)){
                        selectedCarriers.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedCarriers);  // Mostrar el arreglo con todos los drivers seleccionados
                    }
                    if(!selectedCarriersUpdate.includes(selectedText)){
                        selectedCarriersUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedCarriersUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                    }
                    saveNewLocation(selectedText);
                }
            }
        });

        // Guardar un nuevo carrier en la base de datos
        function saveNewLocation(carrierName) {
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
                    // Crear una nueva opción para cada select2
                    var newOption1 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                    var newOption2 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                    var newOption3 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);
                    var newOption4 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);

                    // Agregar la opción a ambos select2 sin eliminarla del otro
                    $('#updateinputlocation').append(newOption1).trigger('change');
                    $('#inputlocation').append(newOption2).trigger('change');
                    $('#inputcarrier').append(newOption3);
                    $('#updateinputcarrier').append(newOption4);

                    // Seleccionar el nuevo carrier automáticamente
                    $('#inputlocation').val(response.newCarrier.pk_company).trigger('change');

                    // Marcar el nuevo ID para evitar que se haga otra solicitud
                    newlyCreatedLocationId = response.newCarrier.CoName;

                    // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                    $('#inputlocation').on('select2:select', function (e) {
                        var selectedId = e.params.data.id;
                        if (selectedId === newlyCreatedLocationId) {
                            // Evitar que se reenvíe la solicitud para el nuevo carrier
                            newlyCreatedLocationId = null;  // Restablecer el ID del carrier creado
                        }
                    });
                    loadCheckboxFilters();
                },
                error: function (xhr, status, error) {
                    console.error('Error al guardar la Location', error);
                }
            });
        }

        var newlyCreatedLocationIdUpdate = null;

        $('#updateinputlocation').on('change', function () {
            var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
            var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada

            // Si no es el nuevo carrier, lo procesamos
            if (selectedText  !== newlyCreatedLocationIdUpdate &&  selectedText.trim() !== '') {
                console.log(selectedText);
                //saveNewCarrier(selectedText);
                if (!selectedLocations.includes(selectedText) || !selectedLocationsUpdate.includes(selectedText) || !selectedCarriers.includes(selectedText) || !selectedCarriersUpdate.includes(selectedText)) {
                    if(!selectedLocations.includes(selectedText)){
                        selectedLocations.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedLocations);  // Mostrar el arreglo con todos los drivers seleccionados
                    }
                    if(!selectedLocationsUpdate.includes(selectedText)){
                        selectedLocationsUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedLocationsUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                    }
                    if(!selectedCarriers.includes(selectedText)){
                        selectedCarriers.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedCarriers);  // Mostrar el arreglo con todos los drivers seleccionados
                    }
                    if(!selectedCarriersUpdate.includes(selectedText)){
                        selectedCarriersUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                        console.log(selectedCarriersUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                    }
                    saveNewlocationUpdate(selectedText);
                }
            }
        });

        function saveNewlocationUpdate(carrierName) {
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
                    var newOption1 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                    var newOption2 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                    var newOption3 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);
                    var newOption4 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, false, false);

                    // Agregar la opción a ambos select2
                    $('#updateinputlocation').append(newOption1).trigger('change');
                    $('#inputlocation').append(newOption2).trigger('change');
                    $('#inputcarrier').append(newOption3);
                    $('#updateinputcarrier').append(newOption4);

                    // Seleccionar el nuevo carrier automáticamente
                    $('#updateinputlocation').val(response.newCarrier.pk_company).trigger('change');

                    // Marcar el nuevo ID para evitar que se haga otra solicitud
                    newlyCreatedLocationIdUpdate = response.newCarrier.CoName;

                    // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                    $('#updateinputlocation').on('select2:select', function (e) {
                        var selectedId = e.params.data.id;
                        if (selectedId === newlyCreatedLocationIdUpdate) {
                            // Evitar que se reenvíe la solicitud para el nuevo carrier
                            newlyCreatedLocationIdUpdate = null;  // Restablecer el ID del carrier creado
                        }
                    });
                    loadCheckboxFilters();
                },
                error: function (xhr, status, error) {
                    console.error('Error al guardar el Location', error);
                }
            });
        }
});

        //Funcion para buscar el availability indicator en la pantalla de empty trailer update
        /*function loadAvailabilityIndicator() {
            var availabilityRoute = $('#inputavailabilityindicator').data('url');
              $.ajax({
                  url: availabilityRoute,
                  method: 'GET',
                  success: function (data) {
                      let select = $('#inputavailabilityindicator');
                      let selectedValue = select.val();
                      //let selectedValue = "{{ old('inputavailabilityindicator') }}"; // Recupera el valor previo
                      select.empty(); // Limpia el select eliminando todas las opciones
                      //select.append('<option selected disabled hidden></option>'); // Opción inicial
    
                      if (data.length === 0) {
                          select.append('<option disabled>No options available</option>');
                      } else {
                            select.append('<option value="">Choose an option</option>');
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
        }*/

        


//Busqueda de las location en el nuevo registros de los empty trailers 
//Busqueda de Location en un nuevo registro
$(document).ready(function () {
    var locationsRoute = $('#inputlocation').data('url');
    var newlyCreatedCarrierId = null; // Variable para almacenar el ID del carrier recién creado
    
    var isLocationsLoaded = false;

    //loadLocationsOnce();

    function loadLocationsOnce() {
        if(isLocationsLoaded) return;

        $.ajax({
                url: locationsRoute,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var locationsData = data.map(item => ({
                        id: item.pk_company,
                        text: item.CoName
                    }));
                    data.forEach(function (location) {
                        if (!selectedLocations.includes(location.CoName)) {
                            selectedLocations.push(location.CoName); // Agregar al arreglo si no está ya
                        }
                    });
                    selectedLocationsUpdate = [...selectedLocations]; 
                    console.log("Locations cargados desde la base de datos:", selectedLocations);
                    console.log("Locations copiados a selectedLocationsUpdate:", selectedLocationsUpdate);

                    // Inicializar Select2 sin AJAX
                    $('#inputlocation').select2({
                        placeholder: 'Select a Location',
                        allowClear: true,
                        tags: false, // Permite agregar nuevas opciones
                        data: locationsData, // Pasar los datos directamente
                        dropdownParent: $('#newtrailerempty'),
                        minimumInputLength: 0
                    });
                    

                    $('#updateinputlocation').select2({
                        placeholder: 'Select a Location',
                        allowClear: true,
                        tags: false, // Permite agregar nuevas opciones
                        data: locationsData, // Pasar los datos directamente
                        dropdownParent: $('#updatenewtrailerempty'),
                        minimumInputLength: 0
                    });

                    isLocationsLoaded = true;
                },
                error: function (xhr, status, error) {
                    console.error('Error al cargar los locations:', error);
                }
        });
    }

    // Cuando el usuario seleccione o ingrese un nuevo valor
    /*$('#inputlocation').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada

        // Si no es el nuevo carrier, lo procesamos
        if (selectedText  !== newlyCreatedCarrierId &&  selectedText.trim() !== '') {
            console.log(selectedText);
            //saveNewCarrier(selectedText);
            if (!selectedLocations.includes(selectedText) || !selectedLocationsUpdate.includes(selectedText)) {
                if(!selectedLocations.includes(selectedText)){
                    selectedLocations.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedLocations);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                if(!selectedLocationsUpdate.includes(selectedText)){
                    selectedLocationsUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedLocationsUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                saveNewCarrier(selectedText);
            }
        }
    });*/

    // Guardar un nuevo carrier en la base de datos
    /*function saveNewCarrier(carrierName) {
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
                // Crear una nueva opción para cada select2
                var newOption1 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                var newOption2 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);

                // Agregar la opción a ambos select2 sin eliminarla del otro
                $('#updateinputlocation').append(newOption1).trigger('change');
                $('#inputlocation').append(newOption2).trigger('change');

                // Seleccionar el nuevo carrier automáticamente
                $('#inputlocation').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedCarrierId = response.newCarrier.CoName;

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#inputlocation').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedCarrierId) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedCarrierId = null;  // Restablecer el ID del carrier creado
                    }
                });
                loadLocationsFilterCheckbox();
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar la Location', error);
            }
        });
    }*/
    var newlyCreatedCarrierIdUpdate = null;

    /*$('#updateinputlocation').on('change', function () {
        var selectedOption = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedText = selectedOption ? selectedOption.text : ''; // Obtener el texto (nombre) de la opción seleccionada

        // Si no es el nuevo carrier, lo procesamos
        if (selectedText  !== newlyCreatedCarrierIdUpdate &&  selectedText.trim() !== '') {
            console.log(selectedText);
            //saveNewCarrier(selectedText);
            if (!selectedLocations.includes(selectedText) || !selectedLocationsUpdate.includes(selectedText)) {
                if(!selectedLocations.includes(selectedText)){
                    selectedLocations.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedLocations);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                if(!selectedLocationsUpdate.includes(selectedText)){
                    selectedLocationsUpdate.push(selectedText);  // Agregar al arreglo solo si no existe
                    console.log(selectedLocationsUpdate);  // Mostrar el arreglo con todos los drivers seleccionados
                }
                saveNewlocationUpdate(selectedText);
            }
        }
    });*/

    /*function saveNewlocationUpdate(carrierName) {
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
                var newOption1 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);
                var newOption2 = new Option(response.newCarrier.CoName, response.newCarrier.pk_company, true, true);

                // Agregar la opción a ambos select2
                $('#updateinputlocation').append(newOption1).trigger('change');
                $('#inputlocation').append(newOption2).trigger('change');

                // Seleccionar el nuevo carrier automáticamente
                $('#updateinputlocation').val(response.newCarrier.pk_company).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedCarrierIdUpdate = response.newCarrier.CoName;

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#updateinputlocation').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedCarrierIdUpdate) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedCarrierIdUpdate = null;  // Restablecer el ID del carrier creado
                    }
                });
                loadLocationsFilterCheckbox();
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el Location', error);
            }
        });
    }*/
});

//Busqueda de Locations en el update 
$(document).ready(function () {
    var carrierRoute = $('#updateinputlocation').data('url');
    var newlyCreatedCarrierId = null; // Variable para almacenar el ID del carrier recién creado
    var selectedLocationsUpdate = []; // Arreglo para almacenar todos los drivers seleccionados

    function loadLocationsUpdate() {
        $('#updateinputlocation').select2({
            placeholder: 'Select or enter a New Carrier',
            //allowClear: true,
            //tags: true, // Permite agregar nuevas opciones
            dropdownParent: $('#updatenewtrailerempty'),
            ajax: {
                url: carrierRoute,
                dataType: 'json',
                delay: 450,
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
    }

});

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
                      //select.append('<option selected disabled hidden></option>'); // Opción inicial
    
                      if (data.length === 0) {
                          select.append('<option disabled>No options available</option>');
                      } else {
                            select.append('<option value="">Choose an option</option>');
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

document.getElementById('exportfile').addEventListener('click', function () {
    var table = document.getElementById('table_empty_trailers');
    
    // Crear un nuevo libro de Excel
    var wb = new ExcelJS.Workbook();
    var ws = wb.addWorksheet('EmptyTrailers');
    
    // Obtener los datos de la tabla
    var rows = table.rows;
    
    // Copiar las filas de la tabla a la hoja de trabajo
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var rowData = [];
        for (var j = 0; j < row.cells.length; j++) {
            rowData.push(row.cells[j].innerText); // Obtener el texto de cada celda
        }
        ws.addRow(rowData);
    }

    // Formatear la columna 2 (índice 1) para que sea m/d/yyyy sin hora
    ws.getColumn(2).eachCell(function (cell, rowNumber) {
        if (rowNumber > 1) { // Evitar el encabezado
            var date = new Date(cell.value);
            if (date instanceof Date && !isNaN(date)) {
                // Establecer el formato de la fecha como m/d/yyyy (sin hora)
                cell.numFmt = 'm/d/yyyy';
            }
        }
    });

    // Formatear las columnas 7, 8, 9 (índices 6, 7, 8) para que sea m/d/yyyy h:mm:ss AM/PM
    [7, 8, 9].forEach(function (colIndex) {
        ws.getColumn(colIndex).eachCell(function (cell, rowNumber) {
            if (rowNumber > 1) { // Evitar el encabezado
                var date = new Date(cell.value);
                if (date instanceof Date && !isNaN(date)) {
                    // Establecer el formato de la fecha y hora como m/d/yyyy h:mm:ss AM/PM
                    cell.numFmt = 'm/d/yyyy h:mm:ss AM/PM';
                }
            }
        });
    });

    // Crear un nombre de archivo basado en la fecha y hora actual
    var now = new Date();
    var year = now.getFullYear();
    var month = String(now.getMonth() + 1).padStart(2, '0');
    var day = String(now.getDate()).padStart(2, '0');
    var hours = String(now.getHours()).padStart(2, '0');
    var minutes = String(now.getMinutes()).padStart(2, '0');
    var seconds = String(now.getSeconds()).padStart(2, '0');

    var formattedDateTime = `${month}${day}${year}_${hours}-${minutes}-${seconds}`;
    var filename = `EmptyTrailers_${formattedDateTime}.xlsx`;

    // Exportar el archivo Excel
    wb.xlsx.writeBuffer().then(function (buffer) {
        var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    });
});

/*
document.getElementById('exportfile').addEventListener('click', function () {
    // Obtén la tabla con el id "table_empty_trailers"
    var table = document.getElementById('table_empty_trailers');
    
    // Convierte la tabla HTML en una hoja de cálculo de Excel
    var wb = XLSX.utils.table_to_book(table, { sheet: "EmptyTrailers" });

    // Aplica formato a las columnas 8, 9, 10 (índices 7, 8, 9)
    var ws = wb.Sheets["EmptyTrailers"];
    
    // Recorre todas las filas y aplica formato a las columnas 8, 9, 10
    var range = XLSX.utils.decode_range(ws['!ref']); // Obtiene el rango de la hoja
    for (var row = range.s.r + 1; row <= range.e.r; row++) {
        // Para la columna 8 (índice 7)
        var cellAddress8 = { r: row, c: 7 };
        var cell8 = ws[XLSX.utils.encode_cell(cellAddress8)];
        if (cell8) {
            cell8.z = "yyyy-mm-dd hh:mm:ss"; // El formato de fecha y hora
        }
        
        // Para la columna 9 (índice 8)
        var cellAddress9 = { r: row, c: 8 };
        var cell9 = ws[XLSX.utils.encode_cell(cellAddress9)];
        if (cell9) {
            cell9.z = "yyyy-mm-dd hh:mm:ss"; // El formato de fecha y hora
        }

        // Para la columna 10 (índice 9)
        var cellAddress10 = { r: row, c: 9 };
        var cell10 = ws[XLSX.utils.encode_cell(cellAddress10)];
        if (cell10) {
            cell10.z = "yyyy-mm-dd hh:mm:ss"; // El formato de fecha y hora
        }
    }

    // Obtén la fecha y hora actuales
    var now = new Date();
    var year = now.getFullYear();
    var month = String(now.getMonth() + 1).padStart(2, '0'); // Mes con 2 dígitos
    var day = String(now.getDate()).padStart(2, '0');       // Día con 2 dígitos
    var hours = String(now.getHours()).padStart(2, '0');    // Horas con 2 dígitos
    var minutes = String(now.getMinutes()).padStart(2, '0');// Minutos con 2 dígitos
    var seconds = String(now.getSeconds()).padStart(2, '0');// Segundos con 2 dígitos

    // Formato: MM-DD-YYYY_HH-MM-SS
    var formattedDateTime = `${month}${day}${year}_${hours}-${minutes}-${seconds}`;

    // Define el nombre del archivo con fecha y hora
    var filename = `EmptyTrailers_${formattedDateTime}.xlsx`;

    // Exporta el archivo Excel
    XLSX.writeFile(wb, filename);
});
*/
// Inicializar los tooltips solo para los elementos con la clase memingo
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
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
    dateFormat: "m/d/Y H:i:S",  // Establece el formato para incluir año, mes, día, hora, minuto y segundo
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
});
    //Script para buscar el availability indicator en la pantalla de empty trailer
    $(document).ready(function () {
      //Funcion para buscar los carriers en la pantalla de empty trailer update
    /*function loadCarriersupdate() {
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
                        select.append(`<option value="${item.pk_company}">${item.CoName}</option>`);
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
    loadCarriersupdate();*/

    //Funcion para buscar los carriers en la pantalla de empty trailer
    /*function loadCarriers() {
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
                        select.append(`<option value="${item.pk_company}">${item.CoName}</option>`);
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
    loadCarriers();*/
    
    //Funcion para buscar las locations en la pantalla de empty trailer update
    /*function loadLocationsupdate() {
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
                        select.append(`<option value="${item.pk_company}">${item.CoName}</option>`);
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
    }*/

    //Ejecurtar la funcion al picarle al boton update 
    /*$('#updateinputlocation').on('focus', loadLocationsupdate);
    loadLocationsupdate();*/

    //Funcion para buscar las locations en la pantalla de empty trailer
    /*function loadLocations() {
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
                        select.append(`<option value="${item.pk_company}">${item.CoName}</option>`);
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
    }*/

    //Ejecurtar la funcion al picarle al boton 
    /*$('#inputlocation').on('focus', loadLocations);
    loadLocations();*/

  });

    //Crear nuevo trailer 
    $(document).ready(function() {

        // Evento cuando se borra la selección con la "X"
        $('#inputcarrier').on('select2:clear', function() {
            const field = $(this);
            const errorContainer = field.parent().find('.invalid-feedback');

            field.addClass('is-invalid'); // Agregar borde rojo
            field.next('.select2-container').find('.select2-selection').addClass('is-invalid');
            errorContainer.text('Carrier is required.'); // Mostrar mensaje de error
        });

        // Si selecciona un valor válido, eliminar error
        $('#inputcarrier').on('select2:select', function() {
            const field = $(this);
            const errorContainer = field.parent().find('.invalid-feedback');

            field.removeClass('is-invalid'); // Quitar borde rojo
            field.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            errorContainer.text(''); // Borrar mensaje de error
        });

        // Evento cuando se borra la selección con la "X"
        $('#inputlocation').on('select2:clear', function() {
            const field = $(this);
            const errorContainer = field.parent().find('.invalid-feedback');

            field.addClass('is-invalid'); // Agregar borde rojo
            field.next('.select2-container').find('.select2-selection').addClass('is-invalid');
            errorContainer.text('Location is required.'); // Mostrar mensaje de error
        });

        // Si selecciona un valor válido, eliminar error
        $('#inputlocation').on('select2:select', function() {
            const field = $(this);
            const errorContainer = field.parent().find('.invalid-feedback');

            field.removeClass('is-invalid'); // Quitar borde rojo
            field.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
            errorContainer.text(''); // Borrar mensaje de error
        });

        // Validación en vivo cuando el usuario interactúa con los campos
        $('input, select').on('input change', function() {
            const field = $(this);
            const fieldName = field.attr('name');
            let errorContainer = field.next('.invalid-feedback');

            // Si es un select2, busca el contenedor adecuado
            /*if (field.hasClass('searchcarrier')) {
                errorContainer = field.parent().find('.invalid-feedback');
            }*/


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
    

            /*if (fieldName === 'inputpalletsontrailer' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets On Trailer is required.');
            }

            if (fieldName === 'inputpalletsontrailer' && field.val().trim() === '0') {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets On Trailer must have a valid value.');
            }*/
                if (fieldName === 'inputpalletsontrailer') {
                    const value = field.val().trim(); // Obtener el valor del campo
                
                    // Verificar si el campo está vacío
                    /*if (value.length === 0) {
                        field.addClass('is-invalid');
                        errorContainer.text('Pallets On Trailer are required.');
                    }
                    // Verificar si el valor es 0 o menor que 0
                    else if (parseFloat(value) === 0 || parseFloat(value) <= 0) {
                        field.addClass('is-invalid');
                        errorContainer.text('Pallets On Trailer must have a valid value.');
                    }*/
                    // Verificar si el valor es una letra (no un número)
                    if (isNaN(value)) {
                        field.addClass('is-invalid');
                        errorContainer.text('The value must be an integer.');
                    }
                    else {
                        field.removeClass('is-invalid');
                        errorContainer.text('');
                    }
                }

            /*if (fieldName === 'inputpalletsonfloor' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets On Floor is required.');
            }

            if (fieldName === 'inputpalletsonfloor' && field.val().trim() === '0') {
                field.addClass('is-invalid');
                errorContainer.text('The Pallets On Floor must have a valid value.');
            }*/
                if (fieldName === 'inputpalletsonfloor') {
                    const value = field.val().trim(); // Obtener el valor del campo
                
                    // Verificar si el campo está vacío
                    /*if (value.length === 0) {
                        field.addClass('is-invalid');
                        errorContainer.text('Pallets On Floor are required.');
                    }
                    // Verificar si el valor es 0 o menor que 0
                    else if (parseFloat(value) === 0 || parseFloat(value) <= 0) {
                        field.addClass('is-invalid');
                        errorContainer.text('Pallets On Floor must have a valid value.');
                    }
                    // Verificar si el valor es una letra (no un número)
                    else*/ if (isNaN(value)) {
                        field.addClass('is-invalid');
                        errorContainer.text('The value must be an integer.');
                    }
                    else {
                        field.removeClass('is-invalid');
                        errorContainer.text('');
                    }
                }
            // Validación específica para #inputcarrier
            /*if (fieldName === 'inputcarrier' && (!field.val() || field.val().trim().length === 0)) {
                field.addClass('is-invalid');
                field.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                errorContainer.text('Carrier is required.');
            } else {
                field.removeClass('is-invalid');
                field.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                errorContainer.text('');
            }*/
                
            /*if (fieldName === 'inputcarrier' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Carrier is required.');
            }*/
    
            /*if (fieldName === 'inputavailabilityindicator' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Availability Indicator is required.');
            }*/
    
            /*if (fieldName === 'inputlocation' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Location is required.');
            }*/
    
            // Validación simple para las fechas (solo obligatorio)
            if (fieldName === 'inputdatein' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Date In is required.');
            }
    
            /*if (fieldName === 'inputdateout' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Date out is required.');
            }*/
    
            /*if (fieldName === 'inputtransactiondate' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('Transaction Date is required.');
            }*/
            /*if (fieldName === 'inputusername' && field.val().trim().length === 0) {
                field.addClass('is-invalid');
                errorContainer.text('The Username is required.');
            }*/
        });
    
        // Cuando el formulario se envía (al hacer clic en Save)
        $('#emptytrailerformm').submit(function(e) {
            e.preventDefault(); // Evita el envío del formulario

            let searchValue = table.search(); // Guarda el término de búsqueda
            let filters = table.columns().search(); // Guarda los filtros de cada columna    
        
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
                        title: '¡Success!',
                        text: 'Trailer successfully added.',
                        confirmButtonText: 'Ok'
                    });
                    trailersData = {};
                    table.clear();
                    updateTrailerTableWithData(response.trailers);
                    $('#closenewtrailerregister').click();
                    //$('#refreshemptytrailertable').click();

                    function updateTrailerTableWithData(trailers) {
                        //const tbody = $('#emptyTrailerTableBody');
                        //tbody.empty(); // Limpiar la tabla antes de agregar nuevas filas
                    
                        trailers.forEach(trailer => {
                            // Guardar los datos actualizados en trailersData
                            trailersData[trailer.pk_trailer] = trailer;
                            // Agregar los datos a la tabla sin atributos
                            const rowNode = table.row.add([
                                trailer.trailer_num ?? '',
                                trailer.status ?? '',
                                trailer.pallets_on_trailer ?? '',
                                trailer.pallets_on_floor ?? '',
                                trailer.carriers?.CoName ?? '',
                                trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : '',
                                trailer.locations?.CoName ?? '',
                                trailer.date_in ?? '',
                                trailer.username ?? ''
                            ]).node(); // Esto devuelve el nodo de la fila agregada
                    
                            // Ahora añadimos los atributos a la fila
                            $(rowNode).attr({
                                'id': `trailer-${trailer.pk_trailer}`,
                                'class': 'clickable-row',
                                'data-bs-toggle': 'offcanvas',
                                'data-bs-target': '#emptytrailer',
                                'aria-controls': 'emptytrailer',
                                'data-id': trailer.pk_trailer
                            });
                        });
                        table.draw(false); // Redibuja la tabla sin reiniciar la paginación

                        // Restaurar la búsqueda y los filtros
                        table.search(searchValue).draw(); // Restablece la búsqueda general
                        filters.each((value, index) => {
                            if (value) table.column(index).search(value).draw(); // Restablece los filtros por columna
                        });

                        // Asignar eventos correctamente incluso tras filtros
                        $(document).off("click", ".clickable-row").on("click", ".clickable-row", function () {
                            const id = $(this).data("id");
                            const trailer = trailersData[id];

                            if (trailer) {
                                document.getElementById("pk_trailer").textContent = trailer.pk_trailer;
                                document.getElementById("offcanvas-id").textContent = trailer.trailer_num;
                                document.getElementById("offcanvas-status").textContent = trailer.status;
                                document.getElementById("offcanvas-pallets-on-trailer").textContent = trailer.pallets_on_trailer;
                                document.getElementById("offcanvas-pallets-on-floor").textContent = trailer.pallets_on_floor;
                                document.getElementById("offcanvas-carrier").textContent = trailer.carriers?.CoName ?? '';
                                document.getElementById("offcanvas-availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : '';
                                document.getElementById("offcanvas-location").textContent = trailer.locations?.CoName ?? '';
                                document.getElementById("offcanvas-date-in").textContent = trailer.date_in;
                                document.getElementById("offcanvas-username").textContent = trailer.username;
                                document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : '';
                                document.getElementById("pk_location").textContent = trailer.locations?.pk_company ?? '';
                                document.getElementById("pk_carrier").textContent = trailer.carriers?.pk_company ?? '';
                            } else {
                                console.error(`No data found for trailer ID ${id}`);
                            }
                        });
                    }
                    loadAvailabilityIndicatorupdate();
                },
                error: function(xhr, status, error) {
                    // Limpia los errores anteriores
                    $('input, select').removeClass('is-invalid');
                    $('.invalid-feedback').text(''); // Vaciar mensajes de error
                    $('.select2-selection').removeClass('is-invalid'); // También eliminar la clase del contenedor de select2

        
                    let errors = xhr.responseJSON.errors;
        
                    // Verifica si hay errores
                    if (errors) {
                        for (let field in errors) {
                            const inputField = $('#' + field);
        
                            // Verifica que exista el div de error; si no, lo crea
                            let errorContainer = inputField.next('.invalid-feedback');
                            console.log(errorContainer)
                            // Verifica si el campo es un select2 (utiliza el id del campo)
                            if (inputField.hasClass('select2-hidden-accessible') || inputField.is('select')) {
                                // Si es un select2, buscamos su contenedor
                                const select2Container = inputField.next('.select2-container').find('.select2-selection');

                                // Agregamos la clase is-invalid al contenedor de select2
                                select2Container.addClass('is-invalid');
                                errorContainer = inputField.parent().find('.invalid-feedback');
                            }
                            if (!errorContainer.length) {
                                errorContainer = $('<div>').addClass('invalid-feedback').insertAfter(inputField);
                            }
        
                            // Marca el input y muestra el error
                            inputField.addClass('is-invalid');
                            errorContainer.text(errors[field][0]);

                        }
                    }
        
                    // Luego muestra el mensaje de error general
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
    function updateTrailerTable() {

        let searchValue = table.search(); // Guarda el término de búsqueda
        let filters = table.columns().search(); // Guarda los filtros de cada columna

        // Obtener los valores de los filtros
        // Función para agregar parámetros solo si tienen valor
        function addParam(key, value) {
            if (value && value.trim() !== '') {  // Solo agregar si tiene un valor
                params.set(key, value);
            }
        }

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
        //const dateOutStart = document.getElementById('emptytrailerfilterinputstartdateout').value;
        //const dateOutEnd = document.getElementById('emptytrailerfilterinputenddateout').value;
        //const transactionDateStart = document.getElementById('emptytrailerfilterinputstarttransactiondate').value;
        //const transactionDateEnd = document.getElementById('emptytrailerfilterinputendtransactiondate').value;
        
        // Obtener todas las ubicaciones seleccionadas (checkboxes marcados)
        let selectedLocationsCheckbox = [];
        $('#locationCheckboxContainer input[type="checkbox"]:checked').each(function () {
            selectedLocationsCheckbox.push($(this).val()); // Agrega el ID de la ubicación
        });

        const locations = selectedLocationsCheckbox.join(','); // Convertir array en string separado por comas

        let selectedCarriesCheckbox = [];
        $('#CarrierCheckboxContainer input[type="checkbox"]:checked').each(function () {
            selectedCarriesCheckbox.push($(this).val()); // Agrega el ID de la ubicación
        });

        const carriers = selectedCarriesCheckbox.join(','); // Convertir array en string separado por comas

        let selectedAvailabilityIndicatorCheckbox = [];
        $('#AvailabilityIndicatorCheckboxContainer input[type="checkbox"]:checked').each(function () {
            selectedAvailabilityIndicatorCheckbox.push($(this).val()); // Agrega el ID de la ubicación
        });

        const indicators = selectedAvailabilityIndicatorCheckbox.join(','); // Convertir array en string separado por comas
        
        // Construir la URL con los parámetros de filtro
        const url = new URL(document.getElementById('refreshemptytrailertable').getAttribute('data-url'));
        const params = new URLSearchParams(url.search);
    
        // Agregar solo los parámetros que tienen valor
        addParam('search', search);
        addParam('trailer_num', trailerNum);
        addParam('status_start', statusStart);
        addParam('status_end', statusEnd);
        addParam('pallets_on_trailer', palletsOnTrailer);
        addParam('pallets_on_floor', palletsOnFloor);
        addParam('carrier', carrier);
        addParam('gnct_id_availability_indicator', availabilityIndicator);
        addParam('location', location);
        addParam('username', username);
        addParam('date_in_start', dateInStart);
        addParam('date_in_end', dateInEnd);

        // Agregar los filtros de checkboxes (si están seleccionados)
        if (locations) params.set('locations', locations);
        if (carriers) params.set('carrierscheckbox', carriers);
        if (indicators) params.set('indicators', indicators);
        /*// Agregar los filtros a los parámetros de la URL
        params.set('search', search);
        params.set('trailer_num', trailerNum);
        params.set('status_start', statusStart);
        params.set('status_end', statusEnd);
        params.set('pallets_on_trailer', palletsOnTrailer);
        params.set('pallets_on_floor', palletsOnFloor);
        params.set('carrier', carrier);
        params.set('carrierscheckbox', carriers);
        params.set('gnct_id_availability_indicator', availabilityIndicator);
        params.set('indicators', indicators);
        params.set('location', location);
        params.set('locations', locations);
        params.set('username', username);
        params.set('date_in_start', dateInStart);
        params.set('date_in_end', dateInEnd);*/
        //params.set('date_out_start', dateOutStart);
        //params.set('date_out_end', dateOutEnd);
        //params.set('transaction_date_start', transactionDateStart);
        //params.set('transaction_date_end', transactionDateEnd);
    
        url.search = params.toString();
        //console.log(url);
        fetch(url)
            .then(response => response.json())
            .then(data => {

            // Actualiza la tabla de DataTables
            table.clear(); // Limpia la tabla actual

                // Aquí va el código para actualizar trailersData
                trailersData = data.reduce((acc, trailer) => {
                    acc[trailer.pk_trailer] = trailer;
                    return acc;
                }, {});
                // Actualizar la tabla con los datos filtrados
                const tbody = document.getElementById('emptyTrailerTableBody');
                //tbody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevas filas
    
                data.forEach(trailer => {
                    const rowNode = table.row.add([
                        trailer.trailer_num ?? '',
                        trailer.status ?? '',
                        trailer.pallets_on_trailer ?? '',
                        trailer.pallets_on_floor ?? '',
                        trailer.carriers?.CoName ?? '',
                        trailer.availability_indicator?.gntc_description ?? '',
                        trailer.locations?.CoName ?? '',
                        trailer.date_in ?? '',
                        trailer.username ?? ''
                    ]).node(); // Esto devuelve el nodo de la fila agregada

                    // Ahora añadimos los atributos a la fila
                    $(rowNode).attr({
                        'id': `trailer-${trailer.pk_trailer}`,
                        'class': 'clickable-row',
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#emptytrailer',
                        'aria-controls': 'emptytrailer',
                        'data-id': trailer.pk_trailer
                    });
                    
                    /*const row = `
                        <tr id="trailer-${trailer.pk_trailer}" class="clickable-row " 
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#emptytrailer" 
                            aria-controls="emptytrailer" 
                            data-id="${trailer.pk_trailer ?? '' }">
                            <td>${trailer.trailer_num ?? '' }</td>
                            <td>${trailer.status ?? '' }</td>
                            <td>${trailer.pallets_on_trailer ?? ''}</td>
                            <td>${trailer.pallets_on_floor ?? ''}</td>
                            <td>${trailer.carriers?.CoName ?? ''}</td>
                            <td>${trailer.availability_indicator?.gntc_description ?? ''}</td>
                            <!--<td>${trailer.locations?.CoName ?? ''}</td>-->
                            <td>${trailer.date_in ?? '' }</td>
                            <!-- <td>${trailer.date_out ?? '' }</td> -->
                            <!-- <td>${trailer.transaction_date ?? '' }</td> -->
                            <td>${trailer.username ?? '' }</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;*/
                });

                // Vuelve a agregar los listeners de clic después de actualizar la tabla
                /*const rows = document.querySelectorAll(".clickable-row");
                rows.forEach(row => {
                    row.addEventListener("click", function () {
                        const id = this.getAttribute("data-id");
                        const trailer = trailersData[id]; // Busca los datos del tráiler*/
                        //console.log(trailer);
                
                table.draw(false); // Redibuja la tabla sin reiniciar la paginación

                // Restaurar la búsqueda y los filtros
                table.search(searchValue).draw(); // Restablece la búsqueda general
                filters.each((value, index) => {
                    if (value) table.column(index).search(value).draw(); // Restablece los filtros por columna
                });


                $(document).off("click", ".clickable-row").on("click", ".clickable-row", function () {
                    const id = $(this).data("id");
                    const trailer = trailersData[id]; 
                        if (trailer) {
                            // Asigna los datos al offcanvas
                            document.getElementById("pk_trailer").textContent = trailer.pk_trailer;
                            document.getElementById("offcanvas-id").textContent = trailer.trailer_num;
                            document.getElementById("offcanvas-status").textContent = trailer.status;
                            document.getElementById("offcanvas-pallets-on-trailer").textContent = trailer.pallets_on_trailer;
                            document.getElementById("offcanvas-pallets-on-floor").textContent = trailer.pallets_on_floor;
                            document.getElementById("offcanvas-carrier").textContent = trailer.carriers && trailer.carriers.CoName ? trailer.carriers.CoName : '';
                            document.getElementById("offcanvas-availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : '';
                            document.getElementById("offcanvas-location").textContent = trailer.locations && trailer.locations.CoName ? trailer.locations.CoName : '';
                            document.getElementById("offcanvas-date-in").textContent = trailer.date_in;
                            //document.getElementById("offcanvas-date-out").textContent = trailer.date_out;
                            //document.getElementById("offcanvas-transaction-date").textContent = trailer.transaction_date;
                            document.getElementById("offcanvas-username").textContent = trailer.username;
                            document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : '';
                            document.getElementById("pk_location").textContent = trailer.locations && trailer.locations.pk_company ? trailer.locations.pk_company : '';
                            document.getElementById("pk_carrier").textContent = trailer.carriers && trailer.carriers.pk_company ? trailer.carriers.pk_company : '';
                        } else {
                            console.error(`No data found for trailer ID ${id}`);
                        }
                    //});
                });
                loadAvailabilityIndicatorupdate();
                
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Llamar la función cuando se hace clic en el botón de "Refresh" o cuando cambian los filtros
    document.getElementById('refreshemptytrailertable').addEventListener('click', updateTrailerTable);
    
    // Configurar los eventos de cambio de los inputs
    const filterInputs = document.querySelectorAll('#filtersapplied input');
    filterInputs.forEach(input => {
        input.addEventListener('input', updateTrailerTable);
    });

    let debounceTimer;

    function debounceUpdate() {
        clearTimeout(debounceTimer); // Cancela el temporizador anterior
        debounceTimer = setTimeout(updateTrailerTable, 1000); // Espera 3 segundos antes de ejecutar la función
    }

    /*const filterGeneralInputs = document.querySelectorAll('#searchemptytrailergeneral');
    filterGeneralInputs.forEach(input => {
        input.addEventListener('input', debounceUpdate);
    });*/
    // Filtros de cada columna
    $('#searchemptytrailergeneral').on('input', function() {
        table.search(this.value).draw(); // Busca en todas las columnas
    });

    // Actualización automática cada 5 minutos (300,000 ms)
    setInterval(updateTrailerTable, 60000);
    

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
            
                // Reiniciar mensajes de error
                let errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains("invalid-feedback")) {
                    errorDiv.textContent = ''; // Vaciar el contenido en lugar de eliminar el div
                } else if (!errorDiv) {
                    // Si no existe, crea uno
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    input.parentNode.appendChild(errorDiv);
                }
            });

            /*inputs.forEach((input) => {
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
            });*/

            // Opcional: Si necesitas reiniciar select dinámicos o resetear errores manualmente
            const selects = form.querySelectorAll("select");
            selects.forEach(select => {
                select.selectedIndex = 0; // Restablecer al primer valor (placeholder)
            });

            // Resetear Select2 manualmente
            $('#inputcarrier').val(null).trigger('change');  // Restablecer el valor del select
            $('#inputlocation').val(null).trigger('change'); 
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
                      document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : '' ;
                      document.getElementById("pk_location").textContent = trailer.locations && trailer.locations.pk_company ? trailer.locations.pk_company : '';
                      document.getElementById("pk_carrier").textContent = trailer.carriers && trailer.carriers.pk_company ? trailer.carriers.pk_company : '';
                      document.getElementById("offcanvas-id").textContent = trailer.trailer_num;
                      document.getElementById("offcanvas-status").textContent = trailer.status;
                      document.getElementById("offcanvas-pallets-on-trailer").textContent = trailer.pallets_on_trailer;
                      document.getElementById("offcanvas-pallets-on-floor").textContent = trailer.pallets_on_floor;
                      document.getElementById("offcanvas-carrier").textContent = trailer.carriers && trailer.carriers.CoName ? trailer.carriers.CoName : '';
                      document.getElementById("offcanvas-availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : '';
                      document.getElementById("offcanvas-location").textContent = trailer.locations && trailer.locations.CoName ? trailer.locations.CoName : '';
                      document.getElementById("offcanvas-date-in").textContent = trailer.date_in;
                      //document.getElementById("offcanvas-date-out").textContent = trailer.date_out;
                      //document.getElementById("offcanvas-transaction-date").textContent = trailer.transaction_date;
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

        let searchValue = table.search(); // Guarda el término de búsqueda
        let filters = table.columns().search(); // Guarda los filtros de cada columna


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
                          /*delete trailersData[trailerId]; // Elimina el tráiler de trailersData
                          //console.log(trailersData)
                          // Eliminar el tráiler de la tabla solo si la eliminación fue exitosa
                          const row = document.querySelector(`#trailer-${trailerId}`);
                          if (row) row.remove();*/

                          // Mostrar alerta de éxito
                          Swal.fire({
                              title: 'Deleted',
                              text: data.message,
                              icon: 'success',
                              confirmButtonText: 'OK',
                          }).then(() => {
                
                            trailersData = {};
                            table.clear();
                            
                            // Añadir las filas nuevas
                            data.trailers.forEach(trailer => {
                                // Guardar los datos actualizados en trailersData
                                trailersData[trailer.pk_trailer] = trailer;
                                // Agregar los datos a la tabla sin atributos
                                const rowNode = table.row.add([
                                    trailer.trailer_num ?? '',
                                    trailer.status ?? '',
                                    trailer.pallets_on_trailer ?? '',
                                    trailer.pallets_on_floor ?? '',
                                    trailer.carriers?.CoName ?? '',
                                    trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : '',
                                    trailer.locations?.CoName ?? '',
                                    trailer.date_in ?? '',
                                    trailer.username ?? ''
                                ]).node(); // Esto devuelve el nodo de la fila agregada

                                // Ahora añadimos los atributos a la fila
                                $(rowNode).attr({
                                    'id': `trailer-${trailer.pk_trailer}`,
                                    'class': 'clickable-row',
                                    'data-bs-toggle': 'offcanvas',
                                    'data-bs-target': '#emptytrailer',
                                    'aria-controls': 'emptytrailer',
                                    'data-id': trailer.pk_trailer
                                });
                            });
                            // Asignar eventos correctamente incluso tras filtros
                            $(document).off("click", ".clickable-row").on("click", ".clickable-row", function () {
                                const id = $(this).data("id");
                                const trailer = trailersData[id];

                                if (trailer) {
                                    document.getElementById("pk_trailer").textContent = trailer.pk_trailer;
                                    document.getElementById("offcanvas-id").textContent = trailer.trailer_num;
                                    document.getElementById("offcanvas-status").textContent = trailer.status;
                                    document.getElementById("offcanvas-pallets-on-trailer").textContent = trailer.pallets_on_trailer;
                                    document.getElementById("offcanvas-pallets-on-floor").textContent = trailer.pallets_on_floor;
                                    document.getElementById("offcanvas-carrier").textContent = trailer.carriers?.CoName ?? '';
                                    document.getElementById("offcanvas-availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : '';
                                    document.getElementById("offcanvas-location").textContent = trailer.locations?.CoName ?? '';
                                    document.getElementById("offcanvas-date-in").textContent = trailer.date_in;
                                    document.getElementById("offcanvas-username").textContent = trailer.username;
                                    document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : '';
                                    document.getElementById("pk_location").textContent = trailer.locations?.pk_company ?? '';
                                    document.getElementById("pk_carrier").textContent = trailer.carriers?.pk_company ?? '';
                                } else {
                                    console.error(`No data found for trailer ID ${id}`);
                                }
                            });


                            // Redibujar la tabla con los nuevos datos
                            //table.draw();
                            table.draw(false); // Redibuja la tabla sin reiniciar la paginación

                            // Restaurar la búsqueda y los filtros
                            table.search(searchValue).draw(); // Restablece la búsqueda general
                            filters.each((value, index) => {
                                if (value) table.column(index).search(value).draw(); // Restablece los filtros por columna
                            });

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
          //document.getElementById('updateinputcarrier').value = trailer.carrier || '';
          document.getElementById('updateinputavailabilityindicator').value = trailer.gnct_id_availability_indicator || '';
          //document.getElementById('updateinputlocation').value = trailer.location || '';
          document.getElementById('updateinputdatein').value = trailer.date_in || '';
          //document.getElementById('updateinputdateout').value = trailer.date_out || '';
          //document.getElementById('updateinputtransactiondate').value = trailer.transaction_date || '';
          //document.getElementById('updateinputusername').value = trailer.username || '';
        
          // Asignar el valor al select2 de Carrier
          // Obtener el CoName del carrier a partir de su ID
          /*if (trailer.location) {
            $.ajax({
                url: 'locations-emptytrailerAjax', // Llamamos a la misma API de carriers
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    let location = data.find(item => item.pk_company == trailer.location);
                    if (location) {
                        let newOption = new Option(location.CoName, location.pk_company, true, true);
                        $('#updateinputlocation').append(newOption).trigger('change');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error al cargar las locations:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'There was an error loading locarion information.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            });
          }
          if (trailer.carrier) {
            $.ajax({
                url: 'carrier-emptytrailerAjax', // Llamamos a la misma API de carriers
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    let carrier = data.find(item => item.pk_company == trailer.carrier);
                    if (carrier) {
                        let newOption = new Option(carrier.CoName, carrier.pk_company, true, true);
                        $('#updateinputcarrier').append(newOption).trigger('change');
                    }
                    // Mostrar el canvas de actualización después de que todo esté listo
                    const updateCanvas = new bootstrap.Offcanvas(document.getElementById('updatenewtrailerempty'));
                    updateCanvas.show();
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
        } else{

          // Mostrar el canvas de actualización
          const updateCanvas = new bootstrap.Offcanvas(document.getElementById('updatenewtrailerempty'));
          updateCanvas.show();

        }*/
        // Crear promesas para las dos solicitudes AJAX
        let locationPromise = new Promise((resolve, reject) => {
            if (trailer.location) {
                let location = selectedLocationsUpdate.find(item => item === trailer.location);
                // Si el carrier está en la lista, solo se selecciona automáticamente
                $('#updateinputlocation').val(trailer.location).trigger('change');
                resolve();
            } else {
                $('#updateinputlocation').val(null).trigger('change');
                //resolve();
            }
        });

        let carrierPromise = new Promise((resolve, reject) => {
            if (trailer.carrier) {
                let carrier = selectedCarriersUpdate.find(item => item === trailer.carrier);
                // Si el carrier está en la lista, solo se selecciona automáticamente
                $('#updateinputcarrier').val(trailer.carrier).trigger('change');
                resolve();
                //$.ajax({
                //    url: 'carrier-emptytrailerAjax', // Cambia esta URL si es necesario
                //    type: 'GET',
                //    dataType: 'json',
                //    success: function (data) {
                //        let carrier = data.find(item => item.pk_company == trailer.carrier);
                //        if (carrier) {
                //            let newOption = new Option(carrier.CoName, carrier.pk_company, true, true);
                //            $('#updateinputcarrier').append(newOption).trigger('change');
                //        }
                //        resolve(); // Resolver la promesa cuando termine esta solicitud
                //    },
                //    error: function (xhr, status, error) {
                //        reject('Error al cargar los carriers: ' + error); // Rechazar si hay error
                //    }
                //});
            } else {
                $('#updateinputcarrier').val(null).trigger('change');
                //console
                //resolve(); // Resolver inmediatamente si no hay carrier
            }
        });

        // Esperar a que ambas promesas se resuelvan antes de mostrar el offcanvas
        Promise.all([locationPromise, carrierPromise])
            .then(() => {
                const updateCanvas = new bootstrap.Offcanvas(document.getElementById('updatenewtrailerempty'));
                updateCanvas.show(); // Mostrar el offcanvas cuando ambas solicitudes terminen
            })
            .catch((error) => {
                console.error(error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al cargar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            });


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

    // Función de validación en tiempo real
    const formFields = [
        //'updateinputidtrailer',
        'updateinputdateofstatus',
        'updateinputpalletsontrailer',
        'updateinputpalletsonfloor',
        'updateinputcarrier',
        //'updateinputavailabilityindicator',
        'updateinputlocation',
        'updateinputdatein',
        //'updateinputdateout',
        //'updateinputtransactiondate',
        //'updateinputusername'
    ];

    // Validación de cada campo
    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`error-${fieldId}`);
        const isSelect2 = $(field).hasClass("searchcarrier"); // Detecta si es un select2
        const isSelect2Location = $(field).hasClass("searchlocation");
        
        if (isSelect2) {
            // Para select2, usa 'change'
            $(field).on('change', function () {
                validateField(field, errorElement, isSelect2, isSelect2Location);
            });
        } else {
            // Para inputs normales, usa keyup y blur
            field.addEventListener('keyup', function () {
                validateField(field, errorElement, isSelect2, isSelect2Location);
            });
    
            field.addEventListener('blur', function () {
                validateField(field, errorElement, isSelect2, isSelect2Location);
            });
        }

        if (isSelect2Location) {
            // Para select2, usa 'change'
            $(field).on('change', function () {
                validateField(field, errorElement, isSelect2, isSelect2Location);
            });
        } else {
            // Para inputs normales, usa keyup y blur
            field.addEventListener('keyup', function () {
                validateField(field, errorElement, isSelect2, isSelect2Location);
            });
    
            field.addEventListener('blur', function () {
                validateField(field, errorElement, isSelect2, isSelect2Location);
            });
        }
    });

    // Función común para validar cada campo
    function validateField(field, errorElement, isSelect2, isSelect2Location) {
        // Validar si el campo está vacío
        if (field.value.trim() === '') {
            //field.classList.add('is-invalid');
            //errorElement.textContent = 'This field is required'; // Mensaje de error

            /// Si es un select2, aplica la clase al contenedor
            if (isSelect2 ) {
                $(field).siblings(".select2").find(".select2-selection").addClass("is-invalid");
                field.classList.add('is-invalid');
                errorElement.textContent = 'Carrier is required.';
            }else if(isSelect2Location){
                $(field).siblings(".select2").find(".select2-selection").addClass("is-invalid");
                field.classList.add('is-invalid');
                errorElement.textContent = 'Location is required.';
            }/*else if(field.id === 'updateinputpalletsontrailer'){
                errorElement.textContent = 'Pallets on trailer are required.';
            }*/else if(field.id === 'updateinputdateofstatus'){
                field.classList.add('is-invalid');
                errorElement.textContent = 'Status date is required.';
            }else if(field.id === 'updateinputdatein'){
                errorElement.textContent = 'Date In is required.';
                field.classList.add('is-invalid');
            }
        }
        // Validar si el campo es un número entero para los campos 'updateinputpalletsonfloor' y 'updateinputpalletsontrailer'
        else if ((field.id === 'updateinputpalletsonfloor' || field.id === 'updateinputpalletsontrailer')) {
            const value = field.value.trim();

            if (isNaN(value) || !Number.isInteger(parseFloat(value))) {
                field.classList.add('is-invalid');
                errorElement.textContent = 'This field must be an integer.'; // Mensaje de error
            }
            /*else if (value <= 0) {
                field.classList.add('is-invalid');
                errorElement.textContent = 'This field must have a valid value.'; // Mensaje de error
            }*/
        }
        else {
            field.classList.remove('is-invalid');
            errorElement.textContent = ''; // Limpiar el mensaje de error

            if (isSelect2) {
                $(field).next('.select2-container').find('.select2-selection').removeClass("is-invalid");
            }
            if(isSelect2Location){
                $(field).next('.select2-container').find('.select2-selection').removeClass("is-invalid");
            }
        }
    }

    // Ejecutar la validación al hacer clic en el botón de "guardar"
    /*document.getElementById("updatesaveButton").addEventListener("click", function () {
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
            else {
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
                    gnct_id_availability_indicator: document.getElementById("updateinputavailabilityindicator").value,
                    location: document.getElementById("updateinputlocation").value,
                    date_in: document.getElementById("updateinputdatein").value,
                    //date_out: document.getElementById("updateinputdateout").value,
                    //transaction_date: document.getElementById("updateinputtransactiondate").value,
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
    });*/

    // Ejecutar la validación al hacer clic en el botón de "guardar"
// Ejecutar la validación al hacer clic en el botón de "guardar"
document.getElementById("updatesaveButton").addEventListener("click", function () {
    let valid = true;

    let searchValue = table.search(); // Guarda el término de búsqueda
    let filters = table.columns().search(); // Guarda los filtros de cada columna


    // Validar cada campo antes de enviar
    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`error-${fieldId}`);
        const isSelect2 = $(field).hasClass("searchcarrier"); // Detecta si es un select2
        const isSelect2Location = $(field).hasClass("searchlocation");

        // Validar el campo
        if (field.value.trim() === '' && field.id !== 'updateinputpalletsonfloor' && field.id !== 'updateinputpalletsontrailer') {
            valid = false;
            //field.classList.add('is-invalid');
            //errorElement.textContent = 'This field is required';

            // Si es un select2, aplica la clase al contenedor
            if (isSelect2) {
                $(field).siblings(".select2").find(".select2-selection").addClass("is-invalid");
                field.classList.add('is-invalid');
                errorElement.textContent = 'Carrier is required.';
            }else if(isSelect2Location){
                $(field).siblings(".select2").find(".select2-selection").addClass("is-invalid");
                field.classList.add('is-invalid');
                errorElement.textContent = 'Location is required.';
            }/*else if(field.id === 'updateinputpalletsontrailer'){
                errorElement.textContent = 'Pallets on trailer are required.';
            }*/else if(field.id === 'updateinputdateofstatus'){
                field.classList.add('is-invalid');
                errorElement.textContent = 'Status date is required.';
            }else if(field.id === 'updateinputdatein'){
                field.classList.add('is-invalid');
                errorElement.textContent = 'Date In is required.';
            }
        }        
        /*else if ((field.id === 'updateinputpalletsonfloor' || field.id === 'updateinputpalletsontrailer')) {
            const value = field.value.trim();

            if (isNaN(value) || !Number.isInteger(parseFloat(value))) {
                field.classList.add('is-invalid');
                errorElement.textContent = 'This field must be an integer.'; // Mensaje de error
            }
            else if (value <= 0) {
                field.classList.add('is-invalid');
                errorElement.textContent = 'This field must have a valid value.'; // Mensaje de error
            }
        }*/
        else {
            field.classList.remove('is-invalid');
            errorElement.textContent = '';

            // Si es un select2, elimina la clase del contenedor
            if (isSelect2) {
                $(field).siblings(".select2").find(".select2-selection").removeClass("is-invalid");
            }
            if(isSelect2Location){
                $(field).siblings(".select2").find(".select2-selection").removeClass("is-invalid");
            }
        }
    });

    // Si hay errores, no enviar el formulario
    if (!valid) {
        const firstInvalidField = document.querySelector('.is-invalid');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
        return;
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
                updateinputpktrailer: document.getElementById("updateinputpktrailer").value,
                updateinputidtrailer: document.getElementById("updateinputidtrailer").value,
                updateinputdateofstatus: document.getElementById("updateinputdateofstatus").value,
                updateinputpalletsontrailer: document.getElementById("updateinputpalletsontrailer").value,
                updateinputpalletsonfloor: document.getElementById("updateinputpalletsonfloor").value,
                updateinputcarrier: document.getElementById("updateinputcarrier").value,
                updateinputavailabilityindicator: document.getElementById("updateinputavailabilityindicator").value,
                updateinputlocation: document.getElementById("updateinputlocation").value,
                updateinputdatein: document.getElementById("updateinputdatein").value,
                updateinputusername: document.getElementById("updateinputusername").value,
            };

            fetch(urlupdatetrailer, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(data),
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((errorData) => {
                            throw errorData;
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    Swal.fire("Saved!", data.message, "success");
                    // Limpiar las filas actuales

                    // Limpiar el objeto trailersData antes de actualizar
                    trailersData = {};

                    table.clear();
                    // Añadir las filas nuevas
                    data.trailers.forEach(trailer => {
                        // Guardar los datos actualizados en trailersData
                        trailersData[trailer.pk_trailer] = trailer;
                        // Agregar los datos a la tabla sin atributos
                        const rowNode = table.row.add([
                            trailer.trailer_num ?? '',
                            trailer.status ?? '',
                            trailer.pallets_on_trailer ?? '',
                            trailer.pallets_on_floor ?? '',
                            trailer.carriers?.CoName ?? '',
                            trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : '',
                            trailer.locations?.CoName ?? '',
                            trailer.date_in ?? '',
                            trailer.username ?? ''
                        ]).node(); // Esto devuelve el nodo de la fila agregada

                        // Ahora añadimos los atributos a la fila
                        $(rowNode).attr({
                            'id': `trailer-${trailer.pk_trailer}`,
                            'class': 'clickable-row',
                            'data-bs-toggle': 'offcanvas',
                            'data-bs-target': '#emptytrailer',
                            'aria-controls': 'emptytrailer',
                            'data-id': trailer.pk_trailer
                        });
                    });

                    table.draw(false); // Redibuja la tabla sin reiniciar la paginación

                    // Restaurar la búsqueda y los filtros
                    table.search(searchValue).draw(); // Restablece la búsqueda general
                    filters.each((value, index) => {
                        if (value) table.column(index).search(value).draw(); // Restablece los filtros por columna
                    });
                    
                    // Asignar eventos correctamente incluso tras filtros
                    $(document).off("click", ".clickable-row").on("click", ".clickable-row", function () {
                        const id = $(this).data("id");
                        const trailer = trailersData[id];

                        if (trailer) {
                            document.getElementById("pk_trailer").textContent = trailer.pk_trailer;
                            document.getElementById("offcanvas-id").textContent = trailer.trailer_num;
                            document.getElementById("offcanvas-status").textContent = trailer.status;
                            document.getElementById("offcanvas-pallets-on-trailer").textContent = trailer.pallets_on_trailer;
                            document.getElementById("offcanvas-pallets-on-floor").textContent = trailer.pallets_on_floor;
                            document.getElementById("offcanvas-carrier").textContent = trailer.carriers?.CoName ?? '';
                            document.getElementById("offcanvas-availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gntc_description ? trailer.availability_indicator.gntc_description : '';
                            document.getElementById("offcanvas-location").textContent = trailer.locations?.CoName ?? '';
                            document.getElementById("offcanvas-date-in").textContent = trailer.date_in;
                            document.getElementById("offcanvas-username").textContent = trailer.username;
                            document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : '';
                            document.getElementById("pk_location").textContent = trailer.locations?.pk_company ?? '';
                            document.getElementById("pk_carrier").textContent = trailer.carriers?.pk_company ?? '';
                        } else {
                            console.error(`No data found for trailer ID ${id}`);
                        }
                    });

                    document.getElementById('closeupdatemptytrailerbutton').click();
                    //document.getElementById('refreshemptytrailertable').click();
                    document.getElementById('closeoffcanvastrailersdetails').click();
                    //$('#table_empty_trailers').DataTable().ajax.reload(); 
                })
                .catch((error) => {
                    console.log(error); // Muestra el error
                    if (error.errors) {
                        Object.keys(error.errors).forEach(field => {
                            const fieldId = field; 
                            const errorMessages = error.errors[field]; // Los mensajes de error
                            const errorElement = document.getElementById(`error-${fieldId}`);
                            const fieldElement = document.getElementById(fieldId);
                            const isSelect2 = $(fieldElement).hasClass("searchcarrier"); // Detecta si es select2
                            const isSelect2Location = $(fieldElement).hasClass("searchlocation");
                
                            if (fieldElement) {
                                fieldElement.classList.add('is-invalid'); // Marca el campo como inválido
                                errorElement.textContent = errorMessages.join(', '); // Muestra el error en el campo

                                // Si es un select2, aplica la clase a la interfaz de select2
                                if (isSelect2) {
                                    $(fieldElement).next('.select2-container').find('.select2-selection').addClass("is-invalid");
                                }

                                if (isSelect2Location){
                                    $(fieldElement).next('.select2-container').find('.select2-selection').addClass("is-invalid");
                                }
                            }
                        });
                    } else {
                        Swal.fire("Error", error.message || "An unknown error occurred", "error");
                    }
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
            // **LIMPIAR EL SELECT2**
        /*let carrierSelect = $('#updateinputcarrier');
        carrierSelect.val(null).trigger('change'); // Quitar selección actual
        carrierSelect.find('option').remove(); // Eliminar opciones dinámicas*/
        //carrierSelect.append(new Option("Select or enter a New Carrier", "", true, true)); // Restaurar placeholder
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
        //const location = document.getElementById("pk_location").textContent;
        const datein = document.getElementById("offcanvas-date-in").textContent;
        //const dateout = document.getElementById("offcanvas-date-out").textContent;
        //const transaction = document.getElementById("offcanvas-transaction-date").textContent;
        const username = document.getElementById("offcanvas-username").textContent;
    
        // Construir la URL con los parámetros necesarios
        //const redirectUrl = `${url}?location=${encodeURIComponent(location)}&trailerId=${encodeURIComponent(trailerId)}&status=${encodeURIComponent(status)}`;
        const redirectUrl = `${url}?trailerId=${encodeURIComponent(trailerId)}
        &status=${encodeURIComponent(status)}
        &palletsontrailer=${encodeURIComponent(palletsontrailer)}
        &palletsonfloor=${encodeURIComponent(palletsonfloor)}
        &carrier=${encodeURIComponent(carrier)}
        &availability=${encodeURIComponent(availability)}
        &datein=${encodeURIComponent(datein)}
        &username=${encodeURIComponent(username)}`;

        /*
        &location=${encodeURIComponent(location)}
        &dateout=${encodeURIComponent(dateout)}
        &transaction=${encodeURIComponent(transaction)}
        &username=${encodeURIComponent(username)}`;
        */

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
        // Verifica si la tabla ya ha sido inicializada antes de inicializarla
    if (!$.fn.dataTable.isDataTable('#table_empty_trailers')) {
        table = $('#table_empty_trailers').DataTable({
            paging: false,  // Desactiva la paginación
            searching: true, // Mantiene la búsqueda activada
            info: false,     // Oculta la información
            lengthChange: false // Desactiva el cambio de cantidad de registros
        });
    } else {
        // Si la tabla ya está inicializada, se puede actualizar la configuración
        table.page.len(-1).draw();  // Muestra todos los registros sin paginación
    }
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
                    //este update su debe ir descomentado
                    //updatetab.click();
                    table.column(0).search($('#emptytrailerfilterinputidtrailer').val()).draw();
                    table.column(2).search($('#emptytrailerfilterinputpalletsontrailer').val()).draw();
                    table.column(3).search($('#emptytrailerfilterinputpalletsonfloor').val()).draw();
                    table.column(8).search($('#emptytrailerfilterinputusername').val()).draw();

                } else {
                    // Si el div no está visible, muestra el div y coloca el valor
                    $(inputFilterSelector).val(inputValue);
                    $(divSelector).show();
                    table.column(0).search($('#emptytrailerfilterinputidtrailer').val()).draw();
                    table.column(2).search($('#emptytrailerfilterinputpalletsontrailer').val()).draw();
                    table.column(3).search($('#emptytrailerfilterinputpalletsonfloor').val()).draw();
                    table.column(8).search($('#emptytrailerfilterinputusername').val()).draw();
                    //este update si debe ir descomentado
                    //updatetab.click();
                }
            } else {
                // Si el campo está vacío, vacía el input del filtro y oculta el div
                $(inputFilterSelector).val('');
                table.column(0).search($('#emptytrailerfilterinputidtrailer').val()).draw();
                table.column(2).search($('#emptytrailerfilterinputpalletsontrailer').val()).draw();
                table.column(3).search($('#emptytrailerfilterinputpalletsonfloor').val()).draw();
                table.column(8).search($('#emptytrailerfilterinputusername').val()).draw();
                $(divSelector).hide();
                $(closeButtonSelector).click(); // Simula un clic en Collapse
                //este update si debe ir descomentado
                //updatetab.click();
                
            }
        }
    
        // Función genérica para manejar clics en botones X
        function handleClearButton(divSelector, inputSelector, applyButtonSelector, closeButtonSelector) {
            $(inputSelector).val('');
            $(divSelector).hide();
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón
            $(applyButtonSelector).click(); // Simula clic en Apply
            //Este update no debe ir descomentado
            //updatetab.click();
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
                if ($(divSelector).is(":visible")) {
                    //Este update si debe ir descomentado
                    //updatetab.click();
                    table.column(0).search($('#emptytrailerfilterinputidtrailer').val()).draw();
                    table.column(2).search($('#emptytrailerfilterinputpalletsontrailer').val()).draw();
                    table.column(3).search($('#emptytrailerfilterinputpalletsonfloor').val()).draw();
                    table.column(8).search($('#emptytrailerfilterinputusername').val()).draw();
                }
                $(divSelector).hide(); // Oculta el div del filtro
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

    //Manejo mejorado de Date of Status
    $(document).ready(function () {
        // Verifica si la tabla ya ha sido inicializada antes de inicializarla
    if (!$.fn.dataTable.isDataTable('#table_empty_trailers')) {
        table = $('#table_empty_trailers').DataTable({
            paging: false,  // Desactiva la paginación
            searching: true, // Mantiene la búsqueda activada
            info: false,     // Oculta la información
            lengthChange: false // Desactiva el cambio de cantidad de registros
        });
    } else {
        // Si la tabla ya está inicializada, se puede actualizar la configuración
        table.page.len(-1).draw();  // Muestra todos los registros sin paginación
    }
        // Filtro de rango de fechas en la columna 1 y 6
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            // **Filtro 1**: Manejo de "Date of Status" (columna 1)
            var startDateStatus = $('#emptytrailerfilterinputdateofstartstatus').val();
            var endDateStatus = $('#emptytrailerfilterinputdateofendstatus').val();
            var dateColumnIndexStatus = 1;
            var rowDateStatus = data[dateColumnIndexStatus] || "";

            // **Filtro 2**: Manejo de "Date In" (columna 6)
            var startDateIn = $('#emptytrailerfilterinputstartdatein').val();
            var endDateIn = $('#emptytrailerfilterinputenddatein').val();
            var dateColumnIndexIn = 7;
            var rowDateIn = data[dateColumnIndexIn] || "";

            // Usa flatpickr para parsear correctamente las fechas
            var rowDateStatusObj = rowDateStatus ? flatpickr.parseDate(rowDateStatus, "d/m/Y H:i:s") : null;
            var startDateStatusObj = startDateStatus ? flatpickr.parseDate(startDateStatus, "d/m/Y H:i:s") : null;
            var endDateStatusObj = endDateStatus ? flatpickr.parseDate(endDateStatus, "d/m/Y H:i:s") : null;

            var rowDateInObj = new Date(rowDateIn);
            var startDateInObj = startDateIn ? new Date(startDateIn) : null;
            var endDateInObj = endDateIn ? new Date(endDateIn) : null;

            // **Lógica de filtrado**
            var statusMatch = (!startDateStatusObj || rowDateStatusObj >= startDateStatusObj) &&
                            (!endDateStatusObj || rowDateStatusObj <= endDateStatusObj);
            
            var inMatch = (!startDateInObj || rowDateInObj >= startDateInObj) &&
                        (!endDateInObj || rowDateInObj <= endDateInObj);

            return statusMatch && inMatch; // Ambas condiciones deben cumplirse
        });
        const updatetab = document.getElementById("refreshemptytrailertable");
        // Función para manejar el estado de los botones (habilitar/deshabilitar)
        function toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector, divSelector) {
            if ($(startInputSelector).val() || $(endInputSelector).val()) {
                $(closeButtonSelector).prop('disabled', true); // Habilita el botón Collapse
            } else {
                $(closeButtonSelector).prop('disabled', false); // Deshabilita el botón Collapse
            }
    
            // Deshabilita el botón Apply hasta que ambos inputs estén llenos
            if ($(startInputSelector).val() && $(endInputSelector).val()) {
                $(applyButtonSelector).prop('disabled', false); // Habilita el botón Apply
            } else if(!$(startInputSelector).val() && !$(endInputSelector).val() && $(divSelector).is(":visible")) {
                $(applyButtonSelector).prop('disabled', false); // Deshabilita el botón Apply
            }else{
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
                    //Este update si debe ir descomentado
                    //updatetab.click();
                    table.draw(); // Redibuja la tabla con el nuevo filtro
                } else {
                    $(startFilterInputSelector).val(startDate);
                    $(endFilterInputSelector).val(endDate);
                    $(divSelector).show(); // Muestra el div del filtro
                    //Este update si debe ir descomentado
                    //updatetab.click();
                    table.draw(); // Redibuja la tabla con el nuevo filtro
                }
            } else {
                $(startFilterInputSelector).val(''); // Limpia el input del Start Date asociado al filtro
                $(endFilterInputSelector).val(''); // Limpia el input del End Date asociado al filtro
                table.draw(); // Redibuja la tabla con el nuevo filtro
                $(divSelector).hide(); // Oculta el div del filtro
                $(closeButtonSelector).click(); // Simula un clic en Collapse
                //Este update si debe ir descomentado
                //updatetab.click();
            }
            toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector, divSelector);
        }
    
        // Función para limpiar el filtro (botón X)
        function clearDateRangeFilter(divSelector, startInputSelector, endInputSelector, applyButtonSelector, closeButtonSelector) {
            $(startInputSelector).val(''); // Limpia el input del Start Date
            $(endInputSelector).val(''); // Limpia el input del End Date
            //table.draw(); // Redibuja la tabla con el nuevo filtro
            $(applyButtonSelector).click();
            $(divSelector).hide(); // Oculta el div del filtro
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón
            $(closeButtonSelector).click();
            $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
            //Este update si debe ir descomentado
            //updatetab.click();
            
        }
    
        // Función para manejar clics en botones de cerrar Collapse
        function handleCloseDateRangeCollapse(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector, applyButtonSelector) {
            if (!$(startInputSelector).val() && !$(endInputSelector).val()) {
                $(startFilterInputSelector).val(''); // Limpia el Start Date del filtro
                $(endFilterInputSelector).val(''); // Limpia el End Date del filtro
                if ($(divSelector).is(":visible")) {
                    //Este update si debe ir descomentado
                    //updatetab.click();
                    table.draw(); // Redibuja la tabla con el nuevo filtro
                    $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
                } 
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
                '#emptytrailerfilterinputdateofendstatus', // End Date input en el filtro
                '#applystatusfilter'
            );
        });
    
    
        // Llamada inicial para verificar los botones
        toggleDateRangeButtons('#inputapplystatusstfilter', '#inputapplystatusedfilter', '#closeapplystatusfilter', '#applystatusfilter', '#emptytrailerfilterdivdateofstatus');

        // ------------------ Verificación de los inputs ------------------
        // Detecta cambios en los inputs del Offcanvas para habilitar o deshabilitar el botón de Collapse
        $('#inputapplystatusstfilter, #inputapplystatusedfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplystatusstfilter', '#inputapplystatusedfilter', '#closeapplystatusfilter', '#applystatusfilter', '#emptytrailerfilterdivdateofstatus');
        });
    });

    //Manejo Filtro de fechas datetime 
    $(document).ready(function () {
        // Verifica si la tabla ya ha sido inicializada antes de inicializarla
    if (!$.fn.dataTable.isDataTable('#table_empty_trailers')) {
        table = $('#table_empty_trailers').DataTable({
            paging: false,  // Desactiva la paginación
            searching: true, // Mantiene la búsqueda activada
            info: false,     // Oculta la información
            lengthChange: false // Desactiva el cambio de cantidad de registros
        });
    } else {
        // Si la tabla ya está inicializada, se puede actualizar la configuración
        table.page.len(-1).draw();  // Muestra todos los registros sin paginación
    }
        // Filtro de rango de fechas en la columna 1 y 6
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            // **Filtro 1**: Manejo de "Date of Status" (columna 1)
            var startDateStatus = $('#emptytrailerfilterinputdateofstartstatus').val();
            var endDateStatus = $('#emptytrailerfilterinputdateofendstatus').val();
            var dateColumnIndexStatus = 1;
            var rowDateStatus = data[dateColumnIndexStatus] || "";

            // **Filtro 2**: Manejo de "Date In" (columna 6)
            var startDateIn = $('#emptytrailerfilterinputstartdatein').val();
            var endDateIn = $('#emptytrailerfilterinputenddatein').val();
            var dateColumnIndexIn = 7;
            var rowDateIn = data[dateColumnIndexIn] || "";

            // Usa flatpickr para parsear correctamente las fechas
            var rowDateStatusObj = rowDateStatus ? flatpickr.parseDate(rowDateStatus, "d/m/Y H:i:s") : null;
            var startDateStatusObj = startDateStatus ? flatpickr.parseDate(startDateStatus, "d/m/Y H:i:s") : null;
            var endDateStatusObj = endDateStatus ? flatpickr.parseDate(endDateStatus, "d/m/Y H:i:s") : null;

            var rowDateInObj = new Date(rowDateIn);
            var startDateInObj = startDateIn ? new Date(startDateIn) : null;
            var endDateInObj = endDateIn ? new Date(endDateIn) : null;

            // **Lógica de filtrado**
            var statusMatch = (!startDateStatusObj || rowDateStatusObj >= startDateStatusObj) &&
                            (!endDateStatusObj || rowDateStatusObj <= endDateStatusObj);
            
            var inMatch = (!startDateInObj || rowDateInObj >= startDateInObj) &&
                        (!endDateInObj || rowDateInObj <= endDateInObj);

            return statusMatch && inMatch; // Ambas condiciones deben cumplirse
        });

        const updatetab = document.getElementById("refreshemptytrailertable");
        // Función para manejar el estado de los botones (habilitar/deshabilitar)
        function toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector, divSelector) {
            if ($(startInputSelector).val() || $(endInputSelector).val()) {
                $(closeButtonSelector).prop('disabled', true); // Habilita el botón Collapse
            } else {
                $(closeButtonSelector).prop('disabled', false); // Deshabilita el botón Collapse
            }
    
            // Deshabilita el botón Apply hasta que ambos inputs estén llenos
            if ($(startInputSelector).val() && $(endInputSelector).val()) {
                $(applyButtonSelector).prop('disabled', false); // Habilita el botón Apply
            } else if(!$(startInputSelector).val() && !$(endInputSelector).val() && $(divSelector).is(":visible")) {
                $(applyButtonSelector).prop('disabled', false); // Deshabilita el botón Apply
            }else{
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
                    //Este update si debe ir descomentado
                    //updatetab.click();
                    table.draw(); // Redibuja la tabla con el nuevo filtro
                } else {
                    $(startFilterInputSelector).val(startDate); // Actualiza el Start Date en el div de filtros
                    $(endFilterInputSelector).val(endDate); // Actualiza el End Date en el div de filtros
                    $(divSelector).show(); // Muestra el div del filtro
                    //Este update si debe ir descomentado
                    //updatetab.click();
                    table.draw(); // Redibuja la tabla con el nuevo filtro
                }
            } else {
                $(startFilterInputSelector).val(''); // Limpia el input del Start Date asociado al filtro
                $(endFilterInputSelector).val(''); // Limpia el input del End Date asociado al filtro
                table.draw(); // Redibuja la tabla con el nuevo filtro
                $(divSelector).hide(); // Oculta el div del filtro
                $(closeButtonSelector).click(); // Simula un clic en Collapse
                //Este update si debe ir descomentado
                //updatetab.click();
            }
            toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector, divSelector);
        }
    
        // Función para limpiar el filtro (botón X)
        function clearDateRangeFilter(divSelector, startInputSelector, endInputSelector, applyButtonSelector, closeButtonSelector) {
            $(startInputSelector).val(''); // Limpia el input del Start Date
            $(endInputSelector).val(''); // Limpia el input del End Date
            //table.draw(); // Redibuja la tabla con el nuevo filtro
            $(applyButtonSelector).click();
            $(divSelector).hide(); // Oculta el div del filtro
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón Collapse
            $(closeButtonSelector).click(); // Simula un clic en Collapse
            $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
            //Este update si debe ir descomentado
            //updatetab.click();
        }
    
        // Función para manejar clics en botones de cerrar Collapse
        function handleCloseDateRangeCollapse(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector, applyButtonSelector) {
            if (!$(startInputSelector).val() && !$(endInputSelector).val()) {
                $(startFilterInputSelector).val(''); // Limpia el Start Date del filtro
                $(endFilterInputSelector).val(''); // Limpia el End Date del filtro
                if ($(divSelector).is(":visible")) {
                    //Este update si debe ir descomentado
                    //updatetab.click();
                    table.draw(); // Redibuja la tabla con el nuevo filtro
                    $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
                } 
                $(divSelector).hide(); // Oculta el div del filtro
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
                '#emptytrailerfilterinputenddatein', // End Date input en el filtro
                '#applydifilter'
            );
        });
    
        // ------------------ Date Out ------------------
        /*$('#applydofilter').on('click', function () {
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
        });*/
    
        // ------------------ Transaction Date ------------------
        /*$('#applytdfilter').on('click', function () {
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
        });*/
    
        // Detectar cambios en los inputs de las fechas para habilitar o deshabilitar botones
        $('#inputapplydistfilter, #inputapplydienfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydistfilter', '#inputapplydienfilter', '#closeapplydifilter', '#applydifilter', '#emptytrailerfilterdivdatein');
        });
    
        /*$('#inputapplydostfilter, #inputapplydoedfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydostfilter', '#inputapplydoedfilter', '#closeapplydofilter', '#applydofilter');
        });*/
    
        /*$('#inputapplytdstfilter, #inputapplytdedfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplytdstfilter', '#inputapplytdedfilter', '#closeapplytdfilter', '#applytdfilter');
        });*/
    
        // Llamada inicial para verificar los botones
        toggleDateRangeButtons('#inputapplydistfilter', '#inputapplydienfilter', '#closeapplydifilter', '#applydifilter', '#emptytrailerfilterdivdatein');
        //toggleDateRangeButtons('#inputapplydostfilter', '#inputapplydoedfilter', '#closeapplydofilter', '#applydofilter');
        //toggleDateRangeButtons('#inputapplytdstfilter', '#inputapplytdedfilter', '#closeapplytdfilter', '#applytdfilter');
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
                        select.append(`<option value="${item.pk_company}">${item.CoName}</option>`);
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
    //Hacer esta peticios para cargar los carriers de los filtros al cargar la pagina ralentiza su operacion 
    //loadCarriersFilter();

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
                        select.append(`<option value="${item.pk_company}">${item.CoName}</option>`);
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
    //Hacer esta peticios para cargar los locations de los filtros al cargar la pagina ralentiza su operacion 
    //loadLocationsFilter();

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
    /*$('#addmorefiltersemptytrailer').one('click', function() {
        loadAvailabilityIndicatorFilter();
        loadLocationsFilter();
        loadCarriersFilter();
    });*/
    

    // Cargar datos al enfocarse y al cargar la página filters
    $('#inputapplyaifilter').on('focus', loadAvailabilityIndicatorFilter);
    //Hacer esta peticios para cargar los availability indicators de los filtros al cargar la pagina ralentiza su operacion 
    //loadAvailabilityIndicatorFilter();

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
    
    /*
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
    }*/