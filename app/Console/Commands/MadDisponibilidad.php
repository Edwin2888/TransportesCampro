<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MadDisponibilidad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mad:disponibilidad';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Inserta información disponibilidad transporte';

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
        //  
        $cad = "EXEC sp_tra_mad_disponibilidad";
        $mad = \DB::connection('sqlsrvCxParque')
                ->select("SET NOCOUNT ON;" . $cad);
        $this->info($mad[0]->respuesta);

        $cad = "EXEC sp_tra_inserta_ubicacion_vehiculos";
        $mad = \DB::connection('sqlsrvCxParque')
                ->select("SET NOCOUNT ON;" . $cad);
        $this->info($mad[0]->respuesta);

        
    }
}
