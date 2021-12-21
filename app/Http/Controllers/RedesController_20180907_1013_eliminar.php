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
        /*

        $this->tblAux = "rds_gop_";
        $this->tblAux1 = "rds_";
        
        Session::put('proy_short',$this->tblAux1);
        Session::put('proy_long',$this->tblAux);
        Session::put('user_login',"U01852");
             */
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
                     . Session::get('fecha1') . "','" . Session::get('fecha2') . "','" . Session::get('tipo') . "','" . Session::get('tproceso') . "'" ;

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
                     . "" . "','" . "" . "','','0'";
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

        

        
        ////////////////////////////////////////////////
        
        
        
      
        $permisoDESC = self::consultaAcceso('OP103'); //BANDEJA DE GESTIÓN DE DESCARGOS
        $materiales = self::consultaAcceso('OP104');//BANDEJA DE GESTIÓN DE MATERIALES

        // W -> Total
        // R -> Restringuido
        // N -> No tiene acceso
        $pdescargos=0;
        if(strlen($permisoDESC) > 0){  
          if($permisoDESC == "W"){$pdescargos=1;  }
        } 
        $pmateriales=0;
        if(strlen($materiales) > 0){  
            if($materiales == "W"){$pmateriales=1;  }
        } 
        
        
       $consulta="  select 
                        tipo_proceso 
                    from 
                        rds_gop_proyectos 
                    where 
                         tipo_proceso is not null
                    group by tipo_proceso ";
         $tproceso = DB::select($consulta);

        
        ////////////////////////////////////////////////

        return view('proyectos.redes.trabajoprogramado.index',array(
            'pdescargos' => $pdescargos,
            "tproceso" =>$tproceso,
            'pmateriales' => $pmateriales, 
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
            "permisoversion" => self::consultaAcceso('OP102'),
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
           
         $consulta="  select 
                        tipo_proceso 
                    from 
                        rds_gop_proyectos 
                    where 
                         tipo_proceso is not null
                    group by tipo_proceso ";
         $tproceso = DB::select($consulta);
        
        
        
        
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
                            ->join($this->tblAux . 'ordenes_manoobra_detalle as tbl1' ,$this->tblAux . 'ordenes.id_orden','=', 'tbl1.id_orden');
                           
            if(Session::has('estadonodo') && Session::get('estadonodo')!="0" ){
                  $ordeneRealizada=$ordeneRealizada->leftJoin($this->tblAux . 'ordenes_manoobra as tbl3' ,function($join)
                            {
                                $join->on('tbl1.id_orden','=','tbl3.id_orden')
                                     ->on('tbl3.id_personaCargo','=','tbl1.id_lider');
                            }); 
                            
                    if(Session::get('estadonodo')==""){
                       // ->where('tbl1.id_estado',0)
                        $datito=Session::get('estadonodo');
                        $ordeneRealizada=$ordeneRealizada->where(function($query) use ($datito){
        
                            $query->whereNull('tbl3.id_estado_nodo')
                                  ->orWhere('tbl3.id_estado_nodo',$datito);
                            
                        });
                                                
                    }else{
                       $ordeneRealizada=$ordeneRealizada->where('tbl3.id_estado_nodo',Session::get('estadonodo'));
                    }        
                            
            }
                            //->join($this->tblAux1 . 'inv_bodegas as tbl2' ,'tbl1.id_lider','=', 'tbl2.id_bodega')
                            /*->leftJoin($this->tblAux . 'ordenes_manoobra as tbl3' ,function($join)
                            {
                                $join->on('tbl1.id_orden','=','tbl3.id_orden')
                                    ->on('tbl3.id_personaCargo','=','tbl1.id_lider');
                            })
                            ->join($this->tblAux . 'baremos as tbl4' ,'tbl4.codigo','=', 'tbl3.id_baremo')*/
            $ordeneRealizada=$ordeneRealizada->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto','LIKE',"%" . $proy . "%")
                            ->where($this->tblAux . 'proyectos'  . $this->valorT .'.nombre','LIKE',"%" . $proyN . "%")
                            ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_tipo','T56')
                            ->where('tbl1.id_estado',0);
                            //->where('periodo',2017)
                            
            if(Session::has('estadonodo') && Session::get('estadonodo')!="0" ){
                
                $ordeneRealizada=$ordeneRealizada->select('gop_estado_ordenes.nombre as id_estadoN','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado','fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',
                                'cd','nivel_tension',$this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                $this->tblAux . 'proyectos'  . $this->valorT .'.nombre as nombreP',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.nodos_utilizados',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.descargo',
                                $this->tblAux . 'ordenes_manoobra.id_estado_nodo' 
                                //,'tbl1.id_lider','tbl2.nombre'
                                //,'"" as id_lider','"" as nombre'
                                //,DB::raw('SUM(tbl4.precio * tbl3.cantidad_confirmada) as cantidad')
                                );
            }else{
                
                $ordeneRealizada=$ordeneRealizada->select('gop_estado_ordenes.nombre as id_estadoN','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado','fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',
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
            }

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
               // "tproceso" =>$tproceso,
                "mtpro" =>0,
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
                "permisoversion" => self::consultaAcceso('OP102'),
                "opc" => 2
                ));

        }
        $proyectos = DB::table($this->tblAux . 'tipo_proyecto')
                        ->get(['id_proyecto','des_proyecto']);
           
         
        return view('proyectos.redes.trabajoprogramado.indexOrdenes',array(
            'tipos' => $tip,
            'estad' => $estado,
            //"tproceso" =>$tproceso,
            "mtpro" =>0,
            'ordenesRealizada' => $ordenesRealizada,
            'gom' => [],
            "prog" => null,
            'proyec' => "",
            "fecha" => $fechaActual,
            "fecha2" => $nuevafecha,
            "comboxP" => [],
            "proyecto" => $proyectos,
            "ejecucionB" => null,
            "permisoversion" => self::consultaAcceso('OP102'),
            "encabezado" => null,
            "index" => 1,
            "opc" => 2
            ));
    }

    public function reArrayFiles(&$file_post) {
      $file_ary   = array();
      $file_count = count($file_post['name']);
      $file_keys  = array_keys($file_post);

      for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
          $file_ary[$i][$key] = $file_post[$key][$i];
        }
      }

      return $file_ary;
    }

    public function subirArchivoFtp($servidor, $archivo_origen, $archivo_destino, $usuario_ftp, $clave_ftp) {
      $pwdftp = $clave_ftp;
      $userftp = $usuario_ftp;

      $lccx = ftp_connect($servidor);
      ftp_pasv($lccx, true);
      
      $resultado = ftp_login($lccx, $userftp, $pwdftp);

      if ((!$lccx) || (!$resultado)) {
        return 'No se puede conectar con el Servidor de Archivos... Comuniquese con el Administrador';
      } 
      else {
        ftp_pasv($lccx, true);
        $upload = ftp_put($lccx, $archivo_destino, $archivo_origen, FTP_BINARY);
        $captura_error = error_get_last();

        $error_msg = "<BR>Servidor: $servidor<BR>FTP Conect: $lccx<BR>Archivo origen: $archivo_origen<BR>Archivo Destino: $archivo_destino<BR>Usuario FTP: $usuario_ftp";
        
        if (!$upload) {
          $msg = "No se logró completar la transferencia del Archivo<br>Mensaje de Error: " . $captura_error['message'] . $error_msg;
          return $msg;
        }

        ftp_close($lccx);
      }
    }

    /**
    * @author Edwin A. Triviños R.
    * @date Junio 14, 2018
    */ 
    public function guardarEventoBitacoraOrden(Request $request) {
      $error = false;
      $mensaje = '';

      $bitacora__id_evento      = $request->all()["bitacora__id_evento"];
      $bitacora__id_orden       = $request->all()["bitacora__id_orden"];
      $bitacora__fecha_evento   = $request->all()["bitacora__fecha_evento"];
      $bitacora__observaciones  = $request->all()["bitacora__observaciones"];

      $archivos = $_FILES['bitacora__archivo_evento'];

      // ================================================================================================
      // Se crean los archivos
      // ================================================================================================
      $nombre_archivo_ftp = '';

      if(!$error && is_array($archivos) && count($archivos) > 0) {
        $archivos = $this->reArrayFiles($archivos);

        if(is_array($archivos) && count($archivos) > 0) {
          
          foreach($archivos as $archivo) {
            $error_creando_archivo = false;

            $error_archivo    = $archivo['error'];
            $nombre_archivo   = $archivo['name'];

            $nombre_temporal_archivo  = $archivo['tmp_name'];

            // Se lee el fichero
            if($nombre_temporal_archivo) {
              $fecha_actual = date('Ymd_His');
              $contenido_archivo = file_get_contents($nombre_temporal_archivo);

              // Se escribe el fichero
              if($contenido_archivo === FALSE) {
                $error_creando_archivo = true;
                $error = true;
                $mensaje = "Ha ocurrido un error al leer el archivo.";
              }
              else {
                $ftp_server     = "190.60.248.195";
                $ftp_user_name  = "usuario_ftp";
                $ftp_user_pass  = "74091450652!@#1723cc";
                $ruta_eventos_bitacora = "/telco/bitacora_orden/";

                $nombre_archivo_ftp = "{$bitacora__id_orden}_{$bitacora__id_evento}_".date('Ymd')."_{$nombre_archivo}";
                $ruta_archivo_ftp = "{$ruta_eventos_bitacora}{$nombre_archivo_ftp}";

                $mensaje = $this->subirArchivoFtp($ftp_server, 
                            $nombre_temporal_archivo, 
                            $ruta_archivo_ftp, 
                            $ftp_user_name, 
                            $ftp_user_pass, 
                            $ajax = true);

                if($mensaje) {
                  $error = true;
                }
              }
            }

          }
        }
      }

      // ================================================================================================
      // Se crea/actualiza el evento de la bitácora de la orden
      // ================================================================================================
      if(!$error) {
        $fecha_actual = date('Y-m-d H:i:s');

        // =========================================================
        // Se crea el evento de la bitácora de la orden
        // =========================================================
        if($bitacora__id_evento) {
          $data_actualizacion = array(
            'fecha_evento'        =>  $bitacora__fecha_evento,
            'id_usuario'          =>  Session::get('user_login'),
            'descripcion'         =>  $bitacora__observaciones,
            'fecha_actualizacion' =>  $fecha_actual
          );

          if($nombre_archivo_ftp) {
            $data_actualizacion['ruta_archivo'] = $nombre_archivo_ftp;
          }

          DB::table('tel_gop_bitacora')
            ->where('id_evento', $bitacora__id_evento)
            ->update($data_actualizacion);
        }
        // =========================================================
        // Se actualiza el evento de la bitácora de la orden
        // =========================================================
        else {
          $data_creacion = array(
            'id_orden'            =>  $bitacora__id_orden,
            'fecha_evento'        =>  $bitacora__fecha_evento,
            'id_usuario'          =>  Session::get('user_login'),
            'descripcion'         =>  $bitacora__observaciones,
            'fecha_creacion'      =>  $fecha_actual,
            'fecha_actualizacion' =>  $fecha_actual,
            'ruta_archivo'        =>  $nombre_archivo_ftp
          );

          DB::Table('tel_gop_bitacora')  
            ->insert(array($data_creacion));
        }
      }

      // ================================================================================================
      // Se envía la respuesta al usuario
      // ================================================================================================
      return response()->json([
        'error'     =>  $error,
        'mensaje'   =>  $mensaje
      ]); 
    }

    public function eliminarEventoBitacoraOrden(Request $request) {
      $error = false;
      $mensaje = '';

      $bitacora__id_evento      = $request->all()["bitacora__id_evento"];

      // ================================================================================================
      // Se elimina el evento
      // ================================================================================================
      if($bitacora__id_evento) {
        DB::table('tel_gop_bitacora')
          ->where('id_evento', $bitacora__id_evento)
          ->delete();
      }
      else {
        $error = true;
        $mensaje = "Por favor seleccione un evento a eliminar.";
      }

      // ================================================================================================
      // Se envía la respuesta al usuario
      // ================================================================================================
      return response()->json([
        'error'     =>  $error,
        'mensaje'   =>  $mensaje
      ]); 
    }

    public function actualizarGOMProyecto(Request $request) {
      $error = false;
      $mensaje = '';

      $id_proyecto  = $request->all()["id_proyecto"];
      $gom_actual   = $request->all()["gom_actual"];
      $gom_nueva    = $request->all()["gom_nueva"];

      // ================================================================================================
      // Se elimina el evento
      // ================================================================================================
      if($id_proyecto && $gom_actual && $gom_nueva) {
        /*
        // $this->tblAux // rds_gop_
        DB::table('tel_gop_bitacora')
            ->where('id_evento', $bitacora__id_evento)
            ->update($data_actualizacion);
        */
      }
      else {
        $error = true;
        $mensaje = "Por favor ingrese todos los valores, son obligatorios.";
      }

      // ================================================================================================
      // Se envía la respuesta al usuario
      // ================================================================================================
      return response()->json([
        'error'     =>  $error,
        'mensaje'   =>  $mensaje
      ]); 
    }
       
    //consulta estado de nodo
    public function consultaestadonodo(Request $request){
        
        $id_nodo = $request->all()['id_nodo'];
        $consulta=" select 
                         top 1 id_estado_nodo 
                    from 
                         rds_gop_ordenes_manoobra
                    where 
                         id_nodo='".$id_nodo."' order by id_estado_nodo asc
         ";
         $tproceso = DB::select($consulta);
        
         $respuesta="";
         
         if(isset($tproceso[0])  && isset($tproceso[0]->id_estado_nodo) ){
            $respuesta=  $tproceso[0]->id_estado_nodo;
         }
         
         $consultads="select top 1 planilla,gom from rds_gop_nodos where id_nodo='".$id_nodo."'";
         $planilla = DB::select($consultads);
         
         $plaenv="0";
         $gom="0";
         if( isset($planilla[0]) && isset($planilla[0]->gom)  && $planilla[0]->gom!= null && trim($planilla[0]->gom)!=""){
            $gom=trim($planilla[0]->gom);
            if( isset($planilla[0]->planilla) && $planilla[0]->planilla!= null && trim($planilla[0]->planilla)!=""){
               $plaenv=trim($planilla[0]->planilla);
            }
             
         }
         
          /*          
         if($tproceso){
             foreach ($tproceso as $tp){
                 $respuesta=$tp->id_estado_nodo;
             }
         }*/
         
          return response()->json(['status'=>1,'estado'=>$respuesta,'gom'=> $gom,'plantilla'=> $plaenv]); 
    }
    
    //cambiaestado estado de nodo
    public function cambiaestado(Request $request){
        
        $id_nodo = $request->all()['id_nodo'];
        $estadon = $request->all()['estadon'];
        $planilla = $request->all()['planilla'];
        
        
        $cont = DB::Table($this->tblAux . 'ordenes_manoobra')
                ->where('id_nodo',$id_nodo)
                ->count();

        if($cont == 0){
            return response()->json(['status'=>1,'message'=>'Nodo no encontrado' ]);
        }

        
        if($planilla==0 || trim($planilla)=='0' || trim($planilla)==''){
            DB::table($this->tblAux . 'nodos')
                    ->where('id_nodo',$id_nodo)
                    ->whereNotNull('gom')
                    ->where('gom','<>','')
                    ->update(
                        array(
                            'planilla' =>null
                            )); 
        }else{
             DB::table($this->tblAux . 'nodos')
                    ->where('id_nodo',$id_nodo)
                    ->whereNotNull('gom')
                    ->where('gom','<>','')
                    ->update(
                        array(
                            'planilla' =>$planilla
                            )); 
        }
        //$update="update rds_gop_nodos set planilla=".$planilla." where id_nodo='".$id_nodo."' and gom is not null and gom <> ''";
        
           DB::table($this->tblAux . 'nodos')
                    ->where('id_nodo',$id_nodo)
                    ->whereNotNull('gom')
                    ->where('gom','<>','')
                    ->update(
                        array(
                            'planilla' => $planilla
                            )); 

        
        
        
        DB::table($this->tblAux . 'ordenes_manoobra')
        ->where('id_nodo',$id_nodo)
        ->update(array(
            'id_estado_nodo' => $estadon
        ));
        
            
        $ordendet = DB::Table($this->tblAux . 'ordenes_manoobra')
                    ->where('id_nodo',$id_nodo)
                    ->select('id_orden')
                    ->first();
        $idorden= $ordendet->id_orden;
        
        
        //////////////////////////////////////////////
        
         $estadoNodosArray = DB::Table($this->tblAux . 'ordenes_manoobra')
                    ->where('id_orden',$idorden)
                     ->where('id_nodo','like','N%')
                    ->get(['id_estado_nodo']);
                                //    ->where('id_nodo','like','N%')
                                //    ->get(['id_estado_nodo']);

           $totalNodos = count($estadoNodosArray);
                $totalEjecutas = 0;
                $totalReprogramdas = 0;
                $totalCanceladas = 0;

                foreach ($estadoNodosArray as $key => $value) {
                    
                    if($value->id_estado_nodo == "E")
                        $totalEjecutas++;

                    if($value->id_estado_nodo == "R")
                        $totalReprogramdas++;

                    if($value->id_estado_nodo == "C")
                        $totalCanceladas++;
                }
                
                $estado = "E2";//programada para ejecucion
                
                if($totalEjecutas==$totalNodos || ($totalEjecutas+$totalCanceladas) ==$totalNodos){
                    /// esta en estado ejecutado
                    $estado = "E4";
                }else if($totalCanceladas == $totalNodos){
                    // esta en estado cancelado 
                    $estado = "C1";
                }else{
                    // esta en estado reprogramada 
                    $estado = "R0";
                }
               
                DB::table($this->tblAux . 'ordenes')
                            ->where('id_orden',$idorden)
                            ->update(
                                array(
                                    'id_estado' => $estado
                                )
                            );
                
               

         
        
        //////////////////////////////////////////////
     
         
         
          return response()->json(['status'=>1,'est' =>$estado]); 
    }
    
    
    
    
    public function generarExcelConsolidadoBaremos(Request $request)
    {

        //ini_set('memory_limit', '256M');
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        
        
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
         /*
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

                            ->leftjoin($this->tblAux . 'baremos as tbl4' ,'tbl4.codigo','=', 'tbl3.id_baremo')
                            

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


                            ->leftjoin($this->tblAux . 'nodos as nodo' ,'nodo.id_nodo','=', 'tbl3.id_nodo')

                            ->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto','LIKE',"%" . $proy . "%")
                            ->where($this->tblAux . 'proyectos'  . $this->valorT .'.nombre','LIKE',"%" . $proyN . "%")
                            ->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_tipo','T56')
                            ->where('tbl1.id_estado',0)
                          //  ->where('periodo',2017)
                            
                          //INICIO AUMENTADO 04-07-2018 emmanuel    
                           ->whereRaw("periodo = year(".$this->tblAux . "ordenes.fecha_ejecucion) ")        
                            ->where('cuadrigop.id_estado','A')
                            ->select('cuadrigop.id_movil','gop_estado_ordenes.nombre as id_estadoN','tbl1.id_orden',$this->tblAux . 'ordenes'  . $this->valorT . '.id_estado','fecha_emision','fecha_programacion','fecha_prevista_ejecucion','fecha_ejecucion',
                                $this->tblAux . 'ordenes.cd',$this->tblAux . 'ordenes.nivel_tension',$this->tblAux . 'ordenes.direccion',$this->tblAux . 'ordenes.gom',
                                $this->tblAux . 'proyectos'  . $this->valorT .'.nombre as nombreP',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.id_proyecto',
                                'nodo.nombre_nodo as nodos_utilizados',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.wbs_utilzadas',
                                $this->tblAux . 'ordenes'  . $this->valorT .'.descargo',
                                'tbl5.id_baremo as bareE','tbl5.cantidad as cantE','fecha_usuario_terreno_create',
                                'id_estado_nodo','fecha_actualiza_id_estado_nodo','fecha_facturacion',
                                DB::raw('(tbl5.precio * tbl5.cantidad) as valorEje'),
                                'tbl1.id_lider','tbl2.nombre',DB::raw('(tbl4.precio * tbl3.cantidad_confirmada) as cantidad'),
                                    'tbl4.codigo','tbl4.actividad as nombre_baremo','tbl3.cantidad_confirmada','tbl3.id_nodo'
                                );
               */
             $wheresql="";               
                            
            if($estado != "0")
            {
                // $ordeneRealizada = $ordeneRealizada->where($this->tblAux . 'ordenes'  . $this->valorT . '.id_estado',$estado);
                
                $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".id_estado = '".$estado."' ";
            }

            if($gom == "" && $orden == "")
            {
                //$ordeneRealizada = $ordeneRealizada->where($this->tblAux . 'proyectos'  . $this->valorT . '.tipo_proyecto',$tipo);
                
                $wheresql .= " and ".$this->tblAux."proyectos".$this->valorT.".tipo_proyecto = '".$tipo."' ";
            }

            if($gom != "")
            {
                //$ordeneRealizada = $ordeneRealizada->where($this->tblAux . 'ordenes'  . $this->valorT . '.gom',$gom);
                
                $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".gom = '".$gom."' ";
            }

            if($orden != "")
            {
               // $ordeneRealizada = $ordeneRealizada->where($this->tblAux . 'ordenes'  . $this->valorT .'.id_orden','LIKE',"%" . $orden . "%");
                $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".id_orden LIKE '%".$orden."%' ";
            }

            if($fecha1 != "" && $fecha2 != "" && $gom == "" && $orden == "")
            {
                // E2 Programada
                if($estado == "E4")
                {
                   // $ordeneRealizada = $ordeneRealizada ->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_ejecucion',[$fecha1,$fecha2]);  
                    $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".fecha_ejecucion >= '".$fecha1."' ";
                    $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".fecha_ejecucion <= '".$fecha2."' ";
                }
                else
                {
                    // E2 Programada
                    if($estado == "E1")
                    {
                        //$ordeneRealizada = $ordeneRealizada->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_programacion',[$fecha1,$fecha2]); 
                        
                        $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".fecha_programacion >= '".$fecha1."' ";
                        $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".fecha_programacion <= '".$fecha2."' ";
                    }
                    else
                    {
                        // E1 Generada
                        //$ordeneRealizada = $ordeneRealizada->whereBetween($this->tblAux . 'ordenes'  . $this->valorT . '.fecha_ejecucion',[$fecha1,$fecha2]); 
                       
                        $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".fecha_ejecucion >= '".$fecha1."' ";
                        $wheresql .= " and ".$this->tblAux."ordenes".$this->valorT.".fecha_ejecucion <= '".$fecha2."' ";      
                    }
                }
            }
/*
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
                                    'tbl4.codigo','tbl4.actividad','tbl3.cantidad_confirmada','tbl3.id_nodo',
                                    'id_estado_nodo','fecha_actualiza_id_estado_nodo','fecha_facturacion'
                                    )
                                ->get();
            */
          

            $sql=" 
                    select
                            DISTINCT
                            iif( tabla_programado.id_movil is null,tabla_ejecutado.id_movil, tabla_programado.id_movil) as id_movil,
                            iif( tabla_programado.tipoc is null,tabla_ejecutado.tipoc, tabla_programado.tipoc) as tipoc,
                            iif( tabla_programado.id_estadoN is null,tabla_ejecutado.id_estadoN, tabla_programado.id_estadoN) as id_estadoN,
                            iif( tabla_programado.id_orden is null,tabla_ejecutado.id_orden, tabla_programado.id_orden) as id_orden,
                            iif( tabla_programado.id_estado is null,tabla_ejecutado.id_estado, tabla_programado.id_estado) as id_estado,
                            iif( tabla_programado.fecha_emision is null,tabla_ejecutado.fecha_emision, tabla_programado.fecha_emision) as fecha_emision,
                            iif( tabla_programado.fecha_programacion is null,tabla_ejecutado.fecha_programacion, tabla_programado.fecha_programacion) as fecha_programacion,
                            iif( tabla_programado.fecha_prevista_ejecucion is null,tabla_ejecutado.fecha_prevista_ejecucion, tabla_programado.fecha_prevista_ejecucion) as fecha_prevista_ejecucion,
                            iif( tabla_programado.fecha_ejecucion is null,tabla_ejecutado.fecha_ejecucion, tabla_programado.fecha_ejecucion) as fecha_ejecucion,
                            iif( tabla_programado.cd is null,tabla_ejecutado.cd, tabla_programado.cd) as cd,
                            iif( tabla_programado.nivel_tension is null,tabla_ejecutado.nivel_tension, tabla_programado.nivel_tension) as nivel_tension,
                            iif( tabla_programado.direccion is null,tabla_ejecutado.direccion, tabla_programado.direccion) as direccion,
                            iif( tabla_programado.gom is null,tabla_ejecutado.gom, tabla_programado.gom) as gom_ant,
                            
                            
                            

                            iif( tabla_programado.gomn is not null,
                                        tabla_programado.gomn ,
                                          
                                        iif( tabla_ejecutado.gomn  is not null,tabla_ejecutado.gomn,
                                               iif( tabla_programado.gom is null,tabla_ejecutado.gom, tabla_programado.gom )
                                        )
                            

                               ) as gom,


                            iif( tabla_programado.nombreP is null,tabla_ejecutado.nombreP, tabla_programado.nombreP) as nombreP,
                            iif( tabla_programado.id_proyecto is null,tabla_ejecutado.id_proyecto, tabla_programado.id_proyecto) as id_proyecto,
                            iif( tabla_programado.nodos_utilizados is null,tabla_ejecutado.nodos_utilizados, tabla_programado.nodos_utilizados) as nodos_utilizados,
                            iif( tabla_programado.wbs_utilzadas is null,tabla_ejecutado.wbs_utilzadas, tabla_programado.wbs_utilzadas) as wbs_utilzadas,
                            iif( tabla_programado.descargo is null,tabla_ejecutado.descargo, tabla_programado.descargo) as descargo,
                            iif( tabla_programado.bareE is null,tabla_ejecutado.bareE, tabla_programado.bareE) as bareE,
                            iif( tabla_programado.cantE is null,tabla_ejecutado.cantE, tabla_programado.cantE) as cantE,
                            iif( tabla_programado.fecha_usuario_terreno_create is null,tabla_ejecutado.fecha_usuario_terreno_create, tabla_programado.fecha_usuario_terreno_create) as fecha_usuario_terreno_create,
                            iif( tabla_programado.id_estado_nodo is null,tabla_ejecutado.id_estado_nodo, tabla_programado.id_estado_nodo) as id_estado_nodo,
                            iif( tabla_programado.fecha_actualiza_id_estado_nodo is null,tabla_ejecutado.fecha_actualiza_id_estado_nodo, tabla_programado.fecha_actualiza_id_estado_nodo) as fecha_actualiza_id_estado_nodo,
                            iif( tabla_programado.fecha_facturacion is null,tabla_ejecutado.fecha_facturacion, tabla_programado.fecha_facturacion) as fecha_facturacion,
                            iif( tabla_programado.valorEje is null,tabla_ejecutado.valorEje, tabla_programado.valorEje) as valorEje,
                            iif( tabla_programado.id_lider is null,tabla_ejecutado.id_lider, tabla_programado.id_lider) as id_lider,
                            iif( tabla_programado.nombre is null,tabla_ejecutado.nombre, tabla_programado.nombre) as nombre,
                            iif( tabla_programado.cantidad is null,tabla_ejecutado.cantidad, tabla_programado.cantidad) as cantidad,
                            iif( tabla_programado.codigo is null,tabla_ejecutado.codigo, tabla_programado.codigo) as codigo,
                            iif( tabla_programado.nombre_baremo is null,tabla_ejecutado.nombre_baremo, tabla_programado.nombre_baremo) as nombre_baremo,
                            iif( tabla_programado.cantidad_confirmada is null,tabla_ejecutado.cantidad_confirmada, tabla_programado.cantidad_confirmada) as cantidad_confirmada,
                            iif( tabla_programado.id_nodo is null,tabla_ejecutado.id_nodo, tabla_programado.id_nodo) as id_nodo,
                            iif( tabla_programado.gom_nodo_ws_orden is null,tabla_ejecutado.gom_nodo_ws_orden, tabla_programado.gom_nodo_ws_orden) as gom_nodo_ws_orden,
                            iif( tabla_programado.descargos_con_corte is null,tabla_ejecutado.descargos_con_corte, tabla_programado.descargos_con_corte) as descargos_con_corte,
                            iif( tabla_programado.descargos_sin_corte is null,tabla_ejecutado.descargos_sin_corte, tabla_programado.descargos_sin_corte) as descargos_sin_corte

                    from
                        (
                            select
                                 cuadrigop.id_movil,
                                 tcua.nombre as tipoc,
                                 gop_estado_ordenes.nombre as id_estadoN,
                                 tbl1.id_orden,
                                 ".$this->tblAux."ordenes".$this->valorT.".id_estado,
                                 fecha_emision,
                                 fecha_programacion,
                                 fecha_prevista_ejecucion,
                                 fecha_ejecucion,
                                 ".$this->tblAux."ordenes".$this->valorT.".cd,
                                 ".$this->tblAux."ordenes".$this->valorT.".nivel_tension,
                                 ".$this->tblAux."ordenes".$this->valorT.".direccion,
                                 ".$this->tblAux."ordenes".$this->valorT.".gom,
                                 ".$this->tblAux."proyectos".$this->valorT.".nombre as nombreP,
                                 ".$this->tblAux."ordenes".$this->valorT.".id_proyecto,
                                 nodo.nombre_nodo as nodos_utilizados,
                                 ".$this->tblAux."ordenes".$this->valorT.".wbs_utilzadas,
                                 ".$this->tblAux."ordenes".$this->valorT.".descargo,
                                 tbl5.id_baremo as bareE,
                                 tbl5.cantidad as cantE,
                                 fecha_usuario_terreno_create,
                                 id_estado_nodo,
                                 fecha_actualiza_id_estado_nodo,
                                 fecha_facturacion,
                                 (tbl5.precio * tbl5.cantidad) as valorEje,
                                 tbl1.id_lider,
                                 tbl2.nombre,
                                 (tbl4.precio * tbl3.cantidad_confirmada) as cantidad,
                                 tbl4.codigo,
                                 tbl4.actividad as nombre_baremo,
                                 tbl3.cantidad_confirmada,
                                 tbl3.id_nodo,
                                 nodo.gom as gomn,

                                 CASE 
                                    -- nodo
                                    WHEN  
                                    (
                                        SELECT COUNT(*) FROM rds_gop_nodos AS nodo_local_1_programado
                                        WHERE nodo_local_1_programado.id_nodo = nodo.id_nodo 
                                        AND nodo_local_1_programado.id_proyecto = nodo.id_proyecto
                                        AND nodo_local_1_programado.gom IS NOT NULL 
                                        AND nodo_local_1_programado.gom <> '0'
                                        AND nodo_local_1_programado.gom <> ''
                                    ) = 1
                                    THEN (
                                        SELECT gom FROM rds_gop_nodos AS nodo_local_2_programado
                                        WHERE nodo_local_2_programado.id_nodo = nodo.id_nodo 
                                        AND nodo_local_2_programado.id_proyecto = nodo.id_proyecto 
                                        AND nodo_local_2_programado.gom IS NOT NULL 
                                        AND nodo_local_2_programado.gom <> '0'
                                        AND nodo_local_2_programado.gom <> ''
                                    )

                                    -- ws
                                    WHEN  
                                    (
                                        SELECT COUNT(*) FROM rds_gop_ws AS ws_local_1_programado
                                        WHERE ws_local_1_programado.id_ws = nodo.id_ws 
                                        AND ws_local_1_programado.id_proyecto = nodo.id_proyecto
                                        AND ws_local_1_programado.gom IS NOT NULL 
                                        AND ws_local_1_programado.gom <> '0'
                                        AND ws_local_1_programado.gom <> ''
                                    ) > 0
                                    THEN (
                                        SELECT TOP 1 gom FROM rds_gop_ws AS ws_local_2_programado
                                        WHERE ws_local_2_programado.id_ws = nodo.id_ws 
                                            AND ws_local_2_programado.id_proyecto = nodo.id_proyecto 
                                            AND ws_local_2_programado.gom IS NOT NULL 
                                            AND ws_local_2_programado.gom <> '0'
                                            AND ws_local_2_programado.gom <> ''
                                        ORDER BY ws_local_2_programado.gom ASC
                                    )

                                    -- ws gom
                                    WHEN  
                                    (
                                        SELECT COUNT(*) FROM rds_gop_ws_gom AS ws_gom_local_1_programado
                                        WHERE ws_gom_local_1_programado.id_ws = nodo.id_ws 
                                            AND ws_gom_local_1_programado.id_proyecto = nodo.id_proyecto
                                            AND ws_gom_local_1_programado.id_gom IS NOT NULL 
                                            AND ws_gom_local_1_programado.id_gom <> '0'
                                            AND ws_gom_local_1_programado.id_gom <> ''
                                    ) > 0
                                    THEN (
                                        SELECT TOP 1 id_gom FROM rds_gop_ws_gom AS ws_gom_local_2_programado
                                        WHERE ws_gom_local_2_programado.id_ws = nodo.id_ws 
                                            AND ws_gom_local_2_programado.id_proyecto = nodo.id_proyecto
                                            AND ws_gom_local_2_programado.id_gom IS NOT NULL 
                                            AND ws_gom_local_2_programado.id_gom <> '0'
                                            AND ws_gom_local_2_programado.id_gom <> ''
                                        ORDER BY ws_gom_local_2_programado.id_gom ASC
                                    )

                                    -- orden gom
                                    WHEN  
                                    (
                                        SELECT COUNT(*) FROM rds_gop_ordenes AS local_orden_1_programado
                                        WHERE local_orden_1_programado.id_orden = rds_gop_ordenes.id_orden
                                        AND local_orden_1_programado.id_proyecto = rds_gop_ordenes.id_proyecto
                                        AND local_orden_1_programado.gom IS NOT NULL 
                                        AND local_orden_1_programado.gom <> ''
                                    ) = 1
                                    THEN (
                                        SELECT gom FROM rds_gop_ordenes AS local_orden_2_programado
                                        WHERE local_orden_2_programado.id_orden = rds_gop_ordenes.id_orden
                                        AND local_orden_2_programado.id_proyecto = rds_gop_ordenes.id_proyecto
                                        AND local_orden_2_programado.gom IS NOT NULL 
                                        AND local_orden_2_programado.gom <> ''
                                    )

                                    ELSE 0
                                END AS gom_nodo_ws_orden,

                                CONCAT(
                                  IIF(rds_gop_ordenes.descargo IS NOT NULL AND rds_gop_ordenes.descargo <> 0, CONCAT(rds_gop_ordenes.descargo,','),''),
                                  IIF(rds_gop_ordenes.descargo2 IS NOT NULL AND rds_gop_ordenes.descargo2 <>0, CONCAT(rds_gop_ordenes.descargo2,','),''),
                                  IIF(rds_gop_ordenes.descargo3 IS NOT NULL AND rds_gop_ordenes.descargo3 <>0,CONCAT(rds_gop_ordenes.descargo3,','),'')
                                ) descargos_con_corte,

                                CONCAT(
                                  IIF(rds_gop_ordenes.descargo4 IS NOT NULL AND rds_gop_ordenes.descargo4 <>0,CONCAT(rds_gop_ordenes.descargo4,','),''),
                                  IIF(rds_gop_ordenes.descargo5 IS NOT NULL AND rds_gop_ordenes.descargo5 <>0,CONCAT(rds_gop_ordenes.descargo5,','),''),
                                  IIF(rds_gop_ordenes.descargo6 IS NOT NULL AND rds_gop_ordenes.descargo6 <>0,CONCAT(rds_gop_ordenes.descargo6,','),''),
                                  IIF(rds_gop_ordenes.descargo7 IS NOT NULL AND rds_gop_ordenes.descargo7 <>0,CONCAT(rds_gop_ordenes.descargo7,','),'')
                                ) AS descargos_sin_corte
                             from
                                    ".$this->tblAux."ordenes AS rds_gop_ordenes inner join gop_estado_ordenes on gop_estado_ordenes.id_estado = ".$this->tblAux."ordenes".$this->valorT.".id_estado
                                                      inner join ".$this->tblAux."proyectos on ".$this->tblAux."proyectos".$this->valorT.".id_proyecto = ".$this->tblAux."ordenes".$this->valorT.".id_proyecto
                                                      inner join ".$this->tblAux."ordenes_manoobra_detalle as tbl1 on ".$this->tblAux."ordenes".$this->valorT.".id_orden = tbl1.id_orden
                                                      inner join rds_inv_bodegas as tbl2 on tbl1.id_lider = tbl2.id_bodega
                                                      left join ".$this->tblAux."cuadrilla as cuadrigop on cuadrigop.id_lider = tbl1.id_lider
                                                      left join ".$this->tblAux."tipo_cuadrilla as tcua 
                                                               on (cuadrigop.id_tipo_cuadrilla=tcua.id_tipo_cuadrilla)   
                                                      inner join ".$this->tblAux."ordenes_manoobra as tbl3 on tbl1.id_orden = tbl3.id_orden and
                                                                                                       tbl3.id_personaCargo = tbl1.id_lider
                                                      left join ".$this->tblAux."baremos as tbl4 on tbl4.codigo = tbl3.id_baremo
                                                      left join ".$this->tblAux."mobra as tbl5 on tbl1.id_orden = tbl5.id_orden and
                                                                                         tbl5.id_origen = tbl1.id_lider and
                                                                                         tbl5.id_baremo = tbl4.id_baremo and
                                                                                         tbl5.id_nodo = tbl3.id_nodo
                                                      left join ".$this->tblAux."ordenes_mobra as mobra on tbl1.id_orden = mobra.id_orden and
                                                                                                  mobra.id_origen = tbl1.id_lider and
                                                                                                  mobra.id_baremo = tbl4.id_baremo and
                                                                                                  mobra.id_nodo = tbl3.id_nodo and 
                                                                                                  mobra.id_proyecto = rds_gop_proyectos.id_proyecto
                                                      left join ".$this->tblAux."nodos as nodo on nodo.id_nodo = tbl3.id_nodo
                            where
                                  ".$this->tblAux."ordenes".$this->valorT.".id_proyecto LIKE '%".$proy."%' and
                                  ".$this->tblAux."proyectos".$this->valorT.".nombre LIKE '%".$proyN."%' and
                                  ".$this->tblAux."ordenes".$this->valorT.".id_tipo = 'T56' and
                                  /* tbl1.id_estado = 0 and */
                                  periodo = year(".$this->tblAux."ordenes".$this->valorT.".fecha_ejecucion) /*and
                                  cuadrigop.id_estado = 'A' */
                                  ".$wheresql."
                            group by
                                  cuadrigop.id_movil, 
                                  gop_estado_ordenes.nombre, 
                                  tbl1.id_orden, 
                                  ".$this->tblAux."ordenes".$this->valorT.".id_estado,
                                  fecha_emision, 
                                  fecha_programacion, 
                                  fecha_prevista_ejecucion, 
                                  fecha_ejecucion, ".$this->tblAux."ordenes".$this->valorT.".cd,".
                                  $this->tblAux."ordenes".$this->valorT.".nivel_tension, ".
                                  $this->tblAux."ordenes".$this->valorT.".direccion, ".
                                  $this->tblAux."ordenes".$this->valorT.".gom,".
                                  $this->tblAux."proyectos".$this->valorT.".nombre, ".
                                  $this->tblAux."ordenes".$this->valorT.".id_proyecto, ".
                                  "nodo.nombre_nodo, ".
                                  $this->tblAux."ordenes".$this->valorT.".wbs_utilzadas,
                                  ".$this->tblAux."ordenes".$this->valorT.".descargo, ".
                                  "tbl1.id_lider, 
                                  tbl2.nombre, 
                                  tbl5.id_baremo, 
                                  tbl5.cantidad, 
                                  tbl5.precio,
                                  tbl5.cantidad, 
                                  fecha_usuario_terreno_create, 
                                  tbl1.id_lider, 
                                  tbl2.nombre, 
                                  tbl4.precio,
                                  tbl3.cantidad_confirmada, 
                                  tbl4.codigo, 
                                  tbl4.actividad, 
                                  tbl3.cantidad_confirmada, 
                                  tbl3.id_nodo,
                                  id_estado_nodo, 
                                  fecha_actualiza_id_estado_nodo, 
                                  fecha_facturacion,
                                  nodo.gom,
                                  tcua.nombre,
                                  nodo.id_ws,
                                  nodo.id_nodo,
                                  nodo.id_proyecto,
                                  rds_gop_ordenes.id_orden,
                                  rds_gop_ordenes.descargo2,
                                  rds_gop_ordenes.descargo3,
                                  rds_gop_ordenes.descargo4,
                                  rds_gop_ordenes.descargo5,
                                  rds_gop_ordenes.descargo6,
                                  rds_gop_ordenes.descargo7
                        ) as tabla_programado
                        
                        FULL OUTER JOIN

                        (

                            select
                                 cuadrigop.id_movil,
                                 tcua.nombre as tipoc,
                                 gop_estado_ordenes.nombre as id_estadoN,
                                 tbl1.id_orden,
                                 ".$this->tblAux."ordenes".$this->valorT.".id_estado,
                                 fecha_emision,
                                 fecha_programacion,
                                 fecha_prevista_ejecucion,
                                 fecha_ejecucion,
                                 ".$this->tblAux."ordenes".$this->valorT.".cd,
                                 ".$this->tblAux."ordenes".$this->valorT.".nivel_tension,
                                 ".$this->tblAux."ordenes".$this->valorT.".direccion,
                                 ".$this->tblAux."ordenes".$this->valorT.".gom,
                                 ".$this->tblAux."proyectos".$this->valorT.".nombre as nombreP,
                                 ".$this->tblAux."ordenes".$this->valorT.".id_proyecto,
                                 nodo.nombre_nodo as nodos_utilizados,
                                 ".$this->tblAux."ordenes".$this->valorT.".wbs_utilzadas,
                                 ".$this->tblAux."ordenes".$this->valorT.".descargo,
                                 tbl5.id_baremo as bareE,
                                 tbl5.cantidad as cantE,
                                 fecha_usuario_terreno_create,
                                 id_estado_nodo,
                                 fecha_actualiza_id_estado_nodo,
                                 fecha_facturacion,
                                 (tbl5.precio * tbl5.cantidad) as valorEje,
                                 tbl1.id_lider,
                                 tbl2.nombre,
                                 (tbl4.precio * tbl3.cantidad_confirmada) as cantidad,
                                 tbl4.codigo,
                                 tbl4.actividad as nombre_baremo,
                                 mobra.cantidad_confirmada,
                                 mobra.id_nodo,
                                 nodo.gom as gomn,

                                 CASE 
                                    -- nodo
                                    WHEN  
                                    (
                                        SELECT COUNT(*) FROM rds_gop_nodos AS nodo_local_1_ejecutado
                                        WHERE nodo_local_1_ejecutado.id_nodo = nodo.id_nodo 
                                        AND nodo_local_1_ejecutado.id_proyecto = nodo.id_proyecto
                                        AND nodo_local_1_ejecutado.gom IS NOT NULL 
                                        AND nodo_local_1_ejecutado.gom <> '0'
                                        AND nodo_local_1_ejecutado.gom <> ''
                                    ) = 1
                                    THEN (
                                        SELECT gom FROM rds_gop_nodos AS nodo_local_2_ejecutado
                                        WHERE nodo_local_2_ejecutado.id_nodo = nodo.id_nodo 
                                        AND nodo_local_2_ejecutado.id_proyecto = nodo.id_proyecto 
                                        AND nodo_local_2_ejecutado.gom IS NOT NULL 
                                        AND nodo_local_2_ejecutado.gom <> '0'
                                        AND nodo_local_2_ejecutado.gom <> ''
                                    )

                                    -- ws
                                    WHEN  
                                    (
                                        SELECT COUNT(*) FROM rds_gop_ws AS ws_local_1_ejecutado
                                        WHERE ws_local_1_ejecutado.id_ws = nodo.id_ws 
                                        AND ws_local_1_ejecutado.id_proyecto = nodo.id_proyecto
                                        AND ws_local_1_ejecutado.gom IS NOT NULL 
                                        AND ws_local_1_ejecutado.gom <> '0'
                                        AND ws_local_1_ejecutado.gom <> ''
                                    ) > 0
                                    THEN (
                                        SELECT TOP 1 gom FROM rds_gop_ws AS ws_local_2_ejecutado
                                        WHERE ws_local_2_ejecutado.id_ws = nodo.id_ws 
                                            AND ws_local_2_ejecutado.id_proyecto = nodo.id_proyecto 
                                            AND ws_local_2_ejecutado.gom IS NOT NULL 
                                            AND ws_local_2_ejecutado.gom <> '0'
                                            AND ws_local_2_ejecutado.gom <> ''
                                        ORDER BY ws_local_2_ejecutado.gom ASC
                                    )

                                    -- ws gom
                                    WHEN  
                                    (
                                        SELECT COUNT(*) FROM rds_gop_ws_gom AS ws_gom_local_1_ejecutado
                                        WHERE ws_gom_local_1_ejecutado.id_ws = nodo.id_ws 
                                            AND ws_gom_local_1_ejecutado.id_proyecto = nodo.id_proyecto
                                            AND ws_gom_local_1_ejecutado.id_gom IS NOT NULL 
                                            AND ws_gom_local_1_ejecutado.id_gom <> '0'
                                            AND ws_gom_local_1_ejecutado.id_gom <> ''
                                    ) > 0
                                    THEN (
                                        SELECT TOP 1 id_gom FROM rds_gop_ws_gom AS ws_gom_local_2_ejecutado
                                        WHERE ws_gom_local_2_ejecutado.id_ws = nodo.id_ws 
                                            AND ws_gom_local_2_ejecutado.id_proyecto = nodo.id_proyecto
                                            AND ws_gom_local_2_ejecutado.id_gom IS NOT NULL 
                                            AND ws_gom_local_2_ejecutado.id_gom <> '0'
                                            AND ws_gom_local_2_ejecutado.id_gom <> ''
                                        ORDER BY ws_gom_local_2_ejecutado.id_gom ASC
                                    )

                                    -- orden gom
                                    WHEN  
                                    (
                                        SELECT COUNT(*) FROM rds_gop_ordenes AS local_orden_1_ejecutado
                                        WHERE local_orden_1_ejecutado.id_orden = rds_gop_ordenes.id_orden
                                        AND local_orden_1_ejecutado.id_proyecto = rds_gop_ordenes.id_proyecto
                                        AND local_orden_1_ejecutado.gom IS NOT NULL 
                                        AND local_orden_1_ejecutado.gom <> ''
                                    ) = 1
                                    THEN (
                                        SELECT gom FROM rds_gop_ordenes AS local_orden_2_ejecutado
                                        WHERE local_orden_2_ejecutado.id_orden = rds_gop_ordenes.id_orden
                                        AND local_orden_2_ejecutado.id_proyecto = rds_gop_ordenes.id_proyecto
                                        AND local_orden_2_ejecutado.gom IS NOT NULL 
                                        AND local_orden_2_ejecutado.gom <> ''
                                    )

                                    ELSE 0
                                END AS gom_nodo_ws_orden,

                                CONCAT(
                                  IIF(rds_gop_ordenes.descargo IS NOT NULL AND rds_gop_ordenes.descargo <> 0, CONCAT(rds_gop_ordenes.descargo,','),''),
                                  IIF(rds_gop_ordenes.descargo2 IS NOT NULL AND rds_gop_ordenes.descargo2 <>0, CONCAT(rds_gop_ordenes.descargo2,','),''),
                                  IIF(rds_gop_ordenes.descargo3 IS NOT NULL AND rds_gop_ordenes.descargo3 <>0,CONCAT(rds_gop_ordenes.descargo3,','),'')
                                ) descargos_con_corte,

                                CONCAT(
                                  IIF(rds_gop_ordenes.descargo4 IS NOT NULL AND rds_gop_ordenes.descargo4 <>0,CONCAT(rds_gop_ordenes.descargo4,','),''),
                                  IIF(rds_gop_ordenes.descargo5 IS NOT NULL AND rds_gop_ordenes.descargo5 <>0,CONCAT(rds_gop_ordenes.descargo5,','),''),
                                  IIF(rds_gop_ordenes.descargo6 IS NOT NULL AND rds_gop_ordenes.descargo6 <>0,CONCAT(rds_gop_ordenes.descargo6,','),''),
                                  IIF(rds_gop_ordenes.descargo7 IS NOT NULL AND rds_gop_ordenes.descargo7 <>0,CONCAT(rds_gop_ordenes.descargo7,','),'')
                                ) AS descargos_sin_corte
                           from
                                ".$this->tblAux."ordenes AS rds_gop_ordenes 
                                inner join gop_estado_ordenes on gop_estado_ordenes.id_estado = ".$this->tblAux."ordenes".$this->valorT.".id_estado
                                                  inner join ".$this->tblAux."proyectos on ".$this->tblAux."proyectos".$this->valorT.".id_proyecto = ".$this->tblAux."ordenes".$this->valorT.".id_proyecto
                                                  inner join ".$this->tblAux."ordenes_manoobra_detalle as tbl1 on ".$this->tblAux."ordenes".$this->valorT.".id_orden = tbl1.id_orden
                                                  inner join rds_inv_bodegas as tbl2 on tbl1.id_lider = tbl2.id_bodega
                                                  left join ".$this->tblAux."cuadrilla as cuadrigop on cuadrigop.id_lider = tbl1.id_lider
                                                  left join ".$this->tblAux."tipo_cuadrilla as tcua 
                                                               on (cuadrigop.id_tipo_cuadrilla=tcua.id_tipo_cuadrilla)   

                                                  inner join ".$this->tblAux."ordenes_mobra as 
                                                      mobra on tbl1.id_orden = mobra.id_orden and
                                                      mobra.id_origen = tbl1.id_lider and 
                                                      mobra.id_proyecto = rds_gop_proyectos.id_proyecto

                                                  left join ".$this->tblAux."baremos as tbl4 on tbl4.id_baremo =  mobra.id_baremo
                                                  left join ".$this->tblAux."mobra as tbl5 on tbl1.id_orden = tbl5.id_orden and
                                                                                 tbl5.id_origen = tbl1.id_lider and
                                                                                    tbl5.id_baremo = tbl4.id_baremo and
                                                                                    tbl5.id_nodo = mobra.id_nodo
                                                  left join ".$this->tblAux."ordenes_manoobra as tbl3 on tbl1.id_orden = tbl3.id_orden and
                                                                                             tbl3.id_personaCargo = tbl1.id_lider and
                                                                                             tbl3.id_baremo = tbl4.id_baremo and
                                                                                             tbl3.id_nodo =  mobra.id_nodo
                                                        left join ".$this->tblAux."nodos as nodo on nodo.id_nodo = mobra.id_nodo
                            where
                                  ".$this->tblAux."ordenes".$this->valorT.".id_proyecto LIKE '%".$proy."%' and
                                  ".$this->tblAux."proyectos".$this->valorT.".nombre LIKE '%".$proyN."%' and
                                  ".$this->tblAux."ordenes".$this->valorT.".id_tipo = 'T56' and
                                  tbl1.id_estado = 0 and
                                  periodo = year(".$this->tblAux."ordenes".$this->valorT.".fecha_ejecucion) /*and
                                  cuadrigop.id_estado = 'A' */
                                  ".$wheresql."
                            group by
                                  cuadrigop.id_movil, 
                                  gop_estado_ordenes.nombre, 
                                  tbl1.id_orden, ".
                                  $this->tblAux."ordenes".$this->valorT.".id_estado,
                                  fecha_emision, 
                                  fecha_programacion, 
                                  fecha_prevista_ejecucion, 
                                  fecha_ejecucion, ".$this->tblAux."ordenes".$this->valorT.".cd,".
                                  $this->tblAux."ordenes".$this->valorT.".nivel_tension, ".
                                  $this->tblAux."ordenes".$this->valorT.".direccion, ".
                                  $this->tblAux."ordenes".$this->valorT.".gom,".
                                  $this->tblAux."proyectos".$this->valorT.".nombre, ".
                                  $this->tblAux."ordenes".$this->valorT.".id_proyecto, 
                                  nodo.nombre_nodo, ".
                                  $this->tblAux."ordenes".$this->valorT.".wbs_utilzadas,".
                                  $this->tblAux."ordenes".$this->valorT.".descargo, 
                                  tbl1.id_lider, 
                                  tbl2.nombre, 
                                  tbl5.id_baremo, 
                                  tbl5.cantidad, 
                                  tbl5.precio,
                                  tbl5.cantidad, 
                                  fecha_usuario_terreno_create, 
                                  tbl1.id_lider, 
                                  tbl2.nombre, 
                                  tbl4.precio,
                                  tbl3.cantidad_confirmada, 
                                  tbl4.codigo, tbl4.actividad, 
                                  mobra.cantidad_confirmada, 
                                  mobra.id_nodo,
                                  id_estado_nodo, 
                                  fecha_actualiza_id_estado_nodo, 
                                  fecha_facturacion,
                                  nodo.gom,
                                  tcua.nombre,
                                  nodo.id_ws,
                                  nodo.id_nodo,
                                  nodo.id_proyecto,
                                  rds_gop_ordenes.id_orden,
                                  rds_gop_ordenes.descargo2,
                                  rds_gop_ordenes.descargo3,
                                  rds_gop_ordenes.descargo4,
                                  rds_gop_ordenes.descargo5,
                                  rds_gop_ordenes.descargo6,
                                  rds_gop_ordenes.descargo7
                    ) as tabla_ejecutado
                    on  (
                         tabla_programado.cd=tabla_ejecutado.cd and
                         tabla_programado.gom_nodo_ws_orden=tabla_ejecutado.gom_nodo_ws_orden and 
                         tabla_programado.nodos_utilizados=tabla_ejecutado.nodos_utilizados and
                         tabla_programado.wbs_utilzadas =tabla_ejecutado.wbs_utilzadas and
                         tabla_programado.codigo =tabla_ejecutado.codigo and
                         tabla_programado.bareE =tabla_ejecutado.bareE
                         )

                 ";

           echo "<pre>";
           echo $sql; 
           echo "</pre>";
           return;

            $ordeneRealizada = DB::select($sql);
            
          //  echo count($ordeneRealizada); return;
            
            \Excel::create('Consolidado actividades' .  $this->fechaALong, function($excel) use($ordeneRealizada) {            

                $excel->sheet('Consolidado', function($sheet) use($ordeneRealizada){
                    $mes = 4;
                    $primerDia = 1;
                    $ultimoDia = 30;
                    $products = ["Estado orden","Nombre proyecto","Cod proyecto","Nodos","WBS","Descargos Con Corte", "Descargos Sin Corte", "ORDEN","F. Emisión","F. Programación",
                    "F. Ejecución","CD","Dirección","GOM","Líder","Nombre líder","Móvil","Tipo Movil","Baremo","Actividad","Cantidad","Valor","EJECUTADA","Cant. Eje","Valor Eje","Tipo de ingreso","Estado del Nodo","Fecha actualiza nodo"];
                    $sheet->fromArray($products);
                    $k = 2;
                    $sema = 1;


                    for ($i=0; $i < count($ordeneRealizada); $i++) {    

                        $datoE  = "SI";

                        $validacion = "";
                        if($ordeneRealizada[$i]->cantE == NULL || $ordeneRealizada[$i]->cantE == "")
                            $datoE  = "NO";

                        if( ($ordeneRealizada[$i]->fecha_actualiza_id_estado_nodo == NULL || $ordeneRealizada[$i]->fecha_actualiza_id_estado_nodo == "") && $ordeneRealizada[$i]->fecha_facturacion != NULL)
                            $validacion = "CENTRO DE CONTROL";
                        else
                            $validacion = "CUADRILLA";

                        if($datoE == "NO")
                            $validacion = "";

                        $descargos_con_corte = rtrim($ordeneRealizada[$i]->descargos_con_corte, ',');
                        $descargos_sin_corte = rtrim($ordeneRealizada[$i]->descargos_sin_corte, ',');

                        // ==============================================================================
                        // SE AGREGA LA FILA AL EXCEL
                        // ==============================================================================
                        $sheet->row($i +2, array(
                            $ordeneRealizada[$i]->id_estadoN,
                            $ordeneRealizada[$i]->nombreP,
                            $ordeneRealizada[$i]->id_proyecto,
                            $ordeneRealizada[$i]->nodos_utilizados,
                            $ordeneRealizada[$i]->wbs_utilzadas,
                            //$ordeneRealizada[$i]->descargo,
                            $descargos_con_corte,
                            $descargos_sin_corte,
                            $ordeneRealizada[$i]->id_orden,
                            explode(" ",$ordeneRealizada[$i]->fecha_emision)[0],
                            explode(" ",$ordeneRealizada[$i]->fecha_programacion)[0],
                            explode(" ",$ordeneRealizada[$i]->fecha_ejecucion)[0],
                            $ordeneRealizada[$i]->cd,
                            $ordeneRealizada[$i]->direccion,
                            //$ordeneRealizada[$i]->gom,
                            $ordeneRealizada[$i]->gom_nodo_ws_orden,
                            $ordeneRealizada[$i]->id_lider,
                            $ordeneRealizada[$i]->nombre,
                            $ordeneRealizada[$i]->id_movil,
                            $ordeneRealizada[$i]->tipoc,
                            $ordeneRealizada[$i]->codigo,
                            $ordeneRealizada[$i]->nombre_baremo,
                            $ordeneRealizada[$i]->cantidad_confirmada,
                            $ordeneRealizada[$i]->cantidad,
                            $datoE,
                            $ordeneRealizada[$i]->cantE,
                            $ordeneRealizada[$i]->valorEje,
                            $validacion,
                            $ordeneRealizada[$i]->id_estado_nodo,
                            $ordeneRealizada[$i]->fecha_actualiza_id_estado_nodo,
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
                            //->where('periodo',2017)
                            
                            ->whereRaw("periodo = year(".$this->tblAux . "ordenes.fecha_ejecucion) ")          
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
                "permisoversion" => self::consultaAcceso('OP102'),
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
            "permisoversion" => self::consultaAcceso('OP102'),
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
                                       
                    and periodo =  year(tbl1.fecha_ejecucion) 
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

    //Index principal para consulta GOM
    public function indexConsultaGom()
    {
        $permisoGOM = self::consultaAcceso('OP100');

        if($permisoGOM == "N")
            return view('errors.nopermiso'); 


        $estados = DB::Table('pcb_gop_estados_gom')
                   ->get();
        
        $data = [];

        if(Session::has('estado_gom'))
        {   
            
            $data  = DB::Table($this->tblAux  . "proyectos as pry")
                    ->join($this->tblAux  . "ws_gom as gom",'gom.id_proyecto','=','pry.id_proyecto')
                    ->leftJoin('pcc_gop_estados_gom as estado','estado.id_estado_gom','=','gom.id_estado')
                    ->join($this->tblAux  . "ws as ws",'ws.id_ws','=','gom.id_ws')
                    ->select('pry.nombre','pry.id_proyecto','gom.id_gom','gom.id_estado','ws.nombre_ws','estado.nombre_gom')
                    ->where('gom.id_gom','LIKE','%' . Session::get('numero_gom') . '%');

            if(Session::get('estado_gom') != '')
                    $data->where('gom.id_estado',Session::get('estado_gom'));

            $data = $data->get();

        }
        
        return view('proyectos.redes.trabajoprogramado.indexGomProyectos',
            array(
                'estados' => $estados,
                'data' => $data,
                'permisoGOM' => $permisoGOM
                )); 
    }

    //Exporte programación
    public function exportarProgramacion(Request $request)
    {
        $fecha1 = $request->all()['fecha1'];
        $fecha2 = $request->all()['fecha2'];

        $comboTipoProceso = $request->all()['Cbo_proyecto_exporte'];

        $obser = "";
        $anio_exporte = "";
        $mes_exporte = "";

        $tipo=0;
        if(isset($request->all()['tipo']))
        {
            $tipo=$request->all()['tipo'];
        }
        
        
        if(isset($request->all()['txt_obser_exporte']))
        {
            $obser = $request->all()['txt_obser_exporte'];
            $anio_exporte = $request->all()['anio_exporte'];
            $mes_exporte = $request->all()['mes_exporte'];   
        }
        

        $fecha1 = explode("/",$fecha1)[2] . "-" . explode("/",$fecha1)[1]  . "-" . explode("/",$fecha1)[0];
        $fecha2 = explode("/",$fecha2)[2] . "-" . explode("/",$fecha2)[1]  . "-" . explode("/",$fecha2)[0];

        if($comboTipoProceso == "1") //División eléctrica
        {
            $cad = "EXEC sp_" . $this->tblAux . "exporte_programacion_electrica '" . $fecha1
                     . "','" . $fecha2 . "','".$tipo."'  ";
        }
        else
        {
            $cad = "EXEC sp_" . $this->tblAux . "exporte_programacion '" . $fecha1
                     . "','" . $fecha2 . "','".$tipo."' ";
        }

        $arr = DB::select("SET NOCOUNT ON;" . $cad);

        //dd($arr);
        \Excel::create('Exporte de Programación ' . $this->fechaALong, function($excel) use($arr,$obser,$anio_exporte,$mes_exporte,$comboTipoProceso) {            

                $excel->sheet('Exporte', function($sheet) use($arr,$obser,$anio_exporte,$mes_exporte,$comboTipoProceso){
                   
                    //$products = ["NOMBRE LIDER","Fecha","Dia Tex","Semana","Año","Mes","Día","apoyos - horarios","PROCESO","PROYECTO - RADICADO","GOM","WBS","NODOS","SUPERVISOR PLANER","SUPERVISOR EJECUTOR","Notas","Valor MO 2ª VISITA2","ORDEN"];

                    if($comboTipoProceso == "1") //División eléctrica
                    {
                        $products = ["MOVIL","TIPO DE MOVIL","NOMBRE LIDER","PLACA","AVANTEL","Fecha","Dia Tex","Semana","Año","Mes","Día","apoyos - horarios","PROCESO","PROYECTO - RADICADO","GOM","WBS","NODOS","CD","Notas","# CAMPRO","OBSEVACION MANIOBRA","SUPERVISOR PROGRAMADOR","SUPERVISOR EJECUTOR","INGENIERO RESPONSABLE","Valor MO 2ª VISITA2","COMPARACION META","CUMPLIMIENTO","ORDEN","DESCARGOS CORTE","DESCARGOS SIN CORTE"];
                        
                        $sheet->fromArray($products);

                        if($obser != "")
                        {
                            //Guardar versión de la app nueva
                            $ultimoaVersion = DB::Table('rds_gop_versionamiento')  
                                                ->where('mes',$mes_exporte)
                                                ->where('anio',$anio_exporte)
                                                ->where('proceso',$comboTipoProceso)
                                                ->get(['versio']);

                            $versionNueva = 1;
                            if(count($ultimoaVersion) != 0)
                                $versionNueva = intval($ultimoaVersion[0]->versio) + 1;

                            //Primera versión de la App
                            DB::Table('rds_gop_versionamiento')  
                                    ->insert(
                                            array(
                                                array(
                                                    'mes' => $mes_exporte,
                                                    'anio' => $anio_exporte,
                                                    'fecha' => $this->fechaALong,
                                                    'versio' => $versionNueva,
                                                    'observacion' => $obser,
                                                    'usuario' => Session::get('user_login'),
                                                    'proceso' => $comboTipoProceso
                                                )
                                            )
                                        );

                            $idVersion = DB::Table('rds_gop_versionamiento')  
                                                ->where('mes',$mes_exporte)
                                                ->where('anio',$anio_exporte)
                                                ->where('versio',$versionNueva)
                                                ->where('proceso',$comboTipoProceso)
                                                ->value('id');
                        }


                        for ($i=0; $i < count($arr); $i++) {    
                            $gom = $arr[$i]->gom;
                            $gom = str_replace(' ', '', $gom);
                            $gom = trim($gom);

                            if($gom) {
                                $gom = rtrim($gom, ',');
                                $gom_explode = explode(',', $gom);

                                $gom_uniques = array_unique($gom_explode);
                                $gom = implode(',', $gom_uniques);
                            }
                            else {
                                $gom = $arr[$i]->gom_orden;
                            }

                            $sheet->row($i +2, array(
                                $arr[$i]->id_movil,
                                $arr[$i]->tipo_movil,
                                str_replace("Ã‘","Ñ",$arr[$i]->lider),
                                $arr[$i]->placa,
                                $arr[$i]->avantel,
                                explode(" ",$arr[$i]->fecha)[0],
                                $arr[$i]->diaTexto,
                                $arr[$i]->semana,
                                $arr[$i]->anio,
                                $arr[$i]->mes,
                                $arr[$i]->dia,
                                $arr[$i]->apoyo_horarios,
                                $arr[$i]->proceso,
                                $arr[$i]->proyecto_radicado,
                                $gom,
                                $arr[$i]->wbs_utilzadas,
                                $arr[$i]->nodos_utilizados,
                                $arr[$i]->cd,
                                $arr[$i]->notas,
                                $arr[$i]->campros,
                                $arr[$i]->observaciones,
                                $arr[$i]->supervisor,
                                $arr[$i]->supervisor_ejecutor,
                                $arr[$i]->ing_soli_cam,
                                ($arr[$i]->val == NULL ? 0 : $arr[$i]->val),
                                $arr[$i]->meta,
                                "",
                                $arr[$i]->id_orden,
                                $arr[$i]->descargos,
                                $arr[$i]->descargos2
                                ));     
                        }

                    }
                    else //Obras civiles
                    {
                        $products = ["NOMBRE LIDER","Fecha","Dia Tex","Semana","Año","Mes","Día","apoyos - horarios","PROCESO","PROYECTO - RADICADO","GOM","WBS","NODOS","Notas","Valor MO 2ª VISITA2","CUMPLIMIENTO","ORDEN"];
                        
                        $sheet->fromArray($products);
                        
                        $sheet->setColumnFormat(array(
                            'O' => '0,000.00'
                        ));


                        if($obser != "")
                        {
                            //Guardar versión de la app nueva
                            $ultimoaVersion = DB::Table('rds_gop_versionamiento')  
                                                ->where('mes',$mes_exporte)
                                                ->where('anio',$anio_exporte)
                                                ->where('proceso',$comboTipoProceso)
                                                ->get(['versio']);

                            $versionNueva = 1;
                            if(count($ultimoaVersion) != 0)
                                $versionNueva = intval($ultimoaVersion[0]->versio) + 1;

                            //Primera versión de la App
                            DB::Table('rds_gop_versionamiento')  
                                    ->insert(
                                            array(
                                                array(
                                                    'mes' => $mes_exporte,
                                                    'anio' => $anio_exporte,
                                                    'fecha' => $this->fechaALong,
                                                    'versio' => $versionNueva,
                                                    'observacion' => $obser,
                                                    'usuario' => Session::get('user_login'),
                                                    'proceso' => $comboTipoProceso
                                                )
                                            )
                                        );

                            $idVersion = DB::Table('rds_gop_versionamiento')  
                                                ->where('mes',$mes_exporte)
                                                ->where('anio',$anio_exporte)
                                                ->where('versio',$versionNueva)
                                                ->where('proceso',$comboTipoProceso)
                                                ->value('id');
                        }

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

                                if($obser != "")
                                {
                                    DB::Table('rds_gop_versionamiento_detalle')  
                                    ->insert(
                                            array(
                                                array(
                                                    'id_versionamiento' => $idVersion,
                                                    'nombre_lider' => str_replace("Ã‘","Ñ",$arr[$i]->lider),
                                                    'fecha' => $arr[$i]->fecha_programacion,
                                                    'dia_tex' => $arr[$i]->dia,
                                                    'semana' => $arr[$i]->semana,
                                                    'anio' => $arr[$i]->aNo,
                                                    'mes' => $arr[$i]->mes,
                                                    'dia' => $arr[$i]->diaNumero,
                                                    'apoyos' => "",
                                                    'proceso' => "",
                                                    'proyecto' => "",
                                                    'gom' => "",
                                                    'wbs' => "",
                                                    'nodos' => "",
                                                    'notas' => strtoupper($arr[$i]->observacion),
                                                    'valor' => "",
                                                    'cumplimiento' => "",
                                                    'orden' => $arr[$i]->id_orden
                                                    
                                                )
                                            )
                                        );

                                }

                            }
                            else
                            {

                                if($obser != "")
                                {
                                    DB::Table('rds_gop_versionamiento_detalle')  
                                    ->insert(
                                            array(
                                                array(
                                                    'id_versionamiento' => $idVersion,
                                                    'nombre_lider' => str_replace("Ã‘","Ñ",$arr[$i]->lider),
                                                    'fecha' => $arr[$i]->fecha_programacion,
                                                    'dia_tex' => $arr[$i]->dia,
                                                    'semana' => $arr[$i]->semana,
                                                    'anio' => $arr[$i]->aNo,
                                                    'mes' => $arr[$i]->mes,
                                                    'dia' => $arr[$i]->diaNumero,
                                                    'apoyos' => $arr[$i]->apoyo_horarios,
                                                    'proceso' => $arr[$i]->proceso,
                                                    'proyecto' => $arr[$i]->proyecto_radicado,
                                                    'gom' => $arr[$i]->gom,
                                                    'wbs' => $arr[$i]->wbs_utilzadas,
                                                    'nodos' => $arr[$i]->nodos_utilizados,
                                                    'notas' => strtoupper($arr[$i]->observacion),
                                                    'valor' => ($arr[$i]->val == NULL ? 0 : $arr[$i]->val),
                                                    'cumplimiento' => "",
                                                    'orden' => $arr[$i]->id_orden
                                                    
                                                )
                                            )
                                        );

                                }

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
                                    ($arr[$i]->val == NULL ? 0 : $arr[$i]->val),
                                    "",
                                    $arr[$i]->id_orden
                                    ));     
                            }
                        }
                    }

                });
        })->export('xls');
    }

    //Exporte programación de versiones
    public function exportarProgramacionversiones(Request $request)
    {
        $id = $request->all()['id'];
       

        $version = DB::Table('rds_gop_versionamiento')  
                    ->where('id',$id)
                    ->get(['versio','mes','anio'])[0];
        
        \Excel::create('Exporte de Programación V. ' . $version->versio . " Mes " . $version->mes . " Año " . $version->anio . " - " . $this->fechaALong, function($excel) use($id) {
                $excel->sheet('Exporte', function($sheet) use($id){
                   

                    $products = ["NOMBRE LIDER","Fecha","Dia Tex","Semana","Año","Mes","Día","apoyos - horarios","PROCESO","PROYECTO - RADICADO","GOM","WBS","NODOS","Notas","Valor MO 2ª VISITA2","CUMPLIMIENTO","ORDEN"];
                    $sheet->fromArray($products);
                    
                    //Guardar versión de la app nueva
                    $arr = DB::Table('rds_gop_versionamiento_detalle')  
                                        ->where('id_versionamiento',$id)
                                        ->orderBy('id')
                                        ->get();

                        for ($i=0; $i < count($arr); $i++) { 
                                    $sheet->row($i +2, array(
                                    $arr[$i]->nombre_lider,
                                    $arr[$i]->fecha,
                                    $arr[$i]->dia_tex,
                                    $arr[$i]->semana,
                                    $arr[$i]->anio,
                                    $arr[$i]->mes,
                                    $arr[$i]->dia,
                                    $arr[$i]->apoyos,
                                    $arr[$i]->proceso,
                                    $arr[$i]->proyecto,
                                    $arr[$i]->gom,
                                    $arr[$i]->wbs,
                                    $arr[$i]->nodos,
                                    $arr[$i]->notas,
                                    $arr[$i]->valor,
                                    $arr[$i]->cumplimiento,
                                    $arr[$i]->orden
                                    )); 
                        }    
                    

                });
        })->export('xls');
    }


    //Exporte SUpervisores
    public function exportarSupervisores(Request $request)
    {
        $fecha1 = $request->all()['fecha_inicio'];
        $fecha2 = $request->all()['fecha_corte']; 

        $fecha1 = explode("/",$fecha1)[2] . "-" . explode("/",$fecha1)[1]  . "-" . explode("/",$fecha1)[0];
        $fecha2 = explode("/",$fecha2)[2] . "-" . explode("/",$fecha2)[1]  . "-" . explode("/",$fecha2)[0];

        $query ="
            declare @fecha_ini Datetime,  @fecha_fin Datetime

            select @fecha_ini = '" . $fecha1 . "',@fecha_fin = '" . $fecha2 . "';

            with FECHAS(fecha) AS
            (
                SELECT @fecha_ini
                UNION ALL
                SELECT DATEADD(DAY,1,fecha) fecha
                FROM FECHAS
                WHERE fecha <= @fecha_fin
            )

            SELECT FECHAS.fecha,fecha_ejecucion,fecha_ejecucion_final,id_orden,proyecto,nombres, apellidos, id_movil, 
                        gom, wbs_utilzadas, supervisor_ejecutor, 
                        supervisor_ejecutor_cedula, tipo_trabajo  
            FROM FECHAS
            inner join (
            (SELECT [orden].[id_orden], [pry].[nombre] as [proyecto], [rh].[nombres], [rh].[apellidos], [cua].[id_movil], [orden].[fecha_ejecucion], 
                        [orden].[gom], [orden].[fecha_ejecucion_final], [orden].[wbs_utilzadas], [orden].[supervisor_ejecutor], 
                        [orden].[supervisor_ejecutor_cedula], [orden].[tipo_trabajo] 

                        FROM [" . $this->tblAux . "ordenes] as [orden] 
                        inner join [" . $this->tblAux . "proyectos] as [pry] on [pry].[id_proyecto] = [orden].[id_proyecto] 
                        inner join [" . $this->tblAux . "ordenes_manoobra_detalle] as [cuadrilla] on [cuadrilla].[id_orden] = [orden].[id_orden] 
                        inner join [rh_personas] as [rh] on [rh].[identificacion] = [cuadrilla].[id_lider] 
                        inner join [" . $this->tblAux . "cuadrilla] as [cua] on [cua].[id_lider] = [cuadrilla].[id_lider] 

                        WHERE [pry].[tipo_proyecto] = 'T03'
                        and [orden].[id_estado] <> 'A1' 
                        and [cuadrilla].[id_estado] = 0 
                        and 
                        ('" . $fecha1 . "' between [orden].[fecha_ejecucion] and [orden].[fecha_ejecucion_final] 
                            OR  '" . $fecha2 . "' between [orden].[fecha_ejecucion] and [orden].[fecha_ejecucion_final]
                        )
            )) AS CONSULTA1 ON 
             FECHAS.fecha between CONSULTA1.fecha_ejecucion and CONSULTA1.fecha_ejecucion_final
                AND FECHAS.fecha <= @fecha_fin
            ORDER BY CONSULTA1.fecha_ejecucion
        ";

        $arr = DB::select("SET NOCOUNT ON;" . $query);

        \Excel::create('Exporte de Supervisores por Proyecto ' . $this->fechaALong, function($excel) use($arr) {            

                $excel->sheet('Exporte', function($sheet) use($arr){

                    //$products = ["NOMBRE LIDER","Fecha","Dia Tex","Semana","Año","Mes","Día","apoyos - horarios","PROCESO","PROYECTO - RADICADO","GOM","WBS","NODOS","SUPERVISOR PLANER","SUPERVISOR EJECUTOR","Notas","Valor MO 2ª VISITA2","ORDEN"];
                    $products = ["Nombre Proyecto","OT","Nombre del Lider","WBS","TIPO DE TRABAJO"," supervisor_cedula","supervidor_nombre","Fecha","Fecha inicio WBS","Fecha fin WBS"];
                    $sheet->fromArray($products);

                    for ($i=0; $i < count($arr); $i++) {    
                            $sheet->row($i +2, array(
                                str_replace("Ã‘","Ñ",$arr[$i]->proyecto),
                                $arr[$i]->id_orden,
                                str_replace("Ã‘","Ñ",$arr[$i]->apellidos) . " " . str_replace("Ã‘","Ñ",$arr[$i]->nombres),
                                substr($arr[$i]->wbs_utilzadas, 0, -1),
                                $arr[$i]->tipo_trabajo,
                                ($arr[$i]->supervisor_ejecutor_cedula == 0 ? '' : $arr[$i]->supervisor_ejecutor_cedula),
                                ($arr[$i]->supervisor_ejecutor_cedula == 0 ? '' : $arr[$i]->supervisor_ejecutor),
                                explode(" ",$arr[$i]->fecha)[0],
                                explode(" ",$arr[$i]->fecha_ejecucion)[0],
                                explode(" ",$arr[$i]->fecha_ejecucion_final)[0],
                                ));  
                    }

                });
        })->export('xlsx');

    }

    /*-----FILTRO----*/
    /*ORDENES TRABAJO PROGRAMADO PROYECTOS*/
    public function filterOrdenesTP(Request $request)
    {
        //echo "a ver que pasa"; return;
        $fec1 = $request->all()['fecha_inicio'];
        $fec2 = $request->all()['fecha_corte'];
        $fecha_inicio_ymd = (count(explode("/",$fec1)) > 1 ? explode("/",$fec1)[2] . "-" . explode("/",$fec1)[1] . "-" . explode("/",$fec1)[0] : "" ) ;
        $fecha_corte_ymd = (count(explode("/",$fec2)) > 1 ? explode("/",$fec2)[2] . "-" . explode("/",$fec2)[1] . "-" . explode("/",$fec2)[0] : "" ) ;
        $tipo = $request->all()['id_tipo'];
        $esta = $request->all()['cbo_estado'];
        $numP = $request->all()['proyecto'];
        $proyN = $request->all()['proyectoN'];
        $tproceso = 0;
        
          if(isset($request->all()['tproceso'])){
             $tproceso = $request->all()['tproceso'];
          }
        

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


        Session::flash('tproceso',$tproceso);
        //Session::flash('tipo',$request->all()['tipo']);
        Session::flash('estado',$esta);

        
         $estadonodo="0";
        if(isset($request->all()['estadonodo'])){
           $estadonodo = $request->all()['estadonodo'];
        }
        
        Session::flash('estadonodo',$estadonodo);
        
        return Redirect::to(url('/').'/redes/ordenes/home'); 
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

         $estadonodo="0";
        if(isset($request->all()['estadonodo'])){
           $estadonodo = $request->all()['estadonodo'];
        }
        
        Session::flash('estadonodo',$estadonodo);
        
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
        $estadonodo="0";
        if(isset($request->all()['estadonodo'])){
           $estadonodo = $request->all()['estadonodo'];
        }
        
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

        Session::flash('estadonodo',$estadonodo);
        
        
        
        
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

    //Index principal para consulta GOM
    public function indexConsultaGomFilter(Request $request)
    {
        Session::flash('estado_gom',$request->all()['estado_gom']);
        Session::flash('numero_gom',$request->all()['numero_gom']);
        return Redirect::to('transversal/consultar/goms'); 
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

       // $consecutivo=(string)$consecutivo;
        
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
                
                ->leftJoin($this->tblAux . 'estado_gom',$this->tblAux . 'estado_gom.id','=', $this->tblAux . 'nodos' . $this->valorT . '.id_estado_gom')
                
                ->where($this->tblAux . 'ws' . $this->valorT . '.id_proyecto',$consecutivo)
                ->where($this->tblAux . 'nodos.id_proyecto',$consecutivo)
                ->select($this->tblAux . 'ws' . $this->valorT . '.id_ws as ws',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo as idN',$this->tblAux . 'ws' . $this->valorT . '.nombre_ws', $this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo', $this->tblAux . 'nodos' . $this->valorT . '.cd', $this->tblAux . 'nodos' . $this->valorT . '.id_estado',
                 $this->tblAux . 'nodos' . $this->valorT . '.direccion',$this->tblAux . 'nodos' . $this->valorT . '.observaciones',$this->tblAux . 'nodos' . $this->valorT . '.nivel_tension',
                 $this->tblAux . 'nodos' . $this->valorT . '.punto_fisico',$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'nodos' . $this->valorT . '.gom',$this->
                        tblAux . 'nodos' . $this->valorT . '.id_estado_gom',$this->tblAux . 'estado_gom.descripcion')
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
                    
                    ->where($this->tblAux .'ordenes_manoobra_detalle.id_proyecto',$consecutivo)
                    ->where($this->tblAux .'ordenes_manoobra_detalle.id_orden',$val->id_orden)
                    ->where($this->tblAux .'ordenes_manoobra_detalle.id_estado',0)
                    ->select('id_lider',$this->tblAux1 . 'inv_bodegas.nombre')
                    ->get();
            array_push($orGrupoT,array($val,$usersRecuro));
        }

        $anio = 2017;
        $anio = date("Y");
        if($this->tblAux == "apu_gop_")
            $anio = 2016;

        $baremos = DB::table($this->tblAux . 'detalle_mobra' . $this->valorT)
                    ->where($this->tblAux . 'detalle_mobra' . $this->valorT . '.id_proyecto',$consecutivo)
                    ->where($this->tblAux . 'baremos.periodo',$anio)
                
                    
                   ////////////// var que hacert cruzar con rds_gop_mobra y luego con rds_gop_ordenes ???
                    //->whereRaw($this->tblAux . 'baremos.periodo = year('.$this->tblAux . "ordenes.fecha_ejecucion) ") 
                //////////////////////////////////
                    ->where($this->tblAux . 'nodos.id_proyecto',$consecutivo)
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
                   ///////////////////////////////////
                    ->where($this->tblAux . 'nodos.id_proyecto',$consecutivo)
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

            $erroAlcargarBaremos = "";
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
                $congom=0;
                if(isset($results[0]["gom"]) != false){
                     $congom=1;
                }
                
                $arreglosBaremos = [];

                for ($i=0; $i < count($results); $i++) {

                if($results[$i]["wbs"] == "" || $results[$i]["wbs"] == NULL || $results[$i]["wbs"] == null ||
                    $results[$i]["nodo"] == "" || $results[$i]["nodo"] == NULL || $results[$i]["nodo"] == null ||
                    $results[$i]["actividad"] == "" || $results[$i]["actividad"] == NULL || $results[$i]["actividad"] == null)
                    continue;
  
                $gom = null;
                if( $congom == 1 ){
                    $gom = $results[$i]["gom"];
                }

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
                                    'seccionador' => $results[$i]["sec"],
                                    'gom' => $gom
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

                if(floatval($results[$i]["cant"]) <= 0.00)
                    continue;

                $bareCont = DB::Table($this->tblAux . 'baremos')
                            ->where('codigo',$actividad)
                           // ->where('periodo',2017)
                              ->where('periodo',date("Y"))
                            ->count();

                if($bareCont <= 0)
                    continue;

                $existBare = 0;
                //Valida que el baremo no lo halla ingresado en el mismo archivo de excel
                for ($indexBare=0; $indexBare < count($arreglosBaremos); $indexBare++) { 

                    if($arreglosBaremos[$indexBare][0] == $consecutivoWB &&
                        $arreglosBaremos[$indexBare][1] == $consecutivoNODO &&
                        $arreglosBaremos[$indexBare][2] == $actividad)
                        {
                            $existBare = 1;
                            $erroAlcargarBaremos .= "<p> - El baremo $actividad de la WBS $wbs y del nodo $nodo, ya lo ingreso en el archivo cargado</p>";
                        }       
                }

                if($existBare == 0)
                    array_push($arreglosBaremos, [$consecutivoWB,$consecutivoNODO,$actividad]);

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

            if($erroAlcargarBaremos != "")
                Session::flash('dataExcel2',"<p>Se presentaron los siguiente errores al cargar el archivo:</p> " . $erroAlcargarBaremos ); 
             
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
                /*
                $congom=0;
                if(isset($results[0]["gom"]) != false){
                     $congom=1;
                }*/
                
                $materialesNOExisten = "";
                for ($i=0; $i < count($results); $i++) {
                            
                 /*
                 $gom = null;
                 if( $congom == 1 ){
                      $gom = $results[$i]["gom"];
                 }   */
                    
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

            if($request->all()["opc"] == "6")
            {
                \Excel::create('Plantilla carga combustible CAMPRO', function($excel) {            
                    $excel->sheet('Plantilla', function($sheet) {
                        $products = ["fecha","hora","placa","volumen","valor_factura"];
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


    //Carga Masivo Supervisores
    public function cargarMasivoSupervisores(Request $request)
    {
        //obtenemos el campo file definido en el formulario
       $file = $request->file('archivo_excel_masivo');

       if($file == null)
        {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"No ha seleccionado níngun archivo");
            return Redirect::to('/redes/ordenes/orden');
        }

       //Varificamos que carge un .xlsx
       $mime = $file->getMimeType();
       if($mime != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
       {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"Tipo de archivo invalido, tiene que carga un archivo .xlsx");
            return Redirect::to('/redes/ordenes/orden');
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

            if(isset($results[0]["ot"]) == false ||
                    isset($results[0]["supervisor_cedula"]) == false ||
                    isset($results[0]["supervidor_nombre"]) == false ||
                    isset($results[0]["wbs"]) == false )
            {
                Session::flash('dataExcel2',"El archivo que esta tratando de cargar no tiene el formato específico.");                  
            }
            else
            {
                
                for ($i=0; $i < count($results); $i++) {

                    if($results[$i]["ot"] == "" || $results[$i]["ot"] == NULL)
                        continue;

                    $ot = $results[$i]["ot"];
                    $supervisor_cedula = $results[$i]["supervisor_cedula"];
                    $supervidor_nombre = $results[$i]["supervidor_nombre"];

                    $contP = DB::table($this->tblAux . 'ordenes')
                                ->where('id_orden',$ot)
                                ->count();

                   
                    if($contP == 0)
                    {
                        $maniObraA .= "\n- La ManiObra $maniObra no existe";
                        continue;
                    }

                    if($supervisor_cedula == "" || $supervisor_cedula == null)
                        continue;
                    
                    if($supervidor_nombre == "" || $supervidor_nombre == null)
                        continue;



                    DB::Table('rds_gop_ordenes')
                        ->where('id_orden',$ot)
                        ->update(
                            array(
                                    'supervisor_ejecutor' => $supervidor_nombre,
                                    'supervisor_ejecutor_cedula' => $supervisor_cedula,
                                )
                            );
                   
                    
                }
                //var_dump("Se esta cargando el documento en excel, espero un momento");
            }

            if($maniObraA != "")
                $maniObraA  = " Se encontraron las siguiente ManiObras que no existen:\n" . $maniObraA;

            if($maniObraA != "" || $estadosA != "")
                Session::flash('dataExcel2',$maniObraA . "\n" . $estadosA);     
            //var_dump($results);
        })->get();
        
        Session::flash('dataExcel1',"Se ha cargado correctamente el Masivo de supervisores");

        //var_dump("todo BIEN");
      return Redirect::to('/redes/ordenes/orden');
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
                $datoss = array(
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
                        );
                // echo "if 1_1 ".$this->tblAux . 'proyectos' . $this->valorT;
               /// print_r($datoss);
                
                DB::table($this->tblAux . 'proyectos' . $this->valorT)
                ->insert(array($datoss));
               // echo "despues";
                self::saveLog("OPERA01",$proy,"");

               // echo "despues22";
                //Crear WBS Inicial
                $consecutivo = self::generaConsecutivo("ID_WS");
                DB::table($this->tblAux . 'ws'  . $this->valorT)
                ->insert(array(
                    array(
                        'id_ws' => $consecutivo,
                        'id_proyecto' => $proy,
                        'nombre_ws' => "1"
                