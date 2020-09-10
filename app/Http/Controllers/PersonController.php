<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UsersExport;
use App\Imports\UsersImport;

use App\User;

class PersonController extends Controller
{
    //

    public function exportPdf()
    {
    	
    	$users =User::get();
    	$pdf =PDF::loadView('pdf.users',compact('users'));

    	return $pdf->download('user-list.pdf');

    }

    public function importExcel(Request $request)
    {

    	$file = $request->file('file');
        
        //Excel::import(new UsersImport, $file );
        $array = Excel::toArray(new UsersImport, $file );
        dd($array);

        //return back()->with('message', 'Importacion de usuario completada');

    }

    public function exportExcel()
    {
    	return Excel::download(new UsersExport, 'user-list.xlsx');
    }
}
