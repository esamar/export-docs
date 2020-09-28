<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\UsersExport;
use App\Imports\UsersImport;

use App\User;
use App\Director;

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


        unset($array[0][0]);
        unset($array[0][1]);

        $row_header = $array[0][2];

        if ( count($array[0][2]) != 13 )
        {

            echo "Error: El archivo no tiene la estructura correcta<br>" . PHP_EOL;

        }

        $row_master = [ 0 => "COD_MONITOR",
                        1 => "COD_MOD",
                        2 => "ANEXO",
                        3 => "IE",
                        4 => "NIVEL",
                        5 => "DNI",
                        6 => "APELLIDO PATERNO",
                        7 => "APELLIDO MATERNO",
                        8 => "NOMBRES",
                        9 => "EMAIL_DIRECTOR",
                        10 => "TELEFONO 1",
                        11 => "TELÉFONO 2",
                        12 => "OBSERVACIONES"];

        if ( array_diff($row_header, $row_master) )
        {

            echo "Error: La cabecera no tiene el formato correcto<br>" . PHP_EOL;

        }
        #QUITAMOS EL HEADER, YA NO ES NECESARIO        
        unset($array[0][2]);
        $error = false;

        foreach ($array[0] as $key => $row) {

            $array[0][$key]['cod_reg'] = $row[0];
            $array[0][$key]['cod_reg_err'] = ( (int)$row[0] > 0 ? 0 : 1 );

            $array[0][$key]['cod_mod8'] = $row[1] . $row[2];
            $array[0][$key]['cod_mod8_err'] = ( is_numeric($row[1] . $row[2]) && strlen($row[1] . $row[2])==8 ? 0 : 1 );
            
            $array[0][$key]['dni'] = $row[5];
            $array[0][$key]['dni_err'] = ( is_numeric($row[5]) && strlen($row[5])==8 ? 0 : 1 );
            
            $array[0][$key]['apellido_p'] = $row[6];
            $array[0][$key]['apellido_p_err'] = ( $row[6] ? 0 : 1);
            
            $array[0][$key]['apellido_m'] = $row[7];

            $array[0][$key]['nombres'] = $row[8];
            $array[0][$key]['nombres_err'] = ( $row[8] ? 0 : 1);

            $array[0][$key]['email'] = $row[9];
            $array[0][$key]['email_err'] = ( $row[9] ? 0 : 1);

            $array[0][$key]['telefono1'] = $row[10];
            $array[0][$key]['telefono1_err'] = ( is_numeric($row[10]) && strlen($row[10])==9 ? 0 : 1 );
            
            $array[0][$key]['telefono2'] = $row[11];
            $array[0][$key]['telefono2_err'] = '';

            if ( $error === false )
            {

                if ( $array[0][$key]['cod_reg_err'] || $array[0][$key]['dni_err'] || $array[0][$key]['nombres_err'] || $array[0][$key]['email_err'] || $array[0][$key]['telefono1_err'] || $array[0][$key]['telefono2_err'] )
                {
                    $error = true;
                }

            }

            unset(
                    $array[0][$key][0],
                    $array[0][$key][1],
                    $array[0][$key][2],
                    $array[0][$key][3],
                    $array[0][$key][4],
                    $array[0][$key][5],
                    $array[0][$key][6],
                    $array[0][$key][7],
                    $array[0][$key][8],
                    $array[0][$key][9],
                    $array[0][$key][10],
                    $array[0][$key][11],
                    $array[0][$key][12]
                    );

        }

        if ( $error )
        {

            $row = '';

            foreach ($array[0] as $key => $val) 
            {
                
                $row .= '<tr>'.
                            '<td>' . $val['cod_reg'] . '</td>'.
                        '</tr>';

            }

            return back()->with('message' , '<table>'.$row.'</table>' );
        }

        // $query_compare = Director::all();
        // $cod_mon = array_column($array[0],0);
        // foreach ($query_compare as $key => $value) {
        //     # code...
        //     echo $value->Ie_CodigoModular.'<br>';
        // }
        // var_dump( $cod_mon );
        // dd($query_compare->verifyData());
        dd($array[0]);

        //return back()->with('message', 'Importacion de usuario completada');

    }

    public function exportExcel()
    {
    	return Excel::download(new UsersExport, 'user-list.xlsx');
    }
}
