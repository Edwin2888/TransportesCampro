<div class="modal fade" id="modal_add_opcion">
  <div class="modal-dialog modal-md" role="document" >
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Agregar/Editar opciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

       <div class="form-group has-feedback">
          <label for="id_orden">Agregar opción:</label>
          <br>
          <input type="text" placeholder="Ingrese opción"  class="form-control"  style="float:left;width:50%;margin-right:12px;" id="text1"/>
          
          <button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="addOpcion()">
              <i class="fa fa-plus"></i> &nbsp;&nbsp;Agregar opción
          </button>
          
      </div>

       <table id="tbl_causa" class="table table-striped table-bordered" cellspacing="0" width="99%">
        <thead>
            <tr>
                <th style="width:10px;">ID</th>
                <th >Descripción</th>
                <th style="width:10px;"></th>
            </tr>
        </thead>
        <tbody id="tbl_add_opcion">
            
        </tbody>
    </table>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn_save_plan" onclick="saveOpcion()">Guardar</button>
      </div>
    </div>
  </div>
</div>
</div>