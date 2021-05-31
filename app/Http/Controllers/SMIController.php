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

        $fecha_estado = $data['fecha_hora'];

        if ( ( $estado_actual < 6 && $fecha_estado && $usuario ) == false )
        {
        
            return [ 'resp' => 0 , 'msg' => 'Error en los parametros' ];

        }

        $id_tipo = Monitoreo::getTipo($id_evaluacion);

        if ( is_null( $id_tipo ) )
        {

            return [ 'resp' => 0 , 'msg' => 'No existe el id/tipo de evaluación' ]; 

        }
        
        $id_tipo = $id_tipo->tipo;
        
        $resp = Monitoreo::where('tipo', '=' , $id_tipo )
                        ->where('usuario', '=' , $usuario )
                        ->get();
        
        if ( count( $resp ) )
        {
            
            $values = array();

            $estado_registrado = $resp->first()->estado;

            if ( $estado_actual === 1 )
            {

                $estado = 'Inicio de sesión';

                $values[ 'login_contador'] = (int) $resp->first()->login_contador + 1 ;

                $values['login_ultimo'] = $fecha_estado;

            }

            if ( $estado_actual == 5 )
            {

                $estado = 'Fin de sesión';
                
                $values['logout'] = $fecha_estado;

            }

            if ( $estado_registrado < $estado_actual && $estado_actual < 5 )
            {
                    
                $values['estado'] = $estado_actual;

                $values['login'] = $resp->first()->login_ultimo;

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

                        if ( $id_tipo == 0 )
                        {

                            $values['logout'] = $fecha_estado; 

                        }
        
                    break;
                
                    case 4: 
            
                        if ( $id_tipo > 0 )
                        {
                            $estado = 'Finalizado 2';

                            $values['fin2'] = $fecha_estado;

                            $values['logout'] = $fecha_estado; 

                        }
        
                    break;

                }
                
            }
            
        }
        else
        {
            
            $values = [
                'idevaluacion' => $id_evaluacion,
                'login' => $fecha_estado,
                'login_ultimo' => $fecha_estado,
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
