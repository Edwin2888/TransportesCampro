<div class="modal fade" id="modal_gestores_view">
  <div class="modal-dialog modal-lg" role="document" style="width:75%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Gestores</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">

      <button class="btn btn-primary  btn-cam-trans btn-sm" id="create_gestores" type="submit" name="consultar" value="consultar">
        <i class="fa fa-plus"></i> &nbsp;&nbsp;Crear Gestor
    </button>

      </div>
      <div id="tbl_datos_gestores">
        @include('proyectos.electricaribe.validador.tables.tablegestores')
        
      </div>
     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>