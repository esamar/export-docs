<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSiregCuestionario extends Model
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
	protected $table = 'usuario_sireg';

    protected $fillable = ["idususig","dni","username","password","idusuario"];

}
