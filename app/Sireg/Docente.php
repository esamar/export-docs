<?php

namespace App\Sireg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Docente extends Model
{

    protected $table = 'tb_doc_contacto';

    protected $primaryKey = 'CDOC_CODIGO';

    public static function getDocenteRegistro( $id_docente )
    {

        return Docente::select('tb_registro.Ie_CodigoModular AS cod_mod',
                                'tb_doc_registro.Age_Codigo AS id_registrador',
                                'tb_doc_contacto.CDOC_CODIGO AS id_docente',
                                'CDOC_CRED_ACTIVO AS credencial_es_activo',
                                'CDOC_USUARIO AS usuario',
                                'CDOC_PASSWORD AS password',
                                'CDOC_RESUELVE AS resuelve',
                                DB::raw('TRIM(CDOC_RESUELVE_DATA) AS json_data_resuelve'),
                                DB::raw('TRIM(resp_cuestionario) AS json_data_cuestionario'),
                                'tb_doc_contacto.PER_CODIGO AS id_persona',
                                'PER_TIPO_DOC AS tipo_documento',
                                'PER_DOCUMENTO AS numero_documento',
                                'PER_NOMBRE AS nombres',
                                'PER_APELLIDO_P AS apellido_paterno',
                                'PER_APELLIDO_M AS apellido_materno',
                                'PER_EMAIL AS email',
                                'PER_TELEFONO AS telefono1',
                                'PER_TELEFONO2 AS telefono2')
        ->join('tb_registro', function( $join) {

            $join->on('tb_registro.Ie_CodigoModular' , '=' , 'tb_doc_contacto.Ie_CodigoModular' );
            $join->on('tb_registro.REG_TIPO' , '=' , DB::raw('1') );

        })
        
        ->join('tb_persona', 'tb_persona.PER_CODIGO' , '=' , 'tb_doc_contacto.PER_CODIGO' )

        ->leftjoin('tb_doc_registro', function( $join) {

            $join->on('tb_doc_registro.CDOC_CODIGO' , '=' , 'tb_doc_contacto.CDOC_CODIGO' );
            $join->on('tb_doc_registro.RDOC_ACTIVO' , '=' , DB::raw('1') );

        })
        ->where('tb_doc_contacto.CDOC_CODIGO','=', DB::raw($id_docente))
        ->get();
               
    }

    public static function getAllDocenteRegistro( )
    {

        return Docente::select('tb_registro.Ie_CodigoModular AS cod_mod',
                                'tb_doc_registro.Age_Codigo AS id_registrador',
                                'tb_doc_contacto.CDOC_CODIGO AS id_docente',
                                'CDOC_CRED_ACTIVO AS credencial_es_activo',
                                'CDOC_USUARIO AS usuario',
                                'CDOC_PASSWORD AS password',
                                'CDOC_RESUELVE AS resuelve',
                                DB::raw('TRIM(CDOC_RESUELVE_DATA) AS json_data_resuelve'),
                                DB::raw('TRIM(resp_cuestionario) AS json_data_cuestionario'),
                                'tb_doc_contacto.PER_CODIGO AS id_persona',
                                'PER_TIPO_DOC AS tipo_documento',
                                'PER_DOCUMENTO AS numero_documento',
                                'PER_NOMBRE AS nombres',
                                'PER_APELLIDO_P AS apellido_paterno',
                                'PER_APELLIDO_M AS apellido_materno',
                                'PER_EMAIL AS email',
                                'PER_TELEFONO AS telefono1',
                                'PER_TELEFONO2 AS telefono2')
                ->join('tb_registro', function( $join) {

                    $join->on('tb_registro.Ie_CodigoModular' , '=' , 'tb_doc_contacto.Ie_CodigoModular' );
                    $join->on('tb_registro.REG_TIPO' , '=' , DB::raw('1') );

                })
                
                ->join('tb_persona', 'tb_persona.PER_CODIGO' , '=' , 'tb_doc_contacto.PER_CODIGO' )

                ->leftjoin('tb_doc_registro', function( $join) {

                    $join->on('tb_doc_registro.CDOC_CODIGO' , '=' , 'tb_doc_contacto.CDOC_CODIGO' );
                    $join->on('tb_doc_registro.RDOC_ACTIVO' , '=' , DB::raw('1') );

                })
                ->get();
               
    }

}
