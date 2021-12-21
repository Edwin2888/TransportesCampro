<div class="modal fade" id="modal_ipal">
  <div class="modal-dialog modal-lg" role="document" style="width:75%;">
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
        <h5 class="modal-title">Ver Inspección</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php if(
          ( Session::get('user_login') === 'U00664' || Session::get('user_login') === 'U00758' ) 
          && is_array($formCreacion) && count($formCreacion) < 1
        ): ?>
          <div class="alert alert-danger">
            <p>
              EL DETALLE DEL IPAL NO. <?php print $insp; ?> NO SE ENCUENTRA. SI DESEA REGENERAR EL DETALLE DEL IPAL CON TODAS LAS OPCIONES EN SI DE CLICK EN EL BOTÓN 'REGENERAR IPAL <?php print $insp; ?>'.
            </p>
          </div>

          <a id="btn_regenerar_ipal"
            style="display: inline-block; padding: 10px 59px !important; margin-bottom: 15px; background-color: red; color: white;" 
            onclick="regenerar_detalle_ipal('<?php print $insp; ?>', '<?php print $inspeccion->id_orden; ?>')" 
            href="#" 
            class="btn btn-primary btn-cam-trans btn-sm">
              <i class="fa fa-file-pdf-o" aria-hidden="true"></i> 
              REGENERAR DETALLE IPAL <?php print $insp; ?>
          </a>

          <br/>
          <br/>

          <script type="text/javascript">
            function regenerar_detalle_ipal(id_inspeccion, id_orden) {
              if(confirm('En realidad desea regenerar el detalle del IPAL ' + id_inspeccion + '?')) {
                $('#btn_regenerar_ipal').attr('disabled', true).text('Regerando IPAL ' + id_inspeccion +  ' ...');
                                
                var error = false;
                var mensaje_error = '';
                var formData = null;

                var id_inspeccion   = id_inspeccion.trim();

                if(!id_inspeccion) {
                  error = true;
                  mensaje_error = 'Número de IPAL no valido. Numero IPAL vacio';
                }

                // ================================================================================================
                // Se obtienen todos los valores del formulario y los archivos
                // ================================================================================================
                if(!error) {
                    formData = new FormData();

                    formData.append('id_inspeccion',  id_inspeccion);
                    formData.append('id_orden',  id_orden);

                    $.ajax({
                        type:         'POST',
                        url:          "<?php print url('/'); ?>/regenerarDetalleIPAL",
                        mimeType:     "multipart/form-data",
                        data:         formData,
                        dataType:     "json",

                        contentType:  false,
                        cache:        false,
                        processData:  false,

                        headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},

                        success: function(data, textStatus) {
                          $('#btn_regenerar_ipal').attr('disabled', false).text('REGENERAR DETALLE IPAL ' + id_inspeccion);
                          console.log('server response');
                          console.log(data);

                          if(!data.error) {
                            alert('El IPAL ' + id_inspeccion + ' ha sido regenerado.');
                            location.reload();
                          }
                          else {
                            alert(data.mensaje);
                          }
                        },
                        error: function(data) {
                          $('#btn_regenerar_ipal').attr('disabled', false).text('REGENERAR DETALLE IPAL ' + id_inspeccion);
                          alert('Ha ocurrido un error al regenerar el IPAL ' + id_inspeccion + '. Error: ' + data.statusText);
                        }
                    });
                }
                else {
                  $('#btn_regenerar_ipal').attr('disabled', false).text('REGENERAR DETALLE IPAL ' + id_inspeccion);
                  alert(mensaje_error);
                }
              }
            }
          </script>
        <?php endif; ?>

        @if($inspeccion->tipo_inspeccion  == 1)
          <a style="display: inline-block;    padding: 10px 59px !important;    margin-bottom: 15px;" href="{{config('app.Campro')[2]}}/campro/gop/apu/excel_ipal/excel.php?id_inspeccion={{$insp}}&id_orden={{$inspeccion->id_orden}}" target="_blank" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Ver PDF IPAL</a>

        @endif
      
