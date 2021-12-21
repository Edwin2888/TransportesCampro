<div class="modal fade" id="moda_manual">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Crear/Editar Manual</h5>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

        {!! Form::open(['url' => 'saveManual', "method" => "POST"]) !!}
        <input type="hidden"  name="manual_id" id="manual_id" value="0"/>
        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtKilometraje">Proyecto</label>
            <div class="col-sm-9">
                <select name="id_proyecto_add" id="id_proyecto_add" class="form-control">
                        <option value="T01" >RECURSOS HUMANOS</option>
                        <option value="T02" >SUPERVISIÓN Y SST</option>
                        <option value="T03" >TRANSPORTES</option>
                        <option value="T04" >HERRAMIENTAS</option>
                        @foreach($proyecto as $key => $valor)
                            <option value="{{$valor->prefijo_db}}">{{$valor->proyecto}}</option>
                        @endforeach                       
                </select>
            </div>
          </div>
        </div>


        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtTituloManual">Título</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtTituloManual" name="txtTituloManual" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtDescripcionManual">Descripción</label>
            <div class="col-sm-9">
                <textarea style="resize:none;height:90px;" type="text" class="form-control" id="txtDescripcionManual" name="txtDescripcionManual"></textarea>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtversion">Versión</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtversion" name="txtversion" maxlength="10" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtcodigo">Código</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="txtcodigo" name="txtcodigo" maxlength="50" />
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="select_tipo_manual">Tipo de manual</label>
            <div class="col-sm-9">
                <select name="select_tipo_manual" id="select_tipo_manual" class="form-control">
                        <option value="1">Aplicación web</option>
                        <option value="2">Aplicación móvil</option>
                </select>
            </div>
          </div>
        </div>

        <div class="row" style="margin-top:5px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3" for="txtEmbebidoManial">Embebido</label>
            <div class="col-sm-9">
                <textarea style="resize:none;height:120px;" type="text" class="form-control" id="txtEmbebidoManial" name="txtEmbebidoManial"></textarea>
            </div>
          </div>
        </div>
    </div>
      </div>
      <div class="modal-footer">
        <button type="submit" onclick="saveManual();" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-save-primer-mante">Guardar manual</button>
      </div>
      {!!Form::close()!!}
    </div>
  </div>
</div>
</div>