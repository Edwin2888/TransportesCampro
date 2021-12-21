<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;


class ControllerKmChevystar extends Controller
{
    public function indexKm()
    { 
        $tabla = '';
        return view('proyectos.chevystar.index', ['tabla' => $tabla]);
    }

    public function consultaKm(Request $request)
    {
        //dd($request);
        $condicion = '';
        if ($request->placa != '') {
            $condicion .= " and placa = '".$request->placa."'";
        }
        if ($request->fechaInicio != '') {
            $condicion .= " and fecha between '".$request->fechaInicio."' and '".$request->fechaFin."'";
        }

        $sql = DB::connection('sqlsrvCxParque')->table('km_chevystar')
        ->select(DB::raw(' row_number() over (order by (select NULL)) as num'),'kilometraje','placa', 'fecha')
        ->whereRaw('1 = 1 '.$condicion)->get();

        //$sql = DB::table('km_chevystar')->where(' 1 = 1 '.$condicion )->get();
        
        return view('proyectos.chevystar.index',['tabla' => $sql]);
    }
}
