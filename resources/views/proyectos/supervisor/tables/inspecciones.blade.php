<table id="tbl_inspecciones" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:50px;">Tipo inspección</th>
            <th style="width:100px;">Inpección</th>
            <th style="width:50px;">Calificación</th>
            <th style="width:100px;">Orden</th>
            <th style="width:50px;">Resultado</th>
            <th style="width:50px;">Estado</th>
            <th style="width:200px;">Proyecto</th>
            <th style="width:20px;">Supervisor</th>
            <th style="width:20px;">Fecha</th>
            <th style="width:50px;">Líder</th>
            <th style="width:50px;">Móvil</th>
                        
            <th style="width:50px;">Consulta </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inspeccion as $key => $val)
            <tr>

                @if($val->tipo_inspeccion == 1)
                    <td style="text-align:center;">Seguridad</td>
                @endif
                @if($val->tipo_inspeccion == 2)
                    <td style="text-align:center;">Calidad</td>
                @endif

                @if($val->tipo_inspeccion == 3)
                    <td style="text-align:center;">Observación del comportamiento</td>
                @endif

                @if($val->tipo_inspeccion == 4)
                    <td style="text-align:center;">Medio ambiente</td>
                @endif

                @if($val->tipo_inspeccion == 33)
                    <td style="text-align:center;">Inspección de Seguridad Obras Civiles</td>
                @endif

                @if($val->tipo_inspeccion == 34)
                    <td style="text-align:center;">Inspección de Seguridad Telecomunicaciones</td>
                @endif

                @if($val->tipo_inspeccion == 35)
                    <td style="text-align:center;">Inspección de Seguridad Redes Electricas</td>
                @endif

                @if($val->tipo_inspeccion == 43)
                    <td style="text-align:center;">Inspección Kits Manejo de Derrames</td>
                @endif

                @if($val->tipo_inspeccion == 44)
                    <td style="text-align:center;">Inspección Locativa de Gestión Ambiental</td>
                @endif

                @if($val->tipo_inspeccion == 46)
                    <td style="text-align:center;">Inspección Entrega Obras Civiles</td>
                @endif

                @if($val->tipo_inspeccion == 36)
                    <td style="text-align:center;">Inspección Calidad Trabajos de Restablecimiento del Servicio</td>
                @endif

                @if($val->tipo_inspeccion == 37)
                    <td style="text-align:center;">Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</td>
                @endif

                <td style="text-align:center;color:blue">{{$val->id_inspeccion}}</td>
                <td style="text-align:center;">{{$val->calificacion}}</td>
                <td style="text-align:center;">{{$val->id_orden}}</td>

                 @if($val->resultado == "C")
                    <td style="text-align:center;color:green;font-weight:bold;">{{$val->resultado}}</td>
                @else
                    <td style="text-align:center;color:red;font-weight:bold;">{{$val->resultado}}</td>
                @endif
                <td style="text-align:center;">{{$val->nombreE}}</td>  

                <td style="text-align:center;">
                    <?php 
                       if($val->tipo_inspeccion==3){
                           echo $val->ceco;
                       }else{ ?>
                           {{($val->nombre == NULL ? $val->ceco : $val->nombre)}}
                     <?php  }  ?>
                    
                    
                    
                <?php //echo "idi tipo proy |".$val->id_tipo_proyecto."| id proyecto |".$val->id_proyecto."| pre1 =>".$val->pre1."<= |".$val->nombre."|   pre2 =>".$val->pre2."<= |".$val->ceco."|" 
                ?>
                </td>
                <td style="text-align:center;">{{$val->supervisor}} - {{$val->nombreS}}</td>
                <?php
                    $fecha = "";
                ?>
                @if($val->fecha_servidor == NULL || $val->fecha_servidor == "")
                    <td style="text-align:center;"></td>
                @else
                    <?php
                        $fecha = explode(" ",$val->fecha_servidor)[0];
                    ?>
                    <td style="text-align:center;">{{$fecha}}</td>
                @endif
               
                <td style="text-align:center;">{{$val->lider}} - {{$val->nombreL}}</td>
                <td style="text-align:center;">{{$val->movil}}</td>
                
                <td>
                    <a href="../../inspeccionOrdenes/inspeccion/{{$val->id_inspeccion}}" class="btn btn-primary btn-cam-trans btn-sm">
                    <i class="fa fa-search" aria-hidden="true"></i></a>
                </td>
            </tr>

        @endforeach
    </tbody>
</table>