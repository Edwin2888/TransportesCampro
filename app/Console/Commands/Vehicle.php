<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;

class Vehicle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'triggers:vehicles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar vehiculos';

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
        Log::info("Iniciando proceso sincronizacion vehiculos");
        $query = "SELECT d1.KFZKZ as placa,r.sociedad,r.numero_activo_fijo,r.numero_activo_fijo,r.subnumero_activo_fijo,
        r.numero_inventario,r.numero_serie,r.cuenta_proveedor,r.contratante,r.numero_contrato,r.TIPO_VINCULACION,
        r.TIPO_VEHICULO,r.centro_logistico,r.centro_costo,r.elemento_pep,r.responsable
            FROM TB_INT_S_AA_VEHICULOS d1
            CROSS APPLY( 
               SELECT TOP 1 BUKRS as sociedad, ANLN1 as numero_activo_fijo,
               ANLN2 as subnumero_activo_fijo, INVNR as numero_inventario,SERNR as numero_serie,
               LIFNR as cuenta_proveedor,LFEAFI as contratante,LVTNR as numero_contrato,
               TIPO_VEHICULO,TIPO_VINCULACION,KOSTL as centro_costo,WERKS as centro_logistico,
               PSPNR as elemento_pep,responsable
               FROM TB_INT_S_AA_VEHICULOS d2
               WHERE d2.KFZKZ = d1.KFZKZ
               ORDER BY TMS_INSERT DESC
            ) AS r
            WHERE STATUS = '1'
        GROUP BY d1.KFZKZ,r.sociedad, r.numero_activo_fijo,r.numero_activo_fijo,r.subnumero_activo_fijo,
        r.numero_inventario,r.numero_serie,r.cuenta_proveedor,r.contratante,r.numero_contrato,r.TIPO_VINCULACION,
        r.TIPO_VEHICULO,r.centro_logistico,r.centro_costo,r.elemento_pep,r.responsable";
        $oVehicles = DB::connection('sqlsrvSap')->Select($query);
        $oVehicles = collect($oVehicles);
        Log::info(count($oVehicles)." registro encontrado(s)");        
        if(count($oVehicles) > 0){
            DB::connection('sqlsrvSap')->beginTransaction();
            try {
                DB::connection('sqlsrvSap')->table('TB_INT_S_AA_VEHICULOS')->where('status',1)
                ->update(['STATUS' => 5, 'TMS_STATUS' => DB::raw('CURRENT_TIMESTAMP')]);

                $rData = new \Illuminate\Http\Request();                
                $rData->oVehicles = $oVehicles;
                Log::info("Envia informacion");
                $response = self::setVehicles($rData);
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
    public function setVehicles(Request $request){
        $nStatusCode = 200;
        $oVehicles = collect($request->oVehicles);
        Log::info('Inicio proceso vehicles');
        $oLog = [];
        if(!is_null($oVehicles)){
            DB::connection('sqlsrvCxParque')->beginTransaction();
            try {
                DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios')->delete();
                $oVehicles->chunk(130)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios')->insert(json_decode($item, true));                
                });
                // Vehiculos
                // Actualizamos los registros que ya existen
                DB::connection('sqlsrvCxParque')->table('tra_maestro as hijo')
                ->join('tra_proveedores_vehiculos_servicios as padre','padre.placa','=','hijo.placa')
                ->join('tra_tipo_vehiculo as tipo','tipo.tipo_vehiculo_sap','=','padre.tipo_vehiculo')
                ->join('tra_tipo_vinculacion as vinculo','vinculo.tipo_vinculo_sap','=','padre.tipo_vinculacion')
                ->update(['hijo.sociedad' => DB::raw('padre.sociedad'), 'hijo.numero_activo_fijo' => DB::raw('padre.numero_activo_fijo'),
                'hijo.subnumero_activo_fijo' => DB::raw('padre.subnumero_activo_fijo'),
                'hijo.numero_inventario' => DB::raw('padre.numero_inventario'),'hijo.responsable' => DB::raw('padre.responsable'),
                'hijo.numero_serie' => DB::raw('padre.numero_serie'),'hijo.elemento_pep' => DB::raw('padre.elemento_pep'),
                'hijo.id_tipo_vehiculo' => DB::raw('tipo.id_tipo_vehiculo'),'hijo.id_tipo_vinculo' => DB::raw('vinculo.id_tipo_vinculo'),
                'hijo.contratante_leasing' => DB::raw('padre.contratante'), 'hijo.numero_contrato' => DB::raw('padre.numero_contrato'),
                'hijo.centro_costo' => DB::raw('padre.centro_costo'), 'hijo.centro_logistico' => DB::raw('padre.centro_logistico'),
                'hijo.id_estado' => 'E01']);
                // Insertamos los registros
                $oVehicles = DB::connection('sqlsrvCxParque')->table('tra_proveedores_vehiculos_servicios as padre')
                ->leftJoin('tra_maestro as hijo','padre.placa','=','hijo.placa')
                ->join('tra_tipo_vehiculo as tipo','tipo.tipo_vehiculo_sap','=','padre.tipo_vehiculo')
                ->join('tra_tipo_vinculacion as vinculo','vinculo.tipo_vinculo_sap','=','padre.tipo_vinculacion')
                ->whereNull('hijo.placa')
                ->select('padre.sociedad as sociedad','padre.numero_activo_fijo','padre.subnumero_activo_fijo','padre.placa',
                'padre.numero_inventario','padre.numero_serie','padre.contratante as contratante_leasing','padre.numero_contrato',
                'padre.centro_costo','padre.centro_logistico','padre.elemento_pep','padre.responsable',
                DB::raw("'E01' as id_estado"),'tipo.id_tipo_vehiculo','vinculo.id_tipo_vinculo')
                ->get();

                $oVehicles = collect($oVehicles);
                $oVehicles->chunk(138)->each(function($item){
                    DB::connection('sqlsrvCxParque')->table('tra_maestro')
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
