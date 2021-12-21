
    
    var color_bien= '#ccc';//#33bb7d
    var color_mal= '#B94A48';//red

    function valida_texto(elemento){
      var dato = $(elemento).val().trim();
      var valida = $(elemento).data('val');
       if(dato != ''  &&  dato.length > 0){
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).next(".mensaje-validacion").html("");//.hide()
                  $("."+valida).html("");
                  return 1;
       }else{
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
       }   

    }
    function valida_fecha_boo(elemento){
      var dato = $(elemento).val().trim();
      var valida = $(elemento).data('val');
       if(dato != ''  &&  dato.length > 0){
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).parent().next(".mensaje-validacion").html("");//.hide()
                  $("."+valida).html("");
                  return 1;
       }else{
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).parent().next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
       }   

    }
    
    function valida_enteros(elemento){
       var patron = /^\d*$/;   	
       var dato = $(elemento).val().trim();
       var valida = $(elemento).data('val');
       if(dato == ''  ||  dato.length <= 0){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal});    
                  $(elemento).next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
       }  
       else if(!patron.test(dato)){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Formato Incorrecto").show();
                  $("."+valida).html("Formato Incorrecto");
                  return 0;
       }
       else{
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).next(".mensaje-validacion").html("");//.hide();
                  $("."+valida).html("");
                  return 1;
       }
    }
    function valida_decimales(elemento){
       var patron = /^[0-9]+([,][0-9]+)?$/;   	
       var dato = $(elemento).val().trim();
       var valida = $(elemento).data('val');
       if(dato == ''  ||  dato.length <= 0){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal});    
                  $(elemento).next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
       }  
       else if(!patron.test(dato)){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Formato Incorrecto").show();
                  $("."+valida).html("Formato Incorrecto");
                  return 0;
       }
       else{
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).next(".mensaje-validacion").html("");//.hide();
                  $("."+valida).html("");
                  return 1;
       }
    }
    function valida_horas(elemento){
       var patron = /^[0-2][0-9]:[0-6][0-9]$/;   	
       var dato = $(elemento).val().trim();
       var valida = $(elemento).data('val');
       if(dato == ''  ||  dato.length <= 0){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal});    
                  $(elemento).next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
       }  
       else if(!patron.test(dato)){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Formato Incorrecto ejemplo 04:02").show();
                  $("."+valida).html("Formato Incorrecto ejemplo 04:02");
                  return 0;
       }
       else{
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).next(".mensaje-validacion").html("");//.hide();
                  $("."+valida).html("");
                  return 1;
       }
    }
    
    
    function valida_email(elemento){
       var patron = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;   	
       var dato = $(elemento).val().trim();
       var valida = $(elemento).data('val');
       if(dato == ''  ||  dato.length <= 0){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
       }  
       else if(!patron .test(dato)){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Formato Incorrecto").show();
                  $("."+valida).html("Formato Incorrecto");
                  return 0;
       }
       else{
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).next(".mensaje-validacion").html("");//.hide();
                  $("."+valida).html("");
                  return 1;
       }
    }
    function valida_identificacion(elemento){
       var patron = /^([0-9a-zA-Z-]+)?([0-9]+)?$/;   	
       var dato = $(elemento).val().trim();
       var valida = $(elemento).data('val');
       if(dato == ''  ||  dato.length <= 0){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
       }  
       else if(!patron .test(dato)){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Formato Incorrecto").show();
                  $("."+valida).html("Formato Incorrecto");
                  return 0;
       }
       else{
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).next(".mensaje-validacion").html("");//.hide();
                  $("."+valida).html("");
                  return 1;
       }
    }

    function valida_prefijo(elemento){
         var dato = $(elemento).val().trim();
         var valida = $(elemento).data('val');
         if(dato.length > 0 && dato.length < 2){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("M�nimo dos caracteres").show();
                  $("."+valida).html("M�nimo dos caracteres");
                  return 0;
         }
         else{ 
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).next(".mensaje-validacion").html("");//.hide();
                  $("."+valida).html("");
                  return 1; 
         }
    }
    function valida_select(elemento){
         var dato = $(elemento).val();
         var valida = $(elemento).data('val');
         if(dato == 0 ){
                  $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
         }
         else{ 
                  $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).next(".mensaje-validacion").html("");//.hide();
                  $("."+valida).html("");
                  return 1; 
         }
    }
    
    function valida_radios(elemento){
        var name = $(elemento).attr("name");
        var valida = $(elemento).data('val');
        if ($('input[name="'+name+'"]').is(':checked')) {
            $(elemento).css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
            $(elemento).next(".mensaje-validacion").html("");//.hide();
            $("."+valida).html("");
            return 1; 
        } else {
            $(elemento).css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
            $(elemento).next(".mensaje-validacion").html("Requerido").show();
            $("."+valida).html("Requerido");
            return 0;
        }
        
    }
    
    function valida_input_img(elemento){
         var val = $(elemento).val().trim();
         var valida = $(elemento).data('val');
         if(val.length <= 0 ){
                  $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).parent().parent().parent().next(".mensaje-validacion").html("Requerido").show();   
                  $("."+valida).html("Requerido");
                  return 0;
         }
        
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                case 'gif': case 'jpg': case 'png':          
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
                        $("."+valida).html("");
                        return 1;        
                    break;
                default:
                        $(elemento).val("");
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("no es una imagen").show();  
                        $("."+valida).html("no es una imagen");                
                        return 0;
                    break;
            }
        
        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
        $("."+valida).html("");
        return 1;    
    }
    function valida_input_img2(elemento){
         var val = $(elemento).val().trim();
         var valida = $(elemento).data('val');
         if(val.length <= 0 ){
                  $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                  $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
                  $("."+valida).html("");
                  return 1; 
         }
        
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                case 'gif': case 'jpg': case 'png':          
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
                        $("."+valida).html("");
                        return 1;        
                    break;
                default:
                        $(elemento).val("");
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("no es una imagen").show(); 
                        $("."+valida).html("no es una imagen");                 
                        return 0;
                    break;
            }
        
        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
        $("."+valida).html("");
        return 1;    
    }
    
    
    function valida_input_arch(elemento){
         var val = $(elemento).val().trim();
         var valida = $(elemento).data('val');
         if(val.length <= 0 ){
                  $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).parent().parent().parent().next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
         }
        
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                case 'csv': case 'txt':    
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
                        $("."+valida).html("");
                        return 1;        
                    break;
                default:
                        $(elemento).val("");
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("no es un archivo valido").show(); 
                        $("."+valida).html("no es un archivo valido");                 
                        return 0;
                    break;
            }
        
        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
        $("."+valida).html("");
        return 1;    
    }
    
    function valida_input_com(elemento){
         var val = $(elemento).val().trim();
         var valida = $(elemento).data('val');
         if(val.length <= 0 ){
                  $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).parent().parent().parent().next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
         }
        
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                case 'zip': case 'rar': case '7z':          
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
                        $("."+valida).html("");
                        return 1;        
                    break;
                default:
                        $(elemento).val("");
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("no es una archivo valido").show(); 
                        $("."+valida).html("no es una archivo valido");
                        return 0;
                    break;
            }
        
        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();        
        $("."+valida).html("");
        return 1;    
    }
    function valida_input_video(elemento){
         var val = $(elemento).val().trim();
         var valida = $(elemento).data('val');
         if(val.length <= 0 ){
                  $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).parent().parent().parent().next(".mensaje-validacion").html("Requerido").show();  
                  $("."+valida).html("Requerido");    
                  return 0;
         }
        
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                case 'mp4': case 'flv':          
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();  
                        $("."+valida).html("");
                        return 1;        
                    break;
                default:
                        $(elemento).val("");
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("no es una archivo valido").show(); 
                        $("."+valida).html("no es una archivo valido");
                        return 0;
                    break;
            }
        
        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
        $("."+valida).html("");
        return 1;    
    }
    
    function valida_input_video2(elemento){
         var val = $(elemento).val().trim();
         var valida = $(elemento).data('val');
         if(val.length <= 0 ){
                    $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                    $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
                    $("."+valida).html("");
                    return 1; 
         }
        
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                case 'mp4': case 'flv':          
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
                        $("."+valida).html("");
                        return 1;        
                    break;
                default:
                        $(elemento).val("");
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("no es una archivo valido").show(); 
                        $("."+valida).html("no es una archivo valido");                 
                        return 0;
                    break;
            }
        
        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
        $("."+valida).html("");
        return 1;    
    }
    
    function valida_input_audio(elemento){
         var val = $(elemento).val().trim();
         var valida = $(elemento).data('val');
         if(val.length <= 0 ){
                  $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                  $(elemento).parent().parent().parent().next(".mensaje-validacion").html("Requerido").show();
                  $("."+valida).html("Requerido");
                  return 0;
         }
        
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
                //case 'gif': case 'jpg': case 'png':          
                case 'mp3':       
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
                        $("."+valida).html("");
                        return 1;        
                    break;
                default:
                        $(elemento).val("");
                        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_mal, 'border-top-color' : color_mal });    
                        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("no es un archivo mp4").show(); 
                        $("."+valida).html("no es un archivo mp4");                 
                        return 0;
                    break;
            }
        
        $(elemento).parent().prev(".uneditable-input").css({ 'border-color' : color_bien, 'border-top-color' : color_bien });    
        $(elemento).parent().parent().parent().next(".mensaje-validacion").html("");//.hide();
        $("."+valida).html(""); 
        return 1;    
    }
    
    function mensajes(titulo,texto,tipom){
        var tipo="success";
             if(tipom==0){ tipo="error";   }  
        else if(tipom==1){ tipo="success"; }     
        else if(tipom==2){ tipo="info"; } 
        
        new PNotify({title: titulo,text: texto,type: tipo});
    }

    $(".valida_input_img" ).change(function(){ valida_input_img(this);  });
    $(".valida_input_img2" ).change(function(){ valida_input_img2(this);  });    
    $(".valida_input_audio" ).change(function(){ valida_input_audio(this);  });
    $(".valida_input_arch" ).change(function(){ valida_input_arch(this);  });    
    $(".valida_input_com" ).change(function(){ valida_input_com(this);  });
    $(".valida_input_video" ).change(function(){ valida_input_video(this);  });  
    $(".valida_input_video2" ).change(function(){ valida_input_video2(this);  });

    $(".valida_prefijo" ).focusout(function(){ valida_prefijo(this);  });
    $(".valida_fecha_boo" ).focusout(function(){ valida_fecha_boo(this);  });
    $(".valida_select" ).focusout(function(){ valida_select(this);  });    
    $(".valida_texto" ).focusout(function(){ valida_texto(this);  });
    $(".valida_enteros").focusout(function(){ valida_enteros(this); });
    $(".valida_decimales").focusout(function(){ valida_decimales(this); });
    $(".valida_email").focusout(function(){ valida_email(this); });
    $(".valida_identificacion").focusout(function(){ valida_identificacion(this); });
    
    $(".valida_horas").focusout(function(){ valida_horas(this); });
    
    
    $(".solo_numeros").keypress(function (key){
                if ((key.charCode < 48 || key.charCode > 57) )
                    return false;            
    });
    $(".valida_prefijo").keypress(function (key){
                if ((key.charCode < 48 || key.charCode > 57) )
                    return false;     

                if( $(this).val().trim().length >=5 )  
                    return false;             
    });
    $(".ntelefono").keypress(function (key){
                if ((key.charCode < 48 || key.charCode > 57) )
                    return false;     

                if( $(this).val().trim().length >=10 )  
                    return false;             
    });
     
     