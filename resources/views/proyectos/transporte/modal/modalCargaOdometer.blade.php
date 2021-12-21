<div class="modal fade" id="modal_import_odometer">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar Kilometrajes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p style="display:inline-block">Puede descargar el formato del excel desde  
          <form method="POST" action="../../downloadFormato" id="download_format" accept-charset="UTF-8" style="display:inline-block;color:blue;cursor:pointer;">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
          <input type="hidden" value="1" name="opc" />
            &nbsp <b onclick="formato_download()">aquí</b>
          </form>, para cargar los kilometrajes</p>
        <p style="color:red;font-weight:bold;margin-top:10px;">* Recuerde cargar los kilometrajes en el formato que se menciona con anterioridad.</p>
        <p style="color:red;font-weight:bold;margin-top:10px;">* Recuerde que la fecha tiene el siguiente formato dd/mm/yyyy.</p>
        <form method="POST" action="../../transversal/odometro/masivo" accept-charset="UTF-8" enctype="multipart/form-data">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
          <input type="hidden" value="true" name="guardar_odometro">
          <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar archivo" data-size="sm" name="archivo_excel_descargos" id="archivo_excel_descargos"/>
          <button type="submit" class="btn btn-primary" style="width:30%;margin-left:35%;margin-top:20px;">Cargar</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>