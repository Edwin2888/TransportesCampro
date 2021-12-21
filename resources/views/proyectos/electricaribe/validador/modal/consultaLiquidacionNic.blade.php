<div class="modal fade" id="modal_liquidacion_view">
  <div class="modal-dialog modal-lg" role="document" style="width:75%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Liquidación NIC <b id="nic_select"></b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">

      <div class="col-md-6">
      <div class="row">
          <div class="form-group has-feedback">
          <label class="control-label col-sm-2" for="id_nic">NIC</label>
            <div class="col-sm-7">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="id_nic" name="id_nic"  readonly="">
                </div>
          </div>
          </div>
      </div>
        
      <div class="row" style="margin-top:5px">
          <div class="form-group has-feedback">
          <label class="control-label col-sm-2" for="id_cliente">Cliente</label>
            <div class="col-sm-7">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="id_cliente" name="id_cliente"  readonly="">
                </div>
          </div>
          </div>
        </div>
      <div class="row" style="margin-top:5px">
          <div class="form-group has-feedback">
          <label class="control-label col-sm-2" for="id_gestor_select">Gestor</label>
            <div class="col-sm-7">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  {!!Form::select('id_gestor_select', $combo, null,['class' => 'form-control','id' => 'id_gestor_select'])!!}
                </div>
          </div>
          </div>
        </div>

      <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="    width: 100%;">
        <label class="control-label col-sm-2" for="fecha_visita">Fecha V.</label>
        <div class="col-sm-7">
          <input class="form-control" size="16" style="height:30px;" type="text"
             value="" name="fecha_visita" id="fecha_visita"
             placeholder="dd/mm/aaaa">
          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        </div>
      </div>

      <button class="btn btn-primary  btn-cam-trans btn-sm" id="update_gestor" onclick="updateGestorVisita()" type="submit" name="consultar" value="consultar">
        <i class="fa fa-save"></i> &nbsp;&nbsp;Actualizar
    </button>

      </div>

      <div class="col-md-6">
       <div class="row" style="margin-top:5px" >
          <table class="tbl_liquidacion">
            <tbody>
              <tr class="row_liquidacion" id="row1">
                <td>A CANCELAR</td>
                <td id="row1.1">01 CUOTA INICIAL</td>
                <td id="row1.2">1</td>
                <td id="row1.3">Valor</td>
              </tr>

              <tr class="row_liquidacion" id="row2">
                <td></td>
                <td>02 A FINANCIAR</td>
                <td>1</td>
                <td>Valor</td>
              </tr>

              <tr class="row_liquidacion" id="row3">
                <td></td>
                <td>03 CARGOS VARIOS</td>
                <td>1</td>
                <td>Valor</td>
              </tr>

              <tr class="row_liquidacion" id="row4">
                <td></td>
                <td>07 IRREGULARIDAD</td>
                <td>1</td>
                <td>Valor</td>
              </tr>

              <tr class="row_liquidacion final">
                <td>Total A CANCELAR</td>
                <td></td>
                <td id="rw1">1</td>
                <td id="rw2">Valor</td>
              </tr>

              <tr class="row_liquidacion" id="row5">
                <td>DESCUENTO INICIAL</td>
                <td>05 PRESCRITA</td>
                <td>1</td>
                <td>Valor</td>
              </tr>

              <tr class="row_liquidacion" id="row6">
                <td></td>
                <td>05.1 IRREGULARIDAD PRESCRITA</td>
                <td>1</td>
                <td>Valor</td>
              </tr>

              <tr class="row_liquidacion" id="row7">
                <td></td>
                <td>06 UNICOM 55</td>
                <td>1</td>
                <td>Valor</td>
              </tr>

              <tr class="row_liquidacion final">
                <td>Total DESCUENTO INICIAL</td>
                <td></td>
                <td id="rw3">1</td>
                <td id="rw4">Valor</td>
              </tr>


              <tr class="row_liquidacion" id="row8">
                <td>DESCUENTO POR CUMPLIMIENTO</td>
                <td>04 SEGUNDO ACUERDO</td>
                <td>1</td>
                <td>Valor</td>
              </tr>

              <tr class="row_liquidacion final" id="row9">
                <td>Total DESCUENTO POR CUMPLIMIENTO</td>
                <td></td>
                <td id="rw5">1</td>
                <td id="rw6">Valor</td>
              </tr>

              <tr class="row_liquidacion final">
                <td>TOTAL</td>
                <td></td>
                <td id="rw7">1</td>
                <td id="rw8">Valor</td>
              </tr>

            </tbody>
          </table>

       </div>
      </div>
        </div>
      <div id="tbl_datos_liquidadcion">
        
      

      </div>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>