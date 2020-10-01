<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

use App\Exports\UsersExport;
use App\Imports\UsersImport;

use App\User;
use App\Director;
use App\ImportTable;

class PersonController extends Controller
{

    public function exportPdf()
    {
    	
    	$users =User::get();
    	$pdf =PDF::loadView('pdf.users',compact('users'));

    	return $pdf->download('user-list.pdf');

    }

    public function importExcel(Request $request)
    {

        session_start();

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
            $array[0][$key]['dni_err'] = ( $row[5] ? ( is_numeric($row[5]) && strlen($row[5])==8 ? 0 : 1 ) : 0 ); 
            
            $array[0][$key]['apellido_p'] = $row[6];
            $array[0][$key]['apellido_p_err'] = ( $row[6] ? 0 : 1);
            
            $array[0][$key]['apellido_m'] = $row[7];

            $array[0][$key]['nombres'] = $row[8];
            $array[0][$key]['nombres_err'] = ( $row[8] ? 0 : 1);

            $array[0][$key]['email'] = $row[9];
            $array[0][$key]['email_err'] = 0; #( $row[9] ? 0 : 1);

            $array[0][$key]['telefono1'] = $row[10];
            $array[0][$key]['telefono1_err'] = ( is_numeric($row[10]) && strlen($row[10])==9 ? 0 : 1 );
            
            $array[0][$key]['telefono2'] = $row[11];
            $array[0][$key]['telefono2_err'] = '';

            if ( $error === false )
            {

                if (    $array[0][$key]['cod_reg_err'] || 
                        $array[0][$key]['dni_err'] || 
                        $array[0][$key]['apellido_p_err'] || 
                        $array[0][$key]['nombres_err'] || 
                        $array[0][$key]['email_err'] || 
                        $array[0][$key]['telefono1_err'] || 
                        $array[0][$key]['telefono2_err'] )
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
                
                $resumen = '';

                if ( $array[0][$key]['cod_reg_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Código de registrador";
                }
                if ( $array[0][$key]['cod_mod8_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Formato de Cod Mod no válido";
                }
                if ( $array[0][$key]['dni_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Dni invalido ";
                }
                if ( $array[0][$key]['apellido_p_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Campo apellido incorrecto";
                }
                if ( $array[0][$key]['nombres_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Campo nombres incorrecto";
                }
                if ( $array[0][$key]['email_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Formato de email incorrecto";
                }
                if ( $array[0][$key]['telefono1_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Formato Telefono 1 incorrecto";
                }
                if ( $array[0][$key]['telefono2_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Formato Telefono 2 incorrecto";
                }

                $row .= '<tr ' . ( $resumen ? 'class="table-danger"' : '' ) . '>'.
                            '<th scope="row">' . $key . '</td>'.
                            '<td ' . ( $val['cod_reg_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['cod_reg'] . '</td>'.
                            '<td ' . ( $val['cod_mod8_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['cod_mod8'] . '</td>'.
                            '<td ' . ( $val['dni_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['dni'] . '</td>'.
                            '<td ' . ( $val['apellido_p_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['apellido_p'] . '</td>'.
                            '<td>' . $val['apellido_m'] . '</td>'.
                            '<td ' . ( $val['nombres_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['nombres'] . '</td>'.
                            '<td ' . ( $val['email_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['email'] . '</td>'.
                            '<td ' . ( $val['telefono1_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['telefono1'] . '</td>'.
                            '<td ' . ( $val['telefono2_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['telefono2'] . '</td>'.
                            '<td>' . $resumen . '</td>'.

                        '</tr>';

            }

            return view('error')
                        ->with('resumeTable' , $row )
                        ->with('message' , 'Se ha detectado algunos errores en el archivo que está intentando importar. No se puede continuar.')
                        ->with('state_error', true )
                        ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] );
        }


        $id_temporal = idate('y', time()).idate('m', time()).idate('d', time()).idate('h', time()).idate('i', time()).idate('s', time()).rand(1, 100);

        foreach ($array[0] as $key => $val) 
        {

            $temp_table = new ImportTable;
            
            $temp_table->id_temp = $id_temporal;
            $temp_table->id_fila = $key;
            $temp_table->cod_reg = $val['cod_reg'];
            $temp_table->cod_mod = $val['cod_mod8'];
            $temp_table->dni = $val['dni'];
            $temp_table->ape_p = $val['apellido_p'];
            $temp_table->ape_m = $val['apellido_m'];
            $temp_table->nombres = $val['nombres'];
            $temp_table->email = $val['email'];
            $temp_table->telefono1 = $val['telefono1'];
            $temp_table->telefono2 = $val['telefono2'];
            $temp_table->state = 0;
            $temp_table->created_by = $_SESSION['ID_ESPECIALISTA'];
            $temp_table->save();
        
        }

        $integrity_table = DB::select('SELECT 
                                            id_fila,
                                            cod_mod,
                                            CASE WHEN ISNULL(B.Ie_CodigoModular) THEN
                                                1
                                                ELSE
                                                0
                                            END COD_MOD_ERR,
                                            cod_reg,
                                            CASE WHEN ISNULL( B.Age_Codigo) THEN
                                                1
                                            ELSE
                                                0
                                            END COD_REG_ERR,
                                            dni,
                                            CASE WHEN ( dni = CDIR_DOCUMENTO ) THEN
                                                1
                                            ELSE
                                                0
                                            END DNI_ERR,
                                            ape_p,
                                            ape_m,
                                            nombres,
                                            email,
                                            telefono1,
                                            telefono2
                                        FROM import_tables A 
                                        LEFT JOIN tb_registro B 
                                        ON ( Ie_CodigoModular = cod_mod AND cod_reg = Age_Codigo )
                                        LEFT JOIN tb_dir_contacto C 
                                        ON ( B.Ie_CodigoModular = C.Ie_CodigoModular 
                                        AND dni = CDIR_DOCUMENTO AND CDIR_ACTIVO = 1)
                                        WHERE id_temp = "' . $id_temporal. '";');

        
        if ( $integrity_table )
        {

            $row = '';

            $error = false;

            foreach ($integrity_table as $key => $val) 
            {
                
                $resumen = '';

                if ( $val->COD_MOD_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Código módular no existe";
                    
                    $error = true;

                }
                if ( $val->COD_REG_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Código de monitor no existe";
                 
                    $error = true;
                
                }
                if ( $val->DNI_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "El dni ya se encuentra registrado";
                
                    $error = true;
                
                }

                $row .= '<tr ' . ( $resumen ? 'class="table-danger"' : '' ) . '>'.
                            '<th scope="row">' . $val->id_fila . '</td>'.
                            '<td ' . ( $val->COD_REG_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->cod_reg . '</td>'.
                            '<td ' . ( $val->COD_MOD_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->cod_mod . '</td>'.
                            '<td ' . ( $val->DNI_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->dni . '</td>'.
                            '<td>' . $val->ape_p . '</td>'.
                            '<td>' . $val->ape_m . '</td>'.
                            '<td>' . $val->nombres . '</td>'.
                            '<td>' . $val->email . '</td>'.
                            '<td>' . $val->telefono1 . '</td>'.
                            '<td>' . $val->telefono2 . '</td>'.
                            '<td>' . $resumen . '</td>'.
                        '</tr>';

                $message = '';

                if ( $error )
                {

                    $message = 'Se ha detectado errores de integridad. No se puede registrar.';

                    DB::delete('DELETE FROM import_tables WHERE id_temp = "' . $id_temporal. '";');

                }
                else
                {

                    $message = 'Atención: Se va a registrar la siguiente información. Presione el boton "Continuar" para proceder con el registro.';

                    \Storage::disk('local')->put( $id_temporal . '-' . $file->getClientOriginalName(),  \File::get($file));

                }

            }

            return view('error')
                    ->with('resumeTable' , $row )
                    ->with('message' , $message )
                    ->with('state_error', $error )
                    ->with('id_temporal', $id_temporal)
                    ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] );
                    
        }


        // $query_compare = Director::all();
        // $cod_mon = array_column($array[0],0);
        // foreach ($query_compare as $key => $value) {
        //     # code...
        //     echo $value->Ie_CodigoModular.'<br>';
        // }
        // var_dump( $cod_mon );
        // dd($query_compare->verifyData());

        // dd($array[0]);

        //return back()->with('message', 'Importacion de usuario completada');

    }

    public function exportExcel()
    {
    	return Excel::download(new UsersExport, 'user-list.xlsx');
    }

    public function importTableDirector( $id_temporal , $id_especialista )
    {

        $resp = DB::insert('INSERT INTO tb_dir_contacto 
                                ( Ie_CodigoModular, CDIR_TIPO_DOC, CDIR_DOCUMENTO, CDIR_NOMBRE, CDIR_APELLIDO_P, CDIR_APELLIDO_M, CDIR_EMAIL, CDIR_TELEFONO, CDIR_TELEFONO2, CDIR_ACTIVO, id_seed) 
                    SELECT cod_mod , 1 , dni, nombres, ape_p, ape_m, email, telefono1, telefono2 , 1 , id 
                    FROM  import_tables 
                    WHERE id_temp = "' . $id_temporal. '";');

        DB::delete('UPDATE import_tables SET state = 1 WHERE id_temp = "' . $id_temporal. '";');

        if ( $resp )
        {
        
            $message = 'Se ha importado los datos correctamente.';

        }
        else
        {
            
            $message = 'No se ha podido importar los datos.';

        }

        return view('welcome')
                ->with('message' , $message )
                ->with('state', $resp )
                ->with('id_especialista' , $id_especialista );

    }

}
