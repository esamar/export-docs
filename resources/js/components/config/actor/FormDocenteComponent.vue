<template>

    <div>

        <h6 class="mb-3"><b>Módulo Docente - {{ nivel == 1 ? 'Primaria' : 'Secundaria' }}</b></h6>

        <div class="card" style="background:#f9f9f9;">

            <div class="card-body">

                <div>
             
                    <h6><b>Rango de fechas para recepcion de datos de PPFF</b></h6>
                    
                    <div class="row">
                        
                        <div class="col-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Compromiso</label>
                            <h-date-picker :i18n="ptEs" ref="compromiso_ppff" @check-in-changed="setUpdate(1)" @check-out-changed="setUpdate(1)"></h-date-picker>
                        </div>
                
                        <div class="col-6 mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Recepción</label>
                            <h-date-picker :i18n="ptEs" ref="recepcion_ppff" @check-in-changed="setUpdate(2)" @check-out-changed="setUpdate(2)"></h-date-picker>
                        </div>
                        
                    </div>

                </div>

                <div>
                    
                    <h6><b>Rango de fechas para compromiso de resolución de cuestionario del director</b></h6>
            
                    <div class="mb-3">
                        <h-date-picker :i18n="ptEs" ref="compromiso_cuestionario" @check-in-changed="setUpdate(3)" @check-out-changed="setUpdate(3)"></h-date-picker>
                    </div>
                    
                </div>

                <div>
             
                    <h6><b>Rango de fechas para disponibilidad del boton Generar Credenciales</b></h6>
            
                    <div class="mb-3">
                        <h-date-picker :i18n="ptEs" ref="disponibilidad_credenciales" @check-in-changed="setUpdate(4)" @check-out-changed="setUpdate(4)"></h-date-picker>
                    </div>

                </div>

                <div>
                        
                    <label>Editor de plantilla para envios email y wathsapp</label>
                    
                    <div class="card">
                        <div class="card-header">
                            ***
                        </div>
                        <div class="card-body">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:110px;"><b>Email</b></span>
                                </div>
                                <textarea required class="form-control" placeholder="Plantilla" aria-label="Nombre del servicio" aria-describedby="button-addon2" rows="10" v-model:value="config.plantilla_mensaje.email"></textarea> 
                            </div>

                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:110px;"><b>WhatsApp</b></span>
                                </div>
                                <textarea required class="form-control" placeholder="Plantilla" aria-label="Nombre del servicio" aria-describedby="button-addon2" rows="10" v-model:value="config.plantilla_mensaje.wsp"></textarea> 
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</template>

<script>

    import 'vue-hotel-datepicker/dist/vueHotelDatepicker.css'
    
    import HDatePicker from 'vue-hotel-datepicker'
    
    export default {

        data()
        {
        
            return{
                config :{},
                ptEs: {
                      "night": "Dia",
                      "nights": "Dias",
                      "week": "week",
                      "weeks": "weeks",
                      "day-names": ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"],
                      "check-in": "Desde",
                      "check-out": "Hasta",
                      "month-names": [
                          "Enero",
                          "Febrero",
                          "Marzo",
                          "Abril",
                          "Mayo",
                          "Junio",
                          "Julio",
                          "Agosto",
                          "Septiembre",
                          "Octubre",
                          "Noviembre",
                          "Diciembre",
                      ],
                      "tooltip": {
                          "halfDayCheckIn": "Available CheckIn",
                          "halfDayCheckOut": "Available CheckOut",
                          "saturdayToSaturday": "Only Saturday to Saturday",
                          "sundayToSunday": "Only Sunday to Sunday",
                          "minimumRequiredPeriod": "%{minNightInPeriod} %{night} minimum.",
                      },
                    },
            
            };

        },
        components:
        {
            HDatePicker   
        },
        props:
        {

            nivel: String

        },
        watch:
        {

            nivel: function ()
            {

                this.getConfigDocente();

            }

        },
        mounted()
        {
            
            this.getConfigDocente();

        },
        methods:
        {
            setUpdate:function( option )
            {

                switch ( option )
                {

                    case 1:

                        this.config.rango_datos_ppff.compromiso.desde = moment(this.$refs.compromiso_ppff.checkIn).format('YYYY-MM-DD');

                        this.config.rango_datos_ppff.compromiso.hasta = moment(this.$refs.compromiso_ppff.checkOut).format('YYYY-MM-DD');

                    break;
                                        
                    case 2:

                        this.config.rango_datos_ppff.recepcion.desde = moment(this.$refs.recepcion_ppff.checkIn).format('YYYY-MM-DD');

                        this.config.rango_datos_ppff.recepcion.hasta = moment(this.$refs.recepcion_ppff.checkOut).format('YYYY-MM-DD');

                    break;
                                        
                    case 3:

                        this.config.rango_cuestionario.desde = moment(this.$refs.compromiso_cuestionario.checkIn).format('YYYY-MM-DD');

                        this.config.rango_cuestionario.hasta = moment(this.$refs.compromiso_cuestionario.checkOut).format('YYYY-MM-DD');

                    break;
                    
                    case 4:

                        this.config.rango_generar_credencial.desde = moment(this.$refs.disponibilidad_credenciales.checkIn).format('YYYY-MM-DD');

                        this.config.rango_generar_credencial.hasta = moment(this.$refs.disponibilidad_credenciales.checkOut).format('YYYY-MM-DD');

                    break;

                }

            },
            setHDatePicker(){

                const interval = setInterval(() => {

                    if (this.$refs.compromiso_ppff) {

                        this.$refs.compromiso_ppff.checkIn = new Date(this.config.rango_datos_ppff.compromiso.desde);
                        this.$refs.compromiso_ppff.checkOut = new Date(this.config.rango_datos_ppff.compromiso.hasta);

                        this.$refs.recepcion_ppff.checkIn = new Date(this.config.rango_datos_ppff.recepcion.desde);
                        this.$refs.recepcion_ppff.checkOut = new Date(this.config.rango_datos_ppff.recepcion.hasta);

                        this.$refs.compromiso_cuestionario.checkIn = new Date(this.config.rango_cuestionario.desde);
                        this.$refs.compromiso_cuestionario.checkOut = new Date(this.config.rango_cuestionario.hasta);

                        this.$refs.disponibilidad_credenciales.checkIn = new Date(this.config.rango_generar_credencial.desde);
                        this.$refs.disponibilidad_credenciales.checkOut = new Date(this.config.rango_generar_credencial.hasta);

                        clearInterval(interval);

                    }
                }, 50);


            },

           getConfigDocente : function ()
            {

                axios.get('get/2')
                    .then( (response) => {

                        this.config = response.data.filter( x => x.nivel == this.nivel )[0];

                        this.setHDatePicker();

                });

            }
        }
    
    }

</script>

