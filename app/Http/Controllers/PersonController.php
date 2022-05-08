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
use App\ImportTableDir;
use App\ImportTableDoc;
use App\ImportTablePPFF;

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

        $array = Excel::toArray(new UsersImport, $file );

        unset($array[0][0]);
        unset($array[0][1]);

        $row_header = $array[0][2];

        if ( count($array[0][2]) != 13 )
        {

            // echo "Error: El archivo no tiene la estructura correcta<br>" . PHP_EOL;

            return view('error')
            ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] )
            ->with('error_estructura' , 1 );

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

            // echo "Error: La cabecera no tiene el formato correcto<br>" . PHP_EOL;

            return view('error')
            ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] )
            ->with('error_estructura' , 2 );
        }

        #QUITAMOS EL HEADER, YA NO ES NECESARIO        
        unset($array[0][2]);

        $error = false;
        
        $alert = false;

        foreach ($array[0] as $key => $row) 
        {

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
                            '<td></td>'.
                            '<td ' . ( $val['dni_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['dni'] . '</td>'.
                            '<td ' . ( $val['apellido_p_err'] ? 'class="bg-danger"' : '' ) . ' style="text-align: left;">' . $val['apellido_p'] . '</td>'.
                            '<td>' . $val['apellido_m'] . '</td>'.
                            '<td ' . ( $val['nombres_err'] ? 'class="bg-danger"' : '' ) . ' style="text-align: left;">' . $val['nombres'] . '</td>'.
                            '<td ' . ( $val['email_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['email'] . '</td>'.
                            '<td ' . ( $val['telefono1_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['telefono1'] . '</td>'.
                            '<td ' . ( $val['telefono2_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['telefono2'] . '</td>'.
                            '<td>' . $resumen . '</td>'.

                        '</tr>';

            }

            return view('error')
                        ->with('resumeTable' , $row )
                        ->with('message' , 'Se ha detectado algunos errores en el archivo que está intentando importar. No se puede continuar.')
                        ->with('state_error', 1 )
                        ->with('id_temporal', NULL )  
                        ->with('error_estructura', 0 )
                        ->with('instancia' , 0 )
                        ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] );

        }


        $id_temporal = idate('y', time()).idate('m', time()).idate('d', time()).idate('h', time()).idate('i', time()).idate('s', time()).rand(1, 100);

        foreach ($array[0] as $key => $val) 
        {

            $temp_table = new ImportTableDir;
            
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
        
        #CREAR PERSONA DESDE TEMP DIRECTOR
        DB::insert( 'CALL sp_create_dir("' . $id_temporal . '")');

        DB::update('CALL sp_update_id_person_dir("' . $id_temporal . '")');

        $integrity_table = DB::select('SELECT
                                            id,
                                            id_fila,
                                            cod_mod,
                                            cod_reg,
                                            IF( ISNULL( B.Age_Codigo ) , 1 , 0 ) COD_REG_ERR, /*#No existe codigo registrador*/ 
                                            IF( cod_mod = C.Ie_CodigoModular , 0 , 1 ) COD_MOD_ERR , /*#Codigo modular no existe*/ 
                                            IF( cod_reg = C.Age_Codigo , 0 , 1 ) COD_REG_COD_MOD_ERR, /*#El codigo de registrador no está asociado al codigo modular*/
                                            IF( ISNULL( CDIR_CODIGO ) , 0, 1 ) COD_CONTACTO_EX, /*#La ie ya tiene un director asignado*/ 
                                            IF( ISNULL( E.PER_CODIGO ) , 0 , 1 ) COD_DNI_EX, /*#El dni se ha registrado anteriormente*/
                                            ID_GRUPO, /*#id que agrupa a los directores repetidos*/
                                            dni,
                                            ape_p,
                                            ape_m,
                                            nombres,
                                            email,
                                            telefono1,
                                            telefono2
                                        FROM import_table_dirs A 
                                        LEFT JOIN tb_agente B ON (B.Age_Codigo = A.cod_reg)
                                        LEFT JOIN tb_registro C ON (C.Ie_CodigoModular = A.cod_mod AND REG_TIPO = 1)
                                        LEFT JOIN tb_dir_contacto D ON (C.Ie_CodigoModular = D.Ie_CodigoModular AND CDIR_ACTIVO = 1)
                                        LEFT JOIN ( SELECT 
                                                       DISTINCT PER_CODIGO,
                                                       Ie_CodigoModular
                                                    FROM tb_dir_contacto 
                                                    WHERE NOT ISNULL(PER_CODIGO)  ) E 
                                                    ON (E.PER_CODIGO = A.id_person AND E.Ie_CodigoModular = cod_mod)

                                        LEFT JOIN (SELECT 
                                                        @i := @i + 1 as ID_GRUPO, 
                                                        NOMBRE_COMPUESTO
                                                    FROM
                                                    (SELECT
                                                        CONCAT( ape_p, ape_m, nombres ) NOMBRE_COMPUESTO, 
                                                        COUNT(id) CONJUNTO
                                                    FROM import_table_dirs 
                                                        WHERE id_temp = "' . $id_temporal. '" 
                                                        GROUP BY concat(ape_p, ape_m,nombres) 
                                                        HAVING CONJUNTO > 1) AS A
                                                    CROSS JOIN ( SELECT @i := 0 ) R ) G ON ( NOMBRE_COMPUESTO = CONCAT(ape_p, ape_m, nombres) )
                                                    
                                        WHERE id_temp = "' . $id_temporal . '" ;');

        
        if ( $integrity_table )
        {

            $row = '';

            $error_update = [];

            $id_grupo_previo = 0;

            $color_grupo = 'class="bg-warning"';

            foreach ($integrity_table as $key => $val) 
            {

                $error_row = false;

                $alert_row = false;
                
                $resumen = '';

                if ( $val->COD_MOD_ERR )
                {
                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "Código módular no existe";
                    
                    $error_row = true;

                }
                if ( $val->COD_CONTACTO_EX )
                {
                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "La IE ya tiene asignado un director";
                
                    $error_row = true;
                
                }
                if ( $val->COD_REG_ERR )
                {
                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "Código de monitor no existe";
                 
                    $error_row = true;
                
                }
                if ( $val->COD_REG_COD_MOD_ERR )
                {
                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "El código de registrador no está asociado al código modular";
                 
                    $error_row = true;
                
                }

                if ( $val->COD_DNI_EX && $error_row === false )
                {
                    $resumen .= ( $resumen ? '<br>-: ' : 'Error: ') . "Este DNI ya está asociado a la misma IE";
                
                    $error_row = true;
                
                }

                if ( !$val->dni && $error_row === false)
                {
                    $resumen .= ( $resumen ? '<br>-Alerta: ' : 'Alerta: ') . "Este usuario no tiene DNI. Se registrará un ID genérico";
                
                    $alert_row = true;
                
                }

                if ( $val->ID_GRUPO && $error_row === false)
                {
                    $resumen .= ( $resumen ? '<br>-Alerta: ' : 'Alerta: ') . "Este usuario tiene multiples IE en el archivo de carga. Se ha agrupado con un ID de AGRUPAMIENTO";
                
                    $alert_row = true;
                
                }

                if ( $resumen )
                {

                    array_push($error_update, 
                                            [ 
                                                'id' => $val->id , 
                                                'info_error' => $resumen 
                                            ]);

                }

                if ( $id_grupo_previo != $val->ID_GRUPO )
                {

                    if ( $color_grupo == 'class="bg-warning"' )
                    {
                        $color_grupo = 'class="bg-info"';
                    }
                    else
                    {
                        $color_grupo = 'class="bg-warning"';
                    }
                }

                if ( $error_row === true  )
                {
                    $error = true; 
                }

                if ( $alert_row === true  )
                {
                    $alert = true; 
                }

                $row .= '<tr ' . ( $error_row ? 'class="table-danger"' : ( $alert_row ? 'class="table-warning"' : '' ) ) . '>'.
                            '<th scope="row">' .$val->id_fila . '</td>'.
                            '<td ' . ( $val->COD_REG_ERR || $val->COD_REG_COD_MOD_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->cod_reg . '</td>'.
                            '<td ' . ( $val->COD_MOD_ERR || $val->COD_CONTACTO_EX ? 'class="bg-danger"' : '' ) . '>' . $val->cod_mod . '</td>'.
                            
                            '<td ' . ( $val->ID_GRUPO ? $color_grupo : '' ) . '>' . $val->ID_GRUPO . '</td>'.

                            '<td ' . ( $val->COD_CONTACTO_EX ? 'class="bg-danger"' : '' ) . '>' . ( $val->dni ? $val->dni : 'ID Autogenerado'). '</td>'.

                            '<td style="text-align: left;">' . $val->ape_p . '</td>'.
                            '<td style="text-align: left;">' . $val->ape_m . '</td>'.
                            '<td style="text-align: left;">' . $val->nombres . '</td>'.
                            '<td>' . $val->email . '</td>'.
                            '<td>' . $val->telefono1 . '</td>'.
                            '<td>' . $val->telefono2 . '</td>'.
                            '<td>' . $resumen . '</td>'.
                        '</tr>';

                $id_grupo_previo = $val->ID_GRUPO;

            }

            $message = '';

            if ( $alert === true || $error === true )
            {

                foreach ($error_update as $key => $val) 
                {

                    DB::table('import_table_dirs')->where("id", $val['id'] )->update( ["info_error" => $val['info_error'] ]);
                
                }

            }

            if ( $error )
            {

                $message = ':( Se ha detectado errores de integridad. No se puede registrar.';

                $e_state = 1;

                DB::update('UPDATE import_table_dirs SET state = 2 WHERE id_temp = "' . $id_temporal . '";');

            }
            else
            {

                if ( $alert )
                {

                    $message = 'Advertencia: Se ha definido alertas en cada fila sombreada. Revise exhaustivamente el cuadro, si es correcto presione el botón "Importar" o "Cancelar" para abortar la importación.';
                    
                    $e_state = 2;

                }
                else
                {

                    $message = 'Se ve bien! : Se va a registrar la siguiente información. Presione el boton "Importar" para proceder con el registro.';

                    $e_state = 0;

                }


                \Storage::disk('local')->put( $id_temporal . '-' . $file->getClientOriginalName(),  \File::get($file));

            }

            return view('error')
                    ->with('resumeTable' , $row )
                    ->with('message' , $message )
                    ->with('state_error', (int)$e_state )
                    ->with('id_temporal', $id_temporal)
                    ->with('instancia' , 0 )
                    ->with('error_estructura',0)
                    ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] );
                    
        }

        //return back()->with('message', 'Importacion de usuario completada');

    }

    public function exportExcel()
    {

    	return Excel::download(new UsersExport, 'user-list.xlsx');
   
    }

    public function importTableDirector( $id_temporal , $id_especialista )
    {

        $resp = DB::insert('INSERT INTO tb_dir_contacto 
                                (   
                                Ie_CodigoModular, 
                                PER_CODIGO,
                                CDIR_ACTIVO,
                                id_seed) 
                            SELECT 
                                cod_mod,
                                id_person, 
                                1,
                                id 
                            FROM  
                                import_table_dirs 
                            WHERE 
                                id_temp = "' . $id_temporal. '" AND state = 0;');

        DB::update('UPDATE import_table_dirs SET state = 1 WHERE id_temp = "' . $id_temporal. '";');

        if ( $resp )
        {
        
            $message = 'Se ha importado los datos correctamente.';

        }
        else
        {
            
            $message = 'No se ha podido importar los datos.';

        }

        return view('seeds')
                ->with('message' , $message )
                ->with('state', $resp )
                ->with('instancia' , 0 )
                ->with('id_especialista' , $id_especialista );

    }

    public function importExcelDocente(Request $request)
    {

        session_start();

        $file = $request->file('file');

        $array = Excel::toArray(new UsersImport, $file );

        unset($array[0][0]);

        unset($array[0][1]);

        $row_header = $array[0][2];

        if ( count($array[0][2]) != 15 )
        {

            // echo "Error: El archivo no tiene la estructura correcta<br>" . PHP_EOL;

            return view('error')
                    ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] )
                    ->with('error_estructura' , 1 );


        }
     
        $row_master = [ 0 => "COD_MONITOR",
                        1 => "COD_MOD",
                        2 => "ANEXO",
                        3 => "IE",
                        4 => "NIVEL",
                        5 => "GRADO",
                        6 => "SECCIÓN EN LA QUE ENSEÑA",
                        7 => "ÁREA EN LA QUE ENSEÑA",
                        8 => "DNI",
                        9 => "APELLIDO PATERNO",
                        10 => "APELLIDO MATERNO",
                        11 => "NOMBRES ",
                        12 => "TELÉFONO 1",
                        13 => "TELÉFONO 2",
                        14 => "OBSERVACIONES"];

        if ( array_diff($row_header, $row_master) )
        {

            // echo "Error: La cabecera no tiene el formato correcto<br>" . PHP_EOL;

            return view('error')
                    ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] )
                    ->with('error_estructura' , 2 );

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
            
            $array[0][$key]['grado'] = trim($row[5]);

            if ( is_numeric($row[5]) )
            {
                $e_grado = 0;
            }
            else
            {
                $e_grado = 1;
            }

            $array[0][$key]['grado_err'] = $e_grado;

            $array[0][$key]['seccion'] = trim($row[6]);

            if ( $row[6] )
            {
                $e_seccion = 0;
            }
            else
            {
                $e_seccion = 1;
            }

            $array[0][$key]['seccion_err'] = $e_seccion;

            $array[0][$key]['area'] = trim($row[7]);

            if ( $row[7] )
            {
                $e_area = 0;
            }
            else
            {
                $e_area = 1;
            }
            $array[0][$key]['area_err'] = $e_area;

            $array[0][$key]['dni'] = trim($row[8]);
            
            if ( $row[8] )
            {
                if ( is_numeric($row[8]) && strlen($row[8])==8 )
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
            
            $array[0][$key]['apellido_p'] = trim($row[9]);
            $array[0][$key]['apellido_p_err'] = ( $row[9] ? 0 : 1);
            
            $array[0][$key]['apellido_m'] = trim($row[10]);

            $array[0][$key]['nombres'] = trim($row[11]);
            $array[0][$key]['nombres_err'] = ( $row[11] ? 0 : 1);

            $array[0][$key]['telefono1'] = trim($row[12]);

            $e_telefono1 = 0;

            if ( !strlen($row[12])==9 ) 
            {

                $e_telefono1 = 1;

            }
            else
            {

                if ( is_numeric( $row[12] ) )
                {

                    if ( substr( $row[12] , 0 , 1 ) == '9' || substr( $row[12] , 0 , 1 ) === '0' )
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
            
            $array[0][$key]['telefono2'] = trim($row[13]);

            $e_telefono2 = 0;

            if ( $row[13] )
            {

                if ( !strlen($row[13])==9 ) 
                {

                    $e_telefono2 = 1;

                }
                else
                {

                    if ( is_numeric( $row[13] ) )
                    {

                        if ( substr( $row[13] , 0 , 1 ) == '9' || substr( $row[13] , 0 , 1 ) === '0' )
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
                        $array[0][$key]['grado_err'] ||
                        $array[0][$key]['seccion_err'] ||
                        $array[0][$key]['area_err'] ||
                        $array[0][$key]['apellido_p_err'] || 
                        $array[0][$key]['nombres_err'] || 
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
                    $array[0][$key][12],
                    $array[0][$key][13],
                    $array[0][$key][14]
                );

        }

        // dd($array[0]);


        // exit();

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
                if ( $array[0][$key]['grado_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Formato de grado incorrecto";
                }
                if ( $array[0][$key]['seccion_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "La Sección es obligatoria ";
                }
                if ( $array[0][$key]['area_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "El área no existe, verifique la ortografía ";
                }
                if ( $array[0][$key]['apellido_p_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Campo apellido incorrecto";
                }
                if ( $array[0][$key]['nombres_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Campo nombres incorrecto";
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

                            '<th scope="row">' . ( $key + 1 ) . '</td>'.
                
                            '<td ' . ( $val['cod_reg_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['cod_reg'] . '</td>'.
                            
                            '<td ' . ( $val['cod_mod8_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['cod_mod8'] . '</td>'.
                            
                            '<td ' . ( $val['grado_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['grado'] . '</td>'.
                            
                            '<td ' . ( $val['seccion_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['seccion'] . '</td>'.
                            
                            '<td ' . ( $val['area_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['area'] . '</td>'.
                            
                            '<td ' . ( $val['dni_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['dni'] . '</td>'.
                            
                            '<td ' . ( $val['apellido_p_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['apellido_p'] . '</td>'.
                            
                            '<td>' . $val['apellido_m'] . '</td>'.
                            
                            '<td ' . ( $val['nombres_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['nombres'] . '</td>'.
                            
                            '<td ' . ( $val['telefono1_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['telefono1'] . '</td>'.
                            
                            '<td ' . ( $val['telefono2_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['telefono2'] . '</td>'.
                            
                            '<td>' . $resumen . '</td>'.

                        '</tr>';

            }

            return view('error')
                        ->with('resumeTable' , $row )
                        ->with('message' , 'Se ha detectado algunos errores en el archivo que está intentando importar. No se puede continuar.')
                        ->with('state_error', 1 )
                        ->with('instancia' , 1 )
                        ->with('error_estructura', 0 )
                        ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] );

        }


        $id_temporal = idate('y', time()).idate('m', time()).idate('d', time()).idate('h', time()).idate('i', time()).idate('s', time()).rand(1, 100);

        foreach ($array[0] as $key => $val) 
        {

            $temp_table = new ImportTableDoc;
            
            $temp_table->id_temp = $id_temporal;
            $temp_table->id_fila = $key + 1;
            $temp_table->cod_reg = $val['cod_reg'];
            $temp_table->cod_mod = $val['cod_mod8'];
            $temp_table->dni = ( $val['dni'] ? $val['dni'] : NULL );
            $temp_table->grado = $val['grado'];
            $temp_table->seccion = $val['seccion'];;
            $temp_table->area = $val['area'];;
            $temp_table->ape_p = $val['apellido_p'];
            $temp_table->ape_m = $val['apellido_m'];
            $temp_table->nombres = $val['nombres'];
            $temp_table->telefono1 = $val['telefono1'];
            $temp_table->telefono2 = $val['telefono2'];
            $temp_table->state = 0;
            $temp_table->info_error = '';
            $temp_table->created_by = $_SESSION['ID_ESPECIALISTA'];
            $temp_table->save();
        
        }

        #CREAR PERSONA DESDE TEMP DIRECTOR
        DB::insert( 'CALL sp_create_doc("' . $id_temporal . '")' );

        DB::update( 'CALL sp_update_id_person_doc("' . $id_temporal . '")' );

        $integrity_table = DB::select('SELECT
                                            id,
                                            id_fila,
                                            cod_mod,
                                            cod_reg,
                                            IF( ISNULL( B.Age_Codigo ) , 1 , 0 ) COD_REG_ERR, /*#No existe codigo registrador*/
                                            IF( cod_mod = C.Ie_CodigoModular , 0 , 1 ) COD_MOD_ERR , /*#Codigo modular no existe*/
                                            IF( cod_reg = C.Age_Codigo , 0 , 1 ) COD_REG_COD_MOD_ERR, /*#El codigo de registrador no está asociado al codigo modular*/
                                            A.grado, 
                                            A.seccion,
                                            IF( ISNULL(validated) , 0 , IF(validated = 0, 1 , 2 )) COD_GRAD_SEC_ERR,/*#0 => el grado seccion, no existe, 1=> el grado existe pero no está validado, 2=> el grado stá validado*/
                                            area,
                                            IF( ISNULL( ARE_DESCRIPCION ) , 1 , 0 ) AREA_ERR, /*EL AREA NO EXISTE*/
                                            IF( ISNULL( E.PER_CODIGO ) , 0 ,1 ) DOC_EX_ERR, /*EL DOCENTE YA HA SIDO REGISTRADO EN ESTA IE*/
                                            dni,
                                            ape_p,
                                            ape_m,
                                            nombres,
                                            telefono1,
                                            telefono2
                                        FROM import_table_docs A 
                                        LEFT JOIN tb_agente B ON (B.Age_Codigo = A.cod_reg)
                                        LEFT JOIN tb_registro C ON (C.Ie_CodigoModular = A.cod_mod AND REG_TIPO = 1)
                                        LEFT JOIN ( SELECT 
                                                       DISTINCT PER_CODIGO,
                                                       Ie_CodigoModular
                                                    FROM tb_doc_contacto 
                                                    WHERE NOT ISNULL(PER_CODIGO)  ) E 
                                                    ON (E.PER_CODIGO = A.id_person AND E.Ie_CodigoModular = cod_mod)
                                        LEFT JOIN tb_areas F ON (A.area = ARE_DESCRIPCION)
                                        LEFT JOIN tb_grado_seccion G ON (A.grado = G.grado AND A.seccion = G.seccion AND A.cod_mod = G.Ie_CodigoModular )
                                        LEFT JOIN (SELECT 
                                                        @i := @i + 1 as ID_GRUPO, 
                                                        NOMBRE_COMPUESTO
                                                    FROM
                                                    (SELECT
                                                        CONCAT( ape_p, ape_m, nombres ) NOMBRE_COMPUESTO, 
                                                        COUNT(id) CONJUNTO
                                                    FROM import_table_dirs 
                                                        WHERE id_temp = "' . $id_temporal. '" 
                                                        GROUP BY concat(ape_p, ape_m,nombres) 
                                                        HAVING CONJUNTO > 1) AS A
                                                    CROSS JOIN ( SELECT @i := 0 ) R ) H ON ( NOMBRE_COMPUESTO = CONCAT(ape_p, ape_m, nombres) )
                                        WHERE id_temp = "' . $id_temporal. '" ORDER BY cod_mod, dni;');


        if ( $integrity_table )
        {

            $row = '';

            $error = false;
            
            $alert = false;

            $error_update = [];

            $dni_grupo = '';

            $color = true;

            foreach ($integrity_table as $key => $val) 
            {

                $error_row = false;

                $alert_row = false;
                
                $resumen = '';

                if ( $val->COD_MOD_ERR )
                {

                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "Código módular no existe";
                    
                    $error_row = true;

                }

                if ( $val->DOC_EX_ERR )
                {
                
                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "Este docente ya ha sido registrado en esta IE";
                
                    $error_row = true;
                
                }

                if ( $val->COD_REG_ERR )
                {
                    
                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "Código de monitor no existe";
                 
                    $error_row = true;
                
                }

                if ( $val->COD_REG_COD_MOD_ERR )
                {
                 
                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "El código de registrador no está asociado al código modular";
                 
                    $error_row = true;
                
                }

                if ( $val->COD_GRAD_SEC_ERR === 0 )
                {

                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "El grado y sección no existe, no se puede registrar";
                
                    $error_row = true;
                
                }

                if ( $val->COD_GRAD_SEC_ERR === 1 )
                {

                    $resumen .= ( $resumen ? '<br>-Alerta: ' : 'Alerta: ') . "El grado y sección no está validada en SIREG, no se puede registrar";
                
                    $alert_row = true;
                
                }

                if ( $val->AREA_ERR )
                {
                    
                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "El área no existe, verifique la ortografía ";
                
                    $error_row = true;
                
                }

                if ( $resumen )
                {

                    array_push($error_update, 
                                            [ 
                                                'id' => $val->id , 
                                                'info_error' => $resumen 
                                            ]);

                }

                if ( $dni_grupo == $val->dni )
                {


                }
                else
                {
                    
                    $dni_grupo = $val->dni;

                    $color = !( $color );

                }

                if ( $error_row === true  )
                {
                
                    $error = true; 
                
                }

                if ( $alert_row === true  )
                {
                
                    $alert = true; 
                
                }


                // $row .= '<tr ' . ( $resumen ? 'class="table-danger"' :  ( $color ? 'class="fila-o"' : 'class="fila-d"' ) ) . '>'.
                $row .= '<tr ' . ( $error_row ? 'class="table-danger"' : ( $alert_row ? 'class="table-warning"' : '' ) ) . '>'.
                            '<th scope="row">' . $val->id_fila . '</td>'.
                            '<td ' . ( $val->COD_REG_ERR || $val->COD_REG_COD_MOD_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->cod_reg . '</td>'.
                            '<td ' . ( $val->COD_MOD_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->cod_mod . '</td>'.

                            '<td ' . ( $val->COD_GRAD_SEC_ERR === 0 ? 'class="bg-danger"' : ( $val->COD_GRAD_SEC_ERR === 1 ? 'class="bg-alert"' : '' ) ) . '>' . $val->grado . '</td>'.
                            
                            '<td ' . ( $val->COD_GRAD_SEC_ERR === 0 ? 'class="bg-danger"' : ( $val->COD_GRAD_SEC_ERR === 1 ? 'class="bg-alert"' : '' ) ) . '>' . $val->seccion . '</td>'.

                            '<td ' . ( $val->AREA_ERR || $val->AREA_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->area . '</td>'.
                            '<td ' . ( $val->DOC_EX_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->dni . '</td>'.
                            '<td>' . $val->ape_p . '</td>'.
                            '<td>' . $val->ape_m . '</td>'.
                            '<td>' . $val->nombres . '</td>'.
                            '<td>' . $val->telefono1 . '</td>'.
                            '<td>' . $val->telefono2 . '</td>'.
                            '<td>' . $resumen . '</td>'.
                        '</tr>';

            }

            $message = '';

            if ( $error )
            {

                $message = ':( Se ha detectado errores de integridad. No se puede registrar.';


                foreach ($error_update as $key => $val) 
                {

                    DB::table('import_table_docs')
                            ->where("id", $val['id'])
                            ->update(["info_error" => $val['info_error'] ]);

                    // $temp_table = new ImportTable;

                    // $temp_table->exists = true;

                    // $temp_table->id = $val['id'];

                    // $temp_table->info_error = $val['info_error'];

                    // $temp_table->save();
                
                }

                DB::update('UPDATE import_table_docs SET state = 2 WHERE id_temp = "' . $id_temporal. '";');
                
                $e_state = 1;

            }
            else
            {

                if ( $alert )
                {

                    $message = 'Advertencia: Se ha definido alertas en cada fila sombreada. Revise cada mensaje en el cuadro, si es correcto presione el botón "Importar" o "Cancelar" para abortar la importación.';
                    
                    $e_state = 2;

                }
                else
                {

                    $message = 'Se ve bien! : Se va a registrar la siguiente información. Presione el boton "Importar" para proceder con el registro.';

                    $e_state = 0;

                }

                \Storage::disk('local')->put( $id_temporal . '-' . 'doc-' . $file->getClientOriginalName(),  \File::get($file));

            }

            return view('error')
                    ->with('resumeTable' , $row )
                    ->with('message' , $message )
                    ->with('state_error', (int) $e_state )
                    ->with('error_estructura', 0 )
                    ->with('instancia' , 1 )
                    ->with('id_temporal', $id_temporal)
                    ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] );
                    
        }

    }

    public function importTableDocente( $id_temporal , $id_especialista )
    {

        $resp = DB::insert("INSERT INTO tb_doc_contacto ( 
                                                            Ie_CodigoModular, 
                                                            PER_CODIGO,
                                                            /*CDOC_TIPO_DOC, 
                                                            CDOC_NUMERO_DOC, 
                                                            CDOC_NOMBRE, 
                                                            CDOC_APELLIDO_P, 
                                                            CDOC_APELLIDO_M, 
                                                            CDOC_TELEFONO, 
                                                            CDOC_TELEFONO2,*/
                                                            id_seed
                                                        ) 
                                                        (SELECT 
                                                            cod_mod , 
                                                            id_person,
                                                            /*1 , 
                                                            dni, 
                                                            nombres, 
                                                            ape_p, 
                                                            ape_m, 
                                                            telefono1, 
                                                            telefono2,*/
                                                            id 
                                                        FROM  
                                                            import_table_docs 
                                                        WHERE 
                                                            id_temp ='$id_temporal' AND state = 0 
                                                        GROUP BY dni)");
        
            $resp2 = DB::insert("INSERT INTO 
                                    tb_doc_directorio ( 
                                            CDOC_CODIGO, 
                                            RDOC_HISTORIA, 
                                            Ie_CodigoModular, 
                                            DGS_GRADO, 
                                            DGS_SECCION,
                                            DGS_AREAS, 
                                            id_seed,
                                            id_seccion
                                        )
                                        SELECT 
                                            CDOC_CODIGO, 
                                            0, 
                                            A.cod_mod,
                                            A.grado, 
                                            A.seccion,  
                                            CONCAT('[' , GROUP_CONCAT(ARE_CODIGO SEPARATOR ',') , ']') AREA, 
                                            id,
                                            C.id_seccion
                                        FROM 
                                        import_table_docs A 
                                        JOIN 
                                        tb_doc_contacto B ON (A.id_person = B.PER_CODIGO AND A.cod_mod = B.Ie_CodigoModular AND id_temp ='$id_temporal' AND state = 0 ) 
                                        JOIN
                                        tb_grado_seccion C 
                                        ON ( A.cod_mod = C.Ie_CodigoModular AND A.grado = C.grado AND A.seccion = C.seccion )
                                        LEFT JOIN 
                                        tb_areas D ON (A.area = D.ARE_DESCRIPCION ) 
                                        GROUP BY dni, grado, seccion");

        DB::update('UPDATE import_table_docs SET state = 1 WHERE id_temp = "' . $id_temporal. '";');

        if ( $resp && $resp2 )
        {
        
            $message = 'Se ha importado los datos correctamente.';

        }
        else
        {
            
            $message = 'No se ha podido importar los datos. Error: Operación 1 devolvió: ' . $resp . ', Operación 2 devolvió: ' . $resp2  ;

        }

        return view('seeds')
                ->with('message' , $message )
                ->with('state', $resp )
                ->with('instancia' , 1 )
                ->with('error_estructura', 0 )
                ->with('id_especialista' , $id_especialista );

    }

    public function importExcelPPFF(Request $request)
    {

        session_start();
        
        ini_set('memory_limit', '512M');
        
        $file = $request->file('file');
        
        $array = Excel::toArray(new UsersImport, $file );

        unset($array[0][0]);

        unset($array[0][1]);

        $row_header = $array[0][2];

        $error = false;

        $alert = false;

        if ( count($array[0][2]) != 18 )
        {

            // echo "Error: El archivo no tiene la estructura correcta<br>" . PHP_EOL;

            return view('error')
                    ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] )
                    ->with('error_estructura' , 1 );

        }
     
        $row_master = [   0 => "COD_MONITOR",
                          1 => "COD_MOD",
                          2 => "ANEXO",
                          3 => "IE",
                          4 => "NIVEL",
                          5 => "TIPO DOCUMENTO",
                          6 => "DNI",
                          7 => "A.PATERNO",
                          8 => "A.MATERNO",
                          9 => "NOMBRES ",
                          10 => "GRADO",
                          11 => "SECCIÓN",
                          12 => "A.PATERNO_APODERADO",
                          13 => "A.MATERNO_APODERADO",
                          14 => "NOMBRE_APODERADO",
                          15 => "TELÉFONO 1",
                          16 => "TELÉFONO 2",
                          17 => "OBSERVACIÓN"];

        if ( array_diff($row_header, $row_master) )
        {

            // echo "Error: La cabecera no tiene el formato correcto<br>" . PHP_EOL;

            return view('error')
                    ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] )
                    ->with('error_estructura' , 2 );

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
            $tipo_doc = strtoupper(trim($row[5]));

            $codigo_tipo_doc = null;
            
            $e_tipo_doc = 0;

            $e_dni = 0;

            if ( trim($row[6]) )
            {

                switch ($tipo_doc) {
                    
                    case '1':
                    case 'DNI':

                        if ( strlen(trim($row[6])) == 8 && is_numeric( trim($row[6]) ) )
                        {

                            $codigo_tipo_doc = 1;

                        }
                        else
                        {
                            
                            $e_dni = 1;

                        }

                    break;
                    
                    case '2':
                    case 'CE':
                                        
                        if ( strlen( trim($row[6]) ) < 13 )
                        {

                            $codigo_tipo_doc = 2;

                        }
                        else
                        {
                            
                            $e_dni = 1;

                        }

                    break;

                    case '3':
                    case 'PASAPORTE':
                                                            
                        if ( strlen( trim($row[6]) ) < 14 )
                        {

                            $codigo_tipo_doc = 3;

                        }
                        else
                        {
                            
                            $e_dni = 1;

                        }

                    break;

                    case '5':
                    case 'CUE':
                                                            
                        if ( strlen( trim($row[6]) ) == 14 && is_numeric( trim($row[6]) ) )
                        {

                            $codigo_tipo_doc = 4;

                        }
                        else
                        {
                            
                            $e_dni = 1;

                        }

                    default:

                        $e_tipo_doc = 1;

                        $e_dni = 1;

                    break;
                }

            }
            else
            {

                $e_tipo_doc = 1;

                $e_dni = 1;

            }

            $array[0][$key]['tipo_doc'] = $tipo_doc;

            $array[0][$key]['cod_tipo_doc'] = $codigo_tipo_doc;

            $array[0][$key]['tipo_doc_err'] = $e_tipo_doc;

            $array[0][$key]['dni'] = trim($row[6]);

            $array[0][$key]['dni_err'] = $e_dni; 
            
            $array[0][$key]['apellido_p'] = trim($row[7]);

            $array[0][$key]['apellido_p_err'] = ( $row[7] ? 0 : 1);
            
            $array[0][$key]['apellido_m'] = trim($row[8]);

            $array[0][$key]['nombres'] = trim($row[9]);

            $array[0][$key]['nombres_err'] = ( $row[9] ? 0 : 1);

            $array[0][$key]['grado'] = trim($row[10]);

            if ( is_numeric($row[10]) )
            {
                $e_grado = 0;
            }
            else
            {
                $e_grado = 1;
            }

            $array[0][$key]['grado_err'] = $e_grado;

            $array[0][$key]['seccion'] = trim($row[11]);

            if ( $row[11] )
            {
                $e_seccion = 0;
            }
            else
            {
                $e_seccion = 1;
            }

            $array[0][$key]['seccion_err'] = $e_seccion;

            $array[0][$key]['apellido_p_apo'] = trim($row[12]);

            $array[0][$key]['apellido_p_apo_err'] = ( $row[12] ? 0 : 1);
            
            $array[0][$key]['apellido_m_apo'] = trim($row[13]);

            $array[0][$key]['nombres_apo'] = trim($row[14]);

            $array[0][$key]['nombres_apo_err'] = ( $row[14] ? 0 : 1);

            $array[0][$key]['telefono1'] = trim($row[15]);

            $e_telefono1 = 0;

            if ( !strlen($row[15])==9 ) 
            {

                $e_telefono1 = 1;

            }
            else
            {

                if ( is_numeric( $row[15] ) )
                {

                    if ( substr( $row[15] , 0 , 1 ) == '9' || substr( $row[15] , 0 , 1 ) === '0' )
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
            
            $array[0][$key]['telefono2'] = trim($row[16]);

            $e_telefono2 = 0;

            if ( $row[16] )
            {

                if ( !strlen($row[16])==9 ) 
                {

                    $e_telefono2 = 1;

                }
                else
                {

                    if ( is_numeric( $row[16] ) )
                    {

                        if ( substr( $row[16] , 0 , 1 ) == '9' || substr( $row[16] , 0 , 1 ) === '0' )
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
                        $array[0][$key]['tipo_doc_err'] || 
                        $array[0][$key]['dni_err'] || 
                        $array[0][$key]['apellido_p_err'] || 
                        $array[0][$key]['nombres_err'] || 
                        $array[0][$key]['grado_err'] ||
                        $array[0][$key]['seccion_err'] ||
                        $array[0][$key]['apellido_p_apo_err'] ||
                        $array[0][$key]['nombres_apo_err'] ||
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
                    $array[0][$key][12],
                    $array[0][$key][13],
                    $array[0][$key][14],
                    $array[0][$key][15],
                    $array[0][$key][16],
                    $array[0][$key][17]
                );

        }

        // dd($array[0]);exit();

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
                if ( $array[0][$key]['tipo_doc_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "tipo de documento invalido ";
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
                if ( $array[0][$key]['grado_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Formato de grado incorrecto";
                }
                if ( $array[0][$key]['seccion_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "La Sección es obligatoria ";
                }
                if ( $array[0][$key]['apellido_p_apo_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Campo apellido paterno apoderado incorrecto";
                }
                if ( $array[0][$key]['nombres_apo_err'] )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Campo nombres apoderado incorrecto";
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
                            '<th scope="row">' . ( $key + 1 ) . '</td>'.
                            '<td ' . ( $val['cod_reg_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['cod_reg'] . '</td>'.
                            '<td ' . ( $val['cod_mod8_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['cod_mod8'] . '</td>'.
                            '<td ' . ( $val['tipo_doc_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['tipo_doc'] . '</td>'.
                            '<td ' . ( $val['dni_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['dni'] . '</td>'.
                            '<td ' . ( $val['apellido_p_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['apellido_p'] . '</td>'.
                            '<td>' . $val['apellido_m'] . '</td>'.
                            '<td ' . ( $val['nombres_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['nombres'] . '</td>'.
                            '<td ' . ( $val['grado_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['grado'] . '</td>'.
                            '<td ' . ( $val['seccion_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['seccion'] . '</td>'.
                            '<td ' . ( $val['apellido_p_apo_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['apellido_p_apo'] . '</td>'.
                            '<td>' . $val['apellido_m_apo'] . '</td>'.
                            '<td ' . ( $val['nombres_apo_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['nombres_apo'] . '</td>'.
                            '<td ' . ( $val['telefono1_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['telefono1'] . '</td>'.
                            '<td ' . ( $val['telefono2_err'] ? 'class="bg-danger"' : '' ) . '>' . $val['telefono2'] . '</td>'.
                            '<td>' . $resumen . '</td>'.

                        '</tr>';

            }

            return view('error')
                        ->with('resumeTable' , $row )
                        ->with('message' , 'Se ha detectado algunos errores en el archivo que está intentando importar. No se puede continuar.')
                        ->with('error_estructura', 0 )
                        ->with('state_error', 1 )
                        ->with('instancia' , 2 )
                        ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] );

        }


        $id_temporal = idate('y', time()).idate('m', time()).idate('d', time()).idate('h', time()).idate('i', time()).idate('s', time()).rand(1, 100);

        foreach ($array[0] as $key => $val) 
        {

            $temp_table = new ImportTablePPFF;
            
            $temp_table->id_temp = $id_temporal;
            $temp_table->id_fila = $key + 1;
            $temp_table->cod_reg = $val['cod_reg'];
            $temp_table->cod_mod = $val['cod_mod8'];
            $temp_table->tipo_doc = $val['cod_tipo_doc'];
            $temp_table->dni = ( $val['dni'] ? $val['dni'] : NULL );
            $temp_table->grado = $val['grado'];
            $temp_table->seccion = $val['seccion'];
            $temp_table->ape_p = $val['apellido_p'];
            $temp_table->ape_m = $val['apellido_m'];
            $temp_table->nombres = $val['nombres'];
            $temp_table->ape_p_apo = $val['apellido_p_apo'];
            $temp_table->ape_m_apo = $val['apellido_m_apo'];
            $temp_table->nombres_apo = $val['nombres_apo'];
            $temp_table->telefono1 = $val['telefono1'];
            $temp_table->telefono2 = $val['telefono2'];
            $temp_table->state = 0;
            $temp_table->info_error = '';
            $temp_table->created_by = $_SESSION['ID_ESPECIALISTA'];
            $temp_table->save();
        
        }

        #CREAR PERSONA DESDE TEMP DIRECTOR
        DB::insert( 'CALL sp_create_ppff("' . $id_temporal . '")' );

        DB::update( 'CALL sp_update_id_person_ppff("' . $id_temporal . '")' );

        $integrity_table = DB::select('SELECT
                                            id,
                                            id_fila,
                                            cod_mod,
                                            cod_reg,
                                            IF( ISNULL( B.Age_Codigo ) , 1 , 0 ) COD_REG_ERR, /*#No existe codigo registrador*/
                                            IF( cod_mod = C.Ie_CodigoModular , 0 , 1 ) COD_MOD_ERR , /*#Codigo modular no existe*/
                                            IF( cod_reg = C.Age_Codigo , 0 , 1 ) COD_REG_COD_MOD_ERR, /*#El codigo de registrador no está asociado al codigo modular*/
                                            A.grado, 
                                            A.seccion,
                                            IF( ISNULL(validated) , 0 , IF(validated = 0, 1 , 2 )) COD_GRAD_SEC_ERR,/*#0 => el grado seccion, no existe, 1=> el grado existe pero no está validado, 2=> el grado stá validado*/
                                            tipo_doc,
                                            dni,
                                            IF( isnull(EST_NUMERO_DOC ) , 0 , 1 ) DNI_EX_EST_ERR, /*#El DNI del estudiante ya ha sido registrado*/
                                            ape_p,
                                            ape_m,
                                            nombres,
                                            ape_p_apo,
                                            ape_m_apo,
                                            nombres_apo,
                                            telefono1,
                                            telefono2, 
                                            F.id_seccion,
                                            validated
                                        
                                        FROM import_table_ppffs A 
                                        
                                        LEFT JOIN tb_agente B ON (B.Age_Codigo = A.cod_reg)
                                        
                                        LEFT JOIN tb_registro C ON (C.Ie_CodigoModular = A.cod_mod AND REG_TIPO = 1)
                                        
                                        LEFT JOIN tb_ppff_estudiante D ON (A.dni = D.EST_NUMERO_DOC)
                                        
                                        LEFT JOIN tb_grado_seccion F ON (A.cod_mod = F.Ie_CodigoModular AND A.grado = F.grado AND A.seccion = F.seccion )

                                        WHERE id_temp = "' . $id_temporal. '";');



        if ( $integrity_table )
        {

            $row = '';

            $error_update = [];

            $dni_grupo = '';

            $color = true;

            foreach ($integrity_table as $key => $val) 
            {
                    
                $error_row = false;

                $alert_row = false;

                $resumen = '';

                if ( $val->COD_MOD_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Código módular no existe";
                    
                    $error_row = true;

                }
                if ( $val->COD_REG_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "Código de monitor no existe";
                 
                    $error_row = true;
                
                }
                if ( $val->COD_REG_COD_MOD_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "El código de registrador no está asociado al código modular";
                 
                    $error_row = true;
                
                }
                if ( $val->DNI_EX_EST_ERR )
                {
                    $resumen .= ( $resumen ? ', ' : 'Error(es): ') . "El documento de identidad del estudiante ya ha sido registrado";
                
                    $error_row = true;
                
                }

                if ( $val->COD_GRAD_SEC_ERR === 0 )
                {

                    $resumen .= ( $resumen ? '<br>-' : 'Error(es): ') . "El grado y sección no existe, no se puede registrar";
                
                    $error_row = true;
                
                }

                if ( $val->COD_GRAD_SEC_ERR === 1 )
                {

                    $resumen .= ( $resumen ? '<br>-Alerta: ' : 'Alerta: ') . "El grado y sección no está validada en SIREG, no se puede registrar";
                
                    $alert_row = true;
                
                }

                if ( $resumen )
                {

                    array_push($error_update, 
                                            [ 
                                                'id' => $val->id , 
                                                'info_error' => $resumen 
                                            ]);

                }

                if ( $dni_grupo == $val->dni )
                {


                }
                else
                {
                    
                    $dni_grupo = $val->dni;

                    $color = !( $color );

                }

                if ( $error_row === true  )
                {
                    $error = true; 
                }

                if ( $alert_row === true  )
                {
                    $alert = true; 
                }

                $row .= '<tr ' . ( $error_row ? 'class="table-danger"' : ( $alert_row ? 'class="table-warning"' : '' ) ) . '>'.
                // $row .= '<tr ' . ( $resumen ? 'class="table-danger"' :  ( $color ? 'class="fila-o"' : 'class="fila-d"' ) ) . '>'.
                            '<th scope="row">' . $val->id_fila . '</td>'.
                            '<td ' . ( $val->COD_REG_ERR || $val->COD_REG_COD_MOD_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->cod_reg . '</td>'.
                            '<td ' . ( $val->COD_MOD_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->cod_mod . '</td>'.
                            '<td ' . ( $val->DNI_EX_EST_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->tipo_doc . '</td>'.
                            '<td ' . ( $val->DNI_EX_EST_ERR ? 'class="bg-danger"' : '' ) . '>' . $val->dni . '</td>'.
                            '<td>' . $val->ape_p . '</td>'.
                            '<td>' . $val->ape_m . '</td>'.
                            '<td>' . $val->nombres . '</td>'.
                            '<td ' . ( $val->COD_GRAD_SEC_ERR === 0 ? 'class="bg-danger"' : ( $val->COD_GRAD_SEC_ERR === 1 ? 'class="bg-alert"' : '' ) ) . '>' . $val->grado . '</td>'.
                            '<td ' . ( $val->COD_GRAD_SEC_ERR === 0 ? 'class="bg-danger"' : ( $val->COD_GRAD_SEC_ERR === 1 ? 'class="bg-alert"' : '' ) ) . '>' . $val->seccion . '</td>'.
                            '<td>' . $val->ape_p_apo . '</td>'.
                            '<td>' . $val->ape_m_apo . '</td>'.
                            '<td>' . $val->nombres_apo . '</td>'.
                            '<td>' . $val->telefono1 . '</td>'.
                            '<td>' . $val->telefono2 . '</td>'.
                            '<td>' . $resumen . '</td>'.
                        '</tr>';

            }

            $message = '';

            if ( $error )
            {

                $message = ':( Se ha detectado errores de integridad. No se puede registrar.';

                foreach ($error_update as $key => $val) 
                {

                    DB::table('import_table_ppffs')->where("id", $val['id'])->update(["info_error" => $val['info_error'] ]);
                
                }

                DB::update('UPDATE import_table_ppffs SET state = 2 WHERE id_temp = "' . $id_temporal. '";');

                $e_state = 1;

            }
            else
            {

                if ( $alert )
                {

                    $message = 'Advertencia: Se ha definido alertas en cada fila sombreada. Revise cada mensaje en el cuadro, si es correcto presione el botón "Importar" o "Cancelar" para abortar la importación.';
                    
                    $e_state = 2;

                }
                else
                {

                    $message = 'Se ve bien! : Se va a registrar la siguiente información. Presione el boton "Importar" para proceder con el registro.';

                    $e_state = 0;

                }

                \Storage::disk('local')->put( $id_temporal . '-' . 'ppff-' . $file->getClientOriginalName(),  \File::get($file));

            }

            return view('error')
                    ->with('resumeTable' , $row )
                    ->with('message' , $message )
                    ->with('state_error', (int)$e_state )
                    ->with('error_estructura', 0 )
                    ->with('instancia' , 2 )
                    ->with('id_temporal', $id_temporal)
                    ->with('id_especialista' , $_SESSION['ID_ESPECIALISTA'] );
                    
        }

    }

    public function importTablePPFF( $id_temporal , $id_especialista )
    {



                $resp = DB::insert("INSERT INTO tb_ppff_contacto 
                            ( 
                                Ie_CodigoModular, 
                                PER_CODIGO,
                                id_seed
                            )
                            (SELECT 
                                cod_mod , 
                                id_person,
                                id 
                            FROM import_table_ppffs 
                            WHERE id_temp ='$id_temporal' AND state = 0)");
        
        $resp2 = DB::insert("INSERT INTO tb_ppff_estudiante 
                                ( 
                                    CPF_CODIGO, 
                                    Ie_CodigoModular, 
                                    EST_NOMBRES, 
                                    EST_APELLIDO_P, 
                                    EST_APELLIDO_M, 
                                    EST_TIPO_DOC, 
                                    EST_NUMERO_DOC, 
                                    id_seed, 
                                    id_seccion
                                )
                            SELECT 
                                    CPF_CODIGO, 
                                    cod_mod, 
                                    nombres, 
                                    ape_p, 
                                    ape_m, 
                                    tipo_doc, 
                                    dni, 
                                    id,
                                    C.id_seccion
                            FROM import_table_ppffs A 
                            JOIN 
                                tb_ppff_contacto B 
                                ON (A.id = id_seed AND id_temp ='$id_temporal' AND state = 0 )
                            JOIN
                                tb_grado_seccion C 
                                ON ( A.cod_mod = C.Ie_CodigoModular AND A.grado = C.grado AND A.seccion = C.seccion )");

                                    // A.grado, 
                                    // A.seccion, 
                                    // EST_GRADO, 
                                    // EST_SECCION, 

        DB::update('UPDATE import_table_ppffs SET state = 1 WHERE id_temp = "' . $id_temporal. '";');

        if ( $resp && $resp2 )
        {
        
            $message = 'Se ha importado los datos correctamente.';

        }
        else
        {
            
            $message = 'No se ha podido importar los datos. Error: Operación 1 devolvió: ' . $resp . ', Operación 2 devolvió: ' . $resp2  ;

        }

        return view('seeds')
                ->with('message' , $message )
                ->with('state', $resp )
                ->with('instancia' , 2 )
                ->with('id_especialista' , $id_especialista );

    }

}
