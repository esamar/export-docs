<?php

namespace App\Sireg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estudiante extends Model
{

    protected $table = 'tb_ppff_estudiante';

    protected $primaryKey = 'CEST_CODIGO';

    public static function getEstudianteRegistro( $id_estudiante )
    {

        return Estudiante::select(  
                        'tb_ppff_contacto.CPF_CODIGO AS id_ppff',
                        'tb_ppff_estudiante.CEST_CODIGO AS id_estudiante',
                        'tb_registro.Ie_CodigoModular AS cod_mod',
                        'tb_ppff_registro.Age_Codigo AS id_registrador',

                        'CPF_CRED_ACTIVO AS credencial_es_activo',
                        
                        'EST_USUARIO AS usuario_est',
                        'EST_PASSWORD AS password_est',
                        'EST_RESUELVE AS resuelve_est',
                        DB::raw('TRIM(EST_RESUELVE_DATA) AS json_data_estudiante_resuelve'),
                        DB::raw('TRIM(tb_ppff_estudiante.resp_cuestionario) AS json_data_estudiante_cuestionario'),
                        
                        'EST_TIPO_DOC AS tipo_documento_est',
                        'EST_NUMERO_DOC AS numero_documento_est',
                        'EST_NOMBRES AS nombres_est',
                        'EST_APELLIDO_P AS apellido_paterno_est',
                        'EST_APELLIDO_M AS apellido_materno_est',
                        

                        'RPF_FECHA_EST_FAMILIARIZACION AS fh_est_familiarizacion',
                        'RPF_FECHA_EST_CUESTIONARIO AS fh_est_cuestionario',
                        'RPF_FECHA_HSE_CUESTIONARIO AS fh_hse_cuestionario', 
                        'RPF_HORA_PRUEBA AS hora_prueba_dia1',
                        'RPF_HORA_PRUEBA2 AS hora_prueba_dia2',
                        
                        
                        'CPF_USUARIO AS usuario',
                        'CPF_PASSWORD AS password',
                        'CPF_RESUELVE AS resuelve',
                        'RPF_FECHA_PPFF_CUESTIONARIO AS fh_ppff_cuestionario',
                        
                        DB::raw('TRIM(CPF_RESUELVE_DATA) AS json_data_ppff_resuelve'),
                        DB::raw('TRIM(tb_ppff_contacto.resp_cuestionario) AS json_data_ppff_cuestionario'),
                        
                        'tb_ppff_contacto.PER_CODIGO AS id_persona',
                        'PER_TIPO_DOC AS tipo_documento',
                        'PER_DOCUMENTO AS numero_documento',
                        'PER_NOMBRE AS nombres',
                        'PER_APELLIDO_P AS apellido_paterno',
                        'PER_APELLIDO_M AS apellido_materno',
                        'PER_EMAIL AS email',
                        'PER_TELEFONO AS telefono1',
                        'PER_TELEFONO2 AS telefono2'
                    )
                ->join('tb_registro', function( $join) {

                    $join->on('tb_registro.Ie_CodigoModular' , '=' , 'tb_ppff_estudiante.Ie_CodigoModular' );
                    $join->on('tb_registro.REG_TIPO' , '=' , DB::raw('1') );

                })
                
                ->join('tb_ppff_contacto', 'tb_ppff_estudiante.CPF_CODIGO' , '=' , 'tb_ppff_contacto.CPF_CODIGO' )

                ->join('tb_persona', 'tb_persona.PER_CODIGO' , '=' , 'tb_ppff_contacto.PER_CODIGO' )

                ->join('tb_estudiante', 'tb_estudiante.EST_CODIGO' , '=' , 'tb_ppff_estudiante.EST_CODIGO' )

                ->leftjoin('tb_ppff_registro', function( $join) {

                    $join->on('tb_ppff_registro.CEST_CODIGO' , '=' , 'tb_ppff_estudiante.CEST_CODIGO' );
                    $join->on('tb_ppff_registro.RPF_ACTIVO' , '=' , DB::raw('1') );

                })
        ->where('tb_ppff_estudiante.CEST_CODIGO','=', DB::raw($id_estudiante))
        ->get();
               
    }

