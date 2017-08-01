<?php

namespace App\Console\Commands;

use App\Engine\TestThread;
use Illuminate\Console\Command;

class CorrectAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correct:address';

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
          $districts = app('db')->table('districts')
              ->join('provinces', 'districts.province_id', '=', 'provinces.id')
            ->selectRaw('districts.name as district_name, provinces.name as province_name, districts.id as district_id')
            ->get();

        foreach ($districts as $district) {
            $threadedMethod = new TestThread($district);
            $threadedMethod->start(PTHREADS_INHERIT_NONE);
        }
    }
}
