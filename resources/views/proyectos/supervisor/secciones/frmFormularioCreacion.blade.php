@if(count($formSelect) != 0)
	@if(Session::get('frm_filter') == 1)
		<div class="row" style="margin-bottom:10px;">
			<div class="col-md-4">
				<label>Correo 1</label>
				<input type="text" class="form-control" id="correo1" value="{{$correos->correo1}}"/>
			</div>

			<div class="col-md-4">
				<label>Correo 2</label>
				<input type="text" class="form-control" id="correo2" value="{{$correos->correo2}}"/>
			</div>

			<div class="col-md-4">
				<label>Correo 3</label>
				<input type="text" class="form-control" id="correo3" value="{{$correos->correo3}}"/>
			</div>
		</div>
	@endif
@endif

<table  class="table table-striped table-bordered" cellspacing="0" width="99%" id="table_form">
	<thead>
		<th>A</th>
		<th>E</th>
		<th>Item</th>	
		<th>Pregunta</th>	
		<th>Descripción</th>	
		<th>Obliga</th>	
		<th>Tipo de control</th>
		<th>Opc</th>
		<th>Padre</th>
		<th>Texto</th>
		<th>Simbolo control</th>
	</thead>
	<tbody id="tbl_datos_frm">
		@if(Session::has('frm_filter') && count($formSelect) == 0)
			<tr>
				<td><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>
					<td><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>
					<td><input type="text" value="" style="width:40px;" class="form-control" placeholder="Item"/></td>
					<td><input type="number" value="" style="width:50px;padding:0px;" class="form-control" placeholder="Pregunta" disabled /></td>
					<td><input type="text" value="" class="form-control" placeholder="Descripción"/></td>
					<td>
						{!! Form::select('obliga', ['0' => 'No', '1' => 'Si'], 0,array('style' => 'width:50px;','class' => 'form-control')) !!} 
					</td>
					<td>
						{!! Form::select('tipo_control', $tipoControl, 0,array('style' => 'width:220px;','class' => 'form-control','onchange' => 'buscarControl(this)')) !!} 
					</td>
					<td>
						<button onclick="abiriModalOpciones(this)"  style="display:none" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
						    <i class="fa fa-plus"></i>
						</button>
					</td>
					<td>
						<select data-padre="" class="form-control">
							
						</select>
					</td>
					<td>
						<input type="text" value="" placeholder="Aux" class="form-control"/>
					</td>
					<td style="width:180px">
						<img src="../../../img/tipos/0.PNG" style="width:100%;">
					</td>
			</tr>
		@else
			@foreach($formSelect as $key => $valor)
				<tr>
					<td><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>
					<td><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>
					<td><input type="text" value="{{$valor->item_num}}" style="width:40px;" class="form-control" /></td>
					<td><input type="number" value="{{$valor->id_pregunta}}" style="width:50px;" class="form-control" disabled/></td>
					<td><input type="text" value="{{$valor->descrip_pregunta}}" class="form-control"/></td>
					<td>
						{!! Form::select('obliga', ['0' => 'No', '1' => 'Si'], $valor->obligatorio,array('style' => 'width:50px;','class' => 'form-control')) !!} 
					</td>
					<td>
						{!! Form::select('tipo_control', $tipoControl, $valor->tipo_control,array('style' => 'width:220px;','class' => 'form-control','onchange' => 'buscarControl(this)')) !!} 
					</td>
					<td>
						@if($valor->tipo_control == 15)
							<button onclick="abiriModalOpciones(this)" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
							    <i class="fa fa-plus"></i>
							</button>
						@else
							<button onclick="abiriModalOpciones(this)"  style="display:none" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
							    <i class="fa fa-plus"></i>
							</button>
						@endif
					</td>
					<td>
						<select data-padre="{{$valor->id_padre}}" class="form-control">
							
						</select>
					</td>
					<td>
						<input type="text"  placeholder="Aux" value="{{$valor->nombre_corto}}" class="form-control"/>
					</td>
					<td style="width:180px">
					<img src="../../../img/tipos/{{$valor->tipo_control}}.PNG" style="width:100%;">
					
					</td>
				</tr>
			@endforeach
		@endif
	</tbody>
</table>
