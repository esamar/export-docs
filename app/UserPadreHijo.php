<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPadreHijo extends Model
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
	protected $table = 'usuario_padre_hijo';

    protected $fillable = ["idusuario_padre","idusuario_hijo"];

}
