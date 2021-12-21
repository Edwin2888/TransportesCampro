
<table id="materiales" class="table table-striped table-bordered tbl-wbs" cellspacing="0" style="width:100%;">
    <thead>
        <tr>
            <th style="width:1px;"></th>
            <th style="width:100px;">NODO</th>
            <th style="width:200px;">MATERIAL</th>
            <th style="width:200px;">DESCRIPCIÓN</th>
            <th style="width:200px;">CANT</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($material as $mate => $val)
            <tr>
                <td>
                    <!--<div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" style="width:0%">
                        0%
                      </div>
                    </div>-->
                </td>
                <td>{{$val->nombre_nodo}}</td>
                <td>{{$val->codigo_sap}}</td>
                <td style="padding: 18px;">{{$val->nombre}}</td>
                <td>{{str_replace(".00", "", $val->cantidad_replanteo)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>  


<!--id_ws, id_proyecto, nombre_ws, id_origen, observaciones, gom-->