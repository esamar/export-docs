<?php

namespace App\Http\Resources\Sireg\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class DocenteResource extends JsonResource
{

    public function toArray($request)
    {
        
        return [

                'codigo_modular' => $this->cod_mod,
                'id_registrador' => $this->id_registrador,
                'id_docente'    => $this->id_docente,
                'credencial_es_activo'  => $this->credencial_es_activo,
                'usuario'   => $this->usuario,
                'password'  => $this->password,
                'resuelve'  => $this->resuelve,
                'data_cuestionario' => [
                                        'detalle_cuestionario' => json_decode($this->json_data_resulve),
                                        'info_app_cuestionario'=> json_decode($this->json_data_cuestionario)
                                        ],
                'data_persona' => [
                                    'id_persona'    => $this->id_persona,
                                    'tipo_documento'    => $this->tipo_documento,
                                    'numero_documento'  => $this->numero_documento,
                                    'nombres'   => $this->nombres,
                                    'apellido_paterno'  => $this->apellido_paterno,
                                    'apellido_materno'  => $this->apellido_materno,
                                    'email' => $this->email,
                                    'telefono1' => $this->telefono1,
                                    'telefono2' => $this->telefono2
                                ]

        ];

    }
    
}
