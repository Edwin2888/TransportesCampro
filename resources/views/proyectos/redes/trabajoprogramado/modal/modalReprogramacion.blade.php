<div class="modal fade" id="modal_reprogramacion">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Re programación de la ManiObra</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-4" for="inputSuccess3">Fecha de reprogramación</label>
            <div class="input-group date form_date col-sm-8" data-date="" data-date-format="dd/mm/yyyy" style="width:63%;">
                <input class="form-control" size="16" style="height:30px;" type="text" value="" name="fec_emision" id="fecha_repro" placeholder="dd/mm/aaaa" required>
                <span class="input-group-addon"><i class="fa fa-times"></i></span>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>
      </div>



      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-4" for="inputSuccess3">Observaciones de reprogramación</label>
          <div class="col-sm-8" style="    padding: 0px;">
                <textarea style="width:100%;    height: 169px;    resize: none;" maxlength="200" id="observacion_repro"></textarea>
            </div>
        </div>
        </div>
      </div> 
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn_repro_maniobra">Re programar</button>
      </div>
    </div>
  </div>
</div>


