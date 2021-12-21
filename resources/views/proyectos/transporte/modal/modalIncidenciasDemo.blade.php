<div class="modal fade" id="modal_incidencias"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document" style="width: 98%;">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Incidencias</h5>

            </div>
            <div class="modal-body">
                

                <div class="col-md-8" style="    width: 65%;    margin-left: 1%;">
                    <div class="row">
                       <div class="col-md-3">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_vehiculo_incidencia">Vehículo:</label>
                                    <input name="txt_vehiculo_incidencia" type="text" readonly class="form-control" id="txt_vehiculo_incidencia"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_incidencia_estado">Estado incidencia:</label>
                                    <input name="txt_incidencia_estado" type="text" readonly class="form-control" id="txt_incidencia_estado"  />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                           <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="id_incidencia">Incidencia:</label>
                                    <input name="id_incidencia" type="text" readonly class="form-control" id="id_incidencia"  />
                                </div>
                            </div> 
                        </div> 

                        <div class="col-md-3">
                           <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="recorrido_promedio">Recorido promedio:</label>
                                    <input name="recorrido_promedio" type="text" readonly class="form-control" id="recorrido_promedio"  />
                                </div>
                            </div> 
                        </div> 
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4" style="display:none;">

                            <div class="row" style="margin-top:5px;" >
                                <div class="form-group has-feedback">
                                    <label for="select_tipo_incidencia">Tipo de incidencia:</label>
                                    {!!Form::select('select_tipo_incidencia', $tiposInci, null, ["class"=>"form-control","placeholder"=>"Seleccione","id"=>"select_tipo_incidencia","disabled"=>"true"])!!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                           <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_kilometraje">Kilometraje actual del vehículo:</label>
                                    <input name="txt_kilometraje" type="number" readonly class="form-control" id="txt_kilometraje"  min = "1" autocomplete="off" autocomplete="nope"/>
                                    <span id="panel_kilo_ultimo" style="    font-size: 11px;    color: blue;display:none;">
                                        <b>U. Kilometraje:<span id="ultimo_kilo"></span></b>
                                        <b>Fecha:<span id="fecha_kilo"></span></b></span>
                                </div>
                            </div> 
                        </div>

                        

                        <div class="col-md-3">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label >Último kilometraje reportado:</label>
                                    <input name="txt_Fecha_generacion" id="txt_ultimo_kilometraje_reportado" type="text" readonly class="form-control"  />
                                </div>
                            </div>  
                        </div>

                        <div class="col-md-3">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label >Fecha del último kilometraje:</label>
                                    <input  type="text" id="txt_fecha_ultimo_kilometraje" readonly class="form-control"   />
                                </div>
                            </div>  
                        </div>

                        <div class="col-md-3">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_Fecha_generacion">Fecha y hora de generación:</label>
                                    <input name="txt_Fecha_generacion" type="text" readonly class="form-control" id="txt_Fecha_generacion"  />
                                </div>
                            </div>  
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_observacion">Observación:</label>
                                    <textarea name="txt_observacion" type="text" readonly class="form-control" id="txt_observacion" style="height:100px;resize:none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                              <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_direccion">Dirección:</label>
                                    <input name="txt_direccion" type="text" readonly class="form-control" id="txt_direccion"  />
                                </div>
                            </div>

                            <div class="row" style="margin-top:5px;" id="panel_0">
                                <div class="form-group has-feedback">
                                    <label for="id_tipo_incidencia">Ingresar dirección:</label>
                                    <br>
                                        <select class="form-control" id="selCll" style="width:88px;display:inline-block;" />
                                            <option value="">Seleccione</option>
                                            <option value="KR">KR</option>
                                            <option value="CLL">CLL</option>
                                            <option value="AV">AV</option>
                                            <option value="DIAG">DIAG</option>
                                        </select>

                                    <input id="txtCll" type="numer" class="inputCorto form-control"  style="padding:0px;width:52px;display:inline-block;"> </input>

                                    <select class="form-control" id="selLetra1" style="width:88px;display:inline-block;"/>
                                            <option value="">Seleccione</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                            <option value="E">E</option>
                                            <option value="F">F</option>
                                            <option value="G">G</option>
                                            <option value="H">H</option>
                                            <option value="I">I</option>
                                            <option value="J">J</option>
                                            <option value="K">K</option>
                                            <option value="L">L</option>
                                            <option value="M">M</option>
                                            <option value="N">N</option>
                                            <option value="O">O</option>
                                            <option value="P">P</option>
                                            <option value="Q">Q</option>
                                            <option value="R">R</option>
                                            <option value="S">S</option>
                                            <option value="T">T</option>
                                            <option value="U">U</option>
                                            <option value="V">V</option>
                                            <option value="W">W</option>
                                            <option value="X">X</option>
                                            <option value="Y">Y</option>
                                            <option value="Z">Z</option>
                                        </select>
                                        
                                    <select class="form-control" id="selSentido" style="width:88px;display:inline-block;"/>
                                        <option value="">Seleccione</option>
                                        <option value="sur">Sur</option>
                                        <option value="bis">Bis</option>
                                    </select>
                                    <label id="lblNum" style="width:10px;display:inline-block;"> #</label>
                                    <input id="txtNum" type="text" class="inputCorto form-control" style="padding:0px;width:52px;display:inline-block;"></input>
                                    <select class="form-control" id="selLetra" style="width:88px;display:inline-block;"/>
                                        <option value="">Seleccione</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                        <option value="F">F</option>
                                        <option value="G">G</option>
                                        <option value="H">H</option>
                                        <option value="I">I</option>
                                        <option value="J">J</option>
                                        <option value="K">K</option>
                                        <option value="L">L</option>
                                        <option value="M">M</option>
                                        <option value="N">N</option>
                                        <option value="O">O</option>
                                        <option value="P">P</option>
                                        <option value="Q">Q</option>
                                        <option value="R">R</option>
                                        <option value="S">S</option>
                                        <option value="T">T</option>
                                        <option value="U">U</option>
                                        <option value="V">V</option>
                                        <option value="W">W</option>
                                        <option value="X">X</option>
                                        <option value="Y">Y</option>
                                        <option value="Z">Z</option>
                                    </select>
                                    <input id="txtNum2" type="number" class="inputCorto form-control" style="padding:0px;width:88px;display:inline-block;"></input>
                                    <select class="form-control" id="selSentido2" style="width:80px;display:inline-block;"/>
                                        <option value="">Seleccione</option>
                                        <option value="sur">Sur</option>
                                        <option value="bis">Bis</option>
                                    </select>
                                    {!!Form::select('idCiudad', $ciudades, 8, ["class"=>"form-control form-control-input selectWzrd","placeholder"=>"Seleccione","id"=>"idCiudad","style" => "    width: 100px !important;   display: inline-block;"])!!}
                                    <label id="lblDireccion"></label>
                                    <button id="btnDireccion" class="btn btn-primary  btn-cam-trans btn-sm" onclick="capturarDireccion()">Capturar</button>
                                
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p style="margin-top:5px;"><b>Árbol de decisión</b></p>
                            <label style="    color: green;">Seleccione la versión del árbol de decisiones en la que desea trabajar</label>
                            <select class="form-control" style="    margin-bottom: 12px;" onchange="selectVersionArbol()" id="version_Arbol">
                                <option value="1" id="version_1">Versión 1</option>
                                <option value="2" id="version_2">Versión 2</option>
                            </select>
                        </div>
                    </div>

                <div class="row">
                    <div class="col-md-12">

                        

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row" style="margin-top:5px;">
                                    <div class="form-group has-feedback">
                                        <label for="id_incidencia" id="label1">Tipo de novedad reportada:</label>
                                        {!!Form::select('selectArbol1', $arbol, null, ["class"=>"form-control","placeholder"=>"Seleccione","id"=>"selectArbol1"])!!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row" style="margin-top:5px;">
                                    <div class="form-group has-feedback">
                                        <label for="selectComponente" id="label2">Componente:</label>
                                        <select name="selectComponente" type="text" class="form-control" id="selectComponente" >
                                        <option value="0">Seleccione</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="row" style="margin-top:5px;">
                                    <div class="form-group has-feedback">
                                        <label for="selectTipoFalla" id="label3">Tipo de falla:</label>
                                        <select name="selectTipoFalla" type="text" class="form-control" id="selectTipoFalla" >
                                        <option value="0">Seleccione</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row" style="margin-top:5px;">
                                    <div class="form-group has-feedback">
                                        <label for="selectRespuesta" id="label4">Respuesta:</label>
                                        <select name="selectRespuesta" type="text" class="form-control" id="selectRespuesta" >
                                        <option value="0">Seleccione</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <div id="panel_opciones">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="checbox_inhabilita">Inhabilitar vehículo:</label>
                                    <input name="checbox_inhabilita"  disabled type="checkbox"  id="checbox_inhabilita" style="    width: 21px;    height: 17px;    margin-left: 10px;"/>
                                    <label for="txt_tiempo_estimado">Tiempo estimado:</label>
                                    <input name="txt_tiempo_estimado" type="number"  id="txt_tiempo_estimado"/>
                                </div>
                            </div>


                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <button class="btn btn-primary  btn-cam-trans btn-sm" style=" margin-left: 12px;;padding:17px !important;width: 21%;" id="btn_1">Asistencia en sitio</button>
                                    <button class="btn btn-primary  btn-cam-trans btn-sm" style="padding:17px !important;width: 27%;" id="btn_2">Desplazamiento al taller sin grúa</button>
                                    <button class="btn btn-primary  btn-cam-trans btn-sm" style="padding:17px !important;width: 27%;" id="btn_3"> Desplazamiento de vehículo a la sede</button>
                                    <button class="btn btn-primary  btn-cam-trans btn-sm" style="display:none; padding:17px !important;width: 21%;" id="btn_4"> Otro</button>
                                    <button class="btn btn-primary  btn-cam-trans btn-sm" style="padding:17px !important;width: 21%;" id="btn_5"> Entrega propietario</button>
                                </div>
                            </div>    
                        </div>
                    </div>  
                </div>
                

                <div class="row" style="margin-top:5px;" id="panel_1">
                    <div class="form-group has-feedback">
                        <label for="select_tecnico_asignar">Técnico a asignar:</label>
                        <br>
                        <select name="select_tecnico_asignar" type="text" class="form-control" id="select_tecnico_asignar" style="    width: 96%;    display: inline-block;" >
                        <option value="0">Seleccione</option>
                        @foreach($tecnicos as $key => $valor)
                                <option value="{{$valor->identificacion}}">{{$valor->nombres}}</option>
                         @endforeach
                        </select>
                        <button  class="btn btn-primary  btn-cam-trans btn-sm" onclick="seleccionarMapa(1)" ><i class="fa fa-map-marker" aria-hidden="true"></i></button>
                    </div>
                </div>

                <div class="row" style="margin-top:5px;" id="panel_2">
                    <div class="form-group has-feedback">
                        <label for="taller_asignar">Taller a asignar:</label>
                        <br>
                        <select name="taller_asignar" type="text" class="form-control" id="taller_asignar" style="    width: 96%;    display: inline-block;" >
                        <option value="0">Seleccione</option>
                            @foreach($talleres as $key => $val)
                                @if($val->tipo == 1)
                                    <option value="{{$val->id}}">{{trim($val->nombre_proveedor)}}</option>
                                @endif
                            @endforeach

                        </select>
                        <button  class="btn btn-primary  btn-cam-trans btn-sm" onclick="seleccionarMapa(2)"><i class="fa fa-map-marker" aria-hidden="true"></i></button>
                    </div>
                </div>

                
                <div class="row">
                        <div class="col-md-6">
                            <div class="row" style="margin-top:5px;" id="panel_3">
                                <div class="form-group has-feedback">
                                    <label for="base_asignar">Base a asignar:</label>
                                    <br>
                                    <select name="base_asignar" type="text" class="form-control" id="base_asignar" style="    width: 92%;    display: inline-block;" >
                                    <option value="0">Seleccione</option>
                                        @foreach($talleres as $key => $val)
                                            @if($val->tipo == 2)
                                                <option value="{{$val->id}}">{{trim($val->nombre_proveedor)}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button  class="btn btn-primary  btn-cam-trans btn-sm" onclick="seleccionarMapa(3)"><i class="fa fa-map-marker" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                             <div class="row" style="margin-top:5px;" id="panel_4">
                                <div class="form-group has-feedback">
                                    <label for="tencnico_asignar2">Técnico a asignar:</label>
                                    <br>
                                    <select name="tencnico_asignar2" type="text" class="form-control" id="tencnico_asignar2" style="    width: 92%;    display: inline-block;" >
                                    <option value="0">Seleccione</option>
                                    @foreach($tecnicos as $key => $valor)
                                        @if($valor->icono == 11)
                                            <option value="{{$valor->identificacion}}">{{$valor->nombres}}</option>
                                        @endif
                                     @endforeach
                                    </select>
                                    <button  class="btn btn-primary  btn-cam-trans btn-sm" onclick="seleccionarMapa(4)"><i class="fa fa-map-marker" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="row" style="margin-top:5px;" id="panel_5">
                    <div class="form-group has-feedback">
                        <label for="txt_otro_dato">Otro:</label>
                        <br>
                        <input name="txt_otro_dato" type="text" class="form-control" id="txt_otro_dato" style="    width: 96%;    display: inline-block;" >
                    </div>
                </div>

                <div class="row" style="margin-top:5px;" id="panel_novedades">
                    <p><b>Acciones</b></p>
                    <div id="novedades_txt">
                        <div id="img_consulta_ajax">
                        </div>
                        
                        <div class="novedad_user" id="novedades_no_tiene">
                            
                        </div>
                        <div id="novedades_user">
                            <div class="novedad_user">
                                <p class="user"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Alejandra Quintero Espinosa</p>
                                <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i> 2017-01-02 29:12:21</p>
                                <p class="content"><i class="fa fa-commenting-o" aria-hidden="true"></i>&quot;Se agrega notificación de X y Y Z, cada x tiempor&quot;</p>
                            </div>

                            <div class="novedad_user">
                                <p class="user"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Alejandra Quintero Espinosa</p>
                                <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i> 2017-01-02 29:12:21</p>
                                <p class="content"><i class="fa fa-commenting-o" aria-hidden="true"></i>&quot;Se agrega notificación de X y Y Z, cada x tiempor&quot;</p>
                            </div>

                            <div class="novedad_user">
                                <p class="user"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Alejandra Quintero Espinosa</p>
                                <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i> 2017-01-02 29:12:21</p>
                                <p class="content"><i class="fa fa-commenting-o" aria-hidden="true"></i>&quot;Se agrega notificación de X y Y Z, cada x tiempor Se agrega notificación de X y Y Z, cada x tiempor Se agrega notificación de X y Y Z, cada x tiempor&quot;</p>
                            </div>

                            <div class="novedad_user">
                                <p class="user"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Alejandra Quintero Espinosa</p>
                                <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i> 2017-01-02 29:12:21</p>
                                <p class="content"><i class="fa fa-commenting-o" aria-hidden="true"></i>&quot;Se agrega notificación de X y Y Z, cada x tiempo Se agrega notificación de X y Y Z, cada x tiempor&quot;</p>
                            </div>

                            <div class="novedad_user">
                                <p class="user"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Alejandra Quintero Espinosa</p>
                                <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i> 2017-01-02 29:12:21</p>
                                <p class="content"><i class="fa fa-commenting-o" aria-hidden="true"></i>&quot;Se agrega notificación de X y Y Z, cada x tiempor Se agrega notificación de X y Y Z, cada x tiempor Se agrega notificación de X y Y Z, cada x tiempor&quot;</p>
                            </div>

                            <div class="novedad_user">
                                <p class="user"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Alejandra Quintero Espinosa</p>
                                <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i> 2017-01-02 29:12:21</p>
                                <p class="content"><i class="fa fa-commenting-o" aria-hidden="true"></i>&quot;Se agrega notificación de X y Y Z, cada x tiempor Se agrega notificación de X y Y Z, cada x tiempor Se agrega notificación de X y Y Z, cada x tiempor&quot;</p>
                            </div>
                        </div>
                        
                        <div id="crear_novedad">
                            
                        </div>
                        

                        
                        

                    </div>
                </div>

            </div>

            <div class="col-md-4" style="margin-bottom: 20px;    padding: 0px;">


                    <div class="panel-group" style="    margin-top: 16px;    margin-bottom: 8px;">
                          <div class="panel panel-default">
                            <div class="panel-heading" style="background:#0287ec;color:white">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse1"><b> <i class="fa fa-hand-o-right"></i> Datos del vehículo seleccionado</b></a>
                              </h4>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse">
                              <div class="panel-body">
                                  
                                  <p id="txt_panel_datos_incidencia"><b>No existe información para mostrar</b></p>
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw" id="loading_panel_datos_incidencia" style="    font-size: 28px;    display: block;    margin: auto;"></i>
                        <div id="panel_datos_incidencia" style="    display: block;   width: 100%;    padding: 4px; ">

                            <table class="table table-striped table-bordered">
                            
                            <tbody>
                                <tr>
                                    <td style="width: 50%;"><b>Proyecto</b></td>
                                    <td style="width: 50%;" id="txt_tipo_proyecto"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Estado</b></td>
                                    <td style="width: 50%;" id="txt_estado_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Tipo vehículo</b></td>
                                    <td style="width: 50%;" id="txt_tipo1_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Tipo de vehículo CAM</b> </td>
                                    <td style="width: 50%;" id="txt_tipo2_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Ciudad</b></td>
                                    <td style="width: 50%;" id="txt_ciudad_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Clase</b></td>
                                    <td style="width: 50%;" id="txt_clase_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Marca</b></td>
                                    <td style="width: 50%;" id="txt_marca_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Modelo</b></td>
                                    <td style="width: 50%;" id="txt_modelo_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Vinculo</b></td>
                                    <td style="width: 50%;" id="txt_binculo_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Transmisión</b></td>
                                    <td style="width: 50%;" id="txt_trasmi_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Color</b></td>
                                    <td style="width: 50%;" id="txt_color_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Combustible</b></td>
                                    <td style="width: 50%;" id="txt_combustible_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Línea</b></td>
                                    <td style="width: 50%;" id="txt_linea_veh"></td>
                                </tr>

                                <tr>
                                    <td style="width: 50%;"><b>Pasajeros</b></td>
                                    <td style="width: 50%;" id="txt_pasajeros_veh"></td>
                                </tr>

                            </tbody>
                            </table>
                            

                        </div>
                              </div>
                            </div>
                          </div>
                    </div>


                    <div class="panel-group" style="    margin-bottom: 10px;">
                          <div class="panel panel-default">
                            <div class="panel-heading" style="background:#0287ec;color:white">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse2"><b> <i class="fa fa-hand-o-right"> </i> Últimas 5 incidencias generadas del vehículo</b></a>
                              </h4>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse">
                              <div class="panel-body">
                                  <p id="txt_panel_ultimas_incidencias"><b>No existe información para mostrar</b></p>

                                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw" id="loading_panel_ultimas_incidencias" style="    font-size: 28px;    display: block;    margin: auto;"></i>

                                    <div id="panel_ultimas_incidencias">
                                        
                                    </div>
                              </div>
                            </div>
                          </div>
                    </div>

                    <div class="panel-group" style="    margin-bottom: 10px;">
                          <div class="panel panel-default">
                            <div class="panel-heading" style="background:#0287ec;color:white">
                              <h4 class="panel-title">
                                <a data-toggle="collapse" href="#collapse3"><b><i class="fa fa-hand-o-right"></i> Últimas 5 kilometrajes reportados del vehículo</b></a>
                              </h4>
                            </div>
                            <div id="collapse3" class="panel-collapse collapse">
                              <div class="panel-body">

                                     <p id="txt_panel_ultimas_kilometrajes"><b>No existe información para mostrar</b></p>

                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw" id="loading_panel_ultimas_kilometrajes" style="    font-size: 28px;    display: block;    margin: auto;"></i>


                        <div id="panel_ultimas_kilometrajes">
                            
                        </div>
                              </div>
                            </div>
                          </div>
                    </div>

                   
                </div>


               

                



                


            <div class="modal-footer">
                     @if($acceso == "W")
                    <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;    position: relative;    left: -12%;" id="save_incidencia">Guardar</button>
                    @endif

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="    position: relative;    left: -12%;">Cerrar</button>
                   
            </div>

            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function consultaInformacionVehiculo()
    {

        document.querySelector("#version_1").disabled = false;
        document.querySelector("#version_2").disabled = false;

        


        var datos = {
            placa: document.querySelector("#txt_vehiculo_incidencia").value,
            incidencia : document.querySelector("#id_incidencia").value
        };

        document.querySelector("#txt_panel_datos_incidencia").style.display = "none";
        document.querySelector("#loading_panel_datos_incidencia").style.display = "block";
        document.querySelector("#panel_datos_incidencia").style.display = "none";

        document.querySelector("#txt_panel_ultimas_incidencias").style.display = "none";
        document.querySelector("#loading_panel_ultimas_incidencias").style.display = "block";
        document.querySelector("#panel_ultimas_incidencias").style.display = "none";


        document.querySelector("#txt_panel_ultimas_kilometrajes").style.display = "none";
        document.querySelector("#loading_panel_ultimas_kilometrajes").style.display = "block";
        document.querySelector("#panel_ultimas_kilometrajes").style.display = "none";


        $.ajax({
        url: '{{ url('/') }}/consultaInformacionVehiculoIncidencias',
        type: "POST",
        headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
        dataType: "json",
        data:datos,
        timeout:3000000,
        success:function(data)
        {
            document.querySelector("#version_Arbol").value = "1";

            if(data.inci_actual == "" || data.inci_actual == null)
            {

                @if($configuracion->status_arbol_v1 == "0")
                    document.querySelector("#version_1").disabled = true;
                @endif

                @if($configuracion->status_arbol_v2 == "0")
                    document.querySelector("#version_2").disabled = true;
                @endif
            }
            else
            {
                

                //Validación estado del árbol de decisiones
                if(data.versionActualIncidencia == 1)
                {
                    document.querySelector("#version_1").disabled = false;
                }

                if(data.versionActualIncidencia == 2)
                {
                    document.querySelector("#version_2").disabled = false;
                    document.querySelector("#version_Arbol").value = "2";
                }

                //Validar si existe la incidencia y la versión con la que fue guardada
                if(data.versionActualIncidencia == 0)
                {
                    document.querySelector("#version_1").disabled = true;
                    document.querySelector("#version_2").disabled = true;
                }
            }      

            if(document.querySelector("#version_Arbol").value == "1")
            {
                document.querySelector("#label1").innerHTML = "Tipo de novedad reportada:";
                document.querySelector("#label2").innerHTML = "Componente:";
                document.querySelector("#label3").innerHTML = "Tipo de falla:";
                document.querySelector("#label4").innerHTML = "Respuesta:";
            }else
            {
                document.querySelector("#label1").innerHTML = "Ubicación de daño:";
                document.querySelector("#label2").innerHTML = "Sistema de fallo asociado:";
                document.querySelector("#label3").innerHTML = "Componente:";
                document.querySelector("#label4").innerHTML = "Novedad reportada:";
            }


            data.promedioRecorrdio = data.promedioRecorrdio.replace(".000","");
            document.querySelector("#recorrido_promedio").value = data.promedioRecorrdio;
            document.querySelector("#loading_panel_datos_incidencia").style.display = "none";
            document.querySelector("#loading_panel_ultimas_incidencias").style.display = "none";
            document.querySelector("#loading_panel_ultimas_kilometrajes").style.display = "none";

            if(data.incidencias.length == 0)
                document.querySelector("#txt_panel_ultimas_incidencias").style.display = "block";
            else
            {
                document.querySelector("#panel_ultimas_incidencias").style.display = "block";
            }

            if(data.kilometrajes.length == 0)
                document.querySelector("#txt_panel_ultimas_kilometrajes").style.display = "block";
            else
            {
                document.querySelector("#panel_ultimas_kilometrajes").style.display = "block";
            }

            var html = '<table class="table table-striped table-bordered">  <tbody>';
            var contador = data.incidencias.length;
            for (var i = 0; i < data.incidencias.length; i++) {

                html += "<tr>"; 

                html += "<td rowspan='3' style='    font-size: 20px;    padding: 12px;    vertical-align: middle;'>" + contador + "</td>";
                html += "<td ><b>N°:</b> " + data.incidencias[i].incidencia + "</td>";
                html += "<td ><b>Fecha generación:</b> " + data.incidencias[i].fecha_servidor.split(".")[0] + "</td>";
                html += "</tr><tr>"; 
                var color = "#046EF5";
                if(data.incidencias[i].id_estado == "E02")
                    color = "#943CCB";
                if(data.incidencias[i].id_estado == "E03")
                    color = "#FFD53B";
                if(data.incidencias[i].id_estado == "E04")
                    color = "#D70823";
                if(data.incidencias[i].id_estado == "E05")
                    color = "#FB862D";
                if(data.incidencias[i].id_estado == "E06")
                    color = "#94DD58";
                if(data.incidencias[i].id_estado == "E07")
                    color = "#895430";
                html += "<td colspan='2' style='background:" + color + ";color:white '><b>Estado:</b> " + data.incidencias[i].estadoInci + "</td></tr>";
                html += "</tr><tr>"; 
                html += "<td colspan='2'><b>Observación:</b> " + data.incidencias[i].observacion + "</td>";

                html += "<tr style='height:10px;'></tr>"; 
                contador--;
                html += "</tr>";   
            }
            html += "</tbody></table>";   

            document.querySelector("#panel_ultimas_incidencias").innerHTML = html;

            html = '<table class="table table-striped table-bordered">  <tbody>';
            var contador = data.kilometrajes.length;
            for (var i = 0; i < data.kilometrajes.length; i++) {

                html += "<tr>"; 

                html += "<td rowspan='2' style='    font-size: 20px;    padding: 12px;    vertical-align: middle;'>" + contador + "</td>";
                html += "<td ><b>K:M</b> " + data.kilometrajes[i].kilometraje.split(".")[0] + "</td>";
                html += "<td ><b>Fecha reporte:</b> " + data.kilometrajes[i].fecha_servidor.split(".")[0] + "</td>";

                html += "<td rowspan='2' style='    font-size: 20px;    padding: 12px;    vertical-align: middle;'><i class='fa fa-times' style='cursor:pointer;color:red;' data-id='" + data.kilometrajes[i].id + "' onclick='deleteKilometraje(this)'></i></td>";

                html += "</tr><tr>"; 
                
                html += "<td colspan='2'><b>Observación:</b> " + data.kilometrajes[i].observaciones + "</td></tr>";



                html += "<tr style='height:10px;'></tr>"; 
                contador--;  
            }
            html += "</tbody></table>";   


            /*for (var i = 0; i < data.kilometrajes.length; i++) {
                html += "<div style='    border: 1px solid #a29b9b;    padding: 2px;    margin-bottom: 3px;'>";
                html += "<p style='  margin: 0px;    font-size: 12px;    display: block;    width: 49%;    float: left;text-align: center;  '><b># " + contador + " <i class='fa fa-times' style='cursor:pointer;color:red;' data-id='" + data.kilometrajes[i].id + "' onclick='deleteKilometraje(this)'></i></b><br>KM:" +  + "</p>";
                html += "<p style='  margin: 0px;    font-size: 12px;    display: block;    width: 49%;    float: left;text-align: center;  border-left: 1px solid #a29b9b;'> " + data.kilometrajes[i].fecha_servidor + "</p>";
                html += "<p style='  margin: 0px;    font-size: 10px;    display: block;    width: 100%;    clear: both;text-align: center;    border-top: 1px solid #a29b9b;'>" + data.kilometrajes[i]. + "</p>";

                contador--;
                html += "</div>";   
            }*/

            document.querySelector("#panel_ultimas_kilometrajes").innerHTML = html;

            document.querySelector("#txt_tipo_proyecto").innerHTML = data.datosVehiculo.contrato;
            document.querySelector("#txt_estado_veh").innerHTML = data.datosVehiculo.estado;
            document.querySelector("#txt_tipo1_veh").innerHTML = data.datosVehiculo.nombreCAM;
            document.querySelector("#txt_tipo2_veh").innerHTML = data.datosVehiculo.tipoV;
            document.querySelector("#txt_ciudad_veh").innerHTML = data.datosVehiculo.ciudad;
            document.querySelector("#txt_clase_veh").innerHTML = data.datosVehiculo.clase;
            document.querySelector("#txt_marca_veh").innerHTML = data.datosVehiculo.marca;
            document.querySelector("#txt_binculo_veh").innerHTML = data.datosVehiculo.vinculo;
            document.querySelector("#txt_modelo_veh").innerHTML = data.datosVehiculo.modelo;
            document.querySelector("#txt_trasmi_veh").innerHTML = data.datosVehiculo.trasmi;
            document.querySelector("#txt_color_veh").innerHTML = data.datosVehiculo.color;
            document.querySelector("#txt_combustible_veh").innerHTML = data.datosVehiculo.combustible;
            document.querySelector("#txt_linea_veh").innerHTML = data.datosVehiculo.linea;
            document.querySelector("#txt_pasajeros_veh").innerHTML = data.datosVehiculo.combustible;

            document.querySelector("#panel_datos_incidencia").style.display = "block";

        },
        error:function(request,status,error){
          
        }

    });
    }


    function deleteKilometraje(ele)
    {
        if(confirm("¿Seguro que desea eliminar el kilometraje?"))
        {
             $.ajax({
                url: '{{ url('/') }}/rutaInsercionTransporte',
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:{
                    id : ele.dataset.id,
                    opc : 33,
                    placa: document.querySelector("#txt_vehiculo_incidencia").value
                },
                timeout:3000000,
                success:function(data)
                {
                    consultaInformacionVehiculo();
                    mostrarModal(2,null,"EXITO","Se ha eliminado correctamente el odometro del vehículo.\n",0,"Aceptar","",null); 
                },
                error:function(request,status,error){
                  
                }

            });

        }
    }

    function selectVersionArbol(pru,tipo)
    {
        if(document.querySelector("#version_Arbol").value == "1")
        {
            document.querySelector("#label1").innerHTML = "Tipo de novedad reportada:";
            document.querySelector("#label2").innerHTML = "Componente:";
            document.querySelector("#label3").innerHTML = "Tipo de falla:";
            document.querySelector("#label4").innerHTML = "Respuesta:";
        }else
        {
            document.querySelector("#label1").innerHTML = "Ubicación de daño:";
            document.querySelector("#label2").innerHTML = "Sistema de fallo asociado:";
            document.querySelector("#label3").innerHTML = "Componente:";
            document.querySelector("#label4").innerHTML = "Novedad reportada:";
        }

        document.querySelector("#selectArbol1").innerHTML = "";
        document.querySelector("#selectComponente").innerHTML = "";
        document.querySelector("#selectTipoFalla").innerHTML = "";
        document.querySelector("#selectRespuesta").innerHTML = "";

        $.ajax({
                url: '{{ url('/') }}/updateArbolDecision',
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:{
                    version : document.querySelector("#version_Arbol").value,
                    opc : "7"
                },
                timeout:3000000,
                success:function(data)
                {
                    var datos = "";

                    datos += "<option>Seleccione</option>";
                    for (var i = 0; i < data.length; i++) {

                        datos += "<option value='" + data[i]["id"] + "'>" + data[i]["descripcion"] + "</option>";
                    }

                    document.querySelector("#selectArbol1").innerHTML = datos;

                    if(pru == 1)
                    {
                        document.querySelector("#selectArbol1").value = tipo;
                        var datos = {
                          arbol: document.querySelector("#selectArbol1").value,
                          opc: 7,
                          version : document.querySelector("#version_Arbol").value
                        };
                        consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 2);
                    }
                },
                error:function(request,status,error){
                  
                }

            });
    }
</script>