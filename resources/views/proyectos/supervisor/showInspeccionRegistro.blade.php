@extends('template.index')

@section('title')
	Inspección de ordenes
@stop

@section('title-section')
    Inspección de ordenes
@stop

@section('content')
    <style type="text/css">
    #tbl_inspecciones_filter
    {
        position: relative;
        left: 100px;
    }

    </style>
	<main>

        <!-- Import Modal -->
        @include('proyectos.supervisor.modal.modalPlanAccion')
        @include('proyectos.supervisor.modal.modalVerIpal')
        @include('proyectos.supervisor.modal.modalVerLog')
        
		<div class="container-fluid">

            @include('proyectos.supervisor.secciones.frmEncabezadoInspeccion')

            <!-- =============================================================================================================================== -->
            <!-- Botones Inspección -->
            <!-- =============================================================================================================================== -->
            @if($inspeccion->estado <> 'E2')
              <a 
                style="position: relative;margin-left: 26px;"  
                id="btn-ver-ipal" 
                class="btn btn-primary btn-cam-trans btn-sm">
                  <i class="fa fa-file-o"></i> Ver Inspección
              </a>
              
              <a 
                style="position: relative;display:none" 
                onclick="cerrar_inpeccion()" 
                class="btn btn-primary btn-cam-trans btn-sm">
                  <i class="fa fa-file-o"></i> 
                  Finalizar inspección
              </a>
            @else
              <a 
                style="position: relative;margin-left: 26px;" 
                id="btn-ver-ipal" 
                class="btn btn-primary btn-cam-trans btn-sm">
                  <i class="fa fa-file-o"></i>  
                  Ver Inspección
              </a>
            @endif

           

            <a 
              style="position: relative;" 
              onclick="abrirLog();" 
              class="btn btn-primary btn-cam-trans btn-sm">
                <i class="fa fa-refresh"></i> 
                Ver LOG
            </a>

            <a 
              style="position: relative;" 
              href="../../inspeccionOrdenes" 
              id="btn-salir" 
              class="btn btn-primary btn-cam-trans btn-sm" 
              id="btn-salir">
                Cerrar
            </a>

         

            <!-- =============================================================================================================================== -->
            <!-- MAPA -->
            <!-- =============================================================================================================================== -->

            <?php if($latitud && $longitud): ?>
              <script type="text/javascript" src="http://maps.google.com/maps/api/js?region=CO&key=AIzaSyAUYrvReDG54RWrEo-X64GzzMn1daq2IBg"></script>

              <div class="row" style="padding: 20px;">
                <div class="col-md-12">
                  <div id="map" style="width: 100%; height: 400px;"></div>

                  <div class="maps-buttons" style="margin-top: 5px;">
                    <a 
                      href="https://maps.google.com/?q=<?php print $latitud; ?>,<?php print $longitud; ?>"
                      target="_blank"
                      title="Ver en Google Maps">
                        Ver en Google Maps
                    </a>
                  </div>
                </div>
              </div>

              <!-- Coordenadas -->
              <!--
              <div class="row">
                  <div class="col-md-2">
                      <div class="form-group has-feedback">
                    <label for="text_latitud">Latitud</label>
                    <input 
                      name="text_latitud" 
                      id="text_latitud" 
                      class="form-control" 
                      size="16" 
                      type="text" 
                      value="{{ $latitud }}"
                      readonly 
                      placeholder="Latitud"/>
                  </div>
                  </div>

                  <div class="col-md-2">
                      <div class="form-group has-feedback">
                    <label for="text_longitud">Longitud</label>
                    <input 
                      name="text_longitud" 
                      id="text_longitud" 
                      class="form-control" 
                      size="16" 
                      type="text" 
                      value="{{ $longitud }}"
                      readonly 
                      placeholder="Longitud"/>
                  </div>
                  </div>
              </div>
              -->

              <script type="text/javascript">
                  window.onload = function () {
                  function initialize() {
                    var latitud   = '<?php print $latitud; ?>';
                    var longitud  = '<?php print $longitud; ?>';

                    if(latitud && longitud) {
                        var infowindow = new google.maps.InfoWindow();


                        var mapDiv = document.getElementById("map");
                        var latlng = new google.maps.LatLng(latitud, longitud);

                        // Map Options
                        var mapOptions = {
                            zoom: 14,
                            center: latlng,
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                            imageDefaultUI: true
                        };

                        // Create the map
                        var map = new google.maps.Map(mapDiv, mapOptions);
                        var userPathCoordinates = [];

                        // Create the marker
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(latitud, longitud),
                            title: 'COORDENADAS',
                        });

                        // Add the marker
                        marker.setMap(map);

                        // Add the info window to the marker
                        google.maps.event.addListener(marker, 'click', (function (marker) {
                            return function () {
                                var windowContent = '<div class="container-fluid" style="height: 100px; width: 300px;">' +
                                                                        '<div class="row">' +
                                                                            '<strong>Latitud: </strong> '   + latitud   + '<br/>' + 
                                                                            '<strong>Longitud: </strong> '  + longitud  + '<br/>' + 
                                                                        '</div>' +
                                                                    '</div>';

                                infowindow.setContent(windowContent);
                                infowindow.open(map, marker);
                            }
                        })(marker));
                    }
                  }

                  initialize();
                };
              </script>

              <style type="text/css">
                  #map {
                  width: 100%;
                  height: 700px;
                  border: 1px solid #000;
                }
              </style>
            <?php else: ?>
              <div class="row" style="padding: 20px;">
                <div class="col-md-12">
                  <div class="alert alert-danger">
                    No existen coordenadas relacionadas a la inspección actual.
                  </div>
                </div>
              </div>
            <?php endif; ?>

            <!-- =============================================================================================================================== -->
            <!-- CAUSAS -->
            <!-- =============================================================================================================================== -->
            <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
                <input type="hidden" value="{{$insp}}" id="id_inspeccion"/>
                <h3>Causa raíz</h3> 
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-9">
                                <div class="form-group has-feedback">
                                    
                                    @if($inspeccion->estado <> 'E2')
                                    <label for="id_orden" >Item no conforme:</label>
                                    <br>
                                    
                                    <select class="form-control" style="width:25%;display:inline-block;" id="causa_add_select">
                                         @foreach($form as $key => $valor)
                                            @if($valor->respuesta == 1)
                                                <option value="{{$valor->id_pregunta}}">{{$valor->descrip_pregunta}}</option>
                                            @endif
                                         @endforeach
                                    </select>
                                    @endif

                                    @if($inspeccion->estado <> 'E2')
                                    <label for="id_orden" style="position:relative;top:-25px;">Causa raíz:</label>
                                    <br>
                                    
                                    <input type="text" placeholder="Ingrese la causa"  class="form-control"  style="width:33%;position:relative; top: -30px;    margin-left: 25.5%;display: inline-block;" id="text1"/>

                                    <br>
                                    
                                    <label for="id_orden" style="position:relative;top:-25px;">Adjunto:</label>
                                    
                                    <div style="    width: 59%;    position: relative;    top: -25px;">
                                        <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar adjunto" data-size="sm" id="adjunto" name="adjunto"   style="width:23%;">    
                                    </div>
                                    
                                    
                                    @if($inspeccion->resultado == "NC")
                                    <button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="addcausa()">
                                        <i class="fa fa-plus"></i> &nbsp;&nbsp;Agregar causa
                                    </button>
                                    @endif
                                    @endif
                                    
                                </div>

                                <table id="tbl_causa" class="table table-striped table-bordered" cellspacing="0" width="99%">
                                    <thead>
                                        <tr>
                                            <th style="width:200px;">Item</th>
                                            <th style="width:200px;">Causa raíz</th>
                                            <th style="width:100px;">Adjunto</th>
                                            @if($inspeccion->estado <> 'E2' && $inspeccion->estado <> 'A1')
                                                <th style="width:20px;"></th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_causas">
                                        @foreach($causas as $key => $valor)
                                            <tr>
                                                <td data-id="{{$valor->id}}" data-analisis="{{$valor->id_item}}">{{$valor->des_item}}</td>
                                                <td>{{$valor->analisis}}</td>

                                                <td>
                                                    @if($valor->adjunto != "" && $valor->adjunto != NULL)
                                                        <a target="_blank" style="text-align: center;display: block" href="http://190.60.248.195/anexos_apa/anexos/{{$valor->adjunto}}">Ver adjunto</a>
                                                    @endif
                                                </td>
                                                
                                                
                                                @if($inspeccion->estado <> 'E2' && $inspeccion->estado <> 'A1')
                                                    <td><i class='fa fa-times' onclick='deleteCausa(this)' style='    font-size: 20px;  color: red;  display: block;  text-align: center;    cursor: pointer;'></i></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div> 
                    </div>    
                </div>
              


                
                <h3>Planes de Acción</h3> 
                @if($inspeccion->resultado == "NC")
                    @if($inspeccion->estado <> 'E2')
                    <div class="col-md-12">
                        <button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="addModalInspeccion()">
                            <i class="fa fa-plus"></i> &nbsp;&nbsp;Agregar plan de acción
                        </button>
                    </div>
                    @endif
                @endif
            </div>

            @if($inspeccion->estado <> 'E2')
                <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0" style="margin-top:70px;">
            @else
                <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0" style="margin-top:20px;">
            @endif
                @include('proyectos.supervisor.tables.tblplanaccion')
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);
        var binaryString;
        var extencion ='pdf';

        function cerrar_inpeccion()
        {
            if(confirm("¿Seguro que desea finalizar la inspección?"))
            {
                var array = 
                {
                    ins : document.querySelector("#id_inspeccion").value, 
                    tip : "15"
                };

                consultaAjax("../../../saveSupervision",array,15000,"POST",6); 

            }
        }

        var arregloInformacion = [];
        var cargaArchivo = false;

        function ini()
        {  

            document.querySelector("#plan_accion_select").addEventListener("change",function()
            {
                modalDatos();

            });

            $("#adjunto").change(function(ele){ 
                //if(validarArchivoPDF(this))
                //{
                  cargaArchivo = true;
                  var file = document.querySelector('#adjunto').files[0];

                  var fic = file.name;


                  arregloInformacion = [];
                  mime = file.type;

                  var str = mime; 
                  mime = "";

                  if(str.indexOf("pdf") !== -1)
                    mime = "pdf";

                  if(str.indexOf("image") !== -1)
                    mime = "png";


                  if(mime == "")                  
                  {
                    mostrarModal(1,null,"Tipo de archivo causa raíz","Los tipos de archivo que puede cargar son PDF e imagenes.\n",0,"Aceptar","",null);
                    $("#adjunto").filestyle('clear');
                    document.querySelector("#adjunto").value = "";
                    return;   
                  }

                  uploadFileToServer(file,arregloInformacion);
            });

            @if(Session::has('abrir'))
                $("#modal_ipal").modal("toggle");  
                <?php
                    Session::forget('abrir');
                ?>
            @endif

            var alto = screen.height - 400;
            var altopx = alto+"px";
            $('#tbl_inspecciones').dataTable({
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

            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none"; 

             var handleFileSelect = function(evt) {
                var files = evt.target.files;
                var file = files[0];
               // console.log(file);
                var nombre = file['name'];
                extencion = nombre.substring(nombre.lastIndexOf('.')+1, nombre.length);
              //  console.log("extencion "+extencion);


                if (files && file) {
                    var reader = new FileReader();

                    reader.onload = function(readerEvt) {
                        binaryString = btoa(readerEvt.target.result);
                    };
                    reader.readAsBinaryString(file);
                }
            };

            document.getElementById('fileevidencia').addEventListener('change', handleFileSelect);

            document.querySelector("#btn-ver-ipal").addEventListener("click",function()
            {
                $("#modal_ipal").modal("toggle");  
            });
        }

        function modalDatos()
        {
             var texto = document.querySelector("#plan_accion_select").options[document.querySelector("#plan_accion_select").selectedIndex].text;
                var tr = document.querySelector("#tbl_causas").children;

                var arreglo = [];
                for (var i = 0; i < tr.length; i++) {
                    if(tr[i].children[0].innerHTML == texto)
                        arreglo.push(tr[i].children[0].dataset.id);
                };
                var combox = document.querySelector("#analisis_causa_modal").children;
                var arregloDatos = [];
                for (var i = 0; i < combox.length; i++) {
                    combox[i].style.display = "none";
                    for (var j = 0; j < arreglo.length; j++) {
                        if(arreglo[j] == combox[i].value)
                            arregloDatos.push(combox[i]);
                    };
                };
                var valueSelect = "";
                for (var i = 0; i < arregloDatos.length; i++) {
                    arregloDatos[i].style.display = "block";
                    if(i == 0)
                        valueSelect = arregloDatos[i].value;
                };

                document.querySelector("#analisis_causa_modal").value = valueSelect;
        }


        function abrirLog()
        {
            var array = 
                {
                    ins : document.querySelector("#id_inspeccion").value, 
                    tip : "13"
                }
            consultaAjax("../../../saveSupervision",array,15000,"POST",4);   

        }


        var opcModal = -1;
        function addModalInspeccion()
        {


            if($('#analisis_causa_modal option').length == 0)
            {
                mostrarModal(1,null,"Plan de acción","No ha ingresado el análisis de causa.\n",0,"Aceptar","",null); 
                return;
            }
            modalDatos();
            opcModal = 1;
            document.querySelector("#oculta1").style.display = "none";
            document.querySelector("#oculta2").style.display = "none";
            document.querySelector("#oculta3").style.display = "none";

            document.querySelector("#txtaccion").value = "";
            document.querySelector("#txtcedula").value = "";
            document.querySelector("#txtresponsable").value = "";
            document.querySelector("#fecha_limite").value = "";
            @if($inspeccion->estado <> 'E2')
                document.querySelector("#btn_save_plan").innerHTML = "Guardar";
            @endif
            $("#modal_plan_accion").modal("toggle");   
        }

        function savePlanAccion()
        {
            if(opcModal == 1) //Inserta
            {
                if(document.querySelector("#txtaccion").value == "" ||
                    document.querySelector("#txtresponsable").value == "" ||
                    document.querySelector("#fecha_limite").value == "" ||
                    document.querySelector("#txtcedula").value == "")
                {
                    mostrarModal(1,null,"Guardar Plan de acción","Hace falta ingresar información.\n",0,"Aceptar","",null); 
                    return;
                }

                var array = 
                {
                    accion : document.querySelector("#txtaccion").value ,
                    resp : document.querySelector("#txtresponsable").value,
                    fechal : document.querySelector("#fecha_limite").value,
                    inspeccion : document.querySelector("#id_inspeccion").value, 
                    item : document.querySelector("#plan_accion_select").value,
                    des_item : document.querySelector("#plan_accion_select").options[document.querySelector("#plan_accion_select").selectedIndex].text,

                    id_ana : document.querySelector("#analisis_causa_modal").value,
                    des_ana : document.querySelector("#analisis_causa_modal").options[document.querySelector("#analisis_causa_modal").selectedIndex].text,

                    cedula : document.querySelector("#txtcedula").value,
                    tip : "6"
                }
                consultaAjax("../../../saveSupervision",array,15000,"POST",1);   
            }
            else //Update
            {
                var datoAux = 0;
                if(document.querySelector("#txtaccion").value == "" ||
                    document.querySelector("#txtresponsable").value == "" ||
                    document.querySelector("#fecha_cierre").value == "" ||
                    document.querySelector("#txtobsercierre").value == "" ||
                    document.querySelector("#fecha_limite").value == "" ||
                    document.querySelector("#txtcedula").value == "")
                {
                    datoAux = 1;
                    //mostrarModal(1,null,"Guardar Plan de acción","Hace falta ingresar información.\n",0,"Aceptar","",null); 
                    //return;
                }

                if(document.querySelector("#fileevidencia").value == "")
                {
                    datoAux = 1;
                    //mostrarModal(1,null,"Guardar Plan de acción","Hace falta adjuntar el archivo de evidencia de cierre.\n",0,"Aceptar","",null); 
                    //return;   
                }

                var array = 
                {
                    accion : document.querySelector("#txtaccion").value ,
                    resp : document.querySelector("#txtresponsable").value,
                    fechal : document.querySelector("#fecha_limite").value,
                    fecha2 : document.querySelector("#fecha_cierre").value,
                    obser : document.querySelector("#txtobsercierre").value,
                    inspeccion : document.querySelector("#id_inspeccion").value, 
                    id : idSelec,
                    cedula : document.querySelector("#txtcedula").value,
                    evidencia : binaryString,
                    extencion : extencion,
                    id_ana : document.querySelector("#analisis_causa_modal").value,
                    des_ana : document.querySelector("#analisis_causa_modal").options[document.querySelector("#analisis_causa_modal").selectedIndex].text,
                    tip :  "8",
                    aux : datoAux
                }
                consultaAjax("../../../saveSupervision",array,1500000,"POST",1); 

            }
        }
        var idSelec = 0;

        function abrirModalActualiza(id,d1,d2,d3,d4,d5,d6,d7,d8)
        {
           opcModal = 2;
           idSelec = id;
            document.querySelector("#oculta1").style.display = "block";
            document.querySelector("#oculta2").style.display = "block";
            document.querySelector("#oculta3").style.display = "block";  

            document.querySelector("#txtaccion").value = d1;
            document.querySelector("#txtresponsable").value = d2;
            document.querySelector("#fecha_limite").value = d3.split("-")[2] + "/" + d3.split("-")[1] + "/" + d3.split("-")[0];
            if(d4.split("-").length > 1)
                document.querySelector("#fecha_cierre").value = (d4.split("-").length > 0 ? d4.split("-")[2] + "/" + d4.split("-")[1] + "/" + d4.split("-")[0] : '');
            else
                document.querySelector("#fecha_cierre").value = "";    

            document.querySelector("#txtobsercierre").value = d5;
            @if($inspeccion->estado <> 'E2')
                document.querySelector("#btn_save_plan").innerHTML = "Actualiza";
            @endif

            document.querySelector("#plan_accion_select").value = d6;

            modalDatos();

            document.querySelector("#analisis_causa_modal").value = d7;

            document.querySelector("#txtcedula").value = d8;


            $("#modal_plan_accion").modal("toggle"); 

        }

        
        function addcausa()
        {
            if(document.querySelector("#text1").value == "")
            {
                mostrarModal(1,null,"Guardar causa raíz","Ingrese la causa\n",0,"Aceptar","",null);
                return;
            }
            
            /*if(document.querySelector("#adjunto").value == "")
            {
                mostrarModal(1,null,"Guardar causa raíz","Hace falta adjuntar el archivo.\n",0,"Aceptar","",null);
                return;   
            }*/

            var arregloC = [];

            arregloC.push(
                    {
                        "causa" : document.querySelector("#text1").value,
                        "id_item" : document.querySelector("#causa_add_select").value,
                        "des_item" : document.querySelector("#causa_add_select").options[document.querySelector("#causa_add_select").selectedIndex].text
                    });

            document.querySelector("#text1").value = "";

            var array = 
                {
                    analisis : arregloC,
                    inspeccion : document.querySelector("#id_inspeccion").value, 
                    archivo : (document.querySelector("#adjunto").value == "" ? "" : arregloInformacion[0]),
                    mime : (document.querySelector("#adjunto").value == "" ? "" : mime),
                    tip : "7"
                };

            consultaAjax("../../../saveSupervision",array,1500000,"POST",2);  
            
        }

        function deleteCausa(ele)
        {
            var array = 
                {
                    id : ele.parentElement.parentElement.children[0].dataset.id,
                    ins : document.querySelector("#id_inspeccion").value, 
                    tip : "14"
                };

            consultaAjax("../../../saveSupervision",array,15000,"POST",5);  

            
        }

        function addanalisis()
        {
            if(document.querySelector("#text2").value == "")
            {
                mostrarModal(1,null,"Análisis","Ingrese el análisis\n",0,"Aceptar","",null);
                return;   
            }
            $("#tbl_analisis").append("<tr> <td>" + document.querySelector("#text2").value + "</td><td><i class='fa fa-times' onclick='deleteAnalisis(this)' style='    font-size: 20px;  color: red;  display: block;  text-align: center;    cursor: pointer;'></i></td></tr>");
            document.querySelector("#text2").value = "";
        }

        function deleteAnalisis(ele)
        {
            $("#tbl_analisis").find(ele.parentElement.parentElement).remove();
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
                    if(opcion == "1") //Guarda plan de acción
                    {
                        if(data == "1")
                        {
                            mostrarModal(2,null,"Plan de acción","Se ha guardado correctamente el plan de acción\n",0,"Aceptar","",null);
                            setTimeout(function()
                            {
                                location.reload();
                            },1000);
                        }
                        else
                        {
                            document.querySelector("#tbl_index_proyectos").innerHTML = data;
                            mostrarModal(2,null,"Plan de acción","Se ha guardado correctamente el plan de acción\n",0,"Aceptar","",null);
                            $("#modal_plan_accion").modal("toggle");
                        }
                    }

                    if(opcion == "2") //Guarda Causa raíz
                    {
                        var html = "<tr>";

                        var htmlCombo = "";
                        for (var i = 0; i < data.length; i++) {

                            html += "<td data-id='" + data[i].id + "' data-analisis='" + data[i].id_item + "'>" + data[i].des_item + "</td>";
                            html += "<td>" + data[i].analisis + "</td>" ;
                            if(data[i].adjunto == "" || data[i].adjunto == undefined)
                                html += "<td></td>" ;
                            else
                                html += "<td><a target='_blank' style='text-align: center;display: block' href='http://190.60.248.195/anexos_apa/anexos/" + data[i].adjunto  + "'>Ver adjunto</a></td>" ;

                            html += "<td><i class='fa fa-times' onclick='deleteCausa(this)' style='    font-size: 20px;  color: red;  display: block;  text-align: center;    cursor: pointer;'></i></td></tr>" ;

                            htmlCombo += "<option value='" + data[i].id + "' data-analisis='" + data[i].id_item + "'>" + data[i].analisis + "</option>";
                        };

                        html += "</tr>";

                        document.querySelector("#adjunto").value = "";
                        $("#adjunto").filestyle('clear');
                        

                        $("#analisis_causa_modal").html(htmlCombo);
                        $("#tbl_causas").html(html);


                        mostrarModal(2,null,"Análisis de causa","Se ha guardado correctamente análisis de causa\n",0,"Aceptar","",null);
                    }

                    if(opcion == "3") //Guarda plan de acción
                    {
                        mostrarModal(2,null,"IPAL","Se ha guardado correctamente el IPAL\n",0,"Aceptar","",null);
                    }

                    if(opcion == "4") //Csonulta Log
                    {
                        var html = "";


                        html += '<table id="tbl_log" class="table table-striped table-bordered" cellspacing="0" width="99%">';
                        html += '<thead>';
                        html += '    <tr>';
                        html += '        <th style="width:50px;">Fecha</th>';
                        html += '        <th style="width:100px;">Usuario</th>';
                           html += '        <th style="width:50px;">Tipo Log</th>';
                           html += '         <th style="width:100px;">Observación</th>';
                           html += '     </tr>';
                           html += ' </thead>';
                           html += ' <tbody>';
                            
                       


                        for (var i = 0; i < data.length; i++) {

                            html += "<tr>";
                                html += "<td>" + data[i].fecha_servidor.split(".")[0] +  "</td>";
                                html += "<td>" + data[i].propietario +  "</td>";
                                html += "<td>" + data[i].des +  "</td>";
                                html += "<td>" + data[i].descripcion +  "</td>";
                            html += "</tr>";  
                        };

                        html += ' </tbody>';

                        document.querySelector("#tbl_data_log").innerHTML = html;
                        

                        var alto = screen.height - 400;
                        var altopx = alto+"px";
                       
                        $('#tbl_log').dataTable({
                            "scrollX":  "100%",
                            "scrolY":   altopx,
                            "paging":   true,
                            "searching": true,
                            "responsive":      false,
                            "colReorder":      true,
                            "order": [[ 0, 'desc' ]],
                            dom: 'T <"clear">lfrtip',
                            tableTools: {
                                "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                            }
                        }); 


                        $("#modal_ver_log").modal("toggle");

                    }

                    if(opcion == "5")
                    {

                        if(data == "-1")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Análisis de causa","No se puede eliminar el análisis de causa, por que se esta utilizando en un plan de acción\n",0,"Aceptar","",null);   
                            return;
                        }

                        var html = "<tr>";
                        var htmlCombo = "";
                        for (var i = 0; i < data.length; i++) {

                            html += "<td data-id='" + data[i].id + "' data-analisis='" + data[i].id_item + "'>" + data[i].des_item + "</td>";
                            html += "<td>" + data[i].analisis + "</td>" ;
                            html += "<td><a target='_blank' style='text-align: center;display: block' href='http://190.60.248.195/anexos_apa/anexos/" + data[i].adjunto  + "'>Ver adjunto</a></td>" ;
                            html += "<td><i class='fa fa-times' onclick='deleteCausa(this)' style='    font-size: 20px;  color: red;  display: block;  text-align: center;    cursor: pointer;'></i></td></tr>" ;
                            htmlCombo += "<option value='" + data[i].id + "' data-analisis='" + data[i].id_item + "'>" + data[i].analisis + "</option>";
                        };

                        html += "</tr>";

                        $("#tbl_causas").html(html);
                        $("#analisis_causa_modal").html(htmlCombo);


                        mostrarModal(2,null,"Análisis de causa","Se ha eliminado correctamente el análisis de causa\n",0,"Aceptar","",null);   
                    }

                    if(opcion == "6")
                    {
                        if(data == "-1")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Finalizar inspección","No puede finalizar la inspección, por que hacen falta planes de acción por cerrar\n",0,"Aceptar","",null);   
                            return;
                        }
                        
                        if(data == "-2")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Finalizar inspección","No puede finalizar la inspección, por que no ha ingresado ningún analisis de causa\n",0,"Aceptar","",null);   
                            return;
                        }

                        if(data == "-3")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Finalizar inspección","No puede finalizar la inspección, por que no ha ingresado ningún plan de acción\n",0,"Aceptar","",null);   
                            return;
                        }


                        if(data == "1")
                        {
                             ocultarSincronizacion();
                             mostrarModal(2,null,"Finalizar inspección","Se ha finalizado correctamente la inspección\n",0,"Aceptar","",null);   
                             document.querySelector("#boton_1_modal").style.display = "none";
                             setTimeout(function()
                            {
                                location.reload();
                            },1000);
                             return;
                        }
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


        function saveIPALFIN()
        {
            var hijos = document.querySelector("#tbl_ipal").children;
            var arreglo = [];
            var con = 0;
            for (var i = 0; i < hijos.length; i++) {
                if(hijos[i].children[2].innerHTML.trim() == "NO")
                {   
                    if(hijos[i].children[3].children[0].value == "")
                    {
                        mostrarModal(1,null,"Guardar IPAL","Hace falta diligenciar el acto o condición del item:"  + hijos[i].children[0].innerHTML + " - " + hijos[i].children[1].innerHTML +"\n",0,"Aceptar","",null);
                        con = 1;
                        break;
                    }
                    arreglo.push(
                    {
                        "id_pre" : hijos[i].children[3].children[0].dataset.idpregunta,
                        "acto_con" : hijos[i].children[3].children[0].value,
                    });
                }
            };

            if(con == 0 )
            {
                var array = 
                {
                    ins : document.querySelector("#id_inspeccion").value,
                    resp : arreglo,
                    tip : "12"
                }
                consultaAjax("../../saveSupervision",array,150000,"POST",3);
            }
        }


        function saveGestor()
        {
            if(document.querySelector("#txt_nombre_gestor").value == "" ||
                document.querySelector("#txt_iden_gestor").value == "")
            {
                mostrarModal(1,null,"Guardar gestor","Hace falta ingresar información\n",0,"Aceptar","",null);
                return;
            }
            
            var array = 
            {
                nombre : document.querySelector("#txt_nombre_gestor").value,
                gestor : document.querySelector("#txt_iden_gestor").value,
                opc : "2"
            }
            consultaAjax("../../consultaInformacionValidador",array,15000,"POST",2);

            
            
        }


    </script>
@stop

