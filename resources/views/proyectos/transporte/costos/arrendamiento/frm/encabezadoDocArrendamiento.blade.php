
<div class="panel panel-default" style="margin-top:10px;">
  <div class="panel-heading" style="    font-weight: bold;">ACCIONES SOBRE EL DOCUMENTO</div>
  <div class="panel-body">
  	<div class="row">
		<div class="col-md-12">
			<div class="row">

				@if($imprimir == "W")
					<form action="{{url('/')}}/transporte/costos/arrendamientosdownload" method="POST" style="display:inline-block;">
						<input type="hidden"  name = "documento" value = "{{$data->id_documento}}" />
						<input type="hidden"  name = "_token" value = "{{ csrf_token() }}" />
						<button class="btn btn-primary  btn-cam-trans btn-sm"><i class="fa fa-print" aria-hidden="true"></i>   Imprimir documento</button>
					</form>
				@endif
				@if($data->id_estado != "E3" && $data->id_estado != "A1")
					@if($editar == "W")
						<button class="btn btn-primary  btn-cam-trans btn-sm" onclick="abreEditarCanon()"><i class="fa fa-edit" aria-hidden="true"></i>   Editar documento</button>
					@endif
					@if($confirmar == "W")
						<button class="btn btn-primary  btn-cam-trans btn-sm" onclick="confirmaDocumento()"><i class="fa fa-check-square-o" aria-hidden="true"></i>   Confirmar documento</button>
					@endif
					@if($anular == "W")
						<button class="btn btn-primary  btn-cam-trans btn-sm" onclick="anularDocumento()"><i class="fa fa-times" aria-hidden="true"></i>   Anular documento</button>
					@endif
				@endif

				@if($data->id_estado == "E3" || $data->id_estado == "A1")
					@if($abrir == "W")
						@if($data->id_estado == "E3")
							<button class="btn btn-primary  btn-cam-trans btn-sm" onclick="abrirDocumento()"><i class="fa fa-check-square-o" aria-hidden="true"></i>   Abrir documento</button>
						@else
							<button class="btn btn-primary  btn-cam-trans btn-sm" onclick="abrirDocumento()"><i class="fa fa-check-square-o" aria-hidden="true"></i>   Restablecer documento</button>
						@endif
					@endif
				@endif

				<button class="btn btn-primary  btn-cam-trans btn-sm" onclick="consultaLog()" ><i class="fa fa-refresh" aria-hidden="true"></i>   Ver Log</button>

				<a href="{{url('/')}}/transporte/costos/arrendamientos/{{Session::get('proyecto_user')}}" class="btn btn-primary  btn-cam-trans btn-sm"><i class="fa fa-times" aria-hidden="true"></i>   Cerrar</a>

			</div>
		</div>
	</div>

  </div>
</div>



