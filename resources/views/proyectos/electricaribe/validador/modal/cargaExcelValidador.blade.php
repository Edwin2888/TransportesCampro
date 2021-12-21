<div class="modal fade" id="modal_import_1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar Excel Validador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">

     <!-- <p>ITEMS a tener en cuenta para importar el archivo de Excel</p>
        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>1- El archivo xxxxxx</p>
          <img src="../../img/ImagenExcel.png">  
        </div>

        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>2- El archivo xxxxxx</p>
          <img src="../../img/ImagenExcel1.png">  
        </div>

        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>3- El archivo xxxxxx</p>
          <img src="../../img/ImagenExcel2.png">  
        </div>

        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>4- El archivo xxxxxx</p>
          <img src="../../img/ImagenExcel3.png">  
        </div>-->

      </div>
     
     <form method="POST" action="../../cargarExcelValidador" accept-charset="UTF-8" enctype="multipart/form-data">
      <input type="hidden" value="{{csrf_token()}}" name="_token">
      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Archivo Excel:</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="file" class="form-control" id="file_upload" name="file_upload" >
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