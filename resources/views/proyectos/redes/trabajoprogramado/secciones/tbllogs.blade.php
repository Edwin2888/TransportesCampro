<table id="tbl_log" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:20px;">Tipo</th>
            <th style="width:20px;">Fecha</th>
            <th style="width:100px;">Usuario</th>
            <th style="width:10px;!important">Dato Modificado</th>
            <th style="width:10px;!important">Acción</th>                                            
        </tr>
    </thead>
      <tbody>
        @foreach($logs as $log => $val)
          <tr>
            <td>{{$val->nombre}}</td>
            <td>{{explode(".",$val->fecha)[0]}}</td>
            <td>{{$val->propietario}}</td>
            <td>{{$val->campo_valor}}</td>
            <td>{{$val->descripcion}}</td>
          </tr>
        @endforeach
    </tbody>
</table> 