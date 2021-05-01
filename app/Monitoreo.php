<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monitoreo extends Model
{
	
    const UPDATED_AT = null;
    
     /**
     * The database connection used by the model.
     *
     * @var string
     */

     protected $connection = 'mysql_monitoreo';

     /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = '37978_persona';


}
