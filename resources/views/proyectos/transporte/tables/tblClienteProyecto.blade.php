<div class="row">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%">
        <thead>
        <th style="width:200px; text-align: center">
            Código
        </th>
        <th style="width:200px;  text-align: center">
            Nombre
        </th >
        <th style="width:200px;  text-align: center">
            Prefijo
        </th >
        <th style="width:200px;  text-align: center">
            Línea
        </th >
        <th style="width:200px;  text-align: center">
            Centro de costo
        </th >
        <th style="width:200px;  text-align: center">
            Op
        </th >
        <th style="width:200px;  text-align: center">
            Adicional
        </th >

        <th style="width:200px;  text-align: center">
            Editar
        </th>
        </thead>
        <tbody id="tbTblClienteProyecto">
        @foreach($clienteProyecto as $idProveedor => $nombreProveedor)
            <tr>
                <td style="text-align:center;">
                    {{$nombreProveedor->id}}
                </td>
                <td style="text-align:center;">
                    {{$nombreProveedor->nombre}}
                </td>
                <td style="text-align:center;">
                    {{$nombreProveedor->prefijo}}
                </td>
                <td style="text-align:center;">
                    {{$nombreProveedor->ln}}
                </td>
                <td style="text-align:center;">
                    {{$nombreProveedor->ceco}}
                </td>
                <td style="text-align:center;">
                    {{$nombreProveedor->op}}
                </td>
                <td style="text-align:center;">
                    {{$nombreProveedor->adicional}}
                </td>

                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="editarModal(7,{{$nombreProveedor->id}},'{{$nombreProveedor->nombre}}','{{$nombreProveedor->prefijo}}','{{$nombreProveedor->ceco}}','{{$nombreProveedor->ln}}','{{$nombreProveedor->op}}','{{$nombreProveedor->adicional}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
