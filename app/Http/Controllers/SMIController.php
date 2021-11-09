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

        $id_evaluacion = $data['id_evaluacion'];
        
        $usuario = $data['numero_documento'];
        
        $estado_actual = (int) $data['id_estado'];

        $fecha_estado = date('Y-m-d H:i:s');
        
        $fecha_estado_seel = $data['fecha_hora'];

        $avance = $data['preguntas_respondidas'];

        if ( ( $estado_actual < 6 && $usuario ) == false )
        {
        
            return [ 'resp' => 0 , 'msg' => 'Error en los parametros' ];

        }

        $data_tipo = Monitoreo::getIdCuestionario($id_evaluacion);

        $id_usuario = Monitoreo::getIdUsuario($usuario);

        if ( is_null( $data_tipo ) )
        {

            return [ 'resp' => 0 , 'msg' => 'No existe el id de evaluación' ]; 

        }
        
        if ( is_null( $id_usuario ) )
        {

            return [ 'resp' => 0 , 'msg' => 'No existe el usuario' ]; 

        }

        $id_tipo = $data_tipo->idcuestionario;

        $id_usuario = $id_usuario->idusuario;

        $resp = Monitoreo::where('idcuestionario', '=' , $id_tipo )
                        ->where('idusuario', '=' , $id_usuario)
                        ->get();
        
        if ( count( $resp ) )
        {
            
            $values = array();

            $estado_registrado = $resp->first()->estado;
            
            // $fechas_seel = (array) json_decode( $resp->first()->info_date_seel );

            if ( $estado_actual === 1 )
            {

                $estado = 'Inicio de sesión';

                $values['login_contador'] =  (int) $resp->first()->login_contador + 1 ;

                $values['seel_login_ultimo'] = $fecha_estado;

                // $fechas_seel['seel_login_ultimo'] = $fecha_estado_seel;
                
            }
            // else
            // {
            //      $estado = 'Inicio de sesión';

            //     $values['login_contador'] = 1 ;

            //     $values['login'] = $fecha_estado;

            //     // $fechas_seel['login'] = $fecha_estado_seel;

            // }

            if ( $estado_actual == 5 )
            {

                $estado = 'Fin de sesión';
                
                $values['logout'] = $fecha_estado;

                // $fechas_seel['logout'] = $fecha_estado_seel;

            }

            if ( $estado_registrado < $estado_actual && $estado_actual < 5 )
            {
                    
                $values['estado'] = $estado_actual;

                $values['login'] = $resp->first()->seel_login_ultimo;

                switch ( $estado_actual ) 
                {
        
                    case 1: 
                
                        $estado = 'Inicio de sesión';
                        
                        $values['login'] = $fecha_estado;
                                    
                        $values['login_contador'] = 1 ;

                        // $fechas_seel['login'] = $fecha_estado_seel;

                    break;
        
                    case 2: 
        
                        $estado = 'Incompleto';

                        $values['inicio'] = $fecha_estado;

                        // $fechas_seel['inicio'] = $fecha_estado_seel;
        
                    break;
        
                    case 3: 
        
                        $estado = 'Finalizado 1';

                        $values['fin1'] = $fecha_estado;

                        $values['fecha_resolucion'] = date("Y-m-d", strtotime($fecha_estado));
                        
                        $values['avance'] = $avance;
                        
                        // $fechas_seel['fin1'] = $fecha_estado_seel;

                        if ( $id_tipo == 0 )
                        {

                            $values['logout'] = $fecha_estado; 
    
                            // $fechas_seel['logout'] = $fecha_estado_seel;

                        }
        
                    break;
                
                    case 4: 
            
                        if ( $id_tipo > 0 )
                        {
                            $estado = 'Finalizado 2';

                            $values['fin2'] = $fecha_estado;

                            // $fechas_seel['fin2'] = $fecha_estado_seel;

                            $values['logout'] = $fecha_estado; 

                            // $fechas_seel['logout'] = $fecha_estado_seel;

                        }
        
                    break;

                }
                
            }

            // $values['info_date_seel'] = json_encode($fechas_seel);
            
        }
        else
        {

            return [ 'resp' => 0 , 'msg' => 'No existe registrado en usuario cuestionarios' ];
            
            // $values = [
            //     'idevaluacion'          => $id_evaluacion,
            //    'login'          => $fecha_estado,
            //    'seel_login_ultimo'   => $fecha_estado,
            //    'estado'         => 1,
            //    'login_contador' => 1,
            //     'estado_temporal'=> $estado_actual,
            //     'info_date_seel' => '{"login":"' . $fecha_estado_seel . '", "seel_login_ultimo":"' . $fecha_estado_seel . '"}'
            // ];

            // $estado = 'Inicio de sesión';

        }

        if ( $values )
        {

            Monitoreo::updateOrInsert(
                
                [
                    'idcuestionario' => $id_tipo, 
                    'idusuario' => $id_usuario 
                ],
                $values
            );
            
            return [ 'resp' => 1 , 'msg' => $estado , 'fecha_hora' => date('Y-m-d H:m:s') ];
            
        }
        else{

            return [ 'resp' => 0 , 'msg' => 'Nada que actualizar' ];

        }

    }

}
