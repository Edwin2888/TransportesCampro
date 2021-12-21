@if($proyecto == null)
    <br>
    <br>
    <br>
@endif

    <div style="width:100%;margin-left:30px;"  class="btns-section">
                    
                    <div class="dropdown" style="    width: 104px;    display: inline-block;">
                      <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-file"></i>  Archivo
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu">
                        <li>
                        <a href="{{url('/')}}/redes/ordenes/ver/" style="width:100%;" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-file"></i> &nbsp;&nbsp;Nuevo</a>
                        <button   type="button" id="btn-nuevo" style="display:none">
                    </button></li>
                            <li><button style="width:100%;" class="btn btn-primary btn-cam-trans btn-sm" type="button" name="guardar" value="guardar" id="btn-guardar"><i
                            class="fa fa-floppy-o"></i> &nbsp;&nbsp;
                            @if($proyec != '') 
                                Actualizar
                            @else
                                Guardar
                            @endif
                    </button></li>
                      </ul>
                    </div>
                 @if($proyec != "")

                    <div class="dropdown" style="    width: 104px;    display: inline-block;">
                      <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-upload"></i>  Importar
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu" style="padding-bottom:0px;">
                        <li><button class="btn btn-primary btn-cam-trans btn-sm"  type="button" id="btn_levantamiento_v2" style="width:100%;"><i class="fa fa-files-o"></i> &nbsp;&nbsp;Levantamiento v2
                        </button></li>
                            <li><button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn-importar" style="width:100%;"><i class="fa fa-upload"></i> &nbsp;&nbsp;Importar MO
                        </button></li>
                            <li><button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn-importar_1"  style="width:100%;"><i class="fa fa-upload"></i> &nbsp;&nbsp;Importar Material
                        </button></li>
                      </ul>
                    </div>


                    
                    <div class="dropdown" style="    width: 104px;    display: inline-block;">
                      <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-files-o"></i>  Agregar
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu" style="padding-bottom:0px;">
                        <li><button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn-gom-add" style="width:100%;"><i class="fa fa-files-o"></i> &nbsp;&nbsp;Agregar GOM
                    </button></li>
                            <li><button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn-descargo-add" style="width:100%;"><i class="fa fa-files-o"></i> &nbsp;&nbsp;Agregar Descargos
                    </button></li>
                      </ul>
                    </div>                    

                

                    <div class="dropdown" style="    width: 104px;    display: inline-block;">
                      <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-files-o"></i>  Ver
                      <span class="caret"></span></button>
                      <ul class="dropdown-menu" style="padding-bottom:0px;">
                        <li><button style="width:100%;" class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_ver_logs"><i class="fa fa-files-o"></i> &nbsp;&nbsp;Ver Logs
                    </button></li>
                            <li><button style="width:100%;" class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn-balance"><i class="fa fa-file"></i> &nbsp;&nbsp;Balance de materiales
                    </button></li>
                        <li><a style="width:100%;color:#0060AC" class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_levantamiento" href="{{config('app.Campro')[2]}}/campro/gop/rds/xls_informe_programado.php?id_proyecto={{$proyec}}"><i class="fa fa-download"></i> &nbsp;&nbsp;Levantamiento informe</a></li>
                    <li><a  style="width:100%;color:#0060AC" href="../../cam/programador/planner" target="_blanck"  class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-calendar"></i> &nbsp;&nbsp; Agendamiento</a></li>
                      </ul>
                    </div> 

                    

                    <button class="btn btn-primary btn-cam-trans btn-sm" style="display:none" type="button" id="btn_restricciones"><i class="fa fa-circle"></i> &nbsp;&nbsp;Restricciones
                    </button>

                    
                     @if($proyec != "")
                        <button style="    color: #fff;    background-color: #286090;    border-color: #204d74;" class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn-orden-trabajo"><i class="fa fa-calendar"></i> &nbsp;&nbsp;Ordenes de ManiObras
                        </button>

                        <!-- ==================================================================================================================== -->
                        <!-- Boton Modal Reporte Novedades Personal -->
                        <!-- ==================================================================================================================== -->
                        <?php if($proyecto[0]->tipo_proyecto === 'T04'): ?>
                          <a  href="#" 
                              class="btn btn-primary btn-sm btn-cam-trans"
                              data-toggle="modal"
                              data-target="#frm_cambiar_gom"
                              role="button"
                              aria-haspopup="true"
                              aria-expanded="false">
                              <i class="fa fa-pencil-square-o"></i>
                              Cambiar GOM
                          </a>
                        <?php endif; ?>

                        <!-- ==================================================================================================================== -->
                        <!-- Modal Cambiar GOM OT -->
                        <!-- ==================================================================================================================== -->
                        <div class="modal fade" id="frm_cambiar_gom" tabindex="-2" role="dialog">
                          <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content modal-filter">
                              <div class="modal-header">
                                <button type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                                <h4 class="modal-title" id="form_titulo" style="color: white;">
                                  CAMBIAR GOM
                                </h4>
                              </div>
                            </div>
                            
                            <div class="modal-body" style="background-color: white;">
                              <div class="alert alert-danger">
                                Esta opción aún se encuentra en desarrollo. Por favor no utilizar.
                              </div>

                              <br/>

                              <form method="post"
                                    id="frm_master_reporte_cambiar_GOM"
                                    name="frm_master_reporte_cambiar_GOM"
                                    enctype="multipart/form-data"
                                    onkeypress="return event.keyCode!=13"
                                    data-toggle="validator"
                                    role="form"
                                    onsubmit="return false;">

                                    <input type="hidden" 
                                      name="proyecto__cambiar_gom__id_proyecto" 
                                      id="proyecto__cambiar_gom__id_proyecto" 
                                      value="<?php print $proyec; ?>" >

                                    <!-- ==================================================================================== -->
                                    <!-- GOM ACTUAL -->
                                    <!-- ==================================================================================== -->
                                    <div class="row">
                                      <div class="col col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">
                                                GOM ACTUAL:
                                            </label>

                                            <div class="input-group">
                                              <input type="text" 
                                                lass="form-control"
                                                name="proyecto__cambiar_gom__gom_actual" 
                                                id="proyecto__cambiar_gom__gom_actual" 
                                                value="<?php print "123456"; ?>"
                                                disabled="disabled"
                                                required>

                                              <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                      </div>

                                      <div class="col col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">
                                                GOM NUEVA:
                                            </label>

                                            <div class="input-group">
                                              <input type="text" 
                                                lass="form-control"
                                                name="proyecto__cambiar_gom__gom_nueva" 
                                                id="proyecto__cambiar_gom__gom_nueva" 
                                                value=""
                                                required>

                                              <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>

                                    <!-- ==================================================================================== -->
                                    <!-- SUBMIT BUTTON -->
                                    <!-- ==================================================================================== -->
                                    <div class="modal-footer">
                                      <button type="button" 
                                        class="btn btn-secondary" 
                                        data-dismiss="modal">
                                          Cerrar
                                      </button>

                                      <button type="submit"
                                        class="btn btn-primary btnguarda" 
                                        id="btn_submit_guardar_nueva_gom" 
                                        onclick="guardar_nueva_gom_proyecto()"
                                        data-tipo="2" 
                                        style="display: inline-block;">
                                          Actualizar GOM
                                      </button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <script type="text/javascript">
                          function guardar_nueva_gom_proyecto() {
                            $('#btn_submit_guardar_nueva_gom').attr('disabled', true).text('Actualizando GOM...');
                            
                            var error = false;
                            var mensaje_error = '';
                            var formData = null;

                            var id_proyecto = $('#proyecto__cambiar_gom__id_proyecto').val().trim();
                            var gom_actual  = $('#proyecto__cambiar_gom__gom_actual').val().trim();
                            var gom_nueva   = $('#proyecto__cambiar_gom__gom_nueva').val().trim();

                            if(!id_proyecto || !gom_actual || !gom_nueva) {
                              error = true;
                              mensaje_error = 'Todos los campos son obligatorios.';
                            }

                            // ================================================================================================
                            // Se obtienen todos los valores del formulario y los archivos
                            // ================================================================================================

                            if(!error) {
                                var formElement = document.getElementById("frm_master_reporte_cambiar_GOM");                                
                                formData = new FormData(formElement);

                                formData.append('id_proyecto',  id_proyecto);
                                formData.append('gom_actual',   gom_actual);
                                formData.append('gom_nueva',    gom_nueva);

                                $.ajax({
                                    type:         'POST',
                                    url:          "<?php print url('/'); ?>/actualizarGOMProyecto",
                                    mimeType:     "multipart/form-data",
                                    data:         formData,
                                    dataType:     "json",

                                    contentType:  false,
                                    cache:        false,
                                    processData:  false,

                                    headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},

                                    success: function(data, textStatus) {
                                      $('#btn_submit_guardar_nueva_gom').attr('disabled', false).text('Actualizar GOM');
                                      console.log('server response');
                                      console.log(data);

                                      if(!data.error) {
                                        alert('La GOM ha sido actualizada correctamente.');
                                        location.reload();
                                      }
                                      else {
                                        alert(data.mensaje);
                                      }
                                    },
                                    error: function(data) {
                                      $('#btn_submit_guardar_nueva_gom').attr('disabled', false).text('Actualizar GOM');
                                      alert('Ha ocurrido un error al actualizar la GOM. Error: ' + data.statusText);
                                    }
                                });
                            }
                            else {
                              $('#btn_submit_guardar_nueva_gom').attr('disabled', false).text('Actualizar GOM');
                              alert(mensaje_error);
                            }
                          }
                        </script>

                    @endif

                        

                    

                @endif
                
                <a href="#" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir">Cerrar</a>
                
                </div> 
                
                
