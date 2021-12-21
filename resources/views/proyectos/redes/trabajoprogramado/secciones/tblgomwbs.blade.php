<table id="wbs_gom" class="table table-striped table-bordered" cellspacing="0" width="99%" style="margin-top: 13px;position: relative;left: -38px;">
  <thead>
      <tr>
          <th style="width:10px;">WBS</th>
          <th style="width:10px; !important">GOM</th>
          <th style="width:10px; !important">Estado</th>
          <th style="width:10px; !important"></th>
          <th style="width:10px; !important"></th>
      </tr>
  </thead>
  <tbody id="tbl_gom_wbs">     
    @if(count($gomwbs) == 0)
      <td colspan="5" style="text-align:center;">No existen datos</td>
    @else
            @for ($j=0; $j < count($gomwbs); $j++)
              <tr>
                <td style="text-align:center;">{{$gomwbs[$j]->nombre_ws}}</td>
                <td style="text-align:center;">
                  <span>{{$gomwbs[$j]->id_gom}}</span>
                  <input type="text" value="{{$gomwbs[$j]->id_gom}}" style="display:none;width: 55px;" data-wbs="{{$gomwbs[$j]->id_ws}}"/>
                </td>
                <td style="text-align:center;">
                  @if($gomwbs[$j]->id_estado == 0)
                    DISPONIBLE
                  @else
                    CERRADA
                  @endif
                </td>
                <td style="text-align:center;">
                  @if($gomwbs[$j]->id_estado == 0)
                  <button class="btn btn-primary btn-cam-trans btn-sm" style="padding:0px 3px;" onclick="editGOMWBS(this)"><i class="fa fa-pencil-square" aria-hidden="true" style="font-size:14px;margin-top:3px;"></i></button>
                  @endif
                </td>
                <td style="text-align:center;">
                  @if($gomwbs[$j]->id_estado == 0)
                  <button class="btn btn-primary btn-cam-trans btn-sm" style="padding:0px 3px;" onclick="deleteGOMWBS('{{$gomwbs[$j]->id_ws}}','{{$gomwbs[$j]->id_gom}}')"><i class="fa fa-window-close" aria-hidden="true" style="font-size:14px;margin-top:3px;"></i></button>
                  @endif
                </td>
              </tr>
            @endfor
    @endif
  </tbody>
</table> 
