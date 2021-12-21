<div class="modal fade" id="modal_plan_accion">
  <div class="modal-dialog modal-lg" role="document" style="width:75%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Plan de acción <b id="nic_select"></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtaccion">Item no conforme</label>
              <div class="col-sm-9">
                  <div class="input-group" style="    width: 100%;">
                    <select class="form-control" id="plan_accion_select">
                    @foreach($form as $key => $valor)
                        @if($valor->respuesta == 1)
                            <option value="{{$valor->id_pregunta}}">{{$valor->descrip_pregunta}}</option>
                        @endif
                     @endforeach
                    </select>
                  </div>
            </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtaccion">Análisis de causas</label>
              <div class="col-sm-9">
                  <div class="input-group" style="    width: 100%;">
                    <select class="form-control" id="analisis_causa_modal">
                    @foreach($causas as $key => $valor)
                            <option value="{{$valor->id}}">{{$valor->analisis}}</option>
                     @endforeach
                    </select>
                  </div>
            </div>
            </div>
          </div>
        </div>

     </div>
     <br>


      <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtaccion">Acción</label>
              <div class="col-sm-9">
                  <div class="input-group" style="    width: 100%;">
                    <input type="text" class="form-control" id="txtaccion" name="txtaccion"  >
                  </div>
            </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtresponsable">Responsable</label>
              <div class="col-sm-9">
                  <div class="input-group" style="    width: 100%;">
                    <input type="text" class="form-control" id="txtresponsable" name="txtresponsable"  >
                  </div>
            </div>
            </div>
          </div>
        </div>
     </div>
     <br>


     <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtcedula">Cédula</label>
              <div class="col-sm-9">
                  <div class="input-group" style="    width: 100%;">
                    <input type="text" class="form-control" id="txtcedula" name="txtcedula"  >
                  </div>
            </div>
            </div>
          </div>
        </div>
      </div>




     <br>
      <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtaccion">Fecha límite</label>
              <div class="col-sm-9">
                <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"  style="    width: 100%;">
                        <input class="form-control" size="16" style="height:30px;" type="text"
                               name="fecha_limite" id="fecha_limite"
                               placeholder="dd/mm/aaaa">

                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
            </div>
          </div>
        </div>

        <div class="col-md-6" id="oculta1">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtaccion">Fecha cierre</label>
              <div class="col-sm-9">
                <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"  style="    width: 100%;">
                        <input class="form-control" size="16" style="height:30px;" type="text"
                               name="fecha_cierre" id="fecha_cierre"
                               placeholder="dd/mm/aaaa">

                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
            </div>
          </div>
        </div>
     </div>
     <br>
      <div class="row">
        <div class="col-md-6" id="oculta2">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtobsercierre">Observación cierre</label>
              <div class="col-sm-9">
                  <div class="input-group" style="    width: 100%;">
                    <input type="text" class="form-control" id="txtobsercierre" name="txtobsercierre"  >
                  </div>
            </div>
            </div>
          </div>
        </div>

        <div class="col-md-6" id="oculta3">
          <div class="row">
            <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="fileevidencia">Evidencia</label>
              <div class="col-sm-9">
                  <div class="input-group" style="    width: 100%;">
                    <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar imagen" data-size="sm" id="fileevidencia" name="fileevidencia"  
                    accept="application/pdf">
                  </div>
            </div>
            </div>
          </div>
        </div>
     </div>
     </div>

      
      
     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        @if($inspeccion->estado <> 'E2')
          <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn_save_plan" onclick="savePlanAccion()">Guardar</button>
        @endif
      </div>
    </div>
  </div>
</div>
</div>