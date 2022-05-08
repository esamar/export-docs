<template>

    <div>

        <h6 class="mb-3"><b>Módulo PPFF - Estudiante - {{ nivel == 1 ? 'Primaria' : 'Secundaria' }}</b></h6>

        <div class="card" style="background:#f9f9f9;">

            <div class="card-body">

                <div>
                        
                    <h6><b>Rango de fechas para compromiso de resolución de cuestionario del PPFF</b></h6>
                    
                            
                        <div class="mb-3">
                            <h-date-picker :i18n="ptEs" ref="rango_ppff" @check-in-changed="setUpdate(1)" @check-out-changed="setUpdate(1)"></h-date-picker>
                        </div>

                </div>

                <div>
             
                    <h6><b>Rango de fechas para compromiso de resolución de cuestionario del Estudiante</b></h6>
                    
                        
                        <div class="mb-3">
                            <h-date-picker :i18n="ptEs" ref="rango_est" @check-in-changed="setUpdate(2)" @check-out-changed="setUpdate(2)"></h-date-picker>
                        </div>

                </div>

                <div>
             
                    <h6><b>Rango de fechas para compromiso de resolución de cuestionario de HSE</b></h6>
                    
                        
                        <div class="mb-3">
                            <h-date-picker :i18n="ptEs" ref="rango_hse" @check-in-changed="setUpdate(3)" @check-out-changed="setUpdate(3)"></h-date-picker>
                        </div>

                </div>

                <div>
                    
                    <h6><b>Rango de fechas para compromiso de ingreso a la plataforma de Familiarización</b></h6>
            
                    <div class="mb-3">
                        <h-date-picker :i18n="ptEs" ref="rango_fam" @check-in-changed="setUpdate(4)" @check-out-changed="setUpdate(4)"></h-date-picker>
                    </div>
                    
                </div>

                <div>
                    
                    <h6><b>Rango horario para ingreso a Sistema de Evaluacion En Linea</b></h6>
            
                    <div class="mb-3">

                    </div>
                    
                </div>

                <div>
             
                    <h6><b>Rango de fechas para disponibilidad del boton Generar Credenciales</b></h6>
            
                    <div class="mb-3">
                        <h-date-picker :i18n="ptEs" ref="disponibilidad_credenciales" @check-in-changed="setUpdate(5)" @check-out-changed="setUpdate(5)"></h-date-picker>
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
                                <textarea required class="form-control" placeholder="Plantilla" aria-label="Nombre del servicio" aria-describedby="button-addon2" rows="10" v-model="config.plantilla_mensaje.email"></textarea> 
                            </div>

                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:110px;"><b>WhatsApp</b></span>
                                </div>
                                <textarea required class="form-control" placeholder="Plantilla" aria-label="Nombre del servicio" aria-describedby="button-addon2" rows="10" v-model="config.plantilla_mensaje.wsp"></textarea> 
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
                config :{
                            plantilla_mensaje : {
                                                    email : "",
                                                    wsp : ""
                                                }
                },
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

                this.getConfigDirector();

            }

        },
        mounted()
        {
            
            this.getConfigDirector();

        },
        methods:
        {
            setUpdate:function( option )
            {

                switch ( option )
                {

                    case 1:

                        this.config.rango_cuestionario_ppff.desde = moment(this.$refs.rango_ppff.checkIn).format('YYYY-MM-DD');

                        this.config.rango_cuestionario_ppff.hasta = moment(this.$refs.rango_ppff.checkOut).format('YYYY-MM-DD');

                    break;
                    
                    case 2:

                        this.config.rango_cuestionario_est.desde = moment(this.$refs.rango_est.checkIn).format('YYYY-MM-DD');

                        this.config.rango_cuestionario_est.hasta = moment(this.$refs.rango_est.checkOut).format('YYYY-MM-DD');

                    break;
                    
                    case 3:

                        this.config.rango_cuestionario_hse.desde = moment(this.$refs.rango_hse.checkIn).format('YYYY-MM-DD');

                        this.config.rango_cuestionario_hse.hasta = moment(this.$refs.rango_hse.checkOut).format('YYYY-MM-DD');

                    break;
                                        
                    case 4:

                        this.config.rango_cuestionario_fam.desde = moment(this.$refs.rango_fam.checkIn).format('YYYY-MM-DD');

                        this.config.rango_cuestionario_fam.hasta = moment(this.$refs.rango_fam.checkOut).format('YYYY-MM-DD');

                    break;
                                        
                    case 5:

                        this.config.rango_generar_credencial.desde = moment(this.$refs.disponibilidad_credenciales.checkIn).format('YYYY-MM-DD');

                        this.config.rango_generar_credencial.hasta = moment(this.$refs.disponibilidad_credenciales.checkOut).format('YYYY-MM-DD');

                    break;

                }

            },
            setHDatePicker(){

                const interval = setInterval(() => {

                    if (this.$refs.rango_ppff) {

                        this.$refs.rango_ppff.checkIn = new Date(this.config.rango_cuestionario_ppff.desde);
                        this.$refs.rango_ppff.checkOut = new Date(this.config.rango_cuestionario_ppff.hasta);

                        this.$refs.rango_est.checkIn = new Date(this.config.rango_cuestionario_est.desde);
                        this.$refs.rango_est.checkOut = new Date(this.config.rango_cuestionario_est.hasta);

                        this.$refs.rango_hse.checkIn = new Date(this.config.rango_cuestionario_hse.desde);
                        this.$refs.rango_hse.checkOut = new Date(this.config.rango_cuestionario_hse.hasta);

                        this.$refs.rango_fam.checkIn = new Date(this.config.rango_cuestionario_fam.desde);
                        this.$refs.rango_fam.checkOut = new Date(this.config.rango_cuestionario_fam.hasta);

                        this.$refs.disponibilidad_credenciales.checkIn = new Date(this.config.rango_generar_credencial.desde);
                        this.$refs.disponibilidad_credenciales.checkOut = new Date(this.config.rango_generar_credencial.hasta);

                        clearInterval(interval);

                    }
                }, 50);


            },

           getConfigDirector : function ()
            {

                axios.get('get/3')
                    .then( (response) => {
                        
                        console.log(response);

                        this.config = response.data.filter( x => x.nivel == this.nivel )[0];

                        this.setHDatePicker();

                });

            }
        }
    
    }

</script>

