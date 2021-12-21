@extends('template.index')

@section('title')
    Lista de Contratantes
@stop

@section('title-section')
    Lista de Contratantes
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="/css/transporte.css">
@stop
<main>

	<!--<a href="/transversal/transporte/nuevoContratantes" class="btn btn-primary  btn-cam-trans btn-sm" style="margin-top:10px;margin-left:5px;"><i class='fa'>&#xf067;</i> Crear Contratante</a>-->

									
	<div class="container" style="margin-top:10px;" >
		     <div class="form-group has-feedback">
                                            <div class="col-md-3">
                                                <center>

                                                 
													&nbsp;&nbsp;
													<a href="/transversal/transporte/home" class="btn btn-primary" > Regresar Parque Automotor</a>
                                                </center>
                                            </div>
                                        </div>
		<div class="row" style="margin-left:2%;width:96%">
						<table id="tabla" class="table table-striped table-bordered" cellspacing="0" >
						   <thead >
						     <td>NOMBRE
						     </td>
						     <td>CECO
						     </td>
						     <td>LN
						     </td>
						  <!--  <td>ELEMENTO PEP
						     </td>-->
						     <td>GRUPO COMPRAS
						     </td>
						    
							 
						     <td style="width:60px;">Editar
						     </td>
						   </thead>
						   <?php foreach($contratantes as $contratante){ ?>
							   <tr>
						     <td><?= $contratante->nombre ?>
						     </td>
						     <td><?= $contratante->ceco ?>
						     </td>
						     <td><?= $contratante->ln ?>
						     </td>
						  <!-- <td><?= $contratante->codigo_pep ?>
						     </td>-->
						     <td><?= $contratante->grupo_compras ?>
						     </td>
						    
							 
						     <td style="width:60px;">

						     	<a  class="btn btn-primary btn-cam-trans btn-sm" href="/transversal/transporte/editarContratantes/<?= $contratante->id ?>"  > <i class="fa fa-search" aria-hidden="true"></i></a>
						    
						     <!--	<a  class="btn btn-primary btn-cam-trans btn-sm"  href="/transversal/transporte/eliminaContratantes/<?= $contratante->id ?>" style="color: red;    border-color: red;" > <i class="fa fa-times" aria-hidden="true"></i></a>-->


							    
							
						     </td>
						   </tr>
						   <?php } ?>
						</table>
						</div>
		</div>
	</div>	
	
                                   
									 
</main>	
<script type="text/javascript">
    //Función INI Javascript
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
     




