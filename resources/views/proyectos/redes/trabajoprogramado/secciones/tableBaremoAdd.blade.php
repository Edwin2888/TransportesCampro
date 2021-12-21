
<?php
            $lider;
            $orden;
            $nodo;

  
  $cant = "select count(*) as cantidad from rds_gop_nodos where gom is not null and gom <> '' and id_nodo='".$nodo."'";
  
  
  $respc = DB::select($cant);
  if($respc[0]->cantidad>0){
    $sql="select id_proyecto from rds_gop_ordenes where id_orden='".$orden."';  ";  
    $resp = DB::select($sql);
    $id_proyecto = $resp[0]->id_proyecto;
    
    //echo "aja |".$lider."|".$orden."|".$nodo."|".$id_proyecto."|";
?>
 
    <a class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirPlanillaMobra(this)" title="Planilla MOBRA" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/rds/pdf_planilla_trabajo_programados.php?id_proyecto=<?= $id_proyecto ?>&id_orden=<?= $orden ?>&id_lider=<?= $lider ?>&nodo=<?= $nodo ?>&tipo=1"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i></a>

    <a class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirPlanillaMate(this)" title="Planilla Materiales" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/rds/pdf_planilla_materiales_programados.php?id_orden=<?= $orden ?>&id_lider=<?= $lider ?>&nodo=<?= $nodo ?>&tipo=1"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>


<?php
  }




  $color = "#F5A9A9";

  //if($estado == "E4" && $tipo == "1")
    //$color = "#9FF781";

  if($estado == "C2")
    $color = "#9FF781";

?>
@if($tipoPRY == "T03")
  <span style="display:none">
@else
  <span>
@endif
  <div>

  <label><b>Consecutivo planilla</b></label>
  @if($tipoPRY == "T03")
    <input type="text" value="-1" id="preplanilla_id" class="form-control" style="width: 100px;    display: inline-block;"/>
  @else
    <input type="text" value="{{$pre}}" id="preplanilla_id" class="form-control" style="width: 100px;    display: inline-block;"/>
  @endif
      <small id="emailHelp" class="form-text text-muted">Utiliza este campo para Planilla que generalizan Nodos</small>
      
  <br>
      <div class="form-group">
  <label><b>Consecutivo planilla Gom nodo</b></label>

  <input type="text" value="{{$pred}}" id="preplanilla_id_d" class="form-control" style="width: 100px;    display: inline-block;"/>

       <small id="emailHelp" class="form-text text-muted">Utiliza este campo para Planilla por nodo</small>
</div>
 </div>
