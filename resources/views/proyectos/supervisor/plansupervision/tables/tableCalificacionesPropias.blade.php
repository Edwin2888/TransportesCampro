
<label>Mis inspecciones: Año: {{$anio}} - Mes: {{$nombre_mes}}</label>

<br>
<br>
<label>Programación</label>
<table class="table table-striped table-bordered" cellspacing="0" width="99%"  >
    <thead>
        <tr>
            <th style="width:50px;">Tipo de inspección</th> 
            <th style="width:50px;">Programadas</th>
            <th style="width:50px;">Ejecutadas</th>
            <th style="width:10px;">%</th>

        </tr>
    </thead>
    <tbody>
       <tr>
          <?php
            $aux1 = 0;
            $aux2 = 0;

            $porcentaje1 = 0;
            $porcentaje2 = 0;
            $porcentaje3 = 0;
            $porcentaje4 = 0;
            $porcentaje5 = 0;
           $porcentaje6 = 0;
           $porcentaje7 = 0;
           $porcentaje8 = 0;
           $porcentaje9 = 0;
           $porcentaje10 = 0;
           $porcentaje11 = 0;
           $porcentaje12 = 0;

            if($aux == 1 )
            {
              $aux1 = intval($lider->comportamiento) + intval($lider->ipales) + intval($lider->calidad) + intval(($lider->ambiental == NULL ? 0 : $lider->ambiental))
              + intval(($lider->seguridad_obra_civil == NULL ? 0 : $lider->seguridad_obra_civil))+ intval(($lider->telecomunicaciones == NULL ? 0 : $lider->telecomunicaciones))
              + intval(($lider->redes_electricas == NULL ? 0 : $lider->redes_electricas))+ intval(($lider->kit_manejo_derrames == NULL ? 0 : $lider->kit_manejo_derrames))
              + intval(($lider->locativa_gestion_ambiental == NULL ? 0 : $lider->locativa_gestion_ambiental))+ intval(($lider->entrega_obra_civil == NULL ? 0 : $lider->entrega_obra_civil))
              + intval(($lider->restablecimiento_servicio == NULL ? 0 : $lider->restablecimiento_servicio))+ intval(($lider->mantenimiento == NULL ? 0 : $lider->mantenimiento));

              $aux2 = intval($cantLiderObser) + intval($cantLiderIPAL) 
                    + intval($cantLiderCali) + intval(($cantLiderAmbiente == NULL ? 0 : $cantLiderAmbiente))
                    + intval(($cantLiderMantenimiento == NULL ? 0 : $cantLiderMantenimiento))
                    + intval(($cantLiderLocativaGestionAmbiental == NULL ? 0 : $cantLiderLocativaGestionAmbiental))
                    + intval(($cantLiderRestablecimientoServicios == NULL ? 0 : $cantLiderRestablecimientoServicios))
                    + intval(($cantLiderEntregaObraCivil == NULL ? 0 : $cantLiderEntregaObraCivil))
                    + intval(($cantLiderKitManejoDerrames == NULL ? 0 : $cantLiderKitManejoDerrames))
                    + intval(($cantLiderTelecomunicaciones == NULL ? 0 : $cantLiderTelecomunicaciones))
                    + intval(($cantLiderSeguridadObraCivil == NULL ? 0 : $cantLiderSeguridadObraCivil))
                    + intval(($cantLiderRedesElectricas == NULL ? 0 : $cantLiderRedesElectricas));

              $porcentaje1 = intval($cantLiderObser) / (intval($lider->comportamiento) == 0 ? 1 : intval($lider->comportamiento)) * 100;

              $porcentaje2 = intval($cantLiderIPAL) / (intval($lider->ipales) == 0 ? 1 : intval($lider->ipales)) * 100;

              $porcentaje3 = intval($cantLiderCali) / (intval($lider->calidad) == 0 ? 1 : intval($lider->calidad)) * 100;

              $porcentaje4 = intval(($cantLiderAmbiente == NULL ? 0 : $cantLiderAmbiente)) / (intval($lider->ambiental) == 0 || $lider->ambiental == NULL ? 1 : intval($lider->ambiental)) * 100;

              $porcentaje5 = $aux2 / ($aux1 == 0 ? 1 : $aux1) * 100;

                $porcentaje6 = intval(($cantLiderSeguridadObraCivil == NULL ? 0 : $cantLiderSeguridadObraCivil)) / (intval($lider->seguridad_obra_civil) == 0 || $lider->seguridad_obra_civil == NULL ? 1 : intval($lider->seguridad_obra_civil)) * 100;

                $porcentaje7 = intval(($cantLiderTelecomunicaciones == NULL ? 0 : $cantLiderTelecomunicaciones)) / (intval($lider->telecomunicaciones) == 0 || $lider->telecomunicaciones == NULL ? 1 : intval($lider->telecomunicaciones)) * 100;

                $porcentaje8 = intval(($cantLiderRedesElectricas == NULL ? 0 : $cantLiderRedesElectricas)) / (intval($lider->redes_electricas) == 0 || $lider->redes_electricas == NULL ? 1 : intval($lider->redes_electricas)) * 100;

                $porcentaje9 = intval(($cantLiderKitManejoDerrames == NULL ? 0 : $cantLiderKitManejoDerrames)) / (intval($lider->kit_manejo_derrames) == 0 || $lider->kit_manejo_derrames == NULL ? 1 : intval($lider->kit_manejo_derrames)) * 100;

                $porcentaje10 = intval(($cantLiderLocativaGestionAmbiental == NULL ? 0 : $cantLiderLocativaGestionAmbiental)) / (intval($lider->locativa_gestion_ambiental) == 0 || $lider->locativa_gestion_ambiental == NULL ? 1 : intval($lider->locativa_gestion_ambiental)) * 100;

                $porcentaje11 = intval(($cantLiderEntregaObraCivil == NULL ? 0 : $cantLiderEntregaObraCivil)) / (intval($lider->entrega_obra_civil) == 0 || $lider->entrega_obra_civil == NULL ? 1 : intval($lider->entrega_obra_civil)) * 100;

                $porcentaje12 = intval(($cantLiderRestablecimientoServicios == NULL ? 0 : $cantLiderRestablecimientoServicios)) / (intval($lider->restablecimiento_servicio) == 0 || $lider->restablecimiento_servicio == NULL ? 1 : intval($lider->restablecimiento_servicio)) * 100;

                $porcentaje13 = intval(($cantLiderMantenimiento == NULL ? 0 : $cantLiderMantenimiento)) / (intval($lider->mantenimiento) == 0 || $lider->mantenimiento == NULL ? 1 : intval($lider->mantenimiento)) * 100;

            }
            else
            {
              if($tipo_login == 0)
              {
                $aux1 = 0;
                $aux2 = 0;
              }
              else
              {
                $aux1 = intval($colaboradores[0]->comportamiento) + intval($colaboradores[0]->ipales) + intval($colaboradores[0]->calidad) + intval(($colaboradores[0]->ambiental == NULL ? 0 : $colaboradores[0]->ambiental))
                    + intval(($colaboradores[0]->seguridad_obra_civil == NULL ? 0 : $colaboradores[0]->seguridad_obra_civil))+ intval(($colaboradores[0]->telecomunicaciones == NULL ? 0 : $colaboradores[0]->telecomunicaciones))
                    + intval(($colaboradores[0]->redes_electricas == NULL ? 0 : $colaboradores[0]->redes_electricas))+ intval(($colaboradores[0]->kit_manejo_derrames == NULL ? 0 : $colaboradores[0]->kit_manejo_derrames))
                    + intval(($colaboradores[0]->locativa_gestion_ambiental == NULL ? 0 : $colaboradores[0]->locativa_gestion_ambiental))+ intval(($colaboradores[0]->entrega_obra_civil == NULL ? 0 : $colaboradores[0]->entrega_obra_civil))
                    + intval(($colaboradores[0]->restablecimiento_servicio == NULL ? 0 : $colaboradores[0]->restablecimiento_servicio))+ intval(($colaboradores[0]->mantenimiento == NULL ? 0 : $colaboradores[0]->mantenimiento));

                $aux2 = intval($cantColaObser) + intval($cantColaIPAL) + intval($cantColaCali) + intval(($cantColaAmbiente == NULL ? 0 : $cantColaAmbiente) );


                  $porcentaje1 = intval($cantColaObser) / (intval($colaboradores[0]->comportamiento) == 0 ? 1 : intval($colaboradores[0]->comportamiento)) * 100;

                  $porcentaje2 = intval($cantColaIPAL) / (intval($colaboradores[0]->ipales) == 0 ? 1 : intval($colaboradores[0]->ipales)) * 100;

                  $porcentaje3 = intval($cantColaCali) / (intval($colaboradores[0]->calidad) == 0 ? 1 : intval($colaboradores[0]->calidad)) * 100;

                  $porcentaje4 = intval(($cantColaAmbiente == NULL ? 0 : $cantColaAmbiente)) / (intval($colaboradores[0]->ambiental) == 0 || $colaboradores[0]->ambiental == NULL ? 1 : intval($colaboradores[0]->ambiental)) * 100;

                  $porcentaje5 = $aux2 / ($aux1 == 0 ? 1 : $aux1) * 100;

                  $porcentaje6 = intval(($cantColaSeguridadObraCivil == NULL ? 0 : $cantColaSeguridadObraCivil)) / (intval($colaboradores[0]->seguridad_obra_civil) == 0 || $colaboradores[0]->seguridad_obra_civil == NULL ? 1 : intval($colaboradores[0]->seguridad_obra_civil)) * 100;

                  $porcentaje7 = intval(($cantColaTelecomunicaciones == NULL ? 0 : $cantColaTelecomunicaciones)) / (intval($colaboradores[0]->telecomunicaciones) == 0 || $colaboradores[0]->telecomunicaciones == NULL ? 1 : intval($colaboradores[0]->telecomunicaciones)) * 100;

                  $porcentaje8 = intval(($cantColaRedesElectricas == NULL ? 0 : $cantColaRedesElectricas)) / (intval($colaboradores[0]->redes_electricas) == 0 || $colaboradores[0]->redes_electricas == NULL ? 1 : intval($colaboradores[0]->redes_electricas)) * 100;

                  $porcentaje9 = intval(($cantColaKitManejoDerrames == NULL ? 0 : $cantColaKitManejoDerrames)) / (intval($colaboradores[0]->kit_manejo_derrames) == 0 || $colaboradores[0]->kit_manejo_derrames == NULL ? 1 : intval($colaboradores[0]->kit_manejo_derrames)) * 100;

                  $porcentaje10 = intval(($cantColaLocativaGestionAmbiental == NULL ? 0 : $cantColaLocativaGestionAmbiental)) / (intval($colaboradores[0]->locativa_gestion_ambiental) == 0 || $colaboradores[0]->locativa_gestion_ambiental == NULL ? 1 : intval($colaboradores[0]->locativa_gestion_ambiental)) * 100;

                  $porcentaje11 = intval(($cantColaEntregaObraCivil == NULL ? 0 : $cantColaEntregaObraCivil)) / (intval($colaboradores[0]->entrega_obra_civil) == 0 || $colaboradores[0]->entrega_obra_civil == NULL ? 1 : intval($colaboradores[0]->entrega_obra_civil)) * 100;

                  $porcentaje12 = intval(($cantColaRestablecimientoServicios == NULL ? 0 : $cantColaRestablecimientoServicios)) / (intval($colaboradores[0]->restablecimiento_servicio) == 0 || $colaboradores[0]->restablecimiento_servicio == NULL ? 1 : intval($colaboradores[0]->restablecimiento_servicio)) * 100;

                  $porcentaje13 = intval(($cantColaMantenimiento == NULL ? 0 : $cantColaMantenimiento)) / (intval($colaboradores[0]->mantenimiento) == 0 || $colaboradores[0]->mantenimiento == NULL ? 1 : intval($colaboradores[0]->mantenimiento)) * 100;

              }
            }

          ?>
           <td>Observación del comportamiento</td>
           @if($aux == "1")
             <td style="text-align:center">{{$lider->comportamiento}}</td>
             <td style="text-align:center">{{$cantLiderObser}}</td>
             <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje1}}%</b></td>
           @else

           @if($tipo_login == 0)
              <td style="text-align:center">0</td>
              <td style="text-align:center">0</td>
              <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje1}}%</b></td>
            @else
              <td style="text-align:center">{{$colaboradores[0]->comportamiento}}</td>
              <td style="text-align:center">{{$cantColaObser}}</td>
              <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje1}}%</b></td>
            @endif
           @endif
       </tr>

       <tr>
           <td>IPALES</td>
            @if($aux == "1")
           <td style="text-align:center">{{$lider->ipales}}</td>
           <td style="text-align:center">{{$cantLiderIPAL}}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje2}}%</b></td>
           @else
           @if($tipo_login == 0)
              <td style="text-align:center">0</td>
              <td style="text-align:center">0</td>
              <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje2}}%</b></td>
            @else
             <td style="text-align:center">{{$colaboradores[0]->ipales}}</td>
            <td style="text-align:center">{{$cantColaIPAL}}</td>
            <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje2}}%</b></td>
            @endif
            
           @endif
       </tr>

       <tr>
           <td>Calidad</td>
           @if($aux == "1")
           <td style="text-align:center">{{$lider->calidad}}</td>
           <td style="text-align:center">{{$cantLiderCali}}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje3}}%</b></td>
           @else

           @if($tipo_login == 0)
              <td style="text-align:center">0</td>
              <td style="text-align:center">0</td>
              <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje3}}%</b></td>
            @else
              <td style="text-align:center">{{$colaboradores[0]->calidad}}</td>
             <td style="text-align:center">{{$cantColaCali}}</td>
             <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje3}}%</b></td>
            @endif


            
           @endif
       </tr>

       <tr>
           <td>Medio ambiente</td>

            @if($aux == "1")
           <td style="text-align:center">{{($lider->ambiental == NULL ? 0 : $lider->ambiental)}}</td>
           <td style="text-align:center">{{($cantLiderAmbiente == NULL ? 0 : $cantLiderAmbiente) }}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje4}}%</b></td>
           @else

           @if($tipo_login == 0)
              <td style="text-align:center">0</td>
              <td style="text-align:center">0</td>
              <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje4}}%</b></td>
            @else
              <td style="text-align:center">{{($colaboradores[0]->ambiental == NULL ? 0 : $colaboradores[0]->ambiental)}}</td>
             <td style="text-align:center">{{($cantColaAmbiente == NULL ? 0 : $cantColaAmbiente) }}</td>
             <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje4}}%</b></td>
            @endif

            
            
           @endif

       </tr>
       <tr>
           <td>Inspeccion Seguridad Obras Civiles</td>

           @if($aux == "1")
               <td style="text-align:center">{{($lider->seguridad_obra_civil == NULL ? 0 : $lider->seguridad_obra_civil)}}</td>
               <td style="text-align:center">{{($cantLiderSeguridadObraCivil == NULL ? 0 : $cantLiderSeguridadObraCivil) }}</td>
               <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje6}}%</b></td>
           @else

               @if($tipo_login == 0)
                   <td style="text-align:center">0</td>
                   <td style="text-align:center">0</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje6}}%</b></td>
               @else
                   <td style="text-align:center">{{($colaboradores[0]->seguridad_obra_civil == NULL ? 0 : $colaboradores[0]->seguridad_obra_civil)}}</td>
                   <td style="text-align:center">{{($cantColaSeguridadObraCivil == NULL ? 0 : $cantColaSeguridadObraCivil) }}</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje6}}%</b></td>
               @endif



           @endif

       </tr>
       <tr>
           <td>Inspeccion Seguridad Telecomunicaciones</td>

           @if($aux == "1")
               <td style="text-align:center">{{($lider->telecomunicaciones == NULL ? 0 : $lider->telecomunicaciones)}}</td>
               <td style="text-align:center">{{($cantLiderTelecomunicaciones == NULL ? 0 : $cantLiderTelecomunicaciones) }}</td>
               <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje7}}%</b></td>
           @else

               @if($tipo_login == 0)
                   <td style="text-align:center">0</td>
                   <td style="text-align:center">0</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje7}}%</b></td>
               @else
                   <td style="text-align:center">{{($colaboradores[0]->telecomunicaciones == NULL ? 0 : $colaboradores[0]->telecomunicaciones)}}</td>
                   <td style="text-align:center">{{($cantColaTelecomunicaciones == NULL ? 0 : $cantColaTelecomunicaciones) }}</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje7}}%</b></td>
               @endif



           @endif

       </tr>
       <tr>
           <td>Inspeccion Redes Electricas</td>

           @if($aux == "1")
               <td style="text-align:center">{{($lider->redes_electricas == NULL ? 0 : $lider->redes_electricas)}}</td>
               <td style="text-align:center">{{($cantLiderRedesElectricas == NULL ? 0 : $cantLiderRedesElectricas) }}</td>
               <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje8}}%</b></td>
           @else

               @if($tipo_login == 0)
                   <td style="text-align:center">0</td>
                   <td style="text-align:center">0</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje8}}%</b></td>
               @else
                   <td style="text-align:center">{{($colaboradores[0]->redes_electricas == NULL ? 0 : $colaboradores[0]->redes_electricas)}}</td>
                   <td style="text-align:center">{{($cantColaRedesElectricas == NULL ? 0 : $cantColaRedesElectricas) }}</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje8}}%</b></td>
               @endif



           @endif

       </tr>
       <tr>
           <td>Inspeccion Kit Manejo de Derrames</td>

           @if($aux == "1")
               <td style="text-align:center">{{($lider->kit_manejo_derrames == NULL ? 0 : $lider->kit_manejo_derrames)}}</td>
               <td style="text-align:center">{{($cantLiderKitManejoDerrames == NULL ? 0 : $cantLiderKitManejoDerrames) }}</td>
               <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje9}}%</b></td>
           @else

               @if($tipo_login == 0)
                   <td style="text-align:center">0</td>
                   <td style="text-align:center">0</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje9}}%</b></td>
               @else
                   <td style="text-align:center">{{($colaboradores[0]->kit_manejo_derrames == NULL ? 0 : $colaboradores[0]->kit_manejo_derrames)}}</td>
                   <td style="text-align:center">{{($cantColaKitManejoDerrames == NULL ? 0 : $cantColaKitManejoDerrames) }}</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje9}}%</b></td>
               @endif



           @endif

       </tr>
       <tr>
           <td>Inspección Locativa de Gestión Ambiental</td>

           @if($aux == "1")
               <td style="text-align:center">{{($lider->locativa_gestion_ambiental == NULL ? 0 : $lider->locativa_gestion_ambiental)}}</td>
               <td style="text-align:center">{{($cantLiderLocativaGestionAmbiental == NULL ? 0 : $cantLiderLocativaGestionAmbiental) }}</td>
               <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje10}}%</b></td>
           @else

               @if($tipo_login == 0)
                   <td style="text-align:center">0</td>
                   <td style="text-align:center">0</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje10}}%</b></td>
               @else
                   <td style="text-align:center">{{($colaboradores[0]->locativa_gestion_ambiental == NULL ? 0 : $colaboradores[0]->locativa_gestion_ambiental)}}</td>
                   <td style="text-align:center">{{($cantColaLocativaGestionAmbiental == NULL ? 0 : $cantColaLocativaGestionAmbiental) }}</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje10}}%</b></td>
               @endif



           @endif

       </tr>
       <tr>
           <td>Inspección Entrega Obras Civiles</td>

           @if($aux == "1")
               <td style="text-align:center">{{($lider->entrega_obra_civil == NULL ? 0 : $lider->entrega_obra_civil)}}</td>
               <td style="text-align:center">{{($cantLiderEntregaObraCivil == NULL ? 0 : $cantLiderEntregaObraCivil) }}</td>
               <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje11}}%</b></td>
           @else

               @if($tipo_login == 0)
                   <td style="text-align:center">0</td>
                   <td style="text-align:center">0</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje11}}%</b></td>
               @else
                   <td style="text-align:center">{{($colaboradores[0]->entrega_obra_civil == NULL ? 0 : $colaboradores[0]->entrega_obra_civil)}}</td>
                   <td style="text-align:center">{{($cantColaEntregaObraCivil == NULL ? 0 : $cantColaEntregaObraCivil) }}</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje11}}%</b></td>
               @endif



           @endif

       </tr>
       <tr>
           <td>Inspección Calidad Trabajos de Restablecimiento del Servicio</td>

           @if($aux == "1")
               <td style="text-align:center">{{($lider->restablecimiento_servicio == NULL ? 0 : $lider->restablecimiento_servicio)}}</td>
               <td style="text-align:center">{{($cantLiderRestablecimientoServicios == NULL ? 0 : $cantLiderRestablecimientoServicios) }}</td>
               <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje12}}%</b></td>
           @else

               @if($tipo_login == 0)
                   <td style="text-align:center">0</td>
                   <td style="text-align:center">0</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje12}}%</b></td>
               @else
                   <td style="text-align:center">{{($colaboradores[0]->restablecimiento_servicio == NULL ? 0 : $colaboradores[0]->restablecimiento_servicio)}}</td>
                   <td style="text-align:center">{{($cantColaRestablecimientoServicios == NULL ? 0 : $cantColaRestablecimientoServicios) }}</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje12}}%</b></td>
               @endif



           @endif

       </tr>
       <tr>
           <td>Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT</td>

           @if($aux == "1")
               <td style="text-align:center">{{($lider->mantenimiento == NULL ? 0 : $lider->mantenimiento)}}</td>
               <td style="text-align:center">{{($cantLiderMantenimiento == NULL ? 0 : $cantLiderMantenimiento) }}</td>
               <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje4}}%</b></td>
           @else

               @if($tipo_login == 0)
                   <td style="text-align:center">0</td>
                   <td style="text-align:center">0</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje4}}%</b></td>
               @else
                   <td style="text-align:center">{{($colaboradores[0]->mantenimiento == NULL ? 0 : $colaboradores[0]->mantenimiento)}}</td>
                   <td style="text-align:center">{{($cantColaMantenimiento == NULL ? 0 : $cantColaMantenimiento) }}</td>
                   <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje13}}%</b></td>
               @endif



           @endif

       </tr>

       <tr>
          <td><b>TOTAL</b></td>
          <td style="text-align:center"><b>{{$aux1}}</b></td>
          <td style="text-align:center"><b>{{$aux2}}</b></td>
          <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje5}}%</b></td>
       </tr>
       


    </tbody>
