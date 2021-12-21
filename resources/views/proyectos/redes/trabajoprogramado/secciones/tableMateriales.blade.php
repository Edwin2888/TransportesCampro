<div class="modal fade" id="modal_captura_ejecucion">
  <div class="modal-dialog modal-lg" role="document" style="width:95%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Capturar Ejecución</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     
      
      <div class="row" style="    margin-top: 16px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="select_nodos">Seleccione el líder a quien le va cargar la ejecución.</label>
            <div class="col-md-4">
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
          <label class="control-label col-sm-3 col-md-offset-1" for="select_nodos">Nodos afectados por líder.</label>
            <div class="col-md-4">
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

      <div class="row">
          <div class="col-md-12">
              <div class="row">
                  <div class="col-md-6">
                      
                  </div>

                  <div class="col-md-6">
                    
                  </div>
              </div>
          </div>
      </div>
      

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-wbs-actividad">Agregar actividad</button>
      </div>
    </div>
  </div>
</div>
</div>

