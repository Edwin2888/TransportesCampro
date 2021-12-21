<table id="tbl_inasistencia" class="table table-striped table-bordered" cellspacing="0" width="99%" >
    <thead>
        <tr>
            <th style="width:50px;">Tipo de inasistencia</th>
            <th style="width:50px;">Turno</th>
            <th style="width:100px;">Móvil</th>
            <th style="width:100px;">Matricula</th>
            <th style="width:100px;">Hora inicio de turno</th>
            <th style="width:100px;">Hora fin de turno</th>
            <th style="width:50px;">Identificación</th>
            <th style="width:50px;">Cuadrillero</th>
            <th style="width:50px;">Tipo de cuadrillero</th>
            <th style="width:50px;">Fecha de inasistencia</th> 
            
            <th style="width:50px;">Observación</th>
            
            <th style="width:50px;">Supervisor</th>    
            <th style="width:50px;">Tipo Operacion</th>      
            
        </tr>
    </thead>
    <tbody>
        @foreach ($inasistencia as $key => $val)
            <tr>

                <td style="text-align:center;">
                    @if($val->motivo == 0)
                        <p style="background-color:green;color:white;    font-weight: bold;padding:3px;">Asistio</p>
                    @endif
                    @if($val->motivo == 1)
                        <p style="background-color:red;color:white;    font-weight: bold;padding:3px;">No asistio</p>
                    @endif
                    @if($val->motivo == 2)
                        <p style="background-color:orange;color:white;    font-weight: bold;padding:3px;">Incapacitado</p>
                    @endif
                    @if($val->motivo == 4)
                        <p style="background-color:gray;color:white;    font-weight: bold;padding:3px;">Inhabilitado</p>
                    @endif
                    @if($val->motivo == 5)
                        <p style="background-color:blue;color:white;    font-weight: bold;padding:3px;">Cambio de turno</p>
                    @endif
                    @if($val->motivo == 6)
                        <p style="background-color:#a1ff25;color:white;    font-weight: bold;padding:3px;">Permiso</p>
                    @endif
                </td>
                <?php
                $nuevafecha = strtotime ( '-1 month' , strtotime ( $val->fecha ) ) ;
                $nuevafecha = date ( 'H:i:s' , $nuevafecha );
                $nuevafecha1 = strtotime ( '-1 month' , strtotime ( $val->fecha_inasistencia2 ) ) ;
                $nuevafecha1 = date ( 'H:i:s' , $nuevafecha1 );
                ?>
                <td style="text-align:center;">{{$val->turno}}</td>
                <td style="text-align:center;">{{$val->movil}}</td>
                <td style="text-align:center;">{{$val->vehiculo}}</td>
                <td style="text-align:center;">{{$nuevafecha}}</td>
                <td style="text-align:center;">{{$nuevafecha1}}</td>
                <td style="text-align:center;">{{$val->cedula}}</tdH:i:s"></td>
                <td style="text-align:center;">{{$val->nombreL}} {{ $val->apellidoL}} - {{$val->tecnico}}</td>
                <td style="text-align:center;">
                    @if($val->tipo_tecnico == 0)
                        Líder
                    @endif

                    @if($val->tipo_tecnico == 1)
                        Auxiliar
                    @endif

                    @if($val->tipo_tecnico == 2)
                        Conductor
                    @endif
                </td>
                <td style="text-align:center;">{{$val->fecha_inasistencia}}</td>
                <td style="text-align:center;">{{$val->observacion}}</td>
                <td style="text-align:center;">{{$val->nombreS}} {{ $val->apellidoS}} - {{$val->id_supervisor}}</td>
                <td style="text-align:center;">{{$val->tipo_operacion}}</td>                
                
            </tr>
        @endforeach
    </tbody>
</table>
