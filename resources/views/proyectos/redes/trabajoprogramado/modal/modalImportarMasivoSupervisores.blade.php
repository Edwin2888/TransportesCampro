<div class="modal fade" id="modal_import_masivo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar Masivo de supervisores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p style="display:inline-block">El formato para cargar el masivo de los supervisores, se encuentra ubicado en <b>Gestión de ManiObras > Seleccione el proyecto Obras Civiles > Exportar masivo supervisores

        
        <p style="color:red;font-weight:bold;margin-top:10px;">* Recuerde que para cargar el masivo de supervisores, tiene que ser en el formato que se menciona con anterioridad.</p>
        <p style="color:red;font-weight:bold;">* La única información que puede actualizar el masivo es la cédula y nombre del supervisor.</p>
        
        <form method="POST" action="../../transversal/ordenes/importarMasivoSupervisores" accept-charset="UTF-8" enctype="multipart/form-data">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
          <input type="file" class="filestyle" data-buttonName="btn-primary" data-buttonText="  Seleccionar archivo" data-size="sm" name="archivo_excel_masivo" id="archivo_excel_masivo"/>
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