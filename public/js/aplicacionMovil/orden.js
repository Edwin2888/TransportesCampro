var server = "http://201.217.195.43/camprolaravel/";
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};


window.addEventListener('load',ini);

/*VARIABLES*/
var _miPosicion;
var _miPosicionGPS;
var db;
var tiempo;


function ini()
{
    if(isMobile.any())
      document.addEventListener("deviceready", ondeviceReady, false);
    else
      trabajarWeb();

}

document.querySelector("#mensaje_espere").style.position = "fixed";
document.querySelector("#mensaje_espere").style.top = "25%";
document.querySelector("#mensaje_espere").style.width = "16%";
document.querySelector("#mensaje_espere").style.left = "42%";

function trabajarWeb()
{
  //document.querySelector("#id_cargando").style.display = "block";  
  document.querySelector("#emergencias_actividades").style.width = "60%";
  consultaOrdenesAlUser();
}
 var ArregloOrdenes = [];
function consultaOrdenesAlUser()
{ 
  var dat = 
  {
    "usuario" : localStorage.getItem("usuario")
  }

  var route = server + "consultaOrdenes";
 

  $.ajax({
    url: route,
    type: "POST",
    dataType: "json",
    data:{dat},
    success:function(data)
    {
      var html = "";

      for (var i = 0; i < data.length; i++) {

        var HtmlN = "";
        for (var j = 0; j < data[i].nodos.length;j++) {
          HtmlN += data[i].nodos[j].nombre_nodo + ",";
        };

        ArregloOrdenes.push(data[i]);
        html += "<li class='list__item' onclick='abrirActividades(this)' data-orden='" + data[i].orden + "'>";
        html += '<ul>';
        html += '<div class="row" style="border-bottom:1px solid grey;">';
        html += '<div class="col-md-11">';
        html += '<li><strong class="columna1">ORDEN DE TRABAJO:</strong><span>' + data[i].orden + '</span></li> ';
        html += '<li><strong class="columna1">Fecha de Programación:</strong><span>' + data[i].fecha  +'</span></li> ';
        html += '<li><strong class="columna1">Dirección:</strong><span>' + data[i].dire + '</span></li> ';
        html += '<li><strong class="columna1">CD:</strong><span>' +  data[i].cd +'</span></li>';
        html += '<li><strong class="columna1">NT:</strong><span>' +  (data[i].nt == 0 ? "MT" : "BT") +'</span></li>';
        html += '<li><strong class="columna1">Hora Inicio:</strong><span>' + data[i].hora_iniM + '</span></li>';
        html += '<li><strong class="columna1">Hora Corte:</strong><span>' + data[i].hora_corte + '</span></li>';
        html += '<li><strong class="columna1">Hora Cierre:</strong><span>' + data[i].hora_cierre + '</span></li>';
        html += '<li><strong class="columna1">Hora Fin:</strong><span>' + data[i].hora_finM + '</span></li>';
        html += '<li><strong class="columna1">NODOS:</strong><span>' + HtmlN + '</span></li>';
        html += '<li><strong class="columna1">Observaciones:</strong><span>' + data[i].obser + '</span></li>';
        html += '</div>';
        html += '<div class="col-md-1">';
        html += '<i class="fa fa-angle-right fa-3x" aria-hidden="true"></i>';
        html += '</div> </div></ul></li>';

        /*hora_iniT hora_finT aux1 aux2  aux3 cond matri"*/

      }
        $("#lista_created").html(html);
        document.querySelector("#id_cargando").style.display = "none";  
      //alert(JSON.stringify(data));
    },
    error:function(x,y)
    {
      alert(JSON.stringify(x));
    }
  });

  
}


