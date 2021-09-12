<template>
    
    <div class="col">
      
        <div class="row mb-3 mt-3">
            <div class="col text-left">
                <h5><b>Muestras - Operativos</b></h5>
            </div>
            <div class="col text-right">
              
              <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#frm_muestra">Crear Muestra</button>

              <div class="modal fade" id="frm_muestra" tabindex="-1" aria-labelledby="frm_muestraLabel" aria-hidden="true" v-if="modalShow" >

                <div class="modal-dialog">
                  
                  <div class="modal-content">

                    <div class="modal-header">
                      <h5 class="modal-title" id="frm_muestraLabel">Crear muestra</h5>
                      <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <form @submit.prevent="createSample">

                        <div class="modal-body text-left">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><b>Nombre:</b></span>
                                </div>
                                <input type="text" id="nombre_nueva_muestra" class="form-control" placeholder="Ingrese el nombre de la muestra" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="newSampleName" required>
                            </div>

                            <div class="alert alert-danger d-flex align-items-center" role="alert" v-if="errorSampleName">
                                <svg class="bi flex-shrink-0 me-2 mr-2" width="16" height="16" role="img" aria-label="Danger:">
                                    <use xlink:href="#exclamation-triangle-fill"/>
                                </svg>
                                <div>
                                    No se ha podido guardar. Ha ocurrido un error
                                </div>
                            </div>  

                        </div>

                        <div class="modal-footer">
                          <button type="button" id="close_modal_muestra" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" v-on:click="clearForm">Cancelar</button>
                          <button type="submit" class="btn btn-primary btn-sm">Crear</button>
                        </div>
                    
                    </form>

                  </div>

                </div>
              </div>
            </div>
        </div>

        <div class="row">
            <table class="table table-hover">
              <thead class="small" style = "background: #e9ecef;">
                <tr>
                  <th scope="col" style="text-align: left;">Nombre de la muestra</th>
                  <th scope="col">Tamaño</th>
                  <th scope="col">Creado</th>
                  <th scope="col">Actualizado</th>
                  <th scope="col">Suscrito</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="sample in samples" :key="sample.id_muestra">
                  <th scope="row" style="text-align: left;">
                    <a :href="`sample/edit/${sample.id_muestra}/1`">
                      <svg class="bi mr-2" width="16" height="16"><use xlink:href="#archive"/></svg> {{ sample.nombre_muestra}}
                    </a>
                  </th>
                  <td>{{ sample.total}}</td>
                  <td>{{ sample.created_at}}</td>
                  <td>{{ sample.updated_at}}</td>
                  <td>{{ sample.servicios}}</td>
                </tr>
              </tbody>
            </table>
        </div>

    </div>

</template>     

<script>
    export default {
        data()
        {
        
            return{
                samples:[],
                newSampleName : "",
                modalShow: true,
                errorSampleName: false,
            };
        
        },
        mounted() 
        {

            this.getSamples();

        },
        methods:
        {
            getSamples : function () 
            {

                axios.get('http://localhost:3000/api/sample')
                    .then( (response) => {

                        this.samples = response.data.data;

                });

            },

            createSample : function () 
            {

                const params = {
                    "name" : this.newSampleName,
                };

                axios.post('http://localhost:3000/api/sample', params )
                    .then( (response) => {

                        if ( response.data.resp )
                        {

                            this.samples.push(response.data.data);

                            document.getElementById('close_modal_muestra').click();

                            this.newSampleName = "";

                        }
                        else
                        {

                            this.errorSampleName = true;

                        }

                });

            },

            clearForm : function ()
            {

                this.errorSampleName = false;

                this.newSampleName = "";

            }

        }
    }
</script>