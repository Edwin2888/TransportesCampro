<button style="position:relative;margin-top:20px;margin-left:5px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="abrirModalIncidencia();">
	<i class="fa fa-refresh"></i> Ver log de la incidencia
</button>

<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">DATOS DE LA INCIDENCIA</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-1" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_incidencia_dato">Incidencia</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->incidencia)}}" name="txt_incidencia_dato" id="txt_incidencia_dato" style="padding:0px;" />
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Fecha creación</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->fecha_servidor}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group has-feedback">
							<label for="txt_placa">Placa</label>
							<input class="form-control" size="16" type="text" readonly style="padding:0px;" value="{{$datos->placa}}" name="txt_placa" id="txt_placa" />
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Estado incidencia</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->estadoIncidencia}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group has-feedback" id="contenedorkn">
							<label for="txt_km">Km</label>
							@if($acceso == "W" && $datos->tipo_incidencia == "349")
							<input class="form-control inputkmtr" size="16" type="text" style="padding:0px;" value="{{str_replace('.00','',$datos->km)}}" name="txt_km" id="txt_km" />
							@else
							<input class="form-control inputkmtr" size="16" type="text" readonly style="padding:0px;" value="{{str_replace('.00','',$datos->km)}}" />
							@endif
						</div>
					</div>

					@if($acceso == "W" && $datos->tipo_incidencia == "349")
					<div class="col-md-1">
						<div class="form-group has-feedback">
							<button style="position:relative;top:20px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="saveKM();">
								<i class="fa fa-save"></i> Guardar KM
							</button>
						</div>
					</div>
					@endif

					<?php if ($peredit == 1) { ?>
						<div class="col-md-1">
							<div class="form-group has-feedback">
								<label for="">Edición</label><br>
								<button class="btn btn-primary  btn-cam-trans btn-sm" onclick="abrirModalEdicion(event);">
									<i class="fa fa-upload"></i> Editar
								</button>
							</div>
						</div>
					<?php } ?>


					@if($acceso == "W")
					@if($datos->id_estado != 'E4')
					<div class="col-md-2">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Adjunto</label>
							<br>
							<button class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="abrirModalAdjunto();">
								<i class="fa fa-upload"></i> Agregar adjunto
							</button>
						</div>
					</div>
					@endif
					@endif
					@if( 1==1 ) <?php /*$datos->accion_tecnico_finaliza == 2  || $datos->accion == 2 */ ?>
					<div class="col-md-2">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Orden Mantenimiento</label>
							<br>
							{!! Form::open(['url' => 'donwloadPDFOrden', "method" => "POST"]) !!}
							<input type="hidden" name="inci" value="{{$datos->incidencia}}" />
							<button class="btn btn-primary  btn-cam-trans btn-sm" type="submit">
								<i class="fa fa-download"></i> Descargar Orden Mantenimiento
							</button>
							{!!Form::close()!!}
						</div>
					</div>
					@endif


				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="form-group has-feedback">
					<label for="txt_matricula_vehiculo">Observación incidencia</label>
					<textarea class="form-control" size="16" type="text" readonly name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" placeholder="Observación">{{strtoupper($datos->observacion)}}</textarea>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-2">
				<div class="form-group has-feedback">
					<?php if ($datos->id_estado != 'E06') { ?>
					<label>Rutina de mantenimiento</label>
					<select id="id_rutina" class="form-control">
						<option value="">---Seleccione---</option>
						<?php foreach ($rutinas as $rutina) : ?>
							<option readonly value="{{ $rutina->id_rutina}}"><?php echo $rutina->nombre ?></option>
						<?php endforeach; ?>
					</select>
					<?php } ?>
				</div>
			</div>
		</div>

		<!-- ====================================================================================================== -->
		<!-- Boton cierre incidencia -->
		<!-- ====================================================================================================== -->
		<?php if ($permiso_w_cerrar_incidencia == 1) : ?>
			<div class="row">
				<div class="col-md-offset-10 col-md-2">
					<div class="form-group has-feedback">
						<?php if ($datos->id_estado != 'E06') { ?>
						<label for="">Cerrar Incidencia</label><br>
						<button class="btn btn-primary  btn-cam-trans btn-sm" onclick="abrirModalCerrarIncidencia(event);">
							<i class="fa fa-upload"></i> Cerrar Incidencia
						</button>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>

@if(count($adjun) > 0)
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">ARCHIVOS ADJUNTOS DE INCIDENCIAS</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					@foreach ($adjun as $key => $value)
					<div class="col-md-1" style="padding:0px;">
						<div class="form-group has-feedback">
							<a href="visor/<?php echo base64_encode($value->direccion); ?>" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Ver adjunto</a>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>
@endif




<div class="panel panel-default">
	<div class="panel-heading" style="    font-weight: bold;">ÁRBOL DE DECISIONES</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Tipo de incidencia</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->tipoInci}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder="Línea" />
						</div>
					</div>

					@if($datos->version_arbol == "1")
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Componente</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->componente)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Tipo de falla</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->tipo_falla}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Respuesta</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->respuesta}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					@else
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Componente</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->componente2)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Tipo de falla</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->tipo_falla2}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Respuesta</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->respuesta2}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					@endif



				</div>
				<div class="row">
					@if($datos->version_arbol == "1")
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Inhabilita</label>
							<input class="form-control" size="16" type="text" readonly value="{{($datos->inhabilita_arbol == 1 ? 'SI' : 'NO')}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder="Línea" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Tiempo inhabilitado</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->tiempo_estimado_arbol)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>
					@else
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Inhabilita</label>
							<input class="form-control" size="16" type="text" readonly value="{{($datos->inhabilita_arbol2 == 1 ? 'SI' : 'NO')}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder="Línea" />
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Tiempo inhabilitado</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->tiempo_estimado_arbol2)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>
					@endif

				</div>



			</div>
		</div>
	</div>
