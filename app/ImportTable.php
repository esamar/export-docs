<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportTable extends Model
{

    const UPDATED_AT = null;
 
    public function DeleteTemp( $codigo )
    {
    	
    	DB::table('import_tables')->whereId($codigo)->delete();

    }
   
}
