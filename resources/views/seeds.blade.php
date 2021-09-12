@extends('layouts.app')

@section('content')

<div class="container-fluid">
    
    <?php 
    
        session_start();
    
        $_SESSION['ID_ESPECIALISTA'] = $id_especialista;

    ?>

    <div class="card text-center">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('seeds', ['id_especialista' => $id_especialista ]) }}">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Output</a>
          </li>

      </div>
      <div class="card-body">

        <h5 class="card-title"><b>SIREG SEEDS - P v0.1.1</b></h5>

        <p class="card-text">Módulo de importación de datos semilla para el sistema de registro SIREG-CP.</p>
        
        <div class = "row">

            <div class="col-12 col-lg-4 pl-3 pr-3">
                
                <div class="card">

                  <div class="card-header">

                    Director

                  </div>

                  <div class="card-body">

                    <form id="form_dir" action="{{ route('import.directory.excel.dir') }}" method="post" enctype="multipart/form-data">
                        
                        @csrf

                        <?php if ( isset( $state ) ):?>
                            
                            <?php if ( $instancia===0 ): ?>

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

                        <?php endif; ?>

                          <div class="input-group is-invalid">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="validatedInputGroupCustomFile" name="file" lang="es" required>
                              <label class="custom-file-label" for="validatedInputGroupCustomFile">Seleccione el archivo...</label>
                            </div>
                            <div class="input-group-append">

                                <button id="btn_import1" class="btn btn-outline-secondary" onmouseup="run_import_dir()">
                                    Importar
                                </button>

                                <button id="spin_1" class="btn btn-outline-secondary" disabled hidden>
                                    <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Analizando...
                                </button>

                            </div>
                          </div>

                        @if (Session::has("table") )
                            <p>{{ Session::get("table")}}</p>
                        @endif

                    </form>

                  </div>
                  
                </div>

            </div>

            <div class="col-12 col-lg-4 pl-3 pr-3">
                
                <div class="card">

                  <div class="card-header">

                    Docentes

                  </div>

                  <div class="card-body">

                    <form  id="form_doc" action="{{ route('import.directory.excel.doc') }}" method="post" enctype="multipart/form-data">
                        
                        @csrf

                        <?php if ( isset( $state ) ): ?>

                            <?php if ( $instancia===1 ): ?>

                                <?php if ( $state ): ?>

                                    <div class="alert alert-success" role="alert">

                                        <?= $message ?>

                                        <a href="{{ route('seeds' , [$id_especialista] )}}" class="btn btn-success">Aceptar</a>

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

                        <?php endif; ?>

                        <?php if ( !isset( $state ) ): ?>

                            <div class="input-group is-invalid">
                                <div class="custom-file">
                                  <input type="file" class="custom-file-input" id="validateDocente" name="file" lang="es" required>
                                  <label class="custom-file-label" for="validateDocente">Seleccione el archivo...</label>
                                </div>
                                <div class="input-group-append">

                                   <button id="btn_import2" class="btn btn-outline-secondary" onmouseup="run_import_doc()">
                                        Importar
                                   </button>

                                    <button id="spin_2" class="btn btn-outline-secondary" disabled hidden>
                                        <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Analizando...
                                    </button>

                                </div>
                            </div>

                        <?php endif; ?>

                        @if (Session::has("table") )
                            <p>{{ Session::get("table")}}</p>
                        @endif

                    </form>

                  </div>

                </div>

            </div>

            <div class="col-12 col-lg-4 pl-3 pr-3">

                <div class="card">

                  <div class="card-header">

                    PPFF - Estudiantes

                  </div>

                  <div class="card-body">

                    <form  id="form_ppff" action="{{ route('import.directory.excel.ppff') }}" method="post" enctype="multipart/form-data">
                        
                        @csrf

                        <?php if ( isset( $state ) ): ?>

                            <?php if ( $instancia===2 ): ?>

                                <?php if ( $state ): ?>

                                    <div class="alert alert-success" role="alert">

                                        <?= $message ?>

                                        <a href="{{ route('seeds' , [$id_especialista] )}}" class="btn btn-success">Aceptar</a>

                                    </div>

                                <?php else: ?>

                                    <div class="alert alert-danger" role="alert">

                                        <?= $message ?>

                                    </div>

                                    <div>
                                        <a href="{{ route('import-to-ppff' , $id_temporal )}}" class="btn btn-success">Continuar</a>
                                    </div><br>

                                <?php endif; ?>

                            <?php endif; ?>

                        <?php endif; ?>

                        <?php if ( !isset( $state ) ): ?>

                          <div class="input-group is-invalid">
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="validatePPFF" name="file" lang="es" required>
                              <label class="custom-file-label" for="validatePPFF">Seleccione el archivo...</label>
                            </div>
                            <div class="input-group-append">
                               <button id="btn_import3" class="btn btn-outline-secondary"onmouseup="run_import_ppff()">
                                    Importar
                               </button>

                                <button id="spin_3" class="btn btn-outline-secondary" disabled hidden>
                                    <span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Analizando...
                                </button>

                            </div>
                          </div>

                        <?php endif; ?>

                        @if (Session::has("table") )
                            <p>{{ Session::get("table")}}</p>
                        @endif

                    </form>

                  </div>

                </div>

              </div>

            </div>

        </div>

    </div>

</div>

<script>
    
    function run_import_dir()
    {
        
        // document.querySelectorAll('.btn_import').forEach( (el) =>{

        //     el.setAttribute("disabled", true);

        // });

// debugger;
        document.getElementById('spin_1').removeAttribute('hidden');
        document.getElementById('btn_import1').setAttribute('hidden',true);
        document.getElementById('btn_import2').setAttribute('disabled', true);
        document.getElementById('btn_import3').setAttribute('disabled', true);

        document.getElementById("form_dir").submit();


    }

    function run_import_doc()
    {
        
        document.getElementById('spin_2').removeAttribute('hidden');
        document.getElementById('btn_import2').setAttribute('hidden',true);
        document.getElementById('btn_import1').setAttribute('disabled', true);
        document.getElementById('btn_import3').setAttribute('disabled', true);

        document.getElementById("form_doc").submit();

    }

    function run_import_ppff()
    {
        
        document.getElementById('spin_3').removeAttribute('hidden');
        document.getElementById('btn_import3').setAttribute('hidden',true);
        document.getElementById('btn_import1').setAttribute('disabled', true);
        document.getElementById('btn_import2').setAttribute('disabled', true);

        document.getElementById("form_ppff").submit();

    }


</script>

@endsection
