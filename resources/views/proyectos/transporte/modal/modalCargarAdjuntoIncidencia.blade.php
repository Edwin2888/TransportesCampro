<div class="modal fade" id="modal_upload_adjunto_incidencia">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Adjunto archivo</h5>

            </div>
            <div class="modal-body" id="tbl_novedades_ingreso_taller">
             {!! Form::open(['url' => 'insertaAdjuntoIncidencia', "method" => "POST", "files" => true]) !!}
                <input type="hidden"  name="incidencia" value="{{$datos->incidencia}}"/>
                <div class="row" style="margin-top:5px;">
                    <div class="form-group has-feedback">
                    <label class="control-label col-sm-3" for="file_adjunto_primer_mante">Adjunto</label>
                    <div class="col-sm-9">
                        <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar adjunto" data-size="sm" id="file_archiv_impor" name="file_upload" />
                    </div>
                  </div>
                </div>
                </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-save-primer-mante">Guardar adjunto</button>
              
            {!!Form::close()!!}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>