<div class="modal fade" id="modal_observacion_anulacion">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Anular documento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

      
        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtObserAnulacion">Observación de anulación</label>
            <div class="col-sm-9">
                <textarea style="    height: 130px;" class="form-control" id="txtObserAnulacion" name="txtObserAnulacion"></textarea>
            </div>
          </div>
        </div>

       
        </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="anularDocumentoSave()">Guardar</button>
      </div>

    </div>
  </div>
</div>
</div>