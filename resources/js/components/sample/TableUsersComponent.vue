<template>

    <div>

        <div class="row mb-3 mt-0">

            <div class="col text-left">


                <div style="float:left;"><h5><b>Administrar Usuarios y cargas</b></h5></div>
                <div v-if="loadUsers" class="spinner-border spinner-border-sm text-primary ml-3" style="float:left;" role="status" aria-hidden="true"></div>

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

                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('1')">Asignar usuarios a la muestra</a></li>
                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('2')">Asignar carga IE a usuarios</a></li>
                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('3')" disabled>Desasignar carga IE a usuarios</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('4')">Registrar nuevas personas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" @click="confOperationCSV('5')" disabled>Descargar asignaciones de usuarios</a></li>

                    </ul>

                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm ml-1 mr-1 pt-0 pb-0" data-bs-toggle="modal" data-bs-target="#modal_add_user" @click="mode='1'"> 

                    <svg class="bi mt-1" width="16" height="16"><use xlink:href="#bi-person-plus-fill"/></svg>

                </button>

                <button type="button" class="btn btn-outline-secondary btn-sm ml-1 mr-1 ml-1 mr-1 pt-0 pb-0" data-bs-toggle="modal" data-bs-target="#modal_add_user" @click="mode='1'"> 

                    <svg class="bi mt-1" width="16" height="16"><use xlink:href="#bi-plus-square"/>Crear</svg>

                </button>

                <button type="button" v-bind:class="(listUsers.length === 0 ? 'btn btn-outline-secondary btn-sm ml-1 mr-1 pt-0 pb-0' : 'btn btn-warning btn-sm ml-1 mr-1 pt-0 pb-0' ) " data-bs-toggle="modal" data-bs-target="#modal_servicio" :disabled = "listUsers.length === 0" @click="showRemoveIe(null)">

                    <svg class="bi mt-1" width="16" height="16"><use xlink:href="#bi-person-check-fill"/></svg>

                </button>
                
                <button type="button" v-bind:class="(listUsers.length === 0 ? 'btn btn-outline-secondary btn-sm ml-1 mr-1 pt-0 pb-0' : 'btn btn-warning btn-sm ml-1 mr-1 pt-0 pb-0' ) " data-bs-toggle="modal" data-bs-target="#modal_servicio" :disabled = "listUsers.length === 0" @click="showRemoveIe(null)">

                    <svg class="bi mt-1" width="16" height="16"><use xlink:href="#bi-person-dash-fill"/></svg>

                </button>

                <button type="button" v-bind:class="(listUsers.length === 0 ? 'btn btn-outline-secondary btn-sm ml-1 mr-1 pt-0 pb-0' : 'btn btn-danger btn-sm ml-1 mr-1 pt-0 pb-0' ) " data-bs-toggle="modal" data-bs-target="#modal_servicio" :disabled = "listUsers.length === 0" @click="showRemoveIe(null)">

                    <svg class="bi mt-1" width="16" height="16"><use xlink:href="#bi-trash-fill"/></svg>

                </button>

            </div>

            <modal-manage-user-component :mode="mode" :sample="sample" :userProp = "userSelected" ></modal-manage-user-component>

            <modal-manage-user-csv-component :mode="modeCSV" :sample="sample"></modal-manage-user-csv-component>

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
                    <button type="button" class="btn btn-primary" @click="removeUser">{{msgButtonName}}</button>
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
                      <th scope="col" style="text-align: left;width: 130px;">Usuario</th>
                      <th scope="col" style="text-align: left;">DNI</th>
                      <th scope="col" style="text-align: left;">Nombres</th>
                      <th scope="col" style="text-align: left;">Email</th>
                      <th scope="col" style="text-align: left;">Teléfono</th>
                      <th scope="col" style="text-align: left;">Rol Modelo 1</th>
                      <th scope="col" style="text-align: left;">Rol Modelo 2</th>
                      <th scope="col" style="text-align: left;">IEs</th>
                      <th scope="col" style="text-align: left;">Estado</th>
                      <th scope="col"></th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users">

                        <td>                   
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" :value="user.usuario" v-model = "listUsers">
                            </div>
                        </td>

                      <th scope="row" style="text-align: left;width: 130px;">
                        
                        <a >
                          <svg class="bi mr-2" width="16" height="16"><use xlink:href="#people-circle"/></svg> {{user.usuario}}
                        </a>

                      </th>
                      <td style="text-align: left;">{{user.numero_documento}}</td>
                      <td style="text-align: left;">{{user.nombres + ', ' + user.apellido_paterno + ' ' + user.apellido_materno }}</td>
                      <td style="text-align: left;">{{user.email}}</td>
                      <td style="text-align: left;">{{user.telefono}}</td>
                      <td style="text-align: left;">{{user.rol_modelo_1}}</td>
                      <td style="text-align: left;">{{user.rol_modelo_2}}</td>
                      <td style="text-align: left;">{{user.total_ie}}</td>
                      <td style="text-align: left;">{{user.estado}}</td>
                      <td>
                        
                        <a href="#" @click="editUser(user.numero_documento)">
                            <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-pencil-square"/></svg>
                        </a> 

                      </td>
                      
                      <td>
                        
                        <a href="#" @click="showRemoveIe(user.usuario)">
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
    
    import ModalManageUserComponent from './users/ModalManageUserComponent.vue';
    import ModalManageUserCsvComponent from './users/ModalManageUserCSVComponent.vue';

    export default {

        data()
        {
        
            return{
                users:[],
                loadUsers : false,
                mode : "1",
                modeCSV : "",
                userSelected : "",
                listUsers : [],
                msgTitle : "",
                msgAlert : "",
                msgButtonName : "",

            };

        },
        components:
        {
           ModalManageUserComponent,
           ModalManageUserCsvComponent
        },        
        props:{

            sample : String,

        },
        mounted() 
        {
            
            this.getUsers();

        },
        created()
        {

            eventBus.$on('updateUsers', function ( sample )
            {

                this.getUsers();

            }.bind(this));

        },
        methods: 
        {

            getUsers : function () 
            {

                this.loadUsers = true;

                axiosR.get(`/api/users/${this.sample}`)
                    .then( (response) => {

                        this.users = response.data.data;

                        this.loadUsers = false;

                });

            },

            editUser : function ( dni )
            {

                $("#modal_add_user").modal('show');

                this.userSelected = dni;

                this.mode = "0";

            },

            showRemoveIe : function ( user )
            {

                if ( user )
                {

                    this.msgTitle = "Retirar IE de la muestra actual";
                    
                    this.msgAlert = `¿Está seguro que desea retirar el usuario  ${user} de la muestra? Presione 'Retirar' para continuar`;

                    this.listUsers = [ user ];

                }
                else if ( this.listUsers.length )
                {

                    this.msgAlert = `¿Está seguro que desea retirar ${this.listUsers.length} IEs  de la muestra? Presione 'Retirar' para continuar`;

                }

                this.msgButtonName = "Retirar";

                $("#modal_alert").modal('show');

            },

            removeUser : function (  )
            {
                
                this.loadUsers = true;

                axiosR.post(`/api/users/${this.sample}/removeUser`, this.listUsers )
                    .then( (response) => {
                        
                        if (response.data.resp)
                        {

                            $("#modal_alert").modal('hide');
                            
                            this.loadUsers = false;

                            for (var i = 0; i < this.listUsers.length; i++) {

                                const index = this.users.findIndex( x=> {

                                                                return x.usuario == this.listUsers[i]

                                                            });
                                this.users.splice(index, 1);

                            }

                            this.listUsers = [];

                            // eventBus.$emit('updateDescribe' , this.sample );

                        }
                        else
                        {
                            
                            this.loadUsers = false;

                            console.log("Error al eliminar" , response.data.resp );

                        }

                });

            },

            confOperationCSV : function ( mode )
            {
                
                $("#modal_ope_user_csv").modal('show');

                this.modeCSV = mode;

            }

        }
    }
</script>