<?php

namespace App\Http\Controllers\Supervision;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Redirect;
use Session;


class ControllerPlanSupervision extends Controller
{
    
    function __construct() {      
        $this->fechaA = Carbon::now('America/Bogota');
        
        //Session::put('user_login',"U01853"); //Aleja

        //Session::put('user_login',"U00001"); //Carlos


        //Session::put('user_login',"U00664"); //Andres
        
        
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    private function saveLog($id_log,$campo_valor,$descripcion)
    {
        /*
        LOGS PROGRAMACIÓN
       
        */
        DB::table($this->tblAux1 . 'log_cambios')
                ->insert(array(
                        array(
                            'id_log' => $id_log,
                            'fecha' => $this->fechaALong,
                            'id_usuario' => Session::get('user_login'),
                            'campo_valor' => $campo_valor,
                            'descripcion' => $descripcion
                            )
                        ));

        return "1";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($aux = '', $anio = '', $mes = '')
    {

        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $datos = [];
        
        $fechaActual = explode("-",$this->fechaShort);

        if(!$anio) { $anio  = $fechaActual[0]; }
        if(!$mes)  { $mes   = $fechaActual[1]; }
        $lider = null;
        $colaboradores = null;

        $cuenta = str_replace("CO","",DB::table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    // ->where('id_usuario', 'U00352')
                    ->value('cuenta'));

        $usuarios = DB::Table('rh_personas')
                        ->where('identificacion',$cuenta)
                        ->select("nombres",'apellidos')
                        ->get()[0];

        

        $usuarios = $usuarios->nombres . ' ' . $usuarios->apellidos;

        $lider  = DB::Table('ins_lider_plan_supervision')
                    ->where('lider',$cuenta)
                    ->where('anio',$anio)
                    ->where('mes',$mes)
                    ->join('rh_personas','identificacion','=','lider')
                    ->select(DB::raw("(nombres + ' ' + apellidos) as nombre"),"nombre_equipo",
                        "comportamiento","ipales","calidad","ambiental","seguridad_obra_civil","telecomunicaciones","redes_electricas","kit_manejo_derrames",
                        "locativa_gestion_ambiental","entrega_obra_civil","restablecimiento_servicio","mantenimiento")
                    ->get();

        $cantColaIPAL = 0;
        $cantColaCali = 0;
        $cantColaObser = 0;
        $cantColaAmbiente = 0;
        $cantColaSeguridadObraCivil = 0;
        $cantColaTelecomunicaciones = 0;
        $cantColaRedesElectricas = 0;
        $cantColaKitManejoDerrames = 0;
        $cantColaLocativaGestionAmbiental = 0;
        $cantColaEntregaObraCivil = 0;
        $cantColaRestablecimientoServicios = 0;
        $cantColaMantenimiento = 0;

        $cantLiderIPAL = 0;
        $cantLiderCali = 0;
        $cantLiderObser = 0;
        $cantLiderAmbiente = 0;
        $cantLiderSeguridadObraCivil = 0;
        $cantLiderTelecomunicaciones = 0;
        $cantLiderRedesElectricas = 0;
        $cantLiderKitManejoDerrames = 0;
        $cantLiderLocativaGestionAmbiental = 0;
        $cantLiderEntregaObraCivil = 0;
        $cantLiderRestablecimientoServicios = 0;
        $cantLiderMantenimiento = 0;

        $cantTotalIpalCola = 0;
        $cantTotalCaliCola = 0;
        $cantTotalObserCola = 0;
        $cantTotalAmbienteCola = 0;
        $cantTotalSeguridadObraCivil = 0;
        $cantTotalTelecomunicacionesCola = 0;
        $cantTotalRedesElectricasCola = 0;
        $cantTotalKitManejoDerramesCola = 0;
        $cantTotalLocativaGestionAmbientalCola = 0;
        $cantTotalEntregaObraCivil = 0;
        $cantTotalRestablecimientoServicio = 0;
        $cantTotalMantenimiento = 0;

        $PcantTotalIpalCola = 0;
        $PcantTotalCaliCola = 0;
        $PcantTotalObserCola = 0;
        $PcantTotalAmbienteCola = 0;

        $PcantTotalSeguridadObraCivil = 0;
        $PcantTotalTelecomunicacionesCola = 0;
        $PcantTotalRedesElectricasCola = 0;
        $PcantTotalKitManejoDerramesCola = 0;
        $PcantTotalLocativaGestionAmbientalCola = 0;
        $PcantTotalEntregaObraCivil = 0;
        $PcantTotalRestablecimientoServicio = 0;
        $PcantTotalMantenimiento = 0;



        $inspeccionesColaboradores = [];


        $inspeccion = [];
        $tipo = 0;
        $lider_equipo = "";
        if(count($lider) > 0)
        {
            $tipo = 1;
            $lider = $lider[0];
            $cantLiderIPAL = DB::Table('ins_inspeccion')
                        ->where('anio',$anio)
                        ->where('mes',$mes)
                        ->where('tipo_inspeccion',1)
                        ->where('supervisor',$cuenta)
                        ->count();

            $cantLiderCali = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',2)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantLiderObser = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',3)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantLiderAmbiente = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',4)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantLiderSeguridadObraCivil = DB::Table('ins_inspeccion')
                ->where('anio',$anio)
                ->where('mes',$mes)
                ->where('tipo_inspeccion',33)
                ->where('supervisor',$cuenta)
                ->count();
            $cantLiderTelecomunicaciones = DB::Table('ins_inspeccion')
                ->where('anio',$anio)
                ->where('mes',$mes)
                ->where('tipo_inspeccion',34)
                ->where('supervisor',$cuenta)
                ->count();
            $cantLiderRedesElectricas = DB::Table('ins_inspeccion')
                ->where('anio',$anio)
                ->where('mes',$mes)
                ->where('tipo_inspeccion',35)
                ->where('supervisor',$cuenta)
                ->count();
            $cantLiderKitManejoDerrames = DB::Table('ins_inspeccion')
                ->where('anio',$anio)
                ->where('mes',$mes)
                ->where('tipo_inspeccion',43)
                ->where('supervisor',$cuenta)
                ->count();
            $cantLiderLocativaGestionAmbiental = DB::Table('ins_inspeccion')
                ->where('anio',$anio)
                ->where('mes',$mes)
                ->where('tipo_inspeccion',44)
                ->where('supervisor',$cuenta)
                ->count();
            $cantLiderEntregaObraCivil = DB::Table('ins_inspeccion')
                ->where('anio',$anio)
                ->where('mes',$mes)
                ->where('tipo_inspeccion',46)
                ->where('supervisor',$cuenta)
                ->count();
            $cantLiderRestablecimientoServicios = DB::Table('ins_inspeccion')
                ->where('anio',$anio)
                ->where('mes',$mes)
                ->where('tipo_inspeccion',36)
                ->where('supervisor',$cuenta)
                ->count();

            $cantLiderMantenimiento= DB::Table('ins_inspeccion')
                ->where('anio',$anio)
                ->where('mes',$mes)
                ->where('tipo_inspeccion',37)
                ->where('supervisor',$cuenta)
                ->count();

            $inspeccion = DB::table('ins_inspeccion')
                            ->leftJoin('gop_proyectos as pry1','pry1.id_proyecto','=','ins_inspeccion.id_tipo_proyecto')
                            ->leftJoin('gop_proyectos as pry2','pry2.id_proyecto','=','ins_inspeccion.id_proyecto')
                            ->leftJoin('rh_personas as p2','p2.identificacion','=','ins_inspeccion.lider')
                            ->join('ins_estados as es','es.id_estado','=','ins_inspeccion.estado')
                            ->join('ins_tipo_inspeccion as tipo','tipo.tipo','=','ins_inspeccion.tipo_inspeccion')
                            ->select('id_inspeccion','pry1.nombre',
                                'fecha_servidor',DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"),'supervisor','lider',
                                'movil','tipo_inspeccion','resultado','estado','es.nombre as nombreE'
                                ,'calificacion','pry2.nombre as ceco','tipo.nombre as tipoINs')
                            ->where('supervisor',$cuenta)
                            ->where('mes',$mes)
                            ->where('anio',$anio)
                            ->get();

            $aux  = 1;
            $colaboradores  = DB::Table('ins_colaborador_plan_supervision')
                    ->where('lider',$cuenta)
                    ->where('anio',$anio)
                    ->where('mes',$mes)
                    ->join('rh_personas','identificacion','=','colaborador')
                    ->select(DB::raw("(nombres + ' ' + apellidos) as nombre"),"nombre_equipo","seguridad_obra_civil","telecomunicaciones",
                        "redes_electricas","kit_manejo_derrames","locativa_gestion_ambiental","entrega_obra_civil","restablecimiento_servicio","mantenimiento",
                        "comportamiento","ipales","calidad","ambiental","colaborador",DB::raw("'' as ipalEje")
                        ,DB::raw("'' as caliEje"),DB::raw("'' as obseEje"),DB::raw("'' as ambiEje")
                        ,DB::raw("'' as locaEje"),DB::raw("'' as kitEje"))
                    ->get();


            $inspeccionesColaboradores = DB::table('ins_inspeccion')
                                        ->leftJoin('gop_proyectos as pry1','pry1.id_proyecto','=','ins_inspeccion.id_tipo_proyecto')
                                        ->leftJoin('gop_proyectos as pry2','pry2.id_proyecto','=','ins_inspeccion.id_proyecto')
                                        ->leftjoin('rh_personas as p2','p2.identificacion','=','ins_inspeccion.lider')
                                        ->join('ins_estados as es','es.id_estado','=','ins_inspeccion.estado')
                                        ->join('ins_colaborador_plan_supervision as cola','cola.colaborador','=','supervisor')
                                        ->join('rh_personas as p3','p3.identificacion','=','cola.colaborador')
                                        ->join('ins_tipo_inspeccion as tipo','tipo.tipo','=','ins_inspeccion.tipo_inspeccion')
                                        ->select('id_inspeccion','pry1.nombre',
                                            'fecha_servidor',DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"),DB::raw("p3.nombres + ' ' + p3.apellidos as nombreSuper"),'ins_inspeccion.lider',
                                            'movil','tipo_inspeccion','resultado','estado','es.nombre as nombreE'
                                            ,'calificacion','pry2.nombre as ceco','tipo.nombre as tipoINs')
                                        ->where('cola.lider',$cuenta)
                                        ->where('ins_inspeccion.mes',$mes)
                                        ->where('ins_inspeccion.anio',$anio)
                                        ->groupBY('id_inspeccion','pry1.nombre','tipo.nombre',
                                            'fecha_servidor',"p2.nombres","p2.apellidos","p3.nombres","p3.apellidos",
                                            'movil','tipo_inspeccion','resultado','estado','es.nombre','ins_inspeccion.lider'
                                            ,'calificacion','pry2.nombre')
                                        ->get();

            //dd($inspeccionesColaboradores);


            for ($i=0; $i < count($colaboradores); $i++) {

                    $PcantTotalIpalCola += intval($colaboradores[$i]->ipales);
                    $PcantTotalCaliCola += intval($colaboradores[$i]->calidad);
                    $PcantTotalObserCola += intval($colaboradores[$i]->comportamiento);
                    $PcantTotalAmbienteCola += intval(($colaboradores[$i]->ambiental == null || $colaboradores[$i]->ambiental = ''? 0 : $colaboradores[$i]->ambiental));
                    $PcantTotalSeguridadObraCivil += intval(($colaboradores[$i]->seguridad_obra_civil == null || $colaboradores[$i]->seguridad_obra_civil == '' ? 0 : $colaboradores[$i]->seguridad_obra_civil));
                    $PcantTotalTelecomunicacionesCola += intval(($colaboradores[$i]->telecomunicaciones == null || $colaboradores[$i]->telecomunicaciones == '' ? 0 : $colaboradores[$i]->telecomunicaciones));
                    $PcantTotalRedesElectricasCola += intval(($colaboradores[$i]->redes_electricas == null || $colaboradores[$i]->redes_electricas == '' ? 0 : $colaboradores[$i]->redes_electricas));
                    $PcantTotalKitManejoDerramesCola += intval(($colaboradores[$i]->kit_manejo_derrames == null || $colaboradores[$i]->kit_manejo_derrames == '' ? 0 : $colaboradores[$i]->kit_manejo_derrames));
                    $PcantTotalLocativaGestionAmbientalCola += intval(($colaboradores[$i]->locativa_gestion_ambiental == null || $colaboradores[$i]->locativa_gestion_ambiental == '' ? 0 : $colaboradores[$i]->locativa_gestion_ambiental));
                    $PcantTotalEntregaObraCivil += intval(($colaboradores[$i]->entrega_obra_civil == null || $colaboradores[$i]->entrega_obra_civil == '' ? 0 : $colaboradores[$i]->entrega_obra_civil));
                    $PcantTotalRestablecimientoServicio += intval(($colaboradores[$i]->restablecimiento_servicio == null || $colaboradores[$i]->restablecimiento_servicio == '' ? 0 : $colaboradores[$i]->restablecimiento_servicio));
                    $PcantTotalMantenimiento += intval(($colaboradores[$i]->mantenimiento == null || $colaboradores[$i]->mantenimiento == '' ? 0 : $colaboradores[$i]->mantenimiento));

                    $cantTotalIpalCola += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',1)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();

                    $colaboradores[$i]->ipalEje =  DB::Table('ins_inspeccion')
                                                    ->where('anio',$anio)
                                                    ->where('mes',$mes)
                                                    ->where('tipo_inspeccion',1)
                                                    ->where('supervisor',$colaboradores[$i]->colaborador)
                                                    ->count();

                    $cantTotalCaliCola += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',2)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();

                    $colaboradores[$i]->caliEje =  DB::Table('ins_inspeccion')
                                                    ->where('anio',$anio)
                                                    ->where('mes',$mes)
                                                    ->where('tipo_inspeccion',2)
                                                    ->where('supervisor',$colaboradores[$i]->colaborador)
                                                    ->count();


                    $cantTotalObserCola += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',3)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count(); 

                    $colaboradores[$i]->obseEje =  DB::Table('ins_inspeccion')
                                                    ->where('anio',$anio)
                                                    ->where('mes',$mes)
                                                    ->where('tipo_inspeccion',3)
                                                    ->where('supervisor',$colaboradores[$i]->colaborador)
                                                    ->count();

                    $cantTotalAmbienteCola += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',4)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $cantTotalSeguridadObraCivil  += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',33)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $cantTotalTelecomunicacionesCola  += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',34)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $cantTotalRedesElectricasCola  += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',35)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $cantTotalKitManejoDerramesCola  += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',43)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $cantTotalLocativaGestionAmbientalCola  += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',44)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $cantTotalEntregaObraCivil   += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',46)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $cantTotalRestablecimientoServicio  += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',36)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $cantTotalMantenimiento   += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',37)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();
                    $colaboradores[$i]->ambiEje =  DB::Table('ins_inspeccion')
                                                    ->where('anio',$anio)
                                                    ->where('mes',$mes)
                                                    ->where('tipo_inspeccion',4)
                                                    ->where('supervisor',$colaboradores[$i]->colaborador)
                                                    ->count();

