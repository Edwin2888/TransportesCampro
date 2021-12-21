
@extends('template.index')

@section('title')
	Reporte vehículos sin kilometrajes
@stop

@section('title-section')
    Reporte vehículos sin kilometrajes
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
                    @include('proyectos.transporte.secciones.frmfilterreporteVehiculosSinKilometraje')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                @include('proyectos.transporte.tables.tblreporteVehiculosSinKilometraje')
                
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {

            var alto = screen.height - 400;
            var altopx = alto+"px";
           
            $('#tbl_seg_datos').dataTable({
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
                }
            }); 
            
           ocultarSincronizacionFondoBlanco();
        }

        function eliminarOdometro(placa,id)
        {
            if(confirm("¿Seguro que desea eliminar el registro de odometros?"))
            {
                var datos = {
                    placa: placa,
                    id: id,
                    opc: 33
                }
                consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 1);
            }
        }

         function consultaAjax(route,datos,tiempoEspera,type,opcion,edita,dato)
    {
        if(dato != -1)
            mostrarSincronizacion();

        $.ajax({
            url: route,
            type: type,
            headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
            dataType: "json",
            data:datos,
            timeout:tiempoEspera,
            success:function(data)
            {
                
                if(opcion ==1)//Crear ciudad
                {
                    mostrarModal(2,null,"Eliminar odometro","Se ha eliminado correctamen el registro del odometro.\n",0,"Aceptar","",null);

                    document.querySelector("#btn_consulta_datos").submit();
                }

                ocultarSincronizacion();
            },
            error:function(request,status,error){
                ocultarSincronizacion();
                //$('#filter_registro').modal('toggle');

                /*mostrarModal(1,null,"Consulta de Alianzas","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);*/
                setTimeout(function()
                {
                    //location.reload();
                },3000);
            }

        });
    }



        


    </script>
@stop