@extends('template.index')

@section('title')
	Consulta vehículos
@stop

@section('title-section')
    Consulta vehículos
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
                    @include('proyectos.transporte.secciones.frmfilter')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                @include('proyectos.transporte.tables.tblVehiculosMaestro')
                
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {

            var alto = screen.height - 400;
            var altopx = alto+"px";
           
            $('#tbl_validaciones').dataTable({
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

