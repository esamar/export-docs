<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        $data[0] = $request->all();

        $dni = $data[0]["dni"];

        $ubicacion_nav = $data[0]["ubicacion_nav"];
        
        $estado = "";
        
        switch ($ubicacion_nav) 
        {

            case 10: #'BIENVENIDO':
            case 11: #'TUTORIAL':
            case 1: #'PREINICIO':
        

                $estado = 'Ingresó';

            break;

            case 3: #'EVALUACION_TEXTO_COMPLETO':
            case 2: #'TRANSICION':
            case 4: #'EVALUACION':
            case 6: #'OPORTUNIAD':
            case 5: #'REVISION_FALTANTES':

                $estado = 'Incompleto';

            break;

            case 7: #'FINALIZADA': 
            case 9: #'AGRADECIMIENTO': 
            case 8: #'CUESTIONARIO': 

                $estado = 'Finalizado 1';

            break;

            case 12: #'CUESTIONARIO_AGRADECIMIENTO': 

                $estado = 'Finalizado 2';

            break;
        
        }

        $resp = DB::update("update admin_bd20_cuestionario.37978_persona SET 
                    
                    estado_evaluacion = '$estado',

                    origen_estado = 1

                    WHERE dni = '$dni' ;");

        return [ 'resp' => $resp , 'estado' => $estado ];

    }

}
