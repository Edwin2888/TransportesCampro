<table id="tbl_documento_vencidos" class="table table-striped table-bordered table_center" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">Estado vehículo</th>
            <th style="width:20px;">Ven</th>
            <th style="width:20px;">Placa</th>
            <th style="width:20px;">Clase</th>
            <th style="width:200px;">Tipo vehículo (TP)</th>
            <th style="width:200px;">Tipo CAM</th>
            <th style="width:20px;">Modelo</th>
            <th style="width:20px;">Cilindraje</th>
            <th style="width:200px;">Documento</th>
            <th style="width:200px;">Referencia</th>
            <th style="width:200px;">Entidad</th>
            <th style="width:20px;">Fecha vencimiento</th>
            <th style="width:20px;">Proyecto</th>
            <th style="width:20px;">Ver</th>
        </tr>
    </thead>
    <tbody>
        <!-- $val->id_estado-->
        <?php $aux = 0; ?>
        @foreach ($vehiculoData as $key => $val)
            <tr>
                <?php
                    $color = "green";
                    if($val->id_estado == "E0")
                        $color = "black";


                    $enc = 1;
                    $fecha = "";

                    if($val->vencimiento != null && $val->vencimiento != "")
                    {
                      $fecha = explode(" ",$val->vencimiento)[0];
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

                <td style="text-align:center;font-weight:bold;color:{{$color }}">{{strtoupper($val->nombreEstado)}}</td>
                <td style="text-align:center;">
                  @if($fecha != "")
                    @if($dias > 0)
                      <span style="color:green;font-size:16px"><i class="fa fa-flag" aria-hidden="true"></i></span>
                    @else
                      <span style="color:red;font-size:16px"><i class="fa fa-flag" aria-hidden="true"></i></span>
                    @endif
                  @endif
                  </td>
                <td style="text-align:center;color:blue;"><a href="../../selectVehiculo/{{strtoupper($val->placa)}}" >{{strtoupper($val->placa)}}</a></td></td>
                <td style="text-align:center;">{{$val->nombreClase}}</td>
                <td style="text-align:center;">{{$val->nombreT}}</td>                
                <td style="text-align:center;">{{$val->nombreTCAM}}</td>     
                <td style="text-align:center;">{{$val->modelo}}</td>
                <td style="text-align:center;">{{$val->cilindraje}}</td>              
                <td style="text-align:center;">{{$val->nombre_documento}}</td>
                <td style="text-align:center;">{{$val->referencia}}</td>
                <td style="text-align:center;">{{$val->entidad}}</td>
                <td style="text-align:center;">{{explode(" ",$val->vencimiento)[0]}}</td>
                <td style="text-align:center;">{{$val->pry}}</td>
                <td style="text-align:center;">
                @if($val->direccion_archivo != null)
                    <a href="visor/<?php echo base64_encode($val->direccion); ?>" target="_black"><i class="fa fa-download" aria-hidden="true"></i></a>
                @endif
                </td>


                
            </tr>

        @endforeach
    </tbody>
</table>