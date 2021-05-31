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

    protected $table = 'monitoreo_seel';

    public static function getTipo( $id_evaluacion )
    {

        return DB::connection( 'mysql_cuestionario' )
                ->table('evaluacion_seel')
                ->select('tipo')
                ->where( 'id' , '=' , $id_evaluacion )
                ->get()
                ->first();
    } 

}
