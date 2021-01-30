<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportTableDir extends Model
{

    const UPDATED_AT = null;
 
    public function DeleteTemp( $codigo )
    {
    	
    	DB::table('import_table_dirs')->whereId($codigo)->delete();

    }
   
}
