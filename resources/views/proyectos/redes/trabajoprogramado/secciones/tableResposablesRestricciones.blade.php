<table id="tbl_restric" class="table table-striped table-bordered" cellspacing="0" width="99%">
  <thead>
      <tr>          
          <th style="width:100px; !important">ID</th>
          <th style="width:200px;">Nombre</th>
          <th style="width:200px;">Correo</th>
          <th style="width:10px; !important"></th>
      </tr>
  </thead>
  <tbody id="tbl_body_responsable">
        @foreach ($res as $res => $val)
          <tr>
              <td>{{$val->id}}</td>
              <td>{{$val->nombre}}</td>
              <td>{{$val->correo}}</td>
              <td><i class="fa fa-edit" onclick="updateResp(this)"></i></td>
          </tr>
        @endforeach
  </tbody>
</table> 


