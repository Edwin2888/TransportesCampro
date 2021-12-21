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
                Editar
            </th>
        </thead>
        <tbody id="tbTblPropitarios">
        @foreach($propietarios as $idPro => $nombrePro)
            <tr>
                <td style="text-align:center;">
                    {{$idPro}}
                </td>
                <td style="text-align:center;">
                    {{$nombrePro}}
                </td>
                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="editarModal(4,{{$idPro}},'{{$nombrePro}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
