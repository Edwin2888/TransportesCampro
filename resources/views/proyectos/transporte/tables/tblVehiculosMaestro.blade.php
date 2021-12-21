<table id="tbl_validaciones" class="table table-striped table-bordered table_center" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">No.</th>
            <th style="width:200px;">Estado</th>
            <th style="width:200px;">Placa</th>
            <th style="width:200px;">Pep</th>
            <th style="width:200px;">Responsable</th>
            <th style="width:200px;">Centro Logistico</th>
            <th style="width:200px;">Codigo Tipo vehículo</th>
            <th style="width:200px;">Tipo vehículo (TP)</th>
            <th style="width:200px;">Tipo CAM</th>
            <th style="width:200px;">Clase</th>
            <th style="width:20px;">Marca</th>
            <th style="width:20px;">Modelo</th>
            <th style="width:20px;">Contrato</th>
            <th style="width:20px;">Fecha Fin Contrato</th>
            <th style="width:20px;">Numero activo</th>
            <th style="width:20px;">Tipo Combustible</th>
            <th style="width:20px;">Transmisión</th>
            <th style="width:20px;">Tipo Vinculación</th>
            <th style="width:20px;">Fecha Vinculación</th>
            <th style="width:20px;">Color</th>
            <th style="width:20px;">Línea</th>
            <th style="width:20px;">Cilindraje</th>
            <th style="width:200px;">Ciudad</th>
            <th style="width:200px;">Propietario</th>
			<th style="width:200px;">Ceco</th>
            <th style="width:200px;">Proyecto</th>
            <th style="width:200px;">Canon</th>
            <th style="width:200px;">Usuario inventario</th>
            <th style="width:200px;">Fecha de inventario</th>
            <th style="width:200px;">Usuario última modificación</th>
            <th style="width:200px;">Fecha de última modificación</th>
           <!-- <th style="width:200px;">Fecha Vinculación</th>-->
        </tr>
    </thead>
    <tbody>
        <!-- $val->id_estado-->
        <?php $aux = 0; ?>
        @foreach ($vehiculoData as $key => $val)
            <tr>
                <td><?php echo ++$aux; ?></td>
                <?php
                    $color = "green";
                    if($val->id_estado == "E0")
                        $color = "black";

                ?>
                <td style="text-align:center;font-weight:bold;color:{{$color }}">{{strtoupper($val->nombreEstado)}}</td>
                <td style="text-align:center;color:blue;"><a href="../../selectVehiculo/{{$val->placa}}" class="btn btn-primary btn-cam-trans btn-sm">{{strtoupper($val->placa)}}</a></td>
                <td style="text-align:center;">{{$val->elemento_pep}}</td>
                <td style="text-align:center;">{{$val->responsable}}</td>
                <td style="text-align:center;">{{$val->centro_logistico}}</td>
                <td style="text-align:center;">{{$val->id_tipo_vehiculo}}</td>
                <td style="text-align:center;">{{$val->nombreT}}</td>
                <td style="text-align:center;">{{strtoupper($val->nombreTipoCAM)}}</td>
                <td style="text-align:center;">{{$val->nombreClase}}</td>
                <td style="text-align:center;">{{$val->nombreM}}</td>
                <td style="text-align:center;">{{$val->modelo}}</td>
                <td style="text-align:center;">{{$val->numero_contrato}}</td>
                <td style="text-align:center;">{{$val->fin_validez}}</td>
                <td style="text-align:center;">{{$val->numero_activo_fijo}}</td>
                <td style="text-align:center;">{{$val->nombreCombus}}</td>
                <td style="text-align:center;">{{$val->nombreTran}}</td>
                <td style="text-align:center;">{{$val->nombreV}}</td>
                <td style="text-align:center;">{{$val->fecha}}</td>  
                <td style="text-align:center;">{{$val->color}}</td>
                <td style="text-align:center;">{{$val->linea}}</td>
                <td style="text-align:center;">{{$val->cilindraje}}</td>
                <td style="text-align:center;">{{$val->nombreC}}</td>                
                <td style="text-align:center;">{{$val->cedula}} - {{$val->nombreP}}</td>  
				<td style="text-align:center;">{{$val->nombrececo}}</td>				
                <td style="text-align:center;">{{$val->nombreCon}}</td>   
                <td style="text-align:center;">{{(explode(".00",$val->valor_contrato)[0] == "" ? 0 : explode(".00",$val->valor_contrato)[0])}}</td>   
                <td style="text-align:center;">{{$val->usua_mod}}</td>   
                <td style="text-align:center;">{{$val->fecha_ultima_mod}}</td>   

                <td style="text-align:center;">{{$val->usuarioCambio}}</td>   
                <td style="text-align:center;">{{$val->fecha_servidorCambio}}</td>  
                <!--<td style="text-align:center;">{{$val->fecha}}</td>  -->

            </tr>

        @endforeach
    </tbody>
</table>