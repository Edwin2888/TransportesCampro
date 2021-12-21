@extends('template.index')

@section('title')
    Centro de control v2
@stop

@section('title-section')
    Centro de control v2
@stop

@section('css2')
    <link rel="stylesheet" type="text/css" href="../../css/transporte.css">
@stop
<!-- 

AIzaSyAUYrvReDG54RWrEo-X64GzzMn1daq2IBg

-->
<style type="text/css">
    #map {
        height: 100%;
        width: 100%;
       }

       .btn-cam-trans
       {
        padding: 0px !important;
       }

      .btn-cam-trans:hover p 
      {
        color:white !important;
      }
      
      .btn-cam-trans p 
      {
        color:#0060AC;
      }

      .btn-primary:hover
      {
        background: white;
      }

      #tbl_novedades_ingreso_taller .col-sm-12,#tbl_novedades_salir_taller .col-sm-12
      {
        overflow-x:auto;
      }

</style>
<main>
    <!-- MODAL-->
    @include('proyectos.transporte.modal.modalIncidencias')    
    @include('proyectos.transporte.modal.modalIngresoTaller')    
    @include('proyectos.transporte.modal.modalSalidaTaller')    
    @include('proyectos.transporte.modal.modalEntregaOperacion')    

    <div class="container">

    <div id="banner_id" style=" display:none;position: absolute;    left: 11px;    top: 65px;    z-index: 20;    background: #286090;    color: white;  padding: 16px;    border-radius: 4px;    font-weight: 700;    font-size: 13px;    box-shadow: 0px 0px 23px 0px rgba(0,0,0,0.75);">      Seleccione líder
    </div>

    <div class="convenciones" style="display:none" id="convenciones_ver">
          <div class="scroll">
              <fieldset>
              <legend>Taller</legend>
              <li><img src="../../../img/mapstransporte/taller1.png"><p>Taller sin vehículos</p></li>
              <li><img src="../../../img/mapstransporte/taller2.png"><p>Taller con vehículos</p></li>
            </fieldset>
              
            <fieldset>
              <legend>Sedes</legend>
              <li><img src="../../../img/mapstransporte/sede1.png"><p>Sede sin vehículos</p></li>
              <li><img src="../../../img/mapstransporte/sede2.png"><p>Sede con vehículos</p></li>
            </fieldset>

            <fieldset>
              <legend>Incidencias</legend>
            <li><img src="../../../img/mapstransporte/incidencia1.png"><p>Incidencia generada</p></li>
            <li><img src="../../../img/mapstransporte/incidencia2.png"><p>Incidencia asignada</p></li>
            <li><img src="../../../img/mapstransporte/incidencia3.png"><p>Remitida a taller</p></li>
            <li><img src="../../../img/mapstransporte/incidencia4.png"><p>Ingreso a taller</p></li>
            <li><img src="../../../img/mapstransporte/incidencia5.png"><p>Salida de taller</p></li>
            <li><img src="../../../img/mapstransporte/incidencia7.png"><p>Entrega propietario</p></li>
            <li><img src="../../../img/mapstransporte/incidencia6.png"><p>Finalizada</p></li>
            
            
            </fieldset>

            <fieldset>
              <legend>Vehículos - Conductors</legend>
            <li><img src="../../../img/mapstransporte/conductor1.png"><p>Con incidencia generada</p></li>
            <li><img src="../../../img/mapstransporte/conductor3.png"><p>Mantenimiento</p></li>
            <li><img src="../../../img/mapstransporte/conductor4.png"><p>Vehículo activo</p></li>
            </fieldset>

            <fieldset>
              <legend>Técnicos</legend>
            <li><img src="../../../img/mapstransporte/tecnico1.png"><p>Disponible</p></li>
            <li><img src="../../../img/mapstransporte/tecnico2.png"><p>En proceso</p></li>
            <li><img src="../../../img/mapstransporte/tecnico3.png"><p>Programado</p></li>
            </fieldset>
            <a href="#" style="    margin-left: 14%;margin-top:5px;" class="btn btn-primary btn-cam-trans btn-sm btn-maps" id="close_conve"><i class="fa fa-times"></i> &nbsp; Cerrar convenciones</a>
          </div>
       </div>

       <div class="row">
        <div class="col-md-10" style="width:79%;display:inline-block">
            <div id="map" style="    margin-top: 6px;    margin-left: -8px;    height: 97%;"></div>
        </div>
        <div class="col-md-2" style="width:20%;display:inline-block;left:8px;">
            <div class="panel-asignacion">
                <p>Filtros</p>

                  <div style="height:92%;">
                    <div style="overflow-y:auto;height:100%;">
                      <p style="color:red;">Sincronización: <b id="sincronizacion">120</b></p>
                      
                      <button data-toggle="collapse" onclick="consultaNuevosDatos();" style="margin-left:29%;margin-bottom:3px;" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">
                          <i class="fa fa-search"></i> &nbsp;&nbsp;Consultar
                      </button>


                       <p class="texto-title"><b style="color:#0060AC">Estado incidencia</b></p>
                        <select class="form-control" id="select_estado_incidencia">
                          <option value="-1">Todos</option>
                          <option value="-2">NINGUNA</option>
                          <option value="E01" selected>Generada</option>
                          <option value="E02">Incidencia Asignada</option>
                          <option value="E03">Remitida a taller</option>
                          <option value="E04">Ingreso a taller</option>
                          <option value="E05">Salida de taller</option>
                          <option value="E06">Finalizada</option>
                          <option value="E07">Entrega propietario</option>  
                        </select>
                      <p class="texto-title"><b style="color:#0060AC">N° Incidencia</b></p>
                        <input  type="text" class="form-control" id="select_incidencia"/>

                      <hr style="margin:7px;border-color:#0060AC">
                      <p class="texto-title"><b style="color:#0060AC">Conductor</b></p>
                        <select class="form-control" id="select_conductores">
                        <option value="">Todos</option>
                        @foreach($vehiculos as $key => $valor)
                          <option value="{{trim($valor->identificacion)}}">{{trim($valor->nombreConductor)}}</option>
                        @endforeach
                        </select>

                      <p class="texto-title"><b style="color:#0060AC">Estado conductores</b></p>
                        <select class="form-control" id="select_estado_conductores">
                          <option value="-1">Todos</option>
                          <option value="-2" selected>NINGUNO</option>
                          <option value="E03">Con incidencia generada</option>
                          <option value="E02">Mantenimiento</option>
                          <option value="E01">Activo</option>
                        </select>
                      <hr style="margin:7px;border-color:#0060AC">
                      <p class="texto-title"><b style="color:#0060AC">Técnicos</b></p>
                       <select class="form-control" id="select_tecnicos">
                         <option value="">Todos</option>
                         @foreach($tecnicos as $key => $valor)
                            <option value="{{trim($valor->identificacion)}}">{{trim($valor->nombres)}}</option>
                          @endforeach
                        </select>

                      <p class="texto-title"><b style="color:#0060AC">Estado técnico</b></p>
                      <select class="form-control" id="select_estado_tecnico">
                        <option value="-1" selected>Todos</option>
                        <option value="-2">NINGUNO</option>
                        <option value="E03">Programado</option>
                        <option value="E01">Disponible</option>
                        <option value="E02">En proceso</option>
                      </select>
                      

                      <hr style="margin:7px;border-color:#0060AC">
                      <p class="texto-title"><b style="color:#0060AC">Talleres</b></p>
                       <select class="form-control" id="select_talleres">
                       <option value="-1">Todos</option>
                       <option value="-2" selected>NINGUNO</option>
                         @foreach($talleres as $key => $val)
                              @if($val->tipo == 1)
                                  <option value="{{$val->id}}">{{trim($val->nombre_proveedor)}}</option>
                              @endif
                          @endforeach
                        </select>

                      <hr style="margin:7px;border-color:#0060AC">
                      <p class="texto-title"><b style="color:#0060AC">Base</b></p>
                       <select class="form-control" id="select_bases">
                       <option value="-1">Todos</option>
                       <option value="-2">NINGUNO</option>
                        @foreach($talleres as $key => $val)
                              @if($val->tipo == 2)
                                  <option value="{{$val->id}}">{{trim($val->nombre_proveedor)}}</option>
                              @endif
                          @endforeach
                       </select>
                    </div>
                </div>
                

                <a href="#" class="btn btn-primary btn-cam-trans btn-sm btn-maps" id="asignar_inci" style="display:none"><i class="fa fa-user"></i> &nbsp; Consultar</a>
            </div>
            <div class="convenciones-incidencia" style="margin-top:10px;">
                <p>Acciones</p>
                @if($acceso == "W")
                  <div style="margin-left:7px;padding:0px !important;height: 49px;padding: 0px 3px !important;" class="btn btn-primary btn-cam-trans btn-sm btn-maps" id="add_inci"> &nbsp; <p style="color:#0060AC;position: relative;top: -2px;">Crear incidencia</p></div>
                @endif
                <div style="padding:0px !important;height: 49px;padding: 0px 3px !important;" class="btn btn-primary btn-cam-trans btn-sm btn-maps" id="ver_conv">&nbsp; <p style="color:#0060AC;position: relative;top: -2px;">Ver convenciones</p></div>
            </div>

            <div class="convenciones-incidencia" style="margin-top:10px;">
                <p>Novedades incidencia</p>
                  <div title="Ingreso a taller" onclick="ingresoTaller()" style="border-color:#bbbbbb;background-color:white; margin-left:19px;padding:0px !important;height: 49px;padding: 0px 3px !important;" class="btn btn-primary btn-cam-trans btn-sm btn-maps" id="add_inci"><img style="    position: relative;    top: 8px;" src="../../../img/IngresoATaller.png"/></div>
                  <div title="Salida a taller" onclick="salidaTaller()" style="border-color:#bbbbbb;background-color:white;padding:0px !important;height: 49px;padding: 0px 3px !important;" class="btn btn-primary btn-cam-trans btn-sm btn-maps" id="ver_conv"><img style="    position: relative;    top: 8px;" src="../../../img/SalidaATaller.png"/></div>
                  <div title="Entrega a la operación" onclick="entregaOpeacion()" style="border-color:#bbbbbb;background-color:white;padding:0px !important;height: 49px;padding: 0px 3px !important;" class="btn btn-primary btn-cam-trans btn-sm btn-maps" id="ver_conv"><img style="    position: relative;    top: 8px;" src="../../../img/EntregaAOperacion.png"/></div>
                  @if(count($acceso2) > 0)
                    @if($acceso2[0]->nivel_acceso == "W" || $acceso2[0]->nivel_acceso == "R")
                    <a title="Seguimiento de mantenimiento" href="../../../transversal/reportes/semanal" style="border-color:#bbbbbb;background-color:white;padding:0px !important;height: 49px;padding: 0px 3px !important;"class="btn btn-primary btn-cam-trans btn-sm btn-maps" id="ver_conv"><img style="    position: relative;    top: 8px;" src="../../../img/seg.png"/></a>
                    @endif
                  @endif
            </div>

        </div>
       </div>

       
    </div>
</main>
<script src="../../js/markerclusterer.js"></script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUYrvReDG54RWrEo-X64GzzMn1daq2IBg">
    </script>
<script>
window.addEventListener('load',ini);



