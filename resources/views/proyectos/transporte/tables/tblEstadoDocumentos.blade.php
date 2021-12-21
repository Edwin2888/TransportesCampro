<table id="tbl_seguimiento_mantenimientos" class="table table-striped table-bordered table_center" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">Ceco</th>
            <th style="width:20px;">Proyecto</th>
            <th style="width:20px;">Propietario</th>
            <th style="width:20px;">Tipo Vinculacion</th>
            <th style="width:20px;">Placa</th>
            <th style="width:20px;">Id_estado</th>
            <th style="width:200px;">Tipo vehículo (TP)</th>
            <th style="width:200px;">Tipo CAM</th>
            <th style="width:20px;">Clase de vehículo</th>
            <th style="width:20px;">Documento</th>
            <th style="width:20px;">Actual</th>
            <th style="width:20px;">Vence</th>
        </tr>
    </thead>
    <tbody>
        <!-- $val->id_estado-->
        <?php $aux = 0; ?>
        @if(isset($dat) && $dat != null)
            @foreach ($dat as $key => $val)
                <tr>
                    <td style="text-align:center;">{{$val->ceco}}</td>
                    <td style="text-align:center;">{{$val->proyecto}}</td>
                    <td style="text-align:center;">{{$val->propietario}}</td>
                    <td style="text-align:center;">{{$val->vinculacion}}</td>
                    <td style="text-align:center;color:blue;">
                        <a href="../../selectVehiculo/{{$val->placa}}">{{strtoupper($val->placa)}}</a>
                    </td>
                    <td style="text-align:center;">{{$val->nombre}}</td>
                    <td style="text-align:center;">{{$val->tipo_vehiculo}}</td>
                    <td style="text-align:center;">{{strtoupper($val->tipo_vehiculoCAM)}}</td>
                    <td style="text-align:center;">{{$val->clase_vehiculo}}</td>                
                    <td style="text-align:center;">{{$val->documento}}</td>
                    <td style="text-align:center;">{{$val->actual}}</td>
                    <td style="text-align:center;">{{$val->fecha_vence}}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<?php
Session::forget("selTipoVehiculoDoc");
Session::forget("selClaseDoc");
Session::forget("selProyectoClienteDoc");

?>