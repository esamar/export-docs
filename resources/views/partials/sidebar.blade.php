<div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 72px;height: calc(100vh - 56px);">

    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
      <li class="nav-item">
        <a href="#" class="nav-link py-3 border-bottom" aria-current="page" title="Home" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="16" height="16" role="img" aria-label="Home"><use xlink:href="#home"/></svg>
        </a>
      </li>
      <li>
        <a href="#" class="nav-link py-3 border-bottom" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="16" height="16"><use xlink:href="#speedometer2"/></svg>
        </a>
      </li>
      <li>
        <a href="#" class="nav-link py-3 border-bottom" title="Orders" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="16" height="16"><use xlink:href="#table"/></svg>
        </a>
      </li>

      <li>
        <a href="#" class="nav-link py-3 border-bottom" title="Products" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="16" height="16"><use xlink:href="#grid"/></svg>
        </a>
      </li>

<!--       <li>
        <a href="#" class="nav-link py-3 border-bottom" title="Usuarios" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="16" height="16"><use xlink:href="#people-circle"/></svg>
        </a>
      </li> -->

      <li>
        <a href="{{ route('config.index' , ['option' => 1 ]) }}" class="nav-link py-3 border-bottom" title="Editor de configuraciones" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="16" height="16"><use xlink:href="#bi-gear-wide"/></svg>
        </a>
      </li>

      <li>
        <a href="{{ route('seeds',['id_especialista' => 12]) }}" class="nav-link py-3 border-bottom" title="SEEDS SIREG" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="16" height="16"><use xlink:href="#egg"/></svg>
        </a>
      </li>

      <li>
        <a href="{{ route('sample') }}" class="nav-link active py-3 border-bottom" title="Poblacion, muestra y usuarios" data-bs-toggle="tooltip" data-bs-placement="right">
          <svg class="bi" width="16" height="16"><use xlink:href="#globe"/></svg>
        </a>
      </li>
      
    </ul>
    <div class="dropdown border-top">
      <a href="#" class="d-flex align-items-center justify-content-center p-3 link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="mdo" width="16" height="16" class="rounded-circle">
      </a>
      <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
        <li><a class="dropdown-item" href="#">New project...</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
      </ul>
    </div>
  </div>