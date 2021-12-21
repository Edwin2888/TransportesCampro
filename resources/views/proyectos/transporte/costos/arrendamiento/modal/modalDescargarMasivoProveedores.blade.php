<div class="modal fade" id="modal_masivo_proveedor">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Masivo de documentos por proveedor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

      <form method="POST" action="{{url('/')}}/transporte/costos/arrendamientosdownloadmasivo" accept-charset="UTF-8" method="POST">
        
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <div class="row" style="margin-top:20px;"  >
          <div class="form-group has-feedback">
              <label class="control-label col-sm-3 col-md-offset-1 " for="fecha1">Fecha inicio</label>
              <div class="col-sm-7">
                   <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" style="width:200px;">
                      <input type='text' class="form-control"   id="fecha1" name="fecha1" />
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
                      <input type='text' class="form-control"   id="fecha2" name="fecha2" />
                          <span class="input-group-addon"><i class="fa fa-times"></i></span>
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
              </div>
            </div>
        </div>

        <div class="row" style="margin-top:20px;"  >
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " >Proveedor</label>
            <div class="col-sm-7">
              {!!Form::select('Cbo_proveedores', $proveedores, Session::get('Cbo_proveedores'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"Cbo_proveedores"])!!}
            </div>
          </div>
        </div>

        <button  class="btn btn-primary" style="background-color:#0060ac;color:white;margin-left: 38%;    margin-top: 20px;" id="btn-add-nodos-orden" onclick="validarExport(event)">Generar masivo</button>


      </form>
       
        </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>
</div>