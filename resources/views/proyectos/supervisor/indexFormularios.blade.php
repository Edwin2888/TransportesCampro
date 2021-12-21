@extends('template.index')

@section('title')
	Creación de formularios
@stop

@section('title-section')
    Creación de formularios
@stop

@section('content')
    <?php
        $servidor = "http://localhost:8000/";
        $servidor = config("app.server_transportes");

    ?>
    <style type="text/css">
    #tbl_inspecciones_filter
    {
        position: relative;
        left: 100px;
    }

    </style>
	<main>

        <!-- Import Modal -->
         @include('proyectos.supervisor.modal.modalAgregarOpcionesFormulario')


		<div class="container-fluid" style="margin-top:20px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-2" style="position:fixed;">
                            <div class="form-group has-feedback">
                                <label for="txt_matricula_vehiculo">Tipo formulario</label>
                                {!! Form::open(array('url' => 'filterFormularios')) !!}
                                    {!! Form::select('proyecto_frm', $tipoform, Session::get('frm_filter'),array('placeholder' => 'Seleccione',"class" =>"form-control","id"=>"proyecto_frm" )) !!} 
                                {!! Form::close()!!}
                            </div>  
                            <a href="#" id="save_formulario" class="btn btn-primary btn-cam-trans btn-sm" style="margin-left:31px;margin-bottom:10px;"><i class="fa fa-save"></i> &nbsp; Guardar formulario</a>
                            <a style="    position: relative;    top: 0px;    margin-left: 35%;" href="../../inspeccionOrdenes" id="btn-salir" class="btn btn-primary btn-cam-trans btn-sm" id="btn-salir">Cerrar</a>
                               
                            <a style="" href="" id="btn-verlog" class="btn btn-primary btn-cam-trans btn-sm" >Ver Log</a>
                       
                        </div>
                        <div class="col-md-10" style="position:relative;left:17%;">
                            @include('proyectos.supervisor.secciones.frmFormularioCreacion')           
                        </div>
                    </div>
                </div>
                
                
            </div>
        </div>
	</main>

<?php ////////////////////////////////////////////////////////
      //////////////// inicio modal log //////////////////////
      //////////////////////////////////////////////////////// ?>
    
<div class="modal fade" id="modal_formu_log">
  <div class="modal-dialog" role="document" style='width: 100%;  max-width: 940px;'>
    <div class="modal-content">
      <div class="modal-header modal-filter panel-warning">
          <h5 class="modal-title">Log Cambios</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">


          <div class="row" style="margin-top:5px;">
            <div class="form-group has-feedback">

                <label class="control-label " for="txtObserAbrir">Detalle </label>
                <label class="control-label " id="placa_log_m"></label>
                <br>

                  <div class="col-sm-12">
                      <table id='tabla_formular_lod' class="table table-striped table-bordered" cellspacing="0"  style=' max-width: 100% !important;width: 100%;'>
                          <thead>
                              <th> Tipo Formulario </th>
                              <th> Usuario </th>
                              <th> Fecha </th>
                              <th> Version Anterios </th>
                              <th> Version Posterior </th>
                          </thead>
                      </table>
                  </div>
            </div>
          </div>


          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>