                    $colaboradores[$i]->kitEje =  DB::Table('ins_inspeccion')
                        ->where('anio',$anio)
                        ->where('mes',$mes)
                        ->where('tipo_inspeccion',43)
                        ->where('supervisor',$colaboradores[$i]->colaborador)
                        ->count();

                    $colaboradores[$i]->locaEje =  DB::Table('ins_inspeccion')
                        ->where('anio',$anio)
                        ->where('mes',$mes)
                        ->where('tipo_inspeccion',44)
                        ->where('supervisor',$colaboradores[$i]->colaborador)
                        ->count();


            }
        }
        else
        {
            $aux  = 2;


            $colaboradores  = DB::Table('ins_colaborador_plan_supervision')
                    ->where('colaborador',$cuenta)
                    ->where('anio',$anio)
                    ->where('mes',$mes)
                    ->join('rh_personas','identificacion','=','colaborador')
                    ->select(DB::raw("(nombres + ' ' + apellidos) as nombre"),"nombre_equipo",
                        "comportamiento","ipales","calidad","ambiental","lider","seguridad_obra_civil","telecomunicaciones","kit_manejo_derrames",
                        "locativa_gestion_ambiental","entrega_obra_civil","restablecimiento_servicio","mantenimiento","redes_electricas")
                    ->get();

            if(count($colaboradores) > 0)
            {

                $lider_equipo = DB::Table('rh_personas')
                        ->where('identificacion',$colaboradores[0]->lider)
                        ->select("nombres",'apellidos')
                        ->get()[0];


                $lider_equipo = $lider_equipo->nombres . ' ' . $lider_equipo->apellidos;


                $tipo = 1;


                 $inspeccion = DB::table('ins_inspeccion')
                        ->leftJoin('gop_proyectos as pry1','pry1.id_proyecto','=','ins_inspeccion.id_tipo_proyecto')
                        ->leftJoin('gop_proyectos as pry2','pry2.id_proyecto','=','ins_inspeccion.id_proyecto')
                        ->rightjoin('rh_personas as p2','p2.identificacion','=','ins_inspeccion.lider')
                        ->join('ins_estados as es','es.id_estado','=','ins_inspeccion.estado')
                        ->select('id_inspeccion','pry1.nombre',
                            'fecha_servidor',DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"),'supervisor','lider',
                            'movil','tipo_inspeccion','resultado','estado','es.nombre as nombreE'
                            ,'calificacion','pry2.nombre as ceco')
                        ->where('supervisor',$cuenta)
                        ->where('mes',$mes)
                        ->where('anio',$anio)
                        ->get();

            }
            else
            {
                $tipo = 0;
            }


            $cantColaIPAL = DB::Table('ins_inspeccion')
                        ->where('anio',$anio)
                        ->where('mes',$mes)
                        ->where('tipo_inspeccion',1)
                        ->where('supervisor',$cuenta)
                        ->count();

            $cantColaCali = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',2)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantColaObser = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',3)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantColaAmbiente = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',4)
                            ->where('supervisor',$cuenta)
                            ->count();
            $cantColaSeguridadObraCivil = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',33)
                            ->where('supervisor',$cuenta)
                            ->count();
            $cantColaTelecomunicaciones = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',34)
                            ->where('supervisor',$cuenta)
                            ->count();
            $cantColaRedesElectricas = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',35)
                            ->where('supervisor',$cuenta)
                            ->count();
            $cantColaKitManejoDerrames = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',43)
                            ->where('supervisor',$cuenta)
                            ->count();
            $cantColaLocativaGestionAmbiental = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',44)
                            ->where('supervisor',$cuenta)
                            ->count();
            $cantColaEntregaObraCivil = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',46)
                            ->where('supervisor',$cuenta)
                            ->count();
            $cantColaRestablecimientoServicios = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',36)
                            ->where('supervisor',$cuenta)
                            ->count();
            $cantColaMantenimiento = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',37)
                            ->where('supervisor',$cuenta)
                            ->count();
        }

        $nombre_mes = '';

        switch($mes) {
            case 1: $nombre_mes = 'ENERO'; break;
            case 2: $nombre_mes = 'FEBRERO'; break;
            case 3: $nombre_mes = 'MARZO'; break;
            case 4: $nombre_mes = 'ABRIL'; break;
            case 5: $nombre_mes = 'MAYO'; break;
            case 6: $nombre_mes = 'JUNIO'; break;
            case 7: $nombre_mes = 'JULIO'; break;
            case 8: $nombre_mes = 'AGOSTO'; break;
            case 9: $nombre_mes = 'SEPTIEMBRE'; break;
            case 10: $nombre_mes = 'OCTUBRE'; break;
            case 11: $nombre_mes = 'NOVIEMBRE'; break;
            case 12: $nombre_mes = 'DICIEMBRE'; break;
        }

        return view('proyectos.supervisor.plansupervision.index',array(
                        "datos" => $datos,
                        'anio' => $anio,
                        'mes' => $mes,
                        'nombre_mes' => $nombre_mes,
                        'aux' => $aux,
                        'usuario' => $usuarios,
                        'lider' => $lider,
                        'colaboradores' => $colaboradores,
                        'cantLiderIPAL' => $cantLiderIPAL,
                        'cantLiderCali' => $cantLiderCali,
                        'cantLiderObser' => $cantLiderObser,
                        'cantLiderAmbiente' => $cantLiderAmbiente,
                        'cantLiderSeguridadObraCivil' =>$cantLiderSeguridadObraCivil,
                        'cantLiderTelecomunicaciones' => $cantLiderTelecomunicaciones,
                        'cantLiderRedesElectricas' => $cantLiderRedesElectricas,
                        'cantLiderKitManejoDerrames' => $cantLiderKitManejoDerrames,
                        'cantLiderEntregaObraCivil' => $cantLiderEntregaObraCivil,
                        'cantLiderRestablecimientoServicios' => $cantLiderRestablecimientoServicios,
                        'cantLiderLocativaGestionAmbiental' => $cantLiderLocativaGestionAmbiental,
                        'cantLiderMantenimiento' => $cantLiderMantenimiento,
                        'cantColaIPAL' => $cantColaIPAL,
                        'cantColaCali' => $cantColaCali,
                        'cantColaObser' => $cantColaObser,
                        'cantColaAmbiente' => $cantColaAmbiente,
                        'cantColaSeguridadObraCivil' =>$cantColaSeguridadObraCivil,
                        'cantColaTelecomunicaciones' => $cantColaTelecomunicaciones,
                        'cantColaRedesElectricas' => $cantColaRedesElectricas,
                        'cantColaKitManejoDerrames' => $cantColaKitManejoDerrames,
                        'cantColaEntregaObraCivil' => $cantColaEntregaObraCivil,
                        'cantColaRestablecimientoServicios' => $cantColaRestablecimientoServicios,
                        'cantColaLocativaGestionAmbiental' => $cantColaLocativaGestionAmbiental,
                        'cantColaMantenimiento' => $cantColaMantenimiento,
                        'inspeccion' => $inspeccion,
                        'tipo_login' => $tipo,
                        'lider_equipo' => $lider_equipo,
                        'cantTotalIpalCola' => $cantTotalIpalCola,
                        'cantTotalCaliCola' => $cantTotalCaliCola,
                        'cantTotalObserCola' => $cantTotalObserCola,
                        'cantTotalAmbienteCola' => $cantTotalAmbienteCola,
                        'cantTotalSeguridadObraCivil' => $cantTotalSeguridadObraCivil,
                        'cantTotalTelecomunicacionesCola' =>$cantTotalTelecomunicacionesCola,
                        'cantTotalRedesElectricasCola'=>$cantTotalRedesElectricasCola,
                        'cantTotalKitManejoDerramesCola'=>$cantTotalKitManejoDerramesCola,
                        'cantTotalLocativaGestionAmbientalCola' =>$cantTotalLocativaGestionAmbientalCola,
                        'cantTotalEntregaObraCivil' => $cantTotalEntregaObraCivil,
                        'cantTotalRestablecimientoServicio'=>$cantTotalRestablecimientoServicio,
                        'cantTotalMantenimiento'=>$cantTotalMantenimiento,
                        'PcantTotalIpalCola' => $PcantTotalIpalCola,
                        'PcantTotalCaliCola' => $PcantTotalCaliCola,
                        'PcantTotalObserCola' => $PcantTotalObserCola,
                        'PcantTotalAmbienteCola' => $PcantTotalAmbienteCola,
                        'PcantTotalSeguridadObraCivil' => $PcantTotalSeguridadObraCivil,
                        'PcantTotalTelecomunicacionesCola' =>$PcantTotalTelecomunicacionesCola,
                        'PcantTotalRedesElectricasCola' => $PcantTotalRedesElectricasCola,
                        'PcantTotalKitManejoDerramesCola' => $PcantTotalKitManejoDerramesCola,
                        'PcantTotalLocativaGestionAmbientalCola' => $PcantTotalLocativaGestionAmbientalCola,
                        'PcantTotalEntregaObraCivil' => $PcantTotalEntregaObraCivil,
                        'PcantTotalRestablecimientoServicio' => $PcantTotalRestablecimientoServicio,
                        'PcantTotalMantenimiento' => $PcantTotalMantenimiento,
                        'inspeccionColaboradores' => $inspeccionesColaboradores
                    ));
    }
}



