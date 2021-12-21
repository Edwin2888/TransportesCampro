
<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
	<i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

<a href="../../transversal/reporte/cobertura" class="btn btn-primary btn-cam-trans btn-sm">
<i class="fa fa-file-o" aria-hidden="true"></i>  Cobertura de inspecciones de seguridad</a>

<a href="../../inasistencias" class="btn btn-primary btn-cam-trans btn-sm">
<i class="fa fa-file-o" aria-hidden="true"></i>  Reportes de inasistencias</a>

<a href="../../inspeccionOrdenesReportes" class="btn btn-primary btn-cam-trans btn-sm">
<i class="fa fa-file-o" aria-hidden="true"></i>  Reportes</a>






<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

	<div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
	<div class="row">
	
{!! Form::open(['url' => 'consultaFiltroSupervisor', "method" => "POST"]) !!}

		<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="id_orden">Proyecto:</label>
					<select name="id_proyecto" id="id_proyecto" class="form-control" style="max-width:250px;padding:0px;">
						<option value="">Todos</option>
						@foreach($proyectos as $key => $valor)
							@if(Session::get('proyecto') == $valor->prefijo_db)
								<option value="{{$valor->prefijo_db}}" selected>{{$valor->proyecto}}</option>
							@else
								<option value="{{$valor->prefijo_db}}">{{$valor->proyecto}}</option>
							@endif
						@endforeach
					</select>
				</div>
		</div>

		<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="id_proyect">Desde:</label>
					<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
						 style="    width: 100%;">
						@if(Session::has('fecha_inicio'))
							<?php
								if(Session::get('fecha_inicio') != "")
								{
									$fechaIni = Session::get('fecha_inicio');
								}
								else
								{
									$fechaIni = $fecha2;
								}
								
							?>
							<input class="form-control" size="16" style="height:30px;" type="text"
							   value="{{$fechaIni}}" name="fecha_inicio" id="fecha_inicio"
							   placeholder="dd/mm/aaaa" >
						@else
							<input class="form-control" size="16" style="height:30px;" type="text"
							   value="{{$fecha2}}" name="fecha_inicio" id="fecha_inicio"
							   placeholder="dd/mm/aaaa" >
						@endif
						<span class="input-group-addon"><i class="fa fa-times"></i></span>
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
					</div>
				</div>
		</div>

		<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="text_nombre_proyect">Hasta:</label>
					<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="    width: 100%;">
						@if(Session::has("fecha_corte"))
							<?php
								 if(Session::get('fecha_corte') != "")
								{
									$fechaFin = Session::get('fecha_corte');
								}
								else
								{
									$fechaFin = $fecha1;
								}
							?>
							<input class="form-control" size="16" style="height:30px;" type="text"
							   value="{{$fechaFin}}" name="fecha_corte" id="fecha_corte"
							   placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
						@else
							<input class="form-control" size="16" style="height:30px;" type="text"
							   value="{{$fecha1}}" name="fecha_corte" id="fecha_corte"
							   placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
						@endif
						<span class="input-group-addon"><i class="fa fa-times"></i></span>
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
		</div>

		<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="id_orden">Tipo de Inspección:</label>
					<select name="tipo_insp" id="tipo_insp" class="form-control" style="max-width:250px;padding:0px;">
						
						@if(Session::has('tipo_inspeccion'))
							@if(Session::get('tipo_inspeccion') == "1")
								<option value="">Todos</option>
								<option value="1" selected>Seguridad</option>
								<option value="2">Calidad</option>
								<option value="3">Observación del comportamiento</option>
								<option value="4">Medio ambiente</option>
								<option value="5">PNC</option>
								<option value=33>Inspeccion Seguridad Obras Civiles</option>
								<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
								<option value=35>Inspeccion Redes Electricas</option>
								<option value=43>Inspeccion Kit Manejo de Derrames</option>
								<option value=44>Inspección Locativa de Gestión Ambiental</option>
								<option value=46>Inspección Entrega Obras Civiles</option>
								<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
								<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>

							@else
								@if(Session::get('tipo_inspeccion') == "2")
									<option value="">Todos</option>
									<option value="1" >Seguridad</option>
									<option value="2" selected>Calidad</option>
									<option value="3">Observación del comportamiento</option>
									<option value="4">Medio ambiente</option>
									<option value="5">PNC</option>
									<option value=33>Inspeccion Seguridad Obras Civiles</option>
									<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
									<option value=35>Inspeccion Redes Electricas</option>
									<option value=43>Inspeccion Kit Manejo de Derrames</option>
									<option value=44>Inspección Locativa de Gestión Ambiental</option>
									<option value=46>Inspección Entrega Obras Civiles</option>
									<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
									<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>

								@else
									@if(Session::get('tipo_inspeccion') == "3")
										<option value="">Todos</option>
										<option value="1" >Seguridad</option>
										<option value="2">Calidad</option>
										<option value="3" selected>Observación del comportamiento</option>
										<option value="4">Medio ambiente</option>
										<option value="5">PNC</option>
										<option value=33>Inspeccion Seguridad Obras Civiles</option>
										<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
										<option value=35>Inspeccion Redes Electricas</option>
										<option value=43>Inspeccion Kit Manejo de Derrames</option>
										<option value=44>Inspección Locativa de Gestión Ambiental</option>
										<option value=46>Inspección Entrega Obras Civiles</option>
										<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
										<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>
									@else
										@if(Session::get('tipo_inspeccion') == "33")
											<option value="">Todos</option>
											<option value="1" >Seguridad</option>
											<option value="2">Calidad</option>
											<option value="3" >Observación del comportamiento</option>
											<option value="4">Medio ambiente</option>
											<option value="5">PNC</option>
											<option value=33 selected>Inspeccion Seguridad Obras Civiles</option>
											<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
											<option value=35>Inspeccion Redes Electricas</option>
											<option value=43>Inspeccion Kit Manejo de Derrames</option>
											<option value=44>Inspección Locativa de Gestión Ambiental</option>
											<option value=46>Inspección Entrega Obras Civiles</option>
											<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
											<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>

										@else
											@if(Session::get('tipo_inspeccion') == "34")
												<option value="">Todos</option>
												<option value="1" >Seguridad</option>
												<option value="2">Calidad</option>
												<option value="3" >Observación del comportamiento</option>
												<option value="4">Medio ambiente</option>
												<option value="5">PNC</option>
												<option value=33>Inspeccion Seguridad Obras Civiles</option>
												<option value=34 selected>Inspeccion Seguridad Telecomunicaciones</option>
												<option value=35>Inspeccion Redes Electricas</option>
												<option value=43>Inspeccion Kit Manejo de Derrames</option>
												<option value=44>Inspección Locativa de Gestión Ambiental</option>
												<option value=46>Inspección Entrega Obras Civiles</option>
												<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
												<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>

											@else
												@if(Session::get('tipo_inspeccion') == "35")
													<option value="">Todos</option>
													<option value="1" >Seguridad</option>
													<option value="2">Calidad</option>
													<option value="3" >Observación del comportamiento</option>
													<option value="4">Medio ambiente</option>
													<option value="5">PNC</option>
													<option value=33>Inspeccion Seguridad Obras Civiles</option>
													<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
													<option value=35 selected>Inspeccion Redes Electricas</option>
													<option value=43>Inspeccion Kit Manejo de Derrames</option>
													<option value=44>Inspección Locativa de Gestión Ambiental</option>
													<option value=46>Inspección Entrega Obras Civiles</option>
													<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
													<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>
												@else
													@if(Session::get('tipo_inspeccion') == "43")
														<option value="">Todos</option>
														<option value="1" >Seguridad</option>
														<option value="2">Calidad</option>
														<option value="3" >Observación del comportamiento</option>
														<option value="4">Medio ambiente</option>
														<option value="5">PNC</option>
														<option value=33>Inspeccion Seguridad Obras Civiles</option>
														<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
														<option value=35>Inspeccion Redes Electricas</option>
														<option value=43 selected>Inspeccion Kit Manejo de Derrames</option>
														<option value=44>Inspección Locativa de Gestión Ambiental</option>
														<option value=46>Inspección Entrega Obras Civiles</option>
														<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
														<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>
													@else 
														@if(Session::get('tipo_inspeccion') == "44")
															<option value="">Todos</option>
															<option value="1" >Seguridad</option>
															<option value="2">Calidad</option>
															<option value="3" >Observación del comportamiento</option>
															<option value="4">Medio ambiente</option>
															<option value="5">PNC</option>
															<option value=33>Inspeccion Seguridad Obras Civiles</option>
															<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
															<option value=35>Inspeccion Redes Electricas</option>
															<option value=43>Inspeccion Kit Manejo de Derrames</option>
															<option value=44 selected>Inspección Locativa de Gestión Ambiental</option>
															<option value=46>Inspección Entrega Obras Civiles</option>
															<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
															<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>
														@else 
														  @if(Session::get('tipo_inspeccion') == "46")
															  <option value="">Todos</option>
															  <option value="1" >Seguridad</option>
															  <option value="2">Calidad</option>
															  <option value="3" >Observación del comportamiento</option>
															  <option value="4">Medio ambiente</option>
															  <option value="5">PNC</option>
															  <option value=33>Inspeccion Seguridad Obras Civiles</option>
															  <option value=34>Inspeccion Seguridad Telecomunicaciones</option>
															  <option value=35>Inspeccion Redes Electricas</option>
															  <option value=43>Inspeccion Kit Manejo de Derrames</option>
															  <option value=44>Inspección Locativa de Gestión Ambiental</option>
															  <option value=46 selected>Inspección Entrega Obras Civiles</option>
															  <option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
																<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>
														  @else 
															  @if(Session::get('tipo_inspeccion') == "36")
																	<option value="">Todos</option>
																	<option value="1" >Seguridad</option>
																	<option value="2">Calidad</option>
																	<option value="3" >Observación del comportamiento</option>
																	<option value="4">Medio ambiente</option>
																	<option value="5">PNC</option>
																	<option value=33>Inspeccion Seguridad Obras Civiles</option>
																	<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
																	<option value=35>Inspeccion Redes Electricas</option>
																	<option value=43>Inspeccion Kit Manejo de Derrames</option>
																	<option value=44>Inspección Locativa de Gestión Ambiental</option>
																	<option value=46>Inspección Entrega Obras Civiles</option>
																	<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
																	<option value=37 selected>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>
																@else 
																	<option value="" selected>Todos</option>
																	<option value="1" >Seguridad</option>
																	<option value="2" >Calidad</option>
																	<option value="3">Observación del comportamiento</option>
																	<option value="4">Medio ambiente</option>
																	<option value="5">PNC</option>
																	<option value=33>Inspeccion Seguridad Obras Civiles</option>
																	<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
																	<option value=35>Inspeccion Redes Electricas</option>
																	<option value=43>Inspeccion Kit Manejo de Derrames</option>
																	<option value=44>Inspección Locativa de Gestión Ambiental</option>
																	<option value=46>Inspección Entrega Obras Civiles</option>
																	<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
																	<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>
														  	@endif
														  @endif
														@endif
													@endif
												@endif
											@endif
										@endif
									@endif
								@endif
							@endif
						@else
							<option value="">Todos</option>
							<option value="1">Seguridad</option>
							<option value="2">Calidad</option>
							<option value="3">Observación del comportamiento</option>
							<option value="4">Medio ambiente</option>
							<option value="5">PNC</option>
							<option value=33>Inspeccion Seguridad Obras Civiles</option>
							<option value=34>Inspeccion Seguridad Telecomunicaciones</option>
							<option value=35>Inspeccion Redes Electricas</option>
							<option value=43>Inspeccion Kit Manejo de Derrames</option>
							<option value=44>Inspección Locativa de Gestión Ambiental</option>
							<option value=46>Inspección Entrega Obras Civiles</option>
							<option value=36>Inspección Calidad Trabajos de Restablecimiento del Servicio</option>
							<option value=37>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</option>

						@endif
						
					</select>
				</div>
		</div>

		<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="id_orden">Resultado:</label>
					<select name="resultado" id="resultado" class="form-control" style="max-width:250px;padding:0px;">
						@if(Session::has('resultado'))
							@if(Session::get('resultado') == "C")
								<option value="">Todos</option>
								<option value="C" selected>Conforme</option>
								<option value="NC">No conforme</option>
							@else
								@if(Session::get('resultado') == "NC")
									<option value="">Todos</option>
									<option value="C" >Conforme</option>
									<option value="NC" selected>No conforme</option>
								@else
									<option value="">Todos</option>
									<option value="C" >Conforme</option>
									<option value="NC" >No conforme</option>
								@endif
							@endif
						@else
							<option value="">Todos</option>
							<option value="C">Conforme</option>
							<option value="NC">No conforme</option>
						@endif
						
					</select>
				</div>
		</div>

		<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="id_orden">Supervisor:</label>
					@if(Session::has("super"))
							<input name="super" type="text" class="form-control" id="super" value="{{Session::get('super')}}"/>
					@else
						<input name="super" type="text" class="form-control" id="super" value=""/>
					@endif
				</div>
		</div>

		<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="id_orden">Estado:</label>
					<select name="id_estado" id="id_estado" class="form-control" style="max-width:250px;padding:0px;">
						<option value="">Todos</option>
						@foreach($estados as $key => $valor)
							@if(Session::get('id_estado') == $valor->id_estado)
								<option value="{{$valor->id_estado}}" selected>{{$valor->nombre}}</option>
							@else
								<option value="{{$valor->id_estado}}">{{$valor->nombre}}</option>
							@endif
						@endforeach
					</select>
				</div>
		</div>
		
		<div class="col-md-1">
			<button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
				<i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
			</button>
		</div>
	{!!Form::close()!!}
		<div class="col-md-2">
			{!! Form::open(['url' => 'generateExcelIPAL', "method" => "POST"]) !!}
			<input type="hidden" id="id_proyecto_1" name="id_proyecto" />
			<input type="hidden" id="fecha_inicio_1" name="fecha_inicio" />
			<input type="hidden" id="fecha_corte_1" name="fecha_corte" />
			<input type="hidden" id="tipo_insp_1" name="tipo_insp" />
			<input type="hidden" id="resultado_1" name="resultado" />
			<input type="hidden" id="super_1" name="super" />
			<input type="hidden" id="id_estado_1" name="id_estado" />

			<button type="submit" onclick="generaConsolidado()" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
				<i class="fa fa-file-excel-o"></i> &nbsp;&nbsp;Generar consolidado
			</button>
			{!!Form::close()!!}
		</div>

		<div class="col-md-2">
			{!! Form::open(['url' => 'generateExcelAccionesCorrectivas', "method" => "POST"]) !!}
			<input type="hidden" id="id_proyecto_2" name="id_proyecto" />
			<input type="hidden" id="fecha_inicio_2" name="fecha_inicio" />
			<input type="hidden" id="fecha_corte_2" name="fecha_corte" />
			<input type="hidden" id="tipo_insp_2" name="tipo_insp" />
			<input type="hidden" id="resultado_2" name="resultado" />
			<input type="hidden" id="super_2" name="super" />
			<input type="hidden" id="id_estado_2" name="id_estado" />

			<button type="submit" onclick="generaConsolidadoAccionesCorrectivas()" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
				<i class="fa fa-file-excel-o"></i> &nbsp;&nbsp;Generar Excel Acciones Correctivas
			</button>
			{!!Form::close()!!}
		</div>


	</div>

