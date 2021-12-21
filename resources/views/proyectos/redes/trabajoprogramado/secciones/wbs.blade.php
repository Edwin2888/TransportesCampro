
<table id="contenido" class="table table-striped table-bordered tbl-wbs" cellspacing="0" style="width:100%">
    <thead>
        <tr>
           <!-- <th style="width:100px;"></th>-->
            <th style="width:20px;">NOMBRE WBS</th>
            <th style="width:50px;">EDITAR</th>
        </tr>
    </thead>
    <tbody id="tbl_wbs_content">
        @foreach ($wbs as $wb => $val)
            <tr>
                <!--<td>
                    <div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" style="width:0%">
                        0%
                      </div>
                    </div>
                </td>-->
                <td data-wbs="{{$val->id_ws}}">{{$val->nombre_ws}}</td>
                <td><button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalWBS('{{$val->id_ws}}','{{$val->nombre_ws}}','{{$val->observaciones}}','{{$val->nombre_ws}}')"><i class="fa fa-search" aria-hidden="true"></i></button></td>
            </tr>
        @endforeach
    </tbody>
</table> 