@extends('template.index')

@section('title')
	Seguimiento de mantenimientos
@stop

@section('title-section')
    Seguimiento de mantenimientos
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
                    @include('proyectos.transporte.secciones.frmfilterreportesemanal')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">

                <form method="post" action="{{url('/')}}/transversal/reportes/exportsemanal" >
                    
                    <input type="hidden" name="fecha_ini_inci_report" id="fecha_ini_inci_report">
                    <input type="hidden" name="fecha_fin_inci_report" id="fecha_fin_inci_report">

                    <input type="hidden" name="tipo_vehiculo_inci_report" id="tipo_vehiculo_inci_report">
                    <input type="hidden" name="estado_inci_report" id="estado_inci_report">
                    <input type="hidden" name="proy_inci_report" id="proy_inci_report">
                    <input type="hidden" name="inci_inci_report" id="inci_inci_report">
                    <input type="hidden" name="tipo_mant_inci_report" id="tipo_mant_inci_report">
                    <input type="hidden" name="placa_inci_report" id="placa_inci_report">


                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <button class="btn btn-primary  btn-cam-trans btn-sm" style="    margin-bottom: 16px;    margin-right: 0px;    width: 159px;    position: relative;    left: 86%;" onclick="consultarDatos()">
                        <i class="fa fa-download"></i> Exportar incidencias
                    </button>
                </form>
                @include('proyectos.transporte.tables.tblseguimientomantenimiento')
                
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {

            var alto = screen.height - 300;
            var altopx = alto+"px";
           /*
            $('#tbl_seg_datos').dataTable({
                scrollX:  "100%",
                scrolY:   altopx,
                scrollCollapse: true,
                paging:   true,
                searching: true,
                responsive:      false,
                colReorder:      true,
                order: [[ 1, 'desc' ]]
            }); */
            /*
             $('#tbl_seg_datos').DataTable( {
                scrollX:  "100%",
                scrollY:        altopx,
                scrollCollapse: true,
                paging:         false,
                responsive:      false,
                colReorder:      true,
                order: [[ 1, 'desc' ]]
            } );*/
            
            
            	$('#tbl_seg_datos').dataTable({
                        scrollX:  "100%",
                        scrollY:        altopx,
                        "paging":         true,
			"responsive": 	   true,
			"colReorder": 	   true,
			dom: 'T <"clear">lfrtip',
			tableTools: {
			"sSwfPath": "<?= Request::root() ?>/js/swf/copy_csv_xls_pdf.swf",
                        "aButtons": ["copy", "xls"]
                        }
		});
            
           ocultarSincronizacionFondoBlanco(); 

        }

        function consultarDatos()
        {
            document.querySelector("#fecha_ini_inci_report").value = document.querySelector("#fecha_inicio").value; 
            document.querySelector("#fecha_fin_inci_report").value = document.querySelector("#fecha_corte").value;

            document.querySelector("#tipo_vehiculo_inci_report").value = document.querySelector("#selTipoVehiculo").value;
            document.querySelector("#estado_inci_report").value = document.querySelector("#selEstado").value;
            document.querySelector("#proy_inci_report").value = document.querySelector("#selProyectoCliente").value;
            document.querySelector("#inci_inci_report").value = document.querySelector("#txt_incidencia").value;
            document.querySelector("#tipo_mant_inci_report").value = document.querySelector("#tipoMANTSelect").value;
            document.querySelector("#placa_inci_report").value = document.querySelector("#txt_placa_filter").value;    

        }


        


    </script>
@stop