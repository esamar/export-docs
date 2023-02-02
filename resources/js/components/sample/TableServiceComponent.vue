<template>

    <div>
        <div class="row mb-3 mt-0">
          <div class="col text-left"><h5><b>Servicios suscritos</b></h5></div>
          <div class="col text-right">
            
            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_servicio">Suscribir servicio</button>
            
            <button type="button" class="btn btn-primary btn-sm" @click="syncAllServices"><svg class="bi mr-2" style="color:white;" width="16" height="16">
                <use xlink:href="#arrow-repeat"/></svg> Sincronizar todos
            </button>

          </div>


            <div class="modal fade" id="modal_servicio" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Suscribir un servicio</h5>
    
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" @click="cleanForm">
                        <span aria-hidden="true">&times;</span>
                    </button>

                  </div>

                    <form @submit.prevent="testConection" ref="formService" autocomplete="off">

                        <div class="modal-body text-left">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:110px;"><b>Nombre servicio:</b></span>
                                </div>
                                <input required type="text" id="nombre_servicio" class="form-control" placeholder="Ingrese el nombre del servicio" aria-label="Nombre del servicio" aria-describedby="button-addon2" v-model="form.nameService">
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width:110px;"><b>Perfil:</b></span>
                                </div>
                                <input required type="text" id="nombre_perfil" class="form-control" placeholder="Ingrese el nombre del perfil de servicio" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.nameProfile">
                            </div>


                            <label>Perfil de conexión</label>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-link"></i>
                                    </span>
                                </div>
                                <input required type="text" id="url_servicio" class="form-control" placeholder="Ingrese la URL del servicio" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.urlService">
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-puzzle-piece"></i>
                                    </span>
                                </div>
                                <input required type="text" id="puerto_servicio" class="form-control" placeholder="Ingrese el Puerto de conexión" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.Port" disabled="disabled">
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-server"></i>
                                    </span>
                                </div>
                                <input required type="text" id="host_servicio" class="form-control" placeholder="Ingrese el Host de conexión BD" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.Host">
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-database"></i>
                                    </span>
                                </div>
                                <input required type="text" id="esquema_servicio" class="form-control" placeholder="Ingrese el Nombre de BD" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.Name">
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-cubes"></i> Chunk
                                    </span>
                                </div>
                                <input required type="text" id="chunk_servicio" class="form-control" placeholder="Ingrese número máximo de filas por paquete" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.Chunk">
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-user"></i>
                                    </span>
                                </div>
                                <input required type="text" autocomplete="false" id="usuario_servicio" class="form-control" placeholder="Ingrese el Usuario de BD" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.User">
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-asterisk"></i>
                                    </span>
                                </div>
                                <input type="password" autocomplete="false" id="password_servicio" class="form-control" placeholder="Ingrese el Password de BD" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.Password">
                            </div>

                            <div class="text-center" v-if="!conection">
                                <button type="submit" class="btn btn-primary btn-sm mb-3" style="width: 60%;">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loadTables"></span>
                                    Conectar a BD
                                </button>                            
                            </div>

                            <div class="alert alert-success d-flex align-items-center" role="alert" v-if="conection">
                              <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                              <div>
                                Se ha establecido conexión con {{ form.Host }}:{{ form.Port }}
                              </div>
                            </div>

                            <div class="alert alert-danger d-flex align-items-center" role="alert" v-if="errConection">
                              <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                              <div>
                                No se puedo conectar con la BD. Se ha presentado el siguiente error : {{errConection}}
                              </div>
                            </div>

                            <!-- <div class="card border-light m-0" v-if="conection" > -->
                            <div class="card border-light m-0" >

                              <div class="card-header">

                                Configuración de tabla de Institución Educativa

                              </div>

                              <div class="card-body">

                                <p class="card-text">Seleccione la tabla de destino </p>
                                    
                                <div class="card" >
                                  <div class="card-header">
                                    
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-table mr-2"></i>  
                                                Tabla servicio
                                                <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="loadColumns"></span>
                                            </span>
                                        </div>
                                        <select class="form-control" v-model:value="form.Table" @change="getColumnsServices($event)">
                                            <option v-for="Table in Tables">{{Table}}</option>
                                        </select>
                                    </div>

                                  </div>

                                  <ul class="list-group list-group-flush">

                                    <li v-for="columnLocal, key in columnsLocal" class="list-group-item mr-1 ml-1 p-2">

                                        <div class="input-group input-group-sm">
                                            
                                            <div class="input-group-prepend" >
                                                <span class="input-group-text" style="background: white; width: 150px;">
                                                    <i class="fa fa-columns mr-2"></i>  <b>{{columnLocal}}</b>
                                                </span>
                                            </div>

                                            <select class="form-control" v-model:value="columnsServiceSelected[key]" @change="selectColumns">

                                                <option v-for="columnService in columnsService">{{columnService }}</option>

                                            </select>

                                        </div>

                                    </li>

                                  </ul>

                                </div>

                              </div>

                            </div>


                            <div class="card border-light m-0" >

                                <div class="card-header">

                                Configuración de tabla de Personas

                                </div>

                                <div class="card-body">

                                <p class="card-text">Seleccione la tabla de destino </p>
                                    
                                <div class="card" >

                                    <div class="card-header">
                                    
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-table mr-2"></i>  
                                                    Tabla servicio
                                                    <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="loadColumns"></span>
                                                </span>
                                            </div>
                                            <select class="form-control" v-model:value="form.Table" @change="getColumnsServices($event)">
                                                <option v-for="Table in Tables">{{Table}}</option>
                                            </select>
                                        </div>

                                    </div>

                                    <ul class="list-group list-group-flush">

                                    <li v-for="columnLocalPersona, key in columnsLocalPersons" class="list-group-item mr-1 ml-1 p-2">

                                        <div class="input-group input-group-sm">
                                            
                                            <div class="input-group-prepend" >
                                                <span class="input-group-text" style="background: white; width: 150px;">
                                                    <i class="fa fa-columns mr-2"></i>  <b>{{columnLocalPersona}}</b>
                                                </span>
                                            </div>

                                            <select class="form-control" v-model:value="columnsServiceSelected[key]" @change="selectColumns">

                                                <option v-for="columnService in columnsService">{{columnService }}</option>

                                            </select>

                                        </div>

                                    </li>

                                    </ul>

                                </div>

                                </div>

                                </div>


                            </div>









                            <div class="card border-light m-0" >

                                <div class="card-header">

                                Configuración de tabla de Usuarios

                                </div>

                                <div class="card-body">

                                <p class="card-text">Seleccione la tabla de destino </p>
                                    
                                <div class="card" >

                                    <div class="card-header">
                                    
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-table mr-2"></i>  
                                                    Tabla servicio
                                                    <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="loadColumns"></span>
                                                </span>
                                            </div>
                                            <select class="form-control" v-model:value="form.Table" @change="getColumnsServices($event)">
                                                <option v-for="Table in Tables">{{Table}}</option>
                                            </select>
                                        </div>

                                    </div>

                                    <ul class="list-group list-group-flush">

                                    <li v-for="columnLocalUser, key in columnsLocalUsers" class="list-group-item mr-1 ml-1 p-2">

                                        <div class="input-group input-group-sm">
                                            
                                            <div class="input-group-prepend" >
                                                <span class="input-group-text" style="background: white; width: 150px;">
                                                    <i class="fa fa-columns mr-2"></i>  <b>{{columnLocalUser}}</b>
                                                </span>
                                            </div>

                                            <select class="form-control" v-model:value="columnsServiceSelected[key]" @change="selectColumns">

                                                <option v-for="columnService in columnsService">{{columnService }}</option>

                                            </select>

                                        </div>

                                    </li>

                                    </ul>

                                </div>

                                </div>

                                </div>


                                <!-- </div> -->


















                        <div class="modal-footer">
                            
                            <button type="button" id = "close_modal_service" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" @click="cleanForm">Cancelar</button>
                            
                            <button type="submit" class="btn btn-primary btn-sm" @click="createService" :disabled = "!disableCreate">
                                <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="createtingService"></span>
                                Suscribir
                            </button>

                        </div>
                  
                    </form>

                </div>
              </div>
            </div>


        </div>
       
        <div class="row">

          <table class="table table-hover small">
            <thead>
              <tr>
                <th scope="col" style="text-align: left;">Servicio</th>
                <th scope="col">Perfil</th>
                <th scope="col">URL</th>
                <th scope="col">Modelo Usuarios</th>
                <th scope="col">Estado</th>
                <th scope="col" style="text-align: right;"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="service in services" :key="service.id_servicio">
                <th scope="row" style="text-align: left;">
                  <!-- <a href="#"> -->
                    <svg class="bi mr-2" width="16" height="16"><use xlink:href="#heading"/></svg> {{ service.nombre_servicio }}
                  <!-- </a> -->
                </th>
                <td>{{ service.bd_perfil }}</td>
                <td>{{ service.url }}</td>
                <td>Modelo</td>
                <td>

                    <!-- <label v-if="service.state">{{ infoState( service.id_servicio ) }}</label> -->

                    <span v-if="service.state === 0" class="badge bg-light p-2">Pendiente</span>
                    <span v-if="service.state === 1" class="badge badge-primary p-2">Actualizado {{ service.updated }}</span>
                    <span v-if="service.state === 2" class="badge badge-danger p-2">
                        Desfasado

                        <span v-if="service.errors" class="btn badge bg-light text-dark" data-bs-toggle="modal" data-bs-target="#modal_errors" @click = "errorsSync(service.errors)">{{ service.errors.length }} errores </span>

                    </span>
                    
                    

                    <!-- <span v-if="service.updated" class="badge badge-primary">{{ service.updated }}</span> -->


                </td>
                <td style="text-align: right;width: 130px;">

                    <button type="button" class="btn btn-warning btn-sm" v-if = "service.state == 2 || !service.state" @click="syncService(service.id_servicio)">
                        <div class="spinner-border text-primary spinner-border-sm" role="status" v-if = "service.action == 'Sincronizando'">
                          <span class="visually-hidden"></span>
                        </div>
                        {{ ( !service.state ? 'Sincronizar' : 'Reintentar' ) }}
                    </button>
                    
                </td>
              </tr>

            </tbody>
          </table>

            <div class="modal fade " id="modal_errors"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Errores de sincronización</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-left">

                        <div class="input-group">

                          <textarea class="form-control" aria-label="With textarea" style="height: 600px;background:black;color:#FF0013;">{{ infoErrorsSync }}</textarea>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id = "close_modal_errors" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" >Cerrar</button>
                    </div>

                </div>
              </div>
            </div>

        </div>

    </div>

