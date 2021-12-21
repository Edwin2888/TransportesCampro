
    <div style="width:100%;margin-left:30px;" class="btns-section">
                        <a href="{{url('/')}}/redes/ordenes/ordentrabajo/{{$proyecto}}" id="btn-nuevo" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-file"></i>Nueva Maniobra</a>
                        
                        @if($encabezado != null)
                            @if($encabezado[0]->id_estado == "E1" || $encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "R0")
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" name="guardar" value="guardar" id="btn-guardar"><i
                                    class="fa fa-floppy-o"></i> &nbsp;&nbsp;
                                    @if($orden != '') 
                                        Actualizar
                                    @else
                                        Guardar
                                    @endif
                            </button>
                            @endif
                            @else
                                <button class="btn btn-primary btn-cam-trans btn-sm" type="button" name="guardar" value="guardar" id="btn-guardar"><i
                                    class="fa fa-floppy-o"></i> &nbsp;&nbsp;
                                    @if($orden != '') 
                                        Actualizar
                                    @else
                                        Guardar
                                    @endif
                                </button>
                            @endif
                        @if($encabezado != null )
                        <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_restricciones"><i class="fa fa-circle"></i> &nbsp;&nbsp;Restricciones</button>

                        <!-- ==================================================================================================================== -->
                        <!-- Boton Modal Reporte Novedades Personal -->
                        <!-- ==================================================================================================================== -->
                        <a  href="#" 
                            class="btn btn-primary btn-sm btn-cam-trans"
                            data-toggle="modal"
                            data-target="#frm_ver_bitacora_OT"
                            role="button"
                            aria-haspopup="true"
                            aria-expanded="false">
                            <i class="fa fa-pencil-square-o"></i>
                            Ver Bitácora
                        </a>

                        <!-- ==================================================================================================================== -->
                        <!-- Modal Ver Bitácora OT -->
                        <!-- ==================================================================================================================== -->
                        <div class="modal fade" id="frm_ver_bitacora_OT" tabindex="-2" role="dialog">
                          <div class="modal-dialog modal-lg" role="document" style="width: 95%;">
                            <div class="modal-content modal-filter">
                              <div class="modal-header">
                                <button type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                                <h4 class="modal-title" id="form_titulo" style="color: white;">
                                  BITÁCORA <?php print $orden; ?>
                                </h4>
                              </div>
                            </div>

                            <div class="modal-body" style="background-color: white;">
                              <!-- ============================================================================================================== -->
                              <!-- Boton Creación Evento Bitácora OT -->
                              <!-- ============================================================================================================== -->
                              <a  href="#" 
                                  class="btn btn-primary btn-sm btn-cam-trans"
                                  data-toggle="modal"
                                  data-target="#frm_creacion_evento_bitacora_OT"
                                  role="button"
                                  aria-haspopup="true"
                                  aria-expanded="false"
                                  onclick="limpiar_evento_bitacora_OT()">
                                    <i class="fa fa-pencil-square-o"></i>
                                    Crear Evento Bitácora
                              </a>

                              <hr/>

                              <!-- ============================================================================================================== -->
                              <!-- Listado Eventos Bitácora OT -->
                              <!-- ============================================================================================================== -->
                              <div>
                                <table class="table .table-striped ">
                                  <thead>
                                    <tr>
                                      <th>ID</th>
                                      <th>OT</th>
                                      <th>FECHA EVENTO</th>
                                      <th>ARCHIVO</th>
                                      <th>DESCRIPCIÓN</th>
                                      <th>USUARIO</th>
                                      <th>FECHA CREACIÓN</th>
                                      <th>FECHA ACTUALIZACIÓN</th>
                                      <th>ACCIONES</th>
                                    </tr>
                                  </thead>

                                  <tbody>
                                    
                                    <?php if(is_array($eventos_bitacora_orden) && count($eventos_bitacora_orden) > 0): ?>
                                      <?php foreach($eventos_bitacora_orden as $evento): ?>
                                        <?php
                                          $evento->fecha_evento         = str_replace('.000', '', $evento->fecha_evento);
                                          $evento->fecha_creacion       = str_replace('.000', '', $evento->fecha_creacion);
                                          $evento->fecha_actualizacion  = str_replace('.000', '', $evento->fecha_actualizacion);
                                          
                                          list($dummy_1, $dummy_2, $nombre_archivo_parte_1, $nombre_archivo_parte_2) = explode('_', $evento->ruta_archivo);
                                          $nombre_archivo_local = "{$nombre_archivo_parte_1}_{$nombre_archivo_parte_2}";
                                        ?>
                                        <tr>
                                          <td><?php print $evento->id_evento; ?></td>
                                          <td><?php print $evento->id_orden; ?></td>
                                          <td><?php print $evento->fecha_evento; ?></td>
                                          
                                          <td>
                                            <a href="http://190.60.248.195/telco/bitacora_orden/<?php print $evento->ruta_archivo; ?>"
                                              target="_blank"
                                              style="color: blue;">
                                                <?php print $nombre_archivo_local; ?>
                                            </a>
                                          </td>
                                          <td><?php print $evento->descripcion; ?></td>

                                          <td><?php print $evento->propietario; ?></td>
                                          <td><?php print $evento->fecha_creacion; ?></td>
                                          <td><?php print $evento->fecha_actualizacion; ?></td>

                                          <td>
                                            <!-- Editar Evento Bitácora OT -->
                                            <a href="#" 
                                              class="btn btn-primary btn-cam-trans btn-sm"
                                              title="Editar Evento"
                                              onclick="cargar_evento_bitacora_OT(
                                                          '<?php print $evento->id_evento; ?>', 
                                                          '<?php print $evento->fecha_evento; ?>',
                                                          '<?php print $evento->ruta_archivo; ?>',
                                                          '<?php print $evento->descripcion; ?>'
                                                      )">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            <!-- Eliminar Evento Bitácora OT -->
                                            <a href="#" 
                                              class="btn btn-primary btn-cam-trans btn-sm"
                                              title="Eliminar Evento"
                                              onclick="eliminar_evento_bitacora_OT('<?php print $evento->id_orden; ?>', '<?php print $evento->id_evento; ?>')">
                                                <i class="fa fa-trash" style="color: red;"></i>
                                            </a>
                                          </td>
                                        </tr>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <tr>
                                        <td colspan="8" style="text-align: center;">
                                          No existen eventos de bitácora relacionados a la OT <?php print $orden; ?>
                                        </td>
                                      </tr>
                                    <?php endif; ?>
                                  </tbody>
                                </table>
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
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- ==================================================================================================================== -->
                        <!-- Modal Creación Evento Bitácora OT -->
                        <!-- ==================================================================================================================== -->
                        <div class="modal fade" id="frm_creacion_evento_bitacora_OT" tabindex="-2" role="dialog">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content modal-filter">
                              <div class="modal-header">
                                <button type="button"
                                        class="close"
                                        data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                                <h4 class="modal-title" id="form_titulo" style="color: white;">
                                  CREACIÓN/ACTUALIZACIÓN EVENTO OT <?php print $orden; ?>
                                </h4>
                              </div>
                            </div>
                            
                            <div class="modal-body" style="background-color: white;">
                              <form method="post"
                                    id="frm_master_reporte_bitacora_OT"
                                    name="frm_master_reporte_bitacora_OT"
                                    enctype="multipart/form-data"
                                    onkeypress="return event.keyCode!=13"
                                    data-toggle="validator"
                                    role="form"
                                    onsubmit="return false;">

                                    <input type="hidden" name="bitacora__id_orden" id="bitacora__id_orden" value="<?php print $orden; ?>">

                                    <!-- ==================================================================================== -->
                                    <!-- ID EVENTO -->
                                    <!-- ==================================================================================== -->
                                    <div class="row">
                                      <div class="col col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">
                                                ID EVENTO:
                                            </label>

                                            <div class="input-group">
                                              <input type="text" 
                                                name="bitacora__id_evento" 
                                                id="bitacora__id_evento" 
                                                value=""
                                                disabled="disabled">
                                            </div>
                                        </div>
                                      </div>
                                    </div>

                                    <!-- ==================================================================================== -->
                                    <!-- FECHA EVENTO -->
                                    <!-- ==================================================================================== -->
                                    <div class="row">
                                      <div class="col col-md-12">
                                        <div class="form-group">
                                            <label for="bitacora__fecha_evento" class="control-label">
                                                FECHA EVENTO <span style="color:red;">(*)</span>:
                                            </label>

                                            <div class="input-group date-xxx form_datetime-xxx"
                                                 data-date=""
                                                 data-date-format="yyyy-mm-dd hh:ii">
                                                <input class="form-control"
                                                       size="16"
                                                       style="height:30px;"
                                                       type="text"
                                                       name="bitacora__fecha_evento"
                                                       id="bitacora__fecha_evento"
                                                       placeholder="aaaa-mm-dd hh:mm"
                                                       required />

                                                <span class="input-group-addon wrapper-fa-times"><i class="fa fa-times"></i></span>
                                                <span class="input-group-addon wrapper-fa-calendar"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                      </div>
                                    </div>

                                    <!-- ==================================================================================== -->
                                    <!-- ARCHIVO EVENTO -->
                                    <!-- ==================================================================================== -->
                                    <div class="row">
                                        <div class="col col-md-12">
                                            <div class="form-group">
                                                <label for="bitacora__archivo_evento" class="control-label">
                                                    ARCHIVO EVENTO <span id="bitacora__archivo_evento__obligatorio" style="color:red;">(*)</span>:
                                                </label>

                                                <div class="alert alert-danger" 
                                                  id="bitacora__archivo_evento__existente"
                                                  style="display: none;">
                                                    Ya existe un archivo relacionado al evento, si selecciona uno nuevo
                                                    el anterior sera reemplazado.
                                                </div>

                                                <input id="bitacora__archivo_evento" name="bitacora__archivo_evento[]" type="file" required>

                                                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                                <span class="help-block with-errors"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ==================================================================================== -->
                                    <!-- OBSERVACIONES EVENTO -->
                                    <!-- ==================================================================================== -->
                                    <div class="row">
                                        <div class="col col-md-12">
                                            <div class="form-group">
                                                <label for="bitacora__observaciones" class="control-label">
                                                    OBSERVACIONES <span style="color:red;">(*)</span>:
                                                </label>

                                                <textarea
                                                        name="bitacora__observaciones"
                                                        id="bitacora__observaciones"
                                                        class="form-control"
                                                        rows="6"
                                                        required></textarea>

                                                <div class="help-block with-errors"></div>
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
                                    id="btn_submit_guardar_bitacora" 
                                    onclick="guardar_evento_bitacora_OT()"
                                    data-tipo="2" 
                                    style="display: inline-block;">
                                      Crear/Actualizar Evento Bitácota
                                  </button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <script type="text/javascript">
                          function eliminar_evento_bitacora_OT(id_orden, id_evento) {
                            limpiar_evento_bitacora_OT();

                            if(confirm('En realidad desea eliminar el evento ' + id_evento + ' de la orden ' + id_orden + ' ?')) {
                              var formData = new FormData();
                              formData.append('bitacora__id_evento', id_evento);

                              $.ajax({
                                type:         'POST',
                                url:          "<?php print url('/'); ?>/eliminarEventoBitacoraOrden",
                                mimeType:     "multipart/form-data",
                                data:         formData,
                                dataType:     "json",

                                contentType:  false,
                                cache:        false,
                                processData:  false,

                                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},

                                success: function(data, textStatus) {
                                  if(!data.error) {
                                    location.reload();
                                  }
                                  else {
                                    alert(data.mensaje);
                                  }
                                },
                                error: function(data) {
                                  alert('Ha ocurrido un error al eliminar el evento. Error: ' + data.statusText);
                                }
                            });
                            }
                          }

                          function limpiar_evento_bitacora_OT() {
                            $('#bitacora__id_evento').val('');
                            $('#bitacora__fecha_evento').val('');
                            $('#bitacora__observaciones').val('');
                            $('#bitacora__archivo_evento').val('');

                            $('#bitacora__archivo_evento__existente').hide();
                            $('#bitacora__archivo_evento__obligatorio').show();
                            $('#bitacora__archivo_evento').attr('required', true);
                          }

                          function cargar_evento_bitacora_OT(id_evento, fecha_evento, ruta_archivo, descripcion) {
                            limpiar_evento_bitacora_OT();

                            $('#frm_creacion_evento_bitacora_OT').modal('show');
                            $('#bitacora__id_evento').val(id_evento);
                            $('#bitacora__fecha_evento').val(fecha_evento);
                            $('#bitacora__observaciones').val(descripcion);

                            $('#bitacora__archivo_evento__existente').show();
                            $('#bitacora__archivo_evento__obligatorio').hide();
                            $('#bitacora__archivo_evento').attr('required', false);
                          }

                          function guardar_evento_bitacora_OT() {
                            $('#btn_submit_guardar_bitacora').attr('disabled', true).text('Creando/Actualizando Evento Bitácora...');

                            var error = false;
                            var mensaje_error = '';
                            var formData = null;

                            // ================================================================================================
                            // Se valida la extensión de los archivos
                            // ================================================================================================
                            var files = document.getElementById("bitacora__archivo_evento").files;

                            for (var i = 0; i < files.length; i++) {
                                var file = files[i];

                                // Check the file type.
                                if (!file.type.match('application/pdf')) {
                                    error = true;
                                    mensaje_error = 'ERROR: EL ARCHIVO SELECCIONADO DEBE SER UN PDF.';
                                }
                            }

                            // ================================================================================================
                            // Se obtienen todos los valores del formulario y los archivos
                            // ================================================================================================

                            if(!error) {
                                var formElement = document.getElementById("frm_master_reporte_bitacora_OT");

                                var bitacora__id_evento     = $('#bitacora__id_evento').val();
                                var bitacora__id_orden      = $('#bitacora__id_orden').val();
                                var bitacora__fecha_evento  = $('#bitacora__fecha_evento').val();
                                var bitacora__observaciones = $('#bitacora__observaciones').val();
                                
                                formData = new FormData(formElement);

                                formData.append('bitacora__id_orden',       bitacora__id_orden);
                                formData.append('bitacora__id_evento',      bitacora__id_evento);
                                formData.append('bitacora__fecha_evento',   bitacora__fecha_evento);
                                formData.append('bitacora__observaciones',  bitacora__observaciones);

                                $.ajax({
                                    type:         'POST',
                                    url:          "<?php print url('/'); ?>/guardarEventoBitacoraOrden",
                                    mimeType:     "multipart/form-data",
                                    data:         formData,
                                    dataType:     "json",

                                    contentType:  false,
                                    cache:        false,
                                    processData:  false,

                                    headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},

                                    success: function(data, textStatus) {
                                      $('#btn_submit_guardar_bitacora').attr('disabled', false).text('Crear/Actualizar Evento Bitácora');
                                      console.log('server response');
                                      console.log(data);

                                      if(!data.error) {
                                        if(bitacora__id_evento) { alert('El evento de la bitacora de la orden ' + bitacora__id_orden + ' ha sido actualizado.'); }
                                        else { alert('El evento de la bitacora de la orden ' + bitacora__id_orden + ' ha sido creado.'); }

                                        location.reload();
                                      }
                                      else {
                                        alert(data.mensaje);
                                      }
                                    },
                                    error: function(data) {
                                      $('#btn_submit_guardar_bitacora').attr('disabled', false).text('Crear/Actualizar Evento Bitácora');
                                      alert('Ha ocurrido un error al crear/actualizar el evento. Error: ' + data.statusText);
                                    }
                                });
                            }
                            else {
                              $('#btn_submit_guardar_bitacora').attr('disabled', false).text('Crear/Actualizar Evento Bitácora');
                              alert(mensaje_error);
                            }
                          }
                        </script>

                        <!-- ==================================================================================================================== -->
                        <!-- Fin: Boton Modal Reporte Novedades Personal -->
                        <!-- ==================================================================================================================== -->
                    
                        @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "E4")
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_captura_eje" ><i class="fa fa-calendar"></i> &nbsp;&nbsp;Capturar ejecución
                            </button>

                        @if($encabezado[0]->id_estado == "E4" || $encabezado[0]->id_estado == "C2")
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_captura_conci"><i class="fa fa-calendar"></i> &nbsp;&nbsp;Capturar conciliación
                            </button>
                        @endif
                            <form action="{{config('app.Campro')[2]}}/campro/home" method="POST" target="_blank" style="display:inline-block">
                                <input type="hidden" name="user" value="{{Session::get('user_login')}}"/>
                                <input type="hidden" name="ruta" value="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pop_fotos.php?id_orden={{$orden}}"/>
                                <input type="submit" class="btn btn-primary btn-cam-trans btn-sm" value="Registro fotográfica ">
                                
                             </form>

                            @if($encabezado[0]->id_estado != "E4" && $encabezado[0]->id_estado != "C2")
                            <a class="btn btn-primary btn-cam-trans btn-sm" style="display:none" id="btn_finalizar_orden" href="#"><i class="fa fa-calendar"></i> &nbsp;&nbsp;Finalizar ejecución Maniobra
                            </a>
                            @endif
                            
                            <!--
                            {{config('app.Campro')[2]}}/campro/gop/rds/pdf_planilla_trabajo_programados.php.php?planilla=3026
                            {{config('app.Campro')[2]}}/campro/gop/rds/pdf_planilla_materiales.php?planilla=3026 
                            -->

                    
                        @endif

                        @if($encabezado[0]->id_estado == "E1")
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_captura_finaliza"><i class="fa fa-calendar"></i> &nbsp;&nbsp;Finalizar programación
                            </button>
                        @endif

                        @if($encabezado[0]->id_estado == "E4")
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_finalizar_conciliacion"><i class="fa fa-calendar"></i> &nbsp;&nbsp;Finalizar conciliación
                            </button>
                        @endif

                        @if($encabezado[0]->id_estado == "E1" || $encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "R0")
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_cancelar_orden"><i class="fa fa-arrows-alt"></i> &nbsp;&nbsp;Cancelar orden
                            </button>
                        @endif

                        @if($encabezado[0]->id_estado == "A1" &&  $encabezado[0]->fecha_reprogramacion == NULL )
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_reprogramacion_maniobra"><i class="fa fa-refresh"></i> &nbsp;&nbsp;Re programación de Maniobra
                            </button>
                        @endif

                        @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "R0")
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_abrir_orden"><i class="fa fa-open"></i> &nbsp;&nbsp;Abrir orden
                            </button>
                        @endif
                        <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_ver_logs"><i class="fa fa-files-o"></i> &nbsp;&nbsp;Ver Logs
                        </button>
                    @endif


                        <?php 
                            /////////////////////////////////////////////////////////////////////////////////////////
                            ////////////////////// inicio codigo para solicitu de miniobras  ////////////////////////
                            ///////////////////////////////////////////////////////////////////////////////////////// 
                        ?>
                        <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_ver_solicitud_maniobra"><i class="fa fa-files-o"></i> &nbsp;&nbsp;Solicitud de maniobra
                        </button>
                        <?php 
                            /////////////////////////////////////////////////////////////////////////////////////////
                            /////////////////////// final codigo para solicitu de miniobras  ////////////////////////
                            ///////////////////////////////////////////////////////////////////////////////////////// 
                        ?>
                    

                        <a href="#" id="btn-salir" style="display:none;" class="btn btn-primary btn-cam-trans btn-sm">Cerrar</a>

                        <a href="{{url('/')}}/redes/ordenes/ver/{{$proyecto}}"  class="btn btn-primary btn-cam-trans btn-sm">Cerrar</a>


        </div>

