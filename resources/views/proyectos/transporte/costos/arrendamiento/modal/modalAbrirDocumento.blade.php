<div class="modal fade" id="modal_abrir_documento">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">

        @if($data->id_estado == "E3")
          <h5 class="modal-title">Abrir documento</h5>
        @else
          <h5 class="modal-title">Restablecer documento</h5>
        @endif
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

      
        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            
              <label class="control-label col-sm-3" for="txtObserAbrir">Observación</label>
            

            <div class="col-sm-9">
                <textarea style="    height: 130px;" class="form-control" id="txtObserAbrir" name="txtObserAbrir"></textarea>
            </div>
          </div>
        </div>

       
        </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="abrirDocumentoSave()">Guardar</button>
      </div>

    </div>
  </div>
</div>
</div>