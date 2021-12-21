@extends('template.index')

@section('title')
	Cerrar restricción
@stop

@section('title-section')
    Cerrar restricción
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

        @include('proyectos.redes.trabajoprogramado.modal.modalconsultaproyectos')

        @include('proyectos.redes.trabajoprogramado.modal.modalLogRestricciones')


		<div class="container-fluid">
            <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                      
                        

                        <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 3px;    padding-bottom: 3px;">
                        <div class="row">

                        

                        {!! Form::open(['url' => 'cerrarresticciones', "method" => "GET", "id" => "form_restriccion"]) !!}
                            <div class="col-md-2">
                                    <div class="form-group has-feedback">
                                        <label for="id_orden">Proyecto:</label>
                                        <input  id="id_proyecto" type="text" readonly="" value="{{Session::get('proyecto_res')}}" class="form-control" style="width: 81%;padding:0px;float: left;" placeholder="Proyecto">
                                        <a onclick="abrirModal(1)" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-search" style="    position: relative;    top: -2px;"></i></a>
                                    </div>
                            </div>

                            <div class="col-md-2">
                                    <div class="form-group has-feedback">
                                        <label for="id_orden">Orden de trabajo:</label>
                                        <select id="name_orden" type="text" class="form-control" style="max-width:250px;padding:0px;" placeholder="Orden de trabajo">
                                        <option value="0">Seleccione</option>
                                        @foreach($ordC as $key => $valor)
                                            @if(Session::get('orden_restriccion') == $valor->id_orden)
                                                <option selected value="{{$valor->id_orden}}">{{$valor->id_orden}}</option>
                                            @else
                                                <option value="{{$valor->id_orden}}">{{$valor->id_orden}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                            </div>

                                <div class="col-md-2">
                                    <div class="form-group has-feedback">
                                        <label for="id_orden">Restricción:</label>
                                        <select id="id_restriccion"  type="text"  class="form-control" style="width: 100%;padding:0px;float: left;" placeholder="Responsable">
                                        <option value="0">Seleccione</option>
                                        @foreach($restC as $key => $valor)
                                            @if(Session::get('rest') == $valor->id_restriccion)
                                                <option selected value="{{$valor->id_restriccion}}">{{$valor->nombre}}</option>
                                            @else
                                                <option value="{{$valor->id_restriccion}}">{{$valor->nombre}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <a style="position: relative;    top: 22px;" href="#" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir" onclick="limpiarDatos();"><i class="fa fa-times"></i> Limpiar</a>
                                </div>
                            </div>
                        {!!Form::close()!!}
                        </div>
                    </div>
            </div>

            <?php if(!$correos): ?>
              <div class="alert alert-danger text-center">
                No existen correos para la restricción <?php print $id_restriccion; ?>
              </div>
            <?php endif; ?>

            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0" style="margin:0px;padding:0px;">
                <div class="modal-body">

                    


                @if($correos != null)

                <button data-res="{{$restD->id_restriccion}}" style="margin-left:25px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" onclick="consulta_log_restriccion({{$restD->id_restriccion}})" >
                                <i class="fa fa-refresh"></i> &nbsp;&nbsp;Eventos de la restricción
                        </button>

                    <div class="row"> 
                      <div class="col-md-6">
                        <div class="row" style="    margin-top: 16px;">
                          <div class="form-group has-feedback">
                            <label class="control-label col-sm-12" for="select_nodos">Estado:</label>
                            @if($restD->id_estado == "A")
                              <div class="col-md-12">
                                  <div class="input-group" style="width:90%;float:left;">
                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    <select type="text" class="form-control" id="estado_restric" name="estado_restric">
                                        <option value="A" >Por iniciar</option>
                                        <option value="P" >En proceso</option>
                                        <option value="X" >Anulada</option>
                                        <option value="C" >Levantada</option>
                                    </select>
                                  </div>
                                  <!--<span style="background-color:red;color:white;display:inline-block;height:20px;width:20px;    margin-left: 10px;   margin-top: 4px;"></span>-->
                              </div>
                            @else
                                @if($restD->id_estado == "P")
                                  <div class="col-md-12">
                                      <div class="input-group" style="width:90%;float:left;">
                                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        <select type="text" class="form-control" id="estado_restric" name="estado_restric">
                                            <option value="P" >En proceso</option>
                                            <option value="X" >Anulada</option>
                                            <option value="C" >Levantada</option>
                                        </select>
                                      </div>
                                      <!--<span style="background-color:red;color:white;display:inline-block;height:20px;width:20px;    margin-left: 10px;   margin-top: 4px;"></span>-->
                                  </div>
                                @else
                                  @if($restD->id_estado == "X")
                                      <p style="    color: black;    margin-left: 15px;    font-weight: bold;    font-size: 20px;">ANULADA</p>
                                  @else
                                      <p style="    color: rgb(0,143,65);    margin-left: 15px;    font-weight: bold;    font-size: 20px;">LEVANTADA</p>
                                  @endif
                                @endif
                            @endif
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="row" style="    margin-top: 16px;">
                          <div class="form-group has-feedback">
                            <label class="control-label col-sm-12" for="select_nodos">Tipo de restricción:</label>
                              <div class="col-md-12">
                                  <div class="input-group">
                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    <input readonly type="text" class="form-control" id="text_restric" name="text_restric" value="{{$restD->texto_restriccion1}}">
                                  </div>
                              </div>
                            </div>
                        </div>
                        </div>
                       
                       <div class="col-md-6">
                        <div class="row" style="    margin-top: 16px;">
                          <div class="form-group has-feedback">
                            <label class="control-label col-sm-12" for="select_nodos">Descripción de restricción:</label>
                              <div class="col-md-12">
                                  <div class="input-group">
                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    <textarea readonly type="text" style="height: 66px;" class="form-control" id="text_descp_restriccion" name="text_impacto" >{{$restD->restriccion_descripcion}}</textarea>
                                  </div>
                            </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                       <div class="col-md-6">
                        <div class="row" style="    margin-top: 16px;">
                          <div class="form-group has-feedback">
                            <label class="control-label col-sm-12" for="select_nodos">Impacto:</label>
                              <div class="col-md-12">
                                  <div class="input-group">
                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    <textarea readonly type="text" style="height: 66px;" class="form-control" id="text_impacto" name="text_impacto">{{$restD->impacto}}</textarea>
                                  </div>
                            </div>
                            </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="row" style="    margin-top: 16px;">
                        <div class="form-group has-feedback" style="    margin-left: 14px;">
                              <label for="fec_limite">Fecha limite:</label>
                                  <input readonly class="form-control" size="16" style="height:30px;" type="text"
                                         value="{{explode(" ",$restD->fecha_limite)[0]}}" name="fec_limite" id="fec_limite" placeholder="dd/mm/aaaa" required>
                              
                          </div>
                        </div>
                        </div> 
                    </div>
                    <div class="row"> 
                      <div class="col-md-6">
                        <div class="row" style="    margin-top: 16px;">
                          <div class="form-group has-feedback">
                            <label class="control-label col-sm-12" for="select_nodos">Responsable:</label>
                              <div class="col-md-12">
                                  <div class="input-group">
                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    <input  readonly value="{{$restD->responsable}}" type="text" class="form-control" id="text_responsable_restric" name="text_responsable_restric"/>
                                  </div>
                            </div>
                            </div>
                        </div>
                      </div>

                      @if($restD->fecha_cierre != "")
                        <div class="col-md-6" id="evidencia_cierre_input1">
                        <div class="row" style="    margin-top: 16px;">
                        <div class="form-group has-feedback" style="    margin-left: 14px;">
                              <label for="fec_limite">Fecha cierre:</label>
                                  <input readonly class="form-control" size="16" style="height:30px;" type="text"
                                         value="{{explode(" ",$restD->fecha_cierre)[0]}}" name="fec_limite" id="fec_limite" placeholder="dd/mm/aaaa" required>
                              
                          </div>
                        </div>
                        </div> 
                      @endif
                    </div>
                    <div class="row" style="display:none" id="row_estado">

                    </div>
                      <p style="    margin-left: 28px;    margin-top: 11px;">Lista de responsables</p>
                      <div class="row">
                        <div class="col-md-6">
                          <table style="    margin-left: 15px;    margin-top: 20px;">
                            <tbody  id="correo_enviar">
                                @foreach($correos as $key => $valor)
                                <tr>
                                    <td style="padding:10px;">{{$valor->responsable}}</td>
                                    <td style="padding:10px;">{{$valor->correo}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                          </table>
                          </div>
                    @if($restD->fecha_cierre == "")
                        <div class="col-md-6" id="evidencia_cierre_input">
                            <div class="row" style="    margin-top: 16px;">
                            <div class="form-group has-feedback" style="    margin-left: 14px;">
                                  <label for="fec_limite">Evidencia de cierre:</label>
                                <br>
                                    <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar evidencia" data-size="sm" name="evidencia_cierre" id="evidencia_cierre"/>                       
                              </div>
                            </div>
                        </div> 
                    @else
                        <div class="col-md-6" id="evidencia_cierre_input">
                            <div class="row" style="    margin-top: 16px;">
                            <div class="form-group has-feedback" style="    margin-left: 14px;">
                                  <label for="fec_limite">Evidencia de cierre:</label>
                                <br>
                                    <a target="blank_" href="http://201.217.195.43{{$restD->evidencia_cierre}}">Ver</a>                          
                              </div>
                            </div>
                        </div> 
                    @endif
                    </div>
                    @if($restD->id_estado == "A" || $restD->id_estado == "P")
                        <button data-res="{{$restD->id_restriccion}}" style="margin-left: 30px;margin-top: 18px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" id="btn_save_restriccion">
                                <i class="fa fa-save"></i> &nbsp;&nbsp;Guardar restricción
                        </button>
                    @endif
                @endif
              </div>
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',inicerrarR);
        var cargaArchivo = false;
        var arregloInformacion = [];
        var mime = "";

        

        function inicerrarR()
        {
            /*
            var datos = 
            {
                opc: 13,
                lid: document.querySelector("#select_lider_carga1").value,
                ot : document.querySelector("#text_conci_2").value,
                nodo: document.querySelector("#select_nodos_afectados1").value,
                dc : ele.innerHTML
            }

            dcSelec = ele.innerHTML;
            consultaAjax("../../../consultaActiMate",datos,120000,"POST",31);   */

            $("#evidencia_cierre").change(function(ele){ 
                //if(validarArchivoPDF(this))
                //{
                  cargaArchivo = true;
                  var file = document.querySelector('#evidencia_cierre').files[0];

                  var fic = file.name;


                  arregloInformacion = [];
                  mime = file.type;

                  var str = mime; 
                  mime = "";

                  if(str.indexOf("pdf") !== -1)
                    mime = "pdf";

                  if(str.indexOf("image") !== -1)
                    mime = "png";

                  if(fic.indexOf("msg") !== -1)
                    mime = "msg";                  

                  if(mime == "")                  
                  {
                    mostrarModal(1,null,"Tipo de archivo restricción","Los tipos de archivo que puede cargar son PDF,MSG e imagenes.\n",0,"Aceptar","",null);
                    $("#evidencia_cierre").filestyle('clear');
                    document.querySelector("#evidencia_cierre").value = "";
                    return;   
                  }

                  uploadFileToServer(file,arregloInformacion);
            });


            document.querySelector("#name_orden").addEventListener("change",function()
            {
                 document.querySelector("#form_restriccion").action = document.querySelector("#form_restriccion").action + "/" + document.querySelector("#id_proyecto").value + "/" + this.value;
                 document.querySelector("#form_restriccion").submit();
            });

            document.querySelector("#id_restriccion").addEventListener("change",function()
            {
                if(this.selectedIndex == 0)
                    return;
                 document.querySelector("#form_restriccion").action = document.querySelector("#form_restriccion").action + "/" + document.querySelector("#id_proyecto").value + "/" + document.querySelector("#name_orden").value + "/" +  this.value; 
                 document.querySelector("#form_restriccion").submit();
            });
            @if($correos != null)
            @if($restD->id_estado == "A" || $restD->id_estado == "P")
            //Cambiar Estado
            document.querySelector("#estado_restric").addEventListener("change",function()
            {
                if(this.value == "C")
                {
                    document.querySelector("#evidencia_cierre_input").style.display = "block";
                    document.querySelector("#btn_save_restriccion").style.display = "inline-block";
                }
                else
                {
                    document.querySelector("#evidencia_cierre_input").style.display = "none";
                    if(this.value == "P" || this.value == "X")
                       document.querySelector("#btn_save_restriccion").style.display = "inline-block"; 
                    else
                       document.querySelector("#btn_save_restriccion").style.display = "none"; 
                }
            });
            @endif
            @if($restD->id_estado == "A" || $restD->id_estado == "X" || $restD->id_estado == "P")
                document.querySelector("#evidencia_cierre_input").style.display = "none";
            @endif

            @if($restD->id_estado == "A")
                document.querySelector("#btn_save_restriccion").style.display = "none";
            @endif
            
            @if($restD->id_estado == "A" || $restD->id_estado == "P")
                document.querySelector("#btn_save_restriccion").addEventListener("click",function()
                {
                    if(document.querySelector("#estado_restric").value == "C")
                    {
                        if(document.querySelector("#evidencia_cierre").value == "")
                        {
                            mostrarModal(1,null,"Guardar restricción","Hace falta ingresar información, para guardar la restricción.\n",0,"Aceptar","",null);
                            return;   
                        }
                    }
                    if(document.querySelector("#estado_restric").value == "X" || document.querySelector("#estado_restric").value == "P")
                    {
                        var array = 
                        {
                            esta : document.querySelector("#estado_restric").value,
                            res_id : document.querySelector("#btn_save_restriccion").dataset.res,
                            archivo : "",
                            opc : "16"
                        }
                        consultaAjax("../../../guardarAsignacionRecursosMateriales",array,25000,"POST",2);
                    }
                    else
                    {
                        if(document.querySelector("#estado_restric").value == "C")
                        {
                            var array = 
                            {
                                esta : document.querySelector("#estado_restric").value,
                                res_id : document.querySelector("#btn_save_restriccion").dataset.res,
                                archivo : arregloInformacion[0],
                                mime : mime,
                                opc : "16"
                            }
                            consultaAjax("../../../guardarAsignacionRecursosMateriales",array,25000,"POST",2);
                        }
                    }
                });
            @endif
            @endif
           ocultarSincronizacionFondoBlanco();
        }

        function abrirModal(opc)
        {
            if(opc == 1)
                $("#modal_proyecto").modal("toggle");
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
            consultaAjax("../../../consultaActiMate",datos,200000,"POST",1);
        }

        function consulta_log_restriccion(res)
        {
          var datos = 
                {
                    opc : 33,
                    res : res
                };

            consultaAjax("../../../consultaActiMate",datos,20000,"POST",3);
        }


        function salir(opc)
        {
            if(opc == 1)
                $("#modal_proyecto").modal("toggle");  

        }

        function limpiarDatos()
        {
            document.querySelector("#id_proyecto").value = "";
            document.querySelector("#name_orden").innerHTML = "";
            document.querySelector("#id_restriccion").innerHTML = "";
            document.querySelector("#form_restriccion").submit();
        }


        function agregarProyectoFilter(ele)
        {
            document.querySelector("#id_proyecto").value =  ele.parentElement.parentElement.children[2].innerHTML;
            document.querySelector("#form_restriccion").action = document.querySelector("#form_restriccion").action + "/" + document.querySelector("#id_proyecto").value;
            $("#modal_proyecto").modal("toggle");  
            document.querySelector("#form_restriccion").submit();
        }


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
                    if(opcion == 1) //Consulta proyecto
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
                        ocultarSincronizacion();
                    }

                    if(opcion == 2) //Save restricción
                    {
                        ocultarSincronizacion();
                        mostrarModal(2,null,"Guardar restricción","Se ha guardado correctamente la restricción.\n",0,"Aceptar","",null);
                        setTimeout(function()
                        {
                            location.reload();
                        },200);
                    }

                    
                    if(opcion == 3) //Consulta Log Restricciones
                    {

                      var html = "";

                      for (var i = 0; i < data.length; i++) {
                        html += "<tr>";
                        html += "<td>" + data[i].nombre + "</td>";
                        html += "<td>" + data[i].fecha + "</td>";
                        html += "<td>" + data[i].propietario + "</td>";
                        html += "</tr>";
                      }
                      
                      document.querySelector("#datos_log_user").innerHTML = html;
                      ocultarSincronizacion();
                      $("#modal_log_restric").modal("toggle");
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

    </script>
@stop