<div class="panel-body posisition-fixed-headaer">
    <div class="row">

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">No. Proyecto:</label>
                    <input name="id_proyect" type="text" class="form-control" id="id_proyect" 
                    value="{{$proyecto}}"  style="padding:0px;margin:0px;" readonly/>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Tipo Proyecto:</label>
                    <input name="id_proyect" type="text" class="form-control" 
                    value="{{$tipo}}"  readonly/>
                </div>
        </div>

        @if($orden != '')
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="text_nombre_proyect">Nombre de proyecto:</label>
                    <input name="text_nombre_proyect" id="text_nombre_proyect" type="text" class="form-control" placeholder="Nombre de proyecto" 
                    value="{{$proyNombre == null ? '' : $proyNombre[0]->nombre}}"  style="margin:0px;padding:0px;"  readonly/>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">No. Orden:</label>
                    @if($orden != '') 
                        <input name="id_orden" type="text" class="form-control" id="id_orden" 
                         readonly value="{{$orden}}"/>
                    @else
                        <input name="id_orden" type="text" class="form-control" id="id_orden" 
                    value="" readonly/>
                    
                    @endif
                    
                </div>
        </div>

        <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label for="id_orden">Estado:</label>
                        <input name="id_orden" type="text" class="form-control" id="id_orden"  readonly value="{{$encabezado[0]->id_estadoN}}"/>
                </div>
        </div>

        <div class="col-md-1">
                <div class="form-group has-feedback">
                    <label for="id_orden">Ver</label>
                    <br>
                        <button class="btn btn-primary btn-cam-trans btn-sm" data-toggle="collapse" data-target="#orden_encabezado"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
        </div>

        @else
            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label for="text_nombre_proyect">Nombre de proyecto:</label>
                    <input name="text_nombre_proyect" id="text_nombre_proyect" type="text" class="form-control" placeholder="Nombre de proyecto" 
                    value="{{$proyNombre == null ? '' : $proyNombre[0]->nombre}}"  style="margin:0px;padding:0px;" readonly/>
                </div>
            </div>

            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="id_orden">No. Orden:</label>
                            <input name="id_orden" type="text" class="form-control" id="id_orden"  value="" readonly/>
                    </div>
            </div>
        @endif



    </div>
    @if($orden != '') 
        <div class="collapse" id="orden_encabezado">
    @else
        <div>
    @endif

        <div class="row">
            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="fec_emision">Fecha de Emisión / Requerimiento:</label>
                        <div class="input-group date form_date no_select" data-date=""
                                         data-date-format="dd/mm/yyyy" style="width:170px;" id="fecha_emi">

                            <?php
                                if($encabezado == null )
                                {

                                    $fe = $fechaA;
                                }
                                else
                                {

                                $fecha = explode(" ",$encabezado[0]->fecha_emision)[0];
                                $fecha = explode("-",$fecha);
                                $fe = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
                                }
                             ?>

                            <input class="form-control" size="16" style="height:30px;" type="text"
                                               value="{{$fe}}" name="fec_emision" id="fec_emision" placeholder="dd/mm/aaaa" required>
                            <span class="input-group-addon"><i class="fa fa-times"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
            </div>

            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="fec_programacion">Fecha Programación:</label>
                        <div class="input-group date form_date no_select" data-date=""
                                         data-date-format="dd/mm/yyyy" style="width:170px;">


                            <?php
                                if($encabezado == null )
                                {

                                    $fe = $fechaA;
                                }
                                else
                                {
                                $fecha = explode(" ",$encabezado[0]->fecha_programacion)[0];
                                $fecha = explode("-",$fecha);
                                $fe = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
                                }
                             ?>

                            <input class="form-control" size="16" style="height:30px;" type="text"
                                               value="{{$fe}}" name="fec_programacion" id="fec_programacion" placeholder="dd/mm/aaaa" required>
                            <span class="input-group-addon"><i class="fa fa-times"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
            </div>


            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="fech_ejecucion">Fecha de I. Ejecución:</label>
                        <div class="input-group date form_date no_select" data-date=""
                                         data-date-format="dd/mm/yyyy" style="width:170px;">
                            <?php
                                if($encabezado == null )
                                {

                                    $fe = "";
                                }
                                else
                                {
                                    $fecha = explode(" ",$encabezado[0]->fecha_ejecucion)[0];
                                $fecha = explode("-",$fecha);
                                
                                $fe = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
                                }
                             ?>

                            <input class="form-control" size="16" style="height:30px;" type="text"
                                               value="{{$fe}}" name="fech_ejecucion" id="fech_ejecucion" placeholder="dd/mm/aaaa" required>
                            <span class="input-group-addon"><i class="fa fa-times"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
            </div>

            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="fech_ejecucion">Fecha de F. Ejecución:</label>
                        <div class="input-group date form_date no_select" data-date=""
                                         data-date-format="dd/mm/yyyy" style="width:170px;">
                            <?php
                                if($encabezado == null )
                                {

                                    $fe = "";
                                }
                                else
                                {
                                    $fecha = explode(" ",$encabezado[0]->fecha_ejecucion_final)[0];
                                    $fecha = explode("-",$fecha);

                                    if(count($fecha) > 1)
                                        $fe = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
                                    else
                                    {
                                        $fecha = explode(" ",$encabezado[0]->fecha_ejecucion)[0];
                                        $fecha = explode("-",$fecha);
                                        $fe = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
                                    }
                                }
                             ?>

                            <input class="form-control" size="16" style="height:30px;" type="text"
                                               value="{{$fe}}" name="fech_ejecucion" id="fech_ejecucion_2" placeholder="dd/mm/aaaa" required>
                            <span class="input-group-addon"><i class="fa fa-times"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="text_solicitante_codensa">Ingeniero Solicitante (CODENSA - Cliente):</label>
                            <input class="form-control" size="16"  type="text"
                                               value="{{$encabezado == null ? '' : $encabezado[0]->ing_solicitante}}" name="text_solicitante_codensa" id="text_solicitante_codensa" placeholder="Ingeniero Solicitante (CODENSA - Cliente)" required />
                    </div>
            </div>

            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="text_solicitante_cam">Ingeniero Solicitante (CAM):</label>
                        <input class="form-control" size="16"  type="text"
                                               value="{{$encabezado == null ? '' : $encabezado[0]->ing_soli_cam}}" name="text_solicitante_cam" id="text_solicitante_cam" placeholder="Ingeniero Solicitante (CAM)" required />
                    </div>
            </div>

            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="text_solicitante_supervisor">Supervisor Programador(CAM):</label>

                        <select data-num="1" required data-live-search="true" name="text_solicitante_supervisor" type="text" class="selectpicker form-control" id="text_solicitante_supervisor"  required>
                            <option value="0">Seleccione</option>
                            @for($i = 0; $i < count($supervisores); $i++)
                                    @if($encabezado == null)
                                        <option value ="{{$supervisores[$i]->identificacion}}">{{$supervisores[$i]->nombre}}</option> 
                                    @else
                                        @if($encabezado[0]->supervisor_programador_cedula != ""  && $encabezado[0]->supervisor_programador_cedula != null)

                                            @if($encabezado[0]->supervisor_programador_cedula == $supervisores[$i]->identificacion)
                                                <option value ="{{$supervisores[$i]->identificacion}}" selected>{{$supervisores[$i]->nombre}}</option> 
                                            @else
                                                <option value ="{{$supervisores[$i]->identificacion}}">{{$supervisores[$i]->nombre}}</option> 
                                            @endif
                                        @else

                                            @if($encabezado[0]->supervisor == $supervisores[$i]->nombre)
                                                <option value ="{{$supervisores[$i]->identificacion}}" selected>{{$supervisores[$i]->nombre}}</option> 
                                            @else
                                                <option value ="{{$supervisores[$i]->identificacion}}">{{$supervisores[$i]->nombre}}</option> 
                                            @endif

                                        @endif
                                        
                                    @endif

                                    
                            @endfor
                        </select>

                    </div>
            </div>

            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="text_solicitante_supervisor_ejecutor">Supervisor Ejecutor<br>(CAM):</label>
                        

                        <select data-num="1" required data-live-search="true" name="text_solicitante_supervisor_ejecutor" type="text" class="selectpicker form-control" id="text_solicitante_supervisor_ejecutor"  required>
                            <option value="0">Seleccione</option>
                            @for($i = 0; $i < count($supervisores); $i++)
                                    @if($encabezado == null)
                                        <option value ="{{$supervisores[$i]->identificacion}}">{{$supervisores[$i]->nombre}}</option> 
                                    @else
                                        @if($encabezado[0]->supervisor_ejecutor_cedula != ""  && $encabezado[0]->supervisor_ejecutor_cedula != null)

                                            @if($encabezado[0]->supervisor_ejecutor_cedula == $supervisores[$i]->identificacion)
                                                <option value ="{{$supervisores[$i]->identificacion}}" selected>{{$supervisores[$i]->nombre}}</option> 
                                            @else
                                                <option value ="{{$supervisores[$i]->identificacion}}">{{$supervisores[$i]->nombre}}</option> 
                                            @endif
                                        @else

                                            @if($encabezado[0]->supervisor_ejecutor == $supervisores[$i]->nombre)
                                                <option value ="{{$supervisores[$i]->identificacion}}" selected>{{$supervisores[$i]->nombre}}</option> 
                                            @else
                                                <option value ="{{$supervisores[$i]->identificacion}}">{{$supervisores[$i]->nombre}}</option> 
                                            @endif

                                        @endif
                                        
                                    @endif

                                    
                            @endfor
                        </select>
                    </div>
            </div>

            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="text_gestor_descargos">Gestor de descargos:<br>(Opcional)</label>
                        
                        <select data-num="1" required data-live-search="true" name="text_gestor_descargos" type="text" class="selectpicker form-control" id="text_gestor_descargos"  required>
                            <option value="0">Seleccione</option>
                            
                            @for($i = 0; $i < count($gestores); $i++)
                                    @if($encabezado == null)
                                        <option value ="{{$gestores[$i]->identificacion}}">{{$gestores[$i]->nombre}}</option> 
                                    @else
                                        @if($encabezado[0]->gestor_descargos != ""  && $encabezado[0]->gestor_descargos != null)

                                            @if($encabezado[0]->gestor_descargos == $gestores[$i]->identificacion)
                                                <option value ="{{$gestores[$i]->identificacion}}" selected>{{$gestores[$i]->nombre}}</option> 
                                            @else
                                                <option value ="{{$gestores[$i]->identificacion}}">{{$gestores[$i]->nombre}}</option> 
                                            @endif
                                        @else

                                            @if($encabezado[0]->gestor_descargos == $gestores[$i]->identificacion)
                                                <option value ="{{$gestores[$i]->identificacion}}" selected>{{$gestores[$i]->nombre}}</option> 
                                            @else
                                                <option value ="{{$gestores[$i]->identificacion}}">{{$gestores[$i]->nombre}}</option> 
                                            @endif

                                        @endif
                                        
                                    @endif

                                    
                            @endfor
                        </select>
                    </div>
            </div>

            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="text_gestor_materiales">Gestor de materiales:<br>(Opcional)</label>
                        

                        <select data-num="1" required data-live-search="true" name="text_gestor_materiales" type="text" class="selectpicker form-control" id="text_gestor_materiales"  required>
                            <option value="0">Seleccione</option>
                            
                            @for($i = 0; $i < count($gestores); $i++)
                                    @if($encabezado == null)
                                        <option value ="{{$gestores[$i]->identificacion}}">{{$gestores[$i]->nombre}}</option> 
                                    @else
                                        @if($encabezado[0]->gestor_materiales != ""  && $encabezado[0]->gestor_materiales != null)

                                            @if($encabezado[0]->gestor_materiales == $gestores[$i]->identificacion)
                                                <option value ="{{$gestores[$i]->identificacion}}" selected>{{$gestores[$i]->nombre}}</option> 
                                            @else
                                                <option value ="{{$gestores[$i]->identificacion}}">{{$gestores[$i]->nombre}}</option> 
                                            @endif
                                        @else

                                            @if($encabezado[0]->gestor_materiales == $gestores[$i]->identificacion)
                                                <option value ="{{$gestores[$i]->identificacion}}" selected>{{$gestores[$i]->nombre}}</option> 
                                            @else
                                                <option value ="{{$gestores[$i]->identificacion}}">{{$gestores[$i]->nombre}}</option> 
                                            @endif

                                        @endif
                                        
                                    @endif

                                    
                            @endfor
                        </select>
                    </div>
            </div>

        </div>

        <div class="row">
                        <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <label for="text_dire_orden">Dirección de Trabajos a Realizar:</label>
                                    <input name="text_dire_orden" type="text" class="form-control" id="text_dire_orden" 
                                    value="{{$encabezado == null ? $dire : $encabezado[0]->direccion}}"/>
                                </div>
                        </div>

                         <div class="col-md-2">
                                <div class="form-group has-feedback selectpicker">
                                    <label for="select_tipo_trabajo">Tipo de Trabajo:</label>
                                    <select id="select_tipo_trabajo" class="selectpicker" multiple style="height: 30px;padding-top:2px;padding-bottom:2px;">
                                        @if($encabezado == null)
                                            <option value="1">LV</option>
                                            <option value="2">LM</option>
                                            <option value="3">PROX</option>
                                            <option value="4">LV - LM</option>
                                            <option value="5">EXP</option>
                                            <option value="6">VyP</option>
                                            <option value="7">Incidencia</option>
                                        @else
                                            @if($encabezado[0]->lv == 0)
                                                <option value="1">LV</option>
                                            @else
                                                 <option value="1" selected>LV</option>
                                            @endif

                                            @if($encabezado[0]->lm == 0)
                                                <option value="2">LM</option>
                                            @else
                                                 <option value="2" selected>LM</option>
                                            @endif

                                            @if($encabezado[0]->prox == 0)
                                                <option value="3">PROX</option>
                                            @else
                                                 <option value="3" selected>PROX</option>
                                            @endif

                                            @if($encabezado[0]->lv_lm == 0)
                                                <option value="4">LV - LM</option>
                                            @else
                                                 <option value="4" selected>LV - LM</option>
                                            @endif
                                            
                                            @if($encabezado[0]->exp == 0)
                                                <option value="5">EXP</option>
                                            @else
                                                 <option value="5" selected>EXP</option>
                                            @endif

                                            @if($encabezado[0]->vyp == 0)
                                                <option value="6">VyP</option>
                                            @else
                                                 <option value="6" selected>VyP</option>
                                            @endif

                                            @if($encabezado[0]->inc == 0)
                                                <option value="7">Incidencia</option>
                                            @else
                                                 <option value="7" selected>Incidencia</option>
                                            @endif

                                        @endif
                                    </select>
                                </div>
                        </div>

                        <!-- ====================================================================================================== -->
                        <!-- SEC/PF/CD -->
                        <!-- ====================================================================================================== -->
                        <?php if($tipoProyecto !== 'T04'): ?>
                          <div class="col-md-3">
                            <div class="form-group has-feedback">
                              <label for="text_cd">SEC/PF/CD:</label>
                              <input name="text_cd" 
                                type="text" 
                                class="form-control" 
                                id="text_cd" 
                                value="{{$encabezado == null ? '' : $encabezado[0]->cd}}" 
                                onblur="consultaCD(this);" />
                            </div>
                          </div>
                        <?php else:?>
                          <input type="hidden" name="text_cd" id="text_cd" value="" />
                        <?php endif; ?>

                        <!-- ====================================================================================================== -->
                        <!-- NIVEL DE TENSION (MT/BT) -->
                        <!-- ====================================================================================================== -->
                        <?php if($tipoProyecto !== 'T04'): ?>
                          <div class="col-md-2">
                            <div class="form-group has-feedback">
                              <label for="select_nivel_t">Nivel de Tensión (MT / BT):</label>

                              <select name="select_nivel_t" 
                                id="select_nivel_t" 
                                class="form-control"
                                required> 

                                @if($encabezado == null)
                                  <option value="0">MT</option>
                                  <option value="1">BT</option>
                                @else
                                  @if($encabezado[0]->nivel_tension == "0")
                                    <option value="0" selected>MT</option>
                                    <option value="1">BT</option>
                                  @else
                                    <option value="0" >MT</option>
                                    <option value="1" selected>BT</option>
                                  @endif
                                @endif
                              </select>
                            </div>
                          </div>
                        <?php else:?>
                          <input type="hidden" name="select_nivel_t" id="select_nivel_t" value="" />
                        <?php endif; ?>


                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="tipo_trabajo">Tipo de trabajo:</label>
                                <select name="tipo_trabajo" id="tipo_trabajo" class="form-control"   required> 
                                @if($encabezado == null)
                                    <option value="DIURNO">DIURNO</option>
                                    <option value="NOCTURNO">NOCTURNO</option>
                                @else
                                    @if($encabezado[0]->tipo_trabajo == "NOCTURNO")
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

                    
        </div>

        <div class="row">
          <!-- ====================================================================================================== -->
          <!-- HORA INICIO -->
          <!-- ====================================================================================================== -->
          <?php if($tipoProyecto !== 'T04'): ?>
            <div class="col-md-2">
              <div class="form-group has-feedback">
                <label for="text_hora_ini">Hora inicio:</label>
                
                <div class="input-group date form_time" 
                  data-date=""
                  data-date-format="hh:ii" 
                  style="width:170px;">
                     @if($tipoProyecto == "T03")
                        <input class="form-control" 
                          size="16" 
                          style="height:30px;" 
                          type="text"
                          value="{{$encabezado == null ? '00:00' : explode(':',$encabezado[0]->hora_ini)[0] . ':' . explode(':',$encabezado[0]->hora_ini)[1]}}" 
                          name="text_hora_ini" 
                          id="text_hora_ini" 
                          placeholder="HH:mm" 
                          required>

                     @else
                        <input class="form-control" 
                        size="16" 
                        style="height:30px;" 
                        type="text"
                        value="{{$encabezado == null ? '' : explode(':',$encabezado[0]->hora_ini)[0] . ':' . explode(':',$encabezado[0]->hora_ini)[1]}}" 
                        name="text_hora_ini" 
                        id="text_hora_ini" 
                        placeholder="HH:mm" 
                        required>
                     @endif
                      
                    <span class="input-group-addon"><i class="fa fa-times"></i></span>
                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                </div>
              </div>
            </div>
          
            <input type="hidden" 
              value="{{$encabezado == null ? '' : explode(':',$encabezado[0]->hora_ini)[0] . ':' . explode(':',$encabezado[0]->hora_ini)[1]}}"  
              id="fecha_ini_hidden"/>
          <?php else:?>
            <input type="hidden" name="text_hora_ini" id="text_hora_ini" value="" />
          <?php endif; ?>

          <!-- ====================================================================================================== -->
          <!-- HORA CORTE -->
          <!-- ====================================================================================================== -->
          <?php if($tipoProyecto !== 'T04'): ?>
            <div class="col-md-2">
              <div class="form-group has-feedback">
                <label for="text_hora_corte">Hora corte:</label>

                @if($tipoProyecto == "T02")
                  <select class="form-control" 
                    required 
                    style="height:30px;" 
                    id="text_hora_corte">
                    @if($encabezado != null)
                      @if($encabezado[0]->hora_corte_largo == "8:00")
                        <option selected value="8:00">8:00</option>
                      @else
                        <option value="8:00">8:00</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "8:15")
                        <option selected value="8:15">8:15</option>
                      @else
                        <option value="8:15">8:15</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "8:30")
                        <option selected value="8:30">8:30</option>
                      @else
                        <option value="8:30">8:30</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "8:45")
                        <option selected value="8:45">8:45</option>
                      @else
                          <option value="8:45">8:45</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "9:00")
                        <option selected value="9:00">9:00</option>
                      @else
                        <option value="9:00">9:00</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "9:15")
                        <option selected value="9:15">9:15</option>
                      @else
                        <option value="9:15">9:15</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "9:30")
                        <option selected value="9:30">9:30</option>
                      @else
                        <option value="9:30">9:30</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "9:45")
                        <option selected value="9:45">9:45</option>
                      @else
                        <option value="9:45">9:45</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "10:00")
                        <option selected value="10:00">10:00</option>
                      @else
                        <option value="10:00">10:00</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "10:15")
                        <option selected value="10:15">10:15</option>
                      @else
                        <option value="10:15">10:15</option>
                      @endif

                      @if($encabezado[0]->hora_corte_largo == "10:30")
                        <option selected value="10:30">10:30</option>
                      @else
                        <option value="10:30">10:30</option>
                      @endif
                    @else
                      <option value="8:00">8:00</option>
                      <option value="8:15">8:15</option>
                      <option value="8:30">8:30</option>
                      <option value="8:45">8:45</option>
                      <option value="9:00">9:00</option>
                      <option value="9:15">9:15</option>
                      <option value="9:30">9:30</option>
                      <option value="9:45">9:45</option>
                      <option value="10:00">10:00</option>
                      <option value="10:15">10:15</option>
                      <option value="10:30">10:30</option>
                    @endif
                  </select>
                @else
                  <div class="input-group date form_time" 
                    data-date=""
                    data-date-format="hh:ii" 
                    style="width:170px;">
                      
                      @if($tipoProyecto == "T03")
                        <!-- CIVILES -->
                        <input class="form-control" 
                          size="16" 
                          style="height:30px;" 
                          type="text"
                          value="{{$encabezado == null ? '00:15' : explode(':',$encabezado[0]->hora_corte)[0] . ':' . explode(':',$encabezado[0]->hora_corte)[1]}}" 
                          name="text_hora_corte" 
                          id="text_hora_corte" 
                          placeholder="HH:mm" required>
                      @elseif($tipoProyecto == "T01")
                        
                          <!-- CARTAS -->
                          <input class="form-control" 
                          size="16" 
                          style="height:30px;" 
                          type="text"
                          value="{{$encabezado == null ? '' : explode(':',$encabezado[0]->hora_corte)[0] . ':' . explode(':',$encabezado[0]->hora_corte)[1]}}" 
                          name="text_hora_corte" 
                          id="text_hora_corte" 
                          placeholder="HH:mm" 
                          required>
                        
                      @elseif($tipoProyecto == "T05")
                        
                          <!-- CARTAS -->
                          <input class="form-control" 
                          size="16" 
                          style="height:30px;" 
                          type="text"
                          value="{{$encabezado == null ? '' : explode(':',$encabezado[0]->hora_corte)[0] . ':' . explode(':',$encabezado[0]->hora_corte)[1]}}" 
                          name="text_hora_corte" 
                          id="text_hora_corte" 
                          placeholder="HH:mm" 
                          required>
                        
                      @endif

                      <span class="input-group-addon"><i class="fa fa-times"></i></span>
                      <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                  </div>
                @endif
              </div>
            </div>
          <?php else:?>
            <input type="hidden" name="text_hora_corte" id="text_hora_corte" value="" />
          <?php endif; ?>

          <!-- ====================================================================================================== -->
          <!-- HORA CIERRE -->
          <!-- ====================================================================================================== -->
          <?php if($tipoProyecto !== 'T04'): ?>
            <div class="col-md-2">
              <div class="form-group has-feedback">
                <label for="text_hora_cierre">Hora cierre:</label>
                
                <div class="input-group date form_time" 
                  data-date=""
                  data-date-format="hh:ii" 
                  style="width:170px;">    
                    @if($tipoProyecto == "T03")
                      <input class="form-control" 
                        size="16" 
                        style="height:30px;" 
                        type="text"
                        value="{{$encabezado == null ? '23:45' : explode(':',$encabezado[0]->hora_cierre)[0] . ':' . explode(':',$encabezado[0]->hora_cierre)[1]}}" 
                        name="text_hora_cierre" 
                        id="text_hora_cierre" 
                        placeholder="HH:mm" 
                        required>
                    @else
                      <input class="form-control" 
                        size="16" 
                        style="height:30px;" 
                        type="text"
                        value="{{$encabezado == null ? '' : explode(':',$encabezado[0]->hora_cierre)[0] . ':' . explode(':',$encabezado[0]->hora_cierre)[1]}}" 
                        name="text_hora_cierre" id="text_hora_cierre" 
                        placeholder="HH:mm" 
                        required>
                    @endif

                    <span class="input-group-addon"><i class="fa fa-times"></i></span>
                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                  </div>
              </div>
            </div>
          <?php else:?>
            <input type="hidden" name="text_hora_cierre" id="text_hora_cierre" value="" />
          <?php endif; ?>

          <!-- ====================================================================================================== -->
          <!-- HORA FIN -->
          <!-- ====================================================================================================== -->
          <?php if($tipoProyecto !== 'T04'): ?>
            <div class="col-md-2">
              <div class="form-group has-feedback">
                <label for="text_hora_fin">Hora fin:</label>

                <div class="input-group date form_time" 
                  data-date=""
                  data-date-format="hh:ii" 
                  style="width:170px;">               
                    @if($tipoProyecto == "T03")
                      <input class="form-control" 
                        size="16" 
                        style="height:30px;" 
                        type="text"
                        value="{{$encabezado == null ? '23:55' : explode(':',$encabezado[0]->hora_fin)[0] . ':' . explode(':',$encabezado[0]->hora_fin)[1]}}" 
                        name="text_hora_fin" 
                        id="text_hora_fin" 
                        placeholder="HH:mm" 
                        required>
                          @else
                            <input class="form-control" 
                              size="16" 
                              style="height:30px;" 
                              type="text"
                              value="{{$encabezado == null ? '' : explode(':',$encabezado[0]->hora_fin)[0] . ':' . explode(':',$encabezado[0]->hora_fin)[1]}}" 
                              name="text_hora_fin" 
                              id="text_hora_fin" 
                              placeholder="HH:mm" 
                              required>
                          @endif

                      <span class="input-group-addon"><i class="fa fa-times"></i></span>
                      <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                </div>
              </div>
            </div>

            <input type="hidden" 
              value="{{$encabezado == null ? '' : explode(':',$encabezado[0]->hora_fin)[0] . ':' . explode(':',$encabezado[0]->hora_fin)[1]}}"  
              id="fecha_fin_hidden" />
          <?php else:?>
            <input type="hidden" name="text_hora_fin" id="text_hora_fin" value="" />
          <?php endif; ?>

          <!-- ====================================================================================================== -->
          <!-- OBSERVACION DEL TRABAJO A REALIZAR -->
          <!-- ====================================================================================================== -->
          <div class="col-md-4">
            <div class="form-group has-feedback">
              <label for="text_obser_orden">Observación del trabajo a realizar:</label>
              
              <textarea id="text_obser_orden" 
              style="width:99%" 
              class="form-control" 
              rows="3"
              required>{{$encabezado == null ? $obs : $encabezado[0]->observaciones}}</textarea>
            </div>
          </div>
        </div>

        <!-- ====================================================================================================== -->
        <!-- DESCARGOS -->
        <!-- ====================================================================================================== -->
        <?php if($tipoProyecto !== 'T04'): ?>
          <div class="row">
            <!-- ==================================================================================================== -->
            <!-- DESCARGO 1 -->
            <!-- ==================================================================================================== -->
            <div class="col-md-2">
              <div class="form-group has-feedback">
                <label for="text_gom_adec">Descargo 1 (Corte):</label>

                @if($encabezado == null)
                  <select data-num="1" 
                    required 
                    data-live-search="true" 
                    name="descargo_add_1" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_1">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                      @endfor
                  </select>
                @else
                  <select data-num="1" 
                    data-live-search="true" 
                    name="descargo_add_1" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_1">   
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        @if($descargos[$i]->des == $encabezado[0]->descargo)
                          <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                        @else
                          <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                        @endif
                      @endfor
                  </select>
                @endif
              </div>
            </div>

            <!-- ==================================================================================================== -->
            <!-- DESCARGO 2 -->
            <!-- ==================================================================================================== -->
            <div class="col-md-2">
              <div class="form-group has-feedback selectpicker">
                <label for="text_gom_inst">Descargo 2 (Corte):</label>

                @if($encabezado == null)
                  <select required 
                  data-live-search="true" 
                  data-num="1" 
                  name="descargo_add_2" 
                  type="text" 
                  class="selectpicker form-control" 
                  id="descargo_add_2">
                    <option value="0">Seleccione</option>
                    @for($i = 0; $i < count($descargos); $i++)
                      <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                    @endfor
                </select>
                @else
                  <select data-num="1" 
                    data-live-search="true" 
                    name="descargo_add_2" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_2">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        @if($descargos[$i]->des == $encabezado[0]->descargo2)
                          <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                        @else
                          <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                        @endif
                      @endfor
                  </select>
                @endif
              </div>
            </div>

            <!-- ==================================================================================================== -->
            <!-- DESCARGO 3 -->
            <!-- ==================================================================================================== -->
            <div class="col-md-2">
              <div class="form-group has-feedback selectpicker">
                <label for="text_gom_inst">Descargo 3 (Corte):</label>
                
                @if($encabezado == null)
                  <select 
                    required 
                    data-live-search="true" 
                    data-num="1" 
                    name="descargo_add_3" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_3">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                      @endfor
                  </select>
                @else
                  <select 
                    data-num="1" 
                    data-live-search="true" 
                    name="descargo_add_3" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_3">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        @if($descargos[$i]->des == $encabezado[0]->descargo3)
                          <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                        @else
                          <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                        @endif
                      @endfor
                  </select>
                @endif
              </div>
            </div>

            <!-- ==================================================================================================== -->
            <!-- DESCARGO 4 -->
            <!-- ==================================================================================================== -->
            <div class="col-md-2">
              <div class="form-group has-feedback selectpicker">
                <label for="text_gom_inst">Descargo 4 (Sin Corte):</label>
            
                @if($encabezado == null)
                  <select 
                    required 
                    data-live-search="true" 
                    data-num="1" 
                    name="descargo_add_4" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_4" >
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                      @endfor
                  </select>
                @else
                  <select 
                    data-num="1" 
                    data-live-search="true" 
                    name="descargo_add_4" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_4">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        @if($descargos[$i]->des == $encabezado[0]->descargo4)
                          <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                        @else
                          <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                        @endif
                      @endfor
                  </select>
                @endif
              </div>
            </div>

            <!-- ==================================================================================================== -->
            <!-- DESCARGO 5 -->
            <!-- ==================================================================================================== -->
            <div class="col-md-2">
              <div class="form-group has-feedback selectpicker">
                <label for="text_gom_inst">Descargo 5 (Sin Corte):</label>
              
                @if($encabezado == null)
                  <select 
                    required 
                    data-live-search="true" 
                    data-num="1" 
                    name="descargo_add_5" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_5">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                      @endfor
                  </select>
                @else
                  <select 
                    data-num="1" 
                    data-live-search="true" 
                    name="descargo_add_5" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_5">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        @if($descargos[$i]->des == $encabezado[0]->descargo5)
                          <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                        @else
                          <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                        @endif
                      @endfor
                  </select>
                @endif
              </div>
            </div>

            <!-- ==================================================================================================== -->
            <!-- DESCARGO 6 -->
            <!-- ==================================================================================================== -->
            <div class="col-md-2">
              <div class="form-group has-feedback selectpicker">
                <label for="text_gom_inst">Descargo 6 (Sin Corte):</label>
            
                @if($encabezado == null)
                  <select 
                    required 
                    data-live-search="true" 
                    data-num="1" 
                    name="descargo_add_6" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_6">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                      @endfor
                  </select>
                @else
                  <select 
                    data-num="1" 
                    data-live-search="true" 
                    name="descargo_add_6" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_6" >
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        @if($descargos[$i]->des == $encabezado[0]->descargo6)
                          <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                        @else
                          <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                        @endif
                      @endfor
                  </select>
                @endif
              </div>
            </div>

            <!-- ==================================================================================================== -->
            <!-- DESCARGO 7 -->
            <!-- ==================================================================================================== -->
            <div class="col-md-2">
              <div class="form-group has-feedback selectpicker">
                <label for="text_gom_inst">Descargo 7 (Sin Corte):</label>
            
                @if($encabezado == null)
                  <select 
                    required 
                    data-live-search="true" 
                    data-num="1" 
                    name="descargo_add_7" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_7">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                      @endfor
                  </select>
                @else
                  <select 
                    data-num="1" 
                    data-live-search="true" 
                    name="descargo_add_7" 
                    type="text" 
                    class="selectpicker form-control" 
                    id="descargo_add_7">
                      <option value="0">Seleccione</option>
                      @for($i = 0; $i < count($descargos); $i++)
                        @if($descargos[$i]->des == $encabezado[0]->descargo7)
                          <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                        @else
                          <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                        @endif
                      @endfor
                  </select>
                @endif
              </div>
            </div>
          </div>
        <?php else: ?>
          <input type="hidden" name="descargo_add_1" id="descargo_add_1" value="" />
          <input type="hidden" name="descargo_add_2" id="descargo_add_2" value="" />
          <input type="hidden" name="descargo_add_3" id="descargo_add_3" value="" />
          <input type="hidden" name="descargo_add_4" id="descargo_add_4" value="" />
          <input type="hidden" name="descargo_add_5" id="descargo_add_5" value="" />
          <input type="hidden" name="descargo_add_6" id="descargo_add_6" value="" />
          <input type="hidden" name="descargo_add_7" id="descargo_add_7" value="" />
        <?php endif; ?>

        <div class="row">
                        <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <label for="text_gom_adec">GOM ADEC:</label>
                                    @if($encabezado == null)
                                        <input data-num="1" name="text_gom_adec" type="text" class="form-control" id="text_gom_adec" readonly/>
                                    @else
                                        @if($encabezado[0]->id_estado == "E1")
                                            <input data-num="1" name="text_gom_adec" type="text" class="form-control" id="text_gom_adec" readonly/>
                                        @else
                                        <select data-num="1" name="text_gom_adec" type="text" class="form-control" id="text_gom_adec" 
                                    >
                                            @for($i = 0; $i < count($goms); $i++)
                                                    @foreach ($goms[$i] as $gom => $val)
                                                        @if($encabezado[0]->gom_adec == $val->id_gom)
                                                            <option value ="{{$val->id_gom}}" selected>{{$val->id_gom}}</option> 
                                                        @else
                                                            <option value ="{{$val->id_gom}}">{{$val->id_gom}}</option>
                                                        @endif
                                                    @endforeach
                                                @endfor
                                        </select>
                                        @endif
                                    @endif
                                </div>
                        </div>
            <input name="text_gom_inst" type="hidden" class="form-control" id="text_gom_inst" />
                         <!--
                         <div class="col-md-2">
                                <div class="form-group has-feedback selectpicker">
                                    <label for="text_gom_inst">GOM INST:</label>
                                    @if($encabezado == null)
                                        <input data-num="1" name="text_gom_inst" type="text" class="form-control" id="text_gom_inst" readonly 
                                    />
                                    @else
                                        @if($encabezado[0]->id_estado == "E1")
                                            <input data-num="1" name="text_gom_inst" type="text" class="form-control" id="text_gom_inst" readonly />
                                        @else
                                            <select data-num="1" name="text_gom_inst" type="text" class="form-control" id="text_gom_inst" 
                                    ">
                                            @if($encabezado[0]->gom_adec == "0" || $encabezado[0]->gom_adec == null)
                                                <option value="0">Seleccione</option>
                                                @for($i = 0; $i < count($goms); $i++)
                                                    @foreach ($goms[$i] as $gom => $val)
                                                        <option value ="{{$val->id_gom}}">{{$val->id_gom}}</option>
                                                    @endforeach
                                                @endfor

                                            @else
                                                @for($i = 0; $i < count($goms); $i++)
                                                    @foreach ($goms[$i] as $gom => $val)
                                                        @if($encabezado[0]->gom_inst == $val->id_gom)
                                                            <option value ="{{$val->id_gom}}" selected>{{$val->id_gom}}</option> 
                                                        @else
                                                            <option value ="{{$val->id_gom}}">{{$val->id_gom}}</option>
                                                        @endif
                                                    @endforeach
                                                @endfor
                                            @endif
                                            </select>
                                        @endif
                                        

                                        
                                    @endif
                                </div>
                        </div> -->

                        <div class="col-md-2">
                                <div class="form-group has-feedback">
                                    <label for="text_fac_orden">FAC:</label>
                                    @if($encabezado == null)
                                        <input data-num="1" name="text_fac_orden" type="text" class="form-control" id="text_fac_orden" readonly />
                                    @else
                                            <input data-num="1" name="text_fac_orden" type="text" class="form-control" id="text_fac_orden" 
                                    value="{{$encabezado == null ? '' : $encabezado[0]->fac_orden}}"/>                                        
                                    @endif
                                </div>
                        </div>

                        <div class="col-md-2">
                                <div class="form-group has-feedback">
                                    <label for="text_rad_oren">RAD:</label>
                                    @if($encabezado == null)
                                        <input data-num="1" name="text_rad_oren" type="text" class="form-control" id="text_rad_oren" 
                                    readonly />
                                    @else
                                        <input data-num="1" name="text_rad_oren" type="text" class="form-control" id="text_rad_oren" 
                                    value="{{$encabezado == null ? '' : $encabezado[0]->rad_orden}}"/>
                                    @endif
                                </div>
                        </div>

                        <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <label for="text_proy">PROY:</label>
                                    @if($encabezado == null)
                                   <input name="text_proy" type="text" class="form-control" id="text_proy" 
                                    readonly />
                                    @else
                                        <input name="text_proy" type="text" class="form-control" id="text_proy" 
                                    value="{{$encabezado == null ? '' : $encabezado[0]->proy_orden}}"/>
                                    @endif
                                </div>
                        </div>
        </div>

        <div class="row">
                        <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label >Ubicación GPS del Descargo o del CD:</label>
                                    @if($encabezado == null)
                                        <input  placeholder="Ejemplo: 4.667960,-74.115803" type="text" class="form-control" id="txt_gps_descargo" />
                                    @else
                                        @if($encabezado[0]->id_estado != "E4" && $encabezado[0]->id_estado != "C2")
                                            @if($encabezado[0]->x != "" && $encabezado[0]->x != NULL)
                                                <input placeholder="Ejemplo: 4.667960,-74.115803"   type="text" class="form-control"  id="txt_gps_descargo"  value ="{{$encabezado[0]->x}},{{$encabezado[0]->y}}" />
                                            @else
                                                <input placeholder="Ejemplo: 4.667960,-74.115803"   type="text" class="form-control"  id="txt_gps_descargo"  />
                                            @endif
                                        @else
                                            <input   placeholder="Ejemplo: 4.667960,-74.115803" type="text" class="form-control"  id="txt_gps_descargo"  value ="{{$encabezado[0]->x}},{{$encabezado[0]->y}}" readonly/>
                                        @endif
                                    @endif
                                </div>
                        </div>

                        
                    
        </div>

    </div>


</div>
@if($encabezado != null )
    @if($encabezado[0]->fecha_reprogramacion != NULL)
        <h3 style="margin-left:40px;color:red;    font-size: 20px;">MANIOBRA RE PROGRAMADA</h3>
        <h4 style="margin-left:40px">Fecha: <b>{{explode(".",$encabezado[0]->fecha_reprogramacion)[0]}}</b></h4>
        <h4 style="margin-left:40px">Observación: <b>{{$encabezado[0]->observacion_reprogramacion}}</b></h4>
    @endif
@endif