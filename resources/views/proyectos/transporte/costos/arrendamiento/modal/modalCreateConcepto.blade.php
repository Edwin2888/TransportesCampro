<div class="modal fade" id="modal_create_concepto">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Crear/Editar concepto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

        {!! Form::open(['url' => 'transporte/costos/conceptos/saveConcepto', "method" => "POST", "files" => true]) !!}
        {{ csrf_field() }}
        
       <input type="hidden" name="id" value = "" id="id"/>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtNombreConcepto">Nombre</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtNombreConcepto" name="txtNombreConcepto" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtDesConcepto">Descripción</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtDesConcepto" name="txtDesConcepto" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="selEstado">Estado</label>
            <div class="col-sm-9">
                <select class="form-control" id="selEstado" name="selEstado">
                  <option>Seleccione</option>
                  <option value="A">Activo</option>
                  <option value="I">Inactivo</option>
                </select>
                
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="file_archiv_impor">Anexo</label>
            <div class="col-sm-9">
                <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar archivo" data-size="sm" id="file_archiv_impor" name="file_archiv_impor" />
            </div>

          </div>
        </div>
        <a href="" id="anexo" target="_blank" style="margin-left:28%">Ver anexo</a>
        </div>
      </div>
      

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="saveConcepto()">Guardar</button>
      </div>

      {!!Form::close()!!}
    </div>
  </div>
</div>
</div>