</div>


@if(count($fotosC) > 0)
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">FOTOS TOMADAS POR EL CONDUCTOR CUANDO GENERA LA INCIDENCIA</div>
	<div class="panel-body">
		<div class="row">
			@foreach($fotosC as $key => $val)
			<div class="col-md-2">
				<img src="http://190.60.248.195/anexos_apa/anexos/{{$val->ruta}}" style="    border: 2px solid #ccc;    padding: 18px;    border-radius: 11px;" />
			</div>
			@endforeach
		</div>
	</div>
</div>
@endif

@if($datos->fecha_ingreso_taller != NULL || $datos->id_estado == 'E07')
<!-- Fecha Visita -->
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">FECHAS DE VISITAS</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					@if(count($perfil) > 0)
					@if($perfil[0]->nivel_acceso == "W")
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<input type="hidden" name="id_doc" id="id_doc" />
							<input type="hidden" name="opc_doc" id="opc_doc" value="1" />
							<label for="txtFechaUltimoMante">Fecha estimada de salida</label>
							<div class="col-sm-9">
								<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="  width: 100%;">
									<input class="form-control" size="16" style="height:30px;" type="text" name="txt_fecha_visita" id="txt_fecha_visita" placeholder="dd/mm/aaaa">
									<span class="input-group-addon"><i class="fa fa-times"></i></span>
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label>Observación</label>
							<textarea class="form-control" id="txt_obser_visita"></textarea>
						</div>
					</div>
					@else
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<input type="hidden" name="id_doc" id="id_doc" />
							<input type="hidden" name="opc_doc" id="opc_doc" value="1" />
							<label for="txtFechaUltimoMante">Fecha estimada de salida</label>
							<div class="col-sm-9">
								<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="  width: 100%;">
									<input class="form-control" size="16" readonly style="height:30px;" type="text" name="txt_fecha_visita" id="txt_fecha_visita" placeholder="dd/mm/aaaa">
									<span class="input-group-addon"><i class="fa fa-times"></i></span>
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label>Observación</label>
							<textarea class="form-control" id="txt_obser_visita" readonly></textarea>
						</div>
					</div>

					@endif
					@else
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<input type="hidden" name="id_doc" id="id_doc" />
							<input type="hidden" name="opc_doc" id="opc_doc" value="1" />
							<label for="txtFechaUltimoMante">Fecha estimada de salida</label>
							<div class="col-sm-9">
								<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="  width: 100%;">
									<input class="form-control" size="16" readonly style="height:30px;" type="text" name="txt_fecha_visita" id="txt_fecha_visita" placeholder="dd/mm/aaaa">
									<span class="input-group-addon"><i class="fa fa-times"></i></span>
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label>Observación</label>
							<textarea class="form-control" id="txt_obser_visita" readonly></textarea>
						</div>
					</div>
					@endif

					@if(count($perfil) > 0)
					@if($perfil[0]->nivel_acceso == "W")
					<div class="col-md-1">
						<div class="form-group has-feedback">
							<button style="position:relative;top:24px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="saveVisita();">
								<i class="fa fa-save"></i> Guardar visita
							</button>
						</div>
					</div>
					@endif
					@endif

				</div>

				<div class="row">
					<table class="table table-striped table-bordered" cellspacing="0" width="50%">
						<thead>
							<th style="width:1px; text-align: center">
								N°
							</th>
							<th style="width:50px; text-align: center">
								Fecha visita
							</th>
							<th style="width:200px;  text-align: center">
								Observación
							</th>
							<th style="width:50px;  text-align: center">
								Usuario
							</th>
						</thead>
						<tbody id="tblvisitas">
							<?php $aux = count($visita); ?>
							@foreach($visita as $key => $val)
							<tr>
								<td style="text-align:center;width:50px;">
									{{$aux--}}
								</td>
								<td style="text-align:center;width:50px;">
									{{$val->fecha_visita}}
								</td>
								<td style="text-align:center;width:50px;">
									{{$val->observacion}}
								</td>
								<td style="text-align:center;width:50px;">
									{{$val->propietario}}
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>


				</div>
			</div>
		</div>
	</div>
