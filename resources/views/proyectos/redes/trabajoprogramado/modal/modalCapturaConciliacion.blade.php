<div class="modal fade" id="modal_captura_conciliacion">
  <div class="modal-dialog modal-lg" role="document" style="width:95%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Capturar Conciliacion</h5>
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
                        <input onkeypress="validarExisteProyecto2()" type="text" class="form-control" id="text_conci_2" name="text_conci_2"/>
                      </div>
                </div>
                </div>
            </div>

          @endif

          <div class="row" style="    margin-top: 16px;">
            <div class="form-group has-feedback">
              <label class="control-label col-sm-12" for="select_nodos">Seleccione el líder a quien le va cargar la conciliación:</label>
                <div class="col-md-12">
                    <div class="input-group">
                      <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                      <select type="text" class="form-control" id="select_lider_carga1" name="select_lider_carga1">
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
                      <select type="text" class="form-control" id="select_nodos_afectados1" name="select_nodos_afectados1">
                      </select>
                    </div>
              </div>
              </div>
          </div>
        </div>

        <div class="col-md-7">
          <p><b id="person_select_combox1"></b></p>
          <div class="col-md-12" id="tbl_persona_cargo1">
          </div>
          @if($encabezado == null)
            <button type="button" class="btn btn-primary" style="margin-left:39%;background-color:#0060ac;color:white;display:none" id="btn_save_conci_fin" style="display:none">Guardar Conciliación ManiObra</button>
          @endif
        </div>
      </div>
      
      <div class="row" style="    margin-top: 16px;">
            <div class="form-group has-feedback">
              <label class="control-label col-sm-6" style="    margin-left: 15px;" for="select_nodos1">DC asociados:</label>
                <div class="col-md-12">
                    <ul class="nodos-add" id="nodos-add1">
                    </ul>
              </div>
            </div>
      </div>


      <div class="row" style="margin-top:10px;">
          <div class="col-md-12" id="datos_captura_ejecucion1">
            @if($ejecucionB != null)
              @include('proyectos.redes.trabajoprogramado.secciones.tableBaremoAdd')
            @endif
          </div>
      </div>
      

      <div class="modal-footer">
      @if($encabezado != null)
        @if($encabezado[0]->id_estado == "E4")
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn_save_guardar_actividad1" style="display:none">Guardar conciliación Nodo</button>
        @endif
      @else
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn_save_guardar_actividad1" style="display:none">Guardar conciliación Nodo</button>
      @endif
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>

