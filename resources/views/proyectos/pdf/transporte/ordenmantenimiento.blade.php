<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Orden de mantenimiento</title>

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
      height: 100px;
      margin-top: 3px;
    }

    .primero_renglon
    {
      display: inline-block;
      width: 65%;
      margin: 0px;
      padding: 0px;
    }

    .segundo_renglon
    {
      display: inline-block;
      width: 35%;
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
            <p style="text-align:center;margin-top:25px;"><b>ORDEN DE MANTENIMIENTO</b></p>
        </div>
        <div>
          <p style="text-align:center;margin-top:20px;"><b>No.: {{$inci->incidencia}}</b></p>
        </div>
      </div>

      <div class="panel_2">
        <p class="primero_renglon" style="position:relative;margin-top:4px;">
          <span style="font-size:14px;">Matricula:</span> {{$inci->placa}} 
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:4px;">
          <span style="font-size:14px;">Conductor:</span>
        </p>
        <br>
        <p class="primero_renglon" style="position:relative;margin-top:-12px;">
          <span style="font-size:14px;">Tipo de vehículo:</span> {{$inci->tipo_vehiculo}} 
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:-12px;">
          <span style="font-size:14px;">Teléfono:</span> {{$inci->telefono}} 
        </p>
        <br>
        <p class="primero_renglon" style="position:relative;margin-top:-27px;">
          <span style="font-size:14px;"> Marca:</span> {{$inci->marca}}  <span style="margin-left:70px;">Modelo:<span style="font-size:14px;">  </span> {{$inci->modelo}}</span>
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:-27px;">
          <span style="font-size:14px;"> Tipo Mantto:</span> {{$inci->tipo_man}}
        </p>
        <br>
        <p class="primero_renglon" style="position:relative;margin-top:-44px;">
          <span style="font-size:14px;"> Proveedor:</span> {{$inci->nombre_proveedor}}
        </p>
        <p class="segundo_renglon" style="position:relative;margin-top:-44px;">
          <span style="font-size:14px;">  Programado:</span>{{explode(".",$inci->fechaP)[0]}}
        </p>

        <table style="width:725px;margin:0px;padding;0px;border:1px solid gray;margin-top:-50px;">
          <tr>
              <td style="font-size:14px;">Observaciones generales</td>
          </tr>
          <tr style="border-top:1px solid gray">
              <td style="border-top:1px solid gray"><b>{{$inci->observacion}}</b></td>
          </tr>
        </table>

        <table style="width:725px;margin:0px;padding;0px;border:1px solid gray;margin-top:20px;">
          <tr>
              <th style="width:20px;border-right:1px solid gray;font-size:12px;">ITEM</th>
              <th style="font-size:12px;">DESCRIPCIÓN</th>
          </tr>
          <tr style="border-top:1px solid gray">
              <td style="border-top:1px solid gray;border-right:1px solid gray;font-size:12px;"><b>1</b></td>
              <td style="border-top:1px solid gray;font-size:15px;">MANTENIMIENTO CORRECTIVO SEGUN DETALLE DE OBSERVACIONES</td>
          </tr>
        </table>
        <br>
        <br>
        <p style="margin:0px;paddin:0px">{{$user}}</p>

      </div>

      <div class="panel_3">
        
      </div>
  </body>
</html>