 <table id="contenido_recurso_ver" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="    width: 288px;">DC Asociados</th>
            <th style="width:20px;">MÓVIL</th>
            <th style="width:100px;">TIPO CUADRILLA</th>
            <th style="width:70px;">LÍDER</th>
            <th style="width:200px;">NOMBRE</th>
            <th style="width:200px;">H. INICIO</th>
            <th style="width:200px;">H. FIN</th>
            <th style="width:200px;">Aux. 1</th>
            <th style="width:200px;">Aux. 2</th>
            <th style="width:200px;">Aux. 3</th>
            <th style="width:200px;">Conductor</th>
            <th style="width:200px;">Vehículo</th>
            <th style="width:100px;"></th>                               
        </tr>
    </thead>
    <tbody>
        @foreach($recurso as $rec => $val)
            @if($val->estadoC == 1 && $encabezado[0]->id_estado != "A1")
                    <?php continue;?>
            @endif
            <tr>
                

                <td>

                    @if($encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE" || $encabezado[0]->id_estado == "E1")
                        <div style="position:relative;    left: -35%;" onclick="addCampros('{{$val->lider}}')" class="btn btn-primary btn-cam-trans btn-sm" title="Agregar DC" href="#"><i class="fa fa-plus" aria-hidden="true"></i></div>
                        <div style="position:relative;    left: -35%;" onclick="cambioLider('{{$val->lider}}','{{$val->horaI}}','{{$val->horaF}}','{{$val->tipo}}','{{$val->movil}}','{{$val->lider}}','{{$val->nom1}}')" class="btn btn-primary btn-cam-trans btn-sm" title="Cambio del líder" href="#"><i class="fa fa-user-times" aria-hidden="true"></i></div>
                    @endif

                        @foreach ($numeroDespacho as $key => $val1)
                            @if($val1->id_lider == $val->lider)
                                
                                @if($val1->id_estado == "E1" || $val1->id_estado == "E2")
                                    <p>
                                    <form action="{{config('app.Campro')[2]}}/campro/ini.php" method="POST" target="_blank">
                                        <input type="hidden" name="user" value="{{Session::get('user_login')}}"/>
                                        <input type="hidden" name="ruta" value="{{config('app.Campro')[2]}}/campro/inventarios/{{ explode('_',\Session::get('proy_short'))[0]}}/movimientos.php?id_documento={{$val1->id_documento}}"/>
                                        
                                   
                                        <input type="submit" style="color:blue;font-size:10px;cursor:pointer;    background: transparent;    border: 0px;" value="{{$val1->id_documento}} - {{$val1->nombre}}">
                                        
                                        <b style="color:red;    font-size: 14px;    cursor: pointer;" title="Cancelar DC asignado" onclick="anularDc('{{$val1->id_documento}}')">X</b></p>
                                        
                                     </form>
                                     
                                        
                                     
                                @else
                                    <p>
                                    <form action="{{config('app.Campro')[2]}}/campro/ini.php" method="POST" target="_blank">
                                        <input type="hidden" name="user" value="{{Session::get('user_login')}}"/>
                                        <input type="hidden" name="ruta" value="{{config('app.Campro')[2]}}/campro/inventarios/{{ explode('_',\Session::get('proy_short'))[0]}}/movimientos.php?id_documento={{$val1->id_documento}}"/>
                                        <input type="submit" style="color:blue;font-size:10px;cursor:pointer;    background: transparent;    border: 0px;" value="{{$val1->id_documento}} - {{$val1->nombre}}">

                                        <b style="color:red;    font-size: 14px;    cursor: pointer;" title="Cancelar DC asignado" onclick="anularDc('{{$val1->id_documento}}')">X</b></p>

                                    </form>


                                    </p>
                                @endif
                            @endif
                        @endforeach
                </td>

                <td>{{$val->movil}}</td>
                <td>{{$val->tipo}}</td>
                <td>{{$val->lider}}</td>
                <td>{{$val->nom1}}</td>
                <td>{{$val->horaI}}</td>
                <td>{{$val->horaF}}</td>
                <td>{{$val->aux1 }} <br> {{ $val->nom2}}  <br> 

                @if($encabezado[0]->id_estado == "E1")
                <button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalDatos('{{$val->lider}}','{{$val->aux1}}','{{$val->horaI}}','{{$val->horaF}}',1)" style="width:20px;height:20px"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-refresh" aria-hidden="true"></i></button>
                @endif
                @if($val->aux1 != "" && $val->aux1 != null)
                @if($encabezado[0]->id_estado == "E1")
                    <button class="btn btn-primary btn-cam-cancel btn-sm" onclick="eliminaAuxMatri('{{$val->lider}}',1)" style="width:20px;height:20px"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-trash-o" aria-hidden="true"></i></button>

                @endif
                @endif
                </td>
                <td>{{$val->aux2 }} <br> {{ $val->nom3}} <br> 
                @if($encabezado[0]->id_estado == "E1")
                <button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalDatos('{{$val->lider}}','{{$val->aux2}}','{{$val->horaI}}','{{$val->horaF}}',2)" style="width:20px;height:20px"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-refresh" aria-hidden="true"></i></button>
                @endif

                @if($val->aux2 != "" && $val->aux2 != null)
                @if($encabezado[0]->id_estado == "E1")
                    <button class="btn btn-primary btn-cam-cancel btn-sm" onclick="eliminaAuxMatri('{{$val->lider}}',2)" style="width:20px;height:20px"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-trash-o" aria-hidden="true"></i></button>

                @endif
                @endif
    </td>
                <td>{{$val->aux3 }} <br> {{$val->nom4}} <br> 

                @if($encabezado[0]->id_estado == "E1")
                <button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalDatos('{{$val->lider}}','{{$val->aux3}}','{{$val->horaI}}','{{$val->horaF}}',3)" style="width:20px;height:20px"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-refresh" aria-hidden="true"></i></button>
                @endif

                 @if($val->aux3 != "" && $val->aux3 != null)
                 @if($encabezado[0]->id_estado == "E1")
                    <button class="btn btn-primary btn-cam-cancel btn-sm" onclick="eliminaAuxMatri('{{$val->lider}}',3)" style="width:20px;height:20px"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-trash-o" aria-hidden="true"></i></button>
                @endif
                @endif
    </td>
                <td>{{$val->cond }} <br> {{$val->nom5}} <br> 
                @if($encabezado[0]->id_estado == "E1")
                <button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalDatos('{{$val->lider}}','{{$val->cond}}','{{$val->horaI}}','{{$val->horaF}}',4)" style="width:20px;height:20px"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-refresh" aria-hidden="true"></i></button>
                @endif

                @if($val->cond != "" && $val->cond != null)
                @if($encabezado[0]->id_estado == "E1")
                    <button class="btn btn-primary btn-cam-cancel btn-sm" onclick="eliminaAuxMatri('{{$val->lider}}',4)" style="width:20px;height:20px"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-trash-o" aria-hidden="true"></i></button>
                @endif
                @endif
    </td>
                <td><p>{{$val->matri }} </p>
                @if($encabezado[0]->id_estado == "E1")
                    @if($val->matri == "" || $val->matri == null)
                        <button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalDatos('{{$val->lider}}','{{$val->matri}}','{{$val->horaI}}','{{$val->horaF}}',5)" style="width:20px;height:20px;margin-top:25px;"><i style="    position: relative;
            top: -6px;    left: -3px;"class="fa fa-refresh" aria-hidden="true"></i></button>
                    @else
                        <button class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirModalDatos('{{$val->lider}}','{{$val->matri}}','{{$val->horaI}}','{{$val->horaF}}',5)" style="width:20px;height:20px;margin-top:10px;"><i style="    position: relative;
            top: -6px;    left: -3px;"class="fa fa-refresh" aria-hidden="true"></i></button>
                    @endif
                @endif
                @if($val->matri != "" && $val->matri != null)
                @if($encabezado[0]->id_estado == "E1")
                    <button class="btn btn-primary btn-cam-cancel btn-sm" onclick="eliminaAuxMatri('{{$val->lider}}',5)" style="width:20px;height:20px;margin-top:10px;"><i style="    position: relative;
        top: -6px;    left: -3px;"class="fa fa-trash-o" aria-hidden="true"></i></button>
                @endif
                @endif
    </td>
                <td>
                @if($encabezado[0]->id_estado == "E1" || $encabezado[0]->id_estado == "E2" || $encabezado[0]->id_estado == "R0" || $encabezado[0]->id_estado == "PE")
                <button class="btn btn-primary btn-cam-trans btn-sm" onclick="deleteUserAsignacion('{{$val->lider}}')"><i class="fa fa-trash" aria-hidden="true"></i></button>
                @endif

                
                
                <br>
                
                
                <?php
                    
                     $sqlnodos ="   SELECT rgn.id_nodo,rgn.nombre_nodo
                        FROM
                        rds_gop_ordenes_manoobra as rgom   
                        inner join rds_gop_nodos as rgn  on ( rgom.id_nodo = rgn.id_nodo and  rgn.gom is not null /*and rgn.gom <> 0 */)
                        left join rds_gop_mobra as mobra on (mobra.id_orden = rgom.id_orden
                                and mobra.id_origen = rgom.id_personaCargo)
                        WHERE rgom.id_orden = '".$orden."' and 
                            rgn.id_proyecto='".$proyecto."' 
                        AND rgom.id_estado = 0
                        AND rgom.id_personaCargo = '".$val->lider."'    
                        group by rgn.id_nodo,rgn.nombre_nodo ";
                 //  echo $sqlnodos;
                     $respunod = DB::select("SET NOCOUNT ON;" . $sqlnodos);
                
                     $control =0;
                     foreach ($respunod as $respn){
                         if($control ==0){$control=1;
                             echo "<b>POR NODOS</b>";
                         }
                         echo "<br>".$respn->nombre_nodo."<br>";
                         
                             if($encabezado[0]->id_estado != "A1"){
                                      if($val->actiC > 0){ ?>
                                            <a class="btn btn-primary btn-cam-trans btn-sm" title="Planilla MOBRA"      target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pdf_planilla_trabajo_programados.php?id_proyecto={{$proyecto}}&id_orden={{$orden}}&id_lider={{$val->lider}}&nodo={{$respn->id_nodo}}"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i></a>
                                <?php } 
                                      if($val->mateC > 0){ ?>
                                            <a class="btn btn-primary btn-cam-trans btn-sm" title="Planilla Materiales" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pdf_planilla_materiales_programados.php?id_orden={{$orden}}&id_lider={{$val->lider}}&nodo={{$respn->id_nodo}}"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                <?php }  

                                }
                     
                     }
                     
                    if($control ==0){?>
                         
                        @if($encabezado[0]->id_estado != "A1")
                            @if($val->actiC > 0)
                                <a class="btn btn-primary btn-cam-trans btn-sm" title="Planilla MOBRA" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pdf_planilla_trabajo_programados.php?id_proyecto={{$proyecto}}&id_orden={{$orden}}&id_lider={{$val->lider}}"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i></a>
                            @endif
                            @if($val->mateC > 0)
                                <a class="btn btn-primary btn-cam-trans btn-sm" title="Planilla Materiales" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pdf_planilla_materiales_programados.php?id_orden={{$orden}}&id_lider={{$val->lider}}"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                            @endif
                            <a class="btn btn-primary btn-cam-trans btn-sm" title="Registro fotográfico" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pop_fotos_lider.php?id_orden={{$orden}}&id_lider={{$val->lider}}"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
                        @endif                   
                <?php  }
                ?>  
                
            </tr>


        @endforeach
    </tbody>
</table> 