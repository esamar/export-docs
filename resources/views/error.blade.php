<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>

    </head>
    <body>

    	<?php if ( $state_error ): ?>

	    	<div class="alert alert-danger" role="alert">

	            <?= $message ?>

			</div>

		<?php else: ?>

			<div class="alert alert-info" role="alert">

	            <?= $message ?>

			</div>

			<div>
				<button> Continuar </button>
			</div><br>

		<?php endif; ?>


    	<table class="table table-sm table-hover">
    		<thead>
    			<tr>	
	    			<th scope="col">FILA </th>
	    			<th scope="col">COD_MONITOR</th>
	    			<th scope="col">COD_MOD8</th>
	    			<th scope="col">DNI</th>
	    			<th scope="col">APELLIDO PATERNO</th>
	    			<th scope="col">APELLIDO MATERNO</th>
	    			<th scope="col">NOMBRES</th>
	    			<th scope="col">EMAIL_DIRECTOR</th>
	    			<th scope="col">TELEFONO 1</th>
	    			<th scope="col">TELEFONO 2</th>
	    			<th scope="col">RESUMEN</th>
    			</tr>
    		</thead>
        
        	<tbody>
        	
		        <?= $resumeTable?>
        
        	</tbody>

    	</table>

    </body>
</html>