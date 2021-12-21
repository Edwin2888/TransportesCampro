
<table id="documetos_vehiculo" class="table table-striped table-bordered table_center odometer" cellspacing="0" width="50%;margin-left:50px;">
  <thead>
      <tr>
          <th style="width:10px;">No.</th>
          <th style="width:100px;">Fecha registro</th>
          <th style="width:100px;">Fecha usuario</th>
          <th style="width:100px;">Kilometraje</th>
          <th style="width:100px;">Eliminar</th>
      </tr>
  </thead>
  <tbody>
    <?php $aux = count($odometro);?>
    @foreach($odometro as $key => $val)
          <tr>             
            <td style="text-align:center;">{{$aux}}</td>
            <td style="text-align:center;">{{explode(".",$val->fecha_servidor)[0]}}</td>
            <td style="text-align:center;">{{$val->fecha}}</td>
            <td style="text-align:center;color:blue">{{$val->kilometraje}}</td>
            <td style="text-align:center;color:red">
                  <i  class="fa fa-times" onclick="eliminarOdometro('{{strtoupper($placa)}}','{{$val->id}}')" aria-hidden="true" style="    font-size: 27px;    text-align: center;    display: block;cursor:pointer;"></i>
                </td>
          </tr>
        <?php $aux--;?>
      @endforeach
  </tbody>
</table> 
