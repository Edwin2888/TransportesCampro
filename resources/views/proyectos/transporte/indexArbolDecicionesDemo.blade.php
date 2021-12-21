@extends('template.index')

@section('title')
	Árbol de decisiones v2
@stop

@section('title-section')
    Árbol de decisiones v2
@stop

@section('content')
    <style type="text/css">
    #tbl_inspecciones_filter
    {
        position: relative;
        left: 100px;
    }

    </style>
	<main>

        <!-- Import Modal -->
        <div style="margin: 23px;">
          <b><h4 style="display: inline-block;    margin-right: 13px;">Estado del árbol de decisiones actual: </h4></b>
          <input id="version" type="checkbox" class="form-control" onchange="cambioVersion(this);">  
        </div>

        <a href="{{ url('/') }}/arbolDecisiones" style="    margin-left: 21px;    margin-bottom: 12px;    display: block;    border: 1px solid;    width: 188px;
    padding: 8px;    border-radius: 7px;">Ver álbol de decisiones v1</a>
        

        <div class="row">
        <div class="col-sm-7">
          <div id="treeview6" class=""></div>
        </div>

        <div class="col-sm-5" id="panel_principal" style="display:none">
          <div class="panel panel-info" style="position: fixed;    width: 40%;">
            <div class="panel-heading" id="title-panel" style="    font-weight: bold;">Panel with panel-info class</div>
            <div class="panel-body" id="panel_nivel_1">
              <label id="label-text-update">Actualizar ubicación de daño:</label>
              <input type="text" id="name_text_update" class="form-control" style="width: 65%;display: inline-block;" >
              <button class="btn btn-primary" onclick="actualizarContenido(this)">Actualizar</button>
              <button class="btn btn-danger" onclick="eliminarContenido(this)" >Eliminar</button>
            </div>

            <hr style="    padding: 0px;    margin-top: 0px;    margin-bottom: 6px;">
            <div id="panel-sin-hijos">
              <p style="    text-align: center;    margin: 0px;    font-weight: bold;    margin-bottom: 9px;" id="label-new">CREAR UNA NUEVA</p>
              <input type="text" id="name_text" class="form-control" style="width: 65%;display: inline-block;    margin-left: 15px;    margin-bottom: 12px;" placeholder="Ingrese la información">
              <button class="btn btn-success" id="btn-save" onclick="guardarInformacion(this)">Guardar</button>  
            </div>

            <div id="panel-con-hijos">
              <div class="row">
                <div class="col-md-12">
                  <p style="    text-align: center;    margin: 0px;    font-weight: bold;    margin-bottom: 9px;" id="label-new">DESCRIPCIÓN DE LA NOVEDAD</p>
                  
                  <label style="        margin-right: 28px;">Inhabilita al equipo para operar</label>
                  <input id="inhabilita" type="checkbox" class="form-control">
                  <br>
                  <label style="    margin-right: 14px;margin-top: 13px;">Tiempo Máximo de atención.  Hrs. </label>
                  <input id="tiempo" type="number" min="0" class="form-control" style="width: 100px;display: inline-block;">
                  <br>
                  <label style="    margin-right: 14px;margin-top: 13px;">Tipo de mantenimiento requerido </label>
                  <select id="select_tipo-mant" class="form-control" style="width: 300px;display: inline-block;">
                    <option value="1">Correctivo Programado - Diagnóstico</option>
                  </select>
                  <br>
                  <label style="        margin-right: 116px;    margin-top: 33px;    position: relative;    top: -13px;">Solución a aplicar </label>
                  <textarea class="form-control" id="txt_solucion" style="width: 300px;height: 36px; display: inline-block;"></textarea>

                  <br>
                  <br>
                  <p style="text-align: center;    font-weight: bold;">TIPOS DE ACCIONES PARA EL MANTENIMIENTO</p>
                  <div class="row">
                    <div class="col-md-4">
                      <p style="text-align: center;margin: 0px;    font-weight: bold;margin-bottom: 4px;">Asistencia en sitio</p>
                      <br>
                      <input id="opcion1" type="checkbox" class="form-control">
                    </div>
                    <div class="col-md-4">
                      <p style="text-align: center;margin: 0px;    font-weight: bold;margin-bottom: 4px;">Desplazamiento al taller sin grúa</p>
                      <input id="opcion2" type="checkbox" class="form-control">
                    </div>
                    <div class="col-md-4">
                      <p style="text-align: center;margin: 0px;    font-weight: bold;margin-bottom: 4px;">Desplazamiento de vehículos a la seda</p>
                      <input id="opcion3" type="checkbox" class="form-control">
                    </div>
                  </div>
                  
                  <button style="    display: block;    margin: auto;    margin-top: 15px;    margin-bottom: 13px;" class="btn btn-success" id="btn-save" onclick="guardarInformacionNovedad(this)">Actualizar novedad</button>  


                  <br>
                </div>
              </div>
              

            </div>
            
          </div>
        </div>
      </div>


	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        var nodoSeleccionadoID = 0;
        var nodoSeleccionadoNivel = 0;

         var defaultData = [
          {
              text: 'ÁRBOL DE DECISIONES',
              level : 1,
              item : '0',
              state: {
                expanded: true
              },
              nodes :
              [
              @foreach($arbol as $key => $val)
                {
                    text: '{{ $val->descripcion }}',
                    item : '{{ $val->item }}',
                    level : 2,
                    state: {
                      expanded: true
                    },
                    nodes: [
                    @foreach($arbol2 as $key2 => $val2)
                        @if($val2->padre == $val->item)
                            {
                                text: '{{ $val2->descripcion }}',
                                item : '{{ $val2->item }}',
                                level : 3,
                                state: {
                                  expanded: true
                                },
                                nodes: [
                                    @foreach($arbol3 as $key3 => $val3)
                                        @if($val3->padre == $val2->item)
                                        {
                                            text: '{{ $val3->descripcion }}',
                                            item : '{{ $val3->item }}',
                                            level : 4,
                                            state: {
                                              expanded: true
                                            },
                                            nodes: [

                                            @foreach($arbol4 as $key4 => $val4)
                                              @if($val4->padre == $val3->item)
                                              {
                                                text: '{{ $val4->descripcion }}',
                                                item : '{{ $val4->item }}',
                                                level : 5,
                                                inhabilita : '{{ $val4->inhabilita }}',
                                                tiempo_estimado : '{{ $val4->tiempo_estimado }}',
                                                asistencia_sitio : '{{ $val4->asistencia_sitio }}',
                                                desplazamiento_sin_grua : '{{ $val4->desplazamiento_sin_grua }}',
                                                desplazamiento_sede : '{{ $val4->desplazamiento_sede }}',
                                                state: {
                                                  expanded: true
                                                }
                                              },
                                              @endif
                                            @endforeach
                                            ]
                                          },
                                        @endif
                                    @endforeach
                                ]
                              },
                        @endif
                    @endforeach                      
                    ]
                },
              @endforeach
        ]} ];


        function guardarInformacion(ele)
        {

          var nodo = $('#treeview6').treeview('getNode', nodoSeleccionadoID);
          var fila = 0;
          var padre = 0;
          var item = 1;

          fila = nodo.level;

          if(nodo.level == 1)
          {
              item = 1;

                if(defaultData[0].nodes.length == 0)
                {
                  item = 1;
                  // Crear nueva información
                  defaultData[0].nodes.push(
                      {
                          text: document.querySelector("#name_text").value.toUpperCase(),
                          item : "1" ,
                          level : 2,
                          state: {
                            expanded: true
                        },
                        nodes : []
                  });
                }
                else
                {
                  item = (defaultData[0].nodes.length + 1);
                  //Agregar nueva información
                  defaultData[0].nodes.push({
                          text: document.querySelector("#name_text").value.toUpperCase(),
                          item : (defaultData[0].nodes.length + 1),
                          level : 2,
                          state: {
                            expanded: true
                          },
                          nodes : []
                  });
                  

                }
          }


          if(nodo.level == 2)
          {
            padre = nodo.item;
            // Crear nuevo ubicación de daño
            for (var i = 0; i < defaultData.length; i++) {
              for (var j = 0; j < defaultData[i].nodes.length; j++) {
                  if(defaultData[i].nodes[j].item == nodo.item)
                  {
                    if(defaultData[i].nodes[j].nodes.length == 0)
                    {
                      item = nodo.item + ".1" ;
                      // Crear nueva información
                      defaultData[i].nodes[j].nodes.push(
                          {
                              text: document.querySelector("#name_text").value.toUpperCase(),
                              item : nodo.item + ".1" ,
                              level : 3,
                              state: {
                                expanded: true
                            },
                            nodes : []
                      });
                    }
                    else
                    {
                      item = nodo.item + "." + (defaultData[i].nodes[j].nodes.length + 1) ;
                      //Agregar nueva información
                      defaultData[i].nodes[j].nodes.push({
                              text: document.querySelector("#name_text").value.toUpperCase(),
                              item : nodo.item + "." + (defaultData[i].nodes[j].nodes.length + 1),
                              level : 3,
                              state: {
                                expanded: true
                              },
                              nodes : []
                      });
                    }
                    break;
                  }
              }
            }
          }

          if(nodo.level == 3)
          {
            padre = nodo.item;
            //Crear Componente
            for (var i = 0; i < defaultData.length; i++) {
              for (var j = 0; j < defaultData[i].nodes.length; j++) {
                for (var k = 0; k < defaultData[i].nodes[j].nodes.length; k++) {
                  if(defaultData[i].nodes[j].nodes[k].item == nodo.item)
                  {
                    if(defaultData[i].nodes[j].nodes[k].nodes.length == 0)
                    {
                      item = nodo.item + "." + "1";
                      // Crear nueva información
                      defaultData[i].nodes[j].nodes[k].nodes.push(
                          {
                              text: document.querySelector("#name_text").value.toUpperCase(),
                              item : nodo.item + "." + "1",
                              level : 4,
                              state: {
                                expanded: true
                              },
                              nodes : []
                      });
                    }
                    else
                    {
                      item = nodo.item + "." + (defaultData[i].nodes[j].nodes[k].nodes.length + 1);
                      //Agregar nueva información
                      defaultData[i].nodes[j].nodes[k].nodes.push({
                              text: document.querySelector("#name_text").value.toUpperCase(),
                              item : nodo.item + "." + (defaultData[i].nodes[j].nodes[k].nodes.length + 1),
                              level : 4,
                              state: {
                                expanded: true
                              },
                              nodes : []
                      });
                    }
                    break;
                  }
                }
              }
            }
          }

          if(nodo.level == 4)
          {
            padre = nodo.item;
            //Crear NOvedad reportada
            for (var i = 0; i < defaultData.length; i++) {
              for (var j = 0; j < defaultData[i].nodes.length; j++) {
                for (var k = 0; k < defaultData[i].nodes[j].nodes.length; k++) {
                  for (var l = 0; l < defaultData[i].nodes[j].nodes[k].nodes.length; l++) {
                    if(defaultData[i].nodes[j].nodes[k].nodes[l].item == nodo.item)
                    {
                      if(defaultData[i].nodes[j].nodes[k].nodes[l].nodes.length == 0)
                      {
                        item = nodo.item + "." + "1";
                        // Crear nueva información
                        defaultData[i].nodes[j].nodes[k].nodes[l].nodes.push(
                            {
                                text: document.querySelector("#name_text").value.toUpperCase(),
                                item : nodo.item + "." + "1",
                                level : 5,
                        });
                      }
                      else
                      {
                        item = nodo.item + "." + (defaultData[i].nodes[j].nodes[k].nodes[l].nodes.length + 1);
                        //Agregar nueva información
                        defaultData[i].nodes[j].nodes[k].nodes[l].nodes.push({
                                text: document.querySelector("#name_text").value.toUpperCase(),
                                item : nodo.item + "." + (defaultData[i].nodes[j].nodes[k].nodes[l].nodes.length + 1),
                                level : 5
                        });
                      }
                      break;
                    }
                  }
                }
              }
            }
          }

          
          $('#treeview6').treeview('remove');
          generarArbol();

          mostrarSincronizacion();
            $.ajax({
                url: "{{ url('/') }}/updateArbolDecision",
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:{
                  opc : 2,
                  item : item,
                  padre : padre,
                  fila : fila,
                  data : document.querySelector("#name_text").value.toUpperCase()
                },
                timeout:300000,
                success:function(data)
                {
                    mostrarModal(2,null,"EXITO","Se ha actualizado correctamente el árbol de decisiones.\n",0,"Aceptar","",null);   
                    document.querySelector("#name_text").value = "";       
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

        function actualizarContenido(ele)
        {

          if(document.querySelector("#name_text_update").value == "")
          {
            mostrarModal(1,null,"ERROR","El campo no puede estar vacio.\n",0,"Aceptar","",null);
            return;
          }

          var nodo = $('#treeview6').treeview('getNode', nodoSeleccionadoID);
            nodo.text = document.querySelector("#name_text_update").value.toUpperCase();

            var item = nodo.item;
            $('#treeview6').treeview('unselectNode', [ nodoSeleccionadoID, { silent: true } ]);

            mostrarSincronizacion();
            $.ajax({
                url: "{{ url('/') }}/updateArbolDecision",
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:{
                  opc : 1,
                  item : item,
                  data : document.querySelector("#name_text_update").value.toUpperCase()
                },
                timeout:300000,
                success:function(data)
                {
                    mostrarModal(2,null,"EXITO","Se ha actualizado correctamente el árbol de decisiones.\n",0,"Aceptar","",null);          
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

        function eliminarContenido()
        {
          var nodo = $('#treeview6').treeview('getNode', nodoSeleccionadoID);
          nodo.text = document.querySelector("#name_text_update").value.toUpperCase();

          if(confirm("¿Seguro que desea eliminar el hijo?"))
          {
            if(nodo.level == 2)
            {
              padre = nodo.item;
              // Crear nuevo ubicación de daño
              for (var i = 0; i < defaultData.length; i++) {
                for (var j = 0; j < defaultData[i].nodes.length; j++) {
                    if(defaultData[i].nodes[j].item == nodo.item)
                    {
                      defaultData[i].nodes.splice(j, 1);
                      break;
                    }
                }
              }
            }

            if(nodo.level == 3)
            {
              padre = nodo.item;
              //Crear Componente
              for (var i = 0; i < defaultData.length; i++) {
                for (var j = 0; j < defaultData[i].nodes.length; j++) {
                  for (var k = 0; k < defaultData[i].nodes[j].nodes.length; k++) {
                    if(defaultData[i].nodes[j].nodes[k].item == nodo.item)
                    {
                      defaultData[i].nodes[j].nodes = [];
                      break;
                    }
                  }
                }
              }
            }

            if(nodo.level == 4)
            {
              padre = nodo.item;
              //Crear NOvedad reportada
              for (var i = 0; i < defaultData.length; i++) {
                for (var j = 0; j < defaultData[i].nodes.length; j++) {
                  for (var k = 0; k < defaultData[i].nodes[j].nodes.length; k++) {
                    for (var l = 0; l < defaultData[i].nodes[j].nodes[k].nodes.length; l++) {
                      if(defaultData[i].nodes[j].nodes[k].nodes[l].item == nodo.item)
                      {
                        defaultData[i].nodes[j].nodes[k].nodes = [];
                        break;
                      }
                    }
                  }
                }
              }
            }

            if(nodo.level == 5)
            {
              padre = nodo.item;
              //Crear NOvedad reportada
              for (var i = 0; i < defaultData.length; i++) {
                for (var j = 0; j < defaultData[i].nodes.length; j++) {
                  for (var k = 0; k < defaultData[i].nodes[j].nodes.length; k++) {
                    for (var l = 0; l < defaultData[i].nodes[j].nodes[k].nodes.length; l++) {
                      for (var m = 0; m < defaultData[i].nodes[j].nodes[k].nodes[l].nodes.length; m++) {
                        if(defaultData[i].nodes[j].nodes[k].nodes[l].nodes[m].item == nodo.item)
                        {
                          defaultData[i].nodes[j].nodes[k].nodes[l].nodes = [];
                          break;
                        }
                      }
                    }
                  }
                }
              }
            }


            
              mostrarSincronizacion();

              $('#treeview6').treeview('remove');
              generarArbol();

              $.ajax({
                url: "{{ url('/') }}/updateArbolDecision",
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:{
                  opc : 4,
                  item : nodo.item,
                  nivel : nodo.level
                },
                timeout:300000,
                success:function(data)
                {
                    mostrarModal(2,null,"EXITO","Se ha actualizado correctamente el árbol de decisiones.\n",0,"Aceptar","",null);          
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
        }
        


        function ini()
        {
          $('#inhabilita').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

          $('#opcion1').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

          $('#opcion2').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

          $('#opcion3').bootstrapToggle({
              on: 'Si',
              off: 'No'
            });

          $('#version').bootstrapToggle({
              on: 'Habilitado',
              off: 'Inhabilitado'
            });

          
          @if($status == "1")
                $('#version').bootstrapToggle('on');
            @endif

            generarArbol();

          

            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none";
            document.querySelector("#save_formulario").addEventListener("click",saveFormulario);


        }

        function generarArbol()
        {
          $('#treeview6').treeview({
              color: "#428bca",
              expandIcon: "glyphicon glyphicon-plus",
              collapseIcon: "glyphicon glyphicon-minus",
              nodeIcon: "glyphicon glyphicon-user",
              showTags: true,
              data: defaultData,

                  onNodeSelected: function(event, data) {
                    // Your logic goes here
                    //console.log(event);
                    //console.log(data);
                    //console.log(data.level);
                    var textoAux = "";
                    var textoAux2 = "";

                    nodoSeleccionadoID = data.nodeId;
                    
                    /*var nodes = $("#treeview9").treeview('getNode', nodos[i].datos.nodeId); 
                                        
                                        var tg = nodes.tags;
                                        if(nodos.length == 1)
                                            nodes.tags = [tg[0],tg[1],document.querySelector("#text_cant_baremos").value,tg[3],tg[4]];*/


                    //data.text = "HOLA 2";

                    if(data.level == 1)
                    {
                      textoAux = "";
                      textoAux2 = "CREAR NUEVA UBICACIÓN DE DAÑO";
                    }

                    if(data.level == 2)
                    {
                      textoAux = "UBICACIÓN DAÑO: ";
                      textoAux2 = "CREAR UN NUEVO SISTEMA DE FALLO ASOCIADO";
                    }
                    if(data.level == 3)
                    {
                      textoAux = "SISTEMAS DE FALLO ASOCIADO: ";
                      textoAux2 = "CREAR NUEVO COMPONENTE";
                    }
                    if(data.level == 4)
                    {
                      textoAux = "COMPONENTE: ";
                      textoAux2 = "CREAR NUEVA NOVEDAD REPORTADA";
                    }

                    if(data.level == 5)
                      textoAux = "NOVEDAD REPORTADA: ";

                    document.querySelector("#panel_nivel_1").style.display = "block";

                    document.querySelector("#title-panel").innerHTML = textoAux + " " + data.text;
                    document.querySelector("#name_text_update").value = data.text;
                    document.querySelector("#label-text-update").innerHTML = "ACTUALIZAR " + textoAux;
                    document.querySelector("#label-new").innerHTML = textoAux2;

                    document.querySelector("#panel_principal").style.display = "block";
                    if(data.level == 5)
                    {
                      document.querySelector("#panel-sin-hijos").style.display = "none";
                      document.querySelector("#panel-con-hijos").style.display = "block";

                      
                      document.querySelector("#tiempo").value = data.tiempo_estimado;
                        
                        if(data.inhabilita == 1)
                          $('#inhabilita').bootstrapToggle('on');
                        else
                          $('#inhabilita').bootstrapToggle('off');
                        
                       document.querySelector("#tiempo").value = data.tiempo_estimado;

                       if(data.asistencia_sitio == 1)
                          $('#opcion1').bootstrapToggle('on');
                        else
                          $('#opcion1').bootstrapToggle('off');

                        if(data.desplazamiento_sin_grua == 1)
                          $('#opcion2').bootstrapToggle('on');
                        else
                          $('#opcion2').bootstrapToggle('off');

                        if(data.desplazamiento_sede == 1)
                          $('#opcion3').bootstrapToggle('on');
                        else
                          $('#opcion3').bootstrapToggle('off');

                    }
                    else
                    {
                      if(data.level != 1)
                      {
                      
                        document.querySelector("#panel-sin-hijos").style.display = "block";
                        document.querySelector("#panel-con-hijos").style.display = "none";    
                      }
                      else
                      {
                        document.querySelector("#panel_nivel_1").style.display = "none";
                        document.querySelector("#panel-sin-hijos").style.display = "block";
                        document.querySelector("#panel-con-hijos").style.display = "none";
                      }
                      
                    }
                  }
            });
        }


        function guardarInformacionNovedad(ele)
        {

          var inhabilita = (document.querySelector("#inhabilita").checked ? 1 : 0);
          var tiempo = document.querySelector("#tiempo").value;
          var tipoMant = document.querySelector("#select_tipo-mant").value;
          var solucion = document.querySelector("#txt_solucion").value;
          var opcion1 = (document.querySelector("#opcion1").checked ? 1 : 0);
          var opcion2 = (document.querySelector("#opcion2").checked ? 1 : 0);
          var opcion3 = (document.querySelector("#opcion3").checked ? 1 : 0);

          var nodo = $('#treeview6').treeview('getNode', nodoSeleccionadoID);

          var item = nodo.item;

          nodo.inhabilita = inhabilita;
          nodo.tiempo_estimado = tiempo;
          nodo.asistencia_sitio = opcion1;
          nodo.desplazamiento_sin_grua = opcion2;
          nodo.desplazamiento_sede = opcion3;

                      



          mostrarSincronizacion();
            $.ajax({
                url: "{{ url('/') }}/updateArbolDecision",
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:{
                  opc : 3,
                  item : item,
                  data : {
                    inhabilita : inhabilita,
                    tiempo:tiempo,
                    tipoMant:tipoMant,
                    solucion:solucion,
                    opcion1:opcion1,
                    opcion2:opcion2,
                    opcion3:opcion3
                  }
                },
                timeout:300000,
                success:function(data)
                {
                    mostrarModal(2,null,"EXITO","Se ha actualizado correctamente el árbol de decisiones.\n",0,"Aceptar","",null);          
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

       

        function cambioVersion(ele)
        {
            $.ajax({
                url: '{{ url('/') }}/updateArbolDecision',
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:{
                    opc : 6,
                    status : (ele.checked ? 1 : 0)
                },
                timeout:10000,
                success:function(data)
                {
                   /* mostrarModal(2,null,"EXITO","Se ha actualizado correctamente el estado del árbol de decisiones.\n",0,"Aceptar","",null);   
                },
                error:function(request,status,error){
                    
                    //$('#filter_registro').modal('toggle');
                    
                    /*mostrarModal(1,null,"Consulta de Alianzas","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);*/
                    setTimeout(function()
                    {
                        //location.reload();
                    },3000);
                }
                    
            });

          if(ele.checked)
          {
            
          }
        }



    </script>
@stop

