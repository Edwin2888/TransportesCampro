<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Redirect;
use Session;
use Carbon\Carbon;
class ControllerManuales extends Controller
{
    //
    function __construct() {    
        $this->fechaA = Carbon::now('America/Bogota');
        $this->fechaALong = $this->fechaA->toDateTimeString();   
        $this->fechaShort = $this->fechaA->format('Y-m-d');
    }

    public function index()
    {
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

        $manualesCabeza = DB::table('gop_manual')
        			->select('id_proyecto')
        			->orderBy('id_proyecto')
        			->groupBy('id_proyecto')
        			->get();

        $manualesCuerpo = DB::table('gop_manual')
        			->select('id_proyecto','id_manual','titulo','descripcion','embebido','estado','version','tipo','fecha_servidor_creacion','fecha_servidor_update')
        			->orderBy('id_proyecto','tipo')
        			->get();

    	return view('proyectos.manuales.index',
    		array('proyecto' => $proyecto,
    		'proyectosSeleccionado' => $manualesCabeza,
    		'proyectosManual' => $manualesCuerpo));
    }

    public function addManual()
    {
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

        $manuales = DB::table('gop_manual')
                    ->select('codigo','id_proyecto','id_manual','titulo','descripcion','embebido','estado','version','tipo','fecha_servidor_creacion','fecha_servidor_update');

        if(Session::has('estado'))
        {
            if(Session::get('nombre_manual') != "")
                $manuales = $manuales->where('titulo','LIKE','%' . Session::get('nombre_manual') . '%');

            if(Session::get('estado') != "-1")
                $manuales = $manuales->where('id_proyecto', Session::get('estado'));

            if(Session::get('tipo') != "0")
                $manuales = $manuales->where('tipo', Session::get('tipo'));

        }   
        
        $manuales = $manuales->orderBy('id_proyecto')
                    ->get();

    	return view('proyectos.manuales.create',array(
    		'proyecto' => $proyecto,
    		'manuales' => $manuales
    		));
    }


    public function guardaManual(Request $request)
    {
    	$id = $request->all()["manual_id"];
    	$proy = $request->all()["id_proyecto_add"];
    	$titulo = $request->all()["txtTituloManual"];
    	$desc = $request->all()["txtDescripcionManual"];
    	$embe = $request->all()["txtEmbebidoManial"];
        $ver = $request->all()["txtversion"];
        $tip = $request->all()["select_tipo_manual"];
        $cod = $request->all()["txtcodigo"];

        
    	if($id == "0") //Insert
    	{
    		DB::table('gop_manual')
				->insert(array(
					array(
						'id_proyecto' => $proy,
						'titulo' => $titulo,
						'descripcion' => $desc,
						'embebido' => $embe,
                        'version' => $ver,
                        'tipo' => $tip,
                        'fecha_servidor_creacion' => $this->fechaALong,
                        'fecha_servidor_update' => $this->fechaALong,
                        'codigo' => $cod
						)));

    		Session::flash('dataExcel1',"Se ha creado correctamente el manual.");
    	}else //Update
    	{
    		DB::table('gop_manual')
    			->where('id_manual',$id)
				->update(
					array(
						'id_proyecto' => $proy,
						'titulo' => $titulo,
						'descripcion' => $desc,
						'embebido' => $embe,
                        'version' => $ver,
                        'tipo' => $tip,
                        'fecha_servidor_update' => $this->fechaALong,
                        'codigo' => $cod
						));

			Session::flash('dataExcel1',"Se ha actualizado correctamente el manual.");
    	}
    	
    	return Redirect::to('manuales/add');

    	
    }

    public function filterManual(Request $request)
    {
        $estado = $request->all()["id_estado"];
        $nombre_manual = $request->all()["nombre_manual"];
        $tipo = $request->all()["select_filter_tipo_manual"];

        Session::flash('estado',$estado);
        Session::flash('nombre_manual',$nombre_manual);
        Session::flash('tipo',$tipo);

        return Redirect::to('/manuales/add');
    }

}
