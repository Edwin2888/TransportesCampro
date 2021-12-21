
<?php


// ==========================================================================================================================
// Conexión a la base datos
// ==========================================================================================================================
$connectionInfoParque = array("Database" => "Parque", "UID" => "campro_web_v2", "PWD" => "NBbaX3g8D7");
$gconParque = sqlsrv_connect("172.16.50.12", $connectionInfoParque);
$optionsParque = array("Scrollable" => SQLSRV_CURSOR_KEYSET);


function getServicios($gconParque) {
  $servicios = array();

  $sql = "SELECT
            *
          FROM tra_servicios;";

  $query = sqlsrv_query($gconParque, $sql);

  while ($servicio = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
      $servicios[] = $servicio;
  }

  sqlsrv_free_stmt($query);

  return $servicios;
}
?>

<div class="modal fade" id="modal_ingreso_taller">
    <div class="modal-dialog modal-lg" role="document" style="width:96%">
        <div class="modal-content">
            <div class="modal-header modal-filter panel-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5 class="modal-title">Novedades incidencia - Ingreso a taller</h5>

            </div>
            <div class="modal-body" id="tbl_novedades_ingreso_taller">
            </div>

                @if($acceso == "W")
            <div class="col-md-12">
                <div class="row">
                        <div class="col-md-4">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="select_tipo_incidencia">Incidencia:</label>
                                    <input name="txt_incidencia_ingreso_taller" type="text" readonly class="form-control" id="txt_incidencia_ingreso_taller"  />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_tiempo_estimado_ingreso_taller">Tiempo estimado:</label>
                                    <input name="txt_tiempo_estimado_ingreso_taller" readonly type="number" class="form-control" id="txt_tiempo_estimado_ingreso_taller"  />
                                </div>
                            </div>  
                        </div>

                               <div class="col-md-4">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_org_compras">Org Compras:</label>
                                    <input name="txt_org_compras" readonly type="text" class="form-control" id="txt_org_compras"  />
                                </div>
                            </div>  
                        </div>

                               <div class="col-md-4">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_pep">Elemento PEP:</label>
                                    <input name="txt_pep" readonly type="text" class="form-control" id="txt_pep"  />
                                </div>
                            </div>  
                        </div>

                         <div class="col-md-4">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="txt_centro_logistico">Centro Logistico:</label>
                                    <input name="txt_centro_logistico" readonly type="text" class="form-control" id="txt_centro_logistico"  />
                                </div>
                            </div>  
                        </div>

                        

                    </br></br>

                           <div class="col-md-4">
                                          <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                               <label for="servicio">
                                                SERVICIO: <span style="color: red;">(*)</span>:
                                                  <?php $servicios = getServicios($gconParque);
                                                   ?>
                                               <label for="servicio">

                                                <select
                                                        name="servicio"
                                                        id="servicio"
                                                        required
                                                        class="form-control">
                                                    <option value=""> - Seleccione el Servicio - Descp - Mat_Grp</option>
                                                    
                                                      <?php 
                                                        $servicio_id = 0;
                                                      foreach ($servicios as $servicio_item): ?>
                                                          <option value="<?php print "{$servicio_item['numero_servicio']}" ?>"
                                                              <?php if ($servicio_id == $servicio_item['numero_servicio']) {
                                                                  echo "selected";
                                                              } ?>>
                                                              <?php 
                                                              $serv = $servicio_item['numero_servicio'] . ' - ' .$servicio_item['descripcion'] . ' - ' .$servicio_item['grupo_articulos'];
                                                              print strtoupper( $serv) ?>
                                                          </option>
                                                      <?php endforeach; ?>
                                                </select>
                                            </label>
                                        </div>
                                         </div>
                                    </div>

                                       <div class="col-md-4">
                                          <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                               <label for="servicio1">
                                                SERVICIO: <span style="color: red;">(opcional)</span>:
                                                  <?php $servicios = getServicios($gconParque);
                                                   ?>
                                               <label for="servicio1">

                                                <select
                                                        name="servicio1"
                                                        id="servicio1"
                                                        
                                                        class="form-control">
                                                    <option value=""> - Seleccione el Servicio - Descp - Mat_Grp</option>
                                                    
                                                      <?php 
                                                        $servicio_id = 0;
                                                      foreach ($servicios as $servicio_item): ?>
                                                          <option value="<?php print "{$servicio_item['numero_servicio']}" ?>"
                                                              <?php if ($servicio_id == $servicio_item['numero_servicio']) {
                                                                  echo "selected";
                                                              } ?>>
                                                              <?php 
                                                              $serv = $servicio_item['numero_servicio'] . ' - ' .$servicio_item['descripcion'] . ' - ' .$servicio_item['grupo_articulos'];
                                                              print strtoupper( $serv) ?>
                                                          </option>
                                                      <?php endforeach; ?>
                                                </select>
                                            </label>
                                        </div>
                                         </div>
                                    </div>

                                       <div class="col-md-4">
                                          <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                               <label for="servicio2">
                                                SERVICIO: <span style="color: red;">(opcional)</span>:
                                                  <?php $servicios = getServicios($gconParque);
                                                   ?>
                                               <label for="servicio2">

                                                <select
                                                        name="servicio2"
                                                        id="servicio2"
                                                        
                                                        class="form-control">
                                                    <option value=""> - Seleccione el Servicio - Descp - Mat_Grp</option>
                                                    
                                                      <?php 
                                                        $servicio_id = 0;
                                                      foreach ($servicios as $servicio_item): ?>
                                                          <option value="<?php print "{$servicio_item['numero_servicio']}" ?>"
                                                              <?php if ($servicio_id == $servicio_item['numero_servicio']) {
                                                                  echo "selected";
                                                              } ?>>
                                                              <?php 
                                                              $serv = $servicio_item['numero_servicio'] . ' - ' .$servicio_item['descripcion'] . ' - ' .$servicio_item['grupo_articulos'];
                                                              print strtoupper( $serv) ?>
                                                          </option>
                                                      <?php endforeach; ?>
                                                </select>
                                            </label>
                                        </div>
                                         </div>
                                    </div>

                        <div class="col-md-4" style="display:none">
                            <div class="row" style="margin-top:5px;">
                                <div class="form-group has-feedback">
                                    <label for="costo">Costo:</label>
                                    <input name="costo" type="text" class="form-control" id="costo"  value="0"/>
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea id="txt_obser_ingreso_Taller" placeholder="Observación" style="height:100px;    margin-bottom: 15px;" class="form-control"></textarea>
                        </div>
                    </div>

                    <div  class="btn btn-primary btn-cam-trans btn-sm btn-maps" onclick="saveIngresoTaller()" style="    margin-left: 16px;"><i class="fa fa-save"></i> &nbsp; Guardar</div>
                    <div  class="btn btn-primary btn-cam-trans btn-sm btn-maps" onclick="cancelarIngresoTaller()"><i class="fa fa-times"></i> &nbsp; Cancelar ingreso a taller</div>

                </div>
            @endif
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>