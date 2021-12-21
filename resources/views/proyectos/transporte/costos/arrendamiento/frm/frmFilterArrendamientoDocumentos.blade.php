<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'transporte/costos/consultaArrendamientosFilter', "method" => "POST"]) !!}

        
         <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Año:</label>
                    <select class="form-control" id="txt_anio" name="txt_anio">
                        @if($anio == 2021)
                            <option value="2021" selected>2021</option>
                            <option value="2020">2020</option>
                            <option value="2019" >2019</option>
                            <option value="2018">2018</option>
                            <option value="2017" >2017</option>
                        @endif

                        @if($anio == 2020)
                            <option value="2020" selected>2020</option>
                            <option value="2019" >2019</option>
                            <option value="2018">2018</option>
                            <option value="2017" >2017</option>
                        @endif

                        @if($anio == 2019)
                            <option value="2020" >2020</option>
                            <option value="2019" selected>2019</option>
                            <option value="2018">2018</option>
                            <option value="2017" >2017</option>
                        @endif

                        @if($anio == 2018)
                            <option value="2020" >2020</option>
                            <option value="2019" >2019</option>
                            <option value="2018" selected>2018</option>
                            <option value="2017" >2017</option>
                        @endif

                        @if($anio == 2017)
                            <option value="2020" >2020</option>
                            <option value="2019" >2019</option>
                            <option value="2018">2018</option>
                            <option value="2017" selected>2017</option>
                        @endif
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                <label for="id_orden">Mes:</label>
                <select class="form-control" id="txt_mes" name="txt_mes">
                    @if($mes == 1)
                        <option value="1" selected>Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 2)
                        <option value="1" >Enero</option>
                        <option value="2" selected>Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 3)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" selected>Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 4)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4" selected>Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 5)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5" selected>Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 6)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6" selected>Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 7)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7" selected>Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 8)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8" selected>Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 9)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9" selected>Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 10)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10" selected>Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 11)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11" selected>Noviembre</option>
                        <option value="12" >Diciembre</option> 
                    @endif

                    @if($mes == 12)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" >Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" selected>Diciembre</option> 
                    @endif
                </select>
                </div>
        </div>
        
        
         <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Proyecto/Cliente:</label>
                        {!!Form::select('arre_proyecto_ar', $proy, Session::get('arre_proyecto_ar'), ["class"=>"form-control selectWzrd","id"=>"arre_proyecto_ar","placeholder"=>"Seleccione"])!!}
                 
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Tipo de vehículo:</label>
                    {!!Form::select('arre_tipo_veh_ar', $tipoM, Session::get('arre_tipo_veh_ar'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"arre_tipo_veh_ar"])!!}
                </div>
        </div>

        

        <div class="col-md-2">
            <div class="form-group has-feedback">
                <label for="id_orden">Estado documento:</label>
                    {!!Form::select('arre_estado_ar', $estadoM, Session::get('arre_estado_ar'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"arre_estado_ar"])!!}
            </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="txt_placa_filter_ar">Placa:</label>
                    <input type="text" name="txt_placa_filter_ar" id="txt_placa_filter_ar" class="form-control" value="{{Session::get('txt_placa_filter_ar')}}">
                </div>
        </div>

        </div>
        <div class="row">
        
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="txt_doc_filter_ar">Documento:</label>
                    <input type="text" name="txt_doc_filter_ar" id="txt_doc_filter_ar" class="form-control" value="{{Session::get('txt_doc_filter_ar')}}">
                </div>
        </div>

        


        <div class="col-md-1">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" id="consultar" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>
    {!!Form::close()!!}
         <div class="col-md-3">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="abrirModalMasivo()">
                <i class="fa fa-download"></i> &nbsp;&nbsp;Generar masivo de documentos por proveedor
            </button>
        </div>

        <div class="col-md-1">
            <a  style="     margin-top: 23px;"  href="{{url('/')}}/transporte/costos/arrendamientos/{{Session::get('proyecto_user')}}" class="btn btn-primary  btn-cam-trans btn-sm"><i class="fa fa-times" aria-hidden="true"></i>   Cerrar</a>
        </div>
        
    

    </div>

</div>

</div>


<?php
    Session::forget('arre_anio_ar');
    Session::forget('arre_mes_ar');
    Session::forget('arre_proyecto_ar');
    Session::forget('arre_tipo_veh_ar');
    Session::forget('arre_estado_ar');
    Session::forget('txt_doc_filter_ar');
    Session::forget('txt_placa_filter_ar');
?>


