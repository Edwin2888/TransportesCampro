
{!! Form::open(['url' => 'transporte/costos/conceptos/delete', "method" => "POST", "id" => "id_formulario_delete"]) !!}
    <input type="hidden" value="" id="id_costo_delete" name="id">
{!!Form::close()!!}

<div class="row" style="margin-top:15px;width:96%;margin-left:2%">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%" id="tbl_conceptos">
        <thead>
            <th style="width:200px; text-align: center">
                Nombre
            </th>
            <th style="width:200px;  text-align: center">
                Descripción
            </th >
            <th style="width:200px;  text-align: center">
                Estado
            </th>
            <th style="width:200px;  text-align: center">
                Editar
            </th>
        </thead>
        <tbody id="tbTblCiudades">
        @foreach($conceptos as $key => $val)
            <tr>
                <td style="text-align:center;">
                    {{$val->nombre}}
                </td>
                <td style="text-align:center;">
                    {{$val->descripcion}}
                </td>
                <td style="text-align:center;">
                    {{$val->id_estado}}
                </td>
                <td style="text-align:center;">
                    <button class="btn btn-primary btn-cam-trans btn-sm" 
                    onclick="abrirModalEditConcepto('{{$val->nombre}}','{{$val->descripcion}}',
                    '{{$val->id_estado}}','{{$val->id}}','{{$val->anexo_obligatorio}}')"><i class="fa fa-search" aria-hidden="true"></i></button>

                    <button class="btn btn-primary btn-cam-trans btn-sm" 
                    onclick="deleteConcepto('{{$val->id}}')" style="color: red;    border-color: red;"><i class="fa fa-times" aria-hidden="true"></i></button>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
