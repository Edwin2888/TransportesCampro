<div class="modal fade" id="modal_cambio_lider">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Cambio líder</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

       <div class="row" style="margin-top:20px;" >
          <p style="    margin-left: 55px;"><b>Líder a modificar: </b><span id="datos_lider_cambio"></span></p>
       </div>

       <div class="row" style="margin-top:20px;" >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="select_tipo_recurso">Tipo de cuadrilla</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <select type="text" class="form-control" id="select_tipo_cuadrilla_cambio" name="select_tipo_cuadrilla_cambio">
                  <option value="0">Seleccione</option>
                  @foreach($tipCuadrilla as $tip => $val)
                    <option value='{{$val->id_tipo_cuadrilla}}'>{{strtoupper($val->nombre)}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;" >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_fecha_ini_cambio">Hora inicio</label>
            <div class="col-sm-7">
                    <input type='text' class="form-control"   id="text_fecha_ini_cambio" readonly />
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_fecha_fin_cambio">Hora fin</label>
            <div class="col-sm-7">
                    <input type='text' class="form-control" id="text_fecha_fin_cambio" readonly/>             
            </div>
          </div>
      </div>
      
      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="consultaRecursoAdd1()">Consultar</button>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_iden_recurs" id="text_iden_recurs">Líder</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <!--<input type="text" data-num="1" type="text" class="form-control" id="text_iden_recurso" name="text_iden_recurso"> -->
                <select type="text" data-num="1" type="text" class="form-control" id="text_iden_recurso_cambio" name="text_iden_recurso_cambio"> 
                </select>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="agregarPersona1()">Cambiar líder</button>
          </div>
      </div>     

      <div class="modal-footer">
        <button type="button" onclick="salir(2)">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>

