@extends('template.index')

@section('title')
	ManiObras
@stop

@section('title-section')
    ManiObras
@stop

@section('content')
    <style type="text/css">
    #ordenes_proyecto_filter
    {
        position: relative;
        top: -35px;
        left: -15px;
    }

    #ordenes_proyecto_wrapper .dataTables_scroll
    {
        position: relative;
        top: -35px;
    }
    </style>

	<main>

        <?php 
            //date_default_timezone_set('America/Bogota');
            //print date('Y-m-d H:i:s'); 
        ?>

    @include('proyectos.redes.trabajoprogramado.modal.modalcargagomestados')
    @include('proyectos.redes.trabajoprogramado.modal.modalBalances')
    @include('proyectos.redes.trabajoprogramado.modal.modalCapturaEjecucion')
    @include('proyectos.redes.trabajoprogramado.modal.modalCapturaConciliacion')
    @include('proyectos.redes.trabajoprogramado.modal.modalconsultaproyectos')

		<div class="container-fluid">
            <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                    @include('proyectos.redes.trabajoprogramado.secciones.filter')
            </div>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                
                {!! Form::open(['url' => 'generarconsolidadobaremos', "method" => "POST","style" => "display: inline-block;"]) !!}
                    <input type="hidden" name="fecha_inicio" id="fecha_inicio1" />
                    <input type="hidden" name="fecha_corte" id="fecha_corte1" />
                    <input type="hidden" name="id_tipo" id="id_tipo1" />
                    <input type="hidden" name="cbo_estado" id="cbo_estado1" />
                    <input type="hidden" name="proyecto" id="proyecto1" />
                    <input type="hidden" name="proyectoN" id="proyectoN1" />
                    <input type="hidden" name="ordenGOM" id="ordenGOM1" />
                    <input type="hidden" name="ordenManiObra" id="ordenManiObra1" />

                    <button style="margin-bottom:10px;" onclick="asignaDatos();" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
                            <i class="fa fa-file-excel-o"></i> &nbsp;&nbsp;Generar consolidado
                    </button>
                {!!Form::close()!!}

                {!! Form::open(['url' => 'generarconsolidadoeje', "method" => "POST","style" => "display: inline-block;"]) !!}
                    <input type="hidden" name="fecha_inicio" id="fecha_inicio2" />
                    <input type="hidden" name="fecha_corte" id="fecha_corte2" />
                    <input type="hidden" name="id_tipo" id="id_tipo2" />
                    <input type="hidden" name="cbo_estado" id="cbo_estado2" />
                    <input type="hidden" name="proyecto" id="proyecto2" />
                    <input type="hidden" name="proyectoN" id="proyectoN2" />
                    <input type="hidden" name="ordenGOM" id="ordenGOM2" />
                    <input type="hidden" name="ordenManiObra" id="ordenManiObra2" />
                    <button style="margin-bottom:10px;" onclick="asignaDatos1();" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
                            <i class="fa fa-file-excel-o"></i> &nbsp;&nbsp;Consolidado de reporte CAMPRO Móvil
                    </button>
                {!!Form::close()!!}

                <span id="panel_civiles_masivo_supervisores" style="display:none">
                    
                    {!! Form::open(['url' => 'transversal/ordenes/exporteSupervisoresTerreno', "method" => "POST","style" => "display: inline-block;"]) !!}
                        <input type="hidden" name="fecha_inicio" id="fecha_inicio3" />
                        <input type="hidden" name="fecha_corte" id="fecha_corte3" />
                        <button style="margin-bottom:10px;" onclick="asignaDatos2();" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
                                <i class="fa fa-file-excel-o"></i> &nbsp;&nbsp;Exportar masivo supervisores
                        </button>
                    {!!Form::close()!!}

                </span>


                @include('proyectos.redes.trabajoprogramado.secciones.tblordenesProyecto')
            </div>
        </div>
	</main>

    <a id="anchorID" href="" target="_blank"></a>

    <script type="text/javascript">

        window.addEventListener('load',iniOrdenesTrabajoProgramado);


        function validarExport(evt)
        {
            var evento = evt || window.event;

            if(document.querySelector("#generar_version_pro").checked)
            {
                if(document.querySelector("#txt_obser_exporte").value == "")
                {
                    mostrarModal(1,null,"Guardar versión de la programación","Ingrese por favor una obversión de la versión\n",0,"Aceptar","",null);
                    evento.preventDefault();
                    return;
                }   
            }else
                document.querySelector("#txt_obser_exporte").value = "";
        }


        function generarVersion()
        {
            if(document.querySelector("#generar_version_pro").checked)
                document.querySelector("#panel_exporte_version").style.display = "block";
            else
            {
                document.querySelector("#panel_exporte_version").style.display = "none";
                document.querySelector("#txt_obser_exporte").value = "";
            }

            
            var dt = new Date();

            // Display the month, day, and year. getMonth() returns a 0-based number.
            var month = dt.getMonth()+1;
            var year = dt.getFullYear();

            document.querySelector("#anio_exporte").value = year;
            document.querySelector("#mes_exporte").value = month;

            //consulta Año y mes seleccionada
            consultadatosversiones();

        }

        function generarExporteVersiones(ele)
        {
            ele.parentElement.submit();
        }


        function consultadatosversiones()
        {

            var array = 
                {
                    opc: 35,
                    mes : document.querySelector("#mes_exporte").value,
                    anio : document.querySelector("#anio_exporte").value
                };


            $.ajax({
                url: "{{url('/')}}/consultaActiMate",
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:array,
                timeout:100000,
                success:function(data)
                {
                    var html = "";

                    for (var i = 0; i < data.length; i++) {
                        
                        html += "<tr>";

                        html += "<td style='text-align:center;'>" + data[i].versio + "</td>";
                        html += "<td style='text-align:center;'>" + data[i].fecha + "</td>";
                        html += "<td style='text-align:center;'>" + data[i].mes + "</td>";
                        html += "<td style='text-align:center;'>" + data[i].anio + "</td>";
                        html += "<td style='text-align:center;'>" + data[i].propietario + "</td>";
                        html += "<td style='text-align:center;'>" + data[i].observacion + "</td>";
                       


                        data[i].id

                        html += "<td style='text-align:center;'><form style='color:blue;cursor:pointer' method='post' action='../../../transversal/ordenes/exporteProgramacionversiones'><input type='hidden' name='_token' value='{{ csrf_token() }}'/> <input type='hidden' name='id' value='" + data[i].id + "'/><span onclick='generarExporteVersiones(this)'><i class='fa fa-file-excel-o' aria-hidden='true'></i> Descargar<span></form></td>";

                        html += "</tr>";
                    }


                    document.querySelector("#tbl_body_versiones").innerHTML = html;
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


        function iniOrdenesTrabajoProgramado()
        {

            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none";
            
            $('#generar_version_pro').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

            var alto = screen.height - 400;
            var altopx = alto+"px";
            var tbl2=   $('#ordenes_proyecto').dataTable({
                    "scrolY":   altopx,
                    "paging":   true,
                    "searching": true,
                    "responsive":      false,
                    "colReorder":      true,
                    "order": [[ 9, 'asc' ]],
                    dom: 'T <"clear">lfrtip',
                    tableTools: {
                        "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                    }
                });           


            $("#gom_cam").DataTable();

            //GOMS
            document.querySelector("#upload_gom").addEventListener("click",function()
            {
                document.querySelector("#text_proyecto").value = "";
                document.querySelector("#text_proyecto").dataset.cod = "";
               // document.querySelector("#text_descargo_add").selectedIndex = 0;
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
                    if(document.querySelector("#text_Proyecto_GOM").value == "")
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
                    consol4 : (document.querySelector("#conso3").checked ? 1 : 0),
                    conci1 : (document.querySelector("#conci1").checked ? 1 : 0),
                    conci2 : (document.querySelector("#conci2").checked ? 1 : 0),
                    conci3 : (document.querySelector("#conci3").checked ? 1 : 0),
                    conci4 : (document.querySelector("#conci4").checked ? 1 : 0),
                    conci5 : (document.querySelector("#conci5").checked ? 1 : 0)
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
                        $("#select_nodos_afectados").find("option").remove();
                            document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                        
                    }
                });

                document.querySelector("#select_nodos_afectados").addEventListener("change",function()
                {
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
                     var datos = 
                        {
                            opc: 8,
                            lid: document.querySelector("#select_lider_carga").value,
                            ot : document.querySelector("#text_eje_1").value,
                            nodo: document.querySelector("#select_nodos_afectados").value,
                            bare : dat,
                            mate : dat1,
                            dc : dcSelec
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
                          mostrarModal(1,null,"Guardar conciliación","No puede guardar los baremos, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                        return;
                        }

                        if(tbl[i].children[2].children[0].children[0].dataset.validado != "1")
                        {
                            mostrarModal(1,null,"Guardar conciliación","No ha terminado de validar todos las actividades.\n",0,"Aceptar","",null);
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
                            mostrarModal(1,null,"Guardar conciliación","No puede guardar los materiales, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                          return;
                        }


                        if(tbl1[i].children[2].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[3].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[4].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[5].children[0].children[0].dataset.validado != "1")
                        {
                            mostrarModal(1,null,"Guardar conciliación","No ha terminado de validar todos los materiales.\n",0,"Aceptar","",null);
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
                }
            }

        }   

        function asignaDatos()
        {
            document.querySelector("#fecha_inicio1").value = document.querySelector("#fecha_inicio").value;
            document.querySelector("#fecha_corte1").value = document.querySelector("#fecha_corte").value;
            document.querySelector("#id_tipo1").value = document.querySelector("#id_tipo").value;
            document.querySelector("#cbo_estado1").value = document.querySelector("#cbo_estado").value;
            document.querySelector("#proyecto1").value = document.querySelector("#proyecto").value;
            document.querySelector("#proyectoN1").value = document.querySelector("#proyectoN").value;
            document.querySelector("#ordenGOM1").value = document.querySelector("#ordenGOM").value;
            document.querySelector("#ordenManiObra1").value = document.querySelector("#ordenManiObra").value;
        }

        function asignaDatos1()
        {
            document.querySelector("#fecha_inicio2").value = document.querySelector("#fecha_inicio").value;
            document.querySelector("#fecha_corte2").value = document.querySelector("#fecha_corte").value;
            document.querySelector("#id_tipo2").value = document.querySelector("#id_tipo").value;
            document.querySelector("#cbo_estado2").value = document.querySelector("#cbo_estado").value;
            document.querySelector("#proyecto2").value = document.querySelector("#proyecto").value;
            document.querySelector("#proyectoN2").value = document.querySelector("#proyectoN").value;
            document.querySelector("#ordenGOM2").value = document.querySelector("#ordenGOM").value;
            document.querySelector("#ordenManiObra2").value = document.querySelector("#ordenManiObra").value;
        }

        function asignaDatos2()
        {
            document.querySelector("#fecha_inicio3").value = document.querySelector("#fecha_inicio").value;
            document.querySelector("#fecha_corte3").value = document.querySelector("#fecha_corte").value;
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

            document.querySelector("#txt_estado").value = document.querySelector("#text_descargo_add").value;
            document.querySelector("#txt_proy").value = document.querySelector("#text_proyecto").dataset.cod;
            
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

                        $("#gom_cam").DataTable();
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
                            mostrarModal(1,null,"Balances","La maniobra aún no ha sido ejecutada.",0,"Aceptar","",null); 
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
                            mostrarModal(1,null,"Cargar archivo GOM","No existe la ManiObra(OT).\n",0,"Aceptar","",null);
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
                            var datos = 
                            {
                                opc: 6,
                                lid: document.querySelector("#select_lider_carga").value,
                                ot : document.querySelector("#text_eje_1").value,
                                nodo: document.querySelector("#select_nodos_afectados").value,
                                dc : "-1"
                            }

                            consultaAjax("../../consultaActiMate",datos,120000,"POST",9);
                        }
                        else
                        {
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

                        var input = $("#tbl_baremos input");

                        if(document.querySelector("#tbl_index_proyectos").dataset.pos == "1")
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
                        
                        mostrarModal(2,null,"Guardar conciliación","Se ha guardado correctamente la ejecución del nodo " + nodo + ".\n",0,"Aceptar","",null);
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

                    if(opcion == 15) //Save Materiales / Baremos
                    {
                        var person = document.querySelector("#select_lider_carga").options[document.querySelector("#select_lider_carga").selectedIndex].text;

                        var doc = document.querySelector("#select_lider_carga").value;

                        var nodo = document.querySelector("#select_nodos_afectados").options[document.querySelector("#select_nodos_afectados").selectedIndex].text;

                        $("#tbl_persona_cargo").html(data);
                        
                        mostrarModal(2,null,"Guardar conciliación","Se ha guardado correctamente la ejecución del nodo " + nodo + ".\n",0,"Aceptar","",null);
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
                        mostrarModal(2,null,"Guardar conciliación","Se ha guardado correctamente la conciliación del nodo .\n",0,"Aceptar","",null);
                        ocultarSincronizacion();
                    }

                    if(opcion == 18) //Save Materiales / Baremos
                    {
                        document.getElementById("anchorID").href = "../../redes/ordenes/ordentrabajo";
                      // alert(document.getElementById("anchorID").href);
                        $("#anchorID")[0].click();
                        ocultarSincronizacion();
                    }

                    if(opcion == 19) //Consulta proyecto
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


        var dcSelec = null;
        function selectDc(ele)
        {

            var nodDc = document.querySelector("#nodos-add").children;
            for (var i = 0; i < nodDc.length; i++) {
                $(nodDc[i]).removeClass('select')
            };

            $(ele).addClass('select');

            var datos = 
            {
                opc: 6,
                lid: document.querySelector("#select_lider_carga").value,
                ot : document.querySelector("#text_eje_1").value,
                nodo: document.querySelector("#select_nodos_afectados").value,
                dc : ele.innerHTML
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

        function verOT(orden)
        {
            var datos = 
                {
                    opc : 2,
                    ot : orden
                };
            consultaAjax("../../guardarProyecto",datos,20000,"POST",18);
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
            consultaAjax("../../consultaActiMate",datos,20000,"POST",19);
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

    </script>
@stop

