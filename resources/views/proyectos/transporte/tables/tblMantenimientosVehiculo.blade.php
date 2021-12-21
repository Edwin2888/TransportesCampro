
<table id="mantenimiento_vehiculos" class="table table-striped table-bordered table_center" cellspacing="0" width="100%;">
  <thead>
      <tr>
          <th style="width:10px;">No.</th>
          <th style="width:100px;">Incidencia</th>
          <th style="width:100px;">Estado Incidencia</th>
          <th style="width:100px;">Novedad</th>
          <th style="width:100px;">Tipo de mantimiento</th>
          <th style="width:100px;">Inhabilitado</th>
          <th style="width:100px;">Tiempo estimado</th>
          <th style="width:200px;">Fecha creación</th>
          <th style="width:300px;">Fecha cierre</th>
          <th style="width:300px;">Realizado por</th>
          <th style="width:200px;">Observación</th>
          <th style="width:200px;">Fecha ingreso a taller</th>
          <th style="width:200px;">Observación ingreso</th>
          <th style="width:200px;">Costo ingreso</th>
          <th style="width:200px;">Fecha salida de taller</th>
          <th style="width:200px;">Observación salida</th>
          <th style="width:200px;">Cerrada por</th>
          <th style="width:200px;">Solicitada por</th>
        
      </tr>
  </thead>
  <tbody>
    <?php $aux = 1?>
    @foreach($incidencias as $key => $val)
          <tr>             
            <td style="text-align:center;">{{$aux}}</td>
            <td><a href="../../transversal/incidencia/{{$val->incidencia}}" target="_blank">{{strtoupper($val->incidencia)}}</a></td>
            <td style="text-align:center;">{{$val->nombreE}}</td>
            <td style="text-align:center;">{{$val->novedad}}</td>
            <td style="text-align:center;">{{$val->resp}}</td>
            <td style="text-align:center;">
                @if($val->inhabilitada==1)
                    <i class="fa fa-close" style="color: red; font-size: 20px"></i>
                @endif
            </td>
            <td style="text-align:center;">{{$val->tiempo_estimado}} Hrs</td>
            <td style="text-align:center;">{{$val->fecha_servidor}}</td>
            <td style="text-align:center;">{{$val->fecha_cierre}}</td>
            <td style="text-align:center;">{{$val->realizadoPor}}</td>
            <td style="text-align:center;">{{$val->observaciones}}</td>
            <td style="text-align:center;">{{$val->fecha_ingreso}}</td>
            <td style="text-align:center;">{{$val->observacion}}</td>
            <td style="text-align:center;">{{$val->costo_ingreso}}</td>
            <td style="text-align:center;">{{$val->fecha_salida}}</td>
            <td style="text-align:center;">{{$val->observacion_salida}}</td>
            <td style="text-align:center;">{{$val->cerradoPor}}</td>
            <td style="text-align:center;">{{$val->solicitante}}</td>

          </tr>
        <?php $aux++;?>
      @endforeach
  </tbody>
</table> 
