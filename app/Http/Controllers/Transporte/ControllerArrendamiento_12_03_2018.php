<?php

namespace App\Http\Controllers\Transporte;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use Carbon\Carbon;
use Redirect;

class ControllerArrendamiento extends Controller
{
    
    /*************************************************/
    /******** FUNCIONES DEL CONTROLADOR **************/
    function __construct() {
        //Session::put('user_login',"U01853");
        
        
        Session::put('proy_short',"home");
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    //Guarda log 
    private function saveLog($id_log,$campo_valor,$descripcion)
    {
        DB::connection('sqlsrvCxParque')
            ->table('tra_log')
            ->insert(array(
                array(
                    'id_log' => $id_log,
                    'fecha' => $this->fechaALong,
                    'id_usuario' => Session::get('user_login'),
                    'campro_valor' => $campo_valor,
                    'descripcion' => $descripcion
                )
            ));

        return "1";
    }

    //Consulta permisos usuarios
    private function consultaAcceso($opc)
    {
        $id_perfil = DB::table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    ->value('id_perfil');

        $per = DB::table('sis_perfiles_opciones')
                    ->where('id_perfil',$id_perfil)
                    ->where('id_opcion',$opc)
                    ->select('id_opcion','nivel_acceso')
                    ->get();

        if(count($per) == 0)
            return "N";
        else
            return $per[0]->nivel_acceso;
    }

    //Función encargada de generar consecutivos
    private function generaConsecutivo($tipo)
    {
        $consen =   DB::connection('sqlsrvCxParque')
                    ->table('gen_consecutivos')
                    ->where('id_campo',$tipo)
                    ->select('consecutivo','prefijo','long_cadena','caracter_relleno')
                    ->get();

        if(count($consen) == 0)
            return -1;


        $lnconsecutivo = $consen[0]->consecutivo;
        $prefijo       = $consen[0]->prefijo;
        $longitud_max  = $consen[0]->long_cadena;
        $relleno       = $consen[0]->caracter_relleno;
                
        //Aumentar Consecutivo
        $lnconsecutivo = $lnconsecutivo + 1 ;

        //Ahora Actualizar el Consecutivo de una vez
        DB::connection('sqlsrvCxParque')
            ->table('gen_consecutivos')
            ->where('id_campo',$tipo)
            ->update(array(
                'consecutivo' => $lnconsecutivo
                ));
        
        $num_relleno    = $longitud_max - strlen($prefijo) ; 
        $char_rellenos  = self::lfillchar($lnconsecutivo,$num_relleno,$relleno) ;
        $ret            = $prefijo.$char_rellenos.$lnconsecutivo ; 
        return $ret; 
    }

    //Función encargada de rellenar de 0 el consecutivo generado
    private function lfillchar($cadena, $numchar, $charfill)
    {
        //Rellena a la Izquierda
        $cadena_final = "";
        $maxchar = strlen($cadena);
        $maxchar = $numchar - $maxchar;
        for ($i = 1; $i <= $maxchar; $i++) {
            $cadena_final = $cadena_final . $charfill;
      

        }

        return $cadena_final;
    }

    /******** FUNCIONES DEL CONTROLADOR **************/
    /*************************************************/
    


    /********************************************************/
    /******** GENERAR DOCUMENTOS DE ARRENDAMIENTO **************/

    //Función encargado de mostrar indivialmente los documentos generado por vehículo en un almanaque
    public function index($proyectoUser = "",$placa = "")
    {

        if(!Session::has('user_login'))
            return Redirect::to('http://162.248.55.204:8090/campro/');

        $permisoArrendamiento = self::consultaAcceso('OP090');

        if($permisoArrendamiento == "N")
            return view('errors.nopermiso');   

        $imprimirArrendamiento = self::consultaAcceso('OP092');

        $arr = explode("-",$this->fechaShort);
        $month=$arr[1];
        $year=$arr[0];
        $dia = $arr[2];


        $arreglo = Array();
        array_push($arreglo,$year + 1);
        array_push($arreglo,$year);
        array_push($arreglo,$year - 1);
        array_push($arreglo,$year - 2);

        $datosA = [];
        $ultimoDia =  date("d",(mktime(0,0,0,$month+1,1,$year)-1));
        $fecha = date('l', strtotime($year . "-" . $month . "-01"));

        $primerDia = 0;
        if ($fecha === "Monday") 
        { 
             $primerDia = 1;
        }

        if ($fecha === "Tuesday") 
        { 
             $primerDia = 2;
        }

        if ($fecha === "Wednesday") 
        { 
             $primerDia = 3;
        }

        if ($fecha === "Thursday") 
        { 
             $primerDia = 4;
        }

        if ($fecha === "Friday") 
        { 
             $primerDia = 5;
        }

        if ($fecha === "Saturday") 
        { 
             $primerDia = 6;
        }

        if ($fecha === "Sunday") 
        { 
             $primerDia = 7;
        }

        $mes = "";
        switch ($month) {
            case 1:
                $mes = "ENERO";
            break;
            case 2:
                $mes = "FEBRERO";
            break;
            case 3:
                $mes = "MARZO";
            break;
            case 4:
                $mes = "ABRIL";
            break;
            case 5:
                $mes = "MAYO";
            break;
            case 6:
                $mes = "JUNIO";
            break;
            case 7:
                $mes = "JULIO";
            break;
            case 8:
                $mes = "AGOSTO";
            break;
            case 9:
                $mes = "SEPTIEMBRE";
            break;
            case 10:
                $mes = "OCTUBRE";
            break;
            case 11:
                $mes = "NOVIEMBRE";
            break;
            case 12:
                $mes = "DICIEMBRE";
            break;

        }

        $fecha1 = $arreglo[1] . "-" . $month . "-" . 1;
        $fecha2 = $arreglo[1] . "-" . $month . "-" . $ultimoDia;

        $cambioPro = DB::connection('sqlsrvCxParque')
                    ->table('tra_cambio_proyectos as cambio')
                    ->join('tra_contratantes as pry','pry.id','=','cambio.proyecto')
                    ->where('cambio.placa',$placa)
                    ->whereBetween('cambio.fecha_servidor',[$fecha1,$fecha2])
                    ->orderBy('cambio.fecha_servidor')
                    ->get(['cambio.fecha_servidor','cambio.proyecto','pry.prefijo']);


        //Consulta arrendamientos
        $id_documento = DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento')
                        ->where('placa',$placa)
                        ->where('mes',$month)
                        ->where('anio',$arreglo[1])
                        ->get(['id_documento','canon_actual']);

        //Consulta arrendamientos toda la información
        $arrendamiento = DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento as arren')
                        ->where('placa',$placa)
                        ->where('mes',$month)
                        ->where('anio',$arreglo[1])
                        ->join('CAMPRO.dbo.sis_usuarios as user1','user1.id_usuario','=','usuario_crea')
                        ->leftJoin('CAMPRO.dbo.sis_usuarios as user2','user2.id_usuario','=','usuario_anula')
                        ->join('CAMPRO.dbo.sis_usuarios as user3','user3.id_usuario','=','usuario_ultima_actualizacion')
                        ->join('CAMPRO.dbo.gop_proyectos as pry','pry.prefijo_db','=','prefijo')
                        ->get(['id_documento','canon_actual','placa','arren.id_estado','cantidad_dias','total_pagar'
                            ,'arren.fecha_creacion','arren.fecha_anula','arren.fecha_ultima_actualizacion',
                            'user1.propietario as crea_user','user2.propietario as anula_user','user3.propietario as anula_actualiza',
                            'pry.nombre']);

        $dias = [];
        $canonActual = -1;
        if(count($id_documento) > 0)
        {
            $canonActual = $id_documento[0]->canon_actual;
            for ($i=0; $i < count($id_documento); $i++) { 
                array_push($dias,DB::connection('sqlsrvCxParque')
                                ->table('tra_detalle_arrendamiento as doc')
                                ->join('tra_arrendamiento as arr','arr.id_documento','=','doc.id_documento')
                                ->where('doc.id_documento',$id_documento[$i]->id_documento)
                                ->get(['doc.dia','doc.check','doc.id_documento','arr.id_estado'])
                        );
            }
        }


        $diasCambioProyecto = [];
        $proyectoID = [];
        $proyectoNombre = [];
        $proyectoPre = [];
        $tipoDia = [];
        $incidencias = [];
        $check = [];

        $datos = 0;
        $primerDiaConsulta = 0;

        $proyectoUltID = "";
        $proyectoUltNombre = "";
        $proyectoUltTipo = "";
        $proyectoUltInci = "";

        foreach ($cambioPro as $key => $valor) {

            if($datos == 0)
                $primerDiaConsulta = explode("-",explode(" ",$valor->fecha_servidor)[0])[2];

            array_push($diasCambioProyecto,intval(explode("-",explode(" ",$valor->fecha_servidor)[0])[2]));
            array_push($proyectoID,$valor->proyecto);

            $pryNom = DB::connection('sqlsrvCxParque')
                    ->table('tra_contratantes')
                    ->where('id',$valor->proyecto)
                    ->value('nombre');

            array_push($proyectoNombre,$pryNom);
            array_push($proyectoPre,$valor->prefijo);
            array_push($tipoDia, 1);
            array_push($incidencias, "");
            
            $proyectoUltID = $valor->proyecto;
            $proyectoUltNombre = $pryNom;
            $datos++;
        }

        if(count($cambioPro) == 0 ||  intval($primerDiaConsulta) >= 0 && intval($primerDiaConsulta) != 1) //No existen cambios del proyecto
        {
            array_push($diasCambioProyecto,1);
            $id_pro = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placa)
                    ->value('id_proyecto');
            array_push($proyectoID,$id_pro);
            array_push($proyectoNombre,
                DB::connection('sqlsrvCxParque')
                    ->table('tra_contratantes')
                    ->where('id',$id_pro)
                    ->value('nombre'));
            array_push($tipoDia, 1);
            array_push($proyectoPre,DB::connection('sqlsrvCxParque')
                    ->table('tra_contratantes')
                    ->where('id',$id_pro)
                    ->value('prefijo'));
            array_push($incidencias, "");
        }

 

        if(count($cambioPro) > 0)
        {
            array_push($diasCambioProyecto,intval($ultimoDia));
            array_push($proyectoID,$proyectoUltID);
            array_push($proyectoNombre,$proyectoUltNombre);
            array_push($proyectoPre,$valor->prefijo);
            array_push($tipoDia, 1);
            array_push($incidencias, "");
        }

 

        $temp = 0;
        $temp1 = "";
        $temp2 = "";
        $temp3 = "";
        $temp4 = "";
        $temp5 = "";

        //Organiza los días
        for ($i=1; $i<count($diasCambioProyecto); $i++)
        {
            for ($j=0 ; $j<count($diasCambioProyecto) - 1; $j++)
            {
               if ($diasCambioProyecto[$j] > $diasCambioProyecto[$j+1])
               {
                    $temp = $diasCambioProyecto[$j];
                    $temp1 = $proyectoID[$j];
                    $temp2 = $proyectoNombre[$j];
                    $temp3 = $tipoDia[$j];
                    $temp4 = $incidencias[$j];
                    $temp5 = $proyectoPre[$j];

                    $diasCambioProyecto[$j] = $diasCambioProyecto[$j+1];
                    $diasCambioProyecto[$j+1] = $temp; 

                    $proyectoID[$j] = $proyectoID[$j+1];
                    $proyectoID[$j+1] = $temp1; 

                    $proyectoNombre[$j] = $proyectoNombre[$j+1];
                    $proyectoNombre[$j+1] = $temp2; 

                    $tipoDia[$j] = $tipoDia[$j+1];
                    $tipoDia[$j+1] = $temp3; 

                    $incidencias[$j] = $incidencias[$j+1];
                    $incidencias[$j+1] = $temp4; 

                    $proyectoPre[$j] = $proyectoPre[$j+1];
                    $proyectoPre[$j+1] = $temp5; 

               }
            }               
        }


        if(count($cambioPro) == 0)
        {
            $id_pro = DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->value('id_proyecto');

            $pry = DB::connection('sqlsrvCxParque')
                        ->table('tra_contratantes')
                        ->where('id',$id_pro)
                        ->get(['nombre','prefijo'])[0];

            for ($i=2; $i <= $ultimoDia; $i++) { 
                array_push($diasCambioProyecto,$i);
                
                array_push($proyectoID,$id_pro);
                array_push($proyectoNombre,$pry->nombre);
                array_push($proyectoPre,$pry->prefijo);

                array_push($tipoDia, 1);
                array_push($incidencias, "");
            }
        }
        else
        {
            
            for ($i=0; $i < count($diasCambioProyecto) - 1; $i++) { 
                    $dia1 = $diasCambioProyecto[$i];
                    $dia2 = $diasCambioProyecto[$i + 1];

                    for ($j=$dia1; $j < $dia2; $j++) { 
                        if($dia1 != $j && $dia2 != $j)
                        {
                            $exit = 0;
                            for ($k=0; $k < count($diasCambioProyecto); $k++) { 
                                if($diasCambioProyecto[$k] == $j)
                                {
                                    $exit = 1;
                                    break;
                                }
                            }

                            if($exit == 0)
                            {
                                array_push($diasCambioProyecto,$j);
                                array_push($proyectoID,$proyectoID[$i]);
                                array_push($proyectoNombre,$proyectoNombre[$i]);
                                array_push($tipoDia, $tipoDia[$i]);
                                array_push($incidencias, $incidencias[$i]);       
                                array_push($proyectoPre, $proyectoPre[$i]);
                            }
                        }
                    }  
            }
        }

        //Consulta incidencias generadas por el vehículo en el periodo seleccioando
        $incidenciasGenerada = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia as inc')
                    ->join('tra_incidencia_novedad as nov','inc.incidencia','=','nov.incidencia')
                    ->where('inc.placa',$placa)
                    ->whereBetween('fecha_ingreso',[$fecha1 . " 00:00:01",$fecha2 . " 23:59:59"])
                    ->orderBy('fecha_ingreso')
                    ->get(['nov.incidencia','nov.fecha_ingreso']);

        
        //Recorre incidencias generadas en el mismo mes
        foreach ($incidenciasGenerada as $key => $valor) {

            $diaInicioIncidencia = 0;
            $diaFinIncidencia = 0;
            //Consulta ingreso a taller
            for ($i=0; $i < count($diasCambioProyecto); $i++) { 
                if($diasCambioProyecto[$i] == intval(explode("-",explode(" ",$valor->fecha_ingreso)[0])[2]))
                {
                    $diaInicioIncidencia = $diasCambioProyecto[$i];
                    $tipoDia[$i] = 2;
                    $incidencias[$i] = $valor->incidencia;
                }
            }           

            //Consulta si para si en el mismo periodo se le hizo salida de taller
            $incidenciaCerrada = DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad')
                        ->where('incidencia',$valor->incidencia)
                        ->whereBetween('fecha_salida',[$fecha1 . " 00:00:01",$fecha2 . " 23:59:59"])
                        ->orderBy('fecha_salida')
                        ->value('fecha_salida');

            //Valida si ya tiene fin de taller 
            if($incidenciaCerrada != "" && $incidenciaCerrada != NULL && $incidenciaCerrada != "NULL")
            {
                for ($i=0; $i < count($diasCambioProyecto); $i++) { 
                    if($diasCambioProyecto[$i] == intval(explode("-",explode(" ",$incidenciaCerrada)[0])[2]))
                    {
                        $diaFinIncidencia = $diasCambioProyecto[$i];
                        $tipoDia[$i] = 2;
                        $incidencias[$i] = $valor->incidencia;
                    }
                }

                //Marca los días como en taller
                for ($i=0; $i < count($diasCambioProyecto); $i++) {
                    if($diasCambioProyecto[$i] > $diaInicioIncidencia  && $diasCambioProyecto[$i] < $diaFinIncidencia)
                    {
                        $tipoDia[$i] = 2;
                        $incidencias[$i] = $valor->incidencia;
                    }
                }
            }
            else
            {
                //Marca los días como en taller
                for ($i=0; $i < count($diasCambioProyecto); $i++) {
                    if($diasCambioProyecto[$i] > $diaInicioIncidencia  && $diasCambioProyecto[$i] <= $ultimoDia)
                    {
                        $tipoDia[$i] = 2;
                        $incidencias[$i] = $valor->incidencia;
                    }
                }   
            }
        }


        //Consulta incidencias cerradas por el vehículo en el periodo seleccioando  y generas antes del mes
        $incidenciasGenerada = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia as inc')
                    ->join('tra_incidencia_novedad as nov','inc.incidencia','=','nov.incidencia')
                    ->where('inc.placa',$placa)
                    ->whereBetween('fecha_salida',[$fecha1 . " 00:00:01",$fecha2 . " 23:59:59"])
                    ->where('nov.fecha_ingreso','<',$fecha1 . " 00:00:01")
                    ->orderBy('fecha_salida')
                    ->get(['nov.incidencia','nov.fecha_salida']);

        //dd($incidenciasGenerada);
        //Recorre incidencias generadas en el mismo mes
        foreach ($incidenciasGenerada as $key => $valor) {

            $diaInicioIncidencia = 0;
            $diaFinIncidencia = 0;
            
            if($valor->fecha_salida != "" && $valor->fecha_salida != NULL && $valor->fecha_salida != "NULL")
            {
                for ($i=0; $i < count($diasCambioProyecto); $i++) { 
                    if($diasCambioProyecto[$i] == intval(explode("-",explode(" ",$valor->fecha_salida)[0])[2]))
                    {
                        $diaFinIncidencia = $diasCambioProyecto[$i];
                        $tipoDia[$i] = 2;
                        $incidencias[$i] = $valor->incidencia;
                    }
                }

                //Marca los días como en taller
                for ($i=0; $i < count($diasCambioProyecto); $i++) {
                    if($diasCambioProyecto[$i] > $diaInicioIncidencia  && $diasCambioProyecto[$i] < $diaFinIncidencia)
                    {
                        $tipoDia[$i] = 2;
                        $incidencias[$i] = $valor->incidencia;
                    }
                }
            }
        }


        $temp = 0;
        $temp1 = "";
        $temp2 = "";
        $temp3 = "";
        $temp4 = "";
        $temp5 = "";

        for ($i=1; $i<count($diasCambioProyecto); $i++)
        {
            for ($j=0 ; $j<count($diasCambioProyecto) - 1; $j++)
            {
               if ($diasCambioProyecto[$j] > $diasCambioProyecto[$j+1])
               {
                    $temp = $diasCambioProyecto[$j];
                    $temp1 = $proyectoID[$j];
                    $temp2 = $proyectoNombre[$j];
                    $temp3 = $tipoDia[$j];
                    $temp4 = $incidencias[$j];
                    $temp5 = $proyectoPre[$j];

                    $diasCambioProyecto[$j] = $diasCambioProyecto[$j+1];
                    $diasCambioProyecto[$j+1] = $temp; 

                    $proyectoID[$j] = $proyectoID[$j+1];
                    $proyectoID[$j+1] = $temp1; 

                    $proyectoNombre[$j] = $proyectoNombre[$j+1];
                    $proyectoNombre[$j+1] = $temp2; 

                    $tipoDia[$j] = $tipoDia[$j+1];
                    $tipoDia[$j+1] = $temp3; 

                    $incidencias[$j] = $incidencias[$j+1];
                    $incidencias[$j+1] = $temp4; 

                    $proyectoPre[$j] = $proyectoPre[$j+1];
                    $proyectoPre[$j+1] = $temp5; 
                    
               }
            }               
        }

        
        $exist = 0;
        $check = 0;
        $id_doc = -1;

        //var_dump($diasCambioProyecto);
        //dd($proyectoPre);
        for ($i=0; $i<count($diasCambioProyecto); $i++)
        {
            $exist = 0;
            $check = 0;
            $id_doc = -1;
            $estado = -1;
            for ($j=0; $j < count($dias); $j++) { 
            for ($k=0; $k < count($dias[$j]); $k++)
                {
                   $diaSelec =  $dias[$j][$k]->dia;
                   if($diaSelec == $diasCambioProyecto[$i])
                   {
                        $exist = 1;
                        $check = $dias[$j][$k]->check;
                        $id_doc = $dias[$j][$k]->id_documento;
                        $estado = $dias[$j][$k]->id_estado;
                        break;
                   }
                }  
            }             
            

            array_push($datosA,
                array(
                    'dia' => $diasCambioProyecto[$i],
                    'pryID' => $proyectoID[$i],
                    'pryNombre' => $proyectoNombre[$i],
                    'tipo' => $tipoDia[$i],
                    'incidencia' => $incidencias[$i],
                    'prefijoPRY' => $proyectoPre[$i],
                    'check' => $check,
                    'doc' => $id_doc,
                    'estado' => $estado,
                    'exist' => $exist
                )
                );   
        }

       // dd($datosA);
    
        $canonVehiculo = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placa)
                    ->value('valor_contrato');

