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

        $id_evaluacion = $data['id_tipo'];
        
        $usuario = $data['numero_documento'];
        
        $estado_actual = (int) $data['id_estado'];

        $fecha_estado = date('Y-m-d H:i:s');
        
        $fecha_estado_seel = $data['fecha_hora'];

        if ( ( $estado_actual < 6 && $usuario ) == false )
        {
        
            return [ 'resp' => 0 , 'msg' => 'Error en los parametros' ];

        }

        $data_tipo = Monitoreo::getTipo($id_evaluacion);

        if ( is_null( $data_tipo ) )
        {

            return [ 'resp' => 0 , 'msg' => 'No existe el id/tipo de evaluación' ]; 

        }
        
        $id_tipo = $data_tipo->tipo;

        $pre = '';

        if ( $data_tipo->recuperacion )
        {

            $pre = 'rec_';

        }

        $resp = Monitoreo::where('tipo', '=' , $id_tipo )
                        ->where('usuario', '=' , $usuario )
                        ->get();
        
        if ( count( $resp ) )
        {
            
            $values = array();

            $estado_registrado = $resp->first()->estado;
            
            $fechas_seel = (array) json_decode( $resp->first()->info_date_seel );

            if ( $estado_actual === 1 )
            {

                $estado = 'Inicio de sesión';

                $values[ $pre . 'login_contador'] = ( $pre ? (int) $resp->first()->rec_login_contador + 1 : (int) $resp->first()->login_contador + 1 ) ;

                $values[ $pre . 'login_ultimo'] = $fecha_estado;

                $fechas_seel['login_ultimo'] = $fecha_estado_seel;
                
            }

            if ( $estado_actual == 5 )
            {

                $estado = 'Fin de sesión';
                
                $values[ $pre . 'logout'] = $fecha_estado;

                $fechas_seel['logout'] = $fecha_estado_seel;

            }

            if ( $estado_registrado < $estado_actual && $estado_actual < 5 )
            {
                    
                $values[ $pre . 'estado'] = $estado_actual;

                $values[ $pre . 'login'] = ( $pre ? $resp->first()->rec_login_ultimo : $resp->first()->login_ultimo );

                switch ( $estado_actual ) 
                {
        
                    case 1: 
                
                        $estado = 'Ingresó';
                        
                        $values[ $pre . 'login'] = $fecha_estado;
                    
                        $fechas_seel['login'] = $fecha_estado_seel;

                    break;
        
                    case 2: 
        
                        $estado = 'Incompleto';

                        $values[ $pre . 'inicio'] = $fecha_estado;

                        $fechas_seel['inicio'] = $fecha_estado_seel;
        
                    break;
        
                    case 3: 
        
                        $estado = 'Finalizado 1';

                        $values[ $pre . 'fin1'] = $fecha_estado;
                        
                        $fechas_seel['fin1'] = $fecha_estado_seel;

                        if ( $id_tipo == 0 )
                        {

                            $values[ $pre . 'logout'] = $fecha_estado; 
    
                            $fechas_seel['logout'] = $fecha_estado_seel;

                        }
        
                    break;
                
                    case 4: 
            
                        if ( $id_tipo > 0 )
                        {
                            $estado = 'Finalizado 2';

                            $values[ $pre . 'fin2'] = $fecha_estado;

                            $fechas_seel['fin2'] = $fecha_estado_seel;

                            $values[ $pre . 'logout'] = $fecha_estado; 

                            $fechas_seel['logout'] = $fecha_estado_seel;

                        }
        
                    break;

                }
                
            }

            $values['info_date_seel'] = json_encode($fechas_seel);
            
        }
        else
        {
            
            $values = [
                'idevaluacion'          => $id_evaluacion,
                $pre . 'login'          => $fecha_estado,
                $pre . 'login_ultimo'   => $fecha_estado,
                $pre . 'estado'         => 1,
                $pre . 'login_contador' => 1,
                'estado_temporal'=> $estado_actual,
                'info_date_seel' => '{"login":"' . $fecha_estado_seel . '", "login_ultimo":"' . $fecha_estado_seel . '"}'
            ];

            $estado = 'Inicio de sesión';

        }

        if ( $values )
        {

            Monitoreo::updateOrInsert(
                
                [
                    'tipo' => $id_tipo, 
                    'usuario' => $usuario 
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
