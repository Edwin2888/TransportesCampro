<div class="modal fade" id="modal_export_pre">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Exporte preplanillas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


      <div class="row">
          <label class="control-label" for="text_nombre_rec">Fecha de ejecución</label>
      </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                        <label for="text_solicitante_cam">Desde:</label>
                        <div class="input-group date form_date" data-date=""
                                         data-date-format="dd/mm/yyyy" style="width:170px;">
                        <input class="form-control" size="16" style="height:30px;" type="text"
                        value="" name="fec_desde_goms" id="fec_desde_goms" placeholder="dd/mm/aaaa" required>
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
              </div>
          
          <div class="col-md-6">
            <div class="form-group">
                        <label for="text_solicitante_cam">Hasta:</label>
                        <div class="input-group date form_date" data-date=""
                                         data-date-format="dd/mm/yyyy" style="width:170px;">
                        <input class="form-control" size="16" style="height:30px;" type="text"
                        value="" name="fec_hasta_goms" id="fec_hasta_goms" placeholder="dd/mm/aaaa" required>
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
              </div>
          </div>
          
          <form method="POST" action="../../downloadConsolidado" accept-charset="UTF-8" id="form_donwload_goms">
            <input type="hidden" value="{{csrf_token()}}" name="_token">
            <input type="hidden" id="fecha1_goms" name="fecha1_goms" />
            <input type="hidden" id="fecha2_goms" name="fecha2_goms"/>
          </form>
      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="downloadPrePlanillas()">Descargar</button>
          </div>
      </div>

      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>