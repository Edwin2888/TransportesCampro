<div class="modal fade" id="modal_reponsable">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Cuadrilla</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="row" style="margin-top:20px;" id="consulta_cuadrillero_0">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_nombre_rec">Móvil</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="txt_movil" name="txt_movil" />
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;" id="consulta_cuadrillero_0">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_nombre_rec">Cedula líder</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="txt_cedula" name="txt_cedula" />
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;" id="consulta_cuadrillero_0">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_nombre_rec">Nombre líder</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="txt_nombre_lider" name="txt_nombre_lider" />
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="consultaResponsable()">Consultar</button>
          </div>
      </div>

      <div class="row" >
          <table class="table table-striped table-bordered" id="consulta_cuadrillero_6">
            <thead>
              <tr>
                <th></th>
                <th style="width: 100px;">Móvil</th>
                <th style="width: 100px;">Líder</th>
                <th style="width: 300px;">Nombre</th>
              </tr>
            </thead>
            <tbody id="tbl_recu_add2">
              <tr>
                
              </tr>
            </tbody>
          </table>
      </div>

      <div class="modal-footer">
        <button type="button" onclick="salir(2)">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>