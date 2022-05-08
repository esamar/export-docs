<?php

namespace App\Http\Controllers\Api\Sireg;

use App\Sireg\Director;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sireg\V1\DirectorResource;

class DirectorController extends Controller
{

    public function index()
    {
        
        return DirectorResource::collection(Director::getAllDirectorRegistro());

    }

    public function store(Request $request)
    {
        //
    }

    // public function show(Director $director)
    public function show($id_director)
    {

        return new DirectorResource(Director::getDirectorRegistro($id_director)[0]);

    }

    public function update(Request $request, Director $director)
    {
        //
    }

    public function destroy(Director $director)
    {
        //
    }
}
