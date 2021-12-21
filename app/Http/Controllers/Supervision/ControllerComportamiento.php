<?php

namespace App\Http\Controllers\Supervision;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Redirect;
use Session;
/*
    Controlador utilizado para la conformación de grupos
*/

class ControllerComportamiento extends Controller
{

    function __construct() {      
    
        $this->fechaA = Carbon::now('America/Bogota');
        //Session::put('user_login',"U01853"); //Aleja
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
    public function index()
    {
        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');


        $datos = [];
        if(Session::has('mes'))
        {
            $consulAux = "";
            

            $consulAux = " insLider.anio = "  . Session::get('anio')  . " AND insLider.mes = " . Session::get('mes') . " ";
        
            $consulAux .= " and (liderRH.nombres + ' ' + liderRH.apellidos) LIKE '%"  . Session::get('lider')  . "%' AND insLider.nombre_equipo LIKE '%" . Session::get('equipo') . "%' ";
        
            $anio_param = Session::get('anio');    
            $mes_param  = Session::get('mes');    

            $consulta = "SELECT 
                            insLider.id,
                            insLider.lider,
                            COUNT(insSuper.lider) as cantColaboradores,
                            SUM(insSuper.comportamiento) as cantCompor,
                            SUM(insSuper.ipales)as cantIpal,
                            SUM(insSuper.calidad) as cantSegu,
                            SUM(insSuper.ambiental) as cantAmbi,
                            insLider.comportamiento as cantComporL,
                            insLider.ipales as cantIpalL,
                            insLider.calidad as cantSeguL,
                            insLider.ambiental as cantAmbiL,
                
                            (
                                SELECT
                                    COUNT(*)
                                FROM ins_inspeccion
                                WHERE 1 = 1
                                    AND supervisor = insLider.lider
                                    AND anio = '{$anio_param}'
                                    AND mes = '{$mes_param}'
                                    AND tipo_inspeccion = 3
                            ) AS cantComporCumplido,
                            
                            (
                                SELECT
                                    COUNT(*)
                                FROM ins_inspeccion
                                WHERE 1 = 1
                                    AND supervisor = insLider.lider
                                    AND anio = '{$anio_param}'
                                    AND mes = '{$mes_param}'
                                    AND tipo_inspeccion = 1
                            ) AS cantIpalCumplido,
                            
                            (
                                SELECT
                                    COUNT(*)
                                FROM ins_inspeccion
                                WHERE 1 = 1
                                    AND supervisor = insLider.lider
                                    AND anio = '{$anio_param}'
                                    AND mes = '{$mes_param}'
                                    AND tipo_inspeccion = 2
                            ) AS cantSeguCumplido,

                            (
                                SELECT
                                    COUNT(*)
                                FROM ins_inspeccion
                                WHERE 1 = 1
                                    AND supervisor = insLider.lider
                                    AND anio = '{$anio_param}'
                                    AND mes = '{$mes_param}'
                                    AND tipo_inspeccion = 4
                            ) AS cantAmbiCumplido,

                            (liderRH.nombres + ' ' + liderRH.apellidos) as nombreL,
                            insLider.nombre_equipo,
                            insLider.anio,
                            insLider.mes
                        FROM ins_lider_plan_supervision AS insLider

                            INNER JOIN ins_colaborador_plan_supervision AS insSuper 
                                ON insLider.lider = insSuper.lider
                                AND insSuper.mes = insLider.mes
                                AND insSuper.anio = insLider.anio
                
                            INNER JOIN rh_personas AS liderRH 
                                ON liderRH.identificacion = insLider.lider
                
                        WHERE $consulAux
                        GROUP BY 
                            insLider.ambiental,
                            insLider.nombre_equipo,
                            insLider.anio,
                            insLider.mes,
                            insLider.lider,
                            liderRH.nombres,
                            liderRH.apellidos,
                            insLider.comportamiento,
                            insLider.ipales,
                            insLider.calidad,
                            insLider.id 
                        ORDER BY insLider.id";

                $datos = DB::select($consulta);

                if(is_array($datos) && count($datos) > 0) {
                  for($i = 0; $i < count($datos); $i++) {
                    $colaboradores  = DB::Table('ins_colaborador_plan_supervision')
                              ->where('lider',$datos[$i]->lider)
                              ->where('anio',$anio_param)
                              ->where('mes',$mes_param)
                              ->join('rh_personas','identificacion','=','colaborador')
                              ->select(DB::raw("(nombres + ' ' + apellidos) as nombre"),"nombre_equipo",
                                  "comportamiento","ipales","calidad","ambiental","colaborador",DB::raw("'' as ipalEje")
                                  ,DB::raw("'' as caliEje"),DB::raw("'' as obseEje"),DB::raw("'' as ambiEje"))
                              ->get();

                    for($j = 0; $j < count($colaboradores); $j++) {
                      $datos[$i]->cantIpalCumplido +=  DB::Table('ins_inspeccion')
                                                          ->where('anio',$anio_param)
                                                          ->where('mes',$mes_param)
                                                          ->where('tipo_inspeccion',1)
                                                          ->where('supervisor',$colaboradores[$j]->colaborador)
                                                          ->count();

                      $datos[$i]->cantSeguCumplido +=  DB::Table('ins_inspeccion')
                                                          ->where('anio',$anio_param)
                                                          ->where('mes',$mes_param)
                                                          ->where('tipo_inspeccion',2)
                                                          ->where('supervisor',$colaboradores[$j]->colaborador)
                                                          ->count();

                      $datos[$i]->cantComporCumplido +=  DB::Table('ins_inspeccion')
                                                          ->where('anio',$anio_param)
                                                          ->where('mes',$mes_param)
                                                          ->where('tipo_inspeccion',3)
                                                          ->where('supervisor',$colaboradores[$j]->colaborador)
                                                          ->count();
                                                          
                      $datos[$i]->cantAmbiCumplido +=  DB::Table('ins_inspeccion')
                                                          ->where('anio',$anio_param)
                                                          ->where('mes',$mes_param)
                                                          ->where('tipo_inspeccion',4)
                                                          ->where('supervisor',$colaboradores[$j]->colaborador)
                                                          ->count();

                    }
                  }
                }

        }

        $fechaActual = explode("-",$this->fechaShort);
        $anio = $fechaActual[0];
        $mes = $fechaActual[1];

        return view('proyectos.supervisor.conformacion.index',array(
                        "datos" => $datos,
                        'anio' => $anio,
                        'mes' => $mes
                    ));
    }

    public function filterEquipos(Request $request)
    {
        Session::flash('mes',$request->all()['txtmes']);
        Session::flash('anio',$request->all()['txtanio']);
        Session::flash('lider',$request->all()['txtlider']);
        Session::flash('equipo',$request->all()['txtequipo']);

        return Redirect::to('transversal/supervision/conformacion');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $fechaActual = explode("-",$this->fechaShort);
        $anio = $fechaActual[0];
        $mes = $fechaActual[1];

        return view('proyectos.supervisor.conformacion.create',array(
            'anio' => $anio,
            'mes' => $mes));

    }

   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lider = DB::table('ins_lider_plan_supervision')
                ->where('id',$id)
                ->join('rh_personas','lider','=','identificacion')
                ->get(['lider','comportamiento','ipales','calidad','ambiental','nombre_equipo','anio','mes','nombres','apellidos','seguridad_obra_civil','telecomunicaciones','redes_electricas',
                    'kit_manejo_derrames','locativa_gestion_ambiental','entrega_obra_civil','restablecimiento_servicio','mantenimiento'])[0];


        $colaborador = DB::table('ins_colaborador_plan_supervision')
                ->where('lider',$lider->lider)
                ->where('anio',$lider->anio)
                ->where('mes',$lider->mes)
                ->join('rh_personas','colaborador','=','identificacion')
                ->get(['colaborador','comportamiento','ipales','calidad','ambiental','nombres','apellidos','seguridad_obra_civil','telecomunicaciones','redes_electricas',
                    'kit_manejo_derrames','locativa_gestion_ambiental','entrega_obra_civil','restablecimiento_servicio','mantenimiento']);

        $tiposInspeccion = DB::table('ins_tipo_inspeccion')->get();

        return view('proyectos.supervisor.conformacion.show',array(
            'lider' => $lider,
            'colaborador' => $colaborador,
            'id' => $id,
            'tipo'=>$tiposInspeccion,
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


class ControllerComportamiento extends Controller
{

    function __construct() {      
    
        $this->fechaA = Carbon::now(-5);
        //Session::put('user_login',"U01853"); //Aleja
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


    public function index()
    {
        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');


        $datos = [];
        if(Session::has('mes'))
        {
            $consulAux = "";
            if( Session::get('lider') == "" && Session::get('equipo') == "")
                $consulAux = " insLider.anio = "  . Session::get('anio')  . " AND insLider.mes = " . Session::get('mes') . " ";
            else
                $consulAux = " (liderRH.nombres + ' ' + liderRH.apellidos) LIKE '%"  . Session::get('lider')  . "%' AND insLider.nombre_equipo LIKE '%" . Session::get('equipo') . "%' ";
            

            $consulta = "select insLider.id,insLider.lider,COUNT(insSuper.lider) as cantColaboradores,
                SUM(insSuper.comportamiento) as cantCompor,
                SUM(insSuper.ipales)as cantIpal,
                SUM(insSuper.calidad) as cantSegu,
                SUM(insSuper.ambiental) as cantAmbi,
                insLider.comportamiento as cantComporL,
                insLider.ipales as cantIpalL,
                insLider.calidad as cantSeguL,
                insLider.ambiental as cantAmbiL,
                '0' as cantComporCumplido,
                '0' as cantIpalCumplido,
                '0' as cantSeguCumplido,
                '0' as cantAmbiCumplido,
                (liderRH.nombres + ' ' + liderRH.apellidos) as nombreL,
                insLider.nombre_equipo,
                insLider.anio,
                insLider.mes
                from ins_lider_plan_supervision as insLider
                INNER JOIN ins_colaborador_plan_supervision as insSuper ON insLider.lider = insSuper.lider
                    and insSuper.mes = insLider.mes
                    and insSuper.anio = insLider.anio
                INNER JOIN rh_personas as liderRH ON liderRH.identificacion = insLider.lider
                WHERE $consulAux
                GROUP BY insLider.ambiental,insLider.nombre_equipo,                insLider.anio,                insLider.mes,
                    insLider.lider,liderRH.nombres,liderRH.apellidos,insLider.comportamiento,insLider.ipales,insLider.calidad,insLider.id ORDER BY insLider.id";

                $datos = DB::select($consulta);
        }

        $fechaActual = explode("-",$this->fechaShort);
        $anio = $fechaActual[0];
        $mes = $fechaActual[1];


        return view('proyectos.supervisor.conformacion.index',array(
                        "datos" => $datos,
                        'anio' => $anio,
                        'mes' => $mes
                    ));
    }

    public function filterEquipos(Request $request)
    {
        Session::flash('mes',$request->all()['txtmes']);
        Session::flash('anio',$request->all()['txtanio']);
        Session::flash('lider',$request->all()['txtlider']);
        Session::flash('equipo',$request->all()['txtequipo']);

        return Redirect::to('transversal/supervision/conformacion');
    }

  
    public function create()
    {
        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $fechaActual = explode("-",$this->fechaShort);
        $anio = $fechaActual[0];
        $mes = $fechaActual[1];

        return view('proyectos.supervisor.conformacion.create',array(
            'anio' => $anio,
            'mes' => $mes));

    }

  
    public function show($id)
    {
        $lider = DB::table('ins_lider_plan_supervision')
                ->where('id',$id)
                ->join('rh_personas','lider','=','identificacion')
                ->get(['lider','comportamiento','ipales','calidad','ambiental','nombre_equipo','anio','mes','nombres','apellidos'])[0];


        $colaborador = DB::table('ins_colaborador_plan_supervision')
                ->where('lider',$lider->lider)
                ->where('anio',$lider->anio)
                ->where('mes',$lider->mes)
                ->join('rh_personas','colaborador','=','identificacion')
                ->get(['colaborador','comportamiento','ipales','calidad','ambiental','nombres','apellidos']);



        return view('proyectos.supervisor.conformacion.show',array(
            'lider' => $lider,
            'colaborador' => $colaborador,
            'id' => $id
            ));

    }
}
*/