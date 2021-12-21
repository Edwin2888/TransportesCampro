<p><b>Convenciones</b></p>
<p><b>IN:</b>Inhabilitado - <b>TE:</b>Tiempo estimado - <b>AS:</b>Asistencia en sitio - <b>DTS:</b>Desplazamiento al taller sin grúa - <b>DVS:</b>Desplazamiento de vehículos a la seda</p>
<table  class="table table-striped table-bordered" cellspacing="0" width="99%" id="table_form">
	<thead>
		<th style="width:10px;"></th>
		<th style="width:10px;"></th>
		<th style="width:10px;"></th>
		<th style="width:10px;"></th>
		<th style="width:10px;"></th>
		<th colspan="4">Descripción</th>			
	</thead>
	<tbody id="tbl_datos_frm">
		@if(count($arbol) == 0)
			<tr data-row="1" data-id="1">
				<td>1</td>
				<td style="width:10px;"><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>
				<td style="width:10px;"><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>
				<td style="width:10px;"><i class="fa fa-arrow-circle-left" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="izquierda(this)"></i></td>
				<td style="width:10px;"><i class="fa fa-arrow-circle-right" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="derecha(this)"></i></td>
				<td colspan="4"><input type="text" style="width:100%;" class="form-control" placeholder="Descripción" value=""/></td>
			</tr>
		@endif
		@foreach($arbol as $key => $value)
			<tr data-row="{{$value->fila}}" data-id="{{$value->id}}">
				@if($value->fila == 2)
					<td style='width:10px;border-right:1px solid transparent'></td>
				@endif
				@if($value->fila == 3)
					<td style='width:10px;border-right:1px solid transparent'></td>
					<td style='width:10px;border-right:1px solid transparent'></td>
				@endif
				@if($value->fila == 4)
					<td style='width:10px;border-right:1px solid transparent'></td>
					<td style='width:10px;border-right:1px solid transparent'></td>
					<td style='width:10px;border-right:1px solid transparent'></td>
				@endif
				<td>{{$value->item}}</td>
				<td style="width:10px;"><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>
				<td style="width:10px;"><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>
				<td style="width:10px;"><i class="fa fa-arrow-circle-left" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="izquierda(this)"></i></td>
				<td style="width:10px;"><i class="fa fa-arrow-circle-right" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="derecha(this)"></i></td>
				<td colspan="4">
					@if($value->fila == 4)
						<input type="text" style="width:70%;display:inline-block;" class="form-control" placeholder="Descripción" value="{{$value->descripcion}}"/>
						IN<input type="checkbox" {{($value->inhabilita == 1 ? 'checked' : '')}}/> - TE<input type="text" style="width:40px;" value="{{$value->tiempo_estimado}}"/> - AS <input type="checkbox" {{($value->asistencia_sitio == 1 ? 'checked' : '')}}/> - DTS <input type="checkbox" {{($value->desplazamiento_sin_grua == 1 ? 'checked' : '')}}/> - DVS <input type="checkbox" {{($value->desplazamiento_sede == 1 ? 'checked' : '')}}/>
					@else
						<input type="text" style="width:100%;" class="form-control" placeholder="Descripción" value="{{$value->descripcion}}"/>
					@endif
					</td>
			</tr>
		@endforeach
	</tbody>
</table>
