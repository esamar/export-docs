<template>

	<div class="modal fade" id="modal_ope_user_csv" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl">
	    <div class="modal-content">
	      <div class="modal-header">

	        <h5 class="modal-title" id="staticBackdropLabel">
	        
	        	{{ titleMode }}
	    	
	    	</h5>

	        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" @click="cleanForm">
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
                            Se ha encontrado el siguiente error: {{ msgError }}
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
								
		                        <div :class="'alert d-flex align-items-center mb-1 ' + ( msgPreLoad.info ? 'alert-light': 'alert-warning' ) " role="alert" v-if="contentFile">
		                          <svg :class=" 'bi flex-shrink-0 mr-2 ' + ( msgPreLoad.info ? 'text-info': 'warning' )" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#info-fill"/></svg>
		                          <div>

		                          	<p class="mb-0" v-html="msgPreLoad.msg"></p>

		                          </div>
		                        </div>

		            			<div class="mb-3" style="overflow: auto; width: 100%; height: 300px;">

									<table class="table table-hover small" v-if="mode=='1'">

					                  <thead>
					                    <tr>
					                      <th scope="col" style="text-align: left;width: 80px;">DNI</th>
					                      <th scope="col" style="text-align: left;">Nombres</th>
					                      <th scope="col" style="text-align: left;">Apellido_1</th>
					                      <th scope="col" style="text-align: left;">Apellido_2</th>
					                      <th scope="col" style="text-align: left;">Rol mod 1</th>
					                      <th scope="col" style="text-align: left;">Rol mod 2</th>
					                      <th scope="col" style="text-align: left;">Usuario</th>
					                      <th scope="col" style="text-align: left;">Password</th>
					                      <th scope="col" style="text-align: left;width: 80px;">Estado</th>
					                    </tr>
					                  </thead>
					                  <tbody>
					                    <tr v-for="user in contentFile">

					                      <th scope="row" style="text-align: left;width: 80px;">{{ user.dni }}</th>
					                      <td style="text-align: left;">{{ user.nombres }}</td>
					                      <td style="text-align: left;">{{ user.apellido_1 }}</td>
					                      <td style="text-align: left;">{{ user.apellido_2 }}</td>
					                      <td style="text-align: left;">{{ user.rol_mod1 }}</td>
					                      <td style="text-align: left;">{{ user.rol_mod2 }}</td>
					                      <td style="text-align: left;">{{ user.usuario }}</td>
					                      <td style="text-align: left;">{{ user.password }}</td>
					                      <td style="text-align: left;width: 80px;">

					                      		<span :class="'badge bg-' + ( user.id_persona ? 'primary' : 'danger' ) + ' text-light p-2'" v-if="user.estado_muestra">
					                      			{{ user.id_persona ? 'Correcto' : 'Nuevo'}}
					                      		</span>

					                      		<span class="badge bg-danger text-light p-2" v-if="!user.estado_muestra">
					                      			Ya existe en la muestra
					                      		</span>


					                  	  </td>

					                    </tr>

					                  </tbody>

					                </table>

									<table class="table table-hover small" v-if="mode=='2'">

					                  <thead>
					                    <tr>
					                      <th scope="col" style="text-align: left;width: 80px;">DNI</th>
					                      <th scope="col" style="text-align: left;">Nombres</th>
					                      <th scope="col" style="text-align: left;">Codmod</th>
					                      <th scope="col" style="text-align: left;">Nombre IE</th>
					                      <th scope="col" style="text-align: left;">Nivel</th>
					                      <th scope="col" style="text-align: left;">Estado</th>
					                    </tr>
					                  </thead>
					                  <tbody>
					                    <tr v-for="ie in contentFile">

					                      <th scope="row" style="text-align: left;width: 80px;">{{ ie.dni }}</th>
					                      <td style="text-align: left;">{{ ie.nombres_persona }}</td>
					                      <td style="text-align: left;">{{ ie.codmod }}</td>
					                      <td style="text-align: left;">{{ ie.nombre_ie }}</td>
					                      <td style="text-align: left;">{{ ie.nivel }}</td>

					                      <td style="text-align: left;">
					                      		<span :class="'badge bg-' + ( ie.pertenece_muestra_persona && ie.pertenece_muestra_ie == '1' ? 'primary' : 'danger' ) + ' text-light p-2'">
					                      			{{ ie.pertenece_muestra_persona && ie.pertenece_muestra_ie == '1' ? 'Correcto' : 'No existe'}}
					                      		</span>

					                  	  </td>

					                    </tr>

					                  </tbody>

					                </table>

									<table class="table table-hover small" v-if="mode=='4'">
										<thead>
											<tr>
												<th scope="col" style="text-align: left;width: 80px;">Tipo</th>
												<th scope="col" style="text-align: left;width: 80px;">Número</th>
												<th scope="col" style="text-align: left;">Nombres</th>
												<th scope="col" style="text-align: left;">Apellido_1</th>
												<th scope="col" style="text-align: left;">Apellido_2</th>
												<th scope="col" style="text-align: left;">Email</th>
												<th scope="col" style="text-align: left;">Teléfono</th>
												<th scope="col" style="text-align: left;">Rol</th>
												<th scope="col" style="text-align: left;">Estado</th>
											</tr>
										</thead>
										<tbody>

											<tr v-for="usuario in contentFile">

												<th scope="row" style="text-align: left;width: 80px;">{{ usuario.tipo ? "DNI" : "Otro" }}</th>
												<th style="text-align: left;">{{ usuario.numero }}</th>
												<td style="text-align: left;">{{ usuario.nombres }}</td>
												<td style="text-align: left;">{{ usuario.apellido_1 }}</td>
												<td style="text-align: left;">{{ usuario.apellido_2 }}</td>
												<td style="text-align: left;">{{ usuario.email }}</td>
												<td style="text-align: left;">{{ usuario.telefono }}</td>
												<td style="text-align: left;">{{ usuario.rol }}</td>
												<td style="text-align: left;">
													<span :class="'badge bg-' + ( usuario.existe === 1 ? 'primary' : 'danger' ) + ' text-light p-2'">
														{{ usuario.existe === 1 ? 'Actualizar' : 'Nuevo'}}
													</span>
												</td>

											</tr>

										</tbody>

									</table>

									<table class="table table-hover small" v-if="mode=='5'">
										<thead>
											<tr>
												<th scope="col" style="text-align: left;width: 80px;">Número</th>
												<th scope="col" style="text-align: left;">Nombres Persona</th>
												<th scope="col" style="text-align: left;">Nombre Usuario</th>
												<th scope="col" style="text-align: left;">Password</th>
												<th scope="col" style="text-align: left;">Rol módulo 1</th>
												<th scope="col" style="text-align: left;">Rol módulo 2</th>
												<th scope="col" style="text-align: left;">Estado</th>
											</tr>
										</thead>
										<tbody>

											<tr v-for="usuario in contentFile">

												<th scope="row" style="text-align: left;width: 80px;">{{ usuario.numero }}</th>
												<td style="text-align: left;">{{ usuario.nombres }}</td>
												<td style="text-align: left;">{{ usuario.usuario }}</td>
												<td style="text-align: left;">{{ usuario.password }}</td>
												<td style="text-align: left;">{{ usuario.rol_mod1 }}</td>
												<td style="text-align: left;">{{ usuario.rol_mod2 }}</td>
												<td style="text-align: left;">
													<span :class="'badge bg-' + ( usuario.existe === 1 ? 'primary' : 'danger' ) + ' text-light p-2'">
														{{ usuario.existe === 1 ? 'Actualizar' : 'No existe'}}
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
				payload : {},
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
					case "1": this.titleMode ="Asignar usuarios a la muestra por lotes"; break;
					case "2": this.titleMode ="Asignar carga IE para usuarios por lotes"; break;
					case "3": this.titleMode ="Desasignar carga IE a usuarios por lotes"; break;
					case "4": this.titleMode ="Actualizar datos de persona por lotes"; break;
					case "5": this.titleMode ="Actualizar datos de usuario por lotes"; break;
				}

        	}

        },
        computed:{

        	msgPreLoad : function () 
        	{
        		
        		let msg = "";

        		let info = "";

        		let numOkItem = 0;

				const totalItem = this.contentFile.length;

				switch ( this.mode ) 
				{

					case "1": 

						numOkItem = this.contentFile.filter( x => x.estado_muestra && x.id_persona ).length;

						msg = ( totalItem == numOkItem 

							? `Se agregará ${numOkItem} Usuarios a la muestra actual. Presione el boton <b>"Procesar"</b> para registrar.`
						
							: `Se ha encontrado  <b>${totalItem - numOkItem}</b> usuarios nuevos. Se registrarán y agregarán ${numOkItem} usuarios a la muestra. Presione el boton <b>"Procesar"</b> para continuar.` );

						info = (totalItem == numOkItem);

					break;

					case "2": 
						
						numOkItem = this.contentFile.filter(x=> x.pertenece_muestra_persona && x.pertenece_muestra_ie ).length;
						
						msg = ( totalItem == numOkItem 

							? `Se agregará ${numOkItem} IEs a los usuarios de la lista. Presione el boton <b>"Procesar"</b> para registrar.`
						
							: `Se ha encontrado <b>${totalItem - numOkItem}</b> items que no pertenecen a la muestra. Solo se puede agregar ${numOkItem} IEs a los usuarios de la lista. Presione el boton <b>"Procesar"</b> para ignorar y continuar.` );

						info = (totalItem == numOkItem);

					break;

					case "4": 						
						
						numOkItem = this.contentFile.length;
						
						msg = `Se va ha Registrar/Actualizar ${numOkItem} usuarios de la lista. Presione el boton <b>"Procesar"</b> para continuar.`;

						info = numOkItem; 
					
					break;

					case "5": 
						
						numOkItem = this.contentFile.filter(x=> x.existe ).length;
						
						msg = ( totalItem == numOkItem 

							? `Se actualizará la información de ${numOkItem} usuarios de la lista. Presione el boton <b>"Procesar"</b> para registrar.`
						
							: `Se ha encontrado <b>${totalItem - numOkItem}</b> usuarios que no pertenecen a la muestra. Se actualizará ${numOkItem} usuarios de la lista. Presione el boton <b>"Procesar"</b> para ignorar y continuar.` );

						info = (totalItem == numOkItem);

					break;

				}

        		return { info , msg };
        	}

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

                this.errModal = false;

                document.getElementById("fileInputCsv").value = "";

            },
        	dirFile : function ( e ) 
        	{

        		const file = e.target.files[0];

        		this.nameCsv = file.name;

				if (file.type === 'application/vnd.ms-excel' || file.type === 'text/csv') 
				{

					let reader = new FileReader();
					
					reader.onloadend = () => { 

						var lines = reader.result.split('\n'); 

						switch (this.mode)
						{

							case "1": this.processFileUserToSample( lines ); break;

							case "2": this.processFileIesToUser( lines ); break;

							case "4": this.processFileUpdatePersonalInformation( lines ); break;

							case "5": this.processFileUpdateUsersInformation( lines ); break;
							
						}

					};

					reader.readAsText(file, 'ISO-8859-1');

				} 
				else 
				{
				
					alert('No se soporta este tipo de archivo');
				
				}

        	},
        	processFile : function ()
        	{

				switch (this.mode)
		    	{

		    		case "1": 
						this.addUserToSample();
		    		break;

		    		case "2": 
						this.addIeToUser();
		    		break;

		    		case "4": 
						this.updatePersonalInformation();
		    		break;
					
		    		case "5": 
						this.updateUserInformation();
		    		break;
					
		    	}

        	},
            processFileUserToSample : function ( lines )
            {

		    	let preContent = new Array();

		    	for (var i = 1; i < lines.length; i++) 
		    	{

		    		if ( lines[i] )
		    		{
		    			
		    			const cols = lines[i].trim().split(',');

		    			preContent.push( { 
    										dni : cols[0],
											nombres : cols[1],
											apellido_1 : cols[2],
											apellido_2 : cols[3],
    										rol_mod1 : cols[4],
    										rol_mod2 : cols[5],
    										usuario : cols[6],
    										password : cols[7],

    									});

		    		}

		    	}

                axiosR.get(`/api/users/0?dni=${ preContent.map( x=> x.dni).join(',') }`)
                    .then( (response) => {

                        preContent.forEach( x => {

                        	const data_person = response.data.data.filter( y => y.numero_documento == x.dni )[0];
							
							const estado_muestra = ( typeof data_person == 'undefined' ? true : ( data_person.id_muestra == this.sample ? false : true ) );

                        	this.contentFile.push( { 
                        							'dni' : x.dni,
													'nombres' : ( typeof data_person != 'undefined' ? data_person.nombres : x.nombres ),
													'apellido_1' : ( typeof data_person != 'undefined' ? data_person.apellido_paterno : x.apellido_1 ),
													'apellido_2' : ( typeof data_person != 'undefined' ? data_person.apellido_materno : x.apellido_2 ),
													'rol_mod1' : x.rol_mod1,
													'rol_mod2' : x.rol_mod2,
													'usuario' : x.usuario,
													'password' : x.password,
													'id_persona' : ( typeof data_person != 'undefined' ? true : false ) ,
													estado_muestra
                         						} );

                        });

                    	this.payload = this.contentFile.filter( x => x.estado_muestra );
						console.log(this.payload);
                });

            },
            processFileIesToUser : function ( lines )
            {
            	
		    	let preContent = new Array();

		    	for (var i = 1; i < lines.length; i++) 
		    	{

		    		if ( lines[i] )
		    		{
		    			
		    			const cols = lines[i].trim().split(',');

		    			preContent.push( { 
    										dni : cols[0].trim(),
    										codmod : cols[1].trim(),

    									} );

		    		}

		    	}

                const responseUser = axiosR.get(`/api/users/0?dni=${ preContent.map( x=> x.dni).join(',') }`);
                 
                const responseIe = axiosR.get(`/api/ies/${this.sample}?codmod=${ preContent.map( x=> x.codmod).join(',') }`);

                axios.all( [responseUser , responseIe] )
                	.then( (response) =>{

                        preContent.forEach( x =>{

                        	const data_person = response[0].data.data.filter( y => y.numero_documento == x.dni )[0];
                        	
                        	const data_ie = response[1].data.data.filter( y => y.codmod == x.codmod )[0];

                        	this.contentFile.push( { 

        							'dni' : x.dni,
									'nombres_persona' : ( typeof data_person != 'undefined' ? data_person.apellido_paterno + data_person.apellido_materno + ', ' + data_person.nombres : '' ),
									'pertenece_muestra_persona' : ( typeof data_person != 'undefined' ? ( data_person.id_muestra ? true : false ): false ) ,
									'codmod' : x.codmod,
									'nombre_ie' : ( typeof data_ie != 'undefined' ? data_ie.nombre : '' ) ,
									'nivel' : ( typeof data_ie != 'undefined' ? data_ie.nivel : '' ) ,
									'pertenece_muestra_ie' : ( typeof data_ie != 'undefined' ? ( data_ie.pertenece_muestra ? true : false) : false )

    							 } );

                        });

						this.payload = this.removeDuplicates( this.contentFile
																	.filter( x => x.pertenece_muestra_persona && x.pertenece_muestra_ie )
																	.map( x => { return  { 'dni' : x.dni } } ) , 'dni' );

						this.payload = this.payload.map( x => {

							return {
										'numero_documento' : x.dni,
										'codmod' : this.contentFile.filter( y  => y.dni == x.dni ).map( z => z.codmod ),
									}

						});


                	});

            },
			processFileUpdatePersonalInformation : function ( lines )
			{

		    	let preContent = new Array();

		    	for (var i = 1; i < lines.length; i++) 
		    	{

		    		if ( lines[i] )
		    		{
		    			
		    			const cols = lines[i].trim().split(',');

		    			preContent.push( { 

											tipo : cols[0].trim(),
											numero : cols[1].trim(),
											nombres : cols[2].trim(),
											apellido_1 : cols[3].trim(),
											apellido_2 : cols[4].trim(),
											email : cols[5].trim(),
											telefono : cols[6].trim(),

    									} );

		    		}

		    	}

                axiosR.get(`/api/users/0?dni=${ preContent.map( x=> x.numero).join(',') }`)		
				.then( (response) =>{

                        preContent.forEach( x =>{

                        	const data_person = response.data.data.filter( y => y.numero_documento == x.numero )[0];


                        	console.log(x);
							console.log(data_person);

							this.contentFile.push({ 

        							'tipo' : x.tipo,
        							'numero' : x.numero,
									'nombres' : ( typeof data_person != 'undefined' ? data_person.nombres : x.nombres ),
									'apellido_1' : ( typeof data_person != 'undefined' ? data_person.apellido_paterno : x.apellido_1 ),
									'apellido_2' : ( typeof data_person != 'undefined' ? data_person.apellido_materno : x.apellido_2 ),
									'email' : ( typeof data_person != 'undefined' ? data_person.email : x.email ), 
									'telefono' : ( typeof data_person != 'undefined' ? data_person.telefono : x.telefono ), 
									'existe' : ( typeof data_person != 'undefined' ? ( data_person.numero === x.numero ? 1 : 0 ) : 0 )

    							});

                        });

						this.payload = this.contentFile ;

						console.log(this.payload)

					});
			},
			processFileUpdateUsersInformation : function ( lines )
			{

		    	let preContent = new Array();

		    	for (var i = 1; i < lines.length; i++) 
		    	{

		    		if ( lines[i] )
		    		{
		    			
		    			const cols = lines[i].trim().split(',');

		    			preContent.push( { 

											numero : cols[0].trim(),
											usuario : cols[1].trim(),
											password : cols[2].trim(),
											rol_mod1 : cols[3].trim(),
											rol_mod2 : cols[4].trim(),

    									} );

		    		}

		    	}

                axiosR.get(`/api/users/0?dni=${ preContent.map( x=> x.numero).join(',') }`)		
				.then( (response) =>{

						preContent.forEach( x =>{

                        	const data_user = response.data.data.filter( y => y.numero_documento == x.numero )[0];

							this.contentFile.push({ 

        							'id_persona' : ( typeof data_user != 'undefined' ? data_user.id_persona : ''),
        							'numero' : x.numero,
									'nombres' : ( typeof data_user != 'undefined' ? data_user.nombres : ''),
									'usuario' : x.usuario,
									'password' : x.password,
									'rol_mod1' : x.rol_mod1,
									'rol_mod2' : x.rol_mod2,
									'existe' : ( typeof data_user != 'undefined' ? 1 : 0 )

    							});

                        });

						this.payload = this.removeDuplicates( this.contentFile
																	.filter( x => x.existe )
																	.map( x => { return  { 'numero' : x.numero } } ) , 'numero' );

						this.payload = this.payload.map( x => {

							return {
										'id' : this.contentFile.filter( y  => y.numero == x.numero )[0].id_persona,
										'usuario' : this.contentFile.filter( y  => y.numero == x.numero )[0].usuario,
										'password' : this.contentFile.filter( y  => y.numero == x.numero )[0].password,
										'rol_mod1' : this.contentFile.filter( y  => y.numero == x.numero )[0].rol_mod1,
										'rol_mod2' : this.contentFile.filter( y  => y.numero == x.numero )[0].rol_mod2,
									}

						});

					});
			},

			removeDuplicates: function (originalArray, prop) 
			{

				var newArray = [];

				var lookupObject  = {};

				for(var i in originalArray) {
					lookupObject[originalArray[i][prop]] = originalArray[i];
				}

				for(i in lookupObject) {
					newArray.push(lookupObject[i]);
				}

				return newArray;

			},
            addUserToSample : function ()
            {

                axiosR.post(`/api/users/${this.sample}/setUserToSample`, this.payload )
                    .then( (response) => {

                    	if ( response.data.resp )
                    	{

                    		this.msgOk = response.data.msg;

                    		this.okModal = true;

                    		eventBus.$emit('updateUsers' , true);

                    	}
                    	else
                    	{
							
							this.msgError = response.data.msg;	

                    		this.errModal = true;                    		

                    	}

                });

            },
            addIeToUser : function ()
            {

                axiosR.post(`/api/users/${this.sample}/setIeToUser`, this.payload )
                    .then( (response) => {

                    	if ( response.data.resp )
                    	{

                    		this.msgOk = response.data.msg;

                    		this.okModal = true;

                    		eventBus.$emit('updateUsers' , true);

                    	}
                    	else
                    	{
							
							this.msgError = response.data.msg;	

                    		this.errModal = true;                    		

                    	}

                })
                .catch( err => {

                	console.log(err);

                });

            },
			updatePersonalInformation : function ()
			{

				axiosR.post(`/api/users/${this.sample}/addUpdatePersonalInfo`, this.payload )
                    .then( (response) => {

						if ( response.data.resp )
                    	{

                    		this.msgOk = response.data.msg;

                    		this.okModal = true;

                    		eventBus.$emit('updateUsers' , true);

                    	}
                    	else
                    	{
							
							this.msgError = response.data.msg;	

                    		this.errModal = true;                    		

                    	}

                })
                .catch( err => {

                	console.log(err);

                });

			},
			updateUserInformation : function ()
			{

				axiosR.post(`/api/users/${this.sample}/addUpdateUserInfo`, this.payload )
                    .then( (response) => {
debugger;
						if ( response.data.resp )
                    	{

                    		this.msgOk = response.data.msg;

                    		this.okModal = true;

                    		eventBus.$emit('updateUsers' , true);

                    	}
                    	else
                    	{
							
							this.msgError = response.data.msg;	

                    		this.errModal = true;                    		

                    	}

                })
                .catch( err => {

                	console.log(err);

                });

			}
        }
    }


</script>