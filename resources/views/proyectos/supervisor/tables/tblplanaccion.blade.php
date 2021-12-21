<table id="tbl_inspecciones" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:50px;">Item</th>
            <th style="width:50px;">Análisis de causas</th>
            <th style="width:50px;">Acción</th>
            <th style="width:100px;">Responsable</th>
            <th style="width:100px;">Cédula</th>
            <th style="width:100px;">Fecha límite</th>
            <th style="width:50px;">Fecha cierre</th>
            <th style="width:50px;">Observación cierre</th>
            <th style="width:50px;">Evidencia</th>
            @if($inspeccion->estado <> 'E2')
                <th style="width:50px;">Consulta</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($plan as $key => $val)
            <tr>
                <td style="text-align:center;">{{$val->des_item}}</td>
                <td style="text-align:center;">{{$val->des_analisis}}</td>
                <td style="text-align:center;">{{$val->accion}}</td>
                <td style="text-align:center;">{{$val->responsable}}</td>
                <td style="text-align:center;">{{$val->usuario}}</td>
                <td style="text-align:center;">{{$val->fecha_limite}}</td>
                <td style="text-align:center;">{{$val->fecha_cierre}}</td>
                <td style="text-align:center;">{{$val->observacion_cierre}}</td>
                @if($val->evidencia == "")
                    <td></td>
                @else
                    <td style="text-align:center;"><a target="_blank" href="http://190.60.248.195/anexos_apa/anexos/{{$val->evidencia}}"><i class="fa fa-download" aria-hidden="true" style="cursor:pointer;font-size:1.5em;color:#084A9E"></i></a></td>
                @endif
                @if($inspeccion->resultado == "NC")
                    @if($inspeccion->estado <> 'E2')
                        <td style="text-align:center;"><button onclick="abrirModalActualiza('{{$val->id}}','{{$val->accion}}','{{$val->responsable}}','{{$val->fecha_limite}}','{{$val->fecha_cierre}}','{{$val->observacion_cierre}}','{{$val->item}}','{{$val->id_analisis}}','{{$val->usuario}}')" class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search" aria-hidden="true"></i></button></td>
                    @endif
                @endif
            </tr>
        @endforeach
    </tbody>
</table>