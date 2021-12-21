@extends('template.index')

@section('title')
	Planner
@stop

@section('title-section')
    Planner
@stop

@section('content')
    
    <style type="text/css">
    body {
        margin: 40px 10px;
        padding: 0;
        font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        font-size: 14px;
    }

    #calendar {
        max-width: 100%;
    }

    .filtros_planner
    {

        border-radius:10px;
        height: 500px;
        position: fixed;
        right:5px;
        background-color: white;
        color: #0060AC;
        border-color: #2e6da4;
        border:1px solid;
        transition: all .4s;

    }
    </style>

	<main style="margin-top:80px;margin-left:20px">
		<div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div id='calendar'></div>
                </div>
                <div class="col-md-3 filtros_planner" >
                    
                    <div class="row" style="margin-top:20px;">
                    <div class="form-group has-feedback">
                      <label class="control-label col-sm-12" for="text_total_mate_add">Proyecto</label>
                      <div class="col-sm-12">
                          <div class="input-group">
                            <span class=" input-group-addon glyphicon glyphicon-edit" aria-hidden="true"></span>
                            <select type="text" class="form-control" id="select_proyecto" name="select_proyecto">
                                <option value="0">Todos</option>
                                <option value="T01">Cartas y nueva demanda</option>
                                <option value="T02">Inversión y mantenimiento</option>
                            </select>
                          </div>
                        </div>
                    </div>
                    </div>

                    <div class="row" style="margin-top:20px;">
                    <div class="form-group has-feedback">
                      <label class="control-label col-sm-12" for="text_total_mate_add">Mes</label>
                      <div class="col-sm-12">
                          <div class="input-group">
                            <span class=" input-group-addon glyphicon glyphicon-edit" aria-hidden="true"></span>
                            <select type="text" class="form-control" id="select_mes" name="select_mes">
                                <option value="1">Enero</option>
                                <option value="2">Febrero</option>
                                <option value="3">Marzo</option>
                                <option value="4">Abril</option>
                                <option value="5">Mayo</option>
                                <option value="6">Junio</option>
                                <option value="7">Julio</option>
                                <option value="8">Agosto</option>
                                <option value="9">Septiembre</option>
                                <option value="10">Octubre</option>
                                <option value="11">Noviembre</option>
                                <option value="12">Diciembre</option>
                            </select>
                          </div>
                        </div>
                    </div>
                    </div>

                    <div class="row" style="margin-top:20px;">
                    <div class="form-group has-feedback">
                      <label class="control-label col-sm-12" for="text_total_mate_add">Estado colaborador</label>
                      <div class="col-sm-12">
                          <div class="input-group">
                            <span class=" input-group-addon glyphicon glyphicon-edit" aria-hidden="true"></span>
                            <select type="text" class="form-control" id="select_estado" name="select_estado">
                                <option value="1">Disponible</option>
                                <option value="2">No disponible</option>
                            </select>
                          </div>
                        </div>
                    </div>
                    </div>

                    <div class="row" style="margin-top:20px;">
                    <div class="form-group has-feedback">
                      <label class="control-label col-sm-12" for="text_total_mate_add">Cargo del colaborador</label>
                      <div class="col-sm-12">
                          <div class="input-group">
                            <span class=" input-group-addon glyphicon glyphicon-edit" aria-hidden="true"></span>
                            <select type="text" class="form-control" id="select_tipo" name="select_tipo">
                                <option value="1">Líder</option>
                                <option value="2">Auxiliar</option>
                            </select>
                          </div>
                        </div>
                    </div>
                    </div>

                    <div class="row" style="margin-top:20px;">
                    <div class="form-group has-feedback">
                      <label class="control-label col-sm-12" for="text_total_mate_add">Identificación colaborador</label>
                      <div class="col-sm-12">
                          <div class="input-group">
                            <span class=" input-group-addon glyphicon glyphicon-edit" aria-hidden="true"></span>
                            <input type="text" class="form-control" id="text_user" name="text_user">
                          </div>
                        </div>
                    </div>
                    </div>

                    <div class="row" style="margin-top:20px;">
                    <div class="form-group has-feedback">
                      <div class="col-sm-12">
                          <div class="input-group">
                            <button class="btn btn-primary btn-cam-trans btn-sm" type="button" id="btn_consultar"><i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
                            </button>
                            <a href="../../excel" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-file-excel-o"></i>Generar Excel</a>
                            </button>
                            <a href="../../redes/ordenes/ver" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir">Cerrar</a>

                          </div>
                        </div>
                    </div>
                    </div>

                    

                </div>
            </div>
            
        </div>
	</main>

    <link href='../../css/fullcalendar.min.css' rel='stylesheet' />
    <link href='../../css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
    

    <script type="text/javascript">


        window.addEventListener('load',iniOrdenesTrabajoProgramado);

        function iniOrdenesTrabajoProgramado()
        {

            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none";
            
            document.querySelector("#btn_consultar").addEventListener("click",function()
            {
                var data = {
                    mes: document.querySelector("#select_mes").value,
                    est: document.querySelector("#select_estado").value,
                    tip: document.querySelector("#select_tipo").value,
                    use: document.querySelector("#text_user").value,
                    proy : document.querySelector("#select_proyecto").value
                }

                consultaAjax("../../consultaPlanner",data,135000,"POST",1);

            });

            $('#calendar').fullCalendar({
            eventClick: function(calEvent, jsEvent, view) {

                var dat = "OT" + calEvent.title.split(" OT")[1];
                var datos = 
                {
                    opc : 2,
                    ot : dat
                };
                consultaAjax("../../guardarProyecto",datos,120000,"POST",2);

                /*alert('Event: ' + calEvent.title);
                alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                alert('View: ' + view.name);*/
                // change the border color just for fun
                //$(this).css('border-color', 'red');

            },
            locale: 'es',
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            monthNames : ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
            monthNamesShort:["Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
            dayNames:["Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado"],
            firstDay : 1,
            // customize the button names,
            // otherwise they'd all just say "list"
            views: {
                listDay: { buttonText: 'Día' },
                listWeek: { buttonText: 'Lista Semana' },
                month: { buttonText: 'Mes' },
                basic: {
                    month: { // name of view
                        titleFormat: 'YYYY, MM, DD'
                        // other view-specific options here
                    }
                },
                agenda: {
                    month: { // name of view
                        titleFormat: 'YYYY, MM, DD'
                        // other view-specific options here
                    }
                    // options apply to agendaWeek and agendaDay views
                },
                week: {
                    // options apply to basicWeek and agendaWeek views
                },
                day: {
                    // options apply to basicDay and agendaDay views
                }
            },
            defaultView: 'month',
            businessHours: true,
            defaultDate: '2017-03-02',
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            eventLimit: true, // allow "more" link when too many events
        });

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
                    var arreglo = [];

                    if(opcion == 1)
                    {
                        if(document.querySelector("#select_tipo").selectedIndex == 1)
                        {
                            ocultarSincronizacion();
                            return;
                        }
                        var FPro = "";
                        var FIno = "";
                        var FFin = "";
                        for (var i = 0; i < data.length; i++) {
                            
                            FPro =  data[i].fecha_programacion.split(" ")[0].split("-");
                            FPro = FPro[0] + "-" + (FPro[1].length == 1 ? "0" + FPro[1] : FPro[1]) + "-" + (FPro[2].length == 1 ? "0" + FPro[2] : FPro[2]);

                            FIno = FPro + "T" + data[i].hora_ini + ":00";
                            FFin = FPro + "T" + data[i].hora_fin + ":00";
                            var col = "#f23030";
                            if(document.querySelector("#select_estado").selectedIndex == 1)
                                col = "#0060AC";

                            var arregloI = 
                            {
                                title: data[i].id_lider + "-" + data[i].nombre + "  " + "" + data[i].id_orden + "",
                                start: FIno,
                                end: FFin,
                                color: col
                            }
                            arreglo.push(arregloI);
                        };

                        $("#calendar").fullCalendar('removeEvents'); 
                        $("#calendar").fullCalendar('addEventSource', arreglo); 

                        // 
                                // url: 'http://google.com/',
                    }
                        
                    if(opcion == 2)
                    {
                        //%$a#lert("Me voy");
                        window.open("../../redes/ordenes/ordentrabajo");
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

    </script>
@stop

@section('js')

    <script src='../../js/fullcalendar.min.js'></script>
    <script src='../../js/es.js'></script>

@stop