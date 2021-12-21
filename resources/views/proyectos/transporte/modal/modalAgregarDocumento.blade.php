<div class="modal fade" id="modal_add_documento">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar Documentos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

        {!! Form::open(['url' => 'insertaDocumentosVehiculo', "method" => "POST", "files" => true]) !!}
        {{ csrf_field() }}
        <input type="hidden" value ="{{$placa}}" name="placa" id="placa_vehi"/>
        <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtTipoSoporte">Tipo de soporte</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtTipoSoporte" name="txtTipoSoporte" readonly />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtReferenciaDoc">Referencia del documento</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtReferenciaDoc" name="txtReferenciaDoc" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <input type="hidden" name="id_doc" id="id_doc"/>
            <input type="hidden" name="opc_doc" id="opc_doc" value="1"/>
            <label class="control-label col-sm-3" for="txtFechaVen">Fecha de vencimiento</label>
            <div class="col-sm-9">
                <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"   style="  width: 100%;">
                  <input class="form-control" size="16" style="height:30px;" type="text"     name="txtFechaVen" id="txtFechaVen"    placeholder="dd/mm/aaaa">
                  <span class="input-group-addon"><i class="fa fa-times"></i></span>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtNombreEntidad">Nombre entidad</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtNombreEntidad" name="txtNombreEntidad" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="file_archiv_impor">Seleccione el archivo a importar</label>
            <div class="col-sm-9">
                <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar archivo" data-size="sm" id="file_archiv_impor" name="file_archiv_impor" />
            </div>
          </div>
        </div>
        </div>
      </div>
      

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden">Agregar documento</button>
      </div>

      {!!Form::close()!!}
    </div>
  </div>
</div>
</div>