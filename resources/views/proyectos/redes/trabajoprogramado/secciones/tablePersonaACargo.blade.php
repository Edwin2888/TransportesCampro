<?php if(!isset($id_proyecto)){$id_proyecto="";} ?>
<?php if(!isset($aprueba)){$aprueba="";} ?>
 

<p style="font-weight: bold;
    text-align: center;
    margin-bottom: 0px;">Datos del proyecto</p>
<table class="table table-striped table-bordered" cellspacing="0" width="99%">
    <tbody id="datos_aux_1">
       <tr>
           <th>PROYECTO</th>
           <th>TIPO PROYECTO</th>
           <th>GOM</th>
           <th>WBS</th>
           <th>NODOS</th>           
           <th>F. Ejecución I.</th>     
           <th>F. Ejecución F.</th>     
       </tr>
       <tr>
           <td style="text-align:center">{{$pry}}</td>
           <td style="text-align:center" id="tipo_proyecto_id" data-tipo="{{$tipoID}}">{{strtoUpper($tipoPRY)}}</td>
           <td style="text-align:center">{{$datos->gom}}</td>
           <td style="text-align:center">{{$datos->wbs_utilzadas}}</td>
           <td style="text-align:center">{{$datos->nodos_utilizados}}</td>
           <td style="text-align:center">{{explode(" ",$datos->fecha_ejecucion)[0]}}</td>
           <td style="text-align:center">{{explode(" ",$datos->fecha_ejecucion_final)[0]}}</td>
       </tr>
    </tbody>
</table>

<p style="font-weight: bold;
    text-align: center;
    margin-bottom: 0px;">Recurso de la ManiObra</p>
<table class="table table-striped table-bordered" cellspacing="0" width="99%">
    <tbody id="datos_aux_2">
       <tr>
          @if($tipoID != "T03")
           <th>RDS</th>
          @endif
           <th>T. Cuadrilla</th>
           <th>Líder</th>           
           <th>Pos. MOBRA</th>     
           <th>Pos. Materiales</th>     
           <th>Reg. Fotográfico</th>     
           <th>Actualiza</th>    
       </tr>
       @foreach($cuadrilla as $key => $val)
        <tr>
          @if($tipoID != "T03")
            <td style="text-align:center;">{{$val->preplanilla}}</td>
          @endif
          
           <td style="text-align:center;">{{$val->tipoC}}</td>
           <td style="text-align:center;">{{$val->nombreL}} - {{$val->id_lider}}</td>
           <td style="text-align:center;">
              @if($val->cantAc > 0)
                <a class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirPlanillaMobra(this)" title="Planilla MOBRA" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pdf_planilla_trabajo_programados.php?id_proyecto={{$id_proyecto}}&id_orden={{$val->id_orden}}&id_lider={{$val->id_lider}}&tipo=1"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i></a>
              @endif
            </td>
           <td style="text-align:center;">
                @if($val->cantMat > 0 || $val->cantCS > 0)
                  <a class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirPlanillaMate(this)"  title="Planilla Materiales" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pdf_planilla_materiales_programados.php?id_orden={{$val->id_orden}}&id_lider={{$val->id_lider}}&tipo=1" data-cedula='{{$val->id_lider}}'>
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                @endif
           </td>
           <td style="text-align:center;">
               <a class="btn btn-primary btn-cam-trans btn-sm" onclick="abrirFotografia(this)" title="Registro fotográfico" target="_blank" href="{{config('app.Campro')[2]}}/campro/gop/{{ explode('_',\Session::get('proy_short'))[0]}}/pop_fotos_lider.php?id_orden={{$val->id_orden}}&id_lider={{$val->id_lider}}"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
           </td>
           <td>
                <a class="btn btn-primary btn-cam-trans btn-sm" title="Actualizar" onclick="actualizaBotonMaterial(this,'{{$val->id_orden}}','{{$val->id_lider}}')" ><i class="fa fa-refresh" aria-hidden="true"></i></a>
           </td>
       </tr>
       @endforeach
    </tbody>
</table>


