<?php

namespace App\Http\Controllers\Supervision;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;

class ControllerWSWeb extends Controller
{

	/*****************************************************************************/
    /******** WEB SERVICES CAMPRO WEB PLAN DE SUPERVISIÓN - GUARDAR **************/
    /*****************************************************************************/   
    public function consultaWSGuardar(Request $request)
    {
    	$opc = $request->all()['opc'];
        $respuesta = 0;
        if($opc == 1) //Save datos de colaboradores
        {
            $lider = $request->all()['lider'];

            $equipo = $request->all()['equipo'];
            $anio = $request->all()['anio'];
            $mes = $request->all()['mes'];

            $cantExiste = DB::table('ins_lider_plan_supervision')
						->where('lider',$lider[0]['cc'])
						->where('anio',$anio)
						->where('mes',$mes)
						->count();

			
			if($cantExiste == 0)
			{
				DB::table('ins_lider_plan_supervision')
				->insert([
								'lider' => $lider[0]['cc'],
								'comportamiento' => $lider[0]['dato1'],
								'ipales' => $lider[0]['dato2'],
								'calidad' => $lider[0]['dato3'],
								'ambiental' => $lider[0]['dato4'],
                                'seguridad_obra_civil' => $lider[0]['dato5'],
                                'telecomunicaciones' => $lider[0]['dato6'],
                                'redes_electricas' => $lider[0]['dato7'],
                                'kit_manejo_derrames' => $lider[0]['dato8'],
                                'locativa_gestion_ambiental' => $lider[0]['dato9'],
                                'entrega_obra_civil' => $lider[0]['dato10'],
                                'restablecimiento_servicio' => $lider[0]['dato11'],
                                'mantenimiento' => $lider[0]['dato12'],
								'nombre_equipo' => $equipo,
								'anio' => $anio,
								'mes' => $mes
                ]);

			}
			else
			{
				DB::table('ins_lider_plan_supervision')
					->where('lider',$lider[0]['cc'])
					->where('anio',$anio)
					->where('mes',$mes)
					->update(
						array(
								'comportamiento' => $lider[0]['dato1'],
								'ipales' => $lider[0]['dato2'],
								'calidad' => $lider[0]['dato3'],
								'ambiental' => $lider[0]['dato4'],
                                'seguridad_obra_civil' => $lider[0]['dato5'],
                                'telecomunicaciones' => $lider[0]['dato6'],
                                'redes_electricas' => $lider[0]['dato7'],
                                'kit_manejo_derrames' => $lider[0]['dato8'],
                                'locativa_gestion_ambiental' => $lider[0]['dato9'],
                                'entrega_obra_civil' => $lider[0]['dato10'],
                                'restablecimiento_servicio' => $lider[0]['dato11'],
                                'mantenimiento' => $lider[0]['dato12'],
								'nombre_equipo' => $equipo
					));
			}
			

			$id = DB::table('ins_lider_plan_supervision')
					->where('lider',$lider[0]['cc'])
					->where('anio',$anio)
					->where('mes',$mes)
					->value('id');

			if(isset($request->all()['colaboradores']))
			{
				DB::table('ins_colaborador_plan_supervision')
					->where('lider',$lider[0]['cc'])
					->where('anio',$anio)
					->where('mes',$mes)
					->delete();

				$colaboradores = $request->all()['colaboradores'];
				for ($i=0; $i < count($colaboradores); $i++) { 
					
					DB::table('ins_colaborador_plan_supervision')
					->insert(array(
							array(
									'lider' => $lider[0]['cc'],
									'colaborador' => $colaboradores[$i]['cc'],
									'comportamiento' => $colaboradores[$i]['dato1'],
									'ipales' => $colaboradores[$i]['dato2'],
									'calidad' => $colaboradores[$i]['dato3'],
									'ambiental' => $colaboradores[$i]['dato4'],
                                    'seguridad_obra_civil' => $colaboradores[$i]['dato5'],
                                    'telecomunicaciones' =>$colaboradores[$i]['dato6'],
                                    'redes_electricas' => $colaboradores[$i]['dato7'],
                                    'kit_manejo_derrames' =>$colaboradores[$i]['dato8'],
                                    'locativa_gestion_ambiental' => $colaboradores[$i]['dato9'],
                                    'entrega_obra_civil' => $colaboradores[$i]['dato10'],
                                    'restablecimiento_servicio' => $colaboradores[$i]['dato11'],
                                    'mantenimiento' => $colaboradores[$i]['dato12'],
									'nombre_equipo' => $equipo,
									'anio' => $anio,
									'mes' => $mes
								)
						));

					//Consultar si existo como líder, y actualizar
					$cantExiste = DB::table('ins_lider_plan_supervision')
									->where('lider',$colaboradores[$i]['cc'])
									->where('anio',$anio)
									->where('mes',$mes)
									->count();

					if($cantExiste > 0)
					{
						//Actualizar como líder
						DB::table('ins_lider_plan_supervision')
								->where('lider',$colaboradores[$i]['cc'])
								->where('anio',$anio)
								->where('mes',$mes)
								->update(
									array(
										'comportamiento' => $colaboradores[$i]['dato1'],
										'ipales' => $colaboradores[$i]['dato2'],
										'calidad' => $colaboradores[$i]['dato3'],
										'ambiental' => $colaboradores[$i]['dato4'],
                                        'seguridad_obra_civil' => $colaboradores[$i]['dato5'],
                                        'telecomunicaciones' =>$colaboradores[$i]['dato6'],
                                        'redes_electricas' => $colaboradores[$i]['dato7'],
                                        'kit_manejo_derrames' =>$colaboradores[$i]['dato8'],
                                        'locativa_gestion_ambiental' => $colaboradores[$i]['dato9'],
                                        'entrega_obra_civil' => $colaboradores[$i]['dato10'],
                                        'restablecimiento_servicio' => $colaboradores[$i]['dato11'],
                                        'mantenimiento' => $colaboradores[$i]['dato12'],
									)
								);
					}


				}
			}


			$respuesta = $id;
    	}

    	return response()->json($respuesta);
    }
    /*****************************************************************************/
    /******** FIN  WEB SERVICES CAMPRO WEB PLAN DE SUPERVISIÓN - GUARDAR **************/
    /*****************************************************************************/   

    
    /*****************************************************************************/
    /******** WEB SERVICES CAMPRO WEB PLAN DE SUPERVISIÓN - CONSULTA **************/
    /*****************************************************************************/   
    public function consultaWSWebConsulta(Request $request)
    {
    	$opc = $request->all()['opc'];

    	if($opc == 1) //Consulta integrantes del grupo y validar si esta en otro equipo PARA INTEGRANTES
    	{
    		$nombre = $request->all()["nombre"];
            $ced = $request->all()["ced"];
            $lider = $request->all()["lider"];
            
            $fechits = date('Y-m-01');

            $consulta = "select rh.identificacion,rh.nombres,rh.apellidos,rh.domicilio,rh.telefono1,REPLACE(REPLACE(rh.correo,char(10),''),char(13),'') as correo,
				super.nombre_equipo, (select rhLider.nombres + ' ' + rhLider.apellidos from rh_personas as rhLider where rhLider.identificacion = super.lider) as nombreOtroLider
				,lid.lider as lid
				from rh_personas as rh
				LEFT JOIN ins_colaborador_plan_supervision super ON rh.identificacion = super.colaborador  and CONVERT(DATE,concat(super.anio,'-',super.mes,'-01'))>='".$fechits."'
				and super.lider <> '" . $lider . "'

				LEFT JOIN ins_lider_plan_supervision lid ON lid.lider = rh.identificacion

                WHERE identificacion LIKE '%" .  $ced. "%' AND  id_estado = 'EP03' AND (nombres + ' ' + apellidos) LIKE '%" .  $nombre. "%' 
                GROUP BY lid.lider,rh.identificacion,rh.nombres,rh.apellidos,rh.domicilio,rh.telefono1,rh.correo,super.nombre_equipo,super.lider
                ORDER BY nombres";

            $conductores = DB::select($consulta);

            return response()->json(view('proyectos.transporte.tables.tblConductoresVehiculos',
                array(
                    "conductores" => $conductores,
                    "opc" => 1
                ))->render());
    	}

    	if($opc == 2) //Consulta integrantes del grupo y validar si esta en otro equipo PARA LIDERES
    	{
    		$nombre = $request->all()["nombre"];
            $ced = $request->all()["ced"];

            $consulta = "select rh.identificacion,rh.nombres,rh.apellidos,rh.domicilio,rh.telefono1,REPLACE(REPLACE(rh.correo,char(10),''),char(13),'') as correo,
				lider.nombre_equipo
				from rh_personas as rh
				LEFT JOIN ins_lider_plan_supervision lider ON
                                
                                (
                                  rh.identificacion = lider.lider and 
                                  lider.anio = convert(INT,YEAR ( getDate())) and 
                                  lider.mes = convert(INT, MONTH ( getDate()))
                                 )

                WHERE identificacion LIKE '%" .  $ced. "%' AND  id_estado = 'EP03' AND (nombres + ' ' + apellidos) LIKE '%" .  $nombre. "%' 
                    
                GROUP BY rh.identificacion,rh.nombres,rh.apellidos,rh.domicilio,rh.telefono1,rh.correo,lider.nombre_equipo
                ORDER BY nombres";

            $conductores = DB::select($consulta);

            return response()->json(view('proyectos.transporte.tables.tblConductoresVehiculos',
                array(
                    "conductores" => $conductores,
                    "opc" => 2
                ))->render());
    	}

    	if($opc == 3) //Consulta si ya existe este equipo configurado
    	{
    		$mes = $request->all()["mes"];
            $id = $request->all()["id"];
            $anio = $request->all()["anio"];
            $lider = $request->all()["lider"];

            Session::flash("mesmayor",$mes);
            Session::flash("aniomayor",$anio);         

            $idConsulta = DB::table('ins_lider_plan_supervision')
            				->where('lider',$lider)
            				->where('anio',$anio)
            				->where('mes',$mes)
            				->value('id');

            $datosMayores = DB::table('ins_lider_plan_supervision')
            				->where('lider',$lider)
            				->select(DB::raw("MAX(anio) as anio"))
            				->get()[0];

            $datosMayoresMes = DB::table('ins_lider_plan_supervision')
            				->where('lider',$lider)
            				->where('anio',$datosMayores->anio)
            				->select(DB::raw("MAX(mes) as mes"))
            				->get()[0];

            $idMayor = DB::table('ins_lider_plan_supervision')
            				->where('lider',$lider)
            				->where('anio',$datosMayores->anio)
            				->where('mes',$datosMayoresMes->mes)
            				->value('id');


            return response()->json(["res" => ($idConsulta == NULL ? "" : $idConsulta),"idMayor" => $idMayor ]);
    	}
    }
    /*****************************************************************************/
    /******** FIN  WEB SERVICES CAMPRO WEB PLAN DE SUPERVISIÓN - CONSULTA **************/
    /*****************************************************************************/   
}

