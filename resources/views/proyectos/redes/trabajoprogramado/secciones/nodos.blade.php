
@if($opcTbl != 1)
    <div>
        <span style="    background: #5bc0de;display:inline-block;width:20px;height:20px;"></span> Nodos utilizados en otras ManiObras
        <span style="    background: #cecaca;display:inline-block;width:20px;height:20px;"></span> Nodos utilizados en esta ManiObra
    </div>
@endif


@if($opcTbl == 1)
    <table id="contenido_nodos" class="table table-striped table-bordered" cellspacing="0" width="99%">
@else
    <table id="contenido_nodos" class="table table-striped table-bordered" cellspacing="0" style="position: relative;
    width: 99%;    left: -13px;">
@endif
    <thead>
        <tr>
            @if($opcTbl == 1)
                <th style="width:1px;"></th>
            @else
                <th></th>
            @endif
            <th style="width:20px;">WBS</th>
            <th style="width:100px;">NODO</th>
            <th style="width:70px;">CD</th>
            <th style="width:200px;">DIRECCIÓN</th>
            <th style="width:50px;">PUNTO FISICO</th>
            @if($opcTbl == 1)
                <th style="width:50px;">EDITAR</th>
            @else
                <th style="width:50px;">SECCIONADOR</th>
                <th style="width:50px;">GOM</th>
                <th style="width:50px;">ESTADO GOM</th>
                <th style="display:none;">Estado</th>
            @endif
            
            <th style="width:50px;">GOM NODO</th>
            <th style="width:50px;">Estado GOM NODO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($nodos as $nod => $val)
            @if($opcTbl == 1)
                <tr>
            @else
                <?php $encon = 0;$encon1 = 0;?>
                        @foreach($noddSel as $nod => $val1)
                           @if($val1->nombre_nodo == $val->nombre_nodo)
                                <?php $encon = 1; break;?>
                           @endif
                        @endforeach

                        @if($encon == 0)
                            @foreach($nodosYaUtilizados as $nod => $val1)
                                @if($val1->id_nodo == $val->idN)
                                    <?php $encon1 = 1; break;?>
                                @endif
                            @endforeach
                        

                        @endif
                        @if($encon == 1)
                            <tr class="selected" data-sele="2">
                        @else
                            @if($encon1 == 1)
                                <tr class="selected_1" data-sele="1">   
                            @else
                                <tr data-sele="0">
                            @endif
                            
                        @endif
            @endif

            
                @if($opcTbl == 1)
                <td>
                    <!--<div class="progress">
                      <div class="progress-bar progress-bar-success" role="progressbar" style="width:0%">
                        0%
                      </div>
                    </div>-->
                </td>
                @else
                    <?php
                        $dat = $val->ws . ";" . $val->idN . ";" . $val->nombre_nodo . ";" . $val->cd . ";" . $val->direccion . ";" . $val->punto_fisico . ";" . $val->seccionador . ";" . ($val->id_gom == "" ? 0 : $val->id_gom);
                    ?>
                    @if(count($noddSel) == 0)
                        <td><input type="checkbox" onclick="pintarFila(this)" data-ele="{{$dat}}"/></td>
                    @else
                        <?php $encon = 0;?>
                        @foreach($noddSel as $nod => $val1)
                           @if($val1->nombre_nodo == $val->nombre_nodo)
                                <?php $encon = 1; break;?>
                           @endif
                        @endforeach

                        @if($encon == 1)
                            <td><input type="checkbox" onclick="pintarFila(this)" data-ele="{{$dat}}" checked /></td>
                        @else
                            <td><input type="checkbox" onclick="pintarFila(this)" data-ele="{{$dat}}"/></td>
                        @endif

                    @endif
                    
                    
                    
                @endif
                @if($opcTbl != 1)
                    <td>{{strtoupper($val->nombre_ws)}}</td>
                @else
                    <td>{{strtoupper($val->nombre_ws)}}</td>
                @endif
                <td>{{strtoupper($val->nombre_nodo)}}</td>
                <td>{{$val->cd}}</td>
                <td>{{strtoupper($val->direccion)}}</td>
                <td>{{strtoupper($val->punto_fisico)}}</td>
                
                @if($opcTbl == 1)
                <td><button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalNODOS(this)"
                data-wbs="{{$val->ws}}" data-nodonumero="{{$val->idN}}" data-nodo="{{$val->nombre_nodo}}" data-cd="{{$val->cd}}" data-dire="{{$val->direccion}}" 
                data-obser="{{$val->observaciones}}" data-nv="{{$val->nivel_tension}}" data-pf="{{$val->punto_fisico}}"
                data-sec="{{$val->seccionador}}" data-gom="{{$val->gom}}"  data-id_estado_gom="{{$val->id_estado_gom}}"
                ><i class="fa fa-search" aria-hidden="true"></i></button></td>
                @else
                    <td>{{strtoupper($val->seccionador)}}</td>
                    <td>{{strtoupper($val->id_gom)}}</td>
                    @if($val->id_gom != "" && $val->id_gom != null)
                        <td>{{strtoupper( ($val->estado_gom == 0 ? "SOLICITADO" : $val->nombre_gom ))}}</td>
                    @else
                        <td>{{strtoupper($val->nombre_gom)}}</td>
                    @endif
                    
                    <td style="display:none">{{$val->estado_gom}}</td>
                @endif
               
                <td>{{strtoupper($val->gom)}}</td>
                <?php /* <td>{{strtoupper($val->descripcion)}}</td> */ ?>
                <td>{{strtoupper($val->id_estado_gom)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>  


<!--id_ws, id_proyecto, nombre_ws, id_origen, observaciones, gom-->