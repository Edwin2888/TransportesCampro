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

                                    ELSE rds_gop_ordenes.gom
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

                                    ELSE rds_gop_ordenes.gom
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

           /*
           echo "<pre>";
           echo $sql; 
           echo "</pre>";
           return;
           */

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
            'correos' => $correos,
            'id_restriccion' => $id
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
         catch (\PDOException $e ) 
        {
            DB::rollBack();
            //echo "se fue al error db ".$e;
            
            
            return response()->json("0");
        }
        catch(Exception $e)
        {
            DB::rollBack();
            //echo "se fue al error ".$e;
            
            
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
                        ->where('id_proyecto',$proyecto)
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
                    $this->tblAux . 'nodos' . $this->valorT . '.observaciones',$this->tblAux . 'nodos' . $this->valorT . '.gom')
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
         
        $gom = $request->all()['gom'];
        $id_estado_gom = $request->all()['id_estado_gom'];

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
                    'gom' => $gom,
                    'id_estado_gom' => $id_estado_gom
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
               
               ->leftJoin($this->tblAux . 'estado_gom',$this->tblAux . 'estado_gom.id','=', $this->tblAux . 'nodos' . $this->valorT . '.id_estado_gom')
                     
                    
                ->where($this->tblAux . 'nodos' . $this->valorT . '.id_proyecto',$pro)
                ->select($this->tblAux . 'ws' . $this->valorT . '.id_ws as ws',$this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.id_estado',
                    $this->tblAux . 'nodos' . $this->valorT . '.direccion',$this->tblAux . 'nodos' . $this->valorT . '.nivel_tension',$this->tblAux . 'nodos' . $this->valorT . '.punto_fisico'
                    ,$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo as idN',
                    $this->tblAux . 'nodos' . $this->valorT . '.observaciones',$this->tblAux . 'nodos' . $this->valorT . '.gom',$this->tblAux . 'estado_gom.descripcion',$this->tblAux . 'nodos' . $this->valorT . '.id_estado_gom')
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
                    'gom' => $gom,
                    'id_estado_gom' => $id_estado_gom
                    ));

            self::saveLog("OPERA41",$pro,"NODO: $baremo - $nodoNombre WBS:$wbs");

            $nodos = DB::table($this->tblAux . 'nodos'. $this->valorT)
                ->join($this->tblAux . 'ws_nodos' . $this->valorT,$this->tblAux . 'ws_nodos' . $this->valorT . '.id_nodo1','=',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo')
                ->join($this->tblAux . 'ws' . $this->valorT,$this->tblAux . 'ws' . $this->valorT . '.id_ws','=',$this->tblAux . 'ws_nodos' . $this->valorT . '.id_ws')
                
                ->leftJoin($this->tblAux . 'estado_gom',$this->tblAux . 'estado_gom.id','=', $this->tblAux . 'nodos' . $this->valorT . '.id_estado_gom')
               
                ->where($this->tblAux . 'nodos' . $this->valorT . '.id_proyecto',$pro)
                ->select($this->tblAux . 'ws' . $this->valorT . '.id_ws as ws',$this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.id_estado',
                    $this->tblAux . 'nodos' . $this->valorT . '.direccion',$this->tblAux . 'nodos' . $this->valorT . '.nivel_tension',$this->tblAux . 'nodos' . $this->valorT . '.punto_fisico'
                    ,$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo as idN',
                    $this->tblAux . 'nodos' . $this->valorT . '.observaciones',$this->tblAux . 'nodos' . $this->valorT . '.gom',$this->tblAux . 'estado_gom.descripcion',$this->tblAux . 'nodos' . $this->valorT . '.id_estado_gom')
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
            $anio = date("Y");
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

            $otWithGOM = DB::table($this->tblAux . 'ordenes')
                    ->where('gom',$gomA)
                    ->get(['id_orden','id_proyecto']);

            if(count($otWithGOM) > 0)
            {

                $proyectoN = DB::Table($this->tblAux . 'proyectos')
                        ->where('id_proyecto',$otWithGOM[0]->id_proyecto)
                        ->value('nombre');

                $id_pro = str_replace("PRY00","",str_replace("PRY000","",str_replace("PRY0000", "", $otWithGOM[0]->id_proyecto)));

                return response()->json([
                        "id" => -1,
                        "msg" => "No puede eliminar la GOM por que esta siendo utilizada en la OT N° " . $otWithGOM[0]->id_orden .  ", en el proyecto " . $proyectoN . "(" . $id_pro . ")" ]);
            }

            DB::Table($this->tblAux . 'ws_gom')
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

            self::saveLog("OPERA13",$proy,"GOM: $gomA  WBS:$wbs");

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomwbs',
                array(
                "gomwbs" => $dbGOMWBS
            ))->render()); 
        }

        if($opc == "8") // Update GOM WBS
        {
            $wbs = $request->all()['wbs'];
            $proy = $request->all()['proy'];
            $gomA = trim($request->all()['gomA']);
            $gomN = trim($request->all()['gomN']);

            DB::Table($this->tblAux . 'ws_gom')
                ->where('id_proyecto',$proy)
                ->where('id_ws',$wbs)
                ->where('id_gom',$gomA)
                ->update(array(
                    'id_gom' => $gomN
                    ));

            //Actualiza la GOM en las ordenes
            DB::table($this->tblAux . 'ordenes')
                    ->where('gom',$gomA)
                    ->update(array(
                        'gom' => $gomN
                    ));

            $dbGOMWBS = DB::table($this->tblAux . 'ws_gom')
                        ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_gom.id_ws')
                        ->where($this->tblAux . 'ws_gom.id_proyecto',$proy)
                        ->where($this->tblAux . 'ws.id_proyecto',$proy)
                        ->where($this->tblAux . 'ws.id_ws',$wbs)
                        ->select('nombre_ws',$this->tblAux . 'ws.id_ws','id_gom','id_estado')
                        ->get();

            self::saveLog("OPERA12",$proy,"GOM ACTUAL:$gomA  GOM NUEVA: $gomN  WBS:$wbs");

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

           // $anio = 2017;
            $anio = date("Y");
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


/*
        $cad = "SELECT *
            FROM
            (SELECT ws,nombre_nodo,cd,TBL1.id_estado,TBL1.direccion,TBL1.nivel_tension,TBL1.punto_fisico,TBL1.seccionador
            ,TBL1.nombre_ws,TBL1.idN,ws_gom.id_gom ,ws_gom.id_estado as estado_gom,estado.nombre_gom,
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
            LEFT JOIN pcc_gop_estados_gom as estado ON estado.id_estado_gom = ws_gom.id_estado
            GROUP BY ws,nombre_nodo,cd,TBL1.id_estado,TBL1.direccion,TBL1.nivel_tension,TBL1.punto_fisico,TBL1.seccionador
            ,TBL1.nombre_ws,TBL1.idN,ws_gom.id_gom,ws_gom.id_estado,estado.nombre_gom
            ) as TBL2
            WHERE TBL2.cant = 1
            ORDER BY nombre_ws asc,nombre_nodo asc";

*/

        $cad = "  
                SELECT *
                          FROM
                          (SELECT ws,nombre_nodo,cd,TBL1.id_estado,TBL1.direccion,TBL1.nivel_tension,TBL1.punto_fisico,TBL1.seccionador
                          ,TBL1.nombre_ws,TBL1.idN,ws_gom.id_gom ,ws_gom.id_estado as estado_gom,estado.nombre_gom,
                              row_number() 
                                over (partition by TBL1.idN order by nombre_nodo) as cant,
                                TBL1.gom,TBL1.descripcion,TBL1.id_estado_gom
                          FROM 
                              (select ws.id_ws as ws,nodos.nombre_nodo,nodos.cd,nodos.id_estado,nodos.direccion,nodos.nivel_tension,nodos.punto_fisico,
                              nodos.seccionador,ws.nombre_ws,nodos.id_nodo as idN,nodos.gom,egom.descripcion,nodos.id_estado_gom
                               from rds_gop_nodos as nodos
                              inner join rds_gop_ws_nodos as ws_nodos on 
                              (ws_nodos.id_nodo1 = nodos.id_nodo and 
                               ws_nodos.id_proyecto=nodos.id_proyecto
                              )
                              INNER JOIN rds_gop_ws as ws on 
                              (ws.id_ws = ws_nodos.id_ws and 
                               ws.id_proyecto = nodos.id_proyecto )
                               left join rds_gop_estado_gom as egom
                               on (egom.id = nodos.id_estado_gom)
                              WHERE nodos.id_proyecto = '" . $proyecto . "') AS TBL1
                          LEFT JOIN rds_gop_ws_gom as ws_gom on (
                          ws_gom.id_ws  = TBL1.ws and 
                          ws_gom.id_proyecto = '" . $proyecto . "'
                          )
                          LEFT JOIN pcc_gop_estados_gom as estado ON estado.id_estado_gom = ws_gom.id_estado
                          GROUP BY ws,nombre_nodo,cd,TBL1.id_estado,TBL1.direccion,TBL1.nivel_tension,TBL1.punto_fisico,TBL1.seccionador
                          ,TBL1.nombre_ws,TBL1.idN,ws_gom.id_gom,ws_gom.id_estado,estado.nombre_gom,TBL1.gom,TBL1.descripcion,TBL1.id_estado_gom
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
                    'descargo3','descargo4','descargo5','descargo6','descargo7','x','y','fecha_ejecucion_final','vyp','inc','supervisor_ejecutor_cedula','supervisor_programador_cedula','gestor_materiales','gestor_descargos','hora_corte_largo')
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
////
        $nodosAfectados = DB::table($this->tblAux . 'ordenes_manoobra')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_manoobra.id_nodo')
                            ->join($this->tblAux . 'ws_nodos' . $this->valorT,$this->tblAux . 'ws_nodos' . $this->valorT . '.id_nodo1','=',$this->tblAux . 'ordenes_manoobra.id_nodo')
                            ->join($this->tblAux . 'ws' . $this->valorT,$this->tblAux . 'ws' . $this->valorT . '.id_ws','=',$this->tblAux . 'ws_nodos' . $this->valorT . '.id_ws')
                            ->where($this->tblAux . 'nodos.id_proyecto',$proyecto)
                            ->where($this->tblAux . 'ws_nodos.id_proyecto',$proyecto)                
                            ->where($this->tblAux . 'ordenes_manoobra.id_proyecto',$proyecto)
                            ->where($this->tblAux . 'ws.id_proyecto',$proyecto)
                            ->where($this->tblAux . 'ordenes_manoobra.id_orden',$orden)
                            ->select($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.direccion',
                                $this->tblAux . 'nodos' . $this->valorT . '.punto_fisico',$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                $this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'ws' . $this->valorT . '.id_ws',
                                $this->tblAux . 'nodos' . $this->valorT.'.gom'    )
                            ->groupBy($this->tblAux . 'nodos' . $this->valorT . '.nombre_nodo',$this->tblAux . 'nodos' . $this->valorT . '.cd',$this->tblAux . 'nodos' . $this->valorT . '.direccion',
                                $this->tblAux . 'nodos' . $this->valorT . '.punto_fisico',$this->tblAux . 'nodos' . $this->valorT . '.seccionador',$this->tblAux . 'nodos' . $this->valorT . '.id_nodo',
                                $this->tblAux . 'ws' . $this->valorT . '.nombre_ws',$this->tblAux . 'ws' . $this->valorT . '.id_ws',$this->tblAux . 'nodos' . $this->valorT.'.gom' )
                            ->orderBy($this->tblAux . 'ws' . $this->valorT . '.nombre_ws','asc')
                            ->get();
/////////////////////////////
        
        
        
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


        $supervisores = "select 
                    rh_personas.identificacion, 
                    (rh_personas.nombres + ' ' + rh_personas.apellidos) as nombre
                    from rh_personas
                    inner join rh_cargos
                    on (rh_cargos.id_cargo = rh_personas.id_cargo ) 
                    where rh_personas.id_estado = 'EP03'
                    AND RH_PERSONAS.ID_PROYECTO = 'PJ00000002' 
                    and ( rh_cargos.nombre like ('%SUPERV%') OR rh_cargos.nombre like ('%INGENI%')  OR rh_cargos.nombre like ('%TECN%LOGO%') )
                    order by (rh_personas.nombres + ' ' + rh_personas.apellidos) asc
                ";
        
        /*
        $supervisores =  "
                    
                    select
                        distinct
                        c.id_supervisor as identificacion,
                        CONCAT(
                                p.nombres,' ',
                                IIF(p.apellidos is not null,p.apellidos,CONCAT(p.apellido1,' ',p.apellido2)) 
                               ) as nombre 
                      from rds_gop_cuadrilla as c inner join rh_personas as p on (
                          c.id_supervisor = p.identificacion
                       )

                       where c.id_estado='A' and
                      c.id_supervisor is not null and
                      c.id_supervisor <> '' and
                      p.id_estado = 'EP03' AND 
                      p.ID_PROYECTO = 'PJ00000002' 
                      order by 
                      CONCAT(
                                p.nombres,' ',
                                IIF(p.apellidos is not null,p.apellidos,CONCAT(p.apellido1,' ',p.apellido2)) 
                               )  asc
                 ";
         */

        $supervisores = DB::select("SET NOCOUNT ON;" . $supervisores);
       /*
        $gestores = "select
               Personas.identificacion as identificacion,
               Usuarios.propietario as nombre
                from sis_usuarios Usuarios
                inner join rh_personas Personas
                on (Personas.id_persona = Usuarios.id_persona)
                where Usuarios.gestor = 'S'
        ";*/
        $gestores = "  select
                        P.identificacion as identificacion,
                        U.propietario as nombre
                         from sis_usuarios U 
                         inner join rh_personas P
                         on (P.id_persona = U.id_persona)
                         where U.gestor = 'S' and u.id_estado='CA' and 
                         p.id_estado='EP03'";

        $gestores = DB::select("SET NOCOUNT ON;" . $gestores);

        /*
         /////////////////////////////////////////////////////////////////////
         /////////////////////////////////////////////////////////////////////
         /////////////////////////////////////////////////////////////////////
         /////////////////////////////////////////////////////////////////////
         */
        $tipomani = DB::connection('sqlsrv')
                ->table('rds_gop_tipo_maniobra')
                ->orderBy('descripcion')
                ->where('id','<',3)
                ->get();    
        

        $accmani = DB::connection('sqlsrv')
                ->table('rds_gop_accion_maniobra')
                ->orderBy('descripcion')
                ->get();  
        
        $elemmani = DB::connection('sqlsrv')
                ->table('rds_gop_elemento_maniobra')
                ->orderBy('descripcion')
                ->get();
        
        /*
         /////////////////////////////////////////////////////////////////////
         /////////////////////////////////////////////////////////////////////
         /////////////////////////////////////////////////////////////////////
         /////////////////////////////////////////////////////////////////////
         */
         
        $ordendet = DB::Table($this->tblAux . 'ordenes')
                            ->where('id_orden',$orden)
                            ->select('hora_apertura','operador_ccontrol_abre','hora_cierre_d','operador_ccontrol_cierra')
                            ->first();
        
        $eventos_bitacora_orden = DB::Table('tel_gop_bitacora')
                                    ->select('tel_gop_bitacora.*', 'sis_usuarios.propietario')
                                    ->where('id_orden', $orden)
                                    ->join('sis_usuarios','sis_usuarios.id_usuario','=', 'tel_gop_bitacora.id_usuario')
                                    ->orderBy('fecha_evento', 'DESC')
                                    ->get();
        /////
        return view('proyectos.redes.trabajoprogramado.orden',array(
            'ordendet' => $ordendet,
            'tipomani' => $tipomani,
            'accmani' => $accmani,
            'elemmani' => $elemmani,
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
            "supervisores" => $supervisores,
            "tipoProyecto" => $proyectoName[0]->tipo_proyecto,
            "dire" => $dire,
            "proyecto" => $proyecto,
            "orden" => $orden,
            "gestores" => $gestores,

            'eventos_bitacora_orden' => $eventos_bitacora_orden,
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


            $tipoProyecto = DB::table($this->tblAux . 'proyectos' )
                            ->where('id_proyecto',$proyecto)
                            ->value('tipo_proyecto');

            // Proyecto civiles ni cartas  no genera Materiales
            if($tipoProyecto == "T03" || $tipoProyecto == "T01")
                return response()->json("1");

            $materialesPostes = [6762448,6762449,6762450,6762451,6762452,6762453,6762454,6762457,6762458,6762464,6762467,6762470,6762472,6794544,6794545,6794547,6794548,6808660];

            return response()->json("1");

            // DC Generación
            try
            {
                DB::beginTransaction();
                foreach ($materialesxPersona as $mat => $val) {

                    //Consultamos los materiales asignados por nodo
                    $materialesxPerNodo = DB::table($this->tblAux . 'ordenes_materiales')
                            ->where('id_orden',$orden_proyecto)
                            ->where('id_persoanCargo',$val->persona)
                            ->select('id_nodo','id_articulo','cantidad_confirmada') //,
                            ->groupBy('id_nodo','id_articulo','cantidad_confirmada')
                            ->orderBY('id_nodo')
                            ->get();

                    //No existe material
                    if(count($materialesxPerNodo) == 0)
                        continue;

                    
                    //Recorremos los materiales por cada nodo
                    foreach ($materialesxPerNodo as $matP => $val1) {

                        $doc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                                ->where('id_orden',$orden_proyecto)
                                ->where('id_lider',$val->persona)
                                ->where('id_nodo',$val1->id_nodo)
                                ->where('id_tipo_documento',"T005")
                                ->select('id_documento','id_estado')
                                ->get();

                        if(count($doc) == 0) //No se ha generado un documento de DC
                        {
                            $codigoSapArticulo = DB::Table($this->tblAux1 . 'inv_maestro_articulos')
                                        ->where('id_articulo',$val1->id_articulo)
                                        ->whereIn(DB::raw('LTRIM(RTRIM(codigo_sap))'),$materialesPostes)
                                        ->count();

                            //Generamos consecutivos
                            $codDocumento = self::dame_uncodigo_almacen("T005");

                            $id_al = "0000";
                            if($this->tblAux1 == "rds_")
                                $id_al = "AR09";

                            if($codigoSapArticulo == 0)
                                $bode = 'RDSCOD';
                            else
                                $bode = 'RDSCODPER';

                            //Consultamos bodega del 
                            $bodega = DB::Table('rds_inv_bodegas')
                                        ->where('id_bodega',$bode)
                                        ->value('nombre');

                            //Cabeza de la bodega - Proyecto
                            $nombre = $bodega . " (" . str_replace("PRY00","",str_replace("PRY000","",str_replace("PRY0000", "", $proyecto))) . ")";
                            
                            //Consultamos la WBS y Nodo a trabajar
                            $wbsNodo = DB::Table($this->tblAux . 'ws_nodos')
                                        ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_nodos.id_ws')
                                        ->join($this->tblAux . 'nodos',$this->tblAux . 'nodos.id_nodo','=',$this->tblAux . 'ws_nodos.id_nodo1')
                                        ->where($this->tblAux . 'ws_nodos.id_proyecto',$proyecto)
                                        ->where($this->tblAux . 'nodos.id_nodo',$val1->id_nodo)
                                        ->select('nombre_ws','nombre_nodo')
                                        ->get()[0];

                           // Generamos documento DC                         
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
                                        "fecha_edicion" => $this->fechaALong,
                                        "id_usuario_autoriza_despacho" => Session::get('user_login'),
                                        "fecha_autoriza_despacho" => $this->fechaALong,
                                        'id_proyecto' => $proyecto,
                                        'id_tipo_orden' => 'T56'
                                        )
                                    ));

                                
                                self::saveLog("OPERA48",$codDocumento,"ORDEN " . $orden_proyecto); 

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

                            $id_al = "0000";
                            if($this->tblAux1 == "rds_")
                                $id_al = "AR09";

                            $codigoSapArticulo = DB::Table($this->tblAux1 . 'inv_maestro_articulos')
                                        ->where('id_articulo',$val1->id_articulo)
                                        ->whereIn(DB::raw('LTRIM(RTRIM(codigo_sap))'),$materialesPostes)
                                        ->count();

                            if($codigoSapArticulo == 0)
                                $bode = 'RDSCOD';
                            else
                                $bode = 'RDSCODPER';

                             $documentoXbodega = DB::table($this->tblAux1  . 'inv_documentos' . $this->valorT)
                                    ->where('id_bodega_destino',$val->persona)
                                    ->where('id_bodega_origen',$bode)
                                    ->where('id_nodo',$val1->id_nodo)
                                    ->where('fecha',$fechaE)
                                    ->where('id_orden',$orden_proyecto)
                                    ->get(['id_documento','id_estado']);

                            //No tiene un DC para la bodega seleccionada, en caso de que el material sea Poste
                            if(count($documentoXbodega) == 0)
                            {
                                $codDocumento = self::dame_uncodigo_almacen("T005");

                                                            //Consultamos bodega del 
                                $bodega = DB::Table('rds_inv_bodegas')
                                            ->where('id_bodega',$bode)
                                            ->value('nombre');

                                //Cabeza de la bodega - Proyecto
                                $nombre = $bodega . " (" . str_replace("PRY00","",str_replace("PRY000","",str_replace("PRY0000", "", $proyecto))) . ")";
                                
                                //Consultamos la WBS y Nodo a trabajar
                                $wbsNodo = DB::Table($this->tblAux . 'ws_nodos')
                                            ->join($this->tblAux . 'ws',$this->tblAux . 'ws.id_ws','=',$this->tblAux . 'ws_nodos.id_ws')
                                            ->join($this->tblAux . 'nodos',$this->tblAux . 'nodos.id_nodo','=',$this->tblAux . 'ws_nodos.id_nodo1')
                                            ->where($this->tblAux . 'ws_nodos.id_proyecto',$proyecto)
                                            ->where($this->tblAux . 'nodos.id_nodo',$val1->id_nodo)
                                            ->select('nombre_ws','nombre_nodo')
                                            ->get()[0];

                               // Generamos documento DC                         
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
                                            "fecha_edicion" => $this->fechaALong,
                                            "id_usuario_autoriza_despacho" => Session::get('user_login'),
                                            "fecha_autoriza_despacho" => $this->fechaALong,
                                            'id_proyecto' => $proyecto,
                                            'id_tipo_orden' => 'T56'
                                            )
                                        ));

                                    
                                        self::saveLog("OPERA48",$codDocumento,"ORDEN " . $orden_proyecto); 

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
                                $codDocumento = $documentoXbodega[0]->id_documento;    

                                //Documento generados
                                if($documentoXbodega[0]->id_estado == "E1")
                                {
                                    $detalleArti = DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                                ->where('id_documento',$codDocumento)
                                                ->where('id_articulo',$val1->id_articulo)
                                                ->select('solicitado')
                                                ->get();

                                    //Guardamos cuerpo
                                    if(count($detalleArti) == 0)
                                    {
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
                                    }
                                    else
                                    {
                                        DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                                                ->where('id_documento',$codDocumento)
                                                ->where('id_articulo',$val1->id_articulo)
                                                ->update(array(
                                                    'solicitado' => $val1->cantidad_confirmada,
                                                ));

                                        self::saveLog("OPERA49",$codDocumento,"ARTICULO " . $val1->id_articulo);
                                    }
                                }
                                else
                                {
                                    //Documento en otro estado, toca generar un Dc nuevo
                                }
                            }
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


        $cdOtro = explode(",",$cd);
    
        $nuevafecha = strtotime ( '-8 days' , strtotime ( $fechEj ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $cdYaTRabajados = "";

        $tipo_pry = DB::Table($this->tblAux . 'proyectos' . $this->valorT)
                            ->where('id_proyecto',$proyecto)
                            ->value('tipo_proyecto');

        if($tipo_pry == "T02")
        {
            //Consulta si tengo más de 3 ordenes para la misma fecha y la misma hora 
            $ordenesFranja = DB::Table($this->tblAux .'ordenes')
                            ->where('hora_corte_largo',$horaC)
                            ->where('id_tipo','T56')
                            ->where('id_orden','<>',$orden_proyecto)
                            ->where('id_estado','<>','A1')
                            ->whereBetween('fecha_ejecucion',[$fechEj . " 00:00:00",$fechEj . " 23:59:59"])
                            ->count();

            if($ordenesFranja > 2)
                //return response()->json("-7");

            for ($i=0; $i < count($cdOtro); $i++) 
            { 
                $dato = str_replace(" ","",trim($cdOtro[$i]));

                $datoCD = DB::Table($this->tblAux .'ordenes')
                            ->where('cd','LIKE','%' . $dato . '%')
                            ->where('id_tipo','T56')
                            ->where('id_orden','<>',$orden_proyecto)
                            ->where('id_estado','<>','A1')
                            ->whereBetween('fecha_ejecucion',[$nuevafecha . " 00:00:00",$fechEj . " 23:59:59"])
                            ->count();

                if($datoCD > 0)
                    $cdYaTRabajados .= ' <br> - ' . $dato;
            }

            if($cdYaTRabajados != "")
                return response()->json($cdYaTRabajados);
        }


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
                            'hora_corte_largo' => $horaC,
                            'hora_cierre' => $horaCi,
                            'hora_fin' => $horaF,
                            'observaciones' => $obser,
                            'gom_adec' => $gomA,
                            'gom_inst' => null,
                            //'gom_inst' => $gomI,
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
                            'supervisor_ejecutor_cedula' => $request->all()['soliSupEjeCedula'], 
                            'supervisor_programador_cedula' => $request->all()['supervisor_programador_cedula'], 

                            'gestor_materiales' => $request->all()['gestor_materiales'],
                            'gestor_descargos' => $request->all()['gestor_descargos'],
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


                $cdOtro = explode(",",$cd);
    
                $nuevafecha = strtotime ( '-8 days' , strtotime ( $fechEj ) ) ;
                $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

                $cdYaTRabajados = "";

                $tipo_pry = DB::Table($this->tblAux . 'proyectos' . $this->valorT)
                            ->where('id_proyecto',$proyecto)
                            ->value('tipo_proyecto');

                if($tipo_pry != "T03" && $tipo_pry != 'T04')
                {
                    for ($i=0; $i < count($cdOtro); $i++) 
                    { 
                        $dato = str_replace(" ","",trim($cdOtro[$i]));

                        $datoCD = DB::Table($this->tblAux .'ordenes')
                                    ->where('cd','LIKE','%' . $dato . '%')
                                    ->where('id_tipo','T56')
                                    ->where('id_orden','<>',$orden_proyecto)
                                    ->where('id_estado','<>','A1')
                                    ->whereBetween('fecha_ejecucion',[$nuevafecha . " 00:00:00",$fechEj . " 23:59:59"])
                                    ->count();

                        if($datoCD > 0)
                            $cdYaTRabajados .= ' <br> - ' . $dato;
                    }

                    if($cdYaTRabajados != "")
                        return response()->json($cdYaTRabajados);
                }

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
                            'hora_corte_largo' => $horaC,
                            'hora_cierre' => $horaCi,
                            'hora_fin' => $horaF,
                            'observaciones' => $obser,
                            'gom_adec' => $gomA,
                            /*'gom_inst' => $gomI,*/
                            'gom_inst' => null,
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
                            'supervisor_ejecutor_cedula' => $request->all()['soliSupEjeCedula'], 
                            'supervisor_programador_cedula' => $request->all()['supervisor_programador_cedula'], 
                            'gestor_materiales' => $request->all()['gestor_materiales'],
                            'gestor_descargos' => $request->all()['gestor_descargos'],
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
            $anio = date("Y");
            if($this->tblAux == "apu_gop_")
                $anio = 2016;

            $nodosA = "";
            $wbsA = "";
            $arregloN = [];
            $arregloWBS = [];

            $orden = $request->all()["orden"];
            
            
            $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$orden."'; ";
            $registro = DB::select($sql);
         
            if(isset($registro[0])  && isset($registro[0]->anio) ){
               $anio =  $registro[0]->anio;
            }

            
            
            
            $proyecto = $request->all()["proyecto"];

            for ($i=0; $i < count($request->all()["nod"]) ; $i++) { 
                $exist = 0;
                $exist2 = 0;

                $wbs = DB::table($this->tblAux . "ws as tbl1")
                        ->join($this->tblAux . "ws_nodos as tbl2",'tbl1.id_ws','=','tbl2.id_ws')
                        ->where('id_nodo1',$request->all()["nod"][$i])
                        ->where('tbl2.id_proyecto',$proyecto)
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
                        ->where($this->tblAux . 'nodos.id_proyecto',$proyecto)
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
            $pro = null;
            if( isset($request->all()["pro"])){
                $pro = $request->all()["pro"];
            }else{
                /*
                $ordenresp = DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                ->where( $this->tblAux . 'ordenes_manoobra_detalle.id_orden',$ot)
                ->where( $this->tblAux . 'ordenes_manoobra_detalle.id_lider',$lider)
                ->select($this->tblAux . 'ordenes_manoobra_detalle.id_proyecto')
                ->get();*/
                $sql = "select 
                           top 1 id_proyecto 
                        from ".$this->tblAux ."ordenes_manoobra_detalle
                        where 
                            id_orden like '%".$ot."' and 
                            id_lider = '".$lider."'  
                      ";
                //echo $sql;
                $ordenresp = DB::select($sql);
                //print_r($ordenresp);
                $pro = $ordenresp[0]->id_proyecto;
            }

            for ($i= (strlen($ot) + 2); $i < 12; $i++) { 
                $ot = "0" . $ot;
            }
            $ot = "OT" . $ot;

            $ordenT = DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                ->leftJoin($this->tblAux . 'ordenes_materiales_documentos',$this->tblAux . 'ordenes_materiales_documentos.id_lider','=',$this->tblAux . 'ordenes_manoobra_detalle.id_lider')
                ->where($this->tblAux . 'ordenes_materiales_documentos.id_orden',$ot)
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_orden',$ot)
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_estado',0)
                ->where($this->tblAux . 'ordenes_manoobra_detalle.id_proyecto',$pro)
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
                    ->where('id_proyecto',$pro)
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
                id_documento,id_documento_cs,propietario,fecha,'-1' as cantidad,
                    ISNULL((
                        select top(1) id_estado_nodo
                        FROM rds_gop_ordenes_manoobra AS pro
                        WHERE
                        pro.id_orden = '$ot' and
                        pro.id_proyecto = '$pro'    
                        and pro.id_nodo = tbl2.id_nodo
                        and tipo_ingreso = 0    
                        and pro.id_personaCargo = '$lider'
                        GROUP BY id_estado_nodo
                    ),'NA') as id_estado
                FROM
                rds_gop_ordenes_materiales_documentos as tbl1 
                inner join rds_gop_nodos as tbl2 on tbl1.id_nodo = tbl2.id_nodo
                left join sis_usuarios as tbl3 on tbl3.id_usuario = tbl1.id_user
                WHERE tbl1.id_orden = '$ot' and 
                    tbl2.id_proyecto='$pro' 
                AND tbl1.id_estado = 0
                AND tbl1.id_lider = '$lider'
                AND  id_documento <> ''
                GROUP BY tbl2.nombre_nodo,tbl2.id_nodo,
                id_documento,id_documento_cs,propietario,fecha
                UNION
                SELECT tbl2.nombre_nodo,tbl2.id_nodo,
                '' as id_documento,'' as id_documento_cs,propietario,tbl1.fecha,count(mobra.id_orden) as cantidad,
                    ISNULL((
                        select top(1) id_estado_nodo
                        FROM rds_gop_ordenes_manoobra AS pro
                        WHERE
                        pro.id_orden = '$ot' and 
                        pro.id_proyecto = '$pro'    
                        and pro.id_nodo = tbl2.id_nodo
                        and tipo_ingreso = 0
                        and pro.id_personaCargo = '$lider'
                        GROUP BY id_estado_nodo
                    ),'NA') as id_estado
                FROM
                rds_gop_ordenes_manoobra as tbl1 
                inner join rds_gop_nodos as tbl2 on tbl1.id_nodo = tbl2.id_nodo
                left join sis_usuarios as tbl3 on tbl3.id_usuario = tbl1.id_user
                left join rds_gop_mobra as mobra on mobra.id_orden = tbl1.id_orden
                        and mobra.id_origen = tbl1.id_personaCargo
                WHERE tbl1.id_orden = '$ot' and 
                    tbl2.id_proyecto='$pro' 
                AND tbl1.id_estado = 0
                AND tbl1.id_personaCargo = '$lider'
                GROUP BY tbl2.nombre_nodo,tbl2.id_nodo,propietario,tbl1.fecha"  ;


                $nodosAfectados = DB::select("SET NOCOUNT ON;" . $cad);


            if(count($nodosAfectados) == 0)
            {
                $nodosAfectados = DB::table($this->tblAux . 'ordenes_manoobra')
                            ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT . '.id_nodo','=',$this->tblAux . 'ordenes_manoobra.id_nodo')
                            ->where($this->tblAux . 'ordenes_manoobra.id_orden',$ot)
                            ->where($this->tblAux . 'ordenes_manoobra.id_proyecto',$pro)
                            ->where($this->tblAux . 'nodos.id_proyecto',$pro)
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


            $apruebaEjecucion = DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                                        ->where('id_orden',$ot)
                                        ->where('id_lider',$lider)
                                        ->where('id_proyecto',$pro)
                                        ->value('aprobacion');

            $obserEjecucion = DB::Table($this->tblAux . 'ordenes_manoobra_detalle')
                                        ->where('id_orden',$ot)
                                        ->where('id_lider',$lider)
                                        ->where('id_proyecto',$pro)
                                        ->value('observacion_aprueba');


            $datosProyecto = DB::table($this->tblAux . 'ordenes')
                       ->where($this->tblAux . 'ordenes.id_orden',$ot)
                       ->where($this->tblAux . 'ordenes.id_proyecto',$pro)
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
                       ->where($this->tblAux . 'ordenes.id_proyecto',$pro)
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
                    "aprueba" => $apruebaEjecucion,
                    "obseraprueba" => $obserEjecucion,
                    "id_proyecto" => $pro,
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
            $anio = date("Y");
            if($this->tblAux == "apu_gop_")
                $anio = 2016;


            
            $datosProyectoID = DB::table($this->tblAux . 'ordenes')
                       ->where($this->tblAux . 'ordenes.id_orden',$orden)
                       ->value('id_proyecto');  
 
            
            $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$orden."'; ";
            $registro = DB::select($sql);
         
            if(isset($registro[0])  && isset($registro[0]->anio) ){
               $anio =  $registro[0]->anio;
            }

            
            $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$orden."'; ";
            $registro = DB::select($sql);
         
            if(isset($registro[0])  && isset($registro[0]->anio) ){
               $anio =  $registro[0]->anio;
            }

            ////////////////////////////////
            /////////////////////////////////
            
            
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
                    ->where('id_proyecto',$proyectoOrden)
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
                        ->where('id_proyecto',$proyectoOrden)
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
                        ->where('id_proyecto',$proyectoOrden)
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
                            ->where('id_proyecto',$proyectoOrden)
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
                            //->where('periodo',2017)
                            ->where('periodo',date("Y")) 
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
                            ->where('id_proyecto',$proyectoOrden)
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

            $preplanillados = DB::table($this->tblAux .'ordenes_manoobra')
                            ->where('id_nodo',$nodo)
                            ->where('id_orden',$orden)
                            ->where('id_personaCargo',$lider)
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
                    "pre" => $preplanilla,
                    "pred" => $preplanillados    ))->render()); 
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
                "pre" => $preplanilla,
                "pred" => $preplanillados))->render()); 
        }

        if($opc == "8") //Guarda Ejecución
        {
            $lider = $request->all()['lid'];
            $orden = $request->all()['ot'];
            $nodo = $request->all()['nodo'];
            $prepla = $request->all()['pla'];
            $preplad = $request->all()['plad'];
            
            for ($i= (strlen($orden) + 2); $i < 12; $i++) { 
                $orden = "0" . $orden;
            }
            $orden = "OT" . $orden;

            
            
                        DB::table($this->tblAux .'ordenes_manoobra')
                            ->where('id_nodo',$nodo)
                            ->where('id_orden',$orden)
                            ->where('id_personaCargo',$lider)
                            ->update(
                                array(
                                    'preplanilla' => $preplad
                                    ));

            
            

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
                        $anio = date("Y");
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

              $cad = "SELECT tbl2.nombre_nodo,tbl2.id_nodo,
                id_documento,id_documento_cs,propietario,fecha,'-1' as cantidad,
                    ISNULL((
                        select top(1) id_estado_nodo
                        FROM rds_gop_ordenes_manoobra AS pro
                        WHERE
                        pro.id_orden = '$orden'
                        and pro.id_nodo = tbl2.id_nodo
                        and tipo_ingreso = 0    
                        and pro.id_personaCargo = '$lider'
                        GROUP BY id_estado_nodo
                    ),'NA') as id_estado
                FROM
                rds_gop_ordenes_materiales_documentos as tbl1 
                inner join rds_gop_nodos as tbl2 on tbl1.id_nodo = tbl2.id_nodo
                left join sis_usuarios as tbl3 on tbl3.id_usuario = tbl1.id_user
                WHERE tbl1.id_orden = '$orden'
                AND tbl1.id_estado = 0
                AND tbl1.id_lider = '$lider'
                AND  id_documento <> ''
                GROUP BY tbl2.nombre_nodo,tbl2.id_nodo,
                id_documento,id_documento_cs,propietario,fecha
                UNION
                SELECT tbl2.nombre_nodo,tbl2.id_nodo,
                '' as id_documento,'' as id_documento_cs,propietario,tbl1.fecha,count(mobra.id_orden) as cantidad,
                    ISNULL((
                        select top(1) id_estado_nodo
                        FROM rds_gop_ordenes_manoobra AS pro
                        WHERE
                        pro.id_orden = '$orden'
                        and pro.id_nodo = tbl2.id_nodo
                        and tipo_ingreso = 0
                        and pro.id_personaCargo = '$lider'
                        GROUP BY id_estado_nodo
                    ),'NA') as id_estado
                FROM
                rds_gop_ordenes_manoobra as tbl1 
                inner join rds_gop_nodos as tbl2 on tbl1.id_nodo = tbl2.id_nodo
                left join sis_usuarios as tbl3 on tbl3.id_usuario = tbl1.id_user
                left join rds_gop_mobra as mobra on mobra.id_orden = tbl1.id_orden
                        and mobra.id_origen = tbl1.id_personaCargo
                WHERE tbl1.id_orden = '$orden'
                AND tbl1.id_estado = 0
                AND tbl1.id_personaCargo = '$lider'
                GROUP BY tbl2.nombre_nodo,tbl2.id_nodo,propietario,tbl1.fecha"  ;


                $nodosAfectados = DB::select("SET NOCOUNT ON;" . $cad);
                $tipo = "1";

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
            $anio = date("Y");
            if($this->tblAux == "apu_gop_")
                $anio = 2016;

            
            $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$orden."'; ";
            $registro = DB::select($sql);
         
            if(isset($registro[0])  && isset($registro[0]->anio) ){
               $anio =  $registro[0]->anio;
            }
            
            
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
                $anio = date("Y");
                if($this->tblAux == "apu_gop_")
                    $anio = 2016;
                
                
            $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$orden."'; ";
            $registro = DB::select($sql);
         
            if(isset($registro[0])  && isset($registro[0]->anio) ){
               $anio =  $registro[0]->anio;
            }
                

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

            $sql = "SELECT
                      rds_gop_ws.id_proyecto,
                      rds_gop_proyectos.nombre,
                      rds_gop_ws.id_ws,
                      rds_gop_ws.nombre_ws,
                      rds_gop_nodos.id_nodo,
                      rds_gop_nodos.nombre_nodo,
                      rds_gop_nodos.gom
                    FROM rds_gop_ws
                      INNER JOIN rds_gop_proyectos 
                        ON rds_gop_proyectos.id_proyecto = rds_gop_ws.id_proyecto
                      INNER JOIN rds_gop_nodos
                        ON rds_gop_ws.id_ws = rds_gop_nodos.id_ws

                    WHERE rds_gop_ws.id_proyecto = 'PRY0001234';";

            $gom_nodos = DB::select($sql);

            return response()->json(view('proyectos.redes.trabajoprogramado.secciones.tblgomimport',
                array(
                "gom"       => $gom,
                "gom_nodos" => $gom_nodos
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
                        //->where('periodo',2017)
                        ->where('periodo',date("Y"))
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
            $orden = $request->all()["orden"];

            $ot = explode("/",$ot)[2] . "-" . explode("/",$ot)[1] . "-" . explode("/",$ot)[0];

            $arregloA = [];
            for ($i=0; $i < count($lid); $i++) {
                //$anno=date("Y");
                $dinero = DB::table($this->tblAux . 'ordenes_manoobra as tbl1')
                        ->join($this->tblAux . 'ordenes as tbl2','tbl1.id_orden','=','tbl2.id_orden')
                        ->join($this->tblAux . 'baremos as tbl3','tbl1.id_baremo','=','tbl3.codigo')
                        ->whereBetween('tbl2.fecha_ejecucion',[$ot . " 00:00:00",$ot . " 23:59:59"])
                        ->where('id_personaCargo',$lid[$i]["lider"])
                        //->where('tbl3.periodo',2017)
                        ->where('tbl1.id_estado',0)
                        //INICIO AUMENTADO 04-07-2018 emmanuel    
                        ->whereRaw("tbl3.periodo = year(tbl2.fecha_ejecucion) ")
                        ->where('tbl2.id_orden',$orden)
                        //FIN AUMENTADO 04-07-2018 emmanuel
                        ->select(DB::raw("SUM(tbl1.cantidad_confirmada * tbl3.precio) as dinero"))
                        ->get()[0]->dinero;

                $tiempo = DB::table($this->tblAux . 'ordenes_manoobra as tbl1')
                        ->join($this->tblAux . 'ordenes as tbl2','tbl1.id_orden','=','tbl2.id_orden')
                        ->join($this->tblAux . 'baremos as tbl3','tbl1.id_baremo','=','tbl3.codigo')
                        ->whereBetween('tbl2.fecha_ejecucion',[$ot . " 00:00:00",$ot . " 23:59:59"])
                        ->where('id_personaCargo',$lid[$i]["lider"])
                        //->where('tbl3.periodo',2017)
                        //INICIO AUMENTADO 04-07-2018 emmanuel    
                        ->whereRaw("tbl3.periodo = year(tbl2.fecha_ejecucion) ")
                        ->where('tbl2.id_orden',$orden)
                        //FIN AUMENTADO 04-07-2018 emmanuel
                        ->where('tbl1.id_estado',0)
                        ->value(DB::raw("SUM(tbl3.tiempo_estimado_minutos) as dinero"));

                $meta = DB::table($this->tblAux . 'tipo_cuadrilla')
                        ->where('nombre', $lid[$i]["tipo"])
                        ->value('meta_produccion_pesos');
                // estaba en 6 horas, cambia por 10 04_07_2018 emmanuel
                $tiempoTotal = 10 * 60;
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

        if($opc == "34") //Update estado GOMS
        {
            $gom = $request->all()["gom"];
            $esta = $request->all()["esta"];

            DB::table($this->tblAux .'ws_gom')
                ->where('id_gom',$gom)
                ->update(array(
                    'id_estado' => $esta
                    ));

            self::saveLog("OPERA37",$gom,"ESTADO: $esta");

            return response()->json("1");
        }

        if($opc == "35") //Consulta versiones programación
        {
            $mes = $request->all()["mes"];
            $anio = $request->all()["anio"];

            $arr = DB::Table('rds_gop_versionamiento')
                       ->where('mes',$mes)
                       ->where('anio',$anio)
                       ->join('sis_usuarios','id_usuario','=','usuario')
                       ->select('fecha','mes','anio','versio','observacion','propietario','id')
                       ->orderBy('fecha','desc')
                       ->get();

        }

        if($opc == "36") //Consulta si el dc ya fue utilizado el días < a 9 días
        {
            $ot = $request->all()["ot"];
            $cd = explode(",",$request->all()["cd"]);

            $fecha_eje = explode(" ",DB::Table($this->tblAux .'ordenes')
                            ->where('id_orden',$ot)
                            ->value('fecha_ejecucion'))[0];


            $nuevafecha = strtotime ( '-8 days' , strtotime ( $fecha_eje ) ) ;
            $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

            $cdYaTRabajados = "";

            $proyecto = DB::Table($this->tblAux . 'ordenes' . $this->valorT)
                            ->where('id_orden',$ot)
                            ->value('id_proyecto');

            $tipo_pry = DB::Table($this->tblAux . 'proyectos' . $this->valorT)
                        ->where('id_proyecto',$proyecto)
                        ->value('tipo_proyecto');

            if($tipo_pry != "T03")
            {
                for ($i=0; $i < count($cd); $i++) 
                { 
                    $dato = str_replace(" ","",trim($cd[$i]));

                        $datoCD = DB::Table($this->tblAux .'ordenes')
                                ->where('cd','LIKE','%' . $dato . '%')
                                ->where('id_tipo','T56')
                                ->where('id_orden','<>',$ot)
                                ->where('id_estado','<>','A1')
                                ->whereBetween('fecha_ejecucion',[$nuevafecha . " 00:00:00",$fecha_eje . " 23:59:59"])
                                ->count();

                        if($datoCD > 0)
                            $cdYaTRabajados .= ' <br> - ' . $dato;
                    
                }
            }

            $arr = $cdYaTRabajados;
            
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
                                    'id_estado' => 0,
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

            $baremos = $request->all()["baremos"];

            $orden = $request->all()["orden"];
            $proyecto = $request->all()["proyecto"];


            //Recorrer baremos 

            for ($i=0; $i < count($baremos); $i++) { 
                $cant =  $baremos[$i]["cant"];
                $bare =  trim($baremos[$i]["bare"]);     


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

                        $anio=date("Y");
                        $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$orde."'; ";
                        $registro = DB::select($sql);

                        if(isset($registro[0])  && isset($registro[0]->anio) ){
                           $anio =  $registro[0]->anio;
                        }
                        
                        
                        $precioBaremo = DB::table($this->tblAux .'baremos')
                                ->where('codigo',$bare)
                                //->where('periodo',2017)
                                ->where('periodo',$anio)
                                ->value('precio');

                        $Baremo = DB::table($this->tblAux .'baremos')
                                ->where('codigo',$bare)
                                //->where('periodo',2017)
                                ->where('periodo',$bare)
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


                    //Anula restricciones
                    DB::table($this->tblAux .'restriccionesProyecto' . $this->valorT)
                        ->where('id_orden',$orden)
                        ->update(array(
                            'id_estado' => 'X'
                            ));

                    $restricciones = DB::table($this->tblAux .'restriccionesProyecto' . $this->valorT)
                                        ->where('id_orden',$orden)
                                        ->get(['id_restriccion']);

                    foreach ($restricciones as $key => $value) {
                        self::saveLog("OPERA64",$value->id_restriccion,"Se anula restricción, por anulación de la orden"); 
                    }

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

                            //Anula restricciones
                            DB::table($this->tblAux .'restriccionesProyecto' . $this->valorT)
                                ->where('id_orden',$orden)
                                ->update(array(
                                    'id_estado' => 'X'
                                    ));
                        

                            $restricciones = DB::table($this->tblAux .'restriccionesProyecto' . $this->valorT)
                                        ->where('id_orden',$orden)
                                        ->get(['id_restriccion']);

                            foreach ($restricciones as $key => $value) {
                                self::saveLog("OPERA64",$value->id_restriccion,"Se anula restricción, por anulación de la orden"); 
                            }


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

                $anio=date("Y");
                $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$ot."'; ";
                $registro = DB::select($sql);

                if(isset($registro[0])  && isset($registro[0]->anio) ){
                   $anio =  $registro[0]->anio;
                }
                
                
                $pre = DB::table($this->tblAux .'baremos' . $this->valorT)
                        ->where('codigo',$bare)
                        //->where('periodo',2017)
                        ->where('periodo',$anio)
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
                               // ->where('periodo',2017)
                                ->where('periodo',$anio)
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
                    
                }catch(\Exception $e)
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

            //Menos para proyectos de Civiles

            $tipoProyecto = DB::table($this->tblAux . 'proyectos')
                        ->where('id_proyecto',$proyecto)
                        ->value('tipo_proyecto');

            if($tipoProyecto == "T03")
            {
                $cantidadEjecutado = DB::table($this->tblAux . 'mobra')
                        ->where('id_orden',$orden)
                        ->where('id_origen',$liderA)
                        ->count();

                if($cantidadEjecutado > 0)
                    return response()->json("-2");
            }

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
             
             
                $anio=date("Y");
                $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$orde."'; ";
                $registro = DB::select($sql);

                 if(isset($registro[0])  && isset($registro[0]->anio) ){
                   $anio =  $registro[0]->anio;
                }
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
                            //->where('periodo',2017)
                            ->where('periodo',$anio)
                            ->value('precio');

                $Baremo = DB::table($this->tblAux .'baremos')
                        ->where('codigo',$bare)
                        //->where('periodo',2017)
                        ->where('periodo',$anio)
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
                    //->where('periodo',2017)
                    ->where('periodo',date("Y"))
                    
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

            $fechasEjecucionDias = array($fecha,$fecha);
            //$fecha = "2017-07-17";

            $tipoP = "T01";
            if(isset($request->all()['tipo_proyecto']))
                $tipoP = $request->all()['tipo_proyecto'];
            
            $personasCuadrilla = [];

            $fecha_ultima = strtotime ( '+4 days' , strtotime ( $fecha ) ) ;
            $fecha_ultima = date ( 'Y-m-j' , $fecha_ultima );

            $cantidad_dias_ordenes_a_mostrar = 30;
            $fecha_30_dias_despues = strtotime ( '+'.$cantidad_dias_ordenes_a_mostrar.' days' , strtotime ( $fecha ) ) ;
            $fecha_30_dias_despues = date ( 'Y-m-j' , $fecha_30_dias_despues );

            $ts_fecha_inicial = 0;
            $ts_fecha_final = 0;

            $fechas_ejecuciones_1 = array();
            $fechas_ejecuciones_2 = array();
            $sql_fechas_ejecucion_1 = '';
            $sql_fechas_ejecucion_2 = '';

            $sql_consultas_pruebas = array();

            if($tipoP == "T02")
            {   $fecha_actual = date('Y-m-d');

                $fecha_dia_antes = strtotime ( '-1 days' , strtotime ( $fecha ) ) ;
                $fecha_dia_antes = date ( 'Y-m-j' , $fecha_dia_antes );

                $fechasEjecucionDias = array($fecha_dia_antes,$fecha);
                $fecha_final_ejecucion_ordenes = $fecha_30_dias_despues;

                list($anio_fecha_inicial, $mes_fecha_inicial, $dia_fecha_inicial) = explode('-', $fecha_dia_antes);
                list($anio_fecha_final, $mes_fecha_final, $dia_fecha_final) = explode('-', $fecha_final_ejecucion_ordenes);

                $ts_fecha_inicial = mktime(0, 0, 0, $mes_fecha_inicial, $dia_fecha_inicial, $anio_fecha_inicial);
                $ts_fecha_final = mktime(0, 0, 0, $mes_fecha_final, $dia_fecha_final, $anio_fecha_final);

                for($i = $ts_fecha_inicial; $i <= $ts_fecha_final; $i+=86400) {
                  $fecha_siguiente_dummy = date('Y-m-j', $i);
                  $fechas_ejecuciones_1[] = " '{$fecha_siguiente_dummy}' BETWEEN orden.fecha_ejecucion AND orden.fecha_ejecucion_final ";
                  $fechas_ejecuciones_2[] = " '{$fecha_siguiente_dummy}' BETWEEN tbl1.fecha_ejecucion AND tbl1.fecha_ejecucion_final ";
                }

                $sql_fechas_ejecucion_1 = implode(' OR ', $fechas_ejecuciones_1);
                $sql_fechas_ejecucion_2 = implode(' OR ', $fechas_ejecuciones_2);

                $sqlConsulta = "
                    SELECT ([cua].[id_movil] + ' - ' + [rh].[nombres] + ' ' +  [rh].[apellidos] + ' - TURNO:' + orden.tipo_trabajo) as nombre, 
                      [cuadrilla].[id_lider],
                      rds_gop_supervisor.hri AS hora_inicio_labores,
                      rds_gop_supervisor.hre AS hora_fin_labores
                    FROM [" . $pre . "_gop_ordenes] as [orden] 
                    INNER join [" . $pre . "_gop_proyectos] as [pry] on [pry].[id_proyecto] = [orden].[id_proyecto] 
                    INNER join [" . $pre . "_gop_ordenes_manoobra_detalle] as [cuadrilla] on [cuadrilla].[id_orden] = [orden].[id_orden] 
                    INNER join [rh_personas] as [rh] on [rh].[identificacion] = [cuadrilla].[id_lider] 
                    INNER join [" . $pre . "_gop_cuadrilla] as [cua] on [cua].[id_lider] = [cuadrilla].[id_lider] 

                    LEFT JOIN rds_gop_supervisor 
                      ON(
                        rds_gop_supervisor.id_supervisor = orden.supervisor_ejecutor_cedula
                        AND rds_gop_supervisor.lider = cuadrilla.id_lider
                        AND rds_gop_supervisor.fecha_programacion LIKE '{$fecha_actual}%'
                      )

                    WHERE 
                    [pry].[tipo_proyecto] = 'T03'
                    and [orden].[id_estado] <> 'A1' 
                    and [cuadrilla].[id_estado] = 0 ".

                    " AND ( {$sql_fechas_ejecucion_1} ) "

                    //" and 
                    //(
                    //    '" . $fecha_dia_antes . "' between orden.fecha_ejecucion and orden.fecha_ejecucion_final
                    //    OR '" . $fecha . "' between orden.fecha_ejecucion and orden.fecha_ejecucion_final
                    //) "

                    //  and supervisor_ejecutor_cedula = '$lider'
                 ."   GROUP BY 
                       nombres,apellidos,
                       cuadrilla.id_lider,
                       [cua].[id_movil],
                       orden.tipo_trabajo,
                       rds_gop_supervisor.hri,
                       rds_gop_supervisor.hre
                    ORDER BY nombres
                ";

                $sql_consultas_pruebas[] = $sqlConsulta;


                $personasCuadrilla = DB::select("SET NOCOUNT ON;" . $sqlConsulta);

                $ordenes = [];

                //Consulta ordenes de la cuadrilla
                foreach ($personasCuadrilla as $key => $value) {
                    if(!$value->hora_inicio_labores) { $value->hora_inicio_labores = ''; }
                    if(!$value->hora_fin_labores)    { $value->hora_fin_labores    = ''; }

                    if($value->hora_inicio_labores) {
                      $value->hora_inicio_labores = substr($value->hora_inicio_labores, 0, 5);
                      if($value->hora_inicio_labores === '00:00') { $value->hora_inicio_labores = ''; }
                    }

                    if($value->hora_fin_labores) {
                      $value->hora_fin_labores = substr($value->hora_fin_labores, 0, 5);
                      if($value->hora_fin_labores === '00:00') { $value->hora_fin_labores = ''; }
                    }

                    $sqlConsulta = 
                    "   
                        select [tbl1].[id_orden], [tbl1].[gom], [tbl1].[hora_ini], [tbl1].[hora_corte], [tbl1].[hora_fin], 
                        [tbl1].[hora_cierre], [tbl1].[descargo], [tbl1].[descargo2], [tbl1].[supervisor], [tbl1].[supervisor_ejecutor], 
                        [tbl3].[nombre], [tbl1].[observaciones], [tbl4].[nombre_cto], '' as nodosU, [tbl1].[direccion], 
                        [tbl1].[nodos_utilizados], [tbl1].[fecha_ejecucion], [tbl1].[fecha_ejecucion_final], [tbl1].[id_estado] as [estadoOrden], 
                        [tbl2].[id_estado] as [estaO], [tbl2].[id_lider] 

                        from [" . $pre . "_gop_ordenes] as [tbl1] 
                        inner join [" . $pre . "_gop_ordenes_manoobra_detalle] as [tbl2] on [tbl2].[id_orden] = [tbl1].[id_orden] 
                        inner join [" . $pre . "_gop_proyectos] as [tbl3] on [tbl3].[id_proyecto] = [tbl2].[id_proyecto] 
                        inner join [" . $pre . "_gop_circuitos] as [tbl4] on [tbl4].[id_circuito] = [tbl3].[cod_cto] 

                        where ".

                        " ( {$sql_fechas_ejecucion_2} ) "
                        
                        //" (
                        //    '" . $fecha_dia_antes . "' BETWEEN [tbl1].[fecha_ejecucion] and [tbl1].[fecha_ejecucion_final]
                        //    or '" . $fecha . "' BETWEEN [tbl1].[fecha_ejecucion] and [tbl1].[fecha_ejecucion_final]
                        //) ".

                        ."
                        and [tbl2].[id_lider] = '" . $value->id_lider . "' 
                        and [tbl2].[id_estado] = 0
                        and [tbl1].[id_estado] NOT in ('A1','E1')

                        GROUP BY 
                        [tbl1].[id_orden], [tbl1].[gom], [tbl1].[hora_ini], [tbl1].[hora_corte], [tbl1].[hora_fin], 
                        [tbl1].[hora_cierre], [tbl1].[descargo], [tbl1].[descargo2], [tbl1].[supervisor], [tbl1].[supervisor_ejecutor], 
                        [tbl3].[nombre], [tbl1].[observaciones], [tbl4].[nombre_cto], [tbl1].[direccion], 
                        [tbl1].[nodos_utilizados], [tbl1].[fecha_ejecucion], [tbl1].[fecha_ejecucion_final], [tbl1].[id_estado], 
                        [tbl2].[id_estado], [tbl2].[id_lider] 


                    ";
                    $sql_consultas_pruebas[] = $sqlConsulta;
                    array_push($ordenes, DB::select("SET NOCOUNT ON;" . $sqlConsulta));   
                }

                for ($i=0; $i < count($ordenes); $i++) { 
                    $nodosUtil = "";
                    $ordenAux = $ordenes[$i];                    
                    foreach ($ordenAux as $key => $value) {                            
                            $value->fecha_ejecucion_inicial = explode(" ",$value->fecha_ejecucion)[0];
                            $value->fecha_ejecucion_final   = explode(" ",$value->fecha_ejecucion_final)[0];

                            $value->fecha_orden = explode(" ",$value->fecha_ejecucion)[0];  
                            //$value->fecha_orden = $fecha;  
                                
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
                                    and [periodo] = 2018 group by [tbl1].[id_baremo], 
                                    [tbl1].[id_nodo], [tbl1].[cantidad_confirmada]
                                    ";

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
                    ->where('tbl1.id_estado','<>','E1')
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
                                    {

                                        
                                        $nodoNoExiste = [];
                                        for ($i=0; $i < count($arregloAc); $i++) { 
                                            if($arregloAc[$i] != $nodoNuevo->id_nodo)
                                                array_push($nodoNoExiste,$arregloAc[$i]);
                                        }


                                        for ($i=0; $i < count($nodoNoExiste); $i++) {
                                            $ex = 0;    
                                            for ($j=0; $j < count($nodosUtil); $j++) 
                                            {
                                                if($nodosUtil[$j]->id_nodo == $nodoNoExiste[$i]->id_nodo)
                                                    $ex = 1;
                                            }

                                            if($ex == 0)
                                            {
                                                array_push($nodosUtil,$nodoNoExiste[$i]);
                                            }   
                                        }
                                    }
                                }

                                if(count($nodosUtil) == 0)
                                {
                                    $nodosUtil = DB::table($pre . '_gop_ordenes_materiales_documentos as tbl1')
                                            ->join($pre . '_gop_nodos as tbl2','tbl1.id_nodo','=','tbl2.id_nodo')
                                            ->where('tbl1.id_orden',$value->id_orden)
                                            ->where('tbl1.id_lider',$lider)
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
                                            ->where('tbl1.id_lider',$lider)
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

            

            $array =[];
            $fecha_menor = strtotime ( '-2 days' , strtotime ( $fecha ) ) ;
            $fecha_menor = date ( 'Y-m-j' , $fecha_menor );


            $fecha_sig_1 = strtotime ( '+1 days' , strtotime ( $fecha ) ) ;
            $fecha_sig_1 = date ( 'Y-m-j' , $fecha_sig_1 );

            $fecha_sig_2 = strtotime ( '+2 days' , strtotime ( $fecha ) ) ;
            $fecha_sig_2 = date ( 'Y-m-j' , $fecha_sig_2 );

            $fecha_sig_3 = strtotime ( '+3 days' , strtotime ( $fecha ) ) ;
            $fecha_sig_3 = date ( 'Y-m-j' , $fecha_sig_3 );

            if($tipoP == "T01")
            {
                $cudri = DB::table($pre . '_gop_cuadrilla as tbl1')
                    ->where('id_lider',$lider)
                    ->leftJoin('rh_personas as tbl2','tbl1.id_lider','=','tbl2.identificacion')
                    ->leftJoin('rh_personas as tbl3','tbl1.id_aux1','=','tbl3.identificacion')
                    ->leftJoin('rh_personas as tbl4','tbl1.id_aux2','=','tbl4.identificacion')
                    ->leftJoin('rh_personas as tbl5','tbl1.id_aux3','=','tbl5.identificacion')
                    ->leftJoin('rh_personas as tbl6','tbl1.id_conductor','=','tbl6.identificacion')
                    ->get(['tbl1.id_lider',DB::raw("(tbl2.nombres + ' ' + tbl2.apellidos ) as nombre_lider"),
                        'tbl1.id_aux1',DB::raw("(tbl3.nombres + ' ' + tbl3.apellidos ) as aux_1"),
                        'tbl1.id_aux2',DB::raw("(tbl4.nombres + ' ' + tbl4.apellidos ) as aux_2"),
                        'tbl1.id_aux3',DB::raw("(tbl5.nombres + ' ' + tbl5.apellidos ) as aux_3"),
                        'tbl1.id_conductor',DB::raw("(tbl6.nombres + ' ' + tbl6.apellidos ) as conductor")])[0];    
            }
            else
                $cudri = [];
            

            array_push($array, ["ordenes" => $ordenes,
                                "fecha_actual" => $fecha,
                                "fecha_atras" => $fecha_menor,
                                "fecha_siguiente_1" => $fecha_sig_1,
                                "fecha_siguiente_2" => $fecha_sig_2,
                                "fecha_siguiente_3" => $fecha_sig_3,
                                "fecha_siguiente_4" => $fecha_ultima,
                                'dias_despachos' => 20,
                                "cuadrilla" => $cudri,
                                "personal_lider" => $personasCuadrilla,
                                "fechas_ejecucion" => $fechasEjecucionDias,
                                "dias_ordenes" => $cantidad_dias_ordenes_a_mostrar,

                                // "fecha_dia_antes" => $fecha_dia_antes,
                                //"fecha_30_dias_despues" => $fecha_30_dias_despues
                                ]);

            return response()->json($array[0]);
        }


        if($opc == "4") //Save Actividades
        {
            $baremos = $request->all()["baremos"];
            $pre = $request->all()["pre"];
            $lider = $request->all()["lider"];
            
            if(isset($request->all()["user"]))
                $lider = $request->all()["user"];

            $tipoP = "T01";
            if(isset($request->all()['id_proyecto']))
                $tipoP = $request->all()['id_proyecto'];

            $orden = 0;
            //echo count($baremos);
            for ($i=0; $i < count($baremos); $i++) { 
                
                $orden = $baremos[$i]["orden"];

                $anio=date("Y");
                $sql=" select year(fecha_ejecucion) as anio from ".$this->tblAux ."ordenes where id_orden='".$orden."'; ";
                $registro = DB::select($sql);
                if(isset($registro[0])  && isset($registro[0]->anio) ){
                    $anio =  $registro[0]->anio;
                }
                
                
                DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                        ->where('id_orden',$orden)
                        ->where('id_lider',$lider)
                        ->update(
                                array(
                                        'fecha_actualiza_ejecucion' => $this->fechaALong
                                    )
                            );

                $proy = DB::table($pre . "_gop_ordenes")
                        ->where('id_orden',$baremos[$i]["orden"])
                        ->value('id_proyecto');

                //echo $proy;
                if($proy != "" && $proy =! null)
                {
                    $baremData = DB::table($pre . "_gop_baremos")
                                ->where('codigo',$baremos[$i]["bare"])
                               // ->where('periodo',2017)
                                ->where('periodo',$anio)
                               
                                ->value('id_baremo');

                    if($tipoP == "T02")
                    {
                        $fecha_ejecucion = $this->fechaShort;
                            if(isset($baremos[$i]["fecha_ejecucion"]))
                                $fecha_ejecucion = $baremos[$i]["fecha_ejecucion"];

                        $cont = DB::table($pre . "_gop_ordenes_mobra")
                            ->where('id_orden',$baremos[$i]["orden"])
                            ->where('id_nodo',$baremos[$i]["nodo"])
                            ->where('id_baremo',$baremData)
                            ->where('id_origen',$lider)
                            ->whereBetween('fecha',[$fecha_ejecucion . " 00:00:00",$fecha_ejecucion . " 23:59:59"])
                            ->count();    
                    }
                    else
                    {
                        $cont = DB::table($pre . "_gop_ordenes_mobra")
                            ->where('id_orden',$baremos[$i]["orden"])
                            ->where('id_nodo',$baremos[$i]["nodo"])
                            ->where('id_baremo',$baremData)
                            ->where('id_origen',$lider)
                            ->count();    
                    }
                    
                        
                    $baremDataPrecio = DB::table($pre . "_gop_baremos")
                                ->where('codigo',$baremos[$i]["bare"])
                                //->where('periodo',2018)
                                ->where('periodo',$anio)
                                ->value('precio');


                    if($cont == 0)//INSERT
                    {
                        $ws = DB::table($pre . "_gop_ws_nodos")
                        ->where('id_nodo1',$baremos[$i]["nodo"])
                        ->value('id_ws');

                        $fechaEJe = DB::table($pre . "_gop_ordenes")
                                    ->where('id_orden',$baremos[$i]["orden"])
                                    ->value('fecha_ejecucion');

                        if($tipoP == "T02")
                        {
                            $fecha_ejecucion = $this->fechaALong;
                            if(isset($baremos[$i]["fecha_ejecucion"]))
                                $fecha_ejecucion = $baremos[$i]["fecha_ejecucion"];

                            DB::table($pre . "_gop_ordenes_mobra")
                            ->insert(array(
                                array(
                                    'id_ws' => $ws,
                                    'id_proyecto' => $proy,
                                    'id_orden' => $baremos[$i]["orden"],
                                    'id_nodo' => $baremos[$i]["nodo"],
                                    'id_baremo' => $baremData,
                                    'cantidad_confirmada' => $baremos[$i]["cant"],
                                    'id_origen' => $lider,
                                    'fecha' => $fecha_ejecucion,
                                    'fecha_usuario_terreno_create' => $this->fechaALong
                                    )
                                ));


                            DB::table($pre . "_gop_mobra")
                                ->insert(array(
                                    array(
                                        'id_orden' => $baremos[$i]["orden"],
                                        'id_origen' => $lider,
                                        'id_baremo' => $baremData,
                                        'cantidad' => $baremos[$i]["cant"],
                                        'precio' => $baremDataPrecio,
                                        'fecha' => $fecha_ejecucion,
                                        'id_nodo' => $baremos[$i]["nodo"]
                                        )
                                    ));
                        }
                        else
                        {
                            DB::table($pre . "_gop_ordenes_mobra")
                            ->insert(array(
                                array(
                                    'id_ws' => $ws,
                                    'id_proyecto' => $proy,
                                    'id_orden' => $baremos[$i]["orden"],
                                    'id_nodo' => $baremos[$i]["nodo"],
                                    'id_baremo' => $baremData,
                                    'cantidad_confirmada' => $baremos[$i]["cant"],
                                    'id_origen' => $lider,
                                    'fecha' => $fechaEJe,
                                    'fecha_usuario_terreno_create' => $this->fechaALong
                                    )
                                ));


                            DB::table($pre . "_gop_mobra")
                                ->insert(array(
                                    array(
                                        'id_orden' => $baremos[$i]["orden"],
                                        'id_origen' => $lider,
                                        'id_baremo' => $baremData,
                                        'cantidad' => $baremos[$i]["cant"],
                                        'precio' => $baremDataPrecio,
                                        'fecha' => $fechaEJe,
                                        'id_nodo' => $baremos[$i]["nodo"]
                                        )
                                    ));
                        }
                        

                    }
                    else // UPDATE
                    {
                        if($tipoP == "T02")
                        {
                            $fecha_ejecucion = $this->fechaShort;
                            if(isset($baremos[$i]["fecha_ejecucion"]))
                                $fecha_ejecucion = $baremos[$i]["fecha_ejecucion"];

                            DB::table($pre . "_gop_mobra")
                                ->where('id_orden',$baremos[$i]["orden"])
                                ->where('id_baremo',$baremData)
                                ->where('id_origen',$lider)
                                ->where('fecha',$fecha_ejecucion)
                                ->where('id_nodo',$baremos[$i]["nodo"])
                                ->update(
                                    array(
                                        'cantidad' => $baremos[$i]["cant"]
                                        )
                                    );

                                DB::table($pre . "_gop_ordenes_mobra")
                                ->where('id_orden',$baremos[$i]["orden"])
                                ->where('id_nodo',$baremos[$i]["nodo"])
                                ->where('id_baremo',$baremData)
                                ->where('fecha',$fecha_ejecucion)
                                ->where('id_origen',$lider)
                                ->update(
                                    array(
                                        'cantidad_confirmada' => $baremos[$i]["cant"],
                                        'fecha_usuario_terreno_update' => $this->fechaALong
                                        )
                                    );
                        }
                        else
                        {
                            DB::table($pre . "_gop_mobra")
                                ->where('id_orden',$baremos[$i]["orden"])
                                ->where('id_baremo',$baremData)
                                ->where('id_origen',$lider)
                                ->where('id_nodo',$baremos[$i]["nodo"])
                                ->update(
                                    array(
                                        'cantidad' => $baremos[$i]["cant"]
                                        )
                                    );

                                DB::table($pre . "_gop_ordenes_mobra")
                                ->where('id_orden',$baremos[$i]["orden"])
                                ->where('id_nodo',$baremos[$i]["nodo"])
                                ->where('id_baremo',$baremData)
                                ->where('id_origen',$lider)
                                ->update(
                                    array(
                                        'cantidad_confirmada' => $baremos[$i]["cant"],
                                        'fecha_usuario_terreno_update' => $this->fechaALong
                                        )
                                    );
                        }
                    }
                }
            }       


            $programadoOrden = DB::table($pre . '_gop_ordenes_manoobra')
                                ->where('id_orden',$orden)
                                ->select('id_nodo','id_baremo')
                                ->get();

            $ejecutadoOrden = DB::table($pre . '_gop_ordenes_mobra')
                                ->where('id_orden',$orden)
                                ->select('id_nodo','id_baremo')
                                ->get();

            $materialProgra = DB::table($pre . '_gop_ordenes_materiales_documentos')
                                ->where('id_orden',$orden)
                                ->select('id_documento','id_documento_cs')
                                ->get();

            $exito = 0;
            foreach ($programadoOrden as $key => $value) {
                $fin = 0;
                foreach ($ejecutadoOrden as $k => $v) {
                    if($v->id_nodo == $value->id_nodo && $v->id_baremo == $value->id_baremo)
                        $fin = 1;
                }
                if($fin == 1)
                  $exito++;  
            }


            if($exito >= count($programadoOrden)) //Nodos Ejecutado Baremos
            {
                if(count($materialProgra) > 0)
                {
                    $noUPdate = 0;
                    foreach ($materialProgra as $key => $value) {
                        if($value->id_documento_cs == null || $value->id_documento_cs == "")
                        {
                            $noUPdate = 1;
                            break;
                        }
                    }

                    if($noUPdate == 0) //Ya existen documentos CS
                    {
                        $exitoMaterial = 0; 
                        $cantMaterial = 0; 
                        foreach ($materialProgra as $key => $value) {
                            $matProOrden = DB::table($pre . '_inv_detalle_documentos')
                                ->where('id_documento',$value->id_documento)
                                ->select('id_articulo')
                                ->get();

                            $matEjeOrden = DB::table($pre . '_inv_detalle_documentos')
                                                ->where('id_documento',$value->id_documento_cs)
                                                ->select('id_articulo')
                                                ->get();

                            $cantMaterial += count($matProOrden);
                            foreach ($matProOrden as $k1 => $v1) {
                                $fin = 0;
                                foreach ($matEjeOrden as $k2 => $v2) {
                                    if($v1->id_articulo == $v2->id_articulo)
                                        $fin = 1;
                                }
                                if($fin == 1)
                                  $exitoMaterial++;
                            }
                        }

                        if($exitoMaterial >= $cantMaterial)
                        {
                            DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                                ->where('id_orden',$orden)
                                ->where('id_lider',$lider)
                                ->update(
                                        array(
                                                'finaliza_terreno' => 1
                                            )
                                    );
                        }
                    }
                }
                else
                {
                    DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                                ->where('id_orden',$orden)
                                ->where('id_lider',$lider)
                                ->update(
                                        array(
                                                'finaliza_terreno' => 1
                                            )
                                    );

                }
            }
            return response()->json(1);
        }

        if($opc == "5") //Save Materiales
        {            
            $pre = $request->all()["pre"];
            $lider = $request->all()["lider"];

            if(isset($request->all()["user"]))
                $lider = $request->all()["user"];

            $tipoP = "T01";
            if(isset($request->all()['id_proyecto']))
                $tipoP = $request->all()['id_proyecto'];

            $obser = "";

            $orden = $request->all()["orden"];


            $finTerreno = 0;
            if(isset($request->all()["fin"]))            
                $finTerreno = $request->all()["fin"];

            if(isset($request->all()["observacion"]))
            {
                DB::table($pre . "_gop_ordenes")
                        ->where('id_orden',$request->all()["orden"])
                        ->update(
                            array(
                                'observaciones_orden' => $request->all()["observacion"]
                                )
                            );
            }
            /*
            if(isset($request->all()["traslado"]))
            {
                $traslado = $request->all()["traslado"];
                for ($i=0; $i < count($traslado); $i++) { 

                    if(intval($traslado[$i]["cantidad"]) == 0)
                        continue;

                    $proy = DB::table($pre . "_gop_ordenes")
                            ->where('id_orden',$traslado[$i]["id_orden"])
                            ->value('id_proyecto');

                    $arti = $traslado[$i]["id_material"];

                    //Consulta cantidad entregada
                    $contD = intval(DB::table($pre . "_gop_ordenes_materiales")
                                    ->where('id_orden',$traslado[$i]["id_orden"])
                                    ->where('id_nodo',$traslado[$i]["nodo_origen"])
                                    ->where('id_articulo',$arti)
                                    ->where('id_persoanCargo',$traslado[$i]["usuario"])
                                    ->value('cantidad_confirmada'));

                    //Consulta DC asociado al líder
                    $dc = DB::table($pre . "_gop_ordenes_materiales_documentos")
                                    ->where('id_orden',$traslado[$i]["id_orden"])
                                    ->where('id_nodo',$traslado[$i]["nodo_origen"])
                                    ->where('id_lider',$traslado[$i]["usuario"])
                                    ->value('id_documento');

                    //Consulta cantidad Entregada
                    $cantEntregrado = DB::table($pre . "_inv_detalle_documentos")
                                    ->where('id_documento',$traslado[$i]["id_orden"])
                                    ->where('id_articulo',$arti)
                                    ->value('cantidad');

                    //Consulta cantidad reintegro
                    $cantEntregradoReintegro = DB::table($pre . "_inv_detalle_documentos")
                                        ->where('id_documento',$traslado[$i]["id_orden"])
                                        ->where('id_articulo',$arti)
                                        ->value('reintegro');

                    //Realiza resta de entregado - reintegro
                    if($cantEntregradoReintegro != null && $cantEntregradoReintegro |= "")
                        $cantEntregrado = intval($cantEntregrado) - intval($cantEntregradoReintegro);

                    try
                    {
                        DB::beginTransaction();
                        if($cantEntregrado == intval($traslado[$i]["cantidad"])) //Cambie toda la cantidad de materiales
                        {
                            //Elimina la asosción del materiales
                            DB::table($pre . "_gop_ordenes_materiales")
                                        ->where('id_orden',$traslado[$i]["id_orden"])
                                        ->where('id_nodo',$traslado[$i]["nodo_origen"])
                                        ->where('id_articulo',$arti)
                                        ->where('id_persoanCargo',$traslado[$i]["usuario"])
                                        ->delete();

                            //Elimina la asoción de materiales del nodo destino si ya existe
                            DB::table($pre . "_gop_ordenes_materiales")
                                        ->where('id_orden',$traslado[$i]["id_orden"])
                                        ->where('id_nodo',$traslado[$i]["nodo_destino"])
                                        ->where('id_articulo',$arti)
                                        ->where('id_persoanCargo',$traslado[$i]["usuario"])
                                        ->delete();

                            //Agrega el nuevo material
                            DB::table($pre . "_gop_ordenes_materiales")
                                ->insert(array(
                                    array(
                                        'id_proyecto' => $proy,
                                        'id_orden' => $traslado[$i]["id_orden"],
                                        'id_nodo' => $traslado[$i]["nodo_destino"],
                                        'id_articulo' => $arti,
                                        'cantidad_confirmada' => $traslado[$i]["cantidad"],
                                        'id_persoanCargo' => $traslado[$i]["usuario"]
                                        )
                                    ));

                            DB::table($pre . "_inv_detalle_documentos")
                                ->where('id_nodo',$traslado[$i]["nodo_origen"])
                                ->where('id_documento',$dc)
                                ->where('id_articulo',$arti)
                                ->update(array(
                                        'id_nodo' => $traslado[$i]["nodo_destino"]
                                    ));

                            DB::table($pre . "_gop_log_traslados_material")
                                ->insert(array(
                                    array(
                                        'id_orden' => $traslado[$i]["id_orden"],
                                        'id_lider' => $traslado[$i]["usuario"],
                                        'nodo_origen' => $traslado[$i]["nodo_origen"],
                                        'nodo_destino' => $traslado[$i]["nodo_destino"],
                                        'id_articulo' => $arti,
                                        'cant' => $traslado[$i]["cantidad"]
                                        )
                                    ));

                            //Consulta si ya exuste asociación de DC - Lider - OT
                            $dcC = DB::table($pre . "_gop_ordenes_materiales_documentos")
                                        ->where('id_orden',$traslado[$i]["id_orden"])
                                        ->where('id_nodo',$traslado[$i]["nodo_destino"])
                                        ->where('id_lider',$traslado[$i]["usuario"])
                                        ->count();

                            if($dcC == 0) //No tengo relación DC - líder
                            {
                                DB::table($pre . "_gop_ordenes_materiales_documentos")
                                ->insert(array(
                                    array(
                                        'id_orden' => $traslado[$i]["id_orden"],
                                        'id_nodo' => $traslado[$i]["nodo_destino"],
                                        'id_tipo_documento' => "T005",
                                        'id_documento' => $dc,
                                        'id_lider' => $traslado[$i]["usuario"]
                                        )
                                    ));
                            }
                        }
                        else
                        {

                            $contD = intval($cantEntregrado) - intval($traslado[$i]["cantidad"]);
                            //ACtualiza la nueva cantidad programada por el líder
                            DB::table($pre . "_gop_ordenes_materiales")
                                        ->where('id_orden',$traslado[$i]["id_orden"])
                                        ->where('id_nodo',$traslado[$i]["nodo_origen"])
                                        ->where('id_articulo',$arti)
                                        ->where('id_persoanCargo',$traslado[$i]["usuario"])
                                        ->update(array(
                                            'cantidad_confirmada' => $contD
                                            ));

                            //Elimina la asoción de materiales del nodo destino si ya existe
                            DB::table($pre . "_gop_ordenes_materiales")
                                        ->where('id_orden',$traslado[$i]["id_orden"])
                                        ->where('id_nodo',$traslado[$i]["nodo_destino"])
                                        ->where('id_articulo',$arti)
                                        ->where('id_persoanCargo',$traslado[$i]["usuario"])
                                        ->delete();

                            //Inserta relación nodos - articulo - orden
                            DB::table($pre . "_gop_ordenes_materiales")
                                ->insert(array(
                                    array(
                                        'id_proyecto' => $proy,
                                        'id_orden' => $traslado[$i]["id_orden"],
                                        'id_nodo' => $traslado[$i]["nodo_destino"],
                                        'id_articulo' => $arti,
                                        'cantidad_confirmada' => $traslado[$i]["cantidad"],
                                        'id_persoanCargo' => $traslado[$i]["usuario"]
                                        )
                                    ));

                            DB::table($pre . "_inv_detalle_documentos")
                                ->where('id_nodo',$traslado[$i]["nodo_origen"])
                                ->where('id_documento',$dc)
                                ->where('id_articulo',$arti)
                                ->update(array(
                                        'id_nodo' => $traslado[$i]["nodo_destino"]
                                    ));

                            //Consulta si ya exuste asociación de DC - Lider - OT
                            $dcC = DB::table($pre . "_gop_ordenes_materiales_documentos")
                                        ->where('id_orden',$traslado[$i]["id_orden"])
                                        ->where('id_nodo',$traslado[$i]["nodo_destino"])
                                        ->where('id_lider',$traslado[$i]["usuario"])
                                        ->count();

                            DB::table($pre . "_gop_log_traslados_material")
                                ->insert(array(
                                    array(
                                        'id_orden' => $traslado[$i]["id_orden"],
                                        'id_lider' => $traslado[$i]["usuario"],
                                        'nodo_origen' => $traslado[$i]["nodo_origen"],
                                        'nodo_destino' => $traslado[$i]["nodo_destino"],
                                        'id_articulo' => $arti,
                                        'cant' => $traslado[$i]["cantidad"]
                                        )
                                    ));


                            if($dcC == 0) //No tengo relación DC - líder
                            {
                                DB::table($pre . "_gop_ordenes_materiales_documentos")
                                ->insert(array(
                                    array(
                                        'id_orden' => $traslado[$i]["id_orden"],
                                        'id_nodo' => $traslado[$i]["nodo_destino"],
                                        'id_tipo_documento' => "T005",
                                        'id_documento' => $dc,
                                        'id_lider' => $traslado[$i]["usuario"]
                                        )
                                    ));
                            }
                        }
                        DB::commit();
                    }
                    catch(Exception $e)
                    {
                        DB::rollBack();
                        return response()->json("0");
                    }

                    // id_orden
                    // id_material // id_articulo
                    // cantidad
                    // nodo_origen
                    // nodo_destino
                    // usuario
                }
            }*/

            $ingreso = 0;
            try
            {
                DB::beginTransaction();

                DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                    ->where('id_orden',$orden)
                    ->where('id_lider',$lider)
                    ->update(
                            array(
                                    'finaliza_terreno' => $finTerreno
                                )
                        );

                DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                        ->where('id_orden',$orden)
                        ->where('id_lider',$lider)
                        ->update(
                                array(
                                        'fecha_actualiza_ejecucion' => $this->fechaALong
                                    )
                            );
                        

                if(!isset($request->all()["baremos"]))
                    return response()->json("1");
                
                $materiales = $request->all()["baremos"];
                for ($i=0; $i < count($materiales); $i++) 
                { 
                    $dc = DB::table($pre . "_gop_ordenes_materiales_documentos")
                        ->where('id_orden',$materiales[$i]["orden"])
                        ->where('id_nodo',$materiales[$i]["nodo"])
                        ->where('id_lider',$lider)
                        ->select('id_documento','id_documento_cs')
                        ->get()[0];


                    $proy = DB::table($pre . "_gop_ordenes")
                            ->where('id_orden',$materiales[$i]["orden"])
                            ->value('id_proyecto');

                        
                        //Si no existe CS -  SOlo ingresa una sola vez
                        if($dc->id_documento_cs == null && $dc->id_documento_cs == NULL) 
                        {
                                $codCosumo = self::dame_uncodigo_almacen("T007");
                                $gom = DB::table($pre . "_gop_ordenes")
                                    ->where('id_orden',$materiales[$i]["orden"])
                                    ->value('gom');

                                 //Crear documento DC
                                DB::table($pre  . '_inv_documentos')
                                ->insert(array(
                                    array(
                                        'id_documento' => $codCosumo,
                                        'id_tipo_movimiento' => "T007",
                                        'fecha' => $this->fechaShort,
                                        'fecha_sistema' => $this->fechaALong,
                                        'id_bodega_origen' => $lider,
                                        'observaciones' => $proy  . "_" . $materiales[$i]["orden"]  . "_" . $materiales[$i]["nodo"] . "_" . $lider,
                                        'id_estado' => 'E3',
                                        'id_orden' => $materiales[$i]["orden"],
                                        'gom' => $gom,
                                        "id_nodo" => $materiales[$i]["nodo"]
                                        )
                                    ));
                                //self::saveLog("OPERA52",$codCosumo," ORDEN: " . $materiales[$i]["orden"]);

                                //Asocia DC a CS
                                DB::table($pre . "_gop_ordenes_materiales_documentos")
                                            ->where('id_orden',$materiales[$i]["orden"])
                                            ->where('id_nodo',$materiales[$i]["nodo"])
                                            ->where('id_lider',$lider)
                                            ->where('id_documento',$dc->id_documento)
                                            ->update(array(
                                                'id_documento_cs' => $codCosumo,
                                                'fecha_terreno' => $this->fechaALong
                                                ));

                                if($materiales[$i]["articulo"] != "-1")
                                {
                                    $mateA = DB::table($pre . "_inv_maestro_articulos")
                                        ->where('codigo_sap',$materiales[$i]["articulo"])
                                        ->value('id_articulo');    
                                }else
                                    $mateA = $materiales[$i]["arti"];
                                

                                if(isset($materiales[$i]["fin"]))
                                {
                                    DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                                        ->where('id_orden',$orden)
                                        ->where('id_lider',$lider)
                                        ->update(
                                                array(
                                                        'finaliza_terreno' => $materiales[$i]["fin"]
                                                    )
                                            ); 
                                }
                                


                                //Inserto primer registro
                                DB::table($pre  . '_inv_detalle_documentos')
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codCosumo,
                                            'id_articulo' => $mateA, 
                                            'consumo' => $materiales[$i]["cant"],
                                            'i_rz' => $materiales[$i]["dato1"],
                                            'r_ch' => $materiales[$i]["dato2"],
                                            'r_rz' => $materiales[$i]["dato3"],
                                            'id_nodo' => $materiales[$i]["nodo"],
                                            'fecha_ejecucion' => $this->fechaALong
                                            )
                                        ));
                                //self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materiales[$i]["articulo"] . " CANTIDAD: " .  $materiales[$i]["cant"]);
                        }
                        else //Segunda vez que entra
                        {
                            
                            $codCosumo = $dc->id_documento_cs;
                            

                            if($materiales[$i]["articulo"] != "-1")
                            {
                                $mateA = DB::table($pre . "_inv_maestro_articulos")
                                    ->where('codigo_sap',$materiales[$i]["articulo"])
                                    ->value('id_articulo');    
                            }else
                                $mateA = $materiales[$i]["arti"];                           
                            

                            $contMat =  DB::table($pre  . '_inv_detalle_documentos')
                                    ->where('id_documento',$codCosumo)
                                    ->where('id_articulo',$mateA)
                                    ->count();

                            if(isset($materiales[$i]["fin"]))
                            {
                                DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                                    ->where('id_orden',$orden)
                                    ->where('id_lider',$lider)
                                    ->update(
                                            array(
                                                    'finaliza_terreno' => $materiales[$i]["fin"]
                                                )
                                        ); 
                            }
                            

                            if($contMat == 0) //No existe el material
                            {
                                //Inserto siguiente  registros
                                DB::table($pre  . '_inv_detalle_documentos')
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codCosumo,
                                            'id_articulo' => $mateA, 
                                            'consumo' => $materiales[$i]["cant"],
                                            'i_rz' => $materiales[$i]["dato1"],
                                            'r_ch' => $materiales[$i]["dato2"],
                                            'r_rz' => $materiales[$i]["dato3"],
                                            'id_nodo' => $materiales[$i]["nodo"],
                                            'fecha_ejecucion' => $this->fechaALong
                                            )
                                        ));

                                //self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materiales[$i]["articulo"] . " CANTIDAD: " .  $materiales[$i]["cant"]);    
                            }
                            else // Ya existe material - UPDATE
                            {
                                //Update siguiente  registros
                                DB::table($pre  . '_inv_detalle_documentos')
                                    ->where('id_documento',$codCosumo)
                                    ->where('id_articulo',$mateA)
                                    ->where('id_nodo',$materiales[$i]["nodo"])
                                    ->update(
                                        array(
                                            'consumo' => $materiales[$i]["cant"],
                                            'i_rz' => $materiales[$i]["dato1"],
                                            'r_ch' => $materiales[$i]["dato2"],
                                            'r_rz' => $materiales[$i]["dato3"]
                                            )
                                        );
                                //self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materiales[$i]["articulo"] . " CANTIDAD: " .  $materiales[$i]["cant"]);       
                            }
                        }
                }

                //Consulta si todo esta lleno
                $programadoOrden = DB::table($pre . '_gop_ordenes_manoobra')
                                ->where('id_orden',$orden)
                                ->select('id_nodo','id_baremo')
                                ->get();

                $ejecutadoOrden = DB::table($pre . '_gop_ordenes_mobra')
                                    ->where('id_orden',$orden)
                                    ->select('id_nodo','id_baremo')
                                    ->get();

                $materialProgra = DB::table($pre . '_gop_ordenes_materiales_documentos')
                                    ->where('id_orden',$orden)
                                    ->select('id_documento','id_documento_cs')
                                    ->get();

                $exito = 0;
                foreach ($programadoOrden as $key => $value) {
                    $fin = 0;
                    foreach ($ejecutadoOrden as $k => $v) {
                        if($v->id_nodo == $value->id_nodo && $v->id_baremo == $value->id_baremo)
                            $fin = 1;
                    }

                    if($fin == 1)
                      $exito++;  
                }


                if($exito >= count($programadoOrden)) //Nodos Ejecutado Baremos
                {
                    if(count($materialProgra) > 0)
                    {
                        $noUPdate = 0;
                        foreach ($materialProgra as $key => $value) {
                            if($value->id_documento_cs == null || $value->id_documento_cs == "")
                            {
                                $noUPdate = 1;
                                break;
                            }
                        }

                        if($noUPdate == 0) //Ya existen documentos CS
                        {
                            $exitoMaterial = 0; 
                            $cantMaterial = 0; 
                            foreach ($materialProgra as $key => $value) {
                                $matProOrden = DB::table($pre . '_inv_detalle_documentos')
                                    ->where('id_documento',$value->id_documento)
                                    ->select('id_articulo')
                                    ->get();

                                $matEjeOrden = DB::table($pre . '_inv_detalle_documentos')
                                                    ->where('id_documento',$value->id_documento_cs)
                                                    ->select('id_articulo')
                                                    ->get();

                                $cantMaterial += count($matProOrden);
                                foreach ($matProOrden as $k1 => $v1) {
                                    $fin = 0;
                                    foreach ($matEjeOrden as $k2 => $v2) {
                                        if($v1->id_articulo == $v2->id_articulo)
                                            $fin = 1;
                                    }
                                    if($fin == 1)
                                      $exitoMaterial++;
                                }
                            }
                        }
                    }
                }
                //Validar llenado TOTAL
                DB::commit();
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
            return response()->json("1");
        }

        if($opc == "7") //Pocisión GPS
        {
            $user = $request->all()["user"];
            $coor = $request->all()["coor"]; 
            $pre = $request->all()["pre"]; 
            DB::table($pre. '_gps_tracker')
                ->insert(array(
                    array(
                        'prefijo' => $pre,
                        'latitud' => explode(",",$coor)[0],
                        'longitud' => explode(",",$coor)[1],
                        'fecha' => explode(" ",$this->fechaALong)[0],
                        'hora' => explode(" ",$this->fechaALong)[1],
                        'usuario_movil' => $user
                        )
                    ));   
            return response()->json(1);
        }

        if($opc == "8") //Save Formularios Autoinpección e IPO
        {
            $pre = $request->all()["pre"];
            $user = $request->all()["user"];
            $personas = $request->all()["personas"];
            $formulario = $request->all()["formulario"];
            $tipo = $request->all()["tipo_frm"];
            $posicion = $request->all()["coor"];
            $version_frm = $request->all()["version"];
            $conforme = "C";

            if(isset($request->all()["conforme"]))
                $conforme =$request->all()["conforme"];
            

            $concecutivo = explode(" ",$this->fechaALong)[0];
            $concecutivo1 = explode(" ",$this->fechaALong)[1];
        
            $concecutivo = explode("-",$concecutivo)[0] . "" . explode("-",$concecutivo)[1] . "" . explode("-",$concecutivo)[2] . "" . explode(":",$concecutivo1)[0] . "" . explode(":",$concecutivo1)[1] . "" . explode(":",$concecutivo1)[2] . "-" . substr(md5(uniqid(rand())), 0, 5);

            $observacion = "SIN OBSERVACIONES";
            if(isset($request->all()["observacion"]))
                $observacion = $request->all()["observacion"];

            $maxEvent = "-1";
            //DB::beginTransaction();

            
          /*  $id_formato = DB::table('ssl_gop_formatos')
                            ->where('id_origen',$user)
                            ->where('prefijo','rds')
                            ->whereIn('tipo_formato',['INSPECCION_PREOPRACIONAL_DE_GRUA','INPECCION_PREOPERACIONAL_DE_MOTOCICLETAS','INSPECCION_PREOPERACIONAL_DE_VEHICULOS'])
                            ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                            ->get(['id','data_final ']);

            if(count($id_formato) > 0)
            {
                //Ya existe un evento de AIV
                
                //Valida si ya esta lleno el campo data_final
                if($id_formato[0]->data_final == NULL || $id_formato[0]->data_final == "NULL" || $id_formato[0]->data_final == "" )
                {
                    $usuarios = [];
                    for ($i=0; $i < count($personas); $i++) 
                    { 
                            $exist = 0;
                            for ($j=0; $j < count($usuarios); $j++) { 
                                if($usuarios[$j]['numero_documento'] == $personas[$i]['cedula'])
                                    $exist = 1;
                            }

                            if($exist == 0)
                            {
                                array_push($usuarios,
                                    array(
                                        'numero_documento' => $personas[$i]['cedula'],
                                        'cargo' => $personas[$i]['cargo'],
                                        'nombre' => ''
                                        )
                                );
                            }
                    }

                    //Arreglo de cuadrilleros
                    $datos = json_encode(array('allData' => array('detalle_conformacion' => $usuarios)));

                    DB::table('ssl_gop_formatos')
                            ->where('id',$id_formato[0]->id)
                            ->update(
                                array(
                                    'data_final' => $datos;
                                )
                            );
                }
            }
*/



            DB::table($pre. '_gps_tracker')
                ->insert(array(
                    array(
                        'prefijo' => $pre,
                        'latitud' => explode(",",$posicion)[0],
                        'longitud' => explode(",",$posicion)[1],
                        'fecha' => explode(" ",$this->fechaALong)[0],
                        'hora' => explode(" ",$this->fechaALong)[1],
                        'usuario_movil' => $user
                        )
                    ));  


            try
            {
                if($tipo == 6)            
                {
                    $contAuto = DB::table('ssl_gop_formatos')
                            ->where('id_origen',$user)
                            ->where('tipo_formato','autoinspeccion')
                            ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                            ->value('id');

                    $matricula = '';
                    if(isset($_REQUEST['matricula'])) {
                      $matricula = $_REQUEST['matricula'];
                    }

                    $maxEventF = 0;
                    $auxIngreso = 0;
                    if($contAuto == NULL || $contAuto == "")
                    {

                        DB::Table('ssl_gop_formatos')  
                        ->insert(array(
                            array(
                                'id_origen' => $user,
                                'tipo_formato' => "autoinspeccion",
                                'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                                'id_orden' => 0,
                                'prefijo' => $pre,
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'latitud' => trim(explode(",",$posicion)[0]),
                                'longitud' => trim(explode(",",$posicion)[1]),
                                'observaciones' => $observacion,
                                "consecutivo_formulario" => $concecutivo,
                                'placa' => $matricula
                                )
                            ));

                        $maxEventF = DB::table('ssl_gop_formatos')
                            ->where('id_origen',$user)
                            ->where('tipo_formato','autoinspeccion')
                            ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                            ->value('id');
                    }
                    else
                    {
                        $maxEventF = $contAuto;
                        $auxIngreso = 1;
                        DB::Table('ssl_gop_formatos')  
                            ->where('id',$maxEventF)
                            ->update(array(
                                    'hora' => explode(" ",$this->fechaALong)[1],
                                    'latitud' => trim(explode(",",$posicion)[0]),
                                    'longitud' => trim(explode(",",$posicion)[1]),
                                    'observaciones' => $observacion,
                                    'placa' => $matricula
                                ));

                        $concecutivo = DB::table('ssl_gop_formatos')
                                        ->where('id_origen',$user)
                                        ->where('tipo_formato','autoinspeccion')
                                        ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                                        ->value('consecutivo_formulario');

                    }
                    

                    $contAuto = DB::table('ssl_eventos')
                            ->where('id_origen',$user)
                            ->where('tipo_evento','autoinspeccion')
                            ->where('fecha',explode(" ",$this->fechaALong)[0])
                            ->value('id');

                    $maxEvent = 0;
                    if($contAuto == NULL || $contAuto == "")
                    {
                        $auxIngreso = 0;
                        DB::Table('ssl_eventos')  
                            ->insert(array(
                                array(
                                    'id_origen' => $user,
                                    'observaciones' => $observacion,
                                    'fecha' => explode(" ",$this->fechaALong)[0],
                                    'id_orden' => 0,
                                    'prefijo' => $pre,
                                    'tipo_evento' => "autoinspeccion",
                                    'hora' => explode(" ",$this->fechaALong)[1],
                                    'notificacion' => 0,
                                    "consecutivo_formulario" => $concecutivo,
                                    'x' => trim(explode(",",$posicion)[0]),
                                    'y' => trim(explode(",",$posicion)[1]),
                                    'id_formato' => $maxEventF,
                                    'conformidad' => $conforme,
                                    'placa' => $matricula
                                    )
                                ));

                        $maxEvent = DB::table('ssl_eventos')
                                ->where('id_origen',$user)
                                ->where('tipo_evento','autoinspeccion')
                                ->where('fecha',explode(" ",$this->fechaALong)[0])
                                ->value('id');
                    }
                    else
                    {
                        $maxEvent = $contAuto;
                        $auxIngreso = 1;
                        DB::Table('ssl_eventos')  
                            ->where('id',$maxEvent)
                            ->update(array(
                                    'hora' => explode(" ",$this->fechaALong)[1],
                                    'x' => trim(explode(",",$posicion)[0]),
                                    'y' => trim(explode(",",$posicion)[1]),
                                    'observaciones' => $observacion,
                                    'placa' => $matricula
                                ));

                        $concecutivo = DB::Table('ssl_eventos')
                                        ->where('id_origen',$user)
                                        ->where('tipo_evento','autoinspeccion')
                                        ->where('fecha',explode(" ",$this->fechaALong)[0])
                                        ->value('consecutivo_formulario');
                    }

                    DB::Table('gop_formulario_respuesta')
                            ->where('incidencia',$concecutivo)
                            ->delete();

                    //if($auxIngreso == 0)
                    //{
                        for ($i=0; $i < count($formulario); $i++) { 
                            DB::table('gop_formulario_respuesta')
                                    ->insert(array(
                                        array(
                                                'usuario' => $user,
                                                'tipo_form' => $tipo,
                                                'incidencia' => $concecutivo,
                                                'tipo_control' => $formulario[$i]['tipo'],
                                                'id_pregunta' => $formulario[$i]['pregunta'],
                                                'res' => $formulario[$i]["respuesta"],
                                                'version_formulario' => $version_frm,
                                                'prefijo' => $pre
                                            )
                                        ));
                        }

                        for ($i=0; $i < count($personas); $i++) { 
                            //Guardo respuestas persona
                            $cont = 2;
                            for ($j=1; $j <= 16; $j++) { 
                                DB::table('gop_formulario_respuesta')
                                    ->insert(array(
                                        array(
                                                'usuario' => $user,
                                                'tipo_form' => $tipo,
                                                'tipo_control' => 2,
                                                'id_pregunta' => $cont,
                                                'incidencia' => $concecutivo,
                                                'res' => $personas[$i]["respuesta" . $j],
                                                'id_persona_conformacion' => $personas[$i]['cedula'],
                                                'cargo_persona_conformacion' => $personas[$i]['cargo'],
                                                'version_formulario' => $version_frm,
                                                'prefijo' => $pre
                                            )
                                        ));
                                $cont++;
                            }
                        }

                        //Guardando fotos
                        /*
                        for ($i=0; $i < count($personas); $i++) { 
                            if(isset($personas[$i]['firma']))
                            {
                                if($personas[$i]['firma'] != "")   
                                {
                                    $inserta = 0;
                                    try
                                    {
                                        $data = explode(',', $personas[$i]['firma']);
                                        $imagenDigital = base64_decode($data[1]);

                                        //Nombre Fotografia
                                        $nombreArchivo = "Redes_Autoinspeccion_Firma_" . $personas[$i]['cedula'] . "_" .  substr(md5(uniqid(rand())), 0, 5) . ".png";
                                        self::envioArchivos($imagenDigital,$nombreArchivo,"/ssl/rds/FORMULARIOS/firmas");
                                        //$nombreArchivo = "/anexos_apa/documentosvehiculos/" . $nombreArchivo;

                                        $inserta = 1;
                                    }
                                    catch(Exception $e)
                                    {
                                        $inserta = 0; 
                                    } 

                                    if($inserta == 1)
                                    {
                                        DB::Table('ssl_gop_firmas_formatos')  
                                        ->insert(array(
                                            array(
                                                'id_formato' => 0,
                                                'ruta' => $nombreArchivo,
                                                'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                                                'id_origen' => $personas[$i]['cedula'],
                                                'hora' => explode(" ",$this->fechaALong)[1],
                                                'id_evento' => $maxEvent
                                                )
                                            ));
                                    }
                                }
                            }
                        }
                        */

                        //self::envioNotiSSL($maxEvent);
                    //}
                }

                if($tipo == 16)            
                {
                    $orden = $request->all()["orden"];

                    $contAuto = DB::table('ssl_gop_formatos')
                            ->where('id_origen',$user)
                            ->where('tipo_formato','preipo')
                            ->where('id_orden',$orden)
                            ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                            ->value('id');

                    $maxEventF = 0;
                    $auxIngreso = 0;
                    

                    if($contAuto == NULL || $contAuto == "")
                    {
                        DB::Table('ssl_gop_formatos')  
                        ->insert(array(
                            array(
                                'id_origen' => $user,
                                'tipo_formato' => "preipo",
                                'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                                'id_orden' => $orden,
                                'prefijo' => $pre,
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'latitud' => trim(explode(",",$posicion)[0]),
                                'longitud' => trim(explode(",",$posicion)[1]),
                                'observaciones' => $observacion,
                                "consecutivo_formulario" => $concecutivo
                                )
                            ));

                        $maxEventF = DB::table('ssl_gop_formatos')
                            ->where('id_origen',$user)
                            ->where('tipo_formato','preipo')
                            ->where('id_orden',$orden)
                            ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                            ->value('id');
                    }
                    else
                    {
                        $maxEventF = $contAuto;
                        $auxIngreso = 1;
                        DB::Table('ssl_gop_formatos')  
                            ->where('id',$maxEventF)
                            ->update(array(
                                    'hora' => explode(" ",$this->fechaALong)[1],
                                    'latitud' => trim(explode(",",$posicion)[0]),
                                    'longitud' => trim(explode(",",$posicion)[1]),
                                    'observaciones' => $observacion
                                ));

                        $concecutivo = DB::table('ssl_gop_formatos')
                                        ->where('id_origen',$user)
                                        ->where('tipo_formato','preipo')
                                        ->where('id_orden',$orden)
                                        ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                                        ->value('consecutivo_formulario');
                    }

                    $contAuto = DB::table('ssl_eventos')
                            ->where('id_origen',$user)
                            ->where('tipo_evento','preipo')
                            ->where('id_orden',$orden)
                            ->where('fecha',explode(" ",$this->fechaALong)[0])
                            ->value('id');

                    $maxEvent = 0;
                    if($contAuto == NULL || $contAuto == "")
                    {
                        DB::Table('ssl_eventos')  
                        ->insert(array(
                            array(
                                'id_origen' => $user,
                                'observaciones' => $observacion,
                                'fecha' => explode(" ",$this->fechaALong)[0],
                                'id_orden' => $orden,
                                'prefijo' => $pre,
                                'tipo_evento' => "preipo",
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'notificacion' => 0,
                                "consecutivo_formulario" => $concecutivo,
                                'x' => trim(explode(",",$posicion)[0]),
                                'y' => trim(explode(",",$posicion)[1]),
                                'id_formato' => $maxEventF,
                                'conformidad' => $conforme
                                )
                            ));

                        $maxEvent = DB::table('ssl_eventos')
                                ->where('id_origen',$user)
                                ->where('tipo_evento','preipo')
                                ->where('id_orden',$orden)
                                ->where('fecha',explode(" ",$this->fechaALong)[0])
                                ->value('id');
                    }
                    else
                    {
                        $maxEvent = $contAuto;
                        $auxIngreso = 1;
                        DB::Table('ssl_eventos')  
                            ->where('id',$maxEvent)
                            ->update(array(
                                    'hora' => explode(" ",$this->fechaALong)[1],
                                    'x' => trim(explode(",",$posicion)[0]),
                                    'y' => trim(explode(",",$posicion)[1]),
                                    'observaciones' => $observacion
                                ));   
                    }
                    

                    if($auxIngreso == 0)
                    {
                        for ($i=0; $i < count($personas); $i++) { 
                            //Guardo respuestas persona
                            $cont = 2;
                            for ($j=1; $j <= 16; $j++) { 

                                if($cont == 4)
                                    $cont = $cont + 2;

                                DB::table('gop_formulario_respuesta')
                                    ->insert(array(
                                        array(
                                                'usuario' => $user,
                                                'tipo_form' => $tipo,
                                                'tipo_control' => 2,
                                                'id_pregunta' => $cont,
                                                'incidencia' => $concecutivo,
                                                'res' => $personas[$i]["respuesta" . $j],
                                                'id_persona_conformacion' => $personas[$i]['cedula'],
                                                'cargo_persona_conformacion' => $personas[$i]['cargo'],
                                                'version_formulario' => $version_frm,
                                                'prefijo' => $pre
                                            )
                                        ));
                                //Firmas Save
                                $cont++;
                            }
                        }

                        for ($i=0; $i < count($formulario); $i++) { 
                            DB::table('gop_formulario_respuesta')
                                    ->insert(array(
                                        array(
                                                'usuario' => $user,
                                                'tipo_form' => $tipo,
                                                'incidencia' => $concecutivo,
                                                'tipo_control' => $formulario[$i]['tipo'],
                                                'id_pregunta' => $formulario[$i]['pregunta'],
                                                'res' => $formulario[$i]["respuesta"],
                                                'version_formulario' => $version_frm,
                                                'prefijo' => $pre
                                            )
                                        ));
                        } 

                        for ($i=0; $i < count($personas); $i++) { 

                            if(isset($personas[$i]['firma']))
                            {
                                if($personas[$i]['firma'] != "")   
                                {
                                    $data = explode(',', $personas[$i]['firma']);
                                    $imagenDigital = base64_decode($data[1]);
            
                                    //Nombre Fotografia
                                    $nombreArchivo = "Redes_PREOPERACION_IPO_Firma_" . $personas[$i]['cedula'] . "_" .  substr(md5(uniqid(rand())), 0, 5) . ".png";
                                    self::envioArchivos($imagenDigital,$nombreArchivo,"/ssl/rds/FORMULARIOS/firmas");
                                    //$nombreArchivo = "/anexos_apa/documentosvehiculos/" . $nombreArchivo;

                                    DB::Table('ssl_gop_firmas_formatos')  
                                    ->insert(array(
                                        array(
                                            'id_formato' => $tipo,
                                            'ruta' => $nombreArchivo,
                                            'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                                            'id_origen' => $personas[$i]['cedula'],
                                            'hora' => explode(" ",$this->fechaALong)[1],
                                            'id_evento' => $maxEvent 
                                            )
                                        ));
                                }
                            }
                        }

                        self::envioNotiSSL($maxEvent); 
                    }                        
                }   
                //DB::commit();
                return response()->json($maxEvent);
            }
            catch(Exception $e)
            {
                //DB::rollBack();
                return response()->json("0");
            } 
        }

        if($opc == "9") //Save Formularios Autoinspécción vehícular
        {
            $pre = $request->all()["pre"];
            $user = $request->all()["user"];
            $formulario = $request->all()["formulario"];
            
            $tipo = $request->all()["tipo_frm"];
            $posicion = $request->all()["coor"];
            $version_frm = $request->all()["version"];
            $observacion = $request->all()["observacion"];
            $placa = $request->all()["placa"];

            $concecutivo = explode(" ",$this->fechaALong)[0];
            $concecutivo1 = explode(" ",$this->fechaALong)[1];
            

            $conforme = "C";

            if(isset($request->all()["conforme"]))
                $conforme =$request->all()["conforme"];
            
            $concecutivo = explode("-",$concecutivo)[0] . "" . explode("-",$concecutivo)[1] . "" . explode("-",$concecutivo)[2] . "" . explode(":",$concecutivo1)[0] . "" . explode(":",$concecutivo1)[1] . "" . explode(":",$concecutivo1)[2] . "-" . substr(md5(uniqid(rand())), 0, 5);

            //DB::beginTransaction();

            DB::table($pre. '_gps_tracker')
                ->insert(array(
                    array(
                        'prefijo' => $pre,
                        'latitud' => explode(",",$posicion)[0],
                        'longitud' => explode(",",$posicion)[1],
                        'fecha' => explode(" ",$this->fechaALong)[0],
                        'hora' => explode(" ",$this->fechaALong)[1],
                        'usuario_movil' => $user
                        )
                    ));  


            try
            {
                $tipoEvento = "";
                
                if($tipo == 18)
                    $tipoEvento = "INSPECCION_PREOPRACIONAL_DE_GRUA";

                if($tipo == 9)
                    $tipoEvento = "INPECCION_PREOPERACIONAL_DE_MOTOCICLETAS";

                if($tipo == 10)
                    $tipoEvento = "INSPECCION_PREOPERACIONAL_DE_VEHICULOS";

                $contAuto = DB::table('ssl_gop_formatos')
                            ->where('id_origen',$user)
                            ->where('tipo_formato',$tipoEvento)
                            ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                            ->value('id');

                //Guardar el kilometraje del vehículo
                DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->insert(
                            array(
                                array(
                                    'placa' => $placa,
                                    'fecha_servidor' => $this->fechaALong,
                                    'fecha' => $this->fechaShort ,
                                    'kilometraje' => $request->all()["kilometraje"],
                                    'observaciones' => "AUTOINSPECCIÓN VEHÍCULAR REDES",
                                    'usuario' => $user,
                                    'tipo' => 3
                                    )
                                ));


                $maxEventF = 0;
                $auxIngreso = 0;                    

                if($contAuto == NULL || $contAuto == "")
                {
                    DB::Table('ssl_gop_formatos')  
                        ->insert(array(
                            array(
                                'id_origen' => $user,
                                'tipo_formato' => $tipoEvento,
                                'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                                'id_orden' => 0,
                                'prefijo' => $pre,
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'latitud' => trim(explode(",",$posicion)[0]),
                                'longitud' => trim(explode(",",$posicion)[1]),
                                'observaciones' => $observacion,
                                "consecutivo_formulario" => $concecutivo,
                                'placa' => $placa
                                )
                            ));


                    $maxEventF = DB::table('ssl_gop_formatos')
                            ->where('id_origen',$user)
                            ->where('tipo_formato',$tipoEvento)
                            ->where('fecha_creacion',explode(" ",$this->fechaALong)[0])
                            ->value('id');
                }
                else
                {
                    $maxEventF = $contAuto;
                     $auxIngreso =1;
                    DB::Table('ssl_gop_formatos') 
                        ->where('id',$maxEventF) 
                        ->update(
                            array(
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'latitud' => trim(explode(",",$posicion)[0]),
                                'longitud' => trim(explode(",",$posicion)[1]),
                                'observaciones' => $observacion
                            ));
                }


                $contAuto = DB::table('ssl_eventos')
                            ->where('id_origen',$user)
                            ->where('tipo_evento',$tipoEvento)
                            ->where('fecha',explode(" ",$this->fechaALong)[0])
                            ->value('id');

                $maxEvent = 0;
                if($contAuto == NULL || $contAuto == "")
                {
                     DB::Table('ssl_eventos')  
                        ->insert(array(
                            array(
                                'id_origen' => $user,
                                'observaciones' => $observacion,
                                'fecha' => explode(" ",$this->fechaALong)[0],
                                'id_orden' => 0,
                                'prefijo' => $pre,
                                'tipo_evento' => $tipoEvento,
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'notificacion' => 0,
                                "consecutivo_formulario" => $concecutivo,
                                'x' => trim(explode(",",$posicion)[0]),
                                'y' => trim(explode(",",$posicion)[1]),
                                'placa' => $placa,
                                'id_formato' => $maxEventF,
                                'conformidad' => $conforme
                                )
                            ));

                        $maxEvent = DB::table('ssl_eventos')
                                    ->where('id_origen',$user)
                                    ->where('tipo_evento',$tipoEvento)
                                    ->where('fecha',explode(" ",$this->fechaALong)[0])
                                    ->value('id');
                }
                else
                {
                    $maxEvent = $contAuto;
                    $auxIngreso = 1;
                    DB::Table('ssl_eventos')  
                        ->where('id',$maxEvent)
                        ->update(array(
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'x' => trim(explode(",",$posicion)[0]),
                                'y' => trim(explode(",",$posicion)[1]),
                                'observaciones' => $observacion
                            )); 

                    $concecutivo = DB::Table('ssl_eventos')
                                    ->where('id_origen',$user)
                                    ->where('tipo_evento',$tipoEvento)
                                    ->where('fecha',explode(" ",$this->fechaALong)[0])
                                    ->value('consecutivo_formulario');
                }

                 DB::Table('gop_formulario_respuesta')
                        ->where('incidencia',$concecutivo)
                        ->delete();

                //Inseta formulario
                for ($i=0; $i < count($formulario); $i++) { 
                    DB::table('gop_formulario_respuesta')
                            ->insert(array(
                                array(
                                        'usuario' => $user,
                                        'tipo_form' => $tipo,
                                        'incidencia' => $concecutivo,
                                        'tipo_control' => $formulario[$i]['tipo'],
                                        'id_pregunta' => $formulario[$i]['pregunta'],
                                        'res' => $formulario[$i]["respuesta"],
                                        'version_formulario' => $version_frm,
                                        'prefijo' => $pre
                                    )
                                ));
                }   
                    
                    //self::envioNotiSSL($maxEvent);   
                
                //DB::commit();

                //Generar incidencia
                
                if(isset($request->all()["kilometraje"]))
                {
                    if($conforme != "C")
                    {
                        $Headers = array(
                            "Content-type: application/json",
                            'Accept: application/json'
                        );

                        //Número de la cuadrilla - 
                        $data = array(
                                    'opc' => "1",
                                    'placa' => $placa,
                                    'km' => $request->all()["kilometraje"],
                                    'observacion' => $request->all()["elementos"],
                                    'gps' => trim(explode(",",$posicion)[0]) . "," . trim(explode(",",$posicion)[1]),
                                    'tipo_novedad' => "356",
                                    'tecnico' => $user,
                                    'prefijo' => $pre
                                    );

                        $data = json_encode($data);
                         
                        $url = config("app.server_transportes") . "/transporte/ws/appTecnico";
                        
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
                        curl_setopt($conexion, CURLOPT_POST, 1);
                        //Seteando los datos del webServices
                        curl_setopt($conexion, CURLOPT_POSTFIELDS, $data);
                        //Para que no genere problemas con el certificado SSL
                        curl_setopt($conexion, CURLOPT_SSL_VERIFYPEER, false);
                        $resultado = curl_exec($conexion);

                        if (FALSE === $resultado)
                            throw new \Exception(curl_error($conexion), curl_errno($conexion));

                        curl_close($conexion);
                        $resultado = json_decode($resultado, true);

                        $id = $resultado["id"];
                        $inci = $resultado["res"];

                        //Insertamos la incidencia
                        DB::table('ssl_eventos')
                            ->where('id',$maxEvent)
                            ->update(
                                    array(
                                            'incidencia_parque' => $inci
                                        )
                                );
                        
                    }
                }                    
                
                return response()->json($maxEvent);
            }
            catch(Exception $e)
            {
                //DB::rollBack();
                return response()->json("0");
            } 
        }

        if($opc == "10") //Save PARE
        {
            $direccion = $request->all()["direccion"];
            $motivo = $request->all()["motivo"];
            $descripcion = $request->all()["descripcion"];
            $solPropuesta = $request->all()["solPropuesta"];
            $tiempoPare = $request->all()["tiempoPare"];
            $posicion = $request->all()["coor"];
            $origen = $request->all()["origen"];
            $pre = $request->all()["pre"];

            $observacion = "DIRECCIÓN: " . $direccion . " MOTIVO: " . $motivo . " DESCRIPCIÓN: " . $descripcion . " SOLUCIÓN:" . $solPropuesta . " TIEMPO PARE: " . $tiempoPare . " MINUTOS";


            DB::table($pre. '_gps_tracker')
                ->insert(array(
                    array(
                        'prefijo' => $pre,
                        'latitud' => explode(",",$posicion)[0],
                        'longitud' => explode(",",$posicion)[1],
                        'fecha' => explode(" ",$this->fechaALong)[0],
                        'hora' => explode(" ",$this->fechaALong)[1],
                        'usuario_movil' => $origen
                        )
                    ));  


            DB::Table('ssl_gop_formatos')  
                    ->insert(array(
                        array(
                            'id_origen' => $origen,
                            'tipo_formato' => "PARE",
                            'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                            'id_orden' => 0,
                            'prefijo' => $pre,
                            'hora' => explode(" ",$this->fechaALong)[1],
                            'latitud' => trim(explode(",",$posicion)[0]),
                            'longitud' => trim(explode(",",$posicion)[1]),
                            'observaciones' => $observacion
                            )
                        ));


            $maxEventF = DB::table('ssl_gop_formatos')
                        ->where('tipo_formato',"PARE")
                        ->where('prefijo','rds')
                        ->get([DB::raw("MAX(id) as dat")])[0]->dat;


            $noti = 0;
            if(isset($request->all()["super"]))
                $noti = $request->all()["super"];

            DB::Table('ssl_eventos')  
                ->insert(array(
                    array(
                            'id_origen' => $origen,
                            'observaciones' => $observacion,
                            'fecha' => explode(" ",$this->fechaALong)[0],
                            'id_orden' => 0,
                            'prefijo' => $pre,
                            'tipo_evento' => "PARE",
                            'hora' => explode(" ",$this->fechaALong)[1],
                            'notificacion' => $noti,
                            "consecutivo_formulario" => 0,
                            'x' => trim(explode(",",$posicion)[0]),
                            'y' => trim(explode(",",$posicion)[1]),
                            'id_formato' => $maxEventF
                        )
                    ));

            $maxEvent = DB::table('ssl_eventos')
                        ->where('tipo_evento',"PARE")
                        ->where('prefijo','rds')
                        ->get([DB::raw("MAX(id) as dat")])[0]->dat;


            self::envioNotiSSL($maxEvent);
            

            return response()->json($maxEvent );
        }

        if($opc == "11") //Save PANICO
        {
            $origen = $request->all()["origen"];
            $pre = $request->all()["pre"];
            $posicion = $request->all()["coor"];
            $observacion = "EVENTO PÁNICO";

            DB::table($pre. '_gps_tracker')
                ->insert(array(
                    array(
                        'prefijo' => $pre,
                        'latitud' => explode(",",$posicion)[0],
                        'longitud' => explode(",",$posicion)[1],
                        'fecha' => explode(" ",$this->fechaALong)[0],
                        'hora' => explode(" ",$this->fechaALong)[1],
                        'usuario_movil' => $origen
                        )
                    )); 


            DB::Table('ssl_gop_formatos')  
                    ->insert(array(
                        array(
                            'id_origen' => $origen,
                            'tipo_formato' => "panico",
                            'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                            'id_orden' => 0,
                            'prefijo' => $pre,
                            'hora' => explode(" ",$this->fechaALong)[1],
                            'latitud' => trim(explode(",",$posicion)[0]),
                            'longitud' => trim(explode(",",$posicion)[1]),
                            'observaciones' => $observacion
                            )
                        ));


            $maxEventF = DB::table('ssl_gop_formatos')
                        ->where('tipo_formato',"panico")
                        ->where('prefijo','rds')
                        ->get([DB::raw("MAX(id) as dat")])[0]->dat;


            DB::Table('ssl_eventos')  
                ->insert(array(
                    array(
                            'id_origen' => $origen,
                            'observaciones' => $observacion,
                            'fecha' => explode(" ",$this->fechaALong)[0],
                            'id_orden' => 0,
                            'prefijo' => $pre,
                            'tipo_evento' => "panico",
                            'hora' => explode(" ",$this->fechaALong)[1],
                            'notificacion' => 0,
                            "consecutivo_formulario" => 0,
                            'x' => trim(explode(",",$posicion)[0]),
                            'y' => trim(explode(",",$posicion)[1]),
                            'id_formato' => $maxEventF
                        )
                    ));

            


            return response()->json(1);
        }

        if($opc == "12") //Save Incidencia parque a Eventos
        {
            $id_evento = $request->all()["evento"]; //id_evento
            $incidencia = $request->all()["incidencia"]; // incidencia generada
            
            DB::table('ssl_eventos')
                ->where('id',$id_evento)
                ->update(
                        array(
                                'incidencia_parque' => $incidencia
                            )
                    );


            $obser = DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia')
                        ->where('incidencia',$incidencia)
                        ->value('observacion');

            $lider = DB::table('ssl_eventos')
                    ->where('id',$id_evento)
                    ->value('id_origen');

            $pre = DB::table('ssl_eventos')
                    ->where('id',$id_evento)
                    ->value('prefijo');

            $movil = DB::table($pre . '_gop_cuadrilla')
                    ->where('id_lider',$lider)
                    ->value('id_movil');

            $com = DB::table($pre . '_gop_cuadrilla')
                    ->where('id_lider',$lider)
                    ->value('com');


            $id_super = DB::table($pre . '_gop_cuadrilla')
                    ->where('id_lider',$lider)
                    ->value('id_supervisor');

            $nombre = DB::table('rh_personas')
                    ->where('identificacion',$id_super)
                    ->value('nombres');

            $apellido = DB::table('rh_personas')
                    ->where('identificacion',$id_super)
                    ->value('apellidos');

            $obser = $obser . " - CUADRILLA: " . strtoupper($movil)  . " - CELULAR: " . $com . " - SUPERVISOR: " . ($id_super == "" || $id_super == NULL || $id_super == "NULL" ? "NO TIENE SUPERVISOR ASIGNADO EN CAMRPO WEB" : "" . $nombre . " " . $apellido);
            
            DB::connection('sqlsrvCxParque')
                ->table('tra_incidencia')
                ->where('incidencia',$incidencia)
                ->update(array(
                        'observacion' => $obser
                    ));

            return response()->json(array(
                    "id" => 1,
                    "res" => "Se ha guardado correctamente el evento")); //Incidencia
        }
        
        if($opc == "13") //Consulta si la autoinspección esta llena
        {
            $usuario = $request->all()["usuario"]; //id_evento
            $fecha = $this->fechaShort;

            $id_super = DB::table('rds_gop_cuadrilla')
                    ->where('id_lider',$usuario)
                    ->value('id_supervisor');

            $nombre = DB::table('rh_personas')
                    ->where('identificacion',$id_super)
                    ->value('nombres');

            $apellido = DB::table('rh_personas')
                    ->where('identificacion',$id_super)
                    ->value('apellidos');

            if(isset($request->all()["vehiculo"])) //Para calificar la AIV
            {
                //Calificar la Autoinspección vehícular
                $tipoEvento = "INPECCION_PREOPERACIONAL_DE_MOTOCICLETAS";

                $cont = DB::table('ssl_eventos')
                    ->where('id_origen',$usuario)
                    ->where('tipo_evento',$tipoEvento)
                    ->where('fecha',$fecha)
                    ->count();

                if($cont == 0)
                {
                    $tipoEvento = "INSPECCION_PREOPERACIONAL_DE_VEHICULOS";
                    
                    $cont = DB::table('ssl_eventos')
                        ->where('id_origen',$usuario)
                        ->where('tipo_evento',$tipoEvento)
                        ->where('fecha',$fecha)
                        ->count();    

                    if($cont == 0)
                    {
                        return response()->json(array(
                            "id" => 0,
                            "res" => "El supervisor aún no ha calificado su AUTOINSPECCIÓN VEHÍCULAR del día de hoy" . ($id_super == "" || $id_super == NULL || $id_super == "NULL" ? ": NO TIENE SUPERVISOR ASIGNADO EN CAMRPO WEB" : ": SUPERVISOR: " . $nombre . " " . $apellido) ));    
                    }

                    //Verificar la calificación de la Autoinspección vehícular
                    $resp = DB::table('ssl_eventos')
                            ->where('id_origen',$usuario)
                            ->where('fecha',$fecha)
                            ->where('tipo_evento',$tipoEvento)
                            ->value('respuesta_auto');

                    if($resp == "" || $resp == null || $resp == NULL)
                    {
                        return response()->json(array(
                                "id" => 0,
                                "res" => "El supervisor aún no ha calificado su AUTOINSPECCIÓN VEHÍCULAR del día de hoy" . ($id_super == "" || $id_super == NULL || $id_super == "NULL" ? ": NO TIENE SUPERVISOR ASIGNADO EN CAMRPO WEB" : ": SUPERVISOR: " . $nombre . " " . $apellido) ));
                    }

                }
                else //Verificar la calificación de la Autoinspección de la moto
                {
                    $resp = DB::table('ssl_eventos')
                            ->where('id_origen',$usuario)
                            ->where('fecha',$fecha)
                            ->where('tipo_evento',$tipoEvento)
                            ->value('respuesta_auto');

                    if($resp == "" || $resp == null || $resp == NULL)
                    {
                        return response()->json(array(
                                "id" => 0,
                                "res" => "El supervisor aún no ha calificado su AUTOINSPECCIÓN VEHÍCULAR del día de hoy" . ($id_super == "" || $id_super == NULL || $id_super == "NULL" ? ": NO TIENE SUPERVISOR ASIGNADO EN CAMRPO WEB" : ": SUPERVISOR: " . $nombre . " " . $apellido) ));
                    }
                }
            }


            $cont = DB::table('ssl_eventos')
                    ->where('id_origen',$usuario)
                    ->where('tipo_evento','autoinspeccion')
                    ->where('fecha',$fecha)
                    ->count();

            

            if($cont == 0)
            {
                return response()->json(array(
                        "id" => 0,
                        "res" => "El supervisor aún no ha calificado su AUTOINSPECCIÓN del día de hoy" . ($id_super == "" || $id_super == NULL || $id_super == "NULL" ? ": NO TIENE SUPERVISOR ASIGNADO EN CAMRPO WEB" : ": SUPERVISOR: " . $nombre . " " . $apellido) ));
            }

            $resp = DB::table('ssl_eventos')
                    ->where('id_origen',$usuario)
                    ->where('fecha',$fecha)
                    ->where('tipo_evento','autoinspeccion')
                    ->value('respuesta_auto');
                    

            if($resp == "" || $resp == null || $resp == NULL)
                return response()->json(array(
                        "id" => 0,
                        "res" => "El supervisor aún no ha calificado su AUTOINSPECCIÓN del día de hoy" . ($id_super == "" || $id_super == NULL || $id_super == "NULL" ? ": NO TIENE SUPERVISOR ASIGNADO EN CAMRPO WEB" : ": SUPERVISOR: " . $nombre . " " . $apellido) ));
            else
                return response()->json(array(
                        "id" => 1,
                        "res" => "La calificación de la AUTOINSPECCIÓN es: " . $resp . ($id_super == "" || $id_super == NULL || $id_super == "NULL" ? " - NO TIENE SUPERVISOR ASIGNADO EN CAMRPO WEB" : " - SUPERVISOR: " . $nombre . " " . $apellido) ));
        }


        if($opc == "14") //Actualiza a que orden le deseo ver la información
        {
            $proyNu = DB::table($this->tblAux . 'ordenes' . $this->valorT)
                ->where('id_orden',$request->all()['ot'])
                ->select('id_proyecto')
                ->get()[0]->id_proyecto;

            Session::put('rds_gop_proyecto_id',$proyNu);
            Session::put('rds_gop_proyecto_orden_id',$request->all()['ot']);

            Session::flash('gop_lider_seleccionado',$request->all()['lider']);
            Session::flash('gop_nodo_seleccionado',$request->all()['nodo']);
            
            return response()->json("1");
        }

        if($opc == "15") //Consultando cuadrilla
        {
            $lider = $request->all()["lider"]; //lider
            $pre = $request->all()["pre"]; //pre
            

            $cudrillas = DB::Table($pre . '_gop_cuadrilla as tbl1')
                        ->where('tbl1.id_lider',$lider)
                        ->leftJoin('rh_personas as tbl2','tbl1.id_lider','=','tbl2.identificacion')
                        ->leftJoin('rh_personas as tbl3','tbl1.id_aux1','=','tbl3.identificacion')
                        ->leftJoin('rh_personas as tbl4','tbl1.id_aux2','=','tbl4.identificacion')
                        ->leftJoin('rh_personas as tbl5','tbl1.id_aux3','=','tbl5.identificacion')

                        ->leftJoin('rh_personas as tblaux4','tbl1.id_aux4','=','tblaux4.identificacion')
                        ->leftJoin('rh_personas as tblaux5','tbl1.id_aux5','=','tblaux5.identificacion')
                        ->leftJoin('rh_personas as tblaux6','tbl1.id_aux6','=','tblaux6.identificacion')

                        ->leftJoin('rh_personas as tbl6','tbl1.id_conductor','=','tbl6.identificacion')


                        ->leftJoin('rh_personas_huella as tbl7','tbl1.id_lider','=','tbl7.usuario')
                        ->leftJoin('rh_personas_huella as tbl8','tbl1.id_aux1','=','tbl8.usuario')
                        ->leftJoin('rh_personas_huella as tbl9','tbl1.id_aux2','=','tbl9.usuario')
                        ->leftJoin('rh_personas_huella as tbl10','tbl1.id_aux3','=','tbl10.usuario')

                        ->leftJoin('rh_personas_huella as aux4','tbl1.id_aux4','=','aux4.usuario')
                        ->leftJoin('rh_personas_huella as aux5','tbl1.id_aux5','=','aux5.usuario')
                        ->leftJoin('rh_personas_huella as aux6','tbl1.id_aux6','=','aux6.usuario')

                        ->leftJoin('rh_personas_huella as tbl11','tbl1.id_conductor','=','tbl11.usuario')

                        ->get(['tbl1.id_lider','tbl1.id_aux1','tbl1.id_aux2','tbl1.id_aux3'
                            
                            ,'tbl1.id_aux4'
                            ,'tbl1.id_aux5'
                            ,'tbl1.id_aux6'

                            ,'tbl1.id_conductor',
                            DB::raw("(tbl2.nombres + ' ' + tbl2.apellidos) as nombreL"),
                            DB::raw("(tbl3.nombres + ' ' + tbl3.apellidos) as nombreAux1"),
                            DB::raw("(tbl4.nombres + ' ' + tbl4.apellidos) as nombreAux2"),
                            DB::raw("(tbl5.nombres + ' ' + tbl5.apellidos) as nombreAux3"),

                            DB::raw("(tblaux4.nombres + ' ' + tblaux4.apellidos) as nombreAux4"),
                            DB::raw("(tblaux5.nombres + ' ' + tblaux5.apellidos) as nombreAux5"),
                            DB::raw("(tblaux6.nombres + ' ' + tblaux6.apellidos) as nombreAux6"),

                            DB::raw("(tbl6.nombres + ' ' + tbl6.apellidos) as nombreCond"),
                            'tbl7.huella as huellaL',
                            'tbl8.huella as huellaAux1',
                            'tbl9.huella as huellaAux2',
                            'tbl10.huella as huellaAux3',

                            'aux4.huella as huellaAux4',
                            'aux5.huella as huellaAux5',
                            'aux6.huella as huellaAux6',

                            'tbl11.huella as huellaCondu',
                            'tbl1.matricula'
                            ])[0];

        
            return response()->json($cudrillas);
        }

        if($opc == "16") //Save Actividades Nuevo modelo
        {
            $baremos = $request->all()["baremos"];
            $pre = $request->all()["pre"];
            $id_proyecto = $request->all()["id_proyecto"];
            $tipoP = $request->all()["id_proyecto"];
         
            //echo count($baremos);
            for ($i=0; $i < count($baremos); $i++) { 
                
                $orden = $baremos[$i]["orden"];
                $lider = $baremos[$i]["lider"];

                DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                        ->where('id_orden',$orden)
                        ->where('id_lider',$lider)
                        ->update(
                                array(
                                        'fecha_actualiza_ejecucion' => $this->fechaALong
                                    )
                            );

                $proy = DB::table($pre . "_gop_ordenes")
                        ->where('id_orden',$baremos[$i]["orden"])
                        ->value('id_proyecto');

                //echo $proy;
                if($proy != "" && $proy =! null)
                {
                    $baremData = DB::table($pre . "_gop_baremos")
                                ->where('codigo',$baremos[$i]["bare"])
                                ->where('periodo',2018)
                                ->value('id_baremo');

                    if($tipoP == "T02")
                    {

                        $fecha_ejecucion = $this->fechaShort;
                            if(isset($baremos[$i]["fecha_ejecucion"]))
                                $fecha_ejecucion = $baremos[$i]["fecha_ejecucion"];

                        $cont = DB::table($pre . "_gop_ordenes_mobra")
                            ->where('id_orden',$baremos[$i]["orden"])
                            ->where('id_nodo',$baremos[$i]["nodo"])
                            ->where('id_baremo',$baremData)
                            ->where('id_origen',$lider)
                            ->whereBetween('fecha',[$fecha_ejecucion . " 00:00:00",$fecha_ejecucion . " 23:59:59"])
                            ->where('fecha',$this->fechaShort)
                            ->count();    
                    }
                    else
                    {
                        $cont = DB::table($pre . "_gop_ordenes_mobra")
                            ->where('id_orden',$baremos[$i]["orden"])
                            ->where('id_nodo',$baremos[$i]["nodo"])
                            ->where('id_baremo',$baremData)
                            ->where('id_origen',$lider)
                            ->count();    
                    }
                    
                        
                    $baremDataPrecio = DB::table($pre . "_gop_baremos")
                                ->where('codigo',$baremos[$i]["bare"])
                                ->where('periodo',2018)
                                ->value('precio');


                    if($cont == 0)//INSERT
                    {
                        $ws = DB::table($pre . "_gop_ws_nodos")
                        ->where('id_nodo1',$baremos[$i]["nodo"])
                        ->value('id_ws');

                        $fechaEJe = DB::table($pre . "_gop_ordenes")
                                    ->where('id_orden',$baremos[$i]["orden"])
                                    ->value('fecha_ejecucion');

                        if($tipoP == "T02")
                        {
                            $fecha_ejecucion = $this->fechaShort;
                            if(isset($baremos[$i]["fecha_ejecucion"]))
                                $fecha_ejecucion = $baremos[$i]["fecha_ejecucion"];

                            DB::table($pre . "_gop_ordenes_mobra")
                            ->insert(array(
                                array(
                                    'id_ws' => $ws,
                                    'id_proyecto' => $proy,
                                    'id_orden' => $baremos[$i]["orden"],
                                    'id_nodo' => $baremos[$i]["nodo"],
                                    'id_baremo' => $baremData,
                                    'cantidad_confirmada' => $baremos[$i]["cant"],
                                    'id_origen' => $lider,
                                    'fecha' => $fecha_ejecucion,
                                    'fecha_usuario_terreno_create' => $this->fechaALong
                                    )
                                ));


                            DB::table($pre . "_gop_mobra")
                                ->insert(array(
                                    array(
                                        'id_orden' => $baremos[$i]["orden"],
                                        'id_origen' => $lider,
                                        'id_baremo' => $baremData,
                                        'cantidad' => $baremos[$i]["cant"],
                                        'precio' => $baremDataPrecio,
                                        'fecha' => $fecha_ejecucion,
                                        'id_nodo' => $baremos[$i]["nodo"]
                                        )
                                    ));
                        }
                        else
                        {
                            DB::table($pre . "_gop_ordenes_mobra")
                            ->insert(array(
                                array(
                                    'id_ws' => $ws,
                                    'id_proyecto' => $proy,
                                    'id_orden' => $baremos[$i]["orden"],
                                    'id_nodo' => $baremos[$i]["nodo"],
                                    'id_baremo' => $baremData,
                                    'cantidad_confirmada' => $baremos[$i]["cant"],
                                    'id_origen' => $lider,
                                    'fecha' => $this->fechaALong,
                                    'fecha_usuario_terreno_create' => $this->fechaALong
                                    )
                                ));


                            DB::table($pre . "_gop_mobra")
                                ->insert(array(
                                    array(
                                        'id_orden' => $baremos[$i]["orden"],
                                        'id_origen' => $lider,
                                        'id_baremo' => $baremData,
                                        'cantidad' => $baremos[$i]["cant"],
                                        'precio' => $baremDataPrecio,
                                        'fecha' => $this->fechaALong,
                                        'id_nodo' => $baremos[$i]["nodo"]
                                        )
                                    ));
                        }
                        

                    }
                    else // UPDATE
                    {

                        if($tipoP == "T02")
                        {
                            $fecha_ejecucion = $this->fechaShort;
                            if(isset($baremos[$i]["fecha_ejecucion"]))
                                $fecha_ejecucion = $baremos[$i]["fecha_ejecucion"];

                            DB::table($pre . "_gop_mobra")
                                ->where('id_orden',$baremos[$i]["orden"])
                                ->where('id_baremo',$baremData)
                                ->where('id_origen',$lider)
                                ->whereBetween('fecha',[$fecha_ejecucion . " 00:00:00",$fecha_ejecucion . " 23:59:59"])
                                ->where('id_nodo',$baremos[$i]["nodo"])
                                ->update(
                                    array(
                                        'cantidad' => $baremos[$i]["cant"]
                                        )
                                    );

                                DB::table($pre . "_gop_ordenes_mobra")
                                ->where('id_orden',$baremos[$i]["orden"])
                                ->where('id_nodo',$baremos[$i]["nodo"])
                                ->where('id_baremo',$baremData)
                                ->where('fecha',$fecha_ejecucion)
                                ->where('id_origen',$lider)
                                ->update(
                                    array(
                                        'cantidad_confirmada' => $baremos[$i]["cant"],
                                        'fecha_usuario_terreno_update' => $this->fechaALong
                                        )
                                    );

                        }
                        else
                        {
                            DB::table($pre . "_gop_mobra")
                                ->where('id_orden',$baremos[$i]["orden"])
                                ->where('id_baremo',$baremData)
                                ->where('id_origen',$lider)
                                ->where('id_nodo',$baremos[$i]["nodo"])
                                ->update(
                                    array(
                                        'cantidad' => $baremos[$i]["cant"]
                                        )
                                    );

                                DB::table($pre . "_gop_ordenes_mobra")
                                ->where('id_orden',$baremos[$i]["orden"])
                                ->where('id_nodo',$baremos[$i]["nodo"])
                                ->where('id_baremo',$baremData)
                                ->where('id_origen',$lider)
                                ->update(
                                    array(
                                        'cantidad_confirmada' => $baremos[$i]["cant"],
                                        'fecha_usuario_terreno_update' => $this->fechaALong
                                        )
                                    );
                        }
                    }
                }
            }       


            $programadoOrden = DB::table($pre . '_gop_ordenes_manoobra')
                                ->where('id_orden',$orden)
                                ->select('id_nodo','id_baremo')
                                ->get();

            $ejecutadoOrden = DB::table($pre . '_gop_ordenes_mobra')
                                ->where('id_orden',$orden)
                                ->select('id_nodo','id_baremo')
                                ->get();

            $materialProgra = DB::table($pre . '_gop_ordenes_materiales_documentos')
                                ->where('id_orden',$orden)
                                ->select('id_documento','id_documento_cs')
                                ->get();

            return response()->json(1);
        }

        if($opc == "17") //Save Materiales nuevo modelo
        {            
            $pre = $request->all()["pre"];
            $tipoP = $request->all()['id_proyecto'];
            $materiales = $request->all()['materiales'];            
          
            $ingreso = 0;
            try
            {
                DB::beginTransaction();

                
                
                
                for ($i=0; $i < count($materiales); $i++) 
                { 

                    $orden = $materiales[$i]['orden'];
                    $lider = $materiales[$i]['lider'];


                    DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                            ->where('id_orden',$orden)
                            ->where('id_lider',$lider)
                            ->update(
                                    array(
                                            'fecha_actualiza_ejecucion' => $this->fechaALong
                                        )
                                );


                    $dc = DB::table($pre . "_gop_ordenes_materiales_documentos")
                        ->where('id_orden',$materiales[$i]["orden"])
                        ->where('id_nodo',$materiales[$i]["nodo"])
                        ->where('id_lider',$lider)
                        ->select('id_documento','id_documento_cs')
                        ->get()[0];


                    $proy = DB::table($pre . "_gop_ordenes")
                            ->where('id_orden',$materiales[$i]["orden"])
                            ->value('id_proyecto');

                        
                        //Si no existe CS -  SOlo ingresa una sola vez
                        if($dc->id_documento_cs == null && $dc->id_documento_cs == NULL) 
                        {
                                $codCosumo = self::dame_uncodigo_almacen("T007");
                                $gom = DB::table($pre . "_gop_ordenes")
                                    ->where('id_orden',$materiales[$i]["orden"])
                                    ->value('gom');

                                 //Crear documento DC
                                DB::table($pre  . '_inv_documentos')
                                ->insert(array(
                                    array(
                                        'id_documento' => $codCosumo,
                                        'id_tipo_movimiento' => "T007",
                                        'fecha' => $this->fechaShort,
                                        'fecha_sistema' => $this->fechaALong,
                                        'id_bodega_origen' => $lider,
                                        'observaciones' => $proy  . "_" . $materiales[$i]["orden"]  . "_" . $materiales[$i]["nodo"] . "_" . $lider,
                                        'id_estado' => 'E3',
                                        'id_orden' => $materiales[$i]["orden"],
                                        'gom' => $gom,
                                        "id_nodo" => $materiales[$i]["nodo"]
                                        )
                                    ));
                                //self::saveLog("OPERA52",$codCosumo," ORDEN: " . $materiales[$i]["orden"]);

                                //Asocia DC a CS
                                DB::table($pre . "_gop_ordenes_materiales_documentos")
                                            ->where('id_orden',$materiales[$i]["orden"])
                                            ->where('id_nodo',$materiales[$i]["nodo"])
                                            ->where('id_lider',$lider)
                                            ->where('id_documento',$dc->id_documento)
                                            ->update(array(
                                                'id_documento_cs' => $codCosumo,
                                                'fecha_terreno' => $this->fechaALong
                                                ));

                                if($materiales[$i]["articulo"] != "-1")
                                {
                                    $mateA = DB::table($pre . "_inv_maestro_articulos")
                                        ->where('codigo_sap',$materiales[$i]["articulo"])
                                        ->value('id_articulo');    
                                }else
                                    $mateA = $materiales[$i]["arti"];

                                //Inserto primer registro
                                DB::table($pre  . '_inv_detalle_documentos')
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codCosumo,
                                            'id_articulo' => $mateA, 
                                            'consumo' => $materiales[$i]["cant"],
                                            'i_rz' => $materiales[$i]["dato1"],
                                            'r_ch' => $materiales[$i]["dato2"],
                                            'r_rz' => $materiales[$i]["dato3"],
                                            'id_nodo' => $materiales[$i]["nodo"],
                                            'fecha_ejecucion' => $this->fechaALong
                                            )
                                        ));
                                //self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materiales[$i]["articulo"] . " CANTIDAD: " .  $materiales[$i]["cant"]);
                        }
                        else //Segunda vez que entra
                        {
                            
                            $codCosumo = $dc->id_documento_cs;
                            

                            if($materiales[$i]["articulo"] != "-1")
                            {
                                $mateA = DB::table($pre . "_inv_maestro_articulos")
                                    ->where('codigo_sap',$materiales[$i]["articulo"])
                                    ->value('id_articulo');    
                            }else
                                $mateA = $materiales[$i]["arti"];                           
                            

                            $contMat =  DB::table($pre  . '_inv_detalle_documentos')
                                    ->where('id_documento',$codCosumo)
                                    ->where('id_articulo',$mateA)
                                    ->count();                           

                            if($contMat == 0) //No existe el material
                            {
                                //Inserto siguiente  registros
                                DB::table($pre  . '_inv_detalle_documentos')
                                    ->insert(array(
                                        array(
                                            'id_documento' => $codCosumo,
                                            'id_articulo' => $mateA, 
                                            'consumo' => $materiales[$i]["cant"],
                                            'i_rz' => $materiales[$i]["dato1"],
                                            'r_ch' => $materiales[$i]["dato2"],
                                            'r_rz' => $materiales[$i]["dato3"],
                                            'id_nodo' => $materiales[$i]["nodo"],
                                            'fecha_ejecucion' => $this->fechaALong
                                            )
                                        ));

                                //self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materiales[$i]["articulo"] . " CANTIDAD: " .  $materiales[$i]["cant"]);    
                            }
                            else // Ya existe material - UPDATE
                            {
                                //Update siguiente  registros
                                DB::table($pre  . '_inv_detalle_documentos')
                                    ->where('id_documento',$codCosumo)
                                    ->where('id_articulo',$mateA)
                                    ->where('id_nodo',$materiales[$i]["nodo"])
                                    ->update(
                                        array(
                                            'consumo' => $materiales[$i]["cant"],
                                            'i_rz' => $materiales[$i]["dato1"],
                                            'r_ch' => $materiales[$i]["dato2"],
                                            'r_rz' => $materiales[$i]["dato3"]
                                            )
                                        );
                                //self::saveLog("OPERA53",$codCosumo," ARTICULO: " . $materiales[$i]["articulo"] . " CANTIDAD: " .  $materiales[$i]["cant"]);       
                            }
                        }
                }

                //Validar llenado TOTAL
                DB::commit();
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
            return response()->json("1");
        }

        if($opc == "18") //Guardar puesta a tierra
        {
            $orden = $request->all()['orden'];
            $fecha = $request->all()['fecha'];
            $medidas_res_pat = $request->all()['medidas_res_pat'];
            $medidas_corriente_pat = $request->all()['medidas_corriente_pat'];
            $estado_pararrayos_r = $request->all()['estado_pararrayos_r'];
            $estado_pararrayos_s = $request->all()['estado_pararrayos_s'];
            $estado_pararrayos_t = $request->all()['estado_pararrayos_t'];
            $estado_protecciones_r = $request->all()['estado_protecciones_r'];
            $estado_protecciones_s = $request->all()['estado_protecciones_s'];
            $estado_protecciones_t = $request->all()['estado_protecciones_t'];
            $estado_terminales_r = $request->all()['estado_terminales_r'];
            $estado_terminales_s = $request->all()['estado_terminales_s'];
            $estado_terminales_t = $request->all()['estado_terminales_t'];
            $concepto_tecnico_medida = $request->all()['concepto_tecnico_medida'];
            $concepto_tecnico_estado = $request->all()['concepto_tecnico_estado'];
            $descripcion_terreno = $request->all()['descripcion_terreno'];
            $descripcion_tubo = $request->all()['descripcion_tubo'];
            $descripcion_tensionado = $request->all()['descripcion_tensionado'];
            $descripcion_zunchado = $request->all()['descripcion_zunchado'];
            $descripcion_tipo = $request->all()['descripcion_tipo'];
            $descripcion_empalmes = $request->all()['descripcion_empalmes'];
            $descripcion_conector = $request->all()['descripcion_conector'];
            $descripcion_otros = $request->all()['descripcion_otros'];
            $usuario = $request->all()['usuario'];

            $contador  = DB::table('rds_gop_puesta_tierra')
                        ->where('id_orden',$orden)
                        ->where('usuario',$usuario)
                        ->value('id_puesta_tierra');
            
            $consecutivo = 0;

            if($contador == "" || $contador == null)
            {
                $consecutivo = self::generaConsecutivo("ID_PUESTA_TIERRA");
                DB::table('rds_gop_puesta_tierra')
                    ->insert(
                            array(
                                array(
                                        'id_puesta_tierra' => $consecutivo,
                                        'medidas_res_pat' => $medidas_res_pat,
                                        'medidas_corriente_pat' => $medidas_corriente_pat,
                                        'estado_pararrayos_r' => $estado_pararrayos_r,
                                        'estado_pararrayos_s' => $estado_pararrayos_s,
                                        'estado_pararrayos_t' => $estado_pararrayos_t,
                                        'estado_protecciones_r' => $estado_protecciones_r,
                                        'estado_protecciones_s' => $estado_protecciones_s,
                                        'estado_protecciones_t' => $estado_protecciones_t,
                                        'estado_terminales_r' => $estado_terminales_r,
                                        'estado_terminales_s' => $estado_terminales_s,
                                        'estado_terminales_t' => $estado_terminales_t,
                                        'concepto_tecnico_medida' => $concepto_tecnico_medida,
                                        'concepto_tecnico_estado' => $concepto_tecnico_estado,
                                        'descripcion_terreno' => $descripcion_terreno,
                                        'descripcion_tubo' => $descripcion_tubo,
                                        'descripcion_tensionado' => $descripcion_tensionado,
                                        'descripcion_zunchado' => $descripcion_zunchado,
                                        'descripcion_tipo' => $descripcion_tipo,
                                        'descripcion_empalmes' => $descripcion_empalmes,
                                        'descripcion_conector' => $descripcion_conector,
                                        'descripcion_otros' => $descripcion_otros,
                                        'id_orden' => $orden,
                                        'usuario' => $usuario
                                    )
                                )
                        );
            }
            else
            {
                $consecutivo = $contador;
                DB::table('rds_gop_puesta_tierra')
                    ->where('id_puesta_tierra',$consecutivo)
                    ->update(
                            array(
                                    'medidas_res_pat' => $medidas_res_pat,
                                    'medidas_corriente_pat' => $medidas_corriente_pat,
                                    'estado_pararrayos_r' => $estado_pararrayos_r,
                                    'estado_pararrayos_s' => $estado_pararrayos_s,
                                    'estado_pararrayos_t' => $estado_pararrayos_t,
                                    'estado_protecciones_r' => $estado_protecciones_r,
                                    'estado_protecciones_s' => $estado_protecciones_s,
                                    'estado_protecciones_t' => $estado_protecciones_t,
                                    'estado_terminales_r' => $estado_terminales_r,
                                    'estado_terminales_s' => $estado_terminales_s,
                                    'estado_terminales_t' => $estado_terminales_t,
                                    'concepto_tecnico_medida' => $concepto_tecnico_medida,
                                    'concepto_tecnico_estado' => $concepto_tecnico_estado,
                                    'descripcion_terreno' => $descripcion_terreno,
                                    'descripcion_tubo' => $descripcion_tubo,
                                    'descripcion_tensionado' => $descripcion_tensionado,
                                    'descripcion_zunchado' => $descripcion_zunchado,
                                    'descripcion_tipo' => $descripcion_tipo,
                                    'descripcion_empalmes' => $descripcion_empalmes,
                                    'descripcion_conector' => $descripcion_conector,
                                    'descripcion_otros' => $descripcion_otros
                                )
                        );
            }
            
            return response()->json($consecutivo);
        }

        if($opc == "19") //Guardar ejecución nodos estados
        {
            $datos = $request->all()["datos"];
            $pre = $request->all()["pre"];

            
            for ($i=0; $i < count($datos); $i++) { 
                
                $id_nodo = $datos[$i]['id_nodo'];
                $id_orden = $datos[$i]['id_orden'];
                $id_estado = $datos[$i]['id_estado'];
                $usuario = $datos[$i]['usuario'];

                DB::table($pre . '_gop_ordenes_manoobra')
                    ->where('id_nodo',$id_nodo)
                    ->where('id_orden',$id_orden)
                    ->where('id_personaCargo',$usuario)
                    ->update(
                        array(
                            'id_estado_nodo' => $id_estado,
                            'fecha_actualiza_id_estado_nodo' => $this->fechaALong
                            )
                        );

                //Validar que todos los nodos no esten ejecutados

                $estadoNodosArray = DB::table($pre . '_gop_ordenes_manoobra')
                                    ->where('id_orden',$id_orden)
                                    ->where('id_nodo','like','N%')
                                    ->get(['id_estado_nodo']);

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
                
                DB::table($pre . '_gop_ordenes')
                            ->where('id_orden',$id_orden)
                            ->update(
                                array(
                                    'id_estado' => $estado
                                )
                            );
                
                
                
                /*
                if($totalEjecutas > 0) //Estado de la orden Parcialmente Ejecutada
                {
                    DB::table($pre . '_gop_ordenes')
                            ->where('id_orden',$id_orden)
                            ->update(
                                array(
                                    'id_estado' => 'E4'
                                )
                            );
                }

                if($totalReprogramdas > 0) //Estado de la orden a reprogramada
                {
                    DB::table($pre . '_gop_ordenes')
                            ->where('id_orden',$id_orden)
                            ->update(
                                array(
                                    'id_estado' => 'R0'
                                )
                            );
                }
                */

                
            }

            return response()->json("1");
        }

        if($opc == "20") // Guardar las horas de inicio y fin de labores de cada técnico
        {
          $fecha_actual         = date('Y-m-d');
          $id_lider             = $request->all()["id_lider"];
          $hora_inicio_labores  = $request->all()["hora_inicio_labores"];
          $hora_fin_labores     = $request->all()["hora_fin_labores"];

          $data_actualizacion = array(
            'hri' => $hora_inicio_labores,
            'hre' => $hora_fin_labores
          );

          $conformacion_cuadrilla_supervisor = DB::Table('rds_gop_supervisor')
                                                ->where('lider', $id_lider)
                                                ->where('fecha_programacion','like',"{$fecha_actual}%")
                                                ->select('lider')
                                                ->get();

          if($conformacion_cuadrilla_supervisor) {
            DB::table('rds_gop_supervisor')
              ->where('lider', $id_lider)
              ->where('fecha_programacion','like',"{$fecha_actual}%")
              ->update($data_actualizacion);

            return response()->json([
              'error'     =>  false,
              'mensaje'   =>  'Las horas han sido almacenadas exitosamente.'
            ]);
          }
          else {
            return response()->json([
              'error'     =>  true,
              'mensaje'   =>  'ERROR: Error al actualizar las horas de labores. No existe una conformación de la cuadrilla para el día de hoy.'
            ]);
          }
        }
    }   


    private function dame_uncodigo_almacen($idtipo) {


        $mov = DB::Table('inv_tipomovimientos' . $this->valorT)
                ->where('id_tipo_movimiento',$idtipo)
                ->select('consecutivo','prefijo')
                ->get()[0];
        //$spt_cons = "select * from inv_tipomovimientos where  = '$idtipo' "; 
        

        $lnconsecutivo = $mov->consecutivo;
        $prefijo       = $mov->prefijo; 
        $longitud_max  = 12; 
        $relleno       = "0"; 
        
        //Aumentar Consecutivo
        $lnconsecutivo = intval($lnconsecutivo) + 1 ;

        //Ahora Actualizar el Consecutivo de una vez

            DB::table('inv_tipomovimientos' . $this->valorT)
            ->where('id_tipo_movimiento',$idtipo)
            ->update(array(
                'consecutivo' => $lnconsecutivo
                ));

        //Construir el Id de Retorno
        $num_relleno    = $longitud_max - strlen($prefijo) ; 
        $char_rellenos  = self::lfillchar($lnconsecutivo,$num_relleno,$relleno) ;
        $ret            = $prefijo.$char_rellenos.$lnconsecutivo ; 
        return $ret; 
    }


    /*FIN ORDENES DE TRABAJO PROGRAMADOS*/


    /*PLANNER*/

    public function mostrarPlanner()
    {
        return view("proyectos.planner.index");
    }

    public function consultPlanner(Request $request)
    {
        $mes = intval($request->all()["mes"]);
        $ultimoDia =  date("d",(mktime(0,0,0,$mes+1,1,"2017")-1));
        $fecha1 = "2017-" . $mes . "-01";
        $fecha2 = "2017-" . $mes . "-" . $ultimoDia;
        $proy = '';
        $tipoEs = $request->all()["est"];
        $tipoUser = $request->all()["tip"];
        $user = $request->all()["use"];
        $proy = $request->all()["proy"];

        $dias = "";
        if($tipoEs == "1")
        {
            for ($i=1; $i <= $ultimoDia ; $i++) { 
                $dias .= $i . ",";
            }
        }

        //$fecha = date('l', strtotime("2017" . "-" . $mes . "-01"));          

        $dias = substr($dias, 0, -1);

        $cad = "EXEC sp_gop_planner_consulta '" .  $fecha1
                     . "','" . $fecha2 . "','" . $proy . "'," . $tipoEs . ","
                      . $tipoUser . ",'" . $user . "','" . $mes . "','2017','" . $dias . "','" .  $proy . "'"  ;

   //     $cad = "EXEC  sp_gop_planner_consulta '2017-02-01', '2017-02-28','', 1, 1,'','1','2017','1,2,3,4'";

        $arrD = DB::select("SET NOCOUNT ON;" . $cad);
        //        array_push($arr,$arrD);

        return response()->json($arrD);
    }

    /*FIN PLANNER*/

    
    // /tprog/adjuntorestricciones

    private function envioArchivos($archivo,$nombreArchivo,$carpeta)
    {
        $id_ftp=ftp_connect("201.217.195.43",21); //Obtiene un manejador del Servidor FTP
        ftp_login($id_ftp,"usuario_ftp","74091450652!@#1723cc"); //Se loguea al Servidor FTP
        ftp_pasv($id_ftp,true); //Se coloca el modo pasivo
        ftp_chdir($id_ftp, $carpeta); // Nos dirigimos a la carpeta de destino
        $Directorio=ftp_pwd($id_ftp);
        $Directorio2=$Directorio;        
        $res = 0;
        try
        {
            $fileL = storage_path('app') . "/" .  $nombreArchivo;
            \Storage::disk('local')->put($nombreArchivo,  $archivo);
            //Enviamos la imagen al servidor FTP
            $exi = ftp_put($id_ftp,$Directorio . "/" . $nombreArchivo,$fileL,FTP_BINARY); 
            //Cuando se envia el archivo, se elimina el archivo

            if(file_exists($fileL))
                unlink($fileL);

            $res = 1;
        }catch(Exception $e)
        {
            $res = $e;
        }
        return $res;
    }


    private function envioNotiSSL($id_evento)
    {
        $Headers = array(
            "Content-type: application/json",
            'Accept: application/json'
        );
        
        $data = array(
                    'id_evento' => $id_evento
                    );

        $data = json_encode($data);
         
        $url = config("app.server_transportes") . "/envioNotificacionesSSLExterno";
        
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
        curl_setopt($conexion, CURLOPT_POST, 1);
        //Seteando los datos del webServices
        curl_setopt($conexion, CURLOPT_POSTFIELDS, $data);
        //Para que no genere problemas con el certificado SSL
        curl_setopt($conexion, CURLOPT_SSL_VERIFYPEER, false);
        $resultado = curl_exec($conexion);

        if (FALSE === $resultado)
            throw new \Exception(curl_error($conexion), curl_errno($conexion));

        curl_close($conexion);
        $resultado = json_decode($resultado, true);

        return $resultado;
    }

    function consultaordenguar(Request $request){
      
        $ot = $request->all()["ot"];           
        for ($i= (strlen($ot) + 2); $i < 12; $i++) { 
            $ot = "0" . $ot;
        }
        $ot = "OT" . $ot;
        
       $canti = DB::Table("rds_gop_ordenes")->where('id_orden',$ot)->count();    
         if($canti<=0){
        //echo "sicñlas3";
            return json_encode(['status'=>'0','statusText' => 'Error','message' => 'Registro no encontrado']);
        }
        
        $resu = DB::Table("rds_gop_ordenes")->where('id_orden',$ot)
                ->select('id_orden','hora_apertura','operador_ccontrol_abre','hora_cierre_d','operador_ccontrol_cierra')
                ->first();
         if($resu){
           // DB::table('cfg_log_copropiedades')->insert(array( $datoslog ));
        $response = array('status' => 1,'statusText' => 'Exito','message' => 'Proceso finalizado satisfactoriamente. ','resu'=>$resu);
        }else{
        $response = array('status' => 0,'statusText' => 'Error','message' => 'Ocurrió un error, por favor inténtalo nuevamente más tarde. ');
        }
        
    return json_encode($response);
    }
    
    
    function ghorasordentrabajo(Request $request){
        
        $datos = array();
        $id_orden = $request->all()['id_orden'];
        
        $datos['hora_apertura'] = $request->all()['hora_apertura'];
        $datos['operador_ccontrol_abre'] = $request->all()['operador_ccontrol_abre'];
        $datos['hora_cierre_d'] = $request->all()['hora_cierre'];
        $datos['operador_ccontrol_cierra'] = $request->all()['operador_ccontrol_cierra'];
        
        //echo "sicñlas";
        $canti = DB::Table("rds_gop_ordenes")->where('id_orden',$id_orden)->count();    
        //echo "sicñlas2";
        if($canti<=0){
        //echo "sicñlas3";
            return json_encode(['status'=>'0','statusText' => 'Error','message' => 'Registro no encontrado']);
        }
        //echo "sicñlas4";
        
        $res = DB::table('rds_gop_ordenes')->where('id_orden',$id_orden)->update($datos);
    
        if($res){
           // DB::table('cfg_log_copropiedades')->insert(array( $datoslog ));
        $response = array('status' => 1,'statusText' => 'Exito','message' => 'Proceso finalizado satisfactoriamente. ');
        }else{
        $response = array('status' => 0,'statusText' => 'Error','message' => 'Ocurrió un error, por favor inténtalo nuevamente más tarde. ');
        }
       
        
    return json_encode($response);
    }
    
    
    
}


 
