<div class="month" style="margin-top: 20px;">
  <ul>
    <li style="text-align:center">
    	Periodo: {{$mesAD}}  - {{$anos[1]}}
      <br>
      <span style="font-size:18px" id="anios_festivos">
      	Placa : {{$placa}}
      </span>
    </li>
  </ul>
</div>

<div class="pago" >
   	<p style="   color: black;    font-size: 20px;    text-transform: uppercase;    letter-spacing: 3px;margin-bottom:2px;    text-align:center;">Pago</p>
	<table style="width:100%;border-collapse: collapse; border: none;" border="0" cellspacing="0" cellpadding="0">
	<tr style="width:100%;border: none;">
		<td style="width:22.5%;border: none;"><span style="width:100%;text-align:center;display:block;"><b>Perfil:<br></b> <span>{{$preNombreUser}}</span></span></td>
		<td style="width:22.5%;border: none;"><span style="width:100%;text-align:center;display:block;"><b>Valor Canon:<br></b> <span id="id_canon">
		@if($canonActual != -1)
			${{number_format($canonActual, 2)}}
		@else
			${{number_format($canon, 2)}}
		@endif
		</span></span></td>
		<td style="width:22.5%;border: none;"><span style="width:100%;text-align:center;display:block;"><b>N° Días:<br></b> <span id="num_dias" style="color:red">0</span></span></td>
		<td style="width:22.5%;border: none;"><span style="width:100%;text-align:center;display:block;"><b>Total:<br></b> $<span id="total_valor">0</span></span></td>
		@if($acceso == "W" && $estado_documento == "E1")	
			<td style="width:10%;border: none;"><button class="btn btn-primary  btn-cam-trans btn-sm" onclick="savePlanilla()" ><i class='fa fa-save'></i> Guardar planilla</button></td>
		@endif
	</tr>
</table>
</div>


<ul class="weekdays">
  <li>Lunes</li>
  <li>Martes</li>
  <li>Míercoes</li>
  <li>Jueves</li>
  <li>Viernes</li>
  <li>Sábado</li>
  <li>Domingo</li>
</ul>

<ul class="days" id="dias_mes">

<?php $fin = true;?>
<?php $dia = $dia1;?>
<?php $aux0 = 0;?>

@for ($i=0; $i < $dia1 - 1; $i++)
	<li style="outline:0px;height:0px;"></li>
@endfor

@for ($i=1; $i <=  $ultimoD; $i++) 
	
			@for($j = 0; $j < count($datos); $j++)
				<?php
					if($datos[$j]["dia"] == $i) //Día áctual
					{
						?>
						@if($datos[$j]["prefijoPRY"] != $preUser)
							<li class="background gray">
						@else
							@if($datos[$j]["tipo"] == 1 &&  $datos[$j]["check"] != 0)
								<li class="background sucess">
							@endif

							@if($datos[$j]["tipo"] == 2 )
								<li class="background warning">
							@endif

							@if($datos[$j]["tipo"] == 3 || $datos[$j]["check"] == 0 && $datos[$j]["tipo"] != 2 )

								@if($datos[$j]["exist"] == 0)
									<li class="background sucess">
								@else
									<li class="background danger">
								@endif
								
							@endif
						@endif
							
								<span data-select="0" data-dia="{{$i}}" class="number" >{{$i}}</span>
								<span class="checked">

								@if($datos[$j]["prefijoPRY"] == $preUser)
									<?php $aux0++;?>
									@if($datos[$j]["check"] == 1)	
										@if($acceso == "W" && $datos[$j]["estado"] == "E1")	
											<input class="checkedelemento" data-prefijo='{{$datos[$j]["prefijoPRY"]}}' data-dia='{{$i}}' data-tipo='{{$datos[$j]["tipo"]}}' data-estado='1' onclick="checkDatos(this)"  type="checkbox" checked />
										@else
											<input class="checkedelemento"  disabled checked  type="checkbox" />
										@endif
									@else
										@if($acceso == "W" && $datos[$j]["estado"] == "E1")	
											@if($datos[$j]["exist"] == 0)		
												<input class="checkedelemento" checked data-prefijo='{{$datos[$j]["prefijoPRY"]}}' data-dia='{{$i}}' data-tipo='{{$datos[$j]["tipo"]}}' data-estado='1' onclick="checkDatos(this)"  type="checkbox"  />
											@else
												<input class="checkedelemento" data-prefijo='{{$datos[$j]["prefijoPRY"]}}' data-dia='{{$i}}' data-tipo='{{$datos[$j]["tipo"]}}' data-estado='2' onclick="checkDatos(this)"  type="checkbox"  />
											@endif
										@else
											@if($datos[$j]["exist"] == 0)		
												<input class="checkedelemento" checked  data-prefijo='{{$datos[$j]["prefijoPRY"]}}' data-dia='{{$i}}' data-tipo='{{$datos[$j]["tipo"]}}' data-estado='1' onclick="checkDatos(this)"  type="checkbox"  />
											@else
												<input class="checkedelemento" disabled  type="checkbox"  />
											@endif
										@endif
									@endif
									
								@endif

								</span>
								
								@if($datos[$j]["tipo"] == 1)		
									@if($datos[$j]["check"] == 1)		
										<span class="estado sucess"><span>ACTIVO</span> <img style="width:20px;" src="{{url('/')}}/img/checked.png"></span>
									@else
										@if($datos[$j]["exist"] == 0)
											<span class="estado sucess"><span>ACTIVO</span> <img style="width:20px;" src="{{url('/')}}/img/checked.png"></span>
										@else
											<span class="estado danger"><span>INACTIVO</span> <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png"></span>
										@endif
									@endif
									
								@endif

								@if($datos[$j]["tipo"] == 2)		
									<span class="estado warning"><span>EN TALLER </span>
										<img style="width:20px;" src="{{url('/')}}/img/crane-truck.png"><br>
										<a href="{{url('/')}}/transversal/incidencia/{{$datos[$j]['incidencia']}}" target="_blank">{{strtoupper($datos[$j]["incidencia"])}}</a></span>
								@endif

								<!--
									crane-truck
									exclamation-mark
									checked

								-->
								<span class="contrato"><b>Contrato:</b> {{$datos[$j]["pryNombre"]}}</span>
								@if($datos[$j]["check"] == 1)	
									<span class="planilla"><a href="{{url('/')}}/transporte/costos/arrendamientosdoc/{{$datos[$j]['doc']}}"><b>PL:{{$datos[$j]["doc"]}}</b></a></span>
								@endif
							</li>
						<?php
					}		
				?>
				
			@endfor			

@endfor
</ul>

<script type="text/javascript">
	
	function calculaTotal()
	{
		@if($canonActual != -1)
			var canon = "{{$canonActual}}";
		@else
			var canon = "{{$canon}}";
		@endif

		var listaCkeck = $(".checkedelemento");
		var diaTotal = 0;
		for (var i = 0; i < listaCkeck.length; i++) {
			if(listaCkeck[i].checked)
				diaTotal++;
		};
		document.querySelector("#num_dias").innerHTML = diaTotal;
		document.querySelector("#total_valor").innerHTML = number_format(( (canon / 30) * (diaTotal == 0 ? 1 : diaTotal)),2);

	}
</script>