</div>
@endif

@if($datos->tecnico_asignado != "" && $datos->tecnico_asignado != null && $datos->tecnico_asignado != NULL)
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">TÉCNICO QUE ATIENDE INCIDENCIA</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Técnico</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->tecnico_asignado)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Fecha de asignación</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->fecha_asignacion}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Fecha que recibe la incidencia</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->fecha_recibido}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Fecha que acepta la incidencia</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->fecha_aceptacion}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-md-3">
				<div class="form-group has-feedback">
					<label for="text_solicitante_supervisor">Fecha que finaliza la incidencia</label>
					<input class="form-control" size="16" type="text" readonly value="{{$datos->fecha_finalizacion}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group has-feedback">
					<label for="text_solicitante_supervisor">Resultado revisión técnico</label>
					<?php
					$revision = "";
					if ($datos->accion_tecnico_finaliza == "1") {
						$revision = "REVISIÓN SATISFACTORIA";
					} else {
						if ($datos->accion_tecnico_finaliza == "2") {
							$revision = "REMITE A TALLER";
						};
					}
					?>
					<input class="form-control" size="16" type="text" readonly value="{{$revision}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group has-feedback">
					<label for="txt_matricula_vehiculo">Observación técnico</label>
					<textarea class="form-control" size="16" type="text" readonly name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" placeholder="Observación">{{strtoupper($datos->obser_tecnico_finaliza)}}</textarea>
				</div>
			</div>
		</div>
	</div>
</div>

	@if(count($fotos) > 0)
	<div class="panel panel-default" style="margin-top:10px;">
		<div class="panel-heading" style="    font-weight: bold;">FOTOS TOMADAS POR EL TÉCNICO CUANDO ATIENDE LA INCIDENCIA</div>
		<div class="panel-body">
			<div class="row">
				@foreach($fotos as $key => $val)
				<div class="col-md-2">
					<img src="http://190.60.248.195/anexos_apa/anexos/{{$val->ruta}}" style="    border: 2px solid #ccc;    padding: 18px;    border-radius: 11px;" />
				</div>
				@endforeach
			</div>
		</div>
	</div>
	@endif

@endif

@if($datos->accion_tecnico_finaliza == 2 )
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">TÉCNICO REMITE A TALLER</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Taller</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->taller_asignado)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Fecha remite taller</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->fecha_finalizacion}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
@endif

