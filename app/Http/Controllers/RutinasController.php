<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Rutina;
use Redirect; //para redirigir pagina
use DB; //para usar BD
use Illuminate\Database\Console\Migrations\RefreshCommand;
use Session; //Para las sesiones
use Response;

class RutinasController extends Controller
{
    public function index()
    {
        $rutinas = DB::connection('sqlsrvCxParque')
            ->table('tra_rutinas')
            ->get();
        if (empty($rutinas)) {
            return view('proyectos.transporte.rutinas.createRutina');
        } else {
            return view('proyectos.transporte.rutinas.rutinas', array("rutinas" => $rutinas));
        }
    }

    public function create()
    {
        return view('proyectos.transporte.rutinas.createRutina');
    }

    public function guardanuevo(Request $request)
    {

        $datos = array();


        if (isset($request->all()['id_rutina'])) {
            $datos['id_rutina'] = trim($request->all()['id_rutina']);
        }

        if (isset($request->all()['nombre'])) {
            $datos['nombre'] = trim($request->all()['nombre']);
        }

        if (isset($request->all()['descripcion'])) {
            $datos['descripcion'] = trim($request->all()['descripcion']);
        }

        DB::connection('sqlsrvCxParque')->table('tra_detalle_rutina')->insert(array($datos));

        $rutinas = DB::connection('sqlsrvCxParque')
            ->table('tra_rutinas')
            ->get();
        if (empty($rutinas)) {
            return view('proyectos.transporte.rutinas.createRutina');
        } else {
            return view('proyectos.transporte.rutinas.rutinas', array("rutinas" => $rutinas));
        }
    }

    public function edit(Request $request)
    {
        $rutinas = DB::connection('sqlsrvCxParque')
            ->table('tra_rutinas')
            ->where('id_rutina', $request->id_rutina)
            ->get();

            $detalles = DB::connection('sqlsrvCxParque')
            ->table('tra_detalle_rutina')
            ->where('id_rutina',$request->id_rutina)
            ->get();

        return view('proyectos.transporte.rutinas.editRutina', array("rutinas" => $rutinas, "detalles" => $detalles));
    }

    public function store(Request $request)
    {
        $data_rutina = array(
            'nombre' => $request->nombre,
            'ciclo'  => $request->ciclo
        );
        \DB::connection('sqlsrvCxParque')->table('tra_rutinas')->insert(array($data_rutina));
        $rutinas = DB::connection('sqlsrvCxParque')
            ->table('tra_rutinas')
            ->get();
            $mensaje = 'Registro completado';
        return view('proyectos.transporte.rutinas.rutinas', array("rutinas" => $rutinas));
    }
}
