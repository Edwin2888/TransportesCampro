@if($prog != null)
<table id="baremo_add_eje" class="table table-striped table-bordered" cellspacing="0" width="99%">
  <thead>
      <tr>
        @if($conso == 0)
            <th style="width:10px;">DC</th>
        @endif
          
        @if($opc == 4 && $conso == 0))
          <th style="width:10px;">GOM</th>
        @endif
          <th style="width:10px;">COD. SAP</th>
          <th style="width:100px; !important">Descripción</th>
          <th style="width:100px; !important">U.Medida</th>
          <th style="width:100px; !important">Levantamiento</th>
          <th style="width:100px; !important">Confirmada</th>
          @if($conc == 1)
            <th style="width:100px; !important">Conciliado</th>
          @else
            <th style="width:100px; !important">Consumido</th>
          @endif
          <th style="width:100px; !important">Reintegros</th>
          <th style="width:100px; !important">Saldo</th>
      </tr>
  </thead>
  <tbody>
      @for ($i=0; $i < count($prog); $i++)
          <tr>
            @if($conso == 0)
            <td style="text-align:center;">
            <form action="{{config('app.Campro')[2]}}/campro" method="POST" target="_blank">
                <input type="hidden" name="user" value="{{Session::get('user_login')}}"/>
                <input type="hidden" name="ruta" value="{{config('app.Campro')[2]}}/campro/inventarios/{{ explode('_',\Session::get('proy_short'))[0]}}/movimientos.php?id_documento={{$prog[$i]->id_documento}} "/>
                <input type="submit" style="color:blue;font-size:10px;cursor:pointer;    background: transparent;    border: 0px;" value="{{$prog[$i]->id_documento}} ">
             </form>
            
            </td>
            @endif

            @if($opc == 4 && $conso == 0)
              <td>{{$prog[$i]->gom}}</td>
            @endif

            <td style="text-align:center;">{{$prog[$i]->codigo_sap}}</td>
            <td style="text-align:center;">{{$prog[$i]->nombre}}</td>
            <td style="text-align:center;">{{$prog[$i]->id_unidad}}</td>
            <td style="text-align:center;color:blue;">{{$prog[$i]->cantidad_replanteo}}</td>
            <?php
              $opera = floatval($prog[$i]->consumo) + 0;
              $opera = floatval($prog[$i]->solicitado) - $opera;
              $opera = $opera - floatval($prog[$i]->reintegro);
            ?>
            <td style="text-align:center;color:blue;">{{($prog[$i]->solicitado == '.00' ? '0' : $prog[$i]->solicitado)}}</td>
            <td style="text-align:center;color:blue;">{{($prog[$i]->consumo == '.00' ? '0' : $prog[$i]->consumo)}}</td>
            <td style="text-align:center;color:blue;">{{($prog[$i]->reintegro == '.00' ? '0' : $prog[$i]->reintegro)}}</td>
            <td style="text-align:center;color:blue;">
              @if($opera  != 0)
                  <span style="color:red">{{$opera}}</span>
                @else
                  {{$opera}}
                @endif
            </td>
          </tr>
      @endfor
  </tbody>
</table> 
@endif