    public static function getAllEstudianteRegistro( )
    {

        return Estudiante::select(  
                        'tb_ppff_contacto.CPF_CODIGO AS id_docente',
                        'tb_ppff_estudiante.CEST_CODIGO AS id_estudiante',
                        'tb_registro.Ie_CodigoModular AS cod_mod',
                        'tb_ppff_registro.Age_Codigo AS id_registrador',

                        'CPF_CRED_ACTIVO AS credencial_es_activo',
                        
                        'EST_USUARIO AS usuario_est',
                        'EST_PASSWORD AS password_est',
                        'EST_RESUELVE AS resuelve_est',
                        DB::raw('TRIM(EST_RESUELVE_DATA) AS json_data_estudiante_resuelve'),
                        DB::raw('TRIM(tb_ppff_estudiante.resp_cuestionario) AS json_data_estudiante_cuestionario'),
                        
                        'EST_TIPO_DOC AS tipo_documento_est',
                        'EST_NUMERO_DOC AS numero_documento_est',
                        'EST_NOMBRES AS nombres_est',
                        'EST_APELLIDO_P AS apellido_paterno_est',
                        'EST_APELLIDO_M AS apellido_materno_est',

                        'RPF_FECHA_EST_FAMILIARIZACION AS fh_est_familiarizacion',
                        'RPF_FECHA_EST_CUESTIONARIO AS fh_est_cuestionario',
                        'RPF_FECHA_HSE_CUESTIONARIO AS fh_hse_cuestionario', 
                        'RPF_HORA_PRUEBA AS hora_prueba_dia1',
                        'RPF_HORA_PRUEBA2 AS hora_prueba_dia2',
                        
                        'CPF_USUARIO AS usuario',
                        'CPF_PASSWORD AS password',
                        'CPF_RESUELVE AS resuelve',
                        'RPF_FECHA_PPFF_CUESTIONARIO AS fh_ppff_cuestionario',
                        
                        DB::raw('TRIM(CPF_RESUELVE_DATA) AS json_data_ppff_resuelve'),
                        DB::raw('TRIM(tb_ppff_contacto.resp_cuestionario) AS json_data_ppff_cuestionario'),
                        
                        'tb_ppff_contacto.PER_CODIGO AS id_persona',
                        'PER_TIPO_DOC AS tipo_documento',
                        'PER_DOCUMENTO AS numero_documento',
                        'PER_NOMBRE AS nombres',
                        'PER_APELLIDO_P AS apellido_paterno',
                        'PER_APELLIDO_M AS apellido_materno',
                        'PER_EMAIL AS email',
                        'PER_TELEFONO AS telefono1',
                        'PER_TELEFONO2 AS telefono2'
                    )
                ->join('tb_registro', function( $join) {

                    $join->on('tb_registro.Ie_CodigoModular' , '=' , 'tb_ppff_estudiante.Ie_CodigoModular' );
                    $join->on('tb_registro.REG_TIPO' , '=' , DB::raw('1') );

                })
                
                ->join('tb_ppff_contacto', 'tb_ppff_estudiante.CPF_CODIGO' , '=' , 'tb_ppff_contacto.CPF_CODIGO' )

                ->join('tb_persona', 'tb_persona.PER_CODIGO' , '=' , 'tb_ppff_contacto.PER_CODIGO' )

                ->join('tb_estudiante', 'tb_estudiante.EST_CODIGO' , '=' , 'tb_ppff_estudiante.EST_CODIGO' )

                ->leftjoin('tb_ppff_registro', function( $join) {

                    $join->on('tb_ppff_registro.CEST_CODIGO' , '=' , 'tb_ppff_estudiante.CEST_CODIGO' );
                    $join->on('tb_ppff_registro.RPF_ACTIVO' , '=' , DB::raw('1') );

                })
                ->take(10)
                ->get();
               
    }

}
