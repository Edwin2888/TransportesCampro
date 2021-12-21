<div class="modal fade" id="modal_carga_masivo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar masivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

        {!! Form::open(['url' => 'cargaExcelMasivoArbolDecisiones', "method" => "POST", "files" => true]) !!}
        

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="file_archiv_impor">Seleccione el archivo a importar</label>
            <div class="col-sm-9">
                <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar archivo" data-size="sm" id="file_archiv_impor" name="file_upload" />
            </div>
          </div>
        </div>
        </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden">Importar</button>
      </div>

      {!!Form::close()!!}
    </div>
  </div>
</div>
</div>