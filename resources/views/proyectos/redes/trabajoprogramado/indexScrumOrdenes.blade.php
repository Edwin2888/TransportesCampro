@extends('template.index')

@section('title')
    Kanban Ordenes
@stop

@section('title-section')
    Kanban Ordenes
@stop

@section('css')
    <link rel="stylesheet" href="../../css/componentModal.css" type="text/css" media="screen" title="no title" charset="utf-8">
    <link rel="stylesheet" href="../../css/default.css" type="text/css" media="screen" title="no title" charset="utf-8">
@stop

@section('content')
    <style type="text/css">
    html, body { height: 100%; padding:0px; margin:0px; overflow: hidden; }
   
    .panel_datos
    {
        height: 100%;
        width:250px;
        background-color: yellow;
        float: left;
        margin-left: 4px;

    }

    #outer_wrapper {  
    overflow: scroll;  
    width:100%;
    height: 75%;
    }
    #outer_wrapper #inner_wrapper {
        width:996000px; /* If you have more elements, increase the width accordingly */
        height: 100%;
        padding: 5px;
    }

    
    #outer_wrapper #inner_wrapper div.box { /* Define the properties of inner block */
        width: 250px;
        height:300px;
        float: left;
        height: 100%;
        margin: 0 4px 0 0;
        border:1px grey solid;
        border-radius: 5px;
        overflow-y:auto;
    }

    .box p
    {
        text-align: center;
        font-size: 11px;
        color:gray;
    }

    .box .line_divisor
    {
        width: 100%;
        border: 1px solid gray;
    }

    .contenedor_restricciones
    {
        width: 96%;
        height: 80px;
        margin-left: 2%;
        box-shadow: 0px 0px 8px -2px rgba(0,0,0,0.75);
        margin-top: 2px;
        border-radius: 5px;
        -webkit-transition: border .4s; /* For Safari 3.1 to 6.0 */
        transition: border .4s;
        border: 1px solid white;
        cursor: pointer;
    }

    .fecha_limite
    {
        width: 100%;
        height: 35px;
        background-color: #dcdcdc;

    }
    .fecha_limite p
    {
        position: relative;
        top:10px;
    }

    .resumen_scrum
    {
        width: 100%;
        height: 60px;
        background: #444444;
        margin-bottom: 6px;

    }

    .contenedor_restricciones:hover
    {
        border: 1px solid gray;
    }


    .contenedor_restricciones .title
    {
        margin-left: 4px;
        margin-top: 1px;
        text-align: left;
        margin-bottom: 0px;
    }

    .contenedor_restricciones .impacto
    {
        margin-left: 4px;
        text-align: left;
        position: relative;
        top: -3px;
        margin-bottom: 0px;
        height: 80%;
    }

    .abierta
    {
        background-color: red;
    }
    
    .contenedor_restricciones.abierta p
    {
        color:gray;
    }

    .anulada
    {
        background-color: black;
    }
    
    .contenedor_restricciones.anulada p
    {
        color:gray;
    }

    .cerrada
    {
        background-color: rgb(0,143,65);
    }
    

    .programada
    {
        background-color: blue;
    }

    .contenedor_restricciones.programada p
    {
        color:gray;
    }

    .programadamas
    {
        background-color: yellow;
    }

    .contenedor_restricciones.programadamas p
    {
        color:gray;
    }


    .contenedor_restricciones.cerrada p
    {
        color:gray;
    }

    .contenedor_restricciones .puntos_add
    {

    }

    .contenedor_restricciones.cerrada
    {

        background-color: rgb(242, 255, 248);
    }

    .contenedor_restricciones.programada
    {
        background-color: rgba(0, 0, 255, 0.15);
    }


     .contenedor_restricciones.programadamas
    {
        background-color: rgba(255, 255, 0, 0.14);
    }

    .contenedor_restricciones.anulada
    {
        background-color: rgba(0, 0, 0, 0.16);
    }

    .contenedor_restricciones.abierta
    {
        background-color: #dfeefb;
    }

    .contenedor_restricciones.abierta .abierta p,.contenedor_restricciones.programada .programada p,
    .contenedor_restricciones.anulada .anulada p,.contenedor_restricciones.cerrada .cerrada p
    {
        color:white;
    }
    
    
    #overlay
    {
        background: rgba(255, 255, 255, 0.8);
    }
    ::-webkit-scrollbar {
    width: 6px;
    }
     
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
    }
     
    ::-webkit-scrollbar-thumb {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
    }


    .elementos
    {
            width: 134px;
    height: 46px;
    position: relative;
    top: -92px;
    right: 0px;
    margin: 0px;
    left: 90%;
    }

    .elementos a
    {
        background: blue;
        border-radius: 100%;
        width: 40px;
        height: 40px;
        display: inline-block;
    }

    .elementos a span
    {
        position: relative;
        color: white;
        font-size: 20px;
        left: 14px;
        top: 7px;
    }
    
    .datos_columna
    {
        height: 100%;
        width: 12px;
        float: left;
        margin-right: 6px;
    }

    .resumen_scrum .fecha
    {
        color: white;
        position: relative;
        top: 6px;
    }

    .resumen_scrum p
    {
        color: white;
    }

    .img_fondo
    {
        position: relative;
        float: right;
        width: 34px;
        opacity: 0.7;
        margin-top: 9px;
        margin-right: 4px;
        
    }

    .ver_mas
    {
        position: relative;
        top: -16px;
        left: 86%;
        border: 1px solid #929292;
        margin: 0px;
        display: inline-block;
        height: 6px;
        padding: 4px;
        border-radius: 1px;
        transition:all .5s;
    }   

    .ver_mas:hover
    {
        border: 1px solid black;
    }

    .ver_mas b
    {
        position: relative;
        top: -10px;
        font-size: 22px;
        top: -21px;
    }

    .datos_extra
    {
        width: 96.5%;
        height: auto;
        background: #fdfdfd;
        margin-left: 2%;
        margin-bottom: 15px;
        border: 1px solid #cccccc;
        box-shadow: 0px 0px 8px -2px rgba(0,0,0,0.75);
        transition:all 0.6s;
    }

    .datos_extra p
    {
       transition:all 0.3s; 
    }

    .datos_extra.oculta
    {
        height: 0px;
    }

    .datos_extra p
    {
        text-align: left;
        margin: 0px;
        margin-left: 8px;
    }

   

    .datos_extra.oculta p
    {
        font-size: 0px;
    }

    .datos_extra.oculta p span
    {
        padding: 0px !important;
    }

    .datos_extra.oculta a{
        border: 0px;
        font-size: 0px;
        padding: 0px;
    }

    .datos_extra tr th,.datos_extra tr td
    {
        font-size: 10px;
        color:gray;
        text-align: center;
    }

    .datos_extra table 
    {
        margin-bottom: 5px;
    }

    .datos_extra.oculta tr th,.datos_extra.oculta tr td
    {
        font-size: 0px;
    }

    .scroll_table
    {
        margin-top: 3px;
        overflow-x:auto;
    }
