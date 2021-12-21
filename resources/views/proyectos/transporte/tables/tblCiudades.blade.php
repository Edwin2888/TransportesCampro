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
        <tbody id="tbTblCiudades">
        @foreach($ciudades as $idCiudad => $nombreCiudad)
            <tr>
                <td style="text-align:center;">
                    {{$idCiudad}}
                </td>
                <td style="text-align:center;">
                    {{$nombreCiudad}}
                </td>
                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="editarModal(1,{{$idCiudad}},'{{$nombreCiudad}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
