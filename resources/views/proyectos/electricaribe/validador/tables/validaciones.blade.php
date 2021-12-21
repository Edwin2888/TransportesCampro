<table id="tbl_validaciones" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">Validado</th>
            <th style="width:200px;">GESTOR</th>
            <th style="width:200px;">FECHA VISITA</th>
            <th style="width:200px;">NIC</th>
            <th style="width:20px;">TARIFA_RECIBO</th>
            <th style="width:20px;">CLIENTE</th>
            <th style="width:50px;">Ver</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($validaciones as $valida => $val)
            <tr>
                <td style="text-align:center;">
                    @if($val->visitado == "1")
                        <span style="color:green;font-size:16px;font-weight:bold;">SI</span>
                    @else
                        <span style="color:red;font-size:16px;font-weight:bold;">NO</span>
                    @endif
                </td>
                <td style="text-align:center;color:blue;">{{$val->id_cruce_gestor}} - {{$val->nombre_gestor}}</td>
                <?php
                    $fecha = "";
                ?>
                @if($val->fecha_visita_gestor == NULL || $val->fecha_visita_gestor == "")
                    <td style="text-align:center;"></td>
                @else
                    <?php
                        $fecha = explode("-",$val->fecha_visita_gestor);
                        $fecha = $fecha[2] . "/" . $fecha[1] . "/" . $fecha[0];
                    ?>
                    <td style="text-align:center;">{{$fecha}}</td>
                @endif
               
                <td style="text-align:center;">{{$val->nic}}</td>
                <td style="text-align:center;">{{$val->tarifa_recibo}}</td>
                <td style="text-align:center;">{{$val->cliente}}</td>
               
                
                <td>
                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalNic({{$val->nic}},'{{$val->cliente}}','{{$val->id_cruce_gestor}}','{{$fecha}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>

        @endforeach
    </tbody>
</table>