<?php ////////////////////////////////////////////////////////
      ///////////////// final modal log //////////////////////
      //////////////////////////////////////////////////////// ?>
    
    
    
    
    <script type="text/javascript">
        window.onload=function() {
        	

        var tablaformulog=$('#tabla_formular_lod').DataTable( {
                                                            dom: 'T <"clear">lfrtip', 
                                                            tableTools: {
                                                                    "sSwfPath": "../media/swf/copy_csv_xls_pdf.swf",
                                                                    "aButtons":[]// ["copy", "xls"]
                                                            },
                                                            initComplete: function () {},
                                                           // "scrollX": true,
                                                            "sScrollXInner": "110%",
                                                            scrollY: '200px',
                                                            "processing": true,
                                                            "language": 
                                                                       {          
                                                                        //"processing": "<img style='width:300px; height:250px;' src='https://{{config('app.Campro')[2]}}/campro/recursos/img/tenor.gif' />",
                                                                       },
                                                            "serverSide": true,
                                                            "aLengthMenu": [[10, 15, 25, 35, 50, 100, 6000], [10, 15, 25, 35, 50, 100, 6000]],
                                                            "ajax": {
                                                                  "url": "<?= Request::root() ?>/formularios/log",
                                                                  "type": "POST",
                                                                  "async": "true",
                                                                  "data": function (d) {
                                                                      d.tipo=$("#proyecto_frm").val();
                                                                      d._token='<?= csrf_token() ?>';
                                                                  },
                                                                  "complete": function (response) {
                                                                      //$("#genSpiner").removeClass("fa fa-refresh fa-spin");
                                                                      //console.log("Data Recibida:", response)
                                                                  }
                                                            },
                                                            "columns": [
                                                                  {"name": "datou",'orderable':true,"searchable":true},
                                                                  {"name": "datod",'orderable':true,"searchable":false},
                                                                  {"name": "datot",'orderable':true,"searchable":true},
                                                                  {"name": "datoc",'orderable':true,"searchable":false},
                                                                  {"name": "datoci",'orderable':true,"searchable":true}
                                                            ]
                                                      }); 
            
            
	    $("#btn-verlog").on('click',function(event){
                event.preventDefault();
                event.stopPropagation();
                
                if($("#proyecto_frm").val().trim() == "" ){
                    mensajes("Error","Seleccione tipo de formulario.\n",0); 
                    return;
                }
            
                tablaformulog.ajax.reload();
                $("#modal_formu_log").modal("show");
                $('#tabla_formular_lod').DataTable().columns.adjust().draw();
               setTimeout(function(){tablaformulog.columns.adjust().draw();},500);
            });
	}

        


        window.addEventListener('load',ini);

        function ini()
        {
            document.querySelector("#save_formulario").addEventListener("click",saveFormulario);

            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none"; 

            document.querySelector("#proyecto_frm").addEventListener("change",function()
            {
                if(this.selectedIndex == 0)
                    return;

                mostrarSincronizacion();
                this.parentElement.submit();

            });
            
            generaIdPregunta();
        }

        //Add hijo
        function addHijo(ele){
            
            if(
                ele.parentElement.parentElement.children[4].children[0].value == "")
            {
                mostrarModal(1,null,"Creación de formulario","No puede agregar más filas, por que no ha terminado de llenar la fila actual.\n",0,"Aceptar","",null);                                 
                return;
            }


            mostrarNotificacion(1,"Se ha agrega una nueva fila");
            
            var html = "<tr>";
            html += '<td><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>';
            html += '<td><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>';
            html += '<td><input type="text" value="" style="width:40px;" class="form-control" placeholder="Item"/></td>';
            html += '<td><input type="number" value="" style="width:50px;" class="form-control" placeholder="Pregunta" disabled/></td>';
            html += '<td><input type="text" value="" class="form-control" placeholder="Descripción"/></td>';
            html += '<td>{!! Form::select("obliga", ["0" => "No", "1" => "Si"], 0,array("style" => "width:50px;","class" => "form-control")) !!} </td>';
            html += '<td>{!! Form::select("tipo_control", $tipoControl, 0,array("style" => "width:220px;","class" => "form-control","onchange" => "buscarControl(this)")) !!}</td>';
            html += '        <td><button onclick="abiriModalOpciones(this)"  style="display:none" class="btn btn-primary  btn-cam-trans btn-sm" type="submit" name="consultar" value="consultar">';
            html += '                <i class="fa fa-plus"></i>';
            html += '            </button></td>';

            html += '<td>';
            html += '            <select data-padre="" class="form-control">';
            html += '            </select>';
            html += '        </td>';
            html += '        <td>';
            html += '            <input type="text" value="" placeholder="Desp. Corto" class="form-control"/>';
            html += '        </td>';
            html += '<td><img src="../../../img/tipos/0.PNG" style="width:100%;"></td>';
            html += '</tr>';
            $(ele.parentElement.parentElement).after(html); 

            generaIdPregunta();           
            //$(ele.parentElement.parentElement.parentElement).find(ele.parentElement.parentElement).remove();
        }

        function buscarControl(ele)
        {
            if(ele.value == 15) //Select
                ele.parentElement.parentElement.children[7].children[0].style.display = "block";
            else
                    ele.parentElement.parentElement.children[7].children[0].style.display = "none";
            
            ele.parentElement.parentElement.children[10].children[0].src = "{{$servidor}}img/tipos/" + ele.value + ".PNG";
        }

        //Eliminar hijo
        function deleteHijo(ele)
        {
            mostrarNotificacion(3,"Se ha eliminado la pregunta #" + ele.parentElement.parentElement.children[3].children[0].value + "\n<b>" + ele.parentElement.parentElement.children[4].children[0].value + "</b>");
            $(ele.parentElement.parentElement.parentElement).find(ele.parentElement.parentElement).remove();
            generaIdPregunta();           
        }

        function generaIdPregunta()
        {
            var hijos = document.querySelector("#tbl_datos_frm").children;

            for (var i = 0; i < hijos.length; i++) {
                hijos[i].children[3].children[0].value = i + 1;
                var opc = "<option>-</option>";
                for (var j = 1; j <= (i + 1); j++) {
                    if((i + 1) != j)
                        opc += "<option>" + j + "</option>";
                };

                hijos[i].children[8].children[0].innerHTML = opc;
                if(hijos[i].children[8].children[0].dataset.padre != "" && hijos[i].children[8].children[0].dataset.padre != null && hijos[i].children[8].children[0].dataset.padre != undefined)
                   hijos[i].children[8].children[0].value =  hijos[i].children[8].children[0].dataset.padre;
            };


        }


        function saveFormulario()
        {
            var hijos = document.querySelector("#tbl_datos_frm").children;            
            if(hijos.length == 0 || document.querySelector("#proyecto_frm").value == "")
                return;

            @if(count($formSelect) != 0)
                @if(Session::get('frm_filter') == 1)
                    if(document.querySelector("#correo1").value == "" ||
                       document.querySelector("#correo2").value == "" ||
                       document.querySelector("#correo3").value == "")
                    {
                        mostrarModal(1,null,"Creación de Formulario","Por favor ingrese los correos electrónicos.",0,"Aceptar","",null);    
                        return;
                    }
                @endif
            @endif

            var exit = 0;
            for (var i = 0; i < hijos.length; i++) {
                if(hijos[i].children[3].children[0].value == "" ||
                    hijos[i].children[4].children[0].value == "")
                {
                    mostrarModal(1,null,"Creación de Formulario","Existen campos vacios en: <br> Item: <b>" + hijos[i].children[2].children[0].value + "</b> <br>Pregunta: <b>" +  hijos[i].children[3].children[0].value  + "</b> <br> Descripción: <b>" + hijos[i].children[4].children[0].value + "</b>",0,"Aceptar","",null);    
                    exit = 1;
                    break;
                }
            }

            if(exit == 1)
                return;


            var arregloP = [];
            exit = 0;
            for (var i = 0; i < hijos.length; i++) {
                var existe = 0;
                for (var j = 0; j < arregloP.length; j++) {
                    if(hijos[i].children[3].children[0].value == arregloP[j])  
                    {
                        existe =1;  
                        break;
                    }
                };
                
                if(existe == 1)
                {
                    mostrarModal(1,null,"Creación de Formulario","Ya existe el número de pregunta # " + hijos[i].children[3].children[0].value + " - " +  hijos[i].children[4].children[0].value  + " en otra parte del formulario.",0,"Aceptar","",null); 
                    exit = 1;
                    break;
                }else
                    arregloP.push(hijos[i].children[3].children[0].value);
            };

            if(exit == 1)
                return;
            var arregloEnvio = [];
            for (var i = 0; i < hijos.length; i++) {
                arregloEnvio.push(
                {
                    "item" : hijos[i].children[2].children[0].value,
                    "preg" : hijos[i].children[3].children[0].value,
                    "desc" : hijos[i].children[4].children[0].value,
                    "obli" : hijos[i].children[5].children[0].value,
                    "tipo" : hijos[i].children[6].children[0].value,
                    "padre" : (hijos[i].children[8].children[0].value == "-" ? "" : hijos[i].children[8].children[0].value),
                    "corto" : hijos[i].children[9].children[0].value
                });
            }

            var datos = {
                form: arregloEnvio,
                tipo_f: document.querySelector("#proyecto_frm").value,
                tip: 9
                @if(count($formSelect) != 0)
                    @if(Session::get('frm_filter') == 1)
                        ,correo1 : document.querySelector("#correo1").value.trim()
                        ,correo2 : document.querySelector("#correo2").value.trim()
                        ,correo3 : document.querySelector("#correo3").value.trim()
                    @endif
                @endif
            }
            consultaAjax("{{url('/')}}/saveSupervision", datos, 150000, "POST", 1);  
        }

        var selectOpcion;
        function abiriModalOpciones(ele)
        {
            document.querySelector("#text1").value = "";
            selectOpcion = ele;

            document.querySelector("#tbl_add_opcion").innerHTML = "";
            if(ele.dataset.datos != "" && ele.dataset.datos != null && ele.dataset.datos != undefined)
            {
                var da = ele.dataset.datos.split("*");
                var html = "";
                for (var i = 0; i < da.length - 1; i++) {
                    var dat = da[i].split("_");
                    $("#tbl_add_opcion").append("<tr> <td>" + dat[0] + "</td><td>" + dat[1] + "</td><td><i class='fa fa-times' onclick='deleteOpcion(this)' style='    font-size: 20px;  color: red;  display: block;  text-align: center;    cursor: pointer;'></i></td></tr>");
                };
            }

            $("#modal_add_opcion").modal("toggle");
        }

        function addOpcion()
        {
            if(document.querySelector("#text1").value == "")
            {
                mostrarModal(1,null,"Opciones","Ingrese la descripción de la acción\n",0,"Aceptar","",null);
                return;
            }
           
            $("#tbl_add_opcion").append("<tr> <td></td><td>" + document.querySelector("#text1").value + "</td><td><i class='fa fa-times' onclick='deleteOpcion(this)' style='    font-size: 20px;  color: red;  display: block;  text-align: center;    cursor: pointer;'></i></td></tr>");
            agregarConsecutivoOpcion();
             document.querySelector("#text1").value = "";
        }

        function agregarConsecutivoOpcion()
        {
            var dat = document.querySelector("#tbl_add_opcion").children;
            for (var i = 0; i < dat.length; i++) {
                dat[i].children[0].innerHTML = (i + 1);
            };

        }

        function deleteOpcion(ele)
        {
            if(document.querySelector("#tbl_add_opcion").children.length == 1)
            {
                mostrarModal(1,null,"Creación de Formulario","De exister por lo menos una opción.",0,"Aceptar","",null); 
                return;
            }
            $("#tbl_add_opcion").find(ele.parentElement.parentElement).remove();
            agregarConsecutivoOpcion();
        }

        function saveOpcion()
        {
            if(document.querySelector("#tbl_add_opcion").children.length == 0)
            {
                mostrarModal(1,null,"Creación de Formulario","Debe agregar por lo menos una opción.",0,"Aceptar","",null); 
                return;
            }   

            var dat = document.querySelector("#tbl_add_opcion").children;
            var html = "";
            for (var i = 0; i < dat.length; i++) {
                html = html + dat[i].children[0].innerHTML + "_" + dat[i].children[1].innerHTML + "*";
            };
            selectOpcion.dataset.datos = html;
            $("#modal_add_opcion").modal("toggle");

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

                    if(opcion == 1)
                    {
                        if(data == "0")
                            mostrarModal(1,null,"Creación de formulario","Se presentaron problemas con la creación del formulario, vuelva a intentar más tarde.\n",0,"Aceptar","",null); 

                        if(data == "1")
                            mostrarModal(2,null,"Creación de formulario","Se ha actualizado correctamente el formulario.\n",0,"Aceptar","",null);                                 
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

    </script>
@stop

