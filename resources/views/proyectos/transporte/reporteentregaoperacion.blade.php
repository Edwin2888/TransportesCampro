@extends('template.index')

@section('title')
	Entrega a la operación
@stop

@section('title-section')
    Entrega a la operación
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

        @include('proyectos.transporte.modal.modalEntregaOperacionTecCon')    

		<div class="container-fluid">
            <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                    @include('proyectos.transporte.secciones.frmfilterreporteentregaOperacion')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                @include('proyectos.transporte.tables.tblentregaOperacion')
                
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
                "order": [[ 12, 'asc' ]],
                dom: 'T <"clear">lfrtip',
                tableTools: {
                    "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                }
            }); 
            
           ocultarSincronizacionFondoBlanco();

        }


        function enviarDatos(persona,inci)
        {
            var datos = {
              incidencia: inci,
              persona: persona,
              opc: 17
            };
            consultaAjax("../../rutaConsultaTransporte", datos, 150000, "POST", 1); 
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
                    if(opcion == 1)//Recibir Notificación
                    {

                        var html = "";

                      
                          html += '<table id="tbl_ver_entrega_op" class="table table-striped table-bordered table_center" cellspacing="0" width="94%;margin-left:3%;">';
                          html += '    <thead>';
                          html += '        <tr>';
                          html += '            <th style="width:10px;">Item</th>';
                          html += '            <th style="width:100px;">Descripción</th>';
                          html += '            <th style="width:100px;">Respuesta</th>';
                          html += '        </tr>';
                          html += '    </thead>';
                          html += '    <tbody>';
                          for (var i = 0; i < data.length; i++) {
                            html += "<tr>";
                            html += "<td>" + data[i].item_num  + "</td>";
                            html += "<td>" + data[i].descrip_pregunta  + "</td>";
                            var res = data[i].res;
                            if(data[i].res  == "1")
                                res = "SI";

                            if(data[i].res  == "2")
                                res = "NO";

                            if(data[i].res  == "3")
                                res = "NA";

                            html += "<td>" + res + " " + (data[i].cantidad == 0 ? "" : " - CANTIDAD: " + data[i].cantidad) + " "  + (data[i].fecha == null || data[i].fecha == "1900-01-01"  ? "" : " - FECHA: " + data[i].fecha )  + "</td>";
                            html += "</tr>";
                          };
                          html += '</tbody>';
                          html += '</table>';

                        document.querySelector("#tbl_novedades_entrega_operacion").innerHTML = html;
                        $("#modal_entrega_opeacion").modal("toggle");
                        ocultarSincronizacion();
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
@stop