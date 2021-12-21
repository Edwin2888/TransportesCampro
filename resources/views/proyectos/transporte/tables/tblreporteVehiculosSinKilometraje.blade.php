<table id="tbl_seg_datos" class="table table-striped table-bordered table_center" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">N°</th>
            <th style="width:20px;">Proyecto</th>
            <th style="width:20px;">Placa</th>
            <th style="width:200px;">Tipo vehículo (TP)</th>
            <th style="width:200px;">Tipo CAM</th>
            <th style="width:200px;">Estado vehículo</th>
        </tr>
    </thead>
    <tbody>
        <!-- $val->id_estado-->
        <?php $aux = 0; ?>
        @foreach ($datos as $key => $val)
             <?php $aux++; ?>
            <tr>
                <td style="text-align:center;">{{$aux}}</td>             
                <td style="text-align:center;">{{strtoupper($val->proyecto)}}</td> 
                <td style="text-align:center;color:blue;"><a href="../../selectVehiculo/{{strtoupper($val->placa)}}" >{{strtoupper($val->placa)}}</a></td></td>
                <td style="text-align:center;">{{strtoupper($val->tipoV)}}</td>             
                <td style="text-align:center;">{{strtoupper($val->cam)}}</td>              
                <td style="text-align:center;">{{strtoupper($val->estado)}}</td>         
         
            </tr>
        @endforeach
    </tbody>
</table>

<?php
Session::forget("fecha_inicio_vso");
Session::forget("fecha_fin_vso");
?>