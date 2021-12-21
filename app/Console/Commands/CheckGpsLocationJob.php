<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use SoapClient;


class CheckGpsLocationJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gps:chevystart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta la información de CHevyStart de la ubicación GPS de los vehículos';

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
        \Log::info('message');
        $this->info("Iniciando consulta");
        // Create the SoapClient instance 
        $fechaA = Carbon::now('America/Bogota');
        $fechaShort = $fechaA->format('Y-m-d');

        ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
        $url         = "https://worldfleetlog.com/WebFleetStationServices/Online.asmx?wsdl";
        $client     = new SoapClient($url, array("trace" => 1, "exception" => 0));
        $auth = array(
            'Username' => 'webservice',
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

            for ($i = 0; $i < count($vehiculos); $i++) {

                $latitud = $vehiculos[$i]->Vehicle_Latitude;
                $longitud = $vehiculos[$i]->Vehicle_Longitude;
                $angulo = $vehiculos[$i]->Vehicle_Angle;
                $color = $vehiculos[$i]->Vehicle_Color;
                $placa = $vehiculos[$i]->Vehicle_Label;
                $date = $vehiculos[$i]->Event_Time;
                $this->info($placa);

                $id = DB::table('oc_cuadrillas')
                    ->where('placa', $placa)
                    ->get(['cuadrilla_id']);


                foreach ($id as $key => $value) {
                    DB::table('oc_recorrido')
                        ->insert(array(
                            array(
                                'movil_id' => $value->cuadrilla_id,
                                'fecha_actualizacion' => $fechaA->toDateTimeString(),
                                'latitud' => $latitud,
                                'longitud' => $longitud,
                                'type_entry' => 1,
                            )
                        ));
                    $this->info("Insercción realizada OC");
                }


                $id = DB::table('tp_cuadrillas')
                    ->where('placa', $placa)
                    ->get(['cuadrillas_id']);


                foreach ($id as $key => $value) {
                    DB::table('tp_recorridos')
                        ->insert(array(
                            array(
                                'usuario_movil' => $value->cuadrillas_id,
                                'fecha' => $fechaA->toDateTimeString(),
                                'hora' => explode(" ", $fechaA->toDateTimeString())[1],
                                'latitud' => $latitud,
                                'longitud' => $longitud,
                                'type_entry' => 1,
                            )
                        ));

                    $this->info("Insercción realizada TP");
                }
            }
        } catch (SoapFault $e) {

            $this->info("ERROR");
            $this->info($e);
        }
    }
}