var opcSelecAccion = 0;
var opcSelecAccionNull = 0;
var opcSelecMapa = 0;
var geocoder;
var reloj = null;
var  mapa ;
function ini()
{
    initMap();
    /*google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
        //this part runs when the mapobject is created and rendered
        google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
            //this part runs when the mapobject shown for the first time
        });
    });

    google.maps.event.addListenerOnce(map, 'idle', function(){
        // do something only the first time the map is loaded
    });

    google.maps.event.addDomListener(window, 'load', function(){
      
    });*/

    document.querySelector("#asignar_inci").addEventListener("click",function()
    {

        if(document.querySelector("#super_select").value == "0")
        {
            mostrarModal(1,null,"Asignación incidencia","No ha seleccionado el supervisor que se le va a asignar la incidencia.\n",0,"Aceptar","",null);
            return;
        }

        if(document.querySelector("#inci_select").value == "")
        {
            mostrarModal(1,null,"Asignación incidencia","No ha seleccionado la incidencia que se le a hacer va asignar al supervisor.\n",0,"Aceptar","",null);
            return;
        }

        var datos = {
            super: document.querySelector("#super_select").value,
            incidencia : document.querySelector("#inci_select").value,
            opc: 16
        };
        consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 1); 
    });
    ocultarSincronizacionFondoBlanco();
    consultaNuevosDatos();
    //Eventos
    document.querySelector("#selectArbol1").addEventListener("change",consultaComponente);
    document.querySelector("#selectComponente").addEventListener("change",consultaTipoFalla);
    document.querySelector("#checbox_inhabilita").addEventListener("change",inhabilitaData);
    document.querySelector("#selectTipoFalla").addEventListener("change",consultaRespuesta);
    document.querySelector("#selectRespuesta").addEventListener("change",habilitaRespuestas);

    

    //Botones Evento
    document.querySelector("#btn_1").addEventListener("click",function(){mostrarPanel(1,this);});
    document.querySelector("#btn_2").addEventListener("click",function(){mostrarPanel(2,this);});
    document.querySelector("#btn_3").addEventListener("click",function(){mostrarPanel(3,this);});
    //document.querySelector("#btn_4").addEventListener("click",function(){mostrarPanel(4,this);});
    document.querySelector("#btn_5").addEventListener("click",function(){mostrarPanel(5,this);});
    
    @if($acceso == "W")
    document.querySelector("#add_inci").addEventListener("click",newIncidencia);
    document.querySelector("#save_incidencia").addEventListener("click",saveIncidenciaDatos);
    @endif
    document.querySelector("#ver_conv").addEventListener("click",function()
      {
        document.querySelector("#convenciones_ver").style.display = "block";
      });

    document.querySelector("#close_conve").addEventListener("click",function()
      {
        document.querySelector("#convenciones_ver").style.display = "none";
      });
     
    
    //Oculta elementos
    document.querySelector("#txt_tiempo_estimado").readOnly = true;
    document.querySelector("#panel_1").style.display = "none";
    document.querySelector("#panel_2").style.display = "none";
    document.querySelector("#panel_3").style.display = "none";
    document.querySelector("#panel_4").style.display = "none";
    document.querySelector("#panel_0").style.display = "none";
    //document.querySelector("#panel_novedades").style.display = "none";

    //Evento otros elemenos
    document.querySelector("#txt_vehiculo_incidencia").addEventListener("blur",validaVehiculo);



    @if($acceso == "W")
    document.querySelector("#select_tecnico_asignar").addEventListener("change",function()
    {
      if(this.selectedIndex == 0)
        document.querySelector("#save_incidencia").style.display = "none";
      else
        document.querySelector("#save_incidencia").style.display = "inline-block";
    });
    
    document.querySelector("#taller_asignar").addEventListener("change",function()
    {
      if(this.selectedIndex == 0)
        document.querySelector("#save_incidencia").style.display = "none";
      else
        document.querySelector("#save_incidencia").style.display = "inline-block";
    });

    document.querySelector("#tencnico_asignar2").addEventListener("change",function()
    {
      if(this.selectedIndex == 0)
        document.querySelector("#save_incidencia").style.display = "none";
      else
        document.querySelector("#save_incidencia").style.display = "inline-block";
    });
    @endif
    

    verSincronizacion();

}

var iconBase = '../../img/mapstransporte/';
var icons = {
    3: {
    icon: iconBase + 'sede1.png'
    },
    4: {
    icon: iconBase + 'sede2.png'
    },
    1: {
    icon: iconBase + 'taller1.png'
    },
    2: {
    icon: iconBase + 'taller2.png'
    },
    5: {
    icon: iconBase + 'incidencia1.png'
    },
    6: {
    icon: iconBase + 'incidencia2.png'
    },
    7: {
    icon: iconBase + 'incidencia3.png'
    },
    8: {
    icon: iconBase + 'incidencia4.png'
    },
    9: {
    icon: iconBase + 'incidencia5.png'
    },
    10: {
    icon: iconBase + 'incidencia6.png'
    },
    11: {
    icon: iconBase + 'tecnico1.png'
    },
    12: {
    icon: iconBase + 'tecnico2.png'
    },
    13: {
    icon: iconBase + 'tecnico3.png'
    },
    14: {
    icon: iconBase + 'conductor1.png'
    },
    15: {
    icon: iconBase + 'conductor2.png'
    },
    16: {
    icon: iconBase + 'conductor3.png'
    }
};

