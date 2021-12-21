<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;

class Services extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triggers:services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar servicios';

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
        Log::info("Iniciando proceso sincronizacion servicios");
        $query = "SELECT d1.ASNUM as numero_servicio,r.tipo_servicio,r.descripcion_servicio,r.grupo_articulos
        FROM TB_INT_S_MM_SERVICIOS d1
        CROSS APPLY( 
           SELECT TOP 1 ASTYP as tipo_servicio,ASKTX as descripcion_servicio,MATKL as grupo_articulos
           FROM TB_INT_S_MM_SERVICIOS d2
           WHERE d2.ASNUM = d1.ASNUM
           ORDER BY TMS_INSERT DESC
        ) AS r
        WHERE STATUS = '1'
        GROUP BY d1.ASNUM,r.tipo_servicio,r.descripcion_servicio,r.grupo_articulos";
        $oServices = DB::connection('sqlsrvSap')->Select($query);
        $oServices = collect($oServices);
        Log::info(count($oServices)." registro encontrado(s)");
        if(count($oServices) > 0){
            DB::connection('sqlsrvSap')->beginTransaction();
            try {
                DB::connection('sqlsrvSap')->table('TB_INT_S_MM_SERVICIOS')->where('status',1)
                ->update(['STATUS' => 5, 'TMS_STATUS' => DB::raw('CURRENT_TIMESTAMP')]);

                $rData = new \Illuminate\Http\Request();                
                $rData->oServices = $oServices;
                Log::info("Envia informacion");
                $response = self::setServices($rData);
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
    public function setServices(Request $request){
        $nStatusCode = 200;
        $oServices = collect($request->oServices);
        Log::info('Inicio proceso providers');
        $oLog = [];
        if(!is_null($oServices)){
            DB::connection('sqlsrvCxParque')->beginTransaction();
            try {
                $oServices->chunk(524)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios')->insert(json_decode($item, true));                
                });
                
                // Actualizamos los registros que ya existen
                DB::connection('sqlsrvCxParque')->table('tra_servicios as hijo')
                ->join('tra_proveedores_vehiculos_servicios as padre','padre.numero_servicio','=','hijo.numero_servicio')
                ->update(['hijo.tipo_servicio' => DB::raw('padre.tipo_servicio'), 'hijo.descripcion' => DB::raw('padre.descripcion_servicio'),
                'hijo.grupo_articulos' => DB::raw('padre.grupo_articulos')]);
                // Insertamos los registros
                $oService = DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios as padre')
                ->leftJoin('tra_servicios as hijo','padre.numero_servicio','=','hijo.numero_servicio')
                ->whereNull('hijo.numero_servicio')
                ->select('padre.tipo_servicio','padre.descripcion_servicio as descripcion','padre.grupo_articulos',
                'padre.numero_servicio')
                ->get();

                $oService = collect($oService);
                $oService->chunk(524)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_servicios')
                    ->insert(json_decode($item, true)
                    );
                });

                DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios')->delete();

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
