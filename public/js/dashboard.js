document.addEventListener("DOMContentLoaded", function() {
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