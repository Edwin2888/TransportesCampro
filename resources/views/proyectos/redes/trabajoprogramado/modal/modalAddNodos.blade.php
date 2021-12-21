<div class="modal fade" id="modal_add_nodos_orden">
  <div class="modal-dialog" role="document" style="    width: 70%;">
    <div class="modal-content" >
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Nodos del proyecto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
        	@include('proyectos.redes.trabajoprogramado.secciones.nodos')
        </div>
      </div>
      

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden">Agregar nodos</button>
      </div>
    </div>
  </div>
</div>
</div>


<div class="modal fade" id="modal_add_personas_orden">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Recurso del proyecto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <div class="row" style="margin-top:20px;display:none" id="consulta_cuadrillero_0">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_nombre_rec">Nombre</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_nombre_rec" name="text_nombre_rec" />
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;display:none" id="consulta_cuadrillero_1">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="select_tipo_recurso">Tipo de cuadrilla</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <select type="text" class="form-control" id="select_tipo_recurso" name="select_tipo_recurso">
                  <option value="0">Seleccione</option>
                  @foreach($tipCuadrilla as $tip => $val)
                    <option value='{{$val->id_tipo_cuadrilla}}'>{{strtoupper($val->nombre)}}</option>
                  @endforeach

                  
                </select>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;display:none" id="consulta_cuadrillero_2" >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_fecha_ini">Hora inicio</label>
            <div class="col-sm-7">
                 <div class="input-group date form_time" data-date="" data-date-format="hh:ii" style="width:200px;">
                    <input type='text' class="form-control"   id="text_fecha_ini"/>
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                </div>

              
            </div>
          </div>
      </div>


      <div class="row" style="margin-top:20px;display:none" id="consulta_cuadrillero_3">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_fecha_fin">Hora fin</label>
            <div class="col-sm-7">
                <div class="input-group date form_time" data-date="" data-date-format="hh:ii" style="width:200px;">
                    <input type='text' class="form-control" id="text_fecha_fin"/>
                    <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                </div>
              
            </div>
          </div>
      </div>
      
      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="consultaRecursoAdd()">Consultar</button>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_iden_recurs" id="text_iden_recurs">Líder</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <!--<input type="text" data-num="1" type="text" class="form-control" id="text_iden_recurso" name="text_iden_recurso"> -->
                <select type="text" data-num="1" type="text" class="form-control" id="text_iden_recurso" name="text_iden_recurso"> 
                </select>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="agregarPersona()">Agregar</button>
          </div>
      </div>


      <div class="row" style="margin-top:20px;margin-bottom:20px;">
        <p>Recurso seleccionado</p>
        <table class="table table-striped table-bordered" id="consulta_cuadrillero_4">
            <thead>
              <tr>
                <th>Móvil</th>
                <th>Tipo cuadrilla</th>
                <th>Líder</th>
                <th>H. Ini</th>
                <th>H. Fin</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="tbl_recurso_add_nuevo">
            </tbody>
            
          </table>
      </div>
      
      <div class="row" style="margin-top:20px;margin-bottom:20px;">
          <div class="col-md-4 col-md-offset-4">
              <button type="button" class="btn btn-primary" style="width:100%;" onclick="agregarPersonaSave()">Guardar recurso</button>
          </div>
      </div>
      

      <div class="row" style="display:none">
          <table class="table table-striped table-bordered" id="consulta_cuadrillero_4">
            <thead>
              <tr>
                <th></th>
                <th>Móvil</th>
                <th>Tipo cuadrilla</th>
                <th>Líder</th>
                <th>Nombres</th>
              </tr>
            </thead>
            <tbody id="tbl_recu_add">
              <tr>
                
              </tr>
            </tbody>
            
          </table>

          <table class="table table-striped table-bordered" id="consulta_cuadrillero_5">
            <thead>
              <tr>
                <th></th>
                <th style="width: 40%;">Auxiliar</th>
                <th style="width: 55%;">Nombres</th>
              </tr>
            </thead>
            <tbody id="tbl_recu_add1">
              <tr>
                
              </tr>
            </tbody>
            
          </table>

          <table class="table table-striped table-bordered" id="consulta_cuadrillero_6">
            <thead>
              <tr>
                <th></th>
                <th style="width: 95%;">Matrícula</th>
              </tr>
            </thead>
            <tbody id="tbl_recu_add2">
              <tr>
                
              </tr>
            </tbody>
            
          </table>

      </div>

      <div class="modal-footer">
        <button type="button" onclick="volverModalRecurso()">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>



<div class="modal fade" id="modal_add_personas_orden_recurso">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Recurso del proyecto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_movil_recurso">Móvil</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_movil_recurso" name="text_movil_recurso" readonly>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_tipo_c_recurso">Tipo cuadrilla</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_tipo_c_recurso" name="text_tipo_c_recurso" readonly>
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_lider_recurso">Líder</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_lider_recurso" name="text_lider_recurso" readonly >
              </div>
            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="text_nombre_recurso">Nombre</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="text" class="form-control" id="text_nombre_recurso" name="text_nombre_recurso" readonly>
              </div>
            </div>
          </div>
      </div>

      

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="addRegistroTbl()">Agregar recurso</button>
        <button type="button" onclick="volverModalRecursoAdd()">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>
