@extends('template.index')

@section('title')
    Kanban Restricciones
@stop

@section('title-section')
    Kanban Restricciones
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
        font-weight: bold;
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
        margin-top: 4px;
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
    
    .contenedor_restricciones.cerrada p
    {
        color:gray;
    }

    .proceso
    {
        background-color: blue;
    }
    
    .contenedor_restricciones.proceso
    {
        color:gray;
    }

    .procesomas
    {
        background-color: yellow;
    }
    
    .contenedor_restricciones.procesomas
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
        background-color: rgb(255, 239, 223);
    }

    .contenedor_restricciones.anulada
    {
        background-color: rgba(0, 0, 0, 0.09);
    }

    .contenedor_restricciones.abierta
    {
        background-color: rgba(255, 0, 0, 0.09);
    }

    .contenedor_restricciones.proceso
    {
        background-color: rgba(0, 0, 255, 0.12);
    }

    .contenedor_restricciones.procesomas
    {
        background-color: rgba(255, 255, 0, 0.2);
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
            width: 134px;    height: 46px;    position: absolute;       top: 95px;    right: 0px;
        margin-right: 20px;
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
        width: 41px;
        opacity: 0.2;
        margin-top: 16px;
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

    </style>
    <main>

    @include('proyectos.redes.trabajoprogramado.modal.modalconsultaproyectos')
    @include('proyectos.redes.trabajoprogramado.modal.modalconsultaresponsable')

    <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
        @include('proyectos.redes.trabajoprogramado.secciones.filterScrumRestricciones')
    </div>
    <div class="row">
        <div class="col-md-12" style="margin-top:15px;" >
            <div style="margin-bottom:5px;font-weight:bold">
              <h4 style="text-align:center;">Convenciones restricciones:</h4>
              <span style="    display: inline-block;    width: 13px;    height: 13px;    margin-right: 7px;    background-color: rgb(0,143,65);margin-left: 31% "></span>Levantada
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 7px;    background-color:blue;"></span>En proceso
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: yellow;"></span>En proceso < 7 días
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: red;"></span>Por iniciar
              <span style="display: inline-block;    width: 13px;    height: 13px;    margin-right: 3px;    background-color: black;"></span>Anulada              
            </div>
        </div>
      </div>
      
    <div class="md-modal md-effect-1" id="modal-1">
        <div class="md-content" id="caja_elemento">
            <h3>Restricción</h3>
            <div>
                <p id="nombre_restriccion" style="font-size:25px;">This is a modal window. You can do the following things with it:</p>
                <ul>
                    <li><strong>Proyecto:  </strong> <span id="txt0"></span></li>
                    <li id="ele0"><strong>Orden de trabajo:  </strong> <span id="txt00"></span></li>
                    <li><strong>Estado:  </strong> <span id="txt1"></span></li>
                    <li><strong>Fecha límite:  </strong><span id="txt2"></span></li>
                    <li><strong>Impacto  :</strong><span id="txt3"></span></li>
                    <li><strong>Responsable:  </strong><span id="txt4"></span></li>
                    <li id="ele1"><strong>Fecha de cierre:  </strong><span id="txt5"></span></li>
                    <li id="ele2"><strong>Evidencia:  </strong><span id="txt6"><a href="http://www.google.com">Ver</a></span></li>
                </ul>
                <button class="md-close">Cerrar</button>
            </div>
        </div>
    </div>
    
    <div class="md-overlay" id="overlay"></div><!-- the overlay element -->

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
        ?>
        @foreach($proyectos as $key => $valor)
            <?php
                

                $fecha_limite = trim(explode(" ",$valor->fecha_limite)[0]);
                $exist= 0;
                for ($i=0; $i < count($arreglo); $i++) { 
                    if($arreglo[$i] == $fecha_limite)
                        $exist= 1;
                }
                
                if($exist == 0)
                {
                    array_push($arreglo, $fecha_limite);
                    if($cont != 0)
                    {
                        echo "<div class='datos_add' style='display:none'><p>$cont0</p><p>$cont1</p><p>$cont2</p><p>$cont3</p></div></div>";
                        $cont0 = 0; //POR INICIAR
                        $cont1 = 0; //ANULADAS
                        $cont2 = 0; //LEVANTADAS
                        $cont3 = 0; //EN PROCE
                    }
                }

                $color = "abierta";
                if($valor->id_estado == "X")
                    $color = "anulada"; //

                if($valor->id_estado == "C")
                    $color = "cerrada"; //

                if($valor->id_estado == "P")
                {
                    $datetime1 = new DateTime($fecha_limite);
                    $datetime2 = new DateTime(date("Y-m-d"));
                    $interval = $datetime2->diff($datetime1);
                    $dias =  intval($interval->format('%R%a'));

                    $color = "proceso";
                    if($dias <=7)
                      $color = "procesomas";

                }
            ?>
            
            @if( $exist == 0 )
                <div class="box">
                    <?php  
                        $diaSemana = $dias[date('N', strtotime($fecha_limite)) - 1];
                        $fechaD = $diaSemana . " " . explode("-",$fecha_limite)[2] . " de " . $mes[intval(explode("-",$fecha_limite)[1]) - 1] . " de " . explode("-",$fecha_limite)[0];
                    ?>

                    <div class="resumen_scrum">
                        <p class="fecha">{{$fechaD}}</p>
                        <p style="font-size:10px;">POR INICIAR:<b class="dato_1"></b> - EN PROCESO: <b class="dato_2"></b> - ANULADAS: <b class="dato_3"></b> - LEVANTADAS: <b class="dato_4"></b></p>
                    </div>

                    <div class="contenedor_restricciones {{$color}}" data-modal="modal-1"  data-fechalim="{{$fecha_limite}}" data-rest="{{$valor->texto_restriccion1}}"
                     data-impa="{{$valor->impacto}}" data-respon="{{$valor->responsable}}" data-esta="{{$valor->id_estado}}"
                      data-fechaC="{{$valor->fecha_cierre}}" data-evidencia="{{$valor->evidencia_cierre}}" data-pry="{{$valor->nombre}}" data-ot="{{$valor->id_orden}}">
                        <div class="datos_columna {{$color}}">
                        </div>
                        <?php
                            $imagen = "forbidden-sign";
                            if($valor->texto_restriccion == "T01")
                                $imagen = "text-document";

                            if($valor->texto_restriccion == "T02")
                                $imagen = "circuit-board";

                            if($valor->texto_restriccion == "T03")
                                $imagen = "planta";

                            if($valor->texto_restriccion == "T04")
                                $imagen = "icu-monitor";

                            if($valor->texto_restriccion == "T05")
                                $imagen = "crane";

                            if($valor->texto_restriccion == "T06")
                                $imagen = "pantone";

                            if($valor->texto_restriccion == "T07")
                                $imagen = "wrench";

                            if($valor->texto_restriccion == "T08")
                                $imagen = "usb-cable";

                            if($valor->texto_restriccion == "T09")
                                $imagen = "manager";

                        ?>
                        @if($valor->texto_restriccion == "T01")
                            <img class="img_fondo" src="../../img/{{$imagen}}.png" style="    width: 35px;    top: -8px;">
                        @else
                            <img class="img_fondo" src="../../img/{{$imagen}}.png">
                        @endif
                        <p class="title">Restricción: {{$valor->texto_restriccion1}}</p>
                        <p class="impacto"><b>Impacto:</b>
                            @if(strlen($valor->impacto) > 83)
                                {{substr($valor->impacto, 0, 83)}} ...
                            @else
                                {{$valor->impacto}}
                            @endif
                        </p>
                        <p class="ver_mas" onclick="abrirDatosMas(this);"><b>...</b></p>
                        
                    </div>

                    <div class="datos_extra oculta">
                        <div class="datos_columna {{$color}}">
                        </div>
                        @if(strlen($valor->impacto) > 83)
                            <p>Impacto: {{$valor->impacto}}</p>
                        @endif
                        <?php
                            $estado = "";
                            $colorTexto = "white";
                            if($valor->id_estado == "X")
                            {
                                $estado = "ANULADA";
                                $color = "anulada";
                                $cont1++;
                            }

                            if($valor->id_estado == "C")
                            {
                                $estado = "LEVANTADA";
                                $color = "cerrada";
                                $cont2++;
                            }

                            if($valor->id_estado == "A")
                            {
                                $estado = "POR INICIAR";
                                $color = "abierta";
                                $cont0++;
                            }

                            if($valor->id_estado == "P")
                            {
                                $estado = "EN PROCESO";
                                $color = "proceso";
                                $cont3++;

                                $datetime1 = new DateTime($fecha_limite);
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime2->diff($datetime1);
                                $dias =  intval($interval->format('%R%a'));
                                
                                if($dias <=7)
                                {
                                    $estado = "EN PROCESO < 7 DÍAS";
                                    $color = "procesomas";
                                    $colorTexto = "black";
                                }

                                  

                            }
                        ?>
                        <p>Proyecto: {{$valor->nombre}}</p>
                        @if($valor->id_orden != "" && $valor->id_orden != null)
                            <p>Orden de trabajo: <span onclick="verOT('{{$valor->id_orden}}')" style="color:blue;cursor:pointer;">{{$valor->id_orden}}</span></p>
                        @endif
                        <p>Estado: <span class="{{$color}}" style="color:{{$colorTexto}};padding:3px;">{{$estado}}</span></p>
                        <p>Fecha límite: {{$fecha_limite}}</p>
                        <p>Responsable: {{$valor->responsable}}</p>
                        @if($valor->id_estado == "C")
                            <p>Fecha cierre: {{$valor->fecha_cierre}}</p>
                            <p>Evidencia: <a target="blank_" href="http://201.217.195.43{{$valor->evidencia_cierre}}">Ver</a>   </p>
                        @endif
                        @if($valor->id_estado == "A" || $valor->id_estado == "P")
                            <?php $orden_r = "0";?>
                            @if($valor->id_orden != "" && $valor->id_orden != null)
                                <?php $orden_r =  $valor->id_orden;?>
                            @endif
                            <a target="_blank" href="../../cerrarresticciones/{{$valor->id_proyecto}}/{{$orden_r}}/{{$valor->id_restriccion}}" class="btn_cerrar">Cerrar restricción</a>
                        @endif
                    </div>
            @else
                <div class="contenedor_restricciones {{$color}}" data-modal="modal-1" data-fechalim="{{$fecha_limite}}" data-rest="{{$valor->texto_restriccion1}}"
                     data-impa="{{$valor->impacto}}" data-respon="{{$valor->responsable}}" data-esta="{{$valor->id_estado}}"
                      data-fechaC="{{$valor->fecha_cierre}}" data-evidencia="{{$valor->evidencia_cierre}}" data-pry="{{$valor->nombre}}" data-ot="{{$valor->id_orden}}">
                        <div class="datos_columna {{$color}}"> </div>
                    <?php
                            $imagen = "forbidden-sign";
                            if($valor->texto_restriccion == "T01")
                                $imagen = "text-document";

                            if($valor->texto_restriccion == "T02")
                                $imagen = "circuit-board";

                            if($valor->texto_restriccion == "T03")
                                $imagen = "planta";

                            if($valor->texto_restriccion == "T04")
                                $imagen = "icu-monitor";

                            if($valor->texto_restriccion == "T05")
                                $imagen = "crane";

                            if($valor->texto_restriccion == "T06")
                                $imagen = "pantone";

                            if($valor->texto_restriccion == "T07")
                                $imagen = "pantone";

                            if($valor->texto_restriccion == "T08")
                                $imagen = "wrench";

                            if($valor->texto_restriccion == "T09")
                                $imagen = "usb-cable";

                            if($valor->texto_restriccion == "T10")
                                $imagen = "manager";
                        ?>
                        
                        @if($valor->texto_restriccion == "T01")
                            <img class="img_fondo" src="../../img/{{$imagen}}.png" style="    width: 35px;    top: -8px;">
                        @else
                            <img class="img_fondo" src="../../img/{{$imagen}}.png">
                        @endif
                    <p class="title">Restricción: {{$valor->texto_restriccion1}}</p>
                    <p class="impacto"><b>Impacto:</b>
                        @if(strlen($valor->impacto) > 83)
                            {{substr($valor->impacto, 0, 83)}} ...
                        @else
                            {{$valor->impacto}}
                        @endif
                    </p>
                    <p class="ver_mas" onclick="abrirDatosMas(this);"><b>...</b></p>
                </div>

                <div class="datos_extra oculta">
                        <div class="datos_columna {{$color}}">
                        </div>
                        @if(strlen($valor->impacto) > 83)
                            <p>Impacto: {{$valor->texto_restriccion1}}</p>
                        @endif
                        <?php
                            $estado = "";
                            $colorTexto = "white";
                            if($valor->id_estado == "X")
                            {
                                $estado = "ANULADA";
                                $color = "anulada";
                                $cont1++;
                            }

                            if($valor->id_estado == "C")
                            {
                                $estado = "LEVANTADA";
                                $color = "cerrada";
                                $cont2++;
                            }

                            if($valor->id_estado == "A")
                            {
                                $estado = "POR INICIAR";
                                $color = "abierta";
                                $cont0++;
                            }

                            if($valor->id_estado == "P")
                            {
                                $estado = "EN PROCESO";
                                $color = "proceso";
                                $cont3++;

                                $datetime1 = new DateTime($fecha_limite);
                                $datetime2 = new DateTime(date("Y-m-d"));
                                $interval = $datetime2->diff($datetime1);
                                $dias =  intval($interval->format('%R%a'));
                                if($dias <=7)
                                {
                                    $estado = "EN PROCESO < 7 DÍAS";
                                    $color = "procesomas";
                                    $colorTexto = "black";
                                }
                            }
                        ?>
                        <p>Proyecto: {{$valor->nombre}}</p>
                        @if($valor->id_orden != "" && $valor->id_orden != null)
                            <p>Orden de trabajo: <span onclick="verOT('{{$valor->id_orden}}')" style="color:blue;cursor:pointer;">{{$valor->id_orden}}</span></p>
                        @endif
                        <p>Estado: <span class="{{$color}}" style="color:{{$colorTexto}};padding:3px;">{{$estado}}</span></p>
                        <p>Fecha límite: {{$fecha_limite}}</p>
                        <p>Responsable: {{$valor->responsable}}</p>
                        @if($valor->id_estado == "C")
                            <p>Fecha cierre: {{$valor->fecha_cierre}}</p>
                            <p>Evidencia: 
                            <a target="blank_" href="http://201.217.195.43{{$valor->evidencia_cierre}}">Ver</a>  </p>   
                        @endif
                        @if($valor->id_estado == "A" || $valor->id_estado == "P")
                            <?php $orden_r = "0";?>
                            @if($valor->id_orden != "" && $valor->id_orden != null)
                                <?php $orden_r =  $valor->id_orden;?>
                            @endif
                            <a target="_blank" href="../../cerrarresticciones/{{$valor->id_proyecto}}/{{$orden_r}}/{{$valor->id_restriccion}}" class="btn_cerrar">Cerrar restricción</a>
                        @endif
                    </div>


            @endif
            <?php  $cont++; ?>
        @endforeach


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

            if(localStorage.getItem('filter-res-fIni') != null &&  localStorage.getItem('filter-res-fIni') != "")
            {
               document.querySelector("#fecha_inicio").value =  localStorage.getItem('filter-res-fIni');
               document.querySelector("#fecha_corte").value =  localStorage.getItem('filter-res-fFin');
               document.querySelector("#id_tipo").value =  localStorage.getItem('filter-res-tipo');
            }


            /*var datos = $(".contenedor_restricciones");
            for (var i = 0; i < datos.length; i++) {
                datos[i].addEventListener("click",function()
                { 
                    document.querySelector("#nombre_restriccion").innerHTML = this.dataset.rest;
                    var estato = "ABIERTA";
                    document.querySelector("#caja_elemento").style.backgroundColor = "#e74c3c";
                    if(this.dataset.esta == "X")
                    {
                        estato = "IGNORADA";                    
                        document.querySelector("#caja_elemento").style.backgroundColor = "#b7b4b4";
                    }

                    if(this.dataset.esta == "C")
                    {
                        estato = "CERRADA";                    
                        document.querySelector("#caja_elemento").style.backgroundColor = "#0060AC";
                    }

                    
                    document.querySelector("#txt0").innerHTML =  this.dataset.pry;
                    if(this.dataset.ot == "")
                        document.querySelector("#ele0").style.display = "none";
                    else
                        document.querySelector("#ele0").style.display = "list-item";

                    document.querySelector("#txt00").innerHTML =  this.dataset.ot;
                    document.querySelector("#txt1").innerHTML =  estato;
                    document.querySelector("#txt2").innerHTML =  this.dataset.fechalim;
                    document.querySelector("#txt3").innerHTML =  this.dataset.impa;
                    document.querySelector("#txt4").innerHTML =  this.dataset.respon;
                    

                    if(this.dataset.esta == "C")
                    {
                        document.querySelector("#ele1").style.display = "list-item";
                        document.querySelector("#ele2").style.display = "list-item";
                        document.querySelector("#txt5").innerHTML =  this.dataset.fecha_cierre.split(" ")[0];
                    //document.querySelector("#txt6").innerHTML =  this.dataset.evidencia_cierre;
                    }else{
                        document.querySelector("#ele1").style.display = "none";
                        document.querySelector("#ele2").style.display = "none";
                    }
                    

                    
                });
            };*/

            var hijos = document.querySelector("#inner_wrapper").children.length;
            //document.querySelector("#outer_wrapper").style.width = (parseInt(hijos) * 900) + "px";
            document.querySelector("#inner_wrapper").style.width = (parseInt(hijos) * 258) + "px";
            
            var hijos = $(".box .datos_add");

            for (var i = 0; i < hijos.length; i++) {
                
                hijos[i].parentElement.children[0].children[1].children[0].innerHTML = hijos[i].children[0].innerHTML;
                hijos[i].parentElement.children[0].children[1].children[1].innerHTML = hijos[i].children[3].innerHTML;
                hijos[i].parentElement.children[0].children[1].children[2].innerHTML = hijos[i].children[1].innerHTML;
                hijos[i].parentElement.children[0].children[1].children[3].innerHTML = hijos[i].children[2].innerHTML;
                
                
            };
            ocultarSincronizacionFondoBlanco();
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
                            html += "<td colspan='3'>No existen proyectos</td>";
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

                    if(opcion == 3) //Consulta responable
                    {
                        var html = "";

                        if(data.length == 0)
                        {
                            html += "<tr>";
                            html += "<td colspan='2'>No existen responsables</td>";
                            html += "</tr>";

                        }
                        else
                        {
                            for (var i = 0; i < data.length; i++) {
                                html += "<tr>";
                                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregarResponFilter(this)' data-cod='" + data[i].codigo + "'></i></td>";
                                html += "<td>" + data[i].responsable + "</td>";
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
            if(document.querySelector("#txt_responsable").value == "")
            {
                mostrarModal(1,null,"Consulta responsable","Tiene ingresar datos para consultar el responsable.\n",0,"Aceptar","",null);
                return;
            }
            var datos = 
                {
                    opc : 26,
                    resp : document.querySelector("#txt_responsable").value
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
            document.querySelector("#id_responsable").value =  ele.parentElement.parentElement.children[1].innerHTML; 
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
        
         function consultaFiltroRestricciones()
        {
            localStorage.setItem('filter-res-fIni',document.querySelector("#fecha_inicio").value);
            localStorage.setItem('filter-res-fFin',document.querySelector("#fecha_corte").value);
            localStorage.setItem('filter-res-tipo',document.querySelector("#id_tipo").value);

        }
        
        //<button class="md-trigger" data-modal="modal-1">Fade in &amp; Scale</button>
    </script>
@stop

