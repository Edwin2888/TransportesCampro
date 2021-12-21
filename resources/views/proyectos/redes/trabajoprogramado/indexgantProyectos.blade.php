@extends('template.index')

@section('title')
	Gantt Proyectos
@stop

@section('title-section')
    Gantt Proyectos
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
    }

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
    .high{
        border:2px solid #d96c49;
        color: #d96c49;
        background: #d96c49;
    }
    .high .gantt_task_progress{
        background: #db2536;
    }

    .medium{
        border:2px solid #34c461;
        color:#34c461;
        background: #34c461;
    }
    .medium .gantt_task_progress{
        background: #23964d;
    }

    .low{
        border:2px solid #6ba8e3;
        color:#6ba8e3;
        background: #6ba8e3;
    }
    .low .gantt_task_progress{
        background: #547dab;
    }

    /*IMPORTANCIA*/
    .important{
        color:red;
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

    </style>
	<main>

    <a href="../../redes/ordenes/ordentrabajo" id="anchorID" style="display:none;" target="_blank"></a>

    <div class="side-body" id="cuerpo" style="margin-top:20px;margin-left:0px;">
        @include('proyectos.redes.trabajoprogramado.secciones.filterGanntProyectos')
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
      
        <div style="text-align: center;height: 40px;line-height: 40px;">
            <button onclick="toggleMode(this)" type="submit" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" >
                Zoom
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

            ocultarSincronizacionFondoBlanco();
            iniciogant();
        }

        /*var demo_tasks = {
            "data":[
                {"id":11, "text":"Project #1", "start_date":"28-03-2013", "duration":"11", "progress": 0.6, "open": true},
                {"id":1, "text":"Project #2", "start_date":"01-04-2013", "duration":"18", "progress": 0.4, "open": true},

                {"id":2, "text":"Task #1", "start_date":"02-04-2013", "duration":"8", "parent":"1", "progress":0.5, "open": true},
                {"id":3, "text":"Task #2", "start_date":"11-04-2013", "duration":"8", "parent":"1", "progress": 0.6, "open": true},
                {"id":4, "text":"Task #3", "start_date":"13-04-2013", "duration":"6", "parent":"1", "progress": 0.5, "open": true},
                {"id":5, "text":"Task #1.1", "start_date":"02-04-2013", "duration":"7", "parent":"2", "progress": 0.6, "open": true},
                {"id":6, "text":"Task #1.2", "start_date":"03-04-2013", "duration":"7", "parent":"2", "progress": 0.6, "open": true},
                {"id":7, "text":"Task #2.1", "start_date":"11-04-2013", "duration":"8", "parent":"3", "progress": 0.6, "open": true},
                {"id":8, "text":"Task #3.1", "start_date":"14-04-2013", "duration":"5", "parent":"4", "progress": 0.5, "open": true},
                {"id":9, "text":"Task #3.2", "start_date":"14-04-2013", "duration":"4", "parent":"4", "progress": 0.5, "open": true},
                {"id":10, "text":"Task #3.3", "start_date":"14-04-2013", "duration":"3", "parent":"4", "progress": 0.5, "open": true},
                
                {"id":12, "text":"Task #1", "start_date":"03-04-2013", "duration":"5", "parent":"11", "progress": 1, "open": true},
                {"id":13, "text":"Task #2", "start_date":"02-04-2013", "duration":"7", "parent":"11", "progress": 0.5, "open": true},
                {"id":14, "text":"Task #3", "start_date":"02-04-2013", "duration":"6", "parent":"11", "progress": 0.8, "open": true},
                {"id":15, "text":"Task #4", "start_date":"02-04-2013", "duration":"5", "parent":"11", "progress": 0.2, "open": true},
                {"id":16, "text":"Task #5", "start_date":"02-04-2013", "duration":"7", "parent":"11", "progress": 0, "open": true},

                {"id":17, "text":"Task #2.1", "start_date":"03-04-2013", "duration":"2", "parent":"13", "progress": 1, "open": true},
                {"id":18, "text":"Task #2.2", "start_date":"06-04-2013", "duration":"3", "parent":"13", "progress": 0.8, "open": true},
                {"id":19, "text":"Task #2.3", "start_date":"10-04-2013", "duration":"4", "parent":"13", "progress": 0.2, "open": true},
                {"id":20, "text":"Task #2.4", "start_date":"10-04-2013", "duration":"4", "parent":"13", "progress": 0, "open": true},
                {"id":21, "text":"Task #4.1", "start_date":"03-04-2013", "duration":"4", "parent":"15", "progress": 0.5, "open": true},
                {"id":22, "text":"Task #4.2", "start_date":"03-04-2013", "duration":"4", "parent":"15", "progress": 0.1, "open": true},
                {"id":23, "text":"Task #4.3", "start_date":"03-04-2013", "duration":"5", "parent":"15", "progress": 0, "open": true}
            ],
            "links":[
                {"id":"1","source":"1","target":"2","type":"1"},
                {"id":"2","source":"2","target":"3","type":"0"},
                {"id":"3","source":"3","target":"4","type":"0"},
                {"id":"4","source":"2","target":"5","type":"2"},
                {"id":"5","source":"2","target":"6","type":"2"},
                {"id":"6","source":"3","target":"7","type":"2"},
                {"id":"7","source":"4","target":"8","type":"2"},
                {"id":"8","source":"4","target":"9","type":"2"},
                {"id":"9","source":"4","target":"10","type":"2"},
                {"id":"10","source":"11","target":"12","type":"1"},
                {"id":"11","source":"11","target":"13","type":"1"},
                {"id":"12","source":"11","target":"14","type":"1"},
                {"id":"13","source":"11","target":"15","type":"1"},
                {"id":"14","source":"11","target":"16","type":"1"},
                {"id":"15","source":"13","target":"17","type":"1"},
                {"id":"16","source":"17","target":"18","type":"0"},
                {"id":"17","source":"18","target":"19","type":"0"},
                {"id":"18","source":"19","target":"20","type":"0"},
                {"id":"19","source":"15","target":"21","type":"2"},
                {"id":"20","source":"15","target":"22","type":"2"},
                {"id":"21","source":"15","target":"23","type":"2"}
            ]

            {"id":11, "text":"Project #1", "start_date":"", "duration":"", "progress": 0.6, "open": true},

                {"id":12, "text":"Task #1", "start_date":"03-04-2013", "duration":"5", "parent":"11", "progress": 1, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "2"},
                {"id":13, "text":"Task #2", "start_date":"", "duration":"", "parent":"11", "progress": 0.5, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "1"},
                {"id":14, "text":"Task #3", "start_date":"02-04-2013", "duration":"6", "parent":"11", "progress": 0.8, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "3"},
                {"id":15, "text":"Task #4", "start_date":"", "duration":"", "parent":"11", "progress": 0.2, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "2"},
                {"id":16, "text":"Task #5", "start_date":"02-04-2013", "duration":"7", "parent":"11", "progress": 0, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "2"},

                {"id":17, "text":"Task #2.1", "start_date":"03-04-2013", "duration":"2", "parent":"13", "progress": 1, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "1"},
                {"id":18, "text":"Task #2.2", "start_date":"06-04-2013", "duration":"3", "parent":"13", "progress": 0.8, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "1"},
                {"id":19, "text":"Task #2.3", "start_date":"10-04-2013", "duration":"8", "parent":"13", "progress": 0.2, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "1"},
                {"id":20, "text":"Task #2.4", "start_date":"10-04-2013", "duration":"4", "parent":"13", "progress": 0, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "3"},
                {"id":21, "text":"Task #4.1", "start_date":"03-04-2013", "duration":"4", "parent":"15", "progress": 0.5, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "3"},
                {"id":22, "text":"Task #4.2", "start_date":"03-04-2013", "duration":"4", "parent":"15", "progress": 0.1, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "1"},
                {"id":23, "text":"Task #4.3", "start_date":"03-04-2013", "duration":"5", "parent":"15", "progress": 0, "open": true,"texto_alternativo" : "TEXTO 1" ,"priority": "1"}

                ,
            "links":[
                {"id":"10","source":"11","target":"12","type":"1"},
                {"id":"11","source":"11","target":"13","type":"1"},
                {"id":"12","source":"11","target":"14","type":"1"},
                {"id":"13","source":"11","target":"15","type":"1"},
                {"id":"14","source":"11","target":"16","type":"1"},
                {"id":"15","source":"13","target":"17","type":"1"},
                {"id":"16","source":"17","target":"18","type":"0"},
                {"id":"17","source":"18","target":"19","type":"0"},
                {"id":"18","source":"19","target":"20","type":"0"},
                {"id":"19","source":"15","target":"21","type":"2"},
                {"id":"20","source":"15","target":"22","type":"2"},
                {"id":"21","source":"15","target":"23","type":"2"}
            ]
        };*/

        var demo_tasks = {
            "data":[
                <?php  $aux = 1;$arregloData = [];?>
                @for($i = 0; $i < count($proyectos); $i++)
                    <?php $fechaA = explode("-",explode(" ",$proyectos[$i]['proyecto']->fechaMenor)[0]); 
                        $fechaA = $fechaA[2] . "-" . $fechaA[1] . "-" . $fechaA[0];
                        $existeProyecto = 0;
                        for ($k=0; $k < count($arregloData); $k++) { 
                            if($proyectos[$i]['proyecto']->nombre == $arregloData[$k])
                            {
                                $existeProyecto = 1;
                                break;
                            }

                        }

                        if($existeProyecto == 1)
                            continue;
                        
                        if(floatval($proyectos[$i]['proyecto']->cantOrdenEje) == 0)
                            $progreso = 0;
                        else
                            $progreso = floatval($proyectos[$i]['proyecto']->cantOrdenEje)   /  (floatval($proyectos[$i]['proyecto']->cantOrdenPro) + floatval($proyectos[$i]['proyecto']->cantOrdenEje));

                        
                        array_push($arregloData,$proyectos[$i]['proyecto']->nombre);
                        

                    ?>
                    @if($i == count($proyectos))
                        {"id":{{$aux}}, "text":"{{$proyectos[$i]['proyecto']->nombre}}", "start_date":"", "duration":"", "progress": {{$progreso}}, "close": true, "opc" : 0,"priority": "0","dato1":"","dato2" : "{{$proyectos[$i]['proyecto']->cantOrdenEje}}","dato3" : "{{$proyectos[$i]['proyecto']->cantOrdenPro}}"},  
                    @else
                        {"id":{{$aux}}, "text":"{{$proyectos[$i]['proyecto']->nombre}}", "start_date":"", "duration":"", "progress": {{$progreso}}, "close": true, "opc" : 0,"priority": "0","dato1":"","dato2" : "{{$proyectos[$i]['proyecto']->cantOrdenEje}}","dato3" : "{{$proyectos[$i]['proyecto']->cantOrdenPro}}"},
                    @endif

                    <?php $auxPadre = $aux; ?>
                    @if(count($proyectos[$i]['maniobra']) > 0 )
                        @for($j = 0; $j < count($proyectos[$i]['maniobra']); $j++)
                            <?php $fechaA = explode("-",explode(" ",$proyectos[$i]['maniobra'][$j]->fechaManiobra)[0]); 
                                $fechaA = $fechaA[2] . "-" . $fechaA[1] . "-" . $fechaA[0];
                                $aux++;
                            ?>
                            @if($proyectos[$i]['maniobra'][$j]->id_estado == "E2")
                                {"nodos" : "{{$proyectos[$i]['maniobra'][$j]->nodos_utilizados}}", "wbs" : "{{$proyectos[$i]['maniobra'][$j]->wbs_utilzadas}}" , "gom" : "{{$proyectos[$i]['maniobra'][$j]->gom}}", "descargo" : "{{$proyectos[$i]['maniobra'][$j]->descargo}}" ,"id":{{$aux}}, "text":"{{$proyectos[$i]['maniobra'][$j]->id_orden}}", "start_date":"{{$fechaA}}", "duration":"1", "parent":"{{$auxPadre}}", "progress": 0, "close": true,"texto_alternativo" : "TEXTO 1" ,"priority": "1", "opc" : 1, "pry" : "{{$proyectos[$i]['maniobra'][$j]->nombre}}","horaini" : "{{$proyectos[$i]['maniobra'][$j]->horaIniMani}}", "horafin" : "{{$proyectos[$i]['maniobra'][$j]->horaFinMani}}" , "valor" : "{{$proyectos[$i]['maniobra'][$j]->valorE}}"},
                            @else
                                {"nodos" : "{{$proyectos[$i]['maniobra'][$j]->nodos_utilizados}}", "wbs" : "{{$proyectos[$i]['maniobra'][$j]->wbs_utilzadas}}" , "gom" : "{{$proyectos[$i]['maniobra'][$j]->gom}}", "descargo" : "{{$proyectos[$i]['maniobra'][$j]->descargo}}" ,"id":{{$aux}}, "text":"{{$proyectos[$i]['maniobra'][$j]->id_orden}}", "start_date":"{{$fechaA}}", "duration":"1", "parent":"{{$auxPadre}}", "progress": 1, "close": true,"texto_alternativo" : "TEXTO 1" ,"priority": "2", "opc" : 1, "pry" : "{{$proyectos[$i]['maniobra'][$j]->nombre}}","horaini" : "{{$proyectos[$i]['maniobra'][$j]->horaIniMani}}", "horafin" : "{{$proyectos[$i]['maniobra'][$j]->horaFinMani}}" , "valor" : "{{$proyectos[$i]['maniobra'][$j]->valorE}}"},
                            @endif  
                        @endfor
                    @endif
                    <?php  $aux++;?>
                @endfor
            ]
        };


        function iniciogant()
        {
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
                /*if(task.priority ==1)
                    return "<div class='important'>"+task.text+" ("+task.users+") </div>";
                return task.text+" ("+task.users+")";*/
                if(task.priority == "1")
                    return "<div class='important'>"+task.text+" (" + Math.round(task.progress*100) + "%) </div>";

                if(task.opc == "1")
                    return task.text+" (" + Math.round(task.progress*100) + "%)";
                else
                    return task.text +" " + task.dato1 + " T: " + (parseInt(task.dato2) + parseInt(task.dato3)) +" P: " + task.dato3 + " E: " + task.dato2;
            };

            //Agregar avance proyecto
            gantt.templates.progress_text = function(start, end, task){
                return "<span style='text-align:left;'>"+Math.round(task.progress*100)+ "% </span>";
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
                        return "high";
                        break;
                    case "2":
                        return "medium";
                        break;
                    case "3":
                        return "low";
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


        function consultaAjax(route,datos,tiempoEspera,type,opcion,collback,dato,ele)
        {
            if(dato != -1)
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


        function verOT(ele)
        {
            var datos = 
                {
                    opc : 2,
                    ot : ele.dataset.ot
                };
            consultaAjax("../../guardarProyecto",datos,20000,"POST",1);
        }


        function consultaFiltroProyecto()
        {
            localStorage.setItem('filter-pry-fIni',document.querySelector("#fecha_inicio").value);
            localStorage.setItem('filter-pry-fFin',document.querySelector("#fecha_corte").value);
            localStorage.setItem('filter-pry-tipo',document.querySelector("#id_tipo").value);

        }

    </script>
@stop

