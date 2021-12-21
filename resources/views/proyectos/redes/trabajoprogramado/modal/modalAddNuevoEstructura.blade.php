<div class="modal fade" id="modal_import_nueva_estructura">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar Excel Nueva Estructura</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     
     <form method="POST" action="../../cargarExcelEstructura" accept-charset="UTF-8" enctype="multipart/form-data">
      <input type="hidden" value="{{csrf_token()}}" name="_token">
      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Excel relación de estructuras:</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="file" class="form-control" id="file_upload_estruc_1" name="file_upload_estruc_1" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Excel familia de materiales:</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="file" class="form-control" id="file_upload_estruc_2" name="file_upload_estruc_2" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Excel actividades elementos baremos:</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="file" class="form-control" id="file_upload_estruc_3" name="file_upload_estruc_3" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Excel relación MO y Materiales:</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="file" class="form-control" id="file_upload_estruc_4" name="file_upload_estruc_4" >
              </div>
            </div>
          </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-wbs-upload" onclick="mostrarSincronizacion()">Importar</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>