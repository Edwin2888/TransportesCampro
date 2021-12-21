<div class="modal fade" id="modal_edit_documento">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Editar documento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

        
        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" >Canon actual</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" disabled value="${{number_format($data->canon_actual, 2)}}"/>
            </div>
          </div>
        </div>


        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txt_nuevo_canon">Nuevo Canon</label>
            <div class="col-sm-9">
                <b style="color:red;    font-size: 10px;">* Ingrese el nuevo canon sin símbolos ni puntos</b>
                <br>
                <input type="text" class="form-control" id="txt_nuevo_canon" name="txt_nuevo_canon" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            
              <label class="control-label col-sm-3" for="txtObserEditarCanon">Observación</label>
            

            <div class="col-sm-9">
                <textarea style="    height: 130px;" class="form-control" id="txtObserEditarCanon" name="txtObserEditarCanon"></textarea>
            </div>
          </div>
        </div>

       
        </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="guardarNuevoCanon()">Guardar</button>
      </div>

    </div>
  </div>
</div>
</div>