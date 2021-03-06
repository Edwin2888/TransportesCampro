<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Nav



Route::get('/', function () {
    //phpinfo();
    
    $Headers = array(
        "Content-type: application/json",
        'Accept: application/json'
    );
    
    $data = array(
                'id_evento' => 46
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

    dd($resultado);

    //return view("welcome");
    
});


Route::get("info",function(){
    phpinfo();
});

/*GESTIÓN DE LOGS*/
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('excel',function()
{   
    $cad = "EXEC sp_gop_excel_programacion 1,30,4";
    $proyecto = \DB::select("SET NOCOUNT ON;" . $cad);
    \Excel::create('Laravel Excel', function($excel) use($proyecto) {            
                $excel->sheet('Sheetname', function($sheet) use($proyecto){
                    $mes = 4;
                    $primerDia = 1;
                    $ultimoDia = 30;
                    $products = ["D","Tipo","NOMBRE LIDER","Fecha","Dia Tex","Semana","apoyos - horarios","PROCESO","PROYECTO - RADICADO","GOM","WBS","NODOS","CD","DESCARGO CON CORTE","DESCARGO SIN CORTE","CAMPRO","OBSERVACIÓN","SUPERVISOR PLANER","SUPERVISOR EJECUTOR","GRUA","SUPERVISOR SISO","Valor MO 2 VISITA2","CUMPLIMIENTO"];
                    $sheet->fromArray($products);
                    $k = 2;
                    $sema = 1;

                    $array_dias['Sunday'] = "Dom";
                    $array_dias['Monday'] = "Lun";
                    $array_dias['Tuesday'] = "Mar";
                    $array_dias['Wednesday'] = "Mie";
                    $array_dias['Thursday'] = "Jue";
                    $array_dias['Friday'] = "Vie";
                    $array_dias['Saturday'] = "Sáb";

                    for ($i=0; $i < count($proyecto); $i++) {    

                            $fecha =  $proyecto[$i]->fecha;

                            $dia = explode("-",$fecha);
                            $d = $dia[2];
                            $m = $dia[1];
                            $a = $dia[0];
                            $diaS = $array_dias[date('l', strtotime($fecha))];
                            $weekNum=date("W",mktime(0,0,0,$m,$d,$a));

                                $sheet->row($i +2, array(
                                'OE',$proyecto[$i]->tipo, $proyecto[$i]->nombre, $proyecto[$i]->fecha , $diaS, $weekNum,'','',$proyecto[$i]->proyecto,
                                $proyecto[$i]->gom,$proyecto[$i]->wbs,$proyecto[$i]->nodos,$proyecto[$i]->cd,$proyecto[$i]->descargo1,$proyecto[$i]->descargo2,
                                $proyecto[$i]->dc,$proyecto[$i]->observacion,$proyecto[$i]->supervisor1,$proyecto[$i]->supervisor2,
                                $proyecto[$i]->grua,$proyecto[$i]->supervisor3,$proyecto[$i]->valornodo,''
                                ));                        
                    }
                });
        })->export('xls');
});

Route::get('generaIRO/{orden}',function($orden)
{
//    set_time_limit(0);

    ini_set("memory_limit", "999M");
    ini_set("max_execution_time", "999");

    //$orden = '3046723';

    $datos_ssl = DB::table('ssl_gop_formatos')
                ->where('id_orden',$orden)
                ->orderBy('id','desc')
                ->get(['id_origen','fecha_creacion','id','id_orden','hora','prefijo','consecutivo_formulario','placa']);

    if(count($datos_ssl) == 0)
        return "<p>No existe esta orden en SSL_EVENTOS</p>";

    $resultado = DB::table('gop_formulario_respuesta')
                    ->where('incidencia',$datos_ssl[0]->consecutivo_formulario)
                    ->where('tipo_form','17')
                    ->get(['tipo_control','id_pregunta','res','cantidad','fecha','tipo_respuesta_user','id_padre_pregunta','id_persona_conformacion']);
    
    if(count($resultado) == 0)
        return "<p>La información no esta almacenado en el nuevo modelo de datos</p>";


    $lideres = array();
    for ($i = 0 ;  $i < count($resultado) ; $i++) 
    {
        if($resultado[$i]->id_pregunta == "122")
            array_push($lideres, $resultado[$i]->id_persona_conformacion);
    }

    $prefijo = $datos_ssl[0]->prefijo;
    $cantidad = count($lideres);

    $movil = DB::table($prefijo . '_gop_cuadrilla')
                ->where('id_lider',$datos_ssl[0]->id_origen)
                ->value('id_movil');

    $com = DB::table($prefijo . '_gop_cuadrilla')
                ->where('id_lider',$datos_ssl[0]->id_origen)
                ->value('com');

    $tipoV = DB::connection('sqlsrvCxParque')
            ->table('tra_maestro as tbl1')
            ->where('placa',$datos_ssl[0]->placa)
            ->join('tra_tipo_vehiculo as tbl2','tbl1.id_tipo_vehiculo','=','tbl2.id_tipo_vehiculo')
            ->value('tbl2.nombre');
    //dd($lideres);

    $lideresNombres = array();
    for ($i=0; $i < count($lideres); $i++) { 
        array_push($lideresNombres, array(DB::table('rh_personas')
                ->where('identificacion',$lideres[$i])
                ->get([DB::raw("(nombres + ' ' + apellidos) as nombre"),'identificacion'])[0],

                DB::table('ssl_gop_firmas_formatos')
                ->where('id_origen',$lideres[$i])
                ->where('id_formato',$datos_ssl[0]->id)
                ->value('ruta')
                )
        );
    }
    
//    dd($lideresNombres);

    
    $view =  \View::make('pdf.iro',array(
            'user' => $resultado,
            'otros' => $datos_ssl,
            'cant' => $cantidad,
            'movil' => $movil,
            'com' => $com,
            'tipoV' => $tipoV,
            'users' => $lideresNombres,
            'orden' => $orden
        ))->render();

    $pdf = \App::make('dompdf.wrapper');
    $pdf->loadHTML($view)->setPaper('Legal', 'portrait');
    //return $pdf->stream('IRO.pdf');
    return $pdf->download('IRO.pdf');
    
});


Route::get('/descargaLogs/{i}',function($data)
{
	$fileL= storage_path(). "/$data.txt";
    $headers = array(
              'Content-Type: text/plain',
            );
    return Response::download($fileL, 'Logs Carga Excel.txt', $headers);
});

Route::get('downloadFiles/{i}',function($data)
{
    $ruta = "http://181.49.158.91/" . $i;
    $nombre = explode("/",$i)[count(explode("/",$i)) - 1];
    return Response::download($ruta, $nombre);
});

Route::post('iniCampro','ControllerWebServices@iniCam');


Route::resource('red','RedesController');

/*-----------------------RUTAS APLICACIÓN REDES -----------------------*/
/*Rutas ordenes trabajao programado*/
//Proyecto
Route::get('redes/ordenes/home','RedesController@indexOrdenes');
Route::get('guardar_foto','RedesController@savePhotoToServerWrapper');


Route::post('redes/ordenes/consultaestadonodo','RedesController@consultaestadonodo');
Route::post('redes/ordenes/cambiaestado','RedesController@cambiaestado');


Route::post('consultaFiltroOrdenes','RedesController@filterOrdenesTP');
Route::post('generarconsolidadobaremos','RedesController@generarExcelConsolidadoBaremos');
Route::post('generarconsolidadoeje','RedesController@generarExcelConsolidadoEjecucion');

//Ordenes
Route::get('redes/ordenes/orden','RedesController@indexOrden');
Route::post('consultaFiltroOrden','RedesController@filterOrdenTP');
//Documentos
Route::get('redes/ordenes/documentos','RedesController@indexDocu');
Route::post('consultaFiltroDoc','RedesController@filterDocTP');
Route::post('proyecto','RedesController@AddVerproyecto');
Route::post('cargarExcelGOMs','RedesController@uploadExcelGOMS');

//Gannt
Route::get('ganntProyectos','RedesController@indexgantProyectos');
Route::post('filterganntProyectos','RedesController@filtergantProyectos');
//Gant Restricciones
Route::get('ganntRestricciones','RedesController@indexgantRestricciones');
Route::post('filterganntRestriccion','RedesController@filterRestriccion');
//Tabla Scrum
Route::get('scrumRestricciones','RedesController@indexscrumRestricciones');
Route::post('filterScrumRestricciones','RedesController@filterScrumRe');

//Tabla Ordenes Scrum
Route::get('scrumOrdenes','RedesController@indexscrumOrdenes');
Route::post('filterScrumOrdenes','RedesController@filterScrumOrdenes');


//Tabla Normal
Route::get('restricciones','RedesController@indextableRestricciones');
Route::post('filterTableRestricciones','RedesController@filterTableRe');

//Restricciones
Route::get('cerrarresticciones/{proyecto?}/{orden?}/{id?}','RedesController@cerrarRestric');

//Index GOMS
Route::get('transversal/consultar/goms','RedesController@indexConsultaGom');
Route::post('transversal/consultar/gomsfilter','RedesController@indexConsultaGomFilter');


Route::get('redes/ordenes/ver/{proyecto?}','RedesController@verOrden');
Route::post('guardarProyecto','RedesController@saveProject');
Route::post('guardarWBS','RedesController@saveWBSProgramados');
Route::post('guardarNODOS','RedesController@saveNODOSProgramados');
Route::post('cargarExcel','RedesController@uploadExcel');
Route::post('cargarExcel1','RedesController@uploadExcelM');
Route::post('cargarExcelEstructura','RedesController@uploadExcelEstructura');
Route::post('consultaBaremos','RedesController@consultaBare');
Route::post('guardarACTIVIDAD','RedesController@saveACTIVIDADProgramados');
Route::post('guardarMaterial','RedesController@saveMaterialProgramados');

Route::post('downloadConsolidado','RedesController@downloadPreplanillas');
Route::post('downloadFormato','RedesController@downloadFormato');
Route::post('cargaDescargos','RedesController@cargaDescargosOT');
Route::post('transversal/ordenes/importarMasivoSupervisores','RedesController@cargarMasivoSupervisores');

//Ordenes de trabajo Programados
Route::get('redes/ordenes/ordentrabajo/{proyecto?}/{orden?}','RedesController@verOrdenTrabajo');
Route::post('guardarOrdenProgramados','RedesController@saveOrdenesTrabajoProgramado');
Route::post('consultaActiMate','RedesController@consultaNodosActividadesMateriales');
Route::post('guardarAsignacionRecursosMateriales','RedesController@saveRecursosMate');

Route::post('guardarEventoBitacoraOrden','RedesController@guardarEventoBitacoraOrden');
Route::post('eliminarEventoBitacoraOrden','RedesController@eliminarEventoBitacoraOrden');
Route::post('actualizarGOMProyecto','RedesController@actualizarGOMProyecto');


Route::post('redes/ghorasordentrabajo','RedesController@ghorasordentrabajo');
Route::post('redes/consultaordenguar','RedesController@consultaordenguar');


//Planner
Route::get('cam/programador/planner','RedesController@mostrarPlanner');
Route::post('consultaPlanner','RedesController@consultPlanner');

//Balances
Route::post('consultaBalances','RedesController@consultBalance');

//Reportes
Route::get('redes/ordenes/reportes','RedesController@indexReportesOrdenes');
Route::post('consultareportesOrdenes','RedesController@filterReportesOrden');
//Descargar Exporte de Programación
Route::post('transversal/ordenes/exporteProgramacion','RedesController@exportarProgramacion');
Route::post('transversal/ordenes/exporteProgramacionversiones','RedesController@exportarProgramacionversiones');

//Descargar exporte masivo
Route::post('transversal/ordenes/exporteSupervisoresTerreno','RedesController@exportarSupervisores');


//APLICACIÓN MÓVIL
Route::post('consultaWebServiceMovil','RedesController@consultaMovil');

/*  --------------- RUTAS WEB SERVICES ----------------------*/

Route::post('validarUsuario','ControllerWebServices@validaUsuarioExistente');
Route::post('consultaOrdenes','ControllerWebServices@consultaOrdenesTrabajo');
Route::post('consultaBaremoss','ControllerWebServices@consultaBaremosTrabajo');
Route::post('saveBaremos','ControllerWebServices@guardarBaremos');
Route::post('consultaMateriales','ControllerWebServices@consultaMaterialesTrabajo');
Route::post('saveMateriales','ControllerWebServices@guardarMateriales');
Route::post('updateOrdenEnd','ControllerWebServices@finalizarOrden');

//Versionamiento de APK
Route::post('consultaVersionApp','ControllerWebServices@consultaVersion');

Route::post('cargaFotograficoMovil','ControllerWebServices@cargaAplicacionesMovilesImagenes');

//Biometrico
Route::post('biometricUser','ControllerWebServices@consultaHuellasUser');
Route::post('biometricConsultaUser','ControllerWebServices@consultaUserHuella');
Route::post('biometricSaveUser','ControllerWebServices@saveUserHuella');




/*-----------------------FIN RUTAS APLICACIÓN REDES -----------------------*/


/*--------------------- RUTAS APLICACIÓN VALIDADOR ---------------------*/
/*--------------------- Rutas apliacación Validador Web---------------------*/
Route::get('electricaribe/validador/index','ControllerValidador@index');
Route::post('cargarExcelValidador','ControllerValidador@uploadExcelValidador');
Route::post('consultaInformacionValidador','ControllerValidador@consultaInfoVali');
Route::post('consultaFiltroValidador','ControllerValidador@filterValidador');

/*--------------------- Rutas aplicación Validador Móvil---------------------*/
Route::post('consultaSaveInformacionValidadorMovil','ControllerValidador@consultaInfoValiMovil');

/*--------------------- FIN RUTAS APLICACIÓN VALIDADOR ---------------------*/





/*----------------------- RUTAS ACCESOS OTRAS APLICACION------------*/
// RUTA DE DESCARGA DE EXCEL
Route::post('descargaExcelKitsMaterial','ControllerWebServices@downloadExcelKitsMaterial');


/*---------------------------RUTAS TRANSPORTE---------------------------------------------*/
//Librerias
// https://bootsnipp.com/snippets/featured/form-wizard-using-tabs ->Wizard
Route::get('transversal/transporte/home','ControllerTransporte@index');
Route::get('rutaCargaCiudades','ControllerTransporte@cargaCiudades');
Route::post('rutaInsercionTransporte','ControllerTransporte@guardaInformacionTransporte');
Route::post('rutaConsultaTransporte','ControllerTransporte@consultaInformacionTransporte');
Route::post('insertaGaleriaFotografias','ControllerTransporte@insertaFotografiasGaleria');

Route::get('transversal/transporte/filterVehiculos','ControllerTransporte@filterVehiculos');
Route::post('filterVehiculos','ControllerTransporte@filterVehic');
Route::get('selectVehiculo/{placa}','ControllerTransporte@selectVehicFilter');

Route::get('transversal/odometro/{placa?}','ControllerTransporte@odometroVehiculo');
Route::post('insertaPrimerMantenimiento','ControllerTransporte@insertMantenimientoPrimero');

Route::post('transversal/odometro/masivo','ControllerTransporte@uploadMasivoKilometrajes');


Route::post('fichaVehiculo','ControllerTransporte@subir_ficha_vehiculo');

//API Vehiculos
Route::get('transversal/transporte/ApiVehiculos','ControllerTransporte@ApiVehiculosAct');

//----------------------------Documentos Vehículo
Route::get('transversal/documentos/home','ControllerTransporte@cargaDocumentos');
Route::get('transversal/documento/{placa?}','ControllerTransporte@documentoVehiculo');
Route::post('insertaDocumentosVehiculo','ControllerTransporte@insertaDocVehi');
Route::post('downloadArchivo','ControllerTransporte@downloadAr');
Route::get('transversal/reporte/documentovencidos','ControllerTransporte@documentovencidosvehiculos');
Route::post('filterVehiculosDocumentoVencidos','ControllerTransporte@filterDocumentoVencidos');
Route::get('transversal/reporte/odometro','ControllerTransporte@reporteOdometro');
Route::post('transversal/reporte/odometro/filter','ControllerTransporte@filterreporteOdometro');
Route::get('transversal/documento/visor/{ruta}','ControllerTransporte@visor');
Route::get('inspeccionOrdenes/inspeccion/visor/{ruta}','ControllerTransporte@visor2');
Route::get('transversal/reporte/visor/{ruta}','ControllerTransporte@visor2');
Route::get('transversal/transporte/visor/{ruta}','ControllerTransporte@visor2');

//----------------------------Fin documentos

//----------------------------Mantenimientos Vehículo
Route::get('transversal/mantenimiento/{placa?}','ControllerTransporte@mantenimientoVehiculo');

//----------------------------Fin Mantenimientos


//----------------------------Seguimiento incidencias
Route::get('transversal/incidencias/home','ControllerTransporte@verincidencias'); 
Route::get('transversal/incidencias/homeDemo','ControllerTransporte@verincidenciasDemo');   
Route::post('consultaInformacionVehiculoIncidencias','ControllerTransporte@consultaInformacionVehiculoIncidencias');
Route::get('transversal/incidencia/visor/{ruta}','ControllerTransporte@visor');


Route::post('insertaFotosMovil','ControllerTransporte@insertaPhotoMovil');
Route::get('transversal/incidencia/{ins?}','ControllerTransporte@verInci');   
Route::post('insertaAdjuntoIncidencia','ControllerTransporte@insertAdjuntoInci');
Route::post('donwloadPDFOrden','ControllerTransporte@donwloadpdfmantenimiento');
Route::post('inidencia/actuaguarda','ControllerTransporte@actuaguarda');
Route::post('inidencia/cerrarIncidencia','ControllerTransporte@cerrarIncidencia');
Route::post('rutinaDetalle','ControllerTransporte@rutinaDetalle');
//----------------------------Fin seguimiento incidencias

//----------------------------Arbol de decisiones
Route::get('arbolDecisiones','ControllerTransporte@indexArbolDeciciones');   
Route::get('arbolDecisionesDemo','ControllerTransporte@indexArbolDecicionesDemo');   
Route::post('updateArbolDecision','ControllerTransporte@updateArbolDecision');
Route::post('cargaExcelMasivoArbolDecisiones','ControllerTransporte@cargaMasivoArbol');   
//----------------------------Fin Arbol de decisiones

//----------------------------Reportes
Route::get('transversal/reportes/semanal','ControllerTransporte@reporte1');  
Route::post('filterSeguimientoIncidencia','ControllerTransporte@filterSegInci');

// Exporte incidencias
Route::post('transversal/reportes/exportsemanal','ControllerTransporte@exportarIncidencias');  

Route::get('transversal/reportes/reportegeneraldocumentos','ControllerTransporte@indexReporteGeneralDocumentos');
Route::post('transversal/reportes/reportegeneraldocumentos','ControllerTransporte@indexReporteGeneralDocumentos');
Route::post('filtergeneraldocumentos','ControllerTransporte@filtesegdocumentos');

Route::get('transversal/reportes/programamantenimiento','ControllerTransporte@indexProgramaMantenimiento');
Route::post('filterProgramaMantenimiento','ControllerTransporte@filterProgramaMantenimiento');
//Reporte programa de mantenimientos API
Route::get('transversal/reportes/ApiProgramaMantenimientos','ControllerTransporte@ApiProgramaMantenimientos');

Route::get('transversal/reportes/entregaOperacion','ControllerTransporte@indexEntregaOperacion');
Route::post('filterEntregaOperacionRo','ControllerTransporte@filterEntregaOperacion');


Route::get('transversal/reportes/vehiculosSinKilometraje','ControllerTransporte@indexvehiculosinkilometraje');
Route::post('transversal/reportes/filtervehiculosSinKilometraje','ControllerTransporte@filterindexvehiculosinkilometraje');
//Reporte general documentos API
Route::get('transversal/reportes/ApiDocumentos','ControllerTransporte@ApiDocumentos');
//----------------------------Fin Reportes

//----------------------------CRUD Proveedores
Route::get('transversal/transporte/nuevoProveedores','ControllerListProveedores@listar');  
Route::get('transversal/transporte/listaProveedores','ControllerListProveedores@index');
Route::resource('transversal/transporte/guardaProveedores','ControllerListProveedores@guardanuevo');

Route::get('transversal/transporte/editarProveedores/{ins?}','ControllerListProveedores@editar');  
Route::get('transversal/transporte/eliminaProveedores/{ins?}','ControllerListProveedores@eliminar');    
//----------------------------Fin CRUD Proveedores


//----------------------------CRUD Contratantes
  
Route::get('transversal/transporte/listaContratantes','ControllerListContratantes@index');
Route::resource('transversal/transporte/guardaContratantes','ControllerListContratantes@guardanuevo');

Route::get('transversal/transporte/editarContratantes/{ins?}','ControllerListContratantes@editar');  
  
//----------------------------Fin CRUD Contratantes


//--------------------CRUD Solicitud de maniobra

Route::resource('redes/ordenes/nuevamaniobra','ControllerSolicitudManiobra@guardanuevo');
Route::resource('redes/ordenes/listasolman','ControllerSolicitudManiobra@listar');
Route::resource('redes/ordenes/eliminasolman','ControllerSolicitudManiobra@eliminar');
Route::resource('redes/ordenes/excelsolicitudmaniobra','ControllerSolicitudManiobra@generaExcel');




//---------------------------- Costos

//--- Conceptos
Route::resource('transporte/costos/conceptos',"Transporte\ControllerConceptos");
Route::post('transporte/costos/conceptos/filter',"Transporte\ControllerConceptos@filter");
Route::post('transporte/costos/conceptos/delete',"Transporte\ControllerConceptos@delete");
Route::post('transporte/costos/conceptos/saveConcepto',"Transporte\ControllerConceptos@saveConcepto");

//--- Causación
Route::resource('transporte/costos/causacion',"Transporte\ControllerCausacion");
Route::post('transporte/costos/causacion/filter',"Transporte\ControllerCausacion@filter");
Route::post('transporte/costos/causacion/WServices',"Transporte\ControllerCausacion@WServicesAjax");

//--- Arendamiento
Route::get('transporte/costos/arrendamiento/{pre?}/{placa?}',"Transporte\ControllerArrendamiento@index");
Route::post('transporte/costos/arrendamiento/saveWebService',"Transporte\ControllerArrendamiento@saveWS");
Route::post('transporte/costos/arrendamiento/consultarDocumentosSolped',"Transporte\ControllerArrendamiento@consultarDocumentosSolped");
Route::post('transporte/costos/arrendamiento/consultarServicio',"Transporte\ControllerArrendamiento@consultarServicio");
Route::post('transporte/costos/arrendamiento/consultarContrato',"Transporte\ControllerArrendamiento@consultarContrato");
Route::post('transporte/costos/arrendamiento/saveSolped',"Transporte\ControllerArrendamiento@saveSolped");
//-- Consultas Arrendamiento
Route::get('transporte/costos/arrendamientos/{pre?}/{cCosto?}',"Transporte\ControllerArrendamiento@consulta");
Route::post('transporte/costos/arrendamientosfilter',"Transporte\ControllerArrendamiento@filterconsulta");
Route::post('reporte/transporte/costos/arrendamientos',"Transporte\ControllerArrendamiento@exporteArrendamiento");

// Route::post('reporte/transporte/generarExcelError',"Transporte\ControllerArrendamiento@exporteErrores");
Route::post('reporte/transporte/generarExcelError',"ControllerSolped@exporteErrores");

//--Descuentos


Route::post('descuentos/arrendamiento',"ControllerDescuentoArrendamiento@listar");
Route::post('descuentos/guardaedita',"ControllerDescuentoArrendamiento@guardaedita");
Route::post('descuentos/borrar',"ControllerDescuentoArrendamiento@eliminar");
Route::post('descuentos/arrendamientolos',"ControllerDescuentoArrendamiento@listarlog");
Route::post('descuentos/suma',"ControllerDescuentoArrendamiento@suma");
Route::post('descuentos/listadocategoria',"ControllerDescuentoArrendamiento@listadocategoria");






//-- Consultas Arrendamiento Documento
Route::get('transporte/costos/arrendamientosdoc/{doc?}',"Transporte\ControllerArrendamiento@consultadocumentos");
Route::post('transporte/costos/arrendamientosdownload',"Transporte\ControllerArrendamiento@imprimirdocumentos");

//-- Consultas Arrendamiento Documento Reporte
Route::get('transporte/costos/consultaArrendamientos',"Transporte\ControllerArrendamiento@consultadocumentosReporte");
Route::post('transporte/costos/consultaArrendamientosFilter',"Transporte\ControllerArrendamiento@consultadocumentosReporteFilter");

//-- Combustibles --
Route::get('transporte/costos/combustible',"Transporte\ControllerCombustible@index");
Route::post('transporte/costos/cargaCombustible','Transporte\ControllerCombustible@cargarCombustibles');  
Route::post('transporte/costos/filterCombustible','Transporte\ControllerCombustible@filterCombustible');  
Route::post('transporte/costos/exporteCombustible','Transporte\ControllerCombustible@exporteCombustibles');  


//---------------------------- FIN Costos


//Web Services Móviles
Route::post('consultarmoviltransporte','ControllerTransporte@consultaWebServicesMovil');
Route::post('savemoviltransporte','ControllerTransporte@saveWebServicesMovil');
Route::post('fotografiasUploadTransporte','ControllerTransporte@uploadFotografias');

Route::group(['middleware' => 'cors'], function () {
    //WS app Técnico
    Route::post('transporte/ws/appTecnico',"Transporte\ControllerWSTecnico@wsExtrasTransporte");    

});



/*-------------------------FIN RUTAS TRASNPORTE------------------------------------------*/



/*-----------------------RUTAS SUPERVISOR ---------------------*/

Route::get('inspeccionNuevoIpal','ControllerSupervisor@nuevoIpal');
Route::post('cargaNuevoIpal'    ,'ControllerSupervisor@cargaNuevoIpal');
Route::post('cargaLideres'      ,'ControllerSupervisor@cargaLideres');
Route::post('guardaNuevoIpal'  ,'ControllerSupervisor@guardaNuevoIpal');
Route::post('regenerarDetalleIPAL'  ,'ControllerSupervisor@regenerarDetalleIPAL');


/*--------------------------GENERA PDF---*/
//Route::post('generarPdfValores','ControllerSupervisor@NuestrosValoresPdf');
Route::get('generarPdfValores/{id_inspeccion}','ControllerSupervisor@NuestrosValoresPdf');
Route::get('generarPdfinspeccion/{id_inspeccion}','ControllerSupervisor@inspeccionAmbientalPdf');


Route::post('formularios/log','ControllerSupervisor@formulog');

Route::get('inspeccionOrdenes','ControllerSupervisor@index');
Route::get('inspeccionOrdenesReportes','ControllerSupervisor@reportes');
Route::post('consultareportes','ControllerSupervisor@reportesconsulta');
Route::post('consultaFiltroSupervisor','ControllerSupervisor@filterInspeccion');
Route::get('inspeccionOrdenes/inspeccion/{ins}/{opc?}','ControllerSupervisor@indexInspeccion');
Route::get('creacionFormularios','ControllerSupervisor@indexFormularios');
Route::post('filterFormularios','ControllerSupervisor@filerformularioscreacion');
Route::get('inasistencias','ControllerSupervisor@indexInasistencia');
Route::post('filterinasistencias','ControllerSupervisor@filterindexInasistencia');

Route::get('transversal/reporte/cobertura','ControllerSupervisor@cobertura');
Route::post('transversal/reporte/cobertura/filter','ControllerSupervisor@filterCobertura');

/*--------------------------GENERA EXCEL---*/
Route::post('generateExcelIPAL','ControllerSupervisor@generaExcelConsolidado');


/*----------------------- RUTAS WEB SERVICES APLICACIÓN SUPERVISIÓN ALEJANDRA------------*/
Route::post('consultaProyectos','ControllerSupervisor@consultaProy');
Route::post('saveSupervision','ControllerSupervisor@saveWebServicesSupervision');
Route::post('fotografiasUpload','ControllerSupervisor@uploadFotografias');


Route::post('envioNotificacionesSSL','ControllerSupervisor@envioNoti');
Route::post('envioNotificacionesSSLExterno','ControllerWebServices@enviarNotificacion');


/*-----------------------FIN RUTAS SUPERVISOR ---------------------*/


/*-------------------------RUTAS PLAN DE SUPERVISIÓN------------------------*/


///--------------- RUTAS SUPERVICIÓN OBSERVACIön DE CONPORTAMIENTO, CALIDAD--------------//
Route::resource('transversal/supervision/conformacion','Supervision\ControllerComportamiento');
Route::post('transversal/supervision/conformacion/filter','Supervision\ControllerComportamiento@filterEquipos');


///--------------- RUTAS SUPERVICIÓN VER PROGRAMACIÓN--------------//
Route::get('transversal/supervision/plansupervision/{dato?}/{anio?}/{mes?}','Supervision\ControllerPlanSupervision@index');

//RUTAS WS WEB
Route::post('transversal/supervision/wsWebConsulta','Supervision\ControllerWSWeb@consultaWSWebConsulta');
Route::post('transversal/supervision/wsWebSave','Supervision\ControllerWSWeb@consultaWSGuardar');



Route::post('transversal/supervision/wsMovilSavePlanSuper','Supervision\ControllerWSMovilPlanSupervision@savePlanSupervision');


Route::post('transversal/supervision/wsMovilConsultaPlanSuper','Supervision\ControllerWSMovilPlanSupervision@consultaPlanSupervision');



Route::post('transversal/supervision/getOrdenesPlan','Supervision\ControllerWSMovilPlanSupervision@getOrdenesSup');


Route::post('transversal/supervision/getInfoOrdenIns','Supervision\ControllerWSMovilPlanSupervision@getInfoOrdenIns');




/*-------------------------FIN RUTAS PLAN DE SUPERVISIÓN------------------------*/


/*----------------------- MANUALES----------------------------------*/
Route::get('manuales','ControllerManuales@index');
Route::get('manuales/add','ControllerManuales@addManual');
Route::post('consultaFilterManual','ControllerManuales@filterManual');
Route::post('saveManual','ControllerManuales@guardaManual');



/*--------------------------- FIN ----------------------------------*/

/*--------------------------- RUTINAS ------------------------------*/

Route::get('transversal/transporte/rutinas','RutinasController@index'); 
Route::get('transversal/transporte/editRutina','RutinasController@edit');
Route::get('transversal/transporte/detalleRutina','RutinasController@listarDetalle');
Route::resource('transversal/transporte/guardaRutina','RutinasController@guardanuevo');
Route::get('transversal/transporte/createRutina','RutinasController@create');
Route::post('transversal/transporte/createRutina/store','RutinasController@store');

/*--------------------------- FIN ----------------------------------*/


/*----------------AGS---------------------*/
Route::post('transversal/ags/saveWSMovil','Ags\ControllerWSMovil@WSAgsSaveMovil');
    
/*----------------FIN AGS---------------------*/


/*----------- VALIDAR CONEXION ---------*/
//Ruta encargada de validar las conexiones en la configuración de los WS de los dispositivos móviles
Route::post('validaConexion','ControllerWebServices@validarConexion');

/*----------------FIN VALIDAR CONEXION ---------------------*/



/* INSTALACIÓN SQL SERVER - https://github.com/Microsoft/msphpsql */


/*
GENERAR LOGS DE LARAVEL
\Log::debug('Test var fails ' . $e->getMessage());
Log::alert();
Log::critical();
Log::error();
Log::warning();
Log::notice();
Log::info();
Log::debug();

//Arreglar Rewruite: http://www.jarrodoberto.com/articles/2011/11/enabling-mod-rewrite-on-ubuntu

*/


/* CRON JOBS LARAVEL*/
// http://programacion.net/articulo/gestionando_cronjobs_con_laravel_1091
// https://styde.net/crear-tareas-programadas-en-linux-con-cronjobs/
// https://www.garron.me/en/bits/specify-editor-crontab-file.html
// HORA DEL SERVIDOR
// http://www.muylinux.com/2010/02/27/cambiando-tu-timezone-en-linux
// https://www.linuxtotal.com.mx/?cont=info_admon_006


Route::get('alarma','ControllerManuales@addManual');

// http://www.iugo.com.uy/blog/laravel-tips-subcarpetas-para-backend-y-frontend/

//ERROR LARAVEL
// https://laracasts.com/discuss/channels/laravel/class-homecontroller-does-not-exist-when-asking-the-routelist-in-laravel-52




////////////////////////////////////////77

Route::get('redes/gestor/{tipo?}','ControllerGestor@bandeja');
Route::get('redes/leer/{id_proyecto?}/{id_orden?}/{tipo?}','ControllerGestor@verdetalle');


Route::post('redes/gestorlista','ControllerGestor@listar');
//////////////////////////////////////////


//traer servicios 
Route::post('transporte/costos/arrendamiento/servicios',"Transporte\ControllerArrendamiento@servicios");
Route::get('/ExporteChevy','ControllerTransporte@ExportChevy');

//----------------------------CRUD Contratantes
Route::get('transversal/transporte/listaContratantes','ControllerListContratantes@index');
Route::resource('transversal/transporte/guardaContratantes','ControllerListContratantes@guardanuevo');
Route::get('transversal/transporte/editarContratantes/{ins?}','ControllerListContratantes@editar'); 
//----------------------------Fin CRUD Contratantes

Route::get('/chevistar/web','ControllerTransporte@chevistar');


Route::get('/chevystar/indexKm','ControllerKmChevystar@indexKm');
Route::post('chevystar/consultaKm', 'ControllerKmChevystar@consultaKm');

