<div class="modal fade" id="modal_mat_add">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Agregar Materiales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
      @if($encabezado[0]->id_estado == "E2")
       <div class="row">
        <p style="color:red">La ManiObra ya se encuentra programada, las actividades agregadas en la ManiObra generaran un nuevo documento de despacho (DC)</p>  
      </div>
      @endif

      <div class="row" style="    margin-top: 16px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="select_nodos">Nodos</label>
            <div class="col-sm-7">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="select_nodos_material" name="select_nodos_material" readonly>
                </div>
          </div>
          </div>
      </div>
      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_mate_add">Material</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>

                <input type="text" class="form-control" id="text_mate_add" name="text_mate_add" readonly>

                <span class=" input-group-addon glyphicon glyphicon glyphicon-search" aria-hidden="true" onclick="abrirModalMateriales()"></span>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_cant_mate_add">Cantidad</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_cant_mate_add" name="text_cant_mate_add" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_valor_mate_add">Valor</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon" aria-hidden="true">$</span>
                <input type="text" class="form-control" id="text_valor_mate_add" name="text_valor_mate_add" readonly >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="text_total_mate_add">Total</label>
          <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon" aria-hidden="true">$</span>
                <input type="text" class="form-control" id="text_total_mate_add" name="text_total_mate_add" readonly>
              </div>
            </div>
        </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="text_total_mate_add">Persona a cargo</label>
          <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <select type="text" class="form-control" id="select_persona_cargo_mate" name="select_persona_cargo_mate">
                  @foreach ($comboxP as $comb => $val)
                      <option value="{{$val->id_lider}}">{{$val->nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
        </div>
        </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-wbs-material-a">Agregar material</button>
      </div>
    </div>
  </div>
</div>
</div>




<div class="modal fade" id="modal_material_add">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Materiales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="volverBaremo" onclick="volverModal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_mate_search">Código</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>

                <input type="text" class="form-control" id="text_mate_search" name="text_mate_search">
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_mate_des_seacrh">Descripción</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_mate_des_seacrh" name="text_mate_des_seacrh" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="consultaMaterialAdd()">Consultar</button>
          </div>
      </div>

      <div class="row">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th></th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Precio</th>
              </tr>
            </thead>
            <tbody id="tbl_mate_add">
              <tr>
                
              </tr>
            </tbody>
            
          </table>
      </div>

      <div class="modal-footer">
        <button type="button" onclick="volverModalMaterial()">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>


