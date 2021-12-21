
<table id="documetos_vehiculo" class="table table-striped table-bordered" cellspacing="0" width="99%">
  <thead>
      <tr>
          <th style="width:10px;">No.</th>
          <th style="width:10px;">Ven</th>
          <th style="width:100px;">Tipo documento</th>
          <th style="width:100px;">Referencia</th>
          <th style="width:100px;">Entidad</th>
          <th style="width:100px;">Vencimiento</th>
          <th style="width:10px;">Cargar</th>
          <th style="width:10px;">Elim</th>
          <th style="width:10px;">Desc</th>
          <th style="width:10px;">Hist</th>
      </tr>
  </thead>
  <tbody>
    <?php $aux = 1;?>
    @foreach($documentosClase as $key => $val)
          <tr>  
           <?php $enc = 0;?>
            <td style="text-align:center;">{{$aux}}</td>
           @foreach($documentos as $key1 => $val2)
                @if($val2->id_documento == $val->id_documento)
                  <?php $enc = 1;
                    $fecha = "";

                    if($val2->vencimiento != null && $val2->vencimiento != "")
                    {
                      $fecha = explode(" ",$val2->vencimiento)[0];
                      $fecha2 = $fecha;
                      $dias = 0;

                      if($fecha == "1900-01-01")
                        $fecha = "";
                      else
                        $fecha = explode("-",$fecha)[2] . '/' . explode("-",$fecha)[1] . '/' . explode("-",$fecha)[0];

                      if($fecha2 != "1900-01-01" && $fecha2 != "")
                      {
                        $datetime1 = new DateTime(date("Y-M-d"));
                        $datetime2 = new DateTime($fecha2);
                        $interval = $datetime1->diff($datetime2);
                        $dias = intval($interval->format('%R%a')) + 1;
                      }
                    }
                  ?> 

                  <td style="text-align:center;">
                  @if($val2->vence=='S')
                    @if($fecha != "")
                      @if($dias > 0)
                        <span style="color:green;font-size:16px"><i class="fa fa-flag" aria-hidden="true"></i></span>
                      @else
                        <span style="color:red;font-size:16px"><i class="fa fa-flag" aria-hidden="true"></i></span>
                      @endif
                    @endif
                  @endif  
                  </td>
                  <td style="text-align:center;">{{$val->nombre_documento}}</td>
                  <td style="text-align:center;">{{$val2->referencia}}</td>
                  <td style="text-align:center;">{{$val2->entidad}}</td>
                  <td style="text-align:center;">
                      {{$fecha}}
                  </td>
                  <td style="text-align:center">
                      @if($permisoArchivo=='W')
                        <i onclick="abrirModalImportar(1,{{$val->id_documento}},'{{$val->nombre_documento}}','{{$val2->referencia}}','{{$val2->entidad}}','{{$fecha}}')" class="fa fa-upload" aria-hidden="true" style="cursor:pointer;font-size:1.5em;color:#084A9E"></i>
                      @endif
                  </td>
                  <td style="text-align:center;">
                    @if($permisoArchivo == "W")
                    @if($val2->direccion_archivo != null)
                      <i class="fa fa-window-close-o" onclick="deleteDocumento({{$val->id_documento}})" aria-hidden="true" style="cursor:pointer;font-size:1.5em;color:#084A9E"></i>
                    @endif
                    @endif
                  </td>
                  <td style="text-align:center;">
                     @if($val2->direccion_archivo != null)
                      <a href="visor/{{base64_encode($val2->direccion)}}" target="_black"><i class="fa fa-download" aria-hidden="true"></i></a>
                    @endif
                  </td>
                  <?php $continue;?>
              @endif
           @endforeach
            @if($enc == 0)
              <td></td>
              <td style="text-align:center;">{{$val->nombre_documento}}</td>
              <td></td>
              <td></td>
              <td></td>
              <td style="text-align:center">
                <i onclick="abrirModalImportar(1,{{$val->id_documento}},'{{$val->nombre_documento}}','','','')" class="fa fa-upload" aria-hidden="true" style="cursor:pointer;font-size:1.5em;color:#084A9E"></i>
              </td>
              <td></td>
              <td></td>
            @endif
            <td style="text-align:center;">
              <i class="fa fa-refresh" aria-hidden="true"  style="cursor:pointer;font-size:1.5em;color:#084A9E" onclick="verDatosLogDocumento({{$val->id_documento}})"></i>
            </td>
          </tr>
        <?php $aux++;?>
      @endforeach
  </tbody>
</table> 
