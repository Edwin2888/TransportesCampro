<div class="row">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%">
        <thead>
        <th style="width:200px;  text-align: center">
            Nombre
        </th >
        <th style="width:200px;  text-align: center">
            Editar
        </th>
        </thead>
        <tbody id="tbTblDocumentos">
        @foreach($documentos as $idDocumento => $nombreDocumento)
            <tr> 
                <td style="text-align:center;" data-id="{{$idDocumento}}">
                    {{$nombreDocumento}}
                </td>
                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="editarModal({{$idDocumento}},'{{$nombreDocumento}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
