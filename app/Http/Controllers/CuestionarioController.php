<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuestionario;
use App\UserCuestionario;

class CuestionarioController extends Controller
{
    
    public function setCredentials(Request $request )
    {

        $data = $request->all();

        $idinstitucion = Cuestionario::getInstitucion( $data['codmod'] );
        
        $id_usuario_existente = Cuestionario::existsUser( $data['user'] );

        if ( $id_usuario_existente )
        {

            return [ "resp" => 1, "iduser" => $id_usuario_existente->id , "error" => "El usuario ya existe, se ha cancelado la operación" ];

        }

        if ( $idinstitucion )
        {

        	$idpersona = $this->storePerson([
				    							"nombres" 		=> $data['nombres'] ,
				    							"appaterno" 	=> $data['appaterno'] ,
				    							"apmaterno" 	=> $data['apmaterno'] ,
				    							"dni" 			=> $data['dni'] ,
				    							"idinstitucion" => $idinstitucion->id,
				    							"nivel" 		=> $data['nivel'] ,
				    							"idgrado" 		=> $data['idgrado'] ,
				    							"grado" 		=> $data['grado'] ,
				    							"seccion" 		=> $data['seccion'] ,
			        							"estado" 		=> 1,
			    							]);

        	if ( $idpersona )
        	{

        		$estado = ($data['idrol'] == 3 || $data['idrol'] == 9 ? 0 : 1 );
        		
        		$idusuario = $this->storeUser([
			        							"idpersona"		=> $idpersona ,
			        							"idrol"			=> $data['idrol'] ,
			        							"nombre"		=> $data['user'] ,
			        							"contrasenia"	=> $data['md5_user'] ,
			        							"estado"		=> $estado ,
			        						]);

        		if ( $idusuario )
        		{

        			return [ "resp"=>1, "iduser" => $idusuario ];

        		}
        		else
        		{

        			return [ "resp"=>0, "error" => "No se puede crear el usuario" ];

        		}

        	}
        	else
        	{
        		
        		return ["resp"=> 0, "error" => "No se puede crear la persona"];

        	}

        }
        else
        {
        	
        	return ["resp"=> 0, "error" => "No existe la institucion educativa"];
        
        }

    }

    public function storePerson( $data )
    {

    	return Cuestionario::create( $data )->id;

    }

    public function storeUser( $data )
    {

    	return UserCuestionario::create( $data )->id;

    }

}
