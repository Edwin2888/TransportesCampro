<h3>DATOS DEL VEHICULO</h3>
                            <br>

                            <button style="margin-left:9%;margin-bottom: 10px;display:none" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaLogVehiculo();" id="btn_log_vehi">
                                <i class="fa fa-refresh"></i> &nbsp;&nbsp;Ver historial de cambios del vehículo
                            </button>

                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Matricula</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        @if(Session::has('imagen_guardada'))
                                            <input value="{{Session::get('imagen_guardada')}}" style="width:86%;margin-right:8px;" id="txtMatricula" type="text" class="form-control form-control-input" name="matricula" placeholder="Matricula">
                                        @else
                                            @if(Session::has('placa_select'))
                                                <input id="txtMatricula"  style="width:86%;margin-right:8px;" value="{{Session::get('placa_select')}}" type="text" class="form-control form-control-input" name="matricula" placeholder="Matricula">
                                            @else
                                                <input id="txtMatricula"  style="width:86%;margin-right:8px;" type="text" class="form-control form-control-input" name="matricula" placeholder="Matricula">
                                            @endif                                           
                                        @endif
                                        <button id="btn_consulta" class="btn btnWzrd fa  fa-search btn-cam-trans btn-sm"></button>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span>Linea</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="txtLinea" type="text" class="form-control form-control-input" name="linea" placeholder="Linea">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Ciudad</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {{--{!!Form::select('idCiudad', $ciudades, (isset($peliculaCombo) ? $peliculaCombo[0]->idPelicula : null))!!}--}}
                                        {!!Form::select('idCiudad', $ciudades, null, ["class"=>"form-control form-control-input selectWzrd","placeholder"=>"Seleccione","id"=>"selCiudad"])!!}
                                        &nbsp;
                                        @if($acceso == "W")
                                        <button class="btn btnWzrd fa  fa-pencil-square-o btn-cam-trans btn-sm" onclick="abrirModal(1)"></button>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span>Cilindraje</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="txtCilindraje" type="text" class="form-control form-control-input" name="cilindraje" placeholder="Cilindraje">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Tipo (TP)</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select id="selTipoVehiculo" class="form-control form-control-input selectWzrd" placeholder="Seleccione">
                                            <option>Seleccione</option>
                                        @foreach($tipoVehiculos as $key => $val)
                                            <option value="{{$val->id_tipo_vehiculo}}" data-cam="{{$val->nombre_cam}}">{{$val->nombre}}</option>
                                        @endforeach
                                        </select>

                                        @if($acceso == "W")
                                        <button class="btn btnWzrd fa  fa-pencil-square-o btn-cam-trans btn-sm" style="    margin-left: 8px;" onclick="abrirModal(2)"></button>
                                        @endif

                                        <p id="tipo_Vehiculo_cam" style="text-align: center;color:green;font-weight: bold;margin-top"></p>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span>Tipo combustible</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!!Form::select('idTipoCombustible', $tipoCombustibles, null, ["class"=>"form-control form-control-input selectWzrdCompleto","placeholder"=>"Seleccione","id"=>"selTipoCombustible"])!!}
                                    </div>
                                </div>
                            </div>
                            
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Agrupación</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" value="" style="    width: 95%;" readonly id="txt_agrupacion" class="form-control form-control-input">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span><b>Hacer Mantenimiento cada</b></span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="number" value="" id="txt_rutina_km" class="form-control form-control-input">
                                    </div>
                                </div>
                            </div>


                            <br>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Marca</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!!Form::select('idMarca', $marcas, null, ["class"=>"form-control form-control-input selectWzrd","placeholder"=>"Seleccione","id"=>"selMarca"])!!}
                                        &nbsp;
                                        @if($acceso == "W")
                                        <button class="btn btnWzrd fa  fa-pencil-square-o btn-cam-trans btn-sm" onclick="abrirModal(3)"></button>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span>Transmisión</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!!Form::select('idTipoTransmision', $tipoTransmision, null, ["class"=>"form-control form-control-input selectWzrdCompleto","placeholder"=>"Seleccione","id"=>"selTipoTransmision"])!!}
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Modelo</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <select id="selModelo" type="text" class="form-control form-control-input" name="modelo">
                                            <option value="">Seleccione</option>
                                            <option value="1988">1988</option>
                                            <option value="1989">1989</option>
                                            <option value="1990">1990</option>
                                            <option value="1991">1991</option>
                                            <option value="1992">1992</option>

                                            <option value="1993">1993</option>
                                            <option value="1994">1994</option>
                                            <option value="1995">1995</option>
                                            <option value="1996">1996</option>
                                            <option value="1997">1997</option>
                                            <option value="1998">1998</option>
                                            <option value="1999">1999</option>
                                            <option value="2000">2000</option>
                                            <option value="2001">2001</option>
                                            <option value="2002">2002</option>
                                            <option value="2003">2003</option>
                                            <option value="2004">2004</option>
                                            <option value="2005">2005</option>
                                            <option value="2006">2006</option>
                                            <option value="2007">2007</option>
                                            <option value="2008">2008</option>
                                            <option value="2009">2009</option>
                                            <option value="2010">2010</option>
                                            <option value="2011">2011</option>
                                            <option value="2012">2012</option>
                                            <option value="2013">2013</option>
                                            <option value="2014">2014</option>
                                            <option value="2015">2015</option>
                                            <option value="2016">2016</option>
                                            <option value="2017">2017</option>
                                            <option value="2018">2018</option>
                                            <option value="2019">2019</option>
                                            <option value="2020">2020</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                            <option value="2023">2023</option>


                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span>Tipo vinculación</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!!Form::select('idTipoVinculacion', $tipoVinculacion, null, ["class"=>"form-control form-control-input selectWzrdCompleto","placeholder"=>"Seleccione","id"=>"selTipoVinculacion"])!!}
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Color</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="txtColor" type="text" class="form-control form-control-input" name="color" placeholder="Color">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <span>Fecha</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group date form_datee" data-date="" data-date-format="dd/mm/yyyy"
                                         style="    width: 100%;">
                                            <input class="form-control form-control-input" size="16" style="height:30px;" type="text"
                                                   name="fecha_inicio" id="fecha_inicio"
                                                   placeholder="dd/mm/aaaa" >
                                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Num pasajeros</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="txtNumPasajeros" type="text" class="form-control form-control-input" name="pasajeros" placeholder="Pasajeros">
                                    </div>
                                </div>
                                
                                <div class="col-md-1">
                                    <span>Ficha Tecnica</span>
                                </div>
                                <div class="col-md-4">
                                    <a href="" target="_blank" id="enlacefichatec" style="display: none;"><i class="fa fa-clone" aria-hidden="true"></i></a>  &nbsp; &nbsp;
                                    <a id="subefich" href="#" style="display: none;"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>                                    
                                </div>
                                
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-1">
                                    <span>Clase</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {!!Form::select('selClase', $clases, null, ["class"=>"form-control form-control-input selectWzrdCompleto","placeholder"=>"Seleccione","id"=>"selClase"])!!}
                                    </div>
                                </div>
                                
                                
                                
                                <span id="div_estado" style="display:none">
                                    <div class="col-md-1">
                                        <span>Estado</span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <select class="form-control form-control-input selectWzrdCompleto" id="selEstado" disabled style="    width: 90%; ">
                                                <option>Seleccione</option>
                                                @for($i = 0; $i < count($estados) ; $i++)
                                                    @if($estados[$i]->id_estado != "E02" && $estados[$i]->id_estado != "E01" && $estados[$i]->id_estado != "E05" )
                                                        <option disabled value="{{$estados[$i]->id_estado}}">{{$estados[$i]->nombre}}</option>
                                                    @else
                                                        <option value="{{$estados[$i]->id_estado}}">{{$estados[$i]->nombre}}</option>
                                                    @endif
                                                @endfor
                                            </select>

                                            @if($acceso == "W")
                                              <button class="btn btnWzrd fa  fa-refresh btn-cam-trans btn-sm" style="    margin-left: 10px;" onclick="abrirModal(9)" title="Novedad Cambio de estado"></button>
                                            @endif
                                        </div>
                                    </div>
                                </span>
                                
                            </div>
                            <br>
                            <div class="row">
                                <span id="numeroServicio" >
                                    <div class="col-md-1 col-md-offset-1">
                                        <span>Servicio</span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <select class="form-control" id="selServicio"  style="width: 100%; ">
                                                <option value >Seleccione Servicio</option>
                                                 @for($i = 0; $i < count($servicios) ; $i++)
                                                    <option value="{{$servicios[$i]->numero_servicio}}">{{$servicios[$i]->numero_servicio}}-{{$servicios[$i]->tipo_servicio}}-{{$servicios[$i]->descripcion}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </span>
                                <span id="numeroContrato" >
                                    <div class="col-md-1">
                                        <span>Contrato</span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <select class="form-control" id="selContrato"  style="width: 100%; ">
                                                <option value >Seleccione Contrato</option>
                                                 @for($i = 0; $i < count($contratos) ; $i++)
                                                    <option value="{{$contratos[$i]->numero_contrato}}">{{$contratos[$i]->numero_contrato}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </span>
                            </div>

                               <br>

                                <div class="row">
                           
                                <span id="numeroServicio" >
                                    <div class="col-md-1 col-md-offset-1">
                                        <span>Elemento PEP</span>
                                    </div>
                                   
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input id="txtPep" type="text" class="form-control form-control-input" name="pep" placeholder="Pep">
                                    </div>
                                </div>

                                   </span>
                            </div>
                            
                            @if($acceso == "W")
                            <ul class="list-inline pull-right">
                                <li><button type="button" class="btn btn-primary next-step" onclick="guardaDatosVehiculo()">Siguiente</button></li>
                            </ul>
                            @endif
                        
                            
  <?php /* model ficha tecnica */ ?> 
                            
 <div class="modal fade" id="modal_add_fichatecni">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Ficha Tecnica</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">

            <form id="form_ficha">
                
                <div class="row" style="margin-top:20px;">
                    <div class="form-group has-feedback">
                        <label class="control-label col-sm-3" for="txtTipoSoporte">Ficha Tecnica</label>
                        <div class="col-sm-9">
                            <input class="form-control form-control-input" type="file" id="fichatecnica"  >
                        </div>
                    </div>
                </div>
                <br>
                <center>
                    <button type="button" class="btn btn-primary btnfrmoc" style="background-color:#0060ac;color:white;" id="btn-add-ficha-tecni">Guardar</button>
                    <img src='<?= Request::root() ?>/img/loader6.gif' class='loading' alt='Loading...' style="display: none">
                </center>

            </form>
            
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
window.onload=function() {
	
    $("#selServicio").select2();    
    $("#enlacefichatec").on('click',function(event){
        
        var ruta=$("#enlacefichatec").attr("href").trim(); 
        
        if(ruta==null  || ruta=='' || ruta=='http://190.60.248.195null' ){            
            event.stopPropagation();
            event.preventDefault();
            mostrarNotificacion(1,"Ficha tecnica aun no guardada");     
        }
        
    });
    
    
    $("#subefich").on('click',function(event){
        
        event.stopPropagation();
        event.preventDefault();
        
        
        var placa = $("#txtMatricula").val().trim();        
        if(placa == null || placa==''){          
            
            mostrarNotificacion(1,"Error, ingrese la placa."); 
            return;
            
        }
        
        $("#modal_add_fichatecni").modal('show');
    });
    
    $(".btnfrmoc").on('click',function(event){
        
        event.stopPropagation();
        event.preventDefault();
        
        
        var placa = $("#txtMatricula").val().trim();
        
        if(placa == null || placa==''){
            
            mostrarNotificacion(1,"Error, ingrese la placa."); 
            return;
            
        }
        
        var imgsize = $("#fichatecnica")[0].files[0].size;
       // alert("tamaño "+imgsize);
        if(imgsize > 5000000){
                  mostrarNotificacion(1,'El tamaño del archivo supera los 5Mb.');
                  return;
        }
        
             $("#form_ficha").find('.btnfrmoc').hide("slow",function(){ 
              
                    $("#form_ficha").find('.loading').show("slow"); 
                     
        
            var formData = new FormData();
                formData.append("_token", "<?= csrf_token() ?>");
                formData.append("placa",placa);
                formData.append("file",$("#fichatecnica")[0].files[0]);

                $.ajax({
                    type: 'POST',
                    url: "<?= Request::root() ?>/fichaVehiculo",
                    dataType: "json",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data,textStatus) {  
                        if(data.status==1){
                           mostrarNotificacion(2,data.msj);  
                           $("#enlacefichatec").attr("href",data.adj);                           
                        }else{
                           mostrarNotificacion(1,data.msj);      
                        }
                        
                    },
                    error:function(request,status,error){
                        
                           mostrarNotificacion(1,"Problema con la conexión a internet","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n");  
                    }
                }).always(function() {              
                          $("#form_ficha").find('.loading').hide("slow",function(){$("#form_ficha").find('.btnfrmoc').show();}); 
                });  
             });
      
        
        
        
    });    
        
}

</script>
