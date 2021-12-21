<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Redirect;//para redirigir pagina
use DB;//para usar BD
use Session;//Para las sesiones
use Carbon\Carbon;
use Response;



class ControllerTransporte extends Controller
{

    /*************************************************/
    /******** FUNCIONES DEL CONTROLADOR **************/

    //Función contructor
    function __construct() {

        //Session::put('user_login',"U00190"); //Oscar
        //Session::put('user_login',"U01853"); //Aleja
        //Session::put('user_login',"U03783"); //Karen Tinjaca

        Session::put('proy_short',"home");
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    //Guardar el log de cambios
    private function saveLog($id_log,$campo_valor,$descripcion)
    {
        DB::connection('sqlsrvCxParque')
            ->table('tra_log')
            ->insert(array(
                array(
                    'id_log' => $id_log,
                    'fecha' => $this->fechaALong,
                    'id_usuario' => Session::get('user_login'),
                    'campro_valor' => $campo_valor,
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

        return  DB::table('sis_perfiles_opciones')
                    ->where('id_perfil',$id_perfil)
                    ->where('id_opcion',$opc)
                    ->select('id_opcion','nivel_acceso')
                    ->get();
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
    /*****************************************************/
    


    /******************************************/
    /******** FICHA DEL VEHÍCULO **************/

    //Muestra la interfaz principal del vehículo
    public function index()
    {  

        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');
        
        $permisoIncidencia = self::consultaAcceso('OP077');

        if(count($permisoIncidencia) == 0)
            return view('errors.nopermiso');   

        // W -> Total
        // R -> Restringuido
        // N -> No tiene acceso

        if($permisoIncidencia[0]->nivel_acceso == "N")
            return view('errors.nopermiso');    

        $permisoModificarFechas = self::consultaAcceso('OP095');

        if(count($permisoModificarFechas) == 0)
            $permisoModificarFechas = "N";
        else
            $permisoModificarFechas = $permisoModificarFechas[0]->nivel_acceso;
        
        $listadoGeneral =
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->select('placa','modelo','color');
        $ciudades =
            DB::connection('sqlsrvCxParque')
                ->table('tra_ciudades')
                ->orderBy('nombre')
                ->lists('nombre','id_ciudad');/*option , value*/

        $tipoVehiculos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->get(['nombre','id_tipo_vehiculo','nombre_cam']);/*option , value*/

        $marcas =
            DB::connection('sqlsrvCxParque')
                ->table('tra_marcas')
                ->orderBy('nombre')
                ->lists('nombre','id_marca');/*option , value*/

        $tipoCombustibles =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_combustible')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_combustible');/*option , value*/

        $tipoTransmision =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_transmision')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_transmision');/*option , value*/

        $tipoVinculacion =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vinculacion')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vinculo');/*option , value*/

        $propietarios =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_propietarios')
                    ->orderBy('nombre')
                    ->lists('nombre','id_propietario');/*option , value*/

        $estados =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_estados')
                    ->where('id_estado','<>','E00')
                    ->select('nombre','id_estado')
                    ->orderBy('nombre')
                    ->get();/*option , value*/

        $proveedorMonitoreo =
            DB::connection('sqlsrvCxParque')
                ->table('tra_proveedor_monitoreo')
                ->orderBy('nombre')
                ->lists('nombre','id_proveedor_monitoreo');/*option , value*/

        $clienteProyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->get(['nombre','id','ceco','prefijo','ln','op','adicional']);/*option , value*/

        $clases =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_clase')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_clase');/*option , value*/

        $tipoCAM =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tiposvehiculo_cam')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/


        return view ('proyectos.transporte.index',
            array(
                "ciudades"=>$ciudades,
                "tipoVehiculos"=>$tipoVehiculos,
                "marcas"=>$marcas,
                "tipoCombustibles"=>$tipoCombustibles,
                "tipoTransmision"=>$tipoTransmision,
                "tipoVinculacion"=>$tipoVinculacion,
                "propietarios"=>$propietarios,
                "estados" => $estados,
                "proveedorMonitoreo" => $proveedorMonitoreo,
                "clienteProyecto" =>$clienteProyecto,
                "clases"=>$clases,
                "acceso" => $permisoIncidencia[0]->nivel_acceso,
                "permisoFecha" => $permisoModificarFechas,
                "tipo_cam" => $tipoCAM 
            )
        );
    }

    //Ver Vehículo seleccionado
    public function selectVehicFilter($placa)
    {
        Session::put("placa_select",$placa);
        return Redirect::to('transversal/transporte/home');
    }


    //Save fotografías vehículos
    public function insertaFotografiasGaleria(Request $request)
    {
        $placa = $request->all()["placa"];

        $galeria = DB::connection('sqlsrvCxParque')
                ->table('tra_vehiculo_galeria')
                ->where('placa',$placa)
                ->select('placa')
                ->get();

        $fotosA = DB::connection('sqlsrvCxParque')
            ->table('tra_vehiculo_galeria')
            ->where('placa',$placa)
            ->select('ruta_imagen1','ruta_imagen2','ruta_imagen3','ruta_imagen4')
            ->get();

        $nombre = "";
        $nombre1 = "";
        $nombre2 = "";
        $nombre3 = "";

        if(count($fotosA) > 0)
        {
            $nombre = $fotosA[0]->ruta_imagen1;
            $nombre1 = $fotosA[0]->ruta_imagen2;
            $nombre2 = $fotosA[0]->ruta_imagen3;
            $nombre3 = $fotosA[0]->ruta_imagen4;
        }

        if(isset($request->all()['fil_img_1']))
        {
            //obtenemos el campo file definido en el formulario
            $file = $request->file('fil_img_1');
            //Varificamos que carge un .xlsx
            $mime = $file->getMimeType();
            
            //obtenemos el nombre del archivo
            $nombre = "Transporte_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 5) . ".jpg";
            self::envioArchivos(\File::get($file),$nombre,"/anexos_apa/documentosvehiculos");
            $nombre = "/anexos_apa/documentosvehiculos/" . $nombre;
            
            //indicamos que queremos guardar un nuevo archivo en el date(format)isco local
            //\Storage::disk('local')->put($nombre,  \File::get($file));
            //storage_path()    
        }
        
        if(isset($request->all()['fil_img_2']))
        {
            //obtenemos el campo file definido en el formulario
            $file2 = $request->file('fil_img_2');
            //Varificamos que carge un .xlsx
            $mime = $file2->getMimeType();
            //obtenemos el nombre del archivo
            $nombre1 =  "Transporte_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";

            self::envioArchivos(\File::get($file2),$nombre1,"/anexos_apa/documentosvehiculos"); 
            $nombre1 = "/anexos_apa/documentosvehiculos/" . $nombre1;

            //indicamos que queremos guardar un nuevo archivo en el disco local
            //\Storage::disk('local')->put($nombre1,  \File::get($file2));  
        }

        if(isset($request->all()['fil_img_3']))
        {
            //obtenemos el campo file definido en el formulario
            $file3 = $request->file('fil_img_3');
            //Varificamos que carge un .xlsx
            $mime = $file3->getMimeType();
            //obtenemos el nombre del archivo
            $nombre2 =  "Transporte_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";
            //indicamos que queremos guardar un nuevo archivo en el disco local
            
            self::envioArchivos(\File::get($file3),$nombre2,"/anexos_apa/documentosvehiculos");
            $nombre2 = "/anexos_apa/documentosvehiculos/" . $nombre2;

            //\Storage::disk('local')->put($nombre2,  \File::get($file3));
        }

        if(isset($request->all()['fil_img_4']))
        {
            //obtenemos el campo file definido en el formulario
            $file4 = $request->file('fil_img_4');
            //Varificamos que carge un .xlsx
            $mime = $file4->getMimeType();
            //obtenemos el nombre del archivo
            $nombre3 =  "Transporte_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";
            //indicamos que queremos guardar un nuevo archivo en el disco local
            //\Storage::disk('local')->put($nombre3,  \File::get($file4));
            self::envioArchivos(\File::get($file4),$nombre3,"/anexos_apa/documentosvehiculos");
            $nombre3 = "/anexos_apa/documentosvehiculos/" . $nombre3;

        }
        if(count($galeria) == 0) //Primer conductor
        {
            DB::connection('sqlsrvCxParque')
            ->table('tra_vehiculo_galeria')
            ->insert(array(
                array(
                    'placa' => $placa,
                    'ruta_imagen1' => $nombre,
                    'ruta_imagen2' => $nombre1,
                    'ruta_imagen3' => $nombre2,
                    'ruta_imagen4' => $nombre3,
                    'fecha_servidor' => $this->fechaALong
                    )
                ));
        }else
        {
            DB::connection('sqlsrvCxParque')
            ->table('tra_vehiculo_galeria')
            ->where('placa',$placa)
            ->update(
                array(
                    'ruta_imagen1' => $nombre,
                    'ruta_imagen2' => $nombre1,
                    'ruta_imagen3' => $nombre2,
                    'ruta_imagen4' => $nombre3
                ));
        }
        Session::flash('dataExcel1',"Se han cargado correctamente las fotografías del vehículo.");
        Session::flash('imagen_guardada',$placa);
        return Redirect::to('/transversal/transporte/home');
    }   

    /******** FIN FICHA DEL VEHÍCULO **************/
    /**********************************************/



    /****************************************************************/
    /******** REPORTE DE TODOS LOS VEHÍCULOS DE CAMPRO **************/

    //Index para mostrar todos los vehículos de campro
    public function filterVehiculos()
    {  
        $vehiculoData = [];
         if(Session::has('selTipoVehiculo')){
            $vehiculoData = DB::connection('sqlsrvCxParque')
                ->Table('tra_maestro as tbl1')
                ->leftJoin('tra_propietarios as tbl2','tbl1.id_propietario','=','tbl2.id_propietario')
                ->leftJoin('tra_ciudades as tbl3','tbl3.id_ciudad','=','tbl1.id_ciudad')
                ->join('tra_marcas as tbl4','tbl4.id_marca','=','tbl1.id_marca')
                ->join('tra_tipo_vehiculo as tbl5','tbl5.id_tipo_vehiculo','=','tbl1.id_tipo_vehiculo')
                ->join('tra_tipo_clase as tbl6','tbl6.id_tipo_clase','=','tbl1.id_clase')
                ->join('tra_tipo_combustible as tbl7','tbl7.id_tipo_combustible','=','tbl1.id_tipo_combustible')
                ->join('tra_tipo_transmision as tbl8','tbl8.id_tipo_transmision','=','tbl1.id_transmision')
                ->join('tra_tipo_vinculacion as tbl9','tbl9.id_tipo_vinculo','=','tbl1.id_tipo_vinculo')
                ->join('tra_estados as tbl10','tbl10.id_estado','=','tbl1.id_estado')
                ->leftjoin('tra_contratantes as tbl11','tbl11.id','=','tbl1.id_proyecto')
                ->leftJoin('CAMPRO.dbo.rh_personas as usua','usua.identificacion','=','tbl1.usuario_ultima_mod')
                ->leftJoin('tra_tiposvehiculo_cam as tipocam','tipocam.id','=','tbl1.id_tipo_vehiculo_cam')

                ->select('tbl1.placa','tbl1.id_ciudad','tbl1.id_tipo_vehiculo','tbl1.id_marca','tbl1.modelo','tbl1.color','tbl1.pasajeros'
                    ,'tbl1.linea','tbl1.cilindraje','tbl1.id_tipo_combustible','tbl1.id_transmision','tbl1.id_tipo_vinculo','tbl1.id_clase','tbl1.id_estado','tbl1.chasis'
                    ,'tbl1.motor','tbl1.gps','tbl1.propietario_gps','tbl1.id_proveedor_monitoreo','tbl1.serie_gps'
                    ,'tbl1.valor_contrato','tbl1.capacete','tbl1.portaescaleras','tbl1.caja_herramientas','tbl1.portapertiga'
                    ,'tbl1.id_propietario','tbl1.id_proyecto','tbl1.fecha_vinculo as fecha'
                    ,'tbl2.domicilio','tbl2.cedula','tbl2.telefonoFijo','tbl2.telefonoCel','tbl2.correo','tbl2.nombre as nombreP',
                    'tbl3.nombre as nombreC','tbl4.nombre as nombreM','tbl5.nombre as nombreT','tbl6.nombre as nombreClase',
                    'tbl7.nombre as nombreCombus','tbl8.nombre as nombreTran','tbl9.nombre as nombreV',
                    'tbl10.nombre as nombreEstado','tbl11.nombre as nombreCon',DB::raw("(usua.nombres + ' ' + usua.apellidos) as usua_mod"),'fecha_ultima_mod','tbl5.nombre_cam as nombreTCAM',
                    'tipocam.nombre as nombreTipoCAM',
                        DB::raw(
                            "(select top(1)  cambios.fecha
                            FROM tra_log as cambios
                            WHERE cambios.campro_valor = tbl1.placa
                            ORDER BY cambios.fecha DESC) as fecha_servidorCambio
                            "
                        ),
                        DB::raw(
                            "(select top(1)  usua.propietario
                            FROM tra_log as cambios
                            INNER JOIN Campro.dbo.sis_usuarios as usua ON usua.id_usuario = cambios.id_usuario
                            WHERE cambios.campro_valor = tbl1.placa
                            ORDER BY cambios.fecha DESC) as usuarioCambio
                            "
                        ),
                        DB::raw(
                            "(select top(1)  cambios.fecha_servidor
                            FROM tra_cambio_estado as cambios
                            WHERE cambios.placa = tbl1.placa
                            ORDER BY cambios.fecha_servidor DESC) as fecha_servidorEstado
                            "
                        ),
                        DB::raw(
                            "(select top(1)  usua.propietario
                            FROM tra_cambio_estado as cambios
                            INNER JOIN Campro.dbo.sis_usuarios as usua ON usua.id_usuario = cambios.usuario
                            WHERE cambios.placa = tbl1.placa
                            ORDER BY cambios.fecha_servidor DESC) as usuarioEstado
                            "
                        ),
                        DB::raw(
                            "(select top(1)  cambios.fecha_servidor
                            FROM tra_cambio_proyectos as cambios
                            WHERE cambios.placa = tbl1.placa
                            ORDER BY cambios.fecha_servidor DESC) as fecha_servidorProyecto
                            "
                        ),
                        DB::raw(
                            "(select top(1)  usua.propietario
                            FROM tra_cambio_proyectos as cambios
                            INNER JOIN Campro.dbo.sis_usuarios as usua ON usua.id_usuario = cambios.usuario
                            WHERE cambios.placa = tbl1.placa
                            ORDER BY cambios.fecha_servidor DESC) as usuarioProyecto
                            "
                        ))
                ->where('tbl1.placa','LIKE','%' . Session::get('placa_consulta')  . '%');
            
            if(Session::get('selTipoVehiculo') != "" && Session::get('placa_consulta') == "")
                $vehiculoData = $vehiculoData->where('tbl1.id_tipo_vehiculo',Session::get('selTipoVehiculo'));

            if(Session::get('selEstado') != "" && Session::get('placa_consulta') == "")
                $vehiculoData = $vehiculoData->where('tbl1.id_estado',Session::get('selEstado'));

            if(Session::get('selProyectoCliente') != "" && Session::get('placa_consulta') == "")
                $vehiculoData = $vehiculoData->where('tbl1.id_proyecto',Session::get('selProyectoCliente'));

            if(Session::get('selPropietario') != "" && Session::get('placa_consulta') == "")
                $vehiculoData = $vehiculoData->where('tbl1.id_propietario',Session::get('selPropietario'));

            $vehiculoData = $vehiculoData->Orderby('tbl1.placa')->get();
        }


        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        $tipoVehiculos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/

        $estados =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_estados')
                    ->orderBy('nombre')
                    ->where('id_estado','<>','E00')
                    ->lists('nombre','id_estado');/*option , value*/

        $proyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/


        $propietarios =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_propietarios')
                    ->orderBy('nombre')
                    ->lists('nombre','id_propietario');/*option , value*/

        return view ('proyectos.transporte.indexFilterVehiculos',
            array(
                "vehiculoData"=>$vehiculoData,
                "fecha1" => $fechaActual,
                "fecha2" => $nuevafecha,
                "tipo_v" => $tipoVehiculos,
                "estados" => $estados,
                "proyecto" => $proyecto,
                "propietarios" => $propietarios
            )
        );
    }

    //Filtro para mostrar todos los vehículos de campro
    public function filterVehic(Request $request)
    {
        Session::flash('selTipoVehiculo', $request->all()["selTipoVehiculo"]);
        Session::flash('selEstado', $request->all()["selEstado"]);
        Session::flash('selProyectoCliente', $request->all()["selProyectoCliente"]);
        Session::flash('selPropietario', $request->all()["selPropietario"]);  
        Session::flash('placa_consulta', $request->all()["placa_consulta"]);          
        
        return Redirect::to('transversal/transporte/filterVehiculos');
    }

    /******** FIN REPORTE DE TODOS LOS VEHÍCULOS DE CAMPRO **************/
    /********************************************************************/

    


    /*******************************************/
    /********* GESTIÓN DE DOCUMENTOS **********/

    //Muestra la interfaz de maestro de documentos
    public function cargaDocumentos()//Index
    {
        $documentos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro_documentos')
                ->orderBy('nombre_documento')
                ->lists('nombre_documento','id_documento');/*option , value*/

        $clases =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_clase')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_clase');/*option , value*/

        return view ('proyectos.transporte.indexDocumentos',
            array(
                "documentos"=>$documentos,
                "clases"=>$clases
            )
        );
    }

    //Consulta los documentos de un vehículo en específico
    public function documentoVehiculo($placa = "")
    {
        if($placa == "")
            return Redirect::to('/transversal/transporte/home');

        $permisoIncidencia = self::consultaAcceso('OP077');

        if(count($permisoIncidencia) == 0)
            return view('errors.nopermiso');   

        // W -> Total
        // R -> Restringuido
        // N -> No tiene acceso

        if($permisoIncidencia[0]->nivel_acceso == "N")
            return view('errors.nopermiso');    


        $documentosV =
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro_documentos as tbl1')
                ->join('tra_maestro_clase_documentos as tbl3','tbl1.id_documento','=','tbl3.id_documento')
                ->join('tra_maestro as tbl4','tbl4.id_clase','=','tbl3.id_clase')
                ->join('tra_documento_vehiculo as tbl2','tbl1.id_documento','=','tbl2.id_documento')
                ->where('tbl4.placa',$placa)
                ->where('tbl2.placa',$placa)
                ->where('tbl2.actual','S')
                ->select('tbl1.id_documento','referencia','entidad','vencimiento','direccion_archivo')
                ->orderBy('tbl1.nombre_documento')
                ->get();

        $claseVeh = DB::connection('sqlsrvCxParque')
                ->table('tra_maestro as tbl1')
                ->where('tbl1.placa',$placa)
                ->value('id_clase');

        //dd($documentosV);
        $documentosClase =
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro_documentos as tbl1')
                ->join('tra_maestro_clase_documentos as tbl3','tbl1.id_documento','=','tbl3.id_documento')
                ->where('tbl3.id_clase',$claseVeh)
                ->select('tbl1.id_documento','nombre_documento')
                ->get();

        $clase =
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro as tbl4')
                ->join('tra_maestro_clase_documentos as tbl3','tbl3.id_clase','=','tbl4.id_clase')
                ->join('tra_tipo_clase as tbl1','tbl1.id_tipo_clase','=','tbl3.id_clase')
                ->where('tbl4.placa',$placa)
                ->select('tbl1.nombre')
                ->get()[0]->nombre;


        $vehiculoData = DB::connection('sqlsrvCxParque')
                ->Table('tra_maestro as tbl1')
                ->where('tbl1.placa',$placa)
                ->leftJoin('tra_propietarios as tbl2','tbl1.id_propietario','=','tbl2.id_propietario')
                ->join('tra_ciudades as tbl3','tbl3.id_ciudad','=','tbl1.id_ciudad')
                ->join('tra_marcas as tbl4','tbl4.id_marca','=','tbl1.id_marca')
                ->join('tra_tipo_vehiculo as tbl5','tbl5.id_tipo_vehiculo','=','tbl1.id_tipo_vehiculo')
                ->join('tra_tipo_clase as tbl6','tbl6.id_tipo_clase','=','tbl1.id_clase')
                ->join('tra_tipo_combustible as tbl7','tbl7.id_tipo_combustible','=','tbl1.id_tipo_combustible')
                ->join('tra_tipo_transmision as tbl8','tbl8.id_tipo_transmision','=','tbl1.id_transmision')
                ->join('tra_tipo_vinculacion as tbl9','tbl9.id_tipo_vinculo','=','tbl1.id_tipo_vinculo')
                ->join('tra_estados as tbl10','tbl10.id_estado','=','tbl1.id_estado')
                ->select('tbl1.placa','tbl1.id_ciudad','tbl1.id_tipo_vehiculo','tbl1.id_marca','tbl1.modelo','tbl1.color','tbl1.pasajeros'
                    ,'tbl1.linea','tbl1.cilindraje','tbl1.id_tipo_combustible','tbl1.id_transmision','tbl1.id_tipo_vinculo'
                    ,'tbl1.id_clase','tbl1.id_estado','tbl1.chasis'
                    ,'tbl1.motor','tbl1.gps','tbl1.propietario_gps','tbl1.id_proveedor_monitoreo','tbl1.serie_gps'
                    ,'tbl1.valor_contrato','tbl1.capacete','tbl1.portaescaleras','tbl1.caja_herramientas','tbl1.portapertiga'
                    ,'tbl1.id_propietario','tbl1.id_proyecto','tbl1.fecha_vinculo as fecha'
                    ,'tbl2.domicilio','tbl2.cedula','tbl2.telefonoFijo','tbl2.telefonoCel','tbl2.correo','tbl2.nombre as nombreP',
                    'tbl3.nombre as nombreC','tbl4.nombre as nombreM','tbl5.nombre as nombreT','tbl6.nombre as nombreClase',
                    'tbl7.nombre as nombreCombus','tbl8.nombre as nombreTran','tbl9.nombre as nombreV',
                    'tbl10.nombre as nombreEstado')
                ->get()[0];

        return view('proyectos.transporte.documentosVehiculos',
            array(
                'documentos' => $documentosV,
                'documentosClase' => $documentosClase,
                'placa' => $placa,
                'clase' => $clase,
                'datos' => $vehiculoData,
                "acceso" => $permisoIncidencia[0]->nivel_acceso
                ));
    }

    /** Insertar Documentos Vehículo**/
    public function insertaDocVehi(Request $request)
    {
        $placa = $request->all()["placa"];
        $id = $request->all()["id_doc"];
        $opc = $request->all()["opc_doc"];
        $fec = null;

        if(isset($request->all()["txtFechaVen"]))
        {
            if($request->all()["txtFechaVen"] != "")
                $fec = explode("/",$request->all()["txtFechaVen"])[2] . "-" . explode("/",$request->all()["txtFechaVen"])[1] . "-" . explode("/",$request->all()["txtFechaVen"])[0];    
        }
        $nombE = $request->all()["txtNombreEntidad"];
        $ref = $request->all()["txtReferenciaDoc"]; 

        $ruta = "";
        $nombre = "";
        if(isset($request->all()["file_archiv_impor"]))
        {

            set_time_limit(0);
            //obtenemos el campo file definido en el formulario
            $file = $request->file('file_archiv_impor');
            //Varificamos que carge un .xlsx
            $mime = $file->getMimeType();
            //obtenemos el nombre del archivo
            $nombre = $file->getClientOriginalName();

            $findme   = 'png';
            $pos = strpos($mime, $findme);

            if ($pos !== false)
                $ruta = "Transporte_Documentos_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 8) . ".png";


            $findme   = 'jpge';
            $pos = strpos($mime, $findme);
            
            if ($pos !== false)
                $ruta = "Transporte_Documentos_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 8) . ".jpge";

            $findme   = 'jpeg';
            $pos = strpos($mime, $findme);
            
            if ($pos !== false)
                $ruta = "Transporte_Documentos_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 8) . ".jpeg";


            $findme   = 'jpg';
            $pos = strpos($mime, $findme);
            
            if ($pos !== false)
                $ruta = "Transporte_Documentos_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 8) . ".jpg";


            $findme   = 'pdf';
            $pos = strpos($mime, $findme);

            if ($pos !== false)
                $ruta = "Transporte_Documentos_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 8) . ".pdf";

            $findme   = 'xmls';
            $pos = strpos($mime, $findme);

            if ($pos !== false)
                $ruta = "Transporte_Documentos_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 8) . ".xmls";

            $findme   = 'xml';
            $pos = strpos($mime, $findme);

            if ($pos !== false)
                $ruta = "Transporte_Documentos_" . $this->fechaShort .   "_" . substr(md5(uniqid(rand())), 0, 8) . ".xml";

            $nombre = $ruta;
            self::envioArchivos(\File::get($file),$nombre,"/anexos_apa/documentosvehiculos");
            $nombre = "/anexos_apa/documentosvehiculos/" . $nombre;

            $ruta = $nombre;
        }
        
        $nobreDOc = DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro_documentos')
                        ->where('id_documento',$id)
                        ->value('nombre_documento');

        if($opc == "1") //Save Nuevo Documento
        {        
            $logDocu = DB::connection('sqlsrvCxParque')
                ->table('tra_documento_vehiculo')
                ->where('placa',$placa)
                ->where('id_documento',$id)
                ->select('id_documento','placa','referencia', 'entidad', 'vencimiento', 'direccion_archivo')
                ->get();

            if(count($logDocu) == 0)//Inserta
            {
                DB::connection('sqlsrvCxParque')
                ->table('tra_documento_vehiculo')
                ->insert(array(
                    array(
                        'id_documento' => $id,
                        'placa' => $placa,
                        'referencia' => $ref,
                        'entidad' => $nombE,
                        'vencimiento' => $fec,
                        'direccion_archivo' => $ruta,
                        'fecha_servidor' => $this->fechaALong,
                        'actual' => 'S'
                        )
                    ));    

                self::saveLog('OPERA25',$placa,'Ingreso primera vez información documento:' . $nobreDOc . " Referencia: " .$ref);

            }else //Update Datos
            {  /*
                if($nombre == "")
                    DB::connection('sqlsrvCxParque')
                    ->table('tra_documento_vehiculo')
                    ->where('placa',$placa)
                    ->where('id_documento',$id)
                    ->update(array(
                        'referencia' => $ref,
                        'entidad' => $nombE,
                        'vencimiento' => $fec
                        ));
                else
                    DB::connection('sqlsrvCxParque')
                    ->table('tra_documento_vehiculo')
                    ->where('placa',$placa)
                    ->where('id_documento',$id)
                    ->update(array(
                        'referencia' => $ref,
                        'entidad' => $nombE,
                        'vencimiento' => $fec,
                        'direccion_archivo' => $ruta,
                        ));
               */
                
                 DB::connection('sqlsrvCxParque')
                    ->table('tra_documento_vehiculo')
                    ->where('placa',$placa)
                    ->where('id_documento',$id)
                    ->update(array('actual' => 'N' ));
                
                  DB::connection('sqlsrvCxParque')
                ->table('tra_documento_vehiculo')
                ->insert(array(
                    array(
                        'id_documento' => $id,
                        'placa' => $placa,
                        'referencia' => $ref,
                        'entidad' => $nombE,
                        'vencimiento' => $fec,
                        'direccion_archivo' => $ruta,
                        'fecha_servidor' => $this->fechaALong,
                        'actual' => 'S'
                        )
                    )); 
                
                self::saveLog('OPERA25',$placa,'Actualiza información documento:' . $nobreDOc . " Referencia: " .$ref);

            }
            
        }
        else //Update
        {

            self::saveLog('OPERA25',$placa,'Actualiza información documento:' . $nobreDOc . " Referencia: " .$ref);

            DB::connection('sqlsrvCxParque')
                    ->table('tra_documento_vehiculo')
                    ->where('placa',$placa)
                    ->where('id_documento',$id)
                    ->update(array(
                        'actual' => 'N',
                        ));

            DB::connection('sqlsrvCxParque')
                ->table('tra_documento_vehiculo')
                ->insert(array(
                    array(
                        'id_documento' => $id,
                        'placa' => $placa,
                        'referencia' => $ref,
                        'entidad' => $nombE,
                        'vencimiento' => $fec,
                        'direccion_archivo' => $ruta,
                        'fecha_servidor' => $this->fechaALong,
                        'actual' => 'S'
                        )
                    )); 
        }
        Session::flash('dataExcel1',"Se ha cargado correctamente la información del documento del vehículo.");
        return Redirect::to("/transversal/documento/$placa");
    }

    //Descarga un documentos del vehiculo seleccionado
    public function downloadAr(Request $request)
    {
        //Archivo PDF
        //PDF file is stored under project/public/download/info.pdf
        $ruta = storage_path('app') . DIRECTORY_SEPARATOR . $request->all()["nombre"];

        $findme   = 'pdf';
        $pos = strpos($request->all()["nombre"], $findme);

        if ($pos !== false){
            $headers = array(
                  'Content-Type: application/pdf'
                );            
        }

        $findme   = 'jpge';
        $pos = strpos($request->all()["nombre"], $findme);

        if ($pos !== false){
            $headers = array(
                  'Content-Type: image/jpge'
                );            
        }

        $findme   = 'jpg';
        $pos = strpos($request->all()["nombre"], $findme);

        if ($pos !== false){
            $headers = array(
                  'Content-Type: image/jpg'
                );            
        }


        $findme   = 'png';
        $pos = strpos($request->all()["nombre"], $findme);

        if ($pos !== false){
            $headers = array(
                  'Content-Type: image/png'
                );            
        }


        $findme   = 'png';
        $pos = strpos($request->all()["nombre"], $findme);

        if ($pos !== false){
            $headers = array(
                  'Content-Type: image/png'
                );            
        }


        return Response::download($ruta, $request->all()["nombre"], $headers);
    }

    //Consulta los documentos vencidos de los vehículos
    public function documentovencidosvehiculos()
    {
        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        $tipoVehiculos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/

        $estados =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_estados')
                    ->orderBy('nombre')
                    ->lists('nombre','id_estado');/*option , value*/

        $proyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/

        $propietarios =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_propietarios')
                    ->orderBy('nombre')
                    ->lists('nombre','id_propietario');/*option , value*/

        $tipoDoc =
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro_documentos')
                ->orderBy('nombre_documento')
                ->lists('nombre_documento','id_documento');/*option , value*/

        $documentosVencidos = [];
        if(Session::has('fecha_inicio_f'))
        {
            $fecha1 = explode("/",Session::get('fecha_inicio_f'))[2] . "-" . explode("/",Session::get('fecha_inicio_f'))[1] . "-" . explode("/",Session::get('fecha_inicio_f'))[0];
            $fecha2 = explode("/",Session::get('fecha_corte_f'))[2] . "-" . explode("/",Session::get('fecha_corte_f'))[1] . "-" . explode("/",Session::get('fecha_corte_f'))[0];

            $documentosVencidos = DB::connection('sqlsrvCxParque')
                            ->table('tra_documento_vehiculo as tbl1')
                            ->join('tra_maestro_documentos as tbl2','tbl1.id_documento','=','tbl2.id_documento')
                            ->join('tra_maestro as tbl3','tbl3.placa','=','tbl1.placa')
                            ->join('tra_estados as tbl4','tbl4.id_estado','=','tbl3.id_estado')
                            ->join('tra_tipo_clase as tbl5','tbl5.id_tipo_clase','=','tbl3.id_clase')
                            ->join('tra_tipo_vehiculo as tbl6','tbl6.id_tipo_vehiculo','=','tbl3.id_tipo_vehiculo')
                            ->join('tra_contratantes as tbl7','tbl7.id','=','tbl3.id_proyecto')
                            ->whereBetween('tbl1.vencimiento',[$fecha1 . " 00:00:00",$fecha2 . " 23:59:59"])
                            ->where('tbl1.actual','S')
                            ->where('tbl2.vence','S')
                            ->select('tbl1.id_documento','tbl1.placa','tbl1.referencia','tbl1.entidad',
                                'tbl1.vencimiento','tbl1.direccion_archivo','tbl2.nombre_documento',
                                'tbl3.id_estado','tbl4.nombre as nombreEstado','tbl5.nombre as nombreClase',
                                'tbl6.nombre as nombreT','modelo','cilindraje','tbl7.nombre as pry','tbl6.nombre_cam as nombreTCAM');

            if(Session::get('selTipoVehiculo_f') != "")
                $documentosVencidos = $documentosVencidos->where('tbl6.id_tipo_vehiculo',Session::get('selTipoVehiculo_f'));
            
            if(Session::get('selEstado_f') != "")
                $documentosVencidos = $documentosVencidos->where('tbl4.id_estado',Session::get('selEstado_f'));

            if(Session::get('selProyectoCliente_f') != "")
                $documentosVencidos = $documentosVencidos->where('tbl3.id_proyecto',Session::get('selProyectoCliente_f'));

            if(Session::get('seltipodoc_f') != "")
                $documentosVencidos = $documentosVencidos->where('tbl2.id_documento',Session::get('seltipodoc_f'));

            

            $documentosVencidos = $documentosVencidos->get();
        }

        return view('proyectos.transporte.indexDocumentosVencidos',array(
            'fecha1' => $fechaActual,
            'fecha2' => $nuevafecha,
            'tipo_v' => $tipoVehiculos,
            'estados' => $estados,
            'proyecto' => $proyecto,
            'propietarios' => $propietarios,
            'vehiculoData' => $documentosVencidos,
            'tipoDoc' => $tipoDoc
            ));
    }

    //Filtro para la consulta los documentos vencidos de los vehículos
    public function filterDocumentoVencidos(Request $request)
    {
        Session::put('fecha_inicio_f',$request->all()["fecha_inicio"]);
        Session::put('fecha_corte_f',$request->all()["fecha_corte"]);
        Session::put('selTipoVehiculo_f',$request->all()["selTipoVehiculo"]);
        Session::put('selEstado_f',$request->all()["selEstado"]);
        Session::put('selProyectoCliente_f',$request->all()["selProyectoCliente"]);
        Session::put('seltipodoc_f',$request->all()["seltipodoc"]);
        return Redirect::to('transversal/reporte/documentovencidos');
    }

    /*********FIN GESTIÓN DE DOCUMENTOS **********/
    /*******************************************/

    

    /*******************************************/
    /********* GESTIÓN DE ODOMETRO **********/

    //Consulta los odometro sde un vehículo en particular
    public function odometroVehiculo($odometro = "")
    {
        //dd("HI");
        if($odometro == "")
            return Redirect::to('/transversal/transporte/home');

        $permisoIncidencia = self::consultaAcceso('OP077');
        if(count($permisoIncidencia) == 0)
            return view('errors.nopermiso');   

        // W -> Total
        // R -> Restringuido
        // N -> No tiene acceso

        if($permisoIncidencia[0]->nivel_acceso == "N")
            return view('errors.nopermiso'); 


        $odometroA =
            DB::connection('sqlsrvCxParque')
                ->table('tra_vehiculo_odometro')
                ->where('placa',$odometro)
                ->select('fecha_servidor','fecha','kilometraje','id')
                ->orderBy('fecha_servidor','desc')
                ->get();

        $vehiculoData = DB::connection('sqlsrvCxParque')
                ->Table('tra_maestro as tbl1')
                ->where('tbl1.placa',$odometro)
                ->leftJoin('tra_propietarios as tbl2','tbl1.id_propietario','=','tbl2.id_propietario')
                ->join('tra_ciudades as tbl3','tbl3.id_ciudad','=','tbl1.id_ciudad')
                ->join('tra_marcas as tbl4','tbl4.id_marca','=','tbl1.id_marca')
                ->join('tra_tipo_vehiculo as tbl5','tbl5.id_tipo_vehiculo','=','tbl1.id_tipo_vehiculo')
                ->join('tra_tipo_clase as tbl6','tbl6.id_tipo_clase','=','tbl1.id_clase')
                ->join('tra_tipo_combustible as tbl7','tbl7.id_tipo_combustible','=','tbl1.id_tipo_combustible')
                ->join('tra_tipo_transmision as tbl8','tbl8.id_tipo_transmision','=','tbl1.id_transmision')
                ->join('tra_tipo_vinculacion as tbl9','tbl9.id_tipo_vinculo','=','tbl1.id_tipo_vinculo')
                ->join('tra_estados as tbl10','tbl10.id_estado','=','tbl1.id_estado')
                ->select('tbl1.placa','tbl1.id_ciudad','tbl1.id_tipo_vehiculo','tbl1.id_marca','tbl1.modelo','tbl1.color','tbl1.pasajeros'
                    ,'tbl1.linea','tbl1.cilindraje','tbl1.id_tipo_combustible','tbl1.id_transmision','tbl1.id_tipo_vinculo'
                    ,'tbl1.id_clase','tbl1.id_estado','tbl1.chasis'
                    ,'tbl1.motor','tbl1.gps','tbl1.propietario_gps','tbl1.id_proveedor_monitoreo','tbl1.serie_gps'
                    ,'tbl1.valor_contrato','tbl1.capacete','tbl1.portaescaleras','tbl1.caja_herramientas','tbl1.portapertiga'
                    ,'tbl1.id_propietario','tbl1.id_proyecto','tbl1.fecha_vinculo as fecha'
                    ,'tbl2.domicilio','tbl2.cedula','tbl2.telefonoFijo','tbl2.telefonoCel','tbl2.correo','tbl2.nombre as nombreP',
                    'tbl3.nombre as nombreC','tbl4.nombre as nombreM','tbl5.nombre as nombreT','tbl6.nombre as nombreClase',
                    'tbl7.nombre as nombreCombus','tbl8.nombre as nombreTran','tbl9.nombre as nombreV',
                    'tbl10.nombre as nombreEstado')
                ->get()[0];

        

        return view('proyectos.transporte.odometroVehiculo',
            array(
                    'datos' => $vehiculoData,
                    'acceso' => $permisoIncidencia[0]->nivel_acceso,
                    'odometro' => $odometroA,
                    'placa' => $odometro
                ));
    }  

    //Carga los kilometrajes de manera masiva mediante un excel
    public function uploadMasivoKilometrajes(Request $request)
    {
        //obtenemos el campo file definido en el formulario
       $file = $request->file('archivo_excel_descargos');

       if($file == null)
        {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"No ha seleccionado níngun archivo");
            return Redirect::to('/transversal/transporte/home');
        }

       //Varificamos que carge un .xlsx
       $mime = $file->getMimeType();
       if($mime != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
       {
            Session::flash('dataExcel1',".");
            Session::flash('dataExcel2',"Tipo de archivo invalido, tiene que carga un archivo .xlsx");
            return Redirect::to('/transversal/transporte/home');
       }

       //obtenemos el nombre del archivo
       $nombre = $file->getClientOriginalName();

       //indicamos que queremos guardar un nuevo archivo en el disco local
       \Storage::disk('local')->put($nombre,  \File::get($file));

       $ruta = "storage/app" . "/" . $nombre;

       set_time_limit(0);

       $result = \Excel::load($ruta, function($reader) use ($request) {
            
            $results = $reader->toArray();
            $proyecto = Session::get('rds_gop_proyecto_id');

            $placaA = "";
            $fechaA = "";
            $kilometrajeA = "";
            $observacionA = "";

            if(isset($results[0]["placa"]) == false ||
                    isset($results[0]["fecha"]) == false ||
                    isset($results[0]["kilometraje"]) == false )
            {
                Session::flash('dataExcel2',"El archivo que esta tratando de cargar no tiene el formato específico.");                  
            }
            else
            {
                for ($i=0; $i < count($results); $i++) {
                    if($results[$i]["placa"] == "" || $results[$i]["placa"] == NULL ||
                        $results[$i]["fecha"] == "" || $results[$i]["fecha"] == NULL  ||
                        $results[$i]["kilometraje"] == "" || $results[$i]["kilometraje"] == NULL
                        )
                        continue;

                    $placa = $results[$i]["placa"];
                    $fecha = $results[$i]["fecha"];
                    $kilometraje = intval($results[$i]["kilometraje"]);
                    $observacion = $results[$i]["observacion"];
                    $fechaD = 0;
                    $placaBusca = DB::connection('sqlsrvCxParque')
                                    ->table('tra_maestro')
                                    ->where('placa',$placa)
                                    ->value('placa');

                    if($placaBusca == "")
                    {
                        $placaA .= "\n - El vehículo $placa no existe \n";
                        continue;
                    }
                    
                    //$fechaD = explode("/",$fecha);
                    /*foreach ($fecha as $k => $v) {
                       $fechaD =  explode(" ",$v)[0];
                       break;
                    }*/

                    $fechaIn =  explode("-",$fecha);
                    if(count($fechaIn) < 2)
                    {
                        $fechaA .= "\n - El formato de la fecha del vehículo $placa no tiene el formato dd/mm/yyyy \n";   
                        break;
                    }

                    $fechaD = $fechaIn[0] . "-" . $fechaIn[1] . "-" . $fechaIn[2];


                    if(!is_numeric($kilometraje))
                    {
                        $kilometrajeA .= "\n - El kilometraje del vehículo $placa no es númerico \n";   
                        continue;
                    }
                    
                    //Consulta los últimos 20 records de Odometros
                    $odometerArray = DB::connection('sqlsrvCxParque')
                                    ->table('tra_vehiculo_odometro')
                                    ->where('placa',$placa)
                                    ->orderBy('fecha','desc')
                                    ->select(DB::raw("TOP(1)fecha"),'kilometraje')
                                    ->get();

                    /*$recorrido = 0;
                    $cantDia = 0;
                    $ultimaFechaRecorrido = "";
                    for ($j=(count($odometerArray) - 1); $j > 0; $j--) { 
                        $kilo = $odometerArray[$j]->kilometraje;
                        $kiloSigui = $odometerArray[$j - 1]->kilometraje;
                        $recorridoDia = floatval($kiloSigui)  - floatval($kilo);
                        $recorrido += $recorridoDia;
                        $cantDia++;
                        //var_dump("RECORRIDO DÍA $cantDia:  " . $recorridoDia . " -> Fecha" . $odometerArray[$j]->fecha);
                        $ultimaFechaRecorrido = $odometerArray[$j]->fecha;
                    }



                    if(count($odometerArray) > 0)
                    {
                        $cantDia++;
                        //var_dump("RECORRIDO DÍA $cantDia:  " . $odometerArray[0]->kilometraje  . " -> Fecha" . $odometerArray[$j]->fecha);
                        $recorrido +=  floatval($odometerArray[0]->kilometraje); 
                        $ultimaFechaRecorrido = $odometerArray[0]->fecha;
                    }


                    //var_dump("DIAS TOTALES RECORRIDOS:  " . $cantDia);
                    //var_dump("ULTIMA FECHA RECORRIDO:  " . $ultimaFechaRecorrido);
                    //var_dump("FECHA RECORRIDO ACTUAL:  " . $fecha);
                    $kiloActual = (count($odometerArray) == 0 ? 0 : $odometerArray[0]->kilometraje);
                    //var_dump("ÚLTIMO KILOMETRAJE  " . $kiloActual);
                    $datetime1 = new \DateTime($ultimaFechaRecorrido);
                    $datetime2 = new \DateTime($fechaD);
                    $interval = $datetime2->diff($datetime1);
                    $dias =  intval($interval->format('%R%a'));
                    $dias = ($dias < 0 ? $dias * -1 : $dias) - 1;
                    //var_dump("DIAS PASADOS DESDE ULTIMO RECORRIDO A ACTUAL -  SIN CONTAR EL DÏA ACTUAL:  " . $dias . " DíAS");            
                    $promedio = $recorrido/($cantDia == 0 ? 1 : $cantDia );
                    $sumaPromedio = 0;
                    //var_dump("PROMEDIO RECORRIDO:  " . $promedio);

                    if($dias > 0 )
                    {
                        ////var_dump("POR LOS :  " . $dias . " DíAS RECORRIDO, MÁS O MENOS TUVO QUE HABER RECORRIDO: " . ($promedio * $dias));            
                        $sumaPromedio = $promedio * $dias;
                        $promedio = $promedio + $sumaPromedio;
                        //var_dump("PROMEDIO RECORRIDO + DÍAS EXTRAS:  " . $promedio);                
                    }
                    //var_dump("PROMEDIO RECORRIDO + 50%:  " . $promedio * 0.5);
                    //var_dump("A LO MAXIMO PUEDE RECORRER  " . (($promedio * 0.5) + $promedio));

                    $kiloMaximo =  (count($odometerArray) == 0 ? 100000000000 :  (($promedio * 0.5) + $promedio));
                    
                    //var_dump("KILOMETRA QUE PUEDE INGRESAR ENTRE  "  .  $promedio . " Y ". $kiloMaximo);
                    //var_dump("KILOMETRAJE A INGRESAR " . $kilometraje);
                    

                    if($kilometraje >= $kiloMaximo || $kilometraje <= $promedio )
                    {
                        $observacionA .= "\n - El kilometraje $kilometraje del vehículo $placa, no se encuentra dentro del promedio(" . $promedio . " Y ". $kiloMaximo . ")\n";   
                        continue;
                    }
                    */

                    $kiloActual  = 0;

                    if(count($odometerArray) > 0)
                        $kiloActual = intval(str_replace(".00","",$odometerArray[0]->kilometraje));

                    if($kilometraje <= $kiloActual)
                    {
                        $observacionA .= "\n - El kilometraje $kilometraje del vehículo $placa es menor o igual al último ingresado (" . $kiloActual . ")\n";   
                        continue;
                    }
                    
                    DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->insert(
                            array(
                                array(
                                    'placa' => $placa,
                                    'fecha_servidor' => $this->fechaALong,
                                    'fecha' => $fechaD ,
                                    'kilometraje' => $kilometraje,
                                    'observaciones' => "CARGA MASIVA EXCEL: " . $observacion,
                                    'usuario' => Session::get('user_login')
                                    )
                                ));
                    //var_dump($odometerArray);
                    echo "<br>";
                    //dd($odometerArray);
                }
            }

            if($placaA != "")
                $placaA  = " Se encuentran las siguientes placa que no existen:\n" . $placaA;

            if($fechaA != "")
                $fechaA  = "\n Se encuentran las siguiente fechas en otro formato:\n" . $fechaA;

            if($kilometrajeA != "")
                $kilometrajeA  = "\n Se encuentran las siguientes kilometrajes que no son valores númericos:\n" . $kilometrajeA;

            if($observacionA != "")
                $observacionA  = "\n Se encuentran las siguientes kilometrajes inconsistentes:\n" . $observacionA;

            if($placaA != "" || $fechaA != "" || $kilometrajeA != "" || $observacionA != "")
                Session::flash('dataExcel2',$placaA . "\n" . $fechaA . "\n" . $kilometrajeA . "\n" . $observacionA);     
            //var_dump($results);
        })->get();
        
        Session::flash('dataExcel1',"Se ha cargado correctamente el Excel de los Kilometrajes");

        //var_dump("todo BIEN");
      return Redirect::to('/transversal/transporte/home');    
    }

    //Consulta el reporte de odometros
    public function reporteOdometro()
    {

        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];


        $tipoVehiculos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/


         $id_perfil = DB::table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    ->value('id_perfil');       

        $proyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/

        $dat = [];

        /*Session::forget("fecha_inicio");
        Session::forget("fecha_corte");
        Session::forget("selTipoVehiculo");
        Session::forget("selProyectoCliente");
        Session::forget("txt_placa");*/

        if(Session::has("fecha_inicio"))
        {
            $fecha1 = explode("/",Session::get("fecha_inicio"))[2] . "-" . explode("/",Session::get("fecha_inicio"))[1] . "-" . explode("/",Session::get("fecha_inicio"))[0];
            $fecha2 = explode("/",Session::get("fecha_corte"))[2] . "-" . explode("/",Session::get("fecha_corte"))[1] . "-" . explode("/",Session::get("fecha_corte"))[0];

            $tipo = Session::get("selTipoVehiculo");
            $pro = Session::get("selProyectoCliente");
            $placa = Session::get("txt_placa");

            if($placa == "")
            {
                $aux1 = "";
                if($tipo != "")
                    $aux1 = " AND tipo.id_tipo_vehiculo = $tipo";

                if($pro != "")
                    $aux1 .= " AND pro.id = $pro";

                $consulta = " 
            
                select odo.id,odo.placa,odo.fecha,odo.observaciones,odo.kilometraje,
                pro.nombre as proyecto,tipo.nombre as tipoV,nombre_cam as tipoVCAM
                from tra_vehiculo_odometro as odo
                INNER JOIN tra_maestro as veh ON odo.placa = veh.placa
                INNER JOIN tra_tipo_vehiculo as tipo ON tipo.id_tipo_vehiculo = veh.id_tipo_vehiculo
                INNER JOIN tra_contratantes as pro ON pro.id = veh.id_proyecto
                WHERE odo.fecha BETWEEN '$fecha1' AND '$fecha2'
                  $aux1";
            }
            else
            {
                $consulta = " 
            
                select odo.id,odo.placa,odo.fecha,odo.observaciones,odo.kilometraje,
                pro.nombre as proyecto,tipo.nombre as tipoV,nombre_cam as tipoVCAM
                from tra_vehiculo_odometro as odo
                INNER JOIN tra_maestro as veh ON odo.placa = veh.placa
                INNER JOIN tra_tipo_vehiculo as tipo ON tipo.id_tipo_vehiculo = veh.id_tipo_vehiculo
                INNER JOIN tra_contratantes as pro ON pro.id = veh.id_proyecto
                WHERE veh.placa LIKE '%$placa%'";
            }
            
            $dat = DB::connection('sqlsrvCxParque')->select($consulta);
            
        }

        return view('proyectos.transporte.reporteOdometro',array(
            'fecha1' => $fechaActual,
            'fecha2' => $nuevafecha,
            'tipoM' => $tipoVehiculos,
            'proy' => $proyecto,
            'dat' => $dat
            ));
    }

    //Filtro para la consulta el reporte de odometros
    public function filterreporteOdometro(Request $request)
    {
        Session::put('fecha_inicio',$request->all()["fecha_inicio"]);
        Session::put('fecha_corte',$request->all()["fecha_corte"]);
        Session::put('selTipoVehiculo',$request->all()["selTipoVehiculo"]);
        Session::put('selProyectoCliente',$request->all()["selProyectoCliente"]);
        Session::put('txt_placa',$request->all()["txt_placa"]);
        return Redirect::to('transversal/reporte/odometro');
    }
    
    //Reporte vehiculo sin kilometraje
    public function indexvehiculosinkilometraje()
    {   

        $datos = [];

        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];

        if(Session::has('fecha_inicio_vso'))
        {

            $fecha1 = explode("/",Session::get('fecha_inicio_vso'))[2] . "-" . explode("/",Session::get('fecha_inicio_vso'))[1] . "-" . explode("/",Session::get('fecha_inicio_vso'))[0];
            $fecha2 = explode("/",Session::get('fecha_fin_vso'))[2] . "-" . explode("/",Session::get('fecha_fin_vso'))[1] . "-" . explode("/",Session::get('fecha_fin_vso'))[0];

            $cad = "EXEC sp_tra_consulta_vehiculos_sin_kilometraje '$fecha1','$fecha2'";

            $datos = DB::connection('sqlsrvCxParque')
                    ->select("SET NOCOUNT ON;" . $cad);
        }
        
        return view('proyectos.transporte.indexVehiculosSinKilometraje',array(
            'fecha1' => $fechaActual,
            'fecha2' => $nuevafecha,
            'datos' => $datos
            ));  
    }

    //Reporte Filter vehiculo sin kilometraje
    public function filterindexvehiculosinkilometraje(Request $request)
    {

        Session::put('fecha_inicio_vso',$request->all()["fecha_inicio_vso"]);
        Session::put('fecha_fin_vso',$request->all()["fecha_fin_vso"]);
        
        return Redirect::to('transversal/reportes/vehiculosSinKilometraje');
    }

    /*********FIN GESTIÓN DE ODOMETRO **********/
    /*******************************************/



    /*******************************************/
    /***************GESTIÓN MATENIMIENTO*************/

    //Inserta el primer mantenimiento del vehículo
    public function insertMantenimientoPrimero(Request $request)
    {
        $placa = $request->all()["placa"];
        $fecha1 = explode("/",$request->all()["txtFechaUltimoMante"])[2] . "-" . explode("/",$request->all()["txtFechaUltimoMante"])[1] . "-" . explode("/",$request->all()["txtFechaUltimoMante"])[0];
        $kilome = $request->all()["txtKilometraje"];
        $observacione = $request->all()["txtObservacionPrimerM"];
        $fecha2 = explode("/",$request->all()["txtFechaProximoMantenimiento"])[2] . "-" . explode("/",$request->all()["txtFechaProximoMantenimiento"])[1] . "-" . explode("/",$request->all()["txtFechaProximoMantenimiento"])[0]; 
        
       /* var_dump($placa);
        //obtenemos el campo file definido en el formulario
        $file = $request->file('file_adjunto_primer_mante');
        //Varificamos que carge un .xlsx
        $mime = $file->getMimeType();
        //obtenemos el nombre del archivo
        $nombre = $file->getClientOriginalName();
        $ruta =  $nombre;
        //indicamos que queremos guardar un nuevo archivo en el disco local
        \Storage::disk('local')->put($nombre,  \File::get($file));*/
        //storage_path()
        
        $ruta = "PRIMER MANTENIMIETNO";
        DB::connection('sqlsrvCxParque')
                    ->table('tra_vehiculo_mantenimiento')
                    ->insert(
                        array(
                            array(
                                'placa' => $placa,
                                'fecha_ultimo_mantenimiento' => $fecha1,
                                'kilometraje' => $kilome,
                                'observacion' => $observacione,
                                'fecha_proximo_mantenimiento' => $fecha2,
                                'adjunto_ruta' => $ruta,
                                )
                        ));

        DB::connection('sqlsrvCxParque')
            ->table('tra_maestro')
            ->where('placa',$placa)
            ->update(
                array(
                    'primer_mantenimiento' => 1
                ));


        Session::flash('dataExcel1',"Se ha registrado correctamente el primer mantenimiento del vehículo $placa");
        Session::flash('imagen_guardada',$placa);
        return Redirect::to('/transversal/transporte/home');   
    }

    //Consulta las incidencias que se han generar para un vehículo
    public function mantenimientoVehiculo($placa = "")
    {
        $vehiculoData =null;
        $incidencias = [];
        if($placa != "")
        {   

            $vehiculoData = DB::connection('sqlsrvCxParque')
                ->Table('tra_maestro as tbl1')
                ->where('tbl1.placa',$placa)
                ->leftJoin('tra_propietarios as tbl2','tbl1.id_propietario','=','tbl2.id_propietario')
                ->join('tra_ciudades as tbl3','tbl3.id_ciudad','=','tbl1.id_ciudad')
                ->join('tra_marcas as tbl4','tbl4.id_marca','=','tbl1.id_marca')
                ->join('tra_tipo_vehiculo as tbl5','tbl5.id_tipo_vehiculo','=','tbl1.id_tipo_vehiculo')
                ->join('tra_tipo_clase as tbl6','tbl6.id_tipo_clase','=','tbl1.id_clase')
                ->join('tra_tipo_combustible as tbl7','tbl7.id_tipo_combustible','=','tbl1.id_tipo_combustible')
                ->join('tra_tipo_transmision as tbl8','tbl8.id_tipo_transmision','=','tbl1.id_transmision')
                ->join('tra_tipo_vinculacion as tbl9','tbl9.id_tipo_vinculo','=','tbl1.id_tipo_vinculo')
                ->join('tra_estados as tbl10','tbl10.id_estado','=','tbl1.id_estado')
                ->select('tbl1.placa','tbl1.id_ciudad','tbl1.id_tipo_vehiculo','tbl1.id_marca','tbl1.modelo','tbl1.color','tbl1.pasajeros'
                    ,'tbl1.linea','tbl1.cilindraje','tbl1.id_tipo_combustible','tbl1.id_transmision','tbl1.id_tipo_vinculo'
                    ,'tbl1.id_clase','tbl1.id_estado','tbl1.chasis'
                    ,'tbl1.motor','tbl1.gps','tbl1.propietario_gps','tbl1.id_proveedor_monitoreo','tbl1.serie_gps'
                    ,'tbl1.valor_contrato','tbl1.capacete','tbl1.portaescaleras','tbl1.caja_herramientas','tbl1.portapertiga'
                    ,'tbl1.id_propietario','tbl1.id_proyecto','tbl1.fecha_vinculo as fecha'
                    ,'tbl2.domicilio','tbl2.cedula','tbl2.telefonoFijo','tbl2.telefonoCel','tbl2.correo','tbl2.nombre as nombreP',
                    'tbl3.nombre as nombreC','tbl4.nombre as nombreM','tbl5.nombre as nombreT','tbl6.nombre as nombreClase',
                    'tbl7.nombre as nombreCombus','tbl8.nombre as nombreTran','tbl9.nombre as nombreV',
                    'tbl10.nombre as nombreEstado')
                ->get()[0];


            $cad = "EXEC sp_tra_consulta_incidencia_vehiculo '$placa'";

            $incidencias = DB::connection('sqlsrvCxParque')
                ->select("SET NOCOUNT ON;" . $cad);

            //dd($incidencias);
        }
        

        return view("proyectos.transporte.mantenimientoVehiculo",
            array(
                'datos' =>  $vehiculoData,
                'incidencias' => $incidencias
                ));
    }

    /*********FIN GESTIÓN MATENIMIENTO **********/
    /*******************************************/




    /*******************************************/
    /***************GESTIÓN INCIDENCIAS*************/
    
    //Función encargada de mostrar la interfaz de centro de control, y la consulta de incidencias
    public function verincidencias()
    {
        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');


        $permisoIncidencia = self::consultaAcceso('OP076');

        if(count($permisoIncidencia) == 0)
            return view('errors.nopermiso');   

        // W -> Total
        // R -> Restringuido
        // N -> No tiene acceso

        if($permisoIncidencia[0]->nivel_acceso == "N")
            return view('errors.nopermiso');               

        $permisoSeguimiento = self::consultaAcceso('OP078');

        $super =  DB::connection('sqlsrvCxParque')
            ->table('tra_token_movil as tbl1')
            ->select(DB::raw("DISTINCT tbl1.usuario"))
            ->groupBy('tbl1.usuario')
            ->get();

        $arbolNivel1 =
                DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->where('fila',1)
                    ->orderBy('descripcion')
                    ->lists('descripcion', 'id');

        $tiposInci =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_tipo')
                    ->orderBy('id')
                    ->lists('nombre', 'id');

        $ciudades =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_ciudades')
                    ->orderBy('nombre')
                    ->where('nombre','<>','')
                    ->lists('nombre');


        $arr = [];
        foreach ($super as $key => $value) {
            $supervisor = DB::connection('sqlsrvCxParque')
                ->table('tra_token_movil as tbl1')
                ->where('tbl2.usuario_movil',$value->usuario)
                ->where('tbl1.usuario',$value->usuario)
                ->join('tra_gps_track as tbl2','tbl1.usuario','=','tbl2.usuario_movil')
                ->select(DB::raw("TOP(1) tbl1.usuario"),'longitud','latitud','fecha','hora')
                ->groupBy('tbl1.usuario','longitud','latitud','fecha','hora')
                ->orderBy('fecha','desc')
                ->orderBy('hora','desc')
                ->get();   

            array_push($arr,[$supervisor,DB::table('rh_personas')
                                        ->where('identificacion',$value->usuario)
                                        ->select('nombres','apellidos')
                                        ->get()
            ]);

        }

        $cad = "EXEC sp_tra_consulta_capacidad_taller ''";

        $talleres = DB::connection('sqlsrvCxParque')
        ->select("SET NOCOUNT ON;" . $cad);

        $cad = "EXEC sp_tra_consulta_incidencias 'E01',''";

        $incidenciaG = DB::connection('sqlsrvCxParque')
        ->select("SET NOCOUNT ON;" . $cad);

        $cadTecnicos = "EXEC sp_tra_consulta_tecnicos '',''";
        $tecnicos = DB::connection('sqlsrvCxParque')
        ->select("SET NOCOUNT ON;" . $cadTecnicos);

        $cadVehiculos = "EXEC sp_tra_consulta_vehiculos '',''";
        $vehiculos = DB::connection('sqlsrvCxParque')
        ->select("SET NOCOUNT ON;" . $cadVehiculos);
        


        return view('proyectos.transporte.indexIncidencias',
            array(
                "incidencias" => $incidenciaG,
                "super" => $arr,
                "arbol" => $arbolNivel1,
                "tiposInci" => $tiposInci ,
                "talleres" => $talleres,
                "tecnicos" => $tecnicos,
                "vehiculos" => $vehiculos,
                "ciudades" => $ciudades,
                "acceso" => $permisoIncidencia[0]->nivel_acceso,
                "acceso2" => $permisoSeguimiento
                ));
    }

    //Función encargada de consultar una incidencia en particular y su detalle
    public function verInci($inci = "")
    {
        //if(!Session::has('user_login'))
        //    return Redirect::to('{{config("app.Campro")[2]}}/campro');
        
        if($inci == "")
            return Redirect::to('transversal/reportes/semanal');

        $permisoIncidencia = self::consultaAcceso('OP078');

        if(count($permisoIncidencia) == 0)
            return view('errors.nopermiso');   

        // W -> Total
        // R -> Restringuido
        // N -> No tiene acceso

        if($permisoIncidencia[0]->nivel_acceso == "N")
            return view('errors.nopermiso');      


        $cad = "EXEC sp_tra_consulta_incidencia_ver '" . $inci . "'";
        $incidencia = DB::connection('sqlsrvCxParque')
                        ->select("SET NOCOUNT ON;" . $cad)[0];

        $adjuntoInci =DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_anexos')
                        ->where('incidencia',$inci)
                        ->where('tipo',"T01")
                        ->orderBy('fecha_servidor')
                        ->select('ruta')
                        ->get();

        $fotosCon =DB::connection('sqlsrvCxParque')
                ->table('tra_incidencia_anexos')
                ->where('incidencia',$inci)
                ->where('tipo',"T00")
                ->orderBy('fecha_servidor')
                ->select('ruta')
                ->get();

        $fotosT =DB::connection('sqlsrvCxParque')
                ->table('tra_incidencia_anexos')
                ->where('incidencia',$inci)
                ->where('tipo',"T02")
                ->orderBy('fecha_servidor')
                ->select('ruta')
                ->get();



        $visitas = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_visita as visi')
                    ->join('CAMPRO.dbo.sis_usuarios as usua','usua.id_usuario','=','visi.usuario')
                    ->where('visi.incidencia',$inci)
                    ->orderBy('visi.fecha_servidor','desc')
                    ->get(['usua.propietario','visi.fecha_visita','visi.observacion']);

        return view('proyectos.transporte.verIncidencia',array(
                'datos' => $incidencia,
                'adjun' => $adjuntoInci,
                'fotos' => $fotosT,
                'fotosC' => $fotosCon,
                'perfil' => self::consultaAcceso('OP075'),
                'perfilCostoReal' => self::consultaAcceso('OP081'), //Edición costo Real
                'perfilFinalizarInci' => self::consultaAcceso('OP082'), //Finalizar incidencia
                'perfilAnularInci' => self::consultaAcceso('OP083'), //Anular incidencia
                'acceso' => $permisoIncidencia[0]->nivel_acceso,
                'visita' => $visitas
            ));
    }

    //Función encargada de cargar el adjunto de la incidencia
    public function insertAdjuntoInci(Request $request)
    {
        $inci = $request->all()["incidencia"];
        $file = $request->file('file_upload');
        //Varificamos que carge un .xlsx
        $mime = $file->getMimeType();


        //obtenemos el nombre del archivo
        $nombre = "Transporte_" . $this->fechaShort . "_" .  substr(md5(uniqid(rand())), 0, 5) . ".pdf";
        self::envioArchivos(\File::get($file),$nombre,"/anexos_apa/documentosvehiculos");
         $nombre  = "/anexos_apa/documentosvehiculos/" . $nombre;

        DB::connection('sqlsrvCxParque')
            ->table('tra_incidencia_anexos')
            ->insert(array(
                array(
                    'incidencia' => $inci,
                    'ruta' => $nombre,
                    'usuario' => Session::get('user_login'),
                    'tipo' => "T01",
                    'fecha_servidor' => $this->fechaALong
                )
            ));

        Session::flash('dataExcel1',"Se ha cargado correctamente el adjunto de la incidencia");

        //var_dump("todo BIEN");
        return Redirect::to("transversal/incidencia/$inci");
    }

    //Función encargada de descargar el PDF del mantenimiento
    public function donwloadpdfmantenimiento(Request $request)
    {

            $inci = $request->all()['inci'];
            $consulta = " 
            SELECT tbl1.incidencia,tbl1.placa,tbl3.nombre AS tipo_vehiculo,'' as telefono,
            ' CORRECTIVO' AS tipo_man,tbl4.nombre as marca,tbl1.fecha_servidor as fechaP,
            tbl2.modelo,tbl1.observacion,tbl5.descripcion as tipoInci,tbl6.nombre_proveedor
            FROM tra_incidencia AS tbl1 
            INNER JOIN tra_maestro as tbl2 ON tbl1.placa = tbl2.placa
            LEFT JOIN tra_tipo_vehiculo as tbl3 ON tbl2.id_tipo_vehiculo = tbl3.id_tipo_vehiculo
            INNER JOIN tra_marcas as tbl4 ON tbl4.id_marca = tbl2.id_marca
            LEFT JOIN gop_arbol_decision as tbl5 ON tbl1.tipo_incidencia = tbl5.id
            LEFT JOIN tra_talleres_gps AS tbl6 ON tbl6.id = tbl1.taller_asignado
            WHERE incidencia = '" . $inci . "'";

            $usuario = DB::Table('sis_usuarios')
                        ->where('id_usuario',Session::get('user_login'))
                        ->value('propietario');

            $dat = DB::connection('sqlsrvCxParque')->select($consulta)[0];
            $view =  \View::make('proyectos.pdf.transporte.ordenmantenimiento', array('inci' => $dat,'user' => $usuario))->render();
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            return $pdf->download('Orden de mantenimiento ' . $inci . '.pdf');
    }

    /*********FIN GESTIÓN INCIDENCIAS **********/
    /*******************************************/


    /*******************************************/
    /***************ARBOL DE DECCIONES*************/
    
    //Función encargada de mostrar el arbol de decisiones
    public function indexArbolDeciciones()
    {

        $arbolDeciciones = DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->select('id','descripcion','fila','padre','item','inhabilita','tiempo_estimado','asistencia_sitio','desplazamiento_sin_grua','desplazamiento_sede')
                    ->orderBy('id')
                    ->get();

        return view("proyectos.transporte.indexArbolDeciciones",array("arbol" => $arbolDeciciones));
    }

    //Función encargada de realizar el cargue masivo del arbol de decisiones mediante un excel
    public function cargaMasivoArbol(Request $request)
    {
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

        DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->delete();

        DB::connection('sqlsrvCxParque')
                ->table('tra_incidencia_tipo')
                ->delete();

        set_time_limit(0);

        $result = \Excel::load($ruta, function($reader) use ($request) {

            $results = $reader->toArray();

            /*if(isset($results[0]["wbs"])  == false||
                    isset($results[0]["nodo"])  == false||
                    isset($results[0]["material"])  == false||
                    isset($results[0]["cant"])  == false) 
            {
                Session::flash('dataExcel2',"El archivo que esta tratando de cargar para el Árbol de decisiones no es válido");                  
            }
            else
            {*/

            $varFila = 1;
            $varFila1 = 1;
            $varFila2 = 1;
            $varFila3 = 1;
            $incrementa = 1;
            for ($i=0; $i < count($results); $i++) {
                

                $novedad = $results[$i]["novedad"];
                $componente = ($results[$i]["componente"] == "" ? $componente : $results[$i]["componente"]);
                $tipo_falla = $results[$i]["tipo_falla"];
                $status = $results[$i]["status"];
                $inhabilita = $results[$i]["inhabilita"];
                $tiempo = $results[$i]["tiempo"];
                $opcion_1 = $results[$i]["opcion_1"];
                $opcion_2 = $results[$i]["opcion_2"];

                DB::connection('sqlsrvCxParque')
                ->table('tra_incidencia_tipo')
                ->insert(array(
                    array(
                        'id' => $varFila ,
                        'nombre' => $novedad
                        )
                    ));
                
                $varFila1 = 1;
                $varFila2 = 1;
                //Inserta Primera fila
                 DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->insert(array(
                        array(
                            'id' => $varFila,
                            'descripcion' => $novedad,
                            'fila' => 1,
                            'padre' => 0,
                            'item' => $incrementa,
                            'inhabilita' => 0,
                            'tiempo_estimado' => "",
                            'asistencia_sitio' => 0,
                            'desplazamiento_sin_grua' => 0,
                            'desplazamiento_sede' => 0
                            )
                        ));
                $padre1 = $varFila;
                $varFila++;

                //Inserta Segunda fila
                 DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->insert(array(
                        array(
                            'id' => $varFila,
                            'descripcion' => $componente,
                            'fila' => 2,
                            'padre' => $padre1,
                            'item' => $incrementa . "." . $varFila1,
                            'inhabilita' => 0,
                            'tiempo_estimado' => "",
                            'asistencia_sitio' => 0,
                            'desplazamiento_sin_grua' => 0,
                            'desplazamiento_sede' => 0
                            )
                        ));

                $padre2 = $varFila;
                $varFila++;

                //Inserta tercera fila
                 DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->insert(array(
                        array(
                            'id' => $varFila,
                            'descripcion' => $tipo_falla,
                            'fila' => 3,
                            'padre' => $padre2,
                            'item' => $incrementa. "." . $varFila1 . "." . $varFila2,
                            'inhabilita' => 0,
                            'tiempo_estimado' => "",
                            'asistencia_sitio' => 0,
                            'desplazamiento_sin_grua' => 0,
                            'desplazamiento_sede' => 0
                            )
                        ));

                $padre3 = $varFila;
                $varFila++;

                $opc1 = "";
                $opc2 = "";
                $opc3 = "";


                 //= $results[$i]["opcion_1"];
                // $opcion_2 = $results[$i]["opcion_2"];

                if($inhabilita = "SI")
                    $inhabilita = 1;
                else
                    $inhabilita = 0;

                if($opcion_1 != "" && $opcion_1 != null && $opcion_1 != "No aplica")
                {
                    //if(trim($opcion_1) == "Desplazamiento a taller sin grúa.")
                       $opc2 = 1;
                    //else
                    //   $opc2 = 0; 
                    
                    //if(trim($opcion_1) == "Asistencia en Sitio o en patio")
                       $opc1 = 1;
                    //else
                    //   $opc1 = 0; 

                   $opc3 = 0;
                   /*if($opcion_ == "Grúa")
                       $opc1 = 1;
                    else
                       $opc1 = 0; */

                    //Inserta cuarta fila
                     DB::connection('sqlsrvCxParque')
                        ->table('gop_arbol_decision')
                        ->insert(array(
                            array(
                                'id' => $varFila,
                                'descripcion' => $status,
                                'fila' => 4,
                                'padre' => $padre3,
                                'item' => $incrementa. "." . $varFila1 . "." . $varFila2 . ".1",
                                'inhabilita' => $inhabilita,
                                'tiempo_estimado' => $tiempo,
                                'asistencia_sitio' => $opc1,
                                'desplazamiento_sin_grua' => $opc2,
                                'desplazamiento_sede' => $opc3
                                )
                            ));

                }

                $varFila1++;
                $varFila2++;
                $varFila++;
                $incrementa++;               
            }             

            var_dump("Se esta cargando el documento en excel, espero un momento");
            
        })->get();
        
        Session::flash('dataExcel1',"Se ha cargado correctamente el masivo del Árbol de decisiones.");

       return Redirect::to('/arbolDecisiones');
    }

    /*********FIN ARBOL DE DECCIONES **********/
    /*******************************************/



    /************************************************/
    /*****************REPORTES*******************/

    //Reporte de seguimientos de incidencias
    public function reporte1()
    {

        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');
        

        $permisoIncidencia = self::consultaAcceso('OP078');

        if(count($permisoIncidencia) == 0)
            return view('errors.nopermiso');   

        // W -> Total
        // R -> Restringuido
        // N -> No tiene acceso

        if($permisoIncidencia[0]->nivel_acceso == "N")
            return view('errors.nopermiso');               


        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];


        $tipoVehiculos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/


         $id_perfil = DB::table('sis_usuarios')
                    ->where('id_usuario',Session::get('user_login'))
                    ->value('id_perfil');

        $acceso = self::consultaAcceso('OP075');
        $preAcce = "N";
        if($id_perfil == "PU89")        
        {
            $estados =
                    DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_estado')
                        ->whereIn('id_estado',['E04','E05'])
                        ->orderBy('id_estado')
                        ->lists('nombre','id_estado');/*option , value*/   
        }
        else
        {        
            $estados =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_estado')
                    ->orderBy('id_estado')
                    ->lists('nombre','id_estado');/*option , value*/   
        }
        
        $estados =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_estado')
                    ->orderBy('id_estado')
                    ->lists('nombre','id_estado');/*option , value*/   


        $proyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/

        $dat = [];
        if(Session::has("fecha_inicio"))
        {
            $fecha1 = explode("/",Session::get("fecha_inicio"))[2] . "-" . explode("/",Session::get("fecha_inicio"))[1] . "-" . explode("/",Session::get("fecha_inicio"))[0];
            $fecha2 = explode("/",Session::get("fecha_corte"))[2] . "-" . explode("/",Session::get("fecha_corte"))[1] . "-" . explode("/",Session::get("fecha_corte"))[0];

            $tipo = Session::get("selTipoVehiculo");
            $esta = Session::get("selEstado");
            $pro = Session::get("selProyectoCliente");
            $inci = Session::get("txt_incidencia");
            $tipoMan = trim(Session::get("tipoMANTSelect"));
            $placa = trim(Session::get("txt_placa_filter"));

            
            $cad = "EXEC sp_tra_consulta_seguimiento_incidencia '$fecha1','$fecha2','$tipo','$esta','$pro','$inci','$tipoMan','$placa'";
            
            //dd($cad);
            $dat = DB::connection('sqlsrvCxParque')
                    ->select("SET NOCOUNT ON;" . $cad);


        }
        return view('proyectos.transporte.reportesemanal',array(
            'fecha1' => $fechaActual,
            'fecha2' => $nuevafecha,
            'tipoM' => $tipoVehiculos,
            'estadoM' => $estados,
            'proy' => $proyecto,
            'dat' => $dat,
            'perfil' =>  $id_perfil
            ));
    }

    //Filtro para el reporte de seguimientos de incidencias
    public function filterSegInci(Request $request)
    {
        Session::put("fecha_inicio",$request->all()['fecha_inicio']);
        Session::put("fecha_corte",$request->all()['fecha_corte']);
        Session::put("selTipoVehiculo",$request->all()['selTipoVehiculo']);
        Session::put("selEstado",$request->all()['selEstado']);
        Session::put("selProyectoCliente",$request->all()['selProyectoCliente']);
        Session::put("txt_incidencia",$request->all()['txt_incidencia']);
        Session::put("tipoMANTSelect",$request->all()['tipoMANTSelect']);
        Session::put("txt_placa_filter",$request->all()['txt_placa_filter']);

        
        return Redirect::to('transversal/reportes/semanal');
    }


    //Exporte incidencias
    public function exportarIncidencias(Request $request)
    {
        $fecha1 = explode("/",$request->all()["fecha_ini_inci_report"])[2] . "-" . explode("/",$request->all()["fecha_ini_inci_report"])[1] . "-" . explode("/",$request->all()["fecha_ini_inci_report"])[0];
        $fecha2 = explode("/",$request->all()["fecha_fin_inci_report"])[2] . "-" . explode("/",$request->all()["fecha_fin_inci_report"])[1] . "-" . explode("/",$request->all()["fecha_fin_inci_report"])[0];

        $tipo = $request->all()["tipo_vehiculo_inci_report"];
        $esta = $request->all()["estado_inci_report"];
        $pro = $request->all()["proy_inci_report"];
        $inci = $request->all()["inci_inci_report"];
        $tipoMan = trim($request->all()["tipo_mant_inci_report"]);
        $placa = trim($request->all()["placa_inci_report"]);

        
        $cad = "EXEC sp_tra_consulta_seguimiento_incidencia '$fecha1','$fecha2','$tipo','$esta','$pro','$inci','$tipoMan','$placa'";
        
        //dd($cad);
        $dat = DB::connection('sqlsrvCxParque')
                ->select("SET NOCOUNT ON;" . $cad);


         \Excel::create('Exporte de incidencias ' . $this->fechaALong, function($excel) use($dat,$esta) {            

                $excel->sheet('Exporte', function($sheet) use($dat,$esta){
                   
                        $products = ["ORDEN","T","PLACA","TIPO VEHÍCULO(TP)","TIPO CAM","TIPO MANT.","NOVEDAD REPORTADA","COMPONENTE",
                        "TIPO DE FALLA","RESPUESTA","FECHA PROG.","TIEMPO ESTIMADO","TIEMPO RESTANTE","CUMPLIMIENTO","FECHA CUMPLIDO",
                        "PROYECTO","OBSERVACIONES","ESTADO","REALIZADP POR","CERRADO POR","PROXIMO MMTO","VALOR FINAL","KM","ENTRADA A TALLER","OBSER. ENTRADA A TALLER","VALOR COTIZACIÓN","OBS. COTIZACIÓN","SALIDA TALLER","OBS. SALIDA TALLER","FECHA FACTURA","NUM FACTURA","SOLICITANTE"];
                        
                        $sheet->fromArray($products);


                        for ($i=0; $i < count($dat); $i++) 
                        {    


                             $mystring = $dat[$i]->resp;

                            if ((strpos($mystring, "Correctivo") === false)) {
                                $mystring =  "PREVENTIVO";
                            } else {
                                $mystring =  "CORRECTIVO";
                            }

                            $colm1 = "";
                            $colm2 = "";
                             if($esta == "E06")
                             {
                                $colm1 = "";
                                $colm2 = (intval($dat[$i]->tiempo_estimado) - intval($dat[$i]->ho) < 0 ? 'NO' : 'SI') . " " . (intval($dat[$i]->tiempo_estimado) - intval($dat[$i]->ho)) . ":" . $dat[$i]->mi . " Hrs";
                             }
                             else
                             {
                                $colm1 = (intval($dat[$i]->tiempo_estimado) - intval($dat[$i]->ho)) . ":" . $dat[$i]->mi;
                                $colm2 = "";
                             }

                            $sheet->row($i +2, array(
                                $dat[$i]->incidencia,
                                "",
                                strtoupper($dat[$i]->placa),
                                $dat[$i]->tipoVehiculo,
                                strtoupper($dat[$i]->cam),
                                $mystring,
                                $dat[$i]->novedad,
                                $dat[$i]->comp,
                                $dat[$i]->falla,
                                $dat[$i]->resp,
                                $dat[$i]->fecha_servidor,
                                $dat[$i]->tiempo_estimado,
                                $colm1,
                                $colm2,
                                $dat[$i]->fecha_cumplido,
                                $dat[$i]->proyecto,
                                $dat[$i]->observaciones,
                                $dat[$i]->nombreE,
                                $dat[$i]->realizadoPor,
                                $dat[$i]->cerradoPor,
                                $dat[$i]->prox_mmto,
                                $dat[$i]->valor_final,
                                $dat[$i]->km,
                                $dat[$i]->fecha_ingreso,
                                $dat[$i]->observacion,
                                $dat[$i]->costo_ingreso,
                                $dat[$i]->obs_cotiza,
                                $dat[$i]->fecha_salida,
                                $dat[$i]->observacion_salida,
                                $dat[$i]->fecha_fact,
                                $dat[$i]->num_fact,
                                $dat[$i]->solicitante
                                ));     
                 
                        }

                });
        })->export('xls');

    }


    //Reporte de estado general de los documentos
    public function indexReporteGeneralDocumentos()
    {

        $tipoVehiculos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/

        $clases =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_tipo_clase')
                    ->orderBy('id_tipo_clase')
                    ->lists('nombre','id_tipo_clase');/*option , value*/

        $proyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/

        $datos = [];

        if(Session::has("selTipoVehiculoDoc"))
        {
            $cad = "EXEC sp_tra_consulta_general_documentos '" . Session::get("selTipoVehiculoDoc") . "','" . Session::get("selClaseDoc") . "','" . Session::get("selProyectoClienteDoc") . "'";
            $datos = DB::connection('sqlsrvCxParque')
                   ->select("SET NOCOUNT ON;" . $cad); 
        }
        
        return view('proyectos.transporte.indexReporteGeneralDocumentos',array(
            'tipoM' => $tipoVehiculos,
            'clases' => $clases,
            'proy' => $proyecto,
            'dat' => $datos 
            )); 
    }

    //Filter reporte de estado general de los documentos
    public function filtesegdocumentos(Request $request)
    {
        Session::put("selTipoVehiculoDoc",$request->all()['selTipoVehiculoDoc']);
        Session::put("selClaseDoc",$request->all()['selClaseDoc']);
        Session::put("selProyectoClienteDoc",$request->all()['selProyectoClienteDoc']);
        return Redirect::to('transversal/reportes/reportegeneraldocumentos');
    }

    //Reporte de programa de mantenimientos
    public function indexProgramaMantenimiento()
    {
        $tipoVehiculos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/


        $proyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/

        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];


        $datos = [];
        
        if(Session::has("selTipoVehiculoProgMant"))
        {
            /*$cad = "EXEC sp_tra_consulta_programa_mantenimiento '" . Session::get("selTipoVehiculoProgMant") . "','" . Session::get("selProyectoClienteProgMant") . "','" . Session::get("fecha_inicio_prog_man") . "'," . (Session::get("cant_mmto_prog_man") == "" ? 0 :Session::get("cant_mmto_prog_man") )  . ",'" . Session::get("txtPlacaProgMant") . "'";*/

            $cad = "EXEC sp_tra_consulta_programa_mantenimiento '" . Session::get("selTipoVehiculoProgMant") . "','" . Session::get("selProyectoClienteProgMant") . "','" . Session::get("fecha_inicio_prog_man") . "'," . (Session::get("cant_mmto_prog_man") == "" ? 0 :Session::get("cant_mmto_prog_man") );

            $datos = DB::connection('sqlsrvCxParque')
                       ->select("SET NOCOUNT ON;" . $cad); 
        }
        
        
        
        return view('proyectos.transporte.indexProgramaMantenimiento',array(
            'tipo_v' => $tipoVehiculos,
            'proyecto' => $proyecto,
            'dat' => $datos,
            'fecha2' => $nuevafecha
            )); 
    }

    //Filtro del reporte de programa de mantenimientos
    public function filterProgramaMantenimiento(Request $request)
    {
        Session::put("selTipoVehiculoProgMant",$request->all()['selTipoVehiculoProgMant']);
        Session::put("selProyectoClienteProgMant",$request->all()['selProyectoClienteProgMant']);
        Session::put("fecha_inicio_prog_man",$request->all()['fecha_inicio_prog_man']);
        Session::put("cant_mmto_prog_man",$request->all()['cant_mmto_prog_man']);
        Session::put("txtPlacaProgMant",$request->all()['txtPlacaProgMant']);


        
        return Redirect::to('transversal/reportes/programamantenimiento');
    }

    //Consulta vehículos para la Entrega a la operación
    public function indexEntregaOperacion()
    {
        if(!Session::has('user_login'))
            return Redirect::to('{{config("app.Campro")[2]}}/campro');
        
        $fechaActual = explode("-",$this->fechaShort);
        $fechaActual = $fechaActual[2] . "/" .  $fechaActual[1] . "/" .  $fechaActual[0];

        $nuevafecha = strtotime ( '-1 month' , strtotime ( $this->fechaShort ) ) ;
        $nuevafecha = date ( 'Y-m-j' , $nuevafecha );

        $nuevafecha = explode("-",$nuevafecha);
        $nuevafecha = $nuevafecha[2] . "/" .  $nuevafecha[1] . "/" .  $nuevafecha[0];


        $tipoVehiculos =
            DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->orderBy('nombre')
                ->lists('nombre','id_tipo_vehiculo');/*option , value*/


        $proyecto =
            DB::connection('sqlsrvCxParque')
                ->table('tra_contratantes')
                ->orderBy('nombre')
                ->lists('nombre','id');/*option , value*/


        $dat = [];
        if(Session::has("fecha_inicio"))
        {
            $fecha1 = explode("/",Session::get("fecha_inicio"))[2] . "-" . explode("/",Session::get("fecha_inicio"))[1] . "-" . explode("/",Session::get("fecha_inicio"))[0];
            $fecha2 = explode("/",Session::get("fecha_corte"))[2] . "-" . explode("/",Session::get("fecha_corte"))[1] . "-" . explode("/",Session::get("fecha_corte"))[0];

            $tipo = Session::get("selTipoVehiculoEP");
            $pro = Session::get("selProyectoClienteEP");
            $inci = Session::get("txt_incidenciaEP");

            $cad = "EXEC sp_tra_consulta_entrega_operacion '$fecha1','$fecha2','$tipo','$pro','$inci'";
            
            //dd($cad);
            $dat = DB::connection('sqlsrvCxParque')
                    ->select("SET NOCOUNT ON;" . $cad);


        }
        return view('proyectos.transporte.reporteentregaoperacion',array(
            'fecha1' => $fechaActual,
            'fecha2' => $nuevafecha,
            'tipoM' => $tipoVehiculos,
            'proy' => $proyecto,
            'dat' => $dat
            ));
    }

    //Filtro de onsulta vehículos para la Entrega a la operación
    public function filterEntregaOperacion(Request $request)
    {
        Session::put("fecha_inicio",$request->all()['fecha_inicio']);
        Session::put("fecha_corte",$request->all()['fecha_corte']);
        Session::put("selTipoVehiculoEP",$request->all()['selTipoVehiculo']);
        Session::put("selProyectoClienteEP",$request->all()['selProyectoCliente']);
        Session::put("txt_incidenciaEP",$request->all()['txt_incidencia']);
        return Redirect::to('transversal/reportes/entregaOperacion');
    }

    /*****************FIN REPORTES*******************/
    /************************************************/


    
    /************************************************/
    /*****************OTRAS FUNCIONES****************/

     //Función encargada del envío de las notificaciones de las incidencias asignadas
    public function enviaNotificacion($titulo,$cuerpo,$sound,$icono,$para,$otroD,$superviso)
    {
        // https://firebase.google.com/docs/cloud-messaging/http-server-ref#downstream-http-messages-json
        // https://github.com/fechanique/cordova-plugin-fcm
        try{
            $Headers = array(
                'Authorization: key=AAAAaLOAy6s:APA91bGFlbyhDKveVFAvMHYOcD8ADifWYorXhoYf4BKoYYcHBhtwpu44dKQ8vKNfcD_4X0P_gFsYc-rFdyojFubjdG82gZ5JeE5ghmcOiuRXZ1anQZH5lEEVYx6sLCHlDJdbTaosr8rg',
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
                'to' =>  $to,
                "notification" => array(
                    "title"=>$title,
                    "body"=>$body,
                    "sound"=>$sonido,
                    "icon"=>$icon,
                    "color" => "#084A9E"
                  ),
                  "data" => array(
                    "title"=>$title,
                    "body"=>$body,
                    "otro"=>$otro,
                  )
                );

            $data = json_encode($data);
           
            //Inicia conexion.
            $conexion = curl_init();
            //Indica un error de conexion
            if (FALSE === $conexion)
                throw new \Exception('failed to initialize');

            //Dirección URL a capturar.
            curl_setopt($conexion,CURLOPT_URL,$url);
             //indicando que es post
            curl_setopt($conexion, CURLOPT_POST, 1);
            //para incluir el header en el output.
            curl_setopt($conexion,CURLOPT_HEADER,0);//No muestra la cabecera en el resultado
            //Envía la cabecera
            curl_setopt($conexion, CURLOPT_HTTPHEADER, $Headers);
            //para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
            curl_setopt($conexion,CURLOPT_RETURNTRANSFER,TRUE);
            //Seteando los datos del webServicecs
            curl_setopt($conexion, CURLOPT_POSTFIELDS, $data);
            //Para que no genere problemas con el certificado SSL
            curl_setopt($conexion, CURLOPT_SSL_VERIFYPEER, false);
            $resultado = curl_exec($conexion);

            if (FALSE === $resultado)
                throw new \Exception(curl_error($conexion), curl_errno($conexion));

            curl_close($conexion);
            $resultado = json_decode($resultado);

                DB::connection('sqlsrvCxParque')
                ->table('tra_notificaciones_envio')
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
                    ));

            if($resultado->success == 1)
                return response()->json("1");
            else
                return response()->json("0");

        } catch(\Exception $e) {
            //var_dump($e->getMessage());
            /*trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);*/
            return response()->json("0");
        }
    }
    
    //Función encargada del cargue de las fotografías
    public function uploadFotografias(Request $request)              
    {
        $file = $request->file('file');
        //Varificamos que carge un .xlsx
        $mime = $file->getMimeType();
        //obtenemos el nombre del archivo
        $nombre = explode("_",$file->getClientOriginalName());

        $nombreArchivo = $nombre[0] . "_" . $nombre[1] . "_" . $nombre[2] . "_" . $nombre[3]  . "_" .  substr(md5(uniqid(rand())), 0, 5) . ".jpg";

        $placa = $nombre[2];
        $tipo = $nombre[4];
        $incidencia = $nombre[3];
            DB::connection('sqlsrvCxParque')
                ->table('tra_anexos_fotos')
                ->insert(array(
                    array(
                        'placa' => $placa,
                        'tipo' => $tipo,
                        'incidencia' => $incidencia,
                        'ruta' => $nombreArchivo,
                        'fecha_servidor' => $this->fechaALong
                        )
                    ));

        $id_ftp=ftp_connect("201.217.195.43",21); //Obtiene un manejador del Servidor FTP
        ftp_login($id_ftp,"usuario_ftp","74091450652!@#1723cc"); //Se loguea al Servidor FTP
        ftp_pasv($id_ftp,true); //Se coloca el modo pasivo
        ftp_chdir($id_ftp, "ins"); // Nos dirigimos a la carpeta de destino
        $Directorio=ftp_pwd($id_ftp);
        $Directorio2=$Directorio;

        //Guardar el archivo en mi carpeta donde esta el proyecto    
        
        $res = 0;
        try
        {
            $fileL = storage_path('app') . "/" .  $nombreArchivo;
            \Storage::disk('local')->put($nombreArchivo,  \File::get($file));
            //file_put_contents("$nombreArchivo",$file);
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


        //indicamos que queremos guardar un nuevo archivo en el disco local
        


        return response()->json($res);
    }

    //Enviar los archivos al servidor FTP
    private function envioArchivos($archivo,$nombreArchivo,$carpeta)
    {
        $id_ftp=ftp_connect("190.60.248.195",21); //Obtiene un manejador del Servidor FTP
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
    
    /*****************OTRAS FUNCIONES****************/
    /*****************FIN OTRAS FUNCIONES****************/



    /************************************************/
    /*****************WEB SERVICES CAMPRO WEB*******************/
    
    //Web Services para guardar la información de transporte CAMPRO WEB
    public function guardaInformacionTransporte(Request $request)
    {
        $opcion = $request->all()["opc"];//
        $respuesta = 0;
        if ($opcion == 1)// Guarda y edita ciudades
        {
            $codigo = $request->all()["codigo"];
            $nombre = $request->all()["nombre"];
            $edita = $request->all()["edita"];


            if($codigo == "-1")
            {
                $consulta = "
                      SELECT MAX(dato) as dato
                      FROM
                      (select cast(id_ciudad as integer) AS dato
                      FROM tra_ciudades) as tbl1";

                $maxPro = intval(DB::connection('sqlsrvCxParque')->select($consulta)[0]->dato) + 1;

                DB::connection('sqlsrvCxParque')
                    ->table('tra_ciudades')
                    ->insert(array(array("id_ciudad" => $maxPro, "nombre" => $nombre)));

                $ciudades =
                DB::connection('sqlsrvCxParque')
                        ->table('tra_ciudades')
                        ->orderBy('nombre')
                        ->lists('nombre', 'id_ciudad');/*option , value*/

                return response()->json(view('proyectos.transporte.tables.tblCiudades',
                    array(
                        "ciudades" => $ciudades
                    ))->render());
            }


                $consulta = DB::connection('sqlsrvCxParque')
                    ->table('tra_ciudades')
                    ->where('id_ciudad',$codigo)
                    ->lists('id_ciudad');

            $consulta = DB::connection('sqlsrvCxParque')
                ->table('tra_ciudades')
                ->where('id_ciudad', $codigo)
                ->lists('id_ciudad');

            //var_dump($edita);
            if (count($consulta) > 0 && $edita != "1")//ciudad ya existe
                return response()->json(-1);

            if ($edita != "1") {//Guarda
                DB::connection('sqlsrvCxParque')
                    ->table('tra_ciudades')
                    ->insert(array(array("id_ciudad" => $codigo, "nombre" => $nombre)));
                $respuesta = 1;
                self::saveLog("OPERA001", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            } else {//Edita
                DB::connection('sqlsrvCxParque')
                    ->table('tra_ciudades')
                    ->where('id_ciudad', $codigo)
                    ->update(
                        array(
                            'nombre' => $nombre
                        ));
                $respuesta = 2;
                self::saveLog("OPERA002", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            }
            //var_dump($respuesta);
            $ciudades =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_ciudades')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_ciudad');/*option , value*/

            return response()->json(view('proyectos.transporte.tables.tblCiudades',
                array(
                    "ciudades" => $ciudades
                ))->render());
        }
        if ($opcion == 2)// Guarda y edita tipo vehiculo
        {
            $codigo = $request->all()["codigo"];
            $nombre = $request->all()["nombre"];
            $edita = $request->all()["edita"];

            if($codigo == "-1")
            {
                $consulta = "
                      SELECT MAX(dato) as dato
                      FROM 
                      (select cast(id_tipo_vehiculo as integer) AS dato
                      FROM tra_tipo_vehiculo) as tbl1";

                $maxPro = intval(DB::connection('sqlsrvCxParque')->select($consulta)[0]->dato) + 1;

                DB::connection('sqlsrvCxParque')
                    ->table('tra_tipo_vehiculo')
                    ->insert(array(array("id_tipo_vehiculo" => $maxPro, "nombre" => $nombre)));

                $tipoVehiculos =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_tipo_vehiculo')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_tipo_vehiculo');/*option , value*/

                return response()->json(view('proyectos.transporte.tables.tblTipoVehiculo',
                        array(
                            "tipoVehiculos" => $tipoVehiculos
                        ))->render());
            }


            $consulta = DB::connection('sqlsrvCxParque')
                ->table('tra_tipo_vehiculo')
                ->where('id_tipo_vehiculo', $codigo)
                ->lists('id_tipo_vehiculo');

            if (count($consulta) > 0 && $edita != "1")//el registro ya existe
                return response()->json(-1);
            if ($edita != "1") {//Guarda
                DB::connection('sqlsrvCxParque')
                    ->table('tra_tipo_vehiculo')
                    ->insert(array(array("id_tipo_vehiculo" => $codigo, "nombre" => $nombre)));
                $respuesta = 1;
                self::saveLog("OPERA003", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            } else {//Edita
                DB::connection('sqlsrvCxParque')
                    ->table('tra_tipo_vehiculo')
                    ->where('id_tipo_vehiculo', $codigo)
                    ->update(
                        array(
                            'nombre' => $nombre
                        ));
                $respuesta = 2;
                self::saveLog("OPERA004", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            }
            $tipoVehiculos =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_tipo_vehiculo')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_tipo_vehiculo');/*option , value*/

            return response()->json(view('proyectos.transporte.tables.tblTipoVehiculo',
                array(
                    "tipoVehiculos" => $tipoVehiculos
                ))->render());
        }
        if ($opcion == 3)// Guarda y edita marca
        {
            $codigo = $request->all()["codigo"];
            $nombre = $request->all()["nombre"];
            $edita = $request->all()["edita"];

            if($codigo == "-1")
            {
                $consulta = "
                      SELECT MAX(dato) as dato
                      FROM 
                      (select cast(id_marca as integer) AS dato
                      FROM tra_marcas) as tbl1";

                $maxPro = intval(DB::connection('sqlsrvCxParque')->select($consulta)[0]->dato) + 1;

                DB::connection('sqlsrvCxParque')
                    ->table('tra_marcas')
                    ->insert(array(array("id_marca" => $maxPro, "nombre" => $nombre)));

                $marcas =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_marcas')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_marca');/*option , value*/

            return response()->json(view('proyectos.transporte.tables.tblMarca',
                array(
                    "marcas" => $marcas
                ))->render());
            }


            $consulta = DB::connection('sqlsrvCxParque')
                ->table('tra_marcas')
                ->where('id_marca', $codigo)
                ->lists('id_marca');

            if (count($consulta) > 0 && $edita != "1")//el registro ya existe
                return response()->json(-1);

            if ($edita != "1") {//Guarda
                DB::connection('sqlsrvCxParque')
                    ->table('tra_marcas')
                    ->insert(array(array("id_marca" => $codigo, "nombre" => $nombre)));
                $respuesta = 1;
                self::saveLog("OPERA005", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            } else {//Edita
                DB::connection('sqlsrvCxParque')
                    ->table('tra_marcas')
                    ->where('id_marca', $codigo)
                    ->update(
                        array(
                            'nombre' => $nombre
                        ));
                $respuesta = 2;
                self::saveLog("OPERA006", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            }
            $marcas =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_marcas')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_marca');/*option , value*/

            return response()->json(view('proyectos.transporte.tables.tblMarca',
                array(
                    "marcas" => $marcas
                ))->render());
        }
        if ($opcion == 4)// Guarda datos del vehiculo
        {
            $matricula = $request->all()["matricula"];
            $ciudad = $request->all()["ciudad"];
            $tipo = $request->all()["tipo"];
            $marca = $request->all()["marca"];
            $modelo = $request->all()["modelo"];
            $color = $request->all()["color"];
            $numPasajeros = $request->all()["numPasajeros"];
            $linea = $request->all()["linea"];
            $cilindraje = $request->all()["cilindraje"];
            $tipoCombustible = $request->all()["tipoCombustible"];
            $tipoTransmision = $request->all()["tipoTransmision"];
            $tipoVinculacion = $request->all()["tipoVinculacion"];
            $estado = $request->all()["estado"];
            $fecha = explode("/", $request->all()["fecha"])[2] . "-" . explode("/", $request->all()["fecha"])[1] . "-" . explode("/", $request->all()["fecha"])[0];
            $clase = $request->all()["clase"];

            $rutina = $request->all()["rutina"];


            $dbVehi = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$matricula)
                    ->get();

            if(count($dbVehi) == 0) //Inserta vehículo
            {
                DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->insert(array(
                    array(
                        "placa" => $matricula,
                        "id_ciudad" => $ciudad,
                        "id_tipo_vehiculo" => $tipo,
                        "id_marca" => $marca,
                        "modelo" => $modelo,
                        "color" => $color,
                        "pasajeros" => $numPasajeros,
                        "linea" => $linea,
                        "cilindraje" => $cilindraje,
                        "id_tipo_combustible" => $tipoCombustible,
                        "id_transmision" => $tipoTransmision,
                        "id_tipo_vinculo" => $tipoVinculacion,
                        "fecha_vinculo" => $fecha,
                        "id_clase" => $clase,
                        "id_estado" => 'E01',
                        "id_proveedor_monitoreo" => 1,
                        "id_propietario" => 1,
                        'fecha_servidor' => $this->fechaALong,
                        'rutina_km' => $rutina
                    )
                ));

                self::saveLog('OPERA21',$matricula,'Creación del vehículo');
            }
            else
            {
                $datosA = DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$matricula)
                        ->get(['id_ciudad','id_tipo_vehiculo','id_marca','modelo','color','id_estado',
                            'pasajeros','linea','cilindraje','id_tipo_combustible','id_transmision'
                            ,'id_tipo_vinculo','fecha_vinculo','id_clase'])[0];

                if($datosA->id_ciudad != $ciudad)
                    self::saveLog('OPERA21',$matricula,'Actuliza ciudad de ' . $datosA->id_ciudad . ' A ' . $ciudad);

                if($datosA->id_tipo_vehiculo != $tipo)
                    self::saveLog('OPERA21',$matricula,'Actuliza tipo de ' . $datosA->id_tipo_vehiculo . ' A ' . $tipo);

                if($datosA->id_marca != $marca)
                    self::saveLog('OPERA21',$matricula,'Actuliza marca de ' . $datosA->id_marca . ' A ' . $marca);

                if($datosA->modelo != $modelo)
                    self::saveLog('OPERA21',$matricula,'Actuliza modelo de ' . $datosA->modelo . ' A ' . $modelo);

                if($datosA->color != $color)
                    self::saveLog('OPERA21',$matricula,'Actuliza color de ' . $datosA->color . ' A ' . $color);

                if($datosA->pasajeros != $numPasajeros)
                    self::saveLog('OPERA21',$matricula,'Actuliza num pasajeros de ' . $datosA->pasajeros . ' A ' . $numPasajeros);

                if($datosA->linea != $linea)
                    self::saveLog('OPERA21',$matricula,'Actuliza linea de ' . $datosA->linea . ' A ' . $linea);

                if($datosA->cilindraje != $cilindraje)
                    self::saveLog('OPERA21',$matricula,'Actuliza cilindraje de ' . $datosA->cilindraje . ' A ' . $cilindraje);

                if($datosA->id_tipo_combustible != $tipoCombustible)
                    self::saveLog('OPERA21',$matricula,'Actuliza tipo combustible de ' . $datosA->id_tipo_combustible . ' A ' . $tipoCombustible);

                if($datosA->id_transmision != $tipoTransmision)
                    self::saveLog('OPERA21',$matricula,'Actuliza tipo transmision de ' . $datosA->id_transmision . ' A ' . $tipoTransmision);

                if($datosA->id_tipo_vinculo != $tipoVinculacion)
                    self::saveLog('OPERA21',$matricula,'Actuliza tipo vinculacion de ' . $datosA->id_tipo_vinculo . ' A ' . $tipoVinculacion);

                if($datosA->fecha_vinculo != $fecha)
                    self::saveLog('OPERA21',$matricula,'Actuliza fecha de ' . $datosA->fecha_vinculo . ' A ' . $fecha);

                if($datosA->id_clase != $clase)
                    self::saveLog('OPERA21',$matricula,'Actuliza clase de ' . $datosA->id_clase . ' A ' . $clase);

                if($estado == "E02")
                {
                    DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$matricula)
                        ->update(array(
                                "id_ciudad" => $ciudad,
                                "id_tipo_vehiculo" => $tipo,
                                "id_marca" => $marca,
                                "modelo" => $modelo,
                                "color" => $color,
                                "id_estado" => "E02",
                                "pasajeros" => $numPasajeros,
                                "linea" => $linea,
                                "cilindraje" => $cilindraje,
                                "id_tipo_combustible" => $tipoCombustible,
                                "id_transmision" => $tipoTransmision,
                                "id_tipo_vinculo" => $tipoVinculacion,
                                "fecha_vinculo" => $fecha,
                                "id_clase" => $clase,
                                'rutina_km' => $rutina                   
                        ));

                    DB::connection('sqlsrvCxParque')
                        ->table('tra_token_movil')
                        ->where('usuario',$matricula)
                        ->update(array(
                                "id_estado" => "E02"                  
                        ));
                }
                else{

                    if($estado == "E01")
                    {
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_maestro')
                            ->where('placa',$matricula)
                            ->update(array(
                                    "id_ciudad" => $ciudad,
                                    "id_tipo_vehiculo" => $tipo,
                                    "id_marca" => $marca,
                                    "modelo" => $modelo,
                                    "color" => $color,
                                    "id_estado" => "E01",
                                    "pasajeros" => $numPasajeros,
                                    "linea" => $linea,
                                    "cilindraje" => $cilindraje,
                                    "id_tipo_combustible" => $tipoCombustible,
                                    "id_transmision" => $tipoTransmision,
                                    "id_tipo_vinculo" => $tipoVinculacion,
                                    "fecha_vinculo" => $fecha,
                                    "id_clase" => $clase,   
                                    'rutina_km' => $rutina                 
                            ));

                        DB::connection('sqlsrvCxParque')
                            ->table('tra_token_movil')
                            ->where('usuario',$matricula)
                            ->update(array(
                                    "id_estado" => "E01"                  
                            ));
                    }
                    else
                    {
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_maestro')
                            ->where('placa',$matricula)
                            ->update(array(
                                    "id_ciudad" => $ciudad,
                                    "id_tipo_vehiculo" => $tipo,
                                    "id_marca" => $marca,
                                    "modelo" => $modelo,
                                    "color" => $color,
                                    "pasajeros" => $numPasajeros,
                                    "linea" => $linea,
                                    "cilindraje" => $cilindraje,
                                    "id_tipo_combustible" => $tipoCombustible,
                                    "id_transmision" => $tipoTransmision,
                                    "id_tipo_vinculo" => $tipoVinculacion,
                                    "fecha_vinculo" => $fecha,
                                    "id_clase" => $clase, 
                                    'rutina_km' => $rutina                   
                            ));    
                    }
                    
                }
            }
            
            $respuesta = 1;
            //self::saveLog("OPERA013", $matricula, "PLACA: " . $matricula);
        }
        if ($opcion == 5)// Guarda propietario tabla
        {
            $codigo = $request->all()["codigo"];
            $nombre = $request->all()["nombre"];
            $edita = $request->all()["edita"];

            if($codigo == "-1")
            {
                $consulta = "
                      SELECT MAX(dato) as dato
                      FROM
                      (select cast(id_propietario as integer) AS dato
                      FROM tra_propietarios) as tbl1";

                $maxPro = intval(DB::connection('sqlsrvCxParque')->select($consulta)[0]->dato) + 1;

                DB::connection('sqlsrvCxParque')
                    ->table('tra_propietarios')
                    ->insert(array(array("id_propietario" => $maxPro, "nombre" => $nombre)));

                $propietarios =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_propietarios')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_propietario');/*option , value*/

                return response()->json(view('proyectos.transporte.tables.tblPropietarios',
                    array(
                        "propietarios" => $propietarios
                    ))->render());
            }

            $consulta = DB::connection('sqlsrvCxParque')
                ->table('tra_propietarios')
                ->where('id_propietario', $codigo)
                ->lists('id_propietario');

            if (count($consulta) > 0 && $edita != "1")//el registro ya existe
                return response()->json(-1);

            if ($edita != "1") {//Guarda
                DB::connection('sqlsrvCxParque')
                    ->table('tra_propietarios')
                    ->insert(array(array("id_propietario" => $codigo, "nombre" => $nombre)));
                $respuesta = 1;
                self::saveLog("OPERA007", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            } else {//Edita
                DB::connection('sqlsrvCxParque')
                    ->table('tra_propietarios')
                    ->where('id_propietario', $codigo)
                    ->update(
                        array(
                            'nombre' => $nombre
                        ));
                $respuesta = 2;
                self::saveLog("OPERA008", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            }
            $propietarios =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_propietarios')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_propietario');/*option , value*/

            return response()->json(view('proyectos.transporte.tables.tblPropietarios',
                array(
                    "propietarios" => $propietarios
                ))->render());
        }
        if ($opcion == 6)// Guarda datos propietario
        {
            $propi = $request->all()["propi"];
            $txtcc = $request->all()["txtcc"];
            $domi = $request->all()["domi"];
            $telefeF = $request->all()["telefeF"];
            $teleC = $request->all()["teleC"];
            $email = $request->all()["email"];
            $placa = $request->all()["matri"];


            DB::connection('sqlsrvCxParque')
                ->table('tra_propietarios')
                ->where('id_propietario', $propi)
                ->update(array(
                    'cedula' => $txtcc,
                    'domicilio' => $domi,
                    'telefonoFijo' => $telefeF,
                    'telefonoCel' => $teleC,
                    'correo' => $email
                ));

            $datosA = DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->where('placa',$placa)
                ->get(['id_propietario'])[0];

            if($datosA->id_propietario != $propi)
                self::saveLog("OPERA22", $placa, $datosA->id_propietario . " A " . $propi);

            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->where('placa', $placa)
                ->update(array(
                    'id_propietario' => $propi
                ));

            $respuesta = 1;
            //self::saveLog("OPERA014", $txtcc, "CEDULA: " . $txtcc . " PROPIETARIO DE: " . $placa);
        }
        if ($opcion == 8)// Guarda proveedor monitoreo
        {
            $codigo = $request->all()["codigo"];
            $nombre = $request->all()["nombre"];
            $edita = $request->all()["edita"];


            if($codigo == "-1")
            {
                $consulta = "
                      SELECT MAX(dato) as dato
                      FROM
                      (select cast(id_proveedor_monitoreo as integer) AS dato
                      FROM tra_proveedor_monitoreo) as tbl1";

                $maxPro = intval(DB::connection('sqlsrvCxParque')->select($consulta)[0]->dato) + 1;

                DB::connection('sqlsrvCxParque')
                    ->table('tra_proveedor_monitoreo')
                    ->insert(array(array("id_proveedor_monitoreo" => $maxPro, "nombre" => $nombre)));

                $proveedorMonitoreo =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_proveedor_monitoreo')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_proveedor_monitoreo');/*option , value*/

            return response()->json(view('proyectos.transporte.tables.tblProveedorMonitoreo',
                array(
                    "proveedorMonitoreo" => $proveedorMonitoreo
                ))->render());
            }


            $consulta = DB::connection('sqlsrvCxParque')
                ->table('tra_proveedor_monitoreo')
                ->where('id_proveedor_monitoreo', $codigo)
                ->lists('id_proveedor_monitoreo');

            if (count($consulta) > 0 && $edita != "1")//el registro ya existe
                return response()->json(-1);

            if ($edita != "1") {//Guarda
                DB::connection('sqlsrvCxParque')
                    ->table('tra_proveedor_monitoreo')
                    ->insert(array(array("id_proveedor_monitoreo" => $codigo, "nombre" => $nombre)));
                $respuesta = 1;
                self::saveLog("OPERA009", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            } else {//Edita
                DB::connection('sqlsrvCxParque')
                    ->table('tra_proveedor_monitoreo')
                    ->where('id_proveedor_monitoreo', $codigo)
                    ->update(
                        array(
                            'nombre' => $nombre
                        ));
                $respuesta = 2;
                self::saveLog("OPERA010", $codigo, "CODIGO: " . $codigo . " NOMBRE: " . $nombre);
            }
            $proveedorMonitoreo =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_proveedor_monitoreo')
                    ->orderBy('nombre')
                    ->lists('nombre', 'id_proveedor_monitoreo');/*option , value*/

            return response()->json(view('proyectos.transporte.tables.tblProveedorMonitoreo',
                array(
                    "proveedorMonitoreo" => $proveedorMonitoreo
                ))->render());
        }
        if ($opcion == 9)// Guarda información complementaria
        {
            $gps = $request->all()["gps"];
            $capacete = $request->all()["capacete"];
            $portaEscalera = $request->all()["portaEscalera"];
            $cajaHerramientas = $request->all()["cajaHerramientas"];
            $pertiga = $request->all()["pertiga"];

            $propietarioGps = $request->all()["propietarioGps"];
            $numChasis = $request->all()["numChasis"];
            $proveedorMonitoreo = $request->all()["proveedorMonitoreo"];
            $numMotor = $request->all()["numMotor"];
            $serieGps = $request->all()["serieGps"];
            $numOrden = $request->all()["numOrden"];
            $canon = $request->all()["canon"];
            $placa = $request->all()["matricula"];
            $proyectoCliente = $request->all()["selProyectoCliente"];

            $tipoCAM = $request->all()["tipoCAM"];


            $pry = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa', $placa)
                    ->value('id_proyecto');

            if($pry == NULL || $pry == "")
                DB::connection('sqlsrvCxParque')
                    ->table('tra_cambio_proyectos')
                    ->insert(array(
                        array(
                        'placa' => $placa,
                        'proyecto' => $proyectoCliente,
                        'observacion' => "ASIGNACIÓN DE PROYECTO POR PRIMERA VEZ",
                        'usuario' => Session::get('user_login')
                        )
                    ));

            $matricula = $placa;
            $datosA = DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$matricula)
                        ->get(['gps','capacete','portaescaleras','caja_herramientas','portapertiga', 'propietario_gps','chasis','id_proveedor_monitoreo','motor','serie_gps','valor_contrato','orden'])[0];

                if($datosA->gps != $gps)
                    self::saveLog('OPERA24',$matricula,'Actuliza gps de ' . $datosA->gps . ' A ' . $gps);

                if($datosA->capacete != $capacete)
                    self::saveLog('OPERA24',$matricula,'Actuliza capacete de ' . $datosA->capacete . ' A ' . $capacete);

                if($datosA->portaescaleras != $portaEscalera)
                    self::saveLog('OPERA24',$matricula,'Actuliza portaescaleras de ' . $datosA->portaescaleras . ' A ' . $portaEscalera);

                if($datosA->caja_herramientas != $cajaHerramientas)
                    self::saveLog('OPERA24',$matricula,'Actuliza cajaHerramientas de ' . $datosA->caja_herramientas . ' A ' . $cajaHerramientas);

                if($datosA->portapertiga != $pertiga)
                    self::saveLog('OPERA24',$matricula,'Actuliza pertiga de ' . $datosA->portapertiga . ' A ' . $pertiga);

                if($datosA->propietario_gps != $propietarioGps)
                    self::saveLog('OPERA24',$matricula,'Actuliza propietario gps de ' . $datosA->propietario_gps . ' A ' . $propietarioGps);

                if($datosA->chasis != $numChasis)
                    self::saveLog('OPERA24',$matricula,'Actuliza numChasis de ' . $datosA->chasis . ' A ' . $numChasis);

                if($datosA->id_proveedor_monitoreo != $proveedorMonitoreo)
                    self::saveLog('OPERA24',$matricula,'Actuliza proveedorMonitoreo de ' . $datosA->id_proveedor_monitoreo . ' A ' . $proveedorMonitoreo);

                if($datosA->motor != $numMotor)
                    self::saveLog('OPERA24',$matricula,'Actuliza tipo numMotor de ' . $datosA->motor . ' A ' . $numMotor);

                if($datosA->serie_gps!= $serieGps)
                    self::saveLog('OPERA24',$matricula,'Actuliza tipo serieGps de ' . $datosA->serie_gps. ' A ' . $serieGps);

                if($datosA->valor_contrato != $canon)
                    self::saveLog('OPERA24',$matricula,'Actuliza tipo canon de ' . $datosA->valor_contrato . ' A ' . $canon);

                if($datosA->orden != $numOrden)
                    self::saveLog('OPERA24',$matricula,'Actuliza numOrden de ' . $datosA->orden . ' A ' . $numOrden);

            if($pry == NULL || $pry == "")                    
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa', $placa)
                    ->update(array(
                        'gps' => $gps,
                        'id_proyecto' => $proyectoCliente,
                        'capacete' => $capacete,
                        'portaescaleras' => $portaEscalera,
                        'caja_herramientas' => $cajaHerramientas,
                        'portapertiga' => $pertiga,
                        'propietario_gps' => $propietarioGps,
                        'chasis' => $numChasis,
                        'id_proveedor_monitoreo' => $proveedorMonitoreo,
                        'motor' => $numMotor,
                        'serie_gps' => $serieGps,
                        'valor_contrato' => $canon,
                        'orden' => $numOrden,
                        'id_tipo_vehiculo_cam' => $tipoCAM
                    ));    
            }
            else
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa', $placa)
                    ->update(array(
                        'gps' => $gps,
                        'capacete' => $capacete,
                        'portaescaleras' => $portaEscalera,
                        'caja_herramientas' => $cajaHerramientas,
                        'portapertiga' => $pertiga,
                        'propietario_gps' => $propietarioGps,
                        'chasis' => $numChasis,
                        'id_proveedor_monitoreo' => $proveedorMonitoreo,
                        'motor' => $numMotor,
                        'serie_gps' => $serieGps,
                        'valor_contrato' => $canon,
                        'orden' => $numOrden,
                        'id_tipo_vehiculo_cam' => $tipoCAM
                    ));    
            }
            

            $respuesta = 1;
            //self::saveLog("OPERA015", $placa, "VEHICULO: " . $placa);
        }

        if ($opcion == 10){// Cliente proyecto 

            $codigo = $request->all()["codigo"];
            $nombre = $request->all()["nombre"];
            $pre = $request->all()["pre"];
            $costo = $request->all()["costo"];

            $ln = $request->all()["ln"];
            $op = $request->all()["op"];
            $adicional = $request->all()["adicional"];


            if($codigo == "")
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_contratantes')
                    ->insert(array(array( "nombre" => $nombre, "prefijo" => $pre, "ceco" => $costo, "ln" => $ln, "op" => $op, "adicional" => $adicional)));
            }
            else
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_contratantes')
                    ->where('id',$codigo)
                    ->update(array("nombre" => $nombre, "prefijo" => $pre, "ceco" => $costo, "ln" => $ln, "op" => $op, "adicional" => $adicional));
            }


            $clienteProyecto =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_contratantes')
                    ->orderBy('nombre')
                    ->get(['nombre','id','ceco','prefijo','ln','op','adicional']);/*option , value*/

            return response()->json(view('proyectos.transporte.tables.tblClienteProyecto',
                array(
                    "clienteProyecto" => $clienteProyecto
                ))->render());
        }

        if ($opcion == 14)// Guarda y edita documentos
        {
            $id = $request->all()["id"];
            $nombre = $request->all()["nombre"];
            $edita = $request->all()["edita"];
            $consulta = DB::connection('sqlsrvCxParque')
                ->table('tra_maestro_documentos')
                ->where('nombre_documento', $nombre)
                ->lists('id_documento');
            //var_dump($edita);
            if (count($consulta) > 0 && $edita != 1)//documento ya existe
                return response()->json(-1);

            if ($edita == 0)//Guarda
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro_documentos')
                    ->insert(array(array("nombre_documento" => $nombre)));

                self::saveLog("OPERA016", $nombre, "NOMBRE: " . $nombre);
            } else {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro_documentos')
                    ->where('id_documento', $id)
                    ->update(
                        array(
                            'nombre_documento' => $nombre
                        ));
                self::saveLog("OPERA017", $id, "ID: " . $id . " NOMBRE: " . $nombre);
            }
            $documentos =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro_documentos')
                    ->orderBy('nombre_documento')
                    ->lists('nombre_documento', 'id_documento');/*option , value*/


            return response()->json(view('proyectos.transporte.tables.tblDocumentos',
                array(
                    "documentos" => $documentos
                ))->render());
        }
        if($opcion == 12) //Elimina documento asignada a vehículo
        {
            $placa = $request->all()["placa"];
            $doc = $request->all()["doc"];
            //Insertar LOG
            $logDocu = DB::connection('sqlsrvCxParque')
                ->table('tra_documento_vehiculo')
                ->where('placa',$placa)
                ->where('id_documento',$doc)
                ->select('id_documento','placa','referencia', 'entidad', 'vencimiento', 'direccion_archivo')
                ->get()[0];


            DB::connection('sqlsrvCxParque')
                ->table('tra_documento_vehiculo')
                ->where('placa',$placa)
                ->where('id_documento',$doc)
                ->update(array(
                    'referencia' => "",
                    'entidad' => "",
                    'vencimiento' => "",
                    'direccion_archivo' => ""
                    ));

            $respuesta = "1";
        }
        if($opcion == 13) //Guarda información del conductor
        {
            $placa = $request->all()["matri"];
            $conductor = $request->all()["conductor"];

            $ultimoConductor = DB::connection('sqlsrvCxParque')
                ->table('tra_vehiculos_conductores')
                ->where('placa',$placa)
                ->select('id_persona')
                ->orderBy('fecha_hora','des')
                ->get();

            if(count($ultimoConductor) == 0) //Primer conductor
            {
                DB::connection('sqlsrvCxParque')
                ->table('tra_vehiculos_conductores')
                ->insert(array(
                    array(
                        'placa' => $placa,
                        'id_persona' => $conductor,
                        'fecha_asignacion' => $this->fechaShort,
                        'observaciones' => "",
                        'id_usuario' => Session::get('user_login'),
                        'fecha_hora' => $this->fechaALong
                        )
                    ));

                 DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placa)
                    ->update(array(
                            "id_estado" => 'E01'                  
                    ));

                self::saveLog("OPERA23", $placa, "Primer conductor " . $conductor);
            }
            else
            {
                $ultimoConductor = $ultimoConductor[0]->id_persona;
                 //Si el último conductor no es igual al actual, crearlo
                if($ultimoConductor != $conductor)
                {
                    DB::connection('sqlsrvCxParque')
                    ->table('tra_vehiculos_conductores')
                    ->insert(array(
                        array(
                            'placa' => $placa,
                            'id_persona' => $conductor,
                            'fecha_asignacion' => $this->fechaShort,
                            'observaciones' => "",
                            'id_usuario' => Session::get('user_login'),
                            'fecha_hora' => $this->fechaALong
                            )
                        ));

                    self::saveLog("OPERA23", $placa, $ultimoConductor . " A " . $conductor);
                }
            } 
        }
        if ($opcion == 15)// Guarda asociación documentos
        {
            $idClase = $request->all()["idClase"];
            $documentos = $request->all()["documentos"];

            $consulta =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro_clase_documentos')
                    ->where('id_clase', $idClase)
                    ->select('id_clase')
                    ->get();

            if (count($consulta) == 0)
                self::saveLog("OPERA018", $idClase, "CLASE: " . $idClase);//Guarda
            else
                self::saveLog("OPERA019", $idClase, "CLASE: " . $idClase);//Edita

            //Elimina documentos asociados a esa clase
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro_clase_documentos')
                ->where('id_clase', $idClase)
                ->delete();
            //Guarda documentos asociados a la clase
            for ($i = 0; $i < count($documentos); $i++) {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro_clase_documentos')
                    ->insert(array(
                        array(
                            "id_documento" => $documentos[$i]['id'],
                            "id_clase" => $idClase,
                            "fecha_servidor" => $this->fechaALong,
                        )
                    ));
            }
            $respuesta = 1;
        }
        if($opcion == 16) //Asignación de incidencias Supervisor - Incidencia
        {
            $super = $request->all()["super"];
            $incidencia = $request->all()["incidencia"];

            $token = DB::connection('sqlsrvCxParque')
                    ->table('tra_token_movil')
                    ->where('supervisor',$super)
                    ->value('token_movil');

            if($token == "")
                return response()->json("-1");

            DB::beginTransaction();
            try
            {
                //Asignación de incidencia al supervisor
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'supervisor_asignado' => $super,
                            'fecha_asignacion' => $this->fechaALong
                            )
                        );

                $title = "Asignación de incidencia";
                $body = "Se le ha asignado la incidencia # $incidencia";
                $sonido = "default";
                $icon = "fcm_push_icon";
                $otro = "";

                $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$super);

               DB::commit();
               return response()->json($res);
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            } 
        }
        if($opcion == 17) //Save Arbol de decisiones
        {
            $form = $request->all()["form"];

            //dd($form);
            
            DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->delete();

            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_tipo')
                    ->delete();

            
            for ($i=0; $i < count($form); $i++) {

                if($form[$i]['row'] == 1) 
                {
                    DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_tipo')
                    ->insert(array(
                        array(
                            'id' => $form[$i]['id'],
                            'nombre' => $form[$i]['des']
                            )
                        ));
                }   
                
               DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->insert(array(
                        array(
                            'id' => $form[$i]['id'],
                            'descripcion' => $form[$i]['des'],
                            'fila' => $form[$i]['row'],
                            'padre' => $form[$i]['parent'],
                            'item' => $form[$i]['item'],
                            'inhabilita' => $form[$i]['in'],
                            'tiempo_estimado' => $form[$i]['te'],
                            'asistencia_sitio' => $form[$i]['as'],
                            'desplazamiento_sin_grua' => $form[$i]['dg'],
                            'desplazamiento_sede' => $form[$i]['ds']
                            )
                        ));
            }

            $respuesta = "1";
        }
        if($opcion == 18) //Save Incidencia Web
        {
            $placa = $request->all()["placa"];
            $incidencia = $request->all()["incidencia"];
            $tipoinci = $request->all()["tipoinci"];
            $direccion = $request->all()["direccion"];
            $direccion2 = $request->all()["direccion2"];
            $obser = $request->all()["obser"];
            $fechahora = $request->all()["fechahora"];
            $arbol1 = $request->all()["arbol1"];
            $arbol2 = $request->all()["arbol2"];
            $arbol3 = $request->all()["arbol3"];
            $arbol4 = $request->all()["arbol4"];
            $inhabilita = $request->all()["inhabilita"];
            $txt_tiempo_estimado = $request->all()["txt_tiempo_estimado"];
            $accion = $request->all()["accion"];
            $tecnico_asigna = $request->all()["tecnico_asigna"];
            $taller_asigna = $request->all()["taller_asigna"];
            $base_asigna = $request->all()["base_asigna"];
            $tecnico_asigna2 = $request->all()["tecnico_asigna2"];            
            $otro = $request->all()["otro"];  
            $km = $request->all()["km"];  

            if($accion == 1)
            {
                $taller_asigna = "";
                $base_asigna = "";
                $otro = "";
            }

            if($accion == 2)
            {
                $tecnico_asigna = "";
                $base_asigna = "";
                $otro = "";
            }

            if($accion == 3)
            {
                $taller_asigna = "";
                $otro = "";
            }

            if($accion == 4 || $accion == 5)
            {
                $taller_asigna = "";
                $base_asigna = "";
                $tecnico_asigna = "";
            }


            if($txt_tiempo_estimado == "")
            {
                $txt_tiempo_estimado = 
                            DB::connection('sqlsrvCxParque')
                                ->table('gop_arbol_decision')
                                ->where('id',$arbol4)
                                ->value('tiempo_estimado');
            }

            DB::beginTransaction();
            try
            {
                if($incidencia == "") //Create Incidencia
                {   
                    $estado = "E01";
                    if($accion == 1) //Asigna técnico
                    {
                        if($tecnico_asigna != "" && $tecnico_asigna != null && $tecnico_asigna != "0")
                            $estado = "E02";  // Asigna técnico
                    }

                    if($accion == 2) //Ingreso a taller
                    {
                        if($taller_asigna != "" && $taller_asigna != null && $taller_asigna != "0")
                            $estado = "E03"; // Asigna taller
                    }
                    
                    if($accion == 3) //Regreso a base y asigna a técnico
                    {
                        if($tecnico_asigna2 != "" && $tecnico_asigna2 != null && $tecnico_asigna2 != "0")
                            $estado = "E02"; // Asigna a técnico
                    }

                    if($accion == 5) //Regreso a base y asigna a técnico
                       $estado = "E07"; 


                    $inc = self::generaConsecutivo("ID_INCIDENCIA");

                    //Cambio de estado Vehículo
                    DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->update(array(
                                'id_estado' => "E04"
                            ));

                    DB::connection('sqlsrvCxParque')
                        ->table('tra_token_movil')
                        ->where('usuario',$placa)
                        ->update(array(
                                'id_estado' => "E04"
                            ));


                    DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->insert(array(
                        array(
                            'incidencia' => $inc,
                            'tipo_incidencia' => $tipoinci,
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => $obser,
                            'coordenadas' => $direccion,
                            'placa' => $placa,
                            'novedadReportada' => $arbol1,
                            'componente' => $arbol2,
                            'tipo_falla' => $arbol3,
                            'respuesta' => $arbol4,
                            'inhabilitado' => $inhabilita,
                            'tiempo_estimado' => $txt_tiempo_estimado,
                            'accion' => $accion,
                            'taller_asignado' => $taller_asigna,
                            'tecnico_asignado' => ($accion == 3 ? $tecnico_asigna2 : $tecnico_asigna),
                            'base_asignada' => $base_asigna == "",
                            'fecha_asignacion' => $this->fechaALong,
                            'fecha_cliente' => $fechahora,
                            'id_estado' => $estado,
                            'otro' => $otro,
                            "usuario_crea" => Session::get('user_login'),
                            "km" => $km,
                            "primera_accion" => $accion
                            )
                        ));
                    
                    //Cambia de estados Técnico - Taller - Base y conductor
                    if($accion == 1) //Asigna técnico
                    {
                        if($tecnico_asigna != "" && $tecnico_asigna != null && $tecnico_asigna != "0")
                        {
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_token_movil')
                            ->where('usuario',$tecnico_asigna)
                            ->update(
                                array(
                                    'id_estado' => "E02"
                                    )
                                );

                            DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $inc,
                                    'usuario' => Session::get('user_login'),
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' =>  " Técnico " . $tecnico_asigna . " se dirige al sitio - " . $obser,
                                    'tipo' => 1
                                    )
                                ));

                                $token = DB::connection('sqlsrvCxParque')
                                    ->table('tra_token_movil')
                                    ->where('usuario',$tecnico_asigna)
                                    ->value('token_movil');

                                $title = "Asignación de incidencia";
                                $body = "Se le ha asignado la incidencia # $inc";
                                $sonido = "default";
                                $icon = "fcm_push_icon";
                                $otro = "";

                                $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$tecnico_asigna);

                                $token = DB::connection('sqlsrvCxParque')
                                    ->table('tra_token_movil')
                                    ->where('usuario',$placa)
                                    ->value('token_movil');

                                $title = "Asignación de incidencia";
                                $body = "La incidencia # $inc que usted genero,  se le ha asignado al técnico $tecnico_asigna";
                                $sonido = "default";
                                $icon = "fcm_push_icon";
                                $otro = "";

                                $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$tecnico_asigna);

                        //Envia Notificacion
                        }
                    }

                    if($accion == 2) //Ingreso a taller
                    {
                        //Agrega acción
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $inc,
                                    'usuario' => Session::get('user_login'),
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' => "Vehículo " . $placa. " Remitido a taller - " . $obser,
                                    'tipo' => 1
                                    )
                                ));

                        // Solo funciona cuando hago el ingreso a taller
                        /*if($taller_asigna != "" && $taller_asigna != null && $taller_asigna != "0")
                        {
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_vehiculo_taller')
                            ->insert(array(
                                array(
                                    'idTaller' => $taller_asigna,
                                    'placa' => $placa,
                                    'fecha_ingreso' => $this->fechaALong
                                    )
                                ));
                        }*/
                    }
                    
                    if($accion == 3) //Regreso a base y asigna a técnico
                    {
                        if($base_asigna != "" && $base_asigna != null && $base_asigna != "0")
                        {
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_vehiculo_taller')
                            ->insert(array(
                                array(
                                    'idTaller' => $base_asigna,
                                    'placa' => $placa,
                                    'fecha_ingreso' => $this->fechaALong
                                    )
                                ));
                        }
                        if($tecnico_asigna2 != "" && $tecnico_asigna2 != null && $tecnico_asigna2 != "0")
                        {
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_token_movil')
                            ->where('usuario',$tecnico_asigna2)
                            ->update(
                                array(
                                    'id_estado' => "E01"
                                    )
                                );

                        }

                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $inc,
                                    'usuario' => Session::get('user_login'),
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' =>  "Dirigirse a base " . $base_asigna . " con el técnico  " . $tecnico_asigna . ",- " . $obser,
                                    'tipo' => 1
                                    )
                                ));

                        //Envia Notificacion
                    }

                    if($accion == 5) //Entrega a propietario
                    {
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $inc,
                                    'usuario' => Session::get('user_login'),
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' =>  "Entrega vehículo  $placa a propietario - " . $obser,
                                    'tipo' => 1
                                    )
                                ));
                    }

                    $respuesta = $inc;
                }
                else
                {
                    $estado = "E01";
                    if($accion == 1) //Asigna técnico
                    {
                        if($tecnico_asigna != "" && $tecnico_asigna != null && $tecnico_asigna != "0")
                            $estado = "E02";  // Asigna técnico
                        //Envia Notificacion
                    }

                    if($accion == 2) //Ingreso a taller
                    {
                        if($taller_asigna != "" && $taller_asigna != null && $taller_asigna != "0")
                            $estado = "E03"; // Asigna taller
                    }
                    
                    if($accion == 3) //Regreso a base y asigna a técnico
                    {
                        if($tecnico_asigna2 != "" && $tecnico_asigna2 != null && $tecnico_asigna2 != "0")
                            $estado = "E02"; // Asigna a técnico
                        //Envia Notificacion
                    }

                    if($accion == 5) //Regreso a base y asigna a técnico
                       $estado = "E07"; 


                    $primera_accion = 
                            DB::connection('sqlsrvCxParque')
                                ->table('tra_incidencia')
                                ->where('incidencia',$incidencia)
                                ->value('primera_accion');

                    $segundaAccion = 0;
                    if($primera_accion != $accion)
                        $segundaAccion = $accion;


                    $estadoInciN = DB::connection('sqlsrvCxParque')
                                ->table('tra_incidencia')
                                ->where('incidencia',$incidencia)
                                ->value('id_estado');


                    if($estadoInciN == "E02")
                        return response()->json("-2");

                    if($estadoInciN == "E03")
                        return response()->json("-3");

                    if($estadoInciN == "E04")
                        return response()->json("-4");

                    if($estadoInciN == "E05")
                        return response()->json("-5");

                    if($estadoInciN == "E06")
                        return response()->json("-6");

                    if($estadoInciN == "E07")
                        return response()->json("-7");


                    DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia')
                        ->where('incidencia',$incidencia)
                        ->update(
                            array(
                                'tipo_incidencia' => $tipoinci,
                                'fecha_servidor' => $this->fechaALong,
                                'observacion' => $obser,
                                'coordenadas' => $direccion,
                                'placa' => $placa,
                                'novedadReportada' => $arbol1,
                                'componente' => $arbol2,
                                'tipo_falla' => $arbol3,
                                'respuesta' => $arbol4,
                                'inhabilitado' => $inhabilita,
                                'tiempo_estimado' => $txt_tiempo_estimado,
                                'accion' => $accion,
                                'taller_asignado' => $taller_asigna,
                                'tecnico_asignado' => ($accion == 3 ? $tecnico_asigna2 : $tecnico_asigna),
                                'base_asignada' => $base_asigna,
                                'fecha_asignacion' => $this->fechaALong,
                                'fecha_cliente' => $fechahora,
                                'id_estado' => $estado,
                                'otro' => $otro,
                                'segunda_accion' => $segundaAccion
                            ));
                    
                    

                    //Cambia de estados Técnico - Taller - Base y conductor
                    if($accion == 1) //Asigna técnico
                    {
                        if($tecnico_asigna != "" && $tecnico_asigna != null && $tecnico_asigna != "0")
                        {
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_token_movil')
                            ->where('usuario',$tecnico_asigna)
                            ->update(
                                array(
                                    'id_estado' => "E01"
                                    )
                                );

                            $token = DB::connection('sqlsrvCxParque')
                                    ->table('tra_token_movil')
                                    ->where('usuario',$tecnico_asigna)
                                    ->value('token_movil');

                            $title = "Asignación de incidencia";
                            $body = "Se le ha asignado la incidencia # $incidencia";
                            $sonido = "default";
                            $icon = "fcm_push_icon";
                            $otro = "";

                            $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$tecnico_asigna);

                            $token = DB::connection('sqlsrvCxParque')
                                ->table('tra_token_movil')
                                ->where('usuario',$placa)
                                ->value('token_movil');

                            $title = "Asignación de incidencia";
                            $body = "La incidencia # $incidencia que usted genero,  se le ha asignado al técnico $tecnico_asigna";
                            $sonido = "default";
                            $icon = "fcm_push_icon";
                            $otro = "";

                            $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$tecnico_asigna);

                            DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => Session::get('user_login'),
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' =>  " Técnico " . $tecnico_asigna . " se dirige al sitio - " . $obser,
                                    'tipo' => 1
                                    )
                                ));

                                


                        }
                        //Envia Notificacion
                    }

                    if($accion == 2) //Ingreso a taller
                    {
                        //Agrega acción
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => Session::get('user_login'),
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' => "Vehículo " . $placa. " Remitido a taller - " . $obser,
                                    'tipo' => 1
                                    )
                                ));

                        $token = DB::connection('sqlsrvCxParque')
                                    ->table('tra_token_movil')
                                    ->where('usuario',$placa)
                                    ->value('token_movil');

                        $title = "Asignación de incidencia";
                        $body = "La incidencia # $incidencia que usted genero, ha sido remitido a taller";
                        $sonido = "default";
                        $icon = "fcm_push_icon";
                        $otro = "";

                        $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$tecnico_asigna);

                    }
                    
                    if($accion == 3) //Regreso a base y asigna a técnico
                    {
                        if($base_asigna != "" && $base_asigna != null && $base_asigna != "0")
                        {
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_vehiculo_taller')
                            ->insert(array(
                                array(
                                    'idTaller' => $base_asigna,
                                    'placa' => $placa,
                                    'fecha_ingreso' => $this->fechaALong
                                    )
                                ));
                        }
                        if($tecnico_asigna2 != "" && $tecnico_asigna2 != null && $tecnico_asigna2 != "0")
                        {
                            DB::connection('sqlsrvCxParque')
                            ->table('tra_token_movil')
                            ->where('usuario',$tecnico_asigna2)
                            ->update(
                                array(
                                    'id_estado' => "E01"
                                    )
                                );

                        }

                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => Session::get('user_login'),
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' =>  "Dirigirse a base " . $base_asigna . " con el técnico  " . $tecnico_asigna . ",- " . $obser,
                                    'tipo' => 1
                                    )
                                ));

                        //Envia Notificacion
                    }
                    
                     if($accion == 5) //Entrega a propietario
                    {
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => Session::get('user_login'),
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' =>  "Entrega vehículo  $placa a propietario  - " . $obser,
                                    'tipo' => 1
                                    )
                                ));
                    }

                    //Cambio de estado Vehículo
                    DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->update(array(
                                'id_estado' => "E04"
                            ));

                    DB::connection('sqlsrvCxParque')
                        ->table('tra_token_movil')
                        ->where('usuario',$placa)
                        ->update(array(
                                'id_estado' => "E04"
                            ));

                    $respuesta = 1;
                }
            
               DB::commit();
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
        }

        if($opcion == 19) //Save Novedades Incidencia
        {   
            $incidencia = $request->all()["incidencia"];
            $obser = $request->all()["obser"];

            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $incidencia,
                            'usuario' => Session::get('user_login'),
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => $obser,
                            'tipo' => 1
                            )
                        ));

            $respuesta = 1;            
        }

        if($opcion == 20) //Save Ingreso Taller Novedad
        {
            //Ingresa novedad
            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_novedad')
                    ->insert(array(
                        array(
                            'incidencia' => $request->all()['incidencia'],
                            'fecha_ingreso' => $this->fechaALong,
                            'observacion' => $request->all()['obser'],
                            'tiempo_estimado' => $request->all()['tiempo'],
                            'id_usuario_ingreso' => Session::get('user_login'),
                            'costo_ingreso' => $request->all()['costo']
                            )
                        ));

            //Cambio de estado incidencia
            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$request->all()['incidencia'])
                    ->update(array(
                            'id_estado' => "E04"
                            
                        ));

            //Consulta Placa y Taller
            $dat = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                        ->where('incidencia',$request->all()['incidencia'])
                        ->select('taller_asignado','placa')
                        ->get();

            //Agregar Vehículo a taller
            DB::connection('sqlsrvCxParque')
                    ->table('tra_vehiculo_taller')
                    ->insert(array(
                        array(
                            'idTaller' => $dat[0]->taller_asignado,
                            'placa' => $dat[0]->placa,
                            'fecha_ingreso' => $this->fechaALong
                            )
                        ));

            //Agrega acción
            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $request->all()['incidencia'],
                            'usuario' => Session::get('user_login'),
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => "Vehículo " . $dat[0]->placa . " ingreso a taller - " . $request->all()['obser'],
                            'tipo' => 1
                            )
                        ));

            $placa = DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia')
                            ->where('incidencia',$request->all()['incidencia'])
                            ->value("placa");

            //Cambio de estado Vehículo
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->where('placa',$placa)
                ->update(array(
                        'id_estado' => "E03"
                    ));

            DB::connection('sqlsrvCxParque')
                ->table('tra_token_movil')
                ->where('usuario',$placa)
                ->update(array(
                        'id_estado' => "E03"
                    ));
            $respuesta = 1;
        }

        if($opcion == 21) //Save Salida Taller
        {

            DB::beginTransaction();

            try
            {

              $kilo = $request->all()['km_salida'];

             //Ingresa novedad
                DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad')
                        ->where('incidencia',$request->all()['incidencia'])
                        ->update(
                            array(
                                'incidencia' => $request->all()['incidencia'],
                                'fecha_salida' => $this->fechaALong,
                                'observacion_salida' => $request->all()['obser'],
                                'id_usuario_salida' => Session::get('user_login'),
                                'costo_salida' => $request->all()['costo_salida']
                                )
                            );

                //Cambio de estado incidencia
                DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia')
                        ->where('incidencia',$request->all()['incidencia'])
                        ->update(array(
                                'id_estado' => "E05",
                                'km_proximo' => ($request->all()['km'] == "" ? 0 : $request->all()['km']),
                                'km' => $kilo
                            ));

                $placa = DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia')
                        ->where('incidencia',$request->all()['incidencia'])
                        ->value('placa');

                DB::connection('sqlsrvCxParque')
                    ->table('tra_vehiculo_odometro')
                    ->insert(array(
                        array(
                        'placa' => $placa,
                        'fecha' => $this->fechaShort,
                        'observaciones' => "ODOMETRO SALIDA DE TALLER",
                        'kilometraje' => $kilo,
                        'fecha_servidor' => $this->fechaALong,
                        'usuario' => Session::get('user_login')
                        )
                ));

                //Consulta Placa y Taller
                $dat = DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia')
                            ->where('incidencia',$request->all()['incidencia'])
                            ->select('taller_asignado','placa')
                            ->get();

                //Agrega acción
                DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_accion')
                        ->insert(array(
                            array(
                                'incidencia' => $request->all()['incidencia'],
                                'usuario' => Session::get('user_login'),
                                'fecha_servidor' => $this->fechaALong,
                                'observacion' => "Vehículo " . $dat[0]->placa . " salida de taller - " . $request->all()['obser'],
                                'tipo' => 1
                                )
                            ));

              DB::commit();
              return response()->json(1);
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json(0);
            }
        }

        if($opcion == 22) //Save finalizar
        {
            $inci = $request->all()['incidencia'];

            DB::connection('sqlsrvCxParque')
                ->table('tra_incidencia')
                ->where('incidencia',$inci)
                ->update(
                    array(
                        'id_estado' => 'E06',
                        'fecha_cierre' => $this->fechaALong,
                        'usuario_cierre' => Session::get('user_login')
                        )
                    );


            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->where('placa',$placa)
                ->update(array(
                        'id_estado' => "E03"
                    ));
                
            //Agrega acción
            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $inci,
                            'usuario' => Session::get('user_login'),
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => "Se ha cerrado la incidencia",
                            'tipo' => 1
                            )
                        ));
        }

        if($opcion == 23) //Save Costo y Fecha de salida de taller
        {
            $costo = $request->all()['costo'];
            $incidencia = $request->all()['incidencia'];
            $obser = $request->all()['obser'];
            $fecha = explode("/",$request->all()['fecha'])[2] . "-" . explode("/",$request->all()['fecha'])[1] . "-" . explode("/",$request->all()['fecha'])[0];

            //Existe Novedad
            $nove = DB::connection('sqlsrvCxParque')
                     ->table('tra_incidencia_novedad')
                     ->where('incidencia',$incidencia)
                     ->count();

            if($nove == 0)
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_novedad')
                    ->insert(array(
                        array(
                            'incidencia' => $incidencia
                            )
                        ));
            }
            else
            {
                $actulF = DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad')
                        ->where('incidencia',$incidencia)
                        ->value('fecha_aproxima_salida');

                $actulC = DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_novedad')
                            ->where('incidencia',$incidencia)
                            ->value('costo_ingreso');

                if(!($actulF == $fecha && $actulC  == $costo))
                {
                    DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_novedad_log')
                            ->insert(array(
                                array(
                                    'id_incidencia' => $incidencia,
                                    'tipo' => 1,
                                    'costo' => $actulC,
                                    'fecha_taller' => $actulF,
                                    'usuario' => Session::get('user_login'),
                                    'observacion' => $obser
                                    )
                                ));
                }
            }
            

            

            DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad')
                        ->where('incidencia',$incidencia)
                        ->update(array(
                                'costo_ingreso' => $costo,
                                'fecha_aproxima_salida' => $fecha
                            ));

            $respuesta = 1;
        }

        if($opcion == 24) //Save Costo y Fecha de salida de taller - SALIDA TALLER
        {
            $costo = $request->all()['costo'];
            $incidencia = $request->all()['incidencia'];
            $obser = $request->all()['obser'];

            $actulC = DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad')
                        ->where('incidencia',$incidencia)
                        ->value('costo_salida');

            if(!($actulC  == $costo))
            {
                DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad_log')
                        ->insert(array(
                            array(
                                'id_incidencia' => $incidencia,
                                'tipo' => 2,
                                'costo' => $actulC,
                                'usuario' => Session::get('user_login'),
                                    'observacion' => $obser
                                )
                            ));
            }

            DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad')
                        ->where('incidencia',$incidencia)
                        ->update(array(
                                'costo_salida' => $costo
                            ));

            $respuesta = 1;
        }

        if($opcion == 25) //Cancelar ingreso a taller
        {

            $incidencia = $request->all()["incidencia"];
            $obser = $request->all()["obser"];

           DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'id_estado' => 'E01',
                            'accion' => NULL,
                            'tecnico_asignado' => NULL,
                            'taller_asignado' => NULL,
                            'fecha_recibido' => NULL,
                            'fecha_aceptacion' => NULL,
                            'fecha_finalizacion' => NULL,
                            'primera_accion' => NULL,
                            'segunda_accion' => NULL
                        ));   

            //Agrega acción
            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $incidencia,
                            'usuario' => Session::get('user_login'),
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => "Centro de control cancela ingreso a taller -  Observación:" . $obser,
                            'tipo' => 1
                            )
                        ));

            //Elimina novedad
            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_novedad')
                    ->where('incidencia',$incidencia)
                    ->delete();

            $respuesta = 1;
        }

        if($opcion == 26) //Save finalizar
        {
            $inci = $request->all()['incidencia'];

            DB::connection('sqlsrvCxParque')
                ->table('tra_incidencia')
                ->where('incidencia',$inci)
                ->update(
                    array(
                        'id_estado' => 'E4',
                        'fecha_anulacion' => $this->fechaALong,
                        'usuario_anula_incidencia' => Session::get('user_login'),
                        'obser_anulacion' => $request->all()['obs'] 
                        )
                    );


            //Agrega acción
            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $inci,
                            'usuario' => Session::get('user_login'),
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => "Se ha anulado la incidencia " . $request->all()['obs'],
                            'tipo' => 1
                            )
                        ));
        }

        if($opcion == 27) //Save cambio de proyecto
        {
            $placa = $request->all()['placa'];
            $pry = $request->all()['pry'];
            $obser = $request->all()['obser'];
            $autoriza = $request->all()['autoriza'];

            
            DB::connection('sqlsrvCxParque')
                    ->table('tra_cambio_proyectos')
                    ->insert(array(
                        array(
                        'placa' => $placa,
                        'proyecto' => $pry,
                        'observacion' => $obser,
                        'usuario' => Session::get('user_login'),
                        'autoriza' => $autoriza
                        )
                    ));

            DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placa)
                    ->update(
                        array(
                        'id_proyecto' => $pry
                    ));

            $respuesta = 1;  
        }

        if($opcion == 28) //Save KM
        {
            $km = $request->all()['km'];
            $incidencia = $request->all()['incidencia'];
            $placa = $request->all()['placa'];

            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                        'km' => $km
                    ));

    
           DB::connection('sqlsrvCxParque')
                ->table('tra_vehiculo_odometro')
                ->insert(array(
                    array(
                    'placa' => $placa,
                    'fecha' => $this->fechaShort,
                    'observaciones' => "ODOMETRO VER INCIDENCIA DETALLE",
                    'kilometraje' => $km,
                    'fecha_servidor' => $this->fechaALong,
                    'usuario' => Session::get('user_login')
                    )
                ));

            $respuesta = 1;  
        }

        if($opcion == 29) //Save KM proximo
        {
            $km = $request->all()['km'];
            $incidencia = $request->all()['incidencia'];

            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                        'km_proximo' => $km
                    ));

            $respuesta = 1;  
        }

        if($opcion == 30) //Save incidencia visita
        {
            $incidencia = $request->all()['incidencia'];
            $fecha = $request->all()['fecha'];
            $obser = $request->all()['obser'];

            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_visita')
                    ->insert(
                        array(
                            array(
                                    'incidencia' => $incidencia,
                                    'fecha_visita' => $fecha,
                                    'observacion' => $obser,
                                    'usuario' => Session::get('user_login')
                                )
                    ));


            $respuesta = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_visita as visi')
                    ->join('CAMPRO.dbo.sis_usuarios as usua','usua.id_usuario','=','visi.usuario')
                    ->where('visi.incidencia',$incidencia)
                    ->orderBy('visi.fecha_servidor','desc')
                    ->get(['usua.propietario','visi.fecha_visita','visi.observacion']);
        }


        if($opcion == 31) //Save cambio de estado
        {
            $placa = $request->all()['placa'];
            $esta = $request->all()['esta'];
            $obser = $request->all()['obser'];

            DB::connection('sqlsrvCxParque')
                    ->table('tra_cambio_estado')
                    ->insert(array(
                        array(
                        'placa' => $placa,
                        'estado' => $esta,
                        'observacion' => $obser,
                        'usuario' => Session::get('user_login')
                        )
                    ));

            DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placa)
                    ->update(
                        array(
                        'id_estado' => $esta
                    ));

            $respuesta = 1; 
        }

        if($opcion == 32) //Save cambio de estado
        {
            $id = $request->all()['id'];
            $fecha = $request->all()['fecha'];

            $fechaActual = DB::connection('sqlsrvCxParque')
                            ->table('tra_cambio_proyectos')
                            ->where('id',$id)
                            ->value('fecha_servidor');

            $observacion = DB::connection('sqlsrvCxParque')
                            ->table('tra_cambio_proyectos')
                            ->where('id',$id)
                            ->value('observacion');

            $usuarioActual = DB::Table('sis_usuarios')
                                ->where('id_usuario',Session::get('user_login'))
                                ->value('propietario');

            $observacion = $observacion . " - ANEXO:  SE HACE CAMBIO DE LA FECHA: " . $fechaActual . " A " . $fecha . " - REALIZADO POR EL USUARIO: " . $usuarioActual . " EN LA FECHA: " . $this->fechaALong;

            DB::connection('sqlsrvCxParque')
                    ->table('tra_cambio_proyectos')
                    ->where('id',$id)
                    ->update(
                            array(
                                'fecha_servidor' => $fecha,
                                'observacion' => $observacion
                                )
                        );

            $respuesta = 1; 
        }

        if($opcion == 33) //Eliinar odometro
        {
            $id = $request->all()['id'];
            $placa = $request->all()['placa'];

            $kiloActual = DB::connection('sqlsrvCxParque')
                            ->table('tra_vehiculo_odometro')
                            ->where('id',$id)
                            ->get(['fecha','observaciones','kilometraje','usuario','tipo'])[0];
        
            $cadena = "INFORMACIÓN DEL REGISTRO ELIMINADO: Fecha captura:" . $kiloActual->fecha . " - Observación: " . $kiloActual->observaciones . " - Kilometraje: " . $kiloActual->kilometraje . " - Usuario: " . $kiloActual->usuario . " - Tipo: " . $kiloActual->tipo;

            self::saveLog('OPERA33',$placa,$cadena);

            DB::connection('sqlsrvCxParque')
                            ->table('tra_vehiculo_odometro')
                            ->where('id',$id)
                            ->delete();

            $respuesta = 1; 
        }

        return response()->json($respuesta);
    }

    //Web Services para consulta la información de transporte CAMPRO WEB
    public function consultaInformacionTransporte(Request $request)
    {
        $opcion = $request->all()["opc"];//
        $respuesta = 0;

        if($opcion == 1) //Consulta Datos Vehiculo
        {
            $placa = $request->all()["placa"];//
            
            $vehiculoData = DB::connection('sqlsrvCxParque')
                ->Table('tra_maestro as tbl1')
                ->leftJoin('tra_propietarios as tbl2','tbl1.id_propietario','=','tbl2.id_propietario')
                ->leftJoin('tra_vehiculo_galeria as tbl3','tbl3.placa','=','tbl1.placa')
                ->where('tbl1.placa',$placa)
                ->select('tbl1.id_ciudad','tbl1.id_tipo_vehiculo','tbl1.id_marca','tbl1.modelo','tbl1.color','tbl1.pasajeros'
                    ,'tbl1.linea','tbl1.cilindraje','tbl1.id_tipo_combustible','tbl1.id_transmision','tbl1.id_tipo_vinculo','tbl1.id_clase','tbl1.id_estado','tbl1.chasis'
                    ,'tbl1.motor','tbl1.gps','tbl1.propietario_gps','tbl1.id_proveedor_monitoreo','tbl1.serie_gps'
                    ,'tbl1.valor_contrato','tbl1.capacete','tbl1.portaescaleras','tbl1.caja_herramientas','tbl1.portapertiga'
                    ,'tbl1.id_propietario','tbl1.id_proyecto','tbl1.fecha_vinculo as fecha'
                    ,'tbl2.domicilio','tbl2.cedula','tbl2.telefonoFijo','tbl2.telefonoCel','tbl2.correo','tbl1.primer_mantenimiento',
                    'tbl3.ruta_imagen1','tbl3.ruta_imagen2','tbl3.ruta_imagen3','tbl3.ruta_imagen4','tbl1.id_tipo_vehiculo_cam','rutina_km as rutina')
                ->get();

            $conductor = "";
            $ultimoConductor = DB::connection('sqlsrvCxParque')
                ->table('tra_vehiculos_conductores')
                ->where('placa',$placa)
                ->select('id_persona')
                ->orderBy('fecha_hora','des')
                ->get();

            if(count($ultimoConductor) > 0)
            {
                $ultimoConductor = $ultimoConductor[0]->id_persona;
                $conductor = DB::table('rh_personas')
                    ->where('identificacion',$ultimoConductor)
                    ->select('identificacion','nombres','apellidos','telefono1','correo','domicilio')
                    ->get();
            }
            $respuesta = array($vehiculoData,$conductor);            
        }

        if($opcion == 2) //Consulta Log del documento del vehículo
        {
            $placa = $request->all()["placa"];
            $doc = $request->all()["doc"];

            $logDocu = DB::connection('sqlsrvCxParque')
                ->table('tra_maestro_documentos as tbl1')
                ->join('tra_maestro_clase_documentos as tbl3','tbl1.id_documento','=','tbl3.id_documento')
                ->join('tra_maestro as tbl4','tbl4.id_clase','=','tbl3.id_clase')
                ->join('tra_documento_vehiculo as tbl2','tbl1.id_documento','=','tbl2.id_documento')
                ->where('tbl2.placa',$placa)
                ->where('tbl4.placa',$placa)
                ->where('tbl1.id_documento',$doc)
                ->where('tbl2.actual','N')
                ->select('tbl1.id_documento','tbl1.nombre_documento','referencia','entidad',
                    'vencimiento','direccion_archivo')
                ->orderBy('tbl2.fecha_servidor','des')
                ->get();

            return response()->json(view('proyectos.transporte.tables.tblLogDocumentosVehiculo',
                array(
                    "logs" => $logDocu
                ))->render());
        }
        
        if($opcion == 3) //Consulta Usuarios
        {
            $nombre = $request->all()["nombre"];
            $ced = $request->all()["ced"];

            $conductores = DB::table('rh_personas')
                ->where('identificacion','LIKE',"%" . $ced. "%")
                ->where(DB::raw("(nombres + ' ' + apellidos)"),'LIKE',"%" . $nombre. "%")
                ->where('id_estado','EP03')
                ->select('identificacion','nombres','apellidos','telefono1','correo','domicilio')
                ->orderBy('nombres')
                ->get();

            return response()->json(view('proyectos.transporte.tables.tblConductoresVehiculos',
                array(
                    "conductores" => $conductores
                ))->render());
        }

        if($opcion == 4) //Consulta si ya realizo el primero Mantenimiento
        {
            $matri = $request->all()["matri"];//  
        }

        if($opcion == 5)// Listar documentos asociados
        {
            $idClase = $request->all()["idClase"];
            $respuesta =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro_clase_documentos')
                    ->where('id_clase',$idClase)
                    ->select('id_documento')
                    ->get();
        }

        if($opcion == 6)// Consulta Fotografias vehículo
        {
            $placa = $request->all()["placa"];
            $imagenes =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_vehiculo_galeria')
                    ->where('placa',$placa)
                    ->select('ruta_imagen1','ruta_imagen2','ruta_imagen3','ruta_imagen4')
                    ->get();
            $img1 = "";
            $img2 = "";
            $img3 = "";
            $img4 = "";

            if(count($imagenes) > 0)
            {
                if($imagenes[0]->ruta_imagen1 != null && $imagenes[0]->ruta_imagen1 != "" && $imagenes[0]->ruta_imagen1 != "0")
                {
                    $img1 = \Storage::disk('local')->get($imagenes[0]->ruta_imagen1);
                    $img1 = base64_encode($img1);
                }

                if($imagenes[0]->ruta_imagen2 != null && $imagenes[0]->ruta_imagen2 != "" && $imagenes[0]->ruta_imagen2 != "0")
                {
                    $img2 = \Storage::disk('local')->get($imagenes[0]->ruta_imagen2);
                    $img2 = base64_encode($img2);
                }

                if($imagenes[0]->ruta_imagen3 != null && $imagenes[0]->ruta_imagen3 != "" && $imagenes[0]->ruta_imagen3 != "0")
                {
                    $img3 = \Storage::disk('local')->get($imagenes[0]->ruta_imagen3);
                    $img3 = base64_encode($img3);
                }

                if($imagenes[0]->ruta_imagen4 != null && $imagenes[0]->ruta_imagen4 != "" && $imagenes[0]->ruta_imagen4 != "0")
                {
                    $img4 = \Storage::disk('local')->get($imagenes[0]->ruta_imagen4);
                    $img4 = base64_encode($img4);
                }
            }
            $respuesta = array($img1,$img2,$img3,$img4);
        }

        if($opcion == 7)// Consulta Componente Árbol de decisiones
        {
            $arbol = $request->all()["arbol"];
            $respuesta =
                DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->where('fila',2)
                    ->where('padre',$arbol)
                    ->orderBy('id')
                    ->select('descripcion', 'id')
                    ->get();
        }

        if($opcion == 8)// Consulta Componente Árbol de decisiones
        {
            $arbol = $request->all()["arbol"];
            $respuesta =
                DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->where('fila',3)
                    ->where('padre',$arbol)
                    ->orderBy('id')
                    ->select('descripcion', 'id')
                    ->get();
        }

        if($opcion == 9) //Consulta existencia Placa
        {
            $placa = $request->all()["placa"];
            $estado =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placa)
                    ->get(['id_estado']); 

            // Consulta del kilometraje del vehículo

            $odometroVehiculo = DB::connection('sqlsrvCxParque')
                                ->table('tra_vehiculo_odometro')
                                ->where('placa',$placa)
                                ->orderBy('fecha','DESC')
                                ->where('observaciones','not like','%chevy%')
                                ->get(['fecha','kilometraje']); 

            if(count($odometroVehiculo) > 0)
                $odometroVehiculo = $odometroVehiculo[0];

            if(count($estado) == 0)
                $respuesta = array(0,$odometroVehiculo);
            else
            {
                if($estado[0]->id_estado == "E02")
                    $respuesta = array(-1,$odometroVehiculo);
                else
                    $respuesta = array(1,$odometroVehiculo);
            }
            
        }

        if($opcion == 10) //Consulta acciones incidencias
        {
            $incidencia = $request->all()["incidencia"];
            $arr =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->select('id','usuario','fecha_servidor','observacion','tipo')
                    ->where('incidencia',$incidencia)
                    ->get(); 

            $arregloD = [];
            foreach ($arr as $key => $value) {
                if($value->tipo == 1)
                {
                    array_push($arregloD,[$value,
                     DB::table('sis_usuarios')
                    ->where('id_usuario',$value->usuario)
                    ->value('propietario')]);    
                }

                if($value->tipo == 2) //Técnico
                {
                    array_push($arregloD,[$value,
                     DB::table('sis_usuarios')
                    ->where('id_usuario',$value->usuario)
                    ->value('propietario')]);    
                }
                
                if($value->tipo == 3) //Conductor
                {
                    array_push($arregloD,[$value,
                     $value->usuario]);    
                }
            }

            $respuesta = $arregloD;
        }

        if($opcion == 11) //Consulta respuestas
        {
            $arbol = $request->all()["arbol"];
            $respuesta =
                DB::connection('sqlsrvCxParque')
                    ->table('gop_arbol_decision')
                    ->where('fila',4)
                    ->where('padre',$arbol)
                    ->orderBy('id')
                    ->select('descripcion', 'id','inhabilita','tiempo_estimado','asistencia_sitio','desplazamiento_sin_grua','desplazamiento_sede')
                    ->get(); 
        }

        if($opcion == 12)//Consulta Ingreso a taller
        {
            $respuesta =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('tra_incidencia.id_estado','E03')
                    ->join('tra_talleres_gps','tra_talleres_gps.id','=','tra_incidencia.taller_asignado')
                    ->join('tra_maestro as tbl3','tbl3.placa','=','tra_incidencia.placa')
                    ->leftJoin("tra_contratantes",'tra_contratantes.id','=','tbl3.id_proyecto')
                    ->select('incidencia', 'tra_incidencia.fecha_servidor','observacion','tra_incidencia.placa','nombre_proveedor',
                        'tra_incidencia.direccion','tra_contratantes.nombre as nombreP','tiempo_estimado','km')
                    ->get(); 
        }

        if($opcion == 13)//Consulta Salida de taller
        {
            $respuesta =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_novedad')
                    ->whereNull('fecha_salida')
                    ->where('entrega_operacion',0)
                    ->join('tra_incidencia','tra_incidencia_novedad.incidencia','=','tra_incidencia.incidencia')
                    ->join('tra_talleres_gps','tra_talleres_gps.id','=','tra_incidencia.taller_asignado')
                    ->join('CAMPRO.dbo.sis_usuarios as tbl1','tbl1.id_usuario','=','id_usuario_ingreso')
                    ->select('tbl1.propietario','tra_incidencia.incidencia', 'id_novedad','fecha_ingreso',
                        'tra_incidencia_novedad.observacion','tra_incidencia.observacion as observacion1',
                        'tra_incidencia_novedad.tiempo_estimado','id_usuario_ingreso','nombre_proveedor',
                        'tra_incidencia.respuesta',
                        'tra_incidencia.direccion','tra_incidencia.placa','fecha_servidor','costo_ingreso as costo','km')
                    ->get(); 
        }

        if($opcion == 14)//Consulta Entrega a operación
        {
            $respuesta =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_novedad')
                    ->whereNotNull('fecha_salida')
                    ->where('entrega_operacion',0)
                    ->join('tra_incidencia','tra_incidencia_novedad.incidencia','=','tra_incidencia.incidencia')
                    ->join('tra_talleres_gps','tra_talleres_gps.id','=','tra_incidencia.taller_asignado')
                    ->join('CAMPRO.dbo.sis_usuarios as tbl1','tbl1.id_usuario','=','id_usuario_ingreso')
                    ->join('CAMPRO.dbo.sis_usuarios as tbl2','tbl2.id_usuario','=','id_usuario_ingreso')
                    ->select('tbl1.propietario','tra_incidencia.incidencia', 'id_novedad','fecha_ingreso',
                        'tra_incidencia_novedad.observacion','tra_incidencia.observacion as observacion1',
                        'tra_incidencia_novedad.tiempo_estimado','id_usuario_ingreso','nombre_proveedor',
                        'tra_incidencia.direccion','tra_incidencia.placa','fecha_servidor',
                        'fecha_salida','observacion_salida','id_usuario_salida','tbl2.propietario as propietario2'
                        ,'costo_ingreso','costo_salida','km','km_proximo')
                    ->get(); 
        }

        if($opcion == 15) //Consulta datos web services Google MAPS Marcadores
        {
            $filter = $request->all()["filter"];

            $taller = ($filter["taller"] == "-1" ? '' : $filter["taller"]);
            $base = ($filter["base"] == "-1" ? '' : $filter["base"]); ;

            $inciEstado = ($filter["estaI"] == "-1" ? '' : $filter["estaI"]); 
            $inci = $filter["inci"];

            $estadoT = ($filter["estaT"] == "-1" ? '' : $filter["estaT"]);
            $tecnico = $filter["tecnico"];

            $estadoC = ($filter["estaC"] == "-1" ? '' : $filter["estaC"]); 
            $conduc = $filter["conduc"];
            
            $talleres = [];
            if($filter["taller"] != "-2" && $filter["base"] != "-2")
            {
                $cad = "EXEC sp_tra_consulta_capacidad_taller '" . ($taller == '' ? $base : $taller) . "'";
                $talleres = DB::connection('sqlsrvCxParque')
                        ->select("SET NOCOUNT ON;" . $cad);
            }

            $incidenciaG = [];
            if($filter["estaI"] != "-2")
            {
                $cad = "EXEC sp_tra_consulta_incidencias '$inciEstado','$inci'";
                $incidenciaG = DB::connection('sqlsrvCxParque')
                        ->select("SET NOCOUNT ON;" . $cad);
            }

            $tecnicos = [];
            if($filter["estaT"] != "-2")
            {
                $cadTecnicos = "EXEC sp_tra_consulta_tecnicos '$estadoT','$tecnico'";
                $tecnicos = DB::connection('sqlsrvCxParque')
                        ->select("SET NOCOUNT ON;" . $cadTecnicos);
            }

            $vehiculos = [];
            if($filter["estaC"] != "-2")
            {
                $cadVehiculos = "EXEC sp_tra_consulta_vehiculos '$estadoC','$conduc'";
                $vehiculos = DB::connection('sqlsrvCxParque')
                    ->select("SET NOCOUNT ON;" . $cadVehiculos);    
            }
            
            
            $respuesta = [$talleres,$incidenciaG,$vehiculos,$tecnicos];

        }

        if($opcion == 16) //Consulta Log Novedades Costos
        {
            $incidencia = $request->all()["incidencia"];
            $tipo = $request->all()["tipo"];

            $costoPrimero = DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_novedad_log')
                            ->where('id_incidencia',$incidencia)
                            ->where('tipo',$tipo)
                            ->where('costo','<>',0)
                            ->orderBy('fecha_servidor','asc')
                            ->value('costo');


            $respuesta = DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad_log')
                        ->join('CAMPRO.dbo.sis_usuarios as tbl1','tbl1.id_usuario','=','usuario')
                        ->where('id_incidencia',$incidencia)
                        ->where('tipo',$tipo)
                        ->select('costo','fecha_taller','fecha_servidor','tbl1.propietario','observacion')
                        ->orderBy('fecha_servidor')
                        ->get();

            $cuentoSubio  = 0;
            $cuentoSubioPor  = 0;
            $diasTotales = "";
            if($costoPrimero != ""  && $costoPrimero != null)
            {
                if($tipo == "1")
                {
                    $costoActual = floatval(
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_novedad')
                            ->where('incidencia',$incidencia)
                            ->value('costo_ingreso')
                            );

                    $contoPrimero =  floatval($costoPrimero);

                    $cuentoSubio = $costoActual - $contoPrimero;
                    $cuentoSubioPor = ($cuentoSubio * 100) / $contoPrimero;


                    $fechaUltima = DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad')
                        ->where('incidencia',$incidencia)
                        ->value('fecha_aproxima_salida');

                    $fechaPrimera = DB::connection('sqlsrvCxParque')
                                    ->table('tra_incidencia_novedad_log')
                                    ->where('id_incidencia',$incidencia)
                                    ->where('tipo',$tipo)
                                    ->where('costo','<>',0)
                                    ->orderBy('fecha_servidor','asc')
                                    ->value('fecha_taller');

                    $datetime1 = new \DateTime($fechaPrimera);
                    $datetime2 = new \DateTime($fechaUltima);
                    $interval = $datetime1->diff($datetime2);
                    $diasTotales  = abs(intval($interval->format('%R%a')));


                }else
                {
                    $costoActual = floatval(
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_novedad')
                            ->where('incidencia',$incidencia)
                            ->value('costo_salida')
                            );
                    $contoPrimero =  floatval($costoPrimero);
                    $cuentoSubio = $costoActual - $contoPrimero;
                    $cuentoSubioPor = ($cuentoSubio * 100) / $contoPrimero;
                    $diasTotales = "";
                }
            }

            $respuesta = array($respuesta,$cuentoSubio,$cuentoSubioPor,$diasTotales);
        }

        if($opcion == 17) //Consulta Entregra Operación
        {
            $incidencia = $request->all()["incidencia"];
            $persona = $request->all()["persona"];

            $consulta = " 
            
            SELECT tbl1.tipo_form,tbl1.tipo_control,tbl1.id_pregunta,tbl1.res,
                            tbl1.cantidad,tbl1.fecha,tbl1.tipo_respuesta_user,tbl2.item_num
                            ,tbl2.descrip_pregunta
            FROM gop_formulario_respuesta AS tbl1 
            INNER JOIN gop_formularios_creacion as tbl2 ON tbl1.id_pregunta = tbl2.id_pregunta
                AND tbl1.tipo_form = tbl2.tipo_formulario
            WHERE tbl1.incidencia = '" . $incidencia . "' AND tbl1.usuario = '" . $persona . "'";


            $respuesta = DB::select($consulta);
        }
        
        if($opcion == 18) //Consulta Novedad Cambio Proyecto
        {
            $placa = $request->all()["placa"];

            $consulta = " 
            
            SELECT tbl2.nombre,tbl1.observacion,tbl1.fecha_servidor,
                tbl3.propietario,autoriza,tbl1.id
            FROM tra_cambio_proyectos AS tbl1 
            INNER JOIN tra_contratantes as tbl2 ON tbl1.proyecto = tbl2.id
            INNER JOIN CAMPRO.dbo.sis_usuarios as tbl3 ON tbl1.usuario = tbl3.id_usuario
            WHERE tbl1.placa = '" . $placa . "' ORDER BY tbl1.fecha_servidor desc" ;


            $respuesta = DB::connection('sqlsrvCxParque')->select($consulta);
        }


        if($opcion == 19) //Consulta Novedad Cambio Proyecto
        {
            $placa = $request->all()["placa"];

            $consulta = " 
            
            SELECT tbl2.nombre,tbl1.observacion,tbl1.fecha_servidor,
                tbl3.propietario
            FROM tra_cambio_estado AS tbl1 
            INNER JOIN tra_estados as tbl2 ON tbl1.estado = tbl2.id_estado
            INNER JOIN CAMPRO.dbo.sis_usuarios as tbl3 ON tbl1.usuario = tbl3.id_usuario
            WHERE tbl1.placa = '" . $placa . "' ORDER BY tbl1.fecha_servidor desc" ;


            $respuesta = DB::connection('sqlsrvCxParque')->select($consulta);
        }


        if($opcion == 20) //Consulta Log del vehículo
        {
            $placa = $request->all()["placa"];//  

            $respuesta =  DB::connection('sqlsrvCxParque')
                            ->table('tra_log as tra')
                            ->join('tra_maestro_log as log','log.id_log','=','tra.id_log')
                            ->join('CAMPRO.dbo.sis_usuarios as sis','sis.id_usuario','=','tra.id_usuario')
                            ->where('campro_valor',$placa)
                            ->whereIn('tra.id_log',['OPERA21','OPERA22','OPERA23','OPERA24','OPERA25','OPERA33'])
                            ->orderBy('fecha','desc')
                            ->get(['fecha','log.descripcion','tra.descripcion as obser','sis.propietario']);


        }

        return response()->json($respuesta);
    }
    /*****************FIN WEB SERVICES CAMPRO WEB**************/
    /**********************************************************/




    /***************************************************/
    /**************WEB SERVICES APLICACIÓN MÓVIL********/

    //Funciones utilizadas por las aplicaciones móviles para realizar la consulta de información
    public function consultaWebServicesMovil(Request $request)
    {
        // http://104.36.166.72:8084/

        //Rutas -> consultarmoviltransporte
        
        $opcion = $request->all()["opc"];
        $respuesta = 0;

        if($opcion == 1)//Login
        {
            $usuario = $request->all()["usuario"];
            $pasword = $request->all()["pass"];
            $pro = $request->all()["pro"];

            $usuario = DB::table('rh_personas')
                ->where('identificacion',$usuario)
                ->where('id_estado', "EP03") 
                ->select('identificacion','nombres','apellidos') 
                ->get();

            if(count($usuario)>0)
                $respuesta=array(1,$usuario[0]->nombres . " " . $usuario[0]->apellidos);
            else
                $respuesta=array(0);           
        }
        if($opcion == 2)//Guarda odometro 
        {
            $placa = $request->all()["placa"];
            $fecha = $request->all()["fecha"];
            $kilometraje = intval($request->all()["kilometraje"]);

            //Consulta los últimos 20 records de Odometros
            $odometerArray = DB::connection('sqlsrvCxParque')
                            ->table('tra_vehiculo_odometro')
                            ->where('placa',$placa)
                            ->orderBy('fecha','desc')
                            ->select(DB::raw("TOP(1)fecha"),'kilometraje')
                            ->get();

            /*$recorrido = 0;
            $cantDia = 0;
            $ultimaFechaRecorrido = "";
            for ($j=(count($odometerArray) - 1); $j > 0; $j--) { 
                $kilo = $odometerArray[$j]->kilometraje;
                $kiloSigui = $odometerArray[$j - 1]->kilometraje;
                $recorridoDia = floatval($kiloSigui)  - floatval($kilo);
                $recorrido += $recorridoDia;
                $cantDia++;
                //var_dump("RECORRIDO DÍA $cantDia:  " . $recorridoDia . " -> Fecha" . $odometerArray[$j]->fecha);
                $ultimaFechaRecorrido = $odometerArray[$j]->fecha;
            }

            if(count($odometerArray) > 0)
            {
                $cantDia++;
                //var_dump("RECORRIDO DÍA $cantDia:  " . $odometerArray[0]->kilometraje  . " -> Fecha" . $odometerArray[$j]->fecha);
                $recorrido +=  floatval($odometerArray[0]->kilometraje); 
                $ultimaFechaRecorrido = $odometerArray[0]->fecha;
            }

            //var_dump("DIAS TOTALES RECORRIDOS:  " . $cantDia);

            //var_dump("ULTIMA FECHA RECORRIDO:  " . $ultimaFechaRecorrido);
            //var_dump("FECHA RECORRIDO ACTUAL:  " . $fecha);

            $kiloActual = (count($odometerArray) == 0 ? 0 : $odometerArray[0]->kilometraje);
            //var_dump("ÚLTIMO KILOMETRAJE  " . $kiloActual);

            $datetime1 = new \DateTime($ultimaFechaRecorrido);
            $datetime2 = new \DateTime($fecha);
            $interval = $datetime2->diff($datetime1);
            $dias =  intval($interval->format('%R%a'));
            $dias = ($dias < 0 ? $dias * -1 : $dias) - 1;
            //var_dump("DIAS PASADOS DESDE ULTIMO RECORRIDO A ACTUAL -  SIN CONTAR EL DÏA ACTUAL:  " . $dias . " DíAS");            
            $promedio = $recorrido/($cantDia == 0 ? 1 : $cantDia );
            $sumaPromedio = 0;
            //var_dump("PROMEDIO RECORRIDO:  " . $promedio);

            if($dias > 0 )
            {
                //var_dump("POR LOS :  " . $dias . " DíAS RECORRIDO, MÁS O MENOS TUVO QUE HABER RECORRIDO: " . ($promedio * $dias));            
                $sumaPromedio = $promedio * $dias;
                $promedio = $promedio + $sumaPromedio;
                //var_dump("PROMEDIO RECORRIDO + DÍAS EXTRAS:  " . $promedio);                
            }
            //var_dump("PROMEDIO RECORRIDO + 50%:  " . $promedio * 0.5);
            //var_dump("A LO MAXIMO PUEDE RECORRER  " . (($promedio * 0.5) + $promedio));

            $kiloMaximo =  (count($odometerArray) == 0 ? 100000000000 :  (($promedio * 0.5) + $promedio));
            
            //var_dump("KILOMETRA QUE PUEDE INGRESAR ENTRE  "  .  $promedio . " Y ". $kiloMaximo);

            //var_dump("KILOMETRAJE A INGRESAR " . $kilometraje);


            $consultaPlaca = DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->where('placa',$placa)
                ->select('placa')
                ->get();

            $consultaKilometraje = DB::connection('sqlsrvCxParque')
                ->table('tra_vehiculo_odometro')
                ->where('placa',$placa)
                ->orderBy("id","desc")
                ->select('kilometraje')
                ->get();

            if(count($consultaPlaca) == 0){
                $respuesta=2;//Placa no encontrada
                return response()->json($respuesta);
            }

            if($kilometraje >= $kiloMaximo)
            {
                $respuesta=3;//Kilometraje debe ser mayor
                return response()->json($respuesta);
            }

            if($kilometraje <= $kiloActual)
            {
                $respuesta=3;//Kilometraje debe ser mayor
                return response()->json($respuesta);
            }

            

            DB::connection('sqlsrvCxParque')
             ->table('tra_vehiculo_odometro')
             ->insert(array(
                array(
                    "placa" => $placa,
                    "fecha_servidor" => $this->fechaALong,
                    "fecha" => $fecha,
                    "kilometraje" => $kilometraje,
                    "observaciones" => (isset($observacion) ? $observacion : "ODOMETRO CAPTURA MANUAL ")
                    )
                ));
            */
        
            $consultaPlaca = DB::connection('sqlsrvCxParque')
                ->table('tra_maestro')
                ->where('placa',$placa)
                ->select('placa')
                ->get();

            if(count($consultaPlaca) == 0){
                $respuesta=2;//Placa no encontrada
                return response()->json($respuesta);
            }

            $kiloActual = 0;
            if(count($odometerArray) > 0)
                $kiloActual = $odometerArray[0]->kilometraje;

            if($kilometraje <= $kiloActual)
            {
                $respuesta=3;//Kilometraje debe ser mayor
                return response()->json($respuesta);
            }

            DB::connection('sqlsrvCxParque')
             ->table('tra_vehiculo_odometro')
             ->insert(array(
                array(
                    "placa" => $placa,
                    "fecha_servidor" => $this->fechaALong,
                    "fecha" => $fecha,
                    "kilometraje" => $kilometraje,
                    "observaciones" => (isset($observacion) ? $observacion : "ODOMETRO CAPTURA MANUAL ")
                    )
                ));


            $respuesta=1;//Guarda

            self::saveLog("OPERA020",$placa,"PLACA: ".$placa." KILOMETRAJE: ".$kilometraje);                 
        }
        if($opcion == 3)//Consulta placa existente
        {
            $placa = $request->all()["placa"];
            $pro = $request->all()["pro"];
            $usuario = $request->all()["usuario"];

            $userCont = DB::table('rh_personas')
                ->where('identificacion',$usuario)
                ->where('id_estado', "EP03") 
                ->count();

            if($userCont == 0)
            {
                if(isset($request->all()["dato"]))
                    $respuesta = array(-1,$this->fechaShort,"","","","");
                else    
                    $respuesta = -1;
            }
            else
            {
                $cont = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placa)
                    ->count();

                $tipoVeh = DB::connection('sqlsrvCxParque')
                            ->table('tra_maestro')
                            ->where('placa',$placa)
                            ->value('id_tipo_vehiculo');

                $tipoVehDes = DB::connection('sqlsrvCxParque')
                                ->table( 'tra_tipo_vehiculo')
                                ->where('id_tipo_vehiculo',$tipoVeh)
                                ->value('nombre');

                $nombreUser = DB::table('rh_personas')
                                ->where('identificacion',$usuario)
                                ->get(['nombres','apellidos'])[0];
                

                if(isset($request->all()["dato"]))
                    $respuesta = array($cont,$this->fechaShort,$tipoVehDes,$tipoVeh,$usuario,$nombreUser->nombres . ' ' . $nombreUser->apellidos);
                else
                    $respuesta = $cont;
            }
        }
        if($opcion == 4) //Consulta Maestro App Conductor
        {
            $tipoInci = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_incidencia_tipo')
                        ->select('id','nombre')
                        ->where('nombre','not like',"MANTENIMIENTO PREVENTIVO")
                        ->where('nombre','not like',"%apa%")
                        ->where('nombre','not like',"%app%")
                        ->orderBy('nombre')
                        ->get();


            $tipoInciNivel3 = DB::connection('sqlsrvCxParque')
                        ->table( 'gop_arbol_decision')
                        ->select('descripcion')
                        ->where('fila',3)
                        ->where('descripcion','not like',"MANTENIMIENTO PREVENTIVO")
                        ->where('descripcion','not like',"%apa%")
                        ->where('descripcion','not like',"%app%")
                        ->orderBy('descripcion')
                        ->groupBy('descripcion')
                        ->get();

            $cad = "select arbol1.id,arbol1.descripcion,
                    (select arbol2.descripcion FROM gop_arbol_decision as arbol2 where arbol2.padre = (arbol1.id + 1)) as hijo
                    from gop_arbol_decision as arbol1
                    where arbol1.fila = 1
                    ORDER BY arbol1.descripcion";
            
            $tipoInciHijos = DB::connection('sqlsrvCxParque')
                ->select( $cad);



            $taller = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_talleres_gps')
                        ->select('id','nombre_proveedor as nombre')
                        ->where('tipo',1)
                        ->orderBy('nombre_proveedor')
                        ->get();

            $formulario = DB::table( 'gop_formularios_creacion')
                        ->select('tipo_formulario as ti','item_num as item','id_pregunta as id','descrip_pregunta as des','obligatorio as obli','tipo_control as tip')
                        ->whereIn('tipo_formulario',[3,4,5])
                        ->orderBy('tipo_formulario')
                        ->orderBy('id_pregunta')
                        ->get();

            $arreglo = [];
            array_push($arreglo, array($tipoInci,[],$taller,$formulario,$tipoInciNivel3,$tipoInciHijos));
            $respuesta = $arreglo;
        }
        if($opcion == 5)//Consulta Incidencias por supervisor
        {
            // sp_tra_consulta_incidencia_tecnico
            $super = $request->all()["super"];
            $cad = "EXEC sp_tra_consulta_incidencia_tecnico '" . $super . "'";
            
            $incidencias = DB::connection('sqlsrvCxParque')
                ->select("SET NOCOUNT ON;" . $cad);

            return response()->json($incidencias);
        }
        if($opcion == 6) //Consulta Acciones Aplicaciones móviles
        {
            $incidencia = $request->all()["incidencia"];
            $arr =
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->select('id','usuario','fecha_servidor','observacion','tipo')
                    ->where('incidencia',$incidencia)
                    ->get(); 

            $arregloD = [];
            foreach ($arr as $key => $value) {
                if($value->tipo == 1) //Técnico
                {
                    array_push($arregloD,[$value,
                     DB::table('sis_usuarios')
                    ->where('id_usuario',$value->usuario)
                    ->value('propietario')]);    
                }

                if($value->tipo == 2) //Conductor
                {
                    $conductor = DB::table('rh_personas')
                                ->where('identificacion',$value->usuario)
                                ->get(["nombres","apellidos"])[0];

                    array_push($arregloD,[$value,$conductor->nombres . " " . $conductor->apellidos]);    
                }
                
                if($value->tipo == 3) //Técnico
                {
                    $conductor = DB::table('rh_personas')
                                ->where('identificacion',$value->usuario)
                                ->get(["nombres","apellidos"])[0];

                    array_push($arregloD,[$value,$conductor->nombres . " " . $conductor->apellidos]);    
                }

            }

            $respuesta = $arregloD;
        }
        if($opcion == 7) //Consulta Datos Incidencia
        {
            $incidencia = $request->all()["incidencia"];
            $respuesta = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_incidencia as tbl1')
                        ->where('incidencia',$incidencia)
                        ->join('tra_incidencia_tipo as tbl2','tbl1.tipo_incidencia','=','tbl2.id')
                        ->select('tbl2.nombre as nombreTipo', 'tbl1.tipo_incidencia','incidencia','nombre','fecha_servidor','observacion','coordenadas','placa','fecha_asignacion as fecha')
                        ->orderBy('incidencia')
                        ->get()[0];
        }

        if($opcion == 8) //Consulta Incidencias por cerrar - Entrega operación
        {
             // sp_tra_consulta_incidencia_tecnico
            $placa = $request->all()["placa"]; 

            $cont = DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placa)
                    ->count();

            if($cont == 0)
                return response()->json("-1");

            $cad = "EXEC sp_tra_consulta_incidencia_entrega_operacion '" . $placa . "'";
            
            $incidencias = DB::connection('sqlsrvCxParque')
                ->select("SET NOCOUNT ON;" . $cad);

            return response()->json($incidencias);
        }

        if($opcion == 9) //Consulta respuesta formularios
        {
            //$placa = $request->all()["placa"]; 
            $incidencia = $request->all()["incidencia"]; 
            $form = $request->all()["form"]; 

            $respuesta = DB::table('gop_formulario_respuesta')
                        ->where('incidencia',$incidencia)
                        ->where('tipo_form',$form)
                        ->where('tipo_respuesta_user',0)
                        ->get(['tipo_control','id_pregunta','res','cantidad','fecha']);
        }

        if($opcion == 10) //Consulta Maestro App Inventario
        {
            $tipoVeh = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_tipo_vehiculo')
                        ->select('id_tipo_vehiculo as id','nombre as des')
                        ->orderBy('nombre')
                        ->get();

            $estado = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_estados')
                        ->select('id_estado as id','nombre as des')
                        ->get();

            $pro = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_contratantes')
                        ->select('id','nombre as des')
                        ->get();

            $arreglo = [];
            array_push($arreglo, array($tipoVeh,$estado,$pro));
            $respuesta = $arreglo;
        }

        if($opcion == 11) //Consulta Vehiculo Inventario
        {
            $placa = $request->all()["placa"]; 

           if(DB::connection('sqlsrvCxParque')
                        ->table( 'tra_maestro')
                        ->where('placa','LIKE','%' . $placa . '%')
                        ->count() == 0)
           {
                return response()->json(-1);
           }

            $veh = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_maestro as tbl1')
                        ->leftJoin('CAMPRO.dbo.rh_personas as tbl2','tbl2.identificacion','=','usuario_ultima_mod')
                        ->where('placa','LIKE','%' . $placa . '%')
                        ->get(['id_tipo_vehiculo as tipo','tbl1.id_estado as estado','tbl1.id_proyecto as proy','fecha_ultima_mod as fecha',DB::raw("(tbl2.nombres + ' ' + tbl2.apellidos) as users")])[0];

            $km = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_vehiculo_odometro')
                        ->where('placa','LIKE','%' . $placa . '%')
                        ->orderBy('fecha_servidor','desc')
                        ->get(['kilometraje','fecha','fecha_servidor']);

            if(count($km) == 0)
                $km = 0;
            else
                $km = $km[0];

            $respuesta = array("veh" => $veh,
                                "km" => $km);
        }


        if($opcion == 12) //Consulta Inventario Proyectos
        {
            $tipo = (isset( $request->all()["id_tipo"]) ?  $request->all()["id_tipo"] : '');
            $estado = (isset( $request->all()["id_estado"]) ?  $request->all()["id_estado"] : '');
            $proyecto = (isset( $request->all()["id_proyecto"]) ?  $request->all()["id_proyecto"] : '');

            $cadAux = "";
            $acceso = 0;
            if($tipo != "" || $estado != "" || $proyecto != "") 
                $cadAux = " WHERE ";


            if($tipo != "")
            {
                $cadAux .= " tipo.id_tipo_vehiculo = $tipo";
                $acceso = 1;
            }

            if($estado != "")
            {
                if($acceso == 0)
                {
                    $cadAux .= " esta.id_estado = '$estado'";
                    $acceso = 1;
                }
                else
                {
                    $cadAux .= " AND esta.id_estado = '$estado'";
                }
            }

            if($proyecto != "")
            {
                if($acceso == 0)
                {
                    $cadAux .= " pry.id = $proyecto";
                    $acceso = 1;
                }
                else
                {
                    $cadAux .= " AND pry.id = $proyecto";
                }
            }

            $consulta = "             
            SELECT pry.nombre as proyecto,count(pry.nombre) as cantidadPry,tipo.nombre as tipoV,count(tipo.nombre) as cantidadTipo
            ,esta.nombre as estado,count(esta.nombre) as cantidadEstado,veh.id_proyecto,tipo.id_tipo_vehiculo,esta.id_estado from 
            tra_maestro as veh INNER JOIN tra_contratantes as pry ON veh.id_proyecto = pry.id
            INNER JOIN tra_tipo_vehiculo as tipo ON veh.id_tipo_vehiculo = tipo.id_tipo_vehiculo
            INNER JOIN tra_estados as esta ON veh.id_estado = esta.id_estado $cadAux 
            GROUP BY pry.nombre,tipo.nombre,esta.nombre,veh.id_proyecto,tipo.id_tipo_vehiculo,esta.id_estado
            ORDER BY pry.nombre,tipo.nombre,esta.nombre
            ";

            $respuesta = DB::connection('sqlsrvCxParque')->select($consulta);

            $consulta = "             
            SELECT veh.placa,veh.id_proyecto,tipo.id_tipo_vehiculo,esta.id_estado from 
            tra_maestro as veh INNER JOIN tra_contratantes as pry ON veh.id_proyecto = pry.id
            INNER JOIN tra_tipo_vehiculo as tipo ON veh.id_tipo_vehiculo = tipo.id_tipo_vehiculo
            INNER JOIN tra_estados as esta ON veh.id_estado = esta.id_estado $cadAux 
            GROUP BY veh.id_proyecto,tipo.id_tipo_vehiculo,esta.id_estado,veh.placa
            ";

            $respuesta1 = DB::connection('sqlsrvCxParque')->select($consulta);


            $respuesta = array("data" => $respuesta,
                                "detalle" => $respuesta1 );
        }


        return response()->json($respuesta);
    }

    //Función utilizada por las aplicaciones móviles para realizar el guardado o actualización de la información
    public function saveWebServicesMovil(Request $request)
    {
       $opcion = $request->all()["opc"]; 
       $respuesta = 0;

       if($opcion == 1) // Save Token Móvil
       {
            $token = $request->all()["token"];
            $super = $request->all()["super"];
            $coor = $request->all()["coor"];
            //$pre = $request->all()["pre"];
            $tipo = 1;
            $conductor = "";
            if(isset($request->all()["conductor"]))
            {
                $conductor = $request->all()["conductor"];
                $tipo = 2;
            }

            $latitude = explode(",",$coor)[0];
            $longitud = explode(",",$coor)[1];

            DB::connection('sqlsrvCxParque')
                    ->table('tra_gps_track')
                    ->insert(array(
                            array(
                                'prefijo' => "apu",
                                'latitud' => $latitude,
                                'longitud' => $longitud,
                                'fecha' => explode(" ",$this->fechaALong)[0],
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'usuario_movil' => $super
                                )
                        ));

            
            $cont = DB::connection('sqlsrvCxParque')
                ->table('tra_token_movil')
                ->where('usuario',$super)
                ->count();

            if($cont == 0)
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_token_movil')
                    ->insert(array(
                            array(
                                'token_movil' => $token,
                                'usuario' => $super,
                                'id_estado' => 'E01',
                                'tipo_usuario' => $tipo,
                                "conductor" => $conductor
                                )
                        ));
            }
            else
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_token_movil')
                    ->where('usuario',$super)
                    ->update(array(
                            'token_movil' => $token,
                            'id_estado' => 'E01',
                            "conductor" => $conductor
                        ));
            }
            
            $respuesta = 1;
       }

       if($opcion == 2) //Save incidencia Conductor
       {
            $tipo = $request->all()["tipo"];
            $placa = $request->all()["placa"];
            $coor = $request->all()["coor"];
            $obser = $request->all()["obser"];
            DB::beginTransaction();
            try
            {
                $inci = self::generaConsecutivo("ID_INCIDENCIA");
                if(isset($request->all()["tecnico"]))
                {  

                    $exis = DB::connection('sqlsrvCxParque')
                                ->table('tra_maestro')
                                ->where('placa',$placa)
                                ->count();

                    if($exis == 0)
                        return response()->json(-1);

                    $usuario = $request->all()["usuario"];
                    
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
                                    'observacion' => $obser,
                                    'coordenadas' => $coor,
                                    'id_estado' => "E02",
                                    'accion' => 1,
                                    'tecnico_asignado' => $usuario,
                                    'fecha_asignacion' => $this->fechaALong,
                                    'fecha_cliente' => $this->fechaALong,
                                    'km' => $request->all()["kilometraje"],
                                    'usuario_crea' =>$usuario,
                                    'creacion' => 1,
                                    "primera_accion" => 1
                                    )
                            ));
                    
                     $token = DB::connection('sqlsrvCxParque')
                            ->table('tra_token_movil')
                            ->where('usuario',$usuario)
                            ->value('token_movil');

                    if($token != "")
                    {
                        $title = "Asignación de incidencia";
                        $body = "Se le ha asignado la incidencia # $inci";
                        $sonido = "default";
                        $icon = "fcm_push_icon";
                        $otro = "";
                        $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$usuario);
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $inci,
                                    'usuario' => $usuario,
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' =>  " Técnico " . $usuario . " crea incidencia y se dirige al sitio - " . $obser,
                                    'tipo' => 3
                                    )
                                ));

                    }

                }
                else
                {

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

                    $conductor = $request->all()["conductor"];
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
                                    'observacion' => $obser,
                                    'coordenadas' => $coor,
                                    'id_estado' => "E01",
                                    'usuario_crea' =>$conductor,
                                    'creacion' => 2
                                    )
                            ));

                    DB::connection('sqlsrvCxParque')
                        ->table('tra_gps_track')
                        ->insert(array(
                                array(
                                    'prefijo' => "",
                                    'latitud' => explode(",",$coor)[0],
                                    'longitud' =>  explode(",",$coor)[1],
                                    'fecha' => explode(" ",$this->fechaALong)[0],
                                    'hora' => explode(" ",$this->fechaALong)[1],
                                    'usuario_movil' => $placa
                                    )
                            ));

                    DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $inci,
                                    'usuario' => $conductor,
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' =>  " Conductor: " . $conductor . " crea incidencia - Observación:" . $obser,
                                    'tipo' => 2
                                    )
                                ));


                }
               DB::commit();
               return response()->json($inci);
            }
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            } 

       }

       if($opcion == 3) //Save Ubicación GPS
       {
            $super = $request->all()["super"];
            $coor = $request->all()["coor"];
            $pre = $request->all()["pre"];
            $tipo = 1;
            $conductor = "";
            if(isset($request->all()["conductor"]))
            {
                $conductor = $request->all()["conductor"];
                $tipo = 2;
            }

            $latitude = explode(",",$coor)[0];
            $longitud = explode(",",$coor)[1];

            DB::connection('sqlsrvCxParque')
                    ->table('tra_gps_track')
                    ->insert(array(
                            array(
                                'prefijo' => $pre,
                                'latitud' => $latitude,
                                'longitud' => $longitud,
                                'fecha' => explode(" ",$this->fechaALong)[0],
                                'hora' => explode(" ",$this->fechaALong)[1],
                                'usuario_movil' => $super
                                )
                        ));

            DB::connection('sqlsrvCxParque')
                    ->table('tra_token_movil')
                    ->where('usuario',$super)
                    ->update(array(
                            "conductor" => $conductor
                        ));
       }

       if($opcion == 4) //Save Acción móvil
       {
            $incidencia = $request->all()["incidencia"];
            $obser = $request->all()["obser"];
            $usuario = $request->all()["usuario"];
            $conductor = "";
            $tipo = 2;

            if(isset($request->all()["conductor"]))
            {
                $conductor = $request->all()["conductor"];
                $tipo = 2;
            }
            else
                $tipo = 3;   

            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $incidencia,
                            'usuario' => (isset($request->all()["conductor"]) ? $request->all()["conductor"] : $usuario),
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => $obser,
                            'tipo' => $tipo
                            )
                        ));

            $respuesta = 1; 
       }

       if($opcion == 5) //Save Fecha de recibido
       {
            $incidencia = $request->all()["incidencia"];
            $super = $request->all()["super"];

            $fechaReci = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->select('fecha_recibido')
                    ->get();

            if($fechaReci[0]->fecha_recibido == NULL)
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'fecha_recibido' => $this->fechaALong
                            
                        ));   

                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $incidencia,
                            'usuario' => $super,
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => "Técnico " . $super . " recibe incidencia",
                            'tipo' => 3
                            )
                        ));
            }
            $respuesta = 1; 
       }

       if($opcion == 6) //Save Fecha de aceptación
       {
            $incidencia = $request->all()["incidencia"];
            $super = $request->all()["super"];

            $fechaReci = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->select('fecha_aceptacion')
                    ->get();


            $tec = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->value('tecnico_asignado');

            if($tec != NULL && $tec != "")
            {
                    DB::connection('sqlsrvCxParque')
                    ->table('tra_token_movil')
                    ->where('usuario',$tec)
                    ->update(array(
                        'id_estado' => 'E03'
                        ));
            }


            if($fechaReci[0]->fecha_aceptacion == NULL)
            {
                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'fecha_aceptacion' => $this->fechaALong
                            
                        ));   

                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $incidencia,
                            'usuario' => $super,
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => "Técnico " . $super . " acepta incidencia",
                            'tipo' => 3
                            )
                        ));
            }
            $respuesta = 1; 
       }

       if($opcion == 7) //Save Fecha de finalización - Fin Téecnico
       {
            $incidencia = $request->all()["incidencia"];
            $super = $request->all()["super"];
            $accion = $request->all()["accion"];
            $obser = $request->all()["obser"];
            $taller = $request->all()["taller"];

            $fechaReci = DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->select('fecha_finalizacion')
                    ->get();

            if($fechaReci[0]->fecha_finalizacion == NULL)
            {
                $estado = "E06";
                if($accion == 2)
                    $estado = "E03";

                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'fecha_finalizacion' => $this->fechaALong,
                            'obser_tecnico_finaliza' => $obser,
                            'accion_tecnico_finaliza' => $accion,
                            'id_estado' => $estado,
                            'taller_asignado' => $taller
                        ));   

                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $incidencia,
                            'usuario' => $super,
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => "Técnico " . $super . " finaliza incidencia",
                            'tipo' => 3
                            )
                        ));

                if($accion == 2) //Remitido a taller
                {
                    DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia')
                        ->where('incidencia',$incidencia)
                        ->update(
                            array(
                                'segunda_accion' => 2
                            ));

                    //Agrega acción
                        DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => $super,
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' => "Técnico " . $super . " remitido a taller - " . $obser,
                                    'tipo' => 3
                                    )
                                ));
                }
                else
                {
                    //Vehículo revisado exitosamente
                    $placa = DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia')
                            ->where('incidencia',$incidencia)
                            ->value("placa");

                    DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia')
                        ->where('incidencia',$incidencia)
                        ->update(
                            array(
                                'fecha_cierre' => $this->fechaALong,
                                'usuario_cierre' => $super
                                )
                            );

                    //Cambio de estado Vehículo
                    DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->update(array(
                                'id_estado' => "E01"
                            ));

                    DB::connection('sqlsrvCxParque')
                        ->table('tra_token_movil')
                        ->where('usuario',$placa)
                        ->update(array(
                                'id_estado' => "E01"
                            ));
                }

                DB::connection('sqlsrvCxParque')
                    ->table('tra_token_movil')
                    ->where('usuario',$super)
                    ->update(
                        array(
                            'id_estado' => 'E01'
                            )
                        );
            }
            $respuesta = 1; 
       }

       if($opcion == 8) //Save formularios respuesta Tecnico
       {
            $pre = $request->all()["pre"];
            $incidencia = $request->all()["incidencia"];
            $cuerpo = $request->all()["cuerpo"];
            $user = $request->all()["user"];
            $tipo_form = $request->all()["tipo_form"];

            if(count($cuerpo) == 0)
              return response()->json(-1);  

            $placaVehiculo = DB::connection('sqlsrvCxParque')
                                ->table('tra_incidencia')
                                ->where('incidencia',$incidencia)
                                ->value('placa');

            $incidenciaSalidaEntrega =  DB::connection('sqlsrvCxParque')
                                            ->table('tra_incidencia')
                                            ->where('placa',$placaVehiculo)
                                            ->whereIn('id_estado',['E05','E07'])
                                            ->get(['incidencia']);

            for ($k=0; $k < count($incidenciaSalidaEntrega); $k++) { 
                
                $incidencia = $incidenciaSalidaEntrega[$k]->incidencia;

                DB::table('gop_formulario_respuesta')
                    ->where('incidencia',$incidencia)
                    ->where('usuario',$user)
                    ->where('tipo_form',$tipo_form)
                    ->delete();

                for ($i=0; $i < count($cuerpo); $i++) { 
                     DB::table('gop_formulario_respuesta')
                        ->insert(
                            array(
                                array(
                                    'usuario' => $user,
                                    'incidencia' => $incidencia,
                                    'tipo_form' => $tipo_form,
                                    'tipo_control' => $cuerpo[$i]["ti"],
                                    'id_pregunta' => $cuerpo[$i]["pr"],
                                    'res' => $cuerpo[$i]["res"],
                                    'cantidad' => ((isset($cuerpo[$i]["cant"]) ? $cuerpo[$i]["cant"] : "")),
                                    'fecha' => ((isset($cuerpo[$i]["fec"]) ? $cuerpo[$i]["fec"] : NULL))
                                    )
                                )
                            );
                }

                DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'tecnico_entrega' => $user,
                            'fecha_entrega' => $this->fechaALong,
                            'accion_entrega_1' => 1
                            )
                        );

                //Agrega acción
                DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_accion')
                        ->insert(array(
                            array(
                                'incidencia' => $incidencia,
                                'usuario' => $user,
                                'fecha_servidor' => $this->fechaALong,
                                'observacion' => "El técnico $user realiza entrega a la operación",
                                'tipo' => 3
                                )
                            ));

            };

            $respuesta = "1";
       }

       if($opcion == 9) //Save formularios respuesta Conductor
       {
            $pre = $request->all()["pre"];
            $incidencia = $request->all()["incidencia"];
            $cuerpo = $request->all()["cuerpo"];
            $user = $request->all()["usuario"];
            $placa = $request->all()["placa"];
            $tipo_form = $request->all()["tipo_form"];

            if(count($cuerpo) == 0)
              return response()->json(-1);  


            $placaVehiculo = DB::connection('sqlsrvCxParque')
                                ->table('tra_incidencia')
                                ->where('incidencia',$incidencia)
                                ->value('placa');


            $incidenciaSalidaEntrega =  DB::connection('sqlsrvCxParque')
                                            ->table('tra_incidencia')
                                            ->where('placa',$placaVehiculo)
                                            ->whereIn('id_estado',['E05','E07'])
                                            ->get(['incidencia']);

            for ($k=0; $k < count($incidenciaSalidaEntrega); $k++) { 
                
                $incidencia = $incidenciaSalidaEntrega[$k]->incidencia;

                DB::table('gop_formulario_respuesta')
                    ->where('incidencia',$incidencia)
                    ->where('usuario',$user)
                    ->where('tipo_form',$tipo_form)
                    ->delete();

                for ($i=0; $i < count($cuerpo); $i++) { 
                     DB::table('gop_formulario_respuesta')
                        ->insert(
                            array(
                                array(
                                    'usuario' => $user,
                                    'incidencia' => $incidencia,
                                    'tipo_form' => $tipo_form,
                                    'tipo_control' => $cuerpo[$i]["ti"],
                                    'id_pregunta' => $cuerpo[$i]["pr"],
                                    'res' => $cuerpo[$i]["res"],
                                    'cantidad' => ((isset($cuerpo[$i]["cant"]) ? $cuerpo[$i]["cant"] : "")),
                                    'fecha' => ((isset($cuerpo[$i]["fec"]) ? $cuerpo[$i]["fec"] : NULL))
                                    )
                                )
                            );
                }

                $tecnicoEntrega =  DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia')
                            ->where('incidencia',$incidencia)
                            ->value('tecnico_entrega');

                if($tecnicoEntrega == null || $tecnicoEntrega == "")
                {
                   DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'id_estado' => 'E06',
                            'fecha_cierre' => $this->fechaALong,
                            'usuario_cierre' => $user,
                            'tecnico_entrega' => $user,
                            'fecha_entrega' => $this->fechaALong,
                            'accion_entrega_1' => 1,
                            'conductor_entrega' => $user,
                            'fecha_verificaciones' => $this->fechaALong,
                            'accion_entrega_2' => 1,
                            )
                        ); 

                    //Agrega acción
                    DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => $user,
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' => "El conductor $user realiza entrega a la operación",
                                    'tipo' => 3
                                    )
                                ));

                    //Agrega acción
                    DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => $user,
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' => "El conductor $user ha cerrado la incidencia",
                                    'tipo' => 3
                                    )
                                ));

                }
                else
                {
                    DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'id_estado' => 'E06',
                            'fecha_cierre' => $this->fechaALong,
                            'usuario_cierre' => $user,

                            'tecnico_entrega' => $user,
                            'fecha_entrega' => $this->fechaALong,
                            'accion_entrega_1' => 1,

                            'conductor_entrega' => $user,
                            'fecha_verificaciones' => $this->fechaALong,
                            'accion_entrega_2' => 1,
                            )
                        ); 


                    //Agrega acción
                    DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => $user,
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' => "El conductor $user realiza entrega a la operación",
                                    'tipo' => 2
                                    )
                                ));

                    //Agrega acción
                    DB::connection('sqlsrvCxParque')
                            ->table('tra_incidencia_accion')
                            ->insert(array(
                                array(
                                    'incidencia' => $incidencia,
                                    'usuario' => $user,
                                    'fecha_servidor' => $this->fechaALong,
                                    'observacion' => "El conductor $user ha cerrado la incidencia",
                                    'tipo' => 2
                                    )
                                ));

                }         

                DB::connection('sqlsrvCxParque')
                        ->table('tra_incidencia_novedad')
                        ->where('incidencia',$incidencia)
                        ->update(
                            array(
                                'entrega_operacion' => 1
                                )
                            );
            };                       
            
            $otrasIncidenicas = DB::connection('sqlsrvCxParque')
                                ->table('tra_incidencia')
                                ->where('placa',$placaVehiculo)
                                ->where('incidencia','<>',$incidencia) //Incidencia actual
                                ->where('id_estado','<>','E06') //Finalzadas
                                ->where('id_estado','<>','E01') //Generadas
                                ->count();
            
            //var_dump($otrasIncidenicas);
            if($otrasIncidenicas == 0)
            {
                //Preguntar si ya existen otras incidencias abiertas
                DB::connection('sqlsrvCxParque')
                    ->table('tra_maestro')
                    ->where('placa',$placaVehiculo)
                    ->update(array(
                            "id_estado" => "E01"                  
                    ));

                DB::connection('sqlsrvCxParque')
                    ->table('tra_token_movil')
                    ->where('usuario',$placaVehiculo)
                    ->update(array(
                            "id_estado" => "E01"                  
                    ));
            }
            
            $respuesta = "1";
       }

       if($opcion == 10) //Tecnico libera incidencia
       {
            $incidencia = $request->all()["incidencia"];
            $super = $request->all()["super"];
            $pre = $request->all()["pre"];

           DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia')
                    ->where('incidencia',$incidencia)
                    ->update(
                        array(
                            'id_estado' => 'E01',
                            'accion' => NULL,
                            'tecnico_asignado' => NULL,
                            'taller_asignado' => NULL,
                            'fecha_recibido' => NULL,
                            'fecha_aceptacion' => NULL,
                            'fecha_finalizacion' => NULL,
                            'primera_accion' => NULL,
                            'segunda_accion' => NULL
                        ));   

            //Agrega acción
            DB::connection('sqlsrvCxParque')
                    ->table('tra_incidencia_accion')
                    ->insert(array(
                        array(
                            'incidencia' => $incidencia,
                            'usuario' => $super,
                            'fecha_servidor' => $this->fechaALong,
                            'observacion' => "El técnico $super cancela asignación de la incidencia",
                            'tipo' => 3
                            )
                        ));

            $respuesta = 1; 
       }

       if($opcion == 11) //Actualiza Vehículo
       {
            $placa = $request->all()["placa"];
            $user = $request->all()["user"];
            $pre = $request->all()["pre"];
            $tipo = $request->all()["tipo"];
            $esta = $request->all()["esta"];
            $pry = $request->all()["pry"];
            $km = $request->all()["km"];
            $obser = $request->all()["obser"];
            $coor = $request->all()["coor"];

            $veh = DB::connection('sqlsrvCxParque')
                        ->table( 'tra_maestro')
                        ->where('placa','LIKE','%' . $placa . '%')
                        ->get(['id_tipo_vehiculo as tipo','id_estado as estado','id_proyecto as proy','coordendas','observacion_mod'])[0];

            //Inserta LOG CAMBIOS VEHÍCULO
            DB::connection('sqlsrvCxParque')
                ->table('tra_maestro_cambios')
                ->insert(
                    array(
                            array(
                                    'placa' => $placa,
                                    'tipo_vehiculo' => $veh->tipo,
                                    'id_estado' => $veh->estado,
                                    'proyecto' => $veh->proy,
                                    'usuario' => $user,
                                    'usuario_proyecto' => $pre,
                                    'coordendas' => $veh->coordendas,
                                    'obser' => $veh->observacion_mod
                                )
                        )
                    );
            
            //Consulta KM ACTUAL

            $kmActual = DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->where('placa','LIKE','%' . $placa . '%')
                        ->get(['kilometraje']);

            if(count($kmActual) > 0)
                $kmActual = intval(str_replace(".00","",$kmActual[0]->kilometraje));
            else
                $kmActual = 0;

            if($kmActual == 0 && intval($km) == 0)
            {

            }
            else
            {
                if($kmActual != intval($km))
                {
                    //Inserta ODOMETRO
                    DB::connection('sqlsrvCxParque')
                    ->table('tra_vehiculo_odometro')
                    ->insert(
                        array(
                                array(
                                        'placa' => $placa,
                                        'fecha' => $this->fechaShort,
                                        'observaciones' =>"CAPTURA ODOMETRO APP INVENTARIO DE VEHÍCULOS",
                                        'kilometraje' => $km,
                                        'fecha_servidor' => $this->fechaALong
                                    )
                            )
                        );
                }
            }

            
            DB::connection('sqlsrvCxParque')
                ->table( 'tra_maestro')
                ->where('placa','LIKE','%' . $placa . '%')
                ->update(
                        array(
                                'fecha_ultima_mod' => $this->fechaALong,
                                'usuario_ultima_mod' => $user,
                                'observacion_mod' => $obser,
                                'coordendas' => $coor,
                                'id_tipo_vehiculo' => $tipo,
                                'id_estado' => $esta,
                                'id_proyecto' => $pry
                            )
                    );

            $respuesta = 1; 
       }

       return response()->json($respuesta);
    }    

    /**************FIN WEB SERVICES APLICACIÓN MÓVIL********/
    /*******************************************************/

}




    



