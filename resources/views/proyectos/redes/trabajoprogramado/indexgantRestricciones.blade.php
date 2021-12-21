@extends('template.index')

@section('title')
	Gantt Restricciones
@stop

@section('title-section')
    Gantt Restricciones
@stop

@section('css')
    <link rel="stylesheet" href="../../css/dhtmlxgantt.css" type="text/css" media="screen" title="no title" charset="utf-8">

@stop

@section('content')
    <style type="text/css">
    html, body { height: 100%; padding:0px; margin:0px; overflow: hidden; }
    .gantt_task_cell.week_end{
        background-color: #EFF5FD;
    }
    .gantt_task_row.gantt_selected .gantt_task_cell.week_end{
        background-color: #F8EC9C;
    }|

    .gantt_task_line.gantt_dependent_task {
            background-color: red;
            border: 1px solid #3c9445;

    }
    .gantt_task_line.gantt_dependent_task .gantt_task_progress {
        background-color: #46ad51;
    }

    .today
    {
        background-color: rgb(243,132,25);
    }

    /*PRIORIDAD*/
    .high{ /*ABIERTA*/
        border:2px solid red;
        color: #d96c49;
        background: red;
    }
    .high .gantt_task_progress{
        background: red;
    }

    .medium{ /*IGNORADA*/
        border:2px solid black;
        color:#34c461;
        background: black;
    }
    .medium .gantt_task_progress{
        background: black;
    }

    .low{ /*CERRADA*/
        border:2px solid #6ba8e3;
        color:#6ba8e3;
        background: rgb(0,143,65);
    }
    .low .gantt_task_progress{
        background: rgb(0,143,65);
    }


    .process{ /*EN PROCESO*/
        border:2px solid blue;
        color:#6ba8e3;
        background: blue;
    }
    .process .gantt_task_progress{
        background: blue;
    }

    .processmenos{ /*EN PROCESO < días*/
        border:2px solid yellow;
        color:#6ba8e3;
        background: yellow;
    }
    .processmenos .gantt_task_progress{
        background: yellow;
    }


    /*IMPORTANCIA*/
    .important{
        color:#c94e50;
    }


    /*PROGRESOR*/
    .gantt_task_progress{
        text-align:left;
        padding-left:10px;
        box-sizing: border-box;
        color:white;
        font-weight: bold;
    }

    .gantt_grid_head_add,.gantt_link_point,.gantt_task_progress_drag,.gantt_task_drag.task_right,.gantt_task_drag.task_left,.gantt_cal_qi_controls,.gantt_task_content,.gantt_section_time,.gantt_cal_lsection,
    .gantt_btn_set.gantt_left_btn_set.gantt_save_btn_set,
    .gantt_btn_set.gantt_right_btn_set.gantt_delete_btn_set{
        display:none !important;
        }

    .gantt_task_progress span
    {
        position: absolute;
        color:black;
    }

    .gantt_tree_content
    {
        font-size: 10px;
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

    .abierta
    {
        background-color: #0060AC;
    }
    
    .contenedor_restricciones.abierta p
    {
        color:white;
    }

    .anulada
    {
        background-color: #b7b4b4;
    }
    
    .contenedor_restricciones.anulada p
    {
        color:white;
    }

    .cerrada
    {
        background-color: rgb(0,143,65);
    }
    
    .contenedor_restricciones.cerrada p
    {
        color:white;
    }


    </style>
	<main>

    @include('proyectos.redes.trabajoprogramado.modal.modalconsultaproyectos')
    @include('proyectos.redes.trabajoprogramado.modal.modalconsultaresponsable')

    <a href="../../redes/ordenes/ordentrabajo" id="anchorID" style="display:none;" target="_blank"></a>
    <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
        @include('proyectos.redes.trabajoprogramado.secciones.filterGanntRestriccion')
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
      
        <div style="text-align: center;height: 40px;line-height: 40px;">
            <button onclick="toggleMode(this)" type="submit" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                Zoom
            </button>

            <button onclick='gantt.exportToExcel()' type="submit" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                Export Excel
            </button>

            <button onclick='gantt.exportToPDF()' type="submit" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                Export PDF
            </button>


        </div>
    </div>

    

    <div id="gantt_here" style='width:100%; height:75%;'></div>
		<div class="container-fluid">
            <div class="tbl_index_proyectos" id="tbl_index_proyectos" data-pos="0">
                
            </div>
        </div>
	</main>

    @section('js')
        <script type="text/javascript" src="../../js/dhtmlxgantt.js"></script>
        <script type="text/javascript" src="../../js/dhtmlxgantt_marker.js"></script>
        <script type="text/javascript" src="../../js/dhtmlxgantt_quick_info.js"></script>
        <script type="text/javascript" src="../../js/api.js"></script>

    @stop
    <script type="text/javascript">

        window.addEventListener('load',inigant);

        function inigant()
        {
            ocultarSincronizacionFondoBlanco();
            iniciogant();
        }       

        var demo_tasks = {
            "data":[
                <?php  $aux = 1;$arregloP = [];$parent = 0;$cambio = 0;?>
                @for($i = 0; $i < count($proyectos); $i++)
                    <?php 
                    $exis = 0;
                    for ($j=0; $j< count($arregloP); $j++) { 
                        if($arregloP[$j] == $proyectos[$i]['proyecto']->id_proyecto)
                            $exis = 1;
                    }

                    if($exis == 0)
                        array_push($arregloP, $proyectos[$i]['proyecto']->id_proyecto);
                    ?>
                    @if($exis == 0)

                        <?php
                            $total = intval($proyectos[$i]['proyecto']->cantA) + intval($proyectos[$i]['proyecto']->cantX) + intval($proyectos[$i]['proyecto']->cantC);
                            $total = (intval($proyectos[$i]['proyecto']->cantX) + intval($proyectos[$i]['proyecto']->cantC)) / $total;
                        ?>
                        {"id":{{$aux}}, "text":"{{$proyectos[$i]['proyecto']->nombre}}", "start_date":"", "duration":"", "progress": "{{$total}}", "close": true, "opc" : 5,"priority": "0","valor1" : "{{$proyectos[$i]['proyecto']->cantA}}","valor2" : "{{$proyectos[$i]['proyecto']->cantX}}","valor3" : "{{$proyectos[$i]['proyecto']->cantC}}"},
                        <?php  $parent = $aux;
                            $fechaN = explode(" ",$proyectos[$i]['proyecto']->fecha_limite)[0];
                            $fechaN = explode("-",$fechaN);
                            $fechaN = $fechaN[2] . "-" . $fechaN[1] . "-" . $fechaN[0];

                        $estado = $proyectos[$i]['proyecto']->id_estado;
                        $prioridad = 1;
                        if($estado == "C")
                        {
                            $prioridad = 3;
                        }else
                        {
                            if($estado == "X")
                                $prioridad = 2;
                        }   

                        
                        ?>
                        <?php  $aux++;$cambio = 1;?>
                        {"id":{{$aux}}, "text":"{{$proyectos[$i]['proyecto']->texto_restriccion1}}", "start_date":"{{$fechaN}}", "duration":"1", "parent":"{{$parent}}", "progress": 1, "close": true,"texto_alternativo" : "TEXTO 1" ,"priority": "{{$prioridad}}", "opc" : 3, "impacto" : "{{$proyectos[$i]['proyecto']->impacto}}", "respon" : "{{$proyectos[$i]['proyecto']->responsable}}", "esta" : "{{$proyectos[$i]['proyecto']->id_estado}}", "fechaC" : "{{$proyectos[$i]['proyecto']->fecha_cierre}}", "evidencia" : "{{$proyectos[$i]['proyecto']->evidencia_cierre}}","id_orden" : "0", "id_proyecto" : "{{$proyectos[$i]['proyecto']->id_proyecto}}", "r_s" : "{{$proyectos[$i]['proyecto']->id_restriccion}}"},

                    @else
                        <?php  
                            $fechaN = explode(" ",$proyectos[$i]['proyecto']->fecha_limite)[0];
                            $fechaN = explode("-",$fechaN);
                            $fechaN = $fechaN[2] . "-" . $fechaN[1] . "-" . $fechaN[0];

                            $estado = $proyectos[$i]['proyecto']->id_estado;
                            $prioridad = 1;
                            if($estado == "C")
                            {
                                $prioridad = 3;
                            }else
                            {
                                if($estado == "X")
                                    $prioridad = 2;
                            }   

                        ?>
                        {"id":{{$aux}}, "text":"{{$proyectos[$i]['proyecto']->texto_restriccion1}}", "start_date":"{{$fechaN}}", "duration":"1", "parent":"{{$parent}}", "progress": 1, "close": true,"texto_alternativo" : "TEXTO 1" ,"priority": "{{$prioridad}}", "opc" : 3, "impacto" : "{{$proyectos[$i]['proyecto']->impacto}}", "respon" : "{{$proyectos[$i]['proyecto']->responsable}}", "esta" : "{{$proyectos[$i]['proyecto']->id_estado}}", "fechaC" : "{{$proyectos[$i]['proyecto']->fecha_cierre}}", "evidencia" : "{{$proyectos[$i]['proyecto']->evidencia_cierre}}","id_orden" : "0", "id_proyecto" : "{{$proyectos[$i]['proyecto']->id_proyecto}}", "r_s" : "{{$proyectos[$i]['proyecto']->id_restriccion}}"},
                    @endif   

                    <?php  $aux++;?>

                    @if($cambio == 1)
                        <?php  $arregloO = [];$parent1 = 0;$cambio = 0;?>
                        @if(count($proyectos[$i]['maniobra']) > 0)
                            @for($k = 0; $k < count($proyectos[$i]['maniobra']); $k++)
                                <?php  
                                    $exis1 = 0;
                                    for ($j=0; $j< count($arregloO); $j++) { 
                                        if($arregloO[$j] == $proyectos[$i]['maniobra'][$k]->id_orden)
                                            $exis1 = 1;
                                    }
                                    if($exis1 == 0)
                                        array_push($arregloO, $proyectos[$i]['maniobra'][$k]->id_orden);
                                ?>

                                @if($exis1 == 0)
                                    <?php
                                        $total = intval($proyectos[$i]['maniobra'][$k]->cantA) + intval($proyectos[$i]['maniobra'][$k]->cantX) + intval($proyectos[$i]['maniobra'][$k]->cantC);
                                        $total = (intval($proyectos[$i]['maniobra'][$k]->cantX) + intval($proyectos[$i]['maniobra'][$k]->cantC)) / $total;
                                    ?>
                                    {"id":{{$aux}}, "text":"{{$proyectos[$i]['maniobra'][$k]->id_orden}}", "start_date":"", "duration":"", "parent":"{{$parent}}", "progress": "{{$total}}", "close": true, "opc" : 4,"priority": "0", "valor1" : "{{$proyectos[$i]['maniobra'][$k]->cantA}}","valor2" : "{{$proyectos[$i]['maniobra'][$k]->cantX}}","valor3" : "{{$proyectos[$i]['maniobra'][$k]->cantC}}","id_orden" : "{{$proyectos[$i]['maniobra'][$k]->id_orden}}", "id_proyecto" : "{{$proyectos[$i]['proyecto']->id_proyecto}}"},
                                    <?php  $parent1 = $aux;
                                        $fechaN = explode(" ",$proyectos[$i]['maniobra'][$k]->fecha_limite)[0];
                                        $fechaN = explode("-",$fechaN);
                                        $fechaN = $fechaN[2] . "-" . $fechaN[1] . "-" . $fechaN[0];

                                        $estado = $proyectos[$i]['maniobra'][$k]->id_estado;
                                        $prioridad = 1;
                                        if($estado == "C")
                                        {
                                            $prioridad = 3;
                                        }else
                                        {
                                            if($estado == "X")
                                                $prioridad = 2;
                                            else
                                            {
                                                if($estado == "P")
                                                {
                                                    $datetime1 = new DateTime(explode(" ",$proyectos[$i]['maniobra'][$k]->fecha_limite)[0]);
                                                    $datetime2 = new DateTime(date("Y-m-d"));
                                                    $interval = $datetime2->diff($datetime1);
                                                    $dias =  intval($interval->format('%R%a'));

                                                    $prioridad = 4;
                                                    if($dias <=7)
                                                      $prioridad = 5;

                                                }
                                            }
                                        } 

                                    ?>
                                    <?php  $aux++;?>
                                    {"id":{{$aux}}, "text":"{{$proyectos[$i]['maniobra'][$k]->texto_restriccion1}}", "start_date":"{{$fechaN}}", "duration":"1", "parent":"{{$parent1}}", "progress": "1", "close": true ,"priority": "{{$prioridad}}", "opc" : 3 , "impacto" : "{{$proyectos[$i]['maniobra'][$k]->impacto}}", "respon" : "{{$proyectos[$i]['maniobra'][$k]->responsable}}", "esta" : "{{$proyectos[$i]['maniobra'][$k]->id_estado}}", "fechaC" : "{{$proyectos[$i]['maniobra'][$k]->fecha_cierre}}", "evidencia" : "{{$proyectos[$i]['maniobra'][$k]->evidencia_cierre}}","id_orden" : "{{$proyectos[$i]['maniobra'][$k]->id_orden}}", "id_proyecto" : "{{$proyectos[$i]['proyecto']->id_proyecto}}","r_s" : "{{$proyectos[$i]['maniobra'][$k]->id_restriccion}}"},
                                @else
                                    <?php  
                                        $fechaN = explode(" ",$proyectos[$i]['maniobra'][$k]->fecha_limite)[0];
                                        $fechaN = explode("-",$fechaN);
                                        $fechaN = $fechaN[2] . "-" . $fechaN[1] . "-" . $fechaN[0];

                                        $estado = $proyectos[$i]['maniobra'][$k]->id_estado;
                                        $prioridad = 1;
                                        if($estado == "C")
                                        {
                                            $prioridad = 3;
                                        }else
                                        {
                                            if($estado == "X")
                                                $prioridad = 2;
                                            else
                                            {
                                                if($estado == "P")
                                                {
                                                    $datetime1 = new DateTime(explode(" ",$proyectos[$i]['maniobra'][$k]->fecha_limite)[0]);
                                                    $datetime2 = new DateTime(date("Y-m-d"));
                                                    $interval = $datetime2->diff($datetime1);
                                                    $dias =  intval($interval->format('%R%a'));

                                                    $prioridad = 4;
                                                    if($dias <=7)
                                                      $prioridad = 5;

                                                }
                                            }
                                        } 

                                    ?>
                                    {"id":{{$aux}}, "text":"{{$proyectos[$i]['maniobra'][$k]->texto_restriccion1}}", "start_date":"{{$fechaN}}", "duration":"1", "parent":"{{$parent1}}", "progress": 1, "close": true ,"priority": "{{$prioridad}}", "opc" : 3 , "impacto" : "{{$proyectos[$i]['maniobra'][$k]->impacto}}", "respon" : "{{$proyectos[$i]['maniobra'][$k]->responsable}}", "esta" : "{{$proyectos[$i]['maniobra'][$k]->id_estado}}", "fechaC" : "{{$proyectos[$i]['maniobra'][$k]->fecha_cierre}}", "evidencia" : "{{$proyectos[$i]['maniobra'][$k]->evidencia_cierre}}","id_orden" : "{{$proyectos[$i]['maniobra'][$k]->id_orden}}", "id_proyecto" : "{{$proyectos[$i]['proyecto']->id_proyecto}}","r_s" : "{{$proyectos[$i]['maniobra'][$k]->id_restriccion}}"},     
                                @endif

                                <?php  $aux++;?>
                            @endfor
                        @endif
                        <?php  $cambio = 0;?>
                    @endif

                @endfor
            ]
        };


        function iniciogant()
        {

            gantt.templates.task_text = function(s,e,task){
                return "Export " + task.text;
            }
            gantt.config.columns[0].template = function(obj){
                return obj.text + " -";
            }
            

            gantt.message("Try to move or resize task to not working time");

            //Saber fecha de hoy
            var date_to_str = gantt.date.date_to_str(gantt.config.task_date);
            var today = new Date({{(explode('-',$fecha)[0])}}, {{(explode('-',$fecha)[1])}}, {{(explode('-',$fecha)[2])}});

            gantt.addMarker({
                start_date: today,
                css: "today",
                text: "Hoy",
                title:"Hoy: "+ date_to_str(today)
            });

            //Configuración de columnas
            gantt.config.columns=[
                {name:"text",       label:"Nombre de la tarea",  tree:true, width:230, template:myFunc },
                {name:"start_date", label:"Fecha inicio", align: "center" },
                {name:"duration",   label:"Duración",   align: "center" }
            ];          

            //Cambio de color texto proyecto
            function myFunc(task){

                if(task.opc == "4" || task.opc == "5")
                    return task.text.replace("OT000","").replace("OT00","").replace("OT0","").replace("OT","") + " T: " + (parseInt(task.valor1) + parseInt(task.valor2) + parseInt(task.valor3)) +" A: " + task.valor1 + " I: " + task.valor2 + " C: " + task.valor3;
                else
                    return task.text;

                /*if(task.priority ==1)
                    return "<div class='important'>"+task.text+" ("+task.users+") </div>";
                return task.text+" ("+task.users+")";*/
                if(task.priority == "1")
                    return "<div class='important'>"+task.text+" (" + Math.round(task.progress*100) + "%) </div>";

                
                //    return task.text+" (" + Math.round(task.progress*100) + "%)";
                //else
                //    return task.text +" " + task.dato1 + " T: " + (parseInt(task.dato2) + parseInt(task.dato3)) +" P: " + task.dato3 + " E: " + task.dato2;
            };

            //Agregar avance proyecto
            gantt.templates.progress_text = function(start, end, task){
                
                if(task.opc == "4" || task.opc == "5")
                    return "<span style='text-align:left;'>"+Math.round(task.progress*100)+ "% </span>";
                else
                    return "<span style='text-align:left;'></span>";

                //return "<span style='text-align:left;'>"+Math.round(task.progress*100)+ "% </span>";
            };

            gantt.config.work_time = true;
            gantt.config.correct_work_time = true;
            gantt.config.scale_unit = "day";
            gantt.config.date_scale = "%D, %d";
            gantt.config.min_column_width = 60;
            gantt.config.duration_unit = "day";
            gantt.config.scale_height = 20*3;
            gantt.config.row_height = 30;

            gantt.config.lightbox.sections = [
                {name: "description", height: 70, map_to: "text", type: "textarea", focus: true},
                {name: "time", type: "duration", map_to: "auto"}
            ];



            var weekScaleTemplate = function(date){
                var dateToStr = gantt.date.date_to_str("%d %M");
                var weekNum = gantt.date.date_to_str("(semana %W)");
                var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
                return dateToStr(date) + " - " + dateToStr(endDate) + " " + weekNum(date);
            };

            gantt.config.subscales = [
                {unit:"month", step:1, date:"%F, %Y"},
                {unit:"week", step:1, template:weekScaleTemplate}

            ];

            gantt.templates.task_class  = function(start, end, task){
                switch (task.priority){
                    case "1":
                        return "high"; //Abierta
                        break;
                    case "2":
                        return "medium"; //Anulada
                        break;
                    case "3":
                        return "low"; //Cerrada
                        break;
                    case "4":
                        return "process"; //Proceso
                        break;
                    case "5":
                        return "processmenos"; // En proceso menos de 7 días
                        break;
                }
            };

            gantt.templates.task_cell_class = function(task, date){
                if(!gantt.isWorkTime(date))
                    return "week_end";
                return "";
            };

            gantt.attachEvent("onBeforeTaskDrag", function(id, mode, e){
                return false;      //denies dragging if the global task index is odd
                
            });

            gantt.init("gantt_here");
            gantt.parse(demo_tasks);
        }
        
        function toggleMode(toggle) {
            toggle.enabled = !toggle.enabled;
            if (toggle.enabled) {
                toggle.innerHTML = "Normal";
                //Saving previous scale state for future restore
                saveConfig();
                zoomToFit();
            } else {

                toggle.innerHTML = "Zoom";
                //Restore previous scale state
                restoreConfig();
                gantt.render();
            }
        }

        var cachedSettings = {};
        function saveConfig() {
            var config = gantt.config;
            cachedSettings = {};
            cachedSettings.scale_unit = config.scale_unit;
            cachedSettings.date_scale = config.date_scale;
            cachedSettings.step = config.step;
            cachedSettings.subscales = config.subscales;
            cachedSettings.template = gantt.templates.date_scale;
            cachedSettings.start_date = config.start_date;
            cachedSettings.end_date = config.end_date;
        }
    
        function restoreConfig() {
            applyConfig(cachedSettings);
        }

        function applyConfig(config, dates) {
            gantt.config.scale_unit = config.scale_unit;
            if (config.date_scale) {
                gantt.config.date_scale = config.date_scale;
                gantt.templates.date_scale = null;
            }
            else {
                gantt.templates.date_scale = config.template;
            }

            gantt.config.step = config.step;
            gantt.config.subscales = config.subscales;

            if (dates) {
                gantt.config.start_date = gantt.date.add(dates.start_date, -1, config.unit);
                gantt.config.end_date = gantt.date.add(gantt.date[config.unit + "_start"](dates.end_date), 2, config.unit);
            } else {
                gantt.config.start_date = gantt.config.end_date = null;
            }
        }



        function zoomToFit() {
            var project = gantt.getSubtaskDates(),
                    areaWidth = gantt.$task.offsetWidth;

            for (var i = 0; i < scaleConfigs.length; i++) {
                var columnCount = getUnitsBetween(project.start_date, project.end_date, scaleConfigs[i].unit, scaleConfigs[i].step);
                if ((columnCount + 2) * gantt.config.min_column_width <= areaWidth) {
                    break;
                }
            }

            if (i == scaleConfigs.length) {
                i--;
            }

            applyConfig(scaleConfigs[i], project);
            gantt.render();
     }

        function getUnitsBetween(from, to, unit, step) {
            var start = new Date(from),
                    end = new Date(to);
            var units = 0;
            while (start.valueOf() < end.valueOf()) {
                units++;
                start = gantt.date.add(start, step, unit);
            }
            return units;
        }

        //Setting available scales
    var scaleConfigs = [
        // minutes
        { unit: "minute", step: 1, scale_unit: "hour", date_scale: "%H", subscales: [
            {unit: "minute", step: 1, date: "%H:%i"}
        ]
        },
        // hours
        { unit: "hour", step: 1, scale_unit: "day", date_scale: "%j %M",
            subscales: [
                {unit: "hour", step: 1, date: "%H:%i"}
            ]
        },
        // days
        { unit: "day", step: 1, scale_unit: "month", date_scale: "%F",
            subscales: [
                {unit: "day", step: 1, date: "%j"}
            ]
        },
        // weeks
        {unit: "week", step: 1, scale_unit: "month", date_scale: "%F",
            subscales: [
                {unit: "week", step: 1, template: function (date) {
                    var dateToStr = gantt.date.date_to_str("%d %M");
                    var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
                    return dateToStr(date) + " - " + dateToStr(endDate);
                }}
            ]},
        // months
        { unit: "month", step: 1, scale_unit: "year", date_scale: "%Y",
            subscales: [
                {unit: "month", step: 1, date: "%M"}
            ]},
        // quarters
        { unit: "month", step: 3, scale_unit: "year", date_scale: "%Y",
            subscales: [
                {unit: "month", step: 3, template: function (date) {
                    var dateToStr = gantt.date.date_to_str("%M");
                    var endDate = gantt.date.add(gantt.date.add(date, 3, "month"), -1, "day");
                    return dateToStr(date) + " - " + dateToStr(endDate);
                }}
            ]},
        // years
        {unit: "year", step: 1, scale_unit: "year", date_scale: "%Y",
            subscales: [
                {unit: "year", step: 5, template: function (date) {
                    var dateToStr = gantt.date.date_to_str("%Y");
                    var endDate = gantt.date.add(gantt.date.add(date, 5, "year"), -1, "day");
                    return dateToStr(date) + " - " + dateToStr(endDate);
                }}
            ]},
        // decades
        {unit: "year", step: 10, scale_unit: "year", template: function (date) {
            var dateToStr = gantt.date.date_to_str("%Y");
            var endDate = gantt.date.add(gantt.date.add(date, 10, "year"), -1, "day");
            return dateToStr(date) + " - " + dateToStr(endDate);
        },
            subscales: [
                {unit: "year", step: 100, template: function (date) {
                    var dateToStr = gantt.date.date_to_str("%Y");
                    var endDate = gantt.date.add(gantt.date.add(date, 100, "year"), -1, "day");
                    return dateToStr(date) + " - " + dateToStr(endDate);
                }}
            ]}
    ];

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

        function verOT(ele)
        {
            var datos = 
                {
                    opc : 2,
                    ot : ele.dataset.ot
                };
            consultaAjax("../../guardarProyecto",datos,20000,"POST",1);
        }



    </script>
@stop

