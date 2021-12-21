<div class="modal fade" id="modal_create_causacion">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Crear/Editar causación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">


        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtPlaca">Placa</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtPlaca" name="txtPlaca" style="width:50%;float:left;margin-right:4px;" onblur ="consultaPlaca()">
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtDesConcepto">Documento referencia</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtDesConcepto" name="txtDesConcepto" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <input type="hidden" name="id_doc" id="id_doc"/>
            <input type="hidden" name="opc_doc" id="opc_doc" value="1"/>
            <label class="control-label col-sm-3" for="txtFecha">Fecha</label>
            <div class="col-sm-9">
                <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"   style="  width: 100%;">
                  <input class="form-control" size="16" style="height:30px;" type="text"     name="txtFecha" id="txtFecha"    placeholder="dd/mm/aaaa">
                  <span class="input-group-addon"><i class="fa fa-times"></i></span>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtObser">Observaciones</label>
            <div class="col-sm-9">
                <textarea type="text" class="form-control" id="txtObser" name="txtObser" style="resize:none;"></textarea>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtValor">Valor</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" id="txtValor" name="txtValor" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtRecepcion">Recepción</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtRecepcion" name="txtRecepcion" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="CheApro">Aprovisionando</label>
            <div class="col-sm-9">
                <input id="CheApro" type="checkbox" class="form-control" >
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="selConcepto">Concepto</label>
            <div class="col-sm-9">
                {!!Form::select('selConcepto', $conceptos, null, ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selConcepto"])!!}
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="selProveedor">Proveedor</label>
            <div class="col-sm-9">
                {!!Form::select('selProveedor', $proveedor, null, ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selProveedor"])!!}
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="selContratante">Contratante</label>
            <div class="col-sm-9">
                {!!Form::select('selContratante', $contratantes, null, ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selContratante"])!!}
            </div>
          </div>
        </div>

        


        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="saveCausacion()">Guardar</button>
      </div>

    </div>
  </div>
</div>
</div>