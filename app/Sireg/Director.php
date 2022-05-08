<?php

namespace App\Sireg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Director extends Model
{
    
    protected $table = 'tb_dir_contacto';

    protected $primaryKey = 'CDIR_CODIGO';

    public static function getDirectorRegistro( $id_director )
    {

        return Director::select('tb_registro.Ie_CodigoModular AS cod_mod',
                                'tb_dir_registro.Age_Codigo AS id_registrador',
                                'tb_dir_contacto.CDIR_CODIGO AS id_director',
                                'CDIR_CRED_ACTIVO AS credencial_es_activo',
                                'CDIR_USUARIO AS usuario',
                                'CDIR_PASSWORD AS password',
                                'CDIR_RESUELVE AS resuelve',
                                DB::raw('TRIM(CDIR_RESUELVE_DATA) AS json_data_resuelve'),
                                DB::raw('TRIM(resp_cuestionario) AS json_data_cuestionario'),
                                'tb_dir_contacto.PER_CODIGO AS id_persona',
                                'PER_TIPO_DOC AS tipo_documento',
                                'PER_DOCUMENTO AS numero_documento',
                                'PER_NOMBRE AS nombres',
                                'PER_APELLIDO_P AS apellido_paterno',
                                'PER_APELLIDO_M AS apellido_materno',
                                'PER_EMAIL AS email',
                                'PER_TELEFONO AS telefono1',
                                'PER_TELEFONO2 AS telefono2')
                ->join('tb_registro', function( $join) {

                    $join->on('tb_registro.Ie_CodigoModular' , '=' , 'tb_dir_contacto.Ie_CodigoModular' );
                    $join->on('tb_dir_contacto.CDIR_ACTIVO' , '=' , DB::raw('1') );
                    $join->on('tb_registro.REG_TIPO' , '=' , DB::raw('1') );

                })
                
                ->join('tb_persona', 'tb_persona.PER_CODIGO' , '=' , 'tb_dir_contacto.PER_CODIGO' )

                ->leftjoin('tb_dir_registro', function( $join) {

                    $join->on('tb_dir_registro.CDIR_CODIGO' , '=' , 'tb_dir_contacto.CDIR_CODIGO' );
                    $join->on('tb_dir_registro.RDIR_ACTIVO' , '=' , DB::raw('1') );

                })
                ->where('tb_dir_contacto.CDIR_CODIGO','=', DB::raw($id_director))
                ->get();
               
    }

    public static function getAllDirectorRegistro( )
    {

        return Director::select('tb_registro.Ie_CodigoModular AS cod_mod',
                                'tb_dir_registro.Age_Codigo AS id_registrador',
                                'tb_dir_contacto.CDIR_CODIGO AS id_director',
                                'CDIR_CRED_ACTIVO AS credencial_es_activo',
                                'CDIR_USUARIO AS usuario',
                                'CDIR_PASSWORD AS password',
                                'CDIR_RESUELVE AS resuelve',
                                DB::raw('TRIM(CDIR_RESUELVE_DATA) AS json_data_resuelve'),
                                DB::raw('TRIM(resp_cuestionario) AS json_data_cuestionario'),
                                'tb_dir_contacto.PER_CODIGO AS id_persona',
                                'PER_TIPO_DOC AS tipo_documento',
                                'PER_DOCUMENTO AS numero_documento',
                                'PER_NOMBRE AS nombres',
                                'PER_APELLIDO_P AS apellido_paterno',
                                'PER_APELLIDO_M AS apellido_materno',
                                'PER_EMAIL AS email',
                                'PER_TELEFONO AS telefono1',
                                'PER_TELEFONO2 AS telefono2')
                ->join('tb_registro', function( $join) {

                    $join->on('tb_registro.Ie_CodigoModular' , '=' , 'tb_dir_contacto.Ie_CodigoModular' );
                    $join->on('tb_dir_contacto.CDIR_ACTIVO' , '=' , DB::raw('1') );
                    $join->on('tb_registro.REG_TIPO' , '=' , DB::raw('1') );

                })
                
                ->join('tb_persona', 'tb_persona.PER_CODIGO' , '=' , 'tb_dir_contacto.PER_CODIGO' )

                ->leftjoin('tb_dir_registro', function( $join) {

                    $join->on('tb_dir_registro.CDIR_CODIGO' , '=' , 'tb_dir_contacto.CDIR_CODIGO' );
                    $join->on('tb_dir_registro.RDIR_ACTIVO' , '=' , DB::raw('1') );

                })
                ->get();
               
    }

}