function initMap() {

var caracteristicas = [
  

  @foreach($incidencias as $key => $val)
    <?php
      $latitud = explode(",",$val->coordenadas)[0];
      $longitud = explode(",",$val->coordenadas)[1];

      $val->observacion = str_replace("\n", "", $val->observacion);
      $val->observacion = str_replace("\r", "", $val->observacion);
      ?>
      {
      position: new google.maps.LatLng({{$latitud}},{{$longitud}}),
      type: {{$val->icono}},
      datos: {
            inci: "{{$val->incidencia}}",
            tipo: "{{$val->tipo_incidencia}}",
            fecha: "{{$val->fecha_servidor}}",
            obser: "{{$val->observacion}}",
            direccion1: "{{$val->coordenadas}}",
            placa: "{{$val->placa}}",
            nove: "{{$val->novedadReportada}}",
            comp: "{{$val->componente}}",
            tipo_falla: "{{$val->tipo_falla}}",
            tiempo_estimado: "{{$val->tiempo_estimado}}",
            accion: "{{$val->accion}}",
            taller_asignado: "{{$val->taller_asignado}}",
            tecnico_asignado: "{{$val->tecnico_asignado}}",
            base_asignada: "{{$val->base_asignada}}",
            fecha_asignacion: "{{$val->fecha_asignacion}}",
            id_estado: "{{$val->id_estado}}",
            direccion2: "{{$val->direccion}}",
            inhabilitado: "{{$val->inhabilitado}}",
            nombre: "{{$val->nombre}}",
            nombreT: "{{$val->nombreT}}",
            otro: "{{$val->otro}}",
            respuesta : "{{$val->respuesta}}",
            km : "{{$val->km}}",
            version : {{$val->version}}
          }
    },
  @endforeach

  @foreach($tecnicos as $key => $valor)
  {
    position: new google.maps.LatLng({{$valor->latitud}},{{$valor->longitud}}),
    type: {{$valor->icono}},
    datos: {
    nombre: "{{$valor->nombres}}",
    identificacion: "{{trim($valor->identificacion)}}",
    foto: "{{trim($valor->adjunto_foto_hv)}}",
    incidencia: "{{trim($valor->incidencia)}}",
    tiempo: "{{trim($valor->tiempo)}}",
    estadoIncidencia: "{{trim($valor->estadoIncidencia)}}",
    estadoTecnico: "{{trim($valor->estadoTecnico)}}",
    fechaAsignacion: "{{$valor->fechaAsignacion}}",
    fechaRecepcion: "{{$valor->fechaRecepcion}}",
    fechaAceptacion: "{{$valor->fechaAceptacion}}",
    vehiculoGenerador: "{{$valor->vehiculoGenerador}}",
    }
  },
  @endforeach

  @foreach($vehiculos as $key => $valor)
    {
    position: new google.maps.LatLng({{$valor->latitud}},{{$valor->longitud}}),
    type: {{$valor->icono}},
    datos: {
    nombreConductor: "{{trim($valor->nombreConductor)}}",
    identificacion: "{{trim($valor->identificacion)}}",
    placa: "{{trim($valor->placa)}}",
    fotoVehiculo: "{{trim($valor->fotoVehiculo)}}",
    fotoConductor: "{{trim($valor->fotoConductor)}}",
    ultimaIncidencia: "{{trim($valor->ultimaIncidencia)}}",
    tiempoInhabilitacion: "{{trim($valor->tiempoInhabilitacion)}}",
    estadoIncidencia: "{{trim($valor->estadoIncidencia)}}",
    estadoVehiculo: "{{trim($valor->estadoVehiculo)}}",
    fecha_asignacion: "{{trim($valor->fecha_asignacion)}}",
    taller_asignado: "{{trim($valor->taller_asignado)}}",
    tecnico_asignado: "{{trim($valor->tecnico_asignado)}}",
    base_asignada: "{{trim($valor->base_asignada)}}",
    accion: "{{trim($valor->accion)}}",
    }
    },
  @endforeach

]

var valores = function (latitud, longitud, fecha, hora) {
  document.querySelector("#lblLatitud").innerHTML = latitud;
  document.querySelector("#lblLongitud").innerHTML = longitud;
  document.querySelector("#lblFecha").innerHTML = fecha;
  document.querySelector("#lblHora").innerHTML = hora;
}

 mapa = new Maps(
  document.getElementById('map'),
    12,
    4.6585494,
    -74.1143412,
    caracteristicas,
    valores
    );
    mapa.pintarMapa;
    mapa.pintarMarcadores;
}

  class Maps{
        constructor(idMap, zoom, latitud, longitud, datos, valores){
            this.idMap = idMap;
            this.zoom = zoom;
            this.latitud = latitud;
            this.longitud = longitud;
            this.datos = datos;
            this.map = null;
            this.marcadoresArray = [];
            this.valores = valores;
        }   

        get pintarMapa(){
            return this.pintar();
        }

        get pintarMarcadores(){
            return this.marcadores();
        }

        get deleteMarcadores(){
            return this.deleteMarca();
        }

        set datosMarker(dat)
        {
          this.datos = dat;
        }
        pintar(){
            this.map = new google.maps.Map(this.idMap, {
            zoom: this.zoom,
            center: {
            //Bogotá
            lat: this.latitud,
            lng: this.longitud
            },
            disableDefaultUI: false
        });

        return this.map;
        }

        marcadores(){
        for (var i = 0, dato; dato = this.datos[i]; i++) {
            this.agregarMarcador(dato);
            }
            return this.marcadoresArray;    
        }

        deleteMarca()
        {
          for (var i = 0; i < this.marcadoresArray.length; i++) {
            this.marcadoresArray[i].setMap(null);
          }
          this.marcadoresArray = [];
        }


        agregarMarcador(dato){
            var marker = new google.maps.Marker({
                position: dato.position,
                icon: icons[dato.type].icon,
                type : dato.type,
                datos: dato.datos,
                map: this.map
            });

          var ht = "";
          if(dato.type > 0 && dato.type < 5)
          {
            ht += '<div>';
            ht += '<div>';
            ht +=  '<p><b>Nombre:</b>'+dato.datos.nombreProveedor+'</p>';
            ht +=  '<p><b>Nit:</b>'+dato.datos.nit+'</p>';
            ht +=  '<p><b>Dirección:</b>'+dato.datos.direccion+'</p>';
            ht +=  '<p><b>Teléfono:</b>'+dato.datos.telefono+'</p>';
            if(dato.datos.cantidad != 0)
              ht += '<p><b>Cantidad vehículos:</b>'+dato.datos.cantidad+'</p>';
            ht += '</div>';
            ht += '</div>';
          }

          if(marker.type > 4 && marker.type <11) //Incidenicas
          {
            ht += '<div>';
            ht += '<div>';
            ht +=  '<p><b>Incidencia:</b>'+dato.datos.inci+'</p>';
            ht +=  '<p><b>Tipo incidencia:</b>'+dato.datos.nombreT+'</p>';
            ht +=  '<p><b>Estado:</b>'+dato.datos.nombre+'</p>';
            ht +=  '<p><b>Observación:</b>'+dato.datos.obser+'</p>';
            ht +=  '<p><b>Fecha creación:</b>'+dato.datos.fecha+'</p>';
            ht +=  '<p><b>Vehículo:</b>'+dato.datos.placa+'</p>';
            ht += '</div>';
            ht += '</div>';
          }

        if(marker.type > 10 && marker.type <14) //Técnico
        {
          ht = '<div class="row">' +
          '<div class="col-md-4" style="float: left; width: 30%; margin-top: 10px;">' +
          '' +
          '</div>' +
          '<div class="col-md-8" style="float: left; width: 70%">' +
          '<p><b>Nombre: </b>' + dato.datos.nombre + '</p>' +
          '<p><b>Cédula: </b>' + dato.datos.identificacion + '</p>';

          //alert(dato.datos.incidencia + "<<--");
          if(dato.datos.incidencia  != "" && dato.datos.incidencia  != null && dato.datos.incidencia  != undefined)
          {
             ht += '<p><b>Incidencia asignada: </b>' + dato.datos.incidencia + '</p>' +
            '<p><b>Tiempo asignación: </b>' + dato.datos.tiempo + '</p>' +
            '<p><b>Estado: </b>' + dato.datos.estadoIncidencia + '</p>' +
            '<p><b>Fecha asignación: </b>' + dato.datos.fechaAsignacion + '</p>' +
            '<p><b>Fecha recepción: </b>' + dato.datos.fechaRecepcion + '</p>' +
            '<p><b>Fecha aceptación: </b>' + dato.datos.fechaAceptacion + '</p>' +
            '<p><b>Vehículo generador: </b>' + dato.datos.vehiculoGenerador + '</p>' +
            '</div>' +
            '</div>';  
          }
          
        }
        
        if(marker.type > 13 && marker.type <17) //Vehículo
        {
          var accion = dato.datos.accion;
          ht = '<div class="row">' +
          '<div class="col-md-4" style="float: left; width: 35%; margin-top: 5%;">' +
          '' +
          '</br>'+
          '</br>'+
          '</br>'+
          '</br>'+
          '' +
          '</div>' +
          '<div class="col-md-8" style="float: left; width: 65%">' +
          '<p><b>DATOS CONDUCTOR </b>'+
          '<p><b>Nombre: </b>' + dato.datos.nombreConductor + '</p>' +
          '<p><b>Cédula: </b>' + 
          dato.datos.identificacion + '</p>' +
          '</br>'+
          '</br>'+
          '<hr />'+
          '<p><b>DATOS VEHÍCULO </b>'+
          '<p><b>Placa: </b>' + dato.datos.placa + '</p>' +
          '<p><b>Ultima Incidencia: </b>' + dato.datos.ultimaIncidencia + '</p>' +
          '<p><b>Tiempo Inhabilitacion: </b>' + dato.datos.tiempoInhabilitacion + '</p>' +
          '<p><b>Estado Incidencia: </b>' + dato.datos.estadoIncidencia + '</p>' +
          '<p><b>Estado Vehiculo: </b>' + dato.datos.estadoVehiculo + '</p>' +
          '<p><b>Fecha Asignacion: </b>' + dato.datos.fecha_asignacion + '</p>';
          if(accion == "2"){
          ht = ht + '<p><b>Taller Asignado: </b>' + dato.datos.taller_asignado + '</p>';
          }
          if(accion == "1"){
          ht = ht + '<p><b>Tecnico Asignado: </b>' + dato.datos.tecnico_asignado + '</p>';
          }
          if(accion == "3"){
          ht = ht + '<p><b>Base Asignada: </b>' + dato.datos.base_asignada + '</p>' +
          '<p><b>Tecnico Asignado: </b>' + dato.datos.tecnico_asignado + '</p>';
          }
          '</div>' +
          '</div>';
        }

       var infowindow = new google.maps.InfoWindow({
            content: ht 
          });

        marker.addListener('dblclick', function() {

            document.querySelector("#txt_fecha_ultimo_kilometraje").value = "";
            document.querySelector("#txt_ultimo_kilometraje_reportado").value = "";

            if(marker.type > 0 && marker.type <5) //Marcadores de Taller, Sedes
            {
                if(opcSelecAccion != 0 && opcSelecAccionNull != 0)
                {
                  if(opcSelecAccion == 1 || opcSelecMapa == 4)
                  {
                    mostrarModal(1,null,"Asignar taller","Esta seleccionando un técnico.\n",0,"Aceptar","",null); 
                    return;
                  }

                  if(opcSelecAccion == 2) // Desplazamiento al taller sin grúa - Asigna Grúa
                  {
                    if(opcSelecMapa == 2) //Carga a segundo combo - Grúa
                    {
                      if(marker.type == 3 || marker.type == 4)
                      {
                        mostrarModal(1,null,"Asignar taller","Ha seleccionado una base.\n",0,"Aceptar","",null); 
                        return;
                      }
                      document.querySelector("#taller_asignar").value = marker.datos.id;
                      opcSelecAccionNull = 0;
                    } 
                  }
                  if(opcSelecAccion == 3) // Desplazamiento de vehículos a la sede - Asigna Base y Asigna técnico
                  {
                    if(opcSelecMapa == 3) //ASignar tercer combo - Asigna base
                    {
                      if(marker.type == 1 || marker.type == 2)
                      {
                        mostrarModal(1,null,"Asignar base","Ha seleccionado un taller.\n",0,"Aceptar","",null); 
                        return;
                      }
                      document.querySelector("#base_asignar").value = marker.datos.id;
                      opcSelecAccionNull = 0;
                    }

                    if(opcSelecMapa == 4) //Asigna cuarto combo - Asigna técnico
                    {
                      document.querySelector("#tencnico_asignar2").value = marker.datos.id;
                    }
                  }
                  document.querySelector("#banner_id").style.display = "none";
                  document.querySelector("#save_incidencia").style.display = "inline-block";
                  $("#modal_incidencias").modal("toggle");
                }else
                {
                  toggleBounce(this);  
                }
            }

            if(marker.type > 4 && marker.type <11) //Marcadores de Incidencias
            {
                limpiarFRMIncidencias(marker.datos.inci,marker.datos.tipo,marker.datos.direccion1,marker.datos.obser,marker.datos.fecha,marker.datos.nove,(marker.datos.inhabilitado == 1 ? true: false),marker.datos.placa);
                 document.querySelector("#txt_vehiculo_incidencia").readOnly = true;
                 
                 document.querySelector("#txt_incidencia_estado").value =marker.datos.nombre; 
                 //Direcciones
                 document.querySelector("#selCll").value = "";
                 document.querySelector("#txtCll").value = "";
                 document.querySelector("#selLetra1").value = "";
                 document.querySelector("#selSentido").value = "";
                 document.querySelector("#txtNum").value = "";
                 document.querySelector("#selLetra").value = "";
                 document.querySelector("#txtNum2").value = "";

                 document.querySelector("#idCiudad").value = "8";
                 document.querySelector("#selSentido2").value = "";

                 document.querySelector("#selectComponente").innerHTML = "";
                 document.querySelector("#selectTipoFalla").innerHTML = "";
                 document.querySelector("#selectRespuesta").innerHTML = "";

                 if(marker.datos.inhabilitado == 1)
                  document.querySelector("#txt_tiempo_estimado").readOnly = true;
                 else
                  document.querySelector("#txt_tiempo_estimado").readOnly = true;

                 document.querySelector("#txt_tiempo_estimado").value = marker.datos.tiempo_estimado;
                 document.querySelector("#select_tipo_incidencia").disabled = false;
                 document.querySelector("#panel_0").style.display = "none";
                 document.querySelector("#txt_observacion").readOnly = false;

                 document.querySelector("#txt_kilometraje").readOnly = false;
                 document.querySelector("#selectArbol1").disabled = false;
                 document.querySelector("#selectComponente").disabled = false;
                 document.querySelector("#selectTipoFalla").disabled = false;
                 document.querySelector("#selectRespuesta").disabled = false;

                 document.querySelector("#txt_kilometraje").disabled = false;

                 
                 document.querySelector("#txt_kilometraje").value = marker.datos.km;
                 document.querySelector("#checbox_inhabilita").disabled = true;
                 limpiaBTNAccion();
                 if(marker.datos.accion == 1)
                 {
                    document.querySelector("#btn_1").style.backgroundColor = "rgb(40, 96, 144)";
                    document.querySelector("#btn_1").style.color = "white";
                    document.querySelector("#panel_1").style.display = "block";
                    document.querySelector("#select_tecnico_asignar").value =  marker.datos.tecnico_asignado;
                 }

                 if(marker.datos.accion == 2)
                 {
                    document.querySelector("#btn_2").style.backgroundColor = "rgb(40, 96, 144)";
                    document.querySelector("#btn_2").style.color = "white";
                    document.querySelector("#panel_2").style.display = "block";
                    document.querySelector("#taller_asignar").value =  marker.datos.taller_asignado;
                 }

                 if(marker.datos.accion == 3)
                 {
                    document.querySelector("#btn_3").style.backgroundColor = "rgb(40, 96, 144)";
                    document.querySelector("#btn_3").style.color = "white";
                    document.querySelector("#panel_3").style.display = "block";
                    document.querySelector("#panel_4").style.display = "block";
                    document.querySelector("#base_asignar").value =  marker.datos.base_asignada;
                    document.querySelector("#tencnico_asignar2").value =  marker.datos.tecnico_asignado;
                 }
                 if(marker.datos.accion == 4)
                 {
                    document.querySelector("#btn_4").style.backgroundColor = "rgb(40, 96, 144)";
                    document.querySelector("#btn_4").style.color = "white";
                    document.querySelector("#panel_5").style.display = "block";
                    document.querySelector("#txt_otro_dato").value =  marker.datos.otro;
                 }

                 if(marker.datos.accion == 5)
                 {
                    document.querySelector("#btn_5").style.backgroundColor = "rgb(40, 96, 144)";
                    document.querySelector("#btn_5").style.color = "white";
                 }

                 opcSelecAccion = marker.datos.accion;

                 consultaNovedades();

                 if(marker.datos.nove != null && marker.datos.nove != "")
                 {
                    var datos = {
                      arbol: document.querySelector("#selectArbol1").value,
                      opc: 7,
                      version : document.querySelector("#version_Arbol").value
                    };
                    consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 2,[marker.datos.comp,marker.datos.tipo_falla,marker.datos.respuesta]); 
                 }

                 document.querySelector("#version_Arbol").value = marker.datos.version;
                 if(marker.datos.tipo != null && marker.datos.tipo != "" && marker.datos.nove != null)
                 {
                    //Consultar versión del árbol de decisiones
                    selectVersionArbol(1,marker.datos.tipo);
                 }

                 @if($acceso == "R")
                     document.querySelector("#txt_vehiculo_incidencia").readOnly = true;
                     document.querySelector("#select_tipo_incidencia").disabled = true;
                     document.querySelector("#txt_observacion").readOnly = true;
                     document.querySelector("#txt_kilometraje").readOnly = true;
                     document.querySelector("#selectArbol1").disabled = true;
                     document.querySelector("#selectComponente").disabled = true;
                     document.querySelector("#selectTipoFalla").disabled = true;
                     document.querySelector("#selectRespuesta").disabled = true;
                     document.querySelector("#txt_kilometraje").disabled = true;
                     document.querySelector("#checbox_inhabilita").disabled = true;
                     document.querySelector("#txt_tiempo_estimado").readOnly = true;

                     document.querySelector("#btn_1").disabled = true;
                     document.querySelector("#btn_2").disabled = true;
                     document.querySelector("#btn_3").disabled = true;
                     document.querySelector("#btn_4").disabled = true;
                     document.querySelector("#btn_5").disabled = true;

                 @endif
                 //document.querySelector("#panel_novedades").style.display = "none";
                 //document.querySelector("#save_incidencia").style.display = "none";
                 validaVehiculo();
                $("#modal_incidencias").modal("toggle");
            } 

            if(marker.type > 10 && marker.type <14) //Marcadores técnicos
            {
              if(opcSelecAccion == 2 || opcSelecMapa == 3)
                {
                  mostrarModal(1,null,"Asignar técnico","Ha seleccionado un taller o una base.\n",0,"Aceptar","",null); 
                  return;
                }
              if(opcSelecAccion != 0 && opcSelecAccionNull != 0)
                {
                  if(opcSelecAccion == 1) // Asigna taller
                  {
                    if(opcSelecMapa == 1) //Selecciona técnico
                    {
                      /*if(marker.type == 12 || marker.type == 13)
                      {
                        mostrarModal(1,null,"Asignar técnico","El técnico a seleccionar tiene que estar en estado disponible.\n",0,"Aceptar","",null); 
                        //return;
                      }*/
                      document.querySelector("#select_tecnico_asignar").value = marker.datos.identificacion;
                      opcSelecAccionNull = 0;
                    } 
                  }
                  if(opcSelecAccion == 3) // Desplazamiento de vehículos a la sede - Asigna Base y Asigna técnico
                  {
                    if(opcSelecMapa == 4) //Selecciona técnico
                    {
                      /*if(marker.type == 12 || marker.type == 13)
                      {
                        mostrarModal(1,null,"Asignar técnico","El técnico a seleccionar tiene que estar ene estado disponible.\n",0,"Aceptar","",null); 
                        return;
                      }*/
                      document.querySelector("#tencnico_asignar2").value = marker.datos.identificacion;
                      opcSelecAccionNull = 0;
                    }
                  }
                  document.querySelector("#banner_id").style.display = "none";
                  document.querySelector("#save_incidencia").style.display = "inline-block";
                  $("#modal_incidencias").modal("toggle");
                }
            }
        });

        var valores = this.valores;

        marker.addListener('click', function()
        {
          infowindow.open(this.map, marker); 
          /*if(marker.type > 4 && marker.type <11) //Marcadores de Incidencias
            {
              infowindow.open(this.map, marker); 
            } 
          else
          {
            if(marker.type > 0 && marker.type <5) //Marcadores de Talleres
            {
                infowindow.open(this.map, marker);  
            }
            
          } */
        });
        this.marcadoresArray.push(marker);
  }
} 

