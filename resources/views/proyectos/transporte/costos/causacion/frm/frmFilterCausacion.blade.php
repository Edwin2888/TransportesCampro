
<a href="#" onclick="abrirModalConcepto();" class="btn btn-primary  btn-cam-trans btn-sm" style="margin-top:10px;"><i class='fa'>&#xf067;</i> Crear causación</a>
<div id="filter" class="collapse in" style="margin-top:9px;border: 1px solid #3b8bd0;    border-radius: 10px;" arial-expanded="true">
    <div class="panel-body posisition-fixed-headaer" style="border-radius: 10px;padding-top: 12px;">
    <div class="row">
    {!! Form::open(['url' => 'transporte/costos/causacion/filter', "method" => "POST"]) !!}
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
                    <label for="id_orden">Placa:</label>
                        <input type="text" class="form-control" id="txtfilternombre" name="txtfilternombre" value="{{Session::get('txtfilternombre')}}"/>
                </div> 
        </div>

        <div class="col-md-2">
            <div class="form-group has-feedback">
                <label for="selConceptoFilter">Concepto:</label>
                    {!!Form::select('selConceptoFilter', $conceptos, Session::get('selConceptoFilter'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selConceptoFilter"])!!}
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group has-feedback">
                <label for="selProveedorFilter">Proveedor:</label>
                    {!!Form::select('selProveedorFilter', $proveedor, Session::get('selProveedorFilter'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selProveedorFilter"])!!}
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group has-feedback">
                <label for="selContraFilter">Contratantes:</label>
                    {!!Form::select('selContraFilter', $contratantes, Session::get('selContraFilter'), ["class"=>"form-control selectWzrd","placeholder"=>"Seleccione","id"=>"selContraFilter"])!!}
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-cam-trans btn-sm" style="margin-top:20px;" id="btn-add-nodos-orden" ><i class="fa fa-search"></i>  Consultar</button>
        <a href="../../transversal/transporte/home" class="btn btn-primary btn-cam-trans btn-sm" style="margin-top:20px;"><i class="fa fa-arrows-alt"></i> &nbsp; Cerrar</a>    
        
    {!!Form::close()!!}

    </div>
</div>
</div>

<?php
    Session::forget('txtfilternombre');
    Session::forget('selContraFilter');
    Session::forget('selProveedorFilter');
    Session::forget('selConceptoFilter');
    Session::forget('fecha_inicio');
    Session::forget('fecha_corte');
?>
