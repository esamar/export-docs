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
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <!-- <div class="modal-header"> -->
                    <!-- <h5 class="modal-title" id="staticBackdropLabel">Suscribir un servicio</h5> -->
    
                    <!-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" @click="cleanForm">
                        <span aria-hidden="true">&times;</span>
                    </button> -->

                  <!-- </div> -->

                    <form @submit.prevent="testConection" ref="formService" autocomplete="off">

                        <div class="modal-body text-left" style="padding: 0!important;">

                            <div class="card border-light m-0" :hidden = "stepFormSuscription != 0">

                                <div class="card-header">Configuración de base de datos del servicio</div>

                                <div class="card-body">

                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width:110px;"><b>Nombre perfil:</b></span>
                                        </div>
                                        <input required type="text" id="nombre_servicio" class="form-control" placeholder="Ingrese el nombre del servicio" aria-label="Nombre del servicio" aria-describedby="button-addon2" v-model="form.nameService">
                                    </div>

                                    <div class="input-group input-group-sm mb-3" disabled>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="width:110px;"><b>Perfil:</b></span>
                                        </div>
                                        <input required type="text" id="nombre_perfil" class="form-control" placeholder="Ingrese el nombre del perfil de servicio" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.nameProfile" disabled>
                                    </div>

                                    <div class="input-group input-group-sm mb-3">
                                        
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-link"></i>
                                            </span>
                                        </div>

                                        <input required type="text" id="url_servicio" class="form-control" placeholder="Ingrese la URL del servicio" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.urlService">
                                    
                                    </div>

                                    <label>Perfil de conexión</label>

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
                                                <i class="fa fa-plug"></i>
                                            </span>
                                        </div>
                                        <input required type="text" id="puerto_servicio" class="form-control" placeholder="Ingrese el Puerto de conexión" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.Port">
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

                                    <label>Tamaño de registro por transaccion</label>

                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa fa-cubes"></i> Tamaño
                                            </span>
                                        </div>
                                        <input required type="text" id="chunk_servicio" class="form-control" placeholder="Ingrese número máximo de filas por paquete" aria-label="Nombre de la muestra" aria-describedby="button-addon2" v-model="form.Chunk">
                                    </div>

                                    <div class="text-center" v-if="!conection">
                                        <button type="submit" class="btn btn-primary btn-sm m-0" style="width: 60%;">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loadTables"></span>
                                            Conectar a BD
                                        </button>                            
                                    </div>

                                    <div class="alert alert-success d-flex align-items-center m-0" role="alert" v-if="conection">
                                        <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                        <div>
                                            Se ha establecido conexión con {{ form.Host }}:{{ form.Port }}
                                        </div>
                                    </div>

                                    <div class="alert alert-danger d-flex align-items-center m-0 mt-3" role="alert" v-if="errConection">
                                        <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                        <div>
                                            No se puedo conectar con la BD. Se ha presentado el siguiente error : {{errConection}}
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="alert alert-danger d-flex align-items-center" role="alert" v-if="errCreationService">
                              <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                              <div>
                                No se puedo crear el servicio: {{errCreationService}}
                              </div>
                            </div>

                            <div class="card border-light m-0" :hidden = "stepFormSuscription != 1">

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
                                        <select class="form-control" v-model="form.Table" @change="getColumnsServicesTable($event, 1)">
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

                                            <select class="form-control" v-model="columnsServiceSelected[key]" @change="selectColumns">

                                                <option v-for="columnService in columnsService">{{columnService }}</option>

                                            </select>

                                        </div>

                                    </li>

                                  </ul>



                                </div>

                              </div>

                            </div>

                            <div class="card border-light m-0" :hidden = "stepFormSuscription != 2">

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
                                                        <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="loadPersonsColumns"></span>
                                                    </span>
                                                </div>

                                                <select class="form-control" v-model = "form.TablePerson" @change="getColumnsServicesTable($event, 2)">
                                                    <option v-for="Table in Tables">{{Table}}</option>
                                                </select>

                                            </div>

                                        </div>

                                        <ul class="list-group list-group-flush">

                                            <li v-for="columnLocalPerson, key in columnsLocalPersons" class="list-group-item mr-1 ml-1 p-2">

                                                <div class="input-group input-group-sm">
                                                    
                                                    <div class="input-group-prepend" >
                                                        <span class="input-group-text" style="background: white; width: 150px;">
                                                            <i class="fa fa-columns mr-2"></i>  <b>{{columnLocalPerson}}</b>
                                                        </span>
                                                    </div>

                                                    <select class="form-control" v-model="columnsPersonsServiceSelected[key]" @change="selectColumns">

                                                        <option v-for="column in columnsPersonsService">{{column }}</option>

                                                    </select>

                                                </div>

                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                            <div class="card border-light m-0" :hidden = "stepFormSuscription != 3">

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
                                                        <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="loadUsersColumns"></span>
                                                    </span>
                                                </div>
                                                <select class="form-control" v-model="form.TableUsers" @change="getColumnsServicesTable($event, 3)">
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

                                                <select class="form-control" v-model="columnsUsersServiceSelected[key]" @change="selectColumns">

                                                    <option v-for="column in columnsUsersService">{{column }}</option>

                                                </select>

                                            </div>

                                        </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                            <div class="card border-light m-0"  :hidden = "stepFormSuscription != 4">

                                <div class="card-header">

                                    Selección del modelo de distribución de asignaciones

                                </div>

                                <div class="card-body">

                                    <!-- <p class="card-text">Seleccione la tabla de destino </p> -->
                                        
                                    <!-- <div class="card" > -->

                                        <!-- <div class="card-header"> -->
                                        
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fa fa-connectdevelop mr-2"></i>  
                                                        Modelo distribución:
                                                        <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="loadModelColumns"></span>
                                                    </span>
                                                </div>
                                                <select class="form-control" v-model="form.modelo" @change="selectColumns">
                                                    <option v-for="model in modelsDistribution" :value="model.id">{{model.modelo}}</option>
                                                </select>
                                            </div>

                                        <!-- </div> -->

                                    <!-- </div> -->

                                </div>

                            </div>

                            <div class="modal-footer">
                            
                                <button type="button" id = "close_modal_service" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" @click="cleanForm">Cancelar</button>

                            
                                <button type="button" class="btn btn-primary btn-sm" @click="nextStep(-1)" v-if="prevButton==1">
                                    Atras
                                </button>
                                
                                <button type="button" class="btn btn-primary btn-sm" @click="nextStep(1)" v-if="nextButton==1" :disabled = "!conection">
                                    Siguiente
                                </button>
                                
                                <button type="submit" class="btn btn-primary btn-sm" @click="createService" :disabled = "!disableCreate" :hidden="suscribeButton!=1">
                                    <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="createtingService"></span>
                                    Suscribir
                                </button>
                                
                            </div>
                  
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
                <th></th>
                <th scope="col" style="text-align: left;">Servicio</th>
                <th scope="col">URL</th>
                <th scope="col">Muestra</th>
                <th scope="col"  style="text-align: center;">Usuarios</th>
                <th scope="col"  style="text-align: center;">Asignación </th>
                <th scope="col" style="text-align: right;"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="service in services" :key="service.id_servicio">
                <th>
                    <svg class="bi mr-2" width="16" height="16"><use xlink:href="#heading"/></svg> 
                </th>
                <th scope="row" style="text-align: left;">
                    {{ service.bd_perfil }}
                </th>
                <td>{{ service.url }}</td>
                <td>

                    <!-- <label v-if="service.state">{{ infoState( service.id_servicio ) }}</label> -->

                    <!-- <span v-if="service.state === 0" class="badge bg-light p-2">Pendiente</span>
                    <span v-if="service.state === 1" class="badge badge-primary p-2">Actualizado {{ service.updated }}</span>
                    <span v-if="service.state === 2" class="badge badge-danger p-2">
                        Desfasado

                        <span v-if="service.errors" class="btn badge bg-light text-dark" data-bs-toggle="modal" data-bs-target="#modal_errors" @click = "errorsSync(service.errors)">{{ service.errors.length }} errores </span>

                    </span> -->
                    <svg class="bi mr-2 spinner-gear" width="18" height="18"  :hidden = "stateRunSampleService != 2"><use xlink:href="#bi-gear-wide-connected"/></svg> 
                    
                    <svg class='bi mr-2 success-process' width="18" height="18" :hidden = "stateRunSampleService != 1"><use xlink:href="#check-circle-fill" /></svg> 
                    
                    <svg class="bi mr-2" width="18" height="18" style="color:lightgray" :hidden = "stateRunSampleService != 0"><use xlink:href="#bi-gear-wide-connected"/></svg> 

                </td>
                <td>

                    <svg class="bi mr-2 spinner-gear" width="18" height="18"  :hidden = "stateRunUserService != 2"><use xlink:href="#bi-gear-wide-connected"/></svg> 
                    
                    <svg class='bi mr-2 success-process' width="18" height="18" :hidden = "stateRunUserService != 1"><use xlink:href="#check-circle-fill" /></svg> 
                    
                    <svg class="bi mr-2" width="18" height="18" style="color:lightgray" :hidden = "stateRunUserService != 0"><use xlink:href="#bi-gear-wide-connected"/></svg> 

                </td>
                <td>
                    
                    <svg class="bi mr-2 spinner-gear" width="18" height="18"  :hidden = "stateRunModelService != 2"><use xlink:href="#bi-gear-wide-connected"/></svg> 
                    
                    <svg class='bi mr-2 success-process' width="18" height="18" :hidden = "stateRunModelService != 1"><use xlink:href="#check-circle-fill" /></svg> 
                    
                    <svg class="bi mr-2" width="18" height="18" style="color:lightgray" :hidden = "stateRunModelService != 0"><use xlink:href="#bi-gear-wide-connected"/></svg> 

                </td>

                <td style="text-align: right;width: 80px;">

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
                stepFormSuscription : 0,
                form : {
                    // Port : "3306",
                    // Chunk : "3500",
                    Chunk:"3500",
                    Host:"localhost",
                    Name:"admin_rnee_temp",
                    Password:"",
                    Port:"3306",
                    User:"root",
                    nameProfile:"PERFIL-DESARROLLO",
                    nameService:"PRUEBA",
                    urlService:"WWW",

                },
                Tables : [],
                columnsLocal : [],
                columnsLocalPersons : [],
                columnsLocalUsers : [],
                columnsService : [],
                columnsPersonsService : [],
                columnsUsersService : [],
                columnsServiceSelected : [],
                columnsPersonsServiceSelected : [],
                columnsUsersServiceSelected : [],
                modelsDistribution : [],
                errConection : "",
                errCreationService : "",
                conection : false,
                loadTables : false,
                loadColumns : false,
                loadModelColumns : false,
                loadPersonsColumns : false,
                loadUsersColumns : false,
                disableCreate : false,
                createtingService : false,
                infoErrorsSync : "",
                stateRunUserService : 1,
                stateRunSampleService : 1,
                stateRunModelService : 1,
                prevButton : 0,
                suscribeButton :0,
                nextButton : 1,
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
            this.getModels();

        },
        methods:
        {
            nextStep : function ( step )
            {
                
                this.stepFormSuscription = this.stepFormSuscription + step;

                if ( this.stepFormSuscription === 0 )
                {

                    this.prevButton = 0;

                }
                else if( this.stepFormSuscription == 4)
                {
                    this.suscribeButton = 1;
                    this.nextButton = 0;
                }
                else
                {
                    this.suscribeButton = 0;
                    this.nextButton = 1;
                    this.prevButton = 1;

                }



            },
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
            getModels : function ()
            {

                axios.get(`http://localhost:3000/api/service/models-distrib`)
                    .then( (response) => {

                        this.modelsDistribution = response.data.data;

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
            getColumnsServicesTable : function (event, step)
            {

                const table = event.target.value;
                
                this.loadColumns = true;

                const payload = {
                                Host : this.form.Host,
                                Name : this.form.Name,
                                Password : this.form.Password,
                                Port : this.form.Port,
                                Table : table,
                                User : this.form.User
                            };
                
                axios.post('http://localhost:3000/api/service/1/columns-mysql', payload )
                    .then( (response) => {

                        const pre_columns = new Array;

                        pre_columns.push("No usar");

                        switch (step)
                        {
                            case 1:
                                this.columnsService = pre_columns.concat(response.data.data);
                            break;

                            case 2:
                                this.columnsPersonsService = pre_columns.concat(response.data.data);
                            break;

                            case 3:
                                this.columnsUsersService = pre_columns.concat(response.data.data);
                            break;

                        }

                        this.loadColumns = false;

                });

            },
            selectColumns : function ()
            {

                if (this.columnsServiceSelected.length && this.columnsPersonsServiceSelected.length && this.columnsUsersServiceSelected.length && this.form.modelo)
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

                const columnsPersonsService = new Array;

                for (var i = 0; i < this.columnsPersonsServiceSelected.length; i++) 
                {

                    if ( typeof this.columnsPersonsServiceSelected[i] != 'undefined' && this.columnsPersonsServiceSelected[i] != 'No usar' )
                    {

                        columnsPersonsService.push({ index : i , col : this.columnsPersonsServiceSelected[i] });

                    }
                    
                }

                const columnsUsersService = new Array;

                for (var i = 0; i < this.columnsUsersServiceSelected.length; i++) 
                {

                    if ( typeof this.columnsUsersServiceSelected[i] != 'undefined' && this.columnsUsersServiceSelected[i] != 'No usar' )
                    {

                        columnsUsersService.push({ index : i , col : this.columnsUsersServiceSelected[i] });

                    }
                    
                }


                this.form['columns'] = columnsService;
                this.form['columnsPersons'] = columnsPersonsService;
                this.form['columnsUsers'] = columnsUsersService;

                this.errCreationService = "";

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
                            
                            // this.cleanForm();

                        }
                        else
                        {
                            
                            this.errCreationService = response.data.msg;

                        }

                });

            },
            syncService : function ( id_service ) 
            {
                this.stateRunSampleService = 0;
                this.stateRunUserService = 0;
                this.stateRunModelService = 0;

                this.syncSampleService(id_service)
                .then( resp_sample => {

                    return this.syncUserService(id_service)
                    .then( resp =>{

                        return Array( resp_sample ,resp ) ;

                    });

                })
                .then( resp_user =>{

                    return this.syncModelService(id_service)
                    .then( resp =>{

                        return resp_user.concat(resp);

                    });

                })
                .then( resp_all =>{

                    console.log(resp_all);

                    if ( resp_all[0].resp && resp_all[1].resp && resp_all[2].resp )
                    {



                    }
                    else{



                    }

                });

            },
            syncSampleService : async function ( id_service ) 
            {

                return new Promise((resolve, reject) => {

                    this.stateRunSampleService = 2;

                    const perfil = this.services.find( x => {
                        return x.id_servicio == id_service;
                    });

                    // perfil.action = "";

                    // perfil.state = 0;

                    // perfil.action = "Sincronizando";

                    axios.get(`http://localhost:3000/api/service/${this.sample}/run-sync-sample/${perfil.bd_perfil}`)
                        .then( (response) => {

                            if ( response.data.resp === 1 )
                            {
                                
                                this.stateRunSampleService = 1;
                                
                                resolve(response.data);

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

                        })
                        .catch( (err)=>{

                            this.infoErrorsSync = err;

                            $('#modal_errors').modal('show');

                            perfil.action = "Error";

                            perfil.state = 2;

                        });
                });
            },
            syncUserService : async function ( id_service ) 
            {

                return new Promise((resolve, reject) => {

                    this.stateRunUserService = 2;
                        
                    const perfil = this.services.find( x => {
                        return x.id_servicio == id_service;
                    });

                    // perfil.action = "";

                    // perfil.state = 0;

                    // perfil.action = "Sincronizando";

                    axios.get(`http://localhost:3000/api/service/${this.sample}/run-sync-user/${perfil.bd_perfil}`)
                        .then( (response) => {
                            resolve(response.data);
                            if ( response.data.resp === 1 )
                            {
                
                                this.stateRunUserService = 1;

                                // perfil.action = "Actualizado";

                                // perfil.state = 1;

                                // perfil["updated"] = response.data.success;

                                eventBus.$emit('updateDescribe' , true);

                            }
                            else if ( response.data.resp == 2 )
                            {

                                // perfil["errors"] = response.data.err;

                                // perfil.action = "Error";

                                // perfil.state = 2;
                            }

                        })
                        .catch( (err)=>{

                            this.infoErrorsSync = err;

                            $('#modal_errors').modal('show');

                            perfil.action = "Error";

                            perfil.state = 2;

                        });

                })

            },
            syncModelService : async function ( id_service ) 
            {

                return new Promise((resolve, reject) => {

                    this.stateRunModelService = 2;
                        
                    const perfil = this.services.find( x => {
                        return x.id_servicio == id_service;
                    });

                    // perfil.action = "";

                    // perfil.state = 0;

                    // perfil.action = "Sincronizando";

                    axios.get(`http://localhost:3000/api/service/${this.sample}/run-sync-model/${perfil.bd_perfil}`)
                        .then( (response) => {
                            resolve(response.data);
                            if ( response.data.resp === 1 )
                            {
                
                                this.stateRunModelService = 1;

                                // perfil.action = "Actualizado";

                                // perfil.state = 1;

                                // perfil["updated"] = response.data.success;

                                eventBus.$emit('updateDescribe' , true);

                            }
                            else if ( response.data.resp == 2 )
                            {

                                // perfil["errors"] = response.data.err;

                                // perfil.action = "Error";

                                // perfil.state = 2;
                            }

                        })
                        .catch( (err)=>{

                            this.infoErrorsSync = err;

                            $('#modal_errors').modal('show');

                            perfil.action = "Error";

                            perfil.state = 2;

                        });

                })

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

        },
        watch:
        {
            "form.nameService": function (nVal)
            {

                const temp = nVal.replaceAll(" ", "-").toUpperCase();

                return this.form.nameProfile = temp.replaceAll("--", "-") 

            }
        }
    }
</script>

<style>
    
    .success-process{
        color:#65b115;
    }

    .spinner-gear {
        color: #046ec5;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
        animation-duration: .8s;
        animation-name: spinner-loading
    }
    @keyframes spinner-loading{
    0% {
        transform: rotate(0deg)
    } to {
        transform: rotate(1turn)
    }
    }


</style>