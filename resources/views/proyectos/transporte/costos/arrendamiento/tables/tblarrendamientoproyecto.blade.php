
<style type="text/css">

.table-scroll {
    position: relative;
    max-width: 1280px;
    width:100%;
    height: 100%;
    margin: auto;
    display:table;
}
.table-wrap {
    width: 100%;
    display:block;
    height: 100%;
    overflow: auto;
    position:relative;
    z-index:1;
}
.table-scroll table {
    width: 100%;
    margin: auto;
    border-collapse: separate;
    border-spacing: 0;
}
.table-scroll th, .table-scroll td {
    padding: 5px 10px;
    border: 1px solid #000;
    background: #fff;
    vertical-align: top;
}
.faux-table table {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    pointer-events: none;
}
.faux-table table + table {
    top: auto;
    bottom: 0;
}
.faux-table table tbody, .faux-table  tfoot {
    visibility: hidden;
    border-color: transparent;
}
.faux-table table + table thead {
    visibility: hidden;
    border-color: transparent;
}
.faux-table table + table  tfoot{
    visibility:visible;
    border-color:#000;
}
.faux-table thead th, .faux-table tfoot th, .faux-table tfoot td {
    background: #ccc;
}
.faux-table {
    position:absolute;
    top:0;
    right:0;
    left:0;
    bottom:0;
    overflow-y:scroll;
}
.faux-table thead, .faux-table tfoot, .faux-table thead th, .faux-table tfoot th, .faux-table tfoot td {
    position:relative;
    z-index:2;
}


</style>


<?php 

    function printCombox($diaCheck,$dia,$mes,$anio,$observaciones,$estado)
    {
        $observacion = ($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones);
        if($diaCheck == "1")
            return '<input data-estado = "' . $estado . '" data-tipo="2" onclick="cambioCheck(this)" type="checkbox" data-dia="' . $dia . '" data-mes="' . $mes . '" data-anio="' . $anio . '" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="' . $observacion . '"/>';
        else
            return '<input disabled type="checkbox" data-dia="' . $dia . '" data-mes="' . $mes . '" data-anio="' . $anio . '"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="' . $observacion . '"/>';
    }

?>


<div class="row" style="margin-top:5px;width:97%;margin-left:2%;height:55%">

<div style="height:100%;overflow-y:auto" >