//Función encargada de ir restando los datos para que se actualizen solos
function verSincronizacion()
{
   reloj  =  setInterval(function()
   {
    if(parseInt(document.querySelector("#sincronizacion").innerHTML) - 1 == -1)
    {
      clearInterval(reloj);
      consultaNuevosDatos();
    }
    else
      document.querySelector("#sincronizacion").innerHTML = parseInt(document.querySelector("#sincronizacion").innerHTML) - 1

   },1000);
}

function consultaNuevosDatos()
{
  document.querySelector("#sincronizacion").innerHTML = "120";
  clearInterval(reloj);
  var array = 
  {
    "estaI" : document.querySelector("#select_estado_incidencia").value,
    "inci" : document.querySelector("#select_incidencia").value,
    "estaC" : document.querySelector("#select_estado_conductores").value,
    "conduc" : document.querySelector("#select_conductores").value,
    "estaT" : document.querySelector("#select_estado_tecnico").value,
    "tecnico" : document.querySelector("#select_tecnicos").value,
    "taller" : document.querySelector("#select_talleres").value,
    "base" : document.querySelector("#select_bases").value
  };

  mapa.deleteMarcadores;

  var datos = {
        filter: array,
        opc: 15
    };
    consultaAjax("../../rutaConsultaTransporte", datos, 150000, "POST", 14); 
}



function abrirModalIncidencias()
{
  document.querySelector("#txt_panel_datos_incidencia").style.display = "none";
  document.querySelector("#loading_panel_datos_incidencia").style.display = "none";
  document.querySelector("#panel_datos_incidencia").style.display = "block";

  document.querySelector("#txt_panel_ultimas_incidencias").style.display = "none";
  document.querySelector("#loading_panel_ultimas_incidencias").style.display = "none";
  document.querySelector("#panel_ultimas_incidencias").style.display = "block";

  document.querySelector("#txt_panel_ultimas_kilometrajes").style.display = "none";
  document.querySelector("#loading_panel_ultimas_kilometrajes").style.display = "none";
  document.querySelector("#panel_ultimas_kilometrajes").style.display = "block";

  $("#modal_incidencias").modal("toggle");
}

//Funciones de Arbol de decisiones
function consultaComponente()
{
  document.querySelector("#panel_opciones").style.display = "none";
  if(document.querySelector("#selectArbol1").selectedIndex != 0)
  {
      var datos = {
          arbol: document.querySelector("#selectArbol1").value,
          opc: 7,
          version : document.querySelector("#version_Arbol").value
      };
      consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 2); 
  }
}

function consultaTipoFalla()
{
  document.querySelector("#panel_opciones").style.display = "none";
  if(document.querySelector("#selectComponente").selectedIndex != 0)
  {
      var datos = {
          arbol: document.querySelector("#selectComponente").value,
          opc: 8,
          version : document.querySelector("#version_Arbol").value
      };
      consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 3); 
  }
}

function consultaRespuesta()
{
  document.querySelector("#panel_opciones").style.display = "none";
  if(document.querySelector("#selectTipoFalla").selectedIndex != 0)
  {
      var datos = {
          arbol: document.querySelector("#selectTipoFalla").value,
          opc: 11,
          version : document.querySelector("#version_Arbol").value
      };
      consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 8); 
  }
}

function habilitaRespuestas(dato)
{
  if(this.selectedIndex != 0)
  {
      document.querySelector("#panel_opciones").style.display = "block";
      var elemento = document.querySelector("#selectRespuesta");
      if(elemento.selectedIndex == -1)
      {
        elemento.selectedIndex = 0;
        return;
      }


      var ele = elemento.options[elemento.selectedIndex];
//      alert(ele);
      if(dato != 1)
      {
        if(ele.dataset.in == 1)
        {
          document.querySelector("#checbox_inhabilita").checked = true;
          document.querySelector("#txt_tiempo_estimado").readOnly = true;
          document.querySelector("#txt_tiempo_estimado").value = ele.dataset.tiempo;
        }
        else
        {
          document.querySelector("#checbox_inhabilita").disabled = true;
          document.querySelector("#txt_tiempo_estimado").readOnly = true;
          document.querySelector("#txt_tiempo_estimado").value = ele.dataset.tiempo;
        }   
      }
      
      if(ele.dataset.asintencia == 1)
        document.querySelector("#btn_1").style.display = "inline-block";
      else
        document.querySelector("#btn_1").style.display = "none";
      
      if(ele.dataset.desplazamiento1 == 1)
        document.querySelector("#btn_2").style.display = "inline-block";
      else
        document.querySelector("#btn_2").style.display = "none";

      if(ele.dataset.desplazamiento2 == 1)
        document.querySelector("#btn_3").style.display = "inline-block";
      else
        document.querySelector("#btn_3").style.display = "none";
  }
  else
  {
    document.querySelector("#panel_opciones").style.display = "none";
  }
}


function inhabilitaData()
{
  if(document.querySelector("#checbox_inhabilita").checked)
    document.querySelector("#txt_tiempo_estimado").readOnly = true;
  else
    document.querySelector("#txt_tiempo_estimado").readOnly = true;
}

function mostrarPanel(opc,ele)
{

    if(document.querySelector("#txt_vehiculo_incidencia").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha seleccioando vehículo.\n",0,"Aceptar","",null);                
      return;
    }

    if(document.querySelector("#txt_direccion").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado la dirección.\n",0,"Aceptar","",null);                
      return;
    }
    
    if(document.querySelector("#txt_kilometraje").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado kilometraje del vehículo.\n",0,"Aceptar","",null);                
      return;
    }

    if(document.querySelector("#txt_observacion").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado la observación de la incidencia.\n",0,"Aceptar","",null);                
      return;
    }

    

    

    if(document.querySelector("#selectArbol1").selectedIndex == 0
      || document.querySelector("#selectComponente").selectedIndex == 0 ||
      document.querySelector("#selectTipoFalla").selectedIndex == 0 ||
      document.querySelector("#selectRespuesta").selectedIndex == 0
      )
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha terminado de diligenciar el árbol de decisiones.\n",0,"Aceptar","",null);                
      return;
    }

    if(document.querySelector("#checbox_inhabilita").checked)
    {
      if(document.querySelector("#txt_tiempo_estimado").value == "")
      {
        mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado el tiempo estimado de arreglo.\n",0,"Aceptar","",null);                
        return;
      }
    }

    
    limpiaBTNAccion();
    ele.style.backgroundColor = "#286090";
    ele.style.color = "white";
    ele.style.borderColor = "#204d74";

    opcSelecAccion = opc;
    opcSelecAccionNull = opc;
    if(opc == 1)
      document.querySelector("#panel_1").style.display = "block";
    
    if(opc == 2)
      document.querySelector("#panel_2").style.display = "block";

    if(opc == 3)
    {
      document.querySelector("#panel_3").style.display = "block";
      document.querySelector("#panel_4").style.display = "block";
    }

    if(opc == 4)
      document.querySelector("#panel_5").style.display = "block";  

    if(opc == 5) 
      document.querySelector("#save_incidencia").style.display = "inline-block";
}

function limpiaBTNAccion()
{
    document.querySelector("#btn_1").style.backgroundColor = "white";
    document.querySelector("#btn_1").style.color = "#0060AC";
    document.querySelector("#btn_2").style.backgroundColor = "white";
    document.querySelector("#btn_2").style.color = "#0060AC";
    document.querySelector("#btn_3").style.backgroundColor = "white";
    document.querySelector("#btn_3").style.color = "#0060AC";
    document.querySelector("#btn_4").style.backgroundColor = "white";
    document.querySelector("#btn_4").style.color = "#0060AC";

    document.querySelector("#btn_5").style.backgroundColor = "white";
    document.querySelector("#btn_5").style.color = "#0060AC";

    document.querySelector("#panel_1").style.display = "none";
    document.querySelector("#panel_2").style.display = "none";
    document.querySelector("#panel_3").style.display = "none";
    document.querySelector("#panel_4").style.display = "none";
    document.querySelector("#panel_5").style.display = "none";

}


function seleccionarMapa(opc)
{
  opcSelecMapa = opc;
  opcSelecAccionNull = opc;
  if(opc == 1)
    document.querySelector("#banner_id").innerHTML = "Seleccione el líder";

  if(opc == 2)
    document.querySelector("#banner_id").innerHTML = "Seleccione taller";

  if(opc == 3)
    document.querySelector("#banner_id").innerHTML = "Seleccione base";

  if(opc == 4)
    document.querySelector("#banner_id").innerHTML = "Seleccione el líder";


  document.querySelector("#banner_id").style.display = "block";
            document.querySelector("#txt_fecha_ultimo_kilometraje").value = "";
            document.querySelector("#txt_ultimo_kilometraje_reportado").value = "";
  $("#modal_incidencias").modal("toggle");
}
  
