
<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

<button class="btn btn-primary  btn-cam-trans btn-sm" id="upload_excel" type="submit" name="consultar" value="consultar">
        <i class="fa fa-upload"></i> &nbsp;&nbsp;Cargar EXCEL
</button>

<button class="btn btn-primary  btn-cam-trans btn-sm" id="gestores" type="submit" name="consultar" value="consultar">
        <i class="fa fa-user"></i> &nbsp;&nbsp;Gestores
</button>

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'consultaFiltroValidador', "method" => "POST"]) !!}

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Estado:</label>
                    <select name="id_estado" id="id_estado" class="form-control" style="max-width:250px;padding:0px;">
                        <option value="-1">Todos</option>
                        <option value="0">No Visitada</option>
                        <option value="1">Visitada</option>                        
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        @if(Session::has('fecha_inicio'))
                            <?php
                                if(Session::get('fecha_inicio') != "")
                                {
                                    $fechaIni = Session::get('fecha_inicio');
                                }
                                else
                                {
                                    $fechaIni = $fecha2;
                                }
                                
                            ?>
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fechaIni}}" name="fecha_inicio" id="fecha_inicio"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @else
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fecha2}}" name="fecha_inicio" id="fecha_inicio"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @endif
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                    </div>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="text_nombre_proyect">Hasta:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="    width: 100%;">
                        @if(Session::has("fecha_corte"))
                            <?php
                                 if(Session::get('fecha_corte') != "")
                                {
                                    $fechaFin = Session::get('fecha_corte');
                                }
                                else
                                {
                                    $fechaFin = $fecha1;
                                }
                            ?>
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fechaFin}}" name="fecha_corte" id="fecha_corte"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @else
                            <input class="form-control" size="16" style="height:30px;" type="text"
                               value="{{$fecha1}}" name="fecha_corte" id="fecha_corte"
                               placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                        @endif
                        <span class="input-group-addon"><i class="fa fa-times"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">NIC:</label>
                    @if(Session::has("nic"))
                            <input name="nic" type="text" class="form-control" id="nic" value="{{Session::get('nic')}}"/>
                    @else
                        <input name="nic" type="text" class="form-control" id="nic" value=""/>
                    @endif
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tarifa recibo:</label>
                    <select name="id_tarifa" id="id_tarifa" class="form-control" style="max-width:250px;padding:0px;">
                    <option value="0">Seleccione</option>
                    @foreach($tipo_recibo as $recib => $val)
                        <option value="{{$val->tarifa_recibo}}">{{$val->tarifa_recibo}}</option>
                    @endforeach
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Cliente:</label>
                    @if(Session::has("cliente"))
                            <input name="cliente" type="text" class="form-control" id="cliente" value="{{Session::get('cliente')}}"/>
                    @else
                        <input name="cliente" type="text" class="form-control" id="cliente" value=""/>
                    @endif
                </div>
        </div>
        
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Gestor:</label>
                    @if(Session::has("id_gestor"))
                        <input name="id_gestor" type="text" class="form-control" id="id_gestor" value="{{Session::get('id_gestor')}}" style="max-width:250px;padding:0px;"/>
                    @else
                        <input name="id_gestor" type="text" class="form-control" id="id_gestor" value="" style="max-width:250px;padding:0px;"/>
                    @endif
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