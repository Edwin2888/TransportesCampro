
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



// https://www.adictosaltrabajo.com/tutoriales/phonegap-notificaciones-push/
// ->  paG: https://www.adictosaltrabajo.com/tutoriales/configurando-notificaciones-push-android/
// ID: notificaciones-1316, Project NUmber: 648684552117
// Servidor Api: apiServer -> AIzaSyA2zAWKzzwo8C11VytCLhwLlV-iREdHaFM
// https://www.adictosaltrabajo.com/tutoriales/phonegap-notificaciones-push/

var db;
//COnfiguracion https://cordova.apache.org/docs/en/4.0.0/config_ref/
var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        
        if( isMobile.any() )
          document.addEventListener('deviceready', this.onDeviceReady, false);
        else
          window.addEventListener("load",iniWeb);
        
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicitly call 'app.receivedEvent(...);'
    onDeviceReady: function() {


        db = window.sqlitePlugin.openDatabase({name: "ordenBD.db", iosDatabaseLocation: 'default'});
        crearBaseDatos();
        document.querySelector("#id_cargando").style.display = "none";
        db.transaction(function(tx) 
        {
            document.getElementById('btn_sesion').addEventListener('click',btnIniciarSesion);
        });
        
        //cargaImagenes();
    },
    // Update DOM on a Received Event
    receivedEvent: function(id) {

        

    }
};

app.initialize();

function iniWeb()
{
  document.querySelector("#login").style.width = "40%";
  document.querySelector("#btn_sesion").addEventListener("click",btnIniciarSesion);
  document.querySelector("#mensaje_espere").style.position = "fixed";
  document.querySelector("#mensaje_espere").style.top = "25%";
  document.querySelector("#mensaje_espere").style.width = "16%";
  document.querySelector("#mensaje_espere").style.left = "42%";
   

}


function btnIniciarSesion()
{

  if(document.querySelector('#pass').value == "" || document.querySelector('#user').value.toLowerCase() == "")
  {
    alert("Ingrese usuario y contraseña");
    return;
  }
  localStorage.setItem("usuario", document.querySelector('#user').value.toLowerCase());
  if(document.querySelector('#pass').value != localStorage.getItem("usuario"))
  {
    alert("El usuario y la contraseña deben ser los mismos.");
    return;
  }

  var dat = 
  {
    "usuario" : localStorage.getItem("usuario"),
    "pass" : document.querySelector('#pass').value
  }

  document.querySelector("#id_cargando").style.display = "block";  
  var route = server + "validarUsuario";
  $.ajax({
    url: route,
    type: "POST",
    dataType: "json",
    data:{dat},
    success:function(data)
    {
      if(data == "1")
      {
        window.location.href = "http://201.217.195.43/camprolaravel/ordenAppProgramados";
       // document.querySelector("#id_cargando").style.display = "none";
      }
      else{
        alert("El usuario no existe");
        document.querySelector("#id_cargando").style.display = "none";
        return;
      }
    },
    error:function(x,y)
    {
      alert(JSON.stringify(x));
    }
  });
}   

function consultaDatosCombox()
{
  db.transaction(function(tx) 
  { 
    tx.executeSql("select tipo_id from TiposC;", [], function(tx, res) 
    {
      if(res.rows.length == 0)
        consultaWebServicesCombox(1);
    }
    ,function(e) {
          console.log("ERROR: " + e.message);
    });

    tx.executeSql("select turn_id from Turnos;", [], function(tx, res) 
    {
      if(res.rows.length == 0)
        consultaWebServicesCombox(2);
    }
    ,function(e) {
          console.log("ERROR: " + e.message);
    });

    tx.executeSql("select tipo_id from TiposF;", [], function(tx, res) 
    {
      if(res.rows.length == 0)
        consultaWebServicesCombox(3);
      else
        ingresarUsuario();
    }
    ,function(e) {
          console.log("ERROR: " + e.message);
    });
  });
}

function consultaWebServicesCombox(opc,callback)
{
    var route = "{{config('app.Campro')[2]}}/campro/webservice/consultaCombox.php";
    var array = 
    {
      "opcion" : opc
    }

    $.ajax({
      url: route,
      type: "POST",
      dataType: "json",
      data:{datos : array},
      success:function(data)
      {
        guardarDatos(opc,data[0][0]);
      },
      error:function(x,y)
      {
        alert(JSON.stringify(x));
        alert(JSON.stringify(y)); 
      }
    });
}

function guardarDatos(opc,arreglo)
{
  
    for (var i = 0; i < arreglo.length; i++) {
        (function(fila)
        {
          db.transaction(function(tx) 
          {
              if(opc == 1)
              {
                tx.executeSql("INSERT INTO TiposC (tipo_id, tipo_nombre) VALUES (?,?)",
                 [arreglo[fila]["id"],arreglo[fila]["descrip"]], function(tx, res) {
                  console.log("CREADO CORRECTAMENTE TiposC");
                }, function(e,e1) {
                    alert("ERROR al insertar: " + e.message);
                });
              }

              if(opc == 2)
              {
                tx.executeSql("INSERT INTO Turnos (turn_id, turn_nombre) VALUES (?,?)",
                 [arreglo[fila]["id"],arreglo[fila]["descrip"]], function(tx, res) {
                  console.log("CREADO CORRECTAMENTE Turnos");
                }, function(e,e1) {
                    alert("ERROR al insertar: " + e.message);
                });
              }

              if(opc == 3)
              {
                tx.executeSql("INSERT INTO TiposF (tipo_nombre) VALUES (?)",
                 [arreglo[fila]["descrip"]], function(tx, res) {
                  console.log("CREADO CORRECTAMENTE Fallas");
                  if((fila + 1) == i)
                  {
                    ingresarUsuario();
                  }
                }, function(e,e1) {
                    alert("ERROR al insertar: " + e.message);
                });
              }
          });
        }(i));
    };
  
}


