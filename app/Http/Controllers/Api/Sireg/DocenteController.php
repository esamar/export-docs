<?php

namespace App\Http\Controllers\Api\Sireg;

use App\Sireg\Docente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sireg\V1\DocenteResource;

class DocenteController extends Controller
{

    public function index()
    {
        
        return DocenteResource::collection(Docente::getAllDocenteRegistro());

    }

    public function store(Request $request)
    {
        //
    }

    public function show($id_docente)
    {
        
        return new DocenteResource(Docente::getDocenteRegistro($id_docente)[0]);

    }

    public function update(Request $request, Docente $docente)
    {
        //
    }

    public function destroy(Docente $docente)
    {
        //
    }
}
