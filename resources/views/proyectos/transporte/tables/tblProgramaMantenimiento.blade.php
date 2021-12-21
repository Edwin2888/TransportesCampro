<table id="tbl_seguimiento_mantenimientos" class="table table-striped table-bordered table_center" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">Centro Costo</th>
            <th style="width:20px;">Proyecto</th>
            <th style="width:20px;">Placa</th>
            <th style="width:20px;">Propietario</th>
            <th style="width:20px;">Tipo V.</th>
            <th style="width:20px;">Rutina</th>
            <th style="width:20px;">F. Próximo Mtto</th>
            <!-- <?php
                 $canti = Session::get('cant_mmto_prog_man');
                 if($canti>1){
                     for($i=1;$i<($canti);$i++){ ?>                         
                        <th style="width:20px;">Fecha <?= ($i+1) ?></th>
            <?php    }
                 }
            ?> -->
            <th style="width:20px;">Fecha último Mtto</th>
            <th style="width:20px;">Km Mtto</th>
            <th style="width:20px;">Km Próximo Mtto</th>
            <th style="width:20px;">F. último Km</th>
            <th style="width:20px;">Último Km</th>
            <th style="width:20px;">Fecha Km Previo</th>
            <th style="width:20px;">Km previo</th>
            <th title="Recorrido promedio segun tipo de vehiculo" style="width:20px;">R.P Real</th>
            <th title="Recorrido promedio segun tipo de vehiculo" style="width:20px;">R.P Ficha</th>
            <th title="Recorrido promedio segun tipo de vehiculo" style="width:20px;">R.P Tipo  </th>
            <th style="width:20px;">Km faltante</th>
            <th style="width:20px;">Día próximo</th>
            <th style="width:20px;">Ult incidencia</th>
        </tr>
    </thead>
    <tbody>
        <!-- $val->id_estado-->
        <?php 
            $aux = 0;
            $fechaActual = date("Y-M-d");
        ?>
        @foreach ($dat as $key => $val)
            <tr>
                <td style="text-align:center;">{{$val->ceco}}</td>
                <td style="text-align:center;">{{$val->nombre_proyecto}}</td>
                <td style="text-align:center;color:blue;">
                    <a href="../../selectVehiculo/{{$val->placa}}" >{{strtoupper($val->placa)}}</a>
                </td>
                <td style="text-align:center;">{{$val->nombre_propietario}}</td>
                <td style="text-align:center;">{{$val->tipo_vehiculo}}</td>  
                <td style="text-align:center;">{{$val->Rutina}}</td>
                @if($val->fecha_ult_mtto=='' || $val->fecha_ult_mtto==null || $val->km_mtto== 0 || $val->km_mtto=='' || $val->km_mtto==null)
                    <td style="text-align:center;"><span style="background-color:red;color:white;    padding: 4px;    border-radius: 4px;">INNMEDIATO <br> {{$fechaActual}}</span></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                    <td style="text-align:center;"></td>
                @elseif($val->ult_km=='' || $val->ult_km==null || $val->fecha_km_previo=='' || $val->fecha_km_previo==null)
                    <?php
                        $faltante =  (int)$val->km_prox_mtto - (int)$val->km_mtto;
                        if((int)$val->km_promedio != 0 ){
                            $km_promedio = (int)$faltante/(int)$val->km_promedio;
                            $fecha_prox_mantenimiento = date("Y-M-d",strtotime($val->fecha_ult_mtto."+ $km_promedio days"));
                        }
                        else{
                            $fecha_prox_mantenimiento  = $val->fecha_ult_mtto;
                        }
                
                    ?>
                <td style="text-align:center;">
                    @if($faltante <= 0)
                            <span style="background-color:red;color:white;    padding: 4px;    border-radius: 4px;">INNMEDIATO</span>
                        @else
                            <?php  
                                $datetime1 = new DateTime(date("Y-M-d"));
                                $datetime2 = new DateTime($fecha_prox_mantenimiento);
                                $interval = $datetime1->diff($datetime2);
                                $dias = intval($interval->format('%R%a')) + 1;
                            ?>
                            @if($dias <= 0)
                                <span style="background-color:red;color:white;    padding: 4px;    border-radius: 4px;">INNMEDIATO <br> {{ $fecha_prox_mantenimiento }} </span>
                            @else
                                @if($val->dias_proximo <= 20)
                                    <span style="background-color:orange;color:white;    padding: 4px;    border-radius: 4px;">{{$fecha_prox_mantenimiento}}</span>
                                @else
                                    <span style="background-color:green;color:white;    padding: 4px;    border-radius: 4px;">{{ $fecha_prox_mantenimiento }}</span>
                                @endif
                            @endif
                    @endif
                </td>
                    <td style="text-align:center;">{{explode(" ",$val->fecha_ult_mtto)[0]}}</td>
                    <td style="text-align:center;">{{$val->km_mtto}}</td>
                    <td style="text-align:center;">{{$val->km_prox_mtto}}</td>
                    <td style="text-align:center;">{{$val->fecha_ult_km}}</td>
                    <td style="text-align:center;">{{$val->ult_km}}</td>
                    <td style="text-align:center;">{{$val->fecha_km_previo}}</td>
                    <td style="text-align:center;">{{$val->km_previo}}</td>
                    @if($val->seleccionRP=='rpr')
                    <td style="text-align:center;">0</td>
                    @else
                    <td style="text-align:center;">0</td>
                    @endif

                    @if($val->seleccionRP=='rpf')
                    <td style="text-align:center;background-color: green">{{$val->km_promedio}}</td>
                    @else
                    <td style="text-align:center;">{{$val->km_promedio}}</td>
                    @endif
                   
                    @if($val->seleccionRP=='rpt')
                    <td style="text-align:center;background-color: green">{{ number_format($val->recorrido_promedio,2)}}</td>
                    @else
                    <td style="text-align:center;">{{ number_format($val->recorrido_promedio,2)}}</td>
                    @endif
                    <td style="text-align:center;">{{$faltante}}</td>
                    <td style="text-align:center;">{{$km_promedio}}</td>
                    <td style="text-align:center;">{{$val->incidencia}}</td>
                @else
                    <td style="text-align:center;">
                        @if($val->fecha_proximo_mtto == 0)
                            <span style="background-color:red;color:white;    padding: 4px;    border-radius: 4px;">Inmediato</span>
                        @else
                            <?php  
                                $datetime1 = new DateTime(date("Y-M-d"));
                                $datetime2 = new DateTime($val->fecha_proximo_mtto);
                                $interval = $datetime1->diff($datetime2);
                                $dias = intval($interval->format('%R%a')) + 1;
                            ?>
                            @if($dias <= 0)
                                <span style="background-color:red;color:white;    padding: 4px;    border-radius: 4px;">INNMEDIATO <br> {{ $val->fecha_proximo_mtto }} </span>
                            @else
                                @if($val->dias_proximo <= 20)
                                    <span style="background-color:orange;color:white;    padding: 4px;    border-radius: 4px;">{{$val->fecha_proximo_mtto}}</span>
                                @else
                                    <span style="background-color:green;color:white;    padding: 4px;    border-radius: 4px;">{{ $val->fecha_proximo_mtto }}</span>
                                @endif
                            @endif
                            
                        @endif
                    </td>
                    <td style="text-align:center;">{{explode(" ",$val->fecha_ult_mtto)[0]}}</td>
                    <td style="text-align:center;">{{$val->km_mtto}}</td>
                    <td style="text-align:center;">{{$val->km_prox_mtto}}</td>
                    <td style="text-align:center;">{{$val->fecha_ult_km}}</td>
                    <td style="text-align:center;">{{$val->ult_km}}</td>
                    <td style="text-align:center;">{{$val->fecha_km_previo}}</td>
                    <td style="text-align:center;">{{$val->km_previo}}</td>
                    @if($val->seleccionRP=='rpr')
                    <td style="text-align:center;background-color: green">{{$val->promedio}}</td>
                    @else
                    <td style="text-align:center;">{{$val->promedio}}</td>
                    @endif

                    @if($val->seleccionRP=='rpf')
                    <td style="text-align:center;background-color: green">{{$val->km_promedio}}</td>
                    @else
                    <td style="text-align:center;">{{$val->km_promedio}}</td>
                    @endif
                   
                    @if($val->seleccionRP=='rpt')
                    <td style="text-align:center;background-color: green">{{ number_format($val->recorrido_promedio,2)}}</td>
                    @else
                    <td style="text-align:center;">{{ number_format($val->recorrido_promedio,2)}}</td>
                    @endif
                    
                    <td style="text-align:center;">{{$val->km_faltante}}</td>
                    <td style="text-align:center;">{{$val->dias_proximo}}</td>
                    <td style="text-align:center;">{{$val->incidencia}}</td>
                @endif
                
            </tr>
        @endforeach
    </tbody>
</table>

<?php
Session::forget("selTipoVehiculoProgMant");
Session::forget("selProyectoClienteProgMant");
Session::forget("fecha_inicio_prog_man");
Session::forget("cant_mmto_prog_man");

?>