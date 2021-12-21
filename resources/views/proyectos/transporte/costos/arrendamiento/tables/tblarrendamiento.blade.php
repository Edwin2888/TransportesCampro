
<p style="text-align:center;margin-top: 27px;"><b>DOCUMENTOS GENERADOS PARA EL VEHÍCULO</b></p>
<div class="row" style="margin-top:15px;width:96%;margin-left:2%">
    <table class="table table-striped table-bordered" cellspacing="0" width="99%" id="tbl_arrendamieno">
        <thead>
            @if($imprimir == "W")
                <th style=" text-align: center">
                    Imprimir
                </th>
            @endif
            <th style=" text-align: center">
                ID Documento
            </th>
            <th style="  text-align: center">
                Estado documento
            </th>
            <th style="  text-align: center">
                Proyecto
            </th >
            <th style="  text-align: center">
                Placa
            </th >
            <th style="  text-align: center">
                Canon actual
            </th>
            <th style="  text-align: center">
                N° de días
            </th>
            <th style="  text-align: center">
                Total a pagar
            </th>
            <th style="  text-align: center">
                Fecha de creación
            </th>
            <th style="  text-align: center">
                Usuario creación
            </th>
            <th style="  text-align: center">
                Fecha de última actualización
            </th>
            <th style="  text-align: center">
                Usuario última actualización
            </th>

            <th style="  text-align: center">
                Fecha de anulación
            </th>
            <th style="  text-align: center">
                Usuario anula
            </th>

        </thead>
        <tbody id="tbTblCiudades">
        @foreach($arrendamiento as $key => $val)

           
            <tr>

                @if($imprimir == "W")
                    <td style="text-align:center;">
                        <form action="{{url('/')}}/transporte/costos/arrendamientosdownload" method="POST" style="display:inline-block;">
                            <input type="hidden"  name = "documento" value = "{{$val->id_documento}}" />
                            <input type="hidden"  name = "_token" value = "{{ csrf_token() }}" />
                            <button class="btn btn-primary  btn-cam-trans btn-sm"><i class="fa fa-print" aria-hidden="true"></i>   Imprimir documento</button>
                        </form>
                    </td>
                @endif

                

                <td style="text-align:center;">
                    <a href="{{url('/')}}/transporte/costos/arrendamientosdoc/{{$val->id_documento}}">{{$val->id_documento}}</a>
                </td>
                
                <td style="text-align:center;">
                    {{$val->id_estado}}
                </td>

                <td style="text-align:center;">
                    {{strtoupper ($val->nombre)}}
                </td>

                <td style="text-align:center;">
                    {{strtoupper ($val->placa)}}
                </td>
    
                <td style="text-align:center;">
                    ${{number_format($val->canon_actual, 2)}}
                </td>

                <td style="text-align:center;">
                    {{$val->cantidad_dias}}
                </td>

                <td style="text-align:center;">
                    ${{number_format($val->total_pagar, 2)}}
                </td>

                <td style="text-align:center;">
                    {{$val->fecha_creacion}}
                </td>

                <td style="text-align:center;">
                    {{$val->crea_user}}
                </td>

                <td style="text-align:center;">
                    {{$val->fecha_ultima_actualizacion}}
                </td>

                <td style="text-align:center;">
                    {{$val->anula_actualiza}}
                </td>

                <td style="text-align:center;">
                    {{$val->fecha_anula}}
                </td>

                <td style="text-align:center;">
                    {{$val->anula_user}}
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

</div>
