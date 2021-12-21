<style>
    .dataTables_length{
        width: 50%;
        float: left;
    }
    .dataTables_filter{        
        width: 50%;
        float: right;
    }
</style>

<div class="modal fade" id="modal_descuentos_arrendamieno">
  <div class="modal-dialog" role="document" style='width: 100%;  max-width: 940px;'>
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
          <h5 class="modal-title">Descuentos Arrendamiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

      
        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            
              <label class="control-label " for="txtObserAbrir">Detalle descuentos </label>
              <label class="control-label " id="placa"></label>
              <br>

                <div class="col-sm-12">
                    <table id='tabla_descuentos' class="table table-striped table-bordered" cellspacing="0"  style=' max-width: 100% !important;width: 100%;'>
                        <thead>
                            <th> Concepto </th>
                            <th> valor </th>
                            <th> F Creación </th>
                            <th> Usuario </th>
                            <th> Adjunto </th>
                            <th> Acciones </th>
                        </thead>
                    </table>
                </div>
          </div>
        </div>

       
        </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="nuevodescu()">Nuevo</button>
      </div>

    </div>
  </div>
</div>




<div class="modal fade" id="modal_crear_edita_descuentos_arrendamieno">
  <div class="modal-dialog" role="document" style='width: 100%;  max-width: 940px;'>
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
          <h5 class="modal-title" id="titulomodalcreaedit"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form id="formcreaedita" onsubmit="formulario(event)">
              <input type="hidden" id="id_descuento" value="0">
              <input type="hidden" id="id_placa" value="0">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        Concepto de Descuento:
                    </div>
                    <div class="col-md-6">
                        <select class="form-control valida_select" id="id_concepto_decuento" data-val="id_concepto_decuento_val">
                             <option value="0">Seleccione Concepto</option>
                             <?php foreach($conceptodes as $con){ ?>
                                   <option value="<?= $con->id ?>"><?= $con->descripcion ?></option>
                             <?php } ?>
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label id="id_concepto_decuento_val" ></label>
                    </div>
                </div>                
                <div style="clear:both;width:100%;height:3px;"></div>
                
                <div class="form-group">
                    <div class="col-md-3">
                        Valor:
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control valida_enteros solo_numeros" value="" id="valor_cescuento" data-val="valor_descuento_val" >
                    </div>
                    <div class="col-md-3">
                        <label id="valor_descuento_val" ></label>
                    </div>
                </div>
                <div style="clear:both;width:100%;height:3px;"></div>  
                
                <div class="form-group">
                    <div class="col-md-3">
                        Periodo
                    </div>
                    <div class="col-md-6">
                        <table border="0">
                            <tr>
                                <td style="border: solid 0px transparent; padding-left: 0px; padding-right: 4px;" >
                                    <select class="form-control valida_select" id="id_ano_decuento" data-val="id_ano_decuento_val">
                                        <option value="0">Seleccione Año</option>
                                        <?php for($i=2006;$i<=2025;$i++){ ?>
                                               <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php  }
                                        ?>
                                    </select>  
                                </td>
                                <td style="border: solid 0px transparent; padding-left: 4px; padding-right: 4px;">                                    
                                    <select class="form-control valida_select" id="id_mes_decuento" data-val="id_mes_decuento_val">
                                        <option value="0">Seleccione Mes</option>
                                        
                                        <?php for($i=1;$i<=12;$i++){ ?>
                                               <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php  }
                                        ?>
                                    </select>  
                                </td>
                            </tr>
                        </table>                        
                    </div>
                    <div class="col-md-3">                        
                        <label id="id_ano_decuento_val" ></label> 
                        <label id="id_mes_decuento_val" ></label>
                    </div>
                </div>
                <div style="clear:both;width:100%;height:3px;"></div>
                
                <div class="form-group">
                    <div class="col-md-3">
                        Adjunto:
                    </div>
                    <div class="col-md-6">
                        <input type="file" class="form-control " value="" id="adjunto_cescuento" data-val="adjunto_cescuento_val" >
                    </div>
                    <div class="col-md-3">
                        <label id="adjunto_cescuento_val" ></label>
                    </div>
                </div>
                <div style="clear:both;width:100%;height:3px;"></div>
                
                <div class="form-group">
                    <center>
                        <button type="submit" class="btnsubmis btn btn-primary btn-form"  style="color:#ffffff;" ><i class="fa fa-save" aria-hidden="true"></i> Guardar</button>
                        <img src="<?= Request::root() ?>/img/loader6.gif" class="loading" alt="Loading..." style="display:none;" >
                    </center>                                 
                </div>    
                
            </div>
          </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>






<div class="modal fade" id="modal_descuentos_arrendamieno_log">
  <div class="modal-dialog" role="document" style='width: 100%;  max-width: 940px;'>
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
          <h5 class="modal-title">Log Descuentos Arrendamiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

      
        <div class="row" style="margin-top:5px;">
          <div class="form-group has-feedback">
            
              <label class="control-label " for="txtObserAbrir">Detalle </label>
              <label class="control-label " id="placa_log_m"></label>
              <br>

                <div class="col-sm-12">
                    <table id='tabla_descuentos_lod' class="table table-striped table-bordered" cellspacing="0"  style=' max-width: 100% !important;width: 100%;'>
                        <thead>
                            <th> Usuario </th>
                            <th> Accion </th>
                            <th> Fecha </th>
                        </thead>
                    </table>
                </div>
          </div>
        </div>

       
        </div>
      </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>
