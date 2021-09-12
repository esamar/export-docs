<template>

    <div>

        <div class="row mb-3 mt-0">

            <div class="col text-left">


                <div style="float:left;"><h5><b>Administrar Muestra - Operativo</b></h5></div>
                <div v-if="loadIes" class="spinner-border spinner-border-sm text-primary ml-3" style="float:left;" role="status" aria-hidden="true"></div>

            </div>

            <div class="col text-right">

                <div class="btn-group">

                    <button class="btn btn-secondary btn-sm" type="button">
                        
                        <i class="fa fa-file-text mr-2"></i>

                        Operación masiva <b>.csv</b>

                    </button>

                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden"></span>
                    </button>
                    
                    <ul class="dropdown-menu">

                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('1')">Seleccionar IEs</a></li>
                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('2')" disabled>Actualizar datos IEs</a></li>
                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('3')" disabled>Retirar IE de la muestra</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('4')" disabled>Descargar muestra</a></li>

                    </ul>

                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm ml-1 mr-1 pt-0 pb-0" data-bs-toggle="modal" data-bs-target="#modal_add_ie" @click="mode='1'"> 

                    <svg class="bi mt-1" width="16" height="16"><use xlink:href="#bi-plus-square"/></svg>

                </button>
                
                <button type="button" v-bind:class="(listCodMods.length === 0 ? 'btn btn-outline-secondary btn-sm pt-0 pb-0' : 'btn btn-danger btn-sm pt-0 pb-0' ) " data-bs-toggle="modal" data-bs-target="#modal_servicio" :disabled = "listCodMods.length === 0" @click="showRemoveIe(null)">

                    <svg class="bi mt-1" width="16" height="16"><use xlink:href="#bi-trash-fill"/></svg>

                </button>

            </div>
            
            <modal-manage-ie-component :mode="mode" :sample="sample" :codmodProp = "codmodSelected" ></modal-manage-ie-component>

            <modal-manage-csv-component :mode="modeCSV" :sample="sample"></modal-manage-csv-component>

            <div class="modal fade " id="modal_alert" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ msgTitle}}</h5>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                        <div class="col-2">

                          <svg class="bi flex-shrink-0 mr-2" width="32" height="32" role="img" aria-label="Success:"><use xlink:href="#bi-info-circle-fill"/></svg>

                        </div>
                        <div class="col-10 text-left">
                            {{msgAlert}}
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="removeIe">{{msgButtonName}}</button>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <div class="row">

            <div class="mb-3" style="overflow: auto; width: 100%; height: calc( 100vh - 300px);">
                
                <table class="table table-hover small" >
                  <thead>
                    <tr>
                      <th scope="col"></th>
                      <th scope="col" style="text-align: left;width: 130px;">Cod mod</th>
                      <th scope="col" style="text-align: left;">Nombre</th>
                      <th scope="col" style="text-align: left;">Nivel</th>
                      <th scope="col" style="text-align: left;">Estrato</th>
                      <th scope="col" style="text-align: left;">Grupo</th>
                      <th scope="col" style="text-align: left;">Dispositivo</th>
                      <th scope="col" style="text-align: left;">UGE</th>
                      <th scope="col" style="text-align: left;">DRE</th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="ie in ies">

                        <td>                   
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" :value="ie.codmod" v-model = "listCodMods">
                            </div>
                        </td>

                      <th scope="row" style="text-align: left;width: 130px;">
                        
                        <a >
                          <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-grid-fill"/></svg> {{ie.codmod}}
                        </a>

                      </th>
                      <td style="text-align: left;">{{ie.nombre}}</td>
                      <td style="text-align: left;">{{ie.nivel}}</td>
                      <td style="text-align: left;">{{ie.estrato}}</td>
                      <td style="text-align: left;">{{ie.sub_muestra}}</td>
                      <td style="text-align: left;">{{ie.dispositivo}}</td>
                      <td style="text-align: left;">{{ie.dre}}</td>
                      <td style="text-align: left;">{{ie.dre}}</td>
                      <td>
                        
                        <a href="#" @click="editIe(ie.codmod)">
                            <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-pencil-square"/></svg>
                        </a> 

                      </td>
                      
                      <td>
                        
                        <a href="#" @click="showRemoveIe(ie.codmod)">
                            <svg class="bi mr-2" width="18" height="18" style="color:#C6C6C6;"><use xlink:href="#bi-trash"/></svg>
                        </a> 

                      </td>

                    </tr>

                  </tbody>
                </table>

            </div>

        </div>
        
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-end">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>

    </div>

</template>

<style type="text/css">
    thead tr th { 
        position: sticky;
        top: -1px;
        z-index: 10;
        background: #e9ecef;
    }
</style>

<script>

    export default {
        data()
        {
        
            return{
                ies:[],
                loadIes : false,
                mode : "1",
                modeCSV : "",
                codmodSelected : "",
                listCodMods : [],
                msgTitle : "",
                msgAlert : "",
                msgButtonName : "",

            };

        },
        props:{

            sample : String,

        },
        mounted() 
        {
            
            this.getIes();

        },
        created()
        {
            
            eventBus.$on('updateDescribe', function ( sample )
            {

                this.getIes();

            }.bind(this));

        },
        methods: 
        {

            getIes : function () 
            {

                this.loadIes = true;

                axios.get(`http://localhost:3000/api/ies/${this.sample}`)
                    .then( (response) => {

                        this.ies = response.data.data;
                        this.loadIes = false;

                });

            },

            editIe : function ( codmod )
            {

                // console.log(codmod );

                $("#modal_add_ie").modal('show');

                this.codmodSelected = codmod;

                this.mode = "0";

                // this.$emit( '');

            },

            showRemoveIe : function ( codmod )
            {

                if ( codmod )
                {

                    this.msgTitle = "Retirar IE de la muestra actual";
                    
                    this.msgAlert = `¿Está seguro que desea retirar la IE ${codmod} de la muestra? Presione 'Retirar' para continuar`;

                    this.listCodMods = [ codmod ];

                }
                else if ( this.listCodMods.length )
                {

                    this.msgAlert = `¿Está seguro que desea retirar ${this.listCodMods.length} IEs  de la muestra? Presione 'Retirar' para continuar`;

                }

                this.msgButtonName = "Retirar";

                $("#modal_alert").modal('show');

            },

            removeIe : function (  )
            {
                
                this.loadIes = true;

                axios.post(`http://localhost:3000/api/ies/${this.sample}/removeMuestra`, this.listCodMods )
                    .then( (response) => {
                        
                        if (response.data.resp)
                        {

                            $("#modal_alert").modal('hide');
                            
                            this.loadIes = false;

                            for (var i = 0; i < this.listCodMods.length; i++) {

                                const index = this.ies.findIndex( x=> {

                                                                return x.codmod == this.listCodMods[i]

                                                            });
                                this.ies.splice(index, 1);

                            }

                            this.listCodMods = [];

                            eventBus.$emit('updateDescribe' , this.sample );

                        }
                        else
                        {
                            
                            this.loadIes = false;

                            console.log("Error al eliminar" , response.data.resp );

                        }

                });

            },

            confOperationCSV : function ( mode )
            {
                
                $("#modal_ope_csv").modal('show');

                this.modeCSV = mode;

            }

        }
    }
</script>