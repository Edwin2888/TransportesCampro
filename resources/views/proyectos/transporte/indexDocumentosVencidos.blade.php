@extends('template.index')

@section('title')
	Consulta documentos vencidos
@stop

@section('title-section')
    Consulta documentos vencidos
@stop

@section('content')
    <style type="text/css">
    #proyectos_filter
    {
        position: relative;
        top: -35px;
        left: -15px;
    }

    #proyectos_wrapper .dataTables_scroll
    {
        position: relative;
        top: -35px;
    }
    </style>
	<main>


		<div class="container-fluid">
            <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                    @include('proyectos.transporte.secciones.frmfilterDocumentosVencidos')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                @include('proyectos.transporte.tables.tblVehiculosDocumentoVencidos')
                
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {
            ocultarSincronizacionFondoBlanco();

           var alto = screen.height - 400;
           var altopx = alto+"px";
            $('#tbl_documento_vencidos').dataTable({
                    "scrollX":  "100%",
                    "scrolY":   altopx,
                    "paging":   true,
                    "searching": true,
                    "responsive":      false,
                    "colReorder":      true,
                    "order": [[ 10, 'asc' ]],
                    dom: 'T <"clear">lfrtip',
                    tableTools: {
                        "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf' 
                    ]
                }); 
        }

        function descargarFile(ele)
        {
            ele.parentElement.submit();
        }
    </script>
@stop

<?php
    Session::forget('fecha_inicio_f');
    Session::forget('fecha_corte_f');
    Session::forget('selTipoVehiculo_f');
    Session::forget('selEstado_f');
    Session::forget('selProyectoCliente_f');
?>