@if($datos->fecha_ingreso_taller != NULL )
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">NOVEDADES INCIDENCIA - INGRESO A TALLER</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-2" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Fecha ingreso taller</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->fecha_ingreso_taller)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Tiempo estimado en taller</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->tiempo_estimado_taller}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Usuario que ingresa a taller</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->usuario_ingreso_taller}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>


				</div>

			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group has-feedback">
					<label for="txt_matricula_vehiculo">Observación ingreso a taller</label>
					<textarea class="form-control" size="16" type="text" readonly name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" placeholder="Observación">{{strtoupper($datos->observacion_ingreso)}}</textarea>
				</div>
			</div>
		</div>

		<div class="row">
			@if(count($perfil) > 0)
			@if($perfil[0]->nivel_acceso == "W")
			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="text_solicitante_supervisor">Costo estimado</label>
					<input class="form-control" size="16" min="0" id="costo_ingreso" type="number" value="{{$datos->costo_ingreso}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group has-feedback">
					<input type="hidden" name="id_doc" id="id_doc" />
					<input type="hidden" name="opc_doc" id="opc_doc" value="1" />
					<label for="txtFechaUltimoMante">Fecha estimada de salida</label>
					<div class="col-sm-9">
						<?php
						$fecha = $datos->fecha_aproxima_salida;
						if ($fecha == null || $fecha == "")
							$fecha = "";
						else
							$fecha = explode("-", $fecha)[2] . "/" . explode("-", $fecha)[1] . "/" . explode("-", $fecha)[0];
						?>
						<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="  width: 100%;">
							<input class="form-control" size="16" style="height:30px;" type="text" name="txtFechaUltimoMante" id="txtFechaUltimoMante" value="{{$fecha}}" placeholder="dd/mm/aaaa">
							<span class="input-group-addon"><i class="fa fa-times"></i></span>
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="txt_observacion_1">Observación</label>
					<textarea class="form-control" name="txt_observacion_1" id="txt_observacion_1">{{$datos->obserIngreso}}</textarea>
				</div>
			</div>

			@else
			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="text_solicitante_supervisor">Costo estimado</label>
					<input class="form-control" size="16" min="0" readonly type="number" value="{{$datos->costo_ingreso}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group has-feedback">
					<input type="hidden" name="id_doc" id="id_doc" />
					<input type="hidden" name="opc_doc" id="opc_doc" value="1" />
					<label for="txtFechaUltimoMante">Fecha estimada de salida</label>
					<div class="col-sm-9">
						<?php
						$fecha = $datos->fecha_aproxima_salida;
						if ($fecha == null || $fecha == "")
							$fecha = "";
						else
							$fecha = explode("-", $fecha)[2] . "/" . explode("-", $fecha)[1] . "/" . explode("-", $fecha)[0];
						?>
						<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="  width: 100%;">
							<input class="form-control" size="16" style="height:30px;" type="text" readonly name="txtFechaUltimoMante" id="txtFechaUltimoMante" value="{{$fecha}}" placeholder="dd/mm/aaaa">

						</div>
					</div>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label>Observación</label>
					<textarea class="form-control" readonly>{{$datos->obserIngreso}}</textarea>
				</div>
			</div>
			@endif
			@else
			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="text_solicitante_supervisor">Costo estimado</label>
					<input class="form-control" size="16" min="0" readonly type="number" value="{{$datos->costo_ingreso}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group has-feedback">
					<input type="hidden" name="id_doc" id="id_doc" />
					<input type="hidden" name="opc_doc" id="opc_doc" value="1" />
					<label for="txtFechaUltimoMante">Fecha estimada de salida</label>
					<div class="col-sm-9">
						<?php
						$fecha = $datos->fecha_aproxima_salida;
						if ($fecha == null || $fecha == "")
							$fecha = "";
						else
							$fecha = explode("-", $fecha)[2] . "/" . explode("-", $fecha)[1] . "/" . explode("-", $fecha)[0];
						?>
						<input class="form-control" size="16" style="height:30px;" type="text" readonly name="txtFechaUltimoMante" id="txtFechaUltimoMante" value="{{$fecha}}" placeholder="dd/mm/aaaa">
					</div>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label>Observación</label>
					<textarea class="form-control" readonly>{{$datos->obserIngreso}}</textarea>
				</div>
			</div>

			@endif
			@if(count($perfil) > 0)
			@if($perfil[0]->nivel_acceso == "W")
			<div class="col-md-1" style="    position: relative;    top: 21px;padding:0px;   ">
				<div class="form-group has-feedback">
					<button class="btn btn-primary  btn-cam-trans btn-sm" id="btn_save_ingreso" type="submit" title="Guardar salida de taller">
						<i class="fa fa-save"> Guardar</i>
					</button>
					<button class="btn btn-primary  btn-cam-trans btn-sm" id="btn_log_ingreso" type="submit" title="Ver LOG">
						<i class="fa fa-refresh"></i>
					</button>
				</div>
			</div>
			@endif
			@endif

		</div>
	</div>
</div>
@endif



