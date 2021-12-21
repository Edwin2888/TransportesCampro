
<!-- RESTRICCION -->
<div class="modal fade" id="modal_restricciones">
  <div class="modal-dialog modal-lg" role="document" style="width:95%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title" style="width: 76%;display: inline-block;">Restricciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <button style="display:none;" data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" id="filter_restric">
          <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
        </button>
        @if($encabezado != null)
          @if($encabezado[0]->id_estado == "E1")
          <button class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" id="btn_create_restric">
              <i class="fa fa-plus"></i> &nbsp;&nbsp;Crear Restricción
          </button>
          @endif
        @endif

        <div id="filter" class="collapse" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;">
        <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
        <div class="row">
            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="id_proyect">Desde:</label>
                        <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy"
                             style="    width: 100%;">
                            <input class="form-control" size="16" style="height:30px;" type="text"
                                   value="" name="fecha_inicio" id="fecha_inicio"
                                   placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                            <span class="input-group-addon"><i class="fa fa-times"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
            </div>

            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="text_nombre_proyect">Hasta:</label>
                        <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" style="    width: 100%;">
                            <input class="form-control" size="16" style="height:30px;" type="text"
                                   value="" name="fecha_corte" id="fecha_corte"
                                   placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                            <span class="input-group-addon"><i class="fa fa-times"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
            </div>

            <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="id_orden">Estado:</label>
                            <select type="text" class="form-control" id="select_estado_filter" name="select_estado_filter">
                              <option value="A">Abierta</option>
                              <option value="X">Ignorada</option>
                              <option value="C">Cerrada</option>
                            </select>
                    </div>
            </div>
            
            <div class="col-md-3">
                <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" id="btn_consulta_restric">
                    <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
                </button>
            </div>
        </div>

    </div>

    </div>

      <div class="row">
        <div class="col-md-12" style="margin-top:15px;" >
            <div style="margin-bottom:5px;font-weight:bold">
              <h4 style="text-align:center;">Convenciones restricciones:</h4>
              <span style="    display: inline-block;    width: 13px;    height: 13px;    margin-right: 7px;    background-color: rgb(0,143,65);margin-left: 31% "></span>Levantada
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 7px;    background-color:blue;"></span>En proceso
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: yellow;"></span>En proceso < 7 días
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: red;"></span>Por iniciar
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: black;"></span>Anulada              
            </div>
            <div id="content_Table_restricciones">
                @include('proyectos.redes.trabajoprogramado.secciones.tableRestricciones')
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
<!-- RESTRICCION -->
<div class="modal fade" id="modal_restricciones_add">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title" style="width: 76%;display: inline-block;">Restricciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     
        <div class="row">
          <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Tipo de restricción:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <select type="text" class="form-control" id="text_restric" name="text_restric">
                          <option value="0">Seleccione</option>
                            @foreach($restricC as $key => $value)
                              <option value="{{$value->id_tipo_restriccion}}">{{$value->nombre}}</option>
                            @endforeach
                        </select>
                      </div>
                  </div>
                </div>
            </div>
            </div>
           
           <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Descripción de restricción:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <textarea type="text" style="height: 66px;" class="form-control" id="text_descp_restriccion" name="text_impacto"></textarea>
                      </div>
                </div>
                </div>
            </div>
          </div>

        </div>

        <div class="row">

           <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Impacto:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <textarea type="text" style="height: 66px;" class="form-control" id="text_impacto" name="text_impacto"></textarea>
                      </div>
                </div>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
            <div class="form-group has-feedback" style="    margin-left: 14px;">
                  <label for="fec_limite">Fecha limite:</label>
                  <div class="input-group date form_date no_select" data-date=""
                       data-date-format="dd/mm/yyyy" style="width:170px;">
                      <input class="form-control" size="16" style="height:30px;" type="text"
                             value="" name="fec_limite" id="fec_limite" placeholder="dd/mm/aaaa" required>
                      <span id="btn_1_fecha" class="input-group-addon"><i class="fa fa-times"></i></span>
                      <span id="btn_2_fecha" class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
              </div>
            </div>
            </div>
            
        </div>

        <div class="row">
          
          <div class="col-md-12">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Responsable:</label>
                  <div class="col-md-12">
                      <div class="input-group" style="width:73%;float:left;">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <select type="text" class="form-control" id="text_responsable_restric" name="text_responsable_restric">
                            <option>Seleccione</option>
                            @foreach($res as $re => $val)
                              <option value="{{$val->id}}" data-correo="{{$val->correo}}">{{$val->nombre}}</option>
                            @endforeach                    
                        </select>
                      </div>
                      <button class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" id="btn_create_restric" 
                      style="padding:5px !important;     margin-left: 3px;" onclick="agregarReponsableNuevo()">
                            <i class="fa fa-plus"> Agregar</i>
                      </button>

                      <button class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" id="btn_create_restric" 
                      style="padding:5px !important;     margin-left: 3px;" onclick="abrirModalResponsable()">
                            <i class="fa fa-plus"> Crear</i>
                        </button>
                </div>
                </div>
            </div>
          </div>

          <div class="col-md-6" style="display:none">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Correo responsable:</label>
                  <div class="col-md-12">
                      <div class="input-group" style="    width: 87%;    float: left;">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <input type="text" class="form-control" id="text_correo_responsable" name="text_correo_responsable" style="padding:0px" />
                      </div>
                            <button style="    float: left;" onclick="addCorreo()" id="btn_add"><i class="fa fa-plus"></i> </button>
                          
                  </div>
                </div>
            </div>
            </div>
        </div>

        <div class="row" style="display:none" id="row_estado">
          <div class="col-md-6" id="evidencia_cierre_input">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Evidencia cierre:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <input type="file" class="form-control" id="evidencia_cierre" name="evidencia_cierre"/>
                      </div>
                </div>
                </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Estado:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <select type="text" class="form-control" id="select_estado" name="select_estado">
                          <option value="A">Abierta</option>
                          <option value="X">Ignorada</option>
                          <option value="C">Cerrada</option>
                        </select>
                      </div>
                  </div>
                </div>
            </div>
            </div>
        </div>

          <p style="    margin-left: 28px;    margin-top: 11px;">Lista de responsables</p>
          <div class="row">
            <div class="col-md-6">
              <table style="    margin-left: 15px;    margin-top: 20px;" class="table table-striped table-bordered" cellspacing="0" width="99%">
                <thead>
                  <tr>
                    <th><b>Responsable</b></th>
                    <th><b>E-mail</b></th>
                    <th><b>Eliminar</b></th>
                  </tr>
                </thead>
                <tbody  id="correo_enviar">
                </tbody>
              </table>
              </div>
          </div>

        

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_close_restriccion">Cerrar</button>
        <button type="button" class="btn btn-primary btnguarda"  id="btn_save_restriccion">Guardar</button>
        <img src="{{url('/')}}/img/loader6.gif" class="loading " alt="Loading..." style="display: none;" >
      </div>

  </div>
