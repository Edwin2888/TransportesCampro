@extends('template.index')

@section('title')
    Lista rutinas
@stop
@section('title-section')
Listado rutinas
@stop
<main>
	<a href="/transversal/transporte/createRutina" class="btn btn-primary  btn-cam-trans btn-sm" style="margin-top:10px;margin-left:5px;"><i class="fa fa-plus-square" aria-hidden="true"></i> Crear Rutina</a>

	<div class="container" style="margin-top:10px;">
		<div class="row" style="margin-left:2%;width:96%">
			<table id="tabla" class="table table-striped table-bordered" cellspacing="0">
				<thead>
					<th>Actividad
					</th>
					<th>Ciclo
					</th>
					<th style="width:60px;">Detalle
					</th>
				</thead>
				<?php foreach ($rutinas as $rutina) { ?>
					<tr>
						<td><?= $rutina->nombre ?>
						</td>
						<td><?= $rutina->ciclo ?>
						</td>

						<td style="width:60px; text-align: center;">

						<a  class="btn btn-primary btn-cam-trans btn-sm" href="/transversal/transporte/editRutina?id_rutina=<?= $rutina->id_rutina ?>"  > <i class="fa fa-search" aria-hidden="true"></i></a>
						<?php } ?>
						
						</td>
					</tr>

			</table>

		</div>
	</div>
	</div>
</main>
<script type="text/javascript">
	//Funci�n INI Javascript
	window.addEventListener('load', iniList);

	function iniList() {
		var alto = screen.height - 400;
		var altopx = alto + "px";

		$('#tabla').dataTable({
			"scrolY": altopx,
			"paging": true,
			"searching": true,
			"responsive": false,
			"colReorder": true,
			dom: 'T <"clear">lfrtip',
			tableTools: {
				"sSwfPath": "../../media/copy_csv_xls_pdf.swf",
				"aButtons": ["copy", "xls"]
			}
		});

		//Oculta el banner transparente
		ocultarSincronizacionFondoBlanco();
	}
</script>
