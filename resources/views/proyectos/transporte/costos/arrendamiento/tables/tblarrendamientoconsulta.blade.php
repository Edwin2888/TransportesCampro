
<br>
<div class="row" >

<div >
    <table class="table table-striped table-bordered" cellspacing="0" width="99%" id="tbl_arrendamieno" >
        <thead id="cabezatable">

            <th style="  text-align: center">
                Proyecto
            </th>
            <th style="  text-align: center">
                Placa
            </th >

            <th style="  text-align: center">
                Estado vehículo
            </th >

            <th style="  text-align: center">
                Tipo de vehículo
            </th >
            <th style="  text-align: center">
                Tipo CAM
            </th>
            <th style="  text-align: center">
                Canon
            </th>
            <th style="  text-align: center">
                ID Documento
            </th>
            @if($permisoImprimir == "W")
                <th style="  text-align: center">
                    Imprimir
                </th>            
            @endif
            <th style="  text-align: center">
                Fecha de creación
            </th>

            <th style="  text-align: center">
                Cantidad días
            </th>
            
            <th style="  text-align: center">
                Total a pagar
            </th>           

        </thead>
        <div>
        <tbody id="tbl_arrendamientos">
            <?php
                $totalPagarFinal = 0;
            ?>
            @foreach($data as $key => $val)

             <tr >

                <td style="text-align:center;">
                    {{$val->proyecto}}
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
                    {{strtoupper ($val->tipoV)}}
                </td>
    

                <td style="text-align:center;">
                    {{strtoupper ($val->tipoCAM)}}
                </td>


                <td style="text-align:center;">
                    ${{number_format($val->canon, 2)}}
                </td>

                <td style="text-align:center;">
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
                </td>
                @if($permisoImprimir == "W")
                    <td style="text-align:center;">
                            <form action="{{url('/')}}/transporte/costos/arrendamientosdownload" method="POST" style="display:inline-block;">
                                <input type="hidden"  name = "documento" value = "{{$val->id_documento}}" />
                                <input type="hidden"  name = "_token" value = "{{ csrf_token() }}" />
                                <button class="btn btn-primary  btn-cam-trans btn-sm"><i class="fa fa-print" aria-hidden="true"></i>   Imprimir documento</button>
                            </form>
                    </td>
                @endif

                <td style="text-align:center;">
                        {{$val->fecha}}
                </td>

                <td style="text-align:center;">
                        {{$val->cantidad_dias}}
                </td>

                <td style="text-align:center;">
                    <?php
                        $totalPagarFinal += $val->total_pagar;
                    ?>
                    ${{number_format($val->total_pagar, 2)}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
    
</div>

<div style="  margin-bottom:10px;  width: 23%;    border: 1px solid #0060AC;    padding: 23px;    border-radius: 3px;    margin-left: 27px;    font-size: 19px;    color: #0060AC;">
    <b>Total a pagar: ${{ number_format($totalPagarFinal, 2)}}</b>
</div>
