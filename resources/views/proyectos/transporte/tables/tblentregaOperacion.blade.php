<table id="tbl_seg_datos" class="table table-striped table-bordered table_center" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">ORDEN</th>
            <th style="width:20px;">Placa</th>
            <th style="width:200px;">Tipo vehículo (TP)</th>
            <th style="width:200px;">Tipo CAM</th>
            <th style="width:20px;">Tipo Mant.</th>
            <th style="width:20px;">Novedad Reportada</th>
            <th style="width:20px;">Fecha prog.</th>
            <th style="width:20px;">Tiempo estimado</th>
            <th style="width:20px;">Fecha cumplido</th>
            <th style="width:200px;">Proyecto</th>
            <th style="width:200px;">Observaciones</th>
            <th style="width:20px;">Persona Recepción</th>
            <th style="width:20px;">Fecha Recepción Entrega OP</th>
            <th style="width:20px;">Persona Verificación</th>
            <th style="width:20px;">Fecha Verificación Entrega OP</th>
        </tr>
    </thead>
    <tbody>
        <!-- $val->id_estado-->
        <?php $aux = 0; ?>
        @foreach ($dat as $key => $val)
            <tr>
                <td style="text-align:center;font-weight:bold;">
                    <a href="../../transversal/incidencia/{{$val->incidencia}}" target="_blank">{{strtoupper($val->incidencia)}}</a></td>
                </td>
                <td style="text-align:center;color:blue;">
                <a href="../../selectVehiculo/{{$val->placa}}" >{{strtoupper($val->placa)}}</a></td>
                <td style="text-align:center;">{{$val->tipoVehiculo}}</td>
                <td style="text-align:center;">{{$val->tipoVehiculoCAM}}</td>
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
                <td style="text-align:center;">{{$val->novedad}}</td>             
                <td style="text-align:center;">{{$val->fecha_servidor}}</td>
                <td style="text-align:center;">{{$val->tiempo_estimado}} Hrs</td>            
                <td style="text-align:center;">{{$val->fecha_cumplido}}</td>
                <td style="text-align:center;">{{$val->proyecto}}</td>
                <td style="text-align:center;">{{$val->observaciones}}</td>
                <td style="text-align:center;">{{($val->nombre1 === NULL ? $val->tecnico_entrega . ' ' . $val->nombre2 : $val->tecnico_entrega . ' ' . $val->nombre1 )}}
                </td>
                <td style="text-align:center;">{{$val->fecha_entrega}}
                    <a href="#" style="display: block;    border: 1px solid #337ab7;    padding: 2px;    border-radius: 4px;    margin-top: 5px;" onclick="enviarDatos('{{$val->tecnico_entrega}}','{{$val->incidencia}}')">Ver Entrega Operación</a>
                </td>
                <td style="text-align:center;">{{$val->conductor_entrega . ' ' . $val->nombre3}}
                </td>
                <td style="text-align:center;">{{$val->fecha_verificaciones}}
                    @if($val->conductor_entrega != NULL)
                        <a href="#" style="display: block;    border: 1px solid #337ab7;    padding: 2px;    border-radius: 4px;    margin-top: 5px;" onclick="enviarDatos('{{$val->conductor_entrega}}','{{$val->incidencia}}')">Ver Entrega Operación</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<?php
Session::forget("fecha_inicio");
Session::forget("fecha_corte");
Session::forget("selTipoVehiculo");
Session::forget("selProyectoCliente");
Session::forget("txt_incidencia");

?>