@if($datos->fecha_salida != NULL )
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">NOVEDADES INCIDENCIA - SALIDA DE TALLER</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Fecha salida de taller</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->fecha_salida)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Usuario que hace salida de taller</label>
							<input class="form-control" size="16" type="text" readonly value="{{$datos->usuario_salida_taller}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group has-feedback">
							<label for="txt_km_prox">Km próximo</label>
							@if($acceso == "W" && $datos->tipo_incidencia == "349")
							<input class="form-control" size="16" type="text" style="padding:0px;" value="{{str_replace('.00','',$datos->km_proximo)}}" name="txt_km_prox" id="txt_km_prox" />
							@else
							<input class="form-control" size="16" type="text" readonly style="padding:0px;" value="{{str_replace('.00','',$datos->km_proximo)}}" />
							@endif
						</div>
					</div>

					@if($acceso == "W" && $datos->tipo_incidencia == "349")
					<div class="col-md-1">
						<div class="form-group has-feedback">
							<button style="position:relative;top:20px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="saveKMProx();">
								<i class="fa fa-save"></i> Guardar KM Próximo
							</button>
						</div>
					</div>
					@endif



				</div>

			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group has-feedback">
					<label for="txt_matricula_vehiculo">Observación salida de taller</label>
					<textarea class="form-control" size="16" type="text" readonly name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" placeholder="Observación">{{strtoupper($datos->observacion_salida)}}</textarea>
				</div>
			</div>
		</div>

		<div class="row">
			@if(count($perfilCostoReal) > 0)
			@if($perfilCostoReal[0]->nivel_acceso == "W")
			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="text_solicitante_supervisor">Costo real</label>
					<input class="form-control" size="16" type="text" value="{{$datos->costo_salida}}" name="txt_costo_salida" id="txt_costo_salida" />
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="txt_observacion_2">Observación</label>
					<textarea class="form-control" name="txt_observacion_2" id="txt_observacion_2">{{$datos->obserSalida}}</textarea>
				</div>
			</div>


			@else
			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="text_solicitante_supervisor">Costo real</label>
					<input class="form-control" size="16" type="text" value="{{$datos->costo_salida}}" name="txt_costo_salida" readonly />
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label>Observación</label>
					<textarea class="form-control" readonly>{{$datos->obserSalida}}</textarea>
				</div>
			</div>

			@endif
			@else
			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label for="text_solicitante_supervisor">Costo real</label>
					<input class="form-control" size="16" type="text" value="{{$datos->costo_salida}}" name="txt_costo_salida" readonly />
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group has-feedback">
					<label>Observación</label>
					<textarea class="form-control" readonly>{{$datos->obserSalida}}</textarea>
				</div>
			</div>
			@endif

			@if(count($perfilCostoReal) > 0)
			@if($perfilCostoReal[0]->nivel_acceso == "W")
			<div class="col-md-1" style="    position: relative;    top: 21px;padding:0px;   ">
				<div class="form-group has-feedback">
					<button class="btn btn-primary  btn-cam-trans btn-sm" id="btn_save_salida" type="submit" title="Guardar salida de taller">
						<i class="fa fa-save"> Guardar</i>
					</button>
					<button class="btn btn-primary  btn-cam-trans btn-sm" id="btn_log_salida" type="submit" title="Ver LOG">
						<i class="fa fa-refresh"></i>
					</button>
				</div>
			</div>
			@endif
			@endif
		</div>
	</div>
</div>
@endif

@if($datos->tecnico_entrega != '' && $datos->tecnico_entrega != null)
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">ENTREGA A LA OPERACIÓN</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Técnico que realiza la entrega a la operación</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->tecnico_entrega)}}" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Fecha que realiza entrega a la operación</label>
							<input style="    width: 82%;    display: inline-block;" class="form-control" size="16" type="text" readonly value="{{($datos->fecha_entrega)}}" />

						</div>
					</div>
					@if($datos->conductor_entrega != '' && $datos->conductor_entrega != null)
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Conductor que realiza la verificación de la operación</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->conductor_entrega)}}" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Fecha de verificación entrega a la operación</label>
							<input style="    width: 82%;    display: inline-block;" class="form-control" size="16" type="text" readonly value="{{($datos->fecha_verificaciones)}}" />
						</div>
					</div>
					@endif


				</div>

			</div>
		</div>
	</div>
</div>
@endif


<?php
$pos = strpos($datos->incidencia, 'OM');
?>

@if($datos->id_estado == 'E05' || $datos->id_estado == 'E07' || $datos->id_estado == 'E06' || $pos !== false )

<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">CIERRE DE INCIDENCIA</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-3" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Fecha de cierre</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->fecha_cierre)}}" name="txt_matricula_vehiculo" id="idfechadecierre" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Usuario que hace el cierre de taller</label>
							<input class="form-control" size="16" type="text" readonly value="{{($datos->usuario_fin1 == NULL ? $datos->usuario_fin2 : $datos->usuario_fin1)}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>

					<div class="panel-default">
						<div class="panel-body" style="width:100%; height:50%; overflow: scroll;">
							<table name="tablaResultado" id="tablaResultado" class="table table-striped">
								<th>Detalle</th>
								<th>Resultado</th>
								<th>Observación</th>
								<?php foreach ($resultadoRutina as $res) : ?>
									<tr>
										<td value="{{ $res->id_detalle}}"><?php echo $res->nombre ?></td>
										<td style="text-align: center;" value="{{ $res->resultado}}"><?php if ($res->resultado == 'S') { ?>
												<i class="fa fa-check-circle-o fa-2x" style="color: #337ab7;" aria-hidden="true"></i>
											<?php } else { ?><i style="color: #337ab7;" class="fa fa-window-close fa-2x" aria-hidden="true"><?php } ?>
										</td>
										<td value="{{ $res->comentario}}"><?php echo $res->comentario ?></td>
									</tr>
								<?php endforeach; ?>
								<table>
						</div>
					</div>

					@if(count($perfilFinalizarInci) > 0)
					@if($perfilFinalizarInci[0]->nivel_acceso == "Write")
					@if(($datos->id_estado == 'E05' || $datos->id_estado == 'E07' || $pos !== false) && $datos->id_estado != 'E06' )
					<div class="col-md-2" style="padding:0px;">
						<div class="form-group has-feedback">
							<button style="    margin-top: 21px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="finalizaIncidencia();">
								<i class="fa fa-close"></i> Finaliza incidencia
							</button>
						</div>
					</div>
					@endif
					@endif
					@endif

				</div>

			</div>
		</div>
	</div>
