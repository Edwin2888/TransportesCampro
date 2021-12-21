@extends('template.index')

@section('title')
    Reporte de inasistencias
@stop

@section('title-section')
    Reporte de Asistencia Cuadrillas
@stop


<main>

<div class="container">
    @include('proyectos.supervisor.secciones.frmfrilterInasistencia')    
    <div class="row" style="margin-top: 20px;">
        <section >
            @include('proyectos.supervisor.tables.tblinasistencia')
        </section>
    </div>
</div>
</main>


<script type="text/javascript">
    window.addEventListener('load',ini);
    /* INICIO: FUNCIONES PARA PESTAÑAS */
   
    function ini() {

        var alto = screen.height - 400;
        var altopx = alto+"px";
        $('#tbl_inasistencia').dataTable({
                    "scrollX":  "100%",
                    "scrolY":   altopx,
                    "paging":   true,
                    "searching": true,
                    "responsive":      false,
                    "colReorder":      true,
                    "order": [[ 5, 'asc' ]],
                    dom: 'T <"clear">lfrtip',
                    tableTools: {
                        "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                    }
        }); 
        ocultarSincronizacionFondoBlanco();
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
                if(opcion == 1) //Elimina documento
                {
                    mostrarModal(2,null,"Eliminar documento","Se ha eliminado correctamente el documento.\n",0,"Aceptar","",null);
                    window.location.reload();
                }
                if(opcion == 2) //Consulta Log
                {
                    document.querySelector("#log_tbl").innerHTML = data;
                    var alto = screen.height - 400;
                    var altopx = alto+"px";
                    $('#tbl_log_documentos').dataTable({
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

                    $("#modal_log_documento").modal("toggle");
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

<?php

    Session::forget('fecha_filter_1');
    Session::forget('fecha_filter_2');
    Session::forget('estado_filter_ina');
    Session::forget('filter_turno_ina');
    Session::forget('filter_movil_ina');
    Session::forget('filter_cuadrillero_ina');
    Session::forget('filter_super_ina');
?>