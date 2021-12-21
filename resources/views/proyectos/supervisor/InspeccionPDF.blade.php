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
	th, td {
	  border: black 1px solid;
	}
	table{
		border-collapse: collapse;
	}
	*{
		text-align: left;
	}
</style>
<head>
	<title>PDF_INSPECCION</title>
</head>
<body>
	<!--encabezado principal con imagen-->
	<div>
		<img  src="{{ public_path('img/engie.png')}}" width="139px;" height="43.5px;" style="position: absolute; border: 1px solid black; left: 0.6%; top: -0.5px;">
	</div>
	<table class="head1" style="font-size: 10px;">
		<tr><th style="text-align: center;">SISTEMA INTEGRADO DE GESTIÓN </th></tr>
		<tr><th style="text-align: center;">INSPECCIÓN AMBIENTAL EN TERRENO</th></tr>
		<tr>
			<table class="headB" style="font-size: 10px;">
				<tr>
					<th style="text-align: center;">CÓDIGO: SGA-PL-01-RG-03</th>
					<th style="text-align: center;">VERSIÓN: 5</th>
					<th style="text-align: center;">FECHA: 2020-08-03</th>
				</tr>
			</table>
		</tr>
	</table>
	<br>
	<!--encabezado secundario con datos-->
	<table style="width: 100%; font-size: 8px; max-height: 200px; min-height: 199px; overflow: hidden; ">
		@foreach($form as $key => $valor2)

			<?php 
				if($valor2->descrip_pregunta=='Dirección'){$direccion=$valor2->respuesta;}
				if($valor2->descrip_pregunta=='Trabajo a realizar'){$trabajo=$valor2->respuesta;}
				if($valor2->descrip_pregunta=='Proceso'){$proceso=$valor2->respuesta;}
				if($valor2->descrip_pregunta=='Descargo'){$descargo=$valor2->respuesta;}
				if($valor2->descrip_pregunta=='Tipo vehiculo'){$tipo=$valor2->respuesta;}
				if($valor2->descrip_pregunta=='Placa Vehiculo'){$placa=$valor2->respuesta;}
			?>

		@endforeach
		<tr>
			<td style="width: 50%; height: 10px; background: #DCE4E7;">FECHA DE DILIGENCIAMIENTO:</td>
			<td style="width: 55%; height: 10px;">{{$inspeccion->fecha_servidor ?? ''}}</td>
			<td style="width: 40%; height: 10px; background: #DCE4E7;">PROYECTO / PROCESO</td>
			<td style="width: 55%; height: 10px;">{{$inspeccion->proyecto ?? ''}}      </td>
		</tr>
		<tr>
			<td style="height: 10px; background: #DCE4E7;">NOMBRE DE SUPERVISOR</td>
			<td style="height: 10px;"> {{$inspeccion->apellidosS ?? ''}} {{$inspeccion->nombres ?? ''}}    </td>
			<td style="height: 10px; background: #DCE4E7;">DIRECCIÓN</td>
			<td style="height: 10px;">{{$direccion ?? ''}}</td>
		</tr>
		<tr>
			<td style="height: 10px; background: #DCE4E7;">DOCUMENTO DE IDENTIDAD SUPERVISOR</td>
			<td style="height: 10px;">{{$inspeccion->supervisor ?? ''}}</td>
			<td style="height: 10px; background: #DCE4E7;">ACTIVIDAD A REALIZAR</td>
			<td style="height: 10px;">{{$trabajo ?? ''}}</td>
		</tr>
		<tr>
			<td style="height: 10px; background: #DCE4E7;">CARGO SUPERVISOR</td>
			<td style="height: 10px;"> {{$inspeccion->nombre ?? ''}}    </td>
			<td style="height: 10px; background: #DCE4E7;">DESCARGO/INCIDENCIA/OT N°</td>
			<td style="height: 10px;">{{$descargo ?? ''}}</td>
		</tr>
		<tr>
			<td style="height: 10px; background: #DCE4E7;">NOMBRE RESPONSABLE DE CUADRILLA</td>
			<td style="height: 10px;">{{$inspeccion->nombreL ?? ''}}   {{$inspeccion->apellidosL ?? ''}}</td>
			<td style="height: 10px; background: #DCE4E7;">TIPO DE VEHÍCULO</td>
			<td style="height: 10px;">{{$tipo ?? ''}}</td>
		</tr>
		<tr>
			<td style="height: 10px; background: #DCE4E7;">DOCUMENTO DE IDENTIDAD RESPONSABLE</td>
			<td style="height: 10px;">{{$inspeccion->lider ?? ''}}</td>
			<td style="height: 10px; background: #DCE4E7;">PLACA DE VEHÍCULO</td>
			<td style="height: 10px;">{{$placa ?? ''}}</td>
		</tr>
		<tr>
			<td style="height: 10px; background: #DCE4E7;">CARGO RESPONSABLE</td>
			<td style="height: 10px;">{{$inspeccion->cargoL ?? ''}}    </td>
			<td style="height: 10px; background: #DCE4E7;">HORA INICIAL  / HORA FINAL</td>
			<td style="height: 10px;">_</td>
		</tr>
	</table>
	<br>
	<!--tabla con respuestas completa-->
	<div style="position: relative; height: 75%;" >

		<div style="position: relative;">
			<table>
				<tr>
					<th style="border:none;">
						<table >
							<tr style="background: #D7D7D7;">
								<th style="width: 90%;">
									<table width="100%;">
										<tr >
											<th style="border: none; border-right: 1px solid black; font-size: 12px; text-align: center; width: 15%;">ITEM</th>
											<th style="border: none; border-right: 1px solid black; font-size: 12px; text-align: center; width: 67%;">DESCRIPCIÓN</th>
											<th style="border: none; font-size: 12px; text-align: center; width: 21%;">CUMPLE</th>
										</tr>
										<tr style="background: #DCE4E7;">
											<th style=" border: none; border-right: 1px solid black; border-top: 1px solid black;  font-size: 8px; ">1</th>
											<th style="border: none; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px; text-align: center;">GESTIÓN DE ARBOLADO Y MANEJO DE RESIDUOS VEGETALES</th>
											<th style="border: none; border-top: 1px solid black;">
												<table style="width: 103%;">
													<tr>
														<th style="width: 62%; height: 20px; border:none; border-right: 1px solid black; text-align: center; font-size: 10px;">SI</th>
														<th style=" border: none; text-align: center; font-size: 10px;">NO</th>
													</tr>
												</table>
											</th>
										</tr>
									</table>
								</th>
								<th style="width: 10%; text-align: center; font-size: 10px; width: 7%;">N/A</th>
							</tr>
						</table>						
					</th>
					<th style="border:none;">
						<table style=" background: #D7D7D7;">
							<tr>
								<th style="width: 90%;">
									<table width="100%;">
										<tr>
											<th style="border: none; border-right: 1px solid black; font-size: 12px; text-align: center; width: 15%;">ITEM</th>
											<th style="border: none; border-right: 1px solid black; font-size: 12px; text-align: center; width: 67%;">DESCRIPCIÓN</th>
											<th style="border: none; font-size: 12px; text-align: center; width: 21%;">CUMPLE</th>
										</tr>
										<tr style="background: #DCE4E7;">
											<th style="border: none; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px; ">3</th>
											<th style="border: none; border-right: 1px solid black; border-top: 1px solid black; font-size: 8px; text-align: center;">GESTIÓN DE ARBOLADO Y MANEJO DE RESIDUOS VEGETALES</th>
											<th style="border: none; border-top: 1px solid black;">
												<table style="width: 103%;">
													<tr>
														<th style="width: 62%; height: 20px; border:none; border-right: 1px solid black; text-align: center; font-size: 10px;">SI</th>
														<th style=" border: none; text-align: center; font-size: 10px;">NO</th>
													</tr>
												</table>
											</th>
										</tr>
									</table>
								</th>
								<th style="width: 10%; text-align: center; font-size: 10px; width: 7%;">N/A</th>
							</tr>
						</table>						
					</th>
				</tr>
			</table>
		</div>
			
		<div style="position: relative;">
			<table>
				<tr>
					<th style="border: none;">
						<table style="position: relative; top: 0.5%; width: 96%;">
							<?php $c=0;?>

							@foreach($formCreacion as $key => $valor1)

									@if($valor1->tipo_control=='11' || $valor1->tipo_control=='19')

										@if($c<24)

											<tr style="font-size: 8px;">
												<th style="font-size: 9px; width: 47px; background: #DCE4E7;">{{$valor1->item_num}}</th>
												<th style="font-size: 9px; text-align: left; width:210px; background: #DCE4E7;">{{$valor1->descrip_pregunta}}</th>
												<th style="text-align: center; background: #DCE4E7;"></th>
												<th style="background: #DCE4E7;"></th>
												<th style="background: #DCE4E7;"></th>
											</tr>

										@endif

										<?php $c++;?>

										@foreach($form as $key => $valor)

												@if($valor->tipo_control !='3' && $valor->tipo_control!='13')

													@if(intval($valor->item_num)==$valor1->item_num)

														@if($c<24)

															<tr style="font-size: 8px;">
																		<th style="font-size: 8px; width: 47px;">{{$valor->item_num}}</th>
																		<th style="font-size: 8px; text-align: left; width:210px;">{{$valor->descrip_pregunta}}</th>

																	@if($valor->respuesta == 2)
																		<th style="text-align: center;">X</th>
																		<th></th>
																		<th></th>
																	@endif

																	@if($valor->respuesta == 1)
																		<th></th>
																		<th style="text-align: center;">X</th>
																		<th></th>

																	@endif
																	@if($valor->respuesta == 0)
																		<th></th>
																		<th></th>
																		<th style="width: 20px; text-align: center;">X</th>
																	@endif
															</tr>

														@endif
														
														<?php $c++;?>

													@endif

												@endif

										@endforeach

									@endif
								
							@endforeach
						</table>						
					</th>
					<th style="border: none;">
						<table style="position: absolute; top: 0.6%; width: 103%; left: 105.5%;">
							<?php $c=0;?>

							@foreach($formCreacion as $key => $valor1)

									@if($valor1->tipo_control=='11' || $valor1->tipo_control=='19')

										@if($c>23)

											<tr style="font-size: 8px;">
												<th style="font-size: 9px; width: 47px; background: #DCE4E7;">{{$valor1->item_num}}</th>
												<th style="font-size: 9px; text-align: left; width:210px; background: #DCE4E7;">{{$valor1->descrip_pregunta}}</th>
												<th style="text-align: center; background: #DCE4E7;"></th>
												<th style="background: #DCE4E7;"></th>
												<th style="background: #DCE4E7;"></th>
											</tr>

										@endif

										<?php $c++;?>

										@foreach($form as $key => $valor)

												@if($valor->tipo_control !='3' && $valor->tipo_control!='13')

													@if(intval($valor->item_num)==$valor1->item_num)

														@if($c>23)

															<tr style="font-size: 8px;">
																		<th style="font-size: 8px; width: 47px;">{{$valor->item_num}}</th>
																		<th style="font-size: 8px; text-align: left; width:210px;">{{$valor->descrip_pregunta}}</th>

																	@if($valor->respuesta == 2)
																		<th style="text-align: center;">X</th>
																		<th></th>
																		<th></th>
																	@endif

																	@if($valor->respuesta == 1)
																		<th></th>
																		<th style="text-align: center;">X</th>
																		<th></th>

																	@endif
																	@if($valor->respuesta == 0)
																		<th></th>
																		<th></th>
																		<th style="width: 20px; text-align: center;">X</th>
																	@endif
															</tr>

														@endif
														
														<?php $c++;?>

													@endif

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

	<center>
		<div style="position: relative; left: 20%; padding-top:50px;">
			<table style="width: 60%;">
				<tr style="width: 100%;">
					<th>_</th>
				</tr>
				<tr>
					<table style="width: 60%; font-size: 10px;">
						<tr>
							<th width="30%;">
								<table width="100%;" style="height: 100px; ">
									<tr>
										@if($inspeccion->resultado=='NC')
										<th height="2%" style="border-right: none; border-left: none; border-top: none; background: red;">No Conforme</th>
										@else
										<th height="2%" style="border-right: none; border-left: none; border-top: none;">No Conforme</th>
										@endif
									</tr>
									<tr>
										<th height="6%;" style="border-right: none; border-left: none;"><img  src="{{ public_path('img/mundo1.png')}}" width="120px;" height="100px;"></th>
									</tr>
									<tr>
										<th height="2%;" style="border-bottom: none; border-left: none; border-right: none;">Ayuda a nuestro planeta</th>
									</tr>
								</table>
							</th>
							<th width="40%;">_</th>
							<th width="30%;">
								<table width="100%;" style="height: 100px;">
									<tr>
										@if($inspeccion->resultado=='C')
										<th height="2%" style="border-right: none; border-left: none; border-top: none; background: green;">Conforme</th>
										@else
										<th height="2%" style="border-right: none; border-left: none; border-top: none;">Conforme</th>
										@endif
									</tr>
									<tr>
										<th height="6%;" style="border-right: none; border-left: none;"><img  src="{{ public_path('img/mundo2.png')}}" width="100px;" height="100px;"></th>
									</tr>
									<tr>
										<th height="2%;" style="border-right: none; border-left: none; border-bottom: none;">Gracias por tu compromiso con el planeta</th>
									</tr>
								</table>
							</th>
						</tr>
					</table>
				</tr>
			</table>
		</div>
	</center>
	<table style="width: 100%; text-align: left; font-size: 10px;">
		<tr><th style="background: #DCE4E7;">OBSERVACIONES</th></tr>
		<tr>
			<th style="font-size: 12px; height: 10px;">{{$inspeccion->observacion}}</th>
		</tr>
		<tr>
			<th style="font-size: 14px; height: 10px;"></th>
		</tr>
		<tr>
			<th style="font-size: 14px; height: 10px;"></th>
		</tr>

	</table>

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
	                $base642 = '';
	            }
           	}

        ?>
		<?php
			if (isset($firmaSupervisor[0])) {
				$ruta = $firmaSupervisor[0]->direccion;
				$tipo = substr($ruta , -3);
	            if ($tipo == 'peg' or $tipo == 'jpg'){
	                $tipo = 'jpeg';
	            }
	            $client = new \GuzzleHttp\Client();
	            try{
	                $res = $client->get($ruta);
	                $content = (string) $res->getBody();
	                $base64 = base64_encode($content);
	            }catch (Exception $e) {
	                $base64 = '';
	            }
	       	}

	    ?>

	<table width="100%;" style="font-size: 10px;">
		<tr>
			<th height="50px;" style="width: 50%;">INSPECCIÓN REALIZADA POR: {{$inspeccion->apellidosS}} {{$inspeccion->nombres}}</th>
			<th>LIDER DE CUADRILLA: {{$inspeccion->nombreL}}   {{$inspeccion->apellidosL}}</th>
		</tr>
		<tr>
			<th height="50px; width50%;">
				FIRMA NOMBRE Y C.C.
				@if(isset($firmaSupervisor[0]))
				<img src="data:image/<?php echo $tipo; ?>;base64,<?php echo $base64; ?>" style="width:150px; height:50px; position: relative; margin-top: 10px">
				@else
				<p style="color: #c4c4c4;">No hay firma.</p>
				@endif
			</th>
			<th>
				FIRMA NOMBRE Y C.C.

				@if(isset($firmaLider[0]))
				<img src="data:image/<?php echo $tipo2; ?>;base64,<?php echo $base642; ?>" style="width:150px; height:50px; position: relative; margin-top: 10px">
				@else
				<p style="color: #c4c4c4;">No hay firma.</p>
				@endif
			</th>
		</tr>
	</table>

</body>