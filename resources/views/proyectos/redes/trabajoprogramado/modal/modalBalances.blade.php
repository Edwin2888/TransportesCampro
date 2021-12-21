<div class="modal fade" id="modal_balances">
  <div class="modal-dialog modal-lg" role="document" style="width:95%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Balance de materiales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body tipo_modal">
     
      
      <div class="row" style="    margin-top: 16px;">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-2" for="select_nodos">Seleccione tipo de balance</label>
            <div class="col-md-3">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <select type="text" class="form-control" id="select_balances" name="select_balances" style="padding:0px;">
                    <option value="0">Seleccione</option>
                    <option value="1">Balance por Documento DC</option>
                    <option value="2">Balance por ManiObra (Orden)</option>
                    <option value="3">Balance por NODO</option>
                    <option value="4">Balance por GOM</option>
                    <option value="5">Balance por Proyecto</option>
                </select>
                </div>
          </div>
            <div class="col-md-2">
                 <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_consultar_balance"><i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
                            </button>
            </div>
          </div>
      </div>

      <div class="row" id="panel_1" style="display:none">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-1" for="select_nodos">DC</label>
            <div class="col-md-3">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="text_dc_balance" name="text_dc_balance" placeholder="Número DC">
                </div>
          </div>


          <label class="control-label col-sm-1" for="select_nodos">Conciliado</label>
            <div class="col-md-3">
                <div class="input-group">
                  <input id="conci1" type="checkbox" class="form-control">
                </div>
           </div>

          </div>
      </div>

      <div class="row" id="panel_2" style="display:none">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-1" for="select_nodos">ManiObra</label>
            <div class="col-md-3">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="text_maniO_balance" name="text_maniO_balance" placeholder="Número ManiObra">
                </div>
           </div>

           <label class="control-label col-sm-1" for="select_nodos">Consolidado</label>
            <div class="col-md-1">
                <div class="input-group">
                  <input id="conso1" type="checkbox" class="form-control">
                </div>
           </div>

           <label class="control-label col-sm-1" for="select_nodos">Conciliado</label>
            <div class="col-md-2">
                <div class="input-group">
                  <input id="conci2" type="checkbox" class="form-control">
                </div>
           </div>

        </div>
       
          
       

        
      </div>


      <div class="row" id="panel_3" style="display:none">
        <div class="form-group has-feedback">
          <label class="control-label col-sm-1" for="select_nodos">Proyecto</label>
            <div class="col-md-3">
                  @if($proyec == "")
                    <input type="text" style="    width: 70%;   display: inline-block;    margin-left: 12px;" class="form-control" id="text_proyecto_Nodo" readonly name="text_proyecto_Nodo" placeholder="Nombre Proyecto" value="{{$proyec}}">
                  @else
                    <input type="text"  style="    width: 70%;   display: inline-block;    margin-left: 12px;" class="form-control" id="text_proyecto_Nodo" readonly name="text_proyecto_Nodo" placeholder="Nombre Proyecto" value="{{$proyec}}" readonly>
                  @endif
                  <a onclick="abrirModal(1)" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-search" style="    position: relative;    top: -2px;"></i></a>
                  <a onclick="limpiar1(1)" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-trash" style="    position: relative;    top: -2px;"></i></a>
          </div>

           <label class="control-label col-sm-1" for="select_nodos">Nodo</label>
            <div class="col-md-2">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="text_Nodo_Proyecto" name="text_Nodo_Proyecto" placeholder="Número Nodo">
                </div>
          </div>

          <label class="control-label col-sm-1" for="select_nodos">Consolidado</label>
            <div class="col-md-1">
                <div class="input-group">
                  <input id="conso2" type="checkbox" class="form-control">
                </div>
           </div>


           <label class="control-label col-sm-1" for="select_nodos">Conciliado</label>
            <div class="col-md-1">
                <div class="input-group">
                  <input id="conci3" type="checkbox" class="form-control">
                </div>
           </div>

          </div>

        
      </div>

      <div class="row" id="panel_4" style="display:none">
      <div class="row">
        <div class="form-group has-feedback">

           <label class="control-label col-sm-1" for="select_nodos">GOM</label>
            <div class="col-md-3">
                <div class="input-group">
                  <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                  <input type="text" class="form-control" id="text_Proyecto_GOM" name="text_Proyecto_GOM" placeholder="Número GOM">
                </div>
          </div>
          <label class="control-label col-sm-1" for="select_nodos">Consolidado</label>
            <div class="col-md-1">
                <div class="input-group">
                  <input id="conso3" type="checkbox" class="form-control">
                </div>
           </div>

          <label class="control-label col-sm-1" for="select_nodos">Conciliado</label>
            <div class="col-md-1">
                <div class="input-group">
                  <input id="conci4" type="checkbox" class="form-control">
                </div>
           </div>

          </div>

      </div>
        
        <div class="row">
              <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        <input class="form-control" size="16" style="height:30px;" type="text"
                               value="" name="fecha_ini_gom" id="fecha_ini_gom"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                    </div>
                </div>
           </div>

           <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Hasta:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        <input class="form-control" size="16" style="height:30px;" type="text"
                               value="" name="fecha_fin_gom" id="fecha_fin_gom"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                    </div>
                </div>
           </div>

        </div>
        


      </div>

      <div class="row" id="panel_5" style="display:none">
        <div class="form-group has-feedback">

           <label class="control-label col-sm-1" for="select_nodos">PROYECTO</label>
            <div class="col-md-3">
                  <input  readonly style="    width: 70%;   display: inline-block;    margin-left: 12px;" type="text" class="form-control" id="text_Proyecto_proy" name="text_Proyecto_proy" placeholder="Nombre PROYECTO">
                  <a onclick="abrirModal(2)" readOnly class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-search" style="    position: relative;    top: -2px;"></i></a>
                  <a onclick="limpiar1(2)" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-trash" style="    position: relative;    top: -2px;"></i></a>
          </div>
          <label class="control-label col-sm-1" for="select_nodos">Consolidado</label>
            <div class="col-md-1">
                <div class="input-group">
                  <input id="conso4" type="checkbox" class="form-control">
                </div>
           </div>

          <label class="control-label col-sm-1" for="select_nodos">Conciliado</label>
            <div class="col-md-1">
                <div class="input-group">
                  <input id="conci5" type="checkbox" class="form-control">
                </div>
           </div>

          </div>

      </div>

      
      <div class="row" style="margin-top:10px;">
          <div class="col-md-12" id="datos_balance" style="display:none">
              @include('proyectos.redes.trabajoprogramado.secciones.tableBalances')
          </div>
      </div>

      

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>

