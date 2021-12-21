<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;

class Providers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trigger:providers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar proveedores en propietarios y talleres';

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
        Log::info("Iniciando proceso sincronizacion proveedores");
        $query = "SELECT d1.LIFNR as cuenta_proveedor,r.organizacion_compras,r.nombre_proveedor,r.bloqueo_compras,r.direccion,r.telefono
        FROM TB_INT_S_MM_PROVEEDORES_FLOTA d1
        CROSS APPLY( 
           SELECT TOP 1 EKORG as organizacion_compras, NAME1 as nombre_proveedor,
		   SPERM as bloqueo_compras, SUBSTRING(CONCAT(STREET,STR_SUPPL1,STR_SUPPL2),1,50)as direccion,
		   TEL_NUMBER as telefono
           FROM TB_INT_S_MM_PROVEEDORES_FLOTA d2
           WHERE d2.LIFNR = d1.LIFNR
           ORDER BY TMS_INSERT DESC
        ) AS r
        WHERE STATUS = '1'
        GROUP BY d1.LIFNR,r.organizacion_compras, r.nombre_proveedor,r.bloqueo_compras,r.direccion,r.telefono";
        $oProviders = DB::connection('sqlsrvSap')->Select($query);
        $oProviders = collect($oProviders);
        Log::info(count($oProviders)." registro encontrado(s)");
        if(count($oProviders) > 0){
            DB::connection('sqlsrvSap')->beginTransaction();
            try {
                DB::connection('sqlsrvSap')->table('TB_INT_S_MM_PROVEEDORES_FLOTA')->where('status',1)
                ->update(['STATUS' => 5, 'TMS_STATUS' => DB::raw('CURRENT_TIMESTAMP')]);

                $rData = new \Illuminate\Http\Request();                
                $rData->oProviders = $oProviders;
                Log::info("Envia informacion");
                $response = self::setProviders($rData);
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
    public function setProviders(Request $request){
        $nStatusCode = 200;
        $oProviders = collect($request->oProviders);
        Log::info('Inicio proceso providers');
        $oLog = [];
        if(!is_null($oProviders)){
            DB::connection('sqlsrvCxParque')->beginTransaction();
            try {
                $oProviders->chunk(349)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios')->insert(json_decode($item, true));                
                });
                // Propietarios
                // Actualizamos los registros que ya existen
                DB::connection('sqlsrvCxParque')->table('tra_propietarios as hijo')
                ->join('tra_proveedores_vehiculos_servicios as padre','padre.cuenta_proveedor','=','hijo.cuenta_proveedor')
                ->update(['hijo.nombre' => DB::raw('padre.nombre_proveedor'), 'hijo.domicilio' => DB::raw('padre.direccion'),
                'hijo.telefonoFijo' => DB::raw('padre.telefono'),'hijo.org_compras' => DB::raw('padre.organizacion_compras'),
                'hijo.bloqueo_compras' => DB::raw('padre.bloqueo_compras') ,'hijo.id_estado' => 'A']);
                // Insertamos los registros
                $oPropietarios = DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios as padre')
                ->leftJoin('tra_propietarios as hijo','padre.cuenta_proveedor','=','hijo.cuenta_proveedor')
                ->whereNull('hijo.cuenta_proveedor')
                ->select('padre.nombre_proveedor as nombre','padre.direccion as domicilio','padre.telefono as telefonoFijo',
                'padre.organizacion_compras as org_compras','padre.bloqueo_compras','padre.cuenta_proveedor',DB::raw("'A' as id_estado"))
                ->get();

                $oPropietarios = collect($oPropietarios);
                $oPropietarios->chunk(299)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_propietarios')
                    ->insert(json_decode($item, true)
                    );
                });
                // Talleres
                // Actualizamos los registros que ya existen
                DB::connection('sqlsrvCxParque')->table('tra_talleres_gps as hijo')
                ->join('tra_proveedores_vehiculos_servicios as padre','padre.cuenta_proveedor','=','hijo.cuenta_proveedor')
                ->update(['hijo.nombre_proveedor' => DB::raw('padre.nombre_proveedor'), 'hijo.direccion' => DB::raw('padre.direccion'),
                'hijo.telefono' => DB::raw('padre.telefono'),'hijo.org_compras' => DB::raw('padre.organizacion_compras'),
                'hijo.bloqueo_compras' => DB::raw('padre.bloqueo_compras'),'hijo.id_estado' => 'A']);
                // Insertamos los registros
                $oTalleres = DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios as padre')
                ->leftJoin('tra_talleres_gps as hijo','padre.cuenta_proveedor','=','hijo.cuenta_proveedor')
                ->whereNull('hijo.cuenta_proveedor')
                ->select('padre.nombre_proveedor','padre.direccion','padre.telefono',DB::raw("'A' as id_estado"),
                'padre.organizacion_compras as org_compras','padre.bloqueo_compras','padre.cuenta_proveedor')
                ->get();

                $oTalleres = collect($oTalleres);
                $oTalleres->chunk(299)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_talleres_gps')
                    ->insert(json_decode($item,true)
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