function abrirActividades(ele)
{

  for (var i = 0; i < ArregloOrdenes.length; i++) {
    if(ArregloOrdenes[i].orden == ele.dataset.orden.trim())
    {
      var HtmlN = "";
      var HtmlID = "";
      for (var j = 0; j < ArregloOrdenes[i].nodos.length;j++) {
        HtmlN += ArregloOrdenes[i].nodos[j].nombre_nodo + ",";
        HtmlID += ArregloOrdenes[i].nodos[j].id_nodo + ",";
      };

      localStorage.setItem("nodos",HtmlN);
      localStorage.setItem("nodosI",HtmlID);
      localStorage.setItem("orden",ArregloOrdenes[i].orden);
      localStorage.setItem("dire",ArregloOrdenes[i].dire);
      localStorage.setItem("fecha",ArregloOrdenes[i].fecha);
      localStorage.setItem("cd",ArregloOrdenes[i].cd);
      localStorage.setItem("nt",ArregloOrdenes[i].nt);
      localStorage.setItem("hora_iniM",ArregloOrdenes[i].hora_iniM);
      localStorage.setItem("hora_corte",ArregloOrdenes[i].hora_corte);
      localStorage.setItem("hora_cierre",ArregloOrdenes[i].hora_cierre);
      localStorage.setItem("hora_finM",ArregloOrdenes[i].hora_finM);
      localStorage.setItem("obser",ArregloOrdenes[i].obser);

      //alert("Encontrada");
      window.location.href = "http://201.217.195.43/camprolaravel/baremosAppProgramados";
    }
  };
  //alert();
  //alert(JSON.stringify(ArregloOrdenes));
  /*
  window.location.href = "../baremosAppProgramados";*/
}

//Agrega Eventos de Bateria y de botón hacia atras
function agregarEventosExtros()
{
    document.addEventListener("backbutton", backboton, false);
    window.addEventListener("batterycritical", onBatteryCritical, false);
    window.addEventListener("batterylow", onBatteryLow, false);
}

function backboton()
{
    if(document.querySelector("#alert_seleccionado").style.display == "block")
    {
        document.querySelector("#alert_seleccionado").style.display = "none"   ;
        return;
    }

    if(document.querySelector("#alert_seleccionado_waze").style.display == "block")
    {
        document.querySelector("#alert_seleccionado_waze").style.display = "none"   ;
        return;
    }

    if(document.querySelector("#alert_llegada_Waze").style.display == "block")
    {
        document.querySelector("#alert_llegada_Waze").style.display = "none"   ;
        return;
    }

    if(document.querySelector("#alert_sin_terminar").style.display == "block")
    {
        document.querySelector("#alert_sin_terminar").style.display = "none"   ;
        return;
    }

    if(document.querySelector("#alert_finalizado").style.display == "block")
    {
        document.querySelector("#alert_finalizado").style.display = "none"   ;
        return;
    }

    if(document.querySelector("#alert_inicio_desplazamiento_sin_gps").style.display == "block")
    {
        document.querySelector("#alert_inicio_desplazamiento_sin_gps").style.display = "none"   ;
        return;
    }

    if(document.querySelector("#alert_fin_desplazamiento_sin_gps").style.display == "block")
    {
        document.querySelector("#alert_fin_desplazamiento_sin_gps").style.display = "none"   ;
        return;
    }

    if(document.querySelector("#alert_sin_terminar_sin_gps").style.display == "block")
    {
        document.querySelector("#alert_sin_terminar_sin_gps").style.display = "none"   ;
        return;
    }

    if(document.querySelector("#alert_sin_terminar_sin_gps").style.display == "block")
    {
        document.querySelector("#alert_sin_terminar_sin_gps").style.display = "none"   ;
        return;
    }
    
    return;
}
function onBatteryCritical()
{
    alert("Nivel de batería  crítico " + info.level + "%\nRecargar ahora!");
}

function onBatteryLow()
{
    alert("Nivel de batería bajo: " + info.level + "%");
}



