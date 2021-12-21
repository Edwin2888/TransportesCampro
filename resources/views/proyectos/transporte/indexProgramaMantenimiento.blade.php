@extends('template.index')

@section('title')
	Programa de mantenimiento
@stop

@section('title-section')
    Programa de mantenimiento
@stop

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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
                @include('proyectos.transporte.secciones.frmFilterProgramaMantenimiento')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                @include('proyectos.transporte.tables.tblProgramaMantenimiento')
                
            </div>
        </div>
	</main>


    <script type="text/javascript">
    $("#selProyectoClienteProgMant").select2();
    $("#selTipoVehiculoProgMant").select2();
        window.addEventListener('load',ini);

        function ini()
        {

            var alto = screen.height - 400;
            var altopx = alto+"px";
           
            $('#tbl_seguimiento_mantenimientos').dataTable({
                "scrollX":  "100%",
                "scrolY":   altopx,
                "paging":   true,
                "searching": true,
                "responsive":      false,
                "colReorder":      true,
                "order": [[ 0, 'asc' ]],
                dom: 'T <"clear">lfrtip',
                tableTools: {
                    "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                },
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf'
                ]
            }); 
            
           ocultarSincronizacionFondoBlanco();

           


           

        }


        


    </script>
@stop