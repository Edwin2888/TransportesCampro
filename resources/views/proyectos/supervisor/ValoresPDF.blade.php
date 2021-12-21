<style type="text/css">
	.headB{
		width: 80%;
		position: relative;
		left: 20%;
		font-size: 14px;
	}

	.head1{
		width: 80%;
		position: relative;
		left: 20%;
		font-size: 14px;
	}
	th {
	  border: black 1px solid;
	}
	table{
		border-collapse: collapse;
	}
	*{
		font-size: 9px;
	}
</style>
<head>
	<title>PDF_OBSERVACION</title>
</head>
<body>
	<div>
		<img  src="{{ public_path('img/engie.png')}}" width="140px;" height="40px;" style="position: absolute; border: 1px solid black; left: 0.6%;">
	</div>
	@foreach($form as $key => $valor)
		<?php 
			if($valor->id_pregunta == '15'){
				$rta_aspectos=$valor->respuesta;
			}
			if($valor->id_pregunta == '16'){
				$rta_oportunidades=$valor->respuesta;
			}	
			if($valor->id_pregunta == '1'){
				$rta_labor=$valor->respuesta;
			}											
		?>
	@endforeach
	<table class="head1">
		<tr><th>EXCELENCIA OPERACIONAL</th></tr>
		<tr><th>INSPECCCIÓN NUESTROS VALORES EN TERRENO</th></tr>
		<tr>
			<table class="headB">
				<tr>
					<th>CODIGO EOP-IN-01-RG-03</th>
					<th>VERSIÓN: 2</th>
					<th>FECHA: 2020-04-13</th>
				</tr>
			</table>
		</tr>
	</table>
	<br>
	<table style="width: 100%; text-align: left;">
	<?php $fecha_servidor = date_create($inspeccion->fecha_servidor); ?>
		<tr><th style="border: none; text-align: left;">Fecha: {{date_format($fecha_servidor, 'Y-m-d') ?? ''}}</th>
			<th style="border: none; text-align: left;">Hora: {{date_format($fecha_servidor, 'H:i:s') ?? ''}}</th>
			<th width="30%" style="border: none; text-align: left;"></th>
		</tr>
		<tr>
			<th style="border: none; height: 30px; text-align: left;">Contrato: <p style=" font-size: 8px; position: absolute; width: 100px; top:0px;">{{$inspeccion->proyecto ?? ''}}</p> </th>
			<th style="border: none; height: 30px; text-align: left;">Proceso: {{$inspeccion->subproyecto ?? ''}}</th>
			<th style="border: none; height: 30px; text-align: left;">Ciudad:  {{$inspeccion->ciudad ?? ''}}</th>
		</tr>
	</table>

	<br>
	<table style="width: 100%; text-align: left;">
		<tr><th style="background: #DCE4E7;">OBJETIVO DEL ACOMPAÑAMIENTO</th></tr>
		<tr>
			<th style="font-size: 12px;">Conseguir acercamiento y mejor entendimiento de la operación, conocer las  actividades, dificultades y desafíos del proceso/personal, para reforzar los aspectos de seguridad, cultura de autocuidado, excelencia operacional y desarrollo individual. </th>
		</tr>
	</table>
	<br>
	<table style="width: 100%; text-align: left;">
		<tr><th style="background: #DCE4E7;">LABOR Y/O TAREA OBSERVADA</th></tr>
		<tr>
			<th style="font-size: 12px; height: 10px; text-align: left;">{{$rta_labor ?? ''}}</th>
		</tr>
		<tr>
			<th style="font-size: 14px; height: 10px;"></th>
		</tr>
		<tr>
			<th style="font-size: 14px; height: 10px;"></th>
		</tr>

	</table>
	<br>
	<div style="position: relative;">
		<table style="width: 100%; position: relative;">
			<tr><th style="background: #DCE4E7;">Aspectos a evaluar</th></tr>
		</table>
		<br><br>
		<br><br>
		<table style="width: 100%; text-align: left; position: relative; top: -5%;">
			<tr>
				<th>
					<table width="100%;">
					<?php $c=0;?>
					<?php $item=0;?>
							@foreach($formCreacion as $key => $valor1)

								@if($valor1->tipo_control=='11')
										<?php $item++;?>
										<tr >
											<th style="background: #DCE4E7;">{{$valor1->descrip_pregunta}}</th>
											<th style="background: #DCE4E7; ">SI</th>
											<th style="background: #DCE4E7;">NO</th>
											<th style="background: #DCE4E7;">N/D</th>
											<th style="background: #DCE4E7;">OBSERVACIONES</th>
										</tr>
										
									@foreach($form as $key => $valor)

										@if(intval($valor->item_num)==$item)
										<tr>
											<th height="20px" style=" border:none; text-align: left; border-bottom: 1px solid black;">{{$valor->item_num}}   {{$valor->descrip_pregunta}}</th>
											
											@if($valor->respuesta == 2)
											<th height="19px">X</th>
											<th height="19px"></th>
											<th height="19px"></th>
											@endif

											@if($valor->respuesta == 1)
											<th height="19px"></th>
											<th height="19px">X</th>
											<th height="19px"></th>
											@endif
											@if($valor->respuesta == 0)
											<th height="19px"></th>
											<th height="19px"></th>
											<th height="19px">X</th>
											@endif
											<th style="border: none; border-bottom: 1px solid black;">{{$valor->texto_extra}}</th>
										</tr>		
											<?php $c++;?>

										@endif

									@endforeach

								@endif
							
						@endforeach			
					</table>				
				</th>
			</tr>
		</table>
	</div>
	
	</div>


	<table style="width: 100%; text-align: left; top: 10%;">
		<tr><th style="background: #DCE4E7;">2. ASPECTOS POSITIVOS</th></tr>
		<tr>
			<th style="font-size: 12px; height: 10px; text-align: left;">{{$rta_aspectos ?? ''}}</th>
		</tr>
		<tr>
			<th style="font-size: 14px; height: 10px;"></th>
		</tr>
		<tr>
			<th style="font-size: 14px; height: 10px;"></th>
		</tr>
	</table>
	<br>
	<table style="width: 100%;">
		<tr><th style="background: #DCE4E7;">3. OPORTUNIDADES DE MEJORA</th></tr>
		<tr>
			<th style="font-size: 12px; height: 10px; text-align: left;">{{$rta_oportunidades ?? ''}}</th>
		</tr>
		<tr>
			<th style="font-size: 14px; height: 10px;"></th>
		</tr>
		<tr>
			<th style="font-size: 14px; height: 10px;"></th>
		</tr>
	</table>

	<table  style="margin-top: 10px; width: 100%; position: relative; top: 	00px;">
		<tr>
			<th style="background: #DCE4E7; width: 50%;">OBSERVADOR:</th>
			<th style="background: #DCE4E7; width: 50%;">LIDER DEL GRUPO TECNICO</th>
		</tr>
		<tr>
			<th>
				<table style="width: 100%;">
					<tr>
						<th  style="width: 20%;">NOMBRE:</th>
						<th></th>
					</tr>
					<tr>
						<th>CARGO:</th>
						<th ></th>
					</tr>
					<tr>
						<th height="40px;">FIRMA:</th>
						<th height="60px;" >
						</th>
					</tr>
				</table>
			</th>
			<th>
				<table style="width: 100%;">
					<tr>
						<th>NOMBRE:</th>
						<th >{{$inspeccion->nombreL}} {{$inspeccion->apellidosL}} {{$inspeccion->lider}}</th>
					</tr>
					<tr>
						<th>CARGO:</th>
						<th >{{$inspeccion->cargoL}}</th>
					</tr>
			        <?php
						if (isset($firmaLider[0])) {
							$ruta2 = $firmaLider[0]->direccion;
							$tipo2 = substr($ruta2 , -3);
				            if ($tipo2 == 'peg' or $tipo2 == 'jpg'){
				                $tipo2 = 'jpeg';
				            }
				            $client = new \GuzzleHttp\Client();
				            try{
				                $res = $client->get($ruta2);
				                $content = (string) $res->getBody();
				                $base642 = base64_encode($content);
				            }catch (Exception $e) {
				                $content = '';
				            }
			           	}
			        ?>
			        
					<tr>
						<th height="40px;">FIRMA: 		
						</th>
						<th height="40px;" >							
							@if(isset($firmaLider[0]))
							<img src="data:image/<?php echo $tipo2; ?>;base64,<?php echo $base642; ?>" style="width:150px; height:50px; position: relative; margin-top: 10px">
							@else
							<p style="color: #c4c4c4;">No hay firma.</p>
							@endif</th>
					</tr>
				</table>
			</th>
		</tr>
	</table>
</body>