function ondeviceReady()
{   
    /*window.plugins.toast.showLongBottom('Bienvenido', 
    function(a){console.log('toast success: ' + a)}, 
    function(b){alert('toast error: ' + b)});*/
    /*db = window.sqlitePlugin.openDatabase({name: "ordenBD.db", iosDatabaseLocation: 'default'});
    calcularCantidadSinEnviar();
    cargarDatosLista();
    document.querySelector("#btn_crear_orden").addEventListener("click",function()
    {
        localStorage.setItem("cargando",0);
        document.querySelector("#id_cargando").style.display = "block";
        window.location.href = "crear.html";
    });

    document.querySelector("#cerrar_session").addEventListener("click",cessarS);
    document.querySelector("#upload").addEventListener("click",enviarInformacionServidorSinEnviar);
    agregarEventosExtros();*/
    //enviarInformacionDatosTotalServidor();
    //cargarEmergencias();
}

function cargarDatosLista()
{
    db.transaction(function (tx) 
    { 
        tx.executeSql("select Orde_numero,Orde_rotulo,Orde_pt,Orde_direc,Orde_loca,Orde_barrio from Orden where Orde_user = '" + localStorage.getItem("usuario") + "' order by Orde_numero desc LIMIT 20;",[],
          function(tx,res)
          {
            //alert(res.rows.length);
            var htmlD = "";
            for (var i = 0; i < res.rows.length; i++) {
                var loc = "";
                if(res.rows.item(i).Orde_loca == "1")
                  loc = "Usaquén";

                if(res.rows.item(i).Orde_loca == "2")
                  loc = "Chapinero";

                if(res.rows.item(i).Orde_loca == "3")
                  loc = "Santa Fe";

                if(res.rows.item(i).Orde_loca == "4")
                  loc = "San Cristóbal";

                if(res.rows.item(i).Orde_loca == "5")
                  loc = "Usme";

                if(res.rows.item(i).Orde_loca == "6")
                  loc = "Tunjuelito";

                if(res.rows.item(i).Orde_loca == "7")
                  loc = "Bosa";

                if(res.rows.item(i).Orde_loca == "8")
                  loc = "Kennedy";

                if(res.rows.item(i).Orde_loca == "9")
                  loc = "Fontibón";

                if(res.rows.item(i).Orde_loca == "10")
                  loc = "Engativá";

                if(res.rows.item(i).Orde_loca == "11")
                  loc = "Suba";

                if(res.rows.item(i).Orde_loca == "12")
                  loc = "Barrios Unidos";

                if(res.rows.item(i).Orde_loca == "13")
                  loc = "Teusaquillo";

                if(res.rows.item(i).Orde_loca == "14")
                  loc = "Los Mártires";

                if(res.rows.item(i).Orde_loca == "15")
                  loc = "Antonio Nariño";

                if(res.rows.item(i).Orde_loca == "16")
                  loc = "Puente Aranda";

                if(res.rows.item(i).Orde_loca == "17")
                  loc = "La Candelaria";

                if(res.rows.item(i).Orde_loca == "18")
                  loc = "Rafael Uribe Uribe";

                if(res.rows.item(i).Orde_loca == "19")
                  loc = "Ciudad Bolívar";

                if(res.rows.item(i).Orde_loca == "20")
                  loc = "Sumapaz";

                if(res.rows.item(i).Orde_loca == "21")
                  loc = "Soacha";


                //alert(JSON.stringify(res.rows.item(i)))
                htmlD += '<li class="list__item" onclick = "abriOrden(' + res.rows.item(i).Orde_numero  + ')">'
                htmlD += '    <ul style="margin-bottom:0px;">';
                htmlD += '    <div class="row" style="border-bottom: 1px solid gray;"> ';
                htmlD += '     <div class="col-md-11"> ';
                htmlD += '        <li><strong class="columna1">N° de rótulo:</strong><span>' + res.rows.item(i).Orde_rotulo + '</span></li>  ';
                htmlD += '         <li><strong class="columna1">N° de punto físico:</strong><span>' + res.rows.item(i).Orde_pt + '</span></li>  ';
                htmlD += '         <li><strong class="columna1">Dirección:</strong>' + res.rows.item(i).Orde_direc + '</span></li>  ';
                htmlD += '         <li><strong class="columna1">Localidad:</strong><span>' + loc + '</span></li>  ';
                htmlD += '         <li><strong class="columna1">Barrio:</strong><span>' + res.rows.item(i).Orde_barrio + '</span></li> ';
                htmlD += '     </div> ';
                htmlD += '     <div class="col-md-1"> ';
                htmlD += '        <i class="fa fa-angle-right fa-3x" aria-hidden="true"></i> ';
                htmlD += '     </div>      ';
                htmlD += '     </div> ';
                htmlD += '    </ul>  ';
                htmlD += ' </li>  ';
            };

            $("#lista_created").html(htmlD);
            setTimeout(function()
            {
              document.querySelector("#id_cargando").style.display = "none";
            },1500);
            
          },
          function (e)
          {
            alert("Error al cargar la información => " + e.message);
          });
    });

    /**/ 
}

