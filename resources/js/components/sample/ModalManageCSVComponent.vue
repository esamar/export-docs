<template>

	<div class="modal fade" id="modal_ope_csv" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl">
	    <div class="modal-content">
	      <div class="modal-header">

	        <h5 class="modal-title" id="staticBackdropLabel">
	        
	        	{{ titleMode }}
	    	
	    	</h5>

	        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	      </div>

			<div class="modal-body">

				<div class="row">

				    <div class="col text-left">
				        
				        <p class="card-text">Seleccione el archivo para iniciar la acción</p>

                        <div class="alert alert-success d-flex align-items-center" role="alert" v-if="okModal">
                          <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                          <div>
                           	{{ msgOk }}
                          </div>
                        </div>

                        <div class="alert alert-danger d-flex align-items-center" role="alert" v-if="errModal">
                          <svg class="bi flex-shrink-0 mr-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                          <div>
                             {{ msgError }}
                          </div>
                        </div>

				        <div class="card" >

				          <div class="card-header">
				            
				            <div class="input-group input-group-sm">

				                <div class="input-group-prepend">
				                    <span class="input-group-text">
				                        <svg class="bi mr-2" width="16" height="16"><use xlink:href="#bi-file-earmark-text-fill"/></svg>
				                        <b>.csv</b>
				                        <span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true" v-if="processing"></span>
				                    </span>
				                </div>
				                
				                <input type="text" class="form-control font-weight-bold" aria-label="Nombre de muestra" placeholder="Nombre de archivo" aria-describedby="button-addon2" v-model="nameCsv" style="background: white;" readonly>

				                <div class="input-group-append">
				                    
				                    <button class="btn btn-secondary" type="submit" id="" onclick="fileInputCsv.click()" :disabled="okModal">
				                        Seleccionar
				                    </button>
  									
  									<input class="form-control form-control-sm" id="fileInputCsv" type="file" @change="dirFile" hidden >

				                </div>

				            </div>

				          </div>
							
							<div :hidden="!contentFile.length">
								
		                        <div class="alert alert-light d-flex align-items-center mb-1" role="alert" v-if="contentFile">
		                          <svg class="bi flex-shrink-0 mr-2 text-info" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#info-fill"/></svg>
		                          <div>
		                            Se va a agregar {{ contentFile.length }} a la muestra actual. Presione el boton <b>"Procesar"</b> para continuar.
		                          </div>
		                        </div>

		            			<div class="mb-3" style="overflow: auto; width: 100%; height: 300px;">

									<table class="table table-hover small" >
					                  <thead>
					                    <tr>
					                      <th scope="col" style="text-align: left;width: 130px;">Cod mod</th>
					                      <th scope="col" style="text-align: left;">Nombre</th>
					                      <th scope="col" style="text-align: left;">Nivel</th>
					                    </tr>
					                  </thead>
					                  <tbody>
					                    <tr v-for="ie in contentFile">

					                      <th scope="row" style="text-align: left;width: 130px;">{{ie}}</th>
					                      <td style="text-align: left;"></td>
					                      <td style="text-align: left;"></td>

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

	        <!-- <button v-if="mode==1" type="button" class="btn btn-primary" @click="addIeSample" :disabled ="!addBotton">Agregar</button> -->

	        <button type="button" class="btn btn-primary" @click="processFile" :disabled = "!contentFile.length || okModal">Procesar</button>

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
            	titleMode : "",
            	nameCsv : "",
            	otro : "",
            	contentFile : [],
            	processing : false,
            	msgOk : "",
				okModal : false,
				msgError :"",
				errModal : false,
            };

        },
        props:{

            sample : String,
            mode : String,

        },
        watch:
        {

        	mode: function ()
        	{
				
				switch ( this.mode ) 
				{
					case "1": this.titleMode ="Seleccionar IEs por archivo CSV"; break;
					case "2": this.titleMode ="Actualizar datos de IEs por archivo CSV"; break;
					case "3": this.titleMode ="Retirar IEs por archivo CSV"; break;
					case "4": this.titleMode ="Descargar CSV de las IEs"; break;
				}


        	}

        },
        mounted(){

        },
        methods: 
        {

            cleanForm : function ()
            {

                this.nameCsv = "";

                this.contentFile = [];
                
                this.okModal = false;
                
                this.msgError = "";
                
                this.msgOk = "";

                document.getElementById("fileInputCsv").value = "";

            },

        	dirFile : function ( e ) {
        		
        		const file = e.target.files[0];

        		this.nameCsv = file.name;
        		
			  if (file.type === 'application/vnd.ms-excel' || file.type === 'text/csv') 
			  {

			    let reader = new FileReader();
			    
			    reader.onloadend = () => { 

			    	var lineas = reader.result.split('\n'); 

			    	for (var i = 1; i < lineas.length; i++) 
			    	{

			    		if ( lineas[i] )
			    		{

			    			this.contentFile.push( lineas[i].trim() );

			    		}

			    	}

			    };

			    reader.readAsText(file, 'ISO-8859-1');

			  } 
			  else 
			  {
			  
			    alert('No se soporta este tipo de archivo');
			  
			  }


        	},

        	processFile : function (  )
        	{
				
				this.addIeSample();

        	},

            addIeSample : function ()
            {

                axios.post(`http://localhost:3000/api/ies/${this.sample}/setMuestra`, this.contentFile )
                    .then( (response) => {

                    	if ( response.data.resp )
                    	{

                    		this.msgOk = response.data.msg;

                    		this.okModal = true;

                    		eventBus.$emit('updateDescribe' , true);

                    	}
                    	else
                    	{
							
							if ( response.data.data )
							{

								this.msgError = `${response.data.msg}. Los siguientes Códigos Modulares no existes [${response.data.data.join()}]`  ;	
							}
							else
							{
								
								this.msgError = response.data.msg;	

							}

							console.log(response.data)
                    		this.errModal = true;                    		

                    	}

                });

            },



        }
    }


</script>