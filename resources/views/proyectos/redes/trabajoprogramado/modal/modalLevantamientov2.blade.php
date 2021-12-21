

<div class="modal fade" id="modal_agregar_estructura">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Crear levantamiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="row">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3 col-md-offset-1" for="select_wbs_levantamiento">WBS</label>
            <div class="col-sm-7">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <select type="text" class="form-control" id="select_wbs_levantamiento" name="select_wbs_levantamiento" >
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
                  <select type="text" class="form-control" id="select_nodos_levantamiento" name="select_nodos_levantamiento" >
                  option value="0">Seleccione</option>
                  </select>
                </div>
          </div>
          </div>
      </div>


      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_tipo_c_recurso">Estructura</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <select type="text" class="form-control" id="select_estruc" name="select_estruc" >
                  <option value="0">Seleccione</option>
                  @foreach($estruc as $es => $val)
                    <option value='{{$val->id}}'>{{strtoupper($val->des_estruc)}}</option>
                  @endforeach
                  </select>
              </div>
            </div>
          </div>
      </div>


      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_movil_recurso">Tipos</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <select type="text" class="form-control" id="select_tipo" name="select_tipo" >
                  <option value="0">Seleccione</option>
                </select>
              </div>
            </div>
          </div>
      </div>




<div id="exTab2" class="container" style="width:100%;margin-top:15px;"> 
<ul class="nav nav-tabs">
      <li class="active">
        <a  href="#1" data-toggle="tab">Mano de obra</a>
      </li>
      <li><a href="#2" data-toggle="tab">Materiales</a>
      </li>
    </ul>
      <div class="tab-content ">
        <div class="tab-pane active" id="1">
            <table class="table table-striped table-bordered" cellspacing="0" width="99%;" style="margin-top:10px;">
              <thead>
                <th>Baremo</th>
                <th>Descripción</th>
                <th>Cant</th>
              </thead>
              <tbody id="tbl_bar_lev">
                
              </tbody>
            </table>
        </div>
        <div class="tab-pane" id="2">
            <table class="table table-striped table-bordered" cellspacing="0" width="99%;" style="margin-top:10px;">
              <thead>
                <th>Material</th>
                <th>Descripción</th>
                <th>Uni</th>
                <th>Cant</th>
              </thead>
              <tbody id="tbl_mat_lev">
                
              </tbody>
            </table>
        </div>
      </div>
  </div>

      

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="saveLevantamiento()">Guardar</button>
      </div>
    </div>
  </div>
</div>
</div>