</template>

<script>
    export default {
        data()
        {
        
            return{
                services:[],
                form : {
                    Port : "3306",
                    Chunk : "3500",
                },
                Tables : [],
                columnsLocal : [],
                columnsLocalPersons : [],
                columnsLocalUsers : [],
                columnsService : [],
                columnsServiceSelected : [],
                errConection : "",
                conection : false,
                loadTables : false,
                loadColumns : false,
                disableCreate : false,
                createtingService : false,
                infoErrorsSync : "",
            };

        },
        props:{

            sample : String,

        },
        mounted() 
        {

            this.getServices();

            this.getColumnsIe();

            this.getColumnsPersons();
            this.getColumnsUsers();
            
        },
        methods:
        {
            
            testConection: function ( event )
            {
                
                this.loadTables = true,
                
                this.errConection = "";

                axios.post('http://localhost:3000/api/service/1/tables-mysql', this.form )
                    .then( (response) => {

                        if ( response.data.resp )
                        {

                            this.Tables = response.data.data;

                            this.conection = true;
                            
                            this.loadTables = false;

                        }
                        else if ( !response.data.resp )
                        {

                            this.errConection = response.data.msg.code;

                            this.loadTables = false;

                        }

                });

            },

            getServices : function () 
            {

                axios.get(`http://localhost:3000/api/service/${this.sample}`)
                    .then( (response) => {

                        this.services = response.data.data;

                });

            },

            getColumnsIe : function () 
            {

                axios.get(`http://localhost:3000/api/ies/1/columns-local`)
                    .then( (response) => {

                        this.columnsLocal = response.data.data;

                });

            },
            getColumnsPersons : function ()
            {

                axios.get(`http://localhost:3000/api/users/columns-local-persons`)
                    .then( (response) => {

                        this.columnsLocalPersons = response.data.data;

                });   

            },
            getColumnsUsers : function ()
            {

                axios.get(`http://localhost:3000/api/users/columns-local-users`)
                    .then( (response) => {

                        this.columnsLocalUsers = response.data.data;

                });   

            },
            getColumnsServices : function (event)
            {

                this.loadColumns = true;

                axios.post('http://localhost:3000/api/service/1/columns-mysql', this.form )
                    .then( (response) => {

                        const pre_columns = new Array;

                        pre_columns.push("No usar");

                        this.columnsService = pre_columns.concat(response.data.data);

                        this.loadColumns = false;

                });

            },

            selectColumns : function ()
            {

                if (this.columnsServiceSelected.length)
                {

                    this.disableCreate = true;

                }

            },

            cleanForm : function ()
            {

                this.$refs.formService.reset();
                this.form = {Port : "3306",};
                this.Tables = [];
                this.columnsService = [];
                this.columnsServiceSelected = [];
                this.conection = false;
            
            },

            createService : function ()
            {

                this.createtingService = true;

                const columnsService = new Array;

                for (var i = 0; i < this.columnsServiceSelected.length; i++) 
                {

                    if ( typeof this.columnsServiceSelected[i] != 'undefined' && this.columnsServiceSelected[i] != 'No usar' )
                    {

                        columnsService.push({ index : i , col : this.columnsServiceSelected[i] });

                    }
                    
                }

                this.form['columns'] = columnsService;

                axios.post(`http://localhost:3000/api/service/${this.sample}/create/${this.form.nameProfile}`, this.form )
                    .then( (response) => {

                        if ( response.data.resp )
                        {

                            this.createtingService = false;
                                                        
                            this.services.push({
                                                bd_perfil : this.form.nameProfile,
                                                id_servicio : response.data.id_servicio,
                                                nombre_servicio : this.form.nameService,
                                                state : 0,
                                                url : this.form.urlService,
                                                action :'',

                                            });

                            document.getElementById('close_modal_service').click();
                            
                            eventBus.$emit('updateDescribe' , true);
                            
                            this.cleanForm();

                        }

                });

            },

            syncService : function ( id_service ) 
            {

                const perfil = this.services.find( x => {
                    return x.id_servicio == id_service;
                });

                perfil.action = "";

                perfil.state = 0;

                perfil.action = "Sincronizando";

                axios.get(`http://localhost:3000/api/service/${this.sample}/run-sync/${perfil.bd_perfil}`)
                        .then( (response) => {

                        if ( response.data.resp === 1 )
                        {

                            perfil.action = "Actualizado";

                            perfil.state = 1;

                            perfil["updated"] = response.data.success;

                            eventBus.$emit('updateDescribe' , true);

                        }
                        else if ( response.data.resp == 2 )
                        {

                            perfil["errors"] = response.data.err;

                            perfil.action = "Error";

                            perfil.state = 2;
                        }

                    }).catch( (err)=>{

                        this.infoErrorsSync = err;

                        $('#modal_errors').modal('show');

                        perfil.action = "Error";

                        perfil.state = 2;

                    });

            },

            syncAllServices : function ()
            {

                for (var i = 0; i < this.services.length; i++) 
                {
                    
                    this.syncService( this.services[i].id_servicio );

                }

            },

            errorsSync : function ( info ) 
            {

                this.infoErrorsSync = JSON.stringify(info);

            },

        }
    }
</script>