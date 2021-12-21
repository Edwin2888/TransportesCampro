<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
class EnviarEventosSSL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:eventossl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviando Notificaciones SSL EVENTOS';

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
        $ssl = DB::Table('ssl_eventos')
            ->where('notificacion',0)
            ->select('id_origen','observaciones','fecha','id_orden','tipo_evento','hora','id','prefijo','conformidad')
            ->get();

        foreach ($ssl as $key => $value) {
            $dbSuper = DB::Table($value->prefijo . '_gop_cuadrilla')
                        ->where('id_lider',$value->id_origen)
                        ->value('id_supervisor');
            //$dbSuper = 79604501;
            if($dbSuper != "" && $dbSuper != null)
            {
                $tokenUser = DB::Table('ins_token_movil')
                        ->where('id_supervidor',$dbSuper)
                        ->value('token_movil');

                if($tokenUser != "" && $tokenUser != null)
                {
                    $liderNombre = DB::Table('rh_personas')
                                ->where('identificacion',$value->id_origen)
                                ->select('nombres','apellidos')
                                ->get()[0];

                    $nom = $liderNombre->nombres . " " . $liderNombre->apellidos; 

                    $title = "Reporte de eventos";

                    $conforme = "";

                    if($value->conformidad == "NC")
                        $conforme = "- NO CONFORME";

                    if($value->conformidad == "C")
                        $conforme = "- CONFORME";

                    $body = "El líder " . $nom  . " a generado un evento tipo " . strtoupper($value->tipo_evento) . " $conforme , Observación: " . $value->observaciones;
                    $sonido = "tono.mp3";
                    $tipo = 0; 
                    if($value->tipo_evento == "panico" || $value->tipo_evento == "PANICO")
                    {
                        $sonido = "panico.mp3";
                        $tipo = 1;  //PANICO
                    }

                    if($value->tipo_evento == "PARE")
                    {
                        $sonido = "pare.mp3";
                        $tipo = 2;  //PARE
                    }

                    if($value->tipo_evento == "autoinspeccion")
                    {
                        $sonido = "auto.mp3";
                        $tipo = 3;  //AUTOINPECCIÓN
                    }

                    if($value->tipo_evento == "preipo")
                    {
                        $sonido = "pre.mp3";
                        $tipo = 4;  //PREIPO
                    }

                    $icon = "fcm_push_icon";
                    $otro = "";
                    $token = $tokenUser;
                    $super = $dbSuper;
                    $res = self::enviaNotificacion($title,$body,$sonido,$icon,$token,$otro,$super,$tipo,$value->tipo_evento,$value->id_origen);
                    $this->info("ENVIO DE NOTIFICACIONES SSL EVENTOS Líder:" . $value->id_origen . " EVENTO:" . strtoupper($value->tipo_evento) . " FECHA:" . $value->fecha . " ID:" . $value->id );

                    //Actualiza SSL
                    DB::Table('ssl_eventos')
                        ->where('id',$value->id)
                        ->update(
                            array(
                            'notificacion' => 1
                            )
                        );
                }
            }
        }
    }

    public function enviaNotificacion($titulo,$cuerpo,$sound,$icono,$para,$otroD,$superviso,$tipo,$tipoEvento,$conforme)
    {
        // https://firebase.google.com/docs/cloud-messaging/http-server-ref#downstream-http-messages-json
        // https://github.com/fechanique/cordova-plugin-fcm
        try{
             $Headers = array(
                'Authorization: key=AAAAatrk_W8:APA91bH2c3TuBj1b2C_nloKLWA24YVo5vspdl-etHANW0vondl3fSVP8WcC9_4xcCX9poRC9YK9T1Ki5cuoJeQ2rhO0N1ftzArrPZoYp0SbiU-aRnfGLVd2wtPZp775GPg390BcTJKpw',
                "Content-type: application/json"
            );

            $url = "https://fcm.googleapis.com/fcm/send";
            //Consultando un grupo

            $title = $titulo;
            $body = $cuerpo;
            $sonido = $sound;
            $icon = $icono;
            $to = $para;
            $otro = $otroD;
            $super = $superviso;

            $data = array(
                'to' =>  $to,
                "notification" => array(
                    "title"=>$title,
                    "body"=>$body,
                    "sound"=>$sonido,
                    "icon"=>$icon,
                    "color" => "#084A9E",
                    "tipo" => $tipo
                  ),
                  "data" => array(
                    "title"=>$title,
                    "body"=>$body,
                    "otro"=>$otro,
                    "tipoE"=>$tipoEvento,
                    "conf"=>$conforme
                  )
                );

            $data = json_encode($data);
           
            //Inicia conexion.
            $conexion = curl_init();
            //Indica un error de conexion
            if (FALSE === $conexion)
                throw new \Exception('failed to initialize');

            //Dirección URL a capturar.
            curl_setopt($conexion,CURLOPT_URL,$url);
             //indicando que es post
            curl_setopt($conexion, CURLOPT_POST, 1);
            //para incluir el header en el output.
            curl_setopt($conexion,CURLOPT_HEADER,0);//No muestra la cabecera en el resultado
            //Envía la cabecera
            curl_setopt($conexion, CURLOPT_HTTPHEADER, $Headers);
            //para devolver el resultado de la transferencia como string del valor de curl_exec() en lugar de mostrarlo directamente.
            curl_setopt($conexion,CURLOPT_RETURNTRANSFER,TRUE);
            //Seteando los datos del webServicecs
            curl_setopt($conexion, CURLOPT_POSTFIELDS, $data);
            //Para que no genere problemas con el certificado SSL
            curl_setopt($conexion, CURLOPT_SSL_VERIFYPEER, false);
            $resultado = curl_exec($conexion);

            if (FALSE === $resultado)
                throw new \Exception(curl_error($conexion), curl_errno($conexion));

            curl_close($conexion);
            $resultado = json_decode($resultado);
                DB::table('ins_notificaciones_envio')
                ->insert(array(
                    array(
                        'supervisor' => $super,
                        'token_enviado' => $to,
                        'titulo' => $title,
                        'cuerpo' => $body,
                        'sonido' => $sonido,
                        'icon' => $icon,
                        'respuesta' => $resultado->success,
                        'error' => $resultado->failure,
                        'enviado' => $resultado->success,
                        'fecha_envio' => $this->fechaALong,
                        'data1Title' => $title,
                        'data2Body' => $body,
                        'data3Otro' => $otro,
                        )
                    ));

            return "1";

        } catch(\Exception $e) {
            return "0";
            //var_dump($e->getMessage());
            /*trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);*/
            
        }
    }
}