</div>
</div>



<!-- RESTRICCION -->
<div class="modal fade" id="modal_responsable_add">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title" style="width: 76%;display: inline-block;">Restricciones responsables</h5>
        <button type="button" onclick="regresarResponsables();" class="close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     
        <div class="row">
          
           
          <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Nombre:</label>
                  <div class="col-md-12">
                      <div class="input-group" style="width:85%;float:left">
                        <input type="text"  class="form-control" id="txt_nombre_resp_create" name="text_impacto" data-dato=""/>
                      </div>
                      <button class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar"  
                      style="padding:5px !important;     margin-left: 3px;" onclick="deleteDatosResponsable()">
                            <i class="fa fa-trash"></i>
                        </button>
                </div>
                </div>
            </div>
          </div>

           <div class="col-md-6">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">E-mail:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <input type="text"  class="form-control" id="txt_nombre_resp_email" name="text_impacto"/>
                      </div>
                </div>
                </div>
            </div>
          </div>

        </div>

        <button style="    margin-left: 31px;margin-top: 10px;" onclick="save_responsable_restriccion()" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" >
            <i class="fa fa-save"></i> &nbsp;&nbsp;Guardar responsable
        </button>

        <div class="row" id="table_responsables_restricciones" style="margin-top:10px;">
          @include('proyectos.redes.trabajoprogramado.secciones.tableResposablesRestricciones')
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="regresarResponsables();">Cerrar</button>
      </div>

  </div>
</div>
</div>