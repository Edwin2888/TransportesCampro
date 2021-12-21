<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Log;

class SolpedConfirm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:solpedConfirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando sincronizacion solped confirmacion';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("Iniciando proceso sincronizacion de solped");
        $oSolped = DB::connection('sqlsrvSap')->table('TB_INT_E_MM_SOLPED_CAB')->whereIn('status',[5,4])
        ->get();
        Log::info(count($oSolped)." registro encontrado(s)");
        if(count($oSolped) > 0){
            DB::connection('sqlsrvSap')->beginTransaction();
            try {
                DB::connection('sqlsrvSap')->table('TB_INT_E_MM_SOLPED_CAB')->whereIn('status',[5,4])
                ->update(['STATUS' => 6, 'TMS_STATUS' => DB::raw('CURRENT_TIMESTAMP')]);
                // $oSolped = $oSolped->get();
                $rInfo = new \Illuminate\Http\Request();
                $rInfo->oSolped = $oSolped;
                Log::info("Envia informacion");
                $response = self::setSolped($rInfo);
                if($response->getStatusCode() == '200'){
                    DB::connection('sqlsrvSap')->commit();
                    Log::info('Successful Synchronization');
                }else {
                    DB::connection('sqlsrvSap')->rollback();
                    Log::info('ERROR');
                    Log::info($response);
                }
            } catch (\Throwable $th) {
                DB::connection('sqlsrvSap')->rollback();
                Log::info('ERROR');
                Log::info($th);   
            }
        }
        Log::info("Fin proceso sincronizacion de solped");
    }
    public function setSolped(Request $request){
        $oSolped = collect($request->oSolped);
        Log::info('Inicio proceso actualizacion de solped');
        $nStatusCode = 200;
        DB::connection('sqlsrvCxParque')->beginTransaction();
        try {
            $nRowInsertC = 0;            
            $oSolped->each(function($item,$key)use(&$nRowInsertC){
                $sEstadoI = '4';
                $sEstadoA = 'E3';
                if($item->STATUS == '4'){
                    $sEstadoI = '3';
                    $sEstadoA = 'A1';
                }
                DB::connection('sqlsrvCxParque')->table('tra_incidencia_novedad')
                ->where('incidencia',$item->DOC_REFERENCIA)
                ->update(['solped' => $item->BANFN,'estado_id' => $sEstadoI]);

                DB::connection('sqlsrvCxParque')->table('tra_arrendamiento')
                ->where('id_documento',$item->DOC_REFERENCIA)
                ->update(['solped' => $item->BANFN, 'id_estado' => $sEstadoA]);
                
                $nRowInsertC++;
            });
            Log::info($nRowInsertC.' solped actualizada(s)');
            DB::connection('sqlsrvCxParque')->commit();
            $aEnvio = array(
                "status" => true,
                "message" => 'Sincronizacion exitosa'
            );
        } catch (\Throwable $th) {
            DB::connection('sqlsrvCxParque')->rollback();
            $nStatusCode = 500;
            $aEnvio = array(
                "status" => false,
                "message" => $th->getMessage()
            );
        }
        return response()->json($aEnvio, $nStatusCode);
    }
}
