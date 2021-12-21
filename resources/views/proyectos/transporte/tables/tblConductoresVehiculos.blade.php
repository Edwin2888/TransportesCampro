
<table id="documetos_vehiculo" class="table table-striped table-bordered" cellspacing="0" width="99%">
  <thead>
      <tr>
          <th style="width:10px;">No.</th>
          <th style="width:100px;">Nombres</th>
          <th style="width:100px;">Identificación</th>
          @if(isset($opc))
            @if($opc == 1)
              <th style="width:100px;">Pertenece a otro equipo</th>
            @else
              <th style="width:100px;">Tiene otro equipo</th>
            @endif
          @endif
          <th style="width:10px;"></th>
      </tr>
  </thead>
  <tbody>
    <?php $aux = 1;?>
    @if(count($conductores) == 0)
      <tr>
        <td colspan="3">No hay datos para mostrar</td>
      </tr>
    @endif

    @foreach($conductores as $key => $val)
          <tr>  
            <td>{{$aux}}</td>
            <td>{{strtoupper($val->nombres)}} {{strtoupper($val->apellidos)}}</td>
            <td>{{$val->identificacion}}</td>
            @if(isset($opc))

            @if($opc == 1)
            <td style="width:100px;">
              @if($val->nombre_equipo != NULL)
                <b style="color:red">{{$val->nombre_equipo}} - {{$val->nombreOtroLider}}</b>
              @else
                <!--if($val->lid == NULL)-->
                  <b style="color:green">NO ES COLABORADOR EN OTRO EQUIPO</b>
                <!--else
                  <b style="color:red">ES LÍDER EN OTRO EQUIPO</b>-->
                <!--endif-->
              @endif
              </td>
            @else
              <td style="width:100px;">
              @if($val->nombre_equipo != NULL)
                  <b style="color:red">{{$val->nombre_equipo}}</b>
                @else
                  <b style="color:green">NO TIENE OTRO EQUIPO</b>
                @endif
              </td>
            @endif
            @endif
            <td>
              @if(isset($opc))
                @if($opc == 1 || $opc == 2)
                  @if($val->nombre_equipo == NULL)
                    @if($opc == 1)
                      <!--if($val->lid == NULL)-->
                        <i onclick="seleccionaPersona('{{trim($val->nombres)}} {{trim($val->apellidos)}}','{{trim($val->identificacion)}}','{{trim($val->domicilio)}}','{{trim($val->telefono1)}}','{{trim($val->correo)}}')" class="fa fa-sign-in" aria-hidden="true" style="    font-size: 18px;    text-align: center;    display: block;cursor:pointer;"></i>
                      <!--endif-->
                    @else
                      <i onclick="seleccionaPersona('{{trim($val->nombres)}} {{trim($val->apellidos)}}','{{trim($val->identificacion)}}','{{trim($val->domicilio)}}','{{trim($val->telefono1)}}','{{trim($val->correo)}}')" class="fa fa-sign-in" aria-hidden="true" style="    font-size: 18px;    text-align: center;    display: block;cursor:pointer;"></i>
                    @endif
                  @endif
                @endif
              @else
                <i onclick="seleccionaPersona('{{trim($val->nombres)}} {{trim($val->apellidos)}}','{{trim($val->identificacion)}}','{{trim($val->domicilio)}}','{{trim($val->telefono1)}}','{{$val->correo}}')" class="fa fa-sign-in" aria-hidden="true" style="    font-size: 18px;    text-align: center;    display: block;cursor:pointer;"></i>
              @endif
            </td>
          </tr>
        <?php $aux++;?>
    @endforeach
  </tbody>
</table> 
