<div class="modal fade" id="modal_import">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar archivo combustible</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <p style="display:inline-block">Puede descargar el formato de excel, haga clic  
          <form method="POST" action="{{url('/')}}/downloadFormato" id="download_format1" accept-charset="UTF-8" style="display:inline-block;color:blue;cursor:pointer;">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
          <input type="hidden" value="6" name="opc" />
            &nbsp <b onclick="formato_download1()">aquí</b>
          </form></p>
          
        <p style="color:red;font-weight:bold;margin-top:10px;">* Recuerde que para cargar el archivo de combustibles tiene que ser con la plantilla que se menciona con anterioridad.</p>
        <p style="color:red;font-weight:bold;margin-top:5px;">* Los valores decimales se representan con la ','</p>
        <p style="color:red;font-weight:bold;margin-top:5px;">* Las unidades de miles y millones se representan con '.'</p>
       
        <br>
     <form method="POST" action="{{url('/')}}/transporte/costos/cargaCombustible" accept-charset="UTF-8" enctype="multipart/form-data">
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




<script type="text/javascript">
  function formato_download1()
  {
      document.querySelector("#download_format1").submit();
  }  

</script>




