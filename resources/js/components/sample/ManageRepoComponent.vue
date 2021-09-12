<template>

    <div class="mt-2">
    
        <label for="nombre_muestra" class="form-label"><b>Administrar</b></label>

        <div class="list-group ">
        
            <a :href="1" class="list-group-item list-group-item-action text-primary pl-2 pr-2" style="background: #F9F9F9;">
                <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-gear-fill"/></svg>             
                <strong>Servicios suscritos</strong>

                <span class="badge bg-danger" style="color:white;float:right;" v-if="state == '0'">

                    <svg class="bi" width="10" height="10"><use xlink:href="#exclamation-triangle-fill"/></svg> Atención

                </span>

            </a>

          <a :href="2" class="list-group-item list-group-item-action text-primary pl-2 pr-2" style="background: #F9F9F9;">
                <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-grid-fill"/></svg>
                <strong>Administrar muestra</strong>
            </a>

          <a :href="3" class="list-group-item list-group-item-action text-primary pl-2 pr-2" style="background: #F9F9F9;">
                <svg class="bi mr-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
                <strong>Administrar usuarios</strong>
            </a>


        </div>

    </div>

</template>

<script>

    export default {

    data() {
        
        return{

            state : '1',

        };

    },
    props : {

        sample : String,

    },
    mounted() {

    },
    created(){

        eventBus.$on('stateServicesSample', function ( stateServices ){

            this.state = stateServices;

        }.bind(this));

    },
    methods: {


      
    }
}

</script>