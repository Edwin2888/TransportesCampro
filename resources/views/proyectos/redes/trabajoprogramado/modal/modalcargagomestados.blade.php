
<!-- GOM CARGA EXCEL -->
<div class="modal fade" id="modal_gom">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title" style="width: 76%;display: inline-block;">Carga GOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        

        <p style="display:inline-block">Puede descargar el formato del excel desde  
          <form method="POST" action="../../downloadFormato" id="download_format" accept-charset="UTF-8" style="display:inline-block;color:blue;cursor:pointer;">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
          <input type="hidden" value="4" name="opc">          
            &nbsp <b onclick="formato_download()">aquí</b>
          </form>, para cargar las GOMs</p>


        <div class="row">
          <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">PROYECTO:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <input type="text" class="form-control" id="text_proyecto" name="text_proyecto" placeholder="N° de proyecto" readonly/>  
                      </div>
                  </div>
                  <button  onclick="consultaProyectoAdd();"  style="    display: inline-block;    position: relative;    top: -31px;    left: 100%;    width: 20%;
"><span class="input-group-addon" style="    border: 0px;    background: transparent;"><i class="fa fa-search"></i></span></button>
                </div>
            </div>
          </div>
        </div>

        <div class="row" style="    position: relative;    top: -52px;">
         <!-- <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Estado GOM:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <select type="text" class="form-control" id="text_descargo_add" name="text_descargo_add" placeholder="Descargo">  
                          <option value="0">Abierta</option>
                          <option value="1">Confirmada</option>
                          <option value="2">Facturada</option>
                        </select>
                      </div>
                </div>
                </div>
            </div>
          </div>-->
        <form method="POST" action="../../cargarExcelGOMs" accept-charset="UTF-8" enctype="multipart/form-data" id="form_add" >
          <input type="hidden" value="{{csrf_token()}}" name="_token"> 
          <input type="hidden" name="txt_estado" id="txt_estado"> 
          <input type="hidden" name="txt_proy" id="txt_proy"> 
          <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Archivo Excel GOM:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                      <input type="file" class="form-control" id="file_upload" name="file_upload" >
                      </div>
                </div>
                </div>
            </div>
          </div>
          <div class="col-md-12" style="text-align:center;">
            <button type="submit" onclick="save_gom_upload()" class="btn btn-primary" style="margin-top:10px; background-color:#0060ac;color:white;" id="btn-wbs-upload" onclick="mostrarSincronizacion()">Importar</button>
          </div>

          </form>
          <div class="col-md-12" style="    margin-top: 20px;">
            <div class="col-md-12" id="tbl_descargos_asociadas">
                
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



<div class="modal fade" id="modal_add_proyecto_gom">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Proyecto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="volverBaremo" onclick="volverModal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_mate_search">N° de proyecto</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>

                <input type="text" class="form-control" id="text_mate_search" name="text_mate_search">
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_mate_des_seacrh">Nombre de proyecto</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_mate_des_seacrh" name="text_mate_des_seacrh" >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="consultaProyectoAddTable()">Consultar</button>
          </div>
      </div>

      <div class="row">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th></th>
                <th>N° Proyecto</th>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody id="tbl_mate_add">
              <tr>
                
              </tr>
            </tbody>
            
          </table>
      </div>

      <div class="modal-footer">
        <button type="button" onclick="volverModalMaterial()">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>