<div id="table-scroll" class="table-scroll">
  <div id="faux-table" class="faux-table" aria="hidden"></div>
  <div class="table-wrap">

    <table class="table table-striped table-bordered main-table" cellspacing="0" width="99%" id="tbl_arrendamieno" >
        <thead id="cabezatable">
            <th scope="col" style=" text-align: center">
            @if($permisoConfirmar == "W")
                Confirmar documentos
            @endif
            </th>
           <th scope="col" style="  text-align: center">
                Proyecto
            </th>
            <th scope="col" style="  text-align: center">
                Placa
            </th >

            <th scope="col" style="  text-align: center">
                Estado veh??culo
            </th >

            <th scope="col" style="  text-align: center">
                Tipo de veh??culo
            </th >

                <th scope="col" style="  text-align: center">
                Tipo CAM
            </th>

                   <th scope="col" style="  text-align: center">
                Tipo de vinculaci??n
            </th >

            <th scope="col" style="  text-align: center">
                Canon
            </th>


              <th scope="col" style="  text-align: center">
                Marca de veh??culo
            </th >

             <th scope="col" style="  text-align: center">
                Modelo de veh??culo
            </th >       

         

             <th scope="col" style="  text-align: center">
                Contrato Vehiculo
            </th >

             <th scope="col" style="  text-align: center">
                Fecha Fin Contrato
            </th >

             <th scope="col" style="  text-align: center">
                Numero de Activo
            </th >

            <th scope="col" style="  text-align: center">
                ID Documento
            </th>
            
        @if($permisoImprimir == "W")
            <th scope="col" style="  text-align: center">
                Imprimir
            </th>            
        @endif
            <th scope="col" style="  text-align: center">
                Fecha de creaci??n
            </th>

            <th scope="col" style="  text-align: center">
                Cantidad d??as
            </th>
            
            <th scope="col" style="  text-align: center">
                Descuentos
            </th>
            
            
            <th scope="col" style="  text-align: center">
                Total a pagar
            </th>

            <th scope="col" style="  text-align: center">
                Generar documentos

            </th>

            @for($i = $iniCuenta ; $i <= $ultimaDia; $i++)
                <th scope="col" style="  text-align: center" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}">
                    D??a {{$i}} {{$mes2Des}}
                </th>
            @endfor

            @for($i = 1 ; $i <= $ultima_dia; $i++)

                @if($i == $diaHoy)
                    <th scope="col" style="  text-align: center;    background: #0060AC;    color: white;" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}">
                        D??a {{$i}} {{ $mes1Des}}
                    </th>
                @else
                    <th scope="col" style="  text-align: center" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}">
                        D??a {{$i}} {{ $mes1Des}}
                    </th>
                @endif
            @endfor
            

        </thead>
        <tbody id="tbl_arrendamientos">
            <?php
                $totalPagarFinal = 0;
            ?>
            @foreach($data as $key => $val)

            <?php
                if($val->mostrar == "0")
                    continue;
             ?>

            @if($val->id_documento == NULL)
                @if($val->estado != 'E02')
                    <tr data-save = "1">
                @else
                    <tr data-save = "0">
                @endif
            @else
                @if($val->estadoDoc == "GENERADO")
                    @if($val->estado != 'E02')
                        <tr data-save = "1">
                    @else
                        <tr data-save = "0">
                    @endif
                @else
                    <tr data-save = "0">
                @endif
            @endif

                    <td style="text-align:center;">
                @if($permisoConfirmar == "W")
                        @if($val->id_documento != NULL)
                            @if($val->id_estado == "E1")
                                <input type="checkbox" style="width:17px;height:17px" class="select" data-ar='{{$val->id_documento}}'/>
                            @endif
                        @endif
                @endif
                    </td>
                <td style="text-align:center;">
                    {{$val->nombre}}
                </td>

                <td style="text-align:center;">
                    {{strtoupper ($val->placa)}}
                </td>

                <td style="text-align:center;">
                     @if($val->estado == 'E01')
                        <b style="color:green;padding:2px;">{{strtoupper ($val->estadoDes)}}</b>
                     @endif
                     @if($val->estado == 'E02')
                        <b style="color:red;padding:2px;">{{strtoupper ($val->estadoDes)}}</b>
                     @endif

                     @if($val->estado == 'E03' || $val->estado == 'E04')
                        <b style="color:orange;padding:2px;">{{strtoupper ($val->estadoDes)}}</b>
                     @endif
                    
                     @if($val->estado == 'E05' )
                        <b style="color:blue;padding:2px;">{{strtoupper ($val->estadoDes)}}</b>
                     @endif

                </td>

              
                <td style="text-align:center;">
                    {{strtoupper ($val->tipo_veh)}}
                </td>

                    <td style="text-align:center;">
                    {{strtoupper ($val->nombre_cam)}}
                </td>

                       <td style="text-align:center;">
                    {{strtoupper ($val->vinculo)}}
                </td>


                <td style="text-align:center;">
                    ${{number_format($val->canon, 2)}}
                </td>


                    <td style="text-align:center;">
                    {{strtoupper ($val->marca_veh)}}
                </td>

                 <td style="text-align:center;">
                    {{strtoupper ($val->modelo)}}
                </td>
    

            
                <td style="text-align:center;">
                    {{strtoupper ($val->contrato_veh)}}
                </td>


                <td style="text-align:center;">
                   {{strtoupper ($val->fin_veh)}}
                </td>

                 <td style="text-align:center;">
                   {{strtoupper ($val->numero_activo)}}
                </td>


                <td style="text-align:center;">
                    @if($val->id_documento != NULL)
                        <a href="{{url('/')}}/transporte/costos/arrendamientosdoc/{{$val->id_documento}}">{{$val->id_documento}}</a>

                        @if($val->estadoDoc == "GENERADO")
                            <br>
                            <b style="color:orange">{{$val->estadoDoc}}</b>
                        @endif

                        @if($val->estadoDoc == "ANULADA")
                            <br>
                            <b style="color:red">{{$val->estadoDoc}}</b>
                        @endif

                        @if($val->estadoDoc == "CONFIRMADO")
                            <br>
                            <b style="color:green">{{$val->estadoDoc}}</b>
                        @endif


                    @else
                        @if($permisoAcceso == "W")
                            @if($val->mostrar != '0')
                                @if($val->canon != '0' && $val->canon != '' && $val->canon != null )
                                    <a  onclick="generaDocumentoIndividual(this)" data-doc="A" data-pre="{{$val->prefijo}}" data-placa="{{$val->placa}}"  class="btn btn-primary  btn-cam-trans btn-sm" target="_blank"><i class="fa fa-check-square-o" aria-hidden="true"></i> Generar documento</a>
                                @endif
                            @endif
                        @endif
                    @endif
                    
                </td>
                @if($permisoImprimir == "W")
                    <td style="text-align:center;">
                        @if($val->id_documento != NULL)
                            <form action="{{url('/')}}/transporte/costos/arrendamientosdownload" method="POST" style="display:inline-block;">
                                <input type="hidden"  name = "documento" value = "{{$val->id_documento}}" />
                                <input type="hidden"  name = "_token" value = "{{ csrf_token() }}" />
                                <button class="btn btn-primary  btn-cam-trans btn-sm"><i class="fa fa-print" aria-hidden="true"></i>   Imprimir documento</button>
                            </form>
                        @endif
                        
                    </td>
                @endif


                <td style="text-align:center;">
                    @if($val->id_documento != NULL)
                        {{$val->fecha}}
                    @endif
                </td>

                <td style="text-align:center;">
                    @if($val->id_documento != NULL)
                        {{$val->cantidad_dias}}
                    @endif
                </td>
                <?php /* ///////////////////////////////////////////////// */ ?>
                 
                <td style="text-align:center;" class="algo">
                    <label class="valor" ></label><br>                    
                    <a href="" onclick="ver_costos(event,this,'<?= $val->placa ?>')" >ver</a>
                    <br>
                    <?php
                    $sql="select ISNULL(sum(valor),0) as suma from PARQUE.dbo.tra_arrendamiento_descuento where placa='".$val->placa."' and ano = '".$anio."' and mes='".$mes."'";
                    $suma=0;        
                    try{  $cond = DB::select( $sql); 
                          $suma=$cond[0]->suma;          
                    }catch (\PDOException $e) { }
                    
                    ?>
                    <input type="hidden" id='suma_descuentos_<?= strtoupper ($val->placa) ?>' value="<?= $suma ?>">
                    <label id='suma_descuentos_txt_<?= strtoupper ($val->placa) ?>'><?= number_format($suma, 2) ?></label>                    
                </td>
                <?php /* ///////////////////////////////////////////////// */ ?>

                <td style="text-align:center;" class=''>
                    @if($val->id_documento != NULL)
                        <?php
                            $totalPagarFinal += $val->total_pagar;
                        ?>
                        ${{number_format($val->total_pagar, 2)}}
                    @endif
                </td>

                <td>
                    @if($val->id_documento == NULL)
                        @if($val->canon != '0' && $val->canon != '' && $val->canon != null )
                            <input class="checSelect" type="checkbox" data-ar="{{$val->id_documento}}" checked style="width: 20px;    height: 20px;    margin-left: 24px;    margin-top: 16px;">
                        @else
                            @if($val->canon == '0' || $val->canon == '' || $val->canon == null)
                                <b style="display:inline-block;color:red;text-align: center;">El canon no puede ser igual a 0</b>
                            @endif
                        @endif
                    @else
                        @if($val->estadoDoc == "GENERADO")
                            @if($val->canon != '0' && $val->canon != '' && $val->canon != null )
                                <input class="checSelect"  type="checkbox" data-ar="{{$val->id_documento}}"  style="width: 20px;    height: 20px;    margin-left: 24px;    margin-top: 16px;">
                            @else
                                @if($val->canon == '0' || $val->canon == '' || $val->canon == null)
                                    <b style="display:inline-block;color:red;text-align: center;">El canon no puede ser igual a 0</b>
                                @endif
                            @endif
                        @endif
                    @endif
                </td>

                @if($val->canon == '0' || $val->mostrar == '0')
                    

                    @for($i = $iniCuenta ; $i <= $ultimaDia; $i++)
                        <td style="  text-align: center;background:#e6e6e6">
                        </td>
                    @endfor

                    @for($i = 1 ; $i <= 25; $i++)
                        <td style="  text-align: center;background:#e6e6e6">
                        </td>
                    @endfor


                    @for($i = 26 ; $i <= $ultima_dia; $i++)
                        <td style="background:#bdb9b9"></td>
                    @endfor
                @else
                    @include('proyectos.transporte.costos.arrendamiento.frm.frmMatrizDiasDocumentos')
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    </div>
    </div>
