<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

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

                <form action="{{ route('import.directory.excel') }}" method="post" enctype="multipart/form-data">
                    
                    @csrf


                    <?php if ( isset( $state ) ):?>

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

                    <!-- <input type="file" name="file"> -->

                    <div class="custom-file">

                      <input type="file" class="custom-file-input" name="file" id="customFileLang" lang="es">
                      <label class="custom-file-label" for="customFileLang">Seleccionar Archivo</label>

                    </div>

<br>
<br>

                    <button class="btn btn-primary">Importar </button>

                    @if (Session::has("table") )
                        <p>{{ Session::get("table")}}</p>
                    @endif

                </form>

            </div>
        </div>
    </body>
</html>
