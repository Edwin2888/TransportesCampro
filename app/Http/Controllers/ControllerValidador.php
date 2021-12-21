<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Session;
use DB;
use Carbon\Carbon;
use Redirect;


class ControllerValidador extends Controller
{

    /*************************************************/
    /******** FUNCIONES DEL CONTROLADOR **************/

    function __construct() {

        $this->valorT = "";
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
        
    }

    /******** FIN FUNCIONES DEL CONTROLADOR **************/
    /*************************************************/

    /*************************************************/
    /******** FUNCIONES DEL VALIDADOR **************/
	/*
    *	Función encargada de mostrar el ruta Index del validador
    */    
    public function index()
    {

        $tipoRecibo = DB::table('eca_gop_validador')
                    ->select('tarifa_recibo')
                    ->groupBy('tarifa_recibo')
                    ->orderBy('tarifa_recibo')
                    ->get();

        $gestores = DB::table('eca_gop_gestor_validador') 
                    ->select('id_cruce_gestor','nombre_gestor')
                    ->where('id_cruce_gestor','<>','0')
                    ->orderBy('nombre_gestor')
                    ->get();

        $gestoresC = DB::table('eca_gop_gestor_validador') 
                    ->orderBy('nombre_gestor')
                    ->where('id_cruce_gestor','<>','0')
                    ->lists('nombre_gestor','id_cruce_gestor');

        $validaciones = DB::table('eca_gop_validador')
                    ->join('eca_gop_gestor_validador','eca_gop_gestor_validador.id_cruce_gestor','=','eca_gop_validador.id_cruce_gestor')
                    ->orderBy('id');

        if(Session::has('id_estado')){

            if(Session::get('id_estado') != -1)
                $validaciones = $validaciones->where('visitado',Session::get('id_estado'));

            if(Session::get('fecha_inicio') != "" && Session::get('fecha_corte') != "")
            {
                $fecha1 = explode("/",Session::get('fecha_inicio'));
                $fecha1 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];

                $fecha2 = explode("/",Session::get('fecha_corte'));
                $fecha2 = $fecha2[2] . "-" . $fecha2[1] . "-" . $fecha2[0];

                $validaciones = $validaciones->whereBetween('fecha_visita_gestor',[$fecha1,$fecha2]);
            }

            if(Session::get('nic') != "")
                $validaciones = $validaciones->where('nic','LIKE','%' . Session::get('nic') . '%');

            if(Session::get('id_tarifa') != "0")
                $validaciones = $validaciones->where('tarifa_recibo','LIKE','%' . Session::get('id_tarifa') . '%');

            if(Session::get('cliente') != "")
                $validaciones = $validaciones->where('cliente','LIKE','%' . Session::get('cliente') . '%');

            if(Session::get('id_gestor') != "")
                $validaciones = $validaciones->where('eca_gop_validador.id_cruce_gestor','LIKE','%' .  Session::get('id_gestor') . '%');

            $validaciones = $validaciones->get();

            if(isset($fecha1))
                $fecha1 = explode("-",$fecha1);
            else
                $fecha1 = explode("-",Session::get('fecha_inicio'));

            $fecha1 = $fecha1[2] . "/" . $fecha1[1] . "/" . $fecha1[0];
            Session::flash('fecha_inicio',$fecha1);

            if(isset($fecha2))
                $fecha2 = explode("-",$fecha2);
            else
                $fecha1 = explode("-",Session::get('fecha_corte'));

            $fecha2 = $fecha2[2] . "/" . $fecha2[1] . "/" . $fecha2[0];
            Session::flash('fecha_corte',$fecha2);

            return view("proyectos.electricaribe.validador.index",
            array("validaciones" => $validaciones,
                "tipo_recibo" => $tipoRecibo,
                'gestores' => $gestores,
                'combo' => $gestoresC));
        }   

