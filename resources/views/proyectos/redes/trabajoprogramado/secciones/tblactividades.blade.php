
<table id="actividades" class="table table-striped table-bordered tbl-wbs" cellspacing="0" style="width:100%;">
    <thead>
        <tr>
            <th style="width:1px;"></th>
            <th style="width:100px;" style="width:20px;">NODO</th>
            <th style="width:200px;" style="width:20px;">BAREMO</th>
            <th style="width:200px;">DESCRIPCIÓN</th>
            <th style="width:200px;" style="width:20px;">CANT</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($actividades as $acti => $val)
            <tr>
                <td>
                    <!--<div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" style="width:0%">
                        0%
                      </div>
                    </div>-->
                </td>
                <td>{{$val->nombre_nodo}}</td>
                <td>{{strtoupper($val->id_baremo)}}</td>
                <td>{{$val->actividad}}</td>
                <td>{{str_replace(".00", "", $val->cantidad_replanteo)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>  


<!--id_ws, id_proyecto, nombre_ws, id_origen, observaciones, gom-->