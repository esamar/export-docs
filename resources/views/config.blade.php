@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card text-center">

        <div class="card-header" hidden>

            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="true" href="#">Inicio</a>
                </li>
            </ul>

        </div>
      
        <div class="card-body" style = "height: calc(100vh - 90px);">
            
            <nav aria-label="breadcrumb">

                <ol class="breadcrumb">

                    <li class="breadcrumb-item">
                        <a href="http://localhost/ImportSIREG/export-docs/public/sample">Muestras</a>
                    </li> 

                    <li aria-current="page" class="breadcrumb-item active">
                        Editar muestra
                    </li>
                </ol>
            </nav>
            
            <form-main-component></form-main-component>

        </div>

    </div>

</div>
@endsection
