<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Monitoreo extends Model
{
	
    const UPDATED_AT = null;
    
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

    protected $table = 'usuario_cuestionario';

    public static function getIdCuestionario( $id_evaluacion )
    {

        return DB::connection( 'mysql_cuestionario' )
                ->table('cuestionario')
                ->select('idcuestionario')
                ->where( 'seel_idevaluacion' , '=' , $id_evaluacion )
                ->get()
                ->first();
    } 

    public static function getIdUsuario( $dni )
    {

        return DB::connection( 'mysql_cuestionario' )
                ->table('usuario')
                ->select('idusuario')
                ->where( 'dni' , '=' , $dni )
                ->get()
                ->first();
    } 

}
