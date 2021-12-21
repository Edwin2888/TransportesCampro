<div class="modal fade" id="modal_import_descargo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar descargos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p style="display:inline-block">Puede descargar el formato del excel desde  
          <form method="POST" action="{{url('/')}}/downloadFormato" id="download_format" accept-charset="UTF-8" style="display:inline-block;color:blue;cursor:pointer;">
            <input type="hidden" value="5" name="opc"/>
            <input type="hidden" value="{{csrf_token()}}" name="_token"/>
          
            &nbsp <b onclick="formato_download()">aquí</b>
          </form>, para cargar los descargos</p>
        <p>Los estados manejados para los descargos son los siguiente:</p>
        <li>GENERADO</li>
        <li>SOLICITADO</li>
        <li>VALIDADO</li>
        <li>APROBADO</li>
        <li>CONFIRMADO</li>
        <li>DENEGADO</li>
        <li>INVALIDADO</li>
        <li>EJECUTADO</li>
        <li>ANULADO</li>
        <li>EN DESCARGO</li>
        <p style="color:red;font-weight:bold;margin-top:10px;">* Recuerde que para cargar los descargos tiene que ser en el formato que se menciona con anterioridad.</p>
        <p style="color:red;font-weight:bold;">* Los estados que puede seleccionar, son los que se estipulan con anterioridad.</p>
        
        <form method="POST" action="../../cargaDescargos" accept-charset="UTF-8" enctype="multipart/form-data">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
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