<?php

namespace App\Http\Resources\Sireg\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class EstudianteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [

                    'cod_mod'              => $this->cod_mod,
                    'id_registrador'       => $this->id_registrador,
                    'id_ppff'              => $this->id_ppff,
                    'id_estudiante'        => $this->id_estudiante,
                    'credencial_es_activo' => $this->credencial_es_activo,
                    'estudiante'           => 
                        [
                            'usuario'            => $this->usuario_est,
                            'password'           => $this->password_est,
                            'fh_familiarizacion' => $this->fh_est_familiarizacion,
                            'fh_cuestionario'    => $this->fh_est_cuestionario,
                            'fh_hse_cuestionario'=> $this->fh_hse_cuestionario,
                            'hora_prueba_dia1'   => $this->hora_prueba_dia1,
                            'hora_prueba_dia2'   => $this->hora_prueba_dia2,
                            'resuelve'           => $this->resuelve_est,
                            'data_cuestionario'  => 
                                [
                                    'detalle_cuestionario'   => json_decode($this->json_data_estudiante_resuelve),
                                    'info_app_cuestionario'  => json_decode($this->json_data_estudiante_cuestionario)
                                ],
                            'data_persona' => 
                                [
                                    'tipo_documento'   => $this->tipo_documento_est,
                                    'numero_documento' => $this->numero_documento_est,
                                    'nombres'          => $this->nombres_est,
                                    'apellido_paterno' => $this->apellido_paterno_est,
                                    'apellido_materno' => $this->apellido_materno_est
                                ]
                        ],
                    'ppff'                  => 
                        [
                            'usuario'        => $this->usuario,
                            'password'       => $this->password,
                            'resuelve'       => $this->resuelve,
                            'fh_cuestionario'=> $this->fh_ppff_cuestionario,
                            'data_cuestionario' =>
                                [
                                    'detalle_cuestionario'          => json_decode($this->json_data_ppff_resuelve),
                                    'info_app_cuestionario'      => json_decode($this->json_data_ppff_cuestionario)
                                ],
                            'data_persona' => 
                                [
                                    'id_persona'       => $this->id_persona,
                                    'tipo_documento'   => $this->tipo_documento,
                                    'numero_documento' => $this->numero_documento,
                                    'nombres'          => $this->nombres,
                                    'apellido_paterno' => $this->apellido_paterno,
                                    'apellido_materno' => $this->apellido_materno,
                                    'email'            => $this->email,
                                    'telefono1'        => $this->telefono1,
                                    'telefono2'        => $this->telefono2
                                ],
                        
                        ]

        ];

    }

}