</div>
    
</div>



<br>

<div class="col-md-6">
    <b style="    margin-left: 26px;    font-size: 21px;">Resumen por tipo de Veh??culo</b>
    <table class="table table-striped table-bordered " cellspacing="0" width="99%"  style="    margin-top: 15px;
        width: 100%;  ">
        <thead>
            <tr>
                <th>Tipo de veh??culo</th>
                <th>Cantidad</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody id="tbody_detalle_resumen">

        </tbody>
    </table>
</div>

<div class="col-md-6">
    <b style="    margin-left: 26px;    font-size: 21px;">Resumen por tipo de Vinculaci??n</b>
    <table class="table table-striped table-bordered " cellspacing="0" width="99%"  style="    margin-top: 15px;
        width: 100%;    ">
        <thead>
            <tr>
                <th>Tipo de Vinculacion</th>
                <th>Cantidad</th>
                <th>Valor</th>
                <th>Descuento</th>
                <th>Valor Final</th>
            </tr>
        </thead>
        <tbody id="tbody_detalle_resumen_vinculacion">

        </tbody>
    </table>
</div>
<div style="clear:both;width:100%;"></div>



<div style="  display:inline-block;margin-bottom:10px;  width: 44%;    border: 1px solid #0060AC;    padding: 23px;    border-radius: 3px;    margin-left: 27px;    font-size: 19px;    color: #0060AC;">
    <b>Total a pagar en documentos generados AR: ${{ number_format($totalPagarFinal, 2)}}</b>
</div>

<div style="  display:inline-block;margin-bottom:10px;  width: 30%;    border: 1px solid #0060AC;    padding: 23px;    border-radius: 3px;    margin-left: 27px;    font-size: 19px;    color: #0060AC;">
    <b>Total a pagar: <span id="total_pagar_final"></span></b>
</div>