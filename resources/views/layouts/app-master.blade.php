<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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


      .offcanvasUpdateStatus {
            background-color: #212529;
            color: white;
            width: 300px;
        }

        .offcanvasUpdateStatus .form-label {
            color: #f8f9fa;
        }
        .offcanvasDetails {
            background-color: #212529;
            color: white;
            width: 300px;
        }

        .ooffcanvasDetails .form-label {
            color: #f8f9fa;
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
      /*.hero-section::before{
        background-color: rgb(0, 0, 0, 0.6);
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
      }*/

                #pills-tabContent {
                padding-top: 0;
                margin-top: 0;
            }

            /* Ajustar márgenes en los elementos dentro de las pestañas */
            .tab-pane {
                padding-top: 10px; /* Ajusta según necesites */
            }

            /* Reducir espacio extra en los p y labels dentro de los detalles */
            .tab-pane .form-label {
                margin-bottom: 5px; /* Menos margen entre label y p */
            }

            .tab-pane .mb-3 {
                margin-bottom: 10px; /* Menos margen entre cada bloque */
            }

            /* Si el espacio es muy grande en los márgenes, también puedes probar ajustando el padding global */
            body {
                margin: 0;
                padding: 0;
            }


            .table {
    table-layout: auto;
    width: 100%;
}

.table-responsive {
    overflow-x: auto;
}

    </style>
</head>
<body>

      @include('layouts.partials.navbar')
      <div style="margin-top: 120px;">
        @yield('content')

        <style>


        </style>
      </div>



</body>


</html>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <!--Script para buscar el availability indicator en la pantalla de empty trailer-->
<script>
        $(document).ready(function () {
            $('#inputavailabilityindicator').on('focus', function () {
                $.ajax({
                    url: '/availability-indicators',
                    method: 'GET',
                    success: function (data) {
                        let options = '<option selected>Open this select menu</option>';
                        data.forEach(item => {
                            options += `<option value="${item.gnct_id}">${item.gntc_description}</option>`;
                        });
                        $('#inputavailabilityindicator').html(options);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al cargar los datos:', error);
                    }
                });
            });
        });
</script>

<script>
    $(document).ready(function () {
        // Interceptar el envío del formulario
        $('#shipmentForm').on('submit', function (event) {
            event.preventDefault(); // Evitar el envío estándar del formulario

            // Obtener la URL del formulario
            let formAction = $(this).attr('action');

            // Serializar los datos del formulario
            let formData = $(this).serialize();

            // Enviar los datos mediante AJAX
            $.ajax({
                url: formAction,
                method: 'PUT',
                data: formData,
                beforeSend: function () {
                    // Puedes agregar un indicador de carga aquí si lo necesitas
                    console.log('Enviando datos...');
                },
                success: function (response) {
                    // Manejar la respuesta exitosa
                    alert(response.message);
                    console.log(response);

                    // Actualizar la página o realizar alguna acción adicional
                    location.reload(); // Recargar la página para ver los cambios
                },
                error: function (xhr) {
                    // Manejar errores
                    let errorMessage = xhr.responseJSON?.message || 'Ocurrió un error al actualizar el envío.';
                    alert(errorMessage);
                    console.error(xhr.responseJSON?.error || xhr.responseText);
                },
            });
        });
    });
</script>
