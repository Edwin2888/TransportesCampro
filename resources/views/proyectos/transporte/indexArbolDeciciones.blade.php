@extends('template.index')

@section('title')
	Árbol de decisiones v1
@stop

@section('title-section')
    Árbol de decisiones v1
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
        @include('proyectos.transporte.modal.modalCargaMasivo')      

        <!-- Import Modal -->
        <div style="margin: 23px;">
          <b><h4 style="display: inline-block;    margin-right: 13px;">Estado del árbol de decisiones actual: </h4></b>
          <input id="version" type="checkbox" class="form-control" onchange="cambioVersion(this);">  
        </div>

        <a href="{{ url('/') }}/arbolDecisionesDemo" style="    margin-left: 21px;    margin-bottom: 12px;    display: block;    border: 1px solid;    width: 188px;
    padding: 8px;    border-radius: 7px;">Ver álbol de decisiones v2</a>


		<div class="container-fluid" style="margin-top:20px;">
            <div class="row">
                <div class="col-md-12">
                    <a href="#" id="save_formulario" class="btn btn-primary btn-cam-trans btn-sm" style="margin-left:0px;margin-bottom:10px;"><i class="fa fa-save"></i> &nbsp; Guardar arból de decisiones</a>

                    <a href="#" onclick="abrirExcelModal()" class="btn btn-primary btn-cam-trans btn-sm" style="margin-left:0px;margin-bottom:10px;"><i class="fa fa-upload"></i> &nbsp; Carga masivo</a>

                    @include('proyectos.transporte.secciones.frmArbolDecisiones')           
                </div>
            </div>
        </div>
	</main>


    <script type="text/javascript">

        window.addEventListener('load',ini);

        function ini()
        {
            document.querySelector("#sincronizando3").style.display = "none";
            document.querySelector("#sincronizando4").style.display = "none";
            document.querySelector("#save_formulario").addEventListener("click",saveFormulario);

            $('#version').bootstrapToggle({
              on: 'Habilitado',
              off: 'Inhabilitado'
            });

            @if($status == "1")
                $('#version').bootstrapToggle('on');
            @endif
          

        }

        function abrirExcelModal()
        {
                $("#modal_carga_masivo").modal("toggle");
        }

        //Add hijo
        function addHijo(ele){
            
            if(ele.parentElement.parentElement.dataset.row == "1")
            {
                if(ele.parentElement.parentElement.children[5].children[0].value == "" )
                {
                    mostrarModal(1,null,"Creación arbol de decisiones","No puede agregar más filas, por que no ha terminado de llenar la fila actual.\n",0,"Aceptar","",null);                                 
                    return;
                }
            }

            if(ele.parentElement.parentElement.dataset.row == "2")
            {
                if(
                ele.parentElement.parentElement.children[6].children[0].value == "")
                {
                    mostrarModal(1,null,"Creación arbol de decisiones","No puede agregar más filas, por que no ha terminado de llenar la fila actual.\n",0,"Aceptar","",null);                                 
                    return;
                }
            }

            if(ele.parentElement.parentElement.dataset.row == "3")
            {
                if(
                ele.parentElement.parentElement.children[7].children[0].value == "" )
                {
                    mostrarModal(1,null,"Creación arbol de decisiones","No puede agregar más filas, por que no ha terminado de llenar la fila actual.\n",0,"Aceptar","",null);                                 
                    return;
                }
            }
            mostrarNotificacion(1,"Se ha agrega una nueva fila");
            var html = "";
            if(ele.parentElement.parentElement.dataset.row == "1")
            {
                html += "<tr data-row='1'>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += '<td><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>';
                html += '<td><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>';
                html += '<td><i class="fa fa-arrow-circle-left" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="izquierda(this)"></i></td>';
                html += '<td><i class="fa fa-arrow-circle-right" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="derecha(this)"></i></td>';
                html += '<td colspan="3"><input type="text" value="" style="width:100%;" class="form-control" placeholder="Descripción"/></td>';
            }            
            
            if(ele.parentElement.parentElement.dataset.row == "2")
            {
                html += "<tr data-row='2'>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += '<td><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>';
                html += '<td><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>';
                html += '<td><i class="fa fa-arrow-circle-left" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="izquierda(this)"></i></td>';
                html += '<td><i class="fa fa-arrow-circle-right" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="derecha(this)"></i></td>';
                html += '<td colspan="3"><input type="text" value="" style="width:100%;" class="form-control" placeholder="Descripción"/></td>';
            } 
            
            if(ele.parentElement.parentElement.dataset.row == "3")
            {
                html += "<tr data-row='3'>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += '<td><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>';
                html += '<td><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>';
                html += '<td><i class="fa fa-arrow-circle-left" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="izquierda(this)"></i></td>';
                html += '<td><i class="fa fa-arrow-circle-right" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="derecha(this)"></i></td>';
                html += '<td colspan="3"><input type="text" value="" style="width:100%;" class="form-control" placeholder="Descripción"/></td>';
            } 

            if(ele.parentElement.parentElement.dataset.row == "4")
            {
                html += "<tr data-row='4'>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += "<td style='width:10px;border-right:1px solid transparent'></td>";
                html += '<td><i class="fa fa-plus" style="color:green;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="addHijo(this)"></i></td>';
                html += '<td><i class="fa fa-times" style="color:red;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="deleteHijo(this)"></i></td>';
                html += '<td><i class="fa fa-arrow-circle-left" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="izquierda(this)"></i></td>';
                html += '<td><i class="fa fa-arrow-circle-right" style="color:blue;font-size:20px;cursor:pointer;" aria-hidden="true" onclick="derecha(this)"></i></td>';
                html += '<td colspan="3"><input type="text" value="" style="width:70%;display:inline-block;" class="form-control" placeholder="Descripción"/>IN<input type="checkbox"/> - TE<input type="text" style="width:40px;" /> - AS <input type="checkbox"/> - DTS <input type="checkbox"/> - DVS <input type="checkbox"/></td>';
            } 

            html += '</tr>';
            $(ele.parentElement.parentElement).after(html);            
            
            var hijos = document.querySelector("#tbl_datos_frm").children;
            for (var i = 0; i < hijos.length; i++) {
                hijos[i].dataset.id = (i + 1);
            };
            ordenarDatos();
        }

        //Eliminar hijo
        function deleteHijo(ele)
        {
            var el  = ele.parentElement.parentElement;
            if(el.nextElementSibling != null)
            {
                //Validar hijo siguiente que no sea nivel 3
                if(el.nextElementSibling.dataset.row == 2)
                {
                    if(el.nextElementSibling.dataset.row == "2")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                        return;
                    }
                    
                }

                if(el.nextElementSibling.dataset.row == 3)
                {
                    if(el.nextElementSibling.dataset.row == "3")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                        return;
                    }
                    
                }

                if(el.nextElementSibling.dataset.row == 4)
                {
                    if(el.nextElementSibling.dataset.row == "4")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                        return;
                    }
                }
            }
            


            mostrarNotificacion(3,"Se ha eliminado una fila");
            $(ele.parentElement.parentElement.parentElement).find(ele.parentElement.parentElement).remove();
            var hijos = document.querySelector("#tbl_datos_frm").children;
            for (var i = 0; i < hijos.length; i++) {
                hijos[i].dataset.id = (i + 1);
            };
            ordenarDatos();

        }

        function derecha(ele)
        {
            var el  = ele.parentElement.parentElement;
            if(el.dataset.id == 1)
            {
                mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                return;
            }

            if(el.dataset.row == 1)
            {
                $(el).prepend("<td style='width:10px;border-right:1px solid transparent'></td>");
                el.dataset.row = 2;
                el.children[5].style.width = "10px";
                ordenarDatos();
                return;
            }

            if(el.dataset.row == 2)
            {
                if(el.previousElementSibling.dataset.row == "1")
                {
                    mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                    return;
                }
                $(el).prepend("<td style='width:10px;border-right:1px solid transparent'></td>");
                el.dataset.row = 3;
                el.children[6].style.width = "10px";
                ordenarDatos();
                return;
            }

            if(el.dataset.row == 3)
            {
                if(el.previousElementSibling.dataset.row == "2")
                {
                    mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                    return;
                }
                $(el).prepend("<td style='width:10px;border-right:1px solid transparent'></td>");
                el.dataset.row = 4;
                el.children[7].style.width = "10px";
                el.children[8].innerHTML = '<td colspan="3"><input type="text" value="' + el.children[8].children[0].value + '" style="width:70%;display:inline-block;" class="form-control" placeholder="Descripción"/>IN<input type="checkbox"/> - TE<input type="text" style="width:40px;" /> - AS <input type="checkbox"/> - DTS <input type="checkbox"/> - DVS <input type="checkbox"/></td>';
                ordenarDatos();
                return;
            }
       }

        function ordenarDatos()
        {
            var hijos = document.querySelector("#tbl_datos_frm").children;
            var dat1 = 0;
            var dat2 = 0;
            var dat3 = 0;
            var dat4 = 0;
            for (var i = 0; i < hijos.length; i++) {
                if(hijos[i].dataset.row == 1)
                {
                    dat1++;
                    dat2 = 0;
                    dat3 = 0;
                    hijos[i].children[0].innerHTML = dat1;
                }

                if(hijos[i].dataset.row == "2")
                {
                    dat2++;
                    dat3 = 0;
                    hijos[i].children[1].innerHTML = dat1 + "." + dat2;   
                }

                if(hijos[i].dataset.row == "3")
                {
                    dat3++;
                    hijos[i].children[2].innerHTML = dat1 + "." + dat2 + "." + dat3;   
                }

                if(hijos[i].dataset.row == "4")
                {
                    dat4++;
                    hijos[i].children[3].innerHTML = dat1 + "." + dat2 + "." + dat3 + "." + dat4;   
                }
            };
        }
        function izquierda(ele)
        {
            var el  = ele.parentElement.parentElement;
            if(el.dataset.row == "1")
            {
                mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                return;
            }

            if(el.dataset.row == "2")
            {
                //Validar hijo siguiente que no sea nivel 3
                if(el.nextElementSibling != null)
                {
                    if(el.nextElementSibling.dataset.row == "3")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                        return;
                    }    
                }
                
                $(el).find('td:first').remove();
                el.dataset.row = 1;
                ordenarDatos();
                return;
            }

            if(el.dataset.row == "3")
            {
                //Validar hijo siguiente que no sea nivel 3
                if(el.nextElementSibling != null)
                {
                    if(el.nextElementSibling.dataset.row == "3")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                        return;
                    }
                 }
                $(el).find('td:first').remove();
                el.dataset.row = 2;
                ordenarDatos();
                return;
            }

            if(el.dataset.row == "4")
            {
                //Validar hijo siguiente que no sea nivel 4
                if(el.nextElementSibling != null)
                {
                    if(el.nextElementSibling.dataset.row == "4")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","No puede realizar este movimiento",0,"Aceptar","",null);    
                        return;
                    }
                }
                $(el).find('td:first').remove();
                el.dataset.row = 3;
                ordenarDatos();
                return;
            }
        }   


        function saveFormulario()
        {

            var hijos = document.querySelector("#tbl_datos_frm").children;
            var arr = [];
            var parent1 = 0;
            var parent2 = 0;
            var parent3 = 0;
            for (var i = 0; i < hijos.length; i++) {
                if(hijos[i].dataset.row == "1")
                {
                    if(hijos[i].children[5].children[0].value == "")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","Hace falta diligenciar un campo",0,"Aceptar","",null);    
                        hijos[i].children[5].children[0].style.border = "1px solid red";
                        return;
                    }
                    else
                    {
                        arr.push(
                        {
                            'des' : hijos[i].children[5].children[0].value,
                            'id' : hijos[i].dataset.id,
                            'row' : hijos[i].dataset.row,
                            'parent' : 0,
                            'item' : hijos[i].children[0].innerHTML,
                            'in' : '',
                            'as' : '',
                            'dg' : '',
                            'ds' : '',
                            'te' : ''
                        });
                        parent1 = hijos[i].dataset.id;
                        hijos[i].children[5].children[0].style.border = "1px solid #ccc";
                    }
                }

                if(hijos[i].dataset.row == "2")
                {
                    if(hijos[i].children[6].children[0].value == "")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","Hace falta diligenciar un campo",0,"Aceptar","",null);    
                        hijos[i].children[6].children[0].style.border = "1px solid red";
                        return;
                    }
                    else
                    {
                        arr.push(
                        {
                            'des' : hijos[i].children[6].children[0].value,
                            'id' : hijos[i].dataset.id,
                            'row' : hijos[i].dataset.row,
                            'parent' : parent1,
                            'item' : hijos[i].children[1].innerHTML,
                            'in' : '',
                            'as' : '',
                            'dg' : '',
                            'ds' : '',
                            'te' : ''
                        });
                        parent2 = hijos[i].dataset.id;
                        hijos[i].children[6].children[0].style.border = "1px solid #ccc";
                    }
                }

                if(hijos[i].dataset.row == "3")
                {
                    if(hijos[i].children[7].children[0].value == "")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","Hace falta diligenciar un campo",0,"Aceptar","",null);    
                        hijos[i].children[7].children[0].style.border = "1px solid red";
                        return;
                    }
                    else
                    {
                        arr.push(
                        {
                            'des' : hijos[i].children[7].children[0].value,
                            'id' : hijos[i].dataset.id,
                            'row' : hijos[i].dataset.row,
                            'parent' : parent2,
                            'item' : hijos[i].children[2].innerHTML,
                            'in' : '',
                            'as' : '',
                            'dg' : '',
                            'ds' : '',
                            'te' : ''
                        });
                        parent3 = hijos[i].dataset.id;
                        hijos[i].children[7].children[0].style.border = "1px solid #ccc";
                    }
                }

                if(hijos[i].dataset.row == "4")
                {
                   if(hijos[i].children[8].children[0].value == "")
                    {
                        mostrarModal(1,null,"Creación arbol de decisiones","Hace falta diligenciar un campo",0,"Aceptar","",null);    
                        hijos[i].children[8].children[0].style.border = "1px solid red";
                        return;
                    }
                    else
                    {
                        if(hijos[i].children[8].children[1].checked)
                        {
                            if(hijos[i].children[8].children[2].value == "")
                            {
                                mostrarModal(1,null,"Creación arbol de decisiones","Hace falta diligenciar el tiempo estimado del item " + hijos[i].children[3].innerHTML,0,"Aceptar","",null);    
                                return;
                            }
                        }
                        arr.push(
                        {
                            'des' : hijos[i].children[8].children[0].value,
                            'id' : hijos[i].dataset.id,
                            'row' : hijos[i].dataset.row,
                            'parent' : parent3,
                            'item' : hijos[i].children[3].innerHTML,
                            'in' : (hijos[i].children[8].children[1].checked ? 1 : 0),
                            'te' : hijos[i].children[8].children[2].value,
                            'as' : (hijos[i].children[8].children[3].checked ? 1 : 0),
                            'dg' : (hijos[i].children[8].children[4].checked ? 1 : 0),
                            'ds' : (hijos[i].children[8].children[5].checked ? 1 : 0)
                        });
                        hijos[i].children[8].children[0].style.border = "1px solid #ccc";
                    }
                }

            };
            
            var datos = {
                form: arr,
                opc: 17
            }
            consultaAjax("../../rutaInsercionTransporte", datos, 150000, "POST", 1);
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
                            mostrarModal(1,null,"Árbol de decisiones","Se presentaron problemas con la creación del árbol de decisiones\n",0,"Aceptar","",null); 

                        if(data == "1")
                            mostrarModal(2,null,"Árbol de decisiones","Se ha actualizado correctamente el árbol de decisiones.\n",0,"Aceptar","",null);                                 
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

        function cambioVersion(ele)
        {

            $.ajax({
                url: '{{ url('/') }}/updateArbolDecision',
                type: "POST",
                headers: {'X-CSRF-TOKEN':document.querySelector("#token_datos").value},
                dataType: "json",
                data:{
                    opc : 5,
                    status : (ele.checked ? 1 : 0)
                },
                timeout:10000,
                success:function(data)
                {
                    /*mostrarModal(2,null,"EXITO","Se ha actualizado correctamente el estado del árbol de decisiones.\n",0,"Aceptar","",null); */  
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

