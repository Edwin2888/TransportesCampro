<hr>

<?php 

    if(isset($gom_nodos)) { ?>
        <table id="gom_cam" class="table table-striped table-bordered" cellspacing="0" style="margin-top:10px;widht:100%;">
          <thead>
              <tr>
                  <th>Proyecto</th>
                  <th>WBS</th>
                  <th>Nodo</th>
                  <th>GOM</th>
              </tr>
          </thead>
          <tbody>
             @foreach ($gom_nodos as $go => $val)
              <tr>
                  <td style="text-align:center;">{{$val->nombre}}</td>
                  <td style="text-align:center;">{{$val->nombre_ws}}</td>
                  <td style="text-align:center;">{{$val->nombre_nodo}}</td>
                  <td style="text-align:center;">{{$val->gom}}</td>
              </tr>
             @endforeach
          </tbody>
      </table>  
    <? }
    else { ?>
      <table id="gom_cam" class="table table-striped table-bordered" cellspacing="0" style="margin-top:10px;widht:100%;">
        <thead>
            <tr>
                <th>Proyecto</th>
                <th>WBS</th>
                <th>GOM</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
           @foreach ($gom as $go => $val)
            <tr>
                <td style="text-align:center;">{{$val->nombre}}</td>
                <td style="text-align:center;">{{$val->nombre_ws}}</td>
                <td style="text-align:center;">{{$val->id_gom}}</td>
                <td style="text-align:center;">
                    <select>
                    @if($val->id_estado == 0)
                        <option value="0" selected>Abierta</option>
                        <option value="1">Confirmada</option>
                        <option value="2">Facturada</option>
                    @elseif($val->id_estado == 1)
                        <option value="0" >Abierta</option>
                        <option value="1" selected>Confirmada</option>
                        <option value="2">Facturada</option>
                    @else
                        <option value="0" >Abierta</option>
                        <option value="1" >Confirmada</option>
                        <option value="2" selected>Facturada</option>
                    @endif
                    </select>
                </td>
            </tr>
           @endforeach
        </tbody>
      </table>  
    <?php }
?>
