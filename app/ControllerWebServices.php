<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Http\Requests;

class ControllerWebServices extends Controller
{
    
    function __construct() {  
        /*$this->valorT = "";
        $this->tblAux = \Session::get('proy_long');
        $this->tblAux1 = \Session::get('proy_short');*/
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    public function iniCam(Request $request)
    {
        if(!isset($request->all()["user"]))
            header('Location:' . "{{config('app.Campro')[2]}}/campro/home");

        $proy = "";
        if(isset($request->all()["proy"]))
            $proy = $request->all()["proy"];
        else
            $proy = "-1";
                
        $user = $request->all()["user"];
        $ruta = $request->all()["ruta"];
        \Session::put('user_login',$user);
        if($proy == "")
        {
            \Session::put('proy_long',"rds_gop_");
            \Session::put('proy_short',"rds_");
            
        }
        else
        {
            if($proy == "-1")
            {
                \Session::put('proy_long',"home");
                \Session::put('proy_short',"home");    
            }
            else
            {
                \Session::put('proy_long',"$proy" . "_gop_");
                \Session::put('proy_short',"$proy" . "_");    
            }
            
        }
        
        \Session::forget('rds_gop_proyecto_orden_id');
        \Session::forget('rds_gop_proyecto_id');

        
        if(isset($request->all()["ver_ot"]))
        {
            $proyNu = DB::table(\Session::get('proy_long') .  'ordenes')
                        ->where('id_orden',$request->all()['ver_ot'])
                        ->select('id_proyecto')
                        ->get()[0]->id_proyecto;

            \Session::put('rds_gop_proyecto_id',$proyNu);
            \Session::put('rds_gop_proyecto_orden_id',$request->all()['ver_ot']);

            \Session::flash('gop_lider_seleccionado',$request->all()['ver_lider']);
            \Session::flash('gop_nodo_seleccionado',$request->all()['ver_nodo']);
        }
        //echo "Conectado con Laravel POST... Redirigiendo a :" . $ruta;
        return \Redirect::to($ruta);
    }

    /*WEB SERVICES ORDENES PROGRAMADAS*/

    public function validaUsuarioExistente(Request $request)
    {
    	$user = $request->all()["dat"]['usuario'];
    	$pass = $request->all()["dat"]['pass'];

    	$userA = DB::table('apu_inv_bodegas')
    			->where('id_bodega',$user)
    			->select('id_bodega')
    			->get();

    	if(count($userA) == 0)
            {
                $userA = DB::table($this->tblAux. 'cuadrilla')
                ->where('id_lider',$user)
                ->select('id_lider')
                ->get();

                if(count($userA) == 0)
                    return response()->json("0");
                else
                    return response()->json("1");
            }
    	else
    		return response()->json("1");

    }

    public function consultaOrdenesTrabajo(Request $request) //
    {
        $lider = $request->all()["dat"]['usuario'];
        //$lider = "79148760";
        $fechaSistema = $this->fechaShort;

        $dat = DB::table($this->tblAux . 'ordenes_manoobra_detalle')
        ->join($this->tblAux . 'ordenes' . $this->valorT, $this->tblAux . 'ordenes_manoobra_detalle.id_orden',
            "=", $this->tblAux . 'ordenes' . $this->valorT . ".id_orden")
        ->where('fecha_programacion',$fechaSistema)
        ->where('id_lider',$lider)
        ->where('id_estado','E2')
        ->select($this->tblAux . 'ordenes_manoobra_detalle.id_orden','fecha_programacion',
            $this->tblAux . 'ordenes_manoobra_detalle.hora_ini',$this->tblAux . 'ordenes_manoobra_detalle.hora_fin'
            ,'aux_id1','aux_id2','aux_id3','conductor_id',
            'matricula','observaciones','direccion','cd','nivel_tension',
            $this->tblAux . 'ordenes' . $this->valorT . ".hora_ini as horaI",
            $this->tblAux . 'ordenes' . $this->valorT . ".hora_corte",
            $this->tblAux . 'ordenes' . $this->valorT . ".hora_cierre",
            $this->tblAux . 'ordenes' . $this->valorT . ".hora_fin as hora_F"
            )
        ->get();

        $ordenes = [];

        foreach ($dat as $orden => $valor) {

            $nodos = DB::Table($this->tblAux . 'ordenes_manoobra')
                ->join($this->tblAux . 'nodos' . $this->valorT,$this->tblAux . 'nodos' . $this->valorT .'.id_nodo',
                    '=',$this->tblAux . 'ordenes_manoobra.id_nodo')
                ->where('id_orden',$valor->id_orden)
                ->where('id_personaCargo',$lider)
                ->select('nombre_nodo',$this->tblAux . 'ordenes_manoobra.id_nodo')
                ->groupBy('nombre_nodo',$this->tblAux . 'ordenes_manoobra.id_nodo')
                ->get();

            array_push($ordenes,
                [
                    "orden" => $valor->id_orden,
                    "fecha" => explode(" ",$valor->fecha_programacion)[0],
                    "hora_iniT" => $valor->hora_ini,
                    "hora_finT" => $valor->hora_fin,
                    "aux1" => $valor->aux_id1,
                    "aux2" => $valor->aux_id2,
                    "aux3" => $valor->aux_id3,
                    "cond" => $valor->conductor_id,
                    "matri" => $valor->matricula,
                    "obser" => $valor->observaciones,
                    "dire" => $valor->direccion,
                    "cd" => $valor->cd,
                    "nt" => $valor->nivel_tension,
                    "hora_iniM" => explode(":",$valor->horaI)[0] . ":" . explode(":",$valor->horaI)[0],
                    "hora_finM" => explode(":",$valor->hora_F)[0] . ":" . explode(":",$valor->hora_F)[0],
                    "hora_corte" => explode(":",$valor->hora_corte)[0] . ":" . explode(":",$valor->hora_corte)[0],
                    "hora_cierre" => explode(":",$valor->hora_cierre)[0] . ":" . explode(":",$valor->hora_cierre)[0],
                    "nodos" => $nodos
                ]
                );
        }

        return response()->json($ordenes);        
    }

    public function consultaBaremosTrabajo(Request $request)
    {
        $lider = $request->all()["dat"]['usuario'];
        $orden = $request->all()["dat"]['orden'];
        $nodo = $request->all()["dat"]['nodo'];

        $baremos = DB::table($this->tblAux . 'ordenes_manoobra')
            ->join($this->tblAux .'baremos',$this->tblAux .'baremos.codigo','=',
                $this->tblAux . 'ordenes_manoobra.id_baremo')
            ->where('id_orden',$orden)
            ->where('id_nodo',$nodo)
            ->where('id_personaCargo',$lider)
            ->where('periodo',2017)
            ->select('cantidad_confirmada as cant',$this->tblAux . 'ordenes_manoobra.id_baremo',
                'actividad')
            ->groupBy('cantidad_confirmada',$this->tblAux . 'ordenes_manoobra.id_baremo',
                'actividad')
            ->get();

        for ($i=0; $i < count($baremos); $i++) { 
            $bare = trim(DB::Table($this->tblAux . 'baremos')   
                    ->where('codigo',$baremos[$i]->id_baremo)
                    ->where('periodo',2017)
                    ->select('id_baremo')
                    ->get()[0]->id_baremo);

            
            $dato = DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                ->where('id_nodo',$nodo)
                ->where('id_orden',$orden)
                ->where('id_origen',$lider)
                ->where('id_baremo',$bare)
                ->select('cantidad_confirmada')
                ->get();

            
            if(count($dato) != 0)
                $baremos[$i]->cant = $dato[0]->cantidad_confirmada;

            //var_dump($baremos[$i]->cantidad_confirmada);  
        }
        
        return response()->json($baremos);
            
    }

    public function guardarBaremos(Request $request)
    {
        $lider = $request->all()["dat"]['0']['usuario'];
        $orden = $request->all()["dat"]['0']['orden'];
        $nodo = $request->all()["dat"]['0']['nodo'];
        $baremos = $request->all()["dat"]['0']['barem'];

        $proyecto = DB::table($this->tblAux . 'ordenes' . $this->valorT )
                ->where('id_orden',$orden)
                ->select('id_proyecto')
                ->get()[0]->id_proyecto;

        $ws = DB::table($this->tblAux . 'nodos' . $this->valorT)
            ->where('id_nodo',$nodo)
            ->select('id_ws')
            ->get()[0]->id_ws;

        for ($i=0; $i < count($baremos); $i++) { 

            $bare = DB::Table($this->tblAux . 'baremos')   
                    ->where('codigo',$baremos[$i]["bar"])
                    ->where('periodo',2017)
                    ->select('id_baremo','precio')
                    ->get()[0];

            $dato = DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                ->where('id_proyecto',$proyecto)
                ->where('id_ws',$ws)
                ->where('id_nodo',$nodo)
                ->where('id_orden',$orden)
                ->where('id_baremo',$bare->id_baremo)
                ->select('id_origen')
                ->get();

            if(count($dato) == 0)
            {
                DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                ->insert(array(
                    array(
                        'id_proyecto' => $proyecto,
                        'id_ws' => $ws,
                        'id_nodo' => $nodo,
                        'id_orden' => $orden,
                        'id_baremo' => $bare->id_baremo,
                        'cantidad_confirmada' => $baremos[$i]["can"],
                        'precio' => $bare->precio,
                        'id_origen' => $lider
                        )
                    ));
            }
            else
            {
                DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                ->where('id_proyecto',$proyecto)
                ->where('id_ws',$ws)
                ->where('id_nodo',$nodo)
                ->where('id_orden',$orden)
                ->where('id_baremo',$bare->id_baremo)
                ->update(array(
                    'cantidad_confirmada' => $baremos[$i]["can"],
                    ));
            }
        }

        return response()->json("1");
    }

    public function consultaMaterialesTrabajo(Request $request)
    {
        $lider = $request->all()["dat"]['usuario'];
        $orden = $request->all()["dat"]['orden'];
        $nodo = $request->all()["dat"]['nodo'];

        $materiales = DB::table($this->tblAux . 'ordenes_materiales')
            ->join($this->tblAux1 .'inv_maestro_articulos',$this->tblAux1 .'inv_maestro_articulos.id_articulo','=',
                $this->tblAux . 'ordenes_materiales.id_articulo')
            ->where('id_orden',$orden)
            ->where('id_nodo',$nodo)
            ->where('id_persoanCargo',$lider)
            ->select('cantidad_confirmada as cant',$this->tblAux . 'ordenes_materiales.id_articulo',
                'nombre','codigo_sap')
            ->groupBy('cantidad_confirmada',$this->tblAux . 'ordenes_materiales.id_articulo',
                'nombre','codigo_sap')
            ->get();


        $doc = DB::Table($this->tblAux . 'ordenes_materiales_documentos')
                ->where('id_orden',trim($orden))
                ->where('id_lider',trim($lider))
                ->where('id_nodo',trim($nodo))
                ->where('id_tipo_documento',"T007")
                ->select('id_documento')
                ->get();



         for ($i=0; $i < count($materiales); $i++) { 
            if(count($doc) > 0)
            {   
                $mat = DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                ->where('id_documento',$doc[0]->id_documento)
                ->where('id_articulo',$materiales[$i]->id_articulo)
                ->select('consumo','i_rz','r_ch','r_rz')
                ->get();

                if(count($mat) > 0)
                {
                    $materiales[$i]->cant1 = $mat[0]->consumo;
                    $materiales[$i]->irz = $mat[0]->i_rz;
                    $materiales[$i]->rch = $mat[0]->r_ch;
                    $materiales[$i]->rrz = $mat[0]->r_rz;
                }
            }
            else
            {
                $materiales[$i]->cant1 = "0";
                $materiales[$i]->irz = "0";
                $materiales[$i]->rch = "0";
                $materiales[$i]->rrz = "0";
            }
        }
        

        /*for ($i=0; $i < count($baremos); $i++) { 
            $bare = trim(DB::Table($this->tblAux . 'baremos')   
                    ->where('codigo',$baremos[$i]->id_baremo)
                    ->where('periodo',2017)
                    ->select('id_baremo')
                    ->get()[0]->id_baremo);

            
            $dato = DB::table($this->tblAux . 'ordenes_mobra' . $this->valorT)
                ->where('id_nodo',$nodo)
                ->where('id_orden',$orden)
                ->where('id_origen',$lider)
                ->where('id_baremo',$bare)
                ->select('cantidad_confirmada')
                ->get();

            
            if(count($dato) != 0)
                $baremos[$i]->cant = $dato[0]->cantidad_confirmada;

            //var_dump($baremos[$i]->cantidad_confirmada);  
        }*/
        
        

        return response()->json($materiales);
    }

    public function guardarMateriales(Request $request)
    {
        $lider = $request->all()["dat"]['0']['usuario'];
        $orden = $request->all()["dat"]['0']['orden'];
        $nodo = $request->all()["dat"]['0']['nodo'];
        try
        {
            if(isset($request->all()["dat"]['0']['mate']))
                $materailes = $request->all()["dat"]['0']['mate'];
            else
                return response()->json("1");
                       
        }catch(Exception $e)
        {
            return response()->json("1");
        }
        

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
                ->where('id_tipo_documento',"T007")
                ->select('id_documento')
                ->get();

      
        $codCosumo = 0;
        if(count($doc) == 0) //Generar nuevo documento
        {
            try
            {
                DB::beginTransaction();
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
                            'observaciones' => $proyecto  . "_" . $orden . "_" . $ws . "_" . $nodo . "_" . $lider,
                            'id_estado' => 'E3',
                            'id_orden' => $orden,
                            'gom' => 1234,
                            "id_usuario_edicion" => 'U00374',
                            "id_nodo" => $nodo
                            )
                        ));

                for ($i=0; $i < count($materailes); $i++) { 
                    DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                        ->insert(array(
                            array(
                                'id_documento' => $codCosumo,
                                'id_articulo' => $materailes[$i]["mat"], 
                                'consumo' => $materailes[$i]["can"],
                                'id_almacen' => '0000',
                                'i_rz' => ($materailes[$i]["rz"] == "" ? 0 :$materailes[$i]["rz"] ),
                                'r_ch' =>  ($materailes[$i]["ch"] == "" ? 0 :$materailes[$i]["ch"] ),
                                'r_rz' =>  ($materailes[$i]["rz"] == "" ? 0 :$materailes[$i]["rz"] )
                                )
                            ));
                }


                DB::table($this->tblAux  . 'ordenes_materiales_documentos')
                ->insert(array(
                    array(
                        'id_orden' => $orden,
                        'id_lider' => $lider,
                        'id_nodo' => $nodo,
                        'id_tipo_documento' => "T007",
                        'id_documento' => $codCosumo

                        )
                    ));

                DB::commit();
            }   
            catch(Exception $e)
            {
                DB::rollBack();
                return response()->json("0");
            }
        }
        else
        {
            $codCosumo = $doc[0]->id_documento;
            //Actualizar información
            for ($i=0; $i < count($materailes); $i++) { 
                    DB::table($this->tblAux1  . 'inv_detalle_documentos' . $this->valorT)
                        ->where('id_articulo',$materailes[$i]["mat"])
                        ->where('id_documento',$codCosumo)
                        ->update(
                            array(
                                'consumo' => $materailes[$i]["can"],
                                'i_rz' => ($materailes[$i]["rz"] == "" ? 0 : $materailes[$i]["rz"] ),
                                'r_ch' =>  ($materailes[$i]["ch"] == "" ? 0 : $materailes[$i]["ch"] ),
                                'r_rz' =>  ($materailes[$i]["rz"] == "" ? 0 : $materailes[$i]["rz"] ) 
                                
                                
                            ));
            }
        }
        return response()->json("1");
    }


    public function finalizarOrden(Request $request)
    {

        $lider = $request->all()["dat"]['usuario'];
        $orden = $request->all()["dat"]['orden'];

        DB::table($this->tblAux . 'ordenes' . $this->valorT)
        ->where('id_orden',$orden)
        ->update(array(
            'id_estado' => 'E4'
            ));

        
        return response()->json($orden);
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



    



    
    


    /*OTROS ACCESOS DE OTRAS APLICACIÓN*/
    //Excel de Kits de Materiales
    public function downloadExcelKitsMaterial(Request $request)
    {
        $fecha = $request->all()["fecha"];
        $tipoKit = $request->all()["kit"];
        $bodega = $request->all()["bodega"];
        $lideres = $request->all()["lideres"];
        $prefijo = $request->all()["prefijo"];


        $nombreBodega = DB::table($prefijo . '_inv_bodegas')
                    ->where('id_bodega',$bodega)
                    ->select('nombre')
                    ->get()[0]->nombre;

        \Excel::create('GENERACIÓN MASIVA DE SOLICITUD DE MATERIALES BASADA EN UN KITS', function($excel) use($fecha,$tipoKit,$bodega,$lideres,$nombreBodega,$prefijo) {

            $excel->sheet('Par seriado', function($sheet) use($fecha,$tipoKit,$bodega,$lideres,$nombreBodega,$prefijo) {

                $sheet->row(1,array( 
                    'FECHA', 'BODEGA', 'NOMBRE BODEGA', 'LÍDER', 'MÓVIL','CÓDIGO DE MATERIAL','MATERIAL','UNIDAD','EXISTENCIA ORIGEN','EXISTENCIA DESTINO','CANTIDAD'
                ));

                $sheet->row(2,array( 
                    '', '', '', '','','','','','','',''
                ));
                
                $arreglo = explode(",",$lideres);

                $auxC = 3;
                for ($i=0; $i < count($arreglo) - 1; $i++) {
                    
                    $movil = DB::table($prefijo . '_gop_cuadrilla')
                            ->where('id_lider',$arreglo[$i])
                            ->select('id_movil')
                            ->get()[0]->id_movil;

                    $consulta = "
                    SELECT
                      " . $prefijo . "_inv_maestro_articulos.nombre,
                      " . $prefijo . "_inv_material_kit.cantidad,
                      " . $prefijo . "_inv_maestro_articulos.codigo_sap as id_articulo,
                      inv_cat_unidades.nombre AS nombreU,
                      isnull(existencia_origen.e_origen,0) as e_origen,
                      isnull(existencia_destino.e_destino,0) as e_destino
                    FROM " . $prefijo . "_inv_material_kit
                    JOIN " . $prefijo . "_inv_maestro_articulos ON " . $prefijo . "_inv_material_kit.id_articulo = " . $prefijo . "_inv_maestro_articulos.id_articulo
                    JOIN inv_cat_unidades ON inv_cat_unidades.id_unidad = " . $prefijo . "_inv_maestro_articulos.id_unidad
                    left join (
                          select id_articulo, count(serie) as e_origen
                          from " . $prefijo . "_inv_seriados
                          where id_bodega = '$bodega'
                          and id_estado = 'ES01'
                        GROUP BY id_articulo
                      ) as existencia_origen
                    on (existencia_origen.id_articulo = " . $prefijo . "_inv_material_kit.id_articulo )
                    left join (
                         select id_articulo, count(serie) as e_destino
                         from " . $prefijo . "_inv_seriados
                         where id_bodega = '$arreglo[$i]' 
                         and id_estado = 'ES02'
                         GROUP BY id_articulo
                        ) as existencia_destino 
                    on (existencia_destino.id_articulo = " . $prefijo . "_inv_material_kit.id_articulo )  
                    WHERE " . $prefijo . "_inv_material_kit.id = $tipoKit
                    AND " . $prefijo . "_inv_maestro_articulos.par_seriado = 'SI';
                        ";
                $mat = DB::select($consulta);


                    foreach ($mat as $mt => $val) {
                        $sheet->row($auxC, array(
                             $fecha,$bodega,$nombreBodega,$arreglo[$i],$movil,$val->id_articulo,$val->nombre,$val->nombreU,$val->e_origen,$val->e_destino,$val->cantidad
                        ));       
                        $auxC++;
                    }
                }
                
                

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:H2');
                $sheet->mergeCells('I1:I2');
                $sheet->mergeCells('J1:J2');
                $sheet->mergeCells('K1:K2');
                

                $sheet->cells('A1:K' . ($auxC + 1), function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    // manipulate the range of cells
                });

                $sheet->cells('A1:K1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontColor('#ffffff');
                    $cells->setBackground('#0060ac');
                    
                });

                $sheet->cells('A2:K2', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontColor('#ffffff');
                    $cells->setBackground('#0060ac');
                });

                //Auto Size Columnas
                $sheet->setAutoSize(array(
                    'A', 'B', 'C' , 'D','E','F','G','H','I','J','K'
                ));

                $sheet->setWidth('A', 15);
                $sheet->setWidth('B', 15);
                $sheet->setWidth('C', 31);
                $sheet->setWidth('D', 15);
                $sheet->setWidth('E', 15);
                $sheet->setWidth('F', 25);
                $sheet->setWidth('G', 65);
                $sheet->setWidth('H', 15);
                $sheet->setWidth('I', 25);
                $sheet->setWidth('J', 25);
                $sheet->setWidth('K', 15);
            });

            $excel->sheet('No par seriado', function($sheet) use($fecha,$tipoKit,$bodega,$lideres,$nombreBodega,$prefijo) {

                            $sheet->row(1,array( 
                                'FECHA', 'BODEGA', 'NOMBRE BODEGA','LÍDER', 'MÓVIL','CÓDIGO DE MATERIAL','MATERIAL','UNIDAD','EXISTENCIA ORIGEN','EXISTENCIA DESTINO','CANTIDAD'
                            ));

                            $sheet->row(2,array( 
                                '', '', '','', '','','','','','',''
                            ));
                            
                            $arreglo = explode(",",$lideres);

                            $auxC = 3;
                            for ($i=0; $i < count($arreglo) - 1; $i++) {
                                
                                $movil = DB::table($prefijo . '_gop_cuadrilla')
                                        ->where('id_lider',$arreglo[$i])
                                        ->select('id_movil')
                                        ->get()[0]->id_movil;

                                $consulta = "
                                SELECT " . $prefijo . "_inv_maestro_articulos.nombre,
                                 " . $prefijo . "_inv_material_kit.cantidad,
                                 " . $prefijo . "_inv_maestro_articulos.codigo_sap as id_articulo,
                                 inv_cat_unidades.nombre as nombreU,
                                 (isnull(entradas_origen.entradas, 0) -
                                  (isnull(salidas_origen.salidas, 0)-isnull(reintegros_origen.reintegros, 0))) as existencia_origen,
                                 (isnull(entradas_destino.entradas, 0) -
                                  (isnull(salidas_destino.salidas, 0)-isnull(reintegros_destino.reintegros,0))) as existencia_destino
                               FROM " . $prefijo . "_inv_material_kit
                                 inner JOIN " . $prefijo . "_inv_maestro_articulos
                                   ON (" . $prefijo . "_inv_material_kit.id_articulo = " . $prefijo . "_inv_maestro_articulos.id_articulo)
                                 JOIN inv_cat_unidades
                                   ON inv_cat_unidades.id_unidad=" . $prefijo . "_inv_maestro_articulos.id_unidad

                                 left join (
                                             select id_articulo, SUM(entradas) AS entradas
                                             from (select id_documento, id_articulo, sum(cantidad) as entradas
                                                   from " . $prefijo . "_inv_detalle_documentos
                                                   group by id_documento, id_articulo) as detalle
                                               inner join (select * from " . $prefijo . "_inv_documentos
                                               where id_bodega_destino = '$bodega'
                                                     and id_estado = 'E3'
                                                          ) as documento
                                                 on (documento.id_documento = detalle.id_documento )
                                             group by id_articulo
                                           ) entradas_origen
                                   on (entradas_origen.id_articulo = " . $prefijo . "_inv_material_kit.id_articulo)

                                 left join (
                                             select id_articulo, sum(salidas) as salidas
                                             from (select id_documento, id_articulo, sum(cantidad) as salidas
                                                   from " . $prefijo . "_inv_detalle_documentos
                                                   group by id_documento, id_articulo) as detalle
                                               inner join (select * from " . $prefijo . "_inv_documentos
                                               where id_bodega_origen = '$bodega'
                                                     and id_estado = 'E3'
                                                          ) as documento
                                                 on (documento.id_documento = detalle.id_documento )
                                               group by id_articulo
                                           ) salidas_origen
                                   on (salidas_origen.id_articulo = " . $prefijo . "_inv_material_kit.id_articulo)


                                 left join (
                                             select id_articulo, sum(reintegros) as reintegros
                                             from (select id_documento, id_articulo, sum(reintegro) as reintegros
                                                   from " . $prefijo . "_inv_detalle_documentos
                                                   group by id_documento, id_articulo) as detalle
                                               inner join (select * from " . $prefijo . "_inv_documentos
                                               where id_bodega_origen = '$bodega'
                                                     and id_estado = 'E3'
                                                          ) as documento
                                                 on (documento.id_documento = detalle.id_documento )
                                             group by id_articulo
                                           ) reintegros_origen
                                   on (reintegros_origen.id_articulo = " . $prefijo . "_inv_material_kit.id_articulo)

                                 left join (
                                             select id_articulo, sum(entradas) as entradas
                                             from (select id_documento, id_articulo, sum(cantidad) as entradas
                                                   from " . $prefijo . "_inv_detalle_documentos
                                                   group by id_documento, id_articulo) as detalle
                                               inner join (select * from " . $prefijo . "_inv_documentos
                                               where id_bodega_destino = '$arreglo[$i]'
                                                     and id_estado = 'E3'
                                                          ) as documento
                                                 on (documento.id_documento = detalle.id_documento )
                                             group by id_articulo
                                           ) entradas_destino
                                   on (entradas_destino.id_articulo = " . $prefijo . "_inv_material_kit.id_articulo)

                                 left join (
                                             select id_articulo, sum(salidas) as salidas
                                             from (select id_documento, id_articulo, sum(cantidad) as salidas
                                                   from " . $prefijo . "_inv_detalle_documentos
                                                   group by id_documento, id_articulo) as detalle
                                               inner join (select * from " . $prefijo . "_inv_documentos
                                               where id_bodega_origen = '$arreglo[$i]'
                                                     and id_estado = 'E3'
                                                          ) as documento
                                                 on (documento.id_documento = detalle.id_documento )
                                               group by id_articulo
                                           ) salidas_destino
                                   on (salidas_destino.id_articulo = " . $prefijo . "_inv_material_kit.id_articulo)


                                 left join (
                                             select id_articulo, sum(reintegros) as reintegros
                                             from (select id_documento, id_articulo, sum(reintegro) as reintegros
                                                   from " . $prefijo . "_inv_detalle_documentos
                                                   group by id_documento, id_articulo) as detalle
                                               inner join (select * from " . $prefijo . "_inv_documentos
                                               where id_bodega_origen = '$arreglo[$i]'
                                                     and id_estado = 'E3'
                                                          ) as documento
                                                 on (documento.id_documento = detalle.id_documento )
                                             group by id_articulo
                                           ) reintegros_destino
                                   on (reintegros_destino.id_articulo = " . $prefijo . "_inv_material_kit.id_articulo)

                               WHERE " . $prefijo . "_inv_material_kit.id = $tipoKit
                                     AND " . $prefijo . "_inv_maestro_articulos.par_seriado = 'NO'
                                    ";
                                    
                                    $mat = DB::select($consulta);
                                    foreach ($mat as $mt => $val) {
                                    $sheet->row($auxC, array(
                                         $fecha,$bodega,$nombreBodega,$arreglo[$i],$movil,$val->id_articulo,$val->nombre,$val->nombreU,$val->existencia_origen,$val->existencia_destino,$val->cantidad
                                    ));       
                                    $auxC++;
                                }
                            }

                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:H2');
                $sheet->mergeCells('I1:I2');
                $sheet->mergeCells('J1:J2');
                $sheet->mergeCells('K1:K2');
                
                $sheet->cells('A1:K' . ($auxC + 1), function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    // manipulate the range of cells
                });

                $sheet->cells('A1:K1', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontColor('#ffffff');
                    $cells->setBackground('#0060ac');
                    
                });

                $sheet->cells('A2:K2', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setFontColor('#ffffff');
                    $cells->setBackground('#0060ac');
                });

                //Auto Size Columnas
                $sheet->setAutoSize(array(
                    'A', 'B', 'C' , 'D','E','F','G','H','I','J','K'
                ));
                $sheet->setWidth('A', 15);
                $sheet->setWidth('B', 15);
                $sheet->setWidth('C', 31);
                $sheet->setWidth('D', 15);
                $sheet->setWidth('E', 15);
                $sheet->setWidth('F', 25);
                $sheet->setWidth('G', 65);
                $sheet->setWidth('H', 15);
                $sheet->setWidth('I', 25);
                $sheet->setWidth('J', 25);
                $sheet->setWidth('K', 15);
                        });
        })->export('xls');

    }

    public function validarConexion(Request $request)
    {
        $opc = $request->all()['opc'];

        if($opc == "1") //Login como Admin desde las aplicaciones móviles
        {
            $pass = $request->all()['pass'];
            $apk = $request->all()['apk'];

            $datos = DB::table('apk_versiones_configuracion')
                        ->where('id_aplicacion',$apk)
                        ->get(['srv_recepcion_prueba','svr_recepcion as srv_recepcion','srv_envio_prueba','svr_envio as srv_envio','srv_imagenes_prueba','svr_imagenes as srv_imagenes','admin_pass'])[0];
            
            if($datos->admin_pass == $pass)
                return response()->json(array("1",$datos));
            else
                return response()->json(array("0",[]));
        }

        if($opc == "2")
        {
            return response()->json("1");
        }
    }

    //Función encargada de validar la versión de APP Móvil
    public function consultaVersion(Request $request)
    {
        $idApk = $request->all()["id"];
        $version = $request->all()["version"];

        $versionAPK = DB::table('apk_versiones_configuracion')
                        ->where('id_aplicacion',$idApk)
                        ->value('version_produccion');

        $url = DB::table('apk_versiones_configuracion')
                        ->where('id_aplicacion',$idApk)
                        ->value('url_actualizar');

        $com1 = DB::table('apk_versiones_configuracion')
                        ->where('id_aplicacion',$idApk)
                        ->value('compresion1');

        $com2 = DB::table('apk_versiones_configuracion')
                        ->where('id_aplicacion',$idApk)
                        ->value('compresion2');

        $com3 = DB::table('apk_versiones_configuracion')
                        ->where('id_aplicacion',$idApk)
                        ->value('compresion3');

        $tel = DB::table('apk_versiones_configuracion')
                ->where('id_aplicacion',$idApk)
                ->value('telefono_soporte');

        $nombre = DB::table('apk_versiones_configuracion')
                ->where('id_aplicacion',$idApk)
                ->value('nombre_soporte');


        if($version == $versionAPK)
            return response()->json(array(1,$url,$com1,$com2,$com3,$tel,$nombre));
        else
            return response()->json(array(0,$url));
    }


    //Función encargada de validar la versión de APP Móvil
    public function consultaHuellasUser(Request $request)
    {
        $user = $request->all()["users"];
        $arr = [];
        for ($i=0; $i < count($user); $i++) { 
            array_push($arr,
                array(
                    "user" => $user[$i],
                    "bio" => DB::Table('rh_personas')
                                ->where('identificacion',$user[$i])
                                ->value('huella')
                )
                );
        }
        return response()->json($arr);
    }

    //Consulta Existencia del usuario
    public function consultaUserHuella(Request $request)
    {
        $cedula = $request->all()["cedula"];

        $contUser = DB::Table('rh_personas')
                    ->where('identificacion',$cedula)
                    ->count();

        if($contUser == 0)
        {
            return response()->json(
                    array(
                                'res' => 0,
                                'nombre' => "",
                                'huella' => 0
                        )
                );    
        }

        $nombre =  DB::Table('rh_personas')
                    ->where('identificacion',$cedula)
                    ->value(DB::raw("(nombres + ' ' + apellidos) as nombre"));

        $huella = DB::Table('rh_personas_huella')
                    ->where('usuario',$cedula)
                    ->value("huella");

        if($huella == null || $huella == "")
            $huella = 0;
        else
            $huella = 1;

        return response()->json(
                    array(
                        'res' => 1,
                        'nombre' => strtoupper($nombre),
                        'huella' => $huella
                        )
            ); 
    }

    //Guarda información de la huella
    public function saveUserHuella(Request $request)
    {   
        $cedula = str_replace("(ANSI)","",$request->all()["cedula"]);        
        $huella = $request->all()["huella"];

        $cont = DB::Table('rh_personas_huella')
            ->where('usuario',$cedula)
            ->count();

        if($cont == 0)
        {
            DB::Table('rh_personas_huella')
            ->where('usuario',$cedula)
            ->insert(
                array(
                    array(
                        "huella" => $huella,
                        "usuario" => $cedula
                    )
                    )
                );
        }
        else
        {
            DB::Table('rh_personas_huella')
            ->where('usuario',$cedula)
            ->update(
                array(
                        "huella" => $huella
                    )
                );
        }

        return response()->json(
                    array(
                        'res' => 1
                        )
            ); 
    }   


    //Función encargada de cargar las fotografias
    public function cargaAplicacionesMovilesImagenes(Request $request)
    {
        $opc = $request->all()["opc"];

        set_time_limit(0);
        
        if($opc == "1") //Carga imagenes REDES
        {
            $orden = $request->all()["orden"];
            $user = "";
            if(isset($request->all()["user"]))
                $user = $request->all()["user"];

            if(isset($request->all()["lider"]))
                $user = $request->all()["lider"];

            $img = $request->all()["img"];
            $archivo = base64_decode(explode(",",$img)[1]);
            $nom = $orden . "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";
            $envio = self::envioArchivos($archivo,$nom,"/tprog/fotos/ordenes","201.217.195.43","usuario_ftp","74091450652!@#1723cc");               

            DB::table('rds_gop_fotos')
                    ->insert(array(
                        array(
                            'id_orden' => $orden,
                            'ruta' => $nom,
                            'usuario' => $user
                            )
                        ));
        }

        if($opc == "2") //Carga tecnico
        {
            $incidencia = $request->all()["incidencia"];
            $tipo = $request->all()["tipo"];
            $img = $request->all()["img"];
            $archivo = base64_decode(explode(",",$img)[1]);
            $nom = $incidencia . "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";
            $envio = self::envioArchivos($archivo,$nom,"/anexos_apa/anexos","190.60.248.195","usuario_ftp","74091450652!@#1723cc");               

            DB::connection('sqlsrvCxParque')
            ->table('tra_incidencia_anexos')
            ->insert(array(
                array(
                    'incidencia' => $incidencia,
                    'ruta' => $nom,
                    'fecha_servidor' => $this->fechaALong,
                    'tipo' => $tipo
                    )
                ));
        }

        if($opc == "3") //App supervisión
        {
            $fecha1 = $request->all()["fecha1"];
            $fecha2 = $request->all()["fecha2"];
            $turno = $request->all()["turno"];
            $super = $request->all()["super"];
            $tipo = $request->all()["tipo"];
            $img = $request->all()["img"];
            $orden = "";
            $pre = "";
            if(isset($request->all()["pre"]))
                $pre = $request->all()["pre"];

            if($request->all()["tipo"] == "T05")
            {
                if(isset($request->all()["orden"]))
                    $orden = $request->all()["orden"];
            }

            $archivo = base64_decode(explode(",",$img)[1]);
            $nom = "Supervision_" . $super .  "_" . substr(md5(uniqid(rand())), 0, 10) . ".png";
            $envio = self::envioArchivos($archivo,$nom,"/ins","201.217.195.43","usuario_ftp","74091450652!@#1723cc");               

            DB::table('ins_foto')
                    ->insert(array(
                        array(
                            'id_super' => $super,
                            'id_tipo' => $tipo,
                            'fecha' => $this->fechaALong,
                            'ruta' => $nom,
                            'id_super' => $super,
                            'turno' => $turno,
                            'fecha_celular' => $fecha1,
                            'fecha_celular2' => $fecha2,
                            'pre' =>  $pre,
                            'orden' => $orden
                            )
                        ));           

        }

        if($opc == "4") //AGS Levantamiento
        {
            $orden = $request->all()["orden"];
            $user = $request->all()["user"];
            $img = $request->all()["img"];
            $pre = $request->all()["pre"];

            $archivo = base64_decode(explode(",",$img)[1]);
            $nom = "AGS_" . $orden . "_" . $user .  "_" . substr(md5(uniqid(rand())), 0, 10) . ".png";
            $envio = self::envioArchivos($archivo,$nom,"/ags","201.217.195.43","usuario_ftp","74091450652!@#1723cc");               

            DB::table($pre . '_gop_ags_fotos')
                    ->insert(array(
                        array(
                            'orden' => $orden,
                            'usuario_movil' => $user,
                            'ruta' => $nom
                            )
                        ));           

        }

        if($opc == "5") //Conductor
        {
            $incidencia = $request->all()["incidencia"];
            $pre = $request->all()["pre"];       
            $tipo = $request->all()["tipo"];
            $img = $request->all()["img"];
            
            $archivo = base64_decode(explode(",",$img)[1]);
            $nom = $incidencia . "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";
            $envio = self::envioArchivos($archivo,$nom,"/anexos_apa/anexos","190.60.248.195","usuario_ftp","74091450652!@#1723cc");               

            DB::connection('sqlsrvCxParque')
            ->table('tra_incidencia_anexos')
            ->insert(array(
                array(
                    'incidencia' => $incidencia,
                    'ruta' => $nom,
                    'fecha_servidor' => $this->fechaALong,
                    'tipo' => $tipo
                    )
                ));       

        }

        if($opc == "6") //Redes Eventos
        {
            $tipo_f = $request->all()["tipo"];
            $img = $request->all()["foto"];
            $tipifi = $request->all()["tipifi"];

            $ssl = 0;

            if(isset($request->all()["ssl"]))
                $ssl = $request->all()["ssl"];
                        
            $archivo = base64_decode(explode(",",$img)[1]);

            if($tipo_f == "6")
                $nom = "Redes_Autoinspeccion" . "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";
            
            if($tipo_f == "16")
                $nom = "Redes_IPO" . "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";

            if($tipo_f == "9")
                $nom = "Redes_Autoinspeccion_Motocicleta" . "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";

            if($tipo_f == "10")
                $nom = "Redes_Autoinspeccion_Vehiculo" . "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";

            if($tipo_f == "-1")
                $nom = "Redes_PARE" . "_" . substr(md5(uniqid(rand())), 0, 5) . ".png";

            $cedula = "";
            if($tipo_f == "-2")
            {
                $cedula = $request->all()["cedula"];
                $nom = "Redes_Autoinspeccion_Firma_" . $cedula . "_" .  substr(md5(uniqid(rand())), 0, 5) . ".png";
            }

            if($tipo_f == "-3")
            {
                $cedula = $request->all()["cedula"];
                $nom = "Redes_PREOPERACION_IPO_Firma_" . $cedula . "_" .  substr(md5(uniqid(rand())), 0, 5) . ".png";
            }

            if($tipo_f == "-2" || $tipo_f == "-3")
            {
                $envio = self::envioArchivos($archivo,$nom,"/ssl/rds/FORMULARIOS/firmas","201.217.195.43","usuario_ftp","74091450652!@#1723cc");    

                DB::Table('ssl_gop_firmas_formatos')  
                            ->insert(array(
                                array(
                                    'id_formato' => ($tipo_f == "-2" ? "6" : "16"),
                                    'ruta' => $nom,
                                    'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                                    'id_origen' => $cedula,
                                    'hora' => explode(" ",$this->fechaALong)[1],
                                    'id_evento' => $ssl
                                    )
                                ));

            }
            else
            {
                $envio = self::envioArchivos($archivo,$nom,"/ssl/rds/FORMULARIOS/fotos","201.217.195.43","usuario_ftp","74091450652!@#1723cc");                   
            }
            

            DB::table('ssl_gop_fotos_formatos')
            ->insert(array(
                array(
                    'id_formato' => 0,
                    'ruta' => $nom,
                    'fecha_creacion' => explode(" ",$this->fechaALong)[0],
                    'hora' => explode(" ",$this->fechaALong)[1],
                    'tipificacion' => $tipifi,
                    'id_evento' => $ssl
                    )
                ));  
        }

        if($opc == "7") //App Pla de supervision
        {
            $super = $request->all()["super"];
            $insp = $request->all()["insp"];
            $tipo = $request->all()["tipo"];
            $img = $request->all()["img"];

            $ruta = DB::table('rutas_anexos')
            ->select('url_visualizacion')
            ->where('tipo_operacion', "ruta")
            ->get();
            $ruta = $ruta[0]->url_visualizacion;

            //$archivo = base64_decode(explode(",",$img)[1]);
            $filePath = "Supervision_" . $super .  "_" . substr(md5(uniqid(rand())), 0, 10) . ".png";
            //$envio = self::envioArchivos($archivo,$nom,"/anexos","190.60.248.195","acceso_anexos","acceso_anexos.2019");               
            //$envio = self::envioArchivos($archivo,$nom,"/anexos_apa/anexos","190.60.248.195","usuario_ftp","74091450652!@#1723cc"); 

            //$url = 'http://localhost:8080/almacenamiento/public/archivos/guardaFotoInspeccion';
            $url = $ruta.'archivos/guardaFotoInspeccion';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $postFields = array(
                'name' => $filePath,
                'img' => $img
            );
            //dd($postFields);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            $result = curl_exec($ch);
            if(curl_errno($ch)){
                throw new Exception(curl_error($ch));
            }
            // //echo $result;
            // print_r($result);
            if($result == 1){
                DB::table('ins_foto')
                ->insert(array(
                    array(
                        'id_super' => $super,
                        'id_tipo' => $tipo,
                        'fecha_celular' => $this->fechaALong,
                        'fecha_celular2' => $this->fechaALong,
                        'fecha' => $this->fechaALong,
                        'ruta' => $filePath,
                        'orden' => $insp,
                        'id_inspeccion' => $insp
                    )
                ));
            }
        }

        return response()->json("1");
    }


    private function envioArchivos($archivo,$nombreArchivo,$carpeta,$ip,$user,$pass)
    {
        $id_ftp=ftp_connect($ip,21); //Obtiene un manejador del Servidor FTP
        ftp_login($id_ftp,$user,$pass); //Se loguea al Servidor FTP
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


    public function enviarNotificacion(Request $request)
    {
        if(!isset($request->all()["id_evento"]))
            return response()->json(array(
                    'res' => "No existe la variable id_evento"
                ));

        $ssl = DB::Table('ssl_eventos')
            ->where('id',$request->all()["id_evento"])
            ->select('id_origen','observaciones','fecha','id_orden','tipo_evento','hora','id','prefijo','conformidad')
            ->get();

        if(count($ssl) == 0)
             return response()->json(array(
                    'res' => "El ID evento no existe"
                ));

        $ssl = DB::Table('ssl_eventos')
            ->where('notificacion',0)
            ->where('id',$request->all()["id_evento"])
            ->select('id_origen','observaciones','fecha','id_orden','tipo_evento','hora','id','prefijo','conformidad')
            ->get();

        if(count($ssl) == 0)
             return response()->json(array(
                    'res' => "La notificación del evento ya fue enviada"
                ));


        foreach ($ssl as $key => $value) {

            $dbSuper = DB::Table($value->prefijo . '_gop_cuadrilla')
                        ->where('id_lider',$value->id_origen)
                        ->value('id_supervisor');
            
            if($dbSuper != "" && $dbSuper != null)
            {
                $tokenUser = DB::Table('ins_token_movil')
                        ->where('id_supervidor',$dbSuper)
                        ->value('token_movil');

                if($tokenUser != "" && $tokenUser != null)
                {
                    $liderNombre = DB::Table('rh_personas')
                                ->where('identificacion',$value->id_origen)
                                ->select('nombres','apellidos')
                                ->get()[0];

                    $nom = $liderNombre->nombres . " " . $liderNombre->apellidos; 

                    $title = "Reporte de eventos";

                    $conforme = "";

                    if($value->conformidad == "NC")
                        $conforme = "- NO CONFORME";

                    if($value->conformidad == "C")
                        $conforme = "- CONFORME";

                    $body = "El líder " . $nom  . " a generado un evento tipo " . strtoupper($value->tipo_evento) . " $conforme , Observación: " . $value->observaciones;
                    $sonido = "tono.mp3";
                    $tipo = 0; 
                    if($value->tipo_evento == "panico" || $value->tipo_evento == "PANICO")
                    {
                        $sonido = "panico.mp3";
                        $tipo = 1;  //PANICO
                    }

                    if($value->tipo_evento == "PARE")
                    {
                        $sonido = "pare.mp3";
                        $tipo = 2;  //PARE
                    }

                    if($value->tipo_evento == "autoinspeccion")
                    {
                        $sonido = "auto.mp3";
                        $tipo = 3;  //AUTOINPECCIÓN
                    }

                    if($value->tipo_evento == "preipo")
                    {
                        $sonido = "pre.mp3";
                        $tipo = 4;  //PREIPO
                    }

                    $icon = "fcm_push_icon";
                    $otro = "";
                    $token = $tokenUser;
                    $super = $dbSuper;
                    $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$super,$tipo,$value->tipo_evento,$value->id_origen);
                    $this->info("ENVIO DE NOTIFICACIONES SSL EVENTOS Líder:" . $value->id_origen . " EVENTO:" . strtoupper($value->tipo_evento) . " FECHA:" . $value->fecha . " ID:" . $value->id );

                    //Actualiza SSL
                    DB::Table('ssl_eventos')
                        ->where('id',$value->id)
                        ->update(
                            array(
                            'notificacion' => 1
                            )
                        );
                }
            }
        }

        return response()->json(array(
                    'res' => "Se ha enviado la notificación del evento"
                ));
    }

    public function enviaNotificacion($titulo,$cuerpo,$sound,$icono,$para,$otroD,$superviso,$tipo,$tipoEvento,$conforme)
    {
        // https://firebase.google.com/docs/cloud-messaging/http-server-ref#downstream-http-messages-json
        // https://github.com/fechanique/cordova-plugin-fcm
        try{
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
                'to' =>  $to,
                "notification" => array(
                    "title"=>$title,
                    "body"=>$body,
                    "sound"=>$sonido,
                    "icon"=>$icon,
                    "color" => "#084A9E",
                    "tipo" => $tipo
                  ),
                  "data" => array(
                    "title"=>$title,
                    "body"=>$body,
                    "otro"=>$otro,
                    "tipoE"=>$tipoEvento,
                    "conf"=>$conforme
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
                    ));

            return "1";

        } catch(\Exception $e) {
            return "0";
            //var_dump($e->getMessage());
            /*trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);*/
            
        }
    }


}