/*

Modificación: Alejandra Quintero
FEcha: 01-12-2019
Descripción: Se comentaria este código por que esta duplicado 

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Redirect;
use Session;


class ControllerPlanSupervision extends Controller
{
    
    function __construct() {      
        $this->fechaA = Carbon::now(-5);
        //Session::put('user_login',"U01853"); //Aleja

        Session::put('user_login',"U00001"); //Carlos


        //Session::put('user_login',"U00664"); //Andres
        
        
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    private function saveLog($id_log,$campo_valor,$descripcion)
    {
        DB::table($this->tblAux1 . 'log_cambios')
                ->insert(array(
                        array(
                            'id_log' => $id_log,
                            'fecha' => $this->fechaALong,
                            'id_usuario' => Session::get('user_login'),
                            'campo_valor' => $campo_valor,
                            'descripcion' => $descripcion
                            )
                        ));

        return "1";
    }

    public function index($aux = '')
    {
       
        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $datos = [];
        
        $fechaActual = explode("-",$this->fechaShort);
        $anio = $fechaActual[0];
        $mes = $fechaActual[1];
        $lider = null;
        $colaboradores = null;

        $cuenta = str_replace("CO","",DB::table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    ->value('cuenta'));
        
        $usuarios = DB::Table('rh_personas')
                        ->where('identificacion',$cuenta)
                        ->select("nombres",'apellidos')
                        ->get()[0];

        $usuarios = $usuarios->nombres . ' ' . $usuarios->apellidos;

        $lider  = DB::Table('ins_lider_plan_supervision')
                    ->where('lider',$cuenta)
                    ->where('anio',$anio)
                    ->where('mes',$mes)
                    ->join('rh_personas','identificacion','=','lider')
                    ->select(DB::raw("(nombres + ' ' + apellidos) as nombre"),"nombre_equipo",
                        "comportamiento","ipales","calidad","ambiental")
                    ->get();

        $cantColaIPAL = 0;
        $cantColaCali = 0;
        $cantColaObser = 0;
        $cantColaAmbiente = 0;

        $cantLiderIPAL = 0;
        $cantLiderCali = 0;
        $cantLiderObser = 0;
        $cantLiderAmbiente = 0;

        $cantTotalIpalCola = 0;
        $cantTotalCaliCola = 0;
        $cantTotalObserCola = 0;
        $cantTotalAmbienteCola = 0;

        $PcantTotalIpalCola = 0;
        $PcantTotalCaliCola = 0;
        $PcantTotalObserCola = 0;
        $PcantTotalAmbienteCola = 0;

        $inspeccionesColaboradores = [];


        $inspeccion = [];
        $tipo = 0;
        $lider_equipo = "";
        if(count($lider) > 0)
        {
            $tipo = 1;
            $lider = $lider[0];
            $cantLiderIPAL = DB::Table('ins_inspeccion')
                        ->where('anio',$anio)
                        ->where('mes',$mes)
                        ->where('tipo_inspeccion',1)
                        ->where('supervisor',$cuenta)
                        ->count();

            $cantLiderCali = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',2)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantLiderObser = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',3)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantLiderAmbiente = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',4)
                            ->where('supervisor',$cuenta)
                            ->count();

            $inspeccion = DB::table('ins_inspeccion')
                            ->join('gop_proyectos','gop_proyectos.id_proyecto','=','ins_inspeccion.id_tipo_proyecto')
                            ->rightjoin('rh_personas as p2','p2.identificacion','=','ins_inspeccion.lider')
                            ->join('ins_estados as es','es.id_estado','=','ins_inspeccion.estado')
                            ->select('id_inspeccion','gop_proyectos.nombre',
                                'fecha_servidor',DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"),'supervisor','lider',
                                'movil','tipo_inspeccion','resultado','estado','es.nombre as nombreE'
                                ,'calificacion')
                            ->where('supervisor',$cuenta)
                            ->where('mes',$mes)
                            ->where('anio',$anio)
                            ->get();

            $aux  = 1;
            $colaboradores  = DB::Table('ins_colaborador_plan_supervision')
                    ->where('lider',$cuenta)
                    ->where('anio',$anio)
                    ->where('mes',$mes)
                    ->join('rh_personas','identificacion','=','colaborador')
                    ->select(DB::raw("(nombres + ' ' + apellidos) as nombre"),"nombre_equipo",
                        "comportamiento","ipales","calidad","ambiental","colaborador",DB::raw("'' as ipalEje")
                        ,DB::raw("'' as caliEje"),DB::raw("'' as obseEje"),DB::raw("'' as ambiEje"))
                    ->get();


            $inspeccionesColaboradores = DB::table('ins_inspeccion')
                                        ->join('gop_proyectos','gop_proyectos.id_proyecto','=','ins_inspeccion.id_tipo_proyecto')
                                        ->rightjoin('rh_personas as p2','p2.identificacion','=','ins_inspeccion.lider')
                                        ->join('ins_estados as es','es.id_estado','=','ins_inspeccion.estado')
                                        ->join('ins_colaborador_plan_supervision as cola','cola.colaborador','=','supervisor')
                                        ->join('rh_personas as p3','p3.identificacion','=','cola.colaborador')
                                        ->select('id_inspeccion','gop_proyectos.nombre',
                                            'fecha_servidor',DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"),DB::raw("p3.nombres + ' ' + p3.apellidos as nombreSuper"),'ins_inspeccion.lider',
                                            'movil','tipo_inspeccion','resultado','estado','es.nombre as nombreE'
                                            ,'calificacion')
                                        ->where('cola.lider',$cuenta)
                                        ->where('ins_inspeccion.mes',$mes)
                                        ->where('ins_inspeccion.anio',$anio)
                                        ->groupBY('id_inspeccion','gop_proyectos.nombre',
                                            'fecha_servidor',"p2.nombres","p2.apellidos","p3.nombres","p3.apellidos",
                                            'movil','tipo_inspeccion','resultado','estado','es.nombre','ins_inspeccion.lider'
                                            ,'calificacion')
                                        ->get();

            //dd($inspeccionesColaboradores);


            for ($i=0; $i < count($colaboradores); $i++) { 
                    
                    $PcantTotalIpalCola += intval($colaboradores[$i]->ipales);
                    $PcantTotalCaliCola += intval($colaboradores[$i]->calidad);
                    $PcantTotalObserCola += intval($colaboradores[$i]->comportamiento);
                    $PcantTotalAmbienteCola += intval(($colaboradores[$i]->ambiental == null || $colaboradores[$i]->ambiental = ''? 0 : $colaboradores[$i]->ambiental));


                    $cantTotalIpalCola += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',1)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();

                    $colaboradores[$i]->ipalEje =  DB::Table('ins_inspeccion')
                                                    ->where('anio',$anio)
                                                    ->where('mes',$mes)
                                                    ->where('tipo_inspeccion',1)
                                                    ->where('supervisor',$colaboradores[$i]->colaborador)
                                                    ->count();

                    $cantTotalCaliCola += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',2)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();

                    $colaboradores[$i]->caliEje =  DB::Table('ins_inspeccion')
                                                    ->where('anio',$anio)
                                                    ->where('mes',$mes)
                                                    ->where('tipo_inspeccion',2)
                                                    ->where('supervisor',$colaboradores[$i]->colaborador)
                                                    ->count();


                    $cantTotalObserCola += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',3)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count(); 

                    $colaboradores[$i]->obseEje =  DB::Table('ins_inspeccion')
                                                    ->where('anio',$anio)
                                                    ->where('mes',$mes)
                                                    ->where('tipo_inspeccion',3)
                                                    ->where('supervisor',$colaboradores[$i]->colaborador)
                                                    ->count();

                    $cantTotalAmbienteCola += DB::Table('ins_inspeccion')
                                            ->where('anio',$anio)
                                            ->where('mes',$mes)
                                            ->where('tipo_inspeccion',4)
                                            ->where('supervisor',$colaboradores[$i]->colaborador)
                                            ->count();

                    $colaboradores[$i]->ambiEje =  DB::Table('ins_inspeccion')
                                                    ->where('anio',$anio)
                                                    ->where('mes',$mes)
                                                    ->where('tipo_inspeccion',4)
                                                    ->where('supervisor',$colaboradores[$i]->colaborador)
                                                    ->count();

            }
        }
        else
        {
            $aux  = 2;
            $colaboradores  = DB::Table('ins_colaborador_plan_supervision')
                    ->where('colaborador',$cuenta)
                    ->where('anio',$anio)
                    ->where('mes',$mes)
                    ->join('rh_personas','identificacion','=','colaborador')
                    ->select(DB::raw("(nombres + ' ' + apellidos) as nombre"),"nombre_equipo",
                        "comportamiento","ipales","calidad","ambiental","lider")
                    ->get();

            if(count($colaboradores) > 0)
            {

                $lider_equipo = DB::Table('rh_personas')
                        ->where('identificacion',$colaboradores[0]->lider)
                        ->select("nombres",'apellidos')
                        ->get()[0];

                $lider_equipo = $lider_equipo->nombres . ' ' . $lider_equipo->apellidos;


                $tipo = 1;

                 $inspeccion = DB::table('ins_inspeccion')
                        ->join('gop_proyectos','gop_proyectos.id_proyecto','=','ins_inspeccion.id_tipo_proyecto')
                        ->rightjoin('rh_personas as p2','p2.identificacion','=','ins_inspeccion.lider')
                        ->join('ins_estados as es','es.id_estado','=','ins_inspeccion.estado')
                        ->select('id_inspeccion','gop_proyectos.nombre',
                            'fecha_servidor',DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"),'supervisor','lider',
                            'movil','tipo_inspeccion','resultado','estado','es.nombre as nombreE'
                            ,'calificacion')
                        ->where('supervisor',$cuenta)
                        ->where('mes',$mes)
                        ->where('anio',$anio)
                        ->get();
            }
            else
            {
                $tipo = 0;
            }

            $cantColaIPAL = DB::Table('ins_inspeccion')
                        ->where('anio',$anio)
                        ->where('mes',$mes)
                        ->where('tipo_inspeccion',1)
                        ->where('supervisor',$cuenta)
                        ->count();

            $cantColaCali = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',2)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantColaObser = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',3)
                            ->where('supervisor',$cuenta)
                            ->count();

            $cantColaAmbiente = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',4)
                            ->where('supervisor',$cuenta)
                            ->count();
        }

        return view('proyectos.supervisor.plansupervision.index',array(
                        "datos" => $datos,
                        'anio' => $anio,
                        'mes' => $mes,
                        'aux' => $aux,
                        'usuario' => $usuarios,
                        'lider' => $lider,
                        'colaboradores' => $colaboradores,
                        'cantLiderIPAL' => $cantLiderIPAL,
                        'cantLiderCali' => $cantLiderCali,
                        'cantLiderObser' => $cantLiderObser,
                        'cantLiderAmbiente' => $cantLiderAmbiente,
                        'cantColaIPAL' => $cantColaIPAL,
                        'cantColaCali' => $cantColaCali,
                        'cantColaObser' => $cantColaObser,
                        'cantColaAmbiente' => $cantColaAmbiente,
                        'inspeccion' => $inspeccion,
                        'tipo_login' => $tipo,
                        'lider_equipo' => $lider_equipo,
                        'cantTotalIpalCola' => $cantTotalIpalCola,
                        'cantTotalCaliCola' => $cantTotalCaliCola,
                        'cantTotalObserCola' => $cantTotalObserCola,
                        'cantTotalAmbienteCola' => $cantTotalAmbienteCola,
                        'PcantTotalIpalCola' => $PcantTotalIpalCola,
                        'PcantTotalCaliCola' => $PcantTotalCaliCola,
                        'PcantTotalObserCola' => $PcantTotalObserCola,
                        'PcantTotalAmbienteCola' => $PcantTotalAmbienteCola,
                        'inspeccionColaboradores' => $inspeccionesColaboradores
                    ));
    }
}
*/