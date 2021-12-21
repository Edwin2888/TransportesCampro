<?php

namespace App\Http\Controllers\Ags;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class ControllerWSMovil extends Controller
{
    //Funciones Propias Privadas

	//Función encargada de llenar los espacios faltantes con 0
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



    public function WSAgsSaveMovil(Request $request)
    {

    	$prefijo = $request->all()['pre'];
		$prefijoFinal = $request->all()['pre'];


		// Decodificando formato Json, y se guarda la opcion seleccionada
	    $user = "";
	    $rotulo = "";
	    $pt = "";
	    $dire = "";
	    $loca = "";
	    $barrio = "";
	    $tipoC = "";
	    $turnC = "";
	    $tipoF = "";
	    $coor = "";
	    $obser = "";
	    $tecno = "";
	    $pote = "";

	    $final_res = "0";

		$id_orden = 0;
		$id_formato = "";

		if($prefijoFinal == "apu" || $prefijoFinal == "mod") //apu y mod
		{
			if(isset($request->all()['id_user']))
	 		$user = $request->all()['id_user'];
	 	
		 	if(isset($request->all()['num_rot']))
		 		$rotulo = $request->all()['num_rot'];

		 	if(isset($request->all()['num_pt']))
		 		$pt = $request->all()['num_pt'];

		 	if(isset($request->all()['dir']))
		 		$dire = $request->all()['dir'];

		 	if(isset($request->all()['loca']))
		 		$loca = $request->all()['loca'];

		 	if(isset($request->all()['bar']))
		 		$barrio = $request->all()['bar'];

		 	if(isset($request->all()['tipo_cuad']))
		 		$tipoC = $request->all()['tipo_cuad'];

		 	if(isset($request->all()['turn_mant']))
		 		$turnoC = $request->all()['turn_mant'];

		 	if(isset($request->all()['tip_fal']))
		 		$tipoF = $request->all()['tip_fal'];

		 	if(isset($request->all()['coord']))
		 		$coor = $request->all()['coord'];

		 	if(isset($request->all()['obser']))
		 		$obser = $request->all()['obser'];

		 	if(isset($request->all()['tecno']))
		 		$tecno = $request->all()['tecno'];

		 	if(isset($request->all()['pote']))
		 		$pote = $request->all()['pote'];

		 	if(isset($request->all()['id_formato']))
		 		$id_formato = $request->all()['id_formato'];


		 	if($id_formato != "")
		 	{
		 		$dato = DB::Table($prefijoFinal . "_gop_auntogeneradas")
					->where('id_formato',$id_formato)
					->count();
		 	}
		 	else
		 	{
		 		$dato = DB::Table($prefijoFinal . "_gop_auntogeneradas")
					->where('id_user',$user)
					->where('id_rotulo',$rotulo)
					->where('id_pt',$pt)
					->where('tipo_cuad',$tipoC)
					->where('turno_cuad',$turnoC)
					->count();
		 	}

			if($dato > 0)
				return response()->json(1);

			$conse = DB::Table('gen_consecutivos')
			 			->where('id_campo','ID_ORDEN')
			 			->get()[0];

		 	$lnconsecutivo = $conse->consecutivo;
			$prefijo	   = $conse->prefijo;
			$longitud_max  = $conse->long_cadena;
			$relleno	   = $conse->caracter_relleno;

			$lnconsecutivo = $lnconsecutivo + 1 ;

			DB::Table('gen_consecutivos')
		 			->where('id_campo','ID_ORDEN')
		 			->update(array(
		 					'consecutivo' => $lnconsecutivo
		 				));

		 	$num_relleno 	= $longitud_max - strlen($prefijo) ; 
			$char_rellenos 	= self::lfillchar($lnconsecutivo,$num_relleno,$relleno) ;
			$ret            = $prefijo.$char_rellenos.$lnconsecutivo ; 


		 	$id_orden = $ret;
			$final_res = $ret;
			$id_tipo = 'T59';
			$fecha = date("Y-m-d");
			$incidencia = $ret;
			$nombre = 'AUTOGENERADA CAM';
			$barrio = $barrio;
			$localidad = $loca;
			$direccion = $dire;
			$direccion_danio = $dire;
			$observaciones = "AUTOGENERADA " . $tipoF . " " . $obser . " tecnologia " . $tecno . " " . $pote;
			$observaciones_orden = "AUTOGENERADA " . $tipoF . " " . $obser . " tecnologia " . $tecno . " " . $pote;
			$id_turno = $turnoC;
			$id_tipo_cuadrilla_sugerida = $tipoC;
			$tipo_falla = $tipoF;
			$rotulo_ref = $rotulo;
			$coor1 = explode(",",$coor);
			try
			{
				$x = $coor1[0];
				$y = $coor1[1];	
			}
			catch(Exception $e)
			{
				$x = "Error Envio X";
				$y = "Error Envio Y";
			}
			
			$fecha_ingreso = date("Y-m-d");
			$id_exclusion = 1;

			
			DB::Table($prefijoFinal . "_gop_ordenes")
				->insert(
						array(
							array(
									'id_origen' => $user,
									'id_orden' => $id_orden,
									'id_tipo' => $id_tipo,
									'fecha' => $fecha,
									'fecha_hora' => $fecha,
									'incidencia' => $incidencia,
									'nombre' => $nombre,
									'barrio' => $barrio,
									'localidad' => $localidad,
									'direccion' => $direccion,
									'direccion_danio' => $direccion,
									'observaciones' => $observaciones,
									'observaciones_orden' => $observaciones_orden,
									'id_turno' => $id_turno,
									'id_tipo_cuadrilla_sugerida' => $id_tipo_cuadrilla_sugerida,
									'tipo_falla' => $tipo_falla,
									'rotulo_ref' => $rotulo_ref,
									'x' => $x,
									'y' => $y,
									'fecha_ingreso' => $fecha_ingreso,
									'id_exclusion' => $id_exclusion
								)
							));
		
			
			DB::Table($prefijoFinal . "_gop_auntogeneradas")
				->insert(
						array(
							array(
									'id_user' => $user,
									'id_rotulo' => $rotulo,
									'id_pt' => $pt,
									'direccion' => $dire,
									'localidad' => $loca,
									'barrio' => $barrio,
									'corrdenadas' => $coor,
									'tipo_cuad' => $tipoC,
									'turno_cuad' => $id_turno,
									'tipo_falla' => $tipo_falla,
									'observaciones' => $obser,
									'tecnologia' => $tecno,
									'potencia' => $pote,
									'id_formato' => $id_formato,
									'id_orden' => $id_orden
								)
							));
		}	


		if($prefijoFinal == "ce8") //ce8
		{
			$user = "";
			$dire = "";
			$cliente = "";
			$telefono = "";
			$trafo = "";
			$coordenadas = "";
			$id_orden = "";
			$id_formato = "";
			$barrio = "";


			if(isset($request->all()['id_user']))
	 			$user = $request->all()['id_user'];
	 	
		 	if(isset($request->all()['direccion']))
		 		$dire = $request->all()['direccion'];

		 	if(isset($request->all()['cliente']))
		 		$cliente = $request->all()['cliente'];

		 	if(isset($request->all()['telefono']))
		 		$telefono = $request->all()['telefono'];

		 	if(isset($request->all()['trafo']))
		 		$trafo = $request->all()['trafo'];

		 	if(isset($request->all()['coord']))
		 		$coordenadas = $request->all()['coord'];

		 	if(isset($request->all()['id_formato']))
		 		$id_formato = $request->all()['id_formato'];

		 	if(isset($request->all()['barrio']))
		 		$barrio = $request->all()['barrio'];

		 	$dato = 0;

		 	if($id_formato != "")
		 	{
		 		$dato = DB::Table("ce8_gop_auntogeneradas")
					->where('id_formato',$id_formato)
					->count();
		 	}
		 	else
		 	{
		 		$dato = DB::Table("ce8_gop_auntogeneradas")
					->where('id_user',$user)
					->where('direccion',$dire)
					->where('nombre_cliente',$cliente)
					->where('telefono',$telefono)
					->where('trafo',$trafo)
					->count();
		 	}

			if($dato > 0)
				return response()->json(1);



		 	$conse = DB::Table('gen_consecutivos')
			 			->where('id_campo','ID_ORDEN')
			 			->get()[0];

		 	$lnconsecutivo = $conse->consecutivo;
			$prefijo	   = $conse->prefijo;
			$longitud_max  = $conse->long_cadena;
			$relleno	   = $conse->caracter_relleno;

			$lnconsecutivo = $lnconsecutivo + 1 ;

			DB::Table('gen_consecutivos')
		 			->where('id_campo','ID_ORDEN')
		 			->update(array(
		 					'consecutivo' => $lnconsecutivo
		 				));

		 	$num_relleno 	= $longitud_max - strlen($prefijo) ; 
			$char_rellenos 	= self::lfillchar($lnconsecutivo,$num_relleno,$relleno) ;
			$ret            = $prefijo.$char_rellenos.$lnconsecutivo ; 


		 	$id_orden = $ret;
			$final_res = $ret;
			$id_tipo = 'T59';
			$fecha = date("Y-m-d");
			$incidencia = $ret;
			$nombre = 'AUTOGENERADA CAM';
			$localidad = "";
			$direccion = $dire;
			$direccion_danio = $dire;
			$observaciones = "AUTOGENERADA Cliente " . $cliente . " telefono " . $telefono . " trafo " . $trafo;
			$observaciones_orden = "AUTOGENERADA Cliente " . $cliente . " telefono " . $telefono . " trafo " . $trafo;
			$id_turno = "";
			$id_tipo_cuadrilla_sugerida = "";
			$tipo_falla = "";
			$rotulo_ref = "";
			$coor1 = explode(",",$coordenadas);

			$x = "Error envio X";
			$y = "Error envio Y";
			if(count($coor1) > 1)
			{
				$x = $coor1[0];
				$y = $coor1[1];		
			}
			$fecha_ingreso = date("Y-m-d");

			DB::Table("ce8_gop_ordenes")
				->insert(
						array(
							array(
									'transformador' => $trafo,
									'telefono' => $telefono,
									'id_cliente' => $cliente,
									'id_origen' => $user,
									'id_orden' => $id_orden,
									'id_tipo' => $id_tipo,
									'fecha' => $fecha,
									'fecha_hora' => $fecha,
									'direccion' => $direccion,
									'incidencia' => $incidencia,
									'nombre' => $nombre,
									'barrio' => $barrio,
									'direccion' => $direccion,
									'observaciones' => $observaciones,
									'x' => $x,
									'y' => $y,
									'fecha_ingreso' => $fecha_ingreso
								)
							));

			DB::Table("ce8_gop_auntogeneradas")
				->insert(
						array(
							array(
									'id_user' => $user,
									'direccion' => $dire,
									'nombre_cliente' => $cliente,
									'telefono' => $telefono,
									'trafo' => $trafo,
									'barrio' => $barrio,
									'coordenadas' => $coordenadas,
									'id_orden' => $id_orden,
									'id_formato' => $id_formato,
									'fecha' => $fecha
								)
							));
		}

		return response()->json("1");
    }
}
