@extends('template.index')

@section('title')
	Plan de supervisión
@stop

@section('title-section')
    Plan de supervisión
@stop

@section('css')
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/demo.css" />
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/tabs.css" />
        <link rel="stylesheet" type="text/css" href="{{url('/')}}/css/tabstyles.css" />
        <script src="{{url('/')}}/js/modernizr.custom.plan.js"></script>
@stop
@section('content')
    <style type="text/css">
    #tbl_inspecciones_filter
    {
        position: relative;
        left: 100px;
    }

    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }

    </style>
	<main>

        <!-- Import Modal -->

		<div class="container-fluid">

            <div class="row">
                <div class="col-md-4">
                    @include('proyectos.supervisor.plansupervision.frm.frmParqueIzquierda')
                    
                </div>    

                <div class="col-md-8">
                    <div style="margin-top:18px;" id="table_lider">

                        @include('proyectos.supervisor.plansupervision.tables.tableCalificacionesPropias')
                    </div>
                    @if($aux == "1")
                    <div id="table_colaboradores" style="display:none;margin-top:18px;">
                        @include('proyectos.supervisor.plansupervision.tables.tableCalificacionesIntegrantes')
                    </div>
                    @endif
                </div>    
            </div>
            
        </div>
	</main>


@section('js')
    <script src="{{url('/')}}/js/cbpFWTabs.js"></script>

    <!--<script src="{{url('/')}}/js/Chart.bundle.js"></script>

    <script src="{{url('/')}}/js/utils.js"></script>-->
    <script src="{{url('/')}}/js/loaderChar.js"></script>-->

    <!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
