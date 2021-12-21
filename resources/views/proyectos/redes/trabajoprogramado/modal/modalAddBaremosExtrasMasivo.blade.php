
<div class="modal fade" id="modal_acti_add_nuevo_modelo" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog md-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Agregar Actividades</h5>
        <button type="button" class="close" onclick="cerrarModalNuevo()" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     
      
      <div class="row" style="margin-top:20px;">
        <div class="col-md-6">
            <label class="control-label col-sm-6  " for="txt_name_baremo">Nombre Baremo</label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="text" class="form-control" id="txt_name_baremo" name="txt_name_baremo" style="text-transform: uppercase;" >
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <label class="control-label col-sm-6" for="txt_cod_bare">Código Baremo</label>
            <div class="col-sm-6">
              <div class="input-group">
                <input type="text" class="form-control" id="txt_cod_bare" name="txt_cod_bare" style="text-transform: uppercase;" >
              </div>
            </div>
          </div>
      </div>

      <button type="button" class="btn btn-primary btn-cam-trans btn-sm" style="    width: 40%;    margin-left: 30%;    margin-bottom: 13px;" onclick="consultarBaremoNuevoModelo()" >Consultar baremos</button>

      <select class="form-control" id="bareSelect"> 
      		
      </select>

      <button type="button" class="btn btn-primary btn-cam-trans btn-sm" style="    width: 40%;    margin-left: 30%;    margin-bottom: 13px;margin-top:10px;" onclick="seleccionarBaremo()" >Seleccionar baremos</button>

	  	<table class="table table-striped table-bordered" cellspacing="0" width="99%">
	  		<thead>
	  			 <tr>
		           <th>Baremo</th>
		           <th>Descripción</th>
		           <th>Cantidad</th>
		           <th>Quitar</th>           
		       </tr>
	  		</thead>
		    <tbody id="datos_aux_bare">
		      
		    </tbody>
		</table>


      </div>


      


      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="cerrarModalNuevo()">Cerrar</button>
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" onclick="saveAgregarActividades()">Agregar baremos</button>
      </div>
    </div>
  </div>
</div>
</div>

