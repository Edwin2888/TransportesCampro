<div class="modal fade" id="modal_deschequear_dia"   data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Deschequear día</h5>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

      
        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtObserDia">Agrege la observación de por que no se va a pagar el día seleccionado.</label>
            <div class="col-sm-9">
                <textarea style="    height: 130px;" class="form-control" id="txtObserDia" name="txtObserDia"></textarea>
            </div>
          </div>
            
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="soporteobserdia">Soporte.</label>
            <div class="col-sm-9">
                <input type="file" class="form-control" id="soporteobserdia" name="soporteobserdia">
            </div>
          </div>
        </div>

       
        </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="cancelarDia();">Cancelar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="guardarObservacionDia()">Guardar</button>
      </div>

    </div>
  </div>
</div>
</div>