<table id="tbl_log_documentos" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
          <th style="width:10px;">No.</th>
          <th style="width:100px;">Tipo documento</th>
          <th style="width:100px;">Referencia</th>
          <th style="width:100px;">Entidad</th>
          <th style="width:100px;">Vencimiento</th> 
          <th style="width:10px;">Desc</th>                                     
        </tr>
    </thead>
      <tbody>
         <?php $aux = 1;?>
        @foreach($logs as $key => $val)
          <tr>
            <td style="text-align:center;">{{$aux}}</td>
            <td style="text-align:center;font-size:10px;">{{$val->nombre_documento}}</td>
            <td style="text-align:center;font-size:10px;">{{$val->referencia}}</td>
            <td style="text-align:center;font-size:10px;">{{$val->entidad}}</td>
            <td style="text-align:center;">{{explode(" ",$val->vencimiento)[0]}}</td>
            <td style="text-align:center;">
            @if($val->direccion_archivo != null)
                  <a href="visor/<?php echo base64_encode($val->direccion1); ?>" target="_black"><i class="fa fa-download" aria-hidden="true"></i></a>
              @endif
            </td>
          </tr>
          <?php $aux++;?>
        @endforeach
    </tbody>
</table> 