<div class="panel-body posisition-fixed-headaer">
                <div class="row">
                    <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="id_proyect">No. Proyecto:</label>
                                <input name="id_proyect" type="text" class="form-control" id="id_proyect" 
                                value="{{$proyec}}"  readonly/>
                            </div>
                    </div>

                    <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label for="nom_proyect">Nombre de proyecto:</label>
                                <input name="nom_proyect" type="text" class="form-control" id="nom_proyect" placeholder="Nombre de proyecto"
                                value="{{$proyecto == null ? '' : $proyecto[0]->nombre}}"/>
                            </div>
                    </div>

                    <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="fec_crea">Fecha recepción:</label>
                                <div class="input-group date form_date" data-date=""
                                     data-date-format="dd/mm/yyyy" style="width:170px;">
                                     <?php
                                        if($proyecto == null )
                                        {

                                            $fe = "";
                                        }
                                        else
                                        {

                                        $fecha = explode("-",$proyecto[0]->fecha_creacion);
                                        $fe = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
                                        }
                                     ?>
                                    <input class="form-control" size="16" style="height:30px;" type="text"
                                           value="{{$proyecto == null ? '' : $fe}}" name="fec_crea" id="fec_crea" placeholder="dd/mm/aaaa" required>
                                    <span class="input-group-addon"><i class="fa fa-times"></i></span>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                    </div>

                   
                    <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label for="per_a_cargo">Contacto proyecto:</label>
                                <input name="per_a_cargo" type="text" class="form-control" id="per_a_cargo" 
                                value="{{$proyecto == null ? '' : $proyecto[0]->info_contacto}}" placeholder="Contacto proyecto"/>
                            </div>
                    </div>

                    <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="per_a_cargo">Tipo proyecto:</label>
                                
                                <select name="id_proyecto_tipo" 
                                  id="id_proyecto_tipo" 
                                  class="form-control"
                                  onchange="validar_tipo_proyecto_seleccionado()" 
                                  required> 

                                    <option value="0">Seleccione</option>
                                    @if($proyecto == null)
                                        @foreach($proyectoA as $key => $val)
                                            <option value="{{$val->id_proyecto}}" >{{$val->des_proyecto}}</option>
                                        @endforeach
                                    @else
                                        @foreach($proyectoA as $key => $val)
                                            @if($val->id_proyecto == $proyecto[0]->tipo_proyecto)
                                                <option value="{{$val->id_proyecto}}" selected>{{$val->des_proyecto}}</option>
                                            @else
                                                <option value="{{$val->id_proyecto}}" >{{$val->des_proyecto}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="val_ini">Valor inicial:</label>
                                <input name="val_ini" type="text" class="form-control" id="val_ini" 
                                value="{{$proyecto == null ? '' : $proyecto[0]->valor_inicial}}" placeholder="Valor inicial"/>
                            </div>
                    </div>

                     <div class="col-md-2" id="wrapper_linea">
                            <div class="form-group has-feedback selectpicker">
                                <label for="linea">Líneas:</label>
                                <select id="linea" 
                                  class="selectpicker" 
                                  multiple 
                                  style="height: 30px;padding-top:2px;padding-bottom:2px;">
                                    @if($proyecto != null)
                                      @if($proyecto[0]->lv == "1")
                                          <option value="1" selected>Línea viva</option>
                                      @else
                                          <option value="1">Línea viva</option>
                                      @endif

                                      @if($proyecto[0]->lm == "1")
                                          <option value="2" selected>Línea muerta</option>
                                      @else
                                          <option value="2">Línea muerta</option>
                                      @endif


                                    @else
                                      <option value="1">Línea viva</option>
                                      <option value="2">Línea muerta</option>
                                    @endif
                                </select>
                            </div>
                    </div>

                     <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="diagrama" style="display:block">Diagrama:</label>
                                @if($proyecto != null)
                                    @if($proyecto[0]->diagrama == "1")
                                        <input id="diagrama" type="checkbox" class="form-control" checked>
                                    @else
                                        <input id="diagrama" type="checkbox" class="form-control">
                                    @endif
                                  @else
                                    <input id="diagrama" type="checkbox" class="form-control">
                                  @endif
                                
                            </div>
                    </div>


                    <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="id_zona">Zona:</label>
                                <input type="hidden" value="{{$proyecto == null ? 0 : $proyecto[0]->zona}}" id="id_zona_hidden" />
                                <select name="id_zona" id="id_zona" class="form-control"   required> 
                                    <option value="0">Seleccione</option>
                                    <option value="SUR">SUR</option>
                                    <option value="NORTE">NORTE</option>
                                    <option value="CENTRO">CENTRO</option>
                                    <option value="AFUERA_DE_BOGOTA">AFUERA DE BOGOTA</option>
                                </select>
                            </div>
                    </div>

                    <div class="col-md-2" id="wrapper_id_circuito">
                            <div class="form-group has-feedback">
                                <label for="id_circuito">Circuito:</label>
                                <input type="hidden" value="{{$proyecto == null ? 0 : $proyecto[0]->zona}}" id="id_circuito_hidden" />
                                <div class="col-md-10">
                                    <select name="id_circuito" id="id_circuito" class="selectpicker form-control"   required data-live-search="true"> 
                                    <option value="0">Seleccione</option>
                                    @foreach($circuitos as $cirs => $val)
                                         @if($proyecto != null)
                                            @if($val->id_circuito == $proyecto[0]->cod_cto)
                                                <option value="{{$val->id_circuito}}" selected>{{$val->nombre_cto}}</option>
                                            @else
                                                <option value="{{$val->id_circuito}}">{{$val->nombre_cto}}</option>
                                            @endif
                                         @else
                                            <option value="{{$val->id_circuito}}">{{$val->nombre_cto}}</option>
                                         @endif                                        
                                    @endforeach
                                </select>
                                </div>
                                <div class="col-md-1" style="padding:0px;   ">
                                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="eventoModalAddCircuito()"><i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                    </div>

                    <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="num_alcaldia">No Alcaldía O Municipio:</label>
                                <input name="num_alcaldia" type="text" class="form-control" id="num_alcaldia" 
                                value="{{$proyecto == null ? '' : $proyecto[0]->municipio}}" placeholder="No Alcaldía"/>
                            </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                                <label for="barrio">Barrio / Vereda:</label>
                                <input name="barrio" type="text" class="form-control" id="barrio" title=""
                                value="{{$proyecto == null ? '' : $proyecto[0]->barrio}}" placeholder="Barrio / Vereda" />
                            </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group has-feedback">
                                <label for="dire">Zona de Influencia/Dirección:</label>
                                <input name="dire" name="dire" type="text" class="form-control" id="dire" title=""
                                value="{{$proyecto == null ? '' : $proyecto[0]->direccion}}" placeholder="Zona de influencia/Dirección" />
                            </div>
                    </div>

                    <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="tipo_trabajo">Tipo de trabajo:</label>
                                <select name="tipo_trabajo" id="tipo_trabajo" class="form-control"   required> 
                                @if($proyecto == null)
                                    <option value="DIURNO">DIURNO</option>
                                    <option value="NOCTURNO">NOCTURNO</option>
                                @else
                                    @if($proyecto[0]->tipo_trabajo == "NOCTURNO")
                                        <option value="DIURNO">DIURNO</option>
                                        <option value="NOCTURNO" selected>NOCTURNO</option>
                                    @else
                                        <option value="DIURNO" selected>DIURNO</option>
                                        <option value="NOCTURNO">NOCTURNO</option>
                                    @endif
                                @endif                                    
                                </select>
                            </div>
                    </div>
                
                    <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="tipo_proceso">Tipo de proceso:</label>
                                <select name="tipo_proceso" id="tipo_proceso" class="form-control"   required> 
                                    <?php /*
                                            @if($proyecto == null)
                                                <option value="NO_APLICA">No aplica</option>
                                                <option value="EXTRACALIDAD">EXTRACALIDAD</option>
                                                <option value="POT">POT</option>
                                            @else
                                                @if($proyecto[0]->tipo_proceso == "EXTRACALIDAD")
                                                    <option value="NO_APLICA">No aplica</option>
                                                    <option value="EXTRACALIDAD" selected>EXTRACALIDAD</option>
                                                    <option value="POT">POT</option>
                                                @else
                                                    @if($proyecto[0]->tipo_proceso == "POT")
                                                        <option value="NO_APLICA">No aplica</option>
                                                        <option value="EXTRACALIDAD">EXTRACALIDAD</option>
                                                        <option value="POT" selected>POT</option>
                                                    @else
                                                        <option value="NO_APLICA" selected>No aplica</option>
                                                        <option value="EXTRACALIDAD">EXTRACALIDAD</option>
                                                        <option value="POT">POT</option>
                                                    @endif
                                                @endif
                                            @endif
                                         */ ?>                                    
                                        <option value=""> - Seleccione - </option>
                                        <option value="NO_APLICA"   <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso== "NO_APLICA")?'selected':'' ?> disabled>NO APLICA</option>   

                                        <option value="EXTRACALIDAD"   <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso== "EXTRACALIDAD")?'selected':'' ?> >EXTRACALIDAD</option>   
                                        <option value="POT"            <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="POT")?'selected':'' ?> >POT</option>
                                        <option value="BARRIOSXAD"     <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="BARRIOSXAD")?'selected':'' ?> >BARRIOSXAD</option>
                                        <option value="CARTAS"         <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="CARTAS")?'selected':'' ?> >CARTAS</option>
                                        <option value="COMETAS"        <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="COMETAS")?'selected':'' ?> >COMETAS</option>
                                        <option value="CONEXMASURB"    <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="CONEXMASURB")?'selected':'' ?> >CONEXMASURB</option>
                                        <option value="EQUICOMBINADOS" <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="EQUICOMBINADOS")?'selected':'' ?> >EQUICOMBINADOS</option>
                                        <option value="IDU"            <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="IDU")?'selected':'' ?> >IDU</option>
                                        <option value="PMP"            <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="PMP")?'selected':'' ?> >PMP</option>
                                        <option value="PNC"            <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="PNC")?'selected':'' ?> >PNC</option>
                                        <option value="PODAS"          <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="PODAS")?'selected':'' ?> >PODAS</option>
                                        <option value="S/E"            <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="S/E")?'selected':'' ?> >S/E</option>
                                        <option value="TELECONTROL"    <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="TELECONTROL")?'selected':'' ?> >TELECONTROL</option>
                                        <option value="ELIMINAR"       <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="ELIMINAR")?'selected':'' ?>  disabled="disabled">ELIMINAR</option>
                                        <option value="PRUEBA"         <?= ( isset($proyecto[0]->tipo_proceso) && $proyecto[0]->tipo_proceso=="PRUEBA")?'selected':'' ?> disabled="disabled">PRUEBA</option>                           
                                </select>
                            </div>
                    </div>


                     <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label for="observaciones">Observación:</label>
                                <textarea id="observaciones" style="width:99%" class="form-control" rows="3"
                                                  required placeholder="Observación">{{$proyecto == null ? '' : $proyecto[0]->observaciones}}</textarea>
                            </div>
                    </div>

                </div>


              
            
                 

                 
                   
                    


                <button class="btn btn-primary btn-cam-trans btn-sm"  style="display:none" type="button" id="btn_cargar_estructura"><i class="fa fa-files-o"></i> &nbsp;&nbsp;Cargar Estructuras
                    </button>
               
</div>