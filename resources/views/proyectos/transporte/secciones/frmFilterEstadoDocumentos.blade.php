
<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
        <div class="row">
            {!! Form::open(['url' => 'transversal/reportes/reportegeneraldocumentos', "method" => "POST"]) !!}
            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="id_orden">Tipo de vehículo:</label>
                        {!!Form::select('selTipoVehiculoDoc', $tipoM, Session::get('selTipoVehiculoDoc'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selTipoVehiculoDoc"])!!}
                    </div>
            </div>
            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="id_orden">Clase:</label>
                        {!!Form::select('selClaseDoc', $clases, Session::get('selClaseDoc'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selClaseDoc"])!!}
                    </div>
            </div>
            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="id_orden">Proyecto/Cliente:</label>
                        {!!Form::select('selProyectoClienteDoc', $proy, Session::get('selProyectoClienteDoc'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selProyectoCliente"])!!}
                        </select>
                    </div>
            </div>
            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="estado">Estado:<span>*</span></label>
                        {!!Form::select('selEstadoDoc[]', $estado, Session::get('selEstadoDoc'), ["class"=>"form-control selectWzrd selectpicker","id"=>"selEstadoDoc[]","multiple", "required"])!!}
                        </select>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
                <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                    <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
                </button>
            </div>

            <div class="col-md-1">
                <a href="../../transversal/transporte/home" class="btn btn-primary btn-cam-trans btn-sm" style="    margin-left: 0px;    margin-top: 23px;    left: -8%;    position: relative;"><i class="fa fa-times"></i> &nbsp; Regresar</a>
            </div>
        </div>
{!!Form::close()!!}

    

    </div>

</div>