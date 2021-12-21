@extends('template.index')

@section('title')
    Solicitudes de mantenimiento
@stop

@section('title-section')
    Solicitudes de mantenimiento
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporte.css">
@stop

<main>

<div class="container">

    
    @if($datos != null)
        @include('proyectos.transporte.secciones.frmEncabezadoVehiculo')    
        <div style="margin-top:10px;"></div>
        <a href="../../selectVehiculo/{{$datos->placa}}" class="btn btn-primary btn-cam-trans btn-sm" style="margin-bottom:10px;margin-left:32px;"><i class="fa fa-close"></i> &nbsp; Cerrar</a>
    @else
        <div style="margin-top:10px;"></div>
        <a href="../../transversal/transporte/home" class="btn btn-primary btn-cam-trans btn-sm" style="margin-bottom:10px;margin-left:32px;"><i class="fa fa-close"></i> &nbsp; Cerrar</a>
    @endif
    <div class="row">
        <section>
            @include('proyectos.transporte.tables.tblMantenimientosVehiculo')    

        </section>
    </div>
</div>
</main>
@section('js')
    <script type="text/javascript" src="../../js/bootstrap-filestyle.min.js"></script>
@stop

<script type="text/javascript">
    window.addEventListener('load',ini);
    /* INICIO: FUNCIONES PARA PESTAÑAS */
    function nextTab(elem) {
        $(elem).next().find('a[data-toggle="tab"]').click();
    }
    function prevTab(elem) {
        $(elem).prev().find('a[data-toggle="tab"]').click();
    }
    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {

        var alto = screen.height - 400;
        var altopx = alto+"px";

        $('#mantenimiento_vehiculos').dataTable({
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

    function nuevodocumento()
    {
        window.location.reload();
    }

    function abrirModalImportar(tipo,id,nombre,ref,enti,venci){
        if(tipo == 1)//modal ADD Documento
        {
            if(ref != "")
                document.querySelector("#opc_doc").value = "2";
            else
                document.querySelector("#opc_doc").value = "1";

            document.querySelector("#id_doc").value = id;
            document.querySelector("#txtTipoSoporte").value = nombre;
            document.querySelector("#txtReferenciaDoc").value = ref;
            document.querySelector("#txtFechaVen").value = venci;
            document.querySelector("#txtNombreEntidad").value = enti;
            $("#modal_add_documento").modal("toggle");
        }
    }

    function descargarFile(ele)
    {
        ele.parentElement.submit();
    }

    function deleteDocumento(doc)
    {
        if(window.confirm("¿Seguro que desea eliminar el documento?"))
        {
            var datos = {
                placa: document.querySelector("#placa_vehi").value,
                doc : doc,
                opc: 12
            };
            consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 1); 
        }
    }

    function verDatosLogDocumento(doc)
    {
        var datos = {
            placa: document.querySelector("#placa_vehi").value,
            doc : doc,
            opc: 2
        };
        consultaAjax("../../rutaConsultaTransporte", datos, 150000, "POST", 2);         
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