function abriOrden(num)
{
    localStorage.setItem("dato-orden",num);
    localStorage.setItem("cargando",1);
    window.location.href = "crear.html";
}

var auxMax = 0;
function calcularCantidadSinEnviar()
{
    db.transaction(function (tx) 
    { 
        tx.executeSql("select Orde_numero,Orde_rotulo,Orde_pt,Orde_direc,Orde_loca,Orde_barrio from Orden where Orde_user = '" + localStorage.getItem("usuario") + "' and Sincronizado = 0;",[],
          function(tx,res)
          {
            document.querySelector("#lbldatos_faltantes").innerHTML = res.rows.length;
            auxMax = res.rows.length;
          },
          function (e)
          {
            alert("Error al cargar la información => " + e.message);
          });
    });
}
function cessarS()
{
    if(document.querySelector("#lbldatos_faltantes").innerHTML != "0")
    {
        alert("No puede cerrar sesión, hasta que envié todas las órdenes")
        return;
    }
    if(confirm("¿Seguro que desea cerrar sesión?"))
    {
        db.transaction(function(tx) 
          { 
             tx.executeSql("UPDATE Inicio SET  " +
                  "Ini_login = 0 , Ini_user = '"  + localStorage.getItem("usuario") +  "';",[],
                  function(tx, res) 
                  {
                        localStorage.removeItem("usuario");
                        localStorage.removeItem("dato-orden");
                        document.querySelector("#id_cargando").style.display = "block";
                        window.location.href = "login.html";
                  }, function(e) 
                  {
                      alert("ERROR al  cerrar sesión: " + e.message);
                  });
          });
    }
}

function enviarInformacionServidorSinEnviar()
{
    db.transaction(function (tx) 
    { 
        tx.executeSql("select Orde_user,Orde_numero,Orde_rotulo,Orde_pt,Orde_direc,Orde_loca,Orde_barrio,Orde_tipo_cuad,Orde_turno,Orde_tipo_falla,Orde_coordenadas from Orden where Orde_user = '" + localStorage.getItem("usuario") + "' and Sincronizado = 0;",[],
          function(tx,res)
          {
            if(res.rows.length > 0 )
            {
                document.querySelector("#id_cargando").style.display = "block";
                for (var i = 0; i < res.rows.length; i++) {
                //
                var arreglo = 
                  {
                    "id_user" : res.rows.item(i).Orde_user,
                    "num_rot" : res.rows.item(i).Orde_rotulo,
                    "num_pt" : res.rows.item(i).Orde_pt,
                    "dir" : res.rows.item(i).Orde_direc,
                    "loca" : res.rows.item(i).Orde_loca,
                    "bar" : res.rows.item(i).Orde_barrio,
                    "tipo_cuad" : res.rows.item(i).Orde_tipo_cuad,
                    "turn_mant" : res.rows.item(i).Orde_turno,
                    "tip_fal" : res.rows.item(i).Orde_tipo_falla,
                    "coord" : res.rows.item(i).Orde_coordenadas,
                    "num" : res.rows.item(i).Orde_numero,
                  };
                    enviarLevantamiento(arreglo);
                    //Consulta fotograias para enviar
                };
                
                //document.querySelector("#id_cargando").style.display = "none";
                //document.querySelector("#lbldatos_faltantes").innerHTML = res.rows.length;
            }
          },
          function (e)
          {
            alert("Error al cargar la información => " + e.message);
          });
    });
}