function ingresarUsuario()
{
  db.transaction(function(tx) 
          { 
            tx.executeSql("select count(Ini_login) as co from Inicio" + ";", [], function(tx, res) 
            {
              if(res.rows.item(0).co > 0)
              {
                   tx.executeSql("UPDATE Inicio SET  " +
                  "Ini_login = 1 , Ini_user = '"  + localStorage.getItem("usuario") +  "';",[],
                  function(tx, res) 
                  {
                      window.location.href = "orden.html";
                  }, function(e) 
                  {
                      alert("ERROR al  Actualizar  Inicio: " + e.message);
                  }); 
              }
              else
              {
                   tx.executeSql("INSERT into Inicio values (1,'" + localStorage.getItem("usuario") + "')" + ";",[],
                  function(tx, res) 
                  {
                      window.location.href = "orden.html";
                    
                  }, function(e) 
                  {
                      alert("ERROR al insertar Actualizar  Inicio: " + e.message);
                  });
              }
            });
          });
}
 
function crearBaseDatos()
{
    try
    {
      var db = window.sqlitePlugin.openDatabase({name: "ordenBD.db", iosDatabaseLocation: 'default'});
        //var db = openDatabase('MyDB', '1.0', 'My Sample DB', 100 * 1024);
        //localStorage.setItem('bd',  JSON.stringify(db));
        //BASE DE DATOS https://www.npmjs.com/package/cordova-sqlite-storage
        db.transaction(function(tx) {
           // tx.executeSql('DROP TABLE IF EXISTS Incidencia');

           //alert('Inicio Base de datos');

            //tx.executeSql('DROP TABLE IF EXISTS Incidencia');
            //tx.executeSql('DROP TABLE IF EXISTS GrupoTecnico');
             // tx.executeSql('DROP TABLE IF EXISTS GrupoTecnico');
          
          tx.executeSql('CREATE TABLE IF NOT EXISTS Inicio (Ini_login BIGINT,Ini_user VARCHAR(20))');

          
          tx.executeSql('CREATE TABLE IF NOT EXISTS Orden (Orde_numero INTEGER PRIMARY KEY AUTOINCREMENT,' +
                                                                  ' Orde_Numero VARCHAR(20),'+
                                                                  ' Orde_rotulo VARCHAR(20),'+
                                                                  ' Orde_pt VARCHAR(20),' +
                                                                  ' Orde_direc VARCHAR(35), ' +
                                                                  ' Orde_loca VARCHAR(25), ' +
                                                                  ' Orde_barrio VARCHAR(25), ' +
                                                                  ' Orde_tipo_cuad INTEGER, ' + 
                                                                  ' Orde_turno INTEGER, ' + 
                                                                  ' Orde_tipo_falla INTEGER, ' + 
                                                                  ' Orde_coordenadas varchar(50), ' +  
                                                                  ' Sincronizado INTEGER DEFAULT 0)');
            
            //tx.executeSql('DROP TABLE IF EXISTS Fotografias');
          tx.executeSql('CREATE TABLE IF NOT EXISTS Fotografias ( Foto_id  INTEGER PRIMARY KEY AUTOINCREMENT,' +
                                                                  ' Foto_identificacion VARCHAR(20),'+
                                                                  ' Foto_orden INTEGER,'+
                                                                  ' Foto_img TEXT,'+
                                                                  ' Sincronizado INTEGER DEFAULT 0, ' +
                                                                  ' Foto_ori INTEGER DEFAULT 0, ' +
                                                                  ' FOREIGN KEY(Foto_orden) REFERENCES Orden(Orde_numero))');

          tx.executeSql('CREATE TABLE IF NOT EXISTS TiposC (tipo_id VARCHAR(5),' +
                                                                  ' tipo_nombre VARCHAR(20))');

          tx.executeSql('CREATE TABLE IF NOT EXISTS Turnos (turn_id VARCHAR(5),' +
                                                                  ' turn_nombre VARCHAR(20))');

          tx.executeSql('CREATE TABLE IF NOT EXISTS TiposF (tipo_id  INTEGER PRIMARY KEY AUTOINCREMENT,' +
                                                                  ' tipo_nombre VARCHAR(20))');

           /*       cargarSesionSiExiste();
                  app.receivedEvent('deviceready');
                  checkGPS();
                  checkConnection();*/
        }
        , function(e) {
          console.log("ERROR: " + e.message);
        });
    }
    catch(Ex)
    {
        alert('Error en la base de datos: ' + Ex);
    }
}







  
