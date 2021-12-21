@extends('template.index')

@section('title')
    Lista de proveedores
@stop

@section('title-section')
    Lista de proveedores
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="/css/transporte.css">
@stop
<main>

	<a href="/transversal/transporte/nuevoProveedores" class="btn btn-primary  btn-cam-trans btn-sm" style="margin-top:10px;margin-left:5px;"><i class='fa'>&#xf067;</i> Crear proveedor</a>

	<div class="container" style="margin-top:10px;" >
		<div class="row" style="margin-left:2%;width:96%">
						<table id="tabla" class="table table-striped table-bordered" cellspacing="0" >
						   <thead >
						     <td>Nombre Proveedor
						     </td>
						     <td>NIT
						     </td>
						     <td>Direccion
						     </td>
						     <td>Telefono
						     </td>
						     <td>Especialidad
						     </td>
						     <td>Proyecto
						     </td>
						     <td>Coordenadas
						     </td>
						     <td>Obs Proyecto
						     </td>
						     <td>Tipo
						     </td>
						     <td>Correo
						     </td>
						     <td>Correo 2
						     </td>
						     <td>Correo 3
						     </td>
							 
						     <td style="width:60px;">Editar
						     </td>
						   </thead>
						   <?php foreach($talleres as $taller){ ?>
							   <tr>
						     <td><?= $taller->nombre_proveedor ?>
						     </td>
						     <td><?= $taller->nit ?>
						     </td>
						     <td><?= $taller->direccion ?>
						     </td>
						     <td><?= $taller->telefono ?>
						     </td>
						     <td><?= $taller->especialidad ?>
						     </td>
						     <td><?= $taller->proyecto ?>
						     </td>
						     <td><?= $taller->coordenadas ?>
						     </td>
						     <td><?= $taller->obs_proyecto ?>
						     </td>
						     <td><?= $taller->tipo ?>
						     </td>
						     <td><?= $taller->correo ?>
						     </td>
						     <td><?= $taller->correo2 ?>
						     </td>
						     <td><?= $taller->correo3 ?>
						     </td>
							 
						     <td style="width:60px;">

						     	<a  class="btn btn-primary btn-cam-trans btn-sm" href="/transversal/transporte/editarProveedores/<?= $taller->id ?>"  > <i class="fa fa-search" aria-hidden="true"></i></a>
						    
						     	<a  class="btn btn-primary btn-cam-trans btn-sm"  href="/transversal/transporte/eliminaProveedores/<?= $taller->id ?>" style="color: red;    border-color: red;" > <i class="fa fa-times" aria-hidden="true"></i></a>


							    
							
						     </td>
						   </tr>
						   <?php } ?>
						</table>
						</div>
		</div>
	</div>	
</main>	
<script type="text/javascript">
    //Funci�n INI Javascript
    window.addEventListener('load',iniList);
 
    function iniList() 
    {
        var alto = screen.height - 400;
        var altopx = alto+"px";

        $('#tabla').dataTable({
            "scrollX":  "100%",
            "scrolY":   altopx,
            "paging":   true,
            "searching": true,
            "responsive":      false,
            "colReorder":      true,
            dom: 'T <"clear">lfrtip',
            tableTools: {
                "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
            }
        }); 

		//Oculta el banner transparente
        ocultarSincronizacionFondoBlanco();
    }  
</script>
     




