<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use DB;
use Carbon\Carbon;
use Redirect;

class ControllerSolped extends Controller
{
    public function exporteErrores(Request $request){
        \Excel::create('Exporte errores arrendamientos ', function($excel) use($request) {            
            $excel->sheet('Errores Solped', function($sheet) use($request){
                $row = 1;

                $sheet->row($row, array(
                    'Placa', 'Grupo de compras ', 'Estado del contratante','Centro Logistico','Sociedad','Numero de servico','Numero de activo fijo','Sub numero de activo fijo','Numero de contrato','Cuenta de proveedor','Organizacion de compras','Estado del propietario','Descripcion de servcio','Grupo de articulos','Elemento Pep'
                ));
                $errores = DB::connection('sqlsrvCxParque')
                ->table('tra_errores_solped')
                ->where('id',$request->valorExcel)
                ->get();

                $row++;
                foreach ($errores as $o) {
                    $sheet->row($row, array(
                        $o->placa,$o->grupo_compras,$o->contratanteEstado,$o->centro_logistico,$o->sociedad,
                        $o->numero_servicio,$o->numero_activo_fijo,$o->subnumero_activo_fijo,$o->numero_contrato_vehiculo,
                        $o->cuenta_proveedor,$o->org_compras,$o->propietarioEstado,$o->descripcion,
                        $o->grupo_articulos,$o->codigo_pep));
                    // $sheet->setColumnFormat(array(
                    //     'F'.$row => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE
                    // ));
                    $row++;
                }
            });
        })->export('xlsx');
    }
}