        $validaciones = $validaciones->whereBetween('fecha_visita_gestor',[$this->fechaShort,$this->fechaShort])->get();

        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];
        

    	return view("proyectos.electricaribe.validador.index",
            array("validaciones" => $validaciones,
                "tipo_recibo" => $tipoRecibo,
                "fecha1" => $fechaActual,
                "fecha2" => $nuevafecha,
                'gestores' => $gestores,
                'combo' => $gestoresC));
    }

    /**
    Función encargada de realizar el filtro de la información
    */
    public function filterValidador(Request $request)
    {
        Session::flash('id_estado', $request->all()["id_estado"]);
        Session::flash('fecha_inicio', $request->all()["fecha_inicio"]);
        Session::flash('fecha_corte', $request->all()["fecha_corte"]);
        Session::flash('id_estado', $request->all()["id_estado"]);
        Session::flash('nic', $request->all()["nic"]);
        Session::flash('id_tarifa', $request->all()["id_tarifa"]);
        Session::flash('cliente', $request->all()["cliente"]);
        Session::flash('id_gestor', $request->all()["id_gestor"]);    
        
        //var_dump($request->all());
        return Redirect::to('electricaribe/validador/index');
    }

    /******** FIN FUNCIONES DEL VALIDADOR **************/
    /*************************************************/
    
    /*************************************************/
    /******** FUNCIONES DEL CARGA MASIVO **************/
    /*
    *	Función encargada de cargar el excel del validador
    */
    public function uploadExcelValidador(Request $request)
    {

   
    	$fileL= storage_path(). "\logsCargaExcelValidador_" . $this->fechaShort . ".txt";
        if(file_exists($fileL))
            unlink($fileL);

        //obtenemos el campo file definido en el formulario
       $file = $request->file('file_upload');
       
       //Varificamos que carge un .xlsx
       $mime = $file->getMimeType();
       if($mime != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
       {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"Tipo de archivo invalido, tiene que carga un archivo .xlsx");
            return Redirect::to('/electricaribe/validador/index');
       }

       //obtenemos el nombre del archivo
       $nombre = $file->getClientOriginalName();

       //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre,  \File::get($file));

       $ruta = "storage/app" . "/" . $nombre;
     
       //var_dump($ruta);

       set_time_limit(0);

       $result = \Excel::load($ruta, function($reader) use ($request,$fileL) {

            $results = $reader->toArray();

            if(isset($results[0]["nic"])  == false||
                    isset($results[0]["unicom"])  == false||
                    isset($results[0]["tipo_recibo"])  == false||
                    isset($results[0]["estado_recibo"])  == false ||
                    isset($results[0]["tarifa_recibo"])  == false ||
                    isset($results[0]["cliente"])  == false ||
                    isset($results[0]["simbolo_variable"])  == false ||
                    isset($results[0]["fecha_facturacion"])  == false ||
                    isset($results[0]["fecha_vcto"])  == false ||
                    isset($results[0]["importe"])  == false ||
                    isset($results[0]["pendiente"])  == false) 
            {
                Session::flash('dataExcel2',"El archivo que esta tratando de cargar no es válido");                  
            }
            else
            {
                $nicIngresados = Array();
                //Crea encabezado
                for ($i=0; $i < count($results); $i++) 
                {
                
                    if($results[$i]["nic"] == "" || $results[$i]["unicom"] == "" || $results[$i]["tipo_recibo"] == "" ||
                        $results[$i]["cliente"] == "" || $results[$i]["simbolo_variable"] == "")
                        continue;
                    $nic =  $results[$i]["nic"];
                    $nicExis = false;
                    for ($j=0; $j < count($nicIngresados); $j++) { 
                        if($nicIngresados[$j] == $nic)
                            $nicExis = true;
                    }

                    if(!$nicExis) // Si el NIC no existe, lo puedo ingresar
                    {
                        $tarifa_recibo =  $results[$i]["tarifa_recibo"];
                        $cliente =  $results[$i]["cliente"];
                        $cruce_gestor =  ($results[$i]["cruce_gestor"] == "" ? '0' : $results[$i]["cruce_gestor"]);
                        $fecha =  $results[$i]["fecha"];


                        $fecha_fin = explode("/",$fecha);
                        if(count($fecha_fin) > 1)
                            $fecha = $fecha_fin[2] . "-" . $fecha_fin[1] . "-" . $fecha_fin[0];

                        array_push($nicIngresados,$nic);

                        $consulta = DB::Table('eca_gop_validador')
                                    ->where('nic',$nic)
                                    ->select('id')
                                    ->get();

                        if(count($consulta) == 0) //Insertar dato, no existe
                        {
                            DB::table('eca_gop_validador')
                            ->insert(array(
                                array(
                                    'nic' => $nic,
                                    'tarifa_recibo' => $tarifa_recibo,
                                    'cliente' => $cliente,
                                    'id_cruce_gestor' => $cruce_gestor,
                                    'fecha_visita_gestor' => $fecha,
                                    'fecha_creacion_servidor' => $this->fechaALong
                                    )));
                        }
                        else //Update Datos
                        {
                            DB::table('eca_gop_validador')
                            ->where('nic',$nic)
                            ->update(
                                array(
                                    'tarifa_recibo' => $tarifa_recibo,
                                    'cliente' => $cliente,
                                    'id_cruce_gestor' => $cruce_gestor,
                                    'fecha_visita_gestor' => $fecha
                                    ));
                        }
                    }
                }
                //Crea Detalle de NIC
                   // $i = 100000;
                
                $nicIngresados = Array();
                for ($i= 0 ; $i < count($results); $i++) 
                {
                    
                    if(!isset($results[$i]["nic"]) && !isset($results[$i]["unicom"]) && !isset($results[$i]["tipo_recibo"]) &&
                    !isset($results[$i]["cliente"]) && !isset($results[$i]["simbolo_variable"]))
                        continue;

                    if($results[$i]["nic"] == "" || $results[$i]["unicom"] == "" || $results[$i]["tipo_recibo"] == "" ||
                        $results[$i]["cliente"] == "" || $results[$i]["simbolo_variable"] == "")
                        continue;

                    $nic =  $results[$i]["nic"];
                    $unicom =  $results[$i]["unicom"];
                    $tipo_recibo =  $results[$i]["tipo_recibo"];
                    $estado_recibo =  $results[$i]["estado_recibo"];
                    $cobrado =  $results[$i]["cobrado"];
                    $simbolo_variable =  $results[$i]["simbolo_variable"];
                    $fecha_facturacion =  str_split($results[$i]["fecha_facturacion"]);
                    $fecha_facturacion = $fecha_facturacion[0] . $fecha_facturacion[1] . $fecha_facturacion[2] . $fecha_facturacion[3] . "-" . $fecha_facturacion[4] . $fecha_facturacion[5] . "-" . $fecha_facturacion[6] . $fecha_facturacion[7];
                    $fecha_vcto =  str_split($results[$i]["fecha_vcto"]);
                    $fecha_vcto = $fecha_vcto[0] . $fecha_vcto[1] . $fecha_vcto[2] . $fecha_vcto[3] . "-" . $fecha_vcto[4] . $fecha_vcto[5] . "-" . $fecha_vcto[6] . $fecha_vcto[7];
                    $importe =  $results[$i]["importe"];
                    $pendiente =  $results[$i]["pendiente"];
                    
                    $consulta = DB::Table('eca_gop_validador_detalle')
                            ->where('nic',$nic)
                            ->where('simbolo_variable',$simbolo_variable)
                            ->select('nic')
                            ->get();

                    if(count($consulta) == 0) //Insertar dato, no existe
                    {
                        DB::table('eca_gop_validador_detalle')
                        ->insert(array(
                            array(
                                'nic' => $nic,
                                'unicom' => $unicom,
                                'tipo_recibo' => $tipo_recibo,
                                'estado_recibo' => $estado_recibo,
                                'simbolo_variable' => $simbolo_variable,
                                'fecha_facturacion' => $fecha_facturacion,
                                'fecha_vencimiento' => $fecha_vcto,
                                'importe' => $importe,
                                'cobrado' => $cobrado,
                                'validacion' => ""
                                )));
                    }
                    else //Update Datos
                    {
                        DB::table('eca_gop_validador_detalle')
                        ->where('nic',$nic)
                        ->where('simbolo_variable',$simbolo_variable)
                        ->update(
                            array(
                                'unicom' => $unicom,
                                'tipo_recibo' => $tipo_recibo,
                                'estado_recibo' => $estado_recibo,
                                'simbolo_variable' => $simbolo_variable,
                                'fecha_facturacion' => $fecha_facturacion,
                                'fecha_vencimiento' => $fecha_vcto,
                                'importe' => $importe,
                                'cobrado' => $cobrado,
                                'validacion' => ""
                                ));
                    }
                }


                //DATOS DE LA HOJA OCULTA LLAMADA VALIDACION
                //Obtenemos fecha Actual
                $fechaActual = $this->fechaShort;
                //Formula traida del excel
                $dias5anios1mes = (365*5)+30;
                //Le restamos a la fecha actual, los 5años y 1 convertivo en meses
                $periodoPrescrita = strtotime("-$dias5anios1mes day", strtotime ( $fechaActual ) ) ;
                //Cambiamos la fecha que nos resulto y la colocamos en formato  Y-> Año - m-> Mes - d-> día
                $periodoPrescrita = date('Y-m-d',$periodoPrescrita);
                //Formula traida del excel
                $diasFinanciar = 18*30;
                //Sacamos primera fecha financiar, Fecha actual menos 30 días
                $fechaFinanciar1 = strtotime('-30 day', strtotime ( $fechaActual ) ) ;
                //Cambiamos la fecha que nos resulto y la colocamos en formato  Y-> Año - m-> Mes - d-> día
                $fechaFinanciar1 = date('Y-m-d',$fechaFinanciar1);
                //Sacamos segunda fecha financiar, Primera Fecha financiar -  Dias a fiananciar
                $fechaFinanciar2 = strtotime("-$diasFinanciar day", strtotime ( $fechaFinanciar1 ) ) ;
                //Cambiamos la fecha que nos resulto y la colocamos en formato  Y-> Año - m-> Mes - d-> día
                $fechaFinanciar2 = date('Y-m-d',$fechaFinanciar2);


                $nicIngresados1 = Array();
                //Consulta información para realizar la validación
                for ($i=0; $i < count($results); $i++) 
                {
                    if($results[$i]["nic"] == "" || $results[$i]["unicom"] == "" || $results[$i]["tipo_recibo"] == "" ||
                        $results[$i]["cliente"] == "" || $results[$i]["simbolo_variable"] == "")
                        continue;

                    $nic =  $results[$i]["nic"];
                    $nicExis = false;

                    for ($j=0; $j < count($nicIngresados1); $j++) { 
                        if($nicIngresados1[$j] == $nic)
                            $nicExis = true;
                    }

                    if(!$nicExis) // Si el NIC no existe, lo puedo ingresar
                    {
                        $consultaDatos = DB::Table('eca_gop_validador_detalle')
                                    ->where('nic',$nic)
                                    ->select('unicom','tipo_recibo','estado_recibo','fecha_facturacion',
                                        'fecha_vencimiento','simbolo_variable')
                                    ->orderBy('simbolo_variable','desc')
                                    ->get();

                        $fechaMayor = DB::Table('eca_gop_validador_detalle')
                                    ->where('nic',$nic)
                                    ->select(DB::raw('MAX(fecha_facturacion) as fecha_facturacion'))
                                    ->get()[0]->fecha_facturacion;
                        array_push($nicIngresados1,$nic);

                        $mesValor = 0;
                        $yaIngreso = 0;
                        $recorrido = 0;

                        foreach ($consultaDatos as $datos => $val) 
                        {
                            $dias   = (strtotime($fechaMayor)-strtotime($val->fecha_facturacion))/86400;
                            $dias   = abs($dias); $dias = floor($dias);     
                            //VALIDACIONES SEGÚN TABLA DINAMICA EXCEL
                            $validacion1 = $val->tipo_recibo. " " . $dias;
                            /*COMO HALLAR LOS OTROS CAMPOS DEL VALIDADOR*/
                            /*                      
                                Última periodo -> La fecha mayor anterior por NIC -> Q
                                Diferencia en días Las dos fechas -> R
                                Diferencia en meses -> S
                                VAL 1 : Concatenar C:R -> T
                                //HOJA OCULTA Validación 
                                    A -> Ahora
                                    B -> A - C
                                    C -> Dias 5 años y  1 mes =(365*5)+30
                                    D -> Dias a financiar -> =18*30
                                    E -> 
                                        SUBCOLUMNA 1 -> A - 30
                                        SUBCOLUMNA 2 -> E - D
                                VAL 2 : Validación : SI R >= HOJAOCULTA C ? 05 PRESCRITA : ''    ->  COLUMNA EXCEL U */
                            $validacion2 = "";
                           // $cuenta = $dias >= $dias5anios1mes;
                            if($dias >= $dias5anios1mes)
                                $validacion2 = "05 PRESCRITA";
                            
                             /*
                                VAL 3 : Validación 3 ->  COLUMNA EXCEL V

                                SI A == ""
                                    ""
                                ELSE
                                    SI C == "Irregularidad"
                                        "07 IRREGULARIDAD"    
                                    ELSE
                                        SI C == "Cargos varios"
                                           "03 CARGOS VARIOS"
                                        ELSE
                                            SI  IZQUIERDA(B;2)="55"
                                               "06 UNICOM 55"
                                            ELSE
                                                SI S == 0
                                                    "01 CUOTA INICIAL"
                                                ELSE
                                                    SI(R69>=VALIDACION C)
                                                      "05 PRESCRITA"
                                                    ELSE
                                                        SI(Y(S69>=1;S69<=18)
                                                            "02 A FINANCIAR"
                                                        ELSE
                                                           "04 SEGUNDO ACUERDO"       
                                
                                CALCULAR No. Mes
                            =SI(IZQUIERDA(B16;2)="55";*
                                S15;
                                SI(T16="Cargos varios 0";*
                                    -1;
                                    SI(C16="Cargos varios" ; *
                                        S15;
                                        SI(T16="Irregularidad 0";*
                                            -1;
                                            SI(C16="Irregularidad";
                                                S15;
                                                SI(C16="Irregularidad";
                                                    S15;
                                                    SI(R16=0;
                                                        0;
                                                    S15+1)))))))
                            */
                    

                            $mes = "";
                            if(strpos($val->unicom, "55") !== false)
                            {
                                $mes = $mesValor;
                            }
                            else
                            {
                                if($validacion1 == "Cargos varios 0")
                                {
                                    $mes = -1;
                                }
                                else
                                {
                                    if($val->tipo_recibo == "Cargos varios")
                                    {
                                        $mes = $mesValor;
                                    }
                                    else
                                    {
                                        if($validacion1 == "Irregularidad 0")
                                        {
                                            $mes = -1;
                                        }
                                        else
                                        {
                                            if($val->tipo_recibo == "Irregularidad")
                                            {
                                                $mes = $mesValor;
                                            }
                                            else
                                            {
                                                if($mesValor == 0 && $yaIngreso == 0 && $recorrido == 0)
                                                {
                                                    $mes = 0;
                                                    echo "INGRESO AQUI " . $mesValor . "<br>";
                                                    $yaIngreso  = 1;
                                                }else
                                                {
                                                    $mes = $mesValor + 1;
                                                    $mesValor++;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $recorrido++;
                            //echo "INGRESO FUERA " . $mesValor . "<br>";

                            $validacion3 = "";
                            if($nic == "")
                            {
                                $validacion3 = "";
                            }else
                            {
                                if($val->tipo_recibo == "Irregularidad")
                                {
                                    $validacion3 = "07 IRREGULARIDAD";
                                }else
                                {
                                    if($val->tipo_recibo == "Cargos varios")
                                    {
                                        $validacion3 = "03 CARGOS VARIOS";
                                    }else
                                    {
                                        if(strpos($val->unicom, "55") !== false)
                                        {
                                            $validacion3 = "06 UNICOM 55";
                                        }else
                                        {
                                            if($mes == 0)
                                            {
                                                $validacion3 = "01 CUOTA INICIAL";
                                            }else
                                            {
                                                if($dias >= $dias5anios1mes)
                                                {
                                                    $validacion3  = "05 PRESCRITA";
                                                }else
                                                {
                                                    if($mes >= 1 && $mes <= 18)
                                                    {
                                                        $validacion3 = "02 A FINANCIAR";
                                                    }else
                                                    {
                                                        $validacion3 = "04 SEGUNDO ACUERDO";
                                                    } 
                                                }
                                            }  
                                        }   
                                    }  
                                }
                            }

                             /*
                            VAL 4 : Validación 4 -> COLUMNA EXCEL W
                                
                                SI(Y(U="05 PRESCRITA";V="07 IRREGULARIDAD")
                                    "05.1 IRREGULARIDAD PRESCRITA"
                                ELSE 
                                    V
                            */

                            $validacion4 = "";
                            if($validacion2 == "05 PRESCRITA" && $validacion3 == "07 IRREGULARIDAD")
                                $validacion4 = "05.1 IRREGULARIDAD PRESCRITA";
                            else
                                $validacion4 = $validacion3;
                            
                           // echo $fechaMayor . "<<--$val->fecha_facturacion<<--" . $mes . "<<--$validacion4<<--$nic<<--<br>";

                            DB::Table('eca_gop_validador_detalle')
                                ->where('simbolo_variable',$val->simbolo_variable)
                                ->update(
                                    array(
                                        'validacion' => $validacion4
                                        ));
                            /*
                            Val Final : Validación Final -> X NO SE UTILIZA EN EL SISTEMA

                            SI(W="05 PRESCRITA")
                                "DESCUENTO INICIAL"
                            ELSE
                                SI(W49="05.1 IRREGULARIDAD PRESCRITA")
                                    "DESCUENTO INICIAL"
                                ELSE
                                    SI(W49="06 UNICOM 55")
                                        "DESCUENTO INICIAL"
                                    ELSE
                                        SI(W49="04 SEGUNDO ACUERDO")
                                            "DESCUENTO POR CUMPLIMIENTO"
                                        ELSE
                                            "A CANCELAR"                  
                        */
                        }
                    }
                }


                    



                var_dump("Se esta cargando el documento en excel, espero un momento");
                
                $arrWBS = Array();
                $arrNodos = Array();
                $arrNodos2 = Array();

                $consecutivoNODO = 0;
                $consecutivoMATE = 0;
            }
            //var_dump($results);
        })->get();
        
        //self::saveLog("OPERA04",Session::get('rds_gop_proyecto_id'),"");
        Session::flash('dataExcel1',"Se ha cargado correctamente el Excel.");

        //var_dump("todo BIEN");
       return Redirect::to('/electricaribe/validador/index');
    }

    /******** FIN FUNCIONES DEL CARGA MASIVO **************/
    /*************************************************/
    

    


    /** WEB SERVICES CAMPRO WEB ***/    
        public function consultaInfoVali(Request $request)
        {
            $opc = $request->all()["opc"];
            $retorno = "";
            if($opc == "1") //Consulta Información de Liquidación
            {
                $nic = $request->all()["nic"];                
                $retorno = DB::table('eca_gop_validador_detalle')
                            ->where('nic',$nic)
                            ->select('nic','unicom','tipo_recibo','estado_recibo','simbolo_variable',
                                'fecha_facturacion','fecha_vencimiento','importe','cobrado',
                                'validacion as val_4')
                            ->orderBy('simbolo_variable')
                            ->get();
            }

            if($opc == "2") //Save gestor
            {
                $nombre = $request->all()["nombre"];                
                $gestor = $request->all()["gestor"];                

                $gest = DB::table('eca_gop_gestor_validador')
                            ->where('id_cruce_gestor',$gestor)
                            ->select('id_cruce_gestor')
                            ->get();

                if(count($gest) == 0)
                {
                    DB::table('eca_gop_gestor_validador')
                            ->insert(array(
                                array(
                                    'id_cruce_gestor' => $gestor,
                                    'nombre_gestor' => $nombre
                                    )));
                    $retorno = "1";
                }
                else
                {
                    $retorno = "-1";
                }
            }

            if($opc == "3") //Update asiganción
            {
                $nic = $request->all()["nic"];                
                $gestor = $request->all()["gestor"];                
                $fecha = explode("/",$request->all()["fecha"]);                
                $fecha = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];

                DB::table('eca_gop_validador')
                    ->where('nic',$nic)
                    ->update(array(
                        'id_cruce_gestor' => $gestor,
                        'fecha_visita_gestor' => $fecha
                        ));

                $retorno = "1";
            }
            return response()->json($retorno);
        }
    /** FIN WEB SERVICES CAMPRO WEB ***/


    

    /** WEB SERVICES APLICACIÓN MÓVIL ***/
        public function consultaInfoValiMovil(Request $request)
        {
            $opc = $request->all()["opc"];
            $retorno = ""; 
            if($opc == 1) //Iniciar sessión
            {
                $gestor = $request->all()["gestor"];
                $gestorValidate = DB::table('eca_gop_gestor_validador')
                    ->where('id_cruce_gestor',$gestor)
                    ->select('nombre_gestor')
                    ->get();

                if(count($gestorValidate) == 0) //No Existe usuario
                    $retorno = [0,""];
                else
                    $retorno = [1,$gestorValidate[0]->nombre_gestor];
            }

            if($opc == 2) //Consulta todas las validaciones para el día de hoy
            {
                $gestor = $request->all()["gestor"];
                $gestorCabeza = DB::table('eca_gop_validador')
                    ->where('id_cruce_gestor',$gestor)
                    ->where('fecha_visita_gestor',$this->fechaShort)
                    ->select('nic','tarifa_recibo','cliente','fecha_visita_gestor','visitado')
                    ->get();

                // ->where('fecha_visita_gestor',$this->fechaShort)
                $gestorCuerpo = DB::table('eca_gop_validador')
                    ->join('eca_gop_validador_detalle','eca_gop_validador.nic','=','eca_gop_validador_detalle.nic')
                    ->where('id_cruce_gestor',$gestor)
                    ->where('fecha_visita_gestor',$this->fechaShort)
                    ->select('eca_gop_validador_detalle.nic','simbolo_variable','importe','cobrado','validacion')
                    ->get();

                
                $retorno = [$gestorCabeza,$gestorCuerpo];
            }

            if($opc == 3) //Consulta todas las validaciones para el día de hoy
            {
                $gestor = $request->all()["gestor"];
                $nic = $request->all()["nic"];

                $gestorCabeza = DB::table('eca_gop_validador')
                    ->where('id_cruce_gestor',$gestor)
                    ->where('nic',$nic)
                    ->update(
                        array(
                            'visitado' => 1
                            ));

                $retorno = "1";
            }


            return response()->json($retorno);
        }
    /** FIN MÓVIL SERVICES APLICACIÓN MÓVIL ***/
    

}
