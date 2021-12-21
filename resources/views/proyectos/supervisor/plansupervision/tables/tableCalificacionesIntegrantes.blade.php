
<label>Inspecciones equipo de trabajo: Enero 2018</label>

<br>
<br>
<label>Programación</label>
<table class="table table-striped table-bordered" cellspacing="0" width="99%"  >
    <thead>
        <tr>
            <th style="width:50px;    vertical-align: middle;" rowspan="2" >Colaborador</th> 
            <th style="width:50px;" colspan="3">Observación del comportamiento</th>
            <th style="width:50px;" colspan="3">IPALES</th>
            <th style="width:50px;" colspan="3">Calidad</th>
            <th style="width:50px;" colspan="3">Medio ambiente</th>
            <th style="width:50px;" colspan="3">Kit Manejo derammes</th>
            <th style="width:50px;" colspan="3">Locativa de Gestión Ambiental</th>
            <th style="width:50px;" >Avance</th>
        </tr>

        <tr>
            
            <th style="width:50px;" >Pro</th>
            <th style="width:50px;" >Eje</th>
            <th style="width:20px;" >%</th>
            
            <th style="width:50px;" >Pro</th>
            <th style="width:50px;" >Eje</th>
            <th style="width:20px;" >%</th>

            <th style="width:50px;" >Pro</th>
            <th style="width:50px;" >Eje</th>
            <th style="width:20px;" >%</th>

            <th style="width:50px;" >Pro</th>
            <th style="width:50px;" >Eje</th>
            <th style="width:20px;" >%</th>

            <th style="width:50px;" >Pro</th>
            <th style="width:50px;" >Eje</th>
            <th style="width:20px;" >%</th>

            <th style="width:50px;" >Pro</th>
            <th style="width:50px;" >Eje</th>
            <th style="width:20px;" >%</th>

            <th style="width:20px;" >%</th>
        </tr>

    </thead>
    <tbody>

        <?php
            $aux1 = 0;
            $aux2 = 0;

            $aux3 = 0;
            $aux4 = 0;

            $aux5 = 0;
            $aux6 = 0;

            $aux7 = 0;
            $aux8 = 0;

            $aux9 = 0;
            $aux10 = 0;

            $aux11 = 0;
            $aux12 = 0;

            $porcentaje1 = 0;
            $porcentaje2 = 0;
            $porcentaje3 = 0;
            $porcentaje4 = 0;
            $porcentaje5 = 0;
            $porcentaje6 = 0;
            $porcentaje7 = 0;
            $porcentaje8 = 0;

            $porcentaje11 = 0;
            $porcentaje12 = 0;
            $porcentaje13 = 0;
            $porcentaje14 = 0;


            $porcentaje9 = 0;
            $porcentaje10 = 0;

        ?>
      @foreach($colaboradores as $key => $valor)
       <tr>
           <td>{{strtoupper($valor->nombre)}}</td>

           <?php
            $aux1 +=  intval($valor->comportamiento);

            $aux3 += intval($valor->ipales);

            $aux5 +=   intval($valor->calidad);

            $aux7 +=  intval(($valor->ambiental == NULL ? 0 : $valor->ambiental));

            $aux9 +=  intval(($valor->kit_manejo_derrames == NULL ? 0 : $valor->kit_manejo_derrames));

            $aux11 +=  intval(($valor->locativa_gestion_ambiental == NULL ? 0 : $valor->locativa_gestion_ambiental));

            $aux2 +=  intval($valor->obseEje); 

            $aux4 +=  intval($valor->ipalEje);

            $aux6 +=   intval($valor->caliEje);

            $aux8 +=   intval(($valor->ambiEje == NULL ? 0 : $valor->ambiEje));

            $aux10 +=   intval(($valor->kitEje == NULL ? 0 : $valor->kitEje));

            $aux12 +=   intval(($valor->locaEje == NULL ? 0 : $valor->locaEje));


          $porcentaje1 = intval($valor->obseEje) / (intval($valor->comportamiento) == 0 ? 1 : intval($valor->comportamiento)) * 100;

          $porcentaje2 = intval($valor->ipalEje) / (intval($valor->ipales) == 0 ? 1 : intval($valor->ipales)) * 100;

          $porcentaje3 = intval($valor->caliEje) / (intval($valor->calidad) == 0 ? 1 : intval($valor->calidad)) * 100;

          $porcentaje4 = intval(($valor->ambiEje == NULL ? 0 : $valor->ambiEje)) / (intval(($valor->ambiental == NULL ? 0 : $valor->ambiental)) == 0 ? 1 : intval(($valor->ambiental == NULL ? 0 : $valor->ambiental))) * 100;
        


          $porcentaje11 = intval(($valor->kitEje == NULL ? 0 : $valor->kitEje)) / (intval(($valor->kit_manejo_derrames == NULL ? 0 : $valor->kit_manejo_derrames)) == 0 ? 1 : intval(($valor->kit_manejo_derrames == NULL ? 0 : $valor->kit_manejo_derrames))) * 100;
          $porcentaje12 = intval(($valor->locaEje == NULL ? 0 : $valor->locaEje)) / (intval(($valor->locativa_gestion_ambiental == NULL ? 0 : $valor->locativa_gestion_ambiental)) == 0 ? 1 : intval(($valor->locativa_gestion_ambiental == NULL ? 0 : $valor->locativa_gestion_ambiental))) * 100;




          $a = intval($valor->obseEje) + intval($valor->ipalEje) + intval($valor->caliEje) + intval(($valor->ambiEje == NULL ? 0 : $valor->ambiEje))
          + intval(($valor->kitEje == NULL ? 0 : $valor->kitEje))
          + intval(($valor->locaEje == NULL ? 0 : $valor->locaEje));

          $b = intval($valor->comportamiento) + intval($valor->ipales) + intval($valor->calidad)
            + intval(($valor->ambiental == NULL ? 0 : $valor->ambiental))
            + intval(($valor->kit_manejo_derrames == NULL ? 0 : $valor->kit_manejo_derrames))
            + intval(($valor->locativa_gestion_ambiental == NULL ? 0 : $valor->locativa_gestion_ambiental));

          
          $porcentaje9 = $a / (($b == 0 ? 1 : $b)) * 100;

           ?>
           <td style="text-align:center">{{$valor->comportamiento}}</td>
           <td style="text-align:center">{{$valor->obseEje}}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje1}}%</b></td>

           <td style="text-align:center">{{$valor->ipales}}</td>
           <td style="text-align:center">{{$valor->ipalEje}}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje2}}%</b></td>
           
           <td style="text-align:center">{{$valor->calidad}}</td>
           <td style="text-align:center">{{$valor->caliEje}}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje3}}%</b></td>

           <td style="text-align:center">{{($valor->ambiental == NULL ? 0 : $valor->ambiental)}}</td>
           <td style="text-align:center">{{($valor->ambiEje == NULL ? 0 : $valor->ambiEje) }}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje4}}%</b></td>

           <td style="text-align:center">{{($valor->kit_manejo_derrames == NULL ? 0 : $valor->kit_manejo_derrames)}}</td>
           <td style="text-align:center">{{($valor->kitEje == NULL ? 0 : $valor->kitEje) }}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje11}}%</b></td>

           <td style="text-align:center">{{($valor->locativa_gestion_ambiental == NULL ? 0 : $valor->locativa_gestion_ambiental)}}</td>
           <td style="text-align:center">{{($valor->locaEje == NULL ? 0 : $valor->locaEje) }}</td>
           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje12}}%</b></td>


           <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje9}}%</b></td>
       </tr>
      @endforeach
      

       <?php
            
            $porcentaje5 = $aux2 / ($aux1 == 0 ? 1 : $aux1) * 100;
            $porcentaje6 = $aux4 / ($aux3 == 0 ? 1 : $aux3) * 100;
            $porcentaje7 = $aux6 / ($aux5 == 0 ? 1 : $aux5) * 100;
            $porcentaje8 = $aux8 / ($aux7 == 0 ? 1 : $aux7) * 100;

            
            $porcentaje13 = $aux10 / ($aux9 == 0 ? 1 : $aux9) * 100;
            $porcentaje14 = $aux12 / ($aux11 == 0 ? 1 : $aux11) * 100;

            $a1Pro = $aux1 + $aux3 + $aux5 + $aux7 + $aux9 + $aux11;
            $a1Eje = $aux2 + $aux4 + $aux6 + $aux8 + $aux10 + $aux12;

            $porcentaje10 = $a1Eje / ($a1Pro == 0 ? 1 : $a1Pro) * 100;


            ?>
      <tr>
         <td ><b>TOTAL</b></td>
         <td style="text-align:center"><b>{{$aux1}}</b></td>
          <td style="text-align:center"><b>{{$aux2}}</b></td>
          <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje5}}%</b></td>          

          <td style="text-align:center"><b>{{$aux3}}</b></td>
          <td style="text-align:center"><b>{{$aux4}}</b></td>          
          <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje6}}%</b></td>

          <td style="text-align:center"><b>{{$aux5}}</b></td>
          <td style="text-align:center"><b>{{$aux6}}</b></td>          
          <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje7}}%</b></td>


          <td style="text-align:center"><b>{{$aux7}}</b></td>
          <td style="text-align:center"><b>{{$aux8}}</b></td>          
          <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje8}}%</b></td>

          <td style="text-align:center"><b>{{$aux9}}</b></td>
          <td style="text-align:center"><b>{{$aux10}}</b></td>          
          <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje13}}%</b></td>

          <td style="text-align:center"><b>{{$aux11}}</b></td>
          <td style="text-align:center"><b>{{$aux12}}</b></td>          
          <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje14}}%</b></td>

          <td><b style="color:green;display: block;text-align: center; ">{{$porcentaje10}}%</b></td>
      </tr>
    </tbody>
</table>

<br>
<label>Inspecciones realizadas por los integrantes del equipo</label>

<table id="tbl_inspecciones_login" class="table table-striped table-bordered" cellspacing="0" width="99%">
    <thead>
        <tr>
            <th style="width:50px;">Tipo inspección</th>
            <th style="width:50px;">Integrante</th>
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
        @foreach ($inspeccionColaboradores as $key => $val)
            <tr>
            <td>{{$val->tipoINs}}</td>
               

                <td style="text-align:center;">{{$val->nombreSuper}}</td>
                
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

