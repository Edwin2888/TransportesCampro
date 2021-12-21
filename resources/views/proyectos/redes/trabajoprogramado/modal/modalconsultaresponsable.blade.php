<div class="modal fade" id="modal_reponsable">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Responsable restricción</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="row" style="margin-top:20px;" id="consulta_cuadrillero_0">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_nombre_rec">Nombre responsable</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="txt_responsable" name="txt_responsable" />
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
                <th style="width: 95%;">Responsable</th>
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