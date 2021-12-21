<div class="row">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%">
        <thead>
        <th style="width:200px; text-align: center">
            Código
        </th>
        <th style="width:200px;  text-align: center">
            Tipo de vehículo
        </th >
        <th style="width:200px;  text-align: center">
            Editar
        </th>
        </thead>
        <tbody id="tbTblVehiculo">  
        @foreach($tipoVehiculos as $key => $val)
            <tr>
                <td style="text-align:center;">
                    {{$val->id_tipo_vehiculo}}
                </td>
                <td style="text-align:center;">
                    {{$val->nombre}}
                </td>
                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" onclick="editarModal(2,{{$val->id_tipo_vehiculo}},'{{$val->nombre}}')"><i class="fa fa-search" aria-hidden="true"></i></button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