/*

Modificación: Alejandra Quintero
FEcha: 01-12-2019
Descripción: Se comentaria este código por que esta duplicado 

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Session;

class ControllerWSWeb extends Controller
{
    //WS Web que permiten realizar consultas AJAX
    public function consultaWSWeb(Request $request)
    {
    	$opc = $request->all()['opc'];
    }

    //WS Web que permiten realizar save AJAX
    public function consultaWSGuardar(Request $request)
    {
    	$opc = $request->all()['opc'];
    	$respuesta = 0;
    	if($opc == 1) //Save datos de colaboradores
    	{
			$lider = $request->all()['lider'];

			$equipo = $request->all()['equipo'];
			$anio = $request->all()['anio'];
			$mes = $request->all()['mes'];

			$cantExiste = DB::table('ins_lider_plan_supervision')
						->where('lider',$lider[0]['cc'])
						->where('anio',$anio)
						->where('mes',$mes)
						->count();
			
			
			if($cantExiste == 0)
			{
				DB::table('ins_lider_plan_supervision')
				->insert(array(
						array(
								'lider' => $lider[0]['cc'],
								'comportamiento' => $lider[0]['dato1'],
								'ipales' => $lider[0]['dato2'],
								'calidad' => $lider[0]['dato3'],
								'ambiental' => $lider[0]['dato4'],
								'nombre_equipo' => $equipo,
								'anio' => $anio,
								'mes' => $mes
							)
					));
			}
			else
			{
				DB::table('ins_lider_plan_supervision')
					->where('lider',$lider[0]['cc'])
					->where('anio',$anio)
					->where('mes',$mes)
					->update(
						array(
								'comportamiento' => $lider[0]['dato1'],
								'ipales' => $lider[0]['dato2'],
								'calidad' => $lider[0]['dato3'],
								'ambiental' => $lider[0]['dato4'],
								'nombre_equipo' => $equipo
					));
			}
			

			$id = DB::table('ins_lider_plan_supervision')
					->where('lider',$lider[0]['cc'])
					->where('anio',$anio)
					->where('mes',$mes)
					->value('id');

			if(isset($request->all()['colaboradores']))
			{
				DB::table('ins_colaborador_plan_supervision')
					->where('lider',$lider[0]['cc'])
					->where('anio',$anio)
					->where('mes',$mes)
					->delete();

				$colaboradores = $request->all()['colaboradores'];
				for ($i=0; $i < count($colaboradores); $i++) { 
					DB::table('ins_colaborador_plan_supervision')
					->insert(array(
							array(
									'lider' => $lider[0]['cc'],
									'colaborador' => $colaboradores[$i]['cc'],
									'comportamiento' => $colaboradores[$i]['dato1'],
									'ipales' => $colaboradores[$i]['dato2'],
									'calidad' => $colaboradores[$i]['dato3'],
									'ambiental' => $colaboradores[$i]['dato4'],
									'nombre_equipo' => $equipo,
									'anio' => $anio,
									'mes' => $mes
								)
						));
				}
			}


			$respuesta = $id;
    	}

    	return response()->json($respuesta);
    }


    //WS Web que permiten consulta AJAX
    public function consultaWSWebConsulta(Request $request)
    {
    	$opc = $request->all()['opc'];

    	if($opc == 1) //Consulta integrantes del grupo y validar si esta en otro equipo
    	{
    		$nombre = $request->all()["nombre"];
            $ced = $request->all()["ced"];
            $lider = $request->all()["lider"];

            $consulta = "select rh.identificacion,rh.nombres,rh.apellidos,rh.domicilio,rh.telefono1,rh.correo,
				super.nombre_equipo, (select rhLider.nombres + ' ' + rhLider.apellidos from rh_personas as rhLider where rhLider.identificacion = super.lider) as nombreOtroLider
				,lid.lider as lid
				from rh_personas as rh
				LEFT JOIN ins_colaborador_plan_supervision super ON rh.identificacion = super.colaborador
				and super.lider <> '" . $lider . "'

				LEFT JOIN ins_lider_plan_supervision lid ON lid.lider = rh.identificacion

                WHERE identificacion LIKE '%" .  $ced. "%' AND  id_estado = 'EP03' AND (nombres + ' ' + apellidos) LIKE '%" .  $nombre. "%' 
                GROUP BY lid.lider,rh.identificacion,rh.nombres,rh.apellidos,rh.domicilio,rh.telefono1,rh.correo,super.nombre_equipo,super.lider
                ORDER BY nombres";

            $conductores = DB::select($consulta);

            return response()->json(view('proyectos.transporte.tables.tblConductoresVehiculos',
                array(
                    "conductores" => $conductores,
                    "opc" => 1
                ))->render());
    	}

    	if($opc == 2) //Consulta integrantes del grupo y validar si esta en otro equipo
    	{
    		$nombre = $request->all()["nombre"];
            $ced = $request->all()["ced"];

            $consulta = "select rh.identificacion,rh.nombres,rh.apellidos,rh.domicilio,rh.telefono1,rh.correo,
				lider.nombre_equipo
				from rh_personas as rh
				LEFT JOIN ins_lider_plan_supervision lider ON rh.identificacion = lider.lider
                WHERE identificacion LIKE '%" .  $ced. "%' AND  id_estado = 'EP03' AND (nombres + ' ' + apellidos) LIKE '%" .  $nombre. "%' 
                GROUP BY rh.identificacion,rh.nombres,rh.apellidos,rh.domicilio,rh.telefono1,rh.correo,lider.nombre_equipo
                ORDER BY nombres";

            $conductores = DB::select($consulta);

            return response()->json(view('proyectos.transporte.tables.tblConductoresVehiculos',
                array(
                    "conductores" => $conductores,
                    "opc" => 2
                ))->render());
    	}

    	if($opc == 3) //Consulta si ya existe este equipo configurado
    	{
    		$mes = $request->all()["mes"];
            $id = $request->all()["id"];
            $anio = $request->all()["anio"];
            $lider = $request->all()["lider"];

            Session::flash("mesmayor",$mes);
            Session::flash("aniomayor",$anio);


            $idConsulta = DB::table('ins_lider_plan_supervision')
            				->where('lider',$lider)
            				->where('anio',$anio)
            				->where('mes',$mes)
            				->value('id');

            $datosMayores = DB::table('ins_lider_plan_supervision')
            				->where('lider',$lider)
            				->select(DB::raw("MAX(anio) as anio"),DB::raw("MAX(mes) as mes"))
            				->get()[0];


            $idMayor = DB::table('ins_lider_plan_supervision')
            				->where('lider',$lider)
            				->where('anio',$datosMayores->anio)
            				->where('mes',$datosMayores->mes)
            				->value('id');


            return response()->json(["res" => ($idConsulta == NULL ? "" : $idConsulta),"idMayor" => $idMayor ]);
    	}


    }
}
*/