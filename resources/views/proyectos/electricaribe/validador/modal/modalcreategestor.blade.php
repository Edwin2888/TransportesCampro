<div class="modal fade" id="modal_gestores_create_view">
  <div class="modal-dialog modal-md" role="document" >
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Gestores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">

      <div class="row">
          <div class="form-group has-feedback">
          <label class="control-label col-sm-3" for="txt_nombre_gestor">Nombre gestor</label>
            <div class="col-sm-9">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="txt_nombre_gestor" name="txt_nombre_gestor">
                </div>
          </div>
          </div>
      </div>
        
      <div class="row" style="margin-top:5px">
          <div class="form-group has-feedback">
          <label class="control-label col-sm-3" for="txt_iden_gestor">Identicación gestor</label>
            <div class="col-sm-9">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="txt_iden_gestor" name="txt_iden_gestor">
                </div>
          </div>
          </div>
        </div>

      </div>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-wbs-upload" onclick="saveGestor()">Guardar gestor</button>
      </div>
    </div>
  </div>
</div>
</div>