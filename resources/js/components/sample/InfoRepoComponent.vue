<template >

    <div>

        <form @submit.prevent="renameSample">

            <div class="input-group input-group-sm mb-3">

                <input type="text" class="form-control font-weight-bold" placeholder="Nombre de muestra" aria-label="Nombre de muestra" aria-describedby="button-addon2" v-model="info.muestra" required>

                <div class="input-group-append">
                    <button class="btn btn-secondary" type="submit" id="button-addon2">
                        Renombrar
                    </button>
                </div>

            </div>

            <div class="alert alert-success d-flex align-items-center" role="alert" v-if="resp_rename.msg_ok" @click="resp_rename.msg_ok = false">
              <svg class="bi flex-shrink-0 me-2 mr-2" width="16" height="16" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
              <div>
                    Se ha actualizado correctamente
              </div>
            </div>

            <div class="alert alert-danger d-flex align-items-center" role="alert" v-if="resp_rename.msg_err" @click="resp_rename.msg_err = false">
              <svg class="bi flex-shrink-0 me-2 mr-2" width="16" height="16" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
              <div>
                    No se puede actualizar el nombre de la muestra. Error: {{ resp_rename.msg_err }}
              </div>
            </div>

        </form>

        <div class="input-group input-group-sm mb-3">
          
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"><b>Repo:</b></span>
          </div>

          <input 
                type="text" 
                class="form-control" 
                aria-label="URL repositorio" 
                aria-describedby="button-addon2" 
                v-model = "repo">
          
          <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="button-addon2">
                <svg class="bi" width="16" height="16" ><use xlink:href="#clipboard-plus"/></svg>
            </button>
          </div>

        </div>

        <div>

            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center p-0 pt-1 pb-1">
                Tamaño
                <span class="badge badge-primary p-1">{{ info.Total }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-0 pt-1 pb-1">
                Primaria
                <span class="badge badge-primary p-1">{{ info.Primaria }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-0 pt-1 pb-1">
                Secundaria
                <span class="badge badge-primary p-1">{{ info.Secundaria }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center p-0 pt-1 pb-1">
                Recientes
                <span class="badge badge-primary p-1">{{ info.nuevos }}</span>
              </li>
            </ul>

        </div>

    </div>

</template>

<script>

export default {

    data() {
        
        return{
        
            info : [],
            repo : "",
            resp_rename : {
                msg_err : "",
                msg_ok : false
            }
        
        };

    },
    props : {

        sample : String,

    },
    mounted() {

        this.sampleDescribe();

        this.repo = `http://localhost:3000/api/ies/${this.sample}`

    },
    created()
    {

        eventBus.$on('updateDescribe', function ( sample )
        {

            this.sampleDescribe();

        }.bind(this));

    },
    methods: {

      renameSample : function  ( ) {

            const params = {
                                
                                new_name : this.info.muestra

                            };

            axios.put(`http://localhost:3000/api/sample/${this.sample}`, params )
                .then( (response) => {

                    if (response.data.resp)
                    {

                        this.resp_rename.msg_ok = true;

                        this.resp_rename.msg_err = "";

                    }
                    else
                    {

                        console.error("Error definido:" + response.data.msg );

                        this.resp_rename.msg_err = response.data.msg;

                        this.resp_rename.msg_ok = false;

                    }

        });

        },

        sampleDescribe : function ()
        {

            axios
            .get(`http://localhost:3000/api/ies/${this.sample}/describe`)
            .then( (response) => {

                this.info = response.data.data;

                eventBus.$emit('stateServicesSample', this.info.estado );

            });
        }


    }
}

</script>
