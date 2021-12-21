<!DOCTYPE html>
<html lang="es">
<head>
	
	<meta  http-equiv="Refresh" content="3600">
	<title>@yield('title')</title>
	

	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{url('/')}}/img/favicon.ico">

	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/navbar-fixed-top.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/side_nav.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/dataTables.tableTools.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/remodal.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/remodal-default-theme.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/style.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/jquery.toastmessage.css">

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<script  src="{{url('/')}}/js/jquery.min.js"></script>

	<!--<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>-->



	<script type="text/javascript" src="{{url('/')}}/js/remodal.min.js"></script>

	
	@yield('css')
	
	<!--<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">-->
	<link rel="stylesheet" type="text/css" href="{{url('/')}}/css/wizard.css">

	<link rel="stylesheet" href="{{url('/')}}/css/jquery.sidr.bare.css">


	<style type="text/css">
        body {
            /*background-image: url({{url('/')}}/img/bg.jpg);*/
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100% 100%;
            background-size: cover;
        }
        .remodal-bg.with-red-theme.remodal-is-opening,
	    .remodal-bg.with-red-theme.remodal-is-opened {
	      filter: none;
	    }

	    .remodal-overlay.with-red-theme {
	      background-color: #f44336;
	    }

	    .remodal.with-red-theme {
	      background: #fff;
	    }



	    /*! ========================================================================
		 * Bootstrap Toggle: bootstrap-toggle.css v2.2.0
		 * http://www.bootstraptoggle.com
		 * ========================================================================
		 * Copyright 2014 Min Hur, The New York Times Company
		 * Licensed under MIT
		 * ======================================================================== */
		.checkbox label .toggle,.checkbox-inline .toggle{margin-left:-20px;margin-right:5px}
		.toggle{position:relative;overflow:hidden}
		.toggle input[type=checkbox]{display:none}
		.toggle-group{position:absolute;width:200%;top:0;bottom:0;left:0;transition:left .35s;-webkit-transition:left .35s;-moz-user-select:none;-webkit-user-select:none}
		.toggle.off .toggle-group{left:-100%}
		.toggle-on{position:absolute;top:0;bottom:0;left:0;right:50%;margin:0;border:0;border-radius:0}
		.toggle-off{position:absolute;top:0;bottom:0;left:50%;right:0;margin:0;border:0;border-radius:0}
		.toggle-handle{position:relative;margin:0 auto;padding-top:0;padding-bottom:0;height:100%;width:0;border-width:0 1px}
		.toggle.btn{min-width:59px;min-height:34px}
		.toggle-on.btn{padding-right:24px}
		.toggle-off.btn{padding-left:24px}
		.toggle.btn-lg{min-width:79px;min-height:45px}
		.toggle-on.btn-lg{padding-right:31px}
		.toggle-off.btn-lg{padding-left:31px}
		.toggle-handle.btn-lg{width:40px}
		.toggle.btn-sm{min-width:50px;min-height:30px}
		.toggle-on.btn-sm{padding-right:20px}
		.toggle-off.btn-sm{padding-left:20px}
		.toggle.btn-xs{min-width:35px;min-height:22px}
		.toggle-on.btn-xs{padding-right:12px}
		.toggle-off.btn-xs{padding-left:12px}

    </style>
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    @yield('css2')
</head>
<body>

	@include('template.secciones.nav')
	@include('template.secciones.aside')

	@yield('content')
		
	<div id="sincronizando1" class="sincronizando1">
	</div>
	<div id="sincronizando2" class="sincronizando2">
    	<div style="width:100%;">	
    	</div>
		<div style="    width: 100%;    margin-top: 39px;    text-align: center;    color: #1D7B77;">
			<div class="sk-three-bounce">
		        <div class="sk-child sk-bounce1" style="background:#F3AC00"></div>
		        <div class="sk-child sk-bounce2" style="background:#F3AC00"></div>
		        <div class="sk-child sk-bounce3" style="background:#F3AC00"></div>
		      </div>
			<p style="    margin-top: 31px;    font-size: 19px; color:#0060AC">Espere un momento</p>
		</div>
	</div>
	<div id="sincronizando3" class="sincronizando1" style="display:block ; background-color:white;opacity:1;">
	</div>
	<div id="sincronizando4" class="sincronizando2" style="display:block; ">
    	<div style="width:100%;">	
    	</div>
		<div style="    width: 100%;    margin-top: 39px;    text-align: center;    color: #1D7B77;">
			<div class="sk-three-bounce">
		        <div class="sk-child sk-bounce1" style="background:#F3AC00"></div>
		        <div class="sk-child sk-bounce2" style="background:#F3AC00"></div>
		        <div class="sk-child sk-bounce3" style="background:#F3AC00"></div>
		      </div>
			<p style="    margin-top: 31px;    font-size: 19px; color:#0060AC">Cargando información, espere un momento</p>
		</div>
	</div>

	<div class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
	  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	  <div>
	  	<img src="{{url('/')}}/img/error.png">
	    <h2 id="modal1Title">Carga de archivos</h2>
	    <p id="modalDesc" style="color:red;">
	      	@if(Session::has('dataExcel2'))
				<span style="display:none;color:red;" id="datos_mensaje_error">{{Session::get('dataExcel2')}}</span>
				
			@else
				{{Session::get('dataExcel1')}}
			@endif
			</p>
			@if(Session::has('Archivo'))
				@if(Session::has('OptionArchivo'))
					<p style="color:red;    text-align: center;">Se encontraron errores al cargar el archivo de las GOM, revisar en el siguiente enlace</p>
					<a href="{{url('/')}}/descargaLogs/{{Session::get('Archivo')}}"  target="_blank" style="color:blue; width:100%;   text-align: center;display:inline-block;">Log carga Goms</a>
				@else
					<p style="color:red;    text-align: center;">Se encontraron errores al cargar el archivo de los materiales, revisar en el siguiente enlace</p>
					<a href="{{url('/')}}/descargaLogs/{{Session::get('Archivo')}}"  target="_blank" style="color:blue; width:100%;   text-align: center;display:inline-block;">Log Materiales</a>
				@endif
			@endif
	  </div>
	  <br>
