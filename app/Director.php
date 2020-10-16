<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    
    protected $table = 'tb_dir_contacto';
    
    protected $primaryKey = 'CDIR_CODIGO';

    $articles = DB::table('articles')
            ->select('articles.id as articles_id', ..... )
            ->join('categories', 'articles.categories_id', '=', 'categories.id')
            ->join('users', 'articles.user_id', '=', 'user.id')

            ->get();

}
