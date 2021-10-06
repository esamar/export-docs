<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCuestionario extends Model
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
	protected $table = 'usuario_cuestionario';

    protected $fillable = ["idusucue","idusuario","idcuestionario","idinstitucion","estado","seccion","fecha_participa"];

}