<!--       
        @if($inspeccion->tipo_inspeccion  == 4)
          <a style="display: inline-block;    padding: 10px 59px !important;    margin-bottom: 15px;" href="{{config('app.Campro')[2]}}/campro/gop/apu/excel_ipal/ambiental.php?inspeccion={{$insp}}&id_orden={{$inspeccion->id_orden}}" target="_blank" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Ver PDF IPAL</a>

        @endif -->

        @if($inspeccion->tipo_inspeccion  == 3)

        <b style="width:20px;font-size: 20px;"><p>Calificación de la inspección:<b style="color:green;">{{$inspeccion->calificacion}}</b></p></b>
        <a href="../../../generarPdfValores/{{$insp}}" class="btn btn-danger" id="Pdf1" style="float:right; background: #4988E4; border:1px #4988E4 solid" target="_blank">
          <i class="fa fa-file-pdf-o" title="Nuestros Valores" data-toggle="tooltip">
              PDF
          </i>
        </a>
        <br>
        @endif
        @if($inspeccion->tipo_inspeccion  == 4)
        <a href="../../../generarPdfinspeccion/{{$insp}}" class="btn btn-danger" id="Pdf1" style="float:right; background: #4988E4; border:1px #4988E4 solid" target="_blank">
          <i class="fa fa-file-pdf-o" title="Nuestros Valores" data-toggle="tooltip">
              PDF
          </i>
        </a>
        <br>
        @endif

        <p style="text-align:center;font-weight: bold;">Información diligenciada da la inspección</p>
       <table id="tbl_causa" class="table table-striped table-bordered" cellspacing="0" width="99%">
        <thead>
            <tr>
                <th style="width:10px;">Item</th>
                <th style="width:100px;">Descripción</th>
                <th style="width:10px;">Respuesta</th>
                <th style="width:10px;">Acto o condición</th>
                <th style="width:10px;">Extra</th>
            </tr>
        </thead>
        <tbody id="tbl_ipal">

        <?php
          $contNO = 0;

        ?>
          @foreach($formCreacion as $key => $valor)

            @if($valor->tipo_control == "0")
              <tr style="    background: #cac6c6;">
                <td >{{$valor->item_num}}</td>
                <td  colspan="2">{{$valor->descrip_pregunta}}</td>
                <td></td>
                <td></td>
              </tr>
            @endif


            @if($valor->tipo_control == "1" || $valor->tipo_control == "11")
              <tr style="background: #e0dfdf;">
                <td >{{$valor->item_num}}</td>
                <td  colspan="2">{{$valor->descrip_pregunta}}</td>
                <td></td>
                <td></td>
              </tr>
            @endif


            @if($valor->tipo_control != "1" && $valor->tipo_control != "0")
                <?php
                  
                  if($valor->tipo_control == "11")
                    // continue;

                  ?>



                <tr>


                    <td >{{$valor->item_num}}</td>
                    <td >{{$valor->descrip_pregunta}}</td>
                      
                        @foreach($form as $key1 => $valor1)
                          @if($valor1->id_pregunta == $valor->id_pregunta)
                            @if($valor1->respuesta == 2)
                              <td >SI </td>
                              <td></td>
                              <td>{{$valor1->texto_extra}}</td>
                            @else
                                @if($valor1->respuesta == 1)

                                  <?php
                                    $contNO++;
                                  ?>

                                  <td  style="background-color:red;color:white;">NO </td>
                                  <td><input type="text" class="form-control" placeholder="Acto o condición" data-idpregunta="{{$valor1->id_pregunta}}" value="{{$valor1->acto_condicion}}"></td>
                                  <td>{{$valor1->texto_extra}}</td>
                                @else
                                  @if(is_numeric($valor1->respuesta))
                                    @if($valor1->respuesta == 0)
                                      <td >NA </td>
                                      <td></td>
                                      <td>{{$valor1->texto_extra}}</td>
                                    @else
                                      <td >{{$valor1->respuesta}} </td>
                                      <td></td>
                                      <td>{{$valor1->texto_extra}}</td>
                                    @endif
                                  @else
                                    <td >{{$valor1->respuesta}} </td>
                                    <td></td>
                                    <td>{{$valor1->texto_extra}}</td>
                                  @endif
                                @endif
                            @endif
                          @endif
                        @endforeach
                </tr>
            @endif

            
          @endforeach
        </tbody>
    </table>  

      <b style="width:20px;"><p>Observación:  </b>{{$inspeccion->observacion}}</p>

      @if($inspeccion->tipo_inspeccion  == 1)
        <b style="width:20px;"><p>Charla calificación:  </b>{{$inspeccion->charla_calificacion}}</p>
      @endif

      <b><p>Firmas</p></b>
      <img src="visor/<?php echo base64_encode($firma[0]->direccion); ?>​​​​">

      <b><p>Registro fotográfico</p></b>
      @foreach($fotos as $key => $val)
        <div class="col-md-3">
              <img src="visor/<?php echo base64_encode($val->direccion); ?>​​​​" style="width:100%;">
        </div>
      @endforeach
      <br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        @if($contNO != 0)
          @if($inspeccion->estado <> 'E2')
          <button type="submit" class="btn btn-primary" style="background-color:#0060ac;color:white;" id="btn_save_plan" onclick="saveIPALFIN()">Guardar información de la inspección</button>
          @endif
        @endif
      </div>
    </div>
  </div>
</div>
</div>