@extends('template.index')

@section('title')
	Proyectos
@stop

@section('title-section')
    Proyectos
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
        top: 0px;
    }
    </style>
	<main>

    @include('proyectos.redes.trabajoprogramado.modal.modalcargagomestados')
    @include('proyectos.redes.trabajoprogramado.modal.modalBalances')
    @include('proyectos.redes.trabajoprogramado.modal.modalCapturaEjecucion')
    @include('proyectos.redes.trabajoprogramado.modal.modalCapturaConciliacion')

    @include('proyectos.redes.trabajoprogramado.modal.modalconsultaproyectos')
    @include('proyectos.redes.trabajoprogramado.modal.modalExportePreplanillas')
    @include('proyectos.redes.trabajoprogramado.modal.modalImportDescargos')

    @include('proyectos.redes.trabajoprogramado.modal.modaActividadOrdenAdd')

    @include('proyectos.redes.trabajoprogramado.modal.modalAddBaremosExtrasMasivo')

    
    

    

    

    

 
		<div class="container-fluid">
            <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                    @include('proyectos.redes.trabajoprogramado.secciones.filter')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                @include('proyectos.redes.trabajoprogramado.secciones.proyectos')
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',iniOrdenesTrabajoProgramado);

        function diaSelectEjecucion(ele)
        {

            var padre = ele.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
            
            var exist = $(padre).hasClass( "no_select" );
            
            if(exist)
                return;
            

            mostrarSincronizacion();    
            setTimeout(function(ele)
            {
                /*var dia = ele.innerHTML.trim();
                var mesAnio = ele.parentElement.parentElement.parentElement;
                var fec = mesAnio.children[0].children[0].children[1].innerHTML.split(" ");
                var mes = 0;
                
                switch(fec[0])
                {
                    case "Diciembre":
                        mes = 12;
                        break;
                    case "Noviembre":
                        mes = 11;
                        break;
                    case "Octubre":
                        mes = 10;
                        break;
                    case "Septiembre":
                        mes = 09;
                        break;
                    case "Agosto":
                        mes = 08;
                        break;
                    case "Julio":
                        mes = 07;
                        break;
                    case "Junio":
                        mes = 06;
                        break;
                    case "Mayo":
                        mes = 05;
                        break;
                    case "Abril":
                        mes = 04;
                        break;
                    case "Marzo":
                        mes = 03;
                        break;
                    case "Febrero":
                        mes = 02;
                        break;
                    case "Enero":
                        mes = 01;
                        break;
                }*/

                var f = document.querySelector("#fech_ejecucionInput").value.split("/");
                //var fecha = f[2] + "-" + f[1] + "-" + f[0];       


                var fechaIni = document.querySelector("#datos_aux_1").children[1].children[5].innerHTML.split("-");
                var fechaFin = document.querySelector("#datos_aux_1").children[1].children[6].innerHTML.split("-");

                var fecahSelect = new Date(f[2],f[1]  - 1,f[0]);

                f = f[2] + "-" + f[1] + "-" + f[0];

                fechaIni = new Date(fechaIni[0],fechaIni[1] - 1,fechaIni[2]);
                fechaFin = new Date(fechaFin[0],fechaFin[1] - 1,fechaFin[2]);


                console.log("Fecha INI:" + fechaIni);
                console.log("Fecha FIN:" + fechaFin);
                console.log("Fecha SELECT:" + fecahSelect);

                ocultarSincronizacion();

               /* if(fechaIni > fecahSelect){
                    mostrarModal(1,null,"Cargar ejecución","No puede seleccionar una fecha menor a la de inicio de la ejecución\n",0,"Aceptar","",null);
                    return;
                }

                if(fecahSelect > fechaFin){
                    mostrarModal(1,null,"Cargar ejecución","No puede seleccionar una fecha mayor a la de finalización de la ejecución\n",0,"Aceptar","",null);
                    return;
                }*/

                
                //Ya se ingreso nuevo NODO
                if(tipoIngresoDCNodo != 0)
                {
                    if(tipoIngresoDCNodo == 1) //No existe DC
                    {
                        var datos = 
                            {
                                opc: 6,
                                lid: document.querySelector("#select_lider_carga").value,
                                ot : document.querySelector("#text_eje_1").value,
                                nodo: document.querySelector("#select_nodos_afectados").value,
                                fecha_consulta : f,
                                dc : -1
                            }
                            consultaAjax("../../consultaActiMate",datos,120000,"POST",9);   
                    }
                    else //Existe DC
                    {
                        //No se ha seleccioando 
                        if(elementoSeleccionadoDC != null)
                        {
                            var datos = 
                            {
                                opc: 6,
                                lid: document.querySelector("#select_lider_carga").value,
                                ot : document.querySelector("#text_eje_1").value,
                                nodo: document.querySelector("#select_nodos_afectados").value,
                                fecha_consulta : f,
                                dc : elementoSeleccionadoDC.innerHTML
                            }

                            dcSelec = elementoSeleccionadoDC;
                            consultaAjax("../../consultaActiMate",datos,120000,"POST",9);   
                        }
                    }
                }
            },200,ele);
        }


        function iniOrdenesTrabajoProgramado()
        {
            
            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none";

            document.querySelector("#close_modal_actual").addEventListener("click",salir);

            document.querySelector("#btn-wbs-actividad").addEventListener("click",saveNuevaActividad); 

            document.querySelector("#dowload_pre").addEventListener("click",function()
            {
                $("#modal_export_pre").modal("toggle");
            });

            document.querySelector("#upload_descargo_ot").addEventListener("click",function()
            {
                $("#modal_import_descargo").modal("toggle");
            });
            consultaWBS();
            /*document.querySelector("#consulta_filter").addEventListener("click",function(evnt)
                {
                    var datos = 
                    {
                        fec1 : document.querySelector("#fecha_inicio").value,
                        fec2 : document.querySelector("#fecha_corte").value,
                        tip : document.querySelector("#id_tipo").value,
                        esta : document.querySelector("#cbo_estado").value,
                        numP : document.querySelector("#proyecto").value,
                    };
                });*/

            //mostrarSincronizacion();

            // mostrarSincronizacion;
            // ocultarSincronizacion;

            
            var alto = screen.height - 400;
            var altopx = alto+"px";
           
            tbl2=   $('#proyectos').dataTable({
                    "scrollX":  "100%",
                    "scrolY":   altopx,
                    "paging":   true,
                    "searching": true,
                    "responsive":      false,
                    "colReorder":      true,
                    "order": [[ 1, 'asc' ]],
                    dom: 'T <"clear">lfrtip',
                    tableTools: {
                        "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                    }
                }); 

            
            $('#gom_cam').dataTable({
                    "scrollX":  "100%",
                    "scrolY":   altopx,
                    "paging":   true,
                    "searching": true,
                    "responsive":      false,
                    "colReorder":      true,
                    dom: 'T <"clear">lfrtip',
                    tableTools: {
                        "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                    }
                }); 


            //GOMS
            document.querySelector("#upload_gom").addEventListener("click",function()
            {
                document.querySelector("#text_proyecto").value = "";
                document.querySelector("#text_proyecto").dataset.cod = "";
                //document.querySelector("#text_descargo_add").selectedIndex = 0;
                document.querySelector("#tbl_descargos_asociadas").innerHTML = "";
                $("#modal_gom").modal("toggle");
            });
            /*
            document.querySelector("#text_descargo_add").addEventListener("change",function()
            {
                if(this.selectedIndex == 0)
                {
                    document.querySelector("#form_add").style.display = "none";
                    var array = 
                    {
                        esta: 0,
                        opc : "17",
                        proy : document.querySelector("#text_proyecto").dataset.cod
                    }
                }

                if(this.selectedIndex == 1)
                {
                    document.querySelector("#form_add").style.display = "block"; 
                    var array = 
                    {
                        esta: 1,
                        opc : "17",
                        proy : document.querySelector("#text_proyecto").dataset.cod
                    }  
                }

                if(this.selectedIndex == 2)
                {
                    document.querySelector("#form_add").style.display = "block";
                    var array = 
                    {
                        esta: 2,
                        opc : "17",
                        proy : document.querySelector("#text_proyecto").dataset.cod
                    }
                }

                consultaAjax("../../consultaActiMate",array,15000,"POST",2);
            });
            */

            var select = $("#gom_cam select");
            for (var i = 0; i < select.length; i++) {
                select[i].addEventListener("change",function()
                {
                    var array = 
                    {
                        gom : this.parentElement.parentElement.children[0].innerHTML,
                        esta: this.value,
                        opc : "16"
                    }
                    consultaAjax("../../consultaActiMate",array,15000,"POST",1);
                });
            };
            //END GOMS

            //BALANCES
            document.querySelector("#btn-balance").addEventListener("click",function()
            {
                $("#modal_balances").modal("toggle");
            });
            //Oculta paneles Combo Balance
            document.querySelector("#select_balances").addEventListener("change",function()
            {
                document.querySelector("#datos_balance").style.display = "none";
                if(this.selectedIndex == 0 )
                {

                    document.querySelector("#panel_1").style.display = "none";
                    document.querySelector("#panel_2").style.display = "none";
                    document.querySelector("#panel_3").style.display = "none";
                    document.querySelector("#panel_4").style.display = "none";
                }

                if(this.selectedIndex == 1)
                {
                    document.querySelector("#panel_1").style.display = "block";
                    document.querySelector("#panel_2").style.display = "none";
                    document.querySelector("#panel_3").style.display = "none";
                    document.querySelector("#panel_4").style.display = "none";
                    document.querySelector("#panel_5").style.display = "none";
                }

                if(this.selectedIndex == 2)
                {
                    document.querySelector("#panel_1").style.display = "none";
                    document.querySelector("#panel_2").style.display = "block";
                    document.querySelector("#panel_3").style.display = "none";
                    document.querySelector("#panel_4").style.display = "none";
                    document.querySelector("#panel_5").style.display = "none";
                }

                if(this.selectedIndex == 3 )
                {
                    document.querySelector("#panel_1").style.display = "none";
                    document.querySelector("#panel_2").style.display = "none";
                    document.querySelector("#panel_3").style.display = "block";
                    document.querySelector("#panel_4").style.display = "none";
                    document.querySelector("#panel_5").style.display = "none";
                }

                if(this.selectedIndex == 4 )
                {
                    document.querySelector("#panel_1").style.display = "none";
                    document.querySelector("#panel_2").style.display = "none";
                    document.querySelector("#panel_3").style.display = "none";
                    document.querySelector("#panel_4").style.display = "block";
                    document.querySelector("#panel_5").style.display = "none";
                }
                if(this.selectedIndex == 5 )
                {
                    document.querySelector("#panel_1").style.display = "none";
                    document.querySelector("#panel_2").style.display = "none";
                    document.querySelector("#panel_3").style.display = "none";
                    document.querySelector("#panel_4").style.display = "none";
                    document.querySelector("#panel_5").style.display = "block";
                }
            });

            //Consulta Tablas Balances
            document.querySelector("#btn_consultar_balance").addEventListener("click",function()
            {
                if(document.querySelector("#select_balances").selectedIndex == 0)
                    return;


                if(document.querySelector("#select_balances").selectedIndex == 1)
                {
                    if(document.querySelector("#text_dc_balance").value == "")
                    {
                        mostrarModal(1,null,"Balances","Ingrese el número de DC\n",0,"Aceptar","",null);
                        return;
                    }
                }

                if(document.querySelector("#select_balances").selectedIndex == 2)
                {
                    if(document.querySelector("#text_maniO_balance").value == "")
                    {
                        mostrarModal(1,null,"Balances","Ingrese el número de la ManiObra\n",0,"Aceptar","",null);
                        return;
                    }
                }

                 if(document.querySelector("#select_balances").selectedIndex == 3)
                {
                    if(document.querySelector("#text_Nodo_Proyecto").value == "")
                    {
                        mostrarModal(1,null,"Balances","Ingrese el número de NODOS\n",0,"Aceptar","",null);
                        return;
                    }
                }

                 if(document.querySelector("#select_balances").selectedIndex == 4)
                {
                    if(document.querySelector("#text_Proyecto_GOM").value == "" && (document.querySelector("#fecha_ini_gom").value == "" || document.querySelector("#fecha_fin_gom").value == ""))
                    {
                        mostrarModal(1,null,"Balances","Ingrese el número de GOM\n",0,"Aceptar","",null);
                        return;
                    }
                }

                 if(document.querySelector("#select_balances").selectedIndex == 5)
                {
                    if(document.querySelector("#text_Proyecto_proy").value == "")
                    {
                        mostrarModal(1,null,"Balances","Ingrese el número de PROYECTO\n",0,"Aceptar","",null);
                        return;
                    }
                }


                var data = {
                    opc: document.querySelector("#select_balances").selectedIndex,
                    dc: document.querySelector("#text_dc_balance").value,
                    ot: document.querySelector("#text_maniO_balance").value,
                    nod: document.querySelector("#text_proyecto_Nodo").value,
                    proy1: document.querySelector("#text_Nodo_Proyecto").dataset.pry,
                    gom: document.querySelector("#text_Proyecto_GOM").value,
                    proy2: document.querySelector("#text_Proyecto_proy").dataset.pry,
                    consol1 : (document.querySelector("#conso1").checked ? 1 : 0),
                    consol2 : (document.querySelector("#conso2").checked ? 1 : 0),
                    consol3 : (document.querySelector("#conso3").checked ? 1 : 0),
                    consol4 : (document.querySelector("#conso4").checked ? 1 : 0),
                    conci1 : (document.querySelector("#conci1").checked ? 1 : 0),
                    conci2 : (document.querySelector("#conci2").checked ? 1 : 0),
                    conci3 : (document.querySelector("#conci3").checked ? 1 : 0),
                    conci4 : (document.querySelector("#conci4").checked ? 1 : 0),
                    conci5 : (document.querySelector("#conci5").checked ? 1 : 0),
                    fecha1: document.querySelector("#fecha_ini_gom").value,
                    fecha2: document.querySelector("#fecha_fin_gom").value
                }

                consultaAjax("../../consultaBalances",data,235000,"POST",3);
            });

            $('#conso1').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });


            $('#conso2').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });


            $('#conso3').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            $('#conso4').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            $('#crear_nuevo_1').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            $('#crear_nuevo_2').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            $('#conci1').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            $('#conci2').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            $('#conci3').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            $('#conci4').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            $('#conci5').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });


            //END BALANCES

            
            //CAPTURA EJECUCUCIÓN Y CONCILIACIÓN

            document.querySelector("#btn-eje").addEventListener("click",function()
            {
                document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                $("#modal_captura_ejecucion").modal("toggle");
            });

            //Ejecución
                document.querySelector("#select_lider_carga").addEventListener("change",function()
                {

                    if($("#select_lider_carga").val()==0){
                        $("#contentprinnodo").hide();
                    }

                    elementoSeleccionadoDC = null;
                    tipoIngresoDCNodo = 0;

                    $("#nodos-add").find("li").remove();
                    document.querySelector("#datos_captura_ejecucion").style.display = "none";
                    if(this.selectedIndex != 0)
                    {
                        var datos = 
                        {
                            opc: 5,
                            lid: document.querySelector("#select_lider_carga").value,
                            ot : document.querySelector("#text_eje_1").value
                        }
                        consultaAjax("../../consultaActiMate",datos,120000,"POST",7,null,7);  
                    }else{
                        $("#persona_a_cargo").find("tr").remove();

                        $("#datos_aux_1").find("tr").remove();
                        $("#datos_aux_2").find("tr").remove();

                        
                        $("#select_nodos_afectados").find("option").remove();
                            document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                        
                    }
                });

                document.querySelector("#select_nodos_afectados").addEventListener("change",function()
                {
                    
                    if($("#select_nodos_afectados").val() == 0){
                        $("#contentprinnodo").hide();
                    }else{  
                        $("#contentprinnodo").show();
                        consultaestadonodo($("#select_nodos_afectados").val());
                    }
                    
                    
                    $("#nodos-add").find("li").remove();
                    @if($encabezado != null)
                    @if($encabezado[0]->id_estado != "E4" && $encabezado[0]->id_estado != "C2")
                        document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                    @endif
                    @endif



                    document.querySelector("#datos_captura_ejecucion").style.display = "none";
                    if(this.selectedIndex != 0)
                    {
                        

                        var datos = 
                        {
                            opc: 9,
                            lid: document.querySelector("#select_lider_carga").value,
                            ot : document.querySelector("#text_eje_1").value,
                            nod: document.querySelector("#select_nodos_afectados").value
                        }
                        consultaAjax("../../consultaActiMate",datos,120000,"POST",8); 
                    }
                    
                });

            document.querySelector("#btn-conci").addEventListener("click",function()
            {
                document.querySelector("#btn_save_guardar_actividad1").style.display = "none";
                $("#modal_captura_conciliacion").modal("toggle");
            });

            //Conciliación
                    document.querySelector("#select_lider_carga1").addEventListener("change",function()
                    {
                        $("#nodos-add1").find("li").remove();
                        document.querySelector("#datos_captura_ejecucion1").style.display = "none";
                        if(this.selectedIndex != 0)
                        {
                            var datos = 
                            {
                                opc: 12,
                                lid: document.querySelector("#select_lider_carga1").value,
                                ot : document.querySelector("#text_conci_2").value
                            }
                            consultaAjax("../../consultaActiMate",datos,120000,"POST",11,null,0);  
                        }else{
                            $("#tbl_persona_a_cargo1").find("tr").remove();
                            $("#datos_aux_1").find("tr").remove();
                            $("#datos_aux_2").find("tr").remove();
                            $("#select_nodos_afectados1").find("option").remove();
                            
                                document.querySelector("#btn_save_guardar_actividad1").style.display = "none";
                            
                        }
                        
                    });

                    document.querySelector("#select_nodos_afectados1").addEventListener("change",function()
                    {
                        $("#nodos-add1").find("li").remove();
                        
                            document.querySelector("#btn_save_guardar_actividad1").style.display = "none";
                        
                        document.querySelector("#datos_captura_ejecucion1").style.display = "none";
                        if(this.selectedIndex != 0)
                        {
                            var datos = 
                            {
                                opc: 9,
                                lid: document.querySelector("#select_lider_carga1").value,
                                ot : document.querySelector("#text_conci_2").value,
                                nod: document.querySelector("#select_nodos_afectados1").value
                            }
                            consultaAjax("../../consultaActiMate",datos,120000,"POST",32); 
                        }
                        
                    });
            
            document.querySelector("#btn_save_guardar_actividad").addEventListener("click",function()
            {
                if(document.querySelector("#select_lider_carga").selectedIndex == 0)
                {
                    mostrarModal(1,null,"Guardar ejecución","Seleccione un líder para guardar la ejecución\n",0,"Aceptar","",null);
                    return;
                }

                if(document.querySelector("#select_nodos_afectados").selectedIndex == 0)
                {
                    mostrarModal(1,null,"Guardar ejecución","Seleccione un nodo para guardar la ejecución\n",0,"Aceptar","",null);
                    return;
                }

              /*  if(document.querySelector("#preplanilla_id").value == "")
                {
                    mostrarModal(1,null,"Guardar ejecución","Ingrese por favor el número de preplanilla\n",0,"Aceptar","",null);
                    return;
                }*/

                var fechaIni = document.querySelector("#datos_aux_1").children[1].children[5].innerHTML.split("-");
                var fechaFin = document.querySelector("#datos_aux_1").children[1].children[6].innerHTML.split("-");

                 var f = document.querySelector("#fech_ejecucionInput").value.split("/");

                var fecahSelect = new Date(f[2],f[1]  - 1,f[0]);

                f = f[2] + "-" + f[1] + "-" + f[0];

                fechaIni = new Date(fechaIni[0],fechaIni[1] - 1,fechaIni[2]);
                fechaFin = new Date(fechaFin[0],fechaFin[1] - 1,fechaFin[2]);

                ocultarSincronizacion();

               /* if(fechaIni > fecahSelect){
                    mostrarModal(1,null,"Cargar ejecución","No puede seleccionar una fecha menor a la de inicio de la ejecución\n",0,"Aceptar","",null);
                    return;
                }

                if(fecahSelect > fechaFin){
                    mostrarModal(1,null,"Cargar ejecución","No puede seleccionar una fecha mayor a la de finalización de la ejecución\n",0,"Aceptar","",null);
                    return;
                }*/

                //Validar Baremos
                var arrayEnviar = [];

                 
                  var dat = [];

                  dat.push({
                    "nodo" : document.querySelector("#select_nodos_afectados").value,
                    "usuario" : document.querySelector("#select_lider_carga").value,
                    "orden" : document.querySelector("#text_eje_1").value,
                    "barem" : arrayEnviar
                  });


                  var tbl1 = document.querySelector("#tbl_mate_eje").children;
                  var arrayEnviar1 = [];

                  for (var i = 0; i < tbl1.length; i++) {
                    if(tbl1[i].children[2].children[0].children[0].value == "")
                    {
                        mostrarModal(1,null,"Guardar ejecución","No puede guardar los materiales, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                        return;
                    }

                    if(tbl1[i].children[2].children[0].children[0].dataset.validado != "1" ||
                        tbl1[i].children[3].children[0].children[0].dataset.validado != "1" ||
                        tbl1[i].children[4].children[0].children[0].dataset.validado != "1" ||
                        tbl1[i].children[5].children[0].children[0].dataset.validado != "1")
                    {
                        mostrarModal(1,null,"Guardar ejecución","No ha terminado de validar todos los materiales.\n",0,"Aceptar","",null);
                        return;
                    }
                    //alert(tbl1[i].children[0].dataset.cod);
                    arrayEnviar1.push({
                      "mat" : tbl1[i].children[0].dataset.cod,
                      "can" : tbl1[i].children[2].children[0].children[0].value,
                      "rz1" : tbl1[i].children[3].children[0].children[0].value,
                      "ch" : tbl1[i].children[4].children[0].children[0].value,
                      "rz" : tbl1[i].children[5].children[0].children[0].value,
                    })

                  };
                  var dat1 = [];

                  dat1.push({
                    "nodo" : document.querySelector("#select_nodos_afectados").value,
                    "usuario" : document.querySelector("#select_lider_carga").value,
                    "orden" : document.querySelector("#text_eje_1").value,
                    "mate" : arrayEnviar1
                  });

                  if(confirm("¿Seguro que desea guardar la ejecución del nodo"))
                  {

                    var dc = 0;
                    try
                    {
                        dc = dcSelec.innerHTML;
                    }
                    catch(err)
                    {
                        dc = dcSelec;
                    }

                    if(dc == undefined)
                        dc = dcSelec;   
                    
                     var datos = 
                        {
                            opc: 8,
                            lid: document.querySelector("#select_lider_carga").value,
                            ot : document.querySelector("#text_eje_1").value,
                            nodo: document.querySelector("#select_nodos_afectados").value,
                            bare : dat,
                            mate : dat1,
                            dc : dc,
                            fecha_consulta : f,
                            pla : document.querySelector("#preplanilla_id").value,
                            plad : document.querySelector("#preplanilla_id_d").value
                        }

                        consultaAjax("../../consultaActiMate",datos,120000,"POST",15); 

                  }
            });

            document.querySelector("#btn_save_eje_fin").addEventListener("click",function()
                {
                    if(confirm("¿Seguro que desea finalizar la ejecución de la maniobra"))
                    {
                        var datos = 
                        {
                            opc: 10,
                            ot : document.querySelector("#text_eje_1").value
                        }

                        consultaAjax("../../consultaActiMate",datos,120000,"POST",16); 
                    }
                });

            
            document.querySelector("#btn_save_guardar_actividad1").addEventListener("click",function()
                {
                    if(document.querySelector("#select_lider_carga1").selectedIndex == 0)
                    {
                        mostrarModal(1,null,"Guardar ejecución","Seleccione un líder para guardar la conciliación\n",0,"Aceptar","",null);
                        return;
                    }

                    if(document.querySelector("#select_nodos_afectados1").selectedIndex == 0)
                    {
                        mostrarModal(1,null,"Guardar ejecución","Seleccione un nodo para guardar la conciliación\n",0,"Aceptar","",null);
                        return;
                    }

                    //Validar Baremos
                    var tbl = document.querySelector("#tbl_baremos_eje1").children;
                    var arrayEnviar = [];

                      for (var i = 0; i < tbl.length; i++) {

                        if(tbl[i].children[2].children[0].children[0].value == "")
                        {
                          mostrarModal(1,null,"Guardar ejecución","No puede guardar los baremos, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                        return;
                        }

                        if(tbl[i].children[2].children[0].children[0].dataset.validado != "1")
                        {
                            mostrarModal(1,null,"Guardar ejecución","No ha terminado de validar todos las actividades.\n",0,"Aceptar","",null);
                            return;
                        }

                        arrayEnviar.push({
                              "bar" : tbl[i].children[0].innerHTML,
                              "can" : tbl[i].children[2].children[0].children[0].value
                            });
                      };
                      var dat = [];

                      dat.push({
                        "nodo" : document.querySelector("#select_nodos_afectados1").value,
                        "usuario" : document.querySelector("#select_lider_carga1").value,
                        "orden" : document.querySelector("#text_conci_2").value,
                        "barem" : arrayEnviar
                      });


                      var tbl1 = document.querySelector("#tbl_mate_eje1").children;
                      var arrayEnviar1 = [];

                      for (var i = 0; i < tbl1.length; i++) {
                        if(tbl1[i].children[2].children[0].children[0].value == "")
                        {
                            mostrarModal(1,null,"Guardar ejecución","No puede guardar los materiales, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                          return;
                        }


                        if(tbl1[i].children[2].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[3].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[4].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[5].children[0].children[0].dataset.validado != "1")
                        {
                            mostrarModal(1,null,"Guardar ejecución","No ha terminado de validar todos los materiales.\n",0,"Aceptar","",null);
                            return;
                        }

                        //alert(tbl1[i].children[0].dataset.cod);
                        arrayEnviar1.push({
                          "mat" : tbl1[i].children[0].dataset.cod,
                          "can" : tbl1[i].children[2].children[0].children[0].value,
                          "rz1" : tbl1[i].children[3].children[0].children[0].value,
                          "ch" : tbl1[i].children[4].children[0].children[0].value,
                          "rz" : tbl1[i].children[5].children[0].children[0].value,
                          "id_autoreg" : tbl1[i].children[0].dataset.idautoreg
                        })

                      };
                      var dat1 = [];

                      dat1.push({
                        "nodo" : document.querySelector("#select_nodos_afectados1").value,
                        "usuario" : document.querySelector("#select_lider_carga1").value,
                        "orden" : document.querySelector("#text_conci_2").value,
                        "mate" : arrayEnviar1
                      });

                      if(confirm("¿Seguro que desea guardar la conciliación del nodo"))
                      {
                          var datos = 
                            {
                                opc: 14,
                                lid: document.querySelector("#select_lider_carga1").value,
                                ot : document.querySelector("#text_conci_2").value,
                                nodo: document.querySelector("#select_nodos_afectados1").value,
                                bare : dat,
                                mate : dat1,
                                dc : dcSelec
                            }

                            consultaAjax("../../consultaActiMate",datos,220000,"POST",17); 
                    }

                });

                document.querySelector("#btn_save_conci_fin").addEventListener("click",function()
                {
                    if(confirm("¿Seguro que desea finalizar la conciliación de la maniobra"))
                    {
                        var datos = 
                        {
                            opc: 15,
                            ot : document.querySelector("#text_conci_2").value
                        }

                        consultaAjax("../../consultaActiMate",datos,120000,"POST",16); 
                    }
                });   
        }

        function validarExisteProyecto1(even)
        {
            var letra = event.which || event.keyCode;
            if(letra == 13)
            {
                if(document.querySelector("#text_eje_1").value == "")
                {
                    return;
                }
                else
                {
                    var array = 
                    {
                        ot : document.querySelector("#text_eje_1").value,
                        opc : "18"
                    }
                    consultaAjax("../../consultaActiMate",array,15000,"POST",4);
                    
                    var blanco = {hora_apertura:'',
                                  operador_ccontrol_abre:'',
                                  hora_cierre_:'',
                                  operador_ccontrol_cierra:'',
                                  id_orden:''};
                    
                     $.ajax({
                              type: 'POST',
                              url: "<?= Request::root() ?>/redes/consultaordenguar",
                              data: {ot:document.querySelector("#text_eje_1").value,
                                     _token:'<?= csrf_token() ?>' },
                              dataType: "json",
                              success: function(data) {
                                      if(data.status == 1){ 
                                              cargainfohora(data.resu)
                                              mensajes("Éxito","Proceso finalizado satisfactoriamente.",1);
                                      }else if(data.status == 0){                                          
                                              cargainfohora(blanco);
                                              mensajes("Error",data.response.message,0); 
                                      }else{ 
                                          cargainfohora(blanco);
                                          mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0); 
                                      }
                              }, 
                              error: function(){ 
                                  cargainfohora(blanco);
                                  mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0);  
                              }
                      }).always(function(){ }); 
                    
                    
                }
            }
        }
        
        
        
        
        function  consultaestadonodo(id_nodo){
            $("#estado_nodo").prop('disabled', false);
        
            $.ajax({
                   type: 'POST',
                   url: "<?= Request::root() ?>/redes/ordenes/consultaestadonodo",
                   data: {id_nodo:id_nodo,
                          _token:'<?= csrf_token() ?>' },
                   dataType: "json",
                   success: function(data) {
                           if(data.status == 1){ 
                                   //mensajes("Éxito","Proceso finalizado satisfactoriamente.",1);

                                   $("#estado_nodo").val(data.estado);
                           }else if(data.status == 0){   
                                   $("#estado_nodo").val("");
                                //   mensajes("Error",data.response.message,0); 
                           }else{ 
                             //  mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0); 
                           }
                   }, 
                   error: function(){ 
                      // mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0);  
                   }
           }).always(function(){              
          });  
        }
        
        function cambiaestado(){
             var nodo = $("#select_nodos_afectados").val();
             var estadon = $("#estado_nodo").val();
                            
             $("#form_estadonodo").find('.btnfrmocd').hide("slow",function(){ 
              
                    $("#form_estadonodo").find('.loadingd').show("slow"); 
                     
                     $.ajax({
                            type: 'POST',
                            url: "<?= Request::root() ?>/redes/ordenes/cambiaestado",
                            data: {id_nodo:nodo,
                                   estadon:estadon,
                                   _token:'<?= csrf_token() ?>' },
                            dataType: "json",
                            success: function(data) {
                                    if(data.status == 1){ 
                                        mensajes("Éxito","Proceso finalizado satisfactoriamente.",1);
                                    }else if(data.status == 0){   
                                        mensajes("Error",data.message,0); 
                                    }else{ 
                                        mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0); 
                                    }
                            }, 
                            error: function(){ 
                               // mensajes("Error","Ocurrió un error por favor inténtalo nuevamente mas tarde.",0);  
                            }
                    }).always(function(){              
                        $("#form_estadonodo").find('.loadingd').hide("slow",function(){$("#form_estadonodo").find('.btnfrmocd').show();}); 
                   });  
             });
        
        }
        
        
        
        function cargainfohora(datos){
             $("#hora_apertura").val(datos['hora_apertura']);
             $("#operador_ccontrol_abre").val(datos['operador_ccontrol_abre']);
             $("#hora_cierre_d").val(datos['hora_cierre_d']);
             $("#operador_ccontrol_cierra").val(datos['operador_ccontrol_cierra']);
             $("#id_orden_hora").val(datos['id_orden']);
        }
        
        function validarExisteProyecto2(even)
        {
            var letra = event.which || event.keyCode;
            if(letra == 13)
            {
                if(document.querySelector("#text_conci_2").value == "")
                {
                    return;
                }
                else
                {
                    var array = 
                    {
                        ot : document.querySelector("#text_conci_2").value,
                        opc : "18"
                    }
                    consultaAjax("../../consultaActiMate",array,15000,"POST",5);
                }
            }

        }

        function save_gom_upload(evt)
        {
            var fila = document.querySelector("#file_upload").value;
            if(fila == "")
            {
                mostrarModal(1,null,"Cargar archivo GOM","No ha seleccionado ningún archivo para cargar.\n",0,"Aceptar","",null);
                window.event.preventDefault();
            }   

//            document.querySelector("#txt_estado").value = document.querySelector("#text_descargo_add").value;
            document.querySelector("#txt_proy").value = document.querySelector("#text_proyecto").dataset.cod;
            
        }


        function consultaWBS()
        {

            var spanWBS = $(".ejecucion_wbs_span");
            for (var i = 0; i < spanWBS.length; i++) {
                var array = 
                {
                    proy : spanWBS[i].dataset.proyecto,
                    opc : "21"
                }
                
                consultaAjax("../../consultaActiMate",array,15000,"POST",6,spanWBS[i].dataset.cantwl,-1,spanWBS[i]);
            }; 
        }

        //Descarga EXCEL GOMS
        function downloadPrePlanillas()
        {
            if(document.querySelector("#fec_desde_goms").value == "" ||
                document.querySelector("#fec_hasta_goms").value == "" )
            {
                mostrarModal(1,null,"Exporte de seguimiento de preplanillas","Tiene que ingresar la fecha DESDE y HASTA para generar el reporte.\n",0,"Aceptar","",null); 
                return;
            }

            document.querySelector("#fecha1_goms").value = document.querySelector("#fec_desde_goms").value.split("/")[2] + "-" + document.querySelector("#fec_desde_goms").value.split("/")[1]  + "-" + document.querySelector("#fec_desde_goms").value.split("/")[0];
            document.querySelector("#fecha2_goms").value = document.querySelector("#fec_hasta_goms").value.split("/")[2] + "-" + document.querySelector("#fec_hasta_goms").value.split("/")[1]  + "-" + document.querySelector("#fec_hasta_goms").value.split("/")[0];
            document.querySelector("#form_donwload_goms").submit();
        }

        function formato_download()
        {
            var ele = document.querySelector("#download_format").parentElement;
            ele.children[1].submit();
        }

        var tipoIngresoDCNodo = 0;
        function consultaAjax(route,datos,tiempoEspera,type,opcion,collback,dato,ele)
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
                    if(opcion == "1") //Update GOM INDIVIDUAL
                    {
                        mostrarModal(2,null,"Estados GOM","El cambio de estado de la GOM, se ha hecho correctemante.\n",0,"Aceptar","",null); 
                        document.querySelector("#tbl_descargos_asociadas").innerHTML = data;

                        var select = $("#gom_cam select");
                        for (var i = 0; i < select.length; i++) {
                            select[i].addEventListener("change",function()
                            {
                                var array = 
                                {
                                    gom : this.parentElement.parentElement.children[0].innerHTML,
                                    esta: this.value,
                                    opc : "16"
                                }
                                consultaAjax("../../consultaActiMate",array,15000,"POST",1);
                            });
                        };

                    }
                    if(opcion == "2") //cambio Change Estados GOM
                    {
                        document.querySelector("#tbl_descargos_asociadas").innerHTML = data;

                        var alto = screen.height - 400;
                        var altopx = alto+"px";

                        $('#gom_cam').dataTable({
                            "scrollX":  "100%",
                            "scrolY":   altopx,
                            "paging":   true,
                            "searching": true,
                            "responsive":      false,
                            "colReorder":      true,
                            dom: 'T <"clear">lfrtip',
                            tableTools: {
                                "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                            }
                        }); 
                        var select = $("#gom_cam select");
                        for (var i = 0; i < select.length; i++) {
                            select[i].addEventListener("change",function()
                            {
                                var array = 
                                {
                                    gom : this.parentElement.parentElement.children[0].innerHTML,
                                    esta: this.value,
                                    opc : "16"
                                }
                                consultaAjax("../../consultaActiMate",array,15000,"POST",1);
                            });
                        };
                        ocultarSincronizacion();
                        
                    }

                    if(opcion == 3) //Consulta Balances
                    {
                        if(data == "0")
                        {
                             ocultarSincronizacion();
                            mostrarModal(1,null,"Balances","No existe el número de DC.",0,"Aceptar","",null); 
                            return;
                        }

                        if(data == "-1")
                        {
                             ocultarSincronizacion();
                            mostrarModal(1,null,"Balances","No existe el número de ManiObra.",0,"Aceptar","",null); 
                            return;
                        }

                        if(data == "-2")
                        {
                             ocultarSincronizacion();
                            mostrarModal(1,null,"Balances","La ManiObra todavía no se ha ejecutado en su totalidad.",0,"Aceptar","",null); 
                            return;
                        }

                        if(data == "-3")
                        {
                             ocultarSincronizacion();
                            mostrarModal(1,null,"Balances","La ManiObra esta anulada.",0,"Aceptar","",null); 
                            return;
                        }

                        if(data == "-4")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Balances","La maniobra se encuentra en estado generado.",0,"Aceptar","",null); 
                            return;
                        }

                        if(data == "-5")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Balances","Existen DC sin confirmar.",0,"Aceptar","",null); 
                            return;
                        }


                        document.querySelector("#datos_balance").style.display = "block";
                        document.querySelector("#datos_balance").innerHTML = data;
                        $("#baremo_add_eje").DataTable(
                        {
                            dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        title: 'Balances'
                                    }                                      
                                ]
                            
                        }
                        );
                        ocultarSincronizacion();
                    }

                    if(opcion == 4) //Carga proyecto ejecuccion
                    {
                        document.querySelector("#tbl_persona_cargo").innerHTML = "";
                        document.querySelector("#btn_save_eje_fin").style.display = "none";
                        if(data == "0")
                        {
                            mostrarModal(1,null,"Consulta ManiObra(OT)","No existe la ManiObra(OT).\n",0,"Aceptar","",null);
                            document.querySelector("#select_lider_carga").innerHTML = "";
                            document.querySelector("#select_nodos_afectados").innerHTML = "";
                            document.querySelector("#nodos-add").innerHTML = "";
                            document.querySelector("#datos_captura_ejecucion").innerHTML = "";
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "0";
                            document.querySelector("#btn_save_eje_fin").style.display = "none";
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == "-1")
                        {
                            mostrarModal(1,null,"Consulta ManiObra(OT)","La orden aún no ha sido programada.\n",0,"Aceptar","",null);
                            document.querySelector("#select_lider_carga").innerHTML = "";
                            document.querySelector("#select_nodos_afectados").innerHTML = "";
                            document.querySelector("#nodos-add").innerHTML = "";
                            document.querySelector("#datos_captura_ejecucion").innerHTML = "";
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "0";
                            document.querySelector("#btn_save_eje_fin").style.display = "none";
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == "-2")
                        {
                            mostrarModal(1,null,"Consulta ManiObra(OT)","La orden ha sido anulada.\n",0,"Aceptar","",null);
                            document.querySelector("#select_lider_carga").innerHTML = "";
                            document.querySelector("#select_nodos_afectados").innerHTML = "";
                            document.querySelector("#nodos-add").innerHTML = "";
                            document.querySelector("#datos_captura_ejecucion").innerHTML = "";
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "0";
                            document.querySelector("#btn_save_eje_fin").style.display = "none";
                            ocultarSincronizacion();
                            return;
                        }

                        document.querySelector("#select_lider_carga").innerHTML = "";
                        document.querySelector("#select_nodos_afectados").innerHTML = "";
                        document.querySelector("#nodos-add").innerHTML = "";
                        document.querySelector("#datos_captura_ejecucion").innerHTML = "";
                        document.querySelector("#tbl_index_proyectos").dataset.pos = "0";

                        if(data[1] == "E2")
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "1";
                        else
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "0";

                        var html = "";
                        html += "<option value='0'>Seleccione</option>";
                        for (var i = 0; i < data[0].length; i++) {
                            html += "<option value='" + data[0][i].id_lider + "'>";
                            html += data[0][i].nombre
                            html += "</option>";
                        };
                        document.querySelector("#select_lider_carga").innerHTML = html;
                        ocultarSincronizacion();
                    }

                    if(opcion == 5) //Carga proyecto conciliación
                    {
                        document.querySelector("#btn_save_conci_fin").style.display = "none";
                        document.querySelector("#tbl_persona_cargo1").innerHTML = "";
                        if(data == "0")
                        {
                            mostrarModal(1,null,"Consulta ManiObra(OT)","No existe la ManiObra(OT).\n",0,"Aceptar","",null);
                            document.querySelector("#select_lider_carga1").innerHTML = "";
                            document.querySelector("#select_nodos_afectados1").innerHTML = "";
                            document.querySelector("#nodos-add1").innerHTML = "";
                            document.querySelector("#datos_captura_ejecucion1").innerHTML = "";
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "0";
                            document.querySelector("#btn_save_conci_fin").style.display = "none";
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == "-1")
                        {
                            mostrarModal(1,null,"Consulta ManiObra(OT)","La orden aún no ha sido programada.\n",0,"Aceptar","",null);
                            document.querySelector("#select_lider_carga1").innerHTML = "";
                            document.querySelector("#select_nodos_afectados1").innerHTML = "";
                            document.querySelector("#nodos-add1").innerHTML = "";
                            document.querySelector("#datos_captura_ejecucion1").innerHTML = "";
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "0";
                            document.querySelector("#btn_save_conci_fin").style.display = "none";
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == "-2")
                        {
                            mostrarModal(1,null,"Consulta ManiObra(OT)","La orden ha sido anulada.\n",0,"Aceptar","",null);
                            document.querySelector("#select_lider_carga1").innerHTML = "";
                            document.querySelector("#select_nodos_afectados1").innerHTML = "";
                            document.querySelector("#datos_captura_ejecucion1").innerHTML = "";
                            document.querySelector("#nodos-add1").innerHTML = "";
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "0";
                            document.querySelector("#btn_save_conci_fin").style.display = "none";
                            ocultarSincronizacion();
                            return;
                        }

                        
                        document.querySelector("#btn_save_guardar_actividad1").style.display = "none";
                        document.querySelector("#select_lider_carga1").innerHTML = "";
                        document.querySelector("#select_nodos_afectados1").innerHTML = "";
                        document.querySelector("#nodos-add1").innerHTML = "";
                        document.querySelector("#datos_captura_ejecucion1").innerHTML = "";
                        document.querySelector("#tbl_index_proyectos").dataset.pos = "0";

                        if(data[1] == "E2")
                        {
                            mostrarModal(1,null,"Cargar conciliación","La ManiObra aún no se le ha cargado la ejecución.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data[1] == "E4")
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "2";
                        else
                            document.querySelector("#tbl_index_proyectos").dataset.pos = "0";

                        var html = "";
                        html += "<option value='0'>Seleccione</option>";
                        for (var i = 0; i < data[0].length; i++) {
                            html += "<option value='" + data[0][i].id_lider + "'>";
                            html += data[0][i].nombre
                            html += "</option>";
                        };
                        document.querySelector("#select_lider_carga1").innerHTML = html;
                        ocultarSincronizacion();
                    }

                    if(opcion == 6) //Consulta Ejecución WBS
                    {
                        ele.innerHTML = data;
                        if(ele.innerHTML != "0")
                            ele.parentElement.style.width =   parseInt(data)*100/parseInt(collback)+ "%";                 
                    }

                    if(opcion == 7) //Consulta Nodos Afectado
                    {
                        $("#tbl_persona_cargo").html(data);
                        if(data.length == 98)
                        {
                            document.querySelector("#datos_captura_ejecucion").style.display = "none";
                            document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                            ocultarSincronizacion();
                            document.querySelector("#btn_save_eje_fin").style.display = "none";
                            return;
                        }
                        var hijoTbl = document.querySelector("#persona_a_cargo").children;
                        var html = "";
                        var array = [];
                        var exis = 0;
                        html += "<option value='0'>Seleccione</option>";
                        var cadenaEstado = "";
                        for (var i = 0; i < hijoTbl.length; i++) {
                            exis = 0;
                            for (var j = 0; j < array.length; j++) {
                                if(array[j] == hijoTbl[i].children[1].dataset.nodo)
                                    exis =1;
                            };

                            if(exis == 0)
                            {
                                if(hijoTbl[i].children[1].dataset.estado == "E")
                                    cadenaEstado = " - EJECUTADO";

                                if(hijoTbl[i].children[1].dataset.estado == "C")
                                    cadenaEstado = " - CANCELADO";

                                if(hijoTbl[i].children[1].dataset.estado == "R")
                                    cadenaEstado = " - REPROGRAMADO";

                                if(hijoTbl[i].children[1].dataset.estado == "NA")
                                    cadenaEstado = "";

                                html += "<option value='" + hijoTbl[i].children[1].dataset.nodo + "'>NODO " + hijoTbl[i].children[1].innerHTML + "" + cadenaEstado  +  "</option>"

                                array.push(hijoTbl[i].children[1].dataset.nodo);
                            }

                        };
                        if(document.querySelector("#tbl_index_proyectos").dataset.pos == "1")
                            document.querySelector("#btn_save_eje_fin").style.display = "block";
                        else
                            document.querySelector("#btn_save_eje_fin").style.display = "none";
                        $("#select_nodos_afectados").html(html);
                        document.querySelector("#datos_captura_ejecucion").style.display = "none";
                            document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                        
                        ocultarSincronizacion();
                    }

                    if(opcion == 8) //Consulta DC
                    {
                        if(data == "0")
                        {
                            tipoIngresoDCNodo = 1;
                            var tipoPRY = document.querySelector("#tipo_proyecto_id").dataset.tipo;
                            if(tipoPRY == "T03")
                            {
                                var fechaE = document.querySelector("#fech_ejecucionInput").value;

                                if(fechaE == "")
                                {
                                    mostrarModal(1,null,"Consultar ejecución","Ingrese la fecha de ejecución, para consultar la información." + nodo + ".\n",0,"Aceptar","",null);
                                    return;
                                }
                                
                                fechaE = fechaE.split("/")[2] + "-" + fechaE.split("/")[1] + "-" + fechaE.split("/")[0];
                                var datos = 
                                {
                                    opc: 6,
                                    lid: document.querySelector("#select_lider_carga").value,
                                    ot : document.querySelector("#text_eje_1").value,
                                    nodo: document.querySelector("#select_nodos_afectados").value,
                                    fecha_consulta : fechaE,
                                    dc : "-1"
                                } 

                            }
                            else
                            {
                                var datos = 
                                {
                                    opc: 6,
                                    lid: document.querySelector("#select_lider_carga").value,
                                    ot : document.querySelector("#text_eje_1").value,
                                    nodo: document.querySelector("#select_nodos_afectados").value,
                                    dc : "-1"
                                }    
                            }
                            

                            consultaAjax("../../consultaActiMate",datos,120000,"POST",9);
                        }
                        else
                        {
                            tipoIngresoDCNodo = 2;
                            $("#nodos-add").find('li').remove();
                            var html = "";
                            for (var i = 0; i < data.length; i++) {
                                html += "<li onclick='selectDc(this)'>" + data[i].id_documento + "</li>";
                            };
                            $("#nodos-add").html(html);

                            ocultarSincronizacion();     
                        }
                    }

                    if(opcion == 9) // Consulta datos
                    {
                        $("#datos_captura_ejecucion").html(data);

                        var input = $("#datos_captura_ejecucion input");
                        for (var i = 0; i < input.length; i++) {
                            if(input[i].dataset.num != null)
                            {
                                input[i].addEventListener("keydown",validaIngreso2);  
                                
                                /*input[i].addEventListener("keypress",function(e)
                                {
                                  var keynum = null;
                                  if(window.event) { // IE                    
                                    keynum = e.keyCode;
                                  } else if(e.which){ // Netscape/Firefox/Opera                   
                                    keynum = e.which;
                                  }

                                  if(parseInt(this.value + "" + String.fromCharCode(keynum)) > parseInt(this.dataset.max))
                                  {
                                        var ev = event || window.event;
                                        ev.preventDefault();
                                        mostrarModal(1,null,"Guardar ejecución","No puede ingresar un valor mayor al programado.\n",0,"Aceptar","",null);
                                        return;
                                  }
                                });*/
                            }
                        };

                        var input = $("#tbl_baremos input");

                        //if(document.querySelector("#tbl_index_proyectos").dataset.pos == "1")
                            document.querySelector("#btn_save_guardar_actividad").style.display = "inline-block";
                            
                        
                        document.querySelector("#datos_captura_ejecucion").style.display = "inline-block";
                        ocultarSincronizacion();
                    }

                    if(opcion == 10) //Save Materiales / Baremos
                    {
                        var person = document.querySelector("#select_lider_carga").options[document.querySelector("#select_lider_carga").selectedIndex].text;

                        var doc = document.querySelector("#select_lider_carga").value;

                        var nodo = document.querySelector("#select_nodos_afectados").options[document.querySelector("#select_nodos_afectados").selectedIndex].text;

                        $("#tbl_persona_cargo").html(data);
                        
                        mostrarModal(2,null,"Guardar ejecución","Se ha guardado correctamente la ejecución del nodo " + nodo + ".\n",0,"Aceptar","",null);
                        ocultarSincronizacion();
                    }

                    if(opcion == 11) //Consulta Nodos Afectado Conciliación
                    {
                        $("#tbl_persona_cargo1").html(data);
                        if(data.length == 98)
                        {
                            document.querySelector("#datos_captura_ejecucion1").style.display = "none";
                            ocultarSincronizacion();
                            document.querySelector("#btn_save_conci_fin").style.display = "none";
                            return;
                        }
                        var hijoTbl = document.querySelector("#persona_a_cargo1").children;
                        var html = "";
                        var array = [];
                        var exis = 0;
                        html += "<option value='0'>Seleccione</option>";
                        for (var i = 0; i < hijoTbl.length; i++) {
                            exis = 0;
                            for (var j = 0; j < array.length; j++) {
                                if(array[j] == hijoTbl[i].children[1].dataset.nodo)
                                    exis =1;
                            };

                            if(exis == 0)
                            {
                                html += "<option value='" + hijoTbl[i].children[1].dataset.nodo + "'>NODO " + hijoTbl[i].children[1].innerHTML + "</option>"

                                array.push(hijoTbl[i].children[1].dataset.nodo);
                                }

                        };
                        if(document.querySelector("#tbl_index_proyectos").dataset.pos == "2")
                            document.querySelector("#btn_save_conci_fin").style.display = "block";
                        else
                            document.querySelector("#btn_save_conci_fin").style.display = "none";

                        $("#select_nodos_afectados1").html(html);
                        document.querySelector("#datos_captura_ejecucion1").style.display = "none";
                       
                            document.querySelector("#btn_save_guardar_actividad1").style.display = "none";
                        
                        ocultarSincronizacion();
                    }

                    if(opcion == 31) // Comsulta datos Conciliación
                    {
                        $("#datos_captura_ejecucion1").html(data);

                        var input = $("#datos_captura_ejecucion1 input");
                        for (var i = 0; i < input.length; i++) {
                            if(input[i].dataset.num != null)
                            {
                             
                                input[i].addEventListener("keydown",validaIngreso);  
                                input[i].addEventListener("keypress",function(e)
                                {
                                  var keynum = null;
                                  if(window.event) { // IE                    
                                    keynum = e.keyCode;
                                  } else if(e.which){ // Netscape/Firefox/Opera                   
                                    keynum = e.which;
                                  }

                                  if(parseInt(this.value + "" + String.fromCharCode(keynum)) > parseInt(this.dataset.max))
                                  {
                                    var ev = event || window.event;
                                    ev.preventDefault();
                                    mostrarModal(1,null,"Guardar ejecución","No puede ingresar un valor mayor al programado.\n",0,"Aceptar","",null);
                                    return;
                                  }
                                });
                            }
                        };

                        var input = $("#tbl_baremos1 input");

                        if(document.querySelector("#tbl_index_proyectos").dataset.pos == "2")
                            document.querySelector("#btn_save_guardar_actividad1").style.display = "inline-block";
                            
                        
                        document.querySelector("#datos_captura_ejecucion1").style.display = "inline-block";
                        ocultarSincronizacion();
                    }


                    if(opcion == 32) //Consulta DC Conciliación
                    {

                        

                        ocultarSincronizacion(); 
                        if(data == "0")
                        {
                            var datos = 
                            {
                                opc: 13,
                                lid: document.querySelector("#select_lider_carga1").value,
                                ot : document.querySelector("#text_conci_2").value,
                                nodo: document.querySelector("#select_nodos_afectados1").value,
                                dc : "-1"
                            }

                            consultaAjax("../../consultaActiMate",datos,120000,"POST",31);
                        }
                        else
                        {
                            $("#nodos-add1").find('li').remove();
                            var html = "";
                            for (var i = 0; i < data.length; i++) {
                                html += "<li onclick='selectDc1(this)'>" + data[i].id_documento + "</li>";
                            };
                            $("#nodos-add1").html(html);

                            ocultarSincronizacion();    
                        }

                    }

                    if(opcion == 12) // Consulta proyectos
                    {
                        var html = "";
                        for (var i = 0; i < data.length; i++) {
                            html += "<tr>";
                            html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarProyecto(this)' data-proy='" + data[i].id_proyecto +"' data-nombre = '" + data[i].nombre + "'></i></td>";
                            html += "<td>" + data[i].id_proyecto + "</td>";
                            html += "<td>" + data[i].nombre + "</td>";
                            html += "</tr>";
                        };
                        $("#tbl_mate_add").html(html);
                        ocultarSincronizacion(); 
                    }

                    
                    if(opcion == 13) // Comsulta GOMS proyecto
                    {
                        var html = "";
                        for (var i = 0; i < data.length; i++) {
                            html += "<tr>";
                            html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarProyecto(this)' data-proy='" + data[i].id_proyecto +"' data-nombre = '" + data[i].nombre + "'></i></td>";
                            html += "<td>" + data[i].id_proyecto + "</td>";
                            html += "<td>" + data[i].nombre + "</td>";
                            html += "</tr>";
                        };
                        $("#tbl_mate_add").html(html);

                        ocultarSincronizacion(); 
                    }

                    if(opcion == 14) //Finalizar Orden Ejecución
                    {
                        if(data == "1")
                        {
                            mostrarModal(2,null,"Ejecución orden","Se ha finalizado la orden existosamente.\n",0,"Aceptar","",null);

                            setTimeout(function()
                            {
                                window.location.reload();
                            },2000);
                            return;
                        }
                        else
                        {
                            var dt = data.split("#");
                            var mes = "";

                            if(dt[2] != "" && dt[2].length > 10)
                            {
                                mes =  mes + "Las siguiente personas les hace falta capturar las actividades realizadas : " + dt[2];   
                            }

                            if(dt[1] != "" && dt[1].length > 10)
                            {
                                mes =  mes + "\n Las siguiente personas les hace falta capturar los materiales utilizado : " + dt[1];
                            }

                            if(dt[0] == "1")
                                mostrarModal(1,null,"No puede finalizar la ejecución de la orden",mes,0,"Aceptar","",null);
                            else
                                mostrarModal(1,null,"No puede finalizar la conciliación de la orden",mes,0,"Aceptar","",null);
                        }
                        ocultarSincronizacion();
                    }

                    if(opcion == 15) //Save Materiales / Baremos Ejecución
                    {
                        var person = document.querySelector("#select_lider_carga").options[document.querySelector("#select_lider_carga").selectedIndex].text;

                        var doc = document.querySelector("#select_lider_carga").value;

                        var nodo = document.querySelector("#select_nodos_afectados").options[document.querySelector("#select_nodos_afectados").selectedIndex].text;

                        $("#tbl_persona_cargo").html(data);
                        
                        mostrarModal(2,null,"Guardar ejecución","Se ha guardado correctamente la ejecución del nodo " + nodo + ".\n",0,"Aceptar","",null);
                        ocultarSincronizacion();
                    }

                    if(opcion == 16) //Finalizar Orden Ejecución
                    {
                        if(data == "1")
                        {
                            mostrarModal(2,null,"Ejecución orden","Se ha finalizado la orden existosamente.\n",0,"Aceptar","",null);

                            setTimeout(function()
                            {
                                window.location.reload();
                            },2000);
                            return;
                        }
                        else
                        {
                            var dt = data.split("#");
                            var mes = "";

                            if(dt[2] != "" && dt[2].length > 10)
                            {
                                mes =  mes + "Las siguiente personas les hace falta capturar las actividades realizadas : " + dt[2];   
                            }

                            if(dt[1] != "" && dt[1].length > 10)
                            {
                                mes =  mes + "\n Las siguiente personas les hace falta capturar los materiales utilizado : " + dt[1];
                            }

                            if(dt[0] == "1")
                                mostrarModal(1,null,"No puede finalizar la ejecución de la orden",mes,0,"Aceptar","",null);
                            else
                                mostrarModal(1,null,"No puede finalizar la conciliación de la orden",mes,0,"Aceptar","",null);
                        }
                        ocultarSincronizacion();
                    }

                    if(opcion == 17) //Save Materiales / Baremos
                    {
                        $("#tbl_persona_cargo1").html(data);                        
                        mostrarModal(2,null,"Guardar ejecución","Se ha guardado correctamente la conciliación del nodo .\n",0,"Aceptar","",null);
                        ocultarSincronizacion();
                    }
                    if(opcion == 18) //Consulta proyecto
                    {
                        var html = "";

                        if(data.length == 0)
                        {
                            html += "<tr>";
                            html += "<td colspan='4'>No existen proyectos</td>";
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
                        ocultarSincronizacion();
                    }

                    if(opcion == 20) //Carga baremos
                    {
                        var html = "";
                        for (var i = 0; i < data.length; i++) {
                            
                            html += "<tr>";
                            html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarTablaBaremo(this)' data-bare='" + data[i].bare +"' data-acti = '" + data[i].actividad + "' data-precio = '" + data[i].precio + "'></i></td>";
                            html += "<td>" + data[i].bare + "</td>";
                            html += "<td>" + data[i].actividad + "</td>";
                            html += "<td> $" + data[i].precio + "</td>";
                            html += "</tr>";
                        };
                        
                        $("#tbl_baremos").html(html);
                        ocultarSincronizacion();
                    }


                    if(opcion == 22) //ADD Actividades / Materiales
                    {
                        if(data == "0")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Agregar Baremo","Usted ya tiene el baremo agregado al nodo seleccionado.\n",0,"Aceptar","",null);
                            return;
                        }

                        if(data == "3")
                        {
                            ocultarSincronizacion();
                            $("#modal_acti").modal("toggle");

                            document.querySelector("#select_nodos_afectados").selectedIndex = 0;
                            
                            document.querySelector("#datos_captura_ejecucion").style.display = "none";

                            document.querySelector("#btn_save_guardar_actividad").style.display = "none";

                            setTimeout(function()
                            {
                                $('#modal_captura_ejecucion').modal('toggle'); 
                            },900);

                            mostrarModal(2,null,"Agregar Baremo","Se ha agregado correctamente el baremo al nodo seleccionado.\n",0,"Aceptar","",null);
                        }
                    }

                    if(opcion == 23) //Consulta material asignado
                    {
                        eleSelec.parentElement.parentElement.children[4].innerHTML = "";
                        if(data > 0)
                        {
                            var html= '<a class="btn btn-primary btn-cam-trans btn-sm" title="Planilla Materiales" target="_blank" href="{{config("app.Campro")[2]}}/campro/gop/{{ explode("_",\Session::get('proy_short'))[0]}}/pdf_planilla_materiales_programados.php?id_orden=' + ordenSelec + '&id_lider=' + liderSelec + '&tipo=1"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';
                            eleSelec.parentElement.parentElement.children[4].innerHTML = html;
                        }   
                        ocultarSincronizacion();    
                    }

                    if(opcion == 24) //Update NODO
                    {
                        mostrarModal(2,null,"Actualizar nodo","Se ha actualizado correctamente el nodo.\n",0,"Aceptar","",null);
                        ocultarSincronizacion();    
                    }

                    if(opcion == 25) //Carga baremos
                    {
                        var html = "";
                        for (var i = 0; i < data.length; i++) {
                                html += "<option value='" + data[i].bare + "'>" + data[i].bare + " - " +  data[i].actividad + "</option>";
                        };
                        
                        $("#bareSelect").html(html);
                        ocultarSincronizacion();
                    }

                    if(opcion == 26) //Guardar baremos extras
                    {
                        if(data.res == 1)
                        {
                            mostrarModal(2,null,"Agregar baremos","Se agregar correctamente los baremos a la OT.\n",0,"Aceptar","",null);
                        }
                        else
                        {
                            mostrarModal(1,null,"Agregar baremoso","Los siguientes baremos ya existen en la OT: \n" + data.bare,0,"Aceptar","",null);
                        }


                        $("#modal_acti_add_nuevo_modelo").modal("toggle");
                        setTimeout(function()
                        {
                            $("#modal_captura_ejecucion").modal("toggle");
                        },700);

                        ocultarSincronizacion();
                    }


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

        function agregarTablaBaremoNuevo(ele)
        {

        }

        function seleccionarBaremo()
        {
            if(document.querySelector("#bareSelect").selectedIndex == -1 || document.querySelector("#bareSelect").selectedIndex == undefined)
            {
                mostrarModal(1,null,"Selecciaonar baremos","Seleccione un baremo\n",0,"Aceptar","",null);
                return;
            }

            var codigo = document.querySelector("#bareSelect").value;
            var actividad = document.querySelector("#bareSelect").options[document.querySelector("#bareSelect").selectedIndex].text;

            var hijos = document.querySelector("#datos_aux_bare").children;
            var sel = 0;
            for (var i = 0; i < hijos.length; i++) {
                if(hijos[i].children[0].innerText == codigo)
                    sel = 1;
            }

            if(sel == 0)
            {
                $("#datos_aux_bare").append("<tr> <td>" + codigo + " </td> <td> " + actividad +  "</td>  <td> <input type='number' /></td> <td> <i onclick='eliminarBaremo(this)' style='color:red' class='fa fa-times' aria-hidden='true'></i></td> </tr>");    
            }else{
                mostrarModal(1,null,"Agregar baremo","Ya tiene este baremo seleccionado\n",0,"Aceptar","",null);
            }
            
        }

        function eliminarBaremo(ele)
        {
            $(ele.parentElement.parentElement.parentElement).find(ele.parentElement.parentElement).remove();
        }


        function saveAgregarActividades()
        {
            var hijos = document.querySelector("#datos_aux_bare").children;
            var sel = "";

            var baremos = [];
            for (var i = 0; i < hijos.length; i++) {
                if(hijos[i].children[2].children[0].value == "")
                {
                    sel =  hijos[i].children[1].innerText;
                    break;
                }

                baremos.push(
                {
                    codigo: hijos[i].children[0].innerText,
                    cant: hijos[i].children[2].children[0].value,
                });
            }

            if(sel != "")
            {   
                mostrarModal(1,null,"Agregar baremo","Hace falta ingresar las cantidades del baremo:\n" + sel,0,"Aceptar","",null);
                return;
            }


            var f = document.querySelector("#fech_ejecucionInput").value.split("/");
            f = f[2] + "-" + f[1] + "-" + f[0];

            var datos = [];
            if(document.querySelector("#tipo_proyecto_id").dataset.tipo == "T03")
            {
                if(confirm("Desea guardar los baremos seleccionados para la fecha de ejecución " + f))
                {
                    datos = 
                    {
                        opc: 20,
                        nodo: document.querySelector("#select_nodos_afectados").value,
                        cargo : document.querySelector("#select_lider_carga").value,
                        bare: baremos,
                        orden: document.querySelector("#text_eje_1").value,
                        fecha_consulta : f
                    };
                }
            }
            else
            {
                datos = 
                    {
                        opc: 20,
                        nodo: document.querySelector("#select_nodos_afectados").value,
                        cargo : document.querySelector("#select_lider_carga").value,
                        bare: baremos,
                        orden: document.querySelector("#text_eje_1").value,
                        fecha_consulta : f
                    };
            }
            
            
            console.log(datos);
            consultaAjax("../../guardarAsignacionRecursosMateriales",datos,120000,"POST",26);   


        }

        function cerrarModalNuevo()
        {
            $("#modal_acti_add_nuevo_modelo").modal("toggle");
            setTimeout(function()
            {
                $("#modal_captura_ejecucion").modal("toggle");
            },700);
        }


        var dcSelec = null;
        var elementoSeleccionadoDC = null;
        function selectDc(ele)
        {

            var nodDc = document.querySelector("#nodos-add").children;
            for (var i = 0; i < nodDc.length; i++) {
                $(nodDc[i]).removeClass('select')
            };

            $(ele).addClass('select');
            elementoSeleccionadoDC = ele;

            var tipoPRY = document.querySelector("#tipo_proyecto_id").dataset.tipo;
            if(tipoPRY == "T03")
            {
                var fechaE = document.querySelector("#fech_ejecucionInput").value;

                if(fechaE == "")
                {
                    mostrarModal(1,null,"Consultar ejecución","Ingrese la fecha de ejecución, para consultar la información." + nodo + ".\n",0,"Aceptar","",null);
                    return;
                }
                
                fechaE = fechaE.split("/")[2] + "-" + fechaE.split("/")[1] + "-" + fechaE.split("/")[0];

                var datos = 
                {
                    opc: 6,
                    lid: document.querySelector("#select_lider_carga").value,
                    ot : document.querySelector("#text_eje_1").value,
                    nodo: document.querySelector("#select_nodos_afectados").value,
                    fecha_consulta : fechaE,
                    dc : ele.innerHTML
                }

            }
            else
            {
                var datos = 
                {
                    opc: 6,
                    lid: document.querySelector("#select_lider_carga").value,
                    ot : document.querySelector("#text_eje_1").value,
                    nodo: document.querySelector("#select_nodos_afectados").value,
                    dc : ele.innerHTML
                }  
            }           

            dcSelec = ele.innerHTML;
            consultaAjax("../../consultaActiMate",datos,120000,"POST",9);   
        }

        function selectDc1(ele)
        {
            var nodDc = document.querySelector("#nodos-add1").children;
            for (var i = 0; i < nodDc.length; i++) {
                $(nodDc[i]).removeClass('select')
            };

            $(ele).addClass('select');

            var datos = 
            {
                opc: 13,
                lid: document.querySelector("#select_lider_carga1").value,
                ot : document.querySelector("#text_conci_2").value,
                nodo: document.querySelector("#select_nodos_afectados1").value,
                dc : ele.innerHTML
            }

            dcSelec = ele.innerHTML;
            consultaAjax("../../consultaActiMate",datos,120000,"POST",31);   
        }

        function consultaFiltro(evt)
        {
            var evento = evt || window.event;
            if(document.querySelector("#fecha_inicio").value == "" &&
                document.querySelector("#fecha_corte").value == "" &&
                document.querySelector("#proyecto").value == "" &&
                document.querySelector("#proyectoN").value == "")
            {
                mostrarModal(1,null,"Filtro","Ingrese por lo menos un filtro\n",0,"Aceptar","",null);
                evento.preventDefault();
                return;
            }
            mostrarSincronizacion();
        }

        //PROYECTO
        function consultaProyectoAdd()
        {   
            $("#modal_gom").modal("toggle");   
            document.querySelector("#tbl_mate_add").innerHTML = "";
            document.querySelector("#text_mate_search").value = "";
            document.querySelector("#text_mate_des_seacrh").value = "";
            setTimeout(function()
            {
                $("#modal_add_proyecto_gom").modal("toggle");   
            },500);
        }

        function volverModalMaterial()
        {
            $("#modal_add_proyecto_gom").modal("toggle"); 
            setTimeout(function()
            {
                $("#modal_gom").modal("toggle");   
            },500);

        }

        function consultaProyectoAddTable()
        {
            var datos = 
            {
                opc: 19,
                num: document.querySelector("#text_mate_search").value,
                nombre : document.querySelector("#text_mate_des_seacrh").value
            }
            consultaAjax("../../consultaActiMate",datos,120000,"POST",12);  
        }

        function agregarProyecto(ele)
        {
            $("#modal_add_proyecto_gom").modal("toggle"); 
            document.querySelector("#text_proyecto").value = ele.dataset.nombre;
            document.querySelector("#text_proyecto").dataset.cod = ele.dataset.proy;
            //Consulta GOMS proyecto
            var datos = 
            {
                opc: 20,
                proy: ele.dataset.proy
            }
            consultaAjax("../../consultaActiMate",datos,120000,"POST",2); 

            setTimeout(function()
            {
                $("#modal_gom").modal("toggle");   
            },500);
        }

        var modalPRYSelect = null;
        function abrirModal(opc)
        {
            modalPRYSelect = opc;

            $("#modal_balances").modal("toggle");
            setTimeout(function()
            {
                $("#modal_proyecto").modal("toggle");
            },500);
            
        }

        function limpiar1(opc)
        {
            document.querySelector("#text_proyecto_Nodo").value = "";
            document.querySelector("#text_Proyecto_proy").value = "";

        }

        function salir(opc)
        {
            $("#modal_proyecto").modal("toggle");
            setTimeout(function()
            {
                $("#modal_balances").modal("toggle");
            },500);
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
            consultaAjax("../../consultaActiMate",datos,20000,"POST",18);
        }

        function agregarProyectoFilter(ele)
        {
            if(modalPRYSelect == 1)
            {
                document.querySelector("#text_proyecto_Nodo").value = ele.parentElement.parentElement.children[1].innerHTML;
                document.querySelector("#text_proyecto_Nodo").dataset.pry = ele.parentElement.parentElement.children[2].innerHTML;
            }
            else
            {
                document.querySelector("#text_Proyecto_proy").value = ele.parentElement.parentElement.children[1].innerHTML;
                document.querySelector("#text_Proyecto_proy").dataset.pry = ele.parentElement.parentElement.children[2].innerHTML;
            }

            $("#modal_proyecto").modal("toggle");
            setTimeout(function()
            {
                $("#modal_balances").modal("toggle");
            },500);
        }


        var ayudaAddBare = 0;
        function addActividadNueva()
        {
            
        

            var fechaIni = document.querySelector("#datos_aux_1").children[1].children[5].innerHTML.split("-");
            var fechaFin = document.querySelector("#datos_aux_1").children[1].children[6].innerHTML.split("-");

             var f = document.querySelector("#fech_ejecucionInput").value.split("/");


            var fecahSelect = new Date(f[2],f[1]  - 1,f[0]);

            f = f[2] + "-" + f[1] + "-" + f[0];

            fechaIni = new Date(fechaIni[0],fechaIni[1] - 1,fechaIni[2]);
            fechaFin = new Date(fechaFin[0],fechaFin[1] - 1,fechaFin[2]);


           /* if(fechaIni > fecahSelect){
                mostrarModal(1,null,"Cargar ejecución","No puede seleccionar una fecha menor a la de inicio de la ejecución\n",0,"Aceptar","",null);
                return;
            }

            if(fecahSelect > fechaFin){
                mostrarModal(1,null,"Cargar ejecución","No puede seleccionar una fecha mayor a la de finalización de la ejecución\n",0,"Aceptar","",null);
                return;
            }*/

            ayudaAddBare = 1;
            document.querySelector("#txt_name_baremo").value = "";
            document.querySelector("#bareSelect").innerHTML = "";
            document.querySelector("#datos_aux_bare").innerHTML = "";
            document.querySelector("#txt_cod_bare").value = "";


            document.querySelector("#select_persona_cargo_bare").value = document.querySelector("#select_lider_carga").value;
            document.querySelector("#select_persona_cargo_bare").parentElement.parentElement.parentElement.parentElement.style.display = "none";

            $("#modal_captura_ejecucion").modal("toggle");
            setTimeout(function()
            {
                $("#modal_acti_add_nuevo_modelo").modal("toggle");
            },700);
            
        }

        //BAREMOS
        function abrirmodalBaremos()
        {
            $("#tbl_baremos").find('tr').remove();
            document.querySelector("#text_baremo_cod").value = "";
            document.querySelector("#text_baremo_des").value = "";

            document.querySelector("#text_baremo_cod").readOnly = false;
            document.querySelector("#text_baremo_des").readOnly = false;

            $('#modal_acti').modal('toggle'); 
            setTimeout(function()
            {
                $('#modal_baremo').modal('toggle');
            },500);
        }

        function volverModal()
        {
            $('#modal_baremo').modal('toggle');
            setTimeout(function()
            {
                $('#modal_acti').modal('toggle'); 
            },500);
        }

        function consultarBaremo()
        {
            if(document.querySelector("#text_baremo_cod").value == "" &&
                document.querySelector("#text_baremo_des").value == "")
            {
                mostrarModal(1,null,"Filtro Baremos","Ingrese información para el filtro\n",0,"Aceptar","",null);
                return;
            }

            var array = 
            {
                cod : document.querySelector("#text_baremo_cod").value,
                des : document.querySelector("#text_baremo_des").value,
                opc : "0"
            }

            consultaAjax("../../consultaBaremos",array,35000,"POST",20);
        }


        function consultarBaremoNuevoModelo()
        {
            if(document.querySelector("#txt_cod_bare").value == "" &&
                document.querySelector("#txt_name_baremo").value == "")
            {
                mostrarModal(1,null,"Filtro Baremos","Ingrese información para el filtro\n",0,"Aceptar","",null);
                return;
            }

            var array = 
            {
                cod : document.querySelector("#txt_cod_bare").value,
                des : document.querySelector("#txt_name_baremo").value,
                opc : "0"
            }
            //Nuevo Modelo Michael
            consultaAjax("../../consultaBaremos",array,35000,"POST",25);
        }



        function agregarTablaBaremo(ele)
        {
            document.querySelector("#text_baremo").value = ele.dataset.bare + " - " +  ele.dataset.acti;
            document.querySelector("#text_baremo").dataset.bare = ele.dataset.bare;
            document.querySelector("#text_baremo").dataset.acti = ele.dataset.acti;
            document.querySelector("#text_baremo").dataset.precio = ele.dataset.precio;

            document.querySelector("#text_valor").value = ele.dataset.precio;

             if(document.querySelector("#text_cant").value == "")
                document.querySelector("#text_cant").value = "1";

            if(document.querySelector("#text_cant").value != "")
            {
                document.querySelector("#text_total").value = parseFloat(ele.dataset.precio) * parseFloat(document.querySelector("#text_cant").value);
            }   
            volverModal();
        }

        function modificaValorTotal()
            {
                if(document.querySelector("#text_valor").value != "")
                {
                    document.querySelector("#text_total").value = parseFloat(document.querySelector("#text_valor").value) * parseFloat(document.querySelector("#text_cant").value);
                }
            }


        function saveNuevaActividad()
        {

            if(document.querySelector("#select_nodos").value == "" ||
                document.querySelector("#text_baremo").value == "" ||
                document.querySelector("#text_cant").value == "" ||
                document.querySelector("#text_valor").value == "" ||
                document.querySelector("#text_total").value == "" ||
                document.querySelector("#select_nodos").dataset.nodo == null ||
                document.querySelector("#select_nodos").dataset.nodo == undefined
                )
            {
                mostrarModal(1,null,"Agregar baremo","Hace falta diligenciar campos para guardar\n",0,"Aceptar","",null);
                return;
            }


            var baremo = document.querySelector("#text_baremo").dataset.bare;
            var cantidad = document.querySelector("#text_cant").value;
            var nodo = document.querySelector("#select_nodos").dataset.nodo;

            
            var datos = 
            {
                opc : 9,
                bare : baremo,
                cant : cantidad,
                nodo : nodo,
                orden : document.querySelector("#text_eje_1").value,
                cargo: document.querySelector("#select_lider_carga").value
            }; 

            consultaAjax("../../guardarAsignacionRecursosMateriales",datos,20000,"POST",22);
           
            
        }


        function  abrirCSAsociado(evt)
        {
            if(!confirm("¿Seguro que desea crear un documento de consumo?"))
            {
                var windowE = evt || window.event;
                windowE.preventDefault();
            }else
            {
                document.querySelector("#select_nodos_afectados").selectedIndex = 0;
                $("#nodos-add").find("li").remove();

                var arreglo = document.querySelector("#url_orden_enviar").value.split("&fecha=");

                if(document.querySelector("#tipo_proyecto_id").dataset.tipo == "T03")
                {
                    var f = document.querySelector("#fech_ejecucionInput").value.split("/");
                    f = f[2] + "-" + f[1] + "-" + f[0];
                    
                    if(arreglo.length == 0 || arreglo.length == 1)
                        document.querySelector("#url_orden_enviar").value = arreglo[0] + "&fecha=" + f;
                    else
                        document.querySelector("#url_orden_enviar").value  = arreglo[0] + "&fecha=" + f;
                }

                

                document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                document.querySelector("#datos_captura_ejecucion").style.display = "none";
            }
        }


        var ordenSelec = 0;
        var liderSelec = 0;
        var eleSelec = 0;

        function abrirPlanillaMobra(ele)
        {
            if(document.querySelector("#tipo_proyecto_id").dataset.tipo == "T03")
            {
                var f = document.querySelector("#fech_ejecucionInput").value.split("/");
                f = f[2] + "-" + f[1] + "-" + f[0];

                var arreglo = ele.href.split("&fecha=");

                ele.href = arreglo[0] + "&fecha=" + f;
            }
        }

        function abrirPlanillaMate(ele)
        {
            if(document.querySelector("#tipo_proyecto_id").dataset.tipo == "T03")
            {
                var f = document.querySelector("#fech_ejecucionInput").value.split("/");
                f = f[2] + "-" + f[1] + "-" + f[0];

                var arreglo = ele.href.split("&fecha=");

                ele.href = arreglo[0] + "&fecha=" + f;
            }
        }

        function abrirFotografia(ele)
        {
            if(document.querySelector("#tipo_proyecto_id").dataset.tipo == "T03")
            {
                var f = document.querySelector("#fech_ejecucionInput").value.split("/");
                f = f[2] + "-" + f[1] + "-" + f[0];

                var arreglo = ele.href.split("&fecha=");

                ele.href = arreglo[0] + "&fecha=" + f;
            }
        }


        function actualizaBotonMaterial(ele,orden,lider)
        {
            ordenSelec = orden;
            liderSelec = lider;
            eleSelec = ele;

            var f = document.querySelector("#fech_ejecucionInput").value.split("/");
            f = f[2] + "-" + f[1] + "-" + f[0];

            var datos = 
            {
                opc : 31,
                orden : orden,
                lider : lider,
                tipo : document.querySelector("#tipo_proyecto_id").dataset.tipo,
                fecha : f
            }; 

            consultaAjax("../../consultaActiMate",datos,20000,"POST",23);

        }


        function saveNodosUpdate(nodo)
        {

            var datos = 
            {
                opc : 32,
                cd : document.querySelector("#cd_nodo").value,
                pf : document.querySelector("#pf_nodo").value,
                dire : document.querySelector("#di_nodo").value,
                sec : document.querySelector("#sec_nodo").value,
                descargo : document.querySelector("#descargo_add_1").value,
                descargo2 : document.querySelector("#descargo_add_2").value,
                descargo3 : document.querySelector("#descargo_add_3").value,
                descargo4 : document.querySelector("#descargo_add_4").value,
                descargo5 : document.querySelector("#descargo_add_5").value,
                descargo6 : document.querySelector("#descargo_add_6").value,
                descargo7 : document.querySelector("#descargo_add_7").value,
                orden : document.querySelector("#text_eje_1").value,
                lider : document.querySelector("#select_lider_carga").value,
                nodo : nodo
            }; 

            consultaAjax("../../consultaActiMate",datos,20000,"POST",24);
        }


        function save_actividades()
        {
                if(document.querySelector("#text_eje_1").value == "")
                {
                    mostrarModal(1,null,"Guardar ejecución","Ingrese por favor el número de ManiObra\n",0,"Aceptar","",null);
                    return;
                }

                if(document.querySelector("#select_lider_carga").selectedIndex == 0)
                {
                    mostrarModal(1,null,"Guardar ejecución","Seleccione un líder para guardar la ejecución\n",0,"Aceptar","",null);
                    return;
                }

                if(document.querySelector("#select_nodos_afectados").selectedIndex == 0)
                {
                    mostrarModal(1,null,"Guardar ejecución","Seleccione un nodo para guardar la ejecución\n",0,"Aceptar","",null);
                    return;
                }

            /*    if(document.querySelector("#preplanilla_id").value == "")
                {
                    mostrarModal(1,null,"Guardar ejecución","Ingrese por favor el número de preplanilla\n",0,"Aceptar","",null);
                    return;
                }*/

                var fechaIni = document.querySelector("#datos_aux_1").children[1].children[5].innerHTML.split("-");
                var fechaFin = document.querySelector("#datos_aux_1").children[1].children[6].innerHTML.split("-");

                 var f = document.querySelector("#fech_ejecucionInput").value.split("/");

                var fecahSelect = new Date(f[2],f[1]  - 1,f[0]);

                f = f[2] + "-" + f[1] + "-" + f[0];

                fechaIni = new Date(fechaIni[0],fechaIni[1] - 1,fechaIni[2]);
                fechaFin = new Date(fechaFin[0],fechaFin[1] - 1,fechaFin[2]);

                ocultarSincronizacion();

             /*   if(fechaIni > fecahSelect){
                    mostrarModal(1,null,"Cargar ejecución","No puede seleccionar una fecha menor a la de inicio de la ejecución\n",0,"Aceptar","",null);
                    return;
                }

                if(fecahSelect > fechaFin){
                    mostrarModal(1,null,"Cargar ejecución","No puede seleccionar una fecha mayor a la de finalización de la ejecución\n",0,"Aceptar","",null);
                    return;
                }*/


                //Validar Baremos
                var tbl = document.querySelector("#tbl_baremos_eje").children;
                var arrayEnviar = [];

                  for (var i = 0; i < tbl.length; i++) {

                    if(tbl[i].children[2].children[0].children[0].value == "")
                    {
                        mostrarModal(1,null,"Guardar ejecución","No puede guardar los baremos, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                        return;
                    }

                    if(tbl[i].children[2].children[0].children[0].dataset.validado != "1")
                    {
                        mostrarModal(1,null,"Guardar ejecución","No ha terminado de validar todos las actividades.\n",0,"Aceptar","",null);
                        return;
                    }

                    
                    arrayEnviar.push({
                          "bar" : tbl[i].children[0].innerHTML,
                          "can" : tbl[i].children[2].children[0].children[0].value
                        });
                  };
                  var dat = [];

                 

                  dat.push({
                    "nodo" : document.querySelector("#select_nodos_afectados").value,
                    "usuario" : document.querySelector("#select_lider_carga").value,
                    "orden" : document.querySelector("#text_eje_1").value,
                    "barem" : arrayEnviar
                    
                  });

                  var arrayEnviar1 = [];               
                  var dat1 = [];

                  dat1.push({
                    "nodo" : document.querySelector("#select_nodos_afectados").value,
                    "usuario" : document.querySelector("#select_lider_carga").value,
                    "orden" : document.querySelector("#text_eje_1").value,
                    "mate" : arrayEnviar1
                  });

                  if(!document.querySelector("#preplanilla_id").value && !document.querySelector("#preplanilla_id_d").value) {
                    alert('Por favor ingrese un numero de preplanilla');
                    return false;
                  }

                  if(confirm("¿Seguro que desea guardar las actividades del nodo"))
                  {
                     var datos = 
                        {
                            opc: 8,
                            lid: document.querySelector("#select_lider_carga").value,
                            ot : document.querySelector("#text_eje_1").value,
                            nodo: document.querySelector("#select_nodos_afectados").value,
                            bare : dat,
                            mate : dat1,
                            dc : dcSelec,
                            fecha_consulta : f,
                            pla : document.querySelector("#preplanilla_id").value,
                            plad : document.querySelector("#preplanilla_id_d").value
                        }

                        consultaAjax("../../consultaActiMate",datos,120000,"POST",15); 

                  }
        }

      $("#form_horasac").on('submit',function(event){
           //function guardarHorasse(event){     
                
                event.stopPropagation();
                event.preventDefault();
                
                var idor = $("#id_orden_hora").val().trim();
                var idord = $("#text_eje_1").val().trim();
                  
                
                if(idor=='' || idord==''){
                    mensajes('Error','Ingrese un número de orden',0);
                }
                
            var elemento= $("#form_horasac");    
                
            var elementos = $(elemento).find(".valida_texto");
            var tam = elementos.length;
            var control=0;
            for (var i=0; i<tam; i++) {
                if(valida_texto(elementos[i])==0){
                     control=1;
                }
            }
            
            
            var elementos2 = $(elemento).find(".valida_horas");
            var tam2 = elementos2.length;
            for (var i=0; i<tam2; i++) {
                if(valida_horas(elementos2[i])==0){
                     control=1;
                }
            }
            
            
            if(control==1){return false;}
            
             $(elemento).find('.btnfrmoc').hide("slow",function(){ 
              
                    $(elemento).find('.loading').show("slow"); 
                     
                    var formData = new FormData($(elemento)[0]);
                    $.ajax({
                            type: 'POST',
                            url: "{{url('/')}}/redes/ghorasordentrabajo",
                            data: formData,
                            dataType: "json",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data,textStatus) {  
                                if(data.status==1){
                                    mensajes("Exito","Proceso finalizado satisfactoriamente.\n",1);                                 
                                }else{
                                    mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0);                                 
                                }                                
                            }, 
                            error: function(data) {
                                    mensajes("Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0);  
                            }
                     }).always(function() {              
                          $(elemento).find('.loading').hide("slow",function(){$(elemento).find('.btnfrmoc').show();}); 
                     });  
             });
             return false;  
                
            }); 


    </script>
@stop

