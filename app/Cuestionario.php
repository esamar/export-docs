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
	protected $table = '37978_persona';
    
    protected $fillable = ["nombres","appaterno","apmaterno","dni","idinstitucion","nivel","idgrado","grado","seccion","estado"];

    public static function getInstitucion( $codmod )
    {

    	return DB::connection( 'mysql_cuestionario' )
				->table('37978_institucion')
				->select('id')
				->where( 'modular' , '=' , $codmod )
				->get()
				->first();
    } 

    public static function existsUser( $user )
    {

    	return DB::connection( 'mysql_cuestionario' )
				->table('37978_usuario')
				->join('37978_persona', '37978_usuario.idpersona', '=', '37978_persona.id')
				->select('37978_usuario.id')
				->where( 'nombre' , '=' , $user )
				->get()
				->first();
    } 



}
