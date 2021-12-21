<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Redirect;
use Carbon\Carbon;
use DB;

class ControllerSupervisorTest extends Controller
{

    /*************************************************/
    /******** FUNCIONES DEL CONTROLADOR **************/

    //Función del constructor
    function __construct()
    {
        $this->fechaA = Carbon::now('America/Bogota');
        // Session::put('user_login',"U01853"); //Aleja
        $this->fechaALong = $this->fechaA->toDateTimeString();
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    //Genera consecutivos
    private function generaConsecutivo($tipo)
    {
        $consen = DB::table('gen_consecutivos')
            ->where('id_campo', $tipo)
            ->select('consecutivo', 'prefijo', 'long_cadena', 'caracter_relleno')
            ->get();

        if (count($consen) == 0)
            return -1;


        $lnconsecutivo = $consen[0]->consecutivo;
        $prefijo = $consen[0]->prefijo;
        $longitud_max = $consen[0]->long_cadena;
        $relleno = $consen[0]->caracter_relleno;

        //Aumentar Consecutivo
        $lnconsecutivo = $lnconsecutivo + 1;

        //Ahora Actualizar el Consecutivo de una vez
        DB::table('gen_consecutivos')
            ->where('id_campo', $tipo)
            ->update(array(
                'consecutivo' => $lnconsecutivo
            ));

        $num_relleno = $longitud_max - strlen($prefijo);
        $char_rellenos = self::lfillchar($lnconsecutivo, $num_relleno, $relleno);
        $ret = $prefijo . $char_rellenos . $lnconsecutivo;
        return $ret;
    }

    //Rellena los consecutivos generados con 0
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


    /**
     * Función encargada de guardar el log del usuario
     **/
    private function saveLog($id_log, $campo_valor, $des)
    {
        DB::table('ins_log')
            ->insert(array(
                array(
                    'tipo_log' => $id_log,
                    'usuario' => Session::get('user_login'),
                    'descripcion' => $des,
                    'campo_valor' => $campo_valor
                )
            ));

        return "1";
    }


    /*************************************************/
    /******** INSPECCIONES DE SEGURIDAD **************/

    /**
     * Función encargada de mostrar la pantalla principal de las inspecciones
     **/
    public function index()
    {

        if (!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $fechaActual = explode("-", $this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" . $fechaActual[1] . "/" . $fechaActual[0];

        $nuevafecha = strtotime('-1 month', strtotime($this->fechaShort));
        $nuevafecha = date('Y-m-j', $nuevafecha);

        $nuevafecha = explode("-", $nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" . $nuevafecha[1] . "/" . $nuevafecha[0];

        $consulta = "
        			SELECT proyecto,prefijo_db
        			FROM(
	                  SELECT
	                    gop_proyectos.nombre AS proyecto,
	                    gop_proyectos.prefijo_db,
	                    gop_proyectos.id_proyecto
	                  FROM sis_usuarios
	                    INNER JOIN sis_usuarios_proyectos AS conf_proy
	                      ON (conf_proy.id_usuario = sis_usuarios.id_usuario)
	                    INNER JOIN gop_proyectos
	                      ON (
                          gop_proyectos.id_proyecto = conf_proy.id_proyecto 
                          AND gop_proyectos.prefijo_db != ''
                        )
                      WHERE 1 = 1
                        --AND sis_usuarios.id_usuario = '" . Session::get('user_login') . "' 
                      GROUP BY gop_proyectos.nombre, 
                               gop_proyectos.prefijo_db,
                               gop_proyectos.id_proyecto
	                  ) as tbl GROUP BY tbl.proyecto,tbl.prefijo_db
	                  ORDER BY tbl.proyecto
	                        ";
        $proyecto = DB::select($consulta);


        $estados = DB::table('ins_estados')
            ->get();

        $inspeccion = DB::table('ins_inspeccion')
            ->leftJoin('gop_proyectos as pry1', 'pry1.id_proyecto', '=', 'ins_inspeccion.id_tipo_proyecto')
            ->leftJoin('gop_proyectos as pry2', 'pry2.id_proyecto', '=', 'ins_inspeccion.id_proyecto')
            ->rightjoin('rh_personas as p1', 'p1.identificacion', '=', 'ins_inspeccion.supervisor')
            ->rightjoin('rh_personas as p2', 'p2.identificacion', '=', 'ins_inspeccion.lider')
            ->join('ins_estados as es', 'es.id_estado', '=', 'ins_inspeccion.estado')
            ->select('id_inspeccion', 'id_orden', 'pry1.nombre', DB::raw("p1.nombres + ' ' + p1.apellidos as nombreS"),
                'fecha_servidor', DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"), 'supervisor', 'lider',
                'movil', 'tipo_inspeccion', 'resultado', 'estado', 'es.nombre as nombreE'
                , 'calificacion', 'pry2.nombre as ceco', 'pry1.prefijo_db as pre1', 'pry2.prefijo_db as pre2', 'ins_inspeccion.id_tipo_proyecto', 'ins_inspeccion.id_proyecto');

        if (Session::has('proyecto')) {

            if (Session::get('proyecto') != "") {
                //$inspeccion = $inspeccion->where('prefijo',Session::get('proyecto'));
                /* $inspeccion = $inspeccion->where(function($query)
                                {
                                      $query->orWhere('prefijo',Session::get('proyecto')

                                              pry1.nombre
                                              )
                                        //    ->orWhere('pry1.prefijo_db',Session::get('proyecto'))
                                            ->orWhere('pry2.prefijo_db',Session::get('proyecto'));
                                });  */

                /* $inspeccion = $inspeccion->whereRaw(" ((pry1.nombre is not null and pry1.prefijo_db= '".Session::get('proyecto')."' ) or
                                                                 (pry2.prefijo_db = '".Session::get('proyecto')."' )) " );*/


                if (Session::get('tipo_inspeccion') != null && Session::get('tipo_inspeccion') != '' && (Session::get('tipo_inspeccion') == 3 || Session::get('tipo_inspeccion') == '3')) {
                    // if( (int)Session::get('proyecto') != 3 ){
                    $inspeccion = $inspeccion->where('pry2.prefijo_db', Session::get('proyecto'));
                } else {
                    $inspeccion = $inspeccion->where('prefijo', Session::get('proyecto'));
                }
            }

            if (Session::get('fecha_inicio') != "" && Session::get('fecha_corte') != "") {
                $fecha1 = explode("/", Session::get('fecha_inicio'));
                $fecha1 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];

                $fecha2 = explode("/", Session::get('fecha_corte'));
                $fecha2 = $fecha2[2] . "-" . $fecha2[1] . "-" . $fecha2[0];

                $inspeccion = $inspeccion->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"]);
            }

            if (Session::get('tipo_inspeccion') != "")
                $inspeccion = $inspeccion->where('tipo_inspeccion', Session::get('tipo_inspeccion'));

            if (Session::get('resultado') != "")
                $inspeccion = $inspeccion->where('resultado', Session::get('resultado'));

            if (Session::get('super') != "")
                $inspeccion = $inspeccion->where('supervisor', 'LIKE', '%' . Session::get('super') . '%');

            if (Session::get('id_estado') != "")
                $inspeccion = $inspeccion->where('estado', Session::get('id_estado'));

            $inspeccion = $inspeccion->orderBy('id_inspeccion')->get();


        } else {
            $inspeccion = $inspeccion->whereBetween('fecha_servidor', [$this->fechaShort . " 00:00:00", $this->fechaShort . " 23:59:49"])
                ->orderBy('id_inspeccion')
                ->get();
        }

        return view('proyectos.supervisor.indexTest', array(
            "fecha1" => $fechaActual,
            "fecha2" => $nuevafecha,
            "proyectos" => $proyecto,
            "inspeccion" => $inspeccion,
            "estados" => $estados,
            'proyectoses' => Session::get('proyecto')
        ));
    }

    public function formulog(Request $request)
    {
        $tipoformulario = '';
        if (isset($request->all()['tipo'])) {
            $tipoformulario = trim($request->all()['tipo']);
        }

        $responce = array();

        $page = $request->all()['draw']; // get the requested page
        $limit = $request->all()['length']; // get how many rows we want to have into the grid
        //$sidx = $request->all()['sidx']; // get index row - i.e. user click to sort
        // $sord = $request->all()['sord'];
        $start = $request->all()['start'];
        $columnas = $request->all()['columns'];

        $ordena = $request->all()['order'];
        $ordenamiento = " order by  l.fecha desc ";
        if (isset($request->all()['order']) && isset($ordena[0]['column']) && $ordena[0]['column'] != null && $ordena[0]['column'] != 0) {


            $ordena = $request->all()['order'];
            $ncol = $ordena[0]['column'];

            $colunma = $columnas[$ncol];
            if ($colunma['orderable']) {
                $order = $colunma['name'];

                $direcc = $request->all()['order'];
                $dir = $direcc[0]['dir'];

                if ($order == 'datou') {
                    $order = 't.nombre';
                } else if ($order == 'datod') {
                    $order = "s.propietario";
                } else if ($order == 'datot') {
                    $order = "CONCAT(CONVERT(varchar,l.fecha,103),' ',CONVERT(varchar,l.fecha,108))";
                } else if ($order == 'datoc') {
                    $order = "l.version_previa";
                } else if ($order == 'datoci') {
                    $order = "l.version_posterior";
                }

                $ordenamiento = " order by " . $order . " " . $dir . " ";
            }
        }

        ///
        $search = $request->all()['search'];
        $buscar = "";
        if (isset($request->all()['search']) && isset($search['value']) && $search['value'] != null && $search['value'] != '') {
            $buscarc = $search['value'];
            $buscar = " and (  
                             t.nombre like '%" . $buscarc . "%' or
                             s.propietario like '%" . $buscarc . "%' or
                             CONCAT(CONVERT(varchar,l.fecha,103),' ',CONVERT(varchar,l.fecha,108))  like '%" . $buscarc . "%' or
                             l.version_previa like '%" . $buscarc . "%' or
                             l.version_posterior like '%" . $buscarc . "%' 
                        )     ";
        }

        //////////////////////
        /////////////////


        $scantidad = "
                    select 
                        COUNT(*) as cantidad
                    from gop_formularios_creacion_log as l 
                    where l.tipo_formulario='" . $tipoformulario . "' 
                    " . $buscar . "";
//echo $spt;


        try {
            $rcant = DB::select("SET NOCOUNT ON;" . $scantidad);
            $responce['recordsTotal'] = $rcant[0]->cantidad;//$total_pages;
            $responce['recordsFiltered'] = $rcant[0]->cantidad;//
        } catch (\PDOException $e) {
            $responce['recordsTotal'] = 0;//$total_pages;
            $responce['recordsFiltered'] = 0;//
        }

        $responce['draw'] = $page;//$page;
        $responce['data'] = array();


        $spt = "select 
                    t.nombre as tipo,
                    s.propietario as usurio,
                    CONCAT(CONVERT(varchar,l.fecha,103),' ',CONVERT(varchar,l.fecha,108)) as fecha,
                    l.version_previa,
                    l.version_posterior
               from gop_formularios_creacion_log as l inner join
                             gop_formulario_tipo as t  on (l.tipo_formulario= t.tipo_formulario)  inner join
                             sis_usuarios as s on (l.id_usuario = s.id_usuario)
               where l.tipo_formulario='" . $tipoformulario . "' 
                " . $buscar . "
                " . $ordenamiento . "
            OFFSET " . $start . " ROWS FETCH NEXT " . $limit . " ROWS ONLY ";
//echo $spt;


        try {
            $cond = DB::select($spt);
        } catch (\PDOException $e) {
            $cond = array();
        }
        // echo $spt;

        /*


        $i++;*/

        for ($indi = 0; $indi < count($cond); $indi++) {

            $nuevo = array(
                $cond[$indi]->tipo,
                $cond[$indi]->usurio,
                $cond[$indi]->fecha,
                $cond[$indi]->version_previa,
                $cond[$indi]->version_posterior
            );

            array_push($responce['data'], $nuevo);

        }


        return json_encode($responce);


    }

    /**
     * Filtro para mostrar la pantalla principal de las inspecciones
     **/
    public function filterInspeccion(Request $request)
    {
        Session::flash('proyecto', $request->all()["id_proyecto"]);
        Session::flash('fecha_inicio', $request->all()["fecha_inicio"]);
        Session::flash('fecha_corte', $request->all()["fecha_corte"]);
        Session::flash('tipo_inspeccion', $request->all()["tipo_insp"]);
        Session::flash('resultado', $request->all()["resultado"]);
        Session::flash('super', $request->all()["super"]);
        Session::flash('id_estado', $request->all()["id_estado"]);

        //var_dump($request->all());
        return Redirect::to('inspeccionOrdenes');
    }

    // Función encargada de mostrar el detalle de una inspección en específico
    public function indexInspeccion($id_inspeccion, $abrir = "")
    {

        if (!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        if ($abrir == "1")
            Session::flash('abrir', 1);

        $dato = DB::Table('ins_inspeccion')
            ->where('id_inspeccion', $id_inspeccion)
            ->where('resultado', 'NC')
            ->get();

        $analisis = DB::Table('ins_analisis_causas')
            ->where('id_inspeccion', $id_inspeccion)
            ->select('analisis', 'id_item', 'des_item', 'id', 'adjunto')
            ->get();

        $plan = DB::Table('ins_plan_accion')
            ->where('id_inspeccion', $id_inspeccion)
            ->select('accion', 'responsable', 'fecha_limite', 'fecha_cierre', 'observacion_cierre', 'id', 'evidencia', 'item', 'des_item', 'id_analisis', 'des_analisis', 'usuario')
            ->get();

        $inspec = DB::Table('ins_inspeccion as tbl1')
            ->where('tbl1.id_inspeccion', $id_inspeccion)
            ->leftJoin('gop_proyectos as pry1', 'pry1.id_proyecto', '=', 'tbl1.id_tipo_proyecto')
            ->leftJoin('gop_proyectos as pry2', 'pry2.id_proyecto', '=', 'tbl1.id_proyecto')
            ->join('rh_personas as tbl2', 'tbl2.identificacion', '=', 'tbl1.supervisor')
            ->join('rh_personas as tbl3', 'tbl3.identificacion', '=', 'tbl1.lider')
            ->leftJoin('rh_personas as tbl4', 'tbl4.identificacion', '=', 'tbl1.aux1')
            ->leftJoin('rh_personas as tbl5', 'tbl5.identificacion', '=', 'tbl1.aux2')
            ->leftJoin('rh_personas as tbl6', 'tbl6.identificacion', '=', 'tbl1.aux3')
            ->leftJoin('rh_personas as tbl7', 'tbl7.identificacion', '=', 'tbl1.conductor')
            ->join('ins_estados as tbl8', 'tbl8.id_estado', '=', 'tbl1.estado')
            ->select('tbl1.id_orden', 'tbl1.prefijo', 'tbl1.supervisor', 'tbl1.fecha_servidor', 'tbl1.lider', 'tbl1.aux1',
                'tbl1.aux2', 'tbl1.aux3', 'tbl1.conductor', 'tbl1.matricula', 'tbl1.tipo_cuadrilla', 'tbl1.movil',
                'tbl1.tipo_inspeccion', 'tbl1.resultado', 'tbl1.estado',
                'tbl2.nombres as nombreS', 'tbl2.apellidos as apellidosS',
                'tbl3.nombres as nombreL', 'tbl3.apellidos as apellidosL',
                'tbl4.nombres as nombreA1', 'tbl4.apellidos as apellidosA1',
                'tbl5.nombres as nombreA2', 'tbl5.apellidos as apellidosA2',
                'tbl6.nombres as nombreA3', 'tbl6.apellidos as apellidosA3',
                'tbl7.nombres as nombreC', 'tbl7.apellidos as apellidosC', 'tipo_inspeccion',
                DB::raw(" IIF(tbl1.tipo_inspeccion=3,pry2.nombre,pry1.nombre) as proyecto "),
                'tbl8.nombre as estadoE', 'tbl1.calificacion', 'tbl1.charla_calificacion', 'tbl1.observacion')
            ->get()[0];

        $foto = DB::Table('ins_foto')
            ->where('id_inspeccion', $id_inspeccion)
            ->value('ruta');

        $tipoForm = $inspec->tipo_inspeccion;
        if ($tipoForm == "3")
            $tipoForm = 20;

        if ($tipoForm == "4")
            $tipoForm = 26;

        $verion = DB::table('ins_inspeccion_detalle as tbl1')
            ->where('tbl1.id_formulario', $tipoForm)
            ->where('tbl1.id_inspeccion', $id_inspeccion)
            ->value('version');

        $formCreacion = DB::table('gop_formularios_creacion as tbl1')
            ->where('tbl1.tipo_formulario', $tipoForm)
            ->where('tbl1.version', $verion)
            ->select('tbl1.item_num', 'tbl1.id_pregunta', 'tbl1.descrip_pregunta', 'tbl1.nombre_corto', 'tipo_control')
            ->orderBy('tbl1.id_pregunta')
            ->get();


        $form = DB::table('ins_inspeccion_detalle as tbl1')
            ->join('gop_formularios_creacion as tbl2', 'tbl1.id_pregunta', '=', 'tbl2.id_pregunta')
            ->where('tbl1.id_formulario', $tipoForm)
            ->where('tbl2.tipo_formulario', $tipoForm)
            ->where('tbl1.version', $verion)
            ->where('tbl2.version', $verion)
            ->where('tbl1.id_inspeccion', $id_inspeccion)
            ->select('tbl2.item_num', 'tbl2.id_pregunta', 'tbl2.descrip_pregunta', 'tbl2.tipo_control',
                'tbl1.respuesta', 'tbl1.acto_condicion', 'tbl1.texto_extra')
            ->orderBy('tbl2.id_pregunta')
            ->get();


        if ($inspec->id_orden == "") {
            $fotoInspeccion = DB::Table('ins_foto')
                ->where('orden', $id_inspeccion)
                ->where('id_tipo', '<>', 'T04')
                ->get(['ruta']);
        } else {
            if ($tipoForm == "1")//Seguridad
                $fotoInspeccion = DB::Table('ins_foto')
                    ->where('orden', $inspec->id_orden)
                    ->where('id_tipo', '=', 'T08')
                    ->get(['ruta']);
            else //Calidad
                $fotoInspeccion = DB::Table('ins_foto')
                    ->where('orden', $inspec->id_orden)
                    ->where('id_tipo', '=', 'T09')
                    ->get(['ruta']);
        }


        $consulta = "
              SELECT
                    gop_proyectos.nombre AS proyecto,
                    gop_proyectos.prefijo_db,
                    (
                        select top(1) tbl1.id_proyecto
                        FROM gop_proyectos as tbl1
                        WHERE tbl1.prefijo_db = gop_proyectos.prefijo_db
                    ) as id_proyecto
                    FROM sis_usuarios
                    INNER JOIN sis_usuarios_proyectos AS conf_proy
                        ON (conf_proy.id_usuario = sis_usuarios.id_usuario)
                    INNER JOIN gop_proyectos
                        ON (gop_proyectos.id_proyecto = conf_proy.id_proyecto AND gop_proyectos.prefijo_db != '')
                    WHERE sis_usuarios.cuenta = 'CO8778565' 
                    GROUP BY gop_proyectos.nombre, gop_proyectos.prefijo_db
                    ORDER BY gop_proyectos.nombre
                        ";
        $retorno = DB::select($consulta);
        //Consulta analisis
        //Consulta plan de acciónx
        return view('proyectos.supervisor.showInspeccionRegistro',
            array('insp' => $id_inspeccion,
                'causas' => $analisis,
                'plan' => $plan,
                'inspeccion' => $inspec,
                'form' => $form,
                "proy" => $retorno,
                'formCreacion' => $formCreacion,
                'fotos' => $fotoInspeccion,
                "firma" => $foto));
    }

    public function regenerarDetalleIPAL(Request $request)
    {
        $error = false;
        $mensaje = '';

        $id_inspeccion = $request->all()["id_inspeccion"];
        $id_orden = $request->all()["id_orden"];

        $id_formulario_ipal = 1;
        $version_actual_ipales = '';
        $ultimo_ipal_generado = '';
        $item_ipal = '';

        $detalle_ultimo_ipal_generado = null;

        // ================================================================================================
        // Se regenera el IPAL
        // ================================================================================================
        if ($id_inspeccion) {
            $version_actual_ipales = DB::table('ins_inspeccion_detalle')
                ->where('id_formulario', $id_formulario_ipal)
                ->value(DB::raw('MAX(version) AS version'));

            $ultimo_ipal_generado = DB::table('ins_inspeccion_detalle')
                ->where('id_formulario', $id_formulario_ipal)
                ->where('version', $version_actual_ipales)
                ->value(DB::raw('MAX(id_inspeccion) AS id_inspeccion'));

            // SE OBTIENE EL ULITMO IPAL GENERADO
            $detalle_ultimo_ipal_generado = DB::table('ins_inspeccion_detalle')
                ->where('id_formulario', $id_formulario_ipal)
                ->where('id_inspeccion', $ultimo_ipal_generado)
                ->get();

            // Se regenera cada item del IPAL
            foreach ($detalle_ultimo_ipal_generado as $row) {
                $respuesta_item = 2;
                if ($row->id_pregunta == 119) {
                    $respuesta_item = 'Condiciones Optimas.';
                }

                $item_ipal = array(
                    'id_inspeccion' => $id_inspeccion,
                    'id_orden' => $id_orden,
                    'id_pregunta' => $row->id_pregunta,
                    'acto_condicion' => '',
                    'id_formulario' => $id_formulario_ipal,
                    'respuesta' => $respuesta_item,
                    'texto_extra' => '',
                    'version' => $version_actual_ipales
                );
                DB::table('ins_inspeccion_detalle')
                    ->insert(array(
                        $item_ipal
                    ));
            }
        } else {
            $error = true;
            $mensaje = "Por favor ingrese todos los valores, son obligatorios.";
        }

        // ================================================================================================
        // Se envía la respuesta al usuario
        // ================================================================================================
        return response()->json([
            'error' => $error,
            'mensaje' => $mensaje,

            /*
        'id_inspeccion' =>  $id_inspeccion,
        '_REQUEST'    =>  $_REQUEST,
        'version_actual_ipales'    =>  $version_actual_ipales,
        'ultimo_ipal_generado'    =>  $ultimo_ipal_generado,
        'detalle_ultimo_ipal_generado'    =>  $detalle_ultimo_ipal_generado,
        'item_ipal'    =>  $item_ipal
        */
        ]);
    }


    /******** FIN INSPECCIONES DE SEGURIDAD **************/
    /*************************************************/


    /*************************************************/
    /******** CREACIÓN DE FORMULARIOS **************/
    //Consulta permisos usuarios
    private function consultaAcceso($opc)
    {
        $id_perfil = DB::table('sis_usuarios')
            ->where('id_usuario', Session::get('user_login'))
            ->value('id_perfil');

        $per = DB::table('sis_perfiles_opciones')
            ->where('id_perfil', $id_perfil)
            ->where('id_opcion', $opc)
            ->select('id_opcion', 'nivel_acceso')
            ->get();

        if (count($per) == 0)
            return "N";
        else
            return $per[0]->nivel_acceso;
    }

    //Mostrar la pantalla para la creación de formulario
    public function indexFormularios()
    {


        $permisoform = self::consultaAcceso('OP111'); //Creacion de formularios

        $permif = 0;
        if (count($permisoform) > 0) {
            if ($permisoform == "W") {
                $permif = 1;
            }
        }

        if ($permif == 0) {
            return view('proyectos.supervisor.sinPermisos', array());
            return;
        }


        $tipoForm = DB::table('gop_formulario_tipo')
            ->lists('nombre', 'tipo_formulario');

        $tipoControl = DB::table('gop_formulario_tipo_control')
            ->lists('nombre', 'tipo_control');

        $formSelect = [];
        $correos = [];
        if (Session::has('frm_filter')) {
            $versionActual = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', Session::get('frm_filter'))
                ->value(DB::raw('MAX(version) as dat'));

            $formSelect = DB::Table('gop_formularios_creacion')
                ->where('tipo_formulario', Session::get('frm_filter'))
                ->where('version', $versionActual)
                ->orderBy('id_pregunta')
                ->get();

            $correos = DB::Table('gop_formulario_tipo')
                ->where('tipo_formulario', Session::get('frm_filter'))
                ->get([
                        'correo1',
                        'correo2',
                        'correo3'
                    ]
                )[0];

        }


        return view('proyectos.supervisor.indexFormularios', array(
            'tipoform' => $tipoForm,
            'tipoControl' => $tipoControl,
            'formSelect' => $formSelect,
            'correos' => $correos
        ));
    }

    //Filtro para mostrar la pantalla para la creación de formulario
    public function filerformularioscreacion(Request $request)
    {
        Session::flash('frm_filter', $request->all()["proyecto_frm"]);
        return Redirect::to('creacionFormularios');
    }

    /******** FIN CREACIÓN DE FORMULARIOS **************/
    /*************************************************/


    /*************************************************/
    /******** INASISTENCIAS *************************/

    //Mostrar la pantalla principal de las inasistencias
    public function indexInasistencia()
    {

        if (!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $fechaActual = explode("-", $this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" . $fechaActual[1] . "/" . $fechaActual[0];

        $nuevafecha = strtotime('-1 month', strtotime($this->fechaShort));
        $nuevafecha = date('Y-m-j', $nuevafecha);

        $nuevafecha = explode("-", $nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" . $nuevafecha[1] . "/" . $nuevafecha[0];
        $inasitencia = [];
        if (Session::has('fecha_filter_1')) {
            $fecha1 = explode("/", Session::get('fecha_filter_1'));
            $fecha1 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];

            $fecha2 = explode("/", Session::get('fecha_filter_2'));
            $fecha2 = $fecha2[2] . "-" . $fecha2[1] . "-" . $fecha2[0];

            $inasitencia = DB::Table('ins_gop_inasistencia as tbl1')
                ->select('tbl1.id_supervisor', 'tbl1.fecha', 'tbl1.movil', 'tecnico',
                    'tipo_tecnico', 'fecha_inasistencia', 'motivo',
                    'observacion', 'fecha_inasistencia2', 'turno',
                    'tbl2.nombres as nombreS', 'tbl2.apellidos as apellidoS',
                    'tbl3.nombres as nombreL', 'tbl3.apellidos as apellidoL', 'tbl3.identificacion as cedula',
                    DB::raw("(
                                CASE
                                      WHEN tbl1.pre = 'rds' THEN (select top 1 o.nombre from rds_gop_cuadrilla as c left join rds_gop_tipo_orden as o on (c.id_tipo_orden=o.id_tipo) where c.id_movil=  tbl1.movil )
                                      ELSE '--- '
                                    END
                               ) as tipo_operacion,
                               
                                 (  CASE
                                        WHEN tbl1.pre = 'rds'
                                          THEN (SELECT TOP 1 s.vehiculo
                                                FROM rds_gop_supervisor s
                                                WHERE s.movil = tbl1.movil and s.id_supervisor = tbl1.id_supervisor and s.fecha = tbl1.fecha_inasistencia)
                                        WHEN tbl1.pre = 'apu'
                                          THEN (SELECT TOP 1 c.matricula
                                                FROM apu_gop_cuadrilla c
                                                WHERE c.id_movil = tbl1.movil)
                                        WHEN tbl1.pre = 'epm'
                                          THEN (SELECT TOP 1 c.matricula
                                                FROM epm_gop_cuadrilla c
                                                WHERE c.id_movil = tbl1.movil)
                                        WHEN tbl1.pre = 'ce8'
                                          THEN (SELECT TOP 1 c.matricula
                                                FROM ce8_gop_cuadrilla c
                                                WHERE c.id_movil = tbl1.movil)
                                        WHEN tbl1.pre = 'emc'
                                          THEN (SELECT TOP 1 c.matricula
                                                FROM emc_gop_cuadrilla c
                                                WHERE c.id_movil = tbl1.movil)
                                        ELSE '---'
                                        END  ) as vehiculo                               
                               
                               ")

                )
                ->join('rh_personas as tbl2', 'tbl2.identificacion', '=', 'tbl1.id_supervisor')
                ->join('rh_personas as tbl3', 'tbl3.identificacion', '=', 'tbl1.tecnico')
                /*   ->leftJoin('rds_gop_supervisor as tbl7' ,function($join)
                {
                    $join->on('tbl1.id_supervisor','=','tbl7.id_supervisor')
                        ->on('tbl1.movil','=','tbl7.movil')
                        ->on('tbl1.fecha_inasistencia','=','tbl7.fecha');

                })*/
                ->whereBetween('fecha_inasistencia', [$fecha1, $fecha2])
                ->where('tbl1.movil', 'LIKE', '%' . Session::get('filter_movil_ina') . '%')
                ->where('tecnico', 'LIKE', '%' . Session::get('filter_cuadrillero_ina') . '%')
                ->where('tbl1.id_supervisor', 'LIKE', '%' . Session::get('filter_super_ina') . '%');

            if (Session::get('estado_filter_ina') != -1) {
                $inasitencia = $inasitencia->where('motivo', Session::get('estado_filter_ina'));
            }

            if (Session::get('filter_turno_ina') != -1) {
                $inasitencia = $inasitencia->where('turno', Session::get('filter_turno_ina'));
            }

            if (Session::get('filter-proyecto-inasistencia') != -1) {
                $inasitencia = $inasitencia->where('pre', Session::get('filter-proyecto-inasistencia'));
            }
            $inasitencia = $inasitencia->get();
        }


        $consulta = "
          SELECT
            gop_proyectos.nombre AS proyecto,
            gop_proyectos.prefijo_db,
            (
                select top(1) tbl1.id_proyecto
                FROM gop_proyectos as tbl1
                WHERE tbl1.prefijo_db = gop_proyectos.prefijo_db
            ) as id_proyecto
            FROM sis_usuarios
            INNER JOIN sis_usuarios_proyectos AS conf_proy
                ON (conf_proy.id_usuario = sis_usuarios.id_usuario)
            INNER JOIN gop_proyectos
                ON (gop_proyectos.id_proyecto = conf_proy.id_proyecto AND gop_proyectos.prefijo_db != '')
            WHERE sis_usuarios.cuenta = 'CO8778565' 
            GROUP BY gop_proyectos.nombre, gop_proyectos.prefijo_db
            ORDER BY gop_proyectos.nombre
                ";

        $pry = DB::select($consulta);

        return view("proyectos.supervisor.indexausentismo", array(
            "fecha1" => $nuevafecha,
            "fecha2" => $fechaActual,
            "inasistencia" => $inasitencia,
            "proyectos" => $pry
        ));
    }

    //Filtro para mostrar la pantalla principal de las inasistencias
    public function filterindexInasistencia(Request $request)
    {
        Session::flash('fecha_filter_1', $request->all()["fecha_filter_1"]);
        Session::flash('fecha_filter_2', $request->all()["fecha_filter_2"]);
        Session::flash('estado_filter_ina', $request->all()["estado_filter_ina"]);
        Session::flash('filter_turno_ina', $request->all()["filter_turno_ina"]);
        Session::flash('filter_movil_ina', $request->all()["filter_movil_ina"]);
        Session::flash('filter_cuadrillero_ina', $request->all()["filter_cuadrillero_ina"]);
        Session::flash('filter_super_ina', $request->all()["filter_super_ina"]);
        Session::flash('filter-proyecto-inasistencia', $request->all()["filter-proyecto-inasistencia"]);
        return Redirect::to('inasistencias');
    }

    /*************************************************/
    /******** FIN INASISTENCIAS *************************/


    /**************************************************/
    /*************** REPORTES *************************/

    /**
     * Función encargada de mostrar los reportes de las gráficas
     **/
    public function reportes()
    {
        if (!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $fechaActual = explode("-", $this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" . $fechaActual[1] . "/" . $fechaActual[0];

        $nuevafecha = strtotime('-1 month', strtotime($this->fechaShort));
        $nuevafecha = date('Y-m-j', $nuevafecha);

        $nuevafecha = explode("-", $nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" . $nuevafecha[1] . "/" . $nuevafecha[0];

        $reportInspenccio1 = [];
        $reportInspenccio2 = [];
        $reportInspenccio3 = [];
        $reportInspenccio4 = [];

        $reportInspenccio5 = [];
        $reportInspenccio6 = [];

        $reportInspenccio7 = [];
        $reportInspenccio8 = [];

        if (Session::has('fecha_inicio1')) {
            if (Session::get('fecha_inicio1') != "" && Session::get('fecha_corte1') != "") {
                $fecha1 = explode("/", Session::get('fecha_inicio1'));
                $fecha1 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];

                $fecha2 = explode("/", Session::get('fecha_corte1'));
                $fecha2 = $fecha2[2] . "-" . $fecha2[1] . "-" . $fecha2[0];


                //ESTADO DE INSPECCION
                $estado1 = DB::Table('ins_inspeccion')
                    ->where('estado', 'E0')
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                $estado2 = DB::Table('ins_inspeccion')
                    ->where('estado', 'E1')
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                $estado3 = DB::Table('ins_inspeccion')
                    ->where('estado', 'E2')
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                $estado4 = DB::Table('ins_inspeccion')
                    ->where('estado', 'A1')
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                array_push($reportInspenccio1, $estado1, $estado2, $estado3, $estado4);

                //Inspección por resultados
                $estado1 = DB::Table('ins_inspeccion')
                    ->where('estado', '<>', 'A1')
                    ->where('resultado', 'C')
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                $estado2 = DB::Table('ins_inspeccion')
                    ->where('resultado', 'NC')
                    ->where('estado', '<>', 'A1')
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                array_push($reportInspenccio2, $estado1, $estado2);


                //Proyectos Confirmes/No Conformes
                $estado1 = DB::Table('ins_inspeccion')
                    ->where('resultado', 'C')
                    ->where('estado', '<>', 'A1')
                    ->where('prefijo', Session::get('proyecto1'))
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                $estado2 = DB::Table('ins_inspeccion')
                    ->where('resultado', 'NC')
                    ->where('estado', '<>', 'A1')
                    ->where('prefijo', Session::get('proyecto1'))
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                array_push($reportInspenccio3, $estado1, $estado2);

                //Inasistencias por proyecto individual
                $estado1 = DB::Table('ins_gop_inasistencia')//Asistio
                ->where('motivo', '0')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->join(Session::get('proyecto1') . '_gop_cuadrilla', 'id_lider', '=', 'tecnico')
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();

                $estado2 = DB::Table('ins_gop_inasistencia')//No asistio
                ->where('motivo', '1')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->join(Session::get('proyecto1') . '_gop_cuadrilla', 'id_lider', '=', 'tecnico')
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();

                $estado3 = DB::Table('ins_gop_inasistencia')//Incapacitado
                ->where('motivo', '2')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->join(Session::get('proyecto1') . '_gop_cuadrilla', 'id_lider', '=', 'tecnico')
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();


                $estado4 = DB::Table('ins_gop_inasistencia')//Inhabilitado
                ->where('motivo', '4')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->join(Session::get('proyecto1') . '_gop_cuadrilla', 'id_lider', '=', 'tecnico')
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();

                $estado5 = DB::Table('ins_gop_inasistencia')//Camio de turno
                ->where('motivo', '5')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->join(Session::get('proyecto1') . '_gop_cuadrilla', 'id_lider', '=', 'tecnico')
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();

                array_push($reportInspenccio4, $estado1, $estado2, $estado3, $estado4, $estado5);


                //ESTADO DE INSPECCION por Proyecto
                $estado1 = DB::Table('ins_inspeccion')
                    ->where('estado', 'E0')
                    ->where('prefijo', Session::get('proyecto1'))
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                $estado2 = DB::Table('ins_inspeccion')
                    ->where('estado', 'E1')
                    ->where('prefijo', Session::get('proyecto1'))
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                $estado3 = DB::Table('ins_inspeccion')
                    ->where('estado', 'E2')
                    ->where('prefijo', Session::get('proyecto1'))
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                $estado4 = DB::Table('ins_inspeccion')
                    ->where('estado', 'A1')
                    ->where('prefijo', Session::get('proyecto1'))
                    ->whereBetween('fecha_servidor', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(estado) as con'))
                    ->get();

                array_push($reportInspenccio5, $estado1, $estado2, $estado3, $estado4);


                //Inasistencias por proyecto individual
                $estado1 = DB::Table('ins_gop_inasistencia')//Asistio
                ->where('motivo', '0')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();

                $estado2 = DB::Table('ins_gop_inasistencia')//No asistio
                ->where('motivo', '1')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();

                $estado3 = DB::Table('ins_gop_inasistencia')//Incapacitado
                ->where('motivo', '2')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();


                $estado4 = DB::Table('ins_gop_inasistencia')//Inhabilitado
                ->where('motivo', '4')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();

                $estado5 = DB::Table('ins_gop_inasistencia')//Camio de turno
                ->where('motivo', '5')
                    ->whereBetween('ins_gop_inasistencia.fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:59"])
                    ->select(DB::raw('COUNT(motivo) as con'))
                    ->get();

                array_push($reportInspenccio6, $estado1, $estado2, $estado3, $estado4, $estado5);


                $consulta = "SELECT count(formcrea.descrip_pregunta) as cantPregunta,formcrea.descrip_pregunta as des
                FROM
                gop_formularios_creacion as formcrea 
                INNER JOIN  ins_inspeccion_detalle as ins ON formcrea.version = ins.version
                    AND ins.id_pregunta = formcrea.id_pregunta
                INNER JOIN ins_inspeccion as cabeza ON cabeza.id_inspeccion = ins.id_inspeccion
                WHERE formcrea.tipo_formulario = 1
                    AND ins.respuesta = '1'
                    AND cabeza.fecha_servidor BETWEEN '" . $fecha1 . " 00:00:00' AND '" . $fecha2 . " 23:59:59'
                GROUP BY formcrea.descrip_pregunta";

                $ins = DB::select($consulta);

                array_push($reportInspenccio7, $ins);

                $consulta = "SELECT count(formcrea.descrip_pregunta) as cantPregunta,formcrea.descrip_pregunta as des
                FROM
                gop_formularios_creacion as formcrea 
                INNER JOIN  ins_inspeccion_detalle as ins ON formcrea.version = ins.version
                    AND ins.id_pregunta = formcrea.id_pregunta
                INNER JOIN ins_inspeccion as cabeza ON cabeza.id_inspeccion = ins.id_inspeccion
                WHERE formcrea.tipo_formulario = 1
                    AND ins.respuesta = '1'
                    AND cabeza.prefijo = '" . Session::get('proyecto1') . "'
                    AND cabeza.fecha_servidor BETWEEN '" . $fecha1 . " 00:00:00' AND '" . $fecha2 . " 23:59:59'
                GROUP BY formcrea.descrip_pregunta";

                $ins1 = DB::select($consulta);

                array_push($reportInspenccio8, $ins1);


            }
        }

        $consulta = "
                  SELECT
                    gop_proyectos.nombre AS proyecto,
                    gop_proyectos.prefijo_db
                  FROM sis_usuarios
                    INNER JOIN sis_usuarios_proyectos AS conf_proy
                      ON (conf_proy.id_usuario = sis_usuarios.id_usuario)
                    INNER JOIN gop_proyectos
                      ON (gop_proyectos.id_proyecto = conf_proy.id_proyecto AND gop_proyectos.prefijo_db != '')
                  WHERE sis_usuarios.cuenta = 'CO8778565' GROUP BY gop_proyectos.nombre, gop_proyectos.prefijo_db
                        ";
        $proyecto = DB::select($consulta);

        return view('proyectos.supervisor.indexreportes',
            array(
                'fecha1' => $fechaActual, 'fecha2' => $nuevafecha,
                'proyecto' => $proyecto,
                'reporte1' => $reportInspenccio1,
                'reporte2' => $reportInspenccio2,
                'reporte3' => $reportInspenccio3,
                'reporte4' => $reportInspenccio4,
                'reporte5' => $reportInspenccio5,
                'reporte6' => $reportInspenccio6,
                'reporte7' => $reportInspenccio7,
                'reporte8' => $reportInspenccio8,
            ));
    }

    /**
     * Filtro mostrar los reportes de las gráficas
     **/
    public function reportesconsulta(Request $request)
    {
        Session::flash('fecha_inicio1', $request->all()["fecha_inicio"]);
        Session::flash('fecha_corte1', $request->all()["fecha_corte"]);
        Session::flash('proyecto1', $request->all()["proyecto"]);
        return Redirect::to('inspeccionOrdenesReportes');
    }


    //Función encargada de mostrar la pantalla de la cobertura
    public function cobertura()
    {

        if (!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $fechaActual = explode("-", $this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" . $fechaActual[1] . "/" . $fechaActual[0];

        $nuevafecha = strtotime('-1 month', strtotime($this->fechaShort));
        $nuevafecha = date('Y-m-j', $nuevafecha);

        $nuevafecha = explode("-", $nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" . $nuevafecha[1] . "/" . $nuevafecha[0];

        $datos = [];

        $consulta = "
          SELECT
            gop_proyectos.nombre AS proyecto,
            gop_proyectos.prefijo_db,
            (
                select top(1) tbl1.id_proyecto
                FROM gop_proyectos as tbl1
                WHERE tbl1.prefijo_db = gop_proyectos.prefijo_db
            ) as id_proyecto
            FROM sis_usuarios
            INNER JOIN sis_usuarios_proyectos AS conf_proy
                ON (conf_proy.id_usuario = sis_usuarios.id_usuario)
            INNER JOIN gop_proyectos
                ON (gop_proyectos.id_proyecto = conf_proy.id_proyecto AND gop_proyectos.prefijo_db != '')
            WHERE sis_usuarios.cuenta = 'CO8778565' 
            GROUP BY gop_proyectos.nombre, gop_proyectos.prefijo_db
            ORDER BY gop_proyectos.nombre
                ";

        $pry = DB::select($consulta);

        if (Session::has('proyecto_cobertura')) {
            $proyecto = Session::get('proyecto_cobertura');
            $fecha1 = explode("/", Session::get('fecha_ini_cobertura'));
            $fecha2 = explode("/", Session::get('fecha_fin_cobertura'));

            $fecha1 = $fecha1[2] . "-" . $fecha1[1] . "-" . $fecha1[0];
            $fecha2 = $fecha2[2] . "-" . $fecha2[1] . "-" . $fecha2[0];

            $cad = "EXEC sp_gop_reporte_cobertura '$proyecto','$fecha1','$fecha2'";
            $datos = \DB::select("SET NOCOUNT ON;" . $cad);
        }

        return view("proyectos.supervisor.indexCobertura", array(
            "fecha1" => $fechaActual,
            "fecha2" => $nuevafecha,
            "datos" => $datos,
            "proyectos" => $pry
        ));
    }

    /**
     * Función encargada de manejar los filtros de Cobertura
     **/
    public function filterCobertura(Request $request)
    {
        Session::flash('proyecto_cobertura', $request->all()["id_proyecto"]);
        Session::flash('fecha_ini_cobertura', $request->all()["fecha_inicio"]);
        Session::flash('fecha_fin_cobertura', $request->all()["fecha_corte"]);
        return Redirect::to('transversal/reporte/cobertura');
    }

    /*************** FIN REPORTES **********************/
    /**************************************************/


    //******************************************************
    //-----------------    GENERACIÓN EXCEL IPAL
    //******************************************************
    public function generaExcelConsolidado(Request $request)
    {
        $pry = $request->all()["id_proyecto"];
        $fecha1 = $request->all()["fecha_inicio"];
        $fecha_inicio = explode("/", $fecha1)[2] . "-" . explode("/", $fecha1)[1] . "-" . explode("/", $fecha1)[0];
        $fecha_corte = explode("/", $request->all()["fecha_corte"])[2] . "-" . explode("/", $request->all()["fecha_corte"])[1] . "-" . explode("/", $request->all()["fecha_corte"])[0];
        $tipo_inspeccion = $request->all()["tipo_insp"];
        $resultado = $request->all()["resultado"];
        $super = $request->all()["super"];
        $id_estado = $request->all()["id_estado"];


        $cad = "EXEC sp_gop_consulta_reporte_ipales '$pry','$fecha_inicio','$fecha_corte','$tipo_inspeccion','$resultado','$super','$id_estado'";
        $ipales = \DB::select("SET NOCOUNT ON;" . $cad);


        // echo "<pre>";
        // print_r($cad); echo "\n";
        // echo "</pre>";
        // dd($ipales);

        if(count($ipales) == 0){
            echo "<pre>";
            print_r($this->fechaALong. " Intenta nuevamente !"); echo "\n";
            print_r($cad); echo "\n";
            echo "</pre>";die();
        }else{
            echo "<h1>SI INRGESA: ". count($ipales) ."</h1>";
            echo "<pre>";
            print_r( " Intenta nuevamente !"); echo "\n";
            print_r($cad); echo "\n";
            echo "</pre>";die();
        }





        \Excel::create('IPALES ' . $this->fechaALong, function ($excel) use ($ipales) {
            $excel->sheet('Ipal', function ($sheet) use ($ipales) {
                $titulos = ["N°", "CANTIDAD", "NUMERO IPAL", "TIPO", "FECHA INSPECCIÓN", "HORA INSPECCIÓN", "MES", "SEDE", "CECO", "UNIDAD DE NEGOCIO", "DIVISION",
                    "PROYECTO", "CEDULA DE JEFE DE CUADRILLA", "JEFE DE CUADRILLA ", "CEDULA DE AUXILIAR DE CUADRILLA", "AUXILIAR DE CUADRILLA "
                    , "ACTIVIDAD ESPECÍFICA INSPECCIONADA", "AREA OBSERVADA", "ACTUACIONES INCUMPLIDAS", "RESULTADO DE LA INSPECCION"
                    , " IPAL ", "ACTO CONDICIÓN", "CAUSA DEL INCUMPLIMIENTO", "CEDULA REALIZADOR IPAL", "NOMBRE REALIZADOR IPAL"
                    , "TIPO DE INSPECCION", "DESCRIPCION DEL HALLAZGO", "DESCRIPCION DE LA ACCION CORRECTIVA", "CC RESPONSABLE DE LA ACCION CORRECTIVA"
                    , "NOMBRE RESPONSABLE ACCION CORRECTIVA", "FECHA OBJETIVA DE CIERRE", "FECHA REAL DEL CIERRE"
                    , "CEDULA SUPERVISOR DE CUADRILLA", "NOMBRE SUPERVISOR DE CUADRILLA", "REALIZO GRABACION CHARLA OPERATIVA"
                    , "EVALUACION GRABACION CHARLA OPERATIVA", "ESTADO", "DIRECCIÓN", "LATITUD", "LONGITUD", "EDAD COLABORADOR", "EXPERIENCIA EN EL CARGO", "PERIODO EN LA COMPAÑIA"];
                $sheet->fromArray($titulos);

                $fila = 1;
                $cont = 1;

                for ($i = 0; $i < count($ipales); $i++) {
                    $cont = 1;
                    //Consulta Preguntas negativas
                    $consulta = "
                            select 
                              [tbl3].[tipo_formulario],
                              [tbl1].[id_pregunta], 
                              [tbl1].[respuesta], 
                              [tbl2].[descrip_pregunta],
                              [tbl3].[descrip_pregunta] as padre ,
                              [tbl2].[nombre_corto],
                              acto_condicion,
                              tbl2.item_num,
                              tbl3.item_num as item_num_padre,
                              (
                                  SELECT TOP(1) causa.analisis
                                  FROM ins_analisis_causas as causa
                                  WHERE causa.id_inspeccion = '" . $ipales[$i]->id_inspeccion . "'
                                      AND causa.id_item = tbl2.id_pregunta
                              ) as causa,
                              (
                                  SELECT TOP(1) planA.accion
                                  FROM ins_plan_accion as planA
                                  WHERE planA.id_inspeccion = '" . $ipales[$i]->id_inspeccion . "'
                                      AND planA.item = tbl2.id_pregunta
                              ) as planAccion

                             FROM  [ins_inspeccion_detalle] as [tbl1] 
                             
                              INNER JOIN [gop_formularios_creacion] AS [tbl2] 
                                ON [tbl1].[id_formulario] = [tbl2].[tipo_formulario] 
                                AND [tbl1].[id_pregunta] =   tbl2.id_pregunta
                                AND [tbl2].[tipo_formulario] IN(1, 33, 34, 35, 43, 44, 46, 36)
                                AND [tbl1].[version] = [tbl2].[version] 
                            
                              INNER JOIN [gop_formularios_creacion] AS tbl3 
                                ON tbl3.id_pregunta = [tbl2].[id_padre] 
                                AND [tbl3].[tipo_formulario] IN(1, 33, 34, 35, 43, 44, 46, 36)
                                AND [tbl1].[version] = [tbl3].[version]
                                AND [tbl2].[tipo_formulario] = [tbl3].[tipo_formulario]

                            WHERE [tbl1].[id_inspeccion] = '" . $ipales[$i]->id_inspeccion . "' 
                              AND [tbl2].[tipo_formulario] IN(1, 33, 34, 35, 43, 44, 46, 36) 
                              AND [tbl1].[respuesta] = '1' 
                              AND [tbl3].[descrip_pregunta] NOT IN('DESCRIPCION')

                            GROUP BY 
                              [tbl3].[tipo_formulario],
                              [tbl1].[id_pregunta], 
                              [tbl1].[respuesta], 
                              [tbl2].[descrip_pregunta],
                              [tbl3].[descrip_pregunta],
                              [tbl2].[nombre_corto],
                              acto_condicion,
                              tbl2.item_num,
                              tbl3.item_num,
                              tbl2.id_pregunta

                            ORDER BY [tbl1].[id_pregunta] ASC;";

                    $chalaRealiza = "NO";
                    if ($ipales[$i]->charla_calificacion != null && $ipales[$i]->charla_calificacion != "")
                        $chalaRealiza = "SI";

                    if ($ipales[$i]->resultado == "C")
                        $ipalRes = null;
                    else
                        $ipalRes = DB::select($consulta);

                    if ($ipalRes != null) {
                        foreach ($ipalRes as $key => $value) {
                            $nombre_corto = utf8_encode($value->nombre_corto);
                            $id_tipo_formulario = $value->tipo_formulario;

                            if (in_array($id_tipo_formulario, array(33, 34, 35))) {
                                $nombre_corto = ((int)$value->nombre_corto) * 10;
                            }

                            $sheet->row($fila + 1, array(
                                $fila,
                                $cont,
                                utf8_encode($ipales[$i]->id_inspeccion),
                                $ipales[$i]->tipo_ingreso_inspeccion,
                                explode(" ", $ipales[$i]->fecha_ipal)[0],
                                explode(" ", $ipales[$i]->fecha_ipal)[1],
                                $ipales[$i]->mes,
                                $ipales[$i]->proyecto,
                                $ipales[$i]->ccosto,
                                $ipales[$i]->unidad_negocio,
                                $ipales[$i]->division,
                                $ipales[$i]->proyecto,
                                $ipales[$i]->lider,
                                $ipales[$i]->nombreL,
                                $ipales[$i]->aux1,
                                utf8_encoñde($ipales[$i]->nombreA),
                                $ipales[$i]->tipoOrden,
                                $value->item_num_padre . "   " . $value->padre,
                                $value->item_num . "   " . $value->descrip_pregunta,
                                $ipales[$i]->resultado,
                                $nombre_corto,
                                $value->acto_condicion,
                                $value->causa,
                                $ipales[$i]->supervisor,
                                $ipales[$i]->nombreS,
                                $ipales[$i]->tipoIns,
                                utf8_encode($ipales[$i]->observacion),
                                $value->planAccion,
                                utf8_encode($ipales[$i]->descripcion_accion_correctiva_cedula),
                                utf8_encode($ipales[$i]->descripcion_accion_correctiva_responsable),
                                $ipales[$i]->fecha_objetivo_cierre,
                                $ipales[$i]->fecha_cierre,
                                $ipales[$i]->supervisor_cuadrilla,
                                $ipales[$i]->supervisor_cuadrilla_name,
                                $chalaRealiza,
                                $ipales[$i]->charla_calificacion,
                                $ipales[$i]->estado,
                                utf8_encode($ipales[$i]->direccion),
                                $ipales[$i]->latitud,
                                $ipales[$i]->longitud,
                                $ipales[$i]->edad,
                                "",
                                ""
                            ));
                            $fila++;
                            $cont++;
                            if ($ipales[$i]->resultado == "C")
                                break;
                        }
                    } else {

                        $sheet->row($fila + 1, array(
                            $fila, $cont,
                            utf8_encode($ipales[$i]->id_inspeccion),
                            $ipales[$i]->tipo_ingreso_inspeccion,
                            explode(" ", $ipales[$i]->fecha_ipal)[0],
                            explode(" ", $ipales[$i]->fecha_ipal)[1],
                            $ipales[$i]->mes,
                            utf8_encode($ipales[$i]->proyecto),
                            $ipales[$i]->ccosto,
                            $ipales[$i]->unidad_negocio,
                            $ipales[$i]->division,
                            $ipales[$i]->proyecto,
                            $ipales[$i]->lider,
                            utf8_encode($ipales[$i]->nombreL),
                            $ipales[$i]->aux1,
                            utf8_encode($ipales[$i]->nombreA),
                            $ipales[$i]->tipoOrden,
                            "",
                            "",
                            $ipales[$i]->resultado,
                            "",
                            "",
                            $ipales[$i]->causa_incumplimiento,
                            $ipales[$i]->supervisor,
                            $ipales[$i]->nombreS,
                            $ipales[$i]->tipoIns,
                            utf8_encode($ipales[$i]->observacion),
                            utf8_encode($ipales[$i]->descripcion_accion_correctiva),
                            "",
                            utf8_encode($ipales[$i]->descripcion_accion_correctiva_responsable),
                            $ipales[$i]->fecha_objetivo_cierre,
                            $ipales[$i]->fecha_cierre,
                            $ipales[$i]->supervisor_cuadrilla,
                            $ipales[$i]->supervisor_cuadrilla_name,
                            $chalaRealiza,
                            $ipales[$i]->charla_calificacion,
                            $ipales[$i]->estado,
                            utf8_encode($ipales[$i]->direccion),
                            $ipales[$i]->latitud,
                            $ipales[$i]->longitud,
                            $ipales[$i]->edad,
                            "",
                            ""
                        ));
                        $fila++;
                        $cont++;
                    }

                }
            });
        })->export('xls');
    }

    //******************************************************
    //-----------------FIN     GENERACIÓN EXCEL IPAL
    //******************************************************


    /********************************************************************/
    /******* WEB SERVICE SERVER ENVIO DE NOTIFICACIONES *****************/
    /********************************************************************/

    public function envioNoti(Request $request)
    {
        $origen = $request->all()["id_origen"];
        $observaciones = $request->all()["observaciones"];
        $fecha = $request->all()["fecha"];
        $id_orden = $request->all()["id_orden"];
        $tipo_evento = $request->all()["tipo_evento"];
        $hora = $request->all()["hora"];
        $id = $request->all()["id"];

        $dbSuper = DB::Table('apu_gop_cuadrilla')
            ->where('id_lider', $origen)
            ->value('id_supervisor');

        if ($dbSuper != "" && $dbSuper != null) {
            $tokenUser = DB::Table('ins_token_movil')
                ->where('id_supervidor', $dbSuper)
                ->value('token_movil');

            if ($tokenUser != "" && $tokenUser != null) {
                $liderNombre = DB::Table('rh_personas')
                    ->where('identificacion', $origen)
                    ->select('nombres', 'apellidos')
                    ->get()[0];

                $nom = $liderNombre->nombres . " " . $liderNombre->apellidos;

                $title = "Reporte de eventos";
                $body = "El líder " . $nom . " a generado un evento tipo " . strtoupper($tipo_evento) . ", Observación: " . $observaciones;

                if ($tipo_evento == "panico")
                    $sonido = "tono.mp3";

                if ($tipo_evento == "autoinspeccion")
                    $sonido = "tono.mp3";

                if ($tipo_evento == "preipo")
                    $sonido = "tono.mp3";

                if ($tipo_evento == "PARE")
                    $sonido = "tono.mp3";

                $icon = "fcm_push_icon";
                $otro = "";
                $token = $tokenUser;
                $super = $dbSuper;
                $res = self::enviaNotificacion($title, $body, $sonido, $icon, $token, $otro, $super);

                //Actualiza SSL
                DB::Table('ssl_eventos')
                    ->where('id', $id)
                    ->update(
                        array(
                            'notificacion' => 1
                        )
                    );

                return response()->json(array("res" => $res));
            } else
                return response()->json(array("res" => -1));
        } else
            return response()->json(array("res" => -2));
    }

    private function enviaNotificacion($titulo, $cuerpo, $sound, $icono, $para, $otroD, $superviso)
    {
        // https://firebase.google.com/docs/cloud-messaging/http-server-ref#downstream-http-messages-json
        // https://github.com/fechanique/cordova-plugin-fcm
        try {
            $Headers = array(
                'Authorization: key=AAAAatrk_W8:APA91bH2c3TuBj1b2C_nloKLWA24YVo5vspdl-etHANW0vondl3fSVP8WcC9_4xcCX9poRC9YK9T1Ki5cuoJeQ2rhO0N1ftzArrPZoYp0SbiU-aRnfGLVd2wtPZp775GPg390BcTJKpw',
                "Content-type: application/json"
            );

            $url = "https://fcm.googleapis.com/fcm/send";
            //Consultando un grupo

            $title = $titulo;
            $body = $cuerpo;
            $sonido = $sound;
            $icon = $icono;
            $to = $para;
            $otro = $otroD;
            $super = $superviso;

            $data = array(
                'to' => $to,
                "notification" => array(
                    "title" => $title,
                    "body" => $body,
                    "sound" => $sonido,
                    "icon" => $icon,
                    "color" => "#084A9E"
                ),
                "data" => array(
                    "title" => $title,
                    "body" => $body,
                    "otro" => $otro,
                )
            );

            $data = json_encode($data);

            //Inicia conexion.
            $conexion = curl_init();
            //Indica un error de conexion
            if (FALSE === $conexion)
                throw new \Exception('failed to initialize');

            //Dirección URL a capturar.
            curl_setopt($conexion, CURLOPT_URL, $url);
            //indicando que es post
            curl_setopt($conexion, CURLOPT_POST, 1);
            //para incluir el header en el output.
            curl_setopt($conexion, CURLOPT_HEADER, 0);//No muestra la cabecera en el resultado
            //Envía la cabecera
            curl_setopt($conexion, CURLOPT_HTTPHEADER, $Headers);
            //para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
            curl_setopt($conexion, CURLOPT_RETURNTRANSFER, TRUE);
            //Seteando los datos del webServicecs
            curl_setopt($conexion, CURLOPT_POSTFIELDS, $data);
            //Para que no genere problemas con el certificado SSL
            curl_setopt($conexion, CURLOPT_SSL_VERIFYPEER, false);
            $resultado = curl_exec($conexion);

            if (FALSE === $resultado)
                throw new \Exception(curl_error($conexion), curl_errno($conexion));

            curl_close($conexion);
            /*$resultado = json_decode($resultado);
                DB::table('ins_notificaciones_envio')
                ->insert(array(
                    array(
                        'supervisor' => $super,
                        'token_enviado' => $to,
                        'titulo' => $title,
                        'cuerpo' => $body,
                        'sonido' => $sonido,
                        'icon' => $icon,
                        'respuesta' => $resultado->success,
                        'error' => $resultado->failure,
                        'enviado' => $resultado->success,
                        'fecha_envio' => $this->fechaALong,
                        'data1Title' => $title,
                        'data2Body' => $body,
                        'data3Otro' => $otro,
                        )
                    ));*/

            return "1";

        } catch (\Exception $e) {
            return "0";
            //var_dump($e->getMessage());
            /*trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);*/

        }
    }

    /********************************************************************/
    /******* FIN WEB SERVICE SERVER ENVIO DE NOTIFICACIONES *************/
    /********************************************************************/


    /********************************************************************/
    /*************** WEB SERVICES APLICACIÓN MÓVIL **********************/
    /********************************************************************/

    //Web services para guardar la información de la Aplicación Supervisión
    public function saveWebServicesSupervision(Request $request)
    {
        $tipo = $request->all()["tip"];
        $pre = "";
        if (isset($request->all()["pre"]))
            $pre = $request->all()["pre"];

        $retorno = "0";

        if ($tipo == "1") //Inserta información supervisión
        {
            $super = DB::Table($pre . '_gop_supervisor')
                ->where('id_supervisor', $request->all()["super"])
                ->where('fecha_creacion', $request->all()["fecha"])
                ->where('movil', $request->all()["movil"])
                ->select('lider')
                ->get();

            $aux1 = "";
            $aux2 = "";
            $aux3 = "";
            $aux4 = "";
            $aux5 = "";
            $aux6 = "";
            $conductor = "";
            $matri = "";

            if (isset($request->all()["aux1"]))
                $aux1 = $request->all()["aux1"];

            if (isset($request->all()["aux2"]))
                $aux2 = $request->all()["aux2"];

            if (isset($request->all()["aux3"]))
                $aux3 = $request->all()["aux3"];

            if (isset($request->all()["aux4"]))
                $aux4 = $request->all()["aux4"];

            if (isset($request->all()["aux5"]))
                $aux5 = $request->all()["aux5"];

            if (isset($request->all()["aux6"]))
                $aux6 = $request->all()["aux6"];


            if (isset($request->all()["conductor"]))
                $conductor = $request->all()["conductor"];

            if (isset($request->all()["matri"]))
                $matri = $request->all()["matri"];


            if ($pre == "rds") // TODO: COLOCAR PREFIJO APU O CEDULA SUPERVISOR
            {
                if (count($super) == 0)//Inserta
                {
                    DB::table($pre . '_gop_supervisor')
                        ->insert(array(
                            array(
                                'id_supervisor' => $request->all()["super"],
                                'fecha' => $this->fechaALong,
                                'movil' => $request->all()["movil"],
                                'g_tecnico' => $request->all()["tipo_cu"],
                                'lider' => $request->all()["lider"],
                                'aux1' => $aux1,
                                'aux2' => $aux2,
                                'aux3' => $aux3,

                                'aux4' => $aux4,
                                'aux5' => $aux5,
                                'aux6' => $aux6,


                                'conductor' => $conductor,
                                'vehiculo' => $matri,
                                'com' => $request->all()["com"],
                                'estado' => $request->all()["estado"],
                                'hri' => $request->all()["hIni"],
                                'hre' => $request->all()["hFin"],
                                'fecha_creacion' => $request->all()["fecha"],
                                'id_turno' => $request->all()["turno"],
                                'app_sub' => 1,
                                'fecha_ultimo_uso' => $this->fechaALong,
                                'causa_cancelacionn' => '',
                                'fecha_actualizacion_supervisor' => date('Y-m-d H:i:s')
                            )
                        ));
                } else //Update
                {
                    DB::table($pre . '_gop_supervisor')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_creacion', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->update(array(
                            'estado' => $request->all()["estado"],
                            'g_tecnico' => $request->all()["tipo_cu"],
                            'hri' => $request->all()["hIni"],
                            'hre' => $request->all()["hFin"],
                            'aux1' => $aux1,
                            'aux2' => $aux2,
                            'aux3' => $aux3,

                            'aux4' => $aux4,
                            'aux5' => $aux5,
                            'aux6' => $aux6,

                            'conductor' => $conductor,
                            'vehiculo' => $matri,
                            'app_sub' => 1,
                            'fecha_ultimo_uso' => $this->fechaALong,
                            'causa_cancelacionn' => '',
                            'fecha_actualizacion_supervisor' => date('Y-m-d H:i:s')
                        ));
                }


                //Carga información almacendada
                $cuadri = DB::table($pre . '_gop_cuadrilla')
                    ->where('id_movil', $request->all()["movil"])
                    ->select('id_movil', 'grupo_tecnico', 'id_supervisor', 'id_lider', 'id_aux1', 'id_aux2', 'id_aux3', 'id_aux4', 'id_aux5', 'id_aux6',
                        'id_conductor', 'matricula', 'fecha', 'id_estado', 'com', 'id_tipo_cuadrilla', 'clave_app', DB::raw("'' as id_kit"), DB::raw("'' as com_super"))
                    ->get()[0];

                //Inserta en el Log información
                DB::table($pre . '_gop_cuadrilla_log')
                    ->insert(array(
                        array(
                            'fecha' => $this->fechaALong,
                            'id_movil' => $cuadri->id_movil,
                            'grupo_tecnico' => $cuadri->grupo_tecnico,
                            'id_supervisor' => $cuadri->id_supervisor,
                            'id_lider' => $cuadri->id_lider,
                            'id_aux1' => $cuadri->id_aux1,
                            'id_aux2' => $cuadri->id_aux2,
                            'id_aux3' => $cuadri->id_aux3,

                            'id_aux4' => $cuadri->id_aux4,
                            'id_aux5' => $cuadri->id_aux5,
                            'id_aux6' => $cuadri->id_aux6,


                            'id_conductor' => $cuadri->id_conductor,
                            'matricula' => $cuadri->matricula,
                            'com' => $cuadri->com,
                            'id_tipo_cuadrilla' => $cuadri->id_tipo_cuadrilla
                        )));


                //Actualiza datos cuadrillas
                DB::table($pre . '_gop_cuadrilla')
                    ->where('id_movil', $request->all()["movil"])
                    ->update(array(
                        'fecha' => $this->fechaShort,
                        'grupo_tecnico' => $request->all()["tipo_cu"],
                        'id_supervisor' => $request->all()["super"],
                        'id_lider' => $request->all()["lider"],
                        'id_aux1' => ($aux1 == "0" ? "" : $aux1),
                        'id_aux2' => ($aux2 == "0" ? "" : $aux2),
                        'id_aux3' => ($aux3 == "0" ? "" : $aux3),

                        'id_aux4' => ($aux4 == "0" ? "" : $aux4),
                        'id_aux5' => ($aux5 == "0" ? "" : $aux5),
                        'id_aux6' => ($aux6 == "0" ? "" : $aux6),

                        'id_conductor' => ($conductor == "0" ? "" : $conductor),
                        'matricula' => ($matri == "0" ? "" : $matri),
                        'com' => $request->all()["com"]
                    ));


            } else {
                if ($pre == 'apu') {
                    if (count($super) == 0)//Inserta
                    {
                        DB::table($pre . '_gop_supervisor')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'g_tecnico' => $request->all()["tipo_cu"],
                                    'lider' => $request->all()["lider"],
                                    'aux1' => $aux1,
                                    'aux2' => $aux2,
                                    'aux3' => $aux3,
                                    'conductor' => $conductor,
                                    'vehiculo' => $matri,
                                    'com' => $request->all()["com"],
                                    'estado' => $request->all()["estado"],
                                    'hri' => $request->all()["hIni"],
                                    'hre' => $request->all()["hFin"],
                                    'fecha_creacion' => $request->all()["fecha"],
                                    'id_turno' => $request->all()["turno"],
                                    'app_sub' => 1,
                                    'fecha_ultimo_uso' => $this->fechaALong,
                                    'causa_cancelacionn' => '',
                                    'id_estado_m' => 'A'
                                )
                            ));
                    } else //Update
                    {
                        DB::table($pre . '_gop_supervisor')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_creacion', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->update(array(
                                'estado' => $request->all()["estado"],
                                'g_tecnico' => $request->all()["tipo_cu"],
                                'hri' => $request->all()["hIni"],
                                'hre' => $request->all()["hFin"],
                                'aux1' => $aux1,
                                'aux2' => $aux2,
                                'aux3' => $aux3,
                                'conductor' => $conductor,
                                'vehiculo' => $matri,
                                'app_sub' => 1,
                                'fecha_ultimo_uso' => $this->fechaALong,
                                'causa_cancelacionn' => '',
                                'id_estado_m' => 'A'
                            ));
                    }
                } else {
                    if (count($super) == 0)//Inserta
                    {
                        DB::table($pre . '_gop_supervisor')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'g_tecnico' => $request->all()["tipo_cu"],
                                    'lider' => $request->all()["lider"],
                                    'aux1' => $aux1,
                                    'aux2' => $aux2,
                                    'aux3' => $aux3,
                                    'conductor' => $conductor,
                                    'vehiculo' => $matri,
                                    'com' => $request->all()["com"],
                                    'estado' => $request->all()["estado"],
                                    'hri' => $request->all()["hIni"],
                                    'hre' => $request->all()["hFin"],
                                    'fecha_creacion' => $request->all()["fecha"],
                                    'id_turno' => $request->all()["turno"],
                                    'app_sub' => 1,
                                    'fecha_ultimo_uso' => $this->fechaALong
                                )
                            ));
                    } else //Update
                    {
                        DB::table($pre . '_gop_supervisor')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_creacion', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->update(array(
                                'estado' => $request->all()["estado"],
                                'hri' => $request->all()["hIni"],
                                'hre' => $request->all()["hFin"],
                                'aux1' => $aux1,
                                'aux2' => $aux2,
                                'aux3' => $aux3,
                                'conductor' => $conductor,
                                'vehiculo' => $matri,
                                'app_sub' => 1,
                                'fecha_ultimo_uso' => $this->fechaALong
                            ));
                    }
                }


                //Carga información almacendada
                $cuadri = DB::table($pre . '_gop_cuadrilla')
                    ->where('id_movil', $request->all()["movil"])
                    ->select('id_movil', 'grupo_tecnico', 'id_supervisor', 'id_lider', 'id_aux1', 'id_aux2', 'id_aux3',
                        'id_conductor', 'matricula', 'fecha', 'id_estado', 'com', 'id_tipo_cuadrilla', 'clave_app', DB::raw("'' as id_kit"), DB::raw("'' as com_super"))
                    ->get()[0];

                //Inserta en el Log información
                DB::table($pre . '_gop_cuadrilla_log')
                    ->insert(array(
                        array(
                            'fecha' => $this->fechaALong,
                            'id_movil' => $cuadri->id_movil,
                            'grupo_tecnico' => $cuadri->grupo_tecnico,
                            'id_supervisor' => $cuadri->id_supervisor,
                            'id_lider' => $cuadri->id_lider,
                            'id_aux1' => $cuadri->id_aux1,
                            'id_aux2' => $cuadri->id_aux2,
                            'id_aux3' => $cuadri->id_aux3,
                            'id_conductor' => $cuadri->id_conductor,
                            'matricula' => $cuadri->matricula,
                            'com' => $cuadri->com,
                            'id_tipo_cuadrilla' => $cuadri->id_tipo_cuadrilla
                        )));


                //Actualiza datos cuadrillas
                DB::table($pre . '_gop_cuadrilla')
                    ->where('id_movil', $request->all()["movil"])
                    ->update(array(
                        'fecha' => $this->fechaShort,
                        'grupo_tecnico' => $request->all()["tipo_cu"],
                        'id_supervisor' => $request->all()["super"],
                        'id_lider' => $request->all()["lider"],
                        'id_aux1' => ($aux1 == "0" ? "" : $aux1),
                        'id_aux2' => ($aux2 == "0" ? "" : $aux2),
                        'id_aux3' => ($aux3 == "0" ? "" : $aux3),
                        'id_conductor' => ($conductor == "0" ? "" : $conductor),
                        'matricula' => ($matri == "0" ? "" : $matri),
                        'com' => $request->all()["com"]
                    ));


            }


            //Asistencia de líder
            $tec_ina = DB::Table('ins_gop_inasistencia')
                ->where('id_supervisor', $request->all()["super"])
                ->where('fecha_inasistencia', $request->all()["fecha"])
                ->where('movil', $request->all()["movil"])
                ->where('tecnico', $request->all()["lider"])
                ->count();

            //Guarda Asistencia de líderes que si vinieron
            if ($tec_ina == 0)//Inserta
            {
                DB::table('ins_gop_inasistencia')
                    ->insert(array(
                        array(
                            'id_supervisor' => $request->all()["super"],
                            'fecha' => $this->fechaALong,
                            'movil' => $request->all()["movil"],
                            'tecnico' => $request->all()["lider"],
                            'tipo_tecnico' => 0, // 0-> Líder , 1-> Auxiliar, 2->Conductor
                            'fecha_inasistencia' => $request->all()["fecha"],
                            'motivo' => 0, //Asiste la persona
                            'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                            'fecha_inasistencia2' => $request->all()["fecha2"],
                            'turno' => $request->all()["turno"],
                            'pre' => $request->all()["pre"]
                        )
                    ));
            } else //Update
            {
                DB::table('ins_gop_inasistencia')
                    ->where('id_supervisor', $request->all()["super"])
                    ->where('fecha_inasistencia', $request->all()["fecha"])
                    ->where('movil', $request->all()["movil"])
                    ->where('tecnico', $request->all()["lider"])
                    ->where('turno', $request->all()["turno"])
                    ->update(array(
                        'motivo' => 0, //Asiste la persona
                        'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                        'pre' => $request->all()["pre"]
                    ));
            }


            if (isset($request->all()["aux1"])) {
                if ($request->all()["aux1"] != "" && $request->all()["aux1"] != "0") {
                    //Asistencia de Aux
                    $tec_ina = DB::Table('ins_gop_inasistencia')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_inasistencia', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->where('tecnico', $request->all()["aux1"])
                        ->count();

                    //Guarda Asistencia de líderes que si vinieron
                    if ($tec_ina == 0)//Inserta
                    {
                        DB::table('ins_gop_inasistencia')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'tecnico' => $request->all()["aux1"],
                                    'tipo_tecnico' => 1, // 0-> Líder , 1-> Auxiliar, 2->Conductor
                                    'fecha_inasistencia' => $request->all()["fecha"],
                                    'motivo' => 0, //Asiste la persona
                                    'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                    'fecha_inasistencia2' => $request->all()["fecha2"],
                                    'turno' => $request->all()["turno"],
                                    'pre' => $request->all()["pre"]
                                )
                            ));
                    } else //Update
                    {
                        DB::table('ins_gop_inasistencia')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_inasistencia', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->where('tecnico', $request->all()["aux1"])
                            ->where('turno', $request->all()["turno"])
                            ->update(array(
                                'motivo' => 0, //Asiste la persona
                                'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                'pre' => $request->all()["pre"]
                            ));
                    }
                }
            }

            if (isset($request->all()["aux2"])) {
                if ($request->all()["aux2"] != "" && $request->all()["aux2"] != "0") {
                    //Asistencia de Aux
                    $tec_ina = DB::Table('ins_gop_inasistencia')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_inasistencia', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->where('tecnico', $request->all()["aux2"])
                        ->count();

                    //Guarda Asistencia de líderes que si vinieron
                    if ($tec_ina == 0)//Inserta
                    {
                        DB::table('ins_gop_inasistencia')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'tecnico' => $request->all()["aux2"],
                                    'tipo_tecnico' => 1, // 0-> Líder , 1-> Auxiliar, 2->Conductor
                                    'fecha_inasistencia' => $request->all()["fecha"],
                                    'motivo' => 0, //Asiste la persona
                                    'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                    'fecha_inasistencia2' => $request->all()["fecha2"],
                                    'turno' => $request->all()["turno"],
                                    'pre' => $request->all()["pre"]
                                )
                            ));
                    } else //Update
                    {
                        DB::table('ins_gop_inasistencia')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_inasistencia', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->where('tecnico', $request->all()["aux2"])
                            ->where('turno', $request->all()["turno"])
                            ->update(array(
                                'motivo' => 0, //Asiste la persona
                                'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                'pre' => $request->all()["pre"]
                            ));
                    }
                }
            }


            if (isset($request->all()["aux3"])) {
                if ($request->all()["aux3"] != "" && $request->all()["aux3"] != "0") {
                    //Asistencia de Aux
                    $tec_ina = DB::Table('ins_gop_inasistencia')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_inasistencia', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->where('tecnico', $request->all()["aux3"])
                        ->count();

                    //Guarda Asistencia de líderes que si vinieron
                    if ($tec_ina == 0)//Inserta
                    {
                        DB::table('ins_gop_inasistencia')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'tecnico' => $request->all()["aux3"],
                                    'tipo_tecnico' => 1, // 0-> Líder , 1-> Auxiliar, 2->Conductor
                                    'fecha_inasistencia' => $request->all()["fecha"],
                                    'motivo' => 0, //Asiste la persona
                                    'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                    'fecha_inasistencia2' => $request->all()["fecha2"],
                                    'turno' => $request->all()["turno"],
                                    'pre' => $request->all()["pre"]
                                )
                            ));
                    } else //Update
                    {
                        DB::table('ins_gop_inasistencia')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_inasistencia', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->where('tecnico', $request->all()["aux3"])
                            ->where('turno', $request->all()["turno"])
                            ->update(array(
                                'motivo' => 0, //Asiste la persona
                                'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                'pre' => $request->all()["pre"]
                            ));
                    }
                }
            }


            if (isset($request->all()["aux4"])) {
                if ($request->all()["aux4"] != "" && $request->all()["aux4"] != "0") {
                    //Asistencia de Aux
                    $tec_ina = DB::Table('ins_gop_inasistencia')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_inasistencia', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->where('tecnico', $request->all()["aux4"])
                        ->count();

                    //Guarda Asistencia de líderes que si vinieron
                    if ($tec_ina == 0)//Inserta
                    {
                        DB::table('ins_gop_inasistencia')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'tecnico' => $request->all()["aux4"],
                                    'tipo_tecnico' => 1, // 0-> Líder , 1-> Auxiliar, 2->Conductor
                                    'fecha_inasistencia' => $request->all()["fecha"],
                                    'motivo' => 0, //Asiste la persona
                                    'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                    'fecha_inasistencia2' => $request->all()["fecha2"],
                                    'turno' => $request->all()["turno"],
                                    'pre' => $request->all()["pre"]
                                )
                            ));
                    } else //Update
                    {
                        DB::table('ins_gop_inasistencia')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_inasistencia', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->where('tecnico', $request->all()["aux4"])
                            ->where('turno', $request->all()["turno"])
                            ->update(array(
                                'motivo' => 0, //Asiste la persona
                                'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                'pre' => $request->all()["pre"]
                            ));
                    }
                }
            }

            if (isset($request->all()["aux5"])) {
                if ($request->all()["aux5"] != "" && $request->all()["aux5"] != "0") {
                    //Asistencia de Aux
                    $tec_ina = DB::Table('ins_gop_inasistencia')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_inasistencia', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->where('tecnico', $request->all()["aux5"])
                        ->count();

                    //Guarda Asistencia de líderes que si vinieron
                    if ($tec_ina == 0)//Inserta
                    {
                        DB::table('ins_gop_inasistencia')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'tecnico' => $request->all()["aux5"],
                                    'tipo_tecnico' => 1, // 0-> Líder , 1-> Auxiliar, 2->Conductor
                                    'fecha_inasistencia' => $request->all()["fecha"],
                                    'motivo' => 0, //Asiste la persona
                                    'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                    'fecha_inasistencia2' => $request->all()["fecha2"],
                                    'turno' => $request->all()["turno"],
                                    'pre' => $request->all()["pre"]
                                )
                            ));
                    } else //Update
                    {
                        DB::table('ins_gop_inasistencia')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_inasistencia', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->where('tecnico', $request->all()["aux5"])
                            ->where('turno', $request->all()["turno"])
                            ->update(array(
                                'motivo' => 0, //Asiste la persona
                                'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                'pre' => $request->all()["pre"]
                            ));
                    }
                }
            }

            if (isset($request->all()["aux6"])) {
                if ($request->all()["aux6"] != "" && $request->all()["aux6"] != "0") {
                    //Asistencia de Aux
                    $tec_ina = DB::Table('ins_gop_inasistencia')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_inasistencia', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->where('tecnico', $request->all()["aux6"])
                        ->count();

                    //Guarda Asistencia de líderes que si vinieron
                    if ($tec_ina == 0)//Inserta
                    {
                        DB::table('ins_gop_inasistencia')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'tecnico' => $request->all()["aux6"],
                                    'tipo_tecnico' => 1, // 0-> Líder , 1-> Auxiliar, 2->Conductor
                                    'fecha_inasistencia' => $request->all()["fecha"],
                                    'motivo' => 0, //Asiste la persona
                                    'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                    'fecha_inasistencia2' => $request->all()["fecha2"],
                                    'turno' => $request->all()["turno"],
                                    'pre' => $request->all()["pre"]
                                )
                            ));
                    } else //Update
                    {
                        DB::table('ins_gop_inasistencia')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_inasistencia', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->where('tecnico', $request->all()["aux6"])
                            ->where('turno', $request->all()["turno"])
                            ->update(array(
                                'motivo' => 0, //Asiste la persona
                                'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                'pre' => $request->all()["pre"]
                            ));
                    }
                }
            }


            if (isset($request->all()["conductor"])) {
                if ($request->all()["conductor"] != "" && $request->all()["conductor"] != "0") {
                    //Asistencia de Aux
                    $tec_ina = DB::Table('ins_gop_inasistencia')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_inasistencia', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->where('tecnico', $request->all()["conductor"])
                        ->count();

                    //Guarda Asistencia de líderes que si vinieron
                    if ($tec_ina == 0)//Inserta
                    {
                        DB::table('ins_gop_inasistencia')
                            ->insert(array(
                                array(
                                    'id_supervisor' => $request->all()["super"],
                                    'fecha' => $this->fechaALong,
                                    'movil' => $request->all()["movil"],
                                    'tecnico' => $request->all()["conductor"],
                                    'tipo_tecnico' => 2, // 0-> Líder , 1-> Auxiliar, 2->Conductor
                                    'fecha_inasistencia' => $request->all()["fecha"],
                                    'motivo' => 0, //Asiste la persona
                                    'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                    'fecha_inasistencia2' => $request->all()["fecha2"],
                                    'turno' => $request->all()["turno"],
                                    'pre' => $request->all()["pre"]
                                )
                            ));
                    } else //Update
                    {
                        DB::table('ins_gop_inasistencia')
                            ->where('id_supervisor', $request->all()["super"])
                            ->where('fecha_inasistencia', $request->all()["fecha"])
                            ->where('movil', $request->all()["movil"])
                            ->where('tecnico', $request->all()["conductor"])
                            ->where('turno', $request->all()["turno"])
                            ->update(array(
                                'motivo' => 0, //Asiste la persona
                                'observacion' => "ASISTENCIA POR CONFORMACIÓN DE CUADRILLAS",
                                'pre' => $request->all()["pre"]
                            ));
                    }
                }
            }


            $retorno = "1";
        }

        if ($tipo == "2") //Inserta información supervisión si la cuadrila no salio
        {
            $super = DB::Table($pre . '_gop_supervisor')
                ->where('id_supervisor', $request->all()["super"])
                ->where('fecha_creacion', $request->all()["fecha"])
                ->where('movil', $request->all()["movil"])
                ->select('lider')
                ->get();

            $aux1 = "";
            $aux2 = "";
            $aux3 = "";
            $conductor = "";
            $matri = "";

            if (isset($request->all()["aux1"]))
                $aux1 = $request->all()["aux1"];

            if (isset($request->all()["aux2"]))
                $aux2 = $request->all()["aux2"];

            if (isset($request->all()["aux3"]))
                $aux3 = $request->all()["aux3"];

            if (isset($request->all()["conductor"]))
                $conductor = $request->all()["conductor"];

            if (isset($request->all()["matri"]))
                $matri = $request->all()["matri"];

            $causa_cancelacionn = '';
            if (isset($_REQUEST['causa_cancelacion'])) {
                $causa_cancelacionn = $_REQUEST['causa_cancelacion'];
            }

            if ($pre == "rds" || $pre == "apu") {
                if (count($super) == 0)//Inserta
                {
                    DB::table($pre . '_gop_supervisor')
                        ->insert(array(
                            array(
                                'id_supervisor' => $request->all()["super"],
                                'fecha' => $this->fechaALong,
                                'movil' => $request->all()["movil"],
                                'g_tecnico' => $request->all()["tipo_cu"],
                                'lider' => $request->all()["lider"],
                                'aux1' => ($aux1 == "0" ? "" : $aux1),
                                'aux2' => ($aux2 == "0" ? "" : $aux2),
                                'aux3' => ($aux3 == "0" ? "" : $aux3),
                                'conductor' => ($conductor == "0" ? "" : $conductor),
                                'vehiculo' => ($matri == "0" ? "" : $matri),
                                'com' => $request->all()["com"],
                                'estado' => $request->all()["estado"],
                                'hri' => null,
                                'hre' => null,
                                'fecha_creacion' => $request->all()["fecha"],
                                'id_turno' => $request->all()["turno"],
                                'causa_cancelacionn' => $causa_cancelacionn,
                                'id_estado_m' => $causa_cancelacionn ? 'I' : 'A'
                            )
                        ));
                } else //Update
                {
                    DB::table($pre . '_gop_supervisor')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_creacion', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->update(array(
                            'estado' => $request->all()["estado"],
                            'lider' => $request->all()["lider"],
                            'aux1' => ($aux1 == "0" ? "" : $aux1),
                            'aux2' => ($aux2 == "0" ? "" : $aux2),
                            'aux3' => ($aux3 == "0" ? "" : $aux3),
                            'hri' => null,
                            'hre' => null,
                            'com' => $request->all()["com"],
                            'conductor' => ($conductor == "0" ? "" : $conductor),
                            'vehiculo' => ($matri == "0" ? "" : $matri),
                            'causa_cancelacionn' => $causa_cancelacionn,
                            'id_estado_m' => $causa_cancelacionn ? 'I' : 'A'
                        ));
                }
            } else {
                if (count($super) == 0)//Inserta
                {
                    DB::table($pre . '_gop_supervisor')
                        ->insert(array(
                            array(
                                'id_supervisor' => $request->all()["super"],
                                'fecha' => $this->fechaALong,
                                'movil' => $request->all()["movil"],
                                'g_tecnico' => $request->all()["tipo_cu"],
                                'lider' => $request->all()["lider"],
                                'aux1' => ($aux1 == "0" ? "" : $aux1),
                                'aux2' => ($aux2 == "0" ? "" : $aux2),
                                'aux3' => ($aux3 == "0" ? "" : $aux3),
                                'conductor' => ($conductor == "0" ? "" : $conductor),
                                'vehiculo' => ($matri == "0" ? "" : $matri),
                                'com' => $request->all()["com"],
                                'estado' => $request->all()["estado"],
                                'hri' => null,
                                'hre' => null,
                                'fecha_creacion' => $request->all()["fecha"],
                                'id_turno' => $request->all()["turno"]
                            )
                        ));
                } else //Update
                {
                    DB::table($pre . '_gop_supervisor')
                        ->where('id_supervisor', $request->all()["super"])
                        ->where('fecha_creacion', $request->all()["fecha"])
                        ->where('movil', $request->all()["movil"])
                        ->update(array(
                            'estado' => $request->all()["estado"],
                            'lider' => $request->all()["lider"],
                            'aux1' => ($aux1 == "0" ? "" : $aux1),
                            'aux2' => ($aux2 == "0" ? "" : $aux2),
                            'aux3' => ($aux3 == "0" ? "" : $aux3),
                            'hri' => null,
                            'hre' => null,
                            'com' => $request->all()["com"],
                            'conductor' => ($conductor == "0" ? "" : $conductor),
                            'vehiculo' => ($matri == "0" ? "" : $matri)
                        ));
                }
            }
            $retorno = "1";
        }

        if ($tipo == "3") //Guarda las inasistencias
        {

            $super = DB::Table('ins_gop_inasistencia')
                ->where('id_supervisor', $request->all()["super"])
                ->where('fecha_inasistencia', $request->all()["fecha"])
                ->where('movil', $request->all()["movil"])
                ->where('tecnico', $request->all()["cuadrillero"])
                ->select('id_supervisor')
                ->get();

            $aux1 = "";
            $aux2 = "";
            $aux3 = "";
            $conductor = "";
            $matri = "";

            if (count($super) == 0)//Inserta
            {
                DB::table('ins_gop_inasistencia')
                    ->insert(array(
                        array(
                            'id_supervisor' => $request->all()["super"],
                            'fecha' => $this->fechaALong,
                            'movil' => $request->all()["movil"],
                            'tecnico' => $request->all()["cuadrillero"],
                            'tipo_tecnico' => $request->all()["tipo"],
                            'fecha_inasistencia' => $request->all()["fecha"],
                            'motivo' => $request->all()["motivo"],
                            'observacion' => $request->all()["obser"],
                            'fecha_inasistencia2' => $request->all()["fecha2"],
                            'turno' => $request->all()["turno"],
                            'pre' => $request->all()["pre"]
                        )
                    ));
            } else //Update
            {
                DB::table('ins_gop_inasistencia')
                    ->where('id_supervisor', $request->all()["super"])
                    ->where('fecha_inasistencia', $request->all()["fecha"])
                    ->where('movil', $request->all()["movil"])
                    ->where('tecnico', $request->all()["cuadrillero"])
                    ->where('turno', $request->all()["turno"])
                    ->update(array(
                        'motivo' => $request->all()["motivo"],
                        'observacion' => $request->all()["obser"],
                        'pre' => $request->all()["pre"]
                    ));
            }
            $retorno = "1";
        }

        if ($tipo == "4") //Guarda inspección
        {
            $inpec = DB::Table('ins_inspeccion')
                ->where('id_orden', $request->all()["cabeza"]["orden"])
                ->where('lider', $request->all()["cabeza"]["lider"])
                ->where('tipo_inspeccion', $request->all()["cabeza"]["tipo_insp"])
                ->select('id_inspeccion')
                ->get();

            $obser = "";
            if (isset($request->all()["cabeza"]["observacion"]))
                $obser = $request->all()["cabeza"]["observacion"];

            $charla = "";
            if (isset($request->all()["cabeza"]["charla"]))
                $charla = $request->all()["cabeza"]["charla"];


            $actividadEspecifica = "";
            $coordenadas = "";

            if (isset($request->all()["cabeza"]["actividadEspecifica"]))
                $actividadEspecifica = $request->all()["cabeza"]["actividadEspecifica"];


            if (isset($request->all()["cabeza"]["coordenadas"]))
                $coordenadas = $request->all()["cabeza"]["coordenadas"];


            $conse = "";
            if (count($inpec) == 0)//Inserta
            {
                $conse = self::generaConsecutivo("ID_INSPEC");
                DB::Table('ins_inspeccion')
                    ->insert(array(
                        array(
                            'id_inspeccion' => $conse,
                            'id_orden' => $request->all()["cabeza"]["orden"],
                            'prefijo' => $request->all()["cabeza"]["pre"],
                            'id_tipo_proyecto' => $request->all()["cabeza"]["id_pro"],
                            'supervisor' => $request->all()["cabeza"]["super"],
                            'fecha_servidor' => $this->fechaALong,
                            'lider' => $request->all()["cabeza"]["lider"],
                            'aux1' => $request->all()["cabeza"]["aux1"],
                            'aux2' => $request->all()["cabeza"]["aux2"],
                            'aux3' => $request->all()["cabeza"]["aux3"],
                            'conductor' => $request->all()["cabeza"]["cond"],
                            'matricula' => $request->all()["cabeza"]["matri"],
                            'tipo_ingreso_inspeccion' => 'LN',
                            'tipo_cuadrilla' => $request->all()["cabeza"]["tipoC"],
                            'movil' => $request->all()["cabeza"]["movil"],
                            'tipo_inspeccion' => $request->all()["cabeza"]["tipo_insp"],
                            'resultado' => $request->all()["cabeza"]["resultado"],
                            'estado' => (trim($request->all()["cabeza"]["resultado"]) == "C" ? "E2" : $request->all()["cabeza"]["estado"]),
                            'charla_calificacion' => $charla,
                            'observacion' => $obser,
                            'actividad_especifica' => $actividadEspecifica,
                            'coordenadas' => $coordenadas
                        )
                    ));

                DB::Table('ssl_eventos')
                    ->insert(array(
                        array(
                            'id_origen' => $request->all()["cabeza"]["lider"],
                            'observaciones' => $request->all()["cabeza"]["resultado"] . " - " . $obser,
                            'fecha' => explode(" ", $this->fechaALong)[0],
                            'id_orden' => $request->all()["cabeza"]["orden"],
                            'prefijo' => $request->all()["cabeza"]["pre"],
                            'tipo_evento' => "IPAL",
                            'hora' => explode(" ", $this->fechaALong)[1],
                            'notificacion' => 1,
                            'id_ipal' => $conse
                        )
                    ));

                if (isset($request->all()["cabeza"]["firma"])) {
                    $firma = $request->all()["cabeza"]["firma"];
                    $data = explode(',', $firma);
                    $imagenDigital = base64_decode($data[1]);

                    //Nombre Fotografia
                    $nombreArchivo = "Supervision_" . $request->all()["cabeza"]["super"] . "_" . substr(md5(uniqid(rand())), 0, 10) . ".png";

                    $super = $request->all()["cabeza"]["super"];
                    $fecha_movil = $this->fechaShort;
                    $tipo = 'T06';
                    $turno = $request->all()["cabeza"]["turno"];

                    DB::Table('ins_foto')
                        ->insert(array(
                            array(
                                'id_super' => $super,
                                'fecha_celular' => $fecha_movil,
                                'id_tipo' => $tipo,
                                'turno' => $turno,
                                'id_inspeccion' => $conse,
                                'ruta' => $nombreArchivo,
                                'pre' => $request->all()["cabeza"]["pre"],
                                'orden' => $request->all()["cabeza"]["orden"]
                            )
                        ));

                    $id_ftp = ftp_connect("201.217.195.35", 21); //Obtiene un manejador del Servidor FTP
                    ftp_login($id_ftp, "usuario_ftp", "74091450652!@#1723cc"); //Se loguea al Servidor FTP
                    ftp_pasv($id_ftp, true); //Se coloca el modo pasivo
                    ftp_chdir($id_ftp, "ins"); // Nos dirigimos a la carpeta de destino
                    $Directorio = ftp_pwd($id_ftp);
                    $Directorio2 = $Directorio;

                    //Guardar el archivo en mi carpeta donde esta el proyecto

                    $res = 0;
                    try {
                        $fileL = storage_path('app') . "/" . $nombreArchivo;
                        \Storage::disk('local')->put($nombreArchivo, $imagenDigital);
                        $exi = ftp_put($id_ftp, $Directorio . "/" . $nombreArchivo, $fileL, FTP_BINARY);
                        //Cuando se envia el archivo, se elimina el archivo
                        if (file_exists($fileL))
                            unlink($fileL);

                    } catch (Exception $e) {
                        $res = $e;
                    }
                }
            } else //Update
            {
                $conse = $inpec[0]->id_inspeccion;
                DB::Table('ins_inspeccion')
                    ->where('id_orden', $request->all()["cabeza"]["orden"])
                    ->where('id_inspeccion', $inpec[0]->id_inspeccion)
                    ->where('tipo_inspeccion', $request->all()["cabeza"]["tipo_insp"])
                    ->update(array(
                        'prefijo' => $request->all()["cabeza"]["pre"],
                        'id_tipo_proyecto' => $request->all()["cabeza"]["id_pro"],
                        'supervisor' => $request->all()["cabeza"]["super"],
                        'lider' => $request->all()["cabeza"]["lider"],
                        'aux1' => $request->all()["cabeza"]["aux1"],
                        'aux2' => $request->all()["cabeza"]["aux2"],
                        'aux3' => $request->all()["cabeza"]["aux3"],
                        'conductor' => $request->all()["cabeza"]["cond"],
                        'matricula' => $request->all()["cabeza"]["matri"],
                        'tipo_cuadrilla' => $request->all()["cabeza"]["tipoC"],
                        'movil' => $request->all()["cabeza"]["movil"],
                        'tipo_inspeccion' => $request->all()["cabeza"]["tipo_insp"],
                        'resultado' => $request->all()["cabeza"]["resultado"],
                        'actividad_especifica' => $actividadEspecifica,
                        'coordenadas' => $coordenadas,
                        'estado' => (trim($request->all()["cabeza"]["resultado"]) == "C" ? "E2" : $request->all()["cabeza"]["estado"])
                    ));
            }

            if (isset($request->all()["cuerpo"])) {
                if (isset($request->all()["cuerpo"][0]["preg"])) {
                    //Insertamos detalle
                    DB::table('ins_inspeccion_detalle')
                        ->where('id_inspeccion', $conse)
                        ->where('id_orden', $request->all()["cabeza"]["orden"])
                        ->delete();

                    $mayorValor = 0;
                    for ($i = 0; $i < count($request->all()["cuerpo"]); $i++) {

                        $version = 1;
                        if (isset($request->all()["cuerpo"][$i]["version"]))
                            $version = $request->all()["cuerpo"][$i]["version"];

                        if ($request->all()["cuerpo"][$i]["res"] == "1") {
                            //Consulta respuesta
                            $valor = DB::table('gop_formularios_creacion')
                                ->where('tipo_formulario', $request->all()["cuerpo"][$i]["form"])
                                ->where('version', $version)
                                ->where('id_pregunta', $request->all()["cuerpo"][$i]["preg"])
                                ->value('nombre_corto');

                            if ($valor == "" || $valor == NULL)
                                $valor = 0;
                            else
                                $valor = intval($valor);

                            if ($valor > $mayorValor)
                                $mayorValor = $valor;
                        }

                        $acto = "";
                        if (isset($request->all()["cuerpo"][$i]["acto"]))
                            $acto = $request->all()["cuerpo"][$i]["acto"];


                        if (isset($request->all()["cuerpo"][$i]["hallazgo"])) {
                            if ($request->all()["cuerpo"][$i]["hallazgo"] != "") {
                                $desItem = DB::table('gop_formularios_creacion')
                                    ->where('tipo_formulario', $request->all()["cuerpo"][$i]["form"])
                                    ->where('version', $version)
                                    ->where('id_pregunta', $request->all()["cuerpo"][$i]["preg"])
                                    ->value('descrip_pregunta');

                                DB::Table('ins_analisis_causas')
                                    ->insert(array(
                                        array(
                                            'analisis' => $request->all()["cuerpo"][$i]["hallazgo"],
                                            'id_item' => $request->all()["cuerpo"][$i]["preg"],
                                            'des_item' => $desItem,
                                            'id_inspeccion' => $conse,
                                            'usuario' => $request->all()["cabeza"]["super"],
                                            'tipo_ingeso' => 1
                                        )
                                    ));
                            }
                        }

                        DB::Table('ins_inspeccion_detalle')
                            ->insert(array(
                                array(
                                    'id_inspeccion' => $conse,
                                    'id_orden' => $request->all()["cabeza"]["orden"],
                                    'id_pregunta' => $request->all()["cuerpo"][$i]["preg"],
                                    'respuesta' => $request->all()["cuerpo"][$i]["res"],
                                    'id_formulario' => $request->all()["cuerpo"][$i]["form"],
                                    'version' => $version,
                                    'acto_condicion' => $acto
                                )
                            ));
                    }

                    if ($request->all()["cabeza"]["resultado"] == "C")
                        $mayorValor = 0;

                    //Update calificación
                    DB::table('ins_inspeccion')
                        ->where('id_inspeccion', $conse)
                        ->where('lider', $request->all()["cabeza"]["lider"])
                        ->where('tipo_inspeccion', $request->all()["cabeza"]["tipo_insp"])
                        ->update(array(
                            'calificacion' => ($mayorValor == 0 ? "" : $mayorValor)
                        ));

                    //Update ssl_eventos
                    DB::table('ssl_eventos')
                        ->where('id_ipal', $conse)
                        ->update(array(
                            'respuesta_auto' => ($mayorValor == 0 ? "" : $mayorValor)
                        ));
                }
            }

            $retorno = $conse;
        }

        if ($tipo == "5") //Inserta GPS supervisor
        {
            $user = $request->all()["user"];
            $coor = $request->all()["coor"];
            DB::table('ins_gps_tracker')
                ->insert(array(
                    array(
                        'prefijo' => $pre,
                        'latitud' => explode(",", $coor)[0],
                        'longitud' => explode(",", $coor)[1],
                        'fecha' => explode(" ", $this->fechaALong)[0],
                        'hora' => explode(" ", $this->fechaALong)[1],
                        'usuario_movil' => $user
                    )
                ));
            $retorno = "1";
        }

        if ($tipo == "6") //Inserta plan de acción
        {
            $accion = $request->all()["accion"];
            $resp = $request->all()["resp"];
            $fechal = explode("/", $request->all()["fechal"])[2] . '-' . explode("/", $request->all()["fechal"])[1] . "-" . explode("/", $request->all()["fechal"])[0];
            $inspeccion = $request->all()["inspeccion"];

            $item = $request->all()["item"];
            $des_item = $request->all()["des_item"];


            $cont = DB::Table('ins_plan_accion')
                ->where('id_inspeccion', $inspeccion)
                ->count();

            if ($cont == 0)
                DB::table('ins_inspeccion')
                    ->where('id_inspeccion', $inspeccion)
                    ->update(array(
                        'estado' => 'E1'
                    ));

            DB::Table('ins_plan_accion')
                ->insert(array(
                    array(
                        'id_inspeccion' => $inspeccion,
                        'accion' => $accion,
                        'responsable' => $resp,
                        'fecha_limite' => $fechal,
                        'fecha_sevidor' => $this->fechaALong,
                        'item' => $item,
                        'des_item' => $des_item,
                        'id_analisis' => $request->all()["id_ana"],
                        'des_analisis' => $request->all()["des_ana"],
                        'usuario' => $request->all()["cedula"]
                    )
                ));

            self::saveLog("OP01", $inspeccion, "Acción:" . $accion . " - Item:" . $des_item);

            $plan = DB::Table('ins_plan_accion')
                ->where('id_inspeccion', $inspeccion)
                ->select('accion', 'responsable', 'fecha_limite', 'fecha_cierre', 'observacion_cierre', 'id', 'evidencia', 'item', 'des_item', 'id_analisis', 'des_analisis', 'usuario')
                ->get();

            $inspeccion = DB::table('ins_inspeccion')
                ->where('id_inspeccion', $inspeccion)
                ->get(['estado', 'resultado'])[0];

            return response()->json(view('proyectos.supervisor.tables.tblplanaccion',
                array(
                    "plan" => $plan,
                    "inspeccion" => $inspeccion
                ))->render());

            $retorno = "1";
        }

        if ($tipo == "7") //Inserta Analisis y causas
        {
            $analisis = $request->all()["analisis"];
            $inspeccion = $request->all()["inspeccion"];

            $mime = $request->all()["mime"];

            $nombreArchivo = "";
            if ($mime != "") {
                //Archivo evidencia
                $archivo = base64_decode(explode(",", $request->all()['archivo'])[1]);

                $nombreArchivo = "Supervision_Causa_Raiz_" . $inspeccion . "_" . substr(md5(uniqid(rand())), 0, 5) . "." . $mime;

                $id_ftp = ftp_connect("201.217.195.35", 21); //Obtiene un manejador del Servidor FTP
                ftp_login($id_ftp, "usuario_ftp", "74091450652!@#1723cc"); //Se loguea al Servidor FTP
                ftp_pasv($id_ftp, true); //Se coloca el modo pasivo
                ftp_chdir($id_ftp, "ins"); // Nos dirigimos a la carpeta de destino
                $Directorio = ftp_pwd($id_ftp);
                $Directorio2 = $Directorio;

                //Guardar el archivo en mi carpeta donde esta el proyecto
                $res = 0;
                try {
                    $fileL = storage_path('app') . "/" . $nombreArchivo;
                    \Storage::disk('local')->put($nombreArchivo, $archivo);
                    $exi = ftp_put($id_ftp, $Directorio . "/" . $nombreArchivo, $fileL, FTP_BINARY);
                    //Cuando se envia el archivo, se elimina el archivo
                    if (file_exists($fileL))
                        unlink($fileL);

                } catch (Exception $e) {
                    $res = $e;
                }
            }


            $countAnalisis = DB::Table('ins_analisis_causas')
                ->where('id_inspeccion', $inspeccion)
                ->count();

            if ($countAnalisis == 0)
                self::saveLog("OP07", $inspeccion, "Inspección cambia de estado");

            $user = str_replace("CO", "", DB::Table('sis_usuarios')
                ->where('id_usuario', Session::get('user_login'))
                ->value('cuenta'));

            for ($i = 0; $i < count($analisis); $i++) {
                DB::Table('ins_analisis_causas')
                    ->insert(array(
                        array(
                            'analisis' => $analisis[$i]["causa"],
                            'id_item' => $analisis[$i]["id_item"],
                            'des_item' => $analisis[$i]["des_item"],
                            'id_inspeccion' => $inspeccion,
                            'usuario' => $user,
                            'adjunto' => $nombreArchivo
                        )
                    ));

                if ($countAnalisis == 0)
                    self::saveLog("OP02", $inspeccion, "Analisis:" . $analisis[$i]["causa"] . " - Item:" . $analisis[$i]["des_item"]);
                else
                    self::saveLog("OP04", $inspeccion, "Analisis:" . $analisis[$i]["causa"] . " - Item:" . $analisis[$i]["des_item"]);
            }

            if ($countAnalisis == 0)
                DB::table('ins_inspeccion')
                    ->where('id_inspeccion', $inspeccion)
                    ->update(array(
                        'estado' => 'E1'
                    ));

            $retorno = DB::Table('ins_analisis_causas')
                ->where('id_inspeccion', $inspeccion)
                ->select('analisis', 'id_item', 'des_item', 'id', 'adjunto')
                ->get();

        }

        if ($tipo == "8") //Actualiza plan de acción
        {
            $accion = $request->all()["accion"];
            $resp = $request->all()["resp"];
            $id = $request->all()["id"];
            $fechal = explode("/", $request->all()["fechal"])[2] . '-' . explode("/", $request->all()["fechal"])[1] . "-" . explode("/", $request->all()["fechal"])[0];
            $inspeccion = $request->all()["inspeccion"];

            if ($request->all()["aux"] == "1") //Update sin cerrar
            {
                DB::Table('ins_plan_accion')
                    ->where('id_inspeccion', $inspeccion)
                    ->where('id', $id)
                    ->update(array(
                        'accion' => $accion,
                        'responsable' => $resp,
                        'fecha_limite' => $fechal,
                        'id_analisis' => $request->all()["id_ana"],
                        'des_analisis' => $request->all()["des_ana"],
                        'usuario' => $request->all()["cedula"]
                    ));


                self::saveLog("OP03", $inspeccion, "Acción:" . $accion);
            } else {

                $ext = "pdf";
                if (isset($request->all()["extencion"])) {
                    $ext = trim($request->all()['extencion']);
                }


                $fecha2 = explode("/", $request->all()["fecha2"])[2] . '-' . explode("/", $request->all()["fecha2"])[1] . "-" . explode("/", $request->all()["fecha2"])[0];
                $obser = $request->all()["obser"];

                //Archivo evidencia
                $archivo = base64_decode($request->all()["evidencia"]);

                $nombreArchivo = "Supervision_Anexo_Plan_Accion_" . $inspeccion . "_" . substr(md5(uniqid(rand())), 0, 5) . "." . $ext;

                DB::Table('ins_plan_accion')
                    ->where('id_inspeccion', $inspeccion)
                    ->where('id', $id)
                    ->update(array(
                        'accion' => $accion,
                        'responsable' => $resp,
                        'fecha_limite' => $fechal,
                        'fecha_cierre' => $fecha2,
                        'observacion_cierre' => $obser,
                        'evidencia' => $nombreArchivo,
                        'id_analisis' => $request->all()["id_ana"],
                        'des_analisis' => $request->all()["des_ana"],
                        'usuario' => $request->all()["cedula"]
                    ));


                self::saveLog("OP03", $inspeccion, "Acción:" . $accion . " - Cierre:" . $obser);

                $id_ftp = ftp_connect("201.217.195.35", 21); //Obtiene un manejador del Servidor FTP
                ftp_login($id_ftp, "usuario_ftp", "74091450652!@#1723cc"); //Se loguea al Servidor FTP
                ftp_pasv($id_ftp, true); //Se coloca el modo pasivo
                ftp_chdir($id_ftp, "ins"); // Nos dirigimos a la carpeta de destino
                $Directorio = ftp_pwd($id_ftp);
                $Directorio2 = $Directorio;

                //Guardar el archivo en mi carpeta donde esta el proyecto
                $res = 0;
                try {
                    $fileL = storage_path('app') . "/" . $nombreArchivo;
                    \Storage::disk('local')->put($nombreArchivo, $archivo);
                    $exi = ftp_put($id_ftp, $Directorio . "/" . $nombreArchivo, $fileL, FTP_BINARY);
                    //Cuando se envia el archivo, se elimina el archivo
                    if (file_exists($fileL))
                        unlink($fileL);

                } catch (Exception $e) {
                    $res = $e;
                }
            }

            $plan = DB::Table('ins_plan_accion')
                ->where('id_inspeccion', $inspeccion)
                ->select('accion', 'responsable', 'fecha_limite', 'fecha_cierre', 'observacion_cierre', 'id', 'evidencia', 'item', 'des_item', 'id_analisis', 'des_analisis', 'usuario')
                ->get();

            $cantSinCierre = DB::Table('ins_plan_accion')
                ->where('id_inspeccion', $inspeccion)
                ->whereNull('fecha_cierre')
                ->count();


            if ($cantSinCierre == 0) {
                DB::table('ins_inspeccion')
                    ->where('id_inspeccion', $inspeccion)
                    ->update(array(
                        'estado' => 'E2'
                    ));

                self::saveLog("OP06", $inspeccion, "Finaliza inspección");

                return response()->json("1");

            }

            $inspeccion = DB::table('ins_inspeccion')
                ->where('id_inspeccion', $inspeccion)
                ->get(['estado', 'resultado'])[0];

            return response()->json(view('proyectos.supervisor.tables.tblplanaccion',
                array(
                    "plan" => $plan,
                    "inspeccion" => $inspeccion
                ))->render());

            $retorno = "1";
        }

        if ($tipo == "9") //Save formulario creación
        {
            $tipo_f = $request->all()["tipo_f"];
            $form = $request->all()["form"];

            //DB::beginTransaction();
            try {
                $versionSiguiente = intval(DB::table('gop_formularios_creacion')
                        ->where('tipo_formulario', $tipo_f)
                        ->value(DB::raw('MAX(version)  as dat'))) + 1;

                if (isset($request->all()["correo1"])) {
                    DB::Table('gop_formulario_tipo')
                        ->where('tipo_formulario', 1)
                        ->update(
                            array(
                                'correo1' => $request->all()["correo1"],
                                'correo2' => $request->all()["correo2"],
                                'correo3' => $request->all()["correo3"]
                            )
                        );
                }

                //Inserta registros de creación de formulario
                for ($i = 0; $i < count($form); $i++) {
                    DB::Table('gop_formularios_creacion')
                        ->insert(array(
                            array(
                                'tipo_formulario' => $tipo_f,
                                'item_num' => $form[$i]['item'],
                                'id_pregunta' => $form[$i]['preg'],
                                'descrip_pregunta' => $form[$i]['desc'],
                                'obligatorio' => $form[$i]['obli'],
                                'tipo_control' => $form[$i]['tipo'],
                                'id_padre' => $form[$i]['padre'],
                                'nombre_corto' => $form[$i]['corto'],
                                'version' => $versionSiguiente
                            )
                        ));
                }

                //inserta log
                DB::Table('gop_formularios_creacion_log')
                    ->insert(array(
                        array(
                            'tipo_formulario' => $tipo_f,
                            'id_usuario' => Session::get('user_login'),
                            'version_previa' => ($versionSiguiente - 1),
                            'version_posterior' => $versionSiguiente
                        )
                    ));


                //DB::commit();
                return response()->json("1");
            } catch (Exception $e) {
                //DB::rollBack();
                return response()->json("0");
            }
        }

        if ($tipo == "10") //Save suscripción Supervisor
        {
            $super = $request->all()["super"];
            $token = $request->all()["token"];
            $pre = "";

            $contToken = DB::Table('ins_token_movil')
                ->where('id_supervidor', $super)
                ->count();

            if ($contToken == 0) {
                if (isset($request->all()["pre"]))
                    $pre = $request->all()["pre"];


                DB::Table('ins_token_movil')
                    ->insert(array(
                        array(
                            'id_supervidor' => $super,
                            'token_movil' => $token,
                            'fecha_servidor' => $this->fechaALong,
                            'pre' => $pre
                        )
                    ));
            } else {
                if (isset($request->all()["pre"]))
                    $pre = $request->all()["pre"];

                DB::Table('ins_token_movil')
                    ->where('id_supervidor', $super)
                    ->update(array(
                            'token_movil' => $token,
                            'fecha_servidor' => $this->fechaALong,
                            'pre' => $pre
                        )
                    );
            }
            return response()->json("1");
        }

        if ($tipo == "11") //Save Observación Supervisor
        {
            $super = $request->all()["super"];
            $fecha1 = $request->all()["fecha1"];
            $fecha2 = $request->all()["fecha2"];
            $turno = $request->all()["turno"];
            $obser = $request->all()["obser"];

            DB::Table('ins_observacion')
                ->insert(array(
                    array(
                        'supervisor' => $super,
                        'fecha_server_1' => $fecha1,
                        'fecha_server_2' => $fecha2,
                        'turno' => $turno,
                        'observacion' => $obser,
                        'fecha_servidor' => $this->fechaALong
                    )
                ));

            return response()->json("1");
        }

        if ($tipo == "12") //Save Acto ó condición
        {
            $ins = $request->all()["ins"];
            $resp = $request->all()["resp"];


            for ($i = 0; $i < count($resp); $i++) {
                $pre = $resp[$i]["id_pre"];
                $acto = $resp[$i]["acto_con"];

                DB::Table('ins_inspeccion_detalle')
                    ->where('id_inspeccion', $ins)
                    ->where('id_formulario', 1)
                    ->where('id_pregunta', $pre)
                    ->update(
                        array(
                            'acto_condicion' => $acto
                        ));

                self::saveLog("OP05", $ins, "Pregunta:" . $pre . " - Acto ó condición:" . $acto);

            }

            return response()->json("1");
        }

        if ($tipo == "13") //Consulta LOG
        {
            $ins = $request->all()["ins"];
            $retorno = DB::Table('ins_log as log')
                ->join('sis_usuarios as sis', 'sis.id_usuario', '=', 'log.usuario')
                ->join('ins_tipo_log as tipoL', 'tipoL.id', '=', 'log.tipo_log')
                ->where('log.campo_valor', $ins)
                ->get(['sis.propietario', 'log.fecha_servidor', 'log.descripcion', 'log.descripcion', 'tipoL.des']);

        }

        if ($tipo == "14") //Delete Analisis
        {

            $datoCount = DB::Table('ins_plan_accion')
                ->where('id_inspeccion', $request->all()["ins"])
                ->where('id_analisis', $request->all()["id"])
                ->count();

            if ($datoCount != 0)
                $retorno = "-1";
            else {
                $analisi = DB::Table('ins_analisis_causas')
                    ->where('id', $request->all()["id"])
                    ->value('analisis');

                DB::Table('ins_analisis_causas')
                    ->where('id', $request->all()["id"])
                    ->delete();

                self::saveLog("OP08", $request->all()["ins"], "Acción:" . $analisi);

                $retorno = DB::Table('ins_analisis_causas')
                    ->where('id_inspeccion', $request->all()["ins"])
                    ->select('analisis', 'id_item', 'des_item', 'id', 'adjunto')
                    ->get();
            }

        }

        if ($tipo == "15") //Cerrar inspección
        {
            $cantSinCierre = DB::Table('ins_plan_accion')
                ->where('id_inspeccion', $request->all()["ins"])
                ->whereNull('fecha_cierre')
                ->count();


            $cantidadAnalisis = DB::Table('ins_analisis_causas')
                ->where('id_inspeccion', $request->all()["ins"])
                ->count();

            if ($cantidadAnalisis == 0)
                return response()->json("-2");


            $cantidadPlanAccion = DB::Table('ins_plan_accion')
                ->where('id_inspeccion', $request->all()["ins"])
                ->count();

            if ($cantidadPlanAccion == 0)
                return response()->json("-3");

            if ($cantSinCierre == 0) {
                DB::table('ins_inspeccion')
                    ->where('id_inspeccion', $request->all()["ins"])
                    ->update(array(
                        'estado' => 'E2'
                    ));

                self::saveLog("OP06", $request->all()["ins"], "Finaliza inspección");
                return response()->json("1");
            } else
                return response()->json("-1");
        }

        if ($tipo == "16") //Guardar Calificación Autoinspección
        {
            $cali = $request->all()['cali'];
            $id = $request->all()['id'];
            $superId = $request->all()['super'];
            $obser = "";

            if (isset($request->all()['obser']))
                $obser = $request->all()['obser'];

            DB::table('ssl_eventos')
                ->where('id', $id)
                ->update(
                    array(
                        'respuesta_auto' => $cali,
                        'supervisor_respuesta' => $superId,
                        'fecha_calificacion_supervisor' => $this->fechaALong,
                        'observacion_calificacion_supervisor' => $obser
                    ));

            $retorno = "1";
        }

        return response()->json($retorno);
    }

    //Web services para consultar la información de la Aplicación Supervisión
    public function consultaProy(Request $request)
    {
        $tipo = $request->all()['tip'];
        $retorno = "0";
        if ($tipo == 1) //Consulta proyectos
        {
            $consulta = "
                  SELECT
                      gop_proyectos.nombre AS proyecto,
                      gop_proyectos.prefijo_db as prefijo_db,
                      (
                        select top(1) tbl1.id_proyecto
                        FROM gop_proyectos as tbl1
                        WHERE tbl1.prefijo_db = gop_proyectos.prefijo_db
                        ) as id_proyecto
                    FROM gop_proyectos
                    where prefijo_db is not null and prefijo_db != '' and id_estado = 'A'
                    GROUP BY nombre, prefijo_db;
                        ";
            $retorno = DB::select($consulta);
        }

        if ($tipo == 2) //Consulta Login
        {
            $userN = $request->all()['usuario'];
            $pass = $request->all()['pass'];
            $pre = $request->all()['pro'];

            $user = DB::Table($pre . '_gop_cuadrilla')
                ->where('id_supervisor', $userN)
                ->select('id_supervisor')
                ->get();
            $usuario_existe = count($user);

            // ============================================================================
            // Si no existe en la tabla de gop_cuadrilla se busca en gop_supervisor
            // ============================================================================
            if (!$usuario_existe) {
                $user = DB::Table($pre . '_gop_supervisor')
                    ->where('id_supervisor', $userN)
                    ->select('id_supervisor')
                    ->get();
                $usuario_existe = count($user);
            }

            if ($usuario_existe) {
                $user = DB::Table('rh_personas')
                    ->where('identificacion', $userN)
                    ->select('nombres', 'apellidos')
                    ->get();
                $retorno = array("1", $user[0]->nombres . " " . $user[0]->apellidos);
            } else {
                $retorno = array("0", "");
            }
        }

        if ($tipo == 3) //Consulta Fecha de Trabajo
        {
            //Retornar turno
            $fecha = explode(" ", $this->fechaALong)[0];
            $fecha2 = explode(" ", $this->fechaALong)[0];
            $hora = explode(":", explode(" ", $this->fechaALong)[1]);
            $turno1 = "C";
            $turno2 = "C";
            $turnoS = "C";

            $hora1 = 5;
            $minuto1 = 30;

            $hora2 = 17;
            $minuto2 = 31;

            if (isset($request->all()['pre'])) {
                if ($request->all()['pre'] == "rds") {
                    $hora1 = 4;
                    $minuto1 = 30;

                    $hora2 = 23;
                    $minuto2 = 31;
                }
            }

            if (intval($hora[0]) > $hora1) {
                $turno1 = "A";
            } else {
                if (intval($hora[0]) == $hora1) {
                    if (intval($hora[1]) > $minuto1)
                        $turno1 = "A";
                }
            }

            if (intval($hora[0]) < $hora2) {
                $turno2 = "A";
            } else {
                if (intval($hora[0]) == $hora2) {
                    if (intval($hora[1]) < $minuto2)
                        $turno2 = "A";
                }
            }

            if ($turno1 == "A" && $turno2 == "A")
                $turnoS = "A";

            if ($turnoS == "C") {
                if (intval($hora[0]) >= 0 and intval($hora[0]) < 6) //Update
                {
                    $fecha2 = strtotime('-1 day', strtotime($fecha));
                    $fecha2 = date('Y-m-d', $fecha2);
                    $fecha3 = $fecha;
                    $fecha = $fecha2;
                    $fecha2 = $fecha3;
                } else {
                    $fecha2 = strtotime('+1 day', strtotime($fecha));
                    $fecha2 = date('Y-m-d', $fecha2);
                }
            }

            $version = DB::Table('apk_versiones_configuracion')
                ->where('id_aplicacion', 1)//Aplicación de supervisión
                ->value('version_produccion');
            //3$hora =
            $arreglo = "";

            $fecha_menor = strtotime('-1 days', strtotime($fecha));
            $fecha_menor = date('Y-m-j', $fecha_menor);


            $retorno = array($arreglo, $fecha, $turnoS, $fecha2, $version, $fecha_menor);
        }

        if ($tipo == 4) //Consulta Cuadrillas Lider
        {
            $super = $request->all()['super'];
            $pre = $request->all()['pre'];
            $fecha = "";
            $fecha2 = "";
            if (isset($request->all()['fecha1'])) {
                $fecha = $request->all()['fecha1'];
                $fecha2 = $request->all()['fecha2'];
            } else {
                $fecha = $this->fechaShort;
                $fecha2 = $this->fechaShort;
            }

            $fecha3 = "";

            $hora = explode(":", explode(" ", $this->fechaALong)[1]);
            if (intval($hora[0]) >= 0 && intval($hora[0]) < 6) //Entre las 12 y 6 de am
            {
                $fecha3 = $fecha2;
                $fecha = $fecha2;
                $fecha2 = $fecha3;
            }

            if ($pre == "rds") // TODO: COLOCAR PREFIJO APU O CEDULA SUPERVISOR
            {
                if (true) {
                    // $fecha  = "2018-07-06"; $fecha2 = "2018-07-06";

                    $consulta = "SELECT
                              --rds_gop_supervisor.fecha                            AS fecha,
                              --rds_gop_supervisor.fecha_creacion                   AS fecha_creacion,
                              --rds_gop_supervisor.fecha_programacion               AS fecha_programacion,
                              rds_gop_supervisor.movil                            AS id_movil,
                              rds_gop_supervisor.lider                            AS id_lider,
                              rds_gop_supervisor.aux1                             AS id_aux1,
                              rds_gop_supervisor.aux2                             AS id_aux2,
                              rds_gop_supervisor.aux3                             AS id_aux3,

                              rds_gop_supervisor.aux4                             AS id_aux4,
                              rds_gop_supervisor.aux5                             AS id_aux5,
                              rds_gop_supervisor.aux6                             AS id_aux6,
                              
                              (rh_aux4.apellidos  + ' ' +  rh_aux4.nombres)       AS nombre_aux4,
                              (rh_aux5.apellidos  + ' ' +  rh_aux5.nombres)       AS nombre_aux5,
                              (rh_aux6.apellidos  + ' ' +  rh_aux6.nombres)       AS nombre_aux6,


                              rds_gop_supervisor.conductor                        AS id_conductor,
                              rds_gop_supervisor.id_supervisor                    AS id_supervisor,
                              rds_gop_supervisor.estado                           AS id_estado,
                              rds_gop_supervisor.g_tecnico                        AS id_tipo_cuadrilla,
                              rds_gop_tipo_cuadrilla.nombre                       AS nombre_tipo_cuadrilla,
                              rds_gop_supervisor.com                              AS com,
                              (rh_supervisores.apellidos+rh_supervisores.nombres) AS nombre_supervisor,
                              (rh_lideres.apellidos + ' ' + rh_lideres.nombres)   AS nombre_lider,
                              (rh_aux1.apellidos  + ' ' +  rh_aux1.nombres)       AS nombre_aux1,
                              (rh_aux2.apellidos  + ' ' +  rh_aux2.nombres)       AS nombre_aux2,
                              (rh_aux3.apellidos  + ' ' +  rh_aux3.nombres)       AS nombre_aux3,
                              (rh_conductor.apellidos + rh_conductor.nombres)     AS nombre_conductor,
                              rds_gop_supervisor.vehiculo                         AS matricula,
                              rds_gop_supervisor.estado,
                              rds_gop_supervisor.hri,
                              rds_gop_supervisor.hre,
                              rds_gop_tipo_cuadrilla.meta_produccion_pesos        AS meta
                            FROM ( 
                              SELECT * FROM rds_gop_supervisor 
                                WHERE estado = 'A' 
                                AND id_supervisor = '{$super}' 
                                AND lider <> ''
                              ) AS rds_gop_supervisor
                      
                              LEFT JOIN ( 
                                SELECT * FROM rh_personas 
                                WHERE id_estado = 'EP03'
                              ) AS rh_supervisores
                                ON (rh_supervisores.identificacion = rds_gop_supervisor.id_supervisor )
                      
                              LEFT JOIN ( 
                                SELECT * FROM rh_personas 
                                WHERE id_estado = 'EP03'
                              ) AS rh_lideres
                                ON (rh_lideres.identificacion = rds_gop_supervisor.lider )
                          
                              LEFT JOIN ( 
                                SELECT * FROM rh_personas 
                                WHERE id_estado = 'EP03'
                              ) AS rh_aux1
                                ON (rh_aux1.identificacion = rds_gop_supervisor.aux1 )
                       
                              LEFT JOIN ( 
                                SELECT * FROM rh_personas
                              ) AS rh_aux2
                                ON (rh_aux2.identificacion = rds_gop_supervisor.aux2 )
                      
                              LEFT JOIN ( 
                                SELECT * FROM rh_personas
                              ) AS rh_aux3
                                ON (rh_aux3.identificacion = rds_gop_supervisor.aux3 )
                      
                              LEFT JOIN ( 
                                SELECT * FROM rh_personas 
                                WHERE id_estado = 'EP03'
                              ) AS rh_conductor
                                ON (rh_conductor.identificacion = rds_gop_supervisor.conductor )

                              LEFT JOIN ( 
                                SELECT * FROM rh_personas
                              ) AS rh_aux4
                                ON (rh_aux4.identificacion = rds_gop_supervisor.aux4 )

                              LEFT JOIN ( 
                                SELECT * FROM rh_personas
                              ) AS rh_aux5
                                ON (rh_aux5.identificacion = rds_gop_supervisor.aux5 )

                              LEFT JOIN ( 
                                SELECT * FROM rh_personas
                              ) AS rh_aux6
                                ON (rh_aux6.identificacion = rds_gop_supervisor.aux6 )
                      
                              LEFT JOIN (
                                SELECT * FROM rds_gop_tipo_cuadrilla
                              ) AS rds_gop_tipo_cuadrilla
                                ON (rds_gop_tipo_cuadrilla.id_tipo_cuadrilla = rds_gop_supervisor.g_tecnico)
                                      
                            WHERE rds_gop_supervisor.lider IS NOT NULL
                              AND rds_gop_supervisor.fecha_programacion BETWEEN '{$fecha} 00:00:00' AND '{$fecha2} 23:59:59'
                              ORDER BY rds_gop_supervisor.fecha_programacion DESC;";
                } else {
                    $consulta =
                        "
                        SELECT
                        " . $pre . "_gop_cuadrilla.id_movil,
                        " . $pre . "_gop_cuadrilla.id_lider,
                        " . $pre . "_gop_cuadrilla.id_aux1,
                        " . $pre . "_gop_cuadrilla.id_aux2,
                        " . $pre . "_gop_cuadrilla.id_aux3,

                        rds_gop_cuadrilla.id_aux4,
                        rds_gop_cuadrilla.id_aux5,
                        rds_gop_cuadrilla.id_aux6,
                        
                        (rh_aux4.apellidos  + ' ' +  rh_aux4.nombres) AS nombre_aux4,
                        (rh_aux5.apellidos  + ' ' +  rh_aux5.nombres) AS nombre_aux5,
                        (rh_aux6.apellidos  + ' ' +  rh_aux6.nombres) AS nombre_aux6,


                        " . $pre . "_gop_cuadrilla.id_conductor,
                        " . $pre . "_gop_cuadrilla.id_supervisor,
                        " . $pre . "_gop_cuadrilla.id_estado,
                        " . $pre . "_gop_cuadrilla.id_tipo_cuadrilla,
                        " . $pre . "_gop_tipo_cuadrilla.nombre AS nombre_tipo_cuadrilla,
                        " . $pre . "_gop_cuadrilla.com,
                        (rh_supervisores.apellidos+rh_supervisores.nombres) AS nombre_supervisor,
                        (rh_lideres.apellidos + ' ' + rh_lideres.nombres) AS nombre_lider,
                        (rh_aux1.apellidos  + ' ' +  rh_aux1.nombres) AS nombre_aux1,
                        (rh_aux2.apellidos  + ' ' +  rh_aux2.nombres) AS nombre_aux2,
                        (rh_aux3.apellidos  + ' ' +  rh_aux3.nombres) AS nombre_aux3,
                        (rh_conductor.apellidos + rh_conductor.nombres)  AS nombre_conductor,
                        " . $pre . "_gop_cuadrilla.matricula,
                        estado,hri,hre,
                          " . $pre . "_gop_tipo_cuadrilla.meta_produccion_pesos as meta
                      FROM ( SELECT * FROM " . $pre . "_gop_cuadrilla where id_estado = 'A' and id_supervisor = '$super' and id_lider <> '') AS " . $pre . "_gop_cuadrilla
                        LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_supervisores
                          ON (rh_supervisores.identificacion = " . $pre . "_gop_cuadrilla.id_supervisor )
                        LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_lideres
                          ON (rh_lideres.identificacion = " . $pre . "_gop_cuadrilla.id_lider )
                        LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux1
                          ON (rh_aux1.identificacion = " . $pre . "_gop_cuadrilla.id_aux1 )
                        LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux2
                          ON (rh_aux2.identificacion = " . $pre . "_gop_cuadrilla.id_aux2 )
                        LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux3
                          ON (rh_aux3.identificacion = " . $pre . "_gop_cuadrilla.id_aux3 )
                        LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_conductor
                          ON (rh_conductor.identificacion = " . $pre . "_gop_cuadrilla.id_conductor )

                        LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux4
                          ON (rh_aux4.identificacion = rds_gop_cuadrilla.id_aux4 )

                          LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux5
                          ON (rh_aux5.identificacion = rds_gop_cuadrilla.id_aux5 )

                          LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux6
                          ON (rh_aux6.identificacion = rds_gop_cuadrilla.id_aux6 )


                          
                        LEFT JOIN (SELECT * FROM " . $pre . "_gop_tipo_cuadrilla) AS " . $pre . "_gop_tipo_cuadrilla
                          ON (" . $pre . "_gop_tipo_cuadrilla.id_tipo_cuadrilla = " . $pre . "_gop_cuadrilla.id_tipo_cuadrilla)
                        LEFT JOIN (
                          select 
                          movil,estado,hri,hre
                          from " . $pre . "_gop_supervisor 
                          where id_supervisor = '$super'
                          AND fecha_creacion BETWEEN '$fecha' AND '$fecha2'
                        ) AS captura ON captura.movil = " . $pre . "_gop_cuadrilla.id_movil
                          WHERE id_lider IS NOT NULL
                  ";
                }
            } else {
                $consulta =
                    "
                  SELECT
                  " . $pre . "_gop_cuadrilla.id_movil,
                  " . $pre . "_gop_cuadrilla.id_lider,
                  " . $pre . "_gop_cuadrilla.id_aux1,
                  " . $pre . "_gop_cuadrilla.id_aux2,
                  " . $pre . "_gop_cuadrilla.id_aux3,
                  " . $pre . "_gop_cuadrilla.id_conductor,
                  " . $pre . "_gop_cuadrilla.id_supervisor,
                  " . $pre . "_gop_cuadrilla.id_estado,
                  " . $pre . "_gop_cuadrilla.id_tipo_cuadrilla,
                  " . $pre . "_gop_tipo_cuadrilla.nombre AS nombre_tipo_cuadrilla,
                  " . $pre . "_gop_cuadrilla.com,
                  
                  (' ') AS id_aux4,
                  (' ') AS id_aux5,
                  (' ') AS id_aux6,
                  (' ') AS nombre_aux4,
                  (' ') AS nombre_aux5,
                  (' ') AS nombre_aux6,

                  (rh_supervisores.apellidos+rh_supervisores.nombres) AS nombre_supervisor,
                  (rh_lideres.apellidos + ' ' + rh_lideres.nombres) AS nombre_lider,
                  (rh_aux1.apellidos  + ' ' +  rh_aux1.nombres) AS nombre_aux1,
                  (rh_aux2.apellidos  + ' ' +  rh_aux2.nombres) AS nombre_aux2,
                  (rh_aux3.apellidos  + ' ' +  rh_aux3.nombres) AS nombre_aux3,
                  (rh_conductor.apellidos + rh_conductor.nombres)  AS nombre_conductor,
                  " . $pre . "_gop_cuadrilla.matricula,
                  estado,hri,hre,
                    " . $pre . "_gop_tipo_cuadrilla.meta_produccion_pesos as meta
                FROM ( SELECT * FROM " . $pre . "_gop_cuadrilla where id_estado = 'A' and id_supervisor = '$super') AS " . $pre . "_gop_cuadrilla
                  LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_supervisores
                    ON (rh_supervisores.identificacion = " . $pre . "_gop_cuadrilla.id_supervisor )
                  LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_lideres
                    ON (rh_lideres.identificacion = " . $pre . "_gop_cuadrilla.id_lider )
                  LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux1
                    ON (rh_aux1.identificacion = " . $pre . "_gop_cuadrilla.id_aux1 )
                  LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux2
                    ON (rh_aux2.identificacion = " . $pre . "_gop_cuadrilla.id_aux2 )
                  LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux3
                    ON (rh_aux3.identificacion = " . $pre . "_gop_cuadrilla.id_aux3 )
                  LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_conductor
                    ON (rh_conductor.identificacion = " . $pre . "_gop_cuadrilla.id_conductor )
                  LEFT JOIN (SELECT * FROM " . $pre . "_gop_tipo_cuadrilla) AS " . $pre . "_gop_tipo_cuadrilla
                    ON (" . $pre . "_gop_tipo_cuadrilla.id_tipo_cuadrilla = " . $pre . "_gop_cuadrilla.id_tipo_cuadrilla)
                  LEFT JOIN (
                    select 
                    movil,estado,hri,hre
                    from " . $pre . "_gop_supervisor 
                    where id_supervisor = '$super'
                    AND fecha_creacion BETWEEN '$fecha' AND '$fecha2'
                  ) AS captura ON captura.movil = " . $pre . "_gop_cuadrilla.id_movil
                    WHERE id_lider IS NOT NULL
            ";
            }


            //dd($consulta);
            $retorno = DB::select($consulta);
        }

        if ($tipo == 5) //Consulta solo un líder
        {
            $super = $request->all()['super'];
            $pre = $request->all()['pre'];
            $fecha = $this->fechaShort;
            $movil = $request->all()['movil'];

            $consulta =
                "           
                  SELECT
                    " . $pre . "_gop_cuadrilla.id_movil,
                    " . $pre . "_gop_cuadrilla.id_lider,
                    " . $pre . "_gop_cuadrilla.id_aux1,
                    " . $pre . "_gop_cuadrilla.id_aux2,
                    " . $pre . "_gop_cuadrilla.id_aux3,
                    " . $pre . "_gop_cuadrilla.id_conductor,
                    " . $pre . "_gop_cuadrilla.id_supervisor,
                    " . $pre . "_gop_cuadrilla.id_estado,
                    " . $pre . "_gop_cuadrilla.id_tipo_cuadrilla,
                    " . $pre . "_gop_tipo_cuadrilla.nombre AS nombre_tipo_cuadrilla,
                    " . $pre . "_gop_cuadrilla.com,
                    (rh_supervisores.apellidos+rh_supervisores.nombres) AS nombre_supervisor,
                    (rh_lideres.apellidos + ' ' + rh_lideres.nombres) AS nombre_lider,
                    (rh_aux1.apellidos  + ' ' +  rh_aux1.nombres) AS nombre_aux1,
                    (rh_aux2.apellidos  + ' ' +  rh_aux2.nombres) AS nombre_aux2,
                    (rh_aux3.apellidos  + ' ' +  rh_aux3.nombres) AS nombre_aux3,
                    (rh_conductor.apellidos + rh_conductor.nombres)  AS nombre_conductor,
                    " . $pre . "_gop_cuadrilla.matricula,
                    estado,hri,hre,liderA,aux1A,aux2A,aux3A,conductorA,vehiculoA,estadoA,
                    id_turnoA,nombre_aux1A,nombre_aux2A,nombre_aux3A,nombre_conductorA
                FROM ( SELECT * FROM " . $pre . "_gop_cuadrilla where id_estado = 'A' and id_supervisor = '$super') AS " . $pre . "_gop_cuadrilla
                    LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_supervisores
                    ON (rh_supervisores.identificacion = " . $pre . "_gop_cuadrilla.id_supervisor )
                    LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_lideres
                    ON (rh_lideres.identificacion = " . $pre . "_gop_cuadrilla.id_lider )
                    LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux1
                    ON (rh_aux1.identificacion = " . $pre . "_gop_cuadrilla.id_aux1 )
                    LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux2
                    ON (rh_aux2.identificacion = " . $pre . "_gop_cuadrilla.id_aux2 )
                    LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux3
                    ON (rh_aux3.identificacion = " . $pre . "_gop_cuadrilla.id_aux3 )
                    LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_conductor
                    ON (rh_conductor.identificacion = " . $pre . "_gop_cuadrilla.id_conductor )
                    LEFT JOIN (SELECT * FROM " . $pre . "_gop_tipo_cuadrilla) AS " . $pre . "_gop_tipo_cuadrilla
                    ON (" . $pre . "_gop_tipo_cuadrilla.id_tipo_cuadrilla = " . $pre . "_gop_cuadrilla.id_tipo_cuadrilla)
                    LEFT JOIN (
                        SELECT movil,estado,hri,hre,lider as liderA,aux1 as aux1A,aux2 as aux2A,aux3 as aux3A
                        ,conductor as conductorA,vehiculo as vehiculoA,estado as estadoA
                        ,id_turno as  id_turnoA,
                        (rh_aux1A.apellidos  + ' ' +  rh_aux1A.nombres) AS nombre_aux1A,
                            (rh_aux2A.apellidos  + ' ' +  rh_aux2A.nombres) AS nombre_aux2A,
                            (rh_aux3A.apellidos  + ' ' +  rh_aux3A.nombres) AS nombre_aux3A,
                            (rh_conductorA.apellidos + rh_conductorA.nombres)  AS nombre_conductorA
                        FROM " . $pre . "_gop_supervisor
                        LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux1A
                            ON (rh_aux1A.identificacion = " . $pre . "_gop_supervisor.aux1 )
                            LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux2A
                            ON (rh_aux2A.identificacion = " . $pre . "_gop_supervisor.aux2 )
                            LEFT JOIN ( SELECT * FROM rh_personas  ) AS rh_aux3A
                            ON (rh_aux3A.identificacion = " . $pre . "_gop_supervisor.aux3 )
                            LEFT JOIN ( SELECT * FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_conductorA
                            ON (rh_conductorA.identificacion = " . $pre . "_gop_supervisor.conductor )
                        where id_supervisor = '$super'
                            AND fecha_creacion = '$fecha'    
                    ) AS captura ON captura.movil = " . $pre . "_gop_cuadrilla.id_movil
                  WHERE '" . $pre . "_gop_cuadrilla.id_movil = '$movil'
            ";

            $retorno = DB::select($consulta);
        }

        if ($tipo == 6) //Consulta Maestros Turnos,Lideres,Auxiliares,Vehículos,Criticidad
        {
            $pre = $request->all()['pre'];
            $arreglo = [];
            $tipoTurno = DB::table($pre . '_gop_turno_cuadrilla')
                ->whereIn('id_turno', ['T01', 'T02'])
                ->select('cod_turno', 'nombre')
                ->orderBy('id_turno')
                ->get();

            $lideres = DB::table($pre . '_gop_cuadrilla')
                ->join('rh_personas', function ($join) use ($pre) {
                    $join->on($pre . '_gop_cuadrilla.id_lider', '=', 'rh_personas.identificacion');
                })
                ->select($pre . '_gop_cuadrilla.id_lider', 'rh_personas.apellidos', 'rh_personas.nombres')
                ->get();
            // ->where('rh_personas.id_estado','=','EP03');

            $vehiculos = DB::table($pre . '_gop_cuadrilla')
                ->where('matricula', '<>', '')
                ->select('matricula')
                ->groupBy('matricula')
                ->get();

            $vehiculos = DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->select('placa as matricula', 'id_estado')
                ->groupBy('placa', 'id_estado')
                ->get();

            $tipos_cuadrillas = array();

            if ($pre === 'apu' || $pre === 'rds') {
                $tipos_cuadrillas = DB::table($pre . '_gop_tipo_cuadrilla')
                    ->select('id_tipo_cuadrilla', 'nombre')
                    ->get();
            }

            $consulta =
                "               
                SELECT user_doc as id ,user_nombre as nombre FROM
                (
                SELECT
                        identificacion AS user_doc,
                        (rh_lideres.nombres + ' ' + rh_lideres.apellidos) AS user_nombre
                    FROM " . $pre . "_gop_cuadrilla
                        LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_lideres
                        ON (rh_lideres.identificacion = " . $pre . "_gop_cuadrilla.id_lider )
                    GROUP BY identificacion,apellidos,nombres
                UNION 
                SELECT
                        identificacion AS user_doc,
                        (rh_aux1.nombres  + ' ' +  rh_aux1.apellidos) AS user_nombre
                    FROM " . $pre . "_gop_cuadrilla
                        INNER JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux1
                        ON (rh_aux1.identificacion = " . $pre . "_gop_cuadrilla.id_aux1 )
                     GROUP BY identificacion,apellidos,nombres
                UNION
                SELECT
                        identificacion AS user_doc,
                        (rh_aux2.nombres  + ' ' +  rh_aux2.apellidos) AS user_nombre
                    FROM " . $pre . "_gop_cuadrilla
                        LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux2
                        ON (rh_aux2.identificacion = " . $pre . "_gop_cuadrilla.id_aux2 )
                    GROUP BY identificacion,apellidos,nombres
                UNION 
                SELECT
                        identificacion AS user_doc,
                        (rh_aux3.nombres  + ' ' +  rh_aux3.apellidos) AS user_nombre
                FROM " . $pre . "_gop_cuadrilla
                    LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux3
                    ON (rh_aux3.identificacion = " . $pre . "_gop_cuadrilla.id_aux3 )
                    GROUP BY identificacion,apellidos,nombres
                UNION 
                SELECT identificacion AS user_doc,
                        (rh_conductor.nombres  + ' ' +  rh_conductor.apellidos) AS user_nombre
                FROM " . $pre . "_gop_cuadrilla
                    LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_conductor
                    ON (rh_conductor.identificacion = " . $pre . "_gop_cuadrilla.id_conductor )
                    GROUP BY identificacion,apellidos,nombres

                UNION

                SELECT identificacion AS user_doc,
                        (rh_bodega.nombres  + ' ' +  rh_bodega.apellidos) AS user_nombre 
                FROM " . $pre . "_inv_bodegas
                    LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_bodega
                    ON (rh_bodega.identificacion = " . $pre . "_inv_bodegas.id_bodega )
                    GROUP BY rh_bodega.nombres,rh_bodega.apellidos,identificacion
                ) as tbl
                WHERE user_doc IS NOT NULL
                GROUP BY user_doc,user_nombre
                ORDER BY user_doc
            ";
            $auxiliares = DB::select($consulta);


            $versionActual = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 1)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $consultaFormulario = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 1)
                ->where('version', $versionActual)
                ->select('item_num', 'id_pregunta', 'descrip_pregunta', 'obligatorio', 'tipo_control', 'version as ver')
                ->get();

            $versionActualCalidad = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 2)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $consultaFormularioCalidad = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 2)
                ->where('version', $versionActualCalidad)
                ->select('item_num', 'id_pregunta', 'descrip_pregunta', 'obligatorio', 'tipo_control', 'version as ver')
                ->get();


            $criti = DB::Table('ins_criticidad')
                ->select('nombre', 'rango_1', 'rango_2', 'descripcion_criticidad')
                ->orderBy('id', 'asc')
                ->get();

            //Retornar turno
            $fecha = explode(" ", $this->fechaALong)[0];
            $fecha2 = explode(" ", $this->fechaALong)[0];
            $hora = explode(":", explode(" ", $this->fechaALong)[1]);
            $turno1 = "C";
            $turno2 = "C";
            $turnoS = "C";

            $hora1 = 5;
            $minuto1 = 30;

            $hora2 = 17;
            $minuto2 = 31;

            /*$hora1 = 17;
            $minuto1 = 30;

            $hora2 = 20;
            $minuto2 = 31;*/

            if (intval($hora[0]) > $hora1) {
                $turno1 = "A";
            } else {
                if (intval($hora[0]) == $hora1) {
                    if (intval($hora[1]) > $minuto1)
                        $turno1 = "A";
                }
            }

            if (intval($hora[0]) < $hora2) {
                $turno2 = "A";
            } else {
                if (intval($hora[0]) == $hora2) {
                    if (intval($hora[1]) < $minuto2)
                        $turno2 = "A";
                }
            }

            if ($turno1 == "A" && $turno2 == "A")
                $turnoS = "A";

            if ($turnoS == "C") {
                if (intval($hora[0]) >= 0 and intval($hora[0]) < 6) //Update
                {
                    $fecha2 = strtotime('-1 day', strtotime($fecha));
                    $fecha2 = date('Y-m-d', $fecha2);
                    $fecha3 = $fecha;
                    $fecha = $fecha2;
                    $fecha2 = $fecha3;
                } else {
                    $fecha2 = strtotime('+1 day', strtotime($fecha));
                    $fecha2 = date('Y-m-d', $fecha2);
                }
            }

            array_push(
                $arreglo,
                $tipoTurno,
                $lideres,
                $vehiculos,
                $auxiliares,
                $consultaFormulario,
                $criti,
                $consultaFormularioCalidad,
                $fecha,
                $turnoS,
                $fecha2,
                $tipos_cuadrillas
            );

            $retorno = $arreglo;
        }

        if ($tipo == 7) //Consulta personal disponible
        {
            $pre = $request->all()['pre'];
            $fecha = $this->fechaShort;
            $consulta =
                "
            SELECT user_doc as id FROM
                (
                    SELECT
                        identificacion AS user_doc,
                        (rh_lideres.apellidos + ' ' + rh_lideres.nombres) AS user_nombre
                    FROM " . $pre . "_gop_cuadrilla
                        LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_lideres
                        ON (rh_lideres.identificacion = " . $pre . "_gop_cuadrilla.id_lider )
                    INNER JOIN ins_gop_inasistencia ON ins_gop_inasistencia.tecnico = identificacion
                    AND fecha_inasistencia = '$fecha'
                    AND motivo IN ('1','2')
                    INNER JOIN " . $pre . "_gop_supervisor ON " . $pre . "_gop_supervisor.lider = identificacion                      
                    AND estado <> 'A' 
                    AND fecha_creacion = '$fecha'
                    GROUP BY identificacion,apellidos,nombres
                UNION 
                SELECT
                        identificacion AS user_doc,
                        (rh_aux1.apellidos  + ' ' +  rh_aux1.nombres) AS user_nombre
                    FROM " . $pre . "_gop_cuadrilla
                        INNER JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux1
                        ON (rh_aux1.identificacion = " . $pre . "_gop_cuadrilla.id_aux1 )
                        INNER JOIN ins_gop_inasistencia ON ins_gop_inasistencia.tecnico = identificacion
                    AND fecha_inasistencia = '$fecha'
                    AND motivo IN ('1','2')                      
                    INNER JOIN " . $pre . "_gop_supervisor ON " . $pre . "_gop_supervisor.aux1 = identificacion                      
                    AND estado <> 'A'
                    AND fecha_creacion = '$fecha'
                    GROUP BY identificacion,apellidos,nombres
                UNION
                SELECT
                        identificacion AS user_doc,
                        (rh_aux2.apellidos  + ' ' +  rh_aux2.nombres) AS user_nombre
                    FROM " . $pre . "_gop_cuadrilla
                        LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux2
                        ON (rh_aux2.identificacion = " . $pre . "_gop_cuadrilla.id_aux2 )
                    INNER JOIN ins_gop_inasistencia ON ins_gop_inasistencia.tecnico = identificacion
                    AND fecha_inasistencia = '$fecha'
                    AND motivo IN ('1','2')                      
                    INNER JOIN " . $pre . "_gop_supervisor ON " . $pre . "_gop_supervisor.aux2 = identificacion                      
                    AND estado <> 'A'
                    AND fecha_creacion = '$fecha'
                    GROUP BY identificacion,apellidos,nombres
                UNION 
                SELECT
                        identificacion AS user_doc,
                        (rh_aux3.apellidos  + ' ' +  rh_aux3.nombres) AS user_nombre
                FROM " . $pre . "_gop_cuadrilla
                    LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_aux3
                    ON (rh_aux3.identificacion = " . $pre . "_gop_cuadrilla.id_aux3 )
                        INNER JOIN ins_gop_inasistencia ON ins_gop_inasistencia.tecnico = identificacion
                    AND fecha_inasistencia = '$fecha'
                    AND motivo IN ('1','2')      
                    INNER JOIN " . $pre . "_gop_supervisor ON " . $pre . "_gop_supervisor.aux3 = identificacion                      
                    AND estado <> 'A'                
                    AND fecha_creacion = '$fecha'
                    GROUP BY identificacion,apellidos,nombres
                UNION 
                SELECT identificacion AS user_doc,
                        (rh_conductor.apellidos  + ' ' +  rh_conductor.nombres) AS user_nombre
                FROM " . $pre . "_gop_cuadrilla
                    LEFT JOIN ( SELECT identificacion,apellidos,nombres FROM rh_personas WHERE id_estado = 'EP03' ) AS rh_conductor
                    ON (rh_conductor.identificacion = " . $pre . "_gop_cuadrilla.id_conductor )
                        INNER JOIN ins_gop_inasistencia ON ins_gop_inasistencia.tecnico = identificacion
                    AND fecha_inasistencia = '$fecha'
                    AND motivo IN ('1','2')                      
                    INNER JOIN " . $pre . "_gop_supervisor ON " . $pre . "_gop_supervisor.conductor = identificacion                      
                    AND estado <> 'A'
                    AND fecha_creacion = '$fecha'
                    GROUP BY identificacion,apellidos,nombres
                ) as tbl
                WHERE user_doc IS NOT NULL
                GROUP BY user_doc,user_nombre
                ORDER BY user_doc
            ";

            $retorno = DB::select($consulta);
        }

        if ($tipo == 8) //Consulta GPS Cuadrillas
        {
            $pre = $request->all()['pre'];
            $cuad = $request->all()['cuad'];
            $arreglo = [];

            $fecha1 = $this->fechaShort;
            $fecha2 = $this->fechaShort;

            if (isset($request->all()['fecha1'])) {
                $fecha1 = $request->all()['fecha1'];
                $fecha2 = $request->all()['fecha2'];
            }

            $turno = "";

            for ($i = 0; $i < count($cuad); $i++) {

                if ($pre == "rds") {
                    array_push($arreglo,
                        [
                            "gps" => DB::Table($pre . '_gps_tracker')
                                ->where('usuario_movil', $cuad[$i])
                                ->where('prefijo', $pre)
                                ->select(DB::raw('top(1)prefijo'), 'latitud', 'longitud', 'usuario_movil', 'fecha', 'hora')
                                ->orderBy('fecha', 'desc')
                                ->orderBy('hora', 'desc')
                                ->get(),

                            "valor" => DB::Table($pre . '_gop_ordenes')
                                ->where($pre . '_gop_mobra.id_origen', $cuad[$i])
                                ->whereBetween('fecha_ejecucion', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:00"])
                                ->leftjoin($pre . '_gop_mobra', $pre . '_gop_mobra.id_orden', '=', $pre . '_gop_ordenes.id_orden')
                                ->select(DB::raw('SUM(' . $pre . '_gop_mobra.cantidad * ' . $pre . '_gop_mobra.precio) as valor'))
                                ->get()[0]->valor
                        ]);
                } else {
                    array_push($arreglo,
                        [
                            "gps" => DB::Table($pre . '_gps_tracker')
                                ->where('usuario_movil', $cuad[$i])
                                ->where('prefijo', $pre)
                                ->select(DB::raw('top(1)prefijo'), 'latitud', 'longitud', 'usuario_movil', 'fecha', 'hora')
                                ->orderBy('fecha', 'desc')
                                ->orderBy('hora', 'desc')
                                ->get(),

                            "valor" => DB::Table($pre . '_gop_ordenes')
                                ->where($pre . '_gop_ordenes.id_origen', $cuad[$i])
                                ->where($pre . '_gop_ordenes.turno', 'LIKE', '%' . $turno . '%')
                                ->whereBetween('fecha_programacion', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:00"])
                                ->leftjoin($pre . '_gop_mobra', $pre . '_gop_mobra.id_orden', '=', $pre . '_gop_ordenes.id_orden')
                                ->select(DB::raw('SUM(' . $pre . '_gop_mobra.cantidad * ' . $pre . '_gop_mobra.precio) as valor'))
                                ->get()[0]->valor
                        ]);
                }


            }

            $retorno = $arreglo;
        }

        if ($tipo == 9) //Consulta Ordenes y GPS cuadrillas
        {
            $pre = $request->all()['pre'];
            $lider = $request->all()['lider'];
            $turno = $request->all()['turno'];
            $fecha1 = $request->all()['fecha'];

            //$fecha1 = "2017-11-25";
            $fecha2 = $request->all()['fecha2'];
            $turno = "";
            $fecha = $this->fechaShort;
            //$fecha = "2017-05-05";
            // ->where($pre . '_gop_ordenes.turno','LIKE','%' . $turno . '%')

            if ($pre == "rds") {
                $retorno = DB::Table($pre . '_gop_ordenes')
                    ->where($pre . '_gop_ordenes_manoobra_detalle.id_lider', $lider)
                    ->whereBetween('fecha_ejecucion', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:00"])
                    ->join($pre . '_gop_tipo_orden', $pre . '_gop_tipo_orden.id_tipo', '=', $pre . '_gop_ordenes.id_tipo')
                    ->join($pre . '_gop_ordenes_manoobra_detalle', $pre . '_gop_ordenes_manoobra_detalle.id_orden', '=', $pre . '_gop_ordenes.id_orden')
                    ->leftJoin($pre . '_gop_mobra', function ($query) use ($lider, $pre) {
                        $query->on($pre . '_gop_mobra.id_orden', '=', $pre . '_gop_ordenes.id_orden')
                            ->where($pre . '_gop_mobra.id_origen', '=', $lider);
                    })
                    ->join('gop_estado_ordenes', 'gop_estado_ordenes.id_estado', '=', $pre . '_gop_ordenes.id_estado')
                    ->join($pre . '_gop_proyectos as tbl1', 'tbl1.id_proyecto', '=', $pre . '_gop_ordenes.id_proyecto')
                    ->join($pre . '_gop_circuitos as tbl2', 'tbl1.cod_cto', '=', 'tbl2.id_circuito')
                    ->select($pre . '_gop_ordenes.id_orden',
                        $pre . '_gop_ordenes.id_tipo'
                        , $pre . '_gop_ordenes.direccion', 'fecha_programacion', 'gop_estado_ordenes.id_estado',
                        'fecha_ejecucion', $pre . '_gop_ordenes.turno',
                        DB::raw("(" . $pre . '_gop_tipo_orden.nombre' . " +  ' <br><b>PROYECTO:</b>'  + tbl1.nombre + ' <br><b>CIRCUITO:</b>' + tbl2.nombre_cto + ' <br><b>WBS</b>:' + " . $pre . "_gop_ordenes.wbs_utilzadas +  ' <br><b>NODOS:</b>' + " . $pre . "_gop_ordenes.nodos_utilizados +  ' <br><b>GOM:</b>' + " . $pre . "_gop_ordenes.gom +  ' <br><b>DESCARGO 1:</b>' + IIF(" . $pre . "_gop_ordenes.descargo = 0,'NA'," . $pre . "_gop_ordenes.descargo) +  ' <br><b>DESCARGO 2:</b>' + IIF(" . $pre . "_gop_ordenes.descargo2 = 0,'NA'," . $pre . "_gop_ordenes.descargo2) +  ' <br><b>HORA INICIO:</b>' + " . $pre . "_gop_ordenes_manoobra_detalle.hora_ini +  ' <br><b>HORA FIN:</b>' + " . $pre . "_gop_ordenes_manoobra_detalle.hora_fin" . ") as nombre")
                        , 'gop_estado_ordenes.nombre as nombreE',
                        'x', 'y', DB::raw('SUM(' . $pre . '_gop_mobra.cantidad * ' . $pre . '_gop_mobra.precio) as valor'), $pre . '_gop_ordenes.fecha_ejecucion as fecha_hora', $pre . '_gop_ordenes.observaciones',
                        DB::raw("'null' as latitud"), DB::raw("'null' as longitud"))
                    ->groupBy($pre . '_gop_ordenes.id_orden', $pre . '_gop_ordenes.id_tipo', $pre . '_gop_ordenes.direccion', 'fecha_programacion', 'gop_estado_ordenes.id_estado',
                        'fecha_ejecucion', $pre . '_gop_ordenes.turno', $pre . '_gop_tipo_orden.nombre', 'gop_estado_ordenes.nombre',
                        'x', 'y', $pre . '_gop_ordenes.fecha_ejecucion', $pre . '_gop_ordenes.observaciones', 'tbl1.nombre', 'tbl2.nombre_cto', $pre . '_gop_ordenes.wbs_utilzadas', $pre . "_gop_ordenes.nodos_utilizados", $pre . "_gop_ordenes.gom", $pre . "_gop_ordenes.descargo2", $pre . "_gop_ordenes.descargo", $pre . "_gop_ordenes_manoobra_detalle.hora_fin", $pre . "_gop_ordenes_manoobra_detalle.hora_ini")
                    ->orderBy('id_orden', 'asc')
                    ->get();
            } else {
                $retorno = DB::Table($pre . '_gop_ordenes')
                    ->where($pre . '_gop_ordenes.id_origen', $lider)
                    ->whereBetween('fecha_programacion', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:00"])
                    ->join($pre . '_gop_tipo_orden', $pre . '_gop_tipo_orden.id_tipo', '=', $pre . '_gop_ordenes.id_tipo')
                    ->leftjoin($pre . '_gop_mobra', $pre . '_gop_mobra.id_orden', '=', $pre . '_gop_ordenes.id_orden')
                    ->join('gop_estado_ordenes', 'gop_estado_ordenes.id_estado', '=', $pre . '_gop_ordenes.id_estado')
                    ->select($pre . '_gop_ordenes.id_orden', $pre . '_gop_ordenes.id_tipo', 'direccion', 'fecha_programacion', 'gop_estado_ordenes.id_estado',
                        'fecha_ejecucion', $pre . '_gop_ordenes.turno', $pre . '_gop_tipo_orden.nombre', 'gop_estado_ordenes.nombre as nombreE',
                        'x', 'y', DB::raw('SUM(' . $pre . '_gop_mobra.cantidad * ' . $pre . '_gop_mobra.precio) as valor'), $pre . '_gop_ordenes.fecha_hora', $pre . '_gop_ordenes.observaciones')
                    ->groupBy($pre . '_gop_ordenes.id_orden', $pre . '_gop_ordenes.id_tipo', 'direccion', 'fecha_programacion', 'gop_estado_ordenes.id_estado',
                        'fecha_ejecucion', $pre . '_gop_ordenes.turno', $pre . '_gop_tipo_orden.nombre', 'gop_estado_ordenes.nombre',
                        'x', 'y', $pre . '_gop_ordenes.fecha_hora', $pre . '_gop_ordenes.observaciones')
                    ->orderBy('id_orden', 'asc')
                    ->get();
            }

        }

        if ($tipo == 10) //Consulta Ejecución
        {

            // fecha1
            // fecha2 Valida sobre los baremos del día
            $pre = $request->all()['pre'];
            $orden = $request->all()['orden'];

            if ($pre == "rds") {
                $consulta =
                    "
                select t1.id_orden,t3.codigo as id_baremo,t2.cantidad,t3.codigo,t3.actividad,t2.id_nodo,nodo.nombre_nodo from
                " . $pre . "_gop_ordenes t1 inner join " . $pre . "_gop_mobra t2 ON t1.id_orden = t2.id_orden
                inner join " . $pre . "_gop_baremos t3 ON t3.id_baremo = t2.id_baremo
                inner join " . $pre . "_gop_nodos nodo ON nodo.id_nodo = t2.id_nodo
                where t1.id_orden = '" . $orden . "'
                    and t3.periodo = 2018
                    and t3.grupo = 'REDES'
                group by t1.id_orden,t2.id_baremo,t2.cantidad,t3.codigo,t3.actividad,t2.id_nodo,nodo.nombre_nodo
                ORDER BY nodo.nombre_nodo,t3.codigo
                ";
            } else {
                $consulta =
                    "
                select t1.id_orden,t2.id_baremo,t2.cantidad,t3.codigo,t3.actividad from
                " . $pre . "_gop_ordenes t1 inner join " . $pre . "_gop_mobra t2 ON t1.id_orden = t2.id_orden
                inner join " . $pre . "_gop_baremos t3 ON t3.id_baremo = t2.id_baremo
                where t1.id_orden = '" . $orden . "'
                group by t1.id_orden,t2.id_baremo,t2.cantidad,t3.codigo,t3.actividad
                ";
            }


            $parte1 = DB::select($consulta);

            if ($pre == "apu") {
                $consulta =
                    "
                select id_orden,t2.id_estado,t2.id_documento,t3.id_articulo,SUM(t3.cantidad) AS cantidad,t4.codigo_sap,
                    t4.nombre,SUM(r_ch) as r_ch,SUM(r_rz) as r_rz
                    from " . $pre . "_inv_documentos t2 
                    inner join " . $pre . "_inv_detalle_documentos t3 on t2.id_documento = t3.id_documento
                    INNER JOIN " . $pre . "_inv_maestro_articulos t4 ON t4.id_articulo = t3.id_articulo
                    where id_orden = '" . $orden . "'
                    GROUP BY id_orden,t2.id_estado,t2.id_documento,t3.id_articulo,t4.codigo_sap,t4.nombre
                ";
            } else {

                if ($pre == "rds") {
                    $consulta =
                        "
                    select id_orden,t2.id_estado,t2.id_documento,t3.id_articulo,ISNULL(t3.consumo,0) AS cantidad,t4.codigo_sap,
                        t4.nombre,ISNULL(i_rz,0) as i_rz,ISNULL(r_ch,0) as r_ch,ISNULL(r_rz,0) as r_rz,t3.id_nodo,nodo.nombre_nodo
                        from " . $pre . "_inv_documentos t2 
                        inner join " . $pre . "_inv_detalle_documentos t3 on t2.id_documento = t3.id_documento
                        INNER JOIN " . $pre . "_inv_maestro_articulos t4 ON t4.id_articulo = t3.id_articulo
                        inner join " . $pre . "_gop_nodos nodo ON nodo.id_nodo = t3.id_nodo
                        where id_orden = '" . $orden . "'
                        and t2.id_tipo_movimiento = 'T007'
                        GROUP BY id_orden,t2.id_estado,t2.id_documento,t3.id_articulo,t4.codigo_sap,t4.nombre,t3.id_nodo,i_rz,r_ch,r_rz,t3.consumo,nodo.nombre_nodo
                        ORDER BY nodo.nombre_nodo,t4.codigo_sap
                    ";
                } else {
                    "
                    select id_orden,t2.id_estado,t2.id_documento,t3.id_articulo,ISNULL(SUM(t3.cantidad),0) AS cantidad,t4.codigo_sap,
                        t4.nombre,'' as r_ch,'0' as r_rz
                        from " . $pre . "_inv_documentos t2 
                        inner join " . $pre . "_inv_detalle_documentos t3 on t2.id_documento = t3.id_documento
                        INNER JOIN " . $pre . "_inv_maestro_articulos t4 ON t4.id_articulo = t3.id_articulo
                        where id_orden = '" . $orden . "'
                        GROUP BY id_orden,t2.id_estado,t2.id_documento,t3.id_articulo,t4.codigo_sap,t4.nombre
                    ";
                }
            }


            $fotos = [];

            if (isset($request->all()['fecha1'])) {
                if ($pre == "rds") {

                    if (isset($request->all()['usuario'])) {
                        $fotos = DB::Table($pre . '_gop_fotos')
                            ->where('id_orden', $orden)
                            ->where('usuario', $request->all()['usuario'])
                            ->select(DB::raw("('http://201.217.195.35/tprog/fotos/ordenes/' + ruta) as ruta"))
                            ->get();
                    } else {
                        $fotos = DB::Table($pre . '_gop_fotos')
                            ->where('id_orden', $orden)
                            ->select(DB::raw("('http://201.217.195.35/tprog/fotos/ordenes/' + ruta) as ruta"))
                            ->get();
                    }

                } else {

                    if ($pre == "apu") {
                        $fotos = DB::Table($pre . '_gop_fotos')
                            ->where('id_orden', $orden)
                            ->whereBetween('fecha', [$request->all()['fecha1'], $request->all()['fecha2']])
                            ->select(DB::raw("('http://190.60.248.195/fotos/apu/ordenes/' + ruta) as ruta"))
                            ->get();
                    } else {
                        $fotos = DB::Table($pre . '_gop_fotos')
                            ->where('id_orden', $orden)
                            ->whereBetween('fecha', [$request->all()['fecha1'], $request->all()['fecha2']])
                            ->select(DB::raw("('http://201.217.195.35/apu/tprog/fotos/ordenes/' + ruta) as ruta"))
                            ->get();
                    }
                }

            }

            $apro = "";
            $obser = "";
            if ($pre == "rds") {
                if (isset($request->all()['usuario'])) {
                    $apro = DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                        ->where('id_lider', $request->all()['usuario'])
                        ->where('id_orden', $orden)
                        ->value('aprobacion');

                    $obser = DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                        ->where('id_lider', $request->all()['usuario'])
                        ->where('id_orden', $orden)
                        ->value('observacion_aprueba');
                }
            } else {
                $apro = DB::Table($pre . '_gop_ordenes')
                    ->where('id_orden', $orden)
                    ->value('aprobacion');

                $obser = DB::Table($pre . '_gop_ordenes')
                    ->where('id_orden', $orden)
                    ->value('observacion_aprueba');
            }


            $parte2 = DB::select($consulta);
            $retorno = array($parte1, $parte2, $fotos, $apro, $obser);
        }

        if ($tipo == 11) //Consulta ordenes todas las cuadrillas
        {
            $pre = $request->all()['pre'];
            $lideres = $request->all()['cuad'];
            $turno = $request->all()['turno'];
            $fecha1 = $request->all()['fecha1'];
            $fecha2 = $request->all()['fecha2'];
            $turno = "";

            $arryOrdenes = Array();

            for ($i = 0; $i < count($lideres); $i++) {

                /*$cont = DB::Table($pre . '_gop_ordenes')
                        ->where($pre . '_gop_ordenes.id_origen',$lideres[$i])
                        ->whereBetween('fecha_programacion', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:00"])
                        ->count();*/
                if ($pre == "rds") {
                    $cont = DB::Table($pre . '_gop_ordenes as tbl1')
                        ->join($pre . '_gop_ordenes_manoobra_detalle as tbl2', 'tbl1.id_orden', '=', 'tbl2.id_orden')
                        ->where('tbl2.id_lider', $lideres[$i])
                        ->whereBetween('tbl1.fecha_ejecucion', ["$fecha1 00:00:00", "$fecha2 23:59:00"])
                        ->count();
                } else {
                    $cont = DB::Table($pre . '_gop_ordenes')
                        ->where($pre . '_gop_ordenes.id_origen', $lideres[$i])
                        ->whereBetween('fecha_programacion', ["$fecha1 00:00:00", "$fecha2 23:59:00"])
                        ->count();
                }

                if ($cont > 0) {
                    $cad = "EXEC sp_" . $pre . "_gop_consulta_ordenes_cuadrilla '" . $lideres[$i]
                        . "','" . $fecha1 . "','" . $fecha2 . "'";

                    array_push($arryOrdenes, DB::select("SET NOCOUNT ON;" . $cad));
                } else {
                    array_push($arryOrdenes, []);
                }

            }

            $gpsruta = [];
            if (count($lideres) == 1) //Solo un líder, consultar GPS
            {
                $gpsruta = DB::Table($pre . '_gps_tracker')
                    ->where('usuario_movil', $lideres[0])
                    ->where('prefijo', $pre)
                    ->whereBetween('fecha', [$fecha1 . " 00:00:00", $fecha2 . " 23:59:00"])
                    ->select('latitud', 'longitud', 'fecha', 'hora')
                    ->orderBy('fecha', 'asc')
                    ->orderBy('hora', 'asc')
                    ->get();
            }

            $retorno = array($arryOrdenes, $gpsruta);
        }

        if ($tipo == 12) //Consulta fecha y hora actual
            $retorno = explode(".", $this->fechaALong)[0];


        if ($tipo == 13) //Consulta Login REDES
        {
            $userN = $request->all()['usuario'];
            $pass = $request->all()['pass'];
            $pre = $request->all()['pro'];

            $tipoP = "T01"; //División electrica

            if (isset($request->all()['tipo_proyecto']))
                $tipoP = $request->all()['tipo_proyecto'];

            if ($tipoP == "T01") {
                $user = DB::Table($pre . '_gop_cuadrilla')
                    ->where('id_lider', $userN)
                    ->select('id_lider')
                    ->get();
            } else {
                $user = DB::Table($pre . '_gop_ordenes')
                    ->where('supervisor_ejecutor_cedula', $userN)
                    ->select('supervisor_ejecutor_cedula')
                    ->get();
            }

            $ultimoKilo = 0;
            $arrayKilo = [];

            if (count($user) == 0) {
                $retorno = array("0", "");

            } else {
                $user = DB::Table('rh_personas')
                    ->where('identificacion', $userN)
                    ->select('nombres', 'apellidos')
                    ->get();


                if ($tipoP == "T01") {
                    $cudrillas = DB::Table($pre . '_gop_cuadrilla as tbl1')
                        ->where('tbl1.id_lider', $userN)
                        ->leftJoin('rh_personas as tbl2', 'tbl1.id_lider', '=', 'tbl2.identificacion')
                        ->leftJoin('rh_personas as tbl3', 'tbl1.id_aux1', '=', 'tbl3.identificacion')
                        ->leftJoin('rh_personas as tbl4', 'tbl1.id_aux2', '=', 'tbl4.identificacion')
                        ->leftJoin('rh_personas as tbl5', 'tbl1.id_aux3', '=', 'tbl5.identificacion')
                        ->leftJoin('rh_personas as tblaux4', 'tbl1.id_aux4', '=', 'tblaux4.identificacion')
                        ->leftJoin('rh_personas as tblaux5', 'tbl1.id_aux5', '=', 'tblaux5.identificacion')
                        ->leftJoin('rh_personas as tblaux6', 'tbl1.id_aux6', '=', 'tblaux6.identificacion')
                        ->leftJoin('rh_personas as tbl6', 'tbl1.id_conductor', '=', 'tbl6.identificacion')
                        ->leftJoin('rh_personas_huella as tbl7', 'tbl1.id_lider', '=', 'tbl7.usuario')
                        ->leftJoin('rh_personas_huella as tbl8', 'tbl1.id_aux1', '=', 'tbl8.usuario')
                        ->leftJoin('rh_personas_huella as tbl9', 'tbl1.id_aux2', '=', 'tbl9.usuario')
                        ->leftJoin('rh_personas_huella as tbl10', 'tbl1.id_aux3', '=', 'tbl10.usuario')
                        ->leftJoin('rh_personas_huella as aux4', 'tbl1.id_aux4', '=', 'aux4.usuario')
                        ->leftJoin('rh_personas_huella as aux5', 'tbl1.id_aux5', '=', 'aux5.usuario')
                        ->leftJoin('rh_personas_huella as aux6', 'tbl1.id_aux6', '=', 'aux6.usuario')
                        ->leftJoin('rh_personas_huella as tbl11', 'tbl1.id_conductor', '=', 'tbl11.usuario')
                        ->get(['tbl1.id_lider', 'tbl1.id_aux1', 'tbl1.id_aux2', 'tbl1.id_aux3'

                            , 'tbl1.id_aux4'
                            , 'tbl1.id_aux5'
                            , 'tbl1.id_aux6'

                            , 'tbl1.id_conductor',
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

                    //Consultando último kilometraje

                    $Headers = array(
                        "Content-type: application/json",
                        'Accept: application/json'
                    );

                    $data = array(
                        'opc' => "2",
                        'placa' => ($cudrillas->matricula == null ? '' : $cudrillas->matricula)
                    );


                    $data = json_encode($data);

                    $url = config("app.server_transportes") . "/transporte/ws/appTecnico";

                    //Inicia conexion.
                    $conexion = curl_init();
                    //Indica un error de conexion
                    if (FALSE === $conexion)
                        throw new \Exception('failed to initialize');

                    //para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
                    curl_setopt($conexion, CURLOPT_RETURNTRANSFER, TRUE);
                    //para incluir el header en el output.
                    curl_setopt($conexion, CURLOPT_HEADER, 0);//No muestra la cabecera en el resultado
                    //Envía la cabecera
                    curl_setopt($conexion, CURLOPT_HTTPHEADER, $Headers);
                    //Dirección URL a capturar.
                    curl_setopt($conexion, CURLOPT_URL, $url);
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

                    $ultimoKilo = $resultado["id"];
                    $arrayKilo = $resultado["res"];
                } else
                    $cudrillas = [];


                if ($tipoP == "T01") {
                    $tipoV = DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro as tbl1')
                        ->where('placa', $cudrillas->matricula)
                        ->join('tra_tipo_vehiculo as tbl2', 'tbl1.id_tipo_vehiculo', '=', 'tbl2.id_tipo_vehiculo')
                        ->get(['tbl2.id_tipo_vehiculo', 'tbl2.nombre']);
                } else
                    $tipoV = "";


                $versionActual = DB::table('gop_formularios_creacion')
                    ->where('tipo_formulario', 6)
                    ->value(DB::raw('MAX(version) as dat'));

                if ($tipoP == "T01") {
                    $formulario = DB::table('gop_formularios_creacion')
                        ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                        ->whereIn('tipo_formulario', [6])
                        ->where('version', $versionActual)
                        ->orderBy('tipo_formulario')
                        ->orderBy('id_pregunta')
                        ->get();
                } else
                    $formulario = [];


                $retorno = array("1", $user[0]->nombres . " " . $user[0]->apellidos, $this->fechaShort, $cudrillas, $formulario, $tipoV, $ultimoKilo, $arrayKilo);
            }
        }

        if ($tipo == 15) //Consulta Autoinspecciones
        {
            $super = $request->all()['super'];
            $pre = $request->all()['pre'];

            $fecha1 = $this->fechaShort;
            $fecha2 = $this->fechaShort;

            if (isset($request->all()['fecha1']))
                $fecha1 = $request->all()['fecha1'];

            if (isset($request->all()['fecha2']))
                $fecha2 = $request->all()['fecha2'];


            $tipo_evento = "";

            if (isset($request->all()['tipo_evento'])) {
                if ($request->all()['tipo_evento'] != "")
                    $tipo_evento = " AND tipo_formato = '" . $request->all()['tipo_evento'] . "'";
            }

            $lider = "";
            if (isset($request->all()['lider'])) {
                if ($request->all()['lider'] != "")
                    $lider = " AND tbl2.id_origen = '" . $request->all()['lider'] . "'";
            }

            /*$consulta = "
                select tbl2.fecha_creacion as fecha,tbl2.id,tbl2.hora,(tbl3.nombres + ' ' + tbl3.apellidos) as nombres,id_lider,
                    respuesta_auto,tipo_formato as tipo_evento,tbl2.prefijo,tbl4.id as evento
                    FROM " . $pre . "_gop_cuadrilla as tbl1
                    INNER JOIN ssl_gop_formatos as tbl2 ON tbl1.id_lider = tbl2.id_origen
                    INNER JOIN rh_personas as tbl3 ON tbl1.id_lider = tbl3.identificacion
                    LEFT JOIN ssl_eventos as tbl4 ON tbl2.id = tbl4.id_formato
                    where tbl2.fecha_creacion  BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "'
                     and id_supervisor = '" . $super . "'
                    GROUP BY tbl2.fecha_creacion,tbl2.id,tbl2.hora,tbl3.nombres,tbl3.apellidos,respuesta_auto,id_lider,tipo_formato,tbl2.prefijo,tbl4.id
                    ORDER BY fecha desc,hora desc
                        ";*/

            //$fecha1 = "2017-10-01";
            $consulta = "
                select tbl2.fecha_creacion as fecha,tbl2.id,tbl2.hora,(tbl3.nombres + ' ' + tbl3.apellidos) as nombres,id_lider,
                    respuesta_auto,IIF(tipo_formato = 'FORMATO_AUTOINSPECCION','autoinspeccion', tipo_formato ) as tipo_evento,tbl2.prefijo,tbl4.id as evento,conformidad,
                    tbl4.id_formato
                    FROM " . $pre . "_gop_cuadrilla as tbl1
                    INNER JOIN ssl_gop_formatos as tbl2 ON tbl1.id_lider = tbl2.id_origen
                    INNER JOIN rh_personas as tbl3 ON tbl1.id_lider = tbl3.identificacion
                    LEFT JOIN ssl_eventos as tbl4 ON tbl2.id = tbl4.id_formato
                    where tbl2.fecha_creacion  BETWEEN '" . $fecha1 . "' AND '" . $fecha2 . "'
                     and id_supervisor = '" . $super . "'  $tipo_evento $lider
                    GROUP BY tbl4.id_formato,tbl2.fecha_creacion,tbl2.id,tbl2.hora,tbl3.nombres,tbl3.apellidos,respuesta_auto,id_lider,tipo_formato,tbl2.prefijo,tbl4.id,conformidad
                    ORDER BY fecha desc,hora desc";

            $retorno = DB::select($consulta);
        }

        if ($tipo == 16) //Consulta Evento
        {
            $evento = $request->all()['evento'];
            $tipo = $request->all()['tipo_form'];
            $pre = $request->all()['pre'];

            $consultaEncabezado = "";

            if ($pre == "rds") {

                if ($tipo == "-1") {
                    $consultaEncabezado = "
                        SELECT
                              formato.*,
                              formato.latitud as latitud_evento,
                              formato.longitud as longitud_evento,
                              evento.id AS id_evento,
                              evento.incidencia_parque AS incidencia_parque,
                              evento.conformidad AS conformidad,
                              convert(VARCHAR, formato.fecha_creacion, 20) AS fecha_formato,
                              convert(VARCHAR, formato.hora, 20) AS hora_formato,
                              evento.respuesta_auto AS codigo_calificacion,
                              (evento.respuesta_auto + ' ' + caliF.des)         AS calificacion,
                              evento.permitir_salida_a_terreno,
                              evento.observacion_calificacion_supervisor AS observacion_calificacion_supervisor,
                              convert(VARCHAR, evento.fecha_calificacion_supervisor, 20) AS fecha_calificacion_supervisor,
                              evento.supervisor_respuesta AS supervisor_respuesta,
                              evento.respuesta_auto
                            FROM ssl_gop_formatos AS formato

                              LEFT JOIN ssl_eventos AS evento
                                  ON formato.id = evento.id_formato
                            
                            LEFT JOIN ssl_gop_calificacion_formatos as caliF
                                ON caliF.id = evento.respuesta_auto

                            WHERE formato.prefijo = '$pre'
                              AND formato.id = $evento
                            ORDER BY formato.id DESC;
                    ";
                } else {
                    $consultaEncabezado = "
                        SELECT 
                          evento.id                                         AS id_evento,
                          evento.consecutivo_formulario                     AS consecutivo_formulario,
                          evento.id_origen                                  As cedula_lider,
                          evento.id_orden                                   AS numero_orden,
                          evento.tipo_evento                                AS tipo_evento,
                          evento.x                                          AS latitud_evento,
                          evento.y                                          AS longitud_evento,
                          evento.observaciones                              AS observaciones_evento,
                          convert(VARCHAR, evento.fecha, 20)                AS fecha_evento,
                          convert(VARCHAR, evento.hora, 20)                 AS hora_evento,
                          (evento.respuesta_auto + ' ' + caliF.des)         AS calificacion,
                          evento.respuesta_auto,
                          
                          formulario_respuesta.res                          AS respuesta_pregunta,
                          formulario_respuesta.id_persona_conformacion      AS cedula_integrante,
                          formulario_respuesta.cargo_persona_conformacion   AS cargo_integrante,
                          formulario_respuesta.version_formulario           AS version_formulario_respuesta,

                          formulario_tipo.nombre                            AS nombre_formulario,
                          formulario_tipo.codigo                            AS codigo_formulario,
                          formulario_tipo.version                           AS version_formulario,
                          formulario_tipo.fecha_version                     AS fecha_version_formulario,

                          formulario_creacion.item_num                      AS numeral_pregunta,
                          formulario_creacion.id_pregunta                   AS id_pregunta,
                          formulario_creacion.descrip_pregunta              AS descripcion_pregunta,
                          formulario_creacion.tipo_control                  AS id_tipo_control,

                          bodega.nombre                                     AS nombre_integrante,

                          evento.observacion_calificacion_supervisor AS observacion_calificacion_supervisor

                        FROM ssl_eventos AS evento

                          INNER JOIN gop_formulario_respuesta AS formulario_respuesta
                             ON formulario_respuesta.incidencia  = evento.consecutivo_formulario

                          INNER JOIN gop_formulario_tipo AS formulario_tipo
                            ON formulario_tipo.tipo_formulario = formulario_respuesta.tipo_form

                          INNER JOIN gop_formularios_creacion AS formulario_creacion
                             ON (
                               formulario_creacion.tipo_formulario = formulario_respuesta.tipo_form
                               AND 
                               formulario_creacion.id_pregunta = formulario_respuesta.id_pregunta
                             )

                          LEFT JOIN rds_inv_bodegas AS bodega
                            ON bodega.id_bodega = formulario_respuesta.id_persona_conformacion
                            
                          LEFT JOIN ssl_gop_calificacion_formatos as caliF
                            ON caliF.id = evento.respuesta_auto
                        WHERE evento.prefijo = '$pre'
                          AND evento.id = $evento
                          AND formulario_respuesta.prefijo = '$pre'
                          AND formulario_respuesta.version_formulario = formulario_creacion.version

                        GROUP BY
                        evento.id,
                        evento.observacion_calificacion_supervisor,
                          evento.consecutivo_formulario,
                          evento.id_origen,
                          evento.id_orden,
                          evento.tipo_evento,
                          evento.x,
                          evento.y,
                          evento.observaciones,
                          evento.fecha,
                          evento.hora,
                          evento.respuesta_auto,
                          caliF.des,
                          formulario_respuesta.res,
                          formulario_respuesta.id_persona_conformacion,
                          formulario_respuesta.cargo_persona_conformacion,
                          formulario_respuesta.version_formulario,
                          formulario_tipo.nombre,
                          formulario_tipo.codigo,
                          formulario_tipo.version,
                          formulario_tipo.fecha_version,
                          formulario_creacion.item_num,
                          formulario_creacion.id_pregunta,
                          formulario_creacion.descrip_pregunta,
                          formulario_creacion.tipo_control,
                          bodega.nombre,
                          formulario_creacion.orden
                          
                        ORDER BY formulario_creacion.orden ASC,
                          formulario_creacion.id_pregunta ASC;
                    ";
                }

                $consultaEncabezado = DB::select($consultaEncabezado);
                if ($tipo != "-1")
                    $version = $consultaEncabezado[0]->version_formulario_respuesta;

                if ($tipo == "-1") {
                    $preguntas = [];
                } else {
                    $preguntas = "SELECT
                      formulario_creacion.item_num                      AS numeral_pregunta,
                      formulario_creacion.id_pregunta                   AS id_pregunta,
                      formulario_creacion.descrip_pregunta              AS descripcion_pregunta,
                      formulario_creacion.tipo_control                  AS id_tipo_control
                    FROM gop_formularios_creacion AS formulario_creacion
                    WHERE formulario_creacion.tipo_formulario = {$tipo}
                      AND formulario_creacion.version = '$version'
                      ORDER BY formulario_creacion.orden ASC,
                      formulario_creacion.id_pregunta ASC;";

                    $preguntas = DB::select($preguntas);
                }


                $fotos = "SELECT
                    *
                  FROM ssl_gop_fotos_formatos AS foto_formato
                  WHERE foto_formato.id_evento = $evento
                  ORDER BY foto_formato.id DESC;";

                $fotos = DB::select($fotos);

                $firmas = "SELECT
                      ruta,id_origen
                    FROM ssl_gop_firmas_formatos AS firma_formato
                    WHERE firma_formato.id_evento = '$evento'
                    ORDER BY firma_formato.id DESC;";

                $firmas = DB::select($firmas);

                $respuesta = "SELECT
                      respuesta_auto,id_formato
                    FROM ssl_eventos
                    WHERE id = $evento;";

                $respuesta = DB::select($respuesta);

                $calificacionTipo = "SELECT
                  id,des
                FROM ssl_gop_calificacion_formatos";

                $calificacionTipo = DB::select($calificacionTipo);

                $id_form = $respuesta[0]->id_formato;

                $dcAsociado = "SELECT
                          lider,dc,
                          convert(VARCHAR, fecha_supervisor, 20) AS fecha_supervisor
                        FROM ssl_gop_documentos_asociacion
                        WHERE id_evento = $id_form";

                $dcAsociado = DB::select($dcAsociado);

                return response()->json(array(
                    'tipo' => 1,
                    'encabezado' => $consultaEncabezado,
                    'preguntas' => $preguntas,
                    'fotos' => $fotos,
                    'firmas' => $firmas,
                    'respuesta' => $respuesta[0]->respuesta_auto,
                    'tipo_calificacion' => $calificacionTipo,
                    'dc' => $dcAsociado
                ));

            } else {
                $consultaEncabezado = " SELECT
                    formato.*,
                    evento.incidencia_parque AS incidencia_parque,
                    evento.conformidad AS conformidad,
                    convert(VARCHAR, formato.fecha_creacion, 20) AS fecha_formato,
                    convert(VARCHAR, formato.hora, 20) AS hora_formato,
                    evento.respuesta_auto         AS calificacion,
                    caliF.des as calides,
                    evento.observacion_calificacion_supervisor AS observacion_calificacion_supervisor,
                    evento.permitir_salida_a_terreno
                FROM ssl_gop_formatos AS formato

                    LEFT JOIN ssl_eventos AS evento
                        ON formato.id = evento.id_formato
                            
                LEFT JOIN ssl_gop_calificacion_formatos as caliF
                    ON caliF.id = evento.respuesta_auto

                            WHERE formato.prefijo = '$pre'
                              AND formato.id = $evento
                            ORDER BY formato.id DESC";

                $consultaEncabezado = DB::select($consultaEncabezado);


                $data_formato = json_decode($consultaEncabezado[0]->data_final);
                $responses = [];

                if ($tipo == "-11")
                    $data_payload = json_decode($data_formato->data);
                else {
                    $data_payload = json_decode($data_formato->allData->data);
                    $responses = $data_formato->preparedResponses;
                }


                $firmas = "SELECT
                  *
                FROM ssl_gop_firmas_formatos AS firma_formato
                WHERE firma_formato.id_formato = {$evento}
                ORDER BY firma_formato.id DESC;";

                $firmas = DB::select($firmas);

                $fotos = "SELECT
                  *
                FROM ssl_gop_fotos_formatos AS foto_formato
                WHERE foto_formato.id_formato = {$evento}
                ORDER BY foto_formato.id DESC;";

                $fotos = DB::select($fotos);

                $respuesta = "SELECT
                      respuesta_auto,id_formato
                    FROM ssl_eventos
                    WHERE id = $evento;";

                $respuesta = DB::select($respuesta);

                $calificacionTipo = "SELECT
                  id,des
                FROM ssl_gop_calificacion_formatos";

                $calificacionTipo = DB::select($calificacionTipo);

                $dcAsociado = "SELECT
                          lider,dc,
                          convert(VARCHAR, fecha_supervisor, 20) AS fecha_supervisor
                        FROM ssl_gop_documentos_asociacion
                        WHERE id_evento = $evento";

                $dcAsociado = DB::select($dcAsociado);


                $datosQuestions = "";
                if ($tipo == "-10") //IRO
                {
                    $datosQuestions = json_decode($data_formato->allData->data);
                    $datosQuestions = $datosQuestions->questions;
                }


                return response()->json(array(
                    'tipo' => 2,
                    'encabezado' => $consultaEncabezado,
                    'preguntas' => ($tipo == "-11" ? $data_payload : $responses),
                    'firmas' => $firmas,
                    'fotos' => $fotos,
                    'respuesta' => $respuesta[0]->respuesta_auto,
                    'tipo_calificacion' => $calificacionTipo,
                    'dc' => $dcAsociado,
                    'personal' => ($tipo == "-11" ? [] : $data_formato->allData->detalle_conformacion),
                    'extra' => $datosQuestions

                ));
            }
        }

        if ($tipo == 17) //Consulta Autoinspecciones
        {
            $pre = $request->all()['pre'];
            $orden = $request->all()['orden'];
            $usuario = $request->all()['usuario'];

            $apro = $request->all()['apro'];
            $obser = $request->all()['obser'];

            if ($pre == "rds") {
                DB::Table($pre . '_gop_ordenes_manoobra_detalle')
                    ->where('id_lider', $usuario)
                    ->where('id_orden', $orden)
                    ->update(
                        array(
                            'aprobacion' => $apro,
                            'observacion_aprueba' => $obser,
                        )
                    );
            } else {
                DB::Table($pre . '_gop_ordenes')
                    ->where('id_orden', $orden)
                    ->update(
                        array(
                            'aprobacion' => $apro,
                            'observacion_aprueba' => $obser,
                        )
                    );
            }

            $retorno = 1;
        }


        return response()->json($retorno);
    }

    //Web services para guardar las fotos de la Aplicación Supervisión
    public function uploadFotografias(Request $request)
    {
        $file = $request->file('file');
        //Varificamos que carge un .xlsx
        $mime = $file->getMimeType();
        //obtenemos el nombre del archivo
        $nombre = explode("_", $file->getClientOriginalName());

        $nombreArchivo = $nombre[0] . "_" . $nombre[2] . "_" . substr(md5(uniqid(rand())), 0, 5) . ".jpg";

        $super = $nombre[1];
        $incidencia = $nombre[2];
        $tipo = $nombre[3];


        DB::Table('ins_foto')
            ->insert(array(
                array(
                    'id_super' => $super,
                    'fecha_celular' => $fecha_movil,
                    'id_tipo' => $tipo,
                    'turno' => $turno,
                    'ruta' => $nombreArchivo
                )
            ));

        $id_ftp = ftp_connect("201.217.195.35", 21); //Obtiene un manejador del Servidor FTP
        ftp_login($id_ftp, "usuario_ftp", "74091450652!@#1723cc"); //Se loguea al Servidor FTP
        ftp_pasv($id_ftp, true); //Se coloca el modo pasivo
        ftp_chdir($id_ftp, "ins"); // Nos dirigimos a la carpeta de destino
        $Directorio = ftp_pwd($id_ftp);
        $Directorio2 = $Directorio;

        //Guardar el archivo en mi carpeta donde esta el proyecto

        $res = 0;
        try {
            $fileL = storage_path('app') . "/" . $nombreArchivo;
            \Storage::disk('local')->put($nombreArchivo, \File::get($file));
            //file_put_contents("$nombreArchivo",$file);
            //Enviamos la imagen al servidor FTP
            $exi = ftp_put($id_ftp, $Directorio . "/" . $nombreArchivo, $fileL, FTP_BINARY);
            //Cuando se envia el archivo, se elimina el archivo

            if (file_exists($fileL))
                unlink($fileL);

            $res = 1;
        } catch (Exception $e) {
            $res = $e;
        }


        //indicamos que queremos guardar un nuevo archivo en el disco local


        return response()->json($res);
    }

    /************************************************************************/
    /*************** FIN WEB SERVICES APLICACIÓN MÓVIL **********************/
    /************************************************************************/


    /************************************************************************/
    /***************** INICIO CREACION DE IPALES WEB ************************/
    /************************************************************************/

    public function nuevoIpal(Request $request)
    {


        if (!Session::has('user_login')) return Redirect::to('{{config("app.Campro")[2]}}/campro');


        return view('proyectos.supervisor.nuevoIpal', array(
            "user_login" => Session::has('user_login'),
        ));

    }

    public function cargaNuevoIpal(Request $request)
    {

        $dato = 0;
        if (isset($request->all()['dato'])) {
            $dato = $request->all()['dato'];
        }

        $tipoformulario = 0;
        //Tipo de inspección
        if ($dato == 1) {
            $tipoformulario = 1;
        } else if ($dato == 2) {
            $tipoformulario = 2;
        } else if ($dato == 3) {
            $tipoformulario = 20;
        } else if ($dato == 4) {
            $tipoformulario = 26;
        }


        // if(!Session::has('user_login'))  return Redirect::to('{{config("app.Campro")[2]}}/campro');

        $consulta = "	SELECT proyecto,prefijo_db,MAX(id_proyecto) AS id_proyecto
        			FROM(
	                  SELECT
	                    gop_proyectos.nombre AS proyecto,
	                    gop_proyectos.prefijo_db,
	                    gop_proyectos.id_proyecto
	                  FROM sis_usuarios
	                    INNER JOIN sis_usuarios_proyectos AS conf_proy
	                      ON (conf_proy.id_usuario = sis_usuarios.id_usuario)
	                    INNER JOIN gop_proyectos
	                      ON (gop_proyectos.id_proyecto = conf_proy.id_proyecto AND gop_proyectos.prefijo_db != '')
	                  WHERE sis_usuarios.id_usuario = '" . Session::get('user_login') . "' GROUP BY gop_proyectos.nombre, gop_proyectos.prefijo_db,gop_proyectos.id_proyecto
	                  ) as tbl GROUP BY tbl.proyecto,tbl.prefijo_db
	                  ORDER BY tbl.proyecto
	                        ";
        $proyecto = DB::select($consulta);

        //$dato=26;
        $sql = "  select 
                        item_num,
                        descrip_pregunta,
                        tipo_control,
                        id_pregunta,
                        nombre_corto,
                        version
                   from gop_formularios_creacion  
                   where 
                       tipo_formulario='" . $tipoformulario . "' and 
                       version = (  select MAX(version) from gop_formularios_creacion  where tipo_formulario='" . $tipoformulario . "' )    ";


        $result = DB::select($sql);
        $canti = count($result);
        $canti = $canti / 2;


        if ($dato == 1) {
            $canti = $canti + 3;
        } else if ($dato == 2) {
            $canti = $canti + 1;
        }

        $fondogrisd = 'bgcolor="#d9d9d9"';
        $fondoazuld = 'bgcolor="#dce6f1"';
        $centrod = ' align="center" ';
        $tablaaa = 'border="1" cellspacing="0" cellpadding="0" width="100%"  ';
        /*   $anchotd=' width="50%" ';
        $anchotdc=' width="100%" ';
        $anchotdc=' width="614px" ';


        $anchouno=' width="22px" ';
        $anchodos=' width="282px" ';
        $anchotres=' width="17px" ';
        $anchocuatro=' width="17px" ';
        $anchocinco=' width="17px" ';
        $anchotresd=' width="51px" ';

        */

        $anchotd = ' width="50%" ';
        $anchotdc = ' width="100%" ';


        $anchouno = ' width="22px" ';
        $anchodos = ' width="282px" ';
        $anchotres = ' width="17px" ';
        $anchocuatro = ' width="17px" ';
        $anchocinco = ' width="17px" ';
        $anchotresd = ' width="51px" ';


        $table = "<table " . $anchotdc . " ><tr><td  valign='top' " . $anchotd . " ><table " . $tablaaa . ">";
        $titulos = "<tr  " . $fondogrisd . " ><td " . $anchouno . " " . $centrod . ">ITEM</td><td " . $anchodos . "> DESCRIPCIÓN</td><td colspan='4' " . $anchotresd . "  " . $centrod . " >CUMPLE</td></tr>";
        $table .= $titulos;

        $cantidad = 1;
        $control = 0;
        $ultimoazul = "";


        foreach ($result as $r) {

            if ($cantidad > ($canti + 1) && $control == 0) {
                $control = 1;
                $table .= "</table></td><td  valign='top'  " . $anchotd . " > <table " . $tablaaa . " >";
                $table .= $titulos;
                $table .= $ultimoazul;
            }
            $cantidad++;

            if ($r->tipo_control == 19 || $r->tipo_control == 1) {//Titulo 3 Opciones SI - NO - NA

                $ultimoazul = "<tr " . $fondoazuld . " ><td " . $centrod . " " . $anchouno . " > " . $r->item_num . " </td>";
                $ultimoazul .= "                    <td  " . $anchodos . " > " . $r->descrip_pregunta . " </td>";
                $ultimoazul .= "                    <td " . $centrod . "  " . $anchotres . " > SI </td>";
                $ultimoazul .= "                    <td " . $centrod . "  " . $anchocuatro . " > NO </td>";
                $ultimoazul .= "                    <td " . $centrod . "  " . $anchocinco . " > N/A </td>";
                $ultimoazul .= "                    <td " . $centrod . "  " . $anchocinco . " > COMENTARIO </td></tr>";

                $table .= $ultimoazul;
            } else if ($r->tipo_control == 11) {//Título 3 Opciones C-NC-NA

                $ultimoazul = "<tr " . $fondoazuld . " ><td " . $centrod . " " . $anchouno . " > " . $r->item_num . " </td>";
                $ultimoazul .= "                    <td  " . $anchodos . " > " . $r->descrip_pregunta . " </td>";
                $ultimoazul .= "                    <td " . $centrod . "  " . $anchotres . " > C </td>";
                $ultimoazul .= "                    <td " . $centrod . "  " . $anchocuatro . " > N/C </td>";
                //   $ultimoazul .= "                    <td ".$centrod."  ".$anchocuatro." colspan='2'> SI </td>";
                $ultimoazul .= "                    <td " . $centrod . "  " . $anchocinco . " > N/A </td>";
                $ultimoazul .= "                    <td " . $centrod . "  " . $anchocinco . " > COMENTARIO </td></tr>";

                $table .= $ultimoazul;
            } else if ($r->tipo_control == 0) {//Titulo 3 Opciones SI - NO - NA

                $ultimoazul = "<tr " . $fondoazuld . " ><td " . $centrod . " " . $anchouno . " > " . $r->item_num . " </td>";
                $ultimoazul .= "                    <td  " . $anchodos . " colspan='5'> " . $r->descrip_pregunta . " </td>";

                $table .= $ultimoazul;
            } else {

                $table .= "<tr class='contenresp' data-idpre='" . $r->id_pregunta . "' data-tipocontrol='" . $r->tipo_control . "' data-califica='" . $r->nombre_corto . "' data-item='" . $r->item_num . "' data-version='" . $r->version . "' data-tipoformulario='" . $tipoformulario . "'>";
                $table .= "     <td " . $centrod . "  " . $anchouno . " > " . $r->item_num . " </td>";
                $table .= "    <td  " . $anchodos . " > " . $r->descrip_pregunta . " </td>";
                $respuesta = "";
                if ($r->tipo_control == 2) {//Radio button 3 opciones SI-NO-NA
                    $table .= " <td " . $centrod . "  " . $anchotres . "   ><input type='radio' value='SI'  name='pregunta_" . $r->id_pregunta . "' class='radio' onclick='checkradio(\"" . $r->id_pregunta . "\")' > </td> ";
                    $table .= " <td " . $centrod . "  " . $anchocuatro . " ><input type='radio' value='NO'  name='pregunta_" . $r->id_pregunta . "' class='radio' onclick='checkradio(\"" . $r->id_pregunta . "\")' > </td> ";
                    $table .= " <td " . $centrod . "  " . $anchocinco . "  ><input type='radio' value='N/A' name='pregunta_" . $r->id_pregunta . "' class='radio' onclick='checkradio(\"" . $r->id_pregunta . "\")' > </td> ";
                    $table .= " <td " . $centrod . "  " . $anchocinco . "  >";
                    $table .= "          <select id='acto_condicion_" . $r->id_pregunta . "' class='form-control' disabled='disabled'><option value='0'>SELECCIONE</option><option value='ACTO'>ACTO</option><option value='CONDICION'>CONDICION</option></select >";
                    $table .= "          <input type='text' id='texto_extra_" . $r->id_pregunta . "'  disabled='disabled'>";
                    $table .= " </td> ";
                } else if ($r->tipo_control == 12) {//Radio button 3 opciones SI-NO-NA
                    /* $table .= " <td ".$centrod."  ".$anchotres."   ><input type='radio' value='C'  name='pregunta_".$r->id_pregunta."' > </td> ";
                        $table .= " <td ".$centrod."  ".$anchocuatro." ><input type='radio' value='NC'  name='pregunta_".$r->id_pregunta."' > </td> ";*/

                    //$table .= " <td ".$centrod."  ".$anchocinco." colspan='2' ><input type='radio' value='SI' name='pregunta_".$r->id_pregunta."'  class='radio' onclick='checkradio()' > </td> ";
                    //$table .= " <td ".$centrod."  ".$anchocinco." ><input type='radio' value='N/A' name='pregunta_".$r->id_pregunta."'  class='radio' onclick='checkradio()' > </td> ";


                    $table .= " <td " . $centrod . "  " . $anchotres . "   ><input type='radio' value='SI'  name='pregunta_" . $r->id_pregunta . "' class='radio' onclick='checkradio(\"" . $r->id_pregunta . "\")' > </td> ";
                    $table .= " <td " . $centrod . "  " . $anchocuatro . " ><input type='radio' value='NO'  name='pregunta_" . $r->id_pregunta . "' class='radio' onclick='checkradio(\"" . $r->id_pregunta . "\")' > </td> ";
                    $table .= " <td " . $centrod . "  " . $anchocinco . "  ><input type='radio' value='N/A' name='pregunta_" . $r->id_pregunta . "' class='radio' onclick='checkradio(\"" . $r->id_pregunta . "\")' > </td> ";
                    // $table .= " <td ".$centrod."  ".$anchocinco." ><input type='text' id='acto_condicion_".$r->id_pregunta."' > </td> ";
                    $table .= " <td " . $centrod . "  " . $anchocinco . "  >";
                    $table .= "          <select id='acto_condicion_" . $r->id_pregunta . "' class='form-control' disabled='disabled'><option value='0'>SELECCIONE</option><option value='ACTO'>ACTO</option><option value='CONDICION'>CONDICION</option></select >";
                    $table .= "          <input type='text' id='texto_extra_" . $r->id_pregunta . "'  disabled='disabled'>";
                    $table .= " </td> ";


                } else if ($r->tipo_control == 13) {//text area
                    $table .= " <td " . $centrod . "  " . $anchotres . " colspan='4'  ><textarea  name='texto_extra_" . $r->id_pregunta . "' id='texto_extra_" . $r->id_pregunta . "' rows='3' style='width: 100%;'></textarea> </td> ";
                }
                $table .= " </tr>";

            }
        }
        $table .= "</table></td></tr></table>";


        return view('proyectos.supervisor.cargaNuevoIpal', array(
            "user_login" => Session::has('user_login'),
            "dato" => $dato,
            "table" => $table,
            "proyecto" => $proyecto
        ));

    }


    public function cargaLideres(Request $request)
    {

        $dato = "";
        if (isset($request->all()['dato'])) {
            $dato = $request->all()['dato'];
        }

        $sql = "  select 
                    c.id_lider,
                    concat(lider.nombres,' ',IIF( (lider.apellidos is not null and lider.apellidos <>''),lider.apellidos,CONCAT(lider.apellido1,' ',lider.apellido2))) as lidertxt,
                    c.id_aux1,
                    concat(aux1.nombres,' ',IIF( (aux1.apellidos is not null and aux1.apellidos <>''),aux1.apellidos,CONCAT(aux1.apellido1,' ',aux1.apellido2))) as aux1txt,
                    c.id_aux2,
                    concat(aux2.nombres,' ',IIF( (aux2.apellidos is not null and aux2.apellidos <>''),aux2.apellidos,CONCAT(aux2.apellido1,' ',aux2.apellido2))) as aux2txt,
                    c.id_aux3,
                    concat(aux3.nombres,' ',IIF( (aux3.apellidos is not null and aux3.apellidos <>''),aux3.apellidos,CONCAT(aux3.apellido1,' ',aux3.apellido2))) as aux3txt,
                    c.id_conductor,
                    concat(conductor.nombres,' ',IIF( (conductor.apellidos is not null and conductor.apellidos <>''),conductor.apellidos,CONCAT(conductor.apellido1,' ',conductor.apellido2))) as conductortxt,
                    c.matricula,
                    c.id_tipo_cuadrilla,
                    tc.nombre as tipocuadrillatxt,
                    c.id_movil,
                    c.id_supervisor,
                    concat(super.nombres,' ',IIF( (super.apellidos is not null and super.apellidos <>''),super.apellidos,CONCAT(super.apellido1,' ',super.apellido2))) as super
                 from 
                      " . $dato . "_gop_cuadrilla as c left join
                      " . $dato . "_gop_tipo_cuadrilla as tc on (c.id_tipo_cuadrilla=tc.id_tipo_cuadrilla) left join
                      rh_personas as lider on (c.id_lider = lider.identificacion) left join
                      rh_personas as aux1 on (c.id_aux1 = aux1.identificacion) left join
                      rh_personas as aux2 on (c.id_aux2 = aux2.identificacion) left join
                      rh_personas as aux3 on (c.id_aux3 = aux3.identificacion) left join
                      rh_personas as conductor on (c.id_conductor = conductor.identificacion) left join
                      rh_personas as super on (c.id_supervisor = super.identificacion)

                 where c.id_estado='A' order by  concat(lider.nombres,' ',IIF( (lider.apellidos is not null and lider.apellidos <>''),lider.apellidos,CONCAT(lider.apellido1,' ',lider.apellido2))) asc   ";


        $result = DB::select($sql);

        $retorna = array('cantidad' => count($result), 'datos' => $result);

        return response()->json($retorna);

    }


    public function guardaNuevoIpal(Request $request)
    {


        $conse = self::generaConsecutivo("ID_INSPEC");
        $datos['id_inspeccion'] = $conse;

        if (isset($request->all()['id_orden'])) {
            $datos['id_orden'] = trim($request->all()['id_orden']);

            if ($datos['id_orden'] == null) {
                $datos['id_orden'] = '';
            }
        }

        if (isset($request->all()['prefijo'])) {
            $datos['prefijo'] = trim($request->all()['prefijo']);
        }

        if (isset($request->all()['id_tipo_proyecto'])) {
            $datos['id_tipo_proyecto'] = trim($request->all()['id_tipo_proyecto']);
            $datos['id_proyecto'] = trim($request->all()['id_tipo_proyecto']);
        }

        if (isset($request->all()['supervisor'])) {
            $datos['supervisor'] = trim($request->all()['supervisor']);
        }
        /*
	if(isset($request->all()['fecha_servidor'])){
             $datos['fecha_servidor']=trim($request->all()['fecha_servidor']);
        }*/

        if (isset($request->all()['lider'])) {
            $datos['lider'] = trim($request->all()['lider']);
        }

        if (isset($request->all()['aux1'])) {
            $datos['aux1'] = trim($request->all()['aux1']);
        }

        if (isset($request->all()['aux2'])) {
            $datos['aux2'] = trim($request->all()['aux2']);
        }

        if (isset($request->all()['aux3'])) {
            $datos['aux3'] = trim($request->all()['aux3']);
        }

        if (isset($request->all()['conductor'])) {
            $datos['conductor'] = trim($request->all()['conductor']);
        }

        if (isset($request->all()['matricula'])) {
            $datos['matricula'] = trim($request->all()['matricula']);
        }

        if (isset($request->all()['tipocu'])) {
            $datos['tipo_cuadrilla'] = trim($request->all()['tipocu']);
        }

        if (isset($request->all()['id_movil'])) {
            $datos['movil'] = trim($request->all()['id_movil']);
        }

        $idtipoformulario = 0;
        if (isset($request->all()['tipo_inspeccion'])) {
            $idtipoformulario = trim($request->all()['tipo_inspeccion']);
            if ($idtipoformulario == 20) {
                $datos['tipo_inspeccion'] = 3;
                $datos['tipo_ingreso_inspeccion'] = "SSL";
            } else if ($idtipoformulario == 26) {
                $datos['tipo_inspeccion'] = 4;
                $datos['tipo_ingreso_inspeccion'] = "SSL";
            } else if ($idtipoformulario == 1) {
                $datos['tipo_inspeccion'] = 1;
                $datos['tipo_ingreso_inspeccion'] = "SSL";
            } else if ($idtipoformulario == 2) {
                $datos['tipo_inspeccion'] = 2;
                $datos['tipo_ingreso_inspeccion'] = "SSL";
            }
        }

        if (isset($request->all()['resultado'])) {
            $datos['resultado'] = trim($request->all()['resultado']);
        }

        if (isset($request->all()['calificacion'])) {
            $datos['calificacion'] = trim($request->all()['calificacion']);
        }

        if (isset($request->all()['observacion'])) {
            $datos['observacion'] = trim($request->all()['observacion']);
        }

        if (isset($request->all()['anio'])) {
            $datos['anio'] = trim($request->all()['anio']);
        }


        if (isset($request->all()['id_proyecto'])) {
            $datos['id_proyecto'] = trim($request->all()['id_proyecto']);
        }
        /*
	if(isset($request->all()['tipo_ingreso_inspeccion'])){
             $datos['tipo_ingreso_inspeccion']=trim($request->all()['tipo_ingreso_inspeccion']);
        }*/

        if ($idtipoformulario == 1) {
            if (isset($request->all()['charla'])) {
                $datos['charla_calificacion'] = trim($request->all()['charla']);
            }
        }

        if (isset($request->all()['mes'])) {
            $datos['mes'] = trim($request->all()['mes']);
        }
        $datos['estado'] = 'E0';
        //$datos['coordenadas']=
        //$datos['actividad_especifica']=
        if (isset($request->all()['direccion_inspeccion'])) {
            $datos['direccion_inspeccion'] = trim($request->all()['direccion_inspeccion']);
        }

        //$datos['usuario_ultima_modif']=Session::get('user_login');

        $res = DB::table('ins_inspeccion')->insert(array($datos));
        ///$datoslog['ins_inspeccion'] = DB::getPdo()->lastInsertId();


        if ($res) {
            $respuestas = $request->all()['datos'];

            foreach ($respuestas as $resp) {

                // print_r($resp);
                $datosins = array();
                $datosins['id_inspeccion'] = $conse;
                $datosins['id_orden'] = $datos['id_orden'];
                $datosins['id_pregunta'] = $resp['id_pregunta'];
                $respues = $resp['respuesta'];
                if ($idtipoformulario == 1 && $respues == 'SI') {
                    $respues = 2;
                } else if ($idtipoformulario == 1 && $respues == 'NO') {
                    $respues = 1;
                } else if ($idtipoformulario == 1 && $respues == 'N/A') {
                    $respues = 0;
                }


                $datosins['respuesta'] = $respues;
                $datosins['version'] = $resp['version'];
                $datosins['acto_condicion'] = $resp['acto_condicion'];
                $datosins['id_formulario'] = $idtipoformulario;
                $datosins['texto_extra'] = $resp['texto_extra'];

                $res2 = DB::connection('sqlsrv')->table('ins_inspeccion_detalle')->insert(array($datosins));
            }

            if ($res) {
                $response = array('status' => 1, 'statusText' => 'Exito', 'message' => 'Proceso finalizado satisfactoriamente. ', 'conse' => $conse);
            } else {
                $response = array('status' => 0, 'statusText' => 'Error', 'message' => 'Ocurrió un error, por favor inténtalo nuevamente más tarde. ');
            }


        } else {
            $response = array('status' => 0, 'statusText' => 'Error', 'message' => 'Ocurrió un error, por favor inténtalo nuevamente más tarde. ');
        }
        return json_encode($response);

    }



    /*
    DB::table( $dato.'_gop_cuadrilla')
    ->select('tipo_formulario as tipo_f','item_num as item','id_pregunta as id','descrip_pregunta as des','obligatorio as obli','tipo_control as tip','nombre_corto','id_padre as padre','version as ver')
                            ->whereIn('id_estado','A')
                            ->orderBy('tipo_formulario')
                            ->orderBy('id_pregunta')
                            ->get();*/
    /************************************************************************/
    /******************* FIN CREACION DE IPALES WEB *************************/
    /************************************************************************/


    /// http://localhost/laravel/public/inspeccionOrdenes
}