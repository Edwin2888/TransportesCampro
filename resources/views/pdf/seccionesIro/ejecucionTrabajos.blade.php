<p class="negrita" style="position:relative;top:-40px;background:#ccc; border-top:0.1mm solid black;border-bottom:0.1mm solid black;">1. DATOS GENERALES DE LA ACTIVIDAD A REALIZAR</p>
      <table border="1" cellpadding="0" cellspacing="0" style="positon:relative;top:82px;width:97.3%;">
      <thead>
        <tr>
          <th style="width:214px">Empresa Colaboradora</th>
          <th style="width:50px">Turno N°</th>
          <th colspan="12">Tipo de Actividad</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width:214px">CAM</td>
          <td style="width:50px"></td>
          <td style="width:50px">Restitución Servicio</td>
          @if($user[0]->res == "RESTITUCION SERVICIO")
            <td style="width:20px">X</td>
          @else
            <td style="width:20px"></td>
          @endif
          
          <td style="width:50px">Alumbrado Público</td>
           @if($user[0]->res == "ALUMBRADO PUBLICO")
            <td style="width:20px">X</td>
          @else
            <td style="width:20px"></td>
          @endif
          <td style="width:55px">Operación red</td>
           @if($user[0]->res == "OPERACION RED")
            <td style="width:20px">X</td>
          @else
            <td style="width:20px"></td>
          @endif
          <td style="width:50px">Inspección</td>
           @if($user[0]->res == "INSPECCION")
            <td style="width:20px">X</td>
          @else
            <td style="width:20px"></td>
          @endif
          <td style="width:50px">SRC</td>
           @if($user[0]->res == "SRC")
            <td style="width:20px">X</td>
          @else
            <td style="width:20px"></td>
          @endif
          <td style="width:39px">Otras</td>
           @if($user[0]->res == "OTRAS")
            <td style="width:41px">X</td>
          @else
            <td style="width:41px"></td>
          @endif
        </tr>
      </tbody>
        
      </table>

      <table border="1" cellpadding="0" cellspacing="0" style="positon:relative;margin-top:-17px;margin-left:0px;width:97.3%;">
      <thead>
        <tr>
          <th rowspan="2" style="width:40px">CANTIDAD INTEGRANTES DEL GRUPO</th>
          <th rowspan="2" style="width:40px">{{$cant}}</th>
          <th style="width:50px">MOVIL No</th>
          <th style="width:113px">No AVANTEL/CEL</th>
          <th style="width:112px">TIPO DE VEHICULO</th>
          <th style="width:90px">PLACA</th>
          <th style="width:69px">Inspec. Pre - Vehiculo</th>
          <th style="width:57px">DIA</th>
          <th style="width:57px">MES</th>
          <th style="width:57px">AÑO</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width:50px">{{$movil}}</td>
          <td style="width:112px">{{$com}}</td>
          <td style="width:112px">{{$tipoV}}</td>
          <td style="width:90px">{{$otros[0]->placa}}</td>
          <td style="width:69px">
              <table border="1" cellpadding="0" cellspacing="0"  style="width:20px;position:relative;top:0px;border:0px;">
                  <thead>
                    <tr>
                      <th style="width:34.5px;border-left:0px;border-top:0px;"><span style="display:block;position:relative;top:-5px;">SI</span></th>
                      <th style="width:34.5px;border-right:0px;border-top:0px;"><span style="display:block;position:relative;top:-5px;">NO</span></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="width:34.5px;border-left:0px;border-bottom:0px;"></td>
                      <td style="width:34.5px;border-right:0px;border-bottom:0px;"></td>
                    </tr>
                  </tbody>
              </table>
          </td>
          <td style="width:57px">{{explode("-",$otros[0]->fecha_creacion)[2]}}</td>
          <td style="width:57px">{{explode("-",$otros[0]->fecha_creacion)[1]}}</td>
          <td style="width:57px">{{explode("-",$otros[0]->fecha_creacion)[0]}}</td>
        </tr>
      </tbody>
        
      </table>

     
      <table border="1" cellpadding="0" cellspacing="0" style="position:relative;top:-2px;">
       <tr>
    	<td style="height:0px; border:0px;"></td>
    	<td style="height:0px; border:0px;"></td>
    	<td style="height:0px; border:0px;"></td>
    	<td style="height:0px; border:0px;"></td>
    	<td style="height:0px; border:0px;"></td>
    	<td style="height:0px; border:0px;"></td>
    	<td style="height:0px; border:0px;"></td>
      </tr>	
      <tr>
    	<td style="width:20px;">ID</td>
    	<td style="width:180px;">NOMBRE</td>
    	<td style="width:70px;">CARGO</td>
    	<td style="width:150px;">DOC. IDENTIFICACIÓN</td>
    	<td style="width:70px;">CONDICIÓN PARA TRABAJAR</td>
    	<td style="width:70px;">REVISIÓN EPP</td>
      <td style="width:148px;">FIRMA</td>
      </tr>	
      <?php $aux = 1; ?>
      @if(isset($user))
        @for ($i = 0 ;  $i < count($user) ; $i++) 
          @if($user[$i]->id_pregunta == "122")
          <tr>
            <td style="height:15px;">{{$aux}}</td>
            <td style="height:15px;">{{$users[$aux - 1][0]->nombre}}</td>
            <td style="height:15px;"></td>
            <td style="height:15px;">{{$user[$i]->id_persona_conformacion}}</td>
            <td style="height:15px;">{{$user[$i]->res}}</td>
            @if( ($user[$i +1]->id_persona_conformacion == $user[$i]->id_persona_conformacion &&  $user[$i +1]->id_pregunta == "123"))
              <td style="height:15px;">{{$user[$i +1]->res}}</td>
            @endif
            @if($user[$i +2]->id_persona_conformacion == $user[$i]->id_persona_conformacion &&  $user[$i +2]->id_pregunta == "123")
              <td style="height:15px;">{{$user[$i +2]->res}}</td>
            @endif
            @if($user[$i +3]->id_persona_conformacion == $user[$i]->id_persona_conformacion &&  $user[$i +3]->id_pregunta == "123")
              <td style="height:15px;">{{$user[$i +3]->res}}</td>
            @endif
            @if($user[$i +4]->id_persona_conformacion == $user[$i]->id_persona_conformacion &&  $user[$i +4]->id_pregunta == "123")
              <td style="height:15px;">{{$user[$i +4]->res}}</td>
            @endif
            @if($user[$i +5]->id_persona_conformacion == $user[$i]->id_persona_conformacion &&  $user[$i +5]->id_pregunta == "123")
                <td style="height:15px;">{{$user[$i +5]->res}}</td>
            @endif
            <td style="height:15px;">
              @if($users[$aux - 1][1] != null && $users[$aux - 1][1] != "")
                @if($aux == 1)
                  <img  style="width:50px;position:absolute;top:8px;left:620px;" src="{{$ruta_imagenes}}/firmas/{{$users[$aux - 1][1]}}">
                @endif

                @if($aux == 2)
                  <img  style="width:50px;position:absolute;top:32px;left:620px;" src="{{$ruta_imagenes}}/firmas/{{$users[$aux - 1][1]}}">
                @endif

                @if($aux == 3)
                  <img  style="width:50px;position:absolute;top:45px;left:620px; " src="{{$ruta_imagenes}}/firmas/{{$users[$aux - 1][1]}}">
                @endif

                @if($aux == 4)
                  <img  style="width:50px;position:absolute;top:60px;left:620px;" src="{{$ruta_imagenes}}/firmas/{{$users[$aux - 1][1]}}">
                @endif

                @if($aux == 5)
                  <img  style="width:50px;position:absolute;top:75px;left:620px;" src="{{$ruta_imagenes}}/firmas/{{$users[$aux - 1][1]}}">
                @endif
              @endif
            </td>
          </tr>
          <?php $aux++;?>
          @endif
        @endfor
      @endif
      
      @for($i=$aux; $i < 4; $i++)
        <tr >
          <td style="height:15px;">{{$i}}</td>
          <td style="height:15px;"></td>
          <td style="height:15px;"></td>
          <td style="height:15px;"></td>
          <td style="height:15px;"></td>
          <td style="height:15px;"></td>
          <td style="height:15px;"></td>
        </tr>
      @endfor     
</table>