</div>
@endif


@if($acceso == "W")
<?php
$pos = strpos($datos->incidencia, 'OM');
if ($pos !== false) { //Es una incidencia tipo OM
?>
	<div class="panel panel-default" style="margin-top:10px;">
		<div class="panel-heading" style="    font-weight: bold;">CAMBIO COSTOS - PARQUE APA</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group has-feedback">
								<label for="text_solicitante_supervisor">Costo estimado</label>
								<input class="form-control" size="16" min="0" id="costo_ingreso" type="number" value="{{$datos->costo_ingreso}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group has-feedback">
								<input type="hidden" name="id_doc" id="id_doc" />
								<input type="hidden" name="opc_doc" id="opc_doc" value="1" />
								<label for="txtFechaUltimoMante">Fecha salida de taller</label>
								<div class="col-sm-9">
									<?php
									$fecha = $datos->fecha_aproxima_salida;
									if ($fecha == null || $fecha == "")
										$fecha = "";
									else
										$fecha = explode("-", $fecha)[2] . "/" . explode("-", $fecha)[1] . "/" . explode("-", $fecha)[0];
									?>
									<div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="  width: 100%;">
										<input class="form-control" size="16" style="height:30px;" type="text" name="txtFechaUltimoMante" id="txtFechaUltimoMante" value="{{$fecha}}" placeholder="dd/mm/aaaa">
										<span class="input-group-addon"><i class="fa fa-times"></i></span>
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-1" style="    position: relative;    top: 21px;padding:0px;    left: -74px;">
							<div class="form-group has-feedback">
								<button class="btn btn-primary  btn-cam-trans btn-sm" id="btn_save_ingreso" type="submit" title="Guardar salida de taller">
									<i class="fa fa-save"> Guardar</i>
								</button>
								<button class="btn btn-primary  btn-cam-trans btn-sm" id="btn_log_ingreso" type="submit" title="Ver LOG">
									<i class="fa fa-refresh"></i>
								</button>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
<?php
}
?>
@endif

@if(count($perfilAnularInci) > 0)
@if($perfilAnularInci[0]->nivel_acceso == "W")
@if($datos->id_estado != 'E06')
<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">ANULAR INCIDENCIA</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-2" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Fecha de anulación</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->fecha_anulacion)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Usuario que hace la anulación de la incidencia</label>
							<input class="form-control" size="16" type="text" readonly value="{{($datos->usuario_anula == NULL ? "" : $datos->usuario_anula)}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label>Observación</label>
							@if($datos->id_estado != 'E4')
							<textarea class="form-control" id="txt_obser_anulacion" style="height: 100px;">{{$datos->obser_anulacion}}</textarea>
							@else
							<textarea readonly class="form-control" id="txt_obser_anulacion" style="height: 100px;">{{$datos->obser_anulacion}}</textarea>
							@endif
						</div>
					</div>


					@if($datos->id_estado != 'E4')
					<div class="col-md-2" style="padding:0px;">
						<div class="form-group has-feedback">
							<button style="    margin-top: 21px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="anulaincidencia();">
								<i class="fa fa-close"></i> Anular incidencia
							</button>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endif
@else

<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">ANULAR INCIDENCIA</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-2" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Fecha de anulación</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->fecha_anulacion)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>


					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Usuario que hace la anulación de la incidencia</label>
							<input class="form-control" size="16" type="text" readonly value="{{($datos->usuario_anula == NULL ? "" : $datos->usuario_anula)}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label>Observación</label>
							<textarea readonly class="form-control" id="txt_obser_anulacion" style="height: 100px;">{{$datos->obser_anulacion}}</textarea>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

@endif

@else

<div class="panel panel-default" style="margin-top:10px;">
	<div class="panel-heading" style="    font-weight: bold;">ANULAR INCIDENCIA</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-2" style="padding:0px;">
						<div class="form-group has-feedback">
							<label for="txt_matricula_vehiculo">Fecha de anulación</label>
							<input class="form-control" size="16" type="text" readonly value="{{strtoupper($datos->fecha_anulacion)}}" name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" />
						</div>
					</div>


					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label for="text_solicitante_supervisor">Usuario que hace la anulación de la incidencia</label>
							<input class="form-control" size="16" type="text" readonly value="{{($datos->usuario_anula == NULL ? "" : $datos->usuario_anula)}}" name="text_solicitante_supervisor" id="text_solicitante_supervisor" />
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group has-feedback">
							<label>Observación</label>
							<textarea readonly class="form-control" id="txt_obser_anulacion" style="height: 100px;">{{$datos->obser_anulacion}}</textarea>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

