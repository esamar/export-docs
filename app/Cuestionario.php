<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cuestionario extends Model
{
    
    const UPDATED_AT = null;
    
    const CREATED_AT = null;
    
	/**
	* The database connection used by the model.
	*
	* @var string
	*/

	protected $connection = 'mysql_cuestionario';

	/**
	* The database table used by the model.
	*
	* @var string
	*/

	protected $table = 'usuario';
    
    protected $fillable = ["idrol","username","password","dni","nombre","apepat","apemat","telefono1","telefono2","activo"];

    public static function getInstitucion( $codmod )
    {

    	return DB::connection( 'mysql_cuestionario' )
				->table('institucion')
				->select('idinstitucion')
				->where( 'modular' , '=' , $codmod )
				->get()
				->first();
    } 

    public static function existsUser( $dni , $user = null )
    {

    	return DB::connection( 'mysql_cuestionario' )
				->table('usuario')
				->select('idusuario')
				->where( 'dni' , '=' , $dni )
				->orwhere( 'username' , '=' , $user )
				->get()
				->first();
    } 

    public static function existsUserSireg( $user , $idusuario )
    {

    	return DB::connection( 'mysql_cuestionario' )
				->table('usuario_sireg')
				->select('idususig')
				->where( 'username' , '=' , $user )
				->where( 'idusuario' , '=' , $idusuario )
				->get()
				->first();
    } 

}
