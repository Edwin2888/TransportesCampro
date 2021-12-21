<?php $encabezado_obtenido = false; ?>
<?php $encabezado = new stdClass(); ?>


 @for($i = $iniCuenta ; $i <= $ultimaDia; $i++)
    <?php 
        //$val->{"dia{$i}"} = 0; 
    ?>

    @if($i == 26)
        @if(isset($val->dia26))            
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 26)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 26)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')">

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')">

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 26)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')">
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia26,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')">
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia26,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 27)
        @if(isset($val->dia27))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 27)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 27)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')">

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')">

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 27)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')">
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia27,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')">
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia27,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 28)
        @if(isset($val->dia28))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 28)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 28)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 28)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia28,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia28,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 29)
        @if(isset($val->dia29))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 29)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 29)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 29)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia29,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia29,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 30)
        @if(isset($val->dia30))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 30)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 30)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 30)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia30,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia30,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 31)
        @if(isset($val->dia31))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 31)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 31)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 31)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia31,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mesPasado}}" data-anio="{{$anioPasado}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia31,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif
@endfor

 @for($i = 1 ; $i <= $ultima_dia; $i++)
    <?php 
        //$val->{"dia{$i}"} = 0; 
    ?>
    
    @if($i == 1)
        @if(isset($val->dia1))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 1)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 1)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 1)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <?php echo printCombox($val->dia1,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                         <?php echo printCombox($val->dia1,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 2)
        @if(isset($val->dia2))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 2)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 2)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 2)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia2,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia2,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 3)
        @if(isset($val->dia3))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 3)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 3)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 3)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia3,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia3,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 4)
        @if(isset($val->dia4))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 4)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 4)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 4)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia4,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia4,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 5)
        @if(isset($val->dia5))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 5)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 5)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 5)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia5,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia5,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 6)
        @if(isset($val->dia6))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 6)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 6)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 6)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia6,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia6,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 7)
        @if(isset($val->dia7))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 7)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 7)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 7)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia7,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia7,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 8)
        @if(isset($val->dia8))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 8)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 8)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 8)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia8,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia8,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 9)
        @if(isset($val->dia9))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 9)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 9)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 9)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia9,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia9,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 10)
        @if(isset($val->dia10))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 10)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 10)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 10)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia10,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia10,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 11)
        @if(isset($val->dia11))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 11)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 11)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 11)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia11,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia11,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 12)
        @if(isset($val->dia12))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 12)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 12)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 12)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia12,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia12,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 13)
        @if(isset($val->dia13))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 13)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 13)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 13)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia13,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia13,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 14)
        @if(isset($val->dia14))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 14)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 14)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 14)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia14,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia14,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 15)
        @if(isset($val->dia15))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 15)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 15)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 15)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia15,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia15,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 16)
        @if(isset($val->dia16))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 16)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 16)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 16)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia16,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia16,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 17)
        @if(isset($val->dia17))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 17)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 17)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 17)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia17,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia17,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 18)
        @if(isset($val->dia18))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 18)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 18)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 18)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia18,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia18,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 19)
        @if(isset($val->dia19))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 19)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 19)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 19)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia19,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia19,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 20)
        @if(isset($val->dia20))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 20)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 20)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 20)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia20,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia20,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 21)
        @if(isset($val->dia21))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 21)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 21)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php 
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 21)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            @if($val->dia21 == "1")
                                <input data-estado = "1" data-tipo="2" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                            @else
                            <?php echo printCombox($val->dia21,$i,$mes,$anio,$observaciones,1); ?>
                            @endif
                        @endif
                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia21,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 22)
        @if(isset($val->dia22))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 22)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 22)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 22)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia22,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia22,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 23)
        @if(isset($val->dia23))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 23)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 23)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 23)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia23,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia23,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 24)
        @if(isset($val->dia24))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 24)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 24)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 24)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia24,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia24,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

    @if($i == 25)
        @if(isset($val->dia25))
            <?php
                $existInci = 0;
                $inci = 0;
                $checkARgenerados = 1;
                $observaciones = "";
                $adjunto="";
            ?>
            @foreach($registros as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 25)
                        {
                            $existInci = 1;
                            $inci = $valor->incidencia;
                            unset($registros[$key]);
                            break;
                        }
                    }
                ?>
            @endforeach

            @if($existInci == 1)

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 25)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "1" data-tipo="1" onclick="cambioCheck(this)" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif
                        
                    </td>
                @else
                    <td style="background:#feefd4" title="EN TALLER - INCIDENCIA: {{$inci}} {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/crane-truck.png" onclick="veradjunto('<?= $adjunto ?>')" >

                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                            <input data-estado = "2" data-tipo="1" type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" onclick="cambioCheck(this)"  style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @endif


                        
                    </td>
                @endif
            @else

                @foreach($registrosDocGenerados as $key => $valor)
                <?php
                    if(strtoupper($valor->placa) == strtoupper($val->placa))
                    {
                        if($valor->dia == 25)
                        {
                            $checkARgenerados = intval($valor->check);
                            $observaciones = $valor->obser;
                            $adjunto = $valor->archivo;
                            unset($registrosDocGenerados[$key]);
                            break;
                        }
                    }
                ?>
                @endforeach

                @if($checkARgenerados == 1)
                    <td style="background:#dbfedb" title="ACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/checked.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}" checked style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia25,$i,$mes,$anio,$observaciones,1); ?>
                        @endif

                    </td>
                @else
                    <td style="background:#f8d9d9" title="INACTIVO {{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : '- OBSERVACIÓN: ' . $observaciones)}}">
                        <img style="width:20px;" src="{{url('/')}}/img/exclamation-mark.png" onclick="veradjunto('<?= $adjunto ?>')" >
                        
                        @if($val->estadoDoc == "CONFIRMADO" || $val->estadoDoc == "ANULADA")
                            <input disabled type="checkbox" data-dia="{{$i}}" data-mes="{{$mes}}" data-anio="{{$anio}}"   style="display:block;    margin-top: 7px;    width: 20px;    height: 20;"  data-obser="{{($observaciones == '' || $observaciones == 'null' || $observaciones == null ? '' : $observaciones)}}"/>
                        @else
                        <?php echo printCombox($val->dia25,$i,$mes,$anio,$observaciones,2); ?>
                        @endif

                    </td>
                @endif

            @endif
        @else
            <td style="background:#cfcfcf"></td>
        @endif
    @endif

@endfor

@for($i = 26 ; $i <= $ultima_dia; $i++)
    <td style="background:#bdb9b9"></td>
@endfor