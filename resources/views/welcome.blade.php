<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <script type="text/javascript">
            
            $( document ).ready(() => {
                $("input[type=file]").change(function(){
                       var fieldVal = $(this).val();
                       if (fieldVal != undefined || fieldVal != "") {   
                           $(this).next(".custom-file-label").html( fieldVal.split("\\").pop() );
                       }
                });

            })

        </script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">

                <!-- <p>Click <a href="{{ route('users.pdf')}}">aqui</a> para descargar en PDF a los usuarios </p> -->

                <!-- <p>Click <a href="{{ route('users.excel')}}">aqui</a> para descargar en EXCEL a los usuarios </p> -->

                <h5 class="card-title"><b>SIREG SEEDS - v0.1</b></h5>

                <p class="card-text">Módulo de importación de datos semilla para el registro de directores.</p>



                <div class="card">

                  <div class="card-header">

                    Importar datos de director

                  </div>

                  <div class="card-body">

                    <form action="{{ route('import.directory.excel') }}" method="post" enctype="multipart/form-data">
                        
                        @csrf

                        <?php 
                        
                            session_start();
                        
                            $_SESSION['ID_ESPECIALISTA'] = $id_especialista;

                        ?>

                        <?php 

                        if ( isset( $state ) ):?>

                            <?php if ( $state ): ?>

                                <div class="alert alert-success" role="alert">

                                    <?= $message ?>

                                </div>

                            <?php else: ?>

                                <div class="alert alert-danger" role="alert">

                                    <?= $message ?>

                                </div>

                                <div>
                                    <a href="{{ route('import-to-director' , $id_temporal )}}" class="btn btn-success">Continuar</a>
                                </div><br>

                            <?php endif; ?>

                        <?php endif; ?>

                          <div class="input-group is-invalid">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="validatedInputGroupCustomFile" name="file" lang="es" required>
                              <label class="custom-file-label" for="validatedInputGroupCustomFile">Seleccione el archivo...</label>
                            </div>
                            <div class="input-group-append">
                               <button class="btn btn-outline-secondary">Importar</button>
                            </div>
                          </div>

                        @if (Session::has("table") )
                            <p>{{ Session::get("table")}}</p>
                        @endif

                    </form>

                  </div>
                </div>



            </div>
        </div>
    </body>
</html>
