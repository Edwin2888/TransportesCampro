<table id="tbl_gom_estados" class="table table-striped table-bordered" cellspacing="0" width="99%" style="margin-top: 13px;">
  <thead>
      <tr>
          <th style="width:10px;">Proyecto</th>
          <th style="width:10px; !important">WBS</th>
          <th style="width:10px; !important">GOM</th>
          <th style="width:10px; !important">Estado</th>
      </tr>
  </thead>
  <tbody id="tbl_gom_wbs">     
      @for ($j=0; $j < count($data); $j++)
        <tr>
          <td style="text-align:center;">{{$data[$j]->nombre}} ({{$data[$j]->id_proyecto}})</td>
          <td style="text-align:center;">{{$data[$j]->nombre_ws}}</td>
          <td style="text-align:center;">{{$data[$j]->id_gom}}</td>
          @if($permisoGOM == "W")
            <td style="text-align:center;">
              <select class="form-control" data-gom="{{$data[$j]->id_gom}}" data-anterior="{{$data[$j]->id_estado}}" onchange="cambiarEstadoGOM(this);">
                @foreach($estados as $key => $val)
                    @if($val->id_estado_gom == $data[$j]->id_estado)
                        <option value="{{$val->id_estado_gom}}" selected>{{$val->nombre_gom}}</option>
                    @else
                        <option value="{{$val->id_estado_gom}}" >{{$val->nombre_gom}}</option>
                    @endif
                @endforeach
              </select>
             </td>
          @else
            <td style="text-align:center;">{{$data[$j]->nombre_gom}}</td>
          @endif
        </tr>
      @endfor
  </tbody>
</table> 
