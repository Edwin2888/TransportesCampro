<?php

namespace App\Http\Controllers\Transporte;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;//para usar BD
use Session;//Para las sesiones
use Carbon\Carbon;
use Response;


class ControllerWSTecnico extends Controller
{

    /*************************************************/
    /******** FUNCIONES DEL CONTROLADOR **************/
    function __construct() {    
        $this->valorT = "";
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    //Genera consecutivos
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

    //Rellena datos sobrantes
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
    /******** FIN FUNCIONES DEL CONTROLADOR **************/
    /*************************************************/
    

    /*****************************************************************************/
    /************ WEB SERVICES CAMPRO MÓVIL APP TECNICO - GUARDAR ****************/
    /*****************************************************************************/
    public function wsExtrasTransporte(Request $request)
    {
        if(!isset($request->all()["opc"]))
            return response()->json(array(
                    "id" => 0,
                    "res" => "No existe el parametro <b>opc</b>"));

        $opc = $request->all()["opc"];
        
        //Genera incidencia 
        if($opc == "1")
        {
            $placa = $request->all()["placa"];
            $km = $request->all()["km"];
            
            if(isset($request->all()["observacion"]))
                $obser = $request->all()["observacion"];
            else
                $obser = "";


            $gps = $request->all()["gps"];
            $tipo = $request->all()["tipo_novedad"];
            $lider = $request->all()["tecnico"];

            $prefijo = $request->all()["prefijo"];


            if(DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                        ->where('placa',$placa)
                        ->where('tipo_incidencia',$tipo)
                        ->where('id_estado','E01')
                        ->count() > 0 )
            {
                return response()->json(array(
                    "id" => 1,
                    "res" => "El vehícula ya cuenta con una incidencia generada, no se puede generar otra incidencia")); //Vehícula incidencia generada
            }
                


            if(DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->count() == 0)
                return response()->json(array(
                    "id" => -1,
                    "res" => "Placa no existe")); //No existe la placa


            if(DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->value('id_estado') == "E02")
                return response()->json(array(
                    "id" => -2,
                    "res" => "El vehículo se encuentra en estado RETIRADO, comuníquese con CENTRO DE CONTROL")); //Vehículo estado retirado


            
            /*
            if(DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->value('id_estado') == "E03")
                return response()->json(array(
                    "id" => -3,
                    "res" => "El vehículo se encuentra en estado MANTENIMIENTO, comuníquese con CENTRO DE CONTROL")); //Vehículo estado retirado
            */


            $km = intval($request->all()["km"]);


            //Consulta los últimos 20 records de Odometros
            $odometerArray = DB::connection('sqlsrvCxParque')
                            ->table('tra_vehiculo_odometro')
                            ->where('placa',$placa)
                            ->orderBy('fecha','desc')
                            ->select(DB::raw("TOP(1)fecha"),'kilometraje')
                            ->get();

            // validamos si debe ingresar kilometraje
            $sKilometaje = DB::connection('sqlsrvCxParque')
                ->table('tra_maestro as m')
                ->join('tra_contratantes as c','c.id','m.id_proyecto')
                ->join('campro.DBO.gop_proyectos as p','p.ccosto','c.ceco')
                ->where('m.placa',$placa)
                ->first();
            $kiloActual = 0;
            if(count($odometerArray) > 0){
                $kiloActual = $odometerArray[0]->kilometraje;
            }                
            
            
            
            if(!is_null($sKilometaje)){
                if($sKilometaje->km_parque == 'S'){
                    if($km <= $kiloActual){
                        DB::connection('sqlsrvCxParque')
                                ->table('tra_vehiculo_odometro')
                                ->insert(
                                    array(
                                        array(
                                            'placa' => $placa,
                                            'fecha_servidor' => $this->fechaALong,
                                            'fecha' => $this->fechaShort ,
                                            'kilometraje' => $km,
                                            'observaciones' => "Ingreso por aplicación Ejecución (Cuadrilla)",
                                            'usuario' => Session::get('user_login')
                                            )
                                        ));

                        return response()->json(array(
                            "id" => -4,
                            "res" => "El kilometraje que ingreso: $km KM es menor al último registrado: $kiloActual KM")); //No exste la placa
                    }
                    else{
                        // KM-NO                    
                        DB::connection('sqlsrvCxParque')
                                ->table('tra_vehiculo_odometro')
                                ->insert(
                                    array(
                                        array(
                                            'placa' => $placa,
                                            'fecha_servidor' => $this->fechaALong,
                                            'fecha' => $this->fechaShort ,
                                            'kilometraje' => $km,
                                            'observaciones' => "Ingreso por aplicación Ejecución (Cuadrilla)",
                                            'usuario' => $lider
                                            )
                                        ));
                    }
                }
            }


                /*            return response()->json(array(
                    "id" => 1,
                    "res" => "Se bloquea la generación de incidencias")); //Incidencia*/


            $inci = self::generaConsecutivo("ID_INCIDENCIA");

                    
            $compo = DB::connection('sqlsrvCxParque')
                        ->table('gop_arbol_decision')
                        ->where('padre',$tipo)
                        ->value('id');
            
            $tipoFalla = DB::connection('sqlsrvCxParque')
                        ->table('gop_arbol_decision')
                        ->where('padre',$compo)
                        ->value('id');
                        
            $resp = DB::connection('sqlsrvCxParque')
                        ->table('gop_arbol_decision')
                        ->where('padre',$tipoFalla)
                        ->select('id','inhabilita','tiempo_estimado')
                        ->get()[0];

            $movil = DB::table($prefijo . "_gop_cuadrilla")
                                ->where('id_lider',$lider)
                                ->value('id_movil');

            $nombreUser = DB::table("rh_personas")
                                ->where('identificacion',$lider)
                                ->get(['nombres','apellidos'])[0];


            if(isset($_REQUEST['respuesta_supervisor'])) {
              // Do nothing
            }
            else {
              DB::connection('sqlsrvCxParque')
                  ->table('tra_incidencia')
                  ->insert(array(
                          array(
                              'incidencia' => $inci,
                              'placa' => $placa,
                              'tipo_incidencia' => $tipo,
                              'novedadReportada' => $tipo,
                              'componente' => $compo,
                              'tipo_falla' => $tipoFalla,
                              'respuesta' => $resp->id,
                              'tiempo_estimado' => $resp->tiempo_estimado,
                              'inhabilitado' => $resp->inhabilita,
                              'fecha_servidor' => $this->fechaALong,
                              'observacion' => $obser . " - MÓVIL: " . $movil . " - LÍDER: " .  $nombreUser->apellidos . " " . $nombreUser->nombres,
                              'coordenadas' => $gps,
                              'id_estado' => "E01",
                              'km' => $km,
                              'usuario_crea' =>$lider,
                              'creacion' => 3
                              )
                      ));
            }
                
            return response()->json(array(
                    "id" => 1,
                    "res" => $inci,
                    "incidencia" => $inci
                    )); //Incidencia
        }

        //Consulta Kilometraje del vehículo
        if($opc == "2")
        {
            if(!isset($request->all()["placa"]))
                return response()->json(array(
                        "id" => -1,
                        "res" => "No existe el parametro <b>placa</b>"));


            $placa = $request->all()["placa"];

            if(DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->count() == 0)
                return response()->json(array(
                    "id" => -2,
                    "res" => "Placa no existe")); //No existe la placa

            if(DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->where('placa',$placa)
                        ->count() == 0)
                return response()->json(array(
                    "id" => -3,
                    "res" => "El vehículo no tiene ningún kilometraje")); //No tiene kilometrajes

            $res = DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->where('placa',$placa)
                        ->orderBy('id','desc')
                        ->get()[0];

            return response()->json(array(
                "id" => 1,
                "res" => $res)); //Retorna último kilometraje
        }
    }
    /*****************************************************************************/
    /********* FIN WEB SERVICES CAMPRO MÓVIL APP TECNICO - GUARDAR ***************/
    /*****************************************************************************/
}
