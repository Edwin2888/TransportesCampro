@extends('template.index')

@section('title')
	ManiObras
@stop

@section('title-section')
    ManiObras
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/carrusel.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/styleCarrusel.css">
@stop

@section('content')
    <style type="text/css">
    .selected_1
    {
        background: #5bc0de;
    }
    </style>
    <main>
    <br><br><br>
    @include('proyectos.redes.trabajoprogramado.secciones.encabezadoOrden')  
	@include('proyectos.redes.trabajoprogramado.modal.modalAddNodos')	
    @include('proyectos.redes.trabajoprogramado.modal.modalLog')
    @include('proyectos.redes.trabajoprogramado.modal.modalRestricciones')
    @if($encabezado != null)
        @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE"  || $encabezado[0]->id_estado == "E1")
            @include('proyectos.redes.trabajoprogramado.modal.modalGeneracionCampros')
            @include('proyectos.redes.trabajoprogramado.modal.modalCambioLider')
        @endif
        @include('proyectos.redes.trabajoprogramado.modal.modaActividadOrdenAdd')
        @include('proyectos.redes.trabajoprogramado.modal.modaMaterialOrdenAdd')
        @include('proyectos.redes.trabajoprogramado.modal.modalCapturaEjecucion')
        @include('proyectos.redes.trabajoprogramado.modal.modalCapturaConciliacion')
        @include('proyectos.redes.trabajoprogramado.modal.modalselecciongom')
        @include('proyectos.redes.trabajoprogramado.modal.modalReprogramacion')
        @include('proyectos.redes.trabajoprogramado.modal.modalAddBaremosExtrasMasivo')
    @endif
        
<?php /*    
/////////////////////////////////////////////////////////////////////////////////////////
////////////////////// inicio codigo para solicitu de miniobras  ////////////////////////
///////////////////////////////////////////////////////////////////////////////////////// 
*/ ?>
        @include('proyectos.redes.trabajoprogramado.modal.modalSolicitudManiobra')
