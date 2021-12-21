
<fieldset style="    border: 1px solid #ccc;    border-radius: 10px;    padding: 21px;">
            <legend style="border:0px;margin-bottom:0px; width: 25%;    font-weight: bold;"><i class="fa fa-cogs" aria-hidden="true"></i>Configuración del equipo</legend>
            <label>Nombre del equipo</label>
            @if(isset($lider))
                <input type="text"  class="form-control" disabled style="display:inline-block;width:20%;margin-top:10px;margin-left:50px;" id="txtNombreEquipo" value="{{$lider->nombre_equipo}}">
            @else
                <input type="text"  class="form-control" style="display:inline-block;width:20%;margin-top:10px;margin-left:50px;"  id="txtNombreEquipo">
            @endif
            <br>
            <label>Año</label>
            @if(isset($lider))
                <select class="form-control" onchange="consultaEquipo({{$id}})" style="display:inline-block;width:20%;margin-top:10px;margin-left:50px;" id="txt_anio">
            @else
                <select class="form-control" style="display:inline-block;width:20%;margin-top:10px;margin-left:50px;" id="txt_anio">
            @endif

            @if(isset($lider))

                @if(Session::has("aniomayor") && Session::get("aniomayor") != "" || date('Y') == '2021')
                    
                    @if(Session::get("aniomayor") == 2021 || date('Y') == '2021')
                        <option value="2021" selected>2021</option>
                        <option value="2020" >2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    @endif

                    @if(Session::get("aniomayor") == 2020)
                        <option value="2020" selected>2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    @endif
                    

                    @if(Session::get("aniomayor") == 2019)
                        <option value="2020" >2020</option>
                        <option value="2019" selected>2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    @endif

                    @if(Session::get("aniomayor") == 2018)
                        <option value="2020" >2020</option>
                        <option value="2019" >2019</option>
                        <option value="2018" selected>2018</option>
                        <option value="2017">2017</option>
                    @endif

                    @if(Session::get("aniomayor") == 2017)
                        <option value="2020" >2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017" selected>2017</option>
                    @endif
                @else
                    @if($lider->anio == 2021 )
                        <option value="2021" selected>2021</option>
                        <option value="2020">2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    @endif

                    @if($lider->anio == 2020 )
                        <option value="2020" selected>2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    @endif
                    

                    @if($lider->anio == 2019 )
                        <option value="2020" >2020</option>
                        <option value="2019" selected>2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                    @endif

                    @if($lider->anio == 2018 )
                        <option value="2020" >2020</option>
                        <option value="2019" >2019</option>
                        <option value="2018" selected>2018</option>
                        <option value="2017">2017</option>
                    @endif

                    @if($lider->anio == 2017 )
                        <option value="2020" >2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017" selected>2017</option>
                    @endif

                @endif
                


            @else

                @if($anio == 2021 || date('Y') == '2021')
                        <option value="2021" selected>2021</option>
                        <option value="2020" >2020</option>
                        <option value="2019">2019</option>
                        <option value="2018">2018</option>
                        <option value="2017">2017</option>
                @endif

                @if($anio == 2020)
                    <option value="2020" selected>2020</option>
                    <option value="2019">2019</option>
                    <option value="2018">2018</option>
                    <option value="2017">2017</option>
                @endif
                

                @if($anio == 2019)
                    <option value="2020" >2020</option>
                    <option value="2019" selected>2019</option>
                    <option value="2018">2018</option>
                    <option value="2017">2017</option>
                @endif

                @if($anio == 2018)
                    <option value="2020" >2020</option>
                    <option value="2019" >2019</option>
                    <option value="2018" selected>2018</option>
                    <option value="2017">2017</option>
                @endif

                @if($anio == 2017)
                    <option value="2020" >2020</option>
                    <option value="2019">2019</option>
                    <option value="2018">2018</option>
                    <option value="2017" selected>2017</option>
                @endif

            @endif
            </select>
            <br>
            <label>Mes</label>
            @if(isset($lider))
                <select class="form-control" onchange="consultaEquipo({{$id}})" style="display:inline-block;width:20%;margin-top:10px;margin-left:50px;" id="txt_mes">
            @else
                <select class="form-control" style="display:inline-block;width:20%;margin-top:10px;margin-left:50px;" id="txt_mes">
            @endif

             @if(isset($lider))

                @if(Session::has("mesmayor") && Session::get("mesmayor") != "")
                     @if(  Session::get("mesmayor") == 1)
                        <option value="1" selected>Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 2)
                        <option value="1" >Enero</option>
                        <option value="2" selected>Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 3)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" selected>Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 4)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4" selected>Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 5)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5" selected>Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 6)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6" selected>Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 7)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7" selected>Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 8)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8" selected>Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 9)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9" selected>Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 10)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10" selected>Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 11)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11" selected>Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if(  Session::get("mesmayor") == 12)
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" selected>Diciembre</option>  
                    @endif
                @else
                    @if($lider->mes == 1) )
                        <option value="1" selected>Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 2) )
                        <option value="1" >Enero</option>
                        <option value="2" selected>Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 3) )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3" selected>Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 4) )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4" selected>Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 5) )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5" selected>Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 6) )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6" selected>Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 7) )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7" selected>Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 8) )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8" selected>Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 9) )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9" selected>Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 10 )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10" selected>Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 11 )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11" selected>Noviembre</option>
                        <option value="12">Diciembre</option>  
                    @endif

                    @if($lider->mes == 12 )
                        <option value="1" >Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12" selected>Diciembre</option>  
                    @endif
                @endif
               
            @else

                @if($mes == 1)
                    <option value="1" selected>Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 2)
                    <option value="1" >Enero</option>
                    <option value="2" selected>Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 3)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3" selected>Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 4)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4" selected>Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 5)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5" selected>Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 6)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6" selected>Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 7)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7" selected>Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 8)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8" selected>Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 9)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9" selected>Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 10)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10" selected>Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 11)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11" selected>Noviembre</option>
                    <option value="12">Diciembre</option>  
                @endif

                @if($mes == 12)
                    <option value="1" >Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12" selected>Diciembre</option>  
                @endif


            @endif
                        

            Session::forget("mesmayor");
            Session::forget("aniomayor");


            </select>
        </fieldset>


        <fieldset style="    border: 1px solid #ccc;    border-radius: 10px;    padding: 21px;">
        <legend style="border:0px;margin-bottom:0px; width: 25%;    font-weight: bold;"><i class="fa fa-address-book-o" aria-hidden="true"></i> Conformación del equipo</legend>
            <!--<div  style="overflow-x: scroll">-->
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-3" >
                        <label style="display:block;">Personal</label>
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-1">
                                <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Observación comportamiento">Compor...</label>
                        </div>

                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="IPALES">IPALES</label>
                        </div>

                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Calidad">Calidad</label>
                        </div>

                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Ambiental">Ambiental</label>
                        </div>

                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Inspeccion Seguridad Obras Civiles">Seguridad Obra..</label>
                        </div>
                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Inspeccion Seguridad Telecomunicaciones">Telecom...</label>
                        </div>
                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Inspeccion Redes Electricas">Redes</label>
                        </div>
                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Inspeccion Kit Manejo de Derrames">Derrames</label>
                        </div>
                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Inspección Locativa de Gestión Ambiental">Gestión Ambiental</label>
                        </div>
                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Inspección Entrega Obras Civiles">Entrega Obra..</label>
                        </div>
                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Inspección Calidad Trabajos de Restablecimiento del Servicio">Calidad Serv..</label>
                        </div>
                        <div class="col-md-1">
                            <label style="text-align:center;width:100%;margin-top:15px;cursor: pointer;" title="Inspección de Calidad Trabajos de Mantenimiento y/o Obras en MT y BT">Mantenimi...</label>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-3" style="margin-top:10px;">
                        <label style="display:block;">Líder</label>


                        @if(isset($lider))
                            <input type="text" data-cedula = "{{$lider->lider}}" readonly="" class="form-control" value="{{strtoupper($lider->nombres)}} {{strtoupper($lider->apellidos)}}" style="display:inline-block;width:75%;" id="txtLiderTable">
                        @else
                            <button onclick="abrirModalPersonas(1,this)" type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-search"></i></button>
                            <button onclick="limpiarPersonas(1,this)" type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-trash-o"></i></button>
                            <input type="text" readonly="" class="form-control" style="display:inline-block;width:75%;" id="txtLiderTable">
                        @endif
                    </div>
                    <div class="col-md-8" style="margin-top:40px;">
                    @if(isset($lider))
                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" value="{{$lider->comportamiento}}" onkeyup ="cambiarDatos(event,1,this)" onclick="cambiarDatos2(this,1)" id="txtLide1"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" value="{{$lider->ipales}}"  onkeyup ="cambiarDatos(event,2,this)" onclick="cambiarDatos2(this,2)" id="txtLide2"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" value="{{$lider->calidad}}"  onkeyup ="cambiarDatos(event,3,this)" onclick="cambiarDatos2(this,3)" id="txtLide3"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control"  min="0" value="{{$lider->ambiental}}" onkeyup ="cambiarDatos(event,4,this)" onclick="cambiarDatos2(this,4)" id="txtLide4"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control"  min="0" value="{{$lider->seguridad_obra_civil}}" onkeyup ="cambiarDatos(event,5,this)" onclick="cambiarDatos2(this,5)" id="txtLide5"/>
                        </div>
                        <div class="col-md-1">
                            <input type="number" class="form-control"  min="0" value="{{$lider->telecomunicaciones}}" onkeyup ="cambiarDatos(event,6,this)" onclick="cambiarDatos2(this,6)" id="txtLide6"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" value="{{$lider->redes_electricas}}"  onkeyup ="cambiarDatos(event,7,this)" onclick="cambiarDatos2(this,7)" id="txtLide7"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control"  min="0" value="{{$lider->kit_manejo_derrames}}" onkeyup ="cambiarDatos(event,8,this)" onclick="cambiarDatos2(this,8)" id="txtLide8"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control"  min="0" value="{{$lider->locativa_gestion_ambiental}}" onkeyup ="cambiarDatos(event,9,this)" onclick="cambiarDatos2(this,9)" id="txtLide9"/>
                        </div>
                        <div class="col-md-1">
                            <input type="number" class="form-control"  min="0" value="{{$lider->entrega_obra_civil}}" onkeyup ="cambiarDatos(event,10,this)" onclick="cambiarDatos2(this,10)" id="txtLide10"/>
                        </div>
                        <div class="col-md-1">
                            <input type="number" class="form-control"  min="0" value="{{$lider->restablecimiento_servicio}}" onkeyup ="cambiarDatos(event,11,this)" onclick="cambiarDatos2(this,11)" id="txtLide11"/>
                        </div>
                        <div class="col-md-1">
                            <input type="number" class="form-control"  min="0" value="{{$lider->mantenimiento}}" onkeyup ="cambiarDatos(event,13,this)" onclick="cambiarDatos2(this,12)" id="txtLide12"/>
                        </div>

                    @else
                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0"  onkeyup ="cambiarDatos(event,1,this)" onclick="cambiarDatos2(this,1)" id="txtLide1"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" onkeyup ="cambiarDatos(event,2,this)" onclick="cambiarDatos2(this,2)" id="txtLide2"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0"  onkeyup ="cambiarDatos(event,3,this)" onclick="cambiarDatos2(this,3)" id="txtLide3"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control"   min="0" value="0" onkeyup ="cambiarDatos(event,4,this)" onclick="cambiarDatos2(this,4)"  id="txtLide4"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control"   min="0" value="0" onkeyup ="cambiarDatos(event,5,this)" onclick="cambiarDatos2(this,5)"  id="txtLide5"/>
                        </div>
                        <div class="col-md-1">
                            <input type="number" class="form-control"   min="0" value="0" onkeyup ="cambiarDatos(event,6,this)" onclick="cambiarDatos2(this,6)"  id="txtLide6"/>
                        </div>
                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0"  onkeyup ="cambiarDatos(event,7,this)" onclick="cambiarDatos2(this,7)" id="txtLide7"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" onkeyup ="cambiarDatos(event,8,this)" onclick="cambiarDatos2(this,8)" id="txtLide8"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0"  onkeyup ="cambiarDatos(event,9,this)" onclick="cambiarDatos2(this,9)" id="txtLide9"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control"   min="0" value="0" onkeyup ="cambiarDatos(event,10,this)" onclick="cambiarDatos2(this,10)"  id="txtLide10"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control"   min="0" value="0" onkeyup ="cambiarDatos(event,11,this)" onclick="cambiarDatos2(this,11)"  id="txtLide11"/>
                        </div>
                        <div class="col-md-1">
                            <input type="number" class="form-control"   min="0" value="0" onkeyup ="cambiarDatos(event,12,this)" onclick="cambiarDatos2(this,12)"  id="txtLide12"/>
                        </div>

                    @endif
                    </div>
                    <div class="col-md-1">
                        <input type="checkbox" id="ChekReplica" onchange ="cambiarDatosTodos()" style="    width: 20px;    height: 20px;" /> <span style="position:relative;top:-6px;">Replicar</span>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-3" style="margin-top:10px;">
                    <br><br>
                    <label style="display:block;">Integrantes</label>
                    <button onclick="agregarColaborador()" type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-plus"></i>  Agregar integrante</button>

                    <br><br>
                    <button onclick="abrirModalPersonas(2,this)"  type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-search"></i></button>
                    <button onclick="limpiarPersonas(2,this)" type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-trash-o"></i></button>
                    @if(isset($colaborador))
                        @if(count($colaborador)> 0)
                            <input type="text" readonly="" data-cedula = "{{$colaborador[0]->colaborador}}" value="{{strtoupper($colaborador[0]->nombres)}} {{strtoupper($colaborador[0]->apellidos)}}" class="form-control" style="display:inline-block;width:75%;" id="txtColaboradorTable">
                        @endif
                    @else
                        <input type="text" readonly="" class="form-control" style="display:inline-block;width:75%;" id="txtColaboradorTable">
                    @endif

                </div>

                <div class="col-md-8" style="margin-top:130px;">
                    @if(isset($colaborador))
                        @if(count($colaborador)> 0)
                            <div class="col-md-1">
                                <input type="number" value = "{{$colaborador[0]->comportamiento}}" class="form-control" min="0" id="txtCola1"/>
                            </div>

                            <div class="col-md-1">
                                <input type="number" value = "{{$colaborador[0]->ipales}}" class="form-control" min="0" id="txtCola2"/>
                            </div>

                            <div class="col-md-1">
                                <input type="number" value = "{{$colaborador[0]->calidad}}" class="form-control" min="0" id="txtCola3"/>
                            </div>

                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->ambiental}}"  class="form-control" min="0" id="txtCola4"/>
                            </div>
                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->seguridad_obra_civil}}"  class="form-control" min="0" id="txtCola5"/>
                            </div>
                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->telecomunicaciones}}"  class="form-control" min="0" id="txtCola6"/>
                            </div>
                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->redes_electricas}}"  class="form-control" min="0" id="txtCola7"/>
                            </div>
                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->kit_manejo_derrames}}"  class="form-control" min="0" id="txtCola8"/>
                            </div>
                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->locativa_gestion_ambiental}}"  class="form-control" min="0" id="txtCola9"/>
                            </div>
                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->entrega_obra_civil}}"  class="form-control" min="0" id="txtCola10"/>
                            </div>
                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->restablecimiento_servicio}}"  class="form-control" min="0" id="txtCola11"/>
                            </div>
                            <div class="col-md-1">
                                <input type="number"  value = "{{$colaborador[0]->mantenimiento}}"  class="form-control" min="0" id="txtCola12"/>
                            </div>

                        @endif
                    @else
                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" id="txtCola1"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" id="txtCola2"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" id="txtCola3"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" value="0" class="form-control" min="0" id="txtCola4"  />
                        </div>

                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" id="txtCola5"/>
                        </div>

                        <div class="col-md-1">
                            <input type="number" value="0" class="form-control" min="0" id="txtCola6"  />
                        </div>
                        <div class="col-md-1">
                            <input type="number" value="0" class="form-control" min="0" id="txtCola7"  />
                        </div>
                        <div class="col-md-1">
                            <input type="number" value="0" class="form-control" min="0" id="txtCola8"  />
                        </div>
                        <div class="col-md-1">
                            <input type="number" value="0" class="form-control" min="0" id="txtCola9"  />
                        </div>
                        <div class="col-md-1">
                            <input type="number" value="0" class="form-control" min="0" id="txtCola10"  />
                        </div>
                        <div class="col-md-1">
                            <input type="number" value="0" class="form-control" min="0" id="txtCola11"  />
                        </div>
                        <div class="col-md-1">
                            <input type="number" value="0" class="form-control" min="0" id="txtCola12"  />
                        </div>

                    @endif
                </div>  
            </div>

                <span id="elementos_add">
                @if(isset($colaborador))
                    @if(count($colaborador)> 1)
                        @for($i = 1; $i < count($colaborador) ; $i++)
                            <div class="row">
                               <div class="col-md-3" style="margin-top:10px;">
                                            <button onclick="abrirModalPersonas(3,this)"  type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-search"></i></button>
                                            <button onclick="limpiarPersonas(3,this)" type="submit" class="btn btn-primary btn-cam-trans btn-sm"  id="btn-add-nodos-orden" ><i class="fa fa-trash-o"></i></button>
                                            <input type="text" data-cedula = "{{$colaborador[$i]->colaborador}}" value="{{strtoupper($colaborador[$i]->nombres)}} {{strtoupper($colaborador[$i]->apellidos)}}"  readonly="" class="form-control" style="display:inline-block;width:75%;">
                                        </div>                    
                                        <div class="col-md-8" style="margin-top:10px;">
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->comportamiento}}"  type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->ipales}}"  type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->calidad}}"  type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->ambiental}}"   type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->seguridad_obra_civil}}"  type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->telecomunicaciones}}"   type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->redes_electricas}}"   type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->kit_manejo_derrames}}"   type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->locativa_gestion_ambiental}}"   type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->entrega_obra_civil}}"   type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->restablecimiento_servicio}}"   type="number" class="form-control" min="0"/>
                                            </div>
                                            <div class="col-md-1">
                                                <input value = "{{$colaborador[$i]->mantenimiento}}"   type="number" class="form-control" min="0"/>
                                            </div>
                                            <div>
                                                <i class="fa fa-times" onclick="eliminarRegistro(this)" style="color:red;font-size: 27px; float: right;margin-top: -30px;margin-right: -20px" title="Eliminar registro"></i>
                                            </div>
                                        </div>
                                </div>
                        @endfor
                    @endif
                @endif
            </span>

                <div class="row">
                    <button onclick="guardarColaborador()" style="margin-top:10px;margin-left:10px;" type="submit" class="btn btn-primary btn-cam-trans btn-sm"   ><i class="fa fa-save"></i>Guardar plan de supervisión</button>
                    <a href="{{url('/')}}/transversal/supervision/conformacion"  style="margin-top:10px;margin-left:10px;    color: #fff;    background-color: #286090;    border-color: #204d74;" class="btn btn-primary btn-cam-trans btn-sm" ><i class="fa fa-times"></i>Salir</a>
                </div>

        </fieldset>
        