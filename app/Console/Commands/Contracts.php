<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;

class Contracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:contracts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizacion de contratos';

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
        Log::info("Iniciando proceso sincronizacion contratos");
        $sQuery = "SELECT d1.EBELN as numero_contrato,r.sociedad,r.proveedor,r.organizacion_compras,r.moneda,r.inicio_validez,r.fin_validez,
		r.suministro_completo,valor_previsto
        FROM TB_INT_S_MM_CONTRATOS_CAB d1
        CROSS APPLY( 
           SELECT TOP 1 BUKRS as sociedad, LIFNR as proveedor,
		   EKORG as organizacion_compras, WAERS as moneda,
		   KDATB as inicio_validez,KDATE as fin_validez,
		   AUTLF as suministro_completo, KTWRT as valor_previsto
           FROM TB_INT_S_MM_CONTRATOS_CAB d2
           WHERE d2.LIFNR = d1.LIFNR
           ORDER BY TMS_INSERT DESC
        ) AS r
        WHERE STATUS = '1'
        GROUP BY d1.EBELN,r.sociedad, r.proveedor,r.organizacion_compras,r.moneda,r.inicio_validez,r.fin_validez,
        r.suministro_completo,valor_previsto";
        $oContracts = DB::connection('sqlsrvSap')->Select($sQuery);
        $oContracts = collect($oContracts);
        Log::info(count($oContracts)." registro encontrado(s)");
        if(count($oContracts) > 0){
            DB::connection('sqlsrvSap')->beginTransaction();
            try {
                DB::connection('sqlsrvSap')->table('TB_INT_S_MM_CONTRATOS_CAB')->where('status',1)
                ->update(['STATUS' => 5, 'TMS_STATUS' => DB::raw('CURRENT_TIMESTAMP')]);

                $rData = new \Illuminate\Http\Request();                
                $rData->oContracts = $oContracts;
                Log::info("Envia informacion");
                $response = self::setContracts($rData);
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
                // $asunto = "Error sincronización ".$sNombreProceso;
                // $correo = config("app.MailsError");
                // $correoCc = config("app.MailsErrorCc");
                // Mail::send('Mail.IntegratorErrors',compact('th','sNombreProceso'), function($mail) use($asunto,$correo,$correoCc){
                //     $mail->from("colombiacam7@gmail.com","Engie Communications");
                //     $mail->subject($asunto);
                //     $mail->cc($correoCc);
                //     $mail->to($correo);
                // });
                
            }
        }
    }
    public function setContracts(Request $request){
        $nStatusCode = 200;
        $oContracts = collect($request->oContracts);
        Log::info('Inicio proceso contracts');
        $oLog = [];
        if(!is_null($oContracts)){
            DB::connection('sqlsrvCxParque')->beginTransaction();
            try {
                $oContracts->chunk(222)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_contratos_cab_sync')->insert(json_decode($item, true));
                    $oDocs = '';
                    $j = 0;
                    $item->each(function($i)use(&$oDocs,&$j){
                        if($j == 0){
                            $oDocs .= "'".$i->numero_contrato."'";
                        }else{
                            $oDocs .= ",'".$i->numero_contrato."'";
                        }
                        $j++;
                    });
                    $sQuery = "SELECT d1.EBELN as numero_contrato, d1.EBELP as posicion,r.id_articulo,r.texto_material,r.cantidad_prevista,
                    r.id_unidad_material,r.precio_material,r.numero_servicio,
                    r.cantidad_servicio,id_unidad_servicio,r.precio_servicio,r.valor_servicio,r.centro_logistico
                    FROM TB_INT_S_MM_CONTRATOS_DET d1
                    CROSS APPLY( 
                       SELECT TOP 1 MATNR as id_articulo, TXZ01 as texto_material,
                       KTMNG as cantidad_prevista, MEINS as id_unidad_material,
                       BRTWR as precio_material,SRVPOS as numero_servicio,
                       KTEXT1 as texto_servicio, MENGE as cantidad_servicio,
                       MEINS_S as id_unidad_servicio,NETWR as precio_servicio, BRTWR_S as valor_servicio,
                       PLANT as centro_logistico
                       FROM TB_INT_S_MM_CONTRATOS_DET d2
                       WHERE d2.EBELN = d1.EBELN AND d2.EBELP = d1.EBELP
                       ORDER BY TMS_INSERT DESC
                    ) AS r
                    WHERE EBELN IN ({$oDocs})
                    GROUP BY d1.EBELN,d1.EBELP,r.id_articulo,r.texto_material,r.cantidad_prevista,
                    r.id_unidad_material,r.precio_material,r.numero_servicio,
                    r.cantidad_servicio,id_unidad_servicio,r.precio_servicio,r.valor_servicio,r.centro_logistico";
                    $oContractsDet = DB::connection('sqlsrvSap')->Select($sQuery);
                    $oContractsDet = collect($oContractsDet);
                    $oContractsDet->chunk(153)->each(function($h){
                        DB::connection('sqlsrvCxParque')->table('tra_contratos_det_sync')
                        ->insert(json_decode($h, true));
                    });
                });
                // Contratos cab
                // Actualizamos los registros que ya existen
                DB::connection('sqlsrvCxParque')->table('tra_contratos_cab as hijo')
                ->join('tra_contratos_cab_sync as padre','padre.numero_contrato','=','hijo.numero_contrato')
                ->update(['hijo.sociedad' => DB::raw('padre.sociedad'), 'hijo.proveedor' => DB::raw('padre.proveedor'),
                'hijo.organizacion_compras' => DB::raw('padre.organizacion_compras'),'hijo.moneda' => DB::raw('padre.moneda'),
                'hijo.inicio_validez' => DB::raw('padre.inicio_validez') ,'hijo.fin_validez' => DB::raw('padre.fin_validez'),
                'hijo.suministro_completo' => DB::raw('padre.suministro_completo') ,'hijo.valor_previsto' => DB::raw('padre.valor_previsto')]);
                // Insertamos los registros
                $oContracts = DB::connection('sqlsrvCxParque')->table('tra_contratos_cab_sync as padre')
                ->leftJoin('tra_contratos_cab as hijo','padre.numero_contrato','=','hijo.numero_contrato')
                ->whereNull('hijo.numero_contrato')
                ->select('padre.*')
                ->get();

                $oContracts = collect($oContracts);
                $oContracts->chunk(222)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_contratos_cab')
                    ->insert(json_decode($item, true)
                    );
                });
                // Contratos det
                // Actualizamos los registros que ya existen
                DB::connection('sqlsrvCxParque')->table('tra_contratos_det as hijo')
                ->join('tra_contratos_det_sync as padre',function($q){
                    $q->on('padre.numero_contrato','=','hijo.numero_contrato')
                    ->on('padre.posicion','=','hijo.posicion');
                })
                ->update(['hijo.id_articulo' => DB::raw('padre.id_articulo'), 'hijo.texto_material' => DB::raw('padre.texto_material'),
                'hijo.cantidad_prevista' => DB::raw('padre.cantidad_prevista'),'hijo.id_unidad_material' => DB::raw('padre.id_unidad_material'),
                'hijo.precio_material' => DB::raw('padre.precio_material') ,'hijo.numero_servicio' => DB::raw('padre.numero_servicio'),
                'hijo.id_unidad_servicio' => DB::raw('padre.id_unidad_servicio') ,'hijo.texto_servicio' => DB::raw('padre.texto_servicio'),
                'hijo.cantidad_servicio' => DB::raw('padre.cantidad_servicio') ,'hijo.precio_servicio' => DB::raw('padre.precio_servicio'),
                'hijo.valor_servicio' => DB::raw('padre.valor_servicio'),'hijo.centro_logistico' => DB::raw('padre.centro_logistico')]);
                // Insertamos los registros
                $oContractsDet = DB::connection('sqlsrvCxParque')->table('tra_contratos_det_sync as padre')
                ->leftJoin('tra_contratos_det as hijo',function($q){
                    $q->on('padre.numero_contrato','=','hijo.numero_contrato')
                    ->on('padre.posicion','=','hijo.posicion');
                })
                ->whereNull('hijo.numero_contrato')
                ->whereNull('hijo.posicion')
                ->select('padre.*')
                ->get();

                $oContractsDet = collect($oContractsDet);
                $oContractsDet->chunk(222)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_contratos_det')
                    ->insert(json_decode($item,true)
                    );
                });
                DB::connection('sqlsrvCxParque')->table('tra_contratos_cab_sync')->delete();
                DB::connection('sqlsrvCxParque')->table('tra_contratos_det_sync')->delete();

                DB::connection('sqlsrvCxParque')->commit();
                $aEnvio = array(
                    "status" => true,
                    "message" => 'Sincronizacion exitosa'
                );
            } catch (\Throwable $th) {
                Log::info($th);
                DB::connection('sqlsrvCxParque')->rollback();
                $nStatusCode = 500;
                $aEnvio = array(
                    "status" => false,
                    "message" => $th->getMessage()
                );
            }
        }
        return response()->json($aEnvio, $nStatusCode);
    }
}
