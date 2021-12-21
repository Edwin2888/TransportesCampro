<div class="modal fade" id="modal_promixo_mantenimiento">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Registrar primer mantenimiento</h5>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

        {!! Form::open(['url' => 'insertaPrimerMantenimiento', "method" => "POST", "files" => true]) !!}
        <input type="hidden"  name="placa" id="placa_vehi_registro_primerMante"/>
        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <input type="hidden" name="id_doc" id="id_doc"/>
            <input type="hidden" name="opc_doc" id="opc_doc" value="1"/>
            <label class="control-label col-sm-3" for="txtFechaUltimoMante">Fecha de último mantenimiento</label>
            <div class="col-sm-9">
                <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"   style="  width: 100%;">
                  <input class="form-control" size="16" style="height:30px;" type="text"     name="txtFechaUltimoMante" id="txtFechaUltimoMante"    placeholder="dd/mm/aaaa">
                  <span class="input-group-addon"><i class="fa fa-times"></i></span>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
              </div>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtKilometraje">Kilometraje</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtKilometraje" name="txtKilometraje" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtObservacionPrimerM">Observación</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtObservacionPrimerM" name="txtObservacionPrimerM" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="file_adjunto_primer_mante">Adjunto</label>
            <div class="col-sm-9">
                <input type="file" class="form-control" id="file_adjunto_primer_mante" name="file_adjunto_primer_mante" />
            </div>
          </div>
        </div>

        <!-- <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <input type="hidden" name="id_doc" id="id_doc"/>
            <input type="hidden" name="opc_doc" id="opc_doc" value="1"/>
            <label class="control-label col-sm-3" for="txtFechaProximoMantenimiento">Fecha de próximo mantenimiento</label>
            <div class="col-sm-9">
                <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"   style="  width: 100%;">
                  <input class="form-control" size="16" style="height:30px;" type="text"     name="txtFechaProximoMantenimiento" id="txtFechaProximoMantenimiento"    placeholder="dd/mm/aaaa">
                  <span class="input-group-addon"><i class="fa fa-times"></i></span>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
              </div>
            </div>
          </div>
        </div> -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-save-primer-mante">Guardar primer mantenimiento</button>
      </div>

      {!!Form::close()!!}
    </div>
  </div>
</div>
</div>