@endif

<?php
//linea 65
//#contenedorkn
//.inputkmtr

$kmtros = "";
// if($datos->fecha_salida != NULL ){$kmtros=str_replace('.00','',$datos->km_proximo);}
$kmtros = str_replace('.00', '', $datos->km_proximo);

$kmtrosinci = str_replace('.00', '', $datos->km);
if(is_numeric($kmtrosinci)){
	$km_actual_mas_rutina_km = str_replace('.00', '', $kmtrosinci + $maestro_parque_item->rutina_km);
}else{
	$km_actual_mas_rutina_km = 0;
}
$fcierre = strtoupper($datos->fecha_cierre);
//         name="txt_matricula_vehiculo" id="idfechadecierre
?>



<?php if ($peredit == 1) { ?>
	<div class="modal fade" id="modal_edutar_reg" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header modal-filter panel-warning">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h5 class="modal-title">Editar Información</h5>

				</div>
				<div class="modal-body datosparaedit">
					<input type="hidden" id="incidenciaedit" value="<?= $incid ?>">
					<div class="form-group">
						<div class="col-md-3">
							<label>KM Proximo:</label>
						</div>
						<div class="col-md-6">
							<input type="text" class="form-control valida_enteros solo_numeros" data-val="val_kmeditas" id="kmeditas" value="<?= $kmtros ?>">
						</div>
						<div class="col-md-3">
							<label class="val_kmeditas"></label>
						</div>
					</div>
					<div style="width:100%;clear:both;height:3px;"></div>

					<div class="form-group">
						<div class="col-md-3">
							<label>KM incidencia:</label>
						</div>
						<div class="col-md-6">
							<input type="text" class="form-control valida_enteros solo_numeros" data-val="val_kmeditasd" id="kminciden" value="<?= $kmtrosinci ?>">
						</div>
						<div class="col-md-3">
							<label class="val_kmeditasd"></label>
						</div>
					</div>
					<div style="width:100%;clear:both;height:3px;"></div>




					<div class="form-group">
						<div class="col-md-3">
							<label>Fecha Cierre:</label>
						</div>
						<div class="col-md-6">
							<div class="input-group date form_date form_datetime" data-date="" data-date-format="yyyy-mm-dd hh:ii:ss " style="    width: 100%;">
								<input class="form-control valida_texto" data-val="fecha_validd" size="16" style="height:30px;" type="text" value="<?= str_replace(":00.000", "", $fcierre); ?>" name="fecha_cierre_ed" id="fecha_cierre_ed" readonly placeholder="aaaa-mm-dd hh:mm:ss ">
								<span class="input-group-addon"><i class="fa fa-times"></i></span>
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>
						</div>
						<div class="col-md-3">
							<label class="fecha_validd"></label>
						</div>
					</div>
					<div style="width:100%;clear:both;height:3px;"></div>

					<center>
						<button type="submit" onclick="guardaeditreg(event)" class=" btn btn-primary btn-form" style="color:#ffffff;"><i class="fa fa-save" aria-hidden="true"></i> Guardar</button>
						<img src="<?= Request::root() ?>/img/loader6.gif" class="loading" alt="Loading..." style="display:none;">
					</center>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>

			</div>
		</div>
	</div>
<?php } ?>
<!-- ================================================================================================================= -->
<!-- Modal Cerrar Incidencia -->
<!-- ================================================================================================================= -->
<?php if ($permiso_w_cerrar_incidencia == 1) : ?>
	<div class="modal fade" id="modal_cerrar_incidencia_reg" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">

				<!-- Header -->
				<div class="modal-header modal-filter panel-warning">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

					<h5 class="modal-title">
						Cerrar Incidencia (Incidencia <?= $incid ?>. Placa: <?php print $datos->placa; ?>)
					</h5>
				</div>

				<!-- Body -->
				<div class="modal-body datosparaedit">
					<form method="post" id="frm_cierre_incidencia" name="frm_cierre_incidencia" enctype="multipart/form-data" onkeypress="return event.keyCode!=13" data-toggle="validator" role="form" onsubmit="return false;">

						<input type="hidden" id="cierre_incidencia__fecha_creacion" value="2018-05-31 00:00:00">
						<input type="hidden" id="cierre_incidencia__numero_incidencia" value="<?= $incid ?>">

						<!-- ====================================================================================================== -->
						<!-- Fecha de Cierre && Taller Incidencia -->
						<!-- ====================================================================================================== -->
						<div class="row">
							<div class="col col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">DETALLES DEL CIERRE</div>
									<div class="panel-body">
										<!-- ====================================================================================================== -->
										<!-- Fecha de Cierre -->
										<!-- ====================================================================================================== -->
										<div class="col col-md-6">
											<div class="form-group">
												<label for="cierre_incidencia__fecha_finalizacion" class="control-label">
													FECHA CIERRE <span style="color:red;">(*)</span>:
												</label>

												<div class="input-group date form_datetime" data-date="" data-date-format="yyyy-mm-dd hh:ii">
													<input class="form-control" size="16" style="height:30px;" type="text" name="cierre_incidencia__fecha_finalizacion" id="cierre_incidencia__fecha_finalizacion" placeholder="aaaa-mm-dd hh:mm" disabled="disabled" required />

													<span class="input-group-addon"><i class="fa fa-times"></i></span>
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<!-- ====================================================================================================== -->
										<!-- Taller Incidencia -->
										<!-- ====================================================================================================== -->
										<div class="col col-md-6">
											<div class="form-group">
												<label for="cierre_incidencia__taller" class="control-label">
													TALLER <span style="color:red;">(*)</span>:
												</label>

												<select class="form-control" name="cierre_incidencia__taller" id="cierre_incidencia__taller" required>
													<option value=""> - Seleccione un taller - </option>

													<?php foreach ($talleres_parque as $taller) : ?>
														<option value="<?php print $taller->id; ?>" <?php $taller->id === $datos->taller_asignado ? print 'selected' : ''; ?>>
															<?php print $taller->nombre_proveedor; ?>
														</option>
													<?php endforeach; ?>

												</select>

												<div class="help-block with-errors"></div>
											</div>
										</div>


										<!-- ====================================================================================================== -->
										<!-- KM Actual Incidencia && KM Próximo Incidencia -->
										<!-- ====================================================================================================== -->
										<div class="row">

											<!-- ==================================================================================================== -->
											<!-- KM Actual Incidencia-->
											<!-- ==================================================================================================== -->
											<div class="col col-md-6">
												<div class="form-group">
													<label for="cierre_incidencia__km_actual" class="control-label">
														KM ACTUAL
														<span style="color:red;">(*)</span>:
													</label>

													<input type="number" class="form-control" data-val="cierre_incidencia__km_actual" name="cierre_incidencia__km_actual" id="cierre_incidencia__km_actual" value="<?= $kmtrosinci ?>" required>

													<div class="help-block with-errors"></div>
												</div>
											</div>

											<!-- ==================================================================================================== -->
											<!-- KM Próximo Incidencia  -->
											<!-- ==================================================================================================== -->
											<div class="col col-md-6">
												<div class="form-group">
													<label for="cierre_incidencia__km_proximo" class="control-label">
														KM PRÓXIMO (+<?php echo $maestro_parque_item->rutina_km; ?>)
														<span style="color:red;">(*)</span>:
													</label>

													<input type="number" class="form-control" data-val="cierre_incidencia__km_proximo" name="cierre_incidencia__km_proximo" id="cierre_incidencia__km_proximo" value="<?= $km_actual_mas_rutina_km ?>" required>

													<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>

										<!-- ====================================================================================================== -->
										<!-- Observaciones  -->
										<!-- ====================================================================================================== -->
										<div class="row">
											<div class="col col-md-12">
												<div class="form-group">
													<label for="cierre_incidencia__observaciones" class="control-label">
														OBSERVACIONES <span style="color:red;">(*)</span>:
													</label>

													<textarea rows="8" class="form-control valida_texto" name="cierre_incidencia__observaciones" id="cierre_incidencia__observaciones" value="" required></textarea>

													<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col col-md-12">
									<div class="panel panel-default">
										<div class="panel-heading">LISTA DE VERIFICACIÓN DE LA RUTINA</div>
										<div class="panel-body" style="width:100%; height:50%; overflow: scroll;">
											<div name="rutinaMantenimiento" id="rutinaMantenimiento"></div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- ====================================================================================================== -->
						<!-- Boton de Guardar-->
						<!-- ====================================================================================================== -->
						<div class="row">
							<div class="col col-md-12" style="text-align: center;">
								<button type="submit" onclick="guardaCerrarIncidencia()" id="cierre_incidencia__btn_guardar" class="btn btn-primary">
									Cerrar Incidencia
								</button>
							</div>

							<img src="<?= Request::root() ?>/img/loader6.gif" class="loading" alt="Loading..." style="display:none;">
						</div>
						<!-- Footer -->
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>