
<div class="modal fade" id="modal_exporte_programacion">
  <div class="modal-dialog" role="document">
    <div class="modal-content" >
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Exportar programación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">


     <form method="POST" action="../../transversal/ordenes/exporteProgramacion" accept-charset="UTF-8" method="POST">
      
      <div class="row" style="margin-top:20px;"  >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " >Proceso</label>
            <div class="col-sm-7">
            @if($permisoversion == "W")
            <select class="form-control" name="Cbo_proyecto_exporte" onchange="(this.value == '2' ? document.querySelector('#panel_versionamiento').style.display = 'block' : document.querySelector('#panel_versionamiento').style.display = 'none')">
            @else
              <select class="form-control" name="Cbo_proyecto_exporte" onchange="">
            @endif
                <option value="1">División eléctrica</option>
                <option value="2">Obras civiles</option>
            </select>
          </div>
          </div>
      </div>

         
      <div class="row" style="margin-top:20px;"  >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " >Tipo</label>
            <div class="col-sm-7">            
                <select name="tipo"  class="form-control" style="max-width:250px;padding:0px;" >
                       <option value="0" >Seleccione Tipo</option>
                    @foreach($proyecto as $key => $val)
                        <option value="{{$val->id_proyecto}}" >{{$val->des_proyecto}}</option>
                    @endforeach
                </select>    
            </div>
        </div>
      </div>
        
         

      <div class="row" style="margin-top:20px;"  >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="fecha1">Fecha inicio</label>
            <div class="col-sm-7">
                 <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" style="width:200px;">
                    <input type='text' class="form-control"   id="fecha1" name="fecha1" />
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>

            </div>
          </div>
      </div>

      <div class="row" style="margin-top:20px;"  >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="fecha2">Fecha fin</label>
            <div class="col-sm-7">
                 <div class="input-group date form_date no_select" data-date="" data-date-format="dd/mm/yyyy" style="width:200px;">
                    <input type='text' class="form-control"   id="fecha2" name="fecha2" />
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
          </div>
      </div>
      </div>
    
    @if($permisoversion == "W")
    <span id="panel_versionamiento" style="display:none">
      <div class="row" style="margin-top:20px;"  >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-7 col-md-offset-1 " for="fecha2">Generar versión de la programación</label>
              <input  type="checkbox" id="generar_version_pro" name="generar_version_pro" class="form-control" style="position:relative;top:-10px;" onchange ="generarVersion()"/>
          </div>
      </div>


      <div class="row" style="margin-top:20px;display:none" id="panel_exporte_version" >
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="fecha2">Año</label>
            <select class="form-control" id="anio_exporte" name="anio_exporte" style="width:40%" onchange="consultadatosversiones()">
                <option value="2018">2018</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
            </select>
            <br>
            <label class="control-label col-sm-3 col-md-offset-1 " >Mes</label>
            <select class="form-control" id="mes_exporte" name="mes_exporte"  style="width:40%" onchange="consultadatosversiones()">
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
            </select>
            <br>
            <label class="control-label col-sm-3 col-md-offset-1 " for="fecha2">Observación</label>
            <textarea id="txt_obser_exporte" name="txt_obser_exporte" style="width: 54%;    height: 100px;"></textarea>

          </div>


          <div class="row" style="margin-top:20px;"  >
            <div class="form-group has-feedback">
                <label class="control-label col-sm-12 col-md-offset-1 " for="fecha2">Versiones de la programación para la fecha seleccionada</label>

                <br>

                <table id="tbl_versiones_programacion" class="table table-striped table-bordered" cellspacing="0" width="99%" style="margin-top: 13px;">
                  <thead>
                      <tr>
                          <th style="width:10px;">Versión</th>
                          <th style="width:10px;">Fecha</th>
                          <th style="width:10px;">Mes</th>
                          <th style="width:10px;">Año</th>
                          <th style="width:10px;">Usuario</th>
                          <th style="width:10px;">Observación</th>
                          <th style="width:10px;">Exportar</th>
                      </tr>
                  </thead>
                  <tbody id="tbl_body_versiones">
                  </tbody>
                </table> 


              </div>
          </div>

      </div>
      
    </span>
    @endif



      <input type="hidden" name="_token" value="{{ csrf_token() }}" />

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-add-nodos-orden" onclick="validarExport();">Exportar programación</button>
      </div>

      </form>

    </div>
  </div>
</div>
</div>
