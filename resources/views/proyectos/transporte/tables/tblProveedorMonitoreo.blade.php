<div class="row">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%">
        <thead>
        <th style="width:200px; text-align: center">
            Código
        </th>
        <th style="width:200px;  text-align: center">
            Nombre proveedor
        </th >
        <th style="width:200px;  text-align: center">
            Editar
        </th>
        </thead>
        <tbody id="tbTblProveedorMonitoreo">
        @foreach($proveedorMonitoreo as $idProveedor => $nombreProveedor)
            <tr>
                <td style="text-align:center;">
                    {{$idProveedor}}
                </td>
                <td style="text-align:center;">
                    {{$nombreProveedor}}
                </td>
                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="editarModal(6,{{$idProveedor}},'{{$nombreProveedor}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
