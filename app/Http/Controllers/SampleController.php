<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SampleController extends Controller
{

    public function index()
    {
        
        return view('sample');

    }

    public function edit( $id_sample , $option )
    {

        return view('sample/edit')
                ->with( 'id_sample', $id_sample )
                ->with( 'option', $option );

    }


}
