document.addEventListener("DOMContentLoaded", function() {
    // Llamar automáticamente al cargar la página
    cargarDashboard();

    document.getElementById("recargar").addEventListener("click", function () {
        cargarDashboard(); // Llamar cuando se haga clic en el botón
    });


    // Obtener la fecha actual en la zona horaria del usuario
    let now = new Date();
    let yesterday = new Date();
    yesterday.setDate(now.getDate() - 1); // Restar un día

    // Opciones de formato (Ej: "Monday 17" - "Tuesday 18 March 2025")
    let optionsDay = { weekday: 'long', day: 'numeric' };
    let optionsFull = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };

    // Formatear las fechas en el idioma del usuario
    let formattedYesterday = yesterday.toLocaleDateString('en-US', optionsDay);
    let formattedToday = now.toLocaleDateString('en-US', optionsFull);

    // Insertar la fecha en el elemento HTML
    document.getElementById("dateDisplay").textContent = `${formattedYesterday} - ${formattedToday}`;
});


function cargarDashboard() {
    fetch('/updatedashboard')
        .then(response => response.json())
        .then(data => {
            console.log("Datos recibidos:", data); // Ver los datos completos

            if (data.status === "success") {

                // Obtener datos para Prealerted
                const prealertedData = data.data.shipmentCounts.Prealerted;
                if (!prealertedData) {
                    console.error("No se encontraron datos de Prealerted.");
                    return;
                }
                const prealertedContainer = document.getElementById("prealerted_container");
                if (!prealertedContainer) {
                    console.error("No se encontró el contenedor de Prealerted.");
                    return;
                }
                prealertedContainer.innerHTML = "";
                Object.entries(prealertedData).forEach(([location, amount]) => {
                    const row = document.createElement("div");
                    row.className = "row mb-2 align-items-start";
                    row.innerHTML = `
                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">${location}</div>
                        <div class="col d-flex justify-content-center">${amount}</div>
                    `;
                    prealertedContainer.appendChild(row);
                });

                // Obtener datos para Driver Assigned
                const driverAssignedData = data.data.shipmentCounts["Driver Assigned"];
                if (!driverAssignedData) {
                    console.error("No se encontraron datos de Driver Assigned.");
                    return;
                }
                const driverAssignedContainer = document.getElementById("driverassigned_container");
                if (!driverAssignedContainer) {
                    console.error("No se encontró el contenedor de Driver Assigned.");
                    return;
                }
                driverAssignedContainer.innerHTML = "";
                Object.entries(driverAssignedData).forEach(([location, amount]) => {
                    const row = document.createElement("div");
                    row.className = "row mb-2 align-items-start";
                    row.innerHTML = `
                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">${location}</div>
                        <div class="col d-flex justify-content-center">${amount}</div>
                    `;
                    driverAssignedContainer.appendChild(row);
                });

                // Obtener datos para In Transit
                const inTransitData = data.data.shipmentCounts["In Transit"];
                if (!inTransitData) {
                    console.error("No se encontraron datos de In Transit.");
                    return;
                }
                const inTransitContainer = document.getElementById("intransit_container");
                if (!inTransitContainer) {
                    console.error("No se encontró el contenedor de In Transit.");
                    return;
                }
                inTransitContainer.innerHTML = "";
                Object.entries(inTransitData).forEach(([key, amount]) => {
                    const [from, to] = key.split(" - ");  // Separa la clave 'FROM - TO'
                    const row = document.createElement("div");
                    row.className = "row mb-2 align-items-start";
                    row.innerHTML = `
                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">${from}</div>
                        <div class="col d-flex justify-content-start" style="font-weight: 600;">${to}</div>
                        <div class="col d-flex justify-content-center">${amount}</div>
                    `;
                    inTransitContainer.appendChild(row);
                });

                // Obtener datos para Delivered
                const deliveredData = data.data.shipmentCounts["Delivered"];
                if (!deliveredData) {
                    console.error("No se encontraron datos de Delivered.");
                    return;
                }
                const deliveredContainer = document.getElementById("delivered_container");
                if (!deliveredContainer) {
                    console.error("No se encontró el contenedor de Delivered.");
                    return;
                }

                // Obtener la fecha de ayer y hoy
                const today = new Date();
                const yesterday = new Date(today);
                yesterday.setDate(today.getDate() - 7);

                // Formato de la fecha (Ayer - Hoy)
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formattedToday = today.toLocaleDateString('en-US', options);
                const formattedYesterday = yesterday.toLocaleDateString('en-US', options);

                // Mostrar la fecha de ayer a hoy
                const dateDisplay = document.getElementById('dateDisplay');
                if (dateDisplay) {
                    dateDisplay.textContent = `${formattedYesterday} - ${formattedToday}`;
                }

                deliveredContainer.innerHTML = "";
                Object.entries(deliveredData).forEach(([key, amount]) => {
                    const [from, to] = key.split(" - ");  // Separa la clave 'FROM - TO'
                    const row = document.createElement("div");
                    row.className = "row mb-2 align-items-start";
                    row.innerHTML = `
                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">${from}</div>
                        <div class="col d-flex justify-content-start" style="font-weight: 600;">${to}</div>
                        <div class="col d-flex justify-content-center">${amount}</div>
                    `;
                    deliveredContainer.appendChild(row);
                });

                // Obtener datos para Empty Pool
                const emptyPoolData = data.data.emptyTrailerCounts;
                if (!emptyPoolData) {
                    console.error("No se encontraron datos de Empty Pool.");
                    return;
                }
                const emptyPoolContainer = document.getElementById("emptypool_container");
                if (!emptyPoolContainer) {
                    console.error("No se encontró el contenedor de Empty Pool.");
                    return;
                }
                emptyPoolContainer.innerHTML = "";
                Object.entries(emptyPoolData).forEach(([location, amount]) => {
                    const row = document.createElement("div");
                    row.className = "row mb-2 align-items-start";
                    row.innerHTML = `
                        <div class="col d-flex justify-content-start ms-5" style="font-weight: 600;">${location}</div>
                        <div class="col d-flex justify-content-center">${amount}</div>
                    `;
                    emptyPoolContainer.appendChild(row);
                });

            } else {
                console.error("Error en la respuesta:", data.message);
            }
        })
        .catch(error => {
            console.error("Error al obtener el dashboard:", error);
        });
}

// Cargar datos al inicio
document.addEventListener("DOMContentLoaded", cargarDashboard);

// Actualizar cada 30 segundos
setInterval(cargarDashboard, 30000);
