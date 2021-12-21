<?php  
  $fechaD = explode("-",$fechaR)[2];
  $fechaM = explode("-",$fechaR)[1];
  $fechaA = explode("-",$fechaR)[0];
?>
<table id="tbl_restric" class="table table-striped table-bordered" cellspacing="0" width="99%">
  <thead>
      <tr>          
          <th style="width:100px; !important">Estado</th>
          <th style="width:200px;">T. Restricción</th>
          <th style="width:200px;">Descrip. Restricción</th>
          <th style="width:100px; !important">Impacto</th>
          <th style="width:100px; !important">Fecha Limite</th>
          <th style="width:100px; !important">Responsable</th>
          <th style="width:100px; !important">Evidencia</th>
          
          <th style="width:100px; !important">Fecha Cierre</th>
          <!--<th style="width:20px; !important"></th>-->
      </tr>
  </thead>
  <tbody id="tbl_body_restric">
        @foreach ($restric as $res => $val)
          <tr>
             <td>
             <?php

              #E5E5E5
              if($val[0]->id_estado == "X")
              {
                $color = "black";
              }

              if($val[0]->id_estado == "A")
              {
                $color = "red";
              }

              if($val[0]->id_estado == "C")
              {
                $color = "rgb(0,143,65)";
              }

              if($val[0]->id_estado == "P")
              {
                $color = "blue";

                $datetime1 = new DateTime(explode(" ",$val[0]->fecha_limite)[0]);
                $datetime2 = new DateTime(date("Y-m-d"));
                $interval = $datetime2->diff($datetime1);
                $dias =  intval($interval->format('%R%a'));

                if($dias <=7)
                  $color = "yellow";
              }

            ?>

              @if($val[0]->id_estado == "A")  
                <span style="background-color:{{$color}};color:white;padding: 3px;    border-radius: 4px;">POR INICIAR</span>
              @endif
              @if($val[0]->id_estado == "C")
                <span style="background-color:{{$color}};color:white;padding: 3px;    border-radius: 4px;">LEVANTADA</span>
              @endif
              @if($val[0]->id_estado == "X")
                <span style="background-color:{{$color}};color:white;padding: 3px;    border-radius: 4px;">ANULADA</span>
              @endif
              @if($val[0]->id_estado == "P")
                  @if($dias <=7)
                    <span style="color:black;background-color:{{$color}};padding: 3px;    border-radius: 4px;">EN PROCESO < 7 DÍAS</span>
                  @else
                    <span style="background-color:{{$color}};color:white;padding: 3px;    border-radius: 4px;">EN PROCESO</span>
                  @endif
              @endif
              </td>
            <td style="width:160px;"><p style="word-wrap: break-word;width:160px;" >{{$val[0]->texto_restriccion}}</p></td>
            <td style="width:160px;"><p style="word-wrap: break-word;width:160px;" >{{$val[0]->restriccion_descripcion}}</p></td>
            <td style="width:160px;"><p style="word-wrap: break-word;width:160px;">{{$val[0]->impacto}}</p></td>
            <td>
            
            <span>{{explode(" ",$val[0]->fecha_limite)[0]}}</span>

            </td>
            <td>{{strtoupper($val[0]->responsable)}}</td>
            <td></td>
           
            
            <td>
              @if($val[0]->fecha_cierre != NULL)
                  {{explode(" ",$val[0]->fecha_cierre)[0]}}
              @endif
            </td>
            <!--<td>
            <?php
              $correos = "";
              for($i = 0; $i < count($val[1]); $i++)
              {
                $correos .= $val[1][$i]->correo . ";";
              }
            ?>
              <button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalRestriccion(this)"
                data-restric="{{$val[0]->id_restriccion}}" data-correo="{{$correos}}" data-tipo="{{$val[0]->tipo}}"
                data-desF = "{{$val[0]->restriccion_descripcion}}"
                ><i class="fa fa-search" aria-hidden="true"></i></button>
            </td>-->
          </tr>
        @endforeach
  </tbody>
</table> 


