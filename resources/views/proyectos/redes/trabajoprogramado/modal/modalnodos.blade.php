<div class="modal fade" id="modal_nodos">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">NODOS Ver/Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="inputSuccess3">WBS</label>
            <div class="col-sm-7">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <select type="text" class="form-control" id="select_wbs" name="select_wbs" >
                  <option value="0">Seleccione</option>
                  @foreach($wbsCombox as $comb => $val)
                    <option value='{{$val->id_ws}}'>{{strtoupper($val->nombre_ws)}}</option>
                  @endforeach
                  </select>
                </div>
          </div>
          </div>
      </div>
     
      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Nodo</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_nodo" name="text_nodo" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="inputSuccess3">Nivel tensión</label>
          <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <select type="text" class="form-control" id="text_nive_teension" name="text_nive_teension" >
                  <option value="0">Seleccione</option>
                  @foreach($nivel_t as $nt => $val)
                    <option value='{{$val->codigo}}'>{{strtoupper($val->nombre)}}</option>
                  @endforeach
                </select>                
              </div>
            </div>
        </div>
        </div>



        <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="inputSuccess3">Punto Físico</label>
          <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="txt_pf_nodos" name="txt_pf_nodos" >
              </div>
            </div>
        </div>
        </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">CD</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_cd" name="text_cd" >
              </div>
            </div>
          </div>
      </div>

       <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="txt_seccionador">Seccionador</label>
          <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="txt_seccionador" name="txt_seccionador" >
              </div>
            </div>
        </div>
        </div>
      </div> 
      
      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Dirección</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_dire" name="text_dire" >
              </div>
            </div>
          </div>
      </div>
        
       <div class="row" style="margin-top:20px;">
            <div class="form-group has-feedback">
                <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Gom Nodo</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <input type="text" class="form-control" id="gom_nodo" name="gom" >
                    </div>
                </div>
            </div>
       </div>
       <div class="row" style="margin-top:20px;">
            <div class="form-group has-feedback">
                <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Estado Gom</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <select class="form-control" id="id_estado_gom" name="id_estado_gom" >
                            <option value="0">Seleccione Estado</option>
                            <option value="1">Solicitado</option>
                            <option value="2">Validado</option>
                            <option value="3">En ejecución</option>
                            <option value="4">Ejecución dos (cargue de soportes)</option>
                            <option value="5">Cerrado</option>
                            <option value="6">Confirmado</option>
                        </select>
                    </div>
                </div>
            </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="inputSuccess3">Observaciones</label>
          <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="txt_obser_nodos" name="txt_obser_nodos" >
              </div>
            </div>
        </div>
        </div>

        

       
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" id="btn-delete-nodos">Eliminar</button>
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-wbs-nodos"></button>
      </div>
    </div>
  </div>
</div>