<div style="    border: 2px solid #ccc;    border-radius: 6px;    width: 50%;   margin-top: 4px;    padding: 5px;">
  <label><b>Datos del nodo</b></label><br>
  <label>CD</label>
  <input class="form-control" type="text" value="{{$nodosDatos->cd}}" id="cd_nodo" style="width: 65px;    display: inline-block;" />
  <label>PF</label>
  <input class="form-control" type="text" value="{{$nodosDatos->punto_fisico}}" id="pf_nodo" style="width: 65px;    display: inline-block;"/>
  <label>Seccionador</label>
  <input class="form-control" type="text" value="{{$nodosDatos->seccionador}}" id="sec_nodo" style="width: 100px;    display: inline-block;"/>

  <label>Dirección</label>
  <input class="form-control" type="text" value="{{$nodosDatos->direccion}}" id="di_nodo" style="width: 100px;    display: inline-block;"/>

  <div>

    <div class="row">
      <div class="col-md-3">
        <label><b>Descargo 1</b></label>
        <select  type="text" class="form-control" id="descargo_add_1">   
        <option value="0">Seleccione</option>
            @for($i = 0; $i < count($descargos); $i++)
                @if($descargos[$i]->des == $descargolider)
                    <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                @else
                    <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                @endif
            @endfor
        </select>
      </div>

      <div class="col-md-3">
        <label><b>Descargo 2</b></label>
        <select  type="text" class="form-control" id="descargo_add_2">   
        <option value="0">Seleccione</option>
            @for($i = 0; $i < count($descargos); $i++)
                @if($descargos[$i]->des == $descargo2)
                    <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                @else
                    <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                @endif
            @endfor
        </select>
      </div>

      <div class="col-md-3">
        <label><b>Descargo 3</b></label>
        <select  type="text" class="form-control" id="descargo_add_3">   
        <option value="0">Seleccione</option>
            @for($i = 0; $i < count($descargos); $i++)
                @if($descargos[$i]->des == $descargo3)
                    <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                @else
                    <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                @endif
            @endfor
        </select>
      </div>


      <div class="col-md-3">
        <label><b>Descargo 4</b></label>
        <select  type="text" class="form-control" id="descargo_add_4">   
        <option value="0">Seleccione</option>
            @for($i = 0; $i < count($descargos); $i++)
                @if($descargos[$i]->des == $descargo4)
                    <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                @else
                    <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                @endif
            @endfor
        </select>
      </div>

    </div>

    <div class="row">
      <div class="col-md-3">
        <label><b>Descargo 5</b></label>
        <select  type="text" class="form-control" id="descargo_add_5">   
        <option value="0">Seleccione</option>
            @for($i = 0; $i < count($descargos); $i++)
                @if($descargos[$i]->des == $descargo5)
                    <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                @else
                    <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                @endif
            @endfor
        </select>
      </div>

      <div class="col-md-3">
        <label><b>Descargo 6</b></label>
        <select  type="text" class="form-control" id="descargo_add_6">   
        <option value="0">Seleccione</option>
            @for($i = 0; $i < count($descargos); $i++)
                @if($descargos[$i]->des == $descargo6)
                    <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                @else
                    <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                @endif
            @endfor
        </select>
      </div>

      <div class="col-md-3">
        <label><b>Descargo 7</b></label>
        <select  type="text" class="form-control" id="descargo_add_7">   
        <option value="0">Seleccione</option>
            @for($i = 0; $i < count($descargos); $i++)
                @if($descargos[$i]->des == $descargo7)
                    <option value ="{{$descargos[$i]->des}}" selected>{{$descargos[$i]->des}}</option> 
                @else
                    <option value ="{{$descargos[$i]->des}}">{{$descargos[$i]->des}}</option> 
                @endif
            @endfor
        </select>
      </div>


    </div>
  </div>
  <a onclick="saveNodosUpdate('{{$nodosDatos->id_nodo}}')" class="btn btn-primary btn-cam-trans btn-sm" title="Guardar datos de nodos" href="#" style="    margin-top: 9px;    margin-left: 23%;"><i class="fa fa-save" aria-hidden="true"></i> Actualizar datos del nodo</a>
</div>

</span>


