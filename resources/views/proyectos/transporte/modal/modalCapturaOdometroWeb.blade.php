<div class="modal fade" id="modal_odometro">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Captura Odómetro</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
      <div class="row" style="    margin-top: 16px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3" for="text_num_circu_add">Fecha</label>
            <div class="col-sm-7">
                <div class="input-group date form_date" data-date=""
                     data-date-format="dd/mm/yyyy" style="width:170px;">
                    <input class="form-control" size="16" style="height:30px;" type="text"
                           value="" name="fec_registro" id="fec_registro" placeholder="dd/mm/aaaa" required>
                    <span class="input-group-addon"><i class="fa fa-times"></i></span>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
          </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-3" for="text_nomb_circu_add">Kilometraje</label>
          <div class="col-sm-7">
              <div class="input-group">
                <input style="    margin-left: 12px;    width: 127%;" type="text" class="form-control" placeholder="Kilometraje" id="txt_kilometraje" name="txt_kilometraje">
              </div>
            </div>
        </div>
        </div>


      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="save_odometer_btn">Guardar</button>
      </div>
    </div>
  </div>
</div>
</div>

