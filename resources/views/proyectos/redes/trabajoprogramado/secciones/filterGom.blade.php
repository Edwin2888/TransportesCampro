<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>



<div class="dropdown" style="    width: 104px;    display: inline-block;">
  <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-random"></i>  Gestionar
  <span class="caret"></span></button>
  <ul class="dropdown-menu" style="padding-bottom:0px;">
    
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/home"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar Proyectos</a></li>
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/orden"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar ManiObras</a></li>
        <li><a style="width:100%;color:#0060AC;" href="../../redes/ordenes/documentos"   class="btn btn-primary btn-cam-trans btn-sm"><i class="fa fa-search"></i> &nbsp;&nbsp; Gestionar Solicitudes de Materiales</a>    </li>
  </ul>
</div>



<div class="dropdown" style="    width: 124px;    display: inline-block;">
  <button class="btn btn-primary btn-cam-trans btn-sm dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-calendar-check-o"></i>  Last Planner
  <span class="caret"></span></button>
  <ul class="dropdown-menu" style="padding-bottom:0px;">
    
    <li><a style="width:100%;color:#0060AC" target="_blank" href="../../ganntProyectos" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir"><i class="fa fa-file-o"></i> &nbsp;&nbsp; Gannt - Kanban Last Planner</a></li>
    <li><a style="width:100%;color:#0060AC" target="_blank" href="../../restricciones" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir"><i class="fa fa-file-o"></i> &nbsp;&nbsp; Restricciones Last Planner</a></li>
    <li><a style="width:100%;color:#0060AC" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/rds/gantt/" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir"><i class="fa fa-file-o"></i> &nbsp;&nbsp; Gannt - Uso capacidad operativa</a></li>

  </ul>
</div>






<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">


    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
    {!! Form::open(['url' => 'transversal/consultar/gomsfilter', "method" => "POST"]) !!}
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Estado gom:</label>
                    <select name="estado_gom" id="estado_gom" class="form-control" style="max-width:250px;padding:0px;" onchange="cambioProyecto()">

                        <option value="" selected>-- Seleccione -- </option>

                        @foreach($estados as $key => $val)
                            @if(Session::get("estado_gom") == $val->id_estado_gom)
                                <option value="{{$val->id_estado_gom}}" selected>{{$val->nombre_gom}}</option>
                            @else
                                <option value="{{$val->id_estado_gom}}" >{{$val->nombre_gom}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
        </div>

        

        
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="numero_gom">Número de GOM:</label>
                        <input name="numero_gom" type="text" class="form-control" id="numero_gom" value="{{Session::get('numero_gom')}}"/>
                </div>
        </div>
        

       
        <div class="col-md-2">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>
    {!!Form::close()!!}

    </div>

</div>

</div>
