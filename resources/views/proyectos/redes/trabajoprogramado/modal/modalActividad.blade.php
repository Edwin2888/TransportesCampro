<div class="modal fade" id="modal_acti">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Actividades Ver/Editar</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="select_wbs_1">WBS</label>
            <div class="col-sm-7">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <select type="text" class="form-control" id="select_wbs_1" name="select_wbs_1" >
                  <option value="0">Seleccione</option>
                  @foreach($wbsCombox as $comb => $val)
                    <option value='{{$val->id_ws}}'>{{strtoupper($val->nombre_ws)}}</option>
                  @endforeach
                  </select>
                </div>
          </div>
          </div>
      </div>
      <div class="row" style="    margin-top: 16px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="select_nodos">Nodos</label>
            <div class="col-sm-7">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <select type="text" class="form-control" id="select_nodos" name="select_nodos" >
                  option value="0">Seleccione</option>
                  </select>
                </div>
          </div>
          </div>
      </div>
      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_baremo">Baremo</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>

                <input type="text" class="form-control" id="text_baremo" name="text_baremo" readonly>

                <span class=" input-group-addon glyphicon glyphicon glyphicon-search" aria-hidden="true" onclick="abrirmodalBaremos()"></span>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Cantidad</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_cant" name="text_cant" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_Valor">Valor</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon" aria-hidden="true">$</span>
                <input type="text" class="form-control" id="text_valor" name="text_valor" readonly >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="text_total">Total</label>
          <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon" aria-hidden="true">$</span>
                <input type="text" class="form-control" id="text_total" name="text_total" readonly>
              </div>
            </div>
        </div>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" id="btn-delete-actividad">Eliminar</button>
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-wbs-actividad"></button>
      </div>
    </div>
  </div>
</div>
</div>




<div class="modal fade" id="modal_baremo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Baremos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="volverBaremo" onclick="volverModal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_baremo">C??digo</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>

                <input type="text" class="form-control" id="text_baremo_cod" name="text_baremo_cod">
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_baremo_des">Descripci??n</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_baremo_des" name="text_baremo_des" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="consultarBaremo()">Consultar</button>
          </div>
      </div>

      <div class="row">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th></th>
                <th>Baremo</th>
                <th>Descripci??n</th>
                <th>Precio</th>
              </tr>
            </thead>
            <tbody id="tbl_baremos">
              <tr>
                
              </tr>
            </tbody>
            
          </table>
      </div>

      <div class="modal-footer">
        <button type="button" onclick="volverModal()">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>


