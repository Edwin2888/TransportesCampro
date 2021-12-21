<table id="descargos_add" class="table table-striped table-bordered" cellspacing="0" width="99%" style="margin-top: 13px;position: relative;">
  <thead>
      <tr>
          <th style="width:10px;">Descargo</th>
          <th style="width:10px; !important">Estado</th>
      </tr>
  </thead>
  <tbody id="tbl_gom_wbs">  
            @for ($j=0; $j < count($descargos); $j++)
              <tr>
                <td style="text-align:center;">{{$descargos[$j]->des}}</td>
                <td style="text-align:center;">
                  <select class="form-control">
                  	@for ($k=0; $k < count($descargosE); $k++)
                  		@if($descargosE[$k]->id == $descargos[$j]->id)
                  			<option value="{{$descargosE[$k]->id}}" selected>{{$descargosE[$k]->nom}}</option>
                  		@else
                  			<option value="{{$descargosE[$k]->id}}">{{$descargosE[$k]->nom}}</option>
                  		@endif
                  	@endfor	
                  </select>
                </td>
              </tr>
            @endfor
  </tbody>
</table> 