function newIncidencia()
{

  document.querySelector("#txt_panel_datos_incidencia").style.display = "block";
  document.querySelector("#loading_panel_datos_incidencia").style.display = "none";
  document.querySelector("#panel_datos_incidencia").style.display = "none";

  document.querySelector("#txt_panel_ultimas_incidencias").style.display = "block";
  document.querySelector("#loading_panel_ultimas_incidencias").style.display = "none";
  document.querySelector("#panel_ultimas_incidencias").style.display = "none";

  document.querySelector("#txt_panel_ultimas_kilometrajes").style.display = "block";
  document.querySelector("#loading_panel_ultimas_kilometrajes").style.display = "none";
  document.querySelector("#panel_ultimas_kilometrajes").style.display = "none";



   //Limpia Campos
   var dt = new Date();
   var fec = dt.getFullYear() + "-" + (dt.getMonth() + 1) + "-" + dt.getDate() + " " + dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
   limpiarFRMIncidencias("","","","",fec,"",false,"");
   document.querySelector("#selCll").value = "";
   document.querySelector("#txt_vehiculo_incidencia").readOnly = false;
   document.querySelector("#txtCll").value = "";
   document.querySelector("#selLetra1").value = "";
   document.querySelector("#selSentido").value = "";
   document.querySelector("#txtNum").value = "";
   document.querySelector("#selLetra").value = "";
   document.querySelector("#txtNum2").value = "";
   document.querySelector("#recorrido_promedio").value = "";
   document.querySelector("#txt_incidencia_estado").value = "";
   document.querySelector("#txt_kilometraje").value = "";
  
   document.querySelector("#txt_fecha_ultimo_kilometraje").value = "";
   
   document.querySelector("#idCiudad").value = 9;
   document.querySelector("#selSentido2").value = "";
   document.querySelector("#selectComponente").innerHTML = "";
   document.querySelector("#selectTipoFalla").innerHTML = "";
   document.querySelector("#selectRespuesta").innerHTML = "";

   document.querySelector("#txt_tiempo_estimado").readOnly = true;

   document.querySelector("#select_tipo_incidencia").disabled = true;
   document.querySelector("#panel_0").style.display = "none";
   document.querySelector("#txt_observacion").readOnly = true;

   document.querySelector("#txt_kilometraje").readOnly = true;
   document.querySelector("#selectArbol1").disabled = true;
   document.querySelector("#selectComponente").disabled = true;
   document.querySelector("#selectTipoFalla").disabled = true;
   document.querySelector("#selectRespuesta").disabled = true;
   document.querySelector("#checbox_inhabilita").disabled = true;
   document.querySelector("#txt_tiempo_estimado").readOnly = true;

   limpiaBTNAccion();
   document.querySelector("#panel_novedades").style.display = "none";
   document.querySelector("#save_incidencia").style.display = "none";
   document.querySelector("#txt_fecha_ultimo_kilometraje").value = "";
  document.querySelector("#txt_ultimo_kilometraje_reportado").value = "";
   $("#modal_incidencias").modal("toggle");
}

function limpiarFRMIncidencias(a,b,c,d,e,f,g,h)
{
  document.querySelector("#id_incidencia").value = a;
  document.querySelector("#select_tipo_incidencia").value = b;
  document.querySelector("#txt_direccion").value = c;
  document.querySelector("#txt_observacion").value = d;
  document.querySelector("#txt_Fecha_generacion").value = e;
  document.querySelector("#selectArbol1").value = f;
  document.querySelector("#checbox_inhabilita").checked = g;
  document.querySelector("#txt_vehiculo_incidencia").value = h;
}

function validaVehiculo()
{

  consultaInformacionVehiculo();

  if(document.querySelector("#txt_vehiculo_incidencia").value != "")
  {
    var datos = {
            placa: document.querySelector("#txt_vehiculo_incidencia").value,
            opc: 9
        };
    consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 4); 
  }
}


function capturarDireccion(){
  var selCll = document.querySelector("#selCll").value;
  var txtCll = document.querySelector("#txtCll").value;
  var letra1 = document.querySelector("#selLetra1").value;

  var sentido1 = document.querySelector("#selSentido").value;
  var lblNum = document.querySelector("#lblNum").value;
  var txtNum = document.querySelector("#txtNum").value;
  var letra = document.querySelector("#selLetra").value;
  var txtNum2 = document.querySelector("#txtNum2").value;
  var selSentido2 = document.querySelector("#selSentido2").value;
  var direccion = selCll + ' ' + txtCll + ''  + letra1 + '' + (sentido1.length > 0 ? " " + sentido1  : sentido1) + ' ' + '#' + '' + (txtNum.length > 0 ? " " + txtNum  : txtNum)  + '' + (letra.length > 0 ? "" + letra  : letra)  + '' +
  (txtNum2.length > 0 ? " " + txtNum2  : txtNum2) + '' + (selSentido2.length > 0 ? " " + selSentido2  : selSentido2) ;
  document.querySelector("#lblDireccion").innerHTML = direccion ;
  direccion = direccion + "," + document.querySelector("#idCiudad").options[document.querySelector("#idCiudad").selectedIndex].text.toLowerCase() + ",Colombia"
  geocoder = new google.maps.Geocoder();
  geocoder.geocode(
  {
    'address': direccion
    }, function(results, status) {
    if (status == 'OK') {
    var coor;
        coor = results[0].geometry.location;
        document.querySelector("#txt_direccion").value = coor;
        document.querySelector("#txt_direccion").value = document.querySelector("#txt_direccion").value.replace("(","").replace(")","").replace(" ","");
    } else {
    alert('No se pudo convertir la dirección en coordenadas geográficas:  ' + status);
    }
    });
  }

function saveIncidenciaDatos()
{
  if(document.querySelector("#txt_vehiculo_incidencia").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha seleccioando vehículo.\n",0,"Aceptar","",null);                
      return;
    }
    if(document.querySelector("#txt_direccion").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado la dirección.\n",0,"Aceptar","",null);                
      return;
    }
    if(document.querySelector("#txt_kilometraje").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado el kilmetraje del vehículo.\n",0,"Aceptar","",null);                
      return;
    }

    var kilo = parseInt(document.querySelector("#txt_kilometraje").value);

    if(kilo <= 0)
    {
       mostrarModal(1,null,"Plan de acción incidencia","El kilometraje del vehículo no puede menor que 1.\n",0,"Aceptar","",null);                
      return;
    }


    if(document.querySelector("#txt_observacion").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado la observación de la incidencia.\n",0,"Aceptar","",null);                
      return;
    }
    if(document.querySelector("#txt_Fecha_generacion").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado la fecha y hora de generación.\n",0,"Aceptar","",null);                
      return;
    }
    if(document.querySelector("#selectArbol1").selectedIndex == 0
      || document.querySelector("#selectComponente").selectedIndex == 0 ||
      document.querySelector("#selectTipoFalla").selectedIndex == 0 ||
      document.querySelector("#selectRespuesta").selectedIndex == 0
      )
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha terminado de diligenciar el árbol de decisiones.\n",0,"Aceptar","",null);                
      return;
    }
    if(document.querySelector("#checbox_inhabilita").checked)
    {
      if(document.querySelector("#txt_tiempo_estimado").value == "")
      {
        mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado el tiempo estimado de arreglo.\n",0,"Aceptar","",null);                
        return;
      }
    }
    if(opcSelecAccion == 0 )
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha seleccionado la acción a realizar para la incidencia.\n",0,"Aceptar","",null);                
      return;
    }

    if(document.querySelector("#txt_tiempo_estimado").value == "")
    {
      mostrarModal(1,null,"Plan de acción incidencia","No ha ingresado el tiempo estimado de arreglo.\n",0,"Aceptar","",null);                
      return;
    }
      
   /* if(opcSelecMapa == 1)
    {
      if(document.querySelector("#select_tecnico_asignar").selectedIndex == 0 ||
        document.querySelector("#select_tecnico_asignar").value == "") 
      {
        mostrarModal(1,null,"Plan de acción incidencia","No ha seleccionado el técnico que va a asistir al sitio.\n",0,"Aceptar","",null);                
        return;   
      }
    }

    if(opcSelecMapa == 2)
    {
      if(document.querySelector("#taller_asignar").selectedIndex == 0 ||
        document.querySelector("#taller_asignar").value == "") 
      {
        mostrarModal(1,null,"Plan de acción incidencia","No ha seleccionado el taller al que se dirige el conductor.\n",0,"Aceptar","",null);                
        return;   
      }
    }

    if(opcSelecMapa == 3)
    {
      if(document.querySelector("#base_asignar").selectedIndex == 0 ||
        document.querySelector("#base_asignar").value == "") 
      {
        mostrarModal(1,null,"Plan de acción incidencia","No ha seleccionado la base al que se dirige el conductor.\n",0,"Aceptar","",null);                
        return;   
      }
    }

    if(opcSelecMapa == 4)
    {
      if(document.querySelector("#tencnico_asignar2").selectedIndex == 0 ||
        document.querySelector("#tencnico_asignar2").value == "") 
      {
        mostrarModal(1,null,"Plan de acción incidencia","No ha seleccionado el técnico que va a asistir a la base.\n",0,"Aceptar","",null);                           
        return;   
      }
    }*/
    var datos = {
            placa: document.querySelector("#txt_vehiculo_incidencia").value,
            incidencia : document.querySelector("#id_incidencia").value,
            tipoinci : document.querySelector("#selectArbol1").value,
            direccion : document.querySelector("#txt_direccion").value,
            direccion2 : document.querySelector("#lblDireccion").innerHTML,
            obser : document.querySelector("#txt_observacion").value,
            km : kilo,
            fechahora:document.querySelector("#txt_Fecha_generacion").value,
            arbol1 : document.querySelector("#selectArbol1").value,
            arbol2 : document.querySelector("#selectComponente").value,
            arbol3 : document.querySelector("#selectTipoFalla").value,
            arbol4 : document.querySelector("#selectRespuesta").value,
            inhabilita : (document.querySelector("#checbox_inhabilita").checked ? 1 : 0),
            txt_tiempo_estimado : document.querySelector("#txt_tiempo_estimado").value,
            accion : opcSelecAccion,
            tecnico_asigna: document.querySelector("#select_tecnico_asignar").value,
            taller_asigna: document.querySelector("#taller_asignar").value,
            base_asigna: document.querySelector("#base_asignar").value,
            tecnico_asigna2: document.querySelector("#tencnico_asignar2").value,
            otro : document.querySelector("#txt_otro_dato").value,
            opc: 18,
            version : document.querySelector("#version_Arbol").value
        };
    consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 5); 
}

function crear_novedad()
{
  document.querySelector("#bnt_add_novedad").disabled = true;
  var crearN = '';
      crearN += ' <div class="novedad_user">';
      crearN += '      <label for="tencnico_asignar2">Descripción de la acción:</label>';
      crearN += '      <textarea name="txt_novedad" type="text"  placeholder="Descripción de la acción" class="form-control" id="txt_novedad" style="height:100px;resize:none"></textarea>';
      crearN += '      <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;    margin-top: 9px;    margin-bottom: 5px;" id="save_novedad"><i class="fa fa-save" aria-hidden="true"></i> Guardar acción</button>';
      crearN += '      <button type="button" class="btn btn-secondary"  style="   margin-top: 9px;    margin-bottom: 5px;" id="cancel_novedad"><i class="fa fa-times" aria-hidden="true"></i>  Cancelar acción</button>';
      crearN += '  </div>';
  document.querySelector("#otro_elemento_new").innerHTML = crearN;
  document.querySelector("#save_novedad").addEventListener("click",function()
  {
    if(document.querySelector("#txt_novedad").value == "")
    {
      mostrarModal(1,null,"Guardar novedad","Tiene que ingresar la descripción de la novedad.\n",0,"Aceptar","",null);
      return;
    }

    var datos = {
            incidencia: document.querySelector("#id_incidencia").value,
            obser : document.querySelector("#txt_novedad").value,
            opc: 19
        };
    
    consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 7); 
  });

  document.querySelector("#cancel_novedad").addEventListener("click",function()
  {
    document.querySelector("#bnt_add_novedad").disabled = false;
    document.querySelector("#otro_elemento_new").innerHTML = "";
  });
} 

function consultaNovedades()
{
    document.querySelector("#panel_novedades").style.display = "block";
    document.querySelector("#img_consulta_ajax").innerHTML = '<img src="../../../img/ajax-loader1.gif" id="loading_novedades" style="    margin-left: calc(50% - 50px);    margin-bottom: 13px;    margin-top: 10px;">';
    document.querySelector("#crear_novedad").innerHTML = "";
    document.querySelector("#novedades_no_tiene").innerHTML = "";
    document.querySelector("#crear_novedad").innerHTML = "";
    document.querySelector("#novedades_user").innerHTML = "";

    document.querySelector("#img_consulta_ajax").style.display = "block";
    document.querySelector("#novedades_no_tiene").style.display = "none";
    document.querySelector("#novedades_user").style.display = "none";
    document.querySelector("#crear_novedad").style.display = "none";
    
    var datos = {
          incidencia: document.querySelector("#id_incidencia").value,
          opc: 10
    };
  
    consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 6,0,-1); 
}

