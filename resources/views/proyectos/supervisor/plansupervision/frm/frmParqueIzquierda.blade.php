
    <div style="background-color:#0060AC;    margin-top: 13px;    ">
        <div style="     top: -6px;display:inline-block;   background: #ccc; width:80px;height:80px;border-radius:100%;border:5px solid white;margin-left:5%;position:relative;">
            <img src="{{url('/')}}/img/male.png" style="    width: 75%;    position: relative;    left: 12%;    top: 5%;">
        </div>
        
        <div style=" display:inline-block;">
            <p style="    color: white;    font-weight: bold;    position: relative;    top: 41px;    margin-left: 18px;    font-size: 14px;margin-bottom:2px;">
              @if($aux == "1")
                {{strtoupper($usuario)}}
              @else
                {{strtoupper($usuario)}}
              @endif
            </p>
            <p style="    color: white;    font-weight: bold;    position: relative;    top: 41px;    margin-left: 18px;    font-size: 16px;margin-bottom:2px;"> <img src="{{url('/')}}/img/icon.png" style="width: 21px; margin-right: 6px;">
            
            @if($tipo_login == 0)
              NO CONFORMADO
            @else
              @if($aux == "1")
                  LÍDER
              @else
                  COLABORADOR
              @endif
            @endif

                </p>
            <p style="    color: white;    font-weight: bold;    position: relative;    top: 41px;    margin-left: 18px;    font-size: 12px;margin-bottom:2px;"> <img src="{{url('/')}}/img/manager2.png" style="width: 16px;    margin-right: 6px;">
              @if($tipo_login == 0)
                EQUIPO: NO CONFORMADO</p>
              @else
                @if($aux == "1")
                  EQUIPO: {{$lider->nombre_equipo}}</p>
                @else
                  EQUIPO: {{$colaboradores[0]->nombre_equipo}}</p>
                @endif
                
              @endif
            <p style="    color: white;    font-weight: bold;    position: relative;    top: 41px;    margin-left: 18px;    font-size: 12px;margin-bottom:2px;"> <img src="{{url('/')}}/img/team.png" style="width: 16px;    margin-right: 6px;">
                
                @if($tipo_login == 0)
                  NO CONFORMADO
                @else
                  @if($aux == "1")
                    Colaboradores: {{count($colaboradores)}}
                  @else
                      Líder: {{strtoupper($lider_equipo)}}
                  @endif
                @endif
                

            </p>
        </div>

        <div style="    border-bottom: 1px solid #e9e9e9;    width: 90%;    margin-top: 20px;    margin-left: 5%;"></div>
        <br>


        <div class="container panel-izquierda-plan">
            
            <section>
                <div class="tabs tabs-style-underline">
                    <nav>
                        <ul>
                            <li><a href="#section-underline-1"><span><i class="fa fa-user" aria-hidden="true"></i>  Mi plan de inspecciones</span></a></li>
                             @if($aux == "1")
                                <li><a href="#section-underline-2" > <span> <i class="fa fa-users" aria-hidden="true"></i>  Mi equipo de trabajo</span></a></li>
                            @endif
                        </ul>
                    </nav>
                    <div class="content-wrap">
                        <b style="    color: #0060AC;    margin-left: 11px;">Año: {{$anio}} - Mes: {{$nombre_mes}}</b>
                        <section id="section-underline-1">
                                  <div id="donutchart" style="width:100%;">
                                  </div>

                                  <div id="donutchart1" style="width:100%;  ">
                                  </div>

                                  <div id="donutchart2" style="width:100%;  ">
                                  </div>

                                  <div id="donutchart3" style="width:100%; ">
                                  </div>
                                    <div id="donutchar1" style="width:100%; ">
                                    </div>
                                    <div id="donutchar2" style="width:100%; ">
                                    </div>
                                    <div id="donutchar3" style="width:100%; ">
                                    </div>
                                    <div id="donutchar4" style="width:100%; ">
                                    </div>
                                    <div id="donutchar5" style="width:100%; ">
                                    </div>
                                    <div id="donutchar6" style="width:100%; ">
                                    </div>
                                    <div id="donutchar7" style="width:100%; ">
                                    </div>
                                    <div id="donutchar8" style="width:100%; ">
                                    </div>
                        </section>
                         @if($aux == "1")
                        <section id="section-underline-2">
                            <div id="donutchart4" style="width:100%; ">
                              </div>

                              <div id="donutchart5" style="width:100%;">
                              </div>

                              <div id="donutchart6" style="width:100%;">
                              </div>

                              <div id="donutchart7" style="width:100%;">
                              </div>
                            <div id="donutchart8" style="width:100%; ">
                            </div>
                            <div id="donutchart9" style="width:100%; ">
                            </div>
                            <div id="donutchart10" style="width:100%; ">
                            </div>
                            <div id="donutchart11" style="width:100%; ">
                            </div>
                            <div id="donutchart12" style="width:100%; ">
                            </div>
                            <div id="donutchart13" style="width:100%; ">
                            </div>
                            <div id="donutchart14" style="width:100%; ">
                            </div>
                            <div id="donutchart15" style="width:100%; ">
                            </div>
                        </section>
                        @endif
                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>        
        </div><!-- /container -->

    </div> 