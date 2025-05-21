var tablewhetaapproval;  // Declara la variable de la tabla fuera de cualquier document.ready


/*document.getElementById('exportfile').addEventListener('click', function () {
    // Obtén la tabla con el id "table_wh_eta_approval_shipments"
    var table = document.getElementById('table_wh_eta_approval_shipments');
    
    // Convierte la tabla HTML en una hoja de cálculo de Excel
    var wb = XLSX.utils.table_to_book(table, { sheet: "WHApptApprovalShipments" });

    // Aplica formato a la tercera columna (suponiendo que la columna de fecha y hora es la tercera)
    var ws = wb.Sheets["WHApptApprovalShipments"];
    
    // Recorre todas las filas y aplica formato a la tercera columna (index 2)
    var range = XLSX.utils.decode_range(ws['!ref']); // Obtiene el rango de la hoja
    for (var row = range.s.r + 1; row <= range.e.r; row++) {
        var cellAddress = { r: row, c: 2 }; // Columna 3, índice 2
        var cell = ws[XLSX.utils.encode_cell(cellAddress)];

        if (cell) {
            // Establecer el formato de la fecha y hora
            cell.z = "yyyy-mm-dd hh:mm:ss"; // El formato que deseas
        }
    }

    // Obtener la fecha y hora actual
    var now = new Date();
    var day = ('0' + now.getDate()).slice(-2);
    var month = ('0' + (now.getMonth() + 1)).slice(-2);
    var year = now.getFullYear();
    var hours = ('0' + now.getHours()).slice(-2);
    var minutes = ('0' + now.getMinutes()).slice(-2);
    var seconds = ('0' + now.getSeconds()).slice(-2);

    
    // Formato: MM-DD-YYYY_HH-MM-SS
    var formattedDateTime = `${month}${day}${year}_${hours}-${minutes}-${seconds}`;

    // Define el nombre del archivo con fecha y hora
    var filename = `WHApptApprovalShipments_${formattedDateTime}.xlsx`;

    // Exporta el archivo Excel con el nombre apropiado
    XLSX.writeFile(wb, filename);
});*/

document.getElementById('exportfile').addEventListener('click', async function () {
    // Obtén la tabla con el id "table_wh_eta_approval_shipments"
    var table = document.getElementById('table_wh_eta_approval_shipments');

    // Crear un nuevo libro de Excel
    var wb = new ExcelJS.Workbook();
    var ws = wb.addWorksheet("WHApptApprovalShipments");

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

    // Formatear la tercera columna (índice 2) para que sea m/d/yyyy h:mm:ss AM/PM
    ws.getColumn(3).eachCell(function (cell, rowNumber) {
        if (rowNumber > 1) { // Evitar el encabezado
            var date = new Date(cell.value);
            if (date instanceof Date && !isNaN(date)) {
                // Establecer el formato de la fecha y hora como m/d/yyyy h:mm:ss AM/PM
                cell.numFmt = 'm/d/yyyy h:mm:ss AM/PM';
            }
        }
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
    var filename = `WHApptApprovalShipments_${formattedDateTime}.xlsx`;

    // Exportar el archivo Excel
    wb.xlsx.writeBuffer().then(function (buffer) {
        var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        var link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    });
});


// Inicializar los tooltips solo para los elementos con la clase memingo
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});

