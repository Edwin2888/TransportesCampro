@extends('template.index')

@section('title')
    Documento de Arrendamiento
@stop

@section('title-section')
    Documento de Arrendamiento
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/transporte.css">
@stop
<!-- http://www.ajaxshake.com/demo/ES/833/453fddb1/mensajes-de-notificacion-estilo-android-con-jquery-toastmessage.html-->

<main>

    <!--@include('proyectos.transporte.costos.conceptos.modal.modalCreateConcepto')-->

    <div class="container">
      
    @include('proyectos.transporte.costos.arrendamiento.modal.modalLogArrendamiento')  
    @include('proyectos.transporte.costos.arrendamiento.modal.modalAnulacionDocumento')
    @include('proyectos.transporte.costos.arrendamiento.modal.modalEditaDocumento')
    @include('proyectos.transporte.costos.arrendamiento.modal.modalAbrirDocumento')

    @include('proyectos.transporte.costos.arrendamiento.frm.encabezadoDocArrendamiento')  
    
    <div class="row">
        <section>
            
        </section>
    </div>

        
        
    </div>
</main>

<script type="text/javascript">
    window.addEventListener('load',ini);

    /* FIN: FUNCIONES PARA PESTAÑAS */
    function ini() {


        var alto = screen.height - 400;
        var altopx = alto+"px";
       
        $('#tbl_arrendamieno').dataTable({
            "scrollX":  "100%",
            "scrolY":   altopx,
            "paging":   true,
            "searching": true,
            "responsive":      false,
            "colReorder":      true,
            "order": [[ 2, 'asc' ]],
            dom: 'T <"clear">lfrtip',
            tableTools: {
                "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
            }
        }); 
        


    
        ocultarSincronizacionFondoBlanco();
        /* FIN: FUNCIONES PARA PESTAÑAS */
    }

    @if($data->id_estado == "E3" || $data->id_estado == "A1")
        
        function abrirDocumento()
        {
            $("#modal_abrir_documento").modal("toggle");
        }

        function abrirDocumentoSave()
        {
            

            if(document.querySelector("#txtObserAbrir").value == "")
            {
                mostrarModal(1,null,"Abrir documento","Hace ingresar la observación",0,"Aceptar","",null); 
                return;
            }

            $("#modal_abrir_documento").modal("toggle");
            if(confirm("¿Seguro que desea abrir el documento?"))
            {
                var datos = {
                  "opc": 4,
                  "doc" : '{{$data->id_documento}}',
                  "obser" : document.querySelector("#txtObserAbrir").value
                };

                consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 150000, "POST", 2);    
            }
        }
    @endif

    @if($data->id_estado != "E3" && $data->id_estado != "A1")
        function anularDocumento()
        {
            $("#modal_observacion_anulacion").modal("toggle");
        }

        function anularDocumentoSave()
        {
            if(document.querySelector("#txtObserAnulacion").value == "")
            {
                mostrarModal(1,null,"Anular documento","Hace ingresar la observación de anulación",0,"Aceptar","",null); 
                return;
            }

            $("#modal_observacion_anulacion").modal("toggle");   
            if(confirm("¿Seguro que desea anular el documento?"))
            {
                var datos = {
                  "opc": 3,
                  "obser": document.querySelector("#txtObserAnulacion").value,
                  "doc" : '{{$data->id_documento}}'
                };

                consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 150000, "POST", 3);    
            }
        }

        function confirmaDocumento()
        {
            if(confirm("¿Seguro que desea confirmar el documento?"))
            {
                var datos = {
                  "opc": 2,
                  "doc" : '{{$data->id_documento}}'
                };

                consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 150000, "POST", 4);    
            }
        }
    @endif

    function abreEditarCanon()
    {
        $("#modal_edit_documento").modal("toggle"); 
    }

    function guardarNuevoCanon()
    {
        if(document.querySelector("#txt_nuevo_canon").value == "")
        {
            mostrarModal(1,null,"Modificar el documento","Hace ingresar el nuevo canon",0,"Aceptar","",null); 
            return;
        }

         if(document.querySelector("#txtObserEditarCanon").value == "")
        {
            mostrarModal(1,null,"Modificar el documento","Hace ingresar la observación del cambio de canón",0,"Aceptar","",null); 
            return;
        }


        $("#modal_edit_documento").modal("toggle");   
        if(confirm("¿Seguro que desea modificar el canon?"))
        {
            var datos = {
              "opc": 6,
              "canon": document.querySelector("#txt_nuevo_canon").value,
              "obser": document.querySelector("#txtObserEditarCanon").value,
              "doc" : '{{$data->id_documento}}'
            };

            consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 150000, "POST", 6);    
        }
    }

    function consultaLog()
    {
        var datos = {
          "opc": 5,
          "doc" : '{{$data->id_documento}}'
        };

        consultaAjax("{{url('/')}}/transporte/costos/arrendamiento/saveWebService", datos, 150000, "POST", 5);    
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
                    if(opcion == 1)//Guardar planilla
                    {
                        document.querySelector("#boton_2_modal").style.display  = "none";
                        document.querySelector("#boton_1_modal").style.display  = "none";

                        mostrarModal(2,null,"Se ha guardado correctamente la planilla N° " + data,"","","",null);                     
                        ocultarSincronizacion();
                        setTimeout(function()
                        {
                            location.reload();
                        },600);
                    }

                    if(opcion == 2 || opcion == 3 || opcion == 4)//Guardar datos de documento
                    {
                        mostrarModal(2,null,"Se ha actualizado correctamente el documento",0,"","",null); 
                        document.querySelector("#boton_2_modal").style.display  = "none";
                        document.querySelector("#boton_1_modal").style.display  = "none";  

                        ocultarSincronizacion();
                        setTimeout(function()
                        {
                            location.reload();
                        },600);

                    }

                    if(opcion == 5)//Consulta LOG
                    {
                    
                        var html = "";

                        html += "<table  class='table table-striped table-bordered table_center' cellspacing='0' width='99%'>";
                        html += "<thead>";
                        html += "<tr>";

                        html += "<th>" + "Fecha" + "</th>";
                        html += "<th>" + "Usuario" + "</th>";
                        html += "<th>" + "Tipo de cambio" + "</th>";
                        html += "<th>" + "Observación" + "</th>";
                        
                        html += "</tr>";
                        html += "</thead>";

                        html += "<tbody>";

                        for (var i = 0; i < data.length; i++) {
                            html += "<tr>";

                            html += "<td>" + data[i].fecha + "</td>";
                            html += "<td>" + data[i].propietario + "</td>";
                            html += "<td>" + data[i].tipo_log + "</td>";
                            html += "<td>" + data[i].descripcion + "</td>";

                            html += "</tr>";
                        };

                        html += "</tbody>";
                        html += "</table>";
                        document.querySelector("#log_tbl").innerHTML = html;

                       $("#modal_log_documento").modal("toggle");
                       ocultarSincronizacion();
                    }

                    if(opcion == 6) //Modificar el canon
                    {
                        mostrarModal(2,null,"Modificar el documento","Se ha modificado correctamente el documento",0,"","",null); 
                        document.querySelector("#boton_2_modal").style.display  = "none";
                        document.querySelector("#boton_1_modal").style.display  = "none";  

                        ocultarSincronizacion();
                        setTimeout(function()
                        {
                            location.reload();
                        },600);
                    }
                },
                error:function(request,status,error){
                    ocultarSincronizacion();
                    //$('#filter_registro').modal('toggle');

                    mostrarModal(1,null,"Problema con la conexión a internet","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);
                    setTimeout(function()
                    {
                        //location.reload();
                    },3000);
                }

            });
        }

    


</script>