<div class="panel panel-default" style="margin-top:10px;">
  <div class="panel-heading" style="    font-weight: bold;">DATOS DEL DOCUMENTO</div>
  <div class="panel-body">
  	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-3" style="padding:0px;">
					<div class="form-group has-feedback">
	                    <label for="txt_incidencia_dato">ID Documento</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{strtoupper($data->id_documento)}}" 
	                    name="txt_incidencia_dato" id="txt_incidencia_dato" style="padding:0px;" />
	                </div>
				</div>

				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="txt_placa">Proyecto</label>
	                    <input class="form-control" size="16"  type="text" readonly  style="padding:0px;" value="{{$data->nombre}}"
	                    name="txt_placa" id="txt_placa"/>
	                </div>
				</div>

				<div class="col-md-1">
					<div class="form-group has-feedback">
	                    <label for="txt_placa">Año</label>
	                    <input class="form-control" size="16"  type="text" readonly  style="padding:0px;" value="{{$data->anio}}"
	                    name="txt_placa" id="txt_placa"/>
	                </div>
				</div>

				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="txt_placa">Mes</label>
	                    <input class="form-control" size="16"  type="text" readonly  style="padding:0px;" value="{{$mes}}"
	                    name="txt_placa" id="txt_placa"/>
	                </div>
				</div>
			</div>
			<div class="row">

				<div class="col-md-3" style="padding:0px;">
					<div class="form-group has-feedback">
	                    <label for="txt_incidencia_dato">Estado Documento</label>
	                    @if($data->id_estado == 'E3')
	                    	<input class="form-control" size="16"  type="text" readonly value="CONFIRMADO" />
	                    @elseif($data->id_estado == 'E1')
	                    	<input class="form-control" size="16"  type="text" readonly value="GENERADO" />
	                    @else
	                    	<input class="form-control" size="16"  type="text" readonly value="ANULADO" />
	                    @endif
	                </div>
				</div>

				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="txt_placa">Placa</label>
	                    <input class="form-control" size="16"  type="text" readonly  style="padding:0px;" value="{{$data->placa}}"
	                    name="txt_placa" id="txt_placa"/>
	                </div>
				</div>
				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Canon</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="${{number_format($data->canon_actual, 2)}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
	                </div>
				</div>

				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">N° de días</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$data->cantidad_dias}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
	                </div>
				</div>

				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Total a pagar</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="${{number_format($data->total_pagar, 2)}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
	                </div>
				</div>

			</div>

			<div class="row">
				<div class="col-md-3" style="padding:0px;">
					<div class="form-group has-feedback">
	                    <label for="txt_incidencia_dato">Fecha de creación</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{strtoupper($data->fecha_creacion)}}" 
	                    name="txt_incidencia_dato" id="txt_incidencia_dato" style="padding:0px;" />
	                </div>
				</div>
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Usuario creación</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$data->crea_user}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
	                </div>
				</div>
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="txt_placa">Fecha de última actualización</label>
	                    <input class="form-control" size="16"  type="text" readonly  style="padding:0px;" value="{{$data->fecha_ultima_actualizacion}}"
	                    name="txt_placa" id="txt_placa"/>
	                </div>
				</div>
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Usuario última actualización</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$data->anula_actualiza}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
	                </div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3" style="padding:0px;">
					<div class="form-group has-feedback">
	                    <label for="txt_incidencia_dato">Fecha de confirmación</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$data->fecha_confirmacion}}" 
	                    name="txt_incidencia_dato" id="txt_incidencia_dato" style="padding:0px;" />
	                </div>
				</div>
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Usuario confirmación</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$data->user_confirma}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
	                </div>
				</div>
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="txt_placa">Fecha de anulación</label>
	                    <input class="form-control" size="16"  type="text" readonly  style="padding:0px;" value="{{$data->fecha_anula}}"
	                    name="txt_placa" id="txt_placa"/>
	                </div>
				</div>
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Usuario anulación</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$data->anula_user}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
	                </div>
				</div>
			</div>


		</div>
	</div>

  </div>
</div>




<div class="panel panel-default" style="margin-top:10px;">
  <div class="panel-heading" style="    font-weight: bold;">DÍAS QUE AFECTA EL DOCUMENTO MES DE {{$mes}}</div>
  <div class="panel-body">
  	<div class="row">
		<div class="col-md-12">
		<br>
			<div class="row">
				@foreach($dias as $key => $val)
					@if($val->check == 1)
					<div class="col-md-1" style="    width: 5.8%;    text-align: center;    margin-top:5px;margin-right: 10px;
    font-size: 12px;    color: #777;    height: 60px;    outline: 0.1mm solid #ccc;    display: inline-block;">
						
						@if($val->mes == 1)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Enero</span></b>
						@endif

						@if($val->mes == 2)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Febrero</span></b>
						@endif

						@if($val->mes == 3)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Marzo</span></b>
						@endif

						@if($val->mes == 4)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Abril</span></b>
						@endif

						@if($val->mes == 5)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Mayo</span></b>
						@endif

						@if($val->mes == 6)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Junio</span></b>
						@endif

						@if($val->mes == 7)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Julio</span></b>
						@endif

						@if($val->mes == 8)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Agosto</span></b>
						@endif

						@if($val->mes == 9)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Septiembre</span></b>
						@endif

						@if($val->mes == 10)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Octubre</span></b>
						@endif

						@if($val->mes == 11)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Noviembre</span></b>
						@endif

						@if($val->mes == 12)
							<b style="color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 12px;    position: relative;    top: -5px;    left: -2px;">Diciembre</span></b>
						@endif

					</div>
					@endif
				@endforeach
			</div>
			<br>  
			
			<div class="row">
				<!--<a href="{{url('/')}}/transporte/costos/arrendamiento/{{$data->prefijo_db}}/{{$data->placa}}" disabled class="btn btn-primary  btn-cam-trans btn-sm" target="_blank"><i class="fa fa-calendar" aria-hidden="true"></i> Ver calendario</a>-->
			</div>
		</div>
	</div>

  </div>
</div>
