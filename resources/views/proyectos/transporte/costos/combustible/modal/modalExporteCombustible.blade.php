
<div class="modal fade" id="modal_exporte_combustible">
  <div class="modal-dialog" role="document">
    <div class="modal-content" >
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Exportar masivo de combustible</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


     <form method="POST" action="{{url('/')}}/transporte/costos/exporteCombustible" accept-charset="UTF-8" method="POST">
      <div class="row" style="margin-top:20px;"  >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="fecha1">Fecha inicio</label>
            <div class="col-sm-7">
                 <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" style="width:200px;">
                    <input type='text' class="form-control"   id="fecha_exporte_1" name="fecha_exporte_1" />
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>

            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;"  >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="fecha2">Fecha fin</label>
            <div class="col-sm-7">
                 <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" style="width:200px;">
                    <input type='text' class="form-control"   id="fecha_exporte_2" name="fecha_exporte_2" />
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
          </div>
      </div>
      </div>
      

      <input type="hidden" name="_token" value="{{ csrf_token() }}" />

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="validarExport();">Exportar combustible</button>
      </div>

      </form>

    </div>
  </div>
</div>
</div>
