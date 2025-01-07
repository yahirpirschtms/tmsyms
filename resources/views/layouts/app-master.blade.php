<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'App')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/png" href="{!! asset('/icons/tms_logo.png')!!}">
    @vite([
        'resources/sass/app.scss',
        'resources/js/app.js'
        ])

    <style>
      .gradient-text {
        background: linear-gradient(135deg, #1e4877, rgb(13, 82, 200));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
      .navbar{
        background: linear-gradient(135deg, #1e4877,rgb(13, 82, 200));
        height: 80px;
        margin: 20px;
        border-radius: 16px;
        padding: 0.5rem;
      }
      .fixed-padding {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%; /* Para que ocupe todo el ancho */
        z-index: 999; /* Asegura que esté sobre otros elementos */
        background-color: white; /* Fondo blanco para no mostrar contenido detrás */
      }
      .navbar-brand{
        font-weight: 500;
        /*color: #1e4877;*/
        color: white;
        font-size: 24px;
        transition: 0.3s color;
      }
      .navbar-brand:hover, .navbar-brand:active{
        color: white;
      }
      .login-button{
        background-color: white;
        color: #1e4877;
        font-size: 14px;
        padding: 8px 20px;
        border-radius: 50px;
        text-decoration: none;
        transition: 0.3s background-color;
      }
      .login-button:hover{
        background-color: #c3c3c3;
      }
      .navbar-toggler{
        border: none;
        font-size: 1.25rem;
      }
      .navbar-toggler:focus, .btn-close:focus{
        box-shadow: none;
        outline: none;
      }
      .nav-link{
        color: white;
        /*color: #1e4877; */
        font-weight: 500;
        position: relative;
      }
      .dropdown-item{
        color: white;
        position: relative;
      }
      .nav-link:hover, .nav-link:active, .dropdown-item:hover, .dropdown-item:active{
        /*color: #000;*/
        color: white;
      }

      @media (max-width: 990px){
        .offcanvas-body{
          background-color: #1e4877;
        }
      }
      @media (min-width: 576px) {
        .modal-dialogg {
          max-width: 300px;
          margin-right: 1.2rem;
          margin-left: auto;
          margin-top: 1.2rem;
        }
      }

      @media (max-width: 576px) {
        .modal-dialogg {
          max-width: 215px;
          margin-right: 1.2rem;
          margin-left: auto;
          margin-top: 1.2rem;
        }
      }

      @media (min-width: 991px) {
        .nav-link::before, .dropdown-item::before{
          content: "";
          position: absolute;
          bottom: 0;
          left: 50%;
          transform: translateX(-50%);
          width: 0;
          height: 0.5px;
          background-color:white;
          /*background-color: #1e4877; */
          visibility: hidden;
          transition: 0.3s ease-in-out;
        }
        .offcanvas-body{
          background-color: transparent;
        }
        .nav-link:hover::before, .nav-link.active::before{
          width: 100%;
          visibility: visible;
        }
        .dropdown-item:hover::before, .dropdown-item.active::before{
          width: 80%;
          visibility: visible;
        }
      }
      .hero-section{
        background: white;
        background-size: cover;
        width: 100%;
      }
      .hero-section .container{
        height: 100vh;
        z-index: 1;
        position: relative;

      }
      .hero-section h1{
        font-size: 1.5em;
      }
      .non{
        cursor: default;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select:none;
        user-select:none;
      }
      .dropdown-item:hover{
        background-color: unset;
      }
      .is-valid {
      border-color: #198754;
      }
      .is-invalid {
          border-color: #dc3545;
      }
      .invalid-feedback {
          color: #dc3545;
          display: block;
      }
      /*.hero-section::before{
        background-color: rgb(0, 0, 0, 0.6);
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
      }*/
    </style>
</head>
<body>

      @include('layouts.partials.navbar')
      <div style="margin-top: 120px;">
        @yield('content')
      </div>

      
      
</body>
</html>


<!--Script para buscar el availability indicator en la pantalla de empty trailer-->
<script>
    $(document).ready(function () {
      //Funcion para buscar los carriers en la pantalla de empty trailer
      function loadCarriers() {
        $.ajax({
            url: "{{ route('carrier-emptytrailer') }}",
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

      //Funcion para buscar las locations en la pantalla de empty trailer
      function loadLocations() {
        $.ajax({
            url: "{{ route('locations-emptytrailer') }}",
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

    //Funcion para buscar el availability indicator en la pantalla de empty trailer
      function loadAvailabilityIndicator() {
          $.ajax({
              url: "{{ route('availabilityindicators-emptytrailer') }}",
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

    // Cargar datos al enfocarse y al cargar la página
    $('#inputavailabilityindicator').on('focus', loadAvailabilityIndicator);
    loadAvailabilityIndicator();
});

//Funcion para actualizar los datos de la tabla empty trailer al picarle al boton refresh
  document.getElementById('refreshemptytrailertable').addEventListener('click', function () {
          fetch("{{ route('emptytrailer.data') }}")
              .then(response => response.json())
              .then(data => {
                  const tbody = document.getElementById('emptyTrailerTableBody');
                  tbody.innerHTML = ''; // Limpia la tabla
                  data.forEach(trailer => {
                      const row = `
                          <tr>
                              <td>${trailer.trailer_num}</td>
                              <td>${trailer.status}</td>
                              <td>${trailer.pallets_on_trailer}</td>
                              <td>${trailer.pallets_on_floor}</td>
                              <td>${trailer.carrier}</td>
                              <td>${trailer.gnct_id_avaibility_indicator}</td>
                              <td>${trailer.location}</td>
                              <td>${trailer.date_in}</td>
                              <td>${trailer.date_out}</td>
                              <td>${trailer.transaction_date}</td>
                              <td>${trailer.username}</td>
                          </tr>
                      `;
                      tbody.innerHTML += row;
                  });
              })
              .catch(error => console.error('Error:', error));
  });
//Funcion para actualizar tabla de empty trailer automaticamente cada 5 min
  setInterval(() => {
          fetch("{{ route('emptytrailer.data') }}")
              .then(response => response.json())
              .then(data => {
                  const tbody = document.getElementById('emptyTrailerTableBody');
                  tbody.innerHTML = '';
                  data.forEach(trailer => {
                      const row = `
                          <tr>
                              <td>${trailer.trailer_num}</td>
                              <td>${trailer.status}</td>
                              <td>${trailer.pallets_on_trailer}</td>
                              <td>${trailer.pallets_on_floor}</td>
                              <td>${trailer.carrier}</td>
                              <td>${trailer.gnct_id_avaibility_indicator}</td>
                              <td>${trailer.location}</td>
                              <td>${trailer.date_in}</td>
                              <td>${trailer.date_out}</td>
                              <td>${trailer.transaction_date}</td>
                              <td>${trailer.username}</td>
                          </tr>
                      `;
                      tbody.innerHTML += row;
                  });
              })
              .catch(error => console.error('Error:', error));
  }, 300000); // Cada 5 minutos 

//Funcion eliminar EmptyTrailers
document.addEventListener("DOMContentLoaded", function () {
    const csrfMeta = document.head.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : null;

    if (!csrfToken) {
        console.error('CSRF token not found!');
        return;
    }

    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const trailerId = this.getAttribute('data-id');
            
            // Confirmación antes de eliminar
            if (confirm("Are you sure you want to delete this trailer?")) {
                // Enviar una solicitud DELETE usando Fetch API
                fetch(`/empty-trailer/${trailerId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken  // Se agrega automáticamente el token CSRF desde la meta etiqueta
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Si la eliminación fue exitosa, eliminar la fila de la tabla
                        const row = document.getElementById(`trailer-${trailerId}`);
                        row.remove();
                    } else {
                        alert(data.error || 'An error occurred while deleting the trailer');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the trailer');
                });
            }
        });
    });
});

</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("emptytrailerformm");
    const inputs = document.querySelectorAll("#emptytrailerformm input, #emptytrailerformm select");

    // Función para validar un campo individual
    function validateField(input) {
        const fieldName = input.name;
        const fieldValue = input.value.trim();  // Recorta espacios en blanco al principio y final

        // Enviar siempre el valor actual del campo
        const valueToValidate = fieldValue.length === 0 ? '' : fieldValue;

        return fetch("{{ route('emptytrailer.validate') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}", // Token CSRF incluido automáticamente
            },
            body: JSON.stringify({
                field: fieldName,
                value: valueToValidate,  // Usar el valor con espacios recortados o vacío
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.valid) {
                    input.classList.remove("is-invalid");
                    input.classList.add("is-valid");
                    const errorDiv = input.nextElementSibling;
                    if (errorDiv && errorDiv.classList.contains("invalid-feedback")) {
                        errorDiv.remove();
                    }
                    return true;  // Campo válido
                } else {
                    input.classList.add("is-invalid");
                    input.classList.remove("is-valid");
                    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains("invalid-feedback")) {
                        const errorDiv = document.createElement("div");
                        errorDiv.classList.add("invalid-feedback");
                        errorDiv.innerText = data.message;
                        input.insertAdjacentElement("afterend", errorDiv);
                    }
                    return false;  // Campo inválido
                }
            });
    }

    // Validar todos los campos cuando se hace submit
    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Evita que se envíe el formulario de inmediato

        const validationPromises = [];

        // Crear un array de promesas para validar cada campo
        inputs.forEach((input) => {
            const validationPromise = validateField(input);
            validationPromises.push(validationPromise);  // Añadir la promesa a la lista
        });

        // Esperar a que todas las validaciones terminen
        Promise.all(validationPromises).then((results) => {
            // Si todos los campos son válidos (todas las promesas resueltas como 'true')
            const allValid = results.every((valid) => valid === true);

            if (allValid) {
                form.submit();  // Si todo es válido, envía el formulario
            }
        });
    });

    // Validación al escribir (keyup) para verificar cada cambio en el valor del campo
    inputs.forEach((input) => {
        input.addEventListener("keyup", function () {
            validateField(this);  // Valida cada campo a medida que se escribe
        });
    });

    // Validación también al salir del campo (blur) para asegurarse que el campo se valida después de dejarlo
    inputs.forEach((input) => {
        input.addEventListener("blur", function () {
            validateField(this);  // Valida el campo cuando se sale de él
        });
    });
});

</script>