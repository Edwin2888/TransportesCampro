
<table id="tbl_seg_datos" class="table table-striped table-bordered table_center" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">N°</th>
            <th style="width:20px;">PLaca</th>
            <th style="width:20px;">Fecha</th>
            <th style="width:20px;">Observación</th>
            <th style="width:20px;">Kilometraje</th>
            <th style="width:20px;">Proyecto</th>
            <th style="width:200px;">Tipo vehículo (TP)</th>
            <th style="width:200px;">Tipo CAM</th>
            <th style="width:100px;">Eliminar</th>
        </tr>
    </thead>
    <tbody>
        <!-- $val->id_estado-->
        <?php $aux = 0; ?>
        @foreach ($dat as $key => $val)
            <tr>
                <td style="text-align:center;font-weight:bold;">
                    {{++$aux}}
                </td>
                <td style="text-align:center;color:blue;">
                <a href="../../selectVehiculo/{{$val->placa}}" >{{strtoupper($val->placa)}}</a></td>
                <td style="text-align:center;">{{$val->fecha}}</td>
                     
                <td style="text-align:center;">{{$val->observaciones}}</td>             
                <td style="text-align:center;">{{$val->kilometraje}}</td>
                <td style="text-align:center;">{{$val->proyecto}}</td>
                <td style="text-align:center;">{{$val->tipoV}}</td>                
                <td style="text-align:center;">{{$val->tipoVCAM}}</td>
                <td style="text-align:center;color:red">
                  <i  class="fa fa-times" onclick="eliminarOdometro('{{strtoupper($val->placa)}}','{{$val->id}}')" aria-hidden="true" style="    font-size: 27px;    text-align: center;    display: block;cursor:pointer;"></i>
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
Session::forget("txt_placa");

?>