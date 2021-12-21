<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use SoapClient;

class ConsultaOdometroChevy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:chevyodometro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // Create the SoapClient instance 
        $fechaA = Carbon::now(-5);
        $fechaShort = $fechaA->format('Y-m-d');

        ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
        $url         = "https://worldfleetlog.com/WebFleetStationServices/Online.asmx?wsdl"; 
        $client     = new SoapClient($url, array("trace" => 1, "exception" => 0)); 
        $auth = array(
                    'Username' => 'z', 
                      'Password'   => '123456', 
                      'Company'     => 'cam colombia'
                
            );
        $ns = 'http://tempuri.org/'; //Namespace of the WS. 
        try {
            //Create Soap Header.        
            $header = new \SOAPHeader($ns, 'LoginInfo', $auth);  
            $client->__setSoapHeaders($header);
            $response = $client->GetCarsInfo();
            $vehiculos = $response->GetCarsInfoResult;
            $vehiculos = $vehiculos->CarOnlinePosItemInfo;

            DB::connection('sqlsrvCxParque')
            ->table('tra_vehiculo_odometro_ultrack')
            ->insert(
                    array(
                        array(
                            'des' => 'Consulta Odometro CHEVYSTAR ' . $fechaA->toDateTimeString() . " CANTIDAD DE PLACAS CONSULTADOS: " . count($vehiculos),
                            'fecha_consulta' => $fechaShort
                            )
                        )
                );

            //for ($i=0; $i < 1; $i++) { 
            for ($i=0; $i < count($vehiculos); $i++) { 
                $latitud = $vehiculos[$i]->Vehicle_Latitude;
                $longitud = $vehiculos[$i]->Vehicle_Longitude;
                $angulo = $vehiculos[$i]->Vehicle_Angle;
                $color = $vehiculos[$i]->Vehicle_Color;
                $data = explode("#",$vehiculos[$i]->Vehicle_Tool_Tip);
                    $placa = trim(explode("-",explode("=",$data[0])[1])[0]);
                    $kilometraje = str_replace(",","",explode("=",$data[8])[1]);
                    
                    if(DB::connection('sqlsrvCxParque')
                        ->table('tra_maestro')
                        ->where('placa',$placa)
                        ->count() == 0)
                        continue;

                    DB::connection('sqlsrvCxParque')
                        ->table('tra_vehiculo_odometro')
                        ->insert(array(
                            array(
                                'placa' => $placa,
                                'fecha_servidor' => $fechaA->toDateTimeString(),
                                'fecha' => $fechaShort,
                                'observaciones' => "CONSULTA ODOMETRO CHEVYSTAR",
                                'kilometraje' => $kilometraje,
                                'usuario' => "CHEVYSTAR",
                                'tipo' => 2
                                )
                            ));
                    //echo "PLACA:$placa-KILOMETRAJE:$kilometraje<br>";
                $label = $vehiculos[$i]->Vehicle_Label;
                $date = $vehiculos[$i]->Event_Time;
                $regio = $vehiculos[$i]->REGION_NAME;
            }

            // CarOnlinePosItemInfo
        } catch (SoapFault $e) {
            DB::connection('sqlsrvCxParque')
            ->table('tra_vehiculo_odometro_ultrack')
            ->insert(
                    array(
                        array(
                            'des' => 'ERROR Consulta Odometro CHEVYSTAR ' . $fechaA->toDateTimeString() . " - " . $e->getMessage(),
                            'fecha_consulta' => $fechaShort
                            )
                        )
                );
        }
    }
}