$(document).ready(function () {
    //Busqueda de carries en un nuevo registro
    var doorNumberRoutewhetaURL = $('#whetainputapproveddoornumber').data('url');
    var newlyCreatedDoorNumberIdwheta = null; // Variable para almacenar el ID del carrier recién creado
    var selectedDoorNumberswheta = [];
    var isDoorNumberLoadedwheta = false; // Bandera para controlar la carga

    var isGenericCatalogsLoadedwheta = false; // Bandera para controlar la carga de la nueva funcion 
    loadInfoGenericCatalogs();

    function loadInfoGenericCatalogs(){
        if (isGenericCatalogsLoadedwheta) return; // Evita cargar dos veces

        $.ajax({
            url: 'getInfoGeneric',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var doorNumbersData = data.door_numbers.map(item => ({
                    id: item.gnct_id,
                    text: item.gntc_value
                }));
                data.door_numbers.forEach(function (doornumber) {
                    if (!selectedDoorNumberswheta.includes(doornumber.gntc_value)) {
                        selectedDoorNumberswheta.push(doornumber.gntc_value); // Agregar al arreglo si no está ya
                    }
                });

                let container = $('#ShipmentTypeCheckboxContainer');
                container.empty();  // Limpiar cualquier contenido previo
    
                if (data.shipment_types.length === 0) {
                    container.append('<p>No options available</p>');
                } else {
                    data.shipment_types.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        container.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.gnct_id}" id="shipmenttypecheckbox${item.gnct_id}">
                                <label class="form-check-label" for="shipmenttypecheckbox${item.gnct_id}">
                                    ${item.gntc_value}
                                </label>
                            </div>
                        `);
                    });
                }

                console.log("Door Numbers cargados desde la base de datos:", selectedDoorNumberswheta);

                $('#whetainputapproveddoornumber').select2({
                    placeholder: 'Select a Door Number',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: doorNumbersData, // Pasar los datos directamente
                    dropdownParent: $('#whetaapprovaloffcanvas'),
                    minimumInputLength: 0
                });
    
                isGenericCatalogsLoadedwheta = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los catalogos:', error);
            }
        });
    }

    //loadDoorNumberWHETAOnce();

    function loadDoorNumberWHETAOnce() {
        if (isDoorNumberLoadedwheta) return; // Evita cargar dos veces
    
        $.ajax({
            url: doorNumberRoutewhetaURL,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var doorNumbersData = data.map(item => ({
                    id: item.gnct_id,
                    text: item.gntc_value
                }));
                data.forEach(function (doornumber) {
                    if (!selectedDoorNumberswheta.includes(doornumber.gntc_value)) {
                        selectedDoorNumberswheta.push(doornumber.gntc_value); // Agregar al arreglo si no está ya
                    }
                });
                console.log("Door Numbers cargados desde la base de datos:", selectedDoorNumberswheta);

                $('#whetainputapproveddoornumber').select2({
                    placeholder: 'Select a Door Number',
                    allowClear: true,
                    tags: false, // Permite agregar nuevas opciones
                    data: doorNumbersData, // Pasar los datos directamente
                    dropdownParent: $('#whetaapprovaloffcanvas'),
                    minimumInputLength: 0
                });
    
                isDoorNumberLoadedwheta = true; // Marcar como cargado
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los Door Numbers:', error);
            }
        });
    }

    $('#whetainputapproveddoornumber').on('change', function () {
        var selectedOptionDoorNumberWHETA = $(this).select2('data')[0]; // Obtener la opción seleccionada
        var selectedTextDoorNumberWHETA = selectedOptionDoorNumberWHETA ? selectedOptionDoorNumberWHETA.text : ''; // Obtener el texto (nombre) de la opción seleccionada
        
        // Si no es el nuevo Door Number, lo procesamos
        if (selectedTextDoorNumberWHETA !== newlyCreatedDoorNumberIdwheta &&  selectedTextDoorNumberWHETA.trim() !== '') {
            console.log(selectedTextDoorNumberWHETA);
            if (!selectedDoorNumberswheta.includes(selectedTextDoorNumberWHETA)) {
                selectedDoorNumberswheta.push(selectedTextDoorNumberWHETA);  // Agregar al arreglo solo si no existe
                console.log(selectedDoorNumberswheta);  // Mostrar el arreglo con todos los drivers seleccionados
                saveNewDoorNumberWHETA(selectedTextDoorNumberWHETA);
            }
        }
    });

    // Guardar un nuevo carrier en la base de datos
    function saveNewDoorNumberWHETA(doorNumberWHETA) {
        $.ajax({
            url: '/save-new-doornumberwheta',  // Ruta que manejará el backend
            type: 'POST',
            data: {
                doorNumberWHETA: doorNumberWHETA,
                _token: $('meta[name="csrf-token"]').attr('content')  // Asegúrate de incluir el CSRF token
            },
            success: function (response) {
                console.log(response);

                // Crear una nueva opción para el select2 con el nuevo carrier
                var newOption = new Option(response.newDoorNumberWHETA.gntc_value, response.newDoorNumberWHETA.gnct_id, true, true);

                // Agregar la nueva opción al select2
                $('#whetainputapproveddoornumber').append(newOption).trigger('change');

                // Seleccionar el nuevo carrier automáticamente
                $('#whetainputapproveddoornumber').val(response.newDoorNumberWHETA.gnct_id).trigger('change');

                // Marcar el nuevo ID para evitar que se haga otra solicitud
                newlyCreatedDoorNumberIdwheta = response.newDoorNumberWHETA.gntc_value;
                //loadTrailerOwnersShipment();

                // Cuando el nuevo carrier sea creado, aseguramos que no se haga más AJAX para este carrier
                $('#whetainputapproveddoornumber').on('select2:select', function (e) {
                    var selectedId = e.params.data.id;
                    if (selectedId === newlyCreatedDoorNumberIdwheta) {
                        // Evitar que se reenvíe la solicitud para el nuevo carrier
                        newlyCreatedDoorNumberIdwheta = null;  // Restablecer el ID del carrier creado
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error('Error al guardar el Door Number', error);
            }
        });
    }

        // Verifica si la tabla ya ha sido inicializada antes de inicializarla
        if (!$.fn.dataTable.isDataTable('#table_wh_eta_approval_shipments')) {
            tablewhetaapproval = $('#table_wh_eta_approval_shipments').DataTable({
                paging: false,  // Desactiva la paginación
                searching: true, // Mantiene la búsqueda activada
                info: false,     // Oculta la información
                lengthChange: false, // Desactiva el cambio de cantidad de registros
                columnDefs: [{ targets: [5,6,7,8,9,10,11,12,13,14,15,16,17,18], searchable: true, visible: false }], // Oculta la columna 5
                //responsive: true,  // Habilita la responsividad de la tabla
                autoWidth: false,  // Permite que las celdas se ajusten al contenido
                //fixedColumns: { leftColumns: 2 }, // Fija las dos primeras columnas (ajusta según tus necesidades)
            });
        } else {
            tablewhetaapproval = $('#table_wh_eta_approval_shipments').DataTable();
            tablewhetaapproval.page.len(-1).draw();  // Muestra todos los registros sin paginación
        }
    
        // Aplica el filtro en la columna "Shipment Type" (columna 0)
        function applyshipmenttypes() {
            var filterValues = $('#emptytrailerfilterinputshipmenttypecheckbox').val()
                .split(',')
                .map(value => value.trim()) // Elimina espacios extra
                .filter(value => value !== '') // Elimina valores vacíos
                .join('|'); // Convierte la lista en una regex separada por "|"
    
            if (filterValues) {
                tablewhetaapproval.column(0).search(filterValues, true, false).draw(); // Busca con regex
            } else {
                tablewhetaapproval.column(0).search('').draw(); // Limpia el filtro si está vacío
            }
        }
    
        // Aplica el filtro general a todas las columnas
        $('#searchemptytrailergeneralwh').on('input', function() {
            tablewhetaapproval.search(this.value).draw();
        });
    
        // Aplica el filtro solo a la columna 5 (incluso si está oculta)
        $('#column5').on('input', function() {
            let columnIndex = tablewhetaapproval.column(':contains("secondary shipment id")').index();
            console.log('Columna 5 encontrada en índice:', columnIndex);
    
            if (columnIndex !== undefined) {
                tablewhetaapproval.column(columnIndex).search(this.value).draw();
            } else {
                console.error("No se encontró la columna 5 en la tabla.");
            }
        });

    function loadShypmentTypesFilterCheckbox() { 
        //console.log("sikeeeee")
        var locationsRoute = $('#multiCollapseapplyshipmenttypefiltercheckbox').data('url');
        $.ajax({
            url: locationsRoute,
            type: 'GET',
            success: function (data) {
                let container = $('#ShipmentTypeCheckboxContainer');
                container.empty();  // Limpiar cualquier contenido previo
    
                if (data.length === 0) {
                    container.append('<p>No options available</p>');
                } else {
                    data.forEach(item => {
                        // Crear un checkbox por cada ubicación
                        container.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${item.gnct_id}" id="shipmenttypecheckbox${item.gnct_id}">
                                <label class="form-check-label" for="shipmenttypecheckbox${item.gnct_id}">
                                    ${item.gntc_description}
                                </label>
                            </div>
                        `);
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Error fetching data ShipTypes:', error);
            }
        });
    }
    
    // Ejecutar la función cuando el panel de filtros se haya expandido
    /*$('#closeapplyshipmenttypefiltercheckbox').one('click', function () {
        loadShypmentTypesFilterCheckbox();
    });*/
    //loadShypmentTypesFilterCheckbox();

    // Obtenemos los elementos
    const updatetab = document.getElementById("refreshwhetapprovaltable");
    const $checkboxContainer = $("#ShipmentTypeCheckboxContainer");
    const $filterDiv = $("#emptytrailerfilterdivshipmenttypecheckbox");
    const $inputPk = $("#emptytrailerfilterinputshipmenttypecheckboxpk");
    const $inputLocation = $("#emptytrailerfilterinputshipmenttypecheckbox");
    const $applyButton = $("#applyshipmenttypefiltercheckbox");
    const $closeButton = $("#closeapplyshipmenttypefiltercheckbox");
    const $offcanvas = $("#offcanvasaddmorefilters");
    const $clearButton = $("#emptytrailerfilterbuttonshipmenttypecheckbox");

    // Variable que guarda los elementos que abrirán el offcanvas
    const openOffcanvasElements = $("#emptytrailerfilterinputshipmenttypecheckbox, #emptytrailerfilterbtnshipmenttypecheckbox");

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
            //Este debe ir descomentado
            //updatetab.click();
            applyshipmenttypes();
        } else {
            $filterDiv.hide();
            $inputPk.val("");
            $inputLocation.val("");
            $closeButton.click();
            //Este debe de ir descomentado
            //updatetab.click();
            applyshipmenttypes();
        }
    });

    // Escuchamos el clic en el botón Close
    $closeButton.on("click", function () {
        if (!$filterDiv.is(":visible")) return;

        // Limpiar los inputs si hay datos seleccionados
        if ($inputLocation.val() !== "" || $inputPk.val() !== "") {
            $inputLocation.val("");
            $inputPk.val("");
            //updatetab.click();
            applyshipmenttypes();
        }

        applyshipmenttypes();
        // Ocultar el div si está visible
        $filterDiv.hide();
        //debe de ir descomentado
        // updatetab.click();
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
            applyshipmenttypes();
            //debe de ir descomentado
            // updatetab.click();
        }
    });

});
    
    function updateShipmentWHETATable() {
        // Obtener los valores de los filtros
        const params = new URLSearchParams();

        function addParam(key, value) {
            if (value && value.trim() !== '') {  // Solo agregar si tiene un valor
                params.set(key, value);
            }
        }

        let searchValue = tablewhetaapproval.search(); // Guarda el término de búsqueda
        let filters = tablewhetaapproval.columns().search(); // Guarda los filtros de cada columna


        // Aplicar la función solo a los valores que no estén vacíos
        addParam('searchwh', document.getElementById('searchemptytrailergeneralwh').value);
        addParam('gnct_id_shipment_type', document.getElementById('emptytrailerfilterinputshipmenttypepk').value);
        addParam('stm_id', document.getElementById('emptytrailerfilterinputidstm').value);
        addParam('secondary_shipment_id', document.getElementById('emptytrailerfilterinputsecondaryid').value);
        addParam('id_trailer', document.getElementById('emptytrailerfilterinputidtrailerwh').value);
        addParam('etd_start', document.getElementById('emptytrailerfilterinputstartsdd').value);
        addParam('etd_end', document.getElementById('emptytrailerfilterinputendsdd').value);
        addParam('units', document.getElementById('emptytrailerfilterinputunits').value);
        addParam('pallets', document.getElementById('emptytrailerfilterinputpallets').value);
        addParam('driver_assigned_date_start', document.getElementById('emptytrailerfilterinputstartdriverassigneddate').value);
        addParam('driver_assigned_date_end', document.getElementById('emptytrailerfilterinputenddriverassigneddate').value);
        addParam('pick_up_date_start', document.getElementById('emptytrailerfilterinputstartpud').value);
        addParam('pick_up_date_end', document.getElementById('emptytrailerfilterinputendpud').value);
        addParam('billing_id', document.getElementById('emptytrailerfilterinputbillingid').value);
        addParam('device_number', document.getElementById('emptytrailerfilterinputdevicenumber').value);

        // Agregar shipment types seleccionados
        let selectedShipmentTypes = [];
        $('#ShipmentTypeCheckboxContainer input[type="checkbox"]:checked').each(function () {
            selectedShipmentTypes.push($(this).val());
        });
        if (selectedShipmentTypes.length > 0) {
            params.set('shipment_types', selectedShipmentTypes.join(','));
        }

        if (!params.toString()) {
            params.set('searchwh', ''); // Parámetro ficticio
        }

        // Construir la URL optimizada
        const url = new URL(document.getElementById('refreshwhetapprovaltable').getAttribute('data-url'));
        url.search = params.toString();
        
        /*const searchwh = document.getElementById('searchemptytrailergeneralwh').value;
        const shipmenttypewh = document.getElementById('emptytrailerfilterinputshipmenttypepk').value;
        const idstmwh = document.getElementById('emptytrailerfilterinputidstm').value;
        const secondaryshipmentwh = document.getElementById('emptytrailerfilterinputsecondaryid').value;
        const idtrailerwh = document.getElementById('emptytrailerfilterinputidtrailerwh').value;
        const sddstart = document.getElementById('emptytrailerfilterinputstartsdd').value;
        const sddend = document.getElementById('emptytrailerfilterinputendsdd').value;
        const unitswh = document.getElementById('emptytrailerfilterinputunits').value;
        const palletswh = document.getElementById('emptytrailerfilterinputpallets').value;
        const dadstart = document.getElementById('emptytrailerfilterinputstartdriverassigneddate').value;
        const dadend = document.getElementById('emptytrailerfilterinputenddriverassigneddate').value;
        const pudstart = document.getElementById('emptytrailerfilterinputstartpud').value;
        const pudend = document.getElementById('emptytrailerfilterinputendpud').value;
        const itdstart = document.getElementById('emptytrailerfilterinputstartitd').value;
        const itdend = document.getElementById('emptytrailerfilterinputenditd').value;
        const drdstart = document.getElementById('emptytrailerfilterinputstartdrd').value;
        const drdend = document.getElementById('emptytrailerfilterinputenddrd').value;
        const sydstart = document.getElementById('emptytrailerfilterinputstartsyd').value;
        const sydend = document.getElementById('emptytrailerfilterinputendsyd').value;
        const aedstart = document.getElementById('emptytrailerfilterinputstartaed').value;
        const aedend = document.getElementById('emptytrailerfilterinputendaed').value;
        const oltstart = document.getElementById('emptytrailerfilterinputstartolt').value;
        const oltend = document.getElementById('emptytrailerfilterinputendolt').value;
        const dobstart = document.getElementById('emptytrailerfilterinputstartdob').value;
        const dobend = document.getElementById('emptytrailerfilterinputenddob').value;
        const biwh = document.getElementById('emptytrailerfilterinputbillingid').value;
        const dnwh = document.getElementById('emptytrailerfilterinputdevicenumber').value;

        let selectedShipmentTypes = [];
        $('#ShipmentTypeCheckboxContainer input[type="checkbox"]:checked').each(function () {
            selectedShipmentTypes.push($(this).val()); // Agrega el ID de la ubicación
        });

        const shypmenttypes = selectedShipmentTypes.join(','); // Convertir array en string separado por comas
        
        // Construir la URL con los parámetros de filtro
        const url = new URL(document.getElementById('refreshwhetapprovaltable').getAttribute('data-url'));
        const params = new URLSearchParams(url.search);

        // Agregar los filtros a los parámetros de la URL
        params.set('searchwh', searchwh);
        params.set('gnct_id_shipment_type', shipmenttypewh);
        params.set('shipment_types', shypmenttypes);
        params.set('stm_id', idstmwh);
        params.set('secondary_shipment_id', secondaryshipmentwh);
        params.set('id_trailer', idtrailerwh);
        params.set('etd_start', sddstart);
        params.set('etd_end', sddend);
        params.set('units', unitswh);
        params.set('pallets', palletswh);
        params.set('driver_assigned_date_start', dadstart);
        params.set('driver_assigned_date_end', dadend);
        params.set('pick_up_date_start', pudstart);
        params.set('pick_up_date_end', pudend);
        params.set('intransit_date_start', itdstart);
        params.set('intransit_date_end', itdend);
        params.set('delivered_date_start', drdstart);
        params.set('delivered_date_end', drdend);
        params.set('secured_yarddate_start', sydstart);
        params.set('secured_yarddate_end', sydend);
        params.set('wh_auth_date_start', aedstart);
        params.set('wh_auth_date_end', aedend);
        params.set('offlanding_time_start', oltstart);
        params.set('offlanding_time_end', oltend);
        params.set('billing_date_start', dobstart);
        params.set('billing_date_end', dobend);
        params.set('billing_id', biwh);
        params.set('device_number', dnwh);

        url.search = params.toString();*/
        //console.log(url);
        fetch(url)
            .then(response => response.json())
            .then(data => {

                // Actualiza la tabla de DataTables
                tablewhetaapproval.clear(); // Limpia la tabla actual

                // Aquí va el código para actualizar trailersData
                shipmentsData = data.reduce((acc, shipment) => {
                    acc[shipment.pk_shipment] = shipment;
                    return acc;
                }, {});
                // Actualizar la tabla con los datos filtrados
               /* const tbody = document.getElementById('shipmentWHTableBody');
                tbody.innerHTML = ''; // Limpiar la tabla antes de agregar nuevas filas

                data.forEach(shipment => {
                    const row = `
                        <tr id="trailer-${shipment.pk_shipment}" class="clickable-row" 
                            data-bs-toggle="offcanvas" 
                            data-bs-target="#shipmentwhapptapprovaldetails" 
                            aria-controls="shipmentwhapptapprovaldetails" 
                            data-id="${shipment.pk_shipment || ''}">
                            <td>${shipment.shipmenttype?.gntc_description ?? 'N/A'}</td>
                            <td>${shipment.stm_id || ''}</td>
                            <td>${shipment.etd || ''}</td>
                            <td>${shipment.units || ''}</td>
                            <td>${shipment.pallets || ''}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });*/
                data.forEach(shipment => {
                    // Agregar los datos a la tabla sin atributos
                    const rowNode = tablewhetaapproval.row.add([
                        shipment.shipmenttype?.gntc_description ?? '',  // Shipment Type
                        shipment.stm_id ?? '',                        // STM ID
                        shipment.etd ?? '',                           // Suggested Delivery Date
                        shipment.units ?? '',                         // Units
                        shipment.pallets ?? '',                       // Pallets
                        shipment.secondary_shipment_id ?? '',         // secondary shipment id
                        shipment.id_trailer ?? '',                    // trailer id
                        shipment.billing_id ?? '',                    // billing id
                        shipment.tracker1 ?? '',                      // tracker1
                        shipment.tracker2 ?? '',                      // tracker2
                        shipment.tracker3 ?? '',                      // tracker3
                        shipment.driver_assigned_date ?? '',          // driver assigned date
                        shipment.pick_up_date ?? '',                  // picked up date
                        shipment.intransit_date ?? '',                // in transit date
                        shipment.delivered_date ?? '',                // delivery/received date
                        shipment.secured_yarddate ?? '',              // secured yard date
                        shipment.wh_auth_date ?? '',                  // approved eta date
                        shipment.billing_date ?? '',                  // date of billing
                        shipment.offloading_time ?? ''
                    ]).node(); // Esto devuelve el nodo de la fila agregada

                    // Ahora añadimos los atributos a la fila
                    $(rowNode).attr({
                        'id': `trailer-${shipment.pk_shipment}`,
                        'class': 'clickable-row',
                        'data-bs-toggle': 'offcanvas',
                        'data-bs-target': '#shipmentwhapptapprovaldetails',
                        'aria-controls': 'shipmentwhapptapprovaldetails',
                        'data-id': shipment.pk_shipment
                    });
                });
                tablewhetaapproval.draw(false); // Redibuja la tabla sin reiniciar la paginación

                // Restaurar la búsqueda y los filtros
                /*tablewhetaapproval.search(searchValue).draw(); // Restablece la búsqueda general
                filters.each((value, index) => {
                    if (value) tablewhetaapproval.column(index).search(value).draw(); // Restablece los filtros por columna
                });*/

                $(document).off("click", ".clickable-row").on("click", ".clickable-row", function () {
                    const id = $(this).data("id");
                    const shipment = shipmentsData[id];
                
                    if (shipment) {
                        // Cambiar el título del canvas concatenando el número del envío
                        const titleElement = document.getElementById('shipmentwhapptapprovaldetailstitle');
                        const originalTitle = titleElement.dataset.originalTitle || titleElement.textContent; // Guardar el título original
                        titleElement.dataset.originalTitle = originalTitle; // Almacenar en un atributo de datos personalizados
                        if (shipment.stm_id !== null) {
                            titleElement.textContent = `${originalTitle} - ${shipment.stm_id}`;
                        }
                
                        // Asignamos los datos al offcanvas
                        document.getElementById("offcanvasdetails-pk_shipment").textContent = shipment.pk_shipment;
                        document.getElementById("offcanvasdetails-id_trailer").textContent = shipment.id_trailer;
                        document.getElementById("offcanvasdetails-stm_id").textContent = shipment.stm_id;
                        document.getElementById("offcanvasdetails-etd").textContent = shipment.etd;
                        document.getElementById("offcanvasdetails-units").textContent = shipment.units;
                        document.getElementById("offcanvasdetails-pallets").textContent = shipment.pallets;
                        document.getElementById("offcanvasdetails-driver_assigned_date").textContent = shipment.driver_assigned_date;
                        document.getElementById("offcanvasdetails-intransit_date").textContent = shipment.intransit_date;
                        document.getElementById("offcanvasdetails-gnct_id_shipment_type").textContent = shipment.shipmenttype && shipment.shipmenttype.gntc_description ? shipment.shipmenttype.gntc_description : 'N/A';
                        document.getElementById("offcanvasdetails-pick_up_date").textContent = shipment.pick_up_date;
                
                    } else {
                        console.error(`No data found for shipment ID ${id}`);
                    }
                
                    // Restaurar el título al cerrar el canvas
                    document.getElementById('shipmentwhapptapprovaldetails').addEventListener('hidden.bs.offcanvas', function () {
                        titleElement.textContent = originalTitle; // Restaurar el título original
                    });
                });

                // Vuelve a agregar los listeners de clic después de actualizar la tabla
                /*const rows = document.querySelectorAll(".clickable-row");
                rows.forEach(row => {
                    row.addEventListener("click", function () {
                        const id = this.getAttribute("data-id");
                        const shipment = shipmentsData[id]; // Busca los datos del tráiler
                        //console.log(trailer);
        
                        // Cambiar el título del canvas concatenando el número del tráiler
                    const titleElement = document.getElementById('shipmentwhapptapprovaldetailstitle');
                    const originalTitle = titleElement.dataset.originalTitle || titleElement.textContent; // Guardar el título original
                    titleElement.dataset.originalTitle = originalTitle; // Almacenar en un atributo de datos personalizados
                    //titleElement.textContent = `${originalTitle} - Trailer ${shipment.id_trailer}`;
                    if(shipment.stm_id !== null){
                        titleElement.textContent = `${originalTitle}${shipment.stm_id}`;
                    }
        
                        if (shipment) {
        
                        //console.log(trailer.availabilityIndicator); // Verifica que availabilityIndicator esté cargado
                            // Asigna los datos al offcanvas
                            document.getElementById("offcanvasdetails-pk_shipment").textContent = shipment.pk_shipment;
                            //document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : 'N/A' ;
                            //document.getElementById("pk_location").textContent = trailer.location;
                            //document.getElementById("pk_carrier").textContent = trailer.carrier;
                            document.getElementById("offcanvasdetails-id_trailer").textContent = shipment.id_trailer;
                            document.getElementById("offcanvasdetails-stm_id").textContent = shipment.stm_id;
                            document.getElementById("offcanvasdetails-etd").textContent = shipment.etd;
                            document.getElementById("offcanvasdetails-units").textContent = shipment.units;
                            document.getElementById("offcanvasdetails-pallets").textContent = shipment.pallets;
                            document.getElementById("offcanvasdetails-driver_assigned_date").textContent = shipment.driver_assigned_date;
                            document.getElementById("offcanvasdetails-intransit_date").textContent = shipment.intransit_date;
                            document.getElementById("offcanvasdetails-gnct_id_shipment_type").textContent = shipment.shipmenttype && shipment.shipmenttype.gntc_description ? shipment.shipmenttype.gntc_description : 'N/A';
                            document.getElementById("offcanvasdetails-pick_up_date").textContent = shipment.pick_up_date;
                            //document.getElementById("offcanvas-transaction-date").textContent = trailer.transaction_date;
                            //document.getElementById("offcanvas-username").textContent = trailer.username;
                            
                        } else {
                            console.error(`No data found for shipment ID ${id}`);
                        }
        
                        // Restaurar el título al cerrar el canvas
                        document.getElementById('shipmentwhapptapprovaldetails').addEventListener('hidden.bs.offcanvas', function () {
                            titleElement.textContent = originalTitle; // Restaurar el título original
                        }); 
                    });
                });*/

            })
            .catch(error => console.error('Error:', error));
    }
    
    // Llamar la función cuando se hace clic en el botón de "Refresh" o cuando cambian los filtros
    document.getElementById('refreshwhetapprovaltable').addEventListener('click', updateShipmentWHETATable);
    
    const filterInputs = document.querySelectorAll('#filtersapplied input');
    filterInputs.forEach(input => {
        input.addEventListener('input', updateShipmentWHETATable);
    });

    /*let debounceTimer;

    function debounceUpdate() {
        clearTimeout(debounceTimer); // Cancela el temporizador anterior
        debounceTimer = setTimeout(updateShipmentWHETATable, 1000); // Espera 3 segundos antes de ejecutar la función
    }

    const filterGeneralInputs = document.querySelectorAll('#searchemptytrailergeneralwh');
    filterGeneralInputs.forEach(input => {
        input.addEventListener('input', debounceUpdate);
    });*/
    

    // Actualización automática cada 5 minutos (300,000 ms)
    setInterval(updateShipmentWHETATable, 60000);

    //Formato de fechas
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

    //Mostrar datos del trailer en el canvas
    document.addEventListener("DOMContentLoaded", function () {
        const rows = document.querySelectorAll(".clickable-row");

        rows.forEach(row => {
            row.addEventListener("click", function () {
                const id = this.getAttribute("data-id");
                const shipment = shipmentsData[id]; // Busca los datos del tráiler
                //console.log(trailer);

                // Cambiar el título del canvas concatenando el número del tráiler
            const titleElement = document.getElementById('shipmentwhapptapprovaldetailstitle');
            const originalTitle = titleElement.dataset.originalTitle || titleElement.textContent; // Guardar el título original
            titleElement.dataset.originalTitle = originalTitle; // Almacenar en un atributo de datos personalizados
            //titleElement.textContent = `${originalTitle} - Trailer ${shipment.id_trailer}`;
            if(shipment.stm_id !== null){
                titleElement.textContent = `${originalTitle}${shipment.stm_id}`;
            }

                if (shipment) {

                //console.log(trailer.availabilityIndicator); // Verifica que availabilityIndicator esté cargado
                    // Asigna los datos al offcanvas
                    document.getElementById("offcanvasdetails-pk_shipment").textContent = shipment.pk_shipment;
                    //document.getElementById("pk_availability").textContent = trailer.availability_indicator && trailer.availability_indicator.gnct_id ? trailer.availability_indicator.gnct_id : 'N/A' ;
                    //document.getElementById("pk_location").textContent = trailer.location;
                    //document.getElementById("pk_carrier").textContent = trailer.carrier;
                    document.getElementById("offcanvasdetails-id_trailer").textContent = shipment.id_trailer;
                    document.getElementById("offcanvasdetails-stm_id").textContent = shipment.stm_id;
                    document.getElementById("offcanvasdetails-etd").textContent = shipment.etd;
                    document.getElementById("offcanvasdetails-units").textContent = shipment.units;
                    document.getElementById("offcanvasdetails-pallets").textContent = shipment.pallets;
                    document.getElementById("offcanvasdetails-driver_assigned_date").textContent = shipment.driver_assigned_date;
                    document.getElementById("offcanvasdetails-intransit_date").textContent = shipment.intransit_date;
                    document.getElementById("offcanvasdetails-gnct_id_shipment_type").textContent = shipment.shipmenttype && shipment.shipmenttype.gntc_description ? shipment.shipmenttype.gntc_description : 'N/A';
                    document.getElementById("offcanvasdetails-pick_up_date").textContent = shipment.pick_up_date;
                    //document.getElementById("offcanvas-transaction-date").textContent = trailer.transaction_date;
                    //document.getElementById("offcanvas-username").textContent = trailer.username;
                    
                } else {
                    console.error(`No data found for shipment ID ${id}`);
                }

                // Restaurar el título al cerrar el canvas
                document.getElementById('shipmentwhapptapprovaldetails').addEventListener('hidden.bs.offcanvas', function () {
                    titleElement.textContent = originalTitle; // Restaurar el título original
                }); 
            });
        });
    });

    //Abrir WH ETA Approval offcanvas
    document.getElementById('whetaapprovalbutton').addEventListener('click', function () {
        // Obtener el ID del trailer actualmente seleccionado
        const selectedId = document.getElementById("offcanvasdetails-pk_shipment").textContent.trim();
        //console.log(selectedId);
        const shipment = shipmentsData[selectedId];

        if (shipment) {
            // Cambiar el título del canvas concatenando el número del tráiler
            const titleElement = document.getElementById('whetaapprovaloffcanvasWithBothOptionsLabel');
            const originalTitle = titleElement.dataset.originalTitle || titleElement.textContent; // Guardar el título original
            titleElement.dataset.originalTitle = originalTitle; // Almacenar en un atributo de datos personalizados
            titleElement.textContent = `${originalTitle}${shipment.stm_id || ''   }`;

            // Llenar los campos del canvas de actualización
            document.getElementById('whetainputpkshipment').value = shipment.pk_shipment || '';
            document.getElementById('whaetainputidtrailer').value = shipment.id_trailer || '';
            document.getElementById('whetainputidstm').value = shipment.stm_id || '';;
            document.getElementById('whetainputpallets').value = shipment.pallets || ''; // Ajusta según tu modelo
            document.getElementById('whetainputunits').value = shipment.units || '';
            document.getElementById('whetainputedt').value = shipment.etd || '';
            document.getElementById('whetainputapprovedeta').value = shipment.etd || '';
            //document.getElementById('updateinputavailabilityindicator').value = shipment.gnct_id_avaibility_indicator || '';

            // Mostrar el canvas de actualización
            const WHETAApprovalCanvas = new bootstrap.Offcanvas(document.getElementById('whetaapprovaloffcanvas'));
            WHETAApprovalCanvas.show();

            // Restaurar el título al cerrar el canvas
            document.getElementById('whetaapprovaloffcanvas').addEventListener('hidden.bs.offcanvas', function () {
                titleElement.textContent = originalTitle; // Restaurar el título original
                $('#whetainputapproveddoornumber').val(null).trigger('change');  // Restablecer el valor del select

                const field = document.getElementById("whetainputapproveddoornumber");
                const errorElement = document.getElementById("error-whetainputapproveddoornumber");

                if ($(field).hasClass("searchdoornumberwheta")) {
                        field.classList.remove('is-invalid'); // Remueve la clase en el select
                        errorElement.textContent = ''; // Limpia el mensaje de error
                        $(field).next('.select2-container').find('.select2-selection').removeClass("is-invalid"); // Remueve la clase en el contenedor visual
                }
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
        //'whaetainputidtrailer',
        //'whetainputidstm',
        'whetainputpallets',
        'whetainputunits',
        //'whetainputedt',
        'whetainputapprovedeta',
        'whetainputapproveddoornumber',
    ];

    // Validación de cada campo
    formFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        const errorElement = document.getElementById(`error-${fieldId}`);
        const isSelect2 = $(field).hasClass("searchdoornumberwheta"); // Detecta si es un select2

        if (isSelect2) {
            // Para select2, usa 'change'
            $(field).on('change', function () {
                validateField(field, errorElement, isSelect2);
            });
        } else {
            // Para inputs normales, usa keyup y blur
            field.addEventListener('keyup', function () {
                validateField(field, errorElement, isSelect2);
            });
    
            field.addEventListener('blur', function () {
                validateField(field, errorElement, isSelect2);
            });
        }
    });

    // Función común para validar cada campo
    function validateField(field, errorElement, isSelect2) {
        // Validar si el campo está vacío
        if (field.value.trim() === '') {
            field.classList.add('is-invalid');
            errorElement.textContent = 'This field is required'; // Mensaje de error

            // Si es select2, aplica la clase al contenedor correcto
            /*if (isSelect2) {
                $(field).next('.select2-container').find('.select2-selection').addClass("is-invalid");
            }*/
                if((field.id === 'whetainputapprovedeta'))errorElement.textContent = 'WH Auth Date is required.';

                if((field.id === 'whetainputunits'))errorElement.textContent = 'Units are required.';

                if((field.id === 'whetainputpallets'))errorElement.textContent = 'Pallets are required.';

                // Si es un select2, aplica la clase al contenedor
                if (isSelect2) {
                    $(field).siblings(".select2").find(".select2-selection").addClass("is-invalid");
                    errorElement.textContent = 'Door number is required';
                }
        }
            // Validar si el campo es un número entero y mayor que 0
           else if ((field.id === 'whetainputpallets' || field.id === 'whetainputunits')) {
                const value = field.value.trim();
                
                // Verificar si el valor no es un número o no es entero
                if (isNaN(value) || !Number.isInteger(parseFloat(value))) {
                    //valid = false;
                    field.classList.add('is-invalid');
                    errorElement.textContent = 'This field must be an integer.'; // Mensaje de error
                }
                // Verificar si el valor es 0 o menor
                else if (parseFloat(value) <= 0) {
                    //valid = false;
                    field.classList.add('is-invalid');
                    errorElement.textContent = 'This field must have a valid value.'; // Mensaje de error
                }
                // Validar si whetainputpallets es mayor que whetainputunits
                else if (field.id === 'whetainputpallets' && document.getElementById('whetainputunits').value !== '' && parseFloat(value) > parseFloat(document.getElementById('whetainputunits').value)) {
                    //valid = false;
                    field.classList.add('is-invalid');
                    errorElement.textContent = 'The number of pallets cannot be greater than the number of units.'; // Mensaje de error
                }else {
                    field.classList.remove('is-invalid');
                    errorElement.textContent = '';
                }
            }
        else {
            field.classList.remove('is-invalid');
            errorElement.textContent = ''; // Limpiar el mensaje de error

            if (isSelect2) {
                $(field).next('.select2-container').find('.select2-selection').removeClass("is-invalid");
            }
        }
    }

    // Ejecutar la validación al hacer clic en el botón de "guardar"
    document.getElementById("whetaapprovalbuttonsave").addEventListener("click", function () {
        let valid = true;

        let searchValue = tablewhetaapproval.search(); // Guarda el término de búsqueda
        let filters = tablewhetaapproval.columns().search(); // Guarda los filtros de cada columna    

        // Validar cada campo antes de enviar
        formFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const errorElement = document.getElementById(`error-${fieldId}`);
            const isSelect2 = $(field).hasClass("searchdoornumberwheta"); // Detecta si es un select2

            // Validar el campo
            if (field.value.trim() === '') {
                valid = false;
                field.classList.add('is-invalid');
                errorElement.textContent = 'This field is required';
                
                if((field.id === 'whetainputapprovedeta'))errorElement.textContent = 'WH Auth Date is required.';

                if((field.id === 'whetainputunits'))errorElement.textContent = 'Units are required.';

                if((field.id === 'whetainputpallets'))errorElement.textContent = 'Pallets are required.';

                // Si es un select2, aplica la clase al contenedor
                if (isSelect2) {
                    $(field).siblings(".select2").find(".select2-selection").addClass("is-invalid");
                    errorElement.textContent = 'Door number is required';
                }
            }
            // Validar si el campo es un número entero y mayor que 0
            else if ((field.id === 'whetainputpallets' || field.id === 'whetainputunits')) {
                const value = field.value.trim();
                
                // Verificar si el valor no es un número o no es entero
                if (isNaN(value) || !Number.isInteger(parseFloat(value))) {
                    valid = false;
                    field.classList.add('is-invalid');
                    errorElement.textContent = 'This field must be an integer.'; // Mensaje de error
                }
                // Verificar si el valor es 0 o menor
                else if (parseFloat(value) <= 0) {
                    valid = false;
                    field.classList.add('is-invalid');
                    errorElement.textContent = 'This field must have a valid value.'; // Mensaje de error
                }
                // Validar si whetainputpallets es mayor que whetainputunits
                else if (field.id === 'whetainputpallets' && document.getElementById('whetainputunits').value !== '' && parseFloat(value) > parseFloat(document.getElementById('whetainputunits').value)) {
                    valid = false;
                    field.classList.add('is-invalid');
                    errorElement.textContent = 'The number of pallets cannot be greater than the number of units.'; // Mensaje de error
                }else {
                    field.classList.remove('is-invalid');
                    errorElement.textContent = '';
                }
            }
            else {
                field.classList.remove('is-invalid');
                errorElement.textContent = '';

                // Si es un select2, elimina la clase del contenedor
                if (isSelect2) {
                    $(field).siblings(".select2").find(".select2-selection").removeClass("is-invalid");
                }
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
        const closewhetaapprovalbutton = document.getElementById('closewhetaapprovalbutton');
        const refreshButtonUpdate = document.getElementById('refreshwhetapprovaltable');
        const closeoffcanvaswhetaapprovaldetails = document.getElementById('closeoffcanvaswhetaapprovaldetails');
        const whetaapprovalbutton = $('#whetaapprovalbuttonsave');
        const urlwhetaapproval = whetaapprovalbutton.data('url');

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
                    pk_shipment: document.getElementById("whetainputpkshipment").value,
                    //id_trailer: document.getElementById("whaetainputidtrailer").value,
                    //stm_id: document.getElementById("whetainputidstm").value,
                    whetainputpallets: document.getElementById("whetainputpallets").value,
                    whetainputunits: document.getElementById("whetainputunits").value,
                    //etd: document.getElementById("whetainputedt").value,
                    whetainputapprovedeta: document.getElementById("whetainputapprovedeta").value,
                    whetainputapproveddoornumber: document.getElementById("whetainputapproveddoornumber").value,
                };

                fetch(urlwhetaapproval, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(data),
                })
                    /*.then((response) => {
                        if (response.ok) {
                            Swal.fire("Saved!", "The changes were saved successfully.", "success");
                            closewhetaapprovalbutton.click();
                            closeoffcanvaswhetaapprovaldetails.click();
                            refreshButtonUpdate.click();
                        } else {
                            return response.json().then((data) => {
                                throw new Error(data.message || "Error saving changes.");
                            });
                        }
                    })*/
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
                        shipmentsData = {};

                        tablewhetaapproval.clear();

                        data.shipments.forEach(shipment => {
                            // Guardar los datos actualizados en trailersData
                            shipmentsData[shipment.pk_shipment] = shipment;
                            // Agregar los datos a la tabla sin atributos
                            const rowNode = tablewhetaapproval.row.add([
                                shipment.shipmenttype?.gntc_description ?? '',  // Shipment Type
                                shipment.stm_id ?? '',                        // STM ID
                                shipment.etd ?? '',                           // Suggested Delivery Date
                                shipment.units ?? '',                         // Units
                                shipment.pallets ?? '',                       // Pallets
                                shipment.secondary_shipment_id ?? '',         // secondary shipment id
                                shipment.id_trailer ?? '',                    // trailer id
                                shipment.billing_id ?? '',                    // billing id
                                shipment.tracker1 ?? '',                      // tracker1
                                shipment.tracker2 ?? '',                      // tracker2
                                shipment.tracker3 ?? '',                      // tracker3
                                shipment.driver_assigned_date ?? '',          // driver assigned date
                                shipment.pick_up_date ?? '',                  // picked up date
                                shipment.intransit_date ?? '',                // in transit date
                                shipment.delivered_date ?? '',                // delivery/received date
                                shipment.secured_yarddate ?? '',              // secured yard date
                                shipment.wh_auth_date ?? '',                  // approved eta date
                                shipment.billing_date ?? '',                  // date of billing
                                shipment.offloading_time ?? ''
                            ]).node(); // Esto devuelve el nodo de la fila agregada
    
                            // Ahora añadimos los atributos a la fila
                            $(rowNode).attr({
                                'id': `trailer-${shipment.pk_shipment}`,
                                'class': 'clickable-row',
                                'data-bs-toggle': 'offcanvas',
                                'data-bs-target': '#shipmentwhapptapprovaldetails',
                                'aria-controls': 'shipmentwhapptapprovaldetails',
                                'data-id': shipment.pk_shipment
                            });
                        });

                        tablewhetaapproval.draw(false); // Redibuja la tabla sin reiniciar la paginación

                        // Restaurar la búsqueda y los filtros
                        tablewhetaapproval.search(searchValue).draw(); // Restablece la búsqueda general
                        filters.each((value, index) => {
                            if (value) tablewhetaapproval.column(index).search(value).draw(); // Restablece los filtros por columna
                        });

                        $(document).off("click", ".clickable-row").on("click", ".clickable-row", function () {
                            const id = $(this).data("id");
                            const shipment = shipmentsData[id];
                        
                            if (shipment) {
                                // Cambiar el título del canvas concatenando el número del envío
                                const titleElement = document.getElementById('shipmentwhapptapprovaldetailstitle');
                                const originalTitle = titleElement.dataset.originalTitle || titleElement.textContent; // Guardar el título original
                                titleElement.dataset.originalTitle = originalTitle; // Almacenar en un atributo de datos personalizados
                                if (shipment.stm_id !== null) {
                                    titleElement.textContent = `${originalTitle} - ${shipment.stm_id}`;
                                }
                        
                                // Asignamos los datos al offcanvas
                                document.getElementById("offcanvasdetails-pk_shipment").textContent = shipment.pk_shipment;
                                document.getElementById("offcanvasdetails-id_trailer").textContent = shipment.id_trailer;
                                document.getElementById("offcanvasdetails-stm_id").textContent = shipment.stm_id;
                                document.getElementById("offcanvasdetails-etd").textContent = shipment.etd;
                                document.getElementById("offcanvasdetails-units").textContent = shipment.units;
                                document.getElementById("offcanvasdetails-pallets").textContent = shipment.pallets;
                                document.getElementById("offcanvasdetails-driver_assigned_date").textContent = shipment.driver_assigned_date;
                                document.getElementById("offcanvasdetails-intransit_date").textContent = shipment.intransit_date;
                                document.getElementById("offcanvasdetails-gnct_id_shipment_type").textContent = shipment.shipmenttype && shipment.shipmenttype.gntc_description ? shipment.shipmenttype.gntc_description : 'N/A';
                                document.getElementById("offcanvasdetails-pick_up_date").textContent = shipment.pick_up_date;
                        
                            } else {
                                console.error(`No data found for shipment ID ${id}`);
                            }
                        
                            // Restaurar el título al cerrar el canvas
                            document.getElementById('shipmentwhapptapprovaldetails').addEventListener('hidden.bs.offcanvas', function () {
                                titleElement.textContent = originalTitle; // Restaurar el título original
                            });
                        });
                        //Swal.fire("Saved!", "The changes were saved successfully.", "success");
                        closewhetaapprovalbutton.click();
                        closeoffcanvaswhetaapprovaldetails.click();
                        //refreshButtonUpdate.click();
                    })
                    .catch((error) => {
                        console.log(error); // Muestra el error
                        if (error.errors) {
                            Object.keys(error.errors).forEach(field => {
                                const fieldId = field; 
                                const errorMessages = error.errors[field]; // Los mensajes de error
                                const errorElement = document.getElementById(`error-${fieldId}`);
                                const fieldElement = document.getElementById(fieldId);
                                const isSelect2 = $(fieldElement).hasClass("searchdoornumberwheta"); // Detecta si es select2
                    
                                if (fieldElement) {
                                    fieldElement.classList.add('is-invalid'); // Marca el campo como inválido
                                    errorElement.textContent = errorMessages.join(', '); // Muestra el error en el campo

                                    // Si es un select2, aplica la clase a la interfaz de select2
                                    if (isSelect2) {
                                        $(fieldElement).next('.select2-container').find('.select2-selection').addClass("is-invalid");
                                    }
                                }
                            });
                        } else {
                            Swal.fire("Error", error.message || "An unknown error occurred", "error");
                        }
                        //Swal.fire("Error", error.message, "error");
                    });
            }
        });
    });

    // Limpiar los mensajes de error al cerrar el modal (tanto al hacer clic en el botón de cerrar como al hacer clic fuera del modal)
    $(document).ready(function() {
        const updatecanvas = document.getElementById("whetaapprovaloffcanvas");
        // Verifica si el evento 'hidden.bs.modal' se está registrando correctamente
        updatecanvas.addEventListener("hidden.bs.offcanvas", function () {
            //console.log("Modal se ha cerrado");  // Asegúrate de que este log se imprime
    
            // Limpiar los mensajes de error y las clases de error
            const fields = [
                'whaetainputidtrailer',
                'whetainputidstm',
                'whetainputpallets',
                'whetainputunits',
                'whetainputedt',
                'whetainputapprovedeta',
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

    //Manejo de filtros de inputs simples
    $(document).ready(function () {

        // Verifica si la tabla ya ha sido inicializada antes de inicializarla
        if (!$.fn.dataTable.isDataTable('#table_wh_eta_approval_shipments')) {
            tablewhetaapproval = $('#table_wh_eta_approval_shipments').DataTable({
                paging: false,  // Desactiva la paginación
                searching: true, // Mantiene la búsqueda activada
                info: false,     // Oculta la información
                lengthChange: false, // Desactiva el cambio de cantidad de registros
                columnDefs: [{ targets: [5,6,7,8,9,10,11,12,13,14,15,16,17,18], searchable: true, visible: false }], // Oculta la columna 5
                //responsive: true,  // Habilita la responsividad de la tabla
                autoWidth: false,  // Permite que las celdas se ajusten al contenido
                //fixedColumns: { leftColumns: 2 }, // Fija las dos primeras columnas (ajusta según tus necesidades)
            });
        } else {
            tablewhetaapproval = $('#table_wh_eta_approval_shipments').DataTable();
            tablewhetaapproval.page.len(-1).draw();  // Muestra todos los registros sin paginación
        }

        /*let columnIndex = tablewhetaapproval.column(':contains("secondary shipment id")').index();
            console.log('Columna 5 encontrada en índice:', columnIndex);
    
            if (columnIndex !== undefined) {
                tablewhetaapproval.column(columnIndex).search(this.value).draw();
            } else {
                console.error("No se encontró la columna 5 en la tabla.");
            }*/

        const updatetab = document.getElementById("refreshwhetapprovaltable");
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
                    //Este debe ir descomentado
                    //updatetab.click();
                    tablewhetaapproval.column(1).search($('#emptytrailerfilterinputidstm').val()).draw();
                    tablewhetaapproval.column(3).search($('#emptytrailerfilterinputunits').val()).draw();
                    tablewhetaapproval.column(4).search($('#emptytrailerfilterinputpallets').val()).draw();
                    
                    tablewhetaapproval.column(5).search($('#emptytrailerfilterinputsecondaryid').val()).draw();
                    tablewhetaapproval.column(6).search($('#emptytrailerfilterinputidtrailerwh').val()).draw();
                    tablewhetaapproval.column(7).search($('#emptytrailerfilterinputbillingid').val()).draw();
                    tablewhetaapproval.column(8).search($('#emptytrailerfilterinputdevicenumber').val()).draw();
                    tablewhetaapproval.column(9).search($('#emptytrailerfilterinputdevicenumber1').val()).draw();
                    tablewhetaapproval.column(10).search($('#emptytrailerfilterinputdevicenumber2').val()).draw();
                } else {
                    // Si el div no está visible, muestra el div y coloca el valor
                    $(inputFilterSelector).val(inputValue);
                    $(divSelector).show();
                    //Este debe de ir descomentado
                    //updatetab.click();
                    tablewhetaapproval.column(1).search($('#emptytrailerfilterinputidstm').val()).draw();
                    tablewhetaapproval.column(3).search($('#emptytrailerfilterinputunits').val()).draw();
                    tablewhetaapproval.column(4).search($('#emptytrailerfilterinputpallets').val()).draw();

                    tablewhetaapproval.column(5).search($('#emptytrailerfilterinputsecondaryid').val()).draw();
                    tablewhetaapproval.column(6).search($('#emptytrailerfilterinputidtrailerwh').val()).draw();
                    tablewhetaapproval.column(7).search($('#emptytrailerfilterinputbillingid').val()).draw();
                    tablewhetaapproval.column(8).search($('#emptytrailerfilterinputdevicenumber').val()).draw();
                    tablewhetaapproval.column(9).search($('#emptytrailerfilterinputdevicenumber1').val()).draw();
                    tablewhetaapproval.column(10).search($('#emptytrailerfilterinputdevicenumber2').val()).draw();
                }
            } else {
                // Si el campo está vacío, vacía el input del filtro y oculta el div
                $(inputFilterSelector).val('');
                tablewhetaapproval.column(1).search($('#emptytrailerfilterinputidstm').val()).draw();
                tablewhetaapproval.column(3).search($('#emptytrailerfilterinputunits').val()).draw();
                tablewhetaapproval.column(4).search($('#emptytrailerfilterinputpallets').val()).draw();

                tablewhetaapproval.column(5).search($('#emptytrailerfilterinputsecondaryid').val()).draw();
                tablewhetaapproval.column(6).search($('#emptytrailerfilterinputidtrailerwh').val()).draw();
                tablewhetaapproval.column(7).search($('#emptytrailerfilterinputbillingid').val()).draw();
                tablewhetaapproval.column(8).search($('#emptytrailerfilterinputdevicenumber').val()).draw();
                tablewhetaapproval.column(9).search($('#emptytrailerfilterinputdevicenumber1').val()).draw();
                tablewhetaapproval.column(10).search($('#emptytrailerfilterinputdevicenumber2').val()).draw();
                $(divSelector).hide();
                $(closeButtonSelector).click(); // Simula un clic en Collapse
                //Este debe ir descomentado
                //updatetab.click();
            }
        }
    
        // Función genérica para manejar clics en botones X
        function handleClearButton(divSelector, inputSelector, applyButtonSelector, closeButtonSelector) {
            $(inputSelector).val('');
            tablewhetaapproval.column(1).search($('#emptytrailerfilterinputidstm').val()).draw();
            tablewhetaapproval.column(3).search($('#emptytrailerfilterinputunits').val()).draw();
            tablewhetaapproval.column(4).search($('#emptytrailerfilterinputpallets').val()).draw();

            tablewhetaapproval.column(5).search($('#emptytrailerfilterinputsecondaryid').val()).draw();
            tablewhetaapproval.column(6).search($('#emptytrailerfilterinputidtrailerwh').val()).draw();
            tablewhetaapproval.column(7).search($('#emptytrailerfilterinputbillingid').val()).draw();
            tablewhetaapproval.column(8).search($('#emptytrailerfilterinputdevicenumber').val()).draw();
            tablewhetaapproval.column(9).search($('#emptytrailerfilterinputdevicenumber1').val()).draw();
            tablewhetaapproval.column(10).search($('#emptytrailerfilterinputdevicenumber2').val()).draw();
            $(divSelector).hide();
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón
            $(applyButtonSelector).click(); // Simula clic en Apply
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
                    //Debe de ir descomentar
                    //updatetab.click();
                    tablewhetaapproval.column(1).search($('#emptytrailerfilterinputidstm').val()).draw();
                    tablewhetaapproval.column(3).search($('#emptytrailerfilterinputunits').val()).draw();
                    tablewhetaapproval.column(4).search($('#emptytrailerfilterinputpallets').val()).draw();

                    tablewhetaapproval.column(5).search($('#emptytrailerfilterinputsecondaryid').val()).draw();
                    tablewhetaapproval.column(6).search($('#emptytrailerfilterinputidtrailerwh').val()).draw();
                    tablewhetaapproval.column(7).search($('#emptytrailerfilterinputbillingid').val()).draw();
                    tablewhetaapproval.column(8).search($('#emptytrailerfilterinputdevicenumber').val()).draw();
                    tablewhetaapproval.column(9).search($('#emptytrailerfilterinputdevicenumber1').val()).draw();
                    tablewhetaapproval.column(10).search($('#emptytrailerfilterinputdevicenumber2').val()).draw();
                } 
                $(divSelector).hide(); // Oculta el div del filtro
               
            }
        }

        // ID STM
        $('#inputapplyidstmfilter').on('input', function () {
            toggleApplyButton('#inputapplyidstmfilter', '#closeapplyidstmfilter');
        });
    
        $('#applyidstmfilter').on('click', function () {
            handleApplyButton('#inputapplyidstmfilter', '#emptytrailerfilterdividstm', '#emptytrailerfilterinputidstm', '#closeapplyidstmfilter');
        });
    
        $('#emptytrailerfilterbuttonidstm').on('click', function () {
            handleClearButton('#emptytrailerfilterdividstm', '#inputapplyidstmfilter', '#applyidstmfilter', '#closeapplyidstmfilter');
        });
    
        $('#emptytrailerfilterbtnidstm, #emptytrailerfilterinputidstm').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputapplyidstmfilter');
        });

        $('#closeapplyidstmfilter').on('click', function () {
            handleCloseCollapseButton('#inputapplyidstmfilter', '#emptytrailerfilterdividstm', '#emptytrailerfilterinputidstm');
        });
    
        // Secondary Shipment ID
        $('#inputapplysecondaryshipmentfilter').on('input', function () {
            toggleApplyButton('#inputapplysecondaryshipmentfilter', '#closeapplysecondaryshipmentfilter');
        });
    
        $('#applysecondaryshipmentfilter').on('click', function () {
            handleApplyButton('#inputapplysecondaryshipmentfilter', '#emptytrailerfilterdivsecondaryid', '#emptytrailerfilterinputsecondaryid', '#closeapplysecondaryshipmentfilter');
        });
    
        $('#emptytrailerfilterbuttonsecondaryid').on('click', function () {
            handleClearButton('#emptytrailerfilterdivsecondaryid', '#inputapplysecondaryshipmentfilter', '#applysecondaryshipmentfilter', '#closeapplysecondaryshipmentfilter');
        });
    
        $('#emptytrailerfilterbtnsecondaryid, #emptytrailerfilterinputsecondaryid').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputapplysecondaryshipmentfilter');
        });

        $('#closeapplysecondaryshipmentfilter').on('click', function () {
            handleCloseCollapseButton('#inputapplysecondaryshipmentfilter', '#emptytrailerfilterdivsecondaryid', '#emptytrailerfilterinputsecondaryid');
        });
    
        // ID Trailer
        $('#inputapplyidtrailerwhfilter').on('input', function () {
            toggleApplyButton('#inputapplyidtrailerwhfilter', '#closeapplyidtrailerwhfilter');
        });
    
        $('#applyidtrailerwhfilter').on('click', function () {
            handleApplyButton('#inputapplyidtrailerwhfilter', '#emptytrailerfilterdividtrailerwh', '#emptytrailerfilterinputidtrailerwh', '#closeapplyidtrailerwhfilter');
        });
    
        $('#emptytrailerfilterbuttonidtrailerwh').on('click', function () {
            handleClearButton('#emptytrailerfilterdividtrailerwh', '#inputapplyidtrailerwhfilter', '#applyidtrailerwhfilter', '#closeapplyidtrailerwhfilter');
        });
    
        $('#emptytrailerfilterbtnidtrailerwh, #emptytrailerfilterinputidtrailerwh').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputapplyidtrailerwhfilter');
        });

        $('#closeapplyidtrailerwhfilter').on('click', function () {
            handleCloseCollapseButton('#inputapplyidtrailerwhfilter', '#emptytrailerfilterdividtrailerwh', '#emptytrailerfilterinputidtrailerwh');
        });
    
        // Units
        $('#inputunitsfilter').on('input', function () {
            toggleApplyButton('#inputunitsfilter', '#closeapplyunitsfilter');
        });
    
        $('#applyunitsfilter').on('click', function () {
            handleApplyButton('#inputunitsfilter', '#emptytrailerfilterdivunits', '#emptytrailerfilterinputunits', '#closeapplyunitsfilter');
        });
    
        $('#emptytrailerfilterbuttonunits').on('click', function () {
            handleClearButton('#emptytrailerfilterdivunits', '#inputunitsfilter', '#applyunitsfilter', '#closeapplyunitsfilter');
        });
    
        $('#emptytrailerfilterbtnunits, #emptytrailerfilterinputunits').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputunitsfilter');
        });

        $('#closeapplyunitsfilter').on('click', function () {
            handleCloseCollapseButton('#inputunitsfilter', '#emptytrailerfilterdivunits', '#emptytrailerfilterinputunits');
        });
    
        // Pallets
        $('#inputpalletsfilter').on('input', function () {
            toggleApplyButton('#inputpalletsfilter', '#closeapplypalletsfilter');
        });
    
        $('#applypalletsfilter').on('click', function () {
            handleApplyButton('#inputpalletsfilter', '#emptytrailerfilterdivpallets', '#emptytrailerfilterinputpallets', '#closeapplypalletsfilter');
        });
    
        $('#emptytrailerfilterbuttonpallets').on('click', function () {
            handleClearButton('#emptytrailerfilterdivpallets', '#inputpalletsfilter', '#applypalletsfilter', '#closeapplypalletsfilter');
        });
    
        $('#emptytrailerfilterbtnpallets, #emptytrailerfilterinputpallets').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputpalletsfilter');
        });
        
        $('#closeapplypalletsfilter').on('click', function () {
            handleCloseCollapseButton('#inputpalletsfilter', '#emptytrailerfilterdivpallets', '#emptytrailerfilterinputpallets');
        });

        // Billing ID
        $('#inputbillingidfilter').on('input', function () {
            toggleApplyButton('#inputbillingidfilter', '#closeapplybillingidfilter');
        });
    
        $('#applybillingidfilter').on('click', function () {
            handleApplyButton('#inputbillingidfilter', '#emptytrailerfilterdivbillingid', '#emptytrailerfilterinputbillingid', '#closeapplybillingidfilter');
        });
    
        $('#emptytrailerfilterbuttonbillingid').on('click', function () {
            handleClearButton('#emptytrailerfilterdivbillingid', '#inputbillingidfilter', '#applybillingidfilter', '#closeapplybillingidfilter');
        });
    
        $('#emptytrailerfilterbtnbillingid, #emptytrailerfilterinputbillingid').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputbillingidfilter');
        });
        
        $('#closeapplybillingidfilter').on('click', function () {
            handleCloseCollapseButton('#inputbillingidfilter', '#emptytrailerfilterdivbillingid', '#emptytrailerfilterinputbillingid');
        });

        // tracker 1
        $('#inputdevicenumberfilter').on('input', function () {
            toggleApplyButton('#inputdevicenumberfilter', '#closeapplydevicenumberfilter');
        });
    
        $('#applydevicenumberfilter').on('click', function () {
            handleApplyButton('#inputdevicenumberfilter', '#emptytrailerfilterdivdevicenumber', '#emptytrailerfilterinputdevicenumber', '#closeapplydevicenumberfilter');
        });
    
        $('#emptytrailerfilterbuttondevicenumber').on('click', function () {
            handleClearButton('#emptytrailerfilterdivdevicenumber', '#inputdevicenumberfilter', '#applydevicenumberfilter', '#closeapplydevicenumberfilter');
        });
    
        $('#emptytrailerfilterbtndevicenumber, #emptytrailerfilterinputdevicenumber').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputdevicenumberfilter');
        });
        
        $('#closeapplydevicenumberfilter').on('click', function () {
            handleCloseCollapseButton('#inputdevicenumberfilter', '#emptytrailerfilterdivdevicenumber', '#emptytrailerfilterinputdevicenumber');
        });


        // tracker 2
        $('#inputdevicenumberfilter1').on('input', function () {
            toggleApplyButton('#inputdevicenumberfilter1', '#closeapplydevicenumberfilter1');
        });
    
        $('#applydevicenumberfilter1').on('click', function () {
            handleApplyButton('#inputdevicenumberfilter1', '#emptytrailerfilterdivdevicenumber1', '#emptytrailerfilterinputdevicenumber1', '#closeapplydevicenumberfilter1');
        });
    
        $('#emptytrailerfilterbuttondevicenumber1').on('click', function () {
            handleClearButton('#emptytrailerfilterdivdevicenumber1', '#inputdevicenumberfilter1', '#applydevicenumberfilter1', '#closeapplydevicenumberfilter1');
        });
    
        $('#emptytrailerfilterbtndevicenumber1, #emptytrailerfilterinputdevicenumber1').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputdevicenumberfilter1');
        });
        
        $('#closeapplydevicenumberfilter1').on('click', function () {
            handleCloseCollapseButton('#inputdevicenumberfilter1', '#emptytrailerfilterdivdevicenumber1', '#emptytrailerfilterinputdevicenumber1');
        });


        // tracker3
        $('#inputdevicenumberfilter2').on('input', function () {
            toggleApplyButton('#inputdevicenumberfilter2', '#closeapplydevicenumberfilter2');
        });
    
        $('#applydevicenumberfilter2').on('click', function () {
            handleApplyButton('#inputdevicenumberfilter2', '#emptytrailerfilterdivdevicenumber2', '#emptytrailerfilterinputdevicenumber2', '#closeapplydevicenumberfilter2');
        });
    
        $('#emptytrailerfilterbuttondevicenumber2').on('click', function () {
            handleClearButton('#emptytrailerfilterdivdevicenumber2', '#inputdevicenumberfilter2', '#applydevicenumberfilter2', '#closeapplydevicenumberfilter2');
        });
    
        $('#emptytrailerfilterbtndevicenumber2, #emptytrailerfilterinputdevicenumber2').on('click', function () {
            handleFilterButtonClick('offcanvasaddmorefilters', '#inputdevicenumberfilter2');
        });
        
        $('#closeapplydevicenumberfilter2').on('click', function () {
            handleCloseCollapseButton('#inputdevicenumberfilter2', '#emptytrailerfilterdivdevicenumber2', '#emptytrailerfilterinputdevicenumber2');
        });
    });

    //Funcion para buscar el Shipment Type en la pantalla de WH ETA Approval en el filtro
    function loadShipmentTypeFilter() {
        var shipmentTypeRoute = $('#inputapplyshipmenttypefilter').data('url');
          $.ajax({
              url: shipmentTypeRoute,
              method: 'GET',
              success: function (data) {
                  let select = $('#inputapplyshipmenttypefilter');
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
    $('#inputapplyshipmenttypefilter').on('focus', loadShipmentTypeFilter);
    $(document).ready(function () {
        //loadShipmentTypeFilter();
    });

    //Filtros de select Shipment Type
    //jhgbwvefqvrjrhegwvfeqcwdfevwgrbehtrnjymrnthegvwfeqcvwgrbet
    $(document).ready(function () {
        // Obtenemos los elementos
        const updatetab = document.getElementById("refreshwhetapprovaltable");
        const $selectElement = $("#inputapplyshipmenttypefilter");
        const $filterDiv = $("#emptytrailerfilterdivshipmenttype");
        const $inputPk = $("#emptytrailerfilterinputshipmenttypepk");
        const $inputLocation = $("#emptytrailerfilterinputshipmenttype");
        const $applyButton = $("#applyshipmenttypefilter");
        const $closeButton = $("#closeapplyshipmenttypefilter");
        const $offcanvas = $("#offcanvasaddmorefilters");
        const $clearButton = $("#emptytrailerfilterbuttonshipmenttype");
    
        // Variable que guarda los elementos que abrirán el offcanvas
        const openOffcanvasElements = $("#emptytrailerfilterinputshipmenttype, #emptytrailerfilterbtnshipmenttype");
    
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

    //Manejo Filtro de fechas datetime 
    $(document).ready(function () {
        // Verifica si la tabla ya ha sido inicializada antes de inicializarla
        if (!$.fn.dataTable.isDataTable('#table_wh_eta_approval_shipments')) {
            tablewhetaapproval = $('#table_wh_eta_approval_shipments').DataTable({
                paging: false,  // Desactiva la paginación
                searching: true, // Mantiene la búsqueda activada
                info: false,     // Oculta la información
                lengthChange: false, // Desactiva el cambio de cantidad de registros
                columnDefs: [{ targets: [5,6,7,8,9,10,11,12,13,14,15,16,17,18], searchable: true, visible: false }], // Oculta la columna 5
                //responsive: true,  // Habilita la responsividad de la tabla
                autoWidth: false,  // Permite que las celdas se ajusten al contenido
                //fixedColumns: { leftColumns: 2 }, // Fija las dos primeras columnas (ajusta según tus necesidades)
            });
        } else {
            tablewhetaapproval = $('#table_wh_eta_approval_shipments').DataTable();
            tablewhetaapproval.page.len(-1).draw();  // Muestra todos los registros sin paginación
        }

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var startDateStatus = $('#emptytrailerfilterinputstartsdd').val();
            var endDateStatus = $('#emptytrailerfilterinputendsdd').val();
            var dateColumnIndexStatus = 2;
            var rowDateStatus = data[dateColumnIndexStatus] || "";

            // Usa flatpickr para parsear correctamente las fechas
            var rowDateStatusObj = rowDateStatus ? flatpickr.parseDate(rowDateStatus, "d/m/Y H:i:s") : null;
            var startDateStatusObj = startDateStatus ? flatpickr.parseDate(startDateStatus, "d/m/Y H:i:s") : null;
            var endDateStatusObj = endDateStatus ? flatpickr.parseDate(endDateStatus, "d/m/Y H:i:s") : null;

            // **Lógica de filtrado**
            var statusMatch = (!startDateStatusObj || rowDateStatusObj >= startDateStatusObj) &&
                            (!endDateStatusObj || rowDateStatusObj <= endDateStatusObj);

            // **Filtro Driver Assigned Date**
            var startDateDriverAssigned = $('#emptytrailerfilterinputstartdriverassigneddate').val();
            var endDateDriverAssigned = $('#emptytrailerfilterinputenddriverassigneddate').val();
            var rowDateDriverAssigned = data[11] || "";

            var rowDateDriverAssignedObj = rowDateDriverAssigned ? flatpickr.parseDate(rowDateDriverAssigned, "d/m/Y H:i:s") : null;
            var startDateDriverAssignedObj = startDateDriverAssigned ? flatpickr.parseDate(startDateDriverAssigned, "d/m/Y H:i:s") : null;
            var endDateDriverAssignedObj = endDateDriverAssigned ? flatpickr.parseDate(endDateDriverAssigned, "d/m/Y H:i:s") : null;

            var driverAssignedMatch = (!startDateDriverAssignedObj || rowDateDriverAssignedObj >= startDateDriverAssignedObj) &&
                                    (!endDateDriverAssignedObj || rowDateDriverAssignedObj <= endDateDriverAssignedObj);

            // **Filtro PUD**
            var startDatePUD = $('#emptytrailerfilterinputstartpud').val();
            var endDatePUD = $('#emptytrailerfilterinputendpud').val();
            var rowDatePUD = data[12] || "";

            var rowDatePUDObj = rowDatePUD ? flatpickr.parseDate(rowDatePUD, "d/m/Y H:i:s") : null;
            var startDatePUDObj = startDatePUD ? flatpickr.parseDate(startDatePUD, "d/m/Y H:i:s") : null;
            var endDatePUDObj = endDatePUD ? flatpickr.parseDate(endDatePUD, "d/m/Y H:i:s") : null;

            var PUDMatch = (!startDatePUDObj || rowDatePUDObj >= startDatePUDObj) &&
                        (!endDatePUDObj || rowDatePUDObj <= endDatePUDObj);

            // **Filtro ITD**
            var startDateITD = $('#emptytrailerfilterinputstartitd').val();
            var endDateITD = $('#emptytrailerfilterinputenditd').val();
            var rowDateITD = data[13] || "";

            var rowDateITDObj = rowDateITD ? flatpickr.parseDate(rowDateITD, "d/m/Y H:i:s") : null;
            var startDateITDObj = startDateITD ? flatpickr.parseDate(startDateITD, "d/m/Y H:i:s") : null;
            var endDateITDObj = endDateITD ? flatpickr.parseDate(endDateITD, "d/m/Y H:i:s") : null;

            var ITDMatch = (!startDateITDObj || rowDateITDObj >= startDateITDObj) &&
                        (!endDateITDObj || rowDateITDObj <= endDateITDObj);

            // **Filtro DRD**
            var startDateDRD = $('#emptytrailerfilterinputstartdrd').val();
            var endDateDRD = $('#emptytrailerfilterinputenddrd').val();
            var rowDateDRD = data[14] || "";

            var rowDateDRDObj = rowDateDRD ? flatpickr.parseDate(rowDateDRD, "d/m/Y H:i:s") : null;
            var startDateDRDObj = startDateDRD ? flatpickr.parseDate(startDateDRD, "d/m/Y H:i:s") : null;
            var endDateDRDObj = endDateDRD ? flatpickr.parseDate(endDateDRD, "d/m/Y H:i:s") : null;

            var DRDMatch = (!startDateDRDObj || rowDateDRDObj >= startDateDRDObj) &&
                        (!endDateDRDObj || rowDateDRDObj <= endDateDRDObj);

            // **Filtro SYD**
            var startDateSYD = $('#emptytrailerfilterinputstartsyd').val();
            var endDateSYD = $('#emptytrailerfilterinputendsyd').val();
            var rowDateSYD = data[15] || "";

            var rowDateSYDObj = rowDateSYD ? flatpickr.parseDate(rowDateSYD, "d/m/Y H:i:s") : null;
            var startDateSYDObj = startDateSYD ? flatpickr.parseDate(startDateSYD, "d/m/Y H:i:s") : null;
            var endDateSYDObj = endDateSYD ? flatpickr.parseDate(endDateSYD, "d/m/Y H:i:s") : null;

            var SYDMatch = (!startDateSYDObj || rowDateSYDObj >= startDateSYDObj) &&
                        (!endDateSYDObj || rowDateSYDObj <= endDateSYDObj);

            // **Filtro AED**
            var startDateAED = $('#emptytrailerfilterinputstartaed').val();
            var endDateAED = $('#emptytrailerfilterinputendaed').val();
            var rowDateAED = data[16] || "";

            var rowDateAEDObj = rowDateAED ? flatpickr.parseDate(rowDateAED, "d/m/Y H:i:s") : null;
            var startDateAEDObj = startDateAED ? flatpickr.parseDate(startDateAED, "d/m/Y H:i:s") : null;
            var endDateAEDObj = endDateAED ? flatpickr.parseDate(endDateAED, "d/m/Y H:i:s") : null;

            var AEDMatch = (!startDateAEDObj || rowDateAEDObj >= startDateAEDObj) &&
                        (!endDateAEDObj || rowDateAEDObj <= endDateAEDObj);

            // **Filtro DOB**
            var startDateDOB = $('#emptytrailerfilterinputstartdob').val();
            var endDateDOB = $('#emptytrailerfilterinputenddob').val();
            var rowDateDOB = data[17] || "";

            var rowDateDOBObj = rowDateDOB ? flatpickr.parseDate(rowDateDOB, "d/m/Y H:i:s") : null;
            var startDateDOBObj = startDateDOB ? flatpickr.parseDate(startDateDOB, "d/m/Y H:i:s") : null;
            var endDateDOBObj = endDateDOB ? flatpickr.parseDate(endDateDOB, "d/m/Y H:i:s") : null;

            var DOBMatch = (!startDateDOBObj || rowDateDOBObj >= startDateDOBObj) &&
                        (!endDateDOBObj || rowDateDOBObj <= endDateDOBObj);

            // **Filtro para emptytrailerfilterinputstartolt y emptytrailerfilterinputendolt**
            var startTimeOLT = $('#emptytrailerfilterinputstartolt').val();
            var endTimeOLT = $('#emptytrailerfilterinputendolt').val();
            var rowTimeOLT = data[18] || "";  // Asume que la columna 5 contiene el valor de tiempo para OLT

            // Convierte las fechas de tipo string en objetos Date
            var rowTimeOLTObj = rowTimeOLT ? new Date("1970-01-01T" + rowTimeOLT + "Z") : null;
            var startTimeOLTObj = startTimeOLT ? new Date("1970-01-01T" + startTimeOLT + "Z") : null;
            var endTimeOLTObj = endTimeOLT ? new Date("1970-01-01T" + endTimeOLT + "Z") : null;

            var oltMatch = (!startTimeOLTObj || rowTimeOLTObj >= startTimeOLTObj) &&
                        (!endTimeOLTObj || rowTimeOLTObj <= endTimeOLTObj);


            return statusMatch && oltMatch && driverAssignedMatch && PUDMatch && ITDMatch && DRDMatch && SYDMatch && AEDMatch && DOBMatch;
        });

        const updatetab = document.getElementById("refreshwhetapprovaltable");
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
                    //Debe ir descomentado
                    //updatetab.click();
                    tablewhetaapproval.draw();
                } else {
                    $(startFilterInputSelector).val(startDate); // Actualiza el Start Date en el div de filtros
                    $(endFilterInputSelector).val(endDate); // Actualiza el End Date en el div de filtros
                    $(divSelector).show(); // Muestra el div del filtro
                    //debe ir descomentado
                    //updatetab.click();
                    tablewhetaapproval.draw();
                }
            } else {
                $(startFilterInputSelector).val(''); // Limpia el input del Start Date asociado al filtro
                $(endFilterInputSelector).val(''); // Limpia el input del End Date asociado al filtro
                tablewhetaapproval.draw();
                $(divSelector).hide(); // Oculta el div del filtro
                $(closeButtonSelector).click(); // Simula un clic en Collapse
                //Deberia ir descomentado
                //updatetab.click();
            }
            toggleDateRangeButtons(startInputSelector, endInputSelector, closeButtonSelector, applyButtonSelector, divSelector);
        }
    
        // Función para limpiar el filtro (botón X)
        function clearDateRangeFilter(divSelector, startInputSelector, endInputSelector, applyButtonSelector, closeButtonSelector) {
            $(startInputSelector).val(''); // Limpia el input del Start Date
            $(endInputSelector).val(''); // Limpia el input del End Date
            $(divSelector).hide(); // Oculta el div del filtro
            $(closeButtonSelector).prop('disabled', false); // Habilita el botón Collapse
            $(closeButtonSelector).click(); // Simula un clic en Collapse
            $(applyButtonSelector).prop('disabled', true); // Deshabilita el botón Apply
            //Deberia ir descomentado
            //updatetab.click();
            tablewhetaapproval.draw();
        }
    
        // Función para manejar clics en botones de cerrar Collapse
        function handleCloseDateRangeCollapse(startInputSelector, endInputSelector, divSelector, startFilterInputSelector, endFilterInputSelector, applyButtonSelector) {
            if (!$(startInputSelector).val() && !$(endInputSelector).val()) {
                $(startFilterInputSelector).val(''); // Limpia el Start Date del filtro
                $(endFilterInputSelector).val(''); // Limpia el End Date del filtro
                if ($(divSelector).is(":visible")) {
                    //Deberia ir descomentado
                    //updatetab.click();
                    tablewhetaapproval.draw();
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
    
        // ------------------ suggested delivery date ------------------
        $('#applysddfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplysddstfilter', // Start Date input en el offcanvas
                '#inputapplysddenfilter', // End Date input en el offcanvas
                '#emptytrailerfiltersdd', // Div del filtro
                '#emptytrailerfilterinputstartsdd', // Start Date input en el filtro
                '#emptytrailerfilterinputendsdd', // End Date input en el filtro
                '#closeapplysddfilter', // Botón Collapse
                '#applysddfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttonsdd').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfiltersdd', // Div del filtro
                '#inputapplysddstfilter', // Start Date input en el offcanvas
                '#inputapplysddenfilter', // End Date input en el offcanvas
                '#applysddfilter', // Botón Apply
                '#closeapplysddfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtnsdd, #emptytrailerfilterinputstartsdd').on('click', function () {
            openCanvasAndFocus('#inputapplysddstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputendsdd').on('click', function () {
            openCanvasAndFocus('#inputapplysddenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplysddfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplysddstfilter', // Start Date input en el offcanvas
                '#inputapplysddenfilter', // End Date input en el offcanvas
                '#emptytrailerfiltersdd', // Div del filtro
                '#emptytrailerfilterinputstartsdd', // Start Date input en el filtro
                '#emptytrailerfilterinputendsdd', // End Date input en el filtro
                '#applysddfilter'
            );
        });
    
        // ------------------ Driver assigned date ------------------
        $('#applydadfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplydadstfilter', // Start Date input en el offcanvas
                '#inputapplydadenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdriverassigneddate', // Div del filtro
                '#emptytrailerfilterinputstartdriverassigneddate', // Start Date input en el filtro
                '#emptytrailerfilterinputenddriverassigneddate', // End Date input en el filtro
                '#closeapplydadfilter', // Botón Collapse
                '#applydadfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttondriverassigneddate').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivdriverassigneddate', // Div del filtro
                '#inputapplydadstfilter', // Start Date input en el offcanvas
                '#inputapplydadenfilter', // End Date input en el offcanvas
                '#applydadfilter', // Botón Apply
                '#closeapplydadfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtndriverassigneddate, #emptytrailerfilterinputstartdriverassigneddate').on('click', function () {
            openCanvasAndFocus('#inputapplydadstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputenddriverassigneddate').on('click', function () {
            openCanvasAndFocus('#inputapplydadenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplydadfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplydadstfilter', // Start Date input en el offcanvas
                '#inputapplydadenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdriverassigneddate', // Div del filtro
                '#emptytrailerfilterinputstartdriverassigneddate', // Start Date input en el filtro
                '#emptytrailerfilterinputenddriverassigneddate', // End Date input en el filtro
                '#applydadfilter'
            );
        });
    
        // ------------------ Picked Up Date ------------------
        $('#applypudfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplypudstfilter', // Start Date input en el offcanvas
                '#inputapplypudenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivpud', // Div del filtro
                '#emptytrailerfilterinputstartpud', // Start Date input en el filtro
                '#emptytrailerfilterinputendpud', // End Date input en el filtro
                '#closeapplypudfilter', // Botón Collapse
                '#applypudfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttonpud').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivpud', // Div del filtro
                '#inputapplypudstfilter', // Start Date input en el offcanvas
                '#inputapplypudenfilter', // End Date input en el offcanvas
                '#applypudfilter', // Botón Apply
                '#closeapplypudfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtnpud, #emptytrailerfilterinputstartpud').on('click', function () {
            openCanvasAndFocus('#inputapplypudstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputendpud').on('click', function () {
            openCanvasAndFocus('#inputapplypudenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplypudfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplypudstfilter', // Start Date input en el offcanvas
                '#inputapplypudenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivpud', // Div del filtro
                '#emptytrailerfilterinputstartpud', // Start Date input en el filtro
                '#emptytrailerfilterinputendpud', // End Date input en el filtro
                '#applypudfilter'
            );
        });

        // ------------------ In Transit Date ------------------
        $('#applyitdfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplyitdstfilter', // Start Date input en el offcanvas
                '#inputapplyitdenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivitd', // Div del filtro
                '#emptytrailerfilterinputstartitd', // Start Date input en el filtro
                '#emptytrailerfilterinputenditd', // End Date input en el filtro
                '#closeapplyitdfilter', // Botón Collapse
                '#applyitdfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttonitd').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivitd', // Div del filtro
                '#inputapplyitdstfilter', // Start Date input en el offcanvas
                '#inputapplyitdenfilter', // End Date input en el offcanvas
                '#applyitdfilter', // Botón Apply
                '#closeapplyitdfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtnitd, #emptytrailerfilterinputstartitd').on('click', function () {
            openCanvasAndFocus('#inputapplyitdstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputenditd').on('click', function () {
            openCanvasAndFocus('#inputapplyitdenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplyitdfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplyitdstfilter', // Start Date input en el offcanvas
                '#inputapplyitdenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivitd', // Div del filtro
                '#emptytrailerfilterinputstartitd', // Start Date input en el filtro
                '#emptytrailerfilterinputenditd', // End Date input en el filtro
                '#applyitdfilter'
            );
        });

        // ------------------ Delivered/Received Date ------------------
        $('#applydrdfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplydrdstfilter', // Start Date input en el offcanvas
                '#inputapplydrdenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdrd', // Div del filtro
                '#emptytrailerfilterinputstartdrd', // Start Date input en el filtro
                '#emptytrailerfilterinputenddrd', // End Date input en el filtro
                '#closeapplydrdfilter', // Botón Collapse
                '#applydrdfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttondrd').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivdrd', // Div del filtro
                '#inputapplydrdstfilter', // Start Date input en el offcanvas
                '#inputapplydrdenfilter', // End Date input en el offcanvas
                '#applydrdfilter', // Botón Apply
                '#closeapplydrdfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtndrd, #emptytrailerfilterinputstartdrd').on('click', function () {
            openCanvasAndFocus('#inputapplydrdstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputenddrd').on('click', function () {
            openCanvasAndFocus('#inputapplydrdenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplydrdfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplydrdstfilter', // Start Date input en el offcanvas
                '#inputapplydrdenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdrd', // Div del filtro
                '#emptytrailerfilterinputstartdrd', // Start Date input en el filtro
                '#emptytrailerfilterinputenddrd', // End Date input en el filtro
                '#applydrdfilter'
            );
        });

        // ------------------ Secured Yard Date ------------------
        $('#applysydfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplysydstfilter', // Start Date input en el offcanvas
                '#inputapplysydenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivsyd', // Div del filtro
                '#emptytrailerfilterinputstartsyd', // Start Date input en el filtro
                '#emptytrailerfilterinputendsyd', // End Date input en el filtro
                '#closeapplysydfilter', // Botón Collapse
                '#applysydfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttonsyd').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivsyd', // Div del filtro
                '#inputapplysydstfilter', // Start Date input en el offcanvas
                '#inputapplysydenfilter', // End Date input en el offcanvas
                '#applysydfilter', // Botón Apply
                '#closeapplysydfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtnsyd, #emptytrailerfilterinputstartsyd').on('click', function () {
            openCanvasAndFocus('#inputapplysydstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputendsyd').on('click', function () {
            openCanvasAndFocus('#inputapplysydenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplysydfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplysydstfilter', // Start Date input en el offcanvas
                '#inputapplysydenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivsyd', // Div del filtro
                '#emptytrailerfilterinputstartsyd', // Start Date input en el filtro
                '#emptytrailerfilterinputendsyd', // End Date input en el filtro
                '#applysydfilter'
            );
        });

        // ------------------ Approved ETA Date ------------------
        $('#applyaedfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplyaedstfilter', // Start Date input en el offcanvas
                '#inputapplyaedenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivaed', // Div del filtro
                '#emptytrailerfilterinputstartaed', // Start Date input en el filtro
                '#emptytrailerfilterinputendaed', // End Date input en el filtro
                '#closeapplyaedfilter', // Botón Collapse
                '#applyaedfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttonaed').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivaed', // Div del filtro
                '#inputapplyaedstfilter', // Start Date input en el offcanvas
                '#inputapplyaedenfilter', // End Date input en el offcanvas
                '#applyaedfilter', // Botón Apply
                '#closeapplyaedfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtnaed, #emptytrailerfilterinputstartaed').on('click', function () {
            openCanvasAndFocus('#inputapplyaedstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputendaed').on('click', function () {
            openCanvasAndFocus('#inputapplyaedenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplyaedfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplyaedstfilter', // Start Date input en el offcanvas
                '#inputapplyaedenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivaed', // Div del filtro
                '#emptytrailerfilterinputstartaed', // Start Date input en el filtro
                '#emptytrailerfilterinputendaed', // End Date input en el filtro
                '#applyaedfilter'
            );
        });

        // ------------------ offload time ------------------
        $('#applyoltfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplyoltstfilter', // Start Date input en el offcanvas
                '#inputapplyoltenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivolt', // Div del filtro
                '#emptytrailerfilterinputstartolt', // Start Date input en el filtro
                '#emptytrailerfilterinputendolt', // End Date input en el filtro
                '#closeapplyoltfilter', // Botón Collapse
                '#applyoltfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttonolt').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivolt', // Div del filtro
                '#inputapplyoltstfilter', // Start Date input en el offcanvas
                '#inputapplyoltenfilter', // End Date input en el offcanvas
                '#applyoltfilter', // Botón Apply
                '#closeapplyoltfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtnolt, #emptytrailerfilterinputstartolt').on('click', function () {
            openCanvasAndFocus('#inputapplyoltstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputendolt').on('click', function () {
            openCanvasAndFocus('#inputapplyoltenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplyoltfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplyoltstfilter', // Start Date input en el offcanvas
                '#inputapplyoltenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivolt', // Div del filtro
                '#emptytrailerfilterinputstartolt', // Start Date input en el filtro
                '#emptytrailerfilterinputendolt', // End Date input en el filtro
                '#applyoltfilter'
            );
        });

        // ------------------ Date of Billing ------------------
        $('#applydobfilter').on('click', function () {
            handleDateRangeFilter(
                '#inputapplydobstfilter', // Start Date input en el offcanvas
                '#inputapplydobenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdob', // Div del filtro
                '#emptytrailerfilterinputstartdob', // Start Date input en el filtro
                '#emptytrailerfilterinputenddob', // End Date input en el filtro
                '#closeapplydobfilter', // Botón Collapse
                '#applydobfilter' // Botón Apply
            );
        });
    
        $('#emptytrailerfilterbuttondob').on('click', function () {
            clearDateRangeFilter(
                '#emptytrailerfilterdivdob', // Div del filtro
                '#inputapplydobstfilter', // Start Date input en el offcanvas
                '#inputapplydobenfilter', // End Date input en el offcanvas
                '#applydobfilter', // Botón Apply
                '#closeapplydobfilter' // Botón Collapse
            );
        });
    
        $('#emptytrailerfilterbtndob, #emptytrailerfilterinputstartdob').on('click', function () {
            openCanvasAndFocus('#inputapplydobstfilter'); // Enfoca el Start Date en el offcanvas
        });
    
        $('#emptytrailerfilterinputenddob').on('click', function () {
            openCanvasAndFocus('#inputapplydobenfilter'); // Enfoca el End Date en el offcanvas
        });
    
        $('#closeapplydobfilter').on('click', function () {
            handleCloseDateRangeCollapse(
                '#inputapplydobstfilter', // Start Date input en el offcanvas
                '#inputapplydobenfilter', // End Date input en el offcanvas
                '#emptytrailerfilterdivdob', // Div del filtro
                '#emptytrailerfilterinputstartdob', // Start Date input en el filtro
                '#emptytrailerfilterinputenddob', // End Date input en el filtro
                '#applydobfilter'
            );
        });
    
        // Detectar cambios en los inputs de las fechas para habilitar o deshabilitar botones
        $('#inputapplysddstfilter, #inputapplysddenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplysddstfilter', '#inputapplysddenfilter', '#closeapplysddfilter', '#applysddfilter', '#emptytrailerfiltersdd');
        });
    
        $('#inputapplydadstfilter, #inputapplydadenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydadstfilter', '#inputapplydadenfilter', '#closeapplydadfilter', '#applydadfilter', '#emptytrailerfilterdivdriverassigneddate');
        });
    
        $('#inputapplypudstfilter, #inputapplypudenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplypudstfilter', '#inputapplypudenfilter', '#closeapplypudfilter', '#applypudfilter', '#emptytrailerfilterdivpud');
        });
        $('#inputapplyitdstfilter, #inputapplyitdenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplyitdstfilter', '#inputapplyitdenfilter', '#closeapplyitdfilter', '#applyitdfilter', '#emptytrailerfilterdivitd');
        });
    
        $('#inputapplydrdstfilter, #inputapplydrdenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydrdstfilter', '#inputapplydrdenfilter', '#closeapplydrdfilter', '#applydrdfilter', '#emptytrailerfilterdivdrd');
        });
        $('#inputapplysydstfilter, #inputapplysydenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplysydstfilter', '#inputapplysydenfilter', '#closeapplysydfilter', '#applysydfilter', '#emptytrailerfilterdivsyd');
        });
    
        $('#inputapplyaedstfilter, #inputapplyaedenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplyaedstfilter', '#inputapplyaedenfilter', '#closeapplyaedfilter', '#applyaedfilter', '#emptytrailerfilterdivaed');
        });
        $('#inputapplyoltstfilter, #inputapplyoltenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplyoltstfilter', '#inputapplyoltenfilter', '#closeapplyoltfilter', '#applyoltfilter', '#emptytrailerfilterdivolt');
        });
    
        $('#inputapplydobstfilter, #inputapplydobenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydobstfilter', '#inputapplydobenfilter', '#closeapplydobfilter', '#applydobfilter', '#emptytrailerfilterdivdob');
        });
    
        // Llamada inicial para verificar los botones
        toggleDateRangeButtons('#inputapplysddstfilter', '#inputapplysddenfilter', '#closeapplysddfilter', '#applysddfilter', '#emptytrailerfiltersdd');
        toggleDateRangeButtons('#inputapplydadstfilter', '#inputapplydadenfilter', '#closeapplydadfilter', '#applydadfilter', '#emptytrailerfilterdivdriverassigneddate');
        toggleDateRangeButtons('#inputapplypudstfilter', '#inputapplypudenfilter', '#closeapplypudfilter', '#applypudfilter', '#emptytrailerfilterdivpud');
        toggleDateRangeButtons('#inputapplyitdstfilter', '#inputapplyitdenfilter', '#closeapplyitdfilter', '#applyitdfilter', '#emptytrailerfilterdivitd');
        toggleDateRangeButtons('#inputapplydrdstfilter', '#inputapplydrdenfilter', '#closeapplydrdfilter', '#applydrdfilter', '#emptytrailerfilterdivdrd');
        toggleDateRangeButtons('#inputapplysydstfilter', '#inputapplysydenfilter', '#closeapplysydfilter', '#applysydfilter', '#emptytrailerfilterdivsyd');
        toggleDateRangeButtons('#inputapplyaedstfilter', '#inputapplyaedenfilter', '#closeapplyaedfilter', '#applyaedfilter', '#emptytrailerfilterdivaed');
        toggleDateRangeButtons('#inputapplyoltstfilter', '#inputapplyoltenfilter', '#closeapplyoltfilter', '#applyoltfilter', '#emptytrailerfilterdivolt');
        toggleDateRangeButtons('#inputapplydobstfilter', '#inputapplydobenfilter', '#closeapplydobfilter', '#applydobfilter', '#emptytrailerfilterdivdob');
    });
    /*
    //Guardar los valores de los filtros para recargas de la pagina
    // Lista de IDs de los inputs
    const inputIds = [
        'searchemptytrailergeneralwh',
        'emptytrailerfilterinputshipmenttype',
        'emptytrailerfilterinputshipmenttypepk',
        'emptytrailerfilterinputidstm',
        'emptytrailerfilterinputsecondaryid',
        'emptytrailerfilterinputidtrailerwh',
        'emptytrailerfilterinputstartsdd',
        'emptytrailerfilterinputendsdd',
        'emptytrailerfilterinputunits',
        'emptytrailerfilterinputpallets',
        'emptytrailerfilterinputstartdriverassigneddate',
        'emptytrailerfilterinputenddriverassigneddate',
        'emptytrailerfilterinputstartpud',
        'emptytrailerfilterinputendpud',
        'emptytrailerfilterinputstartitd',
        'emptytrailerfilterinputenditd',
        'emptytrailerfilterinputstartdrd',
        'emptytrailerfilterinputenddrd',
        'emptytrailerfilterinputstartsyd',
        'emptytrailerfilterinputendsyd',
        'emptytrailerfilterinputstartaed',
        'emptytrailerfilterinputendaed',
        'emptytrailerfilterinputstartolt',
        'emptytrailerfilterinputendolt',
        'emptytrailerfilterinputstartdob',
        'emptytrailerfilterinputenddob',
        'emptytrailerfilterinputbillingid',
        'emptytrailerfilterinputdevicenumber'
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
        //updateTrailerTable();
        applyidstmfFilter();
        applyssFilter();
        applytiFilter();
        applyunitsFilter();
        applypalletsFilter();
        applybillingFilter();
        applyDeviceNumFilter();
        applySuggesDateFilter();
        applyDriverAssigFilter();
        applyPickedDateFilter();
        applyTransitDateFilter();
        applySecuredYardFilter();
        applyApprovedETAFilter();
        applyOfTimeFilter();
        applyBillingFilter();
        applyDeliveryReceivedFilter();
        loadShipmentTypeFilternono(() => {
            applyShipmentTypeFilter();
        });
    });
    function applySuggesDateFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartsdd').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputendsdd').value;
        const filterDiv = document.getElementById('emptytrailerfiltersdd');
        const inputApply1 = document.getElementById('inputapplysddstfilter');
        const inputApply2 = document.getElementById('inputapplysddenfilter');
        const applyButton = document.getElementById('applysddfilter');
        const closeButton = document.getElementById('closeapplysddfilter');

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
    
    function applyDriverAssigFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartdriverassigneddate').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputenddriverassigneddate').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivdriverassigneddate');
        const inputApply1 = document.getElementById('inputapplydadstfilter');
        const inputApply2 = document.getElementById('inputapplydadenfilter');
        const applyButton = document.getElementById('applydadfilter');
        const closeButton = document.getElementById('closeapplydadfilter');

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

    function applyPickedDateFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartpud').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputendpud').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivpud');
        const inputApply1 = document.getElementById('inputapplypudstfilter');
        const inputApply2 = document.getElementById('inputapplypudenfilter');
        const applyButton = document.getElementById('applypudfilter');
        const closeButton = document.getElementById('closeapplypudfilter');

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

    function applyTransitDateFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartitd').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputenditd').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivitd');
        const inputApply1 = document.getElementById('inputapplyitdstfilter');
        const inputApply2 = document.getElementById('inputapplyitdenfilter');
        const applyButton = document.getElementById('applyitdfilter');
        const closeButton = document.getElementById('closeapplyitdfilter');

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

    function applyDeliveryReceivedFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartdrd').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputenddrd').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivdrd');
        const inputApply1 = document.getElementById('inputapplydrdstfilter');
        const inputApply2 = document.getElementById('inputapplydrdenfilter');
        const applyButton = document.getElementById('applydrdfilter');
        const closeButton = document.getElementById('closeapplydrdfilter');

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

    function applySecuredYardFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartsyd').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputendsyd').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivsyd');
        const inputApply1 = document.getElementById('inputapplysydstfilter');
        const inputApply2 = document.getElementById('inputapplysydenfilter');
        const applyButton = document.getElementById('applysydfilter');
        const closeButton = document.getElementById('closeapplysydfilter');

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

    function applyApprovedETAFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartaed').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputendaed').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivaed');
        const inputApply1 = document.getElementById('inputapplyaedstfilter');
        const inputApply2 = document.getElementById('inputapplyaedenfilter');
        const applyButton = document.getElementById('applyaedfilter');
        const closeButton = document.getElementById('closeapplyaedfilter');

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

    function applyOfTimeFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartolt').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputendolt').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivolt');
        const inputApply1 = document.getElementById('inputapplyoltstfilter');
        const inputApply2 = document.getElementById('inputapplyoltenfilter');
        const applyButton = document.getElementById('applyoltfilter');
        const closeButton = document.getElementById('closeapplyoltfilter');

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

    function applyBillingFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputstartdob').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputenddob').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivdob');
        const inputApply1 = document.getElementById('inputapplydobstfilter');
        const inputApply2 = document.getElementById('inputapplydobenfilter');
        const applyButton = document.getElementById('applydobfilter');
        const closeButton = document.getElementById('closeapplydobfilter');

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



    function applyidstmfFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputidstm').value;
        const filterDiv = document.getElementById('emptytrailerfilterdividstm');
        const inputApply = document.getElementById('inputapplyidstmfilter');
        const applyButton = document.getElementById('applyidstmfilter');
        const closeButton = document.getElementById('closeapplyidstmfilter');

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

    function applyssFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputsecondaryid').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivsecondaryid');
        const inputApply = document.getElementById('inputapplysecondaryshipmentfilter');
        const applyButton = document.getElementById('applysecondaryshipmentfilter');
        const closeButton = document.getElementById('closeapplysecondaryshipmentfilter');

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

    function applytiFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputidtrailerwh').value;
        const filterDiv = document.getElementById('emptytrailerfilterdividtrailerwh');
        const inputApply = document.getElementById('inputapplyidtrailerwhfilter');
        const applyButton = document.getElementById('applyidtrailerwhfilter');
        const closeButton = document.getElementById('closeapplyidtrailerwhfilter');

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

    function applyunitsFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputunits').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivunits');
        const inputApply = document.getElementById('inputunitsfilter');
        const applyButton = document.getElementById('applyunitsfilter');
        const closeButton = document.getElementById('closeapplyunitsfilter');

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

    function applypalletsFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputpallets').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivpallets');
        const inputApply = document.getElementById('inputpalletsfilter');
        const applyButton = document.getElementById('applypalletsfilter');
        const closeButton = document.getElementById('closeapplypalletsfilter');

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

    function applybillingFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputbillingid').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivbillingid');
        const inputApply = document.getElementById('inputbillingidfilter');
        const applyButton = document.getElementById('applybillingidfilter');
        const closeButton = document.getElementById('closeapplybillingidfilter');

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

    function applyDeviceNumFilter() {
        // Obtener elementos
        const inputFilterValue = document.getElementById('emptytrailerfilterinputdevicenumber').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivdevicenumber');
        const inputApply = document.getElementById('inputdevicenumberfilter');
        const applyButton = document.getElementById('applydevicenumberfilter');
        const closeButton = document.getElementById('closeapplydevicenumberfilter');

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


    // Función para buscar y cargar los Locations en el select
    function loadShipmentTypeFilternono(callback) {
        var carrierRoute = $('#inputapplyshipmenttypefilter').data('url');
        $.ajax({
            url: carrierRoute,
            type: 'GET',
            success: function (data) {
                let select = $('#inputapplyshipmenttypefilter');
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
        
    function applyShipmentTypeFilter(){
        // Obtener elementos
        const inputFilterValue1 = document.getElementById('emptytrailerfilterinputshipmenttype').value;
        const inputFilterValue2 = document.getElementById('emptytrailerfilterinputshipmenttypepk').value;
        const filterDiv = document.getElementById('emptytrailerfilterdivshipmenttype');
        const applyButton = document.getElementById('applyshipmenttypefilter');
        const closeButton = document.getElementById('closeapplyshipmenttypefilter');
        const inputApply2 = document.getElementById('inputapplyshipmenttypefilter');

        if (!inputApply2) {
            console.error("El elemento select con el ID 'ShipmentType' no existe.");
            return;
        }

        // Esperar a que las opciones estén cargadas y luego seleccionar el valor
        const options = Array.from(inputApply2.options);
        
        if(inputFilterValue1 && inputFilterValue2 && filterDiv.style.display === 'none'){
        const optionToSelect = options.find(option => option.value === inputFilterValue2);
        if (optionToSelect) {
            inputApply2.value = inputFilterValue2; // Selecciona el valor si existe
        } else {
            console.warn(`El valor ${inputFilterValue2} no existe en el select ShipmentType.`);
        }

        // Simular clic en el botón "Close"
        closeButton.click();

        // Simular clic en el botón "Apply"
        applyButton.click();

        // Deshabilitar el botón "Close"
        closeButton.disabled = true;
        }
    }
    */