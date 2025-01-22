
document.getElementById('exportfile').addEventListener('click', function () {
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

    // Exporta el archivo Excel con el nombre apropiado
    var filename = 'WHApptApprovalShipments.xlsx';
    XLSX.writeFile(wb, filename);
});

// Inicializar los tooltips solo para los elementos con la clase memingo
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
    
    function updateShipmentWHETATable() {
        // Obtener los valores de los filtros
        const searchwh = document.getElementById('searchemptytrailergeneralwh').value;
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
        
        // Construir la URL con los parámetros de filtro
        const url = new URL(document.getElementById('refreshwhetapprovaltable').getAttribute('data-url'));
        const params = new URLSearchParams(url.search);

        // Agregar los filtros a los parámetros de la URL
        params.set('search', searchwh);
        params.set('gnct_id_shipment_type', shipmenttypewh);
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

        url.search = params.toString();
        //console.log(url);
        fetch(url)
            .then(response => response.json())
            .then(data => {

                // Aquí va el código para actualizar trailersData
                shipmentsData = data.reduce((acc, shipment) => {
                    acc[shipment.pk_shipment] = shipment;
                    return acc;
                }, {});
                // Actualizar la tabla con los datos filtrados
                const tbody = document.getElementById('shipmentWHTableBody');
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
                });

                // Vuelve a agregar los listeners de clic después de actualizar la tabla
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

            })
            .catch(error => console.error('Error:', error));
    }
    
    // Llamar la función cuando se hace clic en el botón de "Refresh" o cuando cambian los filtros
    document.getElementById('refreshwhetapprovaltable').addEventListener('click', updateShipmentWHETATable);
    
    const filterInputs = document.querySelectorAll('#filtersapplied input');
    filterInputs.forEach(input => {
        input.addEventListener('input', updateShipmentWHETATable);
    });

    const filterGeneralInputs = document.querySelectorAll('#searchemptytrailergeneralwh');
    filterGeneralInputs.forEach(input => {
        input.addEventListener('input', updateShipmentWHETATable);
    });

    // Actualización automática cada 5 minutos (300,000 ms)
    setInterval(updateShipmentWHETATable, 5000000);
    
    //Formato de fechas
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
        'whaetainputidtrailer',
        'whetainputidstm',
        'whetainputpallets',
        'whetainputunits',
        'whetainputedt',
        'whetainputapprovedeta',
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
        /*else if ((field.id === 'updateinputusername' || field.id === 'updateinputpalletsonfloor' || field.id === 'updateinputpalletsontrailer') && field.value.length > 50) {
            field.classList.add('is-invalid');
            errorElement.textContent = 'This field cannot exceed 50 characters'; // Mensaje de error
        }*/ else {
            field.classList.remove('is-invalid');
            errorElement.textContent = ''; // Limpiar el mensaje de error
        }
    }

    // Ejecutar la validación al hacer clic en el botón de "guardar"
    document.getElementById("whetaapprovalbuttonsave").addEventListener("click", function () {
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
            /*else if ((fieldId === 'updateinputusername' || fieldId === 'updateinputpalletsonfloor' || fieldId === 'updateinputpalletsontrailer') && field.value.length > 50) {
                valid = false;
                field.classList.add('is-invalid');
                errorElement.textContent = 'This field cannot exceed 50 characters';
            }*/ else {
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
                    id_trailer: document.getElementById("whaetainputidtrailer").value,
                    stm_id: document.getElementById("whetainputidstm").value,
                    pallets: document.getElementById("whetainputpallets").value,
                    units: document.getElementById("whetainputunits").value,
                    etd: document.getElementById("whetainputedt").value,
                    wh_auth_date: document.getElementById("whetainputapprovedeta").value,
                };

                fetch(urlwhetaapproval, {
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
                            closewhetaapprovalbutton.click();
                            closeoffcanvaswhetaapprovaldetails.click();
                            //refreshButtonUpdate.click();
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

        // Device Number
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
        loadShipmentTypeFilter();
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
        const updatetab = document.getElementById("refreshwhetapprovaltable");
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
                '#emptytrailerfilterinputendsdd' // End Date input en el filtro
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
                '#emptytrailerfilterinputenddriverassigneddate' // End Date input en el filtro
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
                '#emptytrailerfilterinputendpud' // End Date input en el filtro
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
                '#emptytrailerfilterinputenditd' // End Date input en el filtro
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
                '#emptytrailerfilterinputenddrd' // End Date input en el filtro
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
                '#emptytrailerfilterinputendsyd' // End Date input en el filtro
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
                '#emptytrailerfilterinputendaed' // End Date input en el filtro
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
                '#emptytrailerfilterinputendolt' // End Date input en el filtro
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
                '#emptytrailerfilterinputenddob' // End Date input en el filtro
            );
        });
    
        // Detectar cambios en los inputs de las fechas para habilitar o deshabilitar botones
        $('#inputapplysddstfilter, #inputapplysddenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplysddstfilter', '#inputapplysddenfilter', '#closeapplysddfilter', '#applysddfilter');
        });
    
        $('#inputapplydadstfilter, #inputapplydadenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydadstfilter', '#inputapplydadenfilter', '#closeapplydadfilter', '#applydadfilter');
        });
    
        $('#inputapplypudstfilter, #inputapplypudenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplypudstfilter', '#inputapplypudenfilter', '#closeapplypudfilter', '#applypudfilter');
        });
        $('#inputapplyitdstfilter, #inputapplyitdenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplyitdstfilter', '#inputapplyitdenfilter', '#closeapplyitdfilter', '#applyitdfilter');
        });
    
        $('#inputapplydrdstfilter, #inputapplydrdenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydrdstfilter', '#inputapplydrdenfilter', '#closeapplydrdfilter', '#applydrdfilter');
        });
        $('#inputapplysydstfilter, #inputapplysydenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplysydstfilter', '#inputapplysydenfilter', '#closeapplysydfilter', '#applysydfilter');
        });
    
        $('#inputapplyaedstfilter, #inputapplyaedenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplyaedstfilter', '#inputapplyaedenfilter', '#closeapplyaedfilter', '#applyaedfilter');
        });
        $('#inputapplyoltstfilter, #inputapplyoltenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplyoltstfilter', '#inputapplyoltenfilter', '#closeapplyoltfilter', '#applyoltfilter');
        });
    
        $('#inputapplydobstfilter, #inputapplydobenfilter').on('input', function () {
            toggleDateRangeButtons('#inputapplydobstfilter', '#inputapplydobenfilter', '#closeapplydobfilter', '#applydobfilter');
        });
    
        // Llamada inicial para verificar los botones
        toggleDateRangeButtons('#inputapplysddstfilter', '#inputapplysddenfilter', '#closeapplysddfilter', '#applysddfilter');
        toggleDateRangeButtons('#inputapplydadstfilter', '#inputapplydadenfilter', '#closeapplydadfilter', '#applydadfilter');
        toggleDateRangeButtons('#inputapplypudstfilter', '#inputapplypudenfilter', '#closeapplypudfilter', '#applypudfilter');
        toggleDateRangeButtons('#inputapplyitdstfilter', '#inputapplyitdenfilter', '#closeapplyitdfilter', '#applyitdfilter');
        toggleDateRangeButtons('#inputapplydrdstfilter', '#inputapplydrdenfilter', '#closeapplydrdfilter', '#applydrdfilter');
        toggleDateRangeButtons('#inputapplysydstfilter', '#inputapplysydenfilter', '#closeapplysydfilter', '#applysydfilter');
        toggleDateRangeButtons('#inputapplyaedstfilter', '#inputapplyaedenfilter', '#closeapplyaedfilter', '#applyaedfilter');
        toggleDateRangeButtons('#inputapplyoltstfilter', '#inputapplyoltenfilter', '#closeapplyoltfilter', '#applyoltfilter');
        toggleDateRangeButtons('#inputapplydobstfilter', '#inputapplydobenfilter', '#closeapplydobfilter', '#applydobfilter');
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