<div class="row">
    <div class="col-md-6">
        <h4 style="text-align: center;">Actividades</h4>
        @if($tipo == "1")
          <table id="baremo_add_eje" class="table table-striped table-bordered" cellspacing="0" width="99%">
        @else
          <table id="baremo_add_eje1" class="table table-striped table-bordered" cellspacing="0" width="99%">
        @endif
          <thead>
              <tr>
                  <th style="width:20px;">Baremo</th>
                  <th style="width:100px;">Descripción</th>
                  <th style="width:10px; !important">Cant</th>
                  @if($tipoPRY == "T03")
                    <th style="width:10px; !important">Parcial</th>
                  @endif
                  <th style="width:10px; !important">Programado</th>
                                                  
              </tr>
          </thead>
          @if($tipo == "1")
            <tbody id="tbl_baremos_eje">
          @else
            <tbody id="tbl_baremos_eje1">
          @endif
              @foreach($ejecucionB as $eje => $val)
                  <tr>
                      <td>{{$val->id_baremo}}</td>
                      <td>{{$val->actividad}}
                      </td>
                      @if($color == "#9FF781")    
                        @if(isset($val->cantP))
                          <td><center><input data-tipo="1" data-num="1" onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="  background: {{$color}};  width: 36px;text-align:center;" type="text" value="{{$val->cant}}" readonly /></center></td>
                          @if($tipoPRY == "T03")
                            <td style="width:10px; !important;"> <span style="border: 0.1mm solid black;    padding: 5px;    position: relative;    left: 6px;    top: 7px;    display: inline-block;    width: 40px;    height: 24px;">{{$val->parcial}}</span></td>
                          @endif

                          <td><center><span style="     display: inline-block;    width: 31px;   background: blue;    color: white;    padding: 4px;    margin-right: 3px;    border-radius: 4px;">{{$val->cantP}}</span></center></td>
                        @else
                          <td><center><input data-tipo="1" data-num="1" onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="  background: {{$color}};  width: 36px;text-align:center;" type="text" value="{{$val->cant}}" readonly /></center></td>
                          
                          @if($tipoPRY == "T03")
                            <td style="width:10px; !important;"> <span style="border: 0.1mm solid black;    padding: 5px;    position: relative;    left: 6px;    top: 7px;    display: inline-block;    width: 40px;    height: 24px;">{{$val->parcial}}</span></td>
                          @endif

                          <td><center><span style="     display: inline-block;    width: 31px;   background: blue;    color: white;    padding: 4px;    margin-right: 3px;    border-radius: 4px;">{{$val->cant}}</span></center></td>
                        @endif
                      @else
                          @if(isset($val->cantP))
                            <td><center><input data-tipo="1" data-num="1" onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="  background: {{$color}};  width: 36px;text-align:center;" type="text" value="{{$val->cant}}"/></center></td>

                            @if($tipoPRY == "T03")
                              <td style="width:10px; !important;"> <span style="border: 0.1mm solid black;    padding: 5px;    position: relative;    left: 6px;    top: 7px;    display: inline-block;    width: 40px;    height: 24px;">{{$val->parcial}}</span></td>
                            @endif

                            <td><center><span style="     display: inline-block;    width: 31px;   background: blue;    color: white;    padding: 4px;    margin-right: 3px;    border-radius: 4px;">{{$val->cantP}}</span></center></td>
                          @else

                            <td><center><input data-tipo="1" data-num="1" onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="  background: {{$color}};  width: 36px;text-align:center;" type="text" value="{{$val->cant}}"/></center></td>

                            @if($tipoPRY == "T03")
                              <td style="width:10px; !important;"> <span style="border: 0.1mm solid black;    padding: 5px;    position: relative;    left: 6px;    top: 7px;    display: inline-block;    width: 40px;    height: 24px;">{{$val->parcial}}</span></td>
                            @endif

                            <td><center><span style="     display: inline-block;    width: 31px;   background: blue;    color: white;    padding: 4px;    margin-right: 3px;    border-radius: 4px;">{{$val->cant}}</span></center></td>
                          @endif
                          
                      @endif
                  </tr>
              @endforeach
          </tbody>
      </table> 

      <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" onclick="save_actividades()" >Guardar actividades Nodo</button>

      <button class="btn btn-primary btn-cam-trans btn-sm" type="button" onclick="addActividadNueva()"><i class="fa fa-plus"></i> &nbsp;&nbsp;Agregar Actividad
      </button>
    </div>

    <div class="col-md-6">
        <h4 style="text-align: center;">Materiales</h4>
        @if($tipo == "1")
          <table id="mate_add_eje" class="table table-striped table-bordered" cellspacing="0" width="99%">
        @else
          <table id="mate_add_eje1" class="table table-striped table-bordered" cellspacing="0" width="99%">
        @endif
          <thead>
              <tr>
                  <th style="width:20px;">Material</th>
                  <th style="width:100px;">Descripción</th>
                  <th style="width:10px;!important">IN</th>
                  <th style="width:10px;!important">IRZ</th>
                  <th style="width:10px;!important">RCH</th>
                  <th style="width:10px;!important">RRZ</th>
                  @if($tipoPRY == "T03")
                    <th style="width:10px; !important">Parcial</th>
                  @endif
                  <th style="width:10px; !important">Programado</th>
                                                  
              </tr>
          </thead>
          @if($tipo == "1")
            <tbody id="tbl_mate_eje">
          @else
            <tbody id="tbl_mate_eje1">
          @endif
              @foreach($ejecucionM as $rec => $val)
                  <tr>
                      <td data-cod='{{$val->id_articulo}}'>{{$val->codigo_sap}}</td>
                      <td>{{$val->nombre}}
                      </td>
                      @if($color == "#9FF781")
                        @if($val->fecha_terreno != null && $val->fecha_terreno != "")
                          <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '{{$val->cantP}}'style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->cant}}" readonly /></center></td>
                        @else
                          <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '{{$val->cant1}}'style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->cant}}" readonly /></center></td>
                        @endif
                        <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->irz == '' ? 0 : $val->irz }}" readonly /></center></td>
                        <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->rch == '' ? 0 : $val->rch }}" readonly /></center></td>
                        <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->rrz == '' ? 0 : $val->rrz }}" readonly /></center></td>

                        @if($tipoPRY == "T03")
                              <td style="width:10px !important;"> <span style="border: 0.1mm solid black;    padding: 5px;    position: relative;    left: 6px;    top: 7px;    display: inline-block;    width: 40px;    height: 24px;">{{$val->parcial}}</span></td>
                        @endif

                        @if($val->fecha_terreno != null && $val->fecha_terreno != "")
                            <td><center><span style="     display: inline-block;    width: 31px;   background: blue;    color: white;    padding: 4px;    margin-right: 3px;    border-radius: 4px;">{{$val->cantP}}</span></center></td>
                        @else
                            <td><center><span style="     display: inline-block;    width: 31px;   background: blue;    color: white;    padding: 4px;    margin-right: 3px;    border-radius: 4px;">{{$val->cant}}</span></center></td>
                        @endif

                      @else
                        @if($val->fecha_terreno != null && $val->fecha_terreno != "")
                          <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '{{$val->cantP}}'style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->cant}}"/></center></td>                         
                        @else
                          <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '{{$val->cant1}}'style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->cant}}"/></center></td>                         
                        @endif
                        <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->irz == '' ? 0 : $val->irz }}"/></center></td>
                        <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->rch == '' ? 0 : $val->rch }}"/></center></td>
                        <td><center><input data-num="1" data-tipo="2"  onblur="cambio_estado(this);" onclick="seleccionar(this)" data-max = '1000' style="background:{{$color}};    width: 36px;text-align:center;" type="text" value="{{$val->rrz == '' ? 0 : $val->rrz }}"/></center></td>

                        @if($tipoPRY == "T03")
                              <td style="width:10px !important;"> <span style="border: 0.1mm solid black;    padding: 5px;    position: relative;    left: 6px;    top: 7px;    display: inline-block;    width: 40px;    height: 24px;">{{$val->parcial}}</span></td>
                        @endif

                        @if($val->fecha_terreno != null && $val->fecha_terreno != "")
                            <td><center><span style="     display: inline-block;    width: 31px;   background: blue;    color: white;    padding: 4px;    margin-right: 3px;    border-radius: 4px;">{{$val->cantP}}</span></center></td>
                        @else
                            <td><center><span style="     display: inline-block;    width: 31px;   background: blue;    color: white;    padding: 4px;    margin-right: 3px;    border-radius: 4px;">{{$val->cant}}</span></center></td>
                        @endif

                      @endif
                  </tr>
              @endforeach
          </tbody>
      </table> 

      <form action="{{config('app.Campro')[2]}}/campro/home" method="POST" target="_blank">
          <input type="hidden" name="user" value="{{Session::get('user_login')}}"/>
          <?php
            $url = "{{config('app.Campro')[2]}}/campro/inventarios/" . trim(explode('_',\Session::get('proy_short'))[0]) . "/pop_consumos.php?id_orden=$orden&id_origen=$lider&id_nodo=$nodo";
          ?>
          <input type="hidden" id="url_orden_enviar" name="ruta" value="{{$url}}"/> 
          <input onclick="abrirCSAsociado()" type="submit" style="color:blue;font-size:10px;cursor:pointer;    background: transparent;   padding: 10px;    border-radius: 2px;    border: 1px solid blue;" value="Consumos Adicionales">
       </form>

       <p style="margin-top:5px;"><b>Documentos de consumo asociados</b></p>
        @foreach($cs as $eje => $val)
             <form action="{{config('app.Campro')[2]}}/campro/home" method="POST" target="_blank">
                <input type="hidden" name="user" value="{{Session::get('user_login')}}"/>
                <input type="hidden" name="ruta" value="{{config('app.Campro')[2]}}/campro/inventarios/{{ explode('_',\Session::get('proy_short'))[0]}}/pop_consumos.php?id_orden={{$orden}}&id_origen={{$lider}}&id_nodo={{$nodo}}&id_documento={{$val->id_documento}}"/>
                <input type="submit" style="color:blue;font-size:10px;cursor:pointer;    background: transparent;   padding: 10px;" value="{{$val->id_documento}}">
             </form>
        @endforeach

       

    </div>
</div>


 