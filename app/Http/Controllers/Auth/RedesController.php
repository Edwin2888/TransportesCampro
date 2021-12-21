<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Carbon\Carbon;
use Session;
use Redirect;

class RedesController extends Controller
{
    /*
    select * from [dbo].[rds_inv_log_cambios]
    select * from [dbo].[rds_inv_log_documentos]
    select * from [dbo].[rds_inv_log_seriados]

    */
    //TABLAS UTILIZADAS
    // rds_gop_ordenes -> Tabla de ordenes ->primary Key -> id_orden
    // rds_gop_ws -> Tablas de WBS
    // rds_gop_nodos -> Tabla de NODOS
    // rds_gop_ws_nodos -> Tabla de RELACIÓN
    // rds_gop_mobra -> Mano de OBRA
    // rds_inv_documentos -> Materiales Cabebeza
    // rds_inv_detalle_documentos -> Cuerpo Materiales
    //


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct() {           
        $this->valorT = "";
        
        $this->tblAux = Session::get('proy_long');
        $this->tblAux1 = Session::get('proy_short');      
        
        /*$this->tblAux = "rds_gop_";
        $this->tblAux1 = "rds_";
        Session::put('proy_short',$this->tblAux1);
        Session::put('proy_long',$this->tblAux);
        Session::put('user_login',"U01852");*/

        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    private function saveLog($id_log,$campo_valor,$descripcion)
    {
        /*
        LOGS PROGRAMACIÓN
        OPERA01 CREACION PROYECTO PROGRAMADOS
        OPERA02 ACTUALIZAR PROYECTO PROGRAMADOS
        OPERA03 IMPORTAR MANO DE OBRA PROYECTOS EXCEL
        OPERA04 IMPORTAR MATERIALES PROYECTOS EXCEL
        OPERA05 AGREGAR WBS PROYECTO
        OPERA06 AGREGAR NODOS PROYECTO
        OPERA07 AGREGAR ACTIVIDADES PROYECTO
        OPERA08 AGREGAR MATERIALES PROYECTO
        OPERA09 AGREGAR RESTRICCION PROYECTO
        OPERA10 MODIFICAR RESTRICCION PROYECTO
        OPERA11 AGREGAR GOM PROYECTO
        OPERA12 ACTUALIZAR GOM PROYECTO
        OPERA13 ELIMINAR GOM PROYECTO
        OPERA14 AGREGAR DESCARGO PROYECTO
        OPERA15 ACTUALIZAR DESCARGO PROYECTO
        OPERA16 ELIMINAR DESCARGO PROYECTO
        OPERA17 CREAR MANIOBRA PROYECTO
        OPERA18 ACTUALIZAR MANIOBRA PROYECTO
        OPERA19 CANCELAR MANIOBRA PROYECTO
        OPERA20 AGREGA RECURSO MANIOBRA PROYECTO
        OPERA21 ACTUALIZA RECURSO MANIOBRA PROYECTO
        OPERA22 ELIMINAR RECURSO MANIOBRA PROYECTO
        OPERA23 GUARDA ASIGNACION ACTIVIDAD RECURSO MANIOBRA PROYECTO
        OPERA24 ACTUALIZA ASIGNACION ACTIVIDAD RECURSO MANIOBRA PROYECTO
        OPERA25 ELIMINA ASIGNACION ACTIVIDAD RECURSO MANIOBRA PROYECTO
        OPERA27 GUARDA ASIGNACION MATERIAL RECURSO MANIOBRA PROYECTO
        OPERA28 ACTUALIZA ASIGNACION MATERIAL RECURSO MANIOBRA PROYECTO
        OPERA29 ELIMINA ASIGNACION MATERIAL RECURSO MANIOBRA PROYECTO
        OPERA30 AGREGA ACTIVDAD EXTRA MANIOBRA PROYECTO
        OPERA31 AGREGA MATERIAL EXTRA MANIOBRA PROYECTO
        OPERA32 FINALIZA PROGRAMACION MANIOBRA PROYECTO
        OPERA34 FINALIZA EJECUCION MANIOBRA PROYECTO
        OPERA36 FINALIZA CONCILIACION MANIOBRA PROYECTO
        OPERA37 CAMBIO ESTADO GOM PROYECTO
        OPERA38 CARGA EXCEL PARA CAMBIO DE ESTADO GOM PROYECTO
        OPERA39 ACTUALIZA WBS PROYECTO
        OPERA40 ELIMINA WBS PROYECTO
        OPERA41 ACTUALIZA NODOS PROYECTO
        OPERA42 ELIMINA NODOS PROYECTO
        OPERA43 ACTUALIZA ACTIVIDADES PROYECTO
        OPERA44 ELIMINA ACTIVIDADES PROYECTO
        OPERA45 ACTUALIZA MATERIALES PROYECTO
        OPERA46 ELIMINA MATERIALES PROYECTO
        OPERA47 AGREGAR CIRCUITO PROYECTO
        OPERA48 GENERA DOCUMENTOS CABEZA DC
        OPERA49 GENERA DOCUMENTO CUERPO DC
        OPERA50 GUARDAR EJECUCION BAREMOS MANIOBRA
        OPERA51 GUARDA EJECUCION MATERIALES MANIOBRA
        OPERA52 GENERE DOCUMENTOS CABEZA CS
        OPERA53 GENERE DOCUMENTOS CUERPO CS
        OPERA54 GUARDA CONCILIACION BAREMOS MANIONBRA
        OPERA55 GUARDA CONCILIACION MATERIALES MANIONBRA
        OPERA56 ANULA DOCUMENTOS DC MANIOBRA
        OPERA57 ACTUALIZA RECURSO AUXILIAR
        OPERA58 CANCELA RECURSO AUXILIAR
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


    public function index()
    {
        echo  "BIENVENIDO";
    }


    /********************************************/
    /*  Rutas de acceso                         */
    /********************************************/

   
    //Ruta Index Proyectos
    public function indexOrdenes()
    {       
        if(!Session::has('proy_long') || !Session::has('proy_short'))
        {
            return Redirect::to('{{config("app.Campro")[2]}}/campro');
        }


        $tip = \DB::table($this->tblAux . 'tipo_orden')
                ->select('id_tipo as tip','nombre as nom')
                ->get();

        $estado = \DB::table($this->tblAux . 'estado_proyectos')
                ->select('id_estado as id','nombre as nom')
                ->where('nombre','<>','')
                ->get();

         $proyecto = null;
        // Session::forget('fecha1');
        if(Session::has('fecha1'))
        {   
            $cad = "EXEC sp_" . $this->tblAux . "consulta_proyectos '" . Session::get('proyecto')
                     . "','" . Session::get('proyectoN') . "','" . Session::get('estado') . "','" 
                     . Session::get('fecha1') . "','" . Session::get('fecha2') . "','" . Session::get('tipo') . "'" ;

            $proyecto = DB::select("SET NOCOUNT ON;" . $cad);

            $fechaInic = explode("-",Session::get('fecha11'))[2] . "/" . explode("-",Session::get('fecha11'))[1] . "/" . explode("-",Session::get('fecha11'))[0];
            $fechaFin = explode("-",Session::get('fecha21'))[2] . "/" . explode("-",Session::get('fecha21'))[1] . "/" . explode("-",Session::get('fecha21'))[0];
            Session::flash('fecha11',$fechaInic);
            Session::flash('fecha21',$fechaFin);

            /*$page = 1;
            $paginate = 10;

            $offSet = ($page * $paginate) - $paginate;
            $itemsForCurrentPage = array_slice($proyecto, $offSet, $paginate, true);
            $proyecto = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($proyecto), $paginate, $page);*/
        }
        else
        {
            $cad = "EXEC sp_" . $this->tblAux . "consulta_proyectos '" . "PRYY"
                     . "','" . "" . "','" . "" . "','" 
                     . "" . "','" . "" . "',''";
            $proyecto = DB::select("SET NOCOUNT ON;" . $cad);
        }
        
        //return response()->json($proyecto);
        //$page = \Input::get('page', 1);

        

        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        $proyectos = DB::table($this->tblAux . 'tipo_proyecto')
                        ->get(['id_proyecto','des_proyecto']);

        return view('proyectos.redes.trabajoprogramado.index',array(
            'tipos' => $tip,
            'estad' => $estado,
            'proyec1' => $proyecto,
            'gom' => [],
            "prog" => null,
            'proyec' => "",
            "fecha" => $fechaActual,
            "fecha2" => $nuevafecha,
            "comboxP" => [],
            "ejecucionB" => null,
            "encabezado" => null,
            "index" => 1,
            "opc" => 1,
            "proyecto" => $proyectos
            ));
    }

    //Rutas Index Ordenes
    public function indexOrden()
    {
        $tip = \DB::table($this->tblAux . 'tipo_orden')
                ->select('id_tipo as tip','nombre as nom')
                ->get();

        $estado = DB::table('gop_estado_ordenes')
            ->select('id_estado as id','nombre as nom')
            ->where('rds','S')
            ->get();


        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];
           
        
        

        $ordenesRealizada = null;
        if(Session::has('fecha1'))
        {   

            /*$ordenesRealizada = 
            $cad = "EXEC sp_" . $this->tblAux . "consulta_proyectos '" . 
                     . "','" . Session::get('proyectoN') . "','" . Session::get('estado') . "','" 
                     . Session::get('fecha1') . "','" . Session::get('fecha2') . "','" . Session::get('tipo') . "'" ;*/

            $proy = Session::get('proyecto');
            $proyN = Session::get('proyectoN');
            $estado = Session::get('estado');
            $tipo = Session::get('tipo');
            $fecha1 = Session::get('fecha1');
            $fecha2 = Session::get('fecha2');

            $gom = Session::get('gomF1');
            $orden = Session::get('ordenF1');


            $ordeneRealizada =  DB::table($this->tblAux . 'ordenes'  . $this->valorT)
                            ->join('gop_estado_ordenes','gop_estado_ordenes.id_estado','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_estado")
                            ->join($this->tblAux . 'proyectos' ,$this->tblAux . 'proyectos.id_proyecto','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_proyecto")
                            ->join($this->tblAux . 'ordenes_manoobra_detalle as tbl1' ,$this->tblAux . 'ordenes.id_orden','=', 'tbl1.id_orden')
                            //->join($this->tblAux1 . 'inv_bodegas as tbl2' ,'tbl1.id_lider','=', 'tbl2.id_bodega')
                            /*->leftJoin($this->tblAux . 'ordenes_manoobra as tbl3' ,function($join)
                            {
                                $join->on('tbl1.id_orden','=','tbl3.id_orden')
                                    ->on('tbl3.id_personaCargo','=','tbl1.id_lider');
                            })
                            ->join($this->tblAux . 'baremos as tbl4' ,'tbl4.codigo','=', 'tbl3.id_baremo')*/
                            ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto','LIKE',"%" . $proy . "%")
                            ->where($this->tblAux . 'proyectos'  . $this->valorT .'.nombre','LIKE',"%" . $proyN . "%")
                            ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_tipo','T56')
                            ->where('tbl1.id_estado',0)
                            //->where('periodo',2017)
                            ->select('gop_estado_ordenes.nombre as id_estadoN','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado','fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',
                                'cd','nivel_tension',$this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                $this->tblAux . 'proyectos'  . $this->valorT .'.nombre as nombreP',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.nodos_utilizados',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.descargo'
                                //,'tbl1.id_lider','tbl2.nombre'
                                //,'"" as id_lider','"" as nombre'
                                //,DB::raw('SUM(tbl4.precio * tbl3.cantidad_confirmada) as cantidad')
                                );
            

            if($estado != "0")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_estado',$estado);
            }

            if($gom == "" && $orden == "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'proyectos'  . $this->valorT . '.tipo_proyecto',$tipo);
            }

            if($gom != "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT . '.gom',$gom);
            }

            if($orden != "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_orden','LIKE',"%" . $orden . "%");
            }

            if($fecha1 != "" && $fecha2 != "" && $gom == "" && $orden == "")
            {
                // E2 Programada
                if($estado == "E4" || $estado == "E2" || $estado == "A1")
                {
                    $ordeneRealizada = $ordeneRealizada
                                    ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_ejecucion',[$fecha1,$fecha2]);    
                }
                else
                {
                    // E1 Generada
                    if($estado == "E1")
                    {
                        $ordeneRealizada = $ordeneRealizada
                                        ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_programacion',[$fecha1,$fecha2]);    
                    }
                    else
                    {
                        // E1 Generada
                        $ordeneRealizada = $ordeneRealizada
                                        ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_ejecucion',[$fecha1,$fecha2]);       
                    }
                }
                //if($estado == "E1")                
                // Problemas de las fechas que consulta
                // E1 Generada
                // A1 Anulada
                // C2 Conciliada
            }
                            
            $ordeneRealizada = $ordeneRealizada
                                ->groupBy('gop_estado_ordenes.nombre','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado',
                                    'fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion','cd','nivel_tension',
                                    $this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                    $this->tblAux . 'proyectos'  . $this->valorT .'.nombre',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.nodos_utilizados',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.descargo'
                                    //,'tbl1.id_lider','tbl2.nombre'
                                    )
                                ->get();

           // dd($ordeneRealizada);

            $orGrupoT = [];
            foreach ($ordeneRealizada as $orde => $val) {
                $usersRecuro = [];
                array_push($orGrupoT,array($val,$usersRecuro));
            }

            $ordenesRealizada = $orGrupoT;
            
            
            $estado = DB::table('gop_estado_ordenes')
            ->select('id_estado as id','nombre as nom')
            ->where('rds','S')
            ->get();

            if($fecha1 != "" && $fecha2 != "")
            {
                $fecha1 = explode("-",$fecha1);
                $fecha1 = $fecha1[2] . "/" . $fecha1[1] . "/" . $fecha1[0];
                $fecha2 = explode("-",$fecha2);
                $fecha2 = $fecha2[2] . "/" . $fecha2[1] . "/" . $fecha2[0];

                Session::put('fecha1',$fecha1);
                Session::put('fecha2',$fecha2);

            }

            $proyectos = DB::table($this->tblAux . 'tipo_proyecto')
                        ->get(['id_proyecto','des_proyecto']);

            return view('proyectos.redes.trabajoprogramado.indexOrdenes',array(
                'tipos' => $tip,
                'estad' => $estado,
                'ordenesRealizada' => $ordenesRealizada,
                'gom' => [],
                "prog" => null,
                'proyec' => "",
                "fecha" => $fechaActual,
                "fecha2" => $nuevafecha,
                "comboxP" => [],
                "proyecto" => $proyectos,
                "ejecucionB" => null,
                "encabezado" => null,
                "index" => 1,
                "opc" => 2
                ));

        }
        
        $proyectos = DB::table($this->tblAux . 'tipo_proyecto')
                        ->get(['id_proyecto','des_proyecto']);

        return view('proyectos.redes.trabajoprogramado.indexOrdenes',array(
            'tipos' => $tip,
            'estad' => $estado,
            'ordenesRealizada' => $ordenesRealizada,
            'gom' => [],
            "prog" => null,
            'proyec' => "",
            "fecha" => $fechaActual,
            "fecha2" => $nuevafecha,
            "comboxP" => [],
            "proyecto" => $proyectos,
            "ejecucionB" => null,
            "encabezado" => null,
            "index" => 1,
            "opc" => 2
            ));
    }

    public function generarExcelConsolidadoBaremos(Request $request)
    {

        //ini_set('memory_limit', '256M');
        ini_set('memory_limit', '-1');
        
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $fecha_inicio_ymd = (count(explode("/",$fec1)) > 1 ? explode("/",$fec1)[2] . "-" . explode("/",$fec1)[1] . "-" . explode("/",$fec1)[0] : "" ) ;
        $fecha_corte_ymd = (count(explode("/",$fec2)) > 1 ? explode("/",$fec2)[2] . "-" . explode("/",$fec2)[1] . "-" . explode("/",$fec2)[0] : "" ) ;
        $tipo = $request->all()['id_tipo'];
        $esta = $request->all()['cbo_estado'];
        $numP = $request->all()['proyecto'];
        $proyN = $request->all()['proyectoN'];

        $proy = $numP;
        $proyN = $proyN;
        $estado = $esta;
        $tipo = $tipo;
        $fecha1 = $fecha_inicio_ymd;
        $fecha2 = $fecha_corte_ymd;

        $gom = $request->all()['ordenGOM'];
        $orden = $request->all()['ordenManiObra'];

        $ordeneRealizada =  DB::table($this->tblAux . 'ordenes'  . $this->valorT)
                            ->join('gop_estado_ordenes','gop_estado_ordenes.id_estado','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_estado")
                            ->join($this->tblAux . 'proyectos' ,$this->tblAux . 'proyectos.id_proyecto','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_proyecto")
                            ->join($this->tblAux . 'ordenes_manoobra_detalle as tbl1' ,$this->tblAux . 'ordenes.id_orden','=', 'tbl1.id_orden')
                            ->join($this->tblAux1 . 'inv_bodegas as tbl2' ,'tbl1.id_lider','=', 'tbl2.id_bodega')
                            
                            ->leftJoin($this->tblAux . 'cuadrilla as cuadrigop' ,function($join)
                            {
                                $join->on('cuadrigop.id_lider','=','tbl1.id_lider');
                            })
                            
                            ->join($this->tblAux . 'ordenes_manoobra as tbl3' ,function($join)
                            {
                                $join->on('tbl1.id_orden','=','tbl3.id_orden')
                                    ->on('tbl3.id_personaCargo','=','tbl1.id_lider');
                            })

                            ->join($this->tblAux . 'baremos as tbl4' ,'tbl4.codigo','=', 'tbl3.id_baremo')
                            

                            ->leftJoin($this->tblAux . 'mobra as tbl5' ,function($join)
                            {
                                $join->on('tbl1.id_orden','=','tbl5.id_orden')
                                    ->on('tbl5.id_origen','=','tbl1.id_lider')
                                    ->on('tbl5.id_baremo','=','tbl4.id_baremo')
                                    ->on('tbl5.id_nodo','=','tbl3.id_nodo');
                            })

                            ->leftJoin($this->tblAux . 'ordenes_mobra as mobra' ,function($join)
                            {
                                $join->on('tbl1.id_orden','=','mobra.id_orden')
                                    ->on('mobra.id_origen','=','tbl1.id_lider')
                                    ->on('mobra.id_baremo','=','tbl4.id_baremo')
                                    ->on('mobra.id_nodo','=','tbl3.id_nodo');
                            })


                            ->join($this->tblAux . 'nodos as nodo' ,'nodo.id_nodo','=', 'tbl3.id_nodo')

                            ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto','LIKE',"%" . $proy . "%")
                            ->where($this->tblAux . 'proyectos'  . $this->valorT .'.nombre','LIKE',"%" . $proyN . "%")
                            ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_tipo','T56')
                            ->where('tbl1.id_estado',0)
                            ->where('periodo',2017)
                            ->where('cuadrigop.id_estado','A')
                            ->select('cuadrigop.id_movil','gop_estado_ordenes.nombre as id_estadoN','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado','fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',
                                $this->tblAux . 'ordenes.cd',$this->tblAux . 'ordenes.nivel_tension',$this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                $this->tblAux . 'proyectos'  . $this->valorT .'.nombre as nombreP',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                'nodo.nombre_nodo as nodos_utilizados',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.descargo',
                                'tbl5.id_baremo as bareE','tbl5.cantidad as cantE','fecha_usuario_terreno_create',
                                DB::raw('(tbl5.precio * tbl5.cantidad) as valorEje'),
                                'tbl1.id_lider','tbl2.nombre',DB::raw('(tbl4.precio * tbl3.cantidad_confirmada) as cantidad'),
                                    'tbl4.codigo','tbl4.actividad as nombre_baremo','tbl3.cantidad_confirmada','tbl3.id_nodo'
                                );

            if($estado != "0")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_estado',$estado);
            }

            if($gom == "" && $orden == "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'proyectos'  . $this->valorT . '.tipo_proyecto',$tipo);
            }

            if($gom != "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT . '.gom',$gom);
            }

            if($orden != "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_orden','LIKE',"%" . $orden . "%");
            }

            if($fecha1 != "" && $fecha2 != "" && $gom == "" && $orden == "")
            {
                // E2 Programada
                if($estado == "E4")
                {
                    $ordeneRealizada = $ordeneRealizada
                                    ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_ejecucion',[$fecha1,$fecha2]);    
                }
                else
                {
                    // E2 Programada
                    if($estado == "E1")
                    {
                        $ordeneRealizada = $ordeneRealizada
                                        ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_programacion',[$fecha1,$fecha2]);    
                    }
                    else
                    {
                        // E1 Generada
                        $ordeneRealizada = $ordeneRealizada
                                        ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_ejecucion',[$fecha1,$fecha2]);       
                    }
                }
            }

            $ordeneRealizada = $ordeneRealizada->groupBy('cuadrigop.id_movil','gop_estado_ordenes.nombre','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado',
                                    'fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',$this->tblAux . 'ordenes.cd',$this->tblAux . 'ordenes.nivel_tension',
                                    $this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                    $this->tblAux . 'proyectos'  . $this->valorT .'.nombre',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                    'nodo.nombre_nodo',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.descargo','tbl1.id_lider','tbl2.nombre',
                                    'tbl5.id_baremo','tbl5.cantidad',
                                    'tbl5.precio','tbl5.cantidad','fecha_usuario_terreno_create',
                                    'tbl1.id_lider','tbl2.nombre','tbl4.precio','tbl3.cantidad_confirmada',
                                    'tbl4.codigo','tbl4.actividad','tbl3.cantidad_confirmada','tbl3.id_nodo'
                                    )
                                ->get();

            \Excel::create('Consolidado actividades' .  $this->fechaALong, function($excel) use($ordeneRealizada) {            

                $excel->sheet('Consolidado', function($sheet) use($ordeneRealizada){
                    $mes = 4;
                    $primerDia = 1;
                    $ultimoDia = 30;
                    $products = ["Estado orden","Nombre proyecto","Cod proyecto","Nodos","WBS","Descargo","ORDEN","F. Emisión","F. Programación",
                    "F. Ejecución","CD","Dirección","GOM","Líder","Nombre líder","Móvil","Baremo","Actividad","Cantidad","Valor","EJECUTADA","Cant. Eje","Valor Eje","Tipo de ingreso"];
                    $sheet->fromArray($products);
                    $k = 2;
                    $sema = 1;


                    for ($i=0; $i < count($ordeneRealizada); $i++) {    

                        $datoE  = "SI";

                        $validacion = "";
                        if($ordeneRealizada[$i]->cantE == NULL || $ordeneRealizada[$i]->cantE == "")
                            $datoE  = "NO";

                        if($ordeneRealizada[$i]->fecha_usuario_terreno_create == NULL || $ordeneRealizada[$i]->fecha_usuario_terreno_create == "")
                            $validacion = "CENTRO DE CONTROL";
                        else
                            $validacion = "CUADRILLA";

                        if($datoE == "NO")
                            $validacion = "";

                        $sheet->row($i +2, array(
                            $ordeneRealizada[$i]->id_estadoN,
                            $ordeneRealizada[$i]->nombreP,
                            $ordeneRealizada[$i]->id_proyecto,
                            $ordeneRealizada[$i]->nodos_utilizados,
                            $ordeneRealizada[$i]->wbs_utilzadas,
                            $ordeneRealizada[$i]->descargo,
                            $ordeneRealizada[$i]->id_orden,
                            explode(" ",$ordeneRealizada[$i]->fecha_emision)[0],
                            explode(" ",$ordeneRealizada[$i]->fecha_programacion)[0],
                            explode(" ",$ordeneRealizada[$i]->fecha_ejecucion)[0],
                            $ordeneRealizada[$i]->cd,
                            $ordeneRealizada[$i]->direccion,
                            $ordeneRealizada[$i]->gom,
                            $ordeneRealizada[$i]->id_lider,
                            $ordeneRealizada[$i]->nombre,
                            $ordeneRealizada[$i]->id_movil,
                            $ordeneRealizada[$i]->codigo,
                            $ordeneRealizada[$i]->nombre_baremo,
                            $ordeneRealizada[$i]->cantidad_confirmada,
                            $ordeneRealizada[$i]->cantidad,
                            $datoE,
                            $ordeneRealizada[$i]->cantE,
                            $ordeneRealizada[$i]->valorEje,
                            $validacion
                            ));                        
                    }
                });
        })->export('xls');
    }

    public function generarExcelConsolidadoEjecucion(Request $request)
    {
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $fecha_inicio_ymd = (count(explode("/",$fec1)) > 1 ? explode("/",$fec1)[2] . "-" . explode("/",$fec1)[1] . "-" . explode("/",$fec1)[0] : "" ) ;
        $fecha_corte_ymd = (count(explode("/",$fec2)) > 1 ? explode("/",$fec2)[2] . "-" . explode("/",$fec2)[1] . "-" . explode("/",$fec2)[0] : "" ) ;
        $tipo = $request->all()['id_tipo'];
        $esta = $request->all()['cbo_estado'];
        $numP = $request->all()['proyecto'];
        $proyN = $request->all()['proyectoN'];

        $proy = $numP;
        $proyN = $proyN;
        $estado = $esta;
        $tipo = $tipo;
        $fecha1 = $fecha_inicio_ymd;
        $fecha2 = $fecha_corte_ymd;

        $gom = $request->all()['ordenGOM'];
        $orden = $request->all()['ordenManiObra'];

        $ordeneRealizada =  DB::table($this->tblAux . 'ordenes'  . $this->valorT)
                            ->join('gop_estado_ordenes','gop_estado_ordenes.id_estado','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_estado")
                            ->join($this->tblAux . 'proyectos' ,$this->tblAux . 'proyectos.id_proyecto','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_proyecto")
                            ->join($this->tblAux . 'ordenes_manoobra_detalle as tbl1' ,$this->tblAux . 'ordenes.id_orden','=', 'tbl1.id_orden')
                            ->join($this->tblAux1 . 'inv_bodegas as tbl2' ,'tbl1.id_lider','=', 'tbl2.id_bodega')
                            ->join($this->tblAux . 'ordenes_manoobra as tbl3' ,function($join)
                            {
                                $join->on('tbl1.id_orden','=','tbl3.id_orden')
                                    ->on('tbl3.id_personaCargo','=','tbl1.id_lider');
                            })
                            
                            ->join($this->tblAux . 'baremos as tbl4' ,'tbl4.codigo','=', 'tbl3.id_baremo')

                            ->leftJoin($this->tblAux . 'mobra as tbl5' ,function($join)
                            {
                                $join->on('tbl1.id_orden','=','tbl5.id_orden')
                                    ->on('tbl5.id_origen','=','tbl1.id_lider')
                                    ->on('tbl5.id_baremo','=','tbl4.id_baremo');
                            })
                            ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto','LIKE',"%" . $proy . "%")
                            ->where($this->tblAux . 'proyectos'  . $this->valorT .'.nombre','LIKE',"%" . $proyN . "%")
                            ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_tipo','T56')
                            ->where('tbl1.id_estado',0)
                            ->where('periodo',2017)
                            ->select('gop_estado_ordenes.nombre as id_estadoN','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado','fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',
                                'cd','nivel_tension',$this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                $this->tblAux . 'proyectos'  . $this->valorT .'.nombre as nombreP',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.nodos_utilizados',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.descargo',
                                'tbl1.id_lider','tbl2.nombre','tbl1.fecha_actualiza_ejecucion','tbl1.finaliza_terreno'
                                );

            if($estado != "0")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_estado',$estado);
            }

            if($gom == "" && $orden == "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'proyectos'  . $this->valorT . '.tipo_proyecto',$tipo);
            }

            if($gom != "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT . '.gom',$gom);
            }

            if($orden != "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_orden','LIKE',"%" . $orden . "%");
            }

            if($fecha1 != "" && $fecha2 != "" && $gom == "" && $orden == "")
            {
                // E2 Programada
                if($estado == "E4")
                {
                    $ordeneRealizada = $ordeneRealizada
                                    ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_ejecucion',[$fecha1,$fecha2]);    
                }
                else
                {
                    // E2 Programada
                    if($estado == "E1")
                    {
                        $ordeneRealizada = $ordeneRealizada
                                        ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_programacion',[$fecha1,$fecha2]);    
                    }
                    else
                    {
                        // E1 Generada
                        $ordeneRealizada = $ordeneRealizada
                                        ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_ejecucion',[$fecha1,$fecha2]);       
                    }
                }
            }

            $ordeneRealizada = $ordeneRealizada->groupBy('gop_estado_ordenes.nombre','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado',
                                    'fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion','cd','nivel_tension',
                                    $this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                    $this->tblAux . 'proyectos'  . $this->valorT .'.nombre',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.nodos_utilizados',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                    $this->tblAux . 'ordenes'  . $this->valorT .'.descargo','tbl1.id_lider','tbl2.nombre'
                                    ,'tbl1.fecha_actualiza_ejecucion','tbl1.finaliza_terreno'
                                    )
                                ->orderBy('fecha_ejecucion')
                                ->get();

            \Excel::create('Consolidado actividades CAMPRO MÓVIL ' . $this->fechaALong, function($excel) use($ordeneRealizada) {            

                $excel->sheet('Consolidado', function($sheet) use($ordeneRealizada){
                    $mes = 4;
                    $primerDia = 1;
                    $ultimoDia = 30;
                    $products = ["Estado orden","Nombre proyecto","Cod proyecto","Nodos","WBS","Descargo","ORDEN","F. Emisión","F. Programación",
                    "F. Ejecución","CD","Dirección","GOM","Líder","Nombre líder","F. Actualización GPS","F. Actualización Captura Ejecución","Finaliza OT"];
                    $sheet->fromArray($products);
                    $k = 2;
                    $sema = 1;


                    for ($i=0; $i < count($ordeneRealizada); $i++) {    


                        $dat = "NO";
                        if($ordeneRealizada[$i]->finaliza_terreno == "1")
                            $dat = "SI";

                        $fechaE = explode(" ",$ordeneRealizada[$i]->fecha_ejecucion)[0];
                        
                        $fechaTrack = DB::Table('rds_gps_tracker')
                                    ->where('usuario_movil',$ordeneRealizada[$i]->id_lider)
                                    ->whereBetween('fecha',[$fechaE,$fechaE])
                                    ->orderBy('fecha','desc')
                                    ->orderBy('hora','desc')
                                    ->get(["fecha","hora"]);


                        $datoBack = "";
                        if(count($fechaTrack) > 0)                            
                            $datoBack = $fechaTrack[0]->fecha . " " . $fechaTrack[0]->hora;

                        $sheet->row($i +2, array(
                            $ordeneRealizada[$i]->id_estadoN,
                            $ordeneRealizada[$i]->nombreP,
                            $ordeneRealizada[$i]->id_proyecto,
                            $ordeneRealizada[$i]->nodos_utilizados,
                            $ordeneRealizada[$i]->wbs_utilzadas,
                            $ordeneRealizada[$i]->descargo,
                            $ordeneRealizada[$i]->id_orden,
                            explode(" ",$ordeneRealizada[$i]->fecha_emision)[0],
                            explode(" ",$ordeneRealizada[$i]->fecha_programacion)[0],
                            $fechaE,
                            $ordeneRealizada[$i]->cd,
                            $ordeneRealizada[$i]->direccion,
                            $ordeneRealizada[$i]->gom,
                            $ordeneRealizada[$i]->id_lider,
                            $ordeneRealizada[$i]->nombre,
                            $datoBack,
                            $ordeneRealizada[$i]->fecha_actualiza_ejecucion,
                            $dat
                            ));                        
                    }
                });
        })->export('xls');
    }

    //Rutas Index Documentos
    public function indexDocu()
    {
        $tip = \DB::table($this->tblAux . 'tipo_orden')
                ->select('id_tipo as tip','nombre as nom')
                ->get();

        $estado = DB::table('inv_cat_estado_documentos')
                ->select('id_estado as id','nombre as nom')
                ->get();

        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];        

        $ordenesRealizada = null;
        if(Session::has('fecha1'))
        {   

            /*$ordenesRealizada = 
            $cad = "EXEC sp_" . $this->tblAux . "consulta_proyectos '" . 
                     . "','" . Session::get('proyectoN') . "','" . Session::get('estado') . "','" 
                     . Session::get('fecha1') . "','" . Session::get('fecha2') . "','" . Session::get('tipo') . "'" ;*/

            $proy = Session::get('proyecto');
            $proyN = Session::get('proyectoN');
            $estado = Session::get('estado');
            $tipo = Session::get('tipo');
            $fecha1 = Session::get('fecha1');
            $fecha2 = Session::get('fecha2');
            $ord = Session::get('ordenM');

            $ordeneRealizada =  DB::table($this->tblAux . 'ordenes'  . $this->valorT)
                            ->join('gop_estado_ordenes','gop_estado_ordenes.id_estado','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_estado")
                            ->join($this->tblAux . 'proyectos' ,$this->tblAux . 'proyectos.id_proyecto','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_proyecto")
                            ->leftJoin($this->tblAux . 'ordenes_materiales_documentos' ,$this->tblAux . 'ordenes_materiales_documentos.id_orden','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_orden")
                            ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto','LIKE',"%" . $proy . "%")
                            ->where($this->tblAux . 'proyectos'  . $this->valorT .'.nombre','LIKE',"%" . $proyN . "%")
                            ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_tipo','T56')
                            ->where($this->tblAux . 'proyectos'  . $this->valorT . '.tipo_proyecto',$tipo)
                            ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_orden','LIKE',"%" . $ord . "%")
                            ->select('gop_estado_ordenes.nombre as id_estadoN',$this->tblAux . 'ordenes' . $this->valorT . ".id_orden",$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado','fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',
                                'cd','nivel_tension',$this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                $this->tblAux . 'proyectos'  . $this->valorT .'.nombre as nombreP',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                $this->tblAux . 'ordenes_materiales_documentos.id_documento',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.nodos_utilizados',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.descargo');

            if($estado != "0")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->rightJoin($this->tblAux1 . 'inv_documentos',$this->tblAux . 'ordenes_materiales_documentos.id_documento','=',$this->tblAux1 . 'inv_documentos.id_documento')
                                    ->where($this->tblAux1 . 'inv_documentos.id_estado',$estado);
            }

            if($fecha1 != "" && $fecha2 != "")
            {
                $ordeneRealizada = $ordeneRealizada
                                    ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha',[$fecha1,$fecha2]);
            }
                            
            $ordeneRealizada = $ordeneRealizada->get();

            $orGrupoT = [];
            foreach ($ordeneRealizada as $orde => $val) {                
                array_push($orGrupoT,array($val,""));
            }

            $ordenesRealizada = $orGrupoT;
            
            
            $estado = DB::table('inv_cat_estado_documentos')
                ->select('id_estado as id','nombre as nom')
                ->get();

            if($fecha1 != "" && $fecha2 != "")
            {
                $fecha1 = explode("-",$fecha1);
                $fecha1 = $fecha1[2] . "/" . $fecha1[1] . "/" . $fecha1[0];
                $fecha2 = explode("-",$fecha2);
                $fecha2 = $fecha2[2] . "/" . $fecha2[1] . "/" . $fecha2[0];

                Session::put('fecha1',$fecha1);
                Session::put('fecha2',$fecha2);

            }

            $proyectos = DB::table($this->tblAux . 'tipo_proyecto')
                        ->get(['id_proyecto','des_proyecto']);

            return view('proyectos.redes.trabajoprogramado.indexDocumentos',array(
                'tipos' => $tip,
                'estad' => $estado,
                'ordenesRealizada' => $ordenesRealizada,
                'gom' => [],
                "prog" => null,
                'proyec' => "",
                'proyecto' => $proyectos,
                "fecha" => $fechaActual,
                "fecha2" => $nuevafecha,
                "comboxP" => [],
                "ejecucionB" => null,
                "encabezado" => null,
                "index" => 1,
                "opc" => 3
                ));

        }
        
        $proyectos = DB::table($this->tblAux . 'tipo_proyecto')
                        ->get(['id_proyecto','des_proyecto']);

        return view('proyectos.redes.trabajoprogramado.indexDocumentos',array(
            'tipos' => $tip,
            'proyecto' => $proyectos,
            'estad' => $estado,
            'ordenesRealizada' => $ordenesRealizada,
            'gom' => [],
            "prog" => null,
            'proyec' => "",
            "fecha" => $fechaActual,
            "fecha2" => $nuevafecha,
            "comboxP" => [],
            "ejecucionB" => null,
            "encabezado" => null,
            "index" => 1,
            "opc" => 3
            ));
    }

    //Ruta index gantRestricciones
    public function indexgantProyectos()
    {

        $arreglo = [];

        if(Session::has('tipo'))
        {
            $fecha1 = explode("/",Session::get('fecha1'))[2] . "-" .  explode("/",Session::get('fecha1'))[1] . "-" .  explode("/",Session::get('fecha1'))[0] ;
            $fecha2 = explode("/",Session::get('fecha2'))[2] . "-" .  explode("/",Session::get('fecha2'))[1] . "-" .  explode("/",Session::get('fecha2'))[0] ;
            $tipo = Session::get('tipo');
                
            $proyectosConsulta = "
              SELECT *
                FROM
                (SELECT tbl1.id_proyecto,tbl1.nombre,tbl1.fecha,
                COUNT(tbl2.id_orden) as cantOrden,
                MIN(tbl2.fecha_ejecucion) as fechaMenor,
                MAX(tbl2.fecha_ejecucion) as fechaMayor
                ,
                (SELECT COUNT(id_estado)
                FROM rds_gop_ordenes tbl3
                WHERE tbl3.id_proyecto = tbl1.id_proyecto
                AND tbl3.fecha_ejecucion BETWEEN '$fecha1 00:00:00' and '$fecha2 23:59:00'
                AND tbl3.id_estado IN ('E2')
                AND tbl3.id_estado <> 'A1'
                AND tbl3.id_tipo = 'T56'
                ) as cantOrdenPro
                ,
                (SELECT COUNT(id_estado)
                FROM rds_gop_ordenes tbl3
                WHERE tbl3.id_proyecto = tbl1.id_proyecto
                AND tbl3.fecha_ejecucion BETWEEN '$fecha1 00:00:00' and '$fecha2 23:59:00'
                AND tbl3.id_estado IN ('E4','C2')
                AND tbl3.id_estado <> 'A1'
                AND tbl3.id_tipo = 'T56'
                ) as cantOrdenEje
                FROM
                rds_gop_proyectos tbl1
                INNER JOIN rds_gop_ordenes tbl2 ON tbl1.id_proyecto = tbl2.id_proyecto
                WHERE tipo_proyecto = '$tipo'
                AND tbl2.id_tipo = 'T56'
                AND tbl2.id_estado <> 'A1'
                AND tbl2.id_estado <> 'E1'
                AND tbl2.fecha_ejecucion BETWEEN '$fecha1 00:00:00' and '$fecha2 23:59:00'
                GROUP BY tbl1.id_proyecto,tbl1.nombre,tbl1.fecha,tbl2.fecha_ejecucion
                ) as tbl
                WHERE tbl.fechaMenor BETWEEN '$fecha1 00:00:00' and '$fecha2 23:59:00'
                ORDER BY tbl.fechaMenor,tbl.nombre

                ";

            $proyectoManiobras = "
                SELECT tbl1.id_proyecto,tbl1.nombre,
                tbl2.id_orden,tbl2.fecha_ejecucion as fechaManiobra,
                tbl2.id_estado,
                MIN(tbl3.hora_ini) as horaIniMani, MAX(tbl3.hora_fin) as horaFinMani
                ,
                (
                    select  SUM(precio*cantidad_confirmada) 
                    from rds_gop_ordenes_mobra as tbl4
                    INNER JOIN rds_gop_ordenes tbl5 ON tbl4.id_orden = tbl5.id_orden
                    AND tbl5.id_estado IN ('E4','C2')
                    AND tbl4.id_proyecto = tbl1.id_proyecto
                    AND tbl5.id_orden = tbl2.id_orden
                    AND tbl5.id_tipo = 'T56'
                )  as valorE
                ,tbl2.nodos_utilizados,tbl2.wbs_utilzadas,tbl2.gom,tbl2.descargo
                FROM
                rds_gop_proyectos tbl1
                INNER JOIN rds_gop_ordenes tbl2 ON tbl1.id_proyecto = tbl2.id_proyecto
                INNER JOIN rds_gop_ordenes_manoobra_detalle tbl3 ON tbl3.id_orden = tbl2.id_orden
                AND tbl3.id_proyecto = tbl1.id_proyecto
                WHERE tipo_proyecto = '$tipo'
                AND tbl2.id_tipo = 'T56'
                AND tbl2.id_estado <> 'A1'
                AND tbl2.id_estado <> 'E1'
                AND fecha_ejecucion BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00'
                GROUP BY tbl1.id_proyecto,tbl1.nombre,tbl2.id_estado,
                tbl2.id_orden,tbl2.fecha_ejecucion,tbl2.nodos_utilizados,tbl2.wbs_utilzadas,tbl2.gom,tbl2.descargo
                ORDER BY tbl1.id_proyecto,tbl2.fecha_ejecucion asc
                ";

            $proyectos = DB::select($proyectosConsulta);
            $maniobras = DB::select($proyectoManiobras);

            
            foreach ($proyectos as $key => $value) {
                $arreglo1 = [];
                foreach ($maniobras as $key => $val) {
                    if($value->id_proyecto == $val->id_proyecto)
                        array_push($arreglo1,$val);
                }
                array_push($arreglo,
                    ["proyecto" => $value,"maniobra" => $arreglo1]);
            }
        }
        else{

        }
        
        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        //dd($arreglo);
        return view('proyectos.redes.trabajoprogramado.indexgantProyectos',
            array(
                'proyectos' => $arreglo,
                'fecha' => $this->fechaShort,
                'fecha2' => $nuevafecha,
                'fecha1' => $fechaActual
                ));
    }

    //Ruta index gantRestricciones
    public function indexgantRestricciones()
    {

        $arreglo = [];

        if(Session::has('tipo'))
        {
            $fecha1 = explode("/",Session::get('fecha1'))[2] . "-" .  explode("/",Session::get('fecha1'))[1] . "-" .  explode("/",Session::get('fecha1'))[0] ;
            $fecha2 = explode("/",Session::get('fecha2'))[2] . "-" .  explode("/",Session::get('fecha2'))[1] . "-" .  explode("/",Session::get('fecha2'))[0] ;
            $tipo = Session::get('tipo');
            $esta = Session::get('esta2');
            $proyecto = Session::get('proyecto');
            $orden = Session::get('orden');
            $responsable = Session::get('responsable');
                
            $proyectosConsulta = "
              select 
                tbl1.id_proyecto,tbl3.nombre as texto_restriccion1,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                id_orden,tbl2.nombre,tbl1.id_restriccion,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'A'
                    AND tbl5.id_proyecto = tbl1.id_proyecto
                ) as cantA,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'X'
                    AND tbl5.id_proyecto = tbl1.id_proyecto
                ) as cantX,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'C'
                    AND tbl5.id_proyecto = tbl1.id_proyecto
                ) as cantC
                from rds_gop_restriccionesProyecto as tbl1
                INNER JOIN rds_gop_proyectos tbl2 ON tbl1.id_proyecto = tbl2.id_proyecto
                INNER JOIN rds_gop_tipo_restriccion as tbl3 ON tbl3.id_tipo_restriccion = tbl1.texto_restriccion
                WHERE id_orden IS NULL";

                $proyectosConsulta .= " AND tbl1.id_proyecto LIKE '%$proyecto%'  and responsable LIKE '%$responsable%'  ";  
                
                if($tipo != "0")
                    $proyectosConsulta .=  " and tipo_proyecto = '$tipo' ";

                if($proyecto == "" && $orden == "")
                    $proyectosConsulta .=  " AND fecha_limite BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00' ";

                if($esta != "0")
                    $proyectosConsulta .=" AND tbl1.id_estado = '$esta' ";

                $proyectosConsulta .=  "
                GROUP BY tbl1.id_proyecto,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                id_orden,tbl2.nombre,tbl3.nombre,tbl1.id_restriccion,tbl1.id_restriccion
                ORDER BY fecha_limite asc
                ";

            $proyectoManiobras = "
                select 
                tbl1.id_proyecto,tbl3.nombre as texto_restriccion1,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                id_orden,tbl1.id_restriccion,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'A'
                    AND tbl5.id_orden = tbl1.id_orden
                ) as cantA,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'X'
                    AND tbl5.id_orden = tbl1.id_orden
                ) as cantX,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'C'
                    AND tbl5.id_orden = tbl1.id_orden
                ) as cantC
                from rds_gop_restriccionesProyecto as tbl1
                INNER JOIN rds_gop_tipo_restriccion as tbl3 ON tbl3.id_tipo_restriccion = tbl1.texto_restriccion
                INNER JOIN rds_gop_proyectos tbl2 ON tbl1.id_proyecto = tbl2.id_proyecto
                WHERE id_orden IS NOT NULL";

                $proyectoManiobras .= " AND tbl1.id_proyecto LIKE '%$proyecto%'  and responsable LIKE '%$responsable%'  ";  
                
                if($orden != "")
                    $proyectoManiobras .=  " and id_orden LIKE '%$orden%' ";

                if($tipo != "0")
                    $proyectoManiobras .=  " and tipo_proyecto = '$tipo' ";

                if($proyecto == "" && $orden == "")
                    $proyectoManiobras .=  " AND fecha_limite BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00' ";

                if($esta != "0")
                    $proyectoManiobras .=" AND tbl1.id_estado = '$esta' ";

                $proyectoManiobras .=  "
                ORDER BY fecha_limite asc
                ";

               // dd($proyectoManiobras);

            $proyectos = DB::select($proyectosConsulta);
            $maniobras = DB::select($proyectoManiobras);

            
            foreach ($proyectos as $key => $value) {
                $arreglo1 = [];
                foreach ($maniobras as $key => $val) {
                    if($value->id_proyecto == $val->id_proyecto)
                        array_push($arreglo1,$val);
                }
                array_push($arreglo,
                    ["proyecto" => $value,"maniobra" => $arreglo1]);
            }
        }
        
        


        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        //dd($arreglo);
        //dd($arreglo);
        return view('proyectos.redes.trabajoprogramado.indexgantRestricciones',
            array(
                'proyectos' => $arreglo,
                'fecha' => $this->fechaShort,
                'fecha2' => $nuevafecha,
                'fecha1' => $fechaActual
                ));
    }

    //Ruta index Scrum Restricciones
    public function indexscrumRestricciones()
    {

        /*URL CLIMAS
            http://www.accuweather.com/es/co/bogota/107487/weather-forecast/107487
            http://www.accuweather.com/es/co/bogota/107487/hourly-weather-forecast/107487

            https://adminlte.io/themes/AdminLTE/pages/widgets.html#

            https://home.openweathermap.org/api_keys
            http://samples.openweathermap.org/data/2.5/forecast?lat=4.61&lon=-74.088&appid=212874522ce3b5ad32fb797fb734c72e

            

        */
            /*
            $Headers = array(
                "Content-type: application/json",
                'Accept: application/json'
            );

            $url = "http://api.openweathermap.org/data/2.5/forecast?id=3688689&APPID=c025455c0095f9307e133e92ed9a4d0c";
            
            //Inicia conexion.
            $conexion = curl_init();
            //Indica un error de conexion
            if (FALSE === $conexion)
                throw new \Exception('failed to initialize');

            //para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
            curl_setopt($conexion,CURLOPT_RETURNTRANSFER,TRUE);
            //para incluir el header en el output.
            curl_setopt($conexion,CURLOPT_HEADER,0);//No muestra la cabecera en el resultado
            //Envía la cabecera
            curl_setopt($conexion, CURLOPT_HTTPHEADER, $Headers);
            //Dirección URL a capturar.
            curl_setopt($conexion,CURLOPT_URL,$url);
            //indicando que es post
            curl_setopt($conexion, CURLOPT_POST, 0);
            //Seteando los datos del webServicecs
            //curl_setopt($conexion, CURLOPT_POSTFIELDS, $data);
            //Para que no genere problemas con el certificado SSL
            curl_setopt($conexion, CURLOPT_SSL_VERIFYPEER, false);
            $resultado = curl_exec($conexion);

            if (FALSE === $resultado)
                throw new \Exception(curl_error($conexion), curl_errno($conexion));

            curl_close($conexion);
            $resultado = json_decode($resultado, true);

            //dd($resultado);

            $respuesta = $resultado['list'];

            for ($i=0; $i < count($resultado['list']); $i++) { 
                
                $fecha = explode(" ",$resultado['list'][$i]['dt_txt'])[0];
                $hora = explode(" ",$resultado['list'][$i]['dt_txt'])[1];
                $dt = $resultado['list'][$i]['dt'];
                $temp = $resultado['list'][$i]['main']['temp'];
                $temp_min = $resultado['list'][$i]['main']['temp_min'];
                $temp_max = $resultado['list'][$i]['main']['temp_max'];
                $pressure = $resultado['list'][$i]['main']['pressure'];
                $sea_level = $resultado['list'][$i]['main']['sea_level'];
                $grnd_level = $resultado['list'][$i]['main']['grnd_level'];
                $humidity = $resultado['list'][$i]['main']['humidity'];

                //Clima
                $id_clima = $resultado['list'][$i]['weather'][0]['id'];
                $cli_tipo = $resultado['list'][$i]['weather'][0]['main'];
                $cli_des = $resultado['list'][$i]['weather'][0]['description'];
                $cli_icon = $resultado['list'][$i]['weather'][0]['icon'];

                //Nubes
                $nubes = $resultado['list'][$i]['clouds']['all'];

                //Viento
                $velocidad = $resultado['list'][$i]['wind']['speed'];
                $grado = $resultado['list'][$i]['wind']['deg'];

                $ultimaConsul = 0;

                if($i == 0)
                    $ultimaConsul = 1;

                $datos = DB::Table('gop_clima')
                    ->where('fecha_clima',$fecha)
                    ->count();

                if($datos == 0)
                {
                    DB::Table('gop_clima')
                    ->insert(array(
                        'fecha_clima' => $fecha,
                        'fecha_servidor' => $this->fechaALong,
                        'ultima_consulta' => $ultimaConsul
                        ));    
                }
                
                $datos1 = DB::Table('gop_clima_detalle')
                    ->where('fecha_clima',$fecha)
                    ->where('hora',$hora)
                    ->count();

                if($datos1 == 0)
                {
                    DB::Table('gop_clima_detalle')
                    ->insert(array(
                        'fecha_clima' => $fecha,
                        'hora' => $hora,
                        'dt' => $dt,
                        'temp' => $temp,
                        'temp_max' => $temp_max,
                        'temp_min' => $temp_min,
                        'presion_atmos' => $pressure,
                        'presion_mar' => $sea_level,
                        'presion_suel' => $grnd_level,
                        'humedad' => $humidity,
                        'id_clima' => $id_clima,
                        'clima_tipo' => $cli_tipo,
                        'clima_desp' => $cli_des,
                        'clima_icon' => $cli_icon,
                        'clima_nubes' => $nubes,
                        'viento_velocidad' => $velocidad,
                        'viento_grado' => $grado
                        ));
                }
                
            }
            */

        $proyectos = [];
        if(Session::has('tipo'))
        {
            $fecha1 = explode("/",Session::get('fecha1'))[2] . "-" .  explode("/",Session::get('fecha1'))[1] . "-" .  explode("/",Session::get('fecha1'))[0] ;
            $fecha2 = explode("/",Session::get('fecha2'))[2] . "-" .  explode("/",Session::get('fecha2'))[1] . "-" .  explode("/",Session::get('fecha2'))[0] ;
            $tipo = Session::get('tipo');
            $proyecto = Session::get('proyecto');
            $orden = Session::get('orden');
            $responsable = Session::get('responsable');

            $proyectosConsulta = "
              select 
                tbl1.id_proyecto,tbl3.nombre as texto_restriccion1,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                id_orden,tbl2.nombre,tbl1.texto_restriccion,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'A'
                    AND tbl5.id_proyecto = tbl1.id_proyecto
                ) as cantA,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'X'
                    AND tbl5.id_proyecto = tbl1.id_proyecto
                ) as cantX,
                (SELECT COUNT(id_estado)
                    FROM rds_gop_restriccionesProyecto as tbl5
                    WHERE id_estado = 'C'
                    AND tbl5.id_proyecto = tbl1.id_proyecto
                ) as cantC,tbl1.id_restriccion 
                from rds_gop_restriccionesProyecto as tbl1
                INNER JOIN rds_gop_proyectos tbl2 ON tbl1.id_proyecto = tbl2.id_proyecto
                INNER JOIN rds_gop_tipo_restriccion as tbl3 ON tbl3.id_tipo_restriccion = tbl1.texto_restriccion WHERE ";
                
                $proyectosConsulta .= " tbl1.id_proyecto LIKE '%$proyecto%'  and responsable LIKE '%$responsable%'  ";  
                
                if($orden != "")
                    $proyectosConsulta .=  " and id_orden LIKE '%$orden%' ";

                if($tipo != "0")
                    $proyectosConsulta .=  " and tipo_proyecto = '$tipo' ";

                if($proyecto == "" && $orden == "")
                    $proyectosConsulta .=  " AND fecha_limite BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00' ";
                
                $proyectosConsulta .= " GROUP BY tbl1.id_proyecto,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                id_orden,tbl2.nombre,tbl3.nombre,tbl1.id_restriccion 
                ORDER BY fecha_limite asc";
            $proyectos = DB::select($proyectosConsulta);
        }


        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        //dd($arreglo);
        //dd($arreglo);
        return view('proyectos.redes.trabajoprogramado.indexScrumRestriccion',
            array(
                'proyectos' => $proyectos,
                'fecha' => $this->fechaShort,
                'fecha2' => $nuevafecha,
                'fecha1' => $fechaActual
                )); 
    }

    //Ruta index Tabla Restricciones
    public function indextableRestricciones()
    {
        $proyectos = [];
        if(Session::has('tipo'))
        {
            $fecha1 = explode("/",Session::get('fecha1'))[2] . "-" .  explode("/",Session::get('fecha1'))[1] . "-" .  explode("/",Session::get('fecha1'))[0] ;
            $fecha2 = explode("/",Session::get('fecha2'))[2] . "-" .  explode("/",Session::get('fecha2'))[1] . "-" .  explode("/",Session::get('fecha2'))[0] ;
            $tipo = Session::get('tipo');
            $esta = Session::get('esta2');
            $proyecto = Session::get('proyecto');
            $orden = Session::get('orden');
            $responsable = Session::get('responsable');

            $proyectosConsulta = "
              select 
                tbl1.id_proyecto,tbl3.nombre as texto_restriccion1,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                tbl1.id_orden,tbl2.nombre,tbl1.id_restriccion,tbl4.gom,
                tbl4.fecha_ejecucion,fecha_registro,restriccion_descripcion
                from rds_gop_restriccionesProyecto as tbl1
                INNER JOIN rds_gop_proyectos tbl2 ON tbl1.id_proyecto = tbl2.id_proyecto
                INNER JOIN rds_gop_ordenes tbl4 ON tbl1.id_orden = tbl4.id_orden
                INNER JOIN rds_gop_tipo_restriccion as tbl3 ON tbl3.id_tipo_restriccion = tbl1.texto_restriccion WHERE";

                $proyectosConsulta .= " tbl1.id_proyecto LIKE '%$proyecto%'  and responsable LIKE '%$responsable%'  ";  
                
                if($orden != "")
                    $proyectosConsulta .=  " and tbl1.id_orden LIKE '%$orden%' ";

                if($tipo != "0")
                    $proyectosConsulta .=  " and tipo_proyecto = '$tipo' ";

                if($proyecto == "" && $orden == "")
                    $proyectosConsulta .=  " AND tbl4.fecha_ejecucion BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00' ";

                if($esta != "0")
                    $proyectosConsulta .=" AND tbl1.id_estado = '$esta' ";

                $proyectosConsulta .=  "
                GROUP BY tbl1.id_proyecto,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                tbl1.id_orden,tbl2.nombre,tbl3.nombre,tbl1.id_restriccion,tbl4.gom,tbl4.fecha_ejecucion,restriccion_descripcion,fecha_registro
                ORDER BY fecha_limite asc
                ";

            $proyectos = DB::select($proyectosConsulta);
            
        }
        
        


        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        //dd($arreglo);
        //dd($arreglo);
        return view('proyectos.redes.trabajoprogramado.indexTableRestriccion',
            array(
                'proyectos' => $proyectos,
                'fecha' => $this->fechaShort,
                'fecha2' => $nuevafecha,
                'fecha1' => $fechaActual
                )); 
    }

    //Ruta index cerrar Restricción
    public function cerrarRestric($proyecto = "",$orden = "",$id = "")
    {
        $pro = [];
        $ord = [];
        $res = [];
        $restD = [];
        $correos = null;

        Session::forget('orden_restriccion');
        Session::forget('proyecto_res');
        Session::forget('rest');

        if($proyecto != "")
        {
            Session::flash('proyecto_res',$proyecto);
            $ord = DB::table($this->tblAux . 'ordenes')
                    ->where('id_proyecto',$proyecto)
                    ->select('id_orden')
                    ->get();

            $res = DB::table($this->tblAux . 'restriccionesProyecto as tbl1')
                    ->join($this->tblAux .'tipo_restriccion as tbl2','tbl1.texto_restriccion','=','tbl2.id_tipo_restriccion')
                    ->where('id_proyecto',$proyecto)
                    ->whereNull('id_orden')
                    ->select('id_restriccion','tbl2.nombre')
                    ->get();
        }

        if($orden != "" && $orden != "0")
        {
            Session::flash('orden_restriccion',$orden);
            $res = DB::table($this->tblAux . 'restriccionesProyecto as tbl1')
                    ->join($this->tblAux .'tipo_restriccion as tbl2','tbl1.texto_restriccion','=','tbl2.id_tipo_restriccion')
                    ->where('id_proyecto',$proyecto)
                    ->where('id_orden',$orden)
                    ->select('id_restriccion','tbl2.nombre')
                    ->get();
        }

        if($id != "")
        {
            Session::flash('rest',$id);

            $proyectosConsulta = "
              select 
                tbl1.id_proyecto,tbl3.nombre as texto_restriccion1,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                id_orden,tbl2.nombre,restriccion_descripcion,tbl1.id_restriccion
                from rds_gop_restriccionesProyecto as tbl1
                INNER JOIN rds_gop_proyectos tbl2 ON tbl1.id_proyecto = tbl2.id_proyecto
                INNER JOIN rds_gop_tipo_restriccion as tbl3 ON tbl3.id_tipo_restriccion = tbl1.texto_restriccion
                WHERE id_restriccion = '$id'
                GROUP BY tbl1.id_proyecto,texto_restriccion,impacto,fecha_limite,responsable,evidencia_cierre,tbl1.id_estado,fecha_cierre,
                id_orden,tbl2.nombre,tbl3.nombre,restriccion_descripcion,tbl1.id_restriccion
                ORDER BY fecha_limite asc
                ";

            $restD = DB::select($proyectosConsulta)[0];

            $correos = DB::Table('rds_gop_restricciones_correos')
                            ->where('id_restriccion',$id)
                            ->select('correo','responsable')
                            ->get();
            //dd($restD);
        }

        //dd($restD);
        return view('proyectos.redes.trabajoprogramado.cerrarRestriccion',array(
            'proC' => $pro,
            'ordC' => $ord,
            'restC' => $res,
            'resC2' => $res,
            'restD' => $restD,
            'correos' => $correos
            ));
    }

    public function indexscrumOrdenes()
    {
        $proyectos = [];
        $estaE = 0;
        $estaAn = 0;
        $estaPro = 0;
        if(Session::has('tipoO'))
        {
            $fecha1 = explode("/",Session::get('fecha1O'))[2] . "-" .  explode("/",Session::get('fecha1O'))[1] . "-" .  explode("/",Session::get('fecha1O'))[0] ;
            $fecha2 = explode("/",Session::get('fecha2O'))[2] . "-" .  explode("/",Session::get('fecha2O'))[1] . "-" .  explode("/",Session::get('fecha2O'))[0] ;
            $tipo = Session::get('tipoO');
            $proyecto = Session::get('proyectoO');
            $orden = Session::get('ordenO');
            $responsable = Session::get('cuadrillaO');
            ini_set('memory_limit', '-1');

            $proyectosConsulta = "
                SELECT *
                FROM(
                select 
                ROW_NUMBER() OVER(PARTITION BY tbl3.id_lider ORDER BY tbl1.id_orden DESC) AS Row,
                tbl1.id_orden,tbl2.id_proyecto,tbl3.id_lider,tbl3.hora_ini,tbl3.hora_fin,tbl3.id_tipo,tbl5.nombre as tipoC,
                tbl1.fecha_ejecucion,(tbl4.nombres + ' ' + tbl4.apellidos) as nombre,
                tbl1.id_estado,tbl6.nombre as nombreE,tbl1.observaciones,tbl2.nombre as nombreP,tbl1.orden_nueva_reprogra,
                tbl1.fecha_reprogramacion,
                (
                    select top(1) tbl7.id_movil
                    from rds_gop_cuadrilla as tbl7
                    where tbl7.id_lider = tbl3.id_lider
                ) as id_movil,
                (
                    select sum(tblorden1.cantidad_confirmada * precio)
                    from rds_gop_ordenes_manoobra tblorden1
                    inner join rds_gop_baremos tblbare on tblbare.codigo = tblorden1.id_baremo
                    where tblorden1.id_orden = tbl1.id_orden
                    and periodo = 2017
                ) as programado,
                ISNULL(( 
                    select sum(tblorden1.cantidad * tblorden1.precio)
                    from rds_gop_mobra tblorden1
                    where tblorden1.id_orden = tbl1.id_orden
                ),0) as ejecutado,tbl1.nodos_utilizados,tbl1.wbs_utilzadas,tbl1.gom,tbl1.descargo
                from rds_gop_ordenes as tbl1
                inner join rds_gop_proyectos as tbl2 on tbl1.id_proyecto = tbl2.id_proyecto
                inner join rds_gop_ordenes_manoobra_detalle as tbl3 on tbl1.id_orden = tbl3.id_orden
                inner join rh_personas as tbl4 on tbl4.identificacion = tbl3.id_lider
                inner join rds_gop_tipo_cuadrilla as tbl5 on tbl5.id_tipo_cuadrilla = tbl3.id_tipo
                inner join gop_estado_ordenes as tbl6 on tbl6.id_estado = tbl1.id_estado";

                $proyectosConsulta .= " WHERE  tbl3.id_estado = 0 and tbl1.id_estado <> 'E1' AND tbl1.id_proyecto LIKE '%$proyecto%'  and tbl3.id_lider LIKE '%$responsable%'  ";  
                
                if($orden != "")
                    $proyectosConsulta .=  " and tbl1.id_orden LIKE '%$orden%' ";

                if($tipo != "0")
                    $proyectosConsulta .=  " and tbl2.tipo_proyecto = '$tipo' ";

                if($proyecto == "" && $orden == "")
                    $proyectosConsulta .=  " AND fecha_ejecucion BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00'";

                $proyectosConsulta .=  " 
                    GROUP BY 
                    tbl1.id_orden,tbl2.id_proyecto,tbl3.id_lider,tbl3.hora_ini,tbl3.hora_fin,tbl3.id_tipo,tbl5.nombre,
                    tbl1.fecha_ejecucion,tbl4.nombres,tbl4.apellidos,
                    tbl1.id_estado,tbl6.nombre,tbl1.observaciones,tbl2.nombre,tbl1.orden_nueva_reprogra,
                    tbl1.fecha_reprogramacion,tbl1.nodos_utilizados,tbl1.wbs_utilzadas,tbl1.gom,tbl1.descargo
                    ) AS TBL1
                    --WHERE TBL1.Row = 1
                    order by TBL1.fecha_ejecucion asc , TBL1.id_orden asc,TBL1.hora_ini asc";
            
               // dd($proyectosConsulta);
            $proyectos = DB::select($proyectosConsulta);

            //dd($proyectosConsulta);

            $consultaEstaEjecutadas = "
               select count(*) as estadoE from ( select count(tbl1.id_estado) as estadoE
                from rds_gop_ordenes as tbl1
                inner join rds_gop_proyectos as tbl2 on tbl1.id_proyecto = tbl2.id_proyecto
                inner join rds_gop_ordenes_manoobra_detalle as tbl3 on tbl1.id_orden = tbl3.id_orden";

                $consultaEstaEjecutadas .= " WHERE tbl1.id_estado IN ('E4','C2') AND tbl1.id_proyecto LIKE '%$proyecto%'  and tbl3.id_lider LIKE '%$responsable%'  ";  
                
                if($orden != "")
                    $consultaEstaEjecutadas .=  " and tbl1.id_orden LIKE '%$orden%' ";

                if($tipo != "0")
                    $consultaEstaEjecutadas .=  " and tbl2.tipo_proyecto = '$tipo' ";

                if($proyecto == "" && $orden == "")
                    $consultaEstaEjecutadas .=  " AND fecha_ejecucion BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00' ";

                $consultaEstaEjecutadas .=  " GROUP BY tbl1.id_orden) as tbl";

            $estaE = DB::select($consultaEstaEjecutadas)[0]->estadoE;

            $consultaEstaAnuladas = "
               select count(*) as estadoE from ( select count(tbl1.id_estado) as estadoE
                from rds_gop_ordenes as tbl1
                inner join rds_gop_proyectos as tbl2 on tbl1.id_proyecto = tbl2.id_proyecto
                inner join rds_gop_ordenes_manoobra_detalle as tbl3 on tbl1.id_orden = tbl3.id_orden";

                $consultaEstaAnuladas .= " WHERE tbl1.id_estado IN ('A1') AND tbl1.id_proyecto LIKE '%$proyecto%'  and tbl3.id_lider LIKE '%$responsable%'  ";  
                
                if($orden != "")
                    $consultaEstaAnuladas .=  " and tbl1.id_orden LIKE '%$orden%' ";

                if($tipo != "0")
                    $consultaEstaAnuladas .=  " and tbl2.tipo_proyecto = '$tipo' ";

                if($proyecto == "" && $orden == "")
                    $consultaEstaAnuladas .=  " AND fecha_ejecucion BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00' ";

                $consultaEstaAnuladas .=  " GROUP BY tbl1.id_orden) as tbl ";


            $estaAn = DB::select($consultaEstaAnuladas)[0]->estadoE;

            $consultaEstaProgramadas = "
              select count(*) as estadoE from (select count(tbl1.id_estado) as estadoE
                from rds_gop_ordenes as tbl1
                inner join rds_gop_proyectos as tbl2 on tbl1.id_proyecto = tbl2.id_proyecto
                inner join rds_gop_ordenes_manoobra_detalle as tbl3 on tbl1.id_orden = tbl3.id_orden";

                $consultaEstaProgramadas .= " WHERE tbl1.id_estado IN ('E2') AND tbl1.id_proyecto LIKE '%$proyecto%'  and tbl3.id_lider LIKE '%$responsable%'  ";  
                
                if($orden != "")
                    $consultaEstaProgramadas .=  " and tbl1.id_orden LIKE '%$orden%' ";

                if($tipo != "0")
                    $consultaEstaProgramadas .=  " and tbl2.tipo_proyecto = '$tipo' ";

                if($proyecto == "" && $orden == "")
                    $consultaEstaProgramadas .=  " AND fecha_ejecucion BETWEEN  '$fecha1 00:00:00' and '$fecha2 23:59:00' ";


                $consultaEstaProgramadas .=  " GROUP BY tbl1.id_orden) as tbl ";

            $estaPro = DB::select($consultaEstaProgramadas)[0]->estadoE;
            
        }

        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];
        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        return view('proyectos.redes.trabajoprogramado.indexScrumOrdenes',
            array(
                'proyectos' => $proyectos,
                'fecha' => $this->fechaShort,
                'fecha2' => $nuevafecha,
                'fecha1' => $fechaActual,
                'eje' => $estaE,
                'anu' => $estaAn,
                'pro' => $estaPro
                )); 
    }

    public function indexReportesOrdenes()
    {
        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];
        $nuevafecha = strtotime ( '-3 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];
        
        $arr = [];
        $horas = [];
        $bare = [];
        if(Session::has('cuadrilla'))
        {
            $fecha1 = explode("/",Session::get('fecha_inicio_reporte'))[2] . "-" . explode("/",Session::get('fecha_inicio_reporte'))[1] . "-" .  explode("/",Session::get('fecha_inicio_reporte'))[0];
            $fecha2 = explode("/",Session::get('fecha_corte_reporte'))[2] . "-" . explode("/",Session::get('fecha_corte_reporte'))[1] . "-" . explode("/",Session::get('fecha_corte_reporte'))[0];

            $cad = "EXEC sp_" . $this->tblAux1 . "reportes_capacidad_instalada '" . 1 . "', '" .$fecha1 . "', '" .  $fecha2 . "' , '" . Session::get('cuadrilla') ."'";
            $arr = DB::select("SET NOCOUNT ON;" . $cad);
           
            $cad = "EXEC sp_" . $this->tblAux1 . "reportes_capacidad_instalada '" . 2 . "', '" .$fecha1 . "', '" .  $fecha2 . "' , '" . Session::get('cuadrilla') ."'";
            $horas = DB::select("SET NOCOUNT ON;" . $cad);

            $cad = "EXEC sp_" . $this->tblAux1 . "reportes_capacidad_instalada '" . 3 . "', '" .$fecha1 . "', '" .  $fecha2 . "' , '" . Session::get('cuadrilla') ."'";
            $bare = DB::select("SET NOCOUNT ON;" . $cad);

        }
        

        return view('proyectos.redes.trabajoprogramado.indexReportesCapacidad',
            array(
                'fecha2' => $nuevafecha,
                'fecha1' => $fechaActual,
                'reporte1' => $arr,
                'reporte2' => $horas,
                'reporte3' => $bare,
                )); 
    }


    //Exporte programación
    public function exportarProgramacion(Request $request)
    {
        $fecha1 = $request->all()['fecha1'];
        $fecha2 = $request->all()['fecha2'];

        $fecha1 = explode("/",$fecha1)[2] . "-" . explode("/",$fecha1)[1]  . "-" . explode("/",$fecha1)[0];
        $fecha2 = explode("/",$fecha2)[2] . "-" . explode("/",$fecha2)[1]  . "-" . explode("/",$fecha2)[0];

        $cad = "EXEC sp_" . $this->tblAux . "exporte_programacion '" . $fecha1
                     . "','" . $fecha2 . "'";

        $arr = DB::select("SET NOCOUNT ON;" . $cad);

        //dd($arr);
        \Excel::create('Exporte de Programación ' . $this->fechaALong, function($excel) use($arr) {            

                $excel->sheet('Exporte', function($sheet) use($arr){
                   
                    //$products = ["NOMBRE LIDER","Fecha","Dia Tex","Semana","Año","Mes","Día","apoyos - horarios","PROCESO","PROYECTO - RADICADO","GOM","WBS","NODOS","SUPERVISOR PLANER","SUPERVISOR EJECUTOR","Notas","Valor MO 2ª VISITA2","ORDEN"];

                    $products = ["NOMBRE LIDER","Fecha","Dia Tex","Semana","Año","Mes","Día","apoyos - horarios","PROCESO","PROYECTO - RADICADO","GOM","WBS","NODOS","Notas","Valor MO 2ª VISITA2","CUMPLIMIENTO","ORDEN"];
                    $sheet->fromArray($products);
                    

                    for ($i=0; $i < count($arr); $i++) {    

                        if(strtoupper($arr[$i]->observacion) != "")
                        {
                            $sheet->row($i +2, array(
                                str_replace("Ã‘","Ñ",$arr[$i]->lider),
                                $arr[$i]->fecha_programacion,
                                $arr[$i]->dia,
                                $arr[$i]->semana,
                                $arr[$i]->aNo,
                                $arr[$i]->mes,
                                $arr[$i]->diaNumero,
                                $arr[$i]->apoyo_horarios,
                                "",
                                "",
                                "",
                                "",
                                "",
                                /*$arr[$i]->supervisor,
                                $arr[$i]->supervisor_ejecutor,*/
                                strtoupper($arr[$i]->observacion),
                                "",
                                "",
                                $arr[$i]->id_orden
                                ));  
                        }
                        else
                        {
                            
                            
                            


                            $sheet->row($i +2, array(
                                str_replace("Ã‘","Ñ",$arr[$i]->lider),
                                $arr[$i]->fecha_programacion,
                                $arr[$i]->dia,
                                $arr[$i]->semana,
                                $arr[$i]->aNo,
                                $arr[$i]->mes,
                                $arr[$i]->diaNumero,
                                $arr[$i]->apoyo_horarios,
                                $arr[$i]->proceso,
                                $arr[$i]->proyecto_radicado,
                                $arr[$i]->gom,
                                $arr[$i]->wbs_utilzadas,
                                $arr[$i]->nodos_utilizados,
                                /*$arr[$i]->supervisor,
                                $arr[$i]->supervisor_ejecutor,*/
                                strtoupper($arr[$i]->observacion),
                                number_format($arr[$i]->val,2,".",","),
                                "",
                                $arr[$i]->id_orden
                                ));     
                        }
                                              
                    }
                });
        })->export('xls');

    }

    /*-----FILTRO----*/
    /*ORDENES TRABAJO PROGRAMADO PROYECTOS*/
    public function filterOrdenesTP(Request $request)
    {
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $fecha_inicio_ymd = (count(explode("/",$fec1)) > 1 ? explode("/",$fec1)[2] . "-" . explode("/",$fec1)[1] . "-" . explode("/",$fec1)[0] : "" ) ;
        $fecha_corte_ymd = (count(explode("/",$fec2)) > 1 ? explode("/",$fec2)[2] . "-" . explode("/",$fec2)[1] . "-" . explode("/",$fec2)[0] : "" ) ;
        $tipo = $request->all()['id_tipo'];
        $esta = $request->all()['cbo_estado'];
        $numP = $request->all()['proyecto'];
        $proyN = $request->all()['proyectoN'];

        //var_dump($fecha_inicio_ymd);
        Session::flash('tipo',$tipo);
        Session::flash('fecha1',$fecha_inicio_ymd);
        Session::flash('fecha2',$fecha_corte_ymd);
        Session::flash('proyecto',$numP);
        Session::flash('proyectoN',$proyN);

        Session::flash('tipo1',$tipo);
        Session::flash('fecha11',$fecha_inicio_ymd);
        Session::flash('fecha21',$fecha_corte_ymd);
        Session::flash('proyecto1',$numP);
        Session::flash('proyectoN1',$proyN);


        //Session::flash('tipo',$request->all()['tipo']);
        Session::flash('estado',$esta);

        return Redirect::to('../redes/ordenes/home'); 
    }

    /*ORDENES TRABAJO PROGRAMADO ORDENES*/
    public function filterOrdenTP(Request $request)
    {
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $fecha_inicio_ymd = (count(explode("/",$fec1)) > 1 ? explode("/",$fec1)[2] . "-" . explode("/",$fec1)[1] . "-" . explode("/",$fec1)[0] : "" ) ;
        $fecha_corte_ymd = (count(explode("/",$fec2)) > 1 ? explode("/",$fec2)[2] . "-" . explode("/",$fec2)[1] . "-" . explode("/",$fec2)[0] : "" ) ;
        $tipo = $request->all()['id_tipo'];
        $esta = $request->all()['cbo_estado'];
        $numP = $request->all()['proyecto'];
        $proyN = $request->all()['proyectoN'];

        //var_dump($fecha_inicio_ymd);
        Session::flash('tipo',$tipo);
        Session::flash('fecha1',$fecha_inicio_ymd);
        Session::flash('fecha2',$fecha_corte_ymd);
        Session::flash('proyecto',$numP);
        Session::flash('proyectoN',$proyN);

        Session::flash('ordenF1',$request->all()['ordenManiObra']);
        Session::flash('gomF1',$request->all()['ordenGOM']);

        Session::flash('tipo1',$tipo);
        Session::flash('fecha11',$fec1);
        Session::flash('fecha21',$fec2);
        Session::flash('proyecto1',$numP);
        Session::flash('proyectoN1',$proyN);
        Session::flash('ordenM12',$request->all()['ordenManiObra']);
        Session::flash('gomM2',$request->all()['ordenGOM']);


        //Session::flash('tipo',$request->all()['tipo']);
        Session::flash('estado',$esta);

        return Redirect::to('../redes/ordenes/orden'); 
    }

    /*ORDENES TRABAJO PROGRAMADO DOCUMENTOS*/
    public function filterDocTP(Request $request)
    {
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $fecha_inicio_ymd = (count(explode("/",$fec1)) > 1 ? explode("/",$fec1)[2] . "-" . explode("/",$fec1)[1] . "-" . explode("/",$fec1)[0] : "" ) ;
        $fecha_corte_ymd = (count(explode("/",$fec2)) > 1 ? explode("/",$fec2)[2] . "-" . explode("/",$fec2)[1] . "-" . explode("/",$fec2)[0] : "" ) ;
        $tipo = $request->all()['id_tipo'];
        $esta = $request->all()['cbo_estado'];
        $numP = $request->all()['proyecto'];
        $proyN = $request->all()['proyectoN'];
        $orden = $request->all()['ordenManiObra'];

        //var_dump($fecha_inicio_ymd);
        Session::flash('tipo',$tipo);
        Session::flash('fecha1',$fecha_inicio_ymd);
        Session::flash('fecha2',$fecha_corte_ymd);
        Session::flash('proyecto',$numP);
        Session::flash('proyectoN',$proyN);
        Session::flash('ordenM',$orden);

        Session::flash('tipo1',$tipo);
        Session::flash('fecha11',$fecha_inicio_ymd);
        Session::flash('fecha21',$fecha_corte_ymd);
        Session::flash('proyecto1',$numP);
        Session::flash('proyectoN1',$proyN);
        Session::flash('ordenM1',$orden);


        //Session::flash('tipo',$request->all()['tipo']);
        Session::flash('estado',$esta);

        return Redirect::to('../redes/ordenes/documentos'); 
    }

    public function filtergantProyectos(Request $request)
    {
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $proy = $request->all()['id_tipo'];

        Session::flash('tipo',$proy);
        Session::flash('fecha1',$fec1);
        Session::flash('fecha2',$fec2);

        Session::flash('tipo1',$proy);
        Session::flash('fecha11',$fec1);
        Session::flash('fecha21',$fec2);

        return Redirect::to('ganntProyectos');
    }

    public function filterRestriccion(Request $request)
    {
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $proy = $request->all()['id_tipo'];
        $esta = $request->all()['id_estado'];

        Session::flash('tipo',$proy);
        Session::flash('fecha1',$fec1);
        Session::flash('fecha2',$fec2);
        Session::flash('esta2',$esta);

        Session::flash('tipo1',$proy);
        Session::flash('fecha11',$fec1);
        Session::flash('fecha21',$fec2);
        Session::flash('esta21',$esta);

        Session::flash('proyecto',$request->all()['name_proyecto']);
        Session::flash('orden',$request->all()['name_orden']);
        Session::flash('responsable',$request->all()['responsable']);

        return Redirect::to('ganntRestricciones');
    }

    public function filterScrumRe(Request $request)
    {
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $proy = $request->all()['id_tipo'];

        Session::flash('tipo',$proy);
        Session::flash('fecha1',$fec1);
        Session::flash('fecha2',$fec2);
        Session::flash('proyecto',$request->all()['name_proyecto']);
        Session::flash('orden',$request->all()['name_orden']);
        Session::flash('responsable',$request->all()['responsable']);

        Session::flash('tipo1',$proy);
        Session::flash('fecha11',$fec1);
        Session::flash('fecha21',$fec2);

        return Redirect::to('scrumRestricciones');
    }

    public function filterScrumOrdenes(Request $request)
    {
        Session::flash('tipoO',$request->all()['id_tipo']);
        Session::flash('fecha1O',$request->all()['fecha_inicio']);
        Session::flash('fecha2O',$request->all()['fecha_corte']);
        Session::flash('proyectoO',$request->all()['name_proyecto']);
        Session::flash('ordenO',$request->all()['name_orden']);
        Session::flash('cuadrillaO',$request->all()['responsable']);

        return Redirect::to('scrumOrdenes');
    }

    public function filterTableRe(Request $request)
    {
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $proy = $request->all()['id_tipo'];
        $esta = $request->all()['id_estado'];

        Session::flash('proyecto',$request->all()['name_proyecto']);
        Session::flash('orden',$request->all()['name_orden']);
        Session::flash('responsable',$request->all()['responsable']);

        Session::flash('tipo',$proy);
        Session::flash('fecha1',$fec1);
        Session::flash('fecha2',$fec2);
        Session::flash('esta2',$esta);

        Session::flash('tipo1',$proy);
        Session::flash('fecha11',$fec1);
        Session::flash('fecha21',$fec2);
        Session::flash('esta21',$esta);

        //var_dump($esta);
        return Redirect::to('restricciones');
    }

    //FILTER REPORTES ORDENES CAPACIDAD INSTALADA
    public function filterReportesOrden(Request $request)
    {
        Session::flash('fecha_inicio_reporte',$request->all()['fecha_inicio']);
        Session::flash('fecha_corte_reporte',$request->all()['fecha_corte']);
        Session::flash('cuadrilla',$request->all()['responsable']);

        return Redirect::to('redes/ordenes/reportes'); 
    }

    /*-----FILTRO----*/


    public function AddVerproyecto(Request $request)
    {
        if($request->all()["pro"] == "0")
        {
            Session::forget('rds_gop_proyecto_id');
            return Redirect::to('redes/ordenes/ver');
        }
        else
        {
            Session::put('rds_gop_proyecto_id',$request->all()["pro"]);
            return Redirect::to('redes/ordenes/ver');
        }
    }

    //
    public function verOrden($pry = '')
    {

        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');
        
        //Tipo de ingreso a esta función

        if($pry != '') //Editar
            $consecutivo = $pry;   
        else //Nuevo
            $consecutivo = "";

        if($pry != '')
        {
            $cont = DB::Table($this->tblAux . 'proyectos')
                    ->where('id_proyecto',$consecutivo)
                    ->count();

            if($cont == 0)
                return Redirect::to('/redes/ordenes/home');
        }
        


        $circuitos = DB::table($this->tblAux . 'circuitos')
                    ->select('id_circuito','nombre_cto')
                    ->orderBy('nombre_cto')
                    ->get();

        $bodega = DB::table($this->tblAux1 . 'inv_bodegas')
                    ->whereIn('id_tipo',['B1','B2'])
                    ->select('id_bodega','nombre')
                    ->orderBy('nombre')
                    ->get();

        //var_dump($bodega);
        $nivelTension = DB::table($this->tblAux . 'niveltension')
                    ->select('codigo','nombre')
                    ->get();


        $proyecto = DB::table($this->tblAux . 'proyectos' . $this->valorT)
                    ->where('id_proyecto',$consecutivo)
                    ->select('fecha_creacion','nombre','observaciones','info_contacto','valor_inicial','id_estado'
                        ,'cod_cto','nombre_cto','zona','municipio','barrio','direccion'
                        ,'lm','lv','diagrama','id_bodega','tipo_proyecto','tipo_trabajo','tipo_proceso')
                    ->get();
        

        $wbs = DB::table($this->tblAux . 'ws' . $this->valorT)
                ->where('id_proyecto',$consecutivo)
                ->select('id_ws', 'nombre_ws', 'observaciones', 'gom')
                ->orderBy('nombre_ws')
                ->get();

        

        $wbsConsul = DB::table($this->tblAux . 'ws'  . $this->valorT)
                            ->where('id_proyecto',$consecutivo)
                            ->select('id_ws','nombre_ws')
                            ->orderBy('nombre_ws')
                            ->get();

        

        $nodos = DB::table($this->tblAux . 'nodos' . $this->valorT)
                ->join($this->tblAux . 'ws_nodos' . $this->valorT,$this->tblAux . 'ws_nodos' . $this->valorT . '.id_nodo1','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                ->join($this->tblAux . 'ws' . $this->valorT,$this->tblAux . 'ws' . $this->valorT . '.id_ws','=',$this->tblAux . 'ws_nodos' . $this->valorT . '.id_ws')
                ->where($this->tblAux . 'ws' . $this->valorT . '.id_proyecto',$consecutivo)
                ->select($this->tblAux . 'ws' . $this->valorT . '.id_ws as ws',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo as idN',$this->tblAux . 'ws' . $this->valorT . '.nombre_ws', $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo', $this->tblAux . 'nodos' . $this->valorT . '.cd', $this->tblAux . 'nodos' . $this->valorT . '.id_estado',
                 $this->tblAux . 'nodos' . $this->valorT . '.direccion',$this->tblAux . 'nodos' . $this->valorT . '.observaciones',$this->tblAux . 'nodos' . $this->valorT . '.nivel_tension',
                 $this->tblAux . 'nodos' . $this->valorT . '.punto_fisico',$this->tblAux . 'nodos' . $this->valorT . '.seccionador')
                ->get();


        $nodosCombox = DB::table($this->tblAux . 'nodos' . $this->valorT)
                ->where('id_proyecto',$consecutivo)
                ->select('id_nodo','nombre_nodo')
                ->get();

        $ordeneRealizada =  DB::table($this->tblAux . 'ordenes'  . $this->valorT)
                            ->join('gop_estado_ordenes','gop_estado_ordenes.id_estado','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_estado")
                            ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',$consecutivo)
                            ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_tipo','T56')
                            ->select('gop_estado_ordenes.nombre as id_estadoN','id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado','fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',
                                'cd','nivel_tension','direccion','gom',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.nodos_utilizados',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.descargo')
                            ->get();
                


        $orGrupoT = [];
        foreach ($ordeneRealizada as $orde => $val) {
            $usersRecuro = DB::table($this->tblAux .'ordenes_manoobra_detalle')
                    ->join($this->tblAux1 . 'inv_bodegas',$this->tblAux1 . 'inv_bodegas.id_bodega','=',$this->tblAux . 'ordenes_manoobra_detalle.id_lider')
                    ->where($this->tblAux .'ordenes_manoobra_detalle.id_orden',$val->id_orden)
                    ->where($this->tblAux .'ordenes_manoobra_detalle.id_estado',0)
                    ->select('id_lider',$this->tblAux1 . 'inv_bodegas.nombre')
                    ->get();
            array_push($orGrupoT,array($val,$usersRecuro));
        }

        $anio = 2017;
        if($this->tblAux == "apu_gop_")
            $anio = 2016;

        $baremos = DB::table($this->tblAux . 'detalle_mobra' . $this->valorT)
                    ->where($this->tblAux . 'detalle_mobra' . $this->valorT . '.id_proyecto',$consecutivo)
                    ->where($this->tblAux . 'baremos.periodo',$anio)
                    ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'detalle_mobra' . $this->valorT . '.id_nodo','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                    ->join($this->tblAux . 'baremos',$this->tblAux . 'baremos.codigo','=',$this->tblAux . 'detalle_mobra' . $this->valorT . '.id_baremo')
                    ->select($this->tblAux . 'detalle_mobra' . $this->valorT . '.id_baremo',$this->tblAux . 'detalle_mobra' . $this->valorT . '.cantidad_replanteo',
                        $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'baremos.actividad')
                    ->groupBy($this->tblAux . 'detalle_mobra' . $this->valorT . '.id_baremo',$this->tblAux . 'detalle_mobra' . $this->valorT . '.cantidad_replanteo',
                        $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'baremos.actividad')
                    ->orderBY($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo')
                    ->get();
                    


        $material = DB::table($this->tblAux . 'detalle_materiales' . $this->valorT)
                    ->where($this->tblAux . 'detalle_materiales' . $this->valorT . '.id_proyecto',$consecutivo)
                    ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'detalle_materiales' . $this->valorT . '.id_nodo','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                    ->join($this->tblAux1 . 'inv_maestro_articulos',$this->tblAux1 . 'inv_maestro_articulos.id_articulo','=',$this->tblAux . 'detalle_materiales' . $this->valorT . '.id_articulo')
                    ->select($this->tblAux1 . 'inv_maestro_articulos.codigo_sap',$this->tblAux . 'detalle_materiales' . $this->valorT . '.cantidad_replanteo',
                        $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux1 . 'inv_maestro_articulos.nombre')
                    ->groupBy($this->tblAux1 . 'inv_maestro_articulos.codigo_sap',$this->tblAux . 'detalle_materiales' . $this->valorT . '.cantidad_replanteo',
                        $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux1 . 'inv_maestro_articulos.nombre')
                    ->orderBY($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo')
                    ->get();

        $consultaRestric =  [];

        $arreglo = [];
        foreach ($consultaRestric as $key => $value) {
            array_push($arreglo,
                [$value,
                DB::table($this->tblAux .'restricciones_correos')
                    ->where('id_restriccion',$value->id_restriccion)
                    ->select('correo')
                    ->get()]);
        }

        /*$materiales = DB::table($this->tblAux1 . 'inv_documentos' . $this->valorT)
                    ->join('rds_inv_detalle_documentos' . $this->valorT,'rds_inv_detalle_documentos' . $this->valorT . '.id_documento','=','rds_inv_documentos' . $this->valorT . '.id_documento')
                    ->join($this->tblAux . 'nodos' . $this->valorT,'j');*/



        $descargosEstados =  DB::table($this->tblAux . 'estados_descargos')
                ->select('id_estado as id','nombre as nom')
                ->get();

        $descargos =  DB::table($this->tblAux . 'descargo_proyecto')
                ->where('id_proyecto',$consecutivo)
                ->select('id_estado as id','id_descargo as des')
                ->get();

        $estruc = DB::table($this->tblAux .'estructuras')
                    ->select('id','des_estruc')
                    ->get();

        $restricCombo = DB::table($this->tblAux .'tipo_restriccion')
                        ->select('id_tipo_restriccion','nombre')
                        ->orderBY('id_tipo_restriccion')
                        ->get();

        $resp = DB::Table($this->tblAux . 'responsable_restricciones')
                    ->get(['id','nombre','correo']);


        $proyectos = DB::table($this->tblAux . 'tipo_proyecto')
                        ->get(['id_proyecto','des_proyecto']);

        return view('proyectos.redes.trabajoprogramado.ver',
            array(
                    'proyec' => $consecutivo,
                    'circuitos' => $circuitos,
                    'bodega' => $bodega,
                    'proyectoA' => $proyectos,
                    'nivel_t' => $nivelTension,
                    'proyecto' => $proyecto,
                    'wbs' => $wbs,
                    'res' => $resp,
                    'wbsCombox' => $wbsConsul,
                    'nodos' => $nodos,
                    'nodoCombox' => $nodosCombox,
                    'actividades' => $baremos,
                    'material' => $material,
                    'opcTbl' => 1,
                    'prog' => null,
                    'ordenesRealizada' => $orGrupoT,
                    'restric' => $arreglo ,
                    'fechaR' => $this->fechaShort,
                    'descargos' => $descargos,
                    'descargosE' => $descargosEstados,
                    'encabezado' => null,
                    'estruc' => $estruc,
                    'restricC' => $restricCombo,
                    'fecha' => explode('-',$this->fechaShort)[2] . "/" . explode('-',$this->fechaShort)[1] . "/" .explode('-',$this->fechaShort)[0]
                ));
    }

    /********************************************/
    /*  Funciones utilizadas por el controlador */
    /********************************************/
    public function cambiaf_a_mysql($fecha,$tipo1)
    {
        $fechaA = Carbon::now('America/Bogota');
        $fechaALong = $fechaA->toDateTimeString();   
        $fechaShort = $fechaA->format('Y-m-d');
        $lafecha = "";

        if($tipo1 == 1)
            if(count(explode("/",$fecha)) > 1)
                $lafecha = explode("/",$fecha)[0] . "-" . explode("/",$fecha)[1] . "-" . explode("/",$fecha)[2];
            else
                $lafecha = "";
        else
        {
            if(count(explode("/",$fecha)) > 1)
                $lafecha = explode("/",$fecha)[2] . "-" . explode("/",$fecha)[1] . "-" . explode("/",$fecha)[0];
            else
                $lafecha = "";
        }
        
        return $lafecha;
    }

    private function generaConsecutivo($tipo)
    {
        $consen = DB::table('gen_consecutivos' . $this->valorT)
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
        DB::table('gen_consecutivos'. $this->valorT)
            ->where('id_campo',$tipo)
            ->update(array(
                'consecutivo' => $lnconsecutivo
                ));
        
        $num_relleno    = $longitud_max - strlen($prefijo) ; 
        $char_rellenos  = self::lfillchar($lnconsecutivo,$num_relleno,$relleno) ;
        $ret            = $prefijo.$char_rellenos.$lnconsecutivo ; 
        return $ret; 
    }

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

    /*CARGO DE ARCHIVOS EXCEL*/
    public function uploadExcel(Request $request)
    {

       $pry = $request->all()['proyecto'];

       $ordenes = DB::table($this->tblAux . 'ordenes'  . $this->valorT)
                ->where('id_proyecto',$pry)
                ->select('id_orden')
                ->get();

        if(count($ordenes) > 0)
        {
            if(isset($request->all()["crear_nuevo_1"]))
            {
                Session::flash('dataExcel1',".");
                Session::flash('dataExcel2',"No puede cargar nuevamente las Actividades, por que ya tiene ordenes generadas en el proyecto");
                return Redirect::to('/redes/ordenes/ver');
            }
        }


        //obtenemos el campo file definido en el formulario
       $file = $request->file('file_upload');

       //Varificamos que carge un .xlsx
       $mime = $file->getMimeType();
       if($mime != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
       {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"Tipo de archivo invalido, tiene que carga un archivo .xlsx");
            return Redirect::to('/redes/ordenes/ver');
       }

       //obtenemos el nombre del archivo
       $nombre = $file->getClientOriginalName();

       //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre,  \File::get($file));

       $ruta = "storage/app" . "/" . $nombre;



       if(isset($request->all()["crear_nuevo_1"]))
       {
            //Eliminar toda la base del proyecto
            //Actividades
            DB::table($this->tblAux . 'detalle_mobra'  . $this->valorT)
                                ->where('id_proyecto',$pry)
                                ->delete();

            //Materiales
            DB::table($this->tblAux . 'detalle_materiales'  . $this->valorT)
                                ->where('id_proyecto',$pry)
                                ->delete();

            //Nodos
            DB::table($this->tblAux . 'nodos'  . $this->valorT)
                                ->where('id_proyecto',$pry)
                                ->delete();
            //WBS
            DB::table($this->tblAux . 'ws'  . $this->valorT)
                                ->where('id_proyecto',$pry)
                                ->delete();

            //Relacion GOMS
            DB::table($this->tblAux . 'ws_gom'  . $this->valorT)
                                ->where('id_proyecto',$pry)
                                ->delete();
       }

       set_time_limit(0);

       $result = \Excel::load($ruta, function($reader) use ($request) {
            
            $proyecto = $request->all()['proyecto'];

            $results = $reader->toArray();

            if(isset($results[0]["cant"]) == false ||
                    isset($results[0]["wbs"]) == false ||
                    isset($results[0]["nodo"]) == false ||
                    isset($results[0]["actividad"]) == false ||
                    isset($results[0]["direccion"]) == false )
            {
                Session::flash('dataExcel2',"El archivo que esta tratando  de cargar para las Actividades no es válido ");                  
            }
            else
            {
                for ($i=0; $i < count($results); $i++) {

                if($results[$i]["wbs"] == "" || $results[$i]["wbs"] == NULL || $results[$i]["wbs"] == null ||
                    $results[$i]["nodo"] == "" || $results[$i]["nodo"] == NULL || $results[$i]["nodo"] == null ||
                    $results[$i]["actividad"] == "" || $results[$i]["actividad"] == NULL || $results[$i]["actividad"] == null)
                    continue;


                $wbs = str_replace("WBS ", "", $results[$i]["wbs"]);
                $wbs = str_replace("WBS", "", $wbs);
                $wbs = str_replace(" ", "", $wbs);
                $wbs = trim($wbs);

                //NODOS
                $wbsCargadas = DB::table($this->tblAux . 'ws'  . $this->valorT)
                                ->where('id_proyecto',$proyecto)
                                ->where('nombre_ws',$wbs)
                                ->select('id_ws')
                                ->get();


                $consecutivoWB = 0;
                if(count($wbsCargadas) == 0)
                {
                    $consecutivoWB = self::generaConsecutivo("ID_WS");
                        DB::table($this->tblAux . 'ws'  . $this->valorT)
                        ->insert(array(
                            array(
                                'id_ws' => $consecutivoWB,
                                'id_proyecto' => $proyecto,
                                'nombre_ws' => $wbs
                                )));
                }
                else
                {
                    $consecutivoWB = $wbsCargadas[0]->id_ws;
                }




                //NODOS
                $nodo = str_replace(" ", "", $results[$i]["nodo"]);
                $nodo = trim($nodo);

                $nodoCargado = DB::table($this->tblAux . 'nodos'  . $this->valorT)
                            ->where('id_proyecto',$proyecto)
                            ->where('id_ws',$consecutivoWB)
                            ->where('nombre_nodo',$nodo)
                            ->select('id_nodo')
                            ->get();

                $consecutivoNODO = "0";
                if(count($nodoCargado) == 0)
                {
                    $consecutivoNODO = self::generaConsecutivo("ID_NODO");

                    DB::table($this->tblAux . 'nodos'  . $this->valorT)
                            ->insert(array(
                                array(
                                    'id_ws' => $consecutivoWB,
                                    'id_proyecto' => $proyecto,
                                    'id_nodo' => $consecutivoNODO,
                                    'nombre_nodo' => $nodo,
                                    'cd' => $results[$i]["cd"],
                                    'id_estado' => "G",
                                    'direccion' => $results[$i]["direccion"],
                                    'nivel_tension' => $results[$i]["nt"],
                                    'punto_fisico' => $results[$i]["pf"],
                                    'seccionador' => $results[$i]["sec"]
                                    )));

                    $nodCant2 = DB::table($this->tblAux . 'ws_nodos'  . $this->valorT)
                            ->where('id_ws',$consecutivoWB)
                            ->where('id_proyecto',$proyecto)
                            ->where('id_nodo1',$consecutivoNODO)
                            ->get();

                    if(count($nodCant2) == 0)
                    {
                        DB::table($this->tblAux . 'ws_nodos'  . $this->valorT)
                        ->insert(array(
                            array(
                                'id_ws' => $consecutivoWB,
                                'id_proyecto' => $proyecto,
                                'id_nodo1' => $consecutivoNODO
                                )));
                    }
                }
                else
                {
                    $consecutivoNODO = $nodoCargado[0]->id_nodo;
                }

                
                //Actividad
                $actividad = str_replace(" ", "", $results[$i]["actividad"]);
                $actividad = trim($actividad);

                $bare = DB::Table($this->tblAux . 'detalle_mobra' . $this->valorT)
                            ->where('id_ws',$consecutivoWB)
                            ->where('id_proyecto',$proyecto)
                            ->where('id_nodo',$consecutivoNODO)
                            ->where('id_baremo',$actividad)
                            ->select('cantidad_replanteo')
                            ->get();

                if(intval($results[$i]["cant"]) <= 0)
                    continue;

                $bareCont = DB::Table($this->tblAux . 'baremos')
                            ->where('codigo',$actividad)
                            ->where('periodo',2017)
                            ->count();

                if($bareCont <= 0)
                    continue;


                if(count($bare) == 0)
                {
                    DB::Table($this->tblAux . 'detalle_mobra' . $this->valorT)
                        ->insert(array(
                            array(
                                'id_proyecto' => $proyecto,
                                'id_ws' => $consecutivoWB,
                                'id_nodo' => $consecutivoNODO,
                                'id_baremo' => $actividad,
                                'cantidad_replanteo' => $results[$i]["cant"]
                                )));

                    self::saveLog("OPERA07",$proyecto,"BAREMO: $actividad CANTIDAD: " . $results[$i]["cant"] . " NODO: $consecutivoNODO WBS: $consecutivoWB EXCEL"); 

                }
                else
                {
                    // Se repite el siguiente BAREMO Que hacer hay?
                } 
                }
            //Session::put($this->tblAux . 'proyecto_id',$consecutivo);
            
                var_dump("Se esta cargando el documento en excel, espero un momento");
                
                $arrWBS = Array();
                $arrNodos = Array();
                $arrNodos2 = Array();

                $consecutivoNODO = 0;
                $consecutivoMATE = 0;
            }
            //var_dump($results);
        })->get();
        
        self::saveLog("OPERA03",$pry,"");
        Session::flash('dataExcel1',"Se ha cargado correctamente el Excel de las Actividades. HACE FALTA CARGAR EL EXCEL DE LOS MATERIALES");

        //var_dump("todo BIEN");
      return Redirect::to('/redes/ordenes/ver/' . $pry);
    }

    public function uploadExcelM(Request $request)
    {
        $pry = $request->all()['proyecto'];

        $ordenes = DB::table($this->tblAux . 'ordenes'  . $this->valorT)
                ->where('id_proyecto',$pry)
                ->select('id_orden')
                ->get();

        /*if(count($ordenes) > 0)
        {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"No puede cargar nuevamente los Materiales, por que ya tiene ordenes generadas en el proyecto");
            return Redirect::to('/redes/ordenes/ver');
        }*/

        $fileL= storage_path(). "\logsCargaExcel_" . $this->fechaShort . ".txt";
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
            return Redirect::to('/redes/ordenes/ver');
       }

       //obtenemos el nombre del archivo
       $nombre = $file->getClientOriginalName();

       //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre,  \File::get($file));

       $ruta = "storage/app" . "/" . $nombre;
     
       //var_dump($ruta);

       $repiteMaterial = true;
       if(isset($request->all()["crear_nuevo_11"]))
            $repiteMaterial = false;

       set_time_limit(0);


       $result = \Excel::load($ruta, function($reader) use ($request,$fileL,$repiteMaterial) {

            $results = $reader->toArray();
            $proyecto = $request->all()['proyecto'];


            if(isset($results[0]["wbs"])  == false||
                    isset($results[0]["nodo"])  == false||
                    isset($results[0]["material"])  == false||
                    isset($results[0]["cant"])  == false) 
            {
                Session::flash('dataExcel2',"El archivo que esta tratando de cargar para los Materiales no es válido");                  
            }
            else
            {
                $materialesNOExisten = "";
                for ($i=0; $i < count($results); $i++) {
                            
                 if($results[$i]["wbs"] == "" || $results[$i]["wbs"] == NULL || $results[$i]["wbs"] == null ||
                    $results[$i]["nodo"] == "" || $results[$i]["nodo"] == NULL || $results[$i]["nodo"] == null ||
                    $results[$i]["material"] == "" || $results[$i]["material"] == NULL || $results[$i]["material"] == null)
                    continue;


                    $wbs = str_replace("WBS ", "", $results[$i]["wbs"]);
                    $wbs = str_replace("WBS", "", $wbs);
                    $wbs = str_replace(" ", "", $wbs);
                    $wbs = trim($wbs);

                    
                    //NODOS
                    $wbsCargadas = DB::table($this->tblAux . 'ws'  . $this->valorT)
                                    ->where('id_proyecto',$proyecto)
                                    ->where('nombre_ws',$wbs)
                                    ->select('id_ws')
                                    ->get();
                    $consecutivoWB = 0;
                    if(count($wbsCargadas) == 0)
                    {
                        $consecutivoWB = self::generaConsecutivo("ID_WS");
                            DB::table($this->tblAux . 'ws'  . $this->valorT)
                            ->insert(array(
                                array(
                                    'id_ws' => $consecutivoWB,
                                    'id_proyecto' => $proyecto,
                                    'nombre_ws' => $wbs
                                    )));
                    }
                    else
                    {
                        $consecutivoWB = $wbsCargadas[0]->id_ws;
                    }


                    //NODOS
                    $nodo = str_replace(" ", "", $results[$i]["nodo"]);
                    $nodo = trim($nodo);

                    $nodoCargado = DB::table($this->tblAux . 'nodos'  . $this->valorT)
                                ->where('id_proyecto',$proyecto)
                                ->where('id_ws',$consecutivoWB)
                                ->where('nombre_nodo',$nodo)
                                ->select('id_nodo')
                                ->get();

                    $consecutivoNODO = "0";
                    if(count($nodoCargado) == 0)
                    {

                        $consecutivoNODO = "0";
                    }
                    else
                    {
                        $consecutivoNODO = $nodoCargado[0]->id_nodo;
                    }


                    //MATERIALES
                    $material = str_replace(" ", "", $results[$i]["material"]);
                    $material = trim($material);

                    
                    if($consecutivoNODO != "0")
                    {
                        $cod_mat = DB::table($this->tblAux1 .'inv_maestro_articulos')
                                ->where('codigo_sap',$material)
                                ->select('id_articulo')
                                ->get();

                        if(count($cod_mat) ==  0) 
                        {
                            $materialesNOExisten .= "El material $material no existe en la BD. <br>";
                            continue;
                        }

                        $cod_mat = $cod_mat[0]->id_articulo;

                        $mat = DB::Table($this->tblAux . 'detalle_materiales' . $this->valorT)
                                ->where('id_ws',$consecutivoWB)
                                ->where('id_proyecto',$proyecto)
                                ->where('id_nodo',$consecutivoNODO)
                                ->where('id_articulo',$cod_mat)
                                ->select('cantidad_replanteo')
                                ->get();

                        if(intval($results[$i]["cant"]) <= 0)
                            continue;


                        if(count($mat) == 0)
                        { //Insert
                            DB::Table($this->tblAux . 'detalle_materiales' . $this->valorT)
                            ->insert(array(
                                array(
                                    'id_proyecto' => $proyecto,
                                    'id_ws' => $consecutivoWB,
                                    'id_nodo' => $consecutivoNODO,
                                    'id_articulo' => $cod_mat,
                                    'cantidad_replanteo' => $results[$i]["cant"]
                                    )));
                        }
                        else
                        {
                            if($repiteMaterial)
                            {
                                $auxCont = 0;
                                $auxCont = floatval($mat[0]->cantidad_replanteo) + floatval($results[$i]["cant"]);
                                DB::Table($this->tblAux . 'detalle_materiales' . $this->valorT)
                                ->where('id_ws',$consecutivoWB)
                                ->where('id_proyecto',$proyecto)
                                ->where('id_nodo',$consecutivoNODO)
                                ->where('id_articulo',$cod_mat)
                                ->update(array(
                                        'cantidad_replanteo' =>  $auxCont
                                        ));

                                self::saveLog("OPERA08",$proyecto,"MATERIAL: $material CANTIDAD: $auxCont  NODO: $consecutivoNODO WBS: $consecutivoWB EXCEL");    
                            }

                            /*if($archivo = fopen($fileL, "a"))
                            {
                                fwrite($archivo, date("d m Y H:m:s"). " El Material $material ya se encuentra registrado.\n");
                                fclose($archivo);
                            }
                            Session::flash('Archivo',"logsCargaExcel_" . $this->fechaShort);*/
                        }
                    }
            }
            if($materialesNOExisten != "")
                Session::flash('dataExcel2',$materialesNOExisten);                  

            var_dump("Se esta cargando el documento en excel, espero un momento");
            
            $arrWBS = Array();
            $arrNodos = Array();
            $arrNodos2 = Array();

            $consecutivoNODO = 0;
            $consecutivoMATE = 0;
            }
            //var_dump($results);
        })->get();
        
        self::saveLog("OPERA04",$pry,"");
        Session::flash('dataExcel1',"Se ha cargado correctamente el Excel de los Materiales.");

        //var_dump("todo BIEN");
       return Redirect::to('/redes/ordenes/ver/' . $pry);
    }

    public function uploadExcelGOMS(Request $request)
    {
        $fileL= storage_path(). "\logsCargaGoms_" . $this->fechaShort . ".txt";
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
            return Redirect::to('/redes/ordenes/home');
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
            $proyecto = $request->all()["txt_proy"];

            if(isset($results[0]["gom"])  == false) 
            {
                Session::flash('dataExcel2',"El archivo que esta tratando de cargar para las GOMS no es válido");                  
            }
            else
            {
                $datosIncorrectos = "";

                //Session::flash('dataExcel2',$materialesNOExisten);
                for ($i=0; $i < count($results); $i++) 
                {
                    if($results[$i]["gom"] == "" || $results[$i]["gom"] == NULL || $results[$i]["gom"] == null)
                        continue;

                    $gom = str_replace(".","",trim($results[$i]["gom"]));
                    $ws = str_replace(".","",trim($results[$i]["wbs"]));
                    $ws = str_replace("WBS","",$ws);
                    $ws = str_replace(" ","",$ws);
                    $gom = trim($gom);

                    if(strlen($gom) != 7)
                    {
                        $datosIncorrectos .= "La GOM " . $gom . " tiene que tener una longitud de 7 caracteres \n --- ";
                        continue;
                    }

                    $id_ws = DB::table($this->tblAux  . 'ws')
                                ->where('id_proyecto',$proyecto)
                                ->where('nombre_ws',trim($ws))
                                ->value('id_ws');


                    if($id_ws == "" || $id_ws == NULL)
                    {
                        $datosIncorrectos .= "La WBS " . $ws . " no existe en el proyecto " . $proyecto . "\n --- ";
                        continue;
                    }
                    
                    
                    $idPro = DB::table($this->tblAux  . 'ws_gom')
                            ->where('id_gom',$gom)
                            ->value('id_proyecto');

                    if($idPro != "" && $idPro != NULL)
                    {
                        $datosIncorrectos .= "La GOM " . $gom . " pertenece a otro proyecto " . $idPro . "\n --- ";
                        continue;
                    }

                        DB::table($this->tblAux  . 'ws_gom')
                            ->insert(array(
                                array(
                                    'id_gom' => $gom,
                                    'id_proyecto' => $proyecto,
                                    'id_ws' => $id_ws,
                                )
                            ));


                        self::saveLog("OPERA11",$proyecto,"GOM: $gom  WBS:$ws");
                }
            }
            
            if($datosIncorrectos != "")
                Session::flash('dataExcel2',$datosIncorrectos);

                var_dump("Se esta cargando el documento en excel, espero un momento");
            
            
            //var_dump($results);
        })->get();

        Session::flash('dataExcel1',"Se ha cargado correctamente el Excel de las GOMS.");

        //var_dump("todo BIEN");
       return Redirect::to('/redes/ordenes/home');
    }

    public function uploadExcelEstructura(Request $request)
    {
        $fileL= storage_path(). "\logsCargaEstructuras_" . $this->fechaShort . ".txt";
        if(file_exists($fileL))
            unlink($fileL);

        //obtenemos el campo file definido en el formulario
       $file = $request->file('file_upload_estruc_1');
       $file1 = $request->file('file_upload_estruc_2');
       $file2 = $request->file('file_upload_estruc_3');
       $file3 = $request->file('file_upload_estruc_4');
       
       //Varificamos que carge un .xlsx
       $mime = $file->getMimeType();
       $mime1 = $file1->getMimeType();
       $mime2 = $file2->getMimeType();
       $mime3 = $file3->getMimeType();
       if($mime != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ||
        $mime1 != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ||
        $mime2 != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ||
        $mime3 != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
       {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"Tipo de archivo invalido, tiene que carga un archivo .xlsx");
            return Redirect::to('/redes/ordenes/home');
       }

       //obtenemos el nombre del archivo
       $nombre = $file->getClientOriginalName();
       $nombre1 = $file1->getClientOriginalName();
       $nombre2 = $file2->getClientOriginalName();
       $nombre3 = $file3->getClientOriginalName();

       //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre,  \File::get($file));

       $ruta = "storage/app" . "/" . $nombre;
     
       //var_dump($ruta);

       set_time_limit(0);

       var_dump("Se esta cargando el documento en excel, espero un momento");

       //Primer Archivo Carga de Estructuras
       $result = \Excel::load($ruta, function($reader) use ($request,$fileL) {

            $results = $reader->toArray();

            //Ingreso Estructuras
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["estructura_cod"] == "" || $results[$i]["estructura_cod"] == NULL || $results[$i]["estructura_cod"] == null)
                    continue;

                $id_estructura = $results[$i]["estructura_cod"];
                $estructura = $results[$i]["estructura"];
                



                $consultestruc = DB::table($this->tblAux  . 'estructuras')
                            ->where('id',$id_estructura)
                            ->select('id')
                            ->get();

                if(count($consultestruc) == 0)
                {
                    DB::table($this->tblAux  . 'estructuras')
                            ->insert(array(
                                array(
                                    'id' => $id_estructura,
                                    'des_estruc'  => $estructura
                                   )
                                ));
                }
                else
                {
                    DB::table($this->tblAux  . 'estructuras')
                            ->where('id',$id_estructura)
                            ->update(
                                array(
                                    'des_estruc'  => $estructura
                                ));
                }
            }

            //Ingreso Normas
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["norma"] == "" || $results[$i]["norma"] == NULL || $results[$i]["norma"] == null)
                    continue;

                $id_estructura = $results[$i]["estructura_conbinado"];
                $norma = $results[$i]["norma"];
                $des = $results[$i]["des"];
                
                $idE = DB::table($this->tblAux  . 'estructuras')
                            ->where('des_estruc',$id_estructura)
                            ->select('id')
                            ->get()[0]->id;


                $consultnorma = DB::table($this->tblAux  . 'estructura_norma')
                            ->where('id',$idE)
                            ->where('norma',$norma)
                            ->select('des_norma')
                            ->get();

                if(count($consultnorma) == 0)
                {
                    DB::table($this->tblAux  . 'estructura_norma')
                            ->insert(array(
                                array(
                                    'id' => $idE,
                                    'norma'  => $norma,
                                    'des_norma'  => $des
                                   )
                                ));
                }
                else
                {
                    DB::table($this->tblAux  . 'estructura_norma')
                            ->where('id',$id_estructura)
                            ->where('norma',$norma)
                            ->update(
                                array(
                                    'des_norma'  => $des
                                ));
                }
            }

        })->get();
        
        //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre1,  \File::get($file1));

       $ruta1 = "storage/app" . "/" . $nombre1;


        //Segundo Archivo Carga de Familias
       $result1 = \Excel::load($ruta1, function($reader) use ($request,$fileL) {

            $results = $reader->toArray();

            //Ingreso Familias
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["cod_familia"] == "" || $results[$i]["cod_familia"] == NULL || $results[$i]["cod_familia"] == null)
                    continue;

                $id_familia = $results[$i]["cod_familia"];
                $desFa = $results[$i]["familia"];
                
                $consultaF = DB::table($this->tblAux  . 'familias_materiales')
                            ->where('id',$id_familia)
                            ->select('id')
                            ->get();

                if(count($consultaF) == 0)
                {
                    DB::table($this->tblAux  . 'familias_materiales')
                            ->insert(array(
                                array(
                                    'id' => $id_familia,
                                    'des_familia'  => $desFa
                                   )
                                ));
                }
                else
                {
                    DB::table($this->tblAux  . 'familias_materiales')
                            ->where('id',$id_familia)
                            ->update(
                                array(
                                    'des_familia'  => $desFa
                                ));
                }
            }

            //Actualiza Materiales a Familias y a et
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["familia_material"] == "" || $results[$i]["familia_material"] == NULL || $results[$i]["familia_material"] == null)
                    continue;

                $id_fami = $results[$i]["familia_material"];
                $cod_material = $results[$i]["cod_material"];
                $et = $results[$i]["et"];
                
                $idE = DB::table($this->tblAux  . 'familias_materiales')
                            ->where('des_familia',$id_fami)
                            ->select('id')
                            ->get()[0]->id;


                $consultnorma = DB::table($this->tblAux1  . 'inv_maestro_articulos')
                            ->where('codigo_sap',$cod_material)
                            ->select('codigo_sap')
                            ->get();

                if(count($consultnorma) == 0) //Tiene que existir
                {
                    
                }
                else
                {
                    DB::table($this->tblAux1  . 'inv_maestro_articulos')
                            ->where('codigo_sap',$cod_material)
                            ->update(
                                array(
                                    'id_familia'  => $idE,
                                    'especificacion_tec'  => $et
                                ));
                }
            }
        })->get();
      

         //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre2,  \File::get($file2));

       $ruta2 = "storage/app" . "/" . $nombre2;

        //Tercer Archivo Carga de Familias
       $result3 = \Excel::load($ruta2, function($reader) use ($request,$fileL) {

            $results = $reader->toArray();

            //Ingreso Actividades
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["cod_actividad"] == "" || $results[$i]["cod_actividad"] == NULL || $results[$i]["cod_actividad"] == null)
                    continue;

                $id_acti = $results[$i]["cod_actividad"];
                $desActi = $results[$i]["actividad"];
                
                $consultaA = DB::table($this->tblAux  . 'tipo_actividad')
                            ->where('id',$id_acti)
                            ->select('id')
                            ->get();

                if(count($consultaA) == 0)
                {
                    DB::table($this->tblAux  . 'tipo_actividad')
                            ->insert(array(
                                array(
                                    'id' => $id_acti,
                                    'des_tipo_actividad'  => $desActi
                                   )
                                ));
                }
                else
                {
                    DB::table($this->tblAux  . 'tipo_actividad')
                            ->where('id',$id_acti)
                            ->update(
                                array(
                                    'des_tipo_actividad'  => $desActi
                                ));
                }
            }


            //Ingreso Elementos
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["cod_elemento"] == "" || $results[$i]["cod_elemento"] == NULL || $results[$i]["cod_elemento"] == null)
                    continue;

                $id_acti = $results[$i]["cod_elemento"];
                $desActi = $results[$i]["elemento"];
                
                $consultaA = DB::table($this->tblAux  . 'elemento_actividad')
                            ->where('id',$id_acti)
                            ->select('id')
                            ->get();

                if(count($consultaA) == 0)
                {
                    DB::table($this->tblAux  . 'elemento_actividad')
                            ->insert(array(
                                array(
                                    'id' => $id_acti,
                                    'des_elemento_actividad'  => $desActi
                                   )
                                ));
                }
                else
                {
                    DB::table($this->tblAux  . 'elemento_actividad')
                            ->where('id',$id_acti)
                            ->update(
                                array(
                                    'des_elemento_actividad'  => $desActi
                                ));
                }
            }

            //Actualiza Materiales a Familias y a et
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["actividad_mo"] == "" || $results[$i]["actividad_mo"] == NULL || $results[$i]["actividad_mo"] == null)
                    continue;

                $id_acti = $results[$i]["actividad_mo"];
                $id_elem = $results[$i]["elemento_mat"];
                $baremo = $results[$i]["baremo_cod"];
                
                $idA = DB::table($this->tblAux  . 'tipo_actividad')
                            ->where('des_tipo_actividad',$id_acti)
                            ->select('id')
                            ->get()[0]->id;

                $idE= DB::table($this->tblAux  . 'elemento_actividad')
                            ->where('des_elemento_actividad',$id_elem)
                            ->select('id')
                            ->get()[0]->id;

                $consulta = DB::table($this->tblAux  . 'baremos')
                            ->where('codigo',$baremo)
                            ->select('codigo')
                            ->get();

                if(count($consulta) == 0) //Tiene que existir
                {
                    
                }
                else
                {
                    DB::table($this->tblAux  . 'baremos')
                            ->where('codigo',$baremo)
                            ->update(
                                array(
                                    'id_tipo_actividad'  => $idA,
                                    'id_elemento'  => $idE
                                ));
                }
            }
        })->get();
      
        
           //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre3,  \File::get($file3));

       $ruta3 = "storage/app" . "/" . $nombre3;

        //Cuarto Archivo Carga relación MO Materiales
       $result4 = \Excel::load($ruta3, function($reader) use ($request,$fileL) {

            $results = $reader->toArray();

            //Ingreso Relación Materiales
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["mat_des"] == "" || $results[$i]["mat_des"] == NULL || $results[$i]["mat_des"] == null)
                    continue;

                $id_estruc = $results[$i]["estructura"];
                $norma = $results[$i]["cod_estructura"];
                $especi = $results[$i]["especificacion"];
                $cod_sap = $results[$i]["cod_sap"];
                $cant_mat = $results[$i]["cant_mat"];
                
                $idE = DB::table($this->tblAux  . 'estructuras')
                            ->where('des_estruc',$id_estruc)
                            ->select('id')
                            ->get();

                if(count($idE) > 0)
                    $idE = $idE[0]->id;
                else
                    continue;

                $consultaF = DB::table($this->tblAux  . 'relacion_estructura_material')
                            ->where('id_estructura',$idE)
                            ->where('norma',$norma)
                            ->where('codigo_sap',$cod_sap)
                            ->select('id_estructura')
                            ->get();

                if(count($consultaF) == 0)
                {
                    DB::table($this->tblAux  . 'relacion_estructura_material')
                            ->insert(array(
                                array(
                                    'id_estructura' => $idE,
                                    'norma'  => $norma,
                                    'especificacion'  => $especi,
                                    'codigo_sap'  => $cod_sap,
                                    'cant_material'  => $cant_mat
                                   )
                                ));
                }
                else
                {
                    DB::table($this->tblAux  . 'relacion_estructura_material')
                            ->where('id_estructura',$idE)
                            ->where('norma',$norma)
                            ->where('codigo_sap',$cod_sap)
                            ->update(
                                array(
                                    'cant_material'  => $cant_mat,
                                    'especificacion'  => $especi
                                ));
                }
            }

            //Ingreso Relación Mano Obra
            for ($i=0; $i < count($results); $i++) 
            {
                
                if($results[$i]["mo_des"] == "" || $results[$i]["mo_des"] == NULL || $results[$i]["mo_des"] == null)
                    continue;

                $id_estruc = $results[$i]["estructura"];
                $norma = $results[$i]["cod_estructura"];
                $especi = $results[$i]["especificacion"];
                $cod_sap = $results[$i]["baremo"];
                $cant_mat = $results[$i]["cant_mo"];
                
                $idE = DB::table($this->tblAux  . 'estructuras')
                            ->where('des_estruc',$id_estruc)
                            ->select('id')
                            ->get();

                if(count($idE) > 0)
                    $idE = $idE[0]->id;
                else
                    continue;

                $consultaF = DB::table($this->tblAux  . 'relacion_estructura_mobra')
                            ->where('id_estructura',$idE)
                            ->where('norma',$norma)
                            ->where('baremo',$cod_sap)
                            ->select('id_estructura')
                            ->get();

                if(count($consultaF) == 0)
                {
                    DB::table($this->tblAux  . 'relacion_estructura_mobra')
                            ->insert(array(
                                array(
                                    'id_estructura' => $idE,
                                    'norma'  => $norma,
                                    'especificacion'  => $especi,
                                    'baremo'  => $cod_sap,
                                    'cant_baremo'  => $cant_mat
                                   )
                                ));
                }
                else
                {
                    DB::table($this->tblAux  . 'relacion_estructura_mobra')
                            ->where('id_estructura',$idE)
                            ->where('norma',$norma)
                            ->where('baremo',$cod_sap)
                            ->update(
                                array(
                                    'cant_baremo'  => $cant_mat,
                                    'especificacion'  => $especi
                                ));
                }
            }
        })->get();

        Session::flash('dataExcel1',"Se han cargado correctamente las estructuras.");

        //var_dump("todo BIEN");
       return Redirect::to('/redes/ordenes/ver');
    }   

    //Download Preplanillas
    public function downloadPreplanillas(Request $request)
    {
       $fecha1 = $request->all()['fecha1_goms'];
       $fecha2 = $request->all()['fecha2_goms']; 

       $ordenes = DB::table($this->tblAux . 'ordenes as tbl2')
                ->whereBetween('fecha_ejecucion',[$fecha1 . " 00:00:00",$fecha2 . " 23:59:00"])
                ->whereIn('tbl2.id_estado',['E4','C2'])
                ->where('tbl2.id_tipo','T56')
                ->join($this->tblAux . 'ordenes_manoobra_detalle as tbl1','tbl1.id_orden','=','tbl2.id_orden')
                ->join('rh_personas','identificacion','=','tbl1.id_lider')
                ->select('tbl1.id_orden','gom','fecha_ejecucion','tbl1.id_lider','nombres','apellidos')
                ->orderBy('fecha_ejecucion')
                ->get();

       \Excel::create('Consolidado seguimiento preplanillas', function($excel) use($ordenes) {            
            $excel->sheet('Seguimiento', function($sheet) use($ordenes){
                $products = ["GOM","FECHA DE EJECUCIÓN","NOMBRE DEL CUADRILLERO","NÚMERO DE PREPLANILLA"];
                $sheet->fromArray($products);

                for ($i=0; $i < count($ordenes); $i++) {    
                            $sheet->row($i +2, array(
                            $ordenes[$i]->gom, explode(" ",$ordenes[$i]->fecha_ejecucion)[0],$ordenes[$i]->nombres . " " . $ordenes[$i]->apellidos . " - " .  $ordenes[$i]->id_lider,str_replace("OT0","",str_replace("OT00","",str_replace("OT000", "", $ordenes[$i]->id_orden)))
                            ));                        
                }
                });
        })->export('xls');
    }

    //Download Formato Excel
    public function downloadFormato(Request $request)
    {

        if(isset($request->all()["opc"]))
        {
            if($request->all()["opc"] == "1")
            {
                \Excel::create('Plantilla carga kilometrajes', function($excel) {            
                    $excel->sheet('Plantilla', function($sheet) {
                        $products = ["PLACA","FECHA","KILOMETRAJE","OBSERVACION"];
                        $sheet->fromArray($products);
                        });
                })->export('xlsx');   
                return; 
            }

            if($request->all()["opc"] == "2")
            {
                \Excel::create('Libro Mano de Obra', function($excel) {            
                    $excel->sheet('Plantilla', function($sheet) {
                        $products = ["WBS","NODO","ACTIVIDAD","CANT","NT","PF","CD","SEC","DIRECCION"];
                        $sheet->fromArray($products);
                        });
                })->export('xlsx');   
                return; 
            }

            if($request->all()["opc"] == "3")
            {
                \Excel::create('Libro Materiales', function($excel) {            
                    $excel->sheet('Plantilla', function($sheet) {
                        $products = ["WBS","NODO","MATERIAL","CANT"];
                        $sheet->fromArray($products);
                        });
                })->export('xlsx');   
                return; 
            }

            if($request->all()["opc"] == "5")
            {
                    \Excel::create('Plantilla carga descargos', function($excel) {            
                    $excel->sheet('Plantilla', function($sheet) {
                        $products = ["DESCARGO","ESTADO","MANIOBRA"];
                        $sheet->fromArray($products);
                        });
                })->export('xlsx');   
                return;    
            }

            if($request->all()["opc"] == "4")
            {
                \Excel::create('Plantilla carga Goms', function($excel) {            
                    $excel->sheet('Plantilla', function($sheet) {
                        $products = ["GOM","WBS"];
                        $sheet->fromArray($products);
                        });
                })->export('xlsx');   
                return; 
            }

            

            
        }
        
    }

    //Carga Descargos
    public function cargaDescargosOT(Request $request)
    {
        

        //obtenemos el campo file definido en el formulario
       $file = $request->file('archivo_excel_descargos');

       if($file == null)
        {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"No ha seleccionado níngun archivo");
            return Redirect::to('/redes/ordenes/home');
        }

       //Varificamos que carge un .xlsx
       $mime = $file->getMimeType();
       if($mime != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
       {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"Tipo de archivo invalido, tiene que carga un archivo .xlsx");
            return Redirect::to('/redes/ordenes/home');
       }

       //obtenemos el nombre del archivo
       $nombre = $file->getClientOriginalName();

       //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre,  \File::get($file));

       $ruta = "storage/app" . "/" . $nombre;

       set_time_limit(0);

       $result = \Excel::load($ruta, function($reader) use ($request) {
            
            $results = $reader->toArray();

            $maniObraA = "";
            $descargosA = "";
            $estadosA = "";

            if(isset($results[0]["descargo"]) == false ||
                    isset($results[0]["estado"]) == false ||
                    isset($results[0]["maniobra"]) == false )
            {
                Session::flash('dataExcel2',"El archivo que esta tratando de cargar no tiene el formato específico.");                  
            }
            else
            {
                
                for ($i=0; $i < count($results); $i++) {

                    if($results[$i]["descargo"] == "" || $results[$i]["descargo"] == NULL ||
                        $results[$i]["estado"] == "" || $results[$i]["estado"] == NULL  ||
                        $results[$i]["maniobra"] == "" || $results[$i]["maniobra"] == NULL
                        )
                        continue;

                    $descargo = $results[$i]["descargo"];
                    $estado = $results[$i]["estado"];
                    $maniObra = $results[$i]["maniobra"];

                    $contP = DB::table($this->tblAux . 'ordenes')
                                ->where('id_orden',$maniObra)
                                ->count();

                   
                    if($contP == 0)
                    {
                        $maniObraA .= "\n- La ManiObra $maniObra no existe";
                        continue;
                    }

                    $estadoS = "";
                    if($estado == "GENERADO")
                        $estadoS = "1";

                    if($estado == "SOLICITADO")
                        $estadoS = "2";

                    if($estado == "VALIDADO")
                        $estadoS = "3";

                    if($estado == "APROBADO")
                        $estadoS = "4";

                    if($estado == "CONFIRMADO")
                        $estadoS = "5";

                    if($estado == "DENEGADO")
                        $estadoS = "6";

                    if($estado == "INVALIDADO")
                        $estadoS = "7";

                    if($estado == "EJECUTADO")
                        $estadoS = "8";

                    if($estado == "ANULADO")
                        $estadoS = "9";

                    if($estado == "EN DESCARGO")
                        $estadoS = "10";

                    if($estadoS == "")
                    {
                        $estadosA .= "\n - El estado $estado no tiene el formato establecido";
                        continue;
                    }

                    $contDes = DB::table($this->tblAux . 'descargo_proyecto')
                                ->where('id_descargo',$descargo)
                                ->count();

                    if($contDes == 0) //Inserta
                    {
                        $proyectoSe = DB::table($this->tblAux . 'ordenes')
                                ->where('id_orden',$maniObra)
                                ->value("id_proyecto");

                        DB::table($this->tblAux . 'descargo_proyecto')
                                ->insert(array(
                                    array(
                                        'id_proyecto' => $proyectoSe,
                                        'id_estado' => $estadoS,
                                        'id_descargo' => $descargo
                                        )));

                        DB::table($this->tblAux . 'ordenes')
                            ->where('id_orden',$maniObra)
                            ->update(array(
                                    'descargo' => $descargo
                                    ));
                    }   
                    else
                    {
                        DB::table($this->tblAux . 'descargo_proyecto')
                                ->where('id_descargo',$descargo)
                                ->update(array(
                                        'id_estado' => $estadoS
                                        ));
                    }
                    
                }
                //var_dump("Se esta cargando el documento en excel, espero un momento");
            }

            if($maniObraA != "")
                $maniObraA  = " Se encontraron las siguiente ManiObras que no existen:\n" . $maniObraA;

            if($estadosA != "")
                $estadosA  = "\n Se encontraron las siguiente estados que no existen:\n" . $estadosA;

            if($maniObraA != "" || $estadosA != "")
                Session::flash('dataExcel2',$maniObraA . "\n" . $estadosA);     
            //var_dump($results);
        })->get();
        
        Session::flash('dataExcel1',"Se ha cargado correctamente el Excel de los descargos");

        //var_dump("todo BIEN");
      return Redirect::to('/redes/ordenes/home');
    }


    /********************************************/
    /*               Web Services               */
    /********************************************/

    public function saveProject(Request $request)
    {
        $opc = $request->all()['opc'];
        if($opc == "1")
        {
            Session::forget('rds_gop_proyecto_id');
            return response()->json("1");
        }

        if($opc == "2")
        {
            $proyNu = DB::table($this->tblAux . 'ordenes' . $this->valorT)
                ->where('id_orden',$request->all()['ot'])
                ->select('id_proyecto')
                ->get()[0]->id_proyecto;

            Session::put('rds_gop_proyecto_id',$proyNu);
            Session::put('rds_gop_proyecto_orden_id',$request->all()['ot']);
            return response()->json("1");
        }

        if($opc == "3")
        {
            Session::forget('rds_gop_proyecto_orden_id');
            return response()->json("1");
        }



        $proy = $request->all()['proy'];
        $nom = $request->all()['nom'];
        $fech = self::cambiaf_a_mysql($request->all()['fech'],2);
        $cargo = $request->all()['cargo'];
        $val = $request->all()['val'];
        
        $lineaV = explode(";",$request->all()['linea'])[0];
        $lineaM = explode(";",$request->all()['linea'])[1];

        $diag = $request->all()['diagr'];;
        $zona = $request->all()['zona'];
        $circ = $request->all()['circ'];
        $alc = $request->all()['alc'];
        $barr = $request->all()['barr'];
        $dire = $request->all()['dire'];
        $bode = $request->all()['bode'];
        $obs = $request->all()['obs'];
        $tipoP = $request->all()['tipoP'];

        $tipo_trabajo = $request->all()['tipo_trabajo'];
        $tipo_proceso = $request->all()['tipo_proceso'];

        $contP = DB::table($this->tblAux . 'proyectos' . $this->valorT)
                ->where('id_proyecto',$proy)
                ->select('id_proyecto')
                ->get();

        //Session::forget($this->tblAux . 'proyecto_id');

        DB::beginTransaction();

        try
        {
            $res = "";
            if(count($contP) == 0)
            {
                $proy = self::generaConsecutivo("ID_PROY");
                DB::table($this->tblAux . 'proyectos' . $this->valorT)
                ->insert(array(
                    array(
                        'id_proyecto' =>  strToUpper($proy),
                        'fecha_creacion' =>  $fech,
                        'nombre' =>  strToUpper($nom),
                        'observaciones' =>  strToUpper($obs),
                        'info_contacto' =>  strToUpper($cargo),
                        'tipo_trabajo' => $tipo_trabajo,
                        'tipo_proceso' => $tipo_proceso,
                        'valor_inicial' =>  $val,
                        'id_estado' =>  'P1',
                        'lm' => $lineaM,
                        'lv' => $lineaV,
                        'cod_cto' =>  strToUpper($circ),
                        'zona' =>  strToUpper($zona),
                        'municipio' =>  strToUpper($alc),
                        'diagrama' =>  $diag,
                        'barrio' =>  strToUpper($barr),
                        'direccion' =>  strToUpper($dire),
                        'id_bodega' =>  strToUpper($bode),
                        'fecha' => $this->fechaALong,
                        'tipo_proyecto' => $tipoP
                        )));
                
                self::saveLog("OPERA01",$proy,"");

                //Crear WBS Inicial
                $consecutivo = self::generaConsecutivo("ID_WS");
                DB::table($this->tblAux . 'ws'  . $this->valorT)
                ->insert(array(
                    array(
                        'id_ws' => $consecutivo,
                        'id_proyecto' => $proy,
                        'nombre_ws' => "1"
                        )));

                Session::flash('mensaje',"Se ha creado correctamente el proyecto" . $proy . ". <br> Ahora puede cargar la información correspondiente al proyecto");
                Session::put('rds_gop_proyecto_id',$proy);
                $res = $proy;
            }
            else
            {
                DB::table($this->tblAux . 'proyectos' . $this->valorT)
                        ->where('id_proyecto',$proy)
                        ->update(
                            array(
                                'fecha_creacion' =>  $fech,
                                'nombre' =>  strToUpper($nom),
                                'observaciones' =>  strToUpper($obs),
                                'info_contacto' =>  strToUpper($cargo),
                                'valor_inicial' =>  $val,
                                'id_estado' =>  'P1',
                                'tipo_trabajo' => $tipo_trabajo,
                                'tipo_proceso' => $tipo_proceso,
                                'lm' => $lineaM,
                                'lv' => $lineaV,
                                'cod_cto' =>  strToUpper($circ),
                                'zona' =>  strToUpper($zona),
                                'municipio' =>  strToUpper($alc),
                                'diagrama' =>  $diag,
                                'barrio' =>  strToUpper($barr),
                                'direccion' =>  strToUpper($dire),
                                'id_bodega' =>  strToUpper($bode),
                                'tipo_proyecto' => $tipoP
                                ));
                
                self::saveLog("OPERA02",$proy,"");

               $res = $proy;
            }
            
            DB::commit();
            return response()->json($res);

        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json("0");
        }
    }

    public function saveWBSProgramados(Request $request)
    {
        $opc = $request->all()['opc'];
        
        DB::beginTransaction();
        $wbs = $request->all()['wbs'];
        $proyecto = $request->all()['pro'];

        try
        {
            

            if($opc == "2") //Elimina
            {
                $contWBS = DB::table($this->tblAux . 'ws'  . $this->valorT)
                    ->where('id_ws',$wbs)
                    ->where('id_proyecto',$proyecto)
                    ->select('id_proyecto')
                    ->get();
                //Validar si solo existe un WBS
                if(count($contWBS) == 1)
                    return response()->json("-2"); //Solo existe un registro

                DB::table($this->tblAux . 'ws'  . $this->valorT)
                    ->where('id_ws',$wbs)
                    ->where('id_proyecto',$proyecto)
                    ->delete();

                self::saveLog("OPERA40",$proyecto,"WBS: $wbs");

                DB::commit();
                
                $wbsConsul = DB::table($this->tblAux . 'ws'  . $this->valorT)
                            ->where('id_proyecto',$proyecto)
                            ->select('id_ws','nombre_ws')
                            ->get();

                $wbs = DB::table($this->tblAux . 'ws' . $this->valorT)
                ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                ->select('id_ws', 'nombre_ws', 'observaciones')
                ->orderBy('nombre_ws')
                ->get();

                return response()->json(view('proyectos.redes.trabajoprogramado.secciones.wbs',
                array(
                "wbs" => $wbs
                ))->render());
            }

            
            $nombre = strtoupper($request->all()['nombre']);
            $obser = strtoupper($request->all()['obser']);

            

            

            if($opc == "0") //Crear
            {
                $wbsC = DB::table($this->tblAux . 'ws'  . $this->valorT)
                        ->where('nombre_ws',$nombre)
                        ->where('id_proyecto',$proyecto)
                        ->select('nombre_ws')
                        ->get();

                if(count($wbsC) > 0)
                    return response()->json("-1"); //Ya existe otro nodo con ese dato   

                //Solicitar WBS Concecutivo
                $consecutivo = self::generaConsecutivo("ID_WS");
                DB::table($this->tblAux . 'ws'  . $this->valorT)
                ->insert(array(
                    array(
                        'id_ws' => $consecutivo,
                        'id_proyecto' => $proyecto,
                        'nombre_ws' => $nombre,
                        'observaciones' => $obser,
                        'gom' => 0
                        )));

                self::saveLog("OPERA05",$proyecto,"WBS: $consecutivo - $nombre");

                DB::commit();

                $wbs = DB::table($this->tblAux . 'ws' . $this->valorT)
                ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                ->select('id_ws', 'nombre_ws', 'observaciones')
                ->orderBy('nombre_ws')
                ->get();

                return response()->json(view('proyectos.redes.trabajoprogramado.secciones.wbs',
                array(
                "wbs" => $wbs
                ))->render());
            }

            if($opc == "1") //Actualiza
            {       
                $nombreOri = strtoupper($request->all()['ori']);
                if($nombreOri != $nombre)
                {
                    

                    $wbsC = DB::table($this->tblAux . 'ws'  . $this->valorT)
                            ->where('nombre_ws',$nombre)
                            ->where('id_proyecto',$proyecto)
                            ->select('nombre_ws')
                            ->get();

                    if(count($wbsC) > 0)
                        return response()->json("-1"); //Ya existe otro nodo con ese dato 
                }

                DB::table($this->tblAux . 'ws'  . $this->valorT)
                    ->where('id_ws',$wbs)
                    ->where('id_proyecto',$proyecto)
                    ->update(array(
                        'nombre_ws' => $nombre,
                        'observaciones' => $obser
                        ));

                self::saveLog("OPERA39",$proyecto,"WBS: $nombre");

                DB::commit();

                $wbsConsul = DB::table($this->tblAux . 'ws'  . $this->valorT)
                            ->where('id_proyecto',$proyecto)
                            ->select('id_ws','nombre_ws')
                            ->get();

                $wbs = DB::table($this->tblAux . 'ws' . $this->valorT)
                ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                ->select('id_ws', 'nombre_ws', 'observaciones')
                ->orderBy('nombre_ws')
                ->get();

                return response()->json(view('proyectos.redes.trabajoprogramado.secciones.wbs',
                array(
                "wbs" => $wbs
                ))->render());  
            }

        }
        catch(Exception $e)
        {
            DB::rollBack();
            return response()->json("0");
        }
    }

    public function saveNODOSProgramados(Request $request)
    {
        $opc = $request->all()['opc'];
        

        $baremo = $request->all()['nodoA'];
        $pro = $request->all()['pro'];
        $wbs = $request->all()['wbs']; 

        if($opc == 2)
        { 
            DB::table($this->tblAux . 'nodos' . $this->valorT)
                ->where('id_proyecto',$pro)
                ->where('id_ws',$wbs)
                ->where('id_nodo',$baremo)
                ->delete();

            DB::table($this->tblAux . 'ws_nodos' . $this->valorT)
                ->where('id_proyecto',$pro)
                ->where('id_ws',$wbs)
                ->where('id_nodo1',$baremo)
                ->delete();

            self::saveLog("OPERA42",$pro,"NODO: $baremo WBS:$wbs");

            $nodos = DB::table($this->tblAux . 'nodos'. $this->valorT)
                ->join($this->tblAux . 'ws_nodos' . $this->valorT,$this->tblAux . 'ws_nodos' . $this->valorT . '.id_nodo1','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                ->join($this->tblAux . 'ws' . $this->valorT,$this->tblAux . 'ws' . $this->valorT . '.id_ws','=',$this->tblAux . 'ws_nodos' . $this->valorT . '.id_ws')
                ->where($this->tblAux . 'nodos' . $this->valorT . '.id_proyecto',$pro)
                ->select($this->tblAux . 'ws' . $this->valorT . '.id_ws as ws',$this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.id_estado',
                    $this->tblAux . 'nodos' . $this->valorT . '.direccion',$this->tblAux . 'nodos' . $this->valorT . '.nivel_tension',$this->tblAux . 'nodos' . $this->valorT . '.punto_fisico'
                    ,$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo as idN',
                    $this->tblAux . 'nodos' . $this->valorT . '.observaciones')
                ->orderBy($this->tblAux . 'ws' . $this->valorT . '.nombre_ws','asc')
                ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.nodos',
                array(
                "opcTbl" => "1",
                "nodos" => $nodos
                ))->render());  

            //return response()->json("1"); //Eliminado correctamente
        }

        $nodoNombre = $request->all()['nodoNombre'];
        $cd = $request->all()['cd'];
        $dire = $request->all()['dire'];
        $obser = $request->all()['obser'];
        $nt = $request->all()['nt'];
        $pf = $request->all()['pf'];
        $sec = $request->all()['sec'];
         

        $nodo = DB::Table($this->tblAux . 'nodos' . $this->valorT)
                    ->where('id_proyecto',$pro)
                    ->where('nombre_nodo',$nodoNombre)
                    ->get();

       // return response()->json("ALERT");
        if($opc == 0) //Create
        {
            if(count($nodo) == 1)
                return response()->json("-1"); //Ya existe un dato

            $consecutivoNodo = self::generaConsecutivo("ID_NODO");
            DB::Table($this->tblAux . 'nodos' . $this->valorT)
            ->insert(array(
                array(
                    'id_proyecto' => $pro,
                    'id_ws' => $wbs,
                    'id_nodo' => $consecutivoNodo,
                    'nombre_nodo' => $nodoNombre,
                    'cd' => $cd,
                    'id_estado' => 'G',
                    'direccion' => $dire,
                    'observaciones' => $obser,
                    'nivel_tension' => $nt,
                    'punto_fisico' => $pf,
                    'seccionador' => $sec,
                    )));

            DB::Table($this->tblAux . 'ws_nodos' . $this->valorT)
                ->insert(array(
                    array(
                        'id_proyecto' => $pro,
                        'id_ws' => $wbs,
                        'id_nodo1' => $consecutivoNodo
                        )));

            self::saveLog("OPERA06",$pro,"NODO: $consecutivoNodo - $nodoNombre WBS:$wbs");

            $nodos = DB::table($this->tblAux . 'nodos'. $this->valorT)
                ->join($this->tblAux . 'ws_nodos' . $this->valorT,$this->tblAux . 'ws_nodos' . $this->valorT . '.id_nodo1','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                ->join($this->tblAux . 'ws' . $this->valorT,$this->tblAux . 'ws' . $this->valorT . '.id_ws','=',$this->tblAux . 'ws_nodos' . $this->valorT . '.id_ws')
                ->where($this->tblAux . 'nodos' . $this->valorT . '.id_proyecto',$pro)
                ->select($this->tblAux . 'ws' . $this->valorT . '.id_ws as ws',$this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.id_estado',
                    $this->tblAux . 'nodos' . $this->valorT . '.direccion',$this->tblAux . 'nodos' . $this->valorT . '.nivel_tension',$this->tblAux . 'nodos' . $this->valorT . '.punto_fisico'
                    ,$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo as idN',
                    $this->tblAux . 'nodos' . $this->valorT . '.observaciones')
                ->orderBy($this->tblAux . 'ws' . $this->valorT . '.nombre_ws','asc')
                ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.nodos',
                array(
                "opcTbl" => "1",
                "nodos" => $nodos
                ))->render());  

        }

        if($opc == 1) //Update
        {
            if(count($nodo) > 1)
                return response()->json("-1"); //Ya existe un dato

            DB::Table($this->tblAux . 'nodos' . $this->valorT)
                    ->where('id_proyecto',$pro)
                    ->where('id_ws',$wbs)
                    ->where('id_nodo',$baremo)
            ->update(
                array(
                    'id_proyecto' => $pro,
                    'id_ws' => $wbs,
                    'id_nodo' => $baremo,
                    'nombre_nodo' => $nodoNombre,
                    'cd' => $cd,
                    'id_estado' => 'G',
                    'direccion' => $dire,
                    'observaciones' => $obser,
                    'nivel_tension' => $nt,
                    'punto_fisico' => $pf,
                    'seccionador' => $sec,
                    ));

            self::saveLog("OPERA41",$pro,"NODO: $baremo - $nodoNombre WBS:$wbs");

            $nodos = DB::table($this->tblAux . 'nodos'. $this->valorT)
                ->join($this->tblAux . 'ws_nodos' . $this->valorT,$this->tblAux . 'ws_nodos' . $this->valorT . '.id_nodo1','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                ->join($this->tblAux . 'ws' . $this->valorT,$this->tblAux . 'ws' . $this->valorT . '.id_ws','=',$this->tblAux . 'ws_nodos' . $this->valorT . '.id_ws')
                ->where($this->tblAux . 'nodos' . $this->valorT . '.id_proyecto',$pro)
                ->select($this->tblAux . 'ws' . $this->valorT . '.id_ws as ws',$this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.id_estado',
                    $this->tblAux . 'nodos' . $this->valorT . '.direccion',$this->tblAux . 'nodos' . $this->valorT . '.nivel_tension',$this->tblAux . 'nodos' . $this->valorT . '.punto_fisico'
                    ,$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo as idN',
                    $this->tblAux . 'nodos' . $this->valorT . '.observaciones')
                ->orderBy($this->tblAux . 'ws' . $this->valorT . '.nombre_ws','asc')
                ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.nodos',
                array(
                "opcTbl" => "1",
                "nodos" => $nodos
                ))->render()); 
        }

        //Validar que no exista otro nodo con el mismo nombre

        return response()->json($opc);
    }

    public function consultaBare(Request $request)
    {
        $opc = $request->all()['opc']; 

        if($opc == "0")
        {
            $cod = "%" . $request->all()['cod'] . "%";
            $des = "%" .$request->all()['des'] . "%";

            $anio = 2017;
            if($this->tblAux == "apu_gop_")
                $anio = 2016;

            $baremos = DB::table($this->tblAux . 'baremos')
                       ->where('codigo','LIKE',$cod)
                       ->where('actividad','LIKE',$des)
                       ->where('periodo',$anio)
                       ->select('codigo as bare','actividad','precio')
                       ->orderBy('id_baremo')
                       ->get();

            return response()->json($baremos);
        }

        if($opc == "1")
        {
            $cod = $request->all()['cod'];

            $nodos = DB::table($this->tblAux . 'nodos' . $this->valorT)
                       ->where('id_ws',$cod)
                       ->select('id_nodo as nod','nombre_nodo')
                       ->get();

            return response()->json($nodos);
        }

        if($opc == "2") //Consulta Materiales
        {
            $cod = "%" . $request->all()['cod'] . "%";
            $des = "%" .$request->all()['des'] . "%";

            $nodos = DB::table($this->tblAux1 . 'inv_maestro_articulos')
                       ->where('codigo_sap','LIKE',$cod)
                       ->where('nombre','LIKE',$des)
                       ->select('codigo_sap as mate','nombre',DB::raw("'15000' as precio"))
                       ->orderBy('codigo_sap')
                       ->get();

            return response()->json($nodos);
        }

        if($opc == "3") //Consulta Disponibilidad Lideres
        {
            $cod = $request->all()['cod'];
            $des = $request->all()['des'];
            $hor1 = $request->all()['hora1'];
            $hor2 = $request->all()['hora2'];
            $tipo = $request->all()['tipo'];

            $orden = $request->all()['orden'];


            //Tipo de cuadrilla -> 
            $nodSelec = null;

            $fech = explode(" ",DB::Table($this->tblAux . 'ordenes' . $this->valorT)
                    ->where('id_orden',$orden)
                    ->select('fecha_ejecucion')
                    ->get()[0]->fecha_ejecucion)[0];
            

            $cad = "EXEC sp_" . $this->tblAux . "consulta_recurso_disponible '" . $cod
                     . "','" . $des . "','" . $tipo . "','" . $hor1 . "','" . $hor2 . "','" . $fech . "',0,'0'";

            $arr = DB::select("SET NOCOUNT ON;" . $cad);

            return response()->json($arr);
        }

        if($opc == "4") //Consulta Recurso disponible y móvil
        {
            $cod = $request->all()['cod'];
            $hora1 = $request->all()['hora1'];
            $hora2 = $request->all()['hora2'];
            $tipo = $request->all()['tip'];

            $fech = explode(" ",DB::Table($this->tblAux . 'ordenes' . $this->valorT)
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->select('fecha_ejecucion')
                    ->get()[0]->fecha_ejecucion)[0];


            if($tipo == "5")
            {
                $cad = "EXEC sp_" . $this->tblAux . "consulta_recurso_disponible_aux '" . $cod
                     . "','','" . $hora1 . "','" . $hora2 . "','" . $fech . "'," . $tipo;
            }
            else
            {
                $aux = $request->all()['aux'];
                $cad = "EXEC sp_" . $this->tblAux . "consulta_recurso_disponible_aux '" . $cod
                     . "','" . $aux . "','" . $hora1 . "','" . $hora2 . "','" . $fech . "'," . $tipo;
            }
            

            $arr = DB::select("SET NOCOUNT ON;" . $cad);

            return response()->json($arr);
        }

        if($opc == "5") //Consulta WBS GOM
        {
            $wbs = $request->all()['wbs'];
            $pro = $request->all()['pro'];

            $dbGOMWBS = DB::table($this->tblAux . 'ws_gom')
                        ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_gom.id_ws')
                        ->where($this->tblAux . 'ws_gom.id_proyecto',$pro)
                        ->where($this->tblAux . 'ws.id_proyecto',$pro)
                        ->where($this->tblAux . 'ws.id_ws',$wbs)
                        ->select('nombre_ws',$this->tblAux . 'ws.id_ws','id_gom','id_estado')
                        ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomwbs',
                array(
                "gomwbs" => $dbGOMWBS
            ))->render()); 
        }

        if($opc == "6") //ADD GOM WBS
        {
            $wbs = $request->all()['wbs'];
            $gom = trim($request->all()['gom']);
            $proy = $request->all()['proy'];

            $dbWBSGOM = DB::Table($this->tblAux . 'ws_gom')
                        ->where('id_gom',$gom)
                        ->select('id_proyecto')
                        ->get();

            if(count($dbWBSGOM) > 0)
            {
                $proyectoN = DB::Table($this->tblAux . 'proyectos')
                        ->where('id_proyecto',$dbWBSGOM[0]->id_proyecto)
                        ->value('nombre');

                $id_pro = str_replace("PRY00","",str_replace("PRY000","",str_replace("PRY0000", "", $dbWBSGOM[0]->id_proyecto)));
                return response()->json(array("-1","por que se esta utilizando en el proyecto " . $proyectoN . "(" . $id_pro . ")"));
            }

            DB::Table($this->tblAux . 'ws_gom')
                ->insert(array(
                    array(
                        'id_proyecto' => $proy,
                        'id_ws' => $wbs,
                        'id_gom' => $gom,
                        'id_estado' => 0
                    )
                ));

            self::saveLog("OPERA11",$proy,"GOM: $gom  WBS:$wbs");

            $dbGOMWBS = DB::table($this->tblAux . 'ws_gom')
                        ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_gom.id_ws')
                        ->where($this->tblAux . 'ws_gom.id_proyecto',$proy)
                        ->where($this->tblAux . 'ws.id_proyecto',$proy)
                        ->where($this->tblAux . 'ws.id_ws',$wbs)
                        ->select('nombre_ws',$this->tblAux . 'ws.id_ws','id_gom','id_estado')
                        ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomwbs',
                array(
                "gomwbs" => $dbGOMWBS
            ))->render()); 

        }

        if($opc == "7") // Delete GOM WBS
        {
            $wbs = $request->all()['wbs'];
            $gomA = trim($request->all()['gom']);
            $proy = $request->all()['proy'];

            $dbWBSGOM = DB::Table($this->tblAux . 'ws_gom')
                        ->where('id_proyecto',$proy)
                        ->where('id_ws',$wbs)
                        ->where('id_gom',$gomA)
                        ->delete();

            $dbGOMWBS = DB::table($this->tblAux . 'ws_gom')
                        ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_gom.id_ws')
                        ->where($this->tblAux . 'ws_gom.id_proyecto',$proy)
                        ->where($this->tblAux . 'ws.id_proyecto',$proy)
                        ->where($this->tblAux . 'ws.id_ws',$wbs)
                        ->select('nombre_ws',$this->tblAux . 'ws.id_ws','id_gom','id_estado')
                        ->get();

            self::saveLog("OPERA13",$proy,"GOM: $gom  WBS:$wbs");

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomwbs',
                array(
                "gomwbs" => $dbGOMWBS
            ))->render()); 
        }

        if($opc == "8") // Update GOM WBS
        {
            $wbs = $request->all()['wbs'];
            $gomA = trim($request->all()['gomA']);
            $gomN = trim($request->all()['gomN']);

            $dbWBSGOM = DB::Table($this->tblAux . 'ws_gom')
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_ws',$wbs)
                        ->where('id_gom',$gomA)
                        ->update(array(
                            'id_gom' => $gomN
                            ));

            $dbGOMWBS = DB::table($this->tblAux . 'ws_gom')
                        ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_gom.id_ws')
                        ->where($this->tblAux . 'ws_gom.id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where($this->tblAux . 'ws.id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where($this->tblAux . 'ws.id_ws',$wbs)
                        ->select('nombre_ws',$this->tblAux . 'ws.id_ws','id_gom','id_estado')
                        ->get();

            self::saveLog("OPERA12",Session::get('rds_gop_proyecto_id'),"GOM ACTUAL:$gomA  GOM NUEVA: $gomN  WBS:$wbs");

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomwbs',
                array(
                "gomwbs" => $dbGOMWBS
            ))->render()); 
        }

        if($opc == "9") // Save Restricción
        {
            $restric = $request->all()['restric'];
            $impac = $request->all()['impac'];
            $fecha = explode("/",$request->all()['fecha']);
            $fecha = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
            $resp = $request->all()['resp'];
            $corr = $request->all()['corr'];
            $restricD = $request->all()['restricD']; 

            DB::table($this->tblAux .'restriccionesProyecto')
                ->insert(array(
                    array(
                        'id_proyecto' => Session::get('rds_gop_proyecto_id'),
                        'fecha_registro' => $this->fechaALong,
                        'texto_restriccion' => $restric,
                        'impacto' => $impac,
                        'fecha_limite' => $fecha,
                        'responsable' => $resp,
                        'id_estado' => 'A',
                        'restriccion_descripcion' => $restricD
                        )
                    ));

            $maxRestri = DB::table($this->tblAux .'restriccionesProyecto')
                        ->select(DB::raw("MAX(id_restriccion) as id_res"))
                        ->get();

             $maxRestri = $maxRestri[0]->id_res;
            self::saveLog("OPERA09",Session::get('rds_gop_proyecto_id'),"");

            for ($i=0; $i < count($corr); $i++) { 

                DB::table($this->tblAux .'restricciones_correos')
                ->insert(array(
                    array(
                        'id_proyecto' => Session::get('rds_gop_proyecto_id'),
                        'correo' => $corr[$i],
                        'id_restriccion' => $maxRestri
                        )
                    ));

                $arr = [];
                array_push($arr, ["res" => $restric,"imp" => $impac,"fech" => $fecha,"resp" => $resp]);
                try
                {
                    $corrE = $corr[$i];
                    \Mail::send("emails.restriccion",["arr" => $arr],function($msj) use($corrE)
                    {
                        $msj->subject('Asignación Restricción');
                        $msj->to($corrE);
                    });
                }catch(Exception $e)
                {
                }
            }
            
            $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                        ->join($this->tblAux .'tipo_restriccion','id_tipo_restriccion','=','texto_restriccion')
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_estado','A')
                        ->where('id_orden',NULL)
                        ->select('id_restriccion','nombre as texto_restriccion','texto_restriccion as tipo','impacto','fecha_limite',
                            'responsable','correo_responsable','evidencia_cierre','id_estado'
                            ,'fecha_cierre','restriccion_descripcion')
                        ->get();
            $arreglo = [];
            foreach ($consultaRestric as $key => $value) {
                array_push($arreglo,
                    [$value,
                    DB::table($this->tblAux .'restricciones_correos')
                        ->where('id_restriccion',$value->id_restriccion)
                        ->select('correo')
                        ->get()]);
            }


            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableRestricciones',
                array("restric" => $arreglo,
                    'fechaR' => $this->fechaShort
            ))->render()); 

        }

        if($opc == "10") // Update Restriccion
        {
            $restric = $request->all()['restric'];
            $impac = $request->all()['impac'];
            $fecha = explode("/",$request->all()['fecha']);
            $fecha = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
            $resp = $request->all()['resp'];
            $corr = $request->all()['corr'];
            $esta = $request->all()['esta'];
            $res_id = $request->all()['res_id'];

            if($esta == "C")
            {
                DB::table($this->tblAux .'restriccionesProyecto')
                ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                ->where('id_restriccion',$res_id)
                ->update(
                    array(
                        'texto_restriccion' => $restric,
                        'impacto' => $impac,
                        'fecha_limite' => $fecha,
                        'responsable' => $resp,
                        'correo_responsable' => $corr,
                        'id_estado' => $esta,
                        'fecha_cierre' => $this->fechaALong
                        )
                    );  

                self::saveLog("OPERA10",Session::get('rds_gop_proyecto_id'),"RESTRICCION: $res_id");
            }
            else
            {
                DB::table($this->tblAux .'restriccionesProyecto')
                ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                ->where('id_restriccion',$res_id)
                ->update(
                    array(
                        'texto_restriccion' => $restric,
                        'impacto' => $impac,
                        'fecha_limite' => $fecha,
                        'responsable' => $resp,
                        'correo_responsable' => $corr,
                        'id_estado' => $esta
                        )
                    );   
                self::saveLog("OPERA10",Session::get('rds_gop_proyecto_id'),"RESTRICCION: $res_id"); 
            }

            $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                ->select('id_restriccion','texto_restriccion','impacto','fecha_limite',
                    'responsable','correo_responsable','evidencia_cierre','id_estado'
                    ,'fecha_cierre')
                ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableRestricciones',
                array("restric" => $consultaRestric,
                    'fechaR' => $this->fechaShort,
            ))->render()); 
            
        }
        
        if($opc == "11") // Delete Restricción
        {
            $restric = $request->all()['restric'];

            DB::table($this->tblAux .'restriccionesProyecto')
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_restriccion',$restric)
                        ->delete();

            $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_estado','A')
                        ->select('id_restriccion','texto_restriccion','impacto','fecha_limite',
                            'responsable','correo_responsable','evidencia_cierre','id_estado'
                            ,'fecha_cierre','restriccion_descripcion')
                        ->get();

            self::saveLog("OPERA10",Session::get('rds_gop_proyecto_id'),"ELIMINAR RESTRICCION: $restric"); 

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableRestricciones',
                array("restric" => $consultaRestric,
                    'fechaR' => $this->fechaShort,
            ))->render()); 

        }

        if($opc == "12") // Filter Restricción
        {
            $estado = $request->all()['estado'];
            $fecha1 = "";
            $fecha2 = "";
            if($request->all()['fecha1'] != "")
            {
                $fecha1 = explode("/",$request->all()['fecha1']);
                $fecha1 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];   
            }

            if($request->all()['fecha2'] != "")
            {
                $fecha2 = explode("/",$request->all()['fecha2']);
                $fecha2 = $fecha2[2] . "-" . $fecha2[1] . "-" . $fecha2[0];   
            }

            $consultaRestric = [];
            if($fecha1 != "" && $fecha2 != "")
            {
                $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_estado',$estado)
                        ->whereBetween('fecha_limite', [$fecha1, $fecha2])
                        ->select('id_restriccion','texto_restriccion','impacto','fecha_limite',
                            'responsable','correo_responsable','evidencia_cierre','id_estado'
                            ,'fecha_cierre','restriccion_descripcion')
                        ->get();
            }
            else
            {
                $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_estado',$estado)
                        ->select('id_restriccion','texto_restriccion','impacto','fecha_limite',
                            'responsable','correo_responsable','evidencia_cierre','id_estado'
                            ,'fecha_cierre','restriccion_descripcion')
                        ->get();
            }
            

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableRestricciones',
                array("restric" => $consultaRestric,
                    'fechaR' => $this->fechaShort,
            ))->render()); 

        }

        if($opc == "13") // Agregar circuito
        {
            $circ = $request->all()['circ'];
            $circN = $request->all()['circN'];
            $estac = $request->all()['estac'];
            $tensi = $request->all()['tensi'];
            
            $circuitos = DB::table($this->tblAux . 'circuitos')
                    ->select('id_circuito')
                    ->where('id_circuito',$circ)
                    ->get();

            if(count($circuitos) == 0)
            {
                DB::table($this->tblAux . 'circuitos')
                    ->insert(array(
                        array(
                            'id_circuito' => $circ,
                            'nombre_cto' => $circN,
                            'estacion' => $estac,
                            'tension' => $tensi
                            )
                        ));

                self::saveLog("OPERA47",Session::get('rds_gop_proyecto_id'),"CIRCUITO: $circ $circN"); 
            }
            return response()->json("1"); 

        }

        if($opc == "14") // Agregar Descargo
        {
            $pro = $request->all()['pro'];
            $des = $request->all()['des'];

            $descargo = DB::table($this->tblAux . 'descargo_proyecto')
                        ->where('id_proyecto',$pro)
                        ->where('id_descargo',$des) 
                        ->select('id_estado')
                        ->get();

            if(count($descargo) == 0)
            {
                DB::table($this->tblAux . 'descargo_proyecto')
                    ->insert(array(
                        array(
                            'id_proyecto' => $pro,
                            'id_estado' => '1',
                            'id_descargo' => $des
                            )
                        ));

                self::saveLog("OPERA14",$pro,"DESCARGO: $des"); 

                $descargosEstados =  DB::table($this->tblAux . 'estados_descargos')
                ->select('id_estado as id','nombre as nom')
                ->get();

                $descargos =  DB::table($this->tblAux . 'descargo_proyecto')
                        ->where('id_proyecto',$pro)
                        ->select('id_estado as id','id_descargo as des')
                        ->get();

                return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tbldescargosproyecto',
                array(
                'descargos' => $descargos,
                'descargosE' => $descargosEstados
                ))->render());

                

            }
            else
            {
                return response()->json("0");
            }

        }

        if($opc == "15") // Update Descargo
        {
            $pro = $request->all()['pro'];
            $des = $request->all()['des'];
            $esta = $request->all()['esta'];            
            DB::table($this->tblAux . 'descargo_proyecto')
                    ->where('id_proyecto',$pro)
                    ->where('id_descargo',$des)
                    ->update(array(
                            'id_estado' => $esta
                        ));            

            self::saveLog("OPERA15",$pro,"DESCARGO: $des ESTADO $esta"); 

            return response()->json("1");
        }


        if($opc == "16") //Consulta Logs Proyectos
        {
            $proy = $request->all()['proy'];
            $logs = DB::table($this->tblAux1  . 'log_cambios')
                    ->join($this->tblAux  . 'lista_log', $this->tblAux  . 'lista_log.id_log','=',$this->tblAux1  . 'log_cambios.id_log')
                    ->join('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux1  . 'log_cambios.id_usuario')
                    ->where($this->tblAux1  . 'log_cambios.campo_valor',$proy)
                    ->select($this->tblAux1  . 'log_cambios.id',$this->tblAux  . 'lista_log.nombre',$this->tblAux1  . 'log_cambios.fecha',
                        $this->tblAux1  . 'log_cambios.campo_valor',$this->tblAux1  . 'log_cambios.descripcion',
                        'propietario')
                    ->orderBy($this->tblAux1  . 'log_cambios.fecha','desc')
                    ->get();

                    //->orderBy(DB::raw('CAST(' . $this->tblAux1  . 'log_cambios.id AS int)'))

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tbllogs',
                array(
                "logs" => $logs
                ))->render());
        }

        if($opc == "17") //Consulta Logs Orden
        {
            $orden = $request->all()['orden'];
            $logs = DB::table($this->tblAux1  . 'log_cambios')
                    ->join($this->tblAux  . 'lista_log', $this->tblAux  . 'lista_log.id_log','=',$this->tblAux1  . 'log_cambios.id_log')
                    ->join('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux1  . 'log_cambios.id_usuario')
                    ->where($this->tblAux1  . 'log_cambios.campo_valor',$orden)
                    ->select($this->tblAux1  . 'log_cambios.id',$this->tblAux  . 'lista_log.nombre',$this->tblAux1  . 'log_cambios.fecha',
                        $this->tblAux1  . 'log_cambios.campo_valor',$this->tblAux1  . 'log_cambios.descripcion',
                        'propietario')
                    ->orderBy($this->tblAux1  . 'log_cambios.fecha','desc')
                    ->get();

                    // ->orderBy(DB::raw('CAST(' . $this->tblAux1  . 'log_cambios.id AS int)'))
            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tbllogs',
                array(
                "logs" => $logs
                ))->render());
        }

        if($opc == "18") //Guarda Levantamiento v2
        {
            $wbs = $request->all()['wbs'];
            $nodo = $request->all()['nod'];

            $pry = $request->all()['proy'];

            if(isset($request->all()['bar'][0]["bare"]))
            {
                for ($i=0; $i < count($request->all()['bar']); $i++) { 
                    $bare = DB::Table($this->tblAux . 'detalle_mobra' . $this->valorT)
                            ->where('id_ws',$wbs)
                            ->where('id_proyecto',$pry)
                            ->where('id_nodo',$nodo)
                            ->where('id_baremo',$request->all()['bar'][$i]["bare"])
                            ->select('cantidad_replanteo')
                            ->get();

                    if(count($bare) == 0)
                    {
                        DB::Table($this->tblAux . 'detalle_mobra' . $this->valorT)
                        ->insert(array(
                            array(
                                'id_proyecto' => $pry,
                                'id_ws' => $wbs,
                                'id_nodo' => $nodo,
                                'id_baremo' => $request->all()['bar'][$i]["bare"],
                                'cantidad_replanteo' => $request->all()['bar'][$i]["cant"]
                                )));

                        //self::saveLog("OPERA07",$pry,"BAREMO: $actividad CANTIDAD: " . $results[$i]["cant"] . " NODO: $consecutivoNODO WBS: $consecutivoWB EXCEL"); 
                    }

                }
                
            }

            if(isset($request->all()['mat'][0]["mat"]))
            {
                for ($i=0; $i < count($request->all()['mat']); $i++) { 
                    
                    $cod_mat = DB::table($this->tblAux1 .'inv_maestro_articulos')
                            ->where('codigo_sap',$request->all()['mat'][$i]["mat"])
                            ->select('id_articulo')
                            ->get()[0]->id_articulo;

                    $mat = DB::Table($this->tblAux . 'detalle_materiales' . $this->valorT)
                            ->where('id_ws',$wbs)
                            ->where('id_proyecto',$pry)
                            ->where('id_nodo',$nodo)
                            ->where('id_articulo',$cod_mat)
                            ->select('cantidad_replanteo')
                            ->get();


                    if(count($mat) == 0)
                    { //Insert
                        DB::Table($this->tblAux . 'detalle_materiales' . $this->valorT)
                        ->insert(array(
                            array(
                                'id_proyecto' => $pry,
                                'id_ws' => $wbs,
                                'id_nodo' => $nodo,
                                'id_articulo' => $cod_mat,
                                'cantidad_replanteo' => $request->all()['mat'][$i]["cant"]
                                )));
                    }
                    else
                    {
                        $auxCont = 0;
                        $auxCont = floatval($mat[0]->cantidad_replanteo) + floatval($request->all()['mat'][$i]["cant"]);
                        DB::Table($this->tblAux . 'detalle_materiales' . $this->valorT)
                        ->where('id_ws',$wbs)
                        ->where('id_proyecto',$pry)
                        ->where('id_nodo',$nodo)
                        ->where('id_articulo',$cod_mat)
                        ->update(array(
                                'cantidad_replanteo' =>  $auxCont
                                ));

                        /*self::saveLog("OPERA08",$proyecto,"MATERIAL: $material CANTIDAD: $auxCont  NODO: $consecutivoNODO WBS: $consecutivoWB EXCEL");

                        if($archivo = fopen($fileL, "a"))
                        {
                            fwrite($archivo, date("d m Y H:m:s"). " El Material $material ya se encuentra registrado.\n");
                            fclose($archivo);
                        }
                        Session::flash('Archivo',"logsCargaExcel_" . $this->fechaShort);*/
                    }
                }
            }

            return response()->json("1");
        }
    }   

    public function saveACTIVIDADProgramados(Request $request)
    {
        $opc = $request->all()['opc'];
        
        $pro = $request->all()['pro'];
        $wbs = $request->all()['wbs'];
        $nodo = $request->all()['nodo'];
        $baremo = $request->all()['baremo']; 
        $cant = $request->all()['cant'];


        if($opc == "0") //Crear
        {
            DB::Table($this->tblAux . 'detalle_mobra' . $this->valorT)
                ->insert(array(
                    array(
                        'id_proyecto' => $pro,
                        'id_nodo' => $nodo,
                        'id_baremo' => $baremo,
                        'cantidad_replanteo' => $cant,
                        'id_ws' => $wbs
                        )));

            self::saveLog("OPERA07",$pro,"BAREMO: $baremo CANTIDAD: $cant  NODO: $nodo WBS: $wbs MANUALMENTE"); 

            $anio = 2017;
            if($this->tblAux == "apu_gop_")
                $anio = 2016;

            $baremos = DB::table($this->tblAux . 'detalle_mobra' . $this->valorT)
                    ->where($this->tblAux . 'detalle_mobra' . $this->valorT . '.id_proyecto',$pro)
                    ->where($this->tblAux . 'baremos.periodo',$anio)
                    ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'detalle_mobra' . $this->valorT . '.id_nodo','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                    ->join($this->tblAux . 'baremos',$this->tblAux . 'baremos.codigo','=',$this->tblAux . 'detalle_mobra' . $this->valorT . '.id_baremo')
                    ->select($this->tblAux . 'detalle_mobra' . $this->valorT . '.id_baremo',$this->tblAux . 'detalle_mobra' . $this->valorT . '.cantidad_replanteo',
                        $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'baremos.actividad')
                    ->groupBy($this->tblAux . 'detalle_mobra' . $this->valorT . '.id_baremo',$this->tblAux . 'detalle_mobra' . $this->valorT . '.cantidad_replanteo',
                        $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'baremos.actividad')
                    ->orderBY($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo')
                    ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblactividades',
                array(
                "actividades" => $baremos
                ))->render()); 

        } 


        if($opc == "1") //Update
        {
            
        } 
    }

    public function saveMaterialProgramados(Request $request)
    {

        $opc = $request->all()['opc'];
        
        $pro = $request->all()['pro'];
        $wbs = $request->all()['wbs'];
        $nodo = $request->all()['nodo'];
        $material = $request->all()['material']; 
        $cant = $request->all()['cant'];

        // ID_ARTICULO
        if($opc == "0") //Crear
        {

            //$consecutivoMATE1 = self::generaConsecutivo("ID_ARTICULO");

            $mat = DB::Table($this->tblAux1 . 'inv_maestro_articulos')
                    ->where('codigo_sap',$material)
                    ->select('id_articulo')
                    ->get()[0]->id_articulo;

            DB::Table($this->tblAux . 'detalle_materiales' . $this->valorT)
                ->insert(array(
                    array(
                        'id_proyecto' => $pro,
                        'id_nodo' => $nodo,
                        'id_ws' => $wbs,
                        'id_articulo' => $mat,
                        'cantidad_replanteo' => $cant,
                        'valor_unitario' => 0
                        )));

            self::saveLog("OPERA08",$pro,"MATERIAL: $material CANTIDAD: $cant  NODO: $nodo WBS: $wbs MANUALMENTE"); 

            $material = DB::table($this->tblAux . 'detalle_materiales' . $this->valorT)
                    ->where($this->tblAux . 'detalle_materiales' . $this->valorT . '.id_proyecto',$pro)
                    ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'detalle_materiales' . $this->valorT . '.id_nodo','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                    ->join($this->tblAux1 . 'inv_maestro_articulos',$this->tblAux1 . 'inv_maestro_articulos.id_articulo','=',$this->tblAux . 'detalle_materiales' . $this->valorT . '.id_articulo')
                    ->select($this->tblAux1 . 'inv_maestro_articulos.codigo_sap',$this->tblAux . 'detalle_materiales' . $this->valorT . '.cantidad_replanteo',
                        $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux1 . 'inv_maestro_articulos.nombre')
                    ->groupBy($this->tblAux1 . 'inv_maestro_articulos.codigo_sap',$this->tblAux . 'detalle_materiales' . $this->valorT . '.cantidad_replanteo',
                        $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux1 . 'inv_maestro_articulos.nombre')
                    ->orderBY($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo')
                    ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblmateriales',
                array(
                "material" => $material
                ))->render()); 
        }
    }

    public function consultBalance(Request $request)
    {
        $opc = intval($request->all()["opc"]);

        if($opc == 1) //Consulta por DC
        {
            $dc = $request->all()["dc"];  
            $conci1 = $request->all()["conci1"]; 

            $datos = DB::table($this->tblAux .'ordenes_materiales_documentos')
                    ->where('id_documento',$dc)
                    ->where('id_tipo_documento','T005')
                    ->select('id_orden','id_lider','id_nodo')
                    ->get();

            if(count($datos) == 0)
                return response()->json("0");

            $cs = DB::table($this->tblAux .'ordenes_materiales_documentos')
                    ->where('id_documento',$dc)
                    ->where('id_tipo_documento','T005')
                    ->select('id_documento_cs')
                    ->get()[0]->id_documento_cs;

            $cad = "EXEC sp_" . $this->tblAux . "balances " . 1
                     . "," . 0 . "," . $conci1 . ",'" . $cs . "','" . $dc . "','',''";

            $arr = DB::select("SET NOCOUNT ON;" . $cad);

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBalances',
                array(
                "opc" => $opc,
                "conc" => $conci1,
                "prog" => $arr,
                "conso" => 0,
            ))->render()); 


        }

        if($opc == 2) //Consulta por ManiObra
        {
            $ot = $request->all()["ot"];  
            $consol1 = $request->all()["consol1"];  
            $conci2 = $request->all()["conci2"]; 

            $ordenE = DB::table($this->tblAux . 'ordenes')
                    ->where('id_orden',$ot)
                    ->select('id_estado')
                    ->get();

            if(count($ordenE) == 0)
                return response()->json("-1");

            if($ordenE[0]->id_estado == "A1")
                return response()->json("-3");

            if($ordenE[0]->id_estado == "E1")
                return response()->json("-4");

            $dcsinConf = DB::table($this->tblAux1 . 'inv_documentos')
                    ->where('id_orden',$ot)
                    ->whereIn('id_estado',["E1","E2"])
                    ->count();

            if($dcsinConf > 0)
                return response()->json("-5");


            //var_dump($arregloLe);


            $cad = "EXEC sp_" . $this->tblAux . "balances " . 2
                     . "," . $consol1 . "," . $conci2 . ",'" . $ot . "','0','',''";

            $arr = DB::select("SET NOCOUNT ON;" . $cad);

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBalances',
                array(
                "opc" => $opc,
                "conc" => $conci2,
                "conso" => $consol1,
                "prog" => $arr
            ))->render()); 

        }

        if($opc == 3) //NODO
        {
            $pro = $request->all()["nod"];  
            $nodo = $request->all()["proy2"];  
            $consol2 = $request->all()["consol2"]; 
            $conci3 = $request->all()["conci3"]; 


            $cad = "EXEC sp_" . $this->tblAux . "balances " . 3
                     . "," . $consol2 . "," . $conci3 . ",'" . $pro . "','" . $nodo . "','',''";

            $arr = DB::select("SET NOCOUNT ON;" . $cad);

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBalances',
                array(
                "opc" => $opc,
                "conc" => $conci3,
                "prog" => $arr,
                "conso" => $consol2,
            ))->render()); 

        }


        if($opc == 4) //GOM
        {
            $gom = $request->all()["gom"]; 
            $consol3 = $request->all()["consol3"]; 
            $conci4 = $request->all()["conci4"]; 

            $fecha1 = $request->all()["fecha1"]; 
            $fecha2 = $request->all()["fecha2"]; 

            if($fecha1 != "")
                $fecha1 = explode("/", $fecha1)[2] . "-" . explode("/", $fecha1)[1] . "-" . explode("/", $fecha1)[0];
            else
                $fecha1 = "2015-01-01";

            if($fecha2 != "")
                $fecha2 = explode("/", $fecha2)[2] . "-" . explode("/", $fecha2)[1] . "-" . explode("/", $fecha2)[0];
            else
                $fecha2 = "2050-01-01";


            $cad = "EXEC sp_" . $this->tblAux . "balances " . 4
                     . "," . $consol3 . "," . $conci4 . ",'" . $gom . "','" . "" . "','$fecha1','$fecha2'";

            $arr = DB::select("SET NOCOUNT ON;" . $cad);

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBalances',
                array(
                "opc" => $opc,
                "conc" => $conci4,
                "prog" => $arr,
                "conso" => $consol3,
            ))->render()); 

        }

        if($opc == 5) //PROYECTO
        {
            $proy2 = $request->all()["proy2"]; 
            $consol4 = $request->all()["consol4"]; 
            $conci5 = $request->all()["conci5"]; 

            $cad = "EXEC sp_" . $this->tblAux . "balances " . 5
                     . "," . $consol4 . "," . $conci5 . ",'" . $proy2 . "','" . "" . "','',''";

            //echo $cad;
            $arr = DB::select("SET NOCOUNT ON;" . $cad);

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBalances',
                array(
                "opc" => $opc,
                "conc" => $conci5,
                "prog" => $arr,
                "conso" => $consol4,
            ))->render()); 

        }
        return response()->json($opc);
    }

    /*FIN ORDENES TRABAJO PROGRAMADO*/


    /*ORDENES DE TRABAJO PROGRAMADOS*/

    public function verOrdenTrabajo($proyecto = '',$orden = '')
    {
        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

       
        $cont = DB::Table($this->tblAux . 'proyectos')
                ->where('id_proyecto',$proyecto)
                ->count();

        if($cont == 0)
            return Redirect::to('/redes/ordenes/home');
        
        if($orden != '')
        {
            $contO = DB::Table($this->tblAux . 'ordenes')
                    ->where('id_orden',$orden)
                    ->count();

            if($contO == 0)
                return Redirect::to('/redes/ordenes/ver/' . $proyecto);
        }



        $cad = "SELECT *
            FROM
            (SELECT ws,nombre_nodo,cd,TBL1.id_estado,TBL1.direccion,TBL1.nivel_tension,TBL1.punto_fisico,TBL1.seccionador
            ,TBL1.nombre_ws,TBL1.idN,ws_gom.id_gom ,
                row_number() 
                  over (partition by TBL1.idN order by nombre_nodo) as cant
            FROM 
                (select ws.id_ws as ws,nodos.nombre_nodo,nodos.cd,nodos.id_estado,nodos.direccion,nodos.nivel_tension,nodos.punto_fisico,
                nodos.seccionador,ws.nombre_ws,nodos.id_nodo as idN
                 from rds_gop_nodos as nodos
                inner join rds_gop_ws_nodos as ws_nodos on ws_nodos.id_nodo1 = nodos.id_nodo
                INNER JOIN rds_gop_ws as ws on ws.id_ws = ws_nodos.id_ws
                WHERE nodos.id_proyecto = '" . $proyecto . "') AS TBL1
            LEFT JOIN rds_gop_ws_gom as ws_gom on ws_gom.id_ws  = TBL1.ws
            GROUP BY ws,nombre_nodo,cd,TBL1.id_estado,TBL1.direccion,TBL1.nivel_tension,TBL1.punto_fisico,TBL1.seccionador
            ,TBL1.nombre_ws,TBL1.idN,ws_gom.id_gom
            ) as TBL2
            WHERE TBL2.cant = 1
            ORDER BY nombre_ws asc,nombre_nodo asc";


        $nodos = DB::select( $cad);



        $nodosYaUtilizados = DB::Table($this->tblAux . 'ordenes_manoobra')
                            ->where('id_proyecto',$proyecto)
                            ->select('id_nodo')
                            ->groupBy('id_nodo')
                            ->get();
        
        $per = DB::table($this->tblAux . 'ordenes_manoobra_detalle') 
                ->join('rh_personas','rh_personas.identificacion','=',$this->tblAux . 'ordenes_manoobra_detalle.id_lider')
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_proyecto',$proyecto)
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_orden',$orden)
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_estado',0)
                ->select($this->tblAux . 'ordenes_manoobra_detalle.id_lider',DB::raw("rh_personas.nombres + ' ' + rh_personas.apellidos as nombre"),$this->tblAux . 'ordenes_manoobra_detalle.id_tipo')
                ->get();


        $encabezado = DB::table($this->tblAux . 'ordenes' . $this->valorT)
                    ->join('gop_estado_ordenes','gop_estado_ordenes.id_estado','=',$this->tblAux . 'ordenes' . $this->valorT . ".id_estado")
                    ->where('id_orden',$orden)
                    ->where('id_proyecto',$proyecto)
                    ->select('tipo_trabajo','gop_estado_ordenes.nombre as id_estadoN','gop_estado_ordenes.id_estado','fecha_programacion','fecha_ejecucion','id_proyecto'
                        ,'fecha_emision','fecha_prevista_ejecucion','ing_solicitante'
                    ,'ing_soli_cam','supervisor','direccion','lv','lm','prox','lv_lm','exp','cd','nivel_tension','hora_ini','hora_corte'
                    ,'hora_cierre','hora_fin','observaciones','gom_adec','gom_inst','fac_orden','rad_orden','proy_orden','gom',
                    'descargo','descargo2','observacion_reprogramacion','fecha_reprogramacion','orden_nueva_reprogra','usuario_reprogramacion','supervisor_ejecutor',
                    'descargo3','descargo4','descargo5','descargo6','descargo7','x','y','fecha_ejecucion_final','vyp','inc')
                    ->get();
                    
        $proyectoName = Db::table($this->tblAux . 'proyectos' . $this->valorT)
                    ->where('id_proyecto',$proyecto)
                    ->select('nombre','tipo_proyecto')
                    ->get();

        $proyectoTipo = Db::table($this->tblAux . 'tipo_proyecto')
                        ->where('id_proyecto',$proyectoName[0]->tipo_proyecto)
                        ->value('des_proyecto');


        $obser = Db::table($this->tblAux . 'proyectos' . $this->valorT)
                    ->where('id_proyecto',$proyecto)
                    ->value('observaciones');

        $dire = Db::table($this->tblAux . 'proyectos' . $this->valorT)
                    ->where('id_proyecto',$proyecto)
                    ->value('direccion');


        $tiposCuadrilla = Db::Table($this->tblAux . 'tipo_cuadrilla')
                        ->select('id_tipo_cuadrilla','nombre')
                        ->get();

        $nodosAfectados = DB::table($this->tblAux . 'ordenes_manoobra')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_manoobra.id_nodo')
                            ->join($this->tblAux . 'ws_nodos' . $this->valorT,$this->tblAux . 'ws_nodos' . $this->valorT . '.id_nodo1','=',$this->tblAux . 'ordenes_manoobra.id_nodo')
                            ->join($this->tblAux . 'ws' . $this->valorT,$this->tblAux . 'ws' . $this->valorT . '.id_ws','=',$this->tblAux . 'ws_nodos' . $this->valorT . '.id_ws')
                            ->where($this->tblAux . 'ordenes_manoobra.id_proyecto',$proyecto)
                            ->where($this->tblAux . 'ordenes_manoobra.id_orden',$orden)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.direccion',
                                $this->tblAux . 'nodos' . $this->valorT . '.punto_fisico',$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                $this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'ws' . $this->valorT . '.id_ws')
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.direccion',
                                $this->tblAux . 'nodos' . $this->valorT . '.punto_fisico',$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                $this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'ws' . $this->valorT . '.id_ws')
                            ->orderBy($this->tblAux . 'ws' . $this->valorT . '.nombre_ws','asc')
                            ->get();

       // var_dump($nombrdos);
        $gomArray = [];
        for ($i = 0; $i < count($nodosAfectados) ; $i++) {
            $gom = DB::table($this->tblAux .'ws_gom')
                    ->where('id_ws', $nodosAfectados[$i]->id_ws)
                    ->where('id_proyecto',$proyecto)
                    ->where('id_estado',0)
                    ->select('id_gom')
                    ->get();    
            array_push($gomArray, $gom);
        }

        $cad = "EXEC sp_" . $this->tblAux . "consulta_recurso_disponible '" . ""
                     . "','" . "" . "','" . "" . "','" . "" . "','" . "" . "','" . "" . "',1,'" . $orden . "'";


        $arr = DB::select("SET NOCOUNT ON;" . $cad);

        //return response()->json($arr);

        $dc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                ->join($this->tblAux1  . 'inv_documentos' . $this->valorT,$this->tblAux1  . 'inv_documentos' . $this->valorT . ".id_documento",'=',$this->tblAux . 'ordenes_materiales_documentos.id_documento')
                ->join('inv_cat_estado_documentos','inv_cat_estado_documentos.id_estado','=',$this->tblAux1  . 'inv_documentos' . $this->valorT . ".id_estado")
                ->where($this->tblAux . 'ordenes_materiales_documentos.id_orden',$orden)
                ->where($this->tblAux . 'ordenes_materiales_documentos.id_tipo_documento','T005')
                ->select($this->tblAux . 'ordenes_materiales_documentos.id_documento','id_lider','nombre',
                    'inv_cat_estado_documentos.id_estado')
                ->get();

        //Guardamos cabeza
        
        $fechaA = explode("-",$this->fechaShort);
        $fechaA = $fechaA[2] . "/" . $fechaA[1] . "/" . $fechaA[0];

        $descargos =  DB::table($this->tblAux . 'descargo_proyecto')
                        ->where('id_proyecto',$proyecto)
                        ->select('id_estado as id','id_descargo as des')
                        ->orderBy('id_descargo')
                        ->get();

        $clasificacion = DB::table('inv_cat_clasificacion')
                        ->select('id_clasificacion','nombre')
                        ->whereIn('id_clasificacion',['CL14','CL19','CL22'])
                        ->get();

        $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                        ->join($this->tblAux .'tipo_restriccion','id_tipo_restriccion','=','texto_restriccion')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->select('id_restriccion','nombre as texto_restriccion','texto_restriccion as tipo','impacto','fecha_limite',
                            'responsable','correo_responsable','evidencia_cierre','id_estado'
                            ,'fecha_cierre','restriccion_descripcion')
                        ->get();
        $arreglo = [];
        foreach ($consultaRestric as $key => $value) {
            array_push($arreglo,
                [$value,
                DB::table($this->tblAux .'restricciones_correos')
                    ->where('id_restriccion',$value->id_restriccion)
                    ->select('correo')
                    ->get()]);
        }

        $restricCombo = DB::table($this->tblAux .'tipo_restriccion')
                        ->select('id_tipo_restriccion','nombre')
                        ->orderBY('id_tipo_restriccion')
                        ->get();
        
        $resp = DB::Table($this->tblAux . 'responsable_restricciones')
                    ->get(['id','nombre','correo']);

        return view('proyectos.redes.trabajoprogramado.orden',array(
            'nodos' => $nodos,
            'recurso' => $arr,
            'noddSel' => $nodosAfectados,
            'comboxP' => $per,
            'encabezado' => $encabezado,
            'proyNombre' => $proyectoName,
            'tipCuadrilla' => $tiposCuadrilla,
            "numeroDespacho" => $dc,
            "ejecucionB" => null,
            "ejecucionM" => null,
            "fechaA" => $fechaA,
            "tipo" => $proyectoTipo,
            "goms" => $gomArray,
            'fechaR' => $this->fechaShort,
            "descargos" => $descargos,
            'restric' => $arreglo,
            "nodosYaUtilizados" => $nodosYaUtilizados,
            "clasificacion" => $clasificacion,
            "restricC" => $restricCombo,
            'opcTbl' => 2,
            "obs" => $obser,
            "res" => $resp,
            "tipoProyecto" => $proyectoName[0]->tipo_proyecto,
            "dire" => $dire,
            "proyecto" => $proyecto,
            "orden" => $orden,
            'fecha' => explode('-',$this->fechaShort)[2] . "/" . explode('-',$this->fechaShort)[1] . "/" .explode('-',$this->fechaShort)[0]));
    }

    public function saveOrdenesTrabajoProgramado(Request $request)
    {
        $opc = $request->all()['opc'];
        if($opc == "2")
        {
//            Session::forget('rds_gop_proyecto_orden_id');
//            return response()->json("1");
        }

        if($opc == "3")
        {   
            set_time_limit(0);
            $gom = $request->all()['gom'];
            $gomI = $request->all()['gomI'];

            $proyecto = $request->all()['proyecto'];   
            $orden_proyecto = $request->all()['orden'];   

            //Generar documento de despacho
            $materialesxPersona = DB::table($this->tblAux . 'ordenes_materiales')
                        ->where('id_orden',$orden_proyecto)
                        ->where('id_proyecto',$proyecto)
                        ->select('id_persoanCargo as persona') //'id_nodo','id_articulo','cantidad_confirmada',
                        ->groupBy('id_persoanCargo')
                        ->get();

            $ord = DB::table($this->tblAux . 'ordenes' . $this->valorT)
                ->where('id_orden',$orden_proyecto)
                ->select('id_proyecto','fecha_ejecucion')
                ->get()[0];

            $cantidadManoObra = DB::table($this->tblAux . 'ordenes_manoobra')
                            ->where('id_orden',$orden_proyecto)
                            ->select(DB::raw('COUNT(id_baremo) AS cont'))
                            ->get()[0]->cont;

            $cantidadMate = DB::table($this->tblAux . 'ordenes_materiales')
                            ->where('id_orden',$orden_proyecto)
                            ->select(DB::raw('COUNT(id_articulo) AS cont'))
                            ->get()[0]->cont;

            if($cantidadManoObra == 0 && $cantidadMate == 0)
                return response()->json("-2");


            $proyecto = $ord->id_proyecto;
            $fechaE = explode(" ",$ord->fecha_ejecucion)[0];

            $restriccionesObligatorias = DB::Table($this->tblAux . 'restriccionesProyecto as rest')
                                        ->join($this->tblAux . 'tipo_restriccion as tipo','rest.texto_restriccion','=','tipo.id_tipo_restriccion')
                                        ->where('tipo.obligatorio',1)
                                        ->whereIn('rest.id_estado',['A','P'])
                                        ->where('rest.id_orden',$orden_proyecto)
                                        ->get(['tipo.nombre as tipoR','restriccion_descripcion','fecha_limite','responsable']);

            //Actualizado estado de la orden
            DB::table($this->tblAux . 'ordenes' . $this->valorT)
                ->where('id_orden',$orden_proyecto)
                ->update(array(
                    'gom' => $gom,
                    'gom_adec' => $gom,
                    'gom_inst' => $gomI
                    ));

            if(count($restriccionesObligatorias) > 0)
                return response()->json(array(
                        "res" => "-1",
                        "restriccion" => $restriccionesObligatorias));

            //Actualizado estado de la orden
            DB::table($this->tblAux . 'ordenes' . $this->valorT)
                ->where('id_orden',$orden_proyecto)
                ->update(array(
                    'id_estado' => 'E2'
                    ));

            self::saveLog("OPERA32",$orden_proyecto,"GOM " . $gom); 

            return response()->json("1");

            /*
            // DC Michael
            try
            {
                DB::beginTransaction();
                foreach ($materialesxPersona as $mat => $val) {
                    $materialesxPerNodo = DB::table($this->tblAux . 'ordenes_materiales')
                            ->where('id_orden',$orden_proyecto)
                            ->where('id_proyecto',$proyecto)
                            ->where('id_persoanCargo',$val->persona)
                            ->select('id_nodo','id_articulo','cantidad_confirmada') //,
                            ->groupBy('id_nodo','id_articulo','cantidad_confirmada')
                            ->orderBY('id_nodo')
                            ->get();

                    //echo $val->persona . "-" . count($materialesxPerNodo);
                    if(count($materialesxPerNodo) == 0)
                        continue;

                    
                    foreach ($materialesxPerNodo as $matP => $val1) {
                        $doc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                            ->where('id_orden',$orden_proyecto)
                            ->where('id_lider',$val->persona)
                            ->where('id_nodo',$val1->id_nodo)
                            ->where('id_tipo_documento',"T005")
                            ->select('id_documento')
                            ->get();

                        if(count($doc) == 0) //Ya existe un documento
                        {
                            //Inserta documento
                                $codDocumento = self::dame_uncodigo_almacen("T005");

                                $bodega = DB::Table($this->tblAux . 'proyectos')
                                            ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                                            ->select('id_bodega','nombre')
                                            ->get()[0];

                                $bode = $bodega->id_bodega;
                                $nombre = $bodega->nombre . " (" . str_replace("PRY00","",str_replace("PRY000","",str_replace("PRY0000", "", $proyecto))) . ")";
                                //Guardamos cabeza

                                $wbsNodo = DB::Table($this->tblAux . 'ws_nodos')
                                            ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_nodos.id_ws')
                                            ->join($this->tblAux . 'nodos',$this->tblAux . 'nodos.id_nodo','=',$this->tblAux . 'ws_nodos.id_nodo1')
                                            ->where($this->tblAux . 'ws_nodos.id_proyecto',Session::get('rds_gop_proyecto_id'))
                                            ->select('nombre_ws','nombre_nodo')
                                            ->get()[0];

                                DB::table($this->tblAux1  . 'inv_documentos' . $this->valorT)
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codDocumento,
                                            'id_tipo_movimiento' => "T005",
                                            'fecha' => $fechaE,
                                            'fecha_sistema' => $this->fechaALong,
                                            'id_bodega_origen' => $bode,
                                            'id_bodega_destino' => $val->persona,
                                            'observaciones' => "DESPACHO DE MATERIALES ASIGNADO A PROYECTO: " . $nombre  . "_ORDEN DE TRABAJO:" . $orden_proyecto  . "_WBS:" . $wbsNodo->nombre_ws . "_NODO:" . $wbsNodo->nombre_nodo . "_GOM:" . $gom . "_PERSONA:" . $val->persona,
                                            'id_estado' => 'E1',
                                            'id_orden' => $orden_proyecto,
                                            'gom' => $gom,
                                            "id_usuario_edicion" => Session::get('user_login'),
                                            "id_nodo" => $val1->id_nodo,
                                            "fecha_confirmacion" => $this->fechaALong,
                                            "fecha_edicion" => $this->fechaALong,
                                            "id_usuario_autoriza_despacho" => Session::get('user_login'),
                                            "fecha_autoriza_despacho" => $this->fechaALong
                                            )
                                        ));
                                
                                self::saveLog("OPERA48",$codDocumento,"ORDEN " . $orden_proyecto); 

                                $id_al = "0000";
                                if($this->tblAux1 == "rds_")
                                    $id_al = "AR09";


                                //Guardamos cuerpo
                                DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codDocumento,
                                            'id_articulo' => $val1->id_articulo, 
                                            'solicitado' => $val1->cantidad_confirmada,
                                            'id_almacen' => $id_al,
                                            'id_nodo' => $val1->id_nodo
                                            )
                                        ));
                                    
                                    self::saveLog("OPERA49",$codDocumento,"ARTICULO " . $val1->id_articulo); 

                                //Guardamos relación
                                DB::table($this->tblAux  . 'ordenes_materiales_documentos')
                                    ->insert(array(
                                        array(
                                            'id_orden' => $orden_proyecto,
                                            'id_lider' => $val->persona,
                                            'id_nodo' => $val1->id_nodo,
                                            'id_tipo_documento' => "T005",
                                            'id_documento' => $codDocumento
                                            )
                                        ));
                        }
                        else
                        {
                            //Agrego más hijos al detalle
                            $codDocumento = $doc[0]->id_documento;

                            $detalleArti = DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                            ->where('id_documento',$codDocumento)
                                            ->where('id_articulo',$val1->id_articulo)
                                            ->select('solicitado')
                                            ->get();

                            //Guardamos cuerpo
                            if(count($detalleArti) == 0)
                            {
                                $id_al = "0000";
                                if($this->tblAux1 == "rds_")
                                    $id_al = "AR09";

                                DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codDocumento,
                                            'id_articulo' => $val1->id_articulo, 
                                            'solicitado' => $val1->cantidad_confirmada,
                                            'id_almacen' => $id_al
                                            )
                                        ));
                                self::saveLog("OPERA49",$codDocumento,"ARTICULO " . $val1->id_articulo); 
                            }
                            //Update documentos
                        }
                    }
                
                }

                

                DB::commit();

                return response()->json("1");
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
            */
        }

        $proyecto = $request->all()['pry'];   
        $orden_proyecto = $request->all()['orden'];   

        $fechEm = self::cambiaf_a_mysql($request->all()['fechEm'],2);
        $fechPr = self::cambiaf_a_mysql($request->all()['fechPr'],2);
        $fechEj = self::cambiaf_a_mysql($request->all()['fechEj'],2);

        if(isset($request->all()['fechEj2']))
            $fechEj2 = self::cambiaf_a_mysql($request->all()['fechEj2'],2);
        else
            $fechEj2 = $fechEj;

        $soliC = $request->all()['soliC'];
        $soliCAM = $request->all()['soliCAM'];
        $soliSup = $request->all()['soliSup'];
        $dire = $request->all()['dire'];
        $tipo = $request->all()['tipo'];
        $cd = $request->all()['cd'];
        $nt = $request->all()['nivel'];
        $horaI = $request->all()['horaI'];
        $horaC = $request->all()['horaC'];
        $horaCi = $request->all()['horaCi'];
        $horaF = $request->all()['horaF'];
        $obser = $request->all()['obser'];
        $gomA = $request->all()['gomA'];
        $gomI = $request->all()['gomI'];
        $facO = $request->all()['facO'];
        $radO = $request->all()['radO'];
        $proY = $request->all()['proY'];
        $des1 = $request->all()['des1'];
        $des2 = $request->all()['des2'];

        $des3 = $request->all()['des3'];
        $des4 = $request->all()['des4'];
        $des5 = $request->all()['des5'];
        $des6 = $request->all()['des6'];
        $des7 = $request->all()['des7'];

        $gps = $request->all()['gps'];

        $x = null;
        $y = null;
        if($gps != "")
        {
            $gps = explode(",",$gps);
            if(count($gps) > 1)
            {
                $x = trim($gps[0]);
                $y = trim($gps[1]);
            }
        }
        

        $auxCont = DB::Table($this->tblAux . 'ordenes' . $this->valorT)
                    ->where('id_orden',$orden_proyecto)
                    ->select('id_orden')
                    ->get();

        if(count($auxCont) == 0) //Inserta registros
        {
            try
            {
                DB::beginTransaction();
                $consecutivoOrden = self::generaConsecutivo("ID_ORDEN");
                DB::Table($this->tblAux . 'ordenes' . $this->valorT)
                    ->insert(array(
                        array(
                            'id_orden' => $consecutivoOrden,
                            'id_tipo' => "T56",
                            'fecha_programacion' => $fechPr,
                            'fecha_ejecucion' => $fechEj,
                            'fecha_ejecucion_final' => $fechEj2,
                            'id_proyecto' => $proyecto,
                            'fecha_emision' => $fechEm,
                            'ing_solicitante' => $soliC,
                            'ing_soli_cam' => $soliCAM,
                            'supervisor' => $soliSup,
                            'direccion' => $dire,
                            'lv' => explode(";",$tipo)[0],
                            'lm' => explode(";",$tipo)[1],
                            'prox' => explode(";",$tipo)[2],
                            'lv_lm' => explode(";",$tipo)[3],
                            'exp' => explode(";",$tipo)[4],
                            'vyp' => explode(";",$tipo)[5],
                            'inc' => explode(";",$tipo)[6],
                            'cd' => $cd,
                            'nivel_tension' => $nt,
                            'hora_ini' => $horaI,
                            'hora_corte' => $horaC,
                            'hora_cierre' => $horaCi,
                            'hora_fin' => $horaF,
                            'observaciones' => $obser,
                            'gom_adec' => $gomA,
                            'gom_inst' => $gomI,
                            'fac_orden' => $facO,
                            'rad_orden' => $radO,
                            'proy_orden' => $proY,
                            'id_estado' => "E1",
                            'descargo' => $des1,
                            'descargo2' => $des2,
                            'descargo3' => $des3,
                            'descargo4' => $des4,
                            'descargo5' => $des5,
                            'descargo6' => $des6,
                            'descargo7' => $des7,
                            'x' => $x,
                            'y' => $y,
                            'supervisor_ejecutor' => $request->all()['soliSupEje'],
                            'tipo_trabajo' => $request->all()['tipo_trabajo'] 
                            )));
                
                self::saveLog("OPERA17",$consecutivoOrden,"PROYECTO $proyecto"); 

                DB::commit();

                //Session::put('rds_gop_proyecto_orden_id',$consecutivoOrden);

                return response()->json($consecutivoOrden);
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
        }

        else // Update registros
        {
            try
            {


                //$fechEj //Fecha inicial
                //$fechEj2 //Fecha final

                $proyecto = $request->all()['pry'];   
                $orden_proyecto = $request->all()['orden'];   


                $fechasActual = DB::Table($this->tblAux . 'ordenes' . $this->valorT)
                                ->where('id_orden',$orden_proyecto)
                                ->get(['fecha_ejecucion','fecha_ejecucion_final']);

                $fechaEjecucionInicial = $fechasActual[0]->fecha_ejecucion;
                $fechaEjecucionFinal = $fechasActual[0]->fecha_ejecucion_final;


                $datetime1 = new \DateTime($fechaEjecucionInicial);
                $datetime2 = new \DateTime($fechEj);
                $tempFecha1 = "";

                $cantidadMOBRA = 0;
                if($datetime1 < $datetime2)
                {
                    //var_dump("LA FECHA INICIAL HA SIDO REDUCIDA");
                    $cantidadMOBRA = DB::Table($this->tblAux . 'mobra' . $this->valorT)
                                    ->whereBetween('fecha',[$fechaEjecucionInicial,$fechEj])
                                    ->where('id_orden',$orden_proyecto)
                                    ->count();
                }
                
                if($cantidadMOBRA > 0)
                    return response()->json("-5");

                $datetime1 = new \DateTime($fechaEjecucionFinal);
                $datetime2 = new \DateTime($fechEj2);
                $tempFecha1 = "";

                $cantidadMOBRA = 0;
                if($datetime1 > $datetime2)
                {
                    $cantidadMOBRA = DB::Table($this->tblAux . 'mobra' . $this->valorT)
                                    ->whereBetween('fecha',[$fechEj2,$fechaEjecucionFinal])
                                    ->where('id_orden',$orden_proyecto)
                                    ->count();
                }

                if($cantidadMOBRA > 0)
                    return response()->json("-6");

                DB::beginTransaction();
                DB::Table($this->tblAux . 'ordenes' . $this->valorT)
                    ->where('id_orden',$orden_proyecto)
                    ->where('id_proyecto',$proyecto)
                    ->update(
                        array(
                            'fecha_programacion' => $fechPr,
                            'fecha_ejecucion' => $fechEj,
                            'fecha_ejecucion_final' => $fechEj2,
                            'fecha_emision' => $fechEm,
                            'ing_solicitante' => $soliC,
                            'ing_soli_cam' => $soliCAM,
                            'supervisor' => $soliSup,
                            'direccion' => $dire,
                            'lv' => explode(";",$tipo)[0],
                            'lm' => explode(";",$tipo)[1],
                            'prox' => explode(";",$tipo)[2],
                            'lv_lm' => explode(";",$tipo)[3],
                            'exp' => explode(";",$tipo)[4],
                            'vyp' => explode(";",$tipo)[5],
                            'inc' => explode(";",$tipo)[6],
                            'cd' => $cd,
                            'nivel_tension' => $nt,
                            'hora_ini' => $horaI,
                            'hora_corte' => $horaC,
                            'hora_cierre' => $horaCi,
                            'hora_fin' => $horaF,
                            'observaciones' => $obser,
                            'gom_adec' => $gomA,
                            'gom_inst' => $gomI,
                            'fac_orden' => $facO,
                            'rad_orden' => $radO,
                            'proy_orden' => $proY,
                            'descargo' => $des1,
                            'descargo2' => $des2,
                            'descargo3' => $des3,
                            'descargo4' => $des4,
                            'descargo5' => $des5,
                            'descargo6' => $des6,
                            'descargo7' => $des7,
                            'x' => $x,
                            'y' => $y,
                            'supervisor_ejecutor' => $request->all()['soliSupEje'],
                            'tipo_trabajo' => $request->all()['tipo_trabajo'] 
                            ));
                
                self::saveLog("OPERA18",$orden_proyecto,"PROYECTO " . $proyecto); 

                DB::commit();
                return response()->json("2");

            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
        }
        return response()->json($fechEm);
    }

    public function consultaNodosActividadesMateriales(Request $request)
    {
        //rds_gop_ordenes_manoobra
        $opc = $request->all()["opc"];
        $db = DB::connection()->getPdo();
        if($opc == "1") //Consulta Actividades
        {
        }

        if($opc == "2") //Inserta Recurso
        {
            DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                ->insert(array(
                    array(
                        'id_proyecto' => Session::get('rds_gop_proyecto_id'),
                        'id_orden' => Session::get('rds_gop_proyecto_orden_id'),
                        'id_lider' => $request->all()["lid"],
                        'id_tipo' => $request->all()["movi"],
                        'hora_ini' => $request->all()["hor1"],
                        'hora_fin' => $request->all()["hor2"]
                        )));

            self::saveLog("OPERA20",Session::get('rds_gop_proyecto_orden_id'),"LIDER " . $request->all()["lid"]); 
                
            return response()->json("1");
            //Insertar rds_gop_ordenes_manoobra_detalle
        }

        if($opc == "3") //Consulta Actividades
        {   
            $arr = Array();
            $anio = 2017;
            if($this->tblAux == "apu_gop_")
                $anio = 2016;

            $nodosA = "";
            $wbsA = "";
            $arregloN = [];
            $arregloWBS = [];

            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];

            for ($i=0; $i < count($request->all()["nod"]) ; $i++) { 
                $exist = 0;
                $exist2 = 0;

                $wbs = DB::table($this->tblAux . "ws as tbl1")
                        ->join($this->tblAux . "ws_nodos as tbl2",'tbl1.id_ws','=','tbl2.id_ws')
                        ->where('id_nodo1',$request->all()["nod"][$i])
                        ->value('nombre_ws');


                for ($j=0; $j < count($arregloN); $j++) { 
                    if($arregloN[$j] == $request->all()["nod"][$i])
                        $exist = 1;
                }

                if($exist == 0)
                {
                    array_push($arregloN, $request->all()["nod"][$i]);
                    $nodosA .= DB::table($this->tblAux . "nodos")
                        ->where('id_nodo',$request->all()["nod"][$i])
                        ->value('nombre_nodo') . ",";
                }

                for ($j=0; $j < count($arregloWBS); $j++) { 
                    if($arregloWBS[$j] == $wbs)
                        $exist2 = 1;
                }

                if($exist2 == 0)
                {
                    array_push($arregloWBS, $wbs);
                    $wbsA .= $wbs . ",";
                }

                $cad = "EXEC sp_" . $this->tblAux . "consulta_maniobra '" . $proyecto 
                     . "','" .  $orden . "','" . $request->all()["nod"][$i] . "',"  . $anio;

                $arrD = DB::select("SET NOCOUNT ON;" . $cad);
                array_push($arr,$arrD);
            }

            DB::Table($this->tblAux . "ordenes")
                ->where('id_orden', $orden)
                ->update(
                    array(
                        'nodos_utilizados' => $nodosA,
                        'wbs_utilzadas' => $wbsA
                        ));
        }

        if($opc == "4") //Consulta Materiales
        {
            $arr = Array();
            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];

            for ($i=0; $i < count($request->all()["nod"]) ; $i++) { 

                $cad = "EXEC sp_" . $this->tblAux . "consulta_materiales '" . $proyecto 
                     . "','" .  $orden . "','" . $request->all()["nod"][$i] . "'";

                $arrD = DB::select("SET NOCOUNT ON;" . $cad);
                array_push($arr,$arrD);
            }
        }

        if($opc == "5") //Consulta Nodos Afectados por el líder
        {
            $lider = $request->all()["lid"];
            $ot = $request->all()["ot"];

            for ($i= (strlen($ot) + 2); $i < 12; $i++) { 
                $ot = "0" . $ot;
            }
            $ot = "OT" . $ot;

            $ordenT = DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                ->leftJoin($this->tblAux . 'ordenes_materiales_documentos',$this->tblAux . 'ordenes_materiales_documentos.id_lider','=',$this->tblAux . 'ordenes_manoobra_detalle.id_lider')
                ->where($this->tblAux . 'ordenes_materiales_documentos.id_orden',$ot)
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_orden',$ot)
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_estado',0)
                ->select('id_documento',$this->tblAux . 'ordenes_manoobra_detalle.id_lider')
                ->get();

            //var_dump($ordenT);
            $aux = 0;
            $aux2 = 0;
            foreach ($ordenT as $or => $val) {
                if($val->id_documento != null)
                {
                    $ordenDC = DB::Table($this->tblAux1 . 'inv_documentos')
                    ->where('id_documento',$val->id_documento)
                    ->where('id_bodega_destino',$lider)
                    ->select('id_estado')
                    ->get();

                    if(count($ordenDC) > 0)
                    {
                        $ordenDC = $ordenDC[0]->id_estado;
                            //echo $ordenDC;
                        if($ordenDC == "E1" || $ordenDC == "E2")
                            $aux++;
                    }
                    
                }else
                {
                    $aux2++;
                }
            }

            if( (count($ordenT) == $aux) && count($ordenT) > 0)
                return response()->json("<p style='color:red'>No puede diligenciar la ejecución, por que existen DC sin ser confimados.</p>");


            $cad = "SELECT tbl2.nombre_nodo,tbl2.id_nodo,
                id_documento,id_documento_cs,propietario,fecha
                FROM
                rds_gop_ordenes_materiales_documentos as tbl1 
                inner join rds_gop_nodos as tbl2 on tbl1.id_nodo = tbl2.id_nodo
                left join sis_usuarios as tbl3 on tbl3.id_usuario = tbl1.id_user
                WHERE tbl1.id_orden = '$ot'
                AND tbl1.id_estado = 0
                AND tbl1.id_lider = '$lider'
                GROUP BY tbl2.nombre_nodo,tbl2.id_nodo,
                id_documento,id_documento_cs,propietario,fecha
                UNION
                SELECT tbl2.nombre_nodo,tbl2.id_nodo,
                '' as id_documento,'' as id_documento_cs,propietario,fecha
                FROM
                rds_gop_ordenes_manoobra as tbl1 
                inner join rds_gop_nodos as tbl2 on tbl1.id_nodo = tbl2.id_nodo
                left join sis_usuarios as tbl3 on tbl3.id_usuario = tbl1.id_user
                WHERE tbl1.id_orden = '$ot'
                AND tbl1.id_estado = 0
                AND tbl1.id_personaCargo = '$lider'
                GROUP BY tbl2.nombre_nodo,tbl2.id_nodo,propietario,fecha"  ;


                $nodosAfectados = DB::select("SET NOCOUNT ON;" . $cad);


            if(count($nodosAfectados) == 0)
            {
                $nodosAfectados = DB::table($this->tblAux . 'ordenes_manoobra')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_manoobra.id_nodo')
                            ->where($this->tblAux . 'ordenes_manoobra.id_orden',$ot)
                            ->leftJoin('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux . 'ordenes_manoobra.id_user')
                            ->where($this->tblAux . 'ordenes_manoobra.id_estado',0)
                            ->where($this->tblAux . 'ordenes_manoobra.id_personaCargo',$lider)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'propietario','fecha',DB::raw("'' as id_documento"),DB::raw("'' as id_documento_cs"))
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'propietario','fecha')
                            ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                            ->get();                
            }
            $tipo = "1";

            $datosProyecto = DB::table($this->tblAux . 'ordenes')
                       ->where($this->tblAux . 'ordenes.id_orden',$ot)
                       ->get(['id_proyecto','fecha_ejecucion','nodos_utilizados','wbs_utilzadas','gom','fecha_ejecucion_final'])[0];  

            $proyecto = DB::table($this->tblAux . 'proyectos')
                        ->where($this->tblAux . 'proyectos.id_proyecto',$datosProyecto->id_proyecto)
                        ->get(['nombre','tipo_proyecto'])[0];

            $nombreProyecto = $proyecto->nombre;

            $proyectoTipo = DB::table($this->tblAux . 'tipo_proyecto')
                        ->where('id_proyecto',$proyecto->tipo_proyecto)
                        ->value('des_proyecto');


            $cad = "EXEC sp_rds_gop_consulta_personal_maniobra '" .  $ot . "'" ;

            $cuadrilla = DB::select("SET NOCOUNT ON;" . $cad);

            $fechaA = explode("-",$this->fechaShort);
            $fechaA = $fechaA[2] . "/" . $fechaA[1] . "/" . $fechaA[0];

            //Consulta LOG CUADRILLA

            $datosProyectoID = DB::table($this->tblAux . 'ordenes')
                       ->where($this->tblAux . 'ordenes.id_orden',$ot)
                       ->value('id_proyecto');  

            $proyectoID = DB::table($this->tblAux . 'proyectos')
                        ->where($this->tblAux . 'proyectos.id_proyecto',$datosProyectoID)
                        ->value('tipo_proyecto');

            $logUsuario = [];
            if($proyectoID == "T03") 
            {
                $logUsuario = DB::table($this->tblAux1 . 'log_captura_ejecucion as log')
                                ->where('id_orden',$ot)
                                ->where('log.id_log','<>','OPERA67')
                                ->join($this->tblAux . 'lista_log as logtipo','logtipo.id_log','=','log.id_log')
                                ->join('sis_usuarios as sis','sis.id_usuario','=','log.id_usuario')
                                ->select("log.fecha","log.fecha_consulta","log.descripcion","logtipo.nombre","sis.propietario")
                                ->orderBY('log.fecha','asc')
                                ->get();
            }


            


           // dd($cuadrilla);
            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tablePersonaACargo',
                array("nodos" => $nodosAfectados,"tipo" => $tipo,
                    "datos" => $datosProyecto, "pry" => $nombreProyecto,
                    "tipoPRY" => $proyectoTipo, "tipoID" => $proyecto->tipo_proyecto,
                    "fechaA" => $fechaA,
                    "cuadrilla" => $cuadrilla,
                    "log" => $logUsuario ))->render()); 
        }

        if($opc == "6") //Consulta datos Baremos y materiales
        {
            $lider = $request->all()["lid"];
            $orden = $request->all()["ot"];
            $nodo = $request->all()['nodo'];
            $dc = $request->all()['dc'];

            for ($i= (strlen($orden) + 2); $i < 12; $i++) { 
                $orden = "0" . $orden;
            }
            $orden = "OT" . $orden;

            $anio = 2017;
            if($this->tblAux == "apu_gop_")
                $anio = 2016;


            $datosProyectoID = DB::table($this->tblAux . 'ordenes')
                       ->where($this->tblAux . 'ordenes.id_orden',$orden)
                       ->value('id_proyecto');  

            $proyectoID = DB::table($this->tblAux . 'proyectos')
                        ->where($this->tblAux . 'proyectos.id_proyecto',$datosProyectoID)
                        ->value('tipo_proyecto');

            if($proyectoID == "T03") 
            {
                $fechaConsulta = (isset($request->all()["fecha_consulta"]) ? $request->all()["fecha_consulta"] : $this->fechaShort);

                $contDatosEje = DB::table($this->tblAux . 'ordenes_mobra')
                            ->where('id_orden',$orden)
                            ->where('id_origen',$lider)
                            ->where('id_nodo',$nodo)
                            ->whereBetween('fecha',[$fechaConsulta . " 00:00:00",$fechaConsulta . " 23:59:59"])
                            ->count();
            }
            else
            {
                $contDatosEje = DB::table($this->tblAux . 'ordenes_mobra')
                            ->where('id_orden',$orden)
                            ->where('id_origen',$lider)
                            ->where('id_nodo',$nodo)
                            ->count();    
            }
            

            $proyectoOrden = DB::table($this->tblAux . 'ordenes')
                        ->where('id_orden',$orden)
                        ->value('id_proyecto');

            $descargos =  DB::table($this->tblAux . 'descargo_proyecto')
                        ->where('id_proyecto',$proyectoOrden)
                        ->select('id_estado as id','id_descargo as des')
                        ->orderBy('id_descargo')
                        ->get();

            $descargosLider =  DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                            ->where('id_orden',$orden)
                            ->where('id_lider',$lider)
                            ->value('descargo');

            $descargosLiderOtros =  DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                            ->where('id_orden',$orden)
                            ->where('id_lider',$lider)
                            ->get(['descargo2','descargo3','descargo4','descargo5','descargo6','descargo7']);


            //var_dump($contDatosEje);
            if($contDatosEje  == 0)
            {
                //Consulta le ejecución Baremos
                $ejecucionB = DB::table($this->tblAux . 'ordenes_manoobra')
                    ->join($this->tblAux .'baremos',$this->tblAux .'baremos.codigo','=',
                        $this->tblAux . 'ordenes_manoobra.id_baremo')
                    ->where('id_orden',$orden)
                    ->where('id_nodo',$nodo)
                    ->where('id_personaCargo',$lider)
                    ->where('periodo',$anio )
                    ->select('cantidad_confirmada as cant',$this->tblAux . 'ordenes_manoobra.id_baremo',
                        'actividad',$this->tblAux . 'ordenes_manoobra.id_estado',DB::raw("0 as parcial"))
                    ->groupBy('cantidad_confirmada',$this->tblAux . 'ordenes_manoobra.id_baremo',
                        'actividad',$this->tblAux . 'ordenes_manoobra.id_estado',$this->tblAux .'baremos.codigo')
                    ->orderBy($this->tblAux .'baremos.codigo')
                    ->get();

                
                for ($i=0; $i < count($ejecucionB); $i++) {
                    $bare = trim(DB::Table($this->tblAux . 'baremos')   
                            ->where('codigo',$ejecucionB[$i]->id_baremo)
                            ->where('periodo',$anio )
                            ->select('id_baremo')
                            ->get()[0]->id_baremo);

                    if($proyectoID == "T03") 
                    {
                        $fechaConsulta = (isset($request->all()["fecha_consulta"]) ? $request->all()["fecha_consulta"] : $this->fechaShort);

                        $dato = DB::table($this->tblAux . 'ordenes_mobra')
                                ->where('id_nodo',$nodo)
                                ->where('id_orden',$orden)
                                ->where('id_origen',$lider)
                                ->where('id_baremo',$bare)
                                ->whereBetween('fecha',[$fechaConsulta . " 00:00:00",$fechaConsulta . " 23:59:59"])
                                ->select('cantidad_confirmada')
                                ->get();

                        $parcial = DB::table($this->tblAux . 'mobra')
                                ->where('id_nodo',$nodo)
                                ->where('id_orden',$orden)
                                ->where('id_baremo',$bare)
                                ->select(DB::raw("SUM(cantidad) as cantidad"))
                                ->get()[0]->cantidad;

                        $ejecucionB[$i]->parcial = $parcial;


                    }
                    else
                    {
                        $dato = DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                            ->where('id_nodo',$nodo)
                            ->where('id_orden',$orden)
                            ->where('id_origen',$lider)
                            ->where('id_baremo',$bare)
                            ->select('cantidad_confirmada')
                            ->get();
                    }
                    
                    if(count($dato) != 0)
                        $ejecucionB[$i]->cant = $dato[0]->cantidad_confirmada;

                    $ejecucionB[$i]->cant =  str_replace(".00", "", $ejecucionB[$i]->cant);  

                     if($ejecucionB[$i]->cant == "")
                        $ejecucionB[$i]->cant = 0;
                }    
            }
            else
            {

                if($proyectoID == "T03") 
                {
                    $fechaConsulta = (isset($request->all()["fecha_consulta"]) ? $request->all()["fecha_consulta"] : $this->fechaShort);

                    //Consulta le ejecución Baremos Michael
                    $ejecucionB = DB::table($this->tblAux . 'ordenes_mobra')
                        ->join($this->tblAux .'baremos',$this->tblAux .'baremos.id_baremo','=',
                            $this->tblAux . 'ordenes_mobra.id_baremo')
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_origen',$lider)
                        ->where('periodo',$anio )
                        ->whereBetween($this->tblAux . 'ordenes_mobra.fecha',[$fechaConsulta . " 00:00:00",$fechaConsulta . " 23:59:59"])
                        ->select('cantidad_confirmada as cant',$this->tblAux . 'baremos.codigo as id_baremo',
                            'actividad',DB::raw("'' as cantP"),DB::raw("0 as parcial"))
                        ->groupBy('cantidad_confirmada',$this->tblAux . 'baremos.codigo',$this->tblAux . 'ordenes_mobra.id_baremo',
                            'actividad',$this->tblAux .'baremos.codigo')
                        ->orderBy($this->tblAux .'baremos.codigo')
                        ->get();
                }
                else
                {
                    //Consulta le ejecución Baremos Michael
                    $ejecucionB = DB::table($this->tblAux . 'ordenes_mobra')
                        ->join($this->tblAux .'baremos',$this->tblAux .'baremos.id_baremo','=',
                            $this->tblAux . 'ordenes_mobra.id_baremo')
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_origen',$lider)
                        ->where('periodo',$anio )
                        ->select('cantidad_confirmada as cant',$this->tblAux . 'baremos.codigo as id_baremo',
                            'actividad',DB::raw("'' as cantP"),DB::raw("0 as parcial"))
                        ->groupBy('cantidad_confirmada',$this->tblAux . 'baremos.codigo',$this->tblAux . 'ordenes_mobra.id_baremo',
                            'actividad',$this->tblAux .'baremos.codigo')
                        ->orderBy($this->tblAux .'baremos.codigo')
                        ->get();
                }


                
                $datos = DB::table($this->tblAux . 'ordenes_manoobra')
                            ->join($this->tblAux .'baremos',$this->tblAux .'baremos.codigo','=',
                                $this->tblAux . 'ordenes_manoobra.id_baremo')
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_personaCargo',$lider)
                            ->where('periodo',$anio )
                            ->where('cantidad_confirmada','>',0)
                            ->select('cantidad_confirmada as cant',$this->tblAux . 'ordenes_manoobra.id_baremo',
                                'actividad',$this->tblAux . 'ordenes_manoobra.id_estado',DB::raw("'' as cantP"))
                            ->groupBy('cantidad_confirmada',$this->tblAux . 'ordenes_manoobra.id_baremo',
                                'actividad',$this->tblAux . 'ordenes_manoobra.id_estado',
                                $this->tblAux .'baremos.codigo')
                            ->orderBy($this->tblAux .'baremos.codigo')
                            ->get();
                


                //var_dump($ejecucionB);
                //dd($datos);
                $d = null;
                if(count($datos) > 0)
                {
                    for ($i=0; $i < count($datos); $i++) {
                            $exis = 0;
                            if(count($ejecucionB) > 0)
                            {
                                for ($j=0; $j < count($ejecucionB); $j++) {
                                    if(strtoUpper($datos[$i]->id_baremo) != strtoUpper($ejecucionB[$j]->id_baremo))
                                        $d = $datos[$i];
                                    else
                                        $exis = 1;
                                }
                            }
                            else
                            {
                                $d = $datos[$i];
                            }

                            if($exis == 0)
                                array_push($ejecucionB,$d);

                    }   
                }
                


                for ($i=0; $i < count($ejecucionB); $i++) 
                {
                    $bareDato = DB::table($this->tblAux . 'baremos' . $this->valorT)
                            ->where('periodo',2017)
                            ->where('codigo',$ejecucionB[$i]->id_baremo)
                            ->value('id_baremo');
                    
                    if($proyectoID == "T03") 
                    {
                        $fechaConsulta = (isset($request->all()["fecha_consulta"]) ? $request->all()["fecha_consulta"] : $this->fechaShort);

                        $dato = DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                            ->where('id_nodo',$nodo)
                            ->where('id_orden',$orden)
                            ->where('id_origen',$lider)
                            ->where('id_baremo',$bareDato)
                            ->whereBetween('fecha',[$fechaConsulta . " 00:00:00",$fechaConsulta . " 23:59:59"])
                            ->select('cantidad_confirmada')
                            ->get();
                        
                        $parcial = DB::table($this->tblAux . 'mobra')
                                ->where('id_nodo',$nodo)
                                ->where('id_orden',$orden)
                                ->where('id_baremo',$bareDato)
                                ->select(DB::raw("SUM(cantidad) as cantidad"))
                                ->get()[0]->cantidad;

                        $ejecucionB[$i]->parcial = $parcial;


                        if(count($dato) != 0)
                            $ejecucionB[$i]->cant = $dato[0]->cantidad_confirmada;

                        $dato = DB::table($this->tblAux . 'mobra' . $this->valorT)
                            ->where('id_nodo',$nodo)
                            ->where('id_orden',$orden)
                            ->where('id_origen',$lider)
                            ->whereBetween('fecha',[$fechaConsulta . " 00:00:00",$fechaConsulta . " 23:59:59"])
                            ->where('id_baremo',$bareDato)
                            ->select('cantidad')
                            ->get();
                        
                        if(count($dato) != 0)
                            $ejecucionB[$i]->cant = $dato[0]->cantidad;

                    }
                    else
                    {
                        $dato = DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                            ->where('id_nodo',$nodo)
                            ->where('id_orden',$orden)
                            ->where('id_origen',$lider)
                            ->where('id_baremo',$bareDato)
                            ->select('cantidad_confirmada')
                            ->get();
                        
                        if(count($dato) != 0)
                            $ejecucionB[$i]->cant = $dato[0]->cantidad_confirmada;

                        $dato = DB::table($this->tblAux . 'mobra' . $this->valorT)
                            ->where('id_nodo',$nodo)
                            ->where('id_orden',$orden)
                            ->where('id_origen',$lider)
                            ->where('id_baremo',$bareDato)
                            ->select('cantidad')
                            ->get();
                        
                        if(count($dato) != 0)
                            $ejecucionB[$i]->cant = $dato[0]->cantidad;
                    }

                    $dato = DB::table($this->tblAux . 'ordenes_manoobra')
                            ->join($this->tblAux .'baremos',$this->tblAux .'baremos.codigo','=',
                                $this->tblAux . 'ordenes_manoobra.id_baremo')
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_personaCargo',$lider)
                            ->where('periodo',$anio )
                            ->where($this->tblAux . 'ordenes_manoobra.id_baremo',$ejecucionB[$i]->id_baremo)
                            ->select('cantidad_confirmada as cant',$this->tblAux . 'ordenes_manoobra.id_baremo',
                                'actividad',$this->tblAux . 'ordenes_manoobra.id_estado')
                            ->groupBy('cantidad_confirmada',$this->tblAux . 'ordenes_manoobra.id_baremo',
                                'actividad',$this->tblAux . 'ordenes_manoobra.id_estado',
                                $this->tblAux .'baremos.codigo')
                            ->orderBy($this->tblAux .'baremos.codigo')
                            ->get();

                        if(count($dato) != 0)
                            $ejecucionB[$i]->cantP = $dato[0]->cant;

                        $ejecucionB[$i]->cantP =  str_replace(".00", "", $ejecucionB[$i]->cantP);  
                        $ejecucionB[$i]->cant =  str_replace(".00", "", $ejecucionB[$i]->cant);  

                         if($ejecucionB[$i]->cantP == "")
                            $ejecucionB[$i]->cantP = 0;

                        if($ejecucionB[$i]->cant == "")
                            $ejecucionB[$i]->cant = 0;
                } 
            }
            

            $materiales = [];
            $doc = null;

            $estaOrden = DB::table($this->tblAux . "ordenes")
                        ->where('id_orden',$orden)
                        ->select('id_estado')
                        ->get()[0]->id_estado;

            $preplanilla = DB::table($this->tblAux  . 'ordenes_manoobra_detalle')
                    ->where('id_orden',$orden)
                    ->where('id_lider',$lider)
                    ->value('preplanilla');

            
            if($proyectoID == "T03") 
            {
                $fechaConsulta = (isset($request->all()["fecha_consulta"]) ? $request->all()["fecha_consulta"] : $this->fechaShort);

                $documentosCS = DB::table($this->tblAux1 . "inv_documentos")
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_bodega_origen',$lider)
                            ->whereBetween('fecha',[$fechaConsulta . " 00:00:00",$fechaConsulta . " 23:59:59"])
                            ->where('id_tipo_movimiento',"T007")
                            ->where('id_bodega_destino','')
                            ->select('id_documento')
                            ->get();
            }
            else
            {
                $documentosCS = DB::table($this->tblAux1 . "inv_documentos")
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_bodega_origen',$lider)
                            ->where('id_tipo_movimiento',"T007")
                            ->where('id_bodega_destino','')
                            ->select('id_documento')
                            ->get();
            }

            //CONSULTA INFORMACIÓN NODO
            $datosNodo = DB::Table($this->tblAux . 'nodos')
                            ->where('id_nodo',$nodo)
                            ->get(['cd','direccion','punto_fisico','id_nodo','seccionador'])[0];

            //Consulta DC
            if($dc == -1)
            {

                //Retornar sin DC
                return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBaremoAdd',
                array("ejecucionB" => $ejecucionB,
                    "ejecucionM" => $materiales,
                    "estado" => $estaOrden,
                    "tipo" => "1",
                    "orden" => $orden,
                    "lider" => $lider,
                    "nodo" => $nodo,
                    "cs" => $documentosCS,
                    'nodosDatos' => $datosNodo,
                    'descargos' => $descargos,
                    'descargo2' => $descargosLiderOtros[0]->descargo2,
                    'descargo3' => $descargosLiderOtros[0]->descargo3,
                    'descargo4' => $descargosLiderOtros[0]->descargo4,
                    'descargo5' => $descargosLiderOtros[0]->descargo5,
                    'descargo6' => $descargosLiderOtros[0]->descargo6,
                    'descargo7' => $descargosLiderOtros[0]->descargo7,
                    'descargolider' => $descargosLider,
                    'tipoPRY' => $proyectoID,
                    "pre" => $preplanilla))->render()); 
            }else
            {
                $cad = "select SUM(cantidad) as cant,0 as parcial,
                    mateArti.id_articulo,nombre,codigo_sap,SUM(i_rz) as irz,SUM(r_ch) as rch,SUM(r_rz) as rrz,
                    SUM(cantidad) as cant1,SUM(reintegro) as reintegro, SUM(cantidad) as cantP,fecha_terreno
                    FROM rds_gop_ordenes_materiales_documentos as mateOrden
                    INNER JOIN rds_inv_detalle_documentos as docDeta ON mateOrden.id_documento = docDeta.id_documento
                    INNER JOIN rds_inv_maestro_articulos as mateArti ON  mateArti.id_articulo = docDeta.id_articulo
                    WHERE mateOrden.id_documento = '$dc'
                    AND docDeta.id_nodo = '$nodo'
                    AND mateOrden.id_lider = '$lider'
                    GROUP BY mateArti.id_articulo,nombre,codigo_sap,fecha_terreno
                    ORDER BY codigo_sap";

                $materiales = DB::select($cad);

                
                    $doc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                            ->where('id_orden',trim($orden))
                            ->where('id_lider',trim($lider))
                            ->where('id_nodo',trim($nodo))
                            ->where('id_documento',$dc)
                            ->where('id_tipo_documento',"T005")
                            ->select('id_documento_cs as id_documento')
                            ->get();
                
            }
              
            $arregloMate = [];
             for ($i=0; $i < count($materiales); $i++) { 

                if(count($doc) > 0 )
                {
                    if($doc[0]->id_documento != NULL)
                    {   
                        if($proyectoID == "T03") 
                        {
                            $fechaConsulta = (isset($request->all()["fecha_consulta"]) ? $request->all()["fecha_consulta"] : $this->fechaShort);
                                $mat = DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                ->where('id_documento',$doc[0]->id_documento)
                                ->where('id_articulo',$materiales[$i]->id_articulo)
                                ->whereBetween('fecha_generacion',[$fechaConsulta . " 00:00:00",$fechaConsulta . " 23:59:59"])
                                ->select('consumo','i_rz','r_ch','r_rz','id_autoreg')
                                ->get();   

                            $parcial = DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                ->where('id_documento',$doc[0]->id_documento)
                                ->where('id_articulo',$materiales[$i]->id_articulo)
                                ->select(DB::raw("SUM(consumo) as consumo"))
                                ->get()[0]->consumo; 

                            $materiales[$i]->parcial = $parcial;

                        }
                        else
                        {
                            $mat = DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                            ->where('id_documento',$doc[0]->id_documento)
                            ->where('id_articulo',$materiales[$i]->id_articulo)
                            ->select('consumo','i_rz','r_ch','r_rz','id_autoreg')
                            ->get();     
                        }
                        
                        
                        $materiales[$i]->cant = $materiales[$i]->cant - $materiales[$i]->reintegro;
                        $materiales[$i]->cant1 = $materiales[$i]->cant1 - $materiales[$i]->reintegro;

                        if(count($mat) > 0)
                        {
                            $materiales[$i]->cant1 = str_replace(".00", "", $materiales[$i]->cant);
                            $materiales[$i]->cant = str_replace(".00", "", $mat[0]->consumo);
                            $materiales[$i]->irz = str_replace(".00", "", $mat[0]->i_rz);
                            $materiales[$i]->rch = str_replace(".00", "", $mat[0]->r_ch);
                            $materiales[$i]->rrz = str_replace(".00", "", $mat[0]->r_rz);
                            
                            if($materiales[$i]->rch == "")
                                $materiales[$i]->rch = "0";

                            if($materiales[$i]->rrz == "")
                                $materiales[$i]->rrz = "0";

                            if($materiales[$i]->irz == "")
                                $materiales[$i]->irz = "0";
                            
                            if($materiales[$i]->cant1 == "")
                                $materiales[$i]->cant1 = "0";
                        }
                    }
                    else
                    {
                        $materiales[$i]->cant1 = str_replace(".00", "", $materiales[$i]->cant1);
                        $materiales[$i]->irz = "0";
                        $materiales[$i]->rch = "0";
                        $materiales[$i]->rrz = "0";       
                    }
                }
                else
                {
                    $materiales[$i]->cant1 = str_replace(".00", "", $materiales[$i]->cant1);
                    $materiales[$i]->irz = "0";
                    $materiales[$i]->rch = "0";
                    $materiales[$i]->rrz = "0";
                }
                

                $materiales[$i]->cant =str_replace(".00", "", $materiales[$i]->cant);
                $materiales[$i]->cantP =str_replace(".00", "", $materiales[$i]->cantP);

                if($materiales[$i]->cant == "")
                    $materiales[$i]->cant = 0;

                if($materiales[$i]->cant1 == "")
                    $materiales[$i]->cant1 = "0";

                if($materiales[$i]->cantP == "")
                    $materiales[$i]->cantP = "0";
            }

            if($proyectoID == "T03") 
            {
                $fechaConsulta = (isset($request->all()["fecha_consulta"]) ? $request->all()["fecha_consulta"] : $this->fechaShort);

                $documentosCS = DB::table($this->tblAux1 . "inv_documentos")
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_bodega_origen',$lider)
                            ->where('id_tipo_movimiento',"T007")
                            ->whereBetween('fecha',[$fechaConsulta . " 00:00:00",$fechaConsulta . " 23:59:59"])
                            ->where('id_bodega_destino','')
                            ->select('id_documento')
                            ->get();
            }
            else
            {
                $documentosCS = DB::table($this->tblAux1 . "inv_documentos")
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_bodega_origen',$lider)
                            ->where('id_tipo_movimiento',"T007")
                            ->where('id_bodega_destino','')
                            ->select('id_documento')
                            ->get();
            }


            

            //CONSULTA INFORMACIÓN NODO
            $datosNodo = DB::Table($this->tblAux . 'nodos')
                            ->where('id_nodo',$nodo)
                            ->get(['cd','direccion','punto_fisico','id_nodo','seccionador'])[0];

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBaremoAdd',
                array("ejecucionB" => $ejecucionB,
                "ejecucionM" => $materiales,
                "estado" => $estaOrden,
                "tipo" => "1",
                "orden" => $orden,
                "lider" => $lider,
                'nodosDatos' => $datosNodo,
                'descargos' => $descargos,
                'descargo2' => $descargosLiderOtros[0]->descargo2,
                'descargo3' => $descargosLiderOtros[0]->descargo3,
                'descargo4' => $descargosLiderOtros[0]->descargo4,
                'descargo5' => $descargosLiderOtros[0]->descargo5,
                'descargo6' => $descargosLiderOtros[0]->descargo6,
                'descargo7' => $descargosLiderOtros[0]->descargo7,
                'descargolider' => $descargosLider,
                "nodo" => $nodo,
                'tipoPRY' => $proyectoID,
                "cs" => $documentosCS,
                "pre" => $preplanilla))->render()); 
        }

        if($opc == "8") //Guarda Ejecución
        {
            $lider = $request->all()['lid'];
            $orden = $request->all()['ot'];
            $nodo = $request->all()['nodo'];
            $prepla = $request->all()['pla'];
            
            for ($i= (strlen($orden) + 2); $i < 12; $i++) { 
                $orden = "0" . $orden;
            }
            $orden = "OT" . $orden;


            $datosProyectoID = DB::table($this->tblAux . 'ordenes')
                       ->where($this->tblAux . 'ordenes.id_orden',$orden)
                       ->value('id_proyecto');  

            $proyectoID = DB::table($this->tblAux . 'proyectos')
                        ->where($this->tblAux . 'proyectos.id_proyecto',$datosProyectoID)
                        ->value('tipo_proyecto');          


            //Guarda Baremos
            if(isset($request->all()['bare'][0]['barem'][0]["bar"]))
            {
                //DB::beginTransaction();
                try
                {
                        //Save Baremos
                        $baremos = $request->all()['bare'];
                        $proyecto = DB::table($this->tblAux . 'ordenes' . $this->valorT )
                                ->where('id_orden',$orden)
                                ->select('id_proyecto')
                                ->get()[0]->id_proyecto;

                        $ws = DB::table($this->tblAux . 'nodos' . $this->valorT)
                            ->where('id_nodo',$nodo)
                            ->select('id_ws')
                            ->get()[0]->id_ws;


                        $fecha_ejeOT = DB::table($this->tblAux . 'ordenes')
                                        ->where('id_orden',$orden)
                                        ->value('fecha_ejecucion');


                        
                        $anio = 2017;
                        if($this->tblAux == "apu_gop_")
                            $anio = 2016;

                        for ($i=0; $i < count($baremos[0]['barem']); $i++) 
                        { 
                            $bare = DB::Table($this->tblAux . 'baremos')   
                                    ->where('codigo',$baremos[0]["barem"][$i]["bar"])
                                    ->where('periodo',$anio)
                                    ->select('id_baremo','precio')
                                    ->get()[0];


                            //Guarda Tabla anterior M. OBRA
                            if($proyectoID == "T03")                                    
                            {
                                $fecha = $request->all()['fecha_consulta'];
                                $conBAreMOBRA = DB::table($this->tblAux  . "ordenes_mobra")
                                            ->where('id_orden',$orden)
                                            ->where('id_baremo',$bare->id_baremo)
                                            ->where('id_origen',$lider)
                                            ->where('id_nodo',$nodo)
                                            ->whereBetween('fecha',[$fecha . " 00:00:00",$fecha . " 23:59:59"])
                                            ->count();
                            }
                            else
                            {
                                $conBAreMOBRA = DB::table($this->tblAux  . "ordenes_mobra")
                                            ->where('id_orden',$orden)
                                            ->where('id_baremo',$bare->id_baremo)
                                            ->where('id_origen',$lider)
                                            ->where('id_nodo',$nodo)
                                            ->count();
                            }

                            if($conBAreMOBRA == 0)
                            {
                                if($proyectoID == "T03")   
                                {
                                    $fecha = $request->all()['fecha_consulta'];
                                    DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                                    ->insert(array(
                                        array(
                                            'id_proyecto' => $proyecto,
                                            'id_ws' => $ws,
                                            'id_nodo' => $nodo,
                                            'id_orden' => $orden,
                                            'id_baremo' => $bare->id_baremo,
                                            'cantidad_confirmada' => $baremos[0]["barem"][$i]["can"],
                                            'precio' => $bare->precio,
                                            'id_origen' => $lider,
                                            'fecha' => $fecha
                                            )
                                        ));
                                }
                                else
                                {
                                    DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                                    ->insert(array(
                                        array(
                                            'id_proyecto' => $proyecto,
                                            'id_ws' => $ws,
                                            'id_nodo' => $nodo,
                                            'id_orden' => $orden,
                                            'id_baremo' => $bare->id_baremo,
                                            'cantidad_confirmada' => $baremos[0]["barem"][$i]["can"],
                                            'precio' => $bare->precio,
                                            'id_origen' => $lider
                                            )
                                        )); 
                                }
                                

                                self::saveLog("OPERA50",$orden,"LIDER " . $lider . " BAREMO: " . $bare->id_baremo . " CANTIDAD: " . $baremos[0]["barem"][$i]["can"] . " CREAR"); 
                            }
                            else
                            {
                                if($proyectoID == "T03")   
                                {
                                    $fecha = $request->all()['fecha_consulta'];

                                    DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                                        ->where('id_nodo',$nodo)
                                        ->where('id_orden',$orden)
                                        ->where('id_origen',$lider)
                                        ->where('id_baremo',$bare->id_baremo)
                                        ->whereBetween('fecha',[$fecha . " 00:00:00",$fecha . " 23:59:59"])
                                        ->update(array(
                                            'cantidad_confirmada' => $baremos[0]["barem"][$i]["can"],
                                            ));
                                }
                                else
                                {
                                    DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                                        ->where('id_nodo',$nodo)
                                        ->where('id_orden',$orden)
                                        ->where('id_origen',$lider)
                                        ->where('id_baremo',$bare->id_baremo)
                                        ->update(array(
                                            'cantidad_confirmada' => $baremos[0]["barem"][$i]["can"],
                                            ));
                                }
                                

                                self::saveLog("OPERA50",$orden," BAREMO: " . $bare->id_baremo . " CANTIDAD: " . $baremos[0]["barem"][$i]["can"] . " ACTUALIZAR");
                            }


                            if($proyectoID == "T03")   
                            {
                                $fecha = $request->all()['fecha_consulta'];

                                $conBAreMOBRA = DB::table($this->tblAux  . "mobra")
                                            ->where('id_orden',$orden)
                                            ->where('id_baremo',$bare->id_baremo)
                                            ->where('id_origen',$lider)
                                            ->where('id_nodo',$nodo)
                                            ->whereBetween('fecha',[$fecha . " 00:00:00",$fecha . " 23:59:59"])
                                            ->count();
                            }
                            else
                            {
                                $conBAreMOBRA = DB::table($this->tblAux  . "mobra")
                                            ->where('id_orden',$orden)
                                            ->where('id_baremo',$bare->id_baremo)
                                            ->where('id_origen',$lider)
                                            ->where('id_nodo',$nodo)
                                            ->count();
                            }
                            

                            //Guarda Tabla anterior mobra
                            if($conBAreMOBRA == 0)
                            {
                                if($proyectoID == "T03")   
                                {
                                    $fecha = $request->all()['fecha_consulta'];
                                    DB::table($this->tblAux  . "mobra")
                                    ->insert(array(
                                        array(
                                            'id_orden' => $orden,
                                            'id_origen' => $lider,
                                            'id_baremo' => $bare->id_baremo,
                                            'cantidad' => $baremos[0]["barem"][$i]["can"],
                                            'precio' => $bare->precio,
                                            'fecha' => $fecha,
                                            'id_nodo' => $nodo
                                            )
                                        ));
                                }
                                else
                                {
                                    DB::table($this->tblAux  . "mobra")
                                    ->insert(array(
                                        array(
                                            'id_orden' => $orden,
                                            'id_origen' => $lider,
                                            'id_baremo' => $bare->id_baremo,
                                            'cantidad' => $baremos[0]["barem"][$i]["can"],
                                            'precio' => $bare->precio,
                                            'fecha' => $fecha_ejeOT,
                                            'id_nodo' => $nodo
                                            )
                                        ));
                                }  
                            }
                            else
                            {
                                if($proyectoID == "T03")   
                                {
                                    $fecha = $request->all()['fecha_consulta'];

                                    DB::table($this->tblAux  . "mobra")
                                    ->where('id_orden',$orden)
                                    ->where('id_nodo',$nodo)
                                    ->where('id_baremo',$bare->id_baremo)
                                    ->where('id_origen',$lider)
                                    ->whereBetween('fecha',[$fecha . " 00:00:00",$fecha . " 23:59:59"])
                                    ->update(
                                        array(
                                            'cantidad' => $baremos[0]["barem"][$i]["can"]
                                            )
                                        );

                                }
                                else
                                {
                                    DB::table($this->tblAux  . "mobra")
                                    ->where('id_orden',$orden)
                                    ->where('id_nodo',$nodo)
                                    ->where('id_baremo',$bare->id_baremo)
                                    ->where('id_origen',$lider)
                                    ->update(
                                        array(
                                            'cantidad' => $baremos[0]["barem"][$i]["can"]
                                            )
                                        );
                                }
                            }

                            if($proyectoID == "T03")
                            {
                                $fecha = $request->all()['fecha_consulta'];
                                DB::table($this->tblAux1 . 'log_captura_ejecucion')
                                ->insert(array(
                                        array(
                                            'id_log' => "OPERA68",
                                            'id_usuario' => Session::get('user_login'),
                                            'id_orden' => $orden,
                                            'fecha_consulta' => $fecha,
                                            'descripcion' => "NODO: $nodo - LIDER: $lider - BAREMO: " . $bare->id_baremo . " - CANTIDAD: " . $baremos[0]["barem"][$i]["can"]
                                            )
                                        ));
                            }
                            else
                            {
                                DB::table($this->tblAux1 . 'log_captura_ejecucion')
                                ->insert(array(
                                        array(
                                            'id_log' => "OPERA68",
                                            'id_usuario' => Session::get('user_login'),
                                            'id_orden' => $orden,
                                            'fecha_consulta' => $fecha_ejeOT,
                                            'descripcion' => "NODO: $nodo - LIDER: $lider - BAREMO: " . $bare->id_baremo . " - CANTIDAD: " . $baremos[0]["barem"][$i]["can"]
                                            )
                                        ));
                            }
                             
                        }

                        //SAVE HORA Y USER
                        DB::table($this->tblAux .'ordenes_manoobra')
                            ->where('id_nodo',$nodo)
                            ->where('id_orden',$orden)
                            ->where('id_personaCargo',$lider)
                            ->update(
                                array(
                                    'fecha' => $this->fechaALong,
                                    'id_user' => Session::get('user_login')
                                    ));

                  //      DB::commit();
                    }
                    catch(Exception $e)
                    {
                    //    DB::rollBack();
                        return response()->json("0");
                    }
            }
            
            //Guarda Materiales
            if(isset($request->all()['mate'][0]['mate'][0]["mat"]))
            {
                //echo "MAT";
                //Save Materiales
                $materailes = $request->all()['mate'];
                $dcA = $request->all()['dc'];

                $ord = DB::table($this->tblAux . 'ordenes' . $this->valorT )
                    ->where('id_orden',$orden)
                    ->select('id_proyecto','fecha_ejecucion','gom')
                    ->get()[0];

                $proyecto = $ord->id_proyecto;
                $fechaE = explode(" ",$ord->fecha_ejecucion)[0];

                $ws = DB::table($this->tblAux . 'nodos' . $this->valorT)
                    ->where('id_nodo',$nodo)
                    ->select('id_ws')
                    ->get()[0]->id_ws;

                $doc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                        ->where('id_documento',$dcA)
                        ->where('id_nodo',$nodo)
                        ->value('id_documento_cs as id_documento');

                //dd($doc);
              
                $codCosumo = 0;
                if($doc == "" || $doc == NULL) //Generar nuevo documento
                {
                    try
                    {
                      //  DB::beginTransaction();
                        $codCosumo = self::dame_uncodigo_almacen("T007");

                        //Crear información
                        DB::table($this->tblAux1  . 'inv_documentos' . $this->valorT)
                            ->insert(array(
                                array(
                                    'id_documento' => $codCosumo,
                                    'id_tipo_movimiento' => "T007",
                                    'fecha' => $fechaE,
                                    'fecha_sistema' => $this->fechaALong,
                                    'id_bodega_origen' => $lider,
                                    'id_bodega_destino' => $lider,
                                    'observaciones' => $proyecto  . "_" . $orden . "_" . $ws . "_" . $nodo . "_" . $lider,
                                    'id_estado' => 'E3',
                                    'id_orden' => $orden,
                                    'gom' => $ord->gom,
                                    "id_usuario_edicion" => Session::get('user_login'),
                                    "id_nodo" => $nodo,
                                    "fecha_confirmacion" => $this->fechaALong,
                                    "fecha_edicion" => $this->fechaALong,
                                    "id_usuario_autoriza_despacho" => Session::get('user_login'),
                                    "fecha_autoriza_despacho" => $this->fechaALong
                                    )
                                ));
                        
                        if($proyectoID == "T03")
                        {
                            $fecha = $request->all()['fecha_consulta'];
                            DB::table($this->tblAux1 . 'log_captura_ejecucion')
                            ->insert(array(
                                    array(
                                        'id_log' => "OPERA52",
                                        'id_usuario' => Session::get('user_login'),
                                        'id_orden' => $orden,
                                        'fecha_consulta' => $fecha,
                                        'descripcion' => "GENERA A DC : $dcA EL CS : $codCosumo - LIDER: $lider - NODO: $nodo"
                                        )
                                    ));
                        }
                        else
                        {
                            DB::table($this->tblAux1 . 'log_captura_ejecucion')
                            ->insert(array(
                                    array(
                                        'id_log' => "OPERA52",
                                        'id_usuario' => Session::get('user_login'),
                                        'id_orden' => $orden,
                                        'fecha_consulta' => $this->fechaALong,
                                        'descripcion' => "GENERA A DC : $dcA EL CS : $codCosumo - LIDER: $lider - NODO: $nodo"
                                        )
                                    ));
                        }

                        self::saveLog("OPERA52",$codCosumo," ORDEN: " . $orden );

                        /*
                        for ($i=0; $i < count($materailes[0]['mate']); $i++) { 
                            DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                ->insert(array(
                                    array(
                                        'id_documento' => $codCosumo,
                                        'id_articulo' => $materailes[0]["mate"][$i]["mat"], 
                                        'consumo' => $materailes[0]["mate"][$i]["can"],
                                        'id_almacen' => '0000',
                                        'i_rz' => ($materailes[0]["mate"][$i]["rz1"] == "" ? 0 :$materailes[0]["mate"][$i]["rz1"] ),
                                        'r_ch' =>  ($materailes[0]["mate"][$i]["ch"] == "" ? 0 :$materailes[0]["mate"][$i]["ch"] ),
                                        'r_rz' =>  ($materailes[0]["mate"][$i]["rz"] == "" ? 0 :$materailes[0]["mate"][$i]["rz"] ),
                                        'id_nodo' => $nodo
                                        )
                                    ));

                            self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materailes[0]["mate"][$i]["mat"] . " CANTIDAD: " .  $materailes[0]["mate"][$i]["can"]);
                        }*/


                        DB::table($this->tblAux  . 'ordenes_materiales_documentos')
                            ->where('id_orden',$orden)
                            ->where('id_lider',$lider)
                            ->where('id_nodo',$nodo)
                            ->where('id_documento',$dcA)
                                ->update(array(
                                    'id_documento_cs' => $codCosumo,
                                    'fecha' => $this->fechaALong,
                                    'id_user' => Session::get('user_login')
                                ));


                        //DB::commit();
                    }   
                    catch(Exception $e)
                    {
                        //DB::rollBack();
                        return response()->json("0");
                    }
                }
                else
                {
                    $codCosumo = $doc;

                    DB::table($this->tblAux  . 'ordenes_materiales_documentos')
                            ->where('id_orden',$orden)
                            ->where('id_lider',$lider)
                            ->where('id_nodo',$nodo)
                            ->where('id_documento',$dcA)
                                ->update(array(
                                    'id_documento_cs' => $codCosumo,
                                    'fecha' => $this->fechaALong,
                                    'id_user' => Session::get('user_login')
                                ));
                }


                    //Actualizar información
                    for ($i=0; $i < count($materailes[0]['mate']); $i++) { 

                        if($proyectoID == "T03")
                        {
                            $fecha = $request->all()['fecha_consulta'];
                            $dbT = DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                ->where('id_articulo',$materailes[0]["mate"][$i]["mat"])
                                ->where('id_documento',$codCosumo)
                                ->where('id_nodo',$nodo)
                                ->whereBetween('fecha_generacion',[$fecha . " 00:00:00",$fecha . " 23:59:59"])
                                ->select('solicitado')
                                ->get();
                        }
                        else
                        {
                            $dbT = DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                ->where('id_articulo',$materailes[0]["mate"][$i]["mat"])
                                ->where('id_documento',$codCosumo)
                                ->where('id_nodo',$nodo)
                                ->select('solicitado')
                                ->get();
                        }
                        

                        if(count($dbT) == 0)//Insert
                        {
                            if($proyectoID == "T03")
                            {
                                $fecha = $request->all()['fecha_consulta'];
                                DB::table($this->tblAux1 . 'log_captura_ejecucion')
                                ->insert(array(
                                        array(
                                            'id_log' => "OPERA69",
                                            'id_usuario' => Session::get('user_login'),
                                            'id_orden' => $orden,
                                            'fecha_consulta' => $fecha,
                                            'descripcion' => "CS: $codCosumo - ARTICULO: " . $materailes[0]["mate"][$i]["mat"] . " - CANTIDAD: " .  $materailes[0]["mate"][$i]["can"] . " - CREAR"
                                            )
                                        ));

                                DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codCosumo,
                                            'id_articulo' => $materailes[0]["mate"][$i]["mat"], 
                                            'consumo' => $materailes[0]["mate"][$i]["can"],
                                            'cantidad' => $materailes[0]["mate"][$i]["can"],
                                            'id_almacen' => '0000',
                                            'i_rz' => ($materailes[0]["mate"][$i]["rz1"] == "" ? 0 :$materailes[0]["mate"][$i]["rz1"] ),
                                            'r_ch' =>  ($materailes[0]["mate"][$i]["ch"] == "" ? 0 :$materailes[0]["mate"][$i]["ch"] ),
                                            'r_rz' =>  ($materailes[0]["mate"][$i]["rz"] == "" ? 0 :$materailes[0]["mate"][$i]["rz"] ),
                                            'id_nodo' => $nodo,
                                            'fecha_generacion' => $fecha
                                            )
                                        ));

                            }
                            else
                            {
                                DB::table($this->tblAux1 . 'log_captura_ejecucion')
                                ->insert(array(
                                        array(
                                            'id_log' => "OPERA69",
                                            'id_usuario' => Session::get('user_login'),
                                            'id_orden' => $orden,
                                            'fecha_consulta' => $this->fechaALong,
                                            'descripcion' => "CS: $codCosumo - ARTICULO: " . $materailes[0]["mate"][$i]["mat"] . " - CANTIDAD: " .  $materailes[0]["mate"][$i]["can"] . " - CREAR"
                                            )
                                        ));

                                DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codCosumo,
                                            'id_articulo' => $materailes[0]["mate"][$i]["mat"], 
                                            'consumo' => $materailes[0]["mate"][$i]["can"],
                                            'cantidad' => $materailes[0]["mate"][$i]["can"],
                                            'id_almacen' => '0000',
                                            'i_rz' => ($materailes[0]["mate"][$i]["rz1"] == "" ? 0 :$materailes[0]["mate"][$i]["rz1"] ),
                                            'r_ch' =>  ($materailes[0]["mate"][$i]["ch"] == "" ? 0 :$materailes[0]["mate"][$i]["ch"] ),
                                            'r_rz' =>  ($materailes[0]["mate"][$i]["rz"] == "" ? 0 :$materailes[0]["mate"][$i]["rz"] ),
                                            'id_nodo' => $nodo,
                                            'fecha_generacion' => $this->fechaShort
                                            )
                                        ));

                            }

                            //self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materailes[0]["mate"][$i]["mat"] . " CANTIDAD: " .  $materailes[0]["mate"][$i]["can"] . " CREAR");
                        }
                        else //Actualiza
                        {
                            //self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materailes[0]["mate"][$i]["mat"] . " CANTIDAD: " .  $materailes[0]["mate"][$i]["can"] . " ACTUALIZA");

                            if($proyectoID == "T03")
                            {
                                $fecha = $request->all()['fecha_consulta'];
                                DB::table($this->tblAux1 . 'log_captura_ejecucion')
                                ->insert(array(
                                        array(
                                            'id_log' => "OPERA69",
                                            'id_usuario' => Session::get('user_login'),
                                            'id_orden' => $orden,
                                            'fecha_consulta' => $fecha,
                                            'descripcion' => "CS: $codCosumo - ARTICULO: " . $materailes[0]["mate"][$i]["mat"] . " - CANTIDAD: " .  $materailes[0]["mate"][$i]["can"] . " - ACTUALIZA"
                                            )
                                        ));
                               

                                DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                ->where('id_articulo',$materailes[0]["mate"][$i]["mat"])
                                ->where('id_documento',$codCosumo)
                                ->whereBetween('fecha_generacion',[$fecha . " 00:00:00",$fecha . " 23:59:59"])
                                ->where('id_nodo',$nodo)
                                ->update(
                                    array(
                                        'cantidad' => $materailes[0]["mate"][$i]["can"],
                                        'consumo' => $materailes[0]["mate"][$i]["can"],
                                        'i_rz' => ($materailes[0]["mate"][$i]["rz1"] == "" ? 0 : $materailes[0]["mate"][$i]["rz1"] ),
                                        'r_ch' =>  ($materailes[0]["mate"][$i]["ch"] == "" ? 0 : $materailes[0]["mate"][$i]["ch"] ),
                                        'r_rz' =>  ($materailes[0]["mate"][$i]["rz"] == "" ? 0 : $materailes[0]["mate"][$i]["rz"] )   
                                    ));

                            }
                            else
                            {
                                DB::table($this->tblAux1 . 'log_captura_ejecucion')
                                ->insert(array(
                                        array(
                                            'id_log' => "OPERA69",
                                            'id_usuario' => Session::get('user_login'),
                                            'id_orden' => $orden,
                                            'fecha_consulta' => $this->fechaALong,
                                            'descripcion' => "CS: $codCosumo - ARTICULO: " . $materailes[0]["mate"][$i]["mat"] . " - CANTIDAD: " .  $materailes[0]["mate"][$i]["can"] . " - ACTUALIZA"
                                            )
                                        ));

                                DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                ->where('id_articulo',$materailes[0]["mate"][$i]["mat"])
                                ->where('id_documento',$codCosumo)
                                ->where('id_nodo',$nodo)
                                ->update(
                                    array(
                                        'cantidad' => $materailes[0]["mate"][$i]["can"],
                                        'consumo' => $materailes[0]["mate"][$i]["can"],
                                        'i_rz' => ($materailes[0]["mate"][$i]["rz1"] == "" ? 0 : $materailes[0]["mate"][$i]["rz1"] ),
                                        'r_ch' =>  ($materailes[0]["mate"][$i]["ch"] == "" ? 0 : $materailes[0]["mate"][$i]["ch"] ),
                                        'r_rz' =>  ($materailes[0]["mate"][$i]["rz"] == "" ? 0 : $materailes[0]["mate"][$i]["rz"] )   
                                    ));
                            }

                        }
                    }
            }

            $fechaFac = DB::table($this->tblAux  . 'ordenes_manoobra_detalle')
                            ->where('id_orden',$orden)
                            ->where('id_lider',$lider)
                            ->value('fecha_facturacion');

            if($fechaFac == "" || $fechaFac == NULL)
            {
                DB::table($this->tblAux  . 'ordenes_manoobra_detalle')
                ->where('id_orden',$orden)
                ->where('id_lider',$lider)
                ->update(array(
                    'preplanilla' => $prepla,
                    'fecha_facturacion' => $this->fechaShort
                    ));
            }
            else
            {
                DB::table($this->tblAux  . 'ordenes_manoobra_detalle')
                ->where('id_orden',$orden)
                ->where('id_lider',$lider)
                ->update(array(
                    'preplanilla' => $prepla
                    ));
            }
            
            if($proyectoID == "T03")
            {
                $fecha = $request->all()['fecha_consulta'];
                DB::table($this->tblAux1 . 'log_captura_ejecucion')
                ->insert(array(
                        array(
                            'id_log' => "OPERA67",
                            'id_usuario' => Session::get('user_login'),
                            'id_orden' => $orden,
                            'fecha_consulta' => $fecha,
                            'descripcion' => "RDS: " . $prepla . " - LIDER : " . $lider
                            )
                        ));
            }
            else
            {
                DB::table($this->tblAux1 . 'log_captura_ejecucion')
                    ->insert(array(
                            array(
                                'id_log' => "OPERA67",
                                'id_usuario' => Session::get('user_login'),
                                'id_orden' => $orden,
                                'fecha_consulta' => $this->fechaShort,
                                'descripcion' => "RDS: " . $prepla . " - LIDER : " . $lider
                                )
                            ));
            }


            //Nodos que afecto
            $nodosAfectados = DB::table($this->tblAux . 'ordenes_materiales_documentos')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_materiales_documentos.id_nodo')
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_orden',$orden)
                            ->leftJoin('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux . 'ordenes_materiales_documentos.id_user')
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_estado',0)
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_lider',$lider)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'id_documento','id_documento_cs','propietario','fecha')
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'id_documento','id_documento_cs','propietario','fecha')
                            ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                            ->get();

            $tipo = "1";
            if(count($nodosAfectados) == 0)
            {
                $nodosAfectados = DB::table($this->tblAux . 'ordenes_manoobra')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_manoobra.id_nodo')
                            ->where($this->tblAux . 'ordenes_manoobra.id_orden',$orden)
                            ->leftJoin('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux . 'ordenes_manoobra.id_user')
                            ->where($this->tblAux . 'ordenes_manoobra.id_estado',0)
                            ->where($this->tblAux . 'ordenes_manoobra.id_personaCargo',$lider)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'propietario','fecha')
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'propietario','fecha')
                            ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                            ->get();
                $tipo = "2";
            }

            $datosProyecto = DB::table($this->tblAux . 'ordenes')
                       ->where($this->tblAux . 'ordenes.id_orden',$orden)
                       ->get(['id_proyecto','fecha_ejecucion','fecha_ejecucion_final','nodos_utilizados','wbs_utilzadas','gom'])[0];  

            $proyecto = DB::table($this->tblAux . 'proyectos')
                        ->where($this->tblAux . 'proyectos.id_proyecto',$datosProyecto->id_proyecto)
                        ->get(['nombre'])[0]->nombre;


            $cad = "EXEC sp_rds_gop_consulta_personal_maniobra '" .  $orden . "'" ;

            $cuadrilla = DB::select("SET NOCOUNT ON;" . $cad);

            $fechaA = explode("-",$request->all()['fecha_consulta']);
            $fechaA = $fechaA[2] . "/" . $fechaA[1] . "/" . $fechaA[0];

            $proyectoTipo = DB::table($this->tblAux . 'tipo_proyecto')
                        ->where('id_proyecto',$proyectoID)
                        ->value('des_proyecto');

            $logUsuario = [];
            if($proyectoID == "T03") 
            {
                $logUsuario = DB::table($this->tblAux1 . 'log_captura_ejecucion as log')
                                ->where('id_orden',$orden)
                                ->where('log.id_log','<>','OPERA67')
                                ->join($this->tblAux . 'lista_log as logtipo','logtipo.id_log','=','log.id_log')
                                ->join('sis_usuarios as sis','sis.id_usuario','=','log.id_usuario')
                                ->select("log.fecha","log.fecha_consulta","log.descripcion","logtipo.nombre","sis.propietario")
                                ->orderBY('log.fecha','asc')
                                ->get();
            }


            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tablePersonaACargo',
                array("nodos" => $nodosAfectados,"tipo" => $tipo,
                    "datos" => $datosProyecto, "pry" => $proyecto,
                    "cuadrilla" => $cuadrilla, "tipoID" => $proyectoID, "fechaA" => $fechaA,
                    "tipoPRY" => $proyectoTipo,
                    "log" => $logUsuario))->render()   );
        }

        if($opc == "9") //Consulta DC Asociados
        {
            $lider = $request->all()["lid"];
            $orden = $request->all()["ot"];
            $nodo = $request->all()["nod"];

            for ($i= (strlen($orden) + 2); $i < 12; $i++) { 
                $orden = "0" . $orden;
            }
            $orden = "OT" . $orden;

            $dcAso = DB::Table($this->tblAux . 'ordenes_materiales_documentos as ordenDoc')
                    ->join($this->tblAux1 . 'inv_documentos as Doc','Doc.id_documento','=','ordenDoc.id_documento')
                    ->where('ordenDoc.id_orden',$orden)
                    ->where('ordenDoc.id_lider',$lider)
                    ->where('ordenDoc.id_nodo',$nodo)
                    ->where('Doc.id_estado','E3')
                    ->where('ordenDoc.id_tipo_documento','T005')
                    ->select('ordenDoc.id_documento')
                    ->get();

            if(count($dcAso) == 0)
                return response()->json("0");                

            return response()->json($dcAso);
        }

        if($opc == "10") //Finalizar orden
        {
            $orden = $request->all()["ot"];

            for ($i= (strlen($orden) + 2); $i < 12; $i++) { 
                $orden = "0" . $orden;
            }
            $orden = "OT" . $orden;

            $ordenT = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                ->where('id_orden',$orden)
                ->where('id_estado',0)
                ->where('id_tipo_documento','T005')
                ->select('id_documento','id_documento_cs','id_lider')
                ->get();
            
            $aux = 0;
            $doc = "<br>";
            $baremos = "<br>";

            $lidSe = [];

            foreach ($ordenT as $or => $val) {
                if($val->id_documento_cs == NULL || $val->id_documento_cs == "" || $val->id_documento_cs == "0")
                {
                    $aux++;
                    $exist = 0;
                    for ($i=0; $i < count($lidSe); $i++) { 
                        if($lidSe[$i] == $val->id_lider)
                            $exist = 1;
                    }
                    if($exist == 0)
                    {
                        $user = DB::table('rh_personas')
                                ->where('identificacion',$val->id_lider) 
                                ->select(DB::raw("rh_personas.nombres + ' ' + rh_personas.apellidos as nombre"))
                                ->get()[0]->nombre;
                        $doc = $doc . "- " . $user . "<br>";
                        array_push($lidSe, $val->id_lider);
                    }
                }    
            }

            $ordenDetalle = DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                ->where('id_orden',$orden)
                ->where('id_estado',0)
                ->select('id_lider')
                ->get();


            $lidSe2 = [];
            foreach ($ordenDetalle as $ord => $val) {
                $ActiPro = DB::Table($this->tblAux .'ordenes_manoobra')
                        ->where('id_orden',$orden)
                        ->where('id_personaCargo',$val->id_lider)
                        ->select('id_nodo')
                        ->get();

                //var_dump("CANTIDAD EJECUTADA" . $ActiPro );
                if(count($ActiPro) > 0)
                {
                    $ActiEjecutada = DB::Table($this->tblAux .'ordenes_manoobra_detalle')
                        ->where('id_orden',$orden)
                        ->where('id_lider',$val->id_lider)
                        ->value('preplanilla');

                    //var_dump("PLANILLA" . $ActiEjecutada );

                    if($ActiEjecutada == "" || $ActiEjecutada == null || $ActiEjecutada == NULL) //No ha diligenciado las actividades programadas
                    {
                        $aux++;
                        $exist = 0;
                        for ($i=0; $i < count($lidSe2); $i++) { 
                            if($lidSe2[$i] == $val->id_lider)
                                $exist = 1;
                        }
                        if($exist == 0)
                        {
                            $user = DB::table('rh_personas')
                                    ->where('identificacion',$val->id_lider) 
                                    ->select(DB::raw("rh_personas.nombres + ' ' + rh_personas.apellidos as nombre"))
                                    ->get()[0]->nombre;
                            $baremos = $baremos . "- " . $user . "<br>";
                            array_push($lidSe2, $val->id_lider);
                        }

                        
                    }     
                }

            }

            if($aux == 0)
            {
                DB::table($this->tblAux . 'ordenes' . $this->valorT)
                    ->where('id_orden',$orden)
                    ->update(array(
                        'id_estado' => 'E4'
                        ));

                self::saveLog("OPERA34",$orden,"");
                return response()->json("1");
            }
            else
            {
                return response()->json("1#" . $doc . "#" . $baremos);
            }
        }

        if($opc == "11") //Consulta GOM
        {
            $wbs = $request->all()["wbs"];
            $gomArray = [];

            $orden = $request->all()["orden"];
            $id_proyect = $request->all()["proyecto"];


            $proyectoCartas = DB::Table($this->tblAux .  'proyectos')
                                ->whereIn('tipo_proyecto',['T01','T03'])
                                ->where('id_proyecto',$id_proyect)
                                ->count();

            if($proyectoCartas == 0)
            {
                $cantRes = DB::Table($this->tblAux . 'restriccionesProyecto')
                        ->where('id_orden',$orden)
                        ->count();

                if($cantRes == 0)
                    return response()->json("-1");    
            }
            

            for ($i=0; $i < count($wbs); $i++) { 
                $gom = DB::table($this->tblAux .'ws_gom')
                    ->where('id_ws',str_replace('"',"", $wbs[$i]))
                    ->where('id_proyecto',$id_proyect)
                    ->where('id_estado',0)
                    ->select('id_gom')
                    ->get();    
                array_push($gomArray, $gom);
            }
            return response()->json($gomArray);
        }

        if($opc == "12") //Consulta Nodos Afectados por el líder Conciliacion
        {
            $lider = $request->all()["lid"];
            $ot = $request->all()["ot"];
            
            for ($i= (strlen($ot) + 2); $i < 12; $i++) { 
                $ot = "0" . $ot;
            }
            $ot = "OT" . $ot;


            //Nodos que afecto
            $nodosAfectados = DB::table($this->tblAux . 'ordenes_materiales_documentos')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_materiales_documentos.id_nodo')
                            ->join($this->tblAux1 . 'inv_documentos' . $this->valorT,$this->tblAux1 . 'inv_documentos' . $this->valorT . '.id_documento','=',$this->tblAux . 'ordenes_materiales_documentos.id_documento')
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_orden',$ot)
                            ->leftJoin('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux . 'ordenes_materiales_documentos.id_user')
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_estado',0)
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_lider',$lider)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                $this->tblAux . 'ordenes_materiales_documentos.id_documento','id_documento_cs','propietario',$this->tblAux . 'ordenes_materiales_documentos.fecha',$this->tblAux1 . 'inv_documentos.conciliado')
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                $this->tblAux . 'ordenes_materiales_documentos.id_documento','id_documento_cs','propietario',$this->tblAux . 'ordenes_materiales_documentos.fecha',$this->tblAux1 . 'inv_documentos.conciliado')
                            ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                            ->get();

            $tipo = "1";
            if(count($nodosAfectados) == 0)
            {
                $nodosAfectados = DB::table($this->tblAux . 'ordenes_mobra')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_mobra.id_nodo')
                            ->where($this->tblAux . 'ordenes_mobra.id_orden',$ot)
                            ->leftJoin('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux . 'ordenes_mobra.id_user')
                            ->where($this->tblAux . 'ordenes_mobra.id_origen',$lider)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'propietario','fecha','conciliado')
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'propietario','fecha','conciliado')
                            ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                            ->get();
                $tipo = "2";
            }

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tablePersonaACargoConci',
                array("nodos" => $nodosAfectados,
                    "tipo" => $tipo))->render()); 
        }

        if($opc == "13") //Consulta datos Baremos y materiales Conciliacion
        {
            $lider = $request->all()["lid"];
            $orden = $request->all()["ot"];
            $nodo = $request->all()['nodo'];
            $dc = $request->all()['dc'];

            for ($i= (strlen($orden) + 2); $i < 12; $i++) { 
                $orden = "0" . $orden;
            }
            $orden = "OT" . $orden;


            $anio = 2017;
            if($this->tblAux == "apu_gop_")
                $anio = 2016;

            //Consulta le ejecución Baremos
            $ejecucionB = DB::table($this->tblAux . 'ordenes_mobra')
                ->join($this->tblAux .'baremos',$this->tblAux .'baremos.id_baremo','=',
                    $this->tblAux . 'ordenes_mobra.id_baremo')
                ->where('id_orden',$orden)
                ->where('id_nodo',$nodo)
                ->where('id_origen',$lider)
                ->where('periodo',$anio)
                ->select('cantidad_confirmada as cant','conciliado as cantC',$this->tblAux . 'baremos.codigo as id_baremo',
                    'actividad')
                ->groupBy('cantidad_confirmada',$this->tblAux . 'baremos.codigo',
                    'actividad','conciliado')
                ->get();

           //return response()->json($ejecucionB);

            for ($i=0; $i < count($ejecucionB); $i++) {
                
                if($ejecucionB[$i]->cantC != NULL && $ejecucionB[$i]->cantC != "")
                    $ejecucionB[$i]->cant = $ejecucionB[$i]->cantC;

                $ejecucionB[$i]->cant =  str_replace(".00", "", $ejecucionB[$i]->cant);  

                 if($ejecucionB[$i]->cant == "")
                    $ejecucionB[$i]->cant = 0;
            }


            $materiales = [];
            $doc = null;

            $estaOrden = DB::table($this->tblAux . "ordenes")
                        ->where('id_orden',$orden)
                        ->select('id_estado')
                        ->get()[0]->id_estado;

            $documentosCS = DB::table($this->tblAux1 . "inv_documentos")
                            ->where('id_orden',$orden)
                            ->where('id_bodega_origen',$lider)
                            ->where('id_tipo_movimiento',"T007")
                            ->select('id_documento')
                            ->get();

            $arre = [];
            for ($i=0; $i < count($documentosCS); $i++) { 
                array_push($arre,$documentosCS[$i]->id_documento);
            }

            $documentosCS1 = DB::table($this->tblAux . "ordenes_materiales_documentos as tbl1")
                            ->where('tbl1.id_orden',$orden)
                            ->where('tbl1.id_lider',$lider)
                            ->where('tbl1.id_documento_cs','<>',$arre)
                            ->select('tbl1.id_documento_cs as id_documento')
                            ->get();
                            
            //Consulta DC
            if($dc == -1)
            {
                //Retornar sin DC
                return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBaremoAdd',
                array("ejecucionB" => $ejecucionB,
                        "ejecucionM" => $materiales,
                        "estado" => $estaOrden,
                        "orden" => $orden,
                        "lider" => $lider,
                        "cs" => $documentosCS1,
                        "tipo" => "2"))->render()); 
            }else
            {

                $doc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                    ->where('id_orden',trim($orden))
                    ->where('id_lider',trim($lider))
                    ->where('id_nodo',trim($nodo))
                    ->where('id_documento',$dc)
                    ->where('id_tipo_documento',"T005")
                    ->select('id_documento_cs as id_documento')
                    ->get()[0]->id_documento;

                //echo $doc;
                $materiales = DB::table($this->tblAux . 'ordenes_materiales_documentos')
                        ->join($this->tblAux1 .'inv_detalle_documentos',$this->tblAux . 'ordenes_materiales_documentos.id_documento_cs'
                            ,'=',$this->tblAux1 .'inv_detalle_documentos.id_documento')
                        ->join($this->tblAux1 .'inv_maestro_articulos',$this->tblAux1 .'inv_maestro_articulos.id_articulo','=',
                            $this->tblAux1 . 'inv_detalle_documentos.id_articulo')
                        ->where($this->tblAux . 'ordenes_materiales_documentos.id_documento_cs',$doc)
                        ->select('conciliacion',$this->tblAux1 .'inv_detalle_documentos.consumo as cant',$this->tblAux1 . 'inv_detalle_documentos.id_articulo',
                            'nombre','codigo_sap','i_rz as irz ','r_ch as rch','r_rz as rrz',"consumo as cant1")
                        ->groupBy($this->tblAux1 .'inv_detalle_documentos.consumo',$this->tblAux1 . 'inv_detalle_documentos.id_articulo',
                            'nombre','codigo_sap','i_rz','r_ch','r_rz','conciliacion')
                        ->get();
                
                
            }
            
             //return response()->json($materiales);
             for ($i=0; $i < count($materiales); $i++) { 
                  
                if($materiales[$i]->conciliacion != NULL && $materiales[$i]->conciliacion != "")
                    $materiales[$i]->cant = $materiales[$i]->conciliacion;

                $materiales[$i]->cant1 = ($materiales[$i]->cant1 == ".00" ? "0" : $materiales[$i]->cant1);
                $materiales[$i]->cant = ($materiales[$i]->cant == ".00" ? "0" : $materiales[$i]->cant);
                $materiales[$i]->irz = ($materiales[$i]->irz == ".00" ? "0" : $materiales[$i]->irz);
                $materiales[$i]->rch = ($materiales[$i]->rch == ".00" ? "0" : $materiales[$i]->rch);
                $materiales[$i]->rrz = ($materiales[$i]->rrz == ".00" ? "0" : $materiales[$i]->rrz);

                $materiales[$i]->cant1 = str_replace(".00", "", $materiales[$i]->cant1);
                $materiales[$i]->cant = str_replace(".00", "", $materiales[$i]->cant);
                $materiales[$i]->irz = str_replace(".00", "", $materiales[$i]->irz);
                $materiales[$i]->rch = str_replace(".00", "", $materiales[$i]->rch);
                $materiales[$i]->rrz = str_replace(".00", "", $materiales[$i]->rrz);
            }

            $documentosCS = DB::table($this->tblAux1 . "inv_documentos")
                            ->where('id_orden',$orden)
                            ->where('id_bodega_origen',$lider)
                            ->where('id_tipo_movimiento',"T007")
                            ->select('id_documento')
                            ->get();

            $arre = [];
            for ($i=0; $i < count($documentosCS); $i++) { 
                array_push($arre,$documentosCS[$i]->id_documento);
            }

            $documentosCS1 = DB::table($this->tblAux . "ordenes_materiales_documentos as tbl1")
                            ->where('tbl1.id_orden',$orden)
                            ->where('tbl1.id_lider',$lider)
                            ->where('tbl1.id_documento_cs','<>',$arre)
                            ->select('tbl1.id_documento_cs as id_documento')
                            ->get();




            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableBaremoAdd',
                array("ejecucionB" => $ejecucionB,
            "ejecucionM" => $materiales,
            "estado" => $estaOrden,
            "orden" => $orden,
                        "lider" => $lider,
                        "cs" => $documentosCS1,
            "tipo" => "2"))->render()); 
        }

        if($opc == "14") //Guarda Conciliación
        {
            $lider = $request->all()['lid'];
            $orden = $request->all()['ot'];
            $nodo = $request->all()['nodo'];

            for ($i= (strlen($orden) + 2); $i < 12; $i++) { 
                $orden = "0" . $orden;
            }
            $orden = "OT" . $orden;

            //Guarda Baremos
            if(isset($request->all()['bare'][0]['barem'][0]["bar"]))
            {
                //Save Baremos
                $baremos = $request->all()['bare'];
                $proyecto = DB::table($this->tblAux . 'ordenes' . $this->valorT )
                        ->where('id_orden',$orden)
                        ->select('id_proyecto')
                        ->get()[0]->id_proyecto;

                $ws = DB::table($this->tblAux . 'nodos' . $this->valorT)
                    ->where('id_nodo',$nodo)
                    ->select('id_ws')
                    ->get()[0]->id_ws;

                
                $anio = 2017;
                if($this->tblAux == "apu_gop_")
                    $anio = 2016;

                for ($i=0; $i < count($baremos[0]['barem']); $i++) 
                { 

                    $bare = DB::Table($this->tblAux . 'baremos')   
                            ->where('codigo',$baremos[0]["barem"][$i]["bar"])
                            ->where('periodo',$anio)
                            ->select('id_baremo','precio')
                            ->get()[0];

                    DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                    ->where('id_proyecto',$proyecto)
                    ->where('id_ws',$ws)
                    ->where('id_nodo',$nodo)
                    ->where('id_orden',$orden)
                    ->where('id_baremo',$bare->id_baremo)
                    ->update(array(
                        'conciliado' => $baremos[0]["barem"][$i]["can"],
                        'fecha' => $this->fechaALong,
                        'id_user' => Session::get('user_login')
                        ));

                    self::saveLog("OPERA54",$orden,"LIDER " . $lider . " BAREMO: " . $bare->id_baremo . " CANTIDAD: " . $baremos[0]["barem"][$i]["can"] . " ACTUALIZA"); 
                
                }
            }

            //Guarda Materiales
            if(isset($request->all()['mate'][0]['mate'][0]["mat"]))
            {
                //echo "MAT";
                //Save Materiales
                $materailes = $request->all()['mate'];
                $dcA = $request->all()['dc'];

                $ord = DB::table($this->tblAux . 'ordenes' . $this->valorT )
                    ->where('id_orden',$orden)
                    ->select('id_proyecto','fecha_ejecucion')
                    ->get()[0];

                $proyecto = $ord->id_proyecto;
                $fechaE = explode(" ",$ord->fecha_ejecucion)[0];

                $ws = DB::table($this->tblAux . 'nodos' . $this->valorT)
                    ->where('id_nodo',$nodo)
                    ->select('id_ws')
                    ->get()[0]->id_ws;

                $doc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                        ->where('id_orden',trim($orden))
                        ->where('id_lider',trim($lider))
                        ->where('id_nodo',trim($nodo))
                        ->where('id_documento',$dcA)
                        ->select('id_documento_cs')
                        ->get();

                DB::table($this->tblAux1 .'inv_documentos')
                    ->where('id_tipo_movimiento',"T005")
                    ->where('id_documento',$dcA)
                    ->update(array(
                        'conciliado' => 1
                        ));
              
              
                //return response()->json($doc);
                $codCosumo = 0;
                
                    $codCosumo = $doc[0]->id_documento_cs;
                    //Actualizar información
                    for ($i=0; $i < count($materailes[0]['mate']); $i++) { 

                        DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                            ->where('id_articulo',$materailes[0]["mate"][$i]["mat"])
                            ->where('id_documento',$codCosumo)
                            ->update(
                                array(
                                    'conciliacion' => $materailes[0]["mate"][$i]["can"],
                                    'i_rz' => ($materailes[0]["mate"][$i]["rz1"] == "" ? 0 : $materailes[0]["mate"][$i]["rz1"] ),
                                    'r_ch' =>  ($materailes[0]["mate"][$i]["ch"] == "" ? 0 : $materailes[0]["mate"][$i]["ch"] ),
                                    'r_rz' =>  ($materailes[0]["mate"][$i]["rz"] == "" ? 0 : $materailes[0]["mate"][$i]["rz"] )   
                                ));
                        
                        self::saveLog("OPERA55",$codCosumo," ARTICULO: " . $materailes[0]["mate"][$i]["mat"] . " CANTIDAD: " .  $materailes[0]["mate"][$i]["can"] . " ACTUALIZA");
                    }
                
            }

            //Nodos que afecto
            $nodosAfectados = DB::table($this->tblAux . 'ordenes_materiales_documentos')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_materiales_documentos.id_nodo')
                            ->join($this->tblAux1 . 'inv_documentos' . $this->valorT,$this->tblAux1 . 'inv_documentos' . $this->valorT . '.id_documento','=',$this->tblAux . 'ordenes_materiales_documentos.id_documento')
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_orden',$orden)
                            ->leftJoin('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux . 'ordenes_materiales_documentos.id_user')
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_estado',0)
                            ->where($this->tblAux . 'ordenes_materiales_documentos.id_lider',$lider)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                $this->tblAux . 'ordenes_materiales_documentos.id_documento','id_documento_cs','propietario',$this->tblAux . 'ordenes_materiales_documentos.fecha',$this->tblAux1 . 'inv_documentos.conciliado')
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                $this->tblAux . 'ordenes_materiales_documentos.id_documento','id_documento_cs','propietario',$this->tblAux . 'ordenes_materiales_documentos.fecha',$this->tblAux1 . 'inv_documentos.conciliado')
                            ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                            ->get();

            $tipo = "1";
            if(count($nodosAfectados) == 0)
            {
                $nodosAfectados = DB::table($this->tblAux . 'ordenes_mobra')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_mobra.id_nodo')
                            ->where($this->tblAux . 'ordenes_mobra.id_orden',$orden)
                            ->leftJoin('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux . 'ordenes_mobra.id_user')
                            ->where($this->tblAux . 'ordenes_mobra.id_origen',$lider)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'propietario','fecha','conciliado')
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                'propietario','fecha','conciliado')
                            ->orderBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo','asc')
                            ->get();
                $tipo = "2";
            }

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tablePersonaACargoConci',
                array("nodos" => $nodosAfectados,
                    "tipo" => $tipo))->render()); 

        }

        if($opc == "15") //Finalizar Conciliaacion
        {
            $orden = $request->all()["ot"];
            $ordenT = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                ->where('id_orden',$orden)
                ->where('id_estado',0)
                ->where('id_tipo_documento','T005')
                ->select('id_documento_cs','id_lider')
                ->get();

            
            $doc = "<br>";
            $act = "<br>";
            $aux2 = 0;


            $lidSe2 = [];
            foreach ($ordenT as $or => $val) {
                $aux = 0;
                $artiCon = DB::table($this->tblAux1 . 'inv_detalle_documentos')
                            ->where('id_documento',$val->id_documento_cs)
                            ->select('conciliacion')
                            ->get();

                foreach ($artiCon as $art => $va) {
                    if($va->conciliacion == NULL || $va->conciliacion == "")
                    {
                        $aux2 = 1;
                        $aux = 1;
                        break;
                    } 
                }

                if($aux == 1)
                {
                    $exist = 0;
                    for ($i=0; $i < count($lidSe2); $i++) { 
                            if($lidSe2[$i] == $val->id_lider)
                                $exist = 1;
                    }
                    if($exist == 0)
                    {
                        $user = DB::table('rh_personas')
                            ->where('identificacion',$val->id_lider) 
                            ->select(DB::raw("rh_personas.nombres + ' ' + rh_personas.apellidos as nombre"))
                            ->get()[0]->nombre;
                            $doc = $doc . "- " . $user . "<br>";
                        array_push($lidSe2, $val->id_lider);
                    }

                    
                }
            }

            //Verificar actividades llenas
            $acConci = DB::table($this->tblAux .  'ordenes_mobra')
                        ->where('id_orden',$orden)
                        ->select('conciliado','id_origen')
                        ->get();

            $lidSe = [];
            foreach ($acConci as $conci => $val) {
                if($val->conciliado == NULL || $val->conciliado == "")
                {
                    $exist = 0;
                    for ($i=0; $i < count($lidSe); $i++) { 
                        if($lidSe[$i] == $val->id_origen)
                            $exist = 1;
                    }

                    if($exist == 0)
                    {
                        $user = DB::table('rh_personas')
                            ->where('identificacion',$val->id_origen) 
                            ->select(DB::raw("rh_personas.nombres + ' ' + rh_personas.apellidos as nombre"))
                            ->get()[0]->nombre;
                        $act = $act . "- " . $user . "<br>";      
                        array_push($lidSe, $val->id_origen);
                    }
                   
                }
            }

            if($aux2 == 0)
            {
                DB::table($this->tblAux . 'ordenes' . $this->valorT)
                    ->where('id_orden',$orden)
                    ->update(array(
                        'id_estado' => 'C2'
                        ));

                self::saveLog("OPERA36",$orden,"");

                return response()->json("1");
            }
            else
            {
                return response()->json("2#" . $doc . "#" . $act);
            }
        }

        if($opc == "16") //Update estado GOMS
        {
            $gom = $request->all()["gom"];
            $esta = $request->all()["esta"];

            DB::table($this->tblAux .'ws_gom')
                ->where('id_gom',$gom)
                ->update(array(
                    'id_estado' => $esta
                    ));

            self::saveLog("OPERA37",$gom,"ESTADO: $esta");

            $gom = DB::table($this->tblAux .'ws_gom')
                ->where('id_estado',0)
                ->select('id_gom','id_estado')
                ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomimport',
                array(
                "gom" => $gom
                ))->render());
        }

        if($opc == "17") //Consulta GOMS proyecto
        {
            $esta = $request->all()["esta"];

            $proyecto = $request->all()["proy"];

            $gom = DB::table($this->tblAux .'ws_gom')
                ->where($this->tblAux .'ws_gom.id_estado',$esta)
                ->where($this->tblAux .'ws_gom.id_proyecto',$proyecto)
                ->join($this->tblAux .'proyectos',$this->tblAux .'proyectos.id_proyecto','=',$this->tblAux .'ws_gom.id_proyecto')
                ->join($this->tblAux .'ws',$this->tblAux .'ws.id_ws','=',$this->tblAux .'ws_gom.id_ws')
                ->select($this->tblAux .'ws_gom.id_gom',$this->tblAux .'ws_gom.id_estado',$this->tblAux .'proyectos.nombre',
                    $this->tblAux .'ws_gom.id_proyecto',$this->tblAux .'ws.nombre_ws')
                ->get();


            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomimport',
                array(
                "gom" => $gom
                ))->render());
        }

        if($opc == "18") //Consulta exsitenci OT y grupo tecnico
        {
            $ot = $request->all()["ot"];           
            for ($i= (strlen($ot) + 2); $i < 12; $i++) { 
                $ot = "0" . $ot;
            }
            $ot = "OT" . $ot;

            $ott = DB::table($this->tblAux .'ordenes')
                ->where('id_orden',$ot)
                ->select('id_estado')
                ->get();


            if(count($ott) == 0)
                return response()->json("0");

            if($ott[0]->id_estado == "E1")
                return response()->json("-1");

            if($ott[0]->id_estado == "A1")
                return response()->json("-2");



            $per = DB::table($this->tblAux . 'ordenes_manoobra_detalle') 
                ->join('rh_personas','rh_personas.identificacion','=',$this->tblAux . 'ordenes_manoobra_detalle.id_lider')
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_orden',$ot)
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_estado',0)
                ->select($this->tblAux . 'ordenes_manoobra_detalle.id_lider',DB::raw("rh_personas.nombres + ' ' + rh_personas.apellidos as nombre"),$this->tblAux . 'ordenes_manoobra_detalle.id_tipo')
                ->get();


            return response()->json(array($per,$ott[0]->id_estado));
        }

        if($opc == "19") //Consulta datos Baremos y materiales Conciliacion
        {
            $numeroP = $request->all()["num"];
            $nombreP = $request->all()["nombre"];
            
            $pro = DB::table($this->tblAux  . 'proyectos' . $this->valorT)
                    ->where('nombre','LIKE','%' . $nombreP . '%')
                    ->where('id_proyecto','LIKE','%' . $numeroP . '%')
                    ->select('id_proyecto','nombre')
                    ->get();

            return response()->json($pro);       
        }

        if($opc == "20") // 
        {
            $proyecto = $request->all()["proy"];

            $gom = DB::table($this->tblAux .'ws_gom')
                ->where($this->tblAux .'ws_gom.id_estado',0)
                ->where($this->tblAux .'ws_gom.id_proyecto',$proyecto)
                ->join($this->tblAux .'proyectos',$this->tblAux .'proyectos.id_proyecto','=',$this->tblAux .'ws_gom.id_proyecto')
                ->join($this->tblAux .'ws',$this->tblAux .'ws.id_ws','=',$this->tblAux .'ws_gom.id_ws')
                ->select($this->tblAux .'ws_gom.id_gom',$this->tblAux .'ws_gom.id_estado',$this->tblAux .'proyectos.nombre',
                    $this->tblAux .'ws_gom.id_proyecto',$this->tblAux .'ws.nombre_ws')
                ->get();

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomimport',
                array(
                "gom" => $gom
                ))->render());
        }

        if($opc == "21") // Consulta Cantidad de ejecución WBS
        {
            $proyecto = $request->all()["proy"];

            $wbsProyecto = DB::Table($this->tblAux . 'ws')
                        ->where('id_proyecto',$proyecto)
                        ->select('id_ws')
                        ->get();

            $aux = 0;
            foreach ($wbsProyecto as $wbs => $val) {
                
                $nodosEjecutados = DB::Table($this->tblAux . 'ordenes_mobra') //Ejecutado
                        ->where('id_ws',$val->id_ws)
                        ->select('id_nodo')
                        ->get();

                $nodosLevantado = DB::Table($this->tblAux . 'detalle_mobra') //
                        ->where('id_ws',$val->id_ws)
                        ->select('id_nodo')
                        ->get();

                if((count($nodosEjecutados) >= count($nodosLevantado)) && count($nodosEjecutados) != 0)
                    $aux++;

            }

            return response()->json($aux);
        }

        if($opc == "22") //Consulta Tipo Estructura
        {
            $estruc = $request->all()["estruc"];

            $norma = DB::Table($this->tblAux . 'estructura_norma')
                        ->where('id',$estruc)
                        ->select('norma','des_norma')
                        ->get();

            return response()->json($norma);
        }


        if($opc == "23") //Consulta Relación Baremo Material
        {
            $estruc = $request->all()["estruc"];
            $norma = $request->all()["norma"];

            $baremos = DB::Table($this->tblAux . 'relacion_estructura_mobra')
                        ->join($this->tblAux . 'baremos','codigo','=','baremo')
                        ->where('id_estructura',$estruc)
                        ->where('norma',$norma)
                        ->where('periodo',2017)
                        ->select('baremo','cant_baremo','actividad')
                        ->get();

            $mate = DB::Table($this->tblAux . 'relacion_estructura_material')
                        ->join($this->tblAux1 . 'inv_maestro_articulos',$this->tblAux1 . 'inv_maestro_articulos.codigo_sap','=', $this->tblAux . 'relacion_estructura_material.codigo_sap')
                        ->join('inv_cat_unidades',$this->tblAux1 . 'inv_maestro_articulos.id_unidad' ,'=' , 'inv_cat_unidades.id_unidad')
                        ->where('id_estructura',$estruc)
                        ->where('norma',$norma)
                        ->select($this->tblAux . 'relacion_estructura_material.codigo_sap',$this->tblAux . 'relacion_estructura_material.cant_material',
                            $this->tblAux1 . 'inv_maestro_articulos.nombre','inv_cat_unidades.nombre as nombre2')
                        ->get();

            return response()->json(array($baremos,$mate));
        }

        if($opc == "24") // Filter Restricción
        {
            $estado = $request->all()['estado'];
            $fecha1 = "";
            $fecha2 = "";
            if($request->all()['fecha1'] != "")
            {
                $fecha1 = explode("/",$request->all()['fecha1']);
                $fecha1 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];   
            }

            if($request->all()['fecha2'] != "")
            {
                $fecha2 = explode("/",$request->all()['fecha2']);
                $fecha2 = $fecha2[2] . "-" . $fecha2[1] . "-" . $fecha2[0];   
            }

            $consultaRestric = [];
            if($fecha1 != "" && $fecha2 != "")
            {
                $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                        ->join($this->tblAux .'tipo_restriccion','id_tipo_restriccion','=','texto_restriccion')
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_estado',$estado)
                        ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                        ->whereBetween('fecha_limite', [$fecha1, $fecha2])
                        ->select('id_restriccion','nombre as texto_restriccion','texto_restriccion as tipo','impacto','fecha_limite',
                            'responsable','correo_responsable','evidencia_cierre','id_estado'
                            ,'fecha_cierre','restriccion_descripcion')
                        ->get();
            }
            else
            {
                $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                        ->join($this->tblAux .'tipo_restriccion','id_tipo_restriccion','=','texto_restriccion')
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_estado',$estado)
                        ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                        ->select('id_restriccion','nombre as texto_restriccion','texto_restriccion as tipo','impacto','fecha_limite',
                            'responsable','correo_responsable','evidencia_cierre','id_estado'
                            ,'fecha_cierre','restriccion_descripcion')
                        ->get();
            }
            
            $arreglo = [];
            foreach ($consultaRestric as $key => $value) {
                array_push($arreglo,
                    [$value,
                    DB::table($this->tblAux .'restricciones_correos')
                        ->where('id_restriccion',$value->id_restriccion)
                        ->select('correo')
                        ->get()]);
            }


            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableRestricciones',
                array("restric" => $arreglo,
                    'fechaR' => $this->fechaShort
            ))->render());
        }

        if($opc == "25") //Consulta proyectos
        {
            $nom = $request->all()["nom"];
            $pro = $request->all()["pro"];

            $arr = DB::table($this->tblAux . "proyectos")
                ->where('nombre','LIKE',"%" . $nom ."%")
                ->where('id_proyecto','LIKE',"%" . $pro ."%")
                ->select('nombre','id_proyecto')
                ->get();
            return response()->json($arr);
        }

        if($opc == "26") //Consulta responsable
        {
            $resp = $request->all()["resp"];

            $arr = DB::table($this->tblAux . "restriccionesProyecto")
                ->where('responsable','LIKE',"%" . $resp ."%")
                ->select('responsable')
                ->get();
        }

        if($opc == "27") //Consulta cuadrilla
        {
            $movil = $request->all()["movil"];
            $ced = $request->all()["ced"];
            $nomb = $request->all()["nomb"];

            $arr = DB::table($this->tblAux . "cuadrilla as tbl1")
                ->join('rh_personas as tbl2','tbl2.identificacion','=','tbl1.id_lider')
                ->where('id_movil','LIKE',"%" . $movil ."%")
                ->where('id_lider','LIKE',"%" . $ced ."%")
                ->where('apellidos','LIKE',"%" . $nomb ."%")
                ->select('id_movil','id_lider','apellidos','nombres')
                ->get();
        }

        if($opc == "28") //Consulta DC asociados
        {
            $lider = $request->all()["lider"];
            $ot = $request->all()["ot"];
            
            $fechaE = explode(" ",DB::table($this->tblAux .'ordenes')
                        ->where('id_orden',$ot)
                        ->value('fecha_ejecucion'))[0];

            $fechaEFinal = explode(" ",DB::table($this->tblAux .'ordenes')
                        ->where('id_orden',$ot)
                        ->value('fecha_ejecucion_final'))[0];

            if($fechaEFinal == "" || $fechaEFinal == NULL || $fechaEFinal == "NULL")
                $fechaEFinal = $fechaE;


            $dat = DB::table($this->tblAux1 .'inv_documentos as tbl1')
                        ->whereIn('tbl1.id_estado',['E6','E1','E2','E3','A1'])
                        ->where('tbl1.id_tipo_movimiento','T005')
                        ->join($this->tblAux1 .'inv_bodegas as tbl2','tbl2.id_bodega','=','tbl1.id_bodega_destino')
                        ->whereBetween('fecha',[$fechaE . " 00:00:00",$fechaEFinal . " 23:59:59"])
                        ->where('tbl1.id_bodega_destino',$lider)
                        ->select('tbl1.id_documento','tbl1.fecha','tbl1.id_bodega_destino','tbl1.observaciones','tbl1.gom','tbl2.nombre')    
                        ->get();

            $dat1 = DB::table($this->tblAux1 .'inv_documentos as tbl1')
                        ->join($this->tblAux .'ordenes_materiales_documentos as tbl3','tbl3.id_documento','=','tbl1.id_documento')
                        ->where('tbl3.id_lider',$lider)
                        ->where('tbl3.id_orden',$ot)
                        ->select('tbl3.id_documento')    
                        ->get();

            
            $arr = [];
            foreach ($dat as $key => $value) {
                if(count($dat1) == 0)
                {
                    array_push($arr,$value);
                }
                else
                {
                    $exist = 0;
                    foreach ($dat1 as $k => $v) {
                        if($value->id_documento == $v->id_documento)
                            $exist = 1;
                    }

                    if($exist == 0)
                        array_push($arr,$value);  
                }
            }
        }

        if($opc == "29") //Asigna DC a líder
        {
            
            $ot = $request->all()["ot"];
            $pry = $request->all()["pry"];

            $arrData = $request->all()["arr"];

        
            
            //En esta parte es !! Toca modificar
            /*if(DB::table($this->tblAux1 .'inv_documentos')
                            ->where('id_documento',$dc)                        
                            ->value('id_estado') == "E6")
            {*/
                try
                {

                    for ($m=0; $m < count($arrData); $m++) { 

                        $lider = $arrData[$m]["lider"];
                        $dc = $arrData[$m]["dc"];
                        $nodos = $arrData[$m]["nodo"];
                        $noUti = $arrData[$m]["nodo"];



                        //Material del Documento
                        $mateInv = DB::table($this->tblAux1 .'inv_detalle_documentos as tbl1')
                            ->where('tbl1.id_documento',$dc)
                            ->select('tbl1.id_articulo','tbl1.solicitado')    
                            ->get();

                        //Nodos
                        $nodoU = DB::table($this->tblAux .'ordenes_manoobra')
                            ->where('id_orden',$ot)
                            ->select('id_nodo')    
                            ->groupBy('id_nodo')
                            ->get();

                        $array = [];
                        foreach ($nodoU as $key => $value) {
                            array_push($array, $value->id_nodo);
                        }

                        // Existencia Material Proyecto
                        $mateP = DB::table($this->tblAux .'detalle_materiales')
                            ->whereIn('id_nodo',$array)
                            ->select('id_articulo','cantidad_replanteo','id_nodo')    
                            ->get();

                        $nodo = $nodos;
                        $data = "";
                            foreach ($mateInv as $key => $value) { //Recorro los materiales del documento
                                $existeM = 0;
                                $existeMCant = 0;
                                foreach ($mateP as $k => $v) { //Recorro los materiales del Proyecto - Orden
                                    if($value->id_articulo == $v->id_articulo)
                                    {
                                        $existeM = 1;
                                        if($value->solicitado == $v->cantidad_replanteo)
                                            $existeMCant = 1;
                                        break;
                                    }
                                }
                                
                                if($existeM == 1)  //El material existe
                                {
                                    if($existeMCant == 1) //Material con mismas unidades
                                    {
                                        $exist = DB::table($this->tblAux  . 'ordenes_materiales')
                                                ->where('id_proyecto',$pry)
                                                ->where('id_orden',$ot)
                                                ->where('id_nodo',$nodo)
                                                ->where('id_articulo',$value->id_articulo)
                                                ->where('id_persoanCargo',$lider)
                                                ->select('cantidad_confirmada')
                                                ->get();

                                        if(count($exist) > 0)
                                        {
                                            DB::table($this->tblAux  . 'ordenes_materiales')
                                            ->where('id_proyecto',$pry)
                                            ->where('id_orden',$ot)
                                            ->where('id_nodo',$nodo)
                                            ->where('id_articulo',$value->id_articulo)
                                            ->where('id_persoanCargo',$lider)
                                            ->update(
                                                array(
                                                    'cantidad_confirmada' => (intval($value->solicitado) + intval($exist[0]->cantidad_confirmada))
                                                    )
                                                );  

                                        }
                                        else
                                        {
                                            DB::table($this->tblAux  . 'ordenes_materiales')
                                            ->insert(array(
                                                array(
                                                    'id_proyecto' => $pry,
                                                    'id_orden' => $ot, 
                                                    'id_nodo' => $nodo,
                                                    'id_articulo' => $value->id_articulo,
                                                    'cantidad_confirmada' => $value->solicitado,
                                                    'id_persoanCargo' =>  $lider
                                                    )
                                                ));  
                                        }
                                        self::saveLog("OPERA27",$pry,"ARTICULO: " . $value->id_articulo . " CANTIDAD: " . $value->solicitado . " LIDER: $lider NODO $nodo");
                                    }
                                    else //Material sin las mismas unidades
                                    {
                                        $exist = DB::table($this->tblAux  . 'ordenes_materiales')
                                        ->where('id_proyecto',$pry)
                                        ->where('id_orden',$ot)
                                        ->where('id_nodo',$nodo)
                                        ->where('id_articulo',$value->id_articulo)
                                        ->where('id_persoanCargo',$lider)
                                        ->select('cantidad_confirmada')
                                        ->get();

                                        if(count($exist) > 0)
                                        {
                                            DB::table($this->tblAux  . 'ordenes_materiales')
                                            ->where('id_proyecto',$pry)
                                            ->where('id_orden',$ot)
                                            ->where('id_nodo',$nodo)
                                            ->where('id_articulo',$value->id_articulo)
                                            ->where('id_persoanCargo',$lider)
                                            ->update(
                                                array(
                                                    'cantidad_confirmada' => (intval($value->solicitado) + intval($exist[0]->cantidad_confirmada))
                                                    )
                                                );  

                                        }
                                        else
                                        {
                                            DB::table($this->tblAux  . 'ordenes_materiales')
                                            ->insert(array(
                                                array(
                                                    'id_proyecto' => $pry,
                                                    'id_orden' => $ot, 
                                                    'id_nodo' => $nodo,
                                                    'id_articulo' => $value->id_articulo,
                                                    'cantidad_confirmada' => $value->solicitado,
                                                    'id_persoanCargo' =>  $lider
                                                    )
                                                ));  
                                        }

                                        self::saveLog("OPERA27",$pry,"ARTICULO: " . $value->id_articulo . " CANTIDAD: " . $value->solicitado . " LIDER: $lider NODO $nodo - CANTIDADES DIFERENTES");
                                        $data .= "<br>Material sin las mismas cantidades : " . $value->id_articulo;
                                    }
                                }
                                else //El material no existe
                                {
                                    $exist = DB::table($this->tblAux  . 'ordenes_materiales')
                                        ->where('id_proyecto',$pry)
                                        ->where('id_orden',$ot)
                                        ->where('id_nodo',$nodo)
                                        ->where('id_articulo',$value->id_articulo)
                                        ->where('id_persoanCargo',$lider)
                                        ->select('cantidad_confirmada')
                                        ->get();

                                        if(count($exist) > 0)
                                        {
                                            DB::table($this->tblAux  . 'ordenes_materiales')
                                            ->where('id_proyecto',$pry)
                                            ->where('id_orden',$ot)
                                            ->where('id_nodo',$nodo)
                                            ->where('id_articulo',$value->id_articulo)
                                            ->where('id_persoanCargo',$lider)
                                            ->update(
                                                array(
                                                    'cantidad_confirmada' => (intval($value->solicitado) + intval($exist[0]->cantidad_confirmada))
                                                    )
                                                );  

                                        }
                                        else
                                        {
                                            DB::table($this->tblAux  . 'ordenes_materiales')
                                            ->insert(array(
                                                array(
                                                    'id_proyecto' => $pry,
                                                    'id_orden' => $ot, 
                                                    'id_nodo' => $nodo,
                                                    'id_articulo' => $value->id_articulo,
                                                    'cantidad_confirmada' => $value->solicitado,
                                                    'id_persoanCargo' =>  $lider
                                                    )
                                                ));  
                                        }

                                        self::saveLog("OPERA27",$pry,"ARTICULO: " . $value->id_articulo . " CANTIDAD: " . $value->solicitado . " LIDER: $lider NODO $nodo - NO CARGADO EN EL LEVANTAMIENTO");
                                    $data .= "Material no cargado en el levantamiento : " . $value->id_articulo . "<br>";
                                }
                            }

                        //Ingreso material
                        //Asocio DC
                        DB::table($this->tblAux  . 'ordenes_materiales_documentos')
                                    ->insert(array(
                                        array(
                                            'id_orden' => $ot, 
                                            'id_lider' =>  $lider,
                                            'id_nodo' => $nodo,
                                            'id_tipo_documento' => "T005",
                                            'id_documento' => $dc,
                                            )
                                        ));

                        $estadoDC = DB::table($this->tblAux1 .'inv_documentos')
                                    ->where('id_documento',$dc) 
                                    ->value('id_estado');

                        

                        DB::table($this->tblAux1 .'inv_detalle_documentos')
                        ->where('id_documento',$dc) 
                        ->update(
                                array(
                                    'id_nodo' => $noUti
                                    )
                                );

                        if($estadoDC == "E6")
                        {
                            DB::table($this->tblAux1 .'inv_documentos')
                            ->where('id_documento',$dc) 
                            ->update(
                                    array(
                                        'id_estado' => "E1",
                                        )
                                    );
                        }

                        DB::table($this->tblAux1 .'inv_documentos')
                            ->where('id_documento',$dc) 
                            ->update(
                                    array(
                                        'id_orden' => $ot
                                        )
                                    );

                        self::saveLog("OPERA61",$ot,"Asocia DC N° $dc  a líder $lider a nodo $nodo");
                        
                        $arr = "1" . $data;
                    }       
                }   
                catch(Exception $e)
                {
                    $arr = "0";
                }
            /*
            }
            else
            {
                $arr = "-1";
            }
            */
        }


        if($opc == "30") //Consulta porcentaje de uso de uso cuadrill
        {
            $lid = $request->all()["lid"];
            $ot = $request->all()["ot"];

            $ot = explode("/",$ot)[2] . "-" . explode("/",$ot)[1] . "-" . explode("/",$ot)[0];

            $arregloA = [];
            for ($i=0; $i < count($lid); $i++) {

                $dinero = DB::table($this->tblAux . 'ordenes_manoobra as tbl1')
                        ->join($this->tblAux . 'ordenes as tbl2','tbl1.id_orden','=','tbl2.id_orden')
                        ->join($this->tblAux . 'baremos as tbl3','tbl1.id_baremo','=','tbl3.codigo')
                        ->whereBetween('tbl2.fecha_ejecucion',[$ot . " 00:00:00",$ot . " 23:59:59"])
                        ->where('id_personaCargo',$lid[$i]["lider"])
                        ->where('tbl3.periodo',2017)
                        ->where('tbl1.id_estado',0)
                        ->select(DB::raw("SUM(tbl1.cantidad_confirmada * tbl3.precio) as dinero"))
                        ->get()[0]->dinero;

                $tiempo = DB::table($this->tblAux . 'ordenes_manoobra as tbl1')
                        ->join($this->tblAux . 'ordenes as tbl2','tbl1.id_orden','=','tbl2.id_orden')
                        ->join($this->tblAux . 'baremos as tbl3','tbl1.id_baremo','=','tbl3.codigo')
                        ->whereBetween('tbl2.fecha_ejecucion',[$ot . " 00:00:00",$ot . " 23:59:59"])
                        ->where('id_personaCargo',$lid[$i]["lider"])
                        ->where('tbl3.periodo',2017)
                        ->where('tbl1.id_estado',0)
                        ->value(DB::raw("SUM(tbl3.tiempo_estimado_minutos) as dinero"));

                $meta = DB::table($this->tblAux . 'tipo_cuadrilla')
                        ->where('nombre', $lid[$i]["tipo"])
                        ->value('meta_produccion_pesos');

                $tiempoTotal = 6 * 60;
                $por = ($tiempo * 100 / $tiempoTotal);
                array_push($arregloA,array(
                        'lider' => $lid[$i]["lider"],
                        'meta' => $meta,
                        'tiempo' => ($tiempo == null ? 0 : $tiempo),
                        'dinero' => ($dinero == null ? 0 : number_format(floatval($dinero),2)),
                        'tiempo_total' => $tiempoTotal,
                        'por' => ($por == null ? 0 : number_format(floatval($por),2))
                    ));
            }

            $arr = $arregloA;
        }

        if($opc == "31")
        {
            $lider = $request->all()["lider"];
            $orden = $request->all()["orden"];

            $arr = DB::table($this->tblAux1 . 'inv_documentos')
                    ->where('id_orden',$orden)
                    ->where('id_bodega_origen',$lider)
                    ->count();
        }

        if($opc == "32") //UPdate NODO Ejecución
        {
            $cd = $request->all()["cd"];
            $pf = $request->all()["pf"];
            $dire = $request->all()["dire"];
            $sec = $request->all()["sec"];
            $nodo = $request->all()["nodo"];

            DB::Table($this->tblAux . 'nodos')
                ->where('id_nodo',$nodo)
                ->update(
                    array(
                            'cd' => $cd,
                            'punto_fisico' => $pf,
                            'direccion' => $dire,
                            'seccionador' => $sec
                        )
                    );


            
            $orden = $request->all()["orden"];

            for ($i= (strlen($orden) + 2); $i < 12; $i++) { 
                $orden = "0" . $orden;
            }
            $orden = "OT" . $orden;

            $lider = $request->all()["lider"];

            $descargo = $request->all()["descargo"];

            DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                ->where('id_orden',$orden)
                ->where('id_lider',$lider)
                ->update(
                    array(
                            'descargo' => $descargo,
                            'descargo2' => $request->all()["descargo2"],
                            'descargo3' => $request->all()["descargo3"],
                            'descargo4' => $request->all()["descargo4"],
                            'descargo5' => $request->all()["descargo5"],
                            'descargo6' => $request->all()["descargo6"],
                            'descargo7' => $request->all()["descargo7"]
                        )
                    );
            
            $proyecto = DB::table($this->tblAux . 'nodos') 
                            ->where('id_nodo',$nodo)
                            ->value('id_proyecto');

            self::saveLog("OPERA41",$proyecto,"ACTUALIZAR NODO CD:" . $cd . " PF:" . $pf . " DIRECCIÓN:" . $dire . " SECCIONADOR:" . $sec);

            $arr = 1;
        }

        if($opc == "33") //Consulta Log Restricción
        {
            $res = $request->all()["res"];

            $arr = DB::table($this->tblAux1  . 'log_cambios')
                    ->join($this->tblAux  . 'lista_log', $this->tblAux  . 'lista_log.id_log','=',$this->tblAux1  . 'log_cambios.id_log')
                    ->join('sis_usuarios','sis_usuarios.id_usuario','=',$this->tblAux1  . 'log_cambios.id_usuario')
                    ->where($this->tblAux1  . 'log_cambios.campo_valor',$res)
                    ->select($this->tblAux1  . 'log_cambios.id',$this->tblAux  . 'lista_log.nombre',$this->tblAux1  . 'log_cambios.fecha',
                        $this->tblAux1  . 'log_cambios.campo_valor',$this->tblAux1  . 'log_cambios.descripcion',
                        'propietario')
                    ->orderBy($this->tblAux1  . 'log_cambios.fecha','desc')
                    ->get();

        }

        return response()->json($arr);
    }

    public function saveRecursosMate(Request $request)
    {
        $opc = $request->all()["opc"];

        if($opc == "1") // Asignar todos los nodos a una persona
        {
            $op = $request->all()["op"];
            $nodo =  $request->all()["nod"];
            $resp =  $request->all()["resp"];
             $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];

            $nodos = DB::table($this->tblAux . 'detalle_mobra' . $this->valorT)
                    ->where('id_proyecto',$proyecto)
                    ->where('id_nodo',$nodo)
                    ->select('id_baremo','cantidad_replanteo')
                    ->get();

            foreach ($nodos as $nod => $val) {
                $bareI = DB::table($this->tblAux . 'ordenes_manoobra')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_baremo',$val->id_baremo)
                        ->select('id_autoreg')
                        ->get();

                if($op == "1")
                {
                    if(count($bareI) == 0) //Insert
                    {
                        if($resp == "0")
                            continue;

                        DB::table($this->tblAux . 'ordenes_manoobra')
                        ->insert(array(
                            array(
                                'id_proyecto' => $proyecto,
                                'id_orden' => $orden,
                                'id_nodo' => $nodo,
                                'id_baremo' => $val->id_baremo,
                                'cantidad_confirmada' => $val->cantidad_replanteo,
                                'id_personaCargo' => $resp,
                                'id_estado' => 0,
                                'tipo_ingreso' => 0
                                )));

                        self::saveLog("OPERA23",$orden,"BAREMO: " . $val->id_baremo . " CANTIDAD: " . $val->cantidad_replanteo . " LIDER: $resp NODO $nodo");

                    }
                    else //Update
                    {
                        if($resp == "0")
                        {
                            DB::table($this->tblAux . 'ordenes_manoobra')
                            ->where('id_proyecto',$proyecto)
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_baremo',$val->id_baremo)
                            ->delete();

                            self::saveLog("OPERA25",$orden,"BAREMO: " . $val->id_baremo . " NODO $nodo");
                        }
                        else
                        {
                            DB::table($this->tblAux . 'ordenes_manoobra')
                            ->where('id_proyecto',$proyecto)
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_baremo',$val->id_baremo)
                            ->update(
                                array(
                                    'id_personaCargo' => $resp,
                                    'cantidad_confirmada' => $val->cantidad_replanteo
                                    ));

                            self::saveLog("OPERA24",$orden,"BAREMO: " . $val->id_baremo . " CANTIDAD: " . $val->cantidad_replanteo . " LIDER: $resp NODO $nodo");
                        }
                        

                    } 
                }

                if($op == "2")
                {
                    if(count($bareI) == 0) //Insert
                    {
                        if($resp == "0")
                            continue;

                        DB::table($this->tblAux . 'ordenes_manoobra')
                        ->insert(array(
                            array(
                                'id_proyecto' => $proyecto,
                                'id_orden' => $orden,
                                'id_nodo' => $nodo,
                                'id_baremo' => $val->id_baremo,
                                'cantidad_confirmada' => $val->cantidad_replanteo,
                                'id_personaCargo' => $resp,
                                'id_estado' => 0,
                                'tipo_ingreso' => 0
                                )));

                        self::saveLog("OPERA23",$orden,"BAREMO: " . $val->id_baremo . " CANTIDAD: " . $val->cantidad_replanteo . " LIDER: $resp NODO $nodo");
                    }
                }
                
            }
            return response()->json("1");
        }

        if($opc == "2") //Update Baremos
        {
            $nodo =  $request->all()["nod"];
            $resp =  $request->all()["resp"];
            $cant =  $request->all()["cant"];
            $bare =  trim($request->all()["bare"]);

            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];


            $bareI = DB::table($this->tblAux . 'ordenes_manoobra')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_baremo',$bare)
                        ->select('id_estado')
                        ->get();

                if(count($bareI) == 0) //Insert
                {
                    DB::table($this->tblAux . 'ordenes_manoobra')
                    ->insert(array(
                        array(
                            'id_proyecto' => $proyecto,
                            'id_orden' => $orden,
                            'id_nodo' => $nodo,
                            'id_baremo' => $bare,
                            'cantidad_confirmada' => $cant,
                            'id_personaCargo' => $resp
                            )));
                    self::saveLog("OPERA23",$orden,"BAREMO: " . $bare . " CANTIDAD: " . $cant . " LIDER: $resp NODO $nodo");
                }
                else //Update
                {
                    if($resp == "0")
                    {
                        DB::table($this->tblAux . 'ordenes_manoobra')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_baremo',$bare)
                        ->delete();

                        self::saveLog("OPERA25",$orden,"BAREMO: " . $bare . " NODO $nodo");
                    }
                    else
                    {

                        if(intval($bareI[0]->id_estado) == 1)
                        {
                            DB::table($this->tblAux . 'ordenes_manoobra')
                            ->where('id_proyecto',$proyecto)
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_baremo',$bare)
                            ->update(
                                array(
                                    'id_estado' => 0
                                    ));     
                        }
                        else
                        {
                            DB::table($this->tblAux . 'ordenes_manoobra')
                            ->where('id_proyecto',$proyecto)
                            ->where('id_orden',$orden)
                            ->where('id_nodo',$nodo)
                            ->where('id_baremo',$bare)
                            ->update(
                                array(
                                    'id_personaCargo' => $resp,
                                    'cantidad_confirmada' => $cant,
                                    ));     
                        }
                        

                        self::saveLog("OPERA24",$orden,"BAREMO: " . $bare . " CANTIDAD: " . $cant . " LIDER: $resp NODO $nodo");
                    }
                }
            return response()->json("1");
        }

        if($opc == "3") // Asignar todos los materiales a un NODO
        {
            $nodo =  $request->all()["nod"];
            $resp =  $request->all()["resp"];
            $op = $request->all()["op"];
            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];


            $nodos = DB::table($this->tblAux . 'detalle_materiales' . $this->valorT)
                    ->where('id_proyecto',$proyecto)
                    ->where('id_nodo',$nodo)
                    ->select('id_articulo','cantidad_replanteo')
                    ->get();

            foreach ($nodos as $nod => $val) 
            {
                $bareI = DB::table($this->tblAux . 'ordenes_materiales')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_articulo',$val->id_articulo)
                        ->select('id_autoreg')
                        ->get();

                    if($op == "1")
                    {
                        if(count($bareI) == 0) //Insert
                        {
                            if($resp == "0")
                                continue;

                            DB::table($this->tblAux . 'ordenes_materiales')
                            ->insert(array(
                                array(
                                'id_proyecto' => $proyecto,
                                'id_orden' => $orden,
                                'id_nodo' => $nodo,
                                'id_articulo' => $val->id_articulo,
                                'cantidad_confirmada' => $val->cantidad_replanteo,
                                'id_persoanCargo' => $resp
                                )));

                            self::saveLog("OPERA27",$orden,"ARTICULO: " . $val->id_articulo . " CANTIDAD: " . $val->cantidad_replanteo . " LIDER: $resp NODO $nodo");
                        }
                        else //Update
                        {
                            if($resp == "0")
                            {
                                DB::table($this->tblAux . 'ordenes_materiales')
                                ->where('id_proyecto',$proyecto)
                                ->where('id_orden',$orden)
                                ->where('id_nodo',$nodo)
                                ->where('id_articulo',$val->id_articulo)
                                ->delete();           

                                self::saveLog("OPERA29",$orden,"ARTICULO: " . $val->id_articulo . " NODO $nodo");                 
                            }
                            else
                            {
                                DB::table($this->tblAux . 'ordenes_materiales')
                                ->where('id_proyecto',$proyecto)
                                ->where('id_orden',$orden)
                                ->where('id_nodo',$nodo)
                                ->where('id_articulo',$val->id_articulo)
                                ->update(
                                    array(
                                        'id_persoanCargo' => $resp,
                                        'cantidad_confirmada' => $val->cantidad_replanteo
                                        ));
                                self::saveLog("OPERA28",$orden,"ARTICULO: " . $val->id_articulo . " CANTIDAD: " . $val->cantidad_replanteo . " LIDER: $resp NODO $nodo");
                            }
                            

                        } 
                    }

                    if($op == "2")
                    {
                        //echo (count($bareI) . "--");
                        if(count($bareI) == 0) //Insert
                        {
                            if($resp == "0")
                                continue;

                            DB::table($this->tblAux . 'ordenes_materiales')
                                ->insert(array(
                                    array(
                                        'id_proyecto' => $proyecto,
                                        'id_orden' => $orden,
                                        'id_nodo' => $nodo,
                                        'id_articulo' => $val->id_articulo,
                                        'cantidad_confirmada' => $val->cantidad_replanteo,
                                        'id_persoanCargo' => $resp
                                        )));
                            self::saveLog("OPERA27",$orden,"ARTICULO: " . $val->id_articulo . " CANTIDAD: " . $val->cantidad_replanteo . " LIDER: $resp NODO $nodo");
                        }
                }
            }
            return response()->json("1");
        }

        if($opc == "4") //UPdate Articulos
        {
            $nodo =  $request->all()["nod"];
            $resp =  $request->all()["resp"];
            $cant =  $request->all()["cant"];
            $arti =  trim($request->all()["arti"]);
             $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];


            $bareI = DB::table($this->tblAux . 'ordenes_materiales')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_articulo',$arti)
                        ->select('id_autoreg')
                        ->get();

            if(count($bareI) == 0) //Insert
                {
                    DB::table($this->tblAux . 'ordenes_materiales')
                    ->insert(array(
                        array(
                            'id_proyecto' => $proyecto,
                            'id_orden' => $orden,
                            'id_nodo' => $nodo,
                            'id_articulo' => $arti,
                            'cantidad_confirmada' => $cant,
                            'id_persoanCargo' => $resp
                            )));

                    self::saveLog("OPERA27",$orden,"ARTICULO: " . $arti . " CANTIDAD: " . $cant . " LIDER: $resp NODO $nodo");
                }
                else //Update
                {
                    if($resp == "0")
                    {
                        DB::table($this->tblAux . 'ordenes_materiales')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_articulo',$arti)
                        ->delete();

                        self::saveLog("OPERA29",$orden,"ARTICULO: " . $arti . " NODO $nodo");
                    }
                    else
                    {
                        DB::table($this->tblAux . 'ordenes_materiales')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_nodo',$nodo)
                        ->where('id_articulo',$arti)
                        ->update(
                            array(
                                'id_persoanCargo' => $resp,
                                'cantidad_confirmada' => $cant,
                                ));

                        self::saveLog("OPERA28",$orden,"ARTICULO: " . $arti . " CANTIDAD: " . $cant . " LIDER: $resp NODO $nodo");
                    }

                }
            return response()->json("1");
        }

        if($opc == "5") //Insertar Lider
        {
            $recurso = $request->all()["recurso"];
            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];

            for ($i=0; $i < count($recurso); $i++) { 

                $lid = $recurso[$i]["cod"];
                $hor1 = $recurso[$i]["hor1"];
                $hor2 = $recurso[$i]["hor2"];
                $tip = $recurso[$i]["tip"];
                $aux1 = $recurso[$i]["aux1"];
                $aux2 = $recurso[$i]["aux2"];
                $aux3 = $recurso[$i]["aux3"];
                $cond = $recurso[$i]["cond"];
                $matri = $recurso[$i]["movi"];

                $user =DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',$proyecto)
                    ->where('id_orden',$orden)
                    ->where('id_lider',$lid)
                    ->select('id_tipo')
                    ->get();

                if(count($user) == 0)
                {
                    DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                        ->insert(array(
                            array(
                                'id_proyecto' => $proyecto,
                                'id_orden' => $orden,
                                'id_lider' => $lid,
                                'hora_ini' => $hor1,
                                'hora_fin' => $hor2,
                                'id_tipo' => $tip,
                                'aux_id1' => $aux1,
                                'aux_id2' => $aux2,
                                'aux_id3' => $aux3,
                                'conductor_id' => $cond,
                                'matricula' => $matri
                                )));

                    self::saveLog("OPERA20",$orden,"LIDER: " . $lid . " HORA INI: $hor1 HORA FIN: $hor2");
                }
                else
                {
                    DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_lider',$lid)
                        ->update(
                            array(
                                'hora_ini' => $hor1,
                                'hora_fin' => $hor2,
                                'id_tipo' => $tip,
                                'aux_id1' => $aux1,
                                'aux_id2'  => $aux2,
                                'aux_id3' => $aux3,
                                'conductor_id' => $cond,
                                'matricula' => $matri,
                                'id_estado' => 0
                                ));

                    self::saveLog("OPERA21",$orden,"LIDER: " . $lid . " HORA INI: $hor1 HORA FIN: $hor2");
                }
            }
            return response()->json("1");
        }

        if($opc == "6") //Eliminar lider del registro
        {
            $lider = $request->all()["lid"];
            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];


            $cantidadEjecutado = DB::table($this->tblAux . 'mobra')
                    ->where('id_orden',$orden)
                    ->where('id_origen',$lider)
                    ->count();

            if($cantidadEjecutado > 0)
                return response()->json("-3");


            $ordenEsta = DB::table($this->tblAux .'ordenes' . $this->valorT)
                            ->where('id_proyecto',$proyecto)
                            ->where('id_orden',$orden)
                            ->select('id_estado')
                            ->get()[0]->id_estado;

            try
            {
                DB::beginTransaction();

                if($ordenEsta == "E1")
                {
                    //Cambio estado de todos las tablas el estado
                    DB::table($this->tblAux .'ordenes_manoobra')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_personaCargo',$lider)
                        ->update(array(
                            'id_estado' => 1
                            ));
                    
                    //Actualiza ManoObra
                    DB::table($this->tblAux .'ordenes_manoobra_detalle')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_lider',$lider)
                        ->update(array(
                            'id_estado' => 1,
                            'aux_id1' => '',
                            'aux_id2' => '',
                            'aux_id3' => '',
                            'conductor_id' => '',
                            'matricula' => ''
                            ));

                    self::saveLog("OPERA22",$orden,"LIDER: " . $lider . "");

                    //Actualiza ManoObra Detalle
                    DB::table($this->tblAux .'ordenes_materiales')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->where('id_persoanCargo',$lider)
                        ->update(array(
                            'id_estado' => 1
                            ));
                }
                else
                {
                    if($ordenEsta == "E2")
                    {
                        $dc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                        ->join($this->tblAux1  . 'inv_documentos' . $this->valorT,$this->tblAux1  . 'inv_documentos' . $this->valorT . ".id_documento",'=',$this->tblAux . 'ordenes_materiales_documentos.id_documento')
                        ->join('inv_cat_estado_documentos','inv_cat_estado_documentos.id_estado','=',$this->tblAux1  . 'inv_documentos' . $this->valorT . ".id_estado")
                        ->where($this->tblAux . 'ordenes_materiales_documentos.id_orden',$orden)
                        ->where($this->tblAux . 'ordenes_materiales_documentos.id_tipo_documento','T005')
                        ->where($this->tblAux . 'ordenes_materiales_documentos.id_lider',$lider)
                        ->select($this->tblAux . 'ordenes_materiales_documentos.id_documento',
                            'inv_cat_estado_documentos.id_estado')
                        ->get();

                        $aux = 0;
                        foreach ($dc as $documentos => $val) {
                            if($val->id_estado == "E3")
                                $aux++;
                        }

                        if($aux == 0)
                        {
                            //Actualiza relaciones Documentos
                            $dcCant = DB::table($this->tblAux .'ordenes_materiales_documentos')
                                    ->where('id_orden',$orden)
                                    ->where('id_lider',$lider)
                                    ->where('id_tipo_documento','T005')
                                    ->update(array(
                                        'id_estado' => 1
                                        ));

                            //Actualiza Documentos
                            /*foreach ($dc as $documentos => $val) {
                                DB::table($this->tblAux1  . 'inv_documentos' . $this->valorT)
                                        ->where('id_orden',$orden)
                                        ->where('id_tipo_movimiento','T005')
                                        ->where('id_documento',$val->id_documento)
                                        ->update(array(
                                            'id_estado' => "A1"
                                            ));

                                self::saveLog("OPERA56",$val->id_documento,"");
                            }*/

                            //Cambio estado de todos las tablas el estado
                            DB::table($this->tblAux .'ordenes_manoobra')
                                ->where('id_proyecto',$proyecto)
                                ->where('id_orden',$orden)
                                ->where('id_personaCargo',$lider)
                                ->update(array(
                                    'id_estado' => 1
                                    ));
                            
                            //Actualiza ManoObra
                            DB::table($this->tblAux .'ordenes_manoobra_detalle')
                                ->where('id_proyecto',$proyecto)
                                ->where('id_orden',$orden)
                                ->where('id_lider',$lider)
                                ->update(array(
                                    'id_estado' => 1,
                                    'aux_id1' => '',
                                    'aux_id2' => '',
                                    'aux_id3' => '',
                                    'conductor_id' => '',
                                    'matricula' => ''
                                    ));

                            //Actualiza Materiales
                            DB::table($this->tblAux .'ordenes_materiales')
                                ->where('id_proyecto',$proyecto)
                                ->where('id_orden',$orden)
                                ->where('id_persoanCargo',$lider)
                                ->update(array(
                                    'id_estado' => 1
                                    ));
                        }
                        else
                        {
                            return response()->json("-1");
                        }
                    } 
                    else
                    {
                        return response()->json("-2");
                    }
                }

                DB::commit();
                return response()->json("1");
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
        }

        if($opc == "7") //Actualizar Auxiliar o matricula
        {
            $cod = $request->all()["cod"];
            $lid = $request->all()["lid"];
            $tip = $request->all()["tip"];

            if($tip == "1") // Aux 1
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'aux_id1' => $cod
                        ));

                self::saveLog("OPERA57",Session::get('rds_gop_proyecto_orden_id'),"AXILIAR 1: $cod");
            }

            if($tip == "2") // Aux 2
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'aux_id' . $this->valorT => $cod
                        ));
                self::saveLog("OPERA57",Session::get('rds_gop_proyecto_orden_id'),"AXILIAR 2: $cod");
            }

            if($tip == "3") // Aux 3
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'aux_id3' => $cod
                        ));
                self::saveLog("OPERA57",Session::get('rds_gop_proyecto_orden_id'),"AXILIAR 3: $cod");
            }

            if($tip == "4") // Conductor
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'conductor_id' => $cod
                        ));
                self::saveLog("OPERA57",Session::get('rds_gop_proyecto_orden_id'),"CONDUCTOR: $cod");
            }

            if($tip == "5") // Matricula
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'matricula' => $cod
                        ));
                self::saveLog("OPERA57",Session::get('rds_gop_proyecto_orden_id'),"VEHICULO: $cod");
            }
            return response()->json("1");
        }

        if($opc == "8") //Delete Aux o matricula
        {
            $lid = $request->all()["lid"];
            $tip = $request->all()["tip"];

            if($tip == "1") // Aux 1
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'aux_id1' => null
                        ));

                self::saveLog("OPERA58",Session::get('rds_gop_proyecto_orden_id'),"CANCELO AUXILIAR 1  LIDER: $lid");
            }

            if($tip == "2") // Aux 2
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'aux_id' . $this->valorT => null
                        ));

                self::saveLog("OPERA58",Session::get('rds_gop_proyecto_orden_id'),"CANCELO AUXILIAR 2  LIDER: $lid");
            }

            if($tip == "3") // Aux 3
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'aux_id3' => null
                        ));

                self::saveLog("OPERA58",Session::get('rds_gop_proyecto_orden_id'),"CANCELO AUXILIAR 3  LIDER: $lid");
            }

            if($tip == "4") // Conductor
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'conductor_id' => null
                        ));

                self::saveLog("OPERA58",Session::get('rds_gop_proyecto_orden_id'),"CANCELO CONDUCTOR  LIDER: $lid");
            }

            if($tip == "5") // Matricula
            {
                DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                    ->where('id_lider',$lid)
                    ->update(array(
                        'matricula' => null
                        ));

                self::saveLog("OPERA58",Session::get('rds_gop_proyecto_orden_id'),"CANCELO VEHICULO  LIDER: $lid");
            }
            
            return response()->json("1");
        }

        if($opc == "9") //Save baremos nuevos
        {
            $nodo = $request->all()["nodo"];
            $bare = $request->all()["bare"];
            $cant = $request->all()["cant"];
            $cargo = $request->all()["cargo"];
            
            $orde = $request->all()["ord"];
            $proyecto = $request->all()["pry"];

            if(isset($request->all()["orden"]))
            {
                $orde = $request->all()["orden"];
                for ($i= (strlen($orde) + 2); $i < 12; $i++) { 
                    $orde = "0" . $orde;
                }
                $orde = "OT" . $orde;

                $proyecto  = DB::Table($this->tblAux .'ordenes')
                                 ->where('id_orden',$orde)
                                 ->value('id_proyecto');
            }
            
            $ordenEsta = DB::table($this->tblAux .'ordenes' . $this->valorT)
                        ->where('id_orden',$orde)
                        ->select('id_estado')
                        ->get()[0]->id_estado;


             $ws = DB::table($this->tblAux .'ws_nodos' . $this->valorT)
                    ->where('id_nodo1',$nodo)
                    ->select('id_ws')
                    ->get()[0]->id_ws;
             

            if($ordenEsta == "E1") //Generada
            {   
                $consulta = DB::Table($this->tblAux . 'detalle_mobra' . $this->valorT)
                        ->where('id_proyecto',$proyecto)
                        ->where('id_nodo',$nodo)
                        ->where('id_baremo',$bare)
                        ->select('cantidad_replanteo')
                        ->get();

                if(count($consulta) == 0) //Todavia no lo tengo ingresado
                {
                    DB::table($this->tblAux . 'detalle_mobra' . $this->valorT)
                        ->insert(array(
                            array(
                                'id_proyecto' => $proyecto,
                                'id_nodo' => $nodo,
                                'id_baremo' => $bare,
                                'cantidad_replanteo' => 0,
                                'id_ws' => $ws,
                                'tipo_ingreso' => 1
                                )));

                    DB::table($this->tblAux . 'ordenes_manoobra')
                            ->insert(array(
                                array(
                                    'id_proyecto' => $proyecto,
                                    'id_orden' => $orde,
                                    'id_nodo' => $nodo,
                                    'id_baremo' => $bare,
                                    'id_personaCargo' => $cargo,
                                    'cantidad_confirmada' => $cant,
                                    'tipo_ingreso' => 1
                                    )));

                    self::saveLog("OPERA30",$orde,"BAREMO: $bare CANTIDAD: $cant NODO:$nodo");

                    return response()->json("1");
                }   
                else
                {
                    return response()->json("0");
                    //Actualizar la cantidad Levantamiento, y la cantidad de programación
                }
            }
            else
            {
                //Cambio baremo Michael
                if($ordenEsta == "E2" || $ordenEsta == "E4") //Programada
                {
                    $consulta = DB::Table($this->tblAux . 'ordenes_manoobra')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orde)
                        ->where('id_nodo',$nodo)
                        ->where('id_personaCargo',$cargo)
                        ->where('id_baremo',$bare)
                        ->where('tipo_ingreso',1)
                        ->select('cantidad_confirmada')
                        ->get();

                    if(count($consulta) == 0)
                    {
                        DB::table($this->tblAux . 'detalle_mobra' . $this->valorT)
                        ->insert(array(
                            array(
                                'id_proyecto' => $proyecto,
                                'id_nodo' => $nodo,
                                'id_baremo' => $bare,
                                'cantidad_replanteo' => 0,
                                'id_ws' => $ws,
                                'tipo_ingreso' => 1
                                )));

                        DB::table($this->tblAux . 'ordenes_manoobra')
                            ->insert(array(
                                array(
                                    'id_proyecto' => $proyecto,
                                    'id_orden' => $orde,
                                    'id_nodo' => $nodo,
                                    'id_baremo' => $bare,
                                    'id_personaCargo' => $cargo,
                                    'cantidad_confirmada' => $cant,
                                    'tipo_ingreso' => 1
                                    )));

                        $precioBaremo = DB::table($this->tblAux .'baremos')
                                ->where('codigo',$bare)
                                ->where('periodo',2017)
                                ->value('precio');

                        $Baremo = DB::table($this->tblAux .'baremos')
                                ->where('codigo',$bare)
                                ->where('periodo',2017)
                                ->value('id_baremo');


                        DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                                ->insert(array(
                                    array(
                                        'id_proyecto' => $proyecto,
                                        'id_ws' => $ws,
                                        'id_nodo' => $nodo,
                                        'id_orden' => $orde,
                                        'id_baremo' => $Baremo,
                                        'cantidad_confirmada' => $cant,
                                        'precio' => $precioBaremo,
                                        'id_origen' => $cargo
                                        )
                                    ));

                        self::saveLog("OPERA50",$orde,"LIDER " . $cargo . " BAREMO: " . $Baremo . " CANTIDAD: " . $cant . " CREAR"); 
                        
                            //Guarda Tabla anterior mobra
                        DB::table($this->tblAux  . "mobra")
                            ->insert(array(
                                array(
                                    'id_orden' => $orde,
                                    'id_origen' => $cargo,
                                    'id_baremo' => $Baremo,
                                    'cantidad' => $cant,
                                    'precio' => $precioBaremo,
                                    'fecha' => $this->fechaALong,
                                    'id_nodo' => $nodo
                                    )
                                ));

                        return response()->json("3");
                    }   
                    else
                    {
                        return response()->json("0");
                    }
                    
                    //Actualizar la cantidad Levantamiento, y la cantidad de programación
                
                }
                else //No se puede agregar
                {
                    return response()->json("-1");
                }
            }
        }

        if($opc == "10") //Save materiales nuevos
        {
            $nodo = $request->all()["nodo"];
            $mate = $request->all()["mate"];
            $cant = $request->all()["cant"];
            $cargo = $request->all()["cargo"];


            $ordenEsta = DB::table($this->tblAux .'ordenes' . $this->valorT)
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                        ->select('id_estado')
                        ->get()[0]->id_estado;


             $ws = DB::table($this->tblAux .'ws_nodos' . $this->valorT)
                    ->where('id_nodo1',$nodo)
                    ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                    ->select('id_ws')
                    ->get()[0]->id_ws;
             

            if($ordenEsta == "E1") //Generada
            {   
                //Consulta ID ARTICULO
                $arti = DB::Table($this->tblAux1 . 'inv_maestro_articulos')
                    ->where('codigo_sap',$mate)
                    ->select('id_articulo')
                    ->get()[0]->id_articulo;


                $consulta = DB::Table($this->tblAux . 'detalle_materiales' . $this->valorT)
                        ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                        ->where('id_nodo',$nodo)
                        ->where('id_articulo',$arti)
                        ->select('cantidad_replanteo')
                        ->get();

                if(count($consulta) == 0) //Todavia no lo tengo ingresado
                {
                    DB::table($this->tblAux . 'detalle_materiales' . $this->valorT)
                        ->insert(array(
                            array(
                                'id_proyecto' => Session::get('rds_gop_proyecto_id'),
                                'id_nodo' => $nodo,
                                'id_articulo' => $arti,
                                'cantidad_replanteo' => 0,
                                'id_ws' => $ws,
                                'tipo_ingreso' => 1
                                )));

                   

                    DB::table($this->tblAux . 'ordenes_materiales')
                            ->insert(array(
                                array(
                                    'id_proyecto' => Session::get('rds_gop_proyecto_id'),
                                    'id_orden' => Session::get('rds_gop_proyecto_orden_id'),
                                    'id_nodo' => $nodo,
                                    'id_articulo' => $arti,
                                    'id_persoanCargo' => $cargo,
                                    'cantidad_confirmada' => $cant,
                                    'tipo_ingreso' => 1
                                    )));

                    self::saveLog("OPERA31",Session::get('rds_gop_proyecto_orden_id'),"ARTICULO: $arti CANTIDAD: $cant NODO: $nodo");

                    return response()->json("1");
                }   
                else
                {
                    return response()->json("2");
                    //Actualizar la cantidad Levantamiento, y la cantidad de programación
                }
            }
            else
            {
                if($ordenEsta == "E2") //Programada
                {

                    try
                    {
                          
                        //Consulta ID ARTICULO
                        $arti = DB::Table($this->tblAux1 . 'inv_maestro_articulos')
                            ->where('codigo_sap',$mate)
                            ->select('id_articulo')
                            ->get()[0]->id_articulo;

                        //Consultar DC existentes
                        $dcTiene = DB::Table($this->tblAux .'ordenes_materiales_documentos')
                                ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                                ->where('id_estado',0)
                                ->where('id_nodo',$nodo)
                                ->where('id_lider',$cargo)
                                ->select('id_documento')
                                ->get();

                        $docAux = "0";
                        foreach ($dcTiene as $dc => $val) {
                            $docuEstado = DB::table($this->tblAux1 .'inv_documentos')
                                        ->where('id_documento',$val->id_documento)
                                        ->select('id_estado')
                                        ->get()[0]->id_estado;

                            //Consulta si hay un documento abierto
                            if($docuEstado == "E1")
                            {
                                $docAux = $val->id_documento;
                                break;
                            }
                        }

                        //var_dump($docAux);
                        DB::beginTransaction();

                        $docCons = "0";
                        //Generar nuevo documento
                        if($docAux == "0")
                        {

                            //Solicitamos documento nuevo
                            $docCons = self::dame_uncodigo_almacen("T005");

                            $ord = DB::table($this->tblAux . 'ordenes' . $this->valorT)
                                ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                                ->select('id_proyecto','fecha_ejecucion','gom')
                                ->get()[0];

                            $proyecto = $ord->id_proyecto;
                            $fechaE = explode(" ",$ord->fecha_ejecucion)[0];

                            $bodega = DB::Table($this->tblAux . 'proyectos')
                                            ->where('id_proyecto',Session::get('rds_gop_proyecto_id'))
                                            ->select('id_bodega','nombre')
                                            ->get()[0];

                            $bode = $bodega->id_bodega;
                            $nombre = $bodega->nombre . " (" . str_replace("PRY00","",str_replace("PRY000","",str_replace("PRY0000", "", $proyecto))) . ")";
                            //Guardamos cabeza

                            $wbsNodo = DB::Table($this->tblAux . 'ws_nodos')
                                        ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_nodos.id_ws')
                                        ->join($this->tblAux . 'nodos',$this->tblAux . 'nodos.id_nodo','=',$this->tblAux . 'ws_nodos.id_nodo1')
                                        ->where($this->tblAux . 'ws_nodos.id_proyecto',Session::get('rds_gop_proyecto_id'))
                                        ->select('nombre_ws','nombre_nodo')
                                        ->get()[0];

                            //Guardamos cabeza
                            DB::table($this->tblAux1  . 'inv_documentos' . $this->valorT)
                                ->insert(array(
                                    array(
                                        'id_documento' => $docCons,
                                        'id_tipo_movimiento' => "T005",
                                        'fecha' => $fechaE,
                                        'fecha_sistema' => $this->fechaALong,
                                        'id_bodega_origen' => $bode,
                                        'id_bodega_destino' => $cargo,
                                        'observaciones' => "DESPACHO DE MATERIALES ASIGNADO A PROYECTO: " . $nombre  . "_ORDEN DE TRABAJO:" . Session::get('rds_gop_proyecto_orden_id')  . "_WBS:" . $wbsNodo->nombre_ws . "_NODO:" . $wbsNodo->nombre_nodo . "_GOM:" . $gom . "_PERSONA:" . $val->persona,
                                        'id_estado' => 'E1',
                                        'id_orden' => Session::get('rds_gop_proyecto_orden_id'),
                                        'gom' => $ord->gom,
                                        "id_usuario_edicion" => Session::get('user_login'),
                                        "id_nodo" => $nodo,
                                        "fecha_confirmacion" => $this->fechaALong,
                                        "fecha_edicion" => $this->fechaALong,
                                        "id_usuario_autoriza_despacho" => Session::get('user_login'),
                                        "fecha_autoriza_despacho" => $this->fechaALong
                                        )
                                    ));
                            
                            self::saveLog("OPERA48",$docCons,"");

                            //Guardamos relación
                            DB::table($this->tblAux  . 'ordenes_materiales_documentos')
                                ->insert(array(
                                    array(
                                        'id_orden' => Session::get('rds_gop_proyecto_orden_id'),
                                        'id_lider' => $cargo,
                                        'id_nodo' => $nodo,
                                        'id_tipo_documento' => "T005",
                                        'id_documento' => $docCons,
                                        'tipo_ingreso' => 1
                                        )
                                    ));     
                        }
                        else
                        {
                            $docCons =$docAux;
                        }

                        //Inserta datos Materiales seleccionado
                            DB::table($this->tblAux . 'detalle_materiales' . $this->valorT)
                            ->insert(array(
                                array(
                                    'id_proyecto' => Session::get('rds_gop_proyecto_id'),
                                    'id_nodo' => $nodo,
                                    'id_articulo' => $arti,
                                    'cantidad_replanteo' => 0,
                                    'id_ws' => $ws,
                                    "tipo_ingreso" => 1
                                    )));

                            //Inserta materiales levantamiento
                            DB::table($this->tblAux . 'ordenes_materiales')
                                ->insert(array(
                                    array(
                                        'id_proyecto' => Session::get('rds_gop_proyecto_id'),
                                        'id_orden' => Session::get('rds_gop_proyecto_orden_id'),
                                        'id_nodo' => $nodo,
                                        'id_articulo' => $arti,
                                        'id_persoanCargo' => $cargo,
                                        'cantidad_confirmada' => $cant,
                                        'tipo_ingreso' => 1
                                        )));
                        $id_al = "0000";
                        if($this->tblAux1 == "rds_")
                            $id_al = "AR09";


                        //Guardamos cuerpo
                        DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                            ->insert(array(
                                array(
                                    'id_documento' => $docCons,
                                    'id_articulo' => $arti, 
                                    'solicitado' => $cant,
                                    'id_almacen' => $id_al
                                    )
                                ));

                        self::saveLog("OPERA49",$docCons,"ARTICULO: $docCons CANTIDAD:");

                        DB::commit();
                        return response()->json("4");
                    }
                    catch(Exception $e)
                    {
                        DB::rollBack();
                        return response()->json("0");
                    }
                }
                else //No se puede agregar
                {
                    return response()->json("-1");
                }
            }
        }

        if($opc == "11") // Eliminar orden
        {

            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];

            try
            {
                $ordenEsta = DB::table($this->tblAux .'ordenes' . $this->valorT)
                            ->where('id_proyecto',$proyecto)
                            ->where('id_orden',$orden)
                            ->select('id_estado')
                            ->get()[0]->id_estado;

                DB::beginTransaction();

                if($ordenEsta == "E1")
                {
                    //Cambio estado de todos las tablas el estado
                    DB::table($this->tblAux .'ordenes_manoobra')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->update(array(
                            'id_estado' => 1
                            ));
                    
                    //Actualiza ManoObra
                    DB::table($this->tblAux .'ordenes_manoobra_detalle')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->update(array(
                            'id_estado' => 1
                            ));

                    //Actualiza ManoObra Detalle
                    DB::table($this->tblAux .'ordenes_materiales')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orden)
                        ->update(array(
                            'id_estado' => 1
                            ));

                    //Actualiza Material Documentos

                    //Actualiza orden
                    DB::table($this->tblAux .'ordenes' . $this->valorT)
                        ->where('id_orden',$orden)
                        ->update(array(
                            'id_estado' => 'A1'
                            ));


                    self::saveLog("OPERA62",$orden,"");
                    
                    //rds_gop_ordenes_materiales_documentos
                }
                else
                {
                    if($ordenEsta == "E2")
                    {
                        //Consulta
                        $dc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                        ->join($this->tblAux1  . 'inv_documentos' . $this->valorT,$this->tblAux1  . 'inv_documentos' . $this->valorT . ".id_documento",'=',$this->tblAux . 'ordenes_materiales_documentos.id_documento')
                        ->join('inv_cat_estado_documentos','inv_cat_estado_documentos.id_estado','=',$this->tblAux1  . 'inv_documentos' . $this->valorT . ".id_estado")
                        ->where($this->tblAux . 'ordenes_materiales_documentos.id_orden',$orden)
                        ->where($this->tblAux . 'ordenes_materiales_documentos.id_tipo_documento','T005')
                        ->select($this->tblAux . 'ordenes_materiales_documentos.id_documento',
                            'inv_cat_estado_documentos.id_estado')
                        ->get();

                        $aux = 0;
                        foreach ($dc as $documentos => $val) {
                            if($val->id_estado == "E3")
                                $aux++;
                        }

                        if($aux == 0) //No tengo ningún DC confirmado
                        {

                            //Cambio estado de todos las tablas el estado
                            DB::table($this->tblAux .'ordenes_manoobra')
                                ->where('id_proyecto',$proyecto)
                                ->where('id_orden',$orden)
                                ->update(array(
                                    'id_estado' => 1
                                    ));
                            
                            //Actualiza ManoObra
                            DB::table($this->tblAux .'ordenes_manoobra_detalle')
                                ->where('id_proyecto',$proyecto)
                                ->where('id_orden',$orden)
                                ->update(array(
                                    'id_estado' => 1
                                    ));

                            //Actualiza ManoObra Detalle
                            DB::table($this->tblAux .'ordenes_materiales')
                                ->where('id_proyecto',$proyecto)
                                ->where('id_orden',$orden)
                                ->update(array(
                                    'id_estado' => 1
                                    ));

                            //Actualiza orden
                            DB::table($this->tblAux .'ordenes' . $this->valorT)
                                ->where('id_orden',$orden)
                                ->update(array(
                                    'id_estado' => 'A1'
                                    ));

                            self::saveLog("OPERA62",$orden,"");

                            foreach ($dc as $documentos => $val) {
                                
                                //Actualiza Material Documentos
                                DB::table($this->tblAux .'ordenes_materiales_documentos')
                                    ->where('id_orden',$orden)
                                    ->where('id_tipo_documento','T005')
                                    ->where('id_documento',$val->id_documento)
                                    ->update(array(
                                        'id_estado' => 1
                                        ));

                                //Actualiza Documentos Cabeza
                                /*DB::table($this->tblAux1  . 'inv_documentos' . $this->valorT)
                                    ->where('id_orden',Session::get('rds_gop_proyecto_orden_id'))
                                    ->where('id_tipo_movimiento','T005')
                                    ->where('id_documento',$val->id_documento)
                                    ->update(array(
                                        'id_estado' => "A1"
                                        ));  */

                                self::saveLog("OPERA56",$val->id_documento,"COMENTARIADO");
                            }  
                        }
                        else
                        {
                            //Tengo un DC o varios confirmados
                            return response()->json("-2");
                        }
                    }
                    else
                    {
                        return response()->json("-1");
                    }
                }
                DB::commit();
                return response()->json("1");
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
        }

        if($opc == "12") //Anula Despacho
        {
            $dc = $request->all()["dc"];
            $ot = $request->all()["ot"];

            $lider = DB::table($this->tblAux1 .'inv_documentos')
                      ->where('id_documento',$dc) 
                      ->value('id_bodega_destino');

            $nodo = DB::table($this->tblAux1 .'inv_detalle_documentos')
                      ->where('id_documento',$dc) 
                      ->value('id_nodo');

                try
                {

                    //Delete Material
                    DB::table($this->tblAux  . 'ordenes_materiales')
                        ->where('id_orden',$ot)
                        ->where('id_nodo',$nodo)
                        ->where('id_persoanCargo',$lider)
                        ->delete();

                    //Elimino asociación
                    DB::table($this->tblAux  . 'ordenes_materiales_documentos')
                            ->where('id_documento',$dc)
                            ->where('id_orden',$ot)
                            ->where('id_lider',$lider)
                            ->delete();


                    //Elimino id nodo de detalle
                    DB::table($this->tblAux1 .'inv_detalle_documentos')
                    ->where('id_documento',$dc) 
                    ->update(
                            array(
                                'id_nodo' => NULL
                                )
                            );

                    //Elimino id_orden de cabeza documentos
                    DB::table($this->tblAux1 .'inv_documentos')
                        ->where('id_documento',$dc) 
                        ->update(
                                array(
                                    'id_orden' => NULL
                                    )
                                );

                    self::saveLog("OPERA66",$ot,"");

                }   
                catch(Exception $e)
                {
  
                    $arr = "0";
                }

                return response()->json("1");
        }

        if($opc == "13") //Guardar Material Extra
        {
            try
            {   
                $nodo = $request->all()["nodo"];
                $bare = $request->all()["bare"];
                $cant = $request->all()["cant"];
                $cargo = $request->all()["cargo"];
                $ot = $request->all()["ot"];

                $ws = DB::table($this->tblAux .'ws_nodos' . $this->valorT)
                        ->where('id_nodo1',$nodo)
                        ->select('id_ws','id_proyecto')
                        ->get();

                $wbs = $ws[0]->id_ws;
                $proy = $ws[0]->id_proyecto;

                $pre = DB::table($this->tblAux .'baremos' . $this->valorT)
                        ->where('codigo',$bare)
                        ->where('periodo',2017)
                        ->select('precio')
                        ->get()[0]->precio;

                $cont = DB::table($this->tblAux .'ordenes_mobra' . $this->valorT)
                        ->where('id_proyecto',$proy)
                        ->where('id_orden',$ot)
                        ->where('id_nodo',$nodo)
                        ->where('id_baremo',$bare)
                        ->select('cantidad_confirmada')
                        ->get();

                if(count($cont) > 0 )
                    return response()->json("1");


                DB::beginTransaction();

                DB::Table($this->tblAux . 'ordenes_manoobra')
                        ->insert(
                            array(
                                array(
                                    'id_proyecto' => $proy,
                                    'id_orden' => $ot,
                                    'id_nodo' => $nodo,
                                    'id_baremo' => $bare,
                                    'cantidad_confirmada' => 0,
                                    'id_personaCargo' => $cargo,
                                    'tipo_ingreso' => 1
                                    )
                                )
                            );

                $bareNuevo = DB::Table($this->tblAux . 'baremos')
                                ->where('codigo',$bare)
                                ->where('periodo',2017)
                                ->value('id_baremo');

                DB::Table($this->tblAux . 'ordenes_mobra')
                        ->insert(
                            array(
                                array(
                                    'id_proyecto' => $proy,
                                    'id_ws' => $wbs,
                                    'id_nodo' => $nodo,
                                    'id_orden' => $ot,
                                    'id_baremo' => $bareNuevo,
                                    'cantidad_confirmada' => $cant,
                                    'precio' => $pre,
                                    'id_origen' => $cargo
                                    )
                                )
                            );


                DB::commit();
                return response()->json("1");
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("-1");
            }
        }

        if($opc == "14") //Reprogramación de ORDEN
        {
            /*
                Tipos de consulta

                get() -> Retorna Tipo Objeto
                value() -> Retorna el valor si es solo una columna
                ->first() Retorna solo el primer registro de manera de objeto
                ->pluck('title') -> Retorna una columna tipo arreglo;

            */
            set_time_limit(0);
            
            $proyecto = $request->all()["proyecto"];
            $orden = $request->all()["orden"];


            $ordenActual = $orden;
            $proyectoActual = $proyecto;
            $fechaNueva = $request->all()["fecha"];
            $observa = $request->all()["observa"];

            //Valida fecha Actual
            $fechaActual = explode(" ",DB::table($this->tblAux . 'ordenes')
                            ->where('id_orden',$ordenActual)
                            ->value('fecha_ejecucion'))[0];
            
            if($fechaActual == $fechaNueva)
                return response()->json("-3");    

            //Valida Documentos abiertos
            $cantdocumentosAsociados = DB::table($this->tblAux . 'ordenes_materiales_documentos as tbl2')
                            ->where('tbl2.id_orden',$ordenActual)
                            ->whereIn('tbl1.id_estado',['E0','E1','E2','E3','E4','C2'])
                            ->join($this->tblAux1 . 'inv_documentos as tbl1','tbl1.id_documento','=','tbl2.id_documento')
                            ->count();

            if($cantdocumentosAsociados > 0)
                return response()->json("-2");  

            //Valida fecha menor a la actual
            $fecha_inicio = new \DateTime($this->fechaShort);
            $fecha_fin    = new \DateTime($fechaNueva);
            if($fecha_fin <= $fecha_inicio)
                return response()->json("-1");  
            
            //Valida disponiblidad de recurso
            $recursoAntiguo = DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                            ->where('id_orden',$ordenActual)
                            ->select('id_lider','hora_ini','hora_fin')
                            ->get();

            $lideresUtilizados = "";
            foreach ($recursoAntiguo as $key => $value) {
                
                //->where('tbl2.id_lider',$value->id_lider)
                $usoRecurso = DB::table($this->tblAux . 'ordenes as tbl1')                            
                            ->whereBetween('tbl1.fecha_ejecucion',[$fechaNueva . " 00:00:00",$fechaNueva . " 23:59:59"])                            
                            ->whereBetween('tbl2.hora_ini',[$value->hora_ini,$value->hora_fin])
                            ->where('tbl2.hora_fin','<=',$value->hora_fin)
                            ->where('tbl2.id_lider',$value->id_lider)
                            ->join($this->tblAux . 'ordenes_manoobra_detalle as tbl2','tbl1.id_orden','=','tbl2.id_orden')
                            ->select('tbl1.fecha_ejecucion','tbl2.hora_ini','tbl2.hora_fin','tbl2.id_lider')
                            ->count();

                if($usoRecurso > 0 )
                {
                    $nombreUser = DB::table('rh_personas')
                                    ->where('identificacion',$value->id_lider)
                                    ->select(DB::raw("nombres + ' ' + apellidos as nombres"))
                                    ->value('nombres');
                    $lideresUtilizados .= " *" . $value->id_lider . " - " . $nombreUser . "<br>";
                            //var_dump($value->hora_ini . "--" . $value->hora_fin);
                }
            }

            if($lideresUtilizados != "")
                return response()->json($lideresUtilizados);  

            //Orden Actual
            $ordenActualConsulta = DB::table($this->tblAux . 'ordenes')
                                    ->where('id_orden',$ordenActual)
                                    ->get()[0];

            //Mano obra programada
            $manoObraProgramada = DB::table($this->tblAux . 'ordenes_manoobra')
                        ->where('id_orden',$ordenActual)
                        ->select('id_proyecto','id_orden', 'id_nodo', 'id_baremo', 'cantidad_confirmada', 'id_personaCargo')
                        ->get();

            //Detalle de recuros
            $manoObraDetalle = DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                        ->where('id_orden',$ordenActual)
                        ->get();

            //Material Programado
            $materialProgramada = DB::table($this->tblAux . 'ordenes_materiales')
                        ->where('id_orden',$ordenActual)
                        ->get();

            //Documentos generados por Maniobras
            $documentoCreado = DB::table($this->tblAux1 . 'inv_documentos')
                        ->where('id_orden',$ordenActual)
                        ->get();

            try
            {
                DB::beginTransaction();

                $consecutivoOrden = self::generaConsecutivo("ID_ORDEN");

                //Inserta Orden
                DB::Table($this->tblAux . 'ordenes' . $this->valorT)
                    ->insert(array(
                        array(
                            'id_orden' => $consecutivoOrden,
                            'id_tipo' => $ordenActualConsulta->id_tipo,
                            'fecha_programacion' => $this->fechaShort,
                            'fecha_ejecucion' => $fechaNueva,
                            'id_proyecto' => $ordenActualConsulta->id_proyecto,
                            'fecha_emision' => $ordenActualConsulta->fecha_emision,
                            'ing_solicitante' => $ordenActualConsulta->ing_solicitante,
                            'ing_soli_cam' => $ordenActualConsulta->ing_soli_cam,
                            'supervisor' => $ordenActualConsulta->supervisor,
                            'direccion' => $ordenActualConsulta->direccion,
                            'lv' => $ordenActualConsulta->lv,
                            'lm' => $ordenActualConsulta->lm,
                            'prox' => $ordenActualConsulta->prox,
                            'lv_lm' => $ordenActualConsulta->lv_lm,
                            'exp' => $ordenActualConsulta->exp,
                            'cd' => $ordenActualConsulta->cd,
                            'nivel_tension' => $ordenActualConsulta->nivel_tension,
                            'hora_ini' => $ordenActualConsulta->hora_ini,
                            'hora_corte' => $ordenActualConsulta->hora_corte,
                            'hora_cierre' => $ordenActualConsulta->hora_cierre,
                            'hora_fin' => $ordenActualConsulta->hora_fin,
                            'observaciones' => $ordenActualConsulta->observaciones,
                            'gom_adec' => $ordenActualConsulta->gom_adec,
                            'gom_inst' => $ordenActualConsulta->gom_inst,
                            'fac_orden' => $ordenActualConsulta->fac_orden,
                            'rad_orden' => $ordenActualConsulta->rad_orden,
                            'proy_orden' => $ordenActualConsulta->proy_orden,
                            'id_estado' => "E2",
                            'descargo' => $ordenActualConsulta->descargo,
                            'descargo2' => $ordenActualConsulta->descargo2,
                            'gom' => $ordenActualConsulta->gom
                            )));
                self::saveLog("OPERA17",$consecutivoOrden,"PROYECTO $proyectoActual - REPROGRAMACIÓN DE LA OT $ordenActual"); 

                
                //Inserta ManoObra programada
                foreach ($manoObraProgramada as $key => $value) {
                    DB::Table($this->tblAux . 'ordenes_manoobra')
                    ->insert(array(
                        array(
                            'id_orden' => $consecutivoOrden,
                            'id_proyecto' => $value->id_proyecto,
                            'id_nodo' => $value->id_nodo,
                            'id_baremo' => $value->id_baremo,
                            'cantidad_confirmada' => $value->cantidad_confirmada,
                            'id_personaCargo' => $value->id_personaCargo
                            )));    

                    self::saveLog("OPERA23",$consecutivoOrden,"BAREMO: " . $value->id_baremo . " CANTIDAD: " . $value->cantidad_confirmada . " LIDER: $value->id_personaCargo NODO $value->id_nodo RE PROGRAMADA DE: $ordenActual");
                }

                //Inserta Detalle del recuros
                foreach ($manoObraDetalle as $key => $value) {
                    DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->insert(array(
                        array(
                            'id_orden' => $consecutivoOrden,
                            'id_proyecto' => $value->id_proyecto,
                            'id_lider' => $value->id_lider,
                            'hora_ini' => $value->hora_ini,
                            'hora_fin' => $value->hora_fin,
                            'id_tipo' => $value->id_tipo,
                            'aux_id1' => $value->aux_id1,
                            'aux_id2' => $value->aux_id2,
                            'aux_id3' => $value->aux_id3,
                            'conductor_id' => $value->conductor_id,
                            'matricula' => $value->matricula
                            )));   

                    self::saveLog("OPERA20",$consecutivoOrden,"LIDER: " . $value->id_lider . " HORA INI: $value->hora_ini HORA FIN: $value->hora_fin RE PROGRAMADA DE: $ordenActual"); 
                }

                //Inserta Material Programado Se quita la parte de Material
                /*foreach ($materialProgramada as $key => $value) {
                    DB::Table($this->tblAux . 'ordenes_materiales')
                    ->insert(array(
                        array(
                            'id_orden' => $consecutivoOrden,
                            'id_proyecto' => $value->id_proyecto,
                            'id_nodo' => $value->id_nodo,
                            'id_articulo' => $value->id_articulo,
                            'cantidad_confirmada' => $value->cantidad_confirmada,
                            'id_persoanCargo' => $value->id_persoanCargo
                            )));    
                    self::saveLog("OPERA27",$consecutivoOrden,"ARTICULO: " . $value->id_articulo . " CANTIDAD: " . $value->cantidad_confirmada . " LIDER: $value->id_persoanCargo NODO $value->id_nodo RE PROGRAMADA DE: $ordenActual");
                }*/

                //Generar documentos de despacho

                /*
                foreach ($documentoCreado as $key => $value) {

                    $codDocumento = self::dame_uncodigo_almacen("T005");

                    DB::table($this->tblAux1  . 'inv_documentos')
                    ->insert(array(
                        array(
                            'id_documento' => $codDocumento,
                            'id_tipo_movimiento' => $value->id_tipo_movimiento,
                            'fecha' => $fechaNueva,
                            'fecha_sistema' => $this->fechaALong,
                            'id_bodega_origen' => $value->id_bodega_origen,
                            'id_bodega_destino' => $value->id_bodega_destino,
                            'observaciones' => $value->observaciones,
                            'id_estado' => 'E1',
                            'id_orden' => $consecutivoOrden,
                            'gom' => $value->gom,
                            "id_usuario_edicion" => Session::get('user_login'),
                            "id_nodo" => $value->id_nodo,
                            "fecha_confirmacion" => $this->fechaALong,
                            "fecha_edicion" => $this->fechaALong,
                            "id_usuario_autoriza_despacho" => Session::get('user_login'),
                            "fecha_autoriza_despacho" => $this->fechaALong
                            )
                        ));

                    self::saveLog("OPERA48",$codDocumento,"ORDEN " . $consecutivoOrden . " RE PROGRAMACIÓN DE ORDEN : $ordenActual"); 

                    //Guardamos cuerpo
                    $documentoCuerpo = DB::table($this->tblAux1 . 'inv_detalle_documentos')
                                ->where('id_documento',$value->id_documento)
                                ->select('id_articulo','solicitado','id_almacen')
                                ->get();

                    //Relación documentos Maniobras
                    $documentoAsignadosPersonas = 
                        DB::table($this->tblAux . 'ordenes_materiales_documentos')
                        ->where('id_orden',$ordenActual)
                        ->where('id_documento',$value->id_documento)
                        ->select('id_lider','id_nodo','id_tipo_documento')
                        ->get(); 

                    foreach ($documentoCuerpo as $key => $value) {
                        DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                        ->insert(array(
                            array(
                                'id_documento' => $codDocumento,
                                'id_articulo' => $value->id_articulo, 
                                'solicitado' => $value->solicitado,
                                'id_almacen' => $value->id_almacen
                                )
                            ));
                        
                        self::saveLog("OPERA49",$codDocumento,"ARTICULO " . $value->id_articulo . "RE PROGRAMACIÓN DE ORDEN : $ordenActual"); 
                    }
                    
                    //Relación documentos Maniobra
                    foreach ($documentoAsignadosPersonas as $key => $value) {
                        //Guardamos relación
                        DB::table($this->tblAux  . 'ordenes_materiales_documentos')
                            ->insert(array(
                                array(
                                    'id_orden' => $consecutivoOrden,
                                    'id_lider' => $value->id_lider,
                                    'id_nodo' => $value->id_nodo,
                                    'id_tipo_documento' => $value->id_tipo_documento,
                                    'id_documento' => $codDocumento
                                    )
                                ));
                    }
                }
                */
                
                //Actualizar orden
                DB::table($this->tblAux . 'ordenes')
                    ->where('id_orden',$ordenActual)
                    ->update(array(
                        'observacion_reprogramacion' => $observa,
                        'fecha_reprogramacion' => $this->fechaALong,
                        'usuario_reprogramacion' => Session::get('user_login'),
                        'orden_nueva_reprogra' => $consecutivoOrden
                        ));

                Session::put('rds_gop_proyecto_orden_id',$consecutivoOrden);
                DB::commit();
                return response()->json("1");
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("-4");
            }
        }

        if($opc == "15") // Save Restricción
        {
            $restric = $request->all()['restric'];
            $impac = $request->all()['impac'];
            $fecha = explode("/",$request->all()['fecha']);
            $fecha = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];
            $resp = $request->all()['resp'];
            $corr = $request->all()['corr'];
            $restricD = $request->all()['restricD'];     

            $orden = $request->all()['orden'];
            $proyectoID = $request->all()['proyecto'];


            DB::table($this->tblAux .'restriccionesProyecto')
                ->insert(array(
                    array(
                        'id_proyecto' => $proyectoID,
                        'fecha_registro' => $this->fechaALong,
                        'texto_restriccion' => $restric,
                        'impacto' => $impac,
                        'fecha_limite' => $fecha,
                        'responsable' => $corr[0]["nombre"],
                        'id_orden' => $orden,
                        'id_estado' => 'A',
                        'restriccion_descripcion' => $restricD
                        )
                    ));

            $maxRestri = DB::table($this->tblAux .'restriccionesProyecto')
                        ->select(DB::raw("MAX(id_restriccion) as id_res"))
                        ->get();

             $maxRestri = $maxRestri[0]->id_res;
            self::saveLog("OPERA09",$orden,"");

            $proyecto = DB::Table($this->tblAux . 'proyectos')
                            ->where('id_proyecto',$proyectoID)
                            ->value('nombre');

            $datos = DB::Table($this->tblAux . 'ordenes')
                        ->where('id_orden',$orden)
                        ->get(['direccion','fecha_ejecucion','cd','seccionador','pf'])[0];

            $recurso = DB::Table($this->tblAux . 'ordenes_manoobra_detalle as tbl1')
                        ->where('tbl1.id_orden',$orden)
                        ->join('rh_personas as tbl2','tbl1.id_lider','=','tbl2.identificacion')
                        ->get(['tbl1.id_lider',DB::raw("(nombres + ' ' +  apellidos) as nombre")]);

            for ($i=0; $i < count($corr); $i++) { 

                DB::table($this->tblAux .'restricciones_correos')
                ->insert(array(
                    array(
                        'id_proyecto' => $proyectoID,
                        'id_orden' => $orden,
                        'responsable' => $corr[$i]["nombre"],
                        'correo' => $corr[$i]["correo"],
                        'id_restriccion' => $maxRestri
                        )
                    ));

                $arr = [];

                $tipoR = DB::Table($this->tblAux . 'tipo_restriccion')
                            ->where('id_tipo_restriccion',$restric)
                            ->value('nombre');

                
                array_push($arr, ["res" => $restric,"imp" => $impac,"fech" => $fecha,"resp" => $corr[0]["nombre"],
                    "nombreR" => $tipoR, "OT" => $orden,
                    "pry" => $proyecto ,"fechaE" => $datos->fecha_ejecucion,"dire" => $datos->direccion,
                    "cd" => $datos->cd,"pf" => $datos->pf,"sec" => $datos->seccionador,
                    "recurso" => $recurso]);
                try
                {
                    $corrE = $corr[$i]["correo"];
                    if($i == 0)
                    {
                        \Mail::send("emails.restriccion",["arr" => $arr],function($msj) use($corrE)
                        {
                            $msj->subject('Asignación Restricción');
                            $msj->to($corrE);
                        });
                    }
                    else
                    {
                        \Mail::send("emails.restriccion",["arr" => $arr],function($msj) use($corrE)
                        {
                            $msj->subject('Seguimiento Restricción');
                            $msj->to($corrE);
                        }); 
                    }
                    
                }catch(Exception $e)
                {
                }
            }
            $consultaRestric =  DB::table($this->tblAux .'restriccionesProyecto')
                        ->join($this->tblAux .'tipo_restriccion','id_tipo_restriccion','=','texto_restriccion')
                        ->where('id_proyecto',$proyectoID)
                        ->where('id_orden',$orden)
                        ->select('id_restriccion','nombre as texto_restriccion','texto_restriccion as tipo','impacto','fecha_limite',
                            'responsable','correo_responsable','evidencia_cierre','id_estado'
                            ,'fecha_cierre','restriccion_descripcion')
                        ->get();
            $arreglo = [];
            foreach ($consultaRestric as $key => $value) {
                array_push($arreglo,
                    [$value,
                    DB::table($this->tblAux .'restricciones_correos')
                        ->where('id_restriccion',$value->id_restriccion)
                        ->select('correo')
                        ->get()]);
            }


            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableRestricciones',
                array("restric" => $arreglo,
                    'fechaR' => $this->fechaShort
            ))->render()); 
        }

        if($opc == "16") // Update Restriccion
        {
            $esta = $request->all()['esta'];
            $res_id = $request->all()['res_id'];

            if($esta == "C")
            {
                // 
                $archivo = base64_decode(explode(",",$request->all()['archivo'])[1]);
                //dd($archivo);

                $mime = $request->all()['mime'];  

                $envio = self::envioArchivos($archivo,"Restriccion_" . $res_id . "_" . $this->fechaShort . "." . $mime,"/tprog/adjuntorestricciones");                

                
                    DB::table($this->tblAux .'restriccionesProyecto')
                    ->where('id_restriccion',$res_id)
                    ->update(
                        array(
                            'id_estado' => $esta,
                            'fecha_cierre' => $this->fechaALong,
                            'evidencia_cierre' => "/tprog/adjuntorestricciones/Restriccion_" . $res_id . "_" . $this->fechaShort . "." . $mime
                            )
                        );  

                    self::saveLog("OPERA10",Session::get('rds_gop_proyecto_orden_id'),"RESTRICCION: $res_id");
                    
                    self::saveLog("OPERA65",$res_id,""); 
                
            }
            else
            {
                DB::table($this->tblAux .'restriccionesProyecto')
                ->where('id_restriccion',$res_id)
                ->update(
                    array(
                        'id_estado' => $esta
                        )
                    );   
                self::saveLog("OPERA10",Session::get('rds_gop_proyecto_orden_id'),"RESTRICCION: $res_id");

                if($esta == "X")
                    self::saveLog("OPERA64",$res_id,""); 
                else
                    self::saveLog("OPERA63",$res_id,""); 
            }

            return response()->json("1");  
        }

        if($opc == "17") // Cambio líder
        {
            $lid = $request->all()["cod"];
            $hor1 = $request->all()["hor1"];
            $hor2 = $request->all()["hor2"];
            $tip = $request->all()["tip"];
            $aux1 = $request->all()["aux1"];
            $aux2 = $request->all()["aux2"];
            $aux3 = $request->all()["aux3"];
            $cond = $request->all()["cond"];
            $matri = $request->all()["movi"];
            $liderA = $request->all()["lidA"];

            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];


            $contDCConfi = DB::table($this->tblAux1 . 'inv_documentos')
                        ->where('id_orden',$orden)
                        ->where('id_bodega_destino',$liderA)
                        ->where('id_estado','E3')
                        ->count();   

            if($contDCConfi > 0)
                return response()->json("-1");

            $cantidadEjecutado = DB::table($this->tblAux . 'mobra')
                    ->where('id_orden',$orden)
                    ->where('id_origen',$liderA)
                    ->count();

            if($cantidadEjecutado > 0)
                return response()->json("-2");

            DB::beginTransaction();
            try
            {
                //Update Líder
                DB::table($this->tblAux . 'ordenes_manoobra_detalle')
                    ->where('id_proyecto',$proyecto)
                    ->where('id_orden',$orden)
                    ->where('id_lider',$liderA)
                    ->update(
                        array(
                            'id_lider' => $lid,
                            'hora_ini' => $hor1,
                            'hora_fin' => $hor2,
                            'id_tipo' => $tip,
                            'aux_id1' => $aux1,
                            'aux_id2'  => $aux2,
                            'aux_id3' => $aux3,
                            'conductor_id' => $cond,
                            'matricula' => $matri,
                            'id_estado' => 0
                            ));
                self::saveLog("OPERA21",$orden,"CAMBIO DE LIDER: " . $liderA . " POR $lid");

                //Update ManoObra
                DB::table($this->tblAux . 'ordenes_manoobra')
                    ->where('id_proyecto',$proyecto)
                    ->where('id_orden',$orden)
                    ->where('id_personaCargo',$liderA)
                    ->update(
                        array(
                            'id_personaCargo' => $lid
                            ));           

                //Update Documentos
                DB::table($this->tblAux . 'ordenes_materiales')
                    ->where('id_proyecto',$proyecto)
                    ->where('id_orden',$orden)
                    ->where('id_persoanCargo',$liderA)
                    ->update(
                        array(
                            'id_persoanCargo' => $lid
                            )); 

                $doc = DB::table($this->tblAux . 'ordenes_materiales_documentos')
                    ->where('id_orden',$orden)
                    ->where('id_lider',$liderA)
                    ->select('id_documento')
                    ->get(); 

                DB::table($this->tblAux . 'ordenes_materiales_documentos')
                    ->where('id_orden',$orden)
                    ->where('id_lider',$liderA)
                    ->update(
                        array(
                            'id_lider' => $lid
                            )); 

                foreach ($doc as $key => $value) {
                    
                    DB::table($this->tblAux1 . 'inv_documentos')
                    ->where('id_documento',$value->id_documento)
                    ->where('id_bodega_destino',$liderA)
                    ->update(
                        array(
                            'id_bodega_destino' => $lid
                            ));    

                    DB::table('sis_log_documentos') 
                        ->insert(
                                array(
                                    array(
                                            'id_documento' => $value->id_documento,
                                            'id_usuario' => Session::get('user_login'),
                                            'id_log' => 'INV006',
                                            'informacion' => 'CAMBIO DE LÍDER DE ' . $liderA . ' A ' . $lid . ' , TRABAJOS PROGRAMADOS',
                                        )
                                    )
                            );
                }
                DB::commit();
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }

            return response()->json("1");
        }

        if($opc == "18") //Abrir Orden
        {
            $orden = $request->all()["orden"];

            DB::table($this->tblAux .'ordenes')
                    ->where('id_orden',$orden)
                    ->update(
                        array(
                            'id_estado' => 'E1'
                            )
                        );

            self::saveLog("OPERA60",$orden,"Abre orden"); 
            return response()->json("1");
        }

        if($opc == "19") //Save Responsable
        {

            $nombre = $request->all()["nombre"];
            $correo = $request->all()["correo"];
            $id = $request->all()["id"];

            $contR = DB::Table($this->tblAux . 'responsable_restricciones')
                    ->where('id',$id)
                    ->count();

            if($contR == 0) //Create
            {
                DB::Table($this->tblAux . 'responsable_restricciones')
                    ->insert(array(
                        array(
                            'nombre' => $nombre,
                            'correo' => $correo,
                            )
                        ));
            }
            else //Update
            {
                DB::Table($this->tblAux . 'responsable_restricciones')
                    ->where('id',$id)
                    ->update(array(
                            'nombre' => $nombre,
                            'correo' => $correo,
                            )
                        );
            }

            $resp = DB::Table($this->tblAux . 'responsable_restricciones')
                    ->get(['id','nombre','correo']);

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tableResposablesRestricciones',
                array(
                "res" => $resp
                ))->render());
        }

        if($opc == "20") //Save baremos nuevos MASIVO
        {
            $nodo = $request->all()["nodo"];
            $baremos = $request->all()["bare"];
            $cargo = $request->all()["cargo"];

            $fecha_consulta = $request->all()["fecha_consulta"];
            
            
            if(isset($request->all()["ord"]))
            {
                $orde = $request->all()["ord"];
                $proyecto = $request->all()["pry"];
            }
           

            if(isset($request->all()["orden"]))
            {
                $orde = $request->all()["orden"];
                for ($i= (strlen($orde) + 2); $i < 12; $i++) { 
                    $orde = "0" . $orde;
                }
                $orde = "OT" . $orde;

                $proyecto  = DB::Table($this->tblAux .'ordenes')
                                 ->where('id_orden',$orde)
                                 ->value('id_proyecto');
            }
            
            $datosProyectoID = DB::table($this->tblAux . 'ordenes')
                       ->where($this->tblAux . 'ordenes.id_orden',$orde)
                       ->value('id_proyecto');  

            $proyectoID = DB::table($this->tblAux . 'proyectos')
                        ->where($this->tblAux . 'proyectos.id_proyecto',$datosProyectoID)
                        ->value('tipo_proyecto');   

            $ordenEsta = DB::table($this->tblAux .'ordenes' . $this->valorT)
                        ->where('id_orden',$orde)
                        ->select('id_estado')
                        ->get()[0]->id_estado;


             $ws = DB::table($this->tblAux .'ws_nodos' . $this->valorT)
                    ->where('id_nodo1',$nodo)
                    ->select('id_ws')
                    ->get()[0]->id_ws;
             

            $baremosRepetidos = "";
            
            for ($i=0; $i < count( $baremos ); $i++) { 
                $bare =  $baremos[$i]['codigo'];
                $cant =  $baremos[$i]['cant'];

                $consulta = DB::Table($this->tblAux . 'ordenes_manoobra')
                        ->where('id_proyecto',$proyecto)
                        ->where('id_orden',$orde)
                        ->where('id_nodo',$nodo)
                        ->where('id_personaCargo',$cargo)
                        ->where('id_baremo',$bare)
                        ->where('tipo_ingreso',1)
                        ->select('cantidad_confirmada')
                        ->get();

                $precioBaremo = DB::table($this->tblAux .'baremos')
                            ->where('codigo',$bare)
                            ->where('periodo',2017)
                            ->value('precio');

                $Baremo = DB::table($this->tblAux .'baremos')
                        ->where('codigo',$bare)
                        ->where('periodo',2017)
                        ->value('id_baremo');


                if(count($consulta) == 0)
                {

                    DB::table($this->tblAux . 'detalle_mobra' . $this->valorT)
                    ->insert(array(
                        array(
                            'id_proyecto' => $proyecto,
                            'id_nodo' => $nodo,
                            'id_baremo' => $bare,
                            'cantidad_replanteo' => 0,
                            'id_ws' => $ws,
                            'tipo_ingreso' => 1
                            )));

                    DB::table($this->tblAux . 'ordenes_manoobra')
                        ->insert(array(
                            array(
                                'id_proyecto' => $proyecto,
                                'id_orden' => $orde,
                                'id_nodo' => $nodo,
                                'id_baremo' => $bare,
                                'id_personaCargo' => $cargo,
                                'cantidad_confirmada' => 0,
                                'tipo_ingreso' => 1
                                )));                   
                    
                    //Guarda Tabla anterior mobra


                    if($proyectoID != "T03")
                    {
                        DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                            ->insert(array(
                                array(
                                    'id_proyecto' => $proyecto,
                                    'id_ws' => $ws,
                                    'id_nodo' => $nodo,
                                    'id_orden' => $orde,
                                    'id_baremo' => $Baremo,
                                    'cantidad_confirmada' => $cant,
                                    'precio' => $precioBaremo,
                                    'id_origen' => $cargo
                                    )
                                ));

                        self::saveLog("OPERA50",$orde,"LIDER " . $cargo . " BAREMO: " . $Baremo . " CANTIDAD: " . $cant . " CREAR"); 

                        DB::table($this->tblAux  . "mobra")
                        ->insert(array(
                            array(
                                'id_orden' => $orde,
                                'id_origen' => $cargo,
                                'id_baremo' => $Baremo,
                                'cantidad' => $cant,
                                'precio' => $precioBaremo,
                                'fecha' => $this->fechaALong,
                                'id_nodo' => $nodo
                                )
                            ));
                    }
                }   
                else
                {
                    $baremosRepetidos .= "Baremo: " . $bare;
                }


                if($proyectoID == "T03")
                {  
                    DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                            ->insert(array(
                                array(
                                    'id_proyecto' => $proyecto,
                                    'id_ws' => $ws,
                                    'id_nodo' => $nodo,
                                    'id_orden' => $orde,
                                    'id_baremo' => $Baremo,
                                    'cantidad_confirmada' => $cant,
                                    'precio' => $precioBaremo,
                                    'id_origen' => $cargo,
                                    'fecha' => $fecha_consulta
                                    )
                                ));

                    self::saveLog("OPERA50",$orde,"LIDER " . $cargo . " BAREMO: " . $Baremo . " CANTIDAD: " . $cant . " CREAR"); 

                    DB::table($this->tblAux  . "mobra")
                    ->insert(array(
                        array(
                            'id_orden' => $orde,
                            'id_origen' => $cargo,
                            'id_baremo' => $Baremo,
                            'cantidad' => $cant,
                            'precio' => $precioBaremo,
                            'fecha' => $fecha_consulta,
                            'id_nodo' => $nodo
                            )
                        ));


                    DB::table($this->tblAux1 . 'log_captura_ejecucion')
                        ->insert(array(
                                array(
                                    'id_log' => "OPERA68",
                                    'id_usuario' => Session::get('user_login'),
                                    'id_orden' => $orde,
                                    'fecha_consulta' => $fecha_consulta,
                                    'descripcion' => "NODO: $nodo - LIDER: $cargo - BAREMO: " . $Baremo . " - CANTIDAD: " . $cant
                                    )
                                ));

                }

            }

            return response()->json(array(
                'res' => ($baremosRepetidos == "" ? 1 : -1),
                'bare' => $baremosRepetidos
            ));


        }   

    }

    public function consultaMovil(Request $request)
    {
        $opc = $request->all()["opc"];

        //CONSULTA APLICACIÓN MÓVIL
        if($opc == "1") //Consulta Nodos Afectados por el líder
        {
            "1";
        }

        if($opc == "2") //Consulta Materiales y NODOS MAESTRO
        {
            $pre = $request->all()["pre"];
            
            $actividades = DB::table($pre . '_gop_baremos')
                    ->where('periodo',2017)
                    ->join('inv_cat_unidades','inv_cat_unidades.id_unidad','=',$pre . '_gop_baremos.id_unidad')
                    ->select('id_baremo','actividad','codigo','inv_cat_unidades.nombre as nombreUnidad')
                    ->get();

            $materiales = DB::table($pre . '_inv_maestro_articulos')
                    ->join('inv_cat_unidades','inv_cat_unidades.id_unidad','=',$pre . '_inv_maestro_articulos.id_unidad')
                    ->select('id_articulo','codigo_sap',$pre . '_inv_maestro_articulos.nombre','inv_cat_unidades.nombre as nombreUnidad')
                    ->get();

            $bodegas = DB::table($pre . '_inv_bodegas')
                    ->where('id_tipo','B3')
                    ->where('id_estado','A')
                    ->select('id_bodega','nombre')
                    ->get();

            $versionActual = DB::table('gop_formularios_creacion')
                                ->where('tipo_formulario',6)
                                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formulario = DB::table( 'gop_formularios_creacion')
                        ->select('tipo_formulario as tipo_f','item_num as item','id_pregunta as id','descrip_pregunta as des','obligatorio as obli','tipo_control as tip','nombre_corto','id_padre as padre','version as ver')
                        ->whereIn('tipo_formulario',[6])
                        ->where('version',$versionActual)
                        ->orderBy('tipo_formulario')
                        ->orderBy('id_pregunta')
                        ->get();

            $versionActual = DB::table('gop_formularios_creacion')
                    ->where('tipo_formulario',16)
                    ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formulario1 = DB::table( 'gop_formularios_creacion')
                        ->select('tipo_formulario as tipo_f','item_num as item','id_pregunta as id','descrip_pregunta as des','obligatorio as obli','tipo_control as tip','nombre_corto','id_padre as padre','version as ver')
                        ->whereIn('tipo_formulario',[16])
                        ->where('version',$versionActual)
                        ->orderBy('tipo_formulario')
                        ->orderBy('id_pregunta')
                        ->get();

            $array = [];
            array_push($array, ["actividades" => $actividades, "materiales" => $materiales,
             "bodegas" => $bodegas,"form" => $formulario, "form2" => $formulario1]);

            return response()->json($array);
        }


        if($opc == "3") //Consulta OT
        {
            $pre = $request->all()["pre"];
            $lider = $request->all()["lider"];
            $fecha = $this->fechaShort;
            //$fecha = "2017-07-17";

            $tipoP = "T01";
            if(isset($request->all()['tipo_proyecto']))
                $tipoP = $request->all()['tipo_proyecto'];
            
            $personasCuadrilla = [];

            $fecha_ultima = strtotime ( '+4 days' , strtotime ( $fecha ) ) ;
            $fecha_ultima = date ( 'Y-m-j' , $fecha_ultima );

            if($tipoP == "T02")
            {
                $personasCuadrilla = DB::table($pre . '_gop_cuadrilla')                
                                ->where('id_supervisor',$lider)
                                ->join('rh_personas','identificacion','=','id_lider')
                                ->select(DB::raw("(id_movil + ' - ' + nombres + ' ' + apellidos) as nombre"),'id_lider')
                                ->get();

                $ordenes = [];

                //Consulta ordenes de la cuadrilla
                foreach ($personasCuadrilla as $key => $value) {
                    array_push($ordenes,DB::table($pre . '_gop_ordenes as tbl1')
                        ->where('tbl1.fecha_ejecucion','<=',$fecha . " 00:00:00")
                        ->where('tbl1.fecha_ejecucion_final','>=',$fecha . " 23:59:00")
                        ->where('tbl2.id_lider',$value->id_lider)
                        ->where('tbl2.id_estado',0)
                        ->whereIn('tbl1.id_estado',['E2','A1'])
                        ->join($pre . '_gop_ordenes_manoobra_detalle as tbl2','tbl2.id_orden','=','tbl1.id_orden')
                        ->join($pre . '_gop_proyectos as tbl3','tbl3.id_proyecto','=','tbl2.id_proyecto')
                        ->join($pre . '_gop_circuitos as tbl4','tbl4.id_circuito','=','tbl3.cod_cto')
                        ->select('tbl1.id_orden','tbl1.gom','tbl1.hora_ini','tbl1.hora_corte','tbl1.hora_fin',
                            'tbl1.hora_cierre','tbl1.descargo','tbl1.descargo2','tbl1.supervisor','tbl1.supervisor_ejecutor',
                            'tbl3.nombre','tbl1.observaciones','tbl4.nombre_cto',DB::raw("'' as nodosU"),
                            'tbl1.direccion','tbl1.nodos_utilizados','tbl1.fecha_ejecucion','tbl1.id_estado as estadoOrden',
                            'tbl2.id_estado as estaO','tbl2.id_lider')
                        ->get());   
                }

                for ($i=0; $i < count($ordenes); $i++) { 
                    $nodosUtil = "";
                    $ordenAux = $ordenes[$i];                    
                    foreach ($ordenAux as $key => $value) {                            

                            //$value->fecha_orden = explode(" ",$value->fecha_ejecucion)[0];  
                            $value->fecha_orden = $fecha;  
                                
                            $ordenT = $value->id_orden;
                            $wbsUtil = DB::table($pre . '_gop_ws_gom as tbl1')
                                        ->join($pre . '_gop_ws as tbl2','tbl1.id_ws','=','tbl2.id_ws')
                                        ->where('tbl1.id_gom',$value->gom)
                                        ->groupBy('tbl2.nombre_ws')
                                        ->value('tbl2.nombre_ws');
                            
                            $value->wbsU = $wbsUtil;  

                            $actividades =  " 
                                    select [tbl1].[id_baremo], [tbl1].[id_nodo], [tbl1].[cantidad_confirmada], 
                                    SUM(mobra.cantidad) as cantEje 
                                        from [rds_gop_ordenes_manoobra] 
                                    as [tbl1] inner join [rds_gop_baremos] as [bare] on 
                                    [bare].[codigo] = [tbl1].[id_baremo] left join [rds_gop_mobra] as 
                                    [mobra] on [mobra].[id_origen] = [tbl1].[id_personaCargo] 
                                    and [mobra].[id_nodo] = tbl1.id_nodo 
                                    and [mobra].[id_orden] = tbl1.id_orden 
                                    and [mobra].[id_baremo] = bare.id_baremo 
                                    and [mobra].[fecha] <> '$fecha' where [tbl1].[id_orden] = '$ordenT' 
                                    and [tbl1].[id_personaCargo] = '" . $value->id_lider . "' 
                                    and [tbl1].[id_estado] = 0 and [tbl1].[tipo_ingreso] = 0 
                                    and [periodo] = 2017 group by [tbl1].[id_baremo], 
                                    [tbl1].[id_nodo], [tbl1].[cantidad_confirmada]
                                    ";
                            
                               // dd($actividades);
                            $actividades = DB::select($actividades);

                            $value->actividadesR = $actividades;  

                            $materiales = DB::table($pre . '_gop_ordenes_materiales_documentos as tbl1')
                                        ->join($pre . '_inv_detalle_documentos as tbl2','tbl1.id_documento','=','tbl2.id_documento')
                                        ->where('tbl1.id_orden',$value->id_orden)
                                        ->where('tbl1.id_lider',$value->id_lider)
                                        ->select('tbl2.id_nodo','tbl2.cantidad','tbl2.id_articulo','tbl2.reintegro','tbl2.id_documento')
                                        ->groupBy('tbl2.id_nodo','tbl2.cantidad','tbl2.id_articulo','tbl2.reintegro','tbl2.id_documento')
                                        ->get();


                            foreach ($materiales as $k => $v) {
                                if($v->reintegro != null)
                                    $v->cantidad = $v->cantidad - $v->reintegro;
                            }

                            $value->materialesR = $materiales;  


                            $nodosUtil = DB::table($pre . '_gop_ordenes_manoobra as tbl1')
                                        ->join($pre . '_gop_nodos as tbl2','tbl1.id_nodo','=','tbl2.id_nodo')
                                        ->where('tbl1.id_orden',$value->id_orden)
                                        ->select('tbl1.id_nodo','tbl2.nombre_nodo')
                                        ->groupBy('tbl1.id_nodo','tbl2.nombre_nodo')
                                        ->orderBy('tbl2.nombre_nodo')
                                        ->get();

                            $nodosUtilM = DB::table($pre . '_gop_ordenes_materiales as tbl1')
                                        ->join($pre . '_gop_nodos as tbl2','tbl1.id_nodo','=','tbl2.id_nodo')
                                        ->where('tbl1.id_orden',$value->id_orden)
                                        ->select('tbl1.id_nodo','tbl2.nombre_nodo')
                                        ->orderBy('tbl2.nombre_nodo')
                                        ->groupBy('tbl1.id_nodo','tbl2.nombre_nodo')
                                        ->get();


                            //dd($nodosUtilM);
                            $arregloAc = [];
                            foreach ($nodosUtil as $ke => $val) {
                                $existNo = 0;
                                $nodoNuevo = null;
                                foreach ($nodosUtilM as $k => $v) {
                                    if($v->id_nodo == $val->id_nodo)
                                    {
                                        $existNo = 1;
                                        $nodoNuevo = null;
                                    }
                                    else
                                    {
                                        array_push($arregloAc,$v);
                                        $nodoNuevo = $v;
                                    }
                                }

                                if($existNo == 0 && $nodoNuevo != null)
                                {

                                    
                                    $nodoNoExiste = [];
                                    for ($iA=0; $iA < count($arregloAc); $iA++) { 
                                        if($arregloAc[$iA] != $nodoNuevo->id_nodo)
                                            array_push($nodoNoExiste,$arregloAc[$iA]);
                                    }


                                    for ($iB=0; $iB < count($nodoNoExiste); $iB++) {
                                        $ex = 0;    
                                        for ($j=0; $j < count($nodosUtil); $j++) 
                                        {
                                            if($nodosUtil[$j]->id_nodo == $nodoNoExiste[$iB]->id_nodo)
                                                $ex = 1;
                                        }

                                        if($ex == 0)
                                        {
                                            array_push($nodosUtil,$nodoNoExiste[$iB]);
                                        }   
                                    }
                                }
                            }

                            if(count($nodosUtil) == 0)
                            {
                                $nodosUtil = DB::table($pre . '_gop_ordenes_materiales_documentos as tbl1')
                                        ->join($pre . '_gop_nodos as tbl2','tbl1.id_nodo','=','tbl2.id_nodo')
                                        ->where('tbl1.id_orden',$value->id_orden)
                                        ->where('tbl1.id_lider',$value->id_lider)
                                        ->select('tbl1.id_nodo','tbl2.nombre_nodo')
                                        ->groupBy('tbl1.id_nodo','tbl2.nombre_nodo')
                                        ->get();                    
                            }

                            $value->nodosU = $nodosUtil;

                            //Consulta DC asociados

                            $dcAsociados = DB::table($pre . '_gop_ordenes_materiales_documentos as tbl1')
                                        ->join($pre . '_inv_documentos as tbl3','tbl1.id_documento','=','tbl3.id_documento')
                                        ->join('inv_cat_estado_documentos as tbl2','tbl3.id_estado','=','tbl2.id_estado')
                                        ->where('tbl1.id_orden',$value->id_orden)
                                        ->where('tbl1.id_lider',$value->id_lider)
                                        ->where('tbl3.id_estado','<>','A1')
                                        ->where('tbl3.id_estado','<>','E1')
                                        ->select('tbl1.id_documento','tbl3.observaciones','tbl2.nombre as estado','tbl3.id_estado')
                                        ->get();  

                            $value->dc = $dcAsociados;


                            $restricciones = DB::table($pre . '_gop_restriccionesProyecto as tbl1')
                                                ->join($pre . '_gop_tipo_restriccion as tbl2','tbl1.texto_restriccion','=','tbl2.id_tipo_restriccion')
                                                ->where('id_orden',$value->id_orden)
                                                ->where('id_estado','<>','X')
                                                ->get(['tbl2.nombre as nombreR','tbl1.impacto','tbl1.responsable'
                                                    ,'tbl1.id_estado','tbl1.restriccion_descripcion','tbl1.id_restriccion']);

                            $value->restric = $restricciones;
                    }


                }
            }
            else
            {
                $ordenes = DB::table($pre . '_gop_ordenes as tbl1')
                    ->whereBetween('tbl1.fecha_ejecucion',[$fecha . " 00:00:00", $fecha_ultima . " 23:59:59"])
                    ->where('tbl2.id_lider',$lider)
                    ->where('tbl2.id_estado',0)
                    ->whereIn('tbl1.id_estado',['E2','A1'])
                    ->join($pre . '_gop_ordenes_manoobra_detalle as tbl2','tbl2.id_orden','=','tbl1.id_orden')
                    ->join($pre . '_gop_proyectos as tbl3','tbl3.id_proyecto','=','tbl2.id_proyecto')
                    ->join($pre . '_gop_circuitos as tbl4','tbl4.id_circuito','=','tbl3.cod_cto')
                    ->select('tbl1.id_orden','tbl1.gom','tbl1.hora_ini','tbl1.hora_corte','tbl1.hora_fin',
                        'tbl1.hora_cierre','tbl1.descargo','tbl1.descargo2','tbl1.supervisor','tbl1.supervisor_ejecutor',
                        'tbl3.nombre','tbl1.observaciones','tbl4.nombre_cto',DB::raw("'' as nodosU"),
                        'tbl1.direccion','tbl1.nodos_utilizados','tbl1.fecha_ejecucion','tbl1.id_estado as estadoOrden',
                        'tbl2.id_estado as estaO')
                    ->get();    

                        $nodosUtil = "";
                        foreach ($ordenes as $key => $value) {
                          
                                $value->fecha_orden = explode(" ",$value->fecha_ejecucion)[0];  
                                $ordenT = $value->id_orden;
                                $wbsUtil = DB::table($pre . '_gop_ws_gom as tbl1')
                                            ->join($pre . '_gop_ws as tbl2','tbl1.id_ws','=','tbl2.id_ws')
                                            ->where('tbl1.id_gom',$value->gom)
                                            ->groupBy('tbl2.nombre_ws')
                                            ->value('tbl2.nombre_ws');
                                
                                $value->wbsU = $wbsUtil;  
                                $actividades = DB::table($pre . '_gop_ordenes_manoobra as tbl1')
                                            ->where('tbl1.id_orden',$ordenT)
                                            ->where('tbl1.id_personaCargo',$lider)
                                            ->where('tbl1.id_estado',0)
                                            ->where('tbl1.tipo_ingreso',0)
                                            ->select('tbl1.id_baremo','tbl1.id_nodo','tbl1.cantidad_confirmada')
                                            ->get();

                                $value->actividadesR = $actividades;  

                                $materiales = DB::table($pre . '_gop_ordenes_materiales_documentos as tbl1')
                                            ->join($pre . '_inv_detalle_documentos as tbl2','tbl1.id_documento','=','tbl2.id_documento')
                                            ->where('tbl1.id_orden',$value->id_orden)
                                            ->where('tbl1.id_lider',$lider)
                                            ->select('tbl2.id_nodo','tbl2.cantidad','tbl2.id_articulo','tbl2.reintegro','tbl2.id_documento')
                                            ->groupBy('tbl2.id_nodo','tbl2.cantidad','tbl2.id_articulo','tbl2.reintegro','tbl2.id_documento')
                                            ->get();

                                foreach ($materiales as $k => $v) {
                                    if($v->reintegro != null)
                                        $v->cantidad = $v->cantidad - $v->reintegro;
                                }

                                $value->materialesR = $materiales;  


                                $nodosUtil = DB::table($pre . '_gop_ordenes_manoobra as tbl1')
                                            ->join($pre . '_gop_nodos as tbl2','tbl1.id_nodo','=','tbl2.id_nodo')
                                            ->where('tbl1.id_orden',$value->id_orden)
                                            ->select('tbl1.id_nodo','tbl2.nombre_nodo')
                                            ->groupBy('tbl1.id_nodo','tbl2.nombre_nodo')
                                            ->orderBy('tbl2.nombre_nodo')
                                            ->get();

                                $nodosUtilM = DB::table($pre . '_gop_ordenes_materiales as tbl1')
                                            ->join($pre . '_gop_nodos as tbl2','tbl1.id_nodo','=','tbl2.id_nodo')
                                            ->where('tbl1.id_orden',$value->id_orden)
                                            ->select('tbl1.id_nodo','tbl2.nombre_nodo')
                                            ->orderBy('tbl2.nombre_nodo')
                                            ->groupBy('tbl1.id_nodo','tbl2.nombre_nodo')
                                            ->get();
                                //dd($nodosUtilM);
                                $arregloAc = [];
                                foreach ($nodosUtil as $key => $val) {
                                    $existNo = 0;
                                    $nodoNuevo = null;
                                    foreach ($nodosUtilM as $k => $v) {
                                        if($v->id_nodo == $val->id_nodo)
                                        {
                                            $existNo = 1;
                                            $nodoNuevo = null;
                                        }
                                        else
                                        {
                                            array_push($arregloAc,$v);
                                            $nodoNuevo = $v;
                                        }
                                    }

                                    if($existNo == 0 && $nodoNuevo != null)
                    