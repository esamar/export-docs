@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="card text-center">
      
      <div class="card-body" style = "height: calc(100vh - 90px);">
        
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Muestras</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar muestra</li>
          </ol>
        </nav>

        <div class="row">

          <div class="col-3">

            <div class="card">
              
              <div class="card-header">
              
                <b>Detalles Muestra - Operativo </b>
              
              </div>

              <div class="card-body text-left">

                <info-repo-component sample = "{{ $id_sample }}"></info-repo-component>

                <mange-repo-component sample = "{{ $id_sample }}"></mange-repo-component>

              </div>

            </div>

          </div>

          <div class="col-9">

              @if ( $option == 1)
              
                <table-service-component sample = "{{ $id_sample }}"></table-service-component>

              @elseif ($option == 2)
              
                <table-sample-component sample = "{{ $id_sample }}"></table-sample-component>

              @elseif ($option == 3)
              
                <table-users-component sample = "{{ $id_sample }}"></table-users-component>

              @endif

          </div>

        </div>

      </div>

    </div>

</div>
@endsection
