<br>
<div class="input_inspeccion">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="txt_matricula_vehiculo">Inspección</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$insp}}" 
	                    name="txt_matricula_vehiculo" id="txt_matricula_vehiculo" placeholder=""/>
	                </div>
				</div>	
				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Orden</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$inspeccion->id_orden}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>

				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Fecha</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{explode(".",$inspeccion->fecha_servidor)[0]}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder="Línea"/>
	                </div>
				</div>


				<div class="col-md-2">
					<div class="form-group has-feedback">
                                       
	                    <label for="text_solicitante_supervisor">Proyecto</label>
	                 <?php /*        <?php  $proyN = "";?>
	                    @foreach($proy as $key => $valor)
	                    	<?php   
	                    		if($valor->prefijo_db == $inspeccion->prefijo)
	                    			$proyN = $valor->proyecto;
	                    	?>
	                    @endforeach
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$proyN}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
                            */ ?>
                            
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$inspeccion->proyecto}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
                            
	                </div>
				</div>
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Supervisor</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$inspeccion->nombreS}} - {{$inspeccion->apellidosS}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Líder</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$inspeccion->nombreL}} - {{$inspeccion->apellidosL}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>
				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Aux 1</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$inspeccion->nombreA1}} - {{$inspeccion->apellidosA1}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>

				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Aux 2</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$inspeccion->nombreA2}} - {{$inspeccion->apellidosA2}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>

				<div class="col-md-3">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Aux 3</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$inspeccion->nombreA3}} - {{$inspeccion->apellidosA3}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Conductor</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$inspeccion->nombreC}} - {{$inspeccion->apellidosC}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>	
				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Matrícula</label>
	                    <input class="form-control" size="16"  type="text" readonly  value="{{$inspeccion->matricula}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>
				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Móvil</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$inspeccion->movil}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>
				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Tipo de inspección</label>

	                    @if($inspeccion->tipo_inspeccion  == 1)
		                    <input class="form-control" size="16"  type="text" readonly value="Seguridad"  name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
		                @endif
		                @if($inspeccion->tipo_inspeccion  == 2)
		                	 <input class="form-control" size="16"  type="text" readonly value="Calidad"  name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
		                @endif

		                @if($inspeccion->tipo_inspeccion  == 3)
		                	 <input class="form-control" size="16"  type="text" readonly value="Observación del comportamiento"  name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
		                @endif

		                @if($inspeccion->tipo_inspeccion  == 4)
		                	 <input class="form-control" size="16"  type="text" readonly value="Medio ambiente"  name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
		                @endif
	                    
	                </div>
				</div>
				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Resultado</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$inspeccion->resultado}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder=""/>
	                </div>
				</div>	

				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Estado</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$inspeccion->estadoE}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder="Clase"/>
	                </div>
				</div>

				@if( count($sedes) > 0)

				<div class="col-md-2">
					<div class="form-group has-feedback">
	                    <label for="text_solicitante_supervisor">Sede</label>
	                    <input class="form-control" size="16"  type="text" readonly value="{{$sedes[0]->nombre_sede}}"
	                    name="text_solicitante_supervisor" id="text_solicitante_supervisor" placeholder="Clase"/>
	                </div>
				</div>
			@endif
			


			</div>
		</div>	
	</div>

</div>