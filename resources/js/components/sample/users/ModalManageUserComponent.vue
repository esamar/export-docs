<template>

	<div class="modal fade" id="modal_add_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">

	        <h5 class="modal-title" id="staticBackdropLabel">
	        
	        	{{ ( mode == "1" ? "Agregar Usuario a la muestra" : "Actualzar información del Usuario " ) }}
	    	
	    	</h5>

	        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

			<div class="modal-body">

				<div class="row">

				    <div class="col text-left">
				        
				        <p class="card-text">Busque un usuario por el DNI para poder agregar a la muestra-operativo actual</p>
                        
                        <div class="alert alert-success d-flex align-items-center" role="alert" v-if="okModal">
                          <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                          <div>
                            {{ msgOk }}
                          </div>
                        </div>

                        <div class="alert alert-danger d-flex align-items-center" role="alert" v-if="errModal">
                          <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                          <div>
                            El DNI ingresado no tiene el formato correcto
                          </div>
                        </div>

				        <div class="card" >

				          <div class="card-header">
				            
				            <div class="input-group input-group-sm">

				                <div class="input-group-prepend">
				                    <span class="input-group-text">
				                        <svg class="bi mr-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
				                        DNI
				                        <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="loadUser"></span>
				                    </span>
				                </div>
				                
				                <input type="text" v-model = "dni"  class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  required :disabled ="okModal || mode == 0">

				                <div class="input-group-append" :hidden="okModal || mode == 0">
				                    <button class="btn btn-secondary" type="submit" id="button-addon2" @click="getUser">
				                        Buscar
				                    </button>
				                </div>
				            </div>

				          </div>

				          <ul class="list-group list-group-flush" v-if="respUser">

				            <li class="list-group-item mr-1 ml-1 p-2" :hidden="mode==0">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Nombres</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="fullNames" :disabled="mode==1">

				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2" :hidden="mode==1">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Nombres</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="user.nombres" :disabled="mode==1">

				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2" :hidden="mode==1">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Apellido Paterno</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="user.apellido_paterno" :disabled="mode==1">

				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2" :hidden="mode==1">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Apellido Materno</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="user.apellido_materno" :disabled="mode==1">


				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2" :hidden="mode==1">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Email</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="user.email" :disabled="mode==1">

				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2" :hidden="mode==1">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Teléfono</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="user.telefono" :disabled="mode==1">

				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Usuario</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="userPayload.usuario">

				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Contraseña</b>
				                        </span>
				                    </div>
				                    
				                    <input type="password" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="userPayload.password">

				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>ROL Mod 1: SIREG</b>
				                        </span>
				                    </div>
	                    
                            <select class="form-control" v-model:value="userPayload.rol_mod1">

                                <option v-for="rol in roles" :value="rol.id_rol">{{ rol.rol }}</option>

                            </select>

				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
			                    <div class="input-group-prepend" >
			                        <span class="input-group-text" style="background: white; width: 150px;">
			                            <i class="fa fa-columns mr-2"></i>  <b>ROL Mod 2: SMI</b>
			                        </span>
			                    </div>
                  
                          <select class="form-control" v-model:value="userPayload.rol_mod2">

                              <option v-for="rol in roles" :value="rol.id_rol">{{ rol.rol }}</option>

                          </select>

				                </div>

				            </li>

				          </ul>

				        </div>

					    <!-- <p class="card-text mt-3" v-if="respUser">Agregue los codigos modulares separados por comas (,)</p> -->

					    <div class="pb-2">
					    	
							<ul class="nav nav-tabs mt-3" v-if="respUser">
							<!-- <ul class="nav nav-pills mt-3" v-if="respUser"> -->

							  <li class="nav-item">

							    <a :class="tabs.tab1 ? 'nav-link active' : 'nav-link'" href="#" @click="tabSelect(1)">Asignadas</a>
							  </li>

							  <li class="nav-item">
							    <a :class="tabs.tab2 ? 'nav-link active' : 'nav-link'" href="#" @click="tabSelect(2)">Asignar nuevas IEs</a>
							  </li>

							</ul>

					    </div>

				        <div class="card mt-2" v-if="respUser && tabs.tab1">

				          <div class="card-header">

				            <div class="input-group input-group-sm">

					            <div class="col text-right pr-0">

					                <button type="button" :class="(listIesRemove.length === 0 ? 'btn btn-outline-secondary btn-sm pt-0 pb-0' : 'btn btn-danger btn-sm pt-0 pb-0' )" :disabled = "listIesRemove.length === 0" @click="selectRemoveIe()">

					                    <svg class="bi mt-1" width="16" height="16"><use xlink:href="#bi-trash-fill"/></svg>

					                </button>

					            </div>

				            </div>

				          </div>

							<div :hidden="!ies.length">
								
		            			<div class="mb-3" style="overflow: auto; width: 100%; height: 300px;">

									<table class="table table-hover small" >
					                  <thead>
					                    <tr>
					                      <th scope="col"></th>
					                      <th scope="col" style="text-align: left;width: 80px;">Cod mod</th>
					                      <th scope="col" style="text-align: left;">Nombre</th>
					                      <th scope="col" style="text-align: left;">Nivel</th>
					                      <th scope="col" style="text-align: left;"></th>
					                    </tr>
					                  </thead>
					                  <tbody>
					                    <tr v-for="ie in ies">

					                        <td>                   
					                            <div class="form-check">
					                              <input class="form-check-input" type="checkbox" :value="ie.codmod" v-model = "listIesRemove">
					                            </div>
					                        </td>

					                        <th scope="row" style="text-align: left;width: 130px;">
					                        
						                        <a >
						                          	<svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-grid-fill"/></svg> {{ie.codmod}}
						                        </a>

					                        </th>

					                      	<td style="text-align: left;">{{ ie.nombre }}</td>
					                      	<td style="text-align: left;">{{ ie.nivel }}</td>
						                    <td>
						                        
						                        <a href="#" @click="removeIe(ie.codmod)">
						                            <svg class="bi mr-2" width="18" height="18" style="color:#C6C6C6;"><use xlink:href="#bi-trash"/></svg>
						                        </a> 

						                    </td>

					                    </tr>

					                  </tbody>

					                </table>

						        </div>

							</div>

				        </div>

				        
				        <div class="card mt-2" v-if="respUser && tabs.tab2">

				          <div class="card-header">
				            
				            <div class="input-group input-group-sm">

				                <div class="input-group-prepend">
				                    <span class="input-group-text">
				                        <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-grid-fill"/></svg>
				                        <b>Código Modular</b>
				                        <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="processing"></span>
				                    </span>
				                </div>
				                
								<textarea class="form-control" aria-label="With textarea" v-model="textCodmod" placeholder="Ingese los codmod8 separados por comas (,)"></textarea>
				                
				                <div class="input-group-append">
				                    
				                    <button class="btn btn-secondary" type="submit" @click="getIes" :disabled="okModal">
				                        Buscar
				                    </button>

				                </div>

				            </div>

				          </div>
							
							<div :hidden="!contentCodmod.length">
								
		            			<div class="mb-3" style="overflow: auto; width: 100%; height: 300px;">

									<table class="table table-hover small" >
					                  <thead>
					                    <tr>
					                      <th scope="col" style="text-align: left;width: 80px;">Cod mod</th>
					                      <th scope="col" style="text-align: left;">Nombre</th>
					                      <th scope="col" style="text-align: left;">Nivel</th>
					                      <th scope="col" style="text-align: left;width: 80px;">Estado</th>
					                    </tr>
					                  </thead>
					                  <tbody>
					                    <tr v-for="ie in contentCodmod">

					                      	<th scope="row" style="text-align: left;width: 80px;">{{ie.codmod}}</th>
					                      	<td style="text-align: left;">{{ ie.nombre }}</td>
					                      	<td style="text-align: left;">{{ ie.nivel }}</td>
					                      	<td style="text-align: left;width: 80px;">
					                      		<span :class="'badge bg-' + ( ie.pertenece_muestra ? 'primary' : 'danger' ) + ' text-light p-2'">
					                      			{{ ie.pertenece_muestra ? 'Correcto' : 'No pertenece'}}
					                      		</span>
					                  		</td>

					                    </tr>

					                  </tbody>

					                </table>

						        </div>

							</div>

				        </div>


				    </div>

				    
				</div>

			</div>

	      <div class="modal-footer">

	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cleanForm">Cerrar</button>

	        <button v-if="mode==1" type="button" class="btn btn-primary" @click="addUserSample" :disabled ="!addBotton">Agregar</button>

	        <button v-if="mode==0" type="button" class="btn btn-primary" @click="UpdateUserSample" >Actualizar</button>

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
                user:{
						apellido_materno : "",
						apellido_paterno : "",
						created_at : "",
						email : "",
						nombres : "",
						numero_documento : "",
						telefono : "",
                	},
				ies : [],
				listIesRemove : [],
                userPayload : {
	                	numero_documento : "",
						usuario:"",
						password:"",
						rol_mod1:null,
						rol_mod2: null,
	                	codmod : [],
                },
				dni:"42903666",
                roles : {},
                loadUser : false,
                respUser : false,
                errModal : false,
                okModal : false,
                msgError :"",
                msgOk :"",
                addBotton:false,
                processing:false,
                contentCodmod : [],
                tabs : {
                	tab1 : true,
                	tab2 : false
                },

                textCodmod : "02018890,02071260,02071670,02084620,02092700,02093870,02110600,02144290,02145510,02145690,02194020,02240630,02246340,02303420,02314230,02349480,02361580,02366200,02367600,02386750,02402000,02422890,02431880,02478330,02523610,02558020,02567350,02590100,02591190,02591840,02623780,02624770,02625680,02629560,02689610,02729300,02736070,02737480,02738620,02757270,02765920,02774830,02782590,02783330",
            };

        },
        props:{

            sample : String,
            mode : String,
            userProp : String,

        },
        created()
        {

			this.getRol();

        },
        watch:
        {

        	userProp: function ()
        	{
				
				this.dni = this.userProp;

				this.getUser();

        	},

        },
        methods: 
        {

            cleanForm : function ()
            {

                this.user = {};

                this.dni = "";

                this.respUser = false;
                
                this.okModal = false;
                
                this.msgError = "";
                
                this.msgOk = "";

				this.addBotton = false;
                            
            },
            
            getRol : function ()
            {

                axiosR.get(`/api/users/0/roles`)
                    .then( (response) => {

                        this.roles = response.data.data;

                });

            },

            getUser : function ( ) 
            {

               	this.errModal = false;

	            this.getIesUser();

                if ( this.dni.length == 8 && !isNaN(this.dni)  )
                {

                	this.loadUser = true;

	                axiosR.get(`/api/users/0?dni=${this.dni}`)
	                    .then( (response) => {

	                        this.user = response.data.data[0];

	                        this.userPayload.numero_documento = this.dni;

	                        this.loadUser = false;

	                        this.respUser = true;

	                        this.activeAddButton();

	                });

                }
                else
                {

                	this.errModal = true;
	
					this.addBotton = false;

                }

            },
            getIesUser : function ()
            {

        		axiosR.get(`/api/users/${this.sample}/getIesUser/?dni=${this.dni}`)
                    .then( (response) => {

                        this.ies = response.data.data;

                });

            },
            addUserSample : function ()
            {

                axiosR.post(`/api/users/${this.sample}/setUserSample`, [ this.userPayload ] )
                    .then( (response) => {

                    	if ( response.data.resp )
                    	{

                    		this.msgOk = response.data.msg;

                    		this.okModal = true;

                    		this.addBotton = false

                    		eventBus.$emit('updateUsers' , true);

                    	}
                    	else
                    	{
							
							this.msgError = response.data.msg;	

                    		this.errModal = true;                    		

                    	}

                });

            },

            UpdateUserSample : function ()
            {

                axiosR.put(`/api/users/0/${this.dni}`, this.user )
                    .then( (response) => {

		                console.log(response.data);

		                eventBus.$emit('updateUsers' , true);

                });

                axiosR.put(`/api/users/${this.sample}/${this.dni}/updateUser`, this.userPayload )
                    .then( (response) => {

                    	console.log( response.data );

		                eventBus.$emit('updateUsers' , true);

                });


            },
            getIes : function ()
            {

            	axiosR.get(`/api/ies/${this.sample}?codmod=${this.textCodmod.replace(/\n/g, '')}`)
                    .then( (response) => {

                        this.contentCodmod = response.data.data;
	                    
	                    this.userPayload.codmod = this.contentCodmod
	                    							.filter( x => 
	                    							{
							                    		
							                    		return x.pertenece_muestra == 1

								                    })
	                    							.map( x => 
								                    {

								                    	return x.codmod

								                    });

	                    this.activeAddButton();

                });

            },
        	activeAddButton : function ()
        	{

                if ( this.userPayload.numero_documento && 
                	 this.userPayload.password && 
                	 this.userPayload.rol_mod1 && 
                	 this.userPayload.rol_mod2 && 
                	 this.contentCodmod.length )
                {

                	this.addBotton = true;

                }
                else
                {

                	this.addBotton = false;

            	}

        	},
        	tabSelect : function ( tab )
        	{

        		if ( tab == 1 )
        		{

	        		this.tabs.tab1 = true;
	        		this.tabs.tab2 = false;

        		}
        		else
        		{

	        		this.tabs.tab1 = false;
	        		this.tabs.tab2 = true;

        		}

        	},
        	removeIe : function ( codmod )
        	{

				this.listIesRemove = [ codmod ];

				this.selectRemoveIe();

        	},
        	selectRemoveIe : function ()
        	{

				this.ies = this.ies.filter( x => {

					if ( this.listIesRemove.indexOf(x.codmod) == -1 )
					{
						return x
					}

				});

				console.log(this.ies);

        	}

        },
        computed:
        {
        	fullNames: function ()
        	{
        	
        		return this.user.apellido_paterno + " " + this.user.apellido_materno + ", " + this.user.nombres;
        	
        	}
        }

    }


</script>