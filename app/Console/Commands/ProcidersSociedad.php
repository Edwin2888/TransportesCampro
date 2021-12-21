<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcidersSociedad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trigger:providersSociedad';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para sincronizar relacion proveedores en propietarios y talleres';

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
    }
}