//NOVEDADES INCIDENCIAS MANTENIMIENTO
function ingresoTaller()
{
  //tbl_novedades_ingreso_taller
  var datos = {
        opc: 12
  };

  @if($acceso == "W")
  document.querySelector("#tbl_novedades_ingreso_taller").innerHTML = "";
  document.querySelector("#txt_incidencia_ingreso_taller").value = "";
  document.querySelector("#txt_tiempo_estimado_ingreso_taller").value = "";
  document.querySelector("#txt_obser_ingreso_Taller").value = "";
  document.querySelector("#servicio").value = "";
  document.querySelector("#servicio1").value = "";
  document.querySelector("#servicio2").value = "";
  @endif
  consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 9); 

  //$("#modal_ingreso_taller").modal("toggle");
}

function agregaIngresoTaller(ele)
{
  document.querySelector("#txt_incidencia_ingreso_taller").value = ele.parentElement.parentElement.children[2].innerHTML;
  document.querySelector("#txt_tiempo_estimado_ingreso_taller").value = ele.parentElement.parentElement.children[8].innerHTML;
    document.querySelector("#txt_org_compras").value = ele.parentElement.parentElement.children[9].innerHTML;
      document.querySelector("#txt_pep").value = ele.parentElement.parentElement.children[10].innerHTML;
      document.querySelector("#txt_centro_logistico").value = ele.parentElement.parentElement.children[11].innerHTML;
}

function saveIngresoTaller()
{
  if(document.querySelector("#txt_incidencia_ingreso_taller").value == "")
  {
    mostrarModal(1,null,"Novedad ingreso a taller","No ha seleccionado una incidencia.\n",0,"Aceptar","",null);                  
    return;    
  }



  if(document.querySelector("#txt_org_compras").value == "" ||
    document.querySelector("#txt_org_compras").value == ""
     )
  {
    mostrarModal(1,null,"Novedad ingreso a taller","Hace falta org de compras.\n",0,"Aceptar","",null);                  
    return;    
  }

  if(document.querySelector("#txt_pep").value == "" ||
    document.querySelector("#txt_pep").value == ""
     )
  {
    mostrarModal(1,null,"Novedad ingreso a taller","Hace falta elemento PEP.\n",0,"Aceptar","",null);                  
    return;    
  }


  if(document.querySelector("#txt_centro_logistico").value == "    " ||
    document.querySelector("#txt_centro_logistico").value == 'null'
     )
  {
    mostrarModal(1,null,"Novedad ingreso a taller","El vehiculo no tiene el centro Logistico datos faltantes desde SAP.\n",0,"Aceptar","",null);                  
    return;    
  }

   if(document.querySelector("#servicio").value == "" ||
    document.querySelector("#servicio").value == ""
     )
  {
    mostrarModal(1,null,"Novedad ingreso a taller","Falta asociar el Servicio.\n",0,"Aceptar","",null);                  
    return;    
  }

 /*    if(document.querySelector("#servicio").value ==  document.querySelector("#servicio2").value 
     )
  {
    mostrarModal(1,null,"Iguales","No puedes seleccionar dos Servicios Iguales.\n",0,"Aceptar","",null);                  
    return;    
  }

      if(document.querySelector("#servicio").value ==  document.querySelector("#servicio1").value 
     )
  {
    mostrarModal(1,null,"Iguales","No puedes seleccionar dos Servicios Iguales.\n",0,"Aceptar","",null);                  
    return;    
  }

     if(document.querySelector("#servicio2").value ==  document.querySelector("#servicio1").value &&(document.querySelector("#servicio1").value != "" ||
    document.querySelector("#servicio1").value != "")
     )
  {
    mostrarModal(1,null,"Iguales","No puedes seleccionar dos Servicios Iguales.\n",0,"Aceptar","",null);                  
    return;    
  }

       if(document.querySelector("#servicio2").value ==  document.querySelector("#servicio1").value &&(document.querySelector("#servicio2").value != "" ||
    document.querySelector("#servicio2").value != "")
     )
  {
    mostrarModal(1,null,"Iguales","No puedes seleccionar dos Servicios Iguales.\n",0,"Aceptar","",null);                  
    return;    
  }

       if(document.querySelector("#servicio2").value ==  document.querySelector("#servicio").value 
     )
  {
    mostrarModal(1,null,"Iguales","No puedes seleccionar dos Servicios Iguales.\n",0,"Aceptar","",null);                  
    return;    
  }*/



    if(document.querySelector("#txt_tiempo_estimado_ingreso_taller").value == "" ||
    document.querySelector("#txt_obser_ingreso_Taller").value == ""
     )
  {
    mostrarModal(1,null,"Novedad ingreso a taller","Hace falta ingresar información.\n",0,"Aceptar","",null);                  
    return;    
  }


  var datos = {
      incidencia: document.querySelector("#txt_incidencia_ingreso_taller").value,
      obser : document.querySelector("#txt_obser_ingreso_Taller").value,
      tiempo : document.querySelector("#txt_tiempo_estimado_ingreso_taller").value,
      costo : (document.querySelector("#costo").value == "" ? 0 : document.querySelector("#costo").value),
      servicio : document.querySelector("#servicio").value,
      servicio1 : document.querySelector("#servicio1").value,
      servicio2 : document.querySelector("#servicio2").value,
      opc: 20
  };

  consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 10); 
}

var solicitarKMProximo = false;
//SALIDA DE TALLER
function salidaTaller()
{
  //tbl_novedades_ingreso_taller
  var datos = {
        opc: 13
  };

  @if($acceso == "W")
    document.querySelector("#tbl_novedades_salir_taller").innerHTML = "";
    document.querySelector("#txt_incidencia_salida_taller").value = "";
    document.querySelector("#txt_obser_salida_Taller").value = "";
  @endif
  consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 11); 
} 
function agregaSalidaTaller(ele)
{
  var idTipoResp = parseInt(ele.dataset.resp == undefined ? 0 : ele.dataset.resp);

  if(idTipoResp == 351)
  {
    solicitarKMProximo = true;
    document.querySelector("#km_proximo").disabled = false;
  }
  else{
    solicitarKMProximo = false;
    document.querySelector("#km_proximo").disabled = true;
  }
  
  document.querySelector("#km_proximo").value = "";
  document.querySelector("#km_salida_taller").value = "";
  document.querySelector("#txt_incidencia_salida_taller").value = ele.parentElement.parentElement.children[1].innerHTML; 
  document.querySelector("#costo_salida").value = ele.parentElement.parentElement.children[10].innerHTML.replace("$ ",""); 
}

function saveSalidaTaller()
{
    if(document.querySelector("#txt_incidencia_salida_taller").value == "")
    {
      mostrarModal(1,null,"Novedad salida a taller","No ha seleccionado una incidencia.\n",0,"Aceptar","",null);                  
      return;    
    }

    if(document.querySelector("#km_salida_taller").value == "" || document.querySelector("#km_salida_taller").value == "0")
    {
      mostrarModal(1,null,"Novedad salida a taller","No ha ingresado el Km del vehículo.\n",0,"Aceptar","",null);                  
      return;    
    }

    var kmNuevo = parseInt(document.querySelector("#km_salida_taller").value);

    if(kmNuevo <= 1)
    {
      mostrarModal(1,null,"Novedad salida a taller","No ha ingresado el Km del vehículo.\n",0,"Aceptar","",null);                  
      return;  
    }

    if(document.querySelector("#txt_obser_salida_Taller").value == "")
    {
      mostrarModal(1,null,"Novedad salida a taller","Hace falta ingresar la observación.\n",0,"Aceptar","",null);                  
      return;    
    }

    var kmPro = 0;

    if(solicitarKMProximo)
    {
      if(document.querySelector("#km_proximo").value == "" || parseInt(document.querySelector("#km_proximo").value) <= 1)
      {
        mostrarModal(1,null,"Novedad salida a taller","No ha ingresado el Km próximo del vehículo.\n",0,"Aceptar","",null);                  
        return;    
      }

      kmPro = parseInt(document.querySelector("#km_proximo").value) + 5000;
    }
    
    
    

    
    var datos = {
        incidencia: document.querySelector("#txt_incidencia_salida_taller").value,
        obser : document.querySelector("#txt_obser_salida_Taller").value,
        costo_salida : (document.querySelector("#costo_salida").value == "" ? 0 : document.querySelector("#costo_salida").value),
        km : kmPro,
        km_salida : kmNuevo,
        opc: 21
    };

    consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 12); 
}

//CANCELAR INGRESO A TALLER
function cancelarIngresoTaller()
{
  if(document.querySelector("#txt_incidencia_ingreso_taller").value == "")
  {
    mostrarModal(1,null,"Cancelar remisión a taller","No ha seleccionado una incidencia.\n",0,"Aceptar","",null);                  
    return;    
  }

  if(document.querySelector("#txt_obser_ingreso_Taller").value == "" )
  {
    mostrarModal(1,null,"Cancelar remisión a taller","Hace falta ingresar la observación.\n",0,"Aceptar","",null);                  
    return;    
  }

  var datos = {
      incidencia: document.querySelector("#txt_incidencia_ingreso_taller").value,
      obser : document.querySelector("#txt_obser_ingreso_Taller").value,
      opc: 25
  };

  consultaAjax("../../rutaInsercionTransporte", datos, 15000, "POST", 15); 
}


//ENTREGA A OPERACIÓN
function entregaOpeacion()
{
  var datos = {
        opc: 14
  };

  consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 13); 
  modal_entrega_opeacion
}

