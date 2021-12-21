@extends('template.index')

@section('title')
	Restricciones
@stop

@section('title-section')
    Restricciones
@stop



@section('content')

    <style type="text/css">

    table td,table th
    {
        text-align: center;
    }

    .elementos
    {
            width: 134px;    height: 46px;    position: absolute;       top: 95px;    right: 0px;
        margin-right: 20px;
    }

    .elementos a
    {
        background: blue;
        border-radius: 100%;
        width: 40px;
        height: 40px;
        display: inline-block;
    }

    .elementos a span
    {
        position: relative;
        color: white;
        font-size: 20px;
        left: 14px;
        top: 7px;
    }

     .btn_cerrar
    {
        font-size: 12px;
        color: white;
        margin-left: 6px;
        padding: 4px;
        position: relative;
        top: -2px;
        margin-top: 10px;
        display: inline-block;
        margin-bottom: 4px;
        color: #204d74;
        background-color: white;
        border-color: #204d74;
        border: 1px solid;
        transition:background 0.5s;
    }

    .btn_cerrar:hover
    {
        text-decoration: none;
        color: white;
        background-color: #204d74;
        border-color: #204d74;
        border: 1px solid;
    }
    
    </style>
	<main>

        @include('proyectos.redes.trabajoprogramado.modal.modalconsultaproyectos')
        @include('proyectos.redes.trabajoprogramado.modal.modalconsultaresponsable')

        <a href="../../redes/ordenes/ordentrabajo" id="anchorID" style="display:none;" target="_blank"></a>
        <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
            @include('proyectos.redes.trabajoprogramado.secciones.filterTableRestricciones')
        </div>

         <div class="row">
        <div class="col-md-12" style="margin-top:15px;" >
            <div style="margin-bottom:5px;font-weight:bold">
              <h4 style="text-align:center;">Convenciones restricciones:</h4>
              <span style="    display: inline-block;    width: 13px;    height: 13px;    margin-right: 7px;    background-color: rgb(0,143,65);margin-left: 31% "></span>Levantada
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 7px;    background-color:blue;"></span>En proceso
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: yellow;"></span>En proceso < 7 días
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: red;"></span>Por iniciar
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: black;"></span>Anulada              
            </div>
        </div>
      </div>

        @include('proyectos.redes.trabajoprogramado.secciones.tblRestriccionesform')

	</main> 
    
    <script type="text/javascript">

        window.addEventListener('load',inigant);

        function inigant()
        {
            
            if(localStorage.getItem('filter-res-fIni') != null &&  localStorage.getItem('filter-res-fIni') != "")
            {
               document.querySelector("#fecha_inicio").value =  localStorage.getItem('filter-res-fIni');
               document.querySelector("#fecha_corte").value =  localStorage.getItem('filter-res-fFin');
               document.querySelector("#id_tipo").value =  localStorage.getItem('filter-res-tipo');
            }

            var alto = screen.height - 400;
            var altopx = alto+"px";

            $('#restricciones').dataTable({
                    "scrollX":  "100%",
                    "scrolY":   altopx,
                    "paging":   true,
                    "searching": true,
                    "responsive":      false,
                    "colReorder":      true,
                    "order": [[ 3, 'asc' ]],
                    dom: 'T <"clear">lfrtip',
                    tableTools: {
                        "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                    }
                }); 

            ocultarSincronizacionFondoBlanco();
        }

          function consultaAjax(route,datos,tiempoEspera,type,opcion,collback,dato)
        {
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
                    if(opcion == 1) //Cambio orden
                        $("#anchorID")[0].click();
                    

                    if(opcion == 2) //Consulta proyecto
                    {
                        var html = "";

                        if(data.length == 0)
                        {
                            html += "<tr>";
                            html += "<td colspan='3'>No existen proyectos</td>";
                            html += "</tr>";

                        }
                        else
                        {
                            for (var i = 0; i < data.length; i++) {
                                html += "<tr>";
                                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarProyectoFilter(this)' data-cod='" + data[i].codigo + "'></i></td>";
                                html += "<td>" + data[i].nombre + "</td>";
                                html += "<td>" + data[i].id_proyecto + "</td>";
                                html += "</tr>";
                            };
                        }
                        
                        $("#tbl_recu_add1").html(html);

                    }

                    if(opcion == 3) //Consulta responable
                    {
                        var html = "";

                        if(data.length == 0)
                        {
                            html += "<tr>";
                            html += "<td colspan='2'>No existen responsables</td>";
                            html += "</tr>";

                        }
                        else
                        {
                            for (var i = 0; i < data.length; i++) {
                                html += "<tr>";
                                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarResponFilter(this)' data-cod='" + data[i].codigo + "'></i></td>";
                                html += "<td>" + data[i].responsable + "</td>";
                                html += "</tr>";
                            };
                        }
                        
                        $("#tbl_recu_add2").html(html);
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
        
        function abrirModal(opc)
        {
            if(opc == 1)
            {
                $("#modal_proyecto").modal("toggle");
            }

            if(opc == 2)
            {
                $("#modal_reponsable").modal("toggle");   
            }
        }

        function consultaResponsable()
        {
            if(document.querySelector("#txt_responsable").value == "")
            {
                mostrarModal(1,null,"Consulta responsable","Tiene ingresar datos para consultar el responsable.\n",0,"Aceptar","",null);
                return;
            }
            var datos = 
                {
                    opc : 26,
                    resp : document.querySelector("#txt_responsable").value
                };
            consultaAjax("../../consultaActiMate",datos,20000,"POST",3);
        }

        function consultaProyecto()
        {
            if(document.querySelector("#txt_nombre_proyecto").value == "" && document.querySelector("#txt_cod_proyecto").value == "")
            {
                mostrarModal(1,null,"Consulta proyectos","Debe ingresar información, en alguno de los parámetros de búsqueda.\n",0,"Aceptar","",null);
                return;
            }
            var datos = 
                {
                    opc : 25,
                    nom : document.querySelector("#txt_nombre_proyecto").value,
                    pro : document.querySelector("#txt_cod_proyecto").value
                };
            consultaAjax("../../consultaActiMate",datos,20000,"POST",2);
        }

        function agregarProyectoFilter(ele)
        {
            document.querySelector("#id_proyecto").value =  ele.parentElement.parentElement.children[2].innerHTML;
            $("#modal_proyecto").modal("toggle");  
        }

        function agregarResponFilter(ele)
        {
            document.querySelector("#id_responsable").value =  ele.parentElement.parentElement.children[1].innerHTML; 
            $("#modal_reponsable").modal("toggle");    
        }

        function salir(opc)
        {
            if(opc == 1)
                $("#modal_proyecto").modal("toggle");  

            if(opc == 2)
                $("#modal_reponsable").modal("toggle");  
        }

        function limpiar1(opc)
        {
            if(opc == 1)
            {
                document.querySelector("#id_proyecto").value = "";
            }else{
                document.querySelector("#id_responsable").value = "";
            }
        }

         function verOT(orden)
        {
            var datos = 
                {
                    opc : 2,
                    ot : orden
                };
            consultaAjax("../../guardarProyecto",datos,20000,"POST",1);
        }

        function consultaFiltroRestricciones()
        {
            localStorage.setItem('filter-res-fIni',document.querySelector("#fecha_inicio").value);
            localStorage.setItem('filter-res-fFin',document.querySelector("#fecha_corte").value);
            localStorage.setItem('filter-res-tipo',document.querySelector("#id_tipo").value);

        }

        //<button class="md-trigger" data-modal="modal-1">Fade in &amp; Scale</button>
    </script>
@stop

