@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card text-center">
      <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
          <li class="nav-item">
            <a class="nav-link active" aria-current="true" href="#">Inicio</a>
          </li>
        </ul>
      </div>
      
      <div class="card-body">
        
        <h5 class="card-title"><b>SAMPLES v1.0 - RepositorioIE v1.0</b></h5>

        <p class="card-text">Módulo de administración de poblaciones, muestras para servicios.</p>
      
        <div class="row p-4">

            <table-repo-component></table-repo-component>

        </div>

      </div>

    </div>

</div>
@endsection
