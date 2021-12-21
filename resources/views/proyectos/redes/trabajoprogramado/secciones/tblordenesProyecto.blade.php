
<table id="ordenes_proyecto" class="table table-striped table-bordered" cellspacing="0" style="margin-top:10px;">
    <thead>
        <tr>
            @if(isset($opc))
                <th style="width:150px;">Proyecto</th>
            @endif
            <th style="width:100px;">ORDEN DE MANIOBRAS</th>
            @if(isset($opc))
                @if($opc == "3")
                    <th style="width:150px;">Documento</th>
                @endif
            @endif
            <th style="width:100px;">Estado</th>
            <th style="width:80px;">WBS</th>
            <th style="width:80px;">NODOS</th>
            <th style="width:80px;">GOM</th>
            <th style="width:80px;">DESCARGO</th>
            <th style="width:200px;">Fecha Emisión</th>
            <th style="width:200px;">Fecha Programación</th>
            <th style="width:200px;">Fecha Ejecución</th>
            <th style="width:200px;">CD</th>
            <th style="width:200px;">Nivel Tensión</th>
            <th style="width:200px;">Dirección</th>
            @if(isset($opc))
                @if($opc == "2")
                    <!--<th style="width:200px;">Colaborador</th>-->
                    <!--<th style="width:200px;">Programado</th>-->
                @endif
            @else
                <th style="width:200px;">Colaborador</th>
            @endif
            
            <?php  /*if(Session::has('estadonodo') && Session::get('estadonodo')!="0" ){ ?>
                <th style="width:200px;">Estado NODO</th>
            <?php  }*/ ?>
            <td></td>
        </tr>
    </thead>
    <tbody>
        @if($ordenesRealizada != null)
        @foreach ($ordenesRealizada as $orden => $val)
            <tr>
                @if(isset($opc))
                        <td style="text-align:center;">{{$val[0]->nombreP}}({{str_replace("PRY000","",str_replace("PRY0000","",$val[0]->id_proyecto))}})</td>
                @endif
                <td>{{$val[0]->id_orden}}</td>
                @if(isset($opc))
                    @if($opc == "3")
                        <td>
                        <form action="{{config('app.Campro')[2]}}/campro/home" method="POST" target="_blank">
                            <input type="hidden" name="user" value="{{Session::get('user_login')}}"/>
                            <input type="hidden" name="ruta" value="{{config('app.Campro')[2]}}/campro/inventarios/{{ explode('_',\Session::get('proy_short'))[0]}}/movimientos.php?id_documento={{$val[0]->id_documento}} "/>
                            <input type="submit" style="color:blue;font-size:10px;cursor:pointer;    background: transparent;    border: 0px;" value="{{$val[0]->id_documento}} ">
                         </form>
                        </td>
                    @endif
                @endif
                <td>{{$val[0]->id_estadoN}}</td>
                <td>{{$val[0]->wbs_utilzadas}}</td>
                <td>{{$val[0]->nodos_utilizados}}</td>
                <td>{{$val[0]->gom}}</td>
                <td>{{($val[0]->descargo == 0 ? '' : $val[0]->descargo)}}</td>
                <td>{{explode(" ",$val[0]->fecha_emision)[0]}}</td>
                <td>{{explode(" ",$val[0]->fecha_programacion)[0]}}</td>
                <td>{{explode(" ",$val[0]->fecha_ejecucion)[0]}}</td>
                <td>{{$val[0]->cd}}</td>
                <td>
                    @if($val[0]->nivel_tension == 0)
                        MT
                    @else
                        BT
                    @endif
                </td>
                <td>{{$val[0]->direccion}}</td>
                @if(isset($opc))
                    @if($opc == "2")
                        <!--<td style="font-size:10px;">
                                $val[0]->id_lider - $val[0]->nombre
                        </td>
                        <td>
                            number_format($val[0]->cantidad,2)
                        </td>-->
                        @endif
                @else
                    <td style="font-size:10px;">
                            @if(count($val[1]) > 0)
                                {{$val[1][0]->id_lider}} - {{$val[1][0]->nombre}}
                            @endif
                    </td>
                @endif

                
                
                
                <?php /* if(Session::has('estadonodo') && Session::get('estadonodo')!="0" ){ ?>
                    <td>
                        <?= $val[0]->id_estado_nodo ?>
                    </td>
                 <?php  }*/ ?>
                
                
                
                <td>
                    @if(session('user_login') != 'U04172')
                     @if(isset($opc))
                        <a href="{{url('/')}}/redes/ordenes/ordentrabajo/{{$val[0]->id_proyecto}}/{{$val[0]->id_orden}}" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-search"></i></a>
                     @else
                        <a href="{{url('/')}}/redes/ordenes/ordentrabajo/{{$proyec}}/{{$val[0]->id_orden}}" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-search"></i></a>
                     @endif
                    @endif
                    
                </td>
            </tr>
        @endforeach
        @endif
    </tbody>
</table>  


<!--id_ws, id_proyecto, nombre_ws, id_origen, observaciones, gom-->