@stop

    <script type="text/javascript">

        window.addEventListener('load',ini);


        

         


        function ini()
        {
            @if($tipo_login > 0)
                google.charts.load("current", {packages:["corechart"]});
                google.charts.setOnLoadCallback(inicializarGraficas);
            @endif
            
            [].slice.call( document.querySelectorAll( '.tabs' ) ).forEach( function( el ) {
                    new CBPFWTabs( el );
                });

            var alto = screen.height - 400;
            var altopx = alto+"px";

            

            $('#tbl_inspecciones_login').dataTable({
                        "scrollX":  "100%",
                        "scrolY":   altopx,
                        "paging":   true,
                        "searching": true,
                        "responsive":      false,
                        "colReorder":      true,
                        "order": [[ 6, 'asc' ]],
                        dom: 'T <"clear">lfrtip',
                        tableTools: {
                            "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                        }
            }); 


            $('#tbl1').dataTable({
                        "scrollX":  "100%",
                        "scrolY":   altopx,
                        "paging":   true,
                        "searching": true,
                        "responsive":      false,
                        "colReorder":      true,
                        "order": [[ 0, 'asc' ]],
                        dom: 'T <"clear">lfrtip',
                        tableTools: {
                            "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                        }
            }); 

            @if($aux == "1")
                var altopx = alto+"px";
                $('#tbl2').dataTable({
                            "scrollX":  "100%",
                            "scrolY":   altopx,
                            "paging":   true,
                            "searching": true,
                            "responsive":      false,
                            "colReorder":      true,
                            "order": [[ 0, 'asc' ]],
                            dom: 'T <"clear">lfrtip',
                            tableTools: {
                                "sSwfPath": "../../media/copy_csv_xls_pdf.swf", "aButtons": ["copy","xls" ]
                            }
                }); 
            @endif

           



            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none"; 

            

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
                    if(opcion == "1") //Consulta NIC Liquidación
                    {
                        
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

        @if($tipo_login > 0)
         function inicializarGraficas() {
                
                //Plan de observación líder
                var data = google.visualization.arrayToDataTable([
                    ['Observación del comportamiento', 'Porcentaje'],

                   @if($aux == "1")
                        ['Programadas', {{$lider->comportamiento - $cantLiderObser}}], 
                    @else
                        ['Programadas', {{$colaboradores[0]->comportamiento - $cantColaObser}}], 
                    @endif
                    @if($aux == "1")
                        ['Realizadas', {{$cantLiderObser}} ], 
                    @else
                        ['Realizadas', {{$cantColaObser}} ], 
                    @endif
                ]);

                var options = {
                  title: 'Observación del comportamiento',
                  pieHole: 0.4,
                  slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                          }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
                chart.draw(data, options);

                //Plan de Seguridad líder
                var data = google.visualization.arrayToDataTable([
                    ['IPALES', 'Porcentaje'],
                   @if($aux == "1")
                        ['Programadas', {{$lider->ipales - $cantLiderIPAL}}], 
                    @else
                        ['Programadas', {{$colaboradores[0]->ipales - $cantColaIPAL}}],
                    @endif
                    @if($aux == "1")
                        ['Realizadas', {{$cantLiderIPAL}} ], 
                    @else
                        ['Realizadas', {{$cantColaIPAL}} ], 
                    @endif
                ]);

                var options = {
                  title: 'IPALES',
                  pieHole: 0.4,
                  slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                          }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchart1'));
                chart.draw(data, options);

                var data = google.visualization.arrayToDataTable([
                    ['Seguridad Obras Civiles', 'Porcentaje'],
                        @if($aux == "1")
                    ['Programadas', {{$lider->seguridad_obra_civil - $cantLiderSeguridadObraCivil}}],
                        @else
                    ['Programadas', {{$colaboradores[0]->seguridad_obra_civil - $cantColaSeguridadObraCivil}}],
                        @endif
                        @if($aux == "1")
                    ['Realizadas', {{$cantLiderSeguridadObraCivil}} ],
                        @else
                    ['Realizadas', {{$cantColaSeguridadObraCivil}} ],
                    @endif
                ]);

                var options = {
                    title: 'Seguridad Obras Civiles',
                    pieHole: 0.4,
                    slices: {
                        0: { color: 'red' },
                        1: { color: 'green' }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchar1'));
                chart.draw(data, options);

                var data = google.visualization.arrayToDataTable([
                    ['Seguridad Telecomunicaciones', 'Porcentaje'],
                        @if($aux == "1")
                    ['Programadas', {{$lider->telecomunicaciones - $cantLiderTelecomunicaciones}}],
                        @else
                    ['Programadas', {{$colaboradores[0]->telecomunicaciones - $cantColaTelecomunicaciones}}],
                        @endif
                        @if($aux == "1")
                    ['Realizadas', {{$cantLiderTelecomunicaciones}} ],
                        @else
                    ['Realizadas', {{$cantColaTelecomunicaciones}} ],
                    @endif
                ]);

                var options = {
                    title: 'Seguridad Telecomunicaciones',
                    pieHole: 0.4,
                    slices: {
                        0: { color: 'red' },
                        1: { color: 'green' }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchar2'));
                chart.draw(data, options);

                var data = google.visualization.arrayToDataTable([
                    ['Inspeccion Redes Electricas', 'Porcentaje'],
                        @if($aux == "1")
                    ['Programadas', {{$lider->redes_electricas - $cantLiderRedesElectricas}}],
                        @else
                    ['Programadas', {{$colaboradores[0]->redes_electricas - $cantColaRedesElectricas}}],
                        @endif
                        @if($aux == "1")
                    ['Realizadas', {{$cantLiderRedesElectricas}} ],
                        @else
                    ['Realizadas', {{$cantColaRedesElectricas}} ],
                    @endif
                ]);

                var options = {
                    title: 'Inspeccion Redes Electricas',
                    pieHole: 0.4,
                    slices: {
                        0: { color: 'red' },
                        1: { color: 'green' }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchar3'));
                chart.draw(data, options);

                var data = google.visualization.arrayToDataTable([
                    ['Kit Manejo de Derrames', 'Porcentaje'],
                        @if($aux == "1")
                    ['Programadas', {{$lider->kit_manejo_derrames - $cantLiderKitManejoDerrames}}],
                        @else
                    ['Programadas', {{$colaboradores[0]->kit_manejo_derrames - $cantColaKitManejoDerrames}}],
                        @endif
                        @if($aux == "1")
                    ['Realizadas', {{$cantLiderKitManejoDerrames}} ],
                        @else
                    ['Realizadas', {{$cantColaKitManejoDerrames}} ],
                    @endif
                ]);

                var options = {
                    title: 'Kit Manejo de Derrames',
                    pieHole: 0.4,
                    slices: {
                        0: { color: 'red' },
                        1: { color: 'green' }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchar4'));
                chart.draw(data, options);

                var data = google.visualization.arrayToDataTable([
                    ['Locativa de Gestión Ambiental', 'Porcentaje'],
                        @if($aux == "1")
                    ['Programadas', {{$lider->locativa_gestion_ambiental - $cantLiderLocativaGestionAmbiental}}],
                        @else
                    ['Programadas', {{$colaboradores[0]->locativa_gestion_ambiental - $cantColaLocativaGestionAmbiental}}],
                        @endif
                        @if($aux == "1")
                    ['Realizadas', {{$cantLiderLocativaGestionAmbiental}} ],
                        @else
                    ['Realizadas', {{$cantColaLocativaGestionAmbiental}} ],
                    @endif
                ]);

                var options = {
                    title: 'Locativa de Gestión Ambiental',
                    pieHole: 0.4,
                    slices: {
                        0: { color: 'red' },
                        1: { color: 'green' }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchar5'));
                chart.draw(data, options);

                var data = google.visualization.arrayToDataTable([
                    ['Entrega Obras Civiles', 'Porcentaje'],
                        @if($aux == "1")
                    ['Programadas', {{$lider->entrega_obra_civil - $cantLiderEntregaObraCivil}}],
                        @else
                    ['Programadas', {{$colaboradores[0]->entrega_obra_civil - $cantColaEntregaObraCivil}}],
                        @endif
                        @if($aux == "1")
                    ['Realizadas', {{$cantLiderEntregaObraCivil}} ],
                        @else
                    ['Realizadas', {{$cantColaEntregaObraCivil}} ],
                    @endif
                ]);

                var options = {
                    title: 'Entrega Obras Civiles',
                    pieHole: 0.4,
                    slices: {
                        0: { color: 'red' },
                        1: { color: 'green' }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchar6'));
                chart.draw(data, options);

                var data = google.visualization.arrayToDataTable([
                    ['Calidad Trabajos de Restablecimiento del Servicio', 'Porcentaje'],
                        @if($aux == "1")
                    ['Programadas', {{$lider->restablecimiento_servicio - $cantLiderRestablecimientoServicios}}],
                        @else
                    ['Programadas', {{$colaboradores[0]->restablecimiento_servicio - $cantColaRestablecimientoServicios}}],
                        @endif
                        @if($aux == "1")
                    ['Realizadas', {{$cantLiderRestablecimientoServicios}} ],
                        @else
                    ['Realizadas', {{$cantColaRestablecimientoServicios}} ],
                    @endif
                ]);

                var options = {
                    title: 'Calidad Trabajos de Restablecimiento del Servicio',
                    pieHole: 0.4,
                    slices: {
                        0: { color: 'red' },
                        1: { color: 'green' }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchar7'));
                chart.draw(data, options);

                var data = google.visualization.arrayToDataTable([
                    ['Calidad Trabajos de Mantenimiento y/o Obras en MT y BT', 'Porcentaje'],
                        @if($aux == "1")
                    ['Programadas', {{$lider->mantenimiento - $cantLiderMantenimiento}}],
                        @else
                    ['Programadas', {{$colaboradores[0]->mantenimiento - $cantColaMantenimiento}}],
                        @endif
                        @if($aux == "1")
                    ['Realizadas', {{$cantLiderMantenimiento}} ],
                        @else
                    ['Realizadas', {{$cantColaMantenimiento}} ],
                    @endif
                ]);

                var options = {
                    title: 'Calidad Trabajos de Mantenimiento y/o Obras en MT y BT',
                    pieHole: 0.4,
                    slices: {
                        0: { color: 'red' },
                        1: { color: 'green' }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchar8'));
                chart.draw(data, options);

                //Plan de Calidad líder
                var data = google.visualization.arrayToDataTable([
                    ['Calidad', 'Porcentaje'],

                   @if($aux == "1")
                        ['Programadas', {{$lider->calidad - $cantLiderCali}}], 
                    @else
                        ['Programadas', {{$colaboradores[0]->calidad  - $cantColaCali}}], 
                    @endif
                    @if($aux == "1")
                        ['Realizadas', {{$cantLiderCali}} ],
                    @else
                        ['Realizadas', {{$cantColaCali}} ],
                    @endif
                ]);

                var options = {
                  title: 'Calidad',
                  pieHole: 0.4,
                  slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                          }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchart2'));
                chart.draw(data, options);

                //Plan de Medio Ambiente líder
                var data = google.visualization.arrayToDataTable([
                    ['Medio Ambiente', 'Porcentaje'],
                   @if($aux == "1")
                        ['Programadas', 0], 
                    @else
                        ['Programadas', 0], 
                    @endif
                    @if($aux == "1")
                        ['Realizadas', 0 ], 
                    @else
                        ['Realizadas', 0 ], 
                    @endif
                ]);

                var options = {
                  title: 'Medio Ambiente',
                  pieHole: 0.4,
                  slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                          }
                };

                var chart = new google.visualization.PieChart(document.getElementById('donutchart3'));
                chart.draw(data, options);

                @if($aux == "1")
                    
                    //Plan de observación Integrantes
                    var data = google.visualization.arrayToDataTable([
                        ['Observación del comportamiento', 'Porcentaje'],

                       @if($aux == "1" || $aux == "2")
                            ['Programadas', {{$PcantTotalObserCola - $cantTotalObserCola}}], 
                        @endif
                        @if($aux == "1" || $aux == "2")
                            ['Realizadas', {{$cantTotalObserCola}} ], 
                        @endif

                    ]);

                    var options = {
                      title: 'Observación del comportamiento',
                      pieHole: 0.4,
                      slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                          }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart4'));
                    chart.draw(data, options);



                    //Plan de Seguridad Integrantes
                    var data = google.visualization.arrayToDataTable([
                        ['IPALES', 'Porcentaje'],
                        
                       @if($aux == "1" || $aux == "2")
                            ['Programadas', {{$PcantTotalIpalCola - $cantTotalIpalCola}}], 
                        @endif
                        @if($aux == "1" || $aux == "2")
                            ['Realizadas', {{$cantTotalIpalCola}} ], 
                        @endif
                    ]);

                    var options = {
                      title: 'IPALES',
                      pieHole: 0.4,
                      slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                          }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart5'));
                    chart.draw(data, options);



                    //Plan de Calidad Integrantes
                    var data = google.visualization.arrayToDataTable([
                        ['Calidad', 'Porcentaje'],

                       @if($aux == "1" || $aux == "2")
                            ['Programadas', {{$PcantTotalCaliCola - $cantTotalCaliCola}}], 
                        @endif
                        @if($aux == "1" || $aux == "2")
                            ['Realizadas', {{$cantTotalCaliCola}} ], 
                        @endif
                    ]);

                    var options = {
                      title: 'Calidad',
                      pieHole: 0.4,
                      slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                          }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart6'));
                    chart.draw(data, options);




                    //Plan de Medio Ambiente Integrantes
                    var data = google.visualization.arrayToDataTable([
                        ['Medio Ambiente', 'Porcentaje'],
                        
                       @if($aux == "1" || $aux == "2")
                            ['Programadas', {{$PcantTotalAmbienteCola - $cantTotalAmbienteCola}}], 
                        @endif
                        @if($aux == "1" || $aux == "2")
                            ['Realizadas', {{$cantTotalAmbienteCola}} ], 
                        @endif
                    ]);

                    var options = {
                      title: 'Medio Ambiente',
                      pieHole: 0.4,
                      slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                          }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart7'));
                    chart.draw(data, options);

                    var data = google.visualization.arrayToDataTable([
                        ['Seguridad Obras Civiles', 'Porcentaje'],

                            @if($aux == "1" || $aux == "2")
                        ['Programadas', {{$PcantTotalSeguridadObraCivil - $cantTotalSeguridadObraCivil}}],
                            @endif
                            @if($aux == "1" || $aux == "2")
                        ['Realizadas', {{$cantTotalSeguridadObraCivil}} ],
                        @endif
                    ]);

                    var options = {
                        title: 'Seguridad Obras Civiles',
                        pieHole: 0.4,
                        slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart8'));
                    chart.draw(data, options);

                    var data = google.visualization.arrayToDataTable([
                        ['Seguridad Telecomunicaciones', 'Porcentaje'],

                            @if($aux == "1" || $aux == "2")
                        ['Programadas', {{$PcantTotalTelecomunicacionesCola - $cantTotalTelecomunicacionesCola}}],
                            @endif
                            @if($aux == "1" || $aux == "2")
                        ['Realizadas', {{$cantTotalTelecomunicacionesCola}} ],
                        @endif
                    ]);

                    var options = {
                        title: 'Seguridad Telecomunicaciones',
                        pieHole: 0.4,
                        slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart9'));
                    chart.draw(data, options);

                    var data = google.visualization.arrayToDataTable([
                        ['Inspeccion Redes Electricas', 'Porcentaje'],

                            @if($aux == "1" || $aux == "2")
                        ['Programadas', {{$PcantTotalRedesElectricasCola - $cantTotalRedesElectricasCola}}],
                            @endif
                            @if($aux == "1" || $aux == "2")
                        ['Realizadas', {{$cantTotalRedesElectricasCola}} ],
                        @endif
                    ]);

                    var options = {
                        title: 'Inspeccion Redes Electricas',
                        pieHole: 0.4,
                        slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart10'));
                    chart.draw(data, options);

                    var data = google.visualization.arrayToDataTable([
                        ['Kit Manejo de Derrames', 'Porcentaje'],

                            @if($aux == "1" || $aux == "2")
                        ['Programadas', {{$PcantTotalKitManejoDerramesCola - $cantTotalKitManejoDerramesCola}}],
                            @endif
                            @if($aux == "1" || $aux == "2")
                        ['Realizadas', {{$cantTotalKitManejoDerramesCola}} ],
                        @endif
                    ]);

                    var options = {
                        title: 'Kit Manejo de Derrames',
                        pieHole: 0.4,
                        slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart11'));
                    chart.draw(data, options);

                    var data = google.visualization.arrayToDataTable([
                        ['Locativa de Gestión Ambiental', 'Porcentaje'],

                            @if($aux == "1" || $aux == "2")
                        ['Programadas', {{$PcantTotalLocativaGestionAmbientalCola - $cantTotalLocativaGestionAmbientalCola}}],
                            @endif
                            @if($aux == "1" || $aux == "2")
                        ['Realizadas', {{$cantTotalLocativaGestionAmbientalCola}} ],
                        @endif
                    ]);

                    var options = {
                        title: 'Locativa de Gestión Ambiental',
                        pieHole: 0.4,
                        slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart12'));
                    chart.draw(data, options);

                    var data = google.visualization.arrayToDataTable([
                        ['Entrega Obras Civiles', 'Porcentaje'],

                            @if($aux == "1" || $aux == "2")
                        ['Programadas', {{$PcantTotalEntregaObraCivil - $cantTotalEntregaObraCivil}}],
                            @endif
                            @if($aux == "1" || $aux == "2")
                        ['Realizadas', {{$cantTotalEntregaObraCivil}} ],
                        @endif
                    ]);

                    var options = {
                        title: 'Entrega Obras Civiles',
                        pieHole: 0.4,
                        slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart13'));
                    chart.draw(data, options);

                    var data = google.visualization.arrayToDataTable([
                        ['Calidad Trabajos de Restablecimiento del Servicio', 'Porcentaje'],

                            @if($aux == "1" || $aux == "2")
                        ['Programadas', {{$PcantTotalRestablecimientoServicio - $cantTotalRestablecimientoServicio}}],
                            @endif
                            @if($aux == "1" || $aux == "2")
                        ['Realizadas', {{$cantTotalRestablecimientoServicio}} ],
                        @endif
                    ]);

                    var options = {
                        title: 'Calidad Trabajos de Restablecimiento del Servicio',
                        pieHole: 0.4,
                        slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart14'));
                    chart.draw(data, options);

                    var data = google.visualization.arrayToDataTable([
                        ['Calidad Trabajos de Mantenimiento y/o Obras en MT y BT', 'Porcentaje'],

                            @if($aux == "1" || $aux == "2")
                        ['Programadas', {{$PcantTotalMantenimiento - $cantTotalMantenimiento}}],
                            @endif
                            @if($aux == "1" || $aux == "2")
                        ['Realizadas', {{$cantTotalMantenimiento}} ],
                        @endif
                    ]);

                    var options = {
                        title: 'Calidad Trabajos de Mantenimiento y/o Obras en MT y BT',
                        pieHole: 0.4,
                        slices: {
                            0: { color: 'red' },
                            1: { color: 'green' }
                        }
                    };

                    var chart = new google.visualization.PieChart(document.getElementById('donutchart15'));
                    chart.draw(data, options);
                @endif


          }
          @endif

    </script>
@stop

