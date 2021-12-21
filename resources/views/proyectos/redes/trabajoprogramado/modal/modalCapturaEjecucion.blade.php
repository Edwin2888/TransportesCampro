<div class="modal fade" id="modal_captura_ejecucion" data-backdrop="static" data-keyboard="false" >
  <div class="modal-dialog modal-lg" role="document" style="width:95%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Capturar Ejecución</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body tipo_modal">
      <div class="row">
        <div class="col-md-5">
       @if($encabezado == null)
          <div class="row" style="    margin-top: 16px;">
            <div class="form-group has-feedback">
              <label class="control-label col-sm-12" for="select_nodos">Ingrese Número de ManiObra(OT)</label>
                <div class="col-md-12">
                    <div class="input-group">
                      <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                      <input onkeypress="validarExisteProyecto1()" type="text" class="form-control" id="text_eje_1" name="text_eje_1"/>
                    </div>
              </div>
              </div>
          </div>

        @endif

      
          <div class="row" style="    margin-top: 16px;">
            <div class="form-group has-feedback">
              <label class="control-label col-sm-12" for="select_nodos">Seleccione el líder a quien le va cargar la ejecución:</label>
                <div class="col-md-12">
                    <div class="input-group">
                      <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                      <select type="text" class="form-control" id="select_lider_carga" name="select_lider_carga">
                      <option value="0">Seleccione</option>
                      @foreach ($comboxP as $comb => $val)
                          <option value="{{$val->id_lider}}">{{$val->nombre}}</option>
                      @endforeach
                    </select>
                    </div>
              </div>
              </div>
          </div>

          <div class="row" style="    margin-top: 16px;">
            <div class="form-group has-feedback">
              <label class="control-label col-sm-12" for="select_nodos">Nodos afectados por líder:</label>
                <div class="col-md-12">
                    <div class="input-group">
                      <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                      <select type="text" class="form-control" id="select_nodos_afectados" name="select_nodos_afectados">
                      </select>
                    </div>
              </div>
              </div>
          </div>


          <div class="row" style="    margin-top: 16px;display:none;" id="panel_fecha">
            <div class="form-group has-feedback">
              <label class="control-label col-sm-12" for="select_nodos">Fecha de ejecución:</label>
                <div class="col-md-12">
                    <div class="input-group date form_date" data-date=""
                                         data-date-format="dd/mm/yyyy" style="width:100%;">
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input class="form-control" size="16" style="height:30px;" type="text"
                                           name="fech_ejecucionInput" id="fech_ejecucionInput" placeholder="dd/mm/aaaa" required>
                        
                    </div>
              </div>
              </div>
          </div>

        <form id="form_horasac" > 
                <div style="width: 100%;clear: both;height: 8px;"></div>  
                <div class="row" style=" border: 2px solid #ccc;    border-radius: 6px;     margin-top: 4px;    padding: 5px;" id="">
                  <div class="form-group has-feedback" id="content_apertura">
                      <input type="hidden" name="_token" value="<?= csrf_token() ?>" >
                      <input type="hidden" id="id_orden_hora" name="id_orden" value="<?= (isset($orden) && $orden!=null)?$orden:'' ?>" >
                      <div class="col-md-6">

                          <label class="control-label col-sm-12" for="select_nodos">Hora Aperturta:</label>
                          <div class="col-md-12">
                              <input type="text" name="hora_apertura" id="hora_apertura" value="<?= (isset($ordendet) && isset($ordendet->hora_apertura))?$ordendet->hora_apertura:'' ?>" class="form-control valida_horas" data-val="hora_apertura_val">
                              <label class="control-label col-sm-12 hora_apertura_val" style="margin-left: 0px; padding: 0px; color: rgb(185, 74, 72);"></label>
                          </div>

                      </div>
                      <div class="col-md-6">

                          <label class="control-label col-sm-12" for="select_nodos">Operador Centro Control:</label>
                          <div class="col-md-12">
                              <input type="text" name="operador_ccontrol_abre"  value="<?= (isset($ordendet) && isset($ordendet->operador_ccontrol_abre))?$ordendet->operador_ccontrol_abre:'' ?>" id="operador_ccontrol_abre" class="form-control valida_texto" data-val="operador_ccontrol_abre_val">
                              <label class="control-label col-sm-12 operador_ccontrol_abre_val" value="<?= (isset($ordendet) && isset($ordendet->operador_ccontrol_abre))?$ordendet->operador_ccontrol_abre:'' ?>" style="margin-left: 0px; padding: 0px; color: rgb(185, 74, 72);" ></label>
                          </div>

                      </div>
                      <div style="width: 100%;clear: both;"></div>

                      <div class="col-md-6">

                          <label class="control-label col-sm-12" for="select_nodos">Hora Cierre:</label>
                          <div class="col-md-12">
                              <input type="text" name="hora_cierre" id="hora_cierre" value="<?= (isset($ordendet) && isset($ordendet->hora_cierre_d))?$ordendet->hora_cierre_d:'' ?>" class="form-control valida_horas" data-val="hora_cierre_val">
                              <label class="control-label col-sm-12 hora_cierre_val"  style="margin-left: 0px; padding: 0px; color: rgb(185, 74, 72);"></label>
                          </div>

                      </div>
                      <div class="col-md-6">

                          <label class="control-label col-sm-12" for="select_nodos">Operador Centro Control:</label>
                          <div class="col-md-12">
                              <input type="text" name="operador_ccontrol_cierra" value="<?= (isset($ordendet) && isset($ordendet->operador_ccontrol_cierra))?$ordendet->operador_ccontrol_cierra:'' ?>"  id="operador_ccontrol_cierra" class="form-control valida_texto" data-val="operador_ccontrol_cierra_val">
                              <label class="control-label col-sm-12 operador_ccontrol_cierra_val"  style="margin-left: 0px; padding: 0px; color: rgb(185, 74, 72);"></label>
                          </div>

                      </div>
                      <div style="width: 100%;clear: both;"></div>
                      <div class="col-md-12">
                          <center>
                              <button type="submit" class="btn btn-primary btn-cam-trans btn-sm btnfrmoc" title="Guardar datos de nodos" href="" style="    margin-top: 9px; ">
                                  <i class="fa fa-save" aria-hidden="true"></i> Guardar
                              </button>
                             <img src="{{url('/')}}/img/loader6.gif" id="cargandocorreos" class="loading" alt="Loading..." style="display: none;">
                          </center>

                      </div>

                  </div>
                </div>
           </form>
        
        <div id="contentprinnodo" style="display: none;">
           
            <div id="form_estadonodo" > 
                    <div style="width: 100%;clear: both;height: 8px;"></div>  
                    <div class="row" style=" border: 2px solid #ccc;    border-radius: 6px;     margin-top: 4px;    padding: 5px;" id="">
                      <div class="form-group has-feedback" id="content_estadonodo">
                          <input type="hidden" name="_token" value="<?= csrf_token() ?>" >
                          <div class="col-md-6">

                              <label class="control-label col-sm-12" for="select_nodos">Estado de NODO:</label>
                              <div class="col-md-12">

                                  <select id="estado_nodo" >
                                      <option value="">Sin Estado</option>
                                      <option value="E">Ejecutada</option>
                                      <option value="C">Cancelada</option>
                                      <option value="R">Reprogramada</option>
                                  </select>


                                  <label class="control-label col-sm-12 hora_apertura_val" style="margin-left: 0px; padding: 0px; color: rgb(185, 74, 72);"></label>

                              </div>
                              <br>
                              
                              <div style="display: none">
                                <label class="control-label col-sm-12 plnodos" for="select_nodos">Plantilla de nodo:</label>
                                <div class="col-md-12 plnodos">

                                    <input type="text" id="plantilla_nodo" name="plantilla_nodo"  class="form-control valida_texto" >


                                    <label class="control-label col-sm-12 hora_apertura_val" style="margin-left: 0px; padding: 0px; color: rgb(185, 74, 72);"></label>

                                </div>
                              </div>

                          </div>
                          <div class="col-md-6">
                              <center>
                                  <button onclick="cambiaestado()" type="submit" class="btn btn-primary btn-cam-trans btn-sm btnfrmocd" title="Guardar datos de nodos" href="" style="    margin-top: 9px; ">
                                      <i class="fa fa-save" aria-hidden="true"></i> Guardar
                                  </button>
                                 <img src="{{url('/')}}/img/loader6.gif" id="cargandocorreos" class="loadingd" alt="Loading..." style="display: none;">
                              </center>

                          </div>

                      </div>
                    </div>
               </div>
           </div>
        
        </div>

        <div class="col-md-7">
          <p><b id="person_select_combox"></b></p>
          <div class="col-md-12" id="tbl_persona_cargo">
          </div>
          @if($encabezado == null)
            <button type="button" class="btn btn-primary" style="margin-left:39%;background-color:#0060ac;color:white;display:none" id="btn_save_eje_fin" style="display:none">Guardar ejecución ManiObra</button>
          @endif
        </div>
      </div>
      
      <div class="row" style="    margin-top: 16px;">
            <div class="form-group has-feedback">
              <label class="control-label col-sm-6" style="    margin-left: 15px;" for="select_nodos">DC asociados:</label>
                <div class="col-md-12">
                    <ul class="nodos-add" id="nodos-add">
                    </ul>
              </div>
            </div>
      </div>


      <div class="row" style="margin-top:10px;">
          <div class="col-md-12" id="datos_captura_ejecucion">
            @if($ejecucionB != null)
              @include('proyectos.redes.trabajoprogramado.secciones.tableBaremoAdd')
            @endif
          </div>
      </div>
      

      <div class="modal-footer">
      @if($encabezado != null)
        @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "R0" || isset($index))
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn_save_guardar_actividad" style="display:none">Guardar materiales nodo</button>
        @endif
      @else
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn_save_guardar_actividad" style="display:none">Guardar materiales nodo</button>
      @endif  
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">

    window.onload=function(){
	        
            
                        
    };

</script>