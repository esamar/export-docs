<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuestionario;
use App\UserCuestionario;
use App\UserSiregCuestionario;

class CuestionarioController extends Controller
{
    
    public function setCredentials(Request $request )
    {

        $data = $request->all();

        $idinstitucion = Cuestionario::getInstitucion( $data['codmod'] );
        
        $id_usuario_existente = Cuestionario::existsUser( $data['user'] );

        // if ( $id_usuario_existente )
        // {

        //     return [ "resp" => 1, "idusuario" => $id_usuario_existente->idusuario , "error" => "El usuario ya existe, se ha cancelado la operación" ];

        // }

        if ( $idinstitucion )
        {

   	        if ( $id_usuario_existente )
	        {
				
				$idpersona = $id_usuario_existente->idusuario;

	        }
	        else
	        {
	        	
	        	$idpersona = $this->storePerson([
				        							"idrol"		=> $data['idrol'] ,
				        							"username"	=> $data['user'] ,
				        							"password"	=> $data['md5_user'] ,
					    							"dni" 		=> $data['dni'] ,
					    							"nombre" 	=> $data['nombres'] ,
					    							"apepat" 	=> $data['appaterno'] ,
					    							"apemat" 	=> $data['apmaterno'] ,
					    							"telefono1" => $data['telefono1'] ,
					    							"telefono2" => $data['telefono2'] ,
				        							"activo" 	=> 1,
				    							]);

	        }

        	if ( $idpersona )
        	{

        		$estado = 1;

        		$idusuario = $this->storeUser([
			        							"idusuario"		 => $idpersona ,
			        							"idcuestionario" => $data['idcuest'] ,
			        							"idinstitucion"	 => $idinstitucion->idinstitucion ,
			        							"estado"		 => $estado ,
			        						]);

        		if ( $idusuario )
        		{

        			$id_usuario_sireg_existente = Cuestionario::existsUserSireg( $data['user'] , $idpersona );

        			if ( $id_usuario_sireg_existente )
        			{
            			
            			return [ "resp" => 1, "iduser" => $id_usuario_sireg_existente->idususig , "error" => "El usuario sireg ya existe, se ha cancelado la operación" ];

        			}
        			else
					{
					
	        			$idusuario_sireg = $this->storeAuth([
				        							"dni"		 => $data['dni'] ,
				        							"username"   => $data['user'] ,
				        							"password"	 => $data['md5_user'] ,
				        							"idusuario"	 => $idpersona ,
				        						]);

	        			if ( $idusuario_sireg )
	        			{

	        				return [ "resp"=>1, "iduser" => $idusuario_sireg ];

	        			}
	        			else
	        			{
	        				
	        				return [ "resp"=>0, "error" => "No se puede crear el usuario SIREG" ];

	        			}

					}

        		}
        		else
        		{

        			return [ "resp"=>0, "error" => "No se puede crear la configuracion del cuestionario" ];

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

    public function storeAuth( $data )
    {

    	return UserSiregCuestionario::create( $data )->id;

    }

}
