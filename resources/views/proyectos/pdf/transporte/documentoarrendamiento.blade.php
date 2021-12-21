<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Documento de arrendamiento</title>

    <style type="text/css">
    .panel_1
    {
      border: 1px solid black;
      height: 70px;
    }

    .panel_1 div
    {
      display: inline-block;
      border-right:1px solid gray;
      height: 70px;
      margin-top: 8px;
    }

    .panel_1 div:nth-child(1)
    {
      width: 25%;
    }

    .panel_1 div:nth-child(2)
    {
      width: 50%;
    }

    .panel_1 div:nth-child(3)
    {
      width: 25%;
      border-right:0px;
    }

    .panel_2
    {
      border: 1px solid black;
      height: 190px;
      margin-top: 3px;
    }

    .primero_renglon
    {
      display: inline-block;
      width: 55%;
      margin: 0px;
      padding: 0px;
    }

    .segundo_renglon
    {
      display: inline-block;
      width: 45%;
      margin: 0px;
      padding: 0px;
    }

    </style>
  </head>
  <body>
 
    <main>
      <div class="panel_1">
        <div>
          <img src="img/logo.png" style="width:80%;margin-left:20px;margin-top:3px;">
        </div>
        <div>
            <p style="text-align:center;margin-top:25px;"><b>DOCUMENTO DE ARRENDAMIENTO</b></p>
        </div>
        <div>
          <p style="text-align:center;margin-top:20px;"><b>No.: {{$doc}}</b></p>
        </div>
      </div>

      <div class="panel_2">
        <p class="primero_renglon" style="position:relative;margin-top:4px;">
          <span style="font-size:14px;font-weight:bold;">Proyecto:</span> {{$data->nombre}}
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:4px;">
          <span style="font-size:14px;font-weight:bold;">Matricula:</span> {{$data->placa}}
        </p>
        <br>
        <p class="primero_renglon" style="position:relative;margin-top:-15px;">
          <span style="font-size:14px;font-weight:bold;">Estado del documento:</span>
          @if($data->id_estado == "E3")
            CONFIRMADO
          @elseif($data->id_estado == "E1")
            GENERADO
          @else
            {{$data->id_estado}}
          @endif
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:-15px;">
          <span style="font-size:14px;font-weight:bold;">Periodo:</span> {{$data->mes}} / {{$data->anio}}
        </p>
        <br>

        <p class="primero_renglon" style="position:relative;margin-top:-27px;">
          <span style="font-size:14px;font-weight:bold;"> Tipo de vehículo:</span>  {{$data->tipo_vehiculo}}
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:-27px;">
          <span style="font-size:14px;font-weight:bold;"> Tipo CAM:</span> {{$data->nombre_cam}}
        </p>
        <br>

        <p class="primero_renglon" style="position:relative;margin-top:-40px;">
          <span style="font-size:14px;font-weight:bold;"> Marca:</span>  {{$data->marca}}
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:-40px;">
          <span style="font-size:14px;font-weight:bold;"> Canon:</span>  ${{number_format($data->canon_actual, 2)}} 
        </p>
        <br>


        <p class="primero_renglon" style="position:relative;margin-top:-55px;">
          <span style="font-size:14px;font-weight:bold;"> N° de días:</span> {{$data->cantidad_dias}}
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:-55px;">
          <span style="font-size:14px;font-weight:bold;"> Total a pagar:</span> ${{number_format($data->total_pagar, 2)}}
        </p>
        <br>
        <p class="primero_renglon" style="position:relative;margin-top:-73px;">
          <span style="font-size:14px;font-weight:bold;"> Proveedor:</span> {{$data->proveedor}}
        </p>

        <br>

        <p class="primero_renglon" style="position:relative;margin-top:-71px;">
          <span style="font-size:14px;font-weight:bold;"> Fecha de generación:</span> {{$fecha}}
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:-51px;">
          <span style="font-size:14px;font-weight:bold;">  Usuario:</span> {{$user}}
        </p>
        <br>

        <p style="position:relative;margin-top:-51px;"><b>DÍAS QUE APLICAN PARA EL DOCUMENTO</b></p>

        <div style="width:98%">
        <?php 
          $aux = 1;
        ?>
          <?php  $observaciones = ""; ?>
          @foreach($dias as $key => $val)
            <?php  $color = "#92ff92"; ?>
            @if($val->check == 0)
              <?php  $observaciones .= "datos" ;
                  $color = "#eca8a8"; ?>
            @endif

           <?php 
              if($aux == 12)
              {
                echo "<br>";
                $aux = 1;
              }

             ?>
            <div style="    width: 7.3%;    text-align: center;    margin-top:3px;margin-right: 10px;
      font-size: 12px;    color: #777;    height: 60px;    outline: 0.1mm solid #ccc;    display: inline-block;background:{{$color}}" ;>

              @if($val->mes == 1)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Enero</span></b>
              @endif

              @if($val->mes == 2)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Febrero</span></b>
              @endif

              @if($val->mes == 3)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Marzo</span></b>
              @endif

              @if($val->mes == 4)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Abril</span></b>
              @endif

              @if($val->mes == 5)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Mayo</span></b>
              @endif

              @if($val->mes == 6)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Junio</span></b>
              @endif

              @if($val->mes == 7)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Julio</span></b>
              @endif

              @if($val->mes == 8)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Agosto</span></b>
              @endif

              @if($val->mes == 9)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Septiembre</span></b>
              @endif

              @if($val->mes == 10)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Octubre</span></b>
              @endif

              @if($val->mes == 11)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Noviembre</span></b>
              @endif

              @if($val->mes == 12)
                <b style=" color:#777;font-size: 27px;    margin-left: 3px;margin-top:10px;display:block;">{{$val->dia}} <br> <span style="    font-size: 11px;    position: relative;    top: -5px;    left: -2px;">Diciembre</span></b>
              @endif


            </div>

            <?php 
              $aux++;
            ?>
          @endforeach

        </div>
       
        <br>
        <br>
        @if($observaciones != "")
          <p style="margin:0px;"><b>OBSERVACIONES DE LOS DÍAS QUE NO SE VAN A PAGAR</b></p>

           @foreach($dias as $key => $val)
              @if($val->check == 0)
                <p> - {{$val->dia}}/{{$val->mes}}/{{$val->anio}}:{{$val->observacion}}</p>
              @endif
            @endforeach
        @endif
        <p style="margin:0px;paddin:0px"></p>

      </div>

      <div class="panel_3">
        
      </div>
  </body>
</html>