

<button data-toggle="collapse" data-target="#filter" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
    <i class="fa fa-filter"></i> &nbsp;&nbsp;Filtrar
</button>

{!! Form::open(['url' => 'transversal/supervision/conformacion/create', "method" => "GET", "style" => "display:inline-block"]) !!}
	<input type="submit" value="Crear plan de supervisión" class="btn btn-primary  btn-cam-trans btn-sm"/>
{!!Form::close()!!}

<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">

    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    
{!! Form::open(['url' => 'transversal/supervision/conformacion/filter', "method" => "POST"]) !!}
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Año:</label>
                    <select class="form-control" id="txt_anio" name="txtanio">
                    @if(Session::has('anio'))
                        @if(Session::get('anio') == 2021)
                            <option value="2021" selected>2021</option>
                            <option value="2020" >2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                        @endif

                        @if(Session::get('anio') == 2020)
                            <option value="2020" selected>2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                        @endif
                        

                        @if(Session::get('anio') == 2019)
                            <option value="2020" >2020</option>
                            <option value="2019" selected>2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                        @endif

                        @if(Session::get('anio') == 2018)
                            <option value="2020" >2020</option>
                            <option value="2019" >2019</option>
                            <option value="2018" selected>2018</option>
                            <option value="2017">2017</option>
                        @endif

                        @if(Session::get('anio') == 2017)
                            <option value="2020" >2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017" selected>2017</option>
                        @endif
                    @else
                        @if($anio == 2021)
                            <option value="2021" selected>2021</option>
                            <option value="2020" >2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                        @endif
                        @if($anio == 2020)
                            <option value="2020" selected>2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                        @endif
                        

                        @if($anio == 2019)
                            <option value="2020" >2020</option>
                            <option value="2019" selected>2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                        @endif

                        @if($anio == 2018)
                            <option value="2020" >2020</option>
                            <option value="2019" >2019</option>
                            <option value="2018" selected>2018</option>
                            <option value="2017">2017</option>
                        @endif

                        @if($anio == 2017)
                            <option value="2020" >2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017" selected>2017</option>
                        @endif
                    @endif
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="text_nombre_proyect">Mes:</label>
                    <select class="form-control" id="txtmes" name="txtmes">
                    @if(Session::has('mes'))
                        @if(Session::get('mes') == 1)
                            <option value="1" selected>Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 2)
                            <option value="1" >Enero</option>
                            <option value="2" selected>Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 3)
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
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 4)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4" selected>Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 5)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5" selected>Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 6)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6" selected>Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 7)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7" selected>Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 8)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8" selected>Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 9)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9" selected>Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 10)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10" selected>Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 11)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11" selected>Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if(Session::get('mes') == 12)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
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
                    @else
                        @if($mes == 1)
                            <option value="1" selected>Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 2)
                            <option value="1" >Enero</option>
                            <option value="2" selected>Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
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
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 4)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4" selected>Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 5)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5" selected>Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 6)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6" selected>Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 7)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7" selected>Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 8)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8" selected>Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 9)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9" selected>Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 10)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10" selected>Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 11)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11" selected>Noviembre</option>
                            <option value="12">Diciembre</option>  
                        @endif

                        @if($mes == 12)
                            <option value="1" >Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
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
                    @endif                
                    </select>
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Líder:</label>
                    @if(Session::has("lider"))
                            <input name="txtlider" type="text" class="form-control" id="txtlider" value="{{Session::get('lider')}}"/>
                    @else
                        <input name="txtlider" type="text" class="form-control" id="txtlider" value=""/>
                    @endif
                </div>
        </div>

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden">Equipo:</label>
                    @if(Session::has("equipo"))
                            <input name="txtequipo" type="text" class="form-control" id="txtequipo" value="{{Session::get('equipo')}}"/>
                    @else
                        <input name="txtequipo" type="text" class="form-control" id="txtequipo" value=""/>
                    @endif
                </div>
        </div>
        

        

        <div class="col-md-1">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
        </div>
    {!!Form::close()!!}
    </div>

</div>

</div>


<?php
    Session::forget('mes');
    Session::forget('anio');
    Session::forget('lider');
    Session::forget('equipo');

?>