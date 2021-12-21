
<div class="row" style="margin-top:15px;width:96%;margin-left:2%">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%" id="tbl_conceptos">
        <thead>
            <th style="width:20px; text-align: center">
                Placa
            </th>
            <th style="width:20px;  text-align: center">
                Documento referencia
            </th >
            <th style="width:20px;  text-align: center">
                Observaciones
            </th>
            <th style="width:20px;  text-align: center">
                Valor
            </th>
            <th style="width:20px;  text-align: center">
                Recepción
            </th>
            <th style="width:20px;  text-align: center">
                Aprovisionado
            </th>
            <th style="width:20px;  text-align: center">
                Concepto
            </th>
            <th style="width:20px;  text-align: center">
                Proveedor
            </th>
            <th style="width:20px;  text-align: center">
                Contratante
            </th>
            <th style="width:20px;  text-align: center">
                Editar
            </th>
        </thead>
        <tbody id="tbTblCiudades">
        @foreach($causacion as $key => $val)
            <tr>
                <td style="text-align:center;">
                    {{$val->placa}}
                </td>
                <td style="text-align:center;">
                    {{$val->doc_referencia}}
                </td>
                <td style="text-align:center;">
                    {{$val->fecha}}
                </td>
                <td style="text-align:center;">
                    {{$val->observaciones}}
                </td>
                <td style="text-align:center;">
                    {{$val->recepcion}}
                </td>
                <td style="text-align:center;">
                    {{($val->aprovisionado == 1 ? 'SI' : 'NO')}}
                </td>
                <td style="text-align:center;">
                    {{$val->conc}}
                </td>
                <td style="text-align:center;">
                    {{$val->nombre_proveedor}}
                </td>
                <td style="text-align:center;">
                    {{$val->contra}}
                </td>
                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" 
                    onclick="abrirModalEditCausacion('{{$val->placa}}','{{$val->doc_referencia}}',
                    '{{$val->fecha}}','{{$val->observaciones}}','{{$val->valor}}'
                    ,'{{$val->recepcion}}','{{$val->id_proveedor}}','{{$val->aprovisionado}}'
                    ,'{{$val->id_contratante}}','{{$val->id_concepto}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
