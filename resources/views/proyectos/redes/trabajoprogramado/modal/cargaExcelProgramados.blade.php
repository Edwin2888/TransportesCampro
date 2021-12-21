<div class="modal fade" id="modal_import">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar Excel MO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      <p style="display:inline-block">Puede descargar el formato del excel, haga clic  
          <form method="POST" action="{{url('/')}}/downloadFormato" id="download_format1" accept-charset="UTF-8" style="display:inline-block;color:blue;cursor:pointer;">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
          <input type="hidden" value="2" name="opc" />
            &nbsp <b onclick="formato_download1()">aquí</b>
          </form></p>
          <p>Los niveles de tensión (Solo utilizar las convenciones EJ: NT3A ):</p>
          <li>NT1A=BAJA TENSION AEREA </li>
          <li>NT1S=BAJA TENSION SUBTERRANEA</li>
          <li>NT2A=MEDIA TENSION AEREA </li>
          <li>NT2S=MEDIA TENSION SUBTERRANEA</li>
          <li>NT3A=34.5 AEREA </li>
          <li>NT2S=34.5 SUBTERRANEA</li>
        <p style="color:red;font-weight:bold;margin-top:10px;">* Recuerde que para cargar la Mano de obra tiene que ser en el formato que se menciona con anterioridad.</p>
        <p style="color:red;font-weight:bold;">* Los niveles de tensión que puede seleccionar, son los que se encuentran con anterioridad.</p>
        <br>
     <form method="POST" action="{{url('/')}}/cargarExcel" accept-charset="UTF-8" enctype="multipart/form-data">
      <input type="hidden" value="{{csrf_token()}}" name="_token">
      <input type="hidden" value="{{$proyec}}" name="proyecto">
      <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Desea generar nuevamente el proyecto:</label>
      <input type="checkbox" class="form-control" name="crear_nuevo_1" id="crear_nuevo_1">
      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Archivo Excel:</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="file" class="form-control" id="file_upload" name="file_upload" >
              </div>
            </div>
          </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-wbs-upload" onclick="mostrarSincronizacion()">Importar</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>


<div class="modal fade" id="modal_import_1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Importar Excel Materiales</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <!--<div class="row">

      <p>ITEMS a tener en cuenta para importar el archivo de Excel</p>
        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>1- El archivo xxxxxx</p>
          <img src="{{url('/')}}/img/ImagenExcel.png">  
        </div>

        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>2- El archivo xxxxxx</p>
          <img src="{{url('/')}}/img/ImagenExcel1.png">  
        </div>

        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>3- El archivo xxxxxx</p>
          <img src="{{url('/')}}/img/ImagenExcel2.png">  
        </div>

        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>4- El archivo xxxxxx</p>
          <img src="{{url('/')}}/img/ImagenExcel3.png">  
        </div>

        <div class="col-md-10 col-md-offset-1 img-import" >
          <p>5- El archivo xxxxxx</p>
          <img src="{{url('/')}}/img/ImagenExcel4.png">  
        </div>
      </div>  -->
     
     <p style="display:inline-block">Puede descargar el formato del excel, haga clic  
          <form method="POST" action="{{url('/')}}/downloadFormato" id="download_format2" accept-charset="UTF-8" style="display:inline-block;color:blue;cursor:pointer;">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
          <input type="hidden" value="3" name="opc" />
            &nbsp <b onclick="formato_download2()">aquí</b>
          </form></p>
        <p style="color:red;font-weight:bold;margin-top:10px;">* Recuerde que para cargar los Materiales tiene que ser en el formato que se menciona con anterioridad.</p>
        <br>


     <form method="POST" action="{{url('/')}}/cargarExcel1" accept-charset="UTF-8" enctype="multipart/form-data">
      <input type="hidden" value="{{csrf_token()}}" name="_token">
      <input type="hidden" value="{{$proyec}}" name="proyecto">
      <span style="    color: red;    font-size: 13px;    display: inline-block;    text-align: center;    width: 100%;">*Si selecciona la opción SI se cargaran solo los materiales que no esten en el proyecto, de lo contrario si ya existe en el proyecto se acomularan las unidades</span>
      <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Desea solo cargar los materiales faltantes:</label> 
      <input type="checkbox" class="form-control" name="crear_nuevo_11" id="crear_nuevo_11">

      <div class="row" style="margin-top:20px;">
        <div class="form-group has-feedback">
            <label class="control-label col-sm-3 col-md-offset-1 " for="inputGroupSuccess2">Archivo Excel:</label>
            <div class="col-sm-7">
              <div class="input-group">
                <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                <input type="file" class="form-control" id="file_upload" name="file_upload" >
              </div>
            </div>
          </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn-wbs-upload" onclick="mostrarSincronizacion()">Importar</button>
      </div>
      </form>
    </div>
  </div>
</div>
</div>


<!-- GOM-->
<div class="modal fade" id="modal_gom_wbs">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title" style="width: 76%;display: inline-block;">Ordenes GOM</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     
        <div class="row">
          <div class="col-md-12">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Seleccione WBS:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <select type="text" class="form-control" id="select_wbs_gom" name="select_wbs_gom">
                        <option value="0">Seleccione</option>
                          @foreach($wbsCombox as $comb => $val)
                            <option value='{{$val->id_ws}}'>{{strtoupper($val->nombre_ws)}}</option>
                          @endforeach
                      </select>
                      </div>
                  </div>
                </div>
            </div>

            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">GOM:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <input type="text" class="form-control" id="text_gom_wbs" name="text_gom_wbs" placeholder="GOM"/>  
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-plus" aria-hidden="true" onclick="addGOMWBS()"></span>
                      </div>
                </div>
                </div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="col-md-12" id="tbl_gom_asociadas">
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

<!-- DESCARGOS-->
<div class="modal fade" id="modal_descargos">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title" style="width: 76%;display: inline-block;">Descargos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
     
        <div class="row">
          <div class="col-md-12">
            <div class="row" style="    margin-top: 16px;">
              <div class="form-group has-feedback">
                <label class="control-label col-sm-12" for="select_nodos">Descargo:</label>
                  <div class="col-md-12">
                      <div class="input-group">
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-edit" aria-hidden="true"></span>
                        <input type="text" class="form-control" id="text_descargo_add" name="text_descargo_add" placeholder="Descargo"/>  
                        <span class=" input-group-addon glyphicon glyphicon glyphicon-plus" aria-hidden="true" onclick="addDescargo()"></span>
                      </div>
                </div>
                </div>
            </div>
          </div>

          <div class="col-md-12" style="    margin-top: 20px;">
            <div class="col-md-12" id="tbl_descargos_asociadas">
                @include('proyectos.redes.trabajoprogramado.secciones.tbldescargosproyecto')
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

<script type="text/javascript">
  function formato_download1()
  {
      document.querySelector("#download_format1").submit();
  }  

  function formato_download2()
  {
      document.querySelector("#download_format2").submit();
  }  
</script>




