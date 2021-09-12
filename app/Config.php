<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    
    const UPDATED_AT = null;
    
    const CREATED_AT = null;

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'tb_reglas_registro';
    
    // protected $fillable = ["nombres","appaterno","apmaterno","dni","idinstitucion","nivel","idgrado","grado","seccion","estado"];


}
