<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Monitoreo;

class SMIController extends Controller
{
       
	public function updateRange(Request $request )
    {

    	$data = $request->all();

    	$dni = $data[0]["dni"];

    	$instancia = $data[0]["instancia"];
    	
    	$dia = $data[0]["dia"];

    	$rango = $data[0]["rango"];

        $persona = DB::select("SELECT 
                                    * 
                                FROM cuestionario.37978_persona A 
                                JOIN cuestionario.37978_usuario B ON (A.id = B.idpersona) 
                                WHERE dni = '$dni' AND idrol = 3")[0];

        DB::insert("INSERT INTO monitoreo_v2.37978_persona SET 
                    
                    id= " . $persona->id . " ,
                    
                    idrol= 3,
                    
                    estado= 1,

                    dni = '" . $dni . "',
                    
                    Est_EstadoSeleccion= 1,
                    
                    idinstancia=" . $instancia . ",
                    
                    rango_horario = '" . $rango . "'
                    
                    ON DUPLICATE KEY UPDATE
                    
                    rango_horario = '" . $rango . "';");

    	return [ 'resp' => 1 ];

    }

    public function setState(Request $request )
    {

        $estado = "";

        $data = $request->all();

        $id_tipo = $data['id_tipo'];
        
        $tipo_doc = $data['tipo_doc'];

        $numero_doc = $data['numero_doc'];
        
        $estado_actual = (int) $data['id_estado'];

        $fecha_estado = $data['fecha_hora'];

        if ( ( $estado_actual < 6 && $tipo_doc < 5 && $fecha_estado && $numero_doc ) == false )
        {
        
            return [ 'resp' => 0 , 'msg' => 'Error en los parametros' ];

        }

        $resp = Monitoreo::where('tipo', '=' , $id_tipo )
                        ->where('documento_tipo', '=' , $tipo_doc )
                        ->where('documento_numero', '=' , $numero_doc )
                        ->get();
        
        if ( count( $resp ) )
        {
            
            $values = array();

            $estado_registrado = $resp->first()->estado;

            if ( $estado_actual === 1 )
            {

                $estado = 'Inicio de sesión';

                $values[ 'login_contador'] = (int) $resp->first()->login_contador + 1 ;

            }

            if ( $estado_actual == 5 )
            {

                $estado = 'Fin de sesión';
                
                $values['logout'] = $fecha_estado;

            }

            if ( $estado_registrado < $estado_actual && $estado_actual < 5 )
            {
                
                $values['estado'] = $estado_actual;

                $values['login_ultimo'] = $fecha_estado;

                switch ( $estado_actual ) 
                {
        
                    case 1: 
                
                        $estado = 'Ingresó';
                        
                        $values['login'] = $fecha_estado;

                    break;
        
                    case 2: 
        
                        $estado = 'Incompleto';

                        $values['inicio'] = $fecha_estado;
        
                    break;
        
                    case 3: 
        
                        $estado = 'Finalizado 1';

                        $values['fin1'] = $fecha_estado;
        
                    break;
                
                    case 4: 
        
                        $estado = 'Finalizado 2';

                        $values['fin2'] = $fecha_estado;
        
                    break;

                }
                
            }
            
        }
        else
        {
            
            $values = [

                'login' => $fecha_estado,
                'estado' => $estado_actual,
                'login_contador' => 1
            ];

            $estado = 'Inicio de sesión';

        }

        if ( $values )
        {

            Monitoreo::updateOrInsert(
                
                [
                    'tipo' => $id_tipo, 
                    'documento_tipo' => $tipo_doc,
                    'documento_numero' => $numero_doc 
                ],
                $values
            );
            
            return [ 'resp' => 1 , 'msg' => $estado , 'fecha_hora' => date('Y-m-d H:m:s') ];
            
        }
        else{

            return [ 'resp' => 0 , 'msg' => 'Nada que actualizar' ];

        }
            // $resp = Monitoreo::updateOrInsert(
                
                //                         [
                    //                             'tipo' => $id_tipo, 
                    //                             'documento_tipo' => $tipo_doc,
        //                             'documento_numero' => $usuario 
        //                         ],
                                
        //                         [
        //                             'estado' => $id_estado,
        //                             'fecha_simulacro' => '',
        //                             'fecha_hora_login' => $fecha_hora_autenticacion,
        //                             'fecha_hora_inicio' => $hora_inicio_evaluacion,
        //                             'fecha_hora_logout' => $hora_salida_seel
        //                         ]
        //                 );

        // var_dump($resp);

                        // ->update(   array(

                        //                 'estado_evaluacion' => $estado, 

                        //                 'origen_estado' => 1 

                        //                 ) 
                        //         );

    }

}
