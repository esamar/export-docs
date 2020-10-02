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

            $array[0][$key]['cod_reg'] = trim($row[0]);

            $e_cod_reg = 0;

            if ( !is_numeric( $row[0] ) )
            {
                $e_cod_reg = 1;
            }
            else
            {

                if ( (int) $row[0] > 255 || (int) $row[0] < 1 )
                {
                    $e_cod_reg = 1;
                }

            }

            $array[0][$key]['cod_reg_err'] = $e_cod_reg;
            #----------------------------------------------

            $array[0][$key]['cod_mod8'] = trim($row[1] . $row[2]);

            if ( is_numeric($row[1] . $row[2]) && strlen($row[1] . $row[2])==8 )
            {

                $e_cod_mor = 0;

            } 
            else
            {
                $e_cod_mor = 1;
            }

            $array[0][$key]['cod_mod8_err'] = $e_cod_mor;

            #----------------------------------------------
            
            $array[0][$key]['dni'] = trim($row[5]);
            
            if ( $row[5] )
            {
                if ( is_numeric($row[5]) && strlen($row[5])==8 )
                {
                    $e_dni = 0;
                }
                else
                {
                    $e_dni = 1;
                }
            }
            else
            {
                $e_dni = 0;
            }

            $array[0][$key]['dni_err'] = $e_dni; 
            
            $array[0][$key]['apellido_p'] = trim($row[6]);
            $array[0][$key]['apellido_p_err'] = ( $row[6] ? 0 : 1);
            
            $array[0][$key]['apellido_m'] = trim($row[7]);

            $array[0][$key]['nombres'] = trim($row[8]);
            $array[0][$key]['nombres_err'] = ( $row[8] ? 0 : 1);

            $array[0][$key]['email'] = trim($row[9]);

            $e_email = 0;

            if ( $row[9] )
            {

                if ( filter_var( $row[9] , FILTER_VALIDATE_EMAIL) )
                {

                    $e_email = 0;

                }
                else
                {

                    $e_email = 1;

                }

            }

            $array[0][$key]['email_err'] = trim($e_email);

            $array[0][$key]['telefono1'] = trim($row[10]);

            $e_telefono1 = 0;

            if ( !strlen($row[10])==9 ) 
            {

                $e_telefono1 = 1;

            }
            else
            {

                if ( is_numeric( $row[10] ) )
                {

                    if ( substr( $row[10] , 0 , 1 ) == '9' || substr( $row[10] , 0 , 1 ) === '0' )
                    {
                        
                        $e_telefono1 = 0;

                    }
                    else
                    {

                        $e_telefono1 = 1;

                    }

                }
                else
                {

                    $e_telefono1 = 1;

                }

            }

            $array[0][$key]['telefono1_err'] = $e_telefono1;
            
            $array[0][$key]['telefono2'] = trim($row[11]);

            $e_telefono2 = 0;

            if ( $row[11] )
            {

                if ( !strlen($row[11])==9 ) 
                {

                    $e_telefono2 = 1;

                }
                else
                {

                    if ( is_numeric( $row[11] ) )
                    {

                        if ( substr( $row[11] , 0 , 1 ) == '9' || substr( $row[11] , 0 , 1 ) === '0' )
                        {
                            
                            $e_telefono2 = 0;

                        }
                        else
                        {

                            $e_telefono2 = 1;

                        }

                    }
                    else
                    {

                        $e_telefono2 = 1;

                    }

                }

            }

            $array[0][$key]['telefono2_err'] = $e_telefono2;

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
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Formato Teléfono 1 incorrecto";
                }
                if ( $array[0][$key]['telefono2_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Formato Teléfono 2 incorrecto";
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
            $temp_table->id_fila = $key + 1;
            $temp_table->cod_reg = $val['cod_reg'];
            $temp_table->cod_mod = $val['cod_mod8'];
            $temp_table->dni = ( $val['dni'] ? $val['dni'] : NULL );
            $temp_table->ape_p = $val['apellido_p'];
            $temp_table->ape_m = $val['apellido_m'];
            $temp_table->nombres = $val['nombres'];
            $temp_table->email = $val['email'];
            $temp_table->telefono1 = $val['telefono1'];
            $temp_table->telefono2 = $val['telefono2'];
            $temp_table->state = 0;
            $temp_table->info_error = '';
            $temp_table->created_by = $_SESSION['ID_ESPECIALISTA'];
            $temp_table->save();
        
        }

        $integrity_table = DB::select('SELECT
                                        id,
                                        id_fila,
                                        cod_mod,
                                        cod_reg,
                                        IF( ISNULL( B.Age_Codigo ) , 1 , 0 ) COD_REG_ERR, /*#No existe codigo registrador*/ 
                                        IF( cod_mod = C.Ie_CodigoModular , 0 , 1 ) COD_MOD_ERR , /*#Codigo modular no existe*/ 
                                        IF( cod_reg = C.Age_Codigo , 0 , 1 ) COD_REG_COD_MOD_ERR, /*#El codigo de registrador no está asociado al codigo modular*/
                                        IF( ISNULL( CDIR_CODIGO ) , 0, 1 ) COD_CONTACTO_EX, /*#La ie ya tiene un director asignado*/ 
                                        IF( ISNULL( E.CDIR_DOCUMENTO ) , 0 , 1 ) COD_DNI_EX, /*#El dni se ha registrado anteriormente*/
                                        dni,
                                        ape_p,
                                        ape_m,
                                        nombres,
                                        email,
                                        telefono1,
                                        telefono2
                                        FROM import_tables A 
                                        LEFT JOIN tb_agente B ON (B.Age_Codigo = A.cod_reg)
                                        LEFT JOIN tb_registro C ON (C.Ie_CodigoModular = A.cod_mod AND REG_TIPO = 1)
                                        LEFT JOIN tb_dir_contacto D ON (C.Ie_CodigoModular = D.Ie_CodigoModular AND CDIR_ACTIVO = 1)
                                        LEFT JOIN ( SELECT 
                                                        CDIR_DOCUMENTO 
                                                    FROM tb_dir_contacto 
                                                    WHERE NOT ISNULL(CDIR_DOCUMENTO) AND NOT CDIR_DOCUMENTO = "" ) E 
                                                    ON (E.CDIR_DOCUMENTO = A.dni)
                                        WHERE id_temp = "' . $id_temporal. '";');

        
        if ( $integrity_table )
        {

            $row = '';

            $error = false;

            $error_update = [];

            foreach ($integrity_table as $key => $val) 
            {
                
                $resumen = '';

                if ( $val->COD_MOD_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Código módular no existe";
                    
                    $error = true;

                }
                if ( $val->COD_CONTACTO_EX )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "La IE ya tiene asignado un director";
                
                    $error = true;
                
                }
                if ( $val->COD_REG_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Código de monitor no existe";
                 
                    $error = true;
                
                }
                if ( $val->COD_REG_COD_MOD_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "El código de registrador no está asociado al código modular";
                 
                    $error = true;
                
                }
                if ( $val->COD_DNI_EX )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "El dni se ha registrado anteriormente";
                
                    $error = true;
                
                }

                if ( $resumen )
                {

                    array_push($error_update, 
                                            [ 
                                                'id' => $val->id , 
                                                'info_error' => $resumen 
                                            ]);

                }


                $row .= '<tr ' . ( $resumen ? 'class="table-danger"' : '' ) . '>'.
                            '<th scope="row">' . $val->id_fila . '</td>'.
                            '<td ' . ( $val->COD_REG_ERR || $val->COD_REG_COD_MOD_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->cod_reg . '</td>'.
                            '<td ' . ( $val->COD_MOD_ERR || $val->COD_CONTACTO_EX ? 'class="bg-danger"' : '' ) . '>' . $val->cod_mod . '</td>'.
                            '<td ' . ( $val->COD_DNI_EX || $val->COD_CONTACTO_EX ? 'class="bg-danger"' : '' ) . '>' . $val->dni . '</td>'.
                            '<td>' . $val->ape_p . '</td>'.
                            '<td>' . $val->ape_m . '</td>'.
                            '<td>' . $val->nombres . '</td>'.
                            '<td>' . $val->email . '</td>'.
                            '<td>' . $val->telefono1 . '</td>'.
                            '<td>' . $val->telefono2 . '</td>'.
                            '<td>' . $resumen . '</td>'.
                        '</tr>';

            }

            $message = '';

            if ( $error )
            {

                $message = 'Se ha detectado errores de integridad. No se puede registrar.';

                // dd($error_update);

                // DB::delete('DELETE FROM import_tables WHERE id_temp = "' . $id_temporal. '";');

                foreach ($error_update as $key => $val) 
                {

                    DB::table('import_tables')->where("id", $val['id'])->update(["info_error" => $val['info_error'] ]);

                    // $temp_table = new ImportTable;

                    // $temp_table->exists = true;

                    // $temp_table->id = $val['id'];

                    // $temp_table->info_error = $val['info_error'];

                    // $temp_table->save();
                
                }

                DB::update('UPDATE import_tables SET state = 2 WHERE id_temp = "' . $id_temporal. '";');

            }
            else
            {

                $message = 'Atención: Se va a registrar la siguiente información. Presione el boton "Continuar" para proceder con el registro.';

                \Storage::disk('local')->put( $id_temporal . '-' . $file->getClientOriginalName(),  \File::get($file));

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

        DB::update('UPDATE import_tables SET state = 1 WHERE id_temp = "' . $id_temporal. '";');

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
