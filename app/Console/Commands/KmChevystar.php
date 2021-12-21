<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
use Carbon\Carbon;
use SoapClient;
use App\ModelChevystar;

class KmChevystar extends Command
{
    protected $signature = 'kilometros:kmChevystar';
    protected $description = 'registra kilometraje diario de vehiculos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try{
            // Create the SoapClient instance
            $fechaA = Carbon::now(-5);
            $fechaShort = $fechaA->format('Y-m-d');

            ini_set("soap.wsdl_cache_enabled", "0");
            $url         = "https://worldfleetlog.com/WebFleetStationServices/Online.asmx?wsdl";
            $client     = new SoapClient($url, array("trace" => 1, "exception" => 0));
            $auth = array(
                        'Username' => 'webservice',
                        'Password'   => '123456',
                        'Company'     => 'cam colombia'
                );
            $ns = 'http://tempuri.org/'; //Namespace of the WS.
            
            //Create Soap Header.
            $header = new \SOAPHeader($ns, 'LoginInfo', $auth);
            $client->__setSoapHeaders($header);
            $response = $client->GetCarsInfo();
            $vehiculos = $response->GetCarsInfoResult;
            $vehiculos = $vehiculos->CarOnlinePosItemInfo;
            $ayer = date("Y-m-d",strtotime($fechaShort."-1 days"));
            for ($i=0; $i <sizeof($vehiculos) ; $i++){
                if(isset($vehiculos[$i]->Vehicle_Tool_Tip)){
                    $data = explode("#",$vehiculos[$i]->Vehicle_Tool_Tip);
                    $placa = trim(explode("-",explode("=",$data[0])[1])[0]);
                    $kilometraje = str_replace(",","",explode("=",$data[8])[1]);
                    $tabla = new ModelChevystar;
                    $tabla->placa = $placa;
                    $tabla->fecha = $fechaShort;
                    if (is_numeric($kilometraje)){
                        $tabla->kmTotal = $kilometraje;
                    }
                    $tabla->save();
                    $sql = DB::connection('sqlsrvCxParque')
                        ->table('km_chevystar')
                        ->where('fecha', '=', $ayer)
                        ->where('placa', '=', $placa)->orderBy('kmTotal', 'DESC')->get();
                    if(isset($sql[0]->id) && is_numeric($sql[0]->kmTotal) && is_numeric($kilometraje)){
                        $tabla = ModelChevystar::find($sql[0]->id);
                        $tabla->kilometraje = $kilometraje - $sql[0]->kmTotal;
                        $tabla->save();
                    }
                }
            }
            $e = "Se insertaron ".$i.' registros.';
            $asunto = "Sincronización Kms chevistar";
            $correo = config("app.MailsError");
            Mail::send('emails.success',compact('e'), function($mail) use($asunto,$correo){
                $mail->from("sergio.rodriguezb@engie.com");
                $mail->subject($asunto);
                $mail->to("sergio.rodriguezb@engie.com");
            });
        }catch(\Exception $e){
            \DB::rollBack();
            $asunto = "Error - sincronización Kms chevistar";
            $correo = config("app.MailsError");
            Mail::send('emails.error',compact('e'), function($mail) use($asunto,$correo){
                $mail->from("XBQW48@engie.com");
                $mail->subject($asunto);
                $mail->to("sergio.rodriguezb@engie.com");
            });
        }
    }
}
