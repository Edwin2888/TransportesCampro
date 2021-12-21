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
          cargarSesionSiExiste();     
        
        //cargaImagenes();
    },
    // Update DOM on a Received Event
    receivedEvent: function(id) {

        

    }
};

app.initialize();

function iniWeb()
{
    document.querySelector("#id_cargando").style.width = "24%";
    document.querySelector("#id_cargando").style.marginLeft = "38%";
    setTimeout(function()
    {
       window.location.href = "http://201.217.195.43/camprolaravel/loginAppProgramados";
    },4000);  
}

function cargarSesionSiExiste()
{

 if(db  == null || db == undefined)
 {
     setTimeout(function()
          {
             window.location.href = "login.html";
          },7000);
 }
  db.transaction(function(tx) 
    {
        tx.executeSql("select Ini_login,Ini_user from Inicio" + ";", [], function(tx, res) 
        {
          if(res.rows.length == 0)
          {
              setTimeout(function()
              {
                 window.location.href = "login.html";
              },7000);
          }
          
          if(res.rows.item(0).Ini_login == 1)
          {
              setTimeout(function()
              {
                localStorage.setItem("usuario", res.rows.item(0).Ini_user);
                window.location.href = "orden.html";
              },7000);
              
          }
          else
          {
            setTimeout(function()
              {
                 window.location.href = "login.html";
              },7000);

              
          }
        },
        function(err,er)
        {
          if(er.code == 0 || er.code == 5)
          {
             setTimeout(function()
              {
                 window.location.href = "login.html";
              },7000);
          }
        });
    });
}

