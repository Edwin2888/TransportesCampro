@extends('template.index')

@section('title')
	Crear/Ver Proyectos
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/carrusel.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/styleCarrusel.css">
@stop
@section('title-section')
    PROYECTOS TRABAJO PROGRAMADO
@stop
@section('content')
	<main class="rds-orden">
		
        <br><br><br>
        @include('proyectos.redes.trabajoprogramado.secciones.encabezadoProyecto')  
        @include('proyectos.redes.trabajoprogramado.modal.cargaExcelProgramados')
        @include('proyectos.redes.trabajoprogramado.modal.modalBalances')
        @include('proyectos.redes.trabajoprogramado.modal.modalLog')
        @include('proyectos.redes.trabajoprogramado.modal.modalAddNuevoEstructura')
        @include('proyectos.redes.trabajoprogramado.modal.modalLevantamientov2')
        @include('proyectos.redes.trabajoprogramado.modal.modalRestricciones')

        @include('proyectos.redes.trabajoprogramado.modal.modalAddCircuitos')

        @if($proyec != '') 

        @include('proyectos.redes.trabajoprogramado.modal.modalconsultaproyectos')
        
        @include('proyectos.redes.trabajoprogramado.modal.modalwbs')
        @include('proyectos.redes.trabajoprogramado.modal.modalnodos')
        @include('proyectos.redes.trabajoprogramado.modal.modalActividad')
        @include('proyectos.redes.trabajoprogramado.modal.modalMaterial')
        


            <div class="panel-group posisition-fixed-content"  role="tablist" aria-multiselectable="true" id="panel-levantamiento">
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      WBS / NODOS
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body wbs">

                  <div class="col-md-12">
                        <div class="col-md-5">
                            <div class="row" style="position: absolute;    top: -10px; left: 84px;">
                                <div class="col-md-3" style="margin-left:0px;margin-top:10px;">
                                    <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-create-wbs" > <i class="fa fa-plus"></i> Crear WBS</button>
                                </div>
                            </div>
                            
                            <div class="row" id="wbs_content_tbl">
                                @include('proyectos.redes.trabajoprogramado.secciones.wbs')    
                            </div>
                        </div>



                        <div class="col-md-7">
                            <div class="row" style="position: absolute;    top: -10px; left: 84px;">
                                <div class="col-md-3" style="margin-left:0px;margin-top:10px;">
                                    <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-create-nodos" sytle="position: absolute;    top: -10px;"> <i class="fa fa-plus"></i> Crear NODO</button>
                                </div>
                            </div>
                            
                            <div class="row" id="nodo_content_tbl">
                                @include('proyectos.redes.trabajoprogramado.secciones.nodos')
                            </div>
                        </div>
                  </div>
                    
                    
                  </div>
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                  <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      ACTIVIDADES / MATERIALES
                    </a>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                  <div class="panel-body wbs">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="row" style="position: absolute;    top: -10px; left: 84px;">
                                    <div class="col-md-3" style="margin-left:0px;margin-top:10px;">
                                        <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-create-actividad" sytle="position: absolute;    top: -10px;"> <i class="fa fa-plus"></i> Agregar Actividad</button>
                                    </div>
                                </div>
                                
                                <div class="row" id="panel_tbl_actividades">
                                    @include('proyectos.redes.trabajoprogramado.secciones.tblactividades')
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="row" style="position: absolute;    top: -10px; left: 84px;">
                                    <div class="col-md-3" style="margin-left:0px;margin-top:10px;">
                                        <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-create-material" sytle="position: absolute;    top: -10px;"> <i class="fa fa-plus"></i> Agregar Material</button>
                                    </div>
                                </div>
                                
                                <div class="row" id="panel_tbl_materiales">
                                    @include('proyectos.redes.trabajoprogramado.secciones.tblmateriales')
                                </div>
                            </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>


             <div class="panel-group posisition-fixed-content"  role="tablist" aria-multiselectable="true" id="panel-ordentesTrabajo" style="display:none">
              <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      ManiObra
                    </a>
                  </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                  <div class="panel-body wbs">

                  <div class="col-md-12" style="width:50%;position:absolute;top:41px;left:77px;">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-3" style="margin-left:-25px;margin-top:10px;position:relative;">
                                    <a href="{{url('/')}}/redes/ordenes/ordentrabajo/{{$proyec}}" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-plus"></i>Crear ManiObra</a>
                                </div>
                            </div>
                        </div>
                  </div>

                  <div class="row" style="margin-top:7px">
                        @include('proyectos.redes.trabajoprogramado.secciones.tblordenesProyecto')
                  </div>                    
                  </div>
                </div>
              </div>
            </div>

            <script type="text/javascript">
            window.addEventListener('load',iniOtrosDatos);

            var wbsS = null;
            var tipoWBS = 0;
            var tipoNODOS = 0;
            var nodoS = 0;
            var tipoACTIVIDAD = 0;
            var actividad = 0;
            var tipoMATERIAL = 0;
            var material = 0;
            function iniOtrosDatos()
            {
                /*

                            {
                                extend: 'pdfHtml5',
                                orientation:'landscape',
                                title: 'Estructura plan de acción',
                                exportOptions: {
                                    columns: [ 0,1,2,3,4,5,6,7]
                                }
                            } 
                            */
                /*Exportar Excel archivos*/
                $("#contenido").DataTable(
                {
                    dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: 'WBS Proyecto',
                                exportOptions: {
                                    columns: [ 0]
                                }
                            }                                      
                        ]
                    
                }
                );

                $("#contenido_nodos").DataTable(
                {
                    dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: 'NODOS Proyecto',
                                exportOptions: {
                                    columns: [ 1,2,3,4,5]
                                }
                            }                                      
                        ]
                    
                }
                );

                $("#actividades").DataTable(
                {
                    dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: 'Actividades Proyecto',
                                exportOptions: {
                                    columns: [ 1,2,3,4]
                                }
                            }                                      
                        ]
                    
                }
                );

                $("#materiales").DataTable(
                {
                    dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: 'Materiales Proyecto',
                                exportOptions: {
                                    columns: [ 1,2,3,4]
                                }
                            }                                      
                        ]
                    
                }
                );

                $("#ordenes_proyecto").DataTable(
                {
                    dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                title: 'ManiObras Proyecto',
                                exportOptions: {
                                    columns: [0, 1,2,3,4,5,6,7,8,9,10]
                                }
                            }                                      
                        ]
                }
                );
                    
                $("#descargos_add").DataTable();
                //Agregar evento OnChange
                var select = $("#tbl_descargos_asociadas select");
                for (var i = 0; i < select.length; i++) {
                    select[i].addEventListener("change",function()
                    {
                        var array = 
                        {
                            pro :document.querySelector("#id_proyect").value,
                            des :this.parentElement.parentElement.children[0].innerHTML,
                            esta : this.value,
                            opc : "15"
                        }
                        consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",22);
                    })
                };
                

                document.querySelector("#btn-wbs-uc").addEventListener("click",guardarInformacionWBS);
                document.querySelector("#btn-delete-wbs").addEventListener("click",deleteWBS);
                document.querySelector("#text_wbs").addEventListener("keydown",validaIngreso);  
                document.querySelector("#btn-create-wbs").addEventListener("click",guardarWBSNueva);  

                //NODOS 
                document.querySelector("#btn-create-nodos").addEventListener("click",abrirModalCrearNodos);
                document.querySelector("#btn-wbs-nodos").addEventListener("click",guardarNodos);
                document.querySelector("#btn-delete-nodos").addEventListener("click",eliminarNodos);
                
                //ACTIVIDADES
                document.querySelector("#btn-create-actividad").addEventListener("click",abrirModalCrearActividad);
                document.querySelector("#btn-wbs-actividad").addEventListener("click",guardarActividad);
                document.querySelector("#btn-delete-actividad").addEventListener("click",eliminarActividad);
                document.querySelector("#select_wbs_1").addEventListener("change",consultaNodo);
                document.querySelector("#text_cant").addEventListener("blur",modificaValorTotal);
                
                //MATERIAL
                document.querySelector("#btn-create-material").addEventListener("click",abrirModalCrearMaterial);
                document.querySelector("#select_wbs_1_mate").addEventListener("change",consultaNodoMate);
                document.querySelector("#select_wbs_levantamiento").addEventListener("change",consultaNodoMate1);

                document.querySelector("#btn-wbs-material-a").addEventListener("click",guardarMaterial);
                document.querySelector("#text_cant_mate_add").addEventListener("blur",modificaValorTotalMate);

                if(document.querySelector("#btn-orden-trabajo") != null)
                {document.querySelector("#btn-orden-trabajo").addEventListener("click",function()
                {
                    if(document.querySelector("#btn-orden-trabajo").innerHTML == "Levantamiento")
                    {
                        document.querySelector("#btn-orden-trabajo").innerHTML = "Ordenes de ManiObras";
                        document.querySelector("#panel-ordentesTrabajo").style.display = "none";
                        document.querySelector("#panel-levantamiento").style.display = "block";
                    }
                    else
                    {
                        document.querySelector("#btn-orden-trabajo").innerHTML = "Levantamiento";
                    document.querySelector("#panel-levantamiento").style.display = "none";
                        document.querySelector("#panel-ordentesTrabajo").style.display = "block";
                        
                    }
                    //window.location.href = "{{url('/')}}/redes/ordenes/ordentrabajo";
                });
                }
                
                /*
                document.querySelector("#btn-create-orden-trabajo").addEventListener("click",function()
                {
                    mostrarSincronizacion();
                    var datos = 
                    {
                        opc : 3,
                        proy : document.querySelector("#id_proyect").value
                    };
                    consultaAjax("{{url('/')}}/guardarProyecto",datos,20000,"POST",12);
                });*/

                if(document.querySelector("#btn-balance") != null)
                {
                    document.querySelector("#btn-balance").addEventListener("click",function()
                    {
                        $("#modal_balances").modal("toggle");
                    });    
                }
                

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
                        consol4 : (document.querySelector("#conso3").checked ? 1 : 0),
                        conci1 : (document.querySelector("#conci1").checked ? 1 : 0),
                        conci2 : (document.querySelector("#conci2").checked ? 1 : 0),
                        conci3 : (document.querySelector("#conci3").checked ? 1 : 0),
                        conci4 : (document.querySelector("#conci4").checked ? 1 : 0),
                        conci5 : (document.querySelector("#conci5").checked ? 1 : 0),
                        fecha1: document.querySelector("#fecha_ini_gom").value,
                        fecha2: document.querySelector("#fecha_fin_gom").value
                    }

                    consultaAjax("{{url('/')}}/consultaBalances",data,235000,"POST",13);
                });

                //Agregar ADD GOM
                if(document.querySelector("#btn-gom-add") != null)
                {

                    document.querySelector("#text_gom_wbs").addEventListener("keydown",validaIngreso); 
                    
                    document.querySelector("#btn-gom-add").addEventListener("click",function()
                    {
                        document.querySelector("#select_wbs_gom").selectedIndex = 0;
                        document.querySelector("#text_gom_wbs").value = "";
                        $("#tbl_gom_asociadas").find("tr").remove();
                        $("#modal_gom_wbs").modal("toggle");
                    });    
                }
                

                //Evento Change WBS
                document.querySelector("#select_wbs_gom").addEventListener("change",function()
                {
                    document.querySelector("#text_gom_wbs").value = "";
                    $("#tbl_gom_asociadas").find("tr").remove();

                    if(this.selectedIndex != 0)
                    {
                        var array = 
                        {
                            wbs :this.value,
                            opc : "5",
                            pro : document.querySelector("#id_proyect").value
                        }
                        consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",14);
                    }

                });

                //Descargos
                if(document.querySelector("#btn-descargo-add") != null)
                {
                    document.querySelector("#text_descargo_add").addEventListener("keydown",validaIngreso); 

                    document.querySelector("#btn-descargo-add").addEventListener("click",function()
                    {
                        document.querySelector("#text_descargo_add").value = "";
                        $("#modal_descargos").modal("toggle");
                    });
                }
                
                if(document.querySelector("#btn_restricciones") != null)
                {
                
                document.querySelector("#btn_restricciones").addEventListener("click",function()
                {
                    $("#modal_restricciones").modal("toggle");
                });
                }
                //Guardar Restricciones
                document.querySelector("#btn_save_restriccion").addEventListener("click",function()
                {
                    if(document.querySelector("#text_restric").selectedIndex == 0 ||
                        document.querySelector("#text_impacto").value == "" ||
                        document.querySelector("#fec_limite").value == "" ||
                        document.querySelector("#text_responsable_restric").value == "")
                    {
                        mostrarModal(1,null,"Agregar restricción","Hace falta ingresar información, para guardar la restricción.\n",0,"Aceptar","",null);
                        return;
                    }

                    if(document.querySelector("#correo_enviar").children.length == 0)
                    {
                        mostrarModal(1,null,"Agregar restricción","No ha ingresado ningún correo electrónico.\n",0,"Aceptar","",null);
                        return;
                    }


                    var correo = Array();
                    for (var i = 0; i < document.querySelector("#correo_enviar").children.length; i++) {
                       correo.push(document.querySelector("#correo_enviar").children[i].children[0].innerHTML);
                    };

                    if(document.querySelector("#btn_save_restriccion").dataset.tipo == "2")
                    {
                        var array = 
                        {
                            restric :document.querySelector("#text_restric").value,
                            impac :document.querySelector("#text_impacto").value,
                            fecha : document.querySelector("#fec_limite").value,
                            resp : document.querySelector("#text_responsable_restric").value,
                            corr : correo,
                            esta : document.querySelector("#select_estado").value,
                            opc : "9",
                            restricD : document.querySelector("#text_descp_restriccion").value
                        } 
                    }
                    else
                    {
                        if(document.querySelector("#select_estado").selectedIndex == 2)
                        {
                            if(document.querySelector("#evidencia_cierre").value == "")
                            {
                                mostrarModal(1,null,"Agregar restricción","Hace falta ingresar información, para guardar la restricción.\n",0,"Aceptar","",null);
                                return;   
                            }
                        
                        }
                        var array = 
                        {
                            restric :document.querySelector("#text_restric").value,
                            impac :document.querySelector("#text_impacto").value,
                            fecha : document.querySelector("#fec_limite").value,
                            resp : document.querySelector("#text_responsable_restric").value,
                            corr : correo,
                            esta : document.querySelector("#select_estado").value,
                            res_id : document.querySelector("#btn_save_restriccion").dataset.res,
                            opc : "10"
                        } 
                    }
                    

                    consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",18);
                });
                
                //Consulta Restricción
                document.querySelector("#btn_consulta_restric").addEventListener("click",function()
                {
                    var array = 
                    {
                        estado :document.querySelector("#select_estado_filter").value,
                        fecha1 :document.querySelector("#fecha_inicio").value,
                        fecha2 : document.querySelector("#fecha_corte").value,
                        opc : "12"
                    }

                    consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",19); 
                });

                //Create resticción
                document.querySelector("#btn_create_restric").addEventListener("click",function()
                {
                    document.querySelector("#correo_enviar").innerHTML = "";
                    document.querySelector("#text_restric").selectedIndex = 0;
                    document.querySelector("#text_impacto").value = "";
                    document.querySelector("#fec_limite").value = "";
                    document.querySelector("#text_responsable_restric").value = "";
                    document.querySelector("#text_correo_responsable").value = "";
                    document.querySelector("#evidencia_cierre").value = "";
                    document.querySelector("#row_estado").style.display = "none";
                    document.querySelector("#select_estado").selectedIndex = 0;
                    document.querySelector("#btn_save_restriccion").dataset.tipo = 2;
                    document.querySelector("#btn_save_restriccion").innerHTML = "Guardar";
                    document.querySelector("#evidencia_cierre_input").style.display = "none";
                    document.querySelector("#text_descp_restriccion").readOnly = false;
                    document.querySelector("#text_restric").disabled = false;
                    document.querySelector("#text_impacto").readOnly = false;
                    document.querySelector("#fec_limite").readOnly = false;
                    document.querySelector("#text_responsable_restric").readOnly = false;
                    document.querySelector("#text_correo_responsable").readOnly = false;
                    document.querySelector("#btn_add").style.display = "inline-block";
                    document.querySelector("#btn_1_fecha").style.visibility = "visible";
                    document.querySelector("#btn_2_fecha").style.visibility = "visible";
                    document.querySelector("#btn_save_restriccion").style.display = "inline-block";


                    $("#modal_restricciones").modal("toggle");
                    setTimeout(function()
                    {
                        $("#modal_restricciones_add").modal("toggle");
                    },700);
                });
                
                //Cerrar restricción
                document.querySelector("#btn_close_restriccion").addEventListener("click",function()
                {
                    $("#modal_restricciones_add").modal("toggle");
                    setTimeout(function()
                    {
                        $("#modal_restricciones").modal("toggle");
                    },700);
                });

                //Cambiar Estado
                document.querySelector("#select_estado").addEventListener("change",function()
                {
                    if(this.selectedIndex == 2)
                        document.querySelector("#evidencia_cierre_input").style.display = "block";
                    else
                        document.querySelector("#evidencia_cierre_input").style.display = "none";
                });
                $('#tbl_restric').DataTable();

             


                /*LOGS*/
                document.querySelector("#btn_ver_logs").addEventListener("click",function()
                {
                    var array = 
                    {
                        opc : "16",
                        proy : document.querySelector("#id_proyect").value
                    }
                    consultaAjax("{{url('/')}}/consultaBaremos",array,125000,"POST",23); 
                });

                /*NUEVO FORMATO DE PROGRAMACIÓN*/
                document.querySelector("#btn_cargar_estructura").addEventListener("click",function()
                {
                    $("#modal_import_nueva_estructura").modal("toggle");
                });

                document.querySelector("#btn_levantamiento_v2").addEventListener("click",function()
                {
                    $("#modal_agregar_estructura").modal("toggle");

                });

                document.querySelector("#select_estruc").addEventListener("change",function()
                {
                    if(this.selectedIndex == 0)
                    {
                        document.querySelector("#select_tipo").innerHTML = "";
                    }
                    else
                    {
                        var datos = 
                        {
                            opc: 22,
                            estruc: document.querySelector("#select_estruc").value
                        }
                        consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",24);  
                    }

                });

                document.querySelector("#select_tipo").addEventListener("change",function()
                {
                    if(this.selectedIndex == 0)
                    {
                        //document.querySelector("#select_tipo").innerHTML = "";
                    }
                    else
                    {
                        var datos = 
                        {
                            opc: 23,
                            estruc: document.querySelector("#select_estruc").value,
                            norma: document.querySelector("#select_tipo").value
                        }
                        consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",25);  
                    }

                });
            }

            function addCorreo()
            {
                if(!validarEmail(document.querySelector("#text_correo_responsable").value))
                {
                    mostrarModal(1,null,"Agregar restricción","El email ingresado no es correcto.\n",0,"Aceptar","",null);
                    return;
                }

                $("#correo_enviar").append("<tr><td>" + document.querySelector("#text_correo_responsable").value + "</td><td><button onclick='deleteCorreo(this)'><i class='fa fa-times'></i> </button></td></tr>");
                document.querySelector("#text_correo_responsable").value = "";
            }

            function deleteCorreo(ele)
            {
               $("#correo_enviar").find(ele.parentElement.parentElement).remove();
            }


            /*WBS*/
            function guardarWBSNueva()
            {
                tipoWBS = 0;
                document.querySelector("#btn-wbs-uc").innerHTML = "Guardar";
                document.querySelector("#text_wbs").value = "";
                document.querySelector("#text_gom_wbs").value = "";
                document.querySelector("#txt_obser").value = "";
                document.querySelector("#btn-delete-wbs").style.display = "none";
                $('#modal_wbs').modal('toggle');

            }

            function guardarInformacionWBS()
            {
                
                if(tipoWBS == 0)
                    wbsS = -1;

                var datos = 
                    {
                        wbs : wbsS,
                        pro : document.querySelector("#id_proyect").value,
                        nombre : document.querySelector("#text_wbs").value,
                        obser : document.querySelector("#text_gom_wbs").value,
                        ori : document.querySelector("#text_wbs").dataset.ori,
                        opc : tipoWBS
                    };

                consultaAjax("{{url('/')}}/guardarWBS",datos,20000,"POST",2);
            }

            function deleteWBS()
            {
                if(confirm("¿Seguro que desea eliminar la WBS?"))
                {
                    var datos = 
                    {
                        wbs : wbsS,
                        pro : document.querySelector("#id_proyect").value,
                        opc : 2
                    };

                    consultaAjax("{{url('/')}}/guardarWBS",datos,20000,"POST",3);
                    //mostrarModal(2,null,"Eliminar WBS","Se ha eliminado correctamente la WBS",0,"Aceptar","",null);   
                }
            }

            function abrirModalWBS(wbs,nombre,obser,nombreOri)
            {
               // alert(wbs);
                tipoWBS = 1;
                //alert(wbs);
                wbsS = wbs;
                document.querySelector("#btn-delete-wbs").style.display = "inline-block";
                document.querySelector("#btn-wbs-uc").innerHTML = "Actualizar";
                document.querySelector("#text_wbs").value = nombre;
                document.querySelector("#txt_obser").value = obser;
                document.querySelector("#text_wbs").dataset.ori = nombre;
                $('#modal_wbs').modal('toggle');
            }

            function consultaWBStable()
            {
                //alert("Consultando..");
            }
            //FIN WBS

            /*NODOS*/
            function abrirModalCrearNodos()
            {
                tipoNODOS = 0;
                document.querySelector("#select_wbs").disabled = false;
                document.querySelector("#btn-wbs-nodos").innerHTML = "Guardar";
                document.querySelector("#select_wbs").selectedIndex = 0;
                document.querySelector("#text_nodo").value = "";
                document.querySelector("#text_cd").value = "";
                document.querySelector("#text_dire").value = "";
                document.querySelector("#txt_obser_nodos").value = "";
                document.querySelector("#text_nive_teension").selectedIndex = 0;
                document.querySelector("#txt_pf_nodos").value = "";
                document.querySelector("#txt_seccionador").value = "";
                document.querySelector("#btn-delete-nodos").style.display = "none";                
                document.querySelector("#gom_nodo").value = "";
                document.querySelector("#id_estado_gom").value = 0;
                $('#modal_nodos').modal('toggle');   
            }

            function guardarNodos()
            {

                if(tipoNODOS == 0)
                    nodoS = -1;

                var datos = 
                    {
                        nodoA : nodoS,
                        pro : document.querySelector("#id_proyect").value,
                        wbs : document.querySelector("#select_wbs").value,
                        nodoNombre : document.querySelector("#text_nodo").value,
                        cd : document.querySelector("#text_cd").value,
                        dire : document.querySelector("#text_dire").value,
                        obser : document.querySelector("#txt_obser_nodos").value,
                        nt : document.querySelector("#text_nive_teension").value,
                        pf : document.querySelector("#txt_pf_nodos").value,
                        sec : document.querySelector("#txt_seccionador").value,
                        gom: document.querySelector("#gom_nodo").value,
                        id_estado_gom: document.querySelector("#id_estado_gom").value,
                        opc : tipoNODOS
                    };

                consultaAjax("{{url('/')}}/guardarNODOS",datos,45000,"POST",4);

            }

            function eliminarNodos()
            {

                if(confirm("¿Seguro que desea eliminar el NODO"))
                {
                    var datos = 
                    {
                        wbs : document.querySelector("#select_wbs").value,
                        nodoA : nodoS,
                        pro : document.querySelector("#id_proyect").value,
                        opc : 2
                    };

                    consultaAjax("{{url('/')}}/guardarNODOS",datos,20000,"POST",5);
                }
                

            }

            function abrirModalNODOS(ele)
            {
                nodoS = ele.dataset.nodonumero;
                document.querySelector("#select_wbs").value = ele.dataset.wbs;
                document.querySelector("#select_wbs").disabled = true;
                document.querySelector("#text_nodo").value = ele.dataset.nodo;
                document.querySelector("#text_cd").value = ele.dataset.cd;
                document.querySelector("#text_dire").value = ele.dataset.dire;
                document.querySelector("#txt_obser_nodos").value = ele.dataset.obser;
                document.querySelector("#text_nive_teension").value = ele.dataset.nv;
                document.querySelector("#txt_pf_nodos").value = ele.dataset.pf;
                document.querySelector("#txt_seccionador").value = ele.dataset.sec;
                document.querySelector("#gom_nodo").value = ele.dataset.gom;
                
                document.querySelector("#id_estado_gom").value = ele.dataset.id_estado_gom;
                

                tipoNODOS = 1;
                document.querySelector("#btn-wbs-nodos").innerHTML = "Actualizar";
                document.querySelector("#btn-delete-nodos").style.display = "inline-block";

                $('#modal_nodos').modal('toggle'); 

            }

            function consultaNODOStable()
            {
                //alert("Consultando..");
            }


            /*BAREMOS*/
            function abrirmodalBaremos()
            {
                $("#tbl_baremos").find('tr').remove();
                document.querySelector("#text_baremo_cod").value = "";
                document.querySelector("#text_baremo_des").value = "";

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
                    alert("Ingrese información para el filtro");
                    return;
                }

                var array = 
                {
                    cod : document.querySelector("#text_baremo_cod").value,
                    des : document.querySelector("#text_baremo_des").value,
                    opc : "0",
                }

                consultaAjax("{{url('/')}}/consultaBaremos",array,20000,"POST",6);
            }


            function agregarTablaBaremo(ele)
            {

                document.querySelector("#text_baremo").value = ele.dataset.bare + " - " +  ele.dataset.acti;
                document.querySelector("#text_baremo").dataset.bare = ele.dataset.bare;
                document.querySelector("#text_baremo").dataset.acti = ele.dataset.acti;
                document.querySelector("#text_baremo").dataset.precio = ele.dataset.precio;

                document.querySelector("#text_valor").value = ele.dataset.precio;

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

            function consultaNodo()
            {
                if(document.querySelector("#select_wbs_1").selectedIndex == 0)
                    return;

                var array = 
                {
                    cod : document.querySelector("#select_wbs_1").value,
                    opc : "1",
                }

                consultaAjax("{{url('/')}}/consultaBaremos",array,20000,"POST",7);
            }

            function abrirModalCrearActividad()
            {
                tipoACTIVIDAD = 0;
                document.querySelector("#btn-wbs-actividad").innerHTML = "Guardar";
                document.querySelector("#btn-delete-actividad").style.display = "none";
                //document.querySelector("#select_wbs_1").selectedIndex = 0;
                //$("#select_nodos").find('option').remove();
                document.querySelector("#text_baremo").value = "";
                document.querySelector("#text_cant").value = "1";
                document.querySelector("#text_valor").value = "";
                document.querySelector("#text_total").value = "";
                 $('#modal_acti').modal('toggle'); 
            }
            function guardarActividad()
            {

                if(tipoACTIVIDAD == 0)
                    actividad = -1;

                var datos = 
                    {
                        pro : document.querySelector("#id_proyect").value,
                        wbs : document.querySelector("#select_wbs_1").value,
                        nodo : document.querySelector("#select_nodos").value,
                        baremo : document.querySelector("#text_baremo").dataset.bare,
                        cant : document.querySelector("#text_cant").value,
                        opc : tipoACTIVIDAD,
                    };

                consultaAjax("{{url('/')}}/guardarACTIVIDAD",datos,20000,"POST",8);
            }

            function eliminarActividad()
            {

            }

            /*MATERIALES*/
            function abrirModalCrearMaterial()
            {
                
                //document.querySelector("#select_wbs_1_mate").selectedIndex = 0;
                document.querySelector("#text_mate_add").value = "";
                document.querySelector("#text_cant_mate_add").value = "1";
                document.querySelector("#text_valor_mate_add").value = "";
                document.querySelector("#text_total_mate_add").value = "";
                tipoMATERIAL = 0;
                document.querySelector("#btn-wbs-material-a").innerHTML = "Guardar";
                document.querySelector("#btn-delete-material-a").style.display = "none";

                
                $('#modal_mat_add').modal('toggle');

            }

            function consultaNodoMate()
            {
                if(document.querySelector("#select_wbs_1_mate").selectedIndex == 0)
                    return;

                var array = 
                {
                    cod : document.querySelector("#select_wbs_1_mate").value,
                    opc : "1",
                }

                consultaAjax("{{url('/')}}/consultaBaremos",array,20000,"POST",9);
            }

            function consultaNodoMate1()
            {
                if(document.querySelector("#select_wbs_levantamiento").selectedIndex == 0)
                    return;

                var array = 
                {
                    cod : document.querySelector("#select_wbs_levantamiento").value,
                    opc : "1",
                }

                consultaAjax("{{url('/')}}/consultaBaremos",array,20000,"POST",26);
            }

            function abrirModalMateriales()
            {
                $("#tbl_mate_add").find('tr').remove();
                document.querySelector("#text_mate_search").value = "";
                document.querySelector("#text_mate_des_seacrh").value = "";

                $('#modal_mat_add').modal('toggle'); 
                setTimeout(function()
                {
                    $('#modal_material_add').modal('toggle');
                },500);
            }

            function consultaMaterialAdd()
            {
                if(document.querySelector("#text_mate_search").value == "" &&
                    document.querySelector("#text_mate_des_seacrh").value == "")
                {
                    alert("Ingrese información para el filtro");
                    return;
                }

                var array = 
                {
                    cod : document.querySelector("#text_mate_search").value,
                    des : document.querySelector("#text_mate_des_seacrh").value,
                    opc : "2",
                }

                consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",10);
            }

            function agregarTablaMaterial(ele)
            {
                document.querySelector("#text_mate_add").value = ele.dataset.mate + " - " +  ele.dataset.nombre;
                document.querySelector("#text_mate_add").dataset.mate = ele.dataset.mate;
                document.querySelector("#text_mate_add").dataset.nombre = ele.dataset.nombre;
                document.querySelector("#text_mate_add").dataset.precio = ele.dataset.precio;

                document.querySelector("#text_valor_mate_add").value = ele.dataset.precio;

                if(document.querySelector("#text_cant_mate_add").value != "")
                {
                    document.querySelector("#text_total_mate_add").value = parseFloat(ele.dataset.precio) * parseFloat(document.querySelector("#text_cant_mate_add").value);
                }
                $('#modal_material_add').modal('toggle'); 
                setTimeout(function()
                {
                    $('#modal_mat_add').modal('toggle');
                },500);

            }

            function volverModalMaterial()
            {
                $('#modal_material_add').modal('toggle'); 
                setTimeout(function()
                {
                    $('#modal_mat_add').modal('toggle');
                },500);
            }

            function guardarMaterial()
            {
                if(tipoMATERIAL == 0)
                    material = -1;

                var datos = 
                    {
                        pro : document.querySelector("#id_proyect").value,
                        wbs : document.querySelector("#select_wbs_1_mate").value,
                        nodo : document.querySelector("#select_nodos_material").value,
                        material : document.querySelector("#text_mate_add").dataset.mate,
                        cant : document.querySelector("#text_cant_mate_add").value,
                        opc : tipoACTIVIDAD,
                    };

                consultaAjax("{{url('/')}}/guardarMaterial",datos,20000,"POST",11);
            }

            function modificaValorTotalMate()
            {
                if(document.querySelector("#text_cant_mate_add").value != "")
                {
                    document.querySelector("#text_total_mate_add").value = parseFloat(document.querySelector("#text_cant_mate_add").value) * parseFloat(document.querySelector("#text_valor_mate_add").value);
                }
            }

             //RESTRICCION
        function abrirModalRestriccion(ele)
        {
            document.querySelector("#correo_enviar").innerHTML = "";
            document.querySelector("#text_restric").value = ele.dataset.tipo;
            document.querySelector("#text_impacto").value = ele.parentElement.parentElement.children[1].innerHTML.split("160px;")[1].split("</p>")[0].replace('"','').replace(">",'');
            document.querySelector("#text_impacto").readOnly = true;
            document.querySelector("#fec_limite").readOnly = true;
            document.querySelector("#text_responsable_restric").readOnly = true;
            document.querySelector("#text_correo_responsable").readOnly = true;
            document.querySelector("#btn_add").style.display = "none";
            document.querySelector("#btn_1_fecha").style.visibility = "hidden";
            document.querySelector("#btn_2_fecha").style.visibility = "hidden";
            document.querySelector("#text_restric").disabled = true;
            document.querySelector("#text_descp_restriccion").readOnly = true;
            var fecha = ele.parentElement.parentElement.children[2].children[0].innerHTML.split("-");
            fecha = fecha[2] + "/" + fecha[1] + "/" + fecha[0];
            document.querySelector("#fec_limite").value = fecha;
            document.querySelector("#text_responsable_restric").value = ele.parentElement.parentElement.children[3].innerHTML;
            
            var arregloCorreos = ele.dataset.correo.split(";");
            var html = "";
            for (var i = 0; i < arregloCorreos.length; i++) {
                
                html += '<tr><td>' + arregloCorreos[i] + '</td><td></td></tr>';

            };

            
            document.querySelector("#correo_enviar").innerHTML = html;
            
            document.querySelector("#row_estado").style.display = "block";
            document.querySelector("#select_estado").options[document.querySelector("#select_estado").selectedIndex].text = ele.parentElement.parentElement.children[5].innerHTML;
            
            document.querySelector("#btn_save_restriccion").style.display = "none";
            
            if(ele.parentElement.parentElement.children[5].innerHTML == "CERRADA")
                document.querySelector("#evidencia_cierre_input").style.display = "block";

            document.querySelector("#btn_save_restriccion").dataset.tipo = 1;
            document.querySelector("#btn_save_restriccion").dataset.res = ele.dataset.restric;
            document.querySelector("#btn_save_restriccion").innerHTML = "Actualizar";

            $("#modal_restricciones").modal("toggle");
            setTimeout(function()
            {
                $("#modal_restricciones_add").modal("toggle");
            },700);

        }

            /*SECCIÓN GOMS*/
            function verOT(ele)
            {
                var datos = 
                    {
                        opc : 2,
                        ot : ele
                    };
                consultaAjax("{{url('/')}}/guardarProyecto",datos,20000,"POST",12);
            }

            function addGOMWBS()
            {
                if(document.querySelector("#text_gom_wbs").value.length < 7
                    || document.querySelector("#text_gom_wbs").value.length > 7)
                {

                    mostrarModal(1,null,"Agregar GOM","La cantidad de digítos que debe tener la GOM debe ser de 7.\n",0,"Aceptar","",null);   
                    return;
                }

                if(document.querySelector("#text_gom_wbs").value != "" || document.querySelector("#select_wbs_gom").selectedIndex != 0)
                {
                    if(document.querySelector("#select_wbs_gom").value != 0 &&
                        document.querySelector("#select_wbs_gom").selectedIndex != - 1)
                    {
                        var array = 
                        {
                            wbs :document.querySelector("#select_wbs_gom").value,
                            gom :document.querySelector("#text_gom_wbs").value,
                            proy : document.querySelector("#id_proyect").value,
                            opc : "6",
                        }
                        consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",15);    
                    }
                    else
                    {
                        mostrarModal(1,null,"Agregar GOM","Seleccione la WBS.\n",0,"Aceptar","",null);   
                        return;
                    }
                }
                else
                {
                    mostrarModal(1,null,"Agregar GOM","Ingrese GOM.\n",0,"Aceptar","",null);
                }

            }

            function deleteGOMWBS(wbs,gom)
            {
                if(confirm("¿Seguro que desea eliminar la orden GOM"))
                {
                    var array = 
                    {
                        wbs :wbs,
                        gom :gom,
                        proy : document.querySelector("#id_proyect").value,
                        opc : "7",
                    }
                    consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",16);  
                }
                
            }

            function editGOMWBS(ele)
            {
                var tblPadre = ele.parentElement.parentElement;
                var span = tblPadre.children[1].children[0];
                var input = tblPadre.children[1].children[1];

                span.style.display = "none";
                input.style.display = "block";
            }

            function validaIngresoGOM(ele)
            {

                var letra = event.which || event.keyCode;

                if(letra == 13)
                {
                    if(this.value == "")
                    {
                        mostrarModal(1,null,"Actualzar GOM","No puede actualizar la orden GOM, por que esta vacia .\n",0,"Aceptar","",null);
                    }
                    else
                    {
                        if(this.value.length > 7 ||
                            this.value.length < 7)
                        {
                            mostrarModal(1,null,"Actualzar GOM","Cantidad de digitos de la GOM 7 .\n",0,"Aceptar","",null);
                            return;       
                        }
                        var array = 
                        {
                            wbs :this.dataset.wbs,
                            gomN :this.value,
                            gomA :this.parentElement.children[0].innerHTML,
                            proy : document.querySelector("#id_proyect").value,
                            opc : "8",
                        }
                        consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",17);
                    }
                }
                if((letra < 48 || letra > 57) )
                {
                    if(letra == 109 || letra == 189 || letra == 8)
                    {
                    }else
                    {
                        var even = window.event;
                        even.preventDefault();
                    }   
                }
                        
            }
            /* FIN SECCIÓN ADD GOMS*/


            /*SECTION DESCARGOS*/
            function addDescargo()
            {   
                if(document.querySelector("#text_descargo_add").value.length < 8 ||
                    document.querySelector("#text_descargo_add").value.length > 8)
                {
                    mostrarModal(1,null,"Agregar Descargo","La cantidad de digítos del descargo debe ser de 8.\n",0,"Aceptar","",null);
                    return;   
                }

                if(document.querySelector("#text_descargo_add").value != "")
                {
                    var array = 
                    {
                        pro :document.querySelector("#id_proyect").value,
                        des :document.querySelector("#text_descargo_add").value,
                        opc : "14",
                    }
                    consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",21);
                }
                else
                {
                    mostrarModal(1,null,"Agregar Descargo","Ingrese Descargo.\n",0,"Aceptar","",null);
                }
            }


            /*END SECTION DESCARGO*/
            

            </script>
            @endif
	</main>

    <script type="text/javascript">

        window.addEventListener('load',iniOrdenesTrabajoProgramado);

        /*SECCIÓN CIRCUITOS*/
            function eventoModalAddCircuito()
            {
                document.querySelector("#text_num_circu_add").value = "";
                document.querySelector("#text_nomb_circu_add").value = "";
                $("#moda_add_circu").modal("toggle");
            }

            function addCircuito()
            {
                if(document.querySelector("#text_num_circu_add").value == ""||
                    document.querySelector("#text_nomb_circu_add").value == "")
                {
                    mostrarModal(1,null,"Circuitos","Hace falta ingresar informacación, para guardar el circuito.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                    return;
                }

                var array = 
                {
                    circ : document.querySelector("#text_num_circu_add").value,
                    circN : document.querySelector("#text_nomb_circu_add").value,
                    estac : document.querySelector("#text_estacion_add").value,
                    tensi : document.querySelector("#text_tension_add").value,
                    opc : "13",
                }

                consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",20);

            }
            /*FIN SECCIÓN CIRCUITOS*/


        function iniOrdenesTrabajoProgramado()
        {

               /*CIRCUITOS*/
                document.querySelector("#btn_add_circuito").addEventListener("click",addCircuito);
                
            $('#diagrama').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });
/*
            $('#finalizado').bootstrapToggle({
                on: 'Si',
                off: 'No'
            });*/

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

            $('#crear_nuevo_11').bootstrapToggle({
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
            document.querySelector("#id_zona").value = document.querySelector("#id_zona_hidden").value;
            //document.querySelector("#id_local").value = document.querySelector("#id_local_hidden").value;
            
            if(document.querySelector("#btn-importar") != null || document.querySelector("#btn-importar") != undefined)
            {
                document.querySelector("#btn-importar").addEventListener("click",function()
                {

                    $('#modal_import').modal('toggle');
                    
                });
            }

            if(document.querySelector("#btn-importar_1") != null || document.querySelector("#btn-importar_1") != undefined)
            {
                document.querySelector("#btn-importar_1").addEventListener("click",function()
                {
                    $('#modal_import_1').modal('toggle');
                    
                });
            }

            document.querySelector("#btn-nuevo").addEventListener("click",function()
            {
                if(confirm("¿Seguro que desea generar un nuevo proyecto?"))
                {
                    var datos = 
                    {
                        opc : 1
                    };
                    consultaAjax("{{url('/')}}/guardarProyecto",datos,20000,"POST",1);
                }
            });

                        
            document.querySelector("#btn-guardar").addEventListener("click",function(evnt)
                {
                    var linea = "";
                    for (var i = 0; i < document.querySelector("#linea").options.length; i++) {
                            
                            var x = document.querySelector("#linea").options[i];
                            linea += (x.selected ? 1: 0 ) + ";";
                    };

                    var id_proyecto_tipo = $('#id_proyecto_tipo').val();

                    // =========================================================================================================
                    // PROYECTO NOKIA
                    // =========================================================================================================
                    if(id_proyecto_tipo === 'T04') {
                      if(
                        document.querySelector("#nom_proyect").value              == "" ||
                        document.querySelector("#fec_crea").value                 == "" ||
                        document.querySelector("#per_a_cargo").value              == "" ||
                        document.querySelector("#id_proyecto_tipo").selectedIndex == 0 ||
                        document.querySelector("#val_ini").value                  == "" ||
                        document.querySelector("#num_alcaldia").value             == "" ||
                        document.querySelector("#barrio").value                   == "" ||
                        document.querySelector("#dire").value                     == ""
                      ) {
                        mostrarModal(1,null,"Crear Proyecto","Hace falta diligenciar campos.\n",0,"Aceptar","",null);
                        return;
                      }
                    }
                    // =========================================================================================================
                    // OTROS PROYECTOS
                    // =========================================================================================================
                    else if(
                      document.querySelector("#nom_proyect").value                  == "" ||
                      document.querySelector("#fec_crea").value                     == "" ||
                      document.querySelector("#per_a_cargo").value                  == "" ||
                      document.querySelector("#id_proyecto_tipo").selectedIndex     == 0 ||
                      document.querySelector("#val_ini").value                      == "" ||
                      document.querySelector("#id_zona").selectedIndex              == 0||
                      document.querySelector("#id_circuito").selectedIndex          == 0 ||
                      document.querySelector("#num_alcaldia").value                 == "" ||
                      document.querySelector("#barrio").value                       == "" ||
                      document.querySelector("#dire").value                         == ""
                    ) {
                      mostrarModal(1,null,"Crear Proyecto","Hace falta diligenciar campos.\n",0,"Aceptar","",null);
                      return;
                    }

                    var datos = 
                    {
                        opc : 0,
                        proy : document.querySelector("#id_proyect").value,
                        nom : document.querySelector("#nom_proyect").value,
                        fech : document.querySelector("#fec_crea").value,
                        cargo : document.querySelector("#per_a_cargo").value,
                        val : document.querySelector("#val_ini").value,
                        linea : linea,
                        diagr : (document.querySelector("#diagrama").checked ? 1 : 0),
                        //finalizado : (document.querySelector("#finalizado").checked ? 1 : 0),
                        zona : document.querySelector("#id_zona").value,
                        circ : document.querySelector("#id_circuito").value,
                        alc : document.querySelector("#num_alcaldia").value,
                        barr : document.querySelector("#barrio").value,
                        dire : document.querySelector("#dire").value,
                        tipo_trabajo : document.querySelector("#tipo_trabajo").value,
                        tipo_proceso : document.querySelector("#tipo_proceso").value,
                        bode : "",
                        obs : document.querySelector("#observaciones").value,
                        tipoP : document.querySelector("#id_proyecto_tipo").value
                    };
                    consultaAjax("{{url('/')}}/guardarProyecto",datos,20000,"POST",1);
                });
            
            document.querySelector("#btn-salir").addEventListener("click",function()
            {
                if(confirm("¿Seguro que desea salir?"))
                {
                    window.location.href = "{{url('/')}}/redes/ordenes/home";
                }
            });

            $('#contenido').DataTable();
            $('#contenido_nodos').DataTable();

            
            
            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none";
            //mostrarSincronizacion();

            // mostrarSincronizacion;
            // ocultarSincronizacion;
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
                    if(opcion == 1)
                    {
                        if(data == "0")
                        {
                            mostrarModal(1,null,"Guardar proyecto","No se pudo crear el proyecto, por favor intente más tarde.",0,"Aceptar","",null);
                            window.location.reload();
                        }
                        else
                        {
                            mostrarModal(2,null,"Guardar proyecto","Se ha actualizado correctamente el proyecto.",0,"Aceptar","",null);
                            location.href = location.href.split("/ver")[0] + "/ver/" + data; 
                        }
                    }

                    if(opcion == 2) //Actualiza WBS
                    {
                        if(data == "-1")
                        {
                            mostrarModal(1,null,"WBS","No se puede crear/actualizar la WBS, porque que ya existe el número de la WBS .\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }
                        $('#modal_wbs').modal('toggle');
                        document.querySelector("#wbs_content_tbl").innerHTML = data;


                        $("#select_wbs").find('option').remove(); 
                        $("#select_wbs_1").find('option').remove(); 
                        $("#select_wbs_1_mate").find('option').remove(); 
                        $("#select_wbs_gom").find('option').remove(); 


                        var html = "<option value='0'>Seleccione</option>"
                        for (var i = 0; i < document.querySelector("#tbl_wbs_content").children.length; i++) {
                            html += "<option value='" + document.querySelector("#tbl_wbs_content").children[i].children[0].dataset.wbs + "'>" + document.querySelector("#tbl_wbs_content").children[i].children[0].innerHTML +"</option>";
                        };

                        $("#select_wbs").html(html);
                        $("#select_wbs_1").html(html);
                        $("#select_wbs_1_mate").html(html);
                        $("#select_wbs_gom").html(html);

                        ocultarSincronizacion();
                        mostrarModal(2,null,"WBS","Se han actualizado correctamente las WBS",0,"Aceptar","",null);   
                        
                        /*Exportar Excel archivos*/
                        $("#contenido").DataTable(
                        {
                            dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        title: 'WBS Proyecto',
                                        exportOptions: {
                                            columns: [ 0]
                                        }
                                    }                                      
                                ]
                            
                        }
                        );  
                    }

                    if(opcion == 3)
                    {
                        $("#modal_wbs").modal("toggle");
                        document.querySelector("#wbs_content_tbl").innerHTML = data;
                        
                        mostrarModal(2,null,"WBS","Se han actualizado correctamente las WBS",0,"Aceptar","",null);

                        $("#contenido").DataTable(
                        {
                            dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        title: 'WBS Proyecto',
                                        exportOptions: {
                                            columns: [ 0]
                                        }
                                    }                                      
                                ]
                            
                        }
                        ); 


                        ocultarSincronizacion();

                    }

                    if(opcion == 4) //Update Create Nodos
                    {
                        if(data == "-1")
                        {
                            mostrarModal(1,null,"NODOS","Ya existe otro nodo con el mismo nombre",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        mostrarModal(2,null,"NODOS","Se han actualizado correctamente los NODOS del proyecto",0,"Aceptar","",null);

                        $("#nodo_content_tbl").html(data);

                        $('#modal_nodos').modal('toggle');
                        //consultaNODOStable();
                        //ocultarSincronizacion();
                        

                        $("#contenido_nodos").DataTable(
                        {
                            dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        title: 'NODOS Proyecto',
                                        exportOptions: {
                                            columns: [ 1,2,3,4,5]
                                        }
                                    }                                      
                                ]
                            
                        }
                        );
                        ocultarSincronizacion();

                    }

                    if(opcion == 5) //Eliminar NODOS
                    {
                        mostrarModal(2,null,"NODOS","Se ha eliminado correctamente el NODO del proyecto",0,"Aceptar","",null);

                        $("#nodo_content_tbl").html(data);

                        $('#modal_nodos').modal('toggle');
                        
                        $("#contenido_nodos").DataTable(
                        {
                            dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        title: 'NODOS Proyecto',
                                        exportOptions: {
                                            columns: [ 1,2,3,4,5]
                                        }
                                    }                                      
                                ]
                            
                        }
                        );
                        ocultarSincronizacion();
                    }
                    
                    if(opcion == 6) //Carga baremos
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

                    if(opcion == 7) //Consulta Nodo
                    {
                        $("#select_nodos").find("option").remove();
                        var html = "<option value='0'>Seleccione</option>";
                        for (var i = 0; i < data.length; i++) {
                            html += "<option value='" + data[i].nod + "'>";
                            html += data[i].nombre_nodo;
                            html += "</option>";
                        };
                        
                        $("#select_nodos").html(html);
                        ocultarSincronizacion();
                    }

                    if(opcion == 8) // Update actividades
                    {
                       
                        mostrarModal(2,null,"Actividades","Se han actualizado correctamente las Actividades del proyecto",0,"Aceptar","",null);
                        
                        $("#panel_tbl_actividades").html(data);

                        $("#actividades").DataTable(
                        {
                            dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        title: 'Actividades Proyecto',
                                        exportOptions: {
                                            columns: [ 1,2,3,4,5]
                                        }
                                    }                                      
                                ]
                            
                        });
                        $('#modal_acti').modal('toggle');
                        ocultarSincronizacion();

                    }
                    
                    if(opcion == 9) //Consulta Nodo
                    {
                        $("#select_nodos_material").find("option").remove();
                        var html = "<option value='0'>Seleccione</option>";
                        for (var i = 0; i < data.length; i++) {
                            html += "<option value='" + data[i].nod + "'>";
                            html += data[i].nombre_nodo;
                            html += "</option>";
                        };
                        
                        $("#select_nodos_material").html(html);
                        ocultarSincronizacion();
                    }

                    if(opcion == 10) //Carga Materiales
                    {
                        var html = "";
                        for (var i = 0; i < data.length; i++) {
                            
                            html += "<tr>";
                            html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarTablaMaterial(this)' data-mate='" + data[i].mate +"' data-nombre = '" + data[i].nombre + "' data-precio = '" + data[i].precio + "'></i></td>";
                            html += "<td>" + data[i].mate + "</td>";
                            html += "<td>" + data[i].nombre + "</td>";
                            html += "<td> $" + data[i].precio + "</td>";
                            html += "</tr>";
                        };
                        
                        $("#tbl_mate_add").html(html);
                        ocultarSincronizacion();
                    }

                    if(opcion == 11)
                    {
                        mostrarModal(2,null,"Actividades","Se han actualizado correctamente los materiales del proyecto",0,"Aceptar","",null);
                        
                        $("#panel_tbl_materiales").html(data);

                        $("#materiales").DataTable(
                        {
                            dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        title: 'Actividades Proyecto',
                                        exportOptions: {
                                            columns: [ 1,2,3,4,5]
                                        }
                                    }                                      
                                ]
                            
                        });
                        $('#modal_mat_add').modal('toggle');
                        ocultarSincronizacion();

                    }

                    if(opcion == 12)
                    {
                        mostrarSincronizacion();
                        window.location.href = "{{url('/')}}/redes/ordenes/ordentrabajo";
                    }

                    if(opcion == 13) //Consulta Balances
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

                    if(opcion == 14) //Consulta GOM asignadas WBS
                    {
                        document.querySelector("#tbl_gom_asociadas").innerHTML = data;

                        var input = $("#tbl_gom_wbs input");
                        for (var i = 0; i < input.length; i++) {
                            input[i].addEventListener("keydown",validaIngresoGOM);
                        };

                        ocultarSincronizacion();
                    }

                    if(opcion == 15) //Inserta GOM WBS
                    {
                        if(data.length == 2)
                        {
                            mostrarModal(1,null,"Agregar GOM","No se puede agregar la GOM a la WBS," + data[1],0,"Aceptar","",null); 
                            ocultarSincronizacion();
                            return;
                        }


                        mostrarModal(2,null,"Agregar GOM","Se ha asignado correctamente la GOM a la WBS",0,"Aceptar","",null); 

                        document.querySelector("#tbl_gom_asociadas").innerHTML = data;
                        var input = $("#tbl_gom_wbs input");
                        for (var i = 0; i < input.length; i++) {
                            input[i].addEventListener("keydown",validaIngresoGOM);
                        };
                        ocultarSincronizacion();
                    }

                    if(opcion == 16) //Elimina GOM WBS
                    {
                        if(data.id == -1)
                        {
                            mostrarModal(1,null,"ERROR",data.msg,0,"Aceptar","",null);
                        }
                        else
                        {
                            document.querySelector("#tbl_gom_asociadas").innerHTML = data;
                            var input = $("#tbl_gom_wbs input");
                            for (var i = 0; i < input.length; i++) {
                                input[i].addEventListener("keydown",validaIngresoGOM);
                            };    
                        }
                        
                        ocultarSincronizacion();
                    }

                    if(opcion == 17) //Update GOM WBS
                    {
                        document.querySelector("#tbl_gom_asociadas").innerHTML = data;
                        var input = $("#tbl_gom_wbs input");
                        for (var i = 0; i < input.length; i++) {
                            input[i].addEventListener("keydown",validaIngresoGOM);
                        };
                        ocultarSincronizacion();
                    }

                    if(opcion == 18) //Create Restriccion
                    {
                        document.querySelector("#content_Table_restricciones").innerHTML = data;
                        $("#modal_restricciones_add").modal("toggle");
                        setTimeout(function()
                        {
                            $("#modal_restricciones").modal("toggle");
                        },700);
                        $('#tbl_restric').DataTable();
                        ocultarSincronizacion();
                    }

                    if(opcion == 19) //FiLter Restricción
                    {
                        document.querySelector("#content_Table_restricciones").innerHTML = data;
                        $('#tbl_restric').DataTable();
                        ocultarSincronizacion();
                    }

                    if(opcion == 20) //Agrega circuitos
                    {
                        window.location.reload();
                    }
                    
                    if(opcion == 21) //Add Descargo
                    {   
                        if(data == "0")
                        {
                            mostrarModal(1,null,"Agregar descargo","Ya existe el descargo.\n",0,"Aceptar","",null);
                        }
                        else
                        {
                            document.querySelector("#text_descargo_add").value = "";
                            mostrarModal(2,null,"Agregar descargo","Se ha agregado correctamente el descargo.\n",0,"Aceptar","",null);
                            document.querySelector("#tbl_descargos_asociadas").innerHTML = data;
                            var select = $("#tbl_descargos_asociadas select");
                            for (var i = 0; i < select.length; i++) {
                                select[i].addEventListener("change",function()
                                {
                                    var array = 
                                    {
                                        pro :document.querySelector("#id_proyect").value,
                                        des :this.parentElement.parentElement.children[0].innerHTML,
                                        esta : this.value,
                                        opc : "15"
                                    }
                                    consultaAjax("{{url('/')}}/consultaBaremos",array,25000,"POST",22);
                                })
                            };

                            $("#descargos_add").DataTable();   
                        }
                        ocultarSincronizacion();
                    }

                    if(opcion == 22) //Update Descargo
                    {
                        ocultarSincronizacion();
                        mostrarModal(2,null,"Actualizar descargo","Se ha actualizado correctamente el descargo.\n",0,"Aceptar","",null);
                    }

                    if(opcion == 23) //Consulta LOGS
                    {
                        document.querySelector("#datos_log_user").innerHTML = data;
                        $("#modal_log").modal("toggle");
                        //$('#tbl_log').DataTable();
                        ocultarSincronizacion();
                        
                    }


                    if(opcion == 24) //Consulta Normas
                    {
                        var html = "";
                        html += "<option value='0'>Seleccione</option>";
                        for (var i = 0; i < data.length; i++) {
                             html += "<option value='" + data[i].norma + "'>" + data[i].norma  + " - " +  data[i].des_norma  + "</option>";
                        };
                        document.querySelector("#select_tipo").innerHTML = html;
                        ocultarSincronizacion();
                    }

                    if(opcion == 25) //Consulta Materiales y MOBEA
                    {
                        var html = "";
                        for (var i = 0; i < data[0].length; i++) {
                            
                            html += "<tr onclick='addElementoSelect(this)'>";
                            html += "<td>" + data[0][i].baremo + "</td>";
                            html += "<td>" + data[0][i].actividad + "</td>";
                            html += "<td><input style='width:30px' type='text' value='" + data[0][i].cant_baremo + "' /></td>";
                            html += "</tr>";
                        };

                        document.querySelector("#tbl_bar_lev").innerHTML = html;

                        var html = "";
                        for (var i = 0; i < data[1].length; i++) {
                            
                            html += "<tr onclick='addElementoSelect(this)'>";
                            html += "<td>" + data[1][i].codigo_sap + "</td>";
                            html += "<td>" + data[1][i].nombre + "</td>";
                            html += "<td>" + data[1][i].nombre2 + "</td>";
                            html += "<td><input style='width:30px' type='text' value='" + data[1][i].cant_material + "' /></td>";
                            html += "</tr>";
                        };

                        document.querySelector("#tbl_mat_lev").innerHTML = html;
                        ocultarSincronizacion();
                    }

                    if(opcion == 26) //Consulta Nodo
                    {
                        $("#select_nodos_levantamiento").find("option").remove();
                        var html = "<option value='0'>Seleccione</option>";
                        for (var i = 0; i < data.length; i++) {
                            html += "<option value='" + data[i].nod + "'>";
                            html += data[i].nombre_nodo;
                            html += "</option>";
                        };
                        
                        $("#select_nodos_levantamiento").html(html);
                        ocultarSincronizacion();
                    }

                    if(opcion == 27)
                    {
                        window.location.reload();
                    }

                    if(opcion == 28) //Consulta proyecto
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
                error:function(x,y,error){
                    ocultarSincronizacion();
                        ocultarSincronizacion();
                    //$('#filter_registro').modal('toggle');
                    
                    if (x.status === 0) {
                        alert('Not connected.\nPor favor verificar la conexión a internet.');
                    } else if (x.status == 404) {
                        alert('The requested page not found. [404]');
                    } else if (x.status == 500) {
                        alert('Internal Server Error [500].');
                    } else if (y === 'parsererror') {
                        alert('Requested JSON parse failed.');
                    } else if (y === 'timeout') {
                        alert('Time out error.');
                    } else if (y === 'abort') {
                        alert('Ajax request aborted.');
                    } else {
                        alert('Uncaught Error.\n' + x.responseText);
                    }

                    /*mostrarModal(1,null,"Consulta de Alianzas","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);*/
                    setTimeout(function()
                    {
                        //location.reload();
                    },3000);
                }
                    
            });
        }


        /*LEVANTAMIENTO V2*/
        function addElementoSelect(ele)
        {
            if(ele.dataset.select != undefined && ele.dataset.select != null)
            {
                if(ele.dataset.select == "1")
                {
                    ele.dataset.select = "0";
                    ele.style.background = "white";
                }
                else
                {
                    ele.dataset.select = "1";
                    ele.style.background = "rgba(0, 0, 255, 0.41)";
                }
            }
            else
            {
                ele.dataset.select = "1";
                ele.style.background = "rgba(0, 0, 255, 0.41)";
            }
        }

        function saveLevantamiento()
        {
            if(document.querySelector("#select_wbs_levantamiento").selectedIndex == 0)
            {
                mostrarModal(1,null,"Carga Levantamiento","Seleccione WBS\n",0,"Aceptar","",null);
                return;
            }

            if(document.querySelector("#select_nodos_levantamiento").selectedIndex == 0)
            {
                mostrarModal(1,null,"Carga Levantamiento","Seleccione Nodo\n",0,"Aceptar","",null);
                return;
            }

            if(document.querySelector("#select_estruc").selectedIndex == 0)
            {
                mostrarModal(1,null,"Carga Levantamiento","Seleccione Estructura\n",0,"Aceptar","",null);
                return;
            }

            if(document.querySelector("#select_tipo").selectedIndex == 0)
            {
                mostrarModal(1,null,"Carga Levantamiento","Seleccione tipo estructura\n",0,"Aceptar","",null);
                return;
            }

            

            var bar = document.querySelector("#tbl_bar_lev").children;
            var mat = document.querySelector("#tbl_mat_lev").children;

            var wbs = document.querySelector("#select_wbs_levantamiento").value;
            var nodo = document.querySelector("#select_nodos_levantamiento").value;

            var arratBarem = Array();
            for (var i = 0; i < bar.length; i++) {
                arratBarem.push(
                    {
                        "bare" : bar[i].children[0].innerHTML,
                        "cant" : bar[i].children[2].children[0].value
                    });
            };

            var arrayMat = Array();
            for (var i = 0; i < mat.length; i++) {
                arrayMat.push(
                    {
                        "mat" : mat[i].children[0].innerHTML,
                        "cant" : mat[i].children[3].children[0].value
                    });
            };

            var array = 
            {
                wbs : wbs,
                nod : nodo,
                mat : arrayMat,
                proy : document.querySelector("#id_proyect").value,
                bar : arratBarem,
                opc : "18"
            }
            consultaAjax("{{url('/')}}/consultaBaremos",array,55000,"POST",27);
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
            consultaAjax("{{url('/')}}/consultaActiMate",datos,20000,"POST",28);
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

        /**
        * @author Edwin A. Triviños Ramírez
        * @date 2018-06-12
        */
        function validar_tipo_proyecto_seleccionado() {
          var id_proyecto_tipo = $('#id_proyecto_tipo').val();

          // ====================================================================================
          // PROYECTO NOKIA
          // ====================================================================================
          if(id_proyecto_tipo === 'T04') {
            // TIPO PROCESO
            $('#tipo_proceso').val('');
            $("#tipo_proceso option[value='NO_APLICA']").prop("disabled", "");

            // ==================================================================================
            // CIRCUITO / LINEA
            // ==================================================================================
            $("#wrapper_linea").hide();
            $("#wrapper_id_circuito").hide();

            $("#linea").val('').prop("required", false);
            $("#id_circuito").val('').prop("required", false);
            $('#linea, #id_circuito').selectpicker('refresh');
          }
          // ====================================================================================
          // OTROS PROYECTOS
          // ====================================================================================
          else {
            // ==================================================================================
            // TIPO PROCESO
            // ==================================================================================
            $('#tipo_proceso').val('');
            $("#tipo_proceso option[value='NO_APLICA']").prop("disabled", "disabled");

            // ==================================================================================
            // CIRCUITO / LINEA
            // ==================================================================================
            $("#wrapper_linea").show();
            $("#wrapper_id_circuito").show();

            // $("#linea").val('').prop("required", true);
            $("#id_circuito").val('').prop("required", true);
            $('#linea, #id_circuito').selectpicker('refresh');
          }
        }

        /**
        * @author Edwin A. Triviños Ramírez
        * @date 2018-06-12
        */
        function inicializar_proyecto_telco() {
          var id_proyecto_tipo = $('#id_proyecto_tipo').val();

          if(id_proyecto_tipo === 'T04') {
            $("#wrapper_linea").hide();
            $("#wrapper_id_circuito").hide();

            $("#tipo_proceso option[value='NO_APLICA']").prop("disabled", "");
          }
        }

        /**
        * @author Edwin A. Triviños Ramírez
        * @date 2018-06-12
        */
        $(document).ready(function() {
          inicializar_proyecto_telco();
        });


    </script>
@stop

