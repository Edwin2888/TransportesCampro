<script type="text/javascript">
	window.addEventListener('load',iniHome);

	function iniHome()
	{		

       //Captura URL
       var utr = window.location.href.replace("#modal1","").replace("#modal","");
       history.pushState('', 'Transporte', utr);

	   //Modales Acciones
       $(document).on('opening', '.remodal', function () {
        console.log('opening');
      });

      $(document).on('opened', '.remodal', function () {
        console.log('opened');
      });

      $(document).on('closing', '.remodal', function (e) {
        console.log('closing' + (e.reason ? ', reason: ' + e.reason : ''));
      });

      $(document).on('closed', '.remodal', function (e) {
        console.log('closed' + (e.reason ? ', reason: ' + e.reason : ''));
      });

      $(document).on('confirmation', '.remodal', function () {
        console.log('confirmation');
      });

      $(document).on('cancellation', '.remodal', function () {
        console.log('cancellation');
      });

        $('.navbar-toggle').click(function () 	{
            $('.navbar-nav').toggleClass('slide-in');
            $('.side-body').toggleClass('body-slide-in');
            $('#search').removeClass('in').addClass('collapse').slideUp(200);
        });

	   $('#search-trigger').click(function () {
	        $('.navbar-nav').removeClass('slide-in');
	        $('.side-body').removeClass('body-slide-in');
	    });

       document.querySelector("#menu-hambur").addEventListener("click",function()
       {
            if(document.querySelector("#menu_cam").style.display == "block")
                document.querySelector("#menu_cam").style.display = "none";
            else
                document.querySelector("#menu_cam").style.display = "block";
       });



       //data-date-format="hh:mm"

        $('.form_date').datetimepicker({
            language: 'es',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            minDate: -3
        });
        $('.form_time').datetimepicker({
            language: 'es',
            pickDate: false,
            todayBtn: 1,
            autoclose: 1,
            startView: 1
        });

        $('.form_datetime').datetimepicker({
            language: 'es',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });
        
        var cadena = document.querySelector("#datos_mensaje_error").innerHTML.replace(/&lt;p&gt;/g,"<p>").replace(/&lt;\/p&gt;/g,"</p>");

        document.querySelector("#modal1Desc").innerHTML = cadena;
	}

    function cambio_estado(ele)
    {
        if(ele.dataset.tipo == "1") //Baremos
        {
            if(ele.value == "")
            {
                mostrarModal(1,null,"Guardar ejecución","El campo no puede estar vacio.\n",0,"Aceptar","",null);
                ele.style.background = "#F5A9A9";
            }else{
                ele.style.background = "#9FF781";
                ele.dataset.validado = "1";
            }
        }
        else //Materiales
        {
            if(ele.value == "")
            {
                mostrarModal(1,null,"Guardar ejecución","El campo no puede estar vacio.\n",0,"Aceptar","",null);
                ele.style.background = "#F5A9A9";
            }else{

                if(parseInt(ele.value) > parseInt(ele.dataset.max))
                {
                    mostrarModal(1,null,"Guardar ejecución","No puede ingresar un valor mayor al programado:" + ele.dataset.max + ".\n",0,"Aceptar","",null);
                    ele.style.background = "#F5A9A9";
                    ele.value = "";
                    return;
                } 
                else{
                    ele.style.background = "#9FF781";
                    ele.dataset.validado = "1";    
                }  
            }
        }
        
    }

    function seleccionar(ele)
    {
        ele.selectionStart = 0;
        ele.selectionStart = ele.value.length;
    }
    function validaIngreso(ele)
        {
            var letra = event.which || event.keyCode;
            //alert(letra);
            if((letra > 47 && letra < 59) || ( letra > 95 && letra < 106) )
            {
                  
            }
            else
            {
                if(letra == 109 || letra == 189 || letra == 8 || letra == 9)
                {
                }else
                {
                    var even = window.event;
                    even.preventDefault();
                } 
            }



                    
        }

    function validaIngreso2(ele) // Con punto
        {
            var letra = event.which || event.keyCode;
            //alert(letra);
            if((letra > 47 && letra < 59) || ( letra > 95 && letra < 106) )
            {
                  
            }
            else
            {
                if(letra == 109 || letra == 189 || letra == 8 || letra == 9 || letra == 190)
                {
                }else
                {
                    var even = window.event;
                    even.preventDefault();
                } 
            }



                    
        }


    function validarEmail( email ) 
    {
        var expr = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
        //alert(expr.test(email));
        if (email.search(expr) != 0 ) 
            return false;
        else
            return true;
    }

    function base64MimeType(encoded) {
      var result = null;

      if (typeof encoded !== 'string') {
        return result;
      }

      var mime = encoded.match(/data:([a-zA-Z0-9]+\/[a-zA-Z0-9-.+]+).*,.*/);

      if (mime && mime.length) {
        result = mime[1];
      }

      return result;
    }

    //Convierte un archivo en base6
    function uploadFileToServer(ele,arreglo) {
            var event = window.event;
            var selectedFile = event.target.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(ele);
             reader.onload = function () {
               arreglo.push(reader.result);
             };
             reader.onerror = function (error) {
               console.log('Error: ', error);
             };
        }

    //Función encargadad de validar si el archivo a cargar es PDF y pesa menos de 5MB
    function validarArchivoPDF(ele)
        {
          var event = window.event;
          var selectedFile = event.target.files[0];
          var reader = new FileReader();

          if(selectedFile.type == "application/pdf" || selectedFile.type == "image/png" || selectedFile.type == "image/jpe")
          {
              if(selectedFile.size > 5000000)
              {
                  ele.value = "";
                  mostrarModal(1,null,"Tamaño imagen","El tamaño del archivo no puede superar 5 MB.\n",0,"Aceptar","",null);
                  return false;
              }    
          }
          else
          {
              mostrarModal(1,null,"Tipo de archivo","El tipo de archivo a cargar solo puede ser en formato PDF.\n",0,"Aceptar","",null);
              ele.value = "";
              return false;
          }

          return true;
        }

    var inst1;
    function mostrarModal(opcion,callback,title,mensaje,boton,txtBoton1,txtBoton2,callback2)
        {
            $("#title_modal").html(title);
            $("#texto_conten").html(mensaje);

            $("#boton_1_modal").removeClass("btn-succes-modal");
            $("#boton_1_modal").removeClass("btn-error-modal");
            $("#boton_1_modal").removeClass("btn-warning-modal");
            $("#boton_1_modal").removeClass("btn-info-modal");

            if(opcion == 1)
            {
                document.querySelector("#img_modal_datos").src = "../../../img/error.png";
                $("#boton_1_modal").addClass("btn-error-modal");  
            }

            if(opcion == 2)
            {
                document.querySelector("#img_modal_datos").src = "../../../img/success.png";
                $("#boton_1_modal").addClass("btn-succes-modal");  
            }

            if(opcion == 3)
            {
                document.querySelector("#img_modal_datos").src = "../../../img/warning.png";
                $("#boton_1_modal").addClass("btn-warning-modal");  
            }

            if(opcion == 4)
            {
                document.querySelector("#img_modal_datos").src = "../../../img/notice.png";
                $("#boton_1_modal").addClass("btn-info-modal");  
            }

            $("#boton_1_modal").unbind("click");
            $("#boton_2_modal").unbind("click");

            if(boton == 0)
            {
                
                /*if(callback == null)
                {
                    $("#boton_2_modal").bind("click",ocultarModalMensaje);
                }
                else
                {
                    $("#boton_2_modal").bind("click",callback);
                }

                $("#boton_2_modal").css("margin-left","30%");*/
                document.querySelector("#boton_1_modal").innerHTML = txtBoton1;
                document.querySelector("#boton_2_modal").innerHTML = txtBoton2;
                $("#boton_1_modal").css("display","inline-block");
                $("#boton_2_modal").css("display","none");
            }

            if(boton == 1)
            {
                
                if(callback == null)
                {
                    $("#boton_2_modal").bind("click",ocultarModalMensaje);
                }
                else
                {
                    $("#boton_2_modal").bind("click",callback);
                }

                $("#boton_1_modal").css("margin-left","5%");
                $("#boton_2_modal").css("margin-left","5%");
                    
                $("#boton_1_modal").bind("click",callback2);
                document.querySelector("#boton_1_modal").innerHTML = txtBoton1;
                document.querySelector("#boton_2_modal").innerHTML = txtBoton2;
                
                $("#boton_1_modal").css("display","inline-block");
                $("#boton_2_modal").css("display","inline-block");
            }
            //document.querySelector("#modal_mensaje").style.display = "block";

            inst1 = $('[data-remodal-id=modal1]').remodal();
            inst1.open();

            
        }
    
    function ocultarModalMensaje()
        {
            document.querySelector("#modal_mensaje").style.display = "none";    
        }
    
    function mostrarNotificacion(opc,mensaje)
    {
        var toastEl;
         // reconfiguring the toasts as sticky
        $().toastmessage({sticky : true});
        if(opc == 1) //Noticia
            toastEl = $().toastmessage('showNoticeToast', mensaje);
            
        if(opc == 2) //Succes
            toastEl = $().toastmessage('showSuccessToast', mensaje);

        if(opc == 3)// Warning
            toastEl = $().toastmessage('showWarningToast', mensaje);

        if(opc == 4)// Error
            toastEl = $().toastmessage('showErrorToast', mensaje);
        
        setTimeout(function()
        {
            cerrarModal(toastEl);
        },3000); 
    }

    function cerrarModal(ele)
    {
        $().toastmessage('removeToast', ele);
    }


	function valida_fecha(forma) {
        fecha = forma.fecha.value;
        if (!validaFechaDDMMAAAA(fecha)) {
            forma.fecha.value = "";
        }

    }
    function validaFechaDDMMAAAA(fecha) {
        var dtCh = "/";
        var minYear = 2015;
        var maxYear = 2019;

        function isInteger(s) {
            var i;
            for (i = 0; i < s.length; i++) {
                var c = s.charAt(i);
                if (((c < "0") || (c > "9"))) return false;
            }
            return true;
        }

        function stripCharsInBag(s, bag) {
            var i;
            var returnString = "";
            for (i = 0; i < s.length; i++) {
                var c = s.charAt(i);
                if (bag.indexOf(c) == -1) returnString += c;
            }
            return returnString;
        }

        function daysInFebruary(year) {
            return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
        }

        function DaysArray(n) {
            for (var i = 1; i <= n; i++) {
                this[i] = 31;
                if (i == 4 || i == 6 || i == 9 || i == 11) {
                    this[i] = 30
                }
                if (i == 2) {
                    this[i] = 29
                }
            }
            return this
        }

        function isDate(dtStr) {
            var daysInMonth = DaysArray(12);
            var pos1 = dtStr.indexOf(dtCh);
            var pos2 = dtStr.indexOf(dtCh, pos1 + 1);
            var strDay = dtStr.substring(0, pos1);
            var strMonth = dtStr.substring(pos1 + 1, pos2);
            var strYear = dtStr.substring(pos2 + 1);
            strYr = strYear;
            if (strDay.charAt(0) == "0" && strDay.length > 1) strDay = strDay.substring(1);
            if (strMonth.charAt(0) == "0" && strMonth.length > 1) strMonth = strMonth.substring(1);
            for (var i = 1; i <= 3; i++) {
                if (strYr.charAt(0) == "0" && strYr.length > 1) strYr = strYr.substring(1)
            }
            month = parseInt(strMonth);
            day = parseInt(strDay);
            year = parseInt(strYr);
            if (pos1 == -1 || pos2 == -1) {
                return false
            }
            if (strMonth.length < 1 || month < 1 || month > 12) {
                return false
            }
            if (strDay.length < 1 || day < 1 || day > 31 || (month == 2 && day > daysInFebruary(year)) || day > daysInMonth[month]) {
                return false
            }
            if (strYear.length != 4 || year == 0 || year < minYear || year > maxYear) {
                return false
            }
            if (dtStr.indexOf(dtCh, pos2 + 1) != -1 || isInteger(stripCharsInBag(dtStr, dtCh)) == false) {
                return false
            }
            return true
        }

        if (isDate(fecha)) {
            return true;
        } else {
            return false;
        }
    }

     function mostrarSincronizacion()
    {
        $("body").css('overflow','hidden');
        $("hmtl").css('overflow','hidden');
        document.querySelector("#sincronizando1").style.display = "block";
        document.querySelector("#sincronizando2").style.display = "block";
    }

    function ocultarSincronizacion()
    {
        $("body").css('overflow-y','visible');
        $("hmtl").css('overflow-y','visible');
        document.querySelector("#sincronizando1").style.display = "none";
        document.querySelector("#sincronizando2").style.display = "none";
    }

    function ocultarSincronizacionFondoBlanco()
	{
        $("body").css('overflow-y','visible');
        $("hmtl").css('overflow-y','visible');
        document.querySelector("#sincronizando3").style.display = "none";
        document.querySelector("#sincronizando4").style.display = "none";
	}
    

    function number_format(amount, decimals) {

        amount += ''; // por si pasan un numero en vez de un string
        amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

        decimals = decimals || 0; // por si la variable no fue fue pasada

        // si no es un numero o es igual a cero retorno el mismo cero
        if (isNaN(amount) || amount === 0) 
            return parseFloat(0).toFixed(decimals);

        // si es mayor o menor que cero retorno el valor formateado como numero
        amount = '' + amount.toFixed(decimals);

        var amount_parts = amount.split('.'),
            regexp = /(\d+)(\d{3})/;

        while (regexp.test(amount_parts[0]))
            amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

        return amount_parts.join('.');
    }




	</script>