<?php

namespace App\Console\Commands;
use App\Http\Controllers\FalconLivingController;
use Illuminate\Console\Command;

class FalconLivingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'falcon:hourly';

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
     * @return int
     */
    public function handle()
    {
        $falcon = new FalconLivingController();
        $falcon->index();
        return 0;
    }
}
