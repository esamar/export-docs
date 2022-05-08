<template>
    
    <div class="row">
        
        <div class="col-3">

            <div class="card">
              
              <!-- <div class="card-header"> -->
              
                <!-- <b>Detalles Muestra - Operativo </b> -->
              
              <!-- </div> -->

              <div class="card-body text-left">

                <div>

                    <label for="nombre_muestra" class="form-label"><b>Módulo</b></label>

                    <div class="list-group ">
                    
                        <a href="#" class="list-group-item list-group-item-action text-primary pl-2 pr-2" style="background: #F9F9F9;">
                            <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-grid-fill"/></svg>
                            <strong>Editor de formularios</strong>
                        </a>
                    
                        <a href="#" class="list-group-item list-group-item-action text-primary pl-2 pr-2" style="background: #F9F9F9;">
                            <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-grid-fill"/></svg>
                            <strong>Editor de items</strong>
                        </a>
                    
                        <a href="#" class="list-group-item list-group-item-action text-primary pl-2 pr-2" style="background: #F9F9F9;">
                            <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-grid-fill"/></svg>
                            <strong>Configuración de estados</strong>
                        </a>

                    </div>

                </div>

                <div class="mt-2">

                    <label for="nombre_muestra" class="form-label"><b>Generador de Credenciales</b></label>

                    <div class="list-group ">
                    
                        <a href="#" class="list-group-item list-group-item-action text-primary pl-2 pr-2" style="background: #F9F9F9;">
                            <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-cpu"/></svg>
                            <strong>Editor de reglas</strong>
                        </a>
                    
                        <a href="#" class="list-group-item list-group-item-action text-primary pl-2 pr-2" style="background: #F9F9F9;">
                            <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-tools"/></svg>
                            <strong>Debugging de reglas</strong>
                        </a>

                    </div>

                </div>


              </div>

            </div>

        </div>

        <div class="col-9 pl-0">

            <div class="row mb-3 mt-0">
            
                <div class="col text-left"><h5><b>Configurador de módulos</b></h5></div>
                
                <div class="col text-right">
                    
                    <button type="button" class="btn btn-primary btn-sm" @click="saveAll">
                        <svg class="bi mr-2" style="color:white;" width="16" height="16">
                        <use xlink:href="#bi-save-fill"/></svg> Guardar todo
                    </button>

                </div>

            </div>

            <div class="card text-center" style="height: calc(100vh - 250px);">

                <div class="card-header">

                    <ul class="nav nav-tabs card-header-tabs">

                        <li class="nav-item">
                            <a :class="'nav-link ' + (actor == 1 ? 'active' : '') " aria-current="true" href="#" @click="actor = 1">General</a>
                        </li>

                        <li class="nav-item">
                            <a :class="'nav-link ' + (actor == 2 ? 'active' : '') " aria-current="true" href="#" @click="actor = 2">Director</a>
                        </li>

                        <li class="nav-item">
                            <a :class="'nav-link ' + (actor == 3 ? 'active' : '') " aria-current="true" href="#" @click="actor = 3">Docente</a>
                        </li>

                        <li class="nav-item">
                            <a :class="'nav-link ' + (actor == 4 ? 'active' : '') " aria-current="true" href="#" @click="actor = 4">PPFF</a>
                        </li>

                    </ul>

                </div>
                
                <div class="card-body text-left overflow-auto" >

                    <form>

                        <form-director-component ref="directorPrimaria" class="pb-4" :nivel="'1'" :hidden="actor!=2"></form-director-component>

                        <form-director-component ref="directorSecundaria" :nivel="'2'" :hidden="actor!=2"></form-director-component>

                        <form-docente-component ref="docentePrimaria" class="pb-4" :nivel="'1'" :hidden="actor!=3"></form-docente-component>

                        <form-docente-component ref="docenteSecundaria" :nivel="'2'" :hidden="actor!=3"></form-docente-component>

                        <form-ppff-component ref="ppffPrimaria" class="pb-4" :nivel="'1'" :hidden="actor!=4"></form-ppff-component>

                        <form-ppff-component ref="ppffSecundaria" :nivel="'2'" :hidden="actor!=4"></form-ppff-component>

                    </form>

                </div>

            </div>

        </div>

    </div>

</template>

<script>
    
    import FormDirectorComponent from './actor/FormDirectorComponent.vue';
    import FormDocenteComponent from './actor/FormDocenteComponent.vue';
    import FormPpffComponent from './actor/FormPpffComponent.vue';

    export default {
        data()
        {
        
            return{
                nivel : "1",
                actor : 1, 
                director : []
            };

        },
        components:
        {
            
            FormDirectorComponent,
            FormDocenteComponent,
            FormPpffComponent,

        },
        methods:
        {

            saveAll : function()
            {

                console.log(this.$refs.directorPrimaria.config);
                console.log(this.$refs.directorSecundaria.config);
                console.log(this.$refs.docenteSecundaria.config);
                console.log(this.$refs.docenteSecundaria.config);
                console.log(this.$refs.ppffSecundaria.config);
                console.log(this.$refs.ppffSecundaria.config);


            }

        }
    
    }
</script>