        $pryUserCliente = DB::table('gop_proyectos')
                    ->where('prefijo_db',$proyectoUser)
                    ->value('nombre');

        //Consulta arrendamientos actual
        $documentoActualEstado = DB::connection('sqlsrvCxParque')
                            ->table('tra_arrendamiento')
                            ->where('placa',$placa)
                            ->where('mes',$month)
                            ->where('anio',$arreglo[1])
                            ->where('prefijo',$proyectoUser)
                            ->value('id_estado');

        if($documentoActualEstado == "" || $documentoActualEstado == "NULL")
            $documentoActualEstado = "E1";


        if($canonVehiculo == "" || $canonVehiculo == "0"  || $canonVehiculo == NULL)
            $canonVehiculo = 1;

      
        return view("proyectos.transporte.costos.arrendamiento.index", 
            array(  "dia1" => $primerDia,
                    "ultimoD" => $ultimoDia, 
                    "anos" =>  $arreglo, 
                    "mesA" => $month, 
                    "diaA" => $dia,
                    "mesAD" => $mes, 
                    "placa" => $placa,
                    "canon" => $canonVehiculo,
                    "canonActual" => $canonActual,
                    "preUser" => $proyectoUser,
                    "preNombreUser" => $pryUserCliente,
                    "arrendamiento" => $arrendamiento,
                    "acceso" => $permisoArrendamiento,
                    "imprimir" => $imprimirArrendamiento,
                    "estado_documento" => $documentoActualEstado,
                    "datos" => $datosA));
    }

    //Función encargado de mostrar los vehículas que tuvieron relación o estan en mi proyecto
    public function consulta($proyectoUser = "", $cCosto = '')
    {
        if(!Session::has('user_login')) 
            return Redirect::to('http://162.248.55.204:8090/campro/');

        
        $idusu =  Session::get("user_login");
        
        
        $sql="select 
                        p.prefijo_db,p.nombre
                from 
                    Campro.dbo.sis_usuarios_proyectos as up inner join
                    Campro.dbo.gop_proyectos as p on (up.id_proyecto=p.id_proyecto)
                where 
                        up.id_usuario='".$idusu."' and p.prefijo_db is not null and p.prefijo_db <> ''
                group by 
                    p.prefijo_db,p.nombre ";
        
        $proyectospermi=array();
        /*
        try{
            $consultaEliminado = DB::connection('sqlsrv')->select("SET NOCOUNT ON;" . $sql);
            foreach($consultaEliminado as $result){
                array_push($proyectospermi, $result->prefijo_db);
            }         
        }catch (\PDOException $e) { $proyectospermi = array(); }*/
        
        
        $permisoArrendamiento = self::consultaAcceso('OP090');

        if($permisoArrendamiento == "N")
            return view('errors.nopermiso');   

        ini_set('memory_limit', '-1');
        set_time_limit(0);
        
        $imprimirArrendamiento = self::consultaAcceso('OP092');
        $confirmarArrendamiento = self::consultaAcceso('OP180');

        $perfilTransporte = self::consultaAcceso('OP098');

        $arr = explode("-",$this->fechaShort);
        $month=$arr[1];
        $year=$arr[0];
        $dia = $arr[2];

        $ultimoDia =  date("d",(mktime(0,0,0,$month+1,1,$year)-1));
        
        $arreglo = Array();
        array_push($arreglo,$year + 1);
        array_push($arreglo,$year);
        array_push($arreglo,$year - 1);
        array_push($arreglo,$year - 2);

        $fecha1 = $arreglo[1] . "-" . $month . "-" . 1;
        $fecha2 = $arreglo[1] . "-" . $month . "-" . $ultimoDia;

        $placa_arrendamiento = [];
        $tipoVehi = [];
        $proyecto = [];
        $estadoDoc = [];

        $registros = [];

        $registrosDocGenerados = [];

        $mesPasado = intval($month) - 1;
        $anioActual = $arreglo[1];
        $anioPasado = ($mesPasado == 0 ? intval($anioActual) - 1 : $anioActual);
        $mesPasado = ($mesPasado == 0 ? 12 : $mesPasado);

        $mesActualText = "";
        switch ($month) {
            case 1:
                $mesActualText = "Ene";
            break;
            case 2:
                $mesActualText = "Feb";
            break;
            case 3:
                $mesActualText = "Mar";
            break;
            case 4:
                $mesActualText = "Abr";
            break;
            case 5:
                $mesActualText = "May";
            break;
            case 6:
                $mesActualText = "Jun";
            break;
            case 7:
                $mesActualText = "Jul";
            break;
            case 8:
                $mesActualText = "Ago";
            break;
            case 9:
                $mesActualText = "Sep";
            break;
            case 10:
                $mesActualText = "Oct";
            break;
            case 11:
                $mesActualText = "Nov";
            break;
            case 12:
                $mesActualText = "Dic";
            break;
        }

        $mesPasadoText = "";
        $ultimoDiaMesPasado =  date("d",(mktime(0,0,0,$mesPasado+1,1,$anioPasado)-1));
        $iniciCuenta = 26;


        switch ($mesPasado) {
            case 1:
                $mesPasadoText = "Ene";
            break;
            case 2:
                $mesPasadoText = "Feb";
            break;
            case 3:
                $mesPasadoText = "Mar";
            break;
            case 4:
                $mesPasadoText = "Abr";
            break;
            case 5:
                $mesPasadoText = "May";
            break;
            case 6:
                $mesPasadoText = "Jun";
            break;
            case 7:
                $mesPasadoText = "Jul";
            break;
            case 8:
                $mesPasadoText = "Ago";
            break;
            case 9:
                $mesPasadoText = "Sep";
            break;
            case 10:
                $mesPasadoText = "Oct";
            break;
            case 11:
                $mesPasadoText = "Nov";
            break;
            case 12:
                $mesPasadoText = "Dic";
            break;
        }

        $dia2 = $ultimoDiaMesPasado - 1;
        $dia3 = $ultimoDiaMesPasado - 2;
        $dia4 = $ultimoDiaMesPasado - 3;
        $dia5 = $ultimoDiaMesPasado - 4;

        if($perfilTransporte == "W")
        {

            $proyectoUser = "";
            //Ingreso una persona de transporte
            $tipoVehi =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/
  
             /*
            $proyecto =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_contratantes')
                    ->orderBy('nombre')
                    ->where('prefijo','<>','NULL')
                   // ->whereIn('prefijo',$proyectospermi)
                    ->where('prefijo','<>','')
                    ->lists('nombre','prefijo');
            */
            
            $proyecto = DB::connection('sqlsrvCxParque')
                    ->table('tra_contratantes')
                    ->select('prefijo','nombre','ceco')
                    ->where('prefijo','<>','NULL')
                   // ->whereIn('prefijo',$proyectospermi)
                    ->where('prefijo','<>','')
                    ->orderBy('nombre','asc')->get();
            
            
            
            $estadoDoc =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_estados_documentos')
                    ->orderBy('nombre')
                    ->lists('nombre','id');/*option , value*/

            Session::put("proyecto_user","");
            
            if(Session::has("arre_anio") || Session::has('pry_arrendamiento'))
            {
                if(Session::has('pry_arrendamiento'))
                {
                    Session::put("arre_proyecto",Session::get('pry_arrendamiento'));
                    Session::forget('pry_arrendamiento');
                }
                
                $month = Session::get("arre_mes");
                $anio = Session::get("arre_anio");

                $ultimoDia =  date("d",(mktime(0,0,0,$month+1,1,$year)-1));
        
                $arreglo = Array();
                array_push($arreglo,$year + 1);
                array_push($arreglo,$year);
                array_push($arreglo,$year - 1);
                array_push($arreglo,$year - 2);

                $fecha1 = $arreglo[1] . "-" . $month . "-" . 1;
                $fecha2 = $arreglo[1] . "-" . $month . "-" . $ultimoDia;

                $mesPasado = intval($month) - 1;
                $anioActual = $anio;
                $anioPasado = ($mesPasado == 0 ? intval($anioActual) - 1 : $anioActual);
                $mesPasado = ($mesPasado == 0 ? 12 : $mesPasado);

                $mesActualText = "";
                switch ($month) {
                    case 1:
                        $mesActualText = "Ene";
                    break;
                    case 2:
                        $mesActualText = "Feb";
                    break;
                    case 3:
                        $mesActualText = "Mar";
                    break;
                    case 4:
                        $mesActualText = "Abr";
                    break;
                    case 5:
                        $mesActualText = "May";
                    break;
                    case 6:
                        $mesActualText = "Jun";
                    break;
                    case 7:
                        $mesActualText = "Jul";
                    break;
                    case 8:
                        $mesActualText = "Ago";
                    break;
                    case 9:
                        $mesActualText = "Sep";
                    break;
                    case 10:
                        $mesActualText = "Oct";
                    break;
                    case 11:
                        $mesActualText = "Nov";
                    break;
                    case 12:
                        $mesActualText = "Dic";
                    break;
                }

                $mesPasadoText = "";
                $ultimoDiaMesPasado =  date("d",(mktime(0,0,0,$mesPasado+1,1,$anioPasado)-1));
                $iniciCuenta = 26;


                switch ($mesPasado) {
                    case 1:
                        $mesPasadoText = "Ene";
                    break;
                    case 2:
                        $mesPasadoText = "Feb";
                    break;
                    case 3:
                        $mesPasadoText = "Mar";
                    break;
                    case 4:
                        $mesPasadoText = "Abr";
                    break;
                    case 5:
                        $mesPasadoText = "May";
                    break;
                    case 6:
                        $mesPasadoText = "Jun";
                    break;
                    case 7:
                        $mesPasadoText = "Jul";
                    break;
                    case 8:
                        $mesPasadoText = "Ago";
                    break;
                    case 9:
                        $mesPasadoText = "Sep";
                    break;
                    case 10:
                        $mesPasadoText = "Oct";
                    break;
                    case 11:
                        $mesPasadoText = "Nov";
                    break;
                    case 12:
                        $mesPasadoText = "Dic";
                    break;
                }



                     $tipoVe =  '';
                $marcaVe =  '';
                $modeloVe =  '';
                $contratoVe =  '';
                $finVe =  '';
                $activoVe =  '';
                $pry =  Session::get("arre_proyecto");
                $estado =  '';
                $placa =  '';

                $cad = "EXEC sp_tra_consulta_vehiculos_arrendamiento_perfil_transporte '$pry','$month','$anio','$mesPasado','$anioPasado','$tipoVe','$marcaVe','$modeloVe','$contratoVe','$finVe','$contratoVe','$estado','$placa';";

                try{
                $placa_arrendamiento = DB::connection('sqlsrvCxParque')
                                ->select("SET NOCOUNT ON;" . $cad);
                
                 } catch (\PDOException $e) {
            # do something or render a custom error page
            $placa_arrendamiento = array();
        }
                
                        //->statement("SET NOCOUNT ON;" . $cad);
                            //    ->raw("SET NOCOUNT ON;" . $cad);
                                //->select("SET NOCOUNT ON;" . $cad);

                for ($indexArren=0; $indexArren < count($placa_arrendamiento); $indexArren++) { 
                    
                    //Vehiculos en estado retirado actualimente
                    if($placa_arrendamiento[$indexArren]->estado == "E02")
                    {
                        $sqlDatos = "select pry_ubi.dia,pry_ubi.mes,pry_ubi.anio
                                    from tra_vehiculo_ubicacion_proyecto as pry_ubi
                                    where 
                                    ((pry_ubi.mes = $month  and pry_ubi.anio = $anio AND pry_ubi.dia < 26 )
                                    OR (pry_ubi.mes = $mesPasado  and pry_ubi.anio = $anioPasado AND pry_ubi.dia > 25 ))
                                    AND pry_ubi.placa = '" . $placa_arrendamiento[$indexArren]->placa . "'
                                    AND pry_ubi.id_estado <> 'E02'";

                        $consultaEliminado = DB::connection('sqlsrvCxParque')
                                            ->select("SET NOCOUNT ON;" . $sqlDatos);

                        if(count($consultaEliminado) == 0)
                        {
                            $placa_arrendamiento[$indexArren]->mostrar = "0";
                            $placa_arrendamiento[$indexArren]->estadoDes = $placa_arrendamiento[$indexArren]->estadoDes . " - CON DÍAS TRABAJADOS EN EL PERÍODO SELECCIONADO";
                        }
                        else
                        {
                            $placa_arrendamiento[$indexArren]->mostrar = "1";
                            $placa_arrendamiento[$indexArren]->estadoDes = $placa_arrendamiento[$indexArren]->estadoDes . " - CON DÍAS TRABAJADOS EN EL PERÍODO SELECCIONADO";
                            
                        }
                    }
                    else
                        $placa_arrendamiento[$indexArren]->mostrar = "2";

                }
                $k=0;
                for ($indexArren=0; $indexArren < count($placa_arrendamiento); $indexArren++) {
                    if($placa_arrendamiento[$indexArren]->mostrar==1 ||$placa_arrendamiento[$indexArren]->mostrar==2){

                        $placa_arrendamiento[$indexArren]->dia1 = '1';
                        $placa_arrendamiento[$indexArren]->dia2 = '1';
                        $placa_arrendamiento[$indexArren]->dia3 = '1';
                        $placa_arrendamiento[$indexArren]->dia4 = '1';
                        $placa_arrendamiento[$indexArren]->dia5 = '1';
                        $placa_arrendamiento[$indexArren]->dia6 = '1';
                        $placa_arrendamiento[$indexArren]->dia7 = '1';
                        $placa_arrendamiento[$indexArren]->dia8 = '1';
                        $placa_arrendamiento[$indexArren]->dia9 = '1';
                        $placa_arrendamiento[$indexArren]->dia10 = '1';
                        $placa_arrendamiento[$indexArren]->dia11 = '1';
                        $placa_arrendamiento[$indexArren]->dia12 = '1';
                        $placa_arrendamiento[$indexArren]->dia13 = '1';
                        $placa_arrendamiento[$indexArren]->dia14 = '1';
                        $placa_arrendamiento[$indexArren]->dia15 = '1';
                        $placa_arrendamiento[$indexArren]->dia16 = '1';
                        $placa_arrendamiento[$indexArren]->dia17 = '1';
                        $placa_arrendamiento[$indexArren]->dia18 = '1';
                        $placa_arrendamiento[$indexArren]->dia19 = '1';
                        $placa_arrendamiento[$indexArren]->dia20 = '1';
                        $placa_arrendamiento[$indexArren]->dia21 = '1';
                        $placa_arrendamiento[$indexArren]->dia22 = '1';
                        $placa_arrendamiento[$indexArren]->dia23 = '1';
                        $placa_arrendamiento[$indexArren]->dia24 = '1';
                        $placa_arrendamiento[$indexArren]->dia25 = '1';
                        $placa_arrendamiento[$indexArren]->dia26 = '1';
                        $placa_arrendamiento[$indexArren]->dia27 = '1';
                        $placa_arrendamiento[$indexArren]->dia28 = '1';
                        $placa_arrendamiento[$indexArren]->dia29 = '1';
                        $placa_arrendamiento[$indexArren]->dia30 = '1';
                        $placa_arrendamiento[$indexArren]->dia31 = '1';

                        // dd();
                    }
                }
                //dd($placa_arrendamiento1);
                //dd($placa_arrendamiento);
                $sql = "
                SELECT histo.dia,histo.incidencia,histo.placa
                FROM 
                tra_vehiculo_ubicacion_proyecto as histo
                INNER JOIN tra_contratantes as contra ON histo.id_proyecto = contra.id
                WHERE 
                  ((mes = '$month' and anio = '" . $anio . "' and dia < 26)
                  OR (mes = '$mesPasado' and anio = '" . $anioPasado . "' and dia > 25))
                  AND prefijo = '$pry' AND incidencia IS NOT NULL
                ORDER BY histo.placa, histo.dia
            ";

            $registros =  DB::connection('sqlsrvCxParque')
                            ->select("SET NOCOUNT ON;" . $sql);

            $registrosDocGenerados = DB::connection('sqlsrvCxParque')
                                        ->table('tra_arrendamiento as arrend')
                                        ->join('tra_detalle_arrendamiento as det','det.id_documento','=','arrend.id_documento')
                                        ->where('arrend.anio',$anio)
                                        ->where('arrend.mes',$month)
                                        ->where('arrend.prefijo',$pry)
                                        ->orderBy('arrend.placa')
                                        ->orderBy('det.dia')
                                        ->get(['arrend.placa','det.dia','det.check',"det.observacion as obser",'det.archivo','det.id_documento']);

            // for ($i=1; $i < 31; $i++) { 
            //    foreach ($registrosDocGenerados as $valor) {
            //         if($i == $valor->dia){

            //         }else{
            //             if($i>=1 || $i<=25){          
            //             $registrosGener = DB::connection('sqlsrvCxParque')
            //                                 ->table("tra_detalle_arrendamiento")
            //                                 ->where('id_documento',$valor->id_documento)
            //                                 ->where('prefijo',$pry)
            //                                 ->where('anio',$anio)
            //                                 ->where('mes',$month)
            //                                 ->where('dia',$i)
            //                                 ->count();
            //             if($registrosGener==0){
            //                 DB::connection('sqlsrvCxParque')
            //                 ->table('tra_detalle_arrendamiento')
            //                 ->insert(
            //                     array(
            //                             array(
            //                                 'id_documento' => $valor->id_documento,
            //                                 'anio' => $anio,
            //                                 'mes' => $month,
            //                                 'dia' => $i,
            //                                 'prefijo' => $pry,
            //                                 'check' => 0,
            //                                 'observacion' => ""
            //                                 )
            //                         ));
            //             }
            //             }else{
            //                 $registrosGener = DB::connection('sqlsrvCxParque')
            //                                 ->table("tra_detalle_arrendamiento")
            //                                 ->where('id_documento',$valor->id_documento)
            //                                 ->where('prefijo',$pry)
            //                                 ->where('anio',$anioPasado)
            //                                 ->where('mes',$mesPasado)
            //                                 ->where('dia',$i)
            //                                 ->count();
            //             if($registrosGener==0){
            //                 DB::connection('sqlsrvCxParque')
            //                 ->table('tra_detalle_arrendamiento')
            //                 ->insert(
            //                     array(
            //                             array(
            //                                 'id_documento' => $valor->id_documento,
            //                                 'anio' => $anioPasado,
            //                                 'mes' => $mesPasado,
            //                                 'dia' => $i,
            //                                 'prefijo' => $pry,
            //                                 'check' => 0,
            //                                 'observacion' => ""
            //                                 )
            //                         )); 
            //             }
            //             }

            //         }
            //    }
            // }

            $registrosDocGenerados = DB::connection('sqlsrvCxParque')
                                        ->table('tra_arrendamiento as arrend')
                                        ->join('tra_detalle_arrendamiento as det','det.id_documento','=','arrend.id_documento')
                                        ->where('arrend.anio',$anio)
                                        ->where('arrend.mes',$month)
                                        ->where('arrend.prefijo',$pry)
                                        ->orderBy('arrend.placa')
                                        ->orderBy('det.dia')
                                        ->get(['arrend.placa','det.dia','det.check',"det.observacion as obser",'det.archivo','det.id_documento']);

                $proyectoUser = $pry;
            }
        }
        else
        {
            Session::put("proyecto_user",$proyectoUser);

            $cad = "EXEC sp_tra_consulta_vehiculos_arrendamiento '$proyectoUser','$month','$year','$mesPasado','$anioPasado';";

            $placa_arrendamiento = DB::connection('sqlsrvCxParque')
                                ->select("SET NOCOUNT ON;" . $cad);
            

            $sql = "
                SELECT histo.dia,histo.incidencia,histo.placa
                FROM 
                tra_vehiculo_ubicacion_proyecto as histo
                INNER JOIN tra_contratantes as contra ON histo.id_proyecto = contra.id
                WHERE 
                  ((mes = '$month' and anio = '" . $arreglo[1] . "' and dia < 26)
                  OR (mes = '$mesPasado' and anio = '" . $anioPasado . "' and dia > 25))
                  AND prefijo = '$proyectoUser' AND incidencia IS NOT NULL
                ORDER BY histo.placa, histo.dia
            ";

            $registros =  DB::connection('sqlsrvCxParque')
                            ->select("SET NOCOUNT ON;" . $sql);

            $registrosDocGenerados = DB::connection('sqlsrvCxParque')
                                        ->table('tra_arrendamiento as arrend')
                                        ->join('tra_detalle_arrendamiento as det','det.id_documento','=','arrend.id_documento')
                                        ->where('arrend.anio',$arreglo[1])
                                        ->where('arrend.mes',$month)
                                        ->where('arrend.prefijo',$proyectoUser)
                                        ->orderBy('arrend.placa')
                                        ->orderBy('det.dia')
                                        ->get(['arrend.placa','det.dia','det.check',"det.observacion as obser",'det.archivo','arrend.solped']);

        }
        
    //    $carrendamiento = tra_arrendamiento_concepto_descuento

        
          $conceptodes= DB::connection('sqlsrvCxParque')
                                        ->table('tra_arrendamiento_concepto_descuento')
                                        ->orderBy('descripcion')
                                        ->get(['id','descripcion']);
        
        
        //dd($registrosDocGenerados);

        $diaActual = intval($arr[2]);

        $ultimoDia = 30;
        $ultimoDiaMesPasado = 30;
        $aServicios =  DB::connection('sqlsrvCxParque')->table('tra_servicios')->get();

       $contratosSap = DB::connection('sqlsrvCxParque')
       ->table('tra_contratos_cab')
       ->lists('numero_contrato');

       // $permisos = DB::select("sis_perfiles_opciones")
       //              ->join("sis_usuarios","sis_perfiles_opciones.id_perfil","=","sis_usuarios.id_usuario")
       //              ->where("sis_perfiles_opciones","OP180")
       //              ->first();
       //  if($permisos){
       //      $permiso = $permisos[0]->nivel_acceso;
       //  }else{
       //      $permiso = "N";
       //  }
       // var_dump($month);
       // var_dump($mesPasado);
       // var_dump($anioPasado);
       // var_dump($year);

        return view("proyectos.transporte.costos.arrendamiento.arrendamientos", 
        array(  
                "contratosSap"=>$contratosSap,
                "proyectospermi" => $proyectospermi,
                "conceptodes" => $conceptodes,
                "data" => $placa_arrendamiento,
                "pry" => $proyectoUser,
                "permisoAcceso" => $permisoArrendamiento,
                "permisoImprimir" => $imprimirArrendamiento,
                "permisoConfirmar" => $confirmarArrendamiento,
                "perfilTransporte" => $perfilTransporte,
                "tipoM" => $tipoVehi,
                "proy" => $proyecto,
                "estadoM" => $estadoDoc,
                "mes" => $month,
                "mesPasado" => $mesPasado,
                "ultima_dia" => $ultimoDia,
                "registros" => $registros,
                "registrosDocGenerados" => $registrosDocGenerados,
                "iniCuenta" => $iniciCuenta,
                "ultimaDia" => $ultimoDiaMesPasado,
                "mes1Des" => $mesActualText,
                "mes2Des" => $mesPasadoText,
                "diaHoy" => $diaActual,
                "anioPasado" => $anioPasado,
                "anio" => $year,
                "cCostoParam" => $cCosto,
                "aServicios" => $aServicios
              ));
    }

    //Filtro encargado de mostrar los vehículas que tuvieron relación o estan en mi proyecto
    public function filterconsulta(Request $request)
    {
        
        Session::put('arre_anio',$request->all()["txt_anio"]);
        Session::put('arre_mes',$request->all()["txt_mes"]);
        Session::put('arre_proyecto',$request->all()["arre_proyecto"]);
        return Redirect::to('transporte/costos/arrendamientos');
    }

    /******** FIN GENERAR DOCUMENTOS DE ARRENDAMIENTO **************/
    /********************************************************/
    
    

    /********************************************************/
    /******** DOCUMENTOS DE ARRENDAMIENTO - AR **************/

    //Función encargada de mostrar un documento en específico y su detalle
    public function consultadocumentos($doc = "")
    {
        if(!Session::has('user_login'))
            return Redirect::to('http://162.248.55.204:8090/campro/');

        $permisoArrendamiento = self::consultaAcceso('OP090');

        if($permisoArrendamiento == "N")
            return view('errors.nopermiso');   

        $imprimirArrendamiento = self::consultaAcceso('OP092');
        $confirmarArrendamiento = self::consultaAcceso('OP093');
        $anulaArrendamiento = self::consultaAcceso('OP094');
        $abrirArrendamiento = self::consultaAcceso('OP097');
        $editarArrendamiento = self::consultaAcceso('OP091');

        $arr = explode("-",$this->fechaShort);
        $month=$arr[1];
        $year=$arr[0];
        $dia = $arr[2];

         //Consulta arrendamientos toda la información
        $arrendamiento = DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento as arren')
                        ->where('id_documento',$doc)
                        ->leftJoin('CAMPRO.dbo.sis_usuarios as user1','user1.id_usuario','=','usuario_crea')
                        ->leftJoin('CAMPRO.dbo.sis_usuarios as user2','user2.id_usuario','=','usuario_anula')
                        ->leftJoin('CAMPRO.dbo.sis_usuarios as user3','user3.id_usuario','=','usuario_ultima_actualizacion')
                        ->leftJoin('CAMPRO.dbo.sis_usuarios as user4','user4.id_usuario','=','usuario_confirma')
                        ->leftJoin('CAMPRO.dbo.gop_proyectos as pry','pry.prefijo_db','=','prefijo')
                        ->get(['id_documento','canon_actual','placa','arren.id_estado','cantidad_dias','total_pagar'
                            ,'arren.fecha_creacion','arren.fecha_anula','arren.fecha_ultima_actualizacion',
                            'user1.propietario as crea_user','user2.propietario as anula_user','user3.propietario as anula_actualiza',
                            'pry.nombre','arren.mes','arren.anio','pry.prefijo_db'
                            ,'user4.propietario as user_confirma','fecha_confirmacion'])[0];

        //dd($arrendamiento);

        $dias = DB::connection('sqlsrvCxParque')
                ->table('tra_detalle_arrendamiento')
                ->where('id_documento',$doc)
                ->orderBy('mes')
                ->orderBy('dia')
                ->get(['dia','check','mes']);

        $mes = "";
        switch ($month) {
            case 1:
                $mes = "ENERO";
            break;
            case 2:
                $mes = "FEBRERO";
            break;
            case 3:
                $mes = "MARZO";
            break;
            case 4:
                $mes = "ABRIL";
            break;
            case 5:
                $mes = "MAYO";
            break;
            case 6:
                $mes = "JUNIO";
            break;
            case 7:
                $mes = "JULIO";
            break;
            case 8:
                $mes = "AGOSTO";
            break;
            case 9:
                $mes = "SEPTIEMBRE";
            break;
            case 10:
                $mes = "OCTUBRE";
            break;
            case 11:
                $mes = "NOVIEMBRE";
            break;
            case 12:
                $mes = "DICIEMBRE";
            break;

        }

        return view("proyectos.transporte.costos.arrendamiento.arrendamientodocumento", 
            array(  "data" => $arrendamiento,
                    "dias" => $dias,
                    "mes" => $mes,
                    "mesActualNum" => $month,
                    "acceso" => $permisoArrendamiento,
                    "imprimir" => $imprimirArrendamiento,
                    "confirmar" => $confirmarArrendamiento,
                    "anular" => $anulaArrendamiento,
                    "editar" => $editarArrendamiento,
                    "abrir" => $abrirArrendamiento ));
    }

    //Función encargada de mostrar el reporte de documentos
    public function consultadocumentosReporte()
    {

        if(!Session::has('user_login'))
            return Redirect::to('http://162.248.55.204:8090/campro/');

        $perfilTransporte = self::consultaAcceso('OP098');

        if($perfilTransporte != "W")
            return view('errors.nopermiso');  

        $tipoVehi =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/
  
        $proyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->where('prefijo','<>','NULL')
                ->where('prefijo','<>','')
                ->lists('nombre','prefijo');/*option , value*/

        $estadoDoc =
            DB::connection('sqlsrvCxParque')
                ->table('tra_estados_documentos')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/

        $proveedores =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/


        $imprimirArrendamiento = self::consultaAcceso('OP092');

        $arr = explode("-",$this->fechaShort);
        $month=$arr[1];
        $year=$arr[0];

        $datos = [];

        if(Session::has('arre_anio_ar'))
        {
            $month=Session::get('arre_mes_ar');
            $year=Session::get('arre_anio_ar');            

            $datos = DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro as veh')
                        ->join('tra_arrendamiento as arren','veh.placa','=','arren.placa')
                        ->join('tra_contratantes as pry','pry.id','=','veh.id_proyecto')
                        ->join('tra_tipo_vehiculo as tipo','tipo.id_tipo_vehiculo','=','veh.id_tipo_vehiculo')
                        ->join('tra_estados_documentos as estado','estado.id','=','arren.id_estado')                        
                        ->join('tra_estados as estadoVehi','estadoVehi.id_estado','=','veh.id_estado')
                        ->leftjoin('tra_tiposvehiculo_cam as tipocam','tipocam.id','=','veh.id_tipo_vehiculo_cam')
                        ->select('estadoVehi.nombre as estadoDes','estado.nombre as estadoDoc','pry.nombre as proyecto','veh.placa','tipo.nombre as tipoV','tipocam.nombre as tipoCAM'
                            ,'arren.id_documento','estadoVehi.id_estado as estado','arren.cantidad_dias','arren.canon_actual as canon','arren.total_pagar','arren.fecha_creacion as fecha');

            if(Session::get('txt_doc_filter_ar') != "")
            {
                 $datos =  $datos->where('arren.id_documento','LIKE','%' . Session::get('txt_doc_filter_ar') . '%');
            }
            else
            {
                if(Session::get('txt_placa_filter_ar') != "")
                {
                    $datos =  $datos
                            ->where('arren.mes', Session::get('arre_mes_ar'))
                            ->where('arren.anio', Session::get('arre_anio_ar'))
                            ->where('veh.placa' ,'LIKE','%' . Session::get('txt_placa_filter_ar') . '%');
                }
                else
                {
                    $datos =  $datos
                            ->where('arren.mes', Session::get('arre_mes_ar'))
                            ->where('arren.anio', Session::get('arre_anio_ar'))
                            ->where('tipo.id_tipo_vehiculo','LIKE','%' . Session::get('arre_tipo_veh_ar') . '%')
                            ->where('arren.id_estado','LIKE','%' . Session::get('arre_estado_ar') . '%')
                            ->where('pry.prefijo','LIKE','%' . Session::get('arre_proyecto_ar') . '%');
                }
            }

            $datos = $datos
                    ->orderBy('pry.nombre')
                    ->get();
        }

        return view("proyectos.transporte.costos.arrendamiento.consultaArrendamientos", 
            array(  
                "data" => $datos,
                "permisoImprimir" => $imprimirArrendamiento,
                "tipoM" => $tipoVehi,
                "proy" => $proyecto,
                "estadoM" => $estadoDoc,
                "proveedores" => $proveedores,
                "mes" => $month,
                "anio" => $year));
    }

    //Función encargada de realizar el filtro para mostrar el reporte de documentos
    public function consultadocumentosReporteFilter(Request $request)
    {
        Session::put('arre_anio_ar',$request->all()["txt_anio"]);
        Session::put('arre_mes_ar',$request->all()["txt_mes"]);
        Session::put('arre_tipo_veh_ar',$request->all()["arre_tipo_veh_ar"]);
        Session::put('arre_proyecto_ar',$request->all()["arre_proyecto_ar"]);
        Session::put('arre_estado_ar',$request->all()["arre_estado_ar"]);
        Session::put('txt_placa_filter_ar',$request->all()["txt_placa_filter_ar"]);
        Session::put('txt_doc_filter_ar',$request->all()["txt_doc_filter_ar"]);
        return Redirect::to('transporte/costos/consultaArrendamientos');
    }

    //

    //Función encargada de imprimir los documentos de arrendamiento
    public function imprimirdocumentos(Request $request)
    {
        $permisoArrendamiento = self::consultaAcceso('OP090');

        if($permisoArrendamiento == "N")
            return view('errors.nopermiso');   

        $doc = $request->all()["documento"];

         //Consulta arrendamientos toda la información
        $arrendamiento = DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento as arren')
                        ->where('id_documento',$doc)
                        ->join('tra_maestro as veh','arren.placa','=','veh.placa')
                        ->join('CAMPRO.dbo.sis_usuarios as user1','user1.id_usuario','=','usuario_crea')
                        ->leftJoin('CAMPRO.dbo.sis_usuarios as user2','user2.id_usuario','=','usuario_anula')
                        ->join('CAMPRO.dbo.sis_usuarios as user3','user3.id_usuario','=','usuario_ultima_actualizacion')
                        ->leftJoin('CAMPRO.dbo.gop_proyectos as pry','pry.prefijo_db','=','prefijo')
                        
                        ->join('tra_tipo_vehiculo as tipo','tipo.id_tipo_vehiculo','=','veh.id_tipo_vehiculo')
                        ->join('tra_marcas as marca','marca.id_marca','=','veh.id_marca')

                        ->leftJoin('tra_propietarios as propietarios','propietarios.id_propietario','=','veh.id_propietario')

                        ->get(['id_documento','canon_actual','veh.placa','arren.id_estado','cantidad_dias','total_pagar'
                            ,'arren.fecha_creacion','arren.fecha_anula','arren.fecha_ultima_actualizacion',
                            'user1.propietario as crea_user','user2.propietario as anula_user','user3.propietario as anula_actualiza',
                            'pry.nombre','arren.mes','arren.anio','pry.prefijo_db',
                            'tipo.nombre AS tipo_vehiculo','marca.nombre as marca','tipo.nombre_cam','propietarios.nombre as proveedor'])[0];



        $dias = DB::connection('sqlsrvCxParque')
                ->table('tra_detalle_arrendamiento')
                ->where('id_documento',$doc)
                ->orderBy('mes')
                ->orderBy('dia')
                ->get(['dia','check','mes','observacion','anio']);

        $usuario = DB::Table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    ->value('propietario');

        self::saveLog('OPERA26',$doc,"");
        //$dat = DB::connection('sqlsrvCxParque')->select($consulta)[0];
        $view =  \View::make('proyectos.pdf.transporte.documentoarrendamiento', 
                array('doc' => $doc,
                        'user' => $usuario,
                         "data" => $arrendamiento,
                         "fecha" => $this->fechaALong,
                         "dias" => $dias))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        
        return $pdf->download('Documento de arrendamiento N°' . $doc . '.pdf');
    }


    public function imprimirdocumentosMasivo(Request $request)
    {
        $fecha1 = $request->all()["fecha1"];
        $fecha2 = $request->all()["fecha2"];
        $Cbo_proveedores = $request->all()["Cbo_proveedores"];

        $usuario = DB::Table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    ->value('propietario');

        $data = [];

        $view =  \View::make('proyectos.pdf.transporte.pdfMasivoProveedores', 
                array(  'fecha1' => $fecha1,
                        'fecha2' => $fecha2,
                        'user' => $usuario,
                        "data" => $data,
                        "fecha" => $this->fechaALong))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        
        return $pdf->download('Masivo de documentos AR.pdf');


    }

    /******** FIN DOCUMENTOS DE ARRENDAMIENTO - AR **************/
    /************************************************************/
    


    /*****************************************************************************/
    /******** WEB SERVICES CAMPRO WEB ARRENADAMIENTOS - GUARDAR **************/
    /*****************************************************************************/
    public function consultarServicio(Request $request){
        $ser = $request->all()["servicio"];
        $servicio =
        DB::connection('sqlsrvCxParque')
            ->table('tra_servicios')
            ->where('numero_servicio',"=",$ser)
            ->first();
            return response()->json($servicio); 
    }
    public function consultarContrato(Request $request){
        $con = $request->all()["contrato"];
        $contrato =
        DB::connection('sqlsrvCxParque')
            ->table('tra_contratos_cab')
            ->join("tra_contratos_det","tra_contratos_det.numero_contrato","=","tra_contratos_cab.numero_contrato")
            ->where('tra_contratos_cab.numero_contrato',"=",$con)
            ->first();
            return response()->json($contrato); 
    }

    public function consultarDocumentosSolped(Request $request){
        $usuario = DB::table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    ->value('propietario');
            
            $doc = $request->all()["doc"][0];
            $result    = explode('-', $this->fechaShort);
            $fechaDeliv = implode("", $result);
            Session::flash('pry_arrendamiento',$request->all()["pry"]);
            Session::flash("arre_mes",$request->all()["mes"]);
            Session::flash("arre_anio",$request->all()["anio"]);

            // for ($i=0; $i < count($doc); $i++) { 
                $maestro = DB::connection('sqlsrvCxParque')
                                ->table('tra_maestro')
                                ->join('tra_arrendamiento','tra_maestro.placa','=','tra_arrendamiento.placa')
                                ->join('tra_propietarios','tra_propietarios.id_propietario','=','tra_maestro.id_propietario')
                                ->join('tra_contratantes','tra_contratantes.ceco','=','tra_arrendamiento.prefijo')
                                ->where('tra_arrendamiento.id_documento',$doc)
                                ->select('tra_contratantes.grupo_compras','tra_contratantes.codigo_pep','tra_contratantes.id_estado as contratanteEstado',
                                'tra_arrendamiento.total_pagar','tra_arrendamiento.id_documento','tra_maestro.centro_logistico','tra_maestro.sociedad',
                                'tra_maestro.numero_activo_fijo','tra_maestro.subnumero_activo_fijo',
                                'tra_propietarios.cuenta_proveedor','tra_propietarios.org_compras',
                                'tra_propietarios.id_estado as propietarioEstado','tra_maestro.elemento_pep')
                                ->first();
                $elementopep = null;
                if($maestro->codigo_pep){
                    $elementopep = DB::table('elementos_pep')
                                    ->select('elementos_pep.*')
                                    ->where('codigo_pep', $maestro->codigo_pep)
                                    ->first();
                }
                 
                // $tServicio =   DB::connection('sqlsrvCxParque')
                // ->table('tra_servicios')                                  
                // ->where('numero_servicio',$docSer)
                // ->select('grupo_articulos','numero_servicio','descripcion')
                // ->first();  
            // }

            $respuesta = array(
                "usuario"=>$usuario,
                "maestro"=>$maestro,
                "elemento_pep"=>$elementopep
            );
            return response()->json($respuesta); //Incidencia
    }
    //  se genera la solped
    public function saveSolped(Request $request){
        // dd($request->all());
        $v = \Validator::make($request->all(), [
            'Sservicio' => 'required',
            'Smoneda' => 'required',
            'Scuenta' => 'required',
            'Scentro' => 'required',
            'SgrupoArticulos' => 'required',
            'Scantidad' => 'required',
            'Sunidad' => 'required',
            'Sorganizacion' => 'required',
            'SgrupoCompras' => 'required',
            'Sposicion' => 'required',
            'Sped' => 'required',
            'Ssociedad' => 'required',
            'SnumActivo' => 'required',
            'SsubNumActivo' => 'required',
            'Sdescripcion' => 'required',
            'Sarrendamiento' => 'required',
            'Susuario' => 'required',
            'Stotalapgar' => 'required'
        ]);

        $posicion = $request->Sposicion;
        $contrato = $request->Scontrato;
        if($request->Scontrato == ""){
            $contrato = null;
            $posicion = null;
        }
        
        if ($v->fails())
        {   
            //1 lo vamos a tomar como un error de retorno
            return response()->json("1");
            // return redirect()->back()->withInput()->withErrors($v->errors());
        }
        $result    = explode('-', $this->fechaShort);
        $fechaDeliv = implode("", $result);
        DB::connection('DBINTSERVLATD')
        ->table('TB_INT_E_MM_SOLPED_CAB')
        ->insert(
            array(
                array(
                    'TMS_INSERT' => $this->fechaALong,
                    'DOC_REFERENCIA' => $request->Sarrendamiento,
                    'USUARIO_CAM' => Session::get('user_login'),
                    'STATUS' => 1,
                    'TMS_STATUS' => $this->fechaALong
                )
            )
        );
        DB::connection('DBINTSERVLATD')
        ->table('TB_INT_E_MM_SOLPED_DET')
        ->insert(
            array(
                array(
                    'TMS_INSERT' => $this->fechaALong,
                    'DOC_REFERENCIA' => $request->Sarrendamiento,
                    'POS_REFERENCIA' => 1,
                    'FIXED_VEND' => $request->Scuenta,
                    'AGREEMENT' =>$contrato,
                    'AGMT_ITEM' =>$posicion,
                    'SHORT_TEXT' =>  $request->Sdescripcion,
                    // 'SHORT_TEXT' =>  "Solicitud de arrendamiento",
                    'DELIV_DATE' => $fechaDeliv,
                    'PLANT' => $request->Scentro,
                    'MAT_GRP' => $request->SgrupoArticulos,
                    'GR_PRICE' => $request->Stotalapgar,
                    'QUANTITY' => $request->Scantidad,
                    'UNIT' => $request->Sunidad,
                    // 'PURCH_ORG' => 3,
                    'PURCH_ORG' => $request->Sorganizacion,
                    'PUR_GROUP' => $request->SgrupoCompras,
                    'CURRENCY' => $request->Smoneda,
                    'SERVICE' => $request->Sservicio,
                    'WBS_ELEM' => $request->Sped,
                    'BUKRS' => $request->Ssociedad,
                    'ANLN1' => $request->SnumActivo,
                    'ANLN2' => $request->SsubNumActivo
                )
            )
        );
        // se responde que todo ha ido bien
        return response()->json("2");

        // dd($tServicio);
        // DB::connection('sqlsrvCxParque')
        // ->table('tra_arrendamiento')
        // ->where('id_documento',$doc[$i])
        // ->update(
        //     array(
        //             'id_estado' => 'E3',
        //             'usuario_confirma' => Session::get('user_login'),
        //             'fecha_confirmacion' => $this->fechaALong
        //         )
        //     );

        self::saveLog('OPERA28',$request->Sarrendamiento,"CONFIRMACIÓN MASIVAMENTE");
    }
    
    public function saveWS(Request $request)
    {   $retorna="1";
        $opc = $request->all()["opc"];

        if($opc == 1) //Save Planilla
        {
            $datos = $request->all()["data"];
            $mes = $request->all()["mes"];
            $anio = $request->all()["anio"];
            $placa = $request->all()["placa"];
            $canonActual = trim($request->all()["canon"]);
            $total = trim($request->all()["total"]);
            $dias = $request->all()["dias"];
            $preUser = $request->all()["preUser"];

            $consultaArrenda =  DB::connection('sqlsrvCxParque')
                                    ->table('tra_arrendamiento')
                                    ->where('placa',$placa)
                                    ->where('mes',$mes)
                                    ->where('anio',$anio)
                                    ->where('prefijo',$preUser)
                                    ->count();

            $conse = 0;
            if($consultaArrenda == 0) //Ingresa cabeza arrendamiento
            {   
                $conse = self::generaConsecutivo("ID_ARRENDAMIENTO");  
                DB::connection('sqlsrvCxParque')
                ->table('tra_arrendamiento')
                ->insert(
                    array(
                            array(
                                'id_documento' => $conse,
                                'anio' => $anio,
                                'mes' => $mes,
                                'placa' => $placa,
                                'prefijo' => $preUser,
                                'id_estado' => "E1",
                                'cantidad_dias' => $dias,
                                'canon_actual' => $canonActual,
                                'total_pagar' => $total,
                                'fecha_creacion' => $this->fechaALong,
                                'usuario_crea' => Session::get('user_login'),
                                'fecha_ultima_actualizacion' => $this->fechaALong,
                                'usuario_ultima_actualizacion' => Session::get('user_login'),
                                )
                        ));

                self::saveLog('OPERA30',$conse,"");

            }
            else //Actualiza cabeza arrendamieno
            {
                $conse = DB::connection('sqlsrvCxParque')
                                    ->table('tra_arrendamiento')
                                    ->where('placa',$placa)
                                    ->where('mes',$mes)
                                    ->where('anio',$anio)
                                    ->where('prefijo',$preUser)
                                    ->get(['id_documento','cantidad_dias','canon_actual','total_pagar'])[0];

                DB::connection('sqlsrvCxParque')
                    ->table('tra_arrendamiento')
                    ->where('id_documento',$conse->id_documento)
                    ->update(
                        array(
                                'prefijo' => $preUser,
                                'cantidad_dias' => $dias,
                                'canon_actual' => $canonActual,
                                'total_pagar' => $total,
                                'fecha_ultima_actualizacion' => $this->fechaALong,
                                'usuario_ultima_actualizacion' => Session::get('user_login'),
                                
                            ));

                self::saveLog('OPERA27',$conse->id_documento,"DATOS MODIFICADOS: Días:" . $conse->cantidad_dias . " - CANON:" . $conse->canon_actual . " - TOTAL:" . $conse->total_pagar );

                $conse = $conse->id_documento;
            }
            
            DB::connection('sqlsrvCxParque')
                    ->table('tra_detalle_arrendamiento')
                    ->where('id_documento',$conse)
                    ->where('prefijo',$preUser)
                    ->delete();

            //Guarda detalle del documento
            for ($i=0; $i < count($datos); $i++) { 
                
                $check = $datos[$i]["check"];
                $dia = $datos[$i]["dia"];
                $pre = $datos[$i]["pre"];
                
                DB::connection('sqlsrvCxParque')
                ->table('tra_detalle_arrendamiento')
                ->insert(
                    array(
                            array(
                                'id_documento' => $conse,
                                'anio' => $anio,
                                'mes' => $mes,
                                'dia' => $dia,
                                'prefijo' => $pre,
                                'check' => $check
                                )
                        ));

            }

            return response()->json($conse); //Incidencia
        }

        if($opc == 2) //Confirma documento  de arrendamiento
        {
            $doc = $request->all()["doc"];
            DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento')
                        ->where('id_documento',$doc)
                        ->update(
                            array(
                                    'id_estado' => 'E3',
                                    'usuario_confirma' => Session::get('user_login'),
                                    'fecha_confirmacion' => $this->fechaALong
                                )
                            );

            self::saveLog('OPERA28',$doc,"");
            return response()->json("1");
        }

        if($opc == 3) //Anula documento  de arrendamiento
        {
            $doc = $request->all()["doc"];
            $obser = $request->all()["obser"];
            DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento')
                        ->where('id_documento',$doc)
                        ->update(
                            array(
                                    'id_estado' => 'A1',
                                    'usuario_anula' => Session::get('user_login'),
                                    'fecha_anula' => $this->fechaALong,
                                    'observacion_anula' => $obser 
                                )
                            );

            self::saveLog('OPERA29',$doc,"OBSERVACIÓN:" . $obser );
            return response()->json("1");
        }

        if($opc == 4) //Abrir documento de arrendamiento
        {
            $doc = $request->all()["doc"];
            $obser = $request->all()["obser"];

            DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento')
                        ->where('id_documento',$doc)
                        ->update(
                            array(
                                    'id_estado' => 'E1',
                                    'usuario_abre' => Session::get('user_login'),
                                    'fecha_abre' => $this->fechaALong,
                                    'observacion_abrir' => $obser
                                )
                            );

            self::saveLog('OPERA32',$doc,"OBSERVACIÓN:" . $obser );
            return response()->json("1");
        }

        if($opc == 5) //Consulta LOG documento 
        {
            $doc = $request->all()["doc"];
            $consulta = DB::connection('sqlsrvCxParque')
                        ->table('tra_log as log')
                        ->join('tra_maestro_log as maes','maes.id_log','=','log.id_log')
                        ->join('CAMPRO.dbo.sis_usuarios as sis','sis.id_usuario','=','log.id_usuario')
                        ->where('log.campro_valor',$doc)
                        ->select('log.fecha','log.descripcion','maes.descripcion as tipo_log','sis.propietario')
                        ->orderBy('log.fecha','desc')
                        ->get();

            return response()->json($consulta);
        }

        if($opc == 6) //Edita documento de arrendamiento - CANON
        {
            $doc = $request->all()["doc"];
            $canon = $request->all()["canon"];
            $obser = $request->all()["obser"];

            
            $canonActual = DB::connection('sqlsrvCxParque')
                            ->table('tra_arrendamiento')
                            ->where('id_documento',$doc)
                            ->get(['canon_actual','cantidad_dias'])[0];

            $dias = $canonActual->cantidad_dias;
            $total = (floatval($canon) / 30 ) * $dias;

            DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento')
                        ->where('id_documento',$doc)
                        ->update(
                            array(
                                    'canon_actual' => $canon,
                                    'usuario_ultima_actualizacion' => Session::get('user_login'),
                                    'fecha_ultima_actualizacion' => $this->fechaALong,
                                    'total_pagar' => $total
                                )
                            );

            self::saveLog('OPERA27',$doc,"MODIFICA CANON DE: $" .  $canonActual->canon_actual . " A $" . $canon . " - NUEVO VALOR A PAGAR: $" . $total . " - OBSERVACIÓN: " . $obser); 
            return response()->json("1");
        }

         if($opc == 7) //Confirma documento  masivamente
        {  
            $usuario = Session::get('user_login');
            $gid = DB::table("sis_usuarios")
                    ->join("rh_personas","rh_personas.id_persona","=","sis_usuarios.id_persona")
                    ->where("id_usuario",$usuario)
                    ->first();

            $errores = null;
            $doc = $request->all()['doc'];
            Session::flash('pry_arrendamiento',$request->all()["pry"]);
            Session::flash("arre_mes",$request->all()["mes"]);
            Session::flash("arre_anio",$request->all()["anio"]);


            for ($i=0; $i < count($doc); $i++) {
                $fechaA  = Carbon::now('America/Bogota');
                $fechaALong1 = $fechaA->toDateTimeString();
                $fechaShort1 = $fechaA->format('Y-m-d');
                 $result    = explode('-', $fechaShort1);
                $fechaDeliv = implode("", $result);
                $posicion = null;
                $cantidad = 1;
                $unidad = 'SER';
                $moneda = 'COP';
                $maestro = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->leftjoin('tra_arrendamiento','tra_maestro.placa','=','tra_arrendamiento.placa')
                    ->leftjoin('tra_propietarios','tra_propietarios.id_propietario','=','tra_maestro.id_propietario')
                    ->leftjoin('tra_contratantes','tra_contratantes.id','=','tra_maestro.id_proyecto')
                    ->leftjoin('tra_servicios','tra_servicios.numero_servicio','=','tra_maestro.numero_servicio')
                    ->where('tra_arrendamiento.id_documento',$doc[$i])
                    ->select('tra_contratantes.grupo_compras','tra_contratantes.codigo_pep',
                    'tra_contratantes.id_estado as contratanteEstado','tra_contratantes.codigo_pep',
                        'tra_arrendamiento.total_pagar','tra_arrendamiento.id_documento','tra_maestro.centro_logistico','tra_maestro.sociedad',
                        'tra_maestro.numero_activo_fijo','tra_maestro.subnumero_activo_fijo','tra_maestro.responsable',
                        'tra_propietarios.cuenta_proveedor','tra_propietarios.org_compras',
                        'tra_propietarios.id_estado as propietarioEstado','tra_maestro.elemento_pep',
                        'tra_servicios.descripcion','tra_servicios.grupo_articulos','tra_servicios.numero_servicio')
                    ->first();
                    
                    $maestro->codigo_pep = $maestro->elemento_pep; 
                    
                    if($maestro->responsable){
                        $maestro->sociedad = "";
                            $maestro->org_compras = "";
                            $posicion = "";
                            $maestro->numero_servicio = "";
                            $maestro->descripcion = "";
                            $maestro->grupo_articulos = "";
                            $cantidad = "";
                            $unidad = "";
                            $moneda = "";
                            $maestro->propietarioEstado = 'I';
                            $maestro->cuenta_proveedor = "";
                           // $maestro->centro_logistico = "";

                        $contratoSolped = DB::connection('sqlsrvCxParque')
                                ->table('tra_contratos_cab')
                                ->leftjoin("tra_contratos_det", function($join) use($maestro){
                                    $join->on("tra_contratos_det.numero_contrato","=","tra_contratos_cab.numero_contrato");
                                    $join->where("tra_contratos_det.centro_logistico","=",$maestro->centro_logistico);
                                })
                                ->leftjoin('tra_servicios','tra_servicios.numero_servicio','=','tra_contratos_det.numero_servicio')
                                // ->leftjoin('tra_propietarios','tra_propietarios.cuenta_proveedor','=','tra_contratos_cab.proveedor')
                                ->where('tra_contratos_cab.numero_contrato',"=",$maestro->responsable)
                                ->first();
                        if($contratoSolped){
                            $maestro->sociedad = $contratoSolped->sociedad;
                            $maestro->org_compras = $contratoSolped->organizacion_compras;
                            $posicion = $contratoSolped->posicion;
                            $maestro->numero_servicio = $contratoSolped->numero_servicio;
                            $maestro->descripcion = $contratoSolped->descripcion;
                            $maestro->grupo_articulos = $contratoSolped->grupo_articulos;
                            $cantidad = $contratoSolped->cantidad_servicio;
                            $unidad = $contratoSolped->id_unidad_servicio;
                            $moneda = $contratoSolped->moneda;
                            $maestro->propietarioEstado = 'A';
                            $maestro->cuenta_proveedor = $contratoSolped->proveedor;
                            $maestro->centro_logistico = $contratoSolped->centro_logistico;
                        }

                    
                     // dd($maestro);
                        // dd($maestro); 
                        if($maestro!=null){
                            if($maestro->propietarioEstado == "A"){  
                                                // 'TB_INT_S_MM_SERVICIOS.ASKTX','TB_INT_S_MM_SERVICIOS.ASNUM',
                                if($maestro->centro_logistico!='' && $maestro->centro_logistico!=null  && $maestro->numero_activo_fijo!='' && $maestro->numero_activo_fijo!=null && $maestro->subnumero_activo_fijo!='' && $maestro->subnumero_activo_fijo!=null && $maestro->codigo_pep!='' && $maestro->codigo_pep!=null && $maestro->grupo_compras!='' && $maestro->grupo_compras!=null  && $maestro->numero_servicio!='' && $maestro->numero_servicio!=null)
                                {
                                    DB::connection('sqlsrvSap')
                                    ->table('TB_INT_E_MM_SOLPED_CAB')
                                    ->insert(
                                        array(
                                            array(
                                                'TMS_INSERT' => $fechaALong1,
                                                'DOC_REFERENCIA' => $doc[$i],
                                                'USUARIO_CAM' => $gid->gid,
                                                'STATUS' => 1,
                                                'TMS_STATUS' => $fechaALong1
                                            )
                                        )
                                    );
                                
                                    DB::connection('sqlsrvSap')
                                    ->table('TB_INT_E_MM_SOLPED_DET')
                                    ->insert(
                                        array(
                                            array(
                                                'TMS_INSERT' => $fechaALong1,
                                                'DOC_REFERENCIA' => $doc[$i],
                                                'POS_REFERENCIA' => 1,
                                                'FIXED_VEND' => $maestro->cuenta_proveedor,
                                                'AGREEMENT' =>$maestro->responsable,
                                                'AGMT_ITEM' =>$posicion,
                                                'SHORT_TEXT' =>  $maestro->descripcion,
                                                'DELIV_DATE' => $fechaDeliv,
                                                'PLANT' => $maestro->centro_logistico,
                                                'MAT_GRP' => $maestro->grupo_articulos,
                                                'GR_PRICE' => $maestro->total_pagar,
                                                'QUANTITY' => $cantidad,
                                                'UNIT' => $unidad,
                                                'PURCH_ORG' => $maestro->org_compras,
                                                'PUR_GROUP' => $maestro->grupo_compras,
                                                'CURRENCY' => $moneda,
                                                'SERVICE' => $maestro->numero_servicio,
                                                'WBS_ELEM' => $maestro->codigo_pep,
                                                'BUKRS' => $maestro->sociedad,
                                                'ANLN1' => $maestro->numero_activo_fijo,
                                                'ANLN2' => $maestro->subnumero_activo_fijo
                                            )
                                        )
                                    );
                                    DB::connection('sqlsrvCxParque')
                                    ->table('tra_arrendamiento')
                                    ->where('id_documento',$doc[$i])
                                    ->update(
                                        array(
                                                'id_estado' => 'E3',
                                                'usuario_confirma' => Session::get('user_login'),
                                                'fecha_confirmacion' => $this->fechaALong
                                            )
                                        );

                                    self::saveLog('OPERA28',$doc[$i],"CONFIRMACIÓN MASIVAMENTE");
                                }else
                                {
                                    $errores[] = $maestro;
                                    continue;
                                }
                            }else
                            {
                                $errores[] = $maestro;
                                continue;
                            }
                        }else
                        {
                            $errores[] = $maestro;
                            continue;
                        }
                    }    
            }
            return response()->json("1");
        }

        if($opc == 8) //Genera Documentos Masivamente
        {   

            $usuario = Session::get('user_login');
            $gid = DB::table("sis_usuarios")
                    ->join("rh_personas","rh_personas.id_persona","=","sis_usuarios.id_persona")
                    ->where("id_usuario",$usuario)
                    ->first();

            DB::connection('sqlsrvCxParque')
                ->table('tra_errores_solped')
                ->delete();
            $data = $request->all()["data"];
            $observacionesSolped = "";
            
            Session::flash('pry_arrendamiento',$request->all()["pry"]);
            Session::flash("arre_mes",$request->all()["mes"]);
            Session::flash("arre_anio",$request->all()["anio"]);
            $errores = null;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            $length = 8;
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            for ($i=0; $i < count($data); $i++) { 
                $pry = $data[$i]["pry"];
                $mes = $data[$i]["mes"];
                $anio = $data[$i]["anio"];
                $placa = $data[$i]["placa"];
                $documento = $data[$i]["documento"];
                $dias = $data[$i]["dias"];
                $conse = 0;

                $diasTotales = 0;
                for ($j=0; $j < count($dias); $j++) { 
                    if($dias[$j]["check"] == "1")
                        $diasTotales++;
                }

                $valorCanonActual = DB::connection('sqlsrvCxParque')
                                    ->table('tra_maestro')
                                    ->leftjoin('tra_contratos_det','tra_contratos_det.numero_contrato','=','tra_maestro.responsable')
                                    ->where('placa',$placa)
                                    ->value('valor_servicio');

                $valorCanonActual = intval($valorCanonActual);

                $total = $valorCanonActual / 30 * $diasTotales;

                if($documento == "A") //Generar documento
                {   
                    $error = false;
                    $maestroSolped  =  DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->leftjoin('tra_propietarios','tra_propietarios.id_propietario','=','tra_maestro.id_propietario')
                    ->leftjoin('tra_contratantes','tra_contratantes.id','=','tra_maestro.id_proyecto')
                    ->leftjoin('tra_servicios','tra_servicios.numero_servicio','=','tra_maestro.numero_servicio')
                    ->where('tra_maestro.placa',$placa)
                    ->select('tra_contratantes.grupo_compras','tra_contratantes.codigo_pep',
                        'tra_contratantes.id_estado as contratanteEstado','tra_maestro.elemento_pep',
                        'tra_maestro.centro_logistico',
                        'tra_maestro.numero_activo_fijo','tra_maestro.subnumero_activo_fijo',
                        'tra_maestro.responsable','tra_maestro.placa',
                        'tra_propietarios.cuenta_proveedor')
                    ->first();
                    if($maestroSolped->elemento_pep!='' || $maestroSolped->elemento_pep!=null){
                        $maestroSolped->codigo_pep = $maestroSolped->elemento_pep; 
                    }
                    if($maestroSolped){
                        // if(!$maestroSolped->elemento_pep && $maestroSolped->codigo_pep){
                        //     $elementopep = DB::table('elementos_pep')
                        //                 ->select('elemento_pep')
                        //                 ->where('codigo_pep', $maestroSolped->codigo_pep)
                        //                 ->first();
                        //     if($elementopep->elemento_pep){
                        //         $maestroSolped->elemento_pep = $elementopep->elemento_pep;   
                        //     }            
                        // }
                        if($maestroSolped->responsable){
                            $centroLogis="";
                            $centroLogis = $maestroSolped->centro_logistico;
                            $contratoSolped = DB::connection('sqlsrvCxParque')
                                ->table('tra_contratos_cab')
                                ->leftjoin("tra_contratos_det", function($join) use($maestroSolped){
                                    $join->on("tra_contratos_det.numero_contrato","=","tra_contratos_cab.numero_contrato");
                                    $join->where("tra_contratos_det.centro_logistico","=",$maestroSolped->centro_logistico);
                                })
                                ->leftjoin('tra_servicios','tra_servicios.numero_servicio','=','tra_contratos_det.numero_servicio')
                                ->where('tra_contratos_cab.numero_contrato',"=",$maestroSolped->responsable)
                                ->first();
                            if($contratoSolped){
                                $maestroSolped->sociedad = $contratoSolped->sociedad;
                                $maestroSolped->org_compras = $contratoSolped->organizacion_compras;
                                $maestroSolped->numero_servicio = $contratoSolped->numero_servicio;
                                $maestroSolped->descripcion = $contratoSolped->descripcion;
                                $maestroSolped->grupo_articulos = $contratoSolped->grupo_articulos;
                                $maestroSolped->propietarioEstado = 'A';
                                $maestroSolped->centro_logistico = $contratoSolped->centro_logistico;

                                if($centroLogis != $maestroSolped->centro_logistico){
                                    $error = true;
                                    $errores[] = $maestroSolped;
                                }

                                if(!$maestroSolped->codigo_pep || !$maestroSolped->grupo_compras || !$maestroSolped->centro_logistico || !$maestroSolped->grupo_articulos || !$maestroSolped->org_compras || !$maestroSolped->numero_servicio){
                                    $error = true;
                                    $errores[] = $maestroSolped;
                                   
                                }
                                if($maestroSolped->propietarioEstado != 'A'){
                                    $error = true;
                                    $errores[] = $maestroSolped;
                                }
                            }
                        }else{
                            $error = true;
                            $errores[] = $maestroSolped;
                                $maestroSolped->sociedad = "";
                                $maestroSolped->org_compras = "";
                                $maestroSolped->numero_servicio = "";
                                $maestroSolped->descripcion = "";
                                $maestroSolped->grupo_articulos = "";
                                $maestroSolped->propietarioEstado = "";
                                $maestroSolped->centro_logistico = "";
                        }
                        if($gid->gid=="" || $gid->gid==null){
                            $error = true;
                            $errores[] = $maestroSolped;
                            $observacionesSolped = "No tienes un GID asociado";
                        }
                        // dd($maestroSolped);
                        // dd($errores);
                        if(!$error){
                            $conse = self::generaConsecutivo("ID_ARRENDAMIENTO");  
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_arrendamiento')
                            ->insert(
                                array(
                                        array(
                                            'id_documento' => $conse,
                                            'anio' => $anio,
                                            'mes' => $mes,
                                            'placa' => $placa,
                                            'prefijo' => $pry,
                                            'id_estado' => "E1",
                                            'cantidad_dias' => $diasTotales,
                                            'canon_actual' => $valorCanonActual,
                                            'total_pagar' => $total,
                                            'fecha_creacion' => $this->fechaALong,
                                            'usuario_crea' => Session::get('user_login'),
                                            'fecha_ultima_actualizacion' => $this->fechaALong,
                                            'usuario_ultima_actualizacion' => Session::get('user_login'),
                                            //aumentado por solicitud de carlos
                                            /*'id_estado' => 'E3',
                                            'usuario_confirma' => Session::get('user_login'),
                                            'fecha_confirmacion' => $this->fechaALong*/
                                            )
                                    ));
                            self::saveLog('OPERA30',$conse,"");
                        }else{
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_errores_solped')
                            ->insert(
                                array(
                                    array(
                                        'placa' => $maestroSolped->placa,
                                        'grupo_compras' => $maestroSolped->grupo_compras,
                                        'codigo_pep' => $maestroSolped->codigo_pep,
                                        'contratanteEstado' => $maestroSolped->contratanteEstado,
                                        'centro_logistico' => $maestroSolped->centro_logistico,
                                        'sociedad' => $maestroSolped->sociedad,
                                        'numero_servicio' => $maestroSolped->numero_servicio,
                                        'numero_activo_fijo' => $maestroSolped->numero_activo_fijo,
                                        'subnumero_activo_fijo' => $maestroSolped->subnumero_activo_fijo,
                                        'numero_contrato_vehiculo' => $maestroSolped->responsable,
                                        'cuenta_proveedor' => $maestroSolped->cuenta_proveedor,
                                        'org_compras' => $maestroSolped->org_compras,
                                        'propietarioEstado' => $maestroSolped->propietarioEstado,
                                        'descripcion' => $maestroSolped->descripcion,
                                        'grupo_articulos' => $maestroSolped->grupo_articulos,
                                        'id' => $randomString,
                                        'observaciones' => $observacionesSolped
                                    )
                                    ));
                             continue;
                        }
                    }else{
                        $error = true;
                    }
                }
                else
                {
                    $conse = DB::connection('sqlsrvCxParque')
                                    ->table('tra_arrendamiento')
                                    ->where('placa',$placa)
                                    ->where('mes',$mes)
                                    ->where('anio',$anio)
                                    ->where('prefijo',$pry)
                                    ->get(['id_documento','cantidad_dias','canon_actual','total_pagar'])[0];

                    DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento')
                        ->where('id_documento',$conse->id_documento)
                        ->update(
                            array(
                                    'prefijo' => $pry,
                                    'cantidad_dias' => $diasTotales,
                                    'canon_actual' => $valorCanonActual,
                                    'total_pagar' => $total,
                                    'fecha_ultima_actualizacion' => $this->fechaALong,
                                    'usuario_ultima_actualizacion' => Session::get('user_login'),
                                    
                                ));

                    self::saveLog('OPERA27',$conse->id_documento,"DATOS MODIFICADOS: Días:" . $conse->cantidad_dias . " - CANON:" . $conse->canon_actual . " - TOTAL:" . $conse->total_pagar );

                    $conse = $conse->id_documento;
                    $error = false;
                }

                if(!$error){
                    DB::connection('sqlsrvCxParque')
                        ->table('tra_detalle_arrendamiento')
                        ->where('id_documento',$conse)
                        ->where('prefijo',$pry)
                        ->delete();

                        for ($j=0; $j < count($dias); $j++) {
                            if($dias[$j]["dia"] >=1 && $dias[$j]["dia"] <= 25){
                                $mesExc = $dias[$j]["mes"];
                                $anioE = $dias[$j]["anio"];
                            }else{
                                $anioPE = $dias[$j]["anio"];
                                $mesPas = $dias[$j]["mes"]; 
                            }

                        }
                        // dd($dias);
                        // $myArray = collect($dias);
                        // $dias = $myArray->sortBy('dia')->toArray();
                        // for ($j=0; $j < 30; $j++) {}
                        // foreach ($dias as $valor) {
                        //     // dd($valor);
                        //     $dia[] = $valor;
                        // }
                        // $dias = $dia;
                    for ($j=0; $j < count($dias); $j++) {
                      
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_detalle_arrendamiento')
                            ->insert(
                                array(
                                        array(
                                            'id_documento' => $conse,
                                            'anio' => $dias[$j]["anio"],
                                            'mes' => $dias[$j]["mes"],
                                            'dia' => $dias[$j]["dia"],
                                            'prefijo' => $pry,
                                            'check' => $dias[$j]["check"],
                                            'observacion' => $dias[$j]["obser"]
                                            )
                                    )); 
                        
                        if(isset($dias[$j]["check"]) && $dias[$j]["check"] ==  "0")
                            self::saveLog('OPERA34',$conse,"Día: " . $dias[$j]["dia"] . " Mes: " . $dias[$j]["mes"] . " - OBSERVACIÓN: " . $dias[$j]["obser"]);
                    // Agrega la observación al log
                    }
                }  
            }
            if($errores){
                return response()->json($randomString);
            }
            return response()->json('1');
        }


        if($opc == 9) //Guarda cambio de día
        {
            $dia = $request->all()["dia"];
            $mes = $request->all()["mes"];
            $anio = $request->all()["anio"];
            $documento = $request->all()["documento"];
            $obser = $request->all()["obser"];
            $check = $request->all()["check"];

            if($check == "0") //Quita día
                self::saveLog('OPERA34',$documento,"Día: " .  $dia . " Mes: " . $mes . " - OBSERVACIÓN: " . $obser);
            else
                self::saveLog('OPERA35',$documento,"Día: " .  $dia . " Mes: " . $mes . " - OBSERVACIÓN: " . $obser);
            
            ///////////////////////77
            //aca recibir el archivo
            //////////////////
            
            
        $adjunto="";
        
        // if( $request->file('file')!=null && $request->file('file')!=''){
        //     //obtenemos el campo file definido en el formulario
        
        //    $file = $request->file('file');
 
        //     //obtenemos el nombre del archivo
        //     $nombre = $file->getClientOriginalName();
        //     $extension = strtolower($file->getClientOriginalExtension());
            
        //     $nuevonombre = "descuento_".$fecah= date('Y-m-d--g-i-s').".".$extension;
        //      //indicamos que queremos guardar un nuevo archivo en el disco local
        //      \Storage::disk('local')->put($nombre,  \File::get($file));
             
        //     $id_ftp=ftp_connect("190.60.248.195",21); //Obtiene un manejador del Servidor FTP
        //     ftp_login($id_ftp,"usuario_ftp","74091450652!@#1723cc"); //Se loguea al Servidor FTP
        //     ftp_pasv($id_ftp,true); //Se coloca el modo pasivo
        //     ftp_chdir($id_ftp, "anexos_apa/arrendamientos"); // Nos dirigimos a la carpeta de destino
        //     $Directorio=ftp_pwd($id_ftp);
        //     $Directorio2=$Directorio;
        //     //Guardar el archivo en mi carpeta donde esta el proyecto    
        //     $res = 0;
        //     try{
        //         $fileL = storage_path('app') . "/" .  $nombre;
        //         //\Storage::disk('local')->put($nombre, $imagenDigital);
        //         $exi = ftp_put($id_ftp,$Directorio . "/" . $nuevonombre,$fileL,FTP_BINARY); 
                
        //         //echo "adjunti |".$Directorio . "/" . $nuevonombre."|";
        //         //Cuando se envia el archivo, se elimina el archivo
        //         if(file_exists($fileL)) unlink($fileL);
                
        //         $adjunto=$nuevonombre;
        //     }catch(Exception $e){
        //         $res = $e;
        //     }
        
        // }

        // se arregla que el dia concuerdo con el mes de arrendamiento siendo los dias de 26 al 30 del mes anterior 
            if($dia>25 && $dia<31){
                if($mes==1){
                    $mes = 12;
                }else{
                    $mes = $mes-1;
                }
            }
            
            ///////////////////////////////
            
            if(DB::connection('sqlsrvCxParque')
                    ->table('tra_detalle_arrendamiento')
                    ->where('id_documento',$documento)
                    ->where('dia',$dia)
                    ->where('anio',$anio)
                    ->where('mes',$mes)
                    ->count() == 0)
            {

                $pre = DB::connection('sqlsrvCxParque')
                        ->table('tra_detalle_arrendamiento')
                        ->where('id_documento',$documento)
                        ->value('prefijo');

                //Insertamos registros
                DB::connection('sqlsrvCxParque')
                    ->table('tra_detalle_arrendamiento')
                    ->insert(array(
                            array(
                                'id_documento' => $documento,
                                'dia' => $dia,
                                'mes' => $mes,
                                'anio' => $anio,
                                'check' => $check,
                                'prefijo' => $pre,
                                'observacion' => $obser,
                                'archivo' =>$adjunto
                            )
                        ));
            }
            else
            {
                $actualiza= array( 'check' => $check, 'observacion' => $obser );
                
                if($check==1){
                    $actualiza= array( 'check' => $check,'observacion' => $obser,'archivo' =>null);    
                }else if($adjunto!=""){
                    $actualiza= array( 'check' => $check,'observacion' => $obser,'archivo' =>$adjunto);    
                    $retorna=$adjunto;
                }
                
                
                //Actualizamos registro
                DB::connection('sqlsrvCxParque')
                    ->table('tra_detalle_arrendamiento')
                    ->where('id_documento',$documento)
                    ->where('dia',$dia)
                    ->where('anio',$anio)
                    ->where('mes',$mes)
                    ->update(
                        $actualiza
                    );
            }

            //Consulta días checkeados
            $dias = DB::connection('sqlsrvCxParque')
                    ->table('tra_detalle_arrendamiento')
                    ->where('id_documento',$documento)
                    ->where("check",1)
                    ->count();

            //Consulta canon del vehículo
            $canon  = DB::connection('sqlsrvCxParque')
                        ->table('tra_arrendamiento')
                        ->where('id_documento',$documento)
                        ->value('canon_actual');

            $total = $canon / 30 * $dias;

            DB::connection('sqlsrvCxParque')
                ->table('tra_arrendamiento')
                ->where('id_documento',$documento)
                ->update(
                    array(
                        'cantidad_dias' => $dias,
                        'total_pagar' => $total,
                        'fecha_ultima_actualizacion' => $this->fechaALong,
                        'usuario_ultima_actualizacion' => Session::get('user_login')
                    )
                );

            self::saveLog('OPERA27',$documento,"DATOS MODIFICADOS: Días:" . $dias . " - CANON:" . $canon . " - TOTAL:" . $total );

            return response()->json($retorna);
        }


        /*FUNCIONES CONTROLLER*/
    }

    public function exporteErrores(Request $request){
        \Excel::create('Exporte errores arrendamientos ', function($excel) use($request) {            
            $excel->sheet('Errores Solped', function($sheet) use($request){
                $row = 1;
                $sheet->row($row, array(
                    'Placa', 'Grupo de compras ', 'Estado del contratante','Centro Logistico','Sociedad','Numero de servico','Numero de activo fijo','Sub numero de activo fijo','Numero de contrato','Cuenta de proveedor','Organizacion de compras','Estado del propietario','Descripcion de servcio','Grupo de articulos','Elemento Pep','observaciones'
                ));
                $errores = DB::connection('sqlsrvCxParque')
                ->table('tra_errores_solped')
                ->where('id',$request->valorExcel)
                ->get();

                $row++;
                foreach ($errores as $o) {
                    $sheet->row($row, array(
                        $o->placa,$o->grupo_compras,$o->contratanteEstado,$o->centro_logistico,$o->sociedad,
                        $o->numero_servicio,$o->numero_activo_fijo,$o->subnumero_activo_fijo,$o->numero_contrato_vehiculo,
                        $o->cuenta_proveedor,$o->org_compras,$o->propietarioEstado,$o->descripcion,
                        $o->grupo_articulos,$o->codigo_pep,$o->observaciones));
                    // $sheet->setColumnFormat(array(
                    //     'F'.$row => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE
                    // ));
                    $row++;
                }
            });
        })->export('xls');
    }
    /*****************************************************************************/
    /******** FIN WEB SERVICES CAMPRO WEB ARRENADAMIENTOS - GUARDAR **************/
    /*****************************************************************************/


    /*****************************************************************************/
    /**************** EXPORTE DE LOS ARRENDAMIENTOS GENERADOS*********************/
    /*****************************************************************************/
    public function exporteArrendamiento(Request $request)
    {
        
        
        $idusu =  Session::get("user_login");
        $sql="select 
                        p.prefijo_db,p.nombre
                from 
                    Campro.dbo.sis_usuarios_proyectos as up inner join
                    Campro.dbo.gop_proyectos as p on (up.id_proyecto=p.id_proyecto)
                where 
                        up.id_usuario='".$idusu."' and p.prefijo_db is not null and p.prefijo_db <> ''
                group by 
                    p.prefijo_db,p.nombre ";
        $proyectospermi=array();
      /*  try{
            $consultaEliminado = DB::connection('sqlsrv')->select("SET NOCOUNT ON;" . $sql);
            foreach($consultaEliminado as $result){
                array_push($proyectospermi, $result->prefijo_db);
            }         
        }catch (\PDOException $e) { $proyectospermi = array(); }*/
        
        
        
        $mes = $request->all()["mes"];
        $anio = $request->all()["anio"];
        $contrato = $request->all()["pry"];

        $ultimoDia =  date("d",(mktime(0,0,0,$mes+1,1,$anio)-1));

        $fecha1 = $anio . "-" . $mes. "-" . 1;
        $fecha2 = $anio . "-" . $mes. "-" . $ultimoDia;

        $placa_arrendamiento = [];
        $registros = [];
        $registrosDocGenerados = [];

        $mesPasado = intval($mes) - 1;
        $anioActual = $anio;
        $anioPasado = ($mesPasado == 0 ? intval($anioActual) - 1 : $anioActual);
        $mesPasado = ($mesPasado == 0 ? 12 : $mesPasado);

        $ultimoDiaMesPasado =  date("d",(mktime(0,0,0,$mesPasado+1,1,$anioPasado)-1));
        $iniciCuenta = 26;       
        
        $cad = "EXEC sp_tra_consulta_vehiculos_arrendamiento '$contrato','$mes','$anio','$mesPasado','$anioPasado';";
      //  dd($cad);
        try{
            $placa_arrendamiento = DB::connection('sqlsrvCxParque')->select("SET NOCOUNT ON;" . $cad);
        } catch (\PDOException $e) {
            $placa_arrendamiento = array();
        }
        
        
        

        $sql = "
            SELECT histo.dia,histo.incidencia,histo.placa
            FROM 
            tra_vehiculo_ubicacion_proyecto as histo
            INNER JOIN tra_contratantes as contra ON histo.id_proyecto = contra.id
            WHERE 
              ((mes = '$mes' and anio = '" . $anio . "' and dia < 26)
              OR (mes = '$mesPasado' and anio = '" . $anioPasado . "' and dia > 25))
              AND prefijo = '$contrato' AND incidencia IS NOT NULL "
            /*  AND contra.prefijo not in (select 
                                                p.prefijo_db
                                         from 
                                            Campro.dbo.sis_usuarios_proyectos as up inner join
                                            Campro.dbo.gop_proyectos as p on (up.id_proyecto=p.id_proyecto)
                                         where 
                                                up.id_usuario='".$idusu."' and p.prefijo_db is not null and p.prefijo_db <> ''
                                         group by 
                                            p.prefijo_db)*/
            ."ORDER BY histo.placa, histo.dia
        ";

        try{
            $registros = DB::connection('sqlsrvCxParque')->select("SET NOCOUNT ON;" . $sql);
        } catch (\PDOException $e) {
            $registros = array();
        }
        
        
        try{
            $registrosDocGenerados = DB::connection('sqlsrvCxParque')
                                    ->table('tra_arrendamiento as arrend')
                                    ->join('tra_detalle_arrendamiento as det','det.id_documento','=','arrend.id_documento')
                                    ->where('arrend.anio',$anio)
                                    ->where('arrend.mes',$mes)
                                    ->where('arrend.prefijo',$contrato)                    
                                    ->whereIn('arrend.prefijo',$proyectospermi)
                                    ->orderBy('arrend.placa')
                                    ->orderBy('det.dia')
                                    ->get(['arrend.placa','det.dia','det.check']);
        }catch(\PDOException $e) {
            $registrosDocGenerados = array();
        }

        $arr = explode("-",$this->fechaShort);
        $diaActual = intval($arr[2]);
        

        \Excel::create('Exporte de arrendamientos ' . $this->fechaALong, function($excel) use($placa_arrendamiento,$registros,$registrosDocGenerados,$mesPasado,$anioPasado,$anio,$mes,$iniciCuenta,$ultimoDia,$ultimoDiaMesPasado) {            
                $excel->sheet('Exporte de arrendamientos', function($sheet) use($placa_arrendamiento,$registros,$registrosDocGenerados,$mesPasado,$anioPasado,$anio,$mes,$iniciCuenta,$ultimoDia,$ultimoDiaMesPasado){
                   
                    $titulos = ["N°","PROYECTO","PLACA","ESTADO VEHÍCULO","TIPO DE VEHÍCULO","MODELO","marca_veh","contrato_veh","fin_veh","numero_activo","TIPO CAM","CANON","ID DOCUMENTO","FECHA DE CREACIÓN",
                    "CANTIDAD DIAS","TOTAL A PAGAR"];

                    for($i = $iniciCuenta ; $i <= $ultimoDiaMesPasado; $i++)
                    {
                        array_push($titulos, $i. "-" . $mesPasado . "-" . $anioPasado);
                    };
                    
                    for($i = 1 ; $i < 26; $i++)
                    {
                        array_push($titulos, $i. "-" . $mes . "-" . $anio);
                    };
                    $sheet->fromArray($titulos);

                    $fila = 1;
                    $cont = 0;

                    
                    foreach ($placa_arrendamiento as $key => $val) {
                            $cont++;    

                            $arreglo = Array();

                            $diasLlenados = Array();
                            
                            
                            $valorapagar = $val->total_pagar;
                            if($valorapagar>$val->canon){
                                $valorapagar=$val->canon;
                            }
                            $cdias=$val->cantidad_dias;
                            if($cdias >30){$cdias=30;}
                            
                            array_push($arreglo, $cont ,$val->nombre,
                                                strtoupper ($val->placa),
                                                strtoupper ($val->estadoDes),
                                                strtoupper ($val->tipo_veh),
                                                strtoupper ($val->modelo),
                                                strtoupper ($val->marca_veh),
                                                strtoupper ($val->contrato_veh),
                                                strtoupper ($val->fin_veh),
                                                strtoupper ($val->numero_activo),     
                                                strtoupper ($val->nombre_cam),
                                                $val->canon,
                                                $val->id_documento,
                                                $val->fecha,
                                                $cdias,
                                                $valorapagar);

                            for($i = $iniciCuenta ; $i <= $ultimoDiaMesPasado; $i++)
                            {
                                if(isset($val->dia26))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "26")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 26)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia26 = "SI";
                                    else
                                        $dia26 = "NO";

                                    array_push($arreglo, $dia26);
                                    array_push($diasLlenados, "26");
                                }else
                                {
                                    if(26 >= $iniciCuenta && 26 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "26")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "26");
                                    }
                                }

                                if(isset($val->dia27))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "27")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 27)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia27 = "SI";
                                    else
                                        $dia27 = "NO";

                                    array_push($arreglo, $dia27);
                                    array_push($diasLlenados, "27");
                                }else
                                {
                                    if(27 >= $iniciCuenta && 27 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "27")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "27");
                                    }
                                }

                                if(isset($val->dia28))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "28")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 28)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia28 = "SI";
                                    else
                                        $dia28 = "NO";

                                    array_push($arreglo, $dia28);
                                    array_push($diasLlenados, "28");
                                }else
                                {
                                    if(28 >= $iniciCuenta && 28 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "28")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "28");
                                    }
                                }

                                if(isset($val->dia29))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "29")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 29)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia29 = "SI";
                                    else
                                        $dia29 = "NO";

                                    array_push($arreglo, $dia29);
                                    array_push($diasLlenados, "29");
                                }else
                                {
                                    if(29 >= $iniciCuenta && 29 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "29")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "29");
                                    }
                                }

                                if(isset($val->dia30))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "30")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 30)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia30 = "SI";
                                    else
                                        $dia30 = "NO";

                                    array_push($arreglo, $dia30);
                                    array_push($diasLlenados, "30");
                                }else
                                {
                                    if(30 >= $iniciCuenta && 30 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "30")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "30");
                                    }
                                }

                                if(isset($val->dia31))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "31")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 31)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia31 = "SI";
                                    else
                                        $dia31 = "NO";

                                    array_push($arreglo, $dia31);
                                    array_push($diasLlenados, "31");
                                }else
                                {
                                    if(31 >= $iniciCuenta && 31 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "31")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "31");
                                    }
                                }
                            }

                             for($i = 1 ; $i <= $ultimoDia; $i++)
                             {
                                if(isset($val->dia1))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "1")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 1)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia1 = "SI";
                                    else
                                        $dia1 = "NO";

                                    array_push($arreglo, $dia1);
                                    array_push($diasLlenados, "1");
                                }
                                else
                                {
                                    if(1 >= 1 && 1 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "1")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "1");
                                    }
                                }

                                if(isset($val->dia2))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "2")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 2)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia2 = "SI";
                                    else
                                        $dia2 = "NO";

                                    array_push($arreglo, $dia2);
                                    array_push($diasLlenados, "2");
                                }else
                                {
                                    if(2 >= 1 && 2 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "2")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "2");
                                    }
                                }

                                if(isset($val->dia3))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "3")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 3)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia3 = "SI";
                                    else
                                        $dia3 = "NO";

                                    array_push($arreglo, $dia3);
                                    array_push($diasLlenados, "3");
                                }else
                                {
                                    if(3 >= 1 && 3 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "3")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "3");
                                    }
                                }

                                if(isset($val->dia4))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "4")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 4)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia4 = "SI";
                                    else
                                        $dia4 = "NO";

                                    array_push($arreglo, $dia4);
                                    array_push($diasLlenados, "4");
                                }else
                                {
                                    if(4 >= 1 && 4 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "4")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "4");
                                    }
                                }

                                if(isset($val->dia5))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "5")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 5)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia5 = "SI";
                                    else
                                        $dia5 = "NO";

                                    array_push($arreglo, $dia5);
                                    array_push($diasLlenados, "5");
                                }else
                                {
                                    if(5 >= 1 && 5 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "5")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "5");
                                    }
                                }

                                if(isset($val->dia6))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "6")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 6)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia6 = "SI";
                                    else
                                        $dia6 = "NO";

                                    array_push($arreglo, $dia6);
                                    array_push($diasLlenados, "6");
                                }else
                                {
                                    if(6 >= 1 && 6 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "6")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "6");
                                    }
                                }

                                if(isset($val->dia7))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "7")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 7)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia7 = "SI";
                                    else
                                        $dia7 = "NO";

                                    array_push($arreglo, $dia7);
                                    array_push($diasLlenados, "7");
                                }else
                                {
                                    if(7 >= 1 && 7 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "7")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "7");
                                    }
                                }

                                if(isset($val->dia8))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "8")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 8)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia8 = "SI";
                                    else
                                        $dia8 = "NO";

                                    array_push($arreglo, $dia8);
                                    array_push($diasLlenados, "8");
                                }else
                                {
                                    if(8 >= 1 && 8 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "8")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "8");
                                    }
                                }

                                if(isset($val->dia9))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "9")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 9)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia9 = "SI";
                                    else
                                        $dia9 = "NO";

                                    array_push($arreglo, $dia9);
                                    array_push($diasLlenados, "9");
                                }else
                                {
                                    if(9 >= 1 && 9 <= $ultimoDia)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "9")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "9");
                                    }
                                }

                                if(isset($val->dia10))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "10")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 10)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia10 = "SI";
                                    else
                                        $dia10 = "NO";

                                    array_push($arreglo, $dia10);
                                    array_push($diasLlenados, "10");
                                }else
                                {
                                    if(10 >= 1 && 10 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "10")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "10");
                                    }
                                }

                                if(isset($val->dia11))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "11")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 11)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia11 = "SI";
                                    else
                                        $dia11 = "NO";

                                    array_push($arreglo, $dia11);
                                    array_push($diasLlenados, "11");
                                }else
                                {
                                    if(11 >= 1 && 11 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "11")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "11");
                                    }
                                }

                                if(isset($val->dia12))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "12")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 12)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia12 = "SI";
                                    else
                                        $dia12 = "NO";

                                    array_push($arreglo, $dia12);
                                    array_push($diasLlenados, "12");
                                }else
                                {
                                    if(12 >= 1 && 12 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "12")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "12");
                                    }
                                }

                                if(isset($val->dia13))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "13")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 13)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia13 = "SI";
                                    else
                                        $dia13 = "NO";

                                    array_push($arreglo, $dia13);
                                    array_push($diasLlenados, "13");
                                }else
                                {
                                    if(13 >= 1 && 13 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "13")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "13");
                                    }
                                }

                                if(isset($val->dia14))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "14")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 14)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia14 = "SI";
                                    else
                                        $dia14 = "NO";

                                    array_push($arreglo, $dia14);
                                    array_push($diasLlenados, "14");
                                }else
                                {
                                    if(14 >= 1 && 14 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "14")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "14");
                                    }
                                }

                                if(isset($val->dia15))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "15")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 15)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia15 = "SI";
                                    else
                                        $dia15 = "NO";

                                    array_push($arreglo, $dia15);
                                    array_push($diasLlenados, "15");
                                }else
                                {
                                    if(15 >= 1 && 15 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "15")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "15");
                                    }
                                }

                                if(isset($val->dia16))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "16")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 16)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia16 = "SI";
                                    else
                                        $dia16 = "NO";

                                    array_push($arreglo, $dia16);
                                    array_push($diasLlenados, "16");
                                }else
                                {
                                    if(16 >= 1 && 16 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "16")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "16");
                                    }
                                }

                                if(isset($val->dia17))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "17")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 17)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia17 = "SI";
                                    else
                                        $dia17 = "NO";

                                    array_push($arreglo, $dia17);
                                    array_push($diasLlenados, "17");
                                }else
                                {
                                    if(17 >= 1 && 17 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "17")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "17");
                                    }
                                }

                                if(isset($val->dia18))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "18")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 18)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia18 = "SI";
                                    else
                                        $dia18 = "NO";

                                    array_push($arreglo, $dia18);
                                    array_push($diasLlenados, "18");
                                }else
                                {
                                    if(18 >= 1 && 18 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "18")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "18");
                                    }
                                }

                                if(isset($val->dia19))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "19")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 19)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia19 = "SI";
                                    else
                                        $dia19 = "NO";

                                    array_push($arreglo, $dia19);
                                    array_push($diasLlenados, "19");
                                }else
                                {
                                    if(19 >= 1 && 19 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "19")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "19");
                                    }
                                }

                                if(isset($val->dia20))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "20")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 20)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia20 = "SI";
                                    else
                                        $dia20 = "NO";

                                    array_push($arreglo, $dia20);
                                    array_push($diasLlenados, "20");
                                }else
                                {
                                    if(20 >= 1 && 20 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "20")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "20");
                                    }
                                }

                                if(isset($val->dia21))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "21")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 21)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia21 = "SI";
                                    else
                                        $dia21 = "NO";

                                    array_push($arreglo, $dia21);
                                    array_push($diasLlenados, "21");
                                }else
                                {
                                    if(21 >= 1 && 21 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "21")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "21");
                                    }
                                }

                                if(isset($val->dia22))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "22")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 22)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia22 = "SI";
                                    else
                                        $dia22 = "NO";

                                    array_push($arreglo, $dia22);
                                    array_push($diasLlenados, "22");
                                }else
                                {
                                    if(22 >= 1 && 22 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "22")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "22");
                                    }
                                }

                                if(isset($val->dia23))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "23")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 23)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia23 = "SI";
                                    else
                                        $dia23 = "NO";

                                    array_push($arreglo, $dia23);
                                    array_push($diasLlenados, "23");
                                }else
                                {
                                    if(23 >= 1 && 23 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "23")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "23");
                                    }
                                }

                                if(isset($val->dia24))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "24")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 24)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia24 = "SI";
                                    else
                                        $dia24 = "NO";

                                    array_push($arreglo, $dia24);
                                    array_push($diasLlenados, "24");
                                }else
                                {
                                    if(24 >= 1 && 24 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "24")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "24");
                                    }
                                }

                                if(isset($val->dia25))
                                {
                                    $exist = 0;
                                    for ($j=0; $j < count($diasLlenados); $j++) { 
                                        if($diasLlenados[$j] == "25")
                                        {
                                            $exist = 1;
                                            break;
                                        }
                                    }

                                    if($exist == 1)
                                        break;

                                    $checkARgenerados = 0; 
                                    foreach($registrosDocGenerados as $key => $valor)
                                    {
                                        if(strtoupper($valor->placa) == strtoupper($val->placa))
                                        {
                                            if($valor->dia == 25)
                                            {
                                                $checkARgenerados = intval($valor->check);
                                                unset($registrosDocGenerados[$key]);
                                                break;
                                            }
                                        }
                                    }

                                    if($checkARgenerados == 1)
                                        $dia25 = "SI";
                                    else
                                        $dia25 = "NO";

                                    array_push($arreglo, $dia25);
                                    array_push($diasLlenados, "25");
                                }else
                                {
                                    if(25 >= 1 && 25 <= $ultimoDiaMesPasado)
                                    {
                                        $exist = 0;
                                        for ($j=0; $j < count($diasLlenados); $j++) { 
                                            if($diasLlenados[$j] == "25")
                                            {
                                                $exist = 1;
                                                break;
                                            }
                                        }

                                        if($exist == 1)
                                            break;

                                        array_push($arreglo, "NO");
                                        array_push($diasLlenados, "25");
                                    }
                                }



                             }



                            $sheet->row($fila +1, $arreglo);  
                            $fila++;
                        }

                });
        })->export('xlsx');
    }
    public function servicios(){
        
        $aData =  DB::connection('sqlsrvCxParque')->table('tra_servicios')->get();
        $aServicios =array('aData'=>$aData);
         return $aServicios;
    }
}



    