<!--	  <button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>-->
	  <button data-remodal-action="confirm" class="remodal-confirm btn-error-modal">Aceptar</button>
	</div>

	<div class="remodal" data-remodal-id="modal1" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
	  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	  <div>
	  	<img src="{{url('/')}}/img/success.png" id="img_modal_datos">
	    <h2 id="title_modal"></h2>
	    <p id="texto_conten">
	      	
	  </div>
	  <br>
	  <button data-remodal-action="cancel" class="remodal-cancel" id="boton_2_modal">Cancel</button>
	  <button data-remodal-action="confirm" class="remodal-confirm" id="boton_1_modal">Aceptar</button>
	</div>




	<!--<div id="modal_mensaje" style="display:none;    overflow: auto;">
		<div class="opacity_modal"></div>
		<div class="content" >
			<header class="succes" id="header_modal">
				<p id="title_modal"></p>
			</header>
				<div class="succes-main" id="main_modal">
					<img src="{{url('/')}}/img/logo.png" id="img_modal">
					<p id="texto_conten"></p>
				</div>
			<footer class="succes-footer" id="footer_modal">
				<button  id="" class="succes-boton">Si</button>
				<button id="">No</button>
			</footer>
		</div>
	</div> -->

	<div id="modal_mensaje_1" style="display:none;    overflow: auto;">
		<div class="opacity_modal"></div>
		<div class="content" >
			<header class="succes" id="header_modal_1">
				<p id="title_modal_1">Baremos</p>
			</header>
				<div class="succes-main" id="main_modal_1">
					<img src="{{url('/')}}/img/logo.png" id="img_modal">
					<p id="texto_conten_1">Ya tiene personas seleccionadas en este nodo, desea asignar todo el nodo solo al lider seleccionado, o solo a los actividades que no tiene lider asignado </p>
				</div>
			<footer class="succes-footer" id="footer_modal">
				<button  id="boton_1_modal_1" class="succes-boton">Todo a uno</button>
				<button id="boton_2_modal_1" class="succes-boton" style="display:inline-block">Solo faltantes</button>
				<button id="boton_2_modal_2" class="succes-boton" style="display:inline-block">Cancelar</button>
			</footer>
		</div>
	</div>

@if(Session::has('dataExcel1'))
	<div class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
	  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
	  <div>
	    <h2 id="modal1Title">Carga de archivos</h2>
	    <p id="modal1Desc" style="color:red;">
	      	@if(Session::has('dataExcel2'))
				<span style="display:none;color:red;" id="datos_mensaje_error">{{Session::get('dataExcel2')}}</span>
				
			@else
				{{Session::get('dataExcel1')}}
			@endif
			</p>
			@if(Session::has('Archivo'))
				@if(Session::has('OptionArchivo'))
					<p style="color:red;    text-align: center;">Se encontraron errores al cargar el archivo de las GOM, revisar en el siguiente enlace</p>
					<a href="{{url('/')}}/descargaLogs/{{Session::get('Archivo')}}"  target="_blank" style="color:blue; width:100%;   text-align: center;display:inline-block;">Log carga Goms</a>
				@else
					<p style="color:red;    text-align: center;">Se encontraron errores al cargar el archivo de los materiales, revisar en el siguiente enlace</p>
					<a href="{{url('/')}}/descargaLogs/{{Session::get('Archivo')}}"  target="_blank" style="color:blue; width:100%;   text-align: center;display:inline-block;">Log Materiales</a>
				@endif
			@endif
	  </div>
	  <br>