<?php /*    
/////////////////////////////////////////////////////////////////////////////////////////
////////////////////// inicio codigo para solicitu de miniobras  ////////////////////////
///////////////////////////////////////////////////////////////////////////////////////// 
*/ ?>    

    <input type="hidden" id="pro_id" value="{{$proyecto}}" />
    @if($orden != '') 
        
    <div class="panel-body wbs">
            <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3" style="margin-left:0px;margin-top:10px;">

                            @if($encabezado != null)
                                @if($encabezado[0]->id_estado == "E1")
                                <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-add-recurso" > <i class="fa fa-plus"></i>Agregar recursos a ManiObra</button>
                                @endif
                            @else
                                <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-add-recurso" > <i class="fa fa-plus"></i>Agregar recursos a ManiObra</button>
                            @endif

                            

                        </div>
                    </div>
                    
                    <div class="row">
                        <h5 style="text-align:center"><b>Recurso T??cnico Seleccionado</b></h5>


                            @include('proyectos.redes.trabajoprogramado.secciones.tblrecursosutilizados')  
                    </div>
          </div>
    </div>
    <div class="panel-body wbs">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3" style="margin-left:-15px;margin-top:10px;">

                @if($encabezado != null)
                    @if($encabezado[0]->id_estado == "E1")
                    <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-add-nodos"> <i class="fa fa-plus"></i> Agregar nodos a ManiObra</button>
                    @endif
                @else
                    <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-add-nodos"> <i class="fa fa-plus"></i> Agregar nodos a ManiObra</button>
                @endif

                </div>
            </div>
            
            <div class="row">
                <h5 style="text-align:center"><b>Nodos Seleccionados</b></h5>
                    <table id="contenido_nodos_ver" class="table table-striped table-bordered" cellspacing="0" width="99%">
                    <thead>
                        <tr>
                            <th style="width:20px;">WBS</th>
                            <th style="width:100px;">NODO</th>
                            <th style="width:70px;">CD</th>
                            <th style="width:200px;">DIRECCI??N</th>
                            <th style="width:50px;">PUNTO FISICO</th>
                            <th style="width:50px;">SECCIONADOR</th>
                            <th style="display:none;">N</th>
                            <th style="width:50px;">GOM</th>
                            
                        </tr>
                    </thead>
                    <tbody id="nodos_select_recurso">
                    <?php  $nodos = "" ;?>
                        @foreach($noddSel as $nod => $val)
                            <?php  $nodos =  $nodos .  $val->id_nodo . ";" ;?>
                            <tr class="select_nodo">
                                <td>{{$val->nombre_ws}}</td>
                                <td data-nodo="{{$val->id_nodo}}">{{$val->nombre_nodo}}</td>
                                <td>{{$val->cd}}</td>
                                <td>{{$val->direccion}}</td>
                                <td>{{$val->punto_fisico}}</td>
                                <td>{{$val->seccionador}}</td>
                                <td style="display:none;">{{$val->id_nodo}}</td>
                                <td class="gom_tabla">{{$val->gom}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> 

                <input type="hidden" value="{{$nodos}}" id="nodos_select_hidden">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <h4>Convenciones</h4>
            <p style="text-align:justify">
                 <span class="badge">#</span> <b style="font-size:12px;color:#0060AC;">Cantidad de Actividades/Materiales cargadas en el levantamiento</b>
                 <br>
                 <span class="badge" style="background-color:#0060AC !important">#</span> <b style="font-size:12px;color:#0060AC;">Cantidad de Actividades/Materiales programadas para la ejecuci??n</b>
                 <br>
                 <span class="badge" style="background-color:red !important">#</span> <b style="font-size:12px;color:#0060AC;">Cantidad de Actividades/Materiales programadas en otras ManiObras</b>
                 <br>
                 <span class="badge" style="background-color:green !important">#</span> <b style="font-size:12px;color:#0060AC;">Cantidad de Actividades/Materiales ejecutadas</b>
            </p>
        </div>
        @if($encabezado != null)
            <div class="col-md-8" style="display:block">
                <h4>Porcentaje de utlizaci??n cuadrillas - D??a:<b id="fecha_programacion"></b></h4>
                <table>
                    <thead>
                        <tr>
                            <th style="text-align:center;">Tipo C.</th>
                            <th style="    width: 20%;text-align:center;">L??der</th>
                            <th style="    width: 60%;text-align:center;">Programado</th>
                            <th style="    width: 30%;text-align:center;">Meta</th>
                        </tr>
                    </thead>
                    <tbody id="lista_cuadrillas">
                        
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <div class="panel-body wbs">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12" style="margin-left:0px;margin-top:10px;">
                            <h5 style="text-align:center" id="scroll_top_1"><b>Actividades</b></h5>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4" style="margin-bottom:10px;">
                            <div class="input-group" style="width:370px;">
                                <label style="display:block">Filtrar por NODO:</label>
                                <input class="form-control" size="16" style="height:30px;display:inline-block;width:60%;" type="text"
                                                   value="" name="text_filter_acti" id="text_filter_acti" placeholder="Ingrese el nombre del nodo" required>
                                <div onclick="filtarDatosActividades()" class="input-group-addon" style="display:inline-block;height:30px;    width: 35px;"><i class="fa fa-search"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-bottom:10px;">
                            <div class="input-group" style="width:270px;">
                                <label style="display:block">Filtrar por c??digo:</label>
                                <input class="form-control" size="16" style="height:30px;display:inline-block;width:60%;" type="text"
                                                   value="" name="text_filter_cod_1" id="text_filter_cod_1" placeholder="Ingrese el c??digo del baremo" required />
                                <div onclick="filtrarCodigo(1)" class="input-group-addon" style="display:inline-block;height:30px;    width: 35px;"><i class="fa fa-search"></i></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="treeview9" class=""></div>  
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="panel-actividades" style="display:none;margin-top:45px;">
                    <div class="panel panel-primary">
                      <div class="panel-heading" id="nombre-plan"></div>
                      <div class="panel-body">
                                <div id="home_recurso_1" class="tab-pane fade in active" style="display:none">
                                    <div class="row">
                                    @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "E1")
                                         <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-create-actividad" sytle="position: absolute;    top: -10px;"> <i class="fa fa-plus"></i> Agregar Actividad</button>
                                    @endif
                                    </div>
                                    <div class="row" style="margin-top:20px;">
                                      <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                        <label class="control-label col-md-5" for="inputSuccess3">Seleccionar a la l??der de cuadrilla que se va a encargar de todo el nodo.</label>
                                            <div class="col-md-7">
                                                  <div class="input-group">
                                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-pencil" aria-hidden="true"></span>

                                                     <select class="form-control" id="combox_1_recurso">
                                                        <option value="0" selected>No asignado</option>
                                                        @foreach($recurso as $rec => $val)
                                                            <option value="{{$val->lider}}">{{$val->nom1}} - {{$val->tipo}}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                  </div>
                                            </div>
                                        </div>
                                      </div>

                                        <div class="col-md-3 col-md-offset-4">
                                            @if($encabezado[0]->id_estado == "E1")
                                                <button  class="btn btn-default" style="width:200px !important;" id="btn-save-recurso_1"><i class="fa fa-plus" aria-hidden="true" ></i> Guardar</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div id="home_recurso_2" class="tab-pane fade in active" style="display:none">
                                    <h4 id="text_baremo_text"></h4>
                                    <div class="row" style="margin-top:20px;">
                                      <div class="row" style="margin-top:20px;">
                                      
                                      <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                       
                                        <label class="control-label col-md-5" for="inputSuccess3">Seleccionar a la l??der de cuadrilla que se va a encargar de la actividad.</label>
                                            <div class="col-md-7">
                                                  <div class="input-group">
                                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-pencil" aria-hidden="true"></span>

                                                     <select class="form-control" id="combox_2_recurso">
                                                        <option value="0" selected>No asignado</option>
                                                        @foreach($recurso as $rec => $val)
                                                            <option value="{{$val->lider}}">{{$val->nom1}} - {{$val->tipo}}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                  </div>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="col-md-12" style="margin-bottom:20px;">
                                        <div class="form-group has-feedback">
                                        <label class="control-label col-md-5" for="inputSuccess3">Cantidad</label>
                                           <div class="col-md-7">
                                                  <div class="input-group">
                                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                     <input class="form-control" id="text_cant_baremos" data-num="1">
                                                    
                                                  </div>
                                            </div>

                                            
                                        </div>
                                      </div>

                                        <div class="col-md-3 col-md-offset-4">
                                            @if($encabezado[0]->id_estado == "E1")
                                                <button  class="btn btn-default" style="width:200px !important;" id="btn-save-recurso-2"><i 
                                                class="fa fa-plus" aria-hidden="true" ></i> Guardar</button>
                                            @endif
                                        </div>
                                    </div>
                                    </div>
                                </div>

                      </div>
                    </div>
                </div>
          </div>
    </div> 
    <div class="panel-body wbs">
            <div class="col-md-12">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12" style="margin-left:0px;margin-top:10px;">
                            <h5 style="text-align:center"><b id="scroll_top_2">Materiales</b></h5>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4" style="margin-bottom:10px;">
                            <div class="input-group" style="width:270px;">
                                <label style="display:block">Filtrar por NODO:</label>
                                <input class="form-control" size="16" style="height:30px;display:inline-block;width:60%;" type="text"
                                                   value="" name="text_filter_acti" id="text_filter_mate" placeholder="Ingrese el nombre del nodo" required>
                                <div onclick="filtarDatosMateriales()" class="input-group-addon" style="display:inline-block;height:30px;    width: 35px;"><i class="fa fa-search"></i></div>
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-bottom:10px;">
                            <div class="input-group" style="width:270px;">
                                <label style="display:block">Filtrar por clasificaci??n:</label>
                                <select id="clasi_dato" onchange="verClasificacion()" class="form-control"  style="height:30px;display:inline-block;width:60%;" >
                                    <option value="0">Todos</option>
                                    <option value="P">P - {{$clasificacion[0]->nombre}}</option>
                                    <option value="H">H - {{$clasificacion[1]->nombre}}</option>
                                    <option value="E">E - {{$clasificacion[2]->nombre}}</option>
                                </select>                
                            </div>
                        </div>
                        <div class="col-md-4" style="margin-bottom:10px;">
                            <div class="input-group" style="width:270px;">
                                <label style="display:block">Filtrar por c??digo:</label>
                                <input class="form-control" size="16" style="height:30px;display:inline-block;width:60%;" type="text"
                                                   value="" name="text_filter_cod_2" id="text_filter_cod_2" placeholder="Ingrese el c??digo del material" required />
                                <div onclick="filtrarCodigo(2)" class="input-group-addon" style="display:inline-block;height:30px;    width: 35px;"><i class="fa fa-search"></i></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="treeview9" class=""></div>  
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div id="treeview1" class=""></div>  
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="panel-materiales" style="display:none;margin-top:45px;">
                    <div class="panel panel-primary">
                      <div class="panel-heading" id="nombre-plan-1"></div>
                      <div class="panel-body">
                                <div id="home_material_1" class="tab-pane fade in active" style="display:none">
                                    
                                    @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "E1")
                                    <div class="row">
                                        <button class="btn btn-primary btn-cam-trans btn-sm" id="btn-create-material" sytle="position: absolute;    top: -10px;"> <i class="fa fa-plus"></i> Agregar Material</button>
                                    </div>
                                    @endif
                                    <div class="row" style="margin-top:20px;">
                                      <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                         

                                        <label class="control-label col-md-5" for="inputSuccess3">Seleccionar a la l??der de cuadrilla que se va a encargar de todo los materiales del nodo.</label>
                                            <div class="col-md-7"> 
                                                  <div class="input-group">
                                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-pencil" aria-hidden="true"></span>

                                                     <select class="form-control" id="combox_1_materiales">
                                                        <option value="0" selected>No asignado</option>
                                                        @foreach ($comboxP as $comb => $val)
                                                            <option value="{{$val->id_lider}}">{{$val->nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                  </div>
                                            </div>
                                        </div>
                                      </div>

                                        <div class="col-md-3 col-md-offset-4">
                                                @if($encabezado[0]->id_estado == "E1")
                                                <button  class="btn btn-default" style="width:200px !important;" id="btn-save-materiales_1"><i class="fa fa-plus" aria-hidden="true" ></i> Guardar</button>
                                                @endif
                                        </div>
                                    </div>
                                </div>

                                <div id="home_material_2" class="tab-pane fade in active" style="display:none">
                                    <h4 id="text_material"></h4>
                                    <div class="row" style="margin-top:20px;">
                                      <div class="row" style="margin-top:20px;">
                                      
                                      <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                        <label class="control-label col-md-5" for="inputSuccess3">Seleccionar a la l??der de cuadrilla que se va a encargar del material.</label>
                                            <div class="col-md-7">
                                                  <div class="input-group">
                                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-pencil" aria-hidden="true"></span>

                                                     <select class="form-control" id="combox_2_materiales">
                                                        <option value="0" selected>No asignado</option>
                                                        @foreach ($comboxP as $comb => $val)
                                                            <option value="{{$val->id_lider}}">{{$val->nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                  </div>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="col-md-12" style="margin-bottom:20px;">
                                        <div class="form-group has-feedback">
                                        <label class="control-label col-md-5" for="inputSuccess3">Cantidad</label>
                                           <div class="col-md-7">
                                                  <div class="input-group">
                                                    <span class=" input-group-addon glyphicon glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                     <input class="form-control" id="text_cant_materiales" data-num="1">
                                                    
                                                  </div>
                                            </div>

                                            
                                        </div>
                                      </div>

                                        <div class="col-md-3 col-md-offset-4">
                                                @if($encabezado[0]->id_estado == "E1")
                                                <button  class="btn btn-default" style="width:200px !important;" id="btn-save-materiales-2"><i class="fa fa-plus" aria-hidden="true" ></i> Guardar</button>
                                                @endif
                                        </div>
                                    </div>
                                    </div>
                                </div>

                      </div>
                    </div>
                </div>
          </div>
    </div> 

    <script type="text/javascript">
        window.addEventListener('load',iniOrdenesTrabajoProgramadoAdd);

        var tbl3 ;

        var nodoSele = null;
        var persoaCargo = null;
        var nodoPadre = null;
        var opcionNodo = null;
        var opcionNodo1 = null;
        var nodosSelecArray = [];
        var nodosSelecArray1 = [];

        var opcGuardar = null;

        var tipoIngresoDCNodo = 0;
        var elementoSeleccionadoDC = null;


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

              /*  if(fechaIni > fecahSelect){
                    mostrarModal(1,null,"Cargar ejecuci??n","No puede seleccionar una fecha menor a la de inicio de la ejecuci??n\n",0,"Aceptar","",null);
                    return;
                }

                if(fecahSelect > fechaFin){
                    mostrarModal(1,null,"Cargar ejecuci??n","No puede seleccionar una fecha mayor a la de finalizaci??n de la ejecuci??n\n",0,"Aceptar","",null);
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
                                ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                                nodo: document.querySelector("#select_nodos_afectados").value,
                                fecha_consulta : f,
                                dc : -1
                            }
                            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",26);   
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
                                ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                                nodo: document.querySelector("#select_nodos_afectados").value,
                                fecha_consulta : f,
                                dc : elementoSeleccionadoDC.innerHTML
                            }

                            dcSelec = elementoSeleccionadoDC;
                            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",26);   
                        }
                    }
                }
            },200,ele);
        }


        function save_actividades()
        {
                if(document.querySelector("#select_lider_carga").selectedIndex == 0)
                {
                    mostrarModal(1,null,"Guardar ejecuci??n","Seleccione un l??der para guardar la ejecuci??n\n",0,"Aceptar","",null);
                    return;
                }

                if(document.querySelector("#select_nodos_afectados").selectedIndex == 0)
                {
                    mostrarModal(1,null,"Guardar ejecuci??n","Seleccione un nodo para guardar la ejecuci??n\n",0,"Aceptar","",null);
                    return;
                }

                 if((document.querySelector("#preplanilla_id").value == "" &&
                    document.querySelector("#preplanilla_id_d").value == "")
                    || (document.querySelector("#preplanilla_id").value != "" &&
                    document.querySelector("#preplanilla_id_d").value != "")
                )
                {
                    mostrarModal(1,null,"Guardar ejecuci??n","Ingrese por favor Solo un n??mero de  preplanilla\n",0,"Aceptar","",null);
                    return;
                }

                var fechaIni = document.querySelector("#datos_aux_1").children[1].children[5].innerHTML.split("-");
                var fechaFin = document.querySelector("#datos_aux_1").children[1].children[6].innerHTML.split("-");

                 var f = document.querySelector("#fech_ejecucionInput").value.split("/");

                var fecahSelect = new Date(f[2],f[1]  - 1,f[0]);

                f = f[2] + "-" + f[1] + "-" + f[0];

                fechaIni = new Date(fechaIni[0],fechaIni[1] - 1,fechaIni[2]);
                fechaFin = new Date(fechaFin[0],fechaFin[1] - 1,fechaFin[2]);

                ocultarSincronizacion();

              /*  if(fechaIni > fecahSelect){
                    mostrarModal(1,null,"Cargar ejecuci??n","No puede seleccionar una fecha menor a la de inicio de la ejecuci??n\n",0,"Aceptar","",null);
                    return;
                }

                if(fecahSelect > fechaFin){
                    mostrarModal(1,null,"Cargar ejecuci??n","No puede seleccionar una fecha mayor a la de finalizaci??n de la ejecuci??n\n",0,"Aceptar","",null);
                    return;
                } */


                //Validar Baremos
                var tbl = document.querySelector("#tbl_baremos_eje").children;
                var arrayEnviar = [];

                  for (var i = 0; i < tbl.length; i++) {

                    if(tbl[i].children[2].children[0].children[0].value == "")
                    {
                        mostrarModal(1,null,"Guardar ejecuci??n","No puede guardar los baremos, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                        return;
                    }

                    if(tbl[i].children[2].children[0].children[0].dataset.validado != "1")
                    {
                        mostrarModal(1,null,"Guardar ejecuci??n","No ha terminado de validar todos las actividades.\n",0,"Aceptar","",null);
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
                    "orden" : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                    "barem" : arrayEnviar
                    
                  });

                  var arrayEnviar1 = [];               
                  var dat1 = [];

                  dat1.push({
                    "nodo" : document.querySelector("#select_nodos_afectados").value,
                    "usuario" : document.querySelector("#select_lider_carga").value,
                    "orden" : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                    "mate" : arrayEnviar1
                  });

                  if(!document.querySelector("#preplanilla_id").value && !document.querySelector("#preplanilla_id_d").value) {
                    alert('Por favor ingrese un numero de preplanilla');
                    return false;
                  }

                  if(confirm("??Seguro que desea guardar las actividades del nodo"))
                  {
                     var datos = 
                        {
                            opc: 8,
                            lid: document.querySelector("#select_lider_carga").value,
                            ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                            nodo: document.querySelector("#select_nodos_afectados").value,
                            bare : dat,
                            mate : dat1,
                            dc : dcSelec,
                            fecha_consulta : f,
                            pla : document.querySelector("#preplanilla_id").value,
                            plad : document.querySelector("#preplanilla_id_d").value
                        }

                        consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",27); 

                  }
        }


        function consultarBaremoNuevoModelo()
        {
            if(document.querySelector("#txt_cod_bare").value == "" &&
                document.querySelector("#txt_name_baremo").value == "")
            {
                mostrarModal(1,null,"Filtro Baremos","Ingrese informaci??n para el filtro\n",0,"Aceptar","",null);
                return;
            }

            var array = 
            {
                cod : document.querySelector("#txt_cod_bare").value,
                des : document.querySelector("#txt_name_baremo").value,
                opc : "0"
            }
            //Nuevo Modelo Michael
            consultaAjax("{{url('/')}}/consultaBaremos",array,35000,"POST",45);
        }

        function cerrarModalNuevo()
        {
          $('#hora_cierre').prop('readonly', false);
          $('#operador_ccontrol_cierra').prop('readonly', false);
          $('#hora_apertura').prop('readonly', false);
          $('#operador_ccontrol_abre').prop('readonly', false);
          
            $("#modal_acti_add_nuevo_modelo").modal("toggle");
            setTimeout(function()
            {
                $("#modal_captura_ejecucion").modal("toggle");
                
          $('#hora_cierre').prop('readonly', false);
          $('#operador_ccontrol_cierra').prop('readonly', false);
          $('#hora_apertura').prop('readonly', false);
          $('#operador_ccontrol_abre').prop('readonly', false);
            },700);
            
            
          $('#hora_cierre').prop('readonly', false);
          $('#operador_ccontrol_cierra').prop('readonly', false);
          $('#hora_apertura').prop('readonly', false);
          $('#operador_ccontrol_abre').prop('readonly', false);
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


         function  abrirCSAsociado(evt)
        {
            if(!confirm("??Seguro que desea crear un documento de consumo?"))
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
                if(confirm("Desea guardar los baremos seleccionados para la fecha de ejecuci??n " + f))
                {
                    datos = 
                    {
                        opc: 20,
                        nodo: document.querySelector("#select_nodos_afectados").value,
                        cargo : document.querySelector("#select_lider_carga").value,
                        bare: baremos,

                        ord :document.querySelector("#id_orden").value,
                        pry :document.querySelector("#id_proyect").value,


                        orden: document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
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

                        ord :document.querySelector("#id_orden").value,
                        pry :document.querySelector("#id_proyect").value,
                        
                        orden: document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                        fecha_consulta : f
                    };
            }
            
            

            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,120000,"POST",46);   


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
                    orden :document.querySelector("#id_orden").value,
                proyecto :document.querySelector("#id_proyect").value,
                lider : lider,
                tipo : document.querySelector("#tipo_proyecto_id").dataset.tipo,
                fecha : f
            }; 

            consultaAjax("{{url('/')}}/consultaActiMate",datos,20000,"POST",23);

        }

        function generarVersion()
        {
            alert(this.checked);
        }

        function iniOrdenesTrabajoProgramadoAdd()
        {

            

            @if(Session::has('gop_lider_seleccionado'))

                
                document.querySelector("#select_lider_carga").value = {{Session::get('gop_lider_seleccionado')}}

                var datos = 
                {
                    opc: 5,
                    lid: document.querySelector("#select_lider_carga").value,
                    ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0","")
                }
                consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",25,null,25);  

            @endif
            @if($encabezado[0]->id_estado != "E1")
                        var xI = $("input");
                        for (var i = 0; i < xI.length; i++) {
                            xI[i].readOnly = true;
                        };

                        xI = $("textarea");
                        for (var i = 0; i < xI.length; i++) {
                            xI[i].readOnly = true;
                        };

                        xI = $("select");
                        for (var i = 0; i < xI.length; i++) {
                            xI[i].disabled = true;
                        };

            @endif


            @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE")
                
                
                document.querySelector("#fech_ejecucionInput").value = '{{$fecha}}'
                
                document.querySelector("#txt_name_baremo").readOnly = false; 
                document.querySelector("#txt_cod_bare").readOnly = false; 

                document.querySelector("#bareSelect").disabled = false; 
                

                document.querySelector("#fech_ejecucionInput").readOnly = false;
                document.querySelector("#fech_ejecucionInput").disabled = false;

            document.querySelector("#txt_gps_descargo").readOnly = false;
                document.querySelector("#text_gom_adec").disabled = false;
                document.querySelector("#text_gom_inst").disabled = false;
                document.querySelector("#text_fac_orden").readOnly = false;
                document.querySelector("#text_rad_oren").readOnly = false;
                document.querySelector("#text_proy").readOnly = false;
                document.querySelector("#select_estado").disabled = false;
                document.querySelector("#evidencia_cierre").readOnly = false;
                document.querySelector("#text_descp_restriccion").readOnly = false;

                document.querySelector("#fecha_inicio").readOnly = false;
                document.querySelector("#fecha_corte").readOnly = false;
                document.querySelector("#select_estado_filter").disabled = false;

                document.querySelector("#select_tipo_cuadrilla_cambio").disabled = false;
                document.querySelector("#text_iden_recurso_cambio").disabled = false;


                document.querySelector("#descargo_add_1").disabled = false;
                document.querySelector("#descargo_add_2").disabled = false;
                document.querySelector("#descargo_add_3").disabled = false;
                document.querySelector("#descargo_add_4").disabled = false;
                document.querySelector("#descargo_add_5").disabled = false;
                document.querySelector("#descargo_add_6").disabled = false;
                document.querySelector("#descargo_add_7").disabled = false;

                

            @endif

            @if($encabezado[0]->id_estado == "E1" || $encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "E4" || $encabezado[0]->id_estado == "C2")
                document.querySelector("#clasi_dato").disabled = false;
                document.querySelector("#text_filter_cod_1").readOnly = false;
                document.querySelector("#text_filter_cod_2").readOnly = false;
                @if( $encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE" )
                    document.querySelector("#filter_dc").readOnly = false;
                @endif

            @endif

            document.querySelector("#select_persona_cargo_mate").disabled = false;
            document.querySelector("#select_persona_cargo_bare").disabled = false;

            document.querySelector("#text_filter_acti").readOnly = false;
            document.querySelector("#text_filter_mate").readOnly = false;

            

            @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "E1")
            //Cancelar Orden
            document.querySelector("#btn_cancelar_orden").addEventListener("click",function()
            {
                if(confirm("??Seguro que desesa cancelar la orden?"))
                {
                    var datos = 
                    {
                        opc : 11,
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value
                    }; 
                    consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",23);
                }
            });
            @endif

            @if($encabezado != null )
                    consultaPorcentaProduccion();
                @endif

            @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "E4")
                document.querySelector("#btn_captura_eje").addEventListener("click",function()
                {
                    @if($encabezado[0]->id_estado != "E4" && $encabezado[0]->id_estado != "C2")
                        document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                    @endif
                    document.querySelector("#tbl_persona_cargo").innerHTML = "";
                    document.querySelector("#select_nodos_afectados").innerHTML = "";
                    document.querySelector("#nodos-add").innerHTML = "";
                    document.querySelector("#datos_captura_ejecucion").innerHTML = "";
                    document.querySelector("#select_lider_carga").selectedIndex = 0;
                    
                    $('#hora_cierre').prop('readonly', false);
                    $('#operador_ccontrol_cierra').prop('readonly', false);
                    $('#hora_apertura').prop('readonly', false);
                    $('#operador_ccontrol_abre').prop('readonly', false);
                    $("#modal_captura_ejecucion").modal("toggle");
                     setTimeout(function()
                                    {
                        $('#hora_cierre').prop('readonly', false);
                        $('#operador_ccontrol_cierra').prop('readonly', false);
                        $('#hora_apertura').prop('readonly', false);
                        $('#operador_ccontrol_abre').prop('readonly', false);
                    
                    },700);    
                        
                 
                });

                @if($encabezado[0]->id_estado == "E4" || $encabezado[0]->id_estado == "C2")
                document.querySelector("#btn_captura_conci").addEventListener("click",function()
                {
                    $("#modal_captura_conciliacion").modal("toggle");
                });
                @endif
                
                

                $('#baremo_add_eje').DataTable();
                $('#mate_add_eje').DataTable();
                
                document.querySelector("#select_lider_carga").disabled =false;
                document.querySelector("#select_nodos_afectados").disabled =false;

                @if($encabezado[0]->id_estado == "E4" || $encabezado[0]->id_estado == "C2")
                    document.querySelector("#select_lider_carga1").disabled =false;
                    document.querySelector("#select_nodos_afectados1").disabled =false;
                    $('#baremo_add_eje1').DataTable();
                    $('#mate_add_eje1').DataTable();

                    //Conciliaci??n
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
                                ot : document.querySelector("#id_orden").value
                            }
                            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",33,null,0);  
                        }else{
                            $("#tbl_persona_a_cargo1").find("tr").remove();
                            $("#select_nodos_afectados1").find("option").remove();
                            @if($encabezado != null)
                            @if($encabezado[0]->id_estado == "E4")
                                document.querySelector("#btn_save_guardar_actividad1").style.display = "none";
                            @endif
                            @endif
                        }
                        
                    });

                    document.querySelector("#select_nodos_afectados1").addEventListener("change",function()
                    {
                        $("#nodos-add1").find("li").remove();
                        @if($encabezado != null)
                        @if($encabezado[0]->id_estado == "E4")
                            document.querySelector("#btn_save_guardar_actividad1").style.display = "none";
                        @endif
                        @endif
                        document.querySelector("#datos_captura_ejecucion1").style.display = "none";
                        if(this.selectedIndex != 0)
                        {
                            var datos = 
                            {
                                opc: 9,
                                lid: document.querySelector("#select_lider_carga1").value,
                                ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                                nod: document.querySelector("#select_nodos_afectados1").value
                            }
                            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",32); 
                        }
                        
                    });
                @endif

                //Ejecuci??n
                document.querySelector("#select_lider_carga").addEventListener("change",function()
                {
                    
                    if($("#select_lider_carga").val()==0){
                        $("#contentprinnodo").hide();
                    }
                    
                    tipoIngresoDCNodo = 0;
                    $("#nodos-add").find("li").remove();
                    document.querySelector("#datos_captura_ejecucion").style.display = "none";
                    if(this.selectedIndex != 0)
                    {
                        var datos = 
                        {
                            opc: 5,
                            pro: '<?= $proyecto ?>',
                            lid: document.querySelector("#select_lider_carga").value,
                            ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0","")
                        }
                        consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",25,null,25);  
                    }else{
                        $("#persona_a_cargo").find("tr").remove();
                        $("#select_nodos_afectados").find("option").remove();
                        @if($encabezado != null)
                        @if($encabezado[0]->id_estado != "E4" && $encabezado[0]->id_estado != "C2")
                            document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                        @endif
                        @endif
                    }
                });
                
                @if($encabezado != null)
                    document.querySelector("#fecha_programacion").innerHTML = document.querySelector("#fech_ejecucion").value;
                    
                    
                
                $('#select_nodos_afectados').change(function(e){
                    var nodo = $(this).val();
                    
                    $.each($("#datos_aux_2 .fa-file-pdf-o")  ,function(index,value){
                        var link = $(this).parent();
                        var enlace = "{{config('app.Campro')[2]}}/campro/gop/rds/pdf_planilla_materiales_programados.php?id_orden="+$("#bitacora__id_orden").val()+"&id_lider="+link.data("cedula")+"&tipo=1&nodo="+nodo;
                        link.attr("href",enlace);
                        
                        
                    });
                      
                    
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
                            ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                            nod: document.querySelector("#select_nodos_afectados").value
                        }
                        consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",28); 
                    }
                });
                @endif

                @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE")
                document.querySelector("#btn_save_guardar_actividad").addEventListener("click",function()
                {
                    if(document.querySelector("#select_lider_carga").selectedIndex == 0)
                    {
                        mostrarModal(1,null,"Guardar ejecuci??n","Seleccione un l??der para guardar la ejecuci??n\n",0,"Aceptar","",null);
                        return;
                    }

                    if(document.querySelector("#select_nodos_afectados").selectedIndex == 0)
                    {
                        mostrarModal(1,null,"Guardar ejecuci??n","Seleccione un nodo para guardar la ejecuci??n\n",0,"Aceptar","",null);
                        return;
                    }

                    if(document.querySelector("#preplanilla_id").value == "")
                    {
                        mostrarModal(1,null,"Guardar ejecuci??n","No ha ingresado el consecutivo  de planilla\n",0,"Aceptar","",null);
                        return;
                    }

                    var fechaIni = document.querySelector("#datos_aux_1").children[1].children[5].innerHTML.split("-");
                    var fechaFin = document.querySelector("#datos_aux_1").children[1].children[6].innerHTML.split("-");

                     var f = document.querySelector("#fech_ejecucionInput").value.split("/");

                    var fecahSelect = new Date(f[2],f[1]  - 1,f[0]);

                    f = f[2] + "-" + f[1] + "-" + f[0];

                    fechaIni = new Date(fechaIni[0],fechaIni[1] - 1,fechaIni[2]);
                    fechaFin = new Date(fechaFin[0],fechaFin[1] - 1,fechaFin[2]);

                    ocultarSincronizacion();

                  /*  if(fechaIni > fecahSelect){
                        mostrarModal(1,null,"Cargar ejecuci??n","No puede seleccionar una fecha menor a la de inicio de la ejecuci??n\n",0,"Aceptar","",null);
                        return;
                    }

                    if(fecahSelect > fechaFin){
                        mostrarModal(1,null,"Cargar ejecuci??n","No puede seleccionar una fecha mayor a la de finalizaci??n de la ejecuci??n\n",0,"Aceptar","",null);
                        return;
                    }*/


                    //Validar Baremos
                    var arrayEnviar = [];

                     
                      var dat = [];

                      dat.push({
                        "nodo" : document.querySelector("#select_nodos_afectados").value,
                        "usuario" : document.querySelector("#select_lider_carga").value,
                        "orden" : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                        "barem" : arrayEnviar
                      });

                      var tbl1 = document.querySelector("#tbl_mate_eje").children;
                      var arrayEnviar1 = [];

                      for (var i = 0; i < tbl1.length; i++) {
                        if(tbl1[i].children[2].children[0].children[0].value == "")
                        {
                            mostrarModal(1,null,"Guardar ejecuci??n","No puede guardar los materiales, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                            return;
                        }

                        if(tbl1[i].children[2].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[3].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[4].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[5].children[0].children[0].dataset.validado != "1")
                        {
                            mostrarModal(1,null,"Guardar ejecuci??n","No ha terminado de validar todos los materiales.\n",0,"Aceptar","",null);
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
                        "orden" : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                        "mate" : arrayEnviar1
                      });



                      if(confirm("??Seguro que desea guardar la ejecuci??n del nodo"))
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
                                ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                                nodo: document.querySelector("#select_nodos_afectados").value,
                                bare : dat,
                                mate : dat1,
                                dc : dcSelec,
                                fecha_consulta : f,
                                pla : document.querySelector("#preplanilla_id").value
                            }

                            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",27); 
   
                      }
                });

                document.querySelector("#btn_finalizar_orden").addEventListener("click",function()
                {
                    if(confirm("??Seguro que desea finalizar la ejecuci??n de la maniobra"))
                    {
                        var datos = 
                        {
                            opc: 10,
                            ot : document.querySelector("#id_orden").value
                        }

                        consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",29); 
                    }
                });

                @endif

                @if($encabezado[0]->id_estado == "E4" )
                document.querySelector("#btn_save_guardar_actividad1").addEventListener("click",function()
                {
                    if(document.querySelector("#select_lider_carga1").selectedIndex == 0)
                    {
                        mostrarModal(1,null,"Guardar ejecuci??n","Seleccione un l??der para guardar la conciliaci??n\n",0,"Aceptar","",null);
                        return;
                    }

                    if(document.querySelector("#select_nodos_afectados1").selectedIndex == 0)
                    {
                        mostrarModal(1,null,"Guardar ejecuci??n","Seleccione un nodo para guardar la conciliaci??n\n",0,"Aceptar","",null);
                        return;
                    }

                    //Validar Baremos
                    var tbl = document.querySelector("#tbl_baremos_eje1").children;
                    var arrayEnviar = [];

                      for (var i = 0; i < tbl.length; i++) {

                        if(tbl[i].children[2].children[0].children[0].value == "")
                        {
                          mostrarModal(1,null,"Guardar conciliaci??n","No puede guardar los baremos, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                        return;
                        }

                        if(tbl[i].children[2].children[0].children[0].dataset.validado != "1")
                        {
                            mostrarModal(1,null,"Guardar conciliaci??n","No ha terminado de validar todos las actividades.\n",0,"Aceptar","",null);
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
                        "orden" : document.querySelector("#id_orden").value,
                        "barem" : arrayEnviar
                      });


                      var tbl1 = document.querySelector("#tbl_mate_eje1").children;
                      var arrayEnviar1 = [];

                      for (var i = 0; i < tbl1.length; i++) {
                        if(tbl1[i].children[2].children[0].children[0].value == "")
                        {
                            mostrarModal(1,null,"Guardar conciliaci??n","No puede guardar los materiales, ya que hay cantidades vacias.\n",0,"Aceptar","",null);
                          return;
                        }


                        if(tbl1[i].children[2].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[3].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[4].children[0].children[0].dataset.validado != "1" ||
                            tbl1[i].children[5].children[0].children[0].dataset.validado != "1")
                        {
                            mostrarModal(1,null,"Guardar conciliaci??n","No ha terminado de validar todos los materiales.\n",0,"Aceptar","",null);
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
                        "orden" : document.querySelector("#id_orden").value,
                        "mate" : arrayEnviar1
                      });

                      if(confirm("??Seguro que desea guardar la conciliaci??n del nodo"))
                      {
                          var datos = 
                            {
                                opc: 14,
                                lid: document.querySelector("#select_lider_carga1").value,
                                ot : document.querySelector("#id_orden").value,
                                nodo: document.querySelector("#select_nodos_afectados1").value,
                                bare : dat,
                                mate : dat1,
                                dc : dcSelec
                            }

                            consultaAjax("{{url('/')}}/consultaActiMate",datos,220000,"POST",34); 
                    }

                });

                document.querySelector("#btn_finalizar_conciliacion").addEventListener("click",function()
                {
                    if(confirm("??Seguro que desea finalizar la conciliaci??n de la maniobra"))
                    {
                        var datos = 
                        {
                            opc: 15,
                            ot : document.querySelector("#id_orden").value
                        }

                        consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",29); 
                    }
                });

                @endif
            @endif

            @if($encabezado[0]->id_estado == "E1")
                document.querySelector("#btn_captura_finaliza").addEventListener("click",function()
                {
                    
                    var d1 = document.querySelector("#descargo_add_1").value.trim();
                    var d2 = document.querySelector("#descargo_add_2").value.trim();
                    var d3 = document.querySelector("#descargo_add_3").value.trim();
                    var d4 = document.querySelector("#descargo_add_4").value.trim();
                    var d5 = document.querySelector("#descargo_add_5").value.trim();
                    var d6 = document.querySelector("#descargo_add_6").value.trim();
                    var d7 = document.querySelector("#select_tipo_trabajo").value.trim();
                    
                    
                    if(
                        ( d1 == null || d1 == 0 || d1 == '0' || d1 == '' ) &&                             
                        ( d2 == null || d2 == 0 || d2 == '0' || d2 == '' ) &&                             
                        ( d3 == null || d3 == 0 || d3 == '0' || d3 == '' ) &&                             
                        ( d4 == null || d4 == 0 || d4 == '0' || d4 == '' ) &&                             
                        ( d5 == null || d5 == 0 || d5 == '0' || d5 == '' ) &&                             
                        ( d6 == null || d6 == 0 || d6 == '0' || d6 == '' ) &&
                          d7 != 7
                    ){
                  
                       mensajes('Error','Debe agregar al menos un descargo',0);
                       return;
                  
                    }
                    
                    
                    
                    
                    
                    var tbl = tbl3.rows('.selected').data();
                    var arrayNodos = [];
                    for (var i = 0; i < tbl.length; i++) {
                        var wbs = tbl[i][0].split("data-ele=")[1].split(";")[0].trim();

                        var exis = 0;
                        for (var j = 0; j < arrayNodos.length; j++) {
                            if(arrayNodos[j] == wbs)
                                exis = 1;
                        };

                        if(exis == 0)
                            arrayNodos.push(wbs);
                    }

                    if(arrayNodos.length == 0)
                    {
                        mostrarModal(1,null,"ManiObra","No puede finalizar la programaci??n de la maniobra, por que no ha seleccionado ning??n nodo.\n",0,"Aceptar","",null);
                        return;
                    }
                    var datos = 
                    {
                        opc: "11",
                        wbs: arrayNodos,
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value
                    }
                    consultaAjax("{{url('/')}}/consultaActiMate",datos,20000,"POST",30,null);   
                });

            @endif


            document.querySelector("#btn-wbs-actividad").addEventListener("click",saveNuevaActividad); 
            document.querySelector("#btn-wbs-material-a").addEventListener("click",saveNuevoMaterial); 

            document.querySelector("#text_cant").addEventListener("keydown",validaIngreso); 
            document.querySelector("#text_cant_mate_add").addEventListener("keydown",validaIngreso); 

            document.querySelector("#text_cant").addEventListener("blur",modificaValorTotal);
            document.querySelector("#text_cant_mate_add").addEventListener("blur",modificaValorTotalMate);

            @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "E1")
            document.querySelector("#btn-create-actividad").addEventListener("click",function()
                {
                    document.querySelector("#select_nodos").value = "NODO " + this.dataset.nombre;
                    document.querySelector("#select_nodos").dataset.nodo = this.dataset.nodo;
                    document.querySelector("#text_baremo").value = "";
                    document.querySelector("#text_baremo").dataset.bare = "";
                    document.querySelector("#text_baremo").dataset.acti = "";
                    document.querySelector("#text_baremo").dataset.precio = "";
                    document.querySelector("#text_cant").value = "1";
                    document.querySelector("#text_valor").value = "";
                    document.querySelector("#text_total").value = "";

                    document.querySelector("#text_cant").readOnly = false;
                    

                    $("#modal_acti").modal("toggle");
                });

            document.querySelector("#btn-create-material").addEventListener("click",function()
                {
                    document.querySelector("#select_nodos_material").value = "NODO " +  this.dataset.nombre;
                    document.querySelector("#select_nodos_material").dataset.nodo = this.dataset.nodo;
                    document.querySelector("#text_mate_add").value = "";
                    document.querySelector("#text_mate_add").dataset.mate = "";
                    document.querySelector("#text_mate_add").dataset.nombre = "";
                    document.querySelector("#text_mate_add").dataset.precio = "";
                    document.querySelector("#text_cant_mate_add").value = "1";
                    document.querySelector("#text_valor_mate_add").value = "";
                    document.querySelector("#text_total_mate_add").value = "";

                    document.querySelector("#text_cant_mate_add").readOnly = false;
                    $("#modal_mat_add").modal("toggle");
                });
            
            @endif
            $(window).scroll(function(){
                var windowHeight = $(window).scrollTop();
                var contenido2 = $("#scroll_top_1").offset();
                contenido2 = contenido2.top;
                
                var scrollTop = $('html').scrollTop() || $('body').scrollTop();

                var contenido3 = $("#scroll_top_2").offset();
                contenido3 = contenido3.top;

                var content3 = contenido2 + parseFloat($("#treeview9").css("height").replace("px","")) 
                - parseFloat($("#panel-actividades").css("height").replace("px","")) + 35;

                var altoPanel = document.querySelector("#panel-actividades").style.height;

                //Scroll Primero Panel
                if((scrollTop > contenido2 - 50) && (scrollTop < content3)) //Div Quieto
                {
                    $("#panel-actividades").css("position","fixed");
                    $("#panel-actividades").css("left","50%");
                    $("#panel-actividades").css("top","5%");
                    $("#panel-actividades").css("margin-top","45px"); 
                }
                else
                {
                    if(scrollTop > content3)
                    {
                        var cont = parseFloat($("#treeview9").css("height").replace("px","")) 
                - parseFloat($("#panel-actividades").css("height").replace("px","")) + 120;

                        $("#panel-actividades").css("position","relative");
                        $("#panel-actividades").css("left","0%");
                        $("#panel-actividades").css("top","0%");  
                        $("#panel-actividades").css("margin-top",cont);  

                        console.log("MARGIN TOP" + cont);
                    }
                    else
                    {                        
                        $("#panel-actividades").css("position","relative");
                        $("#panel-actividades").css("left","0%");
                        $("#panel-actividades").css("top","0%"); 
                        $("#panel-actividades").css("margin-top","45px");   
                    }
                }

                //Scroll segundo panel
                if((scrollTop > contenido3 - 50)) //Div Quieto
                {
                    $("#panel-materiales").css("position","fixed");
                    $("#panel-materiales").css("left","50%");
                    $("#panel-materiales").css("top","5%");
                    $("#panel-materiales").css("margin-top","45px"); 
                }
                else
                {                     
                    $("#panel-materiales").css("position","relative");
                    $("#panel-materiales").css("left","0%");
                    $("#panel-materiales").css("top","0%"); 
                    $("#panel-materiales").css("margin-top","45px");   
                    
                }

            });
            
            @if($encabezado[0]->id_estado == "E1")
            document.querySelector("#boton_1_modal_1").addEventListener("click",agregarEventosTodo);
            document.querySelector("#boton_2_modal_1").addEventListener("click",agregarSoloVacios);
            document.querySelector("#boton_2_modal_2").addEventListener("click",cerrarTodos);

            
            document.querySelector("#btn-add-nodos").addEventListener("click",abrirModalNodos);
            document.querySelector("#btn-add-recurso").addEventListener("click",abrirModalRecurso);
            

            document.querySelector("#btn-add-nodos-orden").addEventListener("click",addNodosSelect);           
            document.querySelector("#btn-save-recurso_1").addEventListener("click",saveNodos);
            document.querySelector("#btn-save-recurso-2").addEventListener("click",saveNodosBaremos);
            document.querySelector("#btn-save-materiales_1").addEventListener("click",saveNodosMat);
            document.querySelector("#btn-save-materiales-2").addEventListener("click",saveNodosMateriales);

            @endif
            
            
            var nod = document.querySelector("#nodos_select_hidden").value.split(";");

            var arrayNodos = [];
            for (var i = 0; i < nod.length - 1; i++) {
                arrayNodos.push(nod[i]);
            }
            
            
            if(arrayNodos.length > 0)
            {
                var datos = 
                {
                    opc: 3,
                    nod: arrayNodos,
                    orden :document.querySelector("#id_orden").value,
                    proyecto :document.querySelector("#id_proyect").value
                }

                consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",8,null,2);   
            }
            
            document.querySelector("#btn_wbs_gom").addEventListener("click",function()
            {////////////
                
                var congom=0;
                var tbl = tbl3.rows('.selected').data();
                for (var i = 0; i < tbl.length; i++) {
                    var tabGomDos = tbl[i][10];
                    if(tabGomDos != "" && tabGomDos != "0" && tabGomDos != 0) {
                          congom++;
                          console.log(tabGomDos);
                    }
                }

                if(congom > 0 && congom < tbl.length){
                       // es por gom en nodp pero faltan por asignar
                       mostrarModal(1,null,"GOM","No todos los Nodos tienen una gom .\n",0,"Aceptar","",null);                 
                            return;
                }
                else if(congom==0){
                
                        if(document.querySelector("#select_wbs_gom").selectedIndex == 0)
                        {
                            mostrarModal(1,null,"GOM","Seleccione una gom .\n",0,"Aceptar","",null);                 
                            return;
                        }

                        if(document.querySelector("#select_wbs_gom").value == document.querySelector("#select_gom_inst").value)
                        {
                            mostrarModal(1,null,"GOM","La GOM de ADEC debe ser diferente a la GOM de INST .\n",0,"Aceptar","",null);                 
                            return;
                        }

                }
                

                if(confirm("??Seguro que desea finalizar la programaci??n?"))
                {
                    var datos = 
                    {
                        opc : "3",
                        gom : document.querySelector("#select_wbs_gom").value,
                        gomI : document.querySelector("#select_gom_inst").value,
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value
                    };
                    consultaAjax("{{url('/')}}/guardarOrdenProgramados",datos,120000,"POST",7);
                }
            });

            tbl1 = $('#contenido_nodos_ver').DataTable();
            tblDatos = tbl1;
            tbl3 = $('#contenido_nodos').DataTable();
            tbl2 = $('#contenido_recurso_ver').DataTable();

            /*LOGS*/
            document.querySelector("#btn_ver_logs").addEventListener("click",function()
            {
                var array = 
                {
                    opc : "17",
                    orden :document.querySelector("#id_orden").value
                }
                consultaAjax("{{url('/')}}/consultaBaremos",array,125000,"POST",35); 
            });

            /*ABRIR ORDEN*/
            if(document.querySelector("#btn_abrir_orden") != null && document.querySelector("#btn_abrir_orden") != undefined)
            {
                document.querySelector("#btn_abrir_orden").addEventListener("click",function()
                {
                    if(confirm("??Seguro que desea abrir la orden nuevamente?"))
                    {
                        var array = 
                        {
                            opc : "18",
                            orden :document.querySelector("#id_orden").value
                        }
                        consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",array,125000,"POST",43); 
                    }
                });
            }
            
            @if($encabezado[0]->id_estado == "A1")

            document.querySelector("#observacion_repro").readOnly = false;
            /*REPROGRAMACI??N*/
                document.querySelector("#btn_reprogramacion_maniobra").addEventListener("click",function()
                {
                    document.querySelector("#fecha_repro").value = document.querySelector("#fech_ejecucion").value;
                    document.querySelector("#observacion_repro").value = "";
                    $("#modal_reprogramacion").modal("toggle");
                });

                document.querySelector("#btn_repro_maniobra").addEventListener("click",function()
                {
                    if(document.querySelector("#fecha_repro").value == "" || document.querySelector("#observacion_repro").value == "")
                    {
                        mostrarModal(1,null,"ManiObra","Hace falta ingresar campos.\n",0,"Aceptar","",null);
                        return;
                    }

                    if(confirm("??Seguro que desea re programar la Maniobra?"))
                    {
                        var datos = 
                        {
                            opc : 14,
                            orden :document.querySelector("#id_orden").value,
                            proyecto :document.querySelector("#id_proyect").value,
                            fecha : document.querySelector("#fecha_repro").value.split("/")[2] + "-" + document.querySelector("#fecha_repro").value.split("/")[1] + "-" + document.querySelector("#fecha_repro").value.split("/")[0],
                            observa : document.querySelector("#observacion_repro").value
                        }; 

                        consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,500000,"POST",37);
                    }
                });

                
            @endif

            //Restricciones
            @if($encabezado == null)
                

            @else
                document.querySelector("#btn_restricciones").addEventListener("click",function()
                {
                    $("#modal_restricciones").modal("toggle");
                });


                //Guardar Restricciones
                document.querySelector("#btn_save_restriccion").addEventListener("click",function()
                {
                    if(document.querySelector("#text_restric").selectedIndex == 0 ||
                        document.querySelector("#text_impacto").value == "" ||
                        document.querySelector("#fec_limite").value == "")
                    {
                        mostrarModal(1,null,"Agregar restricci??n","Hace falta ingresar informaci??n, para guardar la restricci??n.\n",0,"Aceptar","",null);
                        return;
                    }

                    if(document.querySelector("#correo_enviar").children.length == 0)
                    {
                        mostrarModal(1,null,"Agregar restricci??n","No ha ingresado ning??n responsable.\n",0,"Aceptar","",null);
                        return;
                    }
                    
                    var correo = Array();
                    for (var i = 0; i < document.querySelector("#correo_enviar").children.length; i++) {
                       correo.push({
                            "nombre" : document.querySelector("#correo_enviar").children[i].children[0].innerHTML,
                            "correo" : document.querySelector("#correo_enviar").children[i].children[1].innerHTML
                        });
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
                        restricD : document.querySelector("#text_descp_restriccion").value,
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value,
                        opc : "15"
                    } 
                }
                else
                {
                    if(document.querySelector("#select_estado").selectedIndex == 2)
                    {
                        if(document.querySelector("#evidencia_cierre").value == "")
                        {
                            mostrarModal(1,null,"Agregar restricci??n","Hace falta ingresar informaci??n, para guardar la restricci??n.\n",0,"Aceptar","",null);
                            return;   
                        }
                    
                    }
                    var array = 
                    {
                        esta : document.querySelector("#select_estado").value,
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value,
                        res_id : document.querySelector("#btn_save_restriccion").dataset.res,
                        opc : "16"
                    } 
                }


                    // consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",array,25000,"POST",38);

                     $(".btnguarda").attr("disabled", true);                      
                     $('.btnguarda').hide("slow",function(){ $('.loading').show("slow"); });

                     $.ajax({
                         url: "{{url('/')}}/guardarAsignacionRecursosMateriales",
                         type: "POST",
                         headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                         dataType: "json",
                         data:array,
                        // timeout:tiempoEspera,
                         success:function(data){
                             
                               document.querySelector("#content_Table_restricciones").innerHTML = data;
                        
                              //  if(dato != 1)
                              //  {
                                    $("#modal_restricciones_add").modal("toggle");
                                    setTimeout(function()
                                    {
                                        $("#modal_restricciones").modal("toggle");
                                    },700);    
                              //  }
                                $('#tbl_restric').DataTable();
                                ocultarSincronizacion();
                    
                             
                         },
                         error: function(data, textStatus) {  
                              mostrarModal(1,null,"Ocurrio un error","Por favor int??ntalo nuevamente m??s tarde.\n",0,"Aceptar","",null);
                            return;
                         }
                     }).always(function() { 

                         $('.loading').hide("slow",function(){ $('.btnguarda').show();});
                         $(".btnguarda").attr("disabled", false);
                     });

                 });
            
                //Consulta Restricci??n
                document.querySelector("#btn_consulta_restric").addEventListener("click",function()
                {
                    var array = 
                    {
                        estado :document.querySelector("#select_estado_filter").value,
                        fecha1 :document.querySelector("#fecha_inicio").value,
                        fecha2 : document.querySelector("#fecha_corte").value,
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value,
                        opc : "24"
                    }

                    consultaAjax("{{url('/')}}/consultaActiMate",array,25000,"POST",38,null,1); 
                });

                //Create resticci??n
                document.querySelector("#btn_create_restric").addEventListener("click",function()
                {
                    document.querySelector("#correo_enviar").innerHTML = "";
                    document.querySelector("#text_restric").selectedIndex = 0;
                    document.querySelector("#text_impacto").value = "";
                    document.querySelector("#text_descp_restriccion").value = "";
                    document.querySelector("#fec_limite").value = "";
                    document.querySelector("#text_responsable_restric").selectedIndex = 0;
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
            
                //Cerrar restricci??n
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
                    {
                        document.querySelector("#evidencia_cierre_input").style.display = "block";
                        document.querySelector("#btn_save_restriccion").style.display = "inline-block";
                    }
                    else
                    {
                        document.querySelector("#evidencia_cierre_input").style.display = "none";
                        if(this.selectedIndex == 1)
                           document.querySelector("#btn_save_restriccion").style.display = "inline-block"; 
                        else
                           document.querySelector("#btn_save_restriccion").style.display = "none"; 
                        
                    }
                });
                $('#tbl_restric').DataTable();
            @endif

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
                                   //mensajes("??xito","Proceso finalizado satisfactoriamente.",1);

                                   $("#estado_nodo").val(data.estado);
                                   if(data.gom==0 || data.gom=='0'){
                                       $(".plnodos").hide();
                                       $("#plantilla_nodo").val("");
                                   }else{
                                       $(".plnodos").show();
                                       $('#plantilla_nodo').prop('readonly', false);
                                       if(data.plantilla!=0 && data.plantilla!='0'){
                                            $("#plantilla_nodo").val(data.plantilla);
                                       }
                                   }
                                   
                                   
                           }else if(data.status == 0){   
                                   $("#estado_nodo").val("");
                                //   mensajes("Error",data.response.message,0); 
                           }else{ 
                             //  mensajes("Error","Ocurri?? un error por favor int??ntalo nuevamente mas tarde.",0); 
                           }
                   }, 
                   error: function(){ 
                      // mensajes("Error","Ocurri?? un error por favor int??ntalo nuevamente mas tarde.",0);  
                   }
           }).always(function(){              
          });  
        }
        
        function cambiaestado(){
             var nodo = $("#select_nodos_afectados").val();
             var estadon = $("#estado_nodo").val();
             var planilla = $("#plantilla_nodo").val();
             if(planilla=='0' || planilla==0){
                 planilla="";
             }               
                            
                            
             $("#form_estadonodo").find('.btnfrmocd').hide("slow",function(){ 
              
                    $("#form_estadonodo").find('.loadingd').show("slow"); 
                     
                     $.ajax({
                            type: 'POST',
                            url: "<?= Request::root() ?>/redes/ordenes/cambiaestado",
                            data: {id_nodo:nodo,
                                   estadon:estadon,
                                   planilla:planilla,
                                   _token:'<?= csrf_token() ?>' },
                            dataType: "json",
                            success: function(data) {
                                    if(data.status == 1){ 
                                        mensajes("??xito","Proceso finalizado satisfactoriamente.",1);
                                    }else if(data.status == 0){   
                                        mensajes("Error",data.message,0); 
                                    }else{ 
                                        mensajes("Error","Ocurri?? un error por favor int??ntalo nuevamente mas tarde.",0); 
                                    }
                            }, 
                            error: function(){ 
                               // mensajes("Error","Ocurri?? un error por favor int??ntalo nuevamente mas tarde.",0);  
                            }
                    }).always(function(){              
                        $("#form_estadonodo").find('.loadingd').hide("slow",function(){$("#form_estadonodo").find('.btnfrmocd').show();}); 
                   });  
             });
        
        }

        function consultaPorcentaProduccion()//PORCENTA DE PRODUCCI??N
        {
            var datoTables = document.querySelector("#contenido_recurso_ver").children[1].children;
            var arregloLideres = [];
            for (var i = 0; i < datoTables.length; i++) {
                if(datoTables[i].children.length == 0)
                    continue;

                @if($encabezado != null)
                    
                        arregloLideres.push(
                        {
                            "lider" : datoTables[i].children[3].innerHTML,
                            "tipo" : datoTables[i].children[2].innerHTML
                        });
                @endif
            };

            if(datoTables.length == 0)
            {
                return;    
            }

            var datos = 
            {
                opc: 30,
                lid: arregloLideres,
                ot : document.querySelector("#fech_ejecucion").value,
                orden:document.querySelector("#id_orden").value
            }

            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",42,null,-1);     
        }

        //RESTRICCION
        function abrirModalRestriccion(ele)
        {
            document.querySelector("#correo_enviar").innerHTML = "";
            document.querySelector("#text_restric").value = ele.dataset.tipo;
            document.querySelector("#text_impacto").value = ele.parentElement.parentElement.children[2].innerHTML.split("160px;")[1].split("</p>")[0].replace('"','').replace(">",'');
            document.querySelector("#text_impacto").readOnly = true;
            document.querySelector("#fec_limite").readOnly = true;
            document.querySelector("#text_responsable_restric").disabled = true;
            document.querySelector("#text_correo_responsable").readOnly = true;
            document.querySelector("#btn_add").style.display = "none";
            document.querySelector("#btn_1_fecha").style.visibility = "hidden";
            document.querySelector("#btn_2_fecha").style.visibility = "hidden";
            document.querySelector("#text_restric").disabled = true;
            document.querySelector("#text_descp_restriccion").readOnly = true;
            var fecha = ele.parentElement.parentElement.children[3].children[0].innerHTML.split("-");
            fecha = fecha[2] + "/" + fecha[1] + "/" + fecha[0];
            document.querySelector("#fec_limite").value = fecha;
            document.querySelector("#text_responsable_restric").value = ele.parentElement.parentElement.children[4].innerHTML;
            
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


        function addCorreo()
        {
            if(!validarEmail(document.querySelector("#text_correo_responsable").value))
            {
                mostrarModal(1,null,"Agregar restricci??n","El email ingresado no es correcto.\n",0,"Aceptar","",null);
                return;
            }

            $("#correo_enviar").append("<tr><td>" + document.querySelector("#text_correo_responsable").value + "</td><td><button onclick='deleteCorreo(this)'><i class='fa fa-times'></i> </button></td></tr>");
            document.querySelector("#text_correo_responsable").value = "";
        }

        function deleteCorreo(ele)
        {
           $("#correo_enviar").find(ele.parentElement.parentElement).remove();
        }


        var dcSelec = null;
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
                    mostrarModal(1,null,"Consultar ejecuci??n","Ingrese la fecha de ejecuci??n, para consultar la informaci??n." + nodo + ".\n",0,"Aceptar","",null);
                    return;
                }
                
                fechaE = fechaE.split("/")[2] + "-" + fechaE.split("/")[1] + "-" + fechaE.split("/")[0];

                var datos = 
                {
                    opc: 6,
                    lid: document.querySelector("#select_lider_carga").value,
                    ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                    nodo: document.querySelector("#select_nodos_afectados").value,
                    dc : ele.innerHTML,
                    fecha_consulta : fechaE
                } 
            }
            else
            {
                var datos = 
                {
                    opc: 6,
                    lid: document.querySelector("#select_lider_carga").value,
                    ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                    nodo: document.querySelector("#select_nodos_afectados").value,
                    dc : ele.innerHTML
                } 
            }  


            

            dcSelec = ele.innerHTML;
            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",26);   
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
                ot : document.querySelector("#id_orden").value,
                nodo: document.querySelector("#select_nodos_afectados1").value,
                dc : ele.innerHTML
            }

            dcSelec = ele.innerHTML;
            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",31);   
        }

        function anularDc(DC)
        {
            if(confirm("??Seguro que desea desasociar el DC"))
            {
                var datos = 
                {
                    opc : 12,
                    dc : DC,
                    ot : document.querySelector("#id_orden").value
                }; 

                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,30000,"POST",24);
            }

        }

        function agregarEventosTodo()
        {
            if(opcGuardar == 1) //Asignado todo Baremos a una persona
            {
                var datos = 
                {
                    opc : 1,
                    nod : nodoSele,
                    resp : persoaCargo,
                    orden :document.querySelector("#id_orden").value,
                    proyecto :document.querySelector("#id_proyect").value,
                    op : 1
                };
                document.querySelector("#modal_mensaje_1").style.display ="none";
                opcionNodo1 = 2;
                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,120000,"POST",15);
            }
            else
            {
                var datos = 
                {
                    opc : 3,
                    nod : nodoSele,
                    resp : persoaCargo,
                    orden :document.querySelector("#id_orden").value,
                    proyecto :document.querySelector("#id_proyect").value,
                    op : 1
                };
                document.querySelector("#modal_mensaje_1").style.display ="none";
                opcionNodo1 = 6;
                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,120000,"POST",15);
                
            }
            

        }
        
        function agregarSoloVacios()
        {
            if(opcGuardar == 1) //Baremos solo vacios
            {
                var datos = 
                {
                    opc : 1,
                    nod : nodoSele,
                    resp : persoaCargo,
                    orden :document.querySelector("#id_orden").value,
                    proyecto :document.querySelector("#id_proyect").value,
                    op : 2
                };
                
                document.querySelector("#modal_mensaje_1").style.display ="none";
                opcionNodo1 = 3;
                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,120000,"POST",15);
            }
            else
            {
                var datos = 
                {
                    opc : 3,
                    nod : nodoSele,
                    resp : persoaCargo,
                    orden :document.querySelector("#id_orden").value,
                    proyecto :document.querySelector("#id_proyect").value,
                    op : 2
                };
                document.querySelector("#modal_mensaje_1").style.display ="none";
                opcionNodo1 = 7;
                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,120000,"POST",15);
            }
            
        }
        
        function cerrarTodos()
        {
            nodoSele = null;
            persoaCargo = null;
            opcionNodo = null;
            opcionNodo1 = null;
            document.querySelector("#modal_mensaje_1").style.display ="none";
        }

        function addNodosSelect(ele)
        {

            var arrayNodos = [];
            var arrayNodos1 = [];
            var aux0 = 0;

            var html = "";

            var tbl = tbl3.rows('.selected').data();
            if(tbl.length == 0)
            {
                mostrarModal(1,null,"Agregar nodos","No ha seleccionado ning??n nodo.\n",0,"Aceptar","",null);
                return;
            }
            
            var dNoGOM = 0;
            var GOMFACTURADA = 0;
            var wsnodo = "";
            for (var i = 0; i < tbl.length; i++) {
                
                if(i==0){
                  wsnodo =  tbl[i][0].split("data-ele=")[1].split(";")[0].trim();
                  console.log("arriba |"+wsnodo+"|");
                }else{
                   console.log("arriba |"+wsnodo+"|"+tbl[i][0].split("data-ele=")[1].split(";")[0].trim()+"|");
                    if(wsnodo != tbl[i][0].split("data-ele=")[1].split(";")[0].trim()){
                        mostrarModal(1,null,"WS","Solo es posible agregar Nodos de una WS por orden .\n",0,"Aceptar","",null);
                        return;
                    }
                }
                
                var tab = tbl[i][0].split("data-ele=")[1].split(";")[1].trim();
                arrayNodos1.push(tab);
                var tabGom = tbl[i][9];
                var tabGomDos = tbl[i][11];
                

                if(tabGom == "" && tabGomDos =="" )
                    tabGom = -1;

                var estadoGOm = parseInt(tbl[i][9].trim());


                if(estadoGOm == 5 || estadoGOm == 6)
                {
                    GOMFACTURADA++;
                    break;
                }

                if(parseInt(tabGom) == -1)
                {
                    dNoGOM++;
                    break;
                }

                var encontrada = 0;
                for (var j = 0; j < document.querySelector("#nodos_select_recurso").children.length; j++) {

                    if(document.querySelector("#nodos_select_recurso").children[j].children[0].innerHTML == "No hay datos para mostrar")
                        break;

                    if(document.querySelector("#nodos_select_recurso").children[j].children[1].innerHTML == tbl[i][2])
                    {
                        encontrada = 1;
                        break;
                    }
                }

                if(encontrada == 0)
                {
                    tbl1.row.add( [
                    tbl[i][1],
                    tbl[i][2],
                    tbl[i][3],
                    tbl[i][4],
                    tbl[i][5],
                    tbl[i][6],
                    tbl[i][10],
                    tab
                ] ).draw( false );  
                }
            };

            if(dNoGOM > 0 )
            {
                mostrarModal(1,null,"Agregar nodos","Algunos de los nodos seleccionados, no cuentan con GOM  .\n",0,"Aceptar","",null);
                return;
            }

            if(GOMFACTURADA > 0 )
            {
               mostrarModal(1,null,"Agregar nodos","No puede seleccionar algunos de los nodos, por que cuentan con la GOM ya cerrada .\n",0,"Aceptar","",null);
               return;
            }

            
            var datos = 
            {
                opc: 3,
                nod: arrayNodos1,
                orden :document.querySelector("#id_orden").value,
                proyecto :document.querySelector("#id_proyect").value
            }
            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",8);
        }

        function pintarFila(ele)
        {
            $( ele.parentElement.parentElement).toggleClass('selected');
        }

        function consultaRecursoAdd()
        {

            if(tip != null)
            {
                if(tip == 5)
                {
                    if(document.querySelector("#text_iden_recurso").value == "")
                    {
                        mostrarModal(1,null,"ManiObra","Seleccione por lo menos un filtro .\n",0,"Aceptar","",null);
                        return;
                    }

                    var datos = 
                    {
                        opc : 4,
                        cod : document.querySelector("#text_iden_recurso").value,
                        hora1 :horaI,
                        hora2 :horaF,
                        orden :document.querySelector("#id_orden").value,
                        tip : tip
                    };
                    
                    consultaAjax("{{url('/')}}/consultaBaremos",datos,20000,"POST",18,null,tip);

                }
                else
                {
                    if(document.querySelector("#text_fecha_ini").value == "" &&
                    document.querySelector("#text_fecha_fin").value == "")
                    {
                        mostrarModal(1,null,"ManiObra","Debe seleccionar hora de inicio y de hora de fin .\n",0,"Aceptar","",null);
                        return;
                    }


                    var datos = 
                    {
                        opc : 4,
                        cod : document.querySelector("#text_iden_recurso").value,
                        aux : document.querySelector("#text_nombre_rec").value,
                        hora1 :horaI,
                        hora2 :horaF,
                        orden :document.querySelector("#id_orden").value,
                        tip : tip
                    };
                    
                    consultaAjax("{{url('/')}}/consultaBaremos",datos,20000,"POST",18,null,"1"); 
                }
                return;
            }


            if(document.querySelector("#text_fecha_ini").value == "" ||
                document.querySelector("#text_fecha_fin").value == "")
            {
                mostrarModal(1,null,"ManiObra","Ingrese hora de inicio y hora de fin .\n",0,"Aceptar","",null);
                return;
            }


            //Validaci??n de las horas
            //var hora1 = document.querySelector("#fecha_ini_hidden").value.trim().split(":");
            //var hora2 = document.querySelector("#fecha_fin_hidden").value.trim().split(":");
            if(!comparaFechas(document.querySelector("#fecha_ini_hidden").value,document.querySelector("#text_fecha_ini").value))
            {
                mostrarModal(1,null,"ManiObra","La hora de inicio, no puede ser menor a la hora de inicio de la ManiObra .\n",0,"Aceptar","",null);
                return;
            }

            if(!comparaFechas(document.querySelector("#text_fecha_fin").value,document.querySelector("#fecha_fin_hidden").value))
            {
                mostrarModal(1,null,"ManiObra","La hora de fin, no puede ser mayor a la hora de inicio de la ManiObra .\n",0,"Aceptar","",null);
                return;
            }

            var datos = 
            {
                opc : 3,
                cod : document.querySelector("#text_iden_recurso").value,
                des : document.querySelector("#text_nombre_rec").value,
                tipo : document.querySelector("#select_tipo_recurso").value,
                hora1 : document.querySelector("#text_fecha_ini").value,
                    orden :document.querySelector("#id_orden").value,
                proyecto :document.querySelector("#id_proyect").value,
                hora2 : document.querySelector("#text_fecha_fin").value,

            };
            
            consultaAjax("{{url('/')}}/consultaBaremos",datos,20000,"POST",3,null,"1"); 
        }

        function consultaFiltroPrimeraVez()
        {
            var datos = 
            {
                opc : 3,
                cod : "",
                des : document.querySelector("#text_nombre_rec").value,
                tipo : document.querySelector("#select_tipo_recurso").value,
                hora1 : document.querySelector("#text_fecha_ini").value,
                    orden :document.querySelector("#id_orden").value,
                proyecto :document.querySelector("#id_proyect").value,
                hora2 : document.querySelector("#text_fecha_fin").value
            };
            
            consultaAjax("{{url('/')}}/consultaBaremos",datos,20000,"POST",3,null,1); 
        }

        function addRegistroTbl()
        {
            if(document.querySelector("#text_movil_recurso").value == "" ||
                document.querySelector("#text_tipo_c_recurso").value == "" ||
                document.querySelector("#text_lider_recurso").value == "" ||
                document.querySelector("#text_nombre_recurso").value == "" ||
                document.querySelector("#text_fecha_ini").value == "" ||
                document.querySelector("#text_fecha_fin").value == "")
            {
                mostrarModal(1,null,"ManiObra","Hace falta datos para guardar.\n",0,"Aceptar","",null);
                return;
            }
            var datos = 
            {
                opc : 2,
                lid : document.querySelector("#text_lider_recurso").value,
                hor1 : document.querySelector("#text_fecha_ini").value,
                hor2 : document.querySelector("#text_fecha_fin").value,
                orden :document.querySelector("#id_orden").value,
                movi : document.querySelector("#text_tipo_c_recurso").dataset.ti,
                movi : document.querySelector("#text_tipo_c_recurso").dataset.ti,

            };
            
            var dat = 
            {
                movi : document.querySelector("#text_movil_recurso").value,
                movi : document.querySelector("#text_tipo_c_recurso").value,
                lid : document.querySelector("#text_lider_recurso").value,
                orden :document.querySelector("#id_orden").value,
                lid : document.querySelector("#text_nombre_recurso").value,
                hor1 : document.querySelector("#text_fecha_ini").value,
                hor2 : document.querySelector("#text_fecha_fin").value
            };
            

            consultaAjax("{{url('/')}}/consultaActiMate",datos,20000,"POST",4,null,dat);
        }

        function abrirModalNodos()
        {
            //document.querySelector("#tbl_recurso_add_nuevo").innerHTML = "";
            $("#modal_add_nodos_orden").modal("toggle");
        }

        function abrirModalRecurso()
        {
    
            document.querySelector("#text_iden_recurs").innerHTML = "Lider";
            document.querySelector("#tbl_recurso_add_nuevo").innerHTML = "";
            /*document.querySelector("#consulta_cuadrillero_0").style.display = "block";*/
            document.querySelector("#consulta_cuadrillero_1").style.display = "block";
            document.querySelector("#consulta_cuadrillero_2").style.display = "block";
            document.querySelector("#consulta_cuadrillero_3").style.display = "block";
            /*document.querySelector("#consulta_cuadrillero_4").style.display = "block";
            document.querySelector("#consulta_cuadrillero_5").style.display = "none";
            document.querySelector("#consulta_cuadrillero_6").style.display = "none";*/

            document.querySelector("#text_fecha_ini").value = document.querySelector("#text_hora_corte").value;
            document.querySelector("#text_fecha_fin").value = document.querySelector("#text_hora_fin").value;
            
            if(tip != null)
            {
                document.querySelector("#text_iden_recurso").value = "";
                document.querySelector("#text_nombre_rec").value = "";

                lid = null;
                aux = null;
                horaI = null;
                horaF = null;
                tip = null;
            }
            
            $("#tbl_recu_add").find("option").remove();
            $("#modal_add_personas_orden").modal("toggle");
            //$("#modal_add_personas_orden").modal("toggle");
        }

        function volverModalRecurso()
        {
          $("#modal_add_personas_orden").modal("toggle");   
        }

        function agregarTablaRecurso(ele)
        {
            var datos = 
            {
                opc : 5,
                cod : ele.dataset.lid,
                hor1 : document.querySelector("#text_fecha_ini").value,
                hor2 : document.querySelector("#text_fecha_fin").value,
                tip : ele.dataset.tipoc,
                aux1 : (ele.dataset.disp1 == "0" ? ele.dataset.aux1 : ""),
                aux2 : (ele.dataset.disp2 == "0" ? ele.dataset.aux2 : ""),
                aux3 : (ele.dataset.disp3 == "0" ? ele.dataset.aux3 : ""),
                cond : (ele.dataset.disp4 == "0" ? ele.dataset.cond : ""),
                movi : (ele.dataset.dispMovil == "0" ? ele.dataset.matri : "")
            };
            
            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",12,null,ele);

        }

        function agregarPersona()
        {
            if(document.querySelector("#text_iden_recurso").selectedIndex != 0)
            {
                var ele = document.querySelector("#text_iden_recurso").options[document.querySelector("#text_iden_recurso").selectedIndex];
                
                var hijos = document.querySelector("#tbl_recurso_add_nuevo").children;
                var exi = 0;
                for (var i = 0; i < hijos.length; i++) {
                    if(hijos[i].dataset.lid == ele.dataset.lid)
                    {
                        exi = 1;
                        break;
                    }
                };

                if(exi == 1)
                {
                    mostrarModal(1,null,"Agregar recurso","La cuadrilla ya ha sido agregada.\n",0,"Aceptar","",null);
                    return;
                }
                
                var hijos2 = document.querySelector("#contenido_recurso_ver").children[1].children;
                exi = 0;
                for (var i = 0; i < hijos2.length; i++) 
                {
                    if(hijos2[i].children[0],innerHTML = "No hay datos para mostrar")
                        break;
                    
                    if(hijos2[i].children[2].innerHTML == ele.dataset.lid)
                    {
                        exi = 1;
                        break;
                    }
                };

                if(exi == 1)
                {
                    mostrarModal(1,null,"Agregar recurso","La cuadrilla ya ha sido agregada.\n",0,"Aceptar","",null);
                    return;
                }

                var aux1 = (ele.dataset.disp1 == "0" ? ele.dataset.aux1 : "");
                var  aux2 = (ele.dataset.disp2 == "0" ? ele.dataset.aux2 : "");
                var  aux3 = (ele.dataset.disp3 == "0" ? ele.dataset.aux3 : "");
                var  cond = (ele.dataset.disp4 == "0" ? ele.dataset.cond : "");
                var  movi = (ele.dataset.dispMovil == "0" ? ele.dataset.matri : "");
                var html ="";
                html += "<tr data-lid='" + ele.dataset.lid + "' data-tipoc='" + ele.dataset.tipoc + "' data-hi='" + document.querySelector("#text_fecha_ini").value + "' data-hf='" + document.querySelector("#text_fecha_fin").value + "' data-aux1='" + ele.dataset.aux1 + "' data-aux2='" + ele.dataset.aux2 + "' data-aux3='" + ele.dataset.aux3 + "' data-cond='" + ele.dataset.cond + "' data-movi='" + ele.dataset.movi + "'>";
                    html += "<td>" +  ele.dataset.movil + "</td>";
                    html += "<td>" +  ele.dataset.tipo + "</td>";
                    html += "<td>" +  ele.dataset.lid + " - " + ele.dataset.nombre + "</td>";
                    html += "<td>" +  document.querySelector("#text_fecha_ini").value  + "</td>";
                    html += "<td>" +  document.querySelector("#text_fecha_fin").value  + "</td>";
                    html += "<td><i class='fa fa-times' onclick='deleteLider(this)' style='    font-size: 20px;  color: red;  display: block;  text-align: center;    cursor: pointer;'></i></td>";
                html += "</tr>";

                $("#tbl_recurso_add_nuevo").append(html);
            }
        }

        function deleteLider(ele)
        {
            $("#tbl_recurso_add_nuevo").find(ele.parentElement.parentElement).remove();
        }

        function agregarPersonaSave()
        {
            
            if(document.querySelector("#tbl_recurso_add_nuevo").children.length != 0)

            var hijos = document.querySelector("#tbl_recurso_add_nuevo").children;
            var arrayRecurso = [];
            for (var i = 0; i < hijos.length; i++) {
                arrayRecurso.push(
                    {
                        cod : hijos[i].dataset.lid,
                        hor1 : hijos[i].dataset.hi.split("-")[0],
                        hor2 : hijos[i].dataset.hf,
                        tip : hijos[i].dataset.tipoc,
                        aux1 : (hijos[i].dataset.disp1 == "0" ? hijos[i].dataset.aux1 : ""),
                        aux2 : (hijos[i].dataset.disp2 == "0" ? hijos[i].dataset.aux2 : ""),
                        aux3 : (hijos[i].dataset.disp3 == "0" ? hijos[i].dataset.aux3 : ""),
                        cond : (hijos[i].dataset.disp4 == "0" ? hijos[i].dataset.cond : ""),
                        movi : (hijos[i].dataset.dispMovil == "0" ? hijos[i].dataset.matri : "")
                    }
                );
            };
            var datos = 
            {
                opc : 5,
                orden :document.querySelector("#id_orden").value,
                proyecto :document.querySelector("#id_proyect").value,
                recurso : arrayRecurso
            };
            
            //alert(JSON.stringify(datos));
            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",12,null);
            
        }

        function agregarTablaRecursoAux(ele)
        {
            var datos = 
            {
                opc : 7,
                cod : ele.dataset.cod,
                lid : lid,
                tip : tip
            };
            
            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",19);
        }

        function agregarTablaRecursoMatri(ele)
        {
            var datos = 
            {
                opc : 7,
                cod : ele.dataset.cod,
                lid : lid,
                tip : tip
            };
            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",19);
        }

        function saveNodos()
        {
            var nodes = $("#treeview9").treeview('getNode', arbolNodo); 
            var personaACargo = [];
            nodosSelecArray = [];
            if(nodes.nodes.length > 0) //Tiene m??s de un hijo
            {
                for (var i = 0; i < nodes.nodes.length; i++) { //Recorremos todos los hijos
                    var nodoT = $("#treeview9").treeview('getNode', nodes.nodes[i].nodeId); 
                    nodosSelecArray.push(nodoT);
                    var dat = nodoT.href.split(";");
                    personaACargo.push(dat[4]);
                };

                var cantNullos = 0;
                var cantNoNullos = 0;
                var cantidadRep = 0;
                var penS = personaACargo[0];
                for (var i = 0; i < personaACargo.length; i++) {
                    if(personaACargo[i] == "null")
                        cantNullos++;

                    if(personaACargo[i] != "null")
                        cantNoNullos++;

                    if(penS == personaACargo[i+1])
                        cantidadRep++;
                };

                if(((cantNoNullos == 0 && cantNullos > 0) || (cantNoNullos > 0 && cantNullos == 0))
                && cantidadRep == (i-1)) //Guardar todos a una persona
                {
                    if(document.querySelector("#combox_1_recurso").selectedIndex == 0 &&
                        (cantNullos > 0 && cantNoNullos == 0))
                    {
                        return;
                    }

                    var datos = 
                    {
                        opc : 1,
                        nod : document.querySelector("#combox_1_recurso").dataset.nodo,
                        resp : document.querySelector("#combox_1_recurso").value,
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value,
                        op : 1
                    };
                    opcGuardar = 1;
                    persoaCargo = document.querySelector("#combox_1_recurso").value;
                    if(document.querySelector("#combox_1_recurso").selectedIndex == 0)
                        opcionNodo1 = 4; // Desasignar todo el personal
                    else    
                        opcionNodo1 = 1; // El nodo esta vacio, o ya lo pertene a una persona

                    consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",15);
                }
                else
                {
                    nodoSele = document.querySelector("#combox_1_recurso").dataset.nodo;
                    persoaCargo = document.querySelector("#combox_1_recurso").value;
                    opcionNodo = 0;

                    if(document.querySelector("#combox_1_recurso").selectedIndex == 0)
                    {
                        var datos = 
                        {
                            opc : 1,
                            nod : document.querySelector("#combox_1_recurso").dataset.nodo,
                            resp : "0",
                            orden :document.querySelector("#id_orden").value,
                            proyecto :document.querySelector("#id_proyect").value,
                            op : 1
                        };
                        opcionNodo1 = 4; //Desagsinar todo el personal
                        consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",15);
                        return;
                    }
                    opcGuardar = 1;
                    document.querySelector("#modal_mensaje_1").style.display ="block";
                    document.querySelector("#title_modal_1").innerHTML ="Baremos";
                    document.querySelector("#texto_conten_1").innerHTML ="Ya tiene personas seleccionadas en este nodo, desea asignar todo el nodo solo al lider seleccionado, o solo a los actividades que no tiene lider asignado";
                }
            }

        }

        var arregloNodosSeleccionados = [];
        var arreglosTree = null;

        function saveNodosBaremos()
        {
            if(document.querySelector("#combox_2_recurso").selectedIndex == -1 ||
                document.querySelector("#combox_2_recurso").value == "")
            {
                mostrarModal(1,null,"Seleccionar cuadrilla","No ha seleccionado ninguna cuadrilla para asignar el/los baremo/s seleccionado/s.\n",0,"Aceptar","",null);
                return;
            }

            //Recorrer arreglos de nodos para guardar la informaci??n
            var datosArray = JSON.parse(document.querySelector("#combox_2_recurso").dataset.nodo);
            var nodo = "";
            var cantidad = 0;
            var arregloBare = [];
            for (var i = 0; i < datosArray.length; i++) {
                nodo = datosArray[i].nodo;

                if(datosArray.length == 1)
                {
                    arregloBare.push(
                    {
                        bare : datosArray[i].datos["href"].split("_")[1].split(";")[0],
                        cant : document.querySelector("#text_cant_baremos").value
                    });
                }
                else
                {   
                    arregloBare.push(
                    {
                        bare : datosArray[i].datos["href"].split("_")[1].split(";")[0],
                        cant : datosArray[i].datos["tags"][3],
                    });
                }
            }

            var datos = 
            {
                opc : 2,
                nod : nodo,
                resp : document.querySelector("#combox_2_recurso").value,
                baremos : arregloBare,
                //cant : document.querySelector("#text_cant_baremos").value,
                //bare : document.querySelector("#combox_2_recurso").dataset.baremo,
                orden :document.querySelector("#id_orden").value,
                proyecto :document.querySelector("#id_proyect").value
            };

            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",14,null,arbolBaremo);
        }

        function saveNodosMat()
        {
            var nodes = $("#treeview1").treeview('getNode', arbolNodoM); 
            var personaACargo = [];
            nodosSelecArray1 = [];
            if(nodes.nodes.length > 0) //Tiene m??s de un hijo
            {
                for (var i = 0; i < nodes.nodes.length; i++) { //Recorremos todos los hijos
                    var nodoT = $("#treeview1").treeview('getNode', nodes.nodes[i].nodeId); 
                    var tags_1 = nodoT.tags; 
                    
                    if(document.querySelector("#clasi_dato").value != "0")
                    {
                        if(tags_1[4] != document.querySelector("#clasi_dato").value) 
                            continue;
                    }

                    nodosSelecArray1.push(nodoT);
                    var dat = nodoT.href.split(";");
                    personaACargo.push(dat[4]);
                };

                var cantNullos = 0;
                var cantNoNullos = 0;
                var cantidadRep = 0;
                var penS = personaACargo[0];
                for (var i = 0; i < personaACargo.length; i++) {
                    if(personaACargo[i] == "null")
                        cantNullos++;

                    if(personaACargo[i] != "null")
                        cantNoNullos++;

                    if(penS == personaACargo[i+1])
                        cantidadRep++;
                };

                if(((cantNoNullos == 0 && cantNullos > 0) || (cantNoNullos > 0 && cantNullos == 0))
                && cantidadRep == (i-1)) //Guardar todos a una persona
                {
                    if(document.querySelector("#combox_1_materiales").selectedIndex == 0 &&
                        (cantNullos > 0 && cantNoNullos == 0))
                    {
                        return;
                    }

                    var datos = 
                    {
                        opc : 3,
                        nod : document.querySelector("#combox_1_materiales").dataset.nodo,
                        resp : document.querySelector("#combox_1_materiales").value,
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value,
                        op : 1
                    };
                    
                    opcGuardar = 2;

                    persoaCargo = document.querySelector("#combox_1_materiales").value;
                    if(document.querySelector("#combox_1_materiales").selectedIndex == 0)
                        opcionNodo1 = 8;
                    else    
                        opcionNodo1 = 5;
                    
                    consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,120000,"POST",15);
                }
                else
                {
                    nodoSele = document.querySelector("#combox_1_materiales").dataset.nodo;
                    persoaCargo = document.querySelector("#combox_1_materiales").value;
                    opcionNodo = 0;

                    if(document.querySelector("#combox_1_materiales").selectedIndex == 0)
                    {
                        var datos = 
                        {
                            opc : 3,
                            nod : document.querySelector("#combox_1_materiales").dataset.nodo,
                            resp : "0",
                            orden :document.querySelector("#id_orden").value,
                            proyecto :document.querySelector("#id_proyect").value,
                            op : 1
                        };
                        opcionNodo1 = 8;
                        consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",15);
                        return;
                    }
                    opcGuardar = 2;
                    document.querySelector("#modal_mensaje_1").style.display ="block";
                    document.querySelector("#title_modal_1").innerHTML ="Materiales";
                    document.querySelector("#texto_conten_1").innerHTML ="Ya tiene personas seleccionadas en este nodo, desea asignar todo el nodo solo al lider seleccionado, o solo a los materiales que no tiene lider asignado";
                }
                //alert(cantNullos + "-" + cantNoNullos);

            }
            /*
            var datos = 
            {
                opc : 4,
                nod : document.querySelector("#combox_2_materiales").dataset.nodo,
                resp : document.querySelector("#combox_2_materiales").value,
                cant : document.querySelector("#text_cant_materiales").value,
                arti : document.querySelector("#combox_2_materiales").dataset.articulo
            };
            
            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",16,null,arbolBaremoM);*/

        }

        function saveNodosMateriales()
        {
            var datos = 
            {
                opc : 4,
                nod : document.querySelector("#combox_2_materiales").dataset.nodo,
                resp : document.querySelector("#combox_2_materiales").value,
                cant : document.querySelector("#text_cant_materiales").value,
                arti : document.querySelector("#combox_2_materiales").dataset.articulo,
                orden :document.querySelector("#id_orden").value,
                proyecto :document.querySelector("#id_proyect").value
            };
            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",16,null,arbolBaremoM);
        }

        var arbolNodo = null;
        var arbolBaremo = null;
        var optArbol = 0;
        var ArbolAux = 0;

        var nodosSeleccionadosBaremos = [];
        var baremosSeleccionadosMultiples = [];

        function crearArbolJS(alternateData)
        {
            $('#treeview9').treeview({
                      expandIcon: 'glyphicon glyphicon-chevron-right',
                      collapseIcon: 'glyphicon glyphicon-chevron-down',
                      nodeIcon: "glyphicon glyphicon-user",
                      color: "#428bca",
                      showTags: true,
                      multiSelect : true,
                      data: alternateData,
                      onNodeUnselected: function(event,data)
                      {
                        var tipo = data["href"].split("_")[0].replace("#","");
                        var cod = data["href"].split("_")[1]
                        if(data["href"].split("_").length == 3)
                            cod = data["href"].split("_")[1] + "" + data["href"].split("_")[2];

                        if(tipo == "B")
                        {

                            for (var i = 0; i < nodosSeleccionadosBaremos.length; i++) {
                                $('#treeview9').treeview('unselectNode', [ nodosSeleccionadosBaremos[i].nodeId, { silent: true } ]);
                            }

                            nodosSeleccionadosBaremos = [];

                            cod = cod.split(";");
                            arbolBaremo = data.nodeId;
                            
                            var bare = cod[0];
                            var nodo = cod[2];
                            var cant = cod[3];

                            var exist = 0;
                            for (var i = 0; i < baremosSeleccionadosMultiples.length; i++) {

                                if(baremosSeleccionadosMultiples[i].nodo == nodo
                                    && baremosSeleccionadosMultiples[i].datos.nodeId == arbolBaremo)
                                {
                                    exist = i;
                                    break;
                                }
                            }

                            baremosSeleccionadosMultiples.splice(exist,1);

                            var baremos = "";
                            for (var i = 0; i < baremosSeleccionadosMultiples.length; i++) {
                                
                                baremos += baremosSeleccionadosMultiples[i].datos["href"].split("_")[1].split(";")[0] + ", ";
                            }

                            console.log(baremosSeleccionadosMultiples);

                            if(baremosSeleccionadosMultiples.length == 1)
                            {
                                document.querySelector("#nombre-plan").innerHTML = "BAREMO: " + cod[0];
                                document.querySelector("#text_baremo_text").innerHTML = cod[1];
                                document.querySelector("#combox_2_recurso").dataset.tiposave = 1;
                                document.querySelector("#text_cant_baremos").value = cant;
                                document.querySelector("#text_cant_baremos").disabled = false;
                            }
                            else
                            {
                                document.querySelector("#nombre-plan").innerHTML = "BAREMO: M??s de 1 Baremo seleccionado";
                                document.querySelector("#text_baremo_text").innerHTML = "Baremos seleccionados: " + baremos;
                                document.querySelector("#combox_2_recurso").dataset.tiposave = 2;
                                document.querySelector("#text_cant_baremos").value = "N/A";
                                document.querySelector("#text_cant_baremos").disabled = true;
                            }

                            document.querySelector("#combox_2_recurso").dataset.nodo = JSON.stringify(baremosSeleccionadosMultiples);
                            
                            document.querySelector("#combox_2_recurso").dataset.baremo = "";


                        }                        
                      },
                      onNodeSelected: function(event, data) {

                        document.querySelector("#panel-actividades").style.display = "block";
                        
                        //alert(JSON.stringify(data));

                        var tipo = data["href"].split("_")[0].replace("#","");
                        var cod = data["href"].split("_")[1]
                        if(data["href"].split("_").length == 3)
                            cod = data["href"].split("_")[1] + "" + data["href"].split("_")[2];

                        
                        if(tipo == "N")
                        {

                            //$('#treeview9').treeview('uncheckAll', { silent: true });

                            for (var i = 0; i < nodosSeleccionadosBaremos.length; i++) {
                                $('#treeview9').treeview('unselectNode', [ nodosSeleccionadosBaremos[i].nodeId, { silent: true } ]);
                            }

                            for (var i = 0; i < baremosSeleccionadosMultiples.length; i++) {
                                $('#treeview9').treeview('unselectNode', [ baremosSeleccionadosMultiples[i].datos.nodeId, { silent: true } ]);
                            }
                            baremosSeleccionadosMultiples = [];  

                            nodosSeleccionadosBaremos = [];
                            nodosSeleccionadosBaremos.push(data);

                            cod = cod.split(";");
                            arbolNodo = data.nodeId;
                            optArbol = 1;
                            //$('#tree').treeview('selectNode', [ nodeId, { silent: true } ])
                            document.querySelector("#nombre-plan").innerHTML = "NODO " + cod[1];

                            var nodo = cod[0];
                            var cant = 0;
                            var per = cod[2];
                            var mov = cod[4];

                            document.querySelector("#combox_1_recurso").dataset.cant1 = cod[5];
                            document.querySelector("#combox_1_recurso").dataset.cant2 = cod[6];

                            if(per == null || per == undefined)
                                document.querySelector("#combox_1_recurso").value = 0;
                            else
                                document.querySelector("#combox_1_recurso").value = per;

                            @if($encabezado == null)
                                document.querySelector("#btn-create-actividad").dataset.nodo = nodo;
                                document.querySelector("#btn-create-actividad").dataset.nombre = cod[1];
                            @else
                                @if($encabezado[0]->id_estado == "E1" || $encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE")
                                    document.querySelector("#btn-create-actividad").dataset.nodo = nodo;
                                    document.querySelector("#btn-create-actividad").dataset.nombre = cod[1];
                                @endif
                            @endif
                            
                            
                            document.querySelector("#combox_1_recurso").dataset.nodo = nodo;
                            document.querySelector("#home_recurso_2").style.display = "none";
                            document.querySelector("#home_recurso_1").style.display = "block";
                            
                        }

                        if(tipo == "B")
                        {

                            for (var i = 0; i < nodosSeleccionadosBaremos.length; i++) {
                                $('#treeview9').treeview('unselectNode', [ nodosSeleccionadosBaremos[i].nodeId, { silent: true } ]);
                            }

                            nodosSeleccionadosBaremos = [];

                            cod = cod.split(";");
                            arbolBaremo = data.nodeId;
                            optArbol = 2;

                            
                            var bare = cod[0];
                            var nodo = cod[2];
                            var cant = cod[3];
                            var per = cod[4];
                            var mov = cod[5];

                            var exist = 0;

                            for (var i = 0; i < baremosSeleccionadosMultiples.length; i++) {
                                if(baremosSeleccionadosMultiples[i].nodo != nodo)
                                {
                                    exist = 1;
                                    break;
                                }
                            }

                            if(exist == 1)
                            {
                                for (var i = 0; i < baremosSeleccionadosMultiples.length; i++) {
                                    $('#treeview9').treeview('unselectNode', [ baremosSeleccionadosMultiples[i].datos.nodeId, { silent: true } ]);
                                }
                                baremosSeleccionadosMultiples = [];                                
                            }

                            baremosSeleccionadosMultiples.push(
                                { datos : data,
                                  nodo : nodo
                                });

                            var baremos = "";
                            for (var i = 0; i < baremosSeleccionadosMultiples.length; i++) {
                                
                                baremos += baremosSeleccionadosMultiples[i].datos["href"].split("_")[1].split(";")[0] + ", ";
                            }

                            console.log(baremosSeleccionadosMultiples);

                            


                            if(per == null || per == undefined)
                                document.querySelector("#combox_2_recurso").value = 0;
                            else
                                document.querySelector("#combox_2_recurso").value = per;

                            
                            if(baremosSeleccionadosMultiples.length == 1)
                            {
                                document.querySelector("#nombre-plan").innerHTML = "BAREMO: " + cod[0];
                                document.querySelector("#text_baremo_text").innerHTML = cod[1];
                                document.querySelector("#combox_2_recurso").dataset.tiposave = 1;
                                document.querySelector("#text_cant_baremos").value = cant;
                                document.querySelector("#text_cant_baremos").disabled = false;
                            }
                            else
                            {
                                document.querySelector("#nombre-plan").innerHTML = "BAREMO: " + baremosSeleccionadosMultiples.length + " Baremo/s seleccionado/s";
                                document.querySelector("#text_baremo_text").innerHTML = "Baremos seleccionados: " + baremos;
                                document.querySelector("#combox_2_recurso").dataset.tiposave = 2;
                                document.querySelector("#text_cant_baremos").value = "N/A";
                                document.querySelector("#text_cant_baremos").disabled = true;
                            }

                            document.querySelector("#combox_2_recurso").dataset.nodo = JSON.stringify(baremosSeleccionadosMultiples);
                            
                            document.querySelector("#combox_2_recurso").dataset.baremo = "";
                            
                            
                            document.querySelector("#home_recurso_1").style.display = "none";
                            document.querySelector("#home_recurso_2").style.display = "block";

                        }
                        
                        document.querySelector("#text_filter_cod_1").value = "";
                      }
                });

                $('#treeview9').on('searchComplete', function(event, data) {

                    var array = $.map(data, function(value, index) {
                        return [value];
                    });

                    $('#treeview9').treeview('collapseAll', { silent: true });
                    $('#treeview9').treeview('expandNode', [ 0, { levels: 1, silent: true } ]);
                    
                    for (var i = 0; i < array.length; i++) {
                        $('#treeview9').treeview('expandNode', [ array[i].nodeId, { levels: 1, silent: true } ]);
                        //alert(array[i].nodeId)
                    };

                    if(array.length == 0)
                    {
                         mostrarModal(1,null,"Filtro Actividades Nodos","No existe un nodo, con las catacteristicas del filtro que ingreso.\n",0,"Aceptar","",null);
                    }
                    //alert(JSON.stringify(data.length));
                });

                arreglosTree = $(".node-treeview9");

                var tbl = tbl3.rows('.selected').data();
                var arrayNodos = [];
                for (var i = 0; i < tbl.length; i++) {
                    var tab = tbl[i][0].split("data-ele=")[1].split(";")[1].trim();
                    arrayNodos.push(tab);
                };

                var datos = 
                {
                    opc: 4,
                    nod: arrayNodos,
                    orden :document.querySelector("#id_orden").value,
                    proyecto :document.querySelector("#id_proyect").value
                }

                consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",9,null,2);

        }

        function filtarDatosActividades()
        {
            //https://fullcalendar.io/docs/
            if(document.querySelector("#text_filter_acti").value == "")
            {
                $('#treeview9').treeview('clearSearch');
                $('#treeview9').treeview('collapseAll', { silent: true });
            }
            else
            {
                $('#treeview9').treeview('search', [ 'NODO: ' + document.querySelector("#text_filter_acti").value, {
                              ignoreCase: false,     // case insensitive
                              exactMatch: false,    // like or equals
                              revealResults: true,  // reveal matching nodes
                            }]);
            }

        }

    </script>
    @endif           
       
    </main>

    <script type="text/javascript">

        window.addEventListener('load',iniOrdenesTrabajoProgramado);
        var tbl1;
        var tblDatos;
        var tbl2;


        var lid = null;
        var aux = null;
        var horaI = null;
        var horaF = null;
        var tip = null;

        function eliminaAuxMatri(lider,tipo)
        {
            if(confirm("??Seguro que desea eliminar el auxiliar?"))
            {
                var datos = 
                {
                    opc : 8,
                    lid : lider,
                    tip : tipo
                };   
                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",19);
            }
        }

        function abrirModalDatos(lider,auxX,horaIn,horaFn,tipo)
        {
            $("#tbl_recu_add1").find("tr").remove();
            $("#tbl_recu_add2").find("tr").remove();
            document.querySelector("#text_iden_recurs").innerHTML = "Auxiliar";
            document.querySelector("#consulta_cuadrillero_1").style.display = "none";
            document.querySelector("#consulta_cuadrillero_2").style.display = "none";
            document.querySelector("#consulta_cuadrillero_3").style.display = "none";
            document.querySelector("#consulta_cuadrillero_4").style.display = "none";
            document.querySelector("#consulta_cuadrillero_5").style.display = "block";
            document.querySelector("#consulta_cuadrillero_6").style.display = "none";

            lid = lider;
            aux = auxX;
            horaI = horaIn;
            horaF = horaFn;
            tip = tipo;

            if(tip == 5)
            {
                document.querySelector("#text_iden_recurs").innerHTML = "Matr??cula";
                document.querySelector("#consulta_cuadrillero_0").style.display = "none";
                document.querySelector("#consulta_cuadrillero_6").style.display = "block";
                document.querySelector("#consulta_cuadrillero_5").style.display = "none";
            }
            else
            {
                document.querySelector("#consulta_cuadrillero_0").style.display = "block";   
            }

            $("#modal_add_personas_orden").modal("toggle");
        }

        function deleteUserAsignacion(lider)
        {
            if(confirm("??Seguro que desea eliminar el l??der?"))
            {
                var datos = 
                {
                    opc : 6,
                    lid : lider,
                    orden :document.querySelector("#id_orden").value,
                    proyecto :document.querySelector("#id_proyect").value
                }; 

                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,30000,"POST",13);
            }
        }


        function iniOrdenesTrabajoProgramado()
        {
            @if($encabezado != null)
                @if($encabezado[0]->id_estado == "E1" || $encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE")
                    document.querySelector("#btn-guardar").addEventListener("click",saveDatosOrden);
                @endif
            @else
                document.querySelector("#btn-guardar").addEventListener("click",saveDatosOrden);
            @endif
            document.querySelector("#btn-nuevo").addEventListener("click",function()
                {
                    if(!confirm("??Seguro que desea generar una nueva orden?"))
                    {
                        var evt = this.event || window.event;
                        evt.preventDefault();   
                    }
                });

            document.querySelector("#btn-salir").addEventListener("click",function()
                {
                    if(confirm("??Seguro que desea salir?"))
                    {
                        mostrarSincronizacion();
                        window.location.href = "{{url('/')}}/redes/ordenes/ver";
                    }
                });

            var input = $("input");
            for (var i = 0; i < input.length; i++) {
                if(input[i].dataset.num != null)
                {
                    input[i].addEventListener("keydown",validaIngreso);  
                }
            };   

            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none";

        }

        function saveDatosOrden()
        {


            var tipoT = "";
            for (var i = 0; i < document.querySelector("#select_tipo_trabajo").options.length; i++) {
                    
                    var x = document.querySelector("#select_tipo_trabajo").options[i];
                    tipoT += (x.selected ? 1: 0 ) + ";";
            };

            if(document.querySelector("#fec_emision").value == "" ||
                document.querySelector("#fec_programacion").value == "" ||
                document.querySelector("#fech_ejecucion").value == "" ||
                document.querySelector("#fech_ejecucion_2").value == "" ||
                document.querySelector("#text_solicitante_codensa").value == "" ||
                document.querySelector("#text_solicitante_cam").value == "" ||
                document.querySelector("#text_dire_orden").value == "" ||
                document.querySelector("#text_cd").value == "" ||
                document.querySelector("#text_hora_ini").value == "" ||
                @if($tipoProyecto != "T02")
                    document.querySelector("#text_hora_corte").value == "" ||
                @else
                    document.querySelector("#text_hora_corte").selectedIndex == -1 ||
                @endif
                document.querySelector("#text_hora_cierre").value == "" ||
                document.querySelector("#text_hora_fin").value == "" ||
                document.querySelector("#text_obser_orden").value == "")
                {
                    mostrarModal(1,null,"ManiObra","No se puede guardar la ManiObra, por que tiene campos vacios, que son obligatorios.\n",0,"Aceptar","",null);
                    return;
                }

              if(document.querySelector("#text_solicitante_supervisor").selectedIndex == 0 ||
                document.querySelector("#text_solicitante_supervisor").selectedIndex  == -1 ||
                document.querySelector("#text_solicitante_supervisor").selectedIndex  == "")
            {
                mostrarModal(1,null,"ManiObra","No se puede guardar la ManiObra, por que no ha ingresado el supervisor programador.\n",0,"Aceptar","",null);
                return;
            }

            if(document.querySelector("#text_solicitante_supervisor_ejecutor").selectedIndex == 0 ||
                document.querySelector("#text_solicitante_supervisor_ejecutor").selectedIndex  == -1 ||
                document.querySelector("#text_solicitante_supervisor_ejecutor").selectedIndex  == "")
            {
                mostrarModal(1,null,"ManiObra","No se puede guardar la ManiObra, por que no ha ingresado el supervisor ejecutor.\n",0,"Aceptar","",null);
                return;
            }

            @if($tipoProyecto != "T02")
                if(!comparaFechas(document.querySelector("#text_hora_ini").value,document.querySelector("#text_hora_corte").value))
                {
                    mostrarModal(1,null,"ManiObra","La hora de inicio de la ManiObra, no puede ser mayor a la fecha de corte.\n",0,"Aceptar","",null);
                        return;
                }
            @endif

            @if($tipo != "Obras civiles")
                if(!comparaFechas(document.querySelector("#text_hora_corte").value,document.querySelector("#text_hora_cierre").value))
                {
                    mostrarModal(1,null,"ManiObra","La hora de corte de la ManiObra, no puede ser mayor a la fecha de cierre.\n",0,"Aceptar","",null);
                        return;
                }
            @endif

            if(!comparaFechas(document.querySelector("#text_hora_cierre").value,document.querySelector("#text_hora_fin").value))
            {
                mostrarModal(1,null,"ManiObra","La hora de cierre de la ManiObra, no puede ser mayor a la fecha de fin.\n",0,"Aceptar","",null);
                    return;
            }

            @if($tipo != "Obras civiles")
             if(!comparaFechas(document.querySelector("#text_hora_ini").value,document.querySelector("#text_hora_fin").value))
            {
                mostrarModal(1,null,"ManiObra","La hora de inicio de la ManiObra, no puede ser mayor a la fecha de fin.\n",0,"Aceptar","",null);
                    return;
            }


                @if($tipoProyecto != "T02")
                    if(!comparaFechas(document.querySelector("#text_hora_corte").value,document.querySelector("#text_hora_fin").value))
                    {
                        mostrarModal(1,null,"ManiObra","La hora de corte de la ManiObra, no puede ser mayor a la fecha de fin.\n",0,"Aceptar","",null);
                            return;
                    }
                @endif
            @endif


            //Validar franja horaria
            @if($tipoProyecto == "T02")
                var horaIni = document.querySelector("#text_hora_ini").value;
                var horaCorte = document.querySelector("#text_hora_corte").value;

                var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

                var diasSemana = new Array("Domingo","Lunes","Martes","Mi??rcoles","Jueves","Viernes","S??bado");
                
                var fechaNuevo = document.querySelector("#fech_ejecucion").value;
                fechaNuevo = fechaNuevo.split("/")[2] + "/" + fechaNuevo.split("/")[1] + "/" + fechaNuevo.split("/")[0];

                var f=new Date(fechaNuevo);

                var diaTexto = diasSemana[f.getDay()];


                /* Hora inicio -  D??as Lunes y viernes - M??nimo 09:00
                var horas = horaIni.split(":");
                if(diaTexto == "Lunes" || diaTexto == "Viernes")
                {
                    if(parseInt(horas[0]) < 9)
                    {
                        mostrarModal(1,null,"ManiObra","La hora de inicio de la Maniobra tiene que ser igual ?? mayor a las 9:00am - " + diaTexto + " .\n",0,"Aceptar","",null);
                        return;
                    }
                }

                // Hora inicio -  D??as Martes, Miercoles, Jueves , S??bado y Domingo - M??nimo 08:00
                if(diaTexto == "Domingo" || diaTexto == "Martes" || diaTexto == "Mi??rcoles" || diaTexto == "Jueves" || diaTexto == "S??bado")
                {
                    if(parseInt(horas[0]) < 8)
                    {
                        mostrarModal(1,null,"ManiObra","La hora de inicio de la Maniobra tiene que ser igual ?? mayor a las 8:00am -  " + diaTexto + ".\n",0,"Aceptar","",null);
                        return;
                    }   
                }

                // Hora de corte -  Validar que no existan m??s de una Maniobra de Divisi??n electrica
                // que cumpla las siguientes caracteristicas

                // Lunes - 8:00 - 9:00
                */
            @endif


            if(document.querySelector("#descargo_add_1").value != "0")
            {
                if(document.querySelector("#descargo_add_1").value == document.querySelector("#descargo_add_2").value ||
                document.querySelector("#descargo_add_1").value == document.querySelector("#descargo_add_3").value ||
                document.querySelector("#descargo_add_1").value == document.querySelector("#descargo_add_4").value ||
                document.querySelector("#descargo_add_1").value == document.querySelector("#descargo_add_5").value ||
                document.querySelector("#descargo_add_1").value == document.querySelector("#descargo_add_6").value ||
                document.querySelector("#descargo_add_1").value == document.querySelector("#descargo_add_7").value)
                {
                    mostrarModal(1,null,"ManiObra","Tiene seleccionados descargos repetidos.\n",0,"Aceptar","",null);
                        return;   
                }   
            }
            
            if(document.querySelector("#descargo_add_2").value != "0")
            {
                if(
                document.querySelector("#descargo_add_2").value == document.querySelector("#descargo_add_3").value ||
                document.querySelector("#descargo_add_2").value == document.querySelector("#descargo_add_4").value ||
                document.querySelector("#descargo_add_2").value == document.querySelector("#descargo_add_5").value ||
                document.querySelector("#descargo_add_2").value == document.querySelector("#descargo_add_6").value ||
                document.querySelector("#descargo_add_2").value == document.querySelector("#descargo_add_7").value)
                {
                    mostrarModal(1,null,"ManiObra","Tiene seleccionados descargos repetidos.\n",0,"Aceptar","",null);
                        return;   
                }   
            }

            if(document.querySelector("#descargo_add_3").value != "0")
            {
                if(
                document.querySelector("#descargo_add_3").value == document.querySelector("#descargo_add_4").value ||
                document.querySelector("#descargo_add_3").value == document.querySelector("#descargo_add_5").value ||
                document.querySelector("#descargo_add_3").value == document.querySelector("#descargo_add_6").value ||
                document.querySelector("#descargo_add_3").value == document.querySelector("#descargo_add_7").value)
                {
                    mostrarModal(1,null,"ManiObra","Tiene seleccionados descargos repetidos.\n",0,"Aceptar","",null);
                        return;   
                }   
            }

            if(document.querySelector("#descargo_add_4").value != "0")
            {
                if(
                document.querySelector("#descargo_add_4").value == document.querySelector("#descargo_add_5").value ||
                document.querySelector("#descargo_add_4").value == document.querySelector("#descargo_add_6").value ||
                document.querySelector("#descargo_add_4").value == document.querySelector("#descargo_add_7").value)
                {
                    mostrarModal(1,null,"ManiObra","Tiene seleccionados descargos repetidos.\n",0,"Aceptar","",null);
                        return;   
                }   
            }

            if(document.querySelector("#descargo_add_5").value != "0")
            {
                if(
                document.querySelector("#descargo_add_5").value == document.querySelector("#descargo_add_6").value ||
                document.querySelector("#descargo_add_5").value == document.querySelector("#descargo_add_7").value)
                {
                    mostrarModal(1,null,"ManiObra","Tiene seleccionados descargos repetidos.\n",0,"Aceptar","",null);
                        return;   
                }   
            }

            if(document.querySelector("#descargo_add_6").value != "0")
            {
                if(
                document.querySelector("#descargo_add_6").value == document.querySelector("#descargo_add_7").value)
                {
                    mostrarModal(1,null,"ManiObra","Tiene seleccionados descargos repetidos.\n",0,"Aceptar","",null);
                        return;   
                }   
            }


            var Fecha_aux1 = document.getElementById("fech_ejecucion").value.split("/");
            var Fecha1 = new Date(parseInt(Fecha_aux1[2]),parseInt(Fecha_aux1[1]-1),parseInt(Fecha_aux1[0]));


            var Fecha_aux2 = document.getElementById("fech_ejecucion_2").value.split("/");
            var Fecha2 = new Date(parseInt(Fecha_aux2[2]),parseInt(Fecha_aux2[1]-1),parseInt(Fecha_aux2[0]));


            /*if (Fecha2 < Fecha1){
                alert ("La fecha introducida es anterior a Hoy");
            }
            else{
                if (Fecha1 == Hoy){
                    alert ("Has introducido la fecha de Hoy");
                }
                else{
                    alert ("La fecha introducida es posterior a Hoy");
                }
            }*/

            
                if (Fecha2 < Fecha1){
                    mostrarModal(1,null,"ManiObra","La fecha de fin de ejecuci??n de la Maniobra, no puede ser menor a la fecha de inicio de ejecuci??n\n",0,"Aceptar","",null);
                    return;  
                }



            var datos = 
            {
                opc : 0,
                fechEm : document.querySelector("#fec_emision").value,
                fechPr : document.querySelector("#fec_programacion").value,
                fechEj : document.querySelector("#fech_ejecucion").value,
                fechEj2 : document.querySelector("#fech_ejecucion_2").value,
                soliC : document.querySelector("#text_solicitante_codensa").value,
                soliCAM : document.querySelector("#text_solicitante_cam").value,
                soliSup : document.querySelector("#text_solicitante_supervisor").options[document.querySelector("#text_solicitante_supervisor").selectedIndex].text,
                supervisor_programador_cedula : document.querySelector("#text_solicitante_supervisor").value,
                soliSupEje : document.querySelector("#text_solicitante_supervisor_ejecutor").options[document.querySelector("#text_solicitante_supervisor_ejecutor").selectedIndex].text, //Cedula
                soliSupEjeCedula : document.querySelector("#text_solicitante_supervisor_ejecutor").value, //Cedula
                gestor_materiales : document.querySelector("#text_gestor_materiales").value,
                gestor_descargos : document.querySelector("#text_gestor_descargos").value,
                dire : document.querySelector("#text_dire_orden").value,
                tipo : tipoT,
                cd : document.querySelector("#text_cd").value,
                nivel : document.querySelector("#select_nivel_t").value,
                horaI : document.querySelector("#text_hora_ini").value,
                horaC : document.querySelector("#text_hora_corte").value,
                horaCi : document.querySelector("#text_hora_cierre").value,
                horaF : document.querySelector("#text_hora_fin").value,
                obser : document.querySelector("#text_obser_orden").value,
                gomA : document.querySelector("#text_gom_adec").value,
                gomI : document.querySelector("#text_gom_inst").value,
                facO : document.querySelector("#text_fac_orden").value,
                radO : document.querySelector("#text_rad_oren").value,
                proY : document.querySelector("#text_proy").value,
                des1 : document.querySelector("#descargo_add_1").value,
                des2 : document.querySelector("#descargo_add_2").value,
                des3 : document.querySelector("#descargo_add_3").value,
                des4 : document.querySelector("#descargo_add_4").value,
                des5 : document.querySelector("#descargo_add_5").value,
                des6 : document.querySelector("#descargo_add_6").value,
                des7 : document.querySelector("#descargo_add_7").value,
                gps : document.querySelector("#txt_gps_descargo").value,
                pry : document.querySelector("#id_proyect").value,
                orden : document.querySelector("#id_orden").value,
                tipo_trabajo: document.querySelector("#tipo_trabajo").value
            };

            consultaAjax("{{url('/')}}/guardarOrdenProgramados",datos,20000,"POST",1);
        }

        function comparaFechas(hora1,hora2)
        {

            var hora1F = hora1.trim().split(":");
            var hora2F = hora2.trim().split(":");   

            if(parseInt(hora1F[0]) > parseInt(hora2F[0]))
            {
                return false;
            }
            else
            {
                if(parseInt(hora1F[0]) == parseInt(hora2F[0]))
                {
                    if(parseInt(hora1F[1]) > parseInt(hora2F[1]))
                    {
                        return false;
                    }
                    else
                    {
                        return true;
                    }
                }
                else
                {
                    return true;
                }
            }
        }

        //FILTER DATOS
        function verClasificacion()
        {
            $('#treeview1').treeview('expandAll', { levels: 2, silent: true });
            var nodos = $(".node-treeview1");
            if(document.querySelector("#clasi_dato").value == "0")
            {
                for (var i = 0; i < nodos.length; i++) {
                    if (nodos[i].innerHTML.indexOf('ORDEN: ') == -1 && nodos[i].innerHTML.indexOf('NODO: ') == -1)
                        nodos[i].style.display = "block";   
                };
                /*if (cadena.indexOf('garcia')!=-1) {
                //tu codigo
                }*/
            }
            else
            {
                for (var i = 0; i < nodos.length; i++) {
                    if (nodos[i].innerHTML.indexOf('ORDEN: ') == -1 && nodos[i].innerHTML.indexOf('NODO: ') == -1)
                    {
                        var tipoHerramienta = nodos[i].children[8].innerHTML;
                        if(tipoHerramienta.indexOf(document.querySelector("#clasi_dato").value) == -1)
                            nodos[i].style.display = "none";
                        else
                            nodos[i].style.display = "block";
                        //console.log(nodos[i].innerHTML);
                    }
                };
            }
        }

        function filtrarCodigo(opc)
        {
            if(opc == "1")
            {
                $('#treeview9').treeview('expandAll', { levels: 2, silent: true });
                var nodos = $(".node-treeview9");
                if(document.querySelector("#text_filter_cod_1").value == "")
                {
                    for (var i = 0; i < nodos.length; i++) {
                        if (nodos[i].innerHTML.indexOf('ORDEN: ') == -1 && nodos[i].innerHTML.indexOf('NODO: ') == -1)
                            nodos[i].style.display = "block";   
                    };
                }
                else
                {
                    for (var i = 0; i < nodos.length; i++) {
                        if (nodos[i].innerHTML.indexOf('ORDEN: ') == -1 && nodos[i].innerHTML.indexOf('NODO: ') == -1)
                        {
                            if(nodos[i].outerHTML.indexOf(document.querySelector("#text_filter_cod_1").value) == -1)
                                nodos[i].style.display = "none";
                            else
                                nodos[i].style.display = "block";
                        }
                    };        
                }
                //text_filter_cod_1
            }else{
                $('#treeview1').treeview('expandAll', { levels: 2, silent: true });
                var nodos = $(".node-treeview1");
                if(document.querySelector("#text_filter_cod_2").value == "")
                {
                    for (var i = 0; i < nodos.length; i++) {
                        if (nodos[i].innerHTML.indexOf('ORDEN: ') == -1 && nodos[i].innerHTML.indexOf('NODO: ') == -1)
                            nodos[i].style.display = "block";   
                    };
                }
                else
                {
                    for (var i = 0; i < nodos.length; i++) {
                        if (nodos[i].innerHTML.indexOf('ORDEN: ') == -1 && nodos[i].innerHTML.indexOf('NODO: ') == -1)
                        {
                            if(nodos[i].outerHTML.indexOf(document.querySelector("#text_filter_cod_2").value) == -1)
                                nodos[i].style.display = "none";
                            else
                                nodos[i].style.display = "block";
                        }
                    };        
                }
            }
            

            
            //text_filter_cod_2

        }

//sssss
        function consultaAjax(route,datos,tiempoEspera,type,opcion,collback,dato)
        {
            if(dato != -1)
                mostrarSincronizacion();

            $.ajax({
                url: route,
                type: type,
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:datos,
               // timeout:tiempoEspera,
                success:function(data)
                {
                    if(opcion == 1)
                    {


                        if(data == 2)
                        {
                            mostrarModal(2,null,"ORDEN","Se actualizado correctamente la orden.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == -7)
                        {
                            mostrarModal(1,null,"ORDEN","No puede guardar la ManiObra, por que ya existen m??s de 3 ordenes con la misma hora de corte .\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == -5)
                        {
                            mostrarModal(1,null,"ORDEN","No se puede modificar la fecha de inicio, por que tiene actividades ejecutadas entre el rango de la fecha de inicio modificada.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == -6)
                        {
                            mostrarModal(1,null,"ORDEN","No se puede modificar la fecha de fin, por que tiene actividades ejecutadas entre el rango de la fecha de fin modificada.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        var str = data;
                        var n = str.indexOf("OT0");

                        if(n != -1)
                        {
                            mostrarModal(2,null,"ORDEN","Se creado correctamente la orden.\n",0,"Aceptar","",null);
                            window.location.href = "{{url('/')}}/redes/ordenes/ordentrabajo/{{$proyecto}}/" + data;
                        }
                        else
                        {
                            if(data.length > 4)
                            {
                                mostrarModal(1,null,"Consulta CD","Uno ?? varios de los CD, PF ?? SECCIONADOR que ingreso tuvo una intervenci??n menor a 9 d??as, tiene problemas con los siguientes datos: \n" + data,0,"Aceptar","",null);
                                document.querySelector("#text_cd").value = "";
                                ocultarSincronizacion();
                                return;
                            }
                        }                        
                    }

                    if(opcion == 2)
                    {
                        //crearArbolMateriales(data);
                    }

                    if(opcion == 3)
                    {
                        if(dato == "1")
                        {
                            var html = "";
                            html += "<option value=''>Todos";
                            html += "</option>";

                            for (var i = 0; i < data.length; i++) {
                                
                                html += "<option value='" + data[i].lider + "' data-movil='" + data[i].movil + "' data-tipo='" + data[i].tipo + "' data-lid='" + data[i].lider + "' data-nombre='" + data[i].nom1 + "' data-aux1='" + data[i].aux1 + "' data-aux2='" + data[i].aux2 + "' data-aux3='" + data[i].aux3 + "' data-cond='" + data[i].cond + "' data-matri='" + data[i].matri + "' data-nom2='" + data[i].nom2 + "' data-nom3='" + data[i].nom3 + "' data-nom4='" + data[i].nom4 + "' data-nom5='" + data[i].nom5 + "' data-disp1='" + data[i].disp1 + "' data-disp2='" + data[i].disp2 + "' data-disp3='" + data[i].disp3 + "' data-disp4='" + data[i].disp4 + "' data-dispMovil='" + data[i].dispMovil + "' data-tipoC='" + data[i].tipoC + "'>" + data[i].nom1 + " - " +  data[i].tipo   + " - " + data[i].movil;
                                html += "</option>";
                            };
                            
                            $("#text_iden_recurso").html(html);
                            
                            ocultarSincronizacion();
                        }
                        else
                        {
                            var html = "";
                            for (var i = 0; i < data.length; i++) {
                                
                                html += "<tr>";
                                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarTablaRecurso(this)' data-movil='" + data[i].movil + "' data-tipo='" + data[i].tipo + "' data-lid='" + data[i].lider + "' data-nombre='" + data[i].nom1 + "' data-aux1='" + data[i].aux1 + "' data-aux2='" + data[i].aux2 + "' data-aux3='" + data[i].aux3 + "' data-cond='" + data[i].cond + "' data-matri='" + data[i].matri + "' data-nom2='" + data[i].nom2 + "' data-nom3='" + data[i].nom3 + "' data-nom4='" + data[i].nom4 + "' data-nom5='" + data[i].nom5 + "' data-disp1='" + data[i].disp1 + "' data-disp2='" + data[i].disp2 + "' data-disp3='" + data[i].disp3 + "' data-disp4='" + data[i].disp4 + "' data-dispMovil='" + data[i].dispMovil + "' data-tipoC='" + data[i].tipoC + "'></i></td>";
                                html += "<td>" + data[i].movil + "</td>";
                                html += "<td>" + data[i].tipo + "</td>";
                                html += "<td>" + data[i].lider + "</td>";
                                html += "<td>" + data[i].nom1 + "</td>";
                                html += "</tr>";
                            };
                            
                            $("#tbl_recu_add").html(html);
                        }
                        
                        ocultarSincronizacion();
                    }

                    if(opcion == 4) //Agregar usuario
                    {
                        mostrarModal(2,null,"ManiObra","Se ha agregado correctamente el recurso a la ManiObra.\n",0,"Aceptar","",null);
                        window.location.reload();
                
                    }

                    if(opcion == 5) //Save Nodos Asignado
                    {
                        //window.location.reload();
                    }

                    if(opcion == 6) //Sabe Nodos y Baremos
                    {
                        window.location.reload();
                    }

                    if(opcion == 7) //Nuevo elemento
                    {
                        if(data == "-2")
                        {
                            mostrarModal(1,null,"Ejecuci??n orden","No se puede programar la orden, por que no ha asociado actividades o materiales a la maniobra.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data.res == "-1")
                        {
                            var html = "";
                            for (var i = 0; i < data.restriccion.length; i++) {
                                html += " - <b>TIPO:</b> " + data.restriccion[i].tipoR + "   <b>DESCRIPCI??N:</b> " + data.restriccion[i].restriccion_descripcion + "     <b>FECHA L??MITE:</b>" + data.restriccion[i].fecha_limite.split(" ")[0] + "       <b>RESPONSABLE:</b>" + data.restriccion[i].responsable + "<br>"; 
                            };


                            mostrarModal(1,null,"Ejecuci??n orden","No se puede programar la orden, por que las siguiente restricciones son obligatorias y no han sido levantadas <br>\n " + html,0,"Aceptar","",null);
                            
                            ocultarSincronizacion();
                            window.location.reload();   
                            return;
                        }

                        mostrarModal(2,null,"Ejecuci??n orden","Se ha programado correctamente la ManiObra.\n",0,"Aceptar","",null);
                        window.location.reload();   
                    }

                    if(opcion == 8) //Consutla Actividades
                    {/////
                        var arrayNodos = [];

                        var arrayNodosIngresado = [];
                        for(var i = 0; i < data.length ; i++)
                        {
                            for(var j = 0; j < data[i].length ; j++)
                            { //Consultar si dentro de cada nodo, hay solo una persona a cargo

                                //

                                var auxNodosI = 0;
                                for (var k = 0; k < arrayNodosIngresado.length; k++) {
                                    if(arrayNodosIngresado[k] == data[i][j].nod)
                                    {
                                        auxNodosI = 1;
                                        break;
                                    }
                                }

                                if(auxNodosI == 0)
                                {
                                    arrayNodosIngresado.push(data[i][j].nod);

                                    var arrayBaremos = [];
                                    //Consultar Baremos por cada NODO
                                    var auxCont = 0;
                                    var auxTotal = 0;
                                    for(var l = 0; l < data.length ; l++)
                                    {
                                        for(var m = 0; m < data[l].length ; m++)
                                        {
                                            if(data[i][j].nod == data[l][m].nod)
                                            {
                                                var cantidad = 0;
                                                if(data[l][m].cantidad_confirmada != "0" && data[l][m].cantidad_confirmada != null && data[l][m].cantidad_confirmada != ".00")
                                                    cantidad = data[l][m].cantidad_confirmada.replace(".00","");
                                                else
                                                    cantidad = (data[l][m].cant == null ? 0 : data[l][m].cant.replace(".00",""));

                                                var cant_otra_ot = (data[l][m].cant_otra_ot.replace(".00","") == "" ? "0" : data[l][m].cant_otra_ot.replace(".00",""));
                                                var cant_eje = (data[l][m].cant_eje.replace(".00","") == "" ? "0" : data[l][m].cant_eje.replace(".00",""));

                                                if(cant_eje == "")
                                                    cant_eje = 0;

                                                if(cant_otra_ot == "")
                                                    cant_otra_ot = 0;

                                                console.log(cant_eje + "-"  + cant_otra_ot);

                                                var obser = "";
                                                if(data[l][m].id_personaCargo != undefined && data[l][m].id_personaCargo != null)
                                                {
                                                    auxCont++;
                                                    var color = "blue";
                                                    if(data[l][m].tipoI == "1")
                                                        color = "green";

                                                    var cantAux = data[l][m].cant;
                                                    if(cantAux == null || cantAux == undefined || cantAux == "") 
                                                        cantAux = 0;
                                                    else
                                                        cantAux = cantAux.replace(".00","");

                                                    if(cantAux == "")
                                                        cantAux = 0;

                                                    var cantidadPro = data[l][m].cantidad_confirmada.replace(".00","");

                                                    if(cantidadPro == "")
                                                        cantidadPro = 0;

                                                    arrayBaremos.push(
                                                    {
                                                        text: data[l][m].id_baremo + " - " +  data[l][m].actividad.slice(0,40),
                                                        href: "#B_" + data[l][m].id_baremo + ";" + data[l][m].actividad + ";" + data[l][m].id_nodo + ";" +  cantidad + ";" + data[l][m].id_personaCargo, 
                                                        icon: 'glyphicon glyphicon-ok' ,
                                                        tags : [cant_eje,cant_otra_ot,cantidadPro,cantAux,""],
                                                        color: color,
                                                    }); 
                                                }
                                                else
                                                {
                                                    if(cantidad == "")
                                                        cantidad = "0";
                                                    arrayBaremos.push(
                                                    {
                                                        text: data[l][m].id_baremo + " - " +  data[l][m].actividad.slice(0,40),
                                                        href: "#B_" + data[l][m].id_baremo + ";" + data[l][m].actividad + ";" + data[l][m].id_nodo + ";" +  cantidad + ";" + data[l][m].id_personaCargo, 
                                                        icon: 'glyphicon glyphicon-ok' ,
                                                        tags : [cant_eje,cant_otra_ot,"0",cantidad,""],
                                                        color: "#a1a1c6",
                                                    }); 
                                                }
                                                auxTotal++;
                                                    
                                                
                                            }
                                        }

                                    }

                                    arrayNodos.push(
                                    {
                                        text: "NODO: " + data[i][j].nod,
                                        href: "#N_" + data[i][j].id_nodo + ";" + data[i][j].nod + ";" + data[i][j].id_personaCargo + ";" + data[i][j].nombre + ";" + data[i][j].id_movil + ";" + auxCont + ";" + auxTotal,
                                        icon: 'glyphicon glyphicon-map-marker',
                                        nodes: arrayBaremos,
                                    });       
                                }
                                
                            }
                        }

                        var alternateData = [
                                  {
                                    text: "ORDEN: " + document.querySelector("#id_orden").value,
                                    href: "#O-" + document.querySelector("#id_orden").value,
                                    nodes: arrayNodos,
                                    icon: 'glyphicon glyphicon-lock'
                                  }
                                ];

                        crearArbolJS(alternateData);

                        if(dato != 2)
                            $("#modal_add_nodos_orden").modal("toggle");
                    }

                    if(opcion == 9) // Consulta Materiales
                    {
                        var arrayNodos = [];

                        var arrayNodosIngresado = [];
                        for(var i = 0; i < data.length ; i++)
                        {
                            for(var j = 0; j < data[i].length ; j++)
                            { //Consultar si dentro de cada nodo, hay solo una persona a cargo

                                //

                                var auxNodosI = 0;
                                for (var k = 0; k < arrayNodosIngresado.length; k++) {
                                    if(arrayNodosIngresado[k] == data[i][j].nod)
                                    {
                                        auxNodosI = 1;
                                        break;
                                    }
                                }

                                if(auxNodosI == 0)
                                {
                                    arrayNodosIngresado.push(data[i][j].nod);

                                    var arrayBaremos = [];
                                    var auxCont = 0;
                                    var auxTotal = 0;
                                    //Consultar Baremos por cada NODO
                                    for(var l = 0; l < data.length ; l++)
                                    {
                                        for(var m = 0; m < data[l].length ; m++)
                                        {
                                            if(data[i][j].nod == data[l][m].nod)
                                            {
                                                var cantidad = 0;
                                                if(data[l][m].cantidad_confirmada != "0" && data[l][m].cantidad_confirmada != null && data[l][m].cantidad_confirmada != ".00")
                                                    cantidad = data[l][m].cantidad_confirmada.replace(".00","");
                                                else
                                                    cantidad = (data[l][m].cant == null ? 0 : data[l][m].cant.replace(".00",""));

                                                var obser = "";

                                                var clas = "H"; // CL19
                                                //console.log(data[l][m].id_clasificacion);
                                                if(data[l][m].id_clasificacion == "CL14")
                                                    clas = "P";

                                                if(data[l][m].id_clasificacion == "CL22")
                                                    clas = "E";

                                                var cant_otra_ot = (data[l][m].cant_otra_ot.replace(".00","") == "" ? "0" : data[l][m].cant_otra_ot.replace(".00",""));
                                                var cant_eje = (data[l][m].cant_eje.replace(".00","") == "" ? "0" : data[l][m].cant_eje.replace(".00",""));

                                                if(data[l][m].id_personaCargo != undefined && data[l][m].id_personaCargo != null)
                                                {

                                                    var color = "blue";
                                                    if(data[l][m].tipoI == "1")
                                                        color = "green";

                                                    var cantAux = data[l][m].cant;
                                                    if(cantAux == null || cantAux == undefined) 
                                                        cantAux = 0;
                                                    else
                                                        cantAux = cantAux.replace(".00","");
                                                    
                                                    arrayBaremos.push(
                                                    {
                                                        text: data[l][m].codigo_sap + " - " +  data[l][m].nombreM.slice(0,32),
                                                        href: "#B_" + data[l][m].id_articulo + ";" + data[l][m].nombreM + ";" + data[l][m].id_nodo + ";" +  cantidad + ";" + data[l][m].id_personaCargo, 
                                                        icon: 'glyphicon glyphicon-ok' ,
                                                        tags :[cant_eje,cant_otra_ot,data[l][m].cantidad_confirmada.replace(".00",""),cantAux,clas],
                                                        color: color,
                                                    }); 
                                                    auxCont++;
                                                }
                                                else
                                                {
                                                    if(cantidad == "")
                                                        cantidad = 0;

                                                    arrayBaremos.push(
                                                    {
                                                        text: data[l][m].codigo_sap + " - " +  data[l][m].nombreM.slice(0,32),
                                                        href: "#B_" + data[l][m].id_articulo + ";" + data[l][m].nombreM + ";" + data[l][m].id_nodo + ";" +  cantidad + ";" + data[l][m].id_personaCargo, 
                                                        icon: 'glyphicon glyphicon-ok' ,
                                                        tags : [cant_eje,cant_otra_ot,"0",cantidad,clas],
                                                        color: "#a1a1c6",
                                                    }); 
                                                }
                                                auxTotal++;
                                                    
                                                
                                            }
                                        }

                                    }

                                    arrayNodos.push(
                                    {
                                        text: "NODO: " + data[i][j].nod,
                                        href: "#N_" + data[i][j].id_nodo + ";" + data[i][j].nod + ";" + data[i][j].id_personaCargo + ";" + data[i][j].nombre + ";" + data[i][j].id_movil + ";" + auxCont + ";" + auxTotal,
                                        icon: 'glyphicon glyphicon-map-marker',
                                        nodes: arrayBaremos,
                                    });       
                                }
                                
                            }
                        }

                        var alternateData = [
                                  {
                                    text: "ORDEN: " + document.querySelector("#id_orden").value,
                                    href: "#O-" + document.querySelector("#id_orden").value,
                                    nodes: arrayNodos,
                                    icon: 'glyphicon glyphicon-lock'
                                  }
                                ];

                        crearArbolJSMate(alternateData);

                        if(dato != 2)
                            $("#modal_add_nodos_orden").modal("toggle");
                    }

                    if(opcion == 10) //Save Nodos Materiales Asignado
                    {
                        window.location.reload();
                    }

                    if(opcion == 11) //Sabe Nodos y Actividades
                    {   
                       window.location.reload();
                    }

                    if(opcion == 12) // Agregar usuarios
                    {   
                        if(data == "-1")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Cambio de l??der","No puede hacer el cambio del l??der, por que el l??der actual tiene DC confirmados.\n",0,"Aceptar","",null);
                            return;
                        }
                        
                        if(data == "-2")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Cambio de l??der","No puede hacer el cambio del l??der, por que el l??der actual tiene ejecuci??n de actividades.\n",0,"Aceptar","",null);
                            return;
                        }

                        window.location.reload();
                       
                    }

                    if(opcion == 13)
                    {
                        if(data == "1")
                        {
                            mostrarModal(2,null,"ManiObra","Se ha eliminado correctamente el recurso de la ManiObra.\n",0,"Aceptar","",null);
                            window.location.reload(); 
                        }

                        if(data == "-1")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"ManiObra","No se puede eliminar el l??der, por que tiene asociados uno o varios  DC con estado confirmado.\n",0,"Aceptar","",null);
                            return;
                        }

                        if(data == "-2")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"ManiObra","No puede eliminar al l??der.\n",0,"Aceptar","",null);
                            return;
                        }

                        if(data == "-3")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"ManiObra","No puede eliminar al l??der, por que tiene ejecuci??n de actividades.\n",0,"Aceptar","",null);
                            return;
                        }

                    }

                    if(opcion == 14) //Update Recurso Baremo a baremo                
                    {
                        if(data == "1")
                        {
                            
                            var nodos = document.querySelector("#combox_2_recurso").dataset.nodo;
                            nodos = JSON.parse(nodos);

                            if(document.querySelector("#combox_2_recurso").selectedIndex != 0)
                            {
                                

                                for (var i = 0; i < nodos.length; i++) {
                                    
                                    var nodes = $("#treeview9").treeview('getNode', nodos[i].datos.nodeId); 
                                    nodes.color = "blue";
                                    var tg = nodes.tags;
                                    if(nodos.length == 1)
                                        nodes.tags = [tg[0],tg[1],document.querySelector("#text_cant_baremos").value,tg[3],tg[4]];
                                    else
                                        nodes.tags = [tg[0],tg[1],tg[3],tg[3],tg[4]];

                                    var dat = nodes.href.split(";");
                                    if(nodos.length == 1)
                                        dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + document.querySelector("#text_cant_baremos").value + ";" + document.querySelector("#combox_2_recurso").value;
                                    else
                                        dat = dat[0] + ";" + dat[1] + ";" + dat[3] + ";" + dat[3] + ";" + document.querySelector("#combox_2_recurso").value;

                                    nodes.href = dat;
                                }
                            }
                            else
                            {
                                for (var i = 0; i < nodos.length; i++) {

                                    var nodes = $("#treeview9").treeview('getNode', nodos[i].datos.nodeId); 
                                    nodes.color = "#a1a1c6";
                                    var tg = nodes.tags;
                                    nodes.tags = [tg[0],tg[1],"0",tg[3],tg[4]];
                                    var dat = nodes.href.split(";");
                                    dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + tg[3] + ";" + "0";
                                    nodes.href = dat; 
                                }
                                
                            }
                            

                            $('#treeview9').treeview('selectNode', [ dato, { silent: true } ]);


                            ocultarSincronizacion();
                        }

                        consultaPorcentaProduccion();
                    }

                    if(opcion == 15) // Update todos los nodos
                    {
                        if(opcGuardar == 1)
                        {
                            for (var i = 0; i < nodosSelecArray.length; i++) {
                                var nodo = nodosSelecArray[i]; //Nodos hijos
                                if(opcionNodo1 == 1 || opcionNodo1 == 2) //El nodo se le asigna a una persona ; //Asignar todo a una persona
                                {
                                    nodo.color = "blue";
                                    var tg = nodo.tags;
                                    nodo.tags = [tg[0],tg[1],tg[3],tg[3],tg[4]];
                                    var dat = nodo.href.split(";");
                                    dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + tg[3] + ";" + persoaCargo;
                                    nodo.href = dat;
                                    var nodoP = $("#treeview9").treeview('getNode', arbolNodo); 
                                    var dat = nodoP.href.split(";");
                                    nodoP.href = dat[0] + ";" + dat[1] + ";" + persoaCargo + ";" + dat[3] + ";" + dat[4] + ";" + dat[5] + ";" + dat[6];
                                 }

                                if(opcionNodo1 == 3) //Asignar solo vacios
                                {   
                                    if(nodo.color != "blue")
                                    {
                                        nodo.color = "blue";
                                        var tg = nodo.tags;
                                        nodo.tags = [tg[0],tg[1],tg[3],tg[3],tg[4]];
                                        var dat = nodo.href.split(";");
                                        dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + tg[3] + ";" + persoaCargo;
                                        nodo.href = dat;
                                        var nodoP = $("#treeview9").treeview('getNode', arbolNodo); 
                                        var dat = nodoP.href.split(";");
                                        nodoP.href = dat[0] + ";" + dat[1] + ";" + persoaCargo + ";" + dat[3] + ";" + dat[4] + ";" + dat[5] + ";" + dat[6];
                                    }
                                }

                                if(opcionNodo1 == 4) //Desasignar todo el persona
                                {
                                    nodo.color = "#a1a1c6";
                                    var tg = nodo.tags;
                                    nodo.tags = [tg[0],tg[1],"0",tg[3],tg[4]];
                                    var dat = nodo.href.split(";");
                                    dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + tg[3] + ";" + null;
                                    nodo.href = dat;

                                    var nodoP = $("#treeview9").treeview('getNode', arbolNodo); 
                                    var dat = nodoP.href.split(";");
                                    nodoP.href = dat[0] + ";" + dat[1] + ";0;" + null + ";" + dat[4] + ";" + dat[5] + ";" + dat[6];
                                }
                            };

                            for (var i = 0; i < nodosSeleccionadosBaremos.length; i++) {
                                $('#treeview9').treeview('unselectNode', [ nodosSeleccionadosBaremos[i].nodeId, { silent: true } ]);
                            }

                            nodosSeleccionadosBaremos = [];

                             $('#treeview9').treeview('selectNode', [ 1, { silent: true } ]);
                        }else{
                            for (var i = 0; i < nodosSelecArray1.length; i++) {

                                var nodo = nodosSelecArray1[i]; //Nodos hijos
                                if(document.querySelector("#clasi_dato").value != "0")
                                {
                                    var tags_1 = nodo.tags; 
                                    if(tags_1[4] != document.querySelector("#clasi_dato").value) 
                                        continue;
                                }

                                
                                if(opcionNodo1 == 5 || opcionNodo1 == 6) //El nodo se le asigna a una persona ; //Asignar todo a una persona
                                {
                                    nodo.color = "blue";
                                    var tg = nodo.tags;
                                    nodo.tags = [tg[0],tg[1],tg[3],tg[3],tg[4]];
                                    var dat = nodo.href.split(";");
                                    dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + tg[3] + ";" + persoaCargo;
                                    nodo.href = dat;
                                    var nodoP = $("#treeview1").treeview('getNode', arbolNodoM); 
                                    var dat = nodoP.href.split(";");
                                    nodoP.href = dat[0] + ";" + dat[1] + ";" + persoaCargo + ";" + dat[3] + ";" + dat[4] + ";" + dat[5] + ";" + dat[6];
                                 }

                                if(opcionNodo1 == 7) //Asignar solo vacios
                                {   
                                    if(nodo.color != "blue")
                                    {
                                        nodo.color = "blue";
                                        var tg = nodo.tags;
                                        nodo.tags = [tg[0],tg[1],tg[3],tg[3],tg[4]];
                                        var dat = nodo.href.split(";");
                                        dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + tg[3] + ";" + persoaCargo;
                                        nodo.href = dat;
                                        var nodoP = $("#treeview1").treeview('getNode', arbolNodoM); 
                                        var dat = nodoP.href.split(";");
                                        nodoP.href = dat[0] + ";" + dat[1] + ";" + persoaCargo + ";" + dat[3] + ";" + dat[4] + ";" + dat[5] + ";" + dat[6];
                                    }
                                }

                                if(opcionNodo1 == 8) //Desasignar todo el persona
                                {
                                    nodo.color = "#a1a1c6";
                                    var tg = nodo.tags;
                                    nodo.tags = [tg[0],tg[1],"0",tg[3],tg[4]];
                                    var dat = nodo.href.split(";");
                                    dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + tg[3] + ";" + null;
                                    nodo.href = dat;

                                    var nodoP = $("#treeview1").treeview('getNode', arbolNodoM); 
                                    var dat = nodoP.href.split(";");
                                    nodoP.href = dat[0] + ";" + dat[1] + ";0;" + null + ";" + dat[4] + ";" + dat[5] + ";" + dat[6];
                                }
                            }; 

                            for (var i = 0; i < nodosSeleccionadosBaremos.length; i++) {
                                $('#treeview9').treeview('unselectNode', [ nodosSeleccionadosBaremos[i].nodeId, { silent: true } ]);
                            }

                            nodosSeleccionadosBaremos = [];

                             $('#treeview1').treeview('selectNode', [ 1, { silent: true } ]);
                        }
                       

                       
                        ocultarSincronizacion();
                        consultaPorcentaProduccion();
                    }

                    if(opcion == 16) // Update Material Nodo a Nodo
                    {
                        if(data == "1")
                        {
                            var nodes = $("#treeview1").treeview('getNode', dato); 
                            if(document.querySelector("#combox_2_materiales").selectedIndex != 0)
                            {
                                nodes.color = "blue";
                                var tg = nodes.tags;
                                nodes.tags = [tg[0],tg[1],document.querySelector("#text_cant_materiales").value,tg[3],tg[4]];
                                var dat = nodes.href.split(";");
                                dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + document.querySelector("#text_cant_materiales").value + ";" + document.querySelector("#combox_2_materiales").value;
                                nodes.href = dat;
                            }
                            else
                            {
                               nodes.color = "#a1a1c6";
                                var tg = nodes.tags;
                                nodes.tags = [tg[0],tg[1],"0",tg[3],tg[4]];
                                var dat = nodes.href.split(";");
                                dat = dat[0] + ";" + dat[1] + ";" + dat[2] + ";" + tg[3] + ";" + "0";
                                nodes.href = dat; 
                            }
                            

                            $('#treeview1').treeview('selectNode', [ dato, { silent: true } ]);
                            ocultarSincronizacion();
                        }

                        consultaPorcentaProduccion();
                    }
                     
                    if(opcion == 17) // Update Material todos los nodos
                    {

                    }  

                    if(opcion == 18) // Consulta usuarios Aux
                    {
                        if(dato == 5 || dato == "5")
                        {
                            var html = "";
                            for (var i = 0; i < data.length; i++) {
                                html += "<tr>";
                                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarTablaRecursoMatri(this)' data-cod='" + data[i].codigo + "'></i></td>";
                                html += "<td>" + data[i].codigo + "</td>";
                                html += "</tr>";
                            };
                            
                            $("#tbl_recu_add2").html(html);
                        }else{
                            var html = "";
                            for (var i = 0; i < data.length; i++) {
                                
                                html += "<tr>";
                                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarTablaRecursoAux(this)' data-cod='" + data[i].codigo + "' data-nombre='" + data[i].nombre + "'></i></td>";

                                html += "<td>" + data[i].codigo + "</td>";
                                html += "<td>" + data[i].nombre + "</td>";
                                html += "</tr>";
                            };
                            
                            $("#tbl_recu_add1").html(html);
                        }
                        ocultarSincronizacion();
                    } 

                    if(opcion == 19) //Se actualiza el auxiliar o matricula
                    {
                        window.location.reload();
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

                    if(opcion == 21) //Carga Materiales
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

                    if(opcion == 22) //ADD Actividades / Materiales
                    {
                        if(data == "0")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Agregar Baremo","Usted ya tiene el baremo agregado al nodo seleccionado.\n",0,"Aceptar","",null);
                            return;
                        }

                        if(data == "2")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Agregar Material","Usted ya tiene el material agregado al nodo seleccionado.\n",0,"Aceptar","",null);
                            return;
                        }

                        if(data == "3")
                        {
                            ocultarSincronizacion();
                            $("#modal_acti").modal("toggle");
                            mostrarModal(2,null,"Agregar Baremo","Se ha agregado correctamente el baremo al nodo seleccionado.\n",0,"Aceptar","",null);


                        }

                        if(data == "1")
                        {
                            window.location.reload();
                            ocultarSincronizacion();
                            
                            $("#modal_acti").modal("toggle");
                            mostrarModal(2,null,"Agregar Baremo","Se ha agregado correctamente el baremo al nodo seleccionado.\n",0,"Aceptar","",null);

                        }


                        if(data == "4") 
                        {
                            ocultarSincronizacion();
                            mostrarModal(2,null,"Agregar Material","Se ha agregado correctamente el material al nodo seleccionado.\n",0,"Aceptar","",null);
                            
                        }

                        localStorage.setItem("filterA1",document.querySelector("#text_filter_acti").value);
                        localStorage.setItem("filterM1",document.querySelector("#text_filter_mate").value);

                        setTimeout(function()
                        {
                            //window.location.reload();
                        },1500);
                        
                    }

                    if(opcion == 23) // Delete Orden
                    {
                        if(data == "1")
                        {
                             mostrarModal(2,null,"Cancelar de orden","Se ha cancelado correctamente la orden\n",0,"Aceptar","",null);
                            setTimeout(function()
                            {
                                window.location.reload();
                            },2000);  
                        }

                        if(data == "-2")
                        {
                            ocultarSincronizacion();
                            mostrarModal(1,null,"Cancelar de orden","No se puede cancelar la orden, por que tiene uno o varios DC en estado Confirmados\n",0,"Aceptar","",null);
                            return;
                        }  
                    }

                    if(opcion == 24) //Anular DC
                    {
                        if(data == "1")
                        {
                            mostrarModal(2,null,"Desasociar DC","Se ha desaciado correctamente el DC.\n",0,"Aceptar","",null);
                            setTimeout(function()
                            {
                                window.location.reload();
                            },2000);  
                        }

                        if(data == "-1")
                        {
                             mostrarModal(1,null,"Anular DC","No se pudo anular el DC\n",0,"Aceptar","",null);
                        }
                    }

                    if(opcion == 25) //Consulta Nodos Afectado
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

                                html += "<option value='" + hijoTbl[i].children[1].dataset.nodo + "'>NODO " + hijoTbl[i].children[1].innerHTML + "" + cadenaEstado + "</option>"

                                array.push(hijoTbl[i].children[1].dataset.nodo);
                            }

                        };

                        $("#select_nodos_afectados").html(html);
                        document.querySelector("#datos_captura_ejecucion").style.display = "none";
                        @if($encabezado != null)
                        @if($encabezado[0]->id_estado != "E4" && $encabezado[0]->id_estado != "C2")
                            document.querySelector("#btn_save_guardar_actividad").style.display = "none";
                        @endif
                        @endif


                        @if(Session::has('gop_lider_seleccionado'))

                                document.querySelector("#select_nodos_afectados").value = '{{Session::get("gop_nodo_seleccionado")}}';

                                var datos = 
                                {
                                    opc: 9,
                                    lid: document.querySelector("#select_lider_carga").value,
                                    ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                                    nod: document.querySelector("#select_nodos_afectados").value
                                }

                                consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",28); 

                                $("#modal_captura_ejecucion").modal("toggle");
                                
                                    $('#hora_cierre').prop('readonly', false);
                                    $('#operador_ccontrol_cierra').prop('readonly', false);
                                    $('#hora_apertura').prop('readonly', false);
                                    $('#operador_ccontrol_abre').prop('readonly', false);
                                <?php
                                    Session::forget('gop_lider_seleccionado');
                                    Session::forget('gop_nodo_seleccionado');
                                ?>
                        @endif

                        ocultarSincronizacion();
                    }

                    if(opcion == 26) // Comsulta datos
                    {
                        $("#datos_captura_ejecucion").html(data);

                        var input = $("#datos_captura_ejecucion input");
                        for (var i = 0; i < input.length; i++) {
                            if(input[i].dataset.num != null)
                            {
                                @if($encabezado != null)
                                @if($encabezado[0]->id_estado != "E2")
                                    input[i].readOnly = true;
                                @endif
                                @endif
                                input[i].addEventListener("keydown",validaIngreso2);  
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
                                    mostrarModal(1,null,"Guardar ejecuci??n","No puede ingresar un valor mayor al programado.\n",0,"Aceptar","",null);
                                    return;
                                  }
                                });
                            }
                        };

                        var input = $("#tbl_baremos input");

                        @if($encabezado != null)
                            @if($encabezado[0]->id_estado != "E4" && $encabezado[0]->id_estado != "C2")
                                document.querySelector("#btn_save_guardar_actividad").style.display = "inline-block";
                            @endif
                        @endif
                        
                        document.querySelector("#datos_captura_ejecucion").style.display = "block";
                        ocultarSincronizacion();
                    }

                    if(opcion == 27) //Save Materiales / Baremos
                    {
                        var person = document.querySelector("#select_lider_carga").options[document.querySelector("#select_lider_carga").selectedIndex].text;

                        var doc = document.querySelector("#select_lider_carga").value;

                        var nodo = document.querySelector("#select_nodos_afectados").options[document.querySelector("#select_nodos_afectados").selectedIndex].text;

                        $("#tbl_persona_cargo").html(data);
                        
                        mostrarModal(2,null,"Guardar conciliaci??n","Se ha guardado correctamente la ejecuci??n del nodo " + nodo + ".\n",0,"Aceptar","",null);
                        ocultarSincronizacion();

                        
                    }

                    if(opcion == 28) //Consulta DC
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
                                    mostrarModal(1,null,"Consultar ejecuci??n","Ingrese la fecha de ejecuci??n, para consultar la informaci??n." + nodo + ".\n",0,"Aceptar","",null);
                                    return;
                                }
                                
                                fechaE = fechaE.split("/")[2] + "-" + fechaE.split("/")[1] + "-" + fechaE.split("/")[0];
                                var datos = 
                                {
                                    opc: 6,
                                    lid: document.querySelector("#select_lider_carga").value,
                                    ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
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
                                    ot : document.querySelector("#id_orden").value.replace("OT000","").replace("OT00","").replace("OT0",""),
                                    nodo: document.querySelector("#select_nodos_afectados").value,
                                    dc : "-1"
                                }    
                            }

                            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",26);
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

                    if(opcion == 29) //Finalizar Orden Ejecuci??n
                    {
                        if(data == "1")
                        {
                            mostrarModal(2,null,"Ejecuci??n orden","Se ha finalizado la orden existosamente.\n",0,"Aceptar","",null);

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
                                mostrarModal(1,null,"No puede finalizar la ejecuci??n de la orden",mes,0,"Aceptar","",null);
                            else
                                mostrarModal(1,null,"No puede finalizar la conciliaci??n de la orden",mes,0,"Aceptar","",null);
                        }
                        ocultarSincronizacion();
                    }

                    if(opcion == 30) //Consultar GOM por WBS
                    {
                        //alert(data);
                        var html = "";

                        if(data == "-1")
                        {
                            mostrarModal(1,null,"Asignar GOM","No ha ingresado ninguna restricci??n para la ManiObra\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        html += "<option value='0'>Seleccione</option>";
                        for (var i = 0; i < data.length; i++) {
                            for (var j = 0; j < data[i].length; j++) {
                                html += "<option value='" + data[i][j].id_gom + "'>" + data[i][j].id_gom  + "</option>";
                            };
                        };
                        document.querySelector("#select_wbs_gom").innerHTML = html;
                        document.querySelector("#select_gom_inst").innerHTML = html;

                        $("#modal_gom_wbs").modal("toggle");
                        ocultarSincronizacion();
                    }

                    if(opcion == 31) // Comsulta datos Conciliaci??n
                    {
                        $("#datos_captura_ejecucion1").html(data);

                        var input = $("#datos_captura_ejecucion1 input");
                        for (var i = 0; i < input.length; i++) {
                            if(input[i].dataset.num != null)
                            {
                                @if($encabezado != null)
                                @if($encabezado[0]->id_estado != "E4")
                                    input[i].readOnly = true;
                                @endif
                                @endif
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
                                    mostrarModal(1,null,"Guardar ejecuci??n","No puede ingresar un valor mayor al programado.\n",0,"Aceptar","",null);
                                    return;
                                  }
                                });
                            }
                        };

                        var input = $("#tbl_baremos1 input");

                        @if($encabezado != null)
                            @if($encabezado[0]->id_estado == "E4")
                                document.querySelector("#btn_save_guardar_actividad1").style.display = "inline-block";
                            @endif
                        @endif
                        
                        document.querySelector("#datos_captura_ejecucion1").style.display = "block";
                        ocultarSincronizacion();
                    }


                    if(opcion == 32) //Consulta DC Conciliaci??n
                    {

                        if(data == "0")
                        {
                            var datos = 
                            {
                                opc: 13,
                                lid: document.querySelector("#select_lider_carga1").value,
                                ot : document.querySelector("#id_orden").value,
                                nodo: document.querySelector("#select_nodos_afectados1").value,
                                dc : "-1"
                            }

                            consultaAjax("{{url('/')}}/consultaActiMate",datos,120000,"POST",31);
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

                    if(opcion == 33) //Consulta Nodos Afectado Conciliaci??n
                    {
                        $("#tbl_persona_cargo1").html(data);
                        if(data.length == 98)
                        {
                            document.querySelector("#datos_captura_ejecucion1").style.display = "none";
                            ocultarSincronizacion();
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

                        $("#select_nodos_afectados1").html(html);
                        document.querySelector("#datos_captura_ejecucion1").style.display = "none";
                        @if($encabezado != null)
                        @if($encabezado[0]->id_estado == "E4")
                            document.querySelector("#btn_save_guardar_actividad1").style.display = "none";
                        @endif
                        @endif
                        ocultarSincronizacion();
                    }

                    if(opcion == 34) //Save Materiales / Baremos
                    {
                        $("#tbl_persona_cargo1").html(data);                        
                        mostrarModal(2,null,"Guardar conciliaci??n","Se ha guardado correctamente la conciliaci??n del nodo .\n",0,"Aceptar","",null);
                        ocultarSincronizacion();
                    }

                    if(opcion == 35) //Consulta LOGS
                    {
                        document.querySelector("#datos_log_user").innerHTML = data;
                        $("#modal_log").modal("toggle");
                        //$('#tbl_log').DataTable();
                        ocultarSincronizacion();
                        
                    }

                    if(opcion == 36) //Agregar nuevo dato
                    {
                        var selectedIndexA = document.querySelector("#select_nodos_afectados").selectedIndex;
                        $("#select_nodos_afectados").val(0).change();
                        $('#select_nodos_afectados').trigger('change');

                        document.querySelector("#select_nodos_afectados").selectedIndex = selectedIndexA;
                        $('#select_nodos_afectados').trigger('change');

                        $("#modal_acti").modal("toggle");

                        setTimeout(function()
                        {
                            $("#modal_captura_ejecucion").modal("toggle");
                            $('#hora_cierre').prop('readonly', false);
                            $('#operador_ccontrol_cierra').prop('readonly', false);
                            $('#hora_apertura').prop('readonly', false);
                            $('#operador_ccontrol_abre').prop('readonly', false);
                        },500);
                        ocultarSincronizacion();
                        
                    }

                    if(opcion == 37) //Reprogramaci??n de Maniobra
                    {
                        if(data == "-4")
                        {
                            mostrarModal(1,null,"Re programaci??n de Maniobra","Se presento un problema desconocido, por favor vuelva a intentarlo.\n",0,"Aceptar","",null);
                            setTimeout(function()
                            {
                               location.reload();
                            },2000);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == "-3")
                        {
                            mostrarModal(1,null,"Re programaci??n de Maniobra","La fecha de reprogramaci??n de ManiObra no puede ser igual a la fecha de ejecuci??n de la Maniobra.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == "-2")
                        {
                            mostrarModal(1,null,"Re programaci??n de Maniobra","La Maniobra actual se encuentra con CAMPROS no anulados.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data == "-1")
                        {
                            mostrarModal(1,null,"Re programaci??n de Maniobra","La fecha de re programaci??n de la ManiObra no puede ser menor o igual a la actual.\n",0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        if(data.length > 10)
                        {
                            mostrarModal(1,null,"Re programaci??n de Maniobra","Los siguientes l??deres tiene asignadas ManiObras para la fecha que seleccion de re programaci??n:\n" + data,0,"Aceptar","",null);
                            ocultarSincronizacion();
                            return;
                        }

                        mostrarModal(2,null,"Re programaci??n de Maniobra","Se ha re programado correctamente la ManiObra",0,"Aceptar","",null);
                        setTimeout(function()
                        {
                           location.reload();
                        },2000);
                    }

                    if(opcion == 38) //Add Restricci??n
                    {
                        document.querySelector("#content_Table_restricciones").innerHTML = data;
                        
                        if(dato != 1)
                        {
                            $("#modal_restricciones_add").modal("toggle");
                            setTimeout(function()
                            {
                                $("#modal_restricciones").modal("toggle");
                            },700);    
                        }
                        $('#tbl_restric').DataTable();
                        ocultarSincronizacion();
                    }

                    if(opcion == 39) //Consulta DC Asociados
                    {
                        var html = "";
                        if(data.length == 0)
                        {
                            html += "<tr>";
                                html += "<td colspan='5' style='    text-align: center;'>NO TIENE DESPACHOS ASOCIADOS</td>";
                            html += "</tr>";    
                        }
                        else
                        {
                            var hijosNodosTbl = document.querySelector("#nodos_select_recurso").children;

                            var rows = tblDatos.rows().data();

                            

                            
                            if(hijosNodosTbl.length == 1)
                            {
                                if(hijosNodosTbl[0].innerText == "No hay datos para mostrar")   
                                {
                                    mostrarModal(1,null,"Asignaci??n DC","No tiene nodos seleccioandos para este ManiObra.\n",0,"Aceptar","",null);
                                    ocultarSincronizacion();
                                    return;
                                }
                            }
                            var htmlNodos = "<select style=' padding: 5px;    width: 53px;'>";
                            for (var i = 0; i < rows.length; i++) {
                                htmlNodos += "<option value='" + rows[i][6] + "'>" + rows[i][1] + "</option>";
                            };

                            /*for (var i = 0; i < hijosNodosTbl.length; i++) {
                                
                            };*/
                            htmlNodos += "</select>";

                            for (var i = 0; i < data.length; i++) {
                                html += "<tr>";
                                    html += "<td>" + '<input type="checkbox" style="    width: 20px;    height: 20px;" />' + "</td>"; 
                                    html += "<td>" + htmlNodos + "</td>";
                                    html += "<td data-lider='" + data[i].id_bodega_destino + "'>" + data[i].id_documento + "</td>";
                                    html += "<td>" + data[i].fecha + "</td>";
                                    html += "<td>" + data[i].id_bodega_destino + " - " + data[i].nombre +   "</td>";
                                    html += "<td>" + data[i].gom + "</td>";
                                    html += "<td>" + data[i].observaciones+ "</td>";
                                    //html += "<td>" + '<i title="Asignar DC" style="    color: blue;    cursor: pointer;    font-size: 20px;  position: relative;    top: 14px;" class="fa fa-share" aria-hidden="true" onclick="seleccionarDCLider(this)"></i>' + "</td>"; 
                                html += "</tr>";    
                            };    
                        }
                        document.querySelector("#tbl_dat_dc").innerHTML = html;
                        $("#moda_despachos").modal("toggle");
                        ocultarSincronizacion();
                    }

                    if(opcion == 40) //Asigna DC a l??der
                    {
                        ocultarSincronizacion();
                        if(data == 1)
                        {
                            mostrarModal(2,null,"Asignaci??n DC","Se la asignado correctamente el despacho al l??der.\n",0,"Aceptar","",null);
                            setTimeout(function()
                            {
                                location.reload();
                            },1000);
                        }

                        if(data == 0)
                        {
                            mostrarModal(1,null,"Asignaci??n DC","No se pudo asignar el despacho al l??der.\n",0,"Aceptar","",null);
                            
                        }

                        if(data == -1)
                        {
                            mostrarModal(1,null,"Asignaci??n DC","El DC ya fue asignado a otro l??der.\n",0,"Aceptar","",null);
                            setTimeout(function()
                            {
                                location.reload();
                            },1000);
                        }

                        if(data.length > 3)
                        {
                            mostrarModal(3,null,"Asignaci??n DC","Se presentaron los siguiente problemas al asignar el DC. \n\n" + data,0,"Aceptar","",null);
                            setTimeout(function()
                            {
                                location.reload();
                            },3000);
                        }
                    }

                    if(opcion == 41) //Consulta L??der cambio
                    {
                        var html = "";
                        html += "<option value=''>Todos";
                        html += "</option>";

                        for (var i = 0; i < data.length; i++) {
                            if(dato != data[i].lider)
                            {
                                html += "<option value='" + data[i].lider + "' data-movil='" + data[i].movil + "' data-tipo='" + data[i].tipo + "' data-lid='" + data[i].lider + "' data-nombre='" + data[i].nom1 + "' data-aux1='" + data[i].aux1 + "' data-aux2='" + data[i].aux2 + "' data-aux3='" + data[i].aux3 + "' data-cond='" + data[i].cond + "' data-matri='" + data[i].matri + "' data-nom2='" + data[i].nom2 + "' data-nom3='" + data[i].nom3 + "' data-nom4='" + data[i].nom4 + "' data-nom5='" + data[i].nom5 + "' data-disp1='" + data[i].disp1 + "' data-disp2='" + data[i].disp2 + "' data-disp3='" + data[i].disp3 + "' data-disp4='" + data[i].disp4 + "' data-dispMovil='" + data[i].dispMovil + "' data-tipoC='" + data[i].tipoC + "'>" + data[i].nom1 + " - " +  data[i].tipo   + " - " + data[i].movil;
                                html += "</option>";
                            }
                        };
                        
                        $("#text_iden_recurso_cambio").html(html);
                        ocultarSincronizacion();
                    }

                    if(opcion == 42) //Consulta Producci??n cuadrilla
                    {

                        document.querySelector("#fecha_programacion").innerHTML = document.querySelector("#fech_ejecucion").value;
                        var datoTables = document.querySelector("#contenido_recurso_ver").children[1].children;
                         var html = "";
                        for (var i = 0; i < datoTables.length; i++) {
                            if(datoTables[i].children.length == 0)
                                continue;

                            for (var j = 0; j < data.length; j++) {
                                @if($encabezado != null)
                                   
                                        if(data[j].lider == datoTables[i].children[3].innerHTML)
                                            {
                                            html += "<tr>";
                                                html += "<td style='text-align:center;font-weight:bold;    margin-top: 3px;'> " + datoTables[i].children[2].innerHTML +  "</td>";                        
                                                html += "<td data-lider='" + datoTables[i].children[3].innerHTML + "' style='text-align:center;font-weight:bold;    margin-top: 3px;'> " + datoTables[i].children[4].innerHTML  + "  " + datoTables[i].children[3].innerHTML + "</td>";                        
                                                html += '<td><div class="progress" style="   margin-top: 6px;    width: 96%;    margin-left: 2%;    margin-bottom: 6px;">';                        
                                                html += '<div class="progress-bar progress-bar-striped progress-bar-success active" role="progressbar"  aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:' + data[j].por + '%">';                        
                                                html += '<span style="color:white; font-weight: bold; ">' + data[j].por + '%</span>';                        
                                                html += '</div></div><p style="    text-align: center;    font-weight: bold;">$ ' + data[j].dinero  + " - Tiempo: " + (data[j].tiempo / 60) +   'Hrs</p></td>';                        
                                                html += "<td style='text-align:center;font-weight:bold;'>$ " + number_format(data[j].meta,2)+ " </td>";                        
                                            html += "</tr>";
                                        }
                                @endif
                                
                                
                            };
                        };
                        document.querySelector("#lista_cuadrillas").innerHTML = html;                     
                        ocultarSincronizacion();
                    }

                    if(opcion == 43) //Update orden
                    {
                        mostrarModal(2,null,"Abrir orden","Se ha abierto correctamente la orden",0,"Aceptar","",null);
                        setTimeout(function()
                        {
                            location.reload();
                        },1000);
                    }

                    if(opcion == 44) //Save Responsables restricciones
                    {
                        document.querySelector("#table_responsables_restricciones").innerHTML = data;

                        document.querySelector("#txt_nombre_resp_create").value = "";
                        document.querySelector("#txt_nombre_resp_create").dataset.dato = "";
                        document.querySelector("#txt_nombre_resp_email").value = "";

                        var hijos = document.querySelector("#tbl_body_responsable").children;

                        var html = "";
                        html += "<option>Seleccione</option>";                            
                        for (var i = 0; i < hijos.length; i++) {
                            html += "<option value= '" + hijos[i].children[0].innerHTML + "' data-correo='" + hijos[i].children[2].innerHTML + "'>" + hijos[i].children[1].innerHTML + "</option>";                            
                        };

                        document.querySelector("#text_responsable_restric").innerHTML = html;
                        ocultarSincronizacion();
                    }


                    if(opcion == 45) //Carga baremos Nuevo modelo
                    {
                        var html = "";
                        for (var i = 0; i < data.length; i++) {
                                html += "<option value='" + data[i].bare + "'>" + data[i].bare + " - " +  data[i].actividad + "</option>";
                        };
                        
                        $("#bareSelect").html(html);
                        ocultarSincronizacion();
                    }

                    if(opcion == 46) //Guardar baremos extras
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
                            $('#hora_cierre').prop('readonly', false);
                            $('#operador_ccontrol_cierra').prop('readonly', false);
                            $('#hora_apertura').prop('readonly', false);
                            $('#operador_ccontrol_abre').prop('readonly', false);
                        },700);

                        ocultarSincronizacion();
                        
                    }

                    if(opcion == 47) //Consulta CD ya trabajo < 9 d??as
                    {
                        if(data != "")
                        {
                            mostrarModal(1,null,"Consulta CD","Uno ?? varios de los CD, PF ?? SECCIONADOR que ingreso tuvo una intervenci??n menor a 9 d??as, tiene problemas con los siguientes datos: \n" + data,0,"Aceptar","",null);
                            document.querySelector("#text_cd").value = "";
                        }

                        ocultarSincronizacion();
                    }
                },
                error:function(x,y,error){
                    ocultarSincronizacion();

                    if (x.status === 0) {
                        alert('Not connected.\nPor favor verificar la conexi??n a internet.');
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
                    //$('#filter_registro').modal('toggle');
                    
                    /*mostrarModal(1,null,"Consulta de Alianzas","Existen problemas con la conexi??n a internet, comun??quese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);*/
                    setTimeout(function()
                    {
                        //location.reload();
                    },3000);
                }
                    
            });
        }

        var arbolNodoM = null;
        var arbolBaremoM = null;
        var optArbolM = 0;
        var ArbolAuxM = 0;

        function crearArbolJSMate(alternateData)
        {
            $('#treeview1').treeview({
                      expandIcon: 'glyphicon glyphicon-chevron-right',
                      collapseIcon: 'glyphicon glyphicon-chevron-down',
                      nodeIcon: "glyphicon glyphicon-user",
                      color: "#428bca",
                      showTags: true,
                      data: alternateData,
                      onNodeSelected: function(event, data) {

                        document.querySelector("#panel-materiales").style.display = "block";
                        
                        //alert(JSON.stringify(data));
                        var tipo = data["href"].split("_")[0].replace("#","");
                        var cod = data["href"].split("_")[1]
                        if(data["href"].split("_").length == 3)
                            cod = data["href"].split("_")[1] + "" + data["href"].split("_")[2];
                        
                        
                        if(tipo == "N")
                        {
                            cod = cod.split(";");
                            arbolNodoM = data.nodeId;
                            optArbolM = 1;
                            
                            document.querySelector("#nombre-plan-1").innerHTML = "NODO " + cod[1];

                            var per = cod[2];
                            if(per == null || per == undefined)
                                document.querySelector("#combox_1_materiales").value = 0;
                            else
                                document.querySelector("#combox_1_materiales").value = per;

                            document.querySelector("#combox_1_materiales").dataset.nodo = cod[0];

                            document.querySelector("#combox_1_materiales").dataset.cant1 = cod[5];
                            document.querySelector("#combox_1_materiales").dataset.cant2 = cod[6];

                            @if($encabezado == null)
                                document.querySelector("#btn-create-material").dataset.nodo = cod[0];
                                document.querySelector("#btn-create-material").dataset.nombre = cod[1];
                            @else
                                @if($encabezado[0]->id_estado == "E1" || $encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE")
                                document.querySelector("#btn-create-material").dataset.nodo = cod[0];
                                document.querySelector("#btn-create-material").dataset.nombre = cod[1];
                                @endif
                            @endif

                            document.querySelector("#home_material_2").style.display = "none";
                            document.querySelector("#home_material_1").style.display = "block";
                            
                        }

                        if(tipo == "B")
                        {

                            cod = cod.split(";");

                            arbolBaremoM = data.nodeId;
                            optArbolM = 2;

                            document.querySelector("#nombre-plan-1").innerHTML = "MATERIAL " + cod[0];
                           
                            document.querySelector("#text_material").innerHTML = cod[1];

                            var per = cod[4];
                            if(per == null || per == undefined)
                                document.querySelector("#combox_2_materiales").value = 0;
                            else
                                document.querySelector("#combox_2_materiales").value = per;

                            document.querySelector("#combox_2_materiales").dataset.nodo = cod[2];
                            document.querySelector("#combox_2_materiales").dataset.articulo = cod[0];

                            document.querySelector("#text_cant_materiales").value = cod[3];
                            document.querySelector("#combox_2_materiales").dataset.cant1 = cod[5];
                            document.querySelector("#combox_2_materiales").dataset.cant2 = cod[6];

                            document.querySelector("#home_material_1").style.display = "none";
                            document.querySelector("#home_material_2").style.display = "block";

                        }
                        setTimeout(function()
                        {
                            document.querySelector("#text_filter_cod_2").value = "";
                            if(document.querySelector("#clasi_dato").value != "0")
                            {
                                $('#treeview1').treeview('expandAll', { levels: 2, silent: true });
                                var nodos = $(".node-treeview1");
                                for (var i = 0; i < nodos.length; i++) {
                                    if (nodos[i].innerHTML.indexOf('ORDEN: ') == -1 && nodos[i].innerHTML.indexOf('NODO: ') == -1)
                                    {
                                        var tipoHerramienta = nodos[i].children[8].innerHTML;
                                        if(tipoHerramienta.indexOf(document.querySelector("#clasi_dato").value) == -1)
                                            nodos[i].style.display = "none";
                                        else
                                            nodos[i].style.display = "block";
                                        //console.log(nodos[i].innerHTML);
                                    }
                                };
                            }
                        },5);
                        
                        
                      }
                });
            
            $('#treeview1').on('searchComplete', function(event, data) {

                    var array = $.map(data, function(value, index) {
                        return [value];
                    });

                    $('#treeview1').treeview('collapseAll', { silent: true });
                    $('#treeview1').treeview('expandNode', [ 0, { levels: 1, silent: true } ]);
                    
                    for (var i = 0; i < array.length; i++) {
                        $('#treeview1').treeview('expandNode', [ array[i].nodeId, { levels: 1, silent: true } ]);
                        //alert(array[i].nodeId)
                    };

                    if(array.length == 0)
                    {
                         mostrarModal(1,null,"Filtro Actividades Nodos","No existe un nodo, con las catacteristicas del filtro que ingreso.\n",0,"Aceptar","",null);
                    }
                    //alert(JSON.stringify(data.length));
                });
            ocultarSincronizacion();
        }

        function filtarDatosMateriales()
        {
            //https://fullcalendar.io/docs/
            if(document.querySelector("#text_filter_mate").value == "")
            {
                $('#treeview1').treeview('clearSearch');
                $('#treeview1').treeview('collapseAll', { silent: true });
            }
            else
            {
                $('#treeview1').treeview('search', [ 'NODO: ' + document.querySelector("#text_filter_mate").value, {
                              ignoreCase: false,     // case insensitive
                              exactMatch: false,    // like or equals
                              revealResults: false,  // reveal matching nodes
                            }]);
            }

        }

        function pintaArbol()
        {
            for (var i = 0; i < arreglosTree.length; i++) {
                for (var j = 0; j < arregloNodosSeleccionados.length; j++) {
                    if(arreglos[i].dataset.nodeid == arregloNodosSeleccionados[j].nodo)
                    {
                        arreglos[i].style.color = "blue";
                        arreglos[i].children[4].innerHTML = arregloNodosSeleccionados[j].cant;
                    }
                }
            };
        }

        /*FUNCIONES MODALES ADD BAREMOS Y MATERAILES*/
        function abrirModalMateriales()
        {
            $("#tbl_mate_add").find('tr').remove();
            document.querySelector("#text_mate_search").value = "";
            document.querySelector("#text_mate_des_seacrh").value = "";


            document.querySelector("#text_mate_search").readOnly  =false;
            document.querySelector("#text_mate_des_seacrh").readOnly  =false;

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
                mostrarModal(1,null,"Filtro Materiales","Ingrese informaci??n para el filtro\n",0,"Aceptar","",null);

                return;
            }

            var array = 
            {
                cod : document.querySelector("#text_mate_search").value,
                des : document.querySelector("#text_mate_des_seacrh").value,
                opc : "2",
            }

            consultaAjax("{{url('/')}}/consultaBaremos",array,35000,"POST",21);
        }

        function agregarTablaMaterial(ele)
        {
            document.querySelector("#text_mate_add").value = ele.dataset.mate + " - " +  ele.dataset.nombre;
            document.querySelector("#text_mate_add").dataset.mate = ele.dataset.mate;
            document.querySelector("#text_mate_add").dataset.nombre = ele.dataset.nombre;
            document.querySelector("#text_mate_add").dataset.precio = ele.dataset.precio;

            document.querySelector("#text_valor_mate_add").value = ele.dataset.precio;

            document.querySelector("#text_valor_mate_add").readOnly = false;
            document.querySelector("#text_cant_mate_add").readOnly = false;


            if(document.querySelector("#text_cant_mate_add").value == "")
                document.querySelector("#text_cant_mate_add").value = "1";

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

        function modificaValorTotalMate()
            {
                if(document.querySelector("#text_cant_mate_add").value != "")
                {
                    document.querySelector("#text_total_mate_add").value = parseFloat(document.querySelector("#text_cant_mate_add").value) * parseFloat(document.querySelector("#text_valor_mate_add").value);
                }
            }

        function  saveNuevoMaterial()
        {
            
            if(document.querySelector("#select_nodos_material").value == "" ||
                document.querySelector("#text_mate_add").value == "" ||
                document.querySelector("#text_valor_mate_add").value == "" ||
                document.querySelector("#text_total_mate_add").value == "" ||
                document.querySelector("#text_cant_mate_add").value == "" ||
                document.querySelector("#select_nodos_material").dataset.nodo == null ||
                document.querySelector("#select_nodos_material").dataset.nodo == undefined
                )
            {

                mostrarModal(1,null,"Agregar material","Hace falta diligenciar campos para guardar\n",0,"Aceptar","",null);
                return;

            }

            var mate = document.querySelector("#text_mate_add").dataset.mate;
            var cantidad = document.querySelector("#text_cant_mate_add").value;
            var nodo = document.querySelector("#select_nodos_material").dataset.nodo;



            var datos = 
            {
                opc : 10,
                mate : mate,
                cant : cantidad,
                nodo : nodo,
                cargo: document.querySelector("#select_persona_cargo_mate").value
            }; 

            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",22);

               
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
                mostrarModal(1,null,"Filtro Baremos","Ingrese informaci??n para el filtro\n",0,"Aceptar","",null);
                return;
            }

            var array = 
            {
                cod : document.querySelector("#text_baremo_cod").value,
                des : document.querySelector("#text_baremo_des").value,
                opc : "0"
            }

            consultaAjax("{{url('/')}}/consultaBaremos",array,35000,"POST",20);
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

            if(ayudaAddBare == 1)
            {
                var datos = 
                {
                    opc : 13,
                    bare : baremo,
                    cant : cantidad,
                    nodo : nodo,
                    ot : document.querySelector("#id_orden").value,
                    cargo: document.querySelector("#select_persona_cargo_bare").value
                }; 

                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",36);
            }
            else
            {
                var datos = 
                {
                    opc : 9,
                    bare : baremo,
                    cant : cantidad,
                    nodo : nodo,
                    ord :document.querySelector("#id_orden").value,
                    pry :document.querySelector("#id_proyect").value,
                    cargo: document.querySelector("#select_persona_cargo_bare").value
                }; 

                consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",22);
            }   
            
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
                mostrarModal(1,null,"Cargar ejecuci??n","No puede seleccionar una fecha menor a la de inicio de la ejecuci??n\n",0,"Aceptar","",null);
                return;
            }

            if(fecahSelect > fechaFin){
                mostrarModal(1,null,"Cargar ejecuci??n","No puede seleccionar una fecha mayor a la de finalizaci??n de la ejecuci??n\n",0,"Aceptar","",null);
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
            
          $('#hora_cierre').prop('readonly', false);
          $('#operador_ccontrol_cierra').prop('readonly', false);
          $('#hora_apertura').prop('readonly', false);
          $('#operador_ccontrol_abre').prop('readonly', false);
            setTimeout(function()
            {
                $("#modal_acti_add_nuevo_modelo").modal("toggle");
            },700);
            
        }
        
        function addCampros(lider){

            document.querySelector("#filter_dc").value = "";
             var datos = 
                {
                    opc : 28,
                    ot : document.querySelector("#id_orden").value,
                    lider : lider
                }; 

            consultaAjax("{{url('/')}}/consultaActiMate",datos,20000,"POST",39);
            // 
        }

        var liderSele = null;
        function cambioLider(lider,hora1,hora2,tipo_cuadrilla,movil,lider,nombrel)
        {
            //document.querySelector("#select_tipo_cuadrilla_cambio").options[document.querySelector("#select_tipo_cuadrilla_cambio").selectedIndex].text = tipo_cuadrilla;
            document.querySelector("#text_fecha_ini_cambio").value = hora1;
            document.querySelector("#text_fecha_fin_cambio").value = hora2;
            liderSele = lider;
            document.querySelector("#datos_lider_cambio").innerHTML = "  " + lider + " - " + nombrel + " - " + movil;
            $("#modal_cambio_lider").modal("toggle");
            //consultaRecursoAdd1
            //
            //agregarPersona1
        }

        function consultaRecursoAdd1()
        {
            var datos = 
            {
                opc : 3,
                cod : "",
                des : "",
                tipo : document.querySelector("#select_tipo_cuadrilla_cambio").value,
                hora1 : document.querySelector("#text_fecha_ini_cambio").value,
                    orden :document.querySelector("#id_orden").value,
                proyecto :document.querySelector("#id_proyect").value,
                hora2 : document.querySelector("#text_fecha_fin_cambio").value
            };
            
            consultaAjax("{{url('/')}}/consultaBaremos",datos,200000,"POST",41,null,liderSele); 
        }

        function agregarPersona1()
        {
            if(document.querySelector("#text_iden_recurso_cambio").selectedIndex != 0)
            {
                if(confirm("??Seguro que desea cambiar el l??der actual?"))
                {
                    var ele = document.querySelector("#text_iden_recurso_cambio").options[document.querySelector("#text_iden_recurso_cambio").selectedIndex];
                    var datos = 
                    {
                        opc : 17,
                        cod : ele.dataset.lid,
                        lidA : liderSele,
                        hor1 : document.querySelector("#text_fecha_ini_cambio").value,
                        hor2 : document.querySelector("#text_fecha_fin_cambio").value,
                        tip : ele.dataset.tipoc,
                        aux1 : (ele.dataset.disp1 == "0" ? ele.dataset.aux1 : ""),
                        aux2 : (ele.dataset.disp2 == "0" ? ele.dataset.aux2 : ""),
                        orden :document.querySelector("#id_orden").value,
                        proyecto :document.querySelector("#id_proyect").value,
                        aux3 : (ele.dataset.disp3 == "0" ? ele.dataset.aux3 : ""),
                        cond : (ele.dataset.disp4 == "0" ? ele.dataset.cond : ""),
                        movi : (ele.dataset.dispMovil == "0" ? ele.dataset.matri : "")
                    };
                    
                    consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",datos,20000,"POST",12,null,ele);
                }
            }
        }


        function validarDatosFilterDc()
        {
            var hijosTBL = document.querySelector("#tbl_dat_dc").children;

            if(document.querySelector("#filter_dc").value == "")
            {
                for (var i = 0; i < hijosTBL.length; i++) {
                        hijosTBL[i].style.display = "table-row";
                }; 
            }
            else
            {
                for (var i = 0; i < hijosTBL.length; i++) {
                    if (hijosTBL[i].children[0].innerHTML.indexOf(document.querySelector("#filter_dc").value)!=-1)
                        hijosTBL[i].style.display = "table-row";
                    else
                        hijosTBL[i].style.display = "none";
                };   
            }
        }

        function seleccionarDCLider()
        {

            var hi = document.querySelector("#tbl_dat_dc").children;

            if(hi.length == 0)
                return;

            var cont = 0;
            for (var i = 0; i < hi.length; i++) {
                if(hi[i].children[0].children[0].checked)
                    cont++;
            };
            
            if(cont == 0)
            {
                mostrarModal(1,null,"Asociar DC","No ha seleccionando ning??n DC para asociar al l??der\n",0,"Aceptar","",null);
                return;
            }
            
            //if(confirm("??Seguro que desea asignar el DC " +  ele.parentElement.parentElement.children[1].innerHTML + " al NODO " + ele.parentElement.parentElement.children[0].children[0].options[ele.parentElement.parentElement.children[0].children[0].selectedIndex].text))
            if(confirm("??Seguro que desea asignar los DC seleccionados al l??der?"))
            {
                var arr = [];
                 for (var i = 0; i < hi.length; i++) {
                    if(hi[i].children[0].children[0].checked)
                    {
                        arr.push(
                                {
                                    dc : hi[i].children[2].innerHTML,
                                    lider : hi[i].children[2].dataset.lider,
                                    nodo : hi[i].children[1].children[0].value,
                                }
                            );
                    }
                };


                var datos = 
                {
                    opc : 29,
                    ot : document.querySelector("#id_orden").value,
                    pry : document.querySelector("#id_proyect").value,
                    arr : arr
                }; 

                //alert(JSON.stringify(datos));
                consultaAjax("{{url('/')}}/consultaActiMate",datos,20000,"POST",40);
            }
        }   

        function abrirModalResponsable()
        {
            $("#modal_restricciones_add").modal("toggle");
            setTimeout(function()
            {
                $("#modal_responsable_add").modal("toggle");    
            },600);
        }

        function regresarResponsables()
        {
            $("#modal_responsable_add").modal("toggle");  
            setTimeout(function()
            {
                $("#modal_restricciones_add").modal("toggle");
            },600);   
        }

        function save_responsable_restriccion()
        {
            if(document.querySelector("#txt_nombre_resp_create").value == "" ||
                document.querySelector("#txt_nombre_resp_email").value == "" )
            {
                mostrarModal(1,null,"Agregar responsable","Hace falta diligenciar informaci??n\n",0,"Aceptar","",null);
                return;
            }


            if(!validarEmail(document.querySelector("#txt_nombre_resp_email").value))
            {
                mostrarModal(1,null,"Agregar responsable","El email ingresado no es correcto\n",0,"Aceptar","",null);
                return;
            }
            

            var array = 
            {
                opc : "19",
                nombre : document.querySelector("#txt_nombre_resp_create").value,
                correo : document.querySelector("#txt_nombre_resp_email").value,
                id : document.querySelector("#txt_nombre_resp_create").dataset.dato
            }

            consultaAjax("{{url('/')}}/guardarAsignacionRecursosMateriales",array,125000,"POST",44); 
        }

        function updateResp(ele)
        {
            document.querySelector("#txt_nombre_resp_create").value = ele.parentElement.parentElement.children[1].innerHTML;
            document.querySelector("#txt_nombre_resp_create").dataset.dato = ele.parentElement.parentElement.children[0].innerHTML;
            document.querySelector("#txt_nombre_resp_email").value = ele.parentElement.parentElement.children[2].innerHTML;
        }

        function deleteDatosResponsable()
        {
            document.querySelector("#txt_nombre_resp_create").value = "";
            document.querySelector("#txt_nombre_resp_create").dataset.dato = "";
            document.querySelector("#txt_nombre_resp_email").value = "";
        }

        function agregarReponsableNuevo()
        {

            if(document.querySelector("#text_responsable_restric").selectedIndex == 0 )
            {
                mostrarModal(1,null,"Agregar responsable","No ha seleccionado ning??n responsable\n",0,"Aceptar","",null);
                return;
            }
            $("#correo_enviar").append("<tr><td data-id='" + document.querySelector("#text_responsable_restric").value + "'>" + document.querySelector("#text_responsable_restric").options[document.querySelector("#text_responsable_restric").selectedIndex].text + "</td><td>" + document.querySelector("#text_responsable_restric").options[document.querySelector("#text_responsable_restric").selectedIndex].dataset.correo + "</td><td><button onclick='deleteCorreo(this)'><i class='fa fa-times'></i> </button></td></tr>");
            document.querySelector("#text_responsable_restric").selectedIndex = "";

        }

        function salir(opc)
        {
            if(opc == 2)
            {
                $("#modal_cambio_lider").modal("toggle");
            }
        }


        function consultaCD(ele)
        {
            if(!ele.readOnly)
            {

                if(ele.value != "" && document.querySelector("#id_orden").value != "")
                {
                    var datos = 
                    {
                        opc : 36,
                        ot : document.querySelector("#id_orden").value,
                        cd : ele.value
                    }; 

                    // if(opcion == 46) //Guardar baremos extras
                    //alert(JSON.stringify(datos));
                    consultaAjax("{{url('/')}}/consultaActiMate",datos,20000,"POST",47);
                }

            }
        }


/////////////////////////////////////////////////////////////////////////////////////////
////////////////////// inicio codigo para solicitu de miniobras  ////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////

    $("#btn_ver_solicitud_maniobra").on('click',function(event){
          recargaregistros();
          event.preventDefault();
          event.stopPropagation();
          $("#modal_solicitud_maniobra").modal("show");
          
    });
   
    $('#modal_solicitud_maniobra').on('shown.bs.modal', function () {
        $(".divtitu div").height($(".divtitu").height());
        $(".divtitu .dv-clear").height("0");
        reacomodaaltura();
    });

    function reacomodaaltura(){
        //todos los content data
        var elementos = $(".content_dat");
        for(var i =0;i<elementos.length;i++){
            $(elementos[i]).find(".alturad").css('min-height', '0px');
        }
        for(var i =0;i<elementos.length;i++){
            var altura=$(elementos[i]).height(); 
            $(elementos[i]).find(".alturad").css('min-height', altura+'px');
        }
    }
    

    
    function editarregistro(event,element){
          $('#modal_solicitud_maniobra .day').attr('onclick',''); 
          event.preventDefault();
          event.stopPropagation();
          $(element).parent().parent().parent().hide();
          $(element).parent().parent().parent().next().find(".taga").prop('readonly',false).prop('disabled', false);
          $(element).parent().parent().parent().next().show();
          reacomodaaltura();
        
    }

    function canceleditregistro(event,element){
        
          event.preventDefault();
          event.stopPropagation();
          $(element).parent().parent().parent().parent().hide();
          $(element).parent().parent().parent().parent().prev().show();
          reacomodaaltura();
        
    }
    function detener(event){
          //  event.preventDefault();
      //  event.stopPropagation();
    }
    
    function submitfrm(event,elemento){
        
        
            event.preventDefault();
        event.stopPropagation();
            
            var elementos = $(elemento).find(".valida_texto");
            var tam = elementos.length;
            var control=0;
            for (var i=0; i<tam; i++) {
                if(valida_texto(elementos[i])==0){
                     control=1;
                }
            }
          
            var elementos2 = $(elemento).find(".valida_select");
            var tam2 = elementos2.length;
            for (var i=0; i<tam2; i++) {
                if(valida_select(elementos2[i])==0){
                     control=1;
                }
            }
            if(control==1){return false;}
            
             $(elemento).find('.btnfrmoc').hide("slow",function(){ 
              
                    $(elemento).find('.loading').show("slow"); 
                     
                    var formData = new FormData($(elemento)[0]);
                    $.ajax({
                            type: 'POST',
                            url: "<?= Request::root() ?>/redes/ordenes/nuevamaniobra",
                            data: formData,
                            dataType: "json",
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data,textStatus) {  
                                if(data.status==1){
                                   // if(data.accion==1){
                                       limpianuevo();
                                   // }
                                    mostrarModal(2,null,"Exito","Proceso finalizado satisfactoriamente.\n",0,"Aceptar","",null);                                 
                                }else{
                                    mostrarModal(1,null,"Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0,"Aceptar","",null);                                 
                                }                                
                            }, 
                            error: function(data) {
                                    mostrarModal(1,null,"Error","Ocurrio un error, intentalo nuevamente mas tarde.\n",0,"Aceptar","",null);  
                            }
                     }).always(function() {              
                          $(elemento).find('.loading').hide("slow",function(){$(elemento).find('.btnfrmoc').show();}); 
                     });  
             });
             return false;  
    }
    function borraeditregistro(event,elemento,id){
    
        var contiene=$(elemento).parent().parent();
    
        event.stopPropagation();
        event.preventDefault();
                
        new PNotify({
          title: 'Confirmacion',
          text: 'Esta seguro de eliminar el elemento seleccionada?',
          icon: 'glyphicon glyphicon-question-sign',
          hide: false,
          addclass: 'stack-modal colorcampro',
          stack: {'dir1': 'down', 'dir2': 'right', 'modal': true},
          confirm: {
            confirm: true,
            buttons: [
              {
                text: 'Aceptar',
                //addClass: 'btn-primary',
                click: function(){
                    $(contiene).find('.btnfrmocd').hide("slow",function(){
                       $(contiene).find('.loading').show("slow"); 
                       
                       var token='<?= csrf_token() ?>'; 
                       $.ajax({
                              type: 'POST',
                              url: "<?= Request::root() ?>/redes/ordenes/eliminasolman",
                              data: {id:id,_token:token },
                              dataType: "json",
                              success: function(data) {PNotify.removeAll();
                                      if(data.result == 1){
                                              recargaregistros(); 
                                              mensajes("??xito","Proceso finalizado satisfactoriamente.",1);
                                      }else if(data.response.status == 0){
                                              mensajes("Error",data.response.message,0); 
                                      }else{ mensajes("Error","Ocurri?? un error por favor int??ntalo nuevamente mas tarde.",0); }
                              }, 
                              error: function(){  PNotify.removeAll(); mensajes("Error","Ocurri?? un error por favor int??ntalo nuevamente mas tarde.",0);   }
                      }).always(function(){      
                          $(contiene).find('.loading').hide("slow",function(){$(contiene).find('.btnfrmocd').show();});
                      }); 
                      
                      
                      
                   });
                   
                }
              },
              {
                text: 'Cancelar',
                click: function(){
                   PNotify.removeAll();
                }
              }
            ]
          },
          buttons: {
            closer: false,
            sticker: false
          },
          history: {
            history: false
          }
        });
    
    }
    
    function creanu(){ 
        $('#modal_solicitud_maniobra .day').attr('onclick',''); 
        $("#tipo_nuevo").val(1);
        
        var d = new Date();
        var ano = d.getFullYear();
        var mes = d.getMonth();
            mes++;
            if(mes<10){mes="0"+mes;}
        var dia = d.getDate();
            if(dia<10){dia="0"+dia;}
        
        var hora = d.getHours();
        var minuto = d.getMinutes();       
        
        
        $("#hora_num").val(ano+"-"+mes+"-"+dia+" "+hora+":"+minuto);
        $("#observaciones_num").val("");
        
        var accion =poneaacionmaniobra(1,0);
        $("#accionnuevo").html(accion['select']);
       // var elementpo =poneaelemmaniobra(1,0);
       // $("#elementonuevo").html(elementpo['select']);
        $("#elemento_num_txt").val("");
        
        $("#contentnu").find(".taga").prop('readonly',false).prop('disabled', false);        
        $("#btncenu").hide('slow',function(){           
             $("#contentnu").show('slow');           
        });
    }
    function cambiotipo(){
        
        var tipo = $("#tipo_nuevo").val();
        var accion =poneaacionmaniobra(tipo,0);
        $("#accionnuevo").html(accion['select']);
       // var elementpo =poneaelemmaniobra(tipo,0);
       // $("#elementonuevo").html(elementpo['select']);
    }
    function cancelnuregistro(){
       $("#contentnu").hide('slow',function(){           
             $("#btncenu").show('slow');           
       });
    }
    function ponenuevo(tipo,datos,numero){
        var id=datos['id'];
        var tipo_maniobra_id=datos['tipo_maniobra_id'];
        var accion_maniobra_id=datos['accion_maniobra_id'];
        var elemento_maniobra_id=datos['elemento_maniobra_id'];
        var elemento_maniobra_txt=datos['elemento_maniobra_txt'];
        var observaciones=datos['observaciones'];
        var hora=datos['hora'].replace(":00.000", "");
       
        var poneaacionmaniobr = poneaacionmaniobra(tipo_maniobra_id,accion_maniobra_id);
        //var poneaelemmaniobr = poneaelemmaniobra(tipo_maniobra_id,elemento_maniobra_id);
        var id_orden='<?= $orden ?>';
        var id_proyecto='<?= $proyecto ?>';
        
         var html ="<div class='dv-clear'></div>";
             html +="  <div class='content_dat odd' >";
             html +="     <div class='subconten_dat alturad' >";
             html +="        <div class='divizq col-md-1 padl-0 padr-0 centro alturad'>"+numero+"</div>";
             html +="        <div class='divcen col-md-2 padl-0 padr-0 centro alturad'>"+poneaacionmaniobr['text']+"</div>";
             html +="        <div class='divcen col-md-2 padl-0 padr-0 centro alturad'> "+hora+"</div>";
             html +="        <div class='divcen col-md-3 padl-0 padr-0 alturad'> "+elemento_maniobra_txt+"</div>";
             html +="        <div class='divcen col-md-3 padl-0 padr-0 alturad'>"+escapeHtml(observaciones)+"</div>";
             html +="        <div class='divder col-md-1 padl-0 padr-0 centro alturad'>";
             html +="            <div class='btnfrmocd centro'>";
             html +="                        <a href='#' onclick='editarregistro(event,this);' class='btnedita' style='color:#3019de;'><i class='fa fa-pencil' aria-hidden='true'></i></a>";
             html +="                        <a href='#' onclick='borraeditregistro(event,this,"+id+");' style='color:#ef1414;' ><i class='fa fa-trash' aria-hidden='true'></i></a>";
             html +="            </div> ";
             html +="                <img src='<?= Request::root() ?>/img/loader6.gif' class='loading' alt='Loading...' >";
             html +="        </div>";
             html +="     </div>"; 
             html +="  <div class='subcontend_dat alturad' style='display: none;' >";
             html +="     <form onsubmit='return submitfrm(event,this)'>";             
             html +="        <input type='hidden' name='_token' value='<?= csrf_token() ?>'>";
             html +="        <input type='hidden' name='id' value='"+id+"'>";
             html +="        <input type='hidden' name='tipo_maniobra_id' value='"+tipo_maniobra_id+"'>";
             html +="        <input type='hidden' name='id_orden' value='"+id_orden+"'>";
             html +="        <input type='hidden' name='id_proyecto' value='"+id_proyecto+"'>";
             html +="        <div class='divizq col-md-1 padl-0 padr-0 alturad centro'> ";
             html +="                         "+numero;
             html +="        </div>";
             html +="        <div class='divcen col-md-2 padl-0 padr-0 centro alturad'> ";                           
             html +="            "+poneaacionmaniobr['select'];
             html +="            </div>";
             html +="                <div class='divcen col-md-2 padl-0 padr-0 centro alturad'> ";
             html +="                    <input type='text' name='hora' value='"+hora+"'   class='form-control hora taga valida_texto datetimepicker1 no_select' >";
        
    /*  
            html +="                   <div class='input-group date datetimepicker1'>";
            html +="                         <input type='text' name='hora'  value='"+hora+"'   class='form-control hora taga valida_texto ' />";
            html +="                         <span class='input-group-addon'>";
            html +="                             <span class='glyphicon glyphicon-calendar'></span>";
            html +="                         </span>";
            html +="                     </div>";
    */
    
             html +="                </div>";
             html +="                <div class='divcen col-md-3 padl-0 padr-0 alturad'>";
           //  html +="                  "+poneaelemmaniobr['select'];
             html +="                    <input type='text' name='elemento_maniobra_txt'  value='"+elemento_maniobra_txt+"'   class='form-control hora taga valida_texto  ' >";
             html +="                </div>";
             html +="                    <div class='divcen col-md-3 padl-0 padr-0 alturad'>";
             html +="                        <textarea  name='observaciones'  class='form-control observaciones taga ' >"+escapeHtml(observaciones)+"</textarea>";
             html +="                    </div>";
             html +="                    <div class='divder col-md-1 padl-0 padr-0 centro alturad'>";
             html +="                        <div class='btnfrmoc centro'>";
             html +="                            <button type='submit' class='btnsubmis ' onclick='detener(event)' style='color:#14b939;' ><i class='fa fa-save' aria-hidden='true'></i></button>";
             html +="                            <a href='#'  onclick='canceleditregistro(event,this);' class='btnprev ' ><i class='fa fa-reply' aria-hidden='true'></i></a> ";
             html +="                        </div> ";
             html +="                        <img src='<?= Request::root() ?>/img/loader6.gif' class='loading' alt='Loading...' >";
             html +="                    </div>";
             html +="                </form>    ";
             html +="            </div>       ";             
             html +="            <div class='dv-clear'></div>";
             html +="        </div>      ";                          
             html +="        <div class='dv-clear'></div>";
   // console.log("entro a pone nuevo "+html);
          return html;
    }
    
    function poneaacionmaniobra(tipo,tipomani=0){
        
        var retorna = {select:"", text:""};
        
        var acctionmani = "<select  id='accion_num' name='accion_maniobra_id'  class='form-control accion taga valida_select'  >";
            acctionmani +="     <option value='0' >Seleccione Accion</option>";
            <?php foreach ($accmani as $dato){ ?>
             
            if(tipo==<?= $dato->tipo_maniobra_id  ?> || <?= $dato->tipo_maniobra_id  ?> ==3){

                var selecciona="";  
                if(tipomani==<?= $dato->id  ?>){
                    selecciona="selected";
                    retorna['text']="<?= $dato->descripcion  ?>";
                }
                acctionmani +="     <option value='<?= $dato->id  ?>' "+selecciona+" ><?= $dato->descripcion  ?></option>"; 
            }
            <?php  } ?>
            acctionmani +="</select>";
        
        
        retorna['select']=acctionmani;
        //console.log(acctionmani);
        return retorna;
        
    }
    
     function poneaelemmaniobra(tipo,tipomani=0){
        
        var retorna = {select:"", text:""};
        var acctionmani = "<select  id='elemento_num' name='elemento_maniobra_id'  class='form-control accion taga valida_select'  >";
            acctionmani +="     <option value='0' >Seleccione Accion</option>";
            <?php foreach ($elemmani as $dato){ ?>
             
            if(tipo==<?= $dato->tipo_maniobra_id  ?> || <?= $dato->tipo_maniobra_id  ?> ==3){

                var selecciona="";  
                if(tipomani==<?= $dato->id  ?>){
                    selecciona="selected";
                    retorna['text']="<?= $dato->descripcion  ?>";
                }
                acctionmani +="     <option value='<?= $dato->id  ?>' "+selecciona+" ><?= $dato->descripcion  ?></option>"; 
            }
            <?php  } ?>
            acctionmani +="</select>";
            
        retorna['select']=acctionmani;
        //console.log(acctionmani);
        return retorna;
        
        
    }
    
    function limpianuevo(){
        
    
        $("#tipo_nuevo").val(1);
        $("#hora_num").val("");
        $("#observaciones_num").val("");
        
        var accion =poneaacionmaniobra(1,0);
        $("#accionnuevo").html(accion['select']);
        //var elementpo =poneaelemmaniobra(1,0);
        //$("#elementonuevo").html(elementpo['select']);
        $("#elemento_num_txt").val("");
        recargaregistros();
    }
    
    function recargaregistros(){
        //console.log("aca recargar");
                
        var id_orden='<?= $orden ?>';
        var id_proyecto='<?= $proyecto ?>';
        var token='<?= csrf_token() ?>'; 
         
        $.ajax({
               type: 'POST',
               url: "<?= Request::root() ?>/redes/ordenes/listasolman",
               data: {id_orden:id_orden,id_proyecto:id_proyecto,_token:token },
               dataType: "json",
               success: function(data) {
                   
                   var tamano=data.length;
                   var conta=1;
                   var contad=1;
                   $("#contenedderecha").empty();
                   $("#contenedorizquierda").empty();
                   for(var i=0;i<tamano;i++){
                       
                        var tipod=data[i]['tipo_maniobra_id'];
                        if(tipod==1){
                            var nhtml=ponenuevo(tipod,data[i],conta);
                            $("#contenedorizquierda").append(nhtml);
                            conta++;
                        }else{
                            var nhtml=ponenuevo(tipod,data[i],contad);
                            $("#contenedderecha").append(nhtml);
                            contad++;
                        }
                   }
                   reacomodaaltura();
                   $('.datetimepicker1').datetimepicker().on("show", function(e) {
            $('#modal_solicitud_maniobra .day').attr('onclick',''); 
    });
               }, 
               error: function(){  PNotify.removeAll(); mensajes("Error","Ocurri?? un error por favor int??ntalo nuevamente mas tarde.",0);   }
        }).always(function(){      
        }); 
    }
    
    
     $(function () {
                $('#datetimepicker1').datetimepicker().on("show", function(event){
             $('#modal_solicitud_maniobra .day').attr('onclick',''); 
    });
                $('.datetimepicker1').datetimepicker().on("show", function(e) {
            $('#modal_solicitud_maniobra .day').attr('onclick',''); 
    });
                
                
                  
    });
    
    var entityMap = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
        '/': '&#x2F;',
        '`': '&#x60;',
        '=': '&#x3D;'
      };

      function escapeHtml (string) {
        return String(string).replace(/[&<>"'`=\/]/g, function (s) {
          return entityMap[s];
        });
      }
      
      
      
      //////////////////horas
      
      		
          $("#form_horasac").on('submit',function(event){
           //function guardarHorasse(event){     
                
                event.stopPropagation();
                event.preventDefault();
                
                var idor = $("#id_orden_hora").val().trim();
                <?php if($encabezado == null){?>
                        idor = $("#text_eje_1").val().trim();
                <?php } ?>        
                
                if(idor==''){
                    mensajes('Error','Ingrese un n??mero de orden',0);
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
/////////////////////////////////////////////////////////////////////////////////////////
//////////////////////// fin codigo para solicitu de miniobras  /////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
  
    </script>
@stop

