<div style="margin-top:20px"></div>
<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

<button class="btn btn-primary  btn-cam-trans btn-sm" id="btnCrearManual" type="submit" name="consultar" value="consultar">
        <i class="fa fa-plus"></i> &nbsp;&nbsp;Crear Manual
</button>

<a href="../../manuales"  class="btn btn-primary btn-cam-trans btn-sm">&nbsp;&nbsp; Cerrar</a>

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'consultaFilterManual', "method" => "POST"]) !!}

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Proyecto:</label>
                    <select name="id_estado" id="id_estado" class="form-control" style="max-width:250px;padding:0px;">
                        <option value="-1">Todos</option>

                        @if(Session::has("estado"))
                             @if(Session::get("estado") =="T01")
                                    <option value="T01" selected>RECURSOS HUMANOS</option>
                                    <option value="T02" >SUPERVISIÓN Y SST</option>
                                    <option value="T03" >TRANSPORTES</option>
                                    <option value="T04" >HERRAMIENTAS</option>
                             @endif

                             @if(Session::get("estado") =="T02")
                                    <option value="T01" >RECURSOS HUMANOS</option>
                                    <option value="T02" selected>SUPERVISIÓN Y SST</option>
                                    <option value="T03" >TRANSPORTES</option>
                                    <option value="T04" >HERRAMIENTAS</option>
                             @endif

                             @if(Session::get("estado") =="T03")
                                     <option value="T01" >RECURSOS HUMANOS</option>
                                    <option value="T02" >SUPERVISIÓN Y SST</option>
                                    <option value="T03" selected>TRANSPORTES</option>
                                    <option value="T04" >HERRAMIENTAS</option>
                             @endif

                             @if(Session::get("estado") =="T04")
                                    <option value="T01" >RECURSOS HUMANOS</option>
                                    <option value="T02" >SUPERVISIÓN Y SST</option>
                                    <option value="T03" >TRANSPORTES</option>
                                    <option value="T04" selected>HERRAMIENTAS</option>
                             @endif
                        @else
                            <option value="T01" >RECURSOS HUMANOS</option>
                            <option value="T02" >SUPERVISIÓN Y SST</option>
                            <option value="T03" >TRANSPORTES</option>
                            <option value="T04" >HERRAMIENTAS</option>
                        @endif
                        @foreach($proyecto as $key => $valor)
                            @if(Session::has("estado"))
                                @if(Session::get("estado") == $valor->prefijo_db)
                                    <option value="{{$valor->prefijo_db}}" selected>{{$valor->proyecto}}</option>
                                @else
                                    <option value="{{$valor->prefijo_db}}">{{$valor->proyecto}}</option>
                                @endif
                            @else
                                <option value="{{$valor->prefijo_db}}">{{$valor->proyecto}}</option>
                            @endif
                        @endforeach                       
                    </select>
                </div>
        </div>


        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Nombre manual:</label>
                    @if(Session::has("nombre_manual"))
                            <input name="nombre_manual" type="text" class="form-control" id="nombre_manual" value="{{Session::get('nombre_manual')}}"/>
                    @else
                        <input name="nombre_manual" type="text" class="form-control" id="nombre_manual" value=""/>
                    @endif
                </div>
        </div>


        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tipo de manual:</label>
                    <select name="select_filter_tipo_manual" id="select_filter_tipo_manual" class="form-control">
                        @if(Session::has("tipo"))
                            @if(Session::get("tipo") == 0)
                                <option value="0" selected>Todos</option>
                                <option value="1">Aplicación web</option>
                                <option value="2">Aplicación móvil</option>
                            @endif

                            @if(Session::get("tipo") == 1)
                                <option value="0" >Todos</option>
                                <option value="1" selected>Aplicación web</option>
                                <option value="2">Aplicación móvil</option>
                            @endif

                            @if(Session::get("tipo") == 2)
                                <option value="0" >Todos</option>
                                <option value="1" >Aplicación web</option>
                                <option value="2" selected>Aplicación móvil</option>
                            @endif
                        @else
                            <option value="0">Todos</option>
                            <option value="1">Aplicación web</option>
                            <option value="2">Aplicación móvil</option>
                        @endif
                        
                </select>
                </div>
        </div>



        <div class="col-md-2">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>

    {!!Form::close()!!}

    </div>

</div>

</div>