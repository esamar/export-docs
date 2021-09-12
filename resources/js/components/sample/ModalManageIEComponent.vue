<template>

	<div class="modal fade" id="modal_add_ie" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">

	        <h5 class="modal-title" id="staticBackdropLabel">
	        
	        	{{ ( mode == "1" ? "Agregar IE a la muestra" : "Actualzar información de una IE " ) }}
	    	
	    	</h5>

	        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

			<div class="modal-body">

				<div class="row">

				    <div class="col text-left">
				        
				        <p class="card-text">Busque una IE por el código modular para poder agregar a la muestra actual</p>
                        
                        <div class="alert alert-success d-flex align-items-center" role="alert" v-if="okModal">
                          <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                          <div>
                            {{ msgOk }}
                          </div>
                        </div>

                        <div class="alert alert-danger d-flex align-items-center" role="alert" v-if="errModal">
                          <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                          <div>
                            {{ msgError || "El código modular ingresado no tiene el formato correcto" }}
                          </div>
                        </div>

				        <div class="card" >

				          <div class="card-header">
				            
				            <div class="input-group input-group-sm">

				                <div class="input-group-prepend">
				                    <span class="input-group-text">
				                        <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-grid-fill"/></svg>
				                        Código Modular
				                        <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="loadIe"></span>
				                    </span>
				                </div>
				                
				                <input type="text" v-model = "codmod"  class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  required :disabled ="okModal || mode == 0">

				                <div class="input-group-append" :hidden="okModal || mode == 0">
				                    <button class="btn btn-secondary" type="submit" id="button-addon2" @click="getIe">
				                        Buscar
				                    </button>
				                </div>
				            </div>

				          </div>

				          <ul class="list-group list-group-flush" v-if="respIe">

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Nombre</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="ie.nombre" :disabled="mode==1">


				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Nivel</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="ie.nivel" :disabled="mode==1">


				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>Estrado</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="ie.estrato" :disabled="mode==1">


				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>UGEL</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="ie.uge" :disabled="mode==1">


				                </div>

				            </li>

				            <li class="list-group-item mr-1 ml-1 p-2">

				                <div class="input-group input-group-sm">
				                    
				                    <div class="input-group-prepend" >
				                        <span class="input-group-text" style="background: white; width: 150px;">
				                            <i class="fa fa-columns mr-2"></i>  <b>DRE</b>
				                        </span>
				                    </div>
				                    
				                    <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" aria-describedby="button-addon2"  v-model="ie.dre" :disabled="mode==1">


				                </div>

				            </li>

				          </ul>

				        </div>

				    </div>

				    
				</div>

			</div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="cleanForm">Cerrar</button>

	        <button v-if="mode==1" type="button" class="btn btn-primary" @click="addIeSample" :disabled ="!addBotton">Agregar</button>

	        <button v-if="mode==0" type="button" class="btn btn-primary" @click="UpdateIeSample" >Actualizar</button>

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
                ie:{
						codmod:"",
						dre:"",
						dre_codigo:"",
						nivel:"",
						nivel_codigo:"",
						nombre:"",
						uge:"",
						estrato:"",
						uge_codigo:"",
                	},
				codmod:"",
                loadIe : false,
                respIe : false,
                errModal : false,
                okModal : false,
                msgError :"",
                msgOk :"",
                addBotton:false,
            };

        },
        props:{

            sample : String,
            mode : String,
            codmodProp : String,

        },
        watch:
        {

        	codmodProp: function ()
        	{
				
				this.codmod = this.codmodProp;

				this.getIe();

        	}

        },
        methods: 
        {

            cleanForm : function ()
            {

                this.ie = {};

                this.codmod = "";

                this.respIe = false;
                
                this.okModal = false;
                
                this.msgError = "";
                
                this.msgOk = "";

				this.addBotton = false;
                            
            },

            getIe : function ( ) 
            {

               	this.errModal = false;

               	this.addBotton = false;

                if ( this.codmod.length == 8 && !isNaN(this.codmod)  )
                {

                	this.loadIe = true;

	                axios.get(`http://localhost:3000/api/ies/0?codmod=${this.codmod}`)
	                    .then( (response) => {

	                    	if ( response.data.resp )
	                    	{

		                        this.ie = response.data.data;

		                        this.loadIe = false;

		                        this.respIe = true;

		                        this.addBotton = true;

	                    	}
	                    	else
	                    	{

                				this.errModal = true;

                				this.msgError = response.data.msg;
		                        
		                        this.loadIe = false;

	                    	}

	                });

                }
                else
                {

                	this.errModal = true;

                }

            },

            addIeSample : function ()
            {

                axios.post(`http://localhost:3000/api/ies/${this.sample}/setMuestra`, [ this.codmod ] )
                    .then( (response) => {

                    	if ( response.data.resp )
                    	{

                    		this.msgOk = response.data.msg;

                    		this.okModal = true;

                    		this.addBotton = false

                    		eventBus.$emit('updateDescribe' , true);

                    	}
                    	else
                    	{
							
							this.msgError = response.data.msg;	

                    		this.errModal = true;                    		

                    	}

                });

            },
            UpdateIeSample : function ()
            {

                axios.put(`http://localhost:3000/api/ies/${this.codmod}`, this.ie )
                    .then( (response) => {

                    	if ( response.data.resp )
                    	{

                    		this.msgOk = response.data.msg;

                    		this.okModal = true;

							this.msgOk = response.data.msg;	

                    	}
                    	else
                    	{
							
							this.msgError = response.data.msg;	

                    		this.errModal = true;                    		

                    	}

                });

            }

        }
    }


</script>