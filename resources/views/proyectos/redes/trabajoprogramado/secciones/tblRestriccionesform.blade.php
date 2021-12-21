<table id="restricciones" class="table table-striped table-bordered" cellspacing="0" width="99%" style="margin-top: 13px;position: relative;">
  <thead>
      <tr>
          <th style="width:10px;"></th>
          <th style="width:10px;">Estado</th>
          <th style="width:10px;">Fecha creación</th>
          <th style="width:10px;">Fecha límite</th>
          <th style="width:10px;">Proyecto</th>
          <th style="width:10px;">ManiObra</th>
          <th style="width:10px;">Fecha ejecución</th>
          <th style="width:10px;">GOM</th>
          <th style="width:10px;">Restricción</th>
          <th style="width:10px;">Descripción</th>
          <th style="width:10px;">Impacto</th>
          <th style="width:10px;">Responsable</th>
          <th style="width:10px;">Fecha cierre</th>
          <th style="width:10px;">Evidencia cierre</th>
          <th style="width:10px;">Cerrar R.</th>
      </tr>
  </thead>
  <tbody id="tbl_gom_wbs">  
            @foreach ($proyectos as $key => $valor)
              <tr>
                <?php
                  $estados = "POR INICIAR";
                  $color = "red";
                  if($valor->id_estado == "X")
                  {
                    $estados = "ANULADA";
                    $color = "black";
                  }

                  if($valor->id_estado == "C")
                  {
                    $estados = "LEVANTADA";
                    $color = "rgb(0,143,65)";
                  }

                  if($valor->id_estado == "P")
                  {

                    $datetime1 = new DateTime(explode(" ",$valor->fecha_limite)[0]);
                    $datetime2 = new DateTime(date("Y-m-d"));
                    $interval = $datetime2->diff($datetime1);
                    $dias =  intval($interval->format('%R%a'));

                    $estados = "EN PROCESO";
                    $color = "blue";
                    if($dias <=7)
                      $color = "yellow";
                    
                  }

                ?>
                  <td>
                    <div style="background:{{$color}};width:20px;height:20px;position:relative;left:12px;"></div>
                  </td>
                  <td>{{$estados}}</td>
                  <td>{{explode(" ",$valor->fecha_registro)[0]}}</td>
                  <td>{{explode(" ",$valor->fecha_limite)[0]}}</td>
                  <td>{{$valor->nombre}}</td>
                  <td><span onclick="verOT('{{$valor->id_orden}}')" style="color:blue;cursor:pointer;">{{$valor->id_orden}}</span></td>
                  <td>{{explode(" ",$valor->fecha_ejecucion)[0]}}</td>
                  <td>{{$valor->gom}}</td>
                  <td>{{$valor->texto_restriccion1}}</td>
                  <td>{{$valor->restriccion_descripcion}}</td>
                  <td>{{$valor->impacto}}</td>
                  <td>{{$valor->responsable}}</td>
                  @if($valor->id_estado == "C")
                    <td>{{explode(" ",$valor->fecha_cierre)[0]}}</td>
                    <td>
                        <a target="blank_" href="http://201.217.195.43{{$valor->evidencia_cierre}}">Ver</a>       
                  @else
                    <td></td>
                    <td></td>
                  @endif
                  
                <td>
                  @if($valor->id_estado == "A" || $valor->id_estado == "P")
                  <?php $orden_r = "0";?>
                    @if($valor->id_orden != "" && $valor->id_orden != null)
                        <?php $orden_r =  $valor->id_orden;?>
                    @endif
                  <a target="_blank" href="../../cerrarresticciones/{{$valor->id_proyecto}}/{{$orden_r}}/{{$valor->id_restriccion}}" class="btn_cerrar">Cerrar restricción</a>
                  @endif
                </td>      
              </tr>
            @endforeach
  </tbody>
</table> 
