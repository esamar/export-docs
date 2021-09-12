<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function index( $option = 1 )
    {

        return view('config')
                ->with( 'tab' , $option );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function show(Config $config, $option )
    {

        switch( $option )
        {

            case 0:
            
                return Config::select('nivel','GRADO_DISPONIBLE')->get();

            break;

            case 1:
            
                return Config::select('nivel','DIR_RESUELVE_CUESTIONARIO','DIR_RANGO_FH_DATOS_DOCENTES','DIR_RANGO_FH_DATOS_PPFF','DIR_TEXT_ENVIO_CREDENCIALES','DIR_RANGO_FH_CUESTIONARIO','DIR_FH_HABILITAR_GEN_CREDENCIALES')
                        ->get()
                        ->map( function ($value)
                            {
                            
                                return [

                                        "nivel"                       => json_decode($value->nivel),
                                        "resuelve_cuestionario"       => json_decode($value->DIR_RESUELVE_CUESTIONARIO),
                                        "rango_datos_docente"         => json_decode($value->DIR_RANGO_FH_DATOS_DOCENTES),
                                        "rango_datos_ppff"            => json_decode($value->DIR_RANGO_FH_DATOS_PPFF),
                                        "plantilla_mensaje"           => json_decode($value->DIR_TEXT_ENVIO_CREDENCIALES),
                                        "rango_cuestionario"          => json_decode($value->DIR_RANGO_FH_CUESTIONARIO),
                                        "rango_generar_credencial"    => json_decode($value->DIR_FH_HABILITAR_GEN_CREDENCIALES)

                                    ];
                                
                            });


            break;

            case 2:
            
                return Config::select('nivel','DOC_RANGO_FH_DATOS_PPFF','DOC_RANGO_FH_CUESTIONARIO','DOC_FH_HABILITAR_GEN_CREDENCIALES','DOC_TEXT_ENVIO_CREDENCIALES')->get()
                      ->map( function ($value)
                            {
                            
                                return [

                                        "nivel"                       => json_decode($value->nivel),
                                        "rango_datos_ppff"            => json_decode($value->DOC_RANGO_FH_DATOS_PPFF),
                                        "rango_cuestionario"          => json_decode($value->DOC_RANGO_FH_CUESTIONARIO),
                                        "plantilla_mensaje"           => json_decode($value->DOC_TEXT_ENVIO_CREDENCIALES),
                                        "rango_generar_credencial"    => json_decode($value->DOC_FH_HABILITAR_GEN_CREDENCIALES)

                                    ];
                                
                            });

            break;

            case 3:
            
                return Config::select('nivel','PPFF_RANGO_FH_CUESTIONARIO_PPFF','PPFF_RANGO_FH_CUESTIONARIO_EST','PPFF_RANGO_FH_FAMILIARIZACION','PPFF_RANGO_FH_HSE','PPFF_RANGO_HORARIO','PPFF_FH_HABILITAR_GEN_CREDENCIALES','PPFF_TEXT_ENVIO_CREDENCIALES')->get()
                        ->map( function ($value)
                        {
                        
                            return [

                                    "nivel"                       => json_decode($value->nivel),
                                    "rango_cuestionario_ppff"     => json_decode($value->PPFF_RANGO_FH_CUESTIONARIO_PPFF),
                                    "rango_cuestionario_est"      => json_decode($value->PPFF_RANGO_FH_CUESTIONARIO_EST),
                                    "rango_cuestionario_fam"      => json_decode($value->PPFF_RANGO_FH_FAMILIARIZACION),
                                    "rango_cuestionario_hse"      => json_decode($value->PPFF_RANGO_FH_HSE),
                                    "rango_horario"               => json_decode($value->PPFF_RANGO_HORARIO),
                                    "rango_generar_credencial"    => json_decode($value->PPFF_FH_HABILITAR_GEN_CREDENCIALES),
                                    "plantilla_mensaje"           => json_decode($value->PPFF_TEXT_ENVIO_CREDENCIALES)

                                ];
                            
                        });

            break;

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function edit(Config $config)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Config $config)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Config  $config
     * @return \Illuminate\Http\Response
     */
    public function destroy(Config $config)
    {
        //
    }
}
