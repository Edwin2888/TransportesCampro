<table id="proyectos" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:200px;">PROYECTO</th>
            <th style="width:200px;">CÓDIGO</th>
            <th style="width:20px;">FECHA CREACIÓN</th>
            <th style="width:20px;">TIPO PROGRAMA</th>          
            <th style="width:100px;">VALOR INICIAL</th>
            <th style="width:20px;">VALOR LEVANTAMIENTO</th>
            <th style="width:20px;">VALOR AVANCE</th>
            <th style="width:200px;">OBRA (AVANCE OBRA)</th>
            <th style="width:200px;">WBS (AVANCE WBS)</th>
            <th style="width:200px;">NODO (AVANCE NODO)</th>
            <th style="width:200px;">MANIOBRAS</th>
            <th style="width:200px;">TIPO PROCESO</th>
            <th style="width:50px;">VER</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $sum = 0;
        $sum2 = 0;
        ?>


        @foreach ($proyec1 as $pro => $val)
            <tr>
                <td style="text-align:center;">{{$val->nombre . ' '}}</td>
                <td>{{$val->id_proyecto}}</td>
                <?php
                  $fecha = explode("-",explode(" ",$val->fecha_creacion)[0]);
                  $fecha = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
                ?>
                <td>{{$fecha}}</td>
                <td style="text-align:center;"> 
                  @if($val->tipo_proyecto == NULL || $val->tipo_proyecto == "")

                  @elseif($val->tipo_proyecto == 'T01')
                    CARTAS Y NUEVA DEMANDA
                  @else
                    INVERSIÓN Y MANTENIMIENTO
                  @endif
                </td>
                <?php
                  setlocale(LC_MONETARY, 'en_US');

                  ?>
                <td>${{($val->valor_inicial == "" ? '0' : $val->valor_inicial)}}</td>
                <td>${{number_format(($val->valorLevan == "" ? '0': $val->valorLevan ), 2)}}</td> 
                <td>${{number_format(($val->valorP == "" ? '0': $val->valorP ), 2)}}</td>

                <?php
                  if($val->valorLevan != "")
                    $sum = $sum + $val->valorLevan;

                  if($val->valorP != "")
                    $sum2 = $sum2 + $val->valorP;

                ?>
                <td>
                    <div class="progress">
                        <?php 
                          $maniObraLeva = $val->valorLevan;
                          $maniObraE = ($val->valorP == null ? 0 :$val->valorP );
                          $porcen = 0;
                          if($maniObraLeva != 0)
                            $porcen = number_format( $maniObraE * 100 / $maniObraLeva, 0, '.', ',');
                       ?>
                      <div class="progress-bar progress-bar-success" role="progressbar" style="width:{{$porcen}}%">
                       {{$porcen}}%
                      </div>
                    </div>
                </td>
                 <td>
                    <div class="progress">
                      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="0"
                      aria-valuemin="0" aria-valuemax="100" style="background:#52afff;">
                        <span class="ejecucion_wbs_span" data-cantWL="{{$val->cantWL}}" data-proyecto="{{$val->id_proyecto}}">0</span>/{{$val->cantWL}}
                      </div>
                    </div>
                </td>
                <td><?php  //echo "((".$val->cantNE."*100)/".$val->cantNL.")"; ?>
                    <div class="progress">
                      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="0"
                      aria-valuemin="0" aria-valuemax="100" style="background:#52afff;width:{{(intval($val->cantNL) == 0 ? 0 : intval($val->cantNE)*100/intval($val->cantNL)) }}%">
                        {{$val->cantNE}}/{{$val->cantNL}}   <?php if(intval($val->cantNL)>0){ echo  number_format(((intval($val->cantNE)*100)/intval($val->cantNL)),0, '.', ','),'%'; } ?>     
                      </div>
                    </div>
                </td>
                <td  style="text-align:center;">
                    {{$val->OrdenesP + $val->OrdenesE }}
                </td>
                <td  style="text-align:center;">
                    {{$val->tipo_proceso }}
                </td>
                <td style="text-align:center;">
                @if(session('user_login') != 'U04172')
                    <a href="{{url('/')}}/redes/ordenes/ver/{{$val->id_proyecto}}"><i class="fa fa-search" aria-hidden="true"></i></a>
                @endif
                </td>
            </tr>

        @endforeach
    </tbody>
</table>

<div style="
  box-shadow:         1px 0px 12px 0px rgba(50, 50, 50, 0.75);
    background-color: #337ab7;
    font-size: 20px;
    width: 24%;
    border-radius: 7px;
    padding: 5px;
    color: wheat;
">
  <p>Total Levantamiento: <b style="font-size:25px;">${{number_format($sum,2)}}</b></p>
  <p>Total Avance: <b style="font-size:25px;">${{number_format($sum2,2)}}</b></p>
</div>