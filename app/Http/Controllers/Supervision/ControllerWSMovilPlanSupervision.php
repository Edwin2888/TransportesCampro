<?php

namespace App\Http\Controllers\Supervision;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;


class ControllerWSMovilPlanSupervision extends Controller
{




    /*************************************************/
    /******** FUNCIONES DEL CONTROLADOR **************/
    function __construct()
    {

        $this->valorT = "";

        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    //Función encargada de generar el consecutivo
    private function generaConsecutivo($tipo)
    {
        $consen = DB::table('gen_consecutivos')
            ->where('id_campo', $tipo)
            ->select('consecutivo', 'prefijo', 'long_cadena', 'caracter_relleno')
            ->get();

        if (count($consen) == 0)
            return -1;


        $lnconsecutivo = $consen[0]->consecutivo;
        $prefijo       = $consen[0]->prefijo;
        $longitud_max  = $consen[0]->long_cadena;
        $relleno       = $consen[0]->caracter_relleno;

        //Aumentar Consecutivo
        $lnconsecutivo = $lnconsecutivo + 1;

        //Ahora Actualizar el Consecutivo de una vez
        DB::table('gen_consecutivos')
            ->where('id_campo', $tipo)
            ->update(array(
                'consecutivo' => $lnconsecutivo
            ));

        $num_relleno    = $longitud_max - strlen($prefijo);
        $char_rellenos  = self::lfillchar($lnconsecutivo, $num_relleno, $relleno);
        $ret            = $prefijo . $char_rellenos . $lnconsecutivo;
        return $ret;
    }

    //Función encargada de rellenar el consecutivo generado por 0
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


    /*****************************************************************************/
    /******** WEB SERVICES APP MÓVIL PLAN DE SUPERVISIÓN - CONSULTA **************/
    /*****************************************************************************/

    /**
    Obtiene las ordenes de plan de supervisión asociadas a un usuario
     */

    public function consultaPlanSupervision(Request $request)
    {


        $opc = $request->all()["opc"];

        if ($opc == "1") //Consulta Login usuarios Plan Supervisión
        {
            $user = $request->all()["usuario"];
            $pass = $request->all()["pass"];

            $userCont = DB::Table('rh_personas')
                ->where('identificacion', $user)
                ->where('id_estado', 'EP03')
                ->select(DB::raw("(nombres + ' ' + apellidos) as nombre"))
                ->get();

            if (count($userCont) == 0)
                return response()->json(array(
                    'id' => -1,
                    'res' => "Usuario no existe"
                ));

            $mes = date("n");
            $anio = date("Y");

            $userLider = DB::Table('ins_lider_plan_supervision')
                ->where('anio', $anio)
                ->where('mes', $mes)
                ->where('lider', $user)
                ->count();

            if ($userLider == 0) {

                $userCola = DB::Table('ins_colaborador_plan_supervision')
                    ->where('anio', $anio)
                    ->where('mes', $mes)
                    ->where('colaborador', $user)
                    ->count();

                if ($userCola == 0)
                    return response()->json(array(
                        'id' => -2,
                        'res' => "No ha conformado plan de supervisión para  mes " . $mes . " - " . $anio
                    ));
                else
                    return response()->json(array(
                        'id' => 1,
                        'res' => $userCont[0]->nombre
                    ));
            }

            return response()->json(array(
                'id' => 1,
                'res' => $userCont[0]->nombre
            ));
        }

        if ($opc == "2") //Consulta Maestros tabla
        {
            $versionActualIpal = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 1)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioIpal = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 1)
                ->where('version', $versionActualIpal)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =============================================================================================================================================
            // INSPECCION SEGURIDAD OBRAS CIVILES
            // =============================================================================================================================================
            $versionActualIpalObrasCiviles = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 33)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioIpalObrasCiviles = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 33)
                ->where('version', $versionActualIpalObrasCiviles)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =============================================================================================================================================
            // INSPECCION SEGURIDAD TELECOMUNICACIONES
            // =============================================================================================================================================
            $versionActualIpalTelecomunicaciones = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 34)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioIpalTelecomunicaciones = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 34)
                ->where('version', $versionActualIpalTelecomunicaciones)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();


            // =============================================================================================================================================
            // INSPECCION SEGURIDAD REDES ELECTRICAS
            // =============================================================================================================================================
            $versionActualIpalRedesElectricas = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 35)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioIpalRedesElectricas = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 35)
                ->where('version', $versionActualIpalRedesElectricas)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =============================================================================================================================================
            // INSPECCION DE KIT PARA LA ATENCION DE DERRAMES
            // =============================================================================================================================================
            $versionActualKitsAtencionDerrames = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 43)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioActualKitsAtencionDerrames = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 43)
                ->where('version', $versionActualKitsAtencionDerrames)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =============================================================================================================================================
            // INSPECCIÓN LOCATIVA DE GESTIÓN AMBIENTAL
            // =============================================================================================================================================
            $versionActualLocativaAmbiental = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 44)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioActualLocativaAmbiental = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 44)
                ->where('version', $versionActualLocativaAmbiental)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =============================================================================================================================================
            // CALIDAD: INSPECCIÓN Y ENTREGA DE OBRAS CIVILES
            // =============================================================================================================================================
            $versionActualCalidadEntregaObrasCiviles = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 46)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioActualCalidadEntregaObrasCiviles = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 46)
                ->where('version', $versionActualCalidadEntregaObrasCiviles)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =============================================================================================================================================
            // CALIDAD: INSPECCIÓN CALIDAD TRABAJOS RDS
            // =============================================================================================================================================
            $versionActualCalidadTrabajosRDS = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 36)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioActualCalidadTrabajosRDS = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 36)
                ->where('version', $versionActualCalidadTrabajosRDS)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();


            // =============================================================================================================================================
            // CALIDAD: INSPECCION DE CALIDAD TRABAJOS DE MANTENIMIENTO Y/O OBRAS EN MEDIA Y BAJA TENSION
            // =============================================================================================================================================
            $versionActualCalidadObrasMTBT = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 37)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioActualCalidadObrasMTBT = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 37)
                ->where('version', $versionActualCalidadObrasMTBT)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =============================================================================================================================================
            // INSPECCION CALIDAD
            // =============================================================================================================================================
            $versionActualCalidad = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 2)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioCalidad = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 2)
                ->where('version', $versionActualCalidad)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();


            /*$versionActualObservacion = DB::table('gop_formularios_creacion')
                                ->where('tipo_formulario',1)
                                ->get([DB::raw('MAX(version) as dat')])[0]->dat;*/

            // =============================================================================================================================================
            // INSPECCION OBSERVACIONES
            // =============================================================================================================================================
            $versionActualObservacion = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 20)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioObservacion = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 20)
                ->where('version', $versionActualObservacion)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =============================================================================================================================================
            // INSPECCION MEDIO AMBIENTE
            // =============================================================================================================================================
            $versionActualMedioAmbiente = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 26)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioMedioAmbiente = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 26)
                ->where('version', $versionActualMedioAmbiente)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =======================================================================================================================
            // INSPECCION PNC
            // =======================================================================================================================
            $versionActualInspeccionPNC = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 39)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioInspeccionPNC = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 39)
                ->where('version', $versionActualInspeccionPNC)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();

            // =======================================================================================================================
            // INSPECCION PNC
            // =======================================================================================================================
            $versionCalidadOperacionCom = DB::table('gop_formularios_creacion')
                ->where('tipo_formulario', 47)
                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioCalidadOperacionCom = DB::table('gop_formularios_creacion')
                ->select('tipo_formulario as tipo_f', 'item_num as item', 'id_pregunta as id', 'descrip_pregunta as des', 'obligatorio as obli', 'tipo_control as tip', 'nombre_corto', 'id_padre as padre', 'version as ver')
                ->where('tipo_formulario', 47)
                ->where('version', $versionCalidadOperacionCom)
                ->orderBy('tipo_formulario')
                ->orderBy('id_pregunta')
                ->get();


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
	                      ON (gop_proyectos.id_proyecto = conf_proy.id_proyecto AND gop_proyectos.prefijo_db != '')
	                  WHERE sis_usuarios.cuenta = 'CO8778565' GROUP BY gop_proyectos.nombre, gop_proyectos.prefijo_db,gop_proyectos.id_proyecto
	                  ) as tbl GROUP BY tbl.proyecto,tbl.prefijo_db
	                  ORDER BY tbl.proyecto
	                        ";
            $proyecto = DB::select($consulta);

            return response()->json(array(
                'frmIpal'               => $formularioIpal,
                'frmIpalObrasCiviles'       => $formularioIpalObrasCiviles,
                'frmOpComercial'       => $formularioCalidadOperacionCom, //
                'frmIpalTelecomunicaciones' => $formularioIpalTelecomunicaciones,
                'frmIpalRedesElectricas'    => $formularioIpalRedesElectricas,
                'fmrInspeccionPNC'      => $formularioInspeccionPNC,
                'frmCalidad'            => $formularioCalidad,
                'frmObservacion'        => $formularioObservacion,
                'frmMedioAmbiente'      => $formularioMedioAmbiente,
                'frmKitDerrame'         => $formularioActualKitsAtencionDerrames,
                'frmLocativaAmbiental'  => $formularioActualLocativaAmbiental,
                'frmEntregaOC'          => $formularioActualCalidadEntregaObrasCiviles,
                'frmEntregaRDS'         => $formularioActualCalidadTrabajosRDS,
                'frmEntregaObrasMTBT'   => $formularioActualCalidadObrasMTBT,
                'proyecto'              => $proyecto
            ));
        }

        if ($opc == "3") //Consulta avance
        {

            $mes = date("n");
            $anio = date("Y");
            $user = $request->all()["user"];
            $userLider = DB::Table('ins_lider_plan_supervision')
                ->where('anio', $anio)
                ->where('mes', $mes)
                ->where('lider', $user)
                ->get(['comportamiento', 'ipales', 'calidad', 'ambiental', 'nombre_equipo', 'seguridad_obra_civil', 'telecomunicaciones', 'redes_electricas', 'kit_manejo_derrames', 'locativa_gestion_ambiental', 'entrega_obra_civil', 'restablecimiento_servicio', 'mantenimiento']);

            $sqlIPAL = "select count(id_inspeccion) as cantidadEjecutadas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 1
                            and resultado = 'C'
                            and supervisor = '$user') as cantConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 1
                            and resultado = 'NC'
                            and supervisor = '$user') as cantNoConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 1
                            and estado IN ('E2','E3')
                            and supervisor = '$user') as cantCerradas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 1
                            and estado IN ('E0','E1')
                            and supervisor = '$user') as cantAbiertas
                from
                ins_inspeccion
                where anio = '$anio'
                and mes = '$mes'
                and tipo_inspeccion = 1
                and supervisor = '$user'
                ";

            $cantLiderIPAL = DB::select($sqlIPAL);

            $sqlCalidad = "select count(id_inspeccion) as cantidadEjecutadas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 2
                            and resultado = 'C'
                            and supervisor = '$user') as cantConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 2
                            and resultado = 'NC'
                            and supervisor = '$user') as cantNoConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 2
                            and estado IN ('E2','E3')
                            and supervisor = '$user') as cantCerradas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 2
                            and estado IN ('E0','E1')
                            and supervisor = '$user') as cantAbiertas
                from
                ins_inspeccion
                where anio = '$anio'
                and mes = '$mes'
                and tipo_inspeccion = 2
                and supervisor = '$user'
                ";

            $cantLiderCali =  DB::select($sqlCalidad);

            $sqlObser = "select count(id_inspeccion) as cantidadEjecutadas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 3
                            and resultado = 'C'
                            and supervisor = '$user') as cantConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 3
                            and resultado = 'NC'
                            and supervisor = '$user') as cantNoConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 3
                            and estado IN ('E2','E3')
                            and supervisor = '$user') as cantCerradas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 3
                            and estado IN ('E0','E1')
                            and supervisor = '$user') as cantAbiertas
                from
                ins_inspeccion
                where anio = '$anio'
                and mes = '$mes'
                and tipo_inspeccion = 3
                and supervisor = '$user'
                ";

            $cantLiderObser = DB::select($sqlObser);

            $sqlAmbiental = "select count(id_inspeccion) as cantidadEjecutadas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 4
                            and resultado = 'C'
                            and supervisor = '$user') as cantConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 4
                            and resultado = 'NC'
                            and supervisor = '$user') as cantNoConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 4
                            and estado IN ('E2','E3')
                            and supervisor = '$user') as cantCerradas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion = 4
                            and estado IN ('E0','E1')
                            and supervisor = '$user') as cantAbiertas
                from
                ins_inspeccion
                where anio = '$anio'
                and mes = '$mes'
                and tipo_inspeccion = 4
                and supervisor = '$user'
                ";

            $cantLiderAmbiente = DB::select($sqlAmbiental);

            $sqlInspeccionesSeguridad = "select count(id_inspeccion) as cantidadEjecutadas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(33, 34, 35)
                            and resultado = 'C'
                            and supervisor = '$user') as cantConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(33, 34, 35)
                            and resultado = 'NC'
                            and supervisor = '$user') as cantNoConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(33, 34, 35)
                            and estado IN ('E2','E3')
                            and supervisor = '$user') as cantCerradas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(33, 34, 35)
                            and estado IN ('E0','E1')
                            and supervisor = '$user') as cantAbiertas
                from
                ins_inspeccion
                where anio = '$anio'
                and mes = '$mes'
                and tipo_inspeccion IN(33, 34, 35)
                and supervisor = '$user'
                ";

            $cantLiderInspeccionesSeguridad = DB::select($sqlInspeccionesSeguridad);

            $sqlInspeccionesKitDerrames = "select count(id_inspeccion) as cantidadEjecutadas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(43)
                            and resultado = 'C'
                            and supervisor = '$user') as cantConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(43)
                            and resultado = 'NC'
                            and supervisor = '$user') as cantNoConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(43)
                            and estado IN ('E2','E3')
                            and supervisor = '$user') as cantCerradas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(43)
                            and estado IN ('E0','E1')
                            and supervisor = '$user') as cantAbiertas
                from
                ins_inspeccion
                where anio = '$anio'
                and mes = '$mes'
                and tipo_inspeccion IN(43)
                and supervisor = '$user'
                ";

            $cantLiderInspeccionesKitDerrames = DB::select($sqlInspeccionesKitDerrames);

            $sqlInspeccionesLocativa = "select count(id_inspeccion) as cantidadEjecutadas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(44)
                            and resultado = 'C'
                            and supervisor = '$user') as cantConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(44)
                            and resultado = 'NC'
                            and supervisor = '$user') as cantNoConforme,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(44)
                            and estado IN ('E2','E3')
                            and supervisor = '$user') as cantCerradas,
                        (SELECT count(id_inspeccion)
                            from
                            ins_inspeccion
                            where anio = '$anio'
                            and mes = '$mes'
                            and tipo_inspeccion IN(44)
                            and estado IN ('E0','E1')
                            and supervisor = '$user') as cantAbiertas
                from
                ins_inspeccion
                where anio = '$anio'
                and mes = '$mes'
                and tipo_inspeccion IN(43)
                and supervisor = '$user'
                ";

            $cantLiderInspeccionesLocativa = DB::select($sqlInspeccionesLocativa);


            $mesLetra = "";
            switch ($mes) {
                case 1:
                    $mesLetra = "ENERO";
                    break;
                case 2:
                    $mesLetra = "FEBRERO";
                    break;
                case 3:
                    $mesLetra = "MARZO";
                    break;
                case 4:
                    $mesLetra = "ABRIL";
                    break;
                case 5:
                    $mesLetra = "MAYO";
                    break;
                case 6:
                    $mesLetra = "JUNIO";
                    break;
                case 7:
                    $mesLetra = "JULIO";
                    break;
                case 8:
                    $mesLetra = "AGOSTO";
                    break;
                case 9:
                    $mesLetra = "SEPTIEMBRE";
                    break;
                case 10:
                    $mesLetra = "OCTUBRE";
                    break;
                case 11:
                    $mesLetra = "NOVIEMBRE";
                    break;
                case 12:
                    $mesLetra = "DICIEMBRE";
                    break;
            };


            if (count($userLider) == 0) {
                $userCola = DB::Table('ins_colaborador_plan_supervision')
                    ->where('anio', $anio)
                    ->where('mes', $mes)
                    ->where('colaborador', $user)
                    ->get(['comportamiento', 'ipales', 'calidad', 'ambiental', 'nombre_equipo', 'seguridad_obra_civil', 'telecomunicaciones', 'redes_electricas', 'kit_manejo_derrames', 'locativa_gestion_ambiental', 'entrega_obra_civil', 'restablecimiento_servicio', 'mantenimiento']);

                return response()->json(array(
                    "resultados" => $userCola,
                    "mes" => $mes,
                    "lider" => 0,
                    "anio" => $anio,
                    "cantIPAL" => $cantLiderIPAL[0]->cantidadEjecutadas,
                    "cantCalidad" => $cantLiderCali[0]->cantidadEjecutadas,
                    "cantObservacion" => $cantLiderObser[0]->cantidadEjecutadas,
                    "cantAmbiental" => $cantLiderAmbiente[0]->cantidadEjecutadas,

                    "cantIPALConforme" => $cantLiderIPAL[0]->cantConforme,
                    "cantIPALNoConforme" => $cantLiderIPAL[0]->cantNoConforme,
                    "cantIPALCerradas" => $cantLiderIPAL[0]->cantCerradas,
                    "cantIPALAbiertas" => $cantLiderIPAL[0]->cantAbiertas,

                    "cantCalidadConforme" => $cantLiderCali[0]->cantConforme,
                    "cantCalidadNoConforme" => $cantLiderCali[0]->cantNoConforme,
                    "cantCalidadCerradas" => $cantLiderCali[0]->cantCerradas,
                    "cantCalidadAbiertas" => $cantLiderCali[0]->cantAbiertas,

                    "cantObservacionConforme" => $cantLiderObser[0]->cantConforme,
                    "cantObservacionNoConforme" => $cantLiderObser[0]->cantNoConforme,
                    "cantObservacionCerradas" => $cantLiderObser[0]->cantCerradas,
                    "cantObservacionAbiertas" => $cantLiderObser[0]->cantAbiertas,

                    "cantAmbientalConforme" => $cantLiderAmbiente[0]->cantConforme,
                    "cantAmbientalNoConforme" => $cantLiderAmbiente[0]->cantNoConforme,
                    "cantAmbientalCerradas" => $cantLiderAmbiente[0]->cantCerradas,
                    "cantAmbientalAbiertas" => $cantLiderAmbiente[0]->cantAbiertas,

                    "cantInspeccionSeguridad"           => $cantLiderInspeccionesSeguridad[0]->cantidadEjecutadas,
                    "cantInspeccionSeguridadConforme"   => $cantLiderInspeccionesSeguridad[0]->cantConforme,
                    "cantInspeccionSeguridadNoConforme" => $cantLiderInspeccionesSeguridad[0]->cantNoConforme,
                    "cantInspeccionSeguridadAbiertas"   => $cantLiderInspeccionesSeguridad[0]->cantCerradas,
                    "cantInspeccionSeguridadCerradas"   => $cantLiderInspeccionesSeguridad[0]->cantAbiertas,

                    "cantInspeccionKitDerrames"           => $cantLiderInspeccionesKitDerrames[0]->cantidadEjecutadas,
                    "cantInspeccionKitDerramesConforme"   => $cantLiderInspeccionesKitDerrames[0]->cantConforme,
                    "cantInspeccionKitDerramesNoConforme" => $cantLiderInspeccionesKitDerrames[0]->cantNoConforme,
                    "cantInspeccionKitDerramesAbiertas"   => $cantLiderInspeccionesKitDerrames[0]->cantCerradas,
                    "cantInspeccionKitDerramesCerradas"   => $cantLiderInspeccionesKitDerrames[0]->cantAbiertas,

                    "cantInspeccionLocativa"           => $cantLiderInspeccionesLocativa[0]->cantidadEjecutadas,
                    "cantInspeccionLocativaConforme"   => $cantLiderInspeccionesLocativa[0]->cantConforme,
                    "cantInspeccionLocativaNoConforme" => $cantLiderInspeccionesLocativa[0]->cantNoConforme,
                    "cantInspeccionLocativaAbiertas"   => $cantLiderInspeccionesLocativa[0]->cantCerradas,
                    "cantInspeccionLocativaCerradas"   => $cantLiderInspeccionesLocativa[0]->cantAbiertas,

                    "mesD" => $mesLetra


                ));
            }

            return response()->json(array(
                "resultados" => $userLider,
                "lider" => 1,
                "mes" => $mes,
                "anio" => $anio,
                "cantIPAL" => $cantLiderIPAL[0]->cantidadEjecutadas,
                "cantCalidad" => $cantLiderCali[0]->cantidadEjecutadas,
                "cantObservacion" => $cantLiderObser[0]->cantidadEjecutadas,
                "cantAmbiental" => $cantLiderAmbiente[0]->cantidadEjecutadas,

                "cantIPALConforme" => $cantLiderIPAL[0]->cantConforme,
                "cantIPALNoConforme" => $cantLiderIPAL[0]->cantNoConforme,
                "cantIPALCerradas" => $cantLiderIPAL[0]->cantCerradas,
                "cantIPALAbiertas" => $cantLiderIPAL[0]->cantAbiertas,

                "cantCalidadConforme" => $cantLiderCali[0]->cantConforme,
                "cantCalidadNoConforme" => $cantLiderCali[0]->cantNoConforme,
                "cantCalidadCerradas" => $cantLiderCali[0]->cantCerradas,
                "cantCalidadAbiertas" => $cantLiderCali[0]->cantAbiertas,

                "cantObservacionConforme" => $cantLiderObser[0]->cantConforme,
                "cantObservacionNoConforme" => $cantLiderObser[0]->cantNoConforme,
                "cantObservacionCerradas" => $cantLiderObser[0]->cantCerradas,
                "cantObservacionAbiertas" => $cantLiderObser[0]->cantAbiertas,

                "cantAmbientalConforme" => $cantLiderAmbiente[0]->cantConforme,
                "cantAmbientalNoConforme" => $cantLiderAmbiente[0]->cantNoConforme,
                "cantAmbientalCerradas" => $cantLiderAmbiente[0]->cantCerradas,
                "cantAmbientalAbiertas" => $cantLiderAmbiente[0]->cantAbiertas,

                "cantInspeccionSeguridad"           => $cantLiderInspeccionesSeguridad[0]->cantidadEjecutadas,
                "cantInspeccionSeguridadConforme"   => $cantLiderInspeccionesSeguridad[0]->cantConforme,
                "cantInspeccionSeguridadNoConforme" => $cantLiderInspeccionesSeguridad[0]->cantNoConforme,
                "cantInspeccionSeguridadAbiertas"   => $cantLiderInspeccionesSeguridad[0]->cantCerradas,
                "cantInspeccionSeguridadCerradas"   => $cantLiderInspeccionesSeguridad[0]->cantAbiertas,

                "cantInspeccionKitDerrames"           => $cantLiderInspeccionesKitDerrames[0]->cantidadEjecutadas,
                "cantInspeccionKitDerramesConforme"   => $cantLiderInspeccionesKitDerrames[0]->cantConforme,
                "cantInspeccionKitDerramesNoConforme" => $cantLiderInspeccionesKitDerrames[0]->cantNoConforme,
                "cantInspeccionKitDerramesAbiertas"   => $cantLiderInspeccionesKitDerrames[0]->cantCerradas,
                "cantInspeccionKitDerramesCerradas"   => $cantLiderInspeccionesKitDerrames[0]->cantAbiertas,

                "cantInspeccionLocativa"           => $cantLiderInspeccionesLocativa[0]->cantidadEjecutadas,
                "cantInspeccionLocativaConforme"   => $cantLiderInspeccionesLocativa[0]->cantConforme,
                "cantInspeccionLocativaNoConforme" => $cantLiderInspeccionesLocativa[0]->cantNoConforme,
                "cantInspeccionLocativaAbiertas"   => $cantLiderInspeccionesLocativa[0]->cantCerradas,
                "cantInspeccionLocativaCerradas"   => $cantLiderInspeccionesLocativa[0]->cantAbiertas,

                "mesD" => $mesLetra

            ));
        }

        if ($opc == "4") //Consulta Ubicación GPS Cuadrillas y cuadrillas
        {

            $pre = $request->all()["pre"];

            if ($pre == "rds") {

                $cad = "

                select id_lider,id_movil,( rh1.nombres + ' ' + rh1.apellidos) as nombre,
                            ( rh2.nombres + ' ' + rh2.apellidos) as nombreAux1,
                            ( rh3.nombres + ' ' + rh3.apellidos) as nombreAux2,
                            ( rh4.nombres + ' ' + rh4.apellidos) as nombreAux3,
                            ( rh5.nombres + ' ' + rh5.apellidos) as nombreAux4,
                            ( rh6.nombres + ' ' + rh6.apellidos) as nombreAux5,
                            ( rh7.nombres + ' ' + rh7.apellidos) as nombreAux6,
                            ( rh8.nombres + ' ' + rh8.apellidos) as nombreCond,
                            id_aux1,id_aux2,id_aux3,id_aux4,id_aux5,id_aux6,id_conductor
                            from rds_gop_cuadrilla INNER JOIN rh_personas as rh1 ON id_lider = rh1.identificacion
                            LEFT JOIN gop_tipo_cuadrilla ON   'T' +id_est_cuadrilla = id_tipo_cuadrilla
                            LEFT JOIN rh_personas as rh2 ON id_aux1 = rh2.identificacion
                            LEFT JOIN rh_personas as rh3 ON id_aux2 = rh3.identificacion
                            LEFT JOIN rh_personas as rh4 ON id_aux3 = rh4.identificacion
                            LEFT JOIN rh_personas as rh5 ON id_aux4 = rh5.identificacion
                            LEFT JOIN rh_personas as rh6 ON id_aux5 = rh6.identificacion
                            LEFT JOIN rh_personas as rh7 ON id_aux6 = rh7.identificacion
                            LEFT JOIN rh_personas as rh8 ON id_conductor = rh8.identificacion
                            WHERE rh1.id_estado = 'EP03'
                            GROUP BY id_lider,id_movil,rh1.apellidos,rh1.nombres,
                                id_aux1,id_aux2,id_aux3,id_aux4,id_aux5,id_aux6,id_conductor,
                                rh2.apellidos,rh2.nombres,rh3.apellidos,rh3.nombres,
                                rh4.apellidos,rh4.nombres,rh5.apellidos,rh5.nombres,
                                rh6.apellidos,rh6.nombres,rh7.apellidos,rh7.nombres,
                                rh8.apellidos,rh8.nombres
                            ORDER BY rh1.nombres

                ";

                $cuadrillasGPS = DB::select($cad);
            } else if ($pre == "tp") {
                $cad = "SELECT 
                        cuadrilla.cuadrillas_id                     AS id_movil,
                        persona.identificacion                      AS identificacion,
                        (persona.nombres + ' ' + persona.apellidos) AS nombre,
                        rol.nombre                                  AS rol
                      FROM tp_cuadrillas AS cuadrilla

                        INNER JOIN tp_cuadrilla_miembros AS miembro
                          ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                        INNER JOIN rh_personas AS persona
                          ON persona.id_persona = miembro.id_persona

                        INNER JOIN tp_cuadrilla_roles AS rol
                          ON miembro.roles_id = rol.roles_id

                      WHERE 1 = 1
                        AND cuadrilla.id_proyecto = 'PJ00000123'
                        -- AND rol.roles_id = 1 
                        -- AND cuadrilla.cuadrilla_id_estado = 1
                        -- AND persona.id_estado = 'EP03'
                        -- AND cuadrilla.cuadrillas_id = 1
                      ORDER BY 
                        cuadrilla.cuadrillas_id ASC,
                        rol.roles_id ASC;";

                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            } else if ($pre == "sct") {
                // =====================================================================================
                // MIEMBROS CUADRILLAS OC
                // =====================================================================================
                $cad = "SELECT 
                          cuadrilla.cuadrilla_id                                AS id_movil_solo,
                          CONCAT('oc_', cuadrilla.cuadrilla_id)                 AS id_movil,
                          persona.identificacion                                AS identificacion,
                          persona.nombres + ' ' + persona.apellidos      AS nombre,
                          rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000133'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1

                        UNION ALL

                        SELECT 
                          cuadrilla.cuadrillas_id                                 AS id_movil_solo,
                            CONCAT('tp_', cuadrilla.cuadrillas_id)                AS id_movil,
                            persona.identificacion                                AS identificacion,
                            persona.nombres + ' ' + persona.apellidos      AS nombre,
                            rol.nombre                                            AS rol
                        FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                            ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                            INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000133'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1

                        ORDER BY 
                            id_movil_solo ASC,
                            rol ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            } 


            else if ($pre == "cls"  ) {
                // =====================================================================================
                // MIEMBROS CUADRILLAS OC
                // =====================================================================================
                $cad = "SELECT 
                          cuadrilla.cuadrilla_id                                AS id_movil_solo,
                          CONCAT('oc_', cuadrilla.cuadrilla_id)                 AS id_movil,
                          persona.identificacion                                AS identificacion,
                          persona.nombres + ' ' + persona.apellidos      AS nombre,
                          rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000175'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1

                        UNION ALL

                        SELECT 
                          cuadrilla.cuadrillas_id                                 AS id_movil_solo,
                            CONCAT('tp_', cuadrilla.cuadrillas_id)                AS id_movil,
                            persona.identificacion                                AS identificacion,
                            persona.nombres + ' ' + persona.apellidos      AS nombre,
                            rol.nombre                                            AS rol
                        FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                            ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                            INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000175'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1

                        ORDER BY 
                            id_movil_solo ASC,
                            rol ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            }

            else if ($pre == "ara"  ) {
                // =====================================================================================
                // MIEMBROS CUADRILLAS OC
                // =====================================================================================
                $cad = "SELECT 
                          cuadrilla.cuadrilla_id                                AS id_movil_solo,
                          CONCAT('oc_', cuadrilla.cuadrilla_id)                 AS id_movil,
                          persona.identificacion                                AS identificacion,
                          persona.nombres + ' ' + persona.apellidos      AS nombre,
                          rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000181'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1

                        UNION ALL

                        SELECT 
                          cuadrilla.cuadrillas_id                                 AS id_movil_solo,
                            CONCAT('tp_', cuadrilla.cuadrillas_id)                AS id_movil,
                            persona.identificacion                                AS identificacion,
                            persona.nombres + ' ' + persona.apellidos      AS nombre,
                            rol.nombre                                            AS rol
                        FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                            ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                            INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000181'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1

                        ORDER BY 
                            id_movil_solo ASC,
                            rol ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            }

               else if ($pre == "cew"  ) {
                // =====================================================================================
                // MIEMBROS CUADRILLAS OC
                // =====================================================================================
                $cad = "SELECT 
                          cuadrilla.cuadrilla_id                                AS id_movil_solo,
                          CONCAT('oc_', cuadrilla.cuadrilla_id)                 AS id_movil,
                          persona.identificacion                                AS identificacion,
                          persona.nombres + ' ' + persona.apellidos      AS nombre,
                          rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        left JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        left JOIN rh_personas AS persona
                            ON persona.id_persona = cuadrilla.id_lider

                        left JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000190'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1

                        UNION ALL

                        SELECT 
                          cuadrilla.cuadrillas_id                                 AS id_movil_solo,
                            CONCAT('tp_', cuadrilla.cuadrillas_id)                AS id_movil,
                            persona.identificacion                                AS identificacion,
                            persona.nombres + ' ' + persona.apellidos      AS nombre,
                            rol.nombre                                            AS rol
                        FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                            ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                            INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000190'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1

                        ORDER BY 
                            id_movil_solo ASC,
                            rol ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            }


            else if ($pre == "ltm"  ) {
                // =====================================================================================
                // MIEMBROS CUADRILLAS OC
                // =====================================================================================
                $cad = "SELECT 
                          cuadrilla.cuadrilla_id                                AS id_movil_solo,
                          CONCAT('oc_', cuadrilla.cuadrilla_id)                 AS id_movil,
                          persona.identificacion                                AS identificacion,
                          persona.nombres + ' ' + persona.apellidos      AS nombre,
                          rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000194'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1

                        UNION ALL

                        SELECT 
                          cuadrilla.cuadrillas_id                                 AS id_movil_solo,
                            CONCAT('tp_', cuadrilla.cuadrillas_id)                AS id_movil,
                            persona.identificacion                                AS identificacion,
                            persona.nombres + ' ' + persona.apellidos      AS nombre,
                            rol.nombre                                            AS rol
                        FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                            ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                            INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000194'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1

                        ORDER BY 
                            id_movil_solo ASC,
                            rol ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            }


             else if ($pre == "EPS" or $pre == "eps"  ) {
                // =====================================================================================
                // MIEMBROS CUADRILLAS OC
                // =====================================================================================
                $cad = "SELECT 
                          cuadrilla.cuadrilla_id                                AS id_movil_solo,
                          CONCAT('oc_', cuadrilla.cuadrilla_id)                 AS id_movil,
                          persona.identificacion                                AS identificacion,
                          persona.nombres + ' ' + persona.apellidos      AS nombre,
                          rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000110'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1

                        UNION ALL

                        SELECT 
                          cuadrilla.cuadrillas_id                                 AS id_movil_solo,
                            CONCAT('tp_', cuadrilla.cuadrillas_id)                AS id_movil,
                            persona.identificacion                                AS identificacion,
                            persona.nombres + ' ' + persona.apellidos      AS nombre,
                            rol.nombre                                            AS rol
                        FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                            ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                            INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000110'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1

                        ORDER BY 
                            id_movil_solo ASC,
                            rol ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            }

            else if ($pre == "mec") {
                // =====================================================================================
                // MIEMBROS CUADRILLAS OC
                // =====================================================================================
                $cad = "SELECT 
                          cuadrilla.cuadrilla_id                                AS id_movil_solo,
                          CONCAT('oc_', cuadrilla.cuadrilla_id)                 AS id_movil,
                          persona.identificacion                                AS identificacion,
                          persona.nombres + ' ' + persona.apellidos      AS nombre,
                          rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000184'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1

                        UNION ALL

                        SELECT 
                          cuadrilla.cuadrilla_id                                 AS id_movil_solo,
                            CONCAT('oc_', cuadrilla.cuadrilla_id)                AS id_movil,
                            persona.identificacion                                AS identificacion,
                            persona.nombres + ' ' + persona.apellidos      AS nombre,
                            rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                            INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                            INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                            INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000184'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1

                        ORDER BY 
                            id_movil_solo ASC,
                            rol ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            }
            else {

                try {
                    $cad = "
                            select id_lider,id_movil,( rh1.nombres + ' ' + rh1.apellidos) as nombre,
                            ( rh2.nombres + ' ' + rh2.apellidos) as nombreAux1,
                            ( rh3.nombres + ' ' + rh3.apellidos) as nombreAux2,
                            ( rh4.nombres + ' ' + rh4.apellidos) as nombreAux3,
                            ( rh8.nombres + ' ' + rh8.apellidos) as nombreCond,
                            '' as nombreAux4,
                            '' as nombreAux5,
                            '' as nombreAux6,
                            id_aux1,id_aux2,id_aux3,'' as id_aux4,'' as id_aux5,'' as id_aux6,id_conductor
                            from " . $pre . "_gop_cuadrilla INNER JOIN rh_personas as rh1 ON id_lider = rh1.identificacion
                            LEFT JOIN gop_tipo_cuadrilla ON   'T' +id_est_cuadrilla = id_tipo_cuadrilla
                            LEFT JOIN rh_personas as rh2 ON id_aux1 = rh2.identificacion
                            LEFT JOIN rh_personas as rh3 ON id_aux2 = rh3.identificacion
                            LEFT JOIN rh_personas as rh4 ON id_aux3 = rh4.identificacion
                            LEFT JOIN rh_personas as rh8 ON id_conductor = rh8.identificacion
                            WHERE rh1.id_estado = 'EP03'
                            GROUP BY id_lider,id_movil,rh1.apellidos,rh1.nombres,
                                id_aux1,id_aux2,id_aux3,id_conductor,
                                rh2.apellidos,rh2.nombres,rh3.apellidos,rh3.nombres,
                                rh4.apellidos,rh4.nombres,
                                rh8.apellidos,rh8.nombres
                            ORDER BY rh1.nombres
                
                    ";

                    $cuadrillasGPS = DB::select($cad);
                } catch (\Exception $e) {
                    $cuadrillasGPS = [];
                }
            }



            return response()->json(array(
                'cuarillasGPS' => $cuadrillasGPS
            ));
        }

        if ($opc == "5") //Consulta inspecciones realizadas
        {

            $user = $request->all()["user"];
            $anio = $request->all()["anio"];
            $mes = $request->all()["mes"];
            $tipo_inspeccion = $request->all()["tipo_inspeccion"];

            $inspeccion = DB::table('ins_inspeccion')
                ->leftJoin('gop_proyectos as pry1', 'pry1.id_proyecto', '=', 'ins_inspeccion.id_tipo_proyecto')
                ->leftJoin('gop_proyectos as pry2', 'pry2.id_proyecto', '=', 'ins_inspeccion.id_proyecto')
                ->rightjoin('rh_personas as p2', 'p2.identificacion', '=', 'ins_inspeccion.lider')
                ->join('ins_estados as es', 'es.id_estado', '=', 'ins_inspeccion.estado')
                ->select(
                    'id_inspeccion',
                    'pry1.nombre',
                    'fecha_servidor',
                    DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"),
                    'supervisor',
                    'lider',
                    'movil',
                    'tipo_inspeccion',
                    'resultado',
                    'estado',
                    'es.nombre as nombreE',
                    'calificacion',
                    'pry2.nombre as ceco'
                )
                ->where('supervisor', $user)
                ->where('tipo_inspeccion', $tipo_inspeccion)
                ->where('mes', $mes)
                ->where('anio', $anio)
                ->get();

            return response()->json(array(
                'inspeccion' => $inspeccion
            ));
        }

        if ($opc == "6") //Consulta datos de CCosto
        {   /*
            $proyectos = DB::Table('gop_proyectos')
                            ->orderBy('nombre')
                            ->get(['nombre as proyecto','id_proyecto']);
            */

            $proyectos = DB::Table('gop_proyectos')
                ->orderBy('nombre')
                ->select(DB::raw("ccosto+ ' - ' + nombre as proyecto"), 'id_proyecto')
                ->get();


            return response()->json($proyectos);
        }

        if ($opc == "7") //Consulta datos de personal por Ccosto
        {
            $id_proyecto = $request->all()["id_proyecto"];

            $lideres = DB::Table('rh_personas')
                ->where('id_proyecto', $id_proyecto)
                ->where('id_estado', 'EP03')
                ->orderBy('nombres')
                ->select('identificacion', DB::Raw("(nombres + ' ' + apellidos) as nombre"))
                ->get();

            return response()->json($lideres);
        }

        if ($opc == "8") { //Consulta sedes
            $sedes = DB::Table('gop_proyectos_sedes')
                ->where('prefijo', $request->all()["pre"])
                ->orderBy('nombre_sede')
                ->get(['nombre_sede', 'id']);
            return response()->json($sedes);
        }
    }

    /*****************************************************************************/
    /******** FIN WEB SERVICES APP MÓVIL PLAN DE SUPERVISIÓN - CONSULTA **********/
    /*****************************************************************************/


    /*****************************************************************************/
    /******** WEB SERVICES APP MÓVIL PLAN DE SUPERVISIÓN - GUARDAR **************/
    /*****************************************************************************/
    public function savePlanSupervision(Request $request)
    {
        $opc = $request->all()["opc"];

        if ($opc == "1") //Save ubicación GPS Personal de supervisión
        {
            $user = $request->all()["user"];
            $coor = $request->all()["coor"];

            DB::Table('ins_gps_tracker')
                ->insert(
                    array(
                        array(
                            'prefijo' => 'NA',
                            'latitud' => explode(",", $coor)[0],
                            'longitud' => explode(",", $coor)[1],
                            'fecha' => explode(" ", $this->fechaALong)[0],
                            'hora' =>  explode(" ", $this->fechaALong)[1],
                            'usuario_movil' => $user
                        )
                    )
                );

            return response()->json("1");
        }

        if ($opc == "2") //Save Inspecciones
        {

            $cabezaInpeccion = $request->all()["cabeza"];
            $detallerInpseccion = $request->all()["cuerpo"];

            $tipo = $request->all()["tip"]; //1 -> IPALES 2 -> Calidad 3 -> Observación del comportamiento 4 -> Ambiental

            $orden = $cabezaInpeccion["orden"];
            $pre = $cabezaInpeccion["pre"];

            $observacion = $cabezaInpeccion["observacion"];
            $charla = $cabezaInpeccion["charla"];
            $usuario = $cabezaInpeccion["user"];

            $mes = $cabezaInpeccion["mes"];
            $anio = $cabezaInpeccion["anio"];

            $id_proyecto = $cabezaInpeccion["id_proyecto"];

            $lider = $cabezaInpeccion["lider"];

            $id_proy = DB::table('gop_proyectos')
                ->where('prefijo_db', $pre)
                ->value('id_proyecto');


            $actividadEspecifica = "";
            $coordenadas = "";
            $direccion = "";

            if (isset($request->all()["cabeza"]["actividadEspecifica"]))
                $actividadEspecifica = $request->all()["cabeza"]["actividadEspecifica"];

            if (isset($request->all()["cabeza"]["coordenadas"]))
                $coordenadas = $request->all()["cabeza"]["coordenadas"];

            if (isset($request->all()["cabeza"]["direccion"]))
                $direccion = $request->all()["cabeza"]["direccion"];

            // ====================================================================================================================
            // CONFORMACION DE CUADRILLA SOC-SOT
            // ====================================================================================================================



            if ($pre == 'sct' || $pre == 'tp' || $pre == 'cls' || $pre == 'ltm' || $pre == 'cew' || $pre == 'eps' || $pre == 'ara') {
                $lider_sct = $request->all()["cabeza"]["lider"];
                $grupoOperativo = null;
                $cad = "";

                $miembros_cuadrilla = null;

                if ($pre == 'sct') {
                    // ==================================================================================================
                    // SE OBTIENE LA CONFORMACIÓN DE LA CUADRILLA BAJO EL PROYECTO PJ00000133 - OC CUADRILLAS
                    // ==================================================================================================
                    $cad = "SELECT 
                          cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                          cuadrilla.placa                             AS matricula,
                          cuadrilla.cuadrilla_id                      AS id_movil,
                          persona.identificacion                      AS identificacion,
                          (persona.nombres + ' ' + persona.apellidos) AS nombre,
                          rol.nombre                                  AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN rh_personas AS persona_lider
                            ON persona_lider.id_persona = cuadrilla.id_lider
                          AND persona_lider.identificacion = '{$lider_sct}'

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000133'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1
                        ORDER BY 
                          cuadrilla.cuadrilla_id ASC,
                          rol.roles_id ASC;";
                    $miembros_cuadrilla = DB::select($cad);

                    $existen_miembros_cuadrilla = false;
                    foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                        $existen_miembros_cuadrilla = true;
                    }

                    // ==================================================================================================
                    // SI NO EXISTE CONFORMACIÓN PROYECTO OC CUADRILLAS - SCT SE OBTIENE LA CONFORMACIÓN DE TP CUADRILLAS
                    // ==================================================================================================
                    if (!$existen_miembros_cuadrilla) {
                        $cad = "SELECT 
                            cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                            cuadrilla.placa                             AS matricula,
                            cuadrilla.cuadrillas_id                     AS id_movil,
                            persona.identificacion                      AS identificacion,
                            (persona.nombres + ' ' + persona.apellidos) AS nombre,
                            rol.nombre                                  AS rol
                          FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN rh_personas AS persona_lider
                              ON persona_lider.id_persona = cuadrilla.id_lider
                              AND persona_lider.identificacion = '{$lider_sct}'

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                              ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                              ON persona.id_persona = miembro.id_persona

                            INNER JOIN tp_cuadrilla_roles AS rol
                              ON miembro.roles_id = rol.roles_id

                          WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000133'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1
                          ORDER BY 
                            cuadrilla.cuadrillas_id ASC,
                            rol.roles_id ASC;";
                        $miembros_cuadrilla = DB::select($cad);
                    }
                } else if ($pre == 'tp') {
                    $cad = "SELECT 
                            cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                            cuadrilla.placa                             AS matricula,
                            cuadrilla.cuadrillas_id                     AS id_movil,
                            persona.identificacion                      AS identificacion,
                            (persona.nombres + ' ' + persona.apellidos) AS nombre,
                            rol.nombre                                  AS rol
                          FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN rh_personas AS persona_lider
                              ON persona_lider.id_persona = cuadrilla.id_lider
                              AND persona_lider.identificacion = '{$lider_sct}'

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                              ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                              ON persona.id_persona = miembro.id_persona

                            INNER JOIN tp_cuadrilla_roles AS rol
                              ON miembro.roles_id = rol.roles_id

                          WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000123'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1
                          ORDER BY 
                            cuadrilla.cuadrillas_id ASC,
                            rol.roles_id ASC;";
                    $miembros_cuadrilla = DB::select($cad);
                } else if ($pre == 'cls') {
                    $cad = "SELECT 
                            cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                            cuadrilla.placa                             AS matricula,
                            cuadrilla.cuadrillas_id                     AS id_movil,
                            persona.identificacion                      AS identificacion,
                            (persona.nombres + ' ' + persona.apellidos) AS nombre,
                            rol.nombre                                  AS rol
                          FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN rh_personas AS persona_lider
                              ON persona_lider.id_persona = cuadrilla.id_lider
                              AND persona_lider.identificacion = '{$lider_sct}'

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                              ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                              ON persona.id_persona = miembro.id_persona

                            INNER JOIN tp_cuadrilla_roles AS rol
                              ON miembro.roles_id = rol.roles_id

                          WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000175'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1
                          ORDER BY 
                            cuadrilla.cuadrillas_id ASC,
                            rol.roles_id ASC;";
                    $miembros_cuadrilla = DB::select($cad);
                }
                else if ($pre == 'ara') {
                    $cad = "SELECT 
                            cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                            cuadrilla.placa                             AS matricula,
                            cuadrilla.cuadrillas_id                     AS id_movil,
                            persona.identificacion                      AS identificacion,
                            (persona.nombres + ' ' + persona.apellidos) AS nombre,
                            rol.nombre                                  AS rol
                          FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN rh_personas AS persona_lider
                              ON persona_lider.id_persona = cuadrilla.id_lider
                              AND persona_lider.identificacion = '{$lider_sct}'

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                              ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                              ON persona.id_persona = miembro.id_persona

                            INNER JOIN tp_cuadrilla_roles AS rol
                              ON miembro.roles_id = rol.roles_id

                          WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000181'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1
                          ORDER BY 
                            cuadrilla.cuadrillas_id ASC,
                            rol.roles_id ASC;";
                    $miembros_cuadrilla = DB::select($cad);
                }
                else if ($pre == 'ltm') {
                    $cad = "SELECT 
                            cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                            cuadrilla.placa                             AS matricula,
                            cuadrilla.cuadrillas_id                     AS id_movil,
                            persona.identificacion                      AS identificacion,
                            (persona.nombres + ' ' + persona.apellidos) AS nombre,
                            rol.nombre                                  AS rol
                          FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN rh_personas AS persona_lider
                              ON persona_lider.id_persona = cuadrilla.id_lider
                              AND persona_lider.identificacion = '{$lider_sct}'

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                              ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                              ON persona.id_persona = miembro.id_persona

                            INNER JOIN tp_cuadrilla_roles AS rol
                              ON miembro.roles_id = rol.roles_id

                          WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000194'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1
                          ORDER BY 
                            cuadrilla.cuadrillas_id ASC,
                            rol.roles_id ASC;";
                    $miembros_cuadrilla = DB::select($cad);
                }
             
            else if ($pre == "cew"  ) {
                // =====================================================================================
                // MIEMBROS CUADRILLAS OC
                // =====================================================================================
                $cad = "SELECT 
                          cuadrilla.cuadrilla_id                                AS id_movil_solo,
                          CONCAT('oc_', cuadrilla.cuadrilla_id)                 AS id_movil,
                          persona.identificacion                                AS identificacion,
                          persona.nombres + ' ' + persona.apellidos      AS nombre,
                          rol.nombre                                            AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000190'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1

                        UNION ALL

                        SELECT 
                          cuadrilla.cuadrillas_id                                 AS id_movil_solo,
                            CONCAT('tp_', cuadrilla.cuadrillas_id)                AS id_movil,
                            persona.identificacion                                AS identificacion,
                            persona.nombres + ' ' + persona.apellidos      AS nombre,
                            rol.nombre                                            AS rol
                        FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                            ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                            INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000194'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1

                        ORDER BY 
                            id_movil_solo ASC,
                            rol ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS[$id_movil]['id_movil']     = $id_movil;
                        $cuadrillasGPS[$id_movil]['id_lider']     = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombre']       = $nombre_miembro;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS[$id_movil]['id_conductor'] = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreCond']   = $nombre_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;

                        $cuadrillasGPS[$id_movil]['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                        $cuadrillasGPS[$id_movil]['nombreAux' . $cantidad_auxiliares] = $nombre_miembro;
                    }
                }

                $cuadrillasGPS = array_values($cuadrillasGPS);
            }
                else if ($pre == 'eps') {
                    $cad = "SELECT 
                            cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                            cuadrilla.placa                             AS matricula,
                            cuadrilla.cuadrillas_id                     AS id_movil,
                            persona.identificacion                      AS identificacion,
                            (persona.nombres + ' ' + persona.apellidos) AS nombre,
                            rol.nombre                                  AS rol
                          FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN rh_personas AS persona_lider
                              ON persona_lider.id_persona = cuadrilla.id_lider
                              AND persona_lider.identificacion = '{$lider_sct}'

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                              ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                              ON persona.id_persona = miembro.id_persona

                            INNER JOIN tp_cuadrilla_roles AS rol
                              ON miembro.roles_id = rol.roles_id

                          WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000110'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1
                          ORDER BY 
                            cuadrilla.cuadrillas_id ASC,
                            rol.roles_id ASC;";
                    $miembros_cuadrilla = DB::select($cad);
                }


                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                    $id_movil               = $miembro->id_movil;
                    $rol                    = $miembro->rol;
                    $matricula              = $miembro->matricula;
                    $id_tipo_cuadrilla      = $miembro->id_tipo_cuadrilla;

                    $identificacion_miembro = $miembro->identificacion;
                    $nombre_miembro         = $miembro->nombre;

                    if (!in_array($id_movil, $ids_cuadrillas)) {
                        $ids_cuadrillas[] = $id_movil;
                        $cantidad_auxiliares = 0;

                        $cuadrillasGPS['id_aux1'] = '';
                        $cuadrillasGPS['id_aux2'] = '';
                        $cuadrillasGPS['id_aux3'] = '';
                        $cuadrillasGPS['id_aux4'] = '';
                        $cuadrillasGPS['id_aux5'] = '';
                        $cuadrillasGPS['id_aux6'] = '';
                        $cuadrillasGPS['id_conductor'] = '';
                    }

                    if ($rol === 'LIDER') {
                        $cuadrillasGPS['id_movil']          = $id_movil;
                        $cuadrillasGPS['id_lider']          = $identificacion_miembro;
                        $cuadrillasGPS['matricula']         = $matricula;
                        $cuadrillasGPS['id_tipo_cuadrilla'] = $id_tipo_cuadrilla;
                    } else if ($rol === 'CONDUCTOR') {
                        $cuadrillasGPS['id_conductor'] = $identificacion_miembro;
                    } else if ($rol === 'AUXILIAR') {
                        $cantidad_auxiliares++;
                        $cuadrillasGPS['id_aux' . $cantidad_auxiliares]    = $identificacion_miembro;
                    }
                }

                $grupoOperativo = (object) $cuadrillasGPS;
            }
            // ====================================================================================================================
            // CONFORMACION DE CUADRILLA DIFERENTES A SOC-SOT Y A TP
            // ====================================================================================================================
            else if ($tipo != 3) {
                if ($pre == "")
                    $pre = $id_proyecto;
                $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                    ->where('id_lider', $lider)
                    ->get(['id_aux1', 'id_aux2', 'id_aux3', 'id_conductor', 'matricula', 'id_tipo_cuadrilla', 'id_movil']);

                if (count($grupoOperativo) == 0) {
                    $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                        ->where('id_aux1', $lider)
                        ->get([DB::raw("'' as id_aux1"), DB::raw("'' as id_aux2"), DB::raw("'' as id_aux3"), DB::raw("'' as id_conductor"), 'matricula', 'id_tipo_cuadrilla', 'id_movil']);

                    if (count($grupoOperativo) == 0) {
                        $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                            ->where('id_aux2', $lider)
                            ->get([DB::raw("'' as id_aux1"), DB::raw("'' as id_aux2"), DB::raw("'' as id_aux3"), DB::raw("'' as id_conductor"), 'matricula', 'id_tipo_cuadrilla', 'id_movil']);

                        if (count($grupoOperativo) == 0) {
                            $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                ->where('id_aux3', $lider)
                                ->get([DB::raw("'' as id_aux1"), DB::raw("'' as id_aux2"), DB::raw("'' as id_aux3"), DB::raw("'' as id_conductor"), 'matricula', 'id_tipo_cuadrilla', 'id_movil']);

                            if (count($grupoOperativo) == 0) {
                                $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                    ->where('id_conductor', $lider)
                                    ->get([DB::raw("'' as id_aux1"), DB::raw("'' as id_aux2"), DB::raw("'' as id_aux3"), DB::raw("'' as id_conductor"), 'matricula', 'id_tipo_cuadrilla', 'id_movil']);

                                if (count($grupoOperativo) == 0) {
                                    if ($pre == "rds") {
                                        $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                            ->where('id_aux4', $lider)
                                            ->get([DB::raw("'' as id_aux1"), DB::raw("'' as id_aux2"), DB::raw("'' as id_aux3"), DB::raw("'' as id_conductor"), 'matricula', 'id_tipo_cuadrilla', 'id_movil']);

                                        if (count($grupoOperativo) == 0) {
                                            $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                                ->where('id_aux5', $lider)
                                                ->get([DB::raw("'' as id_aux1"), DB::raw("'' as id_aux2"), DB::raw("'' as id_aux3"), DB::raw("'' as id_conductor"), 'matricula', 'id_tipo_cuadrilla', 'id_movil']);

                                            if (count($grupoOperativo) == 0) {
                                                $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                                    ->where('id_aux6', $lider)
                                                    ->get([DB::raw("'' as id_aux1"), DB::raw("'' as id_aux2"), DB::raw("'' as id_aux3"), DB::raw("'' as id_conductor"), 'matricula', 'id_tipo_cuadrilla', 'id_movil']);

                                                if (count($grupoOperativo) > 0)
                                                    $grupoOperativo = $grupoOperativo[0];
                                            } else
                                                $grupoOperativo = $grupoOperativo[0];
                                        } else
                                            $grupoOperativo = $grupoOperativo[0];
                                    }
                                } else
                                    $grupoOperativo = $grupoOperativo[0];
                            } else
                                $grupoOperativo = $grupoOperativo[0];
                        } else
                            $grupoOperativo = $grupoOperativo[0];
                    } else
                        $grupoOperativo = $grupoOperativo[0];
                } else
                    $grupoOperativo = $grupoOperativo[0];
            }

            $resultado = $cabezaInpeccion["resultado"];
            $estado = $cabezaInpeccion["estado"];



            //DB::beginTransaction();
            try {

                $conse = "";

                if (isset($request->cabeza["id_inspecion"])) {
                    $conse = $request->cabeza["id_inspecion"];
                    $arrayData = array(
                        'prefijo' => $pre,
                        'id_tipo_proyecto' => $id_proy,
                        'supervisor' => $usuario,
                        'fecha_servidor' => $this->fechaALong,
                        'sede' => isset($cabezaInpeccion["sede"]) ? $cabezaInpeccion["sede"] : "",
                        'lider' => $lider,
                        'aux1' => ($tipo == 3 ? '' : $grupoOperativo->id_aux1),
                        'aux2' => ($tipo == 3 ? '' : $grupoOperativo->id_aux2),
                        'aux3' => ($tipo == 3 ? '' : $grupoOperativo->id_aux3),
                        'conductor' => ($tipo == 3 ? '' : $grupoOperativo->id_conductor),
                        'matricula' => ($tipo == 3 ? '' : $grupoOperativo->matricula),
                        'tipo_cuadrilla' => ($tipo == 3 ? '' : $grupoOperativo->id_tipo_cuadrilla),
                        'movil' => ($tipo == 3 ? '' : $grupoOperativo->id_movil),
                        'tipo_inspeccion' => $tipo,
                        'tipo_ingreso_inspeccion' => 'SSL',
                        'resultado' => $resultado,
                        'estado' => (trim($resultado) == "C" ? "E2" : $estado),
                        'charla_calificacion' =>  $charla,
                        'observacion' => $observacion,
                        'anio' => $anio,
                        'mes' => $mes,
                        'id_proyecto' => $id_proyecto,
                        'actividad_especifica' => $actividadEspecifica,
                        'coordenadas' => $coordenadas,
                        'direccion_inspeccion' => $direccion
                    );
                    DB::Table('ins_inspeccion')
                        ->where("id_inspeccion", $conse)
                        ->where("id_orden", $orden)
                        ->update($arrayData);
                } else {
                    $conse = self::generaConsecutivo("ID_INSPEC");
                    $arrayData = array(
                        array(
                            'id_inspeccion' => $conse,
                            'id_orden' => $orden,
                            'prefijo' => $pre,
                            'id_tipo_proyecto' => $id_proy,
                            'supervisor' => $usuario,
                            'sede' => isset($cabezaInpeccion["sede"]) ? $cabezaInpeccion["sede"] : "",
                            'fecha_servidor' => $this->fechaALong,
                            'lider' => $lider,
                            'aux1' => ($tipo == 3 ? '' : $grupoOperativo->id_aux1),
                            'aux2' => ($tipo == 3 ? '' : $grupoOperativo->id_aux2),
                            'aux3' => ($tipo == 3 ? '' : $grupoOperativo->id_aux3),
                            'conductor' => ($tipo == 3 ? '' : $grupoOperativo->id_conductor),
                            'matricula' => ($tipo == 3 ? '' : $grupoOperativo->matricula),
                            'tipo_cuadrilla' => ($tipo == 3 ? '' : $grupoOperativo->id_tipo_cuadrilla),
                            'movil' => ($tipo == 3 ? '' : $grupoOperativo->id_movil),
                            'tipo_inspeccion' => $tipo,
                            'tipo_ingreso_inspeccion' => 'SSL',
                            'resultado' => $resultado,
                            'estado' => (trim($resultado) == "C" ? "E2" : $estado),
                            'charla_calificacion' =>  $charla,
                            'observacion' => $observacion,
                            'anio' => $anio,
                            'mes' => $mes,
                            'id_proyecto' => $id_proyecto,
                            'actividad_especifica' => $actividadEspecifica,
                            'coordenadas' => $coordenadas,
                            'direccion_inspeccion' => $direccion
                        )
                    );
                    DB::Table('ins_inspeccion')
                        ->insert($arrayData);
                }

                $desEvento = "IPAL";
                if ($tipo == "2")
                    $desEvento = "CALIDAD";

                if ($tipo == "3")
                    $desEvento = "OBSERVACION_COMPORTAMIENTO";

                if ($tipo == "4")
                    $desEvento = "MEDIO_AMBIENTE";

                if ($tipo == "33")
                    $desEvento = "INSPECCION SEGURIDAD OBRAS CIVILES";

                if ($tipo == "34")
                    $desEvento = "INSPECCION SEGURIDAD TELECOMUNICACIONES";

                if ($tipo == "35")
                    $desEvento = "INSPECCION SEGURIDAD REDES ELECTRICAS";

                if ($tipo == "43")
                    $desEvento = "FORMATO INSPECCION DE KIT PARA LA ATENCION DE DERRAMES";

                if ($tipo == "44")
                    $desEvento = "FORMATO INSPECCION LOCATIVA AMBIENTAL";

                if ($tipo == "46")
                    $desEvento = "FORMATO ENTREGA OBRAS CIVILES";

                if ($tipo == "36")
                    $desEvento = "FORMATO TRABAJOS RDS";

                if ($tipo == "37")
                    $desEvento = "FORMATO CALIDAD OBRAS MT Y BT";




                DB::Table('ssl_eventos')
                    ->insert(array(
                        array(
                            'id_origen' => $lider,
                            'observaciones' => $resultado . " - " . $observacion,
                            'fecha' => explode(" ", $this->fechaALong)[0],
                            'id_orden' => $orden,
                            'prefijo' => $pre,
                            'tipo_evento' => $desEvento,
                            'hora' => explode(" ", $this->fechaALong)[1],
                            'notificacion' => 1,
                            'id_ipal' => $conse
                        )
                    ));




                if (isset($request->all()["cabeza"]["firma"]) && $request->all()["cabeza"]["firma"] != "") {
                    $firma = $request->all()["cabeza"]["firma"];
                    $data = explode(',', $firma);
                    $imagenDigital = base64_decode($data[1]);

                    $ruta = DB::table('rutas_anexos')
                    ->select('url_visualizacion')
                    ->where('tipo_operacion', "ruta")
                    ->get();
                    $ruta = $ruta[0]->url_visualizacion;


                    //$url = 'http://localhost:8080/almacenamiento/public/archivos/guardaFotoInspeccion';
                    $url = $ruta.'archivos/guardaFotoInspeccion';

                    //Nombre Fotografia
                    $nombreArchivo = "Plan_Supervision_" . $usuario . "_" .  substr(md5(uniqid(rand())), 0, 10) . ".png";

                    $super = $usuario;
                    $fecha_movil = $this->fechaShort;
                    $tipoF = 'T04';
                    $turno = "";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $postFields = array(
                        'name' => $nombreArchivo,
                        'img' => $firma
                    );
                    //dd($postFields);

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                    $result = curl_exec($ch);
                    if(curl_errno($ch)){
                        throw new Exception(curl_error($ch));
                    }

                    //dd($result);
                    if ($result = 1) {
                        if (
                            DB::Table('ins_foto')
                            ->where('id_super', $usuario)
                            ->where('id_tipo', $tipoF)
                            ->where('id_inspeccion', $conse)
                            ->where('orden', $conse)
                            ->count() == 0
                        ) {
                            DB::Table('ins_foto')
                                ->insert(array(
                                    array(
                                        'id_super' => $usuario,
                                        'fecha_celular' => $fecha_movil,
                                        'id_tipo' => $tipoF,
                                        'id_inspeccion' => $conse,
                                        'ruta' => $nombreArchivo,
                                        'orden' => $conse
                                    )
                                ));
                        } else {
                            DB::Table('ins_foto')
                                ->where('id_super', $usuario)
                                ->where('id_tipo', $tipoF)
                                ->where('id_inspeccion', $conse)
                                ->where('orden', $conse)
                                ->update(array(
                                    'ruta' => $nombreArchivo
                                ));
                        }
                    }
                    /*try {
                        $id_ftp = ftp_connect("190.60.248.195", 21); //Obtiene un manejador del Servidor FTP
                        ftp_login($id_ftp, "acceso_anexos", "acceso_anexos.2019"); //Se loguea al Servidor FTP
                        ftp_pasv($id_ftp, true); //Se coloca el modo pasivo
                        ftp_chdir($id_ftp, "anexos"); // Nos dirigimos a la carpeta de destino
                        $Directorio = ftp_pwd($id_ftp);
                        $Directorio2 = $Directorio;

                        $id_ftp = ftp_connect("190.60.248.195", 21); //Obtiene un manejador del Servidor FTP
                        ftp_login($id_ftp, "usuario_ftp", "74091450652!@#1723cc"); //Se loguea al Servidor FTP
                        ftp_pasv($id_ftp, true); //Se coloca el modo pasivo
                        ftp_chdir($id_ftp, "anexos_apa/anexos"); // Nos dirigimos a la carpeta de destino
                        $Directorio = ftp_pwd($id_ftp);
                        $Directorio2 = $Directorio;

                        //Guardar el archivo en mi carpeta donde esta el proyecto    

                        $res = 0;


                        $fileL = storage_path('app') . "/" .  $nombreArchivo;
                        \Storage::disk('local')->put($nombreArchivo, $imagenDigital);
                        $exi = ftp_put($id_ftp, $Directorio . "/" . $nombreArchivo, $fileL, FTP_BINARY);
                        //Cuando se envia el archivo, se elimina el archivo
                        if (file_exists($fileL))
                            unlink($fileL);
                    } catch (\Exception $e) {
                        return response()->json($e);
                    }*/
                }


                if (isset($request->all()["cuerpo"])) {
                    //Insertamos detalle
                    DB::table('ins_inspeccion_detalle')
                        ->where('id_inspeccion', $conse)
                        ->where('id_orden', $request->all()["cabeza"]["orden"])
                        ->delete();

                    $mayorValor = 0;

                    $puntos_por_agrupacion_obras_civiles = array();
                    $agrupaciones_descartadas = array();
                    $puntos_total_ipal_obras_civiles = 0;
                    $puntos_obtenidos_ipal_obras_civiles = 0;

                    $preguntas_formulario = array();



                    for ($i = 0; $i < count($request->all()["cuerpo"]); $i++) {

                        $version = 1;
                        if (isset($request->all()["cuerpo"][$i]["version"]))
                            $version = $request->all()["cuerpo"][$i]["version"];

                        $id_form = $request->all()["cuerpo"][$i]["id_formulario"];

                        if ($id_form == 3)
                            $id_form = 20;

                        if ($id_form == 4)
                            $id_form = 26;

                        // =====================================================================================================================
                        // IPAL OC - SE OBTIENEN LOS PUNTOS OBTENIDOS POR AGRUPACIÓN DE PREGUNTAS
                        // =====================================================================================================================
                        if ($tipo == "33" || $tipo == "34" || $tipo == "35") {
                            //Consulta respuesta
                            $pregunta_formulario = DB::table('gop_formularios_creacion')
                                ->where('tipo_formulario', $id_form)
                                ->where('version', $version)
                                ->where('id_pregunta', $request->all()["cuerpo"][$i]["id_pregunta"])
                                ->select('nombre_corto', 'id_padre')
                                ->get();

                            if ($pregunta_formulario) {
                                $pregunta_formulario[0]->respuesta = $request->all()["cuerpo"][$i]["respuesta"];
                                $preguntas_formulario[] = $pregunta_formulario;

                                $id_padre_pregunta      = $pregunta_formulario[0]->id_padre;
                                $puntos_por_conformidad = $pregunta_formulario[0]->nombre_corto;

                                $puntos_total_ipal_obras_civiles += $puntos_por_conformidad;

                                if ($request->all()["cuerpo"][$i]["respuesta"] == "1") {
                                    if ($puntos_por_conformidad == "3") {
                                        if (!in_array($id_padre_pregunta, $agrupaciones_descartadas)) {
                                            $agrupaciones_descartadas[] = $id_padre_pregunta;
                                            $puntos_por_agrupacion_obras_civiles[$id_padre_pregunta] = 0;
                                        }
                                    }
                                } else {
                                    if (!in_array($id_padre_pregunta, $agrupaciones_descartadas)) {

                                        if (!isset($puntos_por_agrupacion_obras_civiles[$id_padre_pregunta])) {
                                            $puntos_por_agrupacion_obras_civiles[$id_padre_pregunta] = 0;
                                        }

                                        $puntos_por_agrupacion_obras_civiles[$id_padre_pregunta] += $puntos_por_conformidad;
                                    }
                                }
                            }
                        }
                        // =====================================================================================================================
                        // IPAL PRINCIPAL
                        // =====================================================================================================================
                        else {

                            if ($request->all()["cuerpo"][$i]["respuesta"] == "1") {
                                //Consulta respuesta
                                $valor = DB::table('gop_formularios_creacion')
                                    ->where('tipo_formulario', $id_form)
                                    ->where('version', $version)
                                    ->where('id_pregunta', $request->all()["cuerpo"][$i]["id_pregunta"])
                                    ->value('nombre_corto');

                                if ($valor == "" || $valor == NULL)
                                    $valor = 0;
                                else
                                    $valor = intval($valor);

                                if ($valor > $mayorValor)
                                    $mayorValor = $valor;
                            }
                        }

                        $textoextra = "";
                        if (isset($request->all()["cuerpo"][$i]["textoextra"]))
                            $textoextra = $request->all()["cuerpo"][$i]["textoextra"];

                        $acto = "";
                        if (isset($request->all()["cuerpo"][$i]["acto"]))
                            $acto = $request->all()["cuerpo"][$i]["acto"];

                        if (isset($request->all()["cuerpo"][$i]["hallazgo"])) {
                            if ($request->all()["cuerpo"][$i]["hallazgo"] != "") {
                                $desItem = DB::table('gop_formularios_creacion')
                                    ->where('tipo_formulario', $id_form)
                                    ->where('version', $version)
                                    ->where('id_pregunta', $request->all()["cuerpo"][$i]["id_pregunta"])
                                    ->value('descrip_pregunta');
                                //SE COMENTARE EL CODIGO DE ACUERDO A SA SOLICITUD DE CARLOS CAMARGO
                                //DIA 3 MES 7 AÑO 2019

                                // DB::Table('ins_analisis_causas')
                                // ->insert(array(
                                //     array(
                                //         'analisis' => $request->all()["cuerpo"][$i]["hallazgo"],
                                //         'id_item' => $request->all()["cuerpo"][$i]["id_pregunta"],
                                //         'des_item' => $desItem,
                                //         'id_inspeccion' =>$conse,
                                //         'usuario' => $usuario,
                                //         'tipo_ingeso' => 1
                                //         )
                                //     ));
                            }
                        }

                        $orden_detalle = $request->all()["cabeza"]["orden"];

                        if (!$orden_detalle) {
                            $orden_detalle = '';
                        }


                        $data_inspeccion_detalle = array(
                            'id_inspeccion'   => $conse,
                            'id_orden'        => $orden_detalle,
                            'id_pregunta'     => $request->all()["cuerpo"][$i]["id_pregunta"],
                            'respuesta'       => $request->all()["cuerpo"][$i]["respuesta"],
                            'id_formulario'   => $id_form,
                            'version'         => $version,
                            'acto_condicion'  => $acto,
                            'texto_extra'     => (isset($request->all()["cuerpo"][$i]["textoextra"]) ? $request->all()["cuerpo"][$i]["textoextra"] : "") . " " . (isset($request->all()["cuerpo"][$i]["hallazgo"]) ? " - " . $request->all()["cuerpo"][$i]["hallazgo"] : "")
                        );



                        DB::Table('ins_inspeccion_detalle')
                            ->insert(array($data_inspeccion_detalle));



                        if (
                            false &&
                            $tipo == '36' &&
                            $request->all()["cuerpo"][$i]["id_pregunta"] > '38'
                        ) {
                            print "<pre>";
                            print_r($data_inspeccion_detalle);
                            print "</pre>";
                            exit('FOTOX');
                        }
                    }


                    $medalla_obtenida = '';

                    if ($tipo == '33' || $tipo == '34' || $tipo == '35') {
                        // =====================================================================================================================
                        // IPAL OC - SE CALCULA EL TOTAL DE PUNTOS OBTENIDOS
                        // =====================================================================================================================
                        foreach ($puntos_por_agrupacion_obras_civiles as $puntos_por_agrupacion) {
                            $puntos_obtenidos_ipal_obras_civiles += $puntos_por_agrupacion;
                        }

                        // =====================================================================================================================
                        // IPAL OC - SE CALCULA EL PORCENTAJE DE CUMPLIMIENTO OBTENIDO
                        // =====================================================================================================================
                        $porcentaje_cumplimiento_ipal_obras_civiles = 0;
                        if ($puntos_total_ipal_obras_civiles) {
                            $porcentaje_cumplimiento_ipal_obras_civiles =
                                (int) (($puntos_obtenidos_ipal_obras_civiles * 100) / $puntos_total_ipal_obras_civiles);
                        }

                        // =====================================================================================================================
                        // IPAL OC - SE OBTIENE LA MEDALLA OBTENIDO DE ACUERDO AL PORCENTAJE DE CUMPLIMIENTO
                        // =====================================================================================================================
                        if ($porcentaje_cumplimiento_ipal_obras_civiles >= 96) {
                            $medalla_obtenida = 'MEDALLA_VERDE';
                        } else if ($porcentaje_cumplimiento_ipal_obras_civiles >= 86 && $porcentaje_cumplimiento_ipal_obras_civiles < 96) {
                            $medalla_obtenida = 'MEDALLA_AMARILLA';
                        } else if ($porcentaje_cumplimiento_ipal_obras_civiles < 86) {
                            $medalla_obtenida = 'MEDALLA_ROJA';
                        }

                        /*
                          return response()->json(
                            array(
                              'preguntas_formulario' => $preguntas_formulario,
                              'agrupaciones_descartadas' => $agrupaciones_descartadas,
                              'puntos_por_agrupacion_obras_civiles' => $puntos_por_agrupacion_obras_civiles,
                              'puntos_total_ipal_obras_civiles' => $puntos_total_ipal_obras_civiles,
                              'puntos_obtenidos_ipal_obras_civiles' => $puntos_obtenidos_ipal_obras_civiles,
                              'porcentaje_cumplimiento_ipal_obras_civiles' => $porcentaje_cumplimiento_ipal_obras_civiles
                            )
                          );
                          */
                    }

                    if ($request->all()["cabeza"]["resultado"] == "C" || $tipo == "3" || $tipo == "4")
                        $mayorValor  = 0;

                    // ================================================================================================================
                    // PARA LOS FORMATOS DE INSPECCIÓN DE SEGURIDAD SE GUARDA EL PORCENTAJE DE CUMPLIMIENTO
                    // ================================================================================================================
                    if ($tipo == '33' || $tipo == '34' || $tipo == '35') {
                        $resultado_inspeccion_seguridad = 'NC';
                        if ($porcentaje_cumplimiento_ipal_obras_civiles >= 86) {
                            $resultado_inspeccion_seguridad = 'C';
                        }

                        //Update calificación 
                        DB::table('ins_inspeccion')
                            ->where('id_inspeccion', $conse)
                            ->where('lider', $lider)
                            ->update(array(
                                'calificacion' => $porcentaje_cumplimiento_ipal_obras_civiles
                            ));

                        //Update ssl_eventos 
                        DB::table('ssl_eventos')
                            ->where('id_ipal', $conse)
                            ->update(array(
                                'respuesta_auto' => $porcentaje_cumplimiento_ipal_obras_civiles
                            ));
                    }
                    // ================================================================================================================
                    // PARA LOS DEMAS FORMATOS SE GUARDA LA CALIFICACION
                    // ================================================================================================================
                    else {

                        //Update calificación 
                        DB::table('ins_inspeccion')
                            ->where('id_inspeccion', $conse)
                            ->where('lider', $lider)
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

                //  DB::commit();
                if ($tipo == '33' || $tipo == '34' || $tipo == '35') {
                    return response()->json(array(
                        'consecutivo'             => $conse,
                        'medalla_obtenida'        => $medalla_obtenida,
                        'porcentaje_cumplimiento' => $porcentaje_cumplimiento_ipal_obras_civiles
                    ));
                } else {
                    return response()->json($conse);
                }
            } catch (\Illuminate\Database\Illuminate\Database\QueryBuilder $e) {
                response()->json("Error: " . $e);
            } catch (Exception $e) {
                //DB::rollBack();
                return response()->json("0");
            }
        }
    }
    /*****************************************************************************/
    /******** FIN WEB SERVICES APP MÓVIL PLAN DE SUPERVISIÓN - GUARDAR ***********/
    /*****************************************************************************/


    /**
    Consulta órdenes asignadas al usuario logueado
     */
    public function getOrdenesSup(Request $request)
    {

        $tp = \DB::table("ins_inspeccion as ins")
            ->join("rh_personas as p", "p.identificacion", "=", "ins.lider")
            ->join('tp_ordenes_nodos', function ($query) {
                $query->on('tp_ordenes_nodos.orden_id', '=', 'ins.id_orden')
                    ->on('tp_ordenes_nodos.nodo_id', '=', 'ins.visita');
            })
            ->join('tp_nodos', 'tp_nodos.nodo_id', '=', 'tp_ordenes_nodos.nodo_id')
            ->where("supervisor", $request->usuario)
            ->where("estado", "E4")
            ->where("id_orden", "!=", "")
            ->where("tipo_operacion", "p")
            ->orderBy('tp_nodos.nombre')
            ->get([
                "id_orden",
                "id_inspeccion",
                "tp_nodos.nombre as nombreNodo",
                "tp_nodos.nodo_id",
                "tipo_operacion",
                "ins.lider  as cc",
                "ins.prefijo",
                \DB::raw("CAST(fecha_servidor  as DATE) as fecha"),
                \DB::raw("CONCAT(p.nombres,' ',p.apellidos) as lider")
            ]);

        $c = \DB::table("ins_inspeccion as ins")
            ->join("rh_personas as p", "p.identificacion", "=", "ins.lider")
            ->where("supervisor", $request->usuario)
            ->where("estado", "E4")
            ->where("id_orden", "!=", "")
            ->where("tipo_operacion", "c")
            ->get([
                "id_orden",
                "id_inspeccion",
                "ins.visita",
                "ins.prefijo",
                "ins.lider as cc",
                "tipo_operacion",
                \DB::raw("CAST(fecha_servidor  as DATE) as fecha"),
                \DB::raw("CONCAT(p.nombres,' ',p.apellidos) as lider")
            ]);

        return ['tp' => $tp, 'c' => $c];
    }

    /**
    Consulta y retorla la información de una orden específca
    (formatos - fotos - materiales - mano de obra)
     */
    public function getInfoOrdenIns(Request $request)
    {
        //Obtiene el id de la orden
        $idOrden = \DB::table("ins_inspeccion")
            ->where("id_inspeccion", $request->id)
            ->value("id_orden");

        $esTp = 0;
        //Determina si es tp u oc
        try {
            $esTp = \DB::table("oc_ordenes")->where("orden_id", $idOrden)->count();
        } catch (Exception $e) {
            $esTp = 0;
        }


        if ($esTp > 0) {
            $esTp = false;
        } else {
            $esTp = true;
        }

        $materiales = $this->getMateriales($esTp, $idOrden, $request->nodo, $request->programacion);
        $manoObra = $this->getManoObra($esTp, $idOrden, $request->nodo, $request->programacion);
        $fotos = $this->getFotosOp($esTp, $idOrden, $request->nodo, $request->programacion);







        return collect(array(
            "materiales" => $materiales,
            "manoObra" => $manoObra,
            "fotos" => $fotos
        ));
    }

    private function getFotosOp($esTp, $orden, $nodo, $programacion)
    {
        $pref = $this->getPrefijo($esTp, $orden);
        $host = "http://190.60.248.195";

        if ($esTp) {
            $table = \DB::table("tp_ordenes_fotos")->where('nodo_id', $nodo);
        } else {
            $table = \DB::table("oc_ordenes_fotos")->where("id_programacion", $programacion);
        }

        $fotos = $table->where("orden_id", $orden)
            ->get(["orden_id", "adjunto"]);

        $i = 0;
        foreach ($fotos as $f) {
            if ($this->checkRemoteFile($host . "/fotos/" . $pref . "/ordenes/" . $f->adjunto)) {
                $fotos[$i]->enlace = $host . "/fotos/sct/ordenes/" . $f->adjunto;
            } else if ($this->checkRemoteFile($host . "/tp_informacion/" . $pref . "/ordenes/" . $f->adjunto)) {
                $fotos[$i]->enlace = $host . "/tp_informacion/" . $pref . "/ordenes/" . $f->adjunto;
            } else {
                unset($fotos[$i]);
            }
            $i++;
        }

        return $fotos;
    }

    private function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if ($result !== FALSE) {
            return true;
        } else {
            return false;
        }
    }

    /**
    Consulta la mano de obra asociada a una orden
     */

    private function getManoObra($esTp, $orden, $nodo, $programacion)
    {
        if ($esTp) {
            $r = \DB::table("tp_ordenes_ejecucion_mobra as me")->select(
                "b.actividad",
                "b.codigo",
                "b.baremo_id",
                "me.cantidad",
                "mun.factor_incremental",
                "me.baremo_id as baremo",
                "b.precio",
                "inter.nombre as intervencion",
                DB::raw("cast(me.fecha_creacion as date)as fechaBaremo"),
                "inter.tipo_intervencion_id as id_intervencion,,nodo_id,me.orden_id",
                "me.orden_id AS orden"
            )
                ->join("gop_baremos as b", "b.baremo_id", "=", "me.baremo_id")
                ->join("tp_ordenes as o", "o.orden_id", "=", "me.orden_id")
                ->leftJoin("lev_tipo_intervencion as inter", "inter.tipo_intervencion_id", "=", "me.tipo_intervencion_id")
                ->leftjoin("tp_nodos", "tp_nodos.nodo_id", "=", "me.nodo_id")
                ->leftjoin("tp_obras", "tp_obras.obra_id", "=", "tp_nodos.obra_id")
                ->leftJoin("gop_municipios as mun", "mun.id", "=", \DB::raw("tp_nodos.municipio_id and mun.id_proyecto = tp_obras.id_proyecto"))
                ->where("b.periodo", \DB::raw("YEAR(o.fecha_ejecucion)"))
                ->where("b.periodo", \DB::raw("YEAR(o.fecha_ejecucion)"))
                ->where("me.nodo_id", $nodo)
                ->where("me.orden_id", $orden)
                ->get();
        } else {

            $r = \DB::table("oc_ordenes_mobra")
                ->join("oc_ordenes", "oc_ordenes.orden_id", "=", "oc_ordenes_mobra.orden_id")
                ->join("gop_baremos", "gop_baremos.baremo_id", "=", "oc_ordenes_mobra.baremo_id")
                ->where("id_programacion", $programacion)
                ->where("oc_ordenes.orden_id", $orden)
                ->groupBy(
                    "gop_baremos.actividad",
                    "gop_baremos.precio",
                    "gop_baremos.codigo",
                    "gop_baremos.baremo_id",
                    "oc_ordenes_mobra.cantidad"
                )
                ->get([
                    "gop_baremos.actividad",
                    "gop_baremos.precio",
                    "gop_baremos.codigo",
                    "gop_baremos.baremo_id",
                    "oc_ordenes_mobra.cantidad"
                ]);
        }
        return $r;
    }

    /**
    Obtiene los matereriales asociados a una orden
     */

    private function getMateriales($esTp, $orden, $nodo, $programacion)
    {

        $pref = $this->getPrefijo($esTp, $orden);

        //Consulta el material
        if ($esTp) {
            return \DB::table($pref . "_inv_documentos as i")
                ->join($pref . "_inv_detalle_documentos as det", "det.id_documento", "=", "i.id_documento")
                ->join($pref . "_inv_maestro_articulos as m", "m.id_articulo", "=", "det.id_articulo")
                ->where("id_orden", $orden)
                ->where("nodo_id", $nodo)
                ->where("id_tipo_movimiento", "T007")
                ->get(["det.id_documento", "m.codigo_sap", "m.nombre", "det.cantidad", "det.i_rz", "det.r_ch", "det.r_rz"]);
        } else {
            return \DB::table($pref . "_inv_documentos as i")
                ->join($pref . "_inv_detalle_documentos as det", "det.id_documento", "=", "i.id_documento")
                ->join($pref . "_inv_maestro_articulos as m", "m.id_articulo", "=", "det.id_articulo")
                ->where("id_orden", $orden)
                ->where("id_programacion", $programacion)
                ->where("id_tipo_movimiento", "T007")
                ->get(["det.id_documento", "m.codigo_sap", "m.nombre", "det.cantidad", "det.i_rz", "det.r_ch", "det.r_rz"]);
        }
    }

    private function getPrefijo($esTp, $orden)
    {
        //Obtiene el prefijo
        if ($esTp) {
            $pref = \DB::table("tp_ordenes as o")
                ->join("tp_maniobras as m", "m.maniobra_id", "=", "o.maniobra_id")
                ->join("tp_obras as ob", "ob.obra_id", "=", "m.obra_id")
                ->join("gop_proyectos as p", "p.id_proyecto", "=", "ob.id_proyecto")
                ->where("o.orden_id", $orden)
                ->value("p.prefijo_db");
        } else {
            //Obtiene el prefijo
            $pref = \DB::table("oc_ordenes as o")
                ->join("gop_proyectos as p", "p.id_proyecto", "=", "o.id_proyecto")
                ->where("o.orden_id", $orden)
                ->value("p.prefijo_db");
        }
        return $pref;
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


class ControllerWSMovilPlanSupervision extends Controller
{
    //

    function __construct() {           
        $this->valorT = "";

        $this->fechaA = Carbon::now(-5);
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }



    public function consultaPlanSupervision(Request $request)
    {

    	$opc = $request->all()["opc"];

    	if($opc == "1") //Consulta Login usuarios Plan Supervisión
    	{
    		$user = $request->all()["usuario"];
    		$pass = $request->all()["pass"];

    		$userCont = DB::Table('rh_personas')
		    			->where('identificacion',$user)
		    			->where('id_estado','EP03')
		    			->select(DB::raw("(nombres + ' ' + apellidos) as nombre"))
		    			->get();

		    if(count($userCont) == 0)
		    	return response()->json(array(
		    			'id' => -1,
		    			'res' => "Usuario no existe"
		    		));

		    $mes = date("n");
		    $anio = date("Y");

		    $userLider = DB::Table('ins_lider_plan_supervision')
		    			->where('anio',$anio)
		    			->where('mes',$mes)
		    			->where('lider',$user)
		    			->count();

		    if($userLider == 0)
		    {

		    	$userCola = DB::Table('ins_colaborador_plan_supervision')
		    			->where('anio',$anio)
		    			->where('mes',$mes)
		    			->where('colaborador',$user)
		    			->count();

		    	if($userCola == 0)
		    		return response()->json(array(
		    			'id' => -2,
		    			'res' => "No ha conformado plan de supervisión para  mes " . $mes . " - " . $anio
		    		));
		    	else
		    		return response()->json(array(
		    			'id' => 1,
		    			'res' => $userCont[0]->nombre
		    		));
		    	
		    }

			return response()->json(array(
		    			'id' => 1,
		    			'res' => $userCont[0]->nombre
		    		));

    	}	

    	if($opc == "2") //Consulta Maestros tabla
    	{
    		$versionActualIpal = DB::table('gop_formularios_creacion')
                                ->where('tipo_formulario',1)
                                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioIpal = DB::table( 'gop_formularios_creacion')
                        ->select('tipo_formulario as tipo_f','item_num as item','id_pregunta as id','descrip_pregunta as des','obligatorio as obli','tipo_control as tip','nombre_corto','id_padre as padre','version as ver')
                        ->where('tipo_formulario',1)
                        ->where('version',$versionActualIpal)
                        ->orderBy('tipo_formulario')
                        ->orderBy('id_pregunta')
                        ->get();

            $versionActualCalidad = DB::table('gop_formularios_creacion')
                                ->where('tipo_formulario',2)
                                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioCalidad = DB::table( 'gop_formularios_creacion')
                        ->select('tipo_formulario as tipo_f','item_num as item','id_pregunta as id','descrip_pregunta as des','obligatorio as obli','tipo_control as tip','nombre_corto','id_padre as padre','version as ver')
                        ->where('tipo_formulario',2)
                        ->where('version',$versionActualCalidad)
                        ->orderBy('tipo_formulario')
                        ->orderBy('id_pregunta')
                        ->get();

            

            $versionActualObservacion = DB::table('gop_formularios_creacion')
                                ->where('tipo_formulario',20)
                                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioObservacion = DB::table( 'gop_formularios_creacion')
                        ->select('tipo_formulario as tipo_f','item_num as item','id_pregunta as id','descrip_pregunta as des','obligatorio as obli','tipo_control as tip','nombre_corto','id_padre as padre','version as ver')
                        ->where('tipo_formulario',20)
                        ->where('version',$versionActualObservacion)
                        ->orderBy('tipo_formulario')
                        ->orderBy('id_pregunta')
                        ->get();




            //INSPECCIÓN Y ENTREGA DE OBRAS CIVILES
            $versionOpComercial = DB::table('gop_formularios_creacion')
                                ->where('tipo_formulario',47)
                                ->get([DB::raw('MAX(version) as dat')])[0]->dat;

            $formularioOperacionComercial = DB::table( 'gop_formularios_creacion')
                        ->select('tipo_formulario as tipo_f','item_num as item','id_pregunta as id','descrip_pregunta as des','obligatorio as obli','tipo_control as tip','nombre_corto','id_padre as padre','version as ver')
                        ->where('tipo_formulario',47)
                        ->where('version',$versionOpComercial)
                        ->orderBy('tipo_formulario')
                        ->orderBy('id_pregunta')
                        ->get();

            
            $formularioMedioAmbiente = [];


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
	                      ON (gop_proyectos.id_proyecto = conf_proy.id_proyecto AND gop_proyectos.prefijo_db != '')
	                  WHERE sis_usuarios.cuenta = 'CO8778565' GROUP BY gop_proyectos.nombre, gop_proyectos.prefijo_db,gop_proyectos.id_proyecto
	                  ) as tbl GROUP BY tbl.proyecto,tbl.prefijo_db
	                  ORDER BY tbl.proyecto
	                        ";
	    	$proyecto = DB::select($consulta);



            return response()->json(array(
		    			'frmIpal' => $formularioIpal,
		    			'frmCalidad' => $formularioCalidad,
		    			'frmObservacion' => $formularioObservacion,
		    			'frmMedioAmbiente' => $formularioMedioAmbiente,
                        'frmObraCivil' => $formularioObraCivil,
                        'frmOperacionComercial' => $formularioOperacionComercial,
		    			'proyecto' => $proyecto
		    		));
    	}	

        if($opc == "3") //Consulta avance
        {

            $mes = date("n");
            $anio = date("Y");
            $user = $request->all()["user"];
            $userLider = DB::Table('ins_lider_plan_supervision')
                        ->where('anio',$anio)
                        ->where('mes',$mes)
                        ->where('lider',$user)
                        ->get(['comportamiento','ipales','calidad','ambiental','nombre_equipo']);

            $cantLiderIPAL = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',1)
                            ->where('supervisor',$user)
                            ->count();

            $cantLiderCali = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',2)
                            ->where('supervisor',$user)
                            ->count();

            $cantLiderObser = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',3)
                            ->where('supervisor',$user)
                            ->count();

            $cantLiderAmbiente = DB::Table('ins_inspeccion')
                            ->where('anio',$anio)
                            ->where('mes',$mes)
                            ->where('tipo_inspeccion',4)
                            ->where('supervisor',$user)
                            ->count();


            if(count($userLider) == 0)
            {
                $userCola = DB::Table('ins_colaborador_plan_supervision')
                        ->where('anio',$anio)
                        ->where('mes',$mes)
                        ->where('colaborador',$user)
                        ->get(['comportamiento','ipales','calidad','ambiental','nombre_equipo']);

                return response()->json(array(
                                    "resultados" => $userCola,
                                    "mes" => $mes,
                                    "anio" => $anio,
                                    "cantIPAL" => $cantLiderIPAL,
                                    "cantCalidad" => $cantLiderCali,
                                    "cantObservacion" => $cantLiderObser,
                                    "cantAmbiental" => $cantLiderAmbiente));
            }

                return response()->json(array(
                                    "resultados" => $userLider,
                                    "mes" => $mes,
                                    "anio" => $anio,
                                    "cantIPAL" => $cantLiderIPAL,
                                    "cantCalidad" => $cantLiderCali,
                                    "cantObservacion" => $cantLiderObser,
                                    "cantAmbiental" => $cantLiderAmbiente));

        }

        if($opc == "4") //Consulta Ubicación GPS Cuadrillas
        {

            $pre = $request->all()["pre"];
            $cad = "
                SELECT id_lider,(id_movil + ' - ' + nombre) as cuadrilla,TBL4.fecha,ISNULL(TBL4.hora,'') as hora
                ,ISNULL((track.latitud + ' ' + track.longitud),'') as coor
                FROM
                (SELECT id_lider,id_movil,nombre,TBL3.fecha,MAX(gps1.hora)  as hora
                FROM(
                    SELECT id_lider,id_movil,nombre,TBL2.fecha
                    FROM
                        (SELECT id_lider,id_movil,nombre,MAX(gps.fecha) as fecha
                        FROM
                            (select id_lider,id_movil,( nombres + ' ' + apellidos) as nombre
                            from " . $pre . "_gop_cuadrilla INNER JOIN rh_personas ON id_lider = identificacion
                            LEFT JOIN gop_tipo_cuadrilla ON   'T' +id_est_cuadrilla = id_tipo_cuadrilla
                            WHERE rh_personas.id_estado = 'EP03'
                            GROUP BY id_lider,id_movil,apellidos,nombres
                            )
                            AS cuadrilla 
                        LEFT JOIN " . $pre . "_gps_tracker as gps ON gps.usuario_movil = id_lider
                    GROUP BY id_lider,id_movil,nombre) AS TBL2
                ) AS TBL3 
                    LEFT JOIN " . $pre . "_gps_tracker as gps1 ON gps1.usuario_movil = id_lider
                    AND gps1.fecha = TBL3.fecha 
                GROUP BY id_lider,id_movil,nombre,TBL3.fecha
                ) AS tbl4
                LEFT JOIN " . $pre . "_gps_tracker as track ON track.usuario_movil = id_lider
                    AND track.fecha =  TBL4.fecha
                    AND track.hora =  TBL4.hora
                GROUP BY id_lider,id_movil,nombre,TBL4.fecha,TBL4.hora,track.latitud,track.longitud
                ORDER BY nombre
            ";    
            $cuadrillasGPS = DB::select($cad);

            return response()->json(array(
                        'cuarillasGPS' => $cuadrillasGPS
                    ));

        }

        if($opc == "5") //Consulta inspecciones realizadas
        {

            $user = $request->all()["user"];
            $anio = $request->all()["anio"];
            $mes = $request->all()["mes"];
            $tipo_inspeccion = $request->all()["tipo_inspeccion"];

            $inspeccion = DB::table('ins_inspeccion')
                        ->join('gop_proyectos','gop_proyectos.id_proyecto','=','ins_inspeccion.id_tipo_proyecto')
                        ->rightjoin('rh_personas as p2','p2.identificacion','=','ins_inspeccion.lider')
                        ->join('ins_estados as es','es.id_estado','=','ins_inspeccion.estado')
                        ->select('id_inspeccion','gop_proyectos.nombre',
                            'fecha_servidor',DB::raw("p2.nombres + ' ' + p2.apellidos as nombreL"),'supervisor','lider',
                            'movil','tipo_inspeccion','resultado','estado','es.nombre as nombreE'
                            ,'calificacion')
                        ->where('supervisor',$user)
                        ->where('tipo_inspeccion',$tipo_inspeccion)
                        ->where('mes',$mes)
                        ->where('anio',$anio)
                        ->get();

            return response()->json(array(
                        'inspeccion' => $inspeccion
                    ));

        }

        if($opc == "6") //Consulta datos de personal por proyectos
        {

        }

        


    }


    public function savePlanSupervision(Request $request)
    {
        return response()->json("1");
    	$opc = $request->all()["opc"];

    	if($opc == "1") //Save ubicación GPS Personal de supervisión
    	{
    		$user = $request->all()["user"];
    		$coor = $request->all()["coor"];

    		DB::Table('ins_gps_tracker')
    			->insert(
    					array(
							array(
								'prefijo' => 'NA',
								'latitud' => explode(",",$coor)[0],
								'longitud' => explode(",",$coor)[1],
								'fecha' => explode(" ",$this->fechaALong)[0],
								'hora' =>  explode(" ",$this->fechaALong)[1],
								'usuario_movil' => $user
								)
    						));

    		return response()->json("1");
    	}

        if($opc == "2") //Save Inspecciones
        {

            $cabezaInpeccion = $request->all()["cabeza"];
            $detallerInpseccion = $request->all()["cuerpo"];
            $tipo = $request->all()["tip"]; //1 -> IPALES 2 -> Calidad 3 -> Observación del comportamiento 4 -> Ambiental

            $orden = $cabezaInpeccion["orden"];
            $pre = $cabezaInpeccion["pre"];
            $observacion = $cabezaInpeccion["observacion"];
            $charla = $cabezaInpeccion["charla"];
            $usuario = $cabezaInpeccion["user"];
            $mes = $cabezaInpeccion["mes"];
            $anio = $cabezaInpeccion["anio"];

            $lider = $cabezaInpeccion["lider"];

            $id_proy = DB::table('gop_proyectos')   
                        ->where('prefijo_db',$pre)
                        ->value('id_proyecto');

          if($pre === 'sct' || $pre === 'tp') {
              $lider_sct = $request->all()["cabeza"]["lider"];
              $grupoOperativo = null;
              $cad = "";

              $miembros_cuadrilla = null;

              if($pre === 'sct') {
                // ==================================================================================================
                // SE OBTIENE LA CONFORMACIÓN DE LA CUADRILLA BAJO EL PROYECTO PJ00000133 - OC CUADRILLAS
                // ==================================================================================================
                $cad = "SELECT 
                          cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                          cuadrilla.placa                             AS matricula,
                          cuadrilla.cuadrilla_id                      AS id_movil,
                          persona.identificacion                      AS identificacion,
                          (persona.nombres + ' ' + persona.apellidos) AS nombre,
                          rol.nombre                                  AS rol
                        FROM oc_cuadrillas AS cuadrilla

                        INNER JOIN rh_personas AS persona_lider
                            ON persona_lider.id_persona = cuadrilla.id_lider
                          AND persona_lider.identificacion = '{$lider_sct}'

                        INNER JOIN oc_cuadrilla_miembros AS miembro
                            ON miembro.cuadrilla_id = cuadrilla.cuadrilla_id

                        INNER JOIN rh_personas AS persona
                            ON persona.id_persona = miembro.id_persona

                        INNER JOIN oc_cuadrilla_roles AS rol
                            ON miembro.roles_id = rol.roles_id

                        WHERE 1 = 1
                          AND cuadrilla.id_proyecto = 'PJ00000133'
                          -- AND rol.roles_id = 1 
                          -- AND cuadrilla.cuadrilla_id_estado = 1
                          -- AND persona.id_estado = 'EP03'
                          -- AND cuadrilla.cuadrillas_id = 1
                        ORDER BY 
                          cuadrilla.cuadrilla_id ASC,
                          rol.roles_id ASC;";
                $miembros_cuadrilla = DB::select($cad);

                $existen_miembros_cuadrilla = false;
                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                  $existen_miembros_cuadrilla = true;
                }

                // ==================================================================================================
                // SI NO EXISTE CONFORMACIÓN PROYECTO OC CUADRILLAS - SCT SE OBTIENE LA CONFORMACIÓN DE TP CUADRILLAS
                // ==================================================================================================
                if(!$existen_miembros_cuadrilla) {
                  $cad = "SELECT 
                            cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                            cuadrilla.placa                             AS matricula,
                            cuadrilla.cuadrillas_id                     AS id_movil,
                            persona.identificacion                      AS identificacion,
                            (persona.nombres + ' ' + persona.apellidos) AS nombre,
                            rol.nombre                                  AS rol
                          FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN rh_personas AS persona_lider
                              ON persona_lider.id_persona = cuadrilla.id_lider
                              AND persona_lider.identificacion = '{$lider_sct}'

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                              ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                              ON persona.id_persona = miembro.id_persona

                            INNER JOIN tp_cuadrilla_roles AS rol
                              ON miembro.roles_id = rol.roles_id

                          WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000133'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1
                          ORDER BY 
                            cuadrilla.cuadrillas_id ASC,
                            rol.roles_id ASC;";
                  $miembros_cuadrilla = DB::select($cad);
                }
              }
              else if($pre === 'tp') {
                  $cad = "SELECT 
                            cuadrilla.id_tipo_cuadrilla                 AS id_tipo_cuadrilla,
                            cuadrilla.placa                             AS matricula,
                            cuadrilla.cuadrillas_id                     AS id_movil,
                            persona.identificacion                      AS identificacion,
                            (persona.nombres + ' ' + persona.apellidos) AS nombre,
                            rol.nombre                                  AS rol
                          FROM tp_cuadrillas AS cuadrilla

                            INNER JOIN rh_personas AS persona_lider
                              ON persona_lider.id_persona = cuadrilla.id_lider
                              AND persona_lider.identificacion = '{$lider_sct}'

                            INNER JOIN tp_cuadrilla_miembros AS miembro
                              ON miembro.cuadrillas_id = cuadrilla.cuadrillas_id

                            INNER JOIN rh_personas AS persona
                              ON persona.id_persona = miembro.id_persona

                            INNER JOIN tp_cuadrilla_roles AS rol
                              ON miembro.roles_id = rol.roles_id

                          WHERE 1 = 1
                            AND cuadrilla.id_proyecto = 'PJ00000123'
                            -- AND rol.roles_id = 1 
                            -- AND cuadrilla.cuadrilla_id_estado = 1
                            -- AND persona.id_estado = 'EP03'
                            -- AND cuadrilla.cuadrillas_id = 1
                          ORDER BY 
                            cuadrilla.cuadrillas_id ASC,
                            rol.roles_id ASC;";
                $miembros_cuadrilla = DB::select($cad);
              }


                $cuadrillasGPS = array();
                $ids_cuadrillas = array();
                $cantidad_auxiliares = 0;

                foreach ($miembros_cuadrilla as $indice_miembro => $miembro) {
                  $id_movil               = $miembro->id_movil;
                  $rol                    = $miembro->rol;
                  $matricula              = $miembro->matricula;
                  $id_tipo_cuadrilla      = $miembro->id_tipo_cuadrilla;

                  $identificacion_miembro = $miembro->identificacion;
                  $nombre_miembro         = $miembro->nombre;

                  if(!in_array($id_movil, $ids_cuadrillas)) {
                    $ids_cuadrillas[] = $id_movil;
                    $cantidad_auxiliares = 0;

                    $cuadrillasGPS['id_aux1'] = '';
                    $cuadrillasGPS['id_aux2'] = '';
                    $cuadrillasGPS['id_aux3'] = '';
                    $cuadrillasGPS['id_aux4'] = '';
                    $cuadrillasGPS['id_aux5'] = '';
                    $cuadrillasGPS['id_aux6'] = '';
                    $cuadrillasGPS['id_conductor'] = '';
                  }

                  if($rol === 'LIDER') {
                    $cuadrillasGPS['id_movil']          = $id_movil;
                    $cuadrillasGPS['id_lider']          = $identificacion_miembro;
                    $cuadrillasGPS['matricula']         = $matricula;
                    $cuadrillasGPS['id_tipo_cuadrilla'] = $id_tipo_cuadrilla;
                  }
                  else if($rol === 'CONDUCTOR') {
                    $cuadrillasGPS['id_conductor'] = $identificacion_miembro;
                  }
                  else if($rol === 'AUXILIAR') {
                    $cantidad_auxiliares++;
                    $cuadrillasGPS['id_aux'.$cantidad_auxiliares]    = $identificacion_miembro;
                  }
                }

                $grupoOperativo = (object) $cuadrillasGPS;
            }
            // ====================================================================================================================
            // CONFORMACION DE CUADRILLA DIFERENTES A SOC-SOT Y A TP
            // ====================================================================================================================
            else if($tipo != 3)
            {

                $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                            ->where('id_lider',$lider)
                            ->get(['id_aux1','id_aux2','id_aux3','id_conductor','matricula','id_tipo_cuadrilla','id_movil']);   

                if(count($grupoOperativo) == 0) 
                {
                    $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                            ->where('id_aux1',$lider)
                            ->get([DB::raw("'' as id_aux1"),DB::raw("'' as id_aux2"),DB::raw("'' as id_aux3"),DB::raw("'' as id_conductor"),'matricula','id_tipo_cuadrilla','id_movil']);   

                    if(count($grupoOperativo) == 0)
                    {
                        $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                            ->where('id_aux2',$lider)
                            ->get([DB::raw("'' as id_aux1"),DB::raw("'' as id_aux2"),DB::raw("'' as id_aux3"),DB::raw("'' as id_conductor"),'matricula','id_tipo_cuadrilla','id_movil']);   

                        if(count($grupoOperativo) == 0)
                        {
                            $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                ->where('id_aux3',$lider)
                                ->get([DB::raw("'' as id_aux1"),DB::raw("'' as id_aux2"),DB::raw("'' as id_aux3"),DB::raw("'' as id_conductor"),'matricula','id_tipo_cuadrilla','id_movil']);   

                            if(count($grupoOperativo) == 0)
                            {
                                $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                ->where('id_conductor',$lider)
                                ->get([DB::raw("'' as id_aux1"),DB::raw("'' as id_aux2"),DB::raw("'' as id_aux3"),DB::raw("'' as id_conductor"),'matricula','id_tipo_cuadrilla','id_movil']);

                                if(count($grupoOperativo) == 0)
                                {
                                    if($pre == "rds")  
                                    {
                                        $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                            ->where('id_aux4',$lider)
                                            ->get([DB::raw("'' as id_aux1"),DB::raw("'' as id_aux2"),DB::raw("'' as id_aux3"),DB::raw("'' as id_conductor"),'matricula','id_tipo_cuadrilla','id_movil']);

                                        if(count($grupoOperativo) == 0)
                                        {
                                            $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                                ->where('id_aux5',$lider)
                                                ->get([DB::raw("'' as id_aux1"),DB::raw("'' as id_aux2"),DB::raw("'' as id_aux3"),DB::raw("'' as id_conductor"),'matricula','id_tipo_cuadrilla','id_movil']);

                                            if(count($grupoOperativo) == 0)
                                            {
                                                $grupoOperativo = DB::table($pre . '_gop_cuadrilla')
                                                    ->where('id_aux6',$lider)
                                                    ->get([DB::raw("'' as id_aux1"),DB::raw("'' as id_aux2"),DB::raw("'' as id_aux3"),DB::raw("'' as id_conductor"),'matricula','id_tipo_cuadrilla','id_movil']);

                                                if(count($grupoOperativo) > 0)
                                                    $grupoOperativo = $grupoOperativo[0];                    
                                            }
                                            else
                                                $grupoOperativo = $grupoOperativo[0];                    
                                        }
                                        else
                                            $grupoOperativo = $grupoOperativo[0];                

                                    }
                                }
                                else
                                    $grupoOperativo = $grupoOperativo[0];        
                            }
                            else
                                $grupoOperativo = $grupoOperativo[0];    
                        }
                        else
                            $grupoOperativo = $grupoOperativo[0];
                    }
                    else
                        $grupoOperativo = $grupoOperativo[0];
                        
                }
                else
                    $grupoOperativo = $grupoOperativo[0];
            }

            $resultado = $cabezaInpeccion["resultado"];
            $estado = $cabezaInpeccion["estado"];

            //DB::beginTransaction();
            try
            {
                $conse = self::generaConsecutivo("ID_INSPEC");

                DB::Table('ins_inspeccion')  
                    ->insert(array(
                        array(
                            'id_inspeccion' => $conse,
                            'id_orden' => $orden,
                            'prefijo' => $pre,
                            'id_tipo_proyecto' => $id_proy,
                            'supervisor' => $usuario,
                            'fecha_servidor' => $this->fechaALong,
                            'lider' => $lider,
                            'aux1' => $grupoOperativo->id_aux1,
                            'aux2' => $grupoOperativo->id_aux2,
                            'aux3' => $grupoOperativo->id_aux3,
                            'conductor' => $grupoOperativo->id_conductor,
                            'matricula' => $grupoOperativo->matricula,
                            'tipo_cuadrilla' => $grupoOperativo->id_tipo_cuadrilla,
                            'movil' => $grupoOperativo->id_movil,
                            'tipo_inspeccion' => $tipo,
                            'resultado' => $resultado,
                            'estado' =>  (trim($resultado) == "C" ? "E2" : $estado) ,
                            'charla_calificacion' =>  $charla,
                            'observacion' => $observacion,
                            'anio' => $anio,
                            'mes' => $mes
                            )
                        ));
                    $desEvento = "IPAL";
                    if($tipo == "2")
                        $desEvento = "CALIDAD";

                    if($tipo == "3")
                        $desEvento = "OBSERVACION_COMPORTAMIENTO";

                    if($tipo == "4")
                        $desEvento = "MEDIO_AMBIENTE";

                    DB::Table('ssl_eventos')  
                        ->insert(array(
                            array(
                                'id_origen' => $lider,
                                'observaciones' => $resultado . " - " . $observacion,
                                'fecha' => explode(" ",$this->fechaALong)[0],
                                'id_orden' => $orden,
                                'prefijo' => $pre,
                                'tipo_evento' => $desEvento,
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'notificacion' => 1,
                                'id_ipal' => $conse
                                )
                            ));

                // if(isset($request->all()["cabeza"]["firma"]))
                // {
                //     $firma = $request->all()["cabeza"]["firma"];
                //     $data = explode(',', $firma);
                //     $imagenDigital = base64_decode($data[1]);
                
                //     //Nombre Fotografia
                //     $nombreArchivo = "Supervision_" . $usuario . "_" .  substr(md5(uniqid(rand())), 0, 10) . ".png";

                //     $super = $usuario;
                //     $fecha_movil = $this->fechaShort;
                //     $tipoF = 'T04';
                //     $turno = "";

                //     DB::Table('ins_foto')
                //         ->insert(array(
                //             array(
                //                 'id_super' => $usuario,
                //                 'fecha_celular' => $fecha_movil,
                //                 'id_tipo' => $tipoF,
                //                 'id_inspeccion' => $conse,
                //                 'ruta' => $nombreArchivo,
                //                 'orden' => $conse
                //                 )
                //             ));

                //     $id_ftp=ftp_connect("201.217.195.43",21); //Obtiene un manejador del Servidor FTP
                //     ftp_login($id_ftp,"usuario_ftp","74091450652!@#1723cc"); //Se loguea al Servidor FTP
                //     ftp_pasv($id_ftp,true); //Se coloca el modo pasivo
                //     ftp_chdir($id_ftp, "ins"); // Nos dirigimos a la carpeta de destino
                //     $Directorio=ftp_pwd($id_ftp);
                //     $Directorio2=$Directorio;

                //     //Guardar el archivo en mi carpeta donde esta el proyecto    
                    
                //     $res = 0;
                //     try
                //     {
                //         $fileL = storage_path('app') . "/" .  $nombreArchivo;
                //         \Storage::disk('local')->put($nombreArchivo, $imagenDigital);
                //         $exi = ftp_put($id_ftp,$Directorio . "/" . $nombreArchivo,$fileL,FTP_BINARY); 
                //         //Cuando se envia el archivo, se elimina el archivo
                //         if(file_exists($fileL))
                //             unlink($fileL);

                //     }catch(Exception $e)
                //     {
                //         $res = $e;
                //     }
                // }
            
               

                if(isset($request->all()["cuerpo"]))
                {
                        //Insertamos detalle
                        DB::table('ins_inspeccion_detalle')
                            ->where('id_inspeccion',$conse)
                            ->where('id_orden',$request->all()["cabeza"]["orden"])
                            ->delete();

                        $mayorValor = 0;
                        for ($i=0; $i < count($request->all()["cuerpo"]); $i++) { 

                            $version = 1;
                            if(isset($request->all()["cuerpo"][$i]["version"]))
                                $version = $request->all()["cuerpo"][$i]["version"];

                            if($request->all()["cuerpo"][$i]["respuesta"] == "1")
                            {
                                //Consulta respuesta
                                $valor = DB::table('gop_formularios_creacion')
                                            ->where('tipo_formulario',$request->all()["cuerpo"][$i]["id_formulario"])
                                            ->where('version',$version)
                                            ->where('id_pregunta',$request->all()["cuerpo"][$i]["id_pregunta"])
                                            ->value('nombre_corto');

                                if($valor == "" || $valor == NULL)
                                    $valor = 0;
                                else
                                    $valor = intval($valor);

                                if($valor > $mayorValor)
                                    $mayorValor = $valor;    
                            }
                            

                            $id_form = $request->all()["cuerpo"][$i]["id_formulario"];

                            if($id_form == 3)
                                $id_form = 20;


                            $textoextra = "";
                            if(isset($request->all()["cuerpo"][$i]["textoextra"]))
                                $textoextra = $request->all()["cuerpo"][$i]["textoextra"];


                            DB::Table('ins_inspeccion_detalle')  
                                    ->insert(array(
                                        array(
                                            'id_inspeccion' => $conse,
                                            'id_orden' => $request->all()["cabeza"]["orden"],
                                            'id_pregunta' => $request->all()["cuerpo"][$i]["id_pregunta"],
                                            'respuesta' => $request->all()["cuerpo"][$i]["respuesta"],
                                            'id_formulario' => $id_form,
                                            'version' => $version,
                                            'texto_extra' => $textoextra
                                            )
                                        )); 

                            
                        } 

                        if($request->all()["cabeza"]["resultado"] == "C" || $tipo == "3" || $tipo == "4")
                            $mayorValor  = 0;


                        //Update calificación 
                        DB::table('ins_inspeccion')
                            ->where('id_inspeccion',$conse)
                            ->where('lider',$lider)
                            ->update(array(
                                    'calificacion' => ($mayorValor == 0 ? "" : $mayorValor)
                                ));

                        //Update ssl_eventos 
                        DB::table('ssl_eventos')
                            ->where('id_ipal',$conse)
                            ->update(array(
                                    'respuesta_auto' => ($mayorValor == 0 ? "" : $mayorValor)
                                ));
                    
                }
              //  DB::commit();
                return response()->json($conse);
            }
            catch(Exception $e)
            {
                //DB::rollBack();
                return response()->json("0");
            } 
        }
    }


    private function generaConsecutivo($tipo)
    {
        $consen = DB::table('gen_consecutivos')
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
        DB::table('gen_consecutivos')
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


    public function getOrdenesSup(Request $request){
        return \DB::table("ins_inspeccion as ins")
                ->join("rh_personas as p","p.identificacion","=","ins.lider")
                ->where("supervisor",$request->usuario)
                ->where("estado","E4")
                ->where("id_orden","!=","")
                ->get(["id_orden",
                        "id_inspeccion",
                        \DB::raw("CAST(fecha_servidor  as DATE) as fecha"),
                        \DB::raw("CONCAT(p.nombres,' ',p.apellidos) as lider")]);

        
    }
   
    public function getInfoOrdenIns(Request $request){

        //Obtiene el id de la orden
        $idOrden = \DB::table("ins_inspeccion")
                    ->where("id_inspeccion",$request->id)
                    ->value("id_orden");

        //Determina si es tp u oc
        $esTp = \DB::table("tp_ordenes")->where("orden_id",$idOrden)->count();

        

        if($esTp > 0){
            $esTp = true;
        }

        $materiales = $this->getMateriales($esTp,$idOrden);
        $manoObra = $this->getManoObra($esTp,$idOrden);
        $fotos = $this->getFotosOp($esTp,$idOrden);
        

        



        
        return collect(array(
            "materiales" => $materiales,
            "manoObra" => $manoObra,
            "fotos" => $fotos
        ));
        
    }

    private function getFotosOp($esTp,$orden){
        $pref = $this->getPrefijo($esTp,$orden);
        $host = "http://190.60.248.195";

        if($esTp){
            $table =\DB::table("tp_ordenes_fotos");
        }else{
            $table =\DB::table("oc_ordenes_fotos");
        }

        $fotos = $table->where("orden_id",$orden)
                ->get(["orden_id","adjunto"]);

        $i=0;
        foreach($fotos as $f){
            if($this->checkRemoteFile($host."/fotos/".$pref."/ordenes/".$f->adjunto)){
                $fotos[$i]->enlace = $host."/fotos/sct/ordenes/".$f->adjunto;            
            }else if($this->checkRemoteFile($host."/tp_informacion/".$pref."/ordenes/".$f->adjunto)){
                $fotos[$i]->enlace = $host."/tp_informacion/".$pref."/ordenes/".$f->adjunto;
            }else{
                unset($fotos[$i]);
            }
            $i++;
        }

        return $fotos;
        
    }

    private function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if($result !== FALSE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }



    private function getManoObra($esTp,$orden){
        if($esTp){
            $r =\DB::table("tp_ordenes_ejecucion_mobra as me")->select("b.actividad","b.codigo", "b.baremo_id", "me.cantidad","mun.factor_incremental",
                "me.baremo_id as baremo", "b.precio", "inter.nombre as intervencion",DB::raw("cast(me.fecha_creacion as date)as fechaBaremo"),
                "inter.tipo_intervencion_id as id_intervencion,,nodo_id,me.orden_id", "me.orden_id AS orden")
                ->join("gop_baremos as b", "b.baremo_id","=", "me.baremo_id")
                ->join("tp_ordenes as o","o.orden_id","=","me.orden_id")
                ->leftJoin("lev_tipo_intervencion as inter", "inter.tipo_intervencion_id","=","me.tipo_intervencion_id")
                ->leftjoin("tp_nodos","tp_nodos.nodo_id","=","me.nodo_id")
                ->leftjoin("tp_obras","tp_obras.obra_id","=","tp_nodos.obra_id")
                ->leftJoin("gop_municipios as mun", "mun.id","=",\DB::raw("tp_nodos.municipio_id and mun.id_proyecto = tp_obras.id_proyecto"))
                ->where("b.periodo", \DB::raw("YEAR(o.fecha_ejecucion)"))
                ->where("b.periodo", \DB::raw("YEAR(o.fecha_ejecucion)"))
                ->where("me.orden_id",$orden)
                ->get();

        }else{
            
            $r =\DB::table("oc_ordenes_mobra")
                ->join("oc_ordenes","oc_ordenes.orden_id","=","oc_ordenes_mobra.orden_id")
                ->join("gop_baremos","gop_baremos.baremo_id","=","oc_ordenes_mobra.baremo_id")
                
                ->where("oc_ordenes.orden_id",$orden)
                ->groupBy(
                          "gop_baremos.actividad", 
                          "gop_baremos.precio", 
                          "gop_baremos.codigo", 
                          "gop_baremos.baremo_id",
                          "oc_ordenes_mobra.cantidad")
                ->get([
                    "gop_baremos.actividad", 
                  "gop_baremos.precio", 
                  "gop_baremos.codigo", 
                  "gop_baremos.baremo_id",
                  "oc_ordenes_mobra.cantidad"
                ]); 
            



        }
        return $r;





    }

    private function getMateriales($esTp,$orden){
        
        $pref = $this->getPrefijo($esTp,$orden);

        //Consulta el material
        return \DB::table($pref."_inv_documentos as i")
                    ->join($pref."_inv_detalle_documentos as det","det.id_documento","=","i.id_documento")
                    ->join($pref."_inv_maestro_articulos as m","m.id_articulo","=","det.id_articulo")
                    ->where("id_orden",$orden)
                    ->get(["det.id_documento","m.codigo_sap","m.nombre","det.cantidad","det.i_rz","det.r_ch","det.r_rz"]);
    }

    private function getPrefijo($esTp,$orden){
        //Obtiene el prefijo
        if($esTp){
            $pref = \DB::table("tp_ordenes as o")
                        ->join("tp_maniobras as m","m.maniobra_id","=","o.maniobra_id")
                        ->join("tp_obras as ob","ob.obra_id","=","m.obra_id")
                        ->join("gop_proyectos as p","p.id_proyecto","=","ob.id_proyecto")
                        ->where("o.orden_id",$orden)
                        ->value("p.prefijo_db");
        }else{
            //Obtiene el prefijo
            $pref = \DB::table("oc_ordenes as o")
                    ->join("gop_proyectos as p","p.id_proyecto","=","o.id_proyecto")
                    ->where("o.orden_id",$orden)
                    ->value("p.prefijo_db");
        }
        return $pref;
    }


}

*/
