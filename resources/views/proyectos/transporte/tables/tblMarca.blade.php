<div class="row">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%">
        <thead>
        <th style="width:200px; text-align: center">
            Código
        </th>
        <th style="width:200px;  text-align: center">
            Marca
        </th >
        <th style="width:200px;  text-align: center">
            Editar
        </th>
        </thead>
        <tbody id="tbTblMarca">
        @foreach($marcas as $idMarca => $nombreMarca)
            <tr>
                <td style="text-align:center;">
                    {{$idMarca}}
                </td>
                <td style="text-align:center;">
                    {{$nombreMarca}}
                </td>
                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="editarModal(3,{{$idMarca}},'{{$nombreMarca}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
