<div class="modal fade" id="moda_despachos">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Asociación de Despachos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row" style="    margin-top: 16px;">
         <label>Filtro</label><br>
         Ingrese DC <input class="form-control" type="text" placeholder="Despacho" style="margin-bottom:6px;    width: 40%;    display: inline-block;" id="filter_dc"/>
         <button class="btn btn-primary" onclick="validarDatosFilterDc()">Consultar</button>

         

         <p style="color:red;margin-bottom:10px;">* Seleccione los <b>DC</b> chequeandolos en el icono <input type="checkbox" style="width:20px;height:20px" checked readonly />  y luego oprima el botón <b style="color:blue">Asociar DC</b></p>
         <table  class="table table-striped table-bordered" cellspacing="0" width="99%">
          <thead>
            <th></th>
            <th>NODO</th>
            <th>DC</th>
            <th>Fecha de necesidad</th>
            <th>Líder</th>            
            <th>GOM</th>
            <th>Observación</th>
          </thead>
          <tbody id="tbl_dat_dc">
            
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>   
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" onclick="seleccionarDCLider()">Asociar DC</button>     
      </div>
    </div>
  </div>
</div>
</div>