</div>

</div>



<script type="text/javascript">
	
	function generaConsolidado()
	{
		document.querySelector("#id_proyecto_1").value = document.querySelector("#id_proyecto").value;
		document.querySelector("#fecha_inicio_1").value = document.querySelector("#fecha_inicio").value;
		document.querySelector("#fecha_corte_1").value = document.querySelector("#fecha_corte").value;
		document.querySelector("#tipo_insp_1").value = document.querySelector("#tipo_insp").value;
		document.querySelector("#resultado_1").value = document.querySelector("#resultado").value;
		document.querySelector("#super_1").value = document.querySelector("#super").value;
		document.querySelector("#id_estado_1").value = document.querySelector("#id_estado").value;
	}

	function generaConsolidadoAccionesCorrectivas()
	{
		document.querySelector("#id_proyecto_2").value = document.querySelector("#id_proyecto").value;
		document.querySelector("#fecha_inicio_2").value = document.querySelector("#fecha_inicio").value;
		document.querySelector("#fecha_corte_2").value = document.querySelector("#fecha_corte").value;
		document.querySelector("#tipo_insp_2").value = document.querySelector("#tipo_insp").value;
		document.querySelector("#resultado_2").value = document.querySelector("#resultado").value;
		document.querySelector("#super_2").value = document.querySelector("#super").value;
		document.querySelector("#id_estado_2").value = document.querySelector("#id_estado").value;
	}

</script>

<?php
	Session::forget('proyecto');
	Session::forget('fecha_inicio');
	Session::forget('fecha_corte');
	Session::forget('tipo_inspeccion');
	Session::forget('resultado');
	Session::forget('super');
	Session::forget('id_estado');


?>