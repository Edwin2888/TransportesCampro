<div>
	<h3>Restricción asignada</h3>
	<p>Sr/a {!!strtoupper($arr[0]['resp'])!!}, se le ha asignado para la fecha {!!$arr[0]['fech']!!}, la restricción:</p>
	<p>Restricción: {!!$arr[0]['nombreR']!!}</p>
	<p>Impacto: {!!$arr[0]['imp']!!}</p>
	<p>Maniobra: {!!$arr[0]['OT']!!}</p>
	<p>Nombre del proyecto: {!!$arr[0]['pry']!!}</p>
	<p>Fecha de ejecución: {!!explode(" ",$arr[0]['fechaE'])[0]!!}</p>
	<p>Dirección de Trabajos a Realizar: {!!$arr[0]['dire']!!}</p>
	<p>SEC/PF/CD: {!!$arr[0]['sec']!!} {!!$arr[0]['pf']!!} {!!$arr[0]['cd']!!}</p>
	<p>Recurso Técnico:</p>
	<table>
		@foreach($arr[0]['recurso'] as $res => $val)
			<tr>
				<td>{{$val->nombre}} - {{$val->id_lider}}</td>
			</tr>
		@endforeach
	</table>
</div>