</table>

<br>
<label>Inspecciones realizadas</label>


<table id="tbl_inspecciones_login" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:50px;">Tipo inspección</th>
            <th style="width:100px;">Inpección</th>
            <th style="width:50px;">Calificación</th>
            <th style="width:50px;">Resultado</th>
            <th style="width:50px;">Estado</th>
            <th style="width:200px;">Proyecto</th>
            <th style="width:20px;">Fecha</th>
            <th style="width:50px;">Líder</th>
            <th style="width:50px;">Móvil</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inspeccion as $key => $val)
            <tr>
                <td>{{$val->tipoINs}}</td>
               
                <td style="text-align:center;color:blue"><a href="../../../inspeccionOrdenes/inspeccion/{{$val->id_inspeccion}}" target="_blank">
                   {{$val->id_inspeccion}}</a></td>
                <td style="text-align:center;">{{$val->calificacion}}</td>

                 @if($val->resultado == "C")
                    <td style="text-align:center;color:green;font-weight:bold;">{{$val->resultado}}</td>
                @else
                    <td style="text-align:center;color:red;font-weight:bold;">{{$val->resultado}}</td>
                @endif
                <td style="text-align:center;">{{$val->nombreE}}</td>  

                <td style="text-align:center;">{{($val->nombre == NULL ? $val->ceco : $val->nombre)}}</td>
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
                
            </tr>

        @endforeach
    </tbody>
</table>