function consultaAjax(route,datos,tiempoEspera,type,opcion,edita,dato)
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
            if(opcion == 1)//Recibir Notificación
            {
                ocultarSincronizacion();
                mostrarModal(2,null,"Asignación de incidencia","Se ha asignado correctamente la incidencia al supervisor.\n",0,"Aceptar","",null);                
                console.log("Notificación: " + data);
                setTimeout(function()
                {
                    inst1.close();
                    location.reload();
                },1500);
            }

            if(opcion == 2)//Consulta Componente Ärbol de deciciones
            {
                var html = "";
                html += "<option value = '0'>Seleccione</option>"; 
                for (var i = 0; i < data.length; i++) {
                  html += "<option value = '" + data[i].id + "'>" + data[i].descripcion + "</option>"; 
                };
                document.querySelector("#selectComponente").innerHTML = html;

                if(data.length == 1)
                {
                  document.querySelector("#selectComponente").selectedIndex = 1;
                  var datos = {
                      arbol: document.querySelector("#selectComponente").value,
                      opc: 8,
                      version : document.querySelector("#version_Arbol").value
                  };
                  consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 3); 
                }

                if(edita != undefined)
                {
                  document.querySelector("#selectComponente").value = edita[0];
                  var datos = {
                      arbol: document.querySelector("#selectComponente").value,
                      opc: 8,
                      version : document.querySelector("#version_Arbol").value
                  };
                  consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 3,edita); 
                }
                else
                  ocultarSincronizacion();
            }

            if(opcion == 3)//Consulta Tipo de falla
            {
                var html = "";
                html += "<option value = '0'>Seleccione</option>"; 
                for (var i = 0; i < data.length; i++) {
                  html += "<option value = '" + data[i].id + "'>" + data[i].descripcion + "</option>"; 
                };
                document.querySelector("#selectTipoFalla").innerHTML = html;

                if(data.length == 1)
                {
                  document.querySelector("#selectTipoFalla").selectedIndex = 1;
                  var datos = {
                        arbol: document.querySelector("#selectTipoFalla").value,
                        opc: 11,
                        version : document.querySelector("#version_Arbol").value
                    };
                    consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 8); 
                }

                if(edita != undefined)
                {
                    document.querySelector("#selectTipoFalla").value = edita[1];
                    var datos = {
                        arbol: document.querySelector("#selectTipoFalla").value,
                        opc: 11,
                        version : document.querySelector("#version_Arbol").value
                    };
                    consultaAjax("../../rutaConsultaTransporte", datos, 15000, "POST", 8,edita[2]); 
                }
                else
                  ocultarSincronizacion();
              
            }

            if(opcion == 4)//Consulta Existencia placa
            {

                 var odometro = data[1];
                 data = data[0];


                 document.querySelector("#txt_fecha_ultimo_kilometraje").value = "";
                 document.querySelector("#txt_ultimo_kilometraje_reportado").value = "";



                if(data > 0)
                {
                   document.querySelector("#select_tipo_incidencia").disabled = false;
                   document.querySelector("#txt_observacion").readOnly = false;
                   document.querySelector("#txt_kilometraje").readOnly = false;

                   document.querySelector("#panel_0").style.display = "block";
                   document.querySelector("#selectArbol1").disabled = false;
                   document.querySelector("#selectComponente").disabled = false;
                   document.querySelector("#selectTipoFalla").disabled = false;
                   document.querySelector("#selectRespuesta").disabled = false;
                   
                   document.querySelector("#checbox_inhabilita").disabled = true;   

                   if(odometro.fecha)
                   {

                    if( odometro.kilometraje.replace(".00","") != "")
                    {
                      document.querySelector("#txt_fecha_ultimo_kilometraje").value = odometro.fecha;
                      document.querySelector("#txt_ultimo_kilometraje_reportado").value =  odometro.kilometraje.replace(".00","");  
                    }
                    
                   }              
                }
                else
                {
                  if(data == -1)
                  {
                    mostrarModal(1,null,"Consulta vehículo","El vehículo esta retirado.\n",0,"Aceptar","",null);                  
                    document.querySelector("#txt_vehiculo_incidencia").value = "";
                  }
                  else
                    mostrarModal(1,null,"Consulta vehículo","El vehículo no existe registrado.\n",0,"Aceptar","",null);                  

                  document.querySelector("#select_tipo_incidencia").disabled = true;
                  document.querySelector("#panel_0").style.display = "none";
                  document.querySelector("#txt_observacion").readOnly = true;
                  document.querySelector("#txt_kilometraje").readOnly = true;
                  document.querySelector("#selectArbol1").disabled = true;
                  document.querySelector("#selectComponente").disabled = true;
                  document.querySelector("#selectTipoFalla").disabled = true;
                  document.querySelector("#selectRespuesta").disabled = true;                  
                  document.querySelector("#checbox_inhabilita").disabled = true;
                  document.querySelector("#txt_tiempo_estimado").readOnly = true;
                }
                ocultarSincronizacion();
            }

            if(opcion == 5) //Save Incidencia
            {
              ocultarSincronizacion();
              
              if(data == "-2")
              {
                mostrarModal(1,null,"Guardar incidencia","La incidencia ya fue asignada a un técnico.\n",0,"Aceptar","",null);
                return; 
              }

              if(data == "-3")
              {
                mostrarModal(1,null,"Guardar incidencia","La incidencia ya fue remitida a taller.\n",0,"Aceptar","",null);
                return; 
              }

              if(data == "-4")
              {
                mostrarModal(1,null,"Guardar incidencia","La incidencia ya tiene ingreso a taller.\n",0,"Aceptar","",null);
                return; 
              }

              if(data == "-5")
              {
                mostrarModal(1,null,"Guardar incidencia","La incidencia ya tiene salida a taller.\n",0,"Aceptar","",null);
                return; 
              }

              if(data == "-6")
              {
                mostrarModal(1,null,"Guardar incidencia","La incidencia ya fue finalizada.\n",0,"Aceptar","",null);
                return; 
              }

              if(data == "-7")
              {
                mostrarModal(1,null,"Guardar incidencia","La incidencia ha sido asignada a propietario.\n",0,"Aceptar","",null);
                return; 
              }


              if(data == 0)
              {
                mostrarModal(1,null,"Guardar incidencia","No se pudo guardar el plan de acción de la incidencia.\n",0,"Aceptar","",null);
                return;
              }
              
              if(data == 1)
                mostrarModal(2,null,"Guardar incidencia","Se ha actualizado correctamente el plan de acción de la incidencia.\n",0,"Aceptar","",null);

              if(data.length > 5)
              {
                mostrarModal(2,null,"Guardar incidencia","Se ha guardado correctamente el plan de acción de la incidencia.\n",0,"Aceptar","",null);
                document.querySelector("#id_incidencia").value = data;
                document.querySelector("#txt_incidencia_estado").value = "GENERADA";
              }

              consultaNovedades();
              consultaNuevosDatos();

            } 
            if(opcion == 6) //Consulta Acción
            {
              if(data.length == 0)
              {
                document.querySelector("#img_consulta_ajax").innerHTML = '';
                document.querySelector("#novedades_no_tiene").innerHTML = '<p class="user"><i class="fa fa-user-times" aria-hidden="true"></i> No tiene novedades para esta incidencia</p>';
                document.querySelector("#novedades_user").innerHTML = "";
                var crearN = '';
                @if($acceso == "W")
                crearN += '<button type="button" class="btn btn-primary" onclick="crear_novedad()" style="background-color:#0060ac;color:white;        margin-top: 9px;    margin-bottom: 13px;   position: relative;    left: 82%;" id="bnt_add_novedad"><i class="fa fa-plus" aria-hidden="true"></i> Agregar acción</button><div id="otro_elemento_new"></div>';
                document.querySelector("#crear_novedad").innerHTML = crearN;
                @endif
                document.querySelector("#img_consulta_ajax").style.display = "none";
                document.querySelector("#novedades_no_tiene").style.display = "block";
                document.querySelector("#novedades_user").style.display = "none";
                document.querySelector("#crear_novedad").style.display = "block";
              }
              else
              {
                document.querySelector("#img_consulta_ajax").innerHTML = '';
                document.querySelector("#novedades_no_tiene").innerHTML = '';
                document.querySelector("#novedades_user").innerHTML = "";
                @if($acceso == "W")
                var crearN = '<button type="button" class="btn btn-primary" onclick="crear_novedad()" style="background-color:#0060ac;color:white;        margin-top: 9px;    margin-bottom: 13px;   position: relative;    left: 82%;" id="bnt_add_novedad"><i class="fa fa-plus" aria-hidden="true"></i> Agregar acción</button><div id="otro_elemento_new"></div>';
                document.querySelector("#crear_novedad").innerHTML = crearN;
                @endif

                document.querySelector("#img_consulta_ajax").style.display = "none";
                document.querySelector("#novedades_no_tiene").style.display = "none";
                document.querySelector("#novedades_user").style.display = "block";
                document.querySelector("#crear_novedad").style.display = "block";

                var html = '';
                for (var i = 0; i < data.length; i++) {
                  html += '<div class="novedad_user">';
                  var tipo_user = "CENTRO DE CONTROL:";
                  if(data[i][0].tipo == 3)
                    tipo_user = "TÉCNICO:";

                  if(data[i][0].tipo == 2)
                    tipo_user = "CONDUCTOR:";

                  html += '    <p class="user"><i class="fa fa-user-circle-o" aria-hidden="true"></i>' + tipo_user + "" + data[i][1] + '</p>';
                  html += '    <p class="date"><i class="fa fa-calendar" aria-hidden="true"></i>' + data[i][0].fecha_servidor.split(".")[0] + '</p>';
                  html += '    <p class="content"><i class="fa fa-commenting-o" aria-hidden="true"></i>&quot;' + data[i][0].observacion + '&quot;</p>';
                  html += '</div>';
                };
                document.querySelector("#novedades_user").innerHTML = html;
              }
            }

            if(opcion == 7) //Save Acción
            {
              ocultarSincronizacion();
              if(data == 1)
              {
                mostrarModal(2,null,"Guardar novedad","Se ha guardado correctamente la novedad.\n",0,"Aceptar","",null);
                consultaNovedades();
              }
            }

            if(opcion == 8)//Consulta Respuesta
            {
                var html = "";
                html += "<option value = '0'>Seleccione</option>"; 
                for (var i = 0; i < data.length; i++) {
                  html += "<option value = '" + data[i].id + "' data-in='" + data[i].inhabilita + "' data-asintencia='" + data[i].asistencia_sitio + "' data-desplazamiento1='" + data[i].desplazamiento_sin_grua + "' data-desplazamiento2='" + data[i].desplazamiento_sede + "' data-tiempo='" + data[i].tiempo_estimado + "'>" + data[i].descripcion + "</option>"; 
                };
                document.querySelector("#selectRespuesta").innerHTML = html;
                
                if(data.length == 1)
                {
                  document.querySelector("#selectRespuesta").selectedIndex = 1;
                  habilitaRespuestas(-1);
                }

                if(edita != undefined)
                {
                  document.querySelector("#selectRespuesta").value = edita;
                  habilitaRespuestas(1);
                }
                ocultarSincronizacion();
            }

            if(opcion == 9) //Consulta Novedades - Ingreso Taller
            {
              var html = "";

              html += '<table id="tbl_ingreso_taller" class="table table-striped table-bordered table_center dt-responsive" cellspacing="0" width="94%;margin-left:3%;">';
              html += '    <thead>';
              html += '        <tr>';
              html += '            <th style="width:10px;">No.</th>';
              html += '            <th style="width:100px;">Proyecto</th>';
              html += '            <th style="width:100px;">Incidencia</th>';
              html += '            <th style="width:100px;">Fecha creación</th>';
              html += '            <th style="width:100px;">Placa</th>';
              html += '            <th style="width:100px;">Observación</th>';
              html += '            <th style="width:100px;">Taller asignado</th>';
              html += '            <th style="width:100px;">Dirección</th>';
              html += '            <th style="width:100px;">Tiempo estimado</th>';
              html += '            <th style="width:100px;">Org Compras</th>';
              html += '            <th style="width:100px;">Pep</th>';
              html += '            <th style="width:100px;">Centro Logistico</th>';
              html += '            <th></th>';
              html += '        </tr>';
              html += '    </thead>';
              html += '    <tbody>';
              for (var i = 0; i < data.length; i++) {
                html += "<tr>";
                html += "<td>" + (i + 1)  + "</td>";
                html += "<td>" + (data[i].nombreP == undefined ? '' : data[i].nombreP)  + "</td>";
                html += "<td>" + data[i].incidencia  + "</td>";
                html += "<td>" + data[i].fecha_servidor.split(".")[0] + "</td>";
                html += "<td>" + data[i].placa + "</td>";
                html += "<td>" + data[i].observacion  + "</td>";
                html += "<td>" + data[i].nombre_proveedor + "</td>";
                html += "<td>" + (data[i].direccion == null ? "" : data[i].direccion) + "</td>";
                html += "<td>" + data[i].tiempo_estimado + "</td>";
                html += "<td>" + data[i].grupo_compras + "</td>"; 
                html += "<td>" + data[i].codigo_pep + "</td>";
                html += "<td>" + data[i].centro_logistico + "</td>";                  
                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregaIngresoTaller(this)'></i></td>";
                html += "</tr>";
              };
              html += '</tbody>';
              html += '</table>';
              document.querySelector("#tbl_novedades_ingreso_taller").innerHTML = html;
              $('#tbl_ingreso_taller').DataTable({
                        responsive: true
                    } );
              ocultarSincronizacion();
              $("#modal_ingreso_taller").modal("toggle");
            }

            if(opcion == 10) //Save ingreso taller
            {
              if(data == 1)
              {
                mostrarModal(2,null,"Guardar Ingreso Taller","Se ha guardado correctamente.\n",0,"Aceptar","",null);                
              }
              $("#modal_ingreso_taller").modal("toggle");
              //ocultarSincronizacion();
              consultaNuevosDatos();
            }

            if(opcion == 11) //Consulta Novedades - Salida Taller
            {
              var html = "";
              html += '<table id="tbl_salida_taller" class="table table-striped table-bordered table_center dt-responsive" cellspacing="0" width="94%;margin-left:3%;">';
              html += '    <thead>';
              html += '        <tr>';
              html += '            <th style="width:10px;">No.</th>';
              html += '            <th style="width:100px;">Incidencia</th>';
              html += '            <th style="width:100px;">Fecha creación</th>';
              html += '            <th style="width:100px;">Placa</th>';
              html += '            <th style="width:100px;">Observación ingreso a taller</th>';
              html += '            <th style="width:100px;">Taller asignado</th>';
              html += '            <th style="width:100px;">Usuario registra ingreso</th>';
              html += '            <th style="width:100px;">Tiempo estimado</th>';
              html += '            <th style="width:100px;">Fecha de ingreso</th>';
              html += '            <th style="width:100px;">Observación de ingreso</th>';
              html += '            <th style="width:100px;">Costo de ingreso</th>';
              html += '            <th></th>';
              html += '        </tr>';
              html += '    </thead>';
              html += '    <tbody>';
              for (var i = 0; i < data.length; i++) {
                html += "<tr>";
                html += "<td>" + (i + 1)  + "</td>";
                html += "<td>" + data[i].incidencia  + "</td>";
                html += "<td>" + data[i].fecha_servidor.split(".")[0] + "</td>";
                html += "<td>" + data[i].placa + "</td>";
                html += "<td>" + data[i].observacion1  + "</td>";
                html += "<td>" + data[i].nombre_proveedor + "</td>";
                html += "<td>" + data[i].tiempo_estimado + "</td>";
                html += "<td>" + data[i].propietario + "</td>";
                html += "<td>" + data[i].fecha_ingreso.split(".")[0] + "</td>";
                html += "<td>" + data[i].observacion  + "</td>";
                html += "<td>" + (data[i].costo == null ? '' : '$ ' + data[i].costo)  + "</td>";
                html += "<td><i class='fa fa-external-link' data-resp = '" + data[i].respuesta + "' aria-hidden='true' style='cursor:pointer' onclick='agregaSalidaTaller(this)'></i></td>";
                html += "</tr>";
              };
              html += '</tbody>';
              html += '</table>';
              document.querySelector("#tbl_novedades_salir_taller").innerHTML = html;
              $('#tbl_salida_taller').DataTable({
                        responsive: true
                    } );
              ocultarSincronizacion();
              $("#modal_salida_taller").modal("toggle");
            }

            if(opcion == 12) //Save ingreso taller
            {
              if(data == 1)
              {
                mostrarModal(2,null,"Guardar Salida de Taller","Se ha guardado correctamente.\n",0,"Aceptar","",null);                
              }
              $("#modal_salida_taller").modal("toggle");
              consultaNuevosDatos();
            }

            if(opcion == 13) //Consulta Novedades - Entrega opeación
            {
              var html = "";
              html += '<table id="tbl_entrega_operacion" class="table table-striped table-bordered table_center" cellspacing="0" width="94%;margin-left:3%;">';
              html += '    <thead>';
              html += '        <tr>';
              html += '            <th style="width:10px;">No.</th>';
              html += '            <th style="width:100px;">Incidencia</th>';
              html += '            <th style="width:100px;">Fecha creación</th>';
              html += '            <th style="width:100px;">Placa</th>';
              html += '            <th style="width:100px;">Observación ingreso a taller</th>';
              html += '            <th style="width:100px;">Taller asignado</th>';
              html += '            <th style="width:100px;">Tiempo estimado</th>';
              html += '            <th style="width:100px;">Costo ingreso</th>';
              html += '            <th style="width:100px;">Usuario registra ingreso taller</th>';
              html += '            <th style="width:100px;">Fecha de ingreso</th>';
              html += '            <th style="width:100px;">Observación de ingreso</th>';
              html += '            <th style="width:100px;">Usuario registra salida taller</th>';
              html += '            <th style="width:100px;">Fecha de salida</th>';
              html += '            <th style="width:100px;">Observación de salida</th>';
              html += '            <th style="width:100px;">Costo salida</th>';
              html += '            <th></th>';
              html += '        </tr>';
              html += '    </thead>';
              html += '    <tbody>';
              for (var i = 0; i < data.length; i++) {
                html += "<tr>";
                html += "<td>" + (i + 1)  + "</td>";
                html += "<td>" + data[i].incidencia  + "</td>";
                html += "<td>" + data[i].fecha_servidor.split(".")[0] + "</td>";
                html += "<td>" + data[i].placa + "</td>";
                html += "<td>" + data[i].observacion1  + "</td>";
                html += "<td>" + data[i].nombre_proveedor + "</td>";
                html += "<td>" + data[i].tiempo_estimado + "</td>";
                html += "<td>" + (data[i].costo_ingreso == null ? '' : "$" + data[i].costo_ingreso) + "</td>";
                html += "<td>" + data[i].propietario + "</td>";
                html += "<td>" + data[i].fecha_ingreso.split(".")[0] + "</td>";
                html += "<td>" + data[i].observacion  + "</td>";
                html += "<td>" + data[i].propietario2 + "</td>";
                html += "<td>" + data[i].fecha_salida.split(".")[0] + "</td>";
                html += "<td>" + data[i].observacion_salida  + "</td>";
                html += "<td>" + (data[i].costo_salida == null ? '' : "$" + data[i].costo_salida) + "</td>";
                html += "<td><i class='fa fa-external-link' aria-hidden='true' style='cursor:pointer' onclick='agregaSalidaTaller(this)'></i></td>";
                html += "</tr>";
              };
              html += '</tbody>';
              html += '</table>';
              document.querySelector("#tbl_novedades_entrega_operacion").innerHTML = html;
              $('#tbl_entrega_operacion').DataTable();
              ocultarSincronizacion();
              $("#modal_entrega_opeacion").modal("toggle");
            }
            
            if(opcion == 14)//Consulta ubicaciones GPS
            {
              var caracteristicas = [];
              for (var i = 0; i < data[0].length; i++) {
                var latitud = data[0][i].coordenadas.split(",")[0];
                var longitud = data[0][i].coordenadas.split(",")[1];
                caracteristicas.push(
                {
                  position: new google.maps.LatLng(latitud,longitud),
                      type: data[0][i].icono,
                      datos: {
                          id: data[0][i].id,
                          nombreProveedor: data[0][i].nombre_proveedor,
                          nit: data[0][i].nit,
                          direccion: data[0][i].direccion,
                          telefono: data[0][i].telefono,
                          proyecto: data[0][i].proyecto,
                          coordenadas: data[0][i].coordenadas,
                          tipo: data[0][i].tipo,
                          cantidad: data[0][i].cantidad}
                });
              };
            
              for (var i = 0; i < data[1].length; i++) {

                if(data[1][i].coordenadas != null && data[1][i].coordenadas != undefined && data[1][i].coordenadas != "")
                {
                  var latitud = data[1][i].coordenadas.split(",")[0];
                  var longitud = data[1][i].coordenadas.split(",")[1];
                  caracteristicas.push(
                  {
                    position: new google.maps.LatLng(latitud,longitud),
                        type: data[1][i].icono,
                        datos: {
                              inci: data[1][i].incidencia,
                              tipo: data[1][i].tipo_incidencia,
                              fecha: data[1][i].fecha_servidor,
                              obser: data[1][i].observacion,
                              direccion1: data[1][i].coordenadas,
                              placa: data[1][i].placa,
                              nove: data[1][i].novedadReportada,
                              comp: data[1][i].componente,
                              tipo_falla: data[1][i].tipo_falla,
                              tiempo_estimado: data[1][i].tiempo_estimado,
                              accion: data[1][i].accion,
                              taller_asignado: data[1][i].taller_asignado,
                              tecnico_asignado: data[1][i].tecnico_asignado,
                              base_asignada: data[1][i].base_asignada,
                              fecha_asignacion: data[1][i].fecha_asignacion,
                              id_estado: data[1][i].id_estado,
                              direccion2: data[1][i].direccion,
                              inhabilitado: data[1][i].inhabilitado,
                              nombre: data[1][i].nombre,
                              nombreT: data[1][i].nombreT,
                              otro: data[1][i].otro,
                              respuesta : data[1][i].respuesta,
                              version : data[1][i].version,
                              km : data[1][i].km}
                  });
                }
              };

              //´Técnico
              for (var i = 0; i < data[3].length; i++) {
                //alert(data[3][i].icono);
                caracteristicas.push(
                {
                  position: new google.maps.LatLng(data[3][i].latitud,data[3][i].longitud),
                      type: data[3][i].icono,
                      datos: {
                          nombre: data[3][i].nombres,
                          identificacion: data[3][i].identificacion,
                          foto: data[3][i].adjunto_foto_hv,
                          incidencia: data[3][i].incidencia,
                          tiempo: data[3][i].tiempo,
                          estadoIncidencia: data[3][i].estadoIncidencia,
                          estadoTecnico: data[3][i].estadoTecnico,
                          fechaAsignacion: data[3][i].fechaAsignacion,
                          fechaRecepcion: data[3][i].fechaRecepcion,
                          fechaAceptacion: data[3][i].fechaAceptacion,
                          vehiculoGenerador: data[3][i].vehiculoGenerador,
                          }
                });
              };

              for (var i = 0; i < data[2].length; i++) {
                caracteristicas.push(
                {
                  position: new google.maps.LatLng(data[2][i].latitud,data[2][i].longitud),
                      type: data[2][i].icono,
                      datos: {
                          nombreConductor: data[2][i].nombreConductor,
                          identificacion: data[2][i].identificacion,
                          placa: data[2][i].placa,
                          fotoVehiculo: data[2][i].fotoVehiculo,
                          fotoConductor: data[2][i].fotoConductor,
                          ultimaIncidencia: data[2][i].ultimaIncidencia,
                          tiempoInhabilitacion: data[2][i].tiempoInhabilitacion,
                          estadoIncidencia: data[2][i].estadoIncidencia,
                          estadoVehiculo: data[2][i].estadoVehiculo,
                          fecha_asignacion: data[2][i].fecha_asignacion,
                          taller_asignado: data[2][i].taller_asignado,
                          tecnico_asignado: data[2][i].tecnico_asignado,
                          base_asignada: data[2][i].base_asignada,
                          accion: data[2][i].accion
                          }
                });
              };

                var valores = function (latitud, longitud, fecha, hora) {
                  document.querySelector("#lblLatitud").innerHTML = latitud;
                  document.querySelector("#lblLongitud").innerHTML = longitud;
                  document.querySelector("#lblFecha").innerHTML = fecha;
                  document.querySelector("#lblHora").innerHTML = hora;
                }

                mapa.datosMarker =  caracteristicas;
                mapa.pintarMarcadores;

                ocultarSincronizacion();
                verSincronizacion();
            }

            if(opcion == 15) //Save ingreso taller
            {
              ocultarSincronizacion();
              mostrarModal(2,null,"Cancelar remisión a taller","Se ha guardado correctamente.\n",0,"Aceptar","",null);                
              $("#modal_ingreso_taller").modal("toggle");
              consultaNuevosDatos();
            }

        },
        error:function(request,status,error){
            ocultarSincronizacion();
            //$('#filter_registro').modal('toggle');

            mostrarModal(1,null,"Problema con la conexión a internet","Existen problemas con la conexión a internet, comuníquese con la persona encargada del manejo de la red.\n",0,"Aceptar","",null);
            setTimeout(function()
            {
                //location.reload();
            },3000);
        }

    });
}


</script>

<!-- if($opcion == 7)// Consulta Componente Árbol de decisiones -->