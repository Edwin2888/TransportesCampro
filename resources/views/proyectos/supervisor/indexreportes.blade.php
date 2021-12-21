@extends('template.index')

@section('title')
	Reportes
@stop

@section('title-section')
    Reportes
@stop

@section('css')


@section('content')
    <style type="text/css">
    #tbl_inspecciones_filter
    {
        position: relative;
        left: 100px;
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
        {!! Form::open(['url' => 'consultareportes', "method" => "POST"]) !!}
        <input type="hidden" value="1"  name="reporte_tipo" />
        
        <div style="margin-left:17px;margin-top:10px;">
            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="id_proyect">Desde:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                             style="    width: 100%;">
                            @if(Session::has('fecha_inicio1'))
                                <?php
                                    if(Session::get('fecha_inicio1') != "")
                                    {
                                        $fechaIni = Session::get('fecha_inicio1');
                                    }
                                    else
                                    {
                                        $fechaIni = $fecha2;
                                    }
                                    
                                ?>
                                <input class="form-control" size="16" style="height:30px;" type="text"
                                   value="{{$fechaIni}}" name="fecha_inicio" id="fecha_inicio"
                                   placeholder="dd/mm/aaaa" >
                            @else
                                <input class="form-control" size="16" style="height:30px;" type="text"
                                   value="{{$fecha2}}" name="fecha_inicio" id="fecha_inicio"
                                   placeholder="dd/mm/aaaa" >
                            @endif
                            <span class="input-group-addon"><i class="fa fa-times"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                        </div>
                    </div>
            </div>

            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="text_nombre_proyect">Hasta:</label>
                        <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy" style="    width: 100%;">
                            @if(Session::has("fecha_corte1"))
                                <?php
                                     if(Session::get('fecha_corte1') != "")
                                    {
                                        $fechaFin = Session::get('fecha_corte1');
                                    }
                                    else
                                    {
                                        $fechaFin = $fecha1;
                                    }
                                ?>
                                <input class="form-control" size="16" style="height:30px;" type="text"
                                   value="{{$fechaFin}}" name="fecha_corte" id="fecha_corte"
                                   placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                            @else
                                <input class="form-control" size="16" style="height:30px;" type="text"
                                   value="{{$fecha1}}" name="fecha_corte" id="fecha_corte"
                                   placeholder="dd/mm/aaaa" onBlur="valida_fecha(this.form);">
                            @endif
                            <span class="input-group-addon"><i class="fa fa-times"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
            </div>

             <?php  
                    $proyectoSelect = "";
                ?>


            <div class="col-md-2">
                    <div class="form-group has-feedback">
                        <label for="text_nombre_proyect">Proyecto:</label>
                        <div class="form-group has-feedback">
                        <select name="proyecto" id="proyecto" class="form-control" style="max-width:250px;padding:0px;">
                            @foreach($proyecto as $key => $valor)
                                @if(Session::get('proyecto1') == $valor->prefijo_db)
                                    <?php  
                                        $proyectoSelect = $valor->proyecto;
                                    ?>
                                    <option selected value="{{$valor->prefijo_db}}">{{$valor->proyecto}}</option>
                                @else
                                    <option value="{{$valor->prefijo_db}}">{{$valor->proyecto}}</option>
                                @endif
                            @endforeach
                        </select>
                        </div>

                        
                    </div>
            </div>

            <div class="col-md-2">
                <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltro()">
                    <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
                </button>
                <a style="position: relative;top:12px;" href="../../inspeccionOrdenes" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir">Cerrar</a>
            </div>


        </div>
        {!!Form::close()!!}
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="title_reporte">Resultado de Inspecciones (Todos los proyectos)</div>
                        <div id="container2" class="reporte"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="title_reporte">Estado General de Inspecciones (Todos los Proyectos)</div>
                        <div id="container" class="reporte"></div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="title_reporte">Resultado de Inspecciones: {{ $proyectoSelect}}</div>
                        <div id="container3" class="reporte"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="title_reporte">Estado General de Inspecciones: {{ $proyectoSelect}}</div>
                        <div id="container4" class="reporte"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="title_reporte">Reporte de Actuaciones Incumplidas: (Todos los proyectos)</div>
                        <div id="container7" class="reporte"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="title_reporte">Reporte de Actuaciones Incumplidas: {{ $proyectoSelect}}</div>
                        <div id="container8" class="reporte"></div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="title_reporte">Reporte de inasistencias: (Todos los proyectos)</div>
                        <div id="container5" class="reporte"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="title_reporte">Reporte de inasistencias: {{ $proyectoSelect}}</div>
                        <div id="container6" class="reporte"></div>
                    </div>
                </div>


            </div>
        </div>
        

	</main>

    <?php

        Session::forget('fecha_inicio1');
        Session::forget('fecha_corte1'); 
        Session::forget('proyecto1');   

    ?>

    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {
            
            grafica1();
            grafica2();
            grafica3();
            grafica4();
            grafica5();
            grafica6();

            grafica7();
            grafica8();


            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none"; 


        }

        function grafica1()
        {
            // Create the chart
            Highcharts.chart('container', {
                chart: {
                    type: 'column',

                },
                title: {
                    text: 'Estado General de Inspecciones (Todos los Proyectos)'
                },
                subtitle: {
                    text: 'Clic sobre la columna para ver la información más detallada.'
                },
                xAxis: {
                    categories: ['Abierto', 'En proceso', 'Cerrada', 'Anulada']
                },
                yAxis: {
                    title: {
                        text: 'Total ordenes de inspección'
                    }

                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> del total<br/>'
                },
                @if(count($reporte1) > 1)
                series: [{
                    name: 'Estado inspección',
                    colorByPoint: true,
                    data: [{
                        name: 'Abierto',
                        y: {{$reporte1[0][0]->con}},
                        drilldown: 'Abierta'
                    }, {
                        name: 'En proceso',
                        y: {{$reporte1[1][0]->con}},
                        drilldown: 'En proceso'
                    }, {
                        name: 'Cerrada',
                        y: {{$reporte1[2][0]->con}},
                        drilldown: 'Cerrada'
                    }, {
                        name: 'Anulada',
                        y: {{$reporte1[3][0]->con}},
                        drilldown: 'Anulada'
                    }]
                }],
                @endif
            });
        }

        function grafica2()
        {
            // Create the chart
            Highcharts.chart('container2', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Resultado de Inspecciones (Todos los proyectos)'
                },
                subtitle: {
                    text: 'Clic sobre la columna para ver la información más detallada.'
                },
                xAxis: {
                    categories: ['Conforme', 'No conforme']
                },
                yAxis: {
                    title: {
                        text: 'Total ordenes de inspección'
                    }

                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> del total<br/>'
                },
                @if(count($reporte2) > 1)
                series: [{
                    name: 'Resultado inspección',
                    colorByPoint: true,
                    data: [{
                        name: 'Conforme',
                        y: {{$reporte2[0][0]->con}},
                        drilldown: 'Abierta'
                    }, {
                        name: 'No conforme',
                        y: {{$reporte2[1][0]->con}},
                        drilldown: 'En proceso'
                    }]
                }],
                @endif
            });
        }

        function grafica3()
        {
            // Create the chart
            Highcharts.chart('container3', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Resultado de Inspecciones: {{ $proyectoSelect}}'
                },
                subtitle: {
                    text: 'Clic sobre la columna para ver la información más detallada.'
                },
                xAxis: {
                    categories: ['Conforme', 'No conforme']
                },
                yAxis: {
                    title: {
                        text: 'Total ordenes de inspección'
                    }

                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> del total<br/>'
                },
                @if(count($reporte3) > 1)
                series: [{
                    name: 'Resultado inspección',
                    colorByPoint: true,
                    data: [{
                        name: 'Conforme',
                        y: {{$reporte3[0][0]->con}},
                        drilldown: 'Abierta'
                    }, {
                        name: 'No conforme',
                        y: {{$reporte3[1][0]->con}},
                        drilldown: 'En proceso'
                    }]
                }],
                @endif
            });
        }
        
        function grafica4()
        {
            Highcharts.chart('container6', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reporte de inasistencias: {{ $proyectoSelect}}'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            @if(count($reporte4) > 1)
            <?php
                $total = intval($reporte4[0][0]->con) + intval($reporte4[1][0]->con) + intval($reporte4[2][0]->con) + intval($reporte4[3][0]->con) + intval($reporte4[4][0]->con);
                $res1 = intval($reporte4[0][0]->con) / ($total == 0 ? 1 : $total);
                $res2 = intval($reporte4[1][0]->con) / ($total == 0 ? 1 : $total);
                $res3 = intval($reporte4[2][0]->con) / ($total == 0 ? 1 : $total);
                $res4 = intval($reporte4[3][0]->con) / ($total == 0 ? 1 : $total);
                $res5 = intval($reporte4[4][0]->con) / ($total == 0 ? 1 : $total);
                
            ?>
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: [{
                    name: 'Asistió',
                    y: {{$res1}}
                }, {
                    name: 'No asistió',
                    y: {{$res2}}
                }, {
                    name: 'Incapacitado',
                    y: {{$res3}}
                }, {
                    name: 'Inhabilitado',
                    y: {{$res4}}
                }, {
                    name: 'Cambio de turno',
                    y: {{$res5}}
                }]
            }]
            @endif
        });
        }

        function grafica5()
        {
            
            // Create the chart
            Highcharts.chart('container4', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Estado General de Inspecciones: {{ $proyectoSelect}}'
                },
                subtitle: {
                    text: 'Clic sobre la columna para ver la información más detallada.'
                },
                xAxis: {
                    categories: ['Abierto', 'En proceso', 'Cerrada', 'Anulada']
                },
                yAxis: {
                    title: {
                        text: 'Total ordenes de inspección'
                    }

                },
                legend: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.1f}'
                        }
                    }
                },

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> del total<br/>'
                },
                @if(count($reporte5) > 1)
                series: [{
                    name: 'Estado inspección',
                    colorByPoint: true,
                    data: [{
                        name: 'Abierto',
                        y: {{$reporte5[0][0]->con}},
                        drilldown: 'Abierta'
                    }, {
                        name: 'En proceso',
                        y: {{$reporte5[1][0]->con}},
                        drilldown: 'En proceso'
                    }, {
                        name: 'Cerrada',
                        y: {{$reporte5[2][0]->con}},
                        drilldown: 'Cerrada'
                    }, {
                        name: 'Anulada',
                        y: {{$reporte5[3][0]->con}},
                        drilldown: 'Anulada'
                    }]
                }],
                @endif
            });
        }

        function grafica6()
        {
            Highcharts.chart('container5', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reporte de inasistencias: (Todos los proyectos)'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            @if(count($reporte6) > 1)
            <?php
                $total = intval($reporte6[0][0]->con) + intval($reporte6[1][0]->con) + intval($reporte6[2][0]->con) + intval($reporte6[3][0]->con) + intval($reporte6[4][0]->con);
                $res1 = intval($reporte6[0][0]->con) / ($total == 0 ? 1 : $total);
                $res2 = intval($reporte6[1][0]->con) / ($total == 0 ? 1 : $total);
                $res3 = intval($reporte6[2][0]->con) / ($total == 0 ? 1 : $total);
                $res4 = intval($reporte6[3][0]->con) / ($total == 0 ? 1 : $total);
                $res5 = intval($reporte6[4][0]->con) / ($total == 0 ? 1 : $total);
                
            ?>
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: [{
                    name: 'Asistió',
                    y: {{$res1}}
                }, {
                    name: 'No asistió',
                    y: {{$res2}}
                }, {
                    name: 'Incapacitado',
                    y: {{$res3}}
                }, {
                    name: 'Inhabilitado',
                    y: {{$res4}}
                }, {
                    name: 'Cambio de turno',
                    y: {{$res5}}
                }]
            }]
            @endif
            });
        }


        var accentDecode = function (tx)
        {
            var rp = String(tx);
            //
            rp = rp.replace(/&aacute;/g, 'á');
            rp = rp.replace(/&eacute;/g, 'é');
            rp = rp.replace(/&iacute;/g, 'í');
            rp = rp.replace(/&oacute;/g, 'ó');
            rp = rp.replace(/&uacute;/g, 'ú');
            rp = rp.replace(/&ntilde;/g, 'ñ');
            rp = rp.replace(/&uuml;/g, 'ü');
            //
            rp = rp.replace(/&Aacute;/g, 'Á');
            rp = rp.replace(/&Eacute;/g, 'É');
            rp = rp.replace(/&Iacute;/g, 'Í');
            rp = rp.replace(/&Oacute;/g, 'Ó');
            rp = rp.replace(/&Uacute;/g, 'Ú');
            rp = rp.replace(/&Ñtilde;/g, 'Ñ');
            rp = rp.replace(/&Üuml;/g, 'Ü');
            //
            return rp;
        }


        function grafica7()
        {
            Highcharts.chart('container7', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reporte de Actuaciones Incumplidas: (Todos los proyectos)'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false,
                    },
                    showInLegend: true
                }
            },
            @if(count($reporte7) > 0)
            
                series: [{
                    name: 'Porcentaje',
                    colorByPoint: true,
                    data: [
                        @foreach($reporte7[0] as $key => $val)
                        {
                            <?php
                                $res1 = intval($val->cantPregunta) / (count($reporte7) == 0 ? 1 : count($reporte7));
                                $texto = $val->des;
                            ?>
                            name: accentDecode("{{$texto}}"),
                            y: {{$res1}}
                        }, 
                        @endforeach
                    ]
                }]
            @endif
            });
        }


        function grafica8()
        {
            Highcharts.chart('container8', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Reporte de Actuaciones Incumplidas: {{ $proyectoSelect}}'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false,
                    },
                    showInLegend: true
                }
            },
            @if(count($reporte8) > 0)
            
                series: [{
                    name: 'Porcentaje',
                    colorByPoint: true,
                    data: [
                        @foreach($reporte8[0] as $key => $val)
                        {
                            <?php
                                $res1 = intval($val->cantPregunta) / (count($reporte8) == 0 ? 1 : count($reporte8));
                                $texto = $val->des;
                            ?>
                            name: accentDecode("{{$texto}}"),
                            y: {{$res1}}
                        }, 
                        @endforeach
                    ]
                }]
            @endif
            });
        }


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
                    if(opcion == "1") //Guarda plan de acción
                    {
                        document.querySelector("#tbl_index_proyectos").innerHTML = data;
                        mostrarModal(2,null,"Plan de acción","Se ha guardado correctamente el plan de acción\n",0,"Aceptar","",null);
                        $("#modal_plan_accion").modal("toggle");
                    }

                    if(opcion == "2") //Guarda plan de acción
                    {
                        mostrarModal(2,null,"Análisis de causa","Se ha guardado correctamente análisis de causa\n",0,"Aceptar","",null);
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



        function saveGestor()
        {
            if(document.querySelector("#txt_nombre_gestor").value == "" ||
                document.querySelector("#txt_iden_gestor").value == "")
            {
                mostrarModal(1,null,"Guardar gestor","Hace falta ingresar información\n",0,"Aceptar","",null);
                return;
            }
            
            var array = 
            {
                nombre : document.querySelector("#txt_nombre_gestor").value,
                gestor : document.querySelector("#txt_iden_gestor").value,
                opc : "2"
            }
            consultaAjax("../../consultaInformacionValidador",array,15000,"POST",2);

            
            
        }


    </script>
@stop