<!--	  <button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>-->
	  <button data-remodal-action="confirm" class="remodal-confirm">Aceptar</button>
	</div>

	<script type="text/javascript">
		var inst = $('[data-remodal-id=modal]').remodal();
		inst.open();
	</script>
@endif


	<!--@if(Session::has('dataExcel1'))
	<div class="modal-excel" id="modal_archivo_excel_1" style="position: fixed;width: 100%;height: 100%;top: 0px;z-index: 10000;display:block"/>
		<div style="width: 100%;height: 100%;position: fixed;background-color: black;z-index: 10000;opacity: 0.5;"></div>
		<div style="width: 30%;height: 40%;position: fixed;background: white;z-index: 10001;border-radius: 8px;margin-left: 35%;margin-top: 10%;">
			<p style="text-align: center;margin-top: 20%;margin-bottom: 13%;">
			@if(Session::has('dataExcel2'))
				<b style="color:red;">{{Session::get('dataExcel2')}}</b>
			@else
				{{Session::get('dataExcel1')}}
			@endif
			</p>
			@if(Session::has('Archivo'))
				@if(Session::has('OptionArchivo'))
					<p style="color:red;    text-align: center;">Se encontraron errores al cargar el archivo de las GOM, revisar en el siguiente enlace</p>
					<a href="{{url('/')}}/descargaLogs/{{Session::get('Archivo')}}"  target="_blank" style="color:blue; width:100%;   text-align: center;display:inline-block;">Log carga Goms</a>
				@else
					<p style="color:red;    text-align: center;">Se encontraron errores al cargar el archivo de los materiales, revisar en el siguiente enlace</p>
					<a href="{{url('/')}}/descargaLogs/{{Session::get('Archivo')}}"  target="_blank" style="color:blue; width:100%;   text-align: center;display:inline-block;">Log Materiales</a>
				@endif
			@endif
			<div class="row" style="    margin-top: 0px;">
              <div class="form-group has-feedback" style="text-align:center;">
                  <button type="button" class="btn btn-primary" style="background-color:#0060ac;color:white;" onclick="document.querySelector('#modal_archivo_excel_1').style.display = 'none'">Aceptar</button>
                </div>
            </div>
		</div>
	</div>
	@endif-->

	<input type="hidden" value="{{csrf_token()}}" id="token_datos">


	
	<script type="text/javascript" src="{{url('/')}}/js/bootstrap.min.js"></script>
	<!--<script type="text/javascript" src="{{url('/')}}/js/bootstrap-datepicker.js"></script>-->
	<script type="text/javascript" src="{{url('/')}}/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/dataTables.tableTools.min.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/Moment.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/validator.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/bootstrap-select.min.js"></script>
	<!--<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>-->
	<script src="{{url('/')}}/js/bootstrap-toggle.min.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/bootstrap-treeview.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/jquery.toastmessage.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/bootstrap-filestyle.min.js"></script>
	
	
	<script type="text/javascript" src="{{url('/')}}/js/highcharts.js"></script>
	<script type="text/javascript" src="{{url('/')}}/js/modules/exporting.js"></script>

	   
        <?php /* 
         ////////////////////////////////////////////////////////////////////////////
         ////////////////////////////////////////////////////////////////////////////
         ////////////////////////////////////////////////////////////////////////////
         */ ?>
	<script type="text/javascript" src="{{url('/')}}/js/comunProveedor.js"></script>
        <link rel="stylesheet"        href="{{url('/')}}/js/pnotify/pnotify.custom.css" />
	<script type="text/javascript" src="{{url('/')}}/js/pnotify/pnotify.custom.js"></script>
	
        <?php /* 
         ////////////////////////////////////////////////////////////////////////////
         ////////////////////////////////////////////////////////////////////////////
         ////////////////////////////////////////////////////////////////////////////
         */ ?>


	@yield('js')
	@include('template.secciones.js')
</body>
</html>
