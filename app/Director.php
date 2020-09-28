<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    
    protected $table = 'tb_dir_contacto';
    
    protected $primaryKey = 'CDIR_CODIGO';

    public function verifyData()
    {

	    $data = $this->select('Ie_CodigoModular')
	                ->get();
	    return $data;
	    
    }

}