.datos_extra.oculta .scroll_table
{
    width: 0px;
    height: 0px;
}


    
    .btn_cerrar
    {
        font-size: 12px;
        color: white;
        margin-left: 6px;
        padding: 4px;
        position: relative;
        top: -2px;
        margin-top: 10px;
        display: inline-block;
        margin-bottom: 4px;
        color: #204d74;
        background-color: white;
        border-color: #204d74;
        border: 1px solid;
        transition:background 0.5s;
    }

    .btn_cerrar:hover
    {
        text-decoration: none;
        color: white;
        background-color: #204d74;
        border-color: #204d74;
        border: 1px solid;
    }

    .ver_mas_select
    {
        background: gray;
        color:white;
    }

    .ver_mas_select b
    {
        color: white;
    }

    .evidencia_dowload
    {
        color:#0060AC;
    }

    .evidencia_dowload:hover
    {
        color:#0060AC;   
    }


    .title_reporte
    {
        background: #084A9E;
        color: white;
        padding: 3px;
        padding-left: 11px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .reporte
    {
        width: 100%; 
        height: 100%;
        display: inline-block;
        margin-bottom: 30px;
        border: 1px solid #084A9E;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }

    </style>
    <main>

    @include('proyectos.redes.trabajoprogramado.modal.modalconsultaproyectos')
    @include('proyectos.redes.trabajoprogramado.modal.modalconsultaCuadrillas')
    

    <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
        @include('proyectos.redes.trabajoprogramado.secciones.filterScrumOrdenes')
    </div>

    <div class="row">
        <div class="col-md-12" style="margin-top:15px;" >
            <div style="margin-bottom:5px;font-weight:bold">
              <h4 style="text-align:center;">Convenciones ordenes:</h4>
              <span style="    display: inline-block;    width: 13px;    height: 13px;    margin-right: 7px;    background-color: rgb(0,143,65);margin-left: 31% "></span>Ejecutada
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 7px;    background-color:blue;"></span>En proceso
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: yellow;"></span>En proceso < 7 días
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: black;"></span>Anulada              
            </div>
        </div>
      </div>


    
    <a href="../../redes/ordenes/ordentrabajo" id="anchorID" style="display:none;" target="_blank"></a>
    <div id="outer_wrapper">
    <div id="inner_wrapper">
        <?php  $arreglo = [];$cambio = 0; $cont = 0;
        $dias = array('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo');
        $mes = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
        ?>
        <?php
            $cont0 = 0;
            $cont1 = 0;
            $cont2 = 0;
            $cont3 = 0;
            $cont4 = 0;


            $sumTotalPro = 0;
            $sumTotalEje = 0;


            $ordenes = [];

            $conAnu = 0;
            $conRep = 0;
            $conAbi = 0;
            $conPro = 0;
            $conEje = 0;



            $sumPro = 0;
            $sumEje = 0;
        ?>
        @foreach($proyectos as $key => $valor)
            <?php
                $fecha_limite = trim(explode(" ",$valor->fecha_ejecucion)[0]);
                $exist= 0;
                $existO= 0;
                for ($i=0; $i < count($arreglo); $i++) { 
                    if($arreglo[$i] == $fecha_limite)
                        $exist= 1;
                }
                
                if($exist == 0)
                {               
                       
                    array_push($arreglo, $fecha_limite);
                    if($cont != 0)
                        echo "<div class='datos_add' style='display:none'><p>$cont0</p><p>$cont1</p><p>$cont2</p><p>$cont3</p><p>$cont4</p><p>" . $sumPro. "</p><p>" . $sumEje . "</p></div></div>";

                    $sumPro = 0;
                    $sumEje = 0;   
                }
                $color = "abierta";
                if($valor->id_estado == "A1")
                    $color = "anulada"; //

                if($valor->id_estado == "E2")
                {
                    //Programada
                    $color = "programada"; //
                    $datetime1 = new DateTime(explode(" ",$valor->fecha_ejecucion)[0]);
                    $datetime2 = new DateTime(date("Y-m-d"));
                    $interval = $datetime2->diff($datetime1);
                    $dias =  intval($interval->format('%R%a'));
                    if($dias <=7)
                    {
                      $color = "programadamas";
                    }

                } 

                if($valor->id_estado == "E4" || $valor->id_estado == "C2") //Cerrada
                    $color = "cerrada"; //
            ?>
            
            @if( $exist == 0 )
                <div class="box">
                    <?php  
                        $diaSemana = $dias[date('N', strtotime($fecha_limite)) - 1];
                        $fechaD = $diaSemana . " " . explode("-",$fecha_limite)[2] . " de " . $mes[intval(explode("-",$fecha_limite)[1]) - 1] . " de " . explode("-",$fecha_limite)[0];
                        
                        for ($i=0; $i < count($ordenes); $i++) { 
                            if($ordenes[$i] == $valor->id_orden)
                                $existO= 1;
                        }

                        if($existO == 0)
                           array_push($ordenes, $valor->id_orden);
                        else
                           continue;

                        $cont0 = 0;
                        $cont1 = 0;
                        $cont2 = 0;
                        $cont3 = 0;
                        $cont4 = 0;
           


                        $ordenesO2 = [];
                        
                       foreach ($proyectos as $ke => $va) {
                        
                            if($fecha_limite == trim(explode(" ",$va->fecha_ejecucion)[0]))
                            {
                                $existO2= 0;
                                for ($i=0; $i < count($ordenesO2); $i++) { 
                                    if($ordenesO2[$i] == $va->id_orden)
                                        $existO2= 1;
                                }

                                if($existO2 == 0)
                                   array_push($ordenesO2, $va->id_orden);
                                else
                                   continue;

                            

                                if($va->id_estado == "A1" && $va->fecha_reprogramacion == NULL)//Anuladas
                                   {
                                    $cont0++; 
                                    $conAnu++;
                                   }

                                if($va->fecha_reprogramacion != NULL && $va->id_estado == "A1")//Reprogramadas
                                   {
                                    $cont1++; 
                                    $conRep++;
                                   }

                                if($va->id_estado == "E1")//Abiertas
                                   {
                                    $cont2++; 
                                    $conAbi++;
                                   }

                                if($va->id_estado == "E2")//Programadas
                                   {
                                    $cont3++;
                                    $datetime1 = new DateTime(explode(" ",$valor->fecha_ejecucion)[0]);
                                    $datetime2 = new DateTime(date("Y-m-d"));
                                    $interval = $datetime2->diff($datetime1);
                                    $dias =  intval($interval->format('%R%a'));
                                    if($dias <= 0)
                                        $conPro++;

                                    
                                   }

                                if($va->id_estado == "E4" || $va->id_estado == "C2")//Ejecutadas
                                   {
                                    

                                     $cont4++; 
                                     $conEje++;

                                    
                                }
                            }
                        }

                    ?>

                    <div class="resumen_scrum">
                        <p class="fecha">{{$fechaD}}</p>
                        <p style="font-size:10px;position:relative;    top: -7px;">ANU:<b class="dato_1">{{$cont0}}</b> - REP: <b class="dato_2">{{$cont1}}</b> - ABI: <b class="dato_3">{{$cont2}}</b> - PRO: <b class="dato_3">{{$cont3}}</b> - EJE: <b class="dato_3">{{$cont4}}</b></p>
                        <p style="font-size:10px;position:relative;     font-weight: bold;   top: -18px;">PROGRAMADO: $ {{number_format($sumPro,2)}}</p>
                        <p style="font-size:10px;position:relative;     font-weight: bold;   top: -29px;">EJECUTADO: $ {{number_format($sumEje,2)}}</p>

                        
                    </div>

                    <div class="contenedor_restricciones {{$color}}" data-modal="modal-1" >
                        <div class="datos_columna {{$color}}">
                        </div>
                         <?php
                            $img = "text-document";
                            if($valor->id_estado == "E1")
                                $img = "text-document_abierta";

                            if($valor->id_estado == "E2")
                            {
                                $img = "text-document_pro";
                            }

                            if($valor->id_estado == "E4" || $valor->id_estado == "C2")
                            {
                                $img = "text-document_cerra";
                            }
                        ?>
                        <img class="img_fondo" src="../../img/{{$img}}.png">
                        <p class="title"><b>Proyecto:</b> <span style="color:blue;cursor:pointer;">{{$valor->nombreP}}</span></p>
                        <p class="impacto"><b>Observación:</b>
                            @if(strlen($valor->observaciones) > 40)
                                {{substr($valor->observaciones, 0, 40)}} ...
                            @else
                                {{$valor->observaciones}}
                            @endif
                        </p>
                        <p class="ver_mas" onclick="abrirDatosMas(this);"><b>...</b></p>
                        
                    </div>

                    <div class="datos_extra oculta">
                        <div class="datos_columna {{$color}}">
                        </div>

                         <?php

                            if($valor->id_estado == "E2")
                            {
                                $sumPro = $sumPro + $valor->programado;
                                $sumTotalPro = $sumTotalPro + $valor->programado;
                            }

                            if($valor->id_estado == "E4" || $valor->id_estado == "C2")
                            {
                               $sumPro = $sumPro + $valor->programado;
                               $sumTotalPro = $sumTotalPro + $valor->programado;
                               if($valor->ejecutado != 0)
                               {
                                    $sumEje =  $sumEje + $valor->ejecutado;
                                    $sumTotalEje =  $sumTotalEje + $valor->ejecutado;

                               }
                            }
                        ?>

                        

                        <p style="    color: black;   font-weight: bold;"><b>Valor programado:</b>${{number_format($valor->programado)}}</p>
                        @if($valor->ejecutado != 0)
                            <p style="    color: black;   font-weight: bold;"><b>Valor ejecutado:</b>${{number_format($valor->ejecutado)}}</p>
                        @endif
                        @if(strlen($valor->observaciones) > 40)
                            <p><b>Observación:</b> {{$valor->observaciones}}</p>
                        @endif

                        <?php
                            $estado = "";
                             $colorFont = "white";
                            if($valor->id_estado == "A1")
                            {
                                $color = "anulada";
                                $cont1++;
                            }

                            if($valor->id_estado == "E2")
                            {
                                $color = "programada";
                                $cont2++;

                                

                                $datetime1 = new DateTime(explode(" ",$valor->fecha_ejecucion)[0]);
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime2->diff($datetime1);
                                $dias =  intval($interval->format('%R%a'));
                                if($dias <=7)
                                {
                                  $color = "programadamas";
                                   $colorFont = "black";
                                }

                            }

                            if($valor->id_estado == "E1")
                            {
                                $color = "abierta";
                                $cont0++;
                            }

                            if($valor->id_estado == "E4" || $valor->id_estado == "C2")
                            {

                                

                                $color = "cerrada";

                                $cont0++;

                            }
                        ?>
                        
                        <p class="title"><b>OT:</b> <span onclick="verOT('{{$valor->id_orden}}')" style="color:blue;cursor:pointer;">{{$valor->id_orden}}</span></p>
                        <p><b>WBS:</b> {{$valor->wbs_utilzadas}}</p>
                        <p><b>NODOS:</b> {{$valor->nodos_utilizados}}</p>
                        <p><b>GOM:</b> {{$valor->gom}}</p>
                        <p><b>DESCARGO:</b> {{($valor->descargo == 0 ? '' : $valor->descargo)}}</p>
                        <p><b>Móvil:</b>{{$valor->id_movil}}</p>
                        <p><b>Estado:</b> <span class="{{$color}}" style="color:{{$colorFont}};padding:3px;">{{$valor->nombreE}}</span></p>
                        <div class="scroll_table">
                          <table>
                            <thead>
                                <tr>
                                    <th>Líder</th>
                                    <th>Nombre</th>
                                    <th>H.Ini</th>
                                    <th>H.Fin</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proyectos as $k => $v)
                                    @if($v->id_orden == $valor->id_orden)
                                        <tr>            
                                            <td>{{$v->id_lider}}</td>
                                            <td>{{$v->nombre}}</td>
                                            <td>{{$v->hora_ini}}</td>
                                            <td>{{$v->hora_fin}}</td>
                                            <td>{{$v->tipoC}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>   
                        </div>

                        @if($valor->fecha_reprogramacion != null)
                            <p> <span  style="color:white;padding:3px;background-color:#e62222;margin-top:3px;display:inline-block">ORDEN REPROGRAMADA</span></p>
                            <p><b>Fecha reprogramación:</b></p>
                            <p>{{$valor->fecha_reprogramacion}}</p>
                            <p><b>Reprogramada por:</b><span onclick="verOT('{{$valor->orden_nueva_reprogra}}')" style="color:blue;cursor:pointer;">{{$valor->orden_nueva_reprogra}}</span></p>
                        @endif
                        

                    </div>
            @else
                <?php

                for ($i=0; $i < count($ordenes); $i++) { 
                    if($ordenes[$i] == $valor->id_orden)
                        $existO= 1;
                }

                if($existO == 0)
                    array_push($ordenes, $valor->id_orden);
                else
                    continue;
                ?>  
                <div class="contenedor_restricciones {{$color}}" data-modal="modal-1" >
                    <div class="datos_columna {{$color}}"> </div>
                    <?php
                            $img = "text-document";
                            if($valor->id_estado == "E1")
                                $img = "text-document_abierta";

                            if($valor->id_estado == "E2")
                            {
                                $img = "text-document_pro";
                            }

                            if($valor->id_estado == "E4" || $valor->id_estado == "C2")
                            {
                                $img = "text-document_cerra";
                            }
                        ?>
                        <img class="img_fondo" src="../../img/{{$img}}.png">
                        <p class="title"><b>Proyecto:</b> <span style="color:blue;cursor:pointer;">{{$valor->nombreP}}</span></p>
                        <p class="impacto"><b>Observación:</b>
                       @if(strlen($valor->observaciones) > 40)
                                {{substr($valor->observaciones, 0, 40)}} ...
                        @else
                            {{$valor->observaciones}}
                        @endif
                    </p>
                    <p class="ver_mas" onclick="abrirDatosMas(this);"><b>...</b></p>
                </div>

                <div class="datos_extra oculta">
                        <div class="datos_columna {{$color}}">
                        </div>

                        <?php

                            if($valor->id_estado == "E2")
                            {
                                $sumPro = $sumPro + $valor->programado;
                                $sumTotalPro = $sumTotalPro + $valor->programado;
                            }

                            if($valor->id_estado == "E4" || $valor->id_estado == "C2")
                            {
                               $sumPro = $sumPro + $valor->programado;
                               $sumTotalPro = $sumTotalPro + $valor->programado;
                               if($valor->ejecutado != 0)
                               {
                                    $sumEje =  $sumEje + $valor->ejecutado;
                                    $sumTotalEje =  $sumTotalEje + $valor->ejecutado;

                               }
                            }
                        ?>

                        <p style="    color: black;   font-weight: bold;"><b>Valor programado:</b>${{number_format($valor->programado)}}</p>
                        @if($valor->ejecutado != 0)
                            <p style="    color: black;   font-weight: bold;"><b>Valor ejecutado:</b>${{number_format($valor->ejecutado)}}</p>
                        @endif
                        @if(strlen($valor->observaciones) > 40)
                            <p><b>Observación:</b> {{$valor->observaciones}}</p>
                        @endif

                        <?php
                            $estado = "";

                            $colorFont = "white";
                            if($valor->id_estado == "A1")
                            {
                                $color = "anulada";
                                $cont1++;
                            }

                            if($valor->id_estado == "E2")
                            {
                                $color = "programada";
                                $cont2++;

                                

                                $datetime1 = new DateTime(explode(" ",$valor->fecha_ejecucion)[0]);
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime2->diff($datetime1);
                                $dias =  intval($interval->format('%R%a'));
                                if($dias <=7)
                                {
                                  $color = "programadamas";
                                   $colorFont = "black";
                                }
                            }

                            if($valor->id_estado == "E1")
                            {
                                $color = "abierta";
                                $cont0++;
                            }

                            if($valor->id_estado == "E4" || $valor->id_estado == "C2")
                            {
                                $color = "cerrada";
                                $cont0++;

                                


                            }
                        ?>
                        
                        <p class="title"><b>OT:</b> <span onclick="verOT('{{$valor->id_orden}}')" style="color:blue;cursor:pointer;">{{$valor->id_orden}}</span></p>
                        <p><b>WBS:</b> {{$valor->wbs_utilzadas}}</p>
                        <p><b>NODOS:</b> {{$valor->nodos_utilizados}}</p>
                        <p><b>GOM:</b> {{$valor->gom}}</p>
                        <p><b>DESCARGO:</b> {{($valor->descargo == 0 ? '' : $valor->descargo)}}</p>
                        <p><b>Móvil:</b>{{$valor->id_movil}}</p>
                        <p><b>Estado:</b> <span class="{{$color}}" style="color:{{$colorFont}};padding:3px;">{{$valor->nombreE}}</span></p>
                        <div class="scroll_table">
                          <table>
                            <thead>
                                <tr>
                                    <th>Líder</th>
                                    <th>Nombre</th>
                                    <th>H.Ini</th>
                                    <th>H.Fin</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proyectos as $k => $v)
                                    @if($v->id_orden == $valor->id_orden)
                                        <tr>            
                                            <td>{{$v->id_lider}}</td>
                                            <td>{{$v->nombre}}</td>
                                            <td>{{$v->hora_ini}}</td>
                                            <td>{{$v->hora_fin}}</td>
                                            <td>{{$v->tipoC}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>   
                        </div>
                        

                    </div>


            @endif
            <?php  $cont++; ?>
        @endforeach
        <?php
            $res1 = intval($conEje);
            $res3 = intval($conPro);
            $sum = $res3 + $res1;
            if($sum == 0)
              $sum = 1;
        ?>
        <input type="hidden" id="ppc_dato" value="{{($res1 / $sum * 100)}}"/>
        

        <!-- more boxes here -->
    </div>
</div>
    </main> 
    @section('js')
        <script type="text/javascript" src="../../js/classie.js"></script>
        <script type="text/javascript" src="../../js/modalEffects.js"></script>

        <script>
            // this is important for IEs
            var polyfilter_scriptpath = '/js/';
        </script>

        <script type="text/javascript" src="../../js/cssParser.js"></script>
        <script type="text/javascript" src="../../js/css-filters-polyfill.js"></script>
        
    @stop

    
    
    <script type="text/javascript">

        window.addEventListener('load',inigant);
        function inigant()
        {

             if(localStorage.getItem('filter-pry-fIni') != null &&  localStorage.getItem('filter-pry-fIni') != "")
            {
               document.querySelector("#fecha_inicio").value =  localStorage.getItem('filter-pry-fIni');
               document.querySelector("#fecha_corte").value =  localStorage.getItem('filter-pry-fFin');
               document.querySelector("#id_tipo").value =  localStorage.getItem('filter-pry-tipo');
            }

            document.querySelector("#datoPPC").innerHTML =   parseFloat(document.querySelector("#ppc_dato").value.toString()).toFixed(2) + " %";
            var hijos = document.querySelector("#inner_wrapper").children.length;
            //document.querySelector("#outer_wrapper").style.width = (parseInt(hijos) * 900) + "px";
            document.querySelector("#inner_wrapper").style.width = (parseInt(hijos) * 258) + "px";

            var ele = $(".datos_add");
            var totalPro = 0;
            var totalEje = 0;
            for (var i = 0; i < ele.length; i++) {
                totalPro = totalPro + parseFloat(ele[i].children[5].innerHTML.replace(",",".").replace(",",".").replace(",","."));
                totalEje = totalEje + parseFloat(ele[i].children[6].innerHTML.replace(",",".").replace(",",".").replace(",","."));

                ele[i].parentElement.children[0].children[2].innerHTML = "PROGRAMADO:" + format2(parseFloat(ele[i].children[5].innerHTML), "$");
                ele[i].parentElement.children[0].children[3].innerHTML = "EJECUTADO:" +  format2(parseFloat(ele[i].children[6].innerHTML), "$");
            };
            
            document.querySelector("#valor_pro").innerHTML = format2(totalPro, "$");
            document.querySelector("#valor_eje").innerHTML = format2(totalEje, "$");
            
            
          
            ocultarSincronizacionFondoBlanco();
        }

        function format2(n, currency) {
            return currency + " " + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
        }

        function abrirDatosMas(ele)
        {
            $(ele.parentElement.nextElementSibling).toggleClass("oculta");
            $(ele).toggleClass("ver_mas_select");
        }

        function verOT(orden)
        {
            var datos = 
                {
                    opc : 2,
                    ot : orden
                };
            consultaAjax("../../guardarProyecto",datos,20000,"POST",1);
        }

         function consultaAjax(route,datos,tiempoEspera,type,opcion,collback,dato)
        {
            mostrarSincronizacion();
            $.ajax({
                url: route,
                type: type,
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:datos,
                timeout:tiempoEspera,
                success:function(data)
                {
                    if(opcion == 1) //Cambio orden
                        $("#anchorID")[0].click();
                    

                    if(opcion == 2) //Consulta proyecto
                    {
                        var html = "";

                        if(data.length == 0)
                        {
                            html += "<tr>";
                            html += "<td colspan='4'>No existen proyectos</td>";
                            html += "</tr>";
                        }
                        else
                        {
                            for (var i = 0; i < data.length; i++) {
                                html += "<tr>";
                                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarProyectoFilter(this)' data-cod='" + data[i].codigo + "'></i></td>";
                                html += "<td>" + data[i].nombre + "</td>";
                                html += "<td>" + data[i].id_proyecto + "</td>";
                                html += "</tr>";
                            };
                        }
                        
                        $("#tbl_recu_add1").html(html);

                    }

                    if(opcion == 3) //Consulta cuadrillas
                    {
                        var html = "";

                        if(data.length == 0)
                        {
                            html += "<tr>";
                            html += "<td colspan='4'>No existen responsables</td>";
                            html += "</tr>";

                        }
                        else
                        {
                            for (var i = 0; i < data.length; i++) {
                                html += "<tr>";
                                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarResponFilter(this)' data-cod='" + data[i].codigo + "'></i></td>";
                                html += "<td>" + data[i].id_movil + "</td>";
                                html += "<td>" + data[i].id_lider + "</td>";
                                html += "<td>" + data[i].nombres + " " + data[i].apellidos + "</td>";
                                html += "</tr>";
                            };
                        }
                        
                        $("#tbl_recu_add2").html(html);
                    }
                    ocultarSincronizacion();
                },
                error:function(request,status,error){
                    ocultarSincronizacion();
                    //$('#filter_registro').modal('toggle');
                    
                    /*mostrarModal(1,null,"Consulta de Alianzas","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);*/
                    setTimeout(function()
                    {
                        //location.reload();
                    },3000);
                }
                    
            });
        }

        function abrirModal(opc)
        {
            if(opc == 1)
            {
                $("#modal_proyecto").modal("toggle");
            }

            if(opc == 2)
            {
                $("#modal_reponsable").modal("toggle");   
            }
        }

        function consultaResponsable()
        {
            if(document.querySelector("#txt_movil").value == "" &&
                document.querySelector("#txt_cedula").value == "" &&
                document.querySelector("#txt_nombre_lider").value == "")
            {
                mostrarModal(1,null,"Consulta cuadrilla","Tiene ingresar datos para consultar cuadrillas.\n",0,"Aceptar","",null);
                return;
            }
            var datos = 
                {
                    opc : 27,
                    movil : document.querySelector("#txt_movil").value,
                    ced : document.querySelector("#txt_cedula").value,
                    nomb : document.querySelector("#txt_nombre_lider").value,
                };
            consultaAjax("../../consultaActiMate",datos,20000,"POST",3);
        }

        function consultaProyecto()
        {
            if(document.querySelector("#txt_nombre_proyecto").value == "" && document.querySelector("#txt_cod_proyecto").value == "")
            {
                mostrarModal(1,null,"Consulta proyectos","Debe ingresar información, en alguno de los parámetros de búsqueda.\n",0,"Aceptar","",null);
                return;
            }
            var datos = 
                {
                    opc : 25,
                    nom : document.querySelector("#txt_nombre_proyecto").value,
                    pro : document.querySelector("#txt_cod_proyecto").value
                };
            consultaAjax("../../consultaActiMate",datos,20000,"POST",2);
        }

        function agregarProyectoFilter(ele)
        {
            document.querySelector("#id_proyecto").value =  ele.parentElement.parentElement.children[2].innerHTML;
            $("#modal_proyecto").modal("toggle");  
        }

        function agregarResponFilter(ele)
        {
            document.querySelector("#id_responsable").value =  ele.parentElement.parentElement.children[2].innerHTML; 
            $("#modal_reponsable").modal("toggle");    
        }

        function salir(opc)
        {
            if(opc == 1)
                $("#modal_proyecto").modal("toggle");  

            if(opc == 2)
                $("#modal_reponsable").modal("toggle");  
        }

        function limpiar1(opc)
        {
            if(opc == 1)
            {
                document.querySelector("#id_proyecto").value = "";
            }else{
                document.querySelector("#id_responsable").value = "";
            }
        }

        function consultaFiltroProyecto()
        {
            localStorage.setItem('filter-pry-fIni',document.querySelector("#fecha_inicio").value);
            localStorage.setItem('filter-pry-fFin',document.querySelector("#fecha_corte").value);
            localStorage.setItem('filter-pry-tipo',document.querySelector("#id_tipo").value);

        }

        //<button class="md-trigger" data-modal="modal-1">Fade in &amp; Scale</button>
    </script>

@stop

