<table id="tbl_seg_datos" style="border-collapse: separate;" class="table table-striped table-bordered table_center" width="99%" cellpadding="0" cellspacing="0" border="0"` >
    <thead>
        <tr>
            <th >ORDEN</th>
            <th style="width:20px;">T</th>
            <th >Placa</th>
            <th >Tipo vehículo (TP)</th>
            <th >Tipo CAM</th>
            <th >Tipo Mant.</th>
            <th >Novedad Reportada</th>
            <th >Componente</th>
            <th >Tipo de falla</th>
            <th >Respuesta</th>
            <th>Inhabilitado</th>
            <th >Fecha prog.</th>
            <th >Tiempo estimado</th>
            <th >Tiempo restante</th>
            <th >Cumplimiento</th>
            <th >Fecha cumplido</th>
            <th >Proyecto</th>
            <th >Observaciones</th>
            <th >Estado</th>
            <th >Realizado por</th>
            <th >Cerrada por</th>
            <th >Proximo MMTO</th>
            <th >Valor final</th>
            <th >KM</th>
            <th >Entrada a taller</th>
            <th >Obser. entrada a taller</th>
            <th >Valor cotización</th>
            <th >Obs cotización</th>
            <th >Salida taller</th>
            <th >Obs. Salida taller</th>
            <th >Fecha factura</th>
            <th >Num factura</th>
            <th >Solicitante</th>
        </tr>
    </thead>
    <tbody>
        <!-- $val->id_estado-->
        <?php $aux = 0; ?>
        @foreach ($dat as $key => $val)
            <tr>
                <td style="text-align:center;font-weight:bold;">
                
                    <a href="../../transversal/incidencia/{{$val->incidencia}}" target="_blank">{{strtoupper($val->incidencia)}}</a>
                </td>

                <td>
                    <?php
                        $hora = intval($val->tiempo_estimado); //Hora de inhabilitado
                        $minutosTotales = $hora * 60; //Minutos totales
                        $minutos = intval($val->minutos); //Minutos que lleva actual
                        $porc = $minutos * 100 / ($minutosTotales == 0 ? 1 : $minutosTotales);

                        if($porc < 30)
                            $color = "green";

                        if($porc < 80 && $porc >= 30)
                            $color = "orange";

                        if($porc >= 80)
                            $color = "red";

                        $p = number_format ( floatval($porc),2);
                        if($porc > 100)
                        {
                            $porc = 100;
                            $p = 100;
                        }

                        if($porc < 0)
                        {
                            $porc = 0;
                            $p = 0;
                        }
                        
                    ?>
                    <span style="color:{{$color}};font-size:16px"><i class="fa fa-flag" aria-hidden="true"></i><br></span>

                </td>
                <td style="text-align:center;color:blue;">
                    <a href="../../selectVehiculo/{{$val->placa}}" >{{strtoupper($val->placa)}}</a>
                </td>
                <td style="text-align:center;">{{$val->tipoVehiculo}}</td>
                <td style="text-align:center;">{{strtoupper($val->cam)}}</td>
                <td style="text-align:center;">
                    
                    <?php
                        $mystring = $val->resp;

                        // Nótese el uso de ===. Puesto que == simple no funcionará como se espera
                        // porque la posición de 'a' está en el 1° (primer) caracter.
                        if ((strpos($mystring, "Correctivo") === false)) {
                            echo "PREVENTIVO";
                        } else {
                            echo "CORRECTIVO";
                        }
                    ?>
                </td>     
                @if($val->version_arbol == "1" )
                    <td style="text-align:center;">{{$val->novedad}}</td>             
                    <td style="text-align:center;">{{$val->comp}}</td>             
                    <td style="text-align:center;">{{$val->falla}}</td>             
                    <td style="text-align:center;">{{$val->resp}}</td>
                @else
                    <td style="text-align:center;">{{$val->novedad2}}</td>
                    <td style="text-align:center;">{{$val->comp2}}</td>
                    <td style="text-align:center;">{{$val->falla2}}</td>
                    <td style="text-align:center;">{{$val->resp2}}</td>

                @endif
                <td style="text-align:center;">
                    @if($val->inhabilitado==1)
                    <i class="fa fa-close" style="color: red; font-size: 20px"></i>
                    @endif
                </td>


                <td style="text-align:center;">{{$val->fecha_servidor}}</td>
                <td style="text-align:center;">{{$val->tiempo_estimado}} Hrs</td>
                @if(Session::get('selEstado') == "E06")
                    <td></td>
                    <td style="text-align:center;"><span style="color:{{$color}};font-weight:bold">{{(intval($val->tiempo_estimado) - intval($val->ho) < 0 ? 'NO' : 'SI')}} ({{(intval($val->tiempo_estimado) - intval($val->ho))}}:{{$val->mi}} Hrs)</span></td>                  
                @else
                    <td style="text-align:center;"><span style="color:{{$color}};font-weight:bold">{{(intval($val->tiempo_estimado) - intval($val->ho))}}:{{$val->mi}} Hrs</span></td>                  
                    <td></td>
                @endif
                <td style="text-align:center;">{{$val->fecha_cumplido}}</td>              
                <td style="text-align:center;">{{$val->proyecto}}</td>
                <td >

                        {{$val->observaciones}} 

                </td>
                <td style="text-align:center;">{{$val->nombreE}}</td>
                <td style="text-align:center;">{{$val->realizadoPor}}</td>
                <td style="text-align:center;">{{$val->cerradoPor}} </td>
                <td style="text-align:center;">{{$val->prox_mmto}}</td>
                <td style="text-align:center;">{{$val->valor_final}}</td>
                <td style="text-align:center;">{{$val->km}}</td>
                <td style="text-align:center;">{{$val->fecha_ingreso}}</td>
                <td style="text-align:center;">{{str_replace("-","/",$val->observacion)}}</td>
                <td style="text-align:center;">{{$val->costo_ingreso}}</td>
                <td style="text-align:center;">{{$val->obs_cotiza}}</td>
                <td style="text-align:center;">{{$val->fecha_salida}}</td>
                <td style="text-align:center;">{{$val->observacion_salida}}</td>
                <td style="text-align:center;">{{$val->fecha_fact}}</td>
                <td style="text-align:center;">{{$val->num_fact}}</td>
                <td style="text-align:center;">{{$val->solicitante}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<?php
Session::forget("fecha_inicio");
Session::forget("fecha_corte");
Session::forget("selTipoVehiculo");
Session::forget("selEstado");
Session::forget("selProyectoCliente");
Session::forget("txt_incidencia");
Session::forget("tipoMANTSelect");
Session::forget("txt_placa_filter");
?>