@if($tipoID != "T03")
  <p><b>CONVENCIONES</b></p>
  <div >
      <span style="    display: inline-block;    border: 1px solid black;    height: 16px;    width: 29px;"></span>
      <span style="    position: relative;    display: inline-block;    top: -3px;    margin-left: 8px;">Sin captura en terreno</span>
  </div>

  <div >
      <span style="    display: inline-block;    border: 1px solid black;    height: 16px;    width: 29px;background: #a2f9a2"></span>
      <span style="    position: relative;    display: inline-block;    top: -3px;    margin-left: 8px;">Ejecución en terreno de baremos</span>
  </div>

  <div >
      <span style="    display: inline-block;    border: 1px solid black;    height: 16px;    width: 29px;background: #3fbb3f"></span>
      <span style="    position: relative;    display: inline-block;    top: -3px;    margin-left: 8px;">Ejecución en terreno de materiales</span>
  </div>

  <div >
      <span style="    display: inline-block;    border: 1px solid black;    height: 16px;    width: 29px;background: #9898f5"></span>
      <span style="    position: relative;    display: inline-block;    top: -3px;    margin-left: 8px;">Facturación verifica baremos</span>
  </div>

  <div >
      <span style="    display: inline-block;    border: 1px solid black;    height: 16px;    width: 29px;background: #5b5bea"></span>
      <span style="    position: relative;    display: inline-block;    top: -3px;    margin-left: 8px;">Facturación verifica materiales</span>
  </div>
@endif




@if($tipoID != "T03")
<table class="table table-striped table-bordered" cellspacing="0" width="99%" >
@else
<table class="table table-striped table-bordered" cellspacing="0" width="99%" style="display:none;">
@endif
    <thead>

        <tr>
            @if($tipo == "1")
            <th style="width:10px;"></th>
            <th style="width:20px;    padding-right: 0px;    padding-left: 0px;">Nodo</th>
            <th style="width:80px;padding-right: 0px;    padding-left: 0px;">DC</th>
            <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">CS</th>
            <th style="width:100px; !important;padding-right: 0px;    padding-left: 0px;">Usuario</th>
            <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">Fecha</th>
            @else
                <th style="width:10px;"></th>
                <th style="width:20px;    padding-right: 0px;    padding-left: 0px;">Nodo</th>
                <th style="width:100px; !important;padding-right: 0px;    padding-left: 0px;">Usuario</th>
                <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">Fecha</th>
            @endif
        </tr>
    </thead>
    <tbody id="persona_a_cargo">
        @foreach ($nodos as $nod => $val)
            
            @if($tipo == "1")            
              
              <!-- Validación por parte de facturación -->
              @if($val->propietario != NULL) 

                  <!-- Son baremos -->
                  @if($val->id_documento == NULL || $val->id_documento == '')
                      <!-- Capturado en facturación baremos -->
                      <tr style="background: #9898f5">

                  @else
                      <!-- No capturado en facturación materiales -->
                    @if($val->id_documento_cs == NULL || $val->id_documento_cs == '')
                      <tr>
                    <!-- Capturado en terreno materiales -->
                    @else
                      <tr style="background: #5b5bea">
                    @endif


                  @endif
              <!-- No se ha realizado la validación por parte de facturación -->
              @else
                  
                  <!-- Son baremos -->
                  @if($val->id_documento == NULL || $val->id_documento == '')
                    
                    <!-- Capturado en terreno baremos -->
                    @if($val->cantidad > 0)
                      <tr style="background: #a2f9a2">
                    <!-- No se capturaron en terreno baremos-->
                    @else
                      <tr>
                    @endif
                  <!-- Son materiales -->
                  @else

                    <!-- No capturado en terreno materiales -->
                    @if($val->id_documento_cs == NULL || $val->id_documento_cs == '')
                      <tr>
                    <!-- Capturado en terreno materiales -->
                    @else
                      <tr style="background: #3fbb3f">
                    @endif
                  @endif  

              @endif

            @else
              <tr>
            @endif
                @if($tipo == "1")
                @if($val->propietario != NULL)
                    <td style="text-align:center;" id="val_check_1" data-val="1">
                @else
                    <td style="text-align:center;" id="val_check_1" data-val="0">
                @endif
                    @if($val->propietario != NULL)
                        <i class="fa fa-check-square-o" aria-hidden="true" style="color:green" ></i>
                    @else
                        <i class="fa fa-arrows-alt" aria-hidden="true" style="color:red" ></i>
                    @endif
                </td>
                <td style="text-align:center;" data-nodo="{{$val->id_nodo}}" data-estado="{{$val->id_estado}}">{{$val->nombre_nodo}}</td>
                <td style="text-align:center;">
                @if($val->id_documento == NULL)
                    NO APLICA
                @else
                    {{$val->id_documento}}
                @endif
                </td>
                <td style="text-align:center;">
                @if($val->id_documento == NULL)
                    NO APLICA
                @else
                    {{$val->id_documento_cs}}
                @endif
                </td>
                <td style="text-align:center;">{{$val->propietario}}</td>
                <td style="text-align:center;">{{str_replace(".000", "", $val->fecha)}}</td>
                @else
                    @if($val->propietario != NULL)
                        <td style="text-align:center;" id="val_check_1" data-val="1">
                    @else
                        <td style="text-align:center;" id="val_check_1" data-val="0">
                    @endif

                    @if($val->propietario != NULL)
                        <i class="fa fa-check-square-o" aria-hidden="true" style="color:green"></i>
                    @else
                        <i class="fa fa-arrows-alt" aria-hidden="true" style="color:red"></i>
                    @endif
                    </td>
                    <td style="text-align:center;" data-nodo="{{$val->id_nodo}}" data-estado="{{$val->id_estado}}">{{$val->nombre_nodo}}</td>
                    <td style="text-align:center;">{{$val->propietario}}</td>
                    <td style="text-align:center;">{{str_replace(".000", "", $val->fecha)}}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

@if($tipoID != "T03")

   @if($aprueba == "1" || $aprueba == "2")
     <span style="display: inline-block;    border: 1px solid black;    padding: 11px;    border-radius: 8px;margin-bottom:20px;">
    <p><b>APROBACIÓN DEL SUPERVISOR</b></p>

    <span>EL SUPERVISOR 
    <b>
      @if($aprueba == "1")
        <span style="color:green">APRUEBA</span>
      @else
        <span style="color:red">NO APRUEBA</span>
        
      @endif
    </b>
      LA MANIOBRA

    <br>
    <b>OBSERVACIÓN:</b>{{$obseraprueba }}
    </span>
    </span>
  @endif
@endif


@if($tipoID == "T03")

<div style="height:200px;">
  <div  style="height:100%;overflow:auto;">
  <table class="table table-striped table-bordered" cellspacing="0" width="99%" style="height:100%;">

   <thead>
          <tr>
              <th style="width:20px;    padding-right: 0px;    padding-left: 0px;">Fecha</th>
              <th style="width:80px;padding-right: 0px;    padding-left: 0px;">Usuario</th>
              <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">Tipo</th>
              <th style="width:100px; !important;padding-right: 0px;    padding-left: 0px;">Descripción</th>
              <th style="width:80px; !important;padding-right: 0px;    padding-left: 0px;">Fecha de creación</th>
          </tr>
      </thead>

      <tbody>
          
          @foreach ($log as $nod => $val)
            <tr>
                <td>{{$val->fecha_consulta}}</td>
                <td>{{$val->propietario}}</td>
                <td>{{$val->nombre}}</td>
                <td>{{$val->descripcion}}</td>
                <td>{{$val->fecha}}</td>
            </tr>

          @endforeach
      </tbody>

  </table>
  </div>
</div>
@endif


  <script type="text/javascript">
  (function()
  {
    @if($tipoID != "T03")
      document.querySelector("#panel_fecha").style.display = "none";
    @else
      document.querySelector("#fech_ejecucionInput").value = '{{$fechaA}}';
      document.querySelector("#panel_fecha").style.display = "block";

    @endif
  })();
  </script>
