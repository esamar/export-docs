@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card text-center">

        <div class="card-header">
        
            <ul class="nav nav-tabs card-header-tabs">
              <li class="nav-item">
                    <a class="nav-link" href="{{ route('seeds', ['id_especialista' => $id_especialista ]) }}">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="#">Output</a>
              </li>

        </div>

        <div class="card-body" style = "height: calc(100vh - 140px);">

            @if ( $error_estructura )

                @switch( $error_estructura )
                    
                    @case(1)
                        
                        <div class="alert alert-danger" role="alert">
                            Error: El archivo no tiene la estructura correcta
                        </div>
                    
                        @break

                    @case(2)
                        
                        <div class="alert alert-danger" role="alert">
                            Error: La cabecera no tiene el formato correcto
                        </div>

                        @break

                @endswitch

            @else

                @if ( $state_error === 1 )

                    <div class="alert alert-danger" role="alert">
                        
                        <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>

                        {{ $message }}

                    </div>
                    
                    <div>
                        
                        <a href="{{ route('seeds', $id_especialista)}}" class="btn btn-secondary btn-sm">Regresar a inicio</a>
                    
                    </div>

                    <br>

                @else

                    <div class="alert alert-<?= ( $state_error == 2 ? 'warning' : 'info')?>" role="alert">

                        <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>

                        {{ $message }}

                    </div>

                    <div>
                        
                        <?php if ( $state_error == 2 ):?>
                            
                            <a href="{{ route('seeds', $id_especialista)}}" 
                                class="btn btn-secondary btn-sm">Regresar a inicio</a>

                        <?php endif; ?>

                        <?php if ( $instancia === 0 ) : ?>
                            
                            <a href="{{ route('import-to-director' , [$id_temporal , $id_especialista] )}}" 
                                class="btn btn-primary btn-sm">Importar</a>

                        <?php endif; ?>

                        <?php if ( $instancia === 1 ) :?>

                            <a href="{{ route('import-to-docente' , [$id_temporal , $id_especialista] )}}" 
                                class="btn btn-primary btn-sm">Importar</a>

                        <?php endif; ?>

                        <?php if ( $instancia === 2 ) :?>

                            <a href="{{ route('import-to-ppff' , [$id_temporal , $id_especialista] )}}" 
                                class="btn btn-primary btn-sm">Importar</a>

                        <?php endif; ?>

                    </div><br>

                @endif


                <div class="row">

                    <div class="mb-3" style="overflow: auto; width: 100%; height: calc( 100vh - 300px);">

                        <table class="table table-hover">

                            <thead class="small">

                                <tr>    
                                    <th scope="col">Fila</th>
                                    <th scope="col">Monitor</th>
                                    <th scope="col">Codmod</th>

                                    <?php if ( $instancia === 0 ): ?> 
                                        <th scope="col">Agrupamiento</th>
                                        <th scope="col">Número doc</th>
                                        <th scope="col" style="text-align: left;">Ape Paterno</th>
                                        <th scope="col" style="text-align: left;">Ape Materno</th>
                                        <th scope="col" style="text-align: left;">Nombres</th>
                                        <th scope="col" style="text-align: left;">email</th>
                                        <th scope="col">Teléfono1</th>
                                        <th scope="col">Teléfono2</th>
                                    <?php endif; ?>

                                    <?php if ( $instancia === 1 ): ?> 
                                        <th scope="col">Grado</th>
                                        <th scope="col">Sección</th>
                                        <th scope="col">Área</th>
                                        <th scope="col">Número doc</th>
                                        <th scope="col" style="text-align: left;">Ape Paterno</th>
                                        <th scope="col" style="text-align: left;">Ape Materno</th>
                                        <th scope="col" style="text-align: left;">Nombres</th>
                                        <th scope="col">Teléfono1</th>
                                        <th scope="col">Teléfono2</th>
                                    <?php endif; ?>

                                    <?php if ( $instancia == 2 ): ?> 
                                        <th scope="col">Tipo doc</th>
                                        <th scope="col">Número doc</th>
                                        <th scope="col" style="text-align: left;">Ape Paterno</th>
                                        <th scope="col" style="text-align: left;">Ape Materno</th>
                                        <th scope="col" style="text-align: left;">Nombres</th>
                                        <th scope="col">Grado</th>
                                        <th scope="col">Sección</th>
                                        <th scope="col" style="text-align: left;">Ape Paterno apo</th>
                                        <th scope="col" style="text-align: left;">Ape Materno apo</th>
                                        <th scope="col" style="text-align: left;">Nombres apo</th>
                                        <th scope="col">Teléfono1</th>
                                        <th scope="col">Teléfono2</th>
                                    <?php endif; ?>

                                    <th scope="col">Resumen</th>

                                </tr>

                            </thead>
                        
                            <tbody style="font-size: 7.5pt;">
                            
                                <?= $resumeTable ?>
                        
                            </tbody>

                        </table>

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection

