@extends('template.index')

@section('title')
	Reportes
@stop

@section('title-section')
    Reportes
@stop

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
        @include('proyectos.redes.trabajoprogramado.modal.modalconsultaCuadrillas')
        {!! Form::open(['url' => 'consultareportesOrdenes', "method" => "POST"]) !!}
        <input type="hidden" value="1"  name="reporte_tipo" />
        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_proyect">Desde:</label>
                    <div class="input-group date form_date" data-date="" data-date-format="dd/mm/yyyy"
                         style="    width: 100%;">
                        @if(Session::has('fecha_inicio_reporte'))
                            <?php
                                if(Session::get('fecha_inicio_reporte') != "")
                                {
                                    $fechaIni = Session::get('fecha_inicio_reporte');
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
                        @if(Session::has("fecha_corte_reporte"))
                            <?php
                                 if(Session::get('fecha_corte_reporte') != "")
                                {
                                    $fechaFin = Session::get('fecha_corte_reporte');
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

        <div class="col-md-2">
                <div class="form-group has-feedback">
                    <label for="id_orden" style="display:block;">Cuadrilla:</label>
                    <input name="responsable" id="id_responsable"  value="{{Session::get('cuadrilla')}}" type="text" readonly="" class="form-control" style="width: 67%;padding:0px;float: left;" placeholder="Cuadrilla">
                    <a onclick="abrirModal()" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-search" style="    position: relative;    top: -2px;"></i></a>
                    <a onclick="limpiar1()" class="btn btn-primary  btn-cam-trans btn-sm" style="float: left;margin-left: 5px;height: 29px;"><i class="fa fa-trash" style="    position: relative;    top: -2px;"></i></a>
                </div>
            </div>

        <div class="col-md-2">
            <button type="submit" style="margin-top: 23px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar" onclick="consultaFiltroProyecto()">
                <i class="fa fa-filter"></i> &nbsp;&nbsp;Consultar
            </button>
            <a style="position: relative;top:12px;" href="../../inspeccionOrdenes" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir">Cerrar</a>
        </div>

        <div class="elementos">   
            <a href="../../ganntProyectos"  ><span>1</span></a>         
            <a href="../../scrumOrdenes"     ><span>2</span></a>         
            <a href="#"   style="background-color:#9e9e9e" ><span>3</span></a>         
        </div>

        {!!Form::close()!!}
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="title_reporte">Capacidad instalada en dinero</div>
                    <div id="container" class="reporte"></div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="title_reporte">Capacidad instalada en horas</div>
                    <div id="container2" class="reporte"></div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="title_reporte">Capacidad instalada en tiempo de duración por baremo</div>
                    <div id="container3" class="reporte"></div>
                </div>
            </div>

            
        </div>
        

	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {
            
            if(localStorage.getItem('filter-pry-fIni') != null &&  localStorage.getItem('filter-pry-fIni') != "")
            {
               document.querySelector("#fecha_inicio").value =  localStorage.getItem('filter-pry-fIni');
               document.querySelector("#fecha_corte").value =  localStorage.getItem('filter-pry-fFin');
               
            }

            grafica1();
            grafica2();
            grafica3();
            //grafica4();
                      
            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none"; 


        }

        
        function grafica1()
        {
            var arreglo = [];
            var metaCuadrillas = [];
            var programadoCuadrilla = [];
            <?php
            $arregloCuadrillas = [];
                foreach ($reporte1 as $key => $value) {
            ?>
                @if(count($reporte1) > 10)
                    arreglo.push('{{$value->id_movil}} - {{$value->lider_nombre}}' );
                @else
                    arreglo.push('{{$value->id_movil}} <br> {{$value->lider_nombre}}<br> <b>Tipo:</b>{{$value->nombre_cuadrilla}} <br><b>Días:</b> {{$value->dias}}' );
                @endif
                metaCuadrillas.push({{floatval(explode(".",$value->meta_cuadrilla)[0])}});
                programadoCuadrilla.push({{floatval(explode(".",$value->programado_cuadrilla)[0])}});
            <?php
                }
            ?>
            //alert(JSON.stringify(metaCuadrillas));
            Highcharts.chart('container', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Capacidad instalada en dinero'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: arreglo
                },
                yAxis: {
                    title: {
                        text: 'Dinero ($)'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                    name: 'Meta cuadrilla',
                    data: metaCuadrillas
                }, {
                    name: 'Programado a cuadrilla',
                    data: programadoCuadrilla
                }]
            });
        }

        function grafica2()
        {
            var arreglo = [];
            var metaCuadrillas = [];
            var programadoCuadrilla = [];
            <?php
            $arregloCuadrillas = [];
                foreach ($reporte2 as $key => $value) {
            ?>
                @if(count($reporte3) > 10)
                    arreglo.push('{{$value->id_movil}} - {{$value->lider_nombre}}' );
                @else
                    arreglo.push('{{$value->id_movil}} <br> {{$value->lider_nombre}}<br> <b>Tipo:</b>{{$value->nombre_cuadrilla}} <br><b>Días:</b> {{$value->dias}}' );
                @endif
                metaCuadrillas.push({{intval($value->dias)*7}});
                programadoCuadrilla.push({{intval($value->minutos)/60}});
            <?php
                }
            ?>
            //alert(JSON.stringify(metaCuadrillas));
            Highcharts.chart('container2', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Capacidad instalada en horas'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: arreglo
                },
                yAxis: {
                    title: {
                        text: 'Horas (H)'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                    name: 'Horas meta diaria cuadrilla (7 horas)',
                    data: metaCuadrillas
                }, {
                    name: 'Horas programadas cuadrilla',
                    data: programadoCuadrilla
                }]
            });
        }

         function grafica3()
        {
            var arreglo = [];
            var metaCuadrillas = [];
            var programadoCuadrilla = [];
            <?php
            $arregloCuadrillas = [];
                foreach ($reporte3 as $key => $value) {
            ?>
                @if(count($reporte3) > 10)
                    arreglo.push('{{$value->id_movil}} - {{$value->lider_nombre}}' );
                @else
                    arreglo.push('{{$value->id_movil}} <br> {{$value->lider_nombre}}<br> <b>Tipo:</b>{{$value->nombre_cuadrilla}} <br><b>Días:</b> {{$value->dias}}' );
                @endif
                metaCuadrillas.push({{intval($value->dias)*7}});
                programadoCuadrilla.push({{intval($value->programado_cuadrilla_minutos)/60}});
            <?php
                }
            ?>
            //alert(JSON.stringify(metaCuadrillas));
            Highcharts.chart('container3', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Capacidad instalada en tiempo de duración por baremo'
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: arreglo
                },
                yAxis: {
                    title: {
                        text: 'Horas (H)'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                    name: 'Horas meta diaria cuadrilla (7 horas)',
                    data: metaCuadrillas
                }, {
                    name: 'Horas programadas cuadrilla por tiempo de duración del baremo',
                    data: programadoCuadrilla
                }]
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

        function abrirModal()
        {
            $("#modal_reponsable").modal("toggle");   
        }   

        function limpiar1()
        {
            document.querySelector("#id_responsable").value = "";
        }

        function salir(opc)
        {
            $("#modal_reponsable").modal("toggle");
        }

        function agregarResponFilter(ele)
        {
            document.querySelector("#id_responsable").value =  ele.parentElement.parentElement.children[2].innerHTML; 
            $("#modal_reponsable").modal("toggle");    
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


        function consultaFiltroProyecto()
        {
            localStorage.setItem('filter-pry-fIni',document.querySelector("#fecha_inicio").value);
            localStorage.setItem('filter-pry-fFin',document.querySelector("#fecha_corte").value);

        }


    </script>
@stop

.