function enviarLevantamiento(arreg)
{
  var networkState = navigator.connection.type;
  var states = {};
  states[Connection.UNKNOWN]  = 'Unknown connection';
  states[Connection.ETHERNET] = 'Ethernet connection';
  states[Connection.WIFI]     = 'WiFi connection';
  states[Connection.CELL_2G]  = 'Cell 2G connection';
  states[Connection.CELL_3G]  = 'Cell 3G connection';
  states[Connection.CELL_4G]  = 'Cell 4G connection';
  states[Connection.CELL]     = 'Cell generic connection';
  states[Connection.NONE]     = 'No network connection';

  if(!(states[networkState] == 'No network connection' || states[networkState] == 'Unknown connection'))
  {
    //alert("Voy a enviar la información");
      var route = "{{config('app.Campro')[2]}}/campro/webservice/guardarInformacionLevantamiento.php";
      $.ajax({
        url: route,
        type: "POST",
        dataType: "json",
        data:{datos : arreg},
        success:function(data)
        {
          actualizarDatos("Orden","Orde_numero","Orde_user",arreg["num"],arreg["id_user"],1,0);
          enviarFotografiasServer(data[0]['res'],arreg);
        },
        error:function(x,y)
        {
          alert(JSON.stringify(x));
          document.querySelector("#id_cargando").style.display = "none";
          window.location.reload();
        }
      });
  }
  else
  {
    alert("En estos momentos no cuentas con conexión a internet, intenta más tarde");
    document.querySelector("#id_cargando").style.display = "none";
  }
}

function enviarFotografiasServer(orden,arregloD)
{
    db.transaction(function (tx) 
    { 
      tx.executeSql("select Foto_identificacion,Foto_img from Fotografias where Foto_orden = " + arregloD["num"] + " AND Foto_identificacion = '" + arregloD["id_user"]  + "';",[],
        function(tx,res)
        {
           if(res.rows.length > 0)
           {
              var arreglo = Array();
              for (var i = 0; i < res.rows.length; i++) {
                arreglo.push({
                  'id_user' : res.rows.item(i).Foto_identificacion,
                  'orden' :orden,
                  'foto' : res.rows.item(i).Foto_img
                });
              };

              //alert("Arreglo fotografias:" + JSON.stringify(arreglo));
              var route = "{{config('app.Campro')[2]}}/campro/webservice/guardarFotografias.php";
              $.ajax({
                url: route,
                type: "POST",
                dataType: "json",
                data:{datos : arreglo},
                success:function(data)
                {
                  //alert("Respuesta envio Fotografias:" + JSON.stringify(data[0]['res']));
                  if(data[0]['res'] == 1)
                    actualizarDatos("Fotografias","Foto_orden","Foto_identificacion",arregloD["num"],arregloD["id_user"],1,1);
                },
                error:function(x,y)
                {
                  alert(JSON.stringify(x));
                  document.querySelector("#id_cargando").style.display = "none";
                  window.location.reload();
                }
              });
           }
           else
           {
              window.location.reload();
              //window.location.href = "orden.html";
           }

        },
        function (e)
        {
          alert("Error al cargar la información => " + e.message);
          window.location.reload();
        });
    });
}

function actualizarDatos(tabla,col1,col2,update1,update2,tipo,opc)
{
  db.transaction(function(tx) 
    { 
      if(tipo == 1)
      {
        tx.executeSql("UPDATE " + tabla + " SET  " +
                        " Sincronizado = 1" +
                        " WHERE " + col1 + " = " + update1 + " AND " + col2 + " = '" + update2 + "';",[],
            function(tx, res) 
            {
              if(opc == 1)
              {
                setTimeout(function()
                {
                    //document.querySelector("#id_cargando").style.display = "none";
                    window.location.reload();
                },(auxMax * 3000));
              }
              //alert("Todo va bien enviado información");
            }, function(e) 
            {
                alert("ERROR al insertar Actualizar información ya enviada al servidor : " + e.message);
                window.location.